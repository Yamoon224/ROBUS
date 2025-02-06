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
            'zone' => 'required',
            'city' => 'required',
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
