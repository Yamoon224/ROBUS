<?php

namespace App\Http\Controllers\Api\Transfers;


use Illuminate\Http\Request;
use App\Models\Costs\DepositCost;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\DeletedService;
use App\Models\Wallets\WalletDeposit;
use App\Http\Services\ReloadCardService;
use App\Http\Services\MasterService;
use Illuminate\Support\Facades\Validator;
use App\Repositories\ReloadCardRepository;
use App\Http\Services\NotificationSendService;
use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\ValidateDailyLimitService;

class ReloadCardController extends Controller
{

    /** @var  ReloadCardRepository */
    private $reloadCardRepository;
    protected $deletedService;
    protected $percentRobus;
    protected $validatedamountDays;
    protected $notificationOrderCard;
    protected $reloadCardService;
    private $robusMasterTransaction;

    public function __construct(
        ReloadCardRepository $reload_cardReposi,
        DeletedService $deletedService,
        PercentRobusAuthService $percentRobus,
        ValidateDailyLimitService $validatedamountDays,
        NotificationSendService $notificationOrderCard,
        ReloadCardService $reloadCardService,
        MasterService $robusMasterTransaction

    ) {
        $this->reloadCardRepository = $reload_cardReposi;
        $this->deletedService = $deletedService;
        $this->percentRobus = $percentRobus;
        $this->validatedamountDays = $validatedamountDays;
        $this->notificationOrderCard = $notificationOrderCard;
        $this->reloadCardService = $reloadCardService;
        $this->robusMasterTransaction = $robusMasterTransaction;
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

                            $master_id = $this->robusMasterTransaction->mastersId();

                            foreach ($all_valeurs as $amount_user) {

                                if ($request->amount >= $amount_user->amount_begin && $request->amount <= $amount_user->amount_end) {


                                    if ($percent_robus !== null) {

                                        $amount_send = $request->amount;

                                        if ($amount_user->amount == null) {

                                            $percent_valeur = $amount_user->percent * $request->amount;

                                            if (robus()->is_under_distributer) {
                                                $amount_verify = $request->amount;
                                                $valuer_under_com = $amount_user->comission_distributer * $request->amount;
                                                $valuer_master_com =  $master_id ?  $amount_user->percent_masters * $valuer_under_com : 0;
                                                $valuer_deposit_com = (float) $valuer_under_com - (float)$valuer_master_com;

                                                $valuer_admin_com = (float) $percent_valeur -  (float) $valuer_under_com;
                                                $amount_cost = $amount_user->comission_distributer * $request->amount;
                                                $amountReceved = (float)$request->amount;
                                            } else {
                                                $amount_verify = $request->amount;
                                                $amount_admin_com =  $amount_user->percent * $request->amount;
                                                $valuer_master_com =  $master_id ?  $amount_user->percent_masters * $amount_admin_com : 0;
                                                $valuer_admin_com = (float)$amount_admin_com - (float) $valuer_master_com ;
                                                $amount_cost = $amount_user->percent * $percent_valeur;
                                                $valuer_deposit_com = 0;
                                                $amountReceved = (float)$request->amount - (float)$percent_valeur;
                                            }
                                        } else {

                                            $percent_valeur = (float)$amount_user->amount + (float)$request->amount;

                                            if (robus()->is_under_distributer) {

                                                $amount_verify =  (float)$request->amount;
                                                $valuer_under_com = $amount_user->comission_distributer ;
                                                $valuer_master_com =  $master_id ?  $amount_user->percent_masters : 0;
                                                $valuer_deposit_com = (float)  $amount_user->comission_distributer - (float) $valuer_master_com;
                                                $valuer_admin_com = $amount_user->amount - $valuer_under_com;
                                                $amount_cost = $amount_user->comission_distributer;
                                                $amountReceved = (float)$request->amount;
                                            } else {
                                                $amount_verify = (float)$request->amount;
                                                $amount_cost = $amount_user->amount;
                                                $valuer_master_com =  $master_id ?  $amount_user->percent_masters : 0;
                                                $valuer_deposit_com = 0;
                                                $valuer_admin_com = (float)$amount_user->amount - (float) $valuer_master_com;
                                                $amountReceved = (float)$request->amount - (float)$amount_user->amount;
                                            }
                                        }



                                        if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {


                                            if ($this->validatedamountDays->validateAmountVirement($amount_verify)) {


                                                $walletReloadCard =  $this->reloadCardService->createRobusWalletReloadCard($amount_send, $request->provider, $percent_robus, $amount_cost, $request->num_carte, $request->name, $amountReceved);

                                                if ($walletReloadCard != null) {

                                                    if ($this->reloadCardService->updateamountReloadCardReceveid($admin_civ, $valuer_deposit_com, $walletReloadCard, $valuer_admin_com, $amount_verify , $valuer_master_com)) {
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

        // valeur amount virement bank


        try {


            if ($provider == 14 || $provider == 15) {

                if ($wallet_user->is_under_distributer == 1) {

                    $commission_reload = DepositCost::whereId(2)->first();

                    // Verify valeur amount max in Landing the ROBUS
                    $valeur_end = $commission_reload->levelDepositIvories->where('is_ivoires', true)->last();

                    $valeur_begin = $commission_reload->levelDepositIvories->where('is_ivoires', false)->first();

                    $conversion_valeur_begin  = $valeur_begin->amount_begin;

                    //  Conversion valeur
                    $conversion_valeur_end  = $valeur_end->amount_end;


                    if ($conversion_valeur_end  < $amount_virement) {

                        $commission_reload_val_nat_pay = "Montant maxi est  : " .  $conversion_valeur_end;

                        $commission_reload_val_nat_receive = "Montant maxi est  : " .  $conversion_valeur_end;
                    } else {

                        foreach ($commission_reload->levelDepositIvories->where('is_ivoires', true)  as $amount_comission) {

                            if ($amount_virement >= $amount_comission->amount_begin &&  $amount_virement <= $amount_comission->amount_end) {

                                if ($amount_comission->amount == null) {

                                    $percent_admin =  $amount_comission->percent * $amount_virement;

                                    // Commission valuer ROBUS
                                    $commission_reload_val_nat_pay = $amount_comission->comission_distributer * $amount_virement;

                                    // Valeur receive 

                                    $receive_valeur = $amount_virement;

                                    if ($receive_valeur < 0) {

                                        $commission_reload_val_nat_receive = "Montant negatif";
                                    } else {

                                        $commission_reload_val_nat_receive = $receive_valeur;
                                    }
                                } else {

                                    $commission_reload_val_nat_pay = (float) $amount_comission->comission_distributer;

                                    $valeur_receive = (float) $amount_virement;

                                    if ($valeur_receive < 0) {

                                        $commission_reload_val_nat_receive = "Montant negatif";
                                    } else {

                                        $commission_reload_val_nat_receive = (float) $amount_virement;
                                    }
                                }
                            }
                        }
                    }
                } else {

                    $commission_reload = DepositCost::whereId(2)->first();

                    // Verify valeur amount max in Landing the ROBUS
                    $valeur_end = $commission_reload->levelDepositIvories->where('is_inter_bank', true)->last();


                    $valeur_begin = $commission_reload->levelDepositIvories->where('is_inter_bank', false)->first();

                    $conversion_valeur_begin  = $valeur_begin->amount_begin;
                    //  Conversion valeur
                    $conversion_valeur_end  = $valeur_end->amount_end;


                    if ($conversion_valeur_end  < $amount_virement) {

                        $commission_reload_val_nat_pay = "Montant maxi est  : " .  $conversion_valeur_end;

                        $commission_reload_val_nat_receive = "Montant maxi est  : " .  $conversion_valeur_end;
                    } else {

                        foreach ($commission_reload->levelDepositIvories->where('is_inter_bank', true)  as $amount_comission) {

                            if ($amount_virement >= $amount_comission->amount_begin &&  $amount_virement <= $amount_comission->amount_end) {

                                if ($amount_comission->amount == null) {

                                    // Commission valuer ROBUS
                                    $commission_reload_val_nat_pay = $amount_comission->percent * $amount_virement;

                                    // Valeur receive 

                                    $receive_valeur = $amount_virement;

                                    if ($receive_valeur < 0) {

                                        $commission_reload_val_nat_receive = "Montant negatif";
                                    } else {

                                        $commission_reload_val_nat_receive = number_format($receive_valeur, 2, ',', ' ');
                                    }
                                } else {

                                    $commission_reload_val_nat_pay = (float) $amount_comission->amount;

                                    $valeur_receive = (float) $amount_virement;

                                    if ($valeur_receive < 0) {

                                        $commission_reload_val_nat_receive = "Montant negatif";
                                    } else {

                                        $commission_reload_val_nat_receive = (float) $amount_virement;
                                    }
                                }
                            }
                        }
                    }
                }

                return response()->json([
                    'success' => $commission_reload != null ? true : false,
                    'data' =>  [
                        'amount_pay' => $commission_reload_val_nat_pay,
                        'amount_receive' => $commission_reload_val_nat_receive,
                    ],
                ], 200);
            } elseif ($provider == 13 || $provider == 16 || $provider == 17 || $provider == 66) {


                if ($amount_virement != '') {

                    if (robus()->is_under_distributer) {

                        $commission_reload = DepositCost::whereId(2)->first();

                        // Verify valeur amount max in Landing the ROBUS
                        $valeur_end = $commission_reload->levelDepositIvories->where('is_ivoires', true)->last();


                        $valeur_begin = $commission_reload->levelDepositIvories->where('is_ivoires', false)->first();

                        //  Conversion valeur
                        $conversion_valeur_end  = $valeur_end->amount_end;
                        $conversion_valeur_begin  = $valeur_begin->amount_begin;

                        //  Conversion valeur


                        if ($conversion_valeur_end  < $amount_virement) {

                            $commission_reload_val_nat_pay = "Montant maxi est  : " .  $conversion_valeur_end;

                            $commission_reload_val_nat_receive = "Montant maxi est  : " .  $conversion_valeur_end;
                        } else {

                            foreach ($commission_reload->levelDepositIvories->where('is_ivoires', true)  as $amount_comission) {

                                if ($amount_virement >= $amount_comission->amount_begin &&  $amount_virement <= $amount_comission->amount_end) {

                                    if ($amount_comission->amount == null) {

                                        $percent_admin =  $amount_comission->percent * $amount_virement;

                                        // Commission valuer ROBUS
                                        $commission_reload_val_nat_pay = $amount_comission->comission_distributer * $amount_virement;

                                        // Valeur receive 

                                        $receive_valeur = $amount_virement;

                                        if ($receive_valeur < 0) {

                                            $commission_reload_val_nat_receive = "Montant negatif";
                                        } else {

                                            $commission_reload_val_nat_receive = $receive_valeur;
                                        }
                                    } else {

                                        $commission_reload_val_nat_pay = (float) $amount_comission->comission_distributer;

                                        $valeur_receive = (float) $amount_virement;

                                        if ($valeur_receive < 0) {

                                            $commission_reload_val_nat_receive = "Montant negatif";
                                        } else {

                                            $commission_reload_val_nat_receive = (float) $amount_virement;
                                        }
                                    }
                                }
                            }
                        }
                    } else {

                        $commission_reload = DepositCost::whereId(2)->first();


                        // Verify valeur amount max in Landing the ROBUS
                        $valeur_end = $commission_reload->levelDepositIvories->where('is_inter_bank', true)->last();

                        $valeur_begin = $commission_reload->levelDepositIvories->where('is_inter_bank', true)->first();

                        //  Conversion valeur
                        $conversion_valeur_end  = $valeur_end->amount_end;
                        $conversion_valeur_begin  = $valeur_begin->amount_begin;


                        if ($conversion_valeur_end  < $amount_virement) {

                            $commission_reload_val_nat_pay = "Montant maxi est  : " .  $conversion_valeur_end;

                            $commission_reload_val_nat_receive = "Montant maxi est  : " .  $conversion_valeur_end;
                        } else {

                            foreach ($commission_reload->levelDepositIvories->where('is_inter_bank', true)  as $amount_comission) {

                                if ($amount_virement >= $amount_comission->amount_begin &&  $amount_virement <= $amount_comission->amount_end) {

                                    if ($amount_comission->amount == null) {

                                        // Commission valuer ROBUS
                                        $commission_reload_val_nat_pay = $amount_comission->percent * $amount_virement;

                                        // Valeur receive 

                                        $receive_valeur = $amount_virement;

                                        if ($receive_valeur < 0) {

                                            $commission_reload_val_nat_receive = "Montant negatif";
                                        } else {

                                            $commission_reload_val_nat_receive = number_format($receive_valeur, 2, ',', ' ');
                                        }
                                    } else {

                                        $commission_reload_val_nat_pay = (float) $amount_comission->amount;

                                        $valeur_receive = (float) $amount_virement;

                                        if ($valeur_receive < 0) {

                                            $commission_reload_val_nat_receive = "Montant negatif";
                                        } else {

                                            $commission_reload_val_nat_receive = (float) $amount_virement;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    return response()->json([
                        'success' => $commission_reload != null ? true : false,
                        'data' =>  [
                            'amount_pay' => $commission_reload_val_nat_pay,
                            'amount_receive' => $commission_reload_val_nat_receive,
                        ],
                    ], 200);
                } else {
                    return response()->json([
                        'success' =>  false,
                        'message' => 'Saisie le montant SVP',
                    ], 200);
                }
            } else {
                return response()->json([
                    'success' =>  false,
                    'message' => 'Pas de service disponible',
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
