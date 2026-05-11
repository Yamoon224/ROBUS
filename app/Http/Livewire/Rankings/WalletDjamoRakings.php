<?php

namespace App\Http\Livewire\Rankings;

use App\Models\Wallets\WalletDjamo;
use Livewire\Component;
use App\Http\Services\HomeService;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;

class WalletDjamoRakings extends Component
{
    public function render()
    {

        $filters = [
            'periode' => $this->periode ?? 'all',
            // 'provider_id' => $this->service ?? 'all',
            'country_id' => $this->country ?? null,
        ];

        $providers = Provider::where('provider_type_id', 25)->get();
        $countries = Country::whereNotNull('indecative')
            ->where('indecative', '!=', '')
            ->get();

        $classment_djamos = app(HomeService::class)
            ->getSubDistributorsWalletTransactionFilterRanking(WalletDjamo::class, 'wallet_djamos', $filters);
        return view('livewire.rankings.wallet-djamo-rakings', [
            'classment_djamos' => $classment_djamos,
            'providers' => $providers,
            'countries' => $countries,
        ]);
    }
}
