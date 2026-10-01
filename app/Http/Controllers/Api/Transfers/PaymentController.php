<?php

namespace App\Http\Controllers\Api\Transfers;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Models\Costs\PaymentCost;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\DeletedService;
use App\Http\Services\PaymentService;
use App\Models\Wallets\WalletPayment;
use App\Repositories\PaymentRepository;
use App\Http\Services\ConvertionService;
use Illuminate\Support\Facades\Validator;
use App\Http\Services\NotificationSendService;
use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\ValidateDailyLimitService;
use App\Traits\InitializeAppStatusTrait;
use App\Http\Services\AuthenticationService;

class PaymentController extends Controller
{

    use InitializeAppStatusTrait;

    /** @var  PaymentRepository */
    protected $paymentRepository;
    protected $deletedService;
    protected $percentRobus;
    protected $validatedamountDays;
    protected $notificationPayment;
    protected $paymentService;
    protected $convertionservice;
    private $authService;

    public function __construct(
        PaymentRepository $paymentReposi,
        DeletedService $deletedService,
        PercentRobusAuthService $percentRobus,
        ValidateDailyLimitService $validatedamountDays,
        NotificationSendService $notificationPayment,
        PaymentService $paymentService,
        ConvertionService $convertionservice,
        AuthenticationService $authService

    ) {
        $this->paymentRepository = $paymentReposi;
        $this->deletedService = $deletedService;
        $this->percentRobus = $percentRobus;
        $this->validatedamountDays = $validatedamountDays;
        $this->notificationPayment = $notificationPayment;
        $this->paymentService = $paymentService;
        $this->authService = $authService;
        $this->convertionservice = $convertionservice;
        $this->initializeAppStatus($this->authService);
    }

    /**
     * @OA\POST(
     *      path="/user/payments/store",
     *      tags={"Paiement  User"},
     *      summary="Create new paiement user",
     *      description="Faire des paiements des factures des 
     *       Selon les id des providers nous faisons nos verifications.
     *        Si provider_id =  8 (CIE) ou 10 (SODECI)  ou 7 (CANAL) 
     *        Si provider = 8 : Le client fais le choix du type de facture :
     *              si facture_choix_type : 1 le client doit saisie son numero de la facture(id_invoice) max 11 ,
     *              le nom de la facture , le montant et faire le choix du type de paiement : 
     *              si  facture_choix_type : 2 le client doit saisie son numero de la facture(id_invoice) max 9
     *        Si le provider = 10 Pour la facture de SODECI , selon le choix du type de paiement , le client doit saisie : 
     *              le numero du contrat (max : 9), le nom de la facture et le montant.
     *        Si le provider = 7 (CANAL +) selon le choix du paiement(paiementChoix_phone) :
     *                  si paiementChoix_phone = 1 (Numero de telephone) , le client doit saisie son numero a 10 chiffres max , selectionnez l'offre et saisie le montant.
     *                  si paiementChoix_phone = 2 (Numero du decodeur) , le client doit saisie son numero du decodeur a 14 chiffres max , selectionnez l'offre et saisie le montant.
     *         Selon ses selection, nous verifions le solde du clients avec nos commissions et le debiter.
     *         ",
     *  @OA\Parameter(
     *         in="path",
     *         name="provider",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     * @OA\Response(
     *          response=200,
     *          description="success Pour un exemple sur Le paiement d'une facture de canal +",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="provider", type="string", example="7"),
     *              @OA\Property(property="description", type="string", example="Paiement de facture de canal"),
     *              @OA\Property(property="amount", type="number", example="1000"),
     *              @OA\Property(property="id_invoice", type="number", example="12345678908"),
     *              @OA\Property(property="paiementChoix_phone", type="number", example="1"),
     *              @OA\Property(property="offer", type="number", example="2(ACESS +)"),
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
     *                          property="paiementChoix_phone",
     *                          type="number"
     *                      ),
     *                    @OA\Property(
     *                          property="phone",
     *                          type="number"
     *                      ),
     *                    @OA\Property(
     *                          property="amount",
     *                          type="number"
     *                      ),
     *                    @OA\Property(
     *                          property="offre",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={
     *
     *                     "provider":"18",
     *                     "paiementChoix_phone":"054963721",
     *                     "Phone":"0549637210",
     *                     "offer":"2",
     *                     "amount":"1000",
     *                }
     *          
     *             )
     *         )
     *      ),
     *   @OA\Response(
     *          response=333,
     *          description="virements does not match."
     *      ),
     * @OA\Response(
     *          response=341,
     *          description="Vérifiez votre solde."
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
    public function create_payment(Request $request)
    {

        $userAuth = auth_user();

        if ($userAuth->is_suspended) {
            return json_message_response(false, "Votre compte est suspendu, contactez le support client !", 333);
        }

        $offers =  Offer::whereId($request->offer)->whereStatus(true)->first();
        $amount_days = $this->validatedamountDays->getCeilingProperty();
        $valeur_ceiling  =  $userAuth->silver_ceiling;
        $admin_civ = $this->validatedamountDays->adminCiv();
        $percent_robus =  $this->percentRobus->getRobusPercentagePayment();

        try {

            if ($userAuth->country->name_groupe == 'CIV') {

                $validationRules = [];
                $validationMessages = [];

                if ($request->provider == 8) {

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
                } elseif ($request->provider == 10) {

                    $validationRules = [
                        'number_contract' => 'required',
                        'name' => 'required',
                        'amount' => 'required',
                    ];

                    $validationMessages = [
                        'number_contract.required' => 'Votre numero est obligatoire',
                        'amount.required' => 'Votre montant est obligatoire',
                        'name.required' => 'Nom est obligatoire',
                    ];
                } elseif ($request->provider == 7) {

                    if ($request->paiementChoix_phone == 1) {

                        $validationRules = [
                            'number_phone' => 'required',
                            'offer' => 'required',
                            'name' => 'required',
                        ];

                        $validationMessages = [
                            'number_phone.required' => 'Votre numero est obligatoire',
                            'offer.required' => 'Offre est obligatoire',
                            'name.required' => 'Nom est obligatoire',

                        ];
                    } else {
                        $validationRules = [
                            'number_decoder' => 'required|max:14',
                            'offer' => 'required',
                            'name' => 'required',
                        ];

                        $validationMessages = [
                            'number_decoder.required' => 'Votre numero est obligatoire',
                            'offer.required' => 'Offre est obligatoire',
                            'name.required' => 'Nom est obligatoire',
                        ];
                    }
                } else {
                    return  json_message_response(false, "Selectionnez un sevice !", 333);
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

                        $reel_amount = $this->validatedamountDays->search_amount($request->amount);


                        if (robus()->is_under_distributer || robus()->is_api) {
                            if ($request->provider != 7) {

                                $amount_send = $reel_amount;
                                $amount_valeur = $request->provider == 8 ?  $reel_amount : $reel_amount;
                                $amountReceved = $reel_amount - (float) ($percent_robus->amount_auther);
                            } else {

                                $amount_send = $offers->amount;
                                $amount_valeur = $offers->amount + $percent_robus->comission_canal;
                                $amountReceved = $offers->amount;
                            }
                        } else {

                            if ($request->provider != 7) {

                                $amount_send = $reel_amount;
                                $amount_valeur = $request->provider == 8 ?  $reel_amount : $reel_amount;
                                $amountReceved = $reel_amount - (float) ($percent_robus->amount_auther);
                            } else {

                                $amount_send = $offers->amount;
                                $amount_valeur = $offers->amount + $percent_robus->amount_canal;
                                $amountReceved = $offers->amount;
                            }
                        }


                        if ($this->validatedamountDays->validateAmount($amount_valeur)) {


                            if ($request->provider != 7) {

                                $walletPayment =  $this->paymentService->createRobusWalletTransactionPayment($amount_send, $request->provider, $percent_robus, $request->number_phone, $offers,  $request->paiementChoix_phone, $request->facture_choix_type, $request->number_decoder, $request->id_invoice, $request->number_contract, $amountReceved ,  $request->name);

                                if ($walletPayment != null) {

                                    if ($this->paymentService->updateAmountPaymentReceveid($admin_civ, $percent_robus, $walletPayment, $amount_valeur)) {
                                        $this->notificationPayment->sendNotificationPayment($walletPayment);
                                        return $this->messageAfterValidated($walletPayment);
                                    } else {
                                        return  json_message_response(false, "Error sur la commission !", 333);
                                    }
                                } else {

                                    return  json_message_response(false, "Error de creation de transfert du montant!", 333);
                                }
                            } else {

                                $walletPayment =  $this->paymentService->createRobusWalletTransactionPaymentOffer($amount_send, $request->provider, $percent_robus, $request->number_phone, $offers,  $request->paiementChoix_phone, $request->facture_choix_type, $request->number_decoder, $request->id_invoice, $request->number_contract, $request->name, $amountReceved);

                                if ($walletPayment != null) {

                                    if ($this->paymentService->updateAmountPaymentOfferReceveid($admin_civ, $percent_robus, $walletPayment, $amount_valeur)) {
                                        $this->notificationPayment->sendNotificationPaymentOffer($walletPayment);
                                        return $this->messageAfterValidated($walletPayment);
                                    } else {
                                        return  json_message_response(false, "Error sur la commission !", 333);
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
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Les fournisseurs ne sont pas des pays ',
                ], 333);
            }
            // Provider CIE
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
            ], 333);
        }
    }

    public function messageAfterValidated($walletPayment)
    {

        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletPayment->id,
                'provider' => $walletPayment->provider->name,
                'amount' => $walletPayment->amount,
                'status' => $walletPayment->status,
            ],
            'message' => ('Votre demande de transaction a bien été prise en compte.')
        ], 200);
    }
    /**
     * Delete Payment invoice
     * @OA\Delete (
     *      path="/user/payment/delete/{id}",
     *      tags={"Paiement  User"},
     *      summary="Delete payment",
     *      description="Supprimer les paiements", 
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
     *                          property="payment_id",
     *                          type="number"
     *                      ),
     *                 ),
     *                
     *             )
     *         )
     *      ),
     * )
     */
    public function delete_payment(Request $request)
    {

        try {


            $wallet_payment = WalletPayment::find($request->payment_id);

            if ($this->deletedService->deletedTransaction($wallet_payment)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ce Paiement a été surppime de la liste.'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de suppression du Paiement.'
                ], 200);
            }
        } catch (\Exception $exception) {
            return  json_message_response(false, "Erreur de suppression !", 300);
        }
    }

    // Search Valeur receive and valeur pay of Virement
    public function search_payment($amount_paiement)
    {

        $wallet_user = robus();

        // valeur amount virement bank

        try {

            // Invoice auther
            if ($amount_paiement != '') {

                $reel_amount = $this->validatedamountDays->search_amount($amount_paiement);

                $commission_invoice = PaymentCost::whereId(2)->first();

                if ($wallet_user->country->code == 'CI') {

                    if ($wallet_user->is_under_distributer == 1 || $wallet_user->is_api == 1) {

                        $commission_paiement_invoice_val_nat_pay =  (float) $commission_invoice->comission_auther;

                        $commission_paiement_invoice_val_nat_receive =  (float) $reel_amount - (float) $commission_invoice->amount_auther;


                        $commission_transaction_val_percent_pay =  (float) $commission_invoice->amount_auther;
                    } else {

                        $commission_paiement_invoice_val_nat_pay =  (float) $commission_invoice->amount_auther;

                        $commission_paiement_invoice_val_nat_receive =  (float) $reel_amount - (float) $commission_invoice->amount_auther;

                        $commission_transaction_val_percent_pay =  (float) $reel_amount;
                    }

                    return response()->json([
                        'success' => $commission_invoice != null ? true : false,
                        'data' =>  [
                            'amount_pay' => $commission_paiement_invoice_val_nat_pay,
                            'amount_send' => $commission_transaction_val_percent_pay,
                            'amount_receive' => $commission_paiement_invoice_val_nat_receive,
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

    public function search_payment_received($amount_paiement)
    {

        $wallet_user = robus();

        // valeur amount virement bank

        try {

            // Invoice auther
            if ($amount_paiement != '') {

                $reel_amount = $this->validatedamountDays->search_amount($amount_paiement);

                $commission_invoice = PaymentCost::whereId(2)->first();

                if ($wallet_user->country->code == 'CI') {

                    if ($wallet_user->is_under_distributer == 1) {

                        $commission_paiement_invoice_val_nat_pay =  (float) $commission_invoice->comission_auther;

                        $commission_paiement_invoice_val_nat_receive =  (float) $reel_amount;

                        $commission_transaction_val_percent_pay =  (float) $commission_invoice->amount_auther;
                    } else {

                        $commission_paiement_invoice_val_nat_pay =  (float) $commission_invoice->amount_auther;

                        $commission_paiement_invoice_val_nat_receive =   (float) $reel_amount + $commission_invoice->amount_auther;

                        $commission_transaction_val_percent_pay =   (float) $commission_invoice->amount_auther;
                    }

                    return response()->json([
                        'success' => $commission_invoice != null ? true : false,
                        'data' =>  [
                            'amount_pay' => $commission_paiement_invoice_val_nat_pay,
                            'amount_send' => $commission_transaction_val_percent_pay,
                            'amount_receive' => $commission_paiement_invoice_val_nat_receive,
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
    // Search amount offers
    public function search_payment_offer($amount_offer)
    {

        $wallet_user = Auth::guard('api')->user();

        $offers = Offer::whereStatus(true)
            ->latest()
            ->orderBy('name', 'asc')
            ->get();

        try {
            if ($amount_offer != '') {


                if (robus()->country->id != 107) {

                    $commission_invoice = PaymentCost::whereId(1)->first();

                    foreach ($offers  as $offer) {

                        $offer_amount = $offer->whereId($amount_offer)->first();

                        $valeur_offer =  $offer_amount->amount + $commission_invoice->amount_canal;

                        $valeur_offer_receveid =  $offer_amount->amount;

                        $convertion_offers = $this->convertionservice->convertAdminCommissionInvoice($valeur_offer);

                        $convertion_offer_receveid = $this->convertionservice->convertAdminCommissionInvoice($valeur_offer_receveid);

                        $commission_reload_inter_val_inter_percent_pay = 0;

                        if (robus()->is_under_distributer || robus()->is_api) {

                            $commission_paiement_invoice_canal_val_inter = $this->convertionservice->convertAdminCommissionInvoice($commission_invoice->amount_canal);

                            $commission_reload_inter_val_inter_percent_pay = $this->convertionservice->convertAdminCommissionInvoice($commission_invoice->comission_canal);
                        } else {

                            $commission_paiement_invoice_canal_val_inter = $this->convertionservice->convertAdminCommissionInvoice($commission_invoice->amount_canal);

                            $commission_reload_inter_val_inter_percent_pay = $commission_paiement_invoice_canal_val_inter;
                        }


                        $commission_paiement_invoice_canal_val_inter = $convertion_offers;
                    }
                    return response()->json([
                        'success' => $commission_invoice != null ? true : false,
                        'data' =>  [
                            'amount_commission' => $commission_reload_inter_val_inter_percent_pay,
                            'amount_pay' => $commission_paiement_invoice_canal_val_inter,
                            'amount_receive' => $convertion_offer_receveid,
                            'amount_conversion' => $convertion_offers,
                        ],
                    ], 200);
                } else {

                    $commission_invoice = PaymentCost::whereId(2)->first();

                    $commission_reload_inter_val_inter_percent_pay = 0;

                    foreach ($offers  as $offer) {

                        $offer_amount = $offer->whereId($amount_offer)->first();

                        if (robus()->is_under_distributer || robus()->is_api) {

                            $valeur_offer =  $offer_amount->amount + $commission_invoice->amount_canal;


                            $commission_paiement_invoice_canal_val_inter = $commission_invoice->comission_canal;
                        } else {

                            $valeur_offer =  $offer_amount->amount + $commission_invoice->amount_canal;

                            $commission_paiement_invoice_canal_val_inter =  $valeur_offer;
                        }

                        $commission_reload_inter_val_inter_percent_pay =  $commission_invoice->amount_canal;
                    }

                    return response()->json([
                        'success' => $commission_invoice != null ? true : false,
                        'data' =>  [
                            'amount_commission' => $commission_reload_inter_val_inter_percent_pay,
                            'amount_pay' => $commission_paiement_invoice_canal_val_inter,
                            'amount_receive' => $valeur_offer,
                            'amount_conversion' => $commission_paiement_invoice_canal_val_inter,
                        ],
                    ], 200);
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' =>  false,
                'message' => 'Pas de providers',
            ], 200);
        }
    }
}
