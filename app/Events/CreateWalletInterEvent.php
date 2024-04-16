<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class CreateWalletInterEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $inter;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($inter)
    {
        $this->inter = $inter;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('wallet-inter-created');
    }

    public function broadcastWith()
    {
        return [
            'id' =>  $this->inter->id,
            'provider'  =>  $this->inter->provider->name,
            'amount' =>  $this->inter->amount,
            'type' =>  'Inter',
            'description'  =>   $this->inter->description,
            'amount_wallet' =>  $this->inter->wallet->amount . ' ' . $this->inter->wallet->currencie->code,
            'date_created' =>  $this->inter->created_at,
            'date_treatment' =>  $this->inter->transactionInter->created_at ?? null,
            'Reference'  => $this->inter->transactionInter->transaction_id ?? null,
            'inter_cost_percent' =>   $this->inter->costs_amount ?? null,
            'number_compte' =>  $this->inter->number_compte ?? null,
            'phone' =>  $this->inter->phone ?? null,
            'country'  =>  $this->inter->country->code ?? null,
            'devise'  =>  $this->inter->wallet->currencie->code,
            'status'  =>  $this->inter->status,
            'amount_received' =>   $this->inter->amount_received ?? null,
            'userId'  =>   $this->inter->wallet->sidUser->id,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_inter';
    }
}