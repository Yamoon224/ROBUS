<?php

namespace App\Events\Visitors;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Database\Eloquent\Model;

class WebTransactionUpdated implements ShouldBroadcastNow
{
    public function __construct(private Model $transaction)
    {
    }

    public function broadcastOn(): Channel
    {
        return new Channel('wallet-web-transactions-updated');
    }

    public function broadcastAs(): string
    {
        return 'wallet_web_transaction_updated';
    }

    public function broadcastWith(): array
    {
        return ['id' => $this->transaction->id, 'status' => $this->transaction->status];
    }
}
