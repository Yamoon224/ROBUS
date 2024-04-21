<?php

namespace App\Http\Livewire;

use App\Models\Currency;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Custumers\Country;

class Countries extends Component
{

    use WithPagination;

    protected $paginationTheme = "bootstrap";

    public $search = "";
    public $countireName;
    public $countireCode;
    public $countrieStatus;
    public $countrieId;
    public $countrie_indic;
    public $currencie;
    public $ceiling;
    public $silver_no_identity;
    public $name_groupe;
    public $amount_max_send;

    protected $rules = [
        'currencie' => 'required',
        'countrie_indic' => 'required',
        'ceiling' => 'required',
        'amount_max_send' => 'required',
    ];

    protected $messages = [
        'currencie.required' => 'Ce champ est requis',
        'countrie_indic.required' => 'Ajouter indicatif',
        'ceiling.required' => 'Ajouter le plafond',
        'amount_max_send.required' => 'Ajouter le montant maxi ',
    ];

    public function edit($id, $state)
    {
        $check = Country::whereId($id)->first();
        $this->countrieId = $id;
        $this->countrie_indic = $check->indecative;
        $this->currencie = $check->currencie->id ?? 1;
        $this->countrieStatus = $check->robus;
        $this->silver_no_identity = $check->silver_ceiling_no_identity;
        $this->ceiling = $check->silver_ceiling;
        $this->name_groupe = $check->name_groupe;
        $this->amount_max_send = $check->amount_max_send;
        $this->emit($state);
    }


    public function update()
    {
        $this->validate();
        $check = Country::whereId($this->countrieId)->first();

        $currencie = Currency::whereId($this->currencie)->first();

        $check->update([
            'currencie_id' =>  $currencie->id,
            'indecative' =>   $this->countrie_indic,
            'silver_ceiling' =>   $this->ceiling,
            'silver_ceiling_no_identity'  =>   $this->silver_no_identity,
            'name_groupe' => $this->name_groupe,
            'amount_max_send' => $this->amount_max_send,
        ]);

        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'La devise  modifié !');
    }

    public function resetField()
    {
        $this->currencie = '';
        $this->countrie_indic = '';
        $this->ceiling  = '';
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = Country::find($this->countrieId);
        if (!empty($check)) {
            if ($check->robus == 0) $check->update(
                [
                    'robus' => 1,
                    'status' => 1

                ]
            );
            else $check->update([
                'robus' => 0,
                'status' => 0
            ]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->robus == 1 ? 'Pays activée !' : 'Pays désactivée !');
        }
    }


    public function render()
    {

        $countrie = Country::query();

        if ($this->search != "") {

            $this->resetPage();

            $countrie->where("code", "LIKE",  "%" . $this->search . "%")
                ->orWhere("name", "LIKE",  "%" . $this->search . "%");
        }

        return view('livewire.countries', [
            "countries" => $countrie->orderBy('name', 'desc')->paginate(30),
            'currencies' => $this->currencies
        ]);
    }

    public function getCurrenciesProperty()
    {
        return Currency::all();
    }
}
