<?php

namespace App;
use App\InstagramProfileFollowLog;


class InteractionFollowHelper
{
  public static function setSpeedDelay($speed){
    $delay;
    switch ($speed) {
      case 'Fast':
        $delay = 2;
        break;
      case 'Medium':
        $delay = 3;
        break;
      case 'Slow':
        $delay = 5;
        break;
      case 'Ultra Fast':
        $delay = 0;
        break;
      case 'weikian_':
        $delay = 0;
        break;
      default:
        $delay = 5;
        break;
    }
    return $delay;
  }

  public static function unfollowUserIsEmpty($ig_profile, $instagram, $insta_username){
      echo "[" . $insta_username . "] has no follows to unfollow.\n\n";

      #forced unfollow
      if ($auto_unfollow == 1 && $auto_follow == 0) {
          echo "[" . $insta_username . "] adding new unfollows..\n";
          #$followings = $instagram->getSelfUsersFollowing();
          $followings = $instagram->people->getSelfFollowing();
          foreach ($followings->users as $user) {

              try {
                  if (InstagramProfileFollowLog::where('insta_username', $insta_username)->where('follower_id', $user->pk)->count() > 0) {
                      continue;
                  }
                  $follow_log = new InstagramProfileFollowLog;
                  $follow_log->insta_username = $insta_username;
                  $follow_log->follower_username = $user->username;
                  $follow_log->follower_id = $user->pk;
                  $follow_log->follow_success = 1;
                  $follow_log->save();
              } catch (Exception $ex) {
                  echo "[" . $insta_username . "] " . $ex->getMessage() . "..\n";
                  continue;
              }
          }
      } else {
          $ig_profile->unfollow = 0;
          if ($ig_profile->save()) {
              echo "[" . $insta_username . "] is following next round.\n\n";
          }
      }
  }

  public static function unFollowUsers($instagram, $insta_username, $users_to_unfollow){
      foreach ($users_to_unfollow as $user_to_unfollow) {

          echo "[" . $insta_username . "] retrieved: " . $user_to_unfollow->follower_username . "\n";
          $current_log_id = $user_to_unfollow->log_id;

          if ($unfollow_unfollowed == 1) {
              $friendship = $instagram->people->getFriendship($user_to_unfollow->follower_id);
              if ($friendship->followed_by == true) {
                  echo "[" . $insta_username . "] is followed by " . $user_to_unfollow->follower_username . "\n";
                  $user_to_unfollow->unfollowed = 1;
                  $user_to_unfollow->date_unfollowed = \Carbon\Carbon::now();
                  if ($user_to_unfollow->save()) {
                      echo "[" . $insta_username . "] marked as unfollowed & updated log: " . $user_to_unfollow->log_id . "\n\n";
                  }
                  continue;
              }
          }

          #$resp = $instagram->unfollow($user_to_unfollow->follower_id);
          $resp = $instagram->people->unfollow($user_to_unfollow->follower_id);
          echo "[" . $insta_username . "] ";
    //                        var_dump($resp);

          if ($resp->friendship_status->following === false) {
              $user_to_unfollow->unfollowed = 1;
              $user_to_unfollow->date_unfollowed = \Carbon\Carbon::now();
              if ($user_to_unfollow->save()) {
                  echo "[" . $insta_username . "] marked as unfollowed & updated log: " . $user_to_unfollow->log_id . "\n\n";
              }

              $ig_profile->next_follow_time = \Carbon\Carbon::now()->addMinutes($delay)->toDateTimeString();
              $ig_profile->unfollow_quota = $ig_profile->unfollow_quota - 1;
              if ($ig_profile->save()) {
                  echo "[$insta_username] added $delay minutes of delay & new unfollow quota = " . $ig_profile->unfollow_quota;
              }
              break;
          }
      }
  }

  public static function handleInstragramException($ig_profile, $ex, $current_log_id){
      if($ex instanceof \InstagramAPI\Exception\CheckpointRequiredException) {
                  echo "[" . $insta_username . "] checkpoint_ex: " . $ex->getMessage() . "\n";
                  $ig_profile->checkpoint_required = 1;
                  $ig_profile->save();
      }
      else if($ex instanceof \InstagramAPI\Exception\NetworkException) {
            echo "[" . $insta_username . "] network_ex: " . $ex->getMessage() . "\n";
      } 
      else if($ex instanceof \InstagramAPI\Exception\EndpointException) {
            echo "[" . $insta_username . "] endpoint_ex: " . $ex->getMessage() . "\n";

            if (stripos(trim($ex->getMessage()), "Requested resource does not exist.") !== false) {
                $unfollow_log_to_update = InstagramProfileFollowLog::find($current_log_id);
                $unfollow_log_to_update->unfollowed = 1;
                $unfollow_log_to_update->save();
                $followed = 1;
                exit();
            }
      }
      else if($ex instanceof \InstagramAPI\Exception\IncorrectPasswordException) {
            echo "[" . $insta_username . "] incorrectpw_ex: " . $$ex->getMessage() . "\n";
            $ig_profile->incorrect_pw = 1;
            $ig_profile->save();
      } 
      else if($ex instanceof \InstagramAPI\Exception\FeedbackRequiredException) {
            echo "[" . $insta_username . "] feedback_ex: " . $ex->getMessage() . "\n";
      }
      else if($ex instanceof \InstagramAPI\Exception\EmptyResponseException) {
            echo "[" . $insta_username . "] emptyresponse_ex: " . $ex->getMessage() . "\n";
            if (stripos(trim($emptyresponse_ex->getMessage()), "No response from server. Either a connection or configuration error") !== false) {
                $unfollow_log_to_update = InstagramProfileFollowLog::find($current_log_id);
                $unfollow_log_to_update->unfollowed = 1;
                $unfollow_log_to_update->save();
                $followed = 1;
                exit();
            }
      }
      else if($ex instanceof \InstagramAPI\Exception\ThrottledException) {
            echo "[" . $insta_username . "] throttled_ex: " . $ex->getMessage() . "\n";
      } 
      else if($ex instanceof \InstagramAPI\Exception\RequestException) {
            echo "[" . $insta_username . "] request_ex: " . $ex->getMessage() . "\n";
            if (stripos(trim($ex->getMessage()), "feedback_required") !== false) {
                $ig_profile->feedback_required = 1;
                $ig_profile->save();
                $followed = 1;
                exit();
            }
      }
  }



    
}
