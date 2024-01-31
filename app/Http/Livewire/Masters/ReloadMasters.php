<?php

namespace App\Http\Livewire\Masters;

use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletReload;
use Livewire\Component;

class ReloadMasters extends Component
{

    public $master_reloads;

    public function render()
    {

        $sid_wallet = Wallet::where('sid_user_id', $this->master_reloads)->first();

        return view('livewire.masters.reload-masters', [
            'realoads' => $this->getTransactionsTypeAll(WalletReload::class, 'account_wallet', $this->master_reloads, $sid_wallet),
        ]);
    }

    public function getTransactionsTypeAll($wallets, $number_compte, $wallet_solde, $wallet, $page = 20)
    {

        $wallet_all =   $wallets::where('wallet_id', $wallet_solde)
            ->orWhere($number_compte,  $wallet->wallet_id)
            ->orderBy('created_at', 'desc')->paginate($page);

        return $wallet_all;
    }
}
