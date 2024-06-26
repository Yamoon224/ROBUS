<?php

namespace App\Http\Livewire;

use App\Models\Offer;
use Livewire\Component;
use Livewire\WithPagination;

class Offers extends Component
{

    use WithPagination;
    public $search = "";
    public $offerId;
    public $offerStatus;
    public $offerName;
    public $offerAmount;
    public $offerDescription;

    protected $rules = [
        'offerName' => 'required',
        'offerAmount' => 'required',
    ];

    protected $messages = [
        'offerName.required' => 'Ce champ est requis',
        'offerAmount.required' => 'Le montant est requis',
    ];


    public function add()
    {
        $this->validate();
        $check = Offer::whereId($this->offerId)->first();
        if (!$check) {
            Offer::create(
                [
                    'name' => $this->offerName,
                    'description'  => $this->offerDescription,
                    'amount'  => $this->offerAmount,
                    'status'  => 1
                ]
            );
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Offre ajouté !');
        } else $this->emit('error', 'Offre  existante !');
    }

    public function edit($id, $state)
    {
        $check = Offer::whereId($id)->first();
        $this->offerId = $check->id;
        $this->offerName = $check->name;
        $this->offerAmount = $check->amount;
        $this->offerDescription = $check->description;
        $this->offerStatus = $check->status;
        $this->emit($state);
    }

    public function update()
    {
        $this->validate();
        $check = Offer::whereId($this->offerId)->first();
        $check->update([
            'name' => $this->offerName,
            'description' => $this->offerDescription,
            'amount' => $this->offerAmount,
            'status' => 1,
        ]);

        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Offre modifié !');
    }

    public function resetField()
    {
        $this->offerName = '';
        $this->offerAmount = '';
        $this->offerDescription = '';
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = Offer::find($this->offerId);
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'offer activée !' : 'offer désactivée !');
        }
    }

    public function delete()
    {
        $check = Offer::find($this->offerId);
        if (!empty($check)) {
            $check->destroy($this->offerId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'offer supprimée !');
        }
    }

    public function render()
    {
        $offers = Offer::query();

        if ($this->search != "") {

            $this->resetPage();

            $offers->where("name", "LIKE",  "%" . $this->search . "%");
        }
        return view('livewire.offers', [
            "offers" => $offers->orderBy('name', 'desc')->latest()->paginate(10),
        ]);
    }
}