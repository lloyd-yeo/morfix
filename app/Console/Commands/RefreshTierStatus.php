<?php

namespace App\Console\Commands;

use App\BraintreeSubscription;
use App\BraintreeTransaction;
use Illuminate\Console\Command;
use App\User;
use App\InstagramProfilePhotoPostSchedule;
use App\StripeDetail;
use Stripe\Stripe as Stripe;
use App\StripeActiveSubscription;

class RefreshTierStatus extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'refresh:tier';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Refresh tier status of users.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$users = User::where('tier', '>=', '2')
		             ->where('vip', FALSE)
		             ->where('admin', FALSE)
		             ->get();

		$num_stripe_active_paying_user = 0;
		$num_bt_active_paying_user     = 0;

		foreach ($users as $user) {

			$user_tier = 1;

			if ($user->braintree_id != NULL) {

				\Braintree_Configuration::environment('production');
				\Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
				\Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
				\Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');

				$transactions = BraintreeTransaction::select('sub_id')->distinct()
				                                    ->whereNotNull('sub_id')
				                                    ->where('braintree_id', $user->braintree_id)->get();
				foreach ($transactions as $transaction) {
					$sub_id                 = $transaction->sub_id;
					$subscription           = \Braintree_Subscription::find($sub_id);
					$braintree_subscription = BraintreeSubscription::find($sub_id);
					if ($braintree_subscription == NULL) {
						$braintree_subscription = new BraintreeSubscription;
					}
					$braintree_subscription->subscription_id = $sub_id;
					$braintree_subscription->braintree_id    = $user->braintree_id;
					$braintree_subscription->plan_id         = $subscription->planId;
					$braintree_subscription->status          = $subscription->status;

					if ($braintree_subscription->save()) {
						dump($braintree_subscription);
						$plan = $braintree_subscription->plan_id;

						if ($braintree_subscription->status == "Active") {
							if ($plan == "0137") {
								$user_tier = $user_tier + 1;
							} else if ($plan == "0297") {
								$user_tier = $user_tier + 10;
							} else if ($plan == "MX370") {
								$user_tier = $user_tier + 2;
							} else if ($plan == "MX297") {
								$user_tier = $user_tier + 2;
							} else if ($plan == "MX970") {
								$user_tier = $user_tier + 20;
							} else if ($plan == "0167") {
								$user_tier = $user_tier + 12;
							} else if ($plan == "0197") {
								$user_tier = $user_tier + 11;
							} else if ($plan == "0297") {
								$user_tier = $user_tier + 12;
							}
						}
					}
				}

				$user->tier = $user_tier;
				if ($user->save()) {
					if ($user->tier > 1) {
						$num_bt_active_paying_user++;
					}
					echo $user->email . " [$user_tier] saved!\n";
				} else {
					echo $user->email . " [$user_tier] failed to save!\n";
				}
			} else {
				if ($user->stripeDetails()->count() > 0) {
					//Stripe User
					foreach ($user->stripeDetails() as $stripe_detail) {
						$stripe_id = $stripe_detail->stripe_id;

						$user_active_subscriptions = StripeActiveSubscription::where('stripe_id', $stripe_id)
						                                                     ->whereRaw('(status = \'active\' OR status=\'trialing\')')->get();
						foreach ($user_active_subscriptions as $active_sub) {

							$plan = $active_sub->subscription_id;

							if ($plan == "0137") {
								$user_tier = $user_tier + 1;
							} else if ($plan == "0297") {
								$user_tier = $user_tier + 10;
							} else if ($plan == "MX370") {
								$user_tier = $user_tier + 2;
							} else if ($plan == "MX297") {
								$user_tier = $user_tier + 2;
							} else if ($plan == "MX970") {
								$user_tier = $user_tier + 20;
							} else if ($plan == "0167") {
								$user_tier = $user_tier + 11;
							} else if ($plan == "0197") {
								$user_tier = $user_tier + 11;
							}
						}
					}
					$user->tier = $user_tier;
					if ($user->save()) {
						if ($user->tier > 1) {
							$num_stripe_active_paying_user++;
						}
						echo $user->email . " [$user_tier] saved!\n";
					} else {
						echo $user->email . " [$user_tier] failed to save!\n";
					}
				} else {

				}
			}


		}

		echo "Total number of paying users via Stripe: $num_stripe_active_paying_user\n";
		echo "Total number of paying users via Braintree: $num_bt_active_paying_user\n";
	}
}
