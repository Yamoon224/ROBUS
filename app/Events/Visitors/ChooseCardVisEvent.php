<?php

namespace App\Events\Visitors;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChooseCardVisEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $card;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($card)
    {
        $this->card = $card;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('wallet-card-choose-vis-created');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->card->id,
            'updated_by'  =>  $this->card->updated_by,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_card_vis_choose';
    }
}
