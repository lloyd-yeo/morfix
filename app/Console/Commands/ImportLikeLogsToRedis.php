<?php

namespace App\Console\Commands;

use App\InstagramHelper;
use App\InstagramProfile;
use Illuminate\Console\Command;
use InstagramAPI\InstagramID;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;
use App\LikeLogsArchive;
use App\InstagramProfileLikeLog;

class ImportLikeLogsToRedis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importredis:likes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate max_likes for every user_insta_profile & update profile\'s log.';

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
		$ig_profiles = InstagramProfile::all();
		foreach ($ig_profiles as $ig_profile) {
			$like_logs = InstagramProfileLikeLog::where('insta_username', $ig_profile->insta_username)->get();
			foreach ($like_logs as $like_log) {
				$pk = $ig_profile->insta_user_id;

			}
		}
    }
}
