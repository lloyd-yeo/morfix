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
use App\InstagramProfileComment;
use App\InstagramProfileCommentLog;
use App\InstagramProfileFollowLog;
use App\InstagramProfileLikeLog;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;

class InteractionComment implements ShouldQueue {

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
    public $timeout = 120;
    
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
        } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
            $proxy = Proxy::inRandomOrder()->first();
            $ig_profile->proxy = $proxy->proxy;
            $ig_profile->save();
            $proxy->assigned = $proxy->assigned + 1;
            $proxy->save();
            $instagram->setProxy($ig_profile->proxy);
            $instagram->login();
//            var_dump($network_ex);
        } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
            $ig_profile->incorrect_pw = 1;
            $ig_profile->save();
            exit();
        }

        try {

            $comment = InstagramProfileComment::where('insta_username', $ig_username)
                    ->inRandomOrder()
                    ->first();

            if ($comment === NULL) {
                exit();
            }

            echo($comment->comment . "\n");
            $commentText = $comment->comment;

            $commented = false;

            $user_instagram_id = NULL;

            $unengaged_followings = InstagramProfileFollowLog::where('insta_username', $ig_username)
                    ->whereRaw("follower_username NOT IN "
                            . "(SELECT target_username FROM user_insta_profile_comment_log WHERE insta_username = \"$ig_username\")")
                    ->orderBy('date_inserted', 'desc')
                    ->take(5)
                    ->get();

            if (count($unengaged_followings) < 1) {
                $unengaged_likings = InstagramProfileLikeLog::where('insta_username', $ig_username)
                        ->whereRaw("target_username NOT IN "
                                . "(SELECT target_username FROM user_insta_profile_comment_log WHERE insta_username = \"$ig_username\")")
                        ->orderBy('date_liked', 'desc')
                        ->take(10)
                        ->get();

                foreach ($unengaged_likings as $unengaged_liking) {

                    echo("[$ig_username] unengaged likes: \t" . $unengaged_liking->target_username . "\n");

                    try {
                        $user_instagram_id = $instagram->getUsernameId($unengaged_liking->target_username);
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

                            $comment_log = new InstagramProfileCommentLog;
                            $comment_log->insta_username = $ig_username;
                            $comment_log->target_username = $unengaged_liking->follower_username;
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
            } else {
                foreach ($unengaged_followings as $unengaged_following) {

                    echo("[$ig_username] unengaged followings: \t" . $unengaged_following->follower_username . "\n");

                    try {
                        $user_instagram_id = $instagram->getUsernameId($unengaged_following->follower_username);
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
                            $this->line("[" . $ig_profile->username . "] commenting has been banned till " . $ig_profile->auto_comment_ban_time);
                        }
                    }
                }
            } else {
                echo("[ENDING] Request Exception: " . $request_ex->getMessage() . "\n");
                var_dump($request_ex->getResponse());
            }

            $ig_profile->error_msg = $request_ex->getMessage();
            $ig_profile->save();
        }
    }

}
