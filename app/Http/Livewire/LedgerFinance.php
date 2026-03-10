<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use App\Models\LedgerEntry;
use Illuminate\Support\Facades\DB;

class LedgerFinance extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Filtres avancés
    public $search = "";
    public $typeFilter = "all";
    public $startDate = "";
    public $endDate = "";
    public $dateRange = "today";
    public $minAmount = "";
    public $maxAmount = "";
    public $movementType = "all"; // credit, debit, all
    public $selectedColumns = ['transaction_id', 'description', 'debit', 'credit', 'created_at'];
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 50;

    // Statistiques
    public $totalCredits = 0;
    public $totalDebits = 0;
    public $balance = 0;
    public $creditCount = 0;
    public $debitCount = 0;

    protected $queryString = [
        'search' => ['except' => ''],
        'typeFilter' => ['except' => 'all'],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
        'dateRange' => ['except' => 'today'],
        'movementType' => ['except' => 'all'],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        $this->applyDateRange('today');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedDateRange($value)
    {
        $this->applyDateRange($value);
    }

    protected function applyDateRange($range)
    {
        $now = Carbon::now();

        switch ($range) {
            case 'today':
                $this->startDate = $now->format('Y-m-d');
                $this->endDate = $now->format('Y-m-d');
                break;
            case 'yesterday':
                $yesterday = $now->subDay();
                $this->startDate = $yesterday->format('Y-m-d');
                $this->endDate = $yesterday->format('Y-m-d');
                break;
            case 'this_week':
                $this->startDate = $now->startOfWeek()->format('Y-m-d');
                $this->endDate = $now->endOfWeek()->format('Y-m-d');
                break;
            case 'last_week':
                $lastWeek = $now->subWeek();
                $this->startDate = $lastWeek->startOfWeek()->format('Y-m-d');
                $this->endDate = $lastWeek->endOfWeek()->format('Y-m-d');
                break;
            case 'this_month':
                $this->startDate = $now->startOfMonth()->format('Y-m-d');
                $this->endDate = $now->endOfMonth()->format('Y-m-d');
                break;
            case 'last_month':
                $lastMonth = $now->subMonth();
                $this->startDate = $lastMonth->startOfMonth()->format('Y-m-d');
                $this->endDate = $lastMonth->endOfMonth()->format('Y-m-d');
                break;
            case 'this_year':
                $this->startDate = $now->startOfYear()->format('Y-m-d');
                $this->endDate = $now->endOfYear()->format('Y-m-d');
                break;
            case 'custom':
                // Ne pas modifier les dates
                break;
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function exportToCsv()
    {
        $entries = $this->getFilteredQuery()->get();

        $filename = 'grand_livre_' . Carbon::now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = [
            'Transaction ID',
            'Description',
            'Débit (FCFA)',
            'Crédit (FCFA)',
            'Date',
            'Type',
            'Admin/Commission'
        ];

        $callback = function () use ($entries, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($entries as $entry) {
                fputcsv($file, [
                    $entry->transaction_id,
                    $entry->description,
                    $entry->debit > 0 ? number_format($entry->debit, 2) : '0',
                    $entry->credit > 0 ? number_format($entry->credit, 2) : '0',
                    $entry->created_at->format('d/m/Y H:i'),
                    $entry->is_comission ? 'Commission' : ($entry->is_admin ? 'Admin' : 'Standard'),
                    $entry->is_admin ? 'Admin' : ($entry->is_comission ? 'Commission' : '-'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function getFilteredQuery()
    {
        $query = LedgerEntry::query()->with('adminCom');

        // 📅 Filtre par date
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay(),
            ]);
        }

        // 🔍 Recherche avancée
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('description', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('transaction_id', 'LIKE', '%' . $this->search . '%')
                    ->orWhereHas('adminCom', function ($q) {
                        $q->where('type', 'LIKE', '%' . $this->search . '%');
                    });
            });
        }

        // 🏷 Filtre par type de mouvement
        if ($this->movementType === 'credit') {
            $query->where('credit', '>', 0);
        } elseif ($this->movementType === 'debit') {
            $query->where('debit', '>', 0);
        }

        // 💰 Filtre par montant
        if (!empty($this->minAmount)) {
            $query->where(function ($q) {
                $q->where('debit', '>=', $this->minAmount)
                    ->orWhere('credit', '>=', $this->minAmount);
            });
        }

        if (!empty($this->maxAmount)) {
            $query->where(function ($q) {
                $q->where('debit', '<=', $this->maxAmount)
                    ->orWhere('credit', '<=', $this->maxAmount);
            });
        }

        // 🏷 Filtre par type (commission/admin)
        if ($this->typeFilter === 'commission') {
            $query->where('is_comission', true);
        } elseif ($this->typeFilter === 'admin') {
            $query->where('is_admin', true);
        } elseif ($this->typeFilter === 'standard') {
            $query->where('is_comission', false)->where('is_admin', false);
        }

        return $query;
    }

    public function calculateStats()
    {
        $query = $this->getFilteredQuery();

        $stats = (clone $query)->select(
            DB::raw('SUM(credit) as total_credits'),
            DB::raw('SUM(debit) as total_debits'),
            DB::raw('COUNT(CASE WHEN credit > 0 THEN 1 END) as credit_count'),
            DB::raw('COUNT(CASE WHEN debit > 0 THEN 1 END) as debit_count')
        )->first();

        $this->totalCredits = $stats->total_credits ?? 0;
        $this->totalDebits = $stats->total_debits ?? 0;
        $this->balance = $this->totalCredits - $this->totalDebits;
        $this->creditCount = $stats->credit_count ?? 0;
        $this->debitCount = $stats->debit_count ?? 0;
    }

    public function render()
    {
        $query = $this->getFilteredQuery();


        // IMPORTANT: Charger la relation adminCom
        $query->with('adminCom');

        // Ou mieux, charger toutes les relations nécessaires
        $query->with([
            'adminCom',
            'adminCom.walletBank',
            'adminCom.walletVersement',
            'adminCom.walletDeposit',
            'adminCom.walletBankSend',
            'adminCom.orderCard',
            'adminCom.walletInter',
            'adminCom.walletReload',
            'adminCom.walletPayment',
            'adminCom.walletTransaction',
            'adminCom.walletPhoneCredit',
            'adminCom.walletXbet',
            'adminCom.walletPush',
            'adminCom.walletAdmin',
            'adminCom.walletCommission',
            'adminCom.walletDjamo',
            'adminCom.walletTresort',
            'adminCom.walletWave'
        ]);


        // Trier
        $query->orderBy($this->sortField, $this->sortDirection);

        $gains = $query->paginate($this->perPage);

        // Calculer les statistiques
        $this->calculateStats();

        // Totaux généraux (non filtrés)
        $totalCommissionsAdmin = LedgerEntry::where('is_admin', true)->sum('credit');
        $totalCommissionsBanque = LedgerEntry::where('is_comission', true)->sum('debit');

        $today = Carbon::today();

        $commissionsAdminToday = LedgerEntry::where('is_admin', true)
            ->whereDate('created_at', $today)
            ->sum('credit');

        $commissionsBanqueToday = LedgerEntry::where('is_comission', true)
            ->whereDate('created_at', $today)
            ->sum('debit');

        return view('livewire.ledger-finance', [
            'gains' => $gains,
            'totalCommissionsAdmin' => $totalCommissionsAdmin,
            'totalCommissionsBanque' => $totalCommissionsBanque,
            'commissionsAdminToday' => $commissionsAdminToday,
            'commissionsBanqueToday' => $commissionsBanqueToday,
        ]);
    }
}
