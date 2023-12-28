<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ProvideImages extends Component
{

    public $provider_inter_out_civ;
    public $country_inter_out_civ;
    public $phone_inter_out_civ;

    protected $listeners = ['imageProvidersEvent' => 'handleImageEvent'];

    public function render()
    {
        return view('livewire.provide-images');
    }
}
