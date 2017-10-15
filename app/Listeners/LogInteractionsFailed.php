<?php

namespace App\Listeners;

use App\Events\UserInteractionsFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        echo $event->count . ' profiles are not working';
    }
}
