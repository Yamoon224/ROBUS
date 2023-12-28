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

class Statistics extends Component
{

    public function render()
    {



        $walletUser = Auth::guard('web')->user();

       

        if ($walletUser->wallet->sidUser->is_under_distributer == true) {

            return view('livewire.statistics', [

                'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
                'sum_error' => $this->ReloadErrorUser  + $this->VirementErrorUser + $this->PhoneCreditErrorUser  +  $this->DepositErrorUser +  $this->InterErrorUser + $this->PaymentErrorUser +   $this->TransactionErrorUser,
                'sum_success' => $this->ReloadSuccessUser  + $this->VirementSuccessUser + $this->PhoneCreditSuccessUser  +  $this->DepositSuccessUser +  $this->InterSuccessUser + $this->PaymentSuccessUser +   $this->TransactionSuccessUser,
                'pending_success' => $this->ReloadPenddingUser  + $this->VirementPenddingUser + $this->PhoneCreditPenddingUser +  $this->DepositPenddingUser +  $this->InterPenddingUser + $this->PaymentPenddingUser +   $this->TransactionPenddingUser,
                'wallet_Banks_last' => WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.updated_by')->limit(3)->select('wallet_banks.*')->orderBy('wallet_banks.created_at', 'desc')->get(),
                'Wallet_Deposits_last' => WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_deposits.updated_by')->limit(3)->select('wallet_deposits.*')->orderBy('wallet_deposits.created_at', 'desc')->get(),
                'Wallet_Inters_last' => WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
                    ->where('wallet_inters.country_id', Auth::guard('web')->user()->country->id)->where('wallet_inters.updated_by')->limit(3)->select('wallet_inters.*')->orderBy('wallet_inters.created_at', 'desc')->get(),
                'Wallet_Payments_last' => WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_payments.updated_by')->limit(3)->select('wallet_payments.*')->orderBy('wallet_payments.created_at', 'desc')->get(),
                'Wallet_Transactions_last' => WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.updated_by')->limit(3)->select('wallet_transactions.*')->orderBy('created_at', 'desc')->get(),
                'Wallet_PhoneCredits_last' => WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_phone_credits.updated_by')->limit(3)->select('wallet_phone_credits.*')->orderBy('wallet_phone_credits.created_at', 'desc')->get(),
                'Wallet_Reloads_last' => WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_reloads.updated_by')->limit(3)->select('wallet_reloads.*')->orderBy('wallet_reloads.created_at', 'desc')->get(),
                'usersCountry' =>  $walletUser->country->currencie->code ?? null,
                'sum_bank' => $this->WalletBankSumUser,
                'sum_deposit' => $this->WalletDepositSumUser,
                'sum_payment' => $this->WalletPaymentSumUser,
                'sum_reload' => $this->WalletReloadSumUser,
                'sum_transaction' => $this->WalletTransactionSumUser,
                'sum_inter' => $this->WalletInterSumUser,
                'sum_phoneCredit' => $this->WalletPhoneCreditSumUser,
                'sum_bank_gain' => $this->WalletBankSumGainUser,
                'sum_deposit_gain' => $this->WalletDepositSumGainUser,
                'sum_payment_gain' => $this->WalletPaymentSumGainUser,
                'sum_reload_gain' => $this->WalletReloadSumGainUser,
                'sum_transaction_gain' => $this->WalletTransactionSumGainUser,
                'sum_inter_gain' => $this->WalletInterSumGainUser,
                'sum_phoneCredit_gain' => $this->WalletInterSumGainUser,
                'sum_phoneCredit_gain' => $this->WalletInterSumGainUser,
                'sum_phoneCredit_gain' => $this->WalletInterSumGainUser,

            ]);
        } else if ($walletUser->wallet->sidUser->is_distributer == true || $walletUser->wallet->sidUser->is_admin == true) {

            return view('livewire.statistics', [

                'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
                'sum_error' => $this->ReloadError  + $this->VirementError + $this->PhoneCreditError  +  $this->DepositError +  $this->InterError + $this->PaymentError +   $this->TransactionError,
                'sum_success' => $this->ReloadSuccess  + $this->VirementSuccess + $this->PhoneCreditSuccess  +  $this->DepositSuccess +  $this->InterSuccess + $this->PaymentSuccess +   $this->TransactionSuccess,
                'pending_success' => $this->ReloadPendding  + $this->VirementPendding + $this->PhoneCreditPendding  +  $this->DepositPendding +  $this->InterPendding + $this->PaymentPendding +   $this->TransactionPendding,
                'wallet_Banks_last' => WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->limit(3)->select('wallet_banks.*')->orderBy('wallet_banks.created_at', 'desc')->get(),
                'Wallet_Deposits_last' => WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->limit(3)->select('wallet_deposits.*')->orderBy('wallet_deposits.created_at', 'desc')->get(),
                'Wallet_Inters_last' => WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
                    ->where('wallet_inters.country_id', Auth::guard('web')->user()->country->id)->limit(3)->select('wallet_inters.*')->orderBy('wallet_inters.created_at', 'desc')->get(),
                'Wallet_Payments_last' => WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->limit(3)->select('wallet_payments.*')->orderBy('wallet_payments.created_at', 'desc')->get(),
                'Wallet_Transactions_last' => WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->limit(3)->select('wallet_transactions.*')->orderBy('created_at', 'desc')->get(),
                'Wallet_PhoneCredits_last' => WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->limit(3)->select('wallet_phone_credits.*')->orderBy('wallet_phone_credits.created_at', 'desc')->get(),
                'Wallet_Reloads_last' => WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->limit(3)->select('wallet_reloads.*')->orderBy('wallet_reloads.created_at', 'desc')->get(),
                'usersCountry' =>  $walletUser->country->currencie->name,
                'sum_bank' => $this->WalletBankSum,
                'sum_deposit' => $this->WalletDepositSum,
                'sum_payment' => $this->WalletPaymentSum,
                'sum_reload' => $this->WalletReloadSum,
                'sum_transaction' => $this->WalletTransactionSum,
                'sum_inter' => $this->WalletInterSum,
                'sum_phoneCredit' => $this->WalletPhoneCreditSum,
                'sum_bank_gain' => $this->WalletBankSumGain,
                'sum_deposit_gain' => $this->WalletDepositSumGain,
                'sum_payment_gain' => $this->WalletPaymentSumGain,
                'sum_reload_gain' => $this->WalletReloadSumGain,
                'sum_transaction_gain' => $this->WalletTransactionSumGain,
                'sum_inter_gain' => $this->WalletInterSumGain,
                'sum_phoneCredit_gain' => $this->WalletInterSumGain,
                'sum_phoneCredit_gain' => $this->WalletInterSumGain,
                'sum_phoneCredit_gain' => $this->WalletInterSumGain,

            ]);
        }
    }


    //   State gains Admin
    public function getWalletBankSumGainProperty()
    {

        $data = [];
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->whereDate('wallet_banks.created_at', Carbon::today())->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletDepositSumGainProperty()
    {

        $data = [];
        foreach (WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->whereDate('wallet_deposits.created_at', Carbon::today())->where('wallet_deposits.status', 'SUCCESS')->select('wallet_deposits.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }


    public function getWalletPaymentSumGainProperty()
    {

        $data = [];
        foreach (WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->whereDate('wallet_payments.created_at', Carbon::today())
            ->where('wallet_payments.status', 'SUCCESS')->select('wallet_payments.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletTransactionSumGainProperty()
    {

        $data = [];
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->whereDate('wallet_transactions.created_at', Carbon::today())->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletInterSumGainProperty()
    {

        $data = [];
        foreach (WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallet_inters.country_id', Auth::guard('web')->user()->country->id)->whereDate('wallet_inters.created_at', Carbon::today())->where('wallet_inters.status', 'SUCCESS')->select('wallet_inters.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);;
    }
    public function getWalletPhoneCreditSumGainProperty()
    {

        $data = [];
        foreach (WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->whereDate('wallet_phone_credits.created_at', Carbon::today())->where('wallet_phone_credits.status', 'SUCCESS')->select('wallet_phone_credits.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletReloadSumGainProperty()
    {

        $data = [];
        foreach (WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->whereDate('wallet_reloads.created_at', Carbon::today())->where('wallet_reloads.status', 'SUCCESS')->select('wallet_reloads.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    //   State gains User
    public function getWalletBankSumGainUserProperty()
    {

        $data = [];
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->whereDate('wallet_banks.created_at', Carbon::today())
            ->where('wallet_banks.created_by', Auth::guard('web')->user()->id)
            ->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletDepositSumGainUserProperty()
    {

        $data = [];
        foreach (WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->whereDate('wallet_deposits.created_at', Carbon::today())
            ->where('wallet_deposits.created_by', Auth::guard('web')->user()->id)
            ->where('wallet_deposits.status', 'SUCCESS')->select('wallet_deposits.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }


    public function getWalletPaymentSumGainUserProperty()
    {

        $data = [];
        foreach (WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->whereDate('wallet_payments.created_at', Carbon::today())
            ->where('wallet_payments.created_by', Auth::guard('web')->user()->id)
            ->where('wallet_payments.status', 'SUCCESS')->select('wallet_payments.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletTransactionSumGainUserProperty()
    {

        $data = [];
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->whereDate('wallet_transactions.created_at', Carbon::today())
            ->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)
            ->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletInterSumGainUserProperty()
    {

        $data = [];
        foreach (WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallet_inters.country_id', Auth::guard('web')->user()->country->id)
            ->whereDate('wallet_inters.created_at', Carbon::today())
            ->where('wallet_inters.created_by', Auth::guard('web')->user()->id)
            ->where('wallet_inters.status', 'SUCCESS')->select('wallet_inters.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);;
    }

    public function getWalletPhoneCreditSumGainUserProperty()
    {

        $data = [];
        foreach (WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->whereDate('wallet_phone_credits.created_at', Carbon::today())
            ->where('wallet_phone_credits.created_by', Auth::guard('web')->user()->id)
            ->where('wallet_phone_credits.status', 'SUCCESS')->select('wallet_phone_credits.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletReloadSumGainUserProperty()
    {

        $data = [];
        foreach (WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->whereDate('wallet_reloads.created_at', Carbon::today())
            ->where('wallet_reloads.created_by', Auth::guard('web')->user()->id)
            ->where('wallet_reloads.status', 'SUCCESS')->select('wallet_reloads.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    // States Todays
    // Sum Admin
    public function getWalletBankSumProperty()
    {

        $data = [];
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->whereDate('wallet_banks.created_at', Carbon::today())->select('wallet_banks.*')->where('wallet_banks.status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletDepositSumProperty()
    {

        $data = [];

        foreach (WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->select('wallet_deposits.*')->whereDate('wallet_deposits.created_at', Carbon::today())->where('wallet_deposits.status', 'SUCCESS')->get() as $deposit) {
            $data[] = $deposit->amount;
        }

        return array_sum($data);
    }


    public function getWalletPaymentSumProperty()
    {

        $data = [];

        foreach (WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_payments.*')->whereDate('wallet_payments.created_at', Carbon::today())
            ->where('wallet_payments.status', 'SUCCESS')->get() as $payment) {
            $data[] = $payment->amount;
        }

        return array_sum($data);
    }

    public function getWalletTransactionSumProperty()
    {

        $data = [];

        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->select('wallet_transactions.*')->whereDate('wallet_transactions.created_at', Carbon::today())->where('wallet_transactions.status', 'SUCCESS')->get() as $transaction) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }

    public function getWalletInterSumProperty()
    {

        $data = [];

        foreach (WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallet_inters.country_id', Auth::guard('web')->user()->country->id)->select('wallet_inters.*')->whereDate('wallet_inters.created_at', Carbon::today())->where('wallet_inters.status', 'SUCCESS')->get() as $inter) {
            $data[] = $inter->amount;
        }

        return array_sum($data);
    }
    public function getWalletPhoneCreditSumProperty()
    {

        $data = [];


        foreach (WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->select('wallet_phone_credits.*')->whereDate('wallet_phone_credits.created_at', Carbon::today())->where('wallet_phone_credits.status', 'SUCCESS')->get() as $phone) {
            $data[] = $phone->amount;
        }

        return array_sum($data);
    }

    public function getWalletReloadSumProperty()
    {

        $data = [];

        foreach (WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->select('wallet_reloads.*')->whereDate('wallet_reloads.created_at', Carbon::today())->where('wallet_reloads.status', 'SUCCESS')->get() as $reload) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }

    // Sum User
    public function getWalletBankSumUserProperty()
    {

        $data = [];
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->where('wallet_banks.created_by', Auth::guard('web')->user()->id)
            ->whereDate('wallet_banks.created_at', Carbon::today())->select('wallet_banks.*')
            ->where('wallet_banks.status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletDepositSumUserProperty()
    {

        $data = [];

        foreach (WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id',  Auth::guard('web')->user()->country->id)->where('wallet_deposits.created_by',  Auth::guard('web')->user()->id)
            ->whereDate('wallet_deposits.created_at', Carbon::today())->select('wallet_deposits.*')->where('wallet_deposits.status', 'SUCCESS')->get() as $deposit) {
            $data[] = $deposit->amount;
        }

        return array_sum($data);
    }


    public function getWalletPaymentSumUserProperty()
    {

        $data = [];

        foreach (WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id',  Auth::guard('web')->user()->country->id)
            ->where('wallet_payments.created_by',  Auth::guard('web')->user()->id)
            ->whereDate('wallet_payments.created_at', Carbon::today())->select('wallet_payments.*')->where('wallet_payments.status', 'SUCCESS')->get() as $payment) {
            $data[] = $payment->amount;
        }

        return array_sum($data);
    }

    public function getWalletTransactionSumUserProperty()
    {

        $data = [];

        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id',  Auth::guard('web')->user()->country->id)
            ->select('wallet_transactions.*')
            ->where('wallet_transactions.created_by',  Auth::guard('web')->user()->id)
            ->whereDate('wallet_transactions.created_at', Carbon::today())->where('wallet_transactions.status', 'SUCCESS')->get() as $transaction) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }

    public function getWalletInterSumUserProperty()
    {

        $data = [];

        foreach (WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallet_inters.country_id',  Auth::guard('web')->user()->country->id)
            ->where('wallet_inters.created_by',  Auth::guard('web')->user()->id)
            ->select('wallet_inters.*')
            ->whereDate('wallet_inters.created_at', Carbon::today())->where('wallet_inters.status', 'SUCCESS')->get() as $inter) {
            $data[] = $inter->amount;
        }

        return array_sum($data);
    }

    public function getWalletPhoneCreditSumUserProperty()
    {

        $data = [];


        foreach (WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->where('wallet_phone_credits.created_by', Auth::guard('web')->user()->id)
            ->select('wallet_phone_credits.*')
            ->whereDate('wallet_phone_credits.created_at', Carbon::today())
            ->where('wallet_phone_credits.status', 'SUCCESS')->get() as $phone) {
            $data[] = $phone->amount;
        }

        return array_sum($data);
    }

    public function getWalletReloadSumUserProperty()
    {

        $data = [];

        foreach (WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id',  Auth::guard('web')->user()->country->id)
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.created_by',  Auth::guard('web')->user()->id)
            ->whereDate('wallet_reloads.created_at', Carbon::today())->where('wallet_reloads.status', 'SUCCESS')->get() as $reload) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }


    // Details Transaction User
    public function getTransactionErrorUserProperty()
    {
        $transactionError = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_transactions.*')
            ->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'ERROR')->whereDate('wallet_transactions.created_at', Carbon::today())
            ->count();
        return $transactionError;
    }

    public function getTransactionSuccessUserProperty()
    {

        $transactionSuccess = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_transactions.*')
            ->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->whereDate('wallet_transactions.created_at', Carbon::today())
            ->count();
        return $transactionSuccess;
    }

    public function getTransactionPenddingUserProperty()
    {

        $transactionPendding = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_transactions.*')
            ->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'PENDING')->whereDate('wallet_transactions.created_at', Carbon::today())
            ->count();
        return $transactionPendding;
    }

    public function getPaymentErrorUserProperty()
    {

        $paimentError = WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'ERROR')->where('wallet_payments.created_by', Auth::guard('web')->user()->id)->whereDate('wallet_payments.created_at', Carbon::today())
            ->count();
        return $paimentError;
    }

    public function getPaymentSuccessUserProperty()
    {

        $paimentSuccess = WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'SUCCESS')->where('wallet_payments.created_by', Auth::guard('web')->user()->id)->whereDate('wallet_payments.created_at', Carbon::today())
            ->count();
        return $paimentSuccess;
    }

    public function getPaymentPenddingUserProperty()
    {

        $paimentPendding = WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'PENDING')->where('wallet_payments.created_by', Auth::guard('web')->user()->id)->whereDate('wallet_payments.created_at', Carbon::today())
            ->count();
        return $paimentPendding;
    }


    public function getReloadErrorUserProperty()
    {

        $reloadError = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'ERROR')->where('wallet_reloads.created_by', Auth::guard('web')->user()->id)->whereDate('wallet_reloads.created_at', Carbon::today())
            ->count();
        return $reloadError;
    }

    public function getReloadSuccessUserProperty()
    {


        $reloadSuccess = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'SUCCESS')->where('wallet_reloads.created_by', Auth::guard('web')->user()->id)->whereDate('wallet_reloads.created_at', Carbon::today())
            ->count();
        return $reloadSuccess;
    }

    public function getReloadPenddingUserProperty()
    {

        $reloadPendding = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'PENDING')->where('wallet_reloads.created_by', Auth::guard('web')->user()->id)->whereDate('wallet_reloads.created_at', Carbon::today())
            ->count();
        return $reloadPendding;
    }

    public function getVirementSuccessUserProperty()
    {

        $virementSuccess = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_banks.*')
            ->where('wallet_banks.created_by', Auth::guard('web')->user()->id)
            ->where('wallet_banks.status', 'SUCCESS')->whereDate('wallet_banks.created_at', Carbon::today())
            ->count();
        return $virementSuccess;
    }

    public function getVirementPenddingUserProperty()
    {

        $virementPendding = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_banks.*')
            ->where('wallet_banks.created_by', Auth::guard('web')->user()->id)
            ->where('wallet_banks.status', 'PENDING')->whereDate('wallet_banks.created_at', Carbon::today())
            ->count();
        return $virementPendding;
    }

    public function getVirementErrorUserProperty()
    {

        $virementError = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_banks.*')
            ->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'ERROR')->whereDate('wallet_banks.created_at', Carbon::today())
            ->count();
        return $virementError;
    }

    public function getInterErrorUserProperty()
    {

        $interError = WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallet_inters.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_inters.*')
            ->where('wallet_inters.created_by', Auth::guard('web')->user()->id)->where('wallet_inters.status', 'ERROR')->whereDate('wallet_inters.created_at', Carbon::today())
            ->count();
        return $interError;
    }

    public function getInterSuccessUserProperty()
    {

        $interSuccess = WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallet_inters.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_inters.*')
            ->where('wallet_inters.created_by', Auth::guard('web')->user()->id)->where('wallet_inters.status', 'SUCCESS')->whereDate('wallet_inters.created_at', Carbon::today())
            ->count();
        return $interSuccess;
    }

    public function getInterPenddingUserProperty()
    {

        $interPendding = WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallet_inters.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_inters.*')
            ->where('wallet_inters.created_by', Auth::guard('web')->user()->id)->where('wallet_inters.status', 'PENDING')->whereDate('wallet_inters.created_at', Carbon::today())
            ->count();
        return $interPendding;
    }

    public function getPhoneCreditErrorUserProperty()
    {

        $phoneCreditError = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.created_by', Auth::guard('web')->user()->id)->where('wallet_phone_credits.status', 'ERROR')->whereDate('wallet_phone_credits.created_at', Carbon::today())
            ->count();
        return $phoneCreditError;
    }

    public function getPhoneCreditSuccessUserProperty()
    {

        $phoneCreditSuccess = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.created_by', Auth::guard('web')->user()->id)->where('wallet_phone_credits.status', 'SUCCESS')->whereDate('wallet_phone_credits.created_at', Carbon::today())
            ->count();
        return $phoneCreditSuccess;
    }

    public function getPhoneCreditPenddingUserProperty()
    {

        $phoneCreditPendding = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.created_by', Auth::guard('web')->user()->id)->where('wallet_phone_credits.status', 'PENDING')->whereDate('wallet_phone_credits.created_at', Carbon::today())
            ->count();
        return $phoneCreditPendding;
    }

    public function getDepositErrorUserProperty()
    {

        $depositError = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.created_by', Auth::guard('web')->user()->id)->where('wallet_deposits.status', 'ERROR')->whereDate('wallet_deposits.created_at', Carbon::today())
            ->count();
        return $depositError;
    }

    public function getDepositSuccessUserProperty()
    {

        $depositSuccess = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.created_by', Auth::guard('web')->user()->id)->where('wallet_deposits.status', 'SUCCESS')->whereDate('wallet_deposits.created_at', Carbon::today())
            ->count();
        return $depositSuccess;
    }

    public function getDepositPenddingUserProperty()
    {

        $depositPendding = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.created_by', Auth::guard('web')->user()->id)->where('wallet_deposits.status', 'PENDING')->whereDate('wallet_deposits.created_at', Carbon::today())
            ->count();
        return $depositPendding;
    }
    // Details Transaction
    public function getTransactionErrorProperty()
    {

        $transactionError = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_transactions.*')
            ->where('wallet_transactions.status', 'ERROR')->whereDate('wallet_transactions.created_at', Carbon::today())
            ->count();
        return $transactionError;
    }

    public function getTransactionSuccessProperty()
    {

        $transactionSuccess = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_transactions.*')
            ->where('wallet_transactions.status', 'SUCCESS')->whereDate('wallet_transactions.created_at', Carbon::today())
            ->count();
        return $transactionSuccess;
    }

    public function getTransactionPenddingProperty()
    {

        $transactionPendding = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_transactions.*')
            ->where('wallet_transactions.status', 'PENDING')->whereDate('wallet_transactions.created_at', Carbon::today())
            ->count();
        return $transactionPendding;
    }

    public function getPaymentErrorProperty()
    {

        $paimentError = WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'ERROR')->whereDate('wallet_payments.created_at', Carbon::today())
            ->count();
        return $paimentError;
    }

    public function getPaymentSuccessProperty()
    {

        $paimentSuccess = WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'SUCCESS')->whereDate('wallet_payments.created_at', Carbon::today())
            ->count();
        return $paimentSuccess;
    }

    public function getPaymentPenddingProperty()
    {

        $paimentPendding = WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'PENDING')->whereDate('wallet_payments.created_at', Carbon::today())
            ->count();
        return $paimentPendding;
    }


    public function getReloadErrorProperty()
    {

        $reloadError = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'ERROR')->whereDate('wallet_reloads.created_at', Carbon::today())
            ->count();
        return $reloadError;
    }

    public function getReloadSuccessProperty()
    {


        $reloadSuccess = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'SUCCESS')->whereDate('wallet_reloads.created_at', Carbon::today())
            ->count();
        return $reloadSuccess;
    }

    public function getReloadPenddingProperty()
    {

        $reloadPendding = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'PENDING')->whereDate('wallet_reloads.created_at', Carbon::today())
            ->count();
        return $reloadPendding;
    }

    public function getVirementSuccessProperty()
    {

        $virementSuccess = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_banks.*')
            ->where('wallet_banks.status', 'SUCCESS')->whereDate('wallet_banks.created_at', Carbon::today())
            ->count();
        return $virementSuccess;
    }

    public function getVirementPenddingProperty()
    {

        $virementPendding = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_banks.*')
            ->where('wallet_banks.status', 'PENDING')->whereDate('wallet_banks.created_at', Carbon::today())
            ->count();
        return $virementPendding;
    }

    public function getVirementErrorProperty()
    {

        $interError = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_banks.*')
            ->where('wallet_banks.status', 'ERROR')->whereDate('wallet_banks.created_at', Carbon::today())
            ->count();
        return $interError;
    }

    public function getInterErrorProperty()
    {

        $interError = WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallet_inters.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_inters.*')
            ->where('wallet_inters.status', 'ERROR')->whereDate('wallet_inters.created_at', Carbon::today())
            ->count();
        return $interError;
    }

    public function getInterSuccessProperty()
    {

        $interSuccess = WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallet_inters.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_inters.*')
            ->where('wallet_inters.status', 'SUCCESS')->whereDate('wallet_inters.created_at', Carbon::today())
            ->count();
        return $interSuccess;
    }

    public function getInterPenddingProperty()
    {
        $interPendding = WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallet_inters.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_inters.*')
            ->where('wallet_inters.status', 'PENDING')->whereDate('wallet_inters.created_at', Carbon::today())
            ->count();
        return $interPendding;
    }

    public function getPhoneCreditErrorProperty()
    {
        $phoneCreditError = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.status', 'ERROR')->whereDate('wallet_phone_credits.created_at', Carbon::today())
            ->count();
        return $phoneCreditError;
    }

    public function getPhoneCreditSuccessProperty()
    {
        $phoneCreditSuccess = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.status', 'SUCCESS')->whereDate('wallet_phone_credits.created_at', Carbon::today())
            ->count();
        return $phoneCreditSuccess;
    }

    public function getPhoneCreditPenddingProperty()
    {

        $phoneCreditPendding = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.status', 'PENDING')->whereDate('wallet_phone_credits.created_at', Carbon::today())
            ->count();
        return $phoneCreditPendding;
    }

    public function getDepositErrorProperty()
    {


        $depositError = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.status', 'ERROR')->whereDate('wallet_deposits.created_at', Carbon::today())
            ->count();
        return $depositError;
    }

    public function getDepositSuccessProperty()
    {

        $depositSuccess = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.status', 'SUCCESS')->whereDate('wallet_deposits.created_at', Carbon::today())
            ->count();
        return $depositSuccess;
    }

    public function getDepositPenddingProperty()
    {

        $depositPendding = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.status', 'PENDING')->whereDate('wallet_deposits.created_at', Carbon::today())
            ->count();
        return $depositPendding;
    }
}