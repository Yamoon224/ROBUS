<?php

namespace App\Http\Livewire\Levels;

use Livewire\Component;
use App\Models\LevelDeposit;
use Livewire\WithPagination;

class LevelDepositInter extends Component
{

    use WithPagination;
    public $search = "";
    public $levelId;
    public $amount_begin;
    public $amount_end;
    public $amount;
    public $percent;
    public $percent_under_dist_percent;
    public $percent_under_dist_amount;
    public $percent_under_dist;
    public  $percent_admin;
    public $comission_master;

    protected $rules = [
        'amount_begin' => 'required',
        'amount_end' => 'required',
        'percent' => 'required',
        'percent_under_dist' => 'required',
        'comission_master' => 'required',

    ];

    protected $messages = [
        'amount_begin.required' => 'Ce champ est requis',
        'amount_end.required' => 'Le montant est requis',
        'percent.required' => 'Le montant est requis',
        'percent_under_dist.required' => 'Le montant est requis',
        'comission_master.required' => 'Le montant est requis',
    ];


    public function add()
    {
        $this->validate();
        $check = LevelDeposit::whereId($this->levelId)->first();
        if (!$check) {
            LevelDeposit::create(
                [
                    'amount_begin' => $this->amount_begin,
                    'amount_end'  => $this->amount_end,
                    'percent'  => $this->percent,
                    'comission_distributer'  => $this->percent_under_dist,
                    // 'percent_admin'  => $this->percent_admin,
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

        $check = LevelDeposit::whereId($id)->first();

        if (!empty($check)) {
            $this->levelId = $check->id;
            $this->amount_begin = $check->amount_begin;
            $this->amount_end = $check->amount_end;
            $this->amount = $check->amount;
            $this->comission_master = $check->amount != null ? $check->percent_masters : $check->percent_masters * 100;
            $this->percent = $check->percent == null ? $check->amount  : $check->percent * 100;
            $this->percent_under_dist =  $check->amount != null ? $check->comission_distributer : $check->comission_distributer * 100;
            $this->percent_under_dist_amount = $check->comission_distributer;
            $this->percent_admin = $check->percent_admin * 100;

            $this->emit($state);
        } else {
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('error ', 'Aucune donnée  !');
        }
    }

    public function update()
    {
        $this->validate();
        $check = LevelDeposit::whereId($this->levelId)->first();
        if (!empty($check)) {

            if ($check->percent == null) {

                $check->update([
                    'amount_begin' => $this->amount_begin,
                    'amount_end'  => $this->amount_end,
                    'amount'  => $this->percent,
                    // 'percent'  => $this->percent,
                    'comission_distributer'  =>  $this->percent_under_dist,
                    'percent_masters'  => $this->comission_master,
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
                    'comission_distributer'  =>  $this->percent_under_dist / 100,
                    'percent_masters'  => $this->comission_master / 100,
                    // 'comission_distributer'  =>  $this->percent_under_dist_amount ?? null,
                ]);
            }

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
        $this->percent = '';
        $this->amount = '';
        $this->percent_under_dist = '';
        $this->percent_under_dist_amount = '';
        $this->comission_master = '';
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = LevelDeposit::find($this->levelId);
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Frais activée !' : 'Frais désactivée !');
        }
    }

    public function delete()
    {
        $check = LevelDeposit::find($this->levelId);
        if (!empty($check)) {
            $check->destroy($this->levelId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Frais supprimée !');
        }
    }

    public function render()
    {
        $level_deposit = LevelDeposit::query();
        return view('livewire.levels.level-deposit-inter', [
            "level_inters" => $level_deposit->where('is_inter_bank', true)->latest()->get(),
        ]);
    }
}
