<?php

namespace App\Http\Livewire\States;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletPhoneCredit;

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
                'user_sum' => $this->WalletReloadSumAmount,
                'reload_historicals' =>   $realod->join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                    ->when(sabdy()->is_under_distributer, function ($query) {
                        return $query->where('wallet_phone_credits.created_by', sabdy()->id);
                    })
                    ->select('wallet_phone_credits.*')->orderBy('wallet_phone_credits.created_at', 'desc')->paginate(15),
                'usersCountry' =>  $walletUser->country->currencie->name,
                'sum_commissions' => $this->WalletReloadSumGain,
                'realods' => $this->RealodsAdmin,
                'reloads_gain'  =>  $this->RealodsAdminGain,
            ]
        );
    }

    public function getWalletReloadSumAmountProperty()
    {

        $sumGain = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', '=', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->when(sabdy()->is_under_distributer, function ($query) {
                return $query->where('wallet_phone_credits.created_by', sabdy()->id);
            })
            ->where('wallet_phone_credits.status', 'SUCCESS')
            ->select('wallet_phone_credits.*')
            ->sum('wallet_phone_credits.amount');

        return $sumGain;
    }


    public function getWalletReloadSumGainProperty()
    {

        $sumGain = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', '=', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->when(sabdy()->is_under_distributer, function ($query) {
                return $query->where('wallet_phone_credits.created_by', sabdy()->id);
            })
            ->where('wallet_phone_credits.status', 'SUCCESS')
            ->select('wallet_phone_credits.*')
            ->sum('wallet_phone_credits.commissions');

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
            $reloads[$month] = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_phone_credits.created_by', sabdy()->id);
                })
                ->where('wallet_phone_credits.status', 'SUCCESS')
                ->where('wallet_phone_credits.created_at', 'like', '%-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-%')
                ->sum("wallet_phone_credits.$field");
        }

        return array_values($reloads);
    }
}
