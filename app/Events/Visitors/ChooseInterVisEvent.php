<?php

namespace App\Events\Visitors;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChooseInterVisEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $inter;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($inter)
    {
        $this->inter = $inter;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('wallet-inter-choose-vis-created');
    }

    public function broadcastWith()
    {
        return [
            'id' =>  $this->inter->id,
            'updated_by' =>  $this->inter->updated_by,

        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_inter_vis_choose';
    }
}
