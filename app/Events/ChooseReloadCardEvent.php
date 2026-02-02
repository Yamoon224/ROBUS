<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use App\Http\Resources\ReloadCardSinglePuscherCollection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ChooseReloadCardEvent implements ShouldBroadcastNow
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
        return new Channel('wallet-card-choose-created');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->card->id,
            'updated_by'  =>  $this->card->updated_by,
            'userId'  =>   $this->card->wallet->sidUser->id ?? 12,

        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_card_choose';
    }
}