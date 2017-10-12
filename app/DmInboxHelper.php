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
          echo json_encode($thread)."\n\t Thread => $i\n";
          $threadResponse = $instagram->direct->getThread($thread->getThreadId());
          DmInboxHelper::extract($threadResponse->getThread());
      }
    return $response->getInbox();
   }
   else{ 
    echo "\t Inbox is Empty.\n\n All Resuts => ".json_encode($responseArray);
    return null;
   }
  }

  public static function extractThreads($inbox){
    return $inbox->getThreads();
  }

  public static function extract($thread){
    DmInboxHelper::extractUsers($thread);
    DmInboxHelper::extractIndexes(DmInboxHelper::extractItems($thread));
  }

  public static function extractUsers($thread){
    $users = $thread->getUsers();
    echo json_encode($users)."\n";
    return $users;
  }

  public static function extractItems($thread){
    $items = $thread->getItems();
    echo json_encode($items)."\n";
    return $items;
  }

  public static function extractIndexes($objects){
    foreach ($objects as $object) {
      echo "\t\t".print_r($object)."\n";
    }
  }

  public static function getThread(Instagram $instagram, $threadId){
    $threadResponse = $instagram->direct->getThread($thread_id);
    return $threadResponse->getThread();
  }

  public static function handleInstagramException($ex){
    echo "Error  => ".$ex->getMessage()."\n";
  }
}