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

class UpdateTransferEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $transfers_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($transfers_id)
    {
        $this->transfers_id = $transfers_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('updated_transfers');
    }

    public function broadcastWith()
    {
        return [
            'id' =>  $this->transfers_id->id,
            'provider'  =>  $this->transfers_id->provider->name,
            'amount' =>  $this->transfers_id->amount,
            'type' =>  'Transaction',
            'description'  =>   $this->transfers_id->description,
            'amount_wallet' =>  $this->transfers_id->amount_solde,
            'date_created' =>   $this->transfers_id->created_at,
            'date_treatment' =>  $this->transfers_id->transaction->created_at ?? null,
            'Reference'  => $this->transfers_id->transaction->transaction_id  ?? null,
            'status'  =>   $this->transfers_id->status,
            'userId'  =>   $this->transfers_id->wallet->sidUser->id,
        ];
    }

    public function broadcastAs()
    {
        return 'update_wallet_transfers';
    }
}