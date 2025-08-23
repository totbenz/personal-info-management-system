<?php

namespace App\Services;

use App\Models\CTOEntry;
use App\Models\CTOUsage;
use App\Models\CTORequest;
use App\Models\Personnel;
use App\Models\LeaveRequest;
use App\Models\SchoolHeadLeave;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CTOService
{
    /**
     * Create a new CTO entry when a CTO request is approved
     */
    public function createCTOEntry(CTORequest $ctoRequest): CTOEntry
    {
        $earnedDate = now()->toDateString();
        $expiryDate = now()->addYear()->toDateString();
        $daysEarned = $ctoRequest->cto_days_earned;

        $ctoEntry = CTOEntry::create([
            'school_head_id' => $ctoRequest->school_head_id,
            'cto_request_id' => $ctoRequest->id,
            'days_earned' => $daysEarned,
            'days_remaining' => $daysEarned,
            'earned_date' => $earnedDate,
            'expiry_date' => $expiryDate,
            'is_expired' => false,
        ]);

        Log::info('CTO entry created', [
            'cto_entry_id' => $ctoEntry->id,
            'school_head_id' => $ctoRequest->school_head_id,
            'days_earned' => $daysEarned,
            'earned_date' => $earnedDate,
            'expiry_date' => $expiryDate,
            'cto_request_id' => $ctoRequest->id,
        ]);

        return $ctoEntry;
    }

    /**
     * Use CTO days for a leave request using FIFO (oldest first)
     */
    public function useCTOForLeave(int $schoolHeadId, float $daysNeeded, ?int $leaveRequestId = null): array
    {
        if ($daysNeeded <= 0) {
            throw new \InvalidArgumentException('Days needed must be greater than 0');
        }

        $availableEntries = CTOEntry::getAvailableForSchoolHead($schoolHeadId);
        $totalAvailable = $availableEntries->sum('days_remaining');

        if ($totalAvailable < $daysNeeded) {
            throw new \InvalidArgumentException("Insufficient CTO days. Available: {$totalAvailable}, Needed: {$daysNeeded}");
        }

        $usages = [];
        $remainingDaysNeeded = $daysNeeded;

        DB::beginTransaction();
        try {
            foreach ($availableEntries as $entry) {
                if ($remainingDaysNeeded <= 0) {
                    break;
                }

                $daysToUse = min($remainingDaysNeeded, $entry->days_remaining);
                
                $usage = $entry->useDays(
                    $daysToUse,
                    $leaveRequestId,
                    'leave',
                    "Used for leave request"
                );

                $usages[] = $usage;
                $remainingDaysNeeded -= $daysToUse;

                Log::info('CTO days used', [
                    'cto_entry_id' => $entry->id,
                    'school_head_id' => $schoolHeadId,
                    'days_used' => $daysToUse,
                    'days_remaining_in_entry' => $entry->fresh()->days_remaining,
                    'leave_request_id' => $leaveRequestId,
                ]);
            }

            DB::commit();

            Log::info('CTO usage completed', [
                'school_head_id' => $schoolHeadId,
                'total_days_used' => $daysNeeded,
                'entries_used' => count($usages),
                'leave_request_id' => $leaveRequestId,
            ]);

            return $usages;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to use CTO for leave', [
                'school_head_id' => $schoolHeadId,
                'days_needed' => $daysNeeded,
                'leave_request_id' => $leaveRequestId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get CTO balance summary for a school head
     */
    public function getCTOBalance(int $schoolHeadId): array
    {
        $entries = CTOEntry::where('school_head_id', $schoolHeadId)
            ->where('days_remaining', '>', 0)
            ->where('is_expired', false)
            ->where('expiry_date', '>=', now()->toDateString())
            ->orderBy('earned_date', 'asc')
            ->get();

        $totalAvailable = $entries->sum('days_remaining');
        $totalEarned = CTOEntry::where('school_head_id', $schoolHeadId)->sum('days_earned');
        $totalUsed = CTOUsage::where('school_head_id', $schoolHeadId)->sum('days_used');
        
        // Calculate expired days
        $expiredDays = CTOEntry::where('school_head_id', $schoolHeadId)
            ->where(function($query) {
                $query->where('is_expired', true)
                      ->orWhere('expiry_date', '<', now()->toDateString());
            })
            ->sum('days_remaining');

        return [
            'total_available' => $totalAvailable,
            'total_earned' => $totalEarned,
            'total_used' => $totalUsed,
            'expired_days' => $expiredDays,
            'entries' => $entries->map(function ($entry) {
                return [
                    'id' => $entry->id,
                    'days_remaining' => $entry->days_remaining,
                    'days_earned' => $entry->days_earned,
                    'earned_date' => $entry->earned_date,
                    'expiry_date' => $entry->expiry_date,
                    'days_until_expiry' => now()->diffInDays($entry->expiry_date, false),
                ];
            }),
        ];
    }

    /**
     * Expire old CTO entries and update school head leave balances
     */
    public function expireOldCTOs(): int
    {
        $expiredCount = CTOEntry::expireOldEntries();

        if ($expiredCount > 0) {
            // Update school head leave balances for affected personnel
            $affectedSchoolHeads = CTOEntry::where('expiry_date', '<', now()->toDateString())
                ->where('is_expired', true)
                ->pluck('school_head_id')
                ->unique();

            foreach ($affectedSchoolHeads as $schoolHeadId) {
                $this->updateSchoolHeadLeaveBalance($schoolHeadId);
            }

            Log::info('Expired old CTO entries', [
                'expired_count' => $expiredCount,
                'affected_school_heads' => $affectedSchoolHeads->count(),
            ]);
        }

        return $expiredCount;
    }

    /**
     * Update school head leave balance to reflect current CTO availability
     */
    public function updateSchoolHeadLeaveBalance(int $schoolHeadId): void
    {
        $currentYear = now()->year;
        $totalAvailable = CTOEntry::getTotalAvailableDays($schoolHeadId);
        $totalEarned = CTOEntry::where('school_head_id', $schoolHeadId)->sum('days_earned');
        $totalUsed = CTOUsage::where('school_head_id', $schoolHeadId)->sum('days_used');

        $ctoLeaveRecord = SchoolHeadLeave::where('school_head_id', $schoolHeadId)
            ->where('leave_type', 'Compensatory Time Off')
            ->where('year', $currentYear)
            ->first();

        if ($ctoLeaveRecord) {
            $ctoLeaveRecord->update([
                'available' => $totalAvailable,
                'ctos_earned' => $totalEarned,
                'used' => $totalUsed,
            ]);

            Log::info('Updated school head CTO balance', [
                'school_head_id' => $schoolHeadId,
                'available' => $totalAvailable,
                'earned' => $totalEarned,
                'used' => $totalUsed,
            ]);
        }
    }

    /**
     * Check and use CTO days when a school head takes Compensatory Time Off leave
     */
    public function handleCTOLeaveRequest(LeaveRequest $leaveRequest): void
    {
        if ($leaveRequest->leave_type !== 'Compensatory Time Off') {
            return;
        }

        $personnel = $leaveRequest->user->personnel;
        if (!$personnel) {
            throw new \InvalidArgumentException('Personnel not found for user');
        }

        $startDate = Carbon::parse($leaveRequest->start_date);
        $endDate = Carbon::parse($leaveRequest->end_date);
        $leaveDays = $startDate->diffInDays($endDate) + 1;

        // Use CTO days using FIFO
        $this->useCTOForLeave($personnel->id, $leaveDays, $leaveRequest->id);

        // Update the school head leave balance
        $this->updateSchoolHeadLeaveBalance($personnel->id);

        Log::info('CTO leave request processed', [
            'leave_request_id' => $leaveRequest->id,
            'school_head_id' => $personnel->id,
            'days_used' => $leaveDays,
        ]);
    }

    /**
     * Get CTO usage history for a school head
     */
    public function getCTOUsageHistory(int $schoolHeadId, int $limit = 50): array
    {
        $usages = CTOUsage::getHistoryForSchoolHead($schoolHeadId, $limit);

        return $usages->map(function ($usage) {
            return [
                'id' => $usage->id,
                'days_used' => $usage->days_used,
                'used_date' => $usage->used_date,
                'usage_type' => $usage->usage_type,
                'notes' => $usage->notes,
                'cto_earned_date' => $usage->ctoEntry->earned_date ?? null,
                'cto_expiry_date' => $usage->ctoEntry->expiry_date ?? null,
                'leave_request' => $usage->leaveRequest ? [
                    'id' => $usage->leaveRequest->id,
                    'leave_type' => $usage->leaveRequest->leave_type,
                    'start_date' => $usage->leaveRequest->start_date,
                    'end_date' => $usage->leaveRequest->end_date,
                ] : null,
            ];
        })->toArray();
    }
}
