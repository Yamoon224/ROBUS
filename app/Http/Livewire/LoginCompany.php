<?php

namespace App\Http\Livewire;

use App\Models\Sid\SidUser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class LoginCompany extends Component
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

    public function login()
    {

        $msg = 'Bienvenu chez ROBUS';
        $color = 'success';

        if (Auth::guard('company')->attempt(['email' => $this->email, 'password' => $this->password])) {

            $this->validate();

            $user_sid = SidUser::whereEmail($this->email)->whereStatus(true)->first();

            if (!$user_sid) {
                $this->emit('error', 'Vous n\'etes pas identifiable veuillez contactez l\'admin ! Merci bien');
            } elseif ($user_sid->is_company) {

                Auth::login(Auth::guard('company')->user());
                Auth::guard('company')->user()->last_login  = Carbon::now()->toDateTimeString();
                Auth::guard('company')->user()->last_logout  = null;
                Auth::guard('company')->user()->save();
                $this->emit('success', 'Authentificaion reussie redirection en cours !');
                return redirect()->route('companies.home');
            }
        } else {

            $this->emit('error', 'Vous n\'etes pas identifiable !');
        }
    }

    public function logout()
    {
        Auth::user()->last_logout  = Carbon::now()->toDateTimeString();
        Auth::guard('company')->user()->save();
        Auth::logout(Auth::guard('company')->user());
        Session::flush();
        $this->emit('success', 'Déconnexion réussi !');
        return redirect()->route('admin.login_companie');
    }

    public function logout_prospect()
    {
        Auth::user()->last_logout  = Carbon::now()->toDateTimeString();
        Auth::guard('integration')->user()->save();
        Auth::logout(Auth::guard('integration')->user());
        Session::flush();
        $this->emit('success', 'Déconnexion réussi !');
        return redirect()->route('admin.login_integration');
    }



    public function logout_masters()
    {
        // Sauvegarde de la dernière déconnexion pour l'utilisateur
        Auth::guard('master')->user()->last_logout = Carbon::now()->toDateTimeString();
        Auth::guard('master')->user()->save();

        // Déconnexion de l'utilisateur du gardien "master"
        Auth::guard('master')->logout();
        // Après la déconnexion
        Session::flush();
        // Message de succès et redirection
        $this->emit('success', 'Déconnexion réussie !');
        return redirect()->route('admin.login_master');
    }


    public function logout_comzones()
    {
        Auth::user()->last_logout  = Carbon::now()->toDateTimeString();
        Auth::guard('web')->user()->save();
        Auth::logout(Auth::guard('web')->user());
        Session::flush();
        $this->emit('success', 'Déconnexion réussi !');
        return redirect()->route('admin.login_com_zone');
    }
}
