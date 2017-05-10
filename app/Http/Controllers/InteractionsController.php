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
        $instagram_profiles = InstagramProfile::where('email', Auth::user()->email)->take(10)->get();
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

        $ig_profile = InstagramProfile::where('id', $id)->first();

        $likes_done = InstagramProfileLikeLog::where('insta_username', $ig_profile->insta_username)->count();
        $comments_done = InstagramProfileCommentLog::where('insta_username', $ig_profile->insta_username)->count();
        $follows_done = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)->where('follow', 1)->count();
        $unfollows_done = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)->where('unfollowed', 1)->count();
        
        $likes_done_today = InstagramProfileLikeLog::where('insta_username', $ig_profile->insta_username)
                ->whereDate('date_liked', '=', Carbon::today()->toDateString())
                ->count();
        $comments_done_today = InstagramProfileCommentLog::where('insta_username', $ig_profile->insta_username)
                ->whereDate('date_commented', '=', Carbon::today()->toDateString())
                ->count();
        $follows_done_today = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)->where('follow', 1)
                ->whereDate('date_inserted', '=', Carbon::today()->toDateString())
                ->count();
        $unfollows_done_today = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)->where('unfollowed', 1)
                ->whereDate('date_unfollowed', '=', Carbon::today()->toDateString())
                ->count();
//        $likes_done_today = InstagramProfileLikeLog::where('insta_username', $ig_profile->insta_username)
//                ->whereDay('date_liked', '=', date('d'))
//                ->whereMonth('date_liked', '=', date('m'))->count();
//        $comments_done_today = InstagramProfileCommentLog::where('insta_username', $ig_profile->insta_username)
//                ->whereDay('date_commented', '=', date('d'))
//                ->whereMonth('date_commented', '=', date('m'))->count();
//        $follows_done_today = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)->where('follow', 1)
//                ->whereDay('date_inserted', '=', date('d'))
//                ->whereMonth('date_inserted', '=', date('m'))->count();
//        $unfollows_done_today = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)->where('unfollowed', 1)
//                ->whereDay('date_unfollowed', '=', date('d'))
//                ->whereMonth('date_unfollowed', '=', date('m'))->count();
        
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
            'likes_done' => $likes_done,
            'comments_done' => $comments_done,
            'follows_done' => $follows_done,
            'unfollows_done' => $unfollows_done,
        ]);
    }

    public function toggleLike($id) {
        $instagram_profile = InstagramProfile::where('id', $id)->first();
        $instagram_profile->auto_like = ($instagram_profile->auto_like + 1) % 2;
        $response = "There has been an error with the server. Please contact live support.";
        if ($instagram_profile->save()) {
            if ($instagram_profile->auto_like == 1) {
                $response = "Your auto like function has been turned <b>on</b>.";
            } else {
                $response = "Your auto like function has been turned <b>off</b>.";
            }
            return Response::json(array("success" => true, 'message' => $response, 'status' => $instagram_profile->auto_like));
        } else {
            return Response::json(array("success" => false, 'message' => $response, 'status' => $instagram_profile->auto_like));
        }
    }

    public function toggleComment($id) {
        $instagram_profile = InstagramProfile::where('id', $id)->first();
        $instagram_profile->auto_comment = ($instagram_profile->auto_comment + 1) % 2;
        $response = "There has been an error with the server. Please contact live support.";
        if ($instagram_profile->save()) {
            if ($instagram_profile->auto_comment == 1) {
                $response = "Your auto comment function has been turned <b>on</b>.";
            } else {
                $response = "Your auto comment function has been turned <b>off</b>.";
            }
            return Response::json(array("success" => true, 'message' => $response, 'status' => $instagram_profile->auto_comment));
        } else {
            return Response::json(array("success" => false, 'message' => $response, 'status' => $instagram_profile->auto_comment));
        }
    }

    public function toggleFollow($id) {
        $instagram_profile = InstagramProfile::where('id', $id)->first();
        $instagram_profile->auto_follow = ($instagram_profile->auto_follow + 1) % 2;
        $response = "There has been an error with the server. Please contact live support.";
        if ($instagram_profile->save()) {
            if ($instagram_profile->auto_follow == 1) {
                $response = "Your auto follow function has been turned <b>on</b>.";
            } else {
                $response = "Your auto follow function has been turned <b>off</b>.";
            }
            return Response::json(array("success" => true, 'message' => $response, 'status' => $instagram_profile->auto_follow));
        } else {
            return Response::json(array("success" => false, 'message' => $response, 'status' => $instagram_profile->auto_follow));
        }
    }

    public function toggleUnfollow($id) {
        $instagram_profile = InstagramProfile::where('id', $id)->first();
        $instagram_profile->auto_unfollow = ($instagram_profile->auto_unfollow + 1) % 2;
        $response = "There has been an error with the server. Please contact live support.";
        if ($instagram_profile->save()) {
            if ($instagram_profile->auto_unfollow == 1) {
                $response = "Your auto unfollow function has been turned <b>on</b>.";
            } else {
                $response = "Your auto unfollow function has been turned <b>off</b>.";
            }
            return Response::json(array("success" => true, 'message' => $response, 'status' => $instagram_profile->auto_unfollow));
        } else {
            return Response::json(array("success" => false, 'message' => $response, 'status' => $instagram_profile->auto_unfollow));
        }
    }

    public function toggleNiche(Request $request, $id) {
        $instagram_profile = InstagramProfile::where('id', $id)->first();
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
        $instagram_profile = InstagramProfile::where('id', $id)->first();

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
        $instagram_profile = InstagramProfile::where('id', $id)->first();
        $instagram_comment = \App\InstagramProfileComment::where("comment", $request->input('comment'))->where("insta_username", $instagram_profile->insta_username)->first();
        $response = "There has been an error with the server. Please contact live support.";
        if (is_null($instagram_comment)) {
            $new_ig_comment = new InstagramProfileComment;
            $user_comment = $request->input('comment');
            $re = '/:(\S+):/im';
            preg_match_all($re, $user_comment, $matches, PREG_SET_ORDER, 0);

            if (count($matches) > 0) {
                $emoji = new Emoji();
                try {
                    foreach ($matches as $match) {
                        $alias = $match[1];
                        $unicode = $emoji->findByAlias($alias);
                        $replaced_str = preg_replace("/$match[0]/im", $unicode, $user_comment);
                        $user_comment = $replaced_str;
                    }
                } catch (\Unicodeveloper\Emoji\Exceptions\UnknownEmoji $ex) {
                    $this->error($ex->getMessage());
                }
            }

            $new_ig_comment->comment = $user_comment;
            $new_ig_comment->ig_profile_id = $id;
            $new_ig_comment->insta_username = $instagram_profile->insta_username;
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
        $instagram_profile = InstagramProfile::where('id', $id)->first();
        $instagram_profile_target_username = InstagramProfileTargetUsername::where("target_username", $request->input('target_username'))->where("insta_username", $instagram_profile->insta_username)->first();

        $response = "There has been an error with the server. Please contact live support.";
        if (is_null($instagram_profile_target_username)) {
            $new_instagram_profile_target_username = new InstagramProfileTargetUsername;
            $new_instagram_profile_target_username->target_username = $request->input('target_username');
            $new_instagram_profile_target_username->insta_username = $instagram_profile->insta_username;
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
        $instagram_profile = InstagramProfile::where('id', $id)->first();
        $instagram_profile_target_hashtag = \App\InstagramProfileTargetHashtag::where("target_hashtag", $request->input('target_hashtag'))->where("insta_username", $instagram_profile->insta_username)->first();
        $response = "There has been an error with the server. Please contact live support.";
        if (is_null($instagram_profile_target_hashtag)) {
            $instagram_profile_target_hashtag = new InstagramProfileTargetHashtag;
            $instagram_profile_target_hashtag->target_hashtag = $request->input('target_hashtag');
            $instagram_profile_target_hashtag->insta_username = $instagram_profile->insta_username;
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
