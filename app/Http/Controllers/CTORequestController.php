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

use PhpOffice\PhpWord\TemplateProcessor;


class CTORequestController extends Controller
{

    /**
     * Download a CTO request as a Word document using the provided template
     */
    public function download($ctoRequestId)
    {
        $ctoRequest = CTORequest::with(['schoolHead.position', 'schoolHead.school', 'schoolHead.user'])->findOrFail($ctoRequestId);
        $personnel = $ctoRequest->schoolHead;

        // Only allow download if the user is the owner or admin
        $user = Auth::user();
        if (!($user->role === 'admin' || ($personnel && $user->personnel && $user->personnel->id === $personnel->id))) {
            abort(403, 'Unauthorized to download this CTO request.');
        }

        $templatePath = resource_path('views/forms/CTO.docx');
        $templateProcessor = new TemplateProcessor($templatePath);

        // Fill in template variables (update these to match your template placeholders)
        $templateProcessor->setValue('name', $personnel->full_name ?? '-');
        $templateProcessor->setValue('position', $personnel && $personnel->position ? $personnel->position->title : '-');
        $templateProcessor->setValue('office', 'DEPED-' . strtoupper($personnel && $personnel->school ? $personnel->school->division : 'BAYBAY CITY DIVISION'));
        $templateProcessor->setValue('school', $personnel && $personnel->school ? $personnel->school->school_name : '-');
        $templateProcessor->setValue('date_filed', $ctoRequest->created_at ? $ctoRequest->created_at->format('F d, Y') : '-');

        // For CTO form, hardcode as Sick Leave with checkmark
        $templateProcessor->setValue('sick_leave', '☑ Sick Leave');
        $templateProcessor->setValue('vacation_leave', '☐ Vacation Leave');
        $templateProcessor->setValue('others_leave', '☐ Others');

        // Approval status checkmarks
        if ($ctoRequest->status === 'approved') {
            $templateProcessor->setValue('a', '☑');
            $templateProcessor->setValue('d', '☐');
        } elseif ($ctoRequest->status === 'denied') {
            $templateProcessor->setValue('a', '☐');
            $templateProcessor->setValue('d', '☑');
        } else {
            $templateProcessor->setValue('a', '☐');
            $templateProcessor->setValue('d', '☐');
        }

        // Number of hours applied
        $hoursApplied = $ctoRequest->total_hours ?? $ctoRequest->requested_hours ?? 0;
        $templateProcessor->setValue('hours_applied', number_format($hoursApplied) . ' HRS');

        // Inclusive dates (work date)
        $templateProcessor->setValue('inclusive_dates', $ctoRequest->work_date ? strtoupper($ctoRequest->work_date->format('F d, Y')) : '-');

        // Work details
        $templateProcessor->setValue('work_date', $ctoRequest->work_date ? $ctoRequest->work_date->format('M d, Y') : '-');
        $templateProcessor->setValue('morning_in', $ctoRequest->morning_in ?? '-');
        $templateProcessor->setValue('morning_out', $ctoRequest->morning_out ?? '-');
        $templateProcessor->setValue('afternoon_in', $ctoRequest->afternoon_in ?? '-');
        $templateProcessor->setValue('afternoon_out', $ctoRequest->afternoon_out ?? '-');
        $templateProcessor->setValue('total_hours', $ctoRequest->total_hours ?? '-');
        $templateProcessor->setValue('reason', $ctoRequest->reason ?? '-');
        $templateProcessor->setValue('description', $ctoRequest->description ?? '-');
        $templateProcessor->setValue('status', ucfirst($ctoRequest->status));
        $templateProcessor->setValue('approved_at', $ctoRequest->approved_at ? $ctoRequest->approved_at->format('M d, Y') : '-');

        // Certification of Compensatory Overtime Credits
        $templateProcessor->setValue('coc_as_of', $ctoRequest->approved_at ? strtoupper($ctoRequest->approved_at->format('F d, Y')) : '-');
        $templateProcessor->setValue('hours_remaining', number_format($hoursApplied) . ' HRS');

        // Action taken
        $actionTaken = $ctoRequest->status === 'approved' ? 'Approved' : ($ctoRequest->status === 'denied' ? 'Disapproved' : '-');
        $templateProcessor->setValue('action_taken', $actionTaken);
        $templateProcessor->setValue('disapproved_reason', $ctoRequest->admin_notes ?? '');

        // Signatures (these may need to be adjusted based on actual template placeholders)
        $templateProcessor->setValue('applicant_name', $personnel->full_name ?? '-');
        $templateProcessor->setValue('hrmo_name', 'JULIUS CESAR L. DE LA CERNA');
        $templateProcessor->setValue('hrmo_position', 'HRMO II');
        $templateProcessor->setValue('sds_name', 'MANUEL P. ALBAÑO, PhD., CESO V');
        $templateProcessor->setValue('sds_position', 'Schools Division Superintendent');
        $templateProcessor->setValue('recommending_name', 'JOSEMILO P. RUIZ, EdD, CESE');
        $templateProcessor->setValue('recommending_position', 'Assistant Schools Division Superintendent');

        $tempFile = tempnam(sys_get_temp_dir(), 'cto_request_') . '.docx';
        $templateProcessor->saveAs($tempFile);

        return response()->download($tempFile, 'CTO_Request_' . $ctoRequest->id . '.docx')->deleteFileAfterSend(true);
    }
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

        // Compute total hours robustly - calculate morning and afternoon separately
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
