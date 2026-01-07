<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolHeadLeave;
use App\Models\TeacherLeave;
use App\Models\NonTeachingLeave;
use App\Models\Personnel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LeaveManagementController extends Controller
{
    /**
     * Show the leave management interface for admins
     */
    public function index(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $search = $request->input('search');
        $role = $request->input('role');

        // Get users with their leave data based on role filter
        $usersQuery = User::whereIn('role', ['school_head', 'teacher', 'non_teaching'])
            ->with(['personnel.school'])
            ->when($search, function ($query, $search) {
                $query->whereHas('personnel', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('middle_name', 'like', "%{$search}%");
                });
            })
            ->when($role, function ($query, $role) {
                $query->where('role', $role);
            });

        // Paginate the users
        $users = $usersQuery->paginate(20);

        // Get all leave data for the selected year
        $leaveData = [];
        foreach ($users as $user) {
            if ($user->personnel) {
                $personnelLeaves = $this->getPersonnelLeaveData($user, $year);

                $leaveData[] = [
                    'personnel' => $user->personnel,
                    'user' => $user,
                    'leaves' => $personnelLeaves,
                ];
            }
        }

        // Calculate statistics
        $stats = $this->calculateLeaveStats($year);

        return view('admin.leave_management', compact('leaveData', 'year', 'role', 'users', 'stats'));
    }

    /**
     * Get leave data for a specific personnel
     */
    private function getPersonnelLeaveData($user, $year)
    {
        $personnel = $user->personnel;
        $personnelLeaves = [];

        if ($user->role === 'school_head') {
            $leaves = SchoolHeadLeave::where('school_head_id', $personnel->id)
                ->where('year', $year)
                ->get()
                ->keyBy('leave_type');

            $defaultLeaves = SchoolHeadLeave::defaultLeaves(
                $personnel->is_solo_parent ?? false,
                $personnel->sex ?? null
            );

            foreach ($defaultLeaves as $type => $max) {
                $leave = $leaves->get($type);
                $personnelLeaves[] = [
                    'type' => $type,
                    'max' => $max,
                    'available' => $leave ? $leave->available : $max,
                    'used' => $leave ? $leave->used : 0,
                    'record_id' => $leave ? $leave->id : null,
                ];
            }
        } elseif ($user->role === 'teacher') {
            $leaves = TeacherLeave::where('teacher_id', $personnel->id)
                ->where('year', $year)
                ->get()
                ->keyBy('leave_type');

            // Calculate years of service for default leaves
            $yearsOfService = $personnel->employment_start ?
                Carbon::parse($personnel->employment_start)->diffInYears(Carbon::now()) : 0;

            $defaultLeaves = TeacherLeave::defaultLeaves(
                $yearsOfService,
                $personnel->is_solo_parent ?? false,
                $personnel->sex ?? null
            );

            foreach ($defaultLeaves as $type => $max) {
                $leave = $leaves->get($type);
                $personnelLeaves[] = [
                    'type' => $type,
                    'max' => $max,
                    'available' => $leave ? $leave->available : $max,
                    'used' => $leave ? $leave->used : 0,
                    'record_id' => $leave ? $leave->id : null,
                ];
            }
        } elseif ($user->role === 'non_teaching') {
            $leaves = NonTeachingLeave::where('non_teaching_id', $personnel->id)
                ->where('year', $year)
                ->get()
                ->keyBy('leave_type');

            // Calculate years of service for default leaves
            $yearsOfService = $personnel->employment_start ?
                Carbon::parse($personnel->employment_start)->diffInYears(Carbon::now()) : 0;
            $civilStatus = $personnel->civil_status ?? null;

            $defaultLeaves = NonTeachingLeave::defaultLeaves(
                $yearsOfService,
                $personnel->is_solo_parent ?? false,
                $personnel->sex ?? null,
                $civilStatus
            );

            foreach ($defaultLeaves as $type => $max) {
                $leave = $leaves->get($type);
                $personnelLeaves[] = [
                    'type' => $type,
                    'max' => $max,
                    'available' => $leave ? $leave->available : $max,
                    'used' => $leave ? $leave->used : 0,
                    'record_id' => $leave ? $leave->id : null,
                ];
            }
        }

        return $personnelLeaves;
    }

    /**
     * Calculate leave statistics for dashboard
     */
    private function calculateLeaveStats($year)
    {
        $stats = [
            'total_personnel' => 0,
            'total_vacation_available' => 0,
            'total_sick_available' => 0,
            'low_vacation_count' => 0,
            'low_sick_count' => 0,
            'no_vacation_count' => 0,
            'no_sick_count' => 0,
        ];

        $users = User::whereIn('role', ['school_head', 'teacher', 'non_teaching'])
            ->with(['personnel'])
            ->get();

        foreach ($users as $user) {
            if (!$user->personnel) continue;

            $stats['total_personnel']++;
            $leaves = $this->getPersonnelLeaveData($user, $year);

            foreach ($leaves as $leave) {
                if ($leave['type'] === 'Vacation Leave') {
                    $stats['total_vacation_available'] += $leave['available'];
                    if ($leave['available'] <= 0) {
                        $stats['no_vacation_count']++;
                    } elseif ($leave['available'] <= 3) {
                        $stats['low_vacation_count']++;
                    }
                } elseif ($leave['type'] === 'Sick Leave') {
                    $stats['total_sick_available'] += $leave['available'];
                    if ($leave['available'] <= 0) {
                        $stats['no_sick_count']++;
                    } elseif ($leave['available'] <= 3) {
                        $stats['low_sick_count']++;
                    }
                }
            }
        }

        return $stats;
    }

    /**
     * Add available leave days for a specific personnel and leave type
     */
    public function addLeave(Request $request)
    {
        $request->validate([
            'personnel_id' => 'required|exists:personnels,id',
            'leave_type' => 'required|string|in:Vacation Leave,Sick Leave',
            'days_to_add' => 'required|integer|min:1|max:365',
            'reason' => 'nullable|string|max:255',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        try {
            $personnel = Personnel::findOrFail($request->personnel_id);

            // Find the user associated with this personnel
            $user = User::whereHas('personnel', function ($query) use ($personnel) {
                $query->where('id', $personnel->id);
            })->first();

            if (!$user) {
                return redirect()->back()->withErrors([
                    'error' => 'No user account found for this personnel.'
                ]);
            }

            // Calculate years of service if needed
            $yearsOfService = $personnel->employment_start ?
                Carbon::parse($personnel->employment_start)->diffInYears(Carbon::now()) : 0;

            // Handle different personnel types
            if ($user->role === 'school_head') {
                $leaveRecord = SchoolHeadLeave::firstOrCreate(
                    [
                        'school_head_id' => $personnel->id,
                        'leave_type' => $request->leave_type,
                        'year' => $request->year,
                    ],
                    [
                        'available' => 0,
                        'used' => 0,
                        'ctos_earned' => 0,
                        'remarks' => 'Manually initialized',
                    ]
                );
            } elseif ($user->role === 'teacher') {
                $defaultLeaves = TeacherLeave::defaultLeaves(
                    $yearsOfService,
                    $personnel->is_solo_parent ?? false,
                    $personnel->sex ?? null
                );

                $leaveRecord = TeacherLeave::firstOrCreate(
                    [
                        'teacher_id' => $personnel->id,
                        'leave_type' => $request->leave_type,
                        'year' => $request->year,
                    ],
                    [
                        'available' => $defaultLeaves[$request->leave_type] ?? 0,
                        'used' => 0,
                        'remarks' => 'Manually initialized',
                    ]
                );
            } elseif ($user->role === 'non_teaching') {
                $defaultLeaves = NonTeachingLeave::defaultLeaves(
                    $yearsOfService,
                    $personnel->is_solo_parent ?? false,
                    $personnel->sex ?? null
                );

                $leaveRecord = NonTeachingLeave::firstOrCreate(
                    [
                        'non_teaching_id' => $personnel->id,
                        'leave_type' => $request->leave_type,
                        'year' => $request->year,
                    ],
                    [
                        'available' => $defaultLeaves[$request->leave_type] ?? 0,
                        'used' => 0,
                        'remarks' => 'Manually initialized',
                    ]
                );
            } else {
                return redirect()->back()->withErrors([
                    'error' => 'Invalid user role for leave management.'
                ]);
            }

            // Add the requested days to available balance
            $previousAvailable = $leaveRecord->available;
            $leaveRecord->available += $request->days_to_add;

            // Update remarks to include manual addition info
            $addedBy = Auth::user()->personnel ?
                Auth::user()->personnel->first_name . ' ' . Auth::user()->personnel->last_name :
                'Admin';

            $newRemark = "+" . $request->days_to_add . " days added by " . $addedBy . " on " . now()->format('M d, Y');
            if ($request->reason) {
                $newRemark .= " (Reason: " . $request->reason . ")";
            }

            if ($leaveRecord->remarks && !in_array($leaveRecord->remarks, ['Auto-initialized', 'Manually initialized'])) {
                $leaveRecord->remarks = $leaveRecord->remarks . "; " . $newRemark;
            } else {
                $leaveRecord->remarks = $newRemark;
            }

            $leaveRecord->save();

            // Log the action
            Log::info('Manual leave addition performed', [
                'admin_user_id' => Auth::id(),
                'personnel_id' => $personnel->id,
                'personnel_name' => $personnel->first_name . ' ' . $personnel->last_name,
                'personnel_role' => $user->role,
                'leave_type' => $request->leave_type,
                'days_added' => $request->days_to_add,
                'previous_available' => $previousAvailable,
                'new_available' => $leaveRecord->available,
                'reason' => $request->reason,
                'year' => $request->year,
            ]);

            return redirect()->back()->with('success',
                "Successfully added {$request->days_to_add} days to {$personnel->first_name} {$personnel->last_name}'s {$request->leave_type} balance. " .
                "Previous balance: {$previousAvailable} days, New balance: {$leaveRecord->available} days."
            );

        } catch (\Exception $e) {
            Log::error('Failed to add leave days', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'admin_user_id' => Auth::id(),
            ]);

            return redirect()->back()->withErrors([
                'error' => 'Failed to add leave days. Please try again.'
            ]);
        }
    }

    /**
     * Deduct available leave days for a specific personnel and leave type
     */
    public function deductLeave(Request $request)
    {
        $request->validate([
            'personnel_id' => 'required|exists:personnels,id',
            'leave_type' => 'required|string|in:Vacation Leave,Sick Leave',
            'days_to_deduct' => 'required|numeric|min:0.5|max:365',
            'reason' => 'required|string|max:255',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        try {
            $personnel = Personnel::findOrFail($request->personnel_id);

            // Find the user associated with this personnel
            $user = User::whereHas('personnel', function ($query) use ($personnel) {
                $query->where('id', $personnel->id);
            })->first();

            if (!$user) {
                return redirect()->back()->withErrors([
                    'error' => 'No user account found for this personnel.'
                ]);
            }

            // Calculate years of service if needed
            $yearsOfService = $personnel->employment_start ?
                Carbon::parse($personnel->employment_start)->diffInYears(Carbon::now()) : 0;

            // Handle different personnel types
            if ($user->role === 'school_head') {
                $leaveRecord = SchoolHeadLeave::firstOrCreate(
                    [
                        'school_head_id' => $personnel->id,
                        'leave_type' => $request->leave_type,
                        'year' => $request->year,
                    ],
                    [
                        'available' => 0,
                        'used' => 0,
                        'ctos_earned' => 0,
                        'remarks' => 'Manually initialized',
                    ]
                );
            } elseif ($user->role === 'teacher') {
                $defaultLeaves = TeacherLeave::defaultLeaves(
                    $yearsOfService,
                    $personnel->is_solo_parent ?? false,
                    $personnel->sex ?? null
                );

                $leaveRecord = TeacherLeave::firstOrCreate(
                    [
                        'teacher_id' => $personnel->id,
                        'leave_type' => $request->leave_type,
                        'year' => $request->year,
                    ],
                    [
                        'available' => $defaultLeaves[$request->leave_type] ?? 0,
                        'used' => 0,
                        'remarks' => 'Manually initialized',
                    ]
                );
            } elseif ($user->role === 'non_teaching') {
                $defaultLeaves = NonTeachingLeave::defaultLeaves(
                    $yearsOfService,
                    $personnel->is_solo_parent ?? false,
                    $personnel->sex ?? null
                );

                $leaveRecord = NonTeachingLeave::firstOrCreate(
                    [
                        'non_teaching_id' => $personnel->id,
                        'leave_type' => $request->leave_type,
                        'year' => $request->year,
                    ],
                    [
                        'available' => $defaultLeaves[$request->leave_type] ?? 0,
                        'used' => 0,
                        'remarks' => 'Manually initialized',
                    ]
                );
            } else {
                return redirect()->back()->withErrors([
                    'error' => 'Invalid user role for leave management.'
                ]);
            }

            // Check if deduction would result in negative balance
            if ($leaveRecord->available < $request->days_to_deduct) {
                return redirect()->back()->withErrors([
                    'error' => 'Cannot deduct more days than available. Current balance: ' . $leaveRecord->available . ' days.'
                ]);
            }

            // Deduct the requested days from available balance
            $previousAvailable = $leaveRecord->available;
            $leaveRecord->available -= $request->days_to_deduct;

            // Update remarks to include manual deduction info
            $deductedBy = Auth::user()->personnel ?
                Auth::user()->personnel->first_name . ' ' . Auth::user()->personnel->last_name :
                'Admin';

            $newRemark = "-" . $request->days_to_deduct . " days deducted by " . $deductedBy . " on " . now()->format('M d, Y') . " (Reason: " . $request->reason . ")";

            if ($leaveRecord->remarks && !in_array($leaveRecord->remarks, ['Auto-initialized', 'Manually initialized'])) {
                $leaveRecord->remarks = $leaveRecord->remarks . "; " . $newRemark;
            } else {
                $leaveRecord->remarks = $newRemark;
            }

            $leaveRecord->save();

            // Log the action
            Log::info('Manual leave deduction performed', [
                'admin_user_id' => Auth::id(),
                'personnel_id' => $personnel->id,
                'personnel_name' => $personnel->first_name . ' ' . $personnel->last_name,
                'personnel_role' => $user->role,
                'leave_type' => $request->leave_type,
                'days_deducted' => $request->days_to_deduct,
                'previous_available' => $previousAvailable,
                'new_available' => $leaveRecord->available,
                'reason' => $request->reason,
                'year' => $request->year,
            ]);

            return redirect()->back()->with('success',
                "Successfully deducted {$request->days_to_deduct} days from {$personnel->first_name} {$personnel->last_name}'s {$request->leave_type} balance. " .
                "Previous balance: {$previousAvailable} days, New balance: {$leaveRecord->available} days."
            );

        } catch (\Exception $e) {
            Log::error('Failed to deduct leave days', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'admin_user_id' => Auth::id(),
            ]);

            return redirect()->back()->withErrors([
                'error' => 'Failed to deduct leave days. Please try again.'
            ]);
        }
    }

    /**
     * Get leave details for a specific personnel via AJAX
     */
    public function getPersonnelLeaves(Request $request)
    {
        $personnelId = $request->input('personnel_id');
        $year = $request->input('year', Carbon::now()->year);

        $personnel = Personnel::find($personnelId);
        if (!$personnel) {
            return response()->json(['error' => 'Personnel not found'], 404);
        }

        $user = User::whereHas('personnel', function ($query) use ($personnel) {
            $query->where('id', $personnel->id);
        })->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $leaveData = [];

        foreach (['Vacation Leave', 'Sick Leave'] as $leaveType) {
            $leave = null;

            if ($user->role === 'school_head') {
                $leave = SchoolHeadLeave::where('school_head_id', $personnelId)
                    ->where('leave_type', $leaveType)
                    ->where('year', $year)
                    ->first();
            } elseif ($user->role === 'teacher') {
                $leave = TeacherLeave::where('teacher_id', $personnelId)
                    ->where('leave_type', $leaveType)
                    ->where('year', $year)
                    ->first();
            } elseif ($user->role === 'non_teaching') {
                $leave = NonTeachingLeave::where('non_teaching_id', $personnelId)
                    ->where('leave_type', $leaveType)
                    ->where('year', $year)
                    ->first();
            }

            $leaveData[$leaveType] = [
                'available' => $leave ? $leave->available : 0,
                'used' => $leave ? $leave->used : 0,
                'remarks' => $leave ? $leave->remarks : '',
            ];
        }

        return response()->json([
            'personnel' => [
                'id' => $personnel->id,
                'name' => $personnel->first_name . ' ' . $personnel->last_name,
                'role' => ucfirst(str_replace('_', ' ', $user->role)),
                'school' => $personnel->school ? $personnel->school->school_name : 'No School Assigned',
            ],
            'leaves' => $leaveData,
        ]);
    }
}
