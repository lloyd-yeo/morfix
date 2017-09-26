<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use App\InstagramProfile;
use App\InstagramProfileTargetUsername;
use App\InstagramProfileTargetHashtag;
use App\EngagementJob;
use App\BlacklistedUsername;
use App\InstagramProfileLikeLog;
use App\LikeLogsArchive;
use App\Proxy;
use App\Niche;
use App\InstagramHelper;
use App\InteractionHelper;
use App\TargetHelper;

class InteractionLike implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

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
    public $timeout = 360;
    
    protected $profile;
    protected $targeted_hashtags;
    protected $targeted_usernames;
    protected $like_quota;
    protected $speed_delay;
    protected $instagram;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(InstagramProfile $profile) {
        $this->profile = $profile;
        $this->calcSpeedDelay($profile->speed);
        $this->instagram = InstagramHelper::initInstagram();
        $this->like_quota = rand(1, 3);
        $this->targeted_hashtags = TargetHelper::getUserTargetedHashtags($profile);
        $this->targeted_usernames = TargetHelper::getUserTargetedUsernames($profile);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        DB::reconnect();

        $ig_profile = $this->profile;

        $ig_username = $ig_profile->insta_username;

        $instagram = $this->instagram;

        if (InstagramHelper::login($instagram, $ig_profile)) {

            $use_hashtags = $this->randomizeUseHashtags();

            if (!$use_hashtags) {

                foreach ($this->target_usernames as $target_username) {

                    if ($this->like_quota > 0) {

                        //Get followers of the target.
                        echo("\n" . "[$ig_username] Target Username: " . $target_username->target_username . "\n");
                        $target_username_id = $this->checkValidTargetUsername($instagram, $target_username);
                        if ($target_username_id === NULL) {
                            continue;
                        }

                        $target_target_username = $target_username->target_username;
                        $user_follower_response = NULL;
                        $next_max_id = null;

                        $page_count = 0;

                        do {
                            echo "\n[$ig_username] requesting [$target_target_username] with: " . $next_max_id . "\n";

                            $user_follower_response = $instagram->people->getFollowers($target_username_id, NULL, $next_max_id);
                            $target_user_followings = $user_follower_response->users;
                            $next_max_id = $user_follower_response->next_max_id;
                            echo "\n[$ig_username] next_max_id for [$target_target_username] is " . $next_max_id . "\n";
                            $page_count++;

                            //Foreach follower of the target.
                            foreach ($target_user_followings as $user_to_like) {

                                if ($this->like_quota > 0) {

                                    echo("\n" . $user_to_like->username . "\t" . $user_to_like->pk);

                                    $is_duplicate = $this->checkBlacklistAndDuplicate($user_to_like, $page_count);

                                    if ($is_duplicate == 1) {
                                        break;
                                    } else if ($is_duplicate == 2) {
                                        continue;
                                    }

                                    //Get the feed of the user to like.
                                    $user_feed_response = InstagramHelper::getUserFeed($instagram, $user_to_like);
                                    if ($user_feed_response === NULL) {
                                        continue;
                                    }

                                    //Get the media posted by the user.
                                    $user_items = $user_feed_response->items;

                                    //Foreach media posted by the user.
                                    foreach ($user_items as $item) {
                                        if ($this->checkDuplicateByMediaId($item)) {
                                            continue;
                                        }
                                        if ($this->like_quota > 0) {
                                            if (!$this->like($user_to_like, $item)) {
                                                continue;
                                            }
                                        } else {
                                            break;
                                        }
                                    }
                                } else {
                                    break;
                                }
                            }
                        } while ($next_max_id !== NULL && $this->like_quota > 0);
                    }
                }
            } else {
                
            }
        }
    }

    public function like($user_to_like, $item) {
        $ig_profile = $this->profile;
        $like_response = $this->instagram->media->like($item->id);
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
                    $this->like_quota--;
                    $ig_profile->next_like_time = \Carbon\Carbon::now()->addMinutes($this->speed_delay);
                    $ig_profile->auto_like_ban = 0;
                    $ig_profile->auto_like_ban_time = NULL;
                    $ig_profile->save();
                    return true;
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

    public function checkDuplicateByMediaId($item) {
        $ig_profile = $this->profile;
        
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

    public function checkBlacklistAndDuplicate($user_to_like, $page_count) {
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

    public function checkValidTargetUsername($instagram, $target_username) {
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

    public function handleInstagramException($ig_profile, $ex) {
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
                } else if (strpos($feedback_required_response->fullResponse->feedback_message, 'It looks like your profile contains a link that is not allowed') !== false) {
                    $ig_profile->next_like_time = \Carbon\Carbon::now()->addHours(1);
                    $ig_profile->invalid_proxy = 1;
                    $ig_profile->save();
                    echo "\n[$ig_username] has invalid proxy & next_like_time shifted forward to " . \Carbon\Carbon::now()->addHours(1)->toDateTimeString() . "\n";
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

    private function calcSpeedDelay($speed) {
        $speed_delay = 3;
        if ($speed == "Fast") {
            $speed_delay = 1;
        }
        $this->speed_delay = $speed_delay;
    }

}
