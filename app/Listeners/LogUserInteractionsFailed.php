<?php

namespace App\Listeners;

use App\Events\UserInteractionsFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\InstagramProfile;
use App\UserInteractionFailed;
use App\User;

class LogUserInteractionsFailed
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
                $profile = new UserInteractionFailed;
                $profile->email = $failed_profile->email;
                $profile->insta_username = $failed_profile->insta_username;
                $check_tier = User::where('email', $failed_profile->email)->orderBy('user_id','desc')->first();
                $profile->tier = $check_tier->tier;
                $profile->timestamp = Carbon::now()->toDateTimeString();
                $profile->save();

            }
        }
    }
}
