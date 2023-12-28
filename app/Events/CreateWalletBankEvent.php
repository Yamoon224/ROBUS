<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Http\Resources\VirementSinglePuscherCollection;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class CreateWalletBankEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $bank;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($bank)
    {
        $this->bank = $bank;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('wallet-bank-created');
    }

    public function broadcastWith()
    {

        return [

            'id' =>  $this->bank->id,
            'provider'  =>  $this->bank->provider->name,
            'amount' =>  $this->bank->amount,
            'type' =>  'Virement',
            'description'  => $this->bank->description,
            'amount_wallet' =>  $this->bank->wallet->amount . ' ' . $this->bank->wallet->currencie->code,
            'date_created' =>  $this->bank->created_at,
            'date_treatment' =>  $this->bank->paymentBank->created_at ?? null,
            'Reference'  => $this->bank->paymentBank->transaction_id ?? null,
            'bank_cost_percent' =>   $this->bank->costs_amount ?? null,
            'number_compte' =>  $this->bank->number_compte ?? null,
            'client' => [
                'firstname' =>  $this->bank->firstname ?? null,
                'lastname' =>  $this->bank->lastname ?? null,
                'phone' =>  $this->bank->phone ?? null,
            ],
            'devise'  =>  $this->bank->wallet->currencie->code,
            'status'  =>  $this->bank->status ?? null,
            'userId'  =>   $this->bank->wallet->sidUser->id,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_bank';
    }
}