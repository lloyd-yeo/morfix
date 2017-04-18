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

class SnapshotFollowerAnalysis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analysis:follower';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Take a snapshot of the followers and store it.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::connection("mysql_old")->insert("INSERT INTO user_insta_follower_analysis(insta_username, follower_count) SELECT insta_username, follower_count FROM user_insta_profile WHERE invalid_user = 0 AND checkpoint_required = 0 AND account_disabled = 0 AND incorrect_pw = 0;");
        DB::connection("mysql_old")->update("UPDATE user SET engagement_quota = 1;");
//        DB::connection("mysql_old")->update("UPDATE user_insta_profile SET auto_comment_ban = 0, auto_comment_ban_time = NULL, next_comment_time = NOW() + INTERVAL 10 MINUTE, comment_feedback_required = 0 WHERE NOW() >= auto_comment_ban_time;");
    }
}
