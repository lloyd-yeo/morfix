<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AWeberAPI;
use AWeberAPIException;
use App\User;
use App\UserAffiliates;
use App\UserUpdate;
use Auth;
use Braintree_ClientToken;
use Braintree_Configuration;
use Braintree_Customer;
use Braintree_Subscription;
use Cookie;

class FunnelsController extends Controller
{
	public function payment(Request $request)
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');
		$client_token = Braintree_ClientToken::generate();

		return view('braintree.payment', [ 'client_token' => $client_token ]);
	}

	public function purchasePremium(Request $request)
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');
		$client_token = Braintree_ClientToken::generate();

		return view('funnels.upgrade.premium', [ 'client_token' => $client_token ]);
	}

	public function purchasePro(Request $request)
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');
		$client_token = Braintree_ClientToken::generate();

		return view('funnels.upgrade.pro', [ 'client_token' => $client_token ]);
	}

	public function purchaseBusiness(Request $request)
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');
		$client_token = Braintree_ClientToken::generate();

		return view('funnels.upgrade.business', [ 'client_token' => $client_token ]);
	}

	public function purchaseConfirmation(Request $request)
	{
		return view('funnels.upgrade.confirmation');
	}

	public function paymentPremium(Request $request)
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');

		$plan     = "0137";
		$nonce    = $request->input("payment-nonce");
		$name     = $request->input("name");
		$email    = $request->input("email");
		$password = $request->input("password");

		if (User::where('email', $email)->first() !== NULL) {
			$request->session()->flash('error', 'Email exists! You can login at https://app.morfix.co/login');

			return back()->withInput();
		}

		$result = Braintree_Customer::create([
			'firstName'          => $name,
			'email'              => $email,
			'paymentMethodNonce' => $nonce,
		]);

		if ($result->success) {

			$user                     = new User;
			$user->email              = $email;
			$user->password           = $password;
			$user->name               = $name;
			$user->tier               = 1;
			$user->num_acct           = 1;
			$user->trial_activation   = 2;
			$user->braintree_id       = $result->customer->id;
			$user->verification_token = bin2hex(random_bytes(18));
			$user->save();

			//Add referrer
			$referrer = Cookie::get('morfix_referrer');
			if ($referrer !== NULL) {
				$user_affiliate           = new UserAffiliates;
				$user_affiliate->referrer = $referrer;
				$user_affiliate->referred = $user->user_id;
				$user_affiliate->save();

				$referrer = User::find($referrer);
			}

			$sub_result = Braintree_Subscription::create([
				'paymentMethodToken' => $result->customer->paymentMethods[0]->token,
				'merchantAccountId'  => 'morfixUSD',
				'planId'             => $plan,
			]);

			if ($sub_result->success) {

				$consumerKey    = "AkAxBcK3kI1q0yEfgw4R4c77";
				$consumerSecret = "DEchWOGoptnjNSqtwPz3fgZg6wkMpOTWTYCJcgBF";

				$aweber  = new AWeberAPI($consumerKey, $consumerSecret);
				$account = $aweber->getAccount("AgI2J88WjcAhUkFlCn3OwzLx", "wdX1JHuuhIFm9AEiJt3SVUdM5S7Z8lAE7UKmP29P");

				foreach ($account->lists as $offset => $list) {

					$list_id = $list->id;

					if ($list_id != 4485376 OR $list_id != 4631962) {
						continue;
					}

					# create a subscriber
					$params = [
						'email'                             => $email,
						'name'                              => $name,
						'ip_address'                        => $request->ip(),
						'ad_tracking'                       => 'morfix_registration',
						'last_followup_message_number_sent' => 1,
						'misc_notes'                        => 'MorifX Registration Page',
					];

					try {
						$subscribers    = $list->subscribers;
						$new_subscriber = $subscribers->create($params);
					}
					catch (AWeberAPIException $ex) {
					}
				}

				if ($referrer !== NULL) {
					//Send referrer Premium congrats email
					if ($referrer->tier > 1) {
						$referrer->pending_commission = $referrer->pending_commission + 20;
						$referrer->save();

						//Do a new referral upgrade
						$title = "NEW REFERRAL!";
						$type = "NEW_REFERRAL";
						$update_text = "<a href=\"#\">" . $user->email . "</a> just joined as Premium! You’re getting more and more referrals, keep it up!";

						$user_update = new UserUpdate;
						$user_update->email = $referrer->email;
						$user_update->title = $title;
						$user_update->content = $update_text;
						$user_update->type = $type;
						$user_update->save();

					}
				}

				$user->tier = 2;
				$user->save();
				Auth::loginUsingId($user->user_id, TRUE);

				return redirect('pro');
			} else {
				//Redirect back to Premium page. Let user know of error.
				$request->session()->flash('error', 'Unable to register your account, you have not been charged. Do try again.');

				return back()->withInput();
			}
		} else {
			//Redirect back to Premium page. Let user know of error.
			$request->session()->flash('error', 'Unable to register your account, you have not been charged. Do try again.');

			return back()->withInput();
		}

	}

	public function paymentPro(Request $request)
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');

		$plan = 'MX297';

		$user = User::find(Auth::user()->user_id);

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

			//Get referrer & add commissions
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
					$title = "PRO UPGRADE!";
					$type = "PRO_OTO_UPSELL";
					$update_text = "<a href=\"#\">" . $user->email . "</a> just upgraded to Pro! You've earned yourself another $150USD!";

					$user_update = new UserUpdate;
					$user_update->email = $referrer->email;
					$user_update->title = $title;
					$user_update->content = $update_text;
					$user_update->type = $type;
					$user_update->save();

				} else {

					//Do a new referral upgrade
					$title = "PRO UPGRADE!";
					$type = "PRO_OTO_UPSELL";
					$update_text = "<a href=\"#\">" . $user->email . "</a> just upgraded to Pro! But you missed out on the commission because you are not on Pro!";

					$user_update = new UserUpdate;
					$user_update->email = $referrer->email;
					$user_update->title = $title;
					$user_update->content = $update_text;
					$user_update->type = $type;
					$user_update->save();
				}
			}

			return redirect('business');
		}
	}

	public function paymentBusiness(Request $request)
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');

		$plan = '0297';

		$user               = User::find(Auth::user()->user_id);
		$braintree_id       = $user->braintree_id;

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
					$title = "NEW BUSINESS UPGRADE!";
					$type = "BUSINESS_UPGRADE";
					$update_text = "<a href=\"#\">" . $user->email . "</a> just upgraded to Business! That's another $50 USD for as long as they are there, keep it up!";

					$user_update = new UserUpdate;
					$user_update->email = $referrer->email;
					$user_update->title = $title;
					$user_update->content = $update_text;
					$user_update->type = $type;
					$user_update->save();
				} else {
					//Do a new referral upgrade
					$title = "NEW BUSINESS UPGRADE!";
					$type = "BUSINESS_UPGRADE";
					$update_text = "<a href=\"#\">" . $user->email . "</a> just upgraded to Business! But you missed out on the commission because you are not on Business!";

					$user_update = new UserUpdate;
					$user_update->email = $referrer->email;
					$user_update->title = $title;
					$user_update->content = $update_text;
					$user_update->type = $type;
					$user_update->save();
				}
			}

			return view('funnels.upgrade.confirmation');
		}

	}

	public function ebook()
	{
		return view('funnels.ebook');
	}

	public function ebookVsl(Request $request)
	{

		$consumerKey    = "AkAxBcK3kI1q0yEfgw4R4c77";
		$consumerSecret = "DEchWOGoptnjNSqtwPz3fgZg6wkMpOTWTYCJcgBF";

		$aweber  = new AWeberAPI($consumerKey, $consumerSecret);
		$account = $aweber->getAccount("AgI2J88WjcAhUkFlCn3OwzLx", "wdX1JHuuhIFm9AEiJt3SVUdM5S7Z8lAE7UKmP29P");

		foreach ($account->lists as $offset => $list) {

			$list_id = $list->id;

			if ($list_id != 4798139) {
				continue;
			}

			# create a subscriber
			$params = [
				'email'                             => $request->input("email"),
				'name'                              => $request->input("name"),
				'ip_address'                        => $request->ip(),
				'ad_tracking'                       => 'morfix_ebook',
				'last_followup_message_number_sent' => 1,
				'misc_notes'                        => 'Morfix Ebook',
			];

			try {
				$subscribers    = $list->subscribers;
				$new_subscriber = $subscribers->create($params);
			}
			catch (AWeberAPIException $ex) {

			}
		}

		return view('funnels.ebookvsl');
	}

	public function vsl()
	{
		return view('funnels.onlinevsl');
	}

	public function rcVsl()
	{
		return view('funnels.rcvsl');
	}

	public function mcaVsl()
	{
		return view('funnels.mcavsl');
	}

	public function daVsl()
	{
		return view('funnels.davsl');
	}

	public function ospVsl()
	{
		return view('funnels.ospvsl');
	}

	public function mmoVsl()
	{
		return view('funnels.mmovsl');
	}

	public function toolVsl()
	{
		return view('funnels.toolvsl');
	}

	public function mlmVsl()
	{
		return view('funnels.mlmvsl');
	}

}
