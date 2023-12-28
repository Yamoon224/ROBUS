<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\Http\Resources\PhoneCreditSinglePuscherCollection;

class UpdatePhoneCreditEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $phone_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($phone_id)
    {
        $this->phone_id = $phone_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('updated_phone');
    }

    public function broadcastWith()
    {
        return [
            'id' =>  $this->phone_id->id,
            'provider'  => $this->phone_id->provider->name,
            'amount' =>  $this->phone_id->amount ?? null,
            'type' =>  'Phone_credit',
            'description'  => $this->phone_id->description,
            'amount_wallet' => $this->phone_id->wallet->amount . ' ' . $this->phone_id->wallet->currencie->code,
            'Reference'  => $this->phone_id->paymentPhone->transaction_id ?? null,
            'date_treatment' =>  $this->phone_id->paymentPhone->created_at ?? null,
            'phone' => $this->phone_id->phone  ?? null,
            'phone_cost_percent' =>  $this->phone_id->costs_amount  ?? null,
            'devise'  =>  $this->phone_id->wallet->currencie->code,
            'updated_by'  =>  $this->phone_id->updated_by,
            'status'  => $this->phone_id->status,
            'userId'  =>   $this->phone_id->wallet->sidUser->id,

        ];
    }

    public function broadcastAs()
    {
        return 'update_wallet_phone';
    }
}