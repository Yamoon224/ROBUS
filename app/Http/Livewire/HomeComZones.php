<?php

namespace App\Http\Livewire;

use App\Models\AppCloseCom;
use App\Models\CloseApp;
use App\Models\Sid\SidUser;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HomeComZones extends Component
{


    public $startDate = '';
    public $endDate = '';

    public function render()
    {
        $currencie = comzone()->wallet->currencie->name;

        // Account valeurs transactions
        $payment_error = $this->getTransactionCount(WalletPayment::class, 'ERROR', 'wallet_payments.wallet_id', 'wallet_payments.status');
        $payment_padding = $this->getTransactionCount(WalletPayment::class, 'PENDING', 'wallet_payments.wallet_id', 'wallet_payments.status');
        $payment_success = $this->getTransactionCount(WalletPayment::class, 'SUCCESS', 'wallet_payments.wallet_id', 'wallet_payments.status');

        $transaction_error = $this->getTransactionCount(WalletTransaction::class, 'ERROR', 'wallet_transactions.wallet_id', 'wallet_transactions.status');
        $transaction_padding = $this->getTransactionCount(WalletTransaction::class, 'PENDING', 'wallet_transactions.wallet_id', 'wallet_transactions.status');
        $transaction_success = $this->getTransactionCount(WalletTransaction::class, 'SUCCESS', 'wallet_transactions.wallet_id', 'wallet_transactions.status');

        $bank_error = $this->getTransactionCount(WalletBank::class, 'ERROR', 'wallet_banks.wallet_id', 'wallet_banks.status');
        $bank_padding = $this->getTransactionCount(WalletBank::class, 'PENDING', 'wallet_banks.wallet_id', 'wallet_banks.status');
        $bank_success = $this->getTransactionCount(WalletBank::class, 'SUCCESS', 'wallet_banks.wallet_id', 'wallet_banks.status');

        $deposit_error = $this->getTransactionCount(WalletDeposit::class, 'ERROR', 'wallet_deposits.wallet_id', 'wallet_deposits.status');
        $deposit_padding = $this->getTransactionCount(WalletDeposit::class, 'PENDING', 'wallet_deposits.wallet_id', 'wallet_deposits.status');
        $deposit_success = $this->getTransactionCount(WalletDeposit::class, 'SUCCESS', 'wallet_deposits.wallet_id', 'wallet_deposits.status');

        $reload_error = $this->getTransactionCountReload(WalletReload::class);
        $reload_padding = $this->getTransactionCountReload(WalletReload::class);
        $reload_success = $this->getTransactionCountReload(WalletReload::class);

        $phone_credit_error = $this->getTransactionCount(WalletPhoneCredit::class, 'ERROR',  'wallet_phone_credits.wallet_id', 'wallet_phone_credits.status');
        $phone_credit_padding = $this->getTransactionCount(WalletPhoneCredit::class, 'PENDING', 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.status');
        $phone_credit_success = $this->getTransactionCount(WalletPhoneCredit::class, 'SUCCESS',  'wallet_phone_credits.wallet_id', 'wallet_phone_credits.status');

        $inter_error = $this->getTransactionCount(WalletInter::class, 'ERROR',  'wallet_inters.wallet_id', 'wallet_inters.status');
        $inter_padding = $this->getTransactionCount(WalletInter::class, 'PENDING', 'wallet_inters.wallet_id', 'wallet_inters.status');
        $inter_success = $this->getTransactionCount(WalletInter::class, 'SUCCESS',  'wallet_inters.wallet_id', 'wallet_inters.status');


        // Amount transactions days
        $payement_amount = $this->getTodayTransactionAmountTotal(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at',  'wallet_payments.status',  'wallet_payments.amount');
        $transaction_amount = $this->getTodayTransactionAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at',  'wallet_transactions.status',  'wallet_transactions.amount');
        $reload_amount = $this->getTodayReloadAmountTotal(WalletReload::class);
        $phonecredit_amount = $this->getTodayTransactionAmountTotal(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at',  'wallet_phone_credits.status',  'wallet_phone_credits.amount');
        $inter_amount = $this->getTodayTransactionAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at',  'wallet_inters.status',  'wallet_inters.amount');
        $bank_amount = $this->getTodayTransactionAmountTotal(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_at',  'wallet_banks.status',  'wallet_banks.amount');
        $deposit_amount = $this->getTodayTransactionAmountTotal(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_at',  'wallet_deposits.status',  'wallet_deposits.amount');

        // Amount transactions month
        $payement_amount_month = $this->getDaysTransaction(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at',  'wallet_payments.status',  'wallet_payments.amount');
        $transaction_amount_month = $this->getDaysTransaction(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at',  'wallet_transactions.status',  'wallet_transactions.amount');
        $reload_amount_month = $this->getDaysReloadAmountTotal(WalletReload::class);
        $phonecredit_amount_month = $this->getDaysTransaction(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at',  'wallet_phone_credits.status',  'wallet_phone_credits.amount');
        $inter_amount_month = $this->getDaysTransaction(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at',  'wallet_inters.status',  'wallet_inters.amount');
        $bank_amount_month = $this->getDaysTransaction(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_at',  'wallet_banks.status',  'wallet_banks.amount');
        $deposit_amount_month = $this->getDaysTransaction(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_at',  'wallet_deposits.status',  'wallet_deposits.amount');
        $closeRobus = AppCloseCom::orderBy('id', 'asc')->get();

        // Get Users
        $custumers = $this->getUserTypeTotal('is_custumers', true);
        $underDistributors = $this->getUserTypeTotal('is_under_distributer', true);
        $masters = $this->getUserTypeTotal('is_master', true);
        $supervisors = $this->getUserTypeTotal('is_supervisor', true);
        $distributors = $this->getUserTypeTotal('is_distributer', true);
        $companies = $this->getUserTypeTotal('is_company', true);
        $comzones = $this->getUserTypeTotal('is_com_zone', true);

        return view(
            'livewire.home-com-zones',
            [

                // Users

                'is_masters' => $masters,
                'is_custumers' => $custumers,
                'is_distributors' => $distributors,
                'is_underdistributors' => $underDistributors,
                'is_supervisors' => $supervisors,
                'is_compagnies' => $companies,
                'is_comzones' => $comzones,

                'user_currencie' => $currencie,
                'error_payment' => $payment_error,
                'success_payment' => $payment_success,
                'pendding_payment' => $payment_padding,
                // 
                'error_transaction' => $transaction_error,
                'success_transaction' => $transaction_success,
                'pendding_transaction' => $transaction_padding,
                // 
                'error_bank' => $bank_error,
                'success_bank' => $bank_success,
                'pendding_bank' => $bank_padding,
                // 
                'error_deposit' => $deposit_error,
                'success_deposit' => $deposit_success,
                'pendding_deposit' => $deposit_padding,

                // 
                'error_reload' => $reload_error,
                'success_reload' => $reload_success,
                'pendding_reload' => $reload_padding,
                // 
                'error_phone_credit' => $phone_credit_error,
                'success_phone_credit' => $phone_credit_success,
                'pendding_phone_credit' => $phone_credit_padding,

                'error_inter' => $inter_error,
                'success_inter' => $inter_success,
                'pendding_inter' => $inter_padding,

                // 
                'amount_payment_days' => $payement_amount,
                'amount_transaction_days' => $transaction_amount,
                'amount_reload_days' => $reload_amount,
                'amount_phone_credit_days' => $phonecredit_amount,
                'amount_inter_days' => $inter_amount,
                'amount_bank_days' => $bank_amount,
                'amount_deposit_days' => $deposit_amount,
                // 
                'amount_payment_month' => $payement_amount_month,
                'amount_transaction_month' => $transaction_amount_month,
                'amount_reload_month' => $reload_amount_month,
                'amount_phone_credit_month' => $phonecredit_amount_month,
                'amount_inter_month' => $inter_amount_month,
                'amount_bank_month' => $bank_amount_month,
                'amount_deposit_month' => $deposit_amount_month,
                'open_apps' => $closeRobus,

            ]
        );
    }


    public function getUserTypeTotal($user , $status)
    {

        $totalUsers = SidUser::where('country_id' , sabdy()->country->id)->where($user,  $status)->count();

        return $totalUsers;
    }

    public function getDaysTransaction($walletModel, $wallet_transaction, $transaction_days, $transaction_status, $wallet_amount)
    {

        return $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->where('wallets.country_id', comzone()->country->id)
            ->whereMonth($transaction_days, now()->month)
            ->where($transaction_status, 'SUCCESS')
            ->sum($wallet_amount);
    }



    public function getTransactionCount($walletModel, $status, $wallet_transactions, $wallet_status)
    {

        return $walletModel::join(
            'wallets',
            $wallet_transactions,
            'wallets.id'
        )
            ->where('wallets.country_id', comzone()->country->id)
            ->where($wallet_status, $status)
            ->count();
    }


    public function getTransactionCountReload($walletModel)
    {


        $totalCount = $walletModel::where('wallet_id', comzone()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereDate('created_at', Carbon::today())
            ->count();

        return $totalCount;
    }


    public function getTodayTransactionAmountTotal($walletModel, $wallet_transaction, $transaction_days, $transaction_status, $wallet_amount)
    {


        return $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->where('wallets.country_id', comzone()->country->id)
            ->whereDate($transaction_days, Carbon::today())
            ->where($transaction_status, 'SUCCESS')
            ->sum($wallet_amount);
    }




    public function getTodayReloadAmountTotal($walletModel)
    {


        $totalAmount = $walletModel::where('wallet_id',  comzone()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        return $totalAmount;
    }


    public function getDaysReloadAmountTotal($walletModel)
    {


        $totalAmount = $walletModel::where('wallet_id',  comzone()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereMonth(DB::raw('created_at'), now()->month)
            ->sum('amount');

        return $totalAmount;
    }
}
