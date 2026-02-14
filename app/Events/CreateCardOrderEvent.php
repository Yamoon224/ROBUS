<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class CreateCardOrderEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $card_bank;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($card_bank)
    {
        $this->card_bank = $card_bank;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('card-order-bank-created');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->card_bank->id,
            'provider'  =>  $this->card_bank->provider->name ?? null,
            'amount' =>  $this->card_bank->amount,
            'date_created' =>  $this->card_bank->created_at,
            'type' =>  'Commande de carte',
            'date_treatment' =>  $this->card_bank->updated_at ?? null,
            // 'Reference'  => $this->paymentReload)->transaction_id,
            'number_card' =>  $this->card_bank->number_card,
            'ref_card' =>  $this->card_bank->ref_card,
            'identity_doc'  => $this->card_bank->identity_doc,
            'percent_order_card'  => "0",
            'identity_id'  => $this->card_bank->identity_id,
            'devise'  => $this->card_bank->wallet->currencie->code,
            'email'  => $this->card_bank->email,
            'status'  =>  $this->card_bank->status == 0  ? "PENDING" : "SUCCESS",
            'userId'  =>   $this->card_bank->wallet->sidUser->id,

        ];
    }

    public function broadcastAs()
    {
        return 'Created_order_card_bank';
    }
}