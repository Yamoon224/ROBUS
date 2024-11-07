<?php

namespace App\Http\Livewire\Levels;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LevelBankIvoire;

class LevelBankCustumer extends Component
{


    use WithPagination;
    public $search = "";
    public $levelId;
    public $amount_begin;
    public $amount_end;
    public $amount;
    public $comission_distributer;
    public $comission_master;
    public $comissionCompanie;


    protected $rules = [
        'amount_begin' => 'required',
        'amount_end' => 'required',
        'amount' => 'required',
        'comission_distributer' => 'required',
        'comissionCompanie' => 'required',
    ];

    protected $messages = [
        'amount_begin.required' => 'Ce champ est requis',
        'amount_end.required' => 'Le montant est requis',
        'amount.required' => 'Le montant est requis',
        'comission_distributer.required' => 'Le montant est requis',
        'comissionCompanie.required' => 'Le montant est requis',
    ];


    public function add()
    {
        $this->validate();
        $check = LevelBankIvoire::whereId($this->levelId)->first();
        if (!$check) {
            LevelBankIvoire::create(
                [
                    'amount_begin' => $this->amount_begin,
                    'amount_end'  => $this->amount_end,
                    'amount'  => $this->amount,
                    'comission_distributer'  => $this->comission_distributer,
                    'percent_companie'  => $this->comissionCompanie,
                ]
            );
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Frais ajouté !');
        } else $this->emit('error', 'Frais  existante !');
    }

    public function edit($id, $state)
    {
        $check = LevelBankIvoire::whereId($id)->first();
        $this->levelId = $check->id;
        $this->amount_begin = $check->amount_begin;
        $this->amount_end = $check->amount_end;
        $this->amount = $check->amount;
        $this->comissionCompanie = $check->percent_companie;
        $this->comission_distributer = $check->comission_distributer;
        $this->emit($state);
    }

    public function update()
    {
        $this->validate();
        $check = LevelBankIvoire::whereId($this->levelId)->first();
        $check->update([
            'amount_begin' => $this->amount_begin,
            'amount_end'  => $this->amount_end,
            'amount'  => $this->amount,
            'comission_distributer'  => $this->comission_distributer,
            'percent_companie'  => $this->comissionCompanie,
        ]);

        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Frais modifié !');
    }

    public function resetField()
    {
        $this->amount_begin = '';
        $this->amount_end = '';
        $this->amount = '';
        $this->comission_distributer = '';
        $this->comissionCompanie = '';
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = LevelBankIvoire::find($this->levelId);
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Frais activée !' : 'Frais désactivée !');
        }
    }

    public function delete()
    {
        $check = LevelBankIvoire::find($this->levelId);
        if (!empty($check)) {
            $check->destroy($this->levelId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Frais supprimée !');
        }
    }

    public function render()
    {
        $level_invoire = LevelBankIvoire::query();
        return view('livewire.levels.level-bank-custumer', [
            "level_ivoires" => $level_invoire->where('bank_cost_id' , 3)->latest()->get(),
        ]);
    }
}
