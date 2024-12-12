<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllOfferCollection;
use App\Http\Resources\ProviderCollection;
use App\Http\Resources\ProviderSingleCollection;
use App\Http\Services\AuthenticationService;
use App\Http\Services\ProvidersService;
use App\Models\AppStatus;
use App\Models\AppWebStatus;
use App\Models\Costs\OrderCardCost;
use App\Models\Offer;
use App\Models\Providers\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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
    private $authService;
    protected $appStatusResult;

    public function __construct(
        ProvidersService $percentRobus,
        AuthenticationService $authService
    ) {
        $this->percentRobus = $percentRobus;
        $this->authService = $authService;
        $this->appStatusResult = Cache::get('app_status_mobile');
   
        if (!$this->appStatusResult) {
            $this->appStatusResult = $this->authService->appDispoRobus(AppStatus::class);
            Cache::put('app_status_mobile', $this->appStatusResult, now()->addMinutes(30)); 
        }
    }

    public function indexAncien()
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

    public function index()
    {

        // Vérifier l'état de l'application
        $appStatusResult = $this->authService->appDispoRobus(AppStatus::class);

        if (!$appStatusResult['success']) {
            return response()->json($appStatusResult, 301);
        }

        $assignations = $this->percentRobus->operatorServices();

        $restrictionMapTransaction = [
            'TRANSFERT CREDIT' => 'showCreditButton',
            'PAIEMENT DE FACTURE' => 'showPaiementButton',
            'TRANSFERT INTERNATIONNAL MOBILE MONEY' => 'showInterButton',
            'RECHARGEMENT DE CARTE' => 'showRechargementButton',
            'VERSEMENT BANCAIRE' => 'showVirementButton',
            'RETRAIT' => 'showRetraitButton',
            'DEPOTS' => 'showDepotButton',
            'MOBILE MONEY' => 'showMobileButton',
            'DEPOT ET TRANSFERT MOBILE MONEY' => 'showMoneyButton',
            'TRANSFERT A INTER' => 'showTransacInterButton',
            'CREDIT TELEPHONIQUE A INTER' => 'showTelInterButton',
            'VERSEMENT BANCAIRE A INTER' => 'showBancInterButton',
            'RECHARGEMENT DE CARTE A INTER' => 'showCarteInterButton',
            'PAIMENT FACTURE A INTER' => 'showFacInterButton',
        ];

        $activeButtons = [];

        foreach ($assignations as $operator) {
            $restrictions = explode(',', $operator->name);
            foreach ($restrictionMapTransaction as $restriction => $buttonVar) {
                if (in_array($restriction, $restrictions)) {
                    $activeButtons[$buttonVar] = true;
                }
            }
        }

        $countrieId = robus()->country->id;
        $dashboard_data = [];
        $walletTransactionDepot = $this->percentRobus->getProvidersDepotByType(new Provider, $countrieId, 1);
        $walletTransactionMoney = $this->percentRobus->getProvidersMoneyByType(new Provider, $countrieId, 1);
        $walletPayment = $this->percentRobus->getProvidersByType(new Provider, $countrieId, 2);
        $walletPaymentInter = $this->percentRobus->getProvidersByType(new Provider, $countrieId, 7);
        $walletRetrait = Provider::whereId(9)->whereStatus(true)->get();
        $walletBank = $this->percentRobus->getProvidersByType(new Provider, $countrieId, 4);
        $walletPhone = $this->percentRobus->getProvidersByType(new Provider, $countrieId, 6);
        $walletReloadCard = $this->percentRobus->getProvidersByType(new Provider, $countrieId, 5);
        $walletInter = $this->percentRobus->getProvidersByType(new Provider, $countrieId, 8);
        $walletPhoneInter = $this->percentRobus->getProvidersByType(new Provider, $countrieId, 9);
        $walletVirementBank = $this->percentRobus->getProvidersByType(new Provider, $countrieId, 10);
        $walletReloadCardInter = $this->percentRobus->getProvidersByType(new Provider, $countrieId, 11);
        $walletTransaction =  $this->percentRobus->getProvidersByType(new Provider, $countrieId, 1);


        try {


            if (robus()->is_under_distributer == true) {

                if (!empty($activeButtons['showCreditButton'])) {
                    $dashboard_data['Crédit téléphonique'] = ProviderSingleCollection::collection($walletPhone);
                }

                if (!empty($activeButtons['showPaiementButton'])) {
                    $dashboard_data['Factures'] = ProviderSingleCollection::collection($walletPayment);
                }

                if (!empty($activeButtons['showInterButton'])) {
                    $dashboard_data['Transfert international'] = [
                        "Factures" => !empty($activeButtons['showFacInterButton']) ? ProviderSingleCollection::collection($walletPaymentInter) : [],
                        "Transfert" => !empty($activeButtons['showTransacInterButton']) ? ProviderSingleCollection::collection($walletInter) : [],
                        "Crédit téléphonique" => !empty($activeButtons['showTelInterButton']) ? ProviderSingleCollection::collection($walletPhoneInter) : [],
                        "Versement bancaire" => !empty($activeButtons['showBancInterButton']) ? ProviderSingleCollection::collection($walletVirementBank) : [],
                        "Rechargement de carte" => !empty($activeButtons['showCarteInterButton']) ? ProviderSingleCollection::collection($walletReloadCardInter) : [],
                    ];
                }

                if (!empty($activeButtons['showVirementButton'])) {
                    $dashboard_data['Versement bancaire'] = ProviderSingleCollection::collection($walletBank);
                }

                if (!empty($activeButtons['showRechargementButton'])) {
                    $dashboard_data['Rechargement & commande de carte'] = ProviderSingleCollection::collection($walletReloadCard);
                }

                if (!empty($activeButtons['showMoneyButton'])) {
                    $dashboard_data['Transfert national'] = [
                        "Dépôts" => !empty($activeButtons['showDepotButton']) ? ProviderSingleCollection::collection($walletTransactionDepot) : [],
                        "Mobile Money" => !empty($activeButtons['showMobileButton']) ? ProviderSingleCollection::collection($walletTransactionMoney) : [],
                    ];
                }

                if (!empty($activeButtons['showRetraitButton'])) {
                    $dashboard_data['Retrait'] = ProviderSingleCollection::collection($walletRetrait);
                }
            } else {


                if (robus()->country->code == 'CI') {
                    $dashboard_data['Transfert national'] = ProviderSingleCollection::collection($walletTransaction);
                    $dashboard_data['Factures'] = ProviderSingleCollection::collection($walletPayment);
                    $dashboard_data['Versement bancaire'] = ProviderSingleCollection::collection($walletBank);
                    $dashboard_data['Crédit téléphonique'] = ProviderSingleCollection::collection($walletPhone);
                    $dashboard_data['Rechargement & commande de carte'] = ProviderSingleCollection::collection($walletReloadCard);
                    // $dashboard_data['Achat d\'UV'] = ProviderSingleCollection::collection($walletReload);
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
                'Wallets' => $dashboard_data,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Une erreur inattendue s'est produite. Veuillez informer le support client ROBUS.",
            ], 500);
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
