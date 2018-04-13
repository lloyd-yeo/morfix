<?php

namespace App\Http\Controllers;

use App\MorfixPlan;
use App\PaymentLog;
use App\PaypalAgreement;
use App\StripeDetail;
use App\User;
use App\Mail\NewPremiumAffiliate;
use App\UserAffiliates;
use Auth;
use AWeberAPI;
use Braintree_ClientToken;
use Braintree_Configuration;
use Braintree_Customer;
use Braintree_Subscription;
use Cookie;
use Illuminate\Http\Request;
use PayPal\Api\Agreement;
use PayPal\Api\Payer;
use PayPal\Api\Plan;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Response;
use App\CompetitionUpdate;
use App\UserUpdate;
use Mail;

class PaymentController extends Controller
{

	private $apiContext;
	private $mode;
	private $client_id;
	private $secret;

	// Create a new instance with our paypal credentials
	public function __construct()
	{
		// Detect if we are running in live mode or sandbox
		if (config('paypal.settings.mode') == 'live') {
			$this->client_id = config('paypal.live_client_id');
			$this->secret    = config('paypal.live_secret');
		} else {
			$this->client_id = config('paypal.sandbox_client_id');
			$this->secret    = config('paypal.sandbox_secret');
		}

		// Set the Paypal API Context/Credentials
		$this->apiContext = new ApiContext(new OAuthTokenCredential($this->client_id, $this->secret));
		$this->apiContext->setConfig(config('paypal.settings'));
	}

	public function upgradePremium(Request $request)
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');
		$client_token = Braintree_ClientToken::generate();

		return view('payment.upgrade.premium', [ 'client_token' => $client_token ]);
	}

	public function upgradePro(Request $request)
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');
		$client_token = Braintree_ClientToken::generate();

		if ($request->session()->has('upsell')) {
			$request->session()->forget('upsell');

			return view('payment.upgrade.funnel.pro');
		} else {
			return view('payment.upgrade.pro', [ 'client_token' => $client_token ]);
		}
	}

	public function upgradeBusiness(Request $request)
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');

		$client_token = NULL;
		if (Auth::user()->braintree_id != NULL) {
			$client_token = Braintree_ClientToken::generate([ 'customerId' => Auth::user()->braintree_id ]);
		} else {
			$client_token = Braintree_ClientToken::generate();
		}

		if ($request->session()->has('upsell')) {
			return view('payment.upgrade.funnel.business');
		} else {
			return view('payment.upgrade.business', [ 'client_token' => $client_token ]);
		}
	}

	public function upgradeMastermind(Request $request)
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');

		$client_token = NULL;
		if (Auth::user()->braintree_id != NULL) {
			$client_token = Braintree_ClientToken::generate([ 'customerId' => Auth::user()->braintree_id ]);
		} else {
			$client_token = Braintree_ClientToken::generate();
		}

		return view('payment.upgrade.mastermind', [ 'client_token' => $client_token ]);

	}

	public function upgradePremiumPayment(Request $request)
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');

		$plan  = "0137";
		$nonce = $request->input("payment-nonce");

		$user = User::find(Auth::user()->user_id);

		if ($user->email == "ywz.lloyd@gmail.com") {
			$plan = "0137test";
		}

		$success = FALSE;

		$sub_result = NULL;
		if ($user->braintree_id == NULL) {

			//if no braintree records, create new braintree customer
			$result = Braintree_Customer::create([
				'firstName'          => Auth::user()->name,
				'email'              => Auth::user()->email,
				'paymentMethodNonce' => $nonce,
			]);

			if ($result->success) {
				//if success store braintree_id under customer
				$user->braintree_id = $result->customer->id;
				$user->save();

				$sub_result = Braintree_Subscription::create([
					'paymentMethodToken' => $result->customer->paymentMethods[0]->token,
					'merchantAccountId'  => 'morfixUSD',
					'planId'             => $plan,
				]);
			} else {
				dump($result);
				//				foreach($result->errors->deepAll() AS $error) {
				//					dump($error->attribute . ": " . $error->code . " " . $error->message . "\n");
				//				}
				//
				//				foreach($result->errors->forKey('customer')->shallowAll() AS $error) {
				//					dump($error->attribute . ": " . $error->code . " " . $error->message . "\n");
				//				}
				//
				//				foreach($result->errors->forKey('customer')->forKey('creditCard')->shallowAll() AS $error) {
				//					dump($error->attribute . ": " . $error->code . " " . $error->message . "\n");
				//				}
			}


		} else {
			//find braintree_customer from existing records.
			$customer = Braintree_Customer::find($user->braintree_id);

			$sub_result = Braintree_Subscription::create([
				'paymentMethodToken' => $customer->paymentMethods[0]->token,
				'merchantAccountId'  => 'morfixUSD',
				'planId'             => $plan,
			]);
		}

		if ($sub_result->success) {

			if ($user->tier == 1 && $user->trial_upgrade == 0) {
				$user->trial_upgrade = 1;
			}

			$this->subscribeAweberPaid($user, $request->ip(), 2);

			//Get referrer
			$referrer       = NULL;
			$user_affiliate = UserAffiliates::where('referred', $user->user_id)->first();
			if ($user_affiliate !== NULL) {
				$referrer = User::find($user_affiliate->referrer);
			}

			if ($referrer !== NULL) {
				//Send referrer Premium congrats email
				if ($referrer->tier > 1) {
					$referrer->pending_commission = $referrer->pending_commission + 20;
					$referrer->save();

					//Do a new referral upgrade
					$title       = "NEW REFERRAL!";
					$type        = "NEW_REFERRAL";
					$update_text = "<a href=\"#\">" . $user->email . "</a> just upgraded to Premium! You’re getting more and more referrals, keep it up!";

					$user_update          = new UserUpdate;
					$user_update->email   = $referrer->email;
					$user_update->title   = $title;
					$user_update->content = $update_text;
					$user_update->type    = $type;
					$user_update->save();

					if ($referrer->is_competitor == 1) {
						$user_competitor_update          = new CompetitionUpdate;
						$user_competitor_update->email   = $referrer->email;
						$user_competitor_update->title   = $title;
						$user_competitor_update->content = $update_text;
						$user_competitor_update->type    = $type;
						$user_competitor_update->save();
					}
					try {
						Mail::to($referrer->email)->send(new NewPremiumAffiliate($referrer, $user));
					}
					catch (\Exception $ex) {

					}
				}
			}

			$user->tier = 2;
			$user->save();
			$request->session()->flash('payment', 'Congratulations! You are now on Premium!');

			return redirect('/upgrade/pro')->with('upsell', TRUE);
		} else {
			//Redirect back to Premium page. Let user know of error.
			$request->session()->flash('error', 'Unable to register your account, you have not been charged. Do try again.');

			return back()->withInput();
		}
	}

	public function upgradeProPayment(Request $request)
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');

		$upsell = $request->input('upsell', FALSE);
		$nonce  = $request->input("payment-nonce");

		//Not upsell
		if (!$upsell) {

			$plan = "MX370";
			$user = User::find(Auth::user()->user_id);

			if ($user->email == "ywz.lloyd@gmail.com") {
				$plan = "MX370test";
			}

			if ($user->braintree_id === NULL) {

				$result = Braintree_Customer::create([
					'firstName'          => Auth::user()->name,
					'email'              => Auth::user()->email,
					'paymentMethodNonce' => $nonce,
				]);

				if ($result->success) {
					$user->braintree_id = $result->customer->id;
					$user->save();
				} else {
					//Redirect back to Premium page. Let user know of error.
					$request->session()->flash('error', 'Unable to register your account, you have not been charged. Do try again.');

					return back()->withInput();
				}

			}

			//Get referrer
			$referrer       = NULL;
			$user_affiliate = UserAffiliates::where('referred', $user->user_id)->first();
			if ($user_affiliate !== NULL) {
				$referrer = User::find($user_affiliate->referrer);
			}

			$customer = Braintree_Customer::find($user->braintree_id);

			$sub_result = Braintree_Subscription::create([
				'paymentMethodToken' => $customer->paymentMethods[0]->token,
				'merchantAccountId'  => 'morfixUSD',
				'planId'             => $plan,
			]);

			if ($sub_result->success) {

				if ($user->trial_upgrade > 0) {
					$user->trial_upgrade = 1;
				}

				$this->subscribeAweberPaid($user, $request->ip(), 3);

				if ($referrer !== NULL) {
					//Send referrer Pro congrats email
					if ($referrer->tier % 10 == 3) {
						$referrer->pending_commission = $referrer->pending_commission + 200;
						$referrer->save();

						//Do a new referral upgrade
						$title       = "PRO UPGRADE!";
						$type        = "PRO_OTO_UPSELL";
						$update_text = "<a href=\"#\">" . $user->email . "</a> just upgraded to Pro! You've earned yourself another $200USD!";

						$user_update          = new UserUpdate;
						$user_update->email   = $referrer->email;
						$user_update->title   = $title;
						$user_update->content = $update_text;
						$user_update->type    = $type;
						$user_update->save();

						if ($referrer->is_competitor == 1) {
							$user_competitor_update          = new CompetitionUpdate;
							$user_competitor_update->email   = $referrer->email;
							$user_competitor_update->title   = $title;
							$user_competitor_update->content = $update_text;
							$user_competitor_update->type    = $type;
							$user_competitor_update->save();
						}
					}
				}

				$add_on_tier = (int)($user->tier / 10);
				$user->tier  = $add_on_tier + 3;
				$user->save();

				$request->session()->flash('payment', 'Congratulations! You are now on Pro!');

				return redirect('/upgrade/business')->with('upsell', TRUE);
			} else {
				//Redirect back to Premium page. Let user know of error.
				$request->session()->flash('error', 'Unable to register your account, you have not been charged. Do try again.');

				return back()->withInput();
			}

		} else {
			$plan = 'MX297';

			$user = User::find(Auth::user()->user_id);

			if ($user->email == "ywz.lloyd@gmail.com") {
				$plan = "MX297test";
			}

			$braintree_id       = $user->braintree_id;
			$braintree_customer = Braintree_Customer::find($braintree_id);

			$sub_result = Braintree_Subscription::create([
				'paymentMethodToken' => $braintree_customer->paymentMethods[0]->token,
				'merchantAccountId'  => 'morfixUSD',
				'planId'             => $plan,
			]);

			if ($sub_result->success) {
				$user->tier = 3;
				$user->save();

				$this->subscribeAweberPaid($user, $request->ip(), 3);

				//Get referrer
				$referrer       = NULL;
				$user_affiliate = UserAffiliates::where('referred', $user->user_id)->first();
				if ($user_affiliate !== NULL) {
					$referrer = User::find($user_affiliate->referrer);
				}

				if ($referrer !== NULL) {
					//Send referrer Pro congrats email
					if ($referrer->tier % 10 == 3) {
						$referrer->pending_commission = $referrer->pending_commission + 150;
						$referrer->save();

						//Do a new referral upgrade
						$title       = "PRO UPGRADE!";
						$type        = "PRO_OTO_UPSELL";
						$update_text = "<a href=\"#\">" . $user->email . "</a> just upgraded to Pro! You've earned yourself another $150USD!";

						$user_update          = new UserUpdate;
						$user_update->email   = $referrer->email;
						$user_update->title   = $title;
						$user_update->content = $update_text;
						$user_update->type    = $type;
						$user_update->save();

						if ($referrer->is_competitor == 1) {
							$user_competitor_update          = new CompetitionUpdate;
							$user_competitor_update->email   = $referrer->email;
							$user_competitor_update->title   = $title;
							$user_competitor_update->content = $update_text;
							$user_competitor_update->type    = $type;
							$user_competitor_update->save();
						}
					}
				}

				return redirect('/upgrade/business')->with('upsell', TRUE);
			}
		}
	}

	public function upgradeBusinessPayment(Request $request)
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');

		$plan = '0297';

		$user = User::find(Auth::user()->user_id);

		if ($user->email == "ywz.lloyd@gmail.com") {
			$plan = "0297test";
		}

		$braintree_id = $user->braintree_id;
		$nonce        = $request->input("payment-nonce");

		if ($braintree_id === NULL) {

			$result = Braintree_Customer::create([
				'firstName'          => Auth::user()->name,
				'email'              => Auth::user()->email,
				'paymentMethodNonce' => $nonce,
			]);

			if ($result->success) {
				$user->braintree_id = $result->customer->id;
				$user->save();
			} else {
				//Redirect back to Premium page. Let user know of error.
				$request->session()->flash('error', 'Unable to register your account, you have not been charged. Do try again.');

				return back()->withInput();
			}

		}

		$braintree_customer = Braintree_Customer::find($braintree_id);

		$sub_result = Braintree_Subscription::create([
			'paymentMethodToken' => $braintree_customer->paymentMethods[0]->token,
			'merchantAccountId'  => 'morfixUSD',
			'planId'             => $plan,
		]);

		if ($sub_result->success) {
			$user->num_acct = 6;
			$user->tier     = $user->tier + 10;
			$user->save();

			$this->subscribeAweberPaid($user, $request->ip(), 10);

			//Get referrer & add commissions
			$referrer       = NULL;
			$user_affiliate = UserAffiliates::where('referred', $user->user_id)->first();
			if ($user_affiliate !== NULL) {
				$referrer = User::find($user_affiliate->referrer);
			}

			if ($referrer !== NULL) {
				//Send referrer Pro congrats email
				if ($referrer->tier / 10 > 0) {
					$referrer->pending_commission = $referrer->pending_commission + 50;
					$referrer->save();

					//Do a new referral upgrade
					$title       = "NEW BUSINESS UPGRADE!";
					$type        = "BUSINESS_UPGRADE";
					$update_text = "<a href=\"#\">" . $user->email . "</a> just upgraded to Business! That's another $50 USD for as long as they are there, keep it up!";

					$user_update          = new UserUpdate;
					$user_update->email   = $referrer->email;
					$user_update->title   = $title;
					$user_update->content = $update_text;
					$user_update->type    = $type;
					$user_update->save();

					if ($referrer->is_competitor == 1) {
						$user_competitor_update          = new CompetitionUpdate;
						$user_competitor_update->email   = $referrer->email;
						$user_competitor_update->title   = $title;
						$user_competitor_update->content = $update_text;
						$user_competitor_update->type    = $type;
						$user_competitor_update->save();
					}

				}
			}

			return view('payment.upgrade.funnel.confirmation');
		}
	}


	public function upgradeMastermindPayment(Request $request)
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');

		$plan = 'MX970';

		$user = User::find(Auth::user()->user_id);

		if ($user->email == "ywz.lloyd@gmail.com") {
			$plan = "mx970test";
		}

		$braintree_id = $user->braintree_id;
		$nonce        = $request->input("payment-nonce");

		if ($braintree_id === NULL) {
			$result = Braintree_Customer::create([
				'firstName'          => Auth::user()->name,
				'email'              => Auth::user()->email,
				'paymentMethodNonce' => $nonce,
			]);

			if ($result->success) {
				$user->braintree_id = $result->customer->id;
				$user->save();
			} else {
				//Redirect back to Premium page. Let user know of error.
				$request->session()->flash('error', 'Unable to register your account, you have not been charged. Do try again.');

				return back()->withInput();
			}
		}

		$braintree_customer = Braintree_Customer::find($braintree_id);

		$sub_result = Braintree_Subscription::create([
			'paymentMethodToken' => $braintree_customer->paymentMethods[0]->token,
			'merchantAccountId'  => 'morfixUSD',
			'planId'             => $plan,
		]);

		if ($sub_result->success) {
			$user->num_acct = 6;
			$user->tier     = $user->tier + 20;
			$user->save();

			$this->subscribeAweberPaid($user, $request->ip(), 20);

			//Get referrer & add commissions
			$referrer       = NULL;
			$user_affiliate = UserAffiliates::where('referred', $user->user_id)->first();
			if ($user_affiliate !== NULL) {
				$referrer = User::find($user_affiliate->referrer);
			}

			if ($referrer !== NULL) {
				//Send referrer Pro congrats email
				if ($referrer->tier - 20 > 0) {
					$referrer->pending_commission = $referrer->pending_commission + 500;
					$referrer->save();

					//Do a new referral upgrade
					$title       = "NEW BUSINESS UPGRADE!";
					$type        = "BUSINESS_UPGRADE";
					$update_text = "<a href=\"#\">" . $user->email . "</a> just upgraded to Mastermind! That's another $500 USD for as long as they are there, keep it up!";

					$user_update          = new UserUpdate;
					$user_update->email   = $referrer->email;
					$user_update->title   = $title;
					$user_update->content = $update_text;
					$user_update->type    = $type;
					$user_update->save();

					if ($referrer->is_competitor == 1) {
						$user_competitor_update          = new CompetitionUpdate;
						$user_competitor_update->email   = $referrer->email;
						$user_competitor_update->title   = $title;
						$user_competitor_update->content = $update_text;
						$user_competitor_update->type    = $type;
						$user_competitor_update->save();
					}

				}
			}

			return view('payment.upgrade.funnel.confirmation');
		}
	}

	public function subscribeAweberPaid (User $user, $ip_addr, $tier) {
		$consumerKey    = "AkAxBcK3kI1q0yEfgw4R4c77";
		$consumerSecret = "DEchWOGoptnjNSqtwPz3fgZg6wkMpOTWTYCJcgBF";

		$aweber  = new AWeberAPI($consumerKey, $consumerSecret);
		$account = $aweber->getAccount("AgI2J88WjcAhUkFlCn3OwzLx", "wdX1JHuuhIFm9AEiJt3SVUdM5S7Z8lAE7UKmP29P");

		foreach ($account->lists as $offset => $list) {

			$list_id = $list->id;

			if ($list_id != 4485376 OR $list_id != 4631962) {
				continue;
			}

			$notes = "";
			switch ($tier) {
				case 2:
					$notes = "Morfix Premium Payment Page";
					break;
				case 3:
					$notes = "Morfix Pro Payment Page";
					break;
				case 10:
					$notes = "Morfix Business Payment Page";
					break;
				case 20:
					$notes = "Morfix Mastermind Payment Page";
					break;
			}

			# create a subscriber
			$params = [
				'email'                             => $user->email,
				'name'                              => $user->name,
				'ip_address'                        => $ip_addr,
				'ad_tracking'                       => 'morfix_registration',
				'last_followup_message_number_sent' => 1,
				'misc_notes'                        => $notes,
			];

			try {
				$subscribers    = $list->subscribers;
				$new_subscriber = $subscribers->create($params);
			}
			catch (AWeberAPIException $ex) {
				//aweber exception just let it slide
			}
		}
	}
	public function index(Request $request)
	{
		return view('payment.index', [
		]);
	}
}
