<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Response;
use App\User;
use App\IgProfile;
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
        $instagram_profiles = IgProfile::where('email', Auth::user()->email)->take(10)->get();
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
        $instagram_profiles = IgProfile::where('id', $id)
                ->get();
        $default_images = DefaultImageGallery::all();
        $default_categories = DB::connection('mysql_old')->select("SELECT id, category FROM insta_affiliate.default_image_category;");
        
        return view('postscheduling.scheduling', [
            'user_ig_profiles' => $instagram_profiles,
            'default_imgs' => $default_images,
            'default_img_category' => $default_categories,
        ]);
    }
}
