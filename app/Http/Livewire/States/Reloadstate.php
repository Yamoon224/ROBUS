<?php

namespace App\Http\Livewire\States;

use Livewire\Component;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;

class Reloadstate extends Component
{
    public  $startDate = "";
    public  $endDate =  "";

    public function render()
    {


        $walletUser = Auth::guard('web')->user();


        $realod = WalletReload::query();
        if ($walletUser->is_under_distributer) {
            $realod->where('wallet_reloads.created_by', $walletUser->id); 
        }

        


            if ($this->startDate && $this->endDate) {

                $realod->whereBetween('wallet_reloads.created_at', [$this->startDate, $this->endDate]);
            }

         

            return view(
                'livewire.states.reloadstate',
                [
                    'user_sum' => $this->WalletReloadSumAmount,
                    'reload_historicals' =>   $realod->join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                        ->when(sabdy()->is_under_distributer, function ($query) {
                            return $query->where('wallet_reloads.created_by', sabdy()->id);
                        })
                        ->select('wallet_reloads.*')->orderBy('wallet_reloads.created_at', 'desc')->paginate(15),
                    'usersCountry' =>  $walletUser->country->currencie->name,
                    'sum_commissions' => $this->WalletReloadSumGain,
                    'realods' => $this->RealodsAdmin,
                    'reloads_gain'  =>  $this->RealodsAdminGain,
                ]
            );
    
    }


    public function getWalletReloadSumAmountProperty()
    {

        $sumGain = WalletReload::join('wallets', 'wallet_reloads.wallet_id', '=', 'wallets.id')
        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
        ->when(sabdy()->is_under_distributer, function ($query) {
            return $query->where('wallet_reloads.created_by', sabdy()->id);
        })
        ->where('wallet_reloads.status', 'SUCCESS')
        ->select('wallet_reloads.*')
        ->sum('wallet_reloads.amount'); 

        return $sumGain;
    }

    public function getWalletReloadSumGainProperty()
    {

        $sumGain = WalletReload::join('wallets', 'wallet_reloads.wallet_id', '=', 'wallets.id')
        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
        ->when(sabdy()->is_under_distributer, function ($query) {
            return $query->where('wallet_reloads.created_by', sabdy()->id);
        })
        ->where('wallet_reloads.status', 'SUCCESS')
        ->select('wallet_reloads.*')
        ->sum('wallet_reloads.commissions'); 

        return $sumGain;
    }


    public function getRealodsAdminProperty()
    {
        return $this->getReloads('amount');
    }

    public function getRealodsAdminGainProperty()
    {
        return $this->getReloads('commissions');
    }

    private function getReloads($field)
    {
        $reloads = [];
        $userCountryId = Auth::guard('web')->user()->country->id;

        for ($month = 1; $month <= 12; $month++) {
            $reloads[$month] = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_reloads.created_by', sabdy()->id);
                })
                ->where('wallet_reloads.status', 'SUCCESS')
                ->where('wallet_reloads.created_at', 'like', '%-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-%')
                ->sum("wallet_reloads.$field"); 
        }

        return array_values($reloads);
    }
  
}