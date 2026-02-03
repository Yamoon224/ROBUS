<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\Http\Resources\TransactionSinglePuscherCollection;

class UpdateBankSendEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $xbet_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($xbet_id)
    {
        $this->xbet_id = $xbet_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('updated_bank_send');
    }

    public function broadcastWith()
    {
        return [
            'id' =>  $this->xbet_id->id,
            'provider'  =>  $this->xbet_id->provider->name,
            'amount' =>  $this->xbet_id->amount,
            'type' =>  'Versement',
            'description'  =>   $this->xbet_id->description,
            'amount_wallet' =>  $this->xbet_id->amount_solde,
            'date_created' =>   $this->xbet_id->created_at,
            'userId'  =>   $this->xbet_id->wallet->sidUser->id,
            
        ];
    }

    public function broadcastAs()
    {
        return 'update_wallet_bank_send';
    }
}