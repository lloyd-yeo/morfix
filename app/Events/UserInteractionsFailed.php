<?php

namespace App\Events;

use App\UserInteractionFailed;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
Use App\InstagramProfile;


class UserInteractionsFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $failed_profiles;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(UserInteractionFailed $failed_profiles)
    {
        $this->failed_profiles = $failed_profiles;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
