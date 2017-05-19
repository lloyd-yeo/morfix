<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    function upgrade() {
        return view('payment.index', [
        ]);
    }
}
