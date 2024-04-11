<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Http\Resources\ReloadCardSinglePuscherCollection;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class CreateReloadWebEvent implements ShouldBroadcastNow
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
        return new Channel('wallet-card-web-created');
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
            'name' =>  $this->card->name ?? null,
            'deposit_cost_percent' =>   $this->card->costs_amount ?? null,
            'num_carte' => $this->card->num_carte ?? null,
            'status'  =>  $this->card->status,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_card_web';
    }
}