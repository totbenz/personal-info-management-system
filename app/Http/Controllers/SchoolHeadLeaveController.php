<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolHeadLeave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SchoolHeadLeaveController extends Controller
{
    public function index(Request $request)
    {
        $schoolHead = Auth::user()->personnel;
        $year = $request->input('year', Carbon::now()->year);
        $soloParent = $schoolHead->is_solo_parent ?? false;
        $userSex = $schoolHead->sex ?? null;
        $defaultLeaves = SchoolHeadLeave::defaultLeaves($soloParent, $userSex);

        $leaves = SchoolHeadLeave::where('school_head_id', $schoolHead->id)
            ->where('year', $year)
            ->get()
            ->keyBy('leave_type');

        $leaveData = [];
        foreach ($defaultLeaves as $type => $defaultMax) {
            $leave = $leaves->get($type);
            $available = $leave ? $leave->available : $defaultMax;
            $used = $leave ? $leave->used : 0;

            // Calculate dynamic max: if available exceeds default, use available + used as the new max
            $calculatedMax = max($defaultMax, $available + $used);

            $leaveData[] = [
                'type' => $type,
                'max' => $calculatedMax,
                'available' => $available,
                'used' => $used,
                'ctos_earned' => $leave ? $leave->ctos_earned : 0,
                'remarks' => $leave ? $leave->remarks : '',
            ];
        }

        return view('school_head.partials.leaves', compact('leaveData', 'year'));
    }

    /**
     * Add available leave days for the current school head
     */
    public function addLeave(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string|in:Vacation Leave,Sick Leave',
            'days_to_add' => 'required|integer|min:1|max:365',
            'reason' => 'required|string|max:255',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        try {
            $schoolHead = Auth::user()->personnel;
            $year = $request->year;

            // Get or create the leave record
            $leaveRecord = SchoolHeadLeave::firstOrCreate(
                [
                    'school_head_id' => $schoolHead->id,
                    'leave_type' => $request->leave_type,
                    'year' => $year,
                ],
                [
                    'available' => 0,
                    'used' => 0,
                    'ctos_earned' => 0,
                    'remarks' => 'Self-initialized',
                ]
            );

            // Add the requested days to available balance
            $previousAvailable = $leaveRecord->available;
            $leaveRecord->available += $request->days_to_add;

            // Update remarks to include self addition info
            $newRemark = "+" . $request->days_to_add . " days added on " . now()->format('M d, Y') . " (Reason: " . $request->reason . ")";

            if ($leaveRecord->remarks && !in_array($leaveRecord->remarks, ['Auto-initialized', 'Self-initialized'])) {
                $leaveRecord->remarks = $leaveRecord->remarks . "; " . $newRemark;
            } else {
                $leaveRecord->remarks = $newRemark;
            }

            $leaveRecord->save();

            // Log the action
            Log::info('School head self-added leave days', [
                'school_head_id' => $schoolHead->id,
                'school_head_name' => $schoolHead->first_name . ' ' . $schoolHead->last_name,
                'leave_type' => $request->leave_type,
                'days_added' => $request->days_to_add,
                'previous_available' => $previousAvailable,
                'new_available' => $leaveRecord->available,
                'reason' => $request->reason,
                'year' => $year,
            ]);

            return redirect()->back()->with('success',
                "Successfully added {$request->days_to_add} days to your {$request->leave_type} balance. " .
                "Previous balance: {$previousAvailable} days, New balance: {$leaveRecord->available} days."
            );

        } catch (\Exception $e) {
            Log::error('Failed to add leave days for school head', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'school_head_id' => Auth::user()->personnel->id ?? null,
            ]);

            return redirect()->back()->withErrors([
                'error' => 'Failed to add leave days. Please try again.'
            ]);
        }
    }

    /**
     * Deduct available leave days for the current school head
     */
    public function deductLeave(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string|in:Vacation Leave,Sick Leave',
            'days_to_deduct' => 'required|numeric|min:0.5|max:365',
            'reason' => 'required|string|max:255',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        try {
            $schoolHead = Auth::user()->personnel;
            $year = $request->year;

            // Get or create the leave record
            $leaveRecord = SchoolHeadLeave::firstOrCreate(
                [
                    'school_head_id' => $schoolHead->id,
                    'leave_type' => $request->leave_type,
                    'year' => $year,
                ],
                [
                    'available' => 0,
                    'used' => 0,
                    'ctos_earned' => 0,
                    'remarks' => 'Self-initialized',
                ]
            );

            // Check if deduction would result in negative balance
            $daysToDeduct = (float) $request->days_to_deduct;
            $available = (float) $leaveRecord->available;

            if ($available < $daysToDeduct) {
                return redirect()->back()->withErrors([
                    'days_to_deduct' => 'Cannot deduct more days than available. Current balance: ' . $leaveRecord->available . ' days.'
                ]);
            }

            // Deduct the requested days from available balance
            $previousAvailable = $leaveRecord->available;
            $leaveRecord->manual_adjustment -= $daysToDeduct;
            $leaveRecord->available = $available - $daysToDeduct;

            // Update remarks to include self deduction info
            $newRemark = "-" . $request->days_to_deduct . " days deducted on " . now()->format('M d, Y') . " (Reason: " . $request->reason . ")";

            if ($leaveRecord->remarks && !in_array($leaveRecord->remarks, ['Auto-initialized', 'Self-initialized'])) {
                $leaveRecord->remarks = $leaveRecord->remarks . "; " . $newRemark;
            } else {
                $leaveRecord->remarks = $newRemark;
            }

            $leaveRecord->save();

            // Log the action
            Log::info('School head self-deducted leave days', [
                'school_head_id' => $schoolHead->id,
                'school_head_name' => $schoolHead->first_name . ' ' . $schoolHead->last_name,
                'leave_type' => $request->leave_type,
                'days_deducted' => $request->days_to_deduct,
                'previous_available' => $previousAvailable,
                'new_available' => $leaveRecord->available,
                'reason' => $request->reason,
                'year' => $year,
            ]);

            return redirect()->back()->with('success',
                "Successfully deducted {$request->days_to_deduct} days from your {$request->leave_type} balance. " .
                "Previous balance: {$previousAvailable} days, New balance: {$leaveRecord->available} days."
            );

        } catch (\Exception $e) {
            Log::error('Failed to deduct leave days for school head', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'school_head_id' => Auth::user()->personnel->id ?? null,
            ]);

            return redirect()->back()->withErrors([
                'error' => 'Failed to deduct leave days. Please try again.'
            ]);
        }
    }
}
