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
        $data = [];
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where(
                'wallets.country_id',
                sabdy()->country->id
            )
            ->where(
                'wallet_banks.created_by',
                sabdy()->id
            )
            ->whereDate('wallet_banks.created_at', Carbon::today())
            ->select('wallet_banks.*')
            ->where('wallet_banks.status', 'SUCCESS')
            ->get()
            as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletDepositsCeiling()
    {
        $data = [];

        foreach (WalletDeposit::join(
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
            ->whereDate('wallet_deposits.created_at', Carbon::today())
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.status', 'SUCCESS')
            ->get()
            as $deposit) {
            $data[] = $deposit->amount;
        }

        return array_sum($data);
    }

    public function getWalletIntersCeiling()
    {
        $data = [];

        foreach (WalletInter::join(
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
            ->whereDate('wallet_inters.created_at', Carbon::today())
            ->select('wallet_inters.*')
            ->where('wallet_inters.status', 'SUCCESS')
            ->get()
            as $inter) {
            $data[] = $inter->amount;
        }

        return array_sum($data);
    }

    public function getWalletPaymentsCeiling()
    {
        $data = [];

        foreach (WalletPayment::join(
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
            ->whereDate('wallet_payments.created_at', Carbon::today())
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'SUCCESS')
            ->get()
            as $payment) {
            $data[] = $payment->amount;
        }

        return array_sum($data);
    }

    public function getWalletTransactionsCeiling()
    {
        $data = [];

        foreach (WalletTransaction::join(
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
            ->whereDate('wallet_transactions.created_at', Carbon::today())
            ->select('wallet_transactions.*')
            ->where('wallet_transactions.status', 'SUCCESS')
            ->get()
            as $transaction) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
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
        $data = [];

        foreach (WalletPhoneCredit::join(
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
            ->whereDate('wallet_phone_credits.created_at', Carbon::today())
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.status', 'SUCCESS')
            ->get()
            as $phone) {
            $data[] = $phone->amount;
        }

        return array_sum($data);
    }

    public function getWalletBankCardCeiling()
    {
        $data = [];
        foreach (BankCard::where('created_by', sabdy()->id)
            ->whereDate('created_at', Carbon::today())->whereStatus(1)->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletReloadsCeiling()
    {
        $data = [];

        foreach (WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where(
                'wallets.country_id',
                sabdy()->country->id
            )->where(
                'wallet_reloads.created_by',
                sabdy()->id
            )
            ->whereDate('wallet_reloads.created_at', Carbon::today())
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'SUCCESS')
            ->get()
            as $reload) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
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
