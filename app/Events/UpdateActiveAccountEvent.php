<?php

namespace App\Events;

use App\Models\Sid\SidUser;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UpdateActiveAccountEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private $item;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(SidUser $item)
    {
        $this->item = $item;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        return new Channel('active-acccount-user');
    }

    public function broadcastAs()
    {
        return 'active_account_user';
    }
}