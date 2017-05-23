<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EngagementGroupController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $instagram_profiles = InstagramProfile::where('email', Auth::user()->email)->take(11)->get();
        return view('engagement-group.index', [
            'user_ig_profiles' => $instagram_profiles,
        ]);
    }
    
    public function profile(Request $request, $id) {
        
    }
}
