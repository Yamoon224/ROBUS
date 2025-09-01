<?php

namespace App\Http\Controllers\Api\Transfers;


use App\Http\Controllers\Controller;
use App\Http\Services\DeletedService;
use App\Http\Services\NotificationSendService;
use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\ValidateDailyLimitService;
use App\Http\Services\WithService;
use App\Models\Wallets\WalletXbet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class withTransactionController extends Controller
{

        protected $deletedService;
        protected $percentRobus;
        protected $validatedamountDays;
        protected $notificationPhone;
        protected $withService;

        public function __construct(
                DeletedService $deletedService,
                PercentRobusAuthService $percentRobus,
                ValidateDailyLimitService $validatedamountDays,
                NotificationSendService $notificationPhone,
                WithService $withService,
        ) {
                $this->deletedService = $deletedService;
                $this->percentRobus = $percentRobus;
                $this->validatedamountDays = $validatedamountDays;
                $this->notificationPhone = $notificationPhone;
                $this->withService = $withService;
        }

        /**
         * @OA\POST(
         *      path="/user/push/store",
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
        public function create_PushTranaction(Request $request)
        {

                $amount_days = $this->validatedamountDays->getCeilingProperty();
                $valeur_ceiling  =  robus()->silver_ceiling;
                // valueur User send days
                $percent_robus =  $this->percentRobus->getRobusPercentagePush();

                try {


                        if (robus()->is_under_distributer) {

                                $validator = Validator::make(

                                        $request->all(),
                                        [
                                                "phone" => 'required',
                                                "amount" => 'required',
                                                "full_name" => 'required',
                                        ],
                                        [

                                                'phone.required' => 'Votre numero est obligatoire',
                                                'amount.required' => 'Votre montant est obligatoire',
                                                'full_name.required' => 'Nom est obligatoire'

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
                                        ],
                                        [

                                                'phone.required' => 'Votre numero est obligatoire',
                                                'amount.required' => 'Votre montant est obligatoire',


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


                        if ($request->provider == 107) {

                                if ($percent_robus != null) {

                                        if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                                                $reel_amount = $this->validatedamountDays->search_amount($request->amount);

                                                $amountReceved =  $reel_amount;
                                                $transactionResult = $this->processPushTransaction($request, $percent_robus, $amountReceved);

                                                if ($transactionResult['success']) {

                                                        $this->notificationPhone->sendNotificationWiths($transactionResult['walletWith']);
                                                        return $this->messageAfterValidated($transactionResult['walletWith']);
                                                } else {
                                                        return json_message_response(false, $transactionResult['message'], 333);
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
                } catch (\Exception $exception) {

                        return response()->json([
                                'success' => false,
                                'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
                        ], 333);
                }
        }

        public function create_TresortTranaction(Request $request)
        {

                $amount_days = $this->validatedamountDays->getCeilingProperty();
                $valeur_ceiling  =  robus()->silver_ceiling;
                // valueur User send days
                $percent_robus =  $this->percentRobus->getRobusPercentageTresort();

                try {


                        if (robus()->is_under_distributer) {

                                $validator = Validator::make(

                                        $request->all(),
                                        [
                                                "phone" => 'required',
                                                "amount" => 'required',
                                                "full_name" => 'required',
                                        ],
                                        [

                                                'phone.required' => 'Votre numero est obligatoire',
                                                'amount.required' => 'Votre montant est obligatoire',
                                                'full_name.required' => 'Nom est obligatoire'

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
                                        ],
                                        [

                                                'phone.required' => 'Votre numero est obligatoire',
                                                'amount.required' => 'Votre montant est obligatoire',


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


                        if ($request->provider == 107) {

                                if ($percent_robus != null) {

                                        if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                                                $reel_amount = $this->validatedamountDays->search_amount($request->amount);

                                                $amountReceved =  $reel_amount;
                                                $transactionResult = $this->processTresortTransaction($request, $percent_robus, $amountReceved);

                                                if ($transactionResult['success']) {

                                                        $this->notificationPhone->sendNotificationWiths($transactionResult['walletWith']);
                                                        return $this->messageAfterValidated($transactionResult['walletWith']);
                                                } else {
                                                        return json_message_response(false, $transactionResult['message'], 333);
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
                } catch (\Exception $exception) {

                        return response()->json([
                                'success' => false,
                                'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
                        ], 333);
                }
        }

        public function create_DjamoTranaction(Request $request)
        {

                $amount_days = $this->validatedamountDays->getCeilingProperty();
                $valeur_ceiling  =  robus()->silver_ceiling;
                // valueur User send days
                $percent_robus =  $this->percentRobus->getRobusPercentagePush();

                try {


                        if (robus()->is_under_distributer) {

                                $validator = Validator::make(

                                        $request->all(),
                                        [
                                                "phone" => 'required',
                                                "amount" => 'required',
                                                "name_custumer" => 'required',
                                                "image_piece" => 'required',
                                        ],
                                        [

                                                'phone.required' => 'Votre numero est obligatoire',
                                                'amount.required' => 'Votre montant est obligatoire',
                                                'name_custumer.required' => 'Nom est obligatoire',
                                                'image_piece.required' => 'date de validité est obligatoire',

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


                        if ($request->provider == 107) {

                                if ($percent_robus != null) {

                                        if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                                                $reel_amount = $this->validatedamountDays->search_amount($request->amount);

                                                $amountReceved =  $reel_amount;

                                                $file_front = $request->file('image_piece');
                                                $imagePath = null;

                                                if ($file_front) {
                                                        // Chemin de destination
                                                        $destination_path = public_path('image_inters/' . $request->name_custumer . '/');

                                                        // Création du dossier si inexistant
                                                        if (!file_exists($destination_path)) {
                                                                mkdir($destination_path, 0777, true);
                                                        }

                                                        // Générer un nom unique
                                                        $front_file = date('YmdHis') . "." . $file_front->getClientOriginalExtension();

                                                        // Déplacer le fichier
                                                        $file_front->move($destination_path, $front_file);

                                                        // Construire l’URL de l’image
                                                        $imagePath = env('APP_URL') . "/image_inters/" . $request->name_custumer . "/$front_file";
                                                }

                                                $transactionResult = $this->processDjamoTransaction($request, $percent_robus, $amountReceved);

                                                if ($transactionResult['success']) {

                                                        $this->notificationPhone->sendNotificationWiths($transactionResult['walletWith']);
                                                        return $this->messageAfterValidated($transactionResult['walletWith']);
                                                } else {
                                                        return json_message_response(false, $transactionResult['message'], 333);
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
                } catch (\Exception $exception) {

                        return response()->json([
                                'success' => false,
                                'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
                        ], 333);
                }
        }


        // Fonction pour traiter la transaction de dépôt
        private function processPushTransaction($request, $percent_robus, $amountReceved)
        {
                $reel_amount = $this->validatedamountDays->search_amount($request->amount);

                if (robus()->is_under_distributer) {
                        $val_amount_send = $reel_amount;
                } else {
                        $val_amount_send = $reel_amount + (float) ($percent_robus->percent * $reel_amount);
                }

                if (!$this->validatedamountDays->validateAmount($val_amount_send)) {
                        return ['success' => false, 'message' => 'Vérifiez votre solde !!'];
                }

                $walletPush =  $this->withService->createWalletTransactionPush($reel_amount, $request->provider, $percent_robus, $request->phone,  $request->full_name, $amountReceved);
                if ($walletPush == null) {
                        return ['success' => false, 'message' => 'Erreur de création de transfert du montant!'];
                }

                // Mettre à jour le montant reçu dans le service OneXbet
                if (!$this->withService->updateamountPushReceveid($this->validatedamountDays->adminCiv(), $percent_robus, $walletPush, $request->amount)) {
                        return ['success' => false, 'message' => 'Erreur lors de la mise à jour du montant reçu!'];
                }

                return ['success' => true, 'walletPush' => $walletPush];
        }

        private function processTresortTransaction($request, $percent_robus, $amountReceved)
        {
                $reel_amount = $this->validatedamountDays->search_amount($request->amount);

                if (robus()->is_under_distributer) {
                        $val_amount_send = $reel_amount;
                } else {
                        $val_amount_send = $reel_amount + (float) ($percent_robus->percent * $reel_amount);
                }

                if (!$this->validatedamountDays->validateAmount($val_amount_send)) {
                        return ['success' => false, 'message' => 'Vérifiez votre solde !!'];
                }

                $walletPush =  $this->withService->createWalletTransactionTresortMoney($reel_amount, $request->provider, $percent_robus, $request->phone,  $request->full_name, $amountReceved);
                if ($walletPush == null) {
                        return ['success' => false, 'message' => 'Erreur de création de transfert du montant!'];
                }

                // Mettre à jour le montant reçu dans le service OneXbet
                if (!$this->withService->updateamountTresortReceveid($this->validatedamountDays->adminCiv(), $percent_robus, $walletPush, $request->amount)) {
                        return ['success' => false, 'message' => 'Erreur lors de la mise à jour du montant reçu!'];
                }

                return ['success' => true, 'walletPush' => $walletPush];
        }

        private function processDjamoTransaction($request, $percent_robus, $amountReceved , $imagePath )
        {
                $reel_amount = $this->validatedamountDays->search_amount($request->amount);

                if (robus()->is_under_distributer) {
                        $val_amount_send = $reel_amount;
                } else {
                        $val_amount_send = $reel_amount + (float) ($percent_robus->percent * $reel_amount);
                }

                if (!$this->validatedamountDays->validateAmount($val_amount_send)) {
                        return ['success' => false, 'message' => 'Vérifiez votre solde !!'];
                }

                $walletPush =  $this->withService->createWalletTransactionDjamo($reel_amount, $request->provider, $percent_robus, $request->phone,  $request->full_name, $amountReceved , $imagePath );
                if ($walletPush == null) {
                        return ['success' => false, 'message' => 'Erreur de création de transfert du montant!'];
                }

                // Mettre à jour le montant reçu dans le service OneXbet
                if (!$this->withService->updateamountPushReceveid($this->validatedamountDays->adminCiv(), $percent_robus, $walletPush, $request->amount)) {
                        return ['success' => false, 'message' => 'Erreur lors de la mise à jour du montant reçu!'];
                }

                return ['success' => true, 'walletPush' => $walletPush];
        }



        public function messageAfterValidated($walletPush)
        {

                return response()->json([
                        'success' => true,
                        'data' => [
                                'id'      => $walletPush->id,
                                'provider' => $walletPush->provider->name,
                                'amount' => $walletPush->amount,
                                'status' => $walletPush->status,
                        ],
                        'message' =>  $walletPush->choose_type_transaction == 1 ? ('Votre demande de rechargement  a bien été prise en compte.') : ('Votre demande de retrait  a bien été prise en compte.')
                ], 200);
        }
        /**
         * Delete 1xbet
         * @OA\Delete (
         *      path="/user/onexbet/delete/{id}",
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

        // Search Valeur receive and valeur pay of UV


        public function search_xbet($amount_wave)
        {
                try {
                        $percent_robus = $this->percentRobus->getRobusPercentageOnexbet();

                        if ($percent_robus == null) {
                                return response()->json([
                                        'success' => false,
                                        'message' => 'Pas de rechargement dans cette zone...',
                                ], 200);
                        }

                        $commission_wave_val_nat_pay = 0;
                        $commission_wave_val_nat_receive = 0;


                        if ($amount_wave != '') {

                                $reel_amount = $this->validatedamountDays->search_amount($amount_wave);
                                // Conversion valeur
                                $commission_wave_val_nat_pay = $percent_robus->percent * $reel_amount;
                                $commission_wave_val_nat_receive = $reel_amount - $commission_wave_val_nat_pay;
                        } else {
                                return response()->json([
                                        'success' => false,
                                        'message' => 'Montant d\'onde ou montant reçu non spécifié...',
                                ], 400);
                        }

                        return response()->json([
                                'success' => true,
                                'data' =>  [
                                        'amount_pay' => $commission_wave_val_nat_pay,
                                        'amount_send' => $commission_wave_val_nat_pay,
                                        'amount_receive' => $commission_wave_val_nat_receive,
                                ],
                        ], 200);
                } catch (\Exception $exception) {
                        return response()->json([
                                'success' => false,
                                'message' => 'Une erreur s\'est produite lors de la recherche.',
                        ], 500);
                }
        }

        public function search_xbet_received($amount_received)
        {
                try {
                        $percent_robus = $this->percentRobus->getRobusPercentageOnexbet();

                        if ($percent_robus == null) {
                                return response()->json([
                                        'success' => false,
                                        'message' => 'Pas de rechargement dans cette zone...',
                                ], 200);
                        }

                        $commission_wave_val_nat_pay = 0;
                        $commission_wave_val_nat_receive = 0;



                        if ($amount_received != '') {

                                $reel_amount = $this->validatedamountDays->search_amount($amount_received);
                                $commission_wave_val_nat_pay = $percent_robus->percent * $reel_amount;
                                $commission_wave_val_nat_receive = $reel_amount + $commission_wave_val_nat_pay;
                        } else {
                                return response()->json([
                                        'success' => false,
                                        'message' => 'Montant d\'onde ou montant reçu non spécifié...',
                                ], 400);
                        }



                        return response()->json([
                                'success' => true,
                                'data' =>  [
                                        'amount_pay' => $commission_wave_val_nat_pay,
                                        'amount_send' => $commission_wave_val_nat_pay,
                                        'amount_receive' => $commission_wave_val_nat_receive,
                                ],
                        ], 200);
                } catch (\Exception $exception) {
                        return response()->json([
                                'success' => false,
                                'message' => 'Une erreur s\'est produite lors de la recherche.',
                        ], 500);
                }
        }
}
