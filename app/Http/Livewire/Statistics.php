<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Charts\MonthsHistorique;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Services\ServiceStatisticBackend;
use App\Models\Wallets\WalletWave;
use App\Models\Wallets\WalletXbet;
use App\Models\Wallets\WalletPush;
use App\Models\Wallets\WalletDjamo;
use App\Models\Wallets\WalletTresortMoney;
use App\Models\Wallets\WalletVersement;

class Statistics extends Component
{

    public function render()
    {



        $walletUser = Auth::guard('web')->user();
        $payment_error = $this->getTransactionCount(WalletPayment::class, 'ERROR', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by', 'wallet_payments.status',  'wallet_payments.is_web', false);
        $payment_padding = $this->getTransactionCount(WalletPayment::class, 'PENDING', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by', 'wallet_payments.status',  'wallet_payments.is_web', false);
        $payment_success = $this->getTransactionCount(WalletPayment::class, 'SUCCESS', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by', 'wallet_payments.status',  'wallet_payments.is_web', false);
        $transaction_error = $this->getTransactionCount(WalletTransaction::class, 'ERROR', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status',  'wallet_transactions.is_web', false);
        $transaction_padding = $this->getTransactionCount(WalletTransaction::class, 'PENDING', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status',  'wallet_transactions.is_web', false);
        $transaction_success = $this->getTransactionCount(WalletTransaction::class, 'SUCCESS', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status',  'wallet_transactions.is_web', false);
        $bank_error = $this->getTransactionCount(WalletBank::class, 'ERROR', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by', 'wallet_banks.status',  'wallet_banks.is_web', false);
        $bank_padding = $this->getTransactionCount(WalletBank::class, 'PENDING', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by', 'wallet_banks.status',  'wallet_banks.is_web', false);
        $bank_success = $this->getTransactionCount(WalletBank::class, 'SUCCESS', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by', 'wallet_banks.status',  'wallet_banks.is_web', false);
        $deposit_error = $this->getTransactionCount(WalletDeposit::class, 'ERROR', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by', 'wallet_deposits.status',  'wallet_deposits.is_web', false);
        $deposit_padding = $this->getTransactionCount(WalletDeposit::class, 'PENDING', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by', 'wallet_deposits.status',  'wallet_deposits.is_web', false);
        $deposit_success = $this->getTransactionCount(WalletDeposit::class, 'SUCCESS', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by', 'wallet_deposits.status',  'wallet_deposits.is_web', false);

        $phone_credit_error = $this->getTransactionCount(WalletPhoneCredit::class, 'ERROR',  'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by', 'wallet_phone_credits.status',  'wallet_phone_credits.is_web', false);
        $phone_credit_padding = $this->getTransactionCount(WalletPhoneCredit::class, 'PENDING', 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by', 'wallet_phone_credits.status',  'wallet_phone_credits.is_web', false);
        $phone_credit_success = $this->getTransactionCount(WalletPhoneCredit::class, 'SUCCESS',  'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by', 'wallet_phone_credits.status',  'wallet_phone_credits.is_web', false);

        $inter_error = $this->getTransactionCount(WalletInter::class, 'ERROR',  'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by', 'wallet_inters.status',  'wallet_inters.is_web', false);
        $inter_padding = $this->getTransactionCount(WalletInter::class, 'PENDING', 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by', 'wallet_inters.status',  'wallet_inters.is_web', false);
        $inter_success = $this->getTransactionCount(WalletInter::class, 'SUCCESS',  'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by', 'wallet_inters.status',  'wallet_inters.is_web', false);

        $xbet_error = $this->getTransactionCount(WalletXbet::class, 'ERROR',  'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by', 'wallet_xbets.status',  'wallet_xbets.is_web', false);
        $xbet_padding = $this->getTransactionCount(WalletXbet::class, 'PENDING', 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by', 'wallet_xbets.status',  'wallet_xbets.is_web', false);
        $xbet_success = $this->getTransactionCount(WalletXbet::class, 'SUCCESS',  'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by', 'wallet_xbets.status',  'wallet_xbets.is_web', false);

        $wave_error = $this->getTransactionCount(WalletWave::class, 'ERROR',  'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by', 'wallet_waves.status',  'wallet_waves.is_web', false);
        $wave_padding = $this->getTransactionCount(WalletWave::class, 'PENDING', 'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by', 'wallet_waves.status',  'wallet_waves.is_web', false);
        $wave_success = $this->getTransactionCount(WalletWave::class, 'SUCCESS',  'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by', 'wallet_waves.status',  'wallet_waves.is_web', false);

        // Push
        $push_error = $this->getTransactionCount(WalletPush::class, 'ERROR',  'wallet_pushes.wallet_id', 'wallet_pushes.created_at', 'wallet_pushes.updated_by', 'wallet_pushes.status',  'wallet_pushes.is_web', false);
        $push_padding = $this->getTransactionCount(WalletPush::class, 'PENDING', 'wallet_pushes.wallet_id', 'wallet_pushes.created_at', 'wallet_pushes.updated_by', 'wallet_pushes.status',  'wallet_pushes.is_web', false);
        $push_success = $this->getTransactionCount(WalletPush::class, 'SUCCESS',  'wallet_pushes.wallet_id', 'wallet_pushes.created_at', 'wallet_pushes.updated_by', 'wallet_pushes.status',  'wallet_pushes.is_web', false);

        $versment_error = $this->getTransactionCount(WalletVersement::class, 'ERROR',  'wallet_versements.wallet_id', 'wallet_versements.created_at', 'wallet_versements.updated_by', 'wallet_versements.status',  'wallet_versements.is_web', false);
        $versement_padding = $this->getTransactionCount(WalletVersement::class, 'PENDING', 'wallet_versements.wallet_id', 'wallet_versements.created_at', 'wallet_versements.updated_by', 'wallet_versements.status',  'wallet_versements.is_web', false);
        $versement_success = $this->getTransactionCount(WalletVersement::class, 'SUCCESS',  'wallet_versements.wallet_id', 'wallet_versements.created_at', 'wallet_versements.updated_by', 'wallet_versements.status',  'wallet_versements.is_web', false);

        $tresort_error = $this->getTransactionCount(WalletTresortMoney::class, 'ERROR',  'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_at', 'wallet_tresort_money.updated_by', 'wallet_tresort_money.status',  'wallet_tresort_money.is_web', false);
        $tresort_padding = $this->getTransactionCount(WalletTresortMoney::class, 'PENDING', 'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_at', 'wallet_tresort_money.updated_by', 'wallet_tresort_money.status',  'wallet_tresort_money.is_web', false);
        $tresort_success = $this->getTransactionCount(WalletTresortMoney::class, 'SUCCESS',  'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_at', 'wallet_tresort_money.updated_by', 'wallet_tresort_money.status',  'wallet_tresort_money.is_web', false);


        $djamo_error = $this->getTransactionCount(WalletDjamo::class, 'ERROR',  'wallet_djamos.wallet_id', 'wallet_djamos.created_at', 'wallet_djamos.updated_by', 'wallet_djamos.status',  'wallet_djamos.is_web', false);
        $djamo_padding = $this->getTransactionCount(WalletDjamo::class, 'PENDING', 'wallet_djamos.wallet_id', 'wallet_djamos.created_at', 'wallet_djamos.updated_by', 'wallet_djamos.status',  'wallet_djamos.is_web', false);
        $djamo_success = $this->getTransactionCount(WalletDjamo::class, 'SUCCESS',  'wallet_djamos.wallet_id', 'wallet_djamos.created_at', 'wallet_djamos.updated_by', 'wallet_djamos.status',  'wallet_djamos.is_web', false);

        $sumError = $payment_error  + $transaction_error + $bank_error  +  $deposit_error  + $phone_credit_error +   $inter_error + $xbet_error + $push_error + $versment_error + $tresort_error + $djamo_error;
        $sumPending = $payment_padding  + $transaction_padding + $bank_padding  +  $deposit_padding  + $phone_credit_padding +   $inter_padding  + $xbet_padding + $push_padding + $tresort_padding + $djamo_padding + $versement_padding;
        $sumSuccess = $payment_success  + $transaction_success + $bank_success  +  $deposit_success  + $phone_credit_success +   $inter_success +  $xbet_success + $push_success + $versement_success + $tresort_success + $djamo_success;

        return view('livewire.statistics', [

            'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
            'sum_error' => $sumError,
            'sum_success' => $sumPending,
            'pending_success' => $sumSuccess,
            'wallet_Banks_last' => WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_banks.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->limit(3)->select('wallet_banks.*')->orderBy('wallet_banks.created_at', 'desc')->get(),
            'Wallet_Deposits_last' => WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_deposits.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->limit(3)->select('wallet_deposits.*')->orderBy('wallet_deposits.created_at', 'desc')->get(),

            'Wallet_Inters_last' => WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
                ->where('wallet_inters.country_id', Auth::guard('web')->user()->country->id)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_inters.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->limit(3)->select('wallet_inters.*')->orderBy('wallet_inters.created_at', 'desc')->get(),

            'Wallet_Payments_last' => WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_payments.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->limit(3)->select('wallet_payments.*')->orderBy('wallet_payments.created_at', 'desc')->get(),
            'Wallet_Transactions_last' => WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_transactions.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->limit(3)->select('wallet_transactions.*')->orderBy('created_at', 'desc')->get(),
                
            'Wallet_PhoneCredits_last' => WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_phone_credits.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->limit(3)->select('wallet_phone_credits.*')->orderBy('wallet_phone_credits.created_at', 'desc')->get(),
            'Wallet_Reloads_last' => WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_reloads.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->limit(3)->select('wallet_reloads.*')->orderBy('wallet_reloads.created_at', 'desc')->get(),


            //new Historicals
            'Wallet_virement_last' => WalletVersement::join('wallets', 'wallet_versements.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_versements.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->limit(3)->select('wallet_versements.*')->orderBy('wallet_versements.created_at', 'desc')->get(),

            'Wallet_push_last' => WalletPush::join('wallets', 'wallet_pushes.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_pushes.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->limit(3)->select('wallet_pushes.*')->orderBy('wallet_pushes.created_at', 'desc')->get(),


            'Wallet_tresort_last' => WalletTresortMoney::join('wallets', 'wallet_tresort_money.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_tresort_money.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->limit(3)->select('wallet_tresort_money.*')->orderBy('wallet_tresort_money.created_at', 'desc')->get(),

            'Wallet_djamo_last' => WalletDjamo::join('wallets', 'wallet_djamos.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_djamos.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->limit(3)->select('wallet_djamos.*')->orderBy('wallet_djamos.created_at', 'desc')->get(),

            'Wallet_xbet_last' => WalletXbet::join('wallets', 'wallet_xbets.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_xbets.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->limit(3)->select('wallet_xbets.*')->orderBy('wallet_xbets.created_at', 'desc')->get(),

            'usersCountry' =>  $walletUser->country->currencie->name,

            'sum_deposit' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_by', 'wallet_deposits.created_at', 'wallet_deposits.status', 'wallet_deposits.amount'),
            'sum_deposit_gain' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_by', 'wallet_deposits.created_at', 'wallet_deposits.status', 'wallet_deposits.commissions'),

            'sum_payment' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_by', 'wallet_payments.created_at', 'wallet_payments.status', 'wallet_payments.amount'),
            'sum_payment_gain' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_by', 'wallet_payments.created_at', 'wallet_payments.status', 'wallet_payments.commissions'),

            'sum_transaction' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_by', 'wallet_transactions.created_at', 'wallet_transactions.status', 'wallet_transactions.amount'),
            'sum_transaction_gain' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_by', 'wallet_transactions.created_at', 'wallet_transactions.status', 'wallet_transactions.commissions'),

            'sum_inter' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_by', 'wallet_inters.created_at', 'wallet_inters.status', 'wallet_inters.amount'),
            'sum_inter_gain' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_by', 'wallet_inters.created_at', 'wallet_inters.status', 'wallet_inters.commissions'),

            'sum_phoneCredit' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_by', 'wallet_phone_credits.created_at', 'wallet_phone_credits.status', 'wallet_phone_credits.amount'),
            'sum_phoneCredit_gain' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_by', 'wallet_phone_credits.created_at', 'wallet_phone_credits.status', 'wallet_phone_credits.commissions'),

            'sum_bank' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_by', 'wallet_banks.created_at', 'wallet_banks.status', 'wallet_banks.amount'),
            'sum_bank_gain' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_by', 'wallet_banks.created_at', 'wallet_banks.status', 'wallet_banks.commissions'),

            // New statistuque

            'sum_djamo' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletDjamo::class, 'wallet_djamos.wallet_id', 'wallet_djamos.created_by', 'wallet_djamos.created_at', 'wallet_djamos.status', 'wallet_djamos.amount'),
            'sum_djamo_gain' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletDjamo::class, 'wallet_djamos.wallet_id', 'wallet_djamos.created_by', 'wallet_djamos.created_at', 'wallet_djamos.status', 'wallet_djamos.commissions'),

            'sum_tresort' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletTresortMoney::class, 'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_by', 'wallet_tresort_money.created_at', 'wallet_tresort_money.status', 'wallet_tresort_money.amount'),
            'sum_tresort_gain' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletTresortMoney::class, 'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_by', 'wallet_tresort_money.created_at', 'wallet_tresort_money.status', 'wallet_tresort_money.commissions'),

            'sum_push' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletPush::class, 'wallet_pushes.wallet_id', 'wallet_pushes.created_by', 'wallet_pushes.created_at', 'wallet_pushes.status', 'wallet_pushes.amount'),
            'sum_push_gain' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletPush::class, 'wallet_pushes.wallet_id', 'wallet_pushes.created_by', 'wallet_pushes.created_at', 'wallet_pushes.status', 'wallet_pushes.commissions'),

            'sum_xbet' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletXbet::class, 'wallet_xbets.wallet_id', 'wallet_xbets.created_by', 'wallet_xbets.created_at', 'wallet_xbets.status', 'wallet_xbets.amount'),
            'sum_xbet_gain' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletXbet::class, 'wallet_xbets.wallet_id', 'wallet_xbets.created_by', 'wallet_xbets.created_at', 'wallet_xbets.status', 'wallet_xbets.commissions'),

            'sum_versment' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletVersement::class, 'wallet_versements.wallet_id', 'wallet_versements.created_by', 'wallet_versements.created_at', 'wallet_versements.status', 'wallet_versements.amount'),
            'sum_versment_gain' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletVersement::class, 'wallet_versements.wallet_id', 'wallet_versements.created_by', 'wallet_versements.created_at', 'wallet_versements.status', 'wallet_versements.commissions'),

        ]);
    }


    public function getTransactionCount($walletModel, $status, $wallet_transactions, $transaction_days, $walletMy, $wallet_status, $IswebStatus, $isWeb)
    {
        // Commencer par construire la requête de base
        $query = $walletModel::join('wallets', $wallet_transactions, 'wallets.id')
            ->whereDate($transaction_days, Carbon::today())
            ->where($wallet_status, $status)
            ->where($IswebStatus, $isWeb);

        // Vérification de l'utilisateur distributeur
        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transactions) {
                $query->where($wallet_transactions, sabdy()->wallet->id);
            });
        } elseif (sabdy()->is_com_zone) {
            $query->whereIn('wallets.sid_user_id', function ($sub) {
                $sub->select('id')
                    ->from('sid_users')
                    ->where('com_zone_id', sabdy()->id);
            });
        } else {

            $query->where(function ($query) use ($walletMy) {
                $query->where($walletMy, sabdy()->id)
                    ->orWhere($walletMy, '!=', sabdy()->id);
            })
                ->where(function ($query) {
                    $countryId = sabdy()->country->id;
                    $query->where('wallets.country_id', '!=', $countryId)
                        ->orWhere('wallets.country_id', '=', $countryId);
                });
        }

        // Compter les transactions
        return $query->count();
    }

    private function scopeComZoneLinkedUsers($query)
    {
        return $query->whereIn('wallets.sid_user_id', function ($subQuery) {
            $subQuery->select('id')
                ->from('sid_users')
                ->where('com_zone_id', sabdy()->id);
        });
    }
}
