<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MessageTransaction;

class MessageSend extends Component
{

    use WithPagination;
    public $search = "";
    public $messageStatus;

    public function render()
    {
        $messages = MessageTransaction::query();

        if ($this->search != '') {
            $this->resetPage();

            $messages->where(
                'messages',
                'like',
                '%' . $this->search . '%'
            );
        }

        return view('livewire.message-send', [
            "messages" => $messages->orderBy('created_at', 'desc')->latest()->paginate(30),
        ]);
    }
}
