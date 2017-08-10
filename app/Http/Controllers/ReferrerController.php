<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Cookie\CookieJar;
use Response;
use App\User;

class ReferrerController extends Controller {

    public function redirect(CookieJar $cookieJar, Request $request) {
        
        if ($request->referrer) {
            \Cookie::forget('referrer');
            $cookieJar->queue(cookie()->forever('referrer', $request->referrer, 45000));
        }
        
        $redir = $request->input("redir");
        
        if ($redir == "payment") {
            return view('vsl.payment', [
            ]);
        } elseif ($redir == "home") {
            return redirect('https://morfix.co');
        } else {
            return view('vsl.signup', [
                'redir' => $redir,
            ]);
        }
    }
}
