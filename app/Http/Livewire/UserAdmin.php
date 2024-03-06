<?php

namespace App\Http\Livewire;

use App\Mail\UvSend;
use App\Models\Custumers\Country;
use App\Models\Operator;
use App\Models\OperatorService;
use App\Models\Sid\SidUser;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;
use App\Events\assignementEvent;

class UserAdmin extends Component
{
    use WithPagination;

    public $search = "";
    public $countriesFilter = "";
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

    public function edit($id, $state)
    {
        $check = SidUser::whereId($id)->first();
        $this->sidUserId = $id;
        $this->sidUserStatus = $check->status;
        $this->emit($state);
    }



    public function resetdesableField()
    {
        $this->emit('modal-hide', 'hide');
        // return redirect(request()->header('Referer'));
    }



    public function cancelConnectedField()
    {
        $this->user_country = null;
        $this->user_city = null;
        $this->master_selected = null;
        $this->emit('modal-hide', 'hide');
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

            if ($check->status == 0) {
                $check->update(['status' => 1]);

                if ($check->email != null) {

                    Mail::to($check->email)->send(new UvSend('Votre compte Robus a été activé pour une raison \n Merci Bien'));
                }

                $this->resetField();
                $this->emit('modal-hide', 'hide');
                $this->emit('success', $check->status == 1 ? 'Utilisateur activée !' : 'Utilisateur désactivée !');
            } else {

                $check->update(['status' => 0]);

                if ($check->email != null) {

                    Mail::to($check->email)->send(new UvSend('Votre compte Robus a été desactivé pour une raison \n Merci Bien'));
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
        $master_country = null;

        if ($this->search != "") {
            $this->resetPage();

            $users->where(function ($query) {
                $query->where('is_supervisor', true)
                    ->orWhere('is_admin', true)
                    ->orWhere(function ($innerQuery) {
                        $innerQuery->where("firstname", "LIKE",  "%" . $this->search . "%")
                            ->orWhere("lastname", "LIKE",  "%" . $this->search . "%")
                            ->orWhere("phone", "LIKE",  "%" . $this->search . "%")
                            ->orWhere("email", "LIKE",  "%" . $this->search . "%")
                            ->orWhere("wallet_id", "LIKE",  "%" . $this->search . "%");
                    });
            });
        }


        if ($this->countriesFilter != "") {
            $users->where("country_id", $this->countriesFilter)->where('is_supervisor', true)->orWhere('is_admin',  true);
        }

        if ($this->user_country !== null) {
            $master_country =   SidUser::where('is_master', true)->where('country_id', $this->user_country)->get();
        }

        return view('livewire.user-admin', [
            "users" => $users->where(function ($query) {
                $query->where('is_supervisor', true)
                    ->orWhere('is_admin', true);
            })
                ->orderBy('created_at', 'desc')
                ->paginate(30),

            "countries" => Country::where('robus', true)->orderBy("name", 'desc')->get(),
            "operators" => Operator::whereStatus(true)->orderBy("name", 'desc')->get(),
        ]);
    }
}
