<?php

namespace App;
use App\Niche;
use App\InteractionHelper;


class TargetHelper{

  public function getUsernameByNiche($ig_profile, $like_quota){
      $niche = Niche::find($ig_profile->niche);
      $niche_targets = $niche->targetUsernames();

      foreach ($niche_targets as $target_username) {
              //Get followers of the target.
              echo("\n" . "[$ig_username] Target Username: " . $target_username->target_username . "\n");

              $target_target_username = $target_username->target_username;

              $target_username_id = $instagram->people->getUserIdForName(trim($target_target_username));

              $user_follower_response = NULL;

              if ($target_username_id != "") {

                  $next_max_id = null;

                  $page_count = 0;

                  do {

                      echo "\n[$ig_username] requesting [$target_target_username] with: " . $next_max_id . "\n";

                      if ($next_max_id === NULL) {
                          $user_follower_response = $instagram->people->getFollowers($target_username_id);
                      } else {
                          $user_follower_response = $instagram->people->getFollowers($target_username_id, NULL, $next_max_id);
                      }

                      $target_user_followings = $user_follower_response->users;

                      echo "\n[$ig_username] requesting [$target_target_username] got us a list of [" . count($target_user_followings) . "] users. \n";

                      $duplicate = 0;

                      $next_max_id = $user_follower_response->next_max_id;

                      echo "\n[$ig_username] next_max_id for [$target_target_username] is " . $next_max_id;

                      $page_count++;

                      //Foreach follower of the target.
                      foreach ($target_user_followings as $user_to_like) {

                          if ($like_quota > 0) {

                              //Blacklisted username.
                              $blacklisted_username = BlacklistedUsername::find($user_to_like->username);
                              if ($blacklisted_username !== NULL) {
                                  continue;
                              }

                              echo("\n" . $user_to_like->username . "\t" . $user_to_like->pk);

                              //Check for duplicates.
                              $liked_users = InstagramProfileLikeLog::where('insta_username', $ig_username)
                                      ->where('target_username', $user_to_like->username)
                                      ->first();

                              //Duplicate = liked before.
                              if (count($liked_users) > 0) {
                                  echo("\n" . "[Current] Duplicate Log Found:\t[$ig_username] [" . $user_to_like->username . "]");
                                  continue;
                              }

                              //Check for duplicates.
                              $liked_users = LikeLogsArchive::where('insta_username', $ig_username)
                                      ->where('target_username', $user_to_like->username)
                                      ->first();

                              //Duplicate = liked before.
                              if (count($liked_users) > 0) {
                                  echo("\n" . "[Archive] Duplicate Log Found:\t[$ig_username] [" . $user_to_like->username . "]");
                                  continue;
                              }

                              //Get the feed of the user to like.
                              $user_feed_response = NULL;

                              try {
                                  if (is_null($user_to_like)) {
                                      echo("\n" . "Null User - Target Username");
                                      continue;
                                  }
                                  $user_feed_response = $instagram->timeline->getUserFeed($user_to_like->pk);
                              } catch (\InstagramAPI\Exception\EndpointException $endpt_ex) {

                                  echo("\n" . "Endpoint ex: " . $endpt_ex->getMessage());

                                  if ($endpt_ex->getMessage() == "InstagramAPI\Response\UserFeedResponse: Not authorized to view user.") {
                                      $blacklist_username = new BlacklistedUsername;
                                      $blacklist_username->username = $user_to_like->username;
                                      $blacklist_username->save();
                                      echo("\n" . "Blacklisted: " . $user_to_like->username);
                                  }
                                  continue;
                              } catch (\Exception $ex) {
                                  echo("\n" . "Exception: " . $ex->getMessage());
                                  continue;
                              }

                              //Get the media posted by the user.
                              $user_items = $user_feed_response->items;
                              //Foreach media posted by the user.
                              foreach ($user_items as $item) {

                                  if (InstagramProfileLikeLog::where('insta_username', $ig_username)->where('target_media', $item->id)->count() > 0) {
                                      #duplicate. Liked before this photo with this id.
                                      continue;
                                  }

                                  if ($like_quota > 0) {

                                      //Check for duplicates.
                                      $liked_logs = LikeLogsArchive::where('insta_username', $ig_username)
                                              ->where('target_media', $item->id)
                                              ->first();

                                      //Duplicate = liked media before.
                                      if (count($liked_logs) > 0) {
                                          echo("\n" . "Duplicate Log [MEDIA] Found:\t[$ig_username] [" . $item->id . "]");
                                          continue;
                                      }

                                      $like_response = $instagram->media->like($item->id);

                                      if ($like_response->status == "ok") {
                                          try {
                                              echo("\n" . "[$ig_username] Liked " . serialize($like_response));
                                              $like_log = new InstagramProfileLikeLog;
                                              $like_log->insta_username = $ig_username;
                                              $like_log->target_username = $user_to_like->username;
                                              $like_log->target_media = $item->id;
                                              $like_log->target_media_code = $item->getItemUrl();
                                              $like_log->log = serialize($like_response);
                                              $like_log->save();
                                              $like_quota--;

                                              $ig_profile->next_like_time = \Carbon\Carbon::now()->addMinutes($speed_delay);
                                              $ig_profile->save();
                                          } catch (\Exception $ex) {
                                              echo "[$ig_username] saving error [target_username] " . $ex->getMessage() . "\n";
                                              continue;
                                          }
                                      }
                                  } else {
                                      break;
                                  }
                              }
                          } else {
                              break;
                          }
                      }
                  } while ($next_max_id !== NULL && $like_quota > 0);
          }
      }
  }


}