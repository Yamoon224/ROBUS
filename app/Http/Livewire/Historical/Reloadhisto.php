<?php

namespace App\Http\Livewire\Historical;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\HistoriqueService;

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

        $walletUser = Auth::guard('web')->check()
            ? Auth::guard('web')->user()
            : (Auth::guard('integration')->check()
                ? Auth::guard('integration')->user()
                : null);
                
        $realodQuery = WalletReload::query();

        // Filtrer par utilisateur
        if ($walletUser->is_under_distributer || $walletUser->is_api) {
            $realodQuery->where('created_by', $walletUser->id);
        }

        // Filtre par période de dates
        if ($this->startDate && $this->endDate) {
            $realodQuery->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        // Recherche textuelle
        if (!empty($this->searchRealodHistorical)) {
            $this->resetPage(); 
            $realodQuery->orWhereHas('paymentReload', function ($query) {
                $query->where('operator_transaction_name', 'like', '%' . $this->searchRealodHistorical . '%')
                    ->orWhere('transaction_id', 'like', '%' . $this->searchRealodHistorical . '%')
                    ->orWhere('trans_comp_id', 'like', '%' . $this->searchRealodHistorical . '%');
            })
                ->orWhere('amount', 'like', '%' . $this->searchRealodHistorical . '%')
                ->orWhere('phone', 'like', '%' . $this->searchRealodHistorical . '%')
                ->orWhere('account_wallet', 'like', '%' . $this->searchRealodHistorical . '%')
                ->orWhere('reference', 'like', '%' . $this->searchRealodHistorical . '%')
                ->orWhere('payment_method', 'like', '%' . $this->searchRealodHistorical . '%')
                ->orWhere('metadata', 'like', '%' . $this->searchRealodHistorical . '%');
        }

        // Filtre par statut
        if (!empty($this->statusFilter)) {
            $realodQuery->where('status', $this->statusFilter);
        }

        // Obtenir les résultats paginés
        $reloadHistoricals = $realodQuery->orderBy('created_at', 'desc')->paginate(15);

        return view('livewire.historical.reloadhisto', [
            'user' => $this->ReloadsAllDays,
            'reload_historicals' => $reloadHistoricals,
            'usersCountry' => $walletUser->country->currencie->name,
        ]);
    }

    public function getReloadsAllDaysProperty()
    {

        $walletUser = Auth::guard('web')->check()
            ? Auth::guard('web')->user()
            : (Auth::guard('integration')->check()
                ? Auth::guard('integration')->user()
                : null);

        if (!$walletUser) {
            return 0;
        }

        $walletsQuery = WalletReload::query();

        if ($walletUser->is_under_distributer || $walletUser->is_api) {
            $walletsQuery->where('created_by', $walletUser->id);
        }

        $wallets = $walletsQuery->get();

        return $wallets->sum('amount'); // Utilisation de sum pour simplifier
    }
}
