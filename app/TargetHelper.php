<?php

namespace App;

use App\Niche;
use App\InteractionHelper;
use InstagramAPI\Instagram as Instagram;
use App\BlacklistedUsername;
use App\InstagramProfileLikeLog;

class TargetHelper {

    public static function getUsernameByNiche($ig_profile, $like_quota, Instagram $instagram) {
        $niche = Niche::find($ig_profile->niche);
        $niche_targets = $niche->targetUsernames();
        $ig_username = $ig_profile->insta_username;

        foreach ($niche_targets as $target_username) {

            if ($like_quota > 0) {

                //Get followers of the target.
                echo("\n" . "[$ig_username] Target Username: " . $target_username->target_username . "\n");

                $target_target_username = $target_username->target_username;

                $target_username_id = $instagram->people->getUserIdForName(trim($target_target_username));

                $user_follower_response = NULL;

                if ($target_username_id != "") {

                    $next_max_id = null;

                    $page_count = 0;

                    do {

                        echo "\n[$ig_username] requesting [$target_target_username] with: " . $next_max_id . "\n";

                        if ($next_max_id === NULL) {
                            $user_follower_response = $instagram->people->getFollowers($target_username_id);
                        } else {
                            $user_follower_response = $instagram->people->getFollowers($target_username_id, NULL, $next_max_id);
                        }

                        $target_user_followings = $user_follower_response->users;

                        echo "\n[$ig_username] requesting [$target_target_username] got us a list of [" . count($target_user_followings) . "] users. \n";

                        $duplicate = 0;

                        $next_max_id = $user_follower_response->next_max_id;

                        echo "\n[$ig_username] next_max_id for [$target_target_username] is " . $next_max_id;

                        $page_count++;

                        //Foreach follower of the target.
                        foreach ($target_user_followings as $user_to_like) {

                            if ($like_quota > 0) {

                                //Blacklisted username.
                                $blacklisted_username = BlacklistedUsername::find($user_to_like->username);
                                if ($blacklisted_username !== NULL) {
                                    continue;
                                }

                                echo("\n" . $user_to_like->username . "\t" . $user_to_like->pk);

                                //Check for duplicates.
                                $liked_users = InstagramProfileLikeLog::where('insta_username', $ig_username)
                                        ->where('target_username', $user_to_like->username)
                                        ->first();

                                //Duplicate = liked before.
                                if (count($liked_users) > 0) {
                                    echo("\n" . "[Current] Duplicate Log Found:\t[$ig_username] [" . $user_to_like->username . "]");
                                    continue;
                                }

                                //Check for duplicates.
                                $liked_users = LikeLogsArchive::where('insta_username', $ig_username)
                                        ->where('target_username', $user_to_like->username)
                                        ->first();

                                //Duplicate = liked before.
                                if (count($liked_users) > 0) {
                                    echo("\n" . "[Archive] Duplicate Log Found:\t[$ig_username] [" . $user_to_like->username . "]");
                                    continue;
                                }

                                //Get the feed of the user to like.
                                $user_feed_response = NULL;

                                try {
                                    if (is_null($user_to_like)) {
                                        echo("\n" . "Null User - Target Username");
                                        continue;
                                    }
                                    $user_feed_response = $instagram->timeline->getUserFeed($user_to_like->pk);
                                } catch (\InstagramAPI\Exception\EndpointException $endpt_ex) {

                                    echo("\n" . "Endpoint ex: " . $endpt_ex->getMessage());

                                    if ($endpt_ex->getMessage() == "InstagramAPI\Response\UserFeedResponse: Not authorized to view user.") {
                                        $blacklist_username = new BlacklistedUsername;
                                        $blacklist_username->username = $user_to_like->username;
                                        $blacklist_username->save();
                                        echo("\n" . "Blacklisted: " . $user_to_like->username);
                                    }
                                    continue;
                                } catch (\Exception $ex) {
                                    echo("\n" . "Exception: " . $ex->getMessage());
                                    continue;
                                }

                                //Get the media posted by the user.
                                $user_items = $user_feed_response->items;
                                //Foreach media posted by the user.
                                foreach ($user_items as $item) {

                                    if (InstagramProfileLikeLog::where('insta_username', $ig_username)->where('target_media', $item->id)->count() > 0) {
                                        #duplicate. Liked before this photo with this id.
                                        continue;
                                    }

                                    if ($like_quota > 0) {

                                        //Check for duplicates.
                                        $liked_logs = LikeLogsArchive::where('insta_username', $ig_username)
                                                ->where('target_media', $item->id)
                                                ->first();

                                        //Duplicate = liked media before.
                                        if (count($liked_logs) > 0) {
                                            echo("\n" . "Duplicate Log [MEDIA] Found:\t[$ig_username] [" . $item->id . "]");
                                            continue;
                                        }

                                        $like_response = $instagram->media->like($item->id);

                                        if ($like_response->status == "ok") {
                                            try {
                                                echo("\n" . "[$ig_username] Liked " . serialize($like_response));
                                                $like_log = new InstagramProfileLikeLog;
                                                $like_log->insta_username = $ig_username;
                                                $like_log->target_username = $user_to_like->username;
                                                $like_log->target_media = $item->id;
                                                $like_log->target_media_code = $item->getItemUrl();
                                                $like_log->log = serialize($like_response);
                                                $like_log->save();
                                                $like_quota--;

                                                $ig_profile->next_like_time = \Carbon\Carbon::now()->addMinutes($speed_delay);
                                                $ig_profile->save();
                                            } catch (\Exception $ex) {
                                                echo "[$ig_username] saving error [target_username] " . $ex->getMessage() . "\n";
                                                continue;
                                            }
                                        }
                                    } else {
                                        break;
                                    }
                                }
                            } else {
                                break;
                            }
                        }
                    } while ($next_max_id !== NULL && $like_quota > 0);
                } else {
                    continue;
                }
            }
        }
        return $like_quota;
    }

    public static function getHashtagByNiche($ig_profile, $like_quota, Instagram $instagram) {
        $niche = Niche::find($ig_profile->niche);
        $target_hashtags = $niche->targetHashtags();
        $ig_username = $ig_profile->insta_username;

        foreach ($target_hashtags as $target_hashtag) {

            echo("\n" . "target hashtag: " . $target_hashtag->hashtag . "\n\n");

            $hashtag_feed = $instagram->hashtag->getFeed(trim($target_hashtag->hashtag));
            #$hashtag_feed = $instagram->getHashtagFeed();

            foreach ($hashtag_feed->items as $item) {

                if (InstagramProfileLikeLog::where('insta_username', $ig_username)->where('target_media', $item->id)->count() > 0) {
                    #duplicate. Liked before this photo with this id.
                    continue;
                }

                $duplicate = 0;

                $user_to_like = $item->user;

                if (is_null($user_to_like)) {
                    echo("\n" . "null user");
                    continue;
                }

                $followed_users = DB::connection('mysql_old')
                        ->select("SELECT log_id FROM user_insta_profile_like_log WHERE insta_username = ? AND target_username = ?;", [$ig_username, $user_to_like->username]);

                foreach ($followed_users as $followed_user) {
                    $duplicate = 1;
                    echo("\n" . "duplicate log found:\t" . $followed_user->log_id);
                    break;
                }

                if ($duplicate == 1) {
                    continue;
                }

                if ($like_quota > 0) {

                    if ($like_quota == 0) {
                        break;
                    }

                    $like_response = $instagram->media->like($item->id);

                    echo("\n" . "liked " . serialize($like_response));

                    DB::connection('mysql_old')->insert("INSERT INTO user_insta_profile_like_log (insta_username, target_username, target_media, target_media_code, log) "
                            . "VALUES (?,?,?,?,?);", [$ig_username, $user_to_like->username, $item->id, $item->getItemUrl(), serialize($like_response)]);
                    $like_quota--;
                }

                if ($like_quota == 0) {
                    break;
                }
            }
            if ($like_quota == 0) {
                break;
            }
        }
        return $like_quota;
    }

    public static function getUserDefinedHashtag($ig_profile, $like_quota, Instagram $instagram) {
        /*
         * Next is to get user-defined hashtags.
         */
        $ig_username = $ig_profile->insta_username;
        $target_hashtags = InstagramProfileTargetHashtag::where('insta_username', $ig_username)->inRandomOrder()->get();

        //Foreach targeted hashtags
        foreach ($target_hashtags as $target_hashtag) {
            if ($like_quota > 0) {
                echo("\n" . "[$ig_username] Target Hashtag: " . $target_hashtag->hashtag . "\n\n");
                //Get the feed from the targeted hashtag.

                $hashtag_feed = $instagram->hashtag->getFeed(trim($target_hashtag->hashtag));
                //Foreach post under this target hashtag
                foreach ($hashtag_feed->items as $item) {
                    //Get user because we need to check if they have been liked before.
                    $user_to_like = $item->user;
                    //Weird error, null user. Check to be safe.
                    if (is_null($user_to_like)) {
                        echo("\n" . "null user");
                        continue;
                    }

                    //Check for duplicates.
                    $liked_users = InstagramProfileLikeLog::where('insta_username', $ig_username)
                            ->where('target_username', $user_to_like->username)
                            ->first();

                    //Duplicate = liked before.
                    if (count($liked_users) > 0) {
                        echo("\n" . "duplicate log found:\t[$ig_username] [" . $user_to_like->username . "]");
                        continue;
                    }

                    //Check for duplicates.
                    $liked_users = LikeLogsArchive::where('insta_username', $ig_username)
                            ->where('target_username', $user_to_like->username)
                            ->first();

                    //Duplicate = liked before.
                    if (count($liked_users) > 0) {
                        echo("\n" . "Duplicate Log Found:\t[$ig_username] [" . $user_to_like->username . "]");
                        continue;
                    }

                    if ($like_quota > 0) {

                        if (InstagramProfileLikeLog::where('insta_username', $ig_username)->where('target_media', $item->id)->count() > 0) {
                            #duplicate. Liked before this photo with this id.
                            continue;
                        }
                        echo("\n" . "[$ig_username] attemping to like: " . $item->id);
                        $like_response = $instagram->media->like($item->id);

                        if (InteractionHelper::like($ig_username, $like_response, $user_to_like, $item) == true) {
                            $like_quota--;

                            $ig_profile->next_like_time = \Carbon\Carbon::now()->addMinutes($speed_delay);
                            $ig_profile->save();
                        }
                        /*
                          Fixed
                          if ($like_response->status == "ok") {
                          echo("\n" . "[$ig_username] Liked " . serialize($like_response));
                          $like_log = new InstagramProfileLikeLog;
                          $like_log->insta_username = $ig_username;
                          $like_log->target_username = $user_to_like->username;
                          $like_log->target_media = $item->id;
                          $like_log->target_media_code = $item->getItemUrl();
                          $like_log->log = serialize($like_response);
                          $like_log->save();
                          $like_quota--;

                          $ig_profile->next_like_time = \Carbon\Carbon::now()->addMinutes($speed_delay);
                          $ig_profile->save();
                          }
                         */
                    } else {
                        break;
                    }
                }
            } else {
                break;
            }
        }
        return $like_quota;
    }

    public static function definedTargetUsernames($ig_profile, $like_quota, Instagram $instagram) {
        /*
         * Defined target usernames take precedence.
         */
        $target_usernames = InstagramProfileTargetUsername::where('insta_username', $ig_username)
                        ->where('invalid', 0)
                        ->where('insufficient_followers', 0)
                        ->get()->shuffle();

        foreach ($target_usernames as $target_username) {

            if ($like_quota > 0) {

                //Get followers of the target.
                echo("\n" . "[$ig_username] Target Username: " . $target_username->target_username . "\n");

                $target_target_username = $target_username->target_username;

                $target_username_id = "";
                try {
                    $target_username_id = $instagram->people->getUserIdForName(trim($target_target_username));

                    if ($target_username->last_checked === NULL) {
                        $target_response = $instagram->people->getInfoById($target_username_id);
                        $target_username->last_checked = \Carbon\Carbon::now();

                        if ($target_response->user->follower_count < 10000) {
                            $target_username->insufficient_followers = 1;
                            echo "[$ig_username] [$target_username] has insufficient followers.\n";
                        }

                        $target_username->save();
                    }
                } catch (\InstagramAPI\Exception\InstagramException $insta_ex) {

                    $target_username_id = "";
                    $target_username->invalid = 1;
                    $target_username->save();
                    echo "\n[$ig_username] encountered error [$target_target_username]: " . $insta_ex->getMessage() . "\n";

                    $this->handleInstagramException($ig_profile, $insta_ex);
                }

                $user_follower_response = NULL;

                if ($target_username_id != "") {

                    $next_max_id = null;

                    $page_count = 0;

                    do {

                        echo "\n[$ig_username] requesting [$target_target_username] with: " . $next_max_id . "\n";

                        $user_follower_response = $instagram->people->getFollowers($target_username_id, NULL, $next_max_id);

                        $target_user_followings = $user_follower_response->users;

                        $duplicate = 0;

                        $next_max_id = $user_follower_response->next_max_id;

                        echo "\n[$ig_username] next_max_id for [$target_target_username] is " . $next_max_id;

                        $page_count++;

                        //Foreach follower of the target.
                        foreach ($target_user_followings as $user_to_like) {

                            if ($like_quota > 0) {

                                //Blacklisted username.
                                $blacklisted_username = BlacklistedUsername::find($user_to_like->username);
                                if ($blacklisted_username !== NULL) {
                                    if ($page_count === 1) { //if stuck on page 1 - straight on to subsequent pages.
                                        break;
                                    } else if ($page_count === 2) { //if stuck on page 2 - continue browsing.
                                        continue;
                                    }
                                }

                                echo("\n" . $user_to_like->username . "\t" . $user_to_like->pk);

                                //Check for duplicates.
                                $liked_users = InstagramProfileLikeLog::where('insta_username', $ig_username)
                                        ->where('target_username', $user_to_like->username)
                                        ->first();

                                //Duplicate = liked before.
                                if (count($liked_users) > 0) {
                                    echo("\n" . "[Current] Duplicate Log Found:\t[$ig_username] [" . $user_to_like->username . "]");

                                    if ($page_count === 1) { //if stuck on page 1 - straight on to subsequent pages.
                                        break;
                                    } else if ($page_count === 2) { //if stuck on page 2 - continue browsing.
                                        continue;
                                    }
                                }

                                //Check for duplicates.
                                $liked_users = LikeLogsArchive::where('insta_username', $ig_username)
                                        ->where('target_username', $user_to_like->username)
                                        ->first();

                                //Duplicate = liked before.
                                if (count($liked_users) > 0) {
                                    echo("\n" . "[Archive] Duplicate Log Found:\t[$ig_username] [" . $user_to_like->username . "]");

                                    if ($page_count === 1) { //if stuck on page 1 - straight on to subsequent pages.
                                        break;
                                    } else if ($page_count === 2) { //if stuck on page 2 - continue browsing.
                                        continue;
                                    }
                                }

                                //Get the feed of the user to like.
                                $user_feed_response = NULL;
                                try {
                                    if (is_null($user_to_like)) {
                                        echo("\n" . "Null User - Target Username");
                                        continue;
                                    }
                                    $user_feed_response = $instagram->timeline->getUserFeed($user_to_like->pk);
                                } catch (\InstagramAPI\Exception\EndpointException $endpt_ex) {

                                    echo("\n" . "Endpoint ex: " . $endpt_ex->getMessage());

                                    if ($endpt_ex->getMessage() == "InstagramAPI\Response\UserFeedResponse: Not authorized to view user.") {
                                        if (BlacklistedUsername::find($user_to_like->username) === NULL) {
                                            $blacklist_username = new BlacklistedUsername;
                                            $blacklist_username->username = $user_to_like->username;
                                            $blacklist_username->save();
                                            echo("\n" . "Blacklisted: " . $user_to_like->username);
                                        } else {
                                            continue;
                                        }
                                    }
                                    continue;
                                } catch (\Exception $ex) {
                                    echo("\n" . "Exception: " . $ex->getMessage());
                                    continue;
                                }

                                //Get the media posted by the user.
                                $user_items = $user_feed_response->items;
                                //Foreach media posted by the user.
                                foreach ($user_items as $item) {

                                    if (InstagramProfileLikeLog::where('insta_username', $ig_username)->where('target_media', $item->id)->count() > 0) {
                                        #duplicate. Liked before this photo with this id.
                                        continue;
                                    }

                                    if ($like_quota > 0) {

                                        //Check for duplicates.
                                        $liked_logs = LikeLogsArchive::where('insta_username', $ig_username)
                                                ->where('target_media', $item->id)
                                                ->first();

                                        //Duplicate = liked media before.
                                        if (count($liked_logs) > 0) {
                                            echo("\n" . "Duplicate Log [MEDIA] Found:\t[$ig_username] [" . $item->id . "]");
                                            continue;
                                        }

                                        $like_response = $instagram->media->like($item->id);

                                        if ($like_response->status == "ok") {
                                            try {
                                                echo("\n" . "[$ig_username] Liked " . serialize($like_response));
                                                $like_log = new InstagramProfileLikeLog;
                                                $like_log->insta_username = $ig_username;
                                                $like_log->target_username = $user_to_like->username;
                                                $like_log->target_media = $item->id;
                                                $like_log->target_media_code = $item->getItemUrl();
                                                $like_log->log = serialize($like_response);
                                                $like_log->save();
                                                $like_quota--;

                                                $ig_profile->next_like_time = \Carbon\Carbon::now()->addMinutes($speed_delay);
                                                $ig_profile->save();
                                            } catch (\Exception $ex) {
                                                echo "[$ig_username] saving error [target_username] " . $ex->getMessage() . "\n";
                                                continue;
                                            }
                                        }
                                    } else {
                                        break;
                                    }
                                }
                            } else {
                                break;
                            }
                        }
                    } while ($next_max_id !== NULL && $like_quota > 0);
                } else {
                    continue;
                }
            }
        }
        return $like_quota;
    }

    public static function getUserTargetedUsernames($ig_profile) {
        /*
         * Defined target usernames take precedence.
         */
        $target_usernames = InstagramProfileTargetUsername::where('insta_username', $ig_profile->insta_username)
                        ->where('invalid', 0)
                        ->where('insufficient_followers', 0)
                        ->get()->shuffle();

        return $target_usernames;
    }

    public static function getUserTargetedHashtags($ig_profile) {
        /*
         * Defined target usernames take precedence.
         */
        $target_hashtags = InstagramProfileTargetHashtag::where('insta_username', $ig_profile->insta_username)
                ->get()
                ->shuffle();

        return $target_hashtags;
    }
    
    
    
    
}
