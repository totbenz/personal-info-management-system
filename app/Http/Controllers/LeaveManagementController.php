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
                // Update the database first
                $this->updateLeaveDataInDatabase($user->personnel, $user->role, $year);

                // Then get the updated data
                $personnelLeaves = $this->getPersonnelLeaveDataFromDatabase($user, $year);

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
     * Update leave data in database for a personnel
     */
    private function updateLeaveDataInDatabase($personnel, $role, $year)
    {
        // First, run monthly accrual for Sick Leave only (Vacation Leave removed for teachers)
        if ($role === 'teacher' || $role === 'non_teaching') {
            $accrualService = new \App\Services\MonthlyLeaveAccrualService();
            if ($role === 'teacher') {
                $accrualService->updateTeacherLeaveRecords($personnel->id, $year);
            } else {
                $accrualService->updateNonTeachingLeaveRecords($personnel->id, $year);
            }
        } elseif ($role === 'school_head') {
            // For school heads, use monthly accrual for Vacation and Sick leave
            $this->accrueLeaveForSchoolHead($personnel, $year);
        }

        // Then, update fixed allocation leaves
        $this->updateFixedAllocationLeaves($personnel, $role, $year);
    }

    /**
     * Accrue monthly leave for school heads
     */
    private function accrueLeaveForSchoolHead($personnel, $year)
    {
        $leaveTypes = ['Vacation Leave', 'Sick Leave'];

        foreach ($leaveTypes as $leaveType) {
            $leave = \App\Models\SchoolHeadLeave::firstOrCreate([
                'school_head_id' => $personnel->id,
                'leave_type' => $leaveType,
                'year' => $year,
            ], [
                'available' => 0,
                'used' => 0,
                'ctos_earned' => 0,
                'remarks' => 'Auto-initialized',
            ]);

            // Calculate monthly accrual (same as other roles)
            $eligibleMonths = $this->getEligibleMonths($personnel, $year);
            $accruedTotal = 1.25 * $eligibleMonths;

            $currentTotal = (float) $leave->available + (float) $leave->used;
            $tolerance = 0.01;

            if ($leave->wasRecentlyCreated || ($currentTotal + $tolerance) < $accruedTotal) {
                if ($leave->wasRecentlyCreated) {
                    $leave->available = $accruedTotal;
                    $leave->remarks = "Auto-accrued: {$eligibleMonths} month(s) × 1.25 = {$accruedTotal} (" . now()->format('M d, Y') . ")";
                } else {
                    $difference = $accruedTotal - $currentTotal;
                    $leave->available += $difference;
                    $leave->remarks = ($leave->remarks ?? '') . " | +{$difference} (monthly accrual " . now()->format('M d, Y') . ")";
                }
                $leave->save();
            }
        }
    }

    /**
     * Get eligible months for accrual calculation
     */
    private function getEligibleMonths($personnel, $year)
    {
        if (!$personnel->employment_start) {
            return 0;
        }

        $employmentStart = Carbon::parse($personnel->employment_start);
        $currentDate = Carbon::now();

        $startDate = Carbon::create($year, 1, 1);
        if ($employmentStart->year === $year && $employmentStart->month > 1) {
            $startDate = $employmentStart->copy()->startOfMonth();
        }

        $endDate = Carbon::create($year, 12, 31);
        if ($currentDate->year === $year) {
            $endDate = $currentDate->copy()->endOfMonth();
        }

        if ($startDate->gt($endDate)) {
            return 0;
        }

        $months = $startDate->diffInMonths($endDate);

        if ($currentDate->year === $year && $currentDate->day >= 1) {
            $months = min($months + 1, 12);
        }

        return min($months, 12);
    }

    /**
     * Update fixed allocation leaves (non-accruing)
     */
    private function updateFixedAllocationLeaves($personnel, $role, $year)
    {
        if ($role === 'school_head') {
            $fixedLeaves = [
                'MANDATORY FORCED LEAVE' => 5,
                'SPECIAL PRIVILEGE LEAVE' => 3,
                'MATERNITY LEAVE' => ($personnel->sex === 'female') ?
                    (($personnel->is_solo_parent ?? false) ? 120 : 105) : 0,
                'PATERNITY LEAVE' => ($personnel->sex === 'male') ? 7 : 0,
                'SOLO PARENT LEAVE' => ($personnel->is_solo_parent ?? false) ? 7 : 0,
                'STUDY LEAVE' => 180,
                'VAWC LEAVE' => 10,
                'REHABILITATION PRIVILEGE' => 180,
                'SPECIAL LEAVE BENEFITS FOR WOMEN' => ($personnel->sex === 'female') ? 60 : 0,
                'SPECIAL EMERGENCY (CALAMITY LEAVE)' => 1000,
                'ADOPTION LEAVE' => ($personnel->sex === 'female') ? 60 : (($personnel->sex === 'male') ? 7 : 0),
            ];

            foreach ($fixedLeaves as $leaveType => $maxDays) {
                $leave = \App\Models\SchoolHeadLeave::firstOrCreate([
                    'school_head_id' => $personnel->id,
                    'leave_type' => $leaveType,
                    'year' => $year,
                ], [
                    'available' => 0,
                    'used' => 0,
                    'ctos_earned' => 0,
                    'remarks' => 'Auto-initialized',
                ]);

                if ($leave->available == 0) {
                    $leave->available = $maxDays;
                    $leave->remarks = 'Updated with fixed allocation on ' . now()->format('M d, Y');
                    $leave->save();
                }
            }
        } elseif ($role === 'teacher') {
            $fixedLeaves = [
                'MATERNITY LEAVE' => ($personnel->sex === 'female') ?
                    (($personnel->is_solo_parent ?? false) ? 120 : 105) : 0,
                'PATERNITY LEAVE' => ($personnel->sex === 'male') ? 7 : 0,
                'SOLO PARENT LEAVE' => ($personnel->is_solo_parent ?? false) ? 7 : 0,
                'STUDY LEAVE' => 180,
                'VAWC LEAVE' => 10,
                'REHABILITATION PRIVILEGE' => 180,
                'SPECIAL LEAVE BENEFITS FOR WOMEN' => ($personnel->sex === 'female') ? 60 : 0,
                'SPECIAL EMERGENCY (CALAMITY LEAVE)' => 1000,
                'ADOPTION LEAVE' => ($personnel->sex === 'female') ? 60 : (($personnel->sex === 'male') ? 7 : 0),
            ];

            foreach ($fixedLeaves as $leaveType => $maxDays) {
                $leave = \App\Models\TeacherLeave::firstOrCreate([
                    'teacher_id' => $personnel->id,
                    'leave_type' => $leaveType,
                    'year' => $year,
                ], [
                    'available' => 0,
                    'used' => 0,
                    'remarks' => 'Auto-initialized',
                ]);

                if ($leave->available == 0) {
                    $leave->available = $maxDays;
                    $leave->remarks = 'Updated with fixed allocation on ' . now()->format('M d, Y');
                    $leave->save();
                }
            }
        } elseif ($role === 'non_teaching') {
            $fixedLeaves = [
                'MANDATORY FORCED LEAVE' => 5,
                'SPECIAL PRIVILEGE LEAVE' => 3,
                'MATERNITY LEAVE' => ($personnel->sex === 'female') ?
                    (($personnel->is_solo_parent ?? false) ? 120 : 105) : 0,
                'PATERNITY LEAVE' => ($personnel->sex === 'male') ? 7 : 0,
                'SOLO PARENT LEAVE' => ($personnel->is_solo_parent ?? false) ? 7 : 0,
                'STUDY LEAVE' => 180,
                'VAWC LEAVE' => 10,
                'REHABILITATION PRIVILEGE' => 180,
                'SPECIAL LEAVE BENEFITS FOR WOMEN' => ($personnel->sex === 'female') ? 60 : 0,
                'SPECIAL EMERGENCY (CALAMITY LEAVE)' => 1000,
                'ADOPTION LEAVE' => ($personnel->sex === 'female') ? 60 :
                    (($personnel->sex === 'male' && $personnel->civil_status === 'single') ? 60 : 7),
            ];

            foreach ($fixedLeaves as $leaveType => $maxDays) {
                $leave = \App\Models\NonTeachingLeave::firstOrCreate([
                    'non_teaching_id' => $personnel->id,
                    'leave_type' => $leaveType,
                    'year' => $year,
                ], [
                    'available' => 0,
                    'used' => 0,
                    'remarks' => 'Auto-initialized',
                ]);

                if ($leave->available == 0) {
                    $leave->available = $maxDays;
                    $leave->remarks = 'Updated with fixed allocation on ' . now()->format('M d, Y');
                    $leave->save();
                }
            }
        }
    }

    /**
     * Get adoption leave days based on gender and civil status
     */
    private function getAdoptionLeaveDays($personnel)
    {
        if ($personnel->sex === 'female') {
            return 60;
        } elseif ($personnel->sex === 'male') {
            return ($personnel->civil_status === 'single') ? 60 : 7;
        }
        return 0;
    }

    /**
     * Get leave data directly from database (no calculations)
     */
    private function getPersonnelLeaveDataFromDatabase($user, $year)
    {
        $personnel = $user->personnel;
        $personnelLeaves = [];

        if ($user->role === 'school_head') {
            $leaves = SchoolHeadLeave::where('school_head_id', $personnel->id)
                ->where('year', $year)
                ->get();

            foreach ($leaves as $leave) {
                $personnelLeaves[] = [
                    'type' => $leave->leave_type,
                    'max' => $leave->available, // Using available as max since it's the actual allocated value
                    'available' => $leave->available,
                    'used' => $leave->used,
                    'record_id' => $leave->id,
                ];
            }
        } elseif ($user->role === 'teacher') {
            $leaves = TeacherLeave::where('teacher_id', $personnel->id)
                ->where('year', $year)
                ->get();

            foreach ($leaves as $leave) {
                $personnelLeaves[] = [
                    'type' => $leave->leave_type,
                    'max' => $leave->available, // Using available as max since it's the actual allocated value
                    'available' => $leave->available,
                    'used' => $leave->used,
                    'record_id' => $leave->id,
                ];
            }
        } elseif ($user->role === 'non_teaching') {
            $leaves = NonTeachingLeave::where('non_teaching_id', $personnel->id)
                ->where('year', $year)
                ->get();

            foreach ($leaves as $leave) {
                $personnelLeaves[] = [
                    'type' => $leave->leave_type,
                    'max' => $leave->available, // Using available as max since it's the actual allocated value
                    'available' => $leave->available,
                    'used' => $leave->used,
                    'record_id' => $leave->id,
                ];
            }
        }

        return $personnelLeaves;
    }

    /**
     * Update personnel leave data in database based on calculations
     */
    public function updatePersonnelLeaveData(Request $request)
    {
        $request->validate([
            'personnel_id' => 'required|exists:personnels,id',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        try {
            $personnel = Personnel::findOrFail($request->personnel_id);
            $year = $request->year;

            // Find the user associated with this personnel
            $user = User::where('personnel_id', $personnel->id)->first();

            if (!$user) {
                return response()->json(['error' => 'No user account found for this personnel.'], 404);
            }

            // Skip admin role
            if ($user->role === 'admin') {
                return response()->json(['error' => 'Admin users do not have leave allocations.'], 400);
            }

            $updatedLeaves = [];

            if ($user->role === 'school_head') {
                $defaultLeaves = SchoolHeadLeave::defaultLeaves(
                    $personnel->is_solo_parent ?? false,
                    $personnel->sex ?? null
                );

                foreach ($defaultLeaves as $leaveType => $maxDays) {
                    $leave = SchoolHeadLeave::firstOrCreate([
                        'school_head_id' => $personnel->id,
                        'leave_type' => $leaveType,
                        'year' => $year,
                    ], [
                        'available' => 0,
                        'used' => 0,
                        'ctos_earned' => 0,
                        'remarks' => 'Auto-initialized',
                    ]);

                    // Only update if available is 0 to preserve manually adjusted values
                    if ($leave->available == 0) {
                        $leave->available = $maxDays;
                        $leave->remarks = 'Updated with default allocation on ' . now()->format('M d, Y');
                        $leave->save();
                        $updatedLeaves[] = $leaveType;
                    }
                }
            } elseif ($user->role === 'teacher') {
                $yearsOfService = $personnel->employment_start ?
                    Carbon::parse($personnel->employment_start)->diffInYears(Carbon::now()) : 0;

                $defaultLeaves = TeacherLeave::defaultLeaves(
                    $yearsOfService,
                    $personnel->is_solo_parent ?? false,
                    $personnel->sex ?? null
                );

                foreach ($defaultLeaves as $leaveType => $maxDays) {
                    $leave = TeacherLeave::firstOrCreate([
                        'teacher_id' => $personnel->id,
                        'leave_type' => $leaveType,
                        'year' => $year,
                    ], [
                        'available' => 0,
                        'used' => 0,
                        'remarks' => 'Auto-initialized',
                    ]);

                    // Only update if available is 0 to preserve manually adjusted values
                    if ($leave->available == 0) {
                        $leave->available = $maxDays;
                        $leave->remarks = 'Updated with default allocation on ' . now()->format('M d, Y');
                        $leave->save();
                        $updatedLeaves[] = $leaveType;
                    }
                }
            } elseif ($user->role === 'non_teaching') {
                $yearsOfService = $personnel->employment_start ?
                    Carbon::parse($personnel->employment_start)->diffInYears(Carbon::now()) : 0;

                $defaultLeaves = NonTeachingLeave::defaultLeaves(
                    $yearsOfService,
                    $personnel->is_solo_parent ?? false,
                    $personnel->sex ?? null,
                    $personnel->civil_status ?? null
                );

                foreach ($defaultLeaves as $leaveType => $maxDays) {
                    $leave = NonTeachingLeave::firstOrCreate([
                        'non_teaching_id' => $personnel->id,
                        'leave_type' => $leaveType,
                        'year' => $year,
                    ], [
                        'available' => 0,
                        'used' => 0,
                        'remarks' => 'Auto-initialized',
                    ]);

                    // Only update if available is 0 to preserve manually adjusted values
                    if ($leave->available == 0) {
                        $leave->available = $maxDays;
                        $leave->remarks = 'Updated with default allocation on ' . now()->format('M d, Y');
                        $leave->save();
                        $updatedLeaves[] = $leaveType;
                    }
                }
            }

            // Log the action
            Log::info('Leave data updated in database', [
                'admin_user_id' => Auth::id(),
                'personnel_id' => $personnel->id,
                'personnel_name' => $personnel->first_name . ' ' . $personnel->last_name,
                'personnel_role' => $user->role,
                'year' => $year,
                'updated_leaves' => $updatedLeaves,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Leave data updated successfully',
                'updated_leaves' => $updatedLeaves,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update leave data', [
                'error' => $e->getMessage(),
                'personnel_id' => $request->personnel_id,
                'year' => $request->year,
            ]);

            return response()->json([
                'error' => 'Failed to update leave data. Please try again.'
            ], 500);
        }
    }

    /**
     * Process leave accrual for all personnel (triggered by admin)
     */
    public function processAllAccruals(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        try {
            $year = $request->year;
            $processedCount = 0;
            $accrualService = new \App\Services\MonthlyLeaveAccrualService();

            // Get all active personnel (teacher, non_teaching, school_head)
            $users = User::whereIn('role', ['teacher', 'non_teaching', 'school_head'])
                ->whereHas('personnel', function ($query) {
                    $query->whereNotNull('employment_start');
                })
                ->with('personnel')
                ->get();

            foreach ($users as $user) {
                try {
                    // Get employment start date
                    $employmentStart = $user->personnel->employment_start;
                    if ($employmentStart) {
                        // Convert to Carbon if it's a string
                        if (is_string($employmentStart)) {
                            $employmentStart = \Carbon\Carbon::parse($employmentStart);
                        }
                    }

                    // Force update by first resetting Vacation and Sick leave to 0
                    if ($user->role === 'teacher') {
                        // Skip Sick Leave for teachers - only reset other leave types if needed
                        // Note: Sick Leave is removed from accrual for teachers
                        // \App\Models\TeacherLeave::where('teacher_id', $user->personnel->id)
                        //     ->where('year', $year)
                        //     ->whereIn('leave_type', ['Sick Leave'])
                        //     ->update(['available' => 0, 'remarks' => 'Reset before accrual on ' . now()->format('M d, Y')]);

                        // Process accrual (will skip Sick Leave for teachers)
                        $result = $accrualService->updateTeacherLeaveRecords($user->personnel->id, $year);
                        // Always count as processed since we're forcing the update
                        $processedCount++;
                        Log::info("Processed accrual for teacher: {$user->personnel->first_name} {$user->personnel->last_name} (Sick Leave excluded)");
                    } elseif ($user->role === 'non_teaching') {
                        // Reset Vacation and Sick leave
                        \App\Models\NonTeachingLeave::where('non_teaching_id', $user->personnel->id)
                            ->where('year', $year)
                            ->whereIn('leave_type', ['Vacation Leave', 'Sick Leave'])
                            ->update(['available' => 0, 'remarks' => 'Reset before accrual on ' . now()->format('M d, Y')]);

                        // Process accrual
                        $result = $accrualService->updateNonTeachingLeaveRecords($user->personnel->id, $year);
                        // Always count as processed since we're forcing the update
                        $processedCount++;
                        Log::info("Processed accrual for non-teaching: {$user->personnel->first_name} {$user->personnel->last_name}");
                    } elseif ($user->role === 'school_head') {
                        // Reset Vacation and Sick leave
                        \App\Models\SchoolHeadLeave::where('school_head_id', $user->personnel->id)
                            ->where('year', $year)
                            ->whereIn('leave_type', ['Vacation Leave', 'Sick Leave'])
                            ->update(['available' => 0, 'ctos_earned' => 0, 'remarks' => 'Reset before accrual on ' . now()->format('M d, Y')]);

                        // For school heads, handle separately
                        $this->accrueLeaveForSchoolHead($user->personnel, $year);
                        $processedCount++;
                        Log::info("Processed accrual for school head: {$user->personnel->first_name} {$user->personnel->last_name}");
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to process accrual for {$user->personnel->first_name} {$user->personnel->last_name}: {$e->getMessage()}");
                }
            }

            // Also update fixed allocation leaves for all
            foreach ($users as $user) {
                // Skip if employment_start is invalid
                $employmentStart = $user->personnel->employment_start;
                if ($employmentStart) {
                    // Convert to Carbon if it's a string
                    if (is_string($employmentStart)) {
                        $employmentStart = \Carbon\Carbon::parse($employmentStart);
                    }

                    // Comment out the year check to allow old dates
                    // if ($employmentStart->year < 1900) {
                    //     continue;
                    // }
                }
                $this->updateFixedAllocationLeaves($user->personnel, $user->role, $year);
            }

            Log::info('Batch leave accrual processed', [
                'admin_user_id' => Auth::id(),
                'year' => $year,
                'processed_count' => $processedCount,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Leave accrual processed successfully for {$processedCount} personnel.",
                'processed_count' => $processedCount,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to process batch leave accrual', [
                'error' => $e->getMessage(),
                'admin_user_id' => Auth::id(),
            ]);

            return response()->json([
                'error' => 'Failed to process leave accrual. Please try again.'
            ], 500);
        }
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
            $leaves = $this->getPersonnelLeaveDataFromDatabase($user, $year);

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
            'leave_type' => 'required|string',
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
            'leave_type' => 'required|string',
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

        Log::info('getPersonnelLeaves called', [
            'personnelId' => $personnelId,
            'year' => $year
        ]);

        $personnel = Personnel::find($personnelId);
        if (!$personnel) {
            Log::error('Personnel not found', ['personnelId' => $personnelId]);
            return response()->json(['error' => 'Personnel not found'], 404);
        }

        Log::info('Personnel found', [
            'personnelId' => $personnel->id,
            'name' => $personnel->first_name . ' ' . $personnel->last_name
        ]);

        $leaveData = [];
        $leaveTypes = [];
        $personnelRole = '';

        // Check each leave table for this personnel ID
        // Check teacher_leaves first
        $teacherLeaves = TeacherLeave::where('teacher_id', $personnelId)
            ->where('year', $year)
            ->get();

        if ($teacherLeaves->count() > 0) {
            $personnelRole = 'Teacher';
            foreach ($teacherLeaves as $leave) {
                $leaveTypes[] = $leave->leave_type;
                $leaveData[$leave->leave_type] = [
                    'available' => $leave->available,
                    'used' => $leave->used,
                    'remarks' => $leave->remarks ?? '',
                ];
            }
        }

        // Check school_head_leaves
        $schoolHeadLeaves = SchoolHeadLeave::where('school_head_id', $personnelId)
            ->where('year', $year)
            ->get();

        if ($schoolHeadLeaves->count() > 0) {
            $personnelRole = 'School Head';
            foreach ($schoolHeadLeaves as $leave) {
                $leaveTypes[] = $leave->leave_type;
                $leaveData[$leave->leave_type] = [
                    'available' => $leave->available,
                    'used' => $leave->used,
                    'remarks' => $leave->remarks ?? '',
                ];
            }
        }

        // Check non_teaching_leaves
        $nonTeachingLeaves = NonTeachingLeave::where('non_teaching_id', $personnelId)
            ->where('year', $year)
            ->get();

        if ($nonTeachingLeaves->count() > 0) {
            $personnelRole = 'Non Teaching';
            foreach ($nonTeachingLeaves as $leave) {
                $leaveTypes[] = $leave->leave_type;
                $leaveData[$leave->leave_type] = [
                    'available' => $leave->available,
                    'used' => $leave->used,
                    'remarks' => $leave->remarks ?? '',
                ];
            }
        }

        Log::info('Returning leave data', [
            'personnelRole' => $personnelRole,
            'leaveTypesCount' => count($leaveTypes),
            'leaveTypes' => $leaveTypes
        ]);

        return response()->json([
            'personnel' => [
                'id' => $personnel->id,
                'name' => $personnel->first_name . ' ' . $personnel->last_name,
                'role' => $personnelRole,
                'school' => $personnel->school ? $personnel->school->school_name : 'No School Assigned',
            ],
            'leaveTypes' => $leaveTypes,
            'leaves' => $leaveData,
        ]);
    }
}
