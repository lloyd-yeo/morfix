<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Cookie\CookieJar;
use Cookie;
use Response;
use App\User;
use App\ReferrerIp;

class ReferrerController extends Controller {

    public function redirect(CookieJar $cookieJar, Request $request) {

        $referrer_ip = new ReferrerIp;

        if ($request->referrer) {
            Cookie::forget('morfix_referrer');

            $cookieJar->queue(cookie()->forever('morfix_referrer', $request->referrer));
            if (ReferrerIp::where('ip', $request->ip())->first() === NULL) { //doesn't exists
                $referrer_ip->referrer = $request->referrer;
                $referrer_ip->ip = $request->ip();
                $referrer_ip->save();
            } else { //update the referrer
                $referrer_ip = ReferrerIp::where('ip', '=', $request->ip())->first();
                $referrer_ip->referrer = $request->referrer;
                $referrer_ip->save();
            }
        }

        $redir = $request->input("redir");

        switch ($redir) {
	        case "payment":
		        return redirect('premium');
		        break;
	        case "home":
		        return redirect('https://morfix.co');
		        break;
	        case "vsl":
		        return redirect('online');
	        	break;
	        case "davsl":
		        return redirect('digital-altitude');
	        	break;
	        case "ospvsl":
		        return redirect('online-sales-pro');
	        	break;
	        case "mmovsl":
		        return redirect('make-money-online');
	        	break;
	        case "tool":
		        return redirect('tool');
	        	break;
	        case "ebook":
		        return redirect('ebook');
	        	break;
	        case "mlmvsl":
		        return redirect('mlm');
	        	break;
	        case "online":
		        return redirect('online');
	        	break;
	        default:
		        return redirect('https://morfix.co');
	        	break;
        }

//        if ($redir == "payment") {
//            return redirect('https://upgrade.morfix.co/premium');
//        } elseif ($redir == "home") {
//            return redirect('https://morfix.co');
//        } elseif ($redir == "vsl") {
//            return redirect('https://signup.morfix.co/vsl-online');
//        } elseif ($redir == "rcvsl") {
//            return redirect('https://signup.morfix.co/vsl-rc');
//        } elseif ($redir == "mcavsl") {
//            return redirect('https://signup.morfix.co/mca-vsl');
//        } elseif ($redir == "davsl") {
//            return redirect('https://signup.morfix.co/vsl-da');
//        } elseif ($redir == "ospvsl") {
//            return redirect('https://signup.morfix.co/vsl-osp');
//        } elseif ($redir == "mmovsl") {
//            return redirect('https://signup.morfix.co/vsl-mmo');
//        } elseif ($redir == "tool") {
//            return redirect('https://signup.morfix.co/vsl-tool');
//        } elseif ($redir == "mlmvsl") {
//            return redirect('https://signup.morfix.co/vsl-mlm');
//        } elseif ($redir == "ebook") {
////            return redirect('https://signup.morfix.co/ebookmmo');
//            return redirect('/ebook');
//        } elseif ($redir == "online") {
//            return redirect('https://signup.morfix.co/vsl-online');
//        } else {
//            return redirect('https://morfix.co');
//        }
    }

}
