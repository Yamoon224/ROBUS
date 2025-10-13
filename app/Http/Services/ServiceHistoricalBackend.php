<?php

namespace App\Http\Services;



class ServiceHistoricalBackend
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

    public function getWalletSumAdminProperty($query, $walletId, $walletStatus, $walletTransactions)
    {

        $data = [];
        foreach (
            $query::join('wallets', $walletId, 'wallets.id')
                ->where('wallets.country_id', sabdy()->country->id)
                ->where($walletStatus, 'SUCCESS')
                ->select($walletTransactions)->get() as $bank
        ) {
            $data[] = $bank->commissions_admin;
        }

        return array_sum($data);
    }

    public function getWalletSumCommissionProperty($query, $walletId , $walletCreated, $walletStatus, $walletTransactions)
    {

        $data = [];
        foreach (
            $query::join('wallets', $walletId, 'wallets.id')
                ->where('wallets.country_id', sabdy()->country->id)
                ->where($walletCreated, sabdy()->id)
                ->where($walletStatus, 'SUCCESS')
                ->select($walletTransactions)->get() as $bank
        ) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }
}
