<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Response;
use App\User;
use App\InstagramProfile;
use App\Niche;
use App\InstagramProfileComment;
use App\InstagramProfileTargetHashtag;
use App\InstagramProfileTargetUsername;

class AffiliateController extends Controller
{
    /**
     * @param Request $request
     * @param null $user_id
     * @return View
     */
    public function index(Request $request) {
        
        $active_users = DB::connection('mysql_old')->select('SELECT * 
                                FROM insta_affiliate.get_referral_charges_of_user 
                                WHERE referrer_email = ?
                                AND charge_paid = 1
                                AND invoice_paid = 1
                                AND charge_refunded = 0
                                AND NOW() >= start_date
                                AND NOW() <= expiry_date;', [Auth::user()->email]);
        $user_id = Auth::user()->user_id;
        $referral_links = DB::connection('mysql_old')->select('SELECT * FROM yourls_url WHERE url LIKE "%referrer=' . $user_id . '&%";');
        $referrals = DB::connection('mysql_old')->select('SELECT u.email, u.user_tier, a.refunded_premium, a.refunded_pro, a.refunded_business, a.refunded_mastermind FROM insta_affiliate.user_affiliate a, insta_affiliate.user u "
                                    . "WHERE a.referrer = ? AND a.referred = u.user_id;', [Auth::user()->user_id]);
        return view('affiliate.dashboard', [
            'active_users' => $active_users,
            'referral_links' => $referral_links,
            'referrals' => $referrals,
        ]);
    }
}
