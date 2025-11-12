<?php

namespace App\Http\Livewire\States;

use Livewire\Component;
use App\Models\Wallets\WalletBank;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\ServiceStatisticBackend;

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
                'reload_historicals' =>   $realod->join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                    ->when(sabdy()->is_under_distributer, function ($query) {
                        return $query->where('wallet_banks.created_by', sabdy()->id);
                    })
                    ->select('wallet_banks.*')->orderBy('wallet_banks.created_at', 'desc')->paginate(15),
                'usersCountry' =>  $walletUser->country->currencie->name,
                'user_sum' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_by', 'wallet_banks.created_at', 'wallet_banks.status', 'wallet_banks.amount'),
                'sum_commissions' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_by', 'wallet_banks.created_at', 'wallet_banks.status', 'wallet_banks.commissions'),
                'realods' => app(ServiceStatisticBackend::class)->getStatistical(
                    WalletBank::class,
                    'wallet_banks.wallet_id',
                    'wallet_banks.created_by',
                    'wallet_banks.status',
                    'wallet_banks.created_at',
                    'amount'
                ),
                'reloads_gain' => app(ServiceStatisticBackend::class)->getStatistical(
                    WalletBank::class,
                    'wallet_banks.wallet_id',
                    'wallet_banks.created_by',
                    'wallet_banks.status',
                    'wallet_banks.created_at',
                    'commissions'
                ),

            ]
        );
    }


}
