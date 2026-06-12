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

    // ==================== MOUNT ====================

    public function mount($sidUserId = null)
    {
        $this->sidUserId = $sidUserId;
        $this->loadCountries();
    }

    // ==================== CHARGEMENT ====================

    public function loadCountries()
    {
        $this->countries = Country::where('status', true)->get();
    }

    // ==================== MODAL ====================

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

    // ==================== MISE À JOUR DES FILTRES ====================

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
        $this->search      = "";
        $this->statusFilter = "";
        $this->startDate   = null;
        $this->endDate     = null;
        $this->userStatus  = "";
        $this->userCountry = "";
        $this->userCity    = "";
        $this->citySearch  = "";
        $this->perPage     = 15;
        $this->sortField   = 'created_at';
        $this->sortAsc     = false;
        $this->resetPage();
    }

    // ==================== LOGIQUE D'ACCÈS ====================

    /**
     * Récupère les IDs des utilisateurs autorisés selon le rôle :
     *
     * - Si un sidUserId est passé en paramètre → uniquement cet utilisateur
     * - Si is_com_zone = true  → uniquement ses propres visites
     * - Si is_com_zone = false → toutes les visites (tableau vide = pas de filtre)
     */
    private function getAllowedUserIds(): array
    {
        $user = sabdy();

        // Paramètre explicite passé depuis la route/composant
        if ($this->sidUserId) {
            return [$this->sidUserId];
        }

        // COM Zone : voit uniquement SES propres visites
        if ($user->is_com_zone) {
            return [$user->id];
        }

        // Pas un COM Zone : voit TOUTES les visites (aucun filtre sur les IDs)
        return [];
    }

    // ==================== REQUÊTE PRINCIPALE ====================

    public function getVisitesQuery()
    {
        $allowedUserIds = $this->getAllowedUserIds();

        $query = Visite::query()->with(['sidUser', 'wallet']);

        // Filtre sur les utilisateurs autorisés (ignoré si tableau vide)
        if (!empty($allowedUserIds)) {
            $query->whereIn('sidUser_id', $allowedUserIds);
        }

        // Recherche textuelle
        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                  ->orWhereHas('sidUser', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('firstname', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filtre par statut de visite
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Filtre par statut utilisateur
        if ($this->userStatus !== '') {
            $userStatus = $this->userStatus;
            $query->whereHas('sidUser', function ($q) use ($userStatus) {
                $q->where('status', $userStatus);
            });
        }

        // Filtre par pays
        if ($this->userCountry) {
            $userCountry = $this->userCountry;
            $query->whereHas('sidUser', function ($q) use ($userCountry) {
                $q->where('country_id', $userCountry);
            });
        }

        // Filtre par ville
        if ($this->userCity) {
            $userCity = $this->userCity;
            $query->whereHas('sidUser', function ($q) use ($userCity) {
                $q->where('city_id', $userCity);
            });
        }

        // Filtre par date de début
        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        // Filtre par date de fin
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        // Tri
        if ($this->sortField === 'user_name') {
            $query->leftJoin('sid_users', 'visites.sidUser_id', '=', 'sid_users.id')
                  ->orderBy('sid_users.name', $this->sortAsc ? 'asc' : 'desc')
                  ->select('visites.*');
        } elseif ($this->sortField === 'user_email') {
            $query->leftJoin('sid_users', 'visites.sidUser_id', '=', 'sid_users.id')
                  ->orderBy('sid_users.email', $this->sortAsc ? 'asc' : 'desc')
                  ->select('visites.*');
        } else {
            $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
        }

        return $query;
    }

    // ==================== PROPRIÉTÉS CALCULÉES ====================

    /**
     * Liste paginée des visites
     */
    public function getVisitesProperty()
    {
        return $this->getVisitesQuery()->paginate($this->perPage);
    }

    /**
     * Nombre total de visites (selon filtres actifs)
     */
    public function getTotalProperty()
    {
        return $this->getVisitesQuery()->count();
    }

    /**
     * Statistiques globales (sans les filtres de recherche)
     */
    public function getStatisticsProperty()
    {
        $allowedUserIds = $this->getAllowedUserIds();

        $baseQuery = Visite::query();

        if (!empty($allowedUserIds)) {
            $baseQuery->whereIn('sidUser_id', $allowedUserIds);
        }

        return [
            'total'     => (clone $baseQuery)->count(),
            'pending'   => (clone $baseQuery)->where('status', 'pending')->count(),
            'confirmed' => (clone $baseQuery)->where('status', 'confirmed')->count(),
            'rejected'  => (clone $baseQuery)->where('status', 'rejected')->count(),
        ];
    }

    // ==================== ACTIONS ====================

    /**
     * Mettre à jour le statut d'une visite
     */
    public function updateStatus($visiteId, $status)
    {
        $visite = Visite::find($visiteId);

        if ($visite) {
            $visite->update([
                'status'       => $status,
                'confirmed_at' => $status === 'confirmed' ? now() : null,
            ]);

            session()->flash('message', 'Statut mis à jour avec succès');
            $this->dispatchBrowserEvent('status-updated');
        }
    }

    // ==================== RENDU ====================

    public function render()
    {
        return view('livewire.users-visites', [
            'visites'    => $this->visites,
            'total'      => $this->total,
            'statistics' => $this->statistics,
        ]);
    }
}