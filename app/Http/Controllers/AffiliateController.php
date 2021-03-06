<?php

namespace App\Http\Controllers;

use App\BraintreeTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\YourlsUrl;
use App\StripeDetail;
use App\User;
use App\UserAffiliates;
use Response;

class AffiliateController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * @param Request $request
	 * @param null    $user_id
	 *
	 * @return View
	 */
	public function index(Request $request)
	{

		$user_id = Auth::user()->user_id;

		$referral_links = YourlsUrl::where('url', 'like', "%referrer=$user_id&redir=%")->get();

		$suffix = '0';

		if (count($referral_links) < 1) {
			//generate affiliate link
			$ref_kw          = strtolower($this->oldClean($this->getUsernameFromEmail(Auth::user()->email)));
			$original_ref_kw = $ref_kw;

			while (count(YourlsUrl::where('keyword', 'like', '%' . $ref_kw . '%')->get()) > 0) {
				$ref_kw = $original_ref_kw;
				$ref_kw = $ref_kw . "-" . $suffix;
				$suffix++;
			}

			$url_   = "https://app.morfix.co/vsl/signup?referrer=" . $user_id . "&redir=home";
			$title  = "MorfiX - Home";
			$ip     = "155.69.160.38";
			$clicks = 0;

			$url          = new YourlsUrl;
			$url->keyword = $ref_kw;
			$url->url     = $url_;
			$url->title   = $title;
			$url->ip      = $ip;
			$url->clicks  = $clicks;
			$url->save();

			$url_   = "https://app.morfix.co/vsl/signup?referrer=" . $user_id . "&redir=vsl";
			$title  = "MorfiX - VSL";
			$ip     = "155.69.160.38";
			$clicks = 0;

			$keyword      = $ref_kw . "-1";
			$url          = new YourlsUrl;
			$url->keyword = $keyword;
			$url->url     = $url_;
			$url->title   = $title;
			$url->ip      = $ip;
			$url->clicks  = $clicks;
			$url->save();

			$url_   = "https://app.morfix.co/vsl/signup?referrer=" . $user_id . "&redir=rcvsl";
			$title  = "MorfiX - Reverse Commission Funnel";
			$ip     = "155.69.160.38";
			$clicks = 0;

			$keyword      = $ref_kw . "rc";
			$url          = new YourlsUrl;
			$url->keyword = $keyword;
			$url->url     = $url_;
			$url->title   = $title;
			$url->ip      = $ip;
			$url->clicks  = $clicks;
			$url->save();

			$url_   = "https://app.morfix.co/vsl/signup?referrer=" . $user_id . "&redir=mcavsl";
			$title  = "MorfiX - Motor Club of America VSL";
			$ip     = "155.69.160.38";
			$clicks = 0;

			$keyword      = $ref_kw . "mca";
			$url          = new YourlsUrl;
			$url->keyword = $keyword;
			$url->url     = $url_;
			$url->title   = $title;
			$url->ip      = $ip;
			$url->clicks  = $clicks;
			$url->save();

//			$url_   = "https://app.morfix.co/vsl/signup?referrer=" . $user_id . "&redir=davsl";
//			$title  = "MorfiX - Digital Altitude VSL";
//			$ip     = "155.69.160.38";
//			$clicks = 0;
//
//			$keyword      = $ref_kw . "da";
//			$url          = new YourlsUrl;
//			$url->keyword = $keyword;
//			$url->url     = $url_;
//			$url->title   = $title;
//			$url->ip      = $ip;
//			$url->clicks  = $clicks;
//			$url->save();

			$url_   = "https://app.morfix.co/vsl/signup?referrer=" . $user_id . "&redir=ospvsl";
			$title  = "MorfiX - Online Sales Pro VSL";
			$ip     = "155.69.160.38";
			$clicks = 0;

			$keyword      = $ref_kw . "osp";
			$url          = new YourlsUrl;
			$url->keyword = $keyword;
			$url->url     = $url_;
			$url->title   = $title;
			$url->ip      = $ip;
			$url->clicks  = $clicks;
			$url->save();

			$url_   = "https://app.morfix.co/vsl/signup?referrer=" . $user_id . "&redir=mmovsl";
			$title  = "MorfiX - Make Money Online Niche VSL";
			$ip     = "155.69.160.38";
			$clicks = 0;

			$keyword      = $ref_kw . "mmo";
			$url          = new YourlsUrl;
			$url->keyword = $keyword;
			$url->url     = $url_;
			$url->title   = $title;
			$url->ip      = $ip;
			$url->clicks  = $clicks;
			$url->save();

			$url_   = "https://app.morfix.co/vsl/signup?referrer=" . $user_id . "&redir=mlmvsl";
			$title  = "MorfiX - MLM VSL";
			$ip     = "155.69.160.38";
			$clicks = 0;

			$keyword      = $ref_kw . "mlm";
			$url          = new YourlsUrl;
			$url->keyword = $keyword;
			$url->url     = $url_;
			$url->title   = $title;
			$url->ip      = $ip;
			$url->clicks  = $clicks;
			$url->save();

			$url_   = "https://app.morfix.co/vsl/signup?referrer=" . $user_id . "&redir=tool";
			$title  = "MorfiX - The #1 Instagram Growth Hacking Tool";
			$ip     = "155.69.160.38";
			$clicks = 0;

			$keyword      = $ref_kw . "tool";
			$url          = new YourlsUrl;
			$url->keyword = $keyword;
			$url->url     = $url_;
			$url->title   = $title;
			$url->ip      = $ip;
			$url->clicks  = $clicks;
			$url->save();

			$url_   = "https://app.morfix.co/vsl/signup?referrer=" . $user_id . "&redir=ebook";
			$title  = "MorfiX - Ebook VSL";
			$ip     = "155.69.160.38";
			$clicks = 0;

			$keyword      = $ref_kw . "ebook";
			$url          = new YourlsUrl;
			$url->keyword = $keyword;
			$url->url     = $url_;
			$url->title   = $title;
			$url->ip      = $ip;
			$url->clicks  = $clicks;
			$url->save();

			$url_   = "https://morfix.co/app/get-referral-cookie.php?referrer=" . $user_id . "&redir=payment";
			$title  = "MorfiX - Payment Page";
			$ip     = "155.69.160.38";
			$clicks = 0;

			$keyword      = $ref_kw . "payment";
			$url          = new YourlsUrl;
			$url->keyword = $keyword;
			$url->url     = $url_;
			$url->title   = $title;
			$url->ip      = $ip;
			$url->clicks  = $clicks;
			$url->save();

			$referral_links = YourlsUrl::where('url', 'like', "%referrer=$user_id&redir=%")->get();
		}

		$referrals_           = [];
		$free_trial_referrals = [];
		//        DB::enableQueryLog();
		$referrals = DB::table('user as u')
		               ->select('u.email', 'u.tier', 'u.created_at', 'u.paypal', 'u.braintree_id')
		               ->join('user_affiliate as ua', function ($join) {
			               $join->on('ua.referred', '=', 'u.user_id');
		               })
		               ->where('ua.referrer', '=', Auth::user()->user_id)
		               ->get();

		//        dd($referrals);
		//        dd(DB::getQueryLog());
		//        $referrals = DB::table('user')
		//                ->select('user.email', 'user.tier', 'user.created_at', 'user.paypal')
		//                ->join('user_affiliate', 'user.user_id', '=', 'user_affiliate.referred')
		//                ->where('user_affiliate.referrer', Auth::user()->user_id)
		//                ->where('user.tier', '>', 1)
		//                ->get();

		\Stripe\Stripe::setApiKey("sk_live_gnfRoHfQNhreT79YP9b4mIoB");

		foreach ($referrals as $referral) {

			if ($referral->tier == 1) {
				$free_trial_referrals[] = $referral;
				continue;
			}

			if ($referral->email == "maychengmt@yahoo.com" || $referral->email == "michaeltang90@hotmail.com" || $referral->email == "kingkew18@gmail.com") {
				continue;
				$referrals_[] = $referral;
			}

			if ($referral->tier > 1) {
				$active = FALSE;

				if ($referral->paypal == 0) {

					if ($referral->braintree_id != NULL) {
						$active = TRUE;
					}

					$stripe_details = StripeDetail::where('email', $referral->email)->get();

					foreach ($stripe_details as $stripe_detail) {
						$subscriptions = \Stripe\Subscription::all([ 'customer' => $stripe_detail->stripe_id ]);
						foreach ($subscriptions->data as $subscription) {
							if ($subscription->status == "trialing" || $subscription->status == "active") {
								$active = TRUE;
								break;
							}
						}
					}
				} else {
					$active = TRUE;
				}

				if ($active) {
					$referrals_[] = $referral;
				}
			}
		}

		$referrals = $referrals_;

		$invoices = DB::select('SELECT r.vip AS referrer_vip, r.tier AS referrer_user_tier, r.email AS referrer_email, c.charge_id, 
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
                                                        ORDER BY invoice_date DESC;', [ Auth::user()->email ]);

		$braintree_invoices = collect();
		foreach ($referrals as $referral) {
			if ($referral->braintree_id != NULL) {
				$ref_braintree_invoices = BraintreeTransaction::where('braintree_id', $referral->braintree_id)
				                                              ->where('status', 'settled')
				                                              ->where('type', 'sale')
				                                              ->get();

				foreach ($ref_braintree_invoices as $ref_braintree_invoice) {
					$refunded_invoice = BraintreeTransaction::where('sub_id', $ref_braintree_invoice->sub_id)
					                                        ->where('status', 'settled')
					                                        ->where('type', 'credit')
					                                        ->first();
					if ($refunded_invoice == NULL) {
						$braintree_invoices->push($ref_braintree_invoice);
					}
				}
			}
		}


		$qualified = [];
		\Stripe\Subscription::all([ 'limit' => 100, 'status' => 'all', 'customer' => Auth::user()->stripe_id ]);

		return view('affiliate.dashboard', [
			'referral_links'       => $referral_links,
			'referrals'            => $referrals,
			'free_trial_referrals' => $free_trial_referrals,
			'invoices'             => $invoices,
			'braintree_invoices'   => $braintree_invoices,
		]);
	}

	public function savePaypalEmail(Request $request, $id)
	{
		$response           = "We have encountered an error. Please try again later.";
		$user               = User::find($id);
		$user->paypal_email = $request->input('paypal_email');
		if ($user->save()) {
			$response = "Your paypal email has been saved.";

			return Response::json([ "success" => TRUE, 'message' => $response ]);
		} else {
			return Response::json([ "success" => FALSE, 'message' => $response ]);
		}
	}

	public function savePixel(Request $request)
	{
		$response = "We have encountered an error. Please try again later.";

		$pixel   = $request->input('pixel');
		$keyword = $request->input('keyword');

		$url        = YourlsUrl::where('keyword', $keyword)->first();
		$url->pixel = $pixel;

		if ($url->save()) {
			$response = "Your pixel code has been saved & submitted for review.";

			return Response::json([ "success" => TRUE, 'message' => $response ]);
		} else {
			return Response::json([ "success" => FALSE, 'message' => $response ]);
		}
	}

	public function getUsernameFromEmail($email)
	{
		$find     = '@';
		$pos      = strpos($email, $find);
		$username = substr($email, 0, $pos);

		return $username;
	}

	public function oldClean($string)
	{
		$string = str_replace(' ', '#', $string); // Replaces all spaces with hyphens.

		return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}

}
