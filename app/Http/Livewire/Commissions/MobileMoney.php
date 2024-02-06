<?php

namespace App\Http\Livewire\Commissions;

use App\Models\Custumers\Country;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LevelCountry;
use Illuminate\Support\Facades\Auth;

class MobileMoney extends Component
{

    use WithPagination;
    public $search = "";
    public $transaction_id;
    public $transaction_status;
    public $transaction_name;
    public $percent_transaction;
    public $percent_transaction_under_distributer;
    public $percent_admin;
    public $percent_deposit;
    public $percent_banque;
    public $percent_with_drawal;

    //Distributers
    public $percent_admin_distibuter;
    public $percent_deposit_distibuter;
    public $percent_with_drawal_distibuter;

    public $distributer_custumer;
    public $comission_master;

    public function edit($id, $state)
    {
        $check = LevelCountry::whereId($id)->first();

        $this->transaction_id = $check->id;
        $this->transaction_name = $check->name;
        $this->percent_transaction = $check->percent * 100;
        $this->percent_admin = $check->percent_admin * 100;
        $this->percent_banque = $check->percent_banque * 100;
        $this->percent_with_drawal = $check->percent_with_drawal * 100;
        $this->percent_deposit = $check->percent_deposit * 100;
        $this->comission_master = $check->percent_masters * 100;

        // Under distributeurs
        $this->percent_transaction_under_distributer = $check->percent_under_distributer * 100;
        $this->percent_admin_distibuter = $check->percent_admin_under_distributer * 100;
        $this->percent_deposit_distibuter = $check->percent_deposit_under_distributer     * 100;
        $this->percent_with_drawal_distibuter = $check->percent_with_drawal_under_distributer * 100;

        // $this->distributer_custumer = $check->distributer_custumer;

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
                'percent_transaction_under_distributer' => 'required',
                'percent_admin_distibuter' => 'required',
                'comission_master' => 'required',
                // 'percent_deposit_distibuter' => 'required',
                // 'percent_with_drawal_distibuter' => 're/quired',
                
            ],
            [
                'transaction_name.required' => 'Ce champ est requis',
                'percent_admin.required' => 'Ce champ est requis',
                'percent_transaction.required' => 'Ce champ est requis',
                'percent_deposit.required' => 'Ce champ est requis',
                'comission_master.required' => 'Ce champ est requis',
            ]
        );
        $check = LevelCountry::whereId($this->transaction_id)->first();

        $check->update([
            'name' => $this->transaction_name,
            'percent' => $this->percent_transaction,
            'percent_admin' => $this->percent_admin,
            'percent_deposit' => $this->percent_deposit,
            'percent_with_drawal'  => $this->percent_with_drawal,
            'percent_banque'  => $this->percent_banque,
            // Under distributeur
            'percent_under_distributer'  => $this->percent_transaction_under_distributer,
            'percent_admin_under_distributer'  => $this->percent_admin_distibuter,
            'percent_deposit_under_distributer'  => $this->percent_deposit_distibuter,
            'percent_with_drawal_under_distributer'  => $this->percent_with_drawal_distibuter,
            'percent_masters'  => $this->comission_master,
            'status' => 1,
            'created_by' => Auth::guard('web')->user()->id,
        ]);

        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Commission banque modifié !');
    }



    public function edit_caf_caf($id, $state)
    {
        $check = LevelCountry::whereId($id)->first();
        $this->transaction_name = $check->name;
        $this->transaction_id = $check->id;
        $this->percent_transaction = $check->percent * 100;
        $this->comission_master = $check->percent_transaction * 100;
        $this->percent_admin = $check->percent_admin * 100;
        $this->percent_banque = $check->percent_banque * 100;
        $this->percent_with_drawal = $check->percent_with_drawal * 100;
        $this->percent_deposit = $check->percent_deposit * 100;
        $this->distributer_custumer = $check->distributer_custumer;
        $this->emit($state);
    }

    public function resetField()
    {
        $this->transaction_name = '';
        $this->percent_admin = '';
        $this->percent_deposit = '';
        $this->percent_with_drawal = '';
        $this->percent_transaction = '';
        $this->percent_transaction = '';
        $this->percent_banque = '';
        $this->comission_master = '';
        $this->emit('modal-hide', 'hide');
    }


    public function delete()
    {
        $check = LevelCountry::find($this->transaction_id);
        if (!empty($check)) {
            $check->destroy($this->transaction_id);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Internationnal supprimée !');
        }
    }

    public function render()
    {
        $inter_cost = LevelCountry::query();

        if ($this->search != "") {

            $this->resetPage();

            $inter_cost->where("name", "LIKE",  "%" . $this->search . "%");
        }
        return view('livewire.commissions.mobile-money', [
            "west_africa" => $inter_cost->orderBy('name', 'desc')->latest()->get(),
            "wests_africa" => Country::where('name_groupe', 'AFL')->orderBy('name', 'desc')->get(),
        ]);
    }
}
