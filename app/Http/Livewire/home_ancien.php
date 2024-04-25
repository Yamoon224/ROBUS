<?php

namespace App\Http\Livewire;

use App\Models\CloseApp;
use App\Models\Payments\Deposit;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletTransaction;
use App\Models\Wallets\WalletWave;
use App\Models\Wallets\WalletXbet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HomeRobusAncien extends Component
{
    public $startDate = '';
    public $endDate = '';

    public function render()
    {
        $walletUser = sabdy();

        $currencie = sabdy()->wallet->currencie->name;

        // Account valeurs transactions
        $payment_error = $this->getTransactionCount(WalletPayment::class, 'created_at', 'status', 'ERROR');
        $payment_padding = $this->getTransactionCount(WalletPayment::class, 'created_at', 'status', 'PENDING');
        $payment_success = $this->getTransactionCount(WalletPayment::class, 'created_at', 'status', 'SUCCESS');

        $transaction_error = $this->getTransactionCount(WalletTransaction::class, 'created_at', 'status', 'ERROR');
        $transaction_padding = $this->getTransactionCount(WalletTransaction::class, 'created_at', 'status', 'PENDING');
        $transaction_success = $this->getTransactionCount(WalletTransaction::class,  'created_at', 'status', 'SUCCESS');

        $bank_error = $this->getTransactionCount(WalletBank::class, 'created_at', 'status', 'ERROR');
        $bank_padding = $this->getTransactionCount(WalletBank::class, 'created_at', 'status', 'PENDING');
        $bank_success = $this->getTransactionCount(WalletBank::class, 'created_at', 'status', 'SUCCESS');

        $deposit_error = $this->getTransactionCount(WalletDeposit::class, 'created_at', 'status', 'ERROR');
        $deposit_padding = $this->getTransactionCount(WalletDeposit::class, 'created_at', 'status', 'PENDING');
        $deposit_success = $this->getTransactionCount(WalletDeposit::class, 'created_at', 'status', 'SUCCESS');

        $reload_error = $this->getTransactionCountReload(WalletReload::class);
        $reload_padding = $this->getTransactionCountReload(WalletReload::class);
        $reload_success = $this->getTransactionCountReload(WalletReload::class);

        $phone_credit_error = $this->getTransactionCount(WalletPhoneCredit::class, 'created_at', 'status', 'ERROR');
        $phone_credit_padding = $this->getTransactionCount(WalletPhoneCredit::class, 'created_at', 'status', 'PENDING');
        $phone_credit_success = $this->getTransactionCount(WalletPhoneCredit::class, 'created_at', 'status', 'SUCCESS');

        $inter_error = $this->getTransactionCount(WalletInter::class, 'created_at', 'status', 'ERROR');
        $inter_padding = $this->getTransactionCount(WalletInter::class, 'created_at', 'status', 'PENDING');
        $inter_success = $this->getTransactionCount(WalletInter::class, 'created_at', 'status', 'SUCCESS');

        $xbet_error = $this->getTransactionCount(WalletXbet::class, 'created_at', 'status', 'ERROR');
        $xbet_padding = $this->getTransactionCount(WalletXbet::class, 'created_at', 'status', 'PENDING');
        $xbet_success = $this->getTransactionCount(WalletXbet::class, 'created_at', 'status', 'SUCCESS');


        $sum_error =   $payment_error +  $transaction_error  + $bank_error +  $deposit_error  +  $phone_credit_error + $inter_error +  $xbet_error + $reload_error;

        $sum_pending =   $payment_padding +  $transaction_padding  + $bank_padding +  $deposit_padding  +  $phone_credit_padding + $inter_padding +  $xbet_padding + $reload_padding;

        $sum_success =   $payment_success +  $transaction_success  + $bank_success +  $deposit_success  +  $phone_credit_success + $inter_success +  $xbet_success + $reload_success;

        // Amount transactions days
        $payement_amount = $this->getTodayTransactionAmountTotal(WalletPayment::class, 'created_at',  'amount');
        $transaction_amount = $this->getTodayTransactionAmountTotal(WalletTransaction::class, 'created_at',  'amount');
        $reload_amount = $this->getTodayReloadAmountTotal(WalletReload::class);
        $phonecredit_amount = $this->getTodayTransactionAmountTotal(WalletPhoneCredit::class, 'created_at',  'amount');
        $inter_amount = $this->getTodayTransactionAmountTotal(WalletInter::class, 'created_at',  'amount');
        $bank_amount = $this->getTodayTransactionAmountTotal(WalletBank::class, 'created_at',  'amount');
        $deposit_amount = $this->getTodayTransactionAmountTotal(WalletDeposit::class, 'created_at',  'amount');
        $xbet_amount = $this->getTodayTransactionAmountTotal(WalletXbet::class, 'created_at',  'amount');

        $sum_days = $payement_amount + $transaction_amount + $phonecredit_amount + $inter_amount + $bank_amount +  $deposit_amount + $xbet_amount + $reload_amount;

        // Amount transactions month
        $payment_amount_month = $this->getDaysTransaction(WalletPayment::class,  'created_at',  'status',  'amount');
        $transaction_amount_month = $this->getDaysTransaction(WalletTransaction::class,  'created_at',  'status',  'amount');
        $reload_amount_month = $this->getDaysReloadAmountTotal(WalletReload::class);
        $phonecredit_amount_month = $this->getDaysTransaction(WalletPhoneCredit::class,  'created_at',  'status',  'amount');
        $inter_amount_month = $this->getDaysTransaction(WalletInter::class,  'created_at',  'status',  'amount');
        $bank_amount_month = $this->getDaysTransaction(WalletBank::class,  'created_at',  'status',  'amount');
        $deposit_amount_month = $this->getDaysTransaction(WalletDeposit::class,  'created_at',  'status',  'amount');
        $xbet_amount_month = $this->getDaysTransaction(WalletXbet::class,  'created_at',  'status',  'amount');

        // count One transactions
        $transaction_amount_days = $this->getTransactionAllCount(WalletTransaction::class,  'created_at');
        $reload_amount_days = $this->getTransactionAllCount(WalletReload::class,  'created_at');
        $payment_amount_days = $this->getTransactionAllCount(WalletPayment::class, 'created_at');
        $phone_amount_days = $this->getTransactionAllCount(WalletPhoneCredit::class,  'created_at');
        $inter_amount_days = $this->getTransactionAllCount(WalletInter::class,  'created_at');
        $bank_amount_days = $this->getTransactionAllCount(WalletBank::class, 'created_at');
        $deposit_amount_days = $this->getTransactionAllCount(WalletDeposit::class,  'created_at');
        $xbet_amount_days = $this->getTransactionAllCount(WalletXbet::class, 'created_at');

        // Get Users
        $custumers = $this->getUserTypeTotal('is_custumers', true);
        $underDistributors = $this->getUserTypeTotal('is_under_distributer', true);
        $masters = $this->getUserTypeTotal('is_master', true);
        $supervisors = $this->getUserTypeTotal('is_supervisor', true);
        $distributors = $this->getUserTypeTotal('is_distributer', true);
        $companies = $this->getUserTypeTotal('is_company', true);
        $comzones = $this->getUserTypeTotal('is_com_zone', true);


        if ($walletUser->is_under_distributer == true) {
            abort(403);
        } else {

            return view('livewire.home', [

                'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),

                'sum_error' => $sum_error,
                'sum_success' => $sum_success,
                'pending_success' => $sum_pending,

                'sum_amount' => $sum_days,

                'usersCountry' => $currencie,

                'sum_bank' => $bank_amount,
                'sum_deposit' => $deposit_amount,
                'sum_payment' => $payement_amount,
                'sum_reload' => $reload_amount,
                'sum_transaction' => $transaction_amount,
                'sum_inter' => $inter_amount,
                'sum_phoneCredit' => $phonecredit_amount,
                'sum_xbet' => $xbet_amount,

                'sum_bank_month' => $bank_amount_month,
                'sum_deposit_month' => $deposit_amount_month,
                'sum_payment_month' => $payment_amount_month,
                'sum_reload_month' => $reload_amount_month,
                'sum_transaction_month' => $transaction_amount_month,
                'sum_inter_month' =>  $inter_amount_month,
                'sum_phoneCredit_month' => $phonecredit_amount_month,
                'sum_xbet_month' => $xbet_amount_month,

                // Transaction days
                'account_bank_days' => $bank_amount_days,
                'account_deposit_days' => $deposit_amount_days,
                'account_payment_days' => $payment_amount_days,
                'account_reload_days' => $reload_amount_days,
                'account_transaction_days' => $transaction_amount_days,
                'account_inter_days' => $inter_amount_days,
                'account_phoneCredit_days' => $phone_amount_days,
                'account_xbet_days' => $xbet_amount_days,

                // Users

                'is_masters' => $masters,
                'is_custumers' => $custumers,
                'is_distributors' => $distributors,
                'is_underdistributors' => $underDistributors,
                'is_supervisors' => $supervisors,
                'is_compagnies' => $companies,
                'is_comzones' => $comzones,

            ]);
        }
    }



    public function getTransactionAllCount($walletModel, $transaction_days)
    {


        return $walletModel::whereDate($transaction_days, Carbon::today())
            ->count();
    }


    public function getTransactionCountReload($walletModel)
    {


        $totalCount = $walletModel::where('wallet_id', sabdy()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereDate('created_at', Carbon::today())
            ->count();

        return $totalCount;
    }


    public function getTodayReloadAmountTotal($walletModel)
    {


        $totalAmount = $walletModel::where('wallet_id',  sabdy()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        return $totalAmount;
    }

    public function getDaysReloadAmountTotal($walletModel)
    {


        $totalAmount = $walletModel::where('wallet_id',  sabdy()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereMonth(DB::raw('created_at'), now()->month)
            ->sum('amount');

        return $totalAmount;
    }


    // count valeurs
    public function getTransactionCount($walletModel,  $transaction_days, $wallet_status, $status)
    {

        return $walletModel::whereDate($transaction_days, Carbon::today())
            ->where($wallet_status, $status)
            ->count();
    }

    // Sum amount days valeurs
    public function getTodayTransactionAmountTotal($walletModel, $transaction_days, $wallet_amount)
    {

        return $walletModel::whereDate($transaction_days, Carbon::today())
            ->where(function ($query) {
                $id_user = sabdy()->id;
                if (sabdy()->is_distributer) {
                    $query->where('updated_by', '=', $id_user);
                } else {

                    $query->where('updated_by', '!=', $id_user)
                        ->orWhere('updated_by', '=', $id_user);
                }
            })
            ->sum($wallet_amount);
    }


    public function getUserTypeTotal($user, $status)
    {

        $totalUsers = SidUser::where($user,  $status)->count();

        return $totalUsers;
    }


    // Sum amount month valeurs


    public function getDaysTransaction($walletModel, $transaction_days, $transaction_status, $wallet_amount)
    {

        return $walletModel::whereMonth($transaction_days, now()->month)
            ->where($transaction_status, 'SUCCESS')
            ->where(function ($query) {
                if (sabdy()->is_distributer) {
                    $query->where('updated_by', '=', sabdy()->id);
                } else {

                    $query->where('updated_by', '!=', sabdy()->id)
                        ->orWhere('updated_by', '=', sabdy()->id);
                }
            })
            ->sum($wallet_amount);
    }
}
