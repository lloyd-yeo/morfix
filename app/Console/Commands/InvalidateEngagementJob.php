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

class InvalidateEngagementJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'engagement:invalidate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Invalidate engagement jobs with invalid users.';

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
        $invalid_profiles = DB::connection("mysql_old")->select("SELECT insta_username FROM user_insta_profile WHERE incorrect_pw = 1 OR invalid_user = 1 OR checkpoint_required = 1 LIMIT 1000;");
        foreach ($invalid_profiles as $invalid_profile) {
            $insta_username = $invalid_profile->insta_username;
            $updated = DB::connection("mysql_old")->update("UPDATE engagement_job_queue SET fulfilled = 2 WHERE insta_username = ?;", [$insta_username]);
        }
    }
}
