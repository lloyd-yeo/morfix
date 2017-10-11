<?php

namespace App;

use InstagramAPI\Exception\AccountDisabledException;
use InstagramAPI\Exception\CheckpointRequiredException;
use InstagramAPI\Exception\EndpointException;
use InstagramAPI\Exception\IncorrectPasswordException;
use InstagramAPI\Exception\NetworkException;
use InstagramAPI\Exception\RequestException;
use InstagramAPI\Instagram as Instagram;

class InteractionCommentHelper
{

	public static function unengaged($ig_profile, Instagram $instagram)
	{
		$ig_username = $ig_profile->insta_username;
		$engaged_user = NULL;
		try {
			$comments = InstagramProfileComment::where('insta_username', $ig_username)->get();

			if ($comments->isEmpty()) {
				return;
			}
			$comment = $comments->random();
			echo($comment->comment . "\n");
			$commentText = $comment->comment;
			$commented = FALSE;
			$user_instagram_id = NULL;

			$unengaged_followings = InstagramProfileFollowLog::where('insta_username', $ig_username)
				->orderBy('date_inserted', 'desc')
				->take(20)
				->get();

			echo "[$ig_username] Number of unengaged followings " . count($unengaged_followings) . "\n";

			$real_unengaged_followings_count = InteractionCommentHelper::realUnengagedFollowingsCounter($ig_profile, $unengaged_followings);

			if ($real_unengaged_followings_count == 0) {
				/*
				  Unengaged Likings
				 */
				$engaged_user = InteractionCommentHelper::unEngagedLiking($ig_profile, $instagram, $commentText);
			} else {
				/*
				  Unengaged Followings
				 */
				$engaged_user = InteractionCommentHelper::unEngagedFollowings($ig_profile, $instagram, $unengaged_followings, $commentText);
			}
		} catch (CheckpointRequiredException $checkpt_ex) {
			InteractionCommentHelper::handleInstagramException($ig_profile, $checkpt_ex, $engaged_user);
		} catch (IncorrectPasswordException $incorrectpw_ex) {
			InteractionCommentHelper::handleInstagramException($ig_profile, $incorrectpw_ex, $engaged_user);
		} catch (EndpointException $endpoint_ex) {
			InteractionCommentHelper::handleInstagramException($ig_profile, $endpoint_ex, $engaged_user);
		} catch (NetworkException $network_ex) {
			InteractionCommentHelper::handleInstagramException($ig_profile, $network_ex, $engaged_user);
		} catch (AccountDisabledException $acctdisabled_ex) {
			InteractionCommentHelper::handleInstagramException($ig_profile, $acctdisabled_ex, $engaged_user);
		} catch (RequestException $request_ex) {
			InteractionCommentHelper::handleInstagramException($ig_profile, $request_ex, $engaged_user);
		} catch (\Exception $ex) {
			dump($ex);
		}
	}

	public static function realUnengagedFollowingsCounter($ig_profile, $unengaged_followings)
	{
		$ig_username = $ig_profile->insta_username;

		$real_unengaged_followings_count = 0;
		foreach ($unengaged_followings as $unengaged_following) {
			if (InstagramProfileCommentLog::where('insta_username', $unengaged_following->insta_username)
					->where('target_username', $unengaged_following->follower_username)
					->count() > 0) {
				echo("[Initial Check][$ig_username] has engaged before " . $unengaged_following->follower_username . "\n");
				continue;
			}
			$real_unengaged_followings_count++;
		}
		echo "[$ig_username] real unengaged followings count = $real_unengaged_followings_count \n";

		return $real_unengaged_followings_count;
	}

	public static function unEngagedLiking($ig_profile, Instagram $instagram, $commentText)
	{
		$ig_username = $ig_profile->insta_username;
		$engaged_user = NULL;
		$unengaged_likings = InstagramProfileLikeLog::where('insta_username', $ig_username)
			->orderBy('date_liked', 'desc')
			->take(20)
			->get();

		foreach ($unengaged_likings as $unengaged_liking) {
			try {

				if (InstagramProfileCommentLog::where('insta_username', $unengaged_liking->insta_username)
						->where('target_username', $unengaged_liking->target_username)
						->count() > 0) {
					echo("[Like][$ig_username] has engaged before " . $unengaged_liking->target_username . "\n");
					continue;
				}

				echo("[$ig_username] unengaged likes: \t" . $unengaged_liking->target_username . "\n");

				$engaged_user = $unengaged_liking->target_username;
				try {
					$user_instagram_id = $instagram->people->getUserIdForName($unengaged_liking->target_username);
				} catch (RequestException $request_ex) {
					if ($request_ex->getMessage() === "InstagramAPI\Response\UserInfoResponse: User not found.") {
						$comment_log = new InstagramProfileCommentLog;
						$comment_log->insta_username = $ig_username;
						$comment_log->target_username = $unengaged_liking->target_username;
						$comment_log->log = $request_ex->getMessage();
						$comment_log->save();
					}
					echo("[$ig_username] #Followings Failed to get username id: " . $request_ex->getMessage() . "\n");
				} catch (\Exception $ex) {
					dump($ex);
				}

				if ($user_instagram_id === NULL) {
					continue;
				}

				echo "[" . $ig_username . "] retrieving feed of " . $unengaged_liking->target_username .
					"[" . $user_instagram_id . "]\n";

				$user_feed = $instagram->timeline->getUserFeed($user_instagram_id);
				$user_feed_items = $user_feed->getItems();

				foreach ($user_feed_items as $item) {
					$comment_log = new InstagramProfileCommentLog;
					$comment_log->insta_username = $ig_username;
					$comment_log->target_username = $unengaged_liking->target_username;
					$comment_log->target_insta_id = $user_instagram_id;
					$comment_log->target_media = $item->getId();
					$comment_log->save();

					$comment_resp = $instagram->media->comment($item->getId(), $commentText);
					$comment_log->log = serialize($comment_resp);
					if ($comment_log->save()) {
						echo("[$ig_username] has commented on [" . $item->getItemUrl() . "]\n");
					}

					$commented = TRUE;
					$ig_profile->next_comment_time = \Carbon\Carbon::now()->addMinutes(rand(10, 12));
					$ig_profile->save();
					break;
				}

				if ($commented) {
					break;
				}
			} catch (\Exception $ex) {
				dump($ex);
			}
		}

		return $engaged_user;
	}

	public static function unEngagedFollowings($ig_profile, Instagram $instagram, $unengaged_followings, $commentText)
	{
		$ig_username = $ig_profile->insta_username;
		$engaged_user = NULL;
		foreach ($unengaged_followings as $unengaged_following) {
			if (InstagramProfileCommentLog::where('insta_username', $unengaged_following->insta_username)
					->where('target_username', $unengaged_following->follower_username)
					->count() > 0) {
				echo("[Follow][$ig_username] has engaged before " . $unengaged_following->follower_username . "\n");
				continue;
			}

			echo("[$ig_username] unengaged followings: \t" . $unengaged_following->follower_username . "\n");
			$engaged_user = $unengaged_following->target_username;

			try {
				$user_instagram_id = $instagram->people->getUserIdForName($unengaged_following->follower_username);
			} catch (RequestException $request_ex) {
				if ($request_ex->getMessage() === "InstagramAPI\Response\UserInfoResponse: User not found.") {
					$comment_log = new InstagramProfileCommentLog;
					$comment_log->insta_username = $ig_username;
					$comment_log->target_username = $unengaged_following->follower_username;
					$comment_log->log = $request_ex->getMessage();
					$comment_log->save();
				}
				echo("[$ig_username] #Followings Failed to get username id: " . $request_ex->getMessage() . "\n");
			}

			if ($user_instagram_id === NULL) {
				continue;
			}

			$user_feed = $instagram->timeline->getUserFeed($user_instagram_id);
			$user_feed_items = $user_feed->getItems();

			foreach ($user_feed_items as $item) {

				$comment_log = new InstagramProfileCommentLog;
				$comment_log->insta_username = $ig_username;
				$comment_log->target_username = $unengaged_following->follower_username;
				$comment_log->target_insta_id = $user_instagram_id;
				$comment_log->target_media = $item->getId();
				$comment_log->save();
				$comment_resp = $instagram->media->comment($item->getId(), $commentText);
				$comment_log->log = serialize($comment_resp);
				if ($comment_log->save()) {
					echo("[$ig_username] has commented on [" . $item->getItemUrl() . "]\n");
				}

				$commented = TRUE;
				$ig_profile->next_comment_time = \Carbon\Carbon::now()->addMinutes(rand(10, 12));
				$ig_profile->save();

				break;
			}


			if ($commented) {
				break;
			}
		}

		return $engaged_user;
	}

	public static function handleInstagramException($ig_profile, $ex, $engaged_user)
	{
		$ig_username = $ig_profile->insta_username;
		if ($ex instanceof CheckpointRequiredException) {
			echo("checkpt1 " . $ex->getMessage() . "\n");
			$ig_profile->checkpoint_required = 1;
			$ig_profile->save();
		} else {
			if ($ex instanceof IncorrectPasswordException) {
				echo("incorrectpw1 " . $ex->getMessage() . "\n");
				$ig_profile->incorrect_pw = 1;
				$ig_profile->save();
			} else {
				if ($ex instanceof EndpointException) {

					if ($ex->getMessage() === "InstagramAPI\Response\UserInfoResponse: User not found.") {
						$comment_log = new InstagramProfileCommentLog;
						$comment_log->insta_username = $ig_username;
						$comment_log->target_username = $engaged_user;
						$comment_log->save();
					} else {
						if ($ex->getMessage() === "InstagramAPI\Response\UserFeedResponse: Not authorized to view user.") {
							$comment_log = new InstagramProfileCommentLog;
							$comment_log->insta_username = $ig_username;
							$comment_log->target_username = $engaged_user;
							$comment_log->save();
						}
					}

					echo("endpt1 " . $ex->getMessage() . "\n");
				} else {
					if ($ex instanceof NetworkException) {

						echo("network1 " . $ex->getMessage() . "\n");
					} else {
						if ($ex instanceof AccountDisabledException) {

							echo("acctdisabled1 " . $ex->getMessage() . "\n");

							$ig_profile->account_disabled = 1;
							$ig_profile->save();
						} else {
							if ($ex instanceof RequestException) {
								if ($ex->getMessage() === "InstagramAPI\Response\CommentResponse: Feedback required.") {
									if ($ex->hasResponse()) {
										$full_response = $ex->getResponse()->fullResponse;

										if ($full_response->spam === TRUE) {
											$ig_profile->auto_comment_ban = 1;
											$ig_profile->auto_comment_ban_time = \Carbon\Carbon::now()->addHours(6);
											$ig_profile->next_comment_time = \Carbon\Carbon::now()->addHours(6);
											if ($ig_profile->save()) {
												echo("[" . $ig_profile->username . "] commenting has been banned till " . $ig_profile->auto_comment_ban_time);
											}
										}
									}
								} else {
									echo("[ENDING] Request Exception: " . $ex->getMessage() . "\n");
									var_dump($ex->getResponse());
								}
								$ig_profile->error_msg = $ex->getMessage();
								$ig_profile->save();
							}
						}
					}
				}
			}
		}
	}

}
