<?php

namespace App\Http\Livewire\States;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletTransaction;

class Transferstate extends Component
{

    public  $startDate = "";
    public  $endDate =  "";

    public function render()
    {


        $walletUser = Auth::guard('web')->user();


        $realod = WalletTransaction::query();

        if ($walletUser->is_under_distributer) {
            $realod->where('wallet_transactions.created_by', $walletUser->id);
        }



        if ($this->startDate && $this->endDate) {

            $realod->whereBetween('wallet_transactions.created_at', [$this->startDate, $this->endDate]);
        }

        $data = [];
        foreach (WalletTransaction::where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }


        return view(
            'livewire.states.transferstate',
            [
                'user_sum' => $this->WalletReloadSumAmount,
                'reload_historicals' =>   $realod->join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                    ->when(sabdy()->is_under_distributer, function ($query) {
                        return $query->where('wallet_transactions.created_by', sabdy()->id);
                    })
                    ->select('wallet_transactions.*')->orderBy('wallet_transactions.created_at', 'desc')->paginate(15),
                'usersCountry' =>  $walletUser->country->currencie->name,
                'sum_commissions' => $this->WalletReloadSumGain,
                'realods' => $this->RealodsAdmin,
                'reloads_gain'  =>  $this->RealodsAdminGain,
            ]
        );
    }

    public function getWalletReloadSumAmountProperty()
    {

        $sumGain = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', '=', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->when(sabdy()->is_under_distributer, function ($query) {
                return $query->where('wallet_transactions.created_by', sabdy()->id);
            })
            ->where('wallet_transactions.status', 'SUCCESS')
            ->select('wallet_transactions.*')
            ->sum('wallet_transactions.amount');

        return $sumGain;
    }


    public function getWalletReloadSumGainProperty()
    {

        $sumGain = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', '=', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->when(sabdy()->is_under_distributer, function ($query) {
                return $query->where('wallet_transactions.created_by', sabdy()->id);
            })
            ->where('wallet_transactions.status', 'SUCCESS')
            ->select('wallet_transactions.*')
            ->sum('wallet_transactions.commissions');

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
            $reloads[$month] = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_transactions.created_by', sabdy()->id);
                })
                ->where('wallet_transactions.status', 'SUCCESS')
                ->where('wallet_transactions.created_at', 'like', '%-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-%')
                ->sum("wallet_transactions.$field");
        }

        return array_values($reloads);
    }
}
