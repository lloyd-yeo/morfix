<?php

namespace App;
use App\InstagramProfileLikeLog;


class InteractionsHelper{

  public static function like($ig_username, $like_response, $user_to_like, $item){
     if ($like_response->status == "ok") {
        echo("\n" . "[$ig_username] Liked " . serialize($like_response));
        $like_log = new InstagramProfileLikeLog;
        $like_log->insta_username = $ig_username;
        $like_log->target_username = $user_to_like->username;
        $like_log->target_media = $item->id;
        $like_log->target_media_code = $item->getItemUrl();
        $like_log->log = serialize($like_response);
        $like_log->save();
        return true;
        
    }
    return false;
  }

}