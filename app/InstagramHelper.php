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

    public static function login(Instagram $instagram, InstagramProfile $ig_profile) {

        echo("Verifying proxy for profile: [" . $ig_profile->insta_username . "]\n");

        InstagramHelper::verifyAndReassignProxy($ig_profile);

        $instagram->setProxy($ig_profile->proxy);

        echo("Logging in profile: [" . $ig_profile->insta_username . "] [" . $ig_profile->insta_pw . "]\n");

        try {
            $explorer_response = $instagram->login($ig_profile->insta_username, $ig_profile->insta_pw);
            return true;
        } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
            $ig_profile->checkpoint_required = 1;
            $ig_profile->save();
            return false;
        } catch (\InstagramAPI\Exception\InvalidUserException $invalid_user_ex) {
            $ig_profile->invalid_user = 1;
            $ig_profile->save();
            return false;
        } catch (\InstagramAPI\Exception\NetworkException $network_ex) {

            InstagramHelper::verifyAndReassignProxy($ig_profile);

            try {
                $instagram->login($ig_profile->insta_username, $ig_profile->insta_pw);
            } catch (\InstagramAPI\Exception\InstagramException $login_ex) {
                return false;
            }
            
        } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
            
        } catch (\InstagramAPI\Exception\BadRequestException $badrequest_ex) {
            
        } catch (\InstagramAPI\Exception\ForcedPasswordResetException $forcedpwreset_ex) {
            $ig_profile->incorrect_pw = 1;
            $ig_profile->save();
            return false;
        } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
            $ig_profile->incorrect_pw = 1;
            $ig_profile->save();
            return false;
        } catch (\InstagramAPI\Exception\AccountDisabledException $accountdisabled_ex) {
            $ig_profile->invalid_user = 1;
            $ig_profile->save();
            return false;
        } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
            $ig_profile->incorrect_pw = 1;
            $ig_profile->save();
            return false;
        }
    }

    public static function verifyAndReassignProxy(InstagramProfile $ig_profile) {
        if ($ig_profile->proxy === NULL) {
            $proxy = Proxy::inRandomOrder()->first();
            $ig_profile->proxy = $proxy->proxy;
            $ig_profile->save();
            $proxy->assigned = $proxy->assigned + 1;
            $proxy->save();
        }
    }

}
