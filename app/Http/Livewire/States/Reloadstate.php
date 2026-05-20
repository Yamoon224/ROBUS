<?php

namespace App\Http\Livewire\States;

use Livewire\Component;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\ServiceStatisticBackend;

class Reloadstate extends Component
{
    public  $startDate = "";
    public  $endDate =  "";

    public function render()
    {


        $walletUser = Auth::guard('web')->user();


        $realod = WalletReload::query();
        if ($walletUser->is_under_distributer) {
            $realod->where('wallet_reloads.created_by', $walletUser->id); 
        }

        


            if ($this->startDate && $this->endDate) {

                $realod->whereBetween('wallet_reloads.created_at', [$this->startDate, $this->endDate]);
            }

         

            return view(
                'livewire.states.reloadstate',
                [
                    
                    'reload_historicals' =>   $realod->join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
                        ->when(sabdy()->is_under_distributer, function ($query) {
                            return $query->where('wallet_reloads.created_by', sabdy()->id);
                        })
                        ->when(sabdy()->is_com_zone, function ($query) {
                            return $query->whereIn('wallets.sid_user_id', function ($subQuery) {
                                $subQuery->select('id')->from('sid_users')->where('com_zone_id', sabdy()->id);
                            });
                        })
                        ->select('wallet_reloads.*')->orderBy('wallet_reloads.created_at', 'desc')->paginate(15),
                    'usersCountry' =>  $walletUser->country->currencie->name,
                  'user_sum' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletReload::class, 'wallet_reloads.wallet_id', 'wallet_reloads.created_by', 'wallet_reloads.created_at', 'wallet_reloads.status', 'wallet_reloads.amount'),
                'sum_commissions' => app(ServiceStatisticBackend::class)->getWalletSumGain(WalletReload::class, 'wallet_reloads.wallet_id', 'wallet_reloads.created_by', 'wallet_reloads.created_at', 'wallet_reloads.status', 'wallet_reloads.commissions'),
                'realods' => app(ServiceStatisticBackend::class)->getStatistical(
                    WalletReload::class,
                    'wallet_reloads.wallet_id',
                    'wallet_reloads.created_by',
                    'wallet_reloads.status',
                    'wallet_reloads.created_at',
                    'amount'
                ),
                'reloads_gain' => app(ServiceStatisticBackend::class)->getStatistical(
                    WalletReload::class,
                    'wallet_reloads.wallet_id',
                    'wallet_reloads.created_by',
                    'wallet_reloads.status',
                    'wallet_reloads.created_at',
                    'commissions'
                ),
                ]
            );
    
    }


  
}
