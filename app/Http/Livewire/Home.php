<?php

namespace App\Http\Livewire;

use App\Models\CloseApp;
use App\Models\Custumers\Country;
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

        $currencie = sabdy()->wallet->currencie->name;

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

        // $wave_error = $this->getTransactionCount(WalletWave::class, 'ERROR',  'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by', 'wallet_waves.status');
        // $wave_padding = $this->getTransactionCount(WalletWave::class, 'PENDING', 'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by', 'wallet_waves.status');
        // $wave_success = $this->getTransactionCount(WalletWave::class, 'SUCCESS',  'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by', 'wallet_waves.status');

        // Amount transactions days
        $payement_amount = $this->getTodayTransactionAmountTotal(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount');
        $payement_amount_cie = $this->getTodayTransactionProvidersAmountTotal(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount', '8');
        $payement_amount_canal = $this->getTodayTransactionProvidersAmountTotal(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount', '7');
        $payement_amount_sodeci = $this->getTodayTransactionProvidersAmountTotal(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount', '10');
        $transaction_amount = $this->getTodayTransactionAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount');
        $transaction_amount_robus = $this->getTodayTransactionProviderAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount', '9');
        $transaction_amount_moov = $this->getTodayTransactionProviderAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount', '3');
        $transaction_amount_wave = $this->getTodayTransactionProviderAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount', '6');
        $transaction_amount_orange = $this->getTodayTransactionProviderAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount', '4');
        $transaction_amount_mtn = $this->getTodayTransactionProviderAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount', '1');
        $reload_amount = $this->getTodayReloadAmountTotal(WalletReload::class);
        $phonecredit_amount = $this->getTodayTransactionAmountTotal(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by',  'wallet_phone_credits.status',  'wallet_phone_credits.amount');
        $inter_amount = $this->getTodayTransactionProviderTypeOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount_received');

        $inter_amount_moneyRobus = $this->getTodayTransactionProviderOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount_received', 1);
        $inter_amount_cie = $this->getTodayTransactionProviderOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount_received', 5);
        $inter_amount_credit = $this->getTodayTransactionProviderOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount_received', 6);
        $inter_amount_virement = $this->getTodayTransactionProviderOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount_received', 7);
        $inter_amount_rechargement = $this->getTodayTransactionProviderOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount_received', 8);

        $bank_amount = $this->getTodayTransactionAmountTotal(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by',  'wallet_banks.status',  'wallet_banks.amount');
        $deposit_amount = $this->getTodayTransactionAmountTotal(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by',  'wallet_deposits.status',  'wallet_deposits.amount');
        $xbet_amount = $this->getTodayTransactionAmountTotal(WalletXbet::class, 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by',  'wallet_xbets.status',  'wallet_xbets.amount');
        // $wave_amount = $this->getTodayTransactionAmountTotal(WalletWave::class, 'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by',  'wallet_waves.status',  'wallet_waves.amount');

        // Amount transactions month
        $payment_amount_month = $this->getDaysTransaction(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount');
        $transaction_amount_month = $this->getDaysTransaction(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount');
        $reload_amount_month = $this->getDaysReloadAmountTotal(WalletReload::class);
        $phonecredit_amount_month = $this->getDaysTransaction(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by',  'wallet_phone_credits.status',  'wallet_phone_credits.amount');
        $inter_amount_month = $this->getDaysTransaction(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount_received');
        $bank_amount_month = $this->getDaysTransaction(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by',  'wallet_banks.status',  'wallet_banks.amount');
        $deposit_amount_month = $this->getDaysTransaction(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by',  'wallet_deposits.status',  'wallet_deposits.amount');
        $xbet_amount_month = $this->getDaysTransaction(WalletXbet::class, 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by',  'wallet_xbets.status',  'wallet_xbets.amount');
        // $wave_amount_month = $this->getDaysTransaction(WalletWave::class, 'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by',  'wallet_waves.status',  'wallet_waves.amount');

        // count One transactions
        $transaction_amount_days = $this->getTransactionAllCount(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by');
        $reload_amount_days = $this->getTransactionAllCount(WalletReload::class, 'wallet_reloads.wallet_id', 'wallet_reloads.created_at', 'wallet_reloads.updated_by');
        $payment_amount_days = $this->getTransactionAllCount(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by');
        $phone_amount_days = $this->getTransactionAllCount(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by');
        $inter_amount_days = $this->getTransactionAllCount(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by');
        $bank_amount_days = $this->getTransactionAllCount(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by');
        $deposit_amount_days = $this->getTransactionAllCount(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by');
        $xbet_amount_days = $this->getTransactionAllCount(WalletXbet::class, 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by');
        // $wave_amount_days = $this->getTransactionAllCount(WalletWave::class, 'wallet_waves.wallet_id', 'wallet_waves.created_at');
        // $closeRobus = CloseApp::orderBy('id', 'asc')->get();
        // Get Users
        $custumers = $this->getUserTypeTotal('is_custumers', true);
        $underDistributors = $this->getUserTypeTotal('is_under_distributer', true);
        // $masters = $this->getUserTypeTotal('is_master', true);
        $supervisors = $this->getUserTypeTotal('is_supervisor', true);
        $distributors = $this->getUserTypeTotal('is_distributer', true);
        $companies = $this->getUserTypeTotal('is_company', true);
        // $comzones = $this->getUserTypeTotal('is_com_zone', true);

        return view('livewire.home', [

            // Users

            // 'is_masters' => $masters,
            'is_custumers' => $custumers,
            'is_distributors' => $distributors,
            'is_underdistributors' => $underDistributors,
            'is_supervisors' => $supervisors,
            'is_compagnies' => $companies,
            // 'is_comzones' => $comzones,

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
            'pendding_xbet' => $xbet_padding,

            // 'error_wave' => $wave_error,
            // 'success_wave' => $wave_success,
            // 'pendding_wave' => $wave_padding,

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
            'amount_payment_cie_days' => $payement_amount_cie,
            'amount_payment_canal_days' => $payement_amount_canal,
            'amount_payment_sodeci_days' => $payement_amount_sodeci,
            'amount_transaction_days' => $transaction_amount,
            'amount_transaction_robus_days' => $transaction_amount_robus,
            'amount_transaction_moov_days' => $transaction_amount_moov,
            'amount_transaction_wave_days' => $transaction_amount_wave,
            'amount_transaction_orange_days' => $transaction_amount_orange,
            'amount_transaction_mtn_days' => $transaction_amount_mtn,
            'amount_reload_days' => $reload_amount,
            'amount_phone_credit_days' => $phonecredit_amount,
            'amount_inter_days' => $inter_amount,
            // Inters
            'amount_inter_moneyRobus' => $inter_amount_moneyRobus,
            'amount_inter_cie' => $inter_amount_cie,
            'amount_inter_credit' => $inter_amount_credit,
            'amount_inter_virement' => $inter_amount_virement,
            'amount_inter_rechargement' => $inter_amount_rechargement,
            'amount_bank_days' => $bank_amount,
            'amount_deposit_days' => $deposit_amount,
            'amount_xbet_days' => $xbet_amount,
            // 'amount_wave_days' => $wave_amount,


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
            // 'sum_wave' => $wave_amount,
            'sum_bank_month' => $bank_amount_month,
            'sum_deposit_month' => $deposit_amount_month,
            'sum_payment_month' => $payment_amount_month,
            'sum_reload_month' => $reload_amount_month,
            'sum_transaction_month' => $transaction_amount_month,
            'sum_inter_month' => $inter_amount_month,
            'sum_phoneCredit_month' => $phonecredit_amount_month,
            'sum_xbet_month' => $xbet_amount_month,
            // 'sum_wave_month' => $wave_amount_month,
            // Transaction days
            'account_bank_days' => $bank_amount_days,
            'account_deposit_days' => $deposit_amount_days,
            'account_payment_days' => $payment_amount_days,
            'account_reload_days' => $reload_amount_days,
            'account_transaction_days' => $transaction_amount_days,
            'account_inter_days' => $inter_amount_days,
            'account_phoneCredit_days' => $phone_amount_days,
            'account_xbet_days' => $xbet_amount_days,
            // 'account_wave_days' => $wave_amount_days,
            // 'open_apps' => $closeRobus,
        ]);
    }


    public function getUserTypeTotal($user, $status)
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


    public function getTransactionAllCount($walletModel, $wallet_transactions, $transaction_days, $walletMy)
    {

        $countryId = sabdy()->country->id;

        $query =  $walletModel::join(
            'wallets',
            $wallet_transactions,
            'wallets.id'
        )
            ->whereDate($transaction_days, Carbon::today());

        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transactions) {
                $query->where($wallet_transactions, sabdy()->wallet->id);
            });
        } else {

            $query->where('wallets.country_id', '!=', $countryId)
                ->orWhere('wallets.country_id', '=', $countryId);
        }


        return $query->count();
    }


    // count valeurs
    public function getTransactionCount($walletModel, $status, $wallet_transactions, $transaction_days, $walletMy, $wallet_status)
    {

        $query = $walletModel::join('wallets', $wallet_transactions, 'wallets.id')
            ->whereDate($transaction_days, Carbon::today())
            ->where($wallet_status, $status);

        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transactions) {
                $query->where($wallet_transactions, sabdy()->wallet->id);
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

        return $query->count();
    }

    // Sum amount days valeurs
    public function getTodayTransactionAmountTotal($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount)
    {

        $query = $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereDate($transaction_days, Carbon::today())
            ->where($transaction_status, 'SUCCESS');


        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, sabdy()->wallet->id);
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



        return $query->sum($wallet_amount);
    }

    public function getTodayTransactionProvidersAmountTotal($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount, $providers)
    {

        $query = $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereDate($transaction_days, Carbon::today())
            ->where('provider_id', $providers)
            ->where($transaction_status, 'SUCCESS');


        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, sabdy()->wallet->id);
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



        return $query->sum($wallet_amount);
    }

    public function getTodayTransactionProviderAmountTotal($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount, $providerId)
    {

        $query = $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereDate($transaction_days, Carbon::today())
            ->where('provider_id', $providerId)
            ->where($transaction_status, 'SUCCESS');


        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, sabdy()->wallet->id);
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



        return $query->sum($wallet_amount);
    }

    public function getTodayTransactionProviderTypeOneAmountTotal($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount)
    {

        $query = $walletModel::join('wallets', 'wallets.id', '=', $wallet_transaction)
            ->join('countries', 'wallets.country_id', '=', 'countries.id')
            ->whereDate($transaction_days, Carbon::today()) 
            ->where($transaction_status, 'SUCCESS'); 
    
        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, sabdy()->wallet->id);
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
    
        $results = $query->get();
    

        $totalAmountConv = 0;
    
        foreach ($results as $result) {
            
            $countryRobus = $result->country;

            $wallet_amount = (float)$result->amount_received;

            if (sabdy()->country->currencie->value > $countryRobus->currencie->value) {
                $wallet_amount_conv = $wallet_amount * sabdy()->country->currencie->value;
            } elseif (sabdy()->country->currencie->value == $countryRobus->currencie->value) {
                $wallet_amount_conv = $wallet_amount;
            } elseif (sabdy()->country->currencie->value < $countryRobus->currencie->value) {
                $wallet_amount_conv = $wallet_amount * sabdy()->country->currencie->value;
            }
    
            $totalAmountConv += $wallet_amount_conv;
        }
    
        return $totalAmountConv;
    }

    public function getTodayTransactionProviderOneAmountTotal($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount, $providerId)
    {

        $query = $walletModel::join('wallets', $wallet_transaction,  'wallets.id')
            ->whereDate($transaction_days, Carbon::today()) 
            ->whereProviderType($providerId)
            ->where($transaction_status, 'SUCCESS'); 
    
        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, sabdy()->wallet->id);
            });
        } else {
            // Cas où ce n'est ni un distributeur ni un sous-distributeur
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
    
        return $query->sum($wallet_amount);
    }
    

    // Sum amount month valeurs
    public function getDaysTransaction($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount)
    {

        $query = $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereMonth($transaction_days, now()->month)
            ->where($transaction_status, 'SUCCESS');

        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id);
        } elseif (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, sabdy()->wallet->id);
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

        return $query->sum($wallet_amount);
    }
}
