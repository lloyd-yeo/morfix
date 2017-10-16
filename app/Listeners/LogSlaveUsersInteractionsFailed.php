<?php

namespace App\Listeners;

use App\Events\SlaveUsersInteractionsFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Notification;
use App\Notifications\SlaveInteractionsFailed;

class LogSlaveUsersInteractionsFailed
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
     * @param  SlaveUsersInteractionsFailed $event
     * @return void
     */
    public function handle(SlaveUsersInteractionsFailed $event)
    {
        if (!empty($event->failed_profiles)) {


            $failed_profiles_chunks = $event->failed_profiles->chunk(35);

            foreach ($failed_profiles_chunks as $failed_profiles_chunk) {

                foreach ($failed_profiles_chunk as $failed_profile) {
                    $userss[] = $failed_profile->insta_username;
//
                }

                $users = implode("\n", $userss);
                Notification::send($users, new SlaveInteractionsFailed($users));
                unset($userss);
            }

        }
    }
}
