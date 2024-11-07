<?php

namespace App\Http\Livewire;

use App\Mail\UvSend;
use App\Models\Custumers\Country;
use App\Models\Operator;
use App\Models\OperatorService;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;
use App\Events\assignementEvent;

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

    public function edit_update($data)
    {

        $sid_user = $data['user_id'];

        $operator_id = $data['operator_id'];

        $check_user = SidUser::whereId($sid_user)->first();

        $check_operator = Operator::whereId($operator_id)->first();

        $old_operators = OperatorService::where('sid_user_id', $check_user->id)
            ->where('operator_id', $check_operator->id)
            ->first();

        if (!empty($old_operators)) {

            $old_operators->forcedelete();
            event(new assignementEvent($old_operators));
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Liste des operateurs mise à jour !');
        } else {

            $operator_service = new OperatorService;
            $operator_service->sid_user_id = $check_user->id;
            $operator_service->operator_id  = $check_operator->id;
            $operator_service->save();
            event(new assignementEvent($operator_service));

            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Liste des operateurs mise à jour !');
        }
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
        $users = SidUser::query()->where('is_company', true);
    
        if ($this->search != "") {
            $this->resetPage();
    
            $users->where(function($query) {
                $query->where('is_admin', true)
                    ->orWhere("is_distributer", true)
                    ->orWhere("is_under_distributer", true)
                    ->orWhere("firstname", "LIKE", "%" . $this->search . "%")
                    ->orWhere("lastname", "LIKE", "%" . $this->search . "%")
                    ->orWhere("phone", "LIKE", "%" . $this->search . "%")
                    ->orWhere("email", "LIKE", "%" . $this->search . "%");
            });
        }
    
        if ($this->countriesFilter != "") {
            $users->where("country_id", $this->countriesFilter);
        }
    
        $users = $users->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                       ->orderBy('created_at', 'desc')
                       ->paginate($this->perPage);
    
        return view('livewire.user-company', [
            "users" => $users,
            "countries" => Country::where('robus', true)->orderBy("name", 'desc')->get(),
            "operators" => Operator::whereStatus(true)->whereIsCommission(true)->orderBy("name", 'desc')->get(),
        ]);
    }
    

}
