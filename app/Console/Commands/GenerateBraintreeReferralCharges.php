<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\BraintreeTransaction;
use App\User;
use App\UserAffiliates;
use App\StripeDetail;
use App\PaypalCharges;
use App\StripeActiveSubscription;

class GenerateBraintreeReferralCharges extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'generate:braintreetransactions {debug?}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate Braintree Referral Charges';

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
		$users = [];

		$referred_user_under_braintree = User::whereNotNull('braintree_id')->get();

		foreach ($referred_user_under_braintree as $referred_user) {

			$referrer_id = NULL;

			$user_affiliate = UserAffiliates::where('referred', $referred_user->user_id)->whereNotNull('referrer')->first();
			if ($user_affiliate != NULL) {
				$referrer_user  = User::find($user_affiliate->referrer);
				$referrer_email = $referrer_user->email;

				if (!array_has($users, $referrer_email)) {
					$users[$referrer_email]               = [];
					$users[$referrer_email]["premium"]    = 0;
					$users[$referrer_email]["business"]   = 0;
					$users[$referrer_email]["pro"]        = 0;
					$users[$referrer_email]["mastermind"] = 0;
					$users[$referrer_email]["vip"]        = 0;

					$referrer_charge = User::where('email', $referrer_email)->first();

					$stripe_details = StripeDetail::where('email', $referrer_email)->get();

					foreach ($stripe_details as $stripe_detail) {
						if ($referrer_charge->vip == 1) {
							$users[$referrer_email]["vip"] = 1;
						} else {
							$subs = StripeActiveSubscription::where('stripe_id', $stripe_detail->stripe_id)->get();
							foreach ($subs as $sub) {
								if ($sub->status == "active" || $sub->status == "trialing") {
									if ($sub->subscription_id == "0137") {
										$users[$referrer_email]["premium"] = 1;
									} else if ($sub->subscription_id == "0167") {
										$users[$referrer_email]["premium"]  = 1;
										$users[$referrer_email]["business"] = 1;
									} else if ($sub->subscription_id == "0197") {
										$users[$referrer_email]["premium"]  = 1;
										$users[$referrer_email]["business"] = 1;
									} else if ($sub->subscription_id == "0297" && $referrer_email == "Yongshaokoko@gmail.com") {
										$users[$referrer_email]["premium"]  = 1;
										$users[$referrer_email]["business"] = 1;
									} else if ($sub->subscription_id == "0297") {
										$users[$referrer_email]["business"] = 1;
									} else if ($sub->subscription_id == "MX370" || $sub->subscription_id == "MX297") {
										$users[$referrer_email]["pro"] = 1;
									}
								}
							}
						}
					}

					$paypal_charges_for_referrer = PaypalCharges::where('email', $referrer_email)
					                                            ->where('status', 'Completed')->where('time_stamp', '<', '2017-09-01 00:00:00')->get();
					foreach ($paypal_charges_for_referrer as $paypal_charge_for_referrer) {
						if ($paypal_charge_for_referrer->subscription_id == "0137") {
							$users[$referrer_email]["premium"] = 1;
						} else if ($paypal_charge_for_referrer->subscription_id == "0297" && $referrer_email == "Yongshaokoko@gmail.com") {
							$users[$referrer_email]["premium"]  = 1;
							$users[$referrer_email]["business"] = 1;
						} else if ($paypal_charge_for_referrer->subscription_id == "0297") {
							$users[$referrer_email]["business"] = 1;
						} else if ($paypal_charge_for_referrer->subscription_id == "MX370" || $sub->subscription_id == "MX297") {
							$users[$referrer_email]["pro"] = 1;
						}
					}

					$braintree_transactions = BraintreeTransaction::where('user_email', $referrer_email)
					                                              ->where('status', '!=', 'voided')
					                                              ->where('status', '!=', 'processor_declined')
					                                              ->orderBy('sub_id', 'desc')
					                                              ->get();

					$refunded_braintree_subscriptions = array();

					foreach ($braintree_transactions as $braintree_transaction) {
						if ($braintree_transaction->type == "credit") {
							$refunded_braintree_subscriptions[$braintree_transaction->sub_id] = 1;
						}
					}

					foreach ($braintree_transactions as $braintree_transaction) {
						if (!array_has($refunded_braintree_subscriptions, $braintree_transaction->sub_id) && $braintree_transaction->plan_id != NULL) {
							if ($braintree_transaction->plan_id == "0137") {
								$users[$referrer_email]["premium"]    = 1;
							} else if ($braintree_transaction->plan_id == "0297") {
								$users[$referrer_email]["business"]   = 1;
							} else if ($braintree_transaction->plan_id == "MX370") {
								$users[$referrer_email]["premium"]    = 1;
								$users[$referrer_email]["pro"]        = 1;
							} else if ($braintree_transaction->plan_id == "MX970") {
								$users[$referrer_email]["business"]   = 1;
								$users[$referrer_email]["mastermind"] = 1;
							}
						}
					}

					if ($this->argument("debug") !== NULL) {
						dump($users[$referrer_email]);
					}
				}


			}
		}
	}
}
