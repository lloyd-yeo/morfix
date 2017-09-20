<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use App\InstagramProfileComment;
use App\InstagramProfileCommentLog;
use App\InstagramProfileFollowLog;
use App\InstagramProfileLikeLog;
use App\Proxy;
use App\EngagementJob;
use App\InstagramHelper;

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
        
        if ($ig_profile->owner()->tier == 1) {
            exit();
        }
        
        $ig_username = $ig_profile->insta_username;

        $instagram = InstagramHelper::initInstagram();

        if (InstagramHelper::login($instagram, $ig_profile)) {

            $engaged_user = NULL;

            try {

                $comments = InstagramProfileComment::where('insta_username', $ig_username)->get();
                
                if ($comments->isEmpty()) {
                    exit();
                }

                $comment = $comments->random();

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

                if ($real_unengaged_followings_count == 0) {

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

                        $engaged_user = $unengaged_liking->target_username;
                        try {
                            $user_instagram_id = $instagram->people->getUserIdForName($unengaged_liking->target_username);
                            #$user_instagram_id = $instagram->getUsernameId($unengaged_liking->target_username);
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
                        $engaged_user = $unengaged_following->target_username;

                        try {
                            #$user_instagram_id = $instagram->getUsernameId($unengaged_following->follower_username);
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
                    $comment_log = new InstagramProfileCommentLog;
                    $comment_log->insta_username = $ig_username;
                    $comment_log->target_username = $engaged_user;
                    $comment_log->save();
                } else if ($endpoint_ex->getMessage() === "InstagramAPI\Response\UserFeedResponse: Not authorized to view user.") {
                    $comment_log = new InstagramProfileCommentLog;
                    $comment_log->insta_username = $ig_username;
                    $comment_log->target_username = $engaged_user;
                    $comment_log->save();
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
                $ig_profile->error_msg = $request_ex->getMessage();
                $ig_profile->save();
            }
        }
    }

}
