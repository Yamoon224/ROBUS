<?php

namespace App\Http\Livewire\Firms;

use Livewire\Component;
use App\Models\Categorie;
use Livewire\WithPagination;

class Categories extends Component
{
    use WithPagination;
    public $search = "";
    public $categorieId;
    public $categorieStatus;
    public $categorieName;
    public $offerAmount;
    public $categorieDescription;

    protected $rules = [
        'categorieName' => 'required',
    ];

    protected $messages = [
        'categorieName.required' => 'Ce champ est requis',
    ];


    public function add()
    {
        $this->validate();
        $check = Categorie::whereId($this->categorieName)->first();
        if (!$check) {
            Categorie::create(
                [
                    'name' => $this->categorieName,
                    'description'  => $this->categorieDescription,
                    'sid_user_id'  => firm()->id,
                    'status'  => 1
                ]
            );
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Categorie ajouté !');
        } else $this->emit('error', 'Categorie  existante !');
    }

    public function edit($id, $state)
    {
        $check = Categorie::whereId($id)->first();
        $this->categorieId = $check->id;
        $this->categorieName = $check->name;
        $this->categorieDescription = $check->description;
        $this->categorieStatus = $check->status;
        $this->emit($state);
    }

    public function update()
    {
        $this->validate();
        $check = Categorie::whereId($this->categorieId)->first();
        $check->update([
            'name' => $this->categorieName,
            'description' => $this->categorieDescription,
            'sid_user_id'  => firm()->id,
            'status' => 1,
        ]);

        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Categorie modifié !');
    }

    public function resetField()
    {
        $this->categorieName = '';
        $this->offerAmount = '';
        $this->categorieDescription = '';
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = Categorie::find($this->categorieId);
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Categorie activée !' : 'Categorie désactivée !');
        }
    }

    public function delete()
    {
        $check = Categorie::find($this->categorieId);
        if (!empty($check)) {
            $check->destroy($this->categorieId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Categorie supprimée !');
        }
    }

    public function render()
    {
        $categories = Categorie::query();

  
        if ($this->search != "") {

            $this->resetPage();

            $categories->where("name", "LIKE",  "%" . $this->search . "%");
        }
        return view('livewire.firms.categories', [
            "categories" => $categories->where('sid_user_id' , firm()->id)->orderBy('name', 'desc')->latest()->paginate(10),
        ]);
    }
  
}
