<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Collection;


class UsersInteractionsFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $failed_profiles;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Collection $failed_profiles)
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
