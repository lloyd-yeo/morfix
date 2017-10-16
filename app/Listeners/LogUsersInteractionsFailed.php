<?php

namespace App\Listeners;

use App\Events\UsersInteractionsFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Notification;
use App\Notifications\InteractionsFailed;
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
            $num_profiles = $event->failed_profiles->count();
            $from = Carbon::now()->subMinute(15)->toDateTimeString();
            $new_failed_profiles = UserInteractionFailed::where('timestamp', '>', $from)
                ->orderby('id','desc')
                ->take($num_profiles)
                ->get();

    
            $failed_profiles_chunks = $new_failed_profiles->chunk(2);

            foreach ($failed_profiles_chunks as $failed_profiles_chunk) {

                foreach ($failed_profiles_chunk as $failed_profile) {
                    $userss[] = $failed_profile->insta_username;
//
                }

                $users = implode("\n", $userss);
                Notification::send($users, new InteractionsFailed($users));
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
