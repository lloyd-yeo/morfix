<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DirectMessageTemplatesController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id) {
        
        $instagram_profiles = DB::table('morfix_instagram_profiles')
                ->where('email', Auth::user()->email)
                ->take(10)
                ->get();
        
        return view('dm', [
            'user_ig_profiles' => $instagram_profiles,
        ]);
    }
}
