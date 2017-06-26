<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\YourlsUrl;
use App\StripeDetail;
use App\User;
use Response;

class AffiliateController extends Controller {
    
    public function __construct() {
        $this->middleware('auth');
    }
    
    /**
     * @param Request $request
     * @param null $user_id
     * @return View
     */
    public function index(Request $request) {

        $user_id = Auth::user()->user_id;

        $referral_links = YourlsUrl::where('url', 'like', "%referrer=$user_id&redir=%")->get();
        
        $referrals_ = array();
        $referrals = DB::connection('mysql_old')->table('user')
                ->join('user_affiliate', 'user.user_id', '=', 'user_affiliate.referred')
                ->where('user_affiliate.referrer', Auth::user()->user_id)
                ->where('user.user_tier', '>', 1)
                ->select('user.email', 'user.user_tier', 'user.created_at')
                ->get();

        \Stripe\Stripe::setApiKey("sk_live_HeS5nnfJ5qARMPsANoGw32c2");
        
        foreach ($referrals as $referral) {
            if ($referral->email == "maychengmt@yahoo.com" 
                    || $referral->email == "michaeltang90@hotmail.com" 
                    || $referral->email == "kingkew18@gmail.com") {
                continue; 
                $referrals_[] = $referral;
            }

            $active = false;

            $stripe_details = StripeDetail::where('email', $referral->email)->get();
            
            foreach ($stripe_details as $stripe_detail) {
                $subscriptions = \Stripe\Subscription::all(array('customer' => $stripe_detail->stripe_id));
                foreach ($subscriptions->data as $subscription) {
                    if ($subscription->status == "trialing" || $subscription->status == "active") {
                        $active = true;
                        break;
                    }
                }
            }
            
            if ($active) {
                $referrals_[] = $referral;
            }
        }
        
        $referrals = $referrals_;
        
        #$referral_links = DB::connection('mysql_old')->select('SELECT * FROM yourls_url WHERE url LIKE "%referrer=' . $user_id . '&%";');
        #$referrals = DB::connection('mysql_old')->select('SELECT u.email, u.user_tier, u.created_at, a.refunded_premium, a.refunded_pro, a.refunded_business, a.refunded_mastermind 
        #    FROM user_affiliate a, user u WHERE a.referrer = ? AND a.referred = u.user_id AND u.user_tier > 1 ORDER BY u.created_at DESC;', [Auth::user()->user_id]);

        $invoices = DB::connection('mysql')->select('SELECT r.vip AS referrer_vip, r.user_tier AS referrer_user_tier, r.email AS referrer_email, c.charge_id, 
                                                                i.invoice_id, i.subscription_id, i.start_date, i.expiry_date, u.email AS referred_email,
                                                                c.paid AS charge_paid, i.paid AS invoice_paid, c.refunded AS charge_refunded, c.charge_created AS charge_created
                                                        FROM user u, user_stripe_details sd, user_stripe_invoice i, user_stripe_charges c, 
                                                        user r, user_affiliate ua
                                                        WHERE u.email = sd.email
                                                        AND i.stripe_id = sd.stripe_id
                                                        AND i.charge_id = c.charge_id
                                                        AND ua.referred = u.user_id
                                                        AND ua.referrer = r.user_id
                                                        AND r.email = ?
                                                        ORDER BY invoice_date DESC;', [Auth::user()->email]);
        
        return view('affiliate.dashboard', [
            'referral_links' => $referral_links,
            'referrals' => $referrals,
            'invoices' => $invoices,
        ]);
    }
    
    public function savePaypalEmail(Request $request, $id) {
        $response = "We have encountered an error. Please try again later.";
        $user = User::find($id);
        $user->paypal_email = $request->input('paypal_email');
        if ($user->save()) {
            $response = "Your paypal email has been saved.";
            return Response::json(array("success" => true, 'message' => $response));
        } else {
            return Response::json(array("success" => false, 'message' => $response));
        }
    }
    
    public function savePixel(Request $request) {
        $response = "We have encountered an error. Please try again later.";
        
        $pixel = $request->input('pixel');
        $keyword = $request->input('keyword');
        
        $url = YourlsUrl::where('keyword', $keyword)->first();
        $url->pixel = $pixel;
        
        if ($url->save()) {
            $response = "Your pixel code has been saved & submitted for review.";
            return Response::json(array("success" => true, 'message' => $response));
        } else {
            return Response::json(array("success" => false, 'message' => $response));
        }
    }
    
}
