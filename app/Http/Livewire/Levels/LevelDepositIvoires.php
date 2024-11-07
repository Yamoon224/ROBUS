<?php

namespace App\Http\Livewire\Levels;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LevelDepositIvoire;

class LevelDepositIvoires extends Component
{

    use WithPagination;
    public $search = "";
    public $levelId;
    public $amount_begin;
    public $amount_end;
    public $percent;
    public $amount;
    public $comission_master;
    public $comissionCompanie;

    protected $rules = [
        'amount_begin' => 'required',
        'amount_end' => 'required',
        'amount' => 'required',
        'comissionCompanie' => 'required',
    ];

    protected $messages = [
        'amount_begin.required' => 'Ce champ est requis',
        'amount_end.required' => 'Le montant est requis',
        'amount.required' => 'Le montant est requis',
        'comissionCompanie.required' => 'Le montant est requis',
    ];


    public function add()
    {
        $this->validate();
        $check = LevelDepositIvoire::whereId($this->levelId)->first();
        if (!$check) {
            LevelDepositIvoire::create(
                [
                    'amount_begin' => $this->amount_begin,
                    'amount_end'  => $this->amount_end,
                    'amount'  => $this->amount,
                    'percent'  => $this->percent,
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
        $check = LevelDepositIvoire::whereId($id)->first();
        if (!empty($check)) {

            $this->levelId = $check->id;
            $this->amount_begin = $check->amount_begin;
            $this->amount_end = $check->amount_end;
            $this->amount = $check->amount;
            $this->percent = $check->percent;
            $this->comissionCompanie = $check->percent_companie ;
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
        $check = LevelDepositIvoire::whereId($this->levelId)->first();
        if (!empty($check)) {
            $check->update([
                'amount_begin' => $this->amount_begin,
                'amount_end'  => $this->amount_end,
                'amount'  => $this->amount,
                'percent'  => $this->percent,
                'percent_companie'  => $this->comissionCompanie,
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
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = LevelDepositIvoire::find($this->levelId);
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Frais activée !' : 'Frais désactivée !');
        }
    }

    public function delete()
    {
        $check = LevelDepositIvoire::find($this->levelId);
        if (!empty($check)) {
            $check->destroy($this->levelId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Frais supprimée !');
        }
    }

    public function render()
    {
        $level_invoire = LevelDepositIvoire::query();
        return view('livewire.levels.level-deposit-ivoires', [
            "level_ivoires" => $level_invoire->where('is_ivoires', false)->latest()->get(),
        ]);
    }
}
