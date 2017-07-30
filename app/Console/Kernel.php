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
        Commands\GetDm::class,
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
        Commands\ConvertUnicodeEmojiToShortCode::class,
        Commands\MakeStripeIdActive::class,
        Commands\GetStripeStatus::class,
        Commands\RefreshProfileProxy::class,
        Commands\RefreshTierStatus::class,
        Commands\DeleteInvalidImages::class,
        Commands\SendTestDirectMessage::class,
        Commands\ArchiveLikeLogs::class,
        Commands\SendFailedPaymentEmail::class,
        Commands\UpdatePendingCommissionPayable::class,
        Commands\GetUserWithOutstandingPayable::class,
        Commands\VerifyInvoicePaid::class,
        Commands\GetCommissionEligibility::class,
        Commands\GetTotalPendingPayable::class,
        Commands\UpdateUserCommissionPayable::class,
        Commands\UpdatePendingCommission::class,
        Commands\AddUserUpdate::class,
        Commands\ReconcileStripeCharges::class,
        Commands\GetUserSubscription::class,
        Commands\GetUserInvoices::class,
        Commands\UpdateUserLastPaid::class,
        Commands\SendDelinquentEmail::class,
        Commands\GetUsersWithPendingCommisions::class,
        Commands\ReadLastPaidCsv::class,
        Commands\TestDoWhileContinue::class,
        Commands\ManuallyFollowBack::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        #$schedule->command('interaction:like')->cron('*/3 * * * * *');
        $schedule->command('interaction:comment')->everyFiveMinutes();
        $schedule->command('interaction:follow')->cron('*/3 * * * * *');
        #$schedule->command('dm:get')->everyThirtyMinutes();
        #$schedule->command('dm:send')->everyTenMinutes();
        $schedule->command("engagement:add")->hourly();
        $schedule->command("analysis:follower")->daily("00:00");
        $schedule->command("ig:refresh")->everyThirtyMinutes();
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
