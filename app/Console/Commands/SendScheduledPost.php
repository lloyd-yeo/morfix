<?php

namespace App\Console\Commands;

use App\InstagramHelper;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\InstagramProfilePhotoPostSchedule;
use App\User;
use App\InstagramProfile;
use InstagramAPI\Exception\InstagramException;

class SendScheduledPost extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'post:scheduled';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send scheduled post.';

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
		$post_schedules = InstagramProfilePhotoPostSchedule::where('date_to_post', '<=', Carbon::now())
		                                                   ->where('posted', 0)
		                                                   ->get();
		$last_username  = '';
		$logged_in      = FALSE;
		foreach ($post_schedules as $post_schedule) {

			$instagram_profile = InstagramProfile::where('insta_username', $post_schedule->insta_username)->first();

			if ($last_username != $post_schedule->insta_username) {
				$logged_in     = FALSE;
				$last_username = $post_schedule->insta_username;
				$instagram     = InstagramHelper::initInstagram(FALSE, $instagram_profile);

				if ($instagram_profile != NULL) {
					if (InstagramHelper::login($instagram, $instagram_profile)) {
						$logged_in = TRUE;
						$path      = '/var/www/app/storage/app/public/' . $post_schedule->image_path;
						$photo     = new \InstagramAPI\Media\Photo\InstagramPhoto($path);
						try {
							if ($post_schedule->caption != NULL) {
								$response = $instagram->timeline->uploadPhoto($photo->getFile(), [ 'caption' => $post_schedule->caption ]);
							} else {
								$response = $instagram->timeline->uploadPhoto($photo->getFile(), []);
							}
							dump($response);
							if ($response->isOk()) {
								if ($response->getMedia() != NULL) {
									$post_schedule->posted   = 1;
									$post_schedule->log      = $response->asJson();
									$post_schedule->media_id = $response->getMedia()->getPk();
									$post_schedule->save();
									if ($post_schedule->first_comment) {
										$instagram->media->comment($response->getMedia()->getPk(), $post_schedule->first_comment);
									}
								}
							} else {
								$post_schedule->posted      = 2;
								$post_schedule->failure_msg = $response->asJson();
								$post_schedule->save();
							}
						}
						catch (InstagramException $instagramException) {
							dump($instagramException);
							$post_schedule->posted = 2;
							if ($instagramException->hasResponse()) {
								$post_schedule->failure_msg = $instagramException->getResponse()->getMessage();
							}
							$post_schedule->save();
						}
					} else {

					}
				}
			} else {
				if ($logged_in) {
					$path  = '/var/www/app/storage/app/public/' . $post_schedule->image_path;
					$photo = new \InstagramAPI\Media\Photo\InstagramPhoto($path);
					try {
						if ($post_schedule->caption != NULL) {
							$response = $instagram->timeline->uploadPhoto($photo->getFile(), [ 'caption' => $post_schedule->caption ]);
						} else {
							$response = $instagram->timeline->uploadPhoto($photo->getFile(), []);
						}
						dump($response);
						if ($response->isOk()) {
							if ($response->getMedia() != NULL) {
								$post_schedule->posted   = 1;
								$post_schedule->log      = $response->asJson();
								$post_schedule->media_id = $response->getMedia()->getPk();
								$post_schedule->save();
								if ($post_schedule->first_comment) {
									$instagram->media->comment($response->getMedia()->getPk(), $post_schedule->first_comment);
								}
							}
						} else {
							$post_schedule->posted      = 2;
							$post_schedule->failure_msg = $response->asJson();
							$post_schedule->save();
						}
					}
					catch (InstagramException $instagramException) {
						dump($instagramException);
						$post_schedule->posted = 2;
						if ($instagramException->hasResponse()) {
							$post_schedule->failure_msg = $instagramException->getResponse()->getMessage();
						}
						$post_schedule->save();
					}
				}
			}
		}
	}
}