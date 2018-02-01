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
     * @param  WebhookNotification  $webhook
     * @return Response
     */
    public function handleDisputeOpened(Request $request)
    {
        $notification = WebhookNotification::parse($request->bt_signature, $request->bt_payload);

//        $payload = $request->all();
          Log::info( $notification );
        return new Response('Webhook Handled', 200);
    }
}