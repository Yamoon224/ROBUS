<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ProviderType;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\CountryService;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Service extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $search = "";
    public $providerId;
    public $providerStatus;
    public $providerOrder;
    public $provider_amount;
    public $providerName;
    protected $paginationTheme = 'bootstrap';
    public $providerDescription;
    public $providerType;
    public $countrys = [];
    public $image;
    public $managementCountryId = '';
    public $managementProviderTypeId = '';

    protected $rules = [
        'providerName' => 'required',
        'providerDescription' => 'required',
        'countrys' => 'required',
        'providerType'  => 'required',
    ];

    protected $messages = [
        'providerName.required' => 'Ce champ est requis',
        'providerDescription.required' => 'Ce champ est requis',
        'countrys.required' => 'Ce champ est requis',
        'providerType.required' => 'Ce champ est requis',
    ];



    public function edit($id, $state)
    {
        $check = Provider::whereId($id)->first();
        $country = Country::whereId($check->country_id)->first();
        $providerType = ProviderType::whereId($check->provider_type_id)->first();
        // $old_countries = $kits->category_products->pluck('category_id')->toArray();

        $this->providerId = $id;
        $this->providerName = $check->name;
        $this->providerDescription = $check->description;
        // $this->image = $check->image;
        $this->providerStatus = $check->status;
        $this->providerOrder = $check->is_order;
        $this->provider_amount = $check->amount ?? null;
        $this->countrys  = $check->country_services->pluck('country_id')->toArray() ?? null;
        $this->providerType = $providerType->id ?? null;
        $this->emit($state);
    }

    public function update()
    {
        $this->validate();
        $check = Provider::whereId($this->providerId)->first();
        // $country = Country::whereId($this->country)->first();
        $providerType = ProviderType::whereId($this->providerType)->first();
        $provider = $check->update([
            'name' => $this->providerName,
            'description' => $this->providerDescription,
            'status' => 1,
            'company_id' => Auth::guard('web')->user()->company->id,
            'created_by' => Auth::guard('web')->user()->id,
            // 'country_id' =>  $country->id,
            'amount' =>  $this->provider_amount ?? null,
            'provider_type_id'   =>  $providerType->id,
        ]);

        $provider->country_services()->delete();

        foreach ($this->countrys as $key => $country_s_id) {

            $country_service = new CountryService;
            $country_service->provider_id = $provider->id;
            $country_service->country_id = $country_s_id;
            $root_countrie = Country::findOrFail($country_s_id);

            $country_service->save();

            $root_countrie->save();
        }

        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Operateur  modifié !');
    }

    public function resetField()
    {
        $this->providerName = '';
        $this->providerDescription = '';
        $this->countrys = '';
        $this->image = '';
        $this->providerType = '';
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = Provider::find($this->providerId);
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);

            Artisan::call('cache:clear');

            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Operateur activée !' : 'Operateur désactivée !');
        }
    }

    public function setCountryServiceStatus(int $countryServiceId, bool $status): void
    {
        $this->authorizeActivationManagement();

        CountryService::findOrFail($countryServiceId)->update(['status' => $status]);
        Artisan::call('cache:clear');

        $this->emit('success', $status ? 'Méthode activée pour ce pays !' : 'Méthode désactivée pour ce pays !');
    }

    public function setGroupStatus(bool $status): void
    {
        $this->authorizeActivationManagement();
        $this->validate([
            'managementCountryId' => 'required|exists:countries,id',
            'managementProviderTypeId' => 'required|exists:provider_types,id',
        ]);

        DB::table('country_services')
            ->where('country_id', $this->managementCountryId)
            ->whereIn('provider_id', function ($query) {
                $query->select('id')->from('providers')
                    ->where('provider_type_id', $this->managementProviderTypeId)
                    ->whereNull('deleted_at');
            })
            ->update(['status' => $status, 'updated_at' => now()]);

        Artisan::call('cache:clear');
        $this->emit('success', $status ? 'Groupe activé pour ce pays !' : 'Groupe désactivé pour ce pays !');
    }

    public function setCountryStatus(bool $status): void
    {
        $this->authorizeActivationManagement();
        $this->validate(['managementCountryId' => 'required|exists:countries,id']);

        CountryService::where('country_id', $this->managementCountryId)
            ->update(['status' => $status]);

        Artisan::call('cache:clear');
        $this->emit('success', $status ? 'Toutes les méthodes du pays sont activées !' : 'Toutes les méthodes du pays sont désactivées !');
    }

    private function authorizeActivationManagement(): void
    {
        abort_unless(Auth::guard('web')->user()->hasPermissionTo('active-operateurs'), 403);
    }

    public function disableOrder()
    {
        $check = Provider::find($this->providerId);
        if (!empty($check)) {
            if ($check->is_order == 0) $check->update(['is_order' => 1]);
            else $check->update(['is_order' => 0]);
            Artisan::call('cache:clear');

            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Commande de carte activée !' : 'Commande de carte désactivée !');
        }
    }

    public function delete()
    {
        $check = Provider::find($this->providerId);
        if (!empty($check)) {
            $check->destroy($this->providerId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Operateur supprimée !');
        }
    }

    public function render()
    {
        $provider = Provider::with(['providerType', 'country_services.country']);


        if ($this->search != "") {

            $this->resetPage();
            $provider->orwhereHas(
                'providerType',
                function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                }
            )
                ->orWhere("name", "like",  "%" . $this->search . "%");
        }

        return view('livewire.service', [
            "providers" => $provider->orderBy('name', 'asc')->latest()->paginate(10),
            "countries" => Country::where('robus', true)->orderBy('name', 'asc')->get(),
            "providerTypes" => ProviderType::where('status', true)->orderBy('name', 'asc')->get(),
        ]);
    }
}
