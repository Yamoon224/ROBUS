<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Http\Resources\PaymentSinglePuscherCollection;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class CreateWalletDjamoEvent  implements ShouldBroadcastNow
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
        return new Channel('wallet-djamo-created');
    }

    public function broadcastWith()
    {
        return [

            'id' => $this->djamo->id,
            'provider'  => $this->djamo->provider->name,
            'amount' => $this->djamo->amount,
            'type' =>  'djamo',
            'description'  =>   $this->djamo->description,
            'amount_wallet' => $this->djamo->wallet->amount . ' ' . $this->djamo->wallet->currencie->code,
            'date_created' => $this->djamo->created_at,
            'date_treatment' => $this->djamo->payment->created_at ?? null,
            'devise'  =>  $this->djamo->wallet->currencie->code,
            'status'  => $this->djamo->status,
            'userId'  =>   $this->djamo->wallet->sidUser->id,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_djamo';
    }
}