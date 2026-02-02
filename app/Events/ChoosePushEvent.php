<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ChoosePushEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $push;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($push)
    {
        $this->push = $push;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('wallet-push-choose-created');
    }

    public function broadcastWith()
    {

        return [

            'id' =>  $this->push->id,
            'updated_by' =>  $this->push->updated_by,
            'userId'  =>   $this->push->wallet->sidUser->id ?? 12,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_push_choose';
    }
}
