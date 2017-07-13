<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Cookie\CookieJar;
use Response;
use App\User;

class ReferrerController extends Controller {

    public function redirect(CookieJar $cookieJar, Request $request) {
        
        if ($request->referrer) {
            $cookieJar->queue(cookie('referrer', $request->referrer, 45000));
        }
        
        $redir = $request->input("redir");
        
        if ($redir == "payment") {
            
        } else {
            return view('vsl.signup', [
                'redir' => $redir,
            ]);
        }
    }
    
    public function processPayment(Request $request) {
        #echo $request->cookie('referrer');
    }
    
}
