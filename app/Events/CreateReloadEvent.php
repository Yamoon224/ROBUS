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

class CreateReloadEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reload;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($reload)
    {
        $this->reload = $reload;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('wallet-reload-created');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->reload->id,
            'provider'  =>  $this->reload->provider->name,
            'amount' =>  $this->reload->amount,
            'type' =>  'Reload_account',
            'description'  =>  $this->reload->description,
            'amount_wallet' =>  $this->reload->wallet->amount . ' ' . $this->reload->wallet->currencie->code,
            'date_created' =>  $this->reload->created_at ?? null,
            'date_treatment' =>  $this->reload->paymentReload->created_at ?? null,
            'Reference'  => $this->reload->paymentReload->transaction_id ?? null,
            'account_wallet' =>  $this->reload->account_wallet ?? null,
            'phone' =>  $this->reload->phone ?? null,
            'devise'  =>  $this->reload->wallet->currencie->code,
            'status'  =>  $this->reload->status,
            'userId'  =>   $this->reload->wallet->sidUser->id,
        ];
    }

    public function broadcastAs()
    {
        return 'Created_wallet_reload';
    }
}