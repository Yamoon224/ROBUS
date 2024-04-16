<?php

namespace App\Events\Visitors;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateWalletOneXbetVisEvent implements ShouldBroadcastNow
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
        return new Channel('wallet-xbet-created-vis');
    }

    public function broadcastWith()
    {
        return [
            'id' =>  $this->xbet->id,
            'provider'  =>  $this->xbet->provider->name,
            'amount' =>  $this->xbet->amount,
            'type' =>  'xbet',
            'description'  =>   $this->xbet->description,
            'date_created' =>  $this->xbet->created_at
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_xbet_vis';
    }
}
