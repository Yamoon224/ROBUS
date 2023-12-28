<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Custumers\City;
use App\Models\Custumers\Country;

class Cities extends Component
{
    
    use WithPagination;
   
    protected $paginationTheme = "bootstrap";

    public $search = "";
    public $citieStatus;
    public $citieId;
    public $countriesFilter = "";

     public function edit($id, $state)
    {
        $check = City::whereId($id)->first();
        $this->citieId = $id;
        $this->citieStatus = $check->status;
        $this->emit($state);
    }
    
    public function disable()
    {
        $check = City::find($this->citieId);
        if(!empty($check)) {
            if($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Ville activée !' : 'Ville désactivée !');
        }
    }
    
    
    public function render()
    {
        
        $citie = City::query(); 
        
        if($this->search != ""){
            $this->resetPage();
            
            $citie->where("admin_name", "LIKE",  "%". $this->search ."%")
                         ->orWhere("name","LIKE",  "%". $this->search ."%");
        }
        
       if($this->countriesFilter != ""){
            $citie->where("country_id", $this->countriesFilter);
        }
        
        return view('livewire.cities' ,[
            "cities" => $citie->orderBy('name' , 'asc')->paginate(25) , 
            "countries"=> Country::where('robus' , true)->orderBy("name", 'desc')->get()]);
    }

}