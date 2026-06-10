<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sid\SidUser;


class UsersDetailList extends Component
{

     use WithPagination;

    // Propriétés
    public $comZoneId;
    public $userType; // customers, company, distributors, masters
    public $routePrefix;
    public $title;
    
    // Filtres
    public $search = '';
    public $status = '';
    public $kycStatus = '';
    public $walletStatus = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 15;
    
    // Écouteurs d'événements
    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'kycStatus' => ['except' => ''],
        'walletStatus' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'perPage' => ['except' => 15],
        'page' => ['except' => 1],
    ];

    public function mount($comZoneId, $userType, $routePrefix, $title)
    {
        $this->comZoneId = $comZoneId;
        $this->userType = $userType;
        $this->routePrefix = $routePrefix;
        $this->title = $title;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedKycStatus()
    {
        $this->resetPage();
    }

    public function updatedWalletStatus()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->status = '';
        $this->kycStatus = '';
        $this->walletStatus = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->perPage = 15;
        $this->resetPage();
    }

    public function getUsersProperty()
    {
        $query = SidUser::where('com_zone_id', $this->comZoneId);

        // Filtrage par type d'utilisateur
        switch ($this->userType) {
            case 'customers':
                $query->where('is_custumers', true);
                break;
            case 'company':
                $query->where('is_company', true);
                break;
            case 'distributors':
                $query->where('is_under_distributer', true);
                break;
            case 'masters':
                $query->where('is_master', true);
                break;
        }

        // Filtre recherche
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        // Filtre statut compte
        if ($this->status !== '') {
            $query->where('status', $this->status);
        }

        // Filtre statut KYC
        if ($this->kycStatus !== '') {
            $query->whereHas('kyc', function($q) {
                $q->where('status', $this->kycStatus);
            });
        }

        // Filtre statut Wallet
        if ($this->walletStatus !== '') {
            $query->whereHas('wallet', function($q) {
                $q->where('status', $this->walletStatus);
            });
        }

        // Filtre dates
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        // Charger les relations
        $query->with(['wallet', 'kyc']);
        
        // Pagination
        return $query->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.users-detail-list', [
            'users' => $this->users,
            'total' => $this->users->total(),
        ]);
    }

 
}
