<?php

namespace App;
use App\InstagramProfileCommentLog;
use App\InstagramProfileLikeLog;

class InteractionCommentHelper{
  public static function unEngagedLiking($ig_profile, $instagram){
    $ig_username = $ig_profile->insta_username;
    $unengaged_likings = InstagramProfileLikeLog::where('insta_username', $ig_username)
            ->orderBy('date_liked', 'desc')
            ->take(20)
            ->get();

    foreach ($unengaged_likings as $unengaged_liking) {

        if (InstagramProfileCommentLog::where('insta_username', $unengaged_liking->insta_username)
                        ->where('target_username', $unengaged_liking->target_username)
                        ->count() > 0) {
            echo("[Like][$ig_username] has engaged before " . $unengaged_liking->target_username . "\n");
            continue;
        }

        echo("[$ig_username] unengaged likes: \t" . $unengaged_liking->target_username . "\n");

        $engaged_user = $unengaged_liking->target_username;
        try {
            $user_instagram_id = $instagram->people->getUserIdForName($unengaged_liking->target_username);
            #$user_instagram_id = $instagram->getUsernameId($unengaged_liking->target_username);
        } catch (\InstagramAPI\Exception\RequestException $request_ex) {
            if ($request_ex->getMessage() === "InstagramAPI\Response\UserInfoResponse: User not found.") {
                $comment_log = new InstagramProfileCommentLog;
                $comment_log->insta_username = $ig_username;
                $comment_log->target_username = $unengaged_liking->target_username;
                $comment_log->log = $request_ex->getMessage();
                $comment_log->save();
            }
            echo("[$ig_username] #Followings Failed to get username id: " . $request_ex->getMessage() . "\n");
        }

        if ($user_instagram_id === NULL) {
            continue;
        }

        $user_feed = $instagram->timeline->getUserFeed($user_instagram_id);
        $user_feed_items = $user_feed->items;

        if (count($user_feed_items) > 0) {
            foreach ($user_feed_items as $item) {

                $comment_log = new InstagramProfileCommentLog;
                $comment_log->insta_username = $ig_username;
                $comment_log->target_username = $unengaged_liking->target_username;
                $comment_log->target_insta_id = $user_instagram_id;
                $comment_log->target_media = $item->id;
                $comment_log->save();

                $comment_resp = $instagram->media->comment($item->id, $commentText);
                $comment_log->log = serialize($comment_resp);
                if ($comment_log->save()) {
                    echo("[$ig_username] has commented on [" . $item->getItemUrl() . "]\n");
                }

                $commented = true;
                $ig_profile->next_comment_time = \Carbon\Carbon::now()->addMinutes(rand(10, 12));
                $ig_profile->save();
                break;
            }
        }

        if ($commented) {
            break;
        }
    }
    return $engaged_user;
  }

  public static function unEngagedFollowings($ig_profile, $instagram, $unengaged_followings){
    foreach ($unengaged_followings as $unengaged_following) {
        if (InstagramProfileCommentLog::where('insta_username', $unengaged_following->insta_username)
                        ->where('target_username', $unengaged_following->follower_username)
                        ->count() > 0) {
            echo("[Follow][$ig_username] has engaged before " . $unengaged_following->follower_username . "\n");
            continue;
        }

        echo("[$ig_username] unengaged followings: \t" . $unengaged_following->follower_username . "\n");
        $engaged_user = $unengaged_following->target_username;

        try {
            #$user_instagram_id = $instagram->getUsernameId($unengaged_following->follower_username);
            $user_instagram_id = $instagram->people->getUserIdForName($unengaged_following->follower_username);
        } catch (\InstagramAPI\Exception\RequestException $request_ex) {
            if ($request_ex->getMessage() === "InstagramAPI\Response\UserInfoResponse: User not found.") {
                $comment_log = new InstagramProfileCommentLog;
                $comment_log->insta_username = $ig_username;
                $comment_log->target_username = $unengaged_following->follower_username;
                $comment_log->log = $request_ex->getMessage();
                $comment_log->save();
            }
            echo("[$ig_username] #Followings Failed to get username id: " . $request_ex->getMessage() . "\n");
        }

        if ($user_instagram_id === NULL) {
            continue;
        }

        $user_feed = $instagram->timeline->getUserFeed($user_instagram_id);
        $user_feed_items = $user_feed->items;

        if (count($user_feed_items) > 0) {
            foreach ($user_feed_items as $item) {

                $comment_log = new InstagramProfileCommentLog;
                $comment_log->insta_username = $ig_username;
                $comment_log->target_username = $unengaged_following->follower_username;
                $comment_log->target_insta_id = $user_instagram_id;
                $comment_log->target_media = $item->id;
                $comment_log->save();
                $comment_resp = $instagram->media->comment($item->id, $commentText);
                $comment_log->log = serialize($comment_resp);
                if ($comment_log->save()) {
                    echo("[$ig_username] has commented on [" . $item->getItemUrl() . "]\n");
                }

                $commented = true;
                $ig_profile->next_comment_time = \Carbon\Carbon::now()->addMinutes(rand(10, 12));
                $ig_profile->save();

                break;
            }
        }

        if ($commented) {
            break;
        }
    }
    return $engaged_user;
  }

  public static function handleInstragramException($ig_profile, $ex, $engaged_user){
        $ig_username = $ig_profile->insta_username;
        if ($ex instanceof \InstagramAPI\Exception\CheckpointRequiredException) {
                    echo("checkpt1 " . $ex->getMessage() . "\n");
                    $ig_profile->checkpoint_required = 1;
                    $ig_profile->save();
        }
        else if($ex instanceof \InstagramAPI\Exception\IncorrectPasswordException) {
                echo("incorrectpw1 " . $ex->getMessage() . "\n");
                $ig_profile->incorrect_pw = 1;
                $ig_profile->save();
        }
        else if($ex instanceof  \InstagramAPI\Exception\EndpointException) {

                if ($ex->getMessage() === "InstagramAPI\Response\UserInfoResponse: User not found.") {
                    $comment_log = new InstagramProfileCommentLog;
                    $comment_log->insta_username = $ig_username;
                    $comment_log->target_username = $engaged_user;
                    $comment_log->save();
                } else if ($ex->getMessage() === "InstagramAPI\Response\UserFeedResponse: Not authorized to view user.") {
                    $comment_log = new InstagramProfileCommentLog;
                    $comment_log->insta_username = $ig_username;
                    $comment_log->target_username = $engaged_user;
                    $comment_log->save();
                }

                echo("endpt1 " . $endpoint_ex->getMessage() . "\n");
        }
        else if($ex instanceof \InstagramAPI\Exception\NetworkException) {

            echo("network1 " . $ex->getMessage() . "\n");
        }
        else if($ex instanceof  \InstagramAPI\Exception\AccountDisabledException) {

            echo("acctdisabled1 " . $ex->getMessage() . "\n");

            $ig_profile->account_disabled = 1;
            $ig_profile->save();
        }
        else if($ex instanceof \InstagramAPI\Exception\RequestException) {
            if ($ex->getMessage() === "InstagramAPI\Response\CommentResponse: Feedback required.") {
                if ($ex->hasResponse()) {
                    $full_response = $ex->getResponse()->fullResponse;

                    if ($full_response->spam === true) {
                        $ig_profile->auto_comment_ban = 1;
                        $ig_profile->auto_comment_ban_time = \Carbon\Carbon::now()->addHours(6);
                        $ig_profile->next_comment_time = \Carbon\Carbon::now()->addHours(6);
                        if ($ig_profile->save()) {
                            echo("[" . $ig_profile->username . "] commenting has been banned till " . $ig_profile->auto_comment_ban_time);
                        }
                    }
                }
            } else {
                echo("[ENDING] Request Exception: " . $ex->getMessage() . "\n");
                var_dump($ex->getResponse());
            }
            $ig_profile->error_msg = $ex->getMessage();
            $ig_profile->save();
        }
  }
}