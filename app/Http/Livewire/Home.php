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
use App\Models\Wallets\WalletXbet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Home extends Component
{
    public $startDate = '';
    public $endDate = '';

    
    public function render()
    {
        $walletUser = sabdy();

        $currencie = comzone()->wallet->currencie->name;

        // Account valeurs transactions
        $payment_error = $this->getTransactionCount(WalletPayment::class, 'ERROR', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by', 'wallet_payments.status');
        $payment_padding = $this->getTransactionCount(WalletPayment::class, 'PENDING', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by', 'wallet_payments.status');
        $payment_success = $this->getTransactionCount(WalletPayment::class, 'SUCCESS', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by', 'wallet_payments.status');

        $transaction_error = $this->getTransactionCount(WalletTransaction::class, 'ERROR', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');
        $transaction_padding = $this->getTransactionCount(WalletTransaction::class, 'PENDING', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');
        $transaction_success = $this->getTransactionCount(WalletTransaction::class, 'SUCCESS', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');

        $bank_error = $this->getTransactionCount(WalletBank::class, 'ERROR', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by', 'wallet_banks.status');
        $bank_padding = $this->getTransactionCount(WalletBank::class, 'PENDING', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by', 'wallet_banks.status');
        $bank_success = $this->getTransactionCount(WalletBank::class, 'SUCCESS', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by', 'wallet_banks.status');

        $deposit_error = $this->getTransactionCount(WalletDeposit::class, 'ERROR', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by', 'wallet_deposits.status');
        $deposit_padding = $this->getTransactionCount(WalletDeposit::class, 'PENDING', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by', 'wallet_deposits.status');
        $deposit_success = $this->getTransactionCount(WalletDeposit::class, 'SUCCESS', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by', 'wallet_deposits.status');

        $reload_error = $this->getTransactionCountReload(WalletReload::class);
        $reload_padding = $this->getTransactionCountReload(WalletReload::class);
        $reload_success = $this->getTransactionCountReload(WalletReload::class);

        $phone_credit_error = $this->getTransactionCount(WalletPhoneCredit::class, 'ERROR',  'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by', 'wallet_phone_credits.status');
        $phone_credit_padding = $this->getTransactionCount(WalletPhoneCredit::class, 'PENDING', 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by', 'wallet_phone_credits.status');
        $phone_credit_success = $this->getTransactionCount(WalletPhoneCredit::class, 'SUCCESS',  'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by', 'wallet_phone_credits.status');

        $inter_error = $this->getTransactionCount(WalletInter::class, 'ERROR',  'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by', 'wallet_inters.status');
        $inter_padding = $this->getTransactionCount(WalletInter::class, 'PENDING', 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by', 'wallet_inters.status');
        $inter_success = $this->getTransactionCount(WalletInter::class, 'SUCCESS',  'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by', 'wallet_inters.status');

        $xbet_error = $this->getTransactionCount(WalletXbet::class, 'ERROR',  'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by', 'wallet_xbets.status');
        $xbet_padding = $this->getTransactionCount(WalletXbet::class, 'PENDING', 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by', 'wallet_xbets.status');
        $xbet_success = $this->getTransactionCount(WalletXbet::class, 'SUCCESS',  'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by', 'wallet_xbets.status');

        // Amount transactions days
        $payement_amount = $this->getTodayTransactionAmountTotal(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount');
        $transaction_amount = $this->getTodayTransactionAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount');
        $reload_amount = $this->getTodayReloadAmountTotal(WalletReload::class);
        $phonecredit_amount = $this->getTodayTransactionAmountTotal(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by',  'wallet_phone_credits.status',  'wallet_phone_credits.amount');
        $inter_amount = $this->getTodayTransactionAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount');
        $bank_amount = $this->getTodayTransactionAmountTotal(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by',  'wallet_banks.status',  'wallet_banks.amount');
        $deposit_amount = $this->getTodayTransactionAmountTotal(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by',  'wallet_deposits.status',  'wallet_deposits.amount');
        $xbet_amount = $this->getTodayTransactionAmountTotal(WalletXbet::class, 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by',  'wallet_xbets.status',  'wallet_xbets.amount');

        // Amount transactions month
        $payment_amount_month = $this->getDaysTransaction(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount');
        $transaction_amount_month = $this->getDaysTransaction(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount');
        $reload_amount_month = $this->getDaysReloadAmountTotal(WalletReload::class);
        $phonecredit_amount_month = $this->getDaysTransaction(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by',  'wallet_phone_credits.status',  'wallet_phone_credits.amount');
        $inter_amount_month = $this->getDaysTransaction(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount');
        $bank_amount_month = $this->getDaysTransaction(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by',  'wallet_banks.status',  'wallet_banks.amount');
        $deposit_amount_month = $this->getDaysTransaction(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by',  'wallet_deposits.status',  'wallet_deposits.amount');
        $xbet_amount_month = $this->getDaysTransaction(WalletXbet::class, 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by',  'wallet_xbets.status',  'wallet_xbets.amount');

        // count One transactions
        $transaction_amount_days = $this->getTransactionAllCount(WalletTransaction::class , 'wallet_transactions.wallet_id', 'wallet_transactions.created_at');
        $reload_amount_days = $this->getTransactionAllCount(WalletReload::class , 'wallet_reloads.wallet_id', 'wallet_reloads.created_at');
        $payment_amount_days = $this->getTransactionAllCount(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at');
        $phone_amount_days = $this->getTransactionAllCount(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at');
        $inter_amount_days = $this->getTransactionAllCount(WalletInter::class , 'wallet_inters.wallet_id', 'wallet_inters.created_at');
        $bank_amount_days = $this->getTransactionAllCount(WalletBank::class , 'wallet_banks.wallet_id', 'wallet_banks.created_at');
        $deposit_amount_days = $this->getTransactionAllCount(WalletDeposit::class , 'wallet_deposits.wallet_id', 'wallet_deposits.created_at');
        $xbet_amount_days = $this->getTransactionAllCount(WalletXbet::class , 'wallet_xbets.wallet_id', 'wallet_xbets.created_at');
        $closeRobus = CloseApp::orderBy('id' , 'asc')->get();
        // Get Users
        $custumers =$this->getUserTypeTotal('is_custumers' , true);
        $underDistributors =$this->getUserTypeTotal('is_under_distributer' , true);
        $masters =$this->getUserTypeTotal('is_master' , true);
        $supervisors =$this->getUserTypeTotal('is_supervisor' , true);
        $distributors =$this->getUserTypeTotal('is_distributer' , true);
        $companies =$this->getUserTypeTotal('is_company' , true);
        $comzones =$this->getUserTypeTotal('is_com_zone' , true);

  
        if ($walletUser->is_under_distributer == true) {
            abort(403);
        } else {

            return view('livewire.home', [

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
                'error_xbet' => $xbet_error,
                'success_xbet' => $xbet_success,
                'pendding_xbet' => $transaction_padding,
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
          

                'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
                'usersCountry' => $walletUser->country->currencie->name,
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
                'sum_inter_month' => $inter_amount_month,
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
                'open_apps' => $closeRobus,
            ]);
        }
    }


    public function getUserTypeTotal($user , $status)
    {

        $totalUsers = SidUser::where($user,  $status)->count();

        return $totalUsers;
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


    public function getTransactionCountReload($walletModel)
    {


        $totalCount = $walletModel::where('wallet_id', sabdy()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereDate('created_at', Carbon::today())
            ->count();

        return $totalCount;
    }


    public function getTransactionAllCount($walletModel, $wallet_transactions, $transaction_days)
    {


        return $walletModel::join(
            'wallets',
            $wallet_transactions,
            'wallets.id'
        )
            ->whereDate($transaction_days, Carbon::today())
            ->where(function ($query) {
                $countryId = sabdy()->country->id;
                if (sabdy()->is_distributer) {
                    $query->where('wallets.country_id', '=', sabdy()->id);
                } else {

                    $query->where('wallets.country_id', '!=', $countryId)
                        ->orWhere('wallets.country_id', '=', $countryId);
                }
            })
            ->count();
    }


    // count valeurs
    public function getTransactionCount($walletModel, $status, $wallet_transactions, $transaction_days, $walletMy, $wallet_status)
    {

        return $walletModel::join(
            'wallets',
            $wallet_transactions,
            'wallets.id'
        )
            ->whereDate($transaction_days, Carbon::today())
            ->where(function ($query) use ($walletMy) {
                if (sabdy()->is_distributer) {
                    $query->where($walletMy, '=', sabdy()->id);
                } else {
                    $query->where($walletMy, '=', sabdy()->id)->orWhere($walletMy, '!=', sabdy()->id);
                }
            })
            ->where($wallet_status, $status)
            ->where(function ($query) {
                $countryId = sabdy()->country->id;
                if (sabdy()->is_distributer) {
                    $query->where('wallets.country_id', '=', sabdy()->id);
                } else {

                    $query->where('wallets.country_id', '!=', $countryId)
                        ->orWhere('wallets.country_id', '=', $countryId);
                }
            })
            ->count();
    }

    // Sum amount days valeurs
    public function getTodayTransactionAmountTotal($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount)
    {

        return $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereDate($transaction_days, Carbon::today())
            ->where(function ($query) use ($walletMy) {
                if (sabdy()->is_distributer) {
                    $query->where($walletMy, '=', sabdy()->id);
                } else {
                    $query->where($walletMy, '=', sabdy()->id)->orWhere($walletMy, '!=', sabdy()->id);
                }
            })
            // ->where($transaction_status, 'SUCCESS')
            ->where(function ($query) {
                $countryId = sabdy()->country->id;
                if (sabdy()->is_distributer) {
                    $query->where('wallets.country_id', '=', sabdy()->id);
                } else {

                    $query->where('wallets.country_id', '!=', $countryId)
                        ->orWhere('wallets.country_id', '=', $countryId);
                }
            })
            ->sum($wallet_amount);
    }

    // Sum amount month valeurs
    public function getDaysTransaction($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount)
    {

        return $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereMonth($transaction_days, now()->month)
            ->where(function ($query) use ($walletMy) {
                if (sabdy()->is_distributer) {
                    $query->where($walletMy, '=', sabdy()->id);
                } else {
                    $query->where($walletMy, '=', sabdy()->id)->orWhere($walletMy, '!=', sabdy()->id);
                }
            })
            // ->where($transaction_status, 'SUCCESS')
            ->where(function ($query) {
                $countryId = sabdy()->country->id;
                if (sabdy()->is_distributer) {
                    $query->where('wallets.country_id', '=', sabdy()->id);
                } else {

                    $query->where('wallets.country_id', '!=', $countryId)
                        ->orWhere('wallets.country_id', '=', $countryId);
                }
            })
            ->sum($wallet_amount);
    }

   
}
