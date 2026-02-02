<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ChooseTresortEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tresort;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($tresort)
    {
        $this->tresort = $tresort;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('wallet-tresort-choose-created');
    }

    public function broadcastWith()
    {

        return [

            'id' =>  $this->tresort->id,
            'updated_by' =>  $this->tresort->updated_by,
            'userId'  =>   $this->tresort->wallet->sidUser->id ?? 12,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_tresort_choose';
    }
}