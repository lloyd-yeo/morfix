<?php

namespace App\Listeners;

use App\Events\EngagementGroupFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
     * @param  EngagementGroupFailed  $event
     * @return void
     */
    public function handle(EngagementGroupFailed $event)
    {
        //
    }
}
