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
	public $timeout = 7200;

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

		$ig_profiles = InstagramProfile::where('checkpoint_required', 0)
			->where('account_disabled', 0)
			->where('invalid_user', 0)
			->where('incorrect_pw', 0)
			->where('invalid_proxy', 0)
			->get();

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

			if ($this->ig_profile_id !== NULL) {
				if ($ig_profile->id === $this->ig_profile_id) {
					continue;
				}
			}

			$instagram = InstagramHelper::initInstagram();
			if (!InstagramHelper::login($instagram, $ig_profile)) {
				continue;
			}

			try {
				$response = $instagram->media->like($mediaId);

				if ($ig_profile->owner()->trial_activation === 1) {

				} else {
					if ($this->comment === 1) {
						if ($ig_profile->auto_comment === 1) {
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

//				dump($response);

			} catch (\InstagramAPI\Exception\FeedbackRequiredException $feedback_required_ex) {
				continue;
			} catch (\InstagramAPI\Exception\NetworkException $network_ex) {
				continue;
			} catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
				$ig_profile->checkpoint_required = 1;
				$ig_profile->save();
				continue;
			} catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
				continue;
			} catch (\InstagramAPI\Exception\BadRequestException $badrequest_ex) {
				continue;
			} catch (\InstagramAPI\Exception\LoginRequiredException $loginrequired_ex) {
				continue;
			} catch (\InstagramAPI\Exception\SentryBlockException $sentryblock_ex) {
				continue;
			} catch (\InstagramAPI\Exception\ThrottledException $throttled_ex) {
				$ig_profile->next_like_time = \Carbon\Carbon::now()->addHour(1);
				$ig_profile->next_comment_time = \Carbon\Carbon::now()->addHour(1);
				$ig_profile->save();
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
		event(new EngagementGroupFailed($this->media_id));
	}

}
