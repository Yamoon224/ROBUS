<?php

namespace App\Http\Livewire;

use App\Http\Services\AuthenticationService;
use App\Models\AppMasterStatus;
use App\Models\Sid\SidUser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class LoginMaster extends Component
{

    public $email;
    public $password;
    public $appStatusResult;
    // private $authService;
    protected AuthenticationService $authService; 

    public $rules = [
        'email' => 'required|email|exists:sid_users,email',
        'password' => 'required',
    ];

    public $messages = [
        'email.email' => 'Email obligatoire',
        'password.required' => 'Ce champs est requis',
    ];

    public function mount(AuthenticationService $authService)
    {

        $this->authService = $authService;

        // Attempt to retrieve from cache first
        $cachedResult = Cache::get('app_status_master_result');

        
        if ($cachedResult) {
            $this->appStatusResult = $cachedResult;
        } else {
            // If not in cache, call the service method
            $this->appStatusResult = $this->authService->appDispoMasterWebRobus(AppMasterStatus::class);

            Cache::put('app_status_master_result', $this->appStatusResult, now()->addMinutes(20));
        }
    }
    
    public function loginMaster()
    {


        if (Auth::guard('master')->attempt(['email' => $this->email, 'password' => $this->password])) {

            $this->validate();

            $user_sid = SidUser::whereEmail($this->email)->whereStatus(true)->first();

            if (!$user_sid) {
                $this->emit('error', 'Vous n\'etes pas identifiable veuillez contactez l\'admin ! Merci bien');
            } elseif ($user_sid->is_master) {

                Auth::login(Auth::guard('master')->user());
                Auth::guard('master')->user()->last_login  = Carbon::now()->toDateTimeString();
                Auth::guard('master')->user()->last_logout  = null;
                Auth::guard('master')->user()->save();
                $this->emit('success', 'Authentificaion reussie redirection en cours !');
                return redirect()->route('masters.home');
            } else {
                $this->emit('error', 'Vous n\'etes pas identifiable veuillez contactez l\'admin ! Merci bien');
            }
        } else {

            $this->emit('error', 'Vous n\'etes pas identifiable !');
        }
    }

    public function logout()
    {
        Auth::user()->last_logout  = Carbon::now()->toDateTimeString();
        Auth::guard('master')->user()->save();
        Auth::logout(Auth::guard('master')->user());
        Session::flush();
        $this->emit('success', 'Déconnexion réussi !');
        return redirect()->route('admin.login_companie');
    }
}
