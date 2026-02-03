<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Http\Resources\ReloadSinglePuscherCollection;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class UpdateReloadEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $reload_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($reload_id)
    {
        $this->reload_id = $reload_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('updated_reload');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->reload_id->id,
            'provider'  =>  $this->reload_id->provider->name,
            'amount' =>  $this->reload_id->amount,
            'type' =>  'Reload_account',
            'description'  =>  $this->reload_id->description,
            'amount_wallet' =>  $this->reload_id->amount_solde,
            'date_created' =>  $this->reload_id->created_at ?? null,
            'date_treatment' =>  $this->reload_id->paymentReload->created_at ?? null,
            'Reference'  => $this->reload_id->paymentReload->transaction_id ?? null,
            'account_wallet' =>  $this->reload_id->account_wallet ?? null,
            'phone' =>  $this->reload_id->phone ?? null,
            'devise'  =>  $this->reload_id->wallet->currencie->code,
            'updated_by'  =>  $this->reload_id->updated_by,
            'status'  =>  $this->reload_id->status,
            'userId'  =>   $this->reload_id->wallet->sidUser->id,

        ];
    }

    public function broadcastAs()
    {
        return 'update_wallet_reload';
    }
}