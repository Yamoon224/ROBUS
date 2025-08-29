<?php

namespace App\Http\Livewire\Commissions;

use App\Models\PercentCompanie;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class CompagniePercent extends Component
{

    use WithPagination;
    public $search = "";
    public $transaction_id;
    public $transaction_status;
    public $percent_name;
    public $percent_companie;
    public $percent_admin;
    public $percent_deposit;
    public $percent_with_drawal;


    public function add()
    {

        $this->validate(
            [
                'percent_name' => 'required',
                'percent_admin' => 'required',
                'percent_companie' => 'required',
                'percent_deposit' => 'required',
                'percent_with_drawal' => 'required',
            ],
            [
                'percent_name.required' => 'Ce champ est requis',
                'percent_admin.required' => 'Ce champ est requis',
                'percent_companie.required' => 'Ce champ est requis',
                'percent_deposit.required' => 'Ce champ est requis',
                'percent_with_drawal.required' => 'Ce champ est requis',
            ]
        );

        $check = PercentCompanie::whereName($this->percent_name)->first();

        if (!$check) {
            
            PercentCompanie::create(
                [
                    'name' => $this->percent_name,
                    'percent'  => $this->percent_companie,
                    'percent_admin'  => $this->percent_admin,
                    'percent_deposit'  => $this->percent_deposit,
                    'percent_with_drawal'  => $this->percent_with_drawal,
                    'created_by' => Auth::guard('web')->user()->id,
                    'status'  => 1,

                ]
            );
            $this->percent_name = '';
            $this->percent_admin = '';
            $this->percent_deposit = '';
            $this->percent_with_drawal = '';
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Commission  ajouté !');
        } else $this->emit('error', 'Commission  existante !');
    }

    public function edit($id, $state)
    {
        $check = PercentCompanie::whereId($id)->first();

        $this->transaction_id = $check->id;
        $this->percent_name = $check->name;
        $this->percent_companie = $check->percent * 100;
        $this->percent_admin = $check->percent_admin * 100;
        $this->percent_with_drawal = $check->percent_with_drawal * 100;
        $this->percent_deposit = $check->percent_deposit * 100;
        $this->emit($state);
    }


    public function update()
    {
        $this->validate(
            [
                'percent_name' => 'required',
                'percent_admin' => 'required',
                'percent_companie' => 'required',
                'percent_deposit' => 'required',
                'percent_with_drawal' => 'required',
            ],
            [
                'percent_name.required' => 'Ce champ est requis',
                'percent_admin.required' => 'Ce champ est requis',
                'percent_companie.required' => 'Ce champ est requis',
                'percent_deposit.required' => 'Ce champ est requis',
                'percent_with_drawal.required' => 'Ce champ est requis',
            ]
        );

        $check = PercentCompanie::whereId($this->transaction_id)->first();

        $check->update([
            'name' => $this->percent_name,
            'percent' => $this->percent_companie,
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
        $this->percent_name = '';
        $this->percent_admin = '';
        $this->percent_deposit = '';
        $this->percent_with_drawal = '';
        $this->percent_with_drawal = '';
        $this->emit('modal-hide', 'hide');
    }


    public function delete()
    {
        $check = PercentCompanie::find($this->transaction_id);
        if (!empty($check)) {
            $check->destroy($this->transaction_id);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Pourcentage supprimée !');
        }
    }

    public function render()
    {
        $inter_cost = PercentCompanie::query();

        if ($this->search != "") {

            $this->resetPage();

            $inter_cost->where("name", "LIKE",  "%" . $this->search . "%");
        }
        return view('livewire.commissions.compagnie-percent', [
            "companie_percents" => $inter_cost->orderBy('name', 'desc')->latest()->get(),
        ]);
    }

}
