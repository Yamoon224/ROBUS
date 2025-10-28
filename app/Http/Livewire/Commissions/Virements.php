<?php

namespace App\Http\Livewire\Commissions;

use App\Models\VersementPercent;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class Virements extends Component
{

    use WithPagination;
    public $search = "";
    public $transaction_id;
    public $transaction_status;
    public $transaction_name;
    public $percent_transaction;
    public $percent_admin;
    public $percent_bank;
    public $percent_under;
    public $distributer_custumer;
    public $comission_master;
    public $comissionCompanie;


    public function edit($id, $state)
    {
        $check = VersementPercent::whereId($id)->first();

        $this->transaction_id = $check->id;
        $this->transaction_name = $check->name;
        $this->percent_transaction = $check->percent * 100;
        $this->percent_admin = $check->percent_admin * 100;
        $this->percent_under = $check->percent_under * 100;
        $this->percent_bank = $check->percent_bank * 100;
        $this->comissionCompanie = $check->percent_companie * 100;
        $this->distributer_custumer = $check->distributer_custumer;
        $this->emit($state);
    }


    public function update()
    {
        $this->validate(
            [
                'transaction_name' => 'required',
                'percent_admin' => 'required',
                'percent_transaction' => 'required',
                'percent_bank' => 'required',
                'comissionCompanie' => 'required',
            ],
            [
                'transaction_name.required' => 'Ce champ est requis',
                'percent_admin.required' => 'Ce champ est requis',
                'percent_transaction.required' => 'Ce champ est requis',
                'percent_bank.required' => 'Ce champ est requis',
                'comissionCompanie.required' => 'Ce champ est requis',
            ]
        );
        $check = VersementPercent::whereId($this->transaction_id)->first();

        $check->update([
            'name' => $this->transaction_name,
            'percent' => $this->percent_transaction,
            'percent_admin' => $this->percent_admin,
            'percent_bank' => $this->percent_bank,
            'percent_under'  => $this->percent_under,
            'percent_companie'  => $this->comissionCompanie,
            'status' => 1,
            'created_by' => Auth::guard('web')->user()->id,
        ]);

        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Commission banque modifié !');
    }

    public function resetField()
    {
        $this->transaction_name = '';
        $this->percent_admin = '';
        $this->percent_bank = '';
        $this->percent_under = '';
        $this->percent_transaction = '';
        $this->comissionCompanie = '';
        $this->emit('modal-hide', 'hide');
    }

    public function delete()
    {
        $check = VersementPercent::find($this->transaction_id);
        if (!empty($check)) {
            $check->destroy($this->transaction_id);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Internationnal supprimée !');
        }
    }


    public function render()
    {

        $virements = VersementPercent::query();

        if ($this->search != "") {

            $this->resetPage();

            $virements->where("name", "LIKE",  "%" . $this->search . "%");
        }
        return view('livewire.commissions.virements', [
            "virements" => $virements->orderBy('name', 'desc')->latest()->get(),
        ]);
    }

}
