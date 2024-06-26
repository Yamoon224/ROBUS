<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ChooseWaveEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $wave;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($wave)
    {
        $this->wave = $wave;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('wallet-wave-choose-created');
    }

    public function broadcastWith()
    {

        return [

            'id' =>  $this->wave->id,
            'updated_by' =>  $this->wave->updated_by,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_wave_choose';
    }
}