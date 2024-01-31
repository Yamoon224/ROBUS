<?php

namespace App\Http\Livewire\Masters;

use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletInter;
use Livewire\Component;

class InterMasters extends Component
{

    public $master_inters;

    public function render()
    {

        $sid_wallet = Wallet::where('sid_user_id', $this->master_inters)->first();

        return view('livewire.masters.inter-masters' , [
            'internationals' =>  $this->getTransactionsTypeAll('number_compte',  $sid_wallet),
        ]);
    }

    
    public function getTransactionsTypeAll($number_compte, $wallet_solde, $page = 20)
    {

        $wallet_all =   WalletInter::where('wallet_id', $wallet_solde->id)
            ->orWhere($number_compte,  $wallet_solde->wallet_id)
            ->orderBy('created_at', 'desc')->paginate($page);

        return $wallet_all;
    }

}
