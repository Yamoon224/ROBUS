<?php

namespace App\Events\Visitors;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class CreateWalletPhoneCreditVisEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $phone;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('wallet-phone-vis-created');
    }

    public function broadcastWith()
    {
        return [
            'id' =>  $this->phone->id,
            'provider'  => $this->phone->provider->name,
            'amount' => $this->phone->amount,
            'type' =>  'Phone_credit',
            'description'  => $this->phone->description,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_phone_vis';
    }
}