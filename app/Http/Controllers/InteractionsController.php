<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Response;
use App\User;
use App\InstagramProfile;
use App\Niche;
use App\Helper;
use App\InstagramProfileComment;
use App\InstagramProfileTargetHashtag;
use App\InstagramProfileTargetUsername;
use App\InstagramProfileCommentLog;
use App\InstagramProfileFollowLog;
use App\InstagramProfileLikeLog;
use Unicodeveloper\Emoji\Emoji;
use Carbon\Carbon;
use App\RedisRepository;

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
        $instagram_profiles = InstagramProfile::where('email', Auth::user()->email)->take(Auth::user()->num_acct)->get();
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
        
        if ($ig_profile == NULL) {
            return redirect('home');
        }
        
        if ($ig_profile->email != Auth::user()->email) {
             return redirect('home');
        }
        
        $likes_done = 0;
        $comments_done = 0;
        $follows_done = 0;
        $unfollows_done = 0;
        

        $likes_done_today = 0;
        $comments_done_today = 0;
        $follows_done_today = 0;
        $unfollows_done_today = 0;
        
        if (Auth::user()->partition == 0) {

	        $likes_done = RedisRepository::getProfileTotalLikeCount($ig_profile->insta_user_id);

//	        $likes_done = InstagramProfileLikeLog::where('insta_username', $ig_profile->insta_username)->count();
	        $comments_done = InstagramProfileCommentLog::where('insta_username', $ig_profile->insta_username)->count();
	        $follows_done = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)->where('follow', 1)->count();
	        $unfollows_done = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)->where('unfollowed', 1)->count();

	        $likes_done_today = RedisRepository::getProfileDailyLikeCount($ig_profile->insta_user_id);

//	        $likes_done_today = InstagramProfileLikeLog::where('insta_username', $ig_profile->insta_username)
//                    ->whereDate('date_liked', '=', Carbon::today()->toDateString())
//                    ->count();
            $comments_done_today = InstagramProfileCommentLog::where('insta_username', $ig_profile->insta_username)
                    ->whereDate('date_commented', '=', Carbon::today()->toDateString())
                    ->count();
            $follows_done_today = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)->where('follow', 1)
                    ->whereDate('date_inserted', '=', Carbon::today()->toDateString())
                    ->count();
            $unfollows_done_today = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)->where('unfollowed', 1)
                    ->whereDate('date_unfollowed', '=', Carbon::today()->toDateString())
                    ->count();
        } else {
	        $connection_name = Helper::getConnection(Auth::user()->partition);

	        $likes_done_today = DB::connection($connection_name)->table('user_insta_profile_like_log')
		        ->where('insta_username', $ig_profile->insta_username)
		        ->whereDate('date_liked', '=', Carbon::today()->toDateString())
		        ->count();
	        $comments_done_today = DB::connection($connection_name)->table('user_insta_profile_comment_log')
		        ->where('insta_username', $ig_profile->insta_username)
		        ->whereDate('date_commented', '=', Carbon::today()->toDateString())
		        ->count();
	        $follows_done_today = DB::connection($connection_name)->table('user_insta_profile_follow_log')
		        ->where('insta_username', $ig_profile->insta_username)->where('follow', 1)
		        ->whereDate('date_inserted', '=', Carbon::today()->toDateString())
		        ->count();
	        $unfollows_done_today = DB::connection($connection_name)->table('user_insta_profile_follow_log')
		        ->where('insta_username', $ig_profile->insta_username)->where('unfollowed', 1)
		        ->whereDate('date_unfollowed', '=', Carbon::today()->toDateString())
		        ->count();
        }
        
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
            'likes_done_today' => $likes_done_today,
            'comments_done_today' => $comments_done_today,
            'follows_done_today' => $follows_done_today,
            'unfollows_done_today' => $unfollows_done_today,
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
            
            if (Auth::user()->partition > 0) {
                $connection_name = Helper::getConnection(Auth::user()->partition);
                DB::connection($connection_name)->table('user_insta_profile')
                    ->where('id', $id)
                    ->update(['auto_like' => $instagram_profile->auto_like]);
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
            
            if (Auth::user()->partition > 0) {
                $connection_name = Helper::getConnection(Auth::user()->partition);
                DB::connection($connection_name)->table('user_insta_profile')
                    ->where('id', $id)
                    ->update(['auto_comment' => $instagram_profile->auto_comment]);
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
            
            if (Auth::user()->partition > 0) {
                $connection_name = Helper::getConnection(Auth::user()->partition);
                DB::connection($connection_name)->table('user_insta_profile')
                    ->where('id', $id)
                    ->update(['auto_follow' => $instagram_profile->auto_follow]);
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
            
            if (Auth::user()->partition > 0) {
                $connection_name = Helper::getConnection(Auth::user()->partition);
                DB::connection($connection_name)->table('user_insta_profile')
                    ->where('id', $id)
                    ->update(['auto_unfollow' => $instagram_profile->auto_unfollow]);
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
            
            if (Auth::user()->partition > 0) {
                $connection_name = Helper::getConnection(Auth::user()->partition);
                DB::connection($connection_name)->table('user_insta_profile')
                    ->where('id', $id)
                    ->update(['niche' => $instagram_profile->niche]);
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
        
        $follow_recent_engaged = 0;
        if ($request->has('recent-follower-toggle')) {
            $follow_recent_engaged = 1;
        }
        
        $minimum_follower_filter = $request->input('min-follower-filter');
        $maximum_follower_filter = $request->input('max-follower-filter');
        $follow_speed = $request->input('follow-speed');
        $follow_cycle = $request->input('follow-cycle');

        $instagram_profile->unfollow_unfollowed = $unfollow_users_not_following_flag;
        $instagram_profile->follow_recent_engaged = $follow_recent_engaged;
        $instagram_profile->follow_min_followers = $minimum_follower_filter;
        $instagram_profile->follow_max_followers = $maximum_follower_filter;
        $instagram_profile->speed = $follow_speed;
        $instagram_profile->follow_cycle = $follow_cycle;
        
        $response = "There has been an error with the server. Please contact live support.";
        if ($instagram_profile->save()) {
            $response = "Your settings have been saved!";
            
            if (Auth::user()->partition > 0) {
                $connection_name = Helper::getConnection(Auth::user()->partition);
                DB::connection($connection_name)->table('user_insta_profile')
                    ->where('id', $id)
                    ->update(['unfollow_unfollowed' => $unfollow_users_not_following_flag,
                              'follow_recent_engaged' => $follow_recent_engaged,
                              'follow_min_followers' => $minimum_follower_filter,
                              'follow_max_followers' => $maximum_follower_filter,
                              'speed' => $follow_speed,
                              'follow_cycle' => $follow_cycle]);
            }
            
            return Response::json(array("success" => true, 'response' => $response));
        } else {
            return Response::json(array("success" => false, 'response' => $response));
        }
    }

    public function saveComment(Request $request, $id) {
        $instagram_profile = InstagramProfile::where('id', $id)->first();
        #$instagram_comment = \App\InstagramProfileComment::where("comment", $request->input('comment'))->where("insta_username", $instagram_profile->insta_username)->first();
        $instagram_comment = \App\InstagramProfileComment::whereRaw("comment LIKE \"" . $request->input('comment')  . "\"  COLLATE utf8mb4_unicode_ci AND insta_username = \"" . $instagram_profile->insta_username . "\"")->first();
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
                    //$this->error($ex->getMessage());
                }
            }

            $new_ig_comment->comment = $user_comment;
            $new_ig_comment->ig_profile_id = $id;
            $new_ig_comment->insta_username = $instagram_profile->insta_username;
        } else {
            $response = "This comment exists already!";
            return Response::json(array("success" => false, 'response' => $response));
        }

        if ($new_ig_comment->save()) {
            $response = "Successfully added comment!";
            return Response::json(array("success" => true, 'response' => $response, 'id' => $new_ig_comment->comment_id));
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
            return Response::json(array("success" => true, 'response' => $response, 'id' => $new_instagram_profile_target_username->target_id));
        } else {
            return Response::json(array("success" => false, 'response' => $response));
        }
    }

    public function saveHashtag(Request $request, $id) {
        $instagram_profile = InstagramProfile::where('id', $id)->first();
        $instagram_profile_target_hashtag = \App\InstagramProfileTargetHashtag::where("hashtag", $request->input('target_hashtag'))
                ->where("insta_username", $instagram_profile->insta_username)
                ->first();
        
        $response = "There has been an error with the server. Please contact live support.";
        if (is_null($instagram_profile_target_hashtag)) {
            $instagram_profile_target_hashtag = new InstagramProfileTargetHashtag;
            $instagram_profile_target_hashtag->hashtag = $request->input('target_hashtag');
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
