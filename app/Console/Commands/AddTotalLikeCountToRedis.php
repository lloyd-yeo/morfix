<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\InstagramProfile;
use App\InstagramProfileLikeLog;
use App\LikeLogsArchive;
use App\RedisRepository;

class AddTotalLikeCountToRedis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:bootstraplikecount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bootstrap the like count for users.';

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
	    $users = User::where('tier', '>', 1)->get();
	    $like_count_map = array();

		foreach ($users as $user) {
			$instagram_profiles = InstagramProfile::where('user_id', $user->user_id)->get();
			foreach ($instagram_profiles as $instagram_profile) {
				$like_count = LikeLogsArchive::where('insta_username', $instagram_profile->insta_username)->count();
				$like_count_map[$instagram_profile->insta_user_id] = $like_count;
				$this->line("[Likes] Added Instagram account: " . $instagram_profile->insta_username);
			}
		}

		RedisRepository::saveProfileLikeCountMap($like_count_map);
    }
}