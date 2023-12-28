<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Http\Resources\ReloadCardSinglePuscherCollection;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class UpdateReloadCardEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $card_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($card_id)
    {
        $this->card_id = $card_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('updated_card');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->card_id->id,
            'provider'  =>  $this->card_id->provider->name,
            'amount' =>  $this->card_id->amount,
            'type' =>  'Reload_card',
            'description'  => $this->card_id->description,
            'amount_wallet' =>  $this->card_id->wallet->amount . ' ' . $this->card_id->wallet->currencie->code,
            'date_created' =>  $this->card_id->created_at,
            'date_treatment' =>  $this->card_id->deposit->created_at ?? null,
            'Reference'  => $this->card_id->deposit->transaction_id ?? null,
            'name' =>  $this->card_id->name ?? null,
            'deposit_cost_percent' =>   $this->card_id->costs_amount ?? null,
            'num_carte' => $this->card_id->num_carte ?? null,
            'devise'  =>  $this->card_id->wallet->currencie->code,
            'updated_by'  =>  $this->card_id->updated_by,
            'status'  =>  $this->card_id->status,
            'userId'  =>   $this->card_id->wallet->sidUser->id,

        ];
    }

    public function broadcastAs()
    {
        return 'update_wallet_card';
    }
}