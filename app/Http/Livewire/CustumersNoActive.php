<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Custumers\Country;
use App\Models\Sid\SidUser;

class CustumersNoActive extends Component
{

    use WithPagination;


    public $search = "";
    public $countriesFilter = "";


    public function render()
    {
        
        $users = SidUser::query();

        if ($this->search != "") {

            $this->resetPage();

            $users->where('created_by', 0)->where('status', false)
                ->where("firstname", "LIKE",  "%" . $this->search . "%")
                ->orWhere("lastname", "LIKE",  "%" . $this->search . "%")
                ->orWhere("email", "LIKE",  "%" . $this->search . "%")
                ->orWhere("phone", "LIKE",  "%" . $this->search . "%");
        }

        if ($this->countriesFilter != "") {
            $users->where("country_id", $this->countriesFilter);
        }

        return view('livewire.custumers-no-active', [
            "users" => $users->where('created_by', 0)->whereStatus('status', false)->orderBy('created_at', 'desc')->latest()->paginate(30),
            "countries" => Country::where('status', true)->orderBy("name", 'desc')->get()
        ]);
    }
}