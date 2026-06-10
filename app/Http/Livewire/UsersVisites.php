<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Visite;
use App\Models\Custumers\Country;
use App\Models\Custumers\City;
use App\Models\Sid\SidUser;

class UsersVisites extends Component
{
    use WithPagination;

    // Filtres
    public $search = "";
    public $statusFilter = "";
    public $startDate = null;
    public $endDate = null;
    public $perPage = 15;
    
    // Tri
    public $sortField = 'created_at';
    public $sortAsc = false;
    
    // Filtres utilisateur
    public $sidUserId;
    public $userStatus = "";
    public $userCountry = "";
    public $userCity = "";
    
    // Filtres géographiques
    public $countries = [];
    public $cities = [];
    public $citySearch = "";

    public $showDetailsModal = false;
    public $selectedVisite = null;

    public function mount($sidUserId = null)
    {
        $this->sidUserId = $sidUserId;
        $this->loadCountries();
    }

    public function loadCountries()
    {
        $this->countries = Country::where('status', true)->get();
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

    public function updatedUserCountry($countryId)
    {
        if ($countryId) {
            $this->cities = City::where('country_id', $countryId)
                ->where('status', true)
                ->get();
            $this->userCity = "";
        } else {
            $this->cities = [];
            $this->userCity = "";
        }
    }

    public function resetFilters()
    {
        $this->search = "";
        $this->statusFilter = "";
        $this->startDate = null;
        $this->endDate = null;
        $this->userStatus = "";
        $this->userCountry = "";
        $this->userCity = "";
        $this->citySearch = "";
        $this->perPage = 15;
        $this->sortField = 'created_at';
        $this->sortAsc = false;
        $this->resetPage();
    }

    /**
     * Récupère les IDs des utilisateurs autorisés selon le rôle
     */
    private function getAllowedUserIds()
    {
        $user = sabdy();
        
        // Si un ID spécifique est passé en paramètre
        if ($this->sidUserId) {
            return [$this->sidUserId];
        }
        
        // 🔥 CAS 1: L'utilisateur est une ZONE DE CONFIANCE (com_zone)
        // Il doit voir les visites de TOUS les utilisateurs de sa zone
        if ($user->is_com_zone) {
            // Récupérer tous les utilisateurs qui appartiennent à cette zone
            // La relation est : sidUser.com_zone_id = $user->id
            $userIds = SidUser::where('com_zone_id', $user->id)->pluck('id')->toArray();
            
            // Ajouter l'utilisateur lui-même s'il n'est pas déjà dans la liste
            if (!in_array($user->id, $userIds)) {
                $userIds[] = $user->id;
            }
            
            return $userIds;
        }
        
        // 🔥 CAS 2: L'utilisateur est un MASTER
        // Il voit les visites de ses sous-distributeurs
        if ($user->is_master) {
            $userIds = SidUser::where('master_id', $user->id)->pluck('id')->toArray();
            $userIds[] = $user->id;
            return $userIds;
        }
        
        // 🔥 CAS 3: L'utilisateur est un SOUS-DISTRIBUTEUR
        // Il voit ses propres visites et celles de ses clients
        if ($user->is_under_distributer) {
            $userIds = SidUser::where('under_distributer_id', $user->id)->pluck('id')->toArray();
            $userIds[] = $user->id;
            return $userIds;
        }
        
        // 🔥 CAS 4: Par défaut, l'utilisateur ne voit que ses propres visites
        return [$user->id];
    }

    /**
     * Construit la requête des visites
     */
    public function getVisitesQuery()
    {
        $allowedUserIds = $this->getAllowedUserIds();
        
        \Log::info('IDs autorisés pour les visites :', $allowedUserIds);
        
        $query = Visite::query()
            ->with(['sidUser', 'wallet']);
        
        // 🔥 FILTRE PRINCIPAL : Ne montrer que les visites des utilisateurs autorisés
        if (!empty($allowedUserIds)) {
            $query->whereIn('sidUser_id', $allowedUserIds);
        }
        
        // Filtre par recherche
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('sidUser', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('firstname', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%')
                            ->orWhere('phone', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Filtre par statut de visite
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Filtre par statut utilisateur
        if ($this->userStatus !== '') {
            $query->whereHas('sidUser', function ($q) {
                $q->where('status', $this->userStatus);
            });
        }

        // Filtre par pays
        if ($this->userCountry) {
            $query->whereHas('sidUser', function ($q) {
                $q->where('country_id', $this->userCountry);
            });
        }

        // Filtre par ville
        if ($this->userCity) {
            $query->whereHas('sidUser', function ($q) {
                $q->where('city_id', $this->userCity);
            });
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
                $query->leftJoin('sid_users', 'visites.sidUser_id', '=', 'sid_users.id')
                    ->orderBy('sid_users.name', $this->sortAsc ? 'asc' : 'desc')
                    ->select('visites.*');
            } elseif ($this->sortField == 'user_email') {
                $query->leftJoin('sid_users', 'visites.sidUser_id', '=', 'sid_users.id')
                    ->orderBy('sid_users.email', $this->sortAsc ? 'asc' : 'desc')
                    ->select('visites.*');
            } else {
                $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
            }
        }

        return $query;
    }

    /**
     * Propriété paginée des visites
     */
    public function getVisitesProperty()
    {
        return $this->getVisitesQuery()->paginate($this->perPage);
    }

    /**
     * Total des visites
     */
    public function getTotalProperty()
    {
        return $this->getVisitesQuery()->count();
    }

    /**
     * Statistiques des visites
     */
    public function getStatisticsProperty()
    {
        $allowedUserIds = $this->getAllowedUserIds();
        
        $baseQuery = Visite::query();
        
        if (!empty($allowedUserIds)) {
            $baseQuery->whereIn('sidUser_id', $allowedUserIds);
        }
        
        return [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'confirmed' => (clone $baseQuery)->where('status', 'confirmed')->count(),
            'rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
        ];
    }

    /**
     * Mettre à jour le statut d'une visite
     */
    public function updateStatus($visiteId, $status)
    {
        $visite = Visite::find($visiteId);
        if ($visite) {
            $visite->update([
                'status' => $status,
                'confirmed_at' => $status == 'confirmed' ? now() : null
            ]);
            session()->flash('message', 'Statut mis à jour avec succès');
            $this->dispatchBrowserEvent('status-updated');
        }
    }

    /**
     * Rendu principal
     */
    public function render()
    {
        return view('livewire.users-visites', [
            'visites' => $this->visites,
            'total' => $this->total,
            'statistics' => $this->statistics,
        ]);
    }
}