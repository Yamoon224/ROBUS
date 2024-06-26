<?php

namespace App\Events\Visitors;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class CreateWalletTransfersVisEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transfers;

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
        return new Channel('wallet-transfers-vis-created');
    }

    public function broadcastWith()
    {
        return [
            'id' =>  $this->transfers->id,
            'provider'  =>  $this->transfers->provider->name,
            'amount' =>   $this->transfers->amount,
            'type' =>  'Transaction',
            'description'  =>   $this->transfers->description,
            'date_created' =>   $this->transfers->created_at,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_transfers_vis';
    }
}