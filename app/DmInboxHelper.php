<?php

namespace App;

use InstagramAPI\Instagram as Instagram;
use LazyJsonMapper\LazyJsonMapper;

class DmInboxHelper extends LazyJsonMapper
{

	public static function retrieve(Instagram $instagram)
	{
		$response = $instagram->direct->getInbox();
		if ($response->getInbox()->getThreads()) {
			$threads = (array)$response->getInbox()->getThreads();
			$i = 0;

			foreach ($threads as $thread) {
				$i++;
				echo json_encode($thread) . "\n\t Thread => $i\n";
				$threadResponse = $instagram->direct->getThread($thread->getThreadId());
				DmInboxHelper::extract($threadResponse->getThread());
			}

			return $response->getInbox();
		} else {
			echo "\t Inbox is Empty.\n\n All Resuts => " . json_encode($response);

			return NULL;
		}
	}

	public static function extractThreads($inbox)
	{
		return $inbox->getThreads();
	}

	public static function extract($thread)
	{
		DmInboxHelper::extractIndexes(DmInboxHelper::extractUsers($thread));
		DmInboxHelper::extractIndexes(DmInboxHelper::extractItems($thread));
	}

	public static function extractUsers($thread)
	{
		$users = $thread->getUsers();
		echo json_encode($users) . "\n";

		return $users;
	}

	public static function extractItems($thread)
	{
		$items = $thread->getItems();
		echo json_encode($items) . "\n";

		return $items;
	}

	public static function extractIndexes($objects)
	{
		foreach ($objects as $object) {
			echo "\t\t" . print_r($object) . "\n";
		}
	}

	public static function getThread(Instagram $instagram, $threadId)
	{
		$threadResponse = $instagram->direct->getThread($threadId);

		return $threadResponse->getThread();
	}

	public static function handleInstagramException($ex)
	{
		echo "Error  => " . $ex->getMessage() . "\n";
	}
}