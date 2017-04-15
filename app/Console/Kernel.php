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
        Commands\InteractionUnban::class,
        Commands\EngagementGroup::class,
        Commands\InvalidateEngagementJob::class,
        Commands\RefreshInstagramProfile::class,
        Commands\SnapshotFollowerAnalysis::class,
        Commands\ManualLogin::class,
        Commands\ReplicateSetting::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        for ($i = 0; $i < 200; $i++) {
            $counter = $i * 30;
            $schedule->command('dm:get ' . $counter . ' 30')->everyFiveMinutes();
        }
        
        for ($i = 0; $i < 200; $i++) {
            $counter = $i * 30;
            $schedule->command('dm:send ' . $counter . ' 30')->everyFiveMinutes();
        }
        
        for ($i = 0; $i < 24; $i++) {
            $counter = $i * 20;
            $schedule->command('interaction:follow ' . $counter . ' 20')->everyFiveMinutes();
        }

        for ($i = 0; $i < 50; $i++) {
            $counter = $i * 20;
            $schedule->command('interaction:like ' . $counter . ' 20')->everyFiveMinutes();
        }
        
//        for ($i = 0; $i < 50; $i++) {
//            $counter = $i * 10;
//            $schedule->command('interaction:comment ' . $counter . ' 10')->everyFiveMinutes();
//        }
        
        $schedule->command("engagement:add")->everyThirtyMinutes()->withoutOverlapping();
        $schedule->command("interaction:unban")->everyThirtyMinutes()->withoutOverlapping();
        $schedule->command("analysis:follower")->daily("00:00")->withoutOverlapping();
        $schedule->command('ig:refresh 0 100')->everyThirtyMinutes();
        $schedule->command('ig:refresh 0 200')->everyThirtyMinutes();
        $schedule->command('ig:refresh 0 300')->everyThirtyMinutes();
        $schedule->command('ig:refresh 0 400')->everyThirtyMinutes();
        $schedule->command('ig:refresh 0 500')->everyThirtyMinutes();
        $schedule->command('ig:refresh 0 600')->everyThirtyMinutes();
        $schedule->command('ig:refresh 0 700')->everyThirtyMinutes();
        $schedule->command('ig:refresh 0 800')->everyThirtyMinutes();
        $schedule->command('ig:refresh 0 900')->everyThirtyMinutes();
        $schedule->command('ig:refresh 0 1000')->everyThirtyMinutes();
        
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
