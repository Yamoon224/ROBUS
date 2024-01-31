<?php

namespace App\Http\Livewire\Masters;

use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use Livewire\Component;
use Livewire\WithPagination;

class ActiveMasters extends Component
{

    use WithPagination;

    public $masters;

    public $search = "";

    public $wallet_id;
    public $user_id;
    public $underdistributeurDesabled;
    public $amount_payment;
    public $choose_type_payement_emp;
    public $wallet_id_underdistributor;
    public $wallet_id_add;

    protected $listeners = ['refreshPage' => '$refresh'];

    public function edit($id, $state)
    {
        $check = SidUser::whereId($id)->first();
        $this->user_id = $check->id;
        $this->underdistributeurDesabled = $check->is_accepted;
        $this->emit($state);
    }

    public function disable()
    {
        $check = SidUser::find($this->user_id);
        if (!empty($check)) {
            if ($check->master_id  == $this->masters && $check->is_accepted) $check->update(['master_id' => null, 'is_accepted' => false]);
            else $check->update(['master_id' => $this->masters, 'is_accepted' => true]);
            $this->emit('modal-hide', 'hide');
            $this->emit('refreshPage'); // À appeler après avoir effectué les modifications
            $this->emit('success', $check->master_id  == $this->masters ? 'Sous distributeur lié !' : 'Sous distributeur désactivée !');
        }
    }

    public function render()
    {

        return view('livewire.masters.active-masters', [
            "users" => $this->getUsersMasters($this->masters),
            "master_id" => $this->masters
        ]);
    }

    public function getUsersMasters($wallet_solde, $page = 20)
    {
        $master_users =   SidUser::where('master_id', $wallet_solde)
            ->orderBy('created_at', 'desc')->paginate($page);
        return $master_users;
    }
}
