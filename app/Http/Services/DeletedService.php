<?php

namespace App\Http\Services;


use App\Models\Wallets\Wallet;
use App\Repositories\TransactionRepository;

class DeletedService
{

    /** @var  TransactionRepository */
    public $transctionRepository;

    public function __construct(TransactionRepository $transactionReposi)
    {
        $this->transctionRepository = $transactionReposi;
    }


    public function deletedTransaction($wallet_reload)
    {

        $wallet_solde = Wallet::where('sid_user_id', robus()->id)->first();

        if ($wallet_solde->id != $wallet_reload->wallet->id) {
            return  false;
        }

        $wallet_reload->delete();

        return true;
    }


    public function deletedVirement($wallet_reload)
    {

        $wallet_solde = Wallet::where('sid_user_id', robus()->id)->first();

        if ($wallet_solde->id != $wallet_reload->wallet->id) {
            return  false;
        }

        $wallet_reload->delete();

        return true;
    }
  
}
