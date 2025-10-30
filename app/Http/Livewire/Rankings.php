<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Http\Services\HomeService;
use App\Models\Providers\Provider;
use App\Models\Wallets\WalletTransaction;

class Rankings extends Component
{
    public $periode = 'month';
    public $service = 'all';

    public function render()
    {

        $filters = $this->buildFilters();
         $providers = Provider::all();
        $classment_transactions = app(HomeService::class)
            ->getSubDistributorsWalletTransactionRanking(WalletTransaction::class, $filters);

        return view('livewire.rankings', [
            'classment_transactions' => $classment_transactions,
            'providers' => $providers,
        ]);
    }

     private function buildFilters()
    {
        $now = now();
        $filters = [];

        // 📅 Filtres de période
        switch ($this->periode) {
            case 'today':
                $filters['date_start'] = $now->startOfDay();
                $filters['date_end'] = $now->endOfDay();
                break;
            case 'week':
                $filters['date_start'] = $now->startOfWeek();
                $filters['date_end'] = $now->endOfWeek();
                break;
            case 'month':
                $filters['date_start'] = $now->startOfMonth();
                $filters['date_end'] = $now->endOfMonth();
                break;
            case 'year':
                $filters['date_start'] = $now->startOfYear();
                $filters['date_end'] = $now->endOfYear();
                break;
        }

        // 🔍 Filtre service (provider)
        if (!empty($this->service) && $this->service !== 'all') {
            $filters['provider_id'] = $this->service;
        }

        return $filters;
    }
}
