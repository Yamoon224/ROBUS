<?php

namespace App\Http\Controllers\Api\Transfers;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Models\Costs\PaymentCost;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\DeletedService;
use App\Http\Services\VersementService;
use App\Models\Wallets\WalletVersement;
use App\Repositories\VersementRepository;
use App\Http\Services\ConvertionService;
use Illuminate\Support\Facades\Validator;
use App\Http\Services\NotificationSendService;
use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\ValidateDailyLimitService;
use App\Traits\InitializeAppStatusTrait;
use App\Http\Services\AuthenticationService;

class VersementController extends Controller
{

    use InitializeAppStatusTrait;

    /** @var  VersementRepository */
    protected $versementRepository;
    protected $deletedService;
    protected $percentRobus;
    protected $validatedamountDays;
    protected $notificationPayment;
    protected $paymentService;
    protected $convertionservice;
    private $authService;

    public function __construct(
        VersementRepository $paymentReposi,
        DeletedService $deletedService,
        PercentRobusAuthService $percentRobus,
        ValidateDailyLimitService $validatedamountDays,
        NotificationSendService $notificationPayment,
        VersementService $paymentService,
        ConvertionService $convertionservice,
        AuthenticationService $authService

    ) {
        $this->versementRepository = $paymentReposi;
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
     *      path="/user/versements/store",
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
    public function create_versement(Request $request)
    {

        $amount_days = $this->validatedamountDays->getCeilingProperty();
        $valeur_ceiling  =  robus()->silver_ceiling;
        $admin_civ = $this->validatedamountDays->adminCiv();

        try {

            if (robus()->country->name_groupe == 'CIV') {

                $validationRules = [];
                $validationMessages = [];

                $validationRules = [
                    'num_accompte' => 'required',
                    'provider' => 'required',
                    'bank_code' => 'required',
                    'guichet_code' => 'required',
                    'num_authers' => 'required',
                    'amount' => 'required',
                ];

                $validationMessages = [
                    'num_accompte.required' => 'Votre numero est obligatoire',
                    'amount.required' => 'Votre montant est obligatoire',
                    'bank_code.required' => 'Code banque est obligatoire',
                    'guichet_code.required' => 'Code du guichet est obligatoire',
                    'num_authers.required' => 'Numero est obligatoire',
                ];

                $validator = Validator::make($request->all(), $validationRules, $validationMessages);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'QUelque champs obligatoire',
                        'data' => $validator->errors()
                    ], 333);
                }

                $percent_robus =  $this->percentRobus->getRobusPercentageVersement($request->provider);

                if ($percent_robus != null) {

                    if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                        $reel_amount = $this->validatedamountDays->search_amount($request->amount);

                        $percent_amount = $percent_robus->percent * $reel_amount;

                        $valeur_received = $request->amount -  $percent_amount;

                        $amount_valeur = $percent_amount + (float)  $request->amount;

                        if ($this->validatedamountDays->validateAmount($amount_valeur)) {

                            $walletPayment =  $this->paymentService->createRobusWalletTransactionPayment($reel_amount, $valeur_received, $amount_valeur, $request->provider, $percent_robus, $request->num_accompte, $request->bank_code,  $request->guichet_code, $request->num_authers, $request->name);

                            if ($walletPayment != null) {

                                if ($this->paymentService->updateAmountPaymentReceveid($admin_civ, $percent_robus, $walletPayment, $amount_valeur)) {
                                    $this->notificationPayment->sendNotificationVersement($walletPayment);
                                    return $this->messageAfterValidated($walletPayment);
                                } else {
                                    return  json_message_response(false, "Error sur la commission !", 333);
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
     * 
     * @OA\Delete(
     *      path="/user/versements/delete/{id}",
     *      tags={"Paiement User"},
     *      summary="Delete payment",
     *      description="Supprimer les paiements",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID du versement à supprimer",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Versement supprimé avec succès",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="delete virement success")
     *          )
     *      ),
     *      @OA\Response(response=404, description="Versement non trouvé"),
     *      @OA\Response(response=401, description="Non authentifié")
     * )
     */
    public function delete_payment(Request $request)
    {

        try {


            $wallet_payment = WalletVersement::find($request->payment_id);

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
    public function search_versement($amount_paiement, $providerId)
    {

        $wallet_user = robus();

        // valeur amount virement bank

        try {

            // Invoice auther
            if ($amount_paiement != '') {

                $reel_amount = $this->validatedamountDays->search_amount($amount_paiement);

                $commission_invoice =  $this->percentRobus->getRobusPercentageVersement($providerId);

                if ($wallet_user->country->code == 'CI') {

                    if ($wallet_user->is_under_distributer == 1 || $wallet_user->is_api) {

                        $commission_paiement =  $commission_invoice->percent  * $amount_paiement;

                        $commission_paiement_invoice_val_nat_pay =  $commission_invoice->percent_merchands  * $amount_paiement;

                        $commission_paiement_invoice_val_nat_receive =  (float) $reel_amount - (float) $commission_paiement;

                        $commission_transaction_val_percent_pay =  (float) $commission_paiement;
                    } else {

                        $commission_custumer_paiement =  $commission_invoice->percent_custumer  * $amount_paiement;

                        $commission_paiement_invoice_val_nat_pay =   $commission_custumer_paiement;

                        $commission_paiement_invoice_val_nat_receive =  (float) $reel_amount - (float) $commission_custumer_paiement;

                        $commission_transaction_val_percent_pay =  (float) $commission_custumer_paiement;
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

    public function search_versement_received($amount_paiement, $providerId)
    {

        $wallet_user = robus();

        // valeur amount virement bank

        try {

            // Invoice auther
            if ($amount_paiement != '') {

                $reel_amount = $this->validatedamountDays->search_amount($amount_paiement);

                $commission_invoice =  $this->percentRobus->getRobusPercentageVersement($providerId);
                $commission_paiement =  $commission_invoice->percent  * $amount_paiement;

                if ($wallet_user->country->code == 'CI') {

                    if ($wallet_user->is_under_distributer == 1) {

                        $commission_paiement_invoice_val_nat_pay =  $commission_invoice->percent_merchands  * $amount_paiement;

                        $commission_paiement_invoice_val_nat_receive =  (float) $reel_amount + (float) $commission_paiement;

                        $commission_transaction_val_percent_pay =  (float) $commission_paiement;
                    } else {

                        $commission_paiement_invoice_val_nat_pay =   $commission_paiement;

                        $commission_paiement_invoice_val_nat_receive =  (float) $reel_amount + (float) $commission_paiement;

                        $commission_transaction_val_percent_pay =  (float) $commission_paiement;
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
}
