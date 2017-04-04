<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ImportInstagramSession::class,
        Commands\GetNewDmJob::class,
        Commands\SendDmJob::class,
        Commands\InteractionComment::class,
        Commands\InteractionFollow::class,
        Commands\InteractionLike::class,
        Commands\RefreshInstagramProfile::class,
        Commands\SnapshotFollowerAnalysis::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        for ($i = 0; $i < 500; $i++) {
            $counter = $i * 10;
            $schedule->command('dm:get ' . $counter . ' 10')->everyMinute();
        }
        
        for ($i = 0; $i < 500; $i++) {
            $counter = $i * 10;
            $schedule->command('dm:send ' . $counter . ' 10')->everyMinute();
        }
        
        for ($i = 0; $i < 70; $i++) {
            $counter = $i * 10;
            $schedule->command('interaction:follow ' . $counter . ' 10')->cron("*/3 * * * * *");
        }
        
        for ($i = 0; $i < 300; $i++) {
            $counter = $i * 20;
            $schedule->command('interaction:like ' . $counter . ' 20')->cron("*/3 * * * * *");
        }
        
        for ($i = 0; $i < 80; $i++) {
            $counter = $i * 10;
            $schedule->command('ig:refresh ' . $i . ' 1')->everyThirtyMinutes();
        }
        
        //$schedule->command('analysis:follower')->daily();
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands() {
        require base_path('routes/console.php');
    }

}
