<?php

namespace App\Http\Livewire;

use App\Models\Pub;
use Livewire\Component;
use Livewire\WithPagination;

class Advertisement extends Component
{

    use WithPagination;
    public $search = "";
    public $pubId;
    public $message;
    public $title;
    public $status;

    public function edit($id, $state)
    {
        $check = Pub::whereId($id)->first();
        $this->pubId = $check->id;
        $this->title = $check->title;
        $this->message = $check->messages;
        $this->status = $check->status;
        $this->emit($state);
    }

    public function disable()
    {
        $check = Pub::find($this->pubId);
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Publicté activée !' : 'Publicté désactivée !');
        }
    }

    public function delete()
    {
        $check = Pub::find($this->pubId);
        if (!empty($check)) {
            $check->destroy($this->pubId);
            $this->emit('modal-hide', 'hide');

            $this->emit('success', 'Publicté supprimée !');
        }
    }

    public function resetField()
    {
        $this->title = '';
        $this->message = '';
        $this->emit('modal-hide', 'hide');
    }

    public function render()
    {
        $pubs = Pub::query();

        if ($this->search != "") {

            $this->resetPage();

            $pubs->where("messages", "like",  "%" . $this->search . "%");
        }

        return view('livewire.advertisement', [

            "pubs" => $pubs->orderBy('messages', 'desc')->latest()->paginate(20),
        ]);
    }
}
