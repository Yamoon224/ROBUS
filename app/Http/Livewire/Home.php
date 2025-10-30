<?php

namespace App\Http\Livewire;

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
use Livewire\Component;
use App\Http\Services\HomeService;


class Home extends Component
{
    public $startDate = '';
    public $endDate = '';


    public function render()
    {
        $walletUser = sabdy();

        $currencie = sabdy()->wallet->currencie->name;

        // Account valeurs transactions
        $payment_error = app(HomeService::class)->getTransactionCount(WalletPayment::class, 'ERROR', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by', 'wallet_payments.status');
        $payment_padding = app(HomeService::class)->getTransactionCount(WalletPayment::class, 'PENDING', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by', 'wallet_payments.status');
        $payment_success = app(HomeService::class)->getTransactionCount(WalletPayment::class, 'SUCCESS', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by', 'wallet_payments.status');

        $transaction_error = app(HomeService::class)->getTransactionCount(WalletTransaction::class, 'ERROR', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');
        $transaction_padding = app(HomeService::class)->getTransactionCount(WalletTransaction::class, 'PENDING', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');
        $transaction_success = app(HomeService::class)->getTransactionCount(WalletTransaction::class, 'SUCCESS', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');

        $bank_error = app(HomeService::class)->getTransactionCount(WalletBank::class, 'ERROR', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by', 'wallet_banks.status');
        $bank_padding = app(HomeService::class)->getTransactionCount(WalletBank::class, 'PENDING', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by', 'wallet_banks.status');
        $bank_success = app(HomeService::class)->getTransactionCount(WalletBank::class, 'SUCCESS', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by', 'wallet_banks.status');

        $deposit_error = app(HomeService::class)->getTransactionCount(WalletDeposit::class, 'ERROR', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by', 'wallet_deposits.status');
        $deposit_padding = app(HomeService::class)->getTransactionCount(WalletDeposit::class, 'PENDING', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by', 'wallet_deposits.status');
        $deposit_success = app(HomeService::class)->getTransactionCount(WalletDeposit::class, 'SUCCESS', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by', 'wallet_deposits.status');

        $reload_error = app(HomeService::class)->getTransactionCountReload(WalletReload::class);
        $reload_padding = app(HomeService::class)->getTransactionCountReload(WalletReload::class);
        $reload_success = app(HomeService::class)->getTransactionCountReload(WalletReload::class);

        $phone_credit_error = app(HomeService::class)->getTransactionCount(WalletPhoneCredit::class, 'ERROR',  'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by', 'wallet_phone_credits.status');
        $phone_credit_padding = app(HomeService::class)->getTransactionCount(WalletPhoneCredit::class, 'PENDING', 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by', 'wallet_phone_credits.status');
        $phone_credit_success = app(HomeService::class)->getTransactionCount(WalletPhoneCredit::class, 'SUCCESS',  'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by', 'wallet_phone_credits.status');

        $inter_error = app(HomeService::class)->getTransactionCount(WalletInter::class, 'ERROR',  'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by', 'wallet_inters.status');
        $inter_padding = app(HomeService::class)->getTransactionCount(WalletInter::class, 'PENDING', 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by', 'wallet_inters.status');
        $inter_success = app(HomeService::class)->getTransactionCount(WalletInter::class, 'SUCCESS',  'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by', 'wallet_inters.status');

        $xbet_error = app(HomeService::class)->getTransactionCount(WalletXbet::class, 'ERROR',  'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by', 'wallet_xbets.status');
        $xbet_padding = app(HomeService::class)->getTransactionCount(WalletXbet::class, 'PENDING', 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by', 'wallet_xbets.status');
        $xbet_success = app(HomeService::class)->getTransactionCount(WalletXbet::class, 'SUCCESS',  'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by', 'wallet_xbets.status');

        // $wave_error = $this->getTransactionCount(WalletWave::class, 'ERROR',  'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by', 'wallet_waves.status');
        // $wave_padding = $this->getTransactionCount(WalletWave::class, 'PENDING', 'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by', 'wallet_waves.status');
        // $wave_success = $this->getTransactionCount(WalletWave::class, 'SUCCESS',  'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by', 'wallet_waves.status');

        // Amount transactions days
        $payement_amount = app(HomeService::class)->getTodayTransactionAmountTotal(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount');
        $payement_amount_cie = app(HomeService::class)->getTodayTransactionProvidersAmountTotal(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount', '8');
        $payement_amount_canal = app(HomeService::class)->getTodayTransactionProvidersAmountTotal(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount', '7');
        $payement_amount_sodeci = app(HomeService::class)->getTodayTransactionProvidersAmountTotal(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount', '10');
        $transaction_amount = app(HomeService::class)->getTodayTransactionAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount');
        $transaction_amount_robus = app(HomeService::class)->getTodayTransactionProviderAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount', '9');
        $transaction_amount_moov = app(HomeService::class)->getTodayTransactionProviderAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount', '3');
        $transaction_amount_wave = app(HomeService::class)->getTodayTransactionProviderAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount', '6');
        $transaction_amount_orange = app(HomeService::class)->getTodayTransactionProviderAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount', '4');
        $transaction_amount_mtn = app(HomeService::class)->getTodayTransactionProviderAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount', '1');
        $reload_amount = app(HomeService::class)->getTodayReloadAmountTotal(WalletReload::class);
        $phonecredit_amount = app(HomeService::class)->getTodayTransactionAmountTotal(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by',  'wallet_phone_credits.status',  'wallet_phone_credits.amount');
        $inter_amount = app(HomeService::class)->getTodayTransactionProviderTypeOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount_received');

        $inter_amount_moneyRobus = app(HomeService::class)->getTodayTransactionProviderOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount_received', 1);
        $inter_amount_cie = app(HomeService::class)->getTodayTransactionProviderOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount_received', 5);
        $inter_amount_credit = app(HomeService::class)->getTodayTransactionProviderOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount_received', 6);
        $inter_amount_virement = app(HomeService::class)->getTodayTransactionProviderOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount_received', 7);
        $inter_amount_rechargement = app(HomeService::class)->getTodayTransactionProviderOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount_received', 8);

        $bank_amount = app(HomeService::class)->getTodayTransactionAmountTotal(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by',  'wallet_banks.status',  'wallet_banks.amount');
        $deposit_amount = app(HomeService::class)->getTodayTransactionAmountTotal(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by',  'wallet_deposits.status',  'wallet_deposits.amount');
        $xbet_amount = app(HomeService::class)->getTodayTransactionAmountTotal(WalletXbet::class, 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by',  'wallet_xbets.status',  'wallet_xbets.amount');
        // $wave_amount = app(HomeService::class)->getTodayTransactionAmountTotal(WalletWave::class, 'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by',  'wallet_waves.status',  'wallet_waves.amount');

        // Amount transactions month
        $payment_amount_month = app(HomeService::class)->getDaysTransaction(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount');
        $transaction_amount_month = app(HomeService::class)->getDaysTransaction(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount');
        $reload_amount_month = app(HomeService::class)->getDaysReloadAmountTotal(WalletReload::class);
        $phonecredit_amount_month = app(HomeService::class)->getDaysTransaction(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by',  'wallet_phone_credits.status',  'wallet_phone_credits.amount');
        $inter_amount_month = app(HomeService::class)->getDaysTransaction(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount_received');
        $bank_amount_month = app(HomeService::class)->getDaysTransaction(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by',  'wallet_banks.status',  'wallet_banks.amount');
        $deposit_amount_month = app(HomeService::class)->getDaysTransaction(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by',  'wallet_deposits.status',  'wallet_deposits.amount');
        $xbet_amount_month = app(HomeService::class)->getDaysTransaction(WalletXbet::class, 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by',  'wallet_xbets.status',  'wallet_xbets.amount');
        // $wave_amount_month = app(HomeService::class)->getDaysTransaction(WalletWave::class, 'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by',  'wallet_waves.status',  'wallet_waves.amount');

        // count One transactions
        $transaction_amount_days = app(HomeService::class)->getTransactionAllCount(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by');
        $reload_amount_days = app(HomeService::class)->getTransactionAllCount(WalletReload::class, 'wallet_reloads.wallet_id', 'wallet_reloads.created_at', 'wallet_reloads.updated_by');
        $payment_amount_days = app(HomeService::class)->getTransactionAllCount(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by');
        $phone_amount_days = app(HomeService::class)->getTransactionAllCount(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by');
        $inter_amount_days = app(HomeService::class)->getTransactionAllCount(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by');
        $bank_amount_days = app(HomeService::class)->getTransactionAllCount(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by');
        $deposit_amount_days = app(HomeService::class)->getTransactionAllCount(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by');
        $xbet_amount_days = app(HomeService::class)->getTransactionAllCount(WalletXbet::class, 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by');
        // $wave_amount_days = app(HomeService::class)->getTransactionAllCount(WalletWave::class, 'wallet_waves.wallet_id', 'wallet_waves.created_at');
        // $closeRobus = CloseApp::orderBy('id', 'asc')->get();
        // Get Users
        $custumers = app(HomeService::class)->getUserTypeTotal('is_custumers', true);
        $underDistributors = app(HomeService::class)->getUserTypeTotal('is_under_distributer', true);
        // $masters = app(HomeService::class)->getUserTypeTotal('is_master', true);
        $supervisors = app(HomeService::class)->getUserTypeTotal('is_supervisor', true);
        $distributors = app(HomeService::class)->getUserTypeTotal('is_distributer', true);
        $companies = app(HomeService::class)->getUserTypeTotal('is_company', true);
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
            // 'open_apps' => $closeRobus,
        ]);
    }


    public function getUserTypeTotal($user, $status)
    {

        $totalUsers = SidUser::where($user,  $status)->count();

        return $totalUsers;
    }


  
}
