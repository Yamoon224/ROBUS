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
            'amount_wallet' =>  $this->transfers_id->wallet->amount . ' ' . $this->transfers_id->wallet->currencie->code,
            'date_created' =>   $this->transfers_id->created_at,
            'date_treatment' =>  $this->transfers_id->transaction->created_at ?? null,
            'Reference'  => $this->transfers_id->transaction->transaction_id  ?? null,
            // 'userId'  =>   $this->transfers_id->wallet->sidUser->id,

            // 'name' =>   $this->transfers_id->name  ?? null,
            // 'payment_cost_percent' =>   $this->transfers_id->transactionCost->percent  ?? null,
            // 'number_decoder' =>   $this->transfers_id->number_decoder  ?? null,
            // 'id_invoice' =>   $this->transfers_id->id_invoice  ?? null,
            // 'number_decoder' =>   $this->transfers_id->number_decoder  ?? null,
            // 'offer' => $this->transfers_id->offer->name  ?? null,
            // 'number_contract' =>   $this->transfers_id->number_contract  ?? null,
            // 'number_phone' =>   $this->transfers_id->number_phone  ?? null,
            // 'paiementChoix_phone' =>   $this->transfers_id->paiementChoix_phone  ?? null,
            // 'factureChoix_type' =>   $this->transfers_id->factureChoix_type  ?? null,
            // 'devise'  =>  $this->transfers_id->wallet->currencie->code,
            // 'updated_by'  =>  $this->transfers_id->updated_by,
            // 'status'  =>   $this->transfers_id->status,
        ];
    }

    public function broadcastAs()
    {
        return 'update_wallet_transfers';
    }
}