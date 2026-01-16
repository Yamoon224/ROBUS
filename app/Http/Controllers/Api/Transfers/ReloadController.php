<?php

namespace App\Http\Controllers\Api\Transfers;

use App\Models\Sid\SidUser;
use Illuminate\Http\Request;
use App\Models\Banks\BankCard;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Events\AdminNotifEvent;
use App\Models\Costs\ReloadCost;
use App\Events\CreateReloadEvent;
use App\Models\Providers\Provider;
use App\Models\Wallets\WalletBank;
use Illuminate\Support\Facades\DB;
use App\Models\Wallets\WalletInter;
use App\Http\Controllers\Controller;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Services\DeletedService;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Models\Invoices\InvoiceReload;
use App\Models\Payments\PaymentReload;
use App\Repositories\ReloadRepository;
use App\Notifications\UserNotification;
use App\Http\Services\ReloadCardService;
use App\Notifications\AdminNotification;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Admin\MailController;
use App\Http\Resources\ReloadSingleCollection;
use App\Http\Services\NotificationSendService;
use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\ValidateDailyLimitService;

class ReloadController extends Controller
{

    /** @var  ReloadRepository */
    protected $reloadRepository;
    protected $deletedService;
    protected $percentRobus;
    protected $validatedamountDays;
    protected $notificationOrderCard;
    protected $reloadCardService;
    private $authService;

    public function __construct(
        ReloadRepository $reloadReposi,
        DeletedService $deletedService,
        PercentRobusAuthService $percentRobus,
        ValidateDailyLimitService $validatedamountDays,
        NotificationSendService $notificationOrderCard,
        ReloadCardService $reloadCardService
    ) {
        $this->reloadRepository = $reloadReposi;
        $this->deletedService = $deletedService;
        $this->percentRobus = $percentRobus;
        $this->validatedamountDays = $validatedamountDays;
        $this->notificationOrderCard = $notificationOrderCard;
        $this->reloadCardService = $reloadCardService;
    }

    /**
     * @OA\POST(
     *      path="/user/reloads/store",
     *      tags={"Reload User"},
     *      summary="Create new reload",
     *      description="Faire des transactions bancaire  depuis son dashbord
     *         Pour les differents providers dans notre Robus. 
     *        Selon les id des providers nous faisons nos verifications.
     *        si  Provider_id = 9 : nous somme dans le cas d'un compte robus ou le client doit saisie le numero  8 chiffres de Robus.
     *        Si provider_id =  4 (Orange)ou 3(MOOV)  ou 1(MTN) nous verifions si le clients est en CIV alors nous faisons la verification selon les numero prefix des differents operateurs
     *        Sinon il sais le numero de son pays.
     *        Egalement nous avons tenier compte du faite que le client ne peux envoyer que aux autre dans son pays dans le cas de transaction car c'est pas l'internationnal.
     *        Le cas ou c'est un numero, le numero doit etre pour un client et qui est de robus.
     *        Le clients ne peux s'auto envoyer de l'argent donc se recharger.
     *        le clients doit avoir le moment necessaire avec la commissions pour faire des transactions dans le cas contraire un warring.    
     *         ",
     *  @OA\Parameter(
     *         in="path",
     *         name="provider",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     * @OA\Response(
     *          response=200,
     *          description="success Pour un exemple sur une transaction en CIV avec un numero robus",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="provider", type="string", example="Robus"),
     *              @OA\Property(property="number compte", type="string", example="CIAZE3SD"),
     *              @OA\Property(property="description", type="string", example="Demande de transaction"),
     *              @OA\Property(property="amount", type="number", example="1000"),
     *              @OA\Property(property="phone", type="number", example="0545657676"),
     *              @OA\Property(property="status", type="string", example="Pending"),
     *              @OA\Property(property="update_at", type="string", example="2022-08-11T09:25:53.000000Z")
     *          )
     *     ),
     *  @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="provider",
     *                          type="number"
     *                      ),
     *                    @OA\Property(
     *                          property="num_wallet",
     *                          type="string"
     *                      ),
     *                    @OA\Property(
     *                          property="amount",
     *                          type="number"
     *                      ),
     *                 ),
     * 
     *                example = {
     * 
     *                     "provider":"9",
     *                     "amount":"1000",
     *                     "numero compte" : "CIAZE3SD", 
     *                     "description":"Une demande de transaction",
     *
     *                }
     *                
     *             )
     *         )
     *      ),
     * @OA\Response(
     *          response=405,
     *          description="Verify your number."
     *      ),
     * @OA\Response(
     *          response=341,
     *          description="Check your balance."
     *      ),
     *   @OA\Response(
     *          response=333,
     *          description="virements does not match."
     *      ),
     * @OA\Response(
     *      response=433,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     * @OA\Response(
     *      response=406,
     *      description="Is not Custumers"
     *   )
     *  ),
     */
    public function create_reload(Request $request)
    {
        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf("%0.9s", str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf("%0.8s", str_shuffle($rt . $tr));
        $wallet_user = Auth::guard('api')->user();
        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $wallet_user->country->id)->get();
        $amount_days = $this->getWalletBanksCeilingProperty() + $this->getWalletDepositsCeilingProperty()
            + $this->getWalletIntersCeilingProperty() + $this->getWalletPaymentsCeilingProperty()
            + $this->getWalletTransactionsCeilingProperty() + $this->getWalletPhoneCreditsCeilingProperty()
            + $this->getWalletReloadsCeilingProperty() + $this->getWalletBankCardCeilingProperty();


        if ($request->provider == 9) {

            $validator = Validator::make(
                $request->all(),
                [
                    "account_wallet" => 'required|max:8',
                    "amount" => 'required'
                ],
                [

                    'account_wallet.required' => 'Votre numero est obligatoire',
                    'amount.required' => 'Votre montant est obligatoire',
                ]
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Account and amount is required or the 10 digit number',
                        'data' => $validator->errors()
                    ],
                    333
                );
            }

            $wallet_search = Wallet::where('wallet_id', '=',  $request->account_wallet)
                ->where('country_id',  $wallet_user->country->id)
                ->first();

            $percent_robus = ReloadCost::where('status', true)->first();

            if ($wallet_search == null || $wallet_search->sidUser->is_custumers == true  || $wallet_search->sidUser->status == false || $request->account_wallet ==  $wallet_user->wallet->wallet_id) {

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'This number is not ROBUS Or is your number',
                        'data' => $validator->errors()
                    ],
                    333

                );
            } else {

                $solde_Wallet =   $wallet_user->wallet->amount;

                $valeur_ceiling  =  $wallet_user->silver_ceiling;

                //  Verifier transfers Som days

                if ($amount_days >=  $valeur_ceiling) {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'Votre montant journier est atteint !',
                        ],
                        333
                    );
                } else {

                    $valAmount = $request->amount + ($percent_robus->percent * $request->amount);

                    if ($valAmount > $solde_Wallet) {


                        return response()->json(
                            [
                                'success' => false,
                                'message' => 'Check your balance.',
                            ],
                            341
                        );
                    } else {

                        $amount_solde =  $wallet_user->wallet->amount -  $valAmount;

                        $walletReload = $this->reloadRepository->create([
                            'wallet_id' =>  $wallet_user->wallet->id,
                            'amount_solde' => $amount_solde ?? null,
                            'amount' => $request->amount,
                            'description'  => 'Demande de rechargement de compte',
                            'reload_cost_id' => 1,
                            'created_by' => Auth::guard('api')->user()->id,
                            'provider_id'  => $request->provider,
                            'account_wallet'  => $request->account_wallet,
                            'phone'  =>  $request->phone ?? null,
                            'status'  => "SUCCESS"
                        ]);

                        $commission =  ($walletReload->amount);

                        $wallet_user->wallet->update([
                            'reload_by' => Auth::guard('api')->user()->firstname . ' ' . Auth::guard('api')->user()->lastname,
                            'sid_user_relod' => Auth::guard('api')->user()->id,
                            'amount' => DB::raw('amount-'  .  $commission),

                        ]);

                        // Send in compte Client
                        $wallet_search->update([
                            'reload_by' => Auth::guard('api')->user()->firstname . ' ' . Auth::guard('api')->user()->lastname,
                            'sid_user_relod' => Auth::guard('api')->user()->id,
                            'amount' => DB::raw('amount+'  . $commission),
                        ]);

                        $paymentRealod = PaymentReload::create([
                            'wallet_reload_id' => $walletReload->id,
                            'sid_user_id' => Auth::guard('api')->user()->id,
                            'transaction_id' => 'RB_' . $walletReload->provider->name . '_' . $transactionCode,
                            'operator_transaction_name' =>  $walletReload->provider->name,
                            'trans_comp_id' => 'RB_' . $transactionCompiCode,
                            'pay_amount' =>  $walletReload->amount,
                            'fee_amount' =>   $walletReload->reloadCost->percent,
                            'received_amount' => ($walletReload->amount),
                            'status'  =>    $walletReload->status,
                        ]);

                        InvoiceReload::create([
                            'title' => "Rechargement de compte",
                            'wallet_reload_id' => $walletReload->id,
                            'payment_reload_id' =>  $paymentRealod->id,
                            'company_id' => Auth::guard('api')->user()->company->id,
                            'description' => $walletReload->description,
                            'status'  =>    $walletReload->status,
                            'created_by'  =>   Auth::guard('api')->user()->id,
                        ]);


                        if (
                            $wallet_user->country->code == "CI"
                        ) {

                            $message = 'Vous avez effectué un transafers de ' . $walletReload->amount .  $wallet_user->wallet->currencie->code .  'par '
                                . $walletReload->provider->name  .  ' a '
                                . $walletReload->created_at . ' votre demande est en-cours de validation ' . 'Robus vous remercie';

                            $encode = substr(Auth::guard('api')->user()->phone,  -10);

                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                        }

                        $msg = 'Demande en cours de validation';
                        $msg_title = 'Achat d\'UV';
                        $wallet_search->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                        $notifs = $wallet_search->sidUser->unreadNotifications;
                        $wallet_user->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                        $notifs = $wallet_user->unreadNotifications;
                        event(new CreateReloadEvent($paymentRealod));
                        Notification::send($admins, new AdminNotification('Demande de rechargement UV de ' . ' ' . $walletReload->wallet->sidUser->firstname));

                        $admin = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $wallet_user->country->id)->get();

                        if ($admin->count() > 0) {

                            foreach ($admin  as $user) {

                                $admin_single = SidUser::find($user->id);

                                $notifs = $admin_single->unreadNotifications;

                                event(new AdminNotifEvent($notifs));
                            }
                            if ($wallet_user->email != null) {

                                $notify = new MailController;
                                $notify->newReloadUV($walletReload);
                            }
                            return response()->json([
                                'success' => true,
                                'data' => [
                                    'id'      => $walletReload->id,
                                    'amount' => $walletReload->amount,
                                    'account_wallet' => $walletReload->account_wallet,
                                    'ref' => $paymentRealod->transaction_id,
                                    'status' => $walletReload->status,
                                ],
                                'message' => ('Reload has been created successfully.')
                            ], 200);
                        } else {

                            if ($wallet_user->email != null) {

                                $notify = new MailController;
                                $notify->newReloadUV($walletReload);
                            }
                            return response()->json([
                                'success' => true,
                                'data' => [
                                    'id'      => $walletReload->id,
                                    'amount' => $walletReload->amount,
                                    'account_wallet' => $walletReload->account_wallet,
                                    'ref' => $paymentRealod->transaction_id,
                                    'status' => $walletReload->status,
                                ],
                                'message' => ('Reload has been created successfully.')
                            ], 200);
                        }
                    }
                }
            }
        } else if ($request->provider == 4 || $request->provider == 3 || $request->provider == 1) {


            if ($wallet_user->country->code == "CI") {

                $validator = Validator::make(
                    $request->all(),
                    [
                        "phone" => 'required|max:10',
                        "amount" => 'required'
                    ],
                    [

                        'phone.required' => 'Votre numero est obligatoire',
                        'amount.required' => 'Votre montant est obligatoire',
                    ]
                );

                if ($validator->fails()) {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'phone and amount is required',
                            'data' => $validator->errors()
                        ],
                        405
                    );
                }
                // $admin_search = SidUser::where('phone',  $wallet_user->country->indecative .  $request->phone)
                //     ->where('status', true)
                //     ->where('is_custumers', true)
                //     ->where('country_id',  $wallet_user->country->id)
                //     ->first();

                $encode = substr($request->phone, 0, 2);

                if ($request->provider == 1) {
                    //   Mtn
                    if ($encode != 05) {

                        return response()->json([
                            'success' => false,
                            'message' => 'The number must start with 05',
                        ], 405);
                    }


                    if ($request->phone ==  $wallet_user->phone) {

                        return response()->json(
                            [
                                'success' => false,
                                'message' => 'The number does not exist with us',
                            ],
                            405
                        );
                    }

                    // if ($admin_search->is_custumers == true) {

                    $percent_robus = ReloadCost::where('status', true)->first();
                    $valeur_ceiling  =  $wallet_user->silver_ceiling;

                    //  Verifier transfers Som days

                    if ($amount_days >= $valeur_ceiling) {
                        return response()->json(
                            [
                                'success' => false,
                                'message' => 'Votre montant journier est atteint !',
                            ],
                            333
                        );
                    } else {


                        $valAmount = $request->amount + ($percent_robus->percent * $request->amount);

                        if ($valAmount > $wallet_user->wallet->amount) {
                            return response()->json(
                                [
                                    'success' => false,
                                    'message' => 'Check your balance.',
                                ],
                                341
                            );
                        } else {

                            $amount_solde =  $wallet_user->wallet->amount -  $valAmount;

                            $walletReload =  $this->reloadRepository->create([
                                'amount_solde' => $amount_solde ?? null,
                                'wallet_id' =>   $wallet_user->wallet->id,
                                'amount' => $request->amount,
                                'description'  =>  'Demande de rechargement de compte',
                                'reload_cost_id' => 1,
                                'created_by' => Auth::guard('api')->user()->id,
                                'provider_id'  => $request->provider,
                                'account_wallet'  => $request->account_wallet ?? null,
                                'phone'  =>  Auth::guard('api')->user()->country->indecative . $request->phone ?? null,
                                'status'  => "PENDING"
                            ]);

                            $commission =  (($walletReload->amount)  + ($walletReload->amount * $walletReload->reloadCost->percent));

                            $wallet_user->wallet->update([
                                'amount' => DB::raw('amount-'  .  $commission),

                            ]);



                            if ($wallet_user->country->code == "CI") {

                                $message = 'Vous avez effectué un transafers de ' . $walletReload->amount .  $wallet_user->wallet->currencie->code .  'par '
                                    . $walletReload->provider->name  .  ' a '
                                    . $walletReload->created_at . ' votre demande est en-cours de validation ' . 'Robus vous remercie';

                                $encode = substr(Auth::guard('api')->user()->phone,  -10);

                                Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                            }

                            $msg = 'Demande en cours de validation';
                            $msg_title = 'Achat d\'UV';
                            // $admin_search->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                            // $notifs = $admin_search->unreadNotifications;
                            $wallet_user->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                            $notifs = $wallet_user->unreadNotifications;
                            event(new CreateReloadEvent($walletReload));
                            Notification::send($admins, new AdminNotification('Demande de rechargement UV de ' . ' ' . $walletReload->wallet->sidUser->firstname));

                            $admin = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $wallet_user->country->id)->get();

                            if ($admin->count() > 0) {

                                foreach ($admin  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }
                                if ($wallet_user->email != null) {

                                    $notify = new MailController;
                                    $notify->newReloadUV($walletReload);
                                }
                                return response()->json([
                                    'success' => true,
                                    'data' => [
                                        'id'      => $walletReload->id,
                                        'amount' => $walletReload->amount,
                                        'account_wallet' => $walletReload->account_wallet,
                                        'percent' => $walletReload->reloadCost->percent,
                                        'phone' => $walletReload->phone,
                                        'status' => $walletReload->status,
                                    ],
                                    'message' => ('Reload has been created successfully.')
                                ], 200);
                            } else {

                                if ($wallet_user->email != null) {

                                    $notify = new MailController;
                                    $notify->newReloadUV($walletReload);
                                }
                                return response()->json([
                                    'success' => true,
                                    'data' => [
                                        'id'      => $walletReload->id,
                                        'amount' => $walletReload->amount,
                                        'account_wallet' => $walletReload->account_wallet,
                                        'percent' => $walletReload->reloadCost->percent,
                                        'phone' => $walletReload->phone,
                                        'status' => $walletReload->status,
                                    ],
                                    'message' => ('Reload has been created successfully.')
                                ], 200);
                            }
                        }
                    }
                } else if ($request->provider  == 3) {
                    // Moov

                    if ($encode != 01) {

                        return response()->json([
                            'success' => false,
                            'message' => 'The number must start with 01',
                        ], 405);
                    }

                    if ($request->phone ==  $wallet_user->phone) {

                        return response()->json(
                            [
                                'success' => false,
                                'message' => 'The number does not exist with us',
                            ],
                            405
                        );
                    }

                    // if ($admin_search->is_custumers == true) {

                    $percent_robus = ReloadCost::where('status', true)->first();

                    $valeur_ceiling  =  $wallet_user->silver_ceiling;

                    //  Verifier transfers Som days

                    if ($amount_days >=  $valeur_ceiling) {
                        return response()->json(
                            [
                                'success' => false,
                                'message' => 'Votre montant journier est atteint !',
                            ],
                            333
                        );
                    } else {


                        $valAmount = $request->amount + ($percent_robus->percent * $request->amount);

                        if ($valAmount > $wallet_user->wallet->amount) {
                            return response()->json(
                                [
                                    'success' => false,
                                    'message' => 'Check your balance.',
                                ],
                                341
                            );
                        } else {

                            $amount_solde =  $wallet_user->wallet->amount -  $valAmount;

                            $walletReload =  $this->reloadRepository->create([
                                'amount_solde' => $amount_solde ?? null,
                                'wallet_id' =>   $wallet_user->wallet->id,
                                'amount' => $request->amount,
                                'description'  =>  'Demande de rechargement de compte',
                                'reload_cost_id' => 1,
                                'created_by' => Auth::guard('api')->user()->id,
                                'provider_id'  => $request->provider,
                                'account_wallet'  => $request->account_wallet  ?? null,
                                'phone'  =>  Auth::guard('api')->user()->country->indecative . $request->phone ?? null,
                                'status'  => "PENDING"
                            ]);

                            $commission =  (($walletReload->amount)  + ($walletReload->amount * $walletReload->reloadCost->percent));

                            $wallet_user->wallet->update([
                                'amount' => DB::raw('amount-'  .  $commission),

                            ]);


                            if ($wallet_user->country->code == "CI") {

                                $message = 'Vous avez effectué un transafers de ' . $walletReload->amount .  $wallet_user->wallet->currencie->code .  'par '
                                    . $walletReload->provider->name  .  ' a '
                                    . $walletReload->created_at . ' votre demande est en-cours de validation ' . 'Robus vous remercie';

                                $encode = substr(Auth::guard('api')->user()->phone,  -10);

                                Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                            }

                            $msg = 'Demande en cours de validation';
                            $msg_title = 'Achat d\'UV';
                            // $admin_search->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                            // $notifs = $admin_search->unreadNotifications;
                            $wallet_user->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                            $notifs = $wallet_user->unreadNotifications;
                            event(new CreateReloadEvent($walletReload));
                            Notification::send($admins, new AdminNotification('Demande de rechargement UV de ' . ' ' . $walletReload->wallet->sidUser->firstname));
                            $admin = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $wallet_user->country->id)->get();

                            if ($admin->count() > 0) {

                                foreach ($admin  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }
                                if ($wallet_user->email != null) {

                                    $notify = new MailController;
                                    $notify->newReloadUV($walletReload);
                                }
                                return response()->json([
                                    'success' => true,
                                    'data' => [
                                        'id'      => $walletReload->id,
                                        'amount' => $walletReload->amount,
                                        'account_wallet' => $walletReload->account_wallet,
                                        'percent' => $walletReload->reloadCost->percent,
                                        'phone' => $walletReload->phone,
                                        'status' => $walletReload->status,
                                    ],
                                    'message' => ('Reload has been created successfully.')
                                ], 200);
                            } else {

                                if ($wallet_user->email != null) {

                                    $notify = new MailController;
                                    $notify->newReloadUV($walletReload);
                                }
                                return response()->json([
                                    'success' => true,
                                    'data' => [
                                        'id'      => $walletReload->id,
                                        'amount' => $walletReload->amount,
                                        'account_wallet' => $walletReload->account_wallet,
                                        'percent' => $walletReload->reloadCost->percent,
                                        'phone' => $walletReload->phone,
                                        'status' => $walletReload->status,
                                    ],
                                    'message' => ('Reload has been created successfully.')
                                ], 200);
                            }
                        }
                    }
                } else if ($request->provider == 4) {
                    // Orange
                    if ($encode != 07) {

                        return response()->json(
                            [
                                'success' => false,
                                'message' => 'The number must start with 07',
                            ],
                            405
                        );
                    }

                    if ($request->phone ==  $wallet_user->phone) {

                        return response()->json(
                            [
                                'success' => false,
                                'message' => 'The number does not exist with us',
                            ],
                            405
                        );
                    }

                    // if ($admin_search->is_custumers == true) {

                    $percent_robus = ReloadCost::where('status', true)->first();

                    $valeur_ceiling  =  $wallet_user->silver_ceiling;

                    //  Verifier transfers Som days

                    if ($amount_days >=  $valeur_ceiling) {
                        return response()->json(
                            [
                                'success' => false,
                                'message' => 'Votre montant journier est atteint !',
                            ],
                            333
                        );
                    } else {


                        $valAmount = $request->amount + ($percent_robus->percent * $request->amount);

                        if ($valAmount > $wallet_user->wallet->amount) {
                            return response()->json(
                                [
                                    'success' => false,
                                    'message' => 'Check your balance.',
                                ],
                                341
                            );
                        } else {

                            $amount_solde =  $wallet_user->wallet->amount -  $valAmount;

                            $walletReload =  $this->reloadRepository->create([
                                'amount_solde' => $amount_solde ?? null,
                                'wallet_id' =>   $wallet_user->wallet->id,
                                'amount' => $request->amount,
                                'description'  =>  'Demande de rechargement de compte',
                                'reload_cost_id' => 1,
                                'created_by' => Auth::guard('api')->user()->id,
                                'provider_id'  =>  $request->provider,
                                'account_wallet'  => $request->account_wallet  ?? null,
                                'phone'  =>  Auth::guard('api')->user()->country->indecative . $request->phone ?? null,
                                'status'  => "PENDING"
                            ]);

                            $commission =  (($walletReload->amount)  + ($walletReload->amount * $walletReload->reloadCost->percent));

                            $wallet_user->wallet->update([
                                'amount' => DB::raw('amount-'  .  $commission),

                            ]);


                            if ($wallet_user->country->code == "CI") {

                                $message = 'Vous avez effectué un transafers de ' . $walletReload->amount .  $wallet_user->wallet->currencie->code .  'par '
                                    . $walletReload->provider->name  .  ' a '
                                    . $walletReload->created_at . ' votre demande est en-cours de validation ' . 'Robus vous remercie';

                                $encode = substr(Auth::guard('api')->user()->phone,  -10);

                                Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                            }

                            $msg = 'Demande en cours de validation';
                            $msg_title = 'Achat d\'UV';
                            // $admin_search->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                            // $notifs = $admin_search->unreadNotifications;
                            $wallet_user->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                            $notifs = $wallet_user->unreadNotifications;
                            event(new CreateReloadEvent($walletReload));
                            Notification::send($admins, new AdminNotification('Demande de rechargement UV de ' . ' ' . $walletReload->wallet->sidUser->firstname));
                            $admin = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $wallet_user->country->id)->get();

                            if ($admin->count() > 0) {

                                foreach ($admin  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }
                                if ($wallet_user->email != null) {

                                    $notify = new MailController;
                                    $notify->newReloadUV($walletReload);
                                }
                                return response()->json([
                                    'success' => true,
                                    'data' => [
                                        'id'      => $walletReload->id,
                                        'amount' => $walletReload->amount,
                                        'account_wallet' => $walletReload->account_wallet,
                                        'percent' => $walletReload->reloadCost->percent,
                                        'phone' => $walletReload->phone,
                                        'status' => $walletReload->status,
                                    ],
                                    'message' => ('Reload has been created successfully.')
                                ], 200);
                            } else {

                                if ($wallet_user->email != null) {

                                    $notify = new MailController;
                                    $notify->newReloadUV($walletReload);
                                }
                                return response()->json([
                                    'success' => true,
                                    'data' => [
                                        'id'      => $walletReload->id,
                                        'amount' => $walletReload->amount,
                                        'account_wallet' => $walletReload->account_wallet,
                                        'percent' => $walletReload->reloadCost->percent,
                                        'phone' => $walletReload->phone,
                                        'status' => $walletReload->status,
                                    ],
                                    'message' => ('Reload has been created successfully.')
                                ], 200);
                            }
                        }
                    }
                }
            } else {

                $validator = Validator::make(
                    $request->all(),
                    [
                        "phone" => 'required',
                        "amount" => 'required'
                    ],
                    [

                        'phone.required' => 'Votre numero est obligatoire',
                        'amount.required' => 'Votre montant est obligatoire',
                    ]
                );

                if ($validator->fails()) {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'Account and amount is required',
                            'data' => $validator->errors()
                        ],
                        333
                    );
                }


                if ($request->phone ==  $wallet_user->phone) {

                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'The number does not exist with us',
                        ],
                        405
                    );
                }

                // if ($admin_search->is_custumers == true) {

                $percent_robus = ReloadCost::where('status', true)->first();

                $valeur_ceiling  =  $wallet_user->silver_ceiling;

                //  Verifier transfers Som days

                if ($amount_days >=  $valeur_ceiling) {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'Votre montant journier est atteint !',
                        ],
                        333
                    );
                } else {

                    $valAmount = $request->amount + ($percent_robus->percent * $request->amount);

                    if ($valAmount > $wallet_user->wallet->amount) {
                        return response()->json(
                            [
                                'success' => false,
                                'message' => 'Check your balance.',
                            ],
                            341
                        );
                    } else {

                        $amount_solde =  $wallet_user->wallet->amount -  $valAmount;

                        $walletReload =  $this->reloadRepository->create([
                            'amount_solde' => $amount_solde ?? null,
                            'wallet_id' =>   $wallet_user->wallet->id,
                            'amount' => $request->amount,
                            'description'  =>  'Demande de rechargement de compte',
                            'reload_cost_id' => 1,
                            'created_by' => Auth::guard('api')->user()->id,
                            'provider_id'  => $request->provider,
                            'account_wallet'  => $request->account_wallet  ?? null,
                            'phone'  =>  Auth::guard('api')->user()->country->indecative . $request->phone ?? null,
                            'status'  => "PENDING"
                        ]);

                        $commission =  (($walletReload->amount)  + ($walletReload->amount * $walletReload->reloadCost->percent));

                        $wallet_user->wallet->update([
                            'amount' => DB::raw('amount-'  .  $commission),

                        ]);



                        if ($wallet_user->country->code == "CI") {

                            $message = 'Vous avez effectué un transafers de ' . $walletReload->amount .  $wallet_user->wallet->currencie->code .  'par '
                                . $walletReload->provider->name  .  ' a '
                                . $walletReload->created_at . ' votre demande est en-cours de validation ' . 'Robus vous remercie';

                            $encode = substr(Auth::guard('api')->user()->phone,  -10);

                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                        }

                        $msg = 'Demande en cours de validation';
                        $msg_title = 'Achat d\'UV';
                        // $admin_search->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                        // $notifs = $admin_search->unreadNotifications;
                        $wallet_user->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                        $notifs = $wallet_user->unreadNotifications;
                        event(new CreateReloadEvent($walletReload));
                        Notification::send($admins, new AdminNotification('Demande de rechargement UV de ' . ' ' . $walletReload->wallet->sidUser->firstname));
                        $admin = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $wallet_user->country->id)->get();

                        if ($admin->count() > 0) {

                            foreach ($admin  as $user) {

                                $admin_single = SidUser::find($user->id);

                                $notifs = $admin_single->unreadNotifications;

                                event(new AdminNotifEvent($notifs));
                            }
                            if ($wallet_user->email != null) {

                                $notify = new MailController;
                                $notify->newReloadUV($walletReload);
                            }
                            return response()->json([
                                'success' => true,
                                'data' => [
                                    'id'      => $walletReload->id,
                                    'amount' => $walletReload->amount,
                                    'account_wallet' => $walletReload->account_wallet,
                                    'percent' => $walletReload->reloadCost->percent,
                                    'phone' => $walletReload->phone,
                                    'status' => $walletReload->status,
                                ],
                                'message' => ('Reload has been created successfully.')
                            ], 200);
                        } else {

                            if ($wallet_user->email != null) {

                                $notify = new MailController;
                                $notify->newReloadUV($walletReload);
                            }
                            return response()->json([
                                'success' => true,
                                'data' => [
                                    'id'      => $walletReload->id,
                                    'amount' => $walletReload->amount,
                                    'account_wallet' => $walletReload->account_wallet,
                                    'percent' => $walletReload->reloadCost->percent,
                                    'phone' => $walletReload->phone,
                                    'status' => $walletReload->status,
                                ],
                                'message' => ('Reload has been created successfully.')
                            ], 200);
                        }
                    }
                }
            }
        } else  return response()->json([
            'success' => false,
            'message' => 'No selection ',
        ], 404);
    }

    public function messageAfterValidated($walletReload)
    {


        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletReload->id,
                'provider' => $walletReload->provider->name,
                'amount' => $walletReload->amount,
                // 'phone' => $walletReload->phone,
                // 'num_wallet' => $walletReload->num_wallet,
                'status' => $walletReload->status,
            ],
            'message' => ('Votre demande de transaction a bien été prise en compte.')
        ], 200);
    }

    /**
     * Delete reload
     * @OA\Delete (
     *      path="/user/reload/delete/{id}",
     *      tags={"Reload User"},
     *      summary="Delete reload user",
     *      description="Supprimer les rechargements", 
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(property="msg", type="integer", example="delete virement success")
     *         )
     *     ),
     *  @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="reload_id",
     *                          type="number"
     *                      ),
     *                 ),
     *                
     *             )
     *         )
     *      ),
     * )
     */
    public function delete_reload(Request $request)
    {

        try {


            $wallet_reload = WalletReload::find($request->reload_id);

            if ($this->deletedService->deletedTransaction($wallet_reload)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ce virement a été surppime de la liste.'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de suppression du virement.'
                ], 200);
            }
        } catch (\Exception $exception) {
            return   json_message_response(false, "Erreur de suppression !", 300);
        }
    }

    // Verify ceilling


    public function getWalletBankCardCeilingProperty()
    {
        $data = [];
        foreach (
            BankCard::where('created_by', Auth::guard('api')->user()->id)
                ->whereDate('created_at', Carbon::today())->whereStatus(1)->get() as $bank
        ) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }


    public function getWalletBanksCeilingProperty()
    {
        $data = [];
        foreach (
            WalletBank::where('created_by', Auth::guard('api')->user()->id)
                ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $bank
        ) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletDepositsCeilingProperty()
    {

        $data = [];

        foreach (
            WalletDeposit::where('created_by',  Auth::guard('api')->user()->id)
                ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $deposit
        ) {
            $data[] = $deposit->amount;
        }

        return array_sum($data);
    }

    public function getWalletIntersCeilingProperty()
    {
        $data = [];

        foreach (
            WalletInter::where('created_by',  Auth::guard('api')->user()->id)
                ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $inter
        ) {
            $data[] = $inter->amount;
        }

        return array_sum($data);
    }

    public function getWalletPaymentsCeilingProperty()
    {
        $data = [];

        foreach (
            WalletPayment::where('created_by',  Auth::guard('api')->user()->id)
                ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $payment
        ) {
            $data[] = $payment->amount;
        }

        return array_sum($data);
    }


    public function getWalletTransactionsCeilingProperty()
    {
        $data = [];

        foreach (
            WalletTransaction::where('created_by',  Auth::guard('api')->user()->id)
                ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $transaction
        ) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }

    public function getWalletPhoneCreditsCeilingProperty()
    {
        $data = [];


        foreach (
            WalletPhoneCredit::where('created_by', Auth::guard('api')->user()->id)
                ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $phone
        ) {
            $data[] = $phone->amount;
        }

        return array_sum($data);
    }

    public function getWalletReloadsCeilingProperty()
    {
        $data = [];

        foreach (
            WalletReload::where('wallet_reloads.created_by',  Auth::guard('api')->user()->id)
                ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $reload
        ) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }

    public function search_reload($amount_reload)
    {



        try {

            $percent_robus =  $this->percentRobus->getRobusPercentageReload();

            if ($percent_robus != null) {

                if ($amount_reload != '') {

                    $reel_amount = $this->validatedamountDays->search_amount($amount_reload);

                    $commission_phone_credit_val_nat_pay = 0;
                    $commission_phone_credit_commission = 0;

                    $total_percent = (float) $percent_robus->percent + (float) $percent_robus->percent_admin;
                    $commission_phone_credit_val_nat_pay =   $total_percent  *  $reel_amount;

                    $commission_phone_credit_commission =  (float) $reel_amount -  (float) $total_percent ;

                    $countryNameGroup = robus()->country->name_groupe;

                    $mobileMoneyGroups = ['CIV', 'AFL', 'AFC', 'AFE'];

                    return response()->json([
                        'success' => $percent_robus != null ? true : false,
                        'data' =>  [
                            'amount_pay' => $commission_phone_credit_val_nat_pay,
                            'amount_send' => $commission_phone_credit_commission,
                            'amount_receive' => in_array($countryNameGroup, $mobileMoneyGroups)
                                ? 'Vous devez utiliser les rechargements Mobile Money'
                                : 'Vous devez utiliser les cartes bancaires',
                        ],
                    ], 200);
                }
            } else {
                return response()->json([
                    'success' =>  false,
                    'message' => 'Pas de Virement dans cette zone ...',
                ], 200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' =>  false,
                'message' => 'Pas d\'utilisateur',
            ], 200);
        }
    }

    public function search_pixel_in_reload($amount_reload)
    {



        try {

            $percent_robus =  $this->percentRobus->getRobusPercentagePixelInReload();

            if ($percent_robus != null) {

                if ($amount_reload != '') {

                    $reel_amount = $this->validatedamountDays->search_amount($amount_reload);

                    $commission_phone_credit_val_nat_pay = 0;
                    $commission_phone_credit_commission = 0;

                    $total_percent = (float) $percent_robus->percent + (float) $percent_robus->percent_admin;
                    $commission_phone_credit_val_nat_pay =   $total_percent  *  $reel_amount;

                    $commission_phone_credit_commission =  (float) $reel_amount -  (float) $total_percent ;

                    $countryNameGroup = robus()->country->name_groupe;

                    $mobileMoneyGroups = ['CIV', 'AFL', 'AFC', 'AFE'];

                    return response()->json([
                        'success' => $percent_robus != null ? true : false,
                        'data' =>  [
                            'amount_pay' => $commission_phone_credit_val_nat_pay,
                            'amount_send' => $commission_phone_credit_commission,
                            'amount_receive' => in_array($countryNameGroup, $mobileMoneyGroups)
                                ? 'Vous devez utiliser les rechargements Mobile Money'
                                : 'Vous devez utiliser les cartes bancaires',
                        ],
                    ], 200);
                }
            } else {
                return response()->json([
                    'success' =>  false,
                    'message' => 'Pas de Virement dans cette zone ...',
                ], 200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' =>  false,
                'message' => 'Pas d\'utilisateur',
            ], 200);
        }
    }

     public function search_pixel_out_reload($amount_reload)
    {



        try {

            $percent_robus =  $this->percentRobus->getRobusPercentagePixelOutReload();

            if ($percent_robus != null) {

                if ($amount_reload != '') {

                    $reel_amount = $this->validatedamountDays->search_amount($amount_reload);

                    $commission_phone_credit_val_nat_pay = 0;
                    $commission_phone_credit_commission = 0;

                    $total_percent = (float) $percent_robus->percent + (float) $percent_robus->percent_admin;
                    $commission_phone_credit_val_nat_pay =   $total_percent  *  $reel_amount;

                    $commission_phone_credit_commission =  (float) $reel_amount -  (float) $total_percent ;

                    $countryNameGroup = robus()->country->name_groupe;

                    $mobileMoneyGroups = ['CIV', 'AFL', 'AFC', 'AFE'];

                    return response()->json([
                        'success' => $percent_robus != null ? true : false,
                        'data' =>  [
                            'amount_pay' => $commission_phone_credit_val_nat_pay,
                            'amount_send' => $commission_phone_credit_commission,
                            'amount_receive' => in_array($countryNameGroup, $mobileMoneyGroups)
                                ? 'Vous devez utiliser les rechargements Mobile Money'
                                : 'Vous devez utiliser les cartes bancaires',
                        ],
                    ], 200);
                }
            } else {
                return response()->json([
                    'success' =>  false,
                    'message' => 'Pas de Virement dans cette zone ...',
                ], 200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' =>  false,
                'message' => 'Pas d\'utilisateur',
            ], 200);
        }
    }
}
