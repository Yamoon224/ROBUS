<?php

namespace App\Http\Livewire\Levels;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LevelTransactionIvoireInter;

class LevelTransactionIvoireInters extends Component
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
        $check = LevelTransactionIvoireInter::whereId($this->levelId)->first();
        if (!$check) {
            LevelTransactionIvoireInter::create(
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
        $check = LevelTransactionIvoireInter::whereId($id)->first();
        if (!empty($check)) {
            $this->levelId = $check->id;
            $this->amount_begin = $check->amount_begin;
            $this->amount_end = $check->amount_end;
            $this->amount = $check->amount;
            $this->emit($state);
        } else {
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('error', 'Pas de donnée !');
        }
    }

    public function update()
    {
        $this->validate();
        $check = LevelTransactionIvoireInter::whereId($this->levelId)->first();
        if (!empty($check)) {

            $check->update([
                'amount_begin' => $this->amount_begin,
                'amount_end'  => $this->amount_end,
                'amount'  => $this->amount,
            ]);

            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Frais modifié !');
        } else {
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('error', 'pas de donnée !');
        }
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
        $check = LevelTransactionIvoireInter::find($this->levelId);
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Frais activée !' : 'Frais désactivée !');
        }
    }

    public function delete()
    {
        $check = LevelTransactionIvoireInter::find($this->levelId);
        if (!empty($check)) {
            $check->destroy($this->levelId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Frais supprimée !');
        }
    }

    public function render()
    {
        $level_invoire = LevelTransactionIvoireInter::query();
        return view('livewire.levels.level-transaction-ivoire-inters', [
            "level_ivoires" => $level_invoire->where('transaction_cost_id', 5)->latest()->paginate(20),
        ]);
    }
}
