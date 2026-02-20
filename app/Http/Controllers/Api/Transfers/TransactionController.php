<?php

namespace App\Http\Controllers\Api\Transfers;


use App\Http\Controllers\Admin\MailController;
use App\Http\Controllers\Controller;
use App\Http\Services\AuthenticationService;
use App\Http\Services\CreateRobusWalletWithDrawalService;
use App\Http\Services\DeletedService;
use App\Http\Services\MobileWalletTransactionService;
use App\Http\Services\NotificationSendService;
use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\RobusTransactionService;
use App\Http\Services\SearchTransactionService;
use App\Http\Services\ValidateDailyLimitService;
use App\Mail\CodeValidated;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletTransaction;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Traits\InitializeAppStatusTrait;


class TransactionController extends Controller
{

    use InitializeAppStatusTrait;

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
    private $authService;
    protected $appStatusResult;

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
        AuthenticationService $authService
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
        $this->authService = $authService;
        $this->initializeAppStatus($this->authService);
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

                if (robus()->is_under_distributer || robus()->is_api) {

                    $percent_robus = $this->percentRobus->getRobusPercentageSDistributers();

                    // Vérifier si l'utilisateur est un client et non un distributeur, un administrateur, ou un distributeur
                    $isCustomer = optional($wallet_search->sidUser)->is_custumers;
                    $isNotDistributor = !optional($wallet_search->sidUser)->is_under_distributer;
                    $isNotAdmin = !optional($wallet_search->sidUser)->is_admin;
                    $isNotDistributor = !optional($wallet_search->sidUser)->is_distributer;

                    if ($isCustomer && $isNotDistributor && $isNotAdmin && $isNotDistributor) {


                        if ($percent_robus !== null) {

                            if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                                if ($this->validatedamountDays->validateAmount($request->amount)) {

                                    $percent_amount = $percent_robus->percent * $request->amount;

                                    // $percent_real = adjustFeesWithRest($percent_amount , $request->amount);

                                    $valeur_received = $request->amount -  $percent_amount;

                                    // Update Info valeurs
                                    $walletAmountSend = Wallet::where('wallet_id',  $request->num_wallet)->first();

                                    $amount_solde_drawal = (float)$walletAmountSend->amount + (float)$valeur_received;

                                    $amount_solde_drawal_round = round((float)$walletAmountSend->amount + (float)$valeur_received, 2);


                                    $walletTransaction =  $this->robusTransactionService->createRobusWalletTransaction($request->amount, $percent_amount, $percent_robus, $request->provider, $request->num_wallet, $amount_solde_drawal, $walletAmountSend->amount);

                                    if ($walletTransaction != null) {

                                        $message = 'Vous venez de recevoir un montant de ' . $request->amount . ' de la part de ' . robus()->firstname . ' ' . robus()->lastname . ' votre nouveau solde est de ' . $amount_solde_drawal_round . $wallet_search->currencie->name . "\nROBUS VOUS REMERCIE POUR VOTRE FIDELITE";

                                        if ($wallet_search->sidUser->country->name_groupe == 'CIV') {

                                            sendTryCashMessage($wallet_search->sidUser->phone, $message);
                                        }

                                        if ($wallet_search->sidUser->email != null) {

                                            Mail::to($wallet_search->sidUser->email)->send(new CodeValidated('Message dépot : '  . $message));
                                        }

                                        if ($this->robusTransactionService->updateAmountRobusReceived($request->num_wallet, $walletTransaction, $valeur_received)) {
                                            if ($this->robusTransactionService->processCommissionsDistributerRobus($request->amount, $percent_robus, $walletTransaction, $admin_civ)) {
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
                                    return json_message_response(false, "Veuillez verifier votre solde", 341);
                                }
                            } else {
                                return json_message_response(false, "Votre montant journalier est atteint !!", 350);
                            }
                        } else {
                            return  json_message_response(false, "Niveau de pourcentage introuvable !", 403);
                        }
                    } else {


                        if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                            if ($this->validatedamountDays->validateAmount($request->amount)) {

                                $valeur_received = $request->amount;

                                // Update Info valeurs
                                $walletAmountSend = Wallet::where('wallet_id',  $request->num_wallet)->first();

                                $amount_solde_drawal = (float)$walletAmountSend->amount + (float)$valeur_received;

                                $amount_solde_drawal_round = round((float)$walletAmountSend->amount + (float)$valeur_received, 2);

                                $walletTransaction =  $this->robusTransactionService->createRobusWalletTransactionDistributer($request->amount, $percent_robus, $request->provider, $request->num_wallet, $amount_solde_drawal, $walletAmountSend->amount);

                                if ($walletTransaction != null) {

                                    $message = 'Vous venez de recevoir un montant de ' . $request->amount . ' de la part de ' . robus()->firstname . ' ' . robus()->lastname . ' votre nouveau solde est de ' . $amount_solde_drawal_round . $wallet_search->currencie->name . "\nROBUS VOUS REMERCIE POUR VOTRE FIDELITE";

                                    if ($wallet_search->sidUser->country->name_groupe == 'CIV') {

                                        sendTryCashMessage($wallet_search->sidUser->phone, $message);
                                    }

                                    if ($wallet_search->sidUser->email != null) {

                                        Mail::to($wallet_search->sidUser->email)->send(new CodeValidated('Message dépot : '  . $message));
                                    }

                                    if ($this->robusTransactionService->updateAmountRobusReceivedDistributer($request->num_wallet, $walletTransaction, $valeur_received)) {
                                        if ($this->robusTransactionService->processCommissionsRobusDistributer($request->amount)) {
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
                    $isCustomerStatusFalse =   optional($wallet_search->sidUser)->status === false;
                    $isSameWalletAsUser = $request->num_wallet === $wallet_user->wallet->wallet_id;
                    $isAdmin_all =   optional($wallet_search->sidUser)->is_admin === true;
                    // Is custumers
                    if ($isWalletSearchNull  || $isCustomerStatusFalse || $isSameWalletAsUser || $isAdmin_all) {

                        return response()->json(
                            [
                                'success' => false,
                                'message' => 'La demande ne peut pas être traitée : le numéro saisi est invalide, correspond à votre propre numéro ou appartient à un compte non autorisé.',
                            ],
                            333
                        );
                    } else {


                        if ($percent_robus !== null) {

                            if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                                if ($this->validatedamountDays->validateAmount($request->amount)) {

                                    $walletAmountSend = Wallet::where('wallet_id',  $request->num_wallet)->first();

                                    $is_unders = $walletAmountSend->sidUser->is_under_distributer;

                                    $percent_amount = $is_unders ? $percent_robus->percent_under_scanner * $request->amount : $percent_robus->percent * $request->amount;

                                    $valeur_received = (float) $request->amount - $percent_amount;

                                    // Update Info valeurs

                                    $amount_solde_drawal = (float)$walletAmountSend->amount + (float)$valeur_received;

                                    $amount_solde_drawal_round = round((float)$walletAmountSend->amount + (float)$valeur_received, 2);

                                    $walletTransaction = $this->robusTransactionService->createRobusWalletPaiementTransaction($request->amount, $percent_amount, $percent_robus, $request->provider, $request->num_wallet, $amount_solde_drawal, $walletAmountSend->amount , $is_unders);

                                    if ($walletTransaction != null) {

                                        $message = 'Vous venez de recevoir un montant de ' . $request->amount . ' de la part de ' . robus()->firstname . ' ' . robus()->lastname . ' votre nouveau solde est de ' . $amount_solde_drawal_round . $wallet_search->currencie->name . "\nROBUS VOUS REMERCIE POUR VOTRE FIDELITE";

                                        if ($wallet_search->sidUser->country->name_groupe == 'CIV') {

                                            sendTryCashMessage($wallet_search->sidUser->phone, $message);
                                        }

                                        if ($wallet_search->sidUser->email != null) {

                                            Mail::to($wallet_search->sidUser->email)->send(new CodeValidated('Message dépot : '  . $message));
                                        }

                                        if ($this->robusTransactionService->updateAmountRobusReceived($request->num_wallet, $walletTransaction, $valeur_received)) {
                                            if ($this->robusTransactionService->processCommissionsCustumerRobus($request->amount, $percent_robus, $walletTransaction, $admin_civ)) {
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

                if (robus()->is_under_distributer || robus()->is_api) {


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
                    } elseif (robus()->country->name_groupe == 'MAG') {

                        $percent_robus = $this->percentRobus->getRobusPercentageMobileMaroc();
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

                if ($percent_robus !== null) {

                    if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                        if ($this->validatedamountDays->validateAmountSendLimit($request->amount, $valeur_max_send)) {

                            $percent_amount = $percent_robus->percent * $request->amount;

                            if (robus()->is_under_distributer || robus()->is_api) {


                                if (robus()->country->name_groupe == 'CIV') {

                                    $valeur_received = $request->amount;

                                    $amount_payment = $request->amount;
                                } else {

                                    $valeur_received = $request->amount - $percent_amount;

                                    $amount_payment =  $request->amount + $percent_amount;
                                }

                                $cost_payment = $percent_amount;
                            } else {

                                $valeur_received = $request->amount - $percent_amount;

                                $cost_payment = $percent_amount;

                                $amount_payment =  $request->amount + $percent_amount;
                            }

                            if ($this->validatedamountDays->validateAmount($amount_payment)) {

                                $walletTransaction =  $this->mobileWalletTransactionService->createRobusWalletTransactionDistributer($percent_robus, $request->provider, $request->amount, $request->date_piece, $request->num_piece_custumer, $request->name_custumer, $cost_payment, $request->phone, 1);

                                if ($walletTransaction != null) {

                                    if ($this->mobileWalletTransactionService->updateAmountRobusReceivedMobile($walletTransaction, $valeur_received)) {

                                        if (robus()->is_under_distributer || robus()->is_api) {
                                            $processCommission = $this->mobileWalletTransactionService->processCommissionsMobileDistributer($request->amount, $percent_robus, $walletTransaction, $admin_civ);
                                        } else {

                                            $processCommission = $this->mobileWalletTransactionService->processCommissionsMobile($request->amount, $percent_robus, $walletTransaction, $admin_civ);
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

    public function robus_withdrawal(Request $request)
    {

        $wallet_user = Auth::guard('api')->user();
        $amount_days = $this->validatedamountDays->getCeilingProperty();
        $admin_civ = $this->validatedamountDays->adminCiv();
        $valeur_ceiling  =  $wallet_user->silver_ceiling;
        $admins = $this->validatedamountDays->adminAllcountries();

        try {

            if (robus()->is_under_distributer || robus()->is_api) {

                if ($request->provider == 128) {


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

                    // 1️⃣ Wallet inexistant
                    if (!$wallet_search) {
                        return response()->json([
                            'success' => false,
                            'message' => "Ce wallet n'existe pas ou n'est pas ROBUS",
                        ], 404);
                    }

                    // 2️⃣ Utilisateur inexistant ou invalide
                    if (
                        !$wallet_search ||
                        !optional($wallet_search->sidUser)->is_custumers ||
                        !optional($wallet_search->sidUser)->status ||
                        $request->num_wallet === $wallet_user->wallet->wallet_id
                    ) {
                        return response()->json([
                            'success' => false,
                            'message' => "Ce numéro n'est pas ROBUS, est invalide ou vous appartient",
                        ], 403);
                    }

                    // 3️⃣ Empêcher l’auto-transfert
                    if ($request->num_wallet === $wallet_user->wallet->wallet_id) {
                        return response()->json([
                            'success' => false,
                            'message' => "Vous ne pouvez pas effectuer une transaction vers votre propre wallet",
                        ], 400);
                    }
                    // Wallet verify
                    if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                        if ($this->validatedamountDays->validateAmountDrawel($request->amount, $request->num_wallet)) {

                            $walletTransaction = $this->transactionWalletDrawal->createRobusWalletWithDrawalRobusTransaction($request->amount, $request->provider, $request->num_wallet);

                            if ($walletTransaction != null) {

                                $percent_robus = $this->percentRobus->getRobusPercentageSDistributers();

                                if ($percent_robus != null) {

                                    $valuer_with_drawal = $percent_robus->percent_with_drawal * $walletTransaction->amount;

                                    $walletTransaction->update([
                                        'type_transaction' => 'Retrait',
                                        'type_received' => 'Depot',
                                        'status' => 'SUCCESS',
                                        'costs_amount' => 0,
                                        'code_validated' => null,
                                        'created_by' => robus()->id,
                                    ]);


                                    $walletAmountSend = Wallet::where('wallet_id',  $request->num_wallet)->first();

                                    $amount_solde_drawal = (float)$walletAmountSend->amount - (float)$walletTransaction->amount;

                                    $walletUserSearch = $this->validatedamountDays->validatedWallet($request->num_wallet);

                                    if ($this->transactionWalletDrawal->updateDrawalTransaction($walletTransaction, $walletUserSearch, $valuer_with_drawal, $percent_robus, $admin_civ)) {

                                        $message = 'Vous venez d\'effectuer un retrait d\'un montant de ' . $walletTransaction->amount . $wallet_search->currencie->code . ' chez ' . robus()->firstname . ' ' . robus()->lastname . ' votre nouveau solde est de ' . $amount_solde_drawal . $wallet_search->currencie->code . "\nROBUS VOUS REMERCIE POUR VOTRE FIDELITE";

                                        if ($wallet_search->sidUser->country->name_groupe == 'CIV') {

                                            sendTryCashMessage($wallet_search->sidUser->phone, $message);
                                        }

                                        if ($wallet_search->sidUser->email != null) {

                                            Mail::to($wallet_search->sidUser->email)->send(new CodeValidated('Message rétrait : '  . $message));
                                        }

                                        return $this->transactionWalletDrawal->sendNotificationWithDrawal($walletTransaction, $walletUserSearch, $admins);
                                    } else {

                                        return  json_message_response(false, "Erreur de demande de retait ! ", 333);
                                    }
                                } else {
                                    return  json_message_response(false, "Pas de retrait disponible dans cette zone ! ", 333);
                                }
                            } else {

                                return  json_message_response(false, "Error de creation de transfert du montant!", 333);
                            }
                        } else {
                            return   json_message_response(false, "Verifie votre solde de retrait !", 333);
                        }
                    } else {
                        return   json_message_response(false, "Votre montant journalier est atteint !", 333);
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
    public function mooney_withdrawal(Request $request)
    {
        $wallet_user = Auth::guard('api')->user();
        $amount_days = $this->validatedamountDays->getCeilingProperty();
        $admin_civ = $this->validatedamountDays->adminCiv();
        $valeur_ceiling  =  $wallet_user->silver_ceiling;
        $valeur_max_send  =  robus()->country->amount_max_send;


        try {


            if (robus()->is_under_distributer || robus()->is_api) {

                $validator = Validator::make(

                    $request->all(),
                    [
                        "phone" => 'required',
                        "provider" => 'required',
                        "amount" => 'required',
                    ],
                    [

                        'phone.required' => 'Votre numero est obligatoire',
                        'provider.required' => 'Votre operateur est obligatoire',
                        'amount.required' => 'Votre montant est obligatoire',

                    ]
                );
            } else {

                $validator = Validator::make(

                    $request->all(),
                    [
                        "phone" => 'required',
                        "provider" => 'required',
                        "amount" => 'required',
                    ],
                    [

                        'phone.required' => 'Votre numero est obligatoire',
                        'provider.required' => 'Votre operateur est obligatoire',
                        'amount.required' => 'Votre montant est obligatoire',

                    ]
                );
            }



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

                $percent_robus = $this->percentRobus->getRobusPercentageDrawal($request->provider);
            } else {

                return  json_message_response(false, "Pa de Transfert Mobile Money dans la zone !", 333);
            }

            if ($percent_robus !== null) {

                if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                    if ($this->validatedamountDays->validateAmountSendLimit($request->amount, $valeur_max_send)) {


                        $percent_amount = (robus()->is_under_distributer || robus()->is_api) ? $percent_robus->percent_with_drawal * $request->amount : $percent_robus->percent_custum_drawal * $request->amount;

                        if (robus()->is_under_distributer || robus()->is_api) {


                            if (robus()->country->name_groupe == 'CIV') {

                                $valeur_received = $request->amount;
                            } else {

                                $valeur_received = $request->amount - $percent_amount;
                            }

                            $cost_payment = $percent_amount;
                        } else {

                            $valeur_received = $request->amount - $percent_amount;

                            $cost_payment = $percent_amount;
                        }
                        $file_front = $request->file('image');
                        $file_backend = $request->file('image_bakend');
                        $imageBackendPath = null;

                        $imagePath = null;

                        if ($file_front) {
                            // Chemin de destination
                            $destination_path = public_path('transactions/' . robus()->id . '/');

                            // Création du dossier si inexistant
                            if (!file_exists($destination_path)) {
                                mkdir($destination_path, 0777, true);
                            }

                            // Générer un nom unique
                            if ($file_front) {

                                $front_file = uniqid('front_') . '.' . $file_front->getClientOriginalExtension();
                                $file_front->move($destination_path, $front_file);

                                $imagePath = "/transactions/" . robus()->id .  "/$front_file";
                            }

                            // 👉 Image verso
                            if ($file_backend) {
                                $backend_file = uniqid('back_') . '.' . $file_backend->getClientOriginalExtension();
                                $file_backend->move($destination_path, $backend_file);

                                $imageBackendPath = "/transactions/" . robus()->id .  "/$backend_file";
                            }
                        }


                        $walletTransaction =  $this->mobileWalletTransactionService->createRobusDrawalWalletTransactionDistributer($percent_robus, $request->provider, $request->amount, $request->date_piece, $request->name_custumer, $cost_payment, $request->phone, 2, $request->type_piece, $request->num_piece, $request->reference ,   $imagePath,
                                $imageBackendPath );

                        if ($walletTransaction != null) {

                            if ($this->mobileWalletTransactionService->updateAmountRobusReceivedMobile($walletTransaction, $valeur_received)) {

                                if (robus()->is_under_distributer || robus()->is_api) {
                                    $processCommission = $this->mobileWalletTransactionService->processCommissionsDrawalDistributer($request->amount, $percent_robus, $walletTransaction, $admin_civ);
                                } else {

                                    $processCommission = $this->mobileWalletTransactionService->processCommissionsDrawalMobile($request->amount, $percent_robus, $walletTransaction, $admin_civ);
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


                        return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . robus()->country->currencie->code, 333);
                    }
                } else {
                    return   json_message_response(false, "Votre montant journalier est atteint !", 333);
                }
            } else {
                return json_message_response(false, "Niveau de pourcentage introuvable", 333);
            }
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
                // 'phone' => $walletTransaction->phone,
                // 'num_wallet' => $walletTransaction->num_wallet,
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
            if (robus()->is_under_distributer || robus()->is_api) {

                if ($request->provider == 128) {


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
                    $wallet_search = $this->robusTransactionService->searchWalletRobus($request->num_wallet);

                    // 1️⃣ Wallet inexistant
                    if (!$wallet_search) {
                        return response()->json([
                            'success' => false,
                            'message' => "Ce wallet n'existe pas ou n'est pas ROBUS",
                        ], 404);
                    }

                    // 2️⃣ Utilisateur inexistant ou invalide
                    if (
                        !$wallet_search ||
                        !optional($wallet_search->sidUser)->is_custumers ||
                        !optional($wallet_search->sidUser)->status ||
                        $request->num_wallet === $wallet_user->wallet->wallet_id
                    ) {
                        return response()->json([
                            'success' => false,
                            'message' => "Ce numéro n'est pas ROBUS, est invalide ou vous appartient",
                        ], 403);
                    }

                    // 3️⃣ Empêcher l’auto-transfert
                    if ($request->num_wallet === $wallet_user->wallet->wallet_id) {
                        return response()->json([
                            'success' => false,
                            'message' => "Vous ne pouvez pas effectuer une transaction vers votre propre wallet",
                        ], 400);
                    }
                    // Wallet verify
                    if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                        if ($this->validatedamountDays->validateAmountDrawel($request->amount, $request->num_wallet)) {

                            $walletTransaction = $this->transactionWalletDrawal->createRobusWalletWithDrawalTransaction($request->amount, $request->provider, $request->num_wallet,  $wallet_search);

                            if ($walletTransaction != null) {

                                return response()->json([
                                    'success' => true,
                                    'data' => [
                                        'id'      => $walletTransaction->id,
                                        'provider' => $walletTransaction->provider->name,
                                        'amount' => $walletTransaction->amount,
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

            $wallet_search = $this->robusTransactionService->searchWalletRobus($request->num_wallet);


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

                        if (robus()->is_under_distributer || robus()->is_api) {

                            $percent_robus = $this->percentRobus->getRobusPercentageSDistributers();

                            if ($percent_robus != null) {


                                $valuer_with_drawal = $percent_robus->percent_with_drawal * $verify_code->amount;

                                $verify_code->update([
                                    'type_transaction' => 'Retrait',
                                    'status' => 'SUCCESS',
                                    'costs_amount' => 0,
                                    'code_validated' => null,
                                    'created_by' => robus()->id,
                                ]);

                                $walletAmountSend = Wallet::where('wallet_id',  $request->num_wallet)->first();

                                $amount_solde_drawal = (float)$walletAmountSend->amount - (float)$verify_code->amount;

                                if ($this->transactionWalletDrawal->updateDrawalTransaction($verify_code, $walletUserSearch, $valuer_with_drawal, $percent_robus, $admin_civ)) {

                                    $message = 'Vous venez d\'effectuer un retrait d\'un montant de ' . $verify_code->amount . $wallet_search->currencie->code . ' chez ' . robus()->firstname . ' ' . robus()->lastname . ' votre nouveau solde est de ' . $amount_solde_drawal . $wallet_search->currencie->code . "\nROBUS VOUS REMERCIE POUR VOTRE FIDELITE";

                                    if ($wallet_search->sidUser->country->name_groupe == 'CIV') {

                                        sendTryCashMessage($wallet_search->sidUser->phone, $message);
                                    }

                                    if ($wallet_search->sidUser->email != null) {

                                        Mail::to($wallet_search->sidUser->email)->send(new CodeValidated('Message rétrait : '  . $message));
                                    }

                                    return $this->transactionWalletDrawal->sendNotificationWithDrawal($verify_code, $walletUserSearch, $admins);
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

                $commission_transaction = $this->searchTransaction->getCommissionTransactionMobileForCountryGroup(robus()->country->name_groupe, robus()->is_under_distributer);
                $commission_transaction_val_percent_pay = 0;
                $commission_transaction_val_nat_pay = 0;

                if ($commission_transaction) {

                    $commission_transaction_val_percent_pay = (float)$commission_transaction->percent * $amount_transaction;

                    $percent_real = adjustFeesWithRest($commission_transaction_val_percent_pay, $amount_transaction);


                    if (robus()->is_under_distributer || robus()->is_api) {

                        if (robus()->country->name_groupe == 'MAG') {

                            $commission_transaction_val_nat_receive = (float)$amount_transaction - (float) $commission_transaction_val_percent_pay;
                        } else {

                            $commission_transaction_val_nat_receive = (float)$amount_transaction;
                        }

                        $commission_transaction_val_nat_pay = (float)$commission_transaction->percent_deposit * (float)$amount_transaction;
                    } else {

                        $commission_transaction_val_nat_pay =  $commission_transaction_val_percent_pay;

                        $commission_transaction_val_nat_receive = (float)$amount_transaction - $commission_transaction_val_percent_pay;
                    }

                    $conversion_valeur_end = $this->searchTransaction->calculateConversionValeurEnd($default_valeur);

                    if ($conversion_valeur_end < $amount_transaction) {
                        $commission_transaction_val_nat_pay = "Montant maximal est : " . number_format($conversion_valeur_end, 2, ',', ' ');
                    }
                }


                return response()->json([
                    'success' => true,
                    'data' => [
                        'amount_pay' => $commission_transaction_val_nat_pay,
                        'amount_send' => $commission_transaction_val_percent_pay,
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

    public function search_transaction_drawal_robus($amount_transaction)
    {


        try {

            if ($amount_transaction != '') {


                $commission_transaction = $this->percentRobus->getRobusPercentageSDistributers();
                $commission_transaction_val_percent_pay = 0;
                $commission_transaction_val_nat_pay = 0;

                if ($commission_transaction) {

                    $commission_transaction_val_percent_pay_custumer = (float)$commission_transaction->percent_custum_drawal * $amount_transaction;

                    $percent_real = adjustFeesWithRest($commission_transaction_val_percent_pay_custumer, $amount_transaction);

                    $commission_transaction_val_nat_receive = (float)$amount_transaction - $commission_transaction_val_percent_pay_custumer;

                    $commission_transaction_val_percent_pay = (float)$commission_transaction->percent_with_drawal * $commission_transaction_val_nat_receive;
                }


                return response()->json([
                    'success' => true,
                    'data' => [
                        'amount_pay' => $commission_transaction_val_percent_pay,
                        'amount_send' => $commission_transaction_val_percent_pay_custumer,
                        'amount_receive' => $commission_transaction_val_nat_receive,
                    ],
                ], 200);
            } else {
                return response()->json([
                    'success' =>  false,
                    'message' => 'Saisie le montant SVP',
                ], 200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' =>  false,
                'message' => 'Pas d\'utilisateur',
            ], 200);
        }
    }

    public function search_transaction_drawal_received_robus($amount_transaction)
    {


        try {

            if ($amount_transaction != '') {

                $commission_transaction = $this->percentRobus->getRobusPercentageSDistributers();
                $commission_transaction_val_percent_pay = 0;
                $commission_transaction_val_nat_pay = 0;

                if ($commission_transaction) {


                    $commission_transaction_val_percent_pay_custumer = (float)$commission_transaction->percent_custum_drawal * $amount_transaction;

                    $percent_real = adjustFeesWithRest($commission_transaction_val_percent_pay_custumer, $amount_transaction);

                    $commission_transaction_val_nat_receive = (float)$amount_transaction + ($percent_real['adjust'] == 0 ?  $percent_real['rest'] : $percent_real['adjust']);

                    $commission_transaction_val_percent_pay = (float)$commission_transaction->percent_with_drawal * $commission_transaction_val_nat_receive;
                }


                return response()->json([
                    'success' => true,
                    'data' => [
                        'amount_pay' => $commission_transaction_val_percent_pay,
                        'amount_send' => ($percent_real['adjust'] == 0 ? $percent_real['rest'] : $percent_real['adjust']),
                        'amount_receive' => $commission_transaction_val_nat_receive,
                    ],
                ], 200);
            } else {
                return response()->json([
                    'success' =>  false,
                    'message' => 'Saisie le montant SVP',
                ], 200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' =>  false,
                'message' => 'Pas d\'utilisateur',
            ], 200);
        }
    }

    public function search_transaction_money_commission_received($amount_transaction)
    {


        $default_valeur = 2000000;


        try {


            if ($amount_transaction != '') {

                $commission_transaction = $this->searchTransaction->getCommissionTransactionMobileForCountryGroup(robus()->country->name_groupe, robus()->is_under_distributer);
                $commission_transaction_val_percent_pay = 0;
                $commission_transaction_val_nat_pay = 0;

                if ($commission_transaction) {

                    $commission_transaction_val_percent_pay = (float)$commission_transaction->percent * $amount_transaction;

                    $percent_real = adjustFeesWithRest($commission_transaction_val_percent_pay, $amount_transaction);

                    if (robus()->is_under_distributer || robus()->is_api) {

                        if (robus()->country->name_groupe == 'MAG') {

                            $commission_transaction_val_nat_receive =  $percent_real['amountSend'] + ($percent_real['adjust'] == 0 ? $percent_real['adjust'] : $percent_real['rest']);
                        } else {

                            $commission_transaction_val_nat_receive = (float)$amount_transaction;
                        }

                        $commission_transaction_val_nat_pay = (float)$commission_transaction->percent_deposit * $percent_real['amountSend'];
                    } else {

                        $commission_transaction_val_nat_pay = $percent_real['adjust'] == 0 ? $percent_real['adjust'] : $percent_real['rest'];

                        $commission_transaction_val_nat_receive = $percent_real['amountSend'] + $commission_transaction_val_nat_pay;
                    }



                    $conversion_valeur_end = $this->searchTransaction->calculateConversionValeurEnd($default_valeur);

                    if ($conversion_valeur_end < $amount_transaction) {
                        $commission_transaction_val_nat_pay = "Montant maximal est : " . number_format($conversion_valeur_end, 2, ',', ' ');
                    }
                }


                return response()->json([
                    'success' => true,
                    'data' => [
                        'amount_pay' => $commission_transaction_val_nat_pay,
                        'amount_send' => ($percent_real['adjust'] == 0 ? $percent_real['rest'] : $percent_real['adjust']),
                        'amount_receive' => $commission_transaction_val_nat_receive,
                    ],
                ], 200);
            } else {
                return response()->json([
                    'success' =>  false,
                    'message' => 'Saisie le montant SVP',
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

                $commission_transaction_val_nat_pay = 0;
                $commission_transaction_val_percent_pay = 0;
                $commission_transaction_val_nat_receive = (float)$amount_transaction;

                $isUnders = (bool) (robus()?->is_under_distributer || robus()?->is_api);

                // Si l'utilisateur est sous distributeur, admin ou distributeur
                if (($data->sidUser->is_under_distributer && $isUnders) || $data->sidUser->is_admin || $data->sidUser->is_distributer || ($data->sidUser->is_api && $isUnders)) {

                    $conversion_valeur_end = $this->searchTransaction->calculateConversionValeurEnd($default_valeur);

                    if ($conversion_valeur_end < $amount_transaction) {
                        $commission_transaction_val_nat_pay = "Montant maximal est : " . number_format($conversion_valeur_end, 2, ',', ' ');
                    }
                } else {


                    // Si l'utilisateur n'est pas sous distributeur, admin ou distributeur
                    $commission_transaction = $this->searchTransaction->getCommissionTransactionForCountryGroup(robus()->country->name_groupe, robus()->is_under_distributer);

                    $isUnders = (bool) (robus()?->is_under_distributer || robus()?->is_api);

                    if ($commission_transaction && $data?->sidUser?->is_custumers) {

                        $commission_transaction_val_percent_pay = (float)$commission_transaction->percent * $amount_transaction;
                        $commission_transaction_val_nat_pay = $isUnders ? (float)$commission_transaction->percent_deposit * (float) $commission_transaction_val_percent_pay : (float)$commission_transaction->percent_deposit * $amount_transaction;
                        $commission_transaction_val_nat_receive = (float)$amount_transaction - (float) $commission_transaction_val_percent_pay;
                    } else if ($commission_transaction && $data?->sidUser?->is_under_distributer) {

                        $commission_transaction_val_percent_pay = (float)$commission_transaction->percent_under_scanner * $amount_transaction;
                        $commission_transaction_val_nat_pay = $isUnders ? (float)$commission_transaction->percent_deposit * (float) $commission_transaction_val_percent_pay : (float)$commission_transaction->percent_deposit * $amount_transaction;
                        $commission_transaction_val_nat_receive = (float)$amount_transaction - (float) $commission_transaction_val_percent_pay;
                    }

                    $conversion_valeur_end = $this->searchTransaction->calculateConversionValeurEnd($default_valeur);

                    if ($conversion_valeur_end < $amount_transaction) {
                        $commission_transaction_val_nat_pay = "Montant maximal est : " . number_format($conversion_valeur_end, 2, ',', ' ');
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

    public function search_transaction_commission_receved_robus($wallet, $amount_transaction)
    {

        $default_valeur = 2000000;

        // valeur amount virement bank

        try {


            $data = $this->searchTransaction->searchTransactionWallet($wallet);

            // dd(  $data);
            if ($amount_transaction != '') {

                $commission_transaction_val_nat_pay = 0;
                $commission_transaction_val_percent_pay = 0;
                $commission_transaction_val_nat_receive = (float)$amount_transaction;

                // Si l'utilisateur est sous distributeur, admin ou distributeur
                if ($data->sidUser->is_under_distributer || $data->sidUser->is_admin || $data->sidUser->is_distributer) {

                    $conversion_valeur_end = $this->searchTransaction->calculateConversionValeurEnd($default_valeur);

                    if ($conversion_valeur_end < $amount_transaction) {
                        $commission_transaction_val_nat_pay = "Montant maximal est : " . number_format($conversion_valeur_end, 2, ',', ' ');
                    }
                } else {


                    // Si l'utilisateur n'est pas sous distributeur, admin ou distributeur
                    $commission_transaction = $this->searchTransaction->getCommissionTransactionForCountryGroup(robus()->country->name_groupe, robus()->is_under_distributer);
                    $commission_transaction_val_percent_pay = (float)$commission_transaction->percent * $amount_transaction;
                    $percent_real = adjustFeesWithRest($commission_transaction_val_percent_pay, $amount_transaction);

                    if ($commission_transaction) {

                        if ($data->sidUser->is_custumers) {

                            $commission_transaction_val_nat_pay = (float)$commission_transaction->percent_deposit * $percent_real['amountSend'];
                            $commission_transaction_val_nat_receive = $percent_real['amountSend'] + ($percent_real['adjust'] == 0 ? $percent_real['adjust'] : $percent_real['rest']);
                        }

                        $conversion_valeur_end = $this->searchTransaction->calculateConversionValeurEnd($default_valeur);

                        if ($conversion_valeur_end < $amount_transaction) {
                            $commission_transaction_val_nat_pay = "Montant maximal est : " . number_format($conversion_valeur_end, 2, ',', ' ');
                        }
                    }
                }

                return response()->json([
                    'success' => $data != null ? true : false,
                    'data' => [
                        'amount_pay' => ($percent_real['adjust'] == 0 ? $percent_real['rest'] : $percent_real['adjust']),
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
