<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateWaveEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $wave; 

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($wave)
    {
        $this->wave = $wave;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('wallet-wave-created');
    }

    public function broadcastWith()
    {
        return [
            'id' =>  $this->wave->id,
            'provider'  =>  $this->wave->provider->name,
            'amount' =>  $this->wave->amount,
            'type' =>  'rechargement wave',
            'description'  =>   $this->wave->description,
            'amount_wallet' =>  $this->wave->wallet->amount . ' ' . $this->wave->wallet->currencie->code,
            'date_created' =>  $this->wave->created_at,
            'date_treatment' =>  $this->wave->transactionWave->created_at ?? null,
            'Reference'  => $this->wave->transactionWave->transaction_id ?? null,
            'xbet_cost_percent' =>   $this->wave->costs_amount ?? null,
            'number_compte' =>  $this->wave->phone ?? null,
            'country'  =>  $this->wave->wallet->country->code ?? null,
            'devise'  =>  $this->wave->wallet->currencie->code,
            'status'  =>  $this->wave->status,
            'amount_received' =>   $this->wave->amount_received ?? null,
            'userId'  =>   $this->wave->wallet->sidUser->id,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_wave';
    }
}
