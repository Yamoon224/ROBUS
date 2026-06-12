<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\OperatorService;
use App\Models\Sid\SidUser;
use App\Models\Visite;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletBankSend;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletDjamo;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletPush;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletTransaction;
use App\Models\Wallets\WalletTresortMoney;
use App\Models\Wallets\WalletVersement;
use App\Models\Wallets\WalletWave;
use App\Models\Wallets\WalletXbet;
use Carbon\Carbon;


class StatsVisite extends Component
{

    use WithPagination;

    private const COMMISSION_TRANSACTION_STATUS = 'SUCCESS';

    protected array $transactionModels = [
        'transactions' => WalletTransaction::class,
        'credits' => WalletPhoneCredit::class,
        'paiements' => WalletPayment::class,
        'internationals' => WalletInter::class,
        'rechargements' => WalletReload::class,
        'virements' => WalletBank::class,
        'commandes' => WalletDeposit::class,
        'push' => WalletPush::class,
        'djamo' => WalletDjamo::class,
        'xbets' => WalletXbet::class,
        'bank_sends' => WalletBankSend::class,
        'wave' => WalletWave::class,
        'tresort_money' => WalletTresortMoney::class,
        'versements' => WalletVersement::class,
    ];

    protected array $transactionServiceLabels = [
        'DEPOT' => 'transactions',
        'CREDIT TELEPHONIQUE' => 'credits',
        'PAIEMENT DE FACTURE' => 'paiements',
        'TRANSFERT INTERNATIONAL' => 'internationals',
        'RECHARGEMENT' => 'rechargements',
        'Active Virements' => 'virements',
        'Active Commande' => 'commandes',
        'Active Push' => 'push',
        'Active Djamo' => 'djamo',
        'XBET' => 'xbets',
        'Active Bank Send' => 'bank_sends',
        'Active Wave' => 'wave',
        'Active Tresort Money' => 'tresort_money',
        'VERSEMENT BANCAIRE' => 'versements',
    ];

    protected array $serviceNames = [
        'transactions' => 'Transactions',
        'credits' => 'Crédit téléphonique',
        'paiements' => 'Paiement de facture',
        'internationals' => 'Transfert international',
        'rechargements' => 'Rechargement',
        'virements' => 'Virements',
        'commandes' => 'Commandes de carte',
        'push' => 'Push',
        'djamo' => 'Djamo',
        'xbets' => 'Xbet',
        'bank_sends' => 'Bank Send',
        'wave' => 'Wave',
        'tresort_money' => 'Tresort Money',
        'versements' => 'Versement bancaire',
    ];

    public $userId;
    public $search = '';
    public $statusFilter = '';
    public $startDate = null;
    public $endDate = null;
    public $transactionStatus = 'SUCCESS';
    public $transactionStartDate = null;
    public $transactionEndDate = null;
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = false;
    
    // Modal
    public $showDetailsModal = false;
    public $selectedVisite = null;

    public function mount($userId)
    {
        $this->userId = $userId;
    }

    public function setSortField($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortField = $field;
            $this->sortAsc = true;
        }
        $this->resetPage();
    }

    public function setStatusFilter($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->startDate = null;
        $this->endDate = null;
        $this->transactionStatus = 'SUCCESS';
        $this->transactionStartDate = null;
        $this->transactionEndDate = null;
        $this->perPage = 10;
        $this->resetPage();
    }

    public function resetTransactionFilters()
    {
        $this->transactionStatus = 'SUCCESS';
        $this->transactionStartDate = null;
        $this->transactionEndDate = null;
    }

    public function getVisitesProperty()
    {
        $query = Visite::with(['sidUser', 'wallet'])
            ->where('sidUser_id', $this->userId);

        // Recherche
        if ($this->search) {
            $query->where(function($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('sidUser', function($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Filtre par statut
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Filtre par dates
        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        // Tri
        if ($this->sortField) {
            if ($this->sortField == 'user_name') {
                $query->join('sid_users', 'visites.sidUser_id', '=', 'sid_users.id')
                    ->orderBy('sid_users.name', $this->sortAsc ? 'asc' : 'desc')
                    ->select('visites.*');
            } else {
                $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
            }
        }

        return $query->paginate($this->perPage);
    }

    public function getTotalProperty()
    {
        return Visite::where('sidUser_id', $this->userId)->count();
    }

    public function getStatisticsProperty()
    {
        return [
            'total' => Visite::where('sidUser_id', $this->userId)->count(),
            'pending' => Visite::where('sidUser_id', $this->userId)->where('status', 'pending')->count(),
            'confirmed' => Visite::where('sidUser_id', $this->userId)->where('status', 'confirmed')->count(),
            'rejected' => Visite::where('sidUser_id', $this->userId)->where('status', 'rejected')->count(),
        ];
    }

    public function getTransactionCountersProperty()
    {
        $comZone = SidUser::where('is_com_zone', true)->find($this->userId);

        if (!$comZone) {
            return null;
        }

        $dateRange = $this->resolveTransactionDateRange();
        $walletIds = Wallet::whereIn(
            'sid_user_id',
            SidUser::where('com_zone_id', $comZone->id)->pluck('id')
        )->pluck('id');

        $periods = $this->transactionCounterPeriods($dateRange['base_date'], $dateRange['start'], $dateRange['end']);
        $operatorServicesByService = $this->operatorServicesByService($comZone);
        $totals = [];
        $amountTotals = [];
        $servicesStats = [];

        foreach (array_keys($periods) as $period) {
            $totals[$period] = 0;
            $amountTotals[$period] = 0;
        }

        foreach ($this->transactionModels as $service => $model) {
            $operatorService = $operatorServicesByService[$service] ?? null;
            $isAssigned = (bool) $operatorService;
            $counts = [];
            $amounts = [];
            $commissionCounts = [];
            $commissionAmounts = [];
            $commissions = [];

            foreach ($periods as $period => $periodRange) {
                $stats = $this->transactionStatsByPeriod($model, $walletIds, $periodRange['start'], $periodRange['end'], $this->transactionStatus);
                $commissionStats = $this->transactionStatsByPeriod($model, $walletIds, $periodRange['start'], $periodRange['end'], self::COMMISSION_TRANSACTION_STATUS);

                $counts[$period] = $isAssigned ? $stats['count'] : 0;
                $amounts[$period] = $isAssigned ? $stats['amount'] : 0;
                $commissionCounts[$period] = $isAssigned ? $commissionStats['count'] : 0;
                $commissionAmounts[$period] = $isAssigned ? $commissionStats['amount'] : 0;
                $commissions[$period] = $isAssigned
                    ? $operatorService->calculateComZoneCommission($commissionStats['count'], $commissionStats['amount'])
                    : 0;

                if ($isAssigned) {
                    $totals[$period] += $stats['count'];
                    $amountTotals[$period] += $stats['amount'];
                }
            }

            $servicesStats[$service] = [
                'name' => $this->serviceNames[$service] ?? $service,
                'is_assigned' => $isAssigned,
                'counts' => $counts,
                'amounts' => $amounts,
                'commission_status' => self::COMMISSION_TRANSACTION_STATUS,
                'commission_counts' => $commissionCounts,
                'commission_amounts' => $commissionAmounts,
                'commissions' => $commissions,
                'numbers_rule' => $operatorService->numbers ?? 0,
                'amount_rule' => $operatorService->amount ?? 0,
            ];
        }

        return [
            'com_zone_id' => $comZone->id,
            'status' => $this->transactionStatus,
            'filters' => [
                'resolved_start_date' => $dateRange['start']->toDateString(),
                'resolved_end_date' => $dateRange['end']->toDateString(),
            ],
            'linked_users_counts' => $this->linkedUsersCounters($comZone->id),
            'totals' => [
                'counts' => $totals,
                'amounts' => $amountTotals,
            ],
            'services_stats' => $servicesStats,
        ];
    }

    public function updateStatus($visiteId, $status)
    {
        $visite = Visite::find($visiteId);
        if ($visite) {
            $visite->update([
                'status' => $status,
                'confirmed_at' => $status == 'confirmed' ? now() : null
            ]);
            session()->flash('message', 'Statut mis à jour avec succès');
        }
    }

    public function viewDetails($visiteId)
    {
        $this->selectedVisite = Visite::with('sidUser')->find($visiteId);
        $this->showDetailsModal = true;
    }

    public function closeModal()
    {
        $this->showDetailsModal = false;
        $this->selectedVisite = null;
    }

    public function render()
    {
        return view('livewire.stats-visite', [
            'visites' => $this->visites,
            'total' => $this->total,
            'statistics' => $this->statistics,
            'transactionCounters' => $this->transactionCounters,
        ]);
    }

    protected function resolveTransactionDateRange(): array
    {
        $startDate = $this->transactionStartDate;
        $endDate = $this->transactionEndDate;

        if ($startDate || $endDate) {
            $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfDay();
            $end = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

            if ($start->greaterThan($end)) {
                [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
            }

            return [
                'base_date' => $end->copy(),
                'start' => $start,
                'end' => $end,
            ];
        }

        $now = Carbon::now();

        return [
            'base_date' => $now,
            'start' => $now->copy()->startOfDay(),
            'end' => $now->copy()->endOfDay(),
        ];
    }

    protected function transactionCounterPeriods(Carbon $baseDate, Carbon $filterStart, Carbon $filterEnd): array
    {
        return [
            'filtered' => [
                'start' => $filterStart,
                'end' => $filterEnd,
            ],
            'daily' => [
                'start' => $baseDate->copy()->startOfDay(),
                'end' => $baseDate->copy()->endOfDay(),
            ],
            'weekly' => [
                'start' => $baseDate->copy()->startOfWeek(),
                'end' => $baseDate->copy()->endOfWeek(),
            ],
            'monthly' => [
                'start' => $baseDate->copy()->startOfMonth(),
                'end' => $baseDate->copy()->endOfMonth(),
            ],
            'quarterly' => [
                'start' => $baseDate->copy()->startOfQuarter(),
                'end' => $baseDate->copy()->endOfQuarter(),
            ],
            'yearly' => [
                'start' => $baseDate->copy()->startOfYear(),
                'end' => $baseDate->copy()->endOfYear(),
            ],
        ];
    }

    protected function transactionStatsByPeriod(string $model, $walletIds, Carbon $start, Carbon $end, ?string $status): array
    {
        $query = $this->transactionQuery($model, $walletIds, $start, $end, $status);

        return [
            'count' => (clone $query)->count(),
            'amount' => (float) (clone $query)->sum('amount'),
        ];
    }

    protected function transactionQuery(string $model, $walletIds, Carbon $start, Carbon $end, ?string $status)
    {
        $query = $model::whereIn('wallet_id', $walletIds)
            ->whereBetween('created_at', [$start, $end]);

        if ($status) {
            $query->where('status', $status);
        }

        return $query;
    }

    protected function operatorServicesByService(SidUser $comZone): array
    {
        $operatorServices = OperatorService::with('operator')
            ->where('sid_user_id', $comZone->id)
            ->get();

        $services = [];

        foreach ($operatorServices as $operatorService) {
            $serviceKey = $this->serviceKeyForOperatorName($operatorService->operator->name ?? '');

            if ($serviceKey && !isset($services[$serviceKey])) {
                $services[$serviceKey] = $operatorService;
            }
        }

        return $services;
    }

    protected function serviceKeyForOperatorName(string $operatorName): ?string
    {
        foreach (explode(',', $operatorName) as $name) {
            $name = trim($name);

            if (isset($this->transactionServiceLabels[$name])) {
                return $this->transactionServiceLabels[$name];
            }
        }

        return null;
    }

    protected function linkedUsersCounters(int $comZoneId): array
    {
        $linkedUsersQuery = SidUser::where('com_zone_id', $comZoneId);

        return [
            'total' => (clone $linkedUsersQuery)->count(),
            'clients' => (clone $linkedUsersQuery)->where('is_custumers', true)->count(),
            'masters' => (clone $linkedUsersQuery)->where('is_master', true)->count(),
            'under_distributors' => (clone $linkedUsersQuery)->where('is_under_distributer', true)->count(),
        ];
    }

}
