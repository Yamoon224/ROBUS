<?php

namespace App\Http\Livewire\TransactionWeb;

use App\Models\Costs\DepositCost;
use App\Models\Providers\Provider;
use Livewire\Component;

class ReloadCarte extends Component
{
    public function render()
    {

        $deposit_cost = DepositCost::whereIsWeb(true)->first();

        $percent_carte = $deposit_cost->percent * 100;

        $percent_robus = $deposit_cost->percent_admin * 100;

        $carte_reload = Provider::whereStatus(true)->where('provider_type_id',  5)
        ->orderBy('name', 'asc')->get();
        
        return view('livewire.transaction-web.reload-carte', [
            'carte_percent' => $percent_carte,
            'robus_percent' => $percent_robus,
            'carte_reloads' => $carte_reload,
        ]);
    }
}
