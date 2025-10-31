<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Http\Services\HomeService;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use App\Models\Wallets\WalletTransaction;

class Rankings extends Component
{


    public function render()
    {

        $classment_transactions = app(HomeService::class)
            ->getSubDistributorsWalletTransactionFiveRanking(WalletTransaction::class);

        return view('livewire.rankings', [
            'classment_transactions' => $classment_transactions,
            'classment_reloadCards' => $classment_transactions,
            'classment_paiements' => $classment_transactions,
            'classment_virements' => $classment_transactions,
            'classment_creditPhone' => $classment_transactions,
            'classment_SendBank' => $classment_transactions,
            'classment_Inters' => $classment_transactions
        ]);
    }
}
