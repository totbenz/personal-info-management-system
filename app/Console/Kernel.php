<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('personnel:increment-step')->daily();
        $schedule->command('cto:expire-old')->daily()->at('00:30');
        
        // Process year-end force leave deductions on January 1st
        $schedule->command('leave:process-year-end-force-leave')->yearly()->at('01:00');
        
        // Optional: Generate leave accrual reports (informational only)
        // $schedule->command('school-head:accrue-leaves')->monthly()->at('09:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
