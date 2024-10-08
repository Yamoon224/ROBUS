<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Custumers\Country;
use App\Models\City;
use Illuminate\Support\Facades\Validator;

class UnderDistributorsRegisters extends Component
{
    public $currentStep = 1;
    public $name_ground;
    public $email_agent;
    public $lastname;
    public $firstname;
    public $phone;
    public $email;
    public $country_id;
    public $city;
    public $zone;
    public $validated_code;
    public $image;
    public $image_recto;
    public $image_verso;
    public $countries = [];
    public $cities = [];

    public function mount()
    {
        $this->countries =  Country::whereRobus(true)
        ->where('indecative', '!=', null)->get();
    }

    public function updatedCountryId($value)
    {
        $this->cities = City::where('country_id', $value)->get();
        $this->city = null;
    }

    public function submitFirstStep()
    {
        $validatedData = $this->validate([
            'name_ground' => 'required|string',
            'email_agent' => 'required|email',
        ]);

        $this->currentStep = 2;
    }

    public function submitSecondStep()
    {
        $validatedData = $this->validate([
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'phone' => 'required|digits_between:10,15',
            'email' => 'required|email',
            'country_id' => 'required|exists:countries,id',
            'city' => 'required|string',
            'zone' => 'required|string',
            'validated_code' => 'required|numeric',
            'image' => 'required|image',
            'image_recto' => 'required|image',
            'image_verso' => 'required|image',
        ]);

        // Logique pour enregistrer les données

        session()->flash('message', 'Sous-distributeur créé avec succès.');
        return redirect()->route('admin.dashboard');
    }

    public function render()
    {
        return view('livewire.under-distributors-registers');
    }
}
