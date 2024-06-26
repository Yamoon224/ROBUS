<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Contacts\Contact;
use App\Models\Custumers\Country;
use App\Models\Contacts\ContactList;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Redirect\RedirectType;
use App\Models\Custumers\City;

class Detailcontacts extends Component
{
    
    use WithPagination;
    public ContactList $key;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $contactKey;
    public $contactId;
    public $firstname;
    public $lastname;
    public $status;
    public $phone;
    public $email;
    public $countrie;
    public $citie;
    public $paginate = 10;

    protected $rules = [
        'firstname' => 'required',
        'lastname' => 'required',
        'phone' => 'required',
        'email'  => 'required', 
        'countrie'  => 'required',
        'citie'  => 'required',
    ];

    protected $messages = [
        'firstname.required' => 'Ce champ est requis',
        'lastname.required' => 'Ce champ est requis',
        'phone.required' => 'Ce champ est requis',
        'email.required' => 'Ce champ est requis',
        'countrie.required' => 'Ce champ est requis',
        'citie.required' => 'Ce champ est requis',
    ];


     public function mount($key)
    {
        $this->contactKey = $key;
    }
    
      public function add()
    {
        $this->validate();
        $check = ContactList::find($this->contactKey);
        $contact = Contact::whereId($this->contactId)->first();
        $countrie = Country::whereName($this->countrie)->first();
        
        if(!$check) { 
            Contact::create([
            'company_id' => 1,
            'firstname' => $this->firstname , 
            'lastname' => $this->lastname , 
            'phone' => $this->phone , 
            'email' => $this->email ,
            'lot'  =>  uniqid(),
            'contact_list_id' =>  $this->contactKey , 
            'country_id'  =>   $countrie->id , 
            'city_id'  =>   $this->citie ,
            'created_by' => Auth::guard('web')->user()->id,
            'status'  => 1
            ]
        );
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Contact  ajouté !');
        }
        else $this->resetField() ; $this->emit('error', 'Contact  existante !');
    }
    
    public function edit($id, $state)
    {
        $check = Contact::whereId($id)->first();
        $countrie = Country::whereId($check->country_id)->first();
        $citie = City::whereId($check->city_id)->first();
        $this->contactId = $id;
        $this->firstname = $check->firstname;
        $this->lastname = $check->lastname;
        $this->phone = $check->phone;
        $this->email = $check->email;
        $this->status = $check->status;
        $this->countrie  = $countrie->name;
        $this->citie  =   $citie ->name;
        $this->emit($state);
    }
    
    public function update()
    {
        $this->validate();
        $check = Contact::whereId($this->contactId)->first();
        $check->update([
            'company_id' => 1,
            'firstname' => $this->firstname , 
            'lastname' => $this->lastname , 
            'phone' => $this->phone , 
            'email' => $this->email ,
            'lot'  =>  uniqid(),
            'contact_list_id' =>  $this->contactKey ,   
            'created_by' => Auth::guard('web')->user()->id,
            'status'  => 1
        ]);
        
        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Contact modifié !');
    }

    public function resetField()
    {
        $this->firstname = '';
        $this->lastname = '';
        $this->phone = '';
        $this->email = '';
        $this->countrie = '';
        $this->citie = '';
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = Contact::find($this->contactId);
        if(!empty($check)) {
            if($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Contact activée !' : 'Contact désactivée !');
        }
    }

    public function delete()
    {
        $check = Contact::find($this->contactId);
        if(!empty($check)) {
            $check->destroy($this->contactId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Contact supprimée !');
        }
    }
    public function render()
    {   
        
        $contact_list = ContactList::where('sid_user_id', Auth::user()->id)->where('id', $this->contactKey->id)->first(); 
        if(empty($contact_list)){
            return RedirectType::REDIRECT_URI('contacts.list.browse');
        }    
  
        return view('livewire.detailcontacts' , [
            "contacts" =>  Contact::where('contact_list_id', $this->contactKey->id)->paginate(30),
            "countries" =>  Country::where('robus', true)->orderBy('name', 'asc')->get(),
             "cities" =>  City::where('status', true)->orderBy('name', 'asc')->get(),
        ]);
    }
}