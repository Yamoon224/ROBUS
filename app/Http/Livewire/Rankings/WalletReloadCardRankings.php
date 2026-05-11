<?php

namespace App\Http\Livewire\Rankings;

use Livewire\Component;
use App\Http\Services\HomeService;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use App\Models\Wallets\WalletDeposit;

class WalletReloadCardRankings extends Component
{
     public $periode = 'year';
    public $service = 'all';
    public $country = 'all';

    public function render()
    {

        $filters = [
            'periode' => $this->periode ?? 'all',
            // 'provider_id' => $this->service ?? 'all',
            'country_id' => $this->country ?? null,
        ];

        $providers = Provider::where('provider_type_id', 5)->get();
       $countries = Country::whereNotNull('indecative')
            ->where('indecative', '!=', '')
            ->get();
        $classment_transactions = app(HomeService::class)
            ->getSubDistributorsWalletTransactionFilterRanking(WalletDeposit::class , 'wallet_deposits' , $filters);

        return view('livewire.rankings.wallet-reload-card-rankings', [
            'classment_transactions' => $classment_transactions,
            'providers' => $providers,
            'countries' => $countries,
        ]);
    }

}
