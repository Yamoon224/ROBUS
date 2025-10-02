<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ChooseDjamoEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $djamo;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($djamo)
    {
        $this->djamo = $djamo;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('wallet-djamo-choose-created');
    }

    public function broadcastWith()
    {

        return [

            'id' =>  $this->djamo->id,
            'updated_by' =>  $this->djamo->updated_by,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_djamo_choose';
    }
}