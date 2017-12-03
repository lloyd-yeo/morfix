<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Carbon\Carbon;
use Cookie;
use App\User;
use App\InstagramProfile;
use App\UserUpdate;
use App\UserAffiliates;
use App\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CompetitionController extends Controller
{
	protected $startDate = NULL;
	protected $endDate = NULL;

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function show()
	{

		if (Auth::user()->is_competitor == 1 && Auth::user()->tier > 1) {
			$this->startDate = Carbon::create(2017, 12, 4, 0, 0, 0, 'America/Belize');
			$this->endDate   = Carbon::create(2017, 12, 17, 23, 59, 59, 'America/Belize');

			$competitors = $this->getCompetitors();

			$leaderboard_entries = $this->getNewProfilesByRankingLimit();
//			dump($leaderboard_entries);
			$ranking        = 1;
			$in_leaderboard = FALSE;

			foreach ($leaderboard_entries as $leaderboard_entry) {
				if ($leaderboard_entry['email'] == Auth::user()->email) {
					$in_leaderboard = TRUE;
					break;
				}
				$ranking++;
			}

			if (!$in_leaderboard) {
				$ranking = count($competitors);
			}

			$startDate = Carbon::create(2017, 12, 4, 0, 0, 0, 'America/Belize');
			$analysis  = $this->getAnalysis($startDate, $this->endDate);

			$total_referrals = $this->getTotalReferral();

			$daily_referrals = $this->getDailyReferral();

			return view('competition.index', [
				"month"                   => $this->startDate->format("F"),
				"startDate"               => $this->startDate->day,
				"endDate"                 => $this->endDate->day,
				"year"                    => $this->startDate->year,
				"competitors"             => $competitors,
				"ranking"                 => $ranking,
				"dailyReferral"           => $daily_referrals,
				"totalReferral"           => count($total_referrals),
				"allReferrals"            => $total_referrals,
				"analysis"                => $analysis['analysis'],
				"analysisLabel"           => $analysis['analysisLabel'],
				"competition_leaderboard" => $leaderboard_entries,
			]);
		} else {
			return redirect('home')->with('error', 'You are not eligible for the competition!');
		}
	}

	public function getCompetitors()
	{
		$response = User::where('tier', '>', '1')->where('is_competitor', 1)->get();

		return $response;
	}

	public function getRanking()
	{
		$currentUser = Auth::user();

		$ranking = NULL;
		/*
		  1. Get All new Users
		  2. Loop for new users and get referrals
		*/
		$myTotalReferral = $this->getTotalReferral();

		if ($myTotalReferral > 0) {
			$rankingCompetitionResult = $this->getNewProfilesByRanking('>=', $this->startDate);

			$rank = 0;

			foreach ($rankingCompetitionResult as $result) {

				$rank++;
				if ($result->referrer == $currentUser->user_id) {
					break;
				}
			}

			$ranking = $rank;
		} else {
			$ranking = "UNRANKED";
		}

		return $ranking;
	}

	public function getDailyReferral()
	{
		$referrer_id = Auth::user()->user_id;

		$start_date = Carbon::today()->setTime(0, 0, 0)->toDateTimeString();
		$end_date   = Carbon::today()->setTime(23, 59, 59)->toDateTimeString();

		$affiliates_today_count = 0;
		$affiliates_today       = DB::select("SELECT COUNT(referred_user.email) AS referrals
                  FROM user_affiliate ua, user referred_user
                  WHERE ua.referrer = $referrer_id
                  AND referred_user.user_id = ua.referred
                  AND DATE(referred_user.created_at) >= '$start_date'
                  AND DATE(referred_user.created_at) <= '$end_date'
                  AND referred_user.tier > 1;");
		foreach ($affiliates_today as $affiliates_row) {
			$affiliates_today_count = $affiliates_row->referrals;
		}

		return $affiliates_today_count;
	}

	public function getTotalReferral()
	{
		$referrer_id = Auth::user()->user_id;
		$start_date  = Carbon::today()->setTime(0, 0, 0)->toDateTimeString();
		//    $this->endDate = Carbon::today()->setTime(23, 59, 59)->toDateTimeString();
		$end_date = Carbon::create(2017, 12, 17, 23, 59, 59, 'America/Belize');

		$affiliates_total = DB::select("SELECT referred_user.*
                  FROM user_affiliate ua, user referred_user
                  WHERE ua.referrer = $referrer_id
                  AND referred_user.user_id = ua.referred
                  AND DATE(referred_user.created_at) >= '$start_date'
                  AND DATE(referred_user.created_at) <= '$end_date'
                  AND referred_user.tier > 1;");

		return $affiliates_total;
	}

	public function getReferral($newProfiles)
	{
		$referrals   = 0;
		$currentUser = Auth::user();
		foreach ($newProfiles as $newProfile) {
			$userAffiliate = UserAffiliates::where('referrer', '=', $currentUser->user_id)
			                               ->where('referred', '=', $newProfile->user_id)
			                               ->limit(1)
			                               ->get();
			if (sizeof($userAffiliate) >= 1) {
				$referrals++;
			}
		}

		return $referrals;
	}

	public function getNewProfilesByRanking($clause, $date)
	{
		$response = DB::select("SELECT u.name, ua.referrer, count(ua.referrer) as total
                            FROM user AS u
                            LEFT JOIN user_affiliate AS ua 
                            ON ua.referred = u.user_id
                            where date(u.created_at) >= '$date' AND u.tier > 1
                            GROUP BY ua.referrer, u.name
                            ORDER BY total DESC
                            ;");

		return $response;
	}

	public function getNewProfilesByRankingLimit()
	{

		$competitor_stats_array = [];
		$start_date             = $this->startDate;
		$end_date               = $this->endDate;

		foreach ($this->getCompetitors() as $competitor) {

			$referrer_id = $competitor->user_id;

			$response = DB::select("SELECT ua.referrer, COUNT(referred_user.email) AS referrals
                  FROM user_affiliate ua, user referred_user
                  WHERE ua.referrer = $referrer_id
                  AND referred_user.user_id = ua.referred
                  AND DATE(referred_user.created_at) >= '$start_date'
                  AND DATE(referred_user.created_at) <= '$end_date'
                  AND referred_user.tier > 1;");

			foreach ($response as $affiliate_referrals) {
//				if ($affiliate_referrals->referrals > 0) {
					$competitor_stats_array[] = [
						'email'     => $competitor->email,
						'name'      => $competitor->name,
						'referrals' => $affiliate_referrals->referrals,
					];
//				}
			}
		}

		$competitor_stats_collection = collect($competitor_stats_array)->sortByDesc('referrals')->values()->all();

		return $competitor_stats_collection;
	}

	public function getNewProfilesByDate($clause, $date)
	{
		return User::whereDate('created_at', $clause, $date)->where('tier', '>', '1')->get();
	}

	public function getAnalysis(Carbon $startDate, Carbon $endDate)
	{
		$referrer_id       = Auth::user()->user_id;
		$analysis_csv      = "";
		$analysis_date_csv = "";

		while ($startDate->lt($endDate)) {
			$start_date = $startDate;
			$end_date   = $startDate;
			$start_date = $start_date->setTime(0, 0, 0)->toDateTimeString();
			$end_date   = $end_date->setTime(23, 59, 59)->toDateTimeString();

			$response = DB::select("SELECT ua.referrer, COUNT(referred_user.email) AS referrals
                  FROM user_affiliate ua, user referred_user
                  WHERE ua.referrer = $referrer_id
                  AND referred_user.user_id = ua.referred
                  AND DATE(referred_user.created_at) >= '$start_date'
                  AND DATE(referred_user.created_at) <= '$end_date'
                  AND referred_user.tier > 1;");

			foreach ($response as $resp) {
				$analysis_csv            = $resp->referrals . "," . $analysis_csv;
				$analysis_date           = date_create($start_date);
				$analysis_date_formatted = date_format($analysis_date, "d M");

				if ($analysis_date_csv == "") {
					$analysis_date_csv = $analysis_date_formatted;
				} else {
					$analysis_date_csv = $analysis_date_csv . "," . $analysis_date_formatted;
				}

			}

			$startDate = $startDate->addDays(1);
		}

		$new_referral_analysis       = [];
		$new_referral_analysis_label = [];
		//    $new_referral_count          = [];
		$currentUser = Auth::user();

		//    $referrals = DB::select("SELECT date(u.created_at) as date, count(u.created_at) as total
		//                                FROM user_affiliate AS ua
		//                                LEFT JOIN user AS u
		//                                ON u.user_id = ua.referred
		//                                where date(u.created_at) between '$startDate' AND '$endDate' AND ua.referrer = '$currentUser->user_id'
		//                                GROUP BY date(u.created_at)
		//                                ORDER BY date(u.created_at) DESC
		//                                ");

		//    $analysis_csv      = "";
		//    $analysis_date_csv = "";
		//    $sum               = intval(0);
		//    foreach ($referrals as $analysis) {
		//      $sum                     += $analysis->total;
		//      $analysis_csv            = $sum . "," . $analysis_csv;
		//      $analysis_date           = date_create($analysis->date);
		//      $analysis_date_formatted = date_format($analysis_date, "d M");
		//      $analysis_date_csv       = $analysis_date_formatted . "," . $analysis_date_csv;
		//    }

		if ($analysis_csv != "") {
			$analysis_csv = substr($analysis_csv, 0, -1);
		}

		if ($analysis_date_csv != "") {
			$analysis_date_csv = substr($analysis_date_csv, 0, -1);
		}

		$new_referral_analysis       = $analysis_csv;
		$new_referral_analysis_label = $analysis_date_csv;

		//$new_referral_count= $new_referral_diff;
		return [
			"analysis"      => $new_referral_analysis,
			"analysisLabel" => $new_referral_analysis_label,
			//      "referralCount" => $new_referral_count,
		];
	}

	public function getInstagramProfiles()
	{
		$current_user = Auth::user();

		if (UserAffiliates::where('referred', $current_user->user_id)->count() == 0 && $current_user->last_login === NULL) {
			$referrer = Cookie::get('referrer');
			if ($referrer !== NULL && !($referrer == $current_user->user_id)) {
				$user_affiliate           = new UserAffiliates;
				$user_affiliate->referrer = $referrer;
				$user_affiliate->referred = $current_user->user_id;
				$user_affiliate->save();
			}
		}

		$current_user->last_login = \Carbon\Carbon::now();
		$current_user->save();
		$instagram_profiles = [];
		if (Auth::user()->partition === 0) {
			$instagram_profiles = InstagramProfile::where('email', Auth::user()->email)
			                                      ->take($current_user->num_acct)
			                                      ->get();
		} else {
			$connection_name = Helper::getConnection(Auth::user()->partition);

			$instagram_profiles = DB::connection($connection_name)->table('user_insta_profile')
			                        ->where('email', Auth::user()->email)
			                        ->take($current_user->num_acct)
			                        ->get();
		}

		return $instagram_profiles;
	}

	public function getTime()
	{
		$current = Carbon::now();
		$end  = Carbon::create(2017, 12, 17, 23, 59, 59, 'America/Belize');
		$time = $end->diffInSeconds($current);
		$seconds = $time % 60;
		$time    = ($time - $seconds) / 60;
		$minutes = $time % 60;
		$hours   = (($time - $minutes) / 60) % 24;
		$days    = intval((($time / 60) / 24));

		return $this->manageTime($days, $hours, $minutes, $seconds);
	}

	public function manageTime($days, $hours, $minutes, $seconds)
	{
		$time = NULL;
		if ($days > 2) {
			$time = $days . ' days left';
		} else if ($days <= 2 && $hours >= 1) {
			$time = $hours . " hours left";
		} else if ($hours < 1 && $minutes >= 1) {
			$time = $minutes . ' minutes left';
		} else if ($minutes < 1 && $seconds >= 1) {
			$time = $seconds . ' seconds left';
		} else {
			$time = 'Competition has ended';
		}

		return $time;
	}


}
