<?php

namespace App\Console\Commands;

use App\BraintreeTransaction;
use App\PaypalCharges;
use App\StripeActiveSubscription;
use App\StripeDetail;
use App\User;
use App\UserAffiliates;
use Braintree_Customer;
use Illuminate\Console\Command;

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

		\Braintree_Configuration::environment('production');
		\Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		\Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		\Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');

		$users = [];

		$referred_user_under_braintree = User::whereNotNull('braintree_id')->get();

		foreach ($referred_user_under_braintree as $referred_user) {

			$referrer_id = NULL;

			$user_affiliate = UserAffiliates::where('referred', $referred_user->user_id)->whereNotNull('referrer')->first();
			if ($user_affiliate != NULL) { //this referred user has a referrer
				$referrer_user  = User::find($user_affiliate->referrer);
				$referrer_email = $referrer_user->email;

				//If referrer is not in the $users array yet, create a new entry with their email & decide the eligibility of this referrer.
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

					if ($referrer_charge->braintree_id != NULL) {
						$bt_customer = \Braintree_Customer::find($referrer_charge->braintree_id);
						dump($bt_customer);
					}



//					$braintree_transactions = BraintreeTransaction::where('user_email', $referrer_email)
//					                                              ->where('status', '!=', 'voided')
//					                                              ->where('status', '!=', 'processor_declined')
//					                                              ->where('amount' > '0.02')
//					                                              ->orderBy('sub_id', 'desc')
//					                                              ->get();
//
//					$refunded_braintree_subscriptions = [];
//
//					foreach ($braintree_transactions as $braintree_transaction) {
//						if ($braintree_transaction->type == "credit") {
//							$refunded_braintree_subscriptions[$braintree_transaction->sub_id] = 1;
//						}
//					}
//
//					foreach ($braintree_transactions as $braintree_transaction) {
//						if (!array_has($refunded_braintree_subscriptions, $braintree_transaction->sub_id) && $braintree_transaction->plan_id != NULL) {
//							if ($braintree_transaction->plan_id == "0137") {
//								$users[$referrer_email]["premium"] = 1;
//							} else if ($braintree_transaction->plan_id == "0297") {
//								$users[$referrer_email]["business"] = 1;
//							} else if ($braintree_transaction->plan_id == "MX370") {
//								$users[$referrer_email]["premium"] = 1;
//								$users[$referrer_email]["pro"]     = 1;
//							} else if ($braintree_transaction->plan_id == "MX970") {
//								$users[$referrer_email]["business"]   = 1;
//								$users[$referrer_email]["mastermind"] = 1;
//							}
//						}
//					}

					if ($this->argument("debug") !== NULL) {
						dump($users[$referrer_email]);
					}
				}

				//get Braintree transactions for this user
//				$braintree_transactions = BraintreeTransaction::where('user_email', $referred_user->user_id)
//				                    ->where('status', '!=', 'voided')
//				                    ->where('status', '!=', 'processor_declined')
//				                    ->where('amount' > '0.02')
//				                    ->orderBy('sub_id', 'desc')
//				                    ->get();
//
//				$refunded_referred_braintree_subscriptions = [];
//
//				foreach ($braintree_transactions as $braintree_transaction) {
//					if ($braintree_transaction->type == "credit") {
//						$refunded_referred_braintree_subscriptions[$braintree_transaction->sub_id] = 1;
//					}
//				}
//
//				foreach ($braintree_transactions as $braintree_transaction) {
//					if (!array_has($refunded_braintree_subscriptions, $braintree_transaction->sub_id) && $braintree_transaction->plan_id != NULL) {
//						if ($braintree_transaction->plan_id == "0137") {
//							$users[$referrer_email]["premium"] = 1;
//						} else if ($braintree_transaction->plan_id == "0297") {
//							$users[$referrer_email]["business"] = 1;
//						} else if ($braintree_transaction->plan_id == "MX370") {
//							$users[$referrer_email]["premium"] = 1;
//							$users[$referrer_email]["pro"]     = 1;
//						} else if ($braintree_transaction->plan_id == "MX970") {
//							$users[$referrer_email]["business"]   = 1;
//							$users[$referrer_email]["mastermind"] = 1;
//						}
//					}
//				}


			}
		}
	}
}
