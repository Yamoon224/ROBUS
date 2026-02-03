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

class UpdateOrderCardEvent implements ShouldBroadcastNow
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
        return new Channel('updated_order_card');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->paiement_id->id,
            'provider'  =>  $this->paiement_id->provider->name ?? null,
            'amount' =>  number_format((double)$this->paiement_id->amount, 2, '.', ''),
            'date_created' =>  $this->paiement_id->created_at,
            'type' =>  'Commande de carte',
            'date_treatment' =>  $this->paiement_id->updated_at ?? null,
            'amount_wallet' =>  $this->paiement_id->amount_solde,
            'number_card' =>  $this->paiement_id->number_card,
            'ref_card' =>  $this->paiement_id->ref_card,
            'identity_doc'  => $this->paiement_id->identity_doc,
            'percent_order_card'  => "0",
            'identity_id'  => $this->paiement_id->identity_id,
            'identity_id'  => $this->paiement_id->identity_id,
            'devise'  => $this->paiement_id->wallet->currencie->code,
            'email'  => $this->paiement_id->email,
            'status'  =>  $this->paiement_id->status == 0  ? "PENDING" : "SUCCESS",
            'userId'  =>   $this->paiement_id->wallet->sidUser->id,

        ];
    }

    public function broadcastAs()
    {
        return 'update_order_card';
    }
}