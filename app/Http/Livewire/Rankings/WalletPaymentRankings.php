<?php

namespace App\Http\Livewire\Rankings;

use Livewire\Component;
use App\Http\Services\HomeService;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use App\Models\Wallets\WalletPayment;

class WalletPaymentRankings extends Component
{

    public $periode = 'year';
    public $service = 'all';
    public $country = 'all';

    public function render()
    {

        $filters = [
            'periode' => $this->periode ?? 'all',
            // 'provider_id' => $this->service ?? 'all', // supprimé pour afficher tous les providers
            'country_id' => $this->country ?? null,
        ];

        $providers = Provider::where('provider_type_id', 2)->get();
        $countries = Country::whereNotNull('indecative')
            ->where('indecative', '!=', '')
            ->get();

        $classment_transactions = app(HomeService::class)
            ->getSubDistributorsWalletTransactionFilterRanking(WalletPayment::class, 'wallet_payments', $filters);

            return view('livewire.rankings.wallet-payment-rankings', [
            'classment_transactions' => $classment_transactions,
            'providers' => $providers,
            'countries' => $countries,
        ]);
    }
}
