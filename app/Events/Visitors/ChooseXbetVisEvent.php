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

class ChooseXbetVisEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $xbet;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($xbet)
    {
        $this->xbet = $xbet;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('wallet-xbet-choose-vis-created');
    }

    public function broadcastWith()
    {

        return [

            'id' =>  $this->xbet->id,
            'updated_by' =>  $this->xbet->updated_by,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_xbet_vis_choose';
    }
}
