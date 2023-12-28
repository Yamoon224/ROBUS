<?php

namespace App\Http\Livewire\Levels;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LevelDepositIvoire;

class LevelDepositUnder extends Component
{

    use WithPagination;
    public $search = "";
    public $levelId;
    public $amount_begin;
    public $amount_end;
    public $amount;
    public $percent;
    public $percent_admin;
    public $percent_distributer;
    public $amount_admin;
    public $amount_distributer;

    protected $rules = [
        'amount_begin' => 'required',
        'amount_end' => 'required',
        'percent_admin' => 'required',
    ];

    protected $messages = [
        'amount_begin.required' => 'Ce champ est requis',
        'amount_end.required' => 'Le montant est requis',
        'percent_admin.required' => 'Le montant est requis',
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
        $check = LevelDepositIvoire::whereId($id)->first();
        if (!empty($check)) {

            $this->levelId = $check->id;
            $this->amount_begin = $check->amount_begin;
            $this->amount_end = $check->amount_end;
            $this->amount = $check->amount;
            // $this->percent = $check->percent;
            $this->percent = $check->percent == null ? $check->amount  : $check->percent * 100;
            $this->percent_distributer =  $check->amount != null ? $check->comission_distributer : $check->comission_distributer * 100;
            $this->percent_admin = $check->admin_percent;
            // $this->percent_distributer = $check->comission_distributer;
            $this->emit($state);
        } else {
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Frais ajouté !');
        }
    }

    public function update()
    {
        $this->validate();
        $check = LevelDepositIvoire::whereId($this->levelId)->first();

        if (!empty($check)) {

            if ($check->percent == null) {

                $check->update([
                    'amount_begin' => $this->amount_begin,
                    'amount_end'  => $this->amount_end,
                    'amount'  => $this->percent,
                    // 'percent'  => $this->percent,
                    'comission_distributer'  =>  $this->percent_distributer,
                    // 'comission_percent_distributer'  =>  $this->percent_under_dist_percent,
                    // 'percent_admin'  => $this->percent_admin,
                ]);
            } else {

                $check->update([
                    'amount_begin' => $this->amount_begin,
                    'amount_end'  => $this->amount_end,
                    // 'amount'  => $this->amount,
                    'percent'  => $this->percent / 100,
                    // 'percent_admin'  => $this->percent_admin,
                    'comission_distributer'  =>  $this->percent_distributer / 100,
                    // 'comission_distributer'  =>  $this->percent_under_dist_amount ?? null,
                ]);
            }
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Frais modifié !');
        } else {

            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Frais modifié !');
        }

        // $check->update([
        //     'amount_begin' => $this->amount_begin,
        //     'amount_end'  => $this->amount_end,
        //     'amount'  => $this->amount ?? null,
        //     'percent'  => $this->percent ?? null,
        //     'admin_percent'  => $this->percent_admin ?? null,
        //     'comission_distributer'  => $this->percent_distributer ?? null,
        // ]);

    }

    public function resetField()
    {
        $this->amount_begin = '';
        $this->amount_end = '';
        $this->amount = '';
        $this->percent = '';
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
        return view('livewire.levels.level-deposit-under', [
            "level_ivoires" => $level_invoire->where('is_ivoires', true)->latest()->get(),
        ]);
    }
}
