<?php

namespace App;


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

  public static function unfollowUseIsEmpty(){
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
    
}
