<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		//Auto Interactions
		Commands\InteractionLike::class,
		Commands\InteractionFollow::class,
		Commands\InteractionComment::class,

		//Auto DMs
		Commands\GetDm::class,
		Commands\SendDmJob::class,

		//Refresh IG Session
		Commands\RefreshInstagramProfile::class,

		//Daily Follower Snapshots
		Commands\SnapshotFollowerAnalysis::class,

		//Paypal
		Commands\PaypalUpdateChargesDaily::class,

		//Braintree
		Commands\BraintreeListTransactions::class,

		//Stripe
		Commands\StripeGetAllInvoices::class,

		Commands\GeoTargetingTester::class,
		Commands\ImportInstagramSession::class,
		Commands\CheckInteractionsWorking::class,
		Commands\CheckProfileAdded::class,
		Commands\TelegramTester::class,
		Commands\UpdatePendingCommissionPayableNew::class,
		Commands\UpdatePaypalCharges::class,

		Commands\UpdateLastPaidFromCSV::class,
		Commands\UpdateLastPaidFromCsv2::class,
		Commands\UnbanInteraction::class,
		Commands\EngagementGroup::class,
		Commands\InvalidateEngagementJob::class,
		Commands\ManualLogin::class,
		Commands\ReplicateSetting::class,
		Commands\ConvertUnicodeEmojiToShortCode::class,
		Commands\MakeStripeIdActive::class,
		Commands\GetStripeStatus::class,
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
		Commands\ManuallyFollowBack::class,
		Commands\EngagementGroupManual::class,
		Commands\RetrieveCommentForCertainDate::class,
		Commands\ReadPaypalAgreementCsv::class,
		Commands\ReadCommissionList::class,
		Commands\MigrateUsers::class,
		Commands\MigrateNiche::class,
		Commands\MigrateProxyList::class,
		Commands\MigrateFollowLogs::class,
		Commands\MigrateCommentLogs::class,
		Commands\MigrateLikeLogs::class,
		Commands\MigrateComments::class,
		Commands\UpdateUserTotalInteractionStatistics::class,
		Commands\GenerateStripeReferralChargesCsv::class,
		Commands\RefreshStripeCustomerDetails::class,
		Commands\RefreshStripeSubscription::class,
		Commands\RefreshInteractionsQuota::class,
		Commands\CheckDuplicateCharges::class,
		Commands\UpdateNextInteractionTime::class,
		Commands\UpdateTrialActivation::class,
		Commands\UpdateUserTargets::class,
		Commands\RetrieveDmInbox::class,
		Commands\MigrateDmJob::class,
		Commands\ReassignProxy::class,
		Commands\SendPremiumEmail::class,
		Commands\SendFreeTrialEmail::class,
		Commands\SendPremiumAffiliateEmail::class,
		Commands\TestFailedEngagementGroupNotification::class,
		Commands\UpdateMasterInstagramProfileErrorFlags::class,
		Commands\ImportCompetitorsCsv::class,

		Commands\GenerateBraintreeReferralCharges::class,
		Commands\CombinePayoutCSV::class,

		Commands\GetAllStripeInvoiceCharges::class,
		Commands\ManualLoginPrevious::class,
		Commands\GetMonthlyDMUsers::class,
		Commands\TestLoginToInstagressAPI::class,
		Commands\InstagramLogin::class,
		Commands\TurnOffInteractions::class,
		Commands\RefreshInstagramSessionDaemon::class,
		Commands\SendVerifyProfileEmail::class,
		Commands\RefreshInstagramProfileStats::class,
		Commands\ExportSettingsToInstagress::class,
		Commands\GetHashtagFeed::class,
		Commands\ManualInteractionLike::class,
		Commands\SendScheduledPost::class,
		Commands\LikeMedia::class,
		Commands\RedisTester::class,
		Commands\ImportLikeLogsToRedis::class,
		Commands\AddTotalLikeCountToRedis::class,
		Commands\DeleteFailedJobsOnRedis::class,
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule $schedule
	 *
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{

		//For dispensing auto-interactions jobs.
		$schedule->command('interaction:like')->everyFiveMinutes();
		$schedule->command('interaction:comment')->everyFiveMinutes();
		$schedule->command('interaction:follow')->everyMinute();

		//For dispensing DM jobs.
		$schedule->command('dm:get')->hourly();
		$schedule->command('dm:send')->hourly();

		//For taking a snapshot of followers for analysis
		$schedule->command("analysis:follower")->daily("00:00");

		//For refreshing instagram profile sessions & stats
		$schedule->command("ig:refresh")->everyThirtyMinutes();

		//For refreshing interactions quota & releasing ig_throttled
		$schedule->command("refresh:interactionsquota")->hourly();

		//For refreshing Braintree, Stripe & Paypal invoices
		$schedule->command("paypal:updatechargesdaily")
		         ->twiceDaily(5, 17)
		         ->withoutOverlapping()
		         ->emailOutputTo('ywz.lloyd@gmail.com');
		$schedule->command("braintree:listtransactions")
		         ->twiceDaily(4, 16)
		         ->withoutOverlapping();
		$schedule->command("stripe:getinvoice")
		         ->twiceDaily(3, 15)
		         ->withoutOverlapping();
	}

	/**
	 * Register the Closure based commands for the application.
	 *
	 * @return void
	 */
	protected function commands()
	{
		require base_path('routes/console.php');
	}

}