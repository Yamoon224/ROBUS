<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\Http\Resources\PhoneCreditSinglePuscherCollection;

class ChoosePhoneCreditEvent implements ShouldBroadcastNow
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
        return new Channel('wallet-phone-choose-created');
    }

    public function broadcastWith()
    {
        return [
            'id' =>  $this->phone->id,
            'updated_by' => $this->phone->updated_by, 
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_phone_choose';
    }
}