<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\PaypalWebhookLog;

class PaypalWebhookController extends Controller
{
    public function listen(Request $request) {
        
        $log = new PaypalWebhookLog;
        $log->message = "NEW WEBHOOK HERE!";
        $log->save();
        
        $log = new PaypalWebhookLog;
        $log->message = serialize($request);
        $log->save();
    }
}
