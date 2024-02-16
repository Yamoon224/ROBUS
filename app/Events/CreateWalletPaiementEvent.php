<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Http\Resources\PaymentSinglePuscherCollection;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class CreateWalletPaiementEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $paiement;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($paiement)
    {
        $this->paiement = $paiement;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('wallet-paiement-created');
    }

    public function broadcastWith()
    {
        return [

            'id' => $this->paiement->id,
            'provider'  => $this->paiement->provider->name,
            'amount' => $this->paiement->amount,
            'type' =>  'Payment',
            'description'  =>   $this->paiement->description,
            'amount_wallet' => $this->paiement->wallet->amount . ' ' . $this->paiement->wallet->currencie->code,
            'date_created' => $this->paiement->created_at,
            'date_treatment' => $this->paiement->payment->created_at ?? null,
            'Reference'  => $this->paiement->payment->transaction_id ?? null,
            'name' => $this->paiement->name ?? null,
            'payment_cost_percent' => $this->paiement->costs_amount ?? null,
            'number_decoder' => $this->paiement->number_decoder ?? null,
            'id_invoice' => $this->paiement->id_invoice ?? null,
            'offer' => $this->paiement->offer->name ?? null,
            'number_contract' => $this->paiement->number_contract ?? null,
            'number_phone' => $this->paiement->number_phone ?? null,
            'paiementChoix_phone' => $this->paiement->paiementChoix_phone ?? null,
            'factureChoix_type' => $this->paiement->factureChoix_type ?? null,
            'devise'  =>  $this->paiement->wallet->currencie->code,
            'status'  => $this->paiement->status,
            'userId'  =>   $this->paiement->wallet->sidUser->id,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_paiement';
    }
}