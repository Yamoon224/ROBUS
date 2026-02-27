<?php

namespace App\Http\Controllers\Api\Transfers;

use App\Http\Controllers\Controller;
use App\Http\Services\AuthenticationService;
use App\Http\Services\ConvertionService;
use App\Http\Services\DeletedService;
use App\Http\Services\InterPaymentService;
use App\Http\Services\NotificationSendService;
use App\Http\Services\PaymentInterService;
use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\PhoneCreditInterService;
use App\Http\Services\SearchTransactionService;
use App\Http\Services\ValidateDailyLimitService;
use App\Http\Services\VirementInterService;
use App\Http\Services\WithInterService;
use App\Models\AppStatus;
use App\Models\Costs\BankCost;
use App\Models\Costs\DepositCost;
use App\Models\Costs\PaymentCost;
use App\Models\Costs\PhoneCreditCost;
use App\Models\Costs\TransactionCost;
use App\Models\Custumers\Country;
use App\Models\Offer;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletInter;
use App\Repositories\IntersRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Traits\InitializeAppStatusTrait;

class IntersPaymentController extends Controller
{

    use InitializeAppStatusTrait;

    /** @var  IntersRepository */
    protected $intersRepository;
    protected $deletedService;
    protected $percentRobus;
    protected $validatedamountDays;
    protected $notificationPayment;
    protected $interServices;
    protected $paymentServices;
    protected $paymentCreditService;
    protected $convertionservice;
    protected $virementInterService;
    protected $withInterService;
    protected $searchTransaction;
    private $authService;
    protected $appStatusResult;

    public function __construct(
        IntersRepository $inter_Reposi,
        DeletedService $deletedService,
        PercentRobusAuthService $percentRobus,
        ValidateDailyLimitService $validatedamountDays,
        InterPaymentService $interServices,
        PaymentInterService $paymentServices,
        WithInterService $withInterService,
        PhoneCreditInterService $paymentCreditService,
        ConvertionService $convertionservice,
        NotificationSendService $notificationPayment,
        VirementInterService $virementInterService,
        SearchTransactionService $searchTransaction,
        AuthenticationService $authService

    ) {
        $this->intersRepository = $inter_Reposi;
        $this->deletedService = $deletedService;
        $this->percentRobus = $percentRobus;
        $this->validatedamountDays = $validatedamountDays;
        $this->interServices = $interServices;
        $this->paymentServices = $paymentServices;
        $this->paymentCreditService = $paymentCreditService;
        $this->convertionservice = $convertionservice;
        $this->notificationPayment = $notificationPayment;
        $this->virementInterService = $virementInterService;
        $this->searchTransaction = $searchTransaction;
        $this->withInterService = $withInterService;
        $this->authService = $authService;
        $this->initializeAppStatus($this->authService);
    }



    /**
     * @OA\POST(
     *      path="/user/inters/store",
     *      tags={"Inter transfer User"},
     *      summary="Create new inter  transfer user",
     *      description="Faire des tarnsfers a l'internationnal 
     *       Selon les id des providers nous faisons nos verifications.
     *        Si provider_id =  9 (Robus)  nous verifions si le clients le numero n'est pas du clients pour eviter les rechargement de son compte.
     *                  Le client saisie le numero robus(9 chiffres) number_compte , sont montant(amount) et le pays,
     *                  le numero doit etre de robus. 
     *        Si provider_id =  1 (MTN) ou 4(Orange)  ou 3(Moov)  ou 6(Wave) le clients saisie le numero de telephone a l'internationnal , le montant et selectionnez le pays.
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
     *          description="success Pour un exemple sur une Paiement en CIV avec un numero robus",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="provider", type="string", example="9"),
     *              @OA\Property(property="wallet_id", type="string", example="CIAZE3SD"),
     *              @OA\Property(property="description", type="string", example="Demande de transfers a l'inter"),
     *              @OA\Property(property="amount", type="number", example="1000"),
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
     *                          property="number_compte",
     *                          type="string"
     *                      ),
     *                    @OA\Property(
     *                          property="amount",
     *                          type="number"
     *                      ),
     *                 @OA\Property(
     *                          property="country",
     *                          type="integer"
     *                      ),
     *               @OA\Property(
     *                          property="description",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={
     *
     *                     "provider":"9",
     *                     "number_compte":"CIAZE3SD",
     *                     "amount":"1000",
     *                     "country" : 107,
     *                }
     *          
     *             )
     *         )
     *      ),
     *   @OA\Response(
     *          response=333,
     *          description="The number is not found or is not custumers."
     *      ),
     *  @OA\Response(
     *          response=341,
     *          description="Verifier votre solde."
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
    public function create_inter(Request $request)
    {

        $country_robus = Country::whereId($request->country)->first();
        $offers =  Offer::whereId($request->offer)->where('status', true)->first();
        $amount_days = $this->validatedamountDays->getCeilingProperty();
        $valeur_ceiling  =  robus()->silver_ceiling;
        $admin_civ = $this->validatedamountDays->adminCiv();

        try {

            // Is transaction Or Withdrawal
            if ($request->provider_type == 1) {


                $validationRules = [];
                $validationMessages = [];

                if ($request->provider == 51) {

                    $validationRules = [
                        'number_compte' => 'required',
                        'amount' => 'required',
                        'country' => 'required',
                    ];

                    $validationMessages = [
                        'number_compte.required' => 'Votre numero est obligatoire',
                        'amount.required' => 'Votre montant est obligatoire',
                        'country.required' => 'Votre pays est obligatoire',
                    ];
                } elseif (in_array($request->provider, [48, 49, 50, 55, 71, 72, 73, 74, 75, 76, 77, 78, 93, 94, 95, 96, 97, 98, 100, 101, 102, 103])) {

                    if (robus()->is_under_distributer || robus()->is_api) {


                        $validator = Validator::make(

                            $request->all(),
                            [
                                "phone" => 'required',
                                "amount" => 'required',
                                "name_custumer" => 'required',
                                'country' => 'required',
                            ],
                            [

                                'phone.required' => 'Votre numero est obligatoire',
                                'amount.required' => 'Votre montant est obligatoire',
                                'name_custumer.required' => 'Nom est obligatoire',
                                'country.required' => 'Votre pays est obligatoire',

                            ]
                        );

                        if ($validator->fails()) {
                            return response()->json(
                                [
                                    'success' => false,
                                    'message' => 'Verifiez les champs',
                                    'data' => $validator->errors()
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
                                'country' => 'required',
                                "name_custumer" => 'required',
                            ],
                            [

                                'phone.required' => 'Votre numero est obligatoire',
                                'amount.required' => 'Votre montant est obligatoire',
                                'country.required' => 'Votre pays est obligatoire',
                                'name_custumer.required' => 'Nom est obligatoire',

                            ]
                        );

                        if ($validator->fails()) {
                            return response()->json(
                                [
                                    'success' => false,
                                    'message' => 'Verifiez les champs',
                                    'data' => $validator->errors()
                                ],
                                333
                            );
                        }
                    }
                } elseif (in_array($request->provider, [99])) {

                    if (robus()->is_under_distributer || robus()->is_api) {


                        $validator = Validator::make(

                            $request->all(),
                            [
                                "phone" => 'required',
                                "amount" => 'required',
                                "name_custumer" => 'required',
                                "name_sender" => 'required',
                                "num_piece_custumer" => 'required',
                                "date_piece" => 'required',
                                'country' => 'required',
                                'account_number' => 'required',
                                'name' => 'required',
                            ],
                            [

                                'phone.required' => 'Votre numero est obligatoire',
                                'amount.required' => 'Votre montant est obligatoire',
                                'name_custumer.required' => 'Nom est obligatoire',
                                'name_sender.required' => 'Nom expediteur est obligatoire',
                                'num_piece_custumer.required' => 'Numero piece est obligatoire',
                                'date_piece.required' => 'date de validité est obligatoire',
                                'country.required' => 'Votre pays est obligatoire',
                                'account_number.required' => 'Numero de compte est obligatoire',
                                'name.required' => 'Nom de la banque est obligatoire',

                            ]
                        );

                        if ($validator->fails()) {
                            return response()->json(
                                [
                                    'success' => false,
                                    'message' => 'Verifiez les champs',
                                    'data' => $validator->errors()
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
                                'country' => 'required',
                                // 'account_number' => 'required',
                                // 'name' => 'required',
                            ],
                            [

                                'phone.required' => 'Votre numero est obligatoire',
                                'amount.required' => 'Votre montant est obligatoire',
                                // 'name_custumer.required' => 'Nom est obligatoire',
                                // 'num_piece_custumer.required' => 'Numero piece est obligatoire',
                                // 'date_piece.required' => 'date de validité est obligatoire',
                                'country.required' => 'Votre pays est obligatoire',
                                // 'account_number.required' => 'Numero de compte est obligatoire',
                                // 'name.required' => 'Nom de la banque est obligatoire',

                            ]
                        );

                        if ($validator->fails()) {
                            return response()->json(
                                [
                                    'success' => false,
                                    'message' => 'Verifiez les champs',
                                    'data' => $validator->errors()
                                ],
                                333
                            );
                        }
                    }
                } elseif (in_array($request->provider, [104, 105, 106])) {

                    if (robus()->is_under_distributer || robus()->is_api) {


                        $validator = Validator::make(

                            $request->all(),
                            [
                                "phone" => 'required',
                                "amount" => 'required',
                                'country' => 'required',
                                "name_custumer" => 'required',
                                'image_piece' => 'image|mimes:png,jpg,jpeg|max:102400',
                                'image_bakend' => 'image|mimes:png,jpg,jpeg|max:102400',
                            ],
                            [

                                'phone.required' => 'Votre numero est obligatoire',
                                'amount.required' => 'Votre montant est obligatoire',
                                'name_custumer.required' => 'Nom est obligatoire',
                                'country.required' => 'Votre pays est obligatoire',
                            ]
                        );

                        if ($validator->fails()) {
                            return response()->json(
                                [
                                    'success' => false,
                                    'message' => 'Verifiez les champs obligatoires',
                                    'data' => $validator->errors()
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
                                'country' => 'required',
                                'image_piece' => 'image|mimes:png,jpg,jpeg|max:102400',
                                'image_bakend' => 'image|mimes:png,jpg,jpeg|max:102400',

                            ],
                            [

                                'phone.required' => 'Votre numero est obligatoire',
                                'amount.required' => 'Votre montant est obligatoire',
                                'country.required' => 'Votre pays est obligatoire',

                            ]
                        );

                        if ($validator->fails()) {
                            return response()->json(
                                [
                                    'success' => false,
                                    'message' => 'Verifiez les champs',
                                    'data' => $validator->errors()
                                ],
                                333
                            );
                        }
                    }
                } else {
                    return   json_message_response(false, "Erreur de selection des providers !", 300);
                }

                $validator = Validator::make($request->all(), $validationRules, $validationMessages);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Quelque champs obligatoire',
                        'data' => $validator->errors()
                    ], 333);
                }

                $percent_robus =  $this->percentRobus->getRobusPercentageInter($country_robus, $request->provider);


                if ($request->provider == 51) {

                    if ($this->interServices->validateWallet($country_robus, $request->number_compte)) {

                        if ($percent_robus != null) {

                            if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                                if ($this->validatedamountDays->validateAmount($request->amount)) {

                                    if (robus()->is_under_distributer || robus()->is_api) {

                                        $percent_amount = $percent_robus->percent_under_distributer * $request->amount;

                                        $valeur_received = $request->amount -  $percent_amount;
                                    } else {

                                        $percent_amount = $percent_robus->percent * $request->amount;

                                        $valeur_received = $request->amount -  $percent_amount;
                                    }

                                    // Update Info valeurs
                                    $walletAmountSend = Wallet::where('wallet_id',  $request->number_compte)->first();

                                    $amount_solde_drawal = (float)$walletAmountSend->amount + (float)$valeur_received;

                                    $walletInter = $this->interServices->createRobusWalletInter($request->amount, $percent_robus, $country_robus, $request->provider, $request->number_compte, $request->provider_type, $amount_solde_drawal);

                                    if ($walletInter != null) {

                                        if ($this->interServices->updateAmountRobusReceived($walletInter, $country_robus, $valeur_received, $request->number_compte)) {
                                            if ($this->interServices->processCommissionsRobus($request->amount, $percent_robus, $walletInter)) {
                                                $this->interServices->sendNotificationRobus($walletInter);
                                                return $this->messageAfterValidated($walletInter);
                                            } else {

                                                return   json_message_response(false, "Error de transferts du montant !", 300);
                                            }
                                        } else {

                                            return   json_message_response(false, "Error de demande transferts  !", 300);
                                        }
                                    } else {
                                        return   json_message_response(false, "Error de demande transferts  !", 300);
                                    }
                                } else {

                                    return   json_message_response(false, "Verifier votre Solde SVP !", 300);
                                }
                            } else {

                                return   json_message_response(false, "Le  montant maxi jounalier est atteint !", 300);
                            }
                            // }
                        } else {
                            return   json_message_response(false, "Niveau de pourcentage introuvable !", 300);
                        }
                    } else {
                        return   json_message_response(false, "veuillez selectionnez le pays de destination ou le  Numero ne correspond pas au pays SVP !", 300);
                    }
                } elseif (in_array($request->provider, [48, 49, 50, 55, 71, 72, 73, 74, 75, 76, 77, 78, 93, 94, 95, 96, 97, 98, 99, 100, 101, 102, 103])) {

                    if ($this->interServices->validatePhone($request->phone)) {

                        if ($percent_robus != null) {

                            $valeur_max_send  =  $country_robus->amount_max_send;

                            if ($this->validatedamountDays->validateAmountSendLimit($request->amount, $valeur_max_send)) {

                                if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                                    if ($this->validatedamountDays->validateAmount($request->amount)) {

                                        if (robus()->is_under_distributer || robus()->is_api) {

                                            $percent_amount = $percent_robus->percent_under_distributer * $request->amount;

                                            $valeur_received = $request->amount -  $percent_amount;
                                        } else {

                                            $percent_amount = $percent_robus->percent * $request->amount;

                                            $valeur_received = $request->amount -  $percent_amount;
                                        }

                                        $file_front = $request->file('image_piece');
                                        $file_backend = $request->file('image_bakend');
                                        $imagePath = null;
                                        $imageBackendPath = null;

                                        if ($file_front || $file_backend) {
                                            // Chemin de destination
                                            $destination_path = public_path('image_inters/' . $request->name_custumer . '/');

                                            // Création du dossier si inexistant
                                            if (!file_exists($destination_path)) {
                                                mkdir($destination_path, 0777, true);
                                            }

                                            if ($file_front) {
                                                $front_file = uniqid('front_') . '.' . $file_front->getClientOriginalExtension();
                                                $file_front->move($destination_path, $front_file);

                                                $imagePath = env('APP_URL') . "/image_inters/" . $request->name_custumer . "/$front_file";
                                            }

                                            // 👉 Image verso
                                            if ($file_backend) {
                                                $backend_file = uniqid('back_') . '.' . $file_backend->getClientOriginalExtension();
                                                $file_backend->move($destination_path, $backend_file);

                                                $imageBackendPath = env('APP_URL') . "/image_inters/" . $request->name_custumer . "/$backend_file";
                                            }
                                        }



                                        $walletInter = $this->interServices->createMobileWalletInter(
                                            $request->amount,
                                            $percent_robus,
                                            $country_robus,
                                            $request->provider,
                                            $request->phone,
                                            $request->provider_type,
                                            $request->name_custumer,
                                            $request->num_piece_custumer,
                                            $request->date_piece,
                                            $request->name,
                                            $request->name_sender,
                                            $request->account_number,
                                            $imagePath,
                                            $imageBackendPath,
                                            $request->type_piece,

                                        );

                                        if ($walletInter != null) {

                                            if ($this->interServices->updateAmountReceived($walletInter, $country_robus, $valeur_received)) {

                                                if ($this->interServices->processCommissionMobiles($request->amount, $percent_robus, $country_robus, $walletInter, $admin_civ)) {
                                                    $this->interServices->sendNotificationRobus($walletInter);
                                                    return $this->messageAfterValidated($walletInter);
                                                } else {

                                                    return   json_message_response(false, "Error de transferts du montant !", 300);
                                                }
                                            } else {

                                                return   json_message_response(false, "Error de demande transferts  !", 300);
                                            }
                                        } else {
                                            return   json_message_response(false, "Error de demande transferts  !", 300);
                                        }
                                    } else {

                                        return   json_message_response(false, "Verifier votre Solde SVP !", 300);
                                    }
                                } else {

                                    return   json_message_response(false, "Le  montant maxi jounalier est atteint !", 300);
                                }
                            } else {

                                return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $country_robus->currencie->code, 333);
                            }
                        } else {
                            return   json_message_response(false, "Niveau de pourcentage introuvable !", 300);
                        }
                    } else {
                        return   json_message_response(false, "veuillez selectionnez le pays de destination SVP !", 300);
                    }
                } elseif (in_array($request->provider, [104, 105, 106])) {

                    if ($this->interServices->validatePhone($request->phone)) {

                        if ($request->provider == 104) {
                            $percent_djamo =  $this->percentRobus->getRobusPercentageDjamoInter();
                            $actionPercentId = $percent_djamo?->id;
                        } elseif ($request->provider == 105) {
                            $percent_djamo =  $this->percentRobus->getRobusPercentagePushInter();
                            $actionPercentId = $percent_djamo?->id;
                        } elseif ($request->provider == 106) {
                            $percent_djamo =  $this->percentRobus->getRobusPercentageTresortInter();
                            $actionPercentId = $percent_djamo?->id;
                        }

                        if ($percent_djamo != null) {

                            $valeur_max_send  =  $country_robus->amount_max_send;

                            if ($this->validatedamountDays->validateAmountSendLimit($request->amount, $valeur_max_send)) {

                                if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                                    if ($this->validatedamountDays->validateAmount($request->amount)) {

                                        if (robus()->is_under_distributer || robus()->is_api) {

                                            $percent_amount = $percent_djamo->percent_merchands * $request->amount;

                                            $valeur_received = $request->amount -  $percent_amount;

                                            $valeur_amount = $request->amount;
                                        } else {

                                            $percent_amount = $percent_djamo->percent * $request->amount;

                                            $valeur_received = $request->amount -  $percent_amount;

                                            $valeur_amount = $request->amount;
                                        }

                                        $file_front = $request->file('image_piece');
                                        $file_backend = $request->file('image_bakend');
                                        $imageBackendPath = null;
                                        $imagePath = null;

                                        if ($file_front || $file_backend) {
                                            // Chemin de destination
                                            $destination_path = public_path('image_inters/' . $request->name_custumer . '/');

                                            // Création du dossier si inexistant
                                            if (!file_exists($destination_path)) {
                                                mkdir($destination_path, 0777, true);
                                            }

                                            if ($file_front) {
                                                $front_file = uniqid('front_') . '.' . $file_front->getClientOriginalExtension();
                                                $file_front->move($destination_path, $front_file);

                                                $imagePath = env('APP_URL') . "/image_inters/" . $request->name_custumer . "/$front_file";
                                            }

                                            // 👉 Image verso
                                            if ($file_backend) {
                                                $backend_file = uniqid('back_') . '.' . $file_backend->getClientOriginalExtension();
                                                $file_backend->move($destination_path, $backend_file);

                                                $imageBackendPath = env('APP_URL') . "/image_inters/" . $request->name_custumer . "/$backend_file";
                                            }
                                        }


                                        $walletInter = $this->interServices->createWithMobileWalletInter(
                                            $request->amount,
                                            $percent_djamo,
                                            $country_robus,
                                            $request->provider,
                                            $request->phone,
                                            $request->provider_type,
                                            $request->name_custumer,
                                            $request->num_piece_custumer,
                                            $request->date_piece,
                                            $request->name,
                                            $request->name_sender,
                                            $request->account_number,
                                            $actionPercentId,
                                            $imagePath,
                                            $imageBackendPath,
                                            $request->type_piece,
                                        );

                                        if ($walletInter != null) {

                                            if ($this->interServices->updateAmountReceived($walletInter, $country_robus, $valeur_received)) {

                                                if ($this->interServices->processCommissionWithMobiles($valeur_amount, $percent_djamo, $country_robus, $walletInter, $admin_civ)) {
                                                    $this->interServices->sendNotificationRobus($walletInter);
                                                    return $this->messageAfterValidated($walletInter);
                                                } else {

                                                    return   json_message_response(false, "Error de transferts du montant !", 300);
                                                }
                                            } else {

                                                return   json_message_response(false, "Error de demande transferts  !", 300);
                                            }
                                        } else {
                                            return   json_message_response(false, "Error de demande transferts  !", 300);
                                        }
                                    } else {

                                        return   json_message_response(false, "Verifier votre Solde SVP !", 300);
                                    }
                                } else {

                                    return   json_message_response(false, "Le  montant maxi jounalier est atteint !", 300);
                                }
                            } else {

                                return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $country_robus->currencie->code, 333);
                            }
                        } else {
                            return   json_message_response(false, "Niveau de pourcentage introuvable !", 300);
                        }
                    } else {
                        return   json_message_response(false, "veuillez selectionnez le pays de destination SVP !", 300);
                    }
                } else {
                    return   json_message_response(false, "Veuillez selectionnez les providers de destination SVP !", 300);
                }
            } elseif ($request->provider_type == 5) {

                $percent_robus =  $this->percentRobus->getRobusPercentagePaymentInter();

                // Providers SODECI; CIE and SODECI
                // if (robus()->country->code != 'CI') {

                $validationRules = [];
                $validationMessages = [];

                if ($request->provider == 53) {

                    $validationRules = [
                        'id_invoice' => 'required',
                        'name' => 'required',
                        'amount' => 'required',
                    ];

                    $validationMessages = [
                        'id_invoice.required' => 'Votre numero est obligatoire',
                        'amount.required' => 'Votre montant est obligatoire',
                        'name.required' => 'Nom est obligatoire',
                    ];
                } elseif ($request->provider == 54) {

                    $validationRules = [
                        'number_contract' => 'required|max:9',
                        'name' => 'required',
                        'amount' => 'required',
                    ];

                    $validationMessages = [
                        'number_contract.required' => 'Votre numero est obligatoire',
                        'amount.required' => 'Votre montant est obligatoire',
                        'name.required' => 'Nom est obligatoire',
                    ];
                } elseif ($request->provider == 52) {

                    if ($request->paiementChoix_phone == 1) {

                        $validationRules = [
                            'number_phone' => 'required',
                            'offer' => 'required',
                        ];

                        $validationMessages = [
                            'number_phone.required' => 'Votre numero est obligatoire',
                            'offer.required' => 'Offre est obligatoire',
                        ];
                    } else {
                        $validationRules = [
                            'number_decoder' => 'required|max:14',
                            'offer' => 'required',
                        ];

                        $validationMessages = [
                            'number_decoder.required' => 'Votre numero est obligatoire',
                            'offer.required' => 'Offre est obligatoire',
                        ];
                    }
                }

                $validator = Validator::make($request->all(), $validationRules, $validationMessages);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Quelque champs obligatoire',
                        'data' => $validator->errors()
                    ], 333);
                }

                if ($percent_robus != null) {

                    if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {


                        if ($request->provider != 52) {

                            $valeur_convertion_amount =   $this->convertionservice->convertAdminCommissionPayment($percent_robus->amount_auther);

                            $amount_receved = (float) $request->amount - (float) $valeur_convertion_amount;

                            $valeur_convertion =   $request->amount;

                            $amount_valeur = $request->amount;

                            $amount_commission = $percent_robus->amount_auther;

                            $convertion_amount_pay =  $amount_valeur;
                        } else {


                            $amount_valeur = $offers->amount + $percent_robus->amount_canal;

                            $valeur_convertion =   $this->convertionservice->convertAdminCommissionPayment($offers->amount);

                            $amount_receved = $valeur_convertion;

                            $amount_commission = $percent_robus->amount_canal;

                            $convertion_amount_pay =   $this->convertionservice->convertAdminCommissionPayment($amount_valeur);
                        }

                        $valeur_convertion_comi =   $this->convertionservice->convertAdminCommissionPayment($amount_commission);


                        $valeur_convertion_amount_receved =   $this->convertionservice->convertAdminCommission($amount_receved);


                        if ($this->validatedamountDays->validateAmount($convertion_amount_pay)) {


                            $walletPayment = $this->paymentServices->createRobusWalletTransactionPayment(
                                $valeur_convertion,
                                $percent_robus,
                                $country_robus,
                                $request->provider,
                                $request->id_invoice,
                                $request->number_decoder,
                                $request->number_phone,
                                $valeur_convertion_comi,
                                $request->number_contract,
                                $request->paiementChoix_phone,
                                $request->facture_choix_type,
                                $offers,
                                $valeur_convertion_amount_receved,
                                $request->name,
                            );

                            if ($request->provider != 52) {

                                if ($walletPayment != null) {

                                    if ($this->paymentServices->updateAmountPaymentReceveid($admin_civ, $percent_robus, $walletPayment, $convertion_amount_pay)) {
                                        $this->interServices->sendNotificationPayment($walletPayment);
                                        return $this->messageAfterValidated($walletPayment);
                                    }
                                } else {

                                    return  json_message_response(false, "Error de creation de transfert du montant!", 333);
                                }
                            } else {

                                if ($walletPayment != null) {

                                    if ($this->paymentServices->updateAmountPaymentOfferReceveid($admin_civ, $percent_robus, $walletPayment, $convertion_amount_pay)) {
                                        $this->interServices->sendNotificationPayment($walletPayment);
                                        return $this->messageAfterValidated($walletPayment);
                                    }
                                } else {

                                    return  json_message_response(false, "Error de creation de transfert du montant!", 333);
                                }
                            }
                        } else {
                            return json_message_response(false, "Veuillez verifier votre solde", 333);
                        }
                    } else {
                        return json_message_response(false, "Votre montant journalier est atteint !!", 333);
                    }
                } else {

                    return  json_message_response(false, "Pas de poucentage defini dans cette zone !", 333);
                }
                // } else {
                //     return response()->json([
                //         'success' => false,
                //         'message' => 'Les fournisseurs ne sont pas des pays ',
                //     ], 333);
                // }
            } elseif ($request->provider_type == 6) {

                // Phone credit
                $percent_robus =  $this->percentRobus->getRobusPercentagePhoneInter();


                $validator = Validator::make(

                    $request->all(),
                    [
                        'phone' => 'required',
                        'amount' => 'required',
                        'country' => 'required',
                    ],
                    [

                        'phone.required' => 'Votre numero est obligatoire',
                        'amount.required' => 'Votre montant est obligatoire',
                        'country.required' => 'Votre pays est obligatoire',

                    ]

                );

                if ($validator->fails()) {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'Champ est requis',
                            'data' => $validator->errors()
                        ],
                        333
                    );
                }

                if ($percent_robus != null) {

                    if (in_array($request->provider, [56, 57, 58])) {

                        if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                            $amount_valeur = $request->amount + ($percent_robus->percent * $request->amount);

                            $amount_commission = $percent_robus->percent * $request->amount;

                            $amount_receved = $request->amount;

                            $valeur_convertion_comi =   $this->convertionservice->convertAdminCommissionPayment($amount_commission);

                            $valeur_convertion_amount_receved =   $this->convertionservice->convertAdminCommission($amount_receved);

                            if ($this->validatedamountDays->validateAmount($amount_valeur)) {

                                $walletPhoneCredit = $this->paymentCreditService->createRobusWalletTransactionCreditPayment(
                                    $amount_receved,
                                    $percent_robus,
                                    $country_robus,
                                    $request->provider,
                                    $request->id_invoice,
                                    $request->number_decoder,
                                    $request->number_phone,
                                    $amount_commission,
                                    $request->phone,
                                    $request->paiementChoix_phone,
                                    $offers,
                                    $valeur_convertion_amount_receved
                                );

                                if ($walletPhoneCredit != null) {

                                    if ($this->paymentCreditService->updateamountPhoneCreditReceveid($admin_civ, $percent_robus, $walletPhoneCredit, $request->amount)) {
                                        $this->interServices->sendNotificationPayment($walletPhoneCredit);
                                        return $this->messageAfterValidated($walletPhoneCredit);
                                    }
                                } else {

                                    return  json_message_response(false, "Error de creation de transfert du montant!", 333);
                                }
                            } else {
                                return json_message_response(false, "Veuillez verifier votre solde", 333);
                            }
                        } else {
                            return json_message_response(false, "Votre montant journalier est atteint !!", 333);
                        }
                    } else {

                        return json_message_response(false, "Selectionnez un Provider existant SVP !!", 333);
                    }
                } else {

                    return  json_message_response(false, "Pas de poucentage defini dans cette zone !", 333);
                }
            } elseif ($request->provider_type == 7) {

                // Bank reload
                if (in_array($request->provider, [59, 60])) {

                    $validator = Validator::make(

                        $request->all(),
                        [
                            'account_number' => 'required',
                            'amount' => 'required',
                            'country' => 'required',
                            'firstname' => 'required',
                            'lastname' => 'required',
                            'phone' => 'required',
                        ],
                        [

                            'firstname.required' => 'Nom est obligatoire',
                            'amount.required' => 'Votre montant est obligatoire',
                            'lastname.required' => 'Prenom est obligatoire',
                            'account_number.required' => 'Numero de compte est obligatoire',
                            'phone.required' => 'Telephone est obligatoire',
                            'country.required' => 'Pays est obligatoire',

                        ]
                    );

                    if ($validator->fails()) {
                        return response()->json(
                            [
                                'success' => false,
                                'message' => 'Quelque champs sont obligatoire',
                                'data' => $validator->errors()
                            ],
                            333
                        );
                    }

                    $percent_robus =  $this->percentRobus->getRobusPercentageRelaodCard();
                    $valeur_first = $percent_robus->levelBanks->first();
                    $valeur_end = $percent_robus->levelBanks->last();
                    $all_valeurs = $percent_robus->levelBanks;


                    if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                        if ($percent_robus != null) {

                            // Maximun limit valeurs send
                            $valeur_end =   $percent_robus->levelBanks->last();

                            $valeur_convertion_max =  $this->convertionservice->convertAdminCommissionPayment($valeur_end->amount_end);


                            if ($valeur_convertion_max  <  $request->amount) {
                                return response()->json(
                                    [
                                        'success' => false,
                                        'message' => 'Vous avez depasser le montant maximun !! .',
                                    ],
                                    500
                                );
                            }

                            $amount_request =  $this->convertionservice->convertAdminCommission($request->amount);

                            foreach ($all_valeurs as $amount_user) {

                                if ($amount_request >= $amount_user->amount_begin && $amount_request <= $amount_user->amount_end) {

                                    $convertion_amount_begin =  $this->convertionservice->convertAdminCommissionPayment($valeur_first->amount_begin);

                                    $convertion_amount_end =  $this->convertionservice->convertAdminCommissionPayment($valeur_end->amount_end);

                                    // Convertion valeurs
                                    if ($convertion_amount_begin > $request->amount) {

                                        return response()->json(
                                            [
                                                'success' => false,
                                                'message' => 'Montant minimun est de .' . $valeur_first->amount_begin,
                                            ],
                                            341
                                        );
                                    } elseif ($convertion_amount_end < $request->amount) {

                                        return response()->json(
                                            [
                                                'success' => false,
                                                'message' => 'Montant maximum est de .' . $$valeur_end->amount_end,
                                            ],
                                            341
                                        );
                                    } else {

                                        if ($percent_robus !== null) {

                                            if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                                                $percent_distr_cfa =   $amount_user->percent * $amount_request;

                                                $amount_valeur_send = (float)$amount_request +  (float)$percent_distr_cfa;

                                                if (robus()->is_under_distributer || robus()->is_api) {
                                                    $amount_valeur_convertion_send =  $this->convertionservice->convertAdminCommissionPayment($amount_request);
                                                } else {

                                                    $amount_valeur_convertion_send =  $this->convertionservice->convertAdminCommissionPayment($amount_valeur_send);
                                                }

                                                if ($this->validatedamountDays->validateAmountVirement($amount_valeur_convertion_send)) {


                                                    if (robus()->is_under_distributer || robus()->is_api) {

                                                        $comission_distributer = $amount_user->comission_distributer * $amount_request;

                                                        $comission_compagnie = $amount_user->percent_companie * $amount_request;
                                                    } else {

                                                        $amount_payment = (float)$amount_user->percent  * $amount_request;

                                                        $comission_compagnie = 0;

                                                        $comission_distributer = $amount_payment;
                                                    }

                                                    $amount_valeur =  $amount_request;

                                                    $amount_receved_valeur = $amount_request - $percent_distr_cfa;

                                                    $amount_valeur_convertion =  $this->convertionservice->convertAdminCommissionPayment($amount_valeur);

                                                    $amount_commission_convertion =  $this->convertionservice->convertAdminCommissionPayment($comission_distributer);

                                                    $amount_commission_compagnie =  $this->convertionservice->convertAdminCommissionPayment($comission_compagnie);

                                                    $amount_commission_convertion_percent =  $this->convertionservice->convertAdminCommissionPayment($percent_distr_cfa);

                                                    $valeur_convertion_amount_receved =   $amount_receved_valeur;


                                                    //valeur commissions
                                                    $walletVirement =  $this->virementInterService->createRobusWalletTransactionVirement($amount_valeur_convertion, $request->account_number, $country_robus, $request->provider, $amount_commission_convertion_percent, $request->phone, $request->firstname, $request->lastname, $valeur_convertion_amount_receved);

                                                    if ($walletVirement != null) {

                                                        if ($this->virementInterService->updateamountVirementInterReceveid($admin_civ, $amount_commission_convertion, $walletVirement, $amount_valeur_convertion_send, $percent_distr_cfa, $amount_commission_compagnie)) {
                                                            $this->interServices->sendNotificationPayment($walletVirement);
                                                            return $this->messageAfterValidated($walletVirement);
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
                            }
                        } else {

                            return json_message_response(false, "Erreur de pourcentage !", 333);
                        }
                    } else {
                        return   json_message_response(false, "Votre montant journalier est atteint !", 333);
                    }
                } else {

                    return response()->json([
                        'success' => false,
                        'message' => 'Selectionnez un providers existant SVP.',
                    ], 333);
                }
            } elseif ($request->provider_type == 8) {


                $percent_robus =  $this->percentRobus->getRobusPercentageOrderCard();

                $validator = Validator::make(

                    $request->all(),
                    [
                        'account_number' => 'required',
                        'amount' => 'required',
                        'country' => 'required',
                        'name' => 'required',
                    ],
                    [

                        'amount.required' => 'Votre montant est obligatoire',
                        'country.required' => 'Votre pays est obligatoire',
                        'account_number.required' => 'Votre numero compte est obligatoire',
                        'name.required' => 'Votre Nom est obligatoire',

                    ]

                );

                if ($validator->fails()) {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'Champ est requis',
                            'data' => $validator->errors()
                        ],
                        333
                    );
                }



                $valeur_first = $percent_robus->levelDeposits->where('is_inter_bank', true)->first();
                $valeur_end = $percent_robus->levelDeposits->where('is_inter_bank', true)->last();
                $all_valeurs = $percent_robus->levelDeposits->where('is_inter_bank', true);


                if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                    $convertion_amount_begin =  $this->convertionservice->convertAdminCommissionPayment($valeur_first->amount_begin);

                    $convertion_amount_end =  $this->convertionservice->convertAdminCommissionPayment($valeur_end->amount_end);

                    $amount_valeur =  $this->convertionservice->convertAdminCommissionPayment($request->amount);

                    if ($percent_robus != null) {

                        if ($convertion_amount_begin > $request->amount) {

                            return response()->json(
                                [
                                    'success' => false,
                                    'message' => 'Montant minimun est de .' . $valeur_first->amount_begin,
                                ],
                                341
                            );
                        } elseif ($convertion_amount_end < $request->amount) {

                            return response()->json(
                                [
                                    'success' => false,
                                    'message' => 'Montant maximum est de .' . $$valeur_end->amount_end,
                                ],
                                341
                            );
                        } else {

                            $amount_request =  $this->convertionservice->convertAdminCommission($request->amount);


                            foreach ($all_valeurs as $amount_user) {

                                if ($amount_request >= $amount_user->amount_begin && $amount_request <= $amount_user->amount_end) {


                                    if ($percent_robus !== null) {

                                        $amount_receved_valeur =  $amount_request;


                                        if ($amount_user->amount == null) {

                                            $percent_valeur = $amount_user->percent * $amount_request;

                                            $amount_valeur = $amount_request;

                                            if (robus()->is_under_distributer || robus()->is_api) {
                                                $comission_distributer = $amount_user->comission_distributer * $amount_request;
                                                $comission_compagnie = $amount_user->percent_companie * $amount_request;
                                            } else {
                                                $comission_distributer = $percent_valeur;
                                                $comission_compagnie = 0;
                                            }

                                            $amount_receved_valeur = $amount_request - $percent_valeur;
                                        } else {

                                            $percent_valeur = $amount_user->amount;

                                            $amount_valeur = (float)$amount_user->amount + (float)$amount_request;

                                            if (robus()->is_under_distributer || robus()->is_api) {
                                                $comission_distributer = $amount_user->comission_distributer;
                                                $comission_compagnie = $amount_user->percent_companie;
                                            } else {
                                                $comission_distributer = $amount_user->amount;
                                                $comission_compagnie = 0;
                                            }

                                            $amount_receved_valeur = $amount_request - $amount_user->amount;
                                        }


                                        if (robus()->is_under_distributer || robus()->is_api) {
                                            $amount_valeur_send = (float)$amount_request +  (float)$percent_valeur;

                                            $amount_valeur_convertion_send =  $this->convertionservice->convertAdminCommissionPayment($amount_request);
                                        } else {

                                            $amount_valeur_send = (float)$amount_request +  (float)$percent_valeur;

                                            $amount_valeur_convertion_send =  $this->convertionservice->convertAdminCommissionPayment($amount_valeur_send);
                                        }

                                        if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {


                                            if ($this->validatedamountDays->validateAmountVirement($amount_valeur_convertion_send)) {


                                                $amount_valeur_convertion =  $this->convertionservice->convertAdminCommissionPayment($amount_valeur);

                                                $amount_commission_convertion =  $this->convertionservice->convertAdminCommissionPayment($percent_valeur);

                                                $amount_commission_convertion_under =  $this->convertionservice->convertAdminCommissionPayment($comission_distributer);

                                                $amount_commission_convertion_compagnie =  $this->convertionservice->convertAdminCommissionPayment($comission_compagnie);

                                                $walletReloadCard =  $this->virementInterService->createRobusWalletTransactionOrderCard($request->amount, $request->account_number, $country_robus, $request->provider, $amount_commission_convertion,  $request->name, $amount_receved_valeur);

                                                if ($walletReloadCard != null) {

                                                    if ($this->virementInterService->updateamountVirementInterReceveid($admin_civ, $amount_commission_convertion_under, $walletReloadCard, $amount_valeur_convertion_send, $percent_valeur, $amount_commission_convertion_compagnie)) {
                                                        $this->interServices->sendNotificationPayment($walletReloadCard);
                                                        return $this->messageAfterValidated($walletReloadCard);
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
                        }
                    } else {

                        return json_message_response(false, "Erreur de pourcentage !", 333);
                    }
                } else {
                    return   json_message_response(false, "Votre montant journalier est atteint !", 333);
                }
            } elseif ($request->provider_type == 9) {

                // Virement accompts
                $percent_robus =  $this->percentRobus->getRobusPercentageInterVersement();

                $validator = Validator::make(

                    $request->all(),
                    [
                        'num_accompte' => 'required',
                        'provider' => 'required',
                        'bank_code' => 'required',
                        'guichet_code' => 'required',
                        'num_authers' => 'required',
                        'amount' => 'required',
                        'country' => 'required',
                    ],
                    [
                        'num_accompte.required' => 'Votre numero est obligatoire',
                        'amount.required' => 'Votre montant est obligatoire',
                        'bank_code.required' => 'Code banque est obligatoire',
                        'guichet_code.required' => 'Code du guichet est obligatoire',
                        'num_authers.required' => 'Numero est obligatoire',
                        'country.required' => 'Pays est obligatoire',
                    ]
                );

                if ($validator->fails()) {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'Verifier les champs obligatoires sont obligatoire',
                            'data' => $validator->errors()
                        ],
                        333
                    );
                }



                if ($percent_robus != null) {

                    $reel_amount = $this->validatedamountDays->search_amount($request->amount);

                    if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                        if (robus()->is_under_distributer || robus()->is_api) {

                            $percent_amount = $percent_robus->percent_merchands * $request->amount;
                            $valeur_received = $request->amount -  $percent_amount;
                            $valeur_amount = $request->amount;
                        } else {

                            $percent_amount = $percent_robus->percent * $request->amount;
                            $valeur_received = $request->amount -  $percent_amount;
                            $valeur_amount = $request->amount + $percent_amount;
                        }


                        if ($this->validatedamountDays->validateAmount($reel_amount)) {

                            $walletVersement =  $this->interServices->createWithVersementWalletInter($reel_amount, $percent_robus, $country_robus, $request->provider, $request->provider_type, $request->num_authers, $request->guichet_code, $request->bank_code, $request->num_accompte, $request->name);

                            if ($walletVersement != null) {

                                if ($this->interServices->updateAmountReceived($walletVersement, $country_robus, $valeur_received)) {

                                    if ($this->interServices->processCommissionWithVersementMobiles($valeur_amount, $percent_robus, $country_robus, $walletVersement, $admin_civ)) {
                                        $this->interServices->sendNotificationVirementInters($walletVersement);
                                        return $this->messageAfterValidated($walletVersement);
                                    } else {

                                        return   json_message_response(false, "Error de transferts du montant !", 300);
                                    }
                                } else {

                                    return   json_message_response(false, "Error de demande transferts  !", 300);
                                }
                            } else {
                                return   json_message_response(false, "Error de demande transferts  !", 300);
                            }
                        } else {
                            return json_message_response(false, "Veuillez verifier votre solde", 333);
                        }
                    } else {
                        return json_message_response(false, "Votre montant journalier est atteint !!", 333);
                    }
                } else {

                    return  json_message_response(false, "Pas de poucentage defini dans cette zone !", 333);
                }
            } elseif ($request->provider_type == 10) {

                // Transaction Xbet
                $percent_robus =  $this->percentRobus->getRobusPercentageOrderCard();

                $validator = Validator::make(

                    $request->all(),
                    [
                        'account_number' => 'required',
                        'amount' => 'required',
                        'country' => 'required',
                        'name' => 'required',
                    ],
                    [

                        'amount.required' => 'Votre montant est obligatoire',
                        'country.required' => 'Votre pays est obligatoire',
                        'account_number.required' => 'Votre numero compte est obligatoire',
                        'name.required' => 'Votre Nom est obligatoire',

                    ]

                );

                if ($validator->fails()) {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'Champ est requis',
                            'data' => $validator->errors()
                        ],
                        333
                    );
                }



                $valeur_first = $percent_robus->levelDeposits->where('is_inter_bank', true)->first();
                $valeur_end = $percent_robus->levelDeposits->where('is_inter_bank', true)->last();
                $all_valeurs = $percent_robus->levelDeposits->where('is_inter_bank', true);


                if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                    $convertion_amount_begin =  $this->convertionservice->convertAdminCommissionPayment($valeur_first->amount_begin);

                    $convertion_amount_end =  $this->convertionservice->convertAdminCommissionPayment($valeur_end->amount_end);

                    $amount_valeur =  $this->convertionservice->convertAdminCommissionPayment($request->amount);

                    if ($percent_robus != null) {

                        if ($convertion_amount_begin > $request->amount) {

                            return response()->json(
                                [
                                    'success' => false,
                                    'message' => 'Montant minimun est de .' . $valeur_first->amount_begin,
                                ],
                                341
                            );
                        } elseif ($convertion_amount_end < $request->amount) {

                            return response()->json(
                                [
                                    'success' => false,
                                    'message' => 'Montant maximum est de .' . $$valeur_end->amount_end,
                                ],
                                341
                            );
                        } else {

                            $amount_request =  $this->convertionservice->convertAdminCommission($request->amount);


                            foreach ($all_valeurs as $amount_user) {

                                if ($amount_request >= $amount_user->amount_begin && $amount_request <= $amount_user->amount_end) {


                                    if ($percent_robus !== null) {

                                        $amount_receved_valeur =  $amount_request;


                                        if ($amount_user->amount == null) {

                                            $percent_valeur = $amount_user->percent * $amount_request;

                                            $amount_valeur = $amount_request;

                                            if (robus()->is_under_distributer || robus()->is_api) {
                                                $comission_distributer = $amount_user->comission_distributer * $amount_request;
                                                $comission_compagnie = $amount_user->percent_companie * $amount_request;
                                            } else {
                                                $comission_distributer = $percent_valeur;
                                                $comission_compagnie = 0;
                                            }

                                            $amount_receved_valeur = $amount_request - $percent_valeur;
                                        } else {

                                            $percent_valeur = $amount_user->amount;

                                            $amount_valeur = (float)$amount_user->amount + (float)$amount_request;

                                            if (robus()->is_under_distributer || robus()->is_api) {
                                                $comission_distributer = $amount_user->comission_distributer;
                                                $comission_compagnie = $amount_user->percent_companie;
                                            } else {
                                                $comission_distributer = $amount_user->amount;
                                                $comission_compagnie = 0;
                                            }

                                            $amount_receved_valeur = $amount_request - $amount_user->amount;
                                        }


                                        if (robus()->is_under_distributer || robus()->is_api) {
                                            $amount_valeur_send = (float)$amount_request +  (float)$percent_valeur;

                                            $amount_valeur_convertion_send =  $this->convertionservice->convertAdminCommissionPayment($amount_request);
                                        } else {

                                            $amount_valeur_send = (float)$amount_request +  (float)$percent_valeur;

                                            $amount_valeur_convertion_send =  $this->convertionservice->convertAdminCommissionPayment($amount_valeur_send);
                                        }

                                        if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {


                                            if ($this->validatedamountDays->validateAmountVirement($amount_valeur_convertion_send)) {


                                                $amount_valeur_convertion =  $this->convertionservice->convertAdminCommissionPayment($amount_valeur);

                                                $amount_commission_convertion =  $this->convertionservice->convertAdminCommissionPayment($percent_valeur);

                                                $amount_commission_convertion_under =  $this->convertionservice->convertAdminCommissionPayment($comission_distributer);

                                                $amount_commission_convertion_compagnie =  $this->convertionservice->convertAdminCommissionPayment($comission_compagnie);

                                                $amount_conv_countrie =  $this->convertionservice->convertAdminCommissionPayment($amount_request);

                                                // $valeur_convertion_amount_receved =   $this->convertionservice->convertAdminCommission($amount_receved_valeur);

                                                $walletReloadCard =  $this->virementInterService->createRobusWalletTransactionOrderCard($request->amount, $request->account_number, $country_robus, $request->provider, $amount_commission_convertion,  $request->name, $amount_receved_valeur);

                                                if ($walletReloadCard != null) {

                                                    if ($this->virementInterService->updateamountVirementInterReceveid($admin_civ, $amount_commission_convertion_under, $walletReloadCard, $amount_valeur_convertion_send, $percent_valeur, $amount_commission_convertion_compagnie)) {
                                                        $this->interServices->sendNotificationPayment($walletReloadCard);
                                                        return $this->messageAfterValidated($walletReloadCard);
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
                        }
                    } else {

                        return json_message_response(false, "Erreur de pourcentage !", 333);
                    }
                } else {
                    return   json_message_response(false, "Votre montant journalier est atteint !", 333);
                }
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
            ], 333);
        }
    }


    /**
     * Delete Inter transaction
     * @OA\Delete (
     *      path="/user/inter/delete/{id}",
     *      tags={"Inter transfer User"},
     *      summary="Delete inter",
     *      description="Supprimer les paiements a l'internationnal", 
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
     *                          property="inter_id",
     *                          type="number"
     *                      ),
     *                 ),
     *                
     *             )
     *         )
     *      ),
     * )
     */
    public function delete_inter(Request $request)
    {

        try {


            $wallet_inter = WalletInter::find($request->inter_id);

            if ($this->deletedService->deletedTransaction($wallet_inter)) {
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
            return json_message_response(false, "Erreur de suppression !", 300);
        }
    }

    public function messageAfterValidated($walletInter)
    {

        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletInter->id,
                'provider' => $walletInter->provider->name,
                'amount' => $walletInter->amount,
                'status' => $walletInter->status,
            ],
            'message' => ('Votre demande de transaction a bien été prise en compte.')
        ], 200);
    }
    // Search payment Internationnal
    public function search_payment_robus_inter($countrie_id, $amount_paiement_robus, $provider_inter_out_civ)
    {

        $countrie = Country::whereName($countrie_id)->first();

        $valeur_max_send  =  $countrie->amount_max_send ?? 1000000;

        try {

            if (!$countrie || !$amount_paiement_robus  || !$valeur_max_send) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pays ou montant non valide ou plafond invalide.',
                ], 400);
            }


            if ($amount_paiement_robus) {

                $commission_inter =  $this->percentRobus->getRobusPercentageInter($countrie, $provider_inter_out_civ);
                $commission_transaction_val_percent_pay = 0;
                $commission_transaction_val_inter_pay = 0;
                $commission_transaction_val_inter_receive = 0;

                if (!$commission_inter) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Aucune commission définie pour ce pays est fournisseur.',
                    ], 400);
                }

                $is_under_or_distributor = robus()->is_under_distributer == 1 || robus()->is_distributer == 1 || robus()->is_api == 1;
                $commission_transaction_val_percent_pay = $is_under_or_distributor
                    ? (float)$commission_inter->percent_under_distributer * (float)$amount_paiement_robus
                    : (float)$commission_inter->percent * (float)$amount_paiement_robus;

                $exchange_rate_sender = robus()->country->currencie->value;
                $exchange_rate_receiver = $countrie->currencie->value;

                $this->calculateInterPay($is_under_or_distributor, $commission_inter, $commission_transaction_val_percent_pay, $amount_paiement_robus);

                $this->calculateInterPayMoney($is_under_or_distributor, $commission_inter, $amount_paiement_robus);

                // Calculer le taux de conversion en CFA et le montant reçu
                function calculateConversion($valeur_verify, $currencie_sender, $currencie_receiver)
                {
                    $conversion_cfa = (float)$valeur_verify * (float)$currencie_sender->value;
                    return $conversion_cfa / (float)$currencie_receiver->value;
                }


                if (is_string($commission_transaction_val_inter_receive)) {
                    return response()->json([
                        'success' => false,
                        'message' => $commission_transaction_val_inter_receive,
                    ], 400);
                }

                if ($provider_inter_out_civ == 51) {

                    if ($exchange_rate_sender > $exchange_rate_receiver) {

                        $commission_transaction_val_inter_pay =  $this->calculateInterPay($is_under_or_distributor, $commission_inter, $amount_paiement_robus, $amount_paiement_robus);
                        $valeur_verify = (float)$amount_paiement_robus -  $commission_transaction_val_percent_pay;
                        $commission_transaction_val_inter_receive = $valeur_verify < 0
                            ? "Montant négatif"
                            : calculateConversion($valeur_verify, robus()->country->currencie, $countrie->currencie);
                    } elseif ($exchange_rate_sender == $exchange_rate_receiver) {

                        $commission_transaction_val_inter_pay =  $this->calculateInterPay($is_under_or_distributor, $commission_inter, $amount_paiement_robus, $amount_paiement_robus);

                        $valeur_verify = (float)$amount_paiement_robus -  $commission_transaction_val_percent_pay;
                        $commission_transaction_val_inter_receive = $valeur_verify < 0 ? "Montant négatif" : $valeur_verify;
                    } else {

                        $commission_transaction_val_inter_pay =  $this->calculateInterPay($is_under_or_distributor, $commission_inter, $amount_paiement_robus, $amount_paiement_robus);
                        $valeur_verify = (float)$amount_paiement_robus -  $commission_transaction_val_percent_pay;
                        $commission_transaction_val_inter_receive = $valeur_verify < 0
                            ? "Montant négatif"
                            : calculateConversion($valeur_verify, robus()->country->currencie, $countrie->currencie);
                    }
                } else {


                    if ($exchange_rate_sender > $exchange_rate_receiver) {

                        $convertion_transaction_countrie = (float)   $amount_paiement_robus / (float) $countrie->currencie->value;

                        $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                        if (!$amount_send) {
                            return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                        }

                        $commission_transaction_val_inter_pay =  $this->calculateInterPayMoney($is_under_or_distributor, $commission_inter, $amount_paiement_robus);
                        $valeur_verify = (float)$amount_paiement_robus -  (float)$commission_transaction_val_percent_pay;
                        $commission_transaction_val_inter_receive = $valeur_verify < 0
                            ? "Montant négatif"
                            : calculateConversion($valeur_verify, robus()->country->currencie, $countrie->currencie);
                    } elseif ($exchange_rate_sender == $exchange_rate_receiver) {

                        $convertion_transaction_countrie = (float)$amount_paiement_robus;

                        $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                        if (!$amount_send) {
                            return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                        }

                        $commission_transaction_val_inter_pay =  $this->calculateInterPayMoney($is_under_or_distributor, $commission_inter, $amount_paiement_robus);
                        $valeur_verify = (float)$amount_paiement_robus -  (float) $commission_transaction_val_percent_pay;

                        $commission_transaction_val_inter_receive = $valeur_verify < 0 ? "Montant négatif" : $valeur_verify;
                    } else {

                        $convertion_transaction_countrie =  (float)$amount_paiement_robus / (float) $countrie->currencie->value;

                        $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                        if (!$amount_send) {
                            return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                        }
                        // Cas où la devise de l'envoyeur est plus faible
                        $commission_transaction_val_inter_pay =  $this->calculateInterPayMoney($is_under_or_distributor, $commission_inter, $amount_paiement_robus);
                        $valeur_verify = (float)$amount_paiement_robus -  (float) $commission_transaction_val_percent_pay;
                        $commission_transaction_val_inter_receive = $valeur_verify < 0
                            ? "Montant négatif"
                            : calculateConversion($valeur_verify, robus()->country->currencie, $countrie->currencie);
                    }
                }

                return response()->json([
                    'success' => $commission_inter != null ? true : false,
                    'data' =>  [
                        'amount_commission' => $commission_transaction_val_inter_pay,
                        'amount_pay' =>  $commission_transaction_val_percent_pay,
                        'amount_receive' => $commission_transaction_val_inter_receive . ' ' .  $countrie->currencie->code,
                    ],
                ], 200);
            } else {
                return response()->json([
                    'success' =>  false,
                    'message' => 'Pas d\'utilisateur',
                ], 200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' =>  false,
                'message' => 'Pas d\'utilisateur',
            ], 200);
        }
    }

    public function search_Djamo($countrie_id, $amount_paiement_robus)
    {
        return $this->searchInterWallet('djamo', $countrie_id, $amount_paiement_robus);
    }

    public function search_Push($countrie_id, $amount_paiement_robus)
    {
        return $this->searchInterWallet('push', $countrie_id, $amount_paiement_robus);
    }

    public function search_Tresort($countrie_id, $amount_paiement_robus)
    {
        return $this->searchInterWallet('tresort', $countrie_id, $amount_paiement_robus);
    }

    public function search_received_Djamo($countrie_id, $amount_paiement_robus)
    {
        return $this->searchAmountReceivedWallet('djamo', $countrie_id, $amount_paiement_robus);
    }

    public function search_received_Push($countrie_id, $amount_paiement_robus)
    {
        return $this->searchAmountReceivedWallet('push', $countrie_id, $amount_paiement_robus);
    }

    public function search_receveid_Tresort($countrie_id, $amount_paiement_robus)
    {
        return $this->searchAmountReceivedWallet('tresort', $countrie_id, $amount_paiement_robus);
    }


    private function searchAmountReceivedWallet(string $type, $countrie_id, $amount_paiement_robus)
    {
        try {


            $countrie = Country::whereId($countrie_id)->first();
            $valeur_max_send  =  $countrie->amount_max_send ?? 1000000;

            if (!$countrie || !$amount_paiement_robus  || !$valeur_max_send) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pays ou montant non valide ou plafond invalide.',
                ], 400);
            }

            // Choisir la bonne méthode pour récupérer le pourcentage
            $percent_robus = match ($type) {
                'djamo' => $this->percentRobus->getRobusPercentageDjamoInter(),
                'push'  => $this->percentRobus->getRobusPercentagePushInter(),
                'tresort'  => $this->percentRobus->getRobusPercentageTresortInter(),
                default => null,
            };

            $commission_transaction_val_percent_pay = 0;
            $commission_transaction_val_inter_pay = 0;
            $commission_transaction_val_inter_receive = 0;

            if ($percent_robus === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pas de rechargement dans cette zone...',
                ], 200);
            }

            if (empty($amount_paiement_robus)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Montant non spécifié...',
                ], 400);
            }

            $exchange_rate_sender = robus()->country->currencie->value;
            $exchange_rate_receiver = $countrie->currencie->value;

            $is_under_or_distributor = robus()->is_under_distributer == 1 || robus()->is_distributer == 1 || robus()->is_api == 1;
            $commission_transaction_val_percent_pay = $is_under_or_distributor
                ? (float)$percent_robus->percent_merchands * (float)$amount_paiement_robus
                : (float)$percent_robus->percent * (float)$amount_paiement_robus;

            function calculateConversion($valeur_verify, $currencie_sender, $currencie_receiver)
            {
                $conversion_cfa = (float)$valeur_verify * (float)$currencie_sender->value;
                return $conversion_cfa / (float)$currencie_receiver->value;
            }

            if ($exchange_rate_sender > $exchange_rate_receiver) {

                $convertion_transaction_countrie = (float)   $amount_paiement_robus / (float) $countrie->currencie->value;

                $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                if (!$amount_send) {
                    return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                }

                $commission_transaction_val_inter_pay =  $this->calculateInterWithMoney($is_under_or_distributor, $percent_robus, $amount_paiement_robus);
                $valeur_verify = (float)$amount_paiement_robus +  (float)$commission_transaction_val_percent_pay;

                $commission_transaction_val_inter_receive = $valeur_verify < 0
                    ? "Montant négatif"
                    : calculateConversion($valeur_verify, robus()->country->currencie, $countrie->currencie);
            } elseif ($exchange_rate_sender == $exchange_rate_receiver) {
                $convertion_transaction_countrie = (float)$amount_paiement_robus;

                $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                if (!$amount_send) {
                    return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                }

                $commission_transaction_val_inter_pay =  $this->calculateInterWithMoney($is_under_or_distributor, $percent_robus, $amount_paiement_robus);
                $valeur_verify = (float)$amount_paiement_robus +  (float) $commission_transaction_val_percent_pay;

                $commission_transaction_val_inter_receive = $valeur_verify < 0 ? "Montant négatif" : $valeur_verify;
            } else {

                $convertion_transaction_countrie =  (float)$amount_paiement_robus / (float) $countrie->currencie->value;

                $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                if (!$amount_send) {
                    return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                }
                // Cas où la devise de l'envoyeur est plus faible
                $commission_transaction_val_inter_pay =  $this->calculateInterWithMoney($is_under_or_distributor, $percent_robus, $amount_paiement_robus);
                $valeur_verify = (float)$amount_paiement_robus + (float) $commission_transaction_val_percent_pay;
                $commission_transaction_val_inter_receive = $valeur_verify < 0
                    ? "Montant négatif"
                    : calculateConversion($valeur_verify, robus()->country->currencie, $countrie->currencie);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'amount_commission'    => $commission_transaction_val_inter_pay,
                    'amount_pay'     => $commission_transaction_val_percent_pay,
                    'amount_receive' => $commission_transaction_val_inter_receive . ' ' .  $countrie->currencie->code,
                ],
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur s\'est produite lors de la recherche.',
            ], 500);
        }
    }

    public function search_versement($countrie_id, $amount_paiement)
    {

        try {


            if ($amount_paiement != '') {

                $countrie = Country::whereId($countrie_id)->first();
                $valeur_max_send  =  $countrie->amount_max_send ?? 1000000;

                if (!$countrie || !$amount_paiement  || !$valeur_max_send) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pays ou montant non valide ou plafond invalide.',
                    ], 400);
                }

                $exchange_rate_sender = robus()->country->currencie->value;
                $exchange_rate_receiver = $countrie->currencie->value;

                $commission_invoice =  $this->percentRobus->getRobusPercentageInterVersement();
                $commission_transaction_val_percent_pay = 0;
                $commission_transaction_val_inter_pay = 0;
                $commission_transaction_val_inter_receive = 0;

                $is_under_or_distributor = robus()->is_under_distributer == 1 || robus()->is_distributer == 1 || robus()->is_api == 1;
                $commission_transaction_val_percent_pay = $is_under_or_distributor
                    ? (float)$commission_invoice->percent_merchands * (float)$amount_paiement
                    : (float)$commission_invoice->percent * (float)$amount_paiement;


                function calculateConversion($valeur_verify, $currencie_sender, $currencie_receiver)
                {
                    $conversion_cfa = (float)$valeur_verify * (float)$currencie_sender->value;
                    return $conversion_cfa / (float)$currencie_receiver->value;
                }
                if ($exchange_rate_sender > $exchange_rate_receiver) {
                    $convertion_transaction_countrie = (float)   $amount_paiement / (float) $countrie->currencie->value;

                    $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                    if (!$amount_send) {
                        return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                    }

                    $commission_transaction_val_inter_pay =  $this->calculateInterWithMoney($is_under_or_distributor, $commission_invoice, $amount_paiement);
                    $valeur_verify = (float)$amount_paiement -  (float)$commission_transaction_val_percent_pay;
                    $commission_transaction_val_inter_receive = $valeur_verify < 0
                        ? "Montant négatif"
                        : calculateConversion($valeur_verify, robus()->country->currencie, $countrie->currencie);
                } elseif ($exchange_rate_sender == $exchange_rate_receiver) {

                    $convertion_transaction_countrie = (float)$amount_paiement;

                    $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                    if (!$amount_send) {
                        return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                    }

                    $commission_transaction_val_inter_pay =  $this->calculateInterWithMoney($is_under_or_distributor, $commission_invoice, $amount_paiement);
                    $valeur_verify = (float)$amount_paiement -  (float) $commission_transaction_val_percent_pay;

                    $commission_transaction_val_inter_receive = $valeur_verify < 0 ? "Montant négatif" : $valeur_verify;
                } else {

                    $convertion_transaction_countrie =  (float)$amount_paiement / (float) $countrie->currencie->value;

                    $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                    if (!$amount_send) {
                        return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                    }
                    // Cas où la devise de l'envoyeur est plus faible
                    $commission_transaction_val_inter_pay =  $this->calculateInterWithMoney($is_under_or_distributor, $commission_invoice, $amount_paiement);
                    $valeur_verify = (float)$amount_paiement -  (float) $commission_transaction_val_percent_pay;
                    $commission_transaction_val_inter_receive = $valeur_verify < 0
                        ? "Montant négatif"
                        : calculateConversion($valeur_verify, robus()->country->currencie, $countrie->currencie);
                }

                return response()->json([
                    'success' => true,
                    'data' => [
                        'amount_commission'    => $commission_transaction_val_inter_pay,
                        'amount_pay'     => $commission_transaction_val_percent_pay,
                        'amount_receive' => $commission_transaction_val_inter_receive . ' ' .  $countrie->currencie->code,
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

    public function search_versement_received($countrie_id, $amount_paiement)
    {

        try {


            // Invoice auther
            if ($amount_paiement != '') {

                $countrie = Country::whereId($countrie_id)->first();
                $valeur_max_send  =  $countrie->amount_max_send ?? 1000000;

                if (!$countrie || !$amount_paiement  || !$valeur_max_send) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pays ou montant non valide ou plafond invalide.',
                    ], 400);
                }

                $exchange_rate_sender = robus()->country->currencie->value;
                $exchange_rate_receiver = $countrie->currencie->value;

                $commission_invoice =  $this->percentRobus->getRobusPercentageInterVersement();
                $commission_transaction_val_percent_pay = 0;
                $commission_transaction_val_inter_pay = 0;
                $commission_transaction_val_inter_receive = 0;

                $is_under_or_distributor = robus()->is_under_distributer == 1 || robus()->is_distributer == 1 || robus()->is_api == 1;
                $commission_transaction_val_percent_pay = $is_under_or_distributor
                    ? (float)$commission_invoice->percent_merchands * (float)$amount_paiement
                    : (float)$commission_invoice->percent * (float)$amount_paiement;


                if ($exchange_rate_sender > $exchange_rate_receiver) {
                    $convertion_transaction_countrie = (float)   $amount_paiement / (float) $countrie->currencie->value;

                    $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                    if (!$amount_send) {
                        return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                    }

                    $commission_transaction_val_inter_pay =  $this->calculateInterWithMoney($is_under_or_distributor, $commission_invoice, $amount_paiement);
                    $valeur_verify = (float)$amount_paiement +  (float)$commission_transaction_val_percent_pay;
                    $commission_transaction_val_inter_receive = $valeur_verify < 0
                        ? "Montant négatif"
                        : calculateConversion($valeur_verify, robus()->country->currencie, $countrie->currencie);
                } elseif ($exchange_rate_sender == $exchange_rate_receiver) {

                    $convertion_transaction_countrie = (float)$amount_paiement;

                    $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                    if (!$amount_send) {
                        return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                    }

                    $commission_transaction_val_inter_pay =  $this->calculateInterWithMoney($is_under_or_distributor, $commission_invoice, $amount_paiement);
                    $valeur_verify = (float)$amount_paiement +  (float) $commission_transaction_val_percent_pay;

                    $commission_transaction_val_inter_receive = $valeur_verify < 0 ? "Montant négatif" : $valeur_verify;
                } else {

                    $convertion_transaction_countrie =  (float)$amount_paiement / (float) $countrie->currencie->value;

                    $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                    if (!$amount_send) {
                        return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                    }
                    // Cas où la devise de l'envoyeur est plus faible
                    $commission_transaction_val_inter_pay =  $this->calculateInterWithMoney($is_under_or_distributor, $commission_invoice, $amount_paiement);
                    $valeur_verify = (float)$amount_paiement + (float) $commission_transaction_val_percent_pay;
                    $commission_transaction_val_inter_receive = $valeur_verify < 0
                        ? "Montant négatif"
                        : calculateConversion($valeur_verify, robus()->country->currencie, $countrie->currencie);
                }

                return response()->json([
                    'success' => true,
                    'data' => [
                        'amount_commission'    => $commission_transaction_val_inter_pay,
                        'amount_pay'     => $commission_transaction_val_percent_pay,
                        'amount_receive' => $commission_transaction_val_inter_receive . ' ' .  $countrie->currencie->code,
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
    private function searchInterWallet(string $type, $countrie_id, $amount_paiement_robus)
    {
        try {


            $countrie = Country::whereId($countrie_id)->first();
            $valeur_max_send  =  $countrie->amount_max_send ?? 1000000;

            if (!$countrie || !$amount_paiement_robus  || !$valeur_max_send) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pays ou montant non valide ou plafond invalide.',
                ], 400);
            }

            // Choisir la bonne méthode pour récupérer le pourcentage
            $percent_robus = match ($type) {
                'djamo' => $this->percentRobus->getRobusPercentageDjamoInter(),
                'push'  => $this->percentRobus->getRobusPercentagePushInter(),
                'tresort'  => $this->percentRobus->getRobusPercentageTresortInter(),
                default => null,
            };

            $commission_transaction_val_percent_pay = 0;
            $commission_transaction_val_inter_pay = 0;
            $commission_transaction_val_inter_receive = 0;

            if ($percent_robus === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pas de rechargement dans cette zone...',
                ], 200);
            }

            if (empty($amount_paiement_robus)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Montant non spécifié...',
                ], 400);
            }

            $exchange_rate_sender = robus()->country->currencie->value;
            $exchange_rate_receiver = $countrie->currencie->value;

            $is_under_or_distributor = robus()->is_under_distributer == 1 || robus()->is_distributer == 1 || robus()->is_api == 1;
            $commission_transaction_val_percent_pay = $is_under_or_distributor
                ? (float)$percent_robus->percent * (float)$amount_paiement_robus
                : (float)$percent_robus->percent * (float)$amount_paiement_robus;

            function calculateConversion($valeur_verify, $currencie_sender, $currencie_receiver)
            {
                $conversion_cfa = (float)$valeur_verify * (float)$currencie_sender->value;
                return $conversion_cfa / (float)$currencie_receiver->value;
            }

            if ($exchange_rate_sender > $exchange_rate_receiver) {

                $convertion_transaction_countrie = (float)   $amount_paiement_robus / (float) $countrie->currencie->value;

                $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                if (!$amount_send) {
                    return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                }

                $commission_transaction_val_inter_pay =  $this->calculateInterWithMoney($is_under_or_distributor, $percent_robus, $amount_paiement_robus);
                $valeur_verify = (float)$amount_paiement_robus -  (float)$commission_transaction_val_percent_pay;

                $commission_transaction_val_inter_receive = $valeur_verify < 0
                    ? "Montant négatif"
                    : calculateConversion($valeur_verify, robus()->country->currencie, $countrie->currencie);
            } elseif ($exchange_rate_sender == $exchange_rate_receiver) {
                $convertion_transaction_countrie = (float)$amount_paiement_robus;

                $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                if (!$amount_send) {
                    return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                }

                $commission_transaction_val_inter_pay =  $this->calculateInterWithMoney($is_under_or_distributor, $percent_robus, $amount_paiement_robus);
                $valeur_verify = (float)$amount_paiement_robus -  (float) $commission_transaction_val_percent_pay;

                $commission_transaction_val_inter_receive = $valeur_verify < 0 ? "Montant négatif" : $valeur_verify;
            } else {

                $convertion_transaction_countrie =  (float)$amount_paiement_robus / (float) $countrie->currencie->value;

                $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                if (!$amount_send) {
                    return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                }
                // Cas où la devise de l'envoyeur est plus faible
                $commission_transaction_val_inter_pay =  $this->calculateInterWithMoney($is_under_or_distributor, $percent_robus, $amount_paiement_robus);
                $valeur_verify = (float)$amount_paiement_robus -  (float) $commission_transaction_val_percent_pay;
                $commission_transaction_val_inter_receive = $valeur_verify < 0
                    ? "Montant négatif"
                    : calculateConversion($valeur_verify, robus()->country->currencie, $countrie->currencie);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'amount_commission'    => $commission_transaction_val_inter_pay,
                    'amount_pay'     => $commission_transaction_val_percent_pay,
                    'amount_receive' => $commission_transaction_val_inter_receive . ' ' .  $countrie->currencie->code,
                ],
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur s\'est produite lors de la recherche.',
            ], 500);
        }
    }

    public function search_payment_robus_inter_out($countrie_id, $amount_paiement_robus, $provider_inter_out_civ)
    {

        $countrie = Country::whereName($countrie_id)->first();

        $valeur_max_send  =  $countrie->amount_max_send;

        try {

            if (!$countrie || !$amount_paiement_robus  || !$valeur_max_send) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pays ou montant non valide ou plafond invalide.',
                ], 400);
            }


            $commission_inter =  $this->percentRobus->getRobusPercentageInter($countrie, $provider_inter_out_civ);
            $commission_transaction_val_percent_pay = 0;
            $commission_transaction_val_inter_pay = 0;
            $commission_transaction_val_inter_pay_after = 0;
            $commission_transaction_val_inter_receive = 0;
            $convertion_transaction_val_inter_receive = 0;


            if (!$commission_inter) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune commission définie pour ce pays est fournisseur.',
                ], 400);
            }


            $exchange_rate_sender = robus()->country->currencie->value;
            $exchange_rate_receiver = $countrie->currencie->value;

            if ($exchange_rate_sender > $exchange_rate_receiver) {
                $amountSimple = $amount_paiement_robus * $exchange_rate_sender;
                $reelCommissionAmount = $amountSimple * $exchange_rate_receiver;
            } elseif ($exchange_rate_sender === $exchange_rate_receiver) {
                $reelCommissionAmount = $amount_paiement_robus;
            } else {
                $amountSimple = $amount_paiement_robus * $exchange_rate_sender;
                $reelCommissionAmount = $amountSimple * $exchange_rate_receiver;
            }


            if (robus()->is_under_distributer == 1 || robus()->is_distributer == 1) {
                $commission_transaction_val_percent_pay = (float) $commission_inter->percent_under_distributer * (float) $reelCommissionAmount;
                $percent_real = adjustFeesWithRest($commission_transaction_val_percent_pay, $reelCommissionAmount);
                $commission_transaction_val_inter_pay = (float)  $commission_inter->percent_deposit_under_distributer * $amount_paiement_robus;
            } else {
                $commission_transaction_val_percent_pay = (float) $commission_inter->percent * (float) $reelCommissionAmount;
                $commission_transaction_val_inter_pay = $commission_transaction_val_percent_pay;
                $percent_real = adjustFeesWithRest($commission_transaction_val_percent_pay, $reelCommissionAmount);
            }


            if ($provider_inter_out_civ == 51) {

                if ($exchange_rate_sender > $exchange_rate_receiver) {

                    $valeur_verify = (float)$reelCommissionAmount + $commission_transaction_val_percent_pay;
                    $commission_transaction_val_inter_receive = $valeur_verify < 0
                        ? "Montant négatif"
                        : $valeur_verify;
                } elseif ($exchange_rate_sender == $exchange_rate_receiver) {

                    $valeur_verify = (float)$reelCommissionAmount + $commission_transaction_val_percent_pay;
                    $commission_transaction_val_inter_receive = $valeur_verify < 0 ? "Montant négatif" : $valeur_verify;
                } else {

                    $valeur_verify = (float)$reelCommissionAmount + $commission_transaction_val_percent_pay;
                    $commission_transaction_val_inter_receive = $valeur_verify < 0
                        ? "Montant négatif"
                        : $valeur_verify;
                }
            } else {


                if ($exchange_rate_sender > $exchange_rate_receiver) {

                    $convertion_transaction_countrie = (float)   $amount_paiement_robus *  (float) $countrie->currencie->value;

                    $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                    if (!$amount_send) {
                        return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                    }

                    $valeur_verify = (float)$reelCommissionAmount +  $commission_transaction_val_percent_pay;
                    $commission_transaction_val_inter_receive = $valeur_verify < 0
                        ? "Montant négatif"
                        :  $valeur_verify;
                } elseif ($exchange_rate_sender == $exchange_rate_receiver) {

                    $convertion_transaction_countrie = (float)   $amount_paiement_robus;

                    $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                    if (!$amount_send) {
                        return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                    }

                    $valeur_verify = (float)$reelCommissionAmount +  $commission_transaction_val_percent_pay;
                    $commission_transaction_val_inter_receive = $valeur_verify < 0 ? "Montant négatif" : $valeur_verify;
                } else {


                    $convertion_transaction_countrie = (float)   $amount_paiement_robus  * (float) $countrie->currencie->value;

                    $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                    if (!$amount_send) {
                        return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                    }

                    $valeur_verify = (float)$reelCommissionAmount +  $commission_transaction_val_percent_pay;
                    $commission_transaction_val_inter_receive = $valeur_verify < 0
                        ? "Montant négatif"
                        : $valeur_verify;
                }
            }


            if (is_string($commission_transaction_val_inter_receive)) {
                // Message d'erreur si la conversion échoue
                return response()->json([
                    'success' => false,
                    'message' => $commission_transaction_val_inter_receive,
                ], 400);
            }

            return response()->json([
                'success' => $commission_inter != null ? true : false,
                'data' =>  [
                    'amount_commission' => $commission_transaction_val_inter_pay,
                    'amount_pay' =>  $commission_transaction_val_percent_pay,
                    'amount_receive' => $commission_transaction_val_inter_receive,
                ],
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' =>  false,
                'message' => 'Pas Erreur de calcul des commissions',
            ], 200);
        }
    }

    public function paiement_invoice_inter($amount_invoice_inter)
    {


        try {

            if ($amount_invoice_inter != '') {

                $commission_invoice = PaymentCost::whereId(1)->first();

                $commission_reload_inter_val_inter_percent_pay = 0;

                if (robus()->is_under_distributer || robus()->is_api) {

                    $commission_paiement_invoice_val_inter_pay = $this->convertionservice->convertAdminCommissionInvoice($commission_invoice->amount_auther);

                    $commission_reload_inter_val_inter_percent_pay = $this->convertionservice->convertAdminCommissionInvoice($commission_invoice->comission_auther);
                } else {

                    $commission_paiement_invoice_val_inter_pay = $this->convertionservice->convertAdminCommissionInvoice($commission_invoice->amount_auther);

                    $commission_reload_inter_val_inter_percent_pay = $commission_paiement_invoice_val_inter_pay;
                }


                $amount_invoice_conversion = $this->convertionservice->convertAdminCommission($amount_invoice_inter);

                $valeur_receive = (robus()->is_under_distributer || robus()->is_api) ? ((float) $amount_invoice_conversion - (float) $this->convertionservice->convertAdminCommissionInvoice($commission_invoice->amount_auther)) : ((float) $amount_invoice_conversion - (float) $commission_reload_inter_val_inter_percent_pay);

                $commission_paiement_invoice_val_inter_receive =  $valeur_receive;


                return response()->json([
                    'success' => $commission_invoice != null ? true : false,
                    'data' =>  [
                        'amount_commission' => $commission_reload_inter_val_inter_percent_pay,
                        'amount_pay' => $commission_paiement_invoice_val_inter_pay,
                        'amount_receive' => $commission_paiement_invoice_val_inter_receive,
                        'amount_conversion' => $commission_paiement_invoice_val_inter_receive,
                    ],
                ], 200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' =>  false,
                'message' => 'Pas d\'utilisateur',
            ], 200);
        }

        // Invoice inter Inter

    }

    public function paiement_invoice_received($amount_invoice_inter)
    {


        try {

            if ($amount_invoice_inter != '') {

                $commission_invoice = PaymentCost::whereId(1)->first();

                $commission_reload_inter_val_inter_percent_pay = 0;

                if (robus()->is_under_distributer || robus()->is_api) {
                    $commission_paiement_invoice_val_inter_pay = $this->convertionservice->convertAdminCommissionInvoice($commission_invoice->comission_auther);

                    $commission_reload_inter_val_inter_percent_pay = $this->convertionservice->convertAdminCommissionInvoice($commission_invoice->percent_masters);
                } else {

                    $commission_paiement_invoice_val_inter_pay = $this->convertionservice->convertAdminCommissionInvoice($commission_invoice->amount_auther);

                    $commission_reload_inter_val_inter_percent_pay = $commission_paiement_invoice_val_inter_pay;
                }

                $valeur_receive =  (float) $amount_invoice_inter +   (float)$commission_invoice->amount_auther;

                if ($valeur_receive < 0) {

                    $commission_paiement_invoice_val_inter_receive = "Montant negatif";
                } else {

                    $commission_paiement_invoice_val_inter_receive =  $valeur_receive;
                }

                return response()->json([
                    'success' => $commission_invoice != null ? true : false,
                    'data' =>  [
                        'amount_commission' => $commission_reload_inter_val_inter_percent_pay,
                        'amount_pay' => $commission_paiement_invoice_val_inter_pay,
                        'amount_receive' => $commission_paiement_invoice_val_inter_receive,
                    ],
                ], 200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' =>  false,
                'message' => 'Pas d\'utilisateur',
            ], 200);
        }

        // Invoice inter Inter

    }

    public function paiement_virement_inter($amount_virement_inter)
    {


        $countrie = Country::whereId(107)->first();

        try {

            // Calcul valeur commissions virement Bank Inter

            if ($amount_virement_inter != null) {


                $valeur_amount =   $this->convertionservice->convertAdminCommission($amount_virement_inter);

                if ($valeur_amount) {

                    $commission_inter = BankCost::whereId(1)->first();

                    $commission_reload_inter_val_inter_percent_pay = 0;

                    foreach ($commission_inter->levelBanks as $amount_comission) {

                        if ($valeur_amount >= $amount_comission->amount_begin &&  $valeur_amount <= $amount_comission->amount_end) {

                            if ($amount_comission->amount == null) {

                                $percent_distr_cfa =   $amount_comission->percent * $valeur_amount;

                                if (robus()->is_under_distributer || robus()->is_api) {

                                    $valeur_cfa =   $amount_comission->comission_distributer *  $valeur_amount;
                                } else {

                                    $valeur_cfa =   $amount_comission->percent * $valeur_amount;
                                }

                                $commission_reload_inter_val_inter_pay =  $this->convertionservice->convertAdminCommissionInvoice($valeur_cfa);

                                $receive_valeur = $valeur_amount - $percent_distr_cfa;

                                $commission_reload_inter_val_inter_percent_pay =  $this->convertionservice->convertAdminCommissionInvoice($percent_distr_cfa);

                                $under_distribut_commission =  $this->convertionservice->convertAdminCommissionInvoice($valeur_cfa);

                                if ($receive_valeur < 0) {

                                    $commission_reload_inter_val_inter_receive = "Montant negatif";
                                } else {

                                    $commission_reload_inter_val_inter_receive = $receive_valeur;
                                }
                            } else {


                                $percent_distr_cfa  = $amount_comission->amount;

                                if (robus()->is_under_distributer || robus()->is_api) {

                                    $valeur_cfa =   $amount_comission->comission_distributer;
                                } else {

                                    $valeur_cfa = $valeur_amount + $amount_comission->amount;
                                }


                                $commission_reload_inter_val_inter_pay =  $this->convertionservice->convertAdminCommissionInvoice($percent_distr_cfa);

                                $valeur_cfa_receive = $valeur_amount - $percent_distr_cfa;

                                $commission_reload_inter_val_inter_percent_pay =  $this->convertionservice->convertAdminCommissionInvoice($percent_distr_cfa);

                                $under_distribut_commission =  $this->convertionservice->convertAdminCommissionInvoice($valeur_cfa);

                                // Convertion commission

                                if ($valeur_cfa_receive < 0) {
                                    $commission_reload_inter_val_inter_receive =  "Montant negatif";
                                } else {

                                    $commission_reload_inter_val_inter_receive = $valeur_cfa_receive;
                                }
                            }
                        }
                    }
                    
                    return response()->json([
                        'success' => $commission_inter != null ? true : false,
                        'data' =>  [
                            'amount_commission' => (robus()->is_under_distributer || robus()->is_api) ? $under_distribut_commission   : $commission_reload_inter_val_inter_percent_pay,
                            'amount_pay' => $commission_reload_inter_val_inter_percent_pay,
                            'amount_receive' => $commission_reload_inter_val_inter_receive . ' ' .  $countrie->currencie->code,
                        ],
                    ], 200);
                } else {
                    return response()->json([
                        'success' =>  false,
                        'message' => 'Donnez le montant',
                    ], 200);
                }
            } else {

                return response()->json([
                    'success' =>  false,
                    'message' => 'Pas de valeur disponible',
                ], 200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' =>  false,
                'message' => 'Pas d\'utilisateur',
            ], 200);
        }

        // Invoice inter Inter

    }

    public function paiement_virement_received($amount_virement_inter)
    {


        $countrie = Country::whereId(107)->first();

        try {

            // Calcul valeur commissions virement Bank Inter

            if ($amount_virement_inter != null) {


                $valeur_amount =   $this->convertionservice->convertAdminCommission($amount_virement_inter);

                if ($valeur_amount) {

                    $commission_inter = BankCost::whereId(1)->first();

                    $commission_reload_inter_val_inter_percent_pay = 0;

                    foreach ($commission_inter->levelBanks as $amount_comission) {

                        if ($valeur_amount >= $amount_comission->amount_begin &&  $valeur_amount <= $amount_comission->amount_end) {

                            if ($amount_comission->amount == null) {

                                $percent_distr_cfa =   $amount_comission->percent * $valeur_amount;

                                if (robus()->is_under_distributer || robus()->is_api) {

                                    $valeur_cfa =   $amount_comission->comission_distributer *  $valeur_amount;
                                } else {

                                    $valeur_cfa =   $amount_comission->percent * $valeur_amount;
                                }

                                $commission_reload_inter_val_inter_pay =  $this->convertionservice->convertAdminCommissionInvoice($valeur_cfa);

                                $receive_valeur = $valeur_amount + $percent_distr_cfa;

                                $commission_reload_inter_val_inter_percent_pay =  $this->convertionservice->convertAdminCommissionInvoice($percent_distr_cfa);


                                if ($receive_valeur < 0) {

                                    $commission_reload_inter_val_inter_receive = "Montant negatif";
                                } else {

                                    $commission_reload_inter_val_inter_receive = $receive_valeur;
                                }
                            } else {


                                $percent_distr_cfa  = $amount_comission->amount;

                                if (robus()->is_under_distributer || robus()->is_api) {

                                    $valeur_cfa =   $amount_comission->comission_distributer;
                                } else {

                                    $valeur_cfa = $valeur_amount + $amount_comission->amount;
                                }


                                $commission_reload_inter_val_inter_pay =  $this->convertionservice->convertAdminCommissionInvoice($percent_distr_cfa);

                                $valeur_cfa_receive = $valeur_amount + $percent_distr_cfa;

                                $commission_reload_inter_val_inter_percent_pay =  $this->convertionservice->convertAdminCommissionInvoice($percent_distr_cfa);

                                // Convertion commission

                                if ($valeur_cfa_receive < 0) {
                                    $commission_reload_inter_val_inter_receive =  "Montant negatif";
                                } else {

                                    $commission_reload_inter_val_inter_receive = $valeur_cfa_receive;
                                }
                            }
                        }
                    }

                    return response()->json([
                        'success' => $commission_inter != null ? true : false,
                        'data' =>  [
                            'amount_commission' => $commission_reload_inter_val_inter_percent_pay,
                            'amount_pay' => $commission_reload_inter_val_inter_pay,
                            'amount_receive' => $commission_reload_inter_val_inter_receive . ' ' .  $countrie->currencie->code,
                        ],
                    ], 200);
                } else {
                    return response()->json([
                        'success' =>  false,
                        'message' => 'Donnez le montant',
                    ], 200);
                }
            } else {

                return response()->json([
                    'success' =>  false,
                    'message' => 'Pas de valeur disponible',
                ], 200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' =>  false,
                'message' => 'Pas d\'utilisateur',
            ], 200);
        }

        // Invoice inter Inter

    }

    public function paiement_credit_inter($amount_phone_credit)
    {

        try {

            // Phone inter 
            if ($amount_phone_credit != '') {

                $commission_phone_credit =  $this->percentRobus->getRobusPercentagePhoneInter();


                $commission_phone_credit_val_inter_pay =  $amount_phone_credit *  $commission_phone_credit->percent;

                $valeur_receive =  $amount_phone_credit -  $commission_phone_credit_val_inter_pay;

                $amount_commission = (robus()->is_under_distributer || robus()->is_api)
                    ? $amount_phone_credit *  $commission_phone_credit->percent_merchands
                    : $commission_phone_credit_val_inter_pay;

                if ($valeur_receive < 0) {

                    $commission_phone_credit_val_inter_receive = "Montant negatif";
                } else {

                    $commission_phone_credit_val_inter_receive =  $valeur_receive;
                }

                $valeur_convertion_amount_receved =   $this->convertionservice->convertAdminCommission($commission_phone_credit_val_inter_receive);

                return response()->json([
                    'success' => $commission_phone_credit != null ? true : false,
                    'data' =>  [
                        'amount_commission' => $amount_commission,
                        'amount_pay' => $commission_phone_credit_val_inter_pay,
                        'amount_receive' => $valeur_convertion_amount_receved,
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

    public function paiement_credit_received($amount_phone_credit)
    {

        try {

            // Phone inter 
            if ($amount_phone_credit != '') {

                $commission_phone_credit = PhoneCreditCost::whereId(2)->first();


                $commission_phone_credit_val_inter_pay =  $amount_phone_credit *  $commission_phone_credit->percent;

                $valeur_receive =  $amount_phone_credit +  $commission_phone_credit_val_inter_pay;


                if ($valeur_receive < 0) {

                    $commission_phone_credit_val_inter_receive = "Montant negatif";
                } else {

                    $commission_phone_credit_val_inter_receive =  $valeur_receive;
                }


                return response()->json([
                    'success' => $commission_phone_credit != null ? true : false,
                    'data' =>  [
                        'amount_commission' => $commission_phone_credit_val_inter_pay,
                        'amount_pay' => $commission_phone_credit_val_inter_pay,
                        'amount_receive' => $commission_phone_credit_val_inter_receive,
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

    public function reload_card_inter($amount_reload_card)
    {

        $commission_inter = DepositCost::whereId(1)->first();

        try {


            $valeur_amount =   $this->convertionservice->convertAdminCommission($amount_reload_card);

            if ($valeur_amount) {

                $commission_reload_inter_val_inter_percent_pay = 0;

                foreach ($commission_inter->levelDeposits->where('is_inter_bank', true) as $amount_comission) {

                    if ($valeur_amount >= $amount_comission->amount_begin &&  $valeur_amount <= $amount_comission->amount_end) {

                        if ($amount_comission->amount == null) {

                            $percent_distr_cfa =   $amount_comission->percent * $valeur_amount;

                            if (robus()->is_under_distributer || robus()->is_api) {

                                $valeur_cfa =   $amount_comission->comission_distributer *  $valeur_amount;
                            } else {

                                $valeur_cfa =   $amount_comission->percent * $valeur_amount;
                            }

                            $commission_reload_inter_val_inter_pay =  $this->convertionservice->convertAdminCommissionInvoice($valeur_cfa);

                            $android_distribut_commission =  $this->convertionservice->convertAdminCommissionInvoice($valeur_cfa);
                            $receive_valeur = $valeur_amount - $percent_distr_cfa;

                            $commission_reload_inter_val_inter_percent_pay =  $this->convertionservice->convertAdminCommissionInvoice($percent_distr_cfa);


                            if ($receive_valeur < 0) {

                                $commission_reload_inter_val_inter_receive = "Montant negatif";
                            } else {

                                $commission_reload_inter_val_inter_receive = $receive_valeur;
                            }
                        } else {


                            $percent_distr_cfa  = $amount_comission->amount;

                            if (robus()->is_under_distributer || robus()->is_api) {

                                $valeur_cfa =   $amount_comission->comission_distributer;
                            } else {

                                $valeur_cfa = $amount_comission->amount;
                            }


                            $commission_reload_inter_val_inter_pay =  $this->convertionservice->convertAdminCommissionInvoice($valeur_cfa);

                            $valeur_cfa_receive = $valeur_amount - $percent_distr_cfa;

                            $commission_reload_inter_val_inter_percent_pay =  $this->convertionservice->convertAdminCommissionInvoice($percent_distr_cfa);

                            $android_distribut_commission =  $this->convertionservice->convertAdminCommissionInvoice($valeur_cfa);
                            // Convertion commission

                            if ($valeur_cfa_receive < 0) {
                                $commission_reload_inter_val_inter_receive =  "Montant negatif";
                            } else {

                                $commission_reload_inter_val_inter_receive = $valeur_cfa_receive;
                            }
                        }
                    }
                }

                return response()->json([
                    'success' => $commission_inter != null ? true : false,
                    'data' =>  [
                        'amount_commission' => (robus()->is_under_distributer || robus()->is_api) ? $android_distribut_commission : $commission_reload_inter_val_inter_percent_pay,
                        'amount_pay' => $commission_reload_inter_val_inter_percent_pay ?? null,
                        'amount_receive' => $commission_reload_inter_val_inter_receive,
                    ],
                ], 200);
            } else {
                return response()->json([
                    'success' =>  false,
                    'message' => 'Donnez le montant',
                ], 200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' =>  false,
                'message' => 'Pas d\'utilisateur',
            ], 200);
        }
    }

    public function paiement_reload_card_received($amount_reload_card)
    {

        $commission_inter = DepositCost::whereId(1)->first();

        try {


            $valeur_amount =   $this->convertionservice->convertAdminCommission($amount_reload_card);

            if ($valeur_amount) {

                $commission_reload_inter_val_inter_percent_pay = 0;

                foreach ($commission_inter->levelDeposits->where('is_inter_bank', true) as $amount_comission) {

                    if ($valeur_amount >= $amount_comission->amount_begin &&  $valeur_amount <= $amount_comission->amount_end) {

                        if ($amount_comission->amount == null) {

                            $percent_distr_cfa =   $amount_comission->percent * $valeur_amount;

                            if (robus()->is_under_distributer || robus()->is_api) {

                                $valeur_cfa =   $amount_comission->comission_distributer *  $valeur_amount;
                            } else {

                                $valeur_cfa =   $amount_comission->percent * $valeur_amount;
                            }

                            $commission_reload_inter_val_inter_pay =  $this->convertionservice->convertAdminCommissionInvoice($valeur_cfa);

                            // Valeur receive 

                            $receive_valeur = $valeur_amount + $percent_distr_cfa;

                            $commission_reload_inter_val_inter_percent_pay =  $this->convertionservice->convertAdminCommissionInvoice($percent_distr_cfa);


                            if ($receive_valeur < 0) {

                                $commission_reload_inter_val_inter_receive = "Montant negatif";
                            } else {

                                $commission_reload_inter_val_inter_receive = $receive_valeur;
                            }
                        } else {


                            $percent_distr_cfa  = $amount_comission->amount;

                            if (robus()->is_under_distributer || robus()->is_api) {

                                $valeur_cfa =   $amount_comission->comission_distributer;
                            } else {

                                $valeur_cfa = $amount_comission->amount;
                            }


                            $commission_reload_inter_val_inter_pay =  $this->convertionservice->convertAdminCommissionInvoice($valeur_cfa);

                            $valeur_cfa_receive = $valeur_amount + $percent_distr_cfa;

                            $commission_reload_inter_val_inter_percent_pay =  $this->convertionservice->convertAdminCommissionInvoice($percent_distr_cfa);

                            // Convertion commission

                            if ($valeur_cfa_receive < 0) {
                                $commission_reload_inter_val_inter_receive =  "Montant negatif";
                            } else {

                                $commission_reload_inter_val_inter_receive = $valeur_cfa_receive;
                            }
                        }
                    }
                }

                return response()->json([
                    'success' => $commission_inter != null ? true : false,
                    'data' =>  [
                        'amount_commission' => $commission_reload_inter_val_inter_percent_pay,
                        'amount_pay' => $commission_reload_inter_val_inter_pay,
                        'amount_receive' => $commission_reload_inter_val_inter_receive,
                    ],
                ], 200);
            } else {
                return response()->json([
                    'success' =>  false,
                    'message' => 'Donnez le montant',
                ], 200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' =>  false,
                'message' => 'Pas d\'utilisateur',
            ], 200);
        }
    }

    // Search wallet id 
    public function search_transaction_inter($search)
    {

        try {


            $data = Wallet::where('wallet_id', $search)
                // ->where('status', true)
                ->first();

            return response()->json([
                'success' => $data != null ? true : false,
                'data' =>  [
                    'name' => $data->sidUser->firstname . ' ' . $data->sidUser->lastname,
                    'image' => $data->sidUser->image ?? null,
                ],
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' =>  false,
                'message' => 'Pas d\'utilisateur',
            ], 200);
        }
    }

    public function calculateInterPay($isUnderOrDistributor, $commissionInter, $commissionTransactionValPercentPay, $amountPaiementRobus)
    {
        return $isUnderOrDistributor
            ? (float)$commissionInter->percent_deposit_under_distributer * (float)$commissionTransactionValPercentPay
            : (float)$commissionInter->percent * (float)$amountPaiementRobus;
    }

    public function calculateInterPayMoney($isUnderOrDistributor, $commissionInter, $amountPaiementRobus)
    {
        return $isUnderOrDistributor
            ? (float)$commissionInter->percent_deposit_under_distributer * (float)$amountPaiementRobus
            : (float)$commissionInter->percent * (float)$amountPaiementRobus;
    }

    public function calculateInterWithMoney($isUnderOrDistributor, $commissionInter, $amountPaiementRobus)
    {
        return $isUnderOrDistributor
            ? (float)$commissionInter->percent_merchands * (float)$amountPaiementRobus
            : (float)$commissionInter->percent * (float)$amountPaiementRobus;
    }


    public function calculateConversionTransaction($valeurVerify, $currencieSender, $currencieReceiver)
    {
        $conversionCfa = (float)$valeurVerify * (float)$currencieSender->value;
        return $conversionCfa / (float)$currencieReceiver->value;
    }
    /**
     * Effectue les conversions en fonction des devises des pays.
     *
     * @param float $amount
     * @param float $commission
     * @param Country $countrie
     * @param float $max_send
     * @return float|string
     */
    private function calculateConversion(float $amount, float $commission, Country $countrie, float $max_send)
    {
        $robus_currency = robus()->country->currencie->value;
        $target_currency = $countrie->currencie->value;

        $net_amount = $amount - $commission;
        if ($net_amount < 0) {
            return 'Montant négatif après déduction de la commission.';
        }

        if ($robus_currency > $target_currency) {
            $conversion_cfa = $net_amount * $robus_currency;
            $converted_amount = $conversion_cfa / $target_currency;
        } elseif ($robus_currency < $target_currency) {
            $conversion_cfa = $net_amount * $robus_currency;
            $converted_amount = $conversion_cfa / $target_currency;
        } else {
            $converted_amount = $net_amount;
        }

        // Vérification de la limite maximale autorisée
        if ($converted_amount > $max_send) {
            return "Le montant maximum autorisé est de $max_send " . $countrie->currencie->code;
        }

        return $converted_amount;
    }
}
