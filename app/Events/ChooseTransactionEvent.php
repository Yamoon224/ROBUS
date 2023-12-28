<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ChooseTransactionEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $transfers;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($transfers)
    {
        $this->transfers = $transfers;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('wallet-transfers-choose-created');
    }

    public function broadcastWith()
    {
        return [
            'id' =>  $this->transfers->id,
            'updated_by'  =>  $this->transfers->updated_by,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_transfers_choose';
    }
}