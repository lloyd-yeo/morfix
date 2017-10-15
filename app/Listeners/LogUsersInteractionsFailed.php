<?php

namespace App\Listeners;

use App\Events\UsersInteractionsFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\UserInteractionFailed;

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
     * @param  UsersInteractionsFailed  $event
     * @return void
     */
    public function handle(UsersInteractionsFailed $event)
    {
        if (!empty($event->failed_profiles))
        {
            foreach($event->failed_profiles as $failed_profile){
                $update = UserInteractionFailed::where('insta_username' , $failed_profile->insta_username)
                    ->first();
                if($update !== NULL){
                    $update->notified = 1;
                    $update->save();
                }
            }
        }

    }
}
