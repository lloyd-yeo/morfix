<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leaderboard_alltime = DB::table('users')
                     ->select(DB::raw('email, (SUM(pending_commission)+SUM(total_commission)) AS total_comms'))
                     ->groupBy('email')
                     ->orderBy('total_comms', 'desc')
                     ->take(10)
                     ->get();
        
        $leaderboard_weekly = User::orderBy('pending_commission', 'desc')->take(10)->get();
        
        return view('home', [
            'leaderboard_alltime' => $leaderboard_alltime,
            'leaderboard_weekly' => $leaderboard_weekly,
            ]);
    }
}
