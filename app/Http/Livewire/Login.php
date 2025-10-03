<?php

namespace App\Http\Livewire;

use App\Models\Sid\SidUser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Login extends Component
{
    public $email;
    public $password;
 public $isLoggedIn = false;

    public $rules = [
        'email' => 'required|email|exists:sid_users,email',
        'password' => 'required',
    ];

    public $messages = [
        'email.required' => 'Ce champs est requis',
        'email.email' => 'Email obligatoire',
        'password.required' => 'Ce champs est requis',
    ];

    public function login()
    {

        $msg = 'Bienvenu chez ROBUS';
        $color = 'success';
        
    
        $user_sid = SidUser::whereEmail($this->email)->whereStatus(true)->first();

        if (Auth::guard('web')->attempt(['email' => $this->email, 'password' => $this->password])) {
                $this->isLoggedIn = true;

            $this->validate();


            if (!$user_sid) {
                $this->emit('error', 'Vous n\'etes pas identifiable veuillez contactez l\'admin ! Merci bien');
            } elseif ($user_sid->is_admin || $user_sid->is_supervisor || $user_sid->is_distributer || $user_sid->is_under_distributer) {

                Auth::login(Auth::guard('web')->user());    
                Auth::guard('web')->user()->last_login  = Carbon::now()->toDateTimeString();
                Auth::guard('web')->user()->last_logout  = null;
                Auth::guard('web')->user()->save();
                $this->emit('success', 'Authentificaion reussie redirection en cours !');
                return redirect()->route('/');
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
        $this->emit('success', 'Déconnexion réussi !');
        Auth::guard('web')->user()->save();
        Auth::logout(Auth::guard('web')->user());
        Session::flush();
        return redirect()->route('admin.logout');
    }
}
