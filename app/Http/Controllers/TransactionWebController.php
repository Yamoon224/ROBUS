<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\NotificationWebService;
use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\TransactionWebService;
use App\Http\Services\ValidateDailyLimitService;
use App\Models\Costs\DepositCost;
use App\Models\Costs\PhoneCreditCost;
use App\Models\Costs\TransactionCost;
use App\Models\Custumers\Country;
use App\Models\Onexbet;
use App\Models\Providers\Provider;
use App\Models\Wallets\Wallet;
use App\Repositories\ReloadCardRepository;
use Illuminate\Http\Request;
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

    public function __construct(
        ReloadCardRepository $reload_cardReposi,
        NotificationWebService $notificationOrderCard,
        TransactionWebService $reloadCardService,
        PercentRobusAuthService $percentRobus,
        ValidateDailyLimitService $validatedamountDays,

    ) {
        $this->reloadCardRepository = $reload_cardReposi;
        $this->notificationOrderCard = $notificationOrderCard;
        $this->reloadCardService = $reloadCardService;
        $this->percentRobus = $percentRobus;
        $this->validatedamountDays = $validatedamountDays;
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
            ->orderBy('name', 'asc')->get();

        $total_percent =  $carte_percent +  $robus_percent;

        $calcul_valeurs = (float) $total_percent  / 100;

        return view('pages.transactionWeb.browse_carte_reloads', compact('carte_percent', 'robus_percent', 'carte_reloads', 'total_percent', 'calcul_valeurs'));
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

        return view('pages.transactionWeb.browse_carte_pushs', compact('carte_percent', 'robus_percent', 'carte_reloads', 'total_percent', 'calcul_valeurs'));
    }


    public function transactions_robus()
    {

        $transaction_cost = TransactionCost::whereId(42)->first();

        $transaction_percent = $transaction_cost->percent * 100;

        $robus_percent = $transaction_cost->percent_admin * 100;

        $total_percent =  $transaction_percent +  $robus_percent;

        $calcul_valeurs = (float) $total_percent  / 100;


        return view('pages.transactionWeb.browse_robus', compact('transaction_percent', 'robus_percent', 'total_percent', 'calcul_valeurs'));
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

        return view('pages.transactionWeb.browse_deposits', compact('transaction_percent', 'robus_percent', 'total_percent', 'calcul_valeurs', 'providers', 'robus_percent_phone', 'total_percent_phone', 'calcul_valeur_phone', 'phone_percent'));
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
    

        return view('pages.transactionWeb.browse_inters' , compact('countries'));
    }

    public function transactions_xbets()
    {


        $xbet_cost = Onexbet::whereId(3)->first();

        $transaction_percent = $xbet_cost->percent * 100;

        $robus_percent = $xbet_cost->percent_admin * 100;

        $total_percent =  $transaction_percent +  $robus_percent;

        $calcul_valeurs = (float) $total_percent  / 100;

        return view('pages.transactionWeb.browse_xbets', compact('transaction_percent', 'robus_percent', 'total_percent', 'calcul_valeurs'));
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

                    $msg = "Votre demande a crée avec succès";
                    $color = 'success';

                    if ($response['success']) {

                        $this->notificationOrderCard->sendNotificationReloadCard($walletReloadCard);

                        return redirect('https://pay.wave.com/m/M_6A8Cl-gaA1Z7/c/ci/')->with('color', $color)->with('status', $msg);
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

                $walletTransaction =  $this->reloadCardService->createRobusWalletTransactionMoneys($percent_robus, $request->provider, $request->amount, $request->all_name, $amount_cost, $request->my_phone,  $request->phone_benef, $amountReceved);

                if ($walletTransaction != null) {

                    $response = $this->reloadCardService->updateamountTransactionReceveid($admin_civ, $amount_admin, $walletTransaction);

                    $msg = "Votre demande a crée avec succès";
                    $color = 'success';

                    if ($response['success']) {

                        $this->notificationOrderCard->sendNotificationTransactiobWeb($walletTransaction);

                        return redirect('https://pay.wave.com/m/M_6A8Cl-gaA1Z7/c/ci/')->with('color', $color)->with('status', $msg);
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

                    $msg = "Votre demande a crée avec succès";
                    $color = 'success';

                    if ($response['success']) {

                        $this->notificationOrderCard->sendNotificationTransactionXBetWeb($walletTransaction);

                        return redirect('https://pay.wave.com/m/M_6A8Cl-gaA1Z7/c/ci/')->with('color', $color)->with('status', $msg);
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
            return redirect('https://pay.wave.com/m/M_6A8Cl-gaA1Z7/c/ci/')->with('color', 'success')->with('status', 'Votre demande a été créée avec succès');
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

            $response =  $this->reloadCardService->updateamountTransactionReceveid($admin_civ, $amountAdmin, $walletTransaction);

            if (!$response['success']) {
                return back()->with('color', 'danger')->with('status', 'Erreur du montant Admin !')->withInput();
            }

            $this->notificationOrderCard->sendNotificationTransactiobWeb($walletTransaction);

            return redirect('https://pay.wave.com/m/M_6A8Cl-gaA1Z7/c/ci/')->with('color', 'success')->with('status', 'Votre demande a été créée avec succès');
        } catch (\Exception $exception) {

            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de la demande')
                ->withInput();
        }
    }


}
