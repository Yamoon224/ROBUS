<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Sid\SidUser;
use Livewire\WithPagination;
use App\Models\Custumers\Country;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\Redirect\RedirectType;

class DetailCountrie extends Component
{

    use WithPagination;
    public Country $key;
    protected $paginationTheme = 'bootstrap';
    public $searchUser = '';
    public $searchAdmin = '';
    public $countryKey;
    public $paginate = 10;
    
    
    public function mount($key)
    {
        $this->countryKey = $key;
    }

    public function render()
    {
        $sidUser =  SidUser::query();
        
        $user =  User::query();

         
        $countries = Country::where('name', $this->countryKey->name)->first(); 
      
        if(empty($countries)){
            return RedirectType::REDIRECT_URI('/');
        }  
        
         if($this->searchAdmin != ""){
            $this->resetPage();
            $sidUser->where("firstname", "LIKE",  "%". $this->searchAdmin ."%")
                          ->orWhere("lastname","LIKE",  "%". $this->searchAdmin ."%")
                          ->orWhere("phone","LIKE",  "%". $this->searchAdmin ."%");
        }
        
         if($this->searchUser != ""){
            $this->resetPage();
            $user->where("firstname", "LIKE",  "%". $this->searchUser ."%")
                          ->orWhere("lastname","LIKE",  "%". $this->searchUser ."%")
                          ->orWhere("phone","LIKE",  "%". $this->searchUser ."%");
        }
          
        return view('livewire.detail-countrie' , [
            'adminCountries' => $this->CountrieAdmin, 
            'userCountries' => $this->CountrieUser, 
        ]);
    }

    public function getCountrieAdminProperty()
    {
        $collection = new Collection([]);
        
        $countrie = Country::where('name', $this->countryKey->name)->first(); 
        $usersAdminCountries = SidUser::where('country_id' ,  $this->countryKey->id)->get();
        $usersCountries = User::where('country_id' ,  $this->countryKey->id)->get();
        $usersAdmin = SidUser::where('country_id' ,  $this->countryKey->id)->latest()->paginate(15);
        $users = User::where('country_id' ,  $this->countryKey->id)->latest()->paginate(15);
        $usersAdminCountries = SidUser::get();
        
        $data = $usersAdminCountries;

        $cumul_amount = [];
                    
            foreach ($data as $value) {
                    $data1 = [
                        'sumAmount' => $value->wallet->amount,
                    ];
                    $collection->push($data1);
            }
                
            for ($i = 0; $i < $collection->count(); $i++) {
                    $cumul_amount[] = $collection[$i]['sumAmount'];
            }
         
        return $cumul_amount; 
    }

      public function getCountrieUserProperty()
    {
        $collection = new Collection([]);
        
        $usersCountries = User::get();
        
        $data = $usersCountries;

        $cumul_amount = [];
                    
            foreach ($data as $value) {
                    $data1 = [
                        'sumAmount' => $value->wallet->amount,
                    ];
                    $collection->push($data1);
            }
                
            for ($i = 0; $i < $collection->count(); $i++) {
                    $cumul_amount[] = $collection[$i]['sumAmount'];
            }
         
        return $cumul_amount; 
    }
    
   
}