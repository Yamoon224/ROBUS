<?php

namespace App\Helpers;

use App\Models\Custumers\Country;
use Illuminate\Support\Facades\Auth;

class CountryHelper
{
    public static function getCountriesForUser($user)
    {
        $isDistributor = $user->wallet->sidUser->is_distributor;

        $countries = Country::whereRobus(true)
            ->orderBy('name', 'asc');

        if ($isDistributor) {
            $countries->where('id', $user->wallet->country->id);
        }

        return $countries->get();
    }
}
