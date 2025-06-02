<?php

namespace App\Http\Livewire;

use App\Http\Services\AuthenticationService;
use App\Models\AppCloseMaster;
use App\Models\AppMasterStatus;
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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HomeMasters extends Component
{

    public $startDate = '';
    public $endDate = '';

    public $appStatusResult;
    // private $authService;
    protected AuthenticationService $authService; 


    public function mount(AuthenticationService $authService)
    {

        $this->authService = $authService;

        $cachedResult = Cache::get('app_status_master_result');

        if ($cachedResult) {
            $this->appStatusResult = $cachedResult;
        } else {

            $this->appStatusResult = $this->authService->appDispoMasterWebRobus(AppMasterStatus::class);
            Cache::put('app_status_master_result', $this->appStatusResult, now()->addMinutes(20));
        }
    }


    public function render()
    {
        $currencie = master()->wallet->currencie->name;

        // Account valeurs transactions
        $payment_error = $this->getTransactionCount(WalletPayment::class, 'ERROR');
        $payment_padding = $this->getTransactionCount(WalletPayment::class, 'PENDING');
        $payment_success = $this->getTransactionCount(WalletPayment::class, 'SUCCESS');

        $transaction_error = $this->getTransactionCount(WalletTransaction::class, 'ERROR');
        $transaction_padding = $this->getTransactionCount(WalletTransaction::class, 'PENDING');
        $transaction_success = $this->getTransactionCount(WalletTransaction::class, 'SUCCESS');

        $bank_error = $this->getTransactionCount(WalletBank::class, 'ERROR');
        $bank_padding = $this->getTransactionCount(WalletBank::class, 'PENDING');
        $bank_success = $this->getTransactionCount(WalletBank::class, 'SUCCESS');

        $deposit_error = $this->getTransactionCount(WalletDeposit::class, 'ERROR');
        $deposit_padding = $this->getTransactionCount(WalletDeposit::class, 'PENDING');
        $deposit_success = $this->getTransactionCount(WalletDeposit::class, 'SUCCESS');

        $reload_error = $this->getTransactionCountReload(WalletReload::class);
        $reload_padding = $this->getTransactionCountReload(WalletReload::class);
        $reload_success = $this->getTransactionCountReload(WalletReload::class);

        $phone_credit_error = $this->getTransactionCount(WalletPhoneCredit::class, 'ERROR');
        $phone_credit_padding = $this->getTransactionCount(WalletPhoneCredit::class, 'PENDING');
        $phone_credit_success = $this->getTransactionCount(WalletPhoneCredit::class, 'SUCCESS');

        $inter_error = $this->getTransactionCount(WalletInter::class, 'ERROR');
        $inter_padding = $this->getTransactionCount(WalletInter::class, 'PENDING');
        $inter_success = $this->getTransactionCount(WalletInter::class, 'SUCCESS');


        // Amount transactions days
        $payement_amount = $this->getTodayTransactionAmountTotal(WalletPayment::class);
        $transaction_amount = $this->getTodayTransactionAmountTotal(WalletTransaction::class);
        $reload_amount = $this->getTodayReloadAmountTotal(WalletReload::class);
        $phonecredit_amount = $this->getTodayTransactionAmountTotal(WalletPhoneCredit::class);
        $inter_amount = $this->getTodayTransactionAmountTotal(WalletInter::class);
        $bank_amount = $this->getTodayTransactionAmountTotal(WalletBank::class);
        $deposit_amount = $this->getTodayTransactionAmountTotal(WalletDeposit::class);

        // Amount transactions month
        $payement_amount_month = $this->getDaysTransaction(WalletPayment::class);
        $transaction_amount_month = $this->getDaysTransaction(WalletTransaction::class);
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
        $payement_gain_days = $this->getTodayTransactionAmountTotal(WalletPayment::class);
        $transaction_gain_days = $this->getTodayTransactionAmountTotal(WalletTransaction::class);
        $phonecredit_gain_days = $this->getTodayTransactionAmountTotal(WalletPhoneCredit::class);
        $inter_gain_days = $this->getTodayTransactionAmountTotal(WalletInter::class);
        $bank_gain_days = $this->getTodayTransactionAmountTotal(WalletBank::class);
        $deposit_gain_days = $this->getTodayTransactionAmountTotal(WalletDeposit::class);

        //  SUm gain 
        $sum_gain = $payement_gain_days + $transaction_gain_days + $phonecredit_gain_days + $inter_gain_days + $bank_gain_days + $deposit_gain_days ;

        $closeRobus = AppCloseMaster::orderBy('id' , 'asc')->get();
        
        return view(
            'livewire.home-masters',
            [

                'user_currencie' => $currencie,
                'master_gain' => $sum_gain,
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
                // Users
                'custumers_counts' =>  $this->CustumersCount,
                'under_counts' => $this->SidUsersCount,
                'open_apps' => $closeRobus,

            ]
        );
    }

    public function getSidUsersCountProperty()
    {

        $sidUsers = SidUser::where('is_under_distributer',true)->where('master_id',  master()->id)->count();
      
        return $sidUsers;
    }

    public function getCustumersCountProperty()
    {

        $sidUsers = SidUser::whereIsCustumers(true)->where('master_id',  master()->id)->count();
      
        return $sidUsers;
    }



    public function getDaysTransaction($walletModel)
    {

        $sidUsers = SidUser::where('master_id',  master()->id)->get();

        $totalAmount = 0;

        foreach ($sidUsers as $sidUser) {

            $sidUserId = $sidUser->wallet->id;

            $amount = $walletModel::where('wallet_id', $sidUserId)
                ->whereStatus('SUCCESS')
                ->whereMonth('created_at', now()->month)
                ->sum('amount');

            if ($amount !== null) {
                $totalAmount += $amount;
            }
        }


        return $totalAmount;
    }

    public function getDaysTransactionGain($walletModel)
    {

        $sidUsers = SidUser::where('master_id',  master()->id)->get();

        $totalAmount = 0;

        foreach ($sidUsers as $sidUser) {

            $sidUserId = $sidUser->wallet->id;

            $amount = $walletModel::where('wallet_id', $sidUserId)
                ->whereStatus('SUCCESS')
                ->whereMonth('created_at', now()->month)
                ->sum('commission_masters');

            if ($amount !== null) {
                $totalAmount += $amount;
            }
        }


        return $totalAmount;
    }

    public function getTransactionCount($walletModel,  $status)
    {


        $sidUsers = SidUser::where('master_id',  master()->id)->get();
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


    public function getTransactionCountReload($walletModel)
    {


        $totalCount = $walletModel::where('wallet_id', master()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereDate('created_at', Carbon::today())
            ->count();

        return $totalCount;
    }


    public function getTodayTransactionAmountTotal($walletModel)
    {

        $sidUsers = SidUser::where('master_id',  master()->id)->get();

        $totalAmount = 0;

        foreach ($sidUsers as $sidUser) {

            $sidUserId = $sidUser->wallet->id;

            $amount = $walletModel::where('wallet_id', $sidUserId)
                ->whereStatus('SUCCESS')
                ->whereDate('created_at', Carbon::today())
                ->sum('commission_masters');

            if ($amount !== null) {
                $totalAmount += $amount;
            }
        }


        return $totalAmount;
    }



    public function getTodayTransactionGainAmountTotal($walletModel)
    {

        $sidUsers = SidUser::where('master_id',  master()->id)->get();

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


        $totalAmount = $walletModel::where('wallet_id',  master()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        return $totalAmount;
    }


    public function getDaysReloadAmountTotal($walletModel)
    {


        $totalAmount = $walletModel::where('wallet_id',  master()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereMonth(DB::raw('created_at'), now()->month)
            ->sum('amount');

        return $totalAmount;
    }
}
