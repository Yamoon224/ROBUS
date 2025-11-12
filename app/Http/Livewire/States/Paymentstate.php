<?php

namespace App\Http\Livewire\States;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletPayment;
use App\Http\Services\ServiceStatisticBackend;

class Paymentstate extends Component
{

    public  $startDate = "";
    public  $endDate =  "";

    public function render()
    {


        $walletUser = Auth::guard('web')->user();


        $realod = WalletPayment::query();

        if ($walletUser->is_under_distributer) {
            $realod->where('wallet_payments.created_by', $walletUser->id);
        }


        if ($this->startDate && $this->endDate) {

            $realod->whereBetween('wallet_payments.created_at', [$this->startDate, $this->endDate]);
        }


        return view(
            'livewire.states.paymentstate',
            [
                'reload_historicals' =>   $realod->join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                    ->when(sabdy()->is_under_distributer, function ($query) {
                        return $query->where('wallet_payments.created_by', sabdy()->id);
                    })
                    ->select('wallet_payments.*')->orderBy('wallet_payments.created_at', 'desc')->paginate(15),
                'usersCountry' =>  $walletUser->country->currencie->name,
                'sum_commissions' => $this->WalletReloadSumGain,
                'realods' => $this->RealodsAdmin,
                'reloads_gain'  =>  $this->RealodsAdminGain,

                 'user_sum' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_by', 'wallet_payments.created_at', 'wallet_payments.status', 'wallet_payments.amount'),
                'sum_commissions' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_by', 'wallet_payments.created_at', 'wallet_payments.status', 'wallet_payments.commissions'),
                'realods' => app(ServiceStatisticBackend::class)->getStatistical(
                    WalletPayment::class,
                    'wallet_payments.wallet_id',
                    'wallet_payments.created_by',
                    'wallet_payments.status',
                    'wallet_payments.created_at',
                    'amount'
                ),
                'reloads_gain' => app(ServiceStatisticBackend::class)->getStatistical(
                    WalletPayment::class,
                    'wallet_payments.wallet_id',
                    'wallet_payments.created_by',
                    'wallet_payments.status',
                    'wallet_payments.created_at',
                    'commissions'
                ),
            ]
        );
    }


 
}
