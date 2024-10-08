<?php

namespace App\Http\Controllers\Api;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Models\Providers\Provider;
use App\Models\Costs\OrderCardCost;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\ProvidersService;
use App\Http\Resources\AllOfferCollection;
use App\Http\Resources\ProviderCollection;
use App\Http\Resources\ProviderSingleCollection;

/**
 * @OA\Get(
 *      path="/provides",
 *      tags={"provides"},
 *      summary="Get List Of Providers",
 *      description="Returns all provides",
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
class NewProviderController extends Controller
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
        $walletUser  =  Auth::guard('api')->user();

        $walletTransaction =  $this->percentRobus->getProvidersByType(new Provider, robus()->country->id, 1);
        $walletPayment =  $this->percentRobus->getProvidersByType(new Provider, robus()->country->id, 2);
        $walletPaymentInter =  $this->percentRobus->getProvidersByType(new Provider, robus()->country->id, 7);
        $walletBank =  $this->percentRobus->getProvidersByType(new Provider, robus()->country->id, 4);
        $walletPhone =  $this->percentRobus->getProvidersByType(new Provider, robus()->country->id, 6);
        $walletReloadCard =  $this->percentRobus->getProvidersByType(new Provider, robus()->country->id, 5);
        // $walletOrderCard =  $this->percentRobus->getProvidersByType(new Provider, robus()->country->id, 5);
        $walletInter =  $this->percentRobus->getProvidersByType(new Provider, robus()->country->id, 8);
        $walletReload =  $this->percentRobus->getProvidersByType(new Provider, robus()->country->id, 1);
        $walletPhoneInter =  $this->percentRobus->getProvidersByType(new Provider, robus()->country->id, 9);
        $walletVirementBank =  $this->percentRobus->getProvidersByType(new Provider, robus()->country->id, 10);
        $walletReloadCardInter =  $this->percentRobus->getProvidersByType(new Provider, robus()->country->id, 11);
        $walletOrderCard = OrderCardCost::where('provider_type_id',  5)
            ->orderBy('name', 'asc')
            ->get();


        try {

            if ($walletUser->country->code == 'CI') {

                if ($walletUser->is_under_distributer == true) {

                    $dashboard_data['Transfert national'] = ProviderSingleCollection::collection($walletTransaction);
                    $dashboard_data['Factures'] = ProviderSingleCollection::collection($walletPayment);
                    $dashboard_data['Versement bancaire'] = ProviderSingleCollection::collection($walletBank);
                    $dashboard_data['Crédit téléphonique'] = ProviderSingleCollection::collection($walletPhone);
                    $dashboard_data['Rechargement & commande de carte'] =  ProviderSingleCollection::collection($walletReloadCard);
                    $dashboard_data['Achat d\'UV'] = ProviderSingleCollection::collection($walletReload);
                    $dashboard_data['Transfert international'] = [
                        "Transfert" => ProviderSingleCollection::collection($walletInter),
                    ];
                } else {

                    $dashboard_data['Transfert national'] = ProviderSingleCollection::collection($walletTransaction);
                    $dashboard_data['Factures'] = ProviderSingleCollection::collection($walletPayment);
                    $dashboard_data['Versement bancaire'] = ProviderSingleCollection::collection($walletBank);
                    $dashboard_data['Crédit téléphonique'] = ProviderSingleCollection::collection($walletPhone);
                    $dashboard_data['Rechargement & commande de carte'] = ProviderSingleCollection::collection($walletReloadCard);
                    $dashboard_data['Achat d\'UV'] = ProviderSingleCollection::collection($walletReload);
                    $dashboard_data['Transfert international'] = [
                        "Transfert" => ProviderSingleCollection::collection($walletInter),
                    ];
                }
            } else {

                if ($walletUser->is_under_distributer == true) {

                    $dashboard_data['Transfert national'] = ProviderSingleCollection::collection($walletTransaction);
                    // $dashboard_data['Achat d\'UV'] = ProviderSingleCollection::collection($walletReload);
                    $dashboard_data['Transfert international'] = [
                        // "Factures" => ProviderSingleCollection::collection($walletPaymentInter),
                        "Transfert" => ProviderSingleCollection::collection($walletInter),
                        // "Crédit téléphonique" => ProviderSingleCollection::collection($walletPhoneInter),
                        // "Versement bancaire" => ProviderSingleCollection::collection($walletVirementBank),
                        // "Rechargement de carte" => ProviderSingleCollection::collection($walletReloadCardInter),
                    ];
                } else {

                    $dashboard_data['Transfert national'] = ProviderSingleCollection::collection($walletTransaction);
                    $dashboard_data['Transfert international'] = [
                        "Factures" => ProviderSingleCollection::collection($walletPaymentInter),
                        "Transfert" => ProviderSingleCollection::collection($walletInter),
                        "Crédit téléphonique" => ProviderSingleCollection::collection($walletPhoneInter),
                        "Versement bancaire" => ProviderSingleCollection::collection($walletVirementBank),
                        "Rechargement de carte" => ProviderSingleCollection::collection($walletReloadCardInter),
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'Wallets' =>  $dashboard_data,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
            ], 333);
        }
    }

    // public function index()
    // {
    //     $walletUser  =  Auth::guard('api')->user();

    //     $walletTransaction = Provider::join('country_services', 'country_services.provider_id', 'providers.id')
    //         ->where('country_services.country_id', $walletUser->country->id)
    //         ->where('providers.status', true)
    //         ->where('providers.provider_type_id',  1)
    //         ->select('providers.*')
    //         ->orderBy('providers.name', 'asc')->get();


    //     $walletPayment = Provider::join('country_services', 'country_services.provider_id', 'providers.id')
    //         ->where('country_services.country_id', $walletUser->country->id)
    //         ->where('providers.status', true)
    //         ->where('providers.provider_type_id',  2)
    //         ->select('providers.*')
    //         ->orderBy('providers.name', 'asc')->get();

    //     $walletPaymentInter = Provider::join('country_services', 'country_services.provider_id', 'providers.id')
    //         ->where('country_services.country_id', $walletUser->country->id)
    //         ->where('providers.status', true)
    //         ->where('providers.provider_type_id',  7)
    //         ->select('providers.*')
    //         ->orderBy('providers.name', 'asc')->get();


    //     $walletBank = Provider::join('country_services', 'country_services.provider_id', 'providers.id')
    //         ->where('country_services.country_id', $walletUser->country->id)
    //         ->where('providers.status', true)
    //         ->where('providers.provider_type_id',  4)
    //         ->select('providers.*')
    //         ->orderBy('providers.name', 'asc')
    //         ->get();


    //     $walletPhone = Provider::join('country_services', 'country_services.provider_id', 'providers.id')
    //         ->where('country_services.country_id', $walletUser->country->id)
    //         ->where('providers.status', true)
    //         ->where('providers.provider_type_id',  6)
    //         ->select('providers.*')
    //         ->orderBy('providers.name', 'asc')
    //         ->get();

    //     $walletReloadCard = Provider::join('country_services', 'country_services.provider_id', 'providers.id')
    //         ->where('country_services.country_id', $walletUser->country->id)
    //         ->where('providers.status', true)
    //         ->where('providers.provider_type_id',  5)
    //         ->select('providers.*')
    //         ->orderBy('providers.name', 'asc')
    //         ->get();

    //     $walletInter = Provider::join('country_services', 'country_services.provider_id', 'providers.id')
    //         ->where('country_services.country_id', $walletUser->country->id)
    //         ->where('providers.status', true)
    //         ->where('providers.provider_type_id',  8)
    //         ->select('providers.*')
    //         ->orderBy('providers.name', 'asc')
    //         ->get();

    //     $walletInter_under_distri = Provider::join('country_services', 'country_services.provider_id', 'providers.id')
    //         ->where('country_services.country_id', $walletUser->country->id)
    //         ->where('providers.status', true)
    //         ->where('providers.provider_type_id',  8)
    //         ->where('providers.id', 51)
    //         ->select('providers.*')
    //         ->orderBy('providers.name', 'asc')
    //         ->get();

    //     $walletReload = Provider::join('country_services', 'country_services.provider_id', 'providers.id')
    //         ->where('country_services.country_id', $walletUser->country->id)
    //         ->where('providers.status', true)
    //         ->where('providers.name', '!=', 'Wave')
    //         ->where('providers.provider_type_id',  1)
    //         ->select('providers.*')
    //         ->orderBy('providers.name', 'asc')->get();


    //     $walletPhoneInter = Provider::join('country_services', 'country_services.provider_id', 'providers.id')
    //         ->where('country_services.country_id', $walletUser->country->id)
    //         ->where('providers.status', true)
    //         ->where('providers.provider_type_id',  9)
    //         ->select('providers.*')
    //         ->orderBy('providers.name', 'asc')
    //         ->get();


    //     $walletVirementBank = Provider::join('country_services', 'country_services.provider_id', 'providers.id')
    //         ->where('country_services.country_id', $walletUser->country->id)
    //         ->where('providers.status', true)
    //         ->where('providers.provider_type_id',  10)
    //         ->select('providers.*')
    //         ->orderBy('providers.name', 'asc')
    //         ->get();


    //     $walletReloadCardInter = Provider::join('country_services', 'country_services.provider_id', 'providers.id')
    //         ->where('country_services.country_id', $walletUser->country->id)
    //         ->where('providers.status', true)
    //         ->where('providers.provider_type_id',  11)
    //         ->select('providers.*')
    //         ->orderBy('providers.name', 'asc')
    //         ->get();

    //     if ($walletUser->country->code == 'CI') {

    //         if ($walletUser->is_under_distributer == true) {

    //             $dashboard_data['Transfert national'] = ProviderCollection::collection($walletTransaction);
    //             $dashboard_data['Factures'] = ProviderCollection::collection($walletPayment);
    //             $dashboard_data['Versement bancaire'] = ProviderCollection::collection($walletBank);
    //             $dashboard_data['Crédit téléphonique'] = ProviderCollection::collection($walletPhone);
    //             $dashboard_data['Rechargement & commande de carte'] = ProviderCollection::collection($walletReloadCard);
    //             $dashboard_data['Achat d\'UV'] = ProviderCollection::collection($walletReload);
    //         } else {

    //             $dashboard_data['Transfert national'] = ProviderCollection::collection($walletTransaction);
    //             $dashboard_data['Factures'] = ProviderCollection::collection($walletPayment);
    //             $dashboard_data['Versement bancaire'] = ProviderCollection::collection($walletBank);
    //             $dashboard_data['Crédit téléphonique'] = ProviderCollection::collection($walletPhone);
    //             $dashboard_data['Rechargement & commande de carte'] = ProviderCollection::collection($walletReloadCard);
    //             $dashboard_data['Achat d\'UV'] = ProviderCollection::collection($walletReload);
    //             $dashboard_data['Transfert international'] = [
    //                 // "facture" => ProviderCollection::collection($walletPaymentInter),
    //                 "Transfert" => ProviderCollection::collection($walletInter),
    //                 // "phone_credit" => ProviderCollection::collection($walletPhoneInter),
    //                 // "virement_bank" => ProviderCollection::collection($walletVirementBank),
    //                 // "reload_card" => ProviderCollection::collection($walletReloadCardInter),
    //             ];
    //         }
    //     } else {

    //         if ($walletUser->is_under_distributer == true) {

    //             $dashboard_data['Transfert national'] = ProviderCollection::collection($walletTransaction);
    //             $dashboard_data['Achat d\'UV'] = ProviderCollection::collection($walletReload);
    //             // $dashboard_data['Transfert international'] = [
    //             //     "Factures" => ProviderCollection::collection($walletPaymentInter),
    //             //     // "Transfert" => ProviderCollection::collection($walletInter_under_distri),
    //             //     "Crédit téléphonique" => ProviderCollection::collection($walletPhoneInter),
    //             //     "Versement bancaire" => ProviderCollection::collection($walletVirementBank),
    //             //     "Rechargement de carte" => ProviderCollection::collection($walletReloadCardInter),
    //             // ];
    //         } else {

    //             $dashboard_data['Transfert national'] = ProviderCollection::collection($walletTransaction);
    //             $dashboard_data['Transfert international'] = [
    //                 "Factures" => ProviderCollection::collection($walletPaymentInter),
    //                 "Transfert" => ProviderCollection::collection($walletInter),
    //                 "Crédit téléphonique" => ProviderCollection::collection($walletPhoneInter),
    //                 "Versement bancaire" => ProviderCollection::collection($walletVirementBank),
    //                 "Rechargement de carte" => ProviderCollection::collection($walletReloadCardInter),
    //             ];
    //             // $dashboard_data['Achat d\'UV'] = ProviderCollection::collection($walletReload);
    //         }
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'Wallets' =>  $dashboard_data,
    //     ], 200);
    // }


    public function offers()
    {

        $offers = Offer::whereStatus(true)
            ->latest()
            ->orderBy('name', 'asc')
            ->get();

        return new  AllOfferCollection($offers);
    }
}