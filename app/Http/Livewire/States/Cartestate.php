<?php

namespace App\Http\Livewire\States;

use App\Models\Wallets\WalletDeposit;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Cartestate extends Component
{


    public  $startDate = "";
    public  $endDate =  "";

    public function render()
    {


        $walletUser = Auth::guard('web')->user();


        $realod = WalletDeposit::query();
        if ($walletUser->is_under_distributer) {
            $realod->where('wallet_deposits.created_by', $walletUser->id);
        }

        if ($this->startDate && $this->endDate) {

            $realod->whereBetween('wallet_deposits.created_at', [$this->startDate, $this->endDate]);
        }

      

        return view(
            'livewire.states.cartestate',
            [
                'user_sum' => $this->WalletReloadSumAmount,
                'reload_historicals' =>   $realod->join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                    ->when(sabdy()->is_under_distributer, function ($query) {
                        return $query->where('wallet_deposits.created_by', sabdy()->id);
                    })
                    ->select('wallet_deposits.*')->orderBy('wallet_deposits.created_at', 'desc')->paginate(15),
                'usersCountry' =>  $walletUser->country->currencie->name,
                'sum_commissions' => $this->WalletReloadSumGain,
                'realods' => $this->RealodsAdmin,
                'reloads_gain'  =>  $this->RealodsAdminGain,
            ]
        );
    }

    public function getWalletReloadSumAmountProperty()
    {

        $sumGain = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', '=', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->when(sabdy()->is_under_distributer, function ($query) {
                return $query->where('wallet_deposits.created_by', sabdy()->id);
            })
            ->where('wallet_deposits.status', 'SUCCESS')
            ->select('wallet_deposits.*')
            ->sum('wallet_deposits.amount'); 
    
        return $sumGain;
    }


    public function getWalletReloadSumGainProperty()
    {

        $sumGain = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', '=', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->when(sabdy()->is_under_distributer, function ($query) {
                return $query->where('wallet_deposits.created_by', sabdy()->id);
            })
            ->where('wallet_deposits.status', 'SUCCESS')
            ->select('wallet_deposits.*')
            ->sum('wallet_deposits.commissions'); 
    
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
            $reloads[$month] = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_deposits.created_by', sabdy()->id);
                })
                ->where('wallet_deposits.status', 'SUCCESS')
                ->where('wallet_deposits.created_at', 'like', '%-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-%')
                ->sum("wallet_deposits.$field"); 
        }

        return array_values($reloads);
    }

}
