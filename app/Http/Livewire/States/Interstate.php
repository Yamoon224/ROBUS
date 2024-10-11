<?php

namespace App\Http\Livewire\States;

use App\Models\Wallets\WalletInter;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Interstate extends Component
{

    public  $startDate = "";
    public  $endDate =  "";

    public function render()
    {


        $walletUser = Auth::guard('web')->user();


        $realod = WalletInter::query();

        if ($walletUser->is_under_distributer) {
            $realod->where('wallet_inters.created_by', $walletUser->id);
        }

        if ($this->startDate && $this->endDate) {

            $realod->whereBetween('wallet_inters.created_at', [$this->startDate, $this->endDate]);
        }

        return view(
            'livewire.states.interstate',
            [
                'user_sum' => $this->WalletReloadSumAmount,
                'reload_historicals' =>   $realod->join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
                    ->where('wallet_inters.country_id', Auth::guard('web')->user()->country->id)
                    ->when(sabdy()->is_under_distributer, function ($query) {
                        return $query->where('wallet_inters.created_by', sabdy()->id);
                    })
                    ->select('wallet_inters.*')->orderBy('wallet_inters.created_at', 'desc')->paginate(15),
                'usersCountry' =>  $walletUser->country->currencie->name,
                'sum_commissions' => $this->WalletReloadSumGain,
                'realods' => $this->RealodsAdmin,
                'reloads_gain'  =>  $this->RealodsAdminGain,
            ]
        );
    }

    public function getWalletReloadSumAmountProperty()
    {

        $sumGain = WalletInter::join('wallets', 'wallet_inters.wallet_id', '=', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->when(sabdy()->is_under_distributer, function ($query) {
                return $query->where('wallet_inters.created_by', sabdy()->id);
            })
            ->where('wallet_inters.status', 'SUCCESS')
            ->select('wallet_inters.*')
            ->sum('wallet_inters.amount');

        return $sumGain;
    }

    public function getWalletReloadSumGainProperty()
    {

        $sumGain = WalletInter::join('wallets', 'wallet_inters.wallet_id', '=', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->when(sabdy()->is_under_distributer, function ($query) {
                return $query->where('wallet_inters.created_by', sabdy()->id);
            })
            ->where('wallet_inters.status', 'SUCCESS')
            ->select('wallet_inters.*')
            ->sum('wallet_inters.commissions');

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
            $reloads[$month] = WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_inters.created_by', sabdy()->id);
                })
                ->where('wallet_inters.status', 'SUCCESS')
                ->where('wallet_inters.created_at', 'like', '%-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-%')
                ->sum("wallet_inters.$field");
        }

        return array_values($reloads);
    }
}
