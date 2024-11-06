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
        $payment_error = $this->getTransactionUsersCount(WalletPayment::class, 'ERROR');
        $payment_padding = $this->getTransactionUsersCount(WalletPayment::class, 'PENDING');
        $payment_success = $this->getTransactionUsersCount(WalletPayment::class, 'SUCCESS');

        $transaction_error = $this->getTransactionUsersCount(WalletTransaction::class, 'ERROR');
        $transaction_padding = $this->getTransactionUsersCount(WalletTransaction::class, 'PENDING');
        $transaction_success = $this->getTransactionUsersCount(WalletTransaction::class, 'SUCCESS');

        $bank_error = $this->getTransactionUsersCount(WalletBank::class, 'ERROR');
        $bank_padding = $this->getTransactionUsersCount(WalletBank::class, 'PENDING');
        $bank_success = $this->getTransactionUsersCount(WalletBank::class, 'SUCCESS');

        $deposit_error = $this->getTransactionUsersCount(WalletDeposit::class, 'ERROR');
        $deposit_padding = $this->getTransactionUsersCount(WalletDeposit::class, 'PENDING');
        $deposit_success = $this->getTransactionUsersCount(WalletDeposit::class, 'SUCCESS');

        $reload_error = $this->getTransactionCountReload(WalletReload::class);
        $reload_padding = $this->getTransactionCountReload(WalletReload::class);
        $reload_success = $this->getTransactionCountReload(WalletReload::class);

        $phone_credit_error = $this->getTransactionUsersCount(WalletPhoneCredit::class, 'ERROR');
        $phone_credit_padding = $this->getTransactionUsersCount(WalletPhoneCredit::class, 'PENDING');
        $phone_credit_success = $this->getTransactionUsersCount(WalletPhoneCredit::class, 'SUCCESS');

        $inter_error = $this->getTransactionUsersCount(WalletInter::class, 'ERROR');
        $inter_padding = $this->getTransactionUsersCount(WalletInter::class, 'PENDING');
        $inter_success = $this->getTransactionUsersCount(WalletInter::class, 'SUCCESS');

        // Amount transactions days
        $payement_amount = $this->getTodayTransactionCommissionAmountTotal(WalletPayment::class);
        $transaction_amount = $this->getTodayTransactionCommissionAmountTotal(WalletTransaction::class);
        $reload_amount = $this->getTodayReloadAmountTotal(WalletReload::class);
        $phonecredit_amount = $this->getTodayTransactionCommissionAmountTotal(WalletPhoneCredit::class);
        $inter_amount = $this->getTodayTransactionCommissionAmountTotal(WalletInter::class);
        $bank_amount = $this->getTodayTransactionCommissionAmountTotal(WalletBank::class);
        $deposit_amount = $this->getTodayTransactionCommissionAmountTotal(WalletDeposit::class);

        // Amount transactions month
        $payement_amount_month = $this->getDaysTransaction(WalletPayment::class);
        $transaction_amount_month_company = $this->getDaysTransaction(WalletTransaction::class);
        $reload_amount_month = $this->getDaysReloadAmountTotal(WalletReload::class);
        $phonecredit_amount_month = $this->getDaysTransaction(WalletPhoneCredit::class);
        $inter_amount_month = $this->getDaysTransaction(WalletInter::class);
        $bank_amount_month = $this->getDaysTransaction(WalletBank::class);
        $deposit_amount_month = $this->getDaysTransaction(WalletDeposit::class);

        // Gain Month
        $payement_gain_month = $this->getDaysTransactionGain(WalletPayment::class);
        $transaction_gain_month = $this->getDaysTransactionGain(WalletTransaction::class);
        $phonecredit_gain_month = $this->getDaysTransactionGain(WalletPhoneCredit::class);
        $inter_gain_month = $this->getDaysTransactionGain(WalletInter::class);
        $bank_gain_month = $this->getDaysTransactionGain(WalletBank::class);
        $deposit_gain_month = $this->getDaysTransactionGain(WalletDeposit::class);

        // Gain Days
        $payement_gain_days = $this->getTodayTransactionCommissionAmountTotal(WalletPayment::class);
        $transaction_gain_days = $this->getTodayTransactionCommissionAmountTotal(WalletTransaction::class);
        $phonecredit_gain_days = $this->getTodayTransactionCommissionAmountTotal(WalletPhoneCredit::class);
        $inter_gain_days = $this->getTodayTransactionCommissionAmountTotal(WalletInter::class);
        $bank_gain_days = $this->getTodayTransactionCommissionAmountTotal(WalletBank::class);
        $deposit_gain_days = $this->getTodayTransactionCommissionAmountTotal(WalletDeposit::class);

        //  SUm gain 
        $sum_gain = $payement_gain_days + $transaction_gain_days + $phonecredit_gain_days + $inter_gain_days + $bank_gain_days + $deposit_gain_days;

        $walletUser = Auth::guard('company')->user();
        $state_transaction_year =  $this->getMastersGainProperty(WalletTransaction::class);
        $transaction_amount_month = $this->getDaysTransaction(WalletTransaction::class);
        $transaction_error = $this->getTransactionCount(WalletTransaction::class, 'ERROR', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');
        $transaction_padding = $this->getTransactionCount(WalletTransaction::class, 'PENDING', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');
        $transaction_success = $this->getTransactionCount(WalletTransaction::class, 'SUCCESS', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');
        $transaction_amount = $this->getTodayTransactionAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount');
        $closeRobus = CloseApp::orderBy('id', 'asc')->get();
        $employers =  SidUser::where('is_custumers', true)->where('company_id', firm()->company_id)->count();
        $underDistributors =  SidUser::where('is_under_distributer', true)->where('company_id', firm()->company_id)->count();
        $gainReloadEmployer =  $this->getRealodsAdminDays(WalletTransaction::class);

        return view('livewire.home-company', [
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

    
    public function getTransactionCountReload($walletModel)
    {


        $totalCount = $walletModel::where('wallet_id', firm()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereDate('created_at', Carbon::today())
            ->count();

        return $totalCount;
    }

    public function getDaysTransactionGain($walletModel)
    {

        $sidUsers = SidUser::where('company_id', firm()->company->id)
        // ->where('id', '!=', firm()->id)
        ->get();

        $totalAmount = 0;

        foreach ($sidUsers as $sidUser) {

            $sidUserId = $sidUser->wallet->id;

            $amount = $walletModel::where('wallet_id', $sidUserId)
                ->whereStatus('SUCCESS')
                ->whereMonth('created_at', now()->month)
                ->sum('commission_companie');

            if ($amount !== null) {
                $totalAmount += $amount;
            }
        }


        return $totalAmount;
    }

    public function getTodayTransactionCommissionAmountTotal($walletModel)
    {

        $sidUsers = SidUser::where('company_id',  firm()->company->id)->get();

        $totalAmount = 0;

        foreach ($sidUsers as $sidUser) {

            $sidUserId = $sidUser->wallet->id;

            $amount = $walletModel::where('wallet_id', $sidUserId)
                ->whereStatus('SUCCESS')
                ->whereDate('created_at', Carbon::today())
                ->sum('commission_companie');

            if ($amount !== null) {
                $totalAmount += $amount;
            }
        }


        return $totalAmount;
    }

    public function getTodayTransactionGainAmountTotal($walletModel)
    {

        $sidUsers = SidUser::where('company_id',  firm()->company->id)
        ->where('id', '!=', firm()->id)->get();

        $totalAmount = 0;

        foreach ($sidUsers as $sidUser) {

            $sidUserId = $sidUser->wallet->id;

            $amount = $walletModel::where('wallet_id', $sidUserId)
                ->whereStatus('SUCCESS')
                ->whereDate('created_at', Carbon::today())
                ->sum('amount');

            if ($amount !== null) {
                $totalAmount += $amount;
            }
        }


        return $totalAmount;
    }


    public function getTodayReloadAmountTotal($walletModel)
    {


        $totalAmount = $walletModel::where('wallet_id',  firm()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        return $totalAmount;
    }

    public function getDaysReloadAmountTotal($walletModel)
    {


        $totalAmount = $walletModel::where('wallet_id',  firm()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereMonth(DB::raw('created_at'), now()->month)
            ->sum('amount');

        return $totalAmount;
    }

    public function getTodayTransactionAmountTotal($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount)
    {

        $query = $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereDate($transaction_days, Carbon::today())
            ->where($transaction_status, 'SUCCESS');

        if (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, firm()->wallet->id);
            });
        }

        return $query->sum($wallet_amount);
    }


    public function getTransactionCount($walletModel, $status, $wallet_transactions, $transaction_days, $walletMy, $wallet_status)
    {

        $query = $walletModel::join('wallets', $wallet_transactions, 'wallets.id')
            ->whereDate($transaction_days, Carbon::today())
            ->where($wallet_status, $status);

        if (sabdy()->is_company) {
            $query->where(function ($query) use ($wallet_transactions) {
                $query->where($wallet_transactions, firm()->wallet->id);
            });
        }

        return $query->count();
    }

    public function getTransactionUsersCount($walletModel,  $status)
    {


        $sidUsers = SidUser::where('company_id',  firm()->company->id)
        ->where('id', '!=', firm()->id)->get();
        $totalcount = 0;

        foreach ($sidUsers as $sidUser) {

            $sidUserId = $sidUser->wallet->id;

            $count = $walletModel::where('wallet_id', $sidUserId)
                ->where('status', $status)->count();

            if ($count !== null) {
                $totalcount += $count;
            }
        }

        return $totalcount;
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


    public function getMastersGainProperty($walletModel)
    {
        $reloads = [];

        $currentYear = Carbon::now()->year;

        for ($month = 1; $month <= 12; $month++) {
            $formattedMonth = str_pad($month, 2, '0', STR_PAD_LEFT);

            $reloadSum = 0;

            $reloadSum += $walletModel::where('wallet_id', firm()->wallet->id)
                ->where('status', 'SUCCESS')
                ->whereYear('created_at', $currentYear)
                ->where('created_at', 'like', "%-$formattedMonth-%")
                ->sum('amount');


            $reloads[] = $reloadSum;
        }

        return $reloads;
    }

    public function getRealodsAdminDays($walletModel)
    {
        $reloadsByDay = array_fill(0, 7, 0);
    
        $transactions = $walletModel::where('wallet_id', firm()->wallet->id)
            ->where('status', 'SUCCESS')
            ->get();
    
        foreach ($transactions as $transaction) {
            $dayOfWeek = $transaction->created_at->dayOfWeek; 
            $reloadsByDay[$dayOfWeek] += $transaction->commission_companie;
        }
    
        return $reloadsByDay; 
    }
    

    public function getRealodsAdminAmountDaysUsers($walletModel)
    {
        $sidUsers = SidUser::where('company_id', firm()->company->id)
            ->where('id', '!=', firm()->id)
            ->get();
    
        $reloadsByDay = array_fill(0, 7, 0);
    
        foreach ($sidUsers as $sidUser) {
            $sidUserId = $sidUser->wallet->id;
    
            $transactions = $walletModel::where('wallet_id', $sidUserId)
                ->where('status', 'SUCCESS')
                ->get();
    
            foreach ($transactions as $transaction) {
                $dayOfWeek = $transaction->created_at->dayOfWeek;
                $reloadsByDay[$dayOfWeek] += $transaction->amount;
            }
        }
    
        return $reloadsByDay;
    }

    
}
