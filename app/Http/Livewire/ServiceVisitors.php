<?php

namespace App\Http\Livewire;

use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use App\Models\ProviderType;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceVisitors extends Component
{

    use WithPagination;
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


    public function disable()
    {
        $check = Provider::find($this->providerId);
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Operateur activée !' : 'Operateur désactivée !');
        }
    }

    public function render()
    {

        $provider = Provider::query();

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

        return view('livewire.service-visitors' , [
            "providers" => $provider->where('provider_type_id' , 8)->orderBy('name', 'asc')->latest()->paginate(30),
        ]);
    }
}
