<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Currency;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Currencies extends Component
{
     use WithPagination;
   
    protected $paginationTheme = "bootstrap";

    public $search = "";
    public $currencieName;
    public $currencieValue;
    public $currencieCode;
    public $currencieStatus;
    public $currencieId;
    public $currencie;

    protected $rules = [
        'currencieCode' => 'required',
        'currencieName' => 'required',
        'currencieValue' => 'required',
    ];

    protected $messages = [
        'currencieCode.required' => 'Ce champ est requis',
        'currencieName.required' => 'Ce champ est requis',
        'currencieValue.required' => 'Ce champ est requis',
    ];
    
     public function add()
    {
        $this->validate();
        $check = Currency::whereId($this->currencieId)->first();
        if(!$check) {
            
            Currency::create([
            'code' => $this->currencieCode , 
            'name' => $this->currencieName , 
            'value'  => $this->currencieValue , 
            'created_by' => Auth::guard('web')->user()->id,
            'status'  => 1]
        );
            $this->currencieCode = '';
            $this->currencieName = '';
            $this->currencieValue = '';
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Devise  ajouté !');
        }
        else $this->emit('error', 'Devise  existante !');
    }
    
    
    public function edit($id, $state)
    {
        $check = Currency::whereId($id)->first();
        $this->currencieId = $check->id;
        $this->currencieName = $check->name;
        $this->currencieCode = $check->code;
        $this->currencieValue = $check->value;
        $this->currencieStatus = $check->status;
        $this->emit($state);
    }


    
    public function update()
    {
        $this->validate();
        $check = Currency::whereId($this->currencieId)->first();
        $check->update([
            'code' =>  $this->currencieCode,
            'name' =>  $this->currencieName,
            'value' =>  $this->currencieValue,
            'status' => 1,
            'created_by' => Auth::guard('web')->user()->id,
        ]);
        
        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'La devise  modifié !');
    }
    
     public function resetField()
    {
        $this->currencieName = '';
         $this->currencieCode = '';
         $this->currencieValue = '';
        $this->emit('modal-hide', 'hide');
    }


    public function disable()
    {
        $check = Currency::find($this->currencieId);
        if(!empty($check)) {
            if($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Devise activée !' : 'Devise désactivée !');
        }
    }
    
    public function delete()
    {
        $check = Currency::find($this->currencieId);
        if(!empty($check)) {
            $check->destroy($this->currencieId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Devise supprimé !');
        }
    }
    
    public function render()
    {
        
        $currencie = Currency::query(); 
        
        if($this->search != ""){
            $this->resetPage();
            
            $currencie->where("code", "LIKE",  "%". $this->search ."%")
                         ->orWhere("name","LIKE",  "%". $this->search ."%");
        }

        return view('livewire.currencies' ,[
            "currencies" => $currencie->orderBy('name' , 'desc')->paginate(30)
        ]);
    }
    

}