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
    			"competitors"	=> $this->getCompetitors()
    	]);
    }

    public function getCompetitors(){
    	$response = User::where('last_pay_out_date', '=', '2017-10-25 00:00:00')->where('tier', '>', '1')->where('pending_commission_payable','>','0')->orderBy('pending_commission_payable','DESC');
    	return $response;
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
			$secondsString = ($seconds >= 10) ? $seconds : '0'.$seconds;
			$minutesString = ($minutes >= 10) ? $minutes : '0'.$minutes;
			$hoursString = ($hours >= 10) ? $hours : '0'.$hours;
    	$timer = $days."D ".$hoursString.':'.$minutesString.':'.$secondsString;
    	return $timer;
    }


}
