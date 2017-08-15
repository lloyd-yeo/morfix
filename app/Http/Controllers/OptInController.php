<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OptInController extends Controller
{
    public function optin() {
        return view('optin.style1', []);
    }
}
