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
        Commands\UnbanInteraction::class,
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
        $schedule->command('dm:get 0 0 manifestwithmike@gmail.com')->everyFiveMinutes();
        $schedule->command('dm:get 0 0 selfmade@officialselfmade.com')->everyFiveMinutes();
        $schedule->command('dm:get 0 0 madenotw@aol.com')->everyFiveMinutes();
        $schedule->command('dm:get 0 0 lifestyle.kyler@gmail.com')->everyFiveMinutes();
        $schedule->command('dm:get 0 0 julidasvierte@gmail.com')->everyFiveMinutes();
        $schedule->command('dm:get 0 0 ailyndigital@gmail.com')->everyFiveMinutes();
        $schedule->command('dm:get 0 0 julian.kussin@gmail.com')->everyFiveMinutes();
        $schedule->command('dm:get 0 0 chuanian@hotmail.com')->everyFiveMinutes();
        
        for ($i = 0; $i < 50; $i++) {
            $counter = $i * 20;
            $schedule->command('interaction:like ' . $counter . ' 20')->everyFiveMinutes();
        }
//        $schedule->command('interaction:like 0 0 annegreenfield323@gmail.com')->everyFiveMinutes();
//        $schedule->command('interaction:like 0 0 caleb@calebthetrainer.com')->everyFiveMinutes();
        $schedule->command('interaction:like 0 0 xtremewealth@gmail.com')->everyFiveMinutes();
        $schedule->command('interaction:like 0 0 Shawnjosiah.pd@gmail.com')->everyFiveMinutes();
        $schedule->command('interaction:like 0 0 nicolasmaton@gmail.com')->everyFiveMinutes();
        $schedule->command('interaction:like 0 0 raultraining@yahoo.com')->everyFiveMinutes();
        $schedule->command('interaction:like 0 0 peizhisim@gmail.com')->everyFiveMinutes();
        $schedule->command('interaction:like 0 0 julian.kussin@gmail.com')->everyFiveMinutes();
        $schedule->command('interaction:like 0 0 chuanian@hotmail.com')->everyFiveMinutes();
        
        $schedule->command("engagement:add")->everyThirtyMinutes()->withoutOverlapping();
        $schedule->command("analysis:follower")->daily("00:00")->withoutOverlapping();
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
