<?php

namespace App\Tools;

use App\Models\Wallets\Wallet;

class WalletUtil
{
    public static function searchWallet($compte_number_robus)
    {
        return Wallet::where('wallet_id', $compte_number_robus)
            ->where('country_id', sabdy()->country->id)
            ->first();
    }
}
