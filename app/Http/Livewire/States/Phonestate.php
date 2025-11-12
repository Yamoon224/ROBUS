<?php

namespace App\Http\Livewire\States;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletPhoneCredit;
use App\Http\Services\ServiceStatisticBackend;

class Phonestate extends Component
{
    public  $startDate = "";
    public  $endDate =  "";

    public function render()
    {


        $walletUser = Auth::guard('web')->user();


        $realod = WalletPhoneCredit::query();
        if ($walletUser->is_under_distributer) {
            $realod->where('wallet_phone_credits.created_by', $walletUser->id);
        }


        if ($this->startDate && $this->endDate) {

            $realod->whereBetween('wallet_phone_credits.created_at', [$this->startDate, $this->endDate]);
        }


        return view(
            'livewire.states.phonestate',
            [
                'reload_historicals' =>   $realod->join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                    ->when(sabdy()->is_under_distributer, function ($query) {
                        return $query->where('wallet_phone_credits.created_by', sabdy()->id);
                    })
                    ->select('wallet_phone_credits.*')->orderBy('wallet_phone_credits.created_at', 'desc')->paginate(15),
                'usersCountry' =>  $walletUser->country->currencie->name,
          'user_sum' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_by', 'wallet_phone_credits.created_at', 'wallet_phone_credits.status', 'wallet_phone_credits.amount'),
                'sum_commissions' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_by', 'wallet_phone_credits.created_at', 'wallet_phone_credits.status', 'wallet_phone_credits.commissions'),
                'realods' => app(ServiceStatisticBackend::class)->getStatistical(
                    WalletPhoneCredit::class,
                    'wallet_phone_credits.wallet_id',
                    'wallet_phone_credits.created_by',
                    'wallet_phone_credits.status',
                    'wallet_phone_credits.created_at',
                    'amount'
                ),
                'reloads_gain' => app(ServiceStatisticBackend::class)->getStatistical(
                    WalletPhoneCredit::class,
                    'wallet_phone_credits.wallet_id',
                    'wallet_phone_credits.created_by',
                    'wallet_phone_credits.status',
                    'wallet_phone_credits.created_at',
                    'commissions'
                ),


            ]
        );
    }


}
