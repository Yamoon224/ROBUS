<?php

namespace App\Http\Services;

use App\Models\Sid\SidUser;
use App\Models\Custumers\City;
use App\Models\Custumers\Country;
use App\Repositories\RegisterRepository;



class RegisterService
{

    /** @var  RegisterRepository */
    private $registerRepository;

    public function __construct(RegisterRepository $registerReposi)
    {
        $this->registerRepository = $registerReposi;
    }


    public function registerCustumers($country, $citie, $phone, $email, $activation_code)
    {

        
        return  $this->registerRepository->create([
            'country_id'  => $country->id,
            'company_id'  => 1,
            'city_id'  => $citie->id,
            'phone'  => $phone,
            'email'  => $email,
            'status' => false,
            'is_custumers' => false,
            'is_active' => false,
            'verification_code' =>  rand(100000, 999999),
            'activation_code' =>  $activation_code
        ]);
    }

    public function updateCustumers($id, $citie, $country, $activation_code, $phone, $email)
    {

        return  $this->registerRepository->update($id, [
            'country_id'  => $country->id,
            'company_id'  => 1,
            'city_id'  => $citie->id,
            'phone'  => $phone,
            'email'  => $email,
            'status' => false,
            'is_custumers' => false,
            'is_active' => false,
            'verification_code' =>  rand(100000, 999999),
            'activation_code' =>  $activation_code
        ]);
    }

    public function verifyPhone($phone)
    {

        return SidUser::wherePhone($phone)->first();
    }

    public function verifyEmail($email)
    {
        return SidUser::whereEmail($email)->first();
    }

    public function verifyCitie($citie)
    {

        return City::whereName($citie)->whereStatus(true)->first();
    }

    public function verifyCountry($country)
    {

        return Country::whereName($country)->first();
    }
}
