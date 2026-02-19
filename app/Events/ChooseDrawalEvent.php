<?php

namespace App\Events;

use App\Models\EventLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChooseDrawalEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $drawal;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($drawal)
    {
        $this->drawal = $drawal;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('wallet-drawal-choose-created');
    }

    public function broadcastWith()
    {
        return [

            'id' => $this->drawal->id,
            // 'type' => 'Drawal',
            'updated_by'  => $this->drawal->updated_by,
            'userId'  =>   $this->drawal->wallet->sidUser->id ?? 12,

        ];
    }


    public function broadcastAs()
    {
        return 'Created_wallet_drawal_choose';
    }

   
}