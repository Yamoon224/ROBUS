<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Http\Resources\PaymentSinglePuscherCollection;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ChoosePaymentEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $paiement;

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
        return new Channel('wallet-paiement-choose-created');
    }

    public function broadcastWith()
    {
        return [

            'id' => $this->paiement->id,
            'updated_by'  => $this->paiement->updated_by,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_paiement_choose';
    }
}