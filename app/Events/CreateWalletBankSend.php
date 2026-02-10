<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateWalletBankSend implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $xbet; 

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($xbet)
    {
        $this->xbet = $xbet;
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
            'id' =>  $this->xbet->id,
            'provider'  =>  $this->xbet->provider->name,
            'amount' =>  $this->xbet->amount,
            'type' =>  'xbet',
            'description'  =>   $this->xbet->description,
            'amount_wallet' =>  $this->xbet->wallet->amount . ' ' . $this->xbet->wallet->currencie->code,
            'date_created' =>  $this->xbet->created_at,
            'date_treatment' =>  $this->xbet->invoiceBankSend->created_at ?? null,
            'Reference'  => $this->xbet->invoiceBankSend->transaction_id ?? null,
            'country'  =>  $this->xbet->wallet->country->code ?? null,
            'devise'  =>  $this->xbet->wallet->currencie->code,
            'status'  =>  $this->xbet->status,
            'amount_received' =>   $this->xbet->amount_received ?? null,
            'userId'  =>   $this->xbet->wallet->sidUser->id,
        ];
    }

    public function broadcastAs()
    {
        return 'update_wallet_bank';
    }
}
