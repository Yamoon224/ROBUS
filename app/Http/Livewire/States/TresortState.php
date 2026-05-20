<?php

namespace App\Http\Livewire\States;

use Livewire\Component;
use App\Http\Services\ServiceStatisticBackend;
use App\Models\Wallets\WalletTresortMoney;
use Illuminate\Support\Facades\Auth;

class TresortState extends Component
{


    public  $startDate = "";
    public  $endDate =  "";

    public function render()
    {


        $walletUser = Auth::guard('web')->user();


        $realod = WalletTresortMoney::query();

        if ($walletUser->is_under_distributer) {
            $realod->where('wallet_tresort_money.created_by', $walletUser->id);
        }



        if ($this->startDate && $this->endDate) {

            $realod->whereBetween('wallet_tresort_money.created_at', [$this->startDate, $this->endDate]);
        }

        $data = [];
        foreach (WalletTresortMoney::where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }


        return view(
            'livewire.states.tresort-state',
            [
                'reload_historicals' =>   $realod->join('wallets', 'wallet_tresort_money.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                    ->when(sabdy()->is_under_distributer, function ($query) {
                        return $query->where('wallet_tresort_money.created_by', sabdy()->id);
                    })
                    ->when(sabdy()->is_com_zone, function ($query) {
                        return $query->whereIn('wallets.sid_user_id', function ($subQuery) {
                            $subQuery->select('id')->from('sid_users')->where('com_zone_id', sabdy()->id);
                        });
                    })
                    ->select('wallet_tresort_money.*')->orderBy('wallet_tresort_money.created_at', 'desc')->paginate(15),
                'usersCountry' =>  $walletUser->country->currencie->name,
                'user_sum' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletTresortMoney::class, 'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_by', 'wallet_tresort_money.created_at', 'wallet_tresort_money.status', 'wallet_tresort_money.amount'),
                'sum_commissions' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletTresortMoney::class, 'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_by', 'wallet_tresort_money.created_at', 'wallet_tresort_money.status', 'wallet_tresort_money.commissions'),
                'realods' => app(ServiceStatisticBackend::class)->getStatistical(
                    WalletTresortMoney::class,
                    'wallet_tresort_money.wallet_id',
                    'wallet_tresort_money.created_by',
                    'wallet_tresort_money.status',
                    'wallet_tresort_money.created_at',
                    'amount'
                ),
                'reloads_gain' => app(ServiceStatisticBackend::class)->getStatistical(
                    WalletTresortMoney::class,
                    'wallet_tresort_money.wallet_id',
                    'wallet_tresort_money.created_by',
                    'wallet_tresort_money.status',
                    'wallet_tresort_money.created_at',
                    'commissions'
                ),
            ]
        );
    }

 
}
