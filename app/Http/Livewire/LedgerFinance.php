<?php

namespace App\Http\Livewire;

use App\Models\LedgerEntry;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class LedgerFinance extends Component
{
    use WithPagination;

    private const SORTABLE_FIELDS = [
        'transaction_id',
        'description',
        'debit',
        'credit',
        'created_at',
    ];

    private const PER_PAGE_OPTIONS = [20, 50, 100, 200];

    protected $paginationTheme = 'bootstrap';

    // Filtres avancés
    public $search = '';

    public $typeFilter = 'all';

    public $startDate = '';

    public $endDate = '';

    public $dateRange = 'today';

    public $minAmount = '';

    public $maxAmount = '';

    public $movementType = 'all'; // credit, debit, all

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

    public function updatedMovementType()
    {
        $this->resetPage();
    }

    public function updatedTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedStartDate()
    {
        $this->resetPage();
    }

    public function updatedEndDate()
    {
        $this->resetPage();
    }

    public function updatedMinAmount()
    {
        $this->resetPage();
    }

    public function updatedMaxAmount()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedDateRange($value)
    {
        $this->applyDateRange($value);
        $this->resetPage();
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
        if (! in_array($field, self::SORTABLE_FIELDS, true)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function exportToCsv()
    {
        $query = $this->getFilteredQuery()
            ->setEagerLoads([])
            ->orderBy($this->validatedSortField(), $this->validatedSortDirection());

        $filename = 'grand_livre_'.Carbon::now()->format('Y-m-d_His').'.csv';

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
            'Catégorie',
        ];

        $callback = function () use ($query, $columns) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($query->cursor() as $entry) {
                fputcsv($file, [
                    $entry->transaction_id,
                    $entry->description,
                    number_format((float) $entry->debit, 2, '.', ''),
                    number_format((float) $entry->credit, 2, '.', ''),
                    $entry->created_at->format('d/m/Y H:i'),
                    $entry->category_label,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function getFilteredQuery()
    {
        $query = LedgerEntry::query()->with('adminCom.sidUser');

        // 📅 Filtre par date
        if ($this->startDate && $this->endDate) {
            try {
                $start = Carbon::parse($this->startDate)->startOfDay();
                $end = Carbon::parse($this->endDate)->endOfDay();
            } catch (\Throwable $exception) {
                $start = Carbon::today()->startOfDay();
                $end = Carbon::today()->endOfDay();
            }

            if ($start->greaterThan($end)) {
                [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
            }

            $query->whereBetween('created_at', [
                $start,
                $end,
            ]);
        }

        // 🔍 Recherche avancée
        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('description', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('transaction_id', 'LIKE', '%'.$this->search.'%')
                    ->orWhereHas('adminCom', function ($q) {
                        $q->whereHas('sidUser', function ($userQuery) {
                            $userQuery->where('firstname', 'LIKE', '%'.$this->search.'%')
                                ->orWhere('lastname', 'LIKE', '%'.$this->search.'%');
                        });
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
        if ($this->minAmount !== '' && is_numeric($this->minAmount)) {
            $query->where(function ($q) {
                $q->where('debit', '>=', $this->minAmount)
                    ->orWhere('credit', '>=', $this->minAmount);
            });
        }

        if ($this->maxAmount !== '' && is_numeric($this->maxAmount)) {
            $query->where(function ($q) {
                $q->where(function ($debitQuery) {
                    $debitQuery->where('debit', '>', 0)
                        ->where('debit', '<=', $this->maxAmount);
                })->orWhere(function ($creditQuery) {
                    $creditQuery->where('credit', '>', 0)
                        ->where('credit', '<=', $this->maxAmount);
                });
            });
        }

        // 🏷 Filtre par type (commission/admin)
        if ($this->typeFilter === 'commission') {
            $query->where('is_comission', true);
        } elseif ($this->typeFilter === 'admin') {
            $query->where('is_admin', true);
        } elseif ($this->typeFilter === 'uv_commission') {
            $query->where('is_uv', true);
        } elseif ($this->typeFilter === 'uv_purchase') {
            $query->where('is_wallet', true);
        } elseif ($this->typeFilter === 'standard') {
            $query->where('is_comission', false)
                ->where('is_admin', false)
                ->where('is_uv', false)
                ->where('is_wallet', false);
        }

        return $query;
    }

    public function calculateStats()
    {
        $query = $this->getFilteredQuery()->setEagerLoads([]);

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

        // Trier
        $query->orderBy($this->validatedSortField(), $this->validatedSortDirection());

        $perPage = in_array((int) $this->perPage, self::PER_PAGE_OPTIONS, true)
            ? (int) $this->perPage
            : 50;
        $gains = $query->paginate($perPage);

        // Calculer les statistiques
        $this->calculateStats();

        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();
        $summary = LedgerEntry::query()->selectRaw(
            'COALESCE(SUM(CASE WHEN is_admin = 1 THEN credit ELSE 0 END), 0) AS total_admin,
             COALESCE(SUM(CASE WHEN is_comission = 1 THEN debit ELSE 0 END), 0) AS total_bank,
             COALESCE(SUM(CASE WHEN is_uv = 1 THEN credit ELSE 0 END), 0) AS total_uv_commission,
             COALESCE(SUM(CASE WHEN is_wallet = 1 THEN debit ELSE 0 END), 0) AS total_uv_purchase,
             COALESCE(SUM(CASE WHEN is_admin = 1 AND created_at BETWEEN ? AND ? THEN credit ELSE 0 END), 0) AS today_admin,
             COALESCE(SUM(CASE WHEN is_comission = 1 AND created_at BETWEEN ? AND ? THEN debit ELSE 0 END), 0) AS today_bank,
             COALESCE(SUM(CASE WHEN is_uv = 1 AND created_at BETWEEN ? AND ? THEN credit ELSE 0 END), 0) AS today_uv_commission,
             COALESCE(SUM(CASE WHEN is_wallet = 1 AND created_at BETWEEN ? AND ? THEN debit ELSE 0 END), 0) AS today_uv_purchase',
            [
                $todayStart, $todayEnd,
                $todayStart, $todayEnd,
                $todayStart, $todayEnd,
                $todayStart, $todayEnd,
            ]
        )->first();

        return view('livewire.ledger-finance', [
            'gains' => $gains,
            'totalCommissionsAdmin' => (float) $summary->total_admin,
            'totalCommissionsBanque' => (float) $summary->total_bank,
            'commissionsAdminToday' => (float) $summary->today_admin,
            'commissionsBanqueToday' => (float) $summary->today_bank,
            'totalCommissionsAdminUV' => (float) $summary->total_uv_commission,
            'totalAchatUV' => (float) $summary->total_uv_purchase,
            'commissionsAdminUVToday' => (float) $summary->today_uv_commission,
            'ByUVToday' => (float) $summary->today_uv_purchase,

        ]);
    }

    protected function validatedSortField(): string
    {
        return in_array($this->sortField, self::SORTABLE_FIELDS, true)
            ? $this->sortField
            : 'created_at';
    }

    protected function validatedSortDirection(): string
    {
        return $this->sortDirection === 'asc' ? 'asc' : 'desc';
    }
}
