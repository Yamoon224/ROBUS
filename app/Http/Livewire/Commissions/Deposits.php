<?php

namespace App\Http\Livewire\Commissions;

use Livewire\Component;
use App\Models\Costs\DepositCost;
use Illuminate\Support\Facades\Auth;

class Deposits extends Component
{
    public $search = "";
    public $depositId;
    public $depositStatus;
    public $depositName;
    public $depositPercent;
    public $depositPercentAdmin;
    public $orderCardMerchant;

    protected $rules = [
        'depositName' => 'required',
        'depositPercent' => 'required',
        'orderCardMerchant' => 'required',

    ];

    protected $messages = [
        'depositName.required' => 'Ce champ est requis',
        'depositPercent.required' => 'Ce champ est requis',
        'orderCardMerchant.required' => 'Ce champ est requis',
    ];


    public function add()
    {
        $this->validate();
        $check = DepositCost::whereName($this->depositName)->first();
        if (!$check) {
            DepositCost::create(
                [
                    'name' => $this->depositName,
                    'percent'  => $this->depositPercent,
                    'percent_merchands'  => $this->orderCardMerchant,
                    'created_by' => Auth::guard('web')->user()->id,
                    'status'  => 1
                ]
            );
            $this->depositName = '';
            $this->depositPercent = '';
            $this->orderCardMerchant = '';
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Commission de la banque ajouté !');
        } else $this->emit('error', 'Commission  existante !');
    }

    public function edit($id, $state)
    {
        $check = DepositCost::whereId($id)->first();
        $this->depositId = $check->id;
        $this->depositName = $check->name;
        $this->depositPercent = $check->percent * 100;
        $this->depositPercentAdmin = $check->percent_admin * 100;
        $this->orderCardMerchant = $check->percent_merchands * 100; 
        $this->depositStatus = $check->status;
        $this->emit($state);
    }

    public function update()
    {
        $this->validate();
        $check = DepositCost::whereId($this->depositId)->first();
        if ($this->orderCardMerchant > $this->depositPercent) {
            $this->emit('modal-hide', 'hide');
            $this->emit('error', 'Svp verifier les comissions !');
        } else {
            
            $check->update([
                'name' => $this->depositName,
                'percent' => $this->depositPercent,
                'percent_merchands'  => $this->orderCardMerchant,
                'percent_admin'  => $this->depositPercentAdmin,
                'status' => 1,
                'created_by' => Auth::guard('web')->user()->id,
            ]);

            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Commission banque modifié !');
        }
    }

    public function resetField()
    {
        $this->depositName = '';
        $this->depositPercent = '';
        $this->orderCardMerchant = '';
        $this->depositPercentAdmin = '';
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = DepositCost::first();
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Bank activée !' : 'Bank désactivée !');
        }
    }

    public function delete()
    {
        $check = DepositCost::find($this->depositId);
        if (!empty($check)) {
            $check->destroy($this->depositId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Bank supprimée !');
        }
    }

    public function render()
    {
        $deposit_cost = DepositCost::query();

        if ($this->search != "") {

            $this->resetPage();

            $deposit_cost->where("name", "LIKE",  "%" . $this->search . "%");
        }
        return view('livewire.commissions.deposits', [
            "deposits" => $deposit_cost->orderBy('name', 'desc')->latest()->get(),
        ]);
    }
}