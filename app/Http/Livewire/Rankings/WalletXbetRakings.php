<?php

namespace App\Http\Livewire\Rankings;

use App\Models\Wallets\WalletXbet;
use Livewire\Component;
use App\Http\Services\HomeService;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;

class WalletXbetRakings extends Component
{
    public function render()
    {


        $filters = [
            'periode' => $this->periode ?? 'all',
            // 'provider_id' => $this->service ?? 'all',
            'country_id' => $this->country ?? null,
        ];

        $providers = Provider::where('provider_type_id', 13)->get();
        $countries = Country::whereNotNull('indecative')
            ->where('indecative', '!=', '')
            ->get();

        $classment_xbets = app(HomeService::class)
            ->getSubDistributorsWalletTransactionFilterRanking(WalletXbet::class, 'wallet_xbets', $filters);

        return view('livewire.rankings.wallet-xbet-rakings' , compact('classment_xbets' , 'providers' , 'countries'));
    }
}
