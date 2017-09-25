<?php

namespace App;

use App\InstagramProfileLikeLog;
use App\LikeLogsArchive;
use App\BlacklistedUsername;
use App\Instagram;

class InteractionLikeHelper{

    public static function like($ig_profile, $instagram, $user_to_like, $item) {
        $ig_profile = $profile;
        $like_response = $instagram->media->like($item->id);
        if ($like_response->status == "ok") {
            try {
                echo("\n" . "[" . $ig_profile->insta_username . "] Liked " . serialize($like_response));
                $like_log = new InstagramProfileLikeLog;
                $like_log->insta_username = $ig_profile->insta_username;
                $like_log->target_username = $user_to_like->username;
                $like_log->target_media = $item->id;
                $like_log->target_media_code = $item->getItemUrl();
                $like_log->log = serialize($like_response);
                if ($like_log->save()) {
                    $ig_profile->next_like_time = \Carbon\Carbon::now()->addMinutes($this->speed_delay);
                    $ig_profile->auto_like_ban = 0;
                    $ig_profile->auto_like_ban_time = NULL;
                    $ig_profile->save();
                    return true; // If true subtract 1 to like_quota
                } else {
                    return false;
                }
            } catch (\Exception $ex) {
                echo "[" . $ig_profile->insta_username . "] saving error [target_username] " . $ex->getMessage() . "\n";
                return false;
            }
        }
        return false;
    }

    public static function checkDuplicateByMediaId($ig_profile, $item) {
        
        if (InstagramProfileLikeLog::where('insta_username', $ig_profile->insta_username)
                        ->where('target_media', $item->id)->count() > 0) {
            #duplicate. Liked before this photo with this id.
            return true;
        }

        //Check for duplicates.
        $liked_logs = LikeLogsArchive::where('insta_username', $ig_profile->insta_username)
                ->where('target_media', $item->id)
                ->first();

        //Duplicate = liked media before.
        if ($liked_logs !== NULL) {
            echo("\n" . "Duplicate Log [MEDIA] Found:\t[$ig_profile->insta_username] [" . $item->id . "]");
            return true;
        }

        return false;
    }

    public static function checkBlacklistAndDuplicate($user_to_like, $page_count) {
        $ig_profile = $this->profile;
        $ig_username = $ig_profile->insta_username;

        //Blacklisted username.
        $blacklisted_username = BlacklistedUsername::find($user_to_like->username);
        if ($blacklisted_username !== NULL) {
            if ($page_count === 1) { //if stuck on page 1 - straight on to subsequent pages.
                return 1;
            } else if ($page_count === 2) { //if stuck on page 2 - continue browsing.
                return 2;
            }
        }

        //Check for duplicates.
        $liked_users = InstagramProfileLikeLog::where('insta_username', $ig_username)
                ->where('target_username', $user_to_like->username)
                ->first();

        //Duplicate = liked before.
        if (count($liked_users) > 0) {
            echo("\n" . "[Current] Duplicate Log Found:\t[$ig_username] [" . $user_to_like->username . "]");
            if ($page_count === 1) { //if stuck on page 1 - straight on to subsequent pages.
                return 1;
            } else if ($page_count === 2) { //if stuck on page 2 - continue browsing.
                return 2;
            }
        }

        //Check for duplicates.
        $liked_users_archive = LikeLogsArchive::where('insta_username', $ig_username)
                ->where('target_username', $user_to_like->username)
                ->first();

        //Duplicate = liked before.
        if (count($liked_users_archive) > 0) {
            echo("\n" . "[Archive] Duplicate Log Found:\t[$ig_username] [" . $user_to_like->username . "]");

            if ($page_count === 1) { //if stuck on page 1 - straight on to subsequent pages.
                return 1;
            } else if ($page_count === 2) { //if stuck on page 2 - continue browsing.
                return 2;
            }
        }
        return 0;
    }

    public static function checkValidTargetUsername($instagram, $target_username) {
        $target_username_id = NULL;
        try {
            $target_username_id = $instagram->people->getUserIdForName(trim($target_username->target_username));
            if ($target_username->last_checked === NULL) {
                $target_response = $instagram->people->getInfoById($target_username_id);
                $target_username->last_checked = \Carbon\Carbon::now();
                if ($target_response->user->follower_count < 10000) {
                    $target_username->insufficient_followers = 1;
                    echo "[" . $this->profile->insta_username . "] [" . $target_username->target_username . "] has insufficient followers.\n";
                }
                $target_username->save();
            }
        } catch (\InstagramAPI\Exception\InstagramException $insta_ex) {
            $target_username_id = NULL;
            $target_username->invalid = 1;
            $target_username->save();
            echo "\n[" . $this->profile->insta_username . "] encountered error [" . $target_username->target_username . "]: " . $insta_ex->getMessage() . "\n";
            $this->handleInstagramException($this->profile->insta_username, $insta_ex);
        }
        return $target_username_id;
    }

    private function randomizeUseHashtags() {
        $use_hashtags = rand(0, 1);
        if ($use_hashtags == 1 && count($this->targeted_hashtags) == 0) {
            $use_hashtags = 0;
        } else if ($use_hashtags == 0 && count($this->targeted_usernames) == 0) {
            $use_hashtags = 1;
        }
        return $use_hashtags;
    }

    public static function handleInstagramException($ig_profile, $ex) {
        $ig_username = $ig_profile->insta_username;
        if (strpos($ex->getMessage(), 'Throttled by Instagram because of too many API requests') !== false) {
            $ig_profile->next_like_time = \Carbon\Carbon::now()->addHours(2);
            $ig_profile->save();
            echo "\n[$ig_username] has next_like_time shifted forward to " . \Carbon\Carbon::now()->addHours(2)->toDateTimeString() . "\n";
            exit;
        } else if ($ex instanceof \InstagramAPI\Exception\FeedbackRequiredException) {
            if ($ex->hasResponse()) {
                $feedback_required_response = $ex->getResponse();
                if (strpos($feedback_required_response->fullResponse->feedback_message, 'This action was blocked. Please try again later. We restrict certain content and actions to protect our community. Tell us if you think we made a mistake') !== false) {
                    $ig_profile->next_like_time = \Carbon\Carbon::now()->addHours(4);
                    $ig_profile->auto_like_ban = 1;
                    $ig_profile->auto_like_ban_time = \Carbon\Carbon::now()->addHours(4);
                    $ig_profile->save();
                    echo "\n[$ig_username] has next_like_time shifted forward to " . \Carbon\Carbon::now()->addHours(2)->toDateTimeString() . "\n";
                    exit;
                }
            }
            $ig_profile->feedback_required = 1;
            $ig_profile->error_msg = $ex->getMessage();
        } else if ($ex instanceof \InstagramAPI\Exception\CheckpointRequiredException) {
            $ig_profile->checkpoint_required = 1;
            $ig_profile->error_msg = $ex->getMessage();
        } else if ($ex instanceof \InstagramAPI\Exception\NetworkException) {
            
        } else if ($ex instanceof \InstagramAPI\Exception\EndpointException) {
            if ($ex->getMessage() === "InstagramAPI\Response\LoginResponse: The username you entered doesn't appear to belong to an account. Please check your username and try again.") {
                $ig_profile->error_msg = $ex->getMessage();
            } else if ($ex->getMessage() === "InstagramAPI\Response\LoginResponse: The username you entered doesn't appear to belong to an account. Please check your username and try again.") {
                $ig_profile->invalid_user = 1;
            }
        } else if ($ex instanceof \InstagramAPI\Exception\IncorrectPasswordException) {
            $ig_profile->incorrect_pw = 1;
        } else if ($ex instanceof \InstagramAPI\Exception\AccountDisabledException) {
            $ig_profile->account_disabled = 1;
        }

        if ($ex->hasResponse()) {
            dump($ex->getResponse());
        } else {
            echo("\nThis exception has no response.\n");
        }

        $ig_profile->save();
    }

    private static function calcSpeedDelay($speed) {
        $speed_delay = 3;
        if ($speed == "Fast") {
            $speed_delay = 1;
        }
        $this->speed_delay = $speed_delay;
    }
}
