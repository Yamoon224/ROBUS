<?php

namespace App\Http\Services;

use App\Models\Custumers\Zone;
use App\Models\Sid\SidUser;
use Illuminate\Support\Carbon;

class TransactionsAccountService
{



    public function getTransactionMasterCount($walletModel,  $status)
    {


        $sidUsers = SidUser::where('master_id',  robus()->id)->get();
        $totalcount = 0;

        foreach ($sidUsers as $sidUser) {

            $sidUserId = $sidUser->wallet->id;

            $count = $walletModel::where('wallet_id', $sidUserId)
                ->where('status', $status)->count();

            if ($count !== null) {
                $totalcount += $count;
            }
        }

        return $totalcount;
    }

    

    public function getTransactionMasterMonthCount($walletModel)
    {


        $sidUsers = SidUser::where('master_id',  robus()->id)->get();
        $totalcount = 0;

        foreach ($sidUsers as $sidUser) {

            $sidUserId = $sidUser->wallet->id;

            $count = $walletModel::where('wallet_id', $sidUserId)
                ->whereMonth('created_at', now()->month)->count();

            if ($count !== null) {
                $totalcount += $count;
            }
        }

        return $totalcount;
    }

    public function getTransactionMasterYearCount($walletModel)
    {


        $sidUsers = SidUser::where('master_id',  robus()->id)->get();
        $totalcount = 0;

        foreach ($sidUsers as $sidUser) {

            $sidUserId = $sidUser->wallet->id;

            $count = $walletModel::where('wallet_id', $sidUserId)
                ->whereMonth('created_at', now()->month)->count();

            if ($count !== null) {
                $totalcount += $count;
            }
        }

        return $totalcount;
    }

    public function getTodayTransactionGainTotal($walletModel)
    {

        $sidUsers = SidUser::where('master_id',  robus()->id)->get();

        $totalAmount = 0;

        foreach ($sidUsers as $sidUser) {

            $sidUserId = $sidUser->wallet->id;

            $amount = $walletModel::where('wallet_id', $sidUserId)
                ->whereStatus('SUCCESS')
                ->whereDate('created_at', Carbon::today())
                ->sum('commission_masters');

            if ($amount !== null) {
                $totalAmount += $amount;
            }
        }


        return $totalAmount;
    }

    public function getMonthTransactionGain($walletModel)
    {

        $sidUsers = SidUser::where('master_id',  robus()->id)->get();

        $totalAmount = 0;

        foreach ($sidUsers as $sidUser) {

            $sidUserId = $sidUser->wallet->id;

            $amount = $walletModel::where('wallet_id', $sidUserId)
                ->whereStatus('SUCCESS')
                ->whereMonth('created_at', now()->month)
                ->sum('commission_masters');

            if ($amount !== null) {
                $totalAmount += $amount;
            }
        }


        return $totalAmount;
    }



    public function getTransactionCount($wallets, $id, $status)
    {

        $wallet_counts = $wallets::where('wallet_id', $id)->where('status', $status)->count();

        return $wallet_counts;
    }

    public function getTransactionsAll($wallets, $wallet_solde, $page = 20)
    {

        $wallet_all =   $wallets::where('wallet_id', $wallet_solde->id)->orderBy('created_at', 'desc')->paginate($page);

        return $wallet_all;
    }


    public function getTransactionsTypeAll($wallets, $number_compte, $wallet_solde, $page = 20)
    {

        $wallet_all =   $wallets::where('wallet_id', $wallet_solde->id)
            ->orWhere($number_compte,  $wallet_solde->wallet_id)
            ->orderBy('created_at', 'desc')->paginate($page);

        return $wallet_all;
    }


    public function getUsersMasters($wallet_solde, $page = 20)
    {

        $master_users =   SidUser::where('master_id', $wallet_solde)
            ->orderBy('created_at', 'desc')->paginate($page);
        return $master_users;
    }


    public function getTransactionsTypeAllAmount($wallets, $number_compte, $wallet_solde)
    {


        $data = [];


        foreach ($wallets::where('wallet_id', $wallet_solde->id)->orWhere($number_compte,  $wallet_solde->wallet_id)->where('status', 'SUCCESS')->get() as $transaction) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
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

    public function getUserTransactions($wallets, $wallet_solde, $numWallet)
    {
        return   $wallets::where('wallet_id', $wallet_solde->id)
            ->orWhere($numWallet, $wallet_solde->wallet_id)
            ->orderBy('created_at', 'desc')
            ->get();
    }


    public function getUserAllTransactions($wallets, $wallet_solde)
    {
        return   $wallets::where('wallet_id', $wallet_solde->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }


    public function getUserAllCumulTransactions($wallets)
    {
        return   $wallets::where('created_by', robus()->id)
            ->whereStatus('SUCCESS')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getUserAllCumulOrderTransactions($wallets)
    {
        return   $wallets::where('created_by', robus()->id)
            ->whereStatus(1)
            ->orderBy('created_at', 'desc')
            ->get();
    }


    public function getUserRemonte($wallets, $wallet_solde)
    {
        return   $wallets::where('sid_user_id', $wallet_solde->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getWalletSumGainUser($wallet)
    {
        // Récupérer toutes les commissions des banques avec le statut 'SUCCESS'
        $commissions = $wallet::whereStatus('SUCCESS')->pluck('commission_masters');

        // Calculer la somme des commissions
        $totalCommissions = $commissions->sum();

        return $totalCommissions;
    }

    public function getWalletTransactionDaysAccountGainUser($wallet)
    {

        $account_valeur = $wallet::whereDate('created_at', Carbon::now())
            ->where('created_by', robus()->id)
            ->whereStatus('SUCCESS')
            ->count();

        return $account_valeur;
    }

    public function getWalletTransactionDaysAccountStatusUser($wallet, $status)
    {

        $account_valeur = $wallet::whereDate('created_at', Carbon::now())
            ->where('created_by', robus()->id)
            ->whereStatus($status)
            ->count();

        return $account_valeur;
    }



    public function getMonthTransactionAmount($walletModel)
    {

        // Récupérer toutes les amounts des banques avec le statut 'SUCCESS'
        $amounts = $walletModel::whereStatus('SUCCESS')->whereMonth('created_at', now()->month)->pluck('amount');

        // Calculer la somme des amounts
        $totalAmounts = $amounts->sum();

        return $totalAmounts;
    }


    public function getMonthTransactionGainUser($walletModel)
    {

        // Récupérer toutes les amounts des banques avec le statut 'SUCCESS'
        $amounts = $walletModel::whereStatus('SUCCESS')->whereMonth('created_at', now()->month)->pluck('commissions');

        // Calculer la somme des amounts
        $totalAmounts = $amounts->sum();

        return $totalAmounts;
    }

    public function getYearTransactionGainUser($walletModel)
    {

        $year = Carbon::now()->year;

        // Récupérer toutes les amounts des banques avec le statut 'SUCCESS'
        $amounts = $walletModel::whereStatus('SUCCESS')
            ->whereYear('created_at', $year)
            ->pluck('commissions');

        // Calculer la somme des amounts
        $totalAmounts = $amounts->sum();

        return $totalAmounts;
    }



    public function getWalletTransactionMonthAccountStatusUser($wallet)
    {

        $account_valeur = $wallet::whereMonth('created_at', now()->month)
            ->where('created_by', robus()->id)
            ->count();

        return $account_valeur;
    }

    public function getYearTransactionSingleGainUser($walletModel)
    {

        $totalAmount = $walletModel::where('created_by', robus()->id)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');

        return $totalAmount;
    }


    public function getUnderDistributorsUser()
    {

        $under_distributors = SidUser::where('master_id', robus()->id)->get();

        return $under_distributors;
    }

    public function getMastersUser()
    {

        $under_distributors = SidUser::where('country_id', robus()->country->id)->whereIsMaster(true)->get();

        return $under_distributors;
    }

    public function getCustmersUser()
    {

        $under_distributors = SidUser::where('country_id', robus()->country->id)->whereIsCustumers(true)->get();

        return $under_distributors;
    }

    public function getUnderDistributorsComUser()
    {


        $under_distributors = SidUser::where('country_id', robus()->country->id)->whereIsUnderDistributer(true)->get();

        return $under_distributors;
    }
}
