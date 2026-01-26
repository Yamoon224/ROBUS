<?php

namespace App\Http\Controllers\Api\Transfers;


use App\Http\Controllers\Controller;
use App\Http\Services\DeletedService;
use App\Http\Services\NotificationSendService;
use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\XbetService;
use App\Http\Services\ValidateDailyLimitService;
use App\Http\Services\WaveService;
use App\Models\Wallets\WalletWave;
use App\Models\Wallets\WalletXbet;
use App\Repositories\OnexbetRepository;
use App\Repositories\WaveRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\InitializeAppStatusTrait;

class WaveTransactionController extends Controller
{

        /** @var  OnexbetRepository */
        protected $waveRepository;
        protected $deletedService;
        protected $percentRobus;
        protected $validatedamountDays;
        protected $notificationPhone;
        protected $waveService;

        public function __construct(
                WaveRepository $waveRepository,
                DeletedService $deletedService,
                PercentRobusAuthService $percentRobus,
                ValidateDailyLimitService $validatedamountDays,
                NotificationSendService $notificationPhone,
                WaveService $waveService,
        ) {
                $this->waveRepository = $waveRepository;
                $this->deletedService = $deletedService;
                $this->percentRobus = $percentRobus;
                $this->validatedamountDays = $validatedamountDays;
                $this->notificationPhone = $notificationPhone;
                $this->waveService = $waveService;
        }

        /**
         * @OA\POST(
         *      path="/user/wave/store",
         *      tags={"One xbets "},
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
        public function create_WaveTranaction(Request $request)
        {

                $amount_days = $this->validatedamountDays->getCeilingProperty();
                $valeur_ceiling  =  robus()->silver_ceiling;
                // valueur User send days
                $percent_robus =  $this->percentRobus->getRobusPercentageMobileWave();

                try {


                        if (robus()->country->name_groupe == 'CIV') {


                                $validator = Validator::make(

                                        $request->all(),
                                        [

                                                "amount" => 'required'
                                        ],
                                        [

                                                'amount.required' => 'Votre montant est obligatoire',

                                        ]
                                );

                                if ($validator->fails()) {
                                        return response()->json(
                                                [
                                                        'success' => false,
                                                        'message' => 'Numero et le montant  indisponible',
                                                        'data' => $validator->errors()
                                                ],
                                                333
                                        );
                                }


                                if ($request->provider == 94) {

                                        $reel_amount = $this->validatedamountDays->search_amount($request->amount);

                                        if ($percent_robus != null) {

                                                if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                                                        if ($this->validatedamountDays->validateAmountVirement($reel_amount)) {

                                                                // Transaction de retrait
                                                                $amountReceved =  $reel_amount - (float) ($percent_robus->percent  *  $reel_amount);
                                                                $transactionResult = $this->processDepositTransaction($request, $percent_robus, $amountReceved);

                                                                if ($transactionResult['success']) {


                                                                        $this->notificationPhone->sendNotificationWaves($transactionResult['walletWave']);

                                                                        return $this->messageAfterValidated($transactionResult['walletWave']);
                                                                } else {
                                                                        return json_message_response(false, $transactionResult['message'], 333);
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


        // Fonction pour traiter la transaction de dépôt
        private function processDepositTransaction($request, $percent_robus, $amountReceved)
        {
                $reel_amount = $this->validatedamountDays->search_amount($request->amount);

                $val_amount_send =  $reel_amount + (float) ($percent_robus->percent *  $reel_amount);

                if (!$this->validatedamountDays->validateAmount($val_amount_send)) {
                        return ['success' => false, 'message' => 'Vérifiez votre solde !!'];
                }

                $walletWave =  $this->waveService->createRobusWalletTransactionWave($reel_amount, $request->provider, $percent_robus, $amountReceved);

                if ($walletWave == null) {
                        return ['success' => false, 'message' => 'Erreur de création de transfert du montant!'];
                }

                // Mettre à jour le montant reçu dans le service OneXbet
                if (!$this->waveService->updateamountWaveReceveid($this->validatedamountDays->adminCiv(), $percent_robus, $walletWave,  $reel_amount)) {
                        return ['success' => false, 'message' => 'Erreur lors de la mise à jour du montant reçu!'];
                }

                return ['success' => true, 'walletWave' => $walletWave];
        }


        public function messageAfterValidated($walletWave)
        {

                return response()->json([
                        'success' => true,
                        'data' => [
                                'id'      => $walletWave->id,
                                'provider' => $walletWave->provider->name,
                                'amount' => $walletWave->amount,
                                'status' => $walletWave->status,
                        ],
                        'message' =>  'Votre demande de rechargement UV wave a bien été prise en compte.'
                ], 200);
        }
        /**
         * Delete 1xbet
         * @OA\Delete (
         *      path="/user/wave/delete/{id}",
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
         *                          property="wave_id",
         *                          type="number"
         *                      ),
         *                 ),
         *                
         *             )
         *         )
         *      ),
         * )
         */

        public function delete_wallet_wave(Request $request)
        {

                try {

                        $wallet_wave = WalletWave::find($request->wallet_wave);

                        if ($this->deletedService->deletedTransaction($wallet_wave)) {
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

        // Search Valeur receive and valeur pay of UV
        public function search_wave($amount_wave)
        {



                try {

                        $percent_robus =  $this->percentRobus->getRobusPercentageMobileWave();

                        if ($percent_robus != null) {

                                if ($amount_wave != '') {

                                        $commission_wave_val_nat_pay = 0;
                                        $commission_wave_val_nat_receive = 0;
                                        //  Conversion valeur

                                        $reel_amount = $this->validatedamountDays->search_amount($amount_wave);

                                        $commission_wave_val_nat_pay =   $percent_robus->percent  *  $reel_amount;

                                        $commission_wave_val_nat_receive =  $reel_amount - $commission_wave_val_nat_pay;

                                        return response()->json([
                                                'success' => $percent_robus != null ? true : false,
                                                'data' =>  [
                                                        'amount_pay' => $commission_wave_val_nat_pay,
                                                        'amount_send' => $commission_wave_val_nat_pay,
                                                        'amount_receive' => $commission_wave_val_nat_receive,
                                                ],
                                        ], 200);
                                }
                        } else {
                                return response()->json([
                                        'success' =>  false,
                                        'message' => 'Pas de Rechargement dans cette zone ...',
                                ], 200);
                        }
                } catch (\Exception $exception) {
                        return response()->json([
                                'success' =>  false,
                                'message' => 'Pas d\'utilisateur',
                        ], 200);
                }
        }

        // Search Valeur receive and valeur pay of UV
        public function search_wave_received($amount_wave)
        {



                try {

                        $percent_robus =  $this->percentRobus->getRobusPercentageMobileWave();

                        if ($percent_robus != null) {

                                if ($amount_wave != '') {

                                        $commission_wave_val_nat_pay = 0;
                                        $commission_wave_val_nat_receive = 0;

                                        $reel_amount = $this->validatedamountDays->search_amount($amount_wave);


                                        $commission_wave_val_nat_pay =   $percent_robus->percent  *  $reel_amount;

                                        $commission_wave_val_nat_receive =  $reel_amount + $commission_wave_val_nat_pay;

                                        return response()->json([
                                                'success' => $percent_robus != null ? true : false,
                                                'data' =>  [
                                                        'amount_pay' => $commission_wave_val_nat_pay,
                                                        'amount_send' => $commission_wave_val_nat_pay,
                                                        'amount_receive' => $commission_wave_val_nat_receive,
                                                ],
                                        ], 200);
                                }
                        } else {
                                return response()->json([
                                        'success' =>  false,
                                        'message' => 'Pas de Rechargement dans cette zone ...',
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
