<?php

namespace App\Http\Livewire\Companies;

use App\Models\Sid\SidUser;
use Livewire\Component;
use Livewire\WithPagination;
use App\Notifications\UserNotification;


class CompanieEmployers extends Component
{

    use WithPagination;

    public $employers;
    public $search = "";
    public $underdistributeurDesabled;
    public $user_id;
    public $master_id;
    public $company_id;

    public function mount($employers)
    {
        $this->employers =  $employers;
    }

    public function editConnectedCompanie($id, $state)
    {
        $check = SidUser::whereId($id)->first();
        $this->user_id = $check->id;
        $this->company_id = $check->company_id;
        $this->underdistributeurDesabled = $check->is_accepted_companie;
        $this->emit($state);
    }

    public function connectedCompanie()
    {

        $check = SidUser::find($this->underDistributors);

        $check_company = SidUser::whereId($this->user_id)->first();

        if (!empty($check)) {

            if ($check->company_id  == $this->company_id && $check_company->is_accepted_companie) {
                $msg = 'Vous avez été lié a un sous distributeur';
                $msg_title = 'Liaison a un sous distributeur';
                $check->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $check_company->update(['company_id' => 1, 'is_accepted_companie' => false]);
            } else {
                $msg = 'Vous avez été détaché a un sous distributeur';
                $msg_title = 'Détachément a un sous distributeur';
                $check->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $check_company->update(['company_id' => $this->company_id, 'is_accepted_companie' => true]);
            }
            $this->cancelConnectedField();
            $this->emit('modal-hide', 'hide');
            $this->emit('refreshPage');
            $this->emit('success', $check->company_id  == $this->company_id ? 'Sous distributeur lié !' : 'Sous distributeur détaché !');
        }
    }

    public function render()
    {
        $usersId = SidUser::find($this->employers);
    
        if (!$usersId) {
            return view('livewire.companies.companie-employers', [
                'employersCompanies' => collect(),
            ]);
        }
    
        $query = SidUser::where('is_custumers', true)
                        ->where('id', '!=', $this->employers)
                        ->where('company_id', $usersId->company_id);
    
        if ($this->search) {
            $query->where(function ($q) {
                $q->where("firstname", "LIKE", "%" . $this->search . "%")
                  ->orWhere("lastname", "LIKE", "%" . $this->search . "%")
                  ->orWhere("email", "LIKE", "%" . $this->search . "%")
                  ->orWhere("phone", "LIKE", "%" . $this->search . "%");
            });
        }
    
        $employers = $query->paginate(20);

        return view('livewire.companies.companie-employers', [
            'employersCompanies' => $employers,
        ]);
    }
}
