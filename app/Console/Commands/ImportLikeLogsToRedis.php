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
use App\User;

class ImportLikeLogsToRedis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:importlikes';

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
    	$users = User::where('vip', 1)->orWhere('tier', '>', 1)->get();
    	foreach ($users as $user) {
		    $ig_profiles = InstagramProfile::where('user_id', $user->user_id)->get();
		    foreach ($ig_profiles as $ig_profile) {
		    	$liked_media_ids = [];
			    $like_logs = InstagramProfileLikeLog::select(DB::raw("target_media_code, SPLIT_STRING(target_media, '_', 1) AS media_id"))->where('insta_username', $ig_profile->insta_username)->get();

			    foreach ($like_logs as $like_log) {
			    	$this->line($like_log->media_id);
//			    	$like_log->target_media = explode("_", $like_log->target_media)[0];
//			    	$like_log->save();
//				    $liked_media_ids[explode("_", $like_log->target_media)[0]] = $like_log->target_media_code;
				}
//				RedisRepository::saveProfileLikedMedias($ig_profile->insta_user_id, $liked_media_ids);
			    $this->line("[" . $ig_profile->insta_username . "] like_logs count: " . $like_logs->count());
			    $this->line("[" . $ig_profile->insta_username . "] saved like_logs to redis.");
		    }
	    }
    }
}
