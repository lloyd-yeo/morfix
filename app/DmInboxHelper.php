<?php

namespace App;
use InstagramAPI\Instagram as Instagram;
use App\InstagramHelper;
use App\InstagramProfile;
use LazyJsonMapper\LazyJsonMapper;

class DmInboxHelper extends LazyJsonMapper{

  public static function retrieve(Instagram $instagram){
    try{
         $response = $instagram->direct->getInbox();
         if($response->getInbox()->getThreads()){
            $threads = (array)$response->getInbox()->getThreads();
            $i = 0;
            foreach ($threads as $thread) {
                $i++;
                echo "\t Thread => $i\n";
                $threadResponse = $instagram->direct->getThread($thread->getThreadId());
                DmInboxHelper::manage($threadResponse->getThread());
            }
          return $response->getInbox();
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
    $items = (object)$thread->getItems();
    echo "\t\t Items\n".json_encode($items);
  }

  public static function getThread(Instagram $instagram, $threadId){
    $threadResponse = $instagram->direct->getThread($thread_id);
    return $threadResponse->getThread();
  }

  public static function displayIndexes($objects){
      foreach ((array)$objects as $object) {
        echo json_encode($object)."\n";
        foreach ((array)$object as $key => $value) {
          echo "\t\t\t $key => ".json_encode($value)."\n";
        } 
      }
  }

  public static function handleInstagramException($ex){
    echo "Error  => ".$ex->getMessage()."\n";
  }
}