<?php

namespace App\Http\Services;

use App\Models\Sid\SidUser;
use App\Models\Banks\BankCard;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;



class CeldingValeurService
{

  
    public function getTransactionsWallet($wallet, $id)
    {
        return  $wallet::where('wallet_id', $id)
            ->orderBy('created_at', 'desc')->paginate(20);
    }

    public function getTransactionOperatorsWallet($wallet, $id)
    {
        return  $wallet::where('updated_by', $id)
            ->orderBy('created_at', 'desc')->paginate(20);
    }


       public function getTransactionUserWallet($wallet, $id, $num_wallet, $wallet_user)
    {
        return  $wallet::where('wallet_id', $id)
            ->orWhere($num_wallet,  $wallet_user)
            ->orderBy('created_at', 'desc')->paginate(20);
    }
    public function validateDailyLimit($som_transfers, $valeur_ceiling)
    {
        if ($som_transfers >= $valeur_ceiling) {
            return false;
        }

        return true;
    }

    public function validateAmount($transaction_amount_robus)
    {
        $val_amount = $transaction_amount_robus;

        if ($val_amount > sabdy()->wallet->amount) {
            return false;
        }

        return true;
    }


    public function validateAmountVirement($transaction_amount_robus)
    {
        $val_amount = $transaction_amount_robus;

        if ($val_amount > sabdy()->wallet->amount) {
            return false;
        }

        return true;
    }


    
    public function validateAmountDrawel($transaction_amount_robus, $num_wallet)
    {


        $wallet_search = Wallet::where('wallet_id',  $num_wallet)
            ->where('country_id',  sabdy()->country->id)
            ->whereStatus(true)
            ->first();

        $val_amount = $transaction_amount_robus;

        if ($val_amount > $wallet_search->amount) {
            return false;
        }


        //  Send message in phone
        return true;
    }

    public function getSumValTransactions()
    {

        return $this->getWalletBanksCeiling() + $this->getWalletDepositsCeiling() +
            $this->getWalletIntersCeiling() +
            $this->getWalletPaymentsCeiling() +
            $this->getWalletTransactionsCeiling() +
            $this->getWalletPhoneCreditsCeiling() +
            $this->getWalletBankCardCeiling() + $this->getWalletReloadsCeiling();

        // return  $somm_valeur;
    }
   
    public function getWalletBanksCeiling()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where(
            'wallets.country_id',
            sabdy()->country->id
            )
            ->where(
            'wallet_banks.created_by',
            sabdy()->id
            )
            ->whereBetween('wallet_banks.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->select('wallet_banks.*')
            ->where('wallet_banks.status', 'SUCCESS')
            ->sum('wallet_banks.amount');
    }

    public function getWalletDepositsCeiling()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletDeposit::join(
            'wallets',
            'wallet_deposits.wallet_id',
            'wallets.id'
            )
            ->where(
            'wallets.country_id',
            sabdy()->country->id
            )
            ->where(
            'wallet_deposits.created_by',
            sabdy()->id
            )
            ->whereBetween('wallet_deposits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.status', 'SUCCESS')
            ->sum('wallet_deposits.amount');
    }

    public function getWalletIntersCeiling()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletInter::join(
            'wallets',
            'wallet_inters.wallet_id',
            'wallets.id'
            )
            ->where(
            'wallets.country_id',
            sabdy()->country->id
            )
            ->where(
            'wallet_inters.created_by',
            sabdy()->id
            )
            ->whereBetween('wallet_inters.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->select('wallet_inters.*')
            ->where('wallet_inters.status', 'SUCCESS')
            ->sum('wallet_inters.amount');
    }

    public function getWalletPaymentsCeiling()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletPayment::join(
            'wallets',
            'wallet_payments.wallet_id',
            'wallets.id'
            )
            ->where(
            'wallets.country_id',
            sabdy()->country->id
            )
            ->where(
            'wallet_payments.created_by',
            sabdy()->id
            )
            ->whereBetween('wallet_payments.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'SUCCESS')
            ->sum('wallet_payments.amount');
    }

    public function getWalletTransactionsCeiling()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
            )
            ->where(
            'wallets.country_id',
            sabdy()->country->id
            )
            ->where(
            'wallet_transactions.created_by',
            sabdy()->id
            )
            ->whereBetween('wallet_transactions.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->select('wallet_transactions.*')
            ->where('wallet_transactions.status', 'SUCCESS')
            ->sum('wallet_transactions.amount');
    }

    public function adminCiv()
    {
       
       return  SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

    }
    public function getWalletPhoneCreditsCeiling()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletPhoneCredit::join(
            'wallets',
            'wallet_phone_credits.wallet_id',
            'wallets.id'
            )
            ->where(
            'wallets.country_id',
            sabdy()->country->id
            )
            ->where(
            'wallet_phone_credits.created_by',
            sabdy()->id
            )
            ->whereBetween('wallet_phone_credits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.status', 'SUCCESS')
            ->sum('wallet_phone_credits.amount');
    }

    public function getWalletBankCardCeiling()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return BankCard::where('created_by', sabdy()->id)
            ->whereBetween('created_at', [Carbon::today(), Carbon::today()->endOfDay()])->whereStatus(1)->sum('amount');
    }

    public function getWalletReloadsCeiling()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where(
            'wallets.country_id',
            sabdy()->country->id
            )->where(
            'wallet_reloads.created_by',
            sabdy()->id
            )
            ->whereBetween('wallet_reloads.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'SUCCESS')
            ->sum('wallet_reloads.amount');
    }


    public function getAdminDrawalCIV()
    {

        $admin_drawal =  SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->whereId(1)
            ->where('country_id', 107)
            ->first();

        return $admin_drawal;
    }
}
