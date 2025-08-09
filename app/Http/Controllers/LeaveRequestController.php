<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;

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

        LeaveRequest::create([
            'user_id' => Auth::id(),
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

    return redirect()->route('school_head.dashboard')->with('success', 'Leave request submitted!');
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
        $leave->status = $request->status;
        $leave->save();

        // If approved, update school head's leave info
        if ($request->status === 'approved') {
            $user = $leave->user;
            if ($user && $user->role === 'school_head') {
                $personnel = $user->personnel;
                if ($personnel) {
                    // Update work info: set job_status to leave type
                    $personnel->job_status = $leave->leave_type;
                    $personnel->save();

                    // Update available leaves
                    $schoolHeadLeave = \App\Models\SchoolHeadLeave::where('school_head_id', $personnel->id)
                        ->where('leave_type', $leave->leave_type)
                        ->first();
                    if ($schoolHeadLeave) {
                        $schoolHeadLeave->used += 1;
                        $schoolHeadLeave->available = max(0, $schoolHeadLeave->available - 1);
                        $schoolHeadLeave->save();
                    }
                }
            }
        }
        return back()->with('success', 'Leave request updated!');
    }
}
