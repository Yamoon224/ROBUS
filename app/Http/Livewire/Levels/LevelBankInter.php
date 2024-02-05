<?php

namespace App\Http\Livewire\Levels;

use Livewire\Component;
use App\Models\LevelBank;
use Livewire\WithPagination;

class LevelBankInter extends Component
{

    use WithPagination;
    public $search = "";
    public $levelId;
    public $amount_begin;
    public $amount_end;
    public $percent;
    public $comission_distributer;
    public $comission_master;

    protected $rules = [
        'amount_begin' => 'required',
        'amount_end' => 'required',
        'percent' => 'required',
        'comission_distributer' => 'required',
        'comission_master' => 'required',
    ];

    protected $messages = [
        'amount_begin.required' => 'Ce champ est requis',
        'amount_end.required' => 'Le montant est requis',
        'percent.required' => 'Le montant est requis',
        'comission_distributer.required' => 'Le pourcentage est requis',
        'comission_master.required' => 'Le montant est requis',
    ];


    public function add()
    {
        $this->validate();
        $check = LevelBank::whereId($this->levelId)->first();
        if (!$check) {
            LevelBank::create(
                [
                    'amount_begin' => $this->amount_begin,
                    'amount_end'  => $this->amount_end,
                    'percent'  => $this->percent,
                    'comission_distributer'  => $this->comission_distributer,
                    'percent_masters'  => $this->comission_master,
                ]
            );
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Frais ajouté !');
        } else $this->emit('error', 'Frais  existante !');
    }

    public function edit($id, $state)
    {
        $check = LevelBank::whereId($id)->first();
        if (!empty($check)) {
            $this->levelId = $check->id;
            $this->amount_begin = $check->amount_begin;
            $this->amount_end = $check->amount_end;
            $this->comission_master = $check->percent_masters * 100;
            $this->percent = $check->percent * 100;
            $this->comission_distributer = $check->comission_distributer * 100;
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
        $check = LevelBank::whereId($this->levelId)->first();
        if (!empty($check)) {
            $check->update([
                'amount_begin' => $this->amount_begin,
                'amount_end'  => $this->amount_end,
                'percent'  => $this->percent,
                'comission_distributer'  => $this->comission_distributer,
                'percent_masters'  => $this->comission_master,
            ]);

            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Frais modifié !');
        } else {
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('error', 'Aucune modifié !');
        }
    }

    public function resetField()
    {
        $this->amount_begin = '';
        $this->amount_end = '';
        $this->percent = '';
        $this->comission_master = '';
        $this->comission_distributer = '';
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = LevelBank::find($this->levelId);
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Frais activée !' : 'Frais désactivée !');
        }
    }

    public function delete()
    {
        $check = LevelBank::find($this->levelId);
        if (!empty($check)) {
            $check->destroy($this->levelId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Frais supprimée !');
        }
    }

    public function render()
    {
        $level_inter = LevelBank::query();
        return view('livewire.levels.level-bank-inter', [
            "level_inters" => $level_inter->latest()->get(),
        ]);
    }
}
