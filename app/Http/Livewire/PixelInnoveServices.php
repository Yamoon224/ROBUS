<?php

namespace App\Http\Livewire;

use App\Models\Custumers\Country;
use App\Models\PixelInnoveService;
use App\Models\ProviderType;
use App\Models\Providers\Provider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class PixelInnoveServices extends Component
{
    use WithPagination;

    public $search = '';
    public $filterCountryId = '';
    public $filterIsXof = '';
    public $filterIsXaf = '';
    public $filterIsCdf = '';
    public $filterIsGmd = '';

    public $countryId = '';
    public $providerTypeId = '';
    public $providerId = '';
    public $operationType = 'Cash In';
    public $serviceId = '';
    public $fixedFee = '';
    public $commissionPercent = '1.90';
    public $isXof = false;
    public $isXaf = false;
    public $isCdf = false;
    public $isGmd = false;
    public $pixelServiceId;
    public $pixelStatus;

    protected $paginationTheme = 'bootstrap';

    protected function rules()
    {
        return [
            'countryId' => ['required', 'exists:countries,id'],
            'providerId' => ['required', 'exists:providers,id'],
            'operationType' => ['required', Rule::in(['Cash In', 'Cash Out'])],
            'serviceId' => [
                'required',
                'integer',
                Rule::unique('pixel_innove_services', 'service_id')->ignore($this->pixelServiceId),
            ],
            'fixedFee' => ['nullable', 'numeric', 'min:0'],
            'commissionPercent' => ['required', 'numeric', 'min:0'],
            'isXof' => ['boolean'],
            'isXaf' => ['boolean'],
            'isCdf' => ['boolean'],
            'isGmd' => ['boolean'],
        ];
    }

    protected $messages = [
        'countryId.required' => 'Le pays est requis',
        'providerId.required' => 'Le provider est requis',
        'operationType.required' => 'Le type operation est requis',
        'serviceId.required' => 'ID service Pixel requis',
        'serviceId.unique' => 'Cet ID service Pixel existe deja',
        'commissionPercent.required' => 'La commission est requise',
    ];

    public function updatedFilterCountryId()
    {
        $this->resetPage();
    }

    public function updatedFilterIsXof()
    {
        if ($this->filterIsXof) {
            $this->clearZoneFilters('filterIsXof');
        }
        $this->resetPage();
    }

    public function updatedFilterIsXaf()
    {
        if ($this->filterIsXaf) {
            $this->clearZoneFilters('filterIsXaf');
        }
        $this->resetPage();
    }

    public function updatedFilterIsCdf()
    {
        if ($this->filterIsCdf) {
            $this->clearZoneFilters('filterIsCdf');
        }
        $this->resetPage();
    }

    public function updatedFilterIsGmd()
    {
        if ($this->filterIsGmd) {
            $this->clearZoneFilters('filterIsGmd');
        }
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterCountryId = '';
        $this->filterIsXof = '';
        $this->filterIsXaf = '';
        $this->filterIsCdf = '';
        $this->filterIsGmd = '';
        $this->resetPage();
    }

    public function updatedIsXof($value)
    {
        if ($value) {
            $this->clearSelectedZones('isXof');
        }
    }

    public function updatedIsXaf($value)
    {
        if ($value) {
            $this->clearSelectedZones('isXaf');
        }
    }

    public function updatedIsCdf($value)
    {
        if ($value) {
            $this->clearSelectedZones('isCdf');
        }
    }

    public function updatedIsGmd($value)
    {
        if ($value) {
            $this->clearSelectedZones('isGmd');
        }
    }

    public function updatedProviderTypeId()
    {
        $this->providerId = '';
    }

    public function updatedProviderId()
    {
        if ($this->providerId) {
            $provider = Provider::find($this->providerId);
            $this->providerTypeId = $provider->provider_type_id ?? '';
        } else {
            $this->providerTypeId = '';
        }
    }

    public function showCreateModal()
    {
        $this->resetField();
        $this->emit('show-create-pixel-innove-modal');
    }

    public function add()
    {
        $this->validate();

        $provider = Provider::findOrFail($this->providerId);

        $exists = PixelInnoveService::where('provider_id', $this->providerId)
            ->where('country_id', $this->countryId)
            ->where('operation_type', $this->operationType)
            ->exists();

        if ($exists) {
            $this->emit('error', 'Ce service Pixel Innove existe deja pour ce pays, provider et type operation.');
            return;
        }

        PixelInnoveService::create([
            'country_id' => $this->countryId,
            'provider_id' => $this->providerId,
            'name' => $this->buildServiceName($provider->name),
            'operation_type' => $this->operationType,
            'service_id' => $this->serviceId,
            'fixed_fee' => $this->fixedFee ?: null,
            'commission_percent' => $this->commissionPercent,
            'is_xof' => (bool) $this->isXof,
            'is_xaf' => (bool) $this->isXaf,
            'is_cdf' => (bool) $this->isCdf,
            'is_gmd' => (bool) $this->isGmd,
            'created_by' => Auth::guard('web')->id(),
            'status' => true,
        ]);

        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Service Pixel Innove ajoute !');
    }

    public function edit($id, $state)
    {
        $service = PixelInnoveService::findOrFail($id);

        $this->pixelServiceId = $service->id;
        $this->countryId = $service->country_id;
        $this->providerTypeId = $service->provider_type_id;
        $this->providerId = $service->provider_id;
        $this->operationType = $service->operation_type;
        $this->serviceId = $service->service_id;
        $this->fixedFee = $service->fixed_fee;
        $this->commissionPercent = $service->commission_percent;
        $this->isXof = (bool) $service->is_xof;
        $this->isXaf = (bool) $service->is_xaf;
        $this->isCdf = (bool) $service->is_cdf;
        $this->isGmd = (bool) $service->is_gmd;
        $this->pixelStatus = $service->status;

        $this->emit($state);
    }

    public function update()
    {
        $this->validate();

        $service = PixelInnoveService::findOrFail($this->pixelServiceId);
        $provider = Provider::findOrFail($this->providerId);

        $exists = PixelInnoveService::where('provider_id', $this->providerId)
            ->where('country_id', $this->countryId)
            ->where('operation_type', $this->operationType)
            ->where('id', '!=', $service->id)
            ->exists();

        if ($exists) {
            $this->emit('error', 'Ce service Pixel Innove existe deja pour ce pays, provider et type operation.');
            return;
        }

        $service->update([
            'country_id' => $this->countryId,
            'provider_type_id' => $this->providerTypeId ?: null,
            'provider_id' => $this->providerId,
            'name' => $this->buildServiceName($provider->name),
            'operation_type' => $this->operationType,
            'service_id' => $this->serviceId,
            'fixed_fee' => $this->fixedFee ?: null,
            'commission_percent' => $this->commissionPercent,
            'is_xof' => (bool) $this->isXof,
            'is_xaf' => (bool) $this->isXaf,
            'is_cdf' => (bool) $this->isCdf,
            'is_gmd' => (bool) $this->isGmd,
            'updated_by' => Auth::guard('web')->id(),
        ]);

        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Service Pixel Innove modifie !');
    }

    public function disable()
    {
        $service = PixelInnoveService::findOrFail($this->pixelServiceId);
        $service->update(['status' => !$service->status]);

        $this->emit('modal-hide', 'hide');
        $this->emit('success', $service->status ? 'Service Pixel Innove active !' : 'Service Pixel Innove desactive !');
    }

    public function delete()
    {
        $service = PixelInnoveService::findOrFail($this->pixelServiceId);
        $service->delete();

        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Service Pixel Innove supprime !');
    }

    public function resetField()
    {
        $this->countryId = '';
        $this->providerTypeId = '';
        $this->providerId = '';
        $this->operationType = 'Cash In';
        $this->serviceId = '';
        $this->fixedFee = '';
        $this->commissionPercent = '1.90';
        $this->isXof = false;
        $this->isXaf = false;
        $this->isCdf = false;
        $this->isGmd = false;
        $this->pixelServiceId = null;
        $this->pixelStatus = null;
        $this->resetValidation();
    }

    public function render()
    {
        $services = PixelInnoveService::with(['country', 'providerType', 'provider'])
            ->when($this->filterCountryId !== '', fn($q) =>
                $q->where('country_id', $this->filterCountryId)
            )
            ->when($this->filterIsXof !== '', fn($q) =>
                $q->where('is_xof', true)
            )
            ->when($this->filterIsXaf !== '', fn($q) =>
                $q->where('is_xaf', true)
            )
            ->when($this->filterIsCdf !== '', fn($q) =>
                $q->where('is_cdf', true)
            )
            ->when($this->filterIsGmd !== '', fn($q) =>
                $q->where('is_gmd', true)
            )
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('service_id', 'like', '%' . $this->search . '%')
                        ->orWhereHas('country', fn($c) =>
                            $c->where('name', 'like', '%' . $this->search . '%')
                        )
                        ->orWhereHas('provider', fn($p) =>
                            $p->where('name', 'like', '%' . $this->search . '%')
                        );
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.pixel-innove-services', [
            'pixelServices' => $services,
            'countries' => Country::where('robus', true)->orderBy('name', 'asc')->get(),
            'providerTypes' => ProviderType::where('status', true)->orderBy('name', 'asc')->get(),
            'providers' => Provider::where('status', true)
                 ->where('is_pixel', true)
                ->orderBy('name', 'asc')
                ->get(),
        ]);
    }

    private function buildServiceName($providerName)
    {
        $prefix = $this->operationType === 'Cash In' ? 'cashin' : 'cashout';
        $country = Country::find($this->countryId);
        $countryCode = strtoupper($country->iso2 ?? $country->code ?? '');
        $providerSlug = strtoupper(str_replace(' ', '_', $providerName));

        return trim($prefix . '_' . $providerSlug . '_' . $countryCode, '_');
    }

    private function clearZoneFilters(string $except): void
    {
        foreach (['filterIsXof', 'filterIsXaf', 'filterIsCdf', 'filterIsGmd'] as $property) {
            if ($property !== $except) {
                $this->{$property} = '';
            }
        }
    }

    private function clearSelectedZones(string $except): void
    {
        foreach (['isXof', 'isXaf', 'isCdf', 'isGmd'] as $property) {
            if ($property !== $except) {
                $this->{$property} = false;
            }
        }
    }
}
