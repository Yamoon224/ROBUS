<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Visite;

class VisitedComzone extends Component
{

    use WithPagination;

    public $userId;
    public $search = '';
    public $statusFilter = '';
    public $startDate = null;
    public $endDate = null;
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
        $this->perPage = 10;
        $this->resetPage();
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
        return view('livewire.visited-comzone', [
            'visites' => $this->visites,
            'total' => $this->total,
            'statistics' => $this->statistics,
        ]);
    }
}
