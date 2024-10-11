<?php

namespace App\Http\Livewire\States;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletPayment;

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
                'user_sum' => $this->WalletReloadSumAmount,
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
            ]
        );
    }


    public function getWalletReloadSumAmountProperty()
    {

        $sumGain = WalletPayment::join('wallets', 'wallet_payments.wallet_id', '=', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->when(sabdy()->is_under_distributer, function ($query) {
                return $query->where('wallet_payments.created_by', sabdy()->id);
            })
            ->where('wallet_payments.status', 'SUCCESS')
            ->select('wallet_payments.*')
            ->sum('wallet_payments.amount');

        return $sumGain;
    }

    public function getWalletReloadSumGainProperty()
    {

        $sumGain = WalletPayment::join('wallets', 'wallet_payments.wallet_id', '=', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->when(sabdy()->is_under_distributer, function ($query) {
                return $query->where('wallet_payments.created_by', sabdy()->id);
            })
            ->where('wallet_payments.status', 'SUCCESS')
            ->select('wallet_payments.*')
            ->sum('wallet_payments.commissions');

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
            $reloads[$month] = WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_payments.created_by', sabdy()->id);
                })
                ->where('wallet_payments.status', 'SUCCESS')
                ->where('wallet_payments.created_at', 'like', '%-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-%')
                ->sum("wallet_payments.$field");
        }

        return array_values($reloads);
    }
}
