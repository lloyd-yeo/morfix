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

    protected $startDate = null;
    protected $endDate = null;

    public function show(){

        $this->startDate = Carbon::create(2017, 11, 15, 8, 2, 4, 'Asia/Singapore');
        $this->endDate = Carbon::create(2017, 11, 30, 8, 2, 4, 'Asia/Singapore');

        $analysis = $this->getAnalysis($this->startDate, $this->endDate);

        $igProfiles = $this->getInstagramProfiles();

    	return view('competition.index', [
    			"month"			=> "December",
    			"startDate"	=> 4,
    			"endDate"		=> 10,
    			"year"			=> 2017,
    			"competitors"	=> $this->getCompetitors(),
                "ranking"       => $this->getRanking(),
                "dailyReferral" => $this->getDailyReferral(),
                "totalReferral" => $this->getTotalReferral(),
                "analysis"      => $analysis['analysis'],
                "analysisLabel" => $analysis['analysisLabel'],
                "referralCount" => $analysis['referralCount'],
                "igProfiles"    => $igProfiles[0],
                "competition_leaderboard" => $this->getNewProfilesByRankingLimit()
    	]);
    }

    public function getCompetitors(){
    	$response = User::where('last_pay_out_date', '=', '2017-10-25 00:00:00')
	                    ->where('tier', '>', '1')
	                    ->where('pending_commission_payable','>','0')
	                    ->orderBy('pending_commission_payable','DESC')->get();
    	return $response;
    }

    public function getRanking(){
        $currentUser = Auth::user();
        $ranking = NULL;
        /*
            1. Get All new Users
            2. Loop for new users and get referrals
        */
        $myTotalReferral = $this->getTotalReferral();

        if($myTotalReferral > 0){
            $rankingCompetitionResult = $this->getNewProfilesByRanking('>=', $this->startDate);

            $rank = 0;

            foreach ($rankingCompetitionResult as $result) {
//                echo json_encode($result);
                $rank++;
                if($result->referrer == $currentUser->user_id){
                    break;
                }
            }

            $ranking = $rank;
//            if($rank > 10){
//                $ranking = "UNRANKED";
//            }
//            else{
//                $ranking = $rank;
//            }
        }else{
            $ranking = "UNRANKED";
        }
        return $ranking;
    }

    public function getDailyReferral(){
//        $newProfiles = $this->getNewProfilesByDate('>=', Carbon::today());
//        return $this->getReferral($newProfiles);
        $newProfiles = $this->getNewProfilesByDate('>=', Carbon::today());
        return $this->getReferral($newProfiles);
    }

    public function getTotalReferral(){
        $newProfiles = $this->getNewProfilesByDate('>=', $this->startDate);
        return $this->getReferral($newProfiles);
    }

    public function getReferral($newProfiles){
        $referrals = 0;
        $currentUser = Auth::user();
        foreach ($newProfiles as $newProfile) {
            $userAffiliate = UserAffiliates::where('referrer', '=', $currentUser->user_id)
                            ->where('referred', '=', $newProfile->user_id)
                            ->limit(1)
                            ->get();
            if(sizeof($userAffiliate) >= 1){
                $referrals++;
            }
        }
        return $referrals;
    }

    public function getNewProfilesByRanking($clause, $date){
        $response =  DB::select("SELECT u.name, ua.referrer, count(ua.referrer) as total
                            FROM user AS u
                            LEFT JOIN user_affiliate AS ua 
                            ON ua.referred = u.user_id
                            where date(u.created_at) >= '$date' AND u.tier > 1
                            GROUP BY ua.referrer, u.name
                            ORDER BY total DESC
                            ;");
        return $response;
    }

    public function getNewProfilesByRankingLimit(){

	    $competitor_stats_array = array();
		$start_date = $this->startDate;
		$end_date = $this->endDate;

    	foreach ($this->getCompetitors() as $competitor) {

			$referrer_id = $competitor->user_id;
			$response = DB::select("SELECT ua.referrer, COUNT(referred_user.email) AS referrals
									FROM user_affiliate ua, user referred_user, user referrer
									WHERE ua.referrer = $referrer_id
									AND referred_user.user_id = ua.referred
									AND DATE(referred_user.created_at) >= '$start_date'
									AND DATE(referred_user.created_at) <= '$end_date';");

			foreach ($response as $affiliate_referrals) {
				$competitor_stats_array[] = array(
					'name' => $competitor->name,
					'referrals' => $affiliate_referrals->referrals,
				);
			}
	    }
		dump($competitor_stats_array);
	    $competitor_stats_collection = collect($competitor_stats_array)->sortByDesc('referrals');
		dump($competitor_stats_collection);
        return $competitor_stats_collection;
    }

    public function getNewProfilesByDate($clause, $date){
        return User::whereDate('created_at', $clause, $date)->where('tier', '>', '1')->get();
    }

    public function getAnalysis($startDate, $endDate){
        $new_referral_analysis = array();
        $new_referral_analysis_label = array();
        $new_referral_count = array();
        $currentUser = Auth::user();
        $referrals = DB::select("SELECT date(u.created_at) as date, count(u.created_at) as total
                                FROM user_affiliate AS ua
                                LEFT JOIN user AS u 
                                ON u.user_id = ua.referred
                                where date(u.created_at) between '$startDate' AND '$endDate' AND ua.referrer = '$currentUser->user_id'
                                GROUP BY date(u.created_at)
                                ORDER BY date(u.created_at) DESC
                                ");

        $analysis_csv = "";
        $analysis_date_csv = "";
        $sum = intval(0);
        foreach ($referrals as $analysis) {
            $sum += $analysis->total;
            $analysis_csv = $sum. "," . $analysis_csv;
            $analysis_date = date_create($analysis->date);
            $analysis_date_formatted = date_format($analysis_date, "d M");
            $analysis_date_csv = $analysis_date_formatted . "," . $analysis_date_csv;
        }

        if ($analysis_csv != "") {
            $analysis_csv = substr($analysis_csv, 0, -1);
        }

        if ($analysis_date_csv != "") {
            $analysis_date_csv = substr($analysis_date_csv, 0, -1);
        }

        $new_referral_analysis = $analysis_csv;
        $new_referral_analysis_label = $analysis_date_csv;
        //$new_referral_count= $new_referral_diff;
        return array(
            "analysis"  => $new_referral_analysis,
            "analysisLabel" => $new_referral_analysis_label,
            "referralCount" => $new_referral_count
        );
    }

    public function getInstagramProfiles(){
        $current_user = Auth::user();

        if (UserAffiliates::where('referred', $current_user->user_id)->count() == 0 && $current_user->last_login === NULL) {
            $referrer = Cookie::get('referrer');
            if ($referrer !== NULL && !($referrer == $current_user->user_id)) {
                $user_affiliate = new UserAffiliates;
                $user_affiliate->referrer = $referrer;
                $user_affiliate->referred = $current_user->user_id;
                $user_affiliate->save();
            }
        }

        $current_user->last_login = \Carbon\Carbon::now();
        $current_user->save();
        $instagram_profiles = array();
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

    public function getTime(){
    	$current = Carbon::now();
    	$end = Carbon::create(2017, 11, 20, 8,2,4, 'Asia/Singapore');
    	$time = $end->diffInSeconds($current);
    	$seconds = $time % 60;
		$time = ($time - $seconds) / 60;
		$minutes = $time % 60;
		$hours = (($time - $minutes) / 60) % 24;
		$days = intval((($time  / 60) / 24));
		// $secondsString = ($seconds >= 10) ? $seconds : '0'.$seconds;
		// $minutesString = ($minutes >= 10) ? $minutes : '0'.$minutes;
		// $hoursString = ($hours >= 10) ? $hours : '0'.$hours;
  //   	$timer = $days."D ".$hoursString.':'.$minutesString.':'.$secondsString;
    	return $this->manageTime($days, $hours, $minutes, $seconds);
    }

    public function manageTime($days, $hours,$minutes, $seconds){
        $time = null;
            if($days > 2){
                $time = $days. ' days left';
            }
            else if($days <=2 && $hours >= 1){
                $time = $hours. " hours left";
            }
            else if($hours < 1 && $minutes >= 1){
                $time = $minutes. ' minutes left';
            }
            else if($minutes < 1 && $seconds >= 1){
                $time = $seconds. ' seconds left';
            }
            else{
                $time = 'Competition has ended';
            }
        return $time;
    }




}
