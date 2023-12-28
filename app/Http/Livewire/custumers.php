<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Sid\SidUser;
use Livewire\WithPagination;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\Auth;

class custumers extends Component
{
    use WithPagination;

    public $search = "";
    public $countriesFilter = "";
    public $statusFilter_piece = "";
    public $statusFilter_active = "";

    public function render()
    {
        $users = SidUser::query();

        if ($this->search != "") {

            $this->resetPage();

            $users->where('is_custumers', true)->where("firstname", "LIKE",  "%" . $this->search . "%")
                ->orWhere("lastname", "LIKE",  "%" . $this->search . "%")
                ->orWhere("email", "LIKE",  "%" . $this->search . "%")
                ->orWhere("phone", "LIKE",  "%" . $this->search . "%");
        }

        if ($this->statusFilter_piece != "") {
            $users->where("Without_piece", $this->statusFilter_piece);
        }

        if ($this->statusFilter_active != "") {
            $users->where("is_active", $this->statusFilter_active);
        }

        if ($this->countriesFilter != "") {
            $users->where("country_id", $this->countriesFilter);
        }

        return view('livewire.custumers', [
            "users" => $users->where('is_custumers', true)->orderBy('created_at', 'desc')->latest()->paginate(30),
            "countries" => Country::where('status', true)->orderBy("name", 'desc')->get()
        ]);
    }
}