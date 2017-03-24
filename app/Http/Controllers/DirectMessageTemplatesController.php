<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\InstagramProfile;

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
        
        $instagram_profiles = InstagramProfile::where('id', $id)
                                ->get();
        
        return view('dm.template', [
            'user_ig_profiles' => $instagram_profiles,
        ]);
    }
}
