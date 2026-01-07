<?php

// PaymentSuccessful.php
namespace App\Events;

use App\Models\Wallets\WalletReload;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessful
{
    use Dispatchable, SerializesModels;
    
    public $transaction;
    public $data;
    
    public function __construct(WalletReload $transaction, array $data)
    {
        $this->transaction = $transaction;
        $this->data = $data;
    }
}

// PaymentFailed.php
class PaymentFailed
{
    use Dispatchable, SerializesModels;
    
    public $transaction;
    public $data;
    
    public function __construct(WalletReload $transaction, array $data)
    {
        $this->transaction = $transaction;
        $this->data = $data;
    }
}

// PaymentPending.php
class PaymentPending
{
    use Dispatchable, SerializesModels;
    
    public $transaction;
    public $data;
    
    public function __construct(WalletReload $transaction, array $data)
    {
        $this->transaction = $transaction;
        $this->data = $data;
    }
}