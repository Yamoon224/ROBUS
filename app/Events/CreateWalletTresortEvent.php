<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Http\Resources\PaymentSinglePuscherCollection;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class CreateWalletTresortEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tresort;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($tresort)
    {
        $this->tresort = $tresort;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('wallet-tresort-created');
    }

    public function broadcastWith()
    {
        return [

            'id' => $this->tresort->id,
            'provider'  => $this->tresort->provider->name,
            'amount' => $this->tresort->amount,
            'type' =>  'tresort',
            'description'  =>   $this->tresort->description,
            'amount_wallet' => $this->tresort->wallet->amount . ' ' . $this->tresort->wallet->currencie->code,
            'date_created' => $this->tresort->created_at,
            'date_treatment' => $this->tresort->payment->created_at ?? null,
            'devise'  =>  $this->tresort->wallet->currencie->code,
            'status'  => $this->tresort->status,
            'userId'  =>   $this->tresort->wallet->sidUser->id,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_tresort';
    }
}