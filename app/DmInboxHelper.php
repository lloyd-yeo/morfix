<?php

namespace App;
use InstagramAPI\Instagram as Instagram;
use App\InstagramHelper;
use App\InstagramProfile;

class DmInboxHelper{

  public static function getInbox(Instagram $instagram){
     $inbox = $instagram->direct->getInbox();
     return (sizeof($inbox) > 0)? $inbox->inbox->threads : null;
  }

  public static function getThread($threadId){
    $threadResponse = $instagram->direct->getThread($thread_id);
    return $threadResponse->thread;
  }

  public static function extractItems($thread){
    return $thread->items;
  }

  public static function extractUsers($thread){
    return $thread->users;
  }
}