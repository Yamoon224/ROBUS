<?php

namespace App\Http\Livewire;

use App\Models\Categorie;
use App\Models\CloseApp;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletTransaction;
use App\Notifications\UserNotification;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Http\Services\HomeService;

class HomeCompany extends Component
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

    public function cancelEmployesField()
    {

        $this->wallet_id = '';
        $this->amount_payment = '';
        $this->amount_payment_wallet = '';
        $this->wallet_id_employer = '';
        $this->categorieId = null;
        $this->choose_type_payement_emp = null;
        $this->emit('modal-hide', 'hide');
    }



    public function disable()
    {

        $check = SidUser::find($this->user_id);

        if (!empty($check)) {

            if ($check->company_id  == firm()->id && $check->is_accepted_companie) {
                $msg = 'Vous avez été délié ';
                $msg_title = 'Déliaison a  ' . firm()->firstname;
                $check->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $check->update(['company_id' => 1, 'is_accepted_companie' => false, 'categorie_id' => null]);
            } else {
                $msg = 'Vous avez été délié ';
                $msg_title = 'Déliaison a  ' . firm()->firstname;
                $check->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $check->update(['company_id' => 1, 'is_accepted_companie' => false, 'categorie_id' => null]);
            }
            $this->cancelEmployesField();
            $this->emit('modal-hide', 'hide');
            $this->emit('refreshPage');
            $this->emit('success', $check->company_id  == firm()->id ? 'Client lié !' : 'Client détaché !');
        }
    }

    public function update()
    {

        $this->validate([
            'wallet_id' => 'required',
            'categorieId' => 'required',
        ], [
            'wallet_id.required' => 'Votre wallet est obligatoire',
            'categorieId.required' => 'la categorie est obligatoire',

        ]);
        $check =  Wallet::where('wallet_id', $this->wallet_id)->first();

        $check->sidUser->update([
            'categorie_id'  => $this->categorieId,
        ]);

        $this->cancelEmployesField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Categorie modifié !');
    }

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

        // Statistiques underDIstributors
        $payment_error = app(HomeService::class)->getTransactionUsersCount(WalletPayment::class, 'ERROR');
        $payment_padding = app(HomeService::class)->getTransactionUsersCount(WalletPayment::class, 'PENDING');
        $payment_success = app(HomeService::class)->getTransactionUsersCount(WalletPayment::class, 'SUCCESS');

        $transaction_error = app(HomeService::class)->getTransactionUsersCount(WalletTransaction::class, 'ERROR');
        $transaction_padding = app(HomeService::class)->getTransactionUsersCount(WalletTransaction::class, 'PENDING');
        $transaction_success = app(HomeService::class)->getTransactionUsersCount(WalletTransaction::class, 'SUCCESS');

        $bank_error = app(HomeService::class)->getTransactionUsersCount(WalletBank::class, 'ERROR');
        $bank_padding = app(HomeService::class)->getTransactionUsersCount(WalletBank::class, 'PENDING');
        $bank_success = app(HomeService::class)->getTransactionUsersCount(WalletBank::class, 'SUCCESS');

        $deposit_error = app(HomeService::class)->getTransactionUsersCount(WalletDeposit::class, 'ERROR');
        $deposit_padding = app(HomeService::class)->getTransactionUsersCount(WalletDeposit::class, 'PENDING');
        $deposit_success = app(HomeService::class)->getTransactionUsersCount(WalletDeposit::class, 'SUCCESS');

        $reload_error = app(HomeService::class)->getTransactionCountCompagnieReload(WalletReload::class);
        $reload_padding = app(HomeService::class)->getTransactionCountCompagnieReload(WalletReload::class);
        $reload_success = app(HomeService::class)->getTransactionCountCompagnieReload(WalletReload::class);

        $phone_credit_error = app(HomeService::class)->getTransactionUsersCount(WalletPhoneCredit::class, 'ERROR');
        $phone_credit_padding = app(HomeService::class)->getTransactionUsersCount(WalletPhoneCredit::class, 'PENDING');
        $phone_credit_success = app(HomeService::class)->getTransactionUsersCount(WalletPhoneCredit::class, 'SUCCESS');

        $inter_error = app(HomeService::class)->getTransactionUsersCount(WalletInter::class, 'ERROR');
        $inter_padding = app(HomeService::class)->getTransactionUsersCount(WalletInter::class, 'PENDING');
        $inter_success = app(HomeService::class)->getTransactionUsersCount(WalletInter::class, 'SUCCESS');

        // Amount transactions days
        $payement_amount = app(HomeService::class)->getTodayTransactionUndersAmountTotal(WalletPayment::class);
        $transaction_amount = app(HomeService::class)->getTodayTransactionUndersAmountTotal(WalletTransaction::class);
        $reload_amount = app(HomeService::class)->getTodayReloadAmountCompagnieTotal(WalletReload::class);
        $phonecredit_amount = app(HomeService::class)->getTodayTransactionUndersAmountTotal(WalletPhoneCredit::class);
        $inter_amount = app(HomeService::class)->getTodayTransactionUndersAmountTotal(WalletInter::class);
        
        $bank_amount = app(HomeService::class)->getTodayTransactionUndersAmountTotal(WalletBank::class);
        $deposit_amount = app(HomeService::class)->getTodayTransactionUndersAmountTotal(WalletDeposit::class);

        // Amount transactions month
        $payement_amount_month = app(HomeService::class)->getDaysTransactionUnderMonth(WalletPayment::class);
        $transaction_amount_month_company = app(HomeService::class)->getDaysTransactionUnderMonth(WalletTransaction::class);
        $reload_amount_month = app(HomeService::class)->getDaysReloadAmountCompagnieTotal(WalletReload::class);
        $phonecredit_amount_month = app(HomeService::class)->getDaysTransactionUnderMonth(WalletPhoneCredit::class);
        $inter_amount_month = app(HomeService::class)->getDaysTransactionUnderMonth(WalletInter::class);
        $bank_amount_month = app(HomeService::class)->getDaysTransactionUnderMonth(WalletBank::class);
        $deposit_amount_month = app(HomeService::class)->getDaysTransactionUnderMonth(WalletDeposit::class);

        // Gain Month
        $payement_gain_month = app(HomeService::class)->getDaysTransactionCompanieGain(WalletPayment::class);
        $transaction_gain_month = app(HomeService::class)->getDaysTransactionCompanieGain(WalletTransaction::class);
        $phonecredit_gain_month = app(HomeService::class)->getDaysTransactionCompanieGain(WalletPhoneCredit::class);
        $inter_gain_month = app(HomeService::class)->getDaysTransactionCompanieGain(WalletInter::class);
        $bank_gain_month = app(HomeService::class)->getDaysTransactionCompanieGain(WalletBank::class);
        $deposit_gain_month = app(HomeService::class)->getDaysTransactionCompanieGain(WalletDeposit::class);

        // Gain Days
        $payement_gain_days = app(HomeService::class)->getTodayTransactionCommissionCompanieAmountTotal(WalletPayment::class);
        $transaction_gain_days = app(HomeService::class)->getTodayTransactionCommissionCompanieAmountTotal(WalletTransaction::class);
        $phonecredit_gain_days = app(HomeService::class)->getTodayTransactionCommissionCompanieAmountTotal(WalletPhoneCredit::class);
        $inter_gain_days = app(HomeService::class)->getTodayTransactionCommissionCompanieAmountTotal(WalletInter::class);
        $bank_gain_days = app(HomeService::class)->getTodayTransactionCommissionCompanieAmountTotal(WalletBank::class);
        $deposit_gain_days = app(HomeService::class)->getTodayTransactionCommissionCompanieAmountTotal(WalletDeposit::class);

        //  SUm gain 
        $sum_gain = $payement_gain_days + $transaction_gain_days + $phonecredit_gain_days + $inter_gain_days + $bank_gain_days + $deposit_gain_days;

        $walletUser = firm();
        $state_transaction_year =  app(HomeService::class)->getMastersGainProperty(WalletTransaction::class);
        $transaction_amount_month = app(HomeService::class)->getDaysTransactionCompanieGain(WalletTransaction::class);
        
        $transaction_error = app(HomeService::class)->getTransactionFirmCount(WalletTransaction::class, 'ERROR', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');
        $transaction_padding = app(HomeService::class)->getTransactionFirmCount(WalletTransaction::class, 'PENDING', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');
        $transaction_success = app(HomeService::class)->getTransactionFirmCount(WalletTransaction::class, 'SUCCESS', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');
        
        $closeRobus = CloseApp::orderBy('id', 'asc')->get();
        $employers =  SidUser::where('is_custumers', true)->where('company_id', firm()->company_id)->count();
        $underDistributors =  SidUser::where('is_under_distributer', true)->where('company_id', firm()->company_id)->count();
        $gainReloadEmployer =  $this->getRealodsAdminDays(WalletTransaction::class);
        $companyWallet = $walletUser->wallet()->first();

        return view('livewire.home-company', [
            'walletNumber' => $companyWallet->wallet_id ?? null,
            'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
            'sum_error' =>  $transaction_error,
            'sum_success' =>  $transaction_success,
            'pending_success' => $transaction_padding,
            "users" => SidUser::where('company_id', firm()->company->id)->where('id', '!=', firm()->id)->orderBy('created_at', 'desc')->limit(3)->latest()->get(),
            'sum_amount' => $transaction_amount,
            "categories" => Categorie::where('sid_user_id', firm()->company->id)->orderBy("name", 'desc')->get(),
            'user_currencie' => $walletUser->country->currencie->name,
            'realods' => $state_transaction_year,
            'reloads_days'  =>  $transaction_amount_month,
            'employers'  =>  $employers,
            'undersDistributors'  =>  $underDistributors,
            'open_apps' => $closeRobus,
            // Statistique commissions

            'company_gain' => $sum_gain,
            'gainReloadEmployers' => $gainReloadEmployer,
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
            'amount_transaction_month_c' => $transaction_amount_month_company,
            'amount_reload_month' => $reload_amount_month,
            'amount_phone_credit_month' => $phonecredit_amount_month,
            'amount_inter_month' => $inter_amount_month,
            'amount_bank_month' => $bank_amount_month,
            'amount_deposit_month' => $deposit_amount_month,
            // Gains
            'gain_payment_month' => $payement_gain_month,
            'gain_transaction_month' => $transaction_gain_month,
            'gain_phone_credit_month' => $phonecredit_gain_month,
            'gain_inter_month' => $inter_gain_month,
            'gain_bank_month' => $bank_gain_month,
            'gain_deposit_month' => $deposit_gain_month,
            // 
            'gain_payment_days' => $payement_gain_days,
            'gain_transaction_days' => $transaction_gain_days,
            'gain_phone_credit_days' => $phonecredit_gain_days,
            'gain_inter_days' => $inter_gain_days,
            'gain_bank_days' => $bank_gain_days,
            'gain_deposit_days' => $deposit_gain_days,


        ]);
    }


    public function getTransactionCount($walletModel, $status, $wallet_transactions, $transaction_days, $walletMy, $wallet_status)
    {

        $query = $walletModel::join('wallets', $wallet_transactions, 'wallets.id')
            ->whereBetween($transaction_days, [Carbon::today(), Carbon::today()->endOfDay()])
            ->where($wallet_status, $status);

        if (sabdy()->is_company) {
            $query->where(function ($query) use ($wallet_transactions) {
                $query->where($wallet_transactions, firm()->wallet->id);
            });
        }

        return $query->count();
    }



    public function getDaysTransaction($walletModel)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', firm()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        if ($amount !== null) {
            $totalAmount += $amount;
        }


        return $totalAmount;
    }


    public function getRealodsAdminDays($walletModel)
    {

        $reload1 = [];
        $reload2 = [];
        $reload3 = [];
        $reload4 = [];
        $reload5 = [];
        $reload6 = [];
        $reload7 = [];

        foreach ($walletModel::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {

            if ($reload->created_at->dayOfWeek === Carbon::MONDAY) {

                $reload1[] = ($reload->amount);
            }
        }
        foreach ($walletModel::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {

            if ($reload->created_at->dayOfWeek === Carbon::TUESDAY) {

                $reload2[] = ($reload->amount);
            }
        }
        foreach ($walletModel::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::WEDNESDAY) {

                $reload3[] = ($reload->amount);
            }
        }
        foreach ($walletModel::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::THURSDAY) {

                $reload4[] = ($reload->amount);
            }
        }
        foreach ($walletModel::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::FRIDAY) {

                $reload5[] = ($reload->amount);
            }
        }
        foreach ($walletModel::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::SATURDAY) {

                $reload6[] = ($reload->amount);
            }
        }
        foreach ($walletModel::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::SUNDAY) {

                $reload7[] = ($reload->amount);
            }
        }
        $reloads = [array_sum($reload1), array_sum($reload2), array_sum($reload3), array_sum($reload4), array_sum($reload5), array_sum($reload6), array_sum($reload7)];
        // dd($reloads);
        return $reloads;
    }

}
