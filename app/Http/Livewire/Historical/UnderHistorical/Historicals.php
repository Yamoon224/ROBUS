<?php

namespace App\Http\Livewire\Historical\UnderHistorical;

use Livewire\Component;
use App\Models\WithMerchant;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use App\Http\Services\HistoriqueService;

class Historicals extends Component
{
    public function render()
    {



        $walletUser = Auth::guard('web')->user();



        if ($walletUser->wallet->sidUser->is_under_distributer == true) {

            return view('livewire.historical.under-historical.historicals', [

                'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
                'sum_error' => $this->ReloadErrorUser  + $this->VirementErrorUser + $this->PhoneCreditErrorUser  +  $this->DepositErrorUser +  $this->InterErrorUser + $this->PaymentErrorUser +   $this->TransactionErrorUser,
                'sum_success' => $this->ReloadSuccessUser  + $this->VirementSuccessUser + $this->PhoneCreditSuccessUser  +  $this->DepositSuccessUser +  $this->InterSuccessUser + $this->PaymentSuccessUser +   $this->TransactionSuccessUser,
                'pending_success' => $this->ReloadPenddingUser  + $this->VirementPenddingUser + $this->PhoneCreditPenddingUser  +  $this->DepositPenddingUser +  $this->InterPenddingUser + $this->PaymentPenddingUser +   $this->TransactionPenddingUser,
                'wallet_Banks_last' => WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $walletUser->country->id)->select('wallet_banks.*')
                    ->where('wallet_banks.created_by', Auth::guard('web')->user()->id)
                    ->limit(1)->orderBy('wallet_banks.created_at', 'desc')->get(),
                'Wallet_Deposits_last' => WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $walletUser->country->id)->select('wallet_deposits.*')
                    ->where('wallet_deposits.created_by', Auth::guard('web')->user()->id)
                    ->limit(5)->orderBy('wallet_deposits.created_at', 'desc')->get(),
                'Wallet_Inters_last' => WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $walletUser->country->id)->select('wallet_inters.*')
                    ->where('wallet_inters.created_by', Auth::guard('web')->user()->id)
                    ->limit(5)->orderBy('wallet_inters.created_at', 'desc')->get(),
                'Wallet_Payments_last' => WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $walletUser->country->id)->select('wallet_payments.*')
                    ->where('wallet_payments.created_by', Auth::guard('web')->user()->id)
                    ->limit(5)->orderBy('wallet_payments.created_at', 'desc')->get(),
                'Wallet_Transactions_last' => WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                    ->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)
                    ->where('wallets.country_id', $walletUser->country->id)->select('wallet_transactions.*')
                    ->limit(5)->orderBy('wallet_transactions.created_at', 'desc')->get(),
                'Wallet_PhoneCredits_last' => WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                    ->where('wallet_phone_credits.created_by', Auth::guard('web')->user()->id)
                    ->where('wallets.country_id', $walletUser->country->id)->select('wallet_phone_credits.*')
                    ->limit(5)->orderBy('wallet_phone_credits.created_at', 'desc')->get(),
                'Wallet_Reloads_last' => WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $walletUser->country->id)
                    ->where('wallet_reloads.created_by', Auth::guard('web')->user()->id)
                    ->select('wallet_reloads.*')
                    ->limit(5)->orderBy('wallet_reloads.created_at', 'desc')->get(),
                'usersCountry' =>  $walletUser->country->currencie->name,
                'sum_bank' => $this->WalletBanksUser,
                'sum_deposit' => $this->WalletDepositsUser,
                'sum_payment' => $this->WalletPaymentsUser,
                'sum_reload' => $this->WalletReloadsUser,
                'sum_transaction' => $this->WalletTransactionsUser,
                'sum_inter' => $this->WalletIntersUser,
                'sum_phoneCredit' => $this->WalletIntersUser,


            ]);
        } else
        {

            return view('livewire.historical.under-historical.historicals', [

                'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
                'sum_error' => $this->ReloadError  + $this->VirementError + $this->PhoneCreditError  +  $this->DepositError +  $this->InterError + $this->PaymentError +   $this->TransactionError,
                'sum_success' => $this->ReloadSuccess  + $this->VirementSuccess + $this->PhoneCreditSuccess  +  $this->DepositSuccess +  $this->InterSuccess + $this->PaymentSuccess +   $this->TransactionSuccess,
                'pending_success' => $this->ReloadPendding  + $this->VirementPendding + $this->PhoneCreditPendding  +  $this->DepositPendding +  $this->InterPendding + $this->PaymentPendding +   $this->TransactionPendding,
                'wallet_Banks_last' => WalletBank::select('wallet_banks.*')->limit(5)->orderBy('wallet_banks.created_at', 'desc')->get(),
                'Wallet_Deposits_last' => WalletDeposit::select('wallet_deposits.*')->limit(5)->orderBy('wallet_deposits.created_at', 'desc')->get(),
                'Wallet_Inters_last' => WalletInter::where('wallet_inters.country_id', $walletUser->country->id)->select('wallet_inters.*')->limit(1)->orderBy('wallet_inters.created_at', 'desc')->get(),
                'Wallet_Payments_last' => WalletPayment::select('wallet_payments.*')->limit(5)->orderBy('wallet_payments.created_at', 'desc')->get(),
                'Wallet_Transactions_last' => WalletTransaction::select('wallet_transactions.*')->limit(5)->orderBy('wallet_transactions.created_at', 'desc')->get(),
                'Wallet_PhoneCredits_last' => WalletPhoneCredit::select('wallet_phone_credits.*')->limit(5)->orderBy('wallet_phone_credits.created_at', 'desc')->get(),
                'Wallet_Reloads_last' => WalletReload::select('wallet_reloads.*')->limit(5)->orderBy('wallet_reloads.created_at', 'desc')->get(),
                'With_drawer_merchant' => WithMerchant::limit(5)->orderBy('date_removed', 'desc')->get(),
                'usersCountry' =>  $walletUser->country->currencie->name,
                'sum_bank' => $this->WalletBankSumAdmin,
                'sum_deposit' => $this->WalletDepositSumAdmin,
                'sum_payment' => $this->WalletPaymentSumAdmin,
                'sum_reload' => $this->WalletReloadSumAdmin,
                'sum_transaction' => $this->WalletTransactionSumAdmin,
                'sum_inter' => $this->WalletInterSumAdmin,
                'sum_phoneCredit' => $this->WalletInterSumAdmin,

            ]);
        }
    }


    // Sum Admin
    public function getWalletBankSumAdminProperty()
    {


        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->whereBetween('wallet_banks.created_at', [Carbon::today(), Carbon::today()->endOfDay()])->select('wallet_banks.*')->where('wallet_banks.status', 'SUCCESS')->sum('wallet_banks.amount');
    }

    public function getWalletDepositSumAdminProperty()
    {


        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_deposits.*')
            ->whereBetween('wallet_deposits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])->where('wallet_deposits.status', 'SUCCESS')->sum('wallet_deposits.amount');
    }


    public function getWalletPaymentSumAdminProperty()
    {


        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_payments.*')
            ->whereBetween('wallet_payments.created_at', [Carbon::today(), Carbon::today()->endOfDay()])->where('wallet_payments.status', 'SUCCESS')->sum('wallet_payments.amount');
    }

    public function getWalletTransactionSumAdminProperty()
    {


        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_transactions.*')
            ->whereBetween('wallet_transactions.created_at', [Carbon::today(), Carbon::today()->endOfDay()])->where('wallet_transactions.status', 'SUCCESS')->sum('wallet_transactions.amount');
    }

    public function getWalletInterSumAdminProperty()
    {


        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_inters.*')
            ->whereBetween('wallet_inters.created_at', [Carbon::today(), Carbon::today()->endOfDay()])->where('wallet_inters.status', 'SUCCESS')->sum('wallet_inters.amount');
    }
    public function getWalletPhoneCreditSumAdminProperty()
    {


        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_phone_credits.*')
            ->whereBetween('wallet_phone_credits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])->where('wallet_phone_credits.status', 'SUCCESS')->sum('wallet_phone_credits.amount');
    }

    public function getWalletReloadSumAdminProperty()
    {



        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_reloads.*')
            ->whereBetween('wallet_reloads.created_at', [Carbon::today(), Carbon::today()->endOfDay()])->where('wallet_reloads.status', 'SUCCESS')->sum('wallet_reloads.amount');
    }

    // User
    public function getWalletBanksUserProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->where('wallet_banks.created_by', Auth::guard('web')->user()->id)
            ->select('wallet_banks.*')
            ->whereBetween('wallet_banks.created_at', [Carbon::today(), Carbon::today()->endOfDay()])->where('wallet_banks.status', 'SUCCESS')->sum('wallet_banks.amount');
    }

    public function getWalletDepositsUserProperty()
    {

        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id',  Auth::guard('web')->user()->country->id)
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.created_by',  Auth::guard('web')->user()->id)
            ->whereBetween('wallet_deposits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])->where('wallet_deposits.status', 'SUCCESS')->sum('wallet_deposits.amount');
    }

    public function getWalletIntersUserProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallets.country_id',  Auth::guard('web')->user()->country->id)
            ->select('wallet_inters.*')
            ->where('wallet_inters.created_by',  Auth::guard('web')->user()->id)
            ->whereBetween('wallet_inters.created_at', [Carbon::today(), Carbon::today()->endOfDay()])->where('wallet_inters.status', 'SUCCESS')->sum('wallet_inters.amount');
    }

    public function getWalletPaymentsUserProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id',  Auth::guard('web')->user()->country->id)
            ->select('wallet_payments.*')
            ->where('wallet_payments.created_by',  Auth::guard('web')->user()->id)
            ->whereBetween('wallet_payments.created_at', [Carbon::today(), Carbon::today()->endOfDay()])->where('wallet_payments.status', 'SUCCESS')->sum('wallet_payments.amount');
    }


    public function getWalletTransactionsUserProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id',  Auth::guard('web')->user()->country->id)
            ->select('wallet_transactions.*')
            ->where('wallet_transactions.created_by',  Auth::guard('web')->user()->id)
            ->whereBetween('wallet_transactions.created_at', [Carbon::today(), Carbon::today()->endOfDay()])->where('wallet_transactions.status', 'SUCCESS')->sum('wallet_transactions.amount');
    }

    public function getWalletPhoneCreditsUserProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.created_by', Auth::guard('web')->user()->id)
            ->whereBetween('wallet_phone_credits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])->where('wallet_phone_credits.status', 'SUCCESS')->sum('wallet_phone_credits.amount');
    }

    public function getWalletReloadsUserProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id',  Auth::guard('web')->user()->country->id)
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.created_by',  Auth::guard('web')->user()->id)
            ->whereBetween('wallet_reloads.created_at', [Carbon::today(), Carbon::today()->endOfDay()])->where('wallet_reloads.status', 'SUCCESS')->sum('wallet_reloads.amount');
    }

    // Details Transaction User
    public function getTransactionErrorUserProperty()
    {
        $transactionError = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_transactions.*')
            ->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'ERROR')->whereBetween('wallet_transactions.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $transactionError;
    }

    public function getTransactionSuccessUserProperty()
    {

        $transactionSuccess = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_transactions.*')
            ->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->whereBetween('wallet_transactions.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $transactionSuccess;
    }

    public function getTransactionPenddingUserProperty()
    {

        $transactionPendding = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_transactions.*')
            ->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'PENDING')->whereBetween('wallet_transactions.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $transactionPendding;
    }

    public function getPaymentErrorUserProperty()
    {

        $paimentError = WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'ERROR')->where('wallet_payments.created_by', Auth::guard('web')->user()->id)->whereBetween('wallet_payments.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $paimentError;
    }

    public function getPaymentSuccessUserProperty()
    {

        $paimentSuccess = WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'SUCCESS')->where('wallet_payments.created_by', Auth::guard('web')->user()->id)->whereBetween('wallet_payments.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $paimentSuccess;
    }

    public function getPaymentPenddingUserProperty()
    {

        $paimentPendding = WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'PENDING')->where('wallet_payments.created_by', Auth::guard('web')->user()->id)->whereBetween('wallet_payments.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $paimentPendding;
    }


    public function getReloadErrorUserProperty()
    {

        $reloadError = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'ERROR')->where('wallet_reloads.created_by', Auth::guard('web')->user()->id)->whereBetween('wallet_reloads.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $reloadError;
    }

    public function getReloadSuccessUserProperty()
    {


        $reloadSuccess = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'SUCCESS')->where('wallet_reloads.created_by', Auth::guard('web')->user()->id)->whereBetween('wallet_reloads.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $reloadSuccess;
    }

    public function getReloadPenddingUserProperty()
    {

        $reloadPendding = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'PENDING')->where('wallet_reloads.created_by', Auth::guard('web')->user()->id)->whereBetween('wallet_reloads.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $reloadPendding;
    }

    public function getVirementSuccessUserProperty()
    {

        $virementSuccess = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_banks.*')
            ->where('wallet_banks.created_by', Auth::guard('web')->user()->id)
            ->where('wallet_banks.status', 'SUCCESS')->whereBetween('wallet_banks.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $virementSuccess;
    }

    public function getVirementPenddingUserProperty()
    {

        $virementPendding = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_banks.*')
            ->where('wallet_banks.created_by', Auth::guard('web')->user()->id)
            ->where('wallet_banks.status', 'PENDING')->whereBetween('wallet_banks.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $virementPendding;
    }

    public function getVirementErrorUserProperty()
    {

        $virementError = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_banks.*')
            ->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'ERROR')->whereBetween('wallet_banks.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $virementError;
    }

    public function getInterErrorUserProperty()
    {

        $interError = WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_inters.*')
            ->where('wallet_inters.created_by', Auth::guard('web')->user()->id)->where('wallet_inters.status', 'ERROR')->whereBetween('wallet_inters.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $interError;
    }

    public function getInterSuccessUserProperty()
    {

        $interSuccess = WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_inters.*')
            ->where('wallet_inters.created_by', Auth::guard('web')->user()->id)->where('wallet_inters.status', 'SUCCESS')->whereBetween('wallet_inters.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $interSuccess;
    }

    public function getInterPenddingUserProperty()
    {

        $interPendding = WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_inters.*')
            ->where('wallet_inters.created_by', Auth::guard('web')->user()->id)->where('wallet_inters.status', 'PENDING')->whereBetween('wallet_inters.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $interPendding;
    }

    public function getPhoneCreditErrorUserProperty()
    {

        $phoneCreditError = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.created_by', Auth::guard('web')->user()->id)->where('wallet_phone_credits.status', 'ERROR')->whereBetween('wallet_phone_credits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $phoneCreditError;
    }

    public function getPhoneCreditSuccessUserProperty()
    {

        $phoneCreditSuccess = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.created_by', Auth::guard('web')->user()->id)->where('wallet_phone_credits.status', 'SUCCESS')->whereBetween('wallet_phone_credits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $phoneCreditSuccess;
    }

    public function getPhoneCreditPenddingUserProperty()
    {

        $phoneCreditPendding = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.created_by', Auth::guard('web')->user()->id)->where('wallet_phone_credits.status', 'PENDING')->whereBetween('wallet_phone_credits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $phoneCreditPendding;
    }

    public function getDepositErrorUserProperty()
    {

        $depositError = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.created_by', Auth::guard('web')->user()->id)->where('wallet_deposits.status', 'ERROR')->whereBetween('wallet_deposits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $depositError;
    }

    public function getDepositSuccessUserProperty()
    {

        $depositSuccess = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.created_by', Auth::guard('web')->user()->id)->where('wallet_deposits.status', 'SUCCESS')->whereBetween('wallet_deposits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $depositSuccess;
    }

    public function getDepositPenddingUserProperty()
    {

        $depositPendding = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.created_by', Auth::guard('web')->user()->id)->where('wallet_deposits.status', 'PENDING')->whereBetween('wallet_deposits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $depositPendding;
    }
    // Details Transaction
    public function getTransactionErrorProperty()
    {

        $transactionError = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_transactions.*')
            ->where('wallet_transactions.status', 'ERROR')->whereBetween('wallet_transactions.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $transactionError;
    }

    public function getTransactionSuccessProperty()
    {

        $transactionSuccess = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_transactions.*')
            ->where('wallet_transactions.status', 'SUCCESS')->whereBetween('wallet_transactions.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $transactionSuccess;
    }

    public function getTransactionPenddingProperty()
    {

        $transactionPendding = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_transactions.*')
            ->where('wallet_transactions.status', 'PENDING')->whereBetween('wallet_transactions.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $transactionPendding;
    }

    public function getPaymentErrorProperty()
    {

        $paimentError = WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'ERROR')->whereBetween('wallet_payments.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $paimentError;
    }

    public function getPaymentSuccessProperty()
    {

        $paimentSuccess = WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'SUCCESS')->whereBetween('wallet_payments.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $paimentSuccess;
    }

    public function getPaymentPenddingProperty()
    {

        $paimentPendding = WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'PENDING')->whereBetween('wallet_payments.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $paimentPendding;
    }


    public function getReloadErrorProperty()
    {

        $reloadError = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'ERROR')->whereBetween('wallet_reloads.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $reloadError;
    }

    public function getReloadSuccessProperty()
    {


        $reloadSuccess = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'SUCCESS')->whereBetween('wallet_reloads.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $reloadSuccess;
    }

    public function getReloadPenddingProperty()
    {

        $reloadPendding = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'PENDING')->whereBetween('wallet_reloads.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $reloadPendding;
    }

    public function getVirementSuccessProperty()
    {

        $virementSuccess = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_banks.*')
            ->where('wallet_banks.status', 'SUCCESS')->whereBetween('wallet_banks.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $virementSuccess;
    }

    public function getVirementPenddingProperty()
    {

        $virementPendding = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_banks.*')
            ->where('wallet_banks.status', 'PENDING')->whereBetween('wallet_banks.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $virementPendding;
    }

    public function getVirementErrorProperty()
    {

        $interError = WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_inters.*')
            ->where('wallet_inters.status', 'ERROR')->whereBetween('wallet_inters.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $interError;
    }

    public function getInterErrorProperty()
    {

        $interError = WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_inters.*')
            ->where('wallet_inters.status', 'ERROR')->whereBetween('wallet_inters.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $interError;
    }

    public function getInterSuccessProperty()
    {

        $interSuccess = WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_inters.*')
            ->where('wallet_inters.status', 'SUCCESS')->whereBetween('wallet_inters.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $interSuccess;
    }

    public function getInterPenddingProperty()
    {
        $interPendding = WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_inters.*')
            ->where('wallet_inters.status', 'PENDING')->whereBetween('wallet_inters.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $interPendding;
    }

    public function getPhoneCreditErrorProperty()
    {
        $phoneCreditError = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.status', 'ERROR')->whereBetween('wallet_phone_credits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $phoneCreditError;
    }

    public function getPhoneCreditSuccessProperty()
    {
        $phoneCreditSuccess = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.status', 'SUCCESS')->whereBetween('wallet_phone_credits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $phoneCreditSuccess;
    }

    public function getPhoneCreditPenddingProperty()
    {

        $phoneCreditPendding = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.status', 'PENDING')->whereBetween('wallet_phone_credits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $phoneCreditPendding;
    }

    public function getDepositErrorProperty()
    {


        $depositError = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.status', 'ERROR')->whereBetween('wallet_deposits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $depositError;
    }

    public function getDepositSuccessProperty()
    {

        $depositSuccess = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.status', 'SUCCESS')->whereBetween('wallet_deposits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $depositSuccess;
    }

    public function getDepositPenddingProperty()
    {

        $depositPendding = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.status', 'PENDING')->whereBetween('wallet_deposits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();
        return $depositPendding;
    }
   
}
