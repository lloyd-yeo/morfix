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
use App\TargetHelper;

class InteractionFollow implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $profile;
    protected $instagram;
    protected $targeted_hashtags;
    protected $targeted_usernames;
    protected $followed;

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

        $follow_mode = InteractionFollowHelper::setFollowMode($this->profile);
        $this->targeted_hashtags = TargetHelper::getUserTargetedHashtags($this->profile);
        $this->targeted_usernames = TargetHelper::getUserTargetedUsernames($this->profile);

        echo "[" . $this->profile->insta_username . "] Niche: " . $this->profile->niche .
        " Auto_Follow: " . $this->profile->auto_follow .
        " Auto_Unfollow: " . $this->profile->auto_unfollow . "\n";


        if ($follow_mode > 0) { //unfollow segment
            //check quota first
            if ($this->profile->unfollow_quota > 0) {

                echo "[" . $this->profile->insta_username . "] beginning unfollowing sequence.\n";
                $this->initInstagramAPI($this->profile);

                if ($follow_mode === 2) { //forced unfollow, add users to unfollow
                }

                $users_to_unfollow = InstagramProfileFollowLog::where('insta_username', $this->profile->insta_username)
                        ->where('unfollowed', false)
                        ->where('follow', true)
                        ->orderBy('date_inserted', 'asc')
                        ->take(2)
                        ->get();

                foreach ($users_to_unfollow as $user_to_unfollow) {

                    echo "[" . $this->profile->insta_username . "] retrieved: "
                    . $user_to_unfollow->follower_username . "\n";

                    $unfollowed = InteractionFollowHelper::unfollow($this->profile, $this->instagram, $user_to_unfollow);

                    if ($unfollowed === 2) {
                        continue;
                    } else if ($unfollowed <= 1) {
                        return;
                    }
                }
            } else {
                echo "[" . $this->profile->insta_username . "] does not have enough <unfollow_quota> left. \n\n";
            }
        } else if ($follow_mode === 0) { //follow segment
            $throttle_limit = 40;
            $throttle_count = 0;
            //check quota first
            if ($this->profile->follow_quota > 0) {

                echo "[" . $this->profile->insta_username . "] beginning following sequence.\n";
                $this->initInstagramAPI($this->profile);
                $use_hashtags = InstagramHelper::randomizeUseHashtags($this->instagram, $this->profile, $this->targeted_hashtags, $this->targeted_usernames);

                if ($use_hashtags == 0) {
                    //use targeted usernames
                    foreach ($this->targeted_usernames as $target_username) {

                        echo "[" . $this->profile->insta_username . "] using target username: " . $target_username->target_username . "\n";
                        $username_id = InstagramHelper::getUserIdForName($this->instagram, $target_username);
                        if ($username_id === NULL) {
                            continue;
                        }

                        $users_to_follow = InstagramHelper::getTargetUsernameFollowers($this->instagram, $target_username, $username_id);

                        foreach ($users_to_follow as $user_to_follow) {

                            if ($throttle_limit < $throttle_count) {
                                return;
                            }

                            $throttle_count++;
                            $valid_user = 0;
                            $valid_user = InteractionFollowHelper::isProfileValidForFollow($this->instagram, $this->profile, $user_to_follow);

//                            if ($valid_user == 2) {
//                                echo "[" . $this->profile->insta_username . "] encountered an exception.\n";
//                                return;
//                            } else 
                            if ($valid_user) {
                                $this->followed = InteractionFollowHelper::follow($this->instagram, $this->profile, $user_to_follow);
                                if ($this->followed === 0) {
                                    return;
                                } else if ($this->followed === 1) {
                                    return;
                                } else if ($this->followed === 2) {
                                    continue;
                                }
                            } else if (!$valid_user) {
                                echo "[" . $user_to_follow->username . "] is invalid.\n";
                                continue;
                            }
                        }
                    }
                } else if ($use_hashtags == 1) {
                    //use targeted hashtags
                    foreach ($this->targeted_hashtags as $target_hashtag) {
                        echo "[" . $this->profile->insta_username . "] using hashtag: " . $target_hashtag->hashtag . "\n";
                        $hashtag_feed = InstagramHelper::getHashtagFeed($this->instagram, $target_hashtag);
                        if ($hashtag_feed !== NULL) {
                            foreach ($hashtag_feed->items as $item) {
                                if ($throttle_limit < $throttle_count) {
                                    return;
                                }
                                $throttle_count++;
                                $user_to_follow = $item->user;
                                if (InteractionFollowHelper::isProfileValidForFollow($this->instagram, $this->profile, $user_to_follow)) {
                                    $this->followed = InteractionFollowHelper::follow($this->instagram, $this->profile, $user_to_follow);
                                    if ($this->followed === 0) {
                                        return;
                                    } else if ($this->followed === 1) {
                                        return;
                                    } else if ($this->followed === 2) {
                                        continue;
                                    }
                                } else {
                                    continue;
                                }
                            }
                        }
                    }
                } else if ($use_hashtags == 2) {
                    //use niche targets
                    $niche_usernames = Niche::find($this->profile->niche)->targetUsernames();
                    foreach ($niche_usernames as $target_username) {
                        echo "[" . $this->profile->insta_username . "] using NICHE target username: " . $target_username->target_username . "\n";
                        $username_id = InstagramHelper::getUserIdForName($this->instagram, $target_username);
                        if ($username_id === NULL) {
                            continue;
                        }
                        $users_to_follow = InstagramHelper::getTargetUsernameFollowers($this->instagram, $target_username, $username_id);
                        foreach ($users_to_follow as $user_to_follow) {
                            if ($throttle_limit < $throttle_count) {
                                return;
                            }
                            $throttle_count++;
                            if (InteractionFollowHelper::isProfileValidForFollow($this->instagram, $this->profile, $user_to_follow)) {
                                $this->followed = InteractionFollowHelper::follow($this->instagram, $this->profile, $user_to_follow);
                                if ($this->followed === 0) {
                                    return;
                                } else if ($this->followed === 1) {
                                    return;
                                } else if ($this->followed === 2) {
                                    continue;
                                }
                            } else {
                                continue;
                            }
                        }
                    }

                    if ($this->followed === 0) {
                        return;
                    } else if ($this->followed === 1) {
                        return;
                    }

                    $niche_hashtags = Niche::find($this->profile->niche)->targetHashtags();
                    foreach ($niche_hashtags as $target_hashtag) {
                        echo "[" . $this->profile->insta_username . "] using hashtag: " . $target_hashtag->hashtag . "\n";
                        $hashtag_feed = InstagramHelper::getHashtagFeed($this->instagram, $target_hashtag);
                        if ($hashtag_feed !== NULL) {
                            foreach ($hashtag_feed->items as $item) {
                                if ($throttle_limit < $throttle_count) {
                                    return;
                                }
                                $throttle_count++;
                                $user_to_follow = $item->user;
                                if (InteractionFollowHelper::isProfileValidForFollow($this->instagram, $this->profile, $user_to_follow)) {
                                    $this->followed = InteractionFollowHelper::follow($this->instagram, $this->profile, $user_to_follow);
                                    if ($this->followed === 0) {
                                        return;
                                    } else if ($this->followed === 1) {
                                        return;
                                    } else if ($this->followed === 2) {
                                        continue;
                                    }
                                } else {
                                    continue;
                                }
                            }
                        }
                    }
                }
            } else {
                echo "[" . $this->profile->insta_username . "] does not have enough <follow_quota> left. \n\n";
            }
        }
    }

    public function initInstagramAPI($ig_profile) {
        $this->instagram = InstagramHelper::initInstagram();
        if (!InstagramHelper::login($this->instagram, $ig_profile)) {
            exit();
        }
    }

}
