<?php

namespace App\Listeners;

use App\Events\UsersInteractionsFailed;
use App\Notifications\InteractionsFailed;
use Notification;

class LogUsersInteractionsFailed
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
	 * @param  UsersInteractionsFailed $event
	 * @return void
	 */
	public function handle(UsersInteractionsFailed $event)
	{
		if (!empty($event->failed_profiles)) {
			$failed_profiles_chunks = $event->failed_profiles->chunk(35);

			foreach ($failed_profiles_chunks as $failed_profiles_chunk) {

				foreach ($failed_profiles_chunk as $failed_profile) {
					$userss[] = $failed_profile->insta_username;
					//
				}
                $count = count($userss);
				$users = implode("\n", $userss);
				Notification::send($users, new InteractionsFailed($users, $count));
				unset($userss);
			}


			//        if (count($userss) < 30) {
			//            $users = implode("\n", $userss);
			//            Notification::send($users, new InteractionsFailed($users));
			//        }
			//        else{
			//            if ($i % 30 == 0){
			//                $userssliced = array_slice($userss,0,30);
			//                $users = implode("\n", $userss);
			//                Notification::send($users, new InteractionsFailed($users));
			//                $i += 1;
			//            }
			//        }
			//            foreach($event->failed_profiles as $failed_profile){
			//                $users = $failed_profile;
			//                Notification::send($users, new InteractionsFailed($users));
			//            }
		}

	}
}
