<?php

namespace App\Events;

use App\Models\EventLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChoosePaymentEvent implements ShouldBroadcastNow
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