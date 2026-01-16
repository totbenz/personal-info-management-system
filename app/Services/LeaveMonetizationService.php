<?php

namespace App\Services;

use App\Models\LeaveMonetization;
use App\Models\TeacherLeave;
use App\Models\NonTeachingLeave;
use App\Models\SchoolHeadLeave;
use App\Models\Personnel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LeaveMonetizationService
{
    const MINIMUM_BUFFER_DAYS = 5;

    /**
     * Calculate maximum monetizable days for a personnel
     */
    public function calculateMaxMonetizableDays(Personnel $personnel, string $userType): array
    {
        $year = date('Y');
        $result = [
            'vl_available' => 0,
            'sl_available' => 0,
            'vl_max_monetizable' => 0,
            'sl_max_monetizable' => 0,
            'total_max_monetizable' => 0,
        ];

        try {
            switch ($userType) {
                case 'teacher':
                    $leaves = $this->getTeacherLeaves($personnel->id, $year);
                    break;
                case 'non_teaching':
                    $leaves = $this->getNonTeachingLeaves($personnel->id, $year);
                    break;
                case 'school_head':
                    $leaves = $this->getSchoolHeadLeaves($personnel->id, $year);
                    break;
            }

            $result['vl_available'] = $leaves['vl_available'] ?? 0;
            $result['sl_available'] = $leaves['sl_available'] ?? 0;

            // Calculate maximum monetizable days (available - 5 buffer)
            $result['vl_max_monetizable'] = max(0, $result['vl_available'] - self::MINIMUM_BUFFER_DAYS);
            $result['sl_max_monetizable'] = max(0, $result['sl_available'] - self::MINIMUM_BUFFER_DAYS);
            $result['total_max_monetizable'] = $result['vl_max_monetizable'] + $result['sl_max_monetizable'];

        } catch (\Exception $e) {
            Log::error('Error calculating monetizable days', [
                'personnel_id' => $personnel->id,
                'user_type' => $userType,
                'error' => $e->getMessage()
            ]);
        }

        return $result;
    }

    /**
     * Validate monetization request
     */
    public function validateMonetizationRequest(Personnel $personnel, string $userType, int $requestedDays): array
    {
        $validation = [
            'valid' => false,
            'vl_days_used' => 0,
            'sl_days_used' => 0,
            'error' => null,
        ];

        $maxDays = $this->calculateMaxMonetizableDays($personnel, $userType);

        if ($requestedDays <= 0) {
            $validation['error'] = 'Number of days must be greater than 0.';
            return $validation;
        }

        if ($requestedDays > $maxDays['total_max_monetizable']) {
            $validation['error'] = "Invalid amount. You can only monetize up to {$maxDays['total_max_monetizable']} days. You must retain at least 5 days for each leave type.";
            return $validation;
        }

        // Calculate distribution:优先使用 VL
        $remainingDays = $requestedDays;

        // Use VL first
        $vlToUse = min($maxDays['vl_max_monetizable'], $remainingDays);
        $remainingDays -= $vlToUse;

        // Use SL if needed
        $slToUse = 0;
        if ($remainingDays > 0) {
            $slToUse = min($maxDays['sl_max_monetizable'], $remainingDays);
            $remainingDays -= $slToUse;
        }

        if ($remainingDays > 0) {
            $validation['error'] = 'Unable to calculate proper day distribution.';
            return $validation;
        }

        $validation['valid'] = true;
        $validation['vl_days_used'] = $vlToUse;
        $validation['sl_days_used'] = $slToUse;

        return $validation;
    }

    /**
     * Create monetization request
     */
    public function createMonetizationRequest(Personnel $personnel, string $userType, array $data): LeaveMonetization
    {
        return DB::transaction(function () use ($personnel, $userType, $data) {
            $monetization = LeaveMonetization::create([
                'user_id' => auth()->id(),
                'personnel_id' => $personnel->id,
                'user_type' => $userType,
                'vl_days_used' => $data['vl_days_used'],
                'sl_days_used' => $data['sl_days_used'],
                'total_days' => $data['total_days'],
                'total_amount' => $data['total_amount'] ?? 0,
                'status' => 'pending',
                'reason' => $data['reason'] ?? null,
            ]);

            Log::info('Leave monetization request created', [
                'monetization_id' => $monetization->id,
                'personnel_id' => $personnel->id,
                'user_type' => $userType,
                'total_days' => $data['total_days'],
            ]);

            return $monetization;
        });
    }

    /**
     * Process approved monetization (deduct from leave balances)
     */
    public function processApprovedMonetization(LeaveMonetization $monetization): bool
    {
        return DB::transaction(function () use ($monetization) {
            try {
                $year = date('Y');
                $personnel = $monetization->personnel;

                // Deduct from appropriate leave balance table
                switch ($monetization->user_type) {
                    case 'teacher':
                        $this->deductFromTeacherLeave($personnel->id, $year, $monetization->vl_days_used, $monetization->sl_days_used);
                        break;
                    case 'non_teaching':
                        $this->deductFromNonTeachingLeave($personnel->id, $year, $monetization->vl_days_used, $monetization->sl_days_used);
                        break;
                    case 'school_head':
                        $this->deductFromSchoolHeadLeave($personnel->id, $year, $monetization->vl_days_used, $monetization->sl_days_used);
                        break;
                }

                $monetization->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                ]);

                Log::info('Leave monetization approved and processed', [
                    'monetization_id' => $monetization->id,
                    'vl_days' => $monetization->vl_days_used,
                    'sl_days' => $monetization->sl_days_used,
                ]);

                return true;

            } catch (\Exception $e) {
                Log::error('Error processing approved monetization', [
                    'monetization_id' => $monetization->id,
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }
        });
    }

    /**
     * Get teacher leave balances
     */
    private function getTeacherLeaves(int $teacherId, string $year): array
    {
        $leaves = TeacherLeave::where('teacher_id', $teacherId)
            ->where('year', $year)
            ->whereIn('leave_type', ['Vacation Leave', 'Sick Leave'])
            ->get()
            ->keyBy('leave_type');

        return [
            'vl_available' => $leaves->get('Vacation Leave')->available ?? 0,
            'sl_available' => $leaves->get('Sick Leave')->available ?? 0,
        ];
    }

    /**
     * Get non-teaching leave balances
     */
    private function getNonTeachingLeaves(int $nonTeachingId, string $year): array
    {
        $leaves = NonTeachingLeave::where('non_teaching_id', $nonTeachingId)
            ->where('year', $year)
            ->whereIn('leave_type', ['Vacation Leave', 'Sick Leave'])
            ->get()
            ->keyBy('leave_type');

        $vlAvailable = $leaves->get('Vacation Leave')->available ?? 0;
        $slAvailable = $leaves->get('Sick Leave')->available ?? 0;

        // Debug logging
        Log::info('Non-teaching leaves query:', [
            'non_teaching_id' => $nonTeachingId,
            'year' => $year,
            'vl_available' => $vlAvailable,
            'sl_available' => $slAvailable,
            'leaves_found' => $leaves->toArray()
        ]);

        return [
            'vl_available' => $vlAvailable,
            'sl_available' => $slAvailable,
        ];
    }

    /**
     * Get school head leave balances
     */
    private function getSchoolHeadLeaves(int $schoolHeadId, string $year): array
    {
        $leaves = SchoolHeadLeave::where('school_head_id', $schoolHeadId)
            ->where('year', $year)
            ->whereIn('leave_type', ['Vacation Leave', 'Sick Leave'])
            ->get()
            ->keyBy('leave_type');

        return [
            'vl_available' => $leaves->get('Vacation Leave')->available ?? 0,
            'sl_available' => $leaves->get('Sick Leave')->available ?? 0,
        ];
    }

    /**
     * Deduct from teacher leave balance
     */
    private function deductFromTeacherLeave(int $teacherId, string $year, int $vlDays, int $slDays): void
    {
        if ($vlDays > 0) {
            // Try to find the leave record with different case variations
            $leave = TeacherLeave::where('teacher_id', $teacherId)
                ->where('year', $year)
                ->where(function($query) {
                    $query->where('leave_type', 'Vacation Leave')
                          ->orWhere('leave_type', 'VACATION LEAVE')
                          ->orWhere('leave_type', 'vacation leave');
                })
                ->first();

            if ($leave) {
                $oldAvailable = $leave->available;
                $oldUsed = $leave->used;

                $leave->used += $vlDays;
                $leave->available = max(0, $leave->available - $vlDays);
                $leave->save();

                Log::info('Deducted VL from teacher leave', [
                    'teacher_id' => $teacherId,
                    'year' => $year,
                    'vl_days' => $vlDays,
                    'old_available' => $oldAvailable,
                    'new_available' => $leave->available,
                    'old_used' => $oldUsed,
                    'new_used' => $leave->used
                ]);
            } else {
                Log::warning('Vacation Leave record not found for teacher', [
                    'teacher_id' => $teacherId,
                    'year' => $year
                ]);
            }
        }

        if ($slDays > 0) {
            // For teachers, deduct from Service Credit instead of Sick Leave
            $serviceCredit = TeacherLeave::where('teacher_id', $teacherId)
                ->where('year', $year)
                ->where(function($query) {
                    $query->where('leave_type', 'Service Credit')
                          ->orWhere('leave_type', 'SERVICE CREDIT')
                          ->orWhere('leave_type', 'service credit');
                })
                ->first();

            if ($serviceCredit) {
                $oldAvailable = $serviceCredit->available;
                $oldUsed = $serviceCredit->used;

                $serviceCredit->used += $slDays;
                $serviceCredit->available = max(0, $serviceCredit->available - $slDays);
                $serviceCredit->remarks = trim(($serviceCredit->remarks ? $serviceCredit->remarks.'; ' : '')."Monetization used {$slDays} day(s) on ".now()->format('Y-m-d'));
                $serviceCredit->save();

                Log::info('Deducted SL from teacher Service Credit', [
                    'teacher_id' => $teacherId,
                    'year' => $year,
                    'sl_days' => $slDays,
                    'old_available' => $oldAvailable,
                    'new_available' => $serviceCredit->available,
                    'old_used' => $oldUsed,
                    'new_used' => $serviceCredit->used
                ]);
            } else {
                Log::warning('Service Credit record not found for teacher', [
                    'teacher_id' => $teacherId,
                    'year' => $year
                ]);
            }
        }
    }

    /**
     * Deduct from non-teaching leave balance
     */
    private function deductFromNonTeachingLeave(int $nonTeachingId, string $year, int $vlDays, int $slDays): void
    {
        if ($vlDays > 0) {
            $leave = NonTeachingLeave::where('non_teaching_id', $nonTeachingId)
                ->where('year', $year)
                ->where(function($query) {
                    $query->where('leave_type', 'Vacation Leave')
                          ->orWhere('leave_type', 'VACATION LEAVE')
                          ->orWhere('leave_type', 'vacation leave');
                })
                ->first();

            if ($leave) {
                $oldAvailable = $leave->available;
                $oldUsed = $leave->used;

                $leave->used += $vlDays;
                $leave->available = max(0, $leave->available - $vlDays);
                $leave->save();

                Log::info('Deducted VL from non-teaching leave', [
                    'non_teaching_id' => $nonTeachingId,
                    'year' => $year,
                    'vl_days' => $vlDays,
                    'old_available' => $oldAvailable,
                    'new_available' => $leave->available,
                    'old_used' => $oldUsed,
                    'new_used' => $leave->used
                ]);
            } else {
                Log::warning('Vacation Leave record not found for non-teaching', [
                    'non_teaching_id' => $nonTeachingId,
                    'year' => $year
                ]);
            }
        }

        if ($slDays > 0) {
            $leave = NonTeachingLeave::where('non_teaching_id', $nonTeachingId)
                ->where('year', $year)
                ->where(function($query) {
                    $query->where('leave_type', 'Sick Leave')
                          ->orWhere('leave_type', 'SICK LEAVE')
                          ->orWhere('leave_type', 'sick leave');
                })
                ->first();

            if ($leave) {
                $oldAvailable = $leave->available;
                $oldUsed = $leave->used;

                $leave->used += $slDays;
                $leave->available = max(0, $leave->available - $slDays);
                $leave->save();

                Log::info('Deducted SL from non-teaching leave', [
                    'non_teaching_id' => $nonTeachingId,
                    'year' => $year,
                    'sl_days' => $slDays,
                    'old_available' => $oldAvailable,
                    'new_available' => $leave->available,
                    'old_used' => $oldUsed,
                    'new_used' => $leave->used
                ]);
            } else {
                Log::warning('Sick Leave record not found for non-teaching', [
                    'non_teaching_id' => $nonTeachingId,
                    'year' => $year
                ]);
            }
        }
    }

    /**
     * Deduct from school head leave balance
     */
    private function deductFromSchoolHeadLeave(int $schoolHeadId, string $year, int $vlDays, int $slDays): void
    {
        Log::info('Starting school head leave deduction', [
            'school_head_id' => $schoolHeadId,
            'year' => $year,
            'vl_days' => $vlDays,
            'sl_days' => $slDays
        ]);

        if ($vlDays > 0) {
            $leave = SchoolHeadLeave::where('school_head_id', $schoolHeadId)
                ->where('year', $year)
                ->where(function($query) {
                    $query->where('leave_type', 'Vacation Leave')
                          ->orWhere('leave_type', 'VACATION LEAVE')
                          ->orWhere('leave_type', 'vacation leave');
                })
                ->first();

            Log::info('Found Vacation Leave record', [
                'school_head_id' => $schoolHeadId,
                'year' => $year,
                'found' => $leave ? true : false,
                'current_available' => $leave?->available,
                'current_used' => $leave?->used
            ]);

            if ($leave) {
                $oldAvailable = $leave->available;
                $oldUsed = $leave->used;

                $leave->used += $vlDays;
                $leave->available = max(0, $leave->available - $vlDays);
                $leave->save();

                Log::info('Deducted VL from school head leave', [
                    'school_head_id' => $schoolHeadId,
                    'year' => $year,
                    'vl_days' => $vlDays,
                    'old_available' => $oldAvailable,
                    'new_available' => $leave->available,
                    'old_used' => $oldUsed,
                    'new_used' => $leave->used
                ]);
            } else {
                Log::warning('Vacation Leave record not found for school head', [
                    'school_head_id' => $schoolHeadId,
                    'year' => $year
                ]);
            }
        }

        if ($slDays > 0) {
            $leave = SchoolHeadLeave::where('school_head_id', $schoolHeadId)
                ->where('year', $year)
                ->where(function($query) {
                    $query->where('leave_type', 'Sick Leave')
                          ->orWhere('leave_type', 'SICK LEAVE')
                          ->orWhere('leave_type', 'sick leave');
                })
                ->first();

            Log::info('Found Sick Leave record', [
                'school_head_id' => $schoolHeadId,
                'year' => $year,
                'found' => $leave ? true : false,
                'current_available' => $leave?->available,
                'current_used' => $leave?->used
            ]);

            if ($leave) {
                $oldAvailable = $leave->available;
                $oldUsed = $leave->used;

                $leave->used += $slDays;
                $leave->available = max(0, $leave->available - $slDays);
                $leave->save();

                Log::info('Deducted SL from school head leave', [
                    'school_head_id' => $schoolHeadId,
                    'year' => $year,
                    'sl_days' => $slDays,
                    'old_available' => $oldAvailable,
                    'new_available' => $leave->available,
                    'old_used' => $oldUsed,
                    'new_used' => $leave->used
                ]);
            } else {
                Log::warning('Sick Leave record not found for school head', [
                    'school_head_id' => $schoolHeadId,
                    'year' => $year
                ]);
            }
        }
    }

    /**
     * Process rejected monetization request
     */
    public function processRejectedMonetization(LeaveMonetization $monetization, string $rejectionReason, ?string $adminRemarks = null)
    {
        try {
            Log::info('Processing monetization rejection', [
                'monetization_id' => $monetization->id,
                'user_id' => $monetization->user_id,
                'rejection_reason' => $rejectionReason,
                'admin_remarks' => $adminRemarks
            ]);

            // Update the monetization status to rejected
            $monetization->update([
                'status' => 'rejected',
                'rejection_reason' => $rejectionReason,
                'admin_remarks' => $adminRemarks,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            Log::info('Monetization request rejected successfully', [
                'monetization_id' => $monetization->id,
                'user_id' => $monetization->user_id
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Error rejecting monetization', [
                'monetization_id' => $monetization->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
}
