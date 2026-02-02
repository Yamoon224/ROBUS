<?php

namespace App\Events;

use App\Models\EventLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChooseVersementEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $versement;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($versement)
    {
        $this->versement = $versement;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('wallet-versement-choose-created');
    }

    public function broadcastWith()
    {
        return [

            'id' => $this->versement->id,
            'updated_by'  => $this->versement->updated_by,
            'userId'  =>   $this->versement->wallet->sidUser->id ?? 12,
        ];
    }


    public function broadcastAs()
    {
        return 'Created_wallet_versement_choose';
    }

   
}