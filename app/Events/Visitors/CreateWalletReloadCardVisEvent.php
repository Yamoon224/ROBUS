<?php

namespace App\Events\Visitors;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Http\Resources\ReloadCardSinglePuscherCollection;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class CreateWalletReloadCardVisEvent implements ShouldBroadcastNow
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
        return new Channel('wallet-card-vis-created');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->card->id,
            'provider'  =>  $this->card->provider->name,
            'amount' =>  $this->card->amount,
            'type' =>  'Reload_card',
            'description'  => $this->card->description,
            'date_created' =>  $this->card->created_at,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_card_vis';
    }
}