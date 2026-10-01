<?php

namespace App\Http\Livewire;

use App\Http\Services\HistoriqueService;
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
use App\Models\WithMerchant;
use Illuminate\Support\Carbon;
use Livewire\Component;

class HistoriqueVisitorss extends Component
{


    protected $historiqueService;
    public  $startDate = "";
    public  $endDate =  "";

 

    public function render()
    {


        $walletUser = sabdy();

        // Account valeurs transactions

        $transaction_error = $this->getTransactionCount(WalletTransaction::class, 'ERROR', 'created_at', 'updated_by', 'status' ,  'is_web', true);
        $transaction_padding = $this->getTransactionCount(WalletTransaction::class, 'PENDING', 'created_at', 'updated_by', 'status',  'is_web', true);
        $transaction_success = $this->getTransactionCount(WalletTransaction::class, 'SUCCESS', 'created_at', 'updated_by', 'status',  'is_web', true);


        $deposit_error = $this->getTransactionCount(WalletDeposit::class, 'ERROR', 'created_at', 'updated_by', 'status' ,  'is_web', true);
        $deposit_padding = $this->getTransactionCount(WalletDeposit::class, 'PENDING', 'created_at', 'updated_by', 'status',  'is_web', true);
        $deposit_success = $this->getTransactionCount(WalletDeposit::class, 'SUCCESS', 'created_at', 'updated_by', 'status',  'is_web', true);


        $phone_credit_error = $this->getTransactionCount(WalletPhoneCredit::class, 'ERROR', 'created_at', 'updated_by', 'status',  'is_web', true);
        $phone_credit_padding = $this->getTransactionCount(WalletPhoneCredit::class, 'PENDING', 'created_at', 'updated_by', 'status',  'is_web', true);
        $phone_credit_success = $this->getTransactionCount(WalletPhoneCredit::class, 'SUCCESS', 'created_at', 'updated_by', 'status',  'is_web', true);

        $inter_error = $this->getTransactionCount(WalletInter::class, 'ERROR', 'created_at', 'updated_by', 'status',  'is_web', true);
        $inter_padding = $this->getTransactionCount(WalletInter::class, 'PENDING', 'created_at', 'updated_by', 'status',  'is_web', true);
        $inter_success = $this->getTransactionCount(WalletInter::class, 'SUCCESS', 'created_at', 'updated_by', 'status',  'is_web', true);

        $xbet_error = $this->getTransactionCount(WalletXbet::class, 'ERROR', 'created_at', 'updated_by', 'status',  'is_web', true);
        $xbet_padding = $this->getTransactionCount(WalletXbet::class, 'PENDING', 'wallet_id', 'created_at', 'updated_by', 'status',  'is_web', true);
        $xbet_success = $this->getTransactionCount(WalletXbet::class, 'SUCCESS', 'created_at', 'updated_by', 'status',  'is_web', true);


        // Amount transactions days
        $transaction_amount = $this->getTodayTransactionAmountTotal(WalletTransaction::class , 'created_at', 'updated_by',  'status',  'amount',  'is_web', true);
        $phonecredit_amount = $this->getTodayTransactionAmountTotal(WalletPhoneCredit::class, 'created_at', 'updated_by',  'status',  'amount',  'is_web', true);
        $inter_amount = $this->getTodayTransactionAmountTotal(WalletInter::class, 'created_at', 'updated_by',  'status',  'amount',  'is_web', true);
        $deposit_amount = $this->getTodayTransactionAmountTotal(WalletDeposit::class, 'created_at', 'updated_by',  'status',  'amount',  'is_web', true);
        $xbet_amount = $this->getTodayTransactionAmountTotal(WalletXbet::class, 'created_at', 'updated_by',  'status',  'amount',  'is_web', true);

        if ($walletUser->wallet->sidUser->is_under_distributer == true) {

            abort(403);
        } else {

            return view('livewire.historique-visitorss', [

                'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
                'sum_error' =>  $transaction_error   +  $deposit_error  + $phone_credit_error +   $inter_error + $xbet_error ,
                'sum_success' =>  $transaction_success   +  $deposit_success  + $phone_credit_success +   $inter_success +  $xbet_success ,
                'pending_success' =>  $transaction_padding   +  $deposit_padding  + $phone_credit_padding +   $inter_padding  + $xbet_padding,
                'Wallet_Deposits_last' => WalletDeposit::whereIsWeb(true)->select('wallet_deposits.*')->limit(5)->orderBy('created_at', 'desc')->get(),
                'Wallet_Inters_last' => WalletInter::whereIsWeb(true)->select('wallet_inters.*')->limit(5)->orderBy('created_at', 'desc')->get(),
                'Wallet_Transactions_last' => WalletTransaction::whereIsWeb(true)->select('wallet_transactions.*')->limit(5)->orderBy('created_at', 'desc')->get(),
                'Wallet_PhoneCredits_last' => WalletPhoneCredit::whereIsWeb(true)->select('wallet_phone_credits.*')->limit(5)->orderBy('created_at', 'desc')->get(),
                'Wallet_xbet_last' => WalletXbet::whereIsWeb(true)->select('wallet_xbets.*')->limit(5)->orderBy('created_at', 'desc')->get(),
                'usersCountry' =>  $walletUser->country->currencie->name,
                'sum_deposit' => $deposit_amount,
                'sum_transaction' => $transaction_amount,
                'sum_inter' => $inter_amount,
                'sum_phoneCredit' => $phonecredit_amount,
                'sum_xbets' => $xbet_amount,

            ]);
        }
    }

 


    public function getTodayTransactionAmountTotal($walletModel , $transaction_days, $walletMy, $transaction_status, $wallet_amount , $IswebStatus , $isWeb)
    {

        return $walletModel::whereBetween($transaction_days, [Carbon::today(), Carbon::today()->endOfDay()])
            ->where(function ($query) use ($walletMy) {
                if (sabdy()->is_distributer) {
                    $query->where($walletMy, '=', sabdy()->id);
                } else {
                    $query->where($walletMy, '=', sabdy()->id)->orWhere($walletMy, '!=', sabdy()->id);
                }
            })
            ->where($transaction_status, 'SUCCESS')
            ->where($IswebStatus, $isWeb)
            ->sum($wallet_amount);
    }


    // count valeurs
    public function getTransactionCount($walletModel, $status, $transaction_days, $walletMy, $wallet_status , $IswebStatus , $isWeb)
    {

        return $walletModel::whereBetween($transaction_days, [Carbon::today(), Carbon::today()->endOfDay()])
            ->where(function ($query) use ($walletMy) {
                if (sabdy()->is_distributer) {
                    $query->where($walletMy, '=', sabdy()->id);
                } else {
                    $query->where($walletMy, '=', sabdy()->id)->orWhere($walletMy, '!=', sabdy()->id);
                }
            })
            ->where($wallet_status, $status)
            ->where($IswebStatus, $isWeb)
            ->count();
    }
}
