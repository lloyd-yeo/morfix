<?php

namespace App\Console\Commands;

use App\InstagramProfile;
use App\InstagramProfileLikeLog;
use DB;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;

class MigrateLikeLogs extends Command
{

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'migrate:like {insta_username?}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Migrate like logs for this slave.';

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
		if ($this->argument('insta_username') === NULL) {

			$ig_profiles = InstagramProfile::orderBy('user_id', 'desc')->get();

			foreach ($ig_profiles as $ig_profile) {
				DB::table('user_insta_profile_like_log')
					->where('insta_username', $ig_profile->insta_username)
					->where('date_liked', '>', '2017-08-31 10:04:15')
					->orderBy('date_liked', 'desc')->chunk(10000, function ($master_like_logs) {
						foreach ($master_like_logs as $master_like_log) {
							$like_log = new InstagramProfileLikeLog;
							$like_log->log_id = $master_like_log->log_id;
							$like_log->insta_username = $master_like_log->insta_username;
							$like_log->target_username = $master_like_log->target_username;
							$like_log->target_media = $master_like_log->target_media;
							$like_log->target_media_code = $master_like_log->target_media_code;
							$like_log->log = $master_like_log->log;
							$like_log->date_liked = $master_like_log->date_liked;
							try {
								if ($like_log->save()) {
									$this->line("[Like Logs] Saved: " . $like_log->log_id);
								}
							} catch (QueryException $ex) {
								continue;
							}
						}
					});
			}
		} else {
			$ig_profile = InstagramProfile::where('insta_username', $this->argument('insta_username'))
				->first();

			$master_like_logs = DB::table('user_insta_profile_like_log')
				->where('insta_username', $ig_profile->insta_username)
				->get();

			foreach ($master_like_logs as $master_like_log) {
				$like_log = new InstagramProfileLikeLog;
				$like_log->insta_username = $master_like_log->insta_username;
				$like_log->target_username = $master_like_log->target_username;
				$like_log->target_media = $master_like_log->target_media;
				$like_log->target_media_code = $master_like_log->target_media_code;
				$like_log->log = $master_like_log->log;
				$like_log->date_liked = $master_like_log->date_liked;
				try {
					$like_log->save();
				} catch (QueryException $ex) {
					continue;
				}
			}
		}
	}

}
