<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\InstagramProfile;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        echo Auth::user()->email;
        $leaderboard_alltime = DB::connection("mysql_old")
                ->select("SELECT email, name, (SUM(pending_commission)+SUM(all_time_commission)) AS total_comms FROM user
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

        $instagram_profiles = InstagramProfile::where('email', Auth::user()->email)->take(10)->get();
        
        $new_profile_follower_analysis = array();
        $new_profile_follower_analysis_label = array();
        $new_follower_count = array();

        foreach ($instagram_profiles as $ig_profile) {

//            $follower_analysis = DB::table('user_insta_follower_analysis')
//                    ->where('insta_username', $ig_profile->insta_username)
//                    ->orderBy('date', 'desc')
//                    ->take(10)
//                    ->get();
            
            $follower_analysis = DB::connection("mysql_old")->
                    select("SELECT follower_count, date FROM insta_affiliate.user_insta_follower_analysis WHERE insta_username = ? ORDER BY date DESC LIMIT 10;", [$ig_profile->insta_username]);
            $analysis_csv = "";
            $analysis_date_csv = "";

            $new_follower = NULL;
            $new_follower_2 = NULL;
            $new_follower_diff = 0;

            foreach ($follower_analysis as $analysis) {

                if ($new_follower == NULL) {
                    $new_follower_diff = $new_follower;
                    $new_follower = $analysis->follower_count;
                } else if ($new_follower_2 == NULL) {
                    $new_follower_diff = $new_follower - $analysis->follower_count;
                    $new_follower_2 = $analysis->follower_count;
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
        
        $user_updates = DB::connection('mysql_old')->select("SELECT `user_updates`.`id`,
                            `user_updates`.`email`,
                            `user_updates`.`title`,
                            `user_updates`.`content`,
                            `user_updates`.`type`,
                            `user_updates`.`created_at`
                        FROM `insta_affiliate`.`user_updates` WHERE `user_updates`.`email` = ? ORDER BY id DESC;", [Auth::user()->email]);
        
        $remaining_quota = DB::connection('mysql_old')->select("SELECT COUNT(email) AS email_count FROM user_insta_profile WHERE email = \"" . Auth::user()->email . "\";");
        
        $user = User::where('email', Auth::user()->email)->first();
        
        $remaining_quota = $user->num_acct - $remaining_quota[0]->email_count;
        
        return view('home', [
            'leaderboard_alltime' => $leaderboard_alltime,
            'leaderboard_weekly' => $leaderboard_weekly,
            'user_leaderboard_alltime_ranking' => $leaderboard_alltime_ranking,
            'user_ig_profiles' => $instagram_profiles,
            'user_ig_analysis' => $new_profile_follower_analysis,
            'user_ig_analysis_label' => $new_profile_follower_analysis_label,
            'user_ig_new_follower' => $new_follower_count,
            'user_updates' => $user_updates,
            'remaining_quota' => $remaining_quota,
        ]);
    }

}
