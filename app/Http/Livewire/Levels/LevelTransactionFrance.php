<?php

namespace App\Http\Livewire\Levels;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LevelTransaction;

class LevelTransactionFrance extends Component
{

    use WithPagination;
    public $search = "";
    public $levelId;
    public $amount_begin;
    public $amount_end;
    public $amount;
    public $percent_admin;
    public $percent_distributer;
    public $percent_distributer_amount;
    public $amount_admin;
    public $amount_distributer;
    public $percent;

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
                    'percent'  => $this->percent,
                    'admin_percent'  => $this->percent_admin ?? null,
                    'comission_distributer	'  => $this->percent_distributer ?? null,
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
        if (!empty($check)) {

            $this->levelId = $check->id;
            $this->amount_begin = $check->amount_begin;
            $this->amount_end = $check->amount_end;
            $this->percent = $check->percent * 100;
            $this->amount = $check->amount;
            $this->percent_admin = $check->admin_percent;
            $this->percent_distributer = $check->comission_distributer;
            $this->percent_distributer_amount = $check->comission_distributer * 100;
            $this->emit($state);
        } else {
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('error', 'Pas de donnée!');
        }
    }

    public function update()
    {
        $this->validate();
        $check = LevelTransaction::whereId($this->levelId)->first();
        if (!empty($check)) {

            $check->update([
                'amount_begin' => $this->amount_begin,
                'amount_end'  => $this->amount_end,
                'amount'  => $this->amount,
                'percent'  => $this->percent,
                'admin_percent'  => $this->percent_admin ?? null,
                'comission_distributer'  => $this->percent_distributer ?? null,
            ]);

            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Frais modifié !');
        } else {
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('error', 'Aucune donnée !');
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
        return view('livewire.levels.level-transaction-france', [
            "level_ivoires" => $level_invoire->where('transaction_cost_id', 8)->latest()->paginate(20),
        ]);
    }
}
