<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Database\Query\Builder;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\User;
use App\InstagramProfile;
use App\InstagramProfileComment;
use App\InstagramProfileCommentLog;
use App\InstagramProfileFollowLog;
use App\InstagramProfileLikeLog;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;

class InteractionComment extends Command {

    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interaction:comment {email?} {partition?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comment on target user\'s photos.';

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

        $users = array();

        if ($this->argument("email") == "slave") {

            $partition = $this->argument('partition');

            $this->info("[Comment Interaction] queueing jobs for slave " . $partition)

            $users = User::where("email", $this->argument("email"))
                    ->where('partition', $partition)
                    ->get();

            foreach ($users as $user) {

                if ($user->tier > 2) {

                    $instagram_profiles = array();

                    $instagram_profiles = InstagramProfile::where('auto_comment', true)
                            ->where('email', $user->email)
                            ->where('incorrect_pw', false)
                            ->where('partition', $partition)
                            ->get();

                    foreach ($instagram_profiles as $ig_profile) {
                        if (\Carbon\Carbon::now()->gte(new \Carbon\Carbon($ig_profile->next_comment_time))) {
                            dispatch((new \App\Jobs\InteractionComment(\App\InstagramProfile::find($ig_profile->id)))->onQueue('comments'));
                            $this->line("queued profile: " . $ig_profile->insta_username);
                            continue;
                        }
                    }
                }
            }
        } else if (NULL !== $this->argument("email")) {

            $user = User::where("email", $this->argument("email"))->first();

            $instagram_profiles = InstagramProfile::where('auto_comment', true)
                    ->where('email', $user->email)
                    ->where('incorrect_pw', false)
                    ->get();

            jobHandle($instagram_profiles);
        } else {

            foreach (User::cursor() as $user) {

                if ($user->tier > 2) {
                    $instagram_profiles = array();
                    
                    $instagram_profiles = InstagramProfile::where('auto_comment', true)
                                ->where('email', $user->email)
                                ->where('incorrect_pw', false)
                                ->where('partition', 0)
                                ->get();
                    
                    if (count($instagram_profiles) > 0) {
                        foreach ($instagram_profiles as $ig_profile) {
                            if (\Carbon\Carbon::now()->gte(new \Carbon\Carbon($ig_profile->next_comment_time))) {
                                dispatch((new \App\Jobs\InteractionComment(\App\InstagramProfile::find($ig_profile->id)))->onQueue('comments'));
                                $this->line("queued profile: " . $ig_profile->insta_username);
                                continue;
                            }
                        }
                    }
                }
            }
        }
    }

}

function jobHandle($instagram_profiles) {

    foreach ($instagram_profiles as $ig_profile) {

        echo($ig_profile->insta_username . "\t" . $ig_profile->insta_pw . "\n");

        $ig_username = $ig_profile->insta_username;
        $ig_password = $ig_profile->insta_pw;

        $config = array();
        $config['pdo'] = DB::connection('mysql_igsession')->getPdo();
        $config["dbtablename"] = "instagram_sessions";
        $config["storage"] = "mysql";

        $debug = false;
        $truncatedDebug = false;
        $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);

        if ($ig_profile->proxy === NULL) {
            $proxy = Proxy::inRandomOrder()->first();
            $ig_profile->proxy = $proxy->proxy;
            $ig_profile->save();
            $proxy->assigned = $proxy->assigned + 1;
            $proxy->save();
        }

        $instagram->setProxy($ig_profile->proxy);
        $instagram->setUser($ig_username, $ig_password);

        try {
            $instagram->login();
            echo "[$ig_username] logged in.\n";
        } catch (\InstagramAPI\Exception\NetworkException $network_ex) {

            $proxy = Proxy::inRandomOrder()->first();
            $ig_profile->proxy = $proxy->proxy;
            $ig_profile->save();
            $proxy->assigned = $proxy->assigned + 1;
            $proxy->save();
            $instagram->setProxy($ig_profile->proxy);
            $instagram->login();

            var_dump($network_ex);
        } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
            $ig_profile->incorrect_pw = 1;
            $ig_profile->save();
            continue;
        }

        try {

            $comment = InstagramProfileComment::where('insta_username', $ig_username)
                    ->inRandomOrder()
                    ->first();

            if ($comment === NULL) {
                continue;
            }

            echo($comment->comment . "\n");
            $commentText = $comment->comment;

            $commented = false;

            $user_instagram_id = NULL;

            $unengaged_followings = InstagramProfileFollowLog::where('insta_username', $ig_username)
                    ->orderBy('date_inserted', 'desc')
                    ->take(20)
                    ->get();

            echo "[$ig_username] Number of unengaged followings " . count($unengaged_followings) . "\n";

            $real_unengaged_followings_count = 0;

            foreach ($unengaged_followings as $unengaged_following) {
                if (InstagramProfileCommentLog::where('insta_username', $unengaged_following->insta_username)
                                ->where('target_username', $unengaged_following->follower_username)
                                ->count() > 0) {
                    echo("[Initial Check][$ig_username] has engaged before " . $unengaged_following->follower_username . "\n");
                    continue;
                }
                $real_unengaged_followings_count++;
            }

            echo "[$ig_username] real unengaged followings count = $real_unengaged_followings_count \n";

            if (count($unengaged_followings) < 1 || $real_unengaged_followings_count == 0) {

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

                    try {
                        $user_instagram_id = $instagram->people->getUserIdForName($unengaged_liking->target_username);
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



                            if (InstagramProfileCommentLog::where('insta_username', $ig_username)
                                            ->where('target_media', $item->id)->count() == 0) {

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
                            } else {
                                echo("[$ig_username] has commented on [" . $item->getItemUrl() . "] before.\n");
                            }
                        }
                    }

                    if ($commented) {
                        break;
                    }
                }
            } else {
                foreach ($unengaged_followings as $unengaged_following) {

                    if (InstagramProfileCommentLog::where('insta_username', $unengaged_following->insta_username)
                                    ->where('target_username', $unengaged_following->follower_username)
                                    ->count() > 0) {
                        echo("[Follow][$ig_username] has engaged before " . $unengaged_following->follower_username . "\n");
                        continue;
                    }

                    echo("[$ig_username] unengaged followings: \t" . $unengaged_following->follower_username . "\n");

                    try {
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
            }





//            if (count($unengaged_followings) > 0) {
//                continue;
//                foreach ($unengaged_followings as $unengaged_following) {
//                    echo("[$ig_username] \t" . $unengaged_following->follower_username . "\n");
//                }
//            } else {
//                
//                
//            }
            #$instagram->setUser($ig_username, $ig_password);
            #$login_resp = $instagram->login();
        } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpt_ex) {
            echo("checkpt1 " . $checkpt_ex->getMessage() . "\n");
            $ig_profile->checkpoint_required = 1;
            $ig_profile->save();
        } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
            echo("incorrectpw1 " . $incorrectpw_ex->getMessage() . "\n");
            $ig_profile->incorrect_pw = 1;
            $ig_profile->save();
        } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {

            if ($endpoint_ex->getMessage() === "InstagramAPI\Response\UserInfoResponse: User not found.") {
                
            }

            echo("endpt1 " . $endpoint_ex->getMessage() . "\n");
        } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
            echo("network1 " . $network_ex->getMessage() . "\n");
        } catch (\InstagramAPI\Exception\AccountDisabledException $acctdisabled_ex) {
            echo("acctdisabled1 " . $acctdisabled_ex->getMessage() . "\n");
            $ig_profile->account_disabled = 1;
            $ig_profile->save();
        } catch (\InstagramAPI\Exception\RequestException $request_ex) {

            if ($request_ex->getMessage() === "InstagramAPI\Response\CommentResponse: Feedback required.") {
                if ($request_ex->hasResponse()) {
                    $full_response = $request_ex->getResponse()->fullResponse;

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
                echo("[ENDING] Request Exception: " . $request_ex->getMessage() . "\n");
                var_dump($request_ex->getResponse());
            }

//            echo("request1 " . $request_ex->getMessage() . "\n");
//            $ig_profile->error_msg = $request_ex->getMessage();
//            $ig_profile->save();
        }
    }
}
