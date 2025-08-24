<?php
// Before (Problem):

// After (Fixed):
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Services\CTOService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LeaveRequestController extends Controller
{
    protected $ctoService;

    public function __construct(CTOService $ctoService)
    {
        $this->ctoService = $ctoService;
    }
    // User requests leave
    public function store(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $user = Auth::user();
        
        // General validation for all users: Check if male users are trying to request Maternity Leave
        if ($request->leave_type === 'Maternity Leave' && $user->personnel && $user->personnel->sex === 'male') {
            return redirect()->back()
                ->withErrors(['leave_type' => 'Maternity Leave is not available for male personnel.'])
                ->withInput();
        }
        
        // If this is a school head, check leave balance before allowing submission
        if ($user->role === 'school_head') {
            $personnel = $user->personnel;
            if ($personnel) {
                // Ensure leave records exist
                $this->ensureSchoolHeadLeaveRecordsExist($personnel);
                
                // Calculate requested days
                $startDate = Carbon::parse($request->start_date);
                $endDate = Carbon::parse($request->end_date);
                $requestedDays = $startDate->diffInDays($endDate) + 1;
                
                // Check available balance for this leave type
                $currentYear = now()->year;
                $schoolHeadLeave = \App\Models\SchoolHeadLeave::where('school_head_id', $personnel->id)
                    ->where('leave_type', $request->leave_type)
                    ->where('year', $currentYear)
                    ->first();
                
                if (!$schoolHeadLeave || $schoolHeadLeave->available < $requestedDays) {
                    $availableDays = $schoolHeadLeave ? $schoolHeadLeave->available : 0;
                    return redirect()->back()
                        ->withErrors(['leave_days' => "Insufficient leave balance. You have {$availableDays} days available for {$request->leave_type}, but requested {$requestedDays} days."])
                        ->withInput();
                }
            }
        }

        // For teachers, validate only specific leave types that have limits
        if ($user->role === 'teacher') {
            $personnel = $user->personnel;
            if ($personnel) {
                $startDate = Carbon::parse($request->start_date);
                $endDate = Carbon::parse($request->end_date);
                $requestedDays = $startDate->diffInDays($endDate) + 1;
                
                // Check leave type specific limits for teachers
                if ($request->leave_type === 'Solo Parent Leave') {
                    if (!$personnel->is_solo_parent) {
                        return redirect()->back()
                            ->withErrors(['leave_type' => 'You are not eligible for Solo Parent Leave.'])
                            ->withInput();
                    }
                    if ($requestedDays > 7) {
                        return redirect()->back()
                            ->withErrors(['leave_days' => 'Solo Parent Leave is limited to 7 days per year.'])
                            ->withInput();
                    }
                } elseif ($request->leave_type === 'Maternity Leave') {
                    // Check if the user is female
                    if ($personnel->sex !== 'female') {
                        return redirect()->back()
                            ->withErrors(['leave_type' => 'Maternity Leave is only available for female personnel.'])
                            ->withInput();
                    }
                    $maxDays = $personnel->is_solo_parent ? 120 : 105;
                    if ($requestedDays > $maxDays) {
                        return redirect()->back()
                            ->withErrors(['leave_days' => "Maternity Leave is limited to {$maxDays} days."])
                            ->withInput();
                    }
                } elseif (in_array($request->leave_type, ['Rehabilitation Leave', 'Study Leave'])) {
                    if ($requestedDays > 180) {
                        return redirect()->back()
                            ->withErrors(['leave_days' => "{$request->leave_type} is limited to 180 days."])
                            ->withInput();
                    }
                } elseif ($request->leave_type === 'Force Leave') {
                    if ($requestedDays > 5) {
                        return redirect()->back()
                            ->withErrors(['leave_days' => 'Force Leave is limited to 5 days per year.'])
                            ->withInput();
                    }
                }
                // Personal Leave and Sick Leave are unlimited for teachers (taken from service credit)
            }
        }

        // For non-teaching staff, validate Force Leave limits
        if ($user->role === 'non_teaching') {
            $personnel = $user->personnel;
            if ($personnel && $request->leave_type === 'Force Leave') {
                $startDate = Carbon::parse($request->start_date);
                $endDate = Carbon::parse($request->end_date);
                $requestedDays = $startDate->diffInDays($endDate) + 1;
                
                if ($requestedDays > 5) {
                    return redirect()->back()
                        ->withErrors(['leave_days' => 'Force Leave is limited to 5 days per year.'])
                        ->withInput();
                }
            }
        }

        try {
            LeaveRequest::create([
                'user_id' => Auth::id(),
                'leave_type' => $request->leave_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason,
                'status' => 'pending',
            ]);

            // If this is a school head, ensure their leave records are initialized for the current year
            if ($user->role === 'school_head') {
                $this->ensureSchoolHeadLeaveRecordsExist($user->personnel);
            }

            // Redirect based on user role
            if ($user->role === 'school_head') {
                return redirect()->route('school_head.dashboard')->with('success', 'Leave request submitted successfully!');
            } elseif ($user->role === 'teacher') {
                return redirect()->route('teacher.dashboard')->with('success', 'Leave request submitted successfully!');
            }
            
            return redirect()->back()->with('success', 'Leave request submitted successfully!');
            
        } catch (\Exception $e) {
            Log::error('Leave request creation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);
            
            return redirect()->back()
                ->withErrors(['submission' => 'Failed to submit leave request. Please try again.'])
                ->withInput();
        }
    }

    // Admin views pending requests
    public function index()
    {
        $requests = LeaveRequest::where('status', 'pending')->with('user')->get();
        return view('admin.leave_requests', compact('requests'));
    }

    // Admin approves/denies
    public function update(Request $request, $id)
    {
        $leave = LeaveRequest::findOrFail($id);
        $oldStatus = $leave->status;
        $leave->status = $request->status;
        $leave->save();

        // If approved, update leave balance for all user types
        if ($request->status === 'approved') {
            $this->updateLeaveBalance($leave);
        } 
        // If denied, restore job status to Active if it was previously pending
        elseif ($request->status === 'denied') {
            $this->restoreUserStatus($leave, $oldStatus);
        }
        
        // Provide appropriate success message
        $message = match($request->status) {
            'approved' => 'Leave request approved and leave balance updated successfully!',
            'denied' => 'Leave request denied and status restored.',
            default => 'Leave request updated!'
        };
        
        return back()->with('success', $message);
    }

    /**
     * Update leave balance when leave is approved (for all user types)
     */
    private function updateLeaveBalance(LeaveRequest $leave)
    {
        $user = $leave->user;
        if (!$user) {
            return;
        }

        $personnel = $user->personnel;
        if (!$personnel) {
            return;
        }

        // Calculate the number of days for this leave request
        $startDate = Carbon::parse($leave->start_date);
        $endDate = Carbon::parse($leave->end_date);
        $leaveDays = $startDate->diffInDays($endDate) + 1; // +1 to include both start and end dates

        // Update work info: set job_status to leave type
        $personnel->job_status = $leave->leave_type;
        $personnel->save();

        // Handle different user roles
        switch ($user->role) {
            case 'school_head':
                $this->updateSchoolHeadLeaveBalance($leave);
                break;
            case 'teacher':
                $this->updateTeacherLeaveBalance($personnel, $leave->leave_type, $leaveDays);
                break;
            case 'non_teaching':
                $this->updateNonTeachingLeaveBalance($personnel, $leave->leave_type, $leaveDays);
                break;
        }
    }

    /**
     * Update teacher leave balance when leave is approved
     */
    private function updateTeacherLeaveBalance($personnel, $leaveType, $leaveDays)
    {
        $currentYear = now()->year;

        // Ensure teacher leave records exist
        $this->ensureTeacherLeaveRecordsExist($personnel);

        // Handle Force Leave specially
        if ($leaveType === 'Force Leave') {
            $this->handleTeacherForceLeaveDeduction($personnel, $leaveDays, $currentYear);
            return;
        }

        // Handle regular leave types
        $teacherLeave = \App\Models\TeacherLeave::where('teacher_id', $personnel->id)
            ->where('leave_type', $leaveType)
            ->where('year', $currentYear)
            ->first();

        if ($teacherLeave) {
            $previousUsed = $teacherLeave->used;
            $previousAvailable = $teacherLeave->available;
            
            $teacherLeave->used += $leaveDays;
            $teacherLeave->available = max(0, $teacherLeave->available - $leaveDays);
            $teacherLeave->save();

            Log::info("Leave balance updated for teacher", [
                'personnel_id' => $personnel->id,
                'leave_type' => $leaveType,
                'leave_days' => $leaveDays,
                'previous_used' => $previousUsed,
                'new_used' => $teacherLeave->used,
                'previous_available' => $previousAvailable,
                'new_available' => $teacherLeave->available
            ]);
        }
    }

    /**
     * Update non-teaching staff leave balance when leave is approved
     */
    private function updateNonTeachingLeaveBalance($personnel, $leaveType, $leaveDays)
    {
        $currentYear = now()->year;

        // Ensure non-teaching leave records exist
        $this->ensureNonTeachingLeaveRecordsExist($personnel);

        // Handle Force Leave specially
        if ($leaveType === 'Force Leave') {
            $this->handleNonTeachingForceLeaveDeduction($personnel, $leaveDays, $currentYear);
            return;
        }

        // Handle regular leave types
        $nonTeachingLeave = \App\Models\NonTeachingLeave::where('non_teaching_id', $personnel->id)
            ->where('leave_type', $leaveType)
            ->where('year', $currentYear)
            ->first();

        if ($nonTeachingLeave) {
            $previousUsed = $nonTeachingLeave->used;
            $previousAvailable = $nonTeachingLeave->available;
            
            $nonTeachingLeave->used += $leaveDays;
            $nonTeachingLeave->available = max(0, $nonTeachingLeave->available - $leaveDays);
            $nonTeachingLeave->save();

            Log::info("Leave balance updated for non-teaching staff", [
                'personnel_id' => $personnel->id,
                'leave_type' => $leaveType,
                'leave_days' => $leaveDays,
                'previous_used' => $previousUsed,
                'new_used' => $nonTeachingLeave->used,
                'previous_available' => $previousAvailable,
                'new_available' => $nonTeachingLeave->available
            ]);
        }
    }

    /**
     * Update school head's leave balance when leave is approved
     */
    private function updateSchoolHeadLeaveBalance(LeaveRequest $leave)
    {
        $user = $leave->user;
        if (!$user || $user->role !== 'school_head') {
            return;
        }

        $personnel = $user->personnel;
        if (!$personnel) {
            return;
        }

        // Ensure all leave records exist for this school head
        $this->ensureSchoolHeadLeaveRecordsExist($personnel);

        // Calculate the number of days for this leave request
        $startDate = Carbon::parse($leave->start_date);
        $endDate = Carbon::parse($leave->end_date);
        $leaveDays = $startDate->diffInDays($endDate) + 1; // +1 to include both start and end dates

        // Update work info: set job_status to leave type
        $personnel->job_status = $leave->leave_type;
        $personnel->save();

        // Handle CTO leave using the new CTO service
        if ($leave->leave_type === 'Compensatory Time Off') {
            try {
                $this->ctoService->handleCTOLeaveRequest($leave);
                Log::info("CTO leave processed using new service", [
                    'leave_request_id' => $leave->id,
                    'personnel_id' => $personnel->id,
                    'leave_days' => $leaveDays
                ]);
                return; // Exit early since CTO service handles the balance update
            } catch (\Exception $e) {
                Log::error("Failed to process CTO leave using new service", [
                    'leave_request_id' => $leave->id,
                    'personnel_id' => $personnel->id,
                    'error' => $e->getMessage()
                ]);
                // Fall back to legacy method below
            }
        }

        // Get current year
        $currentYear = now()->year;

        // Handle Force Leave specially - it deducts from both Force Leave and Vacation Leave
        if ($leave->leave_type === 'Force Leave') {
            $this->handleForceLeaveDeduction($personnel, $leaveDays, $currentYear);
            return;
        }

        // Now we can be sure the record exists, so get it and update
        $schoolHeadLeave = \App\Models\SchoolHeadLeave::where('school_head_id', $personnel->id)
            ->where('leave_type', $leave->leave_type)
            ->where('year', $currentYear)
            ->first();
        
        if ($schoolHeadLeave) {
            // Update existing leave record
            $previousUsed = $schoolHeadLeave->used;
            $previousAvailable = $schoolHeadLeave->available;
            
            $schoolHeadLeave->used += $leaveDays;
            $schoolHeadLeave->available = max(0, $schoolHeadLeave->available - $leaveDays);
            $schoolHeadLeave->save();
            
            // Log the change for debugging
            Log::info("Leave balance updated for school head", [
                'personnel_id' => $personnel->id,
                'leave_type' => $leave->leave_type,
                'leave_days' => $leaveDays,
                'previous_used' => $previousUsed,
                'new_used' => $schoolHeadLeave->used,
                'previous_available' => $previousAvailable,
                'new_available' => $schoolHeadLeave->available
            ]);
        } else {
            // This should not happen anymore since we ensure records exist, but keep as fallback
            Log::error("Leave record not found after initialization", [
                'personnel_id' => $personnel->id,
                'leave_type' => $leave->leave_type,
                'year' => $currentYear
            ]);
        }
    }

    /**
     * Handle Force Leave deduction from both Force Leave and Vacation Leave
     */
    private function handleForceLeaveDeduction($personnel, $leaveDays, $currentYear)
    {
        // Get Force Leave record
        $forceLeave = \App\Models\SchoolHeadLeave::where('school_head_id', $personnel->id)
            ->where('leave_type', 'Force Leave')
            ->where('year', $currentYear)
            ->first();

        // Get Vacation Leave record
        $vacationLeave = \App\Models\SchoolHeadLeave::where('school_head_id', $personnel->id)
            ->where('leave_type', 'Vacation Leave')
            ->where('year', $currentYear)
            ->first();

        if ($forceLeave && $vacationLeave) {
            // Update Force Leave
            $previousForceUsed = $forceLeave->used;
            $previousForceAvailable = $forceLeave->available;
            
            $forceLeave->used += $leaveDays;
            $forceLeave->available = max(0, $forceLeave->available - $leaveDays);
            $forceLeave->save();

            // Also deduct from Vacation Leave
            $previousVacationUsed = $vacationLeave->used;
            $previousVacationAvailable = $vacationLeave->available;
            
            $vacationLeave->used += $leaveDays;
            $vacationLeave->available = max(0, $vacationLeave->available - $leaveDays);
            $vacationLeave->save();

            // Log the changes
            Log::info("Force Leave processed - deducted from both Force Leave and Vacation Leave", [
                'personnel_id' => $personnel->id,
                'leave_days' => $leaveDays,
                'force_leave' => [
                    'previous_used' => $previousForceUsed,
                    'new_used' => $forceLeave->used,
                    'previous_available' => $previousForceAvailable,
                    'new_available' => $forceLeave->available
                ],
                'vacation_leave' => [
                    'previous_used' => $previousVacationUsed,
                    'new_used' => $vacationLeave->used,
                    'previous_available' => $previousVacationAvailable,
                    'new_available' => $vacationLeave->available
                ]
            ]);
        } else {
            Log::error("Force Leave or Vacation Leave record not found", [
                'personnel_id' => $personnel->id,
                'force_leave_exists' => $forceLeave ? true : false,
                'vacation_leave_exists' => $vacationLeave ? true : false,
                'year' => $currentYear
            ]);
        }
    }

    /**
     * Handle Teacher Force Leave deduction from both Force Leave and Vacation Leave
     */
    private function handleTeacherForceLeaveDeduction($personnel, $leaveDays, $currentYear)
    {
        // Get Force Leave record
        $forceLeave = \App\Models\TeacherLeave::where('teacher_id', $personnel->id)
            ->where('leave_type', 'Force Leave')
            ->where('year', $currentYear)
            ->first();

        // Get Vacation Leave record
        $vacationLeave = \App\Models\TeacherLeave::where('teacher_id', $personnel->id)
            ->where('leave_type', 'Vacation Leave')
            ->where('year', $currentYear)
            ->first();

        if ($forceLeave && $vacationLeave) {
            // Update Force Leave
            $previousForceUsed = $forceLeave->used;
            $previousForceAvailable = $forceLeave->available;
            
            $forceLeave->used += $leaveDays;
            $forceLeave->available = max(0, $forceLeave->available - $leaveDays);
            $forceLeave->save();

            // Also deduct from Vacation Leave
            $previousVacationUsed = $vacationLeave->used;
            $previousVacationAvailable = $vacationLeave->available;
            
            $vacationLeave->used += $leaveDays;
            $vacationLeave->available = max(0, $vacationLeave->available - $leaveDays);
            $vacationLeave->save();

            Log::info("Teacher Force Leave processed - deducted from both Force Leave and Vacation Leave", [
                'personnel_id' => $personnel->id,
                'leave_days' => $leaveDays,
                'force_leave' => [
                    'previous_used' => $previousForceUsed,
                    'new_used' => $forceLeave->used,
                    'previous_available' => $previousForceAvailable,
                    'new_available' => $forceLeave->available
                ],
                'vacation_leave' => [
                    'previous_used' => $previousVacationUsed,
                    'new_used' => $vacationLeave->used,
                    'previous_available' => $previousVacationAvailable,
                    'new_available' => $vacationLeave->available
                ]
            ]);
        }
    }

    /**
     * Handle Non-Teaching Staff Force Leave deduction from both Force Leave and Vacation Leave
     */
    private function handleNonTeachingForceLeaveDeduction($personnel, $leaveDays, $currentYear)
    {
        // Get Force Leave record
        $forceLeave = \App\Models\NonTeachingLeave::where('non_teaching_id', $personnel->id)
            ->where('leave_type', 'Force Leave')
            ->where('year', $currentYear)
            ->first();

        // Get Vacation Leave record
        $vacationLeave = \App\Models\NonTeachingLeave::where('non_teaching_id', $personnel->id)
            ->where('leave_type', 'Vacation Leave')
            ->where('year', $currentYear)
            ->first();

        if ($forceLeave && $vacationLeave) {
            // Update Force Leave
            $previousForceUsed = $forceLeave->used;
            $previousForceAvailable = $forceLeave->available;
            
            $forceLeave->used += $leaveDays;
            $forceLeave->available = max(0, $forceLeave->available - $leaveDays);
            $forceLeave->save();

            // Also deduct from Vacation Leave
            $previousVacationUsed = $vacationLeave->used;
            $previousVacationAvailable = $vacationLeave->available;
            
            $vacationLeave->used += $leaveDays;
            $vacationLeave->available = max(0, $vacationLeave->available - $leaveDays);
            $vacationLeave->save();

            Log::info("Non-Teaching Staff Force Leave processed - deducted from both Force Leave and Vacation Leave", [
                'personnel_id' => $personnel->id,
                'leave_days' => $leaveDays,
                'force_leave' => [
                    'previous_used' => $previousForceUsed,
                    'new_used' => $forceLeave->used,
                    'previous_available' => $previousForceAvailable,
                    'new_available' => $forceLeave->available
                ],
                'vacation_leave' => [
                    'previous_used' => $previousVacationUsed,
                    'new_used' => $vacationLeave->used,
                    'previous_available' => $previousVacationAvailable,
                    'new_available' => $vacationLeave->available
                ]
            ]);
        }
    }

    /**
     * Ensure teacher leave records exist for the current year
     */
    private function ensureTeacherLeaveRecordsExist($personnel)
    {
        $currentYear = now()->year;
        $yearsOfService = $personnel->employment_start ? 
            Carbon::parse($personnel->employment_start)->diffInYears(Carbon::now()) : 0;
        
        $defaultLeaves = \App\Models\TeacherLeave::defaultLeaves(
            $yearsOfService,
            $personnel->is_solo_parent ?? false,
            $personnel->sex ?? null
        );

        foreach ($defaultLeaves as $leaveType => $defaultMax) {
            \App\Models\TeacherLeave::firstOrCreate(
                [
                    'teacher_id' => $personnel->id,
                    'leave_type' => $leaveType,
                    'year' => $currentYear,
                ],
                [
                    'available' => $defaultMax,
                    'used' => 0,
                    'remarks' => 'Auto-initialized for leave processing',
                ]
            );
        }
    }

    /**
     * Ensure non-teaching leave records exist for the current year
     */
    private function ensureNonTeachingLeaveRecordsExist($personnel)
    {
        $currentYear = now()->year;
        $yearsOfService = $personnel->employment_start ? 
            Carbon::parse($personnel->employment_start)->diffInYears(Carbon::now()) : 0;
        
        $defaultLeaves = \App\Models\NonTeachingLeave::defaultLeaves(
            $yearsOfService,
            $personnel->is_solo_parent ?? false,
            $personnel->sex ?? null
        );

        foreach ($defaultLeaves as $leaveType => $defaultMax) {
            \App\Models\NonTeachingLeave::firstOrCreate(
                [
                    'non_teaching_id' => $personnel->id,
                    'leave_type' => $leaveType,
                    'year' => $currentYear,
                ],
                [
                    'available' => $defaultMax,
                    'used' => 0,
                    'remarks' => 'Auto-initialized for leave processing',
                ]
            );
        }
    }

    /**
     * Restore user's status when leave is denied
     */
    private function restoreUserStatus(LeaveRequest $leave, $oldStatus)
    {
        $user = $leave->user;
        if (!$user) {
            return;
        }

        $personnel = $user->personnel;
        if ($personnel && $oldStatus === 'pending') {
            $personnel->job_status = 'Active';
            $personnel->save();
        }
    }

    /**
     * Restore school head's status when leave is denied
     */
    private function restoreSchoolHeadStatus(LeaveRequest $leave, $oldStatus)
    {
        // This method now just calls the general restore method
        $this->restoreUserStatus($leave, $oldStatus);
    }

    /**
     * Ensure school head has all leave type records initialized for the current year
     */
    private function ensureSchoolHeadLeaveRecordsExist($personnel)
    {
        if (!$personnel) {
            return;
        }

        $currentYear = now()->year;
        $soloParent = $personnel->is_solo_parent ?? false;
        $userSex = $personnel->sex ?? null;
        $defaultLeaves = \App\Models\SchoolHeadLeave::defaultLeaves($soloParent, $userSex);

        foreach ($defaultLeaves as $leaveType => $maxDays) {
            // Check if record exists for this leave type and year
            $existingRecord = \App\Models\SchoolHeadLeave::where('school_head_id', $personnel->id)
                ->where('leave_type', $leaveType)
                ->where('year', $currentYear)
                ->first();

            // If record doesn't exist, create it with default values
            if (!$existingRecord) {
                \App\Models\SchoolHeadLeave::create([
                    'school_head_id' => $personnel->id,
                    'leave_type' => $leaveType,
                    'year' => $currentYear,
                    'available' => $maxDays,
                    'used' => 0,
                    'ctos_earned' => 0,
                    'remarks' => 'Auto-initialized'
                ]);

                Log::info("Initialized leave record for school head", [
                    'personnel_id' => $personnel->id,
                    'leave_type' => $leaveType,
                    'year' => $currentYear,
                    'max_days' => $maxDays
                ]);
            }
        }
    }
}
