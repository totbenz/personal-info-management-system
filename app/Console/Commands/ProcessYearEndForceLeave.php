<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SchoolHeadLeave;
use App\Models\TeacherLeave;
use App\Models\NonTeachingLeave;
use App\Models\Personnel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProcessYearEndForceLeave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:process-year-end-force-leave {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process year-end force leave deductions from vacation leave';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->argument('year') ?: Carbon::now()->subYear()->year;
        
        $this->info("Processing year-end force leave deductions for year: {$year}");

        // Process School Heads
        $this->processSchoolHeadForceLeave($year);
        
        // Process Teachers
        $this->processTeacherForceLeave($year);
        
        // Process Non-Teaching Staff
        $this->processNonTeachingForceLeave($year);

        $this->info('Year-end force leave processing completed successfully.');
        return 0;
    }

    /**
     * Process force leave for school heads
     */
    private function processSchoolHeadForceLeave($year)
    {
        $this->info("Processing School Head force leave for year {$year}...");
        
        $forceLeaveRecords = SchoolHeadLeave::where('leave_type', 'Force Leave')
            ->where('year', $year)
            ->where('available', '>', 0)
            ->get();

        $processed = 0;
        foreach ($forceLeaveRecords as $forceLeave) {
            $remainingForceDays = $forceLeave->available;
            
            // Find corresponding vacation leave record
            $vacationLeave = SchoolHeadLeave::where('school_head_id', $forceLeave->school_head_id)
                ->where('leave_type', 'Vacation Leave')
                ->where('year', $year)
                ->first();

            if ($vacationLeave && $remainingForceDays > 0) {
                // Deduct remaining force leave from vacation leave
                $previousVacationAvailable = $vacationLeave->available;
                $vacationLeave->available = max(0, $vacationLeave->available - $remainingForceDays);
                $vacationLeave->save();

                // Update force leave to show it's been processed
                $forceLeave->available = 0;
                $forceLeave->remarks = ($forceLeave->remarks ?? '') . " | Year-end: {$remainingForceDays} days deducted from vacation leave";
                $forceLeave->save();

                // Update vacation leave remarks
                $vacationLeave->remarks = ($vacationLeave->remarks ?? '') . " | Year-end: -{$remainingForceDays} days from unused force leave";
                $vacationLeave->save();

                $processed++;
                
                Log::info("Year-end force leave processed for school head", [
                    'personnel_id' => $forceLeave->school_head_id,
                    'year' => $year,
                    'remaining_force_days' => $remainingForceDays,
                    'vacation_previous_available' => $previousVacationAvailable,
                    'vacation_new_available' => $vacationLeave->available
                ]);
            }
        }
        
        $this->info("Processed {$processed} school head force leave records.");
    }

    /**
     * Process force leave for teachers
     */
    private function processTeacherForceLeave($year)
    {
        $this->info("Processing Teacher force leave for year {$year}...");
        
        $forceLeaveRecords = TeacherLeave::where('leave_type', 'Force Leave')
            ->where('year', $year)
            ->where('available', '>', 0)
            ->get();

        $processed = 0;
        foreach ($forceLeaveRecords as $forceLeave) {
            $remainingForceDays = $forceLeave->available;
            
            // Find corresponding vacation leave record
            $vacationLeave = TeacherLeave::where('teacher_id', $forceLeave->teacher_id)
                ->where('leave_type', 'Vacation Leave')
                ->where('year', $year)
                ->first();

            if ($vacationLeave && $remainingForceDays > 0) {
                // Deduct remaining force leave from vacation leave
                $previousVacationAvailable = $vacationLeave->available;
                $vacationLeave->available = max(0, $vacationLeave->available - $remainingForceDays);
                $vacationLeave->save();

                // Update force leave to show it's been processed
                $forceLeave->available = 0;
                $forceLeave->remarks = ($forceLeave->remarks ?? '') . " | Year-end: {$remainingForceDays} days deducted from vacation leave";
                $forceLeave->save();

                // Update vacation leave remarks
                $vacationLeave->remarks = ($vacationLeave->remarks ?? '') . " | Year-end: -{$remainingForceDays} days from unused force leave";
                $vacationLeave->save();

                $processed++;
                
                Log::info("Year-end force leave processed for teacher", [
                    'personnel_id' => $forceLeave->teacher_id,
                    'year' => $year,
                    'remaining_force_days' => $remainingForceDays,
                    'vacation_previous_available' => $previousVacationAvailable,
                    'vacation_new_available' => $vacationLeave->available
                ]);
            }
        }
        
        $this->info("Processed {$processed} teacher force leave records.");
    }

    /**
     * Process force leave for non-teaching staff
     */
    private function processNonTeachingForceLeave($year)
    {
        $this->info("Processing Non-Teaching staff force leave for year {$year}...");
        
        $forceLeaveRecords = NonTeachingLeave::where('leave_type', 'Force Leave')
            ->where('year', $year)
            ->where('available', '>', 0)
            ->get();

        $processed = 0;
        foreach ($forceLeaveRecords as $forceLeave) {
            $remainingForceDays = $forceLeave->available;
            
            // Find corresponding vacation leave record
            $vacationLeave = NonTeachingLeave::where('non_teaching_id', $forceLeave->non_teaching_id)
                ->where('leave_type', 'Vacation Leave')
                ->where('year', $year)
                ->first();

            if ($vacationLeave && $remainingForceDays > 0) {
                // Deduct remaining force leave from vacation leave
                $previousVacationAvailable = $vacationLeave->available;
                $vacationLeave->available = max(0, $vacationLeave->available - $remainingForceDays);
                $vacationLeave->save();

                // Update force leave to show it's been processed
                $forceLeave->available = 0;
                $forceLeave->remarks = ($forceLeave->remarks ?? '') . " | Year-end: {$remainingForceDays} days deducted from vacation leave";
                $forceLeave->save();

                // Update vacation leave remarks
                $vacationLeave->remarks = ($vacationLeave->remarks ?? '') . " | Year-end: -{$remainingForceDays} days from unused force leave";
                $vacationLeave->save();

                $processed++;
                
                Log::info("Year-end force leave processed for non-teaching staff", [
                    'personnel_id' => $forceLeave->non_teaching_id,
                    'year' => $year,
                    'remaining_force_days' => $remainingForceDays,
                    'vacation_previous_available' => $previousVacationAvailable,
                    'vacation_new_available' => $vacationLeave->available
                ]);
            }
        }
        
        $this->info("Processed {$processed} non-teaching staff force leave records.");
    }
}
