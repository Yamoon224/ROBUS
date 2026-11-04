<?php


namespace App\Http\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ValidatorService
{
    public function validateUserData($data)
    {
        return Validator::make($data, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'zone' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:sid_users'],
            'phone' => ['required', 'unique:sid_users'],
            'password' => ['required', 'confirmed'],
            'image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_recto' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_verso' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
    }

    public function validateComZoneDataUnder($data)
    {
        return Validator::make($data, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'country_id' => 'required',
            'zone' => 'required',
            'city' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:sid_users'],
            'phone' => ['required', 'unique:sid_users'],
            'image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_recto' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_verso' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_trade' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_agence' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
    }

    public function validateComZoneDataCompanie($data)
    {
        return Validator::make($data, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'country_id' => 'required',
            'zone' => 'required',
            'city' => 'required',
            'rib_banque' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:sid_users'],
            'phone' => ['required', 'unique:sid_users'],
            'image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_recto' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_verso' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_trade' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_agence' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
    }

    public function validateUserComData($data)
    {
        return Validator::make($data, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'country_id' => 'required',
            'zone' => 'required',
            'city' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:sid_users'],
            'phone' => ['required', 'unique:sid_users'],
            'image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_recto' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_verso' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
    }


    public function validateUserDataAdmin($data)
    {
        return Validator::make($data, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'country_id' => 'required',
            'zone' => 'required',
            'city' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:sid_users'],
            'phone' => ['required', 'unique:sid_users'],
            'password' => ['required', 'confirmed'],
            'image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_recto' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_verso' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
    }

    public function validateUserUpdateDataAdmin($data)
    {
        return Validator::make($data, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            // 'country_id' => 'required',
            'zone' => 'nullable',
            'city' => 'required',
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required'],
            // Le mot de passe n'est modifié que s'il est renseigné
            'password' => ['nullable', 'confirmed'],
            'image' => 'nullable|mimes:jpeg,png,jpg,webp,svg|max:10240',
            'image_recto' => 'nullable|mimes:jpeg,png,jpg,webp,svg|max:10240',
            'image_verso' => 'nullable|mimes:jpeg,png,jpg,webp,svg|max:10240',
        ], [
            'firstname.required' => 'Le prénom est obligatoire.',
            'lastname.required' => 'Le nom est obligatoire.',
            'city.required' => 'La ville est obligatoire.',
            'email.required' => "L'adresse email est obligatoire.",
            'email.email' => "L'adresse email n'est pas valide.",
            'phone.required' => 'Le téléphone est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'image.max' => 'La photo de profil dépasse 10 Mo.',
            'image_recto.max' => 'La photo recto de la carte dépasse 10 Mo.',
            'image_verso.max' => 'La photo verso de la carte dépasse 10 Mo.',
            'image.mimes' => 'La photo de profil doit être au format jpeg, png, jpg, webp ou svg.',
            'image_recto.mimes' => 'La carte recto doit être au format jpeg, png, jpg, webp ou svg.',
            'image_verso.mimes' => 'La carte verso doit être au format jpeg, png, jpg, webp ou svg.',
            'image.uploaded' => 'Envoi de la photo de profil impossible (fichier trop lourd pour le serveur).',
            'image_recto.uploaded' => 'Envoi de la carte recto impossible (fichier trop lourd pour le serveur).',
            'image_verso.uploaded' => 'Envoi de la carte verso impossible (fichier trop lourd pour le serveur).',
        ]);
    }

    public function validateUserUpdateCompanyDataAdmin($data)
    {
        return Validator::make($data, [
            'firstname' => ['required', 'string', 'max:255'],
            'country_id' => 'required',
            'zone' => 'required',
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required'],
            'password' => ['required', 'confirmed'],
            'image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_recto' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_verso' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
    }

    public function validateUserDataMaster($data)
    {
        return Validator::make($data, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'zone' => 'required',
            'city_id' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:sid_users'],
            'phone' => ['required', 'unique:sid_users'],
            'password' => ['required', 'confirmed'],
            'image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_recto' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_verso' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
    }
}
