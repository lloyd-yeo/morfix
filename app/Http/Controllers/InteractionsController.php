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

class InteractionsController extends Controller {

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
        return view('interactions', [
            'user_ig_profiles' => $instagram_profiles,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $ig_profile = IgProfile::where('id', $id)->first();
//        $instagram_profiles = InstagramProfile::where('id', $id)
//                ->get();
        $niches = Niche::all();
        $comments = \App\InstagramProfileComment::where("insta_username", $ig_profile->insta_username)->get();
        $target_usernames = \App\InstagramProfileTargetUsername::where("insta_username", $ig_profile->insta_username)->get();
        $target_hashtags = \App\InstagramProfileTargetHashtag::where("insta_username", $ig_profile->insta_username)->get();
        return view('interactionsettings', [
            'ig_profile' => $ig_profile,
            'user_ig_comments' => $comments,
            'user_ig_target_usernames' => $target_usernames,
            'user_ig_target_hashtags' => $target_hashtags,
            'niches' => $niches,
        ]);
    }

    public function toggleLike($id) {
        $instagram_profile = IgProfile::where('id', $id)->first();
        $instagram_profile->auto_like = ($instagram_profile->auto_like + 1) % 2;
        $response = "There has been an error with the server. Please contact live support.";
        if ($instagram_profile->save()) {
            if ($instagram_profile->auto_like == 1) {
                $response = "Your auto like function has been turned <b>on</b>.";
            } else {
                $response = "Your auto like function has been turned <b>off</b>.";
            }
            return Response::json(array("success" => true, 'message' => $response));
        } else {
            return Response::json(array("success" => false, 'message' => $response));
        }
    }

    public function toggleComment($id) {
        $instagram_profile = IgProfile::where('id', $id)->first();
        $instagram_profile->auto_comment = ($instagram_profile->auto_comment + 1) % 2;
        $response = "There has been an error with the server. Please contact live support.";
        if ($instagram_profile->save()) {
            if ($instagram_profile->auto_comment == 1) {
                $response = "Your auto comment function has been turned <b>on</b>.";
            } else {
                $response = "Your auto comment function has been turned <b>off</b>.";
            }
            return Response::json(array("success" => true, 'message' => $response));
        } else {
            return Response::json(array("success" => false, 'message' => $response));
        }
    }

    public function toggleFollow($id) {
        $instagram_profile = IgProfile::where('id', $id)->first();
        $instagram_profile->auto_follow = ($instagram_profile->auto_follow + 1) % 2;
        $response = "There has been an error with the server. Please contact live support.";
        if ($instagram_profile->save()) {
            if ($instagram_profile->auto_follow == 1) {
                $response = "Your auto follow function has been turned <b>on</b>.";
            } else {
                $response = "Your auto follow function has been turned <b>off</b>.";
            }
            return Response::json(array("success" => true, 'message' => $response));
        } else {
            return Response::json(array("success" => false, 'message' => $response));
        }
    }

    public function toggleUnfollow($id) {
        $instagram_profile = IgProfile::where('id', $id)->first();
        $instagram_profile->auto_unfollow = ($instagram_profile->auto_unfollow + 1) % 2;
        $response = "There has been an error with the server. Please contact live support.";
        if ($instagram_profile->save()) {
            if ($instagram_profile->auto_unfollow == 1) {
                $response = "Your auto unfollow function has been turned <b>on</b>.";
            } else {
                $response = "Your auto unfollow function has been turned <b>off</b>.";
            }
            return Response::json(array("success" => true, 'message' => $response));
        } else {
            return Response::json(array("success" => false, 'message' => $response));
        }
    }

    public function toggleNiche(Request $request, $id) {
        $instagram_profile = IgProfile::where('id', $id)->first();
        $niche = $request->input('niche');
        $instagram_profile->niche = $niche;
        $response = "There has been an error with the server. Please contact live support.";
        if ($instagram_profile->save()) {
            if ($instagram_profile->auto_unfollow == 1) {
                $response = "Your auto unfollow function has been turned <b>on</b>.";
            } else {
                $response = "Your auto unfollow function has been turned <b>off</b>.";
            }
            return Response::json(array("success" => true, 'message' => $response));
        } else {
            return Response::json(array("success" => false, 'message' => $response));
        }
    }

    public function saveAdvancedFollowSettings(Request $request, $id) {
        $instagram_profile = IgProfile::where('id', $id)->first();

        $unfollow_users_not_following_flag = 0;
        if ($request->has('unfollow-toggle')) {
            $unfollow_users_not_following_flag = 1;
        }
        $minimum_follower_filter = $request->input('min-follower-filter');
        $maximum_follower_filter = $request->input('max-follower-filter');
        $follow_speed = $request->input('follow-speed');
        $follow_cycle = $request->input('follow-cycle');

        $instagram_profile->unfollow_unfollowed = $unfollow_users_not_following_flag;
        $instagram_profile->follow_min_followers = $minimum_follower_filter;
        $instagram_profile->follow_max_followers = $maximum_follower_filter;
        $instagram_profile->speed = $follow_speed;
        $instagram_profile->follow_cycle = $follow_cycle;
        $response = "There has been an error with the server. Please contact live support.";
        if ($instagram_profile->save()) {
            $response = "Your settings have been saved!";
            return Response::json(array("success" => true, 'response' => $response));
        } else {
            return Response::json(array("success" => false, 'response' => $response));
        }
    }

    public function saveComment(Request $request, $id) {
        $instagram_comment = \App\InstagramProfileComment::where("comment", $request->input('comment'))->where("ig_profile_id", $id)->first();
        $response = "There has been an error with the server. Please contact live support.";
        if (is_null($instagram_comment)) {
            $new_ig_comment = new InstagramProfileComment;
            $new_ig_comment->comment = $request->input('comment');
            $new_ig_comment->ig_profile_id = $id;
        } else {
            $response = "This comment exists alreay!";
            return Response::json(array("success" => false, 'response' => $response));
        }

        if ($new_ig_comment->save()) {
            $response = "Successfully added comment!";
            return Response::json(array("success" => true, 'response' => $response, 'id' => $new_ig_comment->id));
        } else {
            return Response::json(array("success" => false, 'response' => $response));
        }
    }

    public function saveUsername(Request $request, $id) {
        $instagram_profile_target_username = \App\InstagramProfileTargetUsername::where("target_username", $request->input('target_username'))->where("insta_id", $id)->first();
        $response = "There has been an error with the server. Please contact live support.";
        if (is_null($instagram_profile_target_username)) {
            $new_instagram_profile_target_username = new InstagramProfileTargetUsername;
            $new_instagram_profile_target_username->target_username = $request->input('target_username');
            $new_instagram_profile_target_username->ig_profile_id = $id;
        } else {
            $response = "This target username exists alreay!";
            return Response::json(array("success" => false, 'response' => $response));
        }

        if ($new_instagram_profile_target_username->save()) {
            $response = "Successfully added username!";
            return Response::json(array("success" => true, 'response' => $response, 'id' => $new_instagram_profile_target_username->id));
        } else {
            return Response::json(array("success" => false, 'response' => $response));
        }
    }

    public function saveHashtag(Request $request, $id) {
        $instagram_profile_target_hashtag = \App\InstagramProfileTargetHashtag::where("target_hashtag", $request->input('target_hashtag'))->where("insta_id", $id)->first();
        $response = "There has been an error with the server. Please contact live support.";
        if (is_null($instagram_profile_target_hashtag)) {
            $instagram_profile_target_hashtag = new InstagramProfileTargetHashtag;
            $instagram_profile_target_hashtag->target_hashtag = $request->input('target_hashtag');
            $instagram_profile_target_hashtag->ig_profile_id = $id;
        } else {
            $response = "This target hashtag exists alreay!";
            return Response::json(array("success" => false, 'response' => $response));
        }

        if ($instagram_profile_target_hashtag->save()) {
            $response = "Successfully added hashtag!";
            return Response::json(array("success" => true, 'response' => $response, 'id' => $instagram_profile_target_hashtag->id));
        } else {
            return Response::json(array("success" => false, 'response' => $response));
        }
    }
    
    public function deleteComment($id) {
        $comment = \App\InstagramProfileComment::find($id);
        if ($comment->delete()) {
            $response = "Successfully deleted comment!";
            return Response::json(array("success" => true, 'response' => $response));
        } else {
            $response = "There has been an error with the server. Please contact live support.";
            return Response::json(array("success" => true, 'response' => $response));
        }
    }
    
    public function deleteTargetUsername($id) {
        $username = \App\InstagramProfileTargetUsername::find($id);
        if ($username->delete()) {
            $response = "Successfully deleted username!";
            return Response::json(array("success" => true, 'response' => $response));
        } else {
            $response = "There has been an error with the server. Please contact live support.";
            return Response::json(array("success" => true, 'response' => $response));
        }
    }
    
    public function deleteTargetHashtag($id) {
        $hashtag = \App\InstagramProfileTargetHashtag::find($id);
        if ($hashtag->delete()) {
            $response = "Successfully deleted hashtag!";
            return Response::json(array("success" => true, 'response' => $response));
        } else {
            $response = "There has been an error with the server. Please contact live support.";
            return Response::json(array("success" => true, 'response' => $response));
        }
    }
    
    
}
