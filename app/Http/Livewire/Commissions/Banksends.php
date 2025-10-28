<?php

namespace App\Http\Livewire\Commissions;

use App\Models\Costs\BankCommission;
use App\Models\LevelBankAuther;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Banksends extends Component
{

    public $search = "";
    public $bankCommissionId;
    public $depositStatus;
    public $depositName;
    public $depositPercent;
    public $depositPercentAdmin;
    public $orderCardMerchant;

    public $levelId;
    public $amount_begin;
    public $amount_end;
    public $percent;
    public $amount;
    public $comission_master;
    public $comissionCompanie;
    public $comissionBank;
    public $comissionAdmin;
    public $comissionUnder;

    protected $rules = [
        'amount_begin' => 'required',
        'amount_end' => 'required',
        'amount' => 'required',
        'comissionCompanie' => 'required',
        'comissionBank' => 'required',
        'comissionAdmin' => 'required',
        'comissionUnder' => 'required'
    ];

    protected $messages = [
        'amount_begin.required' => 'Ce champ est requis',
        'amount_end.required' => 'Le montant est requis',
        'amount.required' => 'Le montant est requis',
        'comissionCompanie.required' => 'Le montant est requis',
        'comissionBank.required' => 'Le montant est requis',
        'comissionAdmin.required' => 'Le montant est requis',
        'comissionUnder.required' => 'Le montant est requis',
    ];


    public function add()
    {
        $this->validate();
        $check = BankCommission::whereName($this->depositName)->first();
        if (!$check) {
            BankCommission::create(
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
        $check = LevelBankAuther::whereId($id)->first();
        if (!empty($check)) {

            $this->levelId = $check->id;
            $this->amount_begin = $check->amount_begin;
            $this->amount_end = $check->amount_end;
            $this->amount = $check->amount;
            $this->percent = $check->percent;
            $this->comissionCompanie = $check->percent_companie;
            $this->comissionBank = $check->percent_bank;
            $this->comissionAdmin = $check->percent_admin;
            $this->comissionUnder = $check->percent_under;
            $this->emit($state);
        } else {
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('error', 'Aucune donnée !');
        }
    }

    public function update()
    {
        $this->validate();
        $check = LevelBankAuther::whereId($this->levelId)->first();
        if (!empty($check)) {
            $check->update([
                'amount_begin' => $this->amount_begin,
                'amount_end'  => $this->amount_end,
                'amount'  => $this->amount,
                'percent'  => $this->percent,
                'percent_companie'  => $this->comissionCompanie,
                'percent_bank'  => $this->comissionBank,
                'percent_admin'  => $this->comissionAdmin,
                'percent_under'  => $this->comissionUnder,
            ]);

            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Frais modifié !');
        } else {
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('error', 'Pas modifié !');
        }
    }

    public function resetField()
    {
        $this->amount_begin = '';
        $this->amount_end = '';
        $this->amount = '';
        $this->percent = '';
        $this->comissionCompanie = '';
        $this->comissionBank = '';
        $this->comissionAdmin = '';
        $this->comissionUnder = '';
        $this->emit('modal-hide', 'hide');
    }


    public function disable()
    {
        $check = BankCommission::first();
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Bank activée !' : 'Bank désactivée !');
        }
    }

    public function delete()
    {
        $check = BankCommission::find($this->bankCommissionId);
        if (!empty($check)) {
            $check->destroy($this->bankCommissionId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Bank supprimée !');
        }
    }

    public function render()
    {
        $banksend_cost = BankCommission::query();

        if ($this->search != "") {

            $this->resetPage();

            $banksend_cost->where("name", "LIKE",  "%" . $this->search . "%");
        }
        return view('livewire.commissions.banksends', [
            "banksends" => $banksend_cost->orderBy('name', 'desc')->latest()->get(),
        ]);
    }
}
