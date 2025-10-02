<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Http\Resources\PaymentSinglePuscherCollection;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class CreateWalletVersementEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $versement;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($versement)
    {
        $this->versement = $versement;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('wallet-versement-created');
    }

    public function broadcastWith()
    {
        return [

            'id' => $this->versement->id,
            'provider'  => $this->versement->provider->name,
            'amount' => $this->versement->amount,
            'type' =>  'Versement',
            'description'  =>   $this->versement->description,
            'amount_wallet' => $this->versement->wallet->amount . ' ' . $this->versement->wallet->currencie->code,
            'date_created' => $this->versement->created_at,
            'date_treatment' => $this->versement->payment->created_at ?? null,
            'devise'  =>  $this->versement->wallet->currencie->code,
            'status'  => $this->versement->status,
            'userId'  =>   $this->versement->wallet->sidUser->id,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_versement';
    }
}