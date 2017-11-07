<?php

namespace App\Http\Controllers;

use Braintree_ClientToken;
use Braintree_Configuration;
use Illuminate\Http\Request;

class BraintreeController extends Controller
{
	public function __construct()
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');
	}

	public function payment(Request $request) {
		$clientToken = Braintree_ClientToken::generate();
		dump($clientToken);
		return view('funnels.ebook');
	}
}
