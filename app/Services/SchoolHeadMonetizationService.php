<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\SchoolHeadMonetization;
use App\Models\SchoolHeadLeave;
use Carbon\Carbon;

class SchoolHeadMonetizationService
{
    /**
     * Process approved monetization request for school head
     */
    public function processApprovedMonetization(SchoolHeadMonetization $monetization)
    {
        try {
            Log::info('Processing school head monetization approval', [
                'monetization_id' => $monetization->id,
                'school_head_id' => $monetization->school_head_id,
                'days_requested' => $monetization->days_requested,
                'vl_deducted' => $monetization->vl_deducted,
                'sl_deducted' => $monetization->sl_deducted
            ]);

            // Get the year for this monetization
            $year = Carbon::parse($monetization->request_date)->year;

            // Use the pre-calculated deduction values
            $vlToDeduct = $monetization->vl_deducted;
            $slToDeduct = $monetization->sl_deducted;

            Log::info('School Head Monetization Using Pre-calculated Values', [
                'school_head_id' => $monetization->school_head_id,
                'year' => $year,
                'days_to_monetize' => $monetization->days_requested,
                'vl_to_deduct' => $vlToDeduct,
                'sl_to_deduct' => $slToDeduct
            ]);

            // Deduct from Vacation Leave
            if ($vlToDeduct > 0) {
                $this->deductFromSchoolHeadLeave(
                    $monetization->school_head_id,
                    'Vacation Leave',
                    $vlToDeduct,
                    $year
                );
            }

            // Deduct from Sick Leave
            if ($slToDeduct > 0) {
                $this->deductFromSchoolHeadLeave(
                    $monetization->school_head_id,
                    'Sick Leave',
                    $slToDeduct,
                    $year
                );
            }

            // Update monetization status
            $monetization->status = 'approved';
            $monetization->approval_date = now();
            $monetization->save();

            Log::info('School Head Monetization Processed Successfully', [
                'monetization_id' => $monetization->id,
                'school_head_id' => $monetization->school_head_id,
                'vl_deducted' => $vlToDeduct,
                'sl_deducted' => $slToDeduct,
                'approval_date' => $monetization->approval_date
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Error processing school head monetization', [
                'monetization_id' => $monetization->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Deduct days from school head leave
     */
    private function deductFromSchoolHeadLeave($schoolHeadId, $leaveType, $days, $year)
    {
        // Try to find the leave record with different case variations
        $leaveRecord = SchoolHeadLeave::where('school_head_id', $schoolHeadId)
            ->where('year', $year)
            ->where(function($query) use ($leaveType) {
                $query->where('leave_type', $leaveType)
                      ->orWhere('leave_type', strtoupper($leaveType))
                      ->orWhere('leave_type', strtolower($leaveType));
            })
            ->first();

        if (!$leaveRecord) {
            Log::warning('School Head leave record not found', [
                'school_head_id' => $schoolHeadId,
                'leave_type' => $leaveType,
                'year' => $year,
                'searched_types' => [
                    $leaveType,
                    strtoupper($leaveType),
                    strtolower($leaveType)
                ]
            ]);
            return false;
        }

        $oldAvailable = $leaveRecord->available;
        $oldUsed = $leaveRecord->used;

        // Deduct from available and add to used
        $leaveRecord->available = max(0, $leaveRecord->available - $days);
        $leaveRecord->used += $days;
        $leaveRecord->save();

        Log::info('Deducted from school head leave', [
            'school_head_id' => $schoolHeadId,
            'leave_type' => $leaveType,
            'year' => $year,
            'days_deducted' => $days,
            'old_available' => $oldAvailable,
            'new_available' => $leaveRecord->available,
            'old_used' => $oldUsed,
            'new_used' => $leaveRecord->used
        ]);

        return true;
    }

    /**
     * Process rejected monetization request
     */
    public function processRejectedMonetization(SchoolHeadMonetization $monetization, $reason = null)
    {
        $monetization->status = 'rejected';
        $monetization->rejection_reason = $reason;
        $monetization->approval_date = now();
        $monetization->save();

        Log::info('School Head Monetization Rejected', [
            'monetization_id' => $monetization->id,
            'school_head_id' => $monetization->school_head_id,
            'reason' => $reason
        ]);

        return true;
    }
}
