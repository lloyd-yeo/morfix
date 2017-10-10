<?php

namespace App\Jobs;

use App\BlacklistedUsername;
use App\InstagramHelper;
use App\InstagramProfile;
use App\InstagramProfileLikeLog;
use App\InteractionHelper;
use App\LikeLogsArchive;
use App\Niche;
use App\TargetHelper;
use Carbon as Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Exception\AccountDisabledException as AccountDisabledException;
use InstagramAPI\Exception\CheckpointRequiredException as CheckpointRequiredException;
use InstagramAPI\Exception\EndpointException as EndpointException;
use InstagramAPI\Exception\FeedbackRequiredException as FeedbackRequiredException;
use InstagramAPI\Exception\IncorrectPasswordException as IncorrectPasswordException;
use InstagramAPI\Exception\InstagramException as InstagramException;
use InstagramAPI\Exception\NetworkException as NetworkException;
use InstagramAPI\Exception\ThrottledException as ThrottledException;
use InstagramAPI\Instagram;
use InstagramAPI\Response\Model\Item as InstagramItem;
use InstagramAPI\Response\Model\User as InstagramUser;

class InteractionLike implements ShouldQueue
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
	public $timeout = 360;

	/**
	 * The App\InstagramProfile to automate Likes for.
	 *
	 * @var InstagramProfile
	 */
	protected $profile;

	/**
	 * The number of likes allocated for this run of the job.
	 * This is generated randomly at the start of the job.
	 *
	 * @var int
	 */
	protected $like_quota;
	protected $targeted_hashtags;
	protected $targeted_usernames;
	protected $speed_delay;
	protected $instagram;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(InstagramProfile $profile)
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

		$this->calcSpeedDelay($this->profile->speed);
		$this->instagram = InstagramHelper::initInstagram();
		$this->like_quota = rand(1, 3);
		$this->targeted_hashtags = TargetHelper::getUserTargetedHashtags($this->profile);
		$this->targeted_usernames = TargetHelper::getUserTargetedUsernames($this->profile);

		$ig_profile = $this->profile;
		$ig_username = $ig_profile->insta_username;

		$instagram = $this->getInstagram();

		if (InstagramHelper::login($instagram, $ig_profile)) {

			$use_hashtags = $this->randomizeUseHashtags();

			if (!$use_hashtags) {

				foreach ($this->targeted_usernames as $target_username) {

					if ($this->like_quota > 0) {

						//Get followers of the target.
						echo("\n" . "[$ig_username] Target Username: " . $target_username->target_username . "\n");
						$target_username_id = $this->checkValidTargetUsername($instagram, $target_username);
						if ($target_username_id === NULL) {
							continue;
						}

						$target_target_username = $target_username->target_username;
						$user_follower_response = NULL;
						$next_max_id = NULL;

						$page_count = 0;

						do {
							echo "\n[$ig_username] requesting [$target_target_username] with: " . $next_max_id . "\n";

							$user_follower_response = $instagram->people->getFollowers($target_username_id, NULL, $next_max_id);
							$target_user_followings = $user_follower_response->getUsers();
							$next_max_id = $user_follower_response->getNextMaxId();
							echo "\n[$ig_username] next_max_id for [$target_target_username] is " . $next_max_id . "\n";
							$page_count++;

							//Foreach follower of the target.
							foreach ($target_user_followings as $user_to_like) {

								if ($this->like_quota > 0) {

									echo("\n" . $user_to_like->getUsername() . "\t" . $user_to_like->getPk());

									$is_duplicate = $this->checkBlacklistAndDuplicates($user_to_like, $page_count);

									if ($is_duplicate == 1) {
										break;
									} else {
										if ($is_duplicate == 2) {
											continue;
										}
									}

									//Get the feed of the user to like.
									$user_feed_response = InstagramHelper::getUserFeed($instagram, $user_to_like);
									if ($user_feed_response === NULL) {
										continue;
									}

									//Get the media posted by the user.
									$user_items = $user_feed_response->getItems();

									//Foreach media posted by the user.
									foreach ($user_items as $item) {
										if ($this->like_quota > 0) {
											if ($this->checkDuplicateByMediaId($item)) {
												continue;
											}
											if (!$this->like($user_to_like, $item)) {
												break;
											}
										} else {
											echo "\n\n[146] Exiting...\n\n";

											return;
										}
									}
								} else {
									echo "\n\n[151] Exiting...\n\n";

									return;
								}
							}
						} while ($next_max_id !== NULL && $this->like_quota > 0);
					} else {
						echo "\n\n[157] Exiting...\n\n";

						return;
					}
				}
			} else {

				foreach ($this->targeted_hashtags as $target_hashtag) {

					if ($this->like_quota > 0) {
						echo("\n" . "[$ig_username] Target Hashtag: " . $target_hashtag->hashtag . "\n\n");
						//Get the feed from the targeted hashtag.

						if (empty(trim($target_hashtag->hashtag))) {
							$target_hashtag->invalid = 1;
							$target_hashtag->save();
							continue;
						}
						$hashtag_feed = InstagramHelper::getHashtagFeed($instagram, $target_hashtag);
						if ($hashtag_feed !== NULL) {
							foreach ($hashtag_feed->getItems() as $item) {
								$user_to_like = $item->getUser();
								if (!$this->checkDuplicate($user_to_like)) {
									if ($this->like_quota > 0) {
										if (!$this->checkDuplicateByMediaId($item)) {
											if (!$this->like($user_to_like, $item)) {
												continue;
											}
										}
									} else {
										echo "\n\n[186] Exiting...\n\n";

										return;
									}
								}
							}
						}
					} else {
						echo "\n\n[192] Exiting...\n\n";

						return;
					}
				}
			}

			if ($this->like_quota > 0) {
				if ($this->profile->niche > 0) {
					$niche = Niche::find($this->profile->niche);
					$niche_targets = $niche->targetUsernames();

					foreach ($niche_targets as $target_username) {

						if ($this->like_quota > 0) {

							//Get followers of the target.
							echo("\n" . "[$ig_username] Target Username: " . $target_username->target_username . "\n");
							$target_username_id = InstagramHelper::getUserIdForNicheUsername($instagram, $target_username);
							if ($target_username_id === NULL) {
								continue;
							}

							$target_target_username = $target_username->target_username;
							$user_follower_response = NULL;
							$next_max_id = NULL;

							$page_count = 0;

							do {
								echo "\n[$ig_username] requesting [$target_target_username] with: " . $next_max_id . "\n";

								$user_follower_response = $instagram->people->getFollowers($target_username_id, NULL, $next_max_id);
								$target_user_followings = $user_follower_response->getUsers();
								$next_max_id = $user_follower_response->getNextMaxId();
								echo "\n[$ig_username] next_max_id for [$target_target_username] is " . $next_max_id . "\n";
								$page_count++;

								//Foreach follower of the target.
								foreach ($target_user_followings as $user_to_like) {

									if ($this->like_quota > 0) {

										echo("\n" . $user_to_like->getUsername() . "\t" . $user_to_like->getPk());

										$is_duplicate = $this->checkBlacklistAndDuplicates($user_to_like, $page_count);

										if ($is_duplicate == 1) {
											break;
										} else {
											if ($is_duplicate == 2) {
												continue;
											}
										}

										//Get the feed of the user to like.
										$user_feed_response = InstagramHelper::getUserFeed($instagram, $user_to_like);
										if ($user_feed_response === NULL) {
											continue;
										}

										//Get the media posted by the user.
										$user_items = $user_feed_response->getItems();

										//Foreach media posted by the user.
										foreach ($user_items as $item) {
											if ($this->like_quota > 0) {
												if ($this->checkDuplicateByMediaId($item)) {
													continue;
												}
												if (!$this->like($user_to_like, $item)) {
													continue;
												}
											} else {
												echo "\n\n[264] Exiting...\n\n";

												return;
											}
										}
									} else {
										echo "\n\n[269] Exiting...\n\n";

										return;
									}
								}
							} while ($next_max_id !== NULL && $this->like_quota > 0);
						}
					}

					$niche = Niche::find($ig_profile->niche);
					$target_hashtags = $niche->targetHashtags();

					foreach ($target_hashtags as $target_hashtag) {
						if ($this->like_quota > 0) {
							echo("\n" . "[$ig_username] Target Hashtag: " . $target_hashtag->hashtag . "\n\n");
							//Get the feed from the targeted hashtag.
							$hashtag_feed = $instagram->hashtag->getFeed(trim($target_hashtag->hashtag));
							foreach ($hashtag_feed->getItems() as $item) {
								$user_to_like = $item->getUser();
								if (!$this->checkDuplicate($user_to_like)) {
									if ($this->like_quota > 0) {
										if (!$this->checkDuplicateByMediaId($item)) {
											if (!$this->like($user_to_like, $item)) {
												continue;
											}
										}
									} else {
										return;
									}
								}
							}
						}
					}
				}
			} else {
				return;
			}
		}
	}

	/**
	 *
	 * @param InstagramUser $user_to_like
	 * @param InstagramItem $item
	 * @return boolean
	 */
	public function like(InstagramUser $user_to_like, InstagramItem $item)
	{
		$ig_profile = $this->profile;
		$like_response = NULL;
		try {
			$like_response = $this->instagram->media->like($item->getId());
		} catch (CheckpointRequiredException $checkpoint_ex) {
			$this->handleInstagramException($ig_profile, $checkpoint_ex);
		} catch (NetworkException $network_ex) {
			$this->handleInstagramException($ig_profile, $network_ex);
		} catch (EndpointException $endpoint_ex) {
			$this->handleInstagramException($ig_profile, $endpoint_ex);
		} catch (IncorrectPasswordException $incorrectpw_ex) {
			$this->handleInstagramException($ig_profile, $incorrectpw_ex);
		} catch (FeedbackRequiredException $feedback_ex) {
			$this->handleInstagramException($ig_profile, $feedback_ex);
		} catch (EmptyResponseException $emptyresponse_ex) {
			$this->handleInstagramException($ig_profile, $emptyresponse_ex);
		} catch (AccountDisabledException $acctdisabled_ex) {
			$this->handleInstagramException($ig_profile, $acctdisabled_ex);
		} catch (ThrottledException $throttled_ex) {
			$this->handleInstagramException($ig_profile, $throttled_ex);
		}

		if ($like_response == NULL) {
			return FALSE;
		} else {
			if ($like_response->getStatus() == "ok") {
				try {
					$this->like_quota = $this->like_quota - 1;
					echo("\n" . "[" . $ig_profile->insta_username . "] Liked " . serialize($like_response) . "\n\n");
					echo("\n" . "[" . $ig_profile->insta_username . "] Remaining Round Quota: " . $this->like_quota);
					$like_log = new InstagramProfileLikeLog;
					$like_log->insta_username = $ig_profile->insta_username;
					$like_log->target_username = $user_to_like->getUsername();
					$like_log->target_media = $item->getId();
					$like_log->target_media_code = $item->getItemUrl();
					$like_log->log = serialize($like_response);
					if ($like_log->save()) {
						$ig_profile->next_like_time = Carbon::now()->addMinutes($this->speed_delay);
						$ig_profile->auto_like_ban = 0;
						$ig_profile->auto_like_ban_time = NULL;
						$ig_profile->save();

						return TRUE;
					} else {
						return FALSE;
					}
				} catch (\Exception $ex) {
					echo "[" . $ig_profile->insta_username . "] saving error [target_username] " . $ex->getMessage() . "\n";

					return FALSE;
				}
			}
		}

		return FALSE;
	}

	public function checkDuplicateByMediaId(InstagramItem $item)
	{
		$ig_profile = $this->profile;

		if (InstagramProfileLikeLog::where('insta_username', $ig_profile->insta_username)
				->where('target_media', $item->getId())->count() > 0) {
			#duplicate. Liked before this photo with this id.
			return TRUE;
		}

		//Check for duplicates.
		$liked_logs = LikeLogsArchive::where('insta_username', $ig_profile->insta_username)
			->where('target_media', $item->getId())
			->first();

		//Duplicate = liked media before.
		if ($liked_logs !== NULL) {
			echo("\n" . "Duplicate Log [MEDIA] Found:\t[" . $ig_profile->insta_username . "] [" . $item->getId() . "]");

			return TRUE;
		}

		return FALSE;
	}

	public function checkDuplicate(InstagramUser $user_to_like)
	{
		//Weird error, null user. Check to be safe.
		if ($user_to_like === NULL) {
			echo("\n" . "NULL user");

			return TRUE;
		}

		//Check for duplicates.
		$liked_user = InstagramProfileLikeLog::where('insta_username', $this->profile->insta_username)
			->where('target_username', $user_to_like->getUsername())
			->first();

		//Duplicate = liked before.
		if ($liked_user !== NULL) {
			echo("\n" . "[Current] Duplicate log found:\t[" . $this->profile->insta_username . "] "
				. "[" . $user_to_like->getUsername() . "]");

			return TRUE;
		}

		//Check for duplicates.
		$liked_user = LikeLogsArchive::where('insta_username', $this->profile->insta_username)
			->where('target_username', $user_to_like->getUsername())
			->first();

		//Duplicate = liked before.
		if ($liked_user !== NULL) {
			echo("\n" . "[Archive] Duplicate Log Found:\t[" . $this->profile->insta_username . "] "
				. "[" . $user_to_like->getUsername() . "]");

			return TRUE;
		}

		return FALSE;
	}

	public function checkBlacklistAndDuplicates(InstagramUser $user_to_like, $page_count)
	{

		$ig_profile = $this->profile;
		$ig_username = $ig_profile->insta_username;

		//Blacklisted username.
		$blacklisted_username = BlacklistedUsername::find($user_to_like->getUsername());
		if ($blacklisted_username !== NULL) {
			if ($page_count === 1) { //if stuck on page 1 - straight on to subsequent pages.
				return 1;
			} else {
				if ($page_count === 2) { //if stuck on page 2 - continue browsing.
					return 2;
				}
			}
		}

		//Check for duplicates.
		$liked_users = InstagramProfileLikeLog::where('insta_username', $ig_username)
			->where('target_username', $user_to_like->getUsername())
			->first();

		//Duplicate = liked before.
		if (count($liked_users) > 0) {
			echo("\n" . "[Current] Duplicate Log Found:\t[$ig_username] [" . $user_to_like->getUsername() . "]");
			if ($page_count === 1) { //if stuck on page 1 - straight on to subsequent pages.
				return 1;
			} else {
				if ($page_count === 2) { //if stuck on page 2 - continue browsing.
					return 2;
				}
			}
		}

		//Check for duplicates.
		$liked_users_archive = LikeLogsArchive::where('insta_username', $ig_username)
			->where('target_username', $user_to_like->getUsername())
			->first();

		//Duplicate = liked before.
		if (count($liked_users_archive) > 0) {
			echo("\n" . "[Archive] Duplicate Log Found:\t[$ig_username] [" . $user_to_like->getUsername() . "]");

			if ($page_count === 1) { //if stuck on page 1 - straight on to subsequent pages.
				return 1;
			} else {
				if ($page_count === 2) { //if stuck on page 2 - continue browsing.
					return 2;
				}
			}
		}

		return 0;
	}

	public function checkValidTargetUsername(Instagram $instagram, $target_username)
	{
		$target_username_id = NULL;
		try {
			$target_username_id = $instagram->people->getUserIdForName(trim($target_username->target_username));
			if ($target_username->last_checked === NULL) {
				$target_response = $instagram->people->getInfoById($target_username_id);
				if ($target_response->getUser()->getFollowerCount() < 10000) {
					$target_username->insufficient_followers = 1;
					echo "[" . $this->profile->insta_username . "] [" . $target_username->target_username . "] has insufficient followers.\n";
				}
				$target_username->save();
			}
		} catch (InstagramException $insta_ex) {
			$target_username_id = NULL;
			$target_username->invalid = 1;
			$target_username->save();
			echo "\n[" . $this->profile->insta_username . "] encountered error [" . $target_username->target_username . "]: " . $insta_ex->getMessage() . "\n";
			$this->handleInstagramException($this->profile->insta_username, $insta_ex);
		}

		return $target_username_id;
	}

	private function randomizeUseHashtags()
	{
		$use_hashtags = rand(0, 1);
		if ($use_hashtags == 1 && count($this->targeted_hashtags) == 0) {
			$use_hashtags = 0;
		} else {
			if ($use_hashtags == 0 && count($this->targeted_usernames) == 0) {
				$use_hashtags = 1;
			}
		}
		echo "[Use Hashtags] Value: " . $use_hashtags . "\n";

		return $use_hashtags;
	}

	public function handleInstagramException(InstagramProfile $ig_profile, InstagramException $ex)
	{
		$this->like_quota = 0;
		$ig_username = $ig_profile->insta_username;
		dump($ex);
		if (strpos($ex->getMessage(), 'Throttled by Instagram because of too many API requests') !== FALSE) {
			$ig_profile->next_like_time = Carbon::now()->addHours(2);
			$ig_profile->save();
			echo "\n[$ig_username] has next_like_time shifted forward to " . Carbon::now()->addHours(2)->toDateTimeString() . "\n";

			return;
		} else {
			if ($ex instanceof FeedbackRequiredException) {
				if ($ex->hasResponse()) {
					$feedback_required_response = $ex->getResponse();
					if (strpos($feedback_required_response->fullResponse->feedback_message, 'This action was blocked. Please try again later. We restrict certain content and actions to protect our community. Tell us if you think we made a mistake') !== FALSE) {
						$ig_profile->next_like_time = Carbon::now()->addHours(4);
						$ig_profile->auto_like_ban = 1;
						$ig_profile->auto_like_ban_time = Carbon::now()->addHours(4);
						$ig_profile->save();
						echo "\n[$ig_username] was blocked & has next_like_time shifted forward to " . Carbon::now()->addHours(2)->toDateTimeString() . "\n";

						return;
					} else {
						if (strpos($feedback_required_response->fullResponse->feedback_message, 'It looks like your profile contains a link that is not allowed') !== FALSE) {
							$ig_profile->next_like_time = Carbon::now()->addHours(1);
							$ig_profile->invalid_proxy = 1;
							$ig_profile->save();
							echo "\n[$ig_username] has invalid proxy & next_like_time shifted forward to " . Carbon::now()->addHours(1)->toDateTimeString() . "\n";

							return;
						} else {
							if (strpos($feedback_required_response->fullResponse->feedback_message, 'It looks like you were misusing this feature by going too fast') !== FALSE) {
								$ig_profile->next_like_time = Carbon::now()->addHours(4);
								$ig_profile->auto_like_ban = 1;
								$ig_profile->auto_like_ban_time = Carbon::now()->addHours(4);
								$ig_profile->save();
								echo "\n[$ig_username] is going too fast & next_like_time shifted forward to " . Carbon::now()->addHours(1)->toDateTimeString() . "\n";

								return;
							}
						}
					}
				}
				$ig_profile->error_msg = $ex->getMessage();
			} else {
				if ($ex instanceof CheckpointRequiredException) {
					$ig_profile->checkpoint_required = 1;
					$ig_profile->error_msg = $ex->getMessage();
				} else {
					if ($ex instanceof NetworkException) {

					} else {
						if ($ex instanceof EndpointException) {
							if ($ex->getMessage() === "InstagramAPI\Response\LoginResponse: The username you entered doesn't appear to belong to an account. Please check your username and try again.") {
								$ig_profile->error_msg = $ex->getMessage();
							} else {
								if ($ex->getMessage() === "InstagramAPI\Response\LoginResponse: The username you entered doesn't appear to belong to an account. Please check your username and try again.") {
									$ig_profile->invalid_user = 1;
								}
							}
						} else {
							if ($ex instanceof IncorrectPasswordException) {
								$ig_profile->incorrect_pw = 1;
							} else {
								if ($ex instanceof AccountDisabledException) {
									$ig_profile->account_disabled = 1;
								} else {
									if ($ex instanceof ThrottledException) {
										$ig_profile->next_like_time = Carbon::now()->addHours(2);
										$ig_profile->auto_like_ban = 1;
										$ig_profile->auto_like_ban_time = Carbon::now()->addHours(2);
										$ig_profile->save();
										echo "\n[$ig_username] got throttled & next_like_time shifted forward to " . Carbon::now()->addHours(1)->toDateTimeString() . "\n";

										return;
									}
								}
							}
						}
					}
				}
			}
		}

		if ($ex->hasResponse()) {
			dump($ex->getResponse());
		} else {
			echo("\nThis exception has no response.\n");
		}

		$ig_profile->save();
	}

	private function calcSpeedDelay($speed)
	{
		$speed_delay = 3;
		if ($speed == "Fast") {
			$speed_delay = 1;
		}
		$this->speed_delay = $speed_delay;
	}

	public function getInstagram(): Instagram
	{
		return $this->instagram;
	}

	public function getProfile(): InstagramProfile
	{
		return $this->profile;
	}

}
