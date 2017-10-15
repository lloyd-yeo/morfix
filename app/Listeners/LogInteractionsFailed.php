<?php

namespace App\Listeners;

use App\Events\UserInteractionsFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\UserInteractionFailed;
use Carbon\Carbon;

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
        $from = Carbon::now()->subMinute(15)->toDateTimeString();
        $to = Carbon::now()->toDateTimeString();
        $notifyusers = UserInteractionFailed::whereBetween('timestamp', array($from, $to))
                                        ->take($event->count)
                                        ->get();
        if ($notifyusers !== null){
            foreach($notifyusers as $notifyuser){
                $notifyuser->notified = 1;
            }
        }

    }
}
