<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Charts\MonthsHistorique;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Database\Eloquent\Collection;

class Statistics extends Component
{

    public function render()
    {



        $walletUser = Auth::guard('web')->user();


        return view('livewire.statistics', [

            'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
            'sum_error' => $this->ReloadError  + $this->VirementError + $this->PhoneCreditError  +  $this->DepositError +  $this->InterError + $this->PaymentError +   $this->TransactionError,
            'sum_success' => $this->ReloadSuccess  + $this->VirementSuccess + $this->PhoneCreditSuccess  +  $this->DepositSuccess +  $this->InterSuccess + $this->PaymentSuccess +   $this->TransactionSuccess,
            'pending_success' => $this->ReloadPendding  + $this->VirementPendding + $this->PhoneCreditPendding  +  $this->DepositPendding +  $this->InterPendding + $this->PaymentPendding +   $this->TransactionPendding,
            'wallet_Banks_last' => WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_banks.created_by', sabdy()->id); 
                })
                ->limit(3)->select('wallet_banks.*')->orderBy('wallet_banks.created_at', 'desc')->get(),
            'Wallet_Deposits_last' => WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_deposits.created_by', sabdy()->id); 
                })
                ->limit(3)->select('wallet_deposits.*')->orderBy('wallet_deposits.created_at', 'desc')->get(),

            'Wallet_Inters_last' => WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
                ->where('wallet_inters.country_id', Auth::guard('web')->user()->country->id)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_inters.created_by', sabdy()->id); 
                })
                ->limit(3)->select('wallet_inters.*')->orderBy('wallet_inters.created_at', 'desc')->get(),
            'Wallet_Payments_last' => WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_payments.created_by', sabdy()->id); 
                })
                ->limit(3)->select('wallet_payments.*')->orderBy('wallet_payments.created_at', 'desc')->get(),
            'Wallet_Transactions_last' => WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_transactions.created_by', sabdy()->id); 
                })
                ->limit(3)->select('wallet_transactions.*')->orderBy('created_at', 'desc')->get(),
            'Wallet_PhoneCredits_last' => WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_phone_credits.created_by', sabdy()->id); 
                })
                ->limit(3)->select('wallet_phone_credits.*')->orderBy('wallet_phone_credits.created_at', 'desc')->get(),
            'Wallet_Reloads_last' => WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_reloads.created_by', sabdy()->id); 
                })
                ->limit(3)->select('wallet_reloads.*')->orderBy('wallet_reloads.created_at', 'desc')->get(),
            'usersCountry' =>  $walletUser->country->currencie->name,
            'sum_bank' => $this->WalletBankSum,
            'sum_deposit' => $this->WalletDepositSum,
            'sum_payment' => $this->WalletPaymentSum,
            'sum_reload' => $this->WalletReloadSum,
            'sum_transaction' => $this->WalletTransactionSum,
            'sum_inter' => $this->WalletInterSum,
            'sum_phoneCredit' => $this->WalletPhoneCreditSum,
            'sum_bank_gain' => $this->WalletBankSumGain,
            'sum_deposit_gain' => $this->WalletDepositSumGain,
            'sum_payment_gain' => $this->WalletPaymentSumGain,
            'sum_reload_gain' => $this->WalletReloadSumGain,
            'sum_transaction_gain' => $this->WalletTransactionSumGain,
            'sum_inter_gain' => $this->WalletInterSumGain,
            'sum_phoneCredit_gain' => $this->WalletPhoneCreditSumGain,

        ]);
        
    }


    //   State gains Admin
    public function getWalletBankSumGainProperty()
    {
        $sumGain = WalletBank::join('wallets', 'wallet_banks.wallet_id', '=', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->when(sabdy()->is_under_distributer, function ($query) {
                return $query->where('wallet_banks.created_by', sabdy()->id);
            })
            ->whereDate('wallet_banks.created_at', Carbon::today())
            ->where('wallet_banks.status', 'SUCCESS')
            ->sum('wallet_banks.commissions'); 
    
        return $sumGain;
    }
    

    public function getWalletDepositSumGainProperty()
    {

        $sumGain = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', '=', 'wallets.id')
        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
        ->when(sabdy()->is_under_distributer, function ($query) {
            return $query->where('wallet_deposits.created_by', sabdy()->id);
        })
        ->whereDate('wallet_deposits.created_at', Carbon::today())
        ->where('wallet_deposits.status', 'SUCCESS')
        ->sum('wallet_deposits.commissions'); 

        return $sumGain;

    }


    public function getWalletPaymentSumGainProperty()
    {

        $sumGain = WalletPayment::join('wallets', 'wallet_payments.wallet_id', '=', 'wallets.id')
        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
        ->when(sabdy()->is_under_distributer, function ($query) {
            return $query->where('wallet_payments.created_by', sabdy()->id);
        })
        ->whereDate('wallet_payments.created_at', Carbon::today())
        ->where('wallet_payments.status', 'SUCCESS')
        ->sum('wallet_payments.commissions'); 

        return $sumGain;
    }

    public function getWalletTransactionSumGainProperty()
    {

        $sumGain = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', '=', 'wallets.id')
        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
        ->when(sabdy()->is_under_distributer, function ($query) {
            return $query->where('wallet_transactions.created_by', sabdy()->id);
        })
        ->whereDate('wallet_transactions.created_at', Carbon::today())
        ->where('wallet_transactions.status', 'SUCCESS')
        ->sum('wallet_transactions.commissions'); 

        return $sumGain;
    }

    public function getWalletInterSumGainProperty()
    {

        $sumGain = WalletInter::join('wallets', 'wallet_inters.wallet_id', '=', 'wallets.id')
        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
        ->when(sabdy()->is_under_distributer, function ($query) {
            return $query->where('wallet_inters.created_by', sabdy()->id);
        })
        ->whereDate('wallet_inters.created_at', Carbon::today())
        ->where('wallet_inters.status', 'SUCCESS')
        ->sum('wallet_inters.commissions'); 

        return $sumGain;

    }
    public function getWalletPhoneCreditSumGainProperty()
    {

        $sumGain = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', '=', 'wallets.id')
        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
        ->when(sabdy()->is_under_distributer, function ($query) {
            return $query->where('wallet_deposits.created_by', sabdy()->id);
        })
        ->whereDate('wallet_deposits.created_at', Carbon::today())
        ->where('wallet_deposits.status', 'SUCCESS')
        ->sum('wallet_deposits.commissions'); 

        return $sumGain;

        $data = [];
        foreach (WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->whereDate('wallet_phone_credits.created_at', Carbon::today())->where('wallet_phone_credits.status', 'SUCCESS')->select('wallet_phone_credits.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletReloadSumGainProperty()
    {

        $sumGain = WalletReload::join('wallets', 'wallet_reloads.wallet_id', '=', 'wallets.id')
        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
        ->when(sabdy()->is_under_distributer, function ($query) {
            return $query->where('wallet_reloads.created_by', sabdy()->id);
        })
        ->whereDate('wallet_reloads.created_at', Carbon::today())
        ->where('wallet_reloads.status', 'SUCCESS')
        ->sum('wallet_reloads.commissions'); 

        return $sumGain;
    }

  
    // Sum Admin
    public function getWalletBankSumProperty()
    {

        $sumGain = WalletBank::join('wallets', 'wallet_banks.wallet_id', '=', 'wallets.id')
        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
        ->when(sabdy()->is_under_distributer, function ($query) {
            return $query->where('wallet_banks.created_by', sabdy()->id);
        })
        ->whereDate('wallet_banks.created_at', Carbon::today())
        ->where('wallet_banks.status', 'SUCCESS')
        ->sum('wallet_banks.amount'); 

        return $sumGain;
    }

    public function getWalletDepositSumProperty()
    {

        $sumGain = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', '=', 'wallets.id')
        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
        ->when(sabdy()->is_under_distributer, function ($query) {
            return $query->where('wallet_deposits.created_by', sabdy()->id);
        })
        ->whereDate('wallet_deposits.created_at', Carbon::today())
        ->where('wallet_deposits.status', 'SUCCESS')
        ->sum('wallet_deposits.amount'); 

        return $sumGain;
    }


    public function getWalletPaymentSumProperty()
    {


        $sumGain = WalletPayment::join('wallets', 'wallet_payments.wallet_id', '=', 'wallets.id')
        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
        ->when(sabdy()->is_under_distributer, function ($query) {
            return $query->where('wallet_payments.created_by', sabdy()->id);
        })
        ->whereDate('wallet_payments.created_at', Carbon::today())
        ->where('wallet_payments.status', 'SUCCESS')
        ->sum('wallet_payments.amount'); 

        return $sumGain;
    }

    public function getWalletTransactionSumProperty()
    {

        $sumGain = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', '=', 'wallets.id')
        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
        ->when(sabdy()->is_under_distributer, function ($query) {
            return $query->where('wallet_transactions.created_by', sabdy()->id);
        })
        ->whereDate('wallet_transactions.created_at', Carbon::today())
        ->where('wallet_transactions.status', 'SUCCESS')
        ->sum('wallet_transactions.amount'); 

        return $sumGain;

    }

    public function getWalletInterSumProperty()
    {

        $sumGain = WalletInter::join('wallets', 'wallet_inters.wallet_id', '=', 'wallets.id')
        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
        ->when(sabdy()->is_under_distributer, function ($query) {
            return $query->where('wallet_inters.created_by', sabdy()->id);
        })
        ->whereDate('wallet_inters.created_at', Carbon::today())
        ->where('wallet_inters.status', 'SUCCESS')
        ->sum('wallet_inters.amount'); 

        return $sumGain;

    }
    public function getWalletPhoneCreditSumProperty()
    {

        $sumGain = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', '=', 'wallets.id')
        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
        ->when(sabdy()->is_under_distributer, function ($query) {
            return $query->where('wallet_phone_credits.created_by', sabdy()->id);
        })
        ->whereDate('wallet_phone_credits.created_at', Carbon::today())
        ->where('wallet_phone_credits.status', 'SUCCESS')
        ->sum('wallet_phone_credits.amount'); 

        return $sumGain;

    }

    public function getWalletReloadSumProperty()
    {

        $sumGain = WalletReload::join('wallets', 'wallet_reloads.wallet_id', '=', 'wallets.id')
        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
        ->when(sabdy()->is_under_distributer, function ($query) {
            return $query->where('wallet_reloads.created_by', sabdy()->id);
        })
        ->whereDate('wallet_reloads.created_at', Carbon::today())
        ->where('wallet_reloads.status', 'SUCCESS')
        ->sum('wallet_reloads.amount'); 

        return $sumGain;

    }

    // Transaction status Infos
    private function getTransactionCountByStatus($status)
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        if (sabdy()->is_under_distributer) {
            $walletInfos = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_transactions.status', $status)
                ->where('wallet_transactions.wallet_id', sabdy()->wallet->id)
                ->whereDate('wallet_transactions.created_at', Carbon::today())
                ->count();
        } else {
            $walletInfos = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_transactions.status', $status)
                ->whereDate('wallet_transactions.created_at', Carbon::today())
                ->count();
        }

        return  $walletInfos;
    }
    // Details Transaction User
    public function getTransactionErrorProperty()
    {
        return $this->getTransactionCountByStatus('ERROR');

    }

    public function getTransactionSuccessProperty()
    {

        return $this->getTransactionCountByStatus('SUCCESS');

    }

    public function getTransactionPenddingProperty()
    {

        return $this->getTransactionCountByStatus('PENDING');

    }

    private function getPaymentCountByStatus($status)
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        if (sabdy()->is_under_distributer) {
            $walletPaiement = WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_payments.status', $status)
                ->where('wallet_payments.wallet_id', sabdy()->wallet->id)
                ->whereDate('wallet_payments.created_at', Carbon::today())
                ->count();
        } else {
            $walletPaiement = WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_payments.status', $status)
                ->whereDate('wallet_payments.created_at', Carbon::today())
                ->count();
        }

        return  $walletPaiement;
    }

    public function getPaymentErrorProperty()
    {

              return $this->getPaymentCountByStatus('ERROR');

    }

    public function getPaymentSuccessProperty()
    {

        return $this->getPaymentCountByStatus('SUCCESS');

    }

    public function getPaymentPenddingProperty()
    {

        return $this->getPaymentCountByStatus('PENDING');

    }

    private function getReloadCountByStatus($status)
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        if (sabdy()->is_under_distributer) {
            $walletPaiement = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_reloads.status', $status)
                ->where('wallet_reloads.wallet_id', sabdy()->wallet->id)
                ->whereDate('wallet_reloads.created_at', Carbon::today())
                ->count();
        } else {
            $walletPaiement = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_reloads.status', $status)
                ->whereDate('wallet_reloads.created_at', Carbon::today())
                ->count();
        }

        return  $walletPaiement;
    }

    public function getReloadErrorProperty()
    {

        return $this->getReloadCountByStatus('ERROR');
    }

    public function getReloadSuccessProperty()
    {


        return $this->getReloadCountByStatus('SUCCESS');
    }

    public function getReloadPenddingProperty()
    {

        return $this->getReloadCountByStatus('PENDING');
    }

    private function getVirementCountByStatus($status)
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        if (sabdy()->is_under_distributer) {
            $walletPaiement = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_banks.status', $status)
                ->where('wallet_banks.wallet_id', sabdy()->wallet->id)
                ->whereDate('wallet_banks.created_at', Carbon::today())
                ->count();
        } else {
            $walletPaiement = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_banks.status', $status)
                ->whereDate('wallet_banks.created_at', Carbon::today())
                ->count();
        }

        return  $walletPaiement;
    }
    public function getVirementSuccessProperty()
    {

        return $this->getVirementCountByStatus('SUCCESS');
    }

    public function getVirementPenddingProperty()
    {

        return $this->getVirementCountByStatus('PENDING');

    }

    public function getVirementErrorProperty()
    {

        return $this->getVirementCountByStatus('ERROR');

    }

    private function getInterCountByStatus($status)
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        if (sabdy()->is_under_distributer) {
            $walletInters = WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_inters.status', $status)
                ->where('wallet_inters.wallet_id', sabdy()->wallet->id)
                ->whereDate('wallet_inters.created_at', Carbon::today())
                ->count();
        } else {
            $walletInters = WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_inters.status', $status)
                ->whereDate('wallet_inters.created_at', Carbon::today())
                ->count();
        }

        return  $walletInters;
    }

    public function getInterErrorProperty()
    {

               return $this->getInterCountByStatus('ERROR');

    }

    public function getInterSuccessProperty()
    {

        return $this->getInterCountByStatus('SUCCESS');

    }

    public function getInterPenddingProperty()
    {

        return $this->getInterCountByStatus('PENDING');

    }

    private function getPhoneCreditCountByStatus($status)
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        if (sabdy()->is_under_distributer) {
            $walletInfos = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_phone_credits.status', $status)
                ->where('wallet_phone_credits.wallet_id', sabdy()->wallet->id)
                ->whereDate('wallet_phone_credits.created_at', Carbon::today())
                ->count();
        } else {
            $walletInfos = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_phone_credits.status', $status)
                ->whereDate('wallet_phone_credits.created_at', Carbon::today())
                ->count();
        }

        return  $walletInfos;
    }
    public function getPhoneCreditErrorProperty()
    {

        return $this->getPhoneCreditCountByStatus('ERROR');

    }

    public function getPhoneCreditSuccessProperty()
    {

        return $this->getPhoneCreditCountByStatus('SUCCESS');

    }

    public function getPhoneCreditPenddingProperty()
    {

        return $this->getPhoneCreditCountByStatus('PENDING');

    }

    private function getDepositCountByStatus($status)
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        if (sabdy()->is_under_distributer) {
            $walletInfos = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_deposits.status', $status)
                ->where('wallet_deposits.wallet_id', sabdy()->wallet->id)
                ->whereDate('wallet_deposits.created_at', Carbon::today())
                ->count();
        } else {
            $walletInfos = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->where('wallet_deposits.status', $status)
                ->whereDate('wallet_deposits.created_at', Carbon::today())
                ->count();
        }

        return  $walletInfos;
    }

    public function getDepositErrorProperty()
    {

        return $this->getDepositCountByStatus('ERROR');

    }

    public function getDepositSuccessProperty()
    {

        return $this->getDepositCountByStatus('SUCCESS');

    }

    public function getDepositPenddingProperty()
    {

        return $this->getDepositCountByStatus('PENDING');

    }
   
}