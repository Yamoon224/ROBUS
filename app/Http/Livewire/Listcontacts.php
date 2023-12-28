<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Contacts\ContactList;
use Illuminate\Support\Facades\Auth;

class Listcontacts extends Component
{
   
    use WithPagination;
    public $search = "";
    public $contactId;
    public $contactStatus;
    public $contactName;
    public $contactDescription;
    public $contactLists;
    
  protected $rules = [
        'contactName' => 'required',
    ];

    protected $messages = [
        'contactName.required' => 'Ce champ est requis',
    ];
       
    
    public function add()
    {
        $this->validate();
        $check = ContactList::whereId($this->contactId)->first();
        if(!$check) {
            ContactList::create([
            'company_id' => 1,
            'name' => $this->contactName , 
            'description'  => $this->contactDescription , 
            'created_by' => Auth::guard('web')->user()->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'status'  => 1]
        );
            $this->contactName = '';
            $this->contactDescription = '';
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Contact liste ajouté !');
        }
        else $this->emit('error', 'Contact liste existante !');
    }
    
    public function edit($id, $state)
    {
        $check = ContactList::whereId($id)->first();
        $this->contactId = $id;
        $this->contactName = $check->name;
        $this->contactDescription = $check->description;
        $this->contactStatus = $check->status;
        $this->emit($state);
    }

    public function update()
    {
        $this->validate();
        $check = ContactList::whereId($this->contactId)->first();
        $check->update([
            'name' => $this->contactName,
            'description' => $this->contactDescription,
            'status' => 1,
            'created_by' => Auth::guard('web')->user()->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
        ]);
        
        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Liste contact modifié !');
    }

    public function resetField()
    {
        $this->contactName = '';
        $this->contactDescription = '';
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = ContactList::find($this->contactId);
        if(!empty($check)) {
            if($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'contact activée !' : 'contact désactivée !');
        }
    }

    public function delete()
    {
        $check = ContactList::find($this->contactId);
        if(!empty($check)) {
            $check->destroy($this->contactId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'contact supprimée !');
        }
    }
       
    public function render()
    {
        $contact_lists = ContactList::query();   

        if($this->search != ""){
            
            $this->resetPage();
            
            $contact_lists->where("name", "LIKE",  "%". $this->search ."%");
        } 
        return view('livewire.listcontacts' ,[
            "contacts" => $contact_lists->orderBy('name' , 'desc')->where('sid_user_id', Auth::user()->id)->latest()->paginate(10)
        ]);
    }

}