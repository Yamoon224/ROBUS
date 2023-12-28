<?php

namespace App\Http\Livewire\Commissions;

use Livewire\Component;
use App\Models\Costs\BankCost;
use Illuminate\Support\Facades\Auth;

class Bank extends Component
{

    public $search = "";
    public $bankId;
    public $bankStatus;
    public $bankName;
    public $bankPercent;
    public $orderCardMerchant;

    protected $rules = [
        'bankName' => 'required',
        'bankPercent' => 'required',
        'orderCardMerchant' => 'required',
    ];

    protected $messages = [
        'bankName.required' => 'Ce champ est requis',
        'bankPercent.required' => 'Ce champ est requis',
        'orderCardMerchant.required' => 'Ce champ est requis',
    ];


    public function add()
    {
        $this->validate();
        $check = BankCost::whereName($this->bankName)->first();
        if (!$check) {
            BankCost::create(
                [
                    'name' => $this->bankName,
                    'percent'  => $this->bankPercent,
                    'percent_merchands'  => $this->orderCardMerchant,
                    'created_by' => Auth::guard('web')->user()->id,
                    'status'  => 1
                ]
            );
            $this->bankName = '';
            $this->bankPercent = '';
            $this->orderCardMerchant = '';
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Commission de la banque ajouté !');
        } else $this->emit('error', 'Commission  existante !');
    }

    public function edit($id, $state)
    {
        $check = BankCost::whereId($id)->first();
        $this->bankId = $id;
        $this->bankName = $check->name;
        $this->bankPercent = $check->percent * 100;
        $this->orderCardMerchant = $check->percent_merchands * 100;
        $this->bankStatus = $check->status;
        $this->emit($state);
    }

    public function update()
    {
        $this->validate();
        $check = BankCost::whereId($this->bankId)->first();

        if ($this->orderCardMerchant > $this->bankPercent) {
            // $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('error', 'Svp verifier les comissions !');
        } else {

            $check->update([
                'name' => $this->bankName,
                'percent' => $this->bankPercent,
                'percent_merchands'  => $this->orderCardMerchant,
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
        $this->bankName = '';
        $this->bankPercent = '';
        $this->orderCardMerchant = '';
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = BankCost::first();
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Bank activée !' : 'Bank désactivée !');
        }
    }

    public function delete()
    {
        $check = BankCost::find($this->bankId);
        if (!empty($check)) {
            $check->destroy($this->bankId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Bank supprimée !');
        }
    }

    public function render()
    {
        $bank_costs = BankCost::query();

        if ($this->search != "") {

            $this->resetPage();

            $bank_costs->where("name", "LIKE",  "%" . $this->search . "%");
        }
        return view('livewire.commissions.bank', [
            "banks" => $bank_costs->orderBy('name', 'desc')->latest()->get(),
        ]);
    }
}