<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeacherLeave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TeacherLeaveController extends Controller
{
    /**
     * Add available leave days for the current teacher
     */
    public function addLeave(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string|in:Personal Leave,Sick Leave',
            'days_to_add' => 'required|integer|min:1|max:365',
            'reason' => 'required|string|max:255',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        try {
            $teacher = Auth::user()->personnel;
            $year = $request->year;

            // Calculate years of service for default leaves
            $yearsOfService = $teacher->employment_start ? 
                Carbon::parse($teacher->employment_start)->diffInYears(Carbon::now()) : 0;

            // Get default leaves to ensure we have proper max values
            $defaultLeaves = TeacherLeave::defaultLeaves(
                $yearsOfService,
                $teacher->is_solo_parent ?? false,
                $teacher->sex ?? null
            );

            // Get or create the leave record
            $leaveRecord = TeacherLeave::firstOrCreate(
                [
                    'teacher_id' => $teacher->id,
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
            Log::info('Teacher self-added leave days', [
                'teacher_id' => $teacher->id,
                'teacher_name' => $teacher->first_name . ' ' . $teacher->last_name,
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
            Log::error('Failed to add leave days for teacher', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'teacher_id' => Auth::user()->personnel->id ?? null,
            ]);

            return redirect()->back()->withErrors([
                'error' => 'Failed to add leave days. Please try again.'
            ]);
        }
    }
}
