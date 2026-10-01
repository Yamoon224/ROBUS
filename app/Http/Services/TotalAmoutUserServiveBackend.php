<?php

namespace App\Http\Services;

use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletPush;
use App\Models\Wallets\WalletTresortMoney;
use App\Models\Wallets\WalletVersement;
use App\Models\Wallets\WalletDjamo;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletTransaction;
use App\Notifications\AdminNotification;
use App\Notifications\UserNotification;
use Illuminate\Support\Carbon;
use App\Models\Banks\BankCard;
use Illuminate\Support\Facades\Auth;
use App\Models\Providers\Provider;


class TotalAmoutUserServiveBackend
{
    /**
     * Distribuer la commission aux admins pour les compagnies acceptées
     */

    public function getSumValTransactionProperty()
    {

        $somm_valeur = $this->getWalletCeilingProperty(WalletBank::class, 'wallet_banks.wallet_id' ,'wallet_banks.created_by' ,'wallet_banks.created_at' ,'wallet_banks.*' ,  'wallet_banks.status')
            + $this->getWalletCeilingProperty(WalletDeposit::class, 'wallet_deposits.wallet_id' ,'wallet_deposits.created_by' ,'wallet_deposits.created_at' ,'wallet_deposits.*' ,  'wallet_deposits.status')
            +  $this->getWalletCeilingProperty(WalletInter::class, 'wallet_inters.wallet_id' ,'wallet_inters.created_by' ,'wallet_inters.created_at' ,'wallet_inters.*' ,  'wallet_inters.status')
            + $this->getWalletCeilingProperty(WalletPayment::class, 'wallet_payments.wallet_id' ,'wallet_payments.created_by' ,'wallet_payments.created_at' ,'wallet_payments.*' ,  'wallet_payments.status')
            + $this->getWalletCeilingProperty(WalletTransaction::class, 'wallet_transactions.wallet_id' ,'wallet_transactions.created_by' ,'wallet_transactions.created_at' ,'wallet_transactions.*' ,  'wallet_transactions.status')
            +  $this->getWalletCeilingProperty(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id' ,'wallet_phone_credits.created_by' ,'wallet_phone_credits.created_at' ,'wallet_phone_credits.*' ,  'wallet_phone_credits.status')
            +$this->getWalletCeilingProperty(WalletPush::class, 'wallet_pushes.wallet_id' ,'wallet_pushes.created_by' ,'wallet_pushes.created_at' ,'wallet_pushes.*' ,  'wallet_pushes.status')
            +$this->getWalletCeilingProperty(WalletTresortMoney::class, 'wallet_tresort_money.wallet_id' ,'wallet_tresort_money.created_by' ,'wallet_tresort_money.created_at' ,'wallet_tresort_money.*' ,  'wallet_tresort_money.status')
            + $this->getWalletCeilingProperty(WalletVersement::class, 'wallet_versements.wallet_id' ,'wallet_versements.created_by' ,'wallet_versements.created_at' ,'wallet_versements.*' ,  'wallet_versements.status')
            + $this->getWalletCeilingProperty(WalletDjamo::class, 'wallet_djamos.wallet_id' ,'wallet_djamos.created_by' ,'wallet_djamos.created_at' ,'wallet_djamos.*' ,  'wallet_djamos.status')
            + $this->getWalletBankCardCeilingProperty();

        return $somm_valeur;
    }


    public function getWalletCeilingProperty($walletClass, string $walletWalletId, string $walletCreatedBy, string $walletCreatedAt, string $allWallet, string $walletStatus)
    {
        $data = [];
        foreach (
            $walletClass::join('wallets', $walletWalletId, 'wallets.id')
                ->where(
                    'wallets.country_id',
                    sabdy()->country->id
                )
                ->where(
                    $walletCreatedBy,
                    sabdy()->id
                )
                ->whereDate($walletCreatedAt, Carbon::today())
                ->select($allWallet)
                ->where($walletStatus, 'SUCCESS')
                ->get()
            as $bank
        ) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }
    public function getNationTransactionProperty()
    {
        $walletUser = Auth::guard('web')->user();

        $transaction_nationnal = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', $walletUser->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 1)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();

        return $transaction_nationnal;
    }

    public function getPaiementTransactionProperty()
    {
        $walletUser = Auth::guard('web')->user();

        $payments = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', $walletUser->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 2)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();

        return $payments;
    }

    public function getVirementTransactionProperty()
    {
        $walletUser = Auth::guard('web')->user();

        $virement_transaction = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', $walletUser->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 4)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();

        return $virement_transaction;
    }

    public function getwalletXbetsCeilingProperty()
    {
        $walletUser = Auth::guard('web')->user();

        $virement_transaction = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', $walletUser->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 13)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();

        return $virement_transaction;
    }

    public function getReloadTransactionProperty()
    {
        $walletUser = Auth::guard('web')->user();

        $reload_transaction = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', $walletUser->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 5)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();

        return $reload_transaction;
    }


    public function getPhoneCreditTransactionProperty()
    {
        $walletUser = Auth::guard('web')->user();

        $phone_transaction = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', $walletUser->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 6)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();

        return $phone_transaction;
    }

    public function getAdminCIVProperty()
    {
        return SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->with('unreadNotifications') // Récupération des notifications non lues directement
            ->get();
    }

    public function getAdminValeurProperty()
    {

        $admins = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', Auth::guard('web')->user()->country->id)
            ->get();

        return  $admins;
    }

    public function getWalletBanksCeilingProperty()
    {
        $data = [];
        foreach (
            WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
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
            as $bank
        ) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }


    public function getWalletBankCardCeilingProperty()
    {
        $data = [];
        foreach (
            BankCard::where('created_by', sabdy()->id)
                ->whereDate('created_at', Carbon::today())->whereStatus(1)->get() as $bank
        ) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }


}
