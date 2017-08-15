<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PayPal\Api\VerifyWebhookSignature;
use PayPal\Api\WebhookEvent;
use App\User;
use App\PaypalWebhookLog;

class PaypalWebhookController extends Controller
{
    public function listen(Request $request) {
        
        $log = new PaypalWebhookLog;
        $log->message = serialize($request->all());
        $log->save();
        
        
    }
}
