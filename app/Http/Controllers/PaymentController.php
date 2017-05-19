<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    function upgrade(Request $request, $plan) {
        
        return view('payment.index', [
        ]);
    }
}
