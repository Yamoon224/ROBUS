<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Http\Resources\IntersSinglePuscherCollection;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ChooseInterEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $inter;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($inter)
    {
        $this->inter = $inter;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('wallet-inter-choose-created');
    }

    public function broadcastWith()
    {
        return [
            'id' =>  $this->inter->id,
            'updated_by' =>  $this->inter->updated_by,

        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_inter_choose';
    }
}