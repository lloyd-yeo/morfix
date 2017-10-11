<?php

namespace App;
use InstagramAPI\Instagram as Instagram;
use App\InstagramHelper;
use App\InstagramProfile;
use LazyJsonMapper\LazyJsonMapper;

class DmInboxHelper extends LazyJsonMapper{

public static function retrieve(Instagram $instagram){
   $response = $instagram->direct->getInbox();
   if($response->getInbox()->getThreads()){
      $threads = (array)$response->getInbox()->getThreads();
      $i = 0;
      foreach ($threads as $thread) {
          $i++;
          echo "\t Thread => $i\n";
          $threadResponse = $instagram->direct->getThread($thread->getThreadId());
          DmInboxHelper::extractItems((array)$threadResponse->getThread());
      }
    return $response->getInbox();
   }
   else{ 
    echo "\t Inbox is Empty.\n\n All Resuts => ".json_encode($responseArray);
    return null;
   }
  }

  public static function extractThreads($inbox){
    return (array)$inbox->getThreads();
  }

  public static function extractUsers($thread){
    return (array)$thread->getUsers();
  }

  public static function extractItems($thread){
    echo json_encode($thread)."\n";
    return (array)$thread->getItems();
  }

  public static function getThread(Instagram $instagram, $threadId){
    $threadResponse = $instagram->direct->getThread($thread_id);
    return (array)$threadResponse->getThread();
  }

  public static function handleInstagramException($ex){
    echo "Error  => ".$ex->getMessage()."\n";
  }
}