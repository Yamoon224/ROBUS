<?php

namespace App\Http\Controllers\Api\Transfers;


use Illuminate\Http\Request;
use App\Models\Banks\BankCard;
use App\Http\Controllers\Controller;
use App\Http\Services\DeletedService;
use App\Http\Services\OrderCardService;
use App\Repositories\OrderCardRepository;
use Illuminate\Support\Facades\Validator;
use App\Http\Services\NotificationSendService;
use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\ValidateDailyLimitService;

class BankCardController extends Controller
{

    /** @var  OrderCardRepository */
    protected $phoneCreditRepository;
    protected $deletedService;
    protected $percentRobus;
    protected $validatedamountDays;
    protected $notificationOrderCard;
    protected $orderCardService;

    public function __construct(
        OrderCardRepository $reload_cardReposi,
        DeletedService $deletedService,
        PercentRobusAuthService $percentRobus,
        ValidateDailyLimitService $validatedamountDays,
        NotificationSendService $notificationOrderCard,
        OrderCardService $orderCardService,
    ) {
        $this->phoneCreditRepository = $reload_cardReposi;
        $this->deletedService = $deletedService;
        $this->percentRobus = $percentRobus;
        $this->validatedamountDays = $validatedamountDays;
        $this->notificationOrderCard = $notificationOrderCard;
        $this->orderCardService = $orderCardService;
    }

    /**
     * @OA\POST(
     *      path="/user/order_cards/store",
     *      tags={"order card User"},
     *      summary="Create new order card",
     *      description="Faire des commandes de carte depuis son dashbord. 
     *       .    
     *         ",
     *  @OA\Parameter(
     *         in="path",
     *         name="email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     * @OA\Response(
     *          response=200,
     *          description="success Pour un exemple sur une demande de rechargement de commande de carte",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="phone", type="string", example="0708090708"),
     *              @OA\Property(property="email", type="string", example="ibe@gmail.com"),
     *              @OA\Property(property="status", type="string", example="Pending"),
     *          )
     *     ),
     *  @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="phone",
     *                          type="number"
     *                      ),
     *                    @OA\Property(
     *                          property="email",
     *                          type="number"
     *                      ),
     *                    @OA\Property(
     *                          property="identity_doc",
     *                          type="string"
     *                      ),
     *                   @OA\Property(
     *                          property="identity_id",
     *                          type="string"
     *                      ),
     *                  @OA\Property(
     *                          property="identity_form",
     *                          type="string"
     *                      ),
     *                 ),
     * 
     *                example = {
     * 
     *                     "phone":"09090909",
     *                     "email":"uba@gmail.com",
     *                     "identity_doc":"/public/images/piece.jpg",
     *                     "identity_doc":"/public/images/piece.jpg",
     *                     "identity_doc":"/public/images/piece.jpg",
     * 
     *                }
     *                
     *             )
     *         )
     *      ),
     * 
     * @OA\Response(
     *          response=341,
     *          description="Error updalod image."
     *      ),
     *  ),
     */
    public function create_order_card(Request $request)
    {
        $amount_days = $this->validatedamountDays->getCeilingProperty();
        $valeur_ceiling  =  robus()->silver_ceiling;
        $admin_civ = $this->validatedamountDays->adminCiv();

        try {

            // if (in_array($request->provider, [1, 3, 4, 5, 6])) {

            $validator = Validator::make(
                $request->all(),
                [
                    'email' => 'required',
                    'address' => 'required',
                ],
                [
                    'email.required' => 'Votre email  est obligatoire',
                    'address.required' => 'Votre adresse  est obligatoire',
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

            $percent_robus =  $this->percentRobus->getRobusPercentageCardOrder($request->provider);
            
            if ($percent_robus != null) {

                if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                    $valeur_amount =  $this->orderCardService->convertAdminCommission($percent_robus->amount);


                    if ($this->validatedamountDays->validateAmount($valeur_amount)) {

                        $walletOrderCard =  $this->orderCardService->createRobusWalletOrderCard($percent_robus->amount, $request->provider, $percent_robus, $request->address, $request->email);

                        if ($walletOrderCard != null) {

                            if ($this->orderCardService->updateamountOrderCardReceveid($admin_civ, $percent_robus, $walletOrderCard, $valeur_amount)) {
                                $this->notificationOrderCard->sendNotificationOrderCard($walletOrderCard);
                                return $this->messageAfterValidated($walletOrderCard);
                            }
                        } else {

                            return  json_message_response(false, "Error de creation de transfert du montant !", 333);
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
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
            ], 333);
        }
    }

    public function messageAfterValidated($walletOrderCard)
    {


        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $walletOrderCard->id,
                'provider' => $walletOrderCard->provider->name,
                'amount' => $walletOrderCard->amount,
                'status' => $walletOrderCard->status,
            ],
            'message' => ('Votre demande de transaction a bien été prise en compte.')
        ], 200);
    }

    /**
     * @OA\POST(
     *      path="/user/order_cards_other/create",
     *      tags={"order card other User"},
     *      summary="Create new order card",
     *      description="Faire des commandes de carte depuis son dashbord. 
     *       .    
     *         ",
     *  @OA\Parameter(
     *         in="path",
     *         name="email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     * @OA\Response(
     *          response=200,
     *          description="success Pour un exemple sur une demande de rechargement de commande de carte",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="phone", type="string", example="0708090708"),
     *              @OA\Property(property="email", type="string", example="ibe@gmail.com"),
     *              @OA\Property(property="status", type="string", example="Pending"),
     *          )
     *     ),
     *  @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="phone",
     *                          type="number"
     *                      ),
     *                    @OA\Property(
     *                          property="email",
     *                          type="number"
     *                      ),
     *                    @OA\Property(
     *                          property="identity_doc",
     *                          type="string"
     *                      ),
     *                  @OA\Property(
     *                          property="identity_form",
     *                          type="string"
     *                      ),
     *                 ),
     * 
     *                example = {
     * 
     *                     "phone":"09090909",
     *                     "email":"uba@gmail.com",
     *                     "identity_doc":"/public/images/piece.jpg",
     *                     "identity_doc":"/public/images/piece.jpg",
     *                     "identity_doc":"/public/images/piece.jpg",
     * 
     *                }
     *                
     *             )
     *         )
     *      ),
     * 
     * @OA\Response(
     *          response=341,
     *          description="Error updalod image."
     *      ),
     *  ),
     */
    public function create_order_card_other(Request $request)
    {
        $amount_days = $this->validatedamountDays->getCeilingProperty();
        $valeur_ceiling  =  robus()->silver_ceiling;
        $admin_civ = $this->validatedamountDays->adminCiv();


        try {

            // if (in_array($request->provider, [1, 3, 4, 5, 6])) {

            if (robus()->is_under_distributer) {

                $validator = Validator::make(

                    $request->all(),
                    [
                        'phone' => 'required',
                        'email' => 'required',
                        'address' => 'required',
                        'provider' => 'required',
                        'recto' => 'required|mimes:jpeg,png,jpg|max:2048',
                        'verso' => 'required|mimes:jpeg,png,jpg|max:2048',
                        'number_card' => 'required',

                    ],
                    [
                        'phone.required' => 'Votre telephone  est obligatoire',
                        'email.required' => 'Votre email  est obligatoire',
                        'recto.required' => 'Votre image est obligatoire',
                        'provider.required' => 'Fournisseur est obligatoire',
                        'verso.required' => 'Votre image est obligatoire',
                        'number_card.required' => 'Votre image est obligatoire',
                        'address.required' => 'Votre adresse est obligatoire',
                    ]

                );
            } else {
                $validator = Validator::make(

                    $request->all(),
                    [
                        'phone' => 'required',
                        'email' => 'required',
                        'address' => 'required',
                        'provider' => 'required',
                        'recto' => 'required|mimes:jpeg,png,jpg|max:2048',
                        'verso' => 'required|mimes:jpeg,png,jpg|max:2048',

                    ],
                    [
                        'phone.required' => 'Votre telephone  est obligatoire',
                        'email.required' => 'Votre email  est obligatoire',
                        'recto.required' => 'Votre image est obligatoire',
                        'provider.required' => 'Fournisseur est obligatoire',
                        'verso.required' => 'Votre image est obligatoire',
                        'address.required' => 'Votre adresse est obligatoire',
                    ]

                );
            }

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


            $percent_robus =  $this->percentRobus->getRobusPercentageCardOrder($request->provider);

            if ($percent_robus != null) {

                if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                    $valeur_amount =  $this->orderCardService->convertAdminCommission($percent_robus->amount);

                    $file_identity_doc = $request->file('recto');
                    $file_identity_form = $request->file('verso');

                    if ($this->validatedamountDays->validateAmount($valeur_amount)) {

                        $walletOrderCard =  $this->orderCardService->createRobusWalletOrderCardOther($percent_robus->amount, $request->provider, $percent_robus, $request->address, $request->email, $request->phone, $request->number_card, $file_identity_doc, $file_identity_form);

                        if ($walletOrderCard != null) {

                            if ($this->orderCardService->updateamountOrderCardReceveid($admin_civ, $percent_robus, $walletOrderCard, $valeur_amount)) {
                                $this->notificationOrderCard->sendNotificationOrderCard($walletOrderCard);
                                return $this->messageAfterValidated($walletOrderCard);
                            }
                        } else {

                            return  json_message_response(false, "Error de creation de transfert du montant !", 333);
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
            // } else {

            //     return  json_message_response(false, "Selectionnez une carte visible !", 333);
            // }
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
            ], 333);
        }
    }


    /**
     * Get Detail order card
     * @OA\Get (
     *      path="/user/order_card/show/{id}",
     *      tags={"Order card User"},
     *      summary="Show details order card",
     *      description="Details sur les demande de carte",   
     *  @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="phone", type="string", example="+22309898989"),
     *              @OA\Property(property="email", type="double", example="uba@gmail.com"),
     *              @OA\Property(property="images", type="string", example="public/image/piece.jpg"),
     *              @OA\Property(property="Status", type="string", example="pending"),
     *              @OA\Property(property="updated_at", type="string", example="2022-08-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2022-08-11T09:25:53.000000Z")
     *         )
     *     )
     * )
     */
    public function order_card_show($reload_card)
    {

        $wallet_solde = BankCard::find($reload_card)->first();


        if ($wallet_solde->id == null) {
            return response()->json([
                'success' => false,
                'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
            ], 333);
        }


        return response()->json([
            'success' => true,
            'message' => 'Demande success.',
            'data' =>  [
                'phone' => $wallet_solde->phone,
                'email' => $wallet_solde->email,
                'status' => $wallet_solde->status,
                'identity_doc' => $wallet_solde->identity_doc,
                'identity_id' => $wallet_solde->identity_id,
                'ref_card' => $wallet_solde->ref_card ?? null,
                'email' => $wallet_solde->email ?? null,
                'address' => $wallet_solde->address ?? null,
            ],
        ]);
    }
}