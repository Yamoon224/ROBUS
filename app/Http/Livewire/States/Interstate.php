<?php

namespace App\Http\Livewire\States;

use App\Models\Wallets\WalletInter;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\ServiceStatisticBackend;

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
                'reload_historicals' =>   $realod->join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
                    ->where('wallet_inters.country_id', Auth::guard('web')->user()->country->id)
                    ->when(sabdy()->is_under_distributer, function ($query) {
                        return $query->where('wallet_inters.created_by', sabdy()->id);
                    })
                    ->select('wallet_inters.*')->orderBy('wallet_inters.created_at', 'desc')->paginate(15),
                'usersCountry' =>  $walletUser->country->currencie->name,
              'user_sum' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_by', 'wallet_inters.created_at', 'wallet_inters.status', 'wallet_inters.amount'),
                'sum_commissions' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_by', 'wallet_inters.created_at', 'wallet_inters.status', 'wallet_inters.commissions'),
                'realods' => app(ServiceStatisticBackend::class)->getStatistical(
                    WalletInter::class,
                    'wallet_inters.wallet_id',
                    'wallet_inters.created_by',
                    'wallet_inters.status',
                    'wallet_inters.created_at',
                    'amount'
                ),
                'reloads_gain' => app(ServiceStatisticBackend::class)->getStatistical(
                    WalletInter::class,
                    'wallet_inters.wallet_id',
                    'wallet_inters.created_by',
                    'wallet_inters.status',
                    'wallet_inters.created_at',
                    'commissions'
                ),
            ]
        );
    }

}
