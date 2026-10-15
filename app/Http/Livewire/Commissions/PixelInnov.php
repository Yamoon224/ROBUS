<?php

namespace App\Http\Livewire\Commissions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PixelInnoveService;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\Auth;

class PixelInnov extends Component
{
    use WithPagination;

    public $search = '';

    public $serviceId;
    public $serviceName;
    public $serviceStatus;
    public $serviceCommissionPercent;
    public $serviceUnderPct;
    public $serviceCompaniePct;
    public $serviceFixedFee;
    public $serviceOperationType;
    public $serviceCountryId;

    protected $rules = [
        'serviceCommissionPercent' => 'required|numeric|min:0|max:100',
        'serviceUnderPct'          => 'required|numeric|min:0|max:100',
        'serviceCompaniePct'       => 'required|numeric|min:0|max:100',
        'serviceFixedFee'          => 'nullable|numeric|min:0',
    ];

    protected $messages = [
        'serviceCommissionPercent.required' => 'Le pourcentage de commission est requis',
        'serviceUnderPct.required'          => 'Le pourcentage sous-distributeur est requis',
        'serviceCompaniePct.required'       => 'Le pourcentage compagnie est requis',
    ];

    public function edit($id)
    {
        $service = PixelInnoveService::findOrFail($id);
        $this->serviceId              = $service->id;
        $this->serviceName            = $service->name;
        $this->serviceOperationType   = $service->operation_type;
        $this->serviceCountryId       = $service->country_id;
        $this->serviceCommissionPercent = $service->commission_percent;
        $this->serviceUnderPct        = $service->commission_under_pct;
        $this->serviceCompaniePct     = $service->commission_companie_pct;
        $this->serviceFixedFee        = $service->fixed_fee;
        $this->serviceStatus          = $service->status;
        $this->emit('update-pixel-modal');
    }

    public function update()
    {
        $this->validate();

        $service = PixelInnoveService::findOrFail($this->serviceId);

        $service->update([
            'commission_percent'     => $this->serviceCommissionPercent,
            'commission_under_pct'   => $this->serviceUnderPct,
            'commission_companie_pct' => $this->serviceCompaniePct,
            'fixed_fee'              => $this->serviceFixedFee,
            'updated_by'             => Auth::guard('web')->user()->id,
        ]);

        $this->resetFields();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Commission Pixel Innov mise à jour !');
    }

    public function toggleStatus($id)
    {
        $service = PixelInnoveService::findOrFail($id);
        $service->update(['status' => !$service->status]);
        $this->emit('success', $service->status ? 'Service activé !' : 'Service désactivé !');
    }

    public function resetFields()
    {
        $this->serviceId              = null;
        $this->serviceName            = '';
        $this->serviceCommissionPercent = '';
        $this->serviceUnderPct        = '';
        $this->serviceCompaniePct     = '';
        $this->serviceFixedFee        = null;
        $this->serviceStatus          = null;
    }

    public function render()
    {
        $services = PixelInnoveService::with('country')
            ->when($this->search, fn($q) => $q->where('name', 'LIKE', "%{$this->search}%")
                ->orWhereHas('country', fn($c) => $c->where('name', 'LIKE', "%{$this->search}%"))
            )
            ->orderBy('country_id')
            ->orderBy('operation_type')
            ->orderBy('name')
            ->paginate(25);

        return view('livewire.commissions.pixel-innov', [
            'services' => $services,
        ]);
    }
}
