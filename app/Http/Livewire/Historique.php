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

class Historique extends Component
{

    protected $historiqueService;
    public  $startDate = "";
    public  $endDate =  "";

    public function mount(HistoriqueService $historiqueService)
    {
        $this->historiqueService = $historiqueService;
    }


    public function render()
    {


        $walletUser = sabdy();

        // Account valeurs transactions
        $payment_error = $this->getTransactionCount(WalletPayment::class, 'ERROR', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by', 'wallet_payments.status',  'wallet_payments.is_web', false);
        $payment_padding = $this->getTransactionCount(WalletPayment::class, 'PENDING', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by', 'wallet_payments.status',  'wallet_payments.is_web', false);
        $payment_success = $this->getTransactionCount(WalletPayment::class, 'SUCCESS', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by', 'wallet_payments.status',  'wallet_payments.is_web', false);

        $transaction_error = $this->getTransactionCount(WalletTransaction::class, 'ERROR', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status',  'wallet_transactions.is_web', false);
        $transaction_padding = $this->getTransactionCount(WalletTransaction::class, 'PENDING', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status',  'wallet_transactions.is_web', false);


        $transaction_success = $this->getTransactionCount(WalletTransaction::class, 'SUCCESS', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status',  'wallet_transactions.is_web', false);

        $bank_error = $this->getTransactionCount(WalletBank::class, 'ERROR', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by', 'wallet_banks.status',  'wallet_banks.is_web', false);
        $bank_padding = $this->getTransactionCount(WalletBank::class, 'PENDING', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by', 'wallet_banks.status',  'wallet_banks.is_web', false);
        $bank_success = $this->getTransactionCount(WalletBank::class, 'SUCCESS', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by', 'wallet_banks.status',  'wallet_banks.is_web', false);

        $deposit_error = $this->getTransactionCount(WalletDeposit::class, 'ERROR', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by', 'wallet_deposits.status',  'wallet_deposits.is_web', false);
        $deposit_padding = $this->getTransactionCount(WalletDeposit::class, 'PENDING', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by', 'wallet_deposits.status',  'wallet_deposits.is_web', false);
        $deposit_success = $this->getTransactionCount(WalletDeposit::class, 'SUCCESS', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by', 'wallet_deposits.status',  'wallet_deposits.is_web', false);

        $reload_error = $this->getTransactionCountReload(WalletReload::class);
        $reload_padding = $this->getTransactionCountReload(WalletReload::class);
        $reload_success = $this->getTransactionCountReload(WalletReload::class);

        $phone_credit_error = $this->getTransactionCount(WalletPhoneCredit::class, 'ERROR',  'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by', 'wallet_phone_credits.status',  'wallet_phone_credits.is_web', false);
        $phone_credit_padding = $this->getTransactionCount(WalletPhoneCredit::class, 'PENDING', 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by', 'wallet_phone_credits.status',  'wallet_phone_credits.is_web', false);
        $phone_credit_success = $this->getTransactionCount(WalletPhoneCredit::class, 'SUCCESS',  'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by', 'wallet_phone_credits.status',  'wallet_phone_credits.is_web', false);

        $inter_error = $this->getTransactionCount(WalletInter::class, 'ERROR',  'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by', 'wallet_inters.status',  'wallet_inters.is_web', false);
        $inter_padding = $this->getTransactionCount(WalletInter::class, 'PENDING', 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by', 'wallet_inters.status',  'wallet_inters.is_web', false);
        $inter_success = $this->getTransactionCount(WalletInter::class, 'SUCCESS',  'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by', 'wallet_inters.status',  'wallet_inters.is_web', false);

        $xbet_error = $this->getTransactionCount(WalletXbet::class, 'ERROR',  'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by', 'wallet_xbets.status',  'wallet_xbets.is_web', false);
        $xbet_padding = $this->getTransactionCount(WalletXbet::class, 'PENDING', 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by', 'wallet_xbets.status',  'wallet_xbets.is_web', false);
        $xbet_success = $this->getTransactionCount(WalletXbet::class, 'SUCCESS',  'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by', 'wallet_xbets.status',  'wallet_xbets.is_web', false);

        $wave_error = $this->getTransactionCount(WalletWave::class, 'ERROR',  'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by', 'wallet_waves.status',  'wallet_waves.is_web', false);
        $wave_padding = $this->getTransactionCount(WalletWave::class, 'PENDING', 'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by', 'wallet_waves.status',  'wallet_waves.is_web', false);
        $wave_success = $this->getTransactionCount(WalletWave::class, 'SUCCESS',  'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by', 'wallet_waves.status',  'wallet_waves.is_web', false);

        // Amount transactions days
        $payement_amount = $this->getTodayTransactionAmountTotal(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount',  'wallet_payments.is_web', false);
        $transaction_amount = $this->getTodayTransactionAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount',  'wallet_transactions.is_web', false);
        $reload_amount = $this->getTodayReloadAmountTotal(WalletReload::class);
        $phonecredit_amount = $this->getTodayTransactionAmountTotal(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by',  'wallet_phone_credits.status',  'wallet_phone_credits.amount',  'wallet_phone_credits.is_web', false);
        $inter_amount = $this->getTodayTransactionAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount',  'wallet_inters.is_web', false);
        $bank_amount = $this->getTodayTransactionAmountTotal(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by',  'wallet_banks.status',  'wallet_banks.amount',   'wallet_banks.is_web', false);
        $deposit_amount = $this->getTodayTransactionAmountTotal(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by',  'wallet_deposits.status',  'wallet_deposits.amount',  'wallet_deposits.is_web', false);
        $xbet_amount = $this->getTodayTransactionAmountTotal(WalletXbet::class, 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by',  'wallet_xbets.status',  'wallet_xbets.amount',  'wallet_xbets.is_web', false);
        $wave_amount = $this->getTodayTransactionAmountTotal(WalletWave::class, 'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by',  'wallet_waves.status',  'wallet_waves.amount',  'wallet_waves.is_web', false);

  
            return view('livewire.historique', [

                'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
                'sum_error' => $payment_error  + $transaction_error + $bank_error  +  $deposit_error +  $reload_error + $phone_credit_error +   $inter_error + $xbet_error ,
                'sum_success' => $payment_success  + $transaction_success + $bank_success  +  $deposit_success +  $reload_success + $phone_credit_success +   $inter_success +  $xbet_success ,
                'pending_success' => $payment_padding  + $transaction_padding + $bank_padding  +  $deposit_padding +  $reload_padding + $phone_credit_padding +   $inter_padding  + $xbet_padding ,
                'wallet_Banks_last' => WalletBank::where('is_web', false)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('created_by', sabdy()->id); 
                })
                ->orderBy('created_at', 'desc')
                ->select('wallet_banks.*')
                ->limit(5)
                ->get(),
                'Wallet_Deposits_last' => WalletDeposit::whereIsWeb(false)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('created_by', sabdy()->id); 
                })
                ->select('wallet_deposits.*')->limit(5)->orderBy('wallet_deposits.created_at', 'desc')->get(),
                'Wallet_Inters_last' => WalletInter::whereIsWeb(false)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('created_by', sabdy()->id); 
                })
                ->select('wallet_inters.*')->limit(5)->orderBy('wallet_inters.created_at', 'desc')->get(),
                'Wallet_Payments_last' => WalletPayment::whereIsWeb(false)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('created_by', sabdy()->id); 
                }) 
                ->select('wallet_payments.*')->limit(5)->orderBy('wallet_payments.created_at', 'desc')->get(),
                'Wallet_Transactions_last' => WalletTransaction::whereIsWeb(false)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('created_by', sabdy()->id); 
                }) 
                ->select('wallet_transactions.*')->limit(5)->orderBy('wallet_transactions.created_at', 'desc')->get(),
                'Wallet_PhoneCredits_last' => WalletPhoneCredit::whereIsWeb(false)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('created_by', sabdy()->id); 
                })
                ->select('wallet_phone_credits.*')->limit(5)->orderBy('wallet_phone_credits.created_at', 'desc')->get(),
                'Wallet_Reloads_last' => WalletReload::select('wallet_reloads.*')
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('created_by', sabdy()->id); 
                })
                ->limit(5)->orderBy('wallet_reloads.created_at', 'desc')->get(),
                'Wallet_xbet_last' => WalletXbet::whereIsWeb(false)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('created_by', sabdy()->id); 
                })
                ->select('wallet_xbets.*')->limit(5)->orderBy('wallet_xbets.created_at', 'desc')->get(),
                'Wallet_wave_last' => WalletWave::whereIsWeb(true)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('created_by', sabdy()->id); 
                })->select('wallet_waves.*')->limit(5)->orderBy('wallet_waves.created_at', 'desc')->get(),
                'With_drawer_merchant' => WithMerchant::limit(5)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('sid_user_id', sabdy()->id); 
                })->orderBy('date_removed', 'desc')->get(),
                'usersCountry' =>  $walletUser->country->currencie->name,
                'sum_bank' =>  $bank_amount,
                'sum_deposit' => $deposit_amount,
                'sum_payment' => $payement_amount,
                'sum_reload' => $reload_amount,
                'sum_transaction' => $transaction_amount,
                'sum_inter' => $inter_amount,
                'sum_phoneCredit' => $phonecredit_amount,
                'sum_xbets' => $xbet_amount,
                'sum_waves' => $wave_amount,
                'sum_under_distributers' => $this->historiqueService->getSDistributeurSumAdmin(),
                'sum_custumers' => $this->historiqueService->getCustumersSumAdmin(),

            ]);
    
    }

    public function getTransactionCountReload($walletModel)
    {


        $totalCount = $walletModel::where('wallet_id', sabdy()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereDate('created_at', Carbon::today())
            ->count();

        return $totalCount;
    }


    public function getTodayReloadAmountTotal($walletModel)
    {

        $totalAmount = $walletModel::where('wallet_id',  sabdy()->wallet->id)
        ->whereStatus('SUCCESS')
        ->whereDate('created_at', Carbon::today())
        ->sum('amount');

        return $totalAmount;
    }

    public function getTodayTransactionAmountTotal($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount, $IswebStatus, $isWeb)
    {
        // Construction de la requête de base
        $query = $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereDate($transaction_days, Carbon::today())
            ->where($transaction_status, 'SUCCESS')
            ->where($IswebStatus, $isWeb);

        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id)
                ->where('wallets.country_id', sabdy()->id);
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

        // Calculer la somme des montants des transactions
        return $query->sum($wallet_amount);
    }


    // count valeurs
    public function getTransactionCount($walletModel, $status, $wallet_transactions, $transaction_days, $walletMy, $wallet_status, $IswebStatus, $isWeb)
    {
        // Commencer par construire la requête de base
        $query = $walletModel::join('wallets', $wallet_transactions, 'wallets.id')
            ->whereDate($transaction_days, Carbon::today())
            ->where($wallet_status, $status)
            ->where($IswebStatus, $isWeb);

        // Vérification de l'utilisateur distributeur
        if (sabdy()->is_distributer) {
            $query->where($walletMy, sabdy()->id)
                ->where('wallets.country_id', sabdy()->id);
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

        // Compter les transactions
        return $query->count();
    }
}
