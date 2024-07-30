<?php

namespace App\Http\Controllers\Api\Transfers;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Models\Costs\BankCost;
use App\Models\Wallets\Wallet;
use App\Models\Costs\DepositCost;
use App\Models\Costs\PaymentCost;
use App\Models\Custumers\Country;
use App\Models\Wallets\WalletInter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\DeletedService;
use App\Models\Costs\PhoneCreditCost;
use App\Models\Costs\TransactionCost;
use App\Repositories\IntersRepository;
use App\Http\Services\ConvertionService;
use Illuminate\Support\Facades\Validator;
use App\Http\Services\InterPaymentService;
use App\Http\Services\PaymentInterService;
use App\Http\Services\VirementInterService;
use App\Http\Services\NotificationSendService;
use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\PhoneCreditInterService;
use App\Http\Services\SearchTransactionService;
use App\Http\Services\ValidateDailyLimitService;

class IntersPaymentControllerDEMO extends Controller
{

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
    protected $searchTransaction;

    public function __construct(
        IntersRepository $inter_Reposi,
        DeletedService $deletedService,
        PercentRobusAuthService $percentRobus,
        ValidateDailyLimitService $validatedamountDays,
        InterPaymentService $interServices,
        PaymentInterService $paymentServices,
        PhoneCreditInterService $paymentCreditService,
        ConvertionService $convertionservice,
        NotificationSendService $notificationPayment,
        VirementInterService $virementInterService,
        SearchTransactionService $searchTransaction
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
                } elseif (in_array($request->provider, [48, 49, 50, 55, 71, 72, 73, 74, 75, 76, 77, 78, 93, 94 , 95 , 96 , 97 , 98])) {

                    if (robus()->is_under_distributer) {


                        $validator = Validator::make(

                            $request->all(),
                            [
                                "phone" => 'required',
                                "amount" => 'required',
                                "name_custumer" => 'required',
                                "num_piece_custumer" => 'required',
                                "date_piece" => 'required',
                                'country' => 'required',
                            ],
                            [

                                'phone.required' => 'Votre numero est obligatoire',
                                'amount.required' => 'Votre montant est obligatoire',
                                'name_custumer.required' => 'Nom est obligatoire',
                                'num_piece_custumer.required' => 'Numero piece est obligatoire',
                                'date_piece.required' => 'date de validité est obligatoire',
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

                                    if (robus()->is_under_distributer) {

                                        $valeur_received = $request->amount - ($percent_robus->percent_under_distributer * $request->amount);
                                    } else {

                                        $valeur_received = $request->amount - ($percent_robus->percent * $request->amount);
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
                } elseif (in_array($request->provider, [48, 49, 50, 55, 71, 72, 73, 74, 75, 76, 77, 78 , 93, 94 , 95 , 96 , 97 , 98])) {

                    if ($this->interServices->validatePhone($request->phone)) {

                        if ($percent_robus != null) {

                            $valeur_max_send  =  $country_robus->amount_max_send;

                            if ($this->validatedamountDays->validateAmountSendLimit($request->amount, $valeur_max_send)) {

                                if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                                    if ($this->validatedamountDays->validateAmount($request->amount)) {

                                        if (robus()->is_under_distributer) {

                                            $valeur_received = $request->amount - ($percent_robus->percent_under_distributer * $request->amount);
                                        } else {

                                            $valeur_received = $request->amount - ($percent_robus->percent * $request->amount);
                                        }

                                        $walletInter = $this->interServices->createMobileWalletInter($request->amount, $percent_robus, $country_robus, $request->provider, $request->phone,  $request->provider_type,  $request->name_custumer, $request->num_piece_custumer, $request->date_piece);

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
                } else {
                    return   json_message_response(false, "Veuillez selectionnez les providers de destination SVP !", 300);
                }
            } elseif ($request->provider_type == 5) {

                $percent_robus =  $this->percentRobus->getRobusPercentagePaymentInter();

                // Providers SODECI; CIE and SODECI
                if (robus()->country->code != 'CI') {

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

                                $amount_receved = $request->amount;

                                $valeur_convertion_amount =   $this->convertionservice->convertAdminCommissionPayment($percent_robus->amount_auther);

                                // $valeur_convertion =  $request->amount + $valeur_convertion_amount;

                                $valeur_convertion =   $request->amount;

                                $amount_valeur = $request->amount + $valeur_convertion_amount;

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
                                    $offers,
                                    $valeur_convertion_amount_receved
                                );

                                if ($request->provider != 7) {

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
                                return json_message_response(false, "Verifié votre solde !!", 333);
                            }
                        } else {
                            return json_message_response(false, "Votre montant journalier est atteint !!", 333);
                        }
                    } else {

                        return  json_message_response(false, "Pas de poucentage defini dans cette zone !", 333);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Les fournisseurs ne sont pas des pays ',
                    ], 333);
                }
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

                            // $valeur_convertion_amount_receved =   $this->convertionservice->convertAdminCommissionPayment($amount_receved);

                            $valeur_convertion_amount_receved =   $this->convertionservice->convertAdminCommission($amount_receved);

                            // $valeur_convertion_comi =   $this->convertionservice->convertAdminCommission($amount_commission);

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
                                return json_message_response(false, "Verifié votre solde !!", 333);
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

                                    $amount_conv_countrie =  $this->convertionservice->convertAdminCommissionPayment($amount_request);

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

                                                if (robus()->is_under_distributer) {
                                                    $amount_valeur_convertion_send =  $this->convertionservice->convertAdminCommissionPayment($amount_request);
                                                } else {

                                                    $amount_valeur_convertion_send =  $this->convertionservice->convertAdminCommissionPayment($amount_valeur_send);
                                                }

                                                if ($this->validatedamountDays->validateAmountVirement($amount_valeur_convertion_send)) {


                                                    if (robus()->is_under_distributer) {

                                                        $comission_distributer = $amount_user->comission_distributer * $amount_request;
                                                    } else {

                                                        $amount_payment = (float)$amount_user->percent  * $amount_request;


                                                        $comission_distributer = $amount_payment;
                                                    }

                                                    $amount_valeur =  $amount_request;

                                                    $amount_receved_valeur = $amount_request - $percent_distr_cfa;

                                                    $amount_valeur_convertion =  $this->convertionservice->convertAdminCommissionPayment($amount_valeur);

                                                    $amount_commission_convertion =  $this->convertionservice->convertAdminCommissionPayment($comission_distributer);

                                                    $amount_commission_convertion_percent =  $this->convertionservice->convertAdminCommissionPayment($percent_distr_cfa);

                                                    $valeur_convertion_amount_receved =   $amount_receved_valeur;


                                                    //valeur commissions
                                                    $walletVirement =  $this->virementInterService->createRobusWalletTransactionVirement($amount_valeur_convertion, $request->account_number, $country_robus, $request->provider, $amount_commission_convertion_percent, $request->phone, $request->firstname, $request->lastname, $valeur_convertion_amount_receved);

                                                    if ($walletVirement != null) {

                                                        if ($this->virementInterService->updateamountVirementInterReceveid($admin_civ, $amount_commission_convertion, $walletVirement, $amount_valeur_convertion_send, $percent_distr_cfa)) {
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

                // $isIvoires = ($request->provider == 63 || $request->provider == 62 ||  $request->provider == 61);

                // if ($isIvoires != null) {
                //     $valeur_first = $percent_robus->levelDeposits->where('is_inter_bank', false)->first();
                //     $valeur_end = $percent_robus->levelDeposits->where('is_inter_bank', false)->last();
                //     $all_valeurs = $percent_robus->levelDeposits->where('is_inter_bank', false);
                // } else {
                // }

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

                                            if (robus()->is_under_distributer) {
                                                $comission_distributer = $amount_user->comission_distributer * $amount_request;
                                            } else {
                                                $comission_distributer = $percent_valeur;
                                            }

                                            $amount_receved_valeur = $amount_request - $percent_valeur;
                                        } else {

                                            $percent_valeur = $amount_user->amount;

                                            $amount_valeur = (float)$amount_user->amount + (float)$amount_request;

                                            if (robus()->is_under_distributer) {
                                                $comission_distributer = $amount_user->comission_distributer;
                                            } else {

                                                $comission_distributer = $amount_user->amount;
                                            }

                                            $amount_receved_valeur = $amount_request - $amount_user->amount;
                                        }


                                        if (robus()->is_under_distributer) {
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

                                                $amount_conv_countrie =  $this->convertionservice->convertAdminCommissionPayment($amount_request);

                                                // $valeur_convertion_amount_receved =   $this->convertionservice->convertAdminCommission($amount_receved_valeur);

                                                $walletReloadCard =  $this->virementInterService->createRobusWalletTransactionOrderCard($request->amount, $request->account_number, $country_robus, $request->provider, $amount_commission_convertion,  $request->name, $amount_receved_valeur);

                                                if ($walletReloadCard != null) {

                                                    if ($this->virementInterService->updateamountVirementInterReceveid($admin_civ, $amount_commission_convertion_under, $walletReloadCard, $amount_valeur_convertion_send, $percent_valeur)) {
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

    public function processTransactionForCIV($request, $country_robus, $percent_robus, $amount_days, $valeur_ceiling)
    {

        // Amount max send country
        $valeur_max_send  =  $country_robus->amount_max_send;
        if (!$this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {
            return json_message_response(false, "Le montant maximal journalier est atteint !", 300);
        } elseif (!$this->validatedamountDays->validateAmountSendLimit($request->amount, $valeur_max_send)) {
            return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $country_robus->currencie->code, 333);
        }


        foreach ($percent_robus->levelTransactions as $amount_user) {

            if ($request->amount >= $amount_user->amount_begin && $request->amount <= $amount_user->amount_end) {

                $valeur_received = ($amount_user->percent === null) ?
                    $request->amount - $amount_user->amount :
                    $request->amount - ($amount_user->percent * $request->amount);

                $amount_cost = ($amount_user->percent === null) ?
                    $amount_user->amount : ($amount_user->percent * $request->amount);

                $amount_percent = ($amount_user->percent === null) ?
                    $amount_user->amount : ($amount_user->percent * $request->amount);

                if (!$this->validatedamountDays->validateAmount($request->amount)) {
                    return json_message_response(false, "Vérifiez votre solde SVP !", 300);
                }


                if ($request->provider == 51) {


                    // Update Info valeurs
                    $walletAmountSend = Wallet::where('wallet_id',  $request->number_compte)->first();

                    $amount_solde_drawal = (float)$walletAmountSend->amount + (float)$request->amount;

                    $walletInter = $this->interServices->createRobusWalletInter($request->amount, $percent_robus, $country_robus, $request->provider, $request->number_compte, $request->provider_type, $amount_solde_drawal);

                    if ($walletInter === null) {

                        return json_message_response(false, "Erreur de demande de transfert !", 300);
                    }

                    if ($this->interServices->updateAmountRobusReceived($walletInter, $country_robus, $valeur_received, $request->number_compte)) {
                        if ($this->interServices->processCommissionsRobusOutCiv($request->amount, $percent_robus, $walletInter)) {
                            $this->interServices->sendNotificationRobus($walletInter);
                            return $this->messageAfterValidated($walletInter);
                        } else {

                            return   json_message_response(false, "Error de transferts du montant !", 300);
                        }
                    } else {

                        return   json_message_response(false, "Error de demande transferts  !", 300);
                    }
                } else {

                    $walletInter = $this->interServices->createMobileWalletInterCIV($request->amount, $percent_robus, $country_robus, $request->provider, $request->phone, $request->provider_type, $amount_cost, $request->name_custumer, $request->num_piece_custumer, $request->date_piece);

                    if ($walletInter === null) {
                        return json_message_response(false, "Erreur de demande de transfert !", 300);
                    }

                    if ($this->interServices->updateAmountReceived($walletInter, $country_robus, $valeur_received, $request->number_compte)) {
                        if ($this->interServices->processCommissionsMobileRobus($request->amount, $amount_percent, $country_robus, $walletInter)) {
                            $this->interServices->sendNotificationRobus($walletInter);
                            return $this->messageAfterValidated($walletInter);
                        } else {
                            return json_message_response(false, "Erreur de transfert du montant !", 300);
                        }
                    } else {
                        return json_message_response(false, "Erreur de demande de transfert !", 300);
                    }
                    $walletInter = $this->interServices->createRobusWalletInterCIV($request->amount, $percent_robus, $country_robus, $request->provider, $request->number_compte, $request->provider_type, $amount_user->amount);
                }
            }
        }

        return json_message_response(false, "Pas de plage de dates chez nous !", 300);
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
        // $default_valeur = 2000000;
        $valeur_max_send  =  $countrie->amount_max_send;

        try {

            if ($amount_paiement_robus) {

                $commission_inter =  $this->percentRobus->getRobusPercentageInter($countrie, $provider_inter_out_civ);
                $commission_transaction_val_percent_pay = 0;
                $commission_transaction_val_inter_pay = 0;
                $commission_transaction_val_inter_pay_after = 0;
                $commission_transaction_val_inter_receive = 0;
                $convertion_transaction_val_inter_receive = 0;

                if ($commission_inter != null) {

                    if ($countrie->id == 107) {

                        if ($provider_inter_out_civ == 51) {

                            if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                $commission_transaction_val_percent_pay =  (float)$commission_inter->percent_under_distributer  * (float) $amount_paiement_robus;
                            } else {

                                $commission_transaction_val_percent_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                            }


                            if (robus()->country->currencie->value > $countrie->currencie->value) {

                                // Verify amount send maxi
                                // $convertion_transaction_val_inter_receive = (float)   $default_valeur  /   (float) $countrie->currencie->value;

                                // if ($convertion_transaction_val_inter_receive < $amount_paiement_robus) {

                                //     $commission_transaction_val_percent_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_receive = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                // } else {


                                if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $commission_transaction_val_percent_pay;
                                } else {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                }

                                $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_percent_pay;

                                //   Convertion  in CFA
                                $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify *   (float) robus()->country->currencie->value;

                                //   Convertion in Country send
                                $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $countrie->currencie->value;


                                if ($valeur_verify < 0) {

                                    $commission_transaction_val_inter_receive =  "Montant negatif";
                                } else {

                                    $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                }
                                // }
                            } elseif (robus()->country->currencie->value == $countrie->currencie->value) {

                                // $convertion_transaction_val_inter_receive =  $default_valeur;

                                // if ($convertion_transaction_val_inter_receive < $amount_paiement_robus) {

                                //     $commission_transaction_val_percent_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_receive = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                // } else {

                                if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $commission_transaction_val_percent_pay;
                                } else {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                }

                                $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_percent_pay;

                                //   Convertion  in CFA
                                $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify;

                                //   Convertion in Country send
                                $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa;


                                if ($valeur_verify < 0) {

                                    $commission_transaction_val_inter_receive =  "Montant negatif";
                                } else {

                                    $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                }
                                // }
                            } elseif (robus()->country->currencie->value < $countrie->currencie->value) {

                                // $convertion_transaction_val_inter_receive =  $default_valeur /   (float) $countrie->currencie->value;

                                // if ($convertion_transaction_val_inter_receive < $amount_paiement_robus) {

                                //     $commission_transaction_val_percent_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_receive = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                // } else {

                                if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $commission_transaction_val_percent_pay;
                                } else {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                }

                                $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_percent_pay;

                                //   Convertion  in CFA
                                $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify *   (float) robus()->country->currencie->value;

                                //   Convertion in Country send
                                $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $countrie->currencie->value;


                                if ($valeur_verify < 0) {

                                    $commission_transaction_val_inter_receive =  "Montant negatif";
                                } else {

                                    $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                }
                                // }
                            }
                        } else {


                            if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                $commission_transaction_val_percent_pay =  (float)$commission_inter->percent_under_distributer  * (float) $amount_paiement_robus;
                            } else {

                                $commission_transaction_val_percent_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                            }

                            if (robus()->country->currencie->value > $countrie->currencie->value) {

                                $convertion_transaction_countrie = (float)   $amount_paiement_robus /   (float) $countrie->currencie->value;

                                $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                                if ($amount_send) {

                                    if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                        $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $amount_paiement_robus;
                                    } else {

                                        $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                    }

                                    $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_percent_pay;

                                    //   Convertion  in CFA
                                    $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify *   (float) robus()->country->currencie->value;

                                    //   Convertion in Country send
                                    $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $countrie->currencie->value;


                                    if ($valeur_verify < 0) {

                                        $commission_transaction_val_inter_receive =  "Montant negatif";
                                    } else {

                                        $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                    }
                                } else {
                                    return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                                }
                            } elseif (robus()->country->currencie->value == $countrie->currencie->value) {

                                $convertion_transaction_countrie = (float)   $amount_paiement_robus;

                                $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                                if ($amount_send) {

                                    if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                        $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $amount_paiement_robus;
                                    } else {

                                        $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                    }

                                    $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_percent_pay;

                                    //   Convertion  in CFA
                                    $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify;

                                    //   Convertion in Country send
                                    $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa;

                                    if ($valeur_verify < 0) {

                                        $commission_transaction_val_inter_receive =  "Montant negatif";
                                    } else {

                                        $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                    }
                                } else {
                                    return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                                }
                            } elseif (robus()->country->currencie->value < $countrie->currencie->value) {

                                $convertion_transaction_countrie = (float)   $amount_paiement_robus  /   (float) $countrie->currencie->value;

                                $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                                if ($amount_send) {


                                    if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                        $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $amount_paiement_robus;
                                    } else {

                                        $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                    }

                                    $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_percent_pay;

                                    //   Convertion  in CFA
                                    $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify *   (float) robus()->country->currencie->value;

                                    //   Convertion in Country send
                                    $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $countrie->currencie->value;


                                    if ($valeur_verify < 0) {

                                        $commission_transaction_val_inter_receive =  "Montant negatif";
                                    } else {

                                        $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                    }
                                } else {

                                    return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                                }
                            }
                        }
                    } else {

                        if ($provider_inter_out_civ == 51) {

                            if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                $commission_transaction_val_percent_pay =  (float)$commission_inter->percent_under_distributer  * (float) $amount_paiement_robus;
                            } else {

                                $commission_transaction_val_percent_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                            }

                            if (robus()->country->currencie->value > $countrie->currencie->value) {

                                // Verify amount send maxi
                                // $convertion_transaction_val_inter_receive = (float)   $default_valeur  /   (float) $countrie->currencie->value;

                                // if ($convertion_transaction_val_inter_receive < $amount_paiement_robus) {

                                //     $commission_transaction_val_percent_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_receive = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                // } else {

                                if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $commission_transaction_val_percent_pay;
                                } else {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                }

                                $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_inter_pay;

                                //   Convertion  in CFA
                                $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify *   (float) robus()->country->currencie->value;

                                //   Convertion in Country send
                                $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $countrie->currencie->value;


                                if ($valeur_verify < 0) {

                                    $commission_transaction_val_inter_receive =  "Montant negatif";
                                } else {

                                    $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                }
                                // }
                            } elseif (robus()->country->currencie->value == $countrie->currencie->value) {

                                // $convertion_transaction_val_inter_receive = (float)   $default_valeur;

                                // if ($convertion_transaction_val_inter_receive < $amount_paiement_robus) {

                                //     $commission_transaction_val_percent_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_receive = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                // } else {


                                if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $commission_transaction_val_percent_pay;
                                } else {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                }

                                $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_inter_pay;

                                //   Convertion  in CFA
                                $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify;

                                //   Convertion in Country send
                                $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa;


                                if ($valeur_verify < 0) {

                                    $commission_transaction_val_inter_receive =  "Montant negatif";
                                } else {

                                    $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                }
                                // }
                            } elseif (robus()->country->currencie->value < $countrie->currencie->value) {

                                // $convertion_transaction_val_inter_receive = (float)   $default_valeur  /   (float) $countrie->currencie->value;

                                // if ($convertion_transaction_val_inter_receive < $amount_paiement_robus) {

                                //     $commission_transaction_val_percent_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_receive = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                // } else {

                                if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $commission_transaction_val_percent_pay;
                                } else {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                }

                                $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_inter_pay;

                                //   Convertion  in CFA
                                $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify *   (float) robus()->country->currencie->value;

                                //   Convertion in Country send
                                $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $countrie->currencie->value;


                                if ($valeur_verify < 0) {

                                    $commission_transaction_val_inter_receive =  "Montant negatif";
                                } else {

                                    $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                }
                                // }
                            }
                        } else {


                            if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                $commission_transaction_val_percent_pay =  (float)$commission_inter->percent_under_distributer  * (float) $amount_paiement_robus;
                            } else {

                                $commission_transaction_val_percent_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                            }

                            if (robus()->country->currencie->value > $countrie->currencie->value) {

                                $convertion_transaction_countrie = (float)   $amount_paiement_robus /   (float) $countrie->currencie->value;

                                $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                                if ($amount_send) {

                                    if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                        $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $amount_paiement_robus;
                                    } else {

                                        $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                    }

                                    $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_percent_pay;

                                    //   Convertion  in CFA
                                    $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify *   (float) robus()->country->currencie->value;

                                    //   Convertion in Country send
                                    $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $countrie->currencie->value;


                                    if ($valeur_verify < 0) {

                                        $commission_transaction_val_inter_receive =  "Montant negatif";
                                    } else {

                                        $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                    }
                                } else {
                                    return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                                }
                            } elseif (robus()->country->currencie->value == $countrie->currencie->value) {

                                $convertion_transaction_countrie = (float)   $amount_paiement_robus;

                                $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                                if ($amount_send) {

                                    if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                        $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $amount_paiement_robus;
                                    } else {

                                        $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                    }

                                    $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_percent_pay;

                                    //   Convertion  in CFA
                                    $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify;

                                    //   Convertion in Country send
                                    $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa;

                                    if ($valeur_verify < 0) {

                                        $commission_transaction_val_inter_receive =  "Montant negatif";
                                    } else {

                                        $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                    }
                                } else {
                                    return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                                }
                            } elseif (robus()->country->currencie->value < $countrie->currencie->value) {

                                $convertion_transaction_countrie = (float)   $amount_paiement_robus  /   (float) $countrie->currencie->value;

                                $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                                if ($amount_send) {


                                    if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                        $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $amount_paiement_robus;
                                    } else {

                                        $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                    }

                                    $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_percent_pay;

                                    //   Convertion  in CFA
                                    $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify *   (float) robus()->country->currencie->value;

                                    //   Convertion in Country send
                                    $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $countrie->currencie->value;


                                    if ($valeur_verify < 0) {

                                        $commission_transaction_val_inter_receive =  "Montant negatif";
                                    } else {

                                        $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                    }
                                } else {

                                    return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                                }
                            }
                        }
                    }

                    return response()->json([
                        'success' => $commission_inter != null ? true : false,
                        'data' =>  [
                            'amount_commission' => $commission_transaction_val_percent_pay,
                            'amount_pay' => $commission_transaction_val_inter_pay,
                            'amount_receive' => $commission_transaction_val_inter_receive . ' ' .  $countrie->currencie->code,
                        ],
                    ], 200);
                } else {
                    return response()->json([
                        'success' =>  false,
                        'message' => 'Pas de selection du pourcentage',
                    ], 200);
                }
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

    public function search_payment_robus_inter_out($amount_paiement_robus, $countrie_id, $provider_inter_out_civ)
    {
        $countrie = Country::whereName($countrie_id)->first();
        $default_valeur = 2000000;
        $valeur_max_send  =  $countrie->amount_max_send;

        try {

            if ($amount_paiement_robus) {

                $commission_inter =  $this->percentRobus->getRobusPercentageInter($countrie, $provider_inter_out_civ);
                $commission_transaction_val_percent_pay = 0;
                $commission_transaction_val_inter_pay = 0;
                $commission_transaction_val_inter_pay_after = 0;
                $commission_transaction_val_inter_receive = 0;
                $convertion_transaction_val_inter_receive = 0;

                if ($commission_inter != null) {

                    if ($countrie->id == 107) {

                        if ($provider_inter_out_civ == 51) {

                            if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                $commission_transaction_val_percent_pay =  (float)$commission_inter->percent_under_distributer  * (float) $amount_paiement_robus;
                            } else {

                                $commission_transaction_val_percent_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                            }


                            if (robus()->country->currencie->value > $countrie->currencie->value) {

                                // Verify amount send maxi
                                // $convertion_transaction_val_inter_receive = (float)   $default_valeur  /   (float) $countrie->currencie->value;

                                // if ($convertion_transaction_val_inter_receive < $amount_paiement_robus) {

                                //     $commission_transaction_val_percent_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_receive = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                // } else {


                                if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $commission_transaction_val_percent_pay;
                                } else {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                }

                                $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_percent_pay;

                                //   Convertion  in CFA
                                $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify *   (float) robus()->country->currencie->value;

                                //   Convertion in Country send
                                $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $countrie->currencie->value;


                                if ($valeur_verify < 0) {

                                    $commission_transaction_val_inter_receive =  "Montant negatif";
                                } else {

                                    $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                }
                                // }
                            } elseif (robus()->country->currencie->value == $countrie->currencie->value) {

                                $convertion_transaction_val_inter_receive =  $default_valeur;

                                // if ($convertion_transaction_val_inter_receive < $amount_paiement_robus) {

                                //     $commission_transaction_val_percent_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_receive = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                // } else {

                                if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $commission_transaction_val_percent_pay;
                                } else {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                }

                                $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_percent_pay;

                                //   Convertion  in CFA
                                $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify;

                                //   Convertion in Country send
                                $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa;


                                if ($valeur_verify < 0) {

                                    $commission_transaction_val_inter_receive =  "Montant negatif";
                                } else {

                                    $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                }
                                // }
                            } elseif (robus()->country->currencie->value < $countrie->currencie->value) {

                                // $convertion_transaction_val_inter_receive =  $default_valeur /   (float) $countrie->currencie->value;

                                // if ($convertion_transaction_val_inter_receive < $amount_paiement_robus) {

                                //     $commission_transaction_val_percent_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_receive = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                // } else {

                                if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $commission_transaction_val_percent_pay;
                                } else {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                }

                                $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_percent_pay;

                                //   Convertion  in CFA
                                $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify *   (float) robus()->country->currencie->value;

                                //   Convertion in Country send
                                $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $countrie->currencie->value;


                                if ($valeur_verify < 0) {

                                    $commission_transaction_val_inter_receive =  "Montant negatif";
                                } else {

                                    $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                }
                                // }
                            }
                        } else {


                            if (robus()->country->name_groupe == 'URP') {

                                // $commission_inter = TransactionCost::whereId(8)->first();
                                $commission_inter_inter = TransactionCost::whereId(8)->first();

                                $valeur_end = $commission_inter_inter->levelTransactions->last();

                                $conversion_valeur_end =  $valeur_end->amount_end;

                                // if ($valeur_end->amount_end < $amount_paiement_robus) {

                                //     $commission_transaction_val_inter_pay  = "La valeur maximun est atteinte" .  $conversion_valeur_end;
                                //     $commission_transaction_val_inter_receive =  "La valeur maximun est atteinte" .  $conversion_valeur_end;
                                // }

                                $valeur_amount =  $amount_paiement_robus;

                                if ($commission_inter_inter) {

                                    foreach ($commission_inter_inter->levelTransactions as $amount_comission) {

                                        if ($valeur_amount >= $amount_comission->amount_begin && $valeur_amount <= $amount_comission->amount_end) {

                                            if ($amount_comission->percent == null) {

                                                $commission_transaction_val_percent_pay =  $amount_comission->amount;

                                                if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {
                                                    $commission_transaction_val_inter_pay_after =  (float)$amount_comission->comission_distributer;
                                                } else {

                                                    $commission_transaction_val_inter_pay_after =  (float)$amount_comission->amount;
                                                }
                                            } else {

                                                $commission_transaction_val_percent_pay = $valeur_amount * $amount_comission->percent;

                                                if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {
                                                    $commission_transaction_val_inter_pay_after =  (float)$amount_comission->comission_distributer  * (float) $valeur_amount;
                                                } else {

                                                    $commission_transaction_val_inter_pay_after =  (float)$amount_comission->percent * (float) $valeur_amount;
                                                }
                                            }

                                            if (robus()->country->currencie->value > $countrie->currencie->value) {


                                                if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                                    $commission_transaction_val_inter_pay =  $commission_transaction_val_inter_pay_after;
                                                } else {

                                                    $commission_transaction_val_inter_pay =  $commission_transaction_val_inter_pay_after;
                                                }

                                                $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_percent_pay;

                                                //   Convertion  in CFA
                                                $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify *   (float) robus()->country->currencie->value;

                                                //   Convertion in Country send
                                                $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $countrie->currencie->value;


                                                if ($valeur_verify < 0) {

                                                    $commission_transaction_val_inter_receive =  "Montant negatif";
                                                } else {

                                                    $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                                }
                                            } elseif (robus()->country->currencie->value == $countrie->currencie->value) {

                                                if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                                    $commission_transaction_val_inter_pay =  $commission_transaction_val_inter_pay_after;
                                                } else {

                                                    $commission_transaction_val_inter_pay =  $commission_transaction_val_inter_pay_after;
                                                }

                                                $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_percent_pay;

                                                //   Convertion  in CFA
                                                $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify;

                                                //   Convertion in Country send
                                                $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa;


                                                if ($valeur_verify < 0) {

                                                    $commission_transaction_val_inter_receive =  "Montant negatif";
                                                } else {

                                                    $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                                }
                                            } elseif (robus()->country->currencie->value > $countrie->currencie->value) {

                                                if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                                    $commission_transaction_val_inter_pay =  $commission_transaction_val_inter_pay_after;
                                                } else {

                                                    $commission_transaction_val_inter_pay =  $commission_transaction_val_inter_pay_after;
                                                }

                                                $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_percent_pay;

                                                //   Convertion  in CFA
                                                $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify *   (float) robus()->country->currencie->value;

                                                //   Convertion in Country send
                                                $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $countrie->currencie->value;


                                                if ($valeur_verify < 0) {

                                                    $commission_transaction_val_inter_receive =  "Montant negatif";
                                                } else {

                                                    $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                                }
                                            }
                                        }
                                    }
                                } else {

                                    $commission_transaction_val_inter_pay  = "Pas Commission";
                                    $commission_transaction_val_inter_receive =  "Pas Commission";
                                }
                            } else {

                                if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                    $commission_transaction_val_percent_pay =  (float)$commission_inter->percent_under_distributer  * (float) $amount_paiement_robus;
                                } else {

                                    $commission_transaction_val_percent_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                }

                                if (robus()->country->currencie->value > $countrie->currencie->value) {

                                    $convertion_transaction_countrie = (float)   $amount_paiement_robus /   (float) $countrie->currencie->value;

                                    $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                                    if ($amount_send) {

                                        if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                            $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $amount_paiement_robus;
                                        } else {

                                            $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                        }

                                        $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_percent_pay;

                                        //   Convertion  in CFA
                                        $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify *   (float) robus()->country->currencie->value;

                                        //   Convertion in Country send
                                        $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $countrie->currencie->value;


                                        if ($valeur_verify < 0) {

                                            $commission_transaction_val_inter_receive =  "Montant negatif";
                                        } else {

                                            $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                        }
                                    } else {
                                        return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                                    }
                                } elseif (robus()->country->currencie->value == $countrie->currencie->value) {

                                    $convertion_transaction_countrie = (float)   $amount_paiement_robus;

                                    $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                                    if ($amount_send) {

                                        if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                            $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $amount_paiement_robus;
                                        } else {

                                            $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                        }

                                        $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_percent_pay;

                                        //   Convertion  in CFA
                                        $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify;

                                        //   Convertion in Country send
                                        $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa;

                                        if ($valeur_verify < 0) {

                                            $commission_transaction_val_inter_receive =  "Montant negatif";
                                        } else {

                                            $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                        }
                                    } else {
                                        return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                                    }
                                } elseif (robus()->country->currencie->value < $countrie->currencie->value) {

                                    $convertion_transaction_countrie = (float)   $amount_paiement_robus  /   (float) $countrie->currencie->value;

                                    $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                                    if ($amount_send) {


                                        if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                            $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $amount_paiement_robus;
                                        } else {

                                            $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                        }

                                        $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_percent_pay;

                                        //   Convertion  in CFA
                                        $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify *   (float) robus()->country->currencie->value;

                                        //   Convertion in Country send
                                        $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $countrie->currencie->value;


                                        if ($valeur_verify < 0) {

                                            $commission_transaction_val_inter_receive =  "Montant negatif";
                                        } else {

                                            $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                        }
                                    } else {

                                        return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                                    }
                                }
                            }
                        }
                    } else {

                        if ($provider_inter_out_civ == 51) {

                            if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                $commission_transaction_val_percent_pay =  (float)$commission_inter->percent_under_distributer  * (float) $amount_paiement_robus;
                            } else {

                                $commission_transaction_val_percent_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                            }

                            if (robus()->country->currencie->value > $countrie->currencie->value) {

                                // Verify amount send maxi
                                // $convertion_transaction_val_inter_receive = (float)   $default_valeur  /   (float) $countrie->currencie->value;

                                // if ($convertion_transaction_val_inter_receive < $amount_paiement_robus) {

                                //     $commission_transaction_val_percent_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_receive = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                // } else {

                                if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $commission_transaction_val_percent_pay;
                                } else {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                }

                                $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_inter_pay;

                                //   Convertion  in CFA
                                $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify *   (float) robus()->country->currencie->value;

                                //   Convertion in Country send
                                $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $countrie->currencie->value;


                                if ($valeur_verify < 0) {

                                    $commission_transaction_val_inter_receive =  "Montant negatif";
                                } else {

                                    $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                }
                                // }
                            } elseif (robus()->country->currencie->value == $countrie->currencie->value) {

                                $convertion_transaction_val_inter_receive = (float)   $default_valeur;

                                // if ($convertion_transaction_val_inter_receive < $amount_paiement_robus) {

                                //     $commission_transaction_val_percent_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_receive = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                // } else {


                                if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $commission_transaction_val_percent_pay;
                                } else {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                }

                                $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_inter_pay;

                                //   Convertion  in CFA
                                $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify;

                                //   Convertion in Country send
                                $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa;


                                if ($valeur_verify < 0) {

                                    $commission_transaction_val_inter_receive =  "Montant negatif";
                                } else {

                                    $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                }
                                // }
                            } elseif (robus()->country->currencie->value < $countrie->currencie->value) {

                                // $convertion_transaction_val_inter_receive = (float)   $default_valeur  /   (float) $countrie->currencie->value;

                                // if ($convertion_transaction_val_inter_receive < $amount_paiement_robus) {

                                //     $commission_transaction_val_percent_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_pay = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                //     $commission_transaction_val_inter_receive = "Le montant maxi est de " . $convertion_transaction_val_inter_receive;
                                // } else {

                                if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $commission_transaction_val_percent_pay;
                                } else {

                                    $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                }

                                $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_inter_pay;

                                //   Convertion  in CFA
                                $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify *   (float) robus()->country->currencie->value;

                                //   Convertion in Country send
                                $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $countrie->currencie->value;


                                if ($valeur_verify < 0) {

                                    $commission_transaction_val_inter_receive =  "Montant negatif";
                                } else {

                                    $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                }
                                // }
                            }
                        } else {


                            if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                $commission_transaction_val_percent_pay =  (float)$commission_inter->percent_under_distributer  * (float) $amount_paiement_robus;
                            } else {

                                $commission_transaction_val_percent_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                            }

                            if (robus()->country->currencie->value > $countrie->currencie->value) {

                                $convertion_transaction_countrie = (float)   $amount_paiement_robus /   (float) $countrie->currencie->value;

                                $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                                if ($amount_send) {

                                    if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                        $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $amount_paiement_robus;
                                    } else {

                                        $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                    }

                                    $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_percent_pay;

                                    //   Convertion  in CFA
                                    $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify *   (float) robus()->country->currencie->value;

                                    //   Convertion in Country send
                                    $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $countrie->currencie->value;


                                    if ($valeur_verify < 0) {

                                        $commission_transaction_val_inter_receive =  "Montant negatif";
                                    } else {

                                        $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                    }
                                } else {
                                    return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                                }
                            } elseif (robus()->country->currencie->value == $countrie->currencie->value) {

                                $convertion_transaction_countrie = (float)   $amount_paiement_robus;

                                $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                                if ($amount_send) {

                                    if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                        $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $amount_paiement_robus;
                                    } else {

                                        $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                    }

                                    $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_percent_pay;

                                    //   Convertion  in CFA
                                    $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify;

                                    //   Convertion in Country send
                                    $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa;

                                    if ($valeur_verify < 0) {

                                        $commission_transaction_val_inter_receive =  "Montant negatif";
                                    } else {

                                        $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                    }
                                } else {
                                    return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                                }
                            } elseif (robus()->country->currencie->value < $countrie->currencie->value) {

                                $convertion_transaction_countrie = (float)   $amount_paiement_robus  /   (float) $countrie->currencie->value;

                                $amount_send =  $this->validatedamountDays->validateAmountSendLimit($convertion_transaction_countrie, $valeur_max_send);

                                if ($amount_send) {


                                    if (robus()->is_under_distributer == 1 ||  robus()->is_distributer == 1) {

                                        $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $amount_paiement_robus;
                                    } else {

                                        $commission_transaction_val_inter_pay =  (float)$commission_inter->percent  * (float) $amount_paiement_robus;
                                    }

                                    $valeur_verify =  (float) $amount_paiement_robus - $commission_transaction_val_percent_pay;

                                    //   Convertion  in CFA
                                    $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify *   (float) robus()->country->currencie->value;

                                    //   Convertion in Country send
                                    $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $countrie->currencie->value;


                                    if ($valeur_verify < 0) {

                                        $commission_transaction_val_inter_receive =  "Montant negatif";
                                    } else {

                                        $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                                    }
                                } else {

                                    return   json_message_response(false, "Le montant maximum a envoyé est de : " . $valeur_max_send . ' '  . $countrie->currencie->code, 333);
                                }
                            }
                        }
                    }

                    return response()->json([
                        'success' => $commission_inter != null ? true : false,
                        'data' =>  [
                            'amount_commission' => $commission_transaction_val_percent_pay,
                            'amount_pay' => $commission_transaction_val_inter_pay,
                            'amount_receive' => $commission_transaction_val_inter_receive . ' ' .  $countrie->currencie->code,
                        ],
                    ], 200);
                } else {
                    return response()->json([
                        'success' =>  false,
                        'message' => 'Pas de selection du pourcentage',
                    ], 200);
                }
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

    public function search_payment_inter_CIV($country, $amount_paiement_robus)
    {

        $wallet_user = Auth::guard('api')->user();

        $default_valeur = 2000000;


        if ($wallet_user->country->code == 'CI') {
            // Countrie ID 42 is CANTRE AFRIQUE
            if ($country  == 42) {

                //Valuer inter  transaction
                $commission_inter = TransactionCost::whereId(5)->first();

                $valeur_amount =  $amount_paiement_robus;

                // Verify valeur amount max in Landing the ROBUS
                $valeur_end = $commission_inter->levelTransactionIvoireInters->last();

                $conversion_valeur_end =  $valeur_end->amount_end;
                //Verify amount in transactions 
                if ($conversion_valeur_end  < $valeur_amount) {
                    $commission_transaction_val_inter_pay = "Montant maxi est  : " .  $conversion_valeur_end;

                    $commission_transaction_val_inter_receive = "Montant maxi est  : " .  $conversion_valeur_end;

                    return response()->json([
                        'success' => $commission_inter != null ? true : false,
                        'data' =>  [
                            'amount_pay' => $commission_transaction_val_inter_pay,
                            'amount_receive' => $commission_transaction_val_inter_receive,
                        ],
                    ], 200);
                } else {

                    foreach ($commission_inter->levelTransactionIvoireInters as $amount_comission) {
                        if ($valeur_amount >= $amount_comission->amount_begin && $valeur_amount <= $amount_comission->amount_end) {

                            $commission_transaction_val_inter_pay =  $amount_comission->amount;

                            $valeur_verify =   $valeur_amount  -  $commission_transaction_val_inter_pay;

                            if ($valeur_verify < 0) {
                                $commission_transaction_val_inter_receive = "Montant negatif";
                            } else {

                                $commission_transaction_val_inter_receive =   $valeur_verify;
                            }

                            return response()->json([
                                'success' => $commission_inter != null ? true : false,
                                'data' =>  [
                                    'amount_pay' => $commission_transaction_val_inter_pay,
                                    'amount_receive' => $commission_transaction_val_inter_receive,
                                ],
                            ], 200);
                        }
                    }
                }
            } elseif ($country  == 146) {

                // countries Maroc
                //Valuer inter  transaction
                $commission_inter = TransactionCost::whereId(20)->first();

                $valeur_amount =  $amount_paiement_robus;

                // Verify valeur amount max in Landing the ROBUS
                $valeur_end = $commission_inter->levelTransactionIvoireInters->last();

                $conversion_valeur_end =  $valeur_end->amount_end;
                //Verify amount in transactions 
                if ($conversion_valeur_end  < $valeur_amount) {
                    $commission_transaction_val_inter_pay = "Montant maxi est  : " .  $conversion_valeur_end;

                    $commission_transaction_val_inter_receive = "Montant maxi est  : " .  $conversion_valeur_end;

                    return response()->json([
                        'success' => $commission_inter != null ? true : false,
                        'data' =>  [
                            'amount_pay' => $commission_transaction_val_inter_pay,
                            'amount_receive' => $commission_transaction_val_inter_receive,
                        ],
                    ], 200);
                } else {

                    foreach ($commission_inter->levelTransactionIvoireInters as $amount_comission) {
                        if ($valeur_amount >= $amount_comission->amount_begin && $valeur_amount <= $amount_comission->amount_end) {

                            $commission_transaction_val_inter_pay =  $amount_comission->amount;

                            $valeur_verify =   $valeur_amount  -  $commission_transaction_val_inter_pay;


                            if ($valeur_verify < 0) {
                                $commission_transaction_val_inter_receive = "Montant negatif";
                            } else {

                                $commission_transaction_val_inter_receive =   $valeur_verify;
                            }

                            return response()->json([
                                'success' => $commission_inter != null ? true : false,
                                'data' =>  [
                                    'amount_pay' => $commission_transaction_val_inter_pay,
                                    'amount_receive' => $commission_transaction_val_inter_receive,
                                ],
                            ], 200);
                        }
                    }
                }
            } elseif ($country  == 74) {
                // Countries FR
                //Valuer inter  transaction
                $commission_inter = TransactionCost::whereId(10)->first();

                $valeur_amount =  $amount_paiement_robus;

                // Verify valeur amount max in Landing the ROBUS
                $valeur_end = $commission_inter->levelTransactionIvoireInters->last();

                $conversion_valeur_end =  $valeur_end->amount_end;
                //Verify amount in transactions 
                if ($conversion_valeur_end  < $valeur_amount) {
                    $commission_transaction_val_inter_pay = "Montant maxi est  : " .  $conversion_valeur_end;

                    $commission_transaction_val_inter_receive = "Montant maxi est  : " .  $conversion_valeur_end;

                    return response()->json([
                        'success' => $commission_inter != null ? true : false,
                        'data' =>  [
                            'amount_pay' => $commission_transaction_val_inter_pay,
                            'amount_receive' => $commission_transaction_val_inter_receive,
                        ],
                    ], 200);
                } else {

                    foreach ($commission_inter->levelTransactionIvoireInters as $amount_comission) {
                        if ($valeur_amount >= $amount_comission->amount_begin && $valeur_amount <= $amount_comission->amount_end) {

                            $commission_transaction_val_inter_pay =  $amount_comission->amount;

                            $valeur_verify =   $valeur_amount  -  $commission_transaction_val_inter_pay;


                            if ($valeur_verify < 0) {
                                $commission_transaction_val_inter_receive = "Montant negatif";
                            } else {

                                $commission_transaction_val_inter_receive =   $valeur_verify;
                            }

                            return response()->json([
                                'success' => $commission_inter != null ? true : false,
                                'data' =>  [
                                    'amount_pay' => $commission_transaction_val_inter_pay,
                                    'amount_receive' => $commission_transaction_val_inter_receive,
                                ],
                            ], 200);
                        }
                    }
                }
            } elseif ($country  == 83) {
                // Countries Is CHANA
                $commission_inter = TransactionCost::whereId(15)->first();

                //  Valeur is CFA        
                if ($default_valeur  < $amount_paiement_robus) {

                    $commission_transaction_val_inter_pay = "Montant maxi est  : " .  $conversion_valeur_end;

                    $commission_transaction_val_inter_receive = "Montant maxi est  : " .  $conversion_valeur_end;

                    return response()->json([
                        'success' => $commission_inter != null ? true : false,
                        'data' =>  [
                            'amount_pay' => $commission_transaction_val_inter_pay,
                            'amount_receive' => $commission_transaction_val_inter_receive,
                        ],
                    ], 200);
                } else {

                    //Valuer inter  transaction
                    $commission_inter = TransactionCost::whereId(15)->first();

                    $commission_transaction_val_inter_pay = $commission_inter->percent  *  $amount_paiement_robus;

                    $valeur_verify =   $amount_paiement_robus -  $commission_transaction_val_inter_pay;

                    if ($valeur_verify < 0) {
                        $commission_transaction_val_inter_receive =  "";
                    } else {

                        $commission_transaction_val_inter_receive = $valeur_verify;
                    }

                    return response()->json([
                        'success' => $commission_inter != null ? true : false,
                        'data' =>  [
                            'amount_pay' => $commission_transaction_val_inter_pay,
                            'amount_receive' => $commission_transaction_val_inter_receive,
                        ],
                    ], 200);
                }
            } elseif ($country  == 218) {
                // Countries is TUNISIE
                //Valuer inter  transaction
                $commission_inter = TransactionCost::whereId(25)->first();

                $valeur_amount =  $amount_paiement_robus;

                // Verify valeur amount max in Landing the ROBUS
                $valeur_end = $commission_inter->levelTransactionIvoireInters->last();

                $conversion_valeur_end =  $valeur_end->amount_end;
                //Verify amount in transactions 
                if ($conversion_valeur_end  < $valeur_amount) {
                    $commission_transaction_val_inter_pay = "Montant maxi est  : " .  $conversion_valeur_end;

                    $commission_transaction_val_inter_receive = "Montant maxi est  : " .  $conversion_valeur_end;

                    return response()->json([
                        'success' => $commission_inter != null ? true : false,
                        'data' =>  [
                            'amount_pay' => $commission_transaction_val_inter_pay,
                            'amount_receive' => $commission_transaction_val_inter_receive,
                        ],
                    ], 200);
                } else {

                    foreach ($commission_inter->levelTransactionIvoireInters as $amount_comission) {
                        if ($valeur_amount >= $amount_comission->amount_begin && $valeur_amount <= $amount_comission->amount_end) {

                            $commission_transaction_val_inter_pay =  $amount_comission->amount;

                            $valeur_verify =   $valeur_amount  -  $commission_transaction_val_inter_pay;


                            if ($valeur_verify < 0) {
                                $commission_transaction_val_inter_receive = "Montant negatif";
                            } else {

                                $commission_transaction_val_inter_receive =   $valeur_verify;
                            }

                            return response()->json([
                                'success' => $commission_inter != null ? true : false,
                                'data' =>  [
                                    'amount_pay' => $commission_transaction_val_inter_pay,
                                    'amount_receive' => $commission_transaction_val_inter_receive,
                                ],
                            ], 200);
                        }
                    }
                }
            }
        }
    }
    public function search_payment_mooney_inter($amount_paiement_mooney)
    {

        $wallet_user = Auth::guard('api')->user();

        $default_valeur = 2000000;

        // valeur amount virement bank

        try {

            // Inter commission transactions 

            if ($amount_paiement_mooney != '') {


                if ($wallet_user->country->code == 'CF') {
                    //Valuer inter  transaction
                    $commission_inter = TransactionCost::whereId(3)->first();

                    $valeur_amount = $amount_paiement_mooney;

                    foreach ($commission_inter->levelTransactions  as $amount_comission) {
                        if (
                            $valeur_amount >= $amount_comission->amount_begin &&
                            $valeur_amount <= $amount_comission->amount_end
                        ) {

                            $commission_transaction_val_inter_pay =  $amount_comission->amount;

                            $valeur_verify =   $valeur_amount - $commission_transaction_val_inter_pay;;

                            if ($valeur_verify < 0) {
                                $commission_transaction_val_inter_receive =   'La valeur SVP';
                            } else {

                                $commission_transaction_val_inter_receive =   $valeur_verify;
                            }

                            return response()->json([
                                'success' => $commission_inter != null ? true : false,
                                'data' =>  [
                                    'amount_pay' => $commission_transaction_val_inter_pay,
                                    'amount_receive' => $commission_transaction_val_inter_receive,
                                ],
                            ], 200);
                        }
                    }
                } elseif ($wallet_user->country->code == 'GH') {
                    //Countries GHANA 
                    //  Conversion valeur
                    $conversion_valeur_end  =  $default_valeur  /  $wallet_user->wallet->currencie->value;


                    $commission_inter = TransactionCost::whereId(13)->first();

                    $commission_transaction_val_inter_pay = $commission_inter->percent  *  $amount_paiement_mooney;

                    $valeur_verify =   $amount_paiement_mooney -  $commission_transaction_val_inter_pay;

                    if ($valeur_verify < 0) {
                        $commission_transaction_val_inter_receive =  "";
                    } else {

                        $commission_transaction_val_inter_receive = $valeur_verify;
                    }

                    return response()->json([
                        'success' => $commission_inter != null ? true : false,
                        'data' =>  [
                            'amount_pay' => $commission_transaction_val_inter_pay,
                            'amount_receive' => $commission_transaction_val_inter_receive,
                        ],
                    ], 200);
                } elseif ($wallet_user->country->code == 'MA') {
                    // Countries Maroc
                    //  Conversion valeur
                    $conversion_valeur_end  =  $default_valeur  /  $wallet_user->wallet->currencie->value;


                    //Valuer inter  transaction
                    $commission_inter = TransactionCost::whereId(18)->first();

                    $commission_transaction_val_inter_pay = $commission_inter->percent  *  $amount_paiement_mooney;

                    $valeur_verify =   $amount_paiement_mooney - $commission_transaction_val_inter_pay;

                    if ($valeur_verify < 0) {
                        $commission_transaction_val_inter_receive =  "";
                    } else {

                        $commission_transaction_val_inter_receive = $valeur_verify;
                    }

                    return response()->json([
                        'success' => $commission_inter != null ? true : false,
                        'data' =>  [
                            'amount_pay' => $commission_transaction_val_inter_pay,
                            'amount_receive' => $commission_transaction_val_inter_receive,
                        ],
                    ], 200);
                } elseif ($wallet_user->country->code == 'FR') {

                    //Valuer inter  transaction
                    $commission_inter = TransactionCost::whereId(8)->first();

                    $valeur_amount =  $amount_paiement_mooney;

                    foreach ($commission_inter->levelTransactions as $amount_comission) {
                        if ($valeur_amount >= $amount_comission->amount_begin && $valeur_amount <= $amount_comission->amount_end) {


                            $valeur_verify = $valeur_amount -  $amount_comission->amount;

                            $commission_transaction_val_inter_pay = $amount_comission->amount;

                            if ($valeur_verify < 0) {
                                $commission_transaction_val_inter_receive =   "Montant negatif";
                            } else {

                                $commission_transaction_val_inter_receive = $valeur_verify;
                            }

                            return response()->json([
                                'success' => $commission_inter != null ? true : false,
                                'data' =>  [
                                    'amount_pay' => $commission_transaction_val_inter_pay,
                                    'amount_receive' => $commission_transaction_val_inter_receive,
                                ],
                            ], 200);
                        }
                    }
                } elseif ($wallet_user->country->code == 'CI') {

                    //Valuer inter  transaction
                    $commission_inter = TransactionCost::whereId(27)->first();

                    $valeur_amount = $amount_paiement_mooney;


                    foreach ($commission_inter->levelTransactions  as $amount_comission) {
                        if (
                            $valeur_amount >= $amount_comission->amount_begin &&
                            $valeur_amount <= $amount_comission->amount_end
                        ) {

                            $commission_transaction_val_inter_pay =  $amount_comission->amount;

                            $valeur_verify =   $valeur_amount  -  $commission_transaction_val_inter_pay;

                            if ($valeur_verify < 0) {
                                $commission_transaction_val_inter_receive =   'La valeur SVP';
                            } else {

                                $commission_transaction_val_inter_receive =   $valeur_verify;
                            }

                            return response()->json([
                                'success' => $commission_inter != null ? true : false,
                                'data' =>  [
                                    'amount_pay' => $commission_transaction_val_inter_pay,
                                    'amount_receive' => $commission_transaction_val_inter_receive,
                                ],
                            ], 200);
                        }
                    }
                } elseif ($wallet_user->country->code == 'TN') {

                    // Countries TUnisie
                    //Valuer inter  transaction
                    $commission_inter = TransactionCost::whereId(23)->first();

                    $commission_transaction_val_inter_pay = $commission_inter->percent  *  $amount_paiement_mooney;

                    $valeur_verify =   $amount_paiement_mooney -   $commission_transaction_val_inter_pay;

                    if ($valeur_verify < 0) {
                        $commission_transaction_val_inter_receive =  "Montant negatif";
                    } else {

                        $commission_transaction_val_inter_receive = $valeur_verify;
                    }

                    return response()->json([
                        'success' => $commission_inter != null ? true : false,
                        'data' =>  [
                            'amount_pay' => $commission_transaction_val_inter_pay,
                            'amount_receive' => $commission_transaction_val_inter_receive,
                        ],
                    ], 200);
                }
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' =>  false,
                'message' => 'Pas d\'utilisateur',
            ], 200);
        }
    }
    public function paiement_invoice_inter($amount_invoice_inter)
    {


        $default_valeur = 2000000;

        try {

            if ($amount_invoice_inter != '') {

                $commission_invoice = PaymentCost::whereId(1)->first();

                $commission_reload_inter_val_inter_percent_pay = 0;

                if (robus()->is_under_distributer) {
                    $commission_paiement_invoice_val_inter_pay = $this->convertionservice->convertAdminCommissionInvoice($commission_invoice->comission_auther);

                    $commission_reload_inter_val_inter_percent_pay = $this->convertionservice->convertAdminCommissionInvoice($commission_invoice->amount_auther);
                } else {

                    $commission_paiement_invoice_val_inter_pay = $this->convertionservice->convertAdminCommissionInvoice($commission_invoice->amount_auther);

                    $commission_reload_inter_val_inter_percent_pay = $commission_paiement_invoice_val_inter_pay;
                }


                $valeur_receive =  (float) $amount_invoice_inter;

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

        $wallet_user = Auth::guard('api')->user();

        $default_valeur = 2000000;

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

                                if (robus()->is_under_distributer) {

                                    $valeur_cfa =   $amount_comission->comission_distributer *  $valeur_amount;
                                } else {

                                    $valeur_cfa =   $amount_comission->percent * $valeur_amount;
                                }

                                $commission_reload_inter_val_inter_pay =  $this->convertionservice->convertAdminCommissionInvoice($valeur_cfa);

                                // Valeur receive 

                                $receive_valeur = $valeur_amount - $percent_distr_cfa;

                                $commission_reload_inter_val_inter_percent_pay =  $this->convertionservice->convertAdminCommissionInvoice($percent_distr_cfa);


                                if ($receive_valeur < 0) {

                                    $commission_reload_inter_val_inter_receive = "Montant negatif";
                                } else {

                                    $commission_reload_inter_val_inter_receive = $receive_valeur;
                                }
                            } else {


                                $percent_distr_cfa  = $amount_comission->amount;

                                if (robus()->is_under_distributer) {

                                    $valeur_cfa =   $amount_comission->comission_distributer;
                                } else {

                                    $valeur_cfa = $valeur_amount + $amount_comission->amount;
                                }


                                $commission_reload_inter_val_inter_pay =  $this->convertionservice->convertAdminCommissionInvoice($percent_distr_cfa);

                                $valeur_cfa_receive = $valeur_amount - $percent_distr_cfa;

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

                $commission_phone_credit = PhoneCreditCost::whereId(2)->first();


                $commission_phone_credit_val_inter_pay =  $amount_phone_credit *  $commission_phone_credit->percent;

                $valeur_receive =  $amount_phone_credit -  $commission_phone_credit_val_inter_pay;


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


        $wallet_user = Auth::guard('api')->user();

        $default_valeur = 2000000;

        //Valuer inter  bank
        $commission_inter = DepositCost::whereId(1)->first();


        try {



            $valeur_amount =   $this->convertionservice->convertAdminCommission($amount_reload_card);



            if ($valeur_amount) {

                $commission_reload_inter_val_inter_percent_pay = 0;
                // Convertion valeurs Send maxi


                foreach ($commission_inter->levelDeposits->where('is_inter_bank', true) as $amount_comission) {

                    if ($valeur_amount >= $amount_comission->amount_begin &&  $valeur_amount <= $amount_comission->amount_end) {

                        if ($amount_comission->amount == null) {

                            $percent_distr_cfa =   $amount_comission->percent * $valeur_amount;

                            if (robus()->is_under_distributer) {

                                $valeur_cfa =   $amount_comission->comission_distributer *  $valeur_amount;
                            } else {

                                $valeur_cfa =   $amount_comission->percent * $valeur_amount;
                            }

                            $commission_reload_inter_val_inter_pay =  $this->convertionservice->convertAdminCommissionInvoice($valeur_cfa);

                            // Valeur receive 

                            $receive_valeur = $valeur_amount - $percent_distr_cfa;

                            $commission_reload_inter_val_inter_percent_pay =  $this->convertionservice->convertAdminCommissionInvoice($percent_distr_cfa);


                            if ($receive_valeur < 0) {

                                $commission_reload_inter_val_inter_receive = "Montant negatif";
                            } else {

                                $commission_reload_inter_val_inter_receive = $receive_valeur;
                            }
                        } else {


                            $percent_distr_cfa  = $amount_comission->amount;

                            if (robus()->is_under_distributer) {

                                $valeur_cfa =   $amount_comission->comission_distributer;
                            } else {

                                $valeur_cfa = $amount_comission->amount;
                            }


                            $commission_reload_inter_val_inter_pay =  $this->convertionservice->convertAdminCommissionInvoice($valeur_cfa);

                            $valeur_cfa_receive = $valeur_amount - $percent_distr_cfa;

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
}