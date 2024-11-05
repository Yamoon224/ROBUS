<?php

namespace App\Http\Livewire\Companies;

use Livewire\Component;
use Livewire\WithPagination;


class UnassignEmployers extends Component
{
    public function render()
    {
        return view('livewire.companies.unassign-employers');
    }
}
