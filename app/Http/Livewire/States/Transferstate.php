<?php

namespace App\Http\Livewire\States;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletTransaction;
use App\Http\Services\ServiceStatisticBackend;

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
                'reload_historicals' =>   $realod->join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                    ->when(sabdy()->is_under_distributer, function ($query) {
                        return $query->where('wallet_transactions.created_by', sabdy()->id);
                    })
                    ->select('wallet_transactions.*')->orderBy('wallet_transactions.created_at', 'desc')->paginate(15),
                'usersCountry' =>  $walletUser->country->currencie->name,
                'user_sum' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_by', 'wallet_transactions.created_at', 'wallet_transactions.status', 'wallet_transactions.amount'),
                'sum_commissions' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_by', 'wallet_transactions.created_at', 'wallet_transactions.status', 'wallet_transactions.commissions'),
                'realods' => app(ServiceStatisticBackend::class)->getStatistical(
                    WalletTransaction::class,
                    'wallet_transactions.wallet_id',
                    'wallet_transactions.created_by',
                    'wallet_transactions.status',
                    'wallet_transactions.created_at',
                    'amount'
                ),
                'reloads_gain' => app(ServiceStatisticBackend::class)->getStatistical(
                    WalletTransaction::class,
                    'wallet_transactions.wallet_id',
                    'wallet_transactions.created_by',
                    'wallet_transactions.status',
                    'wallet_transactions.created_at',
                    'commissions'
                ),
            ]
        );
    }

}
