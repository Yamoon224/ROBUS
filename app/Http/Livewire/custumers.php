<?php

namespace App\Http\Livewire;

use App\Models\Custumers\City;
use Livewire\Component;
use App\Models\Sid\SidUser;
use Livewire\WithPagination;
use App\Models\Custumers\Country;
use App\Notifications\UserNotification;


class custumers extends Component
{
    use WithPagination;

    public $search = "";
    public $countriesFilter = "";
    public $statusFilter_piece = "";
    public $statusFilter_active = "";
    public $sidUserInfos;
    public $perPage = 30;
    public $sortField = 'id';
    public $sortAsc = true;
    public $sidUserId;
    public $sidUserStatus;
    public $user_id;
    public $master_id;
    public $user_country;
    public $user_city;
    public $master_selected;
    public $underdistributeurDesabled;
    public $master_country;
    public $masters;
    public $company_id;
    public $countries;
    public $cities = [];
    public $citiesFilter;
    public $cityNameSearch = '';

    public function mount()
    {
        $this->countries = Country::orderBy('name')->get();
    }


    public function updatedCountriesFilter($countryId)
    {

        if ($countryId) {
            $this->cities = City::where('country_id', $countryId)
                ->orderBy('name')
                ->get();
        } else {
            $this->cities = [];
        }

        $this->citiesFilter = null;
    }


    public function edit($id, $state)
    {
        $check = SidUser::whereId($id)->first();
        $this->sidUserId = $id;
        $this->sidUserStatus = $check->status;
        $this->sidUserInfos = $check->firstname . ' ' . $check->lastname;
        $this->emit($state);
    }


      public function deletedUser()
    {
        $user = SidUser::find($this->sidUserId);

        if (!empty($user)) {
            
            if ($user->wallet) {
                $user->wallet->forceDelete();
            }

            $user->forceDelete();
            $this->emit('modal-hide', 'hide');
            $this->cancelConnectedField();
            $this->emit('refreshPage');
            $this->emit('success', 'Utilisateur supprimer avec success !');
        }
    }
    
    public function editConnected($id, $state)
    {
        $check = SidUser::whereId($id)->first();
        $this->user_id = $check->id;
        $this->master_id = $check->master_id;
        $this->underdistributeurDesabled = $check->is_accepted;
        $this->emit($state);
    }

    public function editConnectedCompanie($id, $state)
    {


        $check = SidUser::whereId($id)->first();
        $this->user_id = $check->id;
        $this->company_id = $check->company_id;
        $this->user_country = $check->country->id;
        $this->underdistributeurDesabled = $check->is_accepted_companie;
        $this->emit($state);
    }

    public function addConnectedCompanie($id, $state)
    {
        $check = SidUser::whereId($id)->first();
        $this->user_id = $check->id;
        $this->company_id = $check->company_id;
        $this->user_country = $check->country->id  ?? null;
        $this->user_city = $check->city->id ?? null;
        $this->underdistributeurDesabled = $check->is_accepted_companie;
        $this->emit($state);
    }

    public function connectedOneCompanie()
    {
        $check = SidUser::find($this->user_id);

        $check_company = SidUser::whereId($this->master_selected)->first();


        if (!empty($check_company->company_id)) {

            if ($check->company_id  == $this->company_id && $check_company->is_accepted_companie) {

                $msg = 'Vous avez été lié a un sous distributeur';
                $msg_title = 'Liaison a un sous distributeur';
                $check->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $check->update(['company_id' => $check_company->company_id, 'is_accepted_companie' => true]);
            } else {
                $msg = 'Vous avez été détaché a un sous distributeur';
                $msg_title = 'Détachément a un sous distributeur';
                $check->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $check->update(['company_id' => $check_company->company_id, 'is_accepted_companie' => true]);
            }

            $this->cancelConnectedField();
            $this->emit('refreshPage');
            $this->emit('success', $check->company_id  == $this->master_selected ? 'Sous distributeur lié !' : 'Sous distributeur désactivée !');
        } else {
            $this->cancelConnectedField();
            $this->emit('refreshPage');
            $this->emit('error', 'Pas de selection de la companie !');
        }
    }

    public function deconnectedOneCompanie()
    {
        $check = SidUser::find($this->user_id);

        if (!empty($check)) {

            if ($check->company_id  == $this->company_id && $check->is_accepted_companie) {
                $msg = 'Vous avez été lié a un sous distributeur';
                $msg_title = 'Liaison a un sous distributeur';
                $check->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $check->update(['company_id' => 1, 'is_accepted_companie' => false]);
            } else {
                $this->cancelConnectedField();
                $this->emit('refreshPage');
                $this->emit('error', 'Pas de selection de la companie !');
            }

            $this->cancelConnectedField();
            $this->emit('refreshPage');
            $this->emit('success', $check->company_id  == $this->master_selected ? 'Sous distributeur lié !' : 'Sous distributeur désactivée !');
        } else {
            $this->cancelConnectedField();
            $this->emit('refreshPage');
            $this->emit('error', 'Pas de selection de la companie !');
        }
    }

    public function addConnected($id, $state)
    {
        $check = SidUser::whereId($id)->first();
        $this->user_id = $check->id;
        $this->master_id = $check->master_id;
        $this->user_country = $check->country->id;
        $this->user_city = $check->city->id;
        $this->underdistributeurDesabled = $check->is_accepted;
        $this->emit($state);
    }


    public function connected()
    {
        $check = SidUser::find($this->user_id);

        $check_master = SidUser::find($this->master_id)->first();

        if (!empty($check)) {
            if ($check->master_id  == $this->master_id && $check->is_accepted) {
                $msg = 'Vous avez été lié a un sous distributeur';
                $msg_title = 'Liaison a un sous distributeur';
                $check_master->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $check->update(['master_id' => null, 'is_accepted' => false]);
                $check->assignRole(4);
            } else {
                $msg = 'Vous avez été detaché a un sous distributeur';
                $msg_title = 'Détachément a un sous distributeur';
                $check_master->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $check->update(['master_id' => $this->master_id, 'is_accepted' => true]);
                $check->assignRole(4);
            }
            $this->cancelConnectedField();
            $this->emit('modal-hide', 'hide');
            $this->emit('refreshPage'); // À appeler après avoir effectué les modifications
            $this->emit('success', $check->master_id  == $this->master_id ? 'Sous distributeur lié !' : 'Sous distributeur détaché !');
        }
    }

    public function cancelConnectedField()
    {
        $this->user_country = null;
        $this->user_city = null;
        $this->master_selected = null;
        $this->emit('modal-hide', 'hide');
    }


    public function connectedOneMaster()
    {
        $check = SidUser::find($this->user_id);

        $check_master = SidUser::find($this->master_selected)->first();

        if (!empty($check)) {
            $check->update(['master_id' => $this->master_selected, 'is_accepted' => true]);
            $check->assignRole(4);
            $msg = 'Vous avez été lié a un sous distributeur';
            $msg_title = 'Liaison a un sous distributeur';
            $check_master->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
            $this->emit('modal-hide', 'hide');
            $this->cancelConnectedField();
            $this->emit('refreshPage'); // À appeler après avoir effectué les modifications
            $this->emit('success', $check->master_id  == $this->master_selected ? 'Sous distributeur lié !' : 'Sous distributeur désactivée !');
        }
    }

    public function resetField()
    {
        $this->sidUserId = '';
        $this->sidUserStatus = '';
        $this->emit('modal-hide', 'hide');
    }

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

        if (!is_array($this->cities) && !is_object($this->cities)) {
            $this->cities = [];
        }

        if ($this->citiesFilter != "") {
            $users->where("city_id", $this->citiesFilter);
        }

        if ($this->cityNameSearch != "") {

            $this->resetPage();
            $users->where('is_custumers', true)
                ->when($this->cityNameSearch, function ($query) {
                    $query->where(function ($query) {
                        $query->where("zone", "LIKE", "%" . $this->cityNameSearch . "%");
                    });
                })
                ->paginate($this->perPage);
        }

        return view('livewire.custumers', [
            "users" => $users->where('is_custumers', true)->orderBy('created_at', 'desc')->latest()->paginate(30),
            "countries" => Country::where('status', true)->orderBy("name", 'desc')->get()
        ]);
    }
}
