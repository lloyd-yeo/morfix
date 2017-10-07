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
            InteractionCommentHelper::unengaged($ig_profile, $instagram);
        }
    }

}
