<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Cookie\CookieJar;
use Response;
use App\User;

class ReferrerController extends Controller
{
    public function redirect(CookieJar $cookieJar, Request $request) {
        if($request->referrer){
            $cookieJar->queue(cookie('referrer', $request->referrer, 45000));
         }
        
        $referrer = $request->input("referrer");
        $redir = $request->input("redir");
        
        return view('vsl.signup');
    }
}
