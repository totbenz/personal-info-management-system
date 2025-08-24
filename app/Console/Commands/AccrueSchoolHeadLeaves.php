<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SchoolHeadLeave;
use App\Models\Personnel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AccrueSchoolHeadLeaves extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'school-head:accrue-leaves';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display calculated leave accruals for school heads (informational only - actual calculation is done in real-time)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentYear = Carbon::now()->year;
        
        // Get all active school heads
        $schoolHeads = Personnel::whereHas('user', function($query) {
            $query->where('role', 'school_head');
        })->where('job_status', 'Active')->get();

        $this->info("=== School Head Leave Accrual Report ===");
        $this->info("Found {$schoolHeads->count()} active school heads");
        $this->info("Note: Leave calculations are now done in real-time on dashboard view");
        $this->info("");

        $accrualService = app(\App\Services\SchoolHeadLeaveAccrualService::class);

        foreach ($schoolHeads as $schoolHead) {
            try {
                $summary = $accrualService->getAccrualSummary($schoolHead->id, $currentYear);
                $accruals = $accrualService->calculateAccruedLeaves($schoolHead->id, $currentYear);
                
                if ($summary && $accruals) {
                    $this->info("--- {$schoolHead->first_name} {$schoolHead->last_name} (ID: {$schoolHead->id}) ---");
                    $this->info("Employment Start: {$summary['employment_start']}");
                    $this->info("Years of Service: {$summary['years_of_service']}");
                    $this->info("Months Eligible in {$currentYear}: {$summary['months_in_current_year']}");
                    
                    foreach ($accruals as $leaveType => $data) {
                        $this->info("{$leaveType}:");
                        $this->info("  - Base: {$data['base_amount']} days");
                        $this->info("  - Monthly Accrual: {$data['monthly_accrual']} days");
                        $this->info("  - Yearly Bonus: {$data['yearly_bonus']} days");
                        $this->info("  - Total Calculated: {$data['total_accrued']} days");
                    }
                    $this->info("");
                }
            } catch (\Exception $e) {
                $this->error("Failed to calculate accruals for school head {$schoolHead->id}: " . $e->getMessage());
            }
        }

        $this->info('Leave accrual report completed');
        return 0;
    }
}
