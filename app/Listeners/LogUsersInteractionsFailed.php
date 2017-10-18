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
            $partition = $event->failed_profiles->first()->partition;
            $failed_profiles_chunks = $event->failed_profiles->chunk(35);

            foreach ($failed_profiles_chunks as $failed_profiles_chunk) {

                foreach ($failed_profiles_chunk as $failed_profile) {
                    $users_array[] = $failed_profile->insta_username . $failed_profile->failure_msg;
                }
                $count = count($users_array);
                $users = implode("\n", $users_array);
                Notification::send($users, new InteractionsFailed($users, $count, $partition));
                unset($users_array);
            }

        }

    }
}
