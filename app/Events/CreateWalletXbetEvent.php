<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class CreateWalletXbetEvent implements ShouldBroadcastNow
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
        return new Channel('wallet-xbet-created');
    }

    public function broadcastWith()
    {

        return [

            'id' =>  $this->xbet->id,
            'provider'  =>  $this->xbet->provider->name,
            'amount' =>  $this->xbet->amount,
            'description'  => $this->xbet->description,
            'amount_wallet' =>  $this->xbet->wallet->amount . ' ' . $this->xbet->wallet->currencie->code,
            'date_created' =>  $this->xbet->created_at,
            'status'  =>  $this->xbet->status ?? null,
            'userId'  =>   $this->xbet->wallet->sidUser->id,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_xbet';
    }
}