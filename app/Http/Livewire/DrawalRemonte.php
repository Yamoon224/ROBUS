<?php

namespace App\Http\Livewire;

use Livewire\Component;

class DrawalRemonte extends Component
{
    public $amount;
    public $amountDrawal;
    public $type;
    public $userId;

    public function mount($amount, $type, $userId)
    {
        $this->amount = $amount;
        $this->type = $type;
        $this->userId = $userId;
    }


    public function edit($amount , $state)
    {
        $this->amount = $amount;
        // $this->type = $type;
        // $this->userId = $userId;
        $this->emit($state);

    }

    public function submit()
    {
       
    }

    public function render()
    {
        return view('livewire.drawal-remonte');
    }
}
