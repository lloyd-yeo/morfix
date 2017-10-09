<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StripeWebhookLog;
use Carbon\Carbon;

class StripeWebhookController extends Controller {

    public function chargeRefunded(Request $request) {

        \Stripe\Stripe::setApiKey("sk_test_dAO7D2WkkUOHnuHgXBeti0KM");
        
        $webhook_log = new StripeWebhookLog;
        $webhook_log->log = $request->json();
        $webhook_log->date_logged = Carbon::now();
        $webhook_log->save();
    }

    public function invoicePaymentFailed(Request $request) {
        
        \Stripe\Stripe::setApiKey("sk_test_dAO7D2WkkUOHnuHgXBeti0KM");
        
        $webhook_log = new StripeWebhookLog;
        $webhook_log->log = serialize($request->all());
        $webhook_log->date_logged = Carbon::now();
        $webhook_log->save();
        
    }

}
