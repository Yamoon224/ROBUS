<?php

namespace App\Http\Services;


use App\Models\Custumers\Country;
use App\Models\OperatorService;
use App\Models\Providers\Provider;
use App\Events\assignementEvent;

class ProvidersService
{


    public function getProvidersByMultipleTypes($providerModel, $types, $countryId)
    {
        return $providerModel::whereIn('type', $types)
            ->where('country_id', $countryId)
            ->where('status', true)
            ->get()
            ->groupBy('type');
    }


    public function operatorServices()
    {

        $operatorServices = OperatorService::getOperatorsWithTranactions(robus()->id);
        event(new assignementEvent($operatorServices));

        return $operatorServices;
    }


    public function operatorServiceCommissions()
    {

        $operatorServices = OperatorService::getOperatorsWithCommissions(robus()->id);
        event(new assignementEvent($operatorServices));

        return $operatorServices;
    }



    public function getProvidersForCountry()
    {
        $countryId = robus()->country->id;

        $countryNameGroup = robus()->country->name_groupe;
        $isUnderDistributer = robus()->is_under_distributer;

        // Tableau de fournisseurs par défaut
        $defaultProviders = [
            'AFL' => [
                25 => [9],
                36 => [9],
                133 => [9],
                214 => [9],
                188 => [9],
                83 => [9],
                157 => [9],
                91 => [9]
            ],
            'CIV' => [107 => [1, 3, 4, 6, 9]],
            'AFC' => [
                39 => [9],
                79 => [9],
                50 => [9],
                57 => [9],
                122 => [9],
                43 => [9],
                42 => [9]
            ],
            'USA' => [1 => [9]],
            'URP' => [
                74 => [9],
                82 => [9],
                199 => [9],
                226 => [9]
            ],
            'MAG' => [
                82 => [9],
                146 => [9],
                216 => [9]
            ],
        ];


        // Tableau des fournisseurs lorsque $isUnderDistributer est vrai
        $underDistributerProviders = [
            'AFL' => [
                25 => [1, 3],
                36 => [4, 3, 9, 6],
                133 => [3, 4, 6, 9],
                214 => [3, 9],
                188 => [6, 9],
                83 => [1, 9],
                157 => [71, 4, 3, 9],
                91 => [1, 4, 9]
            ],
            'CIV' => [107 => [1, 3, 4, 6, 9]],
            'AFC' => [39 => [1, 4, 9], 79 => [71, 72, 9], 50 => [1, 9], 57 => [71, 9], 122 => [1, 4, 9], 43 => [71, 9], 42 => [9, 4, 78]],
            'USA' => [1 => [9]],
            'URP' => [
                74 => [9],
                82 => [9],
                199 => [9],
                226 => [9]
            ],
            'MAG' => [
                82 => [9],
                146 => [9],
                216 => [9]
            ],
        ];

        $providerMap = $isUnderDistributer ? $underDistributerProviders : $defaultProviders;

        if (array_key_exists($countryNameGroup, $providerMap) && array_key_exists($countryId, $providerMap[$countryNameGroup])) {
            $providerIds = $providerMap[$countryNameGroup][$countryId];
        } else {
            $providerIds = [9];
        }

        return Provider::whereIn('id', $providerIds)
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getProvidersMasterForCountry()
    {
        $countryId = robus()->country->id;

        $countryNameGroup = robus()->country->name_groupe;
        $isUnderDistributer = robus()->is_under_distributer;

        // Tableau de fournisseurs par défaut
        $defaultProviders = [
            'AFL' => [
                25 => [9],
                36 => [9],
                133 => [9],
                214 => [9],
                188 => [9],
                83 => [9],
                157 => [9],
                91 => [9]
            ],
            'CIV' => [107 => [9]],
            'AFC' => [
                39 => [9],
                79 => [9],
                50 => [9],
                57 => [9],
                122 => [9],
                43 => [9],
                42 => [9]
            ],
            'USA' => [1 => [9]],
            'URP' => [
                74 => [9],
                82 => [9],
                199 => [9],
                226 => [9]
            ],
            'MAG' => [
                82 => [9],
                146 => [9],
                216 => [9]
            ],
        ];


        // Tableau des fournisseurs lorsque $isUnderDistributer est vrai
        $underDistributerProviders = [
            'AFL' => [
                25 => [9],
                36 => [9],
                133 => [9],
                214 => [9],
                188 => [9],
                83 => [9],
                157 => [9],
                91 => [9]
            ],
            'CIV' => [107 => [9]],
            'AFC' => [39 => [9], 79 => [9], 50 => [9], 57 => [9], 122 => [9], 43 => [9], 42 => [9]],
            'USA' => [1 => [9]],
            'URP' => [
                74 => [9],
                82 => [9],
                199 => [9],
                226 => [9]
            ],
            'MAG' => [
                82 => [9],
                146 => [9],
                216 => [9]
            ],
        ];

        $providerMap = $isUnderDistributer ? $underDistributerProviders : $defaultProviders;

        if (array_key_exists($countryNameGroup, $providerMap) && array_key_exists($countryId, $providerMap[$countryNameGroup])) {
            $providerIds = $providerMap[$countryNameGroup][$countryId];
        } else {
            $providerIds = [9];
        }

        return Provider::whereIn('id', $providerIds)
            ->orderBy('name', 'asc')
            ->get();
    }


    public function getProvidersForCountryInters()
    {
        $countryNameGroup = robus()->country->name_groupe;
        $isUnderDistributer = robus()->is_under_distributer;

        $providerMap = [];

        if ($isUnderDistributer) {
            if ($countryNameGroup == "CIV") {
                $providerMap = ['AFL', 'AFC', 'ASIE', 'AFE'];
            } else {
                $providerMap = ['AFL', 'CIV', 'AFC', 'ASIE', 'AFE'];
            }
        } else {
            $providerMaps = [
                "CIV" => ['AFL', 'AFC', 'USA', 'MAG', 'URP', 'ASIE', 'AFE'],
                "USA" => ['AFL', 'AFC', 'URP', 'MAG', 'CIV', 'ASIE', 'AFE'],
                "AFL" => ['AFL', 'AFC', 'URP', 'CIV', 'USA', 'MAG', 'ASIE', 'AFE'],
                "AFC" => ['CIV', 'AFC', 'URP', 'USA', 'MAG', 'AFL', 'ASIE', 'AFE'],
                "URP" => ['CIV', 'AFC', 'AFL', 'USA', 'MAG', 'ASIE', 'AFE'],
                "MAG" => ['CIV', 'AFC', 'URP', 'AFL', 'USA', 'MAG', 'ASIE', 'AFE'],
            ];

            $providerMap = $providerMaps[$countryNameGroup] ?? [];
        }


        return Country::whereIn('name_groupe', $providerMap)
            ->whereRobus(true)
            ->where('id', '!=', robus()->country->id)
            ->orderBy('name', 'asc')
            ->get();
    }


    public static function getProvidersByType($query, $countryId, $providerType)
    {


        return $query
            ->join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', $countryId)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', $providerType)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();
    }

    public static function getProvidersDepotByType($query, $countryId, $providerType)
    {


        return $query
            ->join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', $countryId)
            ->where('providers.status', true)
            ->where('providers.id', 9)
            ->where('providers.provider_type_id', $providerType)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();
    }

    public static function getProvidersByTypes($query, $countryId, array $providerTypes, $additionalConditions = [])
    {
        $query = $query
            ->join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', $countryId)
            ->where('providers.status', true)
            ->whereIn('providers.provider_type_id', $providerTypes);

        // Ajout de conditions supplémentaires si nécessaire
        foreach ($additionalConditions as $field => $value) {
            $query->where($field, $value);
        }

        return $query
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();
    }


    public static function getProvidersMoneyByType($query, $countryId, $providerType)
    {


        return $query
            ->join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', $countryId)
            ->where('providers.status', true)
            ->where('providers.id', '!=', 9)
            ->where('providers.provider_type_id', $providerType)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();
    }

    public static function getProvidersWebByType($query, $countryId, $providerType)
    {


        return $query
            ->join('country_webs', 'country_webs.provider_id', 'providers.id')
            ->where('country_webs.country_id', $countryId)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', $providerType)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->pluck('providers.name');
    }

    public static function getProvidersByTypeOrderCard($query, $countryId, $providerType)
    {
        return $query
            ->join('country_services', function ($join) use ($countryId) {
                $join->on('country_services.provider_id', '=', 'providers.id')
                    ->where('country_services.country_id', '=', $countryId);
            })
            ->where('providers.status', true)
            ->whereNotNull('providers.amount')
            ->where('providers.provider_type_id', $providerType)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();
    }


    public static function getProvidersByWithdrawal($query, $countryId, $providerType)
    {


        return $query
            ->join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', $countryId)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', $providerType)
            ->where('providers.name', 'ROBUS')
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();
    }

    public static function getProvidersByReloadType($query, $countryId, $providerType)
    {

        return $query
            ->join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', $countryId)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', $providerType)
            ->whereNotIn('providers.name', ['Wave', 'ORANGE', 'MTN', 'MOOV'])
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();
    }

    public static function getProvidersByMasterType($query, $countryId, $providerType)
    {

        return $query
            ->join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', $countryId)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', $providerType)
            ->whereNotIn('providers.name', ['CREER UN MASTER'])
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();
    }


    public static function getProvidersByComZoneType($query, $countryId, $providerType)
    {

        return $query
            ->join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', $countryId)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', $providerType)
            // ->whereNotIn('providers.name', ['Wave', 'ORANGE', 'MTN', 'MOOV'])
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();
    }

    public static function getProvidersByUnderDistributerType($query, $countryId, $providerType)
    {


        return $query
            ->join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', $countryId)
            ->where('providers.status', true)
            ->where('providers.id', '!=', 51)
            ->where('providers.provider_type_id', $providerType)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();
    }

    // Providers countries Inters
    public function getCIVEurope()
    {
        return  Provider::whereIn('id', array(49, 50, 55, 48, 51))
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getEthiopieInter()
    {
        return  Provider::whereIn('id', array(48, 73))
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getWestInter()
    {
        return  Provider::whereIn('id', array(49, 50, 48))
            ->orderBy('name', 'asc')
            ->get();
    }
    public function getOugandaInter()
    {
        return  Provider::whereIn('id', array(71, 48))
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getKeneyaInter()
    {
        return  Provider::whereIn('id', array(76, 71, 77))
            ->orderBy('name', 'asc')
            ->get();
    }
    public function getCIV()
    {
        return  Provider::whereIn('id', array(49, 50, 55, 48, 51))
            ->orderBy('name', 'asc')
            ->get();
    }
    public function getMali()
    {
        return  Provider::whereIn('id', array(50, 49, 55, 51))
            ->orderBy('name', 'asc')
            ->get();
    }
    public function getBf()
    {
        return  Provider::whereIn('id', array(49, 50, 51, 55))
            ->orderBy('name', 'asc')
            ->get();
    }
    public function getTogo()
    {
        return  Provider::whereIn('id', array(50, 51))
            ->orderBy('name', 'asc')
            ->get();
    }
    public function getGabonInter()
    {
        return  Provider::whereIn('id', array(71, 72, 51))
            ->orderBy('name', 'asc')
            ->get();
    }
    public function getCamerounInter()
    {
        return  Provider::whereIn('id', array(48, 49, 51))
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getCongoInter()
    {
        return Provider::whereIn('id', array(48, 51))
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getRdcInter()
    {
        return Provider::whereIn('id', array(71, 51))
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getEuropeInter()
    {
        return Provider::whereIn('id', array(51))
            ->orderBy('name', 'asc')
            ->get();
    }
    public function getGhanaInter()
    {
        return  Provider::whereIn('id', array(48, 51))
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getBeninInter()
    {
        return  Provider::whereIn('id', array(50, 48, 51))
            ->orderBy('name', 'asc')
            ->get();
    }
    public function getLiberiaInter()
    {
        return Provider::whereIn('id', array(48, 49, 51))
            ->orderBy('name', 'asc')
            ->get();
    }
    public function getTchadInter()
    {
        return  Provider::whereIn('id', array(71, 51))
            ->orderBy('name', 'asc')
            ->get();
    }
    public function getCentreAfriqueInter()
    {
        return   Provider::whereIn('id', array(51, 49, 78))
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getSeriaLionInter()
    {
        return   Provider::whereIn('id', array(51, 49, 74, 75))
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getSenegal()
    {
        return  Provider::whereIn('id', array(55, 51))
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getNiger()
    {
        return   Provider::whereIn('id', array(71, 49, 50, 51))
            ->orderBy('name', 'asc')
            ->get();
    }
    public function getGuinee()
    {
        return    Provider::whereIn('id', array(48, 49, 51))
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getEurope()
    {
        return   Provider::whereIn('id', array(51))
            ->orderBy('name', 'asc')
            ->get();
    }

    // National
    public function getCamerounGuinne()
    {
        return  Provider::whereIn('id', array(49, 48))
            ->orderBy('name', 'asc')
            ->get();
    }
    public function getCongo()
    {
        return  Provider::whereIn('id', array(48, 51))
            ->orderBy('name', 'asc')
            ->get();
    }


    public function getCentreAfrique()
    {
        return   Provider::whereIn('id', array(49, 78))
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getConBeninGhanaLibe()
    {
        return   Provider::whereId(48)
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getGhana()
    {
        return  Provider::whereIn('id', array(48, 51))
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getGabon()
    {
        return   Provider::whereIn('id', array(71, 72))
            ->orderBy('name', 'asc')
            ->get();
    }
}
