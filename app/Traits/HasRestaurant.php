<?php

namespace App\Traits;

use App\Models\Restaurant;
use App\Models\Wallets\Wallet;
use App\Scopes\WalletScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasWallet
{

    protected static function booted()
    {
        static::addGlobalScope(new WalletScope());
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

}
