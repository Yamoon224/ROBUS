<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\Http\Resources\TransactionSinglePuscherCollection;

class UpdateWaveEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $wave_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($wave_id)
    {
        $this->wave_id = $wave_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('updated_wave');
    }

    public function broadcastWith()
    {
        return [
            'id' =>  $this->wave_id->id,
            'provider'  =>  $this->wave_id->provider->name,
            'amount' =>  $this->wave_id->amount,
            'type' =>  'UV Wave',
            'description'  =>   $this->wave_id->description,
            'amount_wallet' =>  $this->wave_id->wallet->amount . ' ' . $this->wave_id->wallet->currencie->code,
            'date_created' =>   $this->wave_id->created_at,
        ];
    }

    public function broadcastAs()
    {
        return 'update_wallet_wave';
    }
}