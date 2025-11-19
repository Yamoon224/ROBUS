<?php

namespace App\Http\Controllers\Api\Transfers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\DeletedService;
use App\Models\Costs\PhoneCreditCost;
use App\Http\Services\TresortService;
use App\Models\Wallets\walletPush;
use Illuminate\Support\Facades\Validator;
use App\Repositories\TresortRepository;
use App\Http\Services\NotificationSendService;
use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\ValidateDailyLimitService;
use App\Traits\InitializeAppStatusTrait;
use App\Http\Services\AuthenticationService;
use App\Models\Wallets\WalletTresortMoney;

class TresortMoneyController extends Controller
{

    use InitializeAppStatusTrait;

    /** @var  TresortRepository */
    protected $tresortRepository;
    protected $deletedService;
    protected $percentRobus;
    protected $validatedamountDays;
    protected $notificationPhone;
    protected $tresortService;
    private $authService;

    public function __construct(
        TresortRepository $tresort_Reposi,
        DeletedService $deletedService,
        PercentRobusAuthService $percentRobus,
        ValidateDailyLimitService $validatedamountDays,
        NotificationSendService $notificationPhone,
        TresortService $tresortService,
        AuthenticationService $authService
    ) {
        $this->tresortRepository = $tresort_Reposi;
        $this->deletedService = $deletedService;
        $this->percentRobus = $percentRobus;
        $this->authService = $authService;
        $this->validatedamountDays = $validatedamountDays;
        $this->notificationPhone = $notificationPhone;
        $this->tresortService = $tresortService;
        $this->initializeAppStatus($this->authService);
    }

    /**
     * @OA\POST(
     *      path="/user/tresort_money/store",
     *      tags={"Push User"},
     *      summary="Create new Push user",
     *      description="Faire des achats de credit telephonique 
     *       Selon les id des providers nous faisons nos verifications.
     *        Si provider_id =  18 (MTN) ou 19(Orange)  ou 20(Moov) nous verifions si le clients est en CIV alors nous faisons la verification selon les numero prefix des differents operateurs donc les 10 chiffres obligatoires
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
     *              @OA\Property(property="provider", type="string", example="MTN"),
     *              @OA\Property(property="description", type="string", example="Demande de credit telephonique"),
     *              @OA\Property(property="amount", type="number", example="1000"),
     *              @OA\Property(property="phone", type="number", example="0546963728"),
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
     *                          property="phone",
     *                          type="number"
     *                      ),
     *                    @OA\Property(
     *                          property="amount",
     *                          type="number"
     *                      ),
     *                 ),
     *                 example={
     *
     *                     "provider":"18",
     *                     "phone":"0546963728",
     *                     "amount":"1000",
     *                }
     *          
     *             )
     *         )
     *      ),
     *  @OA\Response(
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
    public function create_tresort(Request $request)
    {
        $amount_days = $this->validatedamountDays->getCeilingProperty();
        $valeur_ceiling  =  robus()->silver_ceiling;
        $admin_civ = $this->validatedamountDays->adminCiv();

        try {

            if (robus()->country->name_groupe == 'CIV') {

                if ($request->provider === 122) {


                    $validator = Validator::make(

                        $request->all(),
                        [
                            'phone' => 'required',
                            'full_name' => 'required',
                            'amount_tresort' => 'required',
                        ],
                        [
                            'phone.required' => 'Numero est obligatoire',
                            'full_name_push.required' => 'Nom & prénom sont obligatoire',
                            'amount_tresort.required' => 'Montant est obligatoire',
                        ]
                    );

                    if ($validator->fails()) {
                        return response()->json(
                            [
                                'success' => false,
                                'message' => 'Numero et montant sont obligatoire',
                                'data' => $validator->errors()
                            ],
                            333
                        );
                    }

                    $percent_robus =  $this->percentRobus->getRobusPercentageTresort();

                    if ($percent_robus != null) {

                        $reel_amount = $this->validatedamountDays->search_amount($request->amount_tresort);

                        if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                            if (robus()->is_under_distributer) {
                                $val_amount_send = $reel_amount;
                                $amountReceved = $reel_amount;
                            } else {

                                $val_amount_send = $reel_amount + (float) ($percent_robus->percent * $reel_amount);
                                $amountReceved = $reel_amount - (float) ($percent_robus->percent * $reel_amount);
                            }


                            if ($this->validatedamountDays->validateAmount($val_amount_send)) {

                                $walletPush =  $this->tresortService->createRobusWalletTransactionTresort($reel_amount, $request->provider, $percent_robus, $request->phone , $request->full_name, $amountReceved);

                                if ($walletPush != null) {

                                    if ($this->tresortService->updateamountTresortReceveid($admin_civ, $percent_robus, $walletPush, $reel_amount)) {
                                        $this->notificationPhone->sendNotificationTresort($walletPush);
                                        return $this->messageAfterValidated($walletPush);
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

                        return  json_message_response(false, "Pas de poucentage defini dans cette zone !", 333);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selectionnez un fournisseur existant SVP ',
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


    public function messageAfterValidated($walletPhone)
    {

        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletPhone->id,
                'provider' => $walletPhone->provider->name,
                'amount' => $walletPhone->amount,
                'status' => $walletPhone->status,
            ],
            'message' => ('Votre demande de transaction a bien été prise en compte.')
        ], 200);
    }
    /**
     * Delete Push
     * @OA\Delete (
     *      path="/user/tresort_money/delete/{id}",
     *      tags={"Push User"},
     *      summary="Delete Push",
     *      description="Supprimer les Rechargement credit", 
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
     *                          property="phone_credit_id",
     *                          type="number"
     *                      ),
     *                 ),
     *                
     *             )
     *         )
     *      ),
     * )
     */

    public function delete_tresort_money(Request $request)
    {

        try {

            $wallet_payment = WalletTresortMoney::find($request->id);

            if ($this->deletedService->deletedTransaction($wallet_payment)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cette transaction a été surppime de la liste.'
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


    // Search Valeur receive and valeur pay of Virement
    public function search_tresortMoney($amount)
    {

        $default_valeur = 2000000;


        try {

            $percent_robus =  $this->percentRobus->getRobusPercentageTresort();

            if ($percent_robus != null) {

                if ($amount != '') {

                    $reel_amount = $this->validatedamountDays->search_amount($amount);

                    $commission_amount_val_nat_pay = 0;
                    $commission_amount_val_nat_receive = 0;
                    $commission_amount_commission = 0;
                    $conversion_valeur_end  =  $default_valeur;

                    if ($conversion_valeur_end  < $reel_amount) {

                        $commission_amount_val_nat_pay = "Montant maxi est  : " .  number_format($conversion_valeur_end, 2, ',', ' ');

                        $commission_amount_val_nat_receive = "Montant maxi est  : " .  number_format($conversion_valeur_end, 2, ',', ' ');
                    } else {

                        if (robus()->is_under_distributer) {


                            $commission_amount_val_nat_pay =   $percent_robus->percent  *  $reel_amount;

                            $commission_amount_commission =   $percent_robus->percent_merchands  *  $reel_amount;

                            $valeur_receive =  $reel_amount;

                            if ($valeur_receive < 0) {

                                $commission_amount_val_nat_receive = "Montant negatif";
                            } else {

                                $commission_amount_val_nat_receive =  $valeur_receive;
                            }
                        } else {

                            $commission_amount_val_nat_pay =   $percent_robus->percent  *  $reel_amount;

                            $commission_amount_commission =   $percent_robus->percent  *  $reel_amount;

                            $valeur_receive =  $reel_amount - $commission_amount_val_nat_pay;

                            if ($valeur_receive < 0) {

                                $commission_amount_val_nat_receive = "Montant negatif";
                            } else {

                                $commission_amount_val_nat_receive =  $valeur_receive;
                            }
                        }
                    }

                    return response()->json([
                        'success' => $percent_robus != null ? true : false,
                        'data' =>  [
                            'amount_pay' => $commission_amount_commission,
                            'amount_send' => $commission_amount_val_nat_pay,
                            'amount_receive' => $commission_amount_val_nat_receive,
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

    public function search_tresort_received($amount)
    {
        $default_valeur = 2000000;


        try {

            $percent_robus =  $this->percentRobus->getRobusPercentageTresort();

            if ($percent_robus != null) {

                if ($amount != '') {

                    $reel_amount = $this->validatedamountDays->search_amount($amount);

                    $commission_amount_val_nat_pay = 0;
                    $commission_amount_val_nat_receive = 0;
                    $commission_amount_commission = 0;

                    //  Conversion valeur
                    $conversion_valeur_end  =  $default_valeur;

                    if ($conversion_valeur_end  < $reel_amount) {

                        $commission_amount_val_nat_pay = "Montant maxi est  : " .  number_format($conversion_valeur_end, 2, ',', ' ');

                        $commission_amount_val_nat_receive = "Montant maxi est  : " .  number_format($conversion_valeur_end, 2, ',', ' ');
                    } else {

                        if (robus()->is_under_distributer) {

                            $commission_amount_val_nat_pay =   $percent_robus->percent  *  $reel_amount;

                            $valeur_receive =  $reel_amount;

                            $commission_amount_commission =   $percent_robus->percent_merchands  *  $reel_amount;

                            if ($valeur_receive < 0) {

                                $commission_amount_val_nat_receive = "Montant negatif";
                            } else {

                                $commission_amount_val_nat_receive =  $valeur_receive;
                            }
                        } else {

                            $commission_amount_val_nat_pay =   $percent_robus->percent  *  $reel_amount;

                            $commission_amount_commission =   $percent_robus->percent  *  $reel_amount;


                            $valeur_receive =  $reel_amount +  (float) $commission_amount_val_nat_pay;

                            if ($valeur_receive < 0) {

                                $commission_amount_val_nat_receive = "Montant negatif";
                            } else {

                                $commission_amount_val_nat_receive =  $valeur_receive;
                            }
                        }
                    }

                    return response()->json([
                        'success' => $percent_robus != null ? true : false,
                        'data' =>  [
                            'amount_pay' => $commission_amount_commission,
                            'amount_send' => $commission_amount_val_nat_pay,
                            'amount_receive' => $commission_amount_val_nat_receive,
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
