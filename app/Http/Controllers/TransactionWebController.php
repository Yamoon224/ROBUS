<?php

namespace App\Http\Controllers;

use App\Console\Commands\CheckAppWeb;
use App\Events\Visitors\CreateWalletInterVisEvent;
use App\Events\Visitors\CreateWalletOneXbetVisEvent;
use App\Events\Visitors\CreateWalletReloadCardVisEvent;
use App\Events\Visitors\CreateWalletTransfersVisEvent;
use App\Http\Controllers\Controller;
use App\Http\Services\AuthenticationService;
use App\Http\Services\NotificationWebService;
use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\ProvidersService;
use App\Http\Services\TransactionWebService;
use App\Http\Services\ValidateDailyLimitService;
use App\Models\AppWebStatus;
use App\Models\Costs\DepositCost;
use App\Models\Costs\PhoneCreditCost;
use App\Models\Costs\TransactionCost;
use App\Models\Custumers\Country;
use App\Models\Onexbet;
use App\Models\Providers\Provider;
use App\Models\Wallets\Wallet;
use App\Repositories\ReloadCardRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;


class TransactionWebController extends Controller
{


    /** @var  ReloadCardRepository */
    protected $reloadCardRepository;
    protected $deletedService;
    protected $percentRobus;
    protected $validatedamountDays;
    protected $notificationOrderCard;
    protected $reloadCardService;
    protected $providersService;
    private $authService;
    protected $appStatusResult;

    public function __construct(
        ReloadCardRepository $reload_cardReposi,
        NotificationWebService $notificationOrderCard,
        TransactionWebService $reloadCardService,
        PercentRobusAuthService $percentRobus,
        ValidateDailyLimitService $validatedamountDays,
        ProvidersService $providersService,
        AuthenticationService $authService

    ) {
        $this->reloadCardRepository = $reload_cardReposi;
        $this->notificationOrderCard = $notificationOrderCard;
        $this->reloadCardService = $reloadCardService;
        $this->percentRobus = $percentRobus;
        $this->validatedamountDays = $validatedamountDays;
        $this->providersService = $providersService;
        $this->authService = $authService;
        $this->appStatusResult = Cache::get('app_status_result');
   
        if (!$this->appStatusResult) {
            $this->appStatusResult = $this->authService->appDispoWebRobus(AppWebStatus::class);
            Cache::put('app_status_result', $this->appStatusResult, now()->addMinutes(20)); // Mettre en cache pendant 60 minutes
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transactions_reloads()
    {


        $deposit_cost = DepositCost::whereIsWeb(true)->first();

        $carte_percent = $deposit_cost->percent * 100;

        $robus_percent = $deposit_cost->percent_admin * 100;

        $carte_reloads = Provider::whereStatus(true)->where('provider_type_id',  5)
            ->orderBy('name', 'asc')->where('id' , '!=' , 91)->get();

        $total_percent =  $carte_percent +  $robus_percent;

        $calcul_valeurs = (float) $total_percent  / 100;

        return view('pages.transactionWeb.browse_carte_reloads', [
            'appStatusResult' => $this->appStatusResult,
            'carte_percent' => $carte_percent,
            'robus_percent' => $robus_percent,
            'carte_reloads' => $carte_reloads,
            'total_percent' => $total_percent,
            'calcul_valeurs' => $calcul_valeurs,
        ]);
    }


    public function searchWallet(Request $request)
    {
        $numWallet = $request->input('num_robus');

        $wallet = Wallet::where('wallet_id', $numWallet)->first();

        if ($wallet) {

            $userFirstName = $wallet->sidUser ? $wallet->sidUser->firstname : null;
            $userLastName = $wallet->sidUser ? $wallet->sidUser->lastname : null;

            return response()->json(['user_firstname' => $userFirstName, 'user_lastname' => $userLastName]);
        } else {
            return response()->json(['error' => 'Aucun wallet trouvé'], 404);
        }
    }

    public function transactions_pushs()
    {

        $deposit_cost = DepositCost::whereIsWeb(true)->first();

        $carte_percent = $deposit_cost->percent * 100;

        $robus_percent = $deposit_cost->percent_admin * 100;

        $carte_reloads = Provider::whereStatus(true)->where('name',  'PUSH')->first();

        $total_percent =  $carte_percent +  $robus_percent;

        $calcul_valeurs = (float) $total_percent  / 100;

        return view('pages.transactionWeb.browse_carte_pushs', [
            'appStatusResult' => $this->appStatusResult,
            'carte_percent' => $carte_percent,
            'robus_percent' => $robus_percent,
            'carte_reloads' => $carte_reloads,
            'total_percent' => $total_percent,
            'calcul_valeurs' => $calcul_valeurs,
        ]);
    }


    public function transactions_robus()
    {

        $transaction_cost = TransactionCost::whereId(42)->first();

        $transaction_percent = $transaction_cost->percent * 100;

        $robus_percent = $transaction_cost->percent_admin * 100;

        $total_percent =  $transaction_percent +  $robus_percent;

        $calcul_valeurs = (float) $total_percent  / 100;

        return view('pages.transactionWeb.browse_robus', [
            'appStatusResult' => $this->appStatusResult,
            'robus_percent' => $robus_percent,
            'total_percent' => $total_percent,
            'calcul_valeurs' => $calcul_valeurs,
            'transaction_percent' => $transaction_percent,
        ]);
    }

    public function transactions_transactions()
    {

        $transaction_cost = TransactionCost::whereId(43)->first();

        $phone_credit_cost = PhoneCreditCost::whereId(3)->first();

        $transaction_percent = $transaction_cost->percent * 100;

        $robus_percent = $transaction_cost->percent_admin * 100;

        $total_percent =  $transaction_percent +  $robus_percent;

        $calcul_valeurs = (float) $total_percent  / 100;

        // Phone Credits
        $phone_percent = $phone_credit_cost->percent * 100;

        $robus_percent_phone = $phone_credit_cost->percent_banque  * 100;

        $total_percent_phone =  $phone_percent +  $robus_percent_phone;

        $calcul_valeur_phone = (float) $total_percent_phone  / 100;

        $providerIds = [1, 3, 4];

        $providers = Provider::whereIn('id', $providerIds)
            ->orderBy('name', 'asc')
            ->get();

        return view('pages.transactionWeb.browse_deposits', [
            'appStatusResult' => $this->appStatusResult,
            'robus_percent' => $robus_percent,
            'total_percent' => $total_percent_phone,
            'calcul_valeurs' => $calcul_valeurs,
            'transaction_percent' => $transaction_percent,
            'providers' => $providers,
            'robus_percent_phone'  => $robus_percent_phone,
            'total_percent_phone'  => $total_percent_phone,
            'calcul_valeur_phone'  => $calcul_valeur_phone,
            'phone_percent'  => $phone_percent,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function transactions_inters()
    {


        $countries = Country::whereIsWeb(true)->where('indecative', '!=', null)
            ->orderBy('name', 'asc')
            ->get();

            return view('pages.transactionWeb.browse_inters', [
                'appStatusResult' => $this->appStatusResult,
                'countries' => $countries,
            ]);
    }

    public function searchCountries($countryId)
    {

        return $this->providersService->getProvidersWebByType(new Provider, $countryId, 8);
    }

    public function searchCountrieName($countryId)
    {
        return Country::whereId($countryId)->select('indecative')->first();
    }

    public function searchProviderPercent($countryId)
    {
        // return $this->percentRobus->getRobusPercentageInterWeb($countryId , $provider); 
        return $this->percentRobus->getRobusPercentageInterWeb($countryId);
    }

    public function transactions_xbets()
    {


        $xbet_cost = Onexbet::whereId(3)->first();

        $transaction_percent = $xbet_cost->percent * 100;

        $robus_percent = $xbet_cost->percent_admin * 100;

        $total_percent =  $transaction_percent +  $robus_percent;

        $calcul_valeurs = (float) $total_percent  / 100;


        return view('pages.transactionWeb.browse_xbets', [
            'appStatusResult' => $this->appStatusResult,
            'robus_percent' => $robus_percent,
            'total_percent' => $total_percent,
            'calcul_valeurs' => $calcul_valeurs,
            'transaction_percent' => $transaction_percent
        ]);
    }

    public function transactions_payments()
    {
        return view('pages.transactionWeb.browse_payments');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Respo
     */
    public function transactions_reload_store(Request $request)
    {

        $admin_civ = $this->validatedamountDays->adminCiv();

        try {


            $validator = Validator::make(

                $request->all(),
                [
                    'num_carte' => 'required',
                    'all_name' => 'required',
                    'provider' => 'required',
                    'my_phone' => 'required',
                    'amount' => 'required',
                    'num_digits' => 'required',
                ],
                [

                    'num_carte.required' => 'Votre Id de la carte est obligatoire',
                    'amount.required' => 'Votre montant est obligatoire',
                    'all_name.required' => 'Votre nom est obligatoire',
                    'provider.required' => 'Votre montant est obligatoire',
                    'my_phone.required' => 'Votre numero est obligatoire',
                    'num_digits.required' => 'Votre 4 derniers chiffre est obligatoire',
                ]
            );

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Champ requise')
                    ->withInput();
            }



            $percent_robus =  $this->percentRobus->getRobusPercentageReloadWeb();

            $reel_amount = $this->validatedamountDays->search_amount($request->amount);


            if ($percent_robus != null) {

                $amount_provider = $percent_robus->percent * $reel_amount;

                $amount_admin = $percent_robus->percent_admin  * $reel_amount;

                $amount_cost = (float) $amount_provider +  (float) $amount_admin;

                $amountReceved = $reel_amount - (float)$amount_cost;

                $walletReloadCard =  $this->reloadCardService->createRobusWalletReloadCard($request->amount, $request->provider, $percent_robus, $amount_cost, $request->num_carte, $request->all_name, $request->my_phone, $request->num_digits, $amountReceved);

                if ($walletReloadCard != null) {

                    $response = $this->reloadCardService->updateamountReloadCardReceveid($admin_civ, $amount_admin, $walletReloadCard);

                    event(new CreateWalletReloadCardVisEvent($walletReloadCard));

                    if ($response['success']) {

                        $this->notificationOrderCard->sendNotificationReloadCard($walletReloadCard);

                        return redirect('https://pay.wave.com/m/M_6A8Cl-gaA1Z7/c/ci/');
                    } else {

                        return back()
                            ->withErrors($validator)
                            ->with('color', 'danger')
                            ->with('status', 'Error du montant Admini !')
                            ->withInput();
                    }
                } else {

                    return back()
                        ->withErrors($validator)
                        ->with('color', 'danger')
                        ->with('status', 'Error de creation de transfert du montant!')
                        ->withInput();
                }
            } else {
                return back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Erreur de pourcentage')
                    ->withInput();
            }
        } catch (\Exception $exception) {

            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de la demande')
                ->withInput();
        }
    }

    public function transactions_push_store(Request $request)
    {
        $admin_civ = $this->validatedamountDays->adminCiv();

        try {



            $validator = Validator::make(

                $request->all(),
                [
                    'num_carte' => 'required',
                    'all_name' => 'required',
                    'my_phone' => 'required',
                    'amount' => 'required'
                ],
                [

                    'num_carte.required' => 'Votre Id de la carte est obligatoire',
                    'amount.required' => 'Votre montant est obligatoire',
                    'all_name.required' => 'Votre nom est obligatoire',
                    'provider.required' => 'Votre montant est obligatoire',
                    'my_phone.required' => 'Votre numero est obligatoire',
                ]
            );

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Champ requise')
                    ->withInput();
            }



            $percent_robus =  $this->percentRobus->getRobusPercentageMobileWeb();

            $reel_amount = $this->validatedamountDays->search_amount($request->amount);


            if ($percent_robus != null) {

                $amount_provider = $percent_robus->percent * $reel_amount;

                $amount_admin = $percent_robus->percent_admin  * $reel_amount;

                $amount_cost = (float) $amount_provider +  (float) $amount_admin;

                $amountReceved = $reel_amount - (float)$amount_cost;

                $walletTransaction =  $this->reloadCardService->createRobusWalletReloadPushCard($request->amount, $percent_robus, $amount_cost,  $request->num_carte, $request->all_name, $request->my_phone, $amountReceved);

                if ($walletTransaction != null) {

                    $response = $this->reloadCardService->updateamountReloadCardReceveid($admin_civ, $amount_admin, $walletTransaction);

                    event(new CreateWalletReloadCardVisEvent($walletTransaction));


                    if ($response['success']) {

                        $this->notificationOrderCard->sendNotificationTransactiobWeb($walletTransaction);

                        return redirect('https://pay.wave.com/m/M_6A8Cl-gaA1Z7/c/ci/');
                    } else {

                        return back()
                            ->withErrors($validator)
                            ->with('color', 'danger')
                            ->with('status', 'Error du montant Admin !')
                            ->withInput();
                    }
                } else {

                    return back()
                        ->withErrors($validator)
                        ->with('color', 'danger')
                        ->with('status', 'Error de creation de transfert du montant!')
                        ->withInput();
                }
            } else {
                return back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Erreur de pourcentage')
                    ->withInput();
            }
        } catch (\Exception $exception) {

            return back()
                ->with('color', 'danger')
                ->with('status', 'Erreur de la demande')
                ->withInput();
        }
    }

    public function transactions_xbets_store(Request $request)
    {

        $admin_civ = $this->validatedamountDays->adminCiv();

        try {


            $validator = Validator::make(

                $request->all(),
                [
                    'num_xbet' => 'required',
                    'all_name' => 'required',
                    'my_phone' => 'required',
                    'amount' => 'required'
                ],
                [

                    'num_xbet.required' => 'Votre Id de la carte est obligatoire',
                    'amount.required' => 'Votre montant est obligatoire',
                    'all_name.required' => 'Votre nom est obligatoire',
                    'my_phone.required' => 'Votre numero est obligatoire',
                ]
            );

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Champ requise')
                    ->withInput();
            }



            $percent_robus =  $this->percentRobus->getRobusPercentageOnexbetWeb();

            $reel_amount = $this->validatedamountDays->search_amount($request->amount);


            if ($percent_robus != null) {

                $amount_provider = $percent_robus->percent * $reel_amount;

                $amount_admin = $percent_robus->percent_admin  * $reel_amount;

                $amount_cost = (float) $amount_provider +  (float) $amount_admin;

                $amountReceved = $reel_amount - (float)$amount_cost;

                $walletTransaction =  $this->reloadCardService->createRobusWalletTransactionOneBet($request->amount, $request->num_xbet, $amount_cost, $request->all_name, $request->my_phone, $amountReceved);

                if ($walletTransaction != null) {

                    $response = $this->reloadCardService->updateamount1XbetReceveid($admin_civ, $amount_admin, $walletTransaction);

                    $msg = "Votre demande a bien été reçue. Vous allez être redirigé vers l'appli Wave pour le paiement de " . $request->amount  .  " FCFA";

                    event(new CreateWalletOneXbetVisEvent($walletTransaction));


                    if ($response['success']) {

                        $this->notificationOrderCard->sendNotificationTransactionXBetWeb($walletTransaction);

                        session()->flash('status', $msg);

                        return redirect('https://pay.wave.com/m/M_6A8Cl-gaA1Z7/c/ci/');
                    } else {

                        return back()
                            ->withErrors($validator)
                            ->with('color', 'danger')
                            ->with('status', 'Error du montant Admin !')
                            ->withInput();
                    }
                } else {

                    return back()
                        ->withErrors($validator)
                        ->with('color', 'danger')
                        ->with('status', 'Error de creation de transfert du montant!')
                        ->withInput();
                }
            } else {
                return back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Erreur de pourcentage')
                    ->withInput();
            }
        } catch (\Exception $exception) {

            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de la demande')
                ->withInput();
        }
    }

    public function transactions_deposits_store(Request $request)
    {
        $admin_civ = $this->validatedamountDays->adminCiv();

        try {


            $validator = Validator::make(

                $request->all(),
                [
                    'name_expeditor' => 'required',
                    'phone_benef' => 'required',
                    'my_phone' => 'required',
                    'amount' => 'required',
                    'provider' => 'required',
                    'operators' => 'required|in:1,2',
                ],
                [

                    'name_expeditor.required' => 'Nom de l\'expediteur est obligatoire',
                    'amount.required' => 'Votre montant est obligatoire',
                    'phone_benef.required' => 'Numero beneficiaire est obligatoire',
                    'my_phone.required' => 'Votre numero est obligatoire',
                    'provider.required' => 'Votre reseau est obligatoire',
                    'operators.required' => 'Votre operateurs est obligatoire',
                ]
            );

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Champ requise')
                    ->withInput();
            }



            $reel_amount = $this->validatedamountDays->search_amount($request->amount);


            $percentRobus = $request->operators == 1 ?
                $this->percentRobus->getRobusPercentageMobileWeb() :
                $this->percentRobus->getRobusPercentagePhoneCreditWeb();

            if (!$percentRobus) {
                return back()->with('color', 'danger')->with('status', 'Erreur de pourcentage')->withInput();
            }

            $amountProvider = $percentRobus->percent * $reel_amount;
            $amountAdmin = $request->operators == 1 ? $percentRobus->percent_admin * $reel_amount : $percentRobus->percent_banque * $reel_amount;
            $amountCost = $amountProvider + $amountAdmin;
            $amountReceived = $reel_amount - $amountCost;

            $walletTransaction = $request->operators == 1 ?
                $this->reloadCardService->createRobusWalletTransactionMoneys($percentRobus, $request->provider, $request->amount, $request->name_expeditor, $amountCost, $request->my_phone, $request->phone_benef, $amountReceived) :
                $this->reloadCardService->createRobusWalletTransactionPhoneWeb($request->amount, $request->provider, $amountCost, $request->phone_benef, $request->my_phone,  $request->name_expeditor, $amountReceived);

            if (!$walletTransaction) {
                return back()->with('color', 'danger')->with('status', 'Erreur de création de transfert du montant!')->withInput();
            }


            event(new CreateWalletTransfersVisEvent($walletTransaction));

            $response = $request->operators == 1 ?
                $this->reloadCardService->updateamountTransactionReceveid($admin_civ, $amountAdmin, $walletTransaction) :
                $this->reloadCardService->updateamountTransactionPhoneCreditReceveid($admin_civ, $amountAdmin, $walletTransaction);

            if (!$response['success']) {
                return back()->with('color', 'danger')->with('status', 'Erreur du montant Admin !')->withInput();
            }

            $notificationMethod = $request->operators == 1 ?
                'sendNotificationTransactiobWeb' :
                'sendNotificationTransactionPhoneCreditWeb';

            $this->notificationOrderCard->$notificationMethod($walletTransaction);
            return redirect('https://pay.wave.com/m/M_6A8Cl-gaA1Z7/c/ci/');
        } catch (\Exception $exception) {

            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de la demande')
                ->withInput();
        }
    }

    public function transactions_robus_store(Request $request)
    {
        $admin_civ = $this->validatedamountDays->adminCiv();

        try {


            $validator = Validator::make(

                $request->all(),
                [
                    'my_phone' => 'required',
                    'amount' => 'required',
                    'num_robus' => 'required',
                ],
                [

                    'amount.required' => 'Votre montant est obligatoire',
                    'my_phone.required' => 'Votre numero est obligatoire',
                    'num_robus.required' => 'Votre numero ROBUS est obligatoire',
                ]
            );

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Champ requise')
                    ->withInput();
            }



            $reel_amount = $this->validatedamountDays->search_amount($request->amount);

            $wallet_search = Wallet::where('wallet_id',  $request->num_robus)->first();

            $percentRobus =  $this->percentRobus->getRobusPercentageWeb();

            if (!$percentRobus) {
                return back()->with('color', 'danger')->with('status', 'Erreur de pourcentage')->withInput();
            }

            $amountProvider = $percentRobus->percent * $reel_amount;
            $amountAdmin =  $percentRobus->percent_admin * $reel_amount;
            $amountCost = $amountProvider + $amountAdmin;
            $amountReceived = $reel_amount - $amountCost;
            $amountDrawal =  $amountCost;
            $nameUser = $wallet_search->sidUser->firstname . ' ' .  $wallet_search->sidUser->lastname;

            $walletTransaction =  $this->reloadCardService->createRobusWalletTransactionWeb($request->amount, $percentRobus, $amountCost, $request->num_robus, $nameUser, $request->my_phone, $amountReceived, $amountDrawal);

            if (!$walletTransaction) {
                return back()->with('color', 'danger')->with('status', 'Erreur de création de transfert du montant!')->withInput();
            }

            event(new CreateWalletTransfersVisEvent($walletTransaction));

            $response =  $this->reloadCardService->updateamountTransactionReceveid($admin_civ, $amountAdmin, $walletTransaction);

            if (!$response['success']) {
                return back()->with('color', 'danger')->with('status', 'Erreur du montant Admin !')->withInput();
            }

            $this->notificationOrderCard->sendNotificationTransactiobWeb($walletTransaction);

            return redirect('https://pay.wave.com/m/M_6A8Cl-gaA1Z7/c/ci/');
        } catch (\Exception $exception) {

            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de la demande')
                ->withInput();
        }
    }

    public function transactions_inters_store(Request $request)
    {
        $admin_civ = $this->validatedamountDays->adminCiv();

        try {




            $validator = Validator::make(

                $request->all(),
                [
                    'phone_benef' => 'required',
                    'my_phone' => 'required',
                    'amount' => 'required',
                    'name_expeditor' => 'required',
                    'name_benef' => 'required',
                    'country_id' => 'required',
                    'provider' => 'required',
                ],
                [

                    'amount.required' => 'Votre montant est obligatoire',
                    'phone_benef.required' => 'Votre numero est obligatoire',
                    'my_phone.required' => 'Votre numero est obligatoire',
                    'name_expeditor.required' => 'Nom expediteur',
                    'name_benef.required' => 'Nom beneficiare',
                    'country_id.required' => 'Votre pas est obligatoire',
                    'provider.required' => 'Provider est obligatoire',
                ]
            );

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Champ requise')
                    ->withInput();
            }

            $country_robus = Country::whereId($request->country_id)->first();

            $reel_amount = $this->validatedamountDays->search_amount($request->amount);

            $percentRobus = $this->percentRobus->getRobusPercentageInterWeb($request->country_id);

            if (!$percentRobus) {
                return back()->with('color', 'danger')->with('status', 'Erreur de pourcentage')->withInput();
            }

            $amountProvider = $percentRobus->percent * $reel_amount;
            $amountAdmin =  $percentRobus->percent_admin * $reel_amount;
            $amountCost = $amountProvider + $amountAdmin;
            $amountReceived = $reel_amount - $amountCost;

            $walletInter =  $this->reloadCardService->createMobileWalletInter($request->amount, $percentRobus, $country_robus, $request->provider, $request->my_phone, $request->phone_benef,  $request->name_expeditor, $request->name_benef, $amountCost, $amountReceived);

            if (!$walletInter) {
                return back()->with('color', 'danger')->with('status', 'Erreur de création de transfert du montant!')->withInput();
            }

            event(new CreateWalletInterVisEvent($walletInter));

            $response =  $this->reloadCardService->updateamountInterReceveid($admin_civ, $amountAdmin, $walletInter);

            if (!$response['success']) {
                return back()->with('color', 'danger')->with('status', 'Erreur du montant Admin !')->withInput();
            }

            $this->notificationOrderCard->sendNotificationTransactionInterWeb($walletInter);

            return redirect('https://pay.wave.com/m/M_6A8Cl-gaA1Z7/c/ci/');
        } catch (\Exception $exception) {

            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de la demande')
                ->withInput();
        }
    }
}
