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
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:sid_users'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('sid_users')],
            'phone' => ['required', Rule::unique('sid_users')],            'password' => ['required', 'confirmed'],
            'image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_recto' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_verso' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
    }
}
