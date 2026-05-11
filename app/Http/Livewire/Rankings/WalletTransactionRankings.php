<?php

namespace App\Http\Livewire\Rankings;

use Livewire\Component;
use App\Http\Services\HomeService;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use App\Models\Wallets\WalletTransaction;

class WalletTransactionRankings extends Component
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

        $providers = Provider::where('provider_type_id', 1)->get();
        $countries = Country::whereNotNull('indecative')
            ->where('indecative', '!=', '')
            ->get();

        $classment_transactions = app(HomeService::class)
            ->getSubDistributorsWalletTransactionFilterRanking(WalletTransaction::class, 'wallet_transactions', $filters);

        return view('livewire.rankings.wallet-transaction-rankings', [
            'classment_transactions' => $classment_transactions,
            'providers' => $providers,
            'countries' => $countries,
        ]);
    }
}
