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
use App\InteractionCommentHelper;

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
                    $engaged_user = InteractionCommentHelper::unEngagedLiking($ig_profile, $instagram);
                } else {
                    $engaged_user = InteractionCommentHelper::unEngagedFollowings($ig_profile, $instagram, $unengaged_followings);
                }
            } catch(\InstagramAPI\Exception\InstagramException $insta_ex){
                InteractionCommentHelper::handleInstragramException($ig_profile, $insta_ex, $engaged_user);
            }
    }

}
