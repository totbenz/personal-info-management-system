<?php

namespace App\Services;

use App\Models\NonTeachingLeave;
use App\Models\Personnel;
use App\Models\TeacherLeave;
use Carbon\Carbon;

class MonthlyLeaveAccrualService
{
    public function updateTeacherLeaveRecords(int $teacherId, ?int $year = null): array
    {
        return $this->updateLeaveRecordsForRole(
            $teacherId,
            $year,
            'teacher',
            TeacherLeave::class,
            'teacher_id'
        );
    }

    public function updateNonTeachingLeaveRecords(int $nonTeachingId, ?int $year = null): array
    {
        return $this->updateLeaveRecordsForRole(
            $nonTeachingId,
            $year,
            'non_teaching',
            NonTeachingLeave::class,
            'non_teaching_id'
        );
    }

    private function updateLeaveRecordsForRole(
        int $personnelId,
        ?int $year,
        string $role,
        string $leaveModelClass,
        string $personnelKey
    ): array {
        $year = $year ?? Carbon::now()->year;
        $personnel = Personnel::find($personnelId);

        if (!$personnel || !$personnel->employment_start) {
            return [];
        }

        $employmentStart = Carbon::parse($personnel->employment_start);

        if ($employmentStart->year > $year) {
            return [];
        }

        $eligibleMonths = $this->getEligibleMonths($employmentStart, $year);
        $accruedTotal = 1.25 * $eligibleMonths;

        $leaveTypes = ['Vacation Leave', 'Sick Leave'];
        $updated = [];

        foreach ($leaveTypes as $leaveType) {
            $leaveRecord = $leaveModelClass::firstOrCreate(
                [
                    $personnelKey => $personnelId,
                    'leave_type' => $leaveType,
                    'year' => $year,
                ],
                [
                    'available' => 0,
                    'used' => 0,
                    'remarks' => 'Auto-initialized (monthly accrual)',
                ]
            );

            $currentTotal = (float) $leaveRecord->available + (float) $leaveRecord->used;
            $tolerance = 0.01;

            if ($leaveRecord->wasRecentlyCreated) {
                $leaveRecord->available = $accruedTotal;
                $leaveRecord->remarks = "Auto-accrued: {$eligibleMonths} month(s) × 1.25 = {$accruedTotal} (" . Carbon::now()->format('M d, Y H:i') . ")";
                $leaveRecord->save();

                $updated[$leaveType] = [
                    'previous_available' => 0,
                    'new_available' => $leaveRecord->available,
                    'eligible_months' => $eligibleMonths,
                    'accrued_total' => $accruedTotal,
                    'role' => $role,
                ];

                continue;
            }

            if (($currentTotal + $tolerance) < $accruedTotal) {
                $difference = $accruedTotal - $currentTotal;
                $previousAvailable = (float) $leaveRecord->available;
                $leaveRecord->available = $previousAvailable + $difference;

                $leaveRecord->remarks = ($leaveRecord->remarks ?? '') .
                    " | +{$difference} (monthly accrual update " . Carbon::now()->format('M d, Y H:i') . ")";

                $leaveRecord->save();

                $updated[$leaveType] = [
                    'previous_available' => $previousAvailable,
                    'new_available' => $leaveRecord->available,
                    'difference_added' => $difference,
                    'eligible_months' => $eligibleMonths,
                    'accrued_total' => $accruedTotal,
                    'role' => $role,
                ];
            }
        }

        return $updated;
    }

    private function getEligibleMonths(Carbon $employmentStart, int $year): int
    {
        $currentDate = Carbon::now();

        $startDate = Carbon::create($year, 1, 1);
        if ($employmentStart->year === $year && $employmentStart->month > 1) {
            $startDate = $employmentStart->copy()->startOfMonth();
        }

        $endDate = Carbon::create($year, 12, 31);
        if ($currentDate->year === $year) {
            $endDate = $currentDate->copy()->endOfMonth();
        }

        if ($startDate->gt($endDate)) {
            return 0;
        }

        $months = $startDate->diffInMonths($endDate);

        if ($currentDate->year === $year && $currentDate->day >= 1) {
            $months = min($months + 1, 12);
        }

        return min($months, 12);
    }
}
