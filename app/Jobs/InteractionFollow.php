<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\User;
use App\InstagramProfile;
use App\InstagramProfileFollowLog;
use App\InstagramProfileTargetHashtag;
use App\InstagramProfileTargetUsername;
use App\Niche;
use App\NicheTarget;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;
use App\InstagramHelper;
use App\InteractionFollowHelper;

class InteractionFollow implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $profile;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 480;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\InstagramProfile $profile) {
        $this->profile = $profile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        DB::reconnect();

        $ig_profile = $this->profile;

        echo($ig_profile->insta_username . "\t" . $ig_profile->insta_pw . "\n");

        $ig_username = $ig_profile->insta_username;
        $ig_password = $ig_profile->insta_pw;

        $insta_username = $ig_profile->insta_username;
        $insta_user_id = $ig_profile->insta_user_id;
        $insta_id = $ig_profile->insta_id;
        $insta_pw = $ig_profile->insta_pw;
        $niche = $ig_profile->niche;
        $next_follow_time = $ig_profile->next_follow_time;
        $unfollow = $ig_profile->unfollow;
        $follow_cycle = $ig_profile->follow_cycle;
        $auto_unfollow = $ig_profile->auto_unfollow;
        $auto_follow = $ig_profile->auto_follow;
        $auto_follow_ban = $ig_profile->auto_follow_ban;
        $auto_follow_ban_time = $ig_profile->auto_follow_ban_time;
        $follow_unfollow_delay = $ig_profile->follow_unfollow_delay;
        $speed = $ig_profile->speed;
        $follow_min_follower = $ig_profile->follow_min_follower;
        $follow_max_follower = $ig_profile->follow_max_follower;
        $unfollow_unfollowed = $ig_profile->unfollow_unfollowed;
        $follow_quota = $ig_profile->follow_quota;
        $unfollow_quota = $ig_profile->unfollow_quota;
        $proxy = $ig_profile->proxy;

        $followed_logs = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)
                ->where('follow', 1)
                ->where('unfollowed', 0)
                ->get();

        $followed_count = count($followed_logs);

        echo "[" . $ig_profile->insta_username . "] number of follows: " . $followed_count . "\n";

        if (($followed_count >= $ig_profile->follow_cycle) && $ig_profile->auto_unfollow = 1) {
            $ig_profile->unfollow = 1;
            $unfollow = 1;
            $ig_profile->save();
            $ig_profile = InstagramProfile::find($ig_profile->id);
        }

        $target_username = "";
        $target_follow_username = "";
        $target_follow_id = "";
        $custom_target_defined = false;

        echo "[" . $insta_username . "] Niche: " . $niche . " Auto_Follow: " . $auto_follow . " Auto_Unfollow: " . $auto_unfollow . "\n";

        if ($unfollow == 1) {
            echo "[" . $insta_username . "] will be unfollowing this round.\n";
        } else {
            echo "[" . $insta_username . "] will be following this round.\n";
        }

        /*
            Set Speed Delay
        */
        $follow_unfollow_delay = InteractionFollowHelper::setSpeedDelay($speed);
        $delay = rand($follow_unfollow_delay, $follow_unfollow_delay + 2); //randomize the delay to escape detection from IG.
        
        //go into unfollowing mode if user is entirely on unfollow OR on the unfollowing cycle.
        if (($auto_unfollow == 1 && $auto_follow == 0) || ($auto_follow == 1 && $auto_unfollow == 1 && $unfollow == 1)) {

            if ($unfollow_quota < 1) {
                echo "[" . $insta_username . "] has reached quota for unfollowing today.\n";
                exit();
            }

            echo "[" . $insta_username . "] beginning unfollowing sequence.\n";
            
            DB::reconnect();
            
            $instagram = InstagramHelper::initInstagram();
            
            if (!InstagramHelper::login($instagram, $ig_profile)) {
                exit();
            }
            
            $current_log_id = "";
            $current_user_to_unfollow = NULL;
            
            try {
                $ig_username = $insta_username;
                $ig_password = $insta_pw;
                
                //[get users to UNFOLLOW]
                $users_to_unfollow = InstagramProfileFollowLog::where('insta_username', $ig_username)
                        ->where('unfollowed', false)
                        ->where('follow', true)
                        ->orderBy('date_inserted', 'asc')
                        ->take(2)
                        ->get();

                if (count($users_to_unfollow) == 0) {
                    InteractionFollowHelper::unfollowUserIsEmpty($ig_profile, $instagram);
                } else {
                    InteractionFollowHelper::unFollowUsers($ig_profile, $instagram, $users_to_unfollow);
                }
                //[END get users to UNFOLLOW]
            } catch (\InstagramAPI\Exception\InstagramException $insta_ex) {
                InteractionFollowHelper::handleInstragramException($ig_profile, $insta_ex, $current_log_id);
            } 
        } else if (($unfollow == 0 && $auto_follow == 1) || ($auto_follow == 1 && $auto_unfollow == 0)) {

            if ($follow_quota < 1) {
                echo "[" . $insta_username . "] has reached quota for following today.\n";
                exit();
            }

            echo "[" . $insta_username . "] beginning following sequence.\n";
            DB::reconnect();
            
            $instagram = InstagramHelper::initInstagram();
            
            if (!InstagramHelper::login($instagram, $ig_profile)) {
                exit();
            }
            

            $ig_username = $insta_username;
            $ig_password = $insta_pw;
            
            //start with targeted usernames/hashtags
            $use_hashtags = rand(0, 1);
            echo "[" . $insta_username . "] random use hashtag: $use_hashtags\n";
            $target_hashtags = NULL;
            $target_usernames = NULL;

            if ($use_hashtags == 1) {
                $target_hashtags = InstagramProfileTargetHashtag::where('insta_username', $insta_username)->get();
                $target_hashtags->shuffle();
                if (count($target_hashtags) == 0) {
                    $target_usernames = InstagramProfileTargetUsername::where('insta_username', $insta_username)->get();
                    $target_usernames->shuffle();
                    $use_hashtags = 0;
                }
            } else if ($use_hashtags == 0) {
                $target_usernames = InstagramProfileTargetUsername::where('insta_username', $insta_username)->get();
                $target_usernames->shuffle();
                if (count($target_usernames) == 0) {
                    $target_hashtags = InstagramProfileTargetHashtag::where('insta_username', $insta_username)->get();
                    $target_hashtags->shuffle();
                    if (count($target_hashtags) > 0) {
                        $use_hashtags = 1;
                    } else {
                        $use_hashtags = 0;
                    }
                }
            }

            echo "[" . $insta_username . "] AFTER random use hashtag: $use_hashtags\n";
            echo "[" . $insta_username . "] [target_hashtag_size: " . count($target_hashtags) . "] [target_usernames_size: " . count($target_usernames) . "] [niche: " . $niche . "]\n";
            $followed = 0;
            $throttle_limit = 41;

            if ($use_hashtags == 1 && count($target_hashtags) > 0) {
                InteractionFollowHelper::useHashtagsIsOne($ig_profile, $instagram, $target_hashtags);
            } else if ($use_hashtags == 0 && count($target_usernames) > 0) {
                InteractionFollowHelper::useHashtagsIsZero($ig_profile, $instagram, $target_hashtags);
            } else if ($use_hashtags == 0 && count($target_usernames) == 0 && count($target_hashtags) == 0) {
                InteractionFollowHelper::allIsZero($ig_profile, $instagram);
            }
        }
    }

}
