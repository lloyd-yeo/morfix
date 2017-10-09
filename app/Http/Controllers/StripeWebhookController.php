<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StripeWebhookLog;

class StripeWebhookController extends Controller
{
    public function chargeRefunded(Request $request) {
        
    }
    
    public function invoicePaymentFailed(Request $request) {
        
    }
}
