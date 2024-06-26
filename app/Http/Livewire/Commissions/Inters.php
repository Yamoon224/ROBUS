<?php

namespace App\Http\Livewire\Commissions;

use Livewire\Component;
use App\Models\Costs\InterCost;
use Illuminate\Support\Facades\Auth;

class Inters extends Component
{
    public $search = "";
    public $interId;
    public $interStatus;
    public $interName;
    public $interPercent;
    public $orderCardMerchant;

  protected $rules = [
        'interName' => 'required',
        'interPercent' => 'required',
        'orderCardMerchant' => 'required'
    ];

    protected $messages = [
        'interName.required' => 'Ce champ est requis',
        'interPercent.required' => 'Ce champ est requis',
        'orderCardMerchant.required' => 'Ce champ est requis',
    ];

    
    public function add()
    {
        $this->validate();
        $check = InterCost::whereName($this->interName)->first();
        if(!$check) {
            InterCost::create(['name' => $this->interName , 
            'percent'  => $this->interPercent ,
                    'percent_merchands'  => $this->orderCardMerchant,
 
            'created_by' => Auth::guard('web')->user()->id,
            'status'  => 1]
        );
            $this->interName = '';
            $this->interPercent = '';
            $this->orderCardMerchant = '';
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Commission  ajouté !');
        }
        else $this->emit('error', 'Commission  existante !');
    }
    
    public function edit($id, $state)
    {
        $check = InterCost::whereId($id)->first();
        $this->interName = $check->name;
        $this->interPercent = $check->percent * 100;
        $this->orderCardMerchant = $check->percent_merchands * 100;
        $this->interStatus = $check->status;
        $this->emit($state);
    }

    public function update()
    {
        $this->validate();
        $check = InterCost::first();
        if ($this->orderCardMerchant > $this->interPercent) {
            $this->emit('modal-hide', 'hide');
            $this->emit('error', 'Svp verifier les comissions !');
        } else {
            $check->update([
                'name' => $this->interName,
                'percent' => $this->interPercent,
                'percent_merchands'  => $this->orderCardMerchant,
                'status' => 1,
                'created_by' => Auth::guard('web')->user()->id,
            ]);

            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Commission banque modifié !');
        }
      
    }

    public function resetField()
    {
        $this->interName = '';
        $this->interPercent = '';
        $this->orderCardMerchant = '';
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = InterCost::first();
        if(!empty($check)) {
            if($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Internationnal activée !' : 'Internationnal désactivée !');
        }
    }

    public function delete()
    {
        $check = InterCost::find($this->interId);
        if(!empty($check)) {
            $check->destroy($this->interId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Internationnal supprimée !');
        }
    }
    
    public function render()
    {
         $inter_cost = InterCost::query();   

        if($this->search != ""){
            
            $this->resetPage();

            $inter_cost->where("name", "LIKE",  "%". $this->search ."%");
        } 
        return view('livewire.commissions.inters' ,[
            "inters" => $inter_cost->orderBy('name' , 'desc')->latest()->paginate(1),
        ]);
        
    }
    

}