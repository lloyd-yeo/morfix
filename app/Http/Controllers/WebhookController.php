<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Log;
use \Laravel\Cashier\Http\Controllers\WebhookController as BaseController;

class WebhookController extends BaseController {

    public function handleInvoiceCreated(array $payload) {
        Log::info('Invoice Created - StripeWebhook - handlewWebhook()', ['details' => json_encode($payload)]);
        DB::connection('mysql_old')->insert("INSERT INTO stripe_webhook_log (log) VALUES (?)", [serialize($payload)]);
        return response('Webhook Handled', 200);
    }

}
