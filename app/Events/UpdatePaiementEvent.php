<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Http\Resources\PaymentSinglePuscherCollection;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class UpdatePaiementEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $paiement_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($paiement_id)
    {
        $this->paiement_id = $paiement_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('updated_paiement');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->paiement_id->id,
            'provider'  => $this->paiement_id->provider->name,
            'amount' =>  $this->paiement_id->amount ?? null,
            'type' =>  'Payment',
            'description'  =>   $this->paiement_id->description,
            'amount_wallet' => $this->paiement_id->wallet->amount . ' ' . $this->paiement_id->wallet->currencie->code,
            'date_created' => $this->paiement_id->created_at,
            'date_treatment' => $this->paiement_id->payment->created_at ?? null,
            'Reference'  => $this->paiement_id->payment->transaction_id ?? null,
            'name' => $this->paiement_id->name ?? null,
            'payment_cost_percent' => $this->paiement_id->costs_amount ?? null,
            'number_decoder' => $this->paiement_id->number_decoder ?? null,
            'id_invoice' => $this->paiement_id->id_invoice ?? null,
            'offer' => $this->paiement_id->offer->name ?? null,
            'number_contract' => $this->paiement_id->number_contract ?? null,
            'number_phone' => $this->paiement_id->number_phone ?? null,
            'paiementChoix_phone' => $this->paiement_id->paiementChoix_phone ?? null,
            'factureChoix_type' => $this->paiement_id->factureChoix_type ?? null,
            'devise'  =>  $this->paiement_id->wallet->currencie->code,
            'updated_by'  =>  $this->paiement_id->updated_by,
            'status'  => $this->paiement_id->status,
            'userId'  =>   $this->paiement_id->wallet->sidUser->id,

        ];
    }

    public function broadcastAs()
    {
        return 'update_wallet_paiement';
    }
}