<?php

namespace App\Http\Services;

use App\Models\Custumers\Zone;

class TransactionsAccountService
{


    public function getTransactionCount($wallets, $id, $status)
    {

        $wallet_counts = $wallets::where('wallet_id', $id)->where('status', $status)->count();

        return $wallet_counts;
    }

    public function getTransactionsAll($wallets, $wallet_solde , $page = 20)
    {

        $wallet_all =   $wallets::where('wallet_id', $wallet_solde->id)->orderBy('created_at', 'desc')->paginate($page);

        return $wallet_all;
    }


    public function getTransactionsTypeAll($wallets, $number_compte, $wallet_solde , $page = 20)
    {

        $wallet_all =   $wallets::where('wallet_id', $wallet_solde->id)
            ->orWhere($number_compte,  $wallet_solde->wallet_id)
            ->orderBy('created_at', 'desc')->paginate($page);

        return $wallet_all;
    }

    public function getTransactionAmount($wallets, $id)
    {

        $data = [];

        foreach ($wallets::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $transaction) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }



    public function walletSumCommission($wallets, $id)
    {

        $data = [];

        foreach ($wallets::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $transaction) {
            $data[] = $transaction->commission_masters;
        }

        return array_sum($data);
    }


    public function getWalletTransactions($wallets, $id)
    {
        return $wallets::where('wallet_id', $id)->orderBy('created_at', 'desc')->paginate(20);
    }

    public function getAllUsers()
    {
        // Logique pour récupérer tous les utilisateurs
        return Zone::all();
    }
}
