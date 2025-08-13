<?php

namespace App\Http\Controllers;

use App\Models\CTORequest;
use App\Models\Personnel;
use App\Models\SchoolHeadLeave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CTORequestController extends Controller
{
    /**
     * Store a new CTO request
     */
    public function store(Request $request)
    {
        $request->validate([
            'requested_hours' => 'required|integer|min:1|max:24',
            'work_date' => 'required|date|before_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'reason' => 'required|string|min:10|max:500',
            'description' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $personnel = $user->personnel;

        // Verify user is a school head
        if ($user->role !== 'school_head' || !$personnel) {
            return redirect()->back()->withErrors([
                'authorization' => 'Only school heads can submit CTO requests.'
            ]);
        }

        // Validate that the calculated hours match the time difference
        $startTime = Carbon::createFromFormat('H:i', $request->start_time);
        $endTime = Carbon::createFromFormat('H:i', $request->end_time);
        $calculatedHours = $endTime->diffInHours($startTime);

        if ($calculatedHours != $request->requested_hours) {
            return redirect()->back()->withErrors([
                'requested_hours' => 'The requested hours must match the time difference between start and end time.'
            ])->withInput();
        }

        // Check if there's already a request for the same date
        $existingRequest = CTORequest::where('school_head_id', $personnel->id)
            ->where('work_date', $request->work_date)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingRequest) {
            return redirect()->back()->withErrors([
                'work_date' => 'You already have a CTO request for this date.'
            ])->withInput();
        }

        try {
            CTORequest::create([
                'school_head_id' => $personnel->id,
                'requested_hours' => $request->requested_hours,
                'work_date' => $request->work_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'reason' => $request->reason,
                'description' => $request->description,
                'status' => 'pending',
            ]);

            return redirect()->back()->with('success', 'CTO request submitted successfully! You will be notified once it\'s reviewed.');

        } catch (\Exception $e) {
            Log::error('CTO request creation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withErrors(['submission' => 'Failed to submit CTO request. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Display all pending CTO requests for admin
     */
    public function index()
    {
        $requests = CTORequest::where('status', 'pending')
            ->with(['schoolHead.school', 'schoolHead.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.cto-requests.index', compact('requests'));
    }

    /**
     * Approve a CTO request
     */
    public function approve(Request $request, CTORequest $ctoRequest)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($ctoRequest->status !== 'pending') {
            return redirect()->back()->withErrors([
                'status' => 'This request has already been processed.'
            ]);
        }

        try {
            // Update the CTO request
            $ctoRequest->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id(),
                'admin_notes' => $request->admin_notes,
            ]);

            // Add CTO to school head's leave balance
            $this->addCTOToLeaveBalance($ctoRequest);

            Log::info('CTO request approved', [
                'cto_request_id' => $ctoRequest->id,
                'school_head_id' => $ctoRequest->school_head_id,
                'hours_approved' => $ctoRequest->requested_hours,
                'cto_days_earned' => $ctoRequest->cto_days_earned,
                'approved_by' => Auth::id()
            ]);

            return redirect()->back()->with('success', 'CTO request approved successfully! CTO has been added to the school head\'s leave balance.');

        } catch (\Exception $e) {
            Log::error('CTO request approval failed', [
                'cto_request_id' => $ctoRequest->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->withErrors([
                'approval' => 'Failed to approve CTO request. Please try again.'
            ]);
        }
    }

    /**
     * Deny a CTO request
     */
    public function deny(Request $request, CTORequest $ctoRequest)
    {
        $request->validate([
            'admin_notes' => 'required|string|min:10|max:1000',
        ]);

        if ($ctoRequest->status !== 'pending') {
            return redirect()->back()->withErrors([
                'status' => 'This request has already been processed.'
            ]);
        }

        try {
            $ctoRequest->update([
                'status' => 'denied',
                'approved_at' => now(),
                'approved_by' => Auth::id(),
                'admin_notes' => $request->admin_notes,
            ]);

            Log::info('CTO request denied', [
                'cto_request_id' => $ctoRequest->id,
                'school_head_id' => $ctoRequest->school_head_id,
                'denied_by' => Auth::id(),
                'reason' => $request->admin_notes
            ]);

            return redirect()->back()->with('success', 'CTO request denied successfully.');

        } catch (\Exception $e) {
            Log::error('CTO request denial failed', [
                'cto_request_id' => $ctoRequest->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->withErrors([
                'denial' => 'Failed to deny CTO request. Please try again.'
            ]);
        }
    }

    /**
     * Add approved CTO to school head's leave balance
     */
    private function addCTOToLeaveBalance(CTORequest $ctoRequest)
    {
        $personnel = $ctoRequest->schoolHead;
        $currentYear = now()->year;
        $ctoDaysEarned = $ctoRequest->cto_days_earned;

        // Find or create the CTO leave record for the current year
        $ctoLeaveRecord = SchoolHeadLeave::where('school_head_id', $personnel->id)
            ->where('leave_type', 'Compensatory Time Off')
            ->where('year', $currentYear)
            ->first();

        if (!$ctoLeaveRecord) {
            // Create new CTO record if it doesn't exist
            $ctoLeaveRecord = SchoolHeadLeave::create([
                'school_head_id' => $personnel->id,
                'leave_type' => 'Compensatory Time Off',
                'year' => $currentYear,
                'available' => 0,
                'used' => 0,
                'ctos_earned' => 0,
                'remarks' => 'Auto-initialized for CTO tracking'
            ]);
        }

        // Update the CTO balance
        $previousCTOsEarned = $ctoLeaveRecord->ctos_earned;
        $previousAvailable = $ctoLeaveRecord->available;

        $ctoLeaveRecord->ctos_earned += $ctoDaysEarned;
        $ctoLeaveRecord->available += $ctoDaysEarned;
        $ctoLeaveRecord->save();

        Log::info('CTO balance updated', [
            'personnel_id' => $personnel->id,
            'cto_request_id' => $ctoRequest->id,
            'previous_ctos_earned' => $previousCTOsEarned,
            'new_ctos_earned' => $ctoLeaveRecord->ctos_earned,
            'previous_available' => $previousAvailable,
            'new_available' => $ctoLeaveRecord->available,
            'days_added' => $ctoDaysEarned
        ]);
    }

    /**
     * Get CTO requests for a specific school head (for dashboard display)
     */
    public function getSchoolHeadRequests($schoolHeadId, $limit = 5)
    {
        return CTORequest::where('school_head_id', $schoolHeadId)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }
}
