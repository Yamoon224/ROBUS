<?php

namespace App\Http\Livewire\Levels;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LevelTransaction;

class LevelTransactions extends Component
{

    use WithPagination;
    public $search = "";
    public $levelId;
    public $amount_begin;
    public $amount_end;
    public $amount;

    protected $rules = [
        'amount_begin' => 'required',
        'amount_end' => 'required',
        'amount' => 'required',
    ];

    protected $messages = [
        'amount_begin.required' => 'Ce champ est requis',
        'amount_end.required' => 'Le montant est requis',
        'amount.required' => 'Le montant est requis',
    ];


    public function add()
    {
        $this->validate();
        $check = LevelTransaction::whereId($this->levelId)->first();
        if (!$check) {
            LevelTransaction::create(
                [
                    'amount_begin' => $this->amount_begin,
                    'amount_end'  => $this->amount_end,
                    'amount'  => $this->amount,
                ]
            );
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Frais ajouté !');
        } else $this->emit('error', 'Frais  existante !');
    }

    public function edit($id, $state)
    {
        $check = LevelTransaction::whereId($id)->first();
        $this->levelId = $check->id;
        $this->amount_begin = $check->amount_begin;
        $this->amount_end = $check->amount_end;
        $this->amount = $check->amount;
        $this->emit($state);
    }

    public function update()
    {
        $this->validate();
        $check = LevelTransaction::whereId($this->levelId)->first();
        $check->update([
            'amount_begin' => $this->amount_begin,
            'amount_end'  => $this->amount_end,
            'amount'  => $this->amount,
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
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = LevelTransaction::find($this->levelId);
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Frais activée !' : 'Frais désactivée !');
        }
    }

    public function delete()
    {
        $check = LevelTransaction::find($this->levelId);
        if (!empty($check)) {
            $check->destroy($this->levelId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Frais supprimée !');
        }
    }

    public function render()
    {
        $level_invoire = LevelTransaction::query();
        return view('livewire.levels.level-transactions', [
            "level_ivoires" => $level_invoire->where('transaction_cost_id' , 3)->latest()->paginate(20),
        ]);
    }

}