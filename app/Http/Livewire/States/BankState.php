<?php

namespace App\Http\Livewire\States;

use Livewire\Component;
use App\Models\Wallets\WalletBank;
use Illuminate\Support\Facades\Auth;

class Bankstate extends Component
{

    public  $startDate = "";
    public  $endDate =  "";

    public function render()
    {


        $walletUser = Auth::guard('web')->user();


        $realod = WalletBank::query();

        if ($walletUser->is_under_distributer) {
            $realod->where('wallet_banks.created_by', $walletUser->id); 
        }

        if ($this->startDate && $this->endDate) {

            $realod->whereBetween('wallet_banks.created_at', [$this->startDate, $this->endDate]);
        }


        return view(
            'livewire.states.bankstate',
            [
                'user_sum' =>  $this->WalletReloadSumAmount,
                'reload_historicals' =>   $realod->join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                    ->when(sabdy()->is_under_distributer, function ($query) {
                        return $query->where('wallet_banks.created_by', sabdy()->id);
                    })
                    ->select('wallet_banks.*')->orderBy('wallet_banks.created_at', 'desc')->paginate(15),
                'usersCountry' =>  $walletUser->country->currencie->name,
                'sum_commissions' => $this->WalletReloadSumGain,
                'realods' => $this->RealodsAdmin,
                'reloads_gain'  =>  $this->RealodsAdminGain,
            ]
        );
    }

 public function getWalletReloadSumAmountProperty()
    {

        $sumGain = WalletBank::join('wallets', 'wallet_banks.wallet_id', '=', 'wallets.id')
        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
        ->when(sabdy()->is_under_distributer, function ($query) {
            return $query->where('wallet_banks.created_by', sabdy()->id);
        })
        ->where('wallet_banks.status', 'SUCCESS')
        ->select('wallet_banks.*')
        ->sum('wallet_banks.amount'); 

        return $sumGain;
    }

    public function getWalletReloadSumGainProperty()
    {

        $sumGain = WalletBank::join('wallets', 'wallet_banks.wallet_id', '=', 'wallets.id')
        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
        ->when(sabdy()->is_under_distributer, function ($query) {
            return $query->where('wallet_banks.created_by', sabdy()->id);
        })
        ->where('wallet_banks.status', 'SUCCESS')
        ->select('wallet_banks.*')
        ->sum('wallet_banks.commissions'); 

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
            $reloads[$month] = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_banks.created_by', sabdy()->id);
                })
                ->where('wallet_banks.status', 'SUCCESS')
                ->where('wallet_banks.created_at', 'like', '%-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-%')
                ->sum("wallet_banks.$field"); 
        }

        return array_values($reloads);
    }
}