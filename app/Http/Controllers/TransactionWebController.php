<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Providers\Provider;
use App\Models\Costs\DepositCost;
use App\Http\Controllers\Controller;
use App\Http\Services\TransactionWebService;
use Illuminate\Support\Facades\Validator;
use App\Repositories\ReloadCardRepository;
use App\Http\Services\NotificationWebService;
use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\ValidateDailyLimitService;


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

    public function transactions_pushs()
    {

        return view('pages.transactionWeb.browse_carte_pushs');
    }


    public function transactions_robus()
    {

        return view('pages.transactionWeb.browse_robus');
    }

    public function transactions_deposits()
    {

        return view('pages.transactionWeb.browse_deposits');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function transactions_inters()
    {
        return view('pages.transactionWeb.browse_inters');
    }

    public function transactions_xbets()
    {
        return view('pages.transactionWeb.browse_xbets');
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

        // try {


            $validator = Validator::make(

                $request->all(),
                [
                    'num_carte' => 'required',
                    'all_name' => 'required',
                    'provider' => 'required',
                    'my_phone' => 'required',
                    'amount' => 'required',
                ],
                [

                    'num_carte.required' => 'Votre Id de la carte est obligatoire',
                    'amount.required' => 'Votre montant est obligatoire',
                    'all_name.required' => 'Votre nom est obligatoire',
                    'provider.required' => 'Votre montant est obligatoire',
                    'my_phone.required' => 'Votre numero est obligatoire'
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

                $walletReloadCard =  $this->reloadCardService->createRobusWalletReloadCard($request->amount, $request->provider, $percent_robus, $amount_cost, $request->num_carte, $request->all_name, $request->my_phone, $amountReceved);

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
        // } catch (\Exception $exception) {

        //     return back()
        //         ->withErrors($validator)
        //         ->with('color', 'danger')
        //         ->with('status', 'Erreur de la demande')
        //         ->withInput();
        // }
    }
}
