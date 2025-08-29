<?php

namespace App\Http\Livewire;

use App\Models\AppCloseCompany;
use Livewire\Component;
use Livewire\WithPagination;

class CloseCompany extends Component
{

    use WithPagination;

    protected $paginationTheme = "bootstrap";

    public $search = "";
    public $time_begin;
    public $time_end;
    public $appStatus;
    public $robusId;
    public $robus_days;
    public $description;

    protected $rules = [
        'time_end' => 'required',
        'description' => 'required',
        'time_begin' => 'required',
    ];

    protected $messages = [
        'time_end.required' => 'Ce champ est requis',
        'description.required' => 'Ce champ est requis',
        'time_begin.required' => 'Ce champ est requis',
    ];


    public function edit($id, $state)
    {
        $check = AppCloseCompany::whereId($id)->first();
        $this->robusId = $check->id;
        $this->robus_days = $check->day_of_week;
        $this->time_begin = $check->time_start;
        $this->time_end = $check->time_end;
        $this->appStatus = $check->status;
        $this->description = $check->description;
        $this->emit($state);
    }


    public function updateApp()
    {
        $this->validate();
        $check = AppCloseCompany::whereId($this->robusId)->first();
        $check->update([
            'time_end' =>  $this->time_end,
            'time_start' =>  $this->time_begin,
            'description' =>  $this->description,
            'status' => 1,
            'created_by' => sabdy()->id,
        ]);

        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'La devise  modifié !');
    }

    public function resetField()
    {
        $this->description = '';
        $this->time_end = '';
        $this->time_begin = '';
        $this->emit('modal-hide', 'hide');
    }


    public function disable()
    {
        $check = AppCloseCompany::find($this->robusId);
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Application activée !' : 'Application désactivée !');
        }
    }


    public function render()
    {
        $closerobusQuery = AppCloseCompany::query();
    
        if (!empty($this->search)) {
            $this->resetPage(); 
            $closerobusQuery->where('day_of_week', 'LIKE', '%' . $this->search . '%');
        }
    
        $closeapps = $closerobusQuery->orderBy('id', 'asc')->get();
    
        return view('livewire.close-company', compact('closeapps'));
    }

   
}
