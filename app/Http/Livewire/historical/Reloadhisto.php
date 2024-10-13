<?php

namespace App\Http\Livewire\Historical;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;

class Reloadhisto extends Component
{
    use WithPagination;

    protected $paginationTheme = "bootstrap";

    public $searchRealodHistorical = "";
    public $statusFilter = "";
    public $startDate = "";
    public $endDate = "";

    public function render()
    {
        $walletUser = Auth::guard('web')->user();
        $realodQuery = WalletReload::query();

        // Filtrer par utilisateur
        if ($walletUser->is_under_distributer) {
            $realodQuery->where('created_by', $walletUser->id);
        }

        // Filtre par période de dates
        if ($this->startDate && $this->endDate) {
            $realodQuery->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        // Recherche textuelle
        if (!empty($this->searchRealodHistorical)) {
            $this->resetPage(); // Réinitialiser la pagination lors d'une recherche
            $realodQuery->orWhereHas('paymentReload', function ($query) {
                $query->where('operator_transaction_name', 'like', '%' . $this->searchRealodHistorical . '%')
                      ->orWhere('transaction_id', 'like', '%' . $this->searchRealodHistorical . '%')
                      ->orWhere('trans_comp_id', 'like', '%' . $this->searchRealodHistorical . '%');
            })
            ->orWhere('amount', 'like', '%' . $this->searchRealodHistorical . '%')
            ->orWhere('phone', 'like', '%' . $this->searchRealodHistorical . '%')
            ->orWhere('account_wallet', 'like', '%' . $this->searchRealodHistorical . '%');
        }

        // Filtre par statut
        if (!empty($this->statusFilter)) {
            $realodQuery->where('status', $this->statusFilter);
        }

        // Obtenir les résultats paginés
        $reloadHistoricals = $realodQuery->orderBy('created_at', 'desc')->paginate(15);

        return view('livewire.historical.reloadhisto', [
            'user' => $this->ReloadsAllDays, // Propriété calculée
            'reload_historicals' => $reloadHistoricals,
            'usersCountry' => $walletUser->country->currencie->name,
        ]);
    }

    public function getReloadsAllDaysProperty()
    {
        $walletUser = Auth::guard('web')->user();

        if (!$walletUser) {
            return 0;
        }

        $walletsQuery = WalletReload::query();

        if ($walletUser->is_under_distributer) {
            $walletsQuery->where('created_by', $walletUser->id);
        }

        $wallets = $walletsQuery->get();

        return $wallets->sum('amount'); // Utilisation de sum pour simplifier
    }
}
