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
use PhpOffice\PhpWord\TemplateProcessor;

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
            'reason' => 'nullable|string',
            'custom_leave_name' => 'nullable|string|max:50',
            'custom_leave_reason' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        // For custom leaves, validate that custom fields are provided
        if ($request->leave_type === 'custom') {
            if (!$request->custom_leave_name) {
                return redirect()->back()
                    ->withErrors(['custom_leave_name' => 'Custom leave name is required.'])
                    ->withInput();
            }
            // For custom leaves, use the reason field as the custom reason
            if (!$request->reason) {
                return redirect()->back()
                    ->withErrors(['reason' => 'Reason is required for custom leave.'])
                    ->withInput();
            }
            // Set the custom_leave_reason to match the reason field
            $request->merge(['custom_leave_reason' => $request->reason]);
        } else {
            // For non-custom leaves, reason is required
            if (!$request->reason) {
                return redirect()->back()
                    ->withErrors(['reason' => 'Reason is required.'])
                    ->withInput();
            }
        }

        // General validation for all users: Check if male users are trying to request Maternity Leave
        if ($request->leave_type === 'Maternity Leave' && $user->personnel && $user->personnel->sex === 'male') {
            return redirect()->back()
                ->withErrors(['leave_type' => 'Maternity Leave is not available for male personnel.'])
                ->withInput();
        }

        // If this is a school head, check leave balance before allowing submission (skip for custom leaves)
        if ($user->role === 'school_head' && $request->leave_type !== 'custom') {
            $personnel = $user->personnel;
            if ($personnel) {
                // Ensure leave records exist
                $this->ensureSchoolHeadLeaveRecordsExist($personnel);

                // Calculate requested days (excluding weekends)
                $startDate = Carbon::parse($request->start_date);
                $endDate = Carbon::parse($request->end_date);
                $requestedDays = $this->calculateWorkingDays($startDate, $endDate);

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

        // For teachers, validate against actual available balance for all leave types
        if ($user->role === 'teacher') {
            $personnel = $user->personnel;
            if ($personnel) {
                $startDate = Carbon::parse($request->start_date);
                $endDate = Carbon::parse($request->end_date);
                $requestedDays = $this->calculateWorkingDays($startDate, $endDate);

                // Get teacher's leave records for current year
                $currentYear = now()->year;
                $this->ensureTeacherLeaveRecordsExist($personnel);
                $teacherLeaves = \App\Models\TeacherLeave::where('teacher_id', $personnel->id)
                    ->where('year', $currentYear)
                    ->get()
                    ->keyBy('leave_type');

                // Check leave type specific limits and availability
                if ($request->leave_type === 'Solo Parent Leave') {
                    if (!$personnel->is_solo_parent) {
                        return redirect()->back()
                            ->withErrors(['leave_type' => 'You are not eligible for Solo Parent Leave.'])
                            ->withInput();
                    }
                    $soloParentLeave = $teacherLeaves->get('Solo Parent Leave');
                    $available = $soloParentLeave?->available ?? 0;
                    if ($requestedDays > $available) {
                        return redirect()->back()
                            ->withErrors(['leave_days' => "Insufficient Solo Parent Leave balance. You have {$available} day(s) available, requested {$requestedDays}."])
                            ->withInput();
                    }
                } elseif ($request->leave_type === 'Maternity Leave') {
                    // Check if the user is female
                    if ($personnel->sex !== 'female') {
                        return redirect()->back()
                            ->withErrors(['leave_type' => 'Maternity Leave is only available for female personnel.'])
                            ->withInput();
                    }
                    $maternityLeave = $teacherLeaves->get('Maternity Leave');
                    $available = $maternityLeave?->available ?? 0;
                    if ($requestedDays > $available) {
                        return redirect()->back()
                            ->withErrors(['leave_days' => "Insufficient Maternity Leave balance. You have {$available} day(s) available, requested {$requestedDays}."])
                            ->withInput();
                    }
                } elseif (in_array($request->leave_type, ['Rehabilitation Leave', 'Study Leave'])) {
                    $leave = $teacherLeaves->get($request->leave_type);
                    $available = $leave?->available ?? 0;
                    if ($requestedDays > $available) {
                        return redirect()->back()
                            ->withErrors(['leave_days' => "Insufficient {$request->leave_type} balance. You have {$available} day(s) available, requested {$requestedDays}."])
                            ->withInput();
                    }
                } elseif ($request->leave_type === 'Force Leave') {
                    $forceLeave = $teacherLeaves->get('Force Leave');
                    $available = $forceLeave?->available ?? 0;
                    if ($requestedDays > $available) {
                        return redirect()->back()
                            ->withErrors(['leave_days' => "Insufficient Force Leave balance. You have {$available} day(s) available, requested {$requestedDays}."])
                            ->withInput();
                    }
                } elseif ($request->leave_type === 'Vacation Leave') {
                    $vacationLeave = $teacherLeaves->get('Vacation Leave');
                    $available = $vacationLeave?->available ?? 0;
                    if ($requestedDays > $available) {
                        return redirect()->back()
                            ->withErrors(['leave_days' => "Insufficient Vacation Leave balance. You have {$available} day(s) available, requested {$requestedDays}."])
                            ->withInput();
                    }
                }
                // Service Credit Leave and Sick Leave require sufficient Service Credit balance
                elseif (in_array($request->leave_type, ['Service Credit Leave', 'Personal Leave','Sick Leave'])) {
                    $serviceCredit = $teacherLeaves->get('Service Credit') ?? $teacherLeaves->get('Service Credit Leave');
                    $available = $serviceCredit?->available ?? 0;
                    if ($available < $requestedDays) {
                        return redirect()->back()
                            ->withErrors(['leave_days' => "Insufficient Service Credit. You have {$available} day(s) available, requested {$requestedDays}."])
                            ->withInput();
                    }
                }
            }
        }

        // For non-teaching staff, validate Force Leave limits
        if ($user->role === 'non_teaching') {
            $personnel = $user->personnel;
            if ($personnel && $request->leave_type === 'Force Leave') {
                $startDate = Carbon::parse($request->start_date);
                $endDate = Carbon::parse($request->end_date);
                $requestedDays = $this->calculateWorkingDays($startDate, $endDate);

                if ($requestedDays > 5) {
                    return redirect()->back()
                        ->withErrors(['leave_days' => 'Force Leave is limited to 5 days per year.'])
                        ->withInput();
                }
            }
        }

        try {
            // Calculate day_debt for SICK LEAVE, SERVICE CREDIT, and Others requests
            $dayDebt = 0;
            if ($user->role === 'teacher' && in_array($request->leave_type, ['SICK LEAVE', 'SERVICE CREDIT', 'custom'])) {
                $personnel = $user->personnel;
                if ($personnel) {
                    $currentYear = now()->year;
                    $serviceCredit = \App\Models\TeacherLeave::where('teacher_id', $personnel->id)
                        ->where('leave_type', 'SERVICE CREDIT')
                        ->where('year', $currentYear)
                        ->first();

                    if ($serviceCredit) {
                        $startDate = Carbon::parse($request->start_date);
                        $endDate = Carbon::parse($request->end_date);
                        $requestedDays = $this->calculateWorkingDays($startDate, $endDate);
                        $newBalance = $serviceCredit->available - $requestedDays;

                        // For Terminal Leave (custom), block if exceeding Service Credit
                        if ($request->leave_type === 'custom' && $requestedDays > $serviceCredit->available) {
                            return redirect()->back()
                                ->withErrors(['leave_dates' => 'Terminal Leave cannot exceed available Service Credit balance. Available: ' . $serviceCredit->available . ' days, Requested: ' . $requestedDays . ' days.'])
                                ->withInput();
                        }

                        // Calculate day_debt if balance will go negative (only for SICK LEAVE and SERVICE CREDIT)
                        if (in_array($request->leave_type, ['SICK LEAVE','SERVICE CREDIT']) && $newBalance < 0) {
                            $dayDebt = abs($newBalance);
                            Log::info('Day debt calculated during submission', [
                                'teacher_id' => $personnel->id,
                                'leave_type' => $request->leave_type,
                                'requested_days' => $requestedDays,
                                'service_credit_available' => $serviceCredit->available,
                                'day_debt' => $dayDebt
                            ]);
                        }
                    }
                }
            }

            LeaveRequest::create([
                'user_id' => Auth::id(),
                'leave_type' => $request->leave_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason,
                'status' => 'pending',
                'custom_leave_name' => $request->leave_type === 'custom' ? $request->custom_leave_name : null,
                'custom_leave_reason' => $request->leave_type === 'custom' ? $request->custom_leave_reason : null,
                'day_debt' => $dayDebt,
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
        $requests = LeaveRequest::with('user')->orderBy('created_at', 'desc')->get();
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
     * Calculate working days between two dates (excluding weekends)
     */
    private function calculateWorkingDays($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Ensure end date is after start date
        if ($end->lt($start)) {
            return 0;
        }

        // Calculate working days (excluding weekends)
        $workingDays = 0;
        $currentDate = $start->copy();

        while ($currentDate->lte($end)) {
            // Exclude Saturday (6) and Sunday (7)
            if ($currentDate->dayOfWeek !== Carbon::SATURDAY && $currentDate->dayOfWeek !== Carbon::SUNDAY) {
                $workingDays++;
            }
            $currentDate->addDay();
        }

        return $workingDays;
    }

    /**
     * Update leave balance when leave is approved (for all user types)
     */
    private function updateLeaveBalance(LeaveRequest $leave)
    {
        $user = $leave->user;
        if (!$user) {
            Log::error("User not found for leave request ID: {$leave->id}");
            return;
        }

        $personnel = $user->personnel;
        if (!$personnel) {
            Log::error("Personnel not found for user ID: {$user->id}");
            return;
        }

        // Calculate leave days (excluding weekends)
        $startDate = Carbon::parse($leave->start_date);
        $endDate = Carbon::parse($leave->end_date);
        $leaveDays = $this->calculateWorkingDays($startDate, $endDate);

        // Update work info: set job_status to leave type
        $personnel->job_status = $leave->leave_type;
        $personnel->save();

        // Handle CTO-based leave requests - deduct from CTO balance instead of regular leave balance
        if ($leave->is_cto_based) {
            $this->processCtoBasedLeave($leave, $personnel, $leaveDays);
            return;
        }

        // Handle different user roles
        switch ($user->role) {
            case 'school_head':
                $this->updateSchoolHeadLeaveBalance($leave);
                break;
            case 'teacher':
                $this->updateTeacherLeaveBalance($personnel, $leave->leave_type, $leaveDays, $leave);
                break;
            case 'non_teaching':
                $this->updateNonTeachingLeaveBalance($personnel, $leave->leave_type, $leaveDays);
                break;
        }
    }

    /**
     * Process CTO-based leave request - deduct from CTO entries using FIFO
     */
    private function processCtoBasedLeave(LeaveRequest $leave, $personnel, float $leaveDays)
    {
        $remainingDays = $leaveDays;

        // Get available CTO entries ordered by earned date (FIFO)
        $ctoEntries = \App\Models\CTOEntry::getAvailableForSchoolHead($personnel->id);

        foreach ($ctoEntries as $entry) {
            if ($remainingDays <= 0) {
                break;
            }

            $daysToUse = min($remainingDays, $entry->days_remaining);

            try {
                // Use days from this CTO entry and create usage record
                $entry->useDays($daysToUse, $leave->id, 'leave', "Used for {$leave->cto_leave_type} leave request");

                Log::info('CTO entry used for leave', [
                    'cto_entry_id' => $entry->id,
                    'leave_request_id' => $leave->id,
                    'days_used' => $daysToUse,
                    'remaining_in_entry' => $entry->days_remaining
                ]);

                $remainingDays -= $daysToUse;
            } catch (\Exception $e) {
                Log::error('Failed to use CTO entry for leave', [
                    'cto_entry_id' => $entry->id,
                    'leave_request_id' => $leave->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        if ($remainingDays > 0) {
            Log::warning('Not all leave days could be covered by CTO', [
                'leave_request_id' => $leave->id,
                'requested_days' => $leaveDays,
                'uncovered_days' => $remainingDays
            ]);
        }

        Log::info('CTO-based leave processed', [
            'leave_request_id' => $leave->id,
            'personnel_id' => $personnel->id,
            'leave_type' => $leave->cto_leave_type,
            'total_days' => $leaveDays,
            'days_deducted_from_cto' => $leaveDays - $remainingDays
        ]);
    }

    /**
     * Update teacher leave balance when leave is approved
     */
    private function updateTeacherLeaveBalance($personnel, $leaveType, $leaveDays, LeaveRequest $leave)
    {
        // Skip balance update for custom leaves
        if ($leaveType === 'custom') {
            return;
        }

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

        // If Personal or Sick leave, deduct from Service Credit pool instead of its own record
        if (in_array($leaveType, ['Personal Leave','SICK LEAVE','SERVICE CREDIT','custom'])) {
            $serviceCredit = \App\Models\TeacherLeave::where('teacher_id', $personnel->id)
                ->where('leave_type', 'SERVICE CREDIT') // Updated to capitalized
                ->where('year', $currentYear)
                ->first();
            if ($serviceCredit) {
                $previousAvailable = $serviceCredit->available;
                $newBalance = $serviceCredit->available - $leaveDays;
                $dayDebt = 0;

                // Handle day_debt logic for SICK LEAVE and SERVICE CREDIT only (not Terminal Leave)
                if (in_array($leaveType, ['SICK LEAVE','SERVICE CREDIT']) && $newBalance < 0) {
                    $dayDebt = abs($newBalance); // Store the negative amount as positive debt
                    $serviceCredit->available = 0; // Set to 0, not negative
                } else {
                    $serviceCredit->available = max(0, $newBalance); // Normal deduction
                }

                $serviceCredit->used += $leaveDays; // track consumption
                $serviceCredit->remarks = trim(($serviceCredit->remarks ? $serviceCredit->remarks.'; ' : '')."{$leaveType} used {$leaveDays} day(s) on ".now()->format('Y-m-d'));
                $serviceCredit->save();

                // Update day_debt in leave request (only if not already calculated during submission)
                if ($dayDebt > 0 && !$leave->day_debt) {
                    $leave->day_debt = $dayDebt;
                    $leave->save();
                }

                // Auto-sync SICK LEAVE to match SERVICE CREDIT
                if ($leaveType === 'SICK LEAVE') {
                    $sickLeave = \App\Models\TeacherLeave::where('teacher_id', $personnel->id)
                        ->where('leave_type', 'SICK LEAVE')
                        ->where('year', $currentYear)
                        ->first();
                    if ($sickLeave) {
                        $sickLeave->available = $serviceCredit->available;
                        $sickLeave->remarks = trim(($sickLeave->remarks ? $sickLeave->remarks.'; ' : '')."Auto-synced with Service Credit on ".now()->format('Y-m-d'));
                        $sickLeave->save();
                    }
                }

                \Log::info('Service Credit deducted for leave', [
                    'teacher_id' => $personnel->id,
                    'leave_type' => $leaveType,
                    'days_deducted' => $leaveDays,
                    'previous_available' => $previousAvailable,
                    'new_available' => $serviceCredit->available,
                    'day_debt' => $dayDebt
                ]);
            }
            return; // do not proceed with per-leave-type logic
        }

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
        // Skip balance update for custom leaves
        if ($leaveType === 'custom') {
            return;
        }

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
        // Skip balance update for custom leaves
        if ($leave->leave_type === 'custom') {
            return;
        }

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

        // Calculate the number of days for this leave request (excluding weekends)
        $startDate = Carbon::parse($leave->start_date);
        $endDate = Carbon::parse($leave->end_date);
        $leaveDays = $this->calculateWorkingDays($startDate, $endDate);

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

    /**
     * Store a CTO-based leave request (using CTO balance for Sick/Vacation Leave/Others)
     */
    public function storeCtoLeave(Request $request)
    {
        $rules = [
            'cto_leave_type' => 'required|string|in:Sick Leave,Vacation Leave,Others',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ];

        // Add validation for cto_others_name if Others is selected
        if ($request->cto_leave_type === 'Others') {
            $rules['cto_others_name'] = 'required|string|max:50';
        }

        $request->validate($rules);

        $user = Auth::user();
        $personnel = $user->personnel;

        if (!$personnel) {
            return redirect()->back()
                ->withErrors(['cto_usage_error' => 'Personnel record not found.'])
                ->withInput();
        }

        // Calculate requested days
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $requestedDays = $startDate->diffInDays($endDate) + 1;

        // Check CTO balance
        $availableCto = \App\Models\CTOEntry::getTotalAvailableDays($personnel->id);

        if ($requestedDays > $availableCto) {
            return redirect()->back()
                ->withErrors(['cto_usage_error' => "Insufficient CTO balance. You have {$availableCto} days available, but requested {$requestedDays} days."])
                ->withInput();
        }

        try {
            // Create leave request with CTO flag
            $leaveRequest = LeaveRequest::create([
                'user_id' => Auth::id(),
                'leave_type' => $request->cto_leave_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason,
                'status' => 'pending',
                'is_cto_based' => true,
                'cto_leave_type' => $request->cto_leave_type,
                'cto_others_name' => $request->cto_leave_type === 'Others' ? $request->cto_others_name : null,
            ]);

            Log::info('CTO-based leave request created', [
                'leave_request_id' => $leaveRequest->id,
                'user_id' => Auth::id(),
                'personnel_id' => $personnel->id,
                'cto_leave_type' => $request->cto_leave_type,
                'requested_days' => $requestedDays,
                'available_cto' => $availableCto
            ]);

            // Redirect based on user role
            $redirectRoute = match($user->role) {
                'school_head' => 'school_head.dashboard',
                'non_teaching' => 'non_teaching.dashboard',
                default => 'dashboard'
            };

            return redirect()->route($redirectRoute)->with('cto_usage_success', 'CTO leave request submitted successfully! Awaiting admin approval.');

        } catch (\Exception $e) {
            Log::error('CTO-based leave request creation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withErrors(['cto_usage_error' => 'Failed to submit CTO leave request. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Download CTO form for CTO-based leave request
     */
    public function downloadCtoForm($leaveRequestId)
    {
        $leaveRequest = LeaveRequest::with(['user.personnel.position', 'user.personnel.school'])->findOrFail($leaveRequestId);

        // Verify this is a CTO-based leave request
        if (!$leaveRequest->is_cto_based) {
            abort(400, 'This leave request is not CTO-based.');
        }

        $user = Auth::user();
        $personnel = $leaveRequest->user->personnel;

        // Only allow download if the user is the owner or admin
        if (!($user->role === 'admin' || ($personnel && $user->personnel && $user->personnel->id === $personnel->id))) {
            abort(403, 'Unauthorized to download this CTO form.');
        }

        $templatePath = resource_path('views/forms/CTO.docx');

        if (!file_exists($templatePath)) {
            abort(404, 'CTO form template not found.');
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        // Calculate leave days
        $startDate = Carbon::parse($leaveRequest->start_date);
        $endDate = Carbon::parse($leaveRequest->end_date);
        $leaveDays = $startDate->diffInDays($endDate) + 1;
        $hoursApplied = $leaveDays * 8; // Assuming 8 hours per day

        // Fill in template variables
        $templateProcessor->setValue('name', $personnel->full_name ?? '-');
        $templateProcessor->setValue('position', $personnel && $personnel->position ? $personnel->position->title : '-');
        $templateProcessor->setValue('office', 'DEPED-' . strtoupper($personnel && $personnel->school ? $personnel->school->division : 'BAYBAY CITY DIVISION'));
        $templateProcessor->setValue('school', $personnel && $personnel->school ? $personnel->school->school_name : '-');
        $templateProcessor->setValue('date_filed', $leaveRequest->created_at ? $leaveRequest->created_at->format('F d, Y') : '-');

        // Set leave type checkmarks based on cto_leave_type
        if ($leaveRequest->cto_leave_type === 'Sick Leave') {
            $templateProcessor->setValue('sick_leave', '☑ Sick Leave');
            $templateProcessor->setValue('vacation_leave', '☐ Vacation Leave');
            $templateProcessor->setValue('others_leave', '☐ Others');
            $templateProcessor->setValue('o_name', '');
        } elseif ($leaveRequest->cto_leave_type === 'Vacation Leave') {
            $templateProcessor->setValue('sick_leave', '☐ Sick Leave');
            $templateProcessor->setValue('vacation_leave', '☑ Vacation Leave');
            $templateProcessor->setValue('others_leave', '☐ Others');
            $templateProcessor->setValue('o_name', '');
        } else {
            // Others
            $templateProcessor->setValue('sick_leave', '☐ Sick Leave');
            $templateProcessor->setValue('vacation_leave', '☐ Vacation Leave');
            $templateProcessor->setValue('others_leave', '☑ Others');
            $templateProcessor->setValue('o_name', $leaveRequest->cto_others_name ?? '');
        }

        // Approval status checkmarks
        if ($leaveRequest->status === 'approved') {
            $templateProcessor->setValue('a', '☑');
            $templateProcessor->setValue('d', '☐');
        } elseif ($leaveRequest->status === 'denied') {
            $templateProcessor->setValue('a', '☐');
            $templateProcessor->setValue('d', '☑');
        } else {
            $templateProcessor->setValue('a', '☐');
            $templateProcessor->setValue('d', '☐');
        }

        // Number of hours applied
        $templateProcessor->setValue('hours_applied', number_format($hoursApplied) . ' HRS');

        // Inclusive dates
        $inclusiveDates = strtoupper($startDate->format('F d, Y'));
        if ($leaveDays > 1) {
            $inclusiveDates .= ' - ' . strtoupper($endDate->format('F d, Y'));
        }
        $templateProcessor->setValue('inclusive_dates', $inclusiveDates);

        // Work details - for CTO usage, these are the leave dates
        $templateProcessor->setValue('work_date', $startDate->format('M d, Y'));
        $templateProcessor->setValue('morning_in', '-');
        $templateProcessor->setValue('morning_out', '-');
        $templateProcessor->setValue('afternoon_in', '-');
        $templateProcessor->setValue('afternoon_out', '-');
        $templateProcessor->setValue('total_hours', number_format($hoursApplied, 2));
        $templateProcessor->setValue('reason', $leaveRequest->reason ?? '-');
        $templateProcessor->setValue('description', $leaveRequest->reason ?? '-');
        $templateProcessor->setValue('status', ucfirst($leaveRequest->status));
        $templateProcessor->setValue('approved_at', $leaveRequest->updated_at ? $leaveRequest->updated_at->format('M d, Y') : '-');

        // Certification of Compensatory Overtime Credits
        $templateProcessor->setValue('coc_as_of', $leaveRequest->updated_at ? strtoupper($leaveRequest->updated_at->format('F d, Y')) : '-');

        // Get remaining CTO balance after this usage
        $remainingCto = \App\Models\CTOEntry::getTotalAvailableDays($personnel->id);
        $templateProcessor->setValue('hours_remaining', number_format($remainingCto * 8) . ' HRS');

        // Action taken
        $actionTaken = $leaveRequest->status === 'approved' ? 'Approved' : ($leaveRequest->status === 'denied' ? 'Disapproved' : '-');
        $templateProcessor->setValue('action_taken', $actionTaken);
        $templateProcessor->setValue('disapproved_reason', '');

        // Signatures
        $templateProcessor->setValue('applicant_name', $personnel->full_name ?? '-');
        $templateProcessor->setValue('hrmo_name', 'JULIUS CESAR L. DE LA CERNA');
        $templateProcessor->setValue('hrmo_position', 'HRMO II');
        $templateProcessor->setValue('sds_name', 'MANUEL P. ALBAÑO, PhD., CESO V');
        $templateProcessor->setValue('sds_position', 'Schools Division Superintendent');
        $templateProcessor->setValue('recommending_name', 'JOSEMILO P. RUIZ, EdD, CESE');
        $templateProcessor->setValue('recommending_position', 'Assistant Schools Division Superintendent');

        $tempFile = tempnam(sys_get_temp_dir(), 'cto_leave_') . '.docx';
        $templateProcessor->saveAs($tempFile);

        return response()->download($tempFile, 'CTO_Leave_' . $leaveRequest->id . '.docx')->deleteFileAfterSend(true);
    }
}
