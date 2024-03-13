<?php

namespace App\Http\Controllers\Api\Transfers;


use App\Http\Controllers\Admin\MailController;
use App\Http\Controllers\Controller;
use App\Http\Services\ConvertionService;
use App\Http\Services\CreateRobusWalletWithDrawalService;
use App\Http\Services\DeletedService;
use App\Http\Services\MasterService;
use App\Http\Services\MobileWalletTransactionService;
use App\Http\Services\NotificationSendService;
use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\ReloadService;
use App\Http\Services\RobusTransactionService;
use App\Http\Services\SearchTransactionService;
use App\Http\Services\ValidateDailyLimitService;
use App\Models\Costs\TransactionCost;
use App\Models\Providers\Provider;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletTransaction;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{

    /** @var  TransactionRepository */
    protected $transctionRepository;
    protected $robusTransactionService;
    protected $transactionWalletDrawal;
    protected $mobileWalletTransactionService;
    protected $validatedamountDays;
    protected $notifications;
    protected $deletedService;
    protected $percentRobus;
    protected $searchTransaction;
    protected $SearchConvertionService;
    protected $robusMasterTransaction;
    protected $robusReloadMasterTransaction;

    public function __construct(
        TransactionRepository $transactionReposi,
        MobileWalletTransactionService $mobileWalletTransactionService,
        RobusTransactionService $robusTransactionService,
        ValidateDailyLimitService $validatedamountDays,
        NotificationSendService $notifications,
        CreateRobusWalletWithDrawalService $transactionWalletDrawal,
        DeletedService $deletedService,
        PercentRobusAuthService $percentRobus,
        SearchTransactionService $searchTransaction,
        ConvertionService $SearchConvertionService,
        MasterService $robusMasterTransaction,
        ReloadService $robusReloadMasterTransaction
    ) {
        $this->transctionRepository = $transactionReposi;
        $this->mobileWalletTransactionService = $mobileWalletTransactionService;
        $this->robusTransactionService = $robusTransactionService;
        $this->validatedamountDays = $validatedamountDays;
        $this->notifications = $notifications;
        $this->transactionWalletDrawal = $transactionWalletDrawal;
        $this->deletedService = $deletedService;
        $this->percentRobus = $percentRobus;
        $this->searchTransaction = $searchTransaction;
        $this->SearchConvertionService = $SearchConvertionService;
        $this->robusMasterTransaction = $robusMasterTransaction;
        $this->robusReloadMasterTransaction = $robusReloadMasterTransaction;
    }
    /**
     * @OA\POST(
     *      path="/user/transactions/store",
     *      tags={"Transactions User"},
     *      summary="Create new virement Bank",
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
     *               @OA\Property(property="amount", type="number", example="1000"),
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
     *          description="Vérifiez votre solde."
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
     * 
     *  ),
     */
    public function create_transaction(Request $request)
    {

        $wallet_user = Auth::guard('api')->user();
        $amount_days = $this->validatedamountDays->getCeilingProperty();
        $admin_civ = $this->validatedamountDays->adminCiv();
        $valeur_ceiling  =  $wallet_user->silver_ceiling;
        $valeur_max_send  =  robus()->country->amount_max_send;


        try {

            if ($request->provider == 9) {

                $validator = Validator::make(
                    $request->all(),
                    [
                        "num_wallet" => 'required',
                        "amount" => 'required'
                    ],
                    [

                        'num_wallet.required' => 'Votre numero est obligatoire',
                        'amount.required' => 'Votre montant est obligatoire',

                    ]
                );

                if ($validator->fails()) {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'Champ is required ',
                            'data' => $validator->errors()
                        ],
                        333
                    );
                }

                $wallet_search = $this->robusTransactionService->searchWalletRobus($request->num_wallet);


                if (!$wallet_search || $request->num_wallet == $wallet_user->wallet->wallet_id) {

                    return response()->json([
                        'success' => false,
                        'message' => 'Ce numéro n\'est pas ROBUS ou est votre numéro',
                    ], 333);
                }

                // Convertion valeur amount
                $reel_amount = $this->validatedamountDays->search_amount($request->amount);

                if (robus()->is_under_distributer) {

                    $percent_robus = $this->percentRobus->getRobusPercentageSDistributers();

                    // Vérifier si l'utilisateur est un client et non un distributeur, un administrateur, ou un distributeur
                    $isCustomer = $wallet_search->sidUser->is_custumers;
                    $isNotDistributor = !$wallet_search->sidUser->is_under_distributer;
                    $isNotAdmin = !$wallet_search->sidUser->is_admin;
                    $isNotDistributor = !$wallet_search->sidUser->is_distributer;

                    if ($isCustomer && $isNotDistributor && $isNotAdmin && $isNotDistributor) {


                        if ($percent_robus !== null) {

                            if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {


                                if ($this->validatedamountDays->validateAmount($reel_amount)) {

                                    $valeur_received = $reel_amount - (float) ($percent_robus->percent * $reel_amount);

                                    // Update Info valeurs
                                    $walletAmountSend = Wallet::where('wallet_id',  $request->num_wallet)->first();

                                    $amount_solde_drawal = (float)$walletAmountSend->amount + (float)$valeur_received;

                                    $walletTransaction =  $this->robusTransactionService->createRobusWalletTransaction($reel_amount, $percent_robus, $request->provider, $request->num_wallet, $amount_solde_drawal);

                                    if ($walletTransaction != null) {

                                        if ($this->robusTransactionService->updateAmountRobusReceived($request->num_wallet, $walletTransaction, $valeur_received)) {
                                            if ($this->robusTransactionService->processCommissionsDistributerRobus($reel_amount, $percent_robus, $walletTransaction, $admin_civ)) {
                                                $this->notifications->sendNotification($walletTransaction);
                                                return $this->messageAfterValidated($walletTransaction);
                                            } else {

                                                return  json_message_response(false, "Error de transferts du montant!", 333);
                                            }
                                        }
                                    } else {

                                        return  json_message_response(false, "Error de creation de transfert du montant!", 333);
                                    }
                                } else {
                                    return json_message_response(false, "Verifié votre solde !!", 333);
                                }
                            } else {
                                return json_message_response(false, "Votre montant journalier est atteint !!", 333);
                            }
                        } else {
                            return  json_message_response(false, "Niveau de pourcentage introuvable !", 333);
                        }
                    } else {


                        if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {


                            if ($this->validatedamountDays->validateAmount($reel_amount)) {

                                $valeur_received = $reel_amount;

                                // Update Info valeurs
                                $walletAmountSend = Wallet::where('wallet_id',  $request->num_wallet)->first();

                                $amount_solde_drawal = (float)$walletAmountSend->amount + (float)$valeur_received;

                                $walletTransaction =  $this->robusTransactionService->createRobusWalletTransactionDistributer($reel_amount, $percent_robus, $request->provider, $request->num_wallet, $amount_solde_drawal);

                                if ($walletTransaction != null) {

                                    if ($this->robusTransactionService->updateAmountRobusReceivedDistributer($request->num_wallet, $walletTransaction, $valeur_received)) {
                                        if ($this->robusTransactionService->processCommissionsRobusDistributer($reel_amount)) {
                                            $this->notifications->sendNotification($walletTransaction);
                                            return $this->messageAfterValidated($walletTransaction);
                                        } else {

                                            return  json_message_response(false, "Error de transferts du montant!", 333);
                                        }
                                    }
                                } else {
                                    return json_message_response(false, "Error de createtion transferts", 333);
                                }
                            } else {
                                return json_message_response(false, "Verifié votre solde ", 333);
                            }
                        } else {
                            return json_message_response(false, "Votre montant journalier est atteint !!", 333);
                        }
                    }
                } else {

                    $percent_robus = $this->percentRobus->getRobusPercentageCustumers();

                    // Code validated
                    $isWalletSearchNull = $wallet_search === null;
                    $isCustomer = $wallet_search->sidUser->is_custumers;
                    $isCustomerStatusFalse = $wallet_search->sidUser->status === false;
                    $isSameWalletAsUser = $request->num_wallet === $wallet_user->wallet->wallet_id;
                    $isUnderDistributor = $wallet_search->sidUser->is_under_distributer === true;
                    $isAdmin_all = $wallet_search->sidUser->is_admin === true;

                    // Is custumers
                    if ($isWalletSearchNull || !$isCustomer || $isCustomerStatusFalse || $isSameWalletAsUser || $isUnderDistributor || $isAdmin_all) {

                        return response()->json(
                            [
                                'success' => false,
                                'message' => 'Ce numéro n\'est pas ROBUS ou est votre numéro ou un distributeur',
                            ],
                            333
                        );
                    } else {


                        if ($percent_robus !== null) {

                            if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {


                                if ($this->validatedamountDays->validateAmount($reel_amount)) {

                                    $valeur_received = $reel_amount - (float) ($percent_robus->percent * $reel_amount);

                                    // Update Info valeurs
                                    $walletAmountSend = Wallet::where('wallet_id',  $request->num_wallet)->first();

                                    $amount_solde_drawal = (float)$walletAmountSend->amount + (float)$valeur_received;

                                    $walletTransaction = $this->robusTransactionService->createRobusWalletTransaction($reel_amount, $percent_robus, $request->provider, $request->num_wallet, $amount_solde_drawal);

                                    if ($walletTransaction != null) {

                                        if ($this->robusTransactionService->updateAmountRobusReceived($request->num_wallet, $walletTransaction, $valeur_received)) {
                                            if ($this->robusTransactionService->processCommissionsCustumerRobus($reel_amount, $percent_robus, $walletTransaction, $admin_civ)) {
                                                $this->notifications->sendNotification($walletTransaction);
                                                return $this->messageAfterValidated($walletTransaction);
                                            } else {

                                                return json_message_response(false, "Error de transferts du montant!", 333);
                                            }
                                        }
                                    } else {

                                        return json_message_response(false, "Error de creation de transfert du montant!", 333);
                                    }
                                } else {
                                    return json_message_response(false, "Verifié votre solde SVP !", 333);
                                }
                            } else {
                                return json_message_response(false, "Votre plafond quotidien est atteint !", 333);
                            }
                        } else {
                            return  json_message_response(false, "Niveau de pourcentage introuvable !", 333);
                        }
                    }
                }
            } else {

                if (robus()->is_under_distributer) {



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
                                'message' => 'Numero et le montant  indisponible',
                                'data' => $validator->errors()
                            ],
                            333
                        );
                    }

                    if (robus()->country->name_groupe == 'CIV') {

                        $percent_robus = $this->percentRobus->getRobusPercentageMobile();
                    } else {

                        return response()->json(
                            [
                                'success' => false,
                                'message' => 'Pas de transfert Mobile Money',
                            ],
                            333
                        );
                    }
                } else {


                    $validator = Validator::make(

                        $request->all(),
                        [
                            "phone" => 'required',
                            "amount" => 'required',
                            // "name_custumer" => 'required',
                            // "num_piece_custumer" => 'required',
                            // "date_piece" => 'required',
                        ],
                        [

                            'phone.required' => 'Votre numero est obligatoire',
                            'amount.required' => 'Votre montant est obligatoire',
                            // 'name_custumer.required' => 'Nom est obligatoire',
                            // 'num_piece_custumer.required' => 'Numero piece est obligatoire',
                            // 'date_piece.required' => 'date de validité est obligatoire',

                        ]
                    );

                    if ($validator->fails()) {
                        return response()->json(
                            [
                                'success' => false,
                                'message' => 'Numero et le montant indisponible',
                                'data' => $validator->errors()
                            ],
                            333
                        );
                    }

                    if (robus()->country->name_groupe == 'CIV') {

                        $percent_robus = $this->percentRobus->getRobusPercentageMobileTwo();
                    } else {

                        return  json_message_response(false, "Pa de Transfert Mobile Money dans la zone !", 333);
                    }
                }

                $reel_amount = $this->validatedamountDays->search_amount($request->amount);

                if ($percent_robus !== null) {

                    if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                        if ($this->validatedamountDays->validateAmountSendLimit($reel_amount, $valeur_max_send)) {

                            if (robus()->is_under_distributer) {

                                $valeur_received = $reel_amount;

                                $cost_payment = $percent_robus->percent *  $reel_amount;

                                $amount_payment = $reel_amount;
                            } else {

                                $valeur_received = $reel_amount - ($percent_robus->percent * $reel_amount);

                                $cost_payment = $percent_robus->percent *  $reel_amount;

                                $amount_payment =  $reel_amount + ($percent_robus->percent * $reel_amount);
                            }

                            if ($this->validatedamountDays->validateAmount($amount_payment)) {

                                $walletTransaction =  $this->mobileWalletTransactionService->createRobusWalletTransactionDistributer($percent_robus, $request->provider, $reel_amount, $request->date_piece, $request->num_piece_custumer, $request->name_custumer, $cost_payment, $request->phone);

                                if ($walletTransaction != null) {

                                    if ($this->mobileWalletTransactionService->updateAmountRobusReceivedMobile($walletTransaction, $valeur_received)) {

                                        if (robus()->is_under_distributer) {
                                            $processCommission = $this->mobileWalletTransactionService->processCommissionsMobileDistributer($reel_amount, $percent_robus, $walletTransaction, $admin_civ);
                                        } else {

                                            $processCommission = $this->mobileWalletTransactionService->processCommissionsMobile($reel_amount, $percent_robus, $walletTransaction, $admin_civ);
                                        }

                                        if ($processCommission) {

                                            $this->notifications->sendNotificationMobile($walletTransaction);

                                            return $this->messageAfterValidated($walletTransaction);
                                        } else {

                                            return  json_message_response(false, "Error de transferts du montant!", 333);
                                        }
                                    }
                                } else {

                                    return  json_message_response(false, "Error de createtion transferts !", 333);
                                }
                            } else {

                                return   json_message_response(false, "Verifié votre solde SVP !", 333);
                            }
                        } else {


                            return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . robus()->country->currencie->code, 333);
                        }
                    } else {
                        return   json_message_response(false, "Votre montant journalier est atteint !", 333);
                    }
                } else {
                    return json_message_response(false, "Niveau de pourcentage introuvable", 333);
                }
            };
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
            ], 333);
        }
    }

    public function create_reload_master(Request $request)
    {

        $wallet_user = robus();
        $amount_days = $this->validatedamountDays->getCeilingProperty();
        $admin_civ = $this->validatedamountDays->adminCiv();
        $valeur_ceiling  =  $wallet_user->silver_ceiling;
        $provider = Provider::whereId(9)->first();

        try {

            if ($request->provider == 9) {

                $validator = Validator::make(
                    $request->all(),
                    [
                        "num_wallet" => 'required',
                        "amount" => 'required'
                    ],
                    [

                        'num_wallet.required' => 'Votre numero est obligatoire',
                        'amount.required' => 'Votre montant est obligatoire',

                    ]
                );

                if ($validator->fails()) {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'Champ is required ',
                            'data' => $validator->errors()
                        ],
                        333
                    );
                }

                $wallet_search = $this->robusTransactionService->searchWalletRobus($request->num_wallet);

                if (!$wallet_search || $request->num_wallet == $wallet_user->wallet->wallet_id) {

                    return response()->json([
                        'success' => false,
                        'message' => 'Ce numéro n\'est pas ROBUS ou est votre numéro',
                    ], 333);
                }


                $sidUser = SidUser::find($wallet_search->sid_user_id);

                if ($sidUser) {

                    $reel_amount = $this->validatedamountDays->search_amount($request->amount);

                    if ($sidUser->is_master) {

                        if ($wallet_search == null &&  $request->num_wallet == robus()->wallet->wallet_id) {

                            return response()->json([
                                'success' => false,
                                'message' => 'Ce numéro n\'est pas ROBUS ou est votre numéro',
                            ], 333);
                        }

                        if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                            if ($this->validatedamountDays->validateAmount($reel_amount)) {


                                $valeur_receive = $this->SearchConvertionService->convertAdminCommissionReload($reel_amount, $wallet_search);

                                // $amount_solde = $check->wallet->amount +  $commission;
                                $walletAmountSend = Wallet::where('wallet_id',  $request->num_wallet)->first();

                                $amount_solde_drawal = (float)$walletAmountSend->amount +  (float)$valeur_receive;

                                $amount_drawal =    $amount_solde_drawal;

                                $amount_solde = (float)robus()->wallet->amount - $reel_amount;

                                $walletReloads =  $this->robusReloadMasterTransaction->createWAlletReloadRobus(robus(), $reel_amount, $provider, $request->num_wallet, $valeur_receive, $amount_solde, $amount_drawal);

                                if ($walletReloads != null) {
                                    if ($this->robusReloadMasterTransaction->updateAmountRobusReceived($walletReloads, robus(), $wallet_search, $reel_amount)) {
                                        return  $this->robusReloadMasterTransaction->sendNotification($wallet_search, robus(), $walletReloads, $admin_civ);
                                    } else {

                                        return  json_message_response(false, "Error de transferts du montant!", 333);
                                    }
                                } else {

                                    return  json_message_response(false, "Error de creation de transfert du montant!", 333);
                                }
                            } else {

                                return json_message_response(false, "Verifié le solde de la personne  !!", 333);
                            }
                        } else {
                            return json_message_response(false, "Votre montant journalier est atteint !!", 333);
                        }
                    } elseif ($sidUser->is_under_distributer) {

                        $account_active = $wallet_search->sidUser->master_id;

                        $account_accepted = $wallet_search->sidUser->is_accepted;

                        $account_is_under = $wallet_search->sidUser->is_under_distributer;


                        if ($account_active == robus()->id && $account_accepted) {


                            if ($wallet_search == null &&  $request->num_wallet == robus()->wallet->wallet_id) {

                                return response()->json([
                                    'success' => false,
                                    'message' => 'Ce numéro n\'est pas ROBUS ou est votre numéro',
                                ], 333);
                            }

                            if ($account_is_under) {

                                if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                                    if ($this->validatedamountDays->validateAmount($reel_amount)) {


                                        $valeur_receive = $this->SearchConvertionService->convertAdminCommissionReload($reel_amount, $wallet_search);

                                        // $amount_solde = $check->wallet->amount +  $commission;
                                        $walletAmountSend = Wallet::where('wallet_id',  $request->num_wallet)->first();

                                        $amount_solde_drawal = (float)$walletAmountSend->amount +  (float)$valeur_receive;

                                        $amount_drawal =    $amount_solde_drawal;

                                        $amount_solde = (float)robus()->wallet->amount -  $reel_amount;

                                        $walletReloads =  $this->robusReloadMasterTransaction->createWAlletReloadRobus(robus(), $reel_amount, $provider, $request->num_wallet, $valeur_receive, $amount_solde, $amount_drawal);

                                        if ($walletReloads != null) {
                                            if ($this->robusReloadMasterTransaction->updateAmountRobusReceived($walletReloads, robus(), $wallet_search, $reel_amount)) {
                                                return  $this->robusReloadMasterTransaction->sendNotification($wallet_search, robus(), $walletReloads, $admin_civ);
                                            } else {

                                                return  json_message_response(false, "Error de transferts du montant!", 333);
                                            }
                                        } else {

                                            return  json_message_response(false, "Error de creation de transfert du montant!", 333);
                                        }
                                    } else {

                                        return json_message_response(false, "Verifié le solde de la personne  !!", 333);
                                    }
                                } else {
                                    return json_message_response(false, "Votre montant journalier est atteint !!", 333);
                                }
                            } else {

                                return json_message_response(false, "Le compte doit etre pour un Sous distributeur", 333);
                            }
                        } else {
                            return json_message_response(false, "Le compte doit etre un Sous distributeur", 333);
                        }
                    } else {

                        return json_message_response(false, "Le compte doit etre un Sous distributeur ou un Master ", 333);
                    }
                } else {
                    return json_message_response(false, "Utilisateur introuvable ", 333);
                }
            } else {
                return json_message_response(false, "Provider introuvable ", 333);
            };
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
            ], 333);
        }
    }


    public function create_transaction_master(Request $request)
    {

        $wallet_user = robus();
        $amount_days = $this->validatedamountDays->getCeilingProperty();
        $admin_civ = $this->validatedamountDays->adminCiv();
        $valeur_ceiling  =  $wallet_user->silver_ceiling;
        $provider = Provider::whereId(9)->first();

        try {

            if ($request->provider == 9) {

                $validator = Validator::make(
                    $request->all(),
                    [
                        "num_wallet" => 'required',
                        "amount" => 'required'
                    ],
                    [

                        'num_wallet.required' => 'Votre numero est obligatoire',
                        'amount.required' => 'Votre montant est obligatoire',

                    ]
                );

                if ($validator->fails()) {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'Champ is required ',
                            'data' => $validator->errors()
                        ],
                        333
                    );
                }

                $wallet_search = $this->robusTransactionService->searchWalletRobus($request->num_wallet);

                if (!$wallet_search || $request->num_wallet == $wallet_user->wallet->wallet_id) {

                    return response()->json([
                        'success' => false,
                        'message' => 'Ce numéro n\'est pas ROBUS ou est votre numéro',
                    ], 333);
                }

                $sidUser = SidUser::find($wallet_search->sid_user_id);

                if ($sidUser) {

                    if ($sidUser->is_under_distributer) {

                        $account_active = $wallet_search->sidUser->master_id;

                        $account_accepted = $wallet_search->sidUser->is_accepted;

                        $account_is_under = $wallet_search->sidUser->is_under_distributer;

                        if ($account_active == robus()->id && $account_accepted) {


                            if ($wallet_search == null &&  $request->num_wallet == robus()->wallet->wallet_id) {

                                return response()->json([
                                    'success' => false,
                                    'message' => 'Ce numéro n\'est pas ROBUS ou est votre numéro',
                                ], 333);
                            }

                            $reel_amount = $this->validatedamountDays->search_amount($request->amount);

                            if ($account_is_under) {

                                if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {


                                    $wallet_send = $wallet_search;

                                    $commission_receiveid =  $reel_amount;

                                    $wallet_receveid =  robus()->wallet;

                                    $commission_send =   $this->SearchConvertionService->convertAdminCommissionInter($commission_receiveid, $wallet_send, $wallet_receveid);

                                    if ($commission_send > $wallet_search->amount) {

                                        return json_message_response(false, "Verifié le solde de la personne  !!", 333);
                                    } else {


                                        $walletAmountSend = DB::table('wallets')->select(DB::raw('SUM(amount) as total_amount'))->where('wallet_id',  $request->num_wallet)->first();

                                        $amount_solde_drawal = (float)$walletAmountSend->total_amount -  (float)$commission_send;

                                        $amount_drawal =    $amount_solde_drawal;

                                        $amount_solde = (float)robus()->wallet->amount +  (float) $reel_amount;

                                        $walletUnlock =   $this->robusMasterTransaction->createRobusUnlocked(robus(), $reel_amount, $provider, $request->num_wallet, $amount_solde, $amount_drawal, $commission_send);

                                        if ($walletUnlock != null) {
                                            if ($this->robusMasterTransaction->updateAmountRobusReceived($commission_send, robus(), $wallet_search, $walletUnlock, $reel_amount)) {
                                                return  $this->robusMasterTransaction->sendNotification($wallet_search, robus(), $walletUnlock, $admin_civ);
                                            } else {

                                                return  json_message_response(false, "Error de transferts du montant!", 333);
                                            }
                                        } else {

                                            return  json_message_response(false, "Error de creation de transfert du montant!", 333);
                                        }
                                    }
                                } else {
                                    return json_message_response(false, "Votre montant journalier est atteint !!", 333);
                                }
                            } else {

                                return json_message_response(false, "Le compte doit etre un Sous distributeur", 333);
                            }
                        } else {

                            return json_message_response(false, "Le compte doit etre un Sous distributeur", 333);
                        }
                    } else {
                        return json_message_response(false, "Le compte doit etre un Sous distributeur ", 333);
                    }
                } else {
                    return json_message_response(false, "Numero du wallet introuvable ", 333);
                }
            } else {
                return json_message_response(false, "Provider introuvable ", 333);
            };
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
            ], 333);
        }
    }


    public function messageAfterValidated($walletTransaction)
    {


        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletTransaction->id,
                'provider' => $walletTransaction->provider->name,
                'amount' => $walletTransaction->amount,
                'status' => $walletTransaction->status,
            ],
            'message' => ('Votre demande de transaction a bien été prise en compte.')
        ], 200);
    }

    // First sted drawal
    public function first_withdrawal(Request $request)
    {

        $wallet_user = Auth::guard('api')->user();
        $amount_days = $this->validatedamountDays->getCeilingProperty();
        $valeur_ceiling  =  robus()->silver_ceiling;

        try {

            // If Distributer and S-distributer
            if (robus()->is_under_distributer) {

                if ($request->provider == 9) {


                    $validator = Validator::make(
                        $request->all(),
                        [
                            "num_wallet" => 'required',
                            "amount" => 'required'
                        ],
                        [

                            'num_wallet.required' => 'Votre numero est obligatoire',
                            'amount.required' => 'Votre montant est obligatoire',

                        ]
                    );

                    if ($validator->fails()) {
                        return response()->json(
                            [
                                'success' => false,
                                'message' => 'Champ is required ',
                                'data' => $validator->errors()
                            ],
                            333
                        );
                    }

                    // Seach wallet User
                    // $wallet_search = Wallet::where('wallet_id',  $request->num_wallet)
                    //     ->where('country_id',  $wallet_user->country->id)
                    //     ->whereStatus(true)
                    //     ->first();

                    $wallet_search = $this->robusTransactionService->searchWalletRobus($request->num_wallet);

                    // Wallet verify
                    if (!$wallet_search || !$wallet_search->sidUser->is_custumers || !$wallet_search->sidUser->status || $request->num_wallet === $wallet_user->wallet->wallet_id) {
                        return response()->json([
                            'success' => false,
                            'message' => "Ce numéro n'est pas ROBUS ou est votre numéro",
                            'data' => $validator->errors()
                        ], 333);
                    } else {

                        $reel_amount = $this->validatedamountDays->search_amount($request->amount);

                        if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                            if ($this->validatedamountDays->validateAmountDrawel($reel_amount, $request->num_wallet)) {

                                $walletTransaction = $this->transactionWalletDrawal->createRobusWalletWithDrawalTransaction($reel_amount, $request->provider, $request->num_wallet,  $wallet_search);

                                if ($walletTransaction != null) {

                                    return response()->json([
                                        'success' => true,
                                        'data' => [
                                            'id'      => $walletTransaction->id,
                                            'provider' => $walletTransaction->provider->name,
                                            'amount' => $walletTransaction->amount,
                                            // 'phone' => $walletTransaction->phone,
                                            // 'num_wallet' => $walletTransaction->num_wallet,
                                            'status' => $walletTransaction->status,
                                        ],
                                        'message' => ('Votre demande de retrait en-cours de validation .')
                                    ], 200);
                                } else {

                                    return  json_message_response(false, "Error de creation de transfert du montant!", 333);
                                }
                            } else {
                                return   json_message_response(false, "Verifie votre solde de retrait !", 333);
                            }
                        } else {
                            return   json_message_response(false, "Votre montant journalier est atteint !", 333);
                        }
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selectionne le type de retrait.',
                    ], 500);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous devez etre un distributer Robus.',
                ], 500);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
            ], 333);
        }
    }

    // Sted 2 Validated transaction
    public function second_withdrawal(Request $request)
    {

        $admins = $this->validatedamountDays->adminAllcountries();
        $admin_civ = $this->validatedamountDays->adminCiv();

        try {

            $validator = Validator::make(

                $request->all(),
                [
                    "code_validated" => 'required',
                ],
                [

                    'code_validated.required' => 'Votre code est obligatoire',

                ]
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Champ is required ',
                        'data' => $validator->errors()
                    ],
                    333
                );
            }

            $verify_code =  $this->validatedamountDays->validatedTransaction($request->code_validated);

            $walletUserSearch = $this->validatedamountDays->validatedWallet($request->num_wallet);


            if ($verify_code->num_wallet == $request->num_wallet) {


                if ($verify_code->status == "SUCCESS") {
                    return response()->json([
                        'success' => false,
                        'message' => ('Vous avez déjà fait un retrait par rapport à ce chiffre.')
                    ], 200);
                } else {

                    $ecoule = strtotime($verify_code->created_at);

                    $compare_date = date('H:i:s', $ecoule);

                    $temps_ecoule = time() - strtotime($compare_date);

                    $ecoule_min = date('i:s', $temps_ecoule);

                    // Verifie valeur code validation delete temps Elapsed 
                    if ($ecoule_min > '05:20') {

                        $verify_code->delete();

                        return response()->json(
                            [
                                'success' => false,
                                'message' => ' Les 5 minutes sont écoulées ! ',
                            ],
                            333
                        );
                    } else {

                        if (robus()->is_under_distributer) {

                            $percent_robus = $this->percentRobus->getRobusPercentageSDistributers();

                            if ($percent_robus != null) {

                                // Valeur off commission
                                $commission_valeur = $percent_robus->percent * $verify_code->amount;

                                // 30% with drawal
                                $valuer_with_drawal = $percent_robus->percent_with_drawal * $commission_valeur;

                                $verify_code->update([
                                    'type_transaction' => 'Retrait',
                                    'status' => 'SUCCESS',
                                    'costs_amount' => 0,
                                    'created_by' => robus()->id,
                                ]);

                                if ($this->transactionWalletDrawal->updateDrawalTransaction($verify_code, $walletUserSearch, $valuer_with_drawal)) {
                                    return $this->transactionWalletDrawal->sendNotificationWithDrawal($verify_code, $admin_civ, $valuer_with_drawal, $walletUserSearch, $admins);
                                } else {

                                    return  json_message_response(false, "Erreur de demande de retait ! ", 333);
                                }
                            } else {
                                return  json_message_response(false, "Pas de retrait disponible dans cette zone ! ", 333);
                            }
                        } else {
                            return  json_message_response(false, "Désolé vous ne pouvez pas faire de retrait ! ", 333);
                        }
                    }
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => (' Le code ou le numero n\'est pas correct, veuillez réessayer.')
                ], 200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
            ], 333);
        }
    }

    /**
     * Delete Transaction
     * @OA\Delete (
     *      path="/user/transaction/delete/{id}",
     *      tags={"Transactions User"},
     *      summary="Delete Transaction",
     *      description="Supprimer les Transactions", 
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
     *             @OA\Property(property="msg", type="integer", example="delete transaction success")
     *         )
     *     ),
     *  @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="transaction_id",
     *                          type="number"
     *                      ),
     *                 ),
     *                
     *             )
     *         )
     *      ),
     * )
     */

    public function delete_transaction(Request $request)
    {

        try {

            $wallet_reload = WalletTransaction::find($request->transaction_id);

            if ($this->deletedService->deletedTransaction($wallet_reload)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cette transaction a été surppime de la liste.'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de suppression.'
                ], 200);
            }
        } catch (\Exception $exception) {
            return   json_message_response(false, "Erreur de suppression !", 333);
        }
    }

    public function search_transaction($search)
    {

        try {

            return $this->searchTransaction->searchTransaction($search);
        } catch (\Exception $exception) {
            return response()->json([
                'success' =>  false,
                'message' => 'Pas d\'utilisateur',
            ], 200);
        }
    }

    // Search Valeur receive and valeur pay of Virement
    public function search_transaction_money_commission($amount_transaction)
    {


        $default_valeur = 2000000;


        try {


            if ($amount_transaction != '') {

                $reel_amount = $this->validatedamountDays->search_amount($amount_transaction);


                $commission_transaction = $this->searchTransaction->getCommissionTransactionMobileForCountryGroup(robus()->country->name_groupe, robus()->is_under_distributer);
                $commission_transaction_val_percent_pay = 0;
                $commission_transaction_val_nat_pay = 0;

                if ($commission_transaction) {

                    $commission_transaction_val_percent_pay = (float)$commission_transaction->percent * $reel_amount;

                    if (robus()->is_under_distributer) {

                        $commission_transaction_val_nat_pay = (float)$commission_transaction->percent_deposit * $commission_transaction_val_percent_pay;

                        $commission_transaction_val_nat_receive = (float)$reel_amount;
                    } else {

                        $commission_transaction_val_nat_pay = (float)$commission_transaction->percent * $reel_amount;

                        $commission_transaction_val_nat_receive = (float)$reel_amount - $commission_transaction_val_percent_pay;
                    }



                    $conversion_valeur_end = $this->searchTransaction->calculateConversionValeurEnd($default_valeur);

                    if ($conversion_valeur_end < $reel_amount) {
                        $commission_transaction_val_nat_pay = "Montant maximal est : " . number_format($conversion_valeur_end, 2, ',', ' ');
                    }
                }


                return response()->json([
                    'success' => true,
                    'data' => [
                        'amount_pay' => $commission_transaction_val_nat_pay,
                        'amount_send' => $commission_transaction_val_nat_pay,
                        'amount_receive' => $commission_transaction_val_nat_receive,
                    ],
                ], 200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' =>  false,
                'message' => 'Pas d\'utilisateur',
            ], 200);
        }
    }

    // Search Valeur receive and valeur pay of Virement
    public function search_transaction_commission_robus($wallet, $amount_transaction)
    {

        $default_valeur = 2000000;

        // valeur amount virement bank

        try {


            $data = $this->searchTransaction->searchTransactionWallet($wallet);

            // dd(  $data);
            if ($amount_transaction != '') {

                $reel_amount = $this->validatedamountDays->search_amount($amount_transaction);

                $commission_transaction_val_nat_pay = 0;
                $commission_transaction_val_percent_pay = 0;
                $commission_transaction_val_nat_receive = (float)$reel_amount;

                // Si l'utilisateur est sous distributeur, admin ou distributeur
                if ($data->sidUser->is_under_distributer || $data->sidUser->is_admin || $data->sidUser->is_distributer) {
                    $conversion_valeur_end = $this->searchTransaction->calculateConversionValeurEnd($default_valeur);

                    if ($conversion_valeur_end < $reel_amount) {
                        $commission_transaction_val_nat_pay = "Montant maximal est : " . number_format($conversion_valeur_end, 2, ',', ' ');
                    }
                } else {


                    // Si l'utilisateur n'est pas sous distributeur, admin ou distributeur
                    $commission_transaction = $this->searchTransaction->getCommissionTransactionForCountryGroup(robus()->country->name_groupe, robus()->is_under_distributer);

                    if ($commission_transaction) {

                        if ($data->sidUser->is_custumers) {

                            $commission_transaction_val_percent_pay = (float)$commission_transaction->percent * $reel_amount;
                            $commission_transaction_val_nat_pay = (float)$commission_transaction->percent_deposit * $commission_transaction_val_percent_pay;
                            $commission_transaction_val_nat_receive = (float)$reel_amount - $commission_transaction_val_percent_pay;
                        }

                        $conversion_valeur_end = $this->searchTransaction->calculateConversionValeurEnd($default_valeur);

                        if ($conversion_valeur_end < $reel_amount) {
                            $commission_transaction_val_nat_pay = "Montant maximal est : " . number_format($conversion_valeur_end, 2, ',', ' ');
                        }
                    }
                }

                return response()->json([
                    'success' => $data != null ? true : false,
                    'data' => [
                        'amount_pay' => $commission_transaction_val_percent_pay,
                        'amount_send' => $commission_transaction_val_nat_pay,
                        'amount_receive' => $commission_transaction_val_nat_receive,
                    ],
                ], 200);
            } else {
                return response()->json([
                    'success' =>  false,
                    'message' => 'Pas de montant',
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
