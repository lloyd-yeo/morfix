<?php

namespace App\Http\Controllers\CancellationSubscriptionWebhook;
use App\Http\Controllers\Controller;
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
        $payload = $request->all();
        Log::info( $payload );
        return new Response('Webhook Handled', 200);
    }
}