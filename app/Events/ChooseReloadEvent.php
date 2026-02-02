<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Http\Resources\ReloadSinglePuscherCollection;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ChooseReloadEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $reload;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($reload)
    {
        $this->reload = $reload;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('wallet-reload-choose-created');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->reload->id,
            'updated_by' =>  $this->reload->updated_by,
            'userId'  =>   $this->reload->wallet->sidUser->id ?? 12,

        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_reload_choose';
    }
}