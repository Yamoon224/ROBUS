<?php

namespace App\Http\Controllers\Api\Transfers;


use App\Http\Controllers\Controller;
use App\Http\Services\DeletedService;
use App\Http\Services\NotificationSendService;
use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\ValidateDailyLimitService;
use App\Http\Services\WithService;
use App\Models\Wallets\WalletPush;
use App\Models\Wallets\WalletDjamo;
use App\Models\Wallets\WalletTresortMoney;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Custumers\Country;
use App\Http\Services\ConvertionService;

class withTransactionController extends Controller
{

        protected $deletedService;
        protected $percentRobus;
        protected $validatedamountDays;
        protected $notificationPhone;
        protected $withService;
        protected $convertionservice;

        public function __construct(
                DeletedService $deletedService,
                ConvertionService $convertionservice,
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
                $this->convertionservice = $convertionservice;
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
        public function create_PushTransaction(Request $request, $is_drawal = false)
        {

                $amount_days = $this->validatedamountDays->getCeilingProperty();
                $valeur_ceiling  = auth_user()->silver_ceiling;
                $percent_robus =  $this->percentRobus->getRobusPercentagePush();

                try {


                        $user = auth_user();

                        if ($user && $user->is_under_distributer || $user->is_api) {

                                $validator = Validator::make(

                                        $request->all(),
                                        [
                                                "phone" => 'required',
                                                "provider" => 'required',
                                                "amount" => 'required',
                                                "full_name" => 'required',
                                        ],
                                        [

                                                'phone.required' => 'Votre numero est obligatoire',
                                                'amount.required' => 'Votre montant est obligatoire',
                                                'provider.required' => 'Votre fournisseur est obligatoire',
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
                                                "provider" => 'required',
                                                "amount" => 'required',
                                                "full_name" => 'required',
                                        ],
                                        [

                                                'phone.required' => 'Votre numero est obligatoire',
                                                'amount.required' => 'Votre montant est obligatoire',
                                                'provider.required' => 'Votre fournisseur est obligatoire',
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
                        }


                        if ($request->provider == 91 || $request->provider == 143) {

                                if ($percent_robus != null) {

                                        if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                                                $reel_amount = $this->validatedamountDays->search_amount($request->amount);


                                                if (!$is_drawal) {

                                                        if (!$this->validatedamountDays->validateAmount($reel_amount)) {
                                                                return ['success' => false, 'message' => 'Veuillez verifier votre solde'];
                                                        }
                                                }

                                                if ($user->is_under_distributer || $user->is_api) {
                                                        $amountReceved =  $reel_amount;
                                                } else {

                                                        $percent = $is_drawal
                                                                ? $percent_robus->percent_with_drawal
                                                                : $percent_robus->percent;
                                                        $amountReceved =  (float) $reel_amount -  (float) ($percent * $reel_amount);
                                                }

                                                $transactionResult = $this->processPushTransaction($request, $percent_robus, $amountReceved, $is_drawal);

                                                if ($transactionResult['success']) {

                                                        $this->notificationPhone->sendNotificationWiths($transactionResult['walletWith']);
                                                        return $this->messageAfterDjamoValidated($transactionResult['walletWith']);
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

        public function create_bankSend(Request $request)
        {
                $countryBank = Country::findOrFail(107);
                $user        = auth_user();

                $userRate = robus()->country->currencie->value;
                $bankRate = $countryBank->currencie->value;
                $country_robus = Country::whereId($request->country_id)
                        // ->whereStatus(true)
                        ->first();
                $percentRobus = $this->percentRobus->getRobusPercentageBankSend($request->country_id);

                if (!$percentRobus) {
                        return json_message_response(false, "Pas de commission définie pour ce pays", 422);
                }

                $validator = Validator::make($request->all(), [
                        'amount' => 'required|numeric|min:1',
                        'provider'                => 'required|integer',
                        'phone_out'      => 'required',
                        'country_id'    => 'required',
                        'name_custumer'            => 'required|string',
                        'name_sender'              => 'required|string',
                        'num_piece'       => 'required|string',
                        'date_piece'               => 'required',
                        'account_number'           => 'required|string',
                        'name'                     => 'required|string',
                        'phone_expe'          => 'required|string',
                        'swit_code'                => 'required|string',
                        'image_piece'                    => 'required|image|mimes:jpg,jpeg,png|max:10240',
                ]);

                if ($validator->fails()) {
                        return response()->json([
                                'success' => false,
                                'errors'  => $validator->errors(),
                        ], 422);
                }

                try {
                        /** ===============================
                         *  1️⃣ Montant utilisateur
                         * =============================== */
                        $amountUser = (float) $this->validatedamountDays
                                ->search_amount($request->amount);

                        if ($amountUser <= 0) {
                                return json_message_response(false, "Montant invalide veuillez verifie votre montant", 422);
                        }

                        /** ===============================
                         *  2️⃣ Conversion en CFA (banque)
                         * =============================== */
                        // $amountCfa = $amountUser * ($userRate / $bankRate);
                        $amountCfa    = $this->convertionservice->convertAdminCommission($amountUser);

                        /** ===============================
                         *  3️⃣ Vérification plafond journalier
                         * =============================== */
                        if (!$this->validatedamountDays->validateDailyLimit(
                                $this->validatedamountDays->getCeilingProperty(),
                                $user->silver_ceiling
                        )) {
                                return json_message_response(false, "Plafond journalier atteint", 422);
                        }

                        /** ===============================
                         *  4️⃣ Niveaux de commission
                         * =============================== */


                        $levels = $percentRobus->levelBankAuthers()
                                ->orderByRaw('CAST(amount_begin AS UNSIGNED) ASC')
                                ->get();

                        if ($levels->isEmpty()) {
                                return json_message_response(false, "Configuration commission invalide", 422);
                        }


                        $min = $levels->min(fn($l) => ceil($this->convertionservice->convertAdminCommissionPayment($l->amount_begin)));
                        $max = $levels->max(fn($l) => ceil($this->convertionservice->convertAdminCommissionPayment($l->amount_end)));


                        if ($amountUser < $min || $amountUser > $max) {
                                return json_message_response(
                                        false,
                                        "Montant autorisé entre " .
                                                number_format($min, 0, ',', ' ') . " et " .
                                                number_format($max, 0, ',', ' ') . robus()->country->currencie->code ?? " CFA",
                                        422
                                );
                        }

                        $level = $levels->first(
                                fn($l) =>
                                $amountCfa >= (($l->amount_begin)) &&
                                        $amountCfa <= (($l->amount_end))
                        );


                        if (!$level) {
                                return json_message_response(false, "Aucun niveau de commission trouvé", 422);
                        }

                        /** ===============================
                         *  5️⃣ Calcul commission CFA
                         * =============================== */
                        $isDistributor = $user->is_under_distributer || $user->is_api;

                        $totalDebitCfa = $isDistributor
                                ? (float) $amountCfa
                                : (float) $amountCfa;



                        /** ===============================
                         *  6️⃣ Vérification solde utilisateur
                         * =============================== */
                        $totalDebitUser = $totalDebitCfa * ($bankRate / $userRate);


                        if ($totalDebitUser > robus()->wallet->amount) {
                                return json_message_response(false, "Veuillez verifeir votre solde", 422);
                        }

                        /**ivalents
                        /** ===============================
                         *  7️⃣ Upload image
                         * =============================== */
                        $file_front = $request->file('image_piece');
                        $file_backend = $request->file('image_bakend');
                        $imageBackendPath = null;

                        $imagePath = null;

                        if ($file_front) {
                                // Chemin de destination
                                $destination_path = public_path('bank_sends/' . robus()->id . '/');

                                // Création du dossier si inexistant
                                if (!file_exists($destination_path)) {
                                        mkdir($destination_path, 0777, true);
                                }

                                // Générer un nom unique
                                if ($file_front) {

                                        $front_file = uniqid('front_') . '.' . $file_front->getClientOriginalExtension();
                                        $file_front->move($destination_path, $front_file);

                                        $imagePath = "/bank_sends/" . robus()->id .  "/$front_file";
                                }

                                // 👉 Image verso
                                if ($file_backend) {
                                        $backend_file = uniqid('back_') . '.' . $file_backend->getClientOriginalExtension();
                                        $file_backend->move($destination_path, $backend_file);

                                        $imageBackendPath = "/bank_sends/" . robus()->id .  "/$backend_file";
                                }
                        }

                        /** ===============================
                         *  8️⃣ Création transaction
                         * =============================== */
                        $wallet = $this->withService->createWalletTransactionBanSend(
                                $amountUser,
                                $level,
                                $country_robus,
                                $request->provider,
                                $request->phone_out,
                                $request->name_custumer,
                                $request->num_piece,
                                $request->date_piece,
                                $request->name,
                                $request->name_sender,
                                $request->account_number,
                                $request->phone_expe,
                                $request->swit_code,
                                $imagePath,
                                $imageBackendPath,
                                $request->type_piece,
                        );


                        if (!$wallet) {
                                return json_message_response(false, "Erreur création transaction bank Send", 500);
                        }

                        /** ===============================
                         *  9️⃣ Mise à jour montants reçus
                         * =============================== */

                        $totalAmountReceivedCfa = $isDistributor
                                ? (float) $amountCfa
                                : (float) $amountCfa;

                        $totalAmountRecevedCustumer = (float) $totalAmountReceivedCfa - (float) $level->amount;

                        if ($country_robus->currencie->value > robus()->country->currencie->value) {
                                $totalAmountRecevedUser = $totalAmountRecevedCustumer / robus()->country->currencie->value;
                        } else if ($country_robus->currencie->value == robus()->country->currencie->value) {
                                $totalAmountRecevedUser = $totalAmountRecevedCustumer;
                        } else {
                                $totalAmountRecevedUser = $totalAmountRecevedCustumer * $country_robus->currencie->value;
                        }

                        $this->withService->updateamountReceveidBankSend(
                                $this->validatedamountDays->adminCiv(),
                                $level,
                                $wallet,
                                $amountCfa,
                                $totalAmountRecevedUser
                        );
                        /** ===============================
                         *  🔟 Notifications
                         * =============================== */
                        $this->notificationPhone->sendNotificationBankSend($wallet);

                        return $this->messageAfterBankSendValidated($wallet);
                } catch (\Throwable $e) {
                        report($e);

                        return json_message_response(
                                false,
                                "Erreur inattendue. Veuillez contacter le support ROBUS",
                                500
                        );
                }
        }

        public function create_TresortTransaction(Request $request, $is_drawal = false)
        {

                $amount_days = $this->validatedamountDays->getCeilingProperty();
                $valeur_ceiling  =  auth_user()->silver_ceiling;;
                // valueur User send days
                $percent_robus =  $this->percentRobus->getRobusPercentageTresort();

                try {

                        $user = auth_user();

                        if ($user && $user->is_under_distributer || $user->is_api) {

                                $validator = Validator::make(

                                        $request->all(),
                                        [
                                                "provider" => 'required',
                                                "phone" => 'required',
                                                "amount" => 'required',
                                                "full_name" => 'required',
                                        ],
                                        [

                                                'phone.required' => 'Votre numero est obligatoire',
                                                'provider.required' => 'Votre fournisseur est obligatoire',
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
                                                "provider" => 'required',
                                                "phone" => 'required',
                                                "amount" => 'required',
                                        ],
                                        [

                                                'provider.required' => 'Votre fournisseur est obligatoire',
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


                        if ($request->provider == 122  || $request->provider == 144) {

                                if ($percent_robus != null) {

                                        if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                                                $reel_amount = $this->validatedamountDays->search_amount($request->amount);


                                                if (!$is_drawal) {
                                                        if (!$this->validatedamountDays->validateAmount($reel_amount)) {
                                                                return ['success' => false, 'message' => 'Veuillez verifier votre solde'];
                                                        }
                                                }

                                                if ($user->is_under_distributer || $user->is_api) {
                                                        $amountReceved =  $reel_amount;
                                                } else {

                                                        $percent = $is_drawal
                                                                ? $percent_robus->percent_with_drawal
                                                                : $percent_robus->percent;
                                                        $amountReceved =  (float) $reel_amount -  (float) ($percent * $reel_amount);
                                                }

                                                $transactionResult = $this->processTresortTransaction($request, $percent_robus, $amountReceved, $is_drawal);

                                                if ($transactionResult['success']) {

                                                        $this->notificationPhone->sendNotificationWiths($transactionResult['walletWith']);
                                                        return $this->messageAfterDjamoValidated($transactionResult['walletWith']);
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

        public function create_DjamoTransaction(Request $request, $is_drawal = false)
        {

                $amount_days = $this->validatedamountDays->getCeilingProperty();
                $valeur_ceiling  =  auth_user()->silver_ceiling;
                // valueur User send days
                $percent_robus =  $this->percentRobus->getRobusPercentageDjamo();

                try {

                        $user = auth_user();

                        if ($user && $user->is_under_distributer || $user->is_api) {

                                $validator = Validator::make(

                                        $request->all(),
                                        [
                                                "phone" => 'required',
                                                "amount" => 'required',
                                                "full_name" => 'required',
                                                "image_piece" => 'required',
                                        ],
                                        [

                                                'phone.required' => 'Votre numero est obligatoire',
                                                'provider.required' => 'Votre fournisseur est obligatoire',
                                                'amount.required' => 'Votre montant est obligatoire',
                                                'full_name.required' => 'Nom est obligatoire',
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
                                                "full_name" => 'required',
                                                "image_piece" => 'required',
                                        ],
                                        [

                                                'phone.required' => 'Votre numero est obligatoire',
                                                'provider.required' => 'Votre fournisseur est obligatoire',
                                                'amount.required' => 'Votre montant est obligatoire',
                                                'full_name.required' => 'Nom est obligatoire',
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
                        }


                        if ($request->provider == 121 || $request->provider == 142) {

                                if ($percent_robus != null) {

                                        if ($this->validatedamountDays->validateDailyLimit($amount_days, $valeur_ceiling)) {

                                                $reel_amount = $this->validatedamountDays->search_amount($request->amount);



                                                $file_front = $request->file('image_piece');
                                                $imagePath = null;

                                                if ($file_front) {

                                                        // Chemin de destination
                                                        $destination_path = public_path('qr_djamos/');

                                                        // Création du dossier si inexistant
                                                        if (!file_exists($destination_path)) {
                                                                mkdir($destination_path, 0777, true);
                                                        }

                                                        // Générer un nom unique
                                                        $front_file = date('YmdHis') . '_' . uniqid() . '.' . $file_front->getClientOriginalExtension();

                                                        // Déplacer le fichier
                                                        $file_front->move($destination_path, $front_file);

                                                        // Construire l’URL de l’image
                                                        $imagePath =  "/qr_djamos/" . "/$front_file";
                                                }


                                                $val_amount_send = $reel_amount;


                                                if (!$is_drawal) {
                                                        if (!$this->validatedamountDays->validateAmount($val_amount_send)) {
                                                                return ['success' => false, 'message' => 'Veuillez verifier votre solde'];
                                                        }
                                                }

                                                if ($user->is_under_distributer || $user->is_api) {
                                                        $amountReceved =  $reel_amount;
                                                } else {

                                                        $percent = $is_drawal
                                                                ? $percent_robus->percent_with_drawal
                                                                : $percent_robus->percent;

                                                        $amountReceved =  (float) $reel_amount -  (float) ($percent * $reel_amount);
                                                }


                                                $transactionResult = $this->processDjamoTransaction($request, $percent_robus, $amountReceved,  $imagePath, $is_drawal);

                                                if ($transactionResult['success']) {

                                                        $this->notificationPhone->sendNotificationWiths($transactionResult['walletWith']);
                                                        return $this->messageAfterDjamoValidated($transactionResult['walletWith']);
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
        private function processPushTransaction($request, $percent_robus, $amountReceved, $is_drawal)
        {
                $reel_amount = $this->validatedamountDays->search_amount($request->amount);

                $user = auth_user();

                if (!$user || !$user->wallet) {
                        return [
                                'success' => false,
                                'message' => 'Utilisateur ou wallet non disponible.'
                        ];
                }


                $walletPush =  $this->withService->createWalletTransactionPush($reel_amount, $request->provider, $percent_robus, $request->phone,  $request->full_name, $amountReceved, $is_drawal, $request->type, $request->numberPiece);
                if ($walletPush == null) {
                        return ['success' => false, 'message' => 'Erreur de création de transfert du montant!'];
                }

                if (!$this->withService->updateamountPushReceveid($this->validatedamountDays->adminCiv(), $percent_robus, $walletPush, $request->amount, $is_drawal)) {
                        return ['success' => false, 'message' => 'Erreur lors de la mise à jour du montant reçu!'];
                }

                return ['success' => true, 'walletWith' => $walletPush];
        }


        private function processTresortTransaction($request, $percent_robus, $amountReceved, $is_drawal)
        {
                $reel_amount = $this->validatedamountDays->search_amount($request->amount);


                $walletPush =  $this->withService->createWalletTransactionTresortMoney($reel_amount, $request->provider, $percent_robus, $request->phone,  $request->full_name, $amountReceved, $is_drawal, $request->type, $request->numberPiece);
                if ($walletPush == null) {
                        return ['success' => false, 'message' => 'Erreur de création de transfert du montant!'];
                }

                $this->withService->updateamountTresortReceveid($this->validatedamountDays->adminCiv(), $percent_robus, $walletPush, $request->amount, $is_drawal);

                return ['success' => true, 'walletWith' => $walletPush];
        }

        private function processDjamoTransaction($request, $percent_robus, $amountReceved, $imagePath, $is_drawal)
        {

                $reel_amount = $this->validatedamountDays->search_amount($request->amount);


                $walletPush =  $this->withService->createWalletTransactionDjamo($reel_amount, $request->provider, $percent_robus, $request->phone,  $request->full_name, $amountReceved, $imagePath, $is_drawal, $request->type, $request->numberPiece);

                if ($walletPush == null) {
                        return ['success' => false, 'message' => 'Erreur de création de transfert du montant!'];
                }

                if (!$this->withService->updateamountDjamoReceveid($this->validatedamountDays->adminCiv(), $percent_robus, $walletPush, $request->amount, $is_drawal)) {
                        return ['success' => false, 'message' => 'Erreur lors de la mise à jour du montant reçu!'];
                }

                return ['success' => true, 'walletWith' => $walletPush];
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
                        'message' =>  $walletPush->choose_type_transaction == 1 ? ('Votre demande de transaction a bien été prise en compte.') : ('Votre demande de retrait a bien été prise en compte.')
                ], 200);
        }

        public function messageAfterDjamoValidated($walletPush)
        {

                return response()->json([
                        'success' => true,
                        'data' => [
                                'id'      => $walletPush->id,
                                'provider' => $walletPush->provider->name,
                                'amount' => $walletPush->amount,
                                'status' => $walletPush->status,
                        ],
                        'message' =>  $walletPush->choose_transaction == 1 ? ('Votre demande de transaction a bien été prise en compte.') : ('Votre demande de retrait a bien été prise en compte.')
                ], 200);
        }

        public function messageAfterBankSendValidated($walletPush)
        {

                return response()->json([
                        'success' => true,
                        'data' => [
                                'id'      => $walletPush->id,
                                'provider' => $walletPush->provider->name,
                                'amount' => $walletPush->amount,
                                'status' => $walletPush->status,
                        ],
                        'message' =>  $walletPush->choose_type_transaction == 1 ? ('Votre demande de transaction bank Send a bien été prise en compte.') : ('Votre demande de transaction bank Send a bien été prise en compte.')
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

        public function delete_wallet_Push(Request $request)
        {

                try {

                        $walletPush = WalletPush::find($request->wallet_xbet);

                        if ($this->deletedService->deletedTransaction($walletPush)) {
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


        public function delete_wallet_Djamo(Request $request)
        {

                try {

                        $walletWith = WalletDjamo::find($request->wallet_xbet);

                        if ($this->deletedService->deletedTransaction($walletWith)) {
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

        public function delete_wallet_Tresort(Request $request)
        {

                try {

                        $walletWith = WalletTresortMoney::find($request->wallet_xbet);

                        if ($this->deletedService->deletedTransaction($walletWith)) {
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

        public function search_drawal_mooney($amount_paiement, $providerId)
        {

                $wallet_user = robus();

                try {

                        // Invoice auther
                        if ($amount_paiement != '') {

                                $reel_amount = $this->validatedamountDays->search_amount($amount_paiement);

                                $commission_invoice =  $this->percentRobus->getRobusPercentageDrawal($providerId);

                                if ($wallet_user->is_under_distributer == 1 || $wallet_user->is_api) {

                                        $commission_paiement =  $commission_invoice->percent  * $amount_paiement;
                                } else {

                                        $commission_paiement =  $commission_invoice->percent_custum_drawal  * $amount_paiement;
                                }


                                if ($wallet_user->country->code == 'CI') {

                                        if ($wallet_user->is_under_distributer == 1 || $wallet_user->is_api) {

                                                $commission_paiement_invoice_val_nat_pay =  $commission_invoice->percent_with_drawal  * $amount_paiement;

                                                $commission_paiement_invoice_val_nat_receive =  (float) $reel_amount;

                                                $commission_transaction_val_percent_pay =  (float) $commission_paiement;
                                        } else {

                                                $commission_paiement_invoice_val_nat_pay =   $commission_paiement;

                                                $commission_paiement_invoice_val_nat_receive =  (float) $reel_amount - (float) $commission_paiement;

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


        public function search_Djamo($amount_send, $is_drawal = false)
        {
                $is_drawal = filter_var($is_drawal, FILTER_VALIDATE_BOOLEAN);

                return $this->searchWallet('djamo', $amount_send, $is_drawal);
        }

        public function search_Push($amount_send, $is_drawal = false)
        {
                $is_drawal = filter_var($is_drawal, FILTER_VALIDATE_BOOLEAN);

                return $this->searchWallet('push', $amount_send, $is_drawal);
        }

        public function search_bank_send(float $amount_send, int $countryId)
        {
                // Pays banque (CFA)
                $bankCountry = Country::find(107);
                $bankCountryReceived = Country::find($countryId);

                if (!$amount_send || $amount_send <= 0) {
                        return response()->json([
                                'success' => false,
                                'message' => 'Montant invalide.',
                        ], 400);
                }

                // Récupération du pourcentage Robus
                $commissionBank = $this->percentRobus->getRobusPercentageBankSend($countryId);

                if (!$commissionBank) {
                        return response()->json([
                                'success' => false,
                                'message' => 'Pas de rechargement bancaire pour ce pays.',
                        ], 200);
                }

                // Valeurs de devises
                $userCurrencyValue   = robus()->country->currencie->value;
                $bankCurrencyValue   = $bankCountry->currencie->value;

                if ($bankCurrencyValue <= 0) {
                        return response()->json([
                                'success' => false,
                                'message' => 'Devise bancaire invalide.',
                        ], 500);
                }

                /**
                 * 🔁 Conversion utilisateur → CFA
                 */
                $amountCfa    = $this->convertionservice->convertAdminCommission($amount_send);

                $levels = $commissionBank->levelBankAuthers()
                        ->orderByRaw('CAST(amount_begin AS UNSIGNED) ASC')
                        ->get();



                if ($levels->isEmpty()) {
                        return response()->json([
                                'success' => false,
                                'message' => 'Aucune tranche de commission disponible.',
                        ], 200);
                }

                $minAmount = $levels->min(fn($l) => ceil($this->convertionservice->convertAdminCommissionPayment($l->amount_begin)));
                $maxAmount = $levels->max(fn($l) => ceil($this->convertionservice->convertAdminCommissionPayment($l->amount_end)));
                $maverify = $this->convertionservice->convertAdminCommissionPayment($amountCfa);

                if ($minAmount !== null && $maverify < $minAmount) {
                        return response()->json([
                                'success' => false,
                                'message' => 'Montant minimum : ' . number_format($minAmount, 2, ',', ' ') . robus()->country->currencie->code ?? "CFA",
                        ], 200);
                }

                if ($maxAmount !== null && $maverify > $maxAmount) {
                        return response()->json([
                                'success' => false,
                                'message' => 'Montant maximum : ' . number_format($maxAmount, 2, ',', ' ') . robus()->country->currencie->code ?? "CFA",
                        ], 200);
                }

                $isDistributor = robus()->is_under_distributer || robus()->is_distributer;


                function calculateConversion($valeur_verify, $currencie_sender, $currencie_receiver)
                {
                        $conversion_cfa = (float)$valeur_verify * (float)$currencie_sender->value;
                        return $conversion_cfa / (float)$currencie_receiver->value;
                }


                foreach ($levels as $level) {

                        if ($amountCfa >= $level->amount_begin && $amountCfa <= $level->amount_end) {

                                /**
                                 * 💰 Commission en CFA
                                 */
                                $commissionCfa = $isDistributor
                                        ? (float) $level->percent_under
                                        : (float) $level->amount;

                                $commissionCfaUnder =  (float) $level->amount;
                                /**
                                 * 🔁 Conversion CFA → devise utilisateur
                                 */
                                $commissionUser =  $this->convertionservice->convertAdminCommissionPayment($commissionCfa);
                                $commissionUnder = $this->convertionservice->convertAdminCommissionPayment($commissionCfaUnder);

                                $amountUser     =  $isDistributor ? (float) $commissionUnder : $amountCfa * ($bankCurrencyValue / $userCurrencyValue);

                                $amountUnderReceived     =  $amountCfa * ($bankCurrencyValue / $userCurrencyValue);
                                $amountRecevedUser     =  $isDistributor ? ((float)  $amountUnderReceived  - (float) $commissionUnder) : ((float)  $amountUser  - (float)  $commissionUser);


                                if ($bankCountryReceived->currencie->value > robus()->country->currencie->value) {
                                        $totalAmountRecevedUser = calculateConversion($amountRecevedUser, robus()->country->currencie, $bankCountryReceived->currencie);
                                } else if ($bankCountryReceived->currencie->value == robus()->country->currencie->value) {
                                        $totalAmountRecevedUser = $amountRecevedUser;
                                } else {
                                        $totalAmountRecevedUser = calculateConversion($amountRecevedUser, robus()->country->currencie, $bankCountryReceived->currencie);
                                }

                                return response()->json([
                                        'success' => true,
                                        'data' => [
                                                'amount_send'    => $amountUser,
                                                'amount_pay'     => $commissionUser,
                                                'amount_receive' => $totalAmountRecevedUser . ' ' . $bankCountryReceived->currencie->code ?? "CFA",
                                        ],
                                ], 200);
                        }
                }

                return response()->json([
                        'success' => false,
                        'message' => 'Aucune tranche correspondante.',
                ], 200);
        }


        public function search_Tresort($amount_send, $is_drawal = false)
        {
                $is_drawal = filter_var($is_drawal, FILTER_VALIDATE_BOOLEAN);

                return $this->searchWallet('tresort', $amount_send, $is_drawal);
        }

        // search received

        public function search_received_Djamo($amount_send)
        {
                return $this->searchAmountReceivedWallet('djamo', $amount_send);
        }

        public function search_received_Push($amount_send)
        {
                return $this->searchAmountReceivedWallet('push', $amount_send);
        }

        public function search_receveid_Tresort($amount_send)
        {
                return $this->searchAmountReceivedWallet('tresort', $amount_send);
        }


        private function searchWallet(string $type, $amount, $is_drawal = false)
        {
                try {
                        // Choisir la bonne méthode pour récupérer le pourcentage
                        $percent_robus = match ($type) {
                                'djamo' => $this->percentRobus->getRobusPercentageDjamo(),
                                'push'  => $this->percentRobus->getRobusPercentagePush(),
                                'tresort'  => $this->percentRobus->getRobusPercentageTresort(),
                                default => null,
                        };

                        if ($percent_robus === null) {
                                return response()->json([
                                        'success' => false,
                                        'message' => 'Pas de rechargement dans cette zone...',
                                ], 200);
                        }

                        if (empty($amount)) {
                                return response()->json([
                                        'success' => false,
                                        'message' => 'Montant non spécifié...',
                                ], 400);
                        }

                        $reel_amount = $this->validatedamountDays->search_amount($amount);

                        if ($is_drawal) {

                                if (robus()->is_under_distributer || robus()->is_api) {
                                        $commission = $percent_robus->percent_with_drawal_merchands * $reel_amount;
                                } else {
                                        $commission = $percent_robus->percent_with_drawal * $reel_amount;
                                }
                        } else {

                                if (robus()->is_under_distributer || robus()->is_api) {
                                        $commission = $percent_robus->percent_merchands * $reel_amount;
                                } else {
                                        $commission = $percent_robus->percent * $reel_amount;
                                }
                        }

                        $net_amount = (robus()->is_under_distributer || robus()->is_api) ? $reel_amount : $reel_amount - $commission;

                        return response()->json([
                                'success' => true,
                                'data' => [
                                        'amount_send'    => $reel_amount,
                                        'amount_pay'     => $commission,
                                        'amount_receive' => $net_amount,
                                ],
                        ], 200);
                } catch (\Exception $exception) {
                        return response()->json([
                                'success' => false,
                                'message' => 'Une erreur s\'est produite lors de la recherche.',
                        ], 500);
                }
        }


        private function searchAmountReceivedWallet(string $type, $amount)
        {
                try {
                        // Choisir la bonne méthode pour récupérer le pourcentage
                        $percent_robus = match ($type) {
                                'djamo' => $this->percentRobus->getRobusPercentageDjamo(),
                                'push'  => $this->percentRobus->getRobusPercentagePush(),
                                'tresort'  => $this->percentRobus->getRobusPercentageTresort(),
                                default => null,
                        };

                        if ($percent_robus === null) {
                                return response()->json([
                                        'success' => false,
                                        'message' => 'Pas de rechargement dans cette zone...',
                                ], 200);
                        }

                        if (empty($amount)) {
                                return response()->json([
                                        'success' => false,
                                        'message' => 'Montant non spécifié...',
                                ], 400);
                        }

                        // Validation et calcul
                        $reel_amount = $this->validatedamountDays->search_amount($amount);
                        $commission = $percent_robus->percent * $reel_amount;
                        $net_amount = $reel_amount + $commission;

                        return response()->json([
                                'success' => true,
                                'data' => [
                                        'amount_send'    => $reel_amount,
                                        'amount_pay'     => $commission,
                                        'amount_receive' => $net_amount,
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
