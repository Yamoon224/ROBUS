<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class CreateWalletDrawalEvent  implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $push;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($push)
    {
        $this->push = $push;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('wallet-drawal-created');
    }

    public function broadcastWith()
    {
        return [

            'id' => $this->push->id,
            'provider'  => $this->push->provider->name,
            'amount' => $this->push->amount,
            'type' =>  'Retrait',
            'description'  =>   $this->push->description,
            'amount_wallet' => $this->push->wallet->amount . ' ' . $this->push->wallet->currencie->code,
            'date_created' => $this->push->created_at,
            'date_treatment' => $this->push->payment->created_at ?? null,
            'devise'  =>  $this->push->wallet->currencie->code,
            'status'  => $this->push->status,
            'userId'  =>   $this->push->wallet->sidUser->id,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_drawal';
    }
}