<?php

namespace App\Http\Livewire\Rankings;

use Livewire\Component;
use App\Http\Services\HomeService;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use App\Models\Wallets\WalletPhoneCredit;

class WalletCreditPhoneRankings extends Component
{

    public $periode = 'year';
    public $service = 'all';
    public $country = 'all';

    public function render()
    {

        $filters = [
            'periode' => $this->periode ?? 'all',
            'country_id' => $this->country ?? null,
        ];

        $providers = Provider::where('provider_type_id', 6)->get();
       $countries = Country::whereNotNull('indecative')
            ->where('indecative', '!=', '')
            ->get();
        $classment_transactions = app(HomeService::class)
            ->getSubDistributorsWalletTransactionFilterRanking(WalletPhoneCredit::class , 'wallet_phone_credits' , $filters);

        return view('livewire.rankings.wallet-credit-phone-rankings', [
            'classment_transactions' => $classment_transactions,
            'providers' => $providers,
            'countries' => $countries,
        ]);
    }
    

}
