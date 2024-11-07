<?php

namespace App\Http\Livewire\Commissions;

use Livewire\Component;
use App\Models\Providers\Provider;
use App\Models\Costs\OrderCardCost;
use Illuminate\Support\Facades\Auth;

class OrderCard extends Component
{

    public $search = "";
    public $orderId;
    public $orderCardStatus;
    public $amount;
    public $name;
    public $amount_merchant;
    public $provider_valeur;
    public $comissionCompanie;

    protected $rules = [
        'amount' => 'required',
        'amount_merchant' => 'required',
        'provider_valeur' => 'required',
        'comissionCompanie' => 'required',
    ];

    protected $messages = [
        'amount.required' => 'Ce champ est requis',
        'amount_merchant.required' => 'Ce champ est requis',
        'provider_valeur.required' => 'Ce champ est requis',
        'comissionCompanie.required' => 'Ce champ est requis',
    ];


    public function add()
    {
        $this->validate();
        $check = OrderCardCost::whereName($this->name)->first();
       
        if (!$check) {
       
            OrderCardCost::create(
                [
                    'name' => $this->name,
                    'amount'  => $this->amount,
                    'percent_merchands'  => $this->amount_merchant,
                    'created_by' => Auth::guard('web')->user()->id,
                    'provider_id' => $this->provider_valeur,
                    'percent_companie'  => $this->comissionCompanie,
                    'status'  => 1
                ]
            );
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Commission  ajouté !');
        } else $this->emit('error', 'Commission  existante !');
    }

    public function edit($id, $state)
    {
        $check = OrderCardCost::whereId($id)->first();
        $this->orderId = $id;
        $this->name = $check->name;
        $this->amount = $check->amount;
        $this->provider_valeur = $check->provider_id ?? null;
        $this->amount_merchant = $check->percent_merchands;
        $this->comissionCompanie = $check->percent_companie ?? null;
        $this->emit($state);
    }


    public function update()
    {
        $this->validate();
        $check = OrderCardCost::whereId($this->orderId)->first();
    
            $check->update([
                'name' => $this->name,
                'amount' => $this->amount,
                'percent_merchands'  => $this->amount_merchant,
                'status' => 1,
                'created_by' => Auth::guard('web')->user()->id,
                'percent_companie'  => $this->comissionCompanie,
                'provider_id' => $this->provider_valeur,
            ]);

            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Commission  modifié !');
       
    }

    public function resetField()
    {
        $this->amount = '';
        $this->amount_merchant = '';
        $this->name = '';
        $this->comissionCompanie = '';
        $this->provider_valeur = null;
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = OrderCardCost::first();
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? ' activée !' : ' désactivée !');
        }
    }

    public function delete()
    {
        $check = OrderCardCost::find($this->orderId);
        if (!empty($check)) {
            $check->destroy($this->orderId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', ' Paiement supprimée !');
        }
    }

    public function render()
    {
        $order_card_cost = OrderCardCost::query();

        if ($this->search != "") {

            $this->resetPage();

            $order_card_cost->where("name", "LIKE",  "%" . $this->search . "%");
        }
        return view('livewire.commissions.order-card', [
            "order_cards" => $order_card_cost->orderBy('name', 'desc')->latest()->get(),
            "providers" => Provider::whereStatus(true)->where('provider_type_id' , 5)->latest()->get(),
        ]);
    }
}