<?php

namespace App;

use Illuminate\Support\Facades\DB;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\User;
use App\InstagramProfile;
use App\InstagramProfileComment;
use App\InstagramProfileCommentLog;
use App\InstagramProfileFollowLog;
use App\InstagramProfileLikeLog;
use App\InstagramProfileTargetHashtag;
use App\InstagramProfileTargetUsername;
use App\Niche;
use App\NicheTarget;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;

class InstagramHelper {

    public static function initInstagram() {
        $config = array();
        $config['pdo'] = DB::connection('mysql_igsession')->getPdo();
        $config["dbtablename"] = "instagram_sessions";
        $config["storage"] = "mysql";

        $debug = false;
        $truncatedDebug = false;
        $instagram = new Instagram($debug, $truncatedDebug, $config);

        return $instagram;
    }

    /*
     * TRUE if the login was successful.
     * FALSE if the the login failed.
     */

    public static function login(Instagram $instagram, InstagramProfile $ig_profile) {
        $flag = false;
        $message = '';
        echo("Verifying proxy for profile: [" . $ig_profile->insta_username . "]\n");

        InstagramHelper::verifyAndReassignProxy($ig_profile);
        $instagram->setProxy($ig_profile->proxy);

        echo("Logging in profile: [" . $ig_profile->insta_username . "] [" . $ig_profile->insta_pw . "]\n");

        try {
            $explorer_response = $instagram->login($ig_profile->insta_username, $ig_profile->insta_pw);
            $flag = true;
            
        } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
            $ig_profile->checkpoint_required = 1;
            $ig_profile->save();
            $message = "CheckpointRequiredException\n";
        } catch (\InstagramAPI\Exception\InvalidUserException $invalid_user_ex) {
            $ig_profile->invalid_user = 1;
            $ig_profile->save();
        } catch (\InstagramAPI\Exception\NetworkException $network_ex) {

            InstagramHelper::verifyAndReassignProxy($ig_profile);

            $ig_profile->invalid_proxy = $ig_profile->invalid_proxy + 1;
            $ig_profile->save();

            $message = "NetworkException";
            try {
                $instagram->login($ig_profile->insta_username, $ig_profile->insta_pw);
                $flag = true;
            } catch (\InstagramAPI\Exception\InstagramException $login_ex) {
                $message .= " with InstagramException\n";
            }

            dump($network_ex);
        } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
            
        } catch (\InstagramAPI\Exception\BadRequestException $badrequest_ex) {
            
        } catch (\InstagramAPI\Exception\ForcedPasswordResetException $forcedpwreset_ex) {
            $ig_profile->incorrect_pw = 1;
            $ig_profile->save();
        } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
            $ig_profile->incorrect_pw = 1;
            $ig_profile->save();
            $message = "IncorrectPasswordException\n";
        } catch (\InstagramAPI\Exception\AccountDisabledException $accountdisabled_ex) {
            $ig_profile->invalid_user = 1;
            $ig_profile->save();
            $message = "AccountDisabledException\n";
        } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
            $ig_profile->incorrect_pw = 1;
            $ig_profile->save();
            $message = "IncorrectPasswordException\n";
        } catch (\InstagramAPI\Exception\ChallengeRequiredException $challengerequired_ex) {
            $ig_profile->checkpoint_required = 1;
            $ig_profile->save();
            $message = "ChallengeRequiredException\n";
        }
        if (!$flag) {
            echo '[' . $ig_profile->insta_username . '] Error:  ' . $message . "\n";
        } else {
            echo '[' . $ig_profile->insta_username . '] has been logged in.' . "\n";
        }
        return $flag;
    }

    public static function verifyAndReassignProxy(InstagramProfile $ig_profile) {
        if ($ig_profile->proxy === NULL || $ig_profile->invalid_proxy > 0) {
            $proxy = Proxy::inRandomOrder()->first();
            $ig_profile->proxy = $proxy->proxy;
            $ig_profile->invalid_proxy = 0;
            $ig_profile->save();
            $proxy->assigned = $proxy->assigned + 1;
            $proxy->save();
            echo '[' . $ig_profile->insta_username . '] has been reassigned a proxy.' . "\n";
        }
    }

    public static function forceReassignProxy(InstagramProfile $ig_profile) {
        $proxy = Proxy::inRandomOrder()->first();
        $ig_profile->proxy = $proxy->proxy;
        $ig_profile->save();
        $proxy->assigned = $proxy->assigned + 1;
        if ($proxy->save()) {
            return true;
        } else {
            return false;
        }
    }

    public static function getUserIdForNicheUsername($instagram, $target_username) {
        $username_id = NULL;
        $username_id = $instagram->people->getUserIdForName(trim($target_username->target_username));
        return $username_id;
    }

    public static function getUserIdForName($instagram, $target_username) {
        $username_id = NULL;
        try {
            $username_id = $instagram->people->getUserIdForName(trim($target_username->target_username));
        } catch (\InstagramAPI\Exception\NotFoundException $not_found_ex) {
            $target_username->invalid = 1;
            $target_username->save();
        }
        return $username_id;
    }

    public static function getUserInfo($instagram, $ig_profile) {
        try {
            $user_response = $instagram->people->getInfoById($ig_profile->insta_user_id);
            return $user_response->user;
        } catch (\InstagramAPI\Exception\InstagramException $insta_ex) {
            echo "[" . $ig_profile->insta_username . "] " . $insta_ex->getMessage() . "\n";
            echo $insta_ex->getTraceAsString() . "\n";
        }
    }

    public static function getTargetUsernameFollowers($instagram, $target_username, $username_id) {
        $user_follower_response = $instagram->people->getFollowers($username_id);
        $users_to_follow = $user_follower_response->users;
        return $users_to_follow;
    }

    public static function getUserFeed(Instagram $instagram, $user_to_like) {
        //Get the feed of the user to like.
        try {
            if ($user_to_like === NULL) {
                echo("\n" . "Null User - Target Username");
                return NULL;
            }
            return $instagram->timeline->getUserFeed($user_to_like->pk);
        } catch (\InstagramAPI\Exception\EndpointException $endpt_ex) {
            echo("\n" . "Endpoint ex: " . $endpt_ex->getMessage());
            if ($endpt_ex->getMessage() == "InstagramAPI\Response\UserFeedResponse: Not authorized to view user.") {
                if (BlacklistedUsername::find($user_to_like->username) === NULL) {
                    $blacklist_username = new BlacklistedUsername;
                    $blacklist_username->username = $user_to_like->username;
                    $blacklist_username->save();
                    echo("\n" . "Blacklisted: " . $user_to_like->username);
                } else {
                    return NULL;
                }
            }
            return NULL;
        } catch (\Exception $ex) {
            echo("\n" . "Exception: " . $ex->getMessage());
            return NULL;
        }
    }

    public static function getTargetHashtagFeed(Instagram $instagram, $hashtag) {
        $hashtag_feed = NULL;
        try {
            $hashtag_feed = $instagram->hashtag->getFeed(trim($hashtag->hashtag));
//            dump($hashtag_feed);
            return $hashtag_feed;
        } catch (\InstagramAPI\Exception\NotFoundException $ex) {
            $hashtag->invalid = 1;
            $hashtag->save();
            return NULL;
        } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
            $ig_profile = InstagramProfile::where('insta_user_id', $instagram->account_id)->first();
            if ($ig_profile !== NULL) {
                $ig_profile->invalid_proxy = $ig_profile->invalid_proxy + 1;
                $ig_profile->save();
            }
            return NULL;
        }
    }

    public static function getHashtagFeed(Instagram $instagram, $hashtag) {
        $hashtag_feed = NULL;
        try {
            $hashtag_feed = $instagram->hashtag->getFeed(trim($hashtag->hashtag));
//            dump($hashtag_feed);
            return $hashtag_feed;
        } catch (\InstagramAPI\Exception\NotFoundException $ex) {
            return NULL;
        } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
            $ig_profile = InstagramProfile::where('insta_user_id', $instagram->account_id)->first();
            if ($ig_profile !== NULL) {
                $ig_profile->invalid_proxy = $ig_profile->invalid_proxy + 1;
            }
            return NULL;
        }
    }

    public static function validForInteraction($ig_profile) {
        if ($ig_profile->checkpoint_required == 1) {
            echo("\n[" . $ig_profile->insta_username . "] has a checkpoint.\n");
//            return false;
        }

        if ($ig_profile->account_disabled == 1) {
            echo("\n[" . $ig_profile->insta_username . "] account has been disabled.\n");
            return false;
        }

        if ($ig_profile->invalid_user == 1) {
            echo("\n[" . $ig_profile->insta_username . "] is a invalid instagram user.\n");
            return false;
        }

        if ($ig_profile->incorrect_pw == 1) {
            echo("\n[" . $ig_profile->insta_username . "] is using an incorrect password.\n");
            return false;
        }

        return true;
    }

    public static function randomizeUseHashtags(Instagram $instagram, InstagramProfile $ig_profile, $targeted_hashtags, $targeted_usernames) {
        $use_hashtags = rand(0, 1);
        if ($use_hashtags == 1 && count($targeted_hashtags) == 0) {
            $use_hashtags = 0;
        } else if ($use_hashtags == 0 && count($targeted_usernames) == 0) {
            $use_hashtags = 1;
        }

        if (count($targeted_hashtags) == 0 && count($targeted_usernames) == 0) {
            $use_hashtags = 2;
        }

        echo "[Use Hashtags] Value: " . $use_hashtags . "\n";
        return $use_hashtags;
    }

}
