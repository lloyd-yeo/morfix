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
use App\InstagramHelper;

class EngagementGroupController extends Controller {

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
//                    'email' => Auth::user()->email,
//        ]);

        $instagram_profiles = InstagramProfile::where('email', Auth::user()->email)
                ->take(Auth::user()->num_acct)
                ->get();
        
        $instagram = InstagramHelper::initInstagram();
        
        foreach ($instagram_profiles as $ig_profile) {
            if (InstagramHelper::login($instagram, $ig_profile, 0)) {
                $items = $instagram->timeline->getSelfUserFeed()->items;
                foreach ($items as $item) {
                    try {
                        $image_url = "";
                        if (is_null($item->image_versions2)) {
                            //is carousel media
                            $image_url = $item->carousel_media[0]->image_versions2->candidates[0]->url;
                        } else {
                            $image_url = $item->image_versions2->candidates[0]->url;
                        }
                        try {
                            $new_profile_post = new InstagramProfileMedia;
                            $new_profile_post->insta_username = $ig_profile->insta_username;
                            $new_profile_post->media_id = $item->pk;
                            $new_profile_post->image_url = $image_url;
                            $new_profile_post->code = $item->code;
                            $new_profile_post->created_at = \Carbon\Carbon::createFromTimestamp($item->taken_at);
                            $new_profile_post->save();
                        } catch (\Exception $ex) {
//                        echo $ex->getMessage();
                        }
                    } catch (\ErrorException $e) {
                        $this->profile->error_msg = $e->getMessage();
                        $this->profile->save();
                    }
                }
            }
        }

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
            $engagement_group_job = EngagementGroupJob::where('media_id', '=', $media_id)->first();
            if ($engagement_group_job === null) {
                $engagement_group_job = new EngagementGroupJob;
                $engagement_group_job->media_id = $media_id;
                $engagement_group_job->engaged = 0;
                if ($engagement_group_job->save()) {
                    $user->engagement_quota = $user->engagement_quota - 1;
                    $user->save();
                    $job = new \App\Jobs\EngagementGroup($media_id, $request->input('profile_id'));
                    $job->onQueue('engagementgroup');
                    dispatch($job);
                }
                return Response::json(array("success" => true, 'message' => "Your photo has been sent for engagement group. Expect a increase in engagement."));
            } else {
                $engagement_group_job->engaged = 0;
                $engagement_group_job->save();
                return Response::json(array("success" => false, 'message' => "Your image has already been sent for engagement before."));
            }
        } else {
            return Response::json(array("success" => false, 'message' => "You've ran out of engagement credits. Do try again tomorrow."));
        }
    }

}
