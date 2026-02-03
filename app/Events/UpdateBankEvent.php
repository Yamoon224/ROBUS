<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use App\Http\Resources\VirementSingleCollection;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class UpdateBankEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $bank_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($bank_id)
    {
        $this->bank_id = $bank_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('updated_bank');
    }

    public function broadcastWith()
    {

        return [

            'id' =>  $this->bank_id->id,
            'provider'  =>  $this->bank_id->provider->name,
            'amount' =>  $this->bank_id->amount ?? null,
            'type' =>  'Virement',
            'description'  => $this->bank_id->description,
            'amount_wallet' =>  $this->bank_id->amount_solde,
            'date_created' =>  $this->bank_id->created_at,
            'date_treatment' =>  $this->bank_id->paymentBank->created_at ?? null,
            'Reference'  => $this->bank_id->paymentBank->transaction_id ?? null,
            'bank_cost_percent' =>   $this->bank_id->costs_amount ?? null,
            'number_compte' =>  $this->bank_id->number_compte ?? null,
            'updated_by'  =>  $this->bank_id->updated_by,
            'client' => [
                'firstname' =>  $this->bank_id->firstname ?? null,
                'lastname' =>  $this->bank_id->lastname ?? null,
                'phone' =>  $this->bank_id->phone ?? null,
            ],
            'devise'  =>  $this->bank_id->wallet->currencie->code,
            'status'  =>  $this->bank_id->status ?? null,
            'userId'  =>   $this->bank_id->wallet->sidUser->id,
            
        ];
    }

    public function broadcastAs()
    {
        return 'update_wallet_bank';
    }
}