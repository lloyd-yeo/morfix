<?php

namespace App\Http\Controllers;

use Braintree\WebhookNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Log;

class CancellationSubscriptionWebhook extends Controller
{
	/**
	 * Handle a Braintree webhook.
	 *
	 * @param  WebhookNotification $webhook
	 *
	 * @return Response
	 */
	public function handleDisputeOpened(Request $request)
	{
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
		Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
		Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');

		$notification = WebhookNotification::parse($request->bt_signature, $request->bt_payload);

		Log::info($notification);

		return new Response('Webhook Handled', 200);
	}
}