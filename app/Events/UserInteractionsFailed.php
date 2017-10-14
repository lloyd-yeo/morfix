<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\InstagramProfile;

class UserInteractionsFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $ig_profile;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(InstagramProfile $ig_profile)
    {
        $this->ig_profile = $ig_profile;
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
