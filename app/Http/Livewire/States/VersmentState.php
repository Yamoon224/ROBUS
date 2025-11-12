<?php

namespace App\Http\Livewire\States;

use Livewire\Component;
use App\Http\Services\ServiceStatisticBackend;
use App\Models\Wallets\WalletVersement;
use Illuminate\Support\Facades\Auth;

class VersmentState extends Component
{

    public  $startDate = "";
    public  $endDate =  "";

    public function render()
    {


        $walletUser = Auth::guard('web')->user();


        $realod = WalletVersement::query();

        if ($walletUser->is_under_distributer) {
            $realod->where('wallet_versements.created_by', $walletUser->id);
        }



        if ($this->startDate && $this->endDate) {

            $realod->whereBetween('wallet_versements.created_at', [$this->startDate, $this->endDate]);
        }

        $data = [];
        foreach (WalletVersement::where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }


        return view(
            'livewire.states.versment-state',
            [
                'reload_historicals' =>   $realod->join('wallets', 'wallet_versements.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                    ->when(sabdy()->is_under_distributer, function ($query) {
                        return $query->where('wallet_versements.created_by', sabdy()->id);
                    })
                    ->select('wallet_versements.*')->orderBy('wallet_versements.created_at', 'desc')->paginate(15),
                'usersCountry' =>  $walletUser->country->currencie->name,
                'user_sum' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletVersement::class, 'wallet_versements.wallet_id', 'wallet_versements.created_by', 'wallet_versements.created_at', 'wallet_versements.status', 'wallet_versements.amount'),
                'sum_commissions' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletVersement::class, 'wallet_versements.wallet_id', 'wallet_versements.created_by', 'wallet_versements.created_at', 'wallet_versements.status', 'wallet_versements.commissions'),
                'realods' => app(ServiceStatisticBackend::class)->getStatistical(
                    WalletVersement::class,
                    'wallet_versements.wallet_id',
                    'wallet_versements.created_by',
                    'wallet_versements.status',
                    'wallet_versements.created_at',
                    'amount'
                ),
                'reloads_gain' => app(ServiceStatisticBackend::class)->getStatistical(
                    WalletVersement::class,
                    'wallet_versements.wallet_id',
                    'wallet_versements.created_by',
                    'wallet_versements.status',
                    'wallet_versements.created_at',
                    'commissions'
                ),
            ]
        );
    }
 
}
