<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class UpdateAllWalletEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $updateAll;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($updateAll)
    {
        $this->updateAll = $updateAll;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('update_all_wallet');
    }

    public function broadcastWith()
    {

        return [
            'id' =>  $this->updateAll->id,
            'updated_by'  =>  $this->updateAll->updated_by,

        ];
    }

    public function broadcastAs()
    {
        return 'wallet_update_all';
    }
}