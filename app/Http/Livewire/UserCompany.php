<?php

namespace App\Http\Livewire;

use App\Mail\UvSend;
use Livewire\Component;
use App\Models\Sid\SidUser;
use Livewire\WithPagination;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\Mail;

class UserCompany extends Component
{

    use WithPagination;

    public $search = "";
    public $countriesFilter = "";
    public $perPage = 30;
    public $sortField = 'id';
    public $sortAsc = true;
    public $sidUserId;
    public $sidUserStatus;

    public function edit($id, $state)
    {
        $check = SidUser::whereId($id)->first();
        $this->sidUserId = $id;
        $this->sidUserStatus = $check->status;
        $this->emit($state);
    }

    public function resetField()
    {
        $this->sidUserId = '';
        $this->sidUserStatus = '';
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = SidUser::whereId($this->sidUserId)->first();

        if (!empty($check)) {
            
            if ($check->status == 0) 
            {
                $check->update(['status' => 1]);

                if ($check->email != null) {
                                    
                    Mail::to($check->email)->send(new UvSend('Votre compte Robus a été activé pour une raison \n Merci Bien' ));
                }
                
                $this->resetField();
                $this->emit('modal-hide', 'hide');
                $this->emit('success', $check->status == 1 ? 'Utilisateur activée !' : 'Utilisateur désactivée !');

            } else {

                $check->update(['status' => 0]);

                if ($check->email != null) {
                                    
                    Mail::to($check->email)->send(new UvSend('Votre compte Robus a été desactivé pour une raison \n Merci Bien' ));
                }
                $this->resetField();
                $this->emit('modal-hide', 'hide');
                $this->emit('success', $check->status == 1 ? 'Utilisateur activée !' : 'Utilisateur désactivée !');
            } 
           
        }
    }

    public function render()
    {

        $users = SidUser::query();

        if ($this->search != "") {

            $this->resetPage();

            $users->where('is_admin', true)
                ->orWhere("is_distributer", true)
                ->orWhere("is_under_distributer", true)
                ->orwhere("firstname", "LIKE",  "%" . $this->search . "%")
                ->orWhere("lastname", "LIKE",  "%" . $this->search . "%")
                ->orWhere("phone", "LIKE",  "%" . $this->search . "%")
                ->orWhere("email", "LIKE",  "%" . $this->search . "%")
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage);
        }

        if ($this->countriesFilter != "") {
            $users->where("country_id", $this->countriesFilter);
        }

        return view('livewire.user-company', [
            "users" => $users->where('is_company' ,true)->orderBy('created_at', 'desc')->latest()->paginate(30),
            "countries" => Country::where('robus', true)->orderBy("name", 'desc')->get()
        ]);
    }


}
