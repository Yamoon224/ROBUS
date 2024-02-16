<?php

namespace App\Http\Controllers\Api\Transfers;


use App\Http\Controllers\Controller;
use App\Http\Services\DeletedService;
use App\Http\Services\NotificationSendService;
use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\XbetService;
use App\Http\Services\ValidateDailyLimitService;
use App\Models\Wallets\WalletXbet;
use App\Repositories\OnexbetRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OnexbetTransactionController extends Controller
{

        /** @var  OnexbetRepository */
        protected $oneXbetRepository;
        protected $deletedService;
        protected $percentRobus;
        protected $validatedamountDays;
        protected $notificationPhone;
        protected $oneXbetService;

        public function __construct(
                OnexbetRepository $xbetRepository,
                DeletedService $deletedService,
                PercentRobusAuthService $percentRobus,
                ValidateDailyLimitService $validatedamountDays,
                NotificationSendService $notificationPhone,
                XbetService $oneXbetService,
        ) {
                $this->oneXbetRepository = $xbetRepository;
                $this->deletedService = $deletedService;
                $this->percentRobus = $percentRobus;
                $this->validatedamountDays = $validatedamountDays;
                $this->notificationPhone = $notificationPhone;
                $this->oneXbetService = $oneXbetService;
        }

        /**
         * @OA\POST(
         *      path="/user/phone_credits/store",
         *      tags={"Phone credit User"},
         *      summary="Create new phone credit user",
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
        public function create_OnexbetTranaction(Request $request)
        {
               
                $amount_days = $this->validatedamountDays->getCeilingProperty();
                $valeur_ceiling  =  robus()->silver_ceiling;
                $admin_civ = $this->validatedamountDays->adminCiv();
                // valueur User send days
                $percent_robus =  $this->percentRobus->getRobusPercentageOnexbet();

                try {

                        if (robus()->country->name_groupe == 'CIV') {

                                $validationRules = [];
                                $validationMessages = [];

                                if ($request->choose_type_transaction == 1) {


                                        $validationRules = [
                                                "id_compte" => 'required',
                                                "amount" => 'required',
                                                "full_name" => 'required'
                                        ];

                                        $validationMessages = [
                                                'code_drawal.required' => 'Votre numero est obligatoire',
                                                'amount.required' => 'Votre montant est obligatoire',
                                                'full_name.required' => 'Votre montant est obligatoire',
                                        ];
                                } else {

                                        $validationRules = [
                                                "code_compte" => 'required',
                                                "amount" => 'required',
                                                "countrie" => 'required',
                                                "citie" => 'required',

                                        ];

                                        $validationMessages = [
                                                'code_compte.required' => 'Votre numero est obligatoire',
                                                'amount.required' => 'Votre montant est obligatoire',
                                                'countrie.required' => 'Votre pays est obligatoire',
                                                'citie.required' => 'Votre ville est obligatoire',
                                        ];
                                }

                                $validator = Validator::make($request->all(), $validationRules, $validationMessages);

                                if ($validator->fails()) {
                                        return response()->json([
                                                'success' => false,
                                                'message' => 'QUelque champs obligatoire',
                                                'data' => $validator->errors()
                                        ], 333);
                                }
                                if ($request->provider == 87) {

                                        if ($percent_robus != null) {

                                                if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                                                        $val_amount_send = $request->amount + ($percent_robus->percent * $request->amount);

                                                        $amountReceved = $request->amount;

                                                        if ($this->validatedamountDays->validateAmount($val_amount_send)) {

                                                                if ($request->choose_type_transaction == 1) {
                                                                        $walletXbet =  $this->oneXbetService->createRobusWalletTransactionOneBet($request->amount, $request->provider, $percent_robus, $request->id_compte,  $request->full_name, $amountReceved);
                                                                } else {
                                                                        $walletXbet =  $this->oneXbetService->createRobusWalletDrawalOneBet($request->amount, $request->provider, $percent_robus, $request->code_compte, $request->countrie, $request->citie, $amountReceved);
                                                                }

                                                                if ($walletXbet != null) {

                                                                        $methodName = ($request->choose_type_transaction == 1) ? 'updateamountOneBetReceveid' : 'updateamountOneBetDrawal';

                                                                        if ($this->oneXbetService->$methodName($admin_civ, $percent_robus, $walletXbet, $request->amount)) {
                                                                                $this->notificationPhone->sendNotification1xbets($walletXbet);
                                                                                return $this->messageAfterValidated($walletXbet);
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

   

        public function messageAfterValidated($walletXbet)
        {

                return response()->json([
                        'success' => true,
                        'data' => [
                                'id'      => $walletXbet->id,
                                'provider' => $walletXbet->provider->name,
                                'amount' => $walletXbet->amount,
                                'status' => $walletXbet->status,
                        ],
                        'message' =>  $walletXbet->choose_type_transaction == 1 ? ('Votre demande de rechargement 1xbet a bien été prise en compte.') : ('Votre demande de retrait 1xbet a bien été prise en compte.')
                ], 200);
        }
        /**
         * Delete 1xbet
         * @OA\Delete (
         *      path="/user/phone_credit/delete/{id}",
         *      tags={"1xbet User"},
         *      summary="Delete 1xbet",
         *      description="Supprimer les 1xbet ", 
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

        public function delete_wallet_xbet(Request $request)
        {

                try {

                        $wallet_1xbet = WalletXbet::find($request->wallet_xbet);

                        if ($this->deletedService->deletedTransaction($wallet_1xbet)) {
                                return response()->json([
                                        'success' => true,
                                        'message' => 'Cette demande a été surppime de la liste.'
                                ], 200);
                        } else {
                                return response()->json([
                                        'success' => false,
                                        'message' => 'Erreur de suppression de la demande .'
                                ], 200);
                        }
                } catch (\Exception $exception) {
                        return   json_message_response(false, "Erreur de suppression !", 300);
                }
        }
}
