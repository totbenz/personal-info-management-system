<?php
// Before (Problem):

// After (Fixed):
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LeaveRequestController extends Controller
{
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

        // If approved, update school head's leave info
        if ($request->status === 'approved') {
            $this->updateSchoolHeadLeaveBalance($leave);
        } 
        // If denied, restore job status to Active if it was previously pending
        elseif ($request->status === 'denied') {
            $this->restoreSchoolHeadStatus($leave, $oldStatus);
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

        // Get current year
        $currentYear = now()->year;

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
     * Restore school head's status when leave is denied
     */
    private function restoreSchoolHeadStatus(LeaveRequest $leave, $oldStatus)
    {
        $user = $leave->user;
        if (!$user || $user->role !== 'school_head') {
            return;
        }

        $personnel = $user->personnel;
        if ($personnel && $oldStatus === 'pending') {
            // Restore job status to Active
            $personnel->job_status = 'Active';
            $personnel->save();
        }
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
        $defaultLeaves = \App\Models\SchoolHeadLeave::defaultLeaves($soloParent);

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
