<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\User;
use App\InstagramProfile;
use App\InstagramProfileComment;
use App\InstagramProfileFollowLog;
use App\InstagramProfileLikeLog;
use App\LikeLogsArchive;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;

class ArchiveLikeLogs extends Command
{

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'archive:like {size}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Do up an archive of the like logs.';

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
		$size = $this->argument('size');
		$seed_size = 4609776;
		while ($seed_size < $size) {

			$seed_size = $seed_size + 200000;

			echo "Archiving results with log_id <= $seed_size...\n";
			DB::insert('INSERT IGNORE INTO user_insta_profile_like_log_archive (insta_username, target_username, 
                target_media, target_media_code, log, date_liked) 
                SELECT insta_username, target_username, target_media, target_media_code, log, date_liked 
                FROM user_insta_profile_like_log 
                WHERE log_id <= ?;', [ $seed_size ]);
			echo "Archiving success!\n";

			echo "Deleting from current table...\n";
			InstagramProfileLikeLog::where('log_id', '<=', $seed_size)->delete();
			InstagramProfileLikeLog::where('log_id', '<=', $seed_size)->chunk(3000, function ($like_logs) {

				foreach ($like_logs as $like_log) {
					//				$archive = new LikeLogsArchive;
					//				$archive->insta_username = $like_log->insta_username;
					//				$archive->target_username = $like_log->target_username;
					//				$archive->target_media = $like_log->target_media;
					//				$archive->target_media_code = $like_log->target_media_code;
					//				$archive->log = $like_log->log;
					//				$archive->date_liked = $like_log->date_liked;
					//				if ($archive->save()) {
					//					echo "Saving archive #" . $archive->log_id . "\n";
					//				}

					echo "Removing: " . $like_log->log_id . "\n";
					$like_log->delete();
				}
			});
			echo "Deleting complete!\n";
		}

	}

}
