<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use App\Models\LedgerEntry;

class LedgerFinance extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = "";
    public $typeFilter = "";
    public $startDate = "";
    public $endDate = "";

    protected $updatesQueryString = ['search', 'typeFilter', 'startDate', 'endDate'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = LedgerEntry::query();

        // 📅 Filtre par date
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay(),
            ]);
        }

        // 🔍 Recherche
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('description', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('transaction_id', 'LIKE', '%' . $this->search . '%');
            });
        }

        // 🏷 Filtre type
        if (!empty($this->typeFilter)) {
            if ($this->typeFilter === 'credit') {
                $query->where('credit', '>', 0);
            }

            if ($this->typeFilter === 'debit') {
                $query->where('debit', '>', 0);
            }
        }

        return view('livewire.ledger-finance', [
            "gains" => $query->with('adminCom')->latest()->paginate(30),
            "sum_gain_commission_days" => $this->gainCommissionTodays,
            "sum_gains_commission" => $this->gainCommission,
            "sum_gains_admin" => $this->gainAdmins,
            "sum_gain_admin_days" => $this->gainAdminTodays,

        ]);
    }

    public function getTypeAttribute()
    {
        return match (true) {
            $this->wallet_bank_id !== null => 'BANK',
            $this->wallet_reload_id !== null => 'RELOAD',
            $this->wallet_payment_id !== null => 'PAYMENT',
            $this->wallet_commission_id !== null => 'COMMISSION',
            $this->wallet_admin_id !== null => 'ADMIN',
            $this->wallet_wave_id !== null => 'WAVE',
            $this->wallet_tresort_money_id !== null => 'TRESOR',
            default => 'UNKNOWN'
        };
    }
    // 💰 Total commissions admin (credit uniquement)
    public function getGainAdminsProperty()
    {
        return LedgerEntry::where('description', 'Commission administrateur')
            ->sum('credit');
    }

    // 📅 Total commissions admin aujourd'hui
    public function getGainCommissionTodaysProperty()
    {
        return LedgerEntry::where('description', 'Commission banque')
            ->whereDate('created_at', Carbon::today())
            ->sum('debit');
    }

    public function getGainCommissionProperty()
    {
        return LedgerEntry::where('description', 'Commission banque')
            ->sum('debit');
    }

    // 📅 Total commissions admin aujourd'hui
    public function getGainAdminTodaysProperty()
    {
        return LedgerEntry::where('description', 'Commission administrateur')
            ->whereDate('created_at', Carbon::today())
            ->sum('credit');
    }
}
