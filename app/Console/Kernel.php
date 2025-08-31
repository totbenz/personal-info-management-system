<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log; // Added this import for logging

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Automated database backups
        if (config('backup.schedule.enabled', true)) {
            $schedule->command('backup:database --type=full --compress=true')
                ->daily()
                ->at(config('backup.schedule.time', '02:00'))
                ->withoutOverlapping()
                ->runInBackground()
                ->onSuccess(function () {
                    Log::info('Automated database backup completed successfully');
                })
                ->onFailure(function () {
                    Log::error('Automated database backup failed');
                    // You can add notification logic here
                });
        }

        // Clean up old backups (keep only recent ones)
        $schedule->command('backup:database --type=full --compress=true')
            ->weekly()
            ->sundays()
            ->at('03:00')
            ->withoutOverlapping()
            ->runInBackground();

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
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
