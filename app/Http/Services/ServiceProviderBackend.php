<?php

namespace App\Http\Services;



class ServiceProviderBackend
{
    /**
     * Distribuer la commission aux admins pour les compagnies acceptées
     */
    public static function getProvidersMoneyByType($query, $providerType)
    {

        $providers = $query
            ->join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', sabdy()->country->id)
            ->where('providers.status', true)
            ->where('providers.id', '!=', 9)
            ->where('providers.provider_type_id', $providerType)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();


        return $providers;
    }

    public static function getProvidersByAllType($query, $providerType)
    {

        $providers = $query
            ->join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', sabdy()->country->id)
            ->where('providers.status', true)
            // ->where('providers.id' , 9)
            ->where('providers.provider_type_id', $providerType)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();


        return $providers;
    }

    public static function getProvidersByType($query, $providerType)
    {

        $providers = $query
            ->join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', sabdy()->country->id)
            ->where('providers.status', true)
            ->where('providers.id', 9)
            ->where('providers.provider_type_id', $providerType)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();


        return $providers;
    }

    
}
