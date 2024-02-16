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

class CreateWalletPhoneCreditEvent implements ShouldBroadcastNow
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
        return new Channel('wallet-phone-created');
    }

    public function broadcastWith()
    {
        return [
            'id' =>  $this->phone->id,
            'provider'  => $this->phone->provider->name,
            'amount' => $this->phone->amount,
            'type' =>  'Phone_credit',
            'description'  => $this->phone->description,
            'amount_wallet' => $this->phone->wallet->amount . ' ' . $this->phone->wallet->currencie->code,
            'Reference'  => $this->phone->paymentPhone->transaction_id ?? null,
            'date_treatment' =>  $this->phone->paymentPhone->created_at ?? null,
            'phone' => $this->phone->phone  ?? null,
            'phone_cost_percent' =>  $this->phone->costs_amount ?? null,
            'devise'  =>  $this->phone->wallet->currencie->code,
            'status'  => $this->phone->status,
            'userId'  =>   $this->phone->wallet->sidUser->id,

        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_phone';
    }
}