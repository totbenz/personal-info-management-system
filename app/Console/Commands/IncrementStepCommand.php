<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Personnel;
use Carbon\Carbon;

class IncrementStepCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'personnel:increment-step';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Increment personnel step every three years based on employment start date';

    /**
     * Execute the console command.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $personnels = Personnel::all();

        foreach ($personnels as $personnel) {
            $employmentStartDate = Carbon::parse($personnel->employment_start);
            $currentDate = Carbon::now();
            $yearsOfService = $employmentStartDate->diffInYears($currentDate);

            // Calculate the expected step increment based on 3-year intervals
            $expectedStepIncrement = intdiv($yearsOfService, 3);

            if ($personnel->step_increment < $expectedStepIncrement) {
                $personnel->step_increment = $expectedStepIncrement;
                $personnel->save();
                $this->info("Updated step increment for personnel ID {$personnel->id} to {$expectedStepIncrement}");
            }
        }

        $this->info('Step increment process completed.');
    }
}
