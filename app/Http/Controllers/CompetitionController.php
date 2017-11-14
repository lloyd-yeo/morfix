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

    public function show(){
        $analysis = $this->getAnalysis();
        $igProfiles = $this->getInstagramProfiles();
    	return view('competition.index', [
    			"month"			=> "December",
    			"startDate"	=> 1,
    			"endDate"		=> 5,
    			"year"			=> 2017,
    			"competitors"	=> $this->getCompetitors(),
                "ranking"       => $this->getRanking(),
                "dailyReferral" => $this->getDailyReferral(),
                "totalReferral" => $this->getTotalReferral(),
                "analysis"      => $analysis['analysis'],
                "analysisLabel" => $analysis['analysisLabel'],
                "followerCount" => $analysis['followerCount'],
                "igProfiles"    => $igProfiles[0]
    	]);
    }

    public function getCompetitors(){
    	$response = User::where('last_pay_out_date', '=', '2017-10-25 00:00:00')
	                    ->where('tier', '>', '1')
	                    ->where('pending_commission_payable','>','0')
	                    ->orderBy('pending_commission_payable','DESC');
    	return $response;
    }

    public function getRanking(){
        $current_user = Auth::user();

        $leaderboard_alltime = DB::select("SELECT email, name, (SUM(pending_commission)+SUM(all_time_commission)) AS total_comms FROM user
                        GROUP BY email, name
                        ORDER BY total_comms DESC LIMIT 10;");

        $leaderboard_weekly = User::orderBy('pending_commission', 'desc')->take(10)->get();

        $leaderboard_alltime_ranking = "UNRANKED";

        $ranking = 1;
        foreach ($leaderboard_alltime as $leaderboard_rankers) {
            if ($leaderboard_rankers->email == Auth::user()->email) {
                $leaderboard_alltime_ranking = "#" . $ranking;
            }
            $ranking++;
        }

        return $ranking;
    }

    public function getDailyReferral(){
        $referrar = 0;
        return $referrar;
    }

    public function getTotalReferral(){
        $referrar = 0;
        return $referrar;
    }

    public function getAnalysis(){
        $new_profile_follower_analysis = array();
        $new_profile_follower_analysis_label = array();
        $new_follower_count = array();
        $instagram_profiles = $this->getInstagramProfiles();
        foreach ($instagram_profiles as $ig_profile) {

            $follower_analysis = DB::select("SELECT follower_count, date FROM insta_affiliate.user_insta_follower_analysis WHERE insta_username = ? ORDER BY date DESC LIMIT 10;",
                [ $ig_profile->insta_username ]);
            $analysis_csv = "";
            $analysis_date_csv = "";

            $new_follower = NULL;
            $new_follower_2 = NULL;
            $new_follower_diff = 0;

            foreach ($follower_analysis as $analysis) {

                if ($new_follower == NULL) {
                    $new_follower_diff = $new_follower;
                    $new_follower = $analysis->follower_count;
                } else {
                    if ($new_follower_2 == NULL) {
                        $new_follower_diff = $new_follower - $analysis->follower_count;
                        $new_follower_2 = $analysis->follower_count;
                    }
                }
                $analysis_csv = $analysis->follower_count . "," . $analysis_csv;
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

            $new_profile_follower_analysis[$ig_profile->insta_username] = $analysis_csv;
            $new_profile_follower_analysis_label[$ig_profile->insta_username] = $analysis_date_csv;
            $new_follower_count[$ig_profile->insta_username] = $new_follower_diff;
        }

        return array(
            "analysis"  => $new_profile_follower_analysis,
            "analysisLabel" => $new_profile_follower_analysis_label,
            "followerCount" => $new_follower_count
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
    	$end = Carbon::create(2017, 11, 15, 8,2,4, 'Asia/Singapore');
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
