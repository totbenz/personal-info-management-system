<?php

namespace App\Http\Controllers;

use App\Models\ServiceCreditRequest;
use App\Models\TeacherLeave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ServiceCreditRequestController extends Controller
{
    /**
     * Teacher submits service credit request
     */
    public function store(Request $request)
    {
        $request->validate([
            'work_date' => 'required|date|before_or_equal:today',
            'morning_in' => 'nullable|date_format:H:i',
            'morning_out' => 'nullable|date_format:H:i|after:morning_in',
            'afternoon_in' => 'nullable|date_format:H:i',
            'afternoon_out' => 'nullable|date_format:H:i|after:afternoon_in',
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Log::info('Service Credit store() invoked', [
            'user_id' => Auth::id(),
            'payload' => $request->only(['work_date','morning_in','morning_out','afternoon_in','afternoon_out','reason'])
        ]);

        $user = Auth::user();
        if ($user->role !== 'teacher') {
            abort(403, 'Only teachers can request service credit.');
        }

        $personnel = $user->personnel;
        if (!$personnel) {
            return back()->withErrors(['error' => 'Personnel record not found.']);
        }

        try {
            // Compute total hours from time segments
            $totalHours = 0;
            $segments = [
                ['in' => $request->morning_in, 'out' => $request->morning_out],
                ['in' => $request->afternoon_in, 'out' => $request->afternoon_out],
            ];
            foreach ($segments as $seg) {
                if ($seg['in'] && $seg['out']) {
                    try {
                        $in = Carbon::createFromFormat('H:i', $seg['in']);
                        $out = Carbon::createFromFormat('H:i', $seg['out']);
                        $diff = $out->diffInMinutes($in) / 60; // hours
                        if ($diff > 0) {
                            $totalHours += $diff;
                        }
                    } catch (\Exception $e) {
                        // ignore segment parse errors (already validated)
                    }
                }
            }
            if ($totalHours <= 0) {
                return back()->withErrors(['time' => 'Please provide valid in/out times to compute Service Credit hours.'])->withInput();
            }
            if ($totalHours > 16) { // safeguard
                return back()->withErrors(['time' => 'Total hours exceed allowable limit (16).'])->withInput();
            }
            $requestedDays = round($totalHours / 8, 2); // convert to days

            ServiceCreditRequest::create([
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

            Log::info('Service Credit request created', [
                'teacher_id' => $personnel->id,
                'total_hours' => $totalHours,
                'requested_days' => $requestedDays
            ]);

            return back()->with('success', 'Service Credit request submitted and pending approval.');
        } catch (\Exception $e) {
            Log::error('Failed to submit service credit request', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return back()->withErrors(['error' => 'Failed to submit request.']);
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
