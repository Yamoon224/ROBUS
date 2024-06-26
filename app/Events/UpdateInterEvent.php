<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Http\Resources\IntersSinglePuscherCollection;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class UpdateInterEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $inter_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($inter_id)
    {
        $this->inter_id = $inter_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('updated_inter');
    }

    public function broadcastWith()
    {
        return [
            'id' =>  $this->inter_id->id,
            'provider'  =>  $this->inter_id->provider->name,
            'amount' =>  $this->inter_id->amount ?? null,
            'type' =>  'Inter',
            'description'  =>   $this->inter_id->description,
            'amount_wallet' =>  $this->inter_id->wallet->amount . ' ' . $this->inter_id->wallet->currencie->code,
            'date_created' =>  $this->inter_id->created_at,
            'date_treatment' =>  $this->inter_id->transactionInter->created_at ?? null,
            'Reference'  => $this->inter_id->transactionInter->transaction_id ?? null,
            'inter_cost_percent' =>   $this->inter_id->costs_amount ?? null,
            'number_compte' =>  $this->inter_id->number_compte ?? null,
            'phone' =>  $this->inter_id->phone ?? null,
            'country'  =>  $this->inter_id->country->code ?? null,
            'devise'  =>  $this->inter_id->wallet->currencie->code,
            'updated_by'  =>  $this->inter_id->updated_by,
            'status'  =>  $this->inter_id->status,
            'userId'  =>   $this->inter_id->wallet->sidUser->id,

        ];
    }

    public function broadcastAs()
    {
        return 'update_wallet_inter';
    }
}