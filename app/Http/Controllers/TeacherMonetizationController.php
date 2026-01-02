<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\TeacherLeave;
use App\Models\LeaveMonetization;
use App\Models\Personnel;
use App\Services\LeaveMonetizationService;
use Carbon\Carbon;

class TeacherMonetizationController extends Controller
{
    protected $monetizationService;

    public function __construct(LeaveMonetizationService $monetizationService)
    {
        $this->monetizationService = $monetizationService;
    }

    public function create()
    {
        $user = Auth::user();
        $personnel = $user->personnel;

        if (!$personnel) {
            return redirect()->back()->with('error', 'Personnel record not found.');
        }

        // Get current year's leave records
        $year = Carbon::now()->year;
        $existingLeaves = TeacherLeave::where('teacher_id', $personnel->id)
            ->where('year', $year)
            ->get()
            ->keyBy('leave_type');

        // Get default leaves based on profile
        $teacher = Personnel::find($personnel->id);
        $soloParent = $teacher->is_solo_parent ?? false;
        $userSex = $teacher->sex ?? null;
        $defaultLeaves = TeacherLeave::defaultLeaves($soloParent, $userSex);

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
        $userSex = $teacher->sex ?? null;
        $isSoloParent = $teacher->is_solo_parent ?? false;
        $civilStatus = $teacher->civil_status ?? null;

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

        return view('teacher.monetization.create', compact('leaveBalances'));
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
        Log::info('Teacher Monetization Request:', [
            'user_id' => $user->id,
            'teacher_id' => $personnel->id,
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

        Log::info('Teacher Monetization Calculation:', [
            'vl_available' => $vlAvailable,
            'sl_available' => $slAvailable,
            'vl_deductible' => $vlDeductible,
            'sl_deductible' => $slDeductible,
            'days_to_monetize' => $daysToMonetize,
            'vl_to_deduct' => $vlToDeduct,
            'sl_to_deduct' => $slToDeduct
        ]);

        try {
            // Create the monetization request
            $monetization = $this->monetizationService->createMonetizationRequest($personnel, 'teacher', [
                'vl_days_used' => $vlToDeduct,
                'sl_days_used' => $slToDeduct,
                'total_days' => $request->days_to_monetize,
                'reason' => $request->reason,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Monetization request submitted successfully! It is now pending approval.',
                'data' => [
                    'id' => $monetization->id,
                    'total_days' => $monetization->total_days,
                    'status' => $monetization->status,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating teacher monetization request', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your request. Please try again.'
            ], 500);
        }
    }

    public function history()
    {
        $user = Auth::user();
        $personnel = $user->personnel;

        if (!$personnel) {
            abort(403, 'Personnel record not found');
        }

        // Get monetization requests
        $monetizationRequests = LeaveMonetization::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get current leave balances
        $year = Carbon::now()->year;
        $existingLeaves = TeacherLeave::where('teacher_id', $personnel->id)
            ->where('year', $year)
            ->get()
            ->keyBy('leave_type');

        $teacher = Personnel::find($personnel->id);
        $soloParent = $teacher->is_solo_parent ?? false;
        $userSex = $teacher->sex ?? null;
        $defaultLeaves = TeacherLeave::defaultLeaves($soloParent, $userSex);

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
        $userSex = $teacher->sex ?? null;
        $isSoloParent = $teacher->is_solo_parent ?? false;
        $civilStatus = $teacher->civil_status ?? null;

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

        return view('teacher.monetization.history', compact('monetizationRequests', 'leaveBalances'));
    }
}
