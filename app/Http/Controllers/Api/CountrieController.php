<?php

namespace App\Http\Controllers\Api;

use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use App\Http\Controllers\Controller;
use App\Http\Services\ProvidersService;
use App\Http\Resources\ProviderCollection;
use App\Http\Resources\AllCountrieCollection;
use App\Http\Resources\ProviderSingleCollection;
use App\Models\Custumers\City;
use App\Http\Resources\AllCitieCollection;

/**
 * @OA\Get(
 *      path="/countries",
 *      tags={"countries"},
 *      summary="Get List Of Countires",
 *      description="Returns all countries in search countries",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\MediaType(
 *           mediaType="application/json",
 *      )
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated",
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      ),
 * @OA\Response(
 *      response=400,
 *      description="Bad Request"
 *   ),
 * @OA\Response(
 *      response=404,
 *      description="not found"
 *   ),
 *  )
 */
class CountrieController extends Controller
{

    /** @var  ReloadCardRepository */
    protected $percentRobus;

    public function __construct(
        ProvidersService $percentRobus,
    ) {
        $this->percentRobus = $percentRobus;
    }

    public function index()
    {
        $countrie = Country::query();


        $countrie->when(request('code'), function ($q) {
            return $q->where('code', 'LIKE', '%' . request('code') . '%');
        });

        $countrie = $countrie->whereRobus(true)->where('indecative', '!=', null)
            ->orderBy('name', 'asc')
            ->get();

        return  new AllCountrieCollection($countrie);
    }

    public function  index_search_country($id)
    {
        $city = City::query();

        $city->when(request('country_id'), function ($q) {
            return $q->where('country_id', request('country_id'));
        });

        $city->when(request('name'), function ($q) {
            return $q->where('name', 'LIKE', '%' . request('name') . '%');
        });

        $city = $city->where('country_id', $id)->orderBy('name', 'asc')->get();

        return new  AllCitieCollection($city);
    }

    public function index_inter_auth()
    {


        return new AllCountrieCollection($this->percentRobus->getProvidersForCountryInters());
    }

    public function index_inter()
    {
        $countrie = Country::query();

        $countrie->when(request('code'), function ($q) {
            return $q->where('code', request('code'));
        });

        $countrie->when(request('name'), function ($q) {
            return $q->where('name', 'LIKE', '%' . request('name') . '%');
        });

        $countrie->when(request('code'), function ($q) {
            return $q->where('code', 'LIKE', '%' . request('code') . '%');
        });


        $countrie = $countrie->whereRobus(true)->whereId(107)->orderBy('name', 'asc')->get();

        return new  AllCountrieCollection($countrie);
    }

    public function index_civ()
    {
        $countrie = Country::query();

        $countrie->when(request('code'), function ($q) {
            return $q->where('code', request('code'));
        });

        $countrie->when(request('name'), function ($q) {
            return $q->where('name', 'LIKE', '%' . request('name') . '%');
        });

        $countrie->when(request('code'), function ($q) {
            return $q->where('code', 'LIKE', '%' . request('code') . '%');
        });


        $countrie = $countrie->whereRobus(true)
            ->whereIn('id', array(146, 42, 83, 74, 218))
            ->orderBy('name', 'asc')->get();

        return new  AllCountrieCollection($countrie);
    }

    public function index_selected_search($country_inter_out_civ)
    {

        $countries = Country::whereName($country_inter_out_civ)->first();
        $country_inter_out_civ =  $countries->id;
        $walletsData = [];



        $walletPaymentInter = $this->percentRobus->getProvidersByType(new Provider, $country_inter_out_civ, 7);
        $walletPhoneInter = $this->percentRobus->getProvidersByType(new Provider, $country_inter_out_civ, 9);
        $walletVirementBank = $this->percentRobus->getProvidersReloadByType(new Provider, $country_inter_out_civ, [10]);
        $walletReloadCardInter = $this->percentRobus->getProvidersReloadByType(new Provider, $country_inter_out_civ, [11, 21, 22]);
        $walletInter =  $this->percentRobus->getProvidersByType(new Provider, $country_inter_out_civ, 8);
        $walletBankInter =  $this->percentRobus->getProvidersByType(new Provider, $country_inter_out_civ, 28);
        $walletInterUnder =  $this->percentRobus->getProvidersByUnderDistributerType(new Provider, $country_inter_out_civ, 8);
        $walletInterXbet =  $this->percentRobus->getProvidersByType(new Provider, $country_inter_out_civ, 26);

        if ((robus()->is_under_distributer || robus()->is_api) && robus()->country->id != 107) {


            $walletsData['Transfert international'] = [
                "Paiements Factures" =>  $walletPaymentInter ? ProviderSingleCollection::collection($walletPaymentInter) : [],
                "Transfert" => $walletInterUnder ? ProviderSingleCollection::collection($walletInterUnder) : [],
                "Crédit téléphonique" => $walletPhoneInter ? ProviderSingleCollection::collection($walletPhoneInter) : [],
                "Versement bancaire" => $walletVirementBank ? ProviderSingleCollection::collection($walletVirementBank) : [],
                "Rechargement de carte" => $walletReloadCardInter ? ProviderSingleCollection::collection($walletReloadCardInter) : [],
                "Bank send" => $walletBankInter ? ProviderSingleCollection::collection($walletBankInter) : [],

            ];
        } elseif ((robus()->is_under_distributer || robus()->is_api) && robus()->country->id == 107) {

            $walletsData['Transfert international'] = [
                "Paiements Factures" =>  $walletPaymentInter ? ProviderSingleCollection::collection($walletPaymentInter) : [],
                "Transfert" => $walletInterUnder ? ProviderSingleCollection::collection($walletInterUnder) : [],
                "Crédit téléphonique" => $walletPhoneInter ? ProviderSingleCollection::collection($walletPhoneInter) : [],
                "Versement bancaire" => $walletVirementBank ? ProviderSingleCollection::collection($walletVirementBank) : [],
                "Rechargement de carte" => $walletReloadCardInter ? ProviderSingleCollection::collection($walletReloadCardInter) : [],
                "Bank send" => $walletBankInter ? ProviderSingleCollection::collection($walletBankInter) : [],

            ];
        } elseif (robus()->is_custumers && robus()->country->id == 107) {

            $walletsData['Transfert international'] = [
                "Paiements Factures" =>  $walletPaymentInter ? ProviderSingleCollection::collection($walletPaymentInter) : [],
                "Transfert" => $walletInter ? ProviderSingleCollection::collection($walletInter) : [],
                "Crédit téléphonique" => $walletPhoneInter ? ProviderSingleCollection::collection($walletPhoneInter) : [],
                "Versement bancaire" => $walletVirementBank ? ProviderSingleCollection::collection($walletVirementBank) : [],
                "Rechargement de carte" => $walletReloadCardInter ? ProviderSingleCollection::collection($walletReloadCardInter) : [],
                "Bank send" => $walletBankInter ? ProviderSingleCollection::collection($walletBankInter) : [],

            ];
        } else {

            $walletsData['Transfert international'] = [
                "Paiements Factures" => $walletPaymentInter ? ProviderSingleCollection::collection($walletPaymentInter)  : [],
                "Transfert" => $walletInter ? ProviderSingleCollection::collection($walletInter) : [],
                "Crédit téléphonique" => $walletPhoneInter ? ProviderSingleCollection::collection($walletPhoneInter) : [],
                "Versement bancaire" => $walletVirementBank ? ProviderSingleCollection::collection($walletVirementBank) : [],
                "Rechargement de carte" => $walletReloadCardInter ? ProviderSingleCollection::collection($walletReloadCardInter) : [],
                "Bank send" => $walletBankInter ? ProviderSingleCollection::collection($walletBankInter) : [],
            ];
        }


        return response()->json([
            'success' => true,
            'Wallets' => $walletsData,
        ], 200);
    }
}
