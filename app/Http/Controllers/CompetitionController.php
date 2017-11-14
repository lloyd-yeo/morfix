<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\User;
class CompetitionController extends Controller
{

    public function show(){
    	return view('competition.index', [
    			"month"			=> "December",
    			"startDate"	=> 1,
    			"endDate"		=> 5,
    			"year"			=> 2017,
    			"competitors"	=> $this->getCompetitors(),
                "ranking"       => $this->getRanking()
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
