<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\User;
class CompetitionController extends Controller
{
    public function show(){
    	return view('competition.index', [
    			"month"				=> "December",
    			"start_date"	=> 5,
    			"end_date"		=> 10,
    			"year"				=> 2017,
    			"competitors"	=> $this->getCompetitors()
    	]);
    }

    public function getCompetitors(){
    	$response = User::where('last_pay_out_date', '=', '2017-10-25 00:00:00')->where('tier', '>', '1')->where('pending_commission_payable','>','0')->orderBy('pending_commission_payable','DESC');
    	return json_encode($response);
    }
}
