<?php

namespace App\Jobs;

use App\BlacklistedUsername;
use App\InstagramHelper;
use App\InstagramProfile;
use App\InstagramProfileLikeLog;
use App\InteractionHelper;
use App\LikeLogsArchive;
use App\Niche;
use App\RedisRepository;
use App\TargetHelper;
use Carbon\Carbon as Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Exception\AccountDisabledException as AccountDisabledException;
use InstagramAPI\Exception\ChallengeRequiredException;
use InstagramAPI\Exception\CheckpointRequiredException as CheckpointRequiredException;
use InstagramAPI\Exception\EndpointException as EndpointException;
use InstagramAPI\Exception\FeedbackRequiredException as FeedbackRequiredException;
use InstagramAPI\Exception\IncorrectPasswordException as IncorrectPasswordException;
use InstagramAPI\Exception\InstagramException as InstagramException;
use InstagramAPI\ExceptionetworkException as NetworkException;
use InstagramAPI\Exception\ThrottledException as ThrottledException;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\Response\Model\Item as InstagramItem;
use InstagramAPI\Response\Model\User as InstagramUser;
use Log;
use Illuminate\Support\Facades\Redis;

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
	public $timeout = 80;

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

	/**
	 * The Instagram API for the profile to interact with Instagram.
	 *
	 * @var Instagram
	 */
	protected $instagram;

	protected $time_start;
	protected $targeted_hashtags;
	protected $targeted_usernames;
	protected $speed_delay;

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
		$this->time_start = microtime(TRUE);
		DB::reconnect();

		$this->calcSpeedDelay($this->profile->speed);
		$this->instagram          = InstagramHelper::initInstagram();
		$this->like_quota         = rand(1, 3);
		$this->targeted_hashtags  = TargetHelper::getUserTargetedHashtags($this->profile);
		$this->targeted_usernames = TargetHelper::getUserTargetedUsernames($this->profile);

		$ig_profile  = $this->profile;
		$ig_username = $ig_profile->insta_username;

		$instagram = $this->getInstagram();
		$instagram = InstagramHelper::setProxy($instagram, $ig_profile, 1);

		try {
			if (InstagramHelper::login($instagram, $ig_profile, 1)) {

				echo("[INTERACTION LIKE] Successfully logged in.\n");

				$use_hashtags = $this->randomizeUseHashtags();

				if (!$use_hashtags) {

					foreach ($this->targeted_usernames as $target_username) {

						echo "[$ig_username][target_username] current like-quota: " . $this->like_quota . "\n";

						if ($this->like_quota > 0) {

							//Get followers of the target.
							Log::info("[$ig_username] Target Username: " . $target_username->target_username);
							echo("[$ig_username] Target Username: " . $target_username->target_username . "\n");

							$target_username_id = $this->checkValidTargetUsername($instagram, $target_username);
							if ($target_username_id === NULL) {
								echo("[$ig_username] target's ID is NULL\n");
								continue;
							} else {
								Log::info("[$ig_username] Retrieved Target Id: " . $target_username_id);
								echo("[$ig_username] Retrieved Target Id: " . $target_username_id . "\n");
							}

							$target_target_username = $target_username->target_username;
							$user_follower_response = NULL;
							$next_max_id            = NULL;

							$page_count = 0;

							do {
								Log::info("[$ig_username] requesting [$target_target_username] with: " . $next_max_id . "");
								$user_follower_response = InstagramHelper::getFollowersViaProfileId($instagram, $ig_profile, $target_username_id, $next_max_id);
								if ($user_follower_response == NULL) {
									Log::info("[$ig_username] failed to retrieve followers from: " . $target_target_username . "");
									echo("[$ig_username] failed to retrieve followers from: " . $target_target_username . "\n");
									continue;
								}

								echo("[$ig_username] successfully retrieved followers from: " . $target_target_username . "\n");

//								RedisRepository::saveUserFollowersResponse($user_follower_response, $target_username_id);

								$target_user_followings = $user_follower_response->getUsers();
								$next_max_id            = $user_follower_response->getNextMaxId();
								echo "[$ig_username] next_max_id for [$target_target_username] is " . $next_max_id . "\n";

								$page_count++;

								//Foreach follower of the target.
								foreach ($target_user_followings as $user_to_like) {

									if ($this->like_quota > 0) {

										Log::info("" . $user_to_like->getUsername() . "\t" . $user_to_like->getPk());
										echo "[$ig_username] checking " . $user_to_like->getUsername() . " (" . $user_to_like->getPk() . ")\n";

										$is_duplicate = $this->checkBlacklistAndDuplicates($user_to_like, $page_count);

										if ($is_duplicate == 1) {
											echo "[$ig_username][" . $user_to_like->getUsername() . "] is duplicate = 1\n";
											break;
										} else {
											if ($is_duplicate == 2) {
												echo "[$ig_username][" . $user_to_like->getUsername() . "] is duplicate = 2\n";
												continue;
											}
										}

										//Get the feed of the user to like.
										$user_feed_response = InstagramHelper::getUserFeed($instagram, $user_to_like);
										if ($user_feed_response == NULL) {
											echo "[$ig_username][" . $user_to_like->getUsername() . "] user's feed is NULL\n";
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
												Log::info("[146] Exiting...");
												$this->printElapsedTime($this->time_start, $ig_profile);

												return;
											}
										}
									} else {
										Log::info("[151] Exiting...");
										$this->printElapsedTime($this->time_start, $ig_profile);

										return;
									}
								}
							} while ($next_max_id !== NULL && $this->like_quota > 0);
						} else {
							Log::info("[157] Exiting...");
							$this->printElapsedTime($this->time_start, $ig_profile);

							return;
						}
					}

				}
				else {

					foreach ($this->targeted_hashtags as $target_hashtag) {

						echo "[$ig_username][target_hashtag] current like-quota: " . $this->like_quota . "\n";

						if ($this->like_quota > 0) {
							Log::info("" . "[$ig_username] Target Hashtag: " . $target_hashtag->hashtag . "");
							//Get the feed from the targeted hashtag.

							if (empty(trim($target_hashtag->hashtag))) {
								//							$target_hashtag->invalid = 1;
								//							$target_hashtag->save();
								continue;
							}
							$hashtag_feed = InstagramHelper::getHashtagFeed($instagram, $target_hashtag);
							if ($hashtag_feed !== NULL) {
								foreach ($hashtag_feed->getItems() as $item) {
									$user_to_like = $item->getUser();
									if ($this->like_quota > 0) {
										if (!$this->checkDuplicateByMediaId($item)) {
											if (!$this->like($user_to_like, $item)) {
												continue;
											}
										}
									} else {
										Log::info("[186] Exiting...");
										$this->printElapsedTime($this->time_start, $ig_profile);

										return;
									}
								}
							}
						} else {
							Log::info("[192] Exiting...");
							$this->printElapsedTime($this->time_start, $ig_profile);

							return;
						}
					}
				}

				echo "[$ig_username][niche] current like-quota: " . $this->like_quota . "\n";

				if ($this->like_quota > 0) {

					if ($this->profile->niche > 0) {

						$niche         = Niche::find($this->profile->niche);
						$niche_targets = $niche->targetUsernames();

						echo "[$ig_username] is using niche: " . $niche->niche . "\n";

						foreach ($niche_targets as $target_username) {

							echo "[$ig_username][niche_target_username] current like-quota: " . $this->like_quota . "\n";

							if ($this->like_quota > 0) {

								//Get followers of the target.
								Log::info("[$ig_username] Target Username: " . $target_username->target_username . "");
								echo "[$ig_username] is using niche: " . $niche->niche . "\n";
								$target_username_id = InstagramHelper::getUserIdForNicheUsername($instagram, $target_username);
								if ($target_username_id === NULL) {
									continue;
								}

								$target_target_username = $target_username->target_username;
								$user_follower_response = NULL;
								$next_max_id            = NULL;

								$page_count = 0;

								do {
									echo "[$ig_username] requesting [$target_target_username] with: " . $next_max_id . "\n";

									$user_follower_response = InstagramHelper::getFollowersViaProfileId($instagram, $ig_profile, $target_username_id, $next_max_id);
									$target_user_followings = $user_follower_response->getUsers();
									$next_max_id            = $user_follower_response->getNextMaxId();

									echo "[$ig_username] next_max_id for [$target_target_username] is " . $next_max_id . "\n";

									$page_count++;

									//Foreach follower of the target.
									foreach ($target_user_followings as $user_to_like) {

										if ($this->like_quota > 0) {

											Log::info("" . $user_to_like->getUsername() . "\t" . $user_to_like->getPk());

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

													Log::info("[264] Exiting...");
													$this->printElapsedTime($this->time_start, $ig_profile);

													return;
												}
											}
										} else {
											Log::info("[269] Exiting...");
											$this->printElapsedTime($this->time_start, $ig_profile);

											return;
										}
									}
								} while ($next_max_id !== NULL && $this->like_quota > 0);
							}
						}

						$niche           = Niche::find($ig_profile->niche);
						$target_hashtags = $niche->targetHashtags();

						foreach ($target_hashtags as $target_hashtag) {

							echo "[$ig_username][niche_target_hashtag] current like-quota: " . $this->like_quota . "\n";

							if ($this->like_quota > 0) {
								Log::info("" . "[$ig_username] Target Hashtag: " . $target_hashtag->hashtag . "");
								//Get the feed from the targeted hashtag.
								$hashtag_feed = InstagramHelper::getHashtagFeed($instagram, $target_hashtag);
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
											$this->printElapsedTime($this->time_start, $ig_profile);

											return;
										}
									}
								}
							}
						}
					}
				} else {
					$this->printElapsedTime($this->time_start, $this->profile);
					return;
				}
			}
		} catch (CheckpointRequiredException $checkpoint_ex) {
			$this->handleInstagramException($ig_profile, $checkpoint_ex);
		}
		catch (NetworkException $network_ex) {
			$this->handleInstagramException($ig_profile, $network_ex);
		}
		catch (EndpointException $endpoint_ex) {
			$this->handleInstagramException($ig_profile, $endpoint_ex);
		}
		catch (IncorrectPasswordException $incorrectpw_ex) {
			$this->handleInstagramException($ig_profile, $incorrectpw_ex);
		}
		catch (FeedbackRequiredException $feedback_ex) {
			$this->handleInstagramException($ig_profile, $feedback_ex);
		}
		catch (EmptyResponseException $emptyresponse_ex) {
			$this->handleInstagramException($ig_profile, $emptyresponse_ex);
		}
		catch (AccountDisabledException $acctdisabled_ex) {
			$this->handleInstagramException($ig_profile, $acctdisabled_ex);
		}
		catch (ThrottledException $throttled_ex) {
			$this->handleInstagramException($ig_profile, $throttled_ex);
		}
		catch (ChallengeRequiredException $challengeRequiredException) {
			$this->handleInstagramException($ig_profile, $challengeRequiredException);
		}
		catch (\Exception $ex) {
			dump($ex);
		}

		$this->printElapsedTime($this->time_start, $ig_profile);
	}

	/**
	 *
	 * @param InstagramUser $user_to_like
	 * @param InstagramItem $item
	 *
	 * @return boolean
	 */
	public function like(InstagramUser $user_to_like, InstagramItem $item)
	{
		$ig_profile    = $this->profile;
		$like_response = NULL;
		try {
			$like_response = $this->instagram->media->like($item->getId());

			echo "[" . $ig_profile->insta_username . "] liking [" . $user_to_like->getUsername() . "] (" . $item->getId() . ")\n";
//			dump($like_response);

			if ($like_response == NULL) {
				echo("" . "[" . $ig_profile->insta_username . "] like_response is NULL\n");
				return FALSE;
			} else {
				if ($like_response->getStatus() == "ok") {
					try {
						$this->like_quota = $this->like_quota - 1;
						Log::info("" . "[" . $ig_profile->insta_username . "] Liked " . serialize($like_response) . "");
						echo("" . "[" . $ig_profile->insta_username . "] Liked " . serialize($like_response) . "\n");
						Log::info("" . "[" . $ig_profile->insta_username . "] Remaining Round Quota: " . $this->like_quota);
						echo("" . "[" . $ig_profile->insta_username . "] Remaining Round Quota: " . $this->like_quota . "\n");

						RedisRepository::saveNewProfileLikeLog($ig_profile->insta_user_id, $item->getPk(), $item->getItemUrl(), Carbon::now()->getTimestamp());
//						RedisRepository::saveProfileLikedUsers($ig_profile->insta_user_id, $item->getUser()->getPk());

						$like_log                    = new InstagramProfileLikeLog;
						$like_log->insta_username    = $ig_profile->insta_username;
						$like_log->target_username   = $user_to_like->getUsername();
						$like_log->target_media      = $item->getPk();
						$like_log->target_media_code = $item->getItemUrl();
						$like_log->log               = serialize($like_response);
						if ($like_log->save()) {
							$ig_profile->next_like_time     = Carbon::now()->addMinutes($this->speed_delay);
							$ig_profile->auto_like_ban      = 0;
							$ig_profile->auto_like_ban_time = NULL;
							$ig_profile->save();

							return TRUE;
						} else {
							return FALSE;
						}

//						$ig_profile->next_like_time     = Carbon::now()->addMinutes($this->speed_delay);
//						$ig_profile->auto_like_ban      = 0;
//						$ig_profile->auto_like_ban_time = NULL;
//						$ig_profile->save();

						return TRUE;

					}
					catch (\Exception $ex) {
						echo "[" . $ig_profile->insta_username . "] saving error [target_username] " . $ex->getMessage() . "";

						return FALSE;
					}
				}
			}
		}
		catch (CheckpointRequiredException $checkpoint_ex) {
			$this->handleInstagramException($ig_profile, $checkpoint_ex);
		}
		catch (NetworkException $network_ex) {
			$this->handleInstagramException($ig_profile, $network_ex);
		}
		catch (EndpointException $endpoint_ex) {
			$this->handleInstagramException($ig_profile, $endpoint_ex);
		}
		catch (IncorrectPasswordException $incorrectpw_ex) {
			$this->handleInstagramException($ig_profile, $incorrectpw_ex);
		}
		catch (FeedbackRequiredException $feedback_ex) {
			$this->handleInstagramException($ig_profile, $feedback_ex);
		}
		catch (EmptyResponseException $emptyresponse_ex) {
			$this->handleInstagramException($ig_profile, $emptyresponse_ex);
		}
		catch (AccountDisabledException $acctdisabled_ex) {
			$this->handleInstagramException($ig_profile, $acctdisabled_ex);
		}
		catch (ThrottledException $throttled_ex) {
			$this->handleInstagramException($ig_profile, $throttled_ex);
		}
		catch (ChallengeRequiredException $challengeRequiredException) {
			$this->handleInstagramException($ig_profile, $challengeRequiredException);
		}
		catch (\Exception $ex) {
			dump($ex);
		}

		return FALSE;
	}

	public function checkDuplicateByMediaId(InstagramItem $item)
	{
		if (InstagramProfileLikeLog::where('insta_username', $this->profile->insta_username)->where('target_media', $item->getPk())->first() != NULL) {
			echo "[" . $this->profile->insta_username . "] has already liked media-id: [" . $item->getPk() . "] before\n";
			return TRUE;
		} else {
			return FALSE;
		}
//		return RedisRepository::checkDuplicateLikeLog($this->profile->insta_user_id, $item->getPk());
	}

	public function checkDuplicate(InstagramUser $user_to_like)
	{
		//Weird error, null user. Check to be safe.
		if ($user_to_like === NULL) {
			Log::info("" . "NULL user");
			return TRUE;
		}

		if (RedisRepository::checkDuplicateLikedUsers($this->profile->insta_user_id, $user_to_like->getPk())) {
			return TRUE;
		}

		return FALSE;
	}

	public function checkBlacklistAndDuplicates(InstagramUser $user_to_like, $page_count)
	{
		$ig_profile  = $this->profile;
		$ig_username = $ig_profile->insta_username;

		//Blacklisted username.
		if (RedisRepository::checkBlacklistPk($user_to_like->getPk())) {
			if ($page_count === 1) { //if stuck on page 1 - straight on to subsequent pages.
				return 1;
			} else {
				if ($page_count === 2) { //if stuck on page 2 - continue browsing.
					return 2;
				}
			}
		}

		if (RedisRepository::checkDuplicateLikedUsers($this->profile->insta_user_id, $user_to_like->getPk())) {
			Log::info("" . "[Current] Duplicate Log Found:\t[$ig_username] [" . $user_to_like->getUsername() . "]");
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
					echo "[" . $this->profile->insta_username . "] [" . $target_username->target_username . "] has insufficient followers.";
				}
				$target_username->save();
			}
		}
		catch (InstagramException $insta_ex) {
			$target_username_id       = NULL;
			$target_username->invalid = 1;
			$target_username->save();
			Log::info("[" . $this->profile->insta_username . "] encountered error [" . $target_username->target_username . "]: " . $insta_ex->getMessage() . "");
			$this->handleInstagramException($this->profile, $insta_ex);
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
		Log::info("[Use Hashtags] Value: " . $use_hashtags . "");
		echo("[Use Hashtags] Value: " . $use_hashtags . "\n");
		return $use_hashtags;
	}

	public function handleInstagramException(InstagramProfile $ig_profile, InstagramException $ex)
	{
		$this->like_quota = 0;
		$ig_username      = $ig_profile->insta_username;
		//		dump($ex);

		if (strpos($ex->getMessage(), 'Throttled by Instagram because of too many API requests') !== FALSE) {
			$ig_profile->next_like_time = Carbon::now()->addMinutes(15);
			$ig_profile->save();
			Log::info("[$ig_username] has next_like_time shifted forward to " . Carbon::now()->addHours(2)->toDateTimeString() . "");

			return;
		} else {
			if ($ex instanceof FeedbackRequiredException) {
				if ($ex->hasResponse()) {
					$feedback_response = $ex->getResponse()->asArray();
					$feedback_msg      = $feedback_response['feedback_message'];
					Log::error(print_r($ex, true));
					if (strpos($feedback_msg, 'This action was blocked. Please try again later. We restrict certain content and actions to protect our community. Tell us if you think we made a mistake') !== FALSE) {
						$ig_profile->next_like_time     = Carbon::now()->addHours(2);
						$ig_profile->auto_like_ban      = 1;
						$ig_profile->auto_like_ban_time = Carbon::now()->addHours(2);
						$ig_profile->save();
						Log::info("[$ig_username] was blocked & has next_like_time shifted forward to " . Carbon::now()->addHours(2)->toDateTimeString() . "");
						return;
					} else {
						if (strpos($feedback_msg, 'It looks like your profile contains a link that is not allowed') !== FALSE) {
							$ig_profile->next_like_time = Carbon::now()->addMinutes(15);
							$ig_profile->invalid_proxy  = 1;
							$ig_profile->save();
							Log::info("[$ig_username] has invalid proxy & next_like_time shifted forward to " . Carbon::now()->addHours(1)->toDateTimeString() . "");
							return;
						} else {
							if (strpos($feedback_msg, 'It looks like you were misusing this feature by going too fast') !== FALSE) {
								$ig_profile->next_like_time     = Carbon::now()->addHours(2);
								$ig_profile->auto_like_ban      = 1;
								$ig_profile->auto_like_ban_time = Carbon::now()->addHours(2);
								$ig_profile->save();
								Log::info("[$ig_username] is going too fast & next_like_time shifted forward to " . Carbon::now()->addHours(1)->toDateTimeString() . "");
								return;
							}
						}
					}
				}
				$ig_profile->error_msg = $ex->getMessage();
			} else {
				if ($ex instanceof CheckpointRequiredException) {
					$ig_profile->checkpoint_required = 1;
					$ig_profile->error_msg           = $ex->getMessage();
				} else {
					if ($ex instanceof NetworkException) {
						//						$ig_profile->invalid_proxy = $ig_profile->invalid_proxy + 1;
						//						$ig_profile->save();
						//						InstagramHelper::verifyAndReassignProxy($ig_profile);
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
										$ig_profile->next_like_time     = Carbon::now()->addHours(2);
										$ig_profile->auto_like_ban      = 1;
										$ig_profile->auto_like_ban_time = Carbon::now()->addHours(2);
										$ig_profile->save();
										Log::info("[$ig_username] got throttled & next_like_time shifted forward to " . Carbon::now()->addHours(1)->toDateTimeString() . "");

										return;
									} else {
										if ($ex instanceof ChallengeRequiredException) {
											$ig_profile->challenge_required = 1;
											$ig_profile->save();

											Log::info("[$ig_username] challenge required.");

											return;
										}
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
			Log::info("This exception has no response.");
		}

		$ig_profile->save();
	}

	private function calcSpeedDelay($speed)
	{
		switch ($speed) {
			case "Fast":
				$this->speed_delay = 2;
				break;
			case "Medium":
				$this->speed_delay = 4;
				break;
			case "Slow":
				$this->speed_delay = 6;
				break;
			default:
				$this->speed_delay = 6;
				break;
		}
	}

	public function getInstagram(): Instagram
	{
		return $this->instagram;
	}

	public function getProfile(): InstagramProfile
	{
		return $this->profile;
	}

	/**
	 * @param $time_start
	 * @param $ig_profile
	 */
	private function printElapsedTime($time_start, $ig_profile): void
	{
		$time_end = microtime(TRUE);
		$duration = ($time_end - $time_start);
		$hours    = (int)($duration / 60 / 60);
		$minutes  = (int)($duration / 60) - $hours * 60;
		$seconds  = (int)$duration - $hours * 60 * 60 - $minutes * 60;
		Log::info("[" . $ig_profile->insta_username . "] elapsed time " . $seconds . " seconds.");
	}

	/**
	 * The job failed to process.
	 *
	 * @param  Exception $exception
	 *
	 * @return void
	 */
	public function failed()
	{
		unset($this->instagram);
	}
}
