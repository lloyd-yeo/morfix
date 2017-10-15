<?php

namespace App\Listeners;

use App\Events\UserInteractionsFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\UserInteractionFailed;

class LogInteractionsFailed
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
     * @param  UserInteractionsFailed  $event
     * @return void
     */
    public function handle(UserInteractionsFailed $event)
    {

        if (!empty($event->failed_profiles))
        {
            foreach($event->failed_profiles as $failed_profile){
                $from = Carbon::now()->subMinute(15)->toDateTimeString();
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
