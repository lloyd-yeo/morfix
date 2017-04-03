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
        for ($i = 0; $i < 800; $i++) {
            $counter = $i * 5;
            $schedule->command('dm:get ' . $counter . ' 5')->everyMinute();
        }
        
        for ($i = 0; $i < 800; $i++) {
            $counter = $i * 5;
            $schedule->command('dm:send ' . $counter . ' 5')->everyMinute();
        }
        
        for ($i = 0; $i < 600; $i++) {
            $schedule->command('interaction:follow ' . $i . ' 1')->everyFiveMinutes();
        }
        
        for ($i = 0; $i < 800; $i++) {
            $schedule->command('ig:refresh ' . $i . ' 1')->everyThirtyMinutes();
        }
        
        $schedule->command('analysis:follower')->daily();
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
