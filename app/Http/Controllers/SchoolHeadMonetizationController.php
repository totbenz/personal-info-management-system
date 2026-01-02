<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\SchoolHeadLeave;
use App\Models\SchoolHeadMonetization;
use App\Models\Personnel;
use App\Services\SchoolHeadLeaveAccrualService;
use Carbon\Carbon;

class SchoolHeadMonetizationController extends Controller
{
    protected $leaveAccrualService;

    public function __construct(SchoolHeadLeaveAccrualService $leaveAccrualService)
    {
        $this->leaveAccrualService = $leaveAccrualService;
    }

    public function create()
    {
        $user = Auth::user();
        $personnel = $user->personnel;

        if (!$personnel) {
            abort(403, 'Personnel record not found');
        }

        // Update leave records to ensure current data
        $this->leaveAccrualService->updateLeaveRecords($personnel->id);

        // Get current year's leave records
        $year = Carbon::now()->year;
        $existingLeaves = SchoolHeadLeave::where('school_head_id', $personnel->id)
            ->where('year', $year)
            ->get()
            ->keyBy('leave_type');

        // Get default leaves based on profile
        $schoolHead = Personnel::find($personnel->id);
        $soloParent = $schoolHead->is_solo_parent ?? false;
        $userSex = $schoolHead->sex ?? null;
        $defaultLeaves = SchoolHeadLeave::defaultLeaves($soloParent, $userSex);

        // Prepare leave data array
        $leaveData = [];
        foreach ($defaultLeaves as $type => $defaultMax) {
            $record = $existingLeaves->get($type);
            $available = $record ? $record->available : $defaultMax;
            $used = $record ? $record->used : 0;

            $calculatedMax = max($defaultMax, $available + $used);
            $leaveData[] = [
                'type' => $type,
                'max' => $calculatedMax,
                'available' => $available,
                'used' => $used,
                'ctos_earned' => 0,
                'remarks' => $record ? $record->remarks : '',
            ];
        }

        // Filter based on user profile
        $userSex = $schoolHead->sex ?? null;
        $isSoloParent = $schoolHead->is_solo_parent ?? false;
        $civilStatus = $schoolHead->civil_status ?? null;

        $filteredLeaveData = array_filter($leaveData, function($leave) use ($userSex, $isSoloParent, $civilStatus) {
            if ($leave['type'] === 'Compensatory Time Off') return false;
            if (!$isSoloParent && $leave['type'] === 'Solo Parent Leave') return false;
            if ($leave['type'] === 'Maternity Leave' && $userSex === 'Male') return false;
            if ($leave['type'] === 'Paternity Leave' && $userSex === 'Female') return false;
            if ($leave['type'] === 'Special Leave Benefits for Women' && $userSex !== 'Female') return false;
            if ($leave['type'] === 'Special Privilege Leave' && $civilStatus !== 'Single') return false;
            return true;
        });

        // Create leave balances array
        $leaveBalances = [];
        foreach ($filteredLeaveData as $leave) {
            $leaveBalances[$leave['type']] = $leave['available'];
        }

        return view('school_head.monetization.create', compact('leaveBalances'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.'
            ], 401);
        }

        try {
            $request->validate([
                'days_to_monetize' => 'required|integer|min:1',
                'reason' => 'required|string|max:500',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->errors())
            ], 422);
        }

        $user = Auth::user();
        $personnel = $user->personnel;

        if (!$personnel) {
            return response()->json([
                'success' => false,
                'message' => 'Personnel record not found.'
            ], 404);
        }

        // Get leave balances from the request
        $vlAvailable = $request->input('vl_available', 0);
        $slAvailable = $request->input('sl_available', 0);

        // Calculate maximum monetizable days
        $maxMonetizableDays = max(0, $vlAvailable - 5) + max(0, $slAvailable - 5);

        // Debug logging
        Log::info('School Head Monetization Request:', [
            'user_id' => $user->id,
            'school_head_id' => $personnel->id,
            'days_requested' => $request->days_to_monetize,
            'vl_available' => $vlAvailable,
            'sl_available' => $slAvailable,
            'max_monetizable' => $maxMonetizableDays
        ]);

        // Validate the request
        if ($request->days_to_monetize > $maxMonetizableDays) {
            return response()->json([
                'success' => false,
                'message' => "Invalid amount. You can only monetize up to {$maxMonetizableDays} days. You must retain at least 5 days for each leave type."
            ], 422);
        }

        // Calculate how many days to deduct from each leave type
        // Leave 5 days buffer for each type
        $vlDeductible = max(0, $vlAvailable - 5);
        $slDeductible = max(0, $slAvailable - 5);

        $daysToMonetize = $request->days_to_monetize;
        $vlToDeduct = min($daysToMonetize, $vlDeductible);
        $slToDeduct = max(0, $daysToMonetize - $vlToDeduct);

        Log::info('School Head Monetization Calculation:', [
            'vl_available' => $vlAvailable,
            'sl_available' => $slAvailable,
            'vl_deductible' => $vlDeductible,
            'sl_deductible' => $slDeductible,
            'days_to_monetize' => $daysToMonetize,
            'vl_to_deduct' => $vlToDeduct,
            'sl_to_deduct' => $slToDeduct
        ]);

        // Create monetization request
        $monetization = SchoolHeadMonetization::create([
            'school_head_id' => $personnel->id,
            'days_requested' => $request->days_to_monetize,
            'reason' => $request->reason,
            'status' => 'pending',
            'request_date' => now(),
            'vl_available' => number_format($vlAvailable, 2, '.', ''),
            'sl_available' => number_format($slAvailable, 2, '.', ''),
            'vl_deducted' => number_format($vlToDeduct, 2, '.', ''),
            'sl_deducted' => number_format($slToDeduct, 2, '.', ''),
        ]);

        Log::info('School Head Monetization Request Created:', [
            'monetization_id' => $monetization->id,
            'school_head_id' => $personnel->id,
            'days' => $request->days_to_monetize
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Monetization request submitted successfully! It is now pending approval.'
        ]);
    }

    public function history()
    {
        $user = Auth::user();
        $personnel = $user->personnel;

        if (!$personnel) {
            abort(403, 'Personnel record not found');
        }

        // Get monetization requests
        $monetizationRequests = SchoolHeadMonetization::where('school_head_id', $personnel->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get current leave balances
        $year = Carbon::now()->year;
        $existingLeaves = SchoolHeadLeave::where('school_head_id', $personnel->id)
            ->where('year', $year)
            ->get()
            ->keyBy('leave_type');

        $schoolHead = Personnel::find($personnel->id);
        $soloParent = $schoolHead->is_solo_parent ?? false;
        $userSex = $schoolHead->sex ?? null;
        $defaultLeaves = SchoolHeadLeave::defaultLeaves($soloParent, $userSex);

        $leaveData = [];
        foreach ($defaultLeaves as $type => $defaultMax) {
            $record = $existingLeaves->get($type);
            $available = $record ? $record->available : $defaultMax;
            $used = $record ? $record->used : 0;

            $leaveData[] = [
                'type' => $type,
                'max' => max($defaultMax, $available + $used),
                'available' => $available,
                'used' => $used,
                'ctos_earned' => 0,
                'remarks' => $record ? $record->remarks : '',
            ];
        }

        // Filter based on user profile
        $userSex = $schoolHead->sex ?? null;
        $isSoloParent = $schoolHead->is_solo_parent ?? false;
        $civilStatus = $schoolHead->civil_status ?? null;

        $filteredLeaveData = array_filter($leaveData, function($leave) use ($userSex, $isSoloParent, $civilStatus) {
            if ($leave['type'] === 'Compensatory Time Off') return false;
            if (!$isSoloParent && $leave['type'] === 'Solo Parent Leave') return false;
            if ($leave['type'] === 'Maternity Leave' && $userSex === 'Male') return false;
            if ($leave['type'] === 'Paternity Leave' && $userSex === 'Female') return false;
            if ($leave['type'] === 'Special Leave Benefits for Women' && $userSex !== 'Female') return false;
            if ($leave['type'] === 'Special Privilege Leave' && $civilStatus !== 'Single') return false;
            return true;
        });

        // Create leave balances array
        $leaveBalances = [];
        foreach ($filteredLeaveData as $leave) {
            $leaveBalances[$leave['type']] = $leave['available'];
        }

        return view('school_head.monetization.history', compact('monetizationRequests', 'leaveBalances'));
    }
}
