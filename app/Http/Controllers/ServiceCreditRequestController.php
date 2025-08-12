<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceCreditRequest;
use App\Models\ServiceCredit;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ServiceCreditRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'requested_personal_leave_credits' => 'required|integer|min:0|max:365',
            'requested_sick_leave_credits' => 'required|integer|min:0|max:365',
            'justification' => 'required|string|min:10|max:1000',
        ]);

        $user = Auth::user();
        $personnel = $user->personnel;

        // Check if there's already a request for this year
        $existingRequest = ServiceCreditRequest::where('personnel_id', $personnel->id)
            ->where('year', $request->year)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingRequest) {
            return redirect()->back()->withErrors([
                'year' => 'You already have a service credit request for this year.'
            ]);
        }

        ServiceCreditRequest::create([
            'personnel_id' => $personnel->id,
            'year' => $request->year,
            'requested_personal_leave_credits' => $request->requested_personal_leave_credits,
            'requested_sick_leave_credits' => $request->requested_sick_leave_credits,
            'justification' => $request->justification,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Service credit request submitted successfully! You will be notified once it\'s reviewed.');
    }

    public function index()
    {
        // For admin to view all pending requests
        $requests = ServiceCreditRequest::with('personnel.school')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.service-credit-requests.index', compact('requests'));
    }

    public function approve(Request $request, ServiceCreditRequest $serviceCreditRequest)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $serviceCreditRequest->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'admin_notes' => $request->admin_notes
        ]);

        // Create or update the service credit record
        ServiceCredit::updateOrCreate(
            [
                'personnel_id' => $serviceCreditRequest->personnel_id,
                'year' => $serviceCreditRequest->year
            ],
            [
                'personal_leave_credits' => $serviceCreditRequest->requested_personal_leave_credits,
                'sick_leave_credits' => $serviceCreditRequest->requested_sick_leave_credits,
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id()
            ]
        );

        return redirect()->back()->with('success', 'Service credit request approved successfully!');
    }

    public function deny(Request $request, ServiceCreditRequest $serviceCreditRequest)
    {
        $request->validate([
            'admin_notes' => 'required|string|min:5|max:500'
        ]);

        $serviceCreditRequest->update([
            'status' => 'denied',
            'admin_notes' => $request->admin_notes
        ]);

        return redirect()->back()->with('success', 'Service credit request denied.');
    }
}
