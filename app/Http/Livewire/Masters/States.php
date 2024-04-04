<?php

namespace App\Http\Livewire\Masters;

use App\Models\Banks\BankCard;
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

class States extends Component
{

    public $startDate = '';
    public $endDate = '';

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

        $order_card_padding = $this->getTransactionCount(BankCard::class, 'PENDING');
        $order_card_success = $this->getTransactionCount(BankCard::class, 'SUCCESS');


        // Gain Month
        $payement_gain_month = $this->getDaysTransactionGain(WalletPayment::class);
        $transaction_gain_month = $this->getDaysTransactionGain(WalletTransaction::class);
        $phonecredit_gain_month = $this->getDaysTransactionGain(WalletPhoneCredit::class);
        $inter_gain_month = $this->getDaysTransactionGain(WalletInter::class);
        $bank_gain_month = $this->getDaysTransactionGain(WalletBank::class);
        $deposit_gain_month = $this->getDaysTransactionGain(WalletDeposit::class);
        $ordercard_gain_month = $this->getDaysTransactionGain(BankCard::class);

        // Gain Days
        $payement_gain_days = $this->getTodayTransactionAmountTotal(WalletPayment::class);
        $transaction_gain_days = $this->getTodayTransactionAmountTotal(WalletTransaction::class);
        $phonecredit_gain_days = $this->getTodayTransactionAmountTotal(WalletPhoneCredit::class);
        $inter_gain_days = $this->getTodayTransactionAmountTotal(WalletInter::class);
        $bank_gain_days = $this->getTodayTransactionAmountTotal(WalletBank::class);
        $ordercard_gain_days = $this->getTodayTransactionAmountTotal(BankCard::class);

        $deposit_gain_days = $this->getTodayTransactionAmountTotal(WalletDeposit::class);

        //  SUm gain 
        $sum_gain =  $ordercard_gain_days  + $payement_gain_days + $transaction_gain_days + $phonecredit_gain_days + $inter_gain_days + $bank_gain_days + $deposit_gain_days;

        // Year
        $state_transaction_year =  $this->getMastersGainProperty(WalletTransaction::class);
        $state_payments_year =  $this->getMastersGainProperty(WalletPayment::class);
        $state_banks_year =  $this->getMastersGainProperty(WalletBank::class);

        $state_phone_year =  $this->getMastersGainProperty(WalletPhoneCredit::class);
        $state_deposit_year =  $this->getMastersGainProperty(WalletDeposit::class);
        $state_inter_year =  $this->getMastersGainProperty(WalletInter::class);
        $state_ordercard_year =  $this->getMastersGainProperty(BankCard::class);

        // days
        $state_transaction_days =  $this->getMastersGainDaysProperty(WalletTransaction::class);
        $state_payments_days =  $this->getMastersGainDaysProperty(WalletPayment::class);
        $state_banks_days =  $this->getMastersGainDaysProperty(WalletBank::class);
        $state_ordercard_days =  $this->getMastersGainDaysProperty(BankCard::class);

        $state_phone_days =  $this->getMastersGainDaysProperty(WalletPhoneCredit::class);
        $state_deposit_days =  $this->getMastersGainDaysProperty(WalletDeposit::class);
        $state_inter_days =  $this->getMastersGainDaysProperty(WalletInter::class);


        return view(
            'livewire.masters.states',
            [

                'user_currencie' => $currencie,
                'master_gain' => $sum_gain,
                'error_payment' => $payment_error,
                'success_payment' => $payment_success,
                'pendding_payment' => $payment_padding,

                // 
                'success_ordercard' => $order_card_success,
                'pendding_ordercard' => $order_card_padding,

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

                // Gains
                'gain_payment_month' => $payement_gain_month,
                'gain_transaction_month' => $transaction_gain_month,
                'gain_phone_credit_month' => $phonecredit_gain_month,
                'gain_inter_month' => $inter_gain_month,
                'gain_bank_month' => $bank_gain_month,
                'gain_deposit_month' => $deposit_gain_month,
                'gain_ordercard_month' => $ordercard_gain_month,

                // 
                'gain_payment_days' => $payement_gain_days,
                'gain_transaction_days' => $transaction_gain_days,
                'gain_phone_credit_days' => $phonecredit_gain_days,
                'gain_inter_days' => $inter_gain_days,
                'gain_bank_days' => $bank_gain_days,
                'gain_deposit_days' => $deposit_gain_days,
                'gain_ordercard_days' => $ordercard_gain_days,

                // State days
                'gain_payment_days_state' => $state_payments_days,
                'gain_transaction_days_state' => $state_transaction_days,
                'gain_phone_credit_days_state' => $state_phone_days,
                'gain_inter_days_state' => $state_inter_days,
                'gain_bank_days_state' => $state_banks_days,
                'gain_deposit_days_state' => $state_deposit_days,
                'gain_ordercard_days_state' => $state_ordercard_days,

                'gain_payment_month_state' => $state_payments_year,
                'gain_transaction_month_state' => $state_transaction_year,
                'gain_phone_credit_month_state' => $state_phone_year,
                'gain_inter_month_state' => $state_inter_year,
                'gain_bank_month_state' => $state_banks_year,
                'gain_deposit_month_state' => $state_deposit_year,
                'gain_ordercard_month_state' => $state_ordercard_year,
            ]
        );
    }




    public function getMastersGainDaysProperty($walletModel)
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




    public function getMastersGainProperty($walletModel)
    {
        $reloads = [];

        $currentYear = Carbon::now()->year;

        for ($month = 1; $month <= 12; $month++) {
            $formattedMonth = str_pad($month, 2, '0', STR_PAD_LEFT);

            $reloadSum = 0;

            foreach (SidUser::where('master_id', master()->id)->get() as $sidUser) {
                $sidUserId = $sidUser->wallet->id;
                $reloadSum += $walletModel::where('wallet_id', $sidUserId)
                    ->where('status', 'SUCCESS')
                    ->whereYear('created_at', $currentYear)
                    ->where('created_at', 'like', "%-$formattedMonth-%")
                    ->sum('amount');
            }

            $reloads[] = $reloadSum;
        }

        return $reloads;
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
