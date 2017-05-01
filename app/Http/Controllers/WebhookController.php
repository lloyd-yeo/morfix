<?php

namespace App\Http\Controllers;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class WebhookController extends CashierController
{
    /**
     * Handle a Stripe webhook.
     *
     * @param  array  $payload
     * @return Response
     */
    public function handleInvoicePaymentSucceeded($payload)
    {
        // Handle The Event
        DB::connection('mysql_old')->insert("INSERT INTO stripe_webhook_log (log) VALUES (?)", [serialize($payload)]);
        return new Response('Webhook Handled', 200);
    }
}
