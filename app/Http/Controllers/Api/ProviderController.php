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
class ProviderController extends Controller
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
                    $dashboard_data['Transfert international'] = [
                        "Transfert" => ProviderSingleCollection::collection($walletInter),
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



    public function offers()
    {

        $offers = Offer::whereStatus(true)
            ->latest()
            ->orderBy('name', 'asc')
            ->get();

        return new  AllOfferCollection($offers);
    }
}