<?php

namespace App\Http\Controllers;

use App\User;
use Braintree_ClientToken;
use Braintree_Configuration;
use Braintree_Customer;
use Braintree_Subscription;
use Illuminate\Http\Request;

class BraintreeController extends Controller
{
	public function payment(Request $request) {
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');
		$client_token = Braintree_ClientToken::generate();
		return view('braintree.payment', ['client_token' => $client_token]);
	}

	public function funnelPremium(Request $request) {
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');
		$client_token = Braintree_ClientToken::generate();
		return view('funnels.upgrade.premium', ['client_token' => $client_token]);
	}

	public function funnelPaymentPremium(Request $request) {
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');

		$plan = $request->input("plan");
		$nonce = $request->input("payment-nonce");
		$name = $request->input("name");
		$email = $request->input("email");
		$password = $request->input("password");

		$result = Braintree_Customer::create([
			'firstName' => $name,
			'email' => $email,
			'paymentMethodNonce' => $nonce,
		]);

		dump($result);

		if ($result->success) {

			$user = new User;
			$user->email = $email;
			$user->password = $password;
			$user->tier = 1;
			$user->braintree_id = $result->customer->id;
			$user->save();

			$sub_result = Braintree_Subscription::create([
				'paymentMethodToken' => $result->customer->paymentMethods[0]->token,
				'merchantAccountId'  => 'morfixUSD',
				'planId'             => $plan
			]);

			if ($sub_result->success) {
				$user->tier = 2;
				$user->save();
			}

			dump($sub_result);
		}
	}
}
