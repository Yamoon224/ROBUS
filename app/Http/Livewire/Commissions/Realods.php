<?php

namespace App\Http\Livewire\Commissions;

use Livewire\Component;
use App\Models\Costs\ReloadCost;
use Illuminate\Support\Facades\Auth;

class Realods extends Component
{
    public $search = "";
    public $reloadId;
    public $reloadStatus;
    public $reloadName;
    public $reloadPercent;
    public $orderCardMerchant;

    protected $rules = [
        'reloadName' => 'required',
        'reloadPercent' => 'required',
        'orderCardMerchant' => 'required',
    ];

    protected $messages = [
        'reloadName.required' => 'Ce champ est requis',
        'reloadPercent.required' => 'Ce champ est requis',
        'orderCardMerchant.required' => 'Ce champ est requis',
    ];


    public function add()
    {
        $this->validate();
        $check = ReloadCost::whereName($this->reloadName)->first();
        if (!$check) {
            ReloadCost::create(
                [
                    'name' => $this->reloadName,
                    'percent'  => $this->reloadPercent,
                    'percent_merchands'  => $this->orderCardMerchant,

                    'created_by' => Auth::guard('web')->user()->id,
                    'status'  => 1
                ]
            );
            $this->reloadName = '';
            $this->reloadPercent = '';
            $this->orderCardMerchant = '';
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Commission  ajouté !');
        } else $this->emit('error', 'Commission  existante !');
    }

    public function edit($id, $state)
    {
        $check = ReloadCost::whereId($id)->first();
        $this->reloadName = $check->name;
        $this->reloadPercent = $check->percent * 100;
        $this->orderCardMerchant = $check->percent_merchands * 100;
        $this->reloadStatus = $check->status;
        $this->emit($state);
    }

    public function update()
    {
        $this->validate();
        $check = ReloadCost::first();
        if ($this->orderCardMerchant > $this->reloadPercent) {
            $this->emit('modal-hide', 'hide');
            $this->emit('error', 'Svp verifier les comissions !');
        } else {
            $check->update([
                'name' => $this->reloadName,
                'percent' => $this->reloadPercent,
                'percent_merchands'  => $this->orderCardMerchant,
                'status' => 1,
                'created_by' => Auth::guard('web')->user()->id,
            ]);

            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Commission  modifié !');
        }
    }

    public function resetField()
    {
        $this->reloadName = '';
        $this->reloadPercent = '';
        $this->orderCardMerchant = '';
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = ReloadCost::first();
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? ' activée !' : ' désactivée !');
        }
    }

    public function delete()
    {
        $check = ReloadCost::find($this->reloadId);
        if (!empty($check)) {
            $check->destroy($this->reloadId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Rechargement supprimée !');
        }
    }

    public function render()
    {
        $reload_cost = ReloadCost::query();

        if ($this->search != "") {

            $this->resetPage();

            $reload_cost->where("name", "LIKE",  "%" . $this->search . "%");
        }
        return view('livewire.commissions.realods', [
            "realods" => $reload_cost->orderBy('name', 'desc')->latest()->paginate(1),
        ]);
    }
}