<?php

namespace App\Http\Livewire\Commissions;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Costs\TransactionCost;

class Transaction extends Component
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
    // Percentage
    public $percent_with_drawal_custumer;
    public $percent_with_drawal_admin;
    public $percent_with_drawal_companie;
    public $percent_with_drawal_master;

    public $distributer_custumer;
    public $comission_master;
    public $comissionCompanie;
    public $percent_scanner;


    public function add()
    {

        $this->validate(
            [
                'transaction_name' => 'required',
                'percent_admin' => 'required',
                'percent_transaction' => 'required',
                'percent_deposit' => 'required',
                'comission_canal' => 'required',
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
        $check = TransactionCost::whereName($this->transaction_name)->first();
        if (!$check) {
            TransactionCost::create(
                [
                    'name' => $this->transaction_name,
                    'percent_transaction'  => $this->percent_transaction,
                    'percent_admin'  => $this->percent_admin,
                    'percent_deposit'  => $this->percent_deposit,
                    'percent_with_drawal'  => $this->percent_with_drawal,
                    'created_by' => Auth::guard('web')->user()->id,
                    'percent_companie'  => $this->comissionCompanie,
                    'percent_custum_drawal'  => $this->percent_with_drawal_custumer,
                    'percent_custum_drawal_admin'  => $this->percent_with_drawal_admin,
                    'percent_custum_drawal_companie'  => $this->percent_with_drawal_companie,
                    'percent_custum_drawal_master'  => $this->percent_with_drawal_master,
                    'status'  => 1,

                ]
            );
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Commission  ajouté !');
        } else $this->emit('error', 'Commission  existante !');
    }

    public function edit($id, $state)
    {
        $check = TransactionCost::whereId($id)->first();

        $this->transaction_id = $check->id;
        $this->transaction_name = $check->name;
        $this->percent_transaction = $check->percent * 100;
        $this->percent_admin = $check->percent_admin * 100;
        $this->percent_with_drawal = $check->percent_with_drawal * 100;

        $this->percent_with_drawal_custumer = $check->percent_custum_drawal * 100;
        $this->percent_with_drawal_admin = $check->percent_custum_drawal_admin * 100;
        $this->percent_with_drawal_companie = $check->percent_custum_drawal_companie * 100;
        $this->percent_with_drawal_master = $check->percent_custum_drawal_master * 100;

        $this->percent_deposit = $check->percent_deposit * 100;
        $this->percent_scanner = $check->percent_under_scanner * 100;
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
            ],
            [
                'transaction_name.required' => 'Ce champ est requis',
                'percent_admin.required' => 'Ce champ est requis',
                'percent_transaction.required' => 'Ce champ est requis',
                'percent_deposit.required' => 'Ce champ est requis',
            ]
        );
        $check = TransactionCost::whereId($this->transaction_id)->first();

        $check->update([
            'name' => $this->transaction_name,
            'percent' => $this->percent_transaction,
            'percent_admin' => $this->percent_admin,
            'percent_deposit' => $this->percent_deposit,
            'percent_with_drawal'  => $this->percent_with_drawal,
            'percent_companie'  => $this->comissionCompanie,
            'percent_custum_drawal'  => $this->percent_with_drawal_custumer,
            'percent_custum_drawal_admin'  => $this->percent_with_drawal_admin,
            'percent_custum_drawal_companie'  => $this->percent_with_drawal_companie,
            'percent_custum_drawal_master'  => $this->percent_with_drawal_master,
            'percent_under_scanner'  => $this->percent_scanner,
            'status' => 1,
            'created_by' => Auth::guard('web')->user()->id,
        ]);

        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Commission banque modifié !');
    }

    public function add_caf_caf()
    {

        $this->validate(
            [
                'transaction_name' => 'required',
                'percent_admin' => 'required',
                'percent_transaction' => 'required',
                'percent_deposit' => 'required',
            ],
            [
                'transaction_name.required' => 'Ce champ est requis',
                'percent_admin.required' => 'Ce champ est requis',
                'percent_transaction.required' => 'Ce champ est requis',
                'percent_deposit.required' => 'Ce champ est requis',
            ]
        );
        $check = TransactionCost::whereName($this->transaction_name)->first();
        if (!$check) {
            TransactionCost::create(
                [
                    'name' => $this->transaction_name,
                    'percent_transaction'  => $this->percent_transaction,
                    'percent_admin'  => $this->percent_admin,
                    'percent_deposit'  => $this->percent_deposit,
                    'percent_with_drawal'  => $this->percent_with_drawal,
                    'percent_custum_drawal_admin'  => $this->percent_with_drawal_admin,
                    'percent_custum_drawal_companie'  => $this->percent_with_drawal_companie,
                    'percent_custum_drawal_master'  => $this->percent_with_drawal_master,
                    'created_by' => Auth::guard('web')->user()->id,
                    'status'  => 1
                ]
            );
            $this->transaction_name = '';
            $this->percent_admin = '';
            $this->percent_deposit = '';
            $this->percent_with_drawal = '';
            $this->percent_with_drawal_custumer = '';
            $this->percent_with_drawal_admin = '';
            $this->percent_with_drawal_companie = '';
            $this->percent_with_drawal_master = '';
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Commission  ajouté !');
        } else $this->emit('error', 'Commission  existante !');
    }

    public function edit_caf_caf($id, $state)
    {
        $check = TransactionCost::whereId($id)->first();
        $this->transaction_name = $check->name;
        $this->transaction_id = $check->id;
        $this->percent_transaction = $check->percent * 100;
        $this->percent_admin = $check->percent_admin * 100;
        $this->percent_with_drawal = $check->percent_with_drawal * 100;
        $this->percent_deposit = $check->percent_deposit * 100;
        $this->distributer_custumer = $check->distributer_custumer;
        $this->percent_scanner = $check->percent_under_scanner  * 100;
        $this->comissionCompanie = $check->percent_companie  * 100;
        $this->emit($state);
    }
    public function update_caf_caf()
    {
        $this->validate(
            [
                'transaction_name' => 'required',
                'percent_admin' => 'required',
                'percent_transaction' => 'required',
            ],
            [
                'transaction_name.required' => 'Ce champ est requis',
                'percent_admin.required' => 'Ce champ est requis',
                'percent_transaction.required' => 'Ce champ est requis',
            ]
        );
        $check = TransactionCost::whereId($this->transaction_id)->first();

        $check->update([
            'name' => $this->transaction_name,
            'percent' => $this->percent_transaction,
            'percent_admin' => $this->percent_admin,
            'percent_deposit' => $this->comissionCompanie,
            'percent_companie' => $this->percent_deposit,
            'percent_with_drawal'  => $this->percent_with_drawal,
            'status' => 1,
            'percent_under_scanner'  => $this->percent_scanner,
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
        $this->percent_deposit = '';
        $this->percent_with_drawal = '';
        $this->percent_with_drawal = '';
        $this->percent_transaction = '';
        $this->comissionCompanie = '';
        $this->percent_with_drawal_custumer = '';
        $this->percent_with_drawal_admin = '';
        $this->percent_with_drawal_companie = '';
        $this->percent_with_drawal_master = '';
        $this->percent_scanner = '';
        $this->emit('modal-hide', 'hide');
    }


    public function delete()
    {
        $check = TransactionCost::find($this->transaction_id);
        if (!empty($check)) {
            $check->destroy($this->transaction_id);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Internationnal supprimée !');
        }
    }

    public function render()
    {
        $inter_cost = TransactionCost::query();

        if ($this->search != "") {

            $this->resetPage();

            $inter_cost->where("name", "LIKE",  "%" . $this->search . "%");
        }
        return view('livewire.commissions.transaction', [
            "centre_afriques" => $inter_cost->orderBy('name', 'desc')->latest()->get(),
        ]);
    }
}
