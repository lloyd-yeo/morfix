<?php

namespace App\Http\Controllers;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Symfony\Component\HttpFoundation\Response;
use Log;


class StripeWebhookController extends CashierController
{
    public function handleChargeRefunded(array $payload)
    {
        Log::info('Payment Refunded - StripeWebhook - handleChargeRefunded()', ['details' => json_encode($payload)]);
        DB::connection('mysql_old')->insert("INSERT INTO stripe_webhook_log (log) VALUES (?)", [serialize($payload)]);
        return new Response('Webhook Handled', 200);
    }
}

 

