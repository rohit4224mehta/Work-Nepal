<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
{
    $schedule->command('jobs:check-expiring')->daily();

    // ========== NOTIFICATION SCHEDULES ==========
        
        // Send daily job alerts at 8 AM
        $schedule->command('notifications:send-job-alerts --frequency=daily')
            ->dailyAt('08:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/job-alerts.log'));
        
        // Send weekly job alerts on Monday at 9 AM
        $schedule->command('notifications:send-job-alerts --frequency=weekly')
            ->weeklyOn(1, '09:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/job-alerts.log'));
        
        // Send instant job alerts every 15 minutes
        $schedule->command('notifications:send-job-alerts --frequency=instant')
            ->everyFifteenMinutes()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/job-alerts.log'));
        
        // Clean old notifications daily at 2 AM
        $schedule->command('notifications:clean --days=30')
            ->dailyAt('02:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/notifications-clean.log'));
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
