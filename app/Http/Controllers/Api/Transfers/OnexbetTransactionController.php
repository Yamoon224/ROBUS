<?php

namespace App\Http\Controllers\Api\Transfers;


use App\Http\Controllers\Controller;
use App\Http\Services\DeletedService;
use App\Http\Services\NotificationSendService;
use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\ValidateDailyLimitService;
use App\Http\Services\XbetService;
use App\Models\Wallets\Wallet;
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
         *      path="/user/onexbets/store",
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
        public function create_OnexbetTranaction(Request $request)
        {

                $amount_days = $this->validatedamountDays->getCeilingProperty();
                $valeur_ceiling  =  robus()->silver_ceiling;
                // valueur User send days
                $percent_robus =  $this->percentRobus->getRobusPercentageOnexbet();

                try {



                        $validationRules = [];
                        $validationMessages = [];

                        if ($request->choose_type_transaction == 1) {


                                $validationRules = [
                                        "id_compte" => 'required',
                                        "amount" => 'required',
                                        "full_name" => 'required'
                                ];

                                $validationMessages = [
                                        'id_compte.required' => 'Votre numero est obligatoire',
                                        'amount.required' => 'Votre montant est obligatoire',
                                        'full_name.required' => 'Votre montant est obligatoire',
                                ];
                        } else {

                                $validationRules = [
                                        "code_compte" => 'required',
                                        "amount" => 'required',
                                        "countrie" => 'required',
                                        "citie" => 'required',
                                        "id_xbet" => 'required',

                                ];

                                $validationMessages = [
                                        'code_compte.required' => 'Votre numero est obligatoire',
                                        'amount.required' => 'Votre montant est obligatoire',
                                        'countrie.required' => 'Votre pays est obligatoire',
                                        'citie.required' => 'Votre ville est obligatoire',
                                        'id_xbet.required' => 'Id Xbet est obligatoire',
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

                        if (robus()->is_custumers == true) {

                                if ($request->choose_type_transaction == 2) {

                                        // 🚫 Interdire l’auto-retrait
                                        if ($request->code_compte == $request->id_xbet) {
                                                return json_message_response(
                                                        false,
                                                        "Vous ne pouvez pas effectuer un retrait vue que le code de retrait est identique au numero du compte Xbet.",
                                                        403
                                                );
                                        }

                                        // 🔍 Vérifier si un dépôt Xbet a déjà été effectué
                                        $hasDepositXbet = WalletXbet::where('num_acompte', $request->id_xbet)
                                                ->where('wallet_id', robus()->wallet->id)
                                                ->exists();

                                        if (! $hasDepositXbet) {
                                                return json_message_response(
                                                        false,
                                                        "Vous ne pouvez pas retirer vers ce compte Xbet tant qu’aucun dépôt n’a été effectué.",
                                                        403
                                                );
                                        }
                                }
                        }


                        if ($request->provider == 87) {

                                if ($percent_robus != null) {

                                        if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {


                                                $reel_amount = $this->validatedamountDays->search_amount($request->amount);

                                                if ($request->choose_type_transaction == 1) {
                                                        $val_amount_send = $reel_amount;
                                                } else {
                                                        $val_amount_send = $reel_amount;
                                                }

                                                if ($request->choose_type_transaction == 2) {

                                                        if ($this->validatedamountDays->validateAmount($val_amount_send)) {

                                                                $amountReceved =  $reel_amount - (float) ($percent_robus->percent_with_drawal *  $reel_amount);
                                                                $transactionResult = $this->processWithdrawalTransaction($request, $percent_robus, $amountReceved, $val_amount_send);
                                                                if ($transactionResult['success']) {

                                                                        $this->notificationPhone->sendNotification1xbets($transactionResult['walletXbet']);
                                                                        return $this->messageAfterValidated($transactionResult['walletXbet']);
                                                                } else {
                                                                        return json_message_response(false, $transactionResult['message'], 333);
                                                                }
                                                        } else {
                                                                return json_message_response(false, "Vérifiez votre solde !!", 341);
                                                        }
                                                } else {
                                                        if ($this->validatedamountDays->validateAmount($val_amount_send)) {

                                                                if ($request->choose_type_transaction == 1) {

                                                                        $amountReceved = (float) $reel_amount - (float) ($percent_robus->percent * $reel_amount);

                                                                        $transactionResult = $this->processDepositTransaction($request, $percent_robus, $amountReceved, $val_amount_send);
                                                                } else {

                                                                        $amountReceved =  $reel_amount - (float) ($percent_robus->percent_with_drawal *  $reel_amount);
                                                                        $transactionResult = $this->processWithdrawalTransaction($request, $percent_robus, $amountReceved, $val_amount_send);
                                                                }

                                                                if ($transactionResult['success']) {

                                                                        $this->notificationPhone->sendNotification1xbets($transactionResult['walletXbet']);
                                                                        return $this->messageAfterValidated($transactionResult['walletXbet']);
                                                                } else {
                                                                        return json_message_response(false, $transactionResult['message'], 333);
                                                                }
                                                        } else {
                                                                return json_message_response(false, "Vérifiez votre solde !!", 341);
                                                        }
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
        private function processDepositTransaction($request, $percent_robus, $amountReceved, $val_amount_send)
        {
                $reel_amount = $this->validatedamountDays->search_amount($request->amount);

                $walletXbet =  $this->oneXbetService->createRobusWalletTransactionOneBet($reel_amount, $request->provider, $percent_robus, $request->id_compte,  $request->full_name, $amountReceved);
                if ($walletXbet == null) {
                        return ['success' => false, 'message' => 'Erreur de création de transfert du montant!'];
                }

                // Mettre à jour le montant reçu dans le service OneXbet
                if (!$this->oneXbetService->updateamountOneBetReceveid($this->validatedamountDays->adminCiv(), $percent_robus, $walletXbet, $request->amount, $val_amount_send)) {
                        return ['success' => false, 'message' => 'Erreur lors de la mise à jour du montant reçu!'];
                }

                return ['success' => true, 'walletXbet' => $walletXbet];
        }

        // Fonction pour traiter la transaction de retrait
        private function processWithdrawalTransaction($request, $percent_robus, $amountReceved, $val_amount_send)
        {

                $reel_amount = $this->validatedamountDays->search_amount($request->amount);

                $walletXbet =  $this->oneXbetService->createRobusWalletDrawalOneBet($reel_amount, $request->provider, $percent_robus, $request->code_compte, $request->countrie, $request->citie, $request->id_xbet, $amountReceved);
                if ($walletXbet == null) {
                        return ['success' => false, 'message' => 'Erreur de création de transfert du montant!'];
                }

                // Mettre à jour le montant reçu dans le service OneXbet
                if (!$this->oneXbetService->updateamountOneBetDrawal($this->validatedamountDays->adminCiv(), $percent_robus, $walletXbet,  $reel_amount, $val_amount_send)) {
                        return ['success' => false, 'message' => 'Erreur lors de la mise à jour du montant reçu!'];
                }


                return ['success' => true, 'walletXbet' => $walletXbet];
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


        public function search_xbet($amount_wave, $is_drawal = false)
        {
                try {
                        $is_drawal = filter_var($is_drawal, FILTER_VALIDATE_BOOLEAN);

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
                                if ($is_drawal) {
                                        $commission_wave_val_nat_pay = $percent_robus->percent_with_drawal * $reel_amount;
                                } else {
                                        $commission_wave_val_nat_pay = $percent_robus->percent * $reel_amount;
                                }
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

        public function search_first_xbet($wallet_id,  $is_drawal = false)
        {
                try {

                        $is_drawal = filter_var($is_drawal, FILTER_VALIDATE_BOOLEAN);

                        // 🔎 Récupération du wallet
                        $wallet = Wallet::where('wallet_id', $wallet_id)->first();

                        if (!$wallet) {
                                return response()->json([
                                        'success' => false,
                                        'message' => 'Aucun wallet trouvé pour ce numéro de wallet.',
                                ], 404);
                        }

                        // 🔁 Recherche XBET
                        $query = WalletXbet::where('wallet_id', $wallet->id);

                        // Retrait → première transaction
                        if ($is_drawal) {
                                $walletXbet = $query
                                        ->orderBy('created_at', 'asc')
                                        ->first();
                        }
                        // Dépôt → dernière transaction
                        else {
                                $walletXbet = $query
                                        ->orderBy('created_at', 'desc')
                                        ->first();
                        }

                        if ($walletXbet) {
                                return response()->json([
                                        'success' => true,
                                        'data' => $walletXbet->choose_type_transaction == 1 ? "Dépôt" : "Retrait",
                                        'num_acompte' => $walletXbet->num_acompte ?? null,
                                        'city_robus' => $walletXbet->city_robus ?? null,
                                        'street_robus' => $walletXbet->street_robus ?? null,
                                ], 200);
                        }

                        return response()->json([
                                'success' => false,
                                'message' => 'Aucun enregistrement trouvé pour ce numéro de Xbet.',
                        ], 404);
                } catch (\Exception $exception) {
                        return response()->json([
                                'success' => false,
                                'message' => 'Une erreur s\'est produite lors de la recherche.',
                        ], 500);
                }
        }
}
