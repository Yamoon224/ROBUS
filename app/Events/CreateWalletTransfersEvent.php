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

class CreateWalletTransfersEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transfers;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($transfers)
    {
        $this->transfers = $transfers;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('wallet-transfers-created');
    }

    public function broadcastWith()
    {
        return [
            'id' =>  $this->transfers->id,
            'provider'  =>  $this->transfers->provider->name,
            'amount' =>   $this->transfers->amount,
            'type' =>  'Transaction',
            'description'  =>   $this->transfers->description,
            'amount_wallet' =>  $this->transfers->wallet->amount . ' ' . $this->transfers->wallet->currencie->code,
            'date_created' =>   $this->transfers->created_at,
            'date_treatment' =>  $this->transfers->transaction->created_at ?? null,
            'Reference'  => $this->transfers->transaction->transaction_id  ?? null,
            'name' =>   $this->transfers->name  ?? null,
            'num_wallet' =>   $this->transfers->num_wallet  ?? null,
            'payment_cost_percent' =>   $this->transfers->costs_amount ?? null,
            'id_invoice' =>   $this->transfers->id_invoice  ?? null,
            'number_decoder' =>   $this->transfers->number_decoder  ?? null,
            'offer' => $this->transfers->offer->name  ?? null,
            'number_contract' =>   $this->transfers->number_contract  ?? null,
            'number_phone' =>   $this->transfers->number_phone  ?? null,
            'paiementChoix_phone' =>   $this->transfers->paiementChoix_phone  ?? null,
            'factureChoix_type' =>   $this->transfers->factureChoix_type  ?? null,
            'amount_received' =>   $this->transfers->amount_received ?? null,
            'devise'  =>  $this->transfers->wallet->currencie->code,
            'status'  =>   $this->transfers->status,
            'userId'  =>   $this->transfers->wallet->sidUser->id,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_transfers';
    }
}