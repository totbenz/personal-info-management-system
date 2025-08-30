<?php

namespace App\Http\Controllers;

use App\Models\CTORequest;
use App\Models\Personnel;
use App\Models\SchoolHeadLeave;
use App\Services\CTOService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CTORequestController extends Controller
{
    protected $ctoService;

    public function __construct(CTOService $ctoService)
    {
        $this->ctoService = $ctoService;
    }
    /**
     * Store a new CTO request
     */
    public function store(Request $request)
    {
        $request->validate([
            'work_date' => 'required|date|before_or_equal:today',
            // Time segment validation - at least one complete pair required
            'morning_in' => 'nullable|date_format:H:i|required_with:morning_out',
            'morning_out' => 'nullable|date_format:H:i|after:morning_in|required_with:morning_in',
            'afternoon_in' => 'nullable|date_format:H:i|required_with:afternoon_out',
            'afternoon_out' => 'nullable|date_format:H:i|after:afternoon_in|required_with:afternoon_in',
            'reason' => 'required|string|min:10|max:500',
            'description' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $personnel = $user->personnel;

        // Authorization: allow school heads and non-teaching personnel to request CTO
        if (!in_array($user->role, ['school_head', 'non_teaching']) || !$personnel) {
            return redirect()->back()->withErrors([
                'authorization' => 'Only school heads and non-teaching personnel can submit CTO requests.'
            ]);
        }

        // Prevent duplicate request for same work date
        $existingRequest = CTORequest::where('school_head_id', $personnel->id)
            ->where('work_date', $request->work_date)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingRequest) {
            return redirect()->back()->withErrors([
                'work_date' => 'You already have a CTO request for this date.'
            ])->withInput();
        }

        // Collect time segments (similar to ServiceCreditRequest)
        $segments = [];
        if ($request->filled(['morning_in', 'morning_out'])) {
            $segments[] = ['in' => $request->morning_in, 'out' => $request->morning_out, 'label' => 'AM'];
        }
        if ($request->filled(['afternoon_in', 'afternoon_out'])) {
            $segments[] = ['in' => $request->afternoon_in, 'out' => $request->afternoon_out, 'label' => 'PM'];
        }
        
        if (empty($segments)) {
            return redirect()->back()->withErrors([
                'time' => 'Provide at least one complete in/out time pair.'
            ])->withInput();
        }

        // Compute total hours robustly
        $totalHours = 0.0;
        foreach ($segments as $seg) {
            try {
                $in = Carbon::createFromFormat('H:i', $seg['in']);
                $out = Carbon::createFromFormat('H:i', $seg['out']);
                $diff = $out->floatDiffInRealMinutes($in) / 60; // float hours
                if ($diff <= 0) {
                    return redirect()->back()->withErrors([
                        'time' => 'Invalid time range for ' . $seg['label'] . ' segment.'
                    ])->withInput();
                }
                $totalHours += $diff;
            } catch (\Exception $e) {
                return redirect()->back()->withErrors([
                    'time' => 'Failed to parse time segment ' . $seg['label'] . '.'
                ])->withInput();
            }
        }
        
        $totalHours = round($totalHours, 2);
        if ($totalHours > 16) {
            return redirect()->back()->withErrors([
                'time' => 'Total hours exceed allowable limit (16 hours).'
            ])->withInput();
        }
        if ($totalHours <= 0) {
            return redirect()->back()->withErrors([
                'time' => 'Computed total hours is zero; check your time entries.'
            ])->withInput();
        }

        $requestedHours = (int) ceil($totalHours); // For backward compatibility

        try {
            CTORequest::create([
                // Reuse legacy column 'school_head_id' to store personnel id for both roles
                'school_head_id' => $personnel->id,
                'requested_hours' => $requestedHours, // For backward compatibility
                'work_date' => $request->work_date,
                'morning_in' => $request->morning_in,
                'morning_out' => $request->morning_out,
                'afternoon_in' => $request->afternoon_in,
                'afternoon_out' => $request->afternoon_out,
                'total_hours' => $totalHours,
                // Keep legacy fields for backward compatibility during transition
                'start_time' => $request->morning_in ?? '08:00', // Default fallback
                'end_time' => $request->afternoon_out ?? ($request->morning_out ?? '17:00'), // Default fallback
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

            // Add CTO to school head's leave balance using the new CTO service
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
     * Add approved CTO to school head's leave balance using the new CTO service
     */
    private function addCTOToLeaveBalance(CTORequest $ctoRequest)
    {
        $personnel = $ctoRequest->schoolHead;
        $currentYear = now()->year;
        $ctoDaysEarned = $ctoRequest->cto_days_earned;

        // Create individual CTO entry with expiration date using the new service
        $this->ctoService->createCTOEntry($ctoRequest);

        // Update the legacy SchoolHeadLeave record for backward compatibility
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

        // Update the CTO balance using the service
        $this->ctoService->updateSchoolHeadLeaveBalance($personnel->id);

        Log::info('CTO balance updated with new service', [
            'personnel_id' => $personnel->id,
            'cto_request_id' => $ctoRequest->id,
            'days_added' => $ctoDaysEarned,
            'expiry_date' => now()->addYear()->toDateString()
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
