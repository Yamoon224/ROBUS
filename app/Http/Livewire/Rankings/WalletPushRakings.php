<?php

namespace App\Http\Livewire\Rankings;

use App\Models\Wallets\WalletPush;
use Livewire\Component;
use App\Http\Services\HomeService;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;

class WalletPushRakings extends Component
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

        $providers = Provider::where('provider_type_id', 24)->get();
       $countries = Country::whereNotNull('indecative')
            ->where('indecative', '!=', '')
            ->get();
        $classment_pushs = app(HomeService::class)
            ->getSubDistributorsWalletTransactionFilterRanking(WalletPush::class , 'wallet_pushes' , $filters);
        return view('livewire.rankings.wallet-push-rakings', [
            'classment_pushs' => $classment_pushs,
            'providers' => $providers,
            'countries' => $countries,
        ]);
    }
}
