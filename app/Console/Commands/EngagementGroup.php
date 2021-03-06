<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\InstagramProfile;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;
use App\EngagementJob;
use App\EngagementGroupJob;

class EngagementGroup extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'engagement:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start liking using the engagement group.';

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
        $outstanding_engagements = EngagementGroupJob::where('engaged', false)->get();

        foreach ($outstanding_engagements as $outstanding_engagement) {
            $media_id = $outstanding_engagement->media_id;
            $comment_count = 100;

            $outstanding_engagement->engaged = 1;
            $outstanding_engagement->save();

            $this->line("UPDATED [$media_id]\n\n");

            $engagement_group_users = DB::select("SELECT p.insta_username, p.insta_pw, p.proxy, p.auto_like, p.auto_comment, p.id, u.user_tier
                                FROM user_insta_profile p, user u
                                WHERE p.user_id = u.user_id
                                AND p.checkpoint_required = 0
                                AND p.invalid_user = 0
                                AND p.incorrect_pw = 0
                                AND p.feedback_required = 0
                                AND p.invalid_proxy = 0
                                AND p.account_disabled = 0
                                AND (u.user_tier = 1 OR (u.user_tier > 1 AND p.auto_interaction = 1 AND (p.auto_like = 1 OR p.auto_comment = 1))) 
                                ORDER BY RAND()");

            foreach ($engagement_group_users as $ig_profile) {
                $ig_username = $ig_profile->insta_username;
                try {
                    $engagement_job = new EngagementJob;
                    $engagement_job->media_id = $media_id;
                    $engagement_job->insta_username = $ig_username;
                    $engagement_job->action = 0;
                    $engagement_job->save();

                    if (($ig_profile->auto_comment == 1 && $comment_count > 0) || $ig_profile->user_tier == 1) {
                        $engagement_job = new EngagementJob;
                        $engagement_job->media_id = $media_id;
                        $engagement_job->insta_username = $ig_username;
                        $engagement_job->action = 1;
                        $engagement_job->save();
						$comment_count = $comment_count - 1;
                    }

                } catch (\PDOException $pdo_ex) {
                    continue;
                }
            }
        }
    }

}
