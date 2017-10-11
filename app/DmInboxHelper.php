<?php

namespace App;
use InstagramAPI\Instagram as Instagram;
use App\InstagramHelper;
use App\InstagramProfile;

class DmInboxHelper{
  public static function retrieve(Instagram $instagram){
    try{
         $response = $instagram->direct->getInbox();
         $responseArray = (array)$response;
         if(isset($responseArray['threads'])){
            $threads = $responseArray['threads'];
            $i = 0;
            foreach ($threads as $thread) {
                $i++;
                $threadResponse = $instagram->direct->getThread($thread->thread_id);
                DmInboxHelper::manage($threadResponse->thread);
            }
          return $inbox;
         }
         else{ 
          echo "\t Inbox is Empty.\n\n All Resuts => ".json_encode($responseArray);
          return null;
         }
    }catch (CheckpointRequiredException $ex) {
      DmInboxHelper::handleInstagramException($ex);
      return false;
    } catch (IncorrectPasswordException $ex) {
      DmInboxHelper::handleInstagramException($ex);
      return false;
    } catch (EndpointException $ex) {
      DmInboxHelper::handleInstagramException($ex);
      return false;
    } catch (NetworkException $ex) {
      DmInboxHelper::handleInstagramException($ex);
      return false;
    } catch (AccountDisabledException $ex) {
      DmInboxHelper::handleInstagramException($ex);
      return false;
    } catch (RequestException $ex) {
      DmInboxHelper::handleInstagramException($ex);
      return false;
    } catch (\Exception $ex) {
      DmInboxHelper::handleInstagramException($ex);
      return false;
    }
  }

  public static function manage($thread){
    $threadArray = (array)$thread;
    foreach ($thread as $key => $value) {
      if($key == 'users' || $key == "inviter" || $key == "last_seen_at"){
        echo "\t $key \n";
        DmInboxHelper::displayIndexes($threadArray[$key]);
      }
      else if($key == 'items'){
        echo "\t $key \n";
        $items = DmInboxHelper::extractItems($thread);
        foreach ($items as $item) {
          DmInboxHelper::displayIndexes($item);
        }
      }
      else{
        echo "\t $key => ".json_encode($value)."\n";
      }
    }
  }

  public static function getThread(Instagram $instagram, $threadId){
    $threadResponse = $instagram->direct->getThread($thread_id);
    return $threadResponse->thread;
  }

  public static function displayIndexes($object){
      foreach ($object as $key => $value) {
          echo "\t\t $key => ".json_encode($value)."\n";
      }
  }

  public static function extractItems($thread){
    return $thread->items;
  }

  public static function extractUsers($thread){
    return $thread->users;
  }

  public static function handleInstagramException($ex){
    echo "Error  => ".$ex->getMessage()."\n";
  }
}