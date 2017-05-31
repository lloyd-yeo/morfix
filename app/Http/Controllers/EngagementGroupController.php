<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\User;
use App\Niche;
use App\EngagementGroupJob;
use App\InstagramProfile;
use App\InstagramProfileComment;
use App\InstagramProfileTargetHashtag;
use App\InstagramProfileTargetUsername;
use App\InstagramProfileCommentLog;
use App\InstagramProfileFollowLog;
use App\InstagramProfileLikeLog;
use App\InstagramProfileMedia;
use Unicodeveloper\Emoji\Emoji;
use Carbon\Carbon;

use Response;

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
        
//        $exit_code = Artisan::call('ig:refresh', [
//            'offset' => 0,
//            'limit' => 0,
//            'email' => Auth::user()->email,
//        ]);
        
        $instagram_profiles = InstagramProfile::where('email', Auth::user()->email)->take(11)->get();
        return view('engagement-group.index', [
            'user_ig_profiles' => $instagram_profiles,
        ]);
    }
    
    public function profile(Request $request, $id) {
        $ig_profile = InstagramProfile::find($id);
        $medias = InstagramProfileMedia::where('insta_username', $ig_profile->insta_username)->orderBy('created_at', 'desc')->get();
        return view('engagement-group.profile', [
            'ig_profile' => $ig_profile,
            'medias' => $medias,
        ]);
    }
    
    public function schedule(Request $request, $media_id) {
        $user = User::where('email', Auth::user()->email)->first();
        if ($user->engagement_quota > 0) {
            $engagement_group_job = new EngagementGroupJob;
            $engagement_group_job->media_id = $media_id;
            $engagement_group_job->engaged = 0;
            if ($engagement_group_job->save()) {
                DB::connection('mysql_old')->table('user')->decrement('engagement_quota', 1, ['email' => Auth::user()->email]);
            }
            return Response::json(array("success" => true, 'message' => "Your photo has been sent for engagement group. Expect a increase in engagement."));
        } else {
            return Response::json(array("success" => fail, 'message' => "You've ran out of engagement credits. Do try again tomorrow."));
        }
        
    }
}
