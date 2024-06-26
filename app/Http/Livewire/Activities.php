<?php

namespace App\Http\Livewire;

use App\Models\EventLog;
use Livewire\Component;
use Livewire\WithPagination;

class Activities extends Component
{

    use WithPagination;
   
    protected $paginationTheme = "bootstrap";
    public $search = "";

    public function render()
    {

         
        $event = EventLog::query(); 
        
        if($this->search != ""){
            $this->resetPage();
            
            $event->where("event_name", "LIKE",  "%". $this->search ."%")
                         ->orWhere("event_user","LIKE",  "%". $this->search ."%")
                         ->orWhere("type_user_event","LIKE",  "%". $this->search ."%")
                         ->orWhere("user_countrie","LIKE",  "%". $this->search ."%")
                         ->orWhere("type_user","LIKE",  "%". $this->search ."%");
        }
     

        return view('livewire.activities',[
            "events" => $event->orderBy('event_name' , 'asc')->paginate(30)]);
    }
}
