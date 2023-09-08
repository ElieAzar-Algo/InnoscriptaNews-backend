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
        // $schedule->command('news:get-newsapi')->everySixHours();
        // $schedule->command('news:get-nytimes-news')->everySixHours();
        // $schedule->command('news:get-guardien-news')->everySixHours();

        //this is only for test (to test the cron jon on the linux server)
        //NOTE: please comment out the 3 jobs above when you uncomment the 3 jobs below
        $schedule->command('news:get-newsapi')->everyFiveMinutes();
        $schedule->command('news:get-nytimes-news')->everyFiveMinutes();
        $schedule->command('news:get-guardien-news')->everyFiveMinutes();
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
