<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateActiveCardEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $active;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($active)
    {
        $this->active = $active;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('active-card-user');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->active->id,
            'status' => $this->active->status
        ];
    }

    public function broadcastAs()
    {
        return 'Active_card_user';
    }
}