<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Log;

class WebhookController extends CashierController {

    public function handleInvoiceCreated(array $payload) {
        Log::info('Invoice Created - StripeWebhook - handlewWebhook()', ['details' => json_encode($payload)]);
        DB::connection('mysql_old')->insert("INSERT INTO stripe_webhook_log (log) VALUES (?)", [serialize($payload)]);
        return new Response('Webhook Handled', 200);
    }

}
