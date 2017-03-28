<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Response;
use App\User;
use App\InstagramProfile;
use App\Niche;
use App\InstagramProfileComment;
use App\InstagramProfileTargetHashtag;
use App\InstagramProfileTargetUsername;
use App\DefaultImageGallery;

class PostSchedulingController extends Controller
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
        $instagram_profiles = DB::table('morfix_instagram_profiles')
                ->where('email', Auth::user()->email)
                ->take(10)
                ->get();

        return view('postscheduling', [
            'user_ig_profiles' => $instagram_profiles,
        ]);
    }
    
    /**
     * Display a gallery of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gallery($id) {
        $instagram_profiles = InstagramProfile::where('id', $id)
                ->get();
        $default_images = DefaultImageGallery::all();
        
        return view('interactionsettings', [
            'user_ig_profiles' => $instagram_profiles,
            'default_img' => $default_images,
        ]);
    }
}
