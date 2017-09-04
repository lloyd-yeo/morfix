<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Cookie\CookieJar;
use Response;
use App\User;
use App\ReferrerIp;

class ReferrerController extends Controller {

    public function redirect(CookieJar $cookieJar, Request $request) {
        
        $referrer_ip = new ReferrerIp;
        
        if ($request->referrer) {
            \Cookie::forget('referrer');
            $cookieJar->queue(cookie()->forever('referrer', $request->referrer));
            $referrer_ip->referrer = $request->referrer;
            $referrer_ip->ip = $request->ip();
            $referrer_ip->save();
        }
        
        $redir = $request->input("redir");
        
        
        
        
        
//        if ($redir == "payment") {
//            return view('vsl.payment', [
//            ]);
//        }
        
        if ($redir == "payment") {
            return redirect('https://upgrade.morfix.co/premium');
        } elseif ($redir == "home") {
            return redirect('https://morfix.co');
        } elseif ($redir == "vsl") {
            return redirect('https://signup.morfix.co/vsl-online');
        } elseif ($redir == "rcvsl") {
            return redirect('https://signup.morfix.co/vsl-rc');
        } elseif ($redir == "mcavsl") {
            return redirect('https://signup.morfix.co/mca-vsl');
        } elseif ($redir == "davsl") {
            return redirect('https://signup.morfix.co/vsl-da');
        } elseif ($redir == "ospvsl") {
            return redirect('https://signup.morfix.co/vsl-osp');
        } elseif ($redir == "mmovsl") {
            return redirect('https://signup.morfix.co/vsl-mmo');
        } elseif ($redir == "tool") {
            return redirect('https://signup.morfix.co/vsl-tool');
        } elseif ($redir == "mlmvsl") {
            return redirect('https://signup.morfix.co/vsl-mlm');
        } else {
            return redirect('https://morfix.co');
        }
    }
}
