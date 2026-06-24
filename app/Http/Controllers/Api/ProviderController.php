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
use App\Traits\InitializeAppStatusTrait;

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

    use InitializeAppStatusTrait;

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
        $this->initializeAppStatus($this->authService);
    }


    public function index()
    {
        $appStatusResult = $this->authService->appDispoRobus(AppStatus::class);
        $countrieId = robus()->country->id;
        $dashboard_data = [];


        try {

            $cacheKey = "dashboard_data_user_" . robus()->id . "_country_" . $countrieId;
            // 1️⃣ Cas où l'app est indisponible
            if (!$appStatusResult['success']) {

                $walletTransactionDepot = $this->percentRobus->getProvidersDepotByType(new Provider, $countrieId, 1);
                $walletTransactionDrawal = $this->percentRobus->getProvidersDrawalByType(new Provider, $countrieId, 29);
                $walletTransactionReload = $this->percentRobus->getProvidersByType(new Provider, $countrieId, 30);

                $dashboard_data['Transfert national'] = [
                    "Dépôts" => ProviderSingleCollection::collection($walletTransactionDepot),
                ];

                (robus()->is_under_distributer || robus()->is_api) ?  $dashboard_data['Retrait'] =  ProviderSingleCollection::collection($walletTransactionDrawal) : [];

                (robus()->is_custumers) ? $dashboard_data['Achat UV'] = ProviderSingleCollection::collection($walletTransactionReload) : [];

                return response()->json(['success' => true, 'Wallets' => $dashboard_data], 200);
            }

            // 2️⃣ Récupération des assignations et mapping restrictions

            return Cache::remember($cacheKey, 1800, function () use ($countrieId) {
                $assignations = $this->percentRobus->operatorServices();
                $restrictionMap = [
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
                    'TRANSFERT PUSH A INTER' => 'showPushInterButton',
                    'TRANSFERT DJAMO A INTER' => 'showDjamoInterButton',
                    'TRANSFERT TRESORT A INTER' => 'showTresortInterButton',
                    'TRANSFERT BANK SEND A INTER' => 'showFacBankSendInterButton',
                    'RETRAIT XBETS A INTER' => 'showXbetsRetraitInterButton',
                    'XBETS A INTER' => 'showXbetsInterButton',
                    'VERSEMENT A INTER' => 'showVersementInterButton',
                    'VERSEMENT BANCAIRE A INTER' => 'showBancInterButton',
                    'RECHARGEMENT DE CARTE A INTER' => 'showCarteInterButton',
                    'PAIMENT FACTURE A INTER' => 'showFacInterButton',
                    'TRANSFERT PUSH' => 'showPushButton',
                    'TRANSFERT DJAMO' => 'showDjamBoutton',
                    'TRANSFERT TRESORT' => 'showTresortButton',
                    'TRANSFERT BANK SEND' => 'showFacBankSendButton',
                    'RETRAIT XBETS' => 'showXbetsRetraitButton',
                    '1XBET' => 'showXbetsButton',
                    'VERSEMENT' => 'showVersementButton',
                ];

                $activeButtons = [];
                foreach ($assignations as $operator) {
                    $restrictions = explode(',', $operator->name);
                    foreach ($restrictionMap as $restriction => $button) {
                        if (in_array($restriction, $restrictions)) $activeButtons[$button] = true;
                    }
                }

                // 3️⃣ Charger **tous les providers en une seule fois** pour éviter les multiples requêtes
                $providerTypes = [
                    'walletTransactionDepot' => [1, 'depot'],
                    'walletTransactionMoney' => [1, 'money'],
                    'walletPayment' => [2, 'payment'],
                    'walletPaymentInter' => [7, 'paymentInter'],
                    'walletRetrait' => [29, 'retrait'],
                    'walletBank' => [[4, 18], 'bank'],
                    'walletPhone' => [6, 'phone'],
                    'walletReloadCard' => [[24, 25, 5], 'reloadCard'],
                    'walletInter' => [8, 'inter'],
                    'walletPhoneInter' => [9, 'phoneInter'],
                    'walletVirementBank' => [10, 'virementBank'],
                    'walletReloadCardInter' => [11, 'reloadCardInter'],
                    'walletTransaction' => [[1, 23], 'transaction'],
                    'walletTresort' => [23, 'tresort'],
                    'walletBankSend' => [27, 'bankSend'],
                    'walletInterPush' => [21, 'interPush'],
                    'walletInterDjamo' => [22, 'interDjamo'],
                    'walletInterTresort' => [20, 'interTresort'],
                    'walletInterBankSend' => [28, 'interBankSend'],
                    'walletInterVersement' => [10, 'interVersement'],
                    'walletXbet' => [13, 'xbet'],
                    'walletInterXbet' => [26, 'interXbet'],
                    'walletUv' => [30, 'relaodUv'],

                ];

                $providerCacheKey = "providers_country_{$countrieId}";

                $providers = [];

                $providerCacheKey = "providers_country_{$countrieId}";
                $providers        = Cache::get($providerCacheKey, []);


                if (empty($providers)) {
                    // Cache vide → charger tous les providers depuis la BDD
                    foreach ($providerTypes as $key => [$type, $alias]) {
                        if (is_array($type)) {
                            $providers[$alias] = $this->percentRobus->getProvidersReloadByType(new Provider, $countrieId, $type);
                        } elseif ($alias === 'depot') {
                            $providers[$alias] = $this->percentRobus->getProvidersDepotByType(new Provider, $countrieId, $type);
                        } elseif ($alias === 'money') {
                            $providers[$alias] = $this->percentRobus->getProvidersMoneyByType(new Provider, $countrieId, $type);
                        } else {
                            $providers[$alias] = $this->percentRobus->getProvidersByType(new Provider, $countrieId, $type);
                        }
                    }

                    Cache::put($providerCacheKey, $providers, 1800);
                } else {
                    // Cache existant, mais vérifier qu'on a tous les alias nécessaires
                    $updated = false;
                    foreach ($providerTypes as $key => [$type, $alias]) {
                        if (!isset($providers[$alias])) {
                            if (is_array($type)) {
                                $providers[$alias] = $this->percentRobus->getProvidersReloadByType(new Provider, $countrieId, $type);
                            } elseif ($alias === 'depot') {
                                $providers[$alias] = $this->percentRobus->getProvidersDepotByType(new Provider, $countrieId, $type);
                            } elseif ($alias === 'money') {
                                $providers[$alias] = $this->percentRobus->getProvidersMoneyByType(new Provider, $countrieId, $type);
                            } else {
                                $providers[$alias] = $this->percentRobus->getProvidersByType(new Provider, $countrieId, $type);
                            }
                            $updated = true;
                        }
                    }

                    if ($updated) {
                        Cache::put($providerCacheKey, $providers, 1800);
                    }
                }


                // 4️⃣ Construire le dashboard de manière dynamique
                if (robus()->is_under_distributer || robus()->is_api) {

                    $dashboard_data['Achat UV'] = ProviderSingleCollection::collection($providers['relaodUv']);

                    if (robus()->country->code == 'CI') {

                        if (!empty($activeButtons['showCreditButton'])) {
                            $dashboard_data['Crédit téléphonique'] = ProviderSingleCollection::collection($providers['phone']);
                        }
                        if (!empty($activeButtons['showPaiementButton'])) {
                            $dashboard_data['Factures'] = ProviderSingleCollection::collection($providers['payment']);
                        }


                        if (!empty($activeButtons['showXbetsButton'])) {
                            $xbets = ProviderSingleCollection::collection($providers['xbet']);
                            if ($xbets->count() > 0) {
                                $dashboard_data['Xbets'] = $xbets;
                            }
                        }
                        
                        if (!empty($activeButtons['showXbetsButton']) && !empty($providers['xbet'])) {
                            $dashboard_data['Xbets'] = ProviderSingleCollection::collection($providers['xbet']);
                        }

                        if (!empty($activeButtons['showInterButton'])) {
                            $dashboard_data['Transfert international'] = [
                                "Transfert" => !empty($activeButtons['showTransacInterButton']) ? ProviderSingleCollection::collection($providers['inter']) : [],
                            ];
                        }

                        if (!empty($activeButtons['showVirementButton'])) {
                            $dashboard_data['Versement bancaire'] = ProviderSingleCollection::collection($providers['bank']);
                        }
                        if (!empty($activeButtons['showRechargementButton'])) {
                            $dashboard_data['Rechargement & commande de carte'] = ProviderSingleCollection::collection($providers['reloadCard']);
                        }
                        if (!empty($activeButtons['showMoneyButton'])) {
                            $dashboard_data['Transfert national'] = [
                                "Dépôts" => !empty($activeButtons['showDepotButton']) ? ProviderSingleCollection::collection($providers['depot']) : [],
                                "Mobile Money" => !empty($activeButtons['showMobileButton']) ? ProviderSingleCollection::collection($providers['money']) : [],
                                "Tresort Money" => !empty($activeButtons['showTresortButton']) ? ProviderSingleCollection::collection($providers['tresort']) : [],
                            ];
                        }
                        if (!empty($activeButtons['showRetraitButton'])) {
                            $dashboard_data['Retrait'] = ProviderSingleCollection::collection($providers['retrait']);
                        }
                    } else {

                        if (!empty($activeButtons['showXbetsButton'])) {
                            $dashboard_data['Xbets'] = ProviderSingleCollection::collection($providers['xbet']);
                        }

                        if (!empty($activeButtons['showMoneyButton'])) {
                            $dashboard_data['Transfert national'] = [
                                "Dépôts" => !empty($activeButtons['showDepotButton']) ? ProviderSingleCollection::collection($providers['depot']) : [],
                            ];
                        }

                        if (!empty($activeButtons['showRetraitButton'])) {
                            $dashboard_data['Retrait'] = ProviderSingleCollection::collection($providers['retrait']);
                        }

                        if (!empty($activeButtons['showInterButton'])) {
                            $dashboard_data['Transfert international'] = [
                                "Transfert" => !empty($activeButtons['showTransacInterButton']) ? ProviderSingleCollection::collection($providers['inter']) : [],
                                "Bank Send" => !empty($activeButtons['showFacBankSendInterButton']) ? ProviderSingleCollection::collection($providers['interBankSend']) : [],
                            ];
                        }
                    }
                } else {
                    // Cas normal hors distributeur/API
                    if (robus()->country->code == 'CI') {
                        $dashboard_data['Transfert national'] = ProviderSingleCollection::collection($providers['transaction']);
                        $dashboard_data['Factures'] = ProviderSingleCollection::collection($providers['payment']);
                        $dashboard_data['Versement bancaire'] = ProviderSingleCollection::collection($providers['bank']);
                        $dashboard_data['Crédit téléphonique'] = ProviderSingleCollection::collection($providers['phone']);
                        $dashboard_data['Achat UV'] = ProviderSingleCollection::collection($providers['relaodUv']);
                        $dashboard_data['Rechargement & commande de carte'] = ProviderSingleCollection::collection($providers['reloadCard']);
                        $dashboard_data['Retrait'] = ProviderSingleCollection::collection($providers['retrait']);
                        $dashboard_data['Xbets'] = ProviderSingleCollection::collection($providers['xbet']);
                        $dashboard_data['Transfert international'] = [
                            "Transfert" => ProviderSingleCollection::collection($providers['inter']),
                            "Bank Send" =>  ProviderSingleCollection::collection($providers['interBankSend']),
                        ];
                    } else {
                        $dashboard_data['Transfert national'] = ProviderSingleCollection::collection($providers['transaction']);
                        $dashboard_data['Xbets'] = ProviderSingleCollection::collection($providers['xbet']);
                        $dashboard_data['Achat UV'] = ProviderSingleCollection::collection($providers['relaodUv']);
                        $dashboard_data['Transfert international'] = [
                            "Factures" => ProviderSingleCollection::collection($providers['paymentInter']),
                            "Transfert" => ProviderSingleCollection::collection($providers['inter']),
                            "Crédit téléphonique" => ProviderSingleCollection::collection($providers['phoneInter']),
                            "Versement bancaire" => ProviderSingleCollection::collection($providers['virementBank']),
                            "Rechargement de carte" => ProviderSingleCollection::collection($providers['reloadCardInter']),
                            "Bank Send" =>  ProviderSingleCollection::collection($providers['interBankSend']),
                        ];
                    }
                }

                return response()->json(['success' => true, 'Wallets' => $dashboard_data], 200);
            });
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Une erreur inattendue s'est produite. Veuillez informer le support client ROBUS.",
            ], 500);
        }
    }



    public function indexProvidersProspect()
    {


        // $appStatusResult = $this->authService->appDispoRobus(AppStatus::class);

        // if (!$appStatusResult['success']) {
        //     return response()->json($appStatusResult, 301);
        // }

        $assignations = $this->percentRobus->operatorIntegrateServices();

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

            'TRANSFERT PUSH A INTER' => 'showPushInterButton',
            'TRANSFERT DJAMO A INTER' => 'showDjamoInterButton',
            'TRANSFERT TRESORT A INTER' => 'showTresortInterButton',
            'TRANSFERT BANK SEND A INTER' => 'showFacBankSendInterButton',
            'RETRAIT XBETS A INTER' => 'showXbetsRetraitInterButton',
            'XBETS A INTER' => 'showXbetsInterButton',
            'VERSEMENT A INTER' => 'showVersementInterButton',

            'VERSEMENT BANCAIRE A INTER' => 'showBancInterButton',
            'RECHARGEMENT DE CARTE A INTER' => 'showCarteInterButton',
            'PAIMENT FACTURE A INTER' => 'showFacInterButton',
            'TRANSFERT PUSH' => 'showPushButton',
            'TRANSFERT DJAMO' => 'showDjamBoutton',
            'TRANSFERT TRESORT' => 'showTresortButton',
            'TRANSFERT BANK SEND' => 'showBankSendButton',
            'RETRAIT XBETS' => 'showXbetsRetraitButton',
            '1XBET' => 'showXbetsButton',
            'VERSEMENT' => 'showVersementButton',
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

        $countrieId = integrate()->country->id;
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
        $walletPush =  $this->percentRobus->getProvidersByType(new Provider, $countrieId, 24);
        $walletXbet =  $this->percentRobus->getProvidersByType(new Provider, $countrieId, 13);
        $walletDjamo =  $this->percentRobus->getProvidersByType(new Provider, $countrieId, 25);
        $walletTresort =  $this->percentRobus->getProvidersByType(new Provider, $countrieId, 23);
        $walletBankSend =  $this->percentRobus->getProvidersByType(new Provider, $countrieId, 27);
        $walletVersement =  $this->percentRobus->getProvidersByType(new Provider, $countrieId, 18);
        $walletInterPush =  $this->percentRobus->getProvidersByType(new Provider, $countrieId, 21);
        $walletInterDjamo =  $this->percentRobus->getProvidersByType(new Provider, $countrieId, 22);
        $walletInterTresort =  $this->percentRobus->getProvidersByType(new Provider, $countrieId, 20);
        $walletInterBankSend =  $this->percentRobus->getProvidersByType(new Provider, $countrieId, 28);
        $walletInterVersement =  $this->percentRobus->getProvidersByType(new Provider, $countrieId, 10);
        $walletUv =  $this->percentRobus->getProvidersByType(new Provider, $countrieId, 30);


        try {


            if (integrate()->is_api == true) {

                if (!empty($activeButtons['showCreditButton'])) {
                    $dashboard_data['Crédit téléphonique'] = ProviderSingleCollection::collection($walletPhone);
                }

                if (!empty($activeButtons['showPaiementButton'])) {
                    $dashboard_data['Factures'] = ProviderSingleCollection::collection($walletPayment);
                }
                if (!empty($activeButtons['showPushButton'])) {
                    $dashboard_data['Push'] = ProviderSingleCollection::collection($walletPush);
                }
                if (!empty($activeButtons['showDjamBoutton'])) {
                    $dashboard_data['Djamo'] = ProviderSingleCollection::collection($walletDjamo);
                }
                if (!empty($activeButtons['showTresortButton'])) {
                    $dashboard_data['Tresort'] = ProviderSingleCollection::collection($walletTresort);
                }
                if (!empty($activeButtons['showBankSendButton'])) {
                    $dashboard_data['Bank Send'] = ProviderSingleCollection::collection($walletBankSend);
                }
                if (!empty($activeButtons['showXbetsRetraitButton'])) {
                    $dashboard_data['Retrait Xbets'] = ProviderSingleCollection::collection($walletXbet);
                }
                if (!empty($activeButtons['showXbetsButton'])) {
                    $dashboard_data['Xbets'] = ProviderSingleCollection::collection($walletXbet);
                }
                if (!empty($activeButtons['showVersementButton'])) {
                    $dashboard_data['Versement'] = ProviderSingleCollection::collection($walletVersement);
                }

                if (!empty($activeButtons['showVirementButton'])) {
                    $dashboard_data['Versement bancaire'] = ProviderSingleCollection::collection($walletBank);
                }

                if (!empty($activeButtons['showRechargementButton'])) {
                    $dashboard_data['Rechargement & commande de carte'] = ProviderSingleCollection::collection($walletReloadCard);
                }

                if (!empty($activeButtons['showMoneyButton'])) {
                    $dashboard_data['Transfert international'] = [
                        "Factures" => !empty($activeButtons['showFacInterButton']) ? ProviderSingleCollection::collection($walletPaymentInter) : [],
                        "Transfert" => !empty($activeButtons['showTransacInterButton']) ? ProviderSingleCollection::collection($walletInter) : [],
                        "Crédit téléphonique" => !empty($activeButtons['showTelInterButton']) ? ProviderSingleCollection::collection($walletPhoneInter) : [],
                        "Versement bancaire" => !empty($activeButtons['showBancInterButton']) ? ProviderSingleCollection::collection($walletVirementBank) : [],
                        "Rechargement de carte" => !empty($activeButtons['showCarteInterButton']) ? ProviderSingleCollection::collection($walletReloadCardInter) : [],

                        // New transactions
                        "Push" => !empty($activeButtons['showPushInterButton']) ? ProviderSingleCollection::collection($walletInterPush) : [],
                        "Djamo" => !empty($activeButtons['showDjamoInterButton']) ? ProviderSingleCollection::collection($walletInterDjamo) : [],
                        "Tresort" => !empty($activeButtons['showTresortInterButton']) ? ProviderSingleCollection::collection($walletInterTresort) : [],
                        "Bank Send" => !empty($activeButtons['showFacBankSendInterButton']) ? ProviderSingleCollection::collection($walletInterBankSend) : [],
                        "Versement" => !empty($activeButtons['showVersementInterButton']) ? ProviderSingleCollection::collection($walletInterVersement) : [],
                        // "Xbets" => !empty($activeButtons['showXbetsInterButton']) ? ProviderSingleCollection::collection($walletInterXbet) : [],
                    ];
                }

                if (!empty($activeButtons['showRetraitButton'])) {
                    $dashboard_data['Retrait'] = ProviderSingleCollection::collection($walletRetrait);
                }
            } else {

                return response()->json([
                    'success' => false,
                    'message' => "Veuillez verifier que vous etes de ROBUS.",
                ], 500);
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
