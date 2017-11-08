<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Carbon\Carbon;
class CompetitionController extends Controller
{
    public function show(){
    	return view('competition.index', [
    			"month"				=> "December",
    			"start_date"	=> 5,
    			"end_date"		=> 10,
    			"year"				=> 2017
    	]);
    }
}
