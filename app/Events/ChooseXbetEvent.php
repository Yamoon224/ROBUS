<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ChooseXbetEvent implements ShouldBroadcastNow
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
        return new Channel('wallet-xbet-choose-created');
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
        return 'Created_wallet_xbet_choose';
    }
}