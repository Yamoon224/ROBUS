<?php

namespace App\Http\Livewire\States;

use App\Models\Wallets\WalletDeposit;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\ServiceStatisticBackend;

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
                'reload_historicals' =>   $realod->join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                    ->when(sabdy()->is_under_distributer, function ($query) {
                        return $query->where('wallet_deposits.created_by', sabdy()->id);
                    })
                    ->select('wallet_deposits.*')->orderBy('wallet_deposits.created_at', 'desc')->paginate(15),
                'usersCountry' =>  $walletUser->country->currencie->name,
               'user_sum' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_by', 'wallet_deposits.created_at', 'wallet_deposits.status', 'wallet_deposits.amount'),
                'sum_commissions' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_by', 'wallet_deposits.created_at', 'wallet_deposits.status', 'wallet_deposits.commissions'),
                'realods' => app(ServiceStatisticBackend::class)->getStatistical(
                    WalletDeposit::class,
                    'wallet_deposits.wallet_id',
                    'wallet_deposits.created_by',
                    'wallet_deposits.status',
                    'wallet_deposits.created_at',
                    'amount'
                ),
                'reloads_gain' => app(ServiceStatisticBackend::class)->getStatistical(
                    WalletDeposit::class,
                    'wallet_deposits.wallet_id',
                    'wallet_deposits.created_by',
                    'wallet_deposits.status',
                    'wallet_deposits.created_at',
                    'commissions'
                ),
            ]
        );
    }


}
