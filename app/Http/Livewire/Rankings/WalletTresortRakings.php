<?php

namespace App\Http\Livewire\Rankings;

use App\Models\Wallets\WalletTresortMoney;
use Livewire\Component;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use App\Http\Services\HomeService;
class WalletTresortRakings extends Component
{
    public function render()
    {

        $filters = [
            'periode' => $this->periode ?? 'all',
            // 'provider_id' => $this->service ?? 'all',
            'country_id' => $this->country ?? null,
        ];

        $providers = Provider::where('provider_type_id', 23)->get();
        $countries = Country::whereNotNull('indecative')
            ->where('indecative', '!=', '')
            ->get();

        $classment_tresortMoneys = app(HomeService::class)
            ->getSubDistributorsWalletTransactionFilterRanking(WalletTresortMoney::class, 'wallet_tresort_money', $filters);
            
        return view('livewire.rankings.wallet-tresort-rakings', [
            'classment_tresortMoneys' => $classment_tresortMoneys,
            'providers' => $providers,
            'countries' => $countries,
        ]);
    }
}
