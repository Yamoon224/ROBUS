<?php

namespace App\Http\Services;



use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Http\Services\ConvertionService;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use App\Models\AdminsCom;
use Illuminate\Support\Facades\DB;

class HistoriqueService
{

    protected $convertionService;
    
    public function mount(ConvertionService $convertionService)
    {
        $this->convertionService = $convertionService;
    }

 

    // Sum Admin
    public function getWalletBankSumAdmin()
    {


        $data = [];

        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', sabdy()->country->id)
            ->whereDate('wallet_banks.created_at', Carbon::today())->select('wallet_banks.*')->where('wallet_banks.status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }


    

    public function getSDistributeurSumAdmin()
    {


        $data = [];

        foreach (Wallet::join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', 107)
            ->select('wallets.*')
            ->get() as $wallet) {
            $data[] = $wallet->amount;
        }

        return array_sum($data);
    }

    public function getSDistributeurSumCountryAdmin($countrie)
    {


        $data = [];

        foreach (Wallet::join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie)
            ->select('wallets.*')
            ->get() as $wallet) {
            $data[] = $wallet->amount;
        }

        return array_sum($data);
    }

    public function getCustumersSumCountrieAdmin($countrie)
    {


        $data = [];

        foreach (Wallet::join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_custumers', true)
            ->where('wallets.country_id', $countrie)
            ->select('wallets.*')
            ->get() as $wallet) {
            $data[] = $wallet->amount;
        }

        return array_sum($data);
    }
    public function getCustumersSumAdmin()
    {


        $data = [];

        foreach (Wallet::join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_custumers', true)
            ->where('wallets.country_id', 107)
            ->select('wallets.*')
            ->get() as $wallet) {
            $data[] = $wallet->amount;
        }

        return array_sum($data);
    }

    public function getWalletDepositSumAdmin()
    {


        $data = [];

        foreach (WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', sabdy()->country->id)
            ->select('wallet_deposits.*')
            ->whereDate('wallet_deposits.created_at', Carbon::today())->where('wallet_deposits.status', 'SUCCESS')->get() as $deposit) {
            $data[] = $deposit->amount;
        }

        return array_sum($data);
    }


    public function getWalletPaymentSumAdmin()
    {


        $data = [];

        foreach (WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id', sabdy()->country->id)
            ->select('wallet_payments.*')
            ->whereDate('wallet_payments.created_at', Carbon::today())->where('wallet_payments.status', 'SUCCESS')->get() as $payment) {
            $data[] = $payment->amount;
        }

        return array_sum($data);
    }

    public function getWalletTransactionSumAdmin()
    {


        $data = [];

        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', sabdy()->country->id)
            ->select('wallet_transactions.*')
            ->whereDate('wallet_transactions.created_at', Carbon::today())->where('wallet_transactions.status', 'SUCCESS')->get() as $transaction) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }

    public function getWalletInterSumAdmin()
    {


        $data = [];

        foreach (WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallets.country_id', sabdy()->country->id)
            ->select('wallet_inters.*')
            ->whereDate('wallet_inters.created_at', Carbon::today())->where('wallet_inters.status', 'SUCCESS')->get() as $inter) {
            $data[] = $inter->amount;
        }

        return array_sum($data);
    }

    public function getCumulsWalletTransactions($wallet, $wallet_transaction, $countryId, $wallet_commission, $wallet_success)
    {
        $cumulAmountComissions = $wallet::join('wallets', 'wallets.id', $wallet_transaction)
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countryId)
            ->where($wallet_commission, '>', 0) // Condition modifiée pour exclure les montants nuls
            ->where($wallet_success, 'SUCCESS')
            ->sum($wallet_commission);


        return $cumulAmountComissions;
    }

    
    
    public function getWalletPhoneCreditSumAdmin()
    {


        $data = [];

        foreach (WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', sabdy()->country->id)
            ->select('wallet_phone_credits.*')
            ->whereDate('wallet_phone_credits.created_at', Carbon::today())->where('wallet_phone_credits.status', 'SUCCESS')->get() as $phone) {
            $data[] = $phone->amount;
        }

        return array_sum($data);
    }

   public function getWalletBankVisitorsDistributersLimitViews($wallet_transactions, $wallet_transaction_id, $wallet_transaction_type)
    {

        $result =  AdminsCom::join($wallet_transactions, $wallet_transaction_id, $wallet_transaction_type)
            ->where('admins_coms.is_web', true)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->groupBy('admins_coms.created_at')
            ->limit(5)
            ->get();


        return   $result;
    }
    
    public function getCumulsWalletDepositVisitorsDistributers($wallet_transactions, $wallet_transaction_id, $wallet_transaction_type)
    {


        $result =  AdminsCom::join($wallet_transactions, $wallet_transaction_id, $wallet_transaction_type)
            ->where('admins_coms.is_web', true)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select(DB::raw('sum(admins_coms.amount) as cumul_amount_chiffre'))
            ->groupBy('admins_coms.created_at')
            ->get();


        return $result->sum('cumul_amount_chiffre');
    }
    
    public function getWalletReloadSumAdmin()
    {



        $data = [];

        foreach (WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', sabdy()->country->id)
            ->select('wallet_reloads.*')
            ->whereDate('wallet_reloads.created_at', Carbon::today())->where('wallet_reloads.status', 'SUCCESS')->get() as $reload) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }
  
}