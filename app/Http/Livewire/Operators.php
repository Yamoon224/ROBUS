<?php

namespace App\Http\Livewire;

use App\Models\Operator;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Providers\Provider;
use Illuminate\Support\Facades\Auth;

class Operators extends Component
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
    public $currentLogo;

    public function edit($id, $state)
    {
        $check = Provider::whereId($id)->first();

        $this->providerId = $id;
        $this->providerName = $check->name;
        $this->providerDescription = $check->description;
        $this->currentLogo = $check->logo;
        $this->providerStatus = $check->status;
        $this->emit($state);
    }

    public function update()
    {
        $this->validate([
            'providerName'        => 'required|string|max:255',
            'providerDescription' => 'nullable|string',
            'image'               => 'nullable|image|max:2048',
        ]);

        $check = Provider::whereId($this->providerId)->first();

        if ($check) {
            $data = [
                'name'        => $this->providerName,
                'description' => $this->providerDescription,
                'status'      => 1,
            ];

            if ($this->image) {
                $filename = 'dist/providers/' . $this->image->getClientOriginalName();
                $this->image->move(public_path('dist/providers'), $this->image->getClientOriginalName());
                $data['logo'] = $filename;
            }

            $check->update($data);

            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Opérateur modifié !');
        }
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
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Operateur activée !' : 'Operateur désactivée !');
        }
    }

    public function disableOrder()
    {
        $check = Provider::find($this->providerId);
        if (!empty($check)) {
            if ($check->is_order == 0) $check->update(['is_order' => 1]);
            else $check->update(['is_order' => 0]);
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
        $provider = Operator::query();


        return view('livewire.operators', [
            "operators" => $provider->orderBy('name', 'asc')->latest()->paginate(10),
        ]);
    }
}
