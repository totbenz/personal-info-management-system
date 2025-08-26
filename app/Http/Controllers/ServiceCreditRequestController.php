<?php

namespace App\Http\Controllers;

use App\Models\ServiceCreditRequest;
use App\Models\TeacherLeave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServiceCreditRequestController extends Controller
{
    /**
     * Teacher submits service credit request
     */
    public function store(Request $request)
    {
        // Validation similar structure to CTO request (explicit rules & messages)
        $request->validate([
            'work_date' => 'required|date|before_or_equal:today',
            // At least one complete in/out pair required
            'morning_in' => 'nullable|date_format:H:i|required_with:morning_out',
            'morning_out' => 'nullable|date_format:H:i|after:morning_in|required_with:morning_in',
            'afternoon_in' => 'nullable|date_format:H:i|required_with:afternoon_out',
            'afternoon_out' => 'nullable|date_format:H:i|after:afternoon_in|required_with:afternoon_in',
            'reason' => 'required|string|min:5|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $personnel = $user->personnel;

        // Authorization (teachers & non_teaching per routes) – keep strict teacher requirement here unless expanded
        if (!in_array($user->role, ['teacher'])) {
            return back()->withErrors(['authorization' => 'Only teachers may request Service Credit.'])->with('sc_modal', true);
        }
        if (!$personnel) {
            return back()->withErrors(['error' => 'Personnel record not found.'])->with('sc_modal', true);
        }

        // Prevent duplicate request (pending or approved) on same work_date
        $duplicate = ServiceCreditRequest::where('teacher_id', $personnel->id)
            ->whereDate('work_date', $request->work_date)
            ->whereIn('status', ['pending','approved'])
            ->first();
        if ($duplicate) {
            return back()->withErrors(['work_date' => 'You already have a Service Credit request for this date.']).withInput()->with('sc_modal', true);
        }

        // Collect time segments
        $segments = [];
        if ($request->filled(['morning_in','morning_out'])) {
            $segments[] = ['in' => $request->morning_in, 'out' => $request->morning_out, 'label' => 'AM'];
        }
        if ($request->filled(['afternoon_in','afternoon_out'])) {
            $segments[] = ['in' => $request->afternoon_in, 'out' => $request->afternoon_out, 'label' => 'PM'];
        }
        if (empty($segments)) {
            return back()->withErrors(['time' => 'Provide at least one complete in/out pair.'])->withInput()->with('sc_modal', true);
        }

        // Compute total hours robustly
        $totalHours = 0.0;
        foreach ($segments as $seg) {
            try {
                $in = Carbon::createFromFormat('H:i', $seg['in']);
                $out = Carbon::createFromFormat('H:i', $seg['out']);
                $diff = $out->floatDiffInRealMinutes($in ?? Carbon::now()) / 60; // float hours
                if ($diff <= 0) {
                    return back()->withErrors(['time' => 'Invalid time range for segment '.$seg['label'].'.'])->withInput()->with('sc_modal', true);
                }
                $totalHours += $diff;
            } catch (\Exception $e) {
                return back()->withErrors(['time' => 'Failed to parse time segment '.$seg['label'].'.'])->withInput()->with('sc_modal', true);
            }
        }
        $totalHours = round($totalHours, 2);
        if ($totalHours > 16) {
            return back()->withErrors(['time' => 'Total hours exceed allowable limit (16).'])->withInput()->with('sc_modal', true);
        }
        if ($totalHours <= 0) {
            return back()->withErrors(['time' => 'Computed total hours is zero; check your time entries.'])->withInput()->with('sc_modal', true);
        }

        $requestedDays = round($totalHours / 8, 2);

        try {
            DB::transaction(function() use ($personnel, $request, $totalHours, $requestedDays) {
                $serviceCredit = ServiceCreditRequest::create([
                    'teacher_id' => $personnel->id,
                    'requested_days' => $requestedDays,
                    'work_date' => $request->work_date,
                    'morning_in' => $request->morning_in,
                    'morning_out' => $request->morning_out,
                    'afternoon_in' => $request->afternoon_in,
                    'afternoon_out' => $request->afternoon_out,
                    'total_hours' => $totalHours,
                    'reason' => $request->reason,
                    'description' => $request->description,
                    'status' => 'pending',
                ]);
                
                // Additional debug logging
                Log::info('Service Credit Request Successfully Created', [
                    'service_credit_id' => $serviceCredit->id,
                    'teacher_id' => $personnel->id,
                    'teacher_name' => $personnel->first_name . ' ' . $personnel->last_name,
                    'user_id' => Auth::id(),
                    'requested_days' => $requestedDays,
                    'total_hours' => $totalHours,
                    'work_date' => $request->work_date,
                    'status' => 'pending',
                    'reason' => $request->reason
                ]);
            });

            Log::info('Service Credit request created', [
                'teacher_id' => $personnel->id,
                'total_hours' => $totalHours,
                'requested_days' => $requestedDays,
                'work_date' => $request->work_date,
            ]);

            return back()->with('success', 'Service Credit request submitted and pending approval.')
                ->with('sc_hours', $totalHours)
                ->with('sc_days', $requestedDays);
        } catch (\Exception $e) {
            Log::error('Failed to submit service credit request', [
                'teacher_id' => $personnel->id ?? null,
                'error' => $e->getMessage(),
            ]);
            return back()->withErrors(['error' => 'Failed to submit request.'])->withInput()->with('sc_modal', true);
        }
    }

    /**
     * Admin lists pending service credit requests
     */
    public function index()
    {
        $requests = ServiceCreditRequest::where('status', 'pending')
            ->with('teacher')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.service_credit_requests', compact('requests'));
    }

    public function approve(Request $request, ServiceCreditRequest $serviceCreditRequest)
    {
        if ($serviceCreditRequest->status !== 'pending') {
            return back()->withErrors(['error' => 'Request already processed.']);
        }

        $serviceCreditRequest->update([
            'status' => 'approved',
            'approved_at' => Carbon::now(),
            'approved_by' => Auth::id(),
            'admin_notes' => $request->admin_notes,
        ]);

        // Add / create TeacherLeave record for Service Credit
        $year = now()->year;
        $record = TeacherLeave::firstOrCreate([
            'teacher_id' => $serviceCreditRequest->teacher_id,
            'leave_type' => 'Service Credit',
            'year' => $year,
        ], [
            'available' => 0,
            'used' => 0,
            'remarks' => 'Initialized via Service Credit approval'
        ]);

        $previous = $record->available;
        $record->available += $serviceCreditRequest->requested_days;
        $record->save();

        Log::info('Service credit approved & added', [
            'service_credit_request_id' => $serviceCreditRequest->id,
            'teacher_id' => $serviceCreditRequest->teacher_id,
            'days_added' => $serviceCreditRequest->requested_days,
            'total_hours' => $serviceCreditRequest->total_hours,
            'previous_available' => $previous,
            'new_available' => $record->available,
        ]);

        return back()->with('success', 'Service credit approved and balance updated.');
    }

    public function deny(Request $request, ServiceCreditRequest $serviceCreditRequest)
    {
        if ($serviceCreditRequest->status !== 'pending') {
            return back()->withErrors(['error' => 'Request already processed.']);
        }

        $serviceCreditRequest->update([
            'status' => 'denied',
            'approved_by' => Auth::id(),
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Service credit request denied.');
    }

    /**
     * Lightweight JSON feed for admin dashboard live refresh (pending requests only)
     */
    public function pendingJson()
    {
        $requests = ServiceCreditRequest::where('status','pending')
            ->with('teacher')
            ->orderBy('created_at','desc')
            ->take(20)
            ->get()
            ->map(function($r){
                return [
                    'id' => $r->id,
                    'teacher' => trim(($r->teacher->first_name ?? '').' '.($r->teacher->last_name ?? '')),
                    'work_date' => optional($r->work_date)->toDateString(),
                    'morning_in' => $r->morning_in,
                    'morning_out' => $r->morning_out,
                    'afternoon_in' => $r->afternoon_in,
                    'afternoon_out' => $r->afternoon_out,
                    'total_hours' => $r->total_hours,
                    'requested_days' => $r->requested_days,
                    'reason' => $r->reason,
                    'created_at' => $r->created_at->toDateTimeString(),
                ];
            });
        return response()->json(['data' => $requests]);
    }
}
