<?php

namespace App\Http\Controllers\Api\Transfers;


use Illuminate\Http\Request;
use App\Models\Costs\DepositCost;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\DeletedService;
use App\Models\Wallets\WalletDeposit;
use App\Http\Services\ReloadCardService;
use Illuminate\Support\Facades\Validator;
use App\Repositories\ReloadCardRepository;
use App\Http\Services\NotificationSendService;
use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\ValidateDailyLimitService;
use App\Traits\InitializeAppStatusTrait;
use App\Http\Services\AuthenticationService;

class ReloadCardController extends Controller
{

    use InitializeAppStatusTrait;

    /** @var  ReloadCardRepository */
    private $reloadCardRepository;
    protected $deletedService;
    protected $percentRobus;
    protected $validatedamountDays;
    protected $notificationOrderCard;
    protected $reloadCardService;
    private $authService;

    
    public function __construct(
        ReloadCardRepository $reload_cardReposi,
        DeletedService $deletedService,
        PercentRobusAuthService $percentRobus,
        ValidateDailyLimitService $validatedamountDays,
        NotificationSendService $notificationOrderCard,
        ReloadCardService $reloadCardService,
        AuthenticationService $authService
    ) {
        $this->reloadCardRepository = $reload_cardReposi;
        $this->deletedService = $deletedService;
        $this->percentRobus = $percentRobus;
        $this->authService = $authService;
        $this->validatedamountDays = $validatedamountDays;
        $this->notificationOrderCard = $notificationOrderCard;
        $this->reloadCardService = $reloadCardService;
        $this->initializeAppStatus($this->authService);

    }

    /**
     * @OA\POST(
     *      path="/user/reload_cards/store",
     *      tags={"Reload card User"},
     *      summary="Create new reload card",
     *      description="Faire des rechargements de carte depuis son dashbord
     *         Pour les differents providers dans notre Robus. 
     *        Selon les id des providers nous faisons nos verifications.
     *        Si provider_id =  14 (Ecobank CI)ou 13(UBA CI)  ou 15(BSIC) ou 16(BNI) ou 17(UBA BF)
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
     *          description="success Pour un exemple sur une demande de rechargement de carte UBA CIV",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="provider", type="string", example="Robus"),
     *              @OA\Property(property="number compte", type="string", example="1234567898 (10 chiffres sous le dos de la carte)"),
     *              @OA\Property(property="description", type="string", example="Demande de rechargement de carte"),
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
     *                          property="num_carte",
     *                          type="number"
     *                      ),
     *                    @OA\Property(
     *                          property="amount",
     *                          type="number"
     *                      ),
     *                   @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                  @OA\Property(
     *                          property="description",
     *                          type="string"
     *                      ),
     *                 ),
     * 
     *                example = {
     * 
     *                     "provider":"13",
     *                     "amount":"1000",
     *                     "numero carte" : "1234567898", 
     *                     "name" : "Dupond Falco",
     *                     "description":"Une demande de rechargement de carte",
     * 
     *                }
     *                
     *             )
     *         )
     *      ),
     * 
     * @OA\Response(
     *          response=341,
     *          description="Verifier votre solde."
     *      ),
     * 
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
    public function create_reload_card(Request $request)
    {
        $amount_days = $this->validatedamountDays->getCeilingProperty();
        $valeur_ceiling  =  robus()->silver_ceiling;
        $admin_civ = $this->validatedamountDays->adminCiv();

        try {

            if (robus()->country->name_groupe == 'CIV') {

                $validator = Validator::make(

                    $request->all(),
                    [
                        'num_carte' => 'required',
                        'name' => 'required',
                        'amount' => 'required',
                    ],
                    [

                        'num_carte.required' => 'Votre numero est obligatoire',
                        'amount.required' => 'Votre montant est obligatoire',
                        'name.required' => 'Votre montant est obligatoire',
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



                $percent_robus =  $this->percentRobus->getRobusPercentageReloadOrder();

                $isIvoires = ($request->provider == 14 || $request->provider == 15);
                $propertyToCheck = $isIvoires ? 'is_ivoires' : 'is_inter_bank';

                if (robus()->is_under_distributer) {
                    $valeur_first = $percent_robus->levelDepositIvories->where('is_ivoires', true)->first();
                    $valeur_end = $percent_robus->levelDepositIvories->where('is_ivoires', true)->last();
                    $all_valeurs = $percent_robus->levelDepositIvories->where('is_ivoires', true);
                } else {

                    $valeur_first = $percent_robus->levelDepositIvories->where('is_inter_bank', true)->first();
                    $valeur_end = $percent_robus->levelDepositIvories->where('is_inter_bank', true)->last();
                    $all_valeurs = $percent_robus->levelDepositIvories->where('is_inter_bank', true);
                }

                if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                    if ($percent_robus != null) {

                        if ($valeur_first->amount_begin > $request->amount) {

                            return response()->json(
                                [
                                    'success' => false,
                                    'message' => 'Montant minimun est de .' . $valeur_first->amount_begin,
                                ],
                                341
                            );
                        } elseif ($valeur_end->amount_end < $request->amount) {

                            return response()->json(
                                [
                                    'success' => false,
                                    'message' => 'Montant maximum est de .' . $$valeur_end->amount_end,
                                ],
                                341
                            );
                        } else {
                            foreach ($all_valeurs as $amount_user) {

                                if ($request->amount >= $amount_user->amount_begin && $request->amount <= $amount_user->amount_end) {


                                    if ($percent_robus !== null) {

                                        $amount_send = $request->amount;

                                        if ($amount_user->amount == null) {

                                            $percent_valeur = $amount_user->percent * $request->amount;

                                            if (robus()->is_under_distributer) {
                                                $amount_verify = $request->amount;
                                                $valuer_deposit_com = $amount_user->comission_distributer * $request->amount;
                                                $valuer_admin_com = $percent_valeur - $valuer_deposit_com;
                                                $amount_cost = $amount_user->comission_distributer * $request->amount;
                                                $amountReceved = (float)$request->amount;
                                                $commissionCompanie = $amount_user->percent_companie * $request->amount;
                                            } else {
                                                $amount_verify = $request->amount;
                                                $valuer_admin_com =  $amount_user->percent * $request->amount;
                                                $amount_cost = $percent_valeur;
                                                $valuer_deposit_com = 0;
                                                $amountReceved = (float)$request->amount - (float)$percent_valeur;
                                                $commissionCompanie = $amount_user->percent_companie * $request->amount;

                                            }
                                        } else {

                                            $percent_valeur = (float)$amount_user->amount + (float)$request->amount;

                                            if (robus()->is_under_distributer) {

                                                $amount_verify =  (float)$request->amount;
                                                $valuer_deposit_com = $amount_user->comission_distributer;
                                                $valuer_admin_com = $request->amount - $valuer_deposit_com;
                                                $amount_cost = $amount_user->comission_distributer;
                                                $amountReceved = (float)$request->amount;
                                                $commissionCompanie = $amount_user->percent_companie;
                                            } else {
                                                $amount_verify = (float)$request->amount;
                                                $amount_cost = $amount_user->amount;
                                                $valuer_deposit_com = 0;
                                                $valuer_admin_com = (float)$amount_user->amount;
                                                $amountReceved = (float)$request->amount - (float)$amount_user->amount;
                                                $commissionCompanie = $amount_user->percent_companie;
                                            }
                                        }



                                        if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {


                                            if ($this->validatedamountDays->validateAmountVirement($amount_verify)) {


                                                $walletReloadCard =  $this->reloadCardService->createRobusWalletReloadCard($amount_send, $request->provider, $percent_robus, $amount_cost, $request->num_carte, $request->name, $amountReceved);

                                                if ($walletReloadCard != null) {

                                                    if ($this->reloadCardService->updateamountReloadCardReceveid($admin_civ, $valuer_deposit_com, $walletReloadCard, $valuer_admin_com, $amount_verify, $commissionCompanie)) {
                                                        $this->notificationOrderCard->sendNotificationReloadCard($walletReloadCard);
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
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Les fournisseurs ne sont pas des pays',
                ], 333);
            }
        } catch (\Exception $exception) {

            return response()->json([
                'success' => false,
                'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
            ], 333);
        }
    }


    public function messageAfterValidated($walletReloadCard)
    {

        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletReloadCard->id,
                'provider' => $walletReloadCard->provider->name,
                'amount' => $walletReloadCard->amount,
                // 'phone' => $walletReloadCard->phone,
                // 'num_wallet' => $walletReloadCard->num_wallet,
                'status' => $walletReloadCard->status,
            ],
            'message' => ('Votre demande de transaction a bien été prise en compte.')
        ], 200);
    }

    /**
     * Delete rechagement de carte
     * @OA\Delete (
     *      path="/user/reload_card/delete/{id}",
     *      tags={"Reload card User"},
     *      summary="Delete rechargement de carte",
     *      description="Supprimer les rechargement de carte", 
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
     *                          property="deposit_id",
     *                          type="number"
     *                      ),
     *                 ),
     *                
     *             )
     *         )
     *      ),
     * )
     */

    public function delete_reload_card(Request $request)
    {

        try {

            $wallet_card = WalletDeposit::find($request->deposit_id);

            if ($this->deletedService->deletedTransaction($wallet_card)) {
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


    // Search Valeur receive and valeur pay of Virement
    public function search_reload_card($provider, $amount_virement)
    {

        $wallet_user = Auth::guard('api')->user();

        try {


            if (!in_array($provider, [14, 15, 13, 16, 17, 66])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pas de service disponible',
                ], 200);
            }

            $is_distributor = $wallet_user->is_under_distributer;
            $commission_type = $is_distributor ? 'is_ivoires' : 'is_inter_bank';
            $commission_reload = DepositCost::whereId(2)->first();

            if (!$commission_reload) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune configuration de commission trouvée',
                ], 404);
            }


            $levelDeposits = $commission_reload->levelDepositIvories->where($commission_type, true);
            $valeur_end = $levelDeposits->last();
            $valeur_begin = $levelDeposits->first();

            $conversion_valeur_end = $valeur_end->amount_end ?? 0;
            $conversion_valeur_begin = $valeur_begin->amount_begin ?? 0;

            if ($amount_virement > $conversion_valeur_end) {
                return response()->json([
                    'success' => false,
                    'message' => "Le montant maximum autorisé est de : $conversion_valeur_end",
                ], 200);
            }

            if (empty($amount_virement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez saisir un montant.',
                ], 200);
            }

            $result = $this->calculateCommission($amount_virement, $levelDeposits);

            return response()->json([
                'success' => true,
                'data' => [
                    'amount_pay' => $result['amount_pay'],
                    'amount_send' => $result['amount_send'],
                    'amount_receive' => $result['amount_receive'],
                ],
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' =>  false,
                'message' => 'Pas d\'utilisateur',
            ], 200);
        }
    }

    public function search_reload_card_received($provider, $amount_virement)
    {
        $wallet_user = Auth::guard('api')->user();

        try {


            if (!in_array($provider, [14, 15, 13, 16, 17, 66])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pas de service disponible',
                ], 200);
            }

            $is_distributor = $wallet_user->is_under_distributer;
            $commission_type = $is_distributor ? 'is_ivoires' : 'is_inter_bank';
            $commission_reload = DepositCost::whereId(2)->first();

            if (!$commission_reload) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune configuration de commission trouvée',
                ], 404);
            }


            $levelDeposits = $commission_reload->levelDepositIvories->where($commission_type, true);
            $valeur_end = $levelDeposits->last();
            $valeur_begin = $levelDeposits->first();

            $conversion_valeur_end = $valeur_end->amount_end ?? 0;
            $conversion_valeur_begin = $valeur_begin->amount_begin ?? 0;

            if ($amount_virement > $conversion_valeur_end) {

                return response()->json([
                    'success' => false,
                    'message' => "Le montant maximum autorisé est de : $conversion_valeur_end",
                ], 200);
            }

            if (empty($amount_virement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez saisir un montant valide !!!.',
                ], 200);
            }

            $result = $this->calculateCommissionReceveid($amount_virement, $levelDeposits);

            return response()->json([
                'success' => true,
                'data' => [
                    'amount_pay' => $result['amount_pay'],
                    'amount_send' => $result['amount_send'],
                    'amount_receive' => $result['amount_receive'],
                ],
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' =>  false,
                'message' => 'Pas d\'utilisateur',
            ], 200);
        }
    }

    private function calculateCommission($amount_virement, $levelDeposits)
    {

        foreach ($levelDeposits as $amount_comission) {

            if ($amount_virement >= $amount_comission->amount_begin && $amount_virement <= $amount_comission->amount_end) {

                if ($amount_comission->amount === null) {

                    if (robus()->is_under_distributer) {
                        $commission_reload_val_nat_pay = $amount_comission->comission_distributer * $amount_virement;
                       $receive_valeur =  $amount_virement;

                    } else {
                        $commission_reload_val_nat_pay = $amount_comission->percent * $amount_virement;
                        $receive_valeur =  $amount_virement -  $commission_reload_val_nat_pay;

                    }
                    $commission_transaction_val_nat_pay = $amount_comission->percent * $amount_virement;
                } else {

                    if (robus()->is_under_distributer) {
                        $commission_reload_val_nat_pay = (float) $amount_comission->comission_distributer;
                        $receive_valeur = (float) $amount_virement;
                    } else {

                        $commission_reload_val_nat_pay = (float) $amount_comission->amount;
                        $receive_valeur =  $amount_virement - $commission_reload_val_nat_pay;
                    }
                    $commission_transaction_val_nat_pay = $amount_comission->amount;
                }

                if ($receive_valeur < 0) {
                    $commission_reload_val_nat_receive = "Montant négatif";
                } else {
                    $commission_reload_val_nat_receive = $receive_valeur;
                }

                return [
                    'amount_pay' => $commission_reload_val_nat_pay,
                    'amount_send' => $commission_transaction_val_nat_pay,
                    'amount_receive' => $commission_reload_val_nat_receive,
                ];
            }
        }

        return [
            'amount_pay' => "Montant invalide",
            'amount_receive' => "Montant invalide",
        ];
    }

    private function calculateCommissionReceveid($amount_virement, $levelDeposits)
    {

        foreach ($levelDeposits as $amount_comission) {

            if ($amount_virement >= $amount_comission->amount_begin && $amount_virement <= $amount_comission->amount_end) {

                if ($amount_comission->amount === null) {

                    if (robus()->is_under_distributer) {
                        $commission_reload_val_nat_pay = $amount_comission->comission_distributer * $amount_virement;
                        $receive_valeur =  $amount_virement;
                    } else {
                        $commission_reload_val_nat_pay = $amount_comission->percent * $amount_virement;
                        $receive_valeur =  $amount_virement +  $commission_reload_val_nat_pay;
                    }
                    $commission_transaction_val_nat_pay = $amount_comission->percent * $amount_virement;
                } else {

                    if (robus()->is_under_distributer) {
                        $commission_reload_val_nat_pay = $amount_comission->comission_distributer;
                        $receive_valeur = (float) $amount_virement;
                    } else {

                        $commission_reload_val_nat_pay = (float) $amount_comission->amount;
                        $receive_valeur =  $amount_virement +  $commission_reload_val_nat_pay;
                    }

                    $commission_transaction_val_nat_pay = $amount_comission->amount;
                }

                if ($receive_valeur < 0) {
                    $commission_reload_val_nat_receive = "Montant négatif";
                } else {
                    $commission_reload_val_nat_receive = $receive_valeur;
                }

                return [
                    'amount_pay' => $commission_reload_val_nat_pay,
                    'amount_send' => $commission_transaction_val_nat_pay,
                    'amount_receive' => $commission_reload_val_nat_receive,
                ];
            }
        }

        return [
            'amount_pay' => "Montant invalide",
            'amount_receive' => "Montant invalide",
        ];
    }
}
