<?php

namespace App;

use App\Niche;
use App\InteractionHelper;
use InstagramAPI\Instagram as Instagram;
use App\BlacklistedUsername;
use App\InstagramProfileLikeLog;

class TargetHelper {


    public static function getHashtagByNiche($ig_profile, $like_quota, Instagram $instagram) {
        $niche = Niche::find($ig_profile->niche);
        $target_hashtags = $niche->targetHashtags();
        $ig_username = $ig_profile->insta_username;

        foreach ($target_hashtags as $target_hashtag) {

            echo("\n" . "target hashtag: " . $target_hashtag->hashtag . "\n\n");

            $hashtag_feed = $instagram->hashtag->getFeed(trim($target_hashtag->hashtag));
            #$hashtag_feed = $instagram->getHashtagFeed();

            foreach ($hashtag_feed->getItems() as $item) {

                if (InstagramProfileLikeLog::where('insta_username', $ig_username)->where('target_media', $item->getId())->count() > 0) {
                    #duplicate. Liked before this photo with this id.
                    continue;
                }

                $duplicate = 0;

                $user_to_like = $item->getUser();

                if (is_null($user_to_like)) {
                    echo("\n" . "null user");
                    continue;
                }

                $followed_users = DB::connection('mysql_old')
                        ->select("SELECT log_id FROM user_insta_profile_like_log WHERE insta_username = ? AND target_username = ?;", 
                                [$ig_username, $user_to_like->getUsername()]);

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

                    $like_response = $instagram->media->like($item->getId());

                    echo("\n" . "liked " . serialize($like_response));

                    DB::connection('mysql_old')->insert("INSERT INTO user_insta_profile_like_log (insta_username, target_username, target_media, target_media_code, log) "
                            . "VALUES (?,?,?,?,?);", [$ig_username, $user_to_like->getUsername(), $item->getId(), 
                                $item->getItemUrl(), serialize($like_response)]);
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
