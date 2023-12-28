<?php

namespace App\Http\Livewire;

use App\Models\Sid\SidUser;
use Livewire\Component;

class Searchcountrie extends Component
{
    public  $query = '';
    public $sidUsers = [];
    public int $selectedIndex = 0;

    public function incrementIndex()
    {
        if ($this->selectedIndex === (count($this->sidUsers) - 1)) {
            $this->selectedIndex = 0;
            return;
        }

        $this->selectedIndex++;
    }

    public function decrementIndex()
    {
        if ($this->selectedIndex === 0) {
            $this->selectedIndex = count($this->sidUsers) - 1;
            return;
        }

        $this->selectedIndex--;
    }


    public function resetIndex()
    {
        $this->reset('selectedIndex');
    }

    public function showSidUsers()
    {
        if ($this->sidUsers) {
            $this->redirect(route('payments.show', $this->sidUsers[$this->selectedIndex]['id']));
        }
    }

    public function updatedQuery()
    {
        $this->resetIndex();
        $words = '%' . $this->query . '%';

        if (strlen($this->query)  >= 2) {
            $this->sidUsers = SidUser::where('transaction_id', 'like',   $words)
                ->orWhere('operator_id', 'like', $words)
                ->with('wallet')
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.searchcountrie');
    }
}