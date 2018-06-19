<?php

namespace App\Jobs;

use App\Events\EngagementGroupFailed;
use App\InstagramHelper;
use App\InstagramProfile;
use App\InstagramProfileComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EngagementGroup implements ShouldQueue
{

	use Dispatchable,
		InteractsWithQueue,
		Queueable,
		SerializesModels;

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
	public $timeout = 7200000;

	/**
	 * @var int The number of comments to give out.
	 */
	protected $comments_to_give;

	protected $media_id;
	protected $ig_profile_id;
	protected $comment;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($media_id, $ig_profile_id, $comment = 1)
	{
		$this->media_id = $media_id;
		$this->ig_profile_id = $ig_profile_id;
		$this->comment = $comment;
		$this->comments_to_give = 100;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		echo "[Job] Turn on commenting? " . $this->comment . "\n";

		$ig_profiles = InstagramProfile::where('checkpoint_required', 0)
			->where('account_disabled', 0)
			->where('invalid_user', 0)
			->where('incorrect_pw', 0)
			->where('invalid_proxy', 0)
			->where('challenge_required', 0)
			->where('feedback_required', 0)
			->where('auto_like_ban', 0)
			->where('auto_comment_ban', 0)
			->orderBy('id', 'desc')
			->get();

		echo count($ig_profiles) . ' profiles retrieved.\n';

		$mediaId = $this->media_id;

		$default_comments = array();
		$default_comments[] = "That is really insta-worthy.";
		$default_comments[] = "Seriously. That's a awesome photo!";
		$default_comments[] = "Really love your feeds. Keep it coming!";
		$default_comments[] = "Your photos look really really good!";
		$default_comments[] = "Those are some good-looking photos!";
		$default_comments[] = "That's an amazing shot!";
		$default_comments[] = "Keep it going! I really like your feed.";
		$default_comments[] = "Keep the photos coming!";
		$default_comments[] = "That is a breathtaking photo! Nicely done!";
		$default_comments[] = "Nice photo! I love your feed!";
		$default_comments[] = "I really love this photo.";

		foreach ($ig_profiles as $ig_profile) {
			sleep (rand(1, 120));
			if ($this->ig_profile_id !== NULL) {
				if ($ig_profile->id === $this->ig_profile_id) {
					continue;
				}
			}

			$instagram = InstagramHelper::initInstagram();
			$instagram = InstagramHelper::setProxy($instagram, $ig_profile, 1);

			if (!InstagramHelper::login($instagram, $ig_profile)) {
				echo "[" . $ig_profile->insta_username . "] logged in failed.";
				continue;
			}

			echo "[" . $ig_profile->insta_username . "] logged in successfully.";

			try {
				$response = $instagram->media->like($mediaId);
				dump($response);
				if ($ig_profile->owner()->trial_activation == 1) {
					echo "[" . $ig_profile->insta_username . "] owner is on Free-Trial\n";
				} else {
					echo "[" . $ig_profile->insta_username . "] owner is NOT on Free-Trial\n";

					if ($this->comment == 1) {

						echo ("[" . $ig_profile->insta_username . "] has turned on Auto-Comment\n");

						if ($ig_profile->auto_comment == 1) {
							$comments = InstagramProfileComment::where('insta_username', $ig_profile->insta_username)
								->get();
							if (count($comments) > 0 && $this->comments_to_give > 0) {
								$comment = $comments->random();
								if (!empty($comment->comment)) {
									$comment_resp = $instagram->media->comment($mediaId, $comment->comment);
									dump($comment_resp);
									$this->comments_to_give--;
								}
							}
						}
					}
				}
			} catch (\InstagramAPI\Exception\FeedbackRequiredException $feedback_required_ex) {
				dump($feedback_required_ex);
				$response = json_decode($feedback_required_ex->getResponse()->asJson(), true);
				dump($response);
				if ($response['spam']) {
					$ig_profile->feedback_required = 1;
					$ig_profile->save();
				}
				continue;
			} catch (\InstagramAPI\Exception\NetworkException $network_ex) {
				dump($feedback_required_ex);
				continue;
			} catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
				dump($feedback_required_ex);
				$ig_profile->checkpoint_required = 1;
				$ig_profile->save();
				continue;
			} catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
				dump($feedback_required_ex);
				continue;
			} catch (\InstagramAPI\Exception\BadRequestException $badrequest_ex) {
				dump($feedback_required_ex);
				continue;
			} catch (\InstagramAPI\Exception\LoginRequiredException $loginrequired_ex) {
				dump($feedback_required_ex);
				continue;
			} catch (\InstagramAPI\Exception\SentryBlockException $sentryblock_ex) {
				dump($feedback_required_ex);
				continue;
			} catch (\InstagramAPI\Exception\ChallengeRequiredException $challengeRequiredException) {
				$ig_profile->challenge_required = 1;
				$ig_profile->save();
				dump($feedback_required_ex);
				continue;
			} catch (\InstagramAPI\Exception\ThrottledException $throttled_ex) {
				dump($feedback_required_ex);
				$ig_profile->next_like_time = \Carbon\Carbon::now()->addHour(1);
				$ig_profile->next_comment_time = \Carbon\Carbon::now()->addHour(1);
				$ig_profile->save();
				continue;
			} catch (\Exception $ex) {
				dump($ex);
				continue;
			}
		}
	}

	/**
	 * Handle a job failure.
	 *
	 * @return void
	 */
	public function failed()
	{
		unset($this->instagram);
		event(new EngagementGroupFailed($this->media_id));
	}

}
