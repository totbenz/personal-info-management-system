<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NonTeachingLeave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NonTeachingLeaveController extends Controller
{
    /**
     * Add available leave days for the current non-teaching staff
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
            $nonTeaching = Auth::user()->personnel;
            $year = $request->year;

            // Calculate years of service for default leaves
            $yearsOfService = $nonTeaching->employment_start ?
                Carbon::parse($nonTeaching->employment_start)->diffInYears(Carbon::now()) : 0;

            // Get default leaves to ensure we have proper max values
            $defaultLeaves = NonTeachingLeave::defaultLeaves(
                $yearsOfService,
                $nonTeaching->is_solo_parent ?? false,
                $nonTeaching->sex ?? null
            );

            // Get or create the leave record
            $leaveRecord = NonTeachingLeave::firstOrCreate(
                [
                    'non_teaching_id' => $nonTeaching->id,
                    'leave_type' => $request->leave_type,
                    'year' => $year,
                ],
                [
                    'available' => $defaultLeaves[$request->leave_type] ?? 0,
                    'used' => 0,
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
            Log::info('Non-teaching staff self-added leave days', [
                'non_teaching_id' => $nonTeaching->id,
                'non_teaching_name' => $nonTeaching->first_name . ' ' . $nonTeaching->last_name,
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
            Log::error('Failed to add leave days for non-teaching staff', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'non_teaching_id' => Auth::user()->personnel->id ?? null,
            ]);

            return redirect()->back()->withErrors([
                'error' => 'Failed to add leave days. Please try again.'
            ]);
        }
    }

    /**
     * Deduct available leave days for the current non-teaching staff
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
            $nonTeaching = Auth::user()->personnel;
            $year = $request->year;

            // Get or create the leave record
            $leaveRecord = NonTeachingLeave::firstOrCreate(
                [
                    'non_teaching_id' => $nonTeaching->id,
                    'leave_type' => $request->leave_type,
                    'year' => $year,
                ],
                [
                    'available' => 0,
                    'used' => 0,
                    'remarks' => 'Self-initialized',
                ]
            );

            // Check if deduction would result in negative balance
            if ($leaveRecord->available < $request->days_to_deduct) {
                return redirect()->back()->withErrors([
                    'days_to_deduct' => 'Cannot deduct more days than available. Current balance: ' . $leaveRecord->available . ' days.'
                ]);
            }

            // Deduct the requested days from available balance
            $previousAvailable = $leaveRecord->available;
            $leaveRecord->available -= $request->days_to_deduct;

            // Update remarks to include self deduction info
            $newRemark = "-" . $request->days_to_deduct . " days deducted on " . now()->format('M d, Y') . " (Reason: " . $request->reason . ")";

            if ($leaveRecord->remarks && !in_array($leaveRecord->remarks, ['Auto-initialized', 'Self-initialized'])) {
                $leaveRecord->remarks = $leaveRecord->remarks . "; " . $newRemark;
            } else {
                $leaveRecord->remarks = $newRemark;
            }

            $leaveRecord->save();

            // Log the action
            Log::info('Non-teaching staff self-deducted leave days', [
                'non_teaching_id' => $nonTeaching->id,
                'non_teaching_name' => $nonTeaching->first_name . ' ' . $nonTeaching->last_name,
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
            Log::error('Failed to deduct leave days for non-teaching staff', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'non_teaching_id' => Auth::user()->personnel->id ?? null,
            ]);

            return redirect()->back()->withErrors([
                'error' => 'Failed to deduct leave days. Please try again.'
            ]);
        }
    }
}
