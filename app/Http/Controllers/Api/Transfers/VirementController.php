<?php

namespace App\Http\Controllers\Api\Transfers;


use Illuminate\Http\Request;
use App\Models\Wallets\WalletBank;
use App\Http\Controllers\Controller;
use App\Http\Services\DeletedService;
use App\Http\Services\VirementService;
use App\Repositories\VirementRepository;
use Illuminate\Support\Facades\Validator;
use App\Http\Services\NotificationSendService;
use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\ValidateDailyLimitService;
use App\Traits\InitializeAppStatusTrait;
use App\Http\Services\AuthenticationService;

class VirementController extends Controller
{

    use InitializeAppStatusTrait;

    /** @var  VirementRepository */
    protected $virementRepository;
    protected $virementServices;
    protected $deletedService;
    protected $percentRobus;
    protected $validatedamountDays;
    protected $notificationVirement;
    private $authService;

    public function __construct(
        VirementRepository $virementReposi,
        VirementService $virementServices,
        DeletedService $deletedService,
        PercentRobusAuthService $percentRobus,
        ValidateDailyLimitService $validatedamountDays,
        NotificationSendService $notificationVirement,
        AuthenticationService $authService
    ) {
        $this->virementRepository = $virementReposi;
        $this->virementServices = $virementServices;
        $this->deletedService = $deletedService;
        $this->percentRobus = $percentRobus;
        $this->validatedamountDays = $validatedamountDays;
        $this->notificationVirement = $notificationVirement;
        $this->authService = $authService;
        $this->initializeAppStatus($this->authService);
    }

    /**
     * @OA\POST(
     *      path="/user/virements/store",
     *      tags={"Virements User"},
     *      summary="Create new virement Bank",
     *      description="Faire des virements bancaire  depuis son dashbord
     *         Pour les differents providers dans notre Robus.          
     *        Selon les id des providers nous faisons nos verifications.
     *        si  Provider_id = 11 (ECOBANK) Le client saisie le numero du compte et les infos du formulaires
     *        Si provider_id =  12 (NSIA)  Le client saisie le numero du compte et les infos du formulaires
     *        le clients doit avoir le moment necessaire avec la commissions pour faire des virements dans le cas contraire un warring.    ",
     *  @OA\Parameter(
     *         in="path",
     *         name="provider",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     * @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="provider", type="string", example="NSIA Banque"),
     *              @OA\Property(property="phone", type="number", example="2585858595"),
     *              @OA\Property(property="firstname", type="string", example="Dupom"),
     *              @OA\Property(property="lastname", type="string", example="Falcodo"),
     *              @OA\Property(property="number compte", type="string", example="1234567894"),
     *              @OA\Property(property="amount", type="number", example="1000"),
     *              @OA\Property(property="commissions", type="float", example="0.01"),
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
     *                          property="phone",
     *                          type="string"
     *                      ),
     *                  @OA\Property(
     *                          property="firstname",
     *                          type="string"
     *                      ),
     *                    @OA\Property(
     *                          property="lastname",
     *                          type="string"
     *                      ),
     *                    @OA\Property(
     *                          property="amount",
     *                          type="number"
     *                      ),
     *                     @OA\Property(
     *                          property="number_compte",
     *                          type="number"
     *                      ),
     *                     @OA\Property(
     *                          property="Commissions",
     *                          type="number"
     *                      ),
     *                 ),
     *
     *                example={
     *
     *                     "provider":"11",
     *                     "amount":"1000",
     *                     "firstname":"sabdy",
     *                     "lastname":"Dev",
     *                     "phone":"0546963728",
     *                     "number_compte":"12345657897",
     * 
     *                }
     *                
     *             )
     *         )
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
    public function create_virement(Request $request)
    {
        $userAuth = auth_user();
        $amount_days = $this->validatedamountDays->getCeilingProperty();
        $valeur_ceiling  =  $userAuth->silver_ceiling;
        $admin_civ = $this->validatedamountDays->adminCiv();

        try {



            if ($userAuth->country->name_groupe == 'CIV') {

                if (in_array($request->provider, [12, 11])) {

                    $validator = Validator::make(

                        $request->all(),
                        [
                            'firstname' => 'required',
                            'lastname' => 'required',
                            'phone' => 'required',
                            'number_compte' => 'required',
                            'amount' => 'required',
                        ],
                        [

                            'firstname.required' => 'Nom est obligatoire',
                            'amount.required' => 'Votre montant est obligatoire',
                            'lastname.required' => 'Prenom est obligatoire',
                            'number_compte.required' => 'Numero de compte est obligatoire',
                            'phone.required' => 'Telephone est obligatoire',

                        ]
                    );

                    if ($validator->fails()) {
                        return response()->json(
                            [
                                'success' => false,
                                'message' => 'Le compte et le montant sont requis ou le numéro à 10 chiffres',
                                'data' => $validator->errors()
                            ],
                            333
                        );
                    }

                    $percent_robus =  $this->percentRobus->getRobusPercentageVirement();

                    $reel_amount = $this->validatedamountDays->search_amount($request->amount);

                    if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                        if ($percent_robus != null) {

                            foreach ($percent_robus->levelBankIvories as $amount_user) {

                                if ($reel_amount >= $amount_user->amount_begin && $reel_amount <= $amount_user->amount_end) {


                                    if ($percent_robus !== null) {

                                        if ($userAuth->is_under_distributer || $userAuth->is_api) {

                                            $amount_valeur = $reel_amount;

                                            $amountReceved =  (float) $reel_amount;
                                        } else {

                                            $amount_valeur = (float)$amount_user->amount + (float)$reel_amount;

                                            $amountReceved =  (float) $reel_amount -  (float) $amount_user->amount;
                                        }

                                        $comission_distributer = $amount_user->amount;

                                        $amount_send = $reel_amount;



                                        if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {


                                            if ($this->validatedamountDays->validateAmountVirement($amount_valeur)) {

                                                //valeur commissions

                                                $walletVirement =  $this->virementServices->createRobusVirement($amount_valeur, $amount_send, $request->provider, $request->description, $percent_robus, $request->number_compte, $request->firstname, $request->lastname, $comission_distributer, $request->phone, $amountReceved);

                                                if ($walletVirement != null) {

                                                    if ($this->virementServices->updateamountVirementReceveid($admin_civ, $amount_user, $walletVirement, $amount_valeur)) {
                                                        $this->notificationVirement->sendNotificationVirement($walletVirement);
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
                        } else {

                            return json_message_response(false, "Erreur de pourcentage !", 333);
                        }
                    } else {
                        return   json_message_response(false, "Votre montant journalier est atteint !", 333);
                    }
                } else {

                    return response()->json([
                        'success' => false,
                        'message' => 'bank transfer creation error.',
                    ], 333);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Les fournisseurs ne sont pas des pays ',
                ], 333);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
            ], 333);
        }
    }

    public function messageAfterValidated($walletVirement)
    {

        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletVirement->id,
                'provider' => $walletVirement->provider->name,
                'amount' => $walletVirement->amount,
                // 'phone' => $walletVirement->phone,
                // 'num_wallet' => $walletVirement->num_wallet,
                'status' => $walletVirement->status,
            ],
            'message' => ('Votre demande de transaction a bien été prise en compte.')
        ], 200);
    }

    // Search Valeur receive and valeur pay of Virement in Inder_distributer
    public function search_virement($amount_virement)
    {

        try {

            $userAuth = auth_user();
            $percent_robus =  $this->percentRobus->getRobusPercentageVirement();

            if ($percent_robus != null) {

                if ($amount_virement != '') {

                    $reel_amount = $this->validatedamountDays->search_amount($amount_virement);

                    $commission_virement_val_nat_pay = 0;
                    $commission_virement_val_nat_receive = 0;
                    $commission_virement_val_nat_send = 0;
                    // Verify valeur amount max in Landing the ROBUS
                    $valeur_end = $percent_robus->levelBankIvories->last();

                    //  Conversion valeur
                    $conversion_valeur_end  = $valeur_end->amount_end;

                    if ($conversion_valeur_end < $reel_amount) {

                        $commission_virement_val_nat_pay = "Montant maxi est  : " .  $conversion_valeur_end;

                        $commission_virement_val_nat_receive = "Montant maxi est  : " .  $conversion_valeur_end;
                    } else {

                        foreach ($percent_robus->levelBankIvories as $amount_comission) {

                            if ($reel_amount >= $amount_comission->amount_begin && $reel_amount <= $amount_comission->amount_end) {


                                $commission_virement_val_nat_pay = $amount_comission->comission_distributer;

                                $commission_virement_val_nat_send = $amount_comission->amount;

                                if ($userAuth->is_under_distributer == 1 ||  $userAuth->is_distributer == 1 || $userAuth->is_api == 1) {

                                    $valeur_receive =  $reel_amount;
                                } else {

                                    $valeur_receive =  $reel_amount -  $commission_virement_val_nat_send;
                                }


                                if ($valeur_receive < 0) {

                                    $commission_virement_val_nat_receive = "Montant negatif";
                                } else {

                                    $commission_virement_val_nat_receive =  $valeur_receive;
                                }
                            }
                        }
                    }

                    return response()->json([
                        'success' => $percent_robus != null ? true : false,
                        'data' =>  [
                            'amount_pay' => $userAuth->is_under_distributer || $userAuth->is_api ? $commission_virement_val_nat_pay : $commission_virement_val_nat_send,
                            'amount_send' => $commission_virement_val_nat_pay,
                            'amount_receive' => $commission_virement_val_nat_receive,
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

    public function search_virement_received($amount_virement)
    {
        try {

            $userAuth = auth_user();
            $percent_robus =  $this->percentRobus->getRobusPercentageVirement();

            if ($percent_robus != null) {

                if ($amount_virement != '') {

                    $reel_amount = $this->validatedamountDays->search_amount($amount_virement);

                    $commission_virement_val_nat_pay = 0;
                    $commission_virement_val_nat_receive = 0;
                    $commission_virement_val_nat_send = 0;
                    // Verify valeur amount max in Landing the ROBUS
                    $valeur_end = $percent_robus->levelBankIvories->last();

                    //  Conversion valeur
                    $conversion_valeur_end  = $valeur_end->amount_end;

                    if ($conversion_valeur_end < $reel_amount) {

                        $commission_virement_val_nat_pay = "Montant maxi est  : " .  $conversion_valeur_end;

                        $commission_virement_val_nat_receive = "Montant maxi est  : " .  $conversion_valeur_end;
                    } else {

                        foreach ($percent_robus->levelBankIvories as $amount_comission) {

                            if ($reel_amount >= $amount_comission->amount_begin && $reel_amount <= $amount_comission->amount_end) {


                                $commission_virement_val_nat_pay = $amount_comission->comission_distributer;

                                $commission_virement_val_nat_send = $amount_comission->amount;

                                if ($userAuth->is_under_distributer == 1 ||  $userAuth->is_distributer == 1 || $userAuth->is_api == 1) {

                                    $valeur_receive =  $reel_amount;
                                } else {

                                    $valeur_receive =  $reel_amount - $commission_virement_val_nat_send;
                                }


                                if ($valeur_receive < 0) {

                                    $commission_virement_val_nat_receive = "Montant negatif";
                                } else {

                                    $commission_virement_val_nat_receive =  $valeur_receive;
                                }
                            }
                        }
                    }

                    return response()->json([
                        'success' => $percent_robus != null ? true : false,
                        'data' =>  [
                            'amount_pay' => $userAuth->is_under_distributer || $userAuth->is_api ? $commission_virement_val_nat_pay : $commission_virement_val_nat_send,
                            'amount_send' => $commission_virement_val_nat_pay,
                            'amount_receive' => $commission_virement_val_nat_receive,
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


    /**
     * Delete virement
     * @OA\Delete (
     *      path="/user/virements/delete/{id}",
     *      tags={"Virements User"},
     *      summary="Delete virement",
     *      description="Supprimer les Virements", 
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
     *                          property="virement_id",
     *                          type="number"
     *                      ),
     *                 ),
     *                
     *             )
     *         )
     *      ),
     * )
     */
    public function delete_virement(Request $request)
    {

        try {


            $wallet_virement = WalletBank::find($request->virement_id);

            if ($this->deletedService->deletedTransaction($wallet_virement)) {
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
}
