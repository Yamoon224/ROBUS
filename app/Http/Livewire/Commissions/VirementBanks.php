<?php

namespace App\Http\Livewire\Commissions;

use Livewire\Component;
use App\Models\VirementPercent;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class VirementBanks extends Component
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
    public $comission_master;
    public $comissionCompanie;

    public $percent_bank_custumer = 0;
    public $percent_bank_under = 0;
    public $percent_custumer = 0;
    public $percent_admin_custumer = 0;

    public $editTypeTresort = false;
    public $editTypePush = false;
    public $editTypeDjamo = false;
    public $editTypeXbet = false;


    public function edit($id, $state)
    {
        $check = VirementPercent::whereId($id)->first();

        $this->transaction_id = $check->id;
        $this->transaction_name = $check->name;
        $this->editTypeXbet = true;
        $this->percent_transaction = $check->percent * 100;
        $this->percent_admin = $check->percent_admin * 100;
        $this->percent_with_drawal = $check->percent_with_drawal * 100;
        $this->percent_deposit = $check->percent_merchands * 100;
        $this->comissionCompanie = $check->percent_companie * 100;
        $this->distributer_custumer = $check->distributer_custumer;

        $this->percent_custumer = $check->percent_custumer * 100 ?? null;
        $this->percent_admin_custumer = $check->percent_admin_custumer * 100 ?? null;
        $this->percent_bank_custumer = $check->percent_bank_custumer * 100 ?? null;
        $this->percent_bank_under = $check->percent_bank_under * 100;

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
        $check = VirementPercent::whereId($this->transaction_id)->first();

        $check->update([
            'name' => $this->transaction_name,
            'percent' => $this->percent_transaction,
            'percent_admin' => $this->percent_admin,
            'percent_merchands' => $this->percent_deposit,
            'percent_with_drawal'  => $this->percent_with_drawal,
            'percent_companie'  => $this->comissionCompanie,

            'percent_custumer'  => $this->percent_custumer,
            'percent_admin_custumer'  => $this->percent_admin_custumer,
            'percent_bank_custumer'  => $this->percent_bank_custumer,
            // 'percent_bank_under'  => $this->percent_bank_under,

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
        $this->percent_custumer = 0;
        $this->percent_admin_custumer = 0;
        $this->percent_bank_custumer = 0;
        $this->percent_bank_under = 0;
        $this->emit('modal-hide', 'hide');
    }

    public function delete()
    {
        $check = VirementPercent::find($this->transaction_id);
        if (!empty($check)) {
            $check->destroy($this->transaction_id);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Internationnal supprimée !');
        }
    }


    public function render()
    {

        $virements = VirementPercent::query();

        if ($this->search != "") {

            $this->resetPage();

            $virements->where("name", "LIKE",  "%" . $this->search . "%");
        }
        return view('livewire.commissions.virement-banks', [
            "virementbanks" => $virements->orderBy('name', 'desc')->latest()->get(),
        ]);
    }
}
