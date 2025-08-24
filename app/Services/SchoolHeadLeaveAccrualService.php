<?php

namespace App\Services;

use App\Models\SchoolHeadLeave;
use App\Models\Personnel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SchoolHeadLeaveAccrualService
{
    /**
     * Calculate and return the current accrued leave amounts for a school head
     */
    public function calculateAccruedLeaves($schoolHeadId, $year = null)
    {
        $year = $year ?? Carbon::now()->year;
        $schoolHead = Personnel::find($schoolHeadId);
        
        if (!$schoolHead || !$schoolHead->employment_start) {
            return [];
        }

        $employmentStart = Carbon::parse($schoolHead->employment_start);
        $currentDate = Carbon::now();
        
        // Only calculate accruals if employment started before or during the current year
        if ($employmentStart->year > $year) {
            return [];
        }

        $accruals = [];
        $leaveTypes = ['Vacation Leave', 'Sick Leave'];

        foreach ($leaveTypes as $leaveType) {
            $baseAmount = 15; // Base amount from defaultLeaves
            $monthlyAccrual = $this->calculateMonthlyAccrual($employmentStart, $year);
            $yearlyBonus = $this->calculateYearlyBonus($employmentStart, $year);
            $totalAccrued = $baseAmount + $monthlyAccrual + $yearlyBonus;

            $accruals[$leaveType] = [
                'base_amount' => $baseAmount,
                'monthly_accrual' => $monthlyAccrual,
                'yearly_bonus' => $yearlyBonus,
                'total_accrued' => $totalAccrued,
                'months_eligible' => $this->getEligibleMonths($employmentStart, $year),
                'years_completed' => $this->getCompletedYears($employmentStart, $year)
            ];
        }

        return $accruals;
    }

    /**
     * Calculate monthly accrual (1.25 days per month)
     */
    private function calculateMonthlyAccrual($employmentStart, $year)
    {
        $monthlyRate = 1.25;
        $eligibleMonths = $this->getEligibleMonths($employmentStart, $year);
        
        return $monthlyRate * $eligibleMonths;
    }

    /**
     * Calculate yearly bonus (15 days per completed year)
     */
    private function calculateYearlyBonus($employmentStart, $year)
    {
        $yearlyBonus = 15;
        $completedYears = $this->getCompletedYears($employmentStart, $year);
        
        return $yearlyBonus * $completedYears;
    }

    /**
     * Get the number of months eligible for accrual in the given year
     */
    private function getEligibleMonths($employmentStart, $year)
    {
        $employmentStart = Carbon::parse($employmentStart);
        $currentDate = Carbon::now();
        
        // Start of the year or employment start, whichever is later
        $startDate = Carbon::create($year, 1, 1);
        if ($employmentStart->year == $year && $employmentStart->month > 1) {
            $startDate = $employmentStart->copy()->startOfMonth();
        }
        
        // End of the year or current date, whichever is earlier
        $endDate = Carbon::create($year, 12, 31);
        if ($currentDate->year == $year) {
            $endDate = $currentDate->copy()->endOfMonth();
        }
        
        // If start date is after end date, no eligible months
        if ($startDate->gt($endDate)) {
            return 0;
        }
        
        // Calculate the number of completed months
        $months = $startDate->diffInMonths($endDate);
        
        // Add 1 if we're currently in a month (partial month counts as full month for accrual)
        if ($currentDate->year == $year && $currentDate->day >= 1) {
            $months = min($months + 1, 12);
        }
        
        return min($months, 12); // Cap at 12 months per year
    }

    /**
     * Get the number of completed years of service by the end of the given year
     */
    private function getCompletedYears($employmentStart, $year)
    {
        $employmentStart = Carbon::parse($employmentStart);
        $endOfYear = Carbon::create($year, 12, 31);
        $currentDate = Carbon::now();
        
        // Use the earlier of end of year or current date
        $calculationDate = $currentDate->lt($endOfYear) ? $currentDate : $endOfYear;
        
        // Calculate completed years
        $completedYears = $employmentStart->diffInYears($calculationDate);
        
        return max(0, $completedYears);
    }

    /**
     * Update leave records with calculated accruals
     */
    public function updateLeaveRecords($schoolHeadId, $year = null)
    {
        $year = $year ?? Carbon::now()->year;
        $accruals = $this->calculateAccruedLeaves($schoolHeadId, $year);
        
        if (empty($accruals)) {
            return false;
        }

        $schoolHead = Personnel::find($schoolHeadId);
        $soloParent = $schoolHead->is_solo_parent ?? false;
        $userSex = $schoolHead->sex ?? null;
        $defaultLeaves = SchoolHeadLeave::defaultLeaves($soloParent, $userSex);

        $updated = [];

        foreach ($accruals as $leaveType => $accrualData) {
            // Get or create the leave record
            $leaveRecord = SchoolHeadLeave::firstOrCreate(
                [
                    'school_head_id' => $schoolHeadId,
                    'leave_type' => $leaveType,
                    'year' => $year
                ],
                [
                    'available' => $defaultLeaves[$leaveType] ?? 15,
                    'used' => 0,
                    'ctos_earned' => 0,
                    'remarks' => 'Auto-initialized with accrual calculation'
                ]
            );

            // Calculate new available amount (base + accruals - used)
            $newAvailable = $accrualData['total_accrued'] - $leaveRecord->used;
            
            // Only update if there's a significant change (avoid unnecessary updates)
            if (abs($leaveRecord->available - $newAvailable) > 0.01) {
                $previousAvailable = $leaveRecord->available;
                $leaveRecord->available = max(0, $newAvailable); // Ensure non-negative
                
                // Update remarks with accrual breakdown
                $remarkParts = [
                    "Base: {$accrualData['base_amount']} days",
                    "Monthly: +{$accrualData['monthly_accrual']} days ({$accrualData['months_eligible']} months × 1.25)",
                    "Yearly: +{$accrualData['yearly_bonus']} days ({$accrualData['years_completed']} years × 15)",
                    "Total accrued: {$accrualData['total_accrued']} days",
                    "Auto-calculated on " . Carbon::now()->format('M d, Y H:i')
                ];
                
                $leaveRecord->remarks = implode('; ', $remarkParts);
                $leaveRecord->save();
                
                $updated[$leaveType] = [
                    'previous' => $previousAvailable,
                    'new' => $leaveRecord->available,
                    'accrual_data' => $accrualData
                ];
                
                Log::info("Leave record updated with automatic accrual", [
                    'school_head_id' => $schoolHeadId,
                    'leave_type' => $leaveType,
                    'year' => $year,
                    'previous_available' => $previousAvailable,
                    'new_available' => $leaveRecord->available,
                    'accrual_breakdown' => $accrualData
                ]);
            }
        }

        return $updated;
    }

    /**
     * Get a summary of accrual information for display
     */
    public function getAccrualSummary($schoolHeadId, $year = null)
    {
        $year = $year ?? Carbon::now()->year;
        $schoolHead = Personnel::find($schoolHeadId);
        
        if (!$schoolHead || !$schoolHead->employment_start) {
            return null;
        }

        $employmentStart = Carbon::parse($schoolHead->employment_start);
        $currentDate = Carbon::now();
        
        return [
            'employment_start' => $employmentStart->format('M d, Y'),
            'years_of_service' => $employmentStart->diffInYears($currentDate),
            'months_in_current_year' => $this->getEligibleMonths($employmentStart, $year),
            'completed_years_by_year_end' => $this->getCompletedYears($employmentStart, $year),
            'next_monthly_accrual' => Carbon::now()->addMonth()->format('M 1, Y'),
            'next_yearly_bonus' => Carbon::create($year + 1, 1, 1)->format('M d, Y'),
            'monthly_rate' => 1.25,
            'yearly_bonus' => 15
        ];
    }
}
