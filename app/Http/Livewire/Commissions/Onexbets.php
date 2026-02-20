<?php

namespace App\Http\Livewire\Commissions;

use App\Models\Onexbet;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Onexbets extends Component
{

    use WithPagination;
    public $search = "";
    public $transaction_id;
    public $transaction_status;
    public $transaction_name;
    public $percent_transaction;
    public $percent_admin;
    public $percent_deposit;
    public $percent_with_drawal;
    public $distributer_custumer;
    public $distributer_drawal;
    public $comission_master;
    public $comissionCompanie;
    public $editTypeTresort = false;
    public $editTypePush = false;
    public $editTypeDjamo = false;
    public $editTypeXbet = false;


    public function edit($id, $state)
    {
        $check = Onexbet::whereId($id)->first();

        $this->transaction_id = $check->id;
        $this->transaction_name = $check->name;
        $this->editTypeXbet = true;
        $this->percent_transaction = $check->percent * 100;
        $this->percent_admin = $check->percent_admin * 100;
        $this->percent_with_drawal = $check->percent_with_drawal * 100;
        $this->distributer_drawal = $check->percent_under_drawal * 100;
        $this->percent_deposit = $check->percent_merchands * 100;
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
                'percent_deposit' => 'required',
                'comissionCompanie' => 'required',
            ],
            [
                'transaction_name.required' => 'Ce champ est requis',
                'percent_admin.required' => 'Ce champ est requis',
                'percent_transaction.required' => 'Ce champ est requis',
                'percent_deposit.required' => 'Ce champ est requis',
                'comissionCompanie.required' => 'Ce champ est requis',
            ]
        );
        $check = Onexbet::whereId($this->transaction_id)->first();

        $check->update([
            'name' => $this->transaction_name,
            'percent' => $this->percent_transaction,
            'percent_admin' => $this->percent_admin,
            'percent_merchands' => $this->percent_deposit,
            'percent_with_drawal'  => $this->percent_with_drawal,
            'percent_companie'  => $this->comissionCompanie,
            'percent_under_drawal'  => $this->distributer_drawal,
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
        $this->percent_admin = 0;
        $this->percent_deposit = 0;
        $this->percent_with_drawal = 0;
        $this->percent_transaction = 0;
        $this->comissionCompanie = 0;
         $this->distributer_drawal= 0;
        $this->emit('modal-hide', 'hide');
    }

    public function delete()
    {
        $check = Onexbet::find($this->transaction_id);
        if (!empty($check)) {
            $check->destroy($this->transaction_id);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Internationnal supprimée !');
        }
    }


    public function render()
    {

        $xbets = Onexbet::query();

        if ($this->search != "") {

            $this->resetPage();

            $xbets->where("name", "LIKE",  "%" . $this->search . "%");
        }
        return view('livewire.commissions.onexbets', [
            "onexbets" => $xbets->orderBy('name', 'desc')->latest()->get(),
        ]);
    }
}
