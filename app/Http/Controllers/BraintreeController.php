<?php

namespace App\Http\Controllers;

use App\User;
use App\UserAffiliates;
use Auth;
use Braintree_ClientToken;
use Braintree_Configuration;
use Braintree_Customer;
use Braintree_Subscription;
use Cookie;
use Illuminate\Http\Request;

class BraintreeController extends Controller
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

	public function funnelPremium(Request $request)
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');
		$client_token = Braintree_ClientToken::generate();

		return view('funnels.upgrade.premium', [ 'client_token' => $client_token ]);
	}

	public function funnelPro(Request $request)
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');
		$client_token = Braintree_ClientToken::generate();

		return view('funnels.upgrade.pro', [ 'client_token' => $client_token ]);
	}

	public function funnelPaymentPremium(Request $request)
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');

		$plan     = "0137test";
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

			$user               = new User;
			$user->email        = $email;
			$user->password     = $password;
			$user->name         = $name;
			$user->tier         = 1;
			$user->braintree_id = $result->customer->id;
			$user->save();

			//Add as referrer
			$referrer = Cookie::get('referrer');
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

				if ($referrer !== NULL) {
					//Send referrer Premium congrats email
				}

				$user->tier = 2;
				$user->save();
				Auth::loginUsingId($user->user_id, TRUE);

				return redirect('funnels/upgrade/pro');
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

	public function funnelPaymentPro(Request $request)
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');

		$plan = 'MX370';

		$user = NULL;

		$braintree_id       = $user->braintree_id;
		$braintree_customer = Braintree_Customer::find($braintree_id);

		$sub_result = Braintree_Subscription::create([
			'paymentMethodToken' => $braintree_customer->paymentMethods[0]->token,
			'merchantAccountId'  => 'morfixUSD',
			'planId'             => $plan,
		]);

		if ($sub_result->success) {
			$tier        = $user->tier;
			$add_on_tier = (int)($tier / 10);
			$tier        = $add_on_tier + 3;
			$user->tier  = $tier;

			$user->save();
		}

		//
		//		$user = User::where('email', 'ywz.lloyd@gmail.com');
		//		$braintree_id = $user->braintree_id;
		//		$braintree_customer = Braintree_Customer::find($braintree_id);
		//
		//		$sub_result = Braintree_Subscription::create([
		//			'paymentMethodToken' => $braintree_customer->paymentMethods[0]->token,
		//			'merchantAccountId'  => 'morfixUSD',
		//			'planId'             => $plan
		//		]);
		//
		//		if ($sub_result->success) {
		//			$user->tier = $user->tier + 10;
		//			$user->save();
		//		}

	}

	public function funnelPaymentBusiness(Request $request)
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');

		$plan = '0297';

		$user               = User::where('email', 'ywz.lloyd@gmail.com');
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
		}

	}
}
