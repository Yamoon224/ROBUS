<?php

namespace App\Http\Livewire\Commissions\Transactions;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Costs\TransactionCost;

class CentreAfrique extends Component
{

    public $search = "";
    public $transaction_id;
    public $transaction_status;
    public $transaction_name;
    public $percent_transaction;
    public $percent_admin;
    public $percent_deposit;
    public $percent_with_drawal;

    protected $rules = [
        'transaction_name' => 'required',
        'percent_admin' => 'required',
        'percent_transaction' => 'required',
        'percent_deposit' => 'required',
    ];

    protected $messages = [
        'transaction_name.required' => 'Ce champ est requis',
        'percent_admin.required' => 'Ce champ est requis',
        'percent_transaction.required' => 'Ce champ est requis',
        'percent_deposit.required' => 'Ce champ est requis',
    ];


    public function add()
    {
        $this->validate();
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
                    'status'  => 1
                ]
            );
            $this->transaction_name = '';
            $this->percent_admin = '';
            $this->percent_deposit = '';
            $this->percent_with_drawal = '';
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Commission  ajouté !');
        } else $this->emit('error', 'Commission  existante !');
    }

    public function edit($id, $state)
    {
        $check = TransactionCost::whereId($id)->first();
        $this->transaction_name = $check->name;
        $this->transaction_id = $check->id;
        $this->percent_transaction = $check->percent * 100;
        $this->percent_admin = $check->percent_admin * 100;
        $this->percent_with_drawal = $check->percent_with_drawal * 100;
        $this->percent_deposit = $check->percent_deposit * 100;
        $this->emit($state);
    }

    public function update()
    {
        $this->validate();
        $check = TransactionCost::whereId($this->transaction_id)->first();
        
        $check->update([
            'name' => $this->transaction_name,
            'percent' => $this->percent_transaction,
            'percent_admin' => $this->percent_admin,
            'percent_deposit' => $this->percent_deposit,
            'percent_with_drawal'  => $this->percent_with_drawal,
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
        $this->percent_deposit = '';
        $this->percent_with_drawal = '';
        $this->percent_transaction = '';
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
        return view('livewire.commissions.transactions.centre-afrique', [
            "centre_afriques" => $inter_cost->orderBy('name', 'desc')->latest()->get(),
        ]);
    }
}