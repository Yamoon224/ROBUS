<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Sid\SidUser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LoginZone extends Component
{

    public $email;
    public $password;


    public $rules = [
        'email' => 'required|email|exists:sid_users,email',
        'password' => 'required',
    ];

    public $messages = [
        'email.email' => 'Email obligatoire',
        'password.required' => 'Ce champs est requis',
    ];

    public function loginComZone()
    {


        if (Auth::guard('comzone')->attempt(['email' => $this->email, 'password' => $this->password])) {

            $this->validate();

            $user_sid = SidUser::whereEmail($this->email)->whereStatus(true)->first();

            if (!$user_sid) {
                $this->emit('error', 'Vous n\'etes pas identifiable veuillez contactez l\'admin ! Merci bien');
            } elseif ($user_sid->is_com_zone) {

                Auth::login(Auth::guard('comzone')->user());
                Auth::guard('comzone')->user()->last_login  = Carbon::now()->toDateTimeString();
                Auth::guard('comzone')->user()->last_logout  = null;
                Auth::guard('comzone')->user()->save();
                $this->emit('success', 'Authentificaion reussie redirection en cours !');
                return redirect()->route('comzones.home');
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
        Auth::guard('comzone')->user()->save();
        Auth::logout(Auth::guard('comzone')->user());
        Auth::logout(Auth::guard('comzone')->user());
        $this->emit('success', 'Déconnexion réussi !');
        return redirect()->route('admin.login_companie');
    }
}
