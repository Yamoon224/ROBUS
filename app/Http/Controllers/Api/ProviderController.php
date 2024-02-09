<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllOfferCollection;
use App\Http\Resources\ProviderCollection;
use App\Http\Resources\ProviderSingleCollection;
use App\Http\Services\ProvidersService;
use App\Models\AppStatus;
use App\Models\Costs\OrderCardCost;
use App\Models\Offer;
use App\Models\OperatorService;
use App\Models\Providers\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

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
        $walletInter =  $this->percentRobus->getProvidersByType(new Provider, robus()->country->id, 8);
        $walletReload =  $this->percentRobus->getProvidersByReloadType(new Provider, robus()->country->id, 1);
        $walletPhoneInter =  $this->percentRobus->getProvidersByType(new Provider, robus()->country->id, 9);
        $walletVirementBank =  $this->percentRobus->getProvidersByType(new Provider, robus()->country->id, 10);
        $walletWithdrawal =  $this->percentRobus->getProvidersByWithdrawal(new Provider, robus()->country->id, 1);
        $walletReloadCardInter =  $this->percentRobus->getProvidersByType(new Provider, robus()->country->id, 11);
        $walletOrderCard =  $this->percentRobus->getProvidersByTypeOrderCard(new Provider, robus()->country->id, 5);
        
        
        try {

            Artisan::call('app:close-status');

            // Récupérer le statut de l'application
            $appStatus = AppStatus::latest()->first();
    
    
            // Vérifier si l'application est fermée
            if ($appStatus && $appStatus->status_app === 'closed') {
    
                $robus_hour_begin = $appStatus->time_start;
                $robus_hour_end = $appStatus->time_end;
                $robus_hour_description = $appStatus->description;
    
                return response()->json([
                    'success' => false,
                    'message' => "L'application ROBUS etait ouverte aujourd'hui de  " . $robus_hour_begin . " a " . $robus_hour_end .  " et est fermée pour cause de " . $robus_hour_description,
                ], 301);
            }
            
            $operators = $this->percentRobus->OperatorServices();
            
            if ($walletUser->country->code == 'CI') {
                
                if ($walletUser->is_under_distributer == true) {
                    

                    $dashboard_data = [];
                    
                    foreach ($operators as $operator) {

                        switch ($operator->operator->id) {
                            case 1:
                                $dashboard_data[$operator->operator->name] = ProviderSingleCollection::collection($walletTransaction);
                                break;
                            case 2:
                                $dashboard_data[$operator->operator->name] = ProviderSingleCollection::collection($walletWithdrawal);
                                break;
                            case 3:
                                $dashboard_data[$operator->operator->name] = ProviderSingleCollection::collection($walletPayment);
                                break;
                            case 4:
                                $dashboard_data[$operator->operator->name] = ProviderSingleCollection::collection($walletBank);
                                break;
                            case 5:
                                $dashboard_data[$operator->operator->name] = ProviderSingleCollection::collection($walletReloadCard);
                                break;
                            case 6:
                                $dashboard_data[$operator->operator->name] = ProviderSingleCollection::collection($walletOrderCard);
                                break;
                            case 7:
                                $dashboard_data[$operator->operator->name] = [
                                    "Transfert" => ProviderSingleCollection::collection($walletInter),
                                ];                                
                                break;
                            case 8:
                                $dashboard_data[$operator->operator->name] = ProviderSingleCollection::collection($walletBank);
                                break;
                            default:
                                // Ne rien faire ou ajouter une logique de gestion d'erreur
                                break;
                        }
                    }
                  
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

                    $dashboard_data = [];
                    
                    foreach ($operators as $operator) {

                        switch ($operator->operator->id) {
                            case 1:
                                $dashboard_data[$operator->operator->name] = ProviderSingleCollection::collection($walletTransaction);
                                break;
                            case 7:
                                $dashboard_data[$operator->operator->name] = [
                                    "Transfert" => ProviderSingleCollection::collection($walletInter),
                                ];                                
                                break;
                            case 8:
                                $dashboard_data[$operator->operator->name] = ProviderSingleCollection::collection($walletBank);
                                break;
                            default:
                                // Ne rien faire ou ajouter une logique de gestion d'erreur
                                break;
                        }
                    }

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
