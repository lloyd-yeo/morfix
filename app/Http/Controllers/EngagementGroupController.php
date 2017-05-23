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
use App\InstagramProfileCommentLog;
use App\InstagramProfileFollowLog;
use App\InstagramProfileLikeLog;
use Unicodeveloper\Emoji\Emoji;
use Carbon\Carbon;

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
        $ig_profile = InstagramProfile::find($id);
        return view('engagement-group.profile', [
            'ig_profile' => $ig_profile,
        ]);
    }
}
