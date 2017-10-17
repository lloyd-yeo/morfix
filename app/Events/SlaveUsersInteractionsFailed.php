<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SlaveUsersInteractionsFailed
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
