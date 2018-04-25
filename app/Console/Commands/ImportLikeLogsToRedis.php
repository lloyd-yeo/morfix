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
use App\RedisRepository;

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
			$this->line("[" . $ig_profile->insta_username . "] like_logs count: " . $like_logs->count());
			foreach ($like_logs as $like_log) {
				$media_pk = explode('_', $like_log->target_media)[0];
//				RedisRepository::savePartialMedia($media_pk, $like_log->target_media_code);
//				RedisRepository::savePartialMediaOwner($like_log->target_username, $media_pk);
				RedisRepository::saveUserLikedMediaByUsername($ig_profile->insta_username, $media_pk, $like_log->date_liked);
				RedisRepository::saveUserLikedMediaByPk($ig_profile->insta_user_id, $media_pk, $like_log->date_liked);
				RedisRepository::saveUserLikedUsername($ig_profile->insta_user_id, $like_log->target_username);
			}
			$this->line("[" . $ig_profile->insta_username . "] saved like_logs to redis.");
		}
    }
}
