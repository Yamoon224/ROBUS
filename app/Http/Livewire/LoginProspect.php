<?php

namespace App\Http\Livewire;

use App\Models\Sid\SidUser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class LoginProspect extends Component
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


        $user_sid = SidUser::whereEmail($this->email)->whereStatus(true)->first();

        if (Auth::guard('integration')->attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->isLoggedIn = true;

            $this->validate();


            if (!$user_sid) {
                $this->emit('error', 'Vous n\'etes pas identifiable veuillez contactez l\'admin ! Merci bien');
            } elseif ($user_sid->is_api) {

                Auth::login(Auth::guard('integration')->user());
                Auth::guard('integration')->user()->last_login  = Carbon::now()->toDateTimeString();
                Auth::guard('integration')->user()->last_logout  = null;
                Auth::guard('integration')->user()->save();
                $this->emit('success', 'Authentificaion reussie redirection en cours !');
                return redirect()->route('propects.home');
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
