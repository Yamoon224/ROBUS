<?php

namespace App\Http\Livewire\States;

use Livewire\Component;
use App\Http\Services\ServiceStatisticBackend;
use App\Models\Wallets\WalletDjamo;
use Illuminate\Support\Facades\Auth;

class DjamoState extends Component
{


    public  $startDate = "";
    public  $endDate =  "";

    public function render()
    {


        $walletUser = Auth::guard('web')->user();


        $realod = WalletDjamo::query();

        if ($walletUser->is_under_distributer) {
            $realod->where('wallet_djamos.created_by', $walletUser->id);
        }



        if ($this->startDate && $this->endDate) {

            $realod->whereBetween('wallet_djamos.created_at', [$this->startDate, $this->endDate]);
        }

        $data = [];
        foreach (WalletDjamo::where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }


        return view(
            'livewire.states.djamo-state',
            [
                'reload_historicals' =>   $realod->join('wallets', 'wallet_djamos.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                    ->when(sabdy()->is_under_distributer, function ($query) {
                        return $query->where('wallet_djamos.created_by', sabdy()->id);
                    })
                    ->when(sabdy()->is_com_zone, function ($query) {
                        return $query->whereIn('wallets.sid_user_id', function ($subQuery) {
                            $subQuery->select('id')->from('sid_users')->where('com_zone_id', sabdy()->id);
                        });
                    })
                    ->select('wallet_djamos.*')->orderBy('wallet_djamos.created_at', 'desc')->paginate(15),
                'usersCountry' =>  $walletUser->country->currencie->name,
                'user_sum' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletDjamo::class, 'wallet_djamos.wallet_id', 'wallet_djamos.created_by', 'wallet_djamos.created_at', 'wallet_djamos.status', 'wallet_djamos.amount'),
                'sum_commissions' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletDjamo::class, 'wallet_djamos.wallet_id', 'wallet_djamos.created_by', 'wallet_djamos.created_at', 'wallet_djamos.status', 'wallet_djamos.commissions'),
                'realods' => app(ServiceStatisticBackend::class)->getStatistical(
                    WalletDjamo::class,
                    'wallet_djamos.wallet_id',
                    'wallet_djamos.created_by',
                    'wallet_djamos.status',
                    'wallet_djamos.created_at',
                    'amount'
                ),
                'reloads_gain' => app(ServiceStatisticBackend::class)->getStatistical(
                    WalletDjamo::class,
                    'wallet_djamos.wallet_id',
                    'wallet_djamos.created_by',
                    'wallet_djamos.status',
                    'wallet_djamos.created_at',
                    'commissions'
                ),
            ]
        );
    }


}
