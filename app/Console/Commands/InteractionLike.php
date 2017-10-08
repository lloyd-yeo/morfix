<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\User;
use App\InstagramProfile;
use App\InstagramProfileTargetUsername;
use App\InstagramProfileTargetHashtag;
use App\EngagementJob;
use App\BlacklistedUsername;
use App\InstagramProfileLikeLog;
use App\LikeLogsArchive;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;
use App\Niche;
use App\InstagramHelper;

class InteractionLike extends Command {

    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interaction:like {email?} {queueasjob?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Like photos of user\'s intended targets.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        if (NULL === $this->argument("email")) { #master
            $this->line("[Likes Interaction Master] Beginning sequence to queue jobs...");

            $users = DB::table('user')
                    ->where('partition', 0)
                    ->orderBy('user_id', 'asc')
                    ->get();

            $this->dispatchJobsToEligibleUsers($users);
        } else if ($this->argument("email") !== NULL && $this->argument("queueasjob") !== NULL) {

            $this->line("[Likes Interaction Email] Queueing job for [" . $this->argument("email") . "]");

            $user = User::where('email', $this->argument("email"))->first();

            if ($user !== NULL) {

                if (($user->tier == 1 && $user->trial_activation == 1) || $user->tier > 1) {

                    $instagram_profiles = InstagramProfile::where('auto_like', true)
                            ->where('user_id', $user->user_id)
                            ->get();

                    foreach ($instagram_profiles as $ig_profile) {

                        if (!InstagramHelper::validForInteraction($ig_profile)) {
                            continue;
                        }

                        if ($ig_profile->auto_like_ban == 1 && \Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($ig_profile->next_like_time))) {
                            $this->error("[" . $ig_profile->insta_username . "] is throttled on Auto Likes & the ban isn't time yet.");
                            continue;
                        }

                        if ($ig_profile->next_like_time === NULL) {
                            $ig_profile->next_like_time = \Carbon\Carbon::now();
                            $ig_profile->save();
                            $job = new \App\Jobs\InteractionLike(\App\InstagramProfile::find($ig_profile->id));
                            $job->onQueue("likes");
                            dispatch($job);
                            $this->line("[" . $ig_profile->insta_username . "] queued for [Likes]");
                        } else if (\Carbon\Carbon::now()->gte(\Carbon\Carbon::parse($ig_profile->next_like_time))) {
                            $job = new \App\Jobs\InteractionLike(\App\InstagramProfile::find($ig_profile->id));
                            $job->onQueue("likes");
                            dispatch($job);
                            $this->line("[" . $ig_profile->insta_username . "] queued for [Likes]");
                        }
                    }
                } else {
                    $this->line("[" . $user->email . "] is not on Premium Tier or Free-Trial");
                }
            } else {
                $this->line("[" . $this->argument("email") . "] user not found.");
            }
        } else if ($this->argument("email") == "slave") {

            $this->line("[Likes Interaction Slave] Beginning sequence to queue jobs...");

            $users = DB::table('user')
                    ->orderBy('user_id', 'asc')
                    ->get();

            $this->dispatchJobsToEligibleUsers($users);
        } else {

            $this->line("[Likes Interaction Email Manual] Beginning sequence for [" . $this->argument("email") . "]");

            $user = User::where('email', $this->argument("email"))->first();

            // microtime(true) returns the unix timestamp plus milliseconds as a float
            $starttime = microtime(true);

            if ($user !== NULL) {
                $this->dispatchManualJobToUser($user);
            }

            $endtime = microtime(true);
            $timediff = $endtime - $starttime;

            echo "\nThis run took: $timediff milliseconds.\n";
        }
    }

    private function dispatchManualJobToUser($user) {
        if (($user->tier == 1 && $user->trial_activation == 1) || $user->tier > 1) {

            $instagram_profiles = InstagramProfile::where('auto_like', true)->where('user_id', $user->user_id)
                    ->get();

            foreach ($instagram_profiles as $ig_profile) {

                $this->line("[" . $ig_profile->insta_username . "] next_like_time [" . $ig_profile->next_like_time . "] [" . $ig_profile->like_quota . "]");

                if (!InstagramHelper::validForInteraction($ig_profile)) {
                    continue;
                }

                if ($ig_profile->auto_like_ban == 1 &&
                        \Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($ig_profile->next_like_time))) {
                    $this->error("[" . $ig_profile->insta_username . "] is throttled on Auto Likes & the ban isn't time yet.");
                    continue;
                }

                if ($ig_profile->next_like_time === NULL) {
                    $ig_profile->next_like_time = \Carbon\Carbon::now();
                    $ig_profile->save();
                    $job = new \App\Jobs\InteractionLike(\App\InstagramProfile::find($ig_profile->id));
                    $job->onQueue("likes");
                    $job->onConnection('sync');
                    dispatch($job);
                    $this->line("[" . $ig_profile->insta_username . "] queued for [Likes]");
                } else if (\Carbon\Carbon::now()->gte(\Carbon\Carbon::parse($ig_profile->next_like_time))) {
                    $job = new \App\Jobs\InteractionLike(\App\InstagramProfile::find($ig_profile->id));
                    $job->onQueue("likes");
                    $job->onConnection('sync');
                    dispatch($job);
                    $this->line("[" . $ig_profile->insta_username . "] queued for [Likes]");
                } else if (\Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($ig_profile->next_like_time))) {
                    $this->line("[" . $ig_profile->insta_username . "] unable to queue because of next_like_time [Likes]");
                }
            }
        } else {
            $this->line("[" . $user->email . "] is not on Premium Tier or Free-Trial");
        }
    }

    private function dispatchJobsToEligibleUsers($users) {
        foreach ($users as $user) {

            if (($user->tier == 1 && $user->trial_activation == 1) || $user->tier > 1) {

                $instagram_profiles = InstagramProfile::where('auto_like', true)->where('user_id', $user->user_id)
                        ->get();

                foreach ($instagram_profiles as $ig_profile) {

                    if (!InstagramHelper::validForInteraction($ig_profile)) {
                        continue;
                    }

                    if ($ig_profile->auto_like_ban == 1 && \Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($ig_profile->next_like_time))) {
                        $this->error("[" . $ig_profile->insta_username . "] is throttled on Auto Likes & the ban isn't time yet.");
                        continue;
                    }

                    if ($ig_profile->next_like_time === NULL) {
                        $ig_profile->next_like_time = \Carbon\Carbon::now();
                        $ig_profile->save();
                        $job = new \App\Jobs\InteractionLike(\App\InstagramProfile::find($ig_profile->id));
                        $job->onQueue("likes");
                        dispatch($job);
                        $this->line("[" . $ig_profile->insta_username . "] queued for [Likes]");
                    } else if (\Carbon\Carbon::now()->gte(\Carbon\Carbon::parse($ig_profile->next_like_time))) {
                        $job = new \App\Jobs\InteractionLike(\App\InstagramProfile::find($ig_profile->id));
                        $job->onQueue("likes");
                        dispatch($job);
                        $this->line("[" . $ig_profile->insta_username . "] queued for [Likes]");
                    }
                }
            } else {
                $this->line("[" . $user->email . "] is not on Premium Tier or Free-Trial");
            }
        }
    }

    public function jobHandle($user, $ig_profile) {

        $this->line($ig_profile->insta_username . "\t" . $ig_profile->insta_pw);

        $ig_username = $ig_profile->insta_username;
        $ig_password = $ig_profile->insta_pw;

//        $config = array();
//        $config["storage"] = "mysql";
//        $config["pdo"] = DB::connection('mysql_igsession')->getPdo();
//        $config["dbtablename"] = "instagram_sessions";
//
//        $debug = false;
//        $truncatedDebug = false;
//        $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);

        $instagram = InstagramHelper::initInstagram();

        if ($ig_profile->proxy === NULL) {
            $proxy = Proxy::inRandomOrder()->first();
            $ig_profile->proxy = $proxy->proxy;
            $ig_profile->save();
            $proxy->assigned = $proxy->assigned + 1;
            $proxy->save();
        }

        $instagram->setProxy($ig_profile->proxy);

        try {
            $like_quota = rand(1, 3);
//            $instagram->setUser($ig_username, $ig_password);
//            $explorer_response = $instagram->login();
            $explorer_response = $instagram->login($ig_username, $ig_password);
            $this->line("[$ig_username] Logged in \t Round-Quota: " . $like_quota);

            /*
             * If user is free tier & not on trial / run out of quota then break.
             */
            if ((!($user->tier > 1 || $user->trial_activation == 1)) || !($like_quota > 0)) {
                $this->line("[$ig_username] User is free tier & not on trial / run out of quota then break.");
                return;
            } else {
                $this->line("[$ig_username] beginning LIKE sequence.");
            }

            /*
             * Defined target usernames take precedence.
             */
            $target_usernames = InstagramProfileTargetUsername::where('insta_username', $ig_username)
                            ->where('invalid', 0)
                            ->where('insufficient_followers', 0)
                            ->inRandomOrder()->get();

            $this->line("[$ig_username] retrieved " . count($target_usernames) . " user-defined usernames.");

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
                            if ($target_response->user->follower_count < 15000) {
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

                        if (strpos($insta_ex->getMessage(), 'Throttled by Instagram because of too many API requests') !== false) {
                            $ig_profile->next_like_time = \Carbon\Carbon::now()->addHours(2);
                            $ig_profile->save();
                            echo "\n[$ig_username] has next_like_time shifted forward to " . \Carbon\Carbon::now()->addHours(2)->toDateTimeString() . "\n";
                            echo "\nTerminating...";
                            exit;
                        }
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
                                            ->get();

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
                                            ->get();

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
                                            if (BlacklistedUsername::where('username', $user_to_like->username)->count() == 0) {
                                                $blacklist_username = new BlacklistedUsername;
                                                $blacklist_username->username = $user_to_like->username;
                                                $blacklist_username->save();
                                                echo("\n" . "Blacklisted: " . $user_to_like->username);
                                            } else {
                                                echo("\n" . "Is a blacklisted username.");
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
                                                    ->get();

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

            if ($like_quota > 0) {

                /*
                 * Next is to get user-defined hashtags.
                 */
                $target_hashtags = InstagramProfileTargetHashtag::where('insta_username', $ig_username)
                        ->get();

                $target_hashtags = $target_hashtags->shuffle();

                //Foreach targeted hashtags
                foreach ($target_hashtags as $target_hashtag) {
                    if ($like_quota > 0) {
                        $this->info("[$ig_username] Target Hashtag: " . $target_hashtag->hashtag . "\n\n");
                        //Get the feed from the targeted hashtag.
                        #$hashtag_feed = $instagram->getHashtagFeed(trim($target_hashtag->hashtag));
                        $hashtag_feed = $instagram->hashtag->getFeed(trim($target_hashtag->hashtag));
                        //Foreach post under this target hashtag
                        foreach ($hashtag_feed->items as $item) {
                            //Get user because we need to check if they have been liked before.
                            $user_to_like = $item->user;
                            //Weird error, null user. Check to be safe.

                            if (is_null($user_to_like)) {
                                $this->error("null user");
                                continue;
                            }

                            //Check for duplicates.
                            $liked_users = InstagramProfileLikeLog::where('insta_username', $ig_username)
                                    ->where('target_username', $user_to_like->username)
                                    ->get();

                            //Duplicate = liked before.
                            if (count($liked_users) > 0) {
                                $this->info("duplicate log found:\t[$ig_username] [" . $user_to_like->username . "]");
                                continue;
                            }

                            //Check for duplicates.
                            $liked_users = LikeLogsArchive::where('insta_username', $ig_username)
                                    ->where('target_username', $user_to_like->username)
                                    ->get();

                            //Duplicate = liked before.
                            if (count($liked_users) > 0) {
                                $this->info("Duplicate Log Found:\t[$ig_username] [" . $user_to_like->username . "]");
                                continue;
                            }

                            if ($like_quota > 0) {

                                $like_response = $instagram->media->like($item->id);

                                if ($like_response->status == "ok") {

                                    $this->info("[$ig_username] Liked " . serialize($like_response));

                                    $like_log = new InstagramProfileLikeLog;
                                    $like_log->insta_username = $ig_username;
                                    $like_log->target_username = $user_to_like->username;
                                    $like_log->target_media = $item->id;
                                    $like_log->target_media_code = $item->getItemUrl();
                                    $like_log->log = serialize($like_response);
                                    if ($like_log->save()) {
                                        $this->info("[$ig_username] Saved!");
                                    }
                                    $like_quota--;
                                }
                            } else {
                                return;
                            }
                        }
                    } else {
                        return;
                    }
                }
            } else {
                return;
            }

            /**
             * Next is to get target usernames by niche
             */
            if ($like_quota > 0) {

                $niche = Niche::find($ig_profile->niche);
                $niche_targets = $niche->targetUsernames();
                $this->line("[$ig_username] retrieved " . count($niche_targets) . " niche usernames.");

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
                                                ->get();

                                        //Duplicate = liked before.
                                        if (count($liked_users) > 0) {
                                            echo("\n" . "[Current] Duplicate Log Found:\t[$ig_username] [" . $user_to_like->username . "]");
                                            continue;
                                        }

                                        //Check for duplicates.
                                        $liked_users = LikeLogsArchive::where('insta_username', $ig_username)
                                                ->where('target_username', $user_to_like->username)
                                                ->get();

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
                                                        ->get();

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
            }

            /**
             * Next is to get target hashtags by niche
             */
            if ($like_quota > 0) {

                $niche = Niche::find($ig_profile->niche);
                $target_hashtags = $niche->targetHashtags();

                foreach ($target_hashtags as $target_hashtag) {

                    $this->info("target hashtag: " . $target_hashtag->hashtag . "\n\n");

                    $hashtag_feed = $instagram->hashtag->getFeed(trim($target_hashtag->hashtag));

                    foreach ($hashtag_feed->items as $item) {

                        $duplicate = 0;

                        $user_to_like = $item->user;

                        if (is_null($user_to_like)) {
                            $this->error("null user");
                            continue;
                        }

                        $followed_users = DB::connection('mysql_old')
                                ->select("SELECT log_id FROM user_insta_profile_like_log WHERE insta_username = ? AND target_username = ?;", [$ig_username, $user_to_like->username]);

                        foreach ($followed_users as $followed_user) {
                            $duplicate = 1;
                            $this->info("duplicate log found:\t" . $followed_user->log_id);
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

                            $this->info("liked " . serialize($like_response));

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
            }
        } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
            $this->error("checkpt\t" . $checkpoint_ex->getMessage());
            DB::connection('mysql_old')->update('update user_insta_profile set checkpoint_required = 1 where id = ?;', [$ig_profile->id]);
            return;
        } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
            $this->error("network\t" . $network_ex->getMessage());
            DB::connection('mysql_old')->update('update user_insta_profile set error_msg = ? where id = ?;', [$network_ex->getMessage(), $ig_profile->id]);
            return;
        } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
            $this->error("endpt\t" . $endpoint_ex->getMessage());
            if ($endpoint_ex->getMessage() === "InstagramAPI\Response\LoginResponse: The username you entered doesn't appear to belong to an account. Please check your username and try again.") {
                DB::connection('mysql_old')->update('update user_insta_profile set error_msg = ? where id = ?;', [$endpoint_ex->getMessage(), $ig_profile->id]);
            }
            return;
        } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
            $this->error("incorrectpw\t" . $incorrectpw_ex->getMessage());
            DB::connection('mysql_old')->update('update user_insta_profile set incorrect_pw = 1, error_msg = ? where id = ?;', [$incorrectpw_ex->getMessage(), $ig_profile->id]);
            return;
        } catch (\InstagramAPI\Exception\FeedbackRequiredException $feedback_ex) {
            $this->error("feedback\t" . $feedback_ex->getMessage());
            DB::connection('mysql_old')->update('update user_insta_profile set invalid_proxy = 1, error_msg = ? where id = ?;', [$feedback_ex->getMessage(), $ig_profile->id]);
            return;
        } catch (\InstagramAPI\Exception\EmptyResponseException $emptyresponse_ex) {
            return;
        } catch (\InstagramAPI\Exception\AccountDisabledException $acctdisabled_ex) {
            $this->error("acctdisabled\t" . $acctdisabled_ex->getMessage());
            DB::connection('mysql_old')->update('update user_insta_profile set invalid_user = 1, error_msg = ? where id = ?;', [$acctdisabled_ex->getMessage(), $ig_profile->id]);
            return;
        }
    }

}
