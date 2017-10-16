<?php

namespace App\Listeners;

use App\Events\EngagementGroupFailed;
use App\Notifications\EngagementGroupFailed as EngagementGroupFailedNotification;
use Notification;

class LogEngagementGroupFailed
{
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  EngagementGroupFailed $event
	 * @return void
	 */
	public function handle(EngagementGroupFailed $event)
	{
		if ($event->media_id !== NULL) {
			Notification::send(NULL, new EngagementGroupFailedNotification($event->media_id));
		}
	}
}
