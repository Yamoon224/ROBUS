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
use App\Models\Wallets\WalletDjamo;
use App\Models\Wallets\WalletPush;
use App\Models\Wallets\WalletTresortMoney;
use App\Models\Wallets\WalletVersement;
use App\Models\Wallets\WalletAdmin;
use App\Models\Wallets\WalletCommission;
use App\Models\IntegrationClient;


class HomeProspects extends Component
{

    public $startDate = '';
    public $endDate = '';
    public $wallet_id;
    public $user_id;
    public $categorieId;
    public $employerDesabled;
    public $amount_payment;
    public $choose_type_payement_emp;
    public $wallet_id_employer;
    public $amount_payment_wallet;


    public function edit($id, $state)
    {
        $check = Wallet::whereId($id)->first();
        $this->user_id = $check->sidUser->id;
        $this->wallet_id = $check->wallet_id;
        $this->employerDesabled = $check->company_id;
        $this->categorieId = $check->sidUser->categorie_id;
        $this->emit($state);
    }

    public function render()
    {
        $walletUser = propect();

        $currencie = propect()->wallet->currencie->name;
        $integrationClient = IntegrationClient::where('sid_user_id', propect()->id)->first();

        // Account valeurs transactions
        $payment_error = app(HomeService::class)->getTransactionProspectCount(WalletPayment::class, 'ERROR', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.created_by', 'wallet_payments.status');
        $payment_padding = app(HomeService::class)->getTransactionProspectCount(WalletPayment::class, 'PENDING', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.created_by', 'wallet_payments.status');
        $payment_success = app(HomeService::class)->getTransactionProspectCount(WalletPayment::class, 'SUCCESS', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.created_by', 'wallet_payments.status');

        $transaction_error = app(HomeService::class)->getTransactionProspectCount(WalletTransaction::class, 'ERROR', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.created_by', 'wallet_transactions.status');
        $transaction_padding = app(HomeService::class)->getTransactionProspectCount(WalletTransaction::class, 'PENDING', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.created_by', 'wallet_transactions.status');
        $transaction_success = app(HomeService::class)->getTransactionProspectCount(WalletTransaction::class, 'SUCCESS', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.created_by', 'wallet_transactions.status');

        $bank_error = app(HomeService::class)->getTransactionProspectCount(WalletBank::class, 'ERROR', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.created_by', 'wallet_banks.status');
        $bank_padding = app(HomeService::class)->getTransactionProspectCount(WalletBank::class, 'PENDING', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.created_by', 'wallet_banks.status');
        $bank_success = app(HomeService::class)->getTransactionProspectCount(WalletBank::class, 'SUCCESS', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.created_by', 'wallet_banks.status');

        $deposit_error = app(HomeService::class)->getTransactionProspectCount(WalletDeposit::class, 'ERROR', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.created_by', 'wallet_deposits.status');
        $deposit_padding = app(HomeService::class)->getTransactionProspectCount(WalletDeposit::class, 'PENDING', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.created_by', 'wallet_deposits.status');
        $deposit_success = app(HomeService::class)->getTransactionProspectCount(WalletDeposit::class, 'SUCCESS', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.created_by', 'wallet_deposits.status');

        $reload_error = app(HomeService::class)->getTransactionCountProspectReload(WalletReload::class);
        $reload_padding = app(HomeService::class)->getTransactionCountProspectReload(WalletReload::class);
        $reload_success = app(HomeService::class)->getTransactionCountProspectReload(WalletReload::class);

        $phone_credit_error = app(HomeService::class)->getTransactionProspectCount(WalletPhoneCredit::class, 'ERROR',  'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.created_by', 'wallet_phone_credits.status');
        $phone_credit_padding = app(HomeService::class)->getTransactionProspectCount(WalletPhoneCredit::class, 'PENDING', 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.created_by', 'wallet_phone_credits.status');
        $phone_credit_success = app(HomeService::class)->getTransactionProspectCount(WalletPhoneCredit::class, 'SUCCESS',  'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.created_by', 'wallet_phone_credits.status');

        $inter_error = app(HomeService::class)->getTransactionProspectCount(WalletInter::class, 'ERROR',  'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.created_by', 'wallet_inters.status');
        $inter_padding = app(HomeService::class)->getTransactionProspectCount(WalletInter::class, 'PENDING', 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.created_by', 'wallet_inters.status');
        $inter_success = app(HomeService::class)->getTransactionProspectCount(WalletInter::class, 'SUCCESS',  'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.created_by', 'wallet_inters.status');

        $xbet_error = app(HomeService::class)->getTransactionProspectCount(WalletXbet::class, 'ERROR',  'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.created_by', 'wallet_xbets.status');
        $xbet_padding = app(HomeService::class)->getTransactionProspectCount(WalletXbet::class, 'PENDING', 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.created_by', 'wallet_xbets.status');
        $xbet_success = app(HomeService::class)->getTransactionProspectCount(WalletXbet::class, 'SUCCESS',  'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.created_by', 'wallet_xbets.status');

        // New transactions

        $djamo_error = app(HomeService::class)->getTransactionProspectCount(WalletDjamo::class, 'ERROR', 'wallet_djamos.wallet_id', 'wallet_djamos.created_at', 'wallet_djamos.created_by', 'wallet_djamos.status');
        $djamo_padding = app(HomeService::class)->getTransactionProspectCount(WalletDjamo::class, 'PENDING', 'wallet_djamos.wallet_id', 'wallet_djamos.created_at', 'wallet_djamos.created_by', 'wallet_djamos.status');
        $djamo_success = app(HomeService::class)->getTransactionProspectCount(WalletDjamo::class, 'SUCCESS', 'wallet_djamos.wallet_id', 'wallet_djamos.created_at', 'wallet_djamos.created_by', 'wallet_djamos.status');

        $versment_error = app(HomeService::class)->getTransactionProspectCount(WalletVersement::class, 'ERROR', 'wallet_versements.wallet_id', 'wallet_versements.created_at', 'wallet_versements.created_by', 'wallet_versements.status');
        $versment_padding = app(HomeService::class)->getTransactionProspectCount(WalletVersement::class, 'PENDING', 'wallet_versements.wallet_id', 'wallet_versements.created_at', 'wallet_versements.created_by', 'wallet_versements.status');
        $versment_success = app(HomeService::class)->getTransactionProspectCount(WalletVersement::class, 'SUCCESS', 'wallet_versements.wallet_id', 'wallet_versements.created_at', 'wallet_versements.created_by', 'wallet_versements.status');

        $tresortMoney_error = app(HomeService::class)->getTransactionProspectCount(WalletTresortMoney::class, 'ERROR', 'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_at', 'wallet_tresort_money.created_by', 'wallet_tresort_money.status');
        $tresortMoney_pendding = app(HomeService::class)->getTransactionProspectCount(WalletTresortMoney::class, 'PENDING', 'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_at', 'wallet_tresort_money.created_by', 'wallet_tresort_money.status');
        $tresortMoney_success = app(HomeService::class)->getTransactionProspectCount(WalletTresortMoney::class, 'SUCCESS', 'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_at', 'wallet_tresort_money.created_by', 'wallet_tresort_money.status');


        $push_error = app(HomeService::class)->getTransactionProspectCount(WalletPush::class, 'ERROR', 'wallet_pushes.wallet_id', 'wallet_pushes.created_at', 'wallet_pushes.created_by', 'wallet_pushes.status');
        $push_pendding = app(HomeService::class)->getTransactionProspectCount(WalletPush::class, 'PENDING', 'wallet_pushes.wallet_id', 'wallet_pushes.created_at', 'wallet_pushes.created_by', 'wallet_pushes.status');
        $push_success = app(HomeService::class)->getTransactionProspectCount(WalletPush::class, 'SUCCESS', 'wallet_pushes.wallet_id', 'wallet_pushes.created_at', 'wallet_pushes.created_by', 'wallet_pushes.status');


        // Amount transactions days
        $payement_amount = app(HomeService::class)->getTodayTransactionProspectAmountTotal(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.created_by',  'wallet_payments.status',  'wallet_payments.amount');
        $payement_amount_cie = app(HomeService::class)->getTodayTransactionProvidersProspectAmountTotal(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.created_by',  'wallet_payments.status',  'wallet_payments.amount', '8');
        $payement_amount_canal = app(HomeService::class)->getTodayTransactionProvidersProspectAmountTotal(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.created_by',  'wallet_payments.status',  'wallet_payments.amount', '7');
        $payement_amount_sodeci = app(HomeService::class)->getTodayTransactionProvidersProspectAmountTotal(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.created_by',  'wallet_payments.status',  'wallet_payments.amount', '10');
        $transaction_amount = app(HomeService::class)->getTodayTransactionProspectAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.created_by',  'wallet_transactions.status',  'wallet_transactions.amount');
        $transaction_amount_robus = app(HomeService::class)->getTodayTransactionProvidersProspectAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.created_by',  'wallet_transactions.status',  'wallet_transactions.amount', '9');
        $transaction_amount_moov = app(HomeService::class)->getTodayTransactionProvidersProspectAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.created_by',  'wallet_transactions.status',  'wallet_transactions.amount', '3');
        $transaction_amount_wave = app(HomeService::class)->getTodayTransactionProvidersProspectAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.created_by',  'wallet_transactions.status',  'wallet_transactions.amount', '6');
        $transaction_amount_orange = app(HomeService::class)->getTodayTransactionProvidersProspectAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.created_by',  'wallet_transactions.status',  'wallet_transactions.amount', '4');
        $transaction_amount_mtn = app(HomeService::class)->getTodayTransactionProvidersProspectAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.created_by',  'wallet_transactions.status',  'wallet_transactions.amount', '1');
        $reload_amount = app(HomeService::class)->getTodayReloadProspectAmountTotal(WalletReload::class);
        $phonecredit_amount = app(HomeService::class)->getTodayTransactionProspectAmountTotal(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.created_by',  'wallet_phone_credits.status',  'wallet_phone_credits.amount');
        $inter_amount = app(HomeService::class)->getTodayTransactionProviderTypeOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.created_by',  'wallet_inters.status',  'wallet_inters.amount_received');

        $inter_amount_moneyRobus = app(HomeService::class)->getTodayTransactionProviderOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.created_by',  'wallet_inters.status',  'wallet_inters.amount_received', 1);
        $inter_amount_cie = app(HomeService::class)->getTodayTransactionProviderOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.created_by',  'wallet_inters.status',  'wallet_inters.amount_received', 5);
        $inter_amount_credit = app(HomeService::class)->getTodayTransactionProviderOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.created_by',  'wallet_inters.status',  'wallet_inters.amount_received', 6);
        $inter_amount_virement = app(HomeService::class)->getTodayTransactionProviderOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.created_by',  'wallet_inters.status',  'wallet_inters.amount_received', 7);
        $inter_amount_rechargement = app(HomeService::class)->getTodayTransactionProviderOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.created_by',  'wallet_inters.status',  'wallet_inters.amount_received', 8);

        $bank_amount = app(HomeService::class)->getTodayTransactionProspectAmountTotal(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.created_by',  'wallet_banks.status',  'wallet_banks.amount');
        $deposit_amount = app(HomeService::class)->getTodayTransactionProspectAmountTotal(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.created_by',  'wallet_deposits.status',  'wallet_deposits.amount');
        $xbet_amount = app(HomeService::class)->getTodayTransactionProspectAmountTotal(WalletXbet::class, 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.created_by',  'wallet_xbets.status',  'wallet_xbets.amount');

        // new amount
        $djamo_amount = app(HomeService::class)->getTodayTransactionProspectAmountTotal(WalletDjamo::class, 'wallet_djamos.wallet_id', 'wallet_djamos.created_at', 'wallet_djamos.created_by',  'wallet_djamos.status',  'wallet_djamos.amount');
        $push_amount = app(HomeService::class)->getTodayTransactionProspectAmountTotal(WalletPush::class, 'wallet_pushes.wallet_id', 'wallet_pushes.created_at', 'wallet_pushes.created_by',  'wallet_pushes.status',  'wallet_pushes.amount');
        $tresortMoney_amount = app(HomeService::class)->getTodayTransactionProspectAmountTotal(WalletTresortMoney::class, 'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_at', 'wallet_tresort_money.created_by',  'wallet_tresort_money.status',  'wallet_tresort_money.amount');
        $versment_amount = app(HomeService::class)->getTodayTransactionProspectAmountTotal(WalletVersement::class, 'wallet_versements.wallet_id', 'wallet_versements.created_at', 'wallet_versements.created_by',  'wallet_versements.status',  'wallet_versements.amount');
        $bank_send = app(HomeService::class)->getTodayTransactionProspectAmountTotal(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.created_by',  'wallet_banks.status',  'wallet_banks.amount');
        // $wave_amount = app(HomeService::class)->getTodayTransactionAmountTotal(WalletWave::class, 'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.created_by',  'wallet_waves.status',  'wallet_waves.amount');

        // Amount transactions month
        $payment_amount_month = app(HomeService::class)->getDaysProspectTransaction(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.created_by',  'wallet_payments.status',  'wallet_payments.amount');
        $transaction_amount_month = app(HomeService::class)->getDaysProspectTransaction(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.created_by',  'wallet_transactions.status',  'wallet_transactions.amount');
        $reload_amount_month = app(HomeService::class)->getDaysReloadProspectAmountTotal(WalletReload::class);
        $phonecredit_amount_month = app(HomeService::class)->getDaysProspectTransaction(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.created_by',  'wallet_phone_credits.status',  'wallet_phone_credits.amount');
        $inter_amount_month = app(HomeService::class)->getDaysProspectTransaction(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.created_by',  'wallet_inters.status',  'wallet_inters.amount_received');
        $bank_amount_month = app(HomeService::class)->getDaysProspectTransaction(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.created_by',  'wallet_banks.status',  'wallet_banks.amount');
        $deposit_amount_month = app(HomeService::class)->getDaysProspectTransaction(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.created_by',  'wallet_deposits.status',  'wallet_deposits.amount');
        $xbet_amount_month = app(HomeService::class)->getDaysProspectTransaction(WalletXbet::class, 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.created_by',  'wallet_xbets.status',  'wallet_xbets.amount');

        $djamo_amount_month = app(HomeService::class)->getDaysProspectTransaction(WalletDjamo::class, 'wallet_djamos.wallet_id', 'wallet_djamos.created_at', 'wallet_djamos.created_by',  'wallet_djamos.status',  'wallet_djamos.amount');

        $push_amount_month = app(HomeService::class)->getDaysProspectTransaction(WalletPush::class, 'wallet_pushes.wallet_id', 'wallet_pushes.created_at', 'wallet_pushes.created_by',  'wallet_pushes.status',  'wallet_pushes.amount');
        $tresortMoney_amount_month = app(HomeService::class)->getDaysProspectTransaction(WalletTresortMoney::class, 'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_at', 'wallet_tresort_money.created_by',  'wallet_tresort_money.status',  'wallet_tresort_money.amount');
        $versment_amount_month = app(HomeService::class)->getDaysProspectTransaction(WalletVersement::class, 'wallet_versements.wallet_id', 'wallet_versements.created_at', 'wallet_versements.created_by',  'wallet_versements.status',  'wallet_versements.amount');

        // count One transactions
        $transaction_amount_days = app(HomeService::class)->getTransactionAllProspectCount(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.created_by');
        $reload_amount_days = app(HomeService::class)->getTransactionAllProspectCount(WalletReload::class, 'wallet_reloads.wallet_id', 'wallet_reloads.created_at', 'wallet_reloads.created_by');
        $payment_amount_days = app(HomeService::class)->getTransactionAllProspectCount(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.created_by');
        $phone_amount_days = app(HomeService::class)->getTransactionAllProspectCount(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.created_by');
        $inter_amount_days = app(HomeService::class)->getTransactionAllProspectCount(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.created_by');
        $bank_amount_days = app(HomeService::class)->getTransactionAllProspectCount(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.created_by');
        $deposit_amount_days = app(HomeService::class)->getTransactionAllProspectCount(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.created_by');
        $xbet_amount_days = app(HomeService::class)->getTransactionAllProspectCount(WalletXbet::class, 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.created_by');


        // New transactions
        $push_amount_days = app(HomeService::class)->getTransactionAllProspectCount(WalletPush::class, 'wallet_pushes.wallet_id', 'wallet_pushes.created_at', 'wallet_pushes.created_by');
        $djamo_amount_days = app(HomeService::class)->getTransactionAllProspectCount(WalletDjamo::class, 'wallet_djamos.wallet_id', 'wallet_djamos.created_at', 'wallet_djamos.created_by');
        $versment_amount_days = app(HomeService::class)->getTransactionAllProspectCount(WalletVersement::class, 'wallet_versements.wallet_id', 'wallet_versements.created_at', 'wallet_versements.created_by');
        $tresortMoney_amount_days = app(HomeService::class)->getTransactionAllProspectCount(WalletTresortMoney::class, 'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_at', 'wallet_tresort_money.created_by');

        return view('livewire.home-prospects', [

            'user_currencie' => $currencie,
            'error_payment' => $payment_error,
            'success_payment' => $payment_success,
            'pendding_payment' => $payment_padding,
            'integrationClient' => $integrationClient,
            // 
            'error_transaction' => $transaction_error,
            'success_transaction' => $transaction_success,
            'pendding_transaction' => $transaction_padding,

            'djamo_error' => $djamo_error,
            'djamo_success' => $djamo_success,
            'djamo_padding' => $djamo_padding,

            'versment_error' => $versment_error,
            'versment_success' => $versment_success,
            'versment_padding' => $versment_padding,

            'tresortMoney_error' => $tresortMoney_error,
            'tresortMoney_success' => $tresortMoney_success,
            'tresortMoney_pendding' => $tresortMoney_pendding,

            'push_error' => $push_error,
            'push_success' => $push_success,
            'push_pendding' => $push_pendding,

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


            // New Transactions
            'amount_djamo_days' => $djamo_amount_month,
            'amount_push_days' => $push_amount_month,
            'amount_tresortMoney_days' => $tresortMoney_amount_month,
            'amount_versment_days' => $versment_amount_month,

            // New 
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

            // New transaction amount
            'sum_push' => $push_amount,
            'sum_djamo' => $djamo_amount,
            'sum_versment' => $versment_amount,
            'sum_tresortMoney' => $tresortMoney_amount,

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

            // New sum
            'sum_push_month' => $push_amount_month,
            'sum_djamo_month' => $djamo_amount_month,
            'sum_tresort_month' => $tresortMoney_amount_month,
            'sum_versment_month' => $versment_amount_month,

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

            // New transactions
            'account_push_days' => $push_amount_days,
            'account_djamo_days' => $djamo_amount_days,
            'account_versment_days' => $versment_amount_days,
            'account_tresort_days' => $tresortMoney_amount_days,

            'walletCommissions' => WalletCommission::where('status', 1)->sum('amount'),
            'walletAdmin' => WalletAdmin::where('status', 1)->sum('amount'),

            // 'open_apps' => $closeRobus,
        ]);
    }

    public function getUserTypeTotal($user, $status)
    {

        $totalUsers = SidUser::where($user,  $status)->count();

        return $totalUsers;
    }
}
