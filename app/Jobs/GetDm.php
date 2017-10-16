<?php

namespace App\Jobs;

use App\DmJob;
use App\InstagramHelper;
use App\Proxy;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class GetDm implements ShouldQueue
{

	use Dispatchable,
		InteractsWithQueue,
		Queueable,
		SerializesModels;

	protected $profile;

	/**
	 * The number of times the job may be attempted.
	 *
	 * @var int
	 */
	public $tries = 1;

	/**
	 * The number of seconds the job can run before timing out.
	 *
	 * @var int
	 */
	public $timeout = 120;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(\App\InstagramProfile $profile)
	{
		$this->profile = $profile;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		DB::reconnect();

		$ig_profile = $this->profile;
		$ig_username = $ig_profile->insta_username;
		$user = $ig_profile->owner();

		$instagram = InstagramHelper::initInstagram();

		if (!InstagramHelper::login($instagram, $ig_profile)) {
			return;
		}

		try {
			$activity_response = $instagram->people->getRecentActivityInbox();

//			dump($activity_response);

			$newest_timestamp = 0;

			foreach ($activity_response->getOldStories() as $story) {

				if ($story->getType() === 3) {
					$story_arr = $story->asArray();

					$story_args = $story_arr['args'];
					$story_args_timestamp = $story_args['timestamp'];
					$recipient_insta_id = $story_args['profile_id'];

					if ($newest_timestamp === 0) {
						//update instagram profile's timestamp here.
						$newest_timestamp = $story_args_timestamp;
						$ig_profile->recent_activity_timestamp = $newest_timestamp;
						$ig_profile->save();
					}

					$new_follower_template = $ig_profile->insta_new_follower_template;
					$follow_up_template = $ig_profile->follow_up_message;

					$existing_dm_jobs = DmJob::where('insta_username', $ig_username)
						->where('recipient_insta_id', $recipient_insta_id)
						->count();

					$job_exists = 0;

					if ($existing_dm_jobs > 0) {
						echo("\n[$ig_username] Dm job exists!");
						$job_exists = 1;
						break;
					}

					if ($job_exists) {
						break;
					}

					if (floatval($ig_profile->recent_activity_timestamp) < floatval($story_args_timestamp)) {

						#echo("queue as new dm");
						$user_info_response = $instagram->people->getInfoById($recipient_insta_id);
						$new_follower = $user_info_response->getUser();
						#echo($new_follower->full_name);

						if ($new_follower->getFullName()) {
							$message = str_replace("\${full_name}",
								$new_follower->getFullName(), $new_follower_template);
						} else {
							if (empty($new_follower->getFullName())) {
								$message = str_replace(" \${full_name}",
									"", $new_follower_template);
							}
						}

						preg_match_all('/{([^}]+)}/', $message, $m);

						$string_replacement = $message;
						#echo(serialize($m) . "\n\n");
						for ($j = 0; $j < count($m[1]); $j++) {
							# Matched text = $result[0][$i];

							$selected_opt = "";

							$string_to_replace = $m[1][$j];
							if (strpos($string_to_replace, '|') !== FALSE) {
								$string_opts = explode("|", $string_to_replace);
								$max = count($string_opts);
								$max--;
								$index = rand(0, $max);
								$selected_opt = $string_opts[$index];
							} else {
								$selected_opt = $string_to_replace;
							}

							$string_replacement = str_replace("\${" . $string_to_replace . "}",
								$selected_opt, $string_replacement);
						}

						echo $string_replacement . "\n\n";

						//insert job into db here.
						$new_dm_job = new DmJob;
						$new_dm_job->insta_username = $ig_username;
						$new_dm_job->recipient_username = $new_follower->getUsername();
						$new_dm_job->recipient_insta_id = $new_follower->getPk();
						$new_dm_job->recipient_fullname = $new_follower->getFullName();
						$new_dm_job->follow_up_order = 0;
						$new_dm_job->message = $string_replacement;
						$new_dm_job->time_to_send = Carbon::now();
						$new_dm_job->save();

						$follow_up_message = "";
						if (!is_null($follow_up_template)) {
							$follow_up_message = trim($follow_up_template);
						}

						if ((!is_null($follow_up_message) && $follow_up_message != "") && $user->tier > 3) {

							if ($new_follower->getFullName()) {
								$message2 = str_replace("\${full_name}",
									$new_follower->getFullName(), $follow_up_message);
							} else {
								if (empty($new_follower->getFullName())) {
									$message2 = str_replace(" \${full_name}", "", $follow_up_message);
								}
							}

							preg_match_all('/{([^}]+)}/', $message2, $m2);

							$string_replacement2 = $message2;

							for ($k = 0; $k < count($m2[1]); $k++) {
								$selected_opt = "";

								$string_to_replace2 = $m2[1][$k];
								if (strpos($string_to_replace2, '|') !== FALSE) {
									$string_opts = explode("|", $string_to_replace2);
									$max = count($string_opts);
									$max--;
									$index = rand(0, $max);
									$selected_opt = $string_opts[$index];
								} else {
									$selected_opt = $string_to_replace2;
								}

								$string_replacement2 = str_replace("\${" . $string_to_replace2 . "}", $selected_opt, $string_replacement2);
							}

							echo $string_replacement2 . "\n\n";

							$new_follow_up_dm_job = new DmJob;
							$new_follow_up_dm_job->insta_username = $ig_username;
							$new_follow_up_dm_job->recipient_username = $new_follower->getUsername();
							$new_follow_up_dm_job->recipient_insta_id = $new_follower->getPk();
							$new_follow_up_dm_job->recipient_fullname = $new_follower->getFullName();
							$new_follow_up_dm_job->follow_up_order = 1;
							$new_follow_up_dm_job->message = $string_replacement2;
							$new_follow_up_dm_job->time_to_send = Carbon::now();
							$new_follow_up_dm_job->save();
						}
					} else {
						break;
					}
				}
			}
		} catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
			#echo($checkpoint_ex->getMessage());
		} catch (\Symfony\Component\Debug\Exception\FatalThrowableError $fatalthrowable_ex) {
			#echo($fatalthrowable_ex->getTraceAsString());
		}
	}

}
