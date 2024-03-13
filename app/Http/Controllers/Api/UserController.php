<?php

namespace App\Http\Controllers\Api;

use App\Events\AdminNotifEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\InterTransactionSingleCollection;
use App\Http\Resources\PaymentSingleCollection;
use App\Http\Resources\PhoneCreditSingleCollection;
use App\Http\Resources\ReloadCardSingle;
use App\Http\Resources\ReloadCartCollection;
use App\Http\Resources\WaveSingleCollection;
use App\Http\Resources\OneXbetSingleCollection;
use App\Http\Resources\ReloadSingleCollection;
use App\Http\Resources\RemonteSingleCollection;
use App\Http\Resources\TransactionSingleCollection;
use App\Http\Resources\UnderCollection;
use App\Http\Resources\UserSingleCollection;
use App\Http\Resources\VirementSingleCollection;
use App\Http\Resources\WalletSingleCollection;
use App\Http\Services\AuthenticationService;
use App\Http\Services\RobusTransactionService;
use App\Http\Services\TransactionsAccountService;
use App\Http\Services\ValidateDailyLimitService;
use App\Mail\NewAdmin;
use App\Mail\UvSend;
use App\Models\AppComZoneStatus;
use App\Models\AppMasterStatus;
use App\Models\AppStatus;
use App\Models\Banks\BankCard;
use App\Models\Kyc;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletTransaction;
use App\Models\Wallets\WalletWave;
use App\Models\Wallets\WalletXbet;
use App\Models\WithMerchant;
use App\Notifications\AdminNotification;
use App\Notifications\UserNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{


    /** @var  TransactionRepository */
    protected $transactionAccount;
    protected $validatedamountDays;
    protected $robusTransactionService;
    protected $authService;

    public function __construct(
        TransactionsAccountService $transactionAccount,
        ValidateDailyLimitService $validatedamountDays,
        RobusTransactionService $robusTransactionService,
        AuthenticationService $authService

    ) {
        $this->transactionAccount = $transactionAccount;
        $this->validatedamountDays = $validatedamountDays;
        $this->robusTransactionService = $robusTransactionService;
        $this->authService = $authService;
    }

    /**
     * @OA\GET(
     *      path="/user/dashboard",
     *      tags={"Dashbord"},
     *      summary="DashBord user",
     *      description="Tableau de bord du clients",
     * @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="Wallet_id", type="string", example="CIERDSD5"),
     *              @OA\Property(property="amount", type="float", example="0"),
     *              @OA\Property(property="status", type="boolean", example="true"),
     *              @OA\Property(property="info_user", type="string", example="Fisrtname , lastname , phone"),
     *              @OA\Property(property="created_at", type="string", example="2022-08-11T09:25:53.000000Z")
     *          )
     *     ),
     *   @OA\Response(
     *          response=333,
     *          description="Code does not match."
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
    public function dashboard()
    {

        // Vérifier l'état de l'application
        $appStatusResult = $this->authService->appDispoRobus(AppStatus::class);

        if (!$appStatusResult['success']) {
            return response()->json($appStatusResult, 200);
        }


        $wallet_solde = Wallet::where('sid_user_id', robus()->id)->first();
        $dashboard_data['wallet_user'] = new WalletSingleCollection($wallet_solde);

        $walletTransaction = $this->transactionAccount->getUserTransactions(WalletTransaction::class, $wallet_solde, 'num_wallet');

        $walletPayment = $this->transactionAccount->getUserAllTransactions(WalletPayment::class, $wallet_solde);

        $walletBank = $this->transactionAccount->getUserAllTransactions(WalletBank::class, $wallet_solde);

        $walletPhone = $this->transactionAccount->getUserAllTransactions(WalletPhoneCredit::class, $wallet_solde);

        $walletReload = $this->transactionAccount->getUserTransactions(WalletReload::class, $wallet_solde, 'account_wallet');

        $walletReloadCard = $this->transactionAccount->getUserAllTransactions(WalletDeposit::class, $wallet_solde);

        $walletInter = $this->transactionAccount->getUserTransactions(WalletInter::class, $wallet_solde, 'number_compte');

        $walletOrderCarte = $this->transactionAccount->getUserAllTransactions(BankCard::class, $wallet_solde);

        $walletRemonte = $this->transactionAccount->getUserRemonte(WithMerchant::class, $wallet_solde);

        $wallet1xbets = $this->transactionAccount->getUserAllTransactions(WalletXbet::class, $wallet_solde);

        $walletWave = $this->transactionAccount->getUserAllTransactions(WalletWave::class, $wallet_solde);


        $som_commissions = $this->transactionAccount->getWalletSumGainUser(WalletTransaction::class) +  $this->transactionAccount->getWalletSumGainUser(WalletPayment::class) +
            $this->transactionAccount->getWalletSumGainUser(WalletBank::class) +  $this->transactionAccount->getWalletSumGainUser(WalletPhoneCredit::class)
            + $this->transactionAccount->getWalletSumGainUser(WalletDeposit::class) + $this->transactionAccount->getWalletSumGainUser(BankCard::class) +
            $this->transactionAccount->getWalletSumGainUser(WalletInter::class) +
            $this->transactionAccount->getWalletSumGainUser(WalletXbet::class) +
            $this->transactionAccount->getWalletSumGainUser(WalletWave::class);

        $dashboard_data['Transactions'] = TransactionSingleCollection::collection($walletTransaction);
        $dashboard_data['Commission remonté'] = RemonteSingleCollection::collection($walletRemonte);
        $dashboard_data['Paiements'] = PaymentSingleCollection::collection($walletPayment);
        $dashboard_data['Versements'] = VirementSingleCollection::collection($walletBank);
        $dashboard_data['Credit telephoniques'] = PhoneCreditSingleCollection::collection($walletPhone);
        $dashboard_data['Rechargement de carte'] = ReloadCardSingle::collection($walletReloadCard);
        $dashboard_data['Rechargement d\'UV'] = ReloadSingleCollection::collection($walletReload);
        $dashboard_data['Commande de carte'] = ReloadCartCollection::collection($walletOrderCarte);
        $dashboard_data['1xbet'] = OneXbetSingleCollection::collection($wallet1xbets);
        $dashboard_data['Achat UV wave'] = WaveSingleCollection::collection($walletWave);
        $dashboard_data['Transfers inter'] = InterTransactionSingleCollection::collection($walletInter);
        $is_under = robus()->is_under_distributer;
        return response()->json([
            'success' => true,
            'historicals' =>  $dashboard_data,
            'som_commission' => $is_under ? $som_commissions . ' ' . robus()->wallet->currencie->code  : " Vous devez etre un Sous distributeur pour avoir accees a son dashboard ",
        ], 200);
    }

    /**
     * @CrossOrigin
     */
    public function dashboard_masters()
    {


        // Vérifier l'état de l'application
        $appStatusResult = $this->authService->appDispoMasterRobus(AppMasterStatus::class);

        if (!$appStatusResult['success']) {

            return response()->json($appStatusResult, 200);
        }


        $wallet_solde = Wallet::where('sid_user_id', robus()->id)->first();

        $dashboard_data['wallet_user'] = new WalletSingleCollection($wallet_solde);

        $walletTransaction = $this->transactionAccount->getUserTransactions(WalletTransaction::class, $wallet_solde, 'num_wallet');

        $walletReload = $this->transactionAccount->getUserTransactions(WalletReload::class, $wallet_solde, 'account_wallet');

        $walletRemonte = $this->transactionAccount->getUserRemonte(WithMerchant::class, $wallet_solde);

        $som_commissions = $this->transactionAccount->getTodayTransactionMasterAmountTotal(WalletTransaction::class) +  $this->transactionAccount->getTodayTransactionMasterAmountTotal(WalletPayment::class) +
            $this->transactionAccount->getTodayTransactionMasterAmountTotal(WalletBank::class) +  $this->transactionAccount->getTodayTransactionMasterAmountTotal(WalletPhoneCredit::class)
            + $this->transactionAccount->getTodayTransactionMasterAmountTotal(WalletDeposit::class) + $this->transactionAccount->getTodayTransactionMasterAmountTotal(BankCard::class) +
            $this->transactionAccount->getTodayTransactionMasterAmountTotal(WalletInter::class) +
            $this->transactionAccount->getTodayTransactionMasterAmountTotal(WalletXbet::class) +
            $this->transactionAccount->getTodayTransactionMasterAmountTotal(WalletWave::class);

        $dashboard_data['Réchargements'] = ReloadSingleCollection::collection($walletReload);
        $dashboard_data['Déchargements'] = TransactionSingleCollection::collection($walletTransaction);
        $dashboard_data['Commission remonté'] = RemonteSingleCollection::collection($walletRemonte);

        $is_master = robus()->is_master;

        return response()->json([
            'success' => $is_master ? true : false,
            'historicals' =>  $is_master ?  $dashboard_data : "Vous devez etre un MASTER pour avoir accees a son dashboard ",
            'som_commission' =>  $is_master ?   $som_commissions . ' ' . robus()->wallet->currencie->code : "Vous devez etre un MASTER pour avoir accees a son dashboard ",
        ], 200);
    }

    public function dashboard_comzones()
    {
        // Vérifier l'état de l'application
        $appStatusResult = $this->authService->appDispoComZoneRobus(AppComZoneStatus::class);

        if (!$appStatusResult['success']) {
            return response()->json($appStatusResult, 200);
        }

        $under_distributors =    $this->transactionAccount->getComZonesUser();

        return response()->json([
            'success' => true,
            'user_alls' => robus()->is_com_zone ? new UnderCollection($under_distributors) : "Vous devez être un COM ZONE",
        ]);
    }
    /**
     * @OA\GET(
     *      path="/user/info",
     *      tags={"Dashbord"},
     *      summary="Info User",
     *      description="Les informatiosn sur le client",
     * @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="Wallet_id", type="string", example="CIERDSD5"),
     *              @OA\Property(property="amount", type="float", example="0"),
     *              @OA\Property(property="status", type="boolean", example="true"),
     *              @OA\Property(property="Wallets", type="password", example="password"),
     *              @OA\Property(property="created_at", type="string", example="2022-08-11T09:25:53.000000Z")
     *          )
     *     ),
     *  @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="Wallet",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="amount",
     *                          type="float"
     *                      ),
     *                      @OA\Property(
     *                          property="Status",
     *                          type="boolean"
     *                      ),
     *                    @OA\Property(
     *                          property="Wallets",
     *                          type="float"
     *                      ),
     *                 ),
     *                 example={
     *                     "Wallet":"CIERDSD5",
     *                     "Amount":"0",
     *                     "Status": "true",
     *                     "Wallets": "Differentes transactions of user",
     *                }
     *             )
     *         )
     *      ),
     *   @OA\Response(
     *          response=333,
     *          description="Code does not match."
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
    public function info()
    {
        $user = SidUser::find(robus()->id);

        return response()->json([
            'success' => true,
            'user' => new UserSingleCollection($user)
        ]);
    }

    public function info_masters()
    {
        $user = SidUser::find(robus()->id);

        return response()->json([
            'success' => true,
            'user' => new UserSingleCollection($user)
        ]);
    }

    /**
     * @OA\POST(
     *      path="/user/info/update",
     *      tags={"Dashbord"},
     *      summary="Update Info user",
     *      description="Mettre a jour les informations du client",
     * @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="firstname", type="string", example="Sabdy"),
     *              @OA\Property(property="lastname", type="string", example="devops"),
     *              @OA\Property(property="phone", type="number", example="0506469637"),
     *              @OA\Property(property="email", type="string", example="sabdydev@gmail.com"),
     *               @OA\Property(property="image", type="string", example="src/demane/demo/saby.jpeg"),
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
     *                          property="Firstname",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="Lastname",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="Phone",
     *                          type="number"
     *                      ),
     *                    @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={
     *                     "Firstname":"Sabdy",
     *                     "Lastname":"Devops",
     *                     "Phone": "050646963728",
     *                     "email": "sabdydev@gmail.com",
     *                     "image": "src/demane/demo/saby.jpeg",
     *                }
     *             )
     *         )
     *      ),
     *   @OA\Response(
     *          response=333,
     *          description="Code does not match."
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
    public function updateInfo(Request $request)
    {

        $user = SidUser::find(robus()->id);

        $file_front = $request->file('image');

        if ($file_front) {

            $destination_path = public_path('profil_images/' . Auth::user()->firstname . '/');
            $front_file = date('YmdHis') . "." . $file_front->getClientOriginalExtension();
            $file_front->move($destination_path, $front_file);

            $user->update([
                'image' => env('APP_URL') . "/public/profil_images/" . Auth::user()->firstname . "/$front_file",
            ]);
        }


        // ancient countries    

        $user->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
        ]);

        if (!empty($user->forget_password)) {
            $ancient_password = $request->currency_password;

            if ($ancient_password == $user->forget_password) {
                $user->update([
                    'forget_password' => null,
                    'password' => Hash::make($request->password),
                ]);
            }
        } else {
            $ancient_password = $request->currency_password;

            if ($ancient_password == $user->password) {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Les informations de profil ont été mises à jour avec succès',
            'user' => new UserSingleCollection($user)
        ], 200);
    }

    public function update_masters(Request $request)
    {

        $user = SidUser::find(robus()->id);

        $file_front = $request->file('image');

        if ($file_front) {

            $destination_path = public_path('profil_images/' . Auth::user()->firstname . '/');
            $front_file = date('YmdHis') . "." . $file_front->getClientOriginalExtension();
            $file_front->move($destination_path, $front_file);

            $user->update([
                'image' => env('APP_URL') . "/public/profil_images/" . Auth::user()->firstname . "/$front_file",
            ]);
        }


        // ancient countries    

        $user->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
        ]);

        if (!empty($user->forget_password)) {
            $ancient_password = $request->currency_password;

            if ($ancient_password == $user->forget_password) {
                $user->update([
                    'forget_password' => null,
                    'password' => Hash::make($request->password),
                ]);
            }
        } else {
            $ancient_password = $request->currency_password;

            if ($ancient_password == $user->password) {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Les informations de profil ont été mises à jour avec succès',
            'user' => new UserSingleCollection($user)
        ], 200);
    }

    /**
     * @OA\POST(
     *      path="/user/info/active_compte",
     *      tags={"Dashbord"},
     *      summary="Active your compte",
     *      description="Charger les documents pour la verification du compte",
     * @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="front", type="file" ,example="src/demane/demo/saby.jpeg"),
     *              @OA\Property(property="back", type="file", example="src/demane/demo/saby.jpeg"),
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
     *                          property="Image front",
     *                          type="file"
     *                      ),
     *                      @OA\Property(
     *                          property="Image Back",
     *                          type="file"
     *                      ),
     *                 ),
     *                 example={
     *                     "front": "src/sabdy/front_saby.jpeg",
     *                     "back": "src/sabdy/back_saby.jpeg",
     *                }
     *             )
     *         )
     *      ),
     *   @OA\Response(
     *          response=333,
     *          description="Code does not match."
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
    public function updload_identity(Request $request)
    {
        $user = SidUser::find(robus()->id);

        //  Image docs
        $file_image = $request->file('selfie');
        //  Front docs
        $file_front = $request->file('front');
        // Back Image
        $file_back = $request->file('back');

        if (isset($file_image) && !empty($file_image) && isset($file_front) && !empty($file_front) && isset($file_back) && !empty($file_back)) {

            $destination_path = public_path('validated_accounts/' . Auth::user()->firstname . '/');
            $front_file = 'selfie_' . date('YmdHis') . "." . $file_image->getClientOriginalExtension();
            $front_front = 'face_' . date('YmdHis') . "." . $file_front->getClientOriginalExtension();
            $back_file = 'arriere_' . date('YmdHis') . "." . $file_back->getClientOriginalExtension();
            $file_back->move($destination_path, $back_file);
            $file_front->move($destination_path, $front_front);
            $file_image->move($destination_path, $front_file);
            Kyc::updateOrCreate(
                [
                    'sid_user_id' => $user->id,
                    'company_id'  =>  $user->company->id
                ],
                [
                    'motif' => 'Validation de compte',
                    'image' => env('APP_URL') . "/public/validated_accounts/" . Auth::user()->firstname . "/$front_file",
                    'screen' => env('APP_URL') . "/public/validated_accounts/" . Auth::user()->firstname . "/$front_front",
                    'screen_back' => env('APP_URL') . "/public/validated_accounts/" . Auth::user()->firstname . "/$back_file",
                    'sid_user_id' => $user->id,
                    'status' => false,
                    'company_id'  =>  $user->company->id
                ]
            );
        } else if (isset($file_front) && !empty($file_front) && isset($file_back) && !empty($file_back) && empty($file_image)) {
            $destination_path = public_path('validated_accounts/' . Auth::user()->firstname . '/');
            $front_file = 'face_' . date('YmdHis') . "." . $file_front->getClientOriginalExtension();
            $back_file = 'arriere_' . date('YmdHis') . "." . $file_back->getClientOriginalExtension();
            $file_back->move($destination_path, $back_file);
            $file_front->move($destination_path, $front_file);
            Kyc::updateOrCreate(
                [
                    'sid_user_id' => $user->id,
                    'company_id'  =>  $user->company->id
                ],
                [
                    'motif' => 'Validation de compte',
                    'screen' => env('APP_URL') . "/public/validated_accounts/" . Auth::user()->firstname . "/$front_file",
                    'screen_back' => env('APP_URL') . "/public/validated_accounts/" . Auth::user()->firstname . "/$back_file",
                    'sid_user_id' => $user->id,
                    'status' => false,
                    'company_id'  =>  $user->company->id
                ]
            );
        } else {
            $destination_path = public_path('validated_accounts/' . Auth::user()->firstname . '/');
            $front_file = 'selfie_' . date('YmdHis') . "." . $file_image->getClientOriginalExtension();
            $file_image->move($destination_path, $front_file);
            Kyc::updateOrCreate(
                [
                    'sid_user_id' => $user->id,
                    'company_id'  =>  $user->company->id
                ],
                [
                    'motif' => 'Validation de compte',
                    'image' => env('APP_URL') . "/public/validated_accounts/" . Auth::user()->firstname . "/$front_file",
                    'sid_user_id' => $user->id,
                    'status' => false,
                    'company_id'  =>  $user->company->id
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Les informations de profil ont été mises à jour avec succès',
            'user' => new UserSingleCollection($user)
        ], 200);
    }

    public function updload_identity_masters(Request $request)
    {
        $user = SidUser::find(robus()->id);

        //  Image docs
        $file_image = $request->file('selfie');
        //  Front docs
        $file_front = $request->file('front');
        // Back Image
        $file_back = $request->file('back');

        if (isset($file_image) && !empty($file_image) && isset($file_front) && !empty($file_front) && isset($file_back) && !empty($file_back)) {

            $destination_path = public_path('validated_accounts/' . Auth::user()->firstname . '/');
            $front_file = 'selfie_' . date('YmdHis') . "." . $file_image->getClientOriginalExtension();
            $front_front = 'face_' . date('YmdHis') . "." . $file_front->getClientOriginalExtension();
            $back_file = 'arriere_' . date('YmdHis') . "." . $file_back->getClientOriginalExtension();
            $file_back->move($destination_path, $back_file);
            $file_front->move($destination_path, $front_front);
            $file_image->move($destination_path, $front_file);
            Kyc::updateOrCreate(
                [
                    'sid_user_id' => $user->id,
                    'company_id'  =>  $user->company->id
                ],
                [
                    'motif' => 'Validation de compte',
                    'image' => env('APP_URL') . "/public/validated_accounts/" . Auth::user()->firstname . "/$front_file",
                    'screen' => env('APP_URL') . "/public/validated_accounts/" . Auth::user()->firstname . "/$front_front",
                    'screen_back' => env('APP_URL') . "/public/validated_accounts/" . Auth::user()->firstname . "/$back_file",
                    'sid_user_id' => $user->id,
                    'status' => false,
                    'company_id'  =>  $user->company->id
                ]
            );
        } else if (isset($file_front) && !empty($file_front) && isset($file_back) && !empty($file_back) && empty($file_image)) {
            $destination_path = public_path('validated_accounts/' . Auth::user()->firstname . '/');
            $front_file = 'face_' . date('YmdHis') . "." . $file_front->getClientOriginalExtension();
            $back_file = 'arriere_' . date('YmdHis') . "." . $file_back->getClientOriginalExtension();
            $file_back->move($destination_path, $back_file);
            $file_front->move($destination_path, $front_file);
            Kyc::updateOrCreate(
                [
                    'sid_user_id' => $user->id,
                    'company_id'  =>  $user->company->id
                ],
                [
                    'motif' => 'Validation de compte',
                    'screen' => env('APP_URL') . "/public/validated_accounts/" . Auth::user()->firstname . "/$front_file",
                    'screen_back' => env('APP_URL') . "/public/validated_accounts/" . Auth::user()->firstname . "/$back_file",
                    'sid_user_id' => $user->id,
                    'status' => false,
                    'company_id'  =>  $user->company->id
                ]
            );
        } else {
            $destination_path = public_path('validated_accounts/' . Auth::user()->firstname . '/');
            $front_file = 'selfie_' . date('YmdHis') . "." . $file_image->getClientOriginalExtension();
            $file_image->move($destination_path, $front_file);
            Kyc::updateOrCreate(
                [
                    'sid_user_id' => $user->id,
                    'company_id'  =>  $user->company->id
                ],
                [
                    'motif' => 'Validation de compte',
                    'image' => env('APP_URL') . "/public/validated_accounts/" . Auth::user()->firstname . "/$front_file",
                    'sid_user_id' => $user->id,
                    'status' => false,
                    'company_id'  =>  $user->company->id
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Les informations de profil ont été mises à jour avec succès',
            'user' => new UserSingleCollection($user)
        ], 200);
    }

    // View liste of commission User
    public function kyc()
    {
        $user = SidUser::find(robus()->id);

        $kyc = Kyc::where('sid_user_id', $user->id)->first();

        return response()->json([
            'success' => true,
            'user' => $kyc
        ]);
    }

    public function kyc_masters()
    {
        $user = SidUser::find(robus()->id);

        $kyc = Kyc::where('sid_user_id', $user->id)->first();

        return response()->json([
            'success' => true,
            'user' => $kyc
        ]);
    }

    // ACOUNT Transaction Historique Distributer
    public function historical_distributer()
    {
        return response()->json([
            'success' => true,
            'sum_transaction_days' =>
            $this->transactionAccount->getWalletTransactionDaysAccountGainUser(WalletBank::class) +
                $this->transactionAccount->getWalletTransactionDaysAccountGainUser(WalletDeposit::class) +
                $this->transactionAccount->getWalletTransactionDaysAccountGainUser(WalletPayment::class) +
                $this->transactionAccount->getWalletTransactionDaysAccountGainUser(BankCard::class) +
                $this->transactionAccount->getWalletTransactionDaysAccountGainUser(WalletPhoneCredit::class) +
                $this->transactionAccount->getWalletTransactionDaysAccountGainUser(WalletTransaction::class) +
                $this->transactionAccount->getWalletTransactionDaysAccountGainUser(WalletReload::class) +
                $this->transactionAccount->getWalletTransactionDaysAccountGainUser(WalletInter::class)   +
                $this->transactionAccount->getWalletTransactionDaysAccountGainUser(WalletXbet::class) +
                $this->transactionAccount->getWalletTransactionDaysAccountGainUser(WalletWave::class),
        ], 200);
    }


    public function historical_distributer_master()
    {
        return response()->json([
            'success' => true,
            'sum_transaction_days' =>
            $this->transactionAccount->getTodayTransactionGainTotal(WalletBank::class) +
                $this->transactionAccount->getTodayTransactionGainTotal(WalletDeposit::class) +
                $this->transactionAccount->getTodayTransactionGainTotal(WalletPayment::class) +
                $this->transactionAccount->getTodayTransactionGainTotal(BankCard::class) +
                $this->transactionAccount->getTodayTransactionGainTotal(WalletPhoneCredit::class) +
                $this->transactionAccount->getTodayTransactionGainTotal(WalletTransaction::class) +
                $this->transactionAccount->getTodayTransactionGainTotal(WalletReload::class) +
                $this->transactionAccount->getTodayTransactionGainTotal(WalletInter::class) +
                $this->transactionAccount->getTodayTransactionGainTotal(WalletXbet::class) +
                $this->transactionAccount->getTodayTransactionGainTotal(WalletWave::class),
        ], 200);
    }


    public function historical_distributer_evolution()
    {


        return response()->json([
            'success' => true,

            'success_transaction_days' =>   $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(WalletBank::class, 'SUCCESS') +
                $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(WalletDeposit::class, 'SUCCESS') +
                $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(WalletPayment::class, 'SUCCESS') +
                $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(BankCard::class, 'SUCCESS') +
                $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(WalletPhoneCredit::class, 'SUCCESS') +
                $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(WalletTransaction::class, 'SUCCESS') +
                $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(WalletReload::class, 'SUCCESS') +
                $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(WalletInter::class, 'SUCCESS'),

            'error_transaction_days' =>   $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(WalletBank::class, 'ERROR') +
                $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(WalletDeposit::class, 'ERROR') +
                $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(WalletPayment::class, 'ERROR') +
                $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(BankCard::class, 'ERROR') +
                $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(WalletPhoneCredit::class, 'ERROR') +
                $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(WalletTransaction::class, 'ERROR') +
                $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(WalletReload::class, 'ERROR') +
                $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(WalletInter::class, 'ERROR'),

            'pendding_transaction_days' =>   $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(WalletBank::class, 'PENDING') +
                $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(WalletDeposit::class, 'PENDING') +
                $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(WalletPayment::class, 'PENDING') +
                $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(BankCard::class, 'PENDING') +
                $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(WalletPhoneCredit::class, 'PENDING') +
                $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(WalletTransaction::class, 'PENDING') +
                $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(WalletReload::class, 'PENDING') +
                $this->transactionAccount->getWalletTransactionDaysAccountStatusUser(WalletInter::class, 'PENDING'),

        ], 200);
    }


    public function historical_distributer_evolution_master()
    {


        return response()->json([
            'success' => true,

            'success_transaction_days' =>   $this->transactionAccount->getTransactionMasterCount(WalletBank::class, 'SUCCESS') +
                $this->transactionAccount->getTransactionMasterCount(WalletDeposit::class, 'SUCCESS') +
                $this->transactionAccount->getTransactionMasterCount(WalletPayment::class, 'SUCCESS') +
                $this->transactionAccount->getTransactionMasterCount(BankCard::class, 'SUCCESS') +
                $this->transactionAccount->getTransactionMasterCount(WalletPhoneCredit::class, 'SUCCESS') +
                $this->transactionAccount->getTransactionMasterCount(WalletTransaction::class, 'SUCCESS') +
                $this->transactionAccount->getTransactionMasterCount(WalletReload::class, 'SUCCESS') +
                $this->transactionAccount->getTransactionMasterCount(WalletInter::class, 'SUCCESS'),

            'error_transaction_days' =>   $this->transactionAccount->getTransactionMasterCount(WalletBank::class, 'ERROR') +
                $this->transactionAccount->getTransactionMasterCount(WalletDeposit::class, 'ERROR') +
                $this->transactionAccount->getTransactionMasterCount(WalletPayment::class, 'ERROR') +
                $this->transactionAccount->getTransactionMasterCount(BankCard::class, 'ERROR') +
                $this->transactionAccount->getTransactionMasterCount(WalletPhoneCredit::class, 'ERROR') +
                $this->transactionAccount->getTransactionMasterCount(WalletTransaction::class, 'ERROR') +
                $this->transactionAccount->getTransactionMasterCount(WalletReload::class, 'ERROR') +
                $this->transactionAccount->getTransactionMasterCount(WalletInter::class, 'ERROR'),

            'pendding_transaction_days' =>   $this->transactionAccount->getTransactionMasterCount(WalletBank::class, 'PENDING') +
                $this->transactionAccount->getTransactionMasterCount(WalletDeposit::class, 'PENDING') +
                $this->transactionAccount->getTransactionMasterCount(WalletPayment::class, 'PENDING') +
                $this->transactionAccount->getTransactionMasterCount(BankCard::class, 'PENDING') +
                $this->transactionAccount->getTransactionMasterCount(WalletPhoneCredit::class, 'PENDING') +
                $this->transactionAccount->getTransactionMasterCount(WalletTransaction::class, 'PENDING') +
                $this->transactionAccount->getTransactionMasterCount(WalletReload::class, 'PENDING') +
                $this->transactionAccount->getTransactionMasterCount(WalletInter::class, 'PENDING'),

        ], 200);
    }

    public function historical_month_distributer_master()
    {

        $som_commission_month =
            $this->transactionAccount->getMonthTransactionGain(WalletBank::class) +
            $this->transactionAccount->getMonthTransactionGain(WalletDeposit::class) +
            $this->transactionAccount->getMonthTransactionGain(WalletPayment::class) +
            $this->transactionAccount->getMonthTransactionGain(BankCard::class) +
            $this->transactionAccount->getMonthTransactionGain(WalletPhoneCredit::class) +
            $this->transactionAccount->getMonthTransactionGain(WalletTransaction::class) +
            $this->transactionAccount->getMonthTransactionGain(WalletReload::class) +
            $this->transactionAccount->getMonthTransactionGain(WalletInter::class);

        return response()->json([
            'success' => true,
            'sum_transaction_month' =>
            $this->transactionAccount->getTransactionMasterMonthCount(WalletBank::class) +
                $this->transactionAccount->getTransactionMasterMonthCount(WalletDeposit::class) +
                $this->transactionAccount->getTransactionMasterMonthCount(WalletPayment::class) +
                $this->transactionAccount->getTransactionMasterMonthCount(BankCard::class) +
                $this->transactionAccount->getTransactionMasterMonthCount(WalletPhoneCredit::class) +
                $this->transactionAccount->getTransactionMasterMonthCount(WalletTransaction::class) +
                $this->transactionAccount->getTransactionMasterMonthCount(WalletReload::class) +
                $this->transactionAccount->getTransactionMasterMonthCount(WalletInter::class),
            'som_commission' =>  $som_commission_month . ' ' . robus()->wallet->currencie->code,
        ], 200);
    }

    public function historical_month_distributer()
    {

        $som_commission_month =
            $this->transactionAccount->getMonthTransactionGainUser(WalletBank::class) +
            $this->transactionAccount->getMonthTransactionGainUser(WalletDeposit::class) +
            $this->transactionAccount->getMonthTransactionGainUser(WalletPayment::class) +
            $this->transactionAccount->getMonthTransactionGainUser(BankCard::class) +
            $this->transactionAccount->getMonthTransactionGainUser(WalletPhoneCredit::class) +
            $this->transactionAccount->getMonthTransactionGainUser(WalletTransaction::class) +
            $this->transactionAccount->getMonthTransactionGainUser(WalletReload::class) +
            $this->transactionAccount->getMonthTransactionGainUser(WalletInter::class);

        return response()->json([
            'success' => true,
            'sum_transaction_month' =>
            $this->transactionAccount->getWalletTransactionMonthAccountStatusUser(WalletBank::class) +
                $this->transactionAccount->getWalletTransactionMonthAccountStatusUser(WalletDeposit::class) +
                $this->transactionAccount->getWalletTransactionMonthAccountStatusUser(WalletPayment::class) +
                $this->transactionAccount->getWalletTransactionMonthAccountStatusUser(BankCard::class) +
                $this->transactionAccount->getWalletTransactionMonthAccountStatusUser(WalletPhoneCredit::class) +
                $this->transactionAccount->getWalletTransactionMonthAccountStatusUser(WalletTransaction::class) +
                $this->transactionAccount->getWalletTransactionMonthAccountStatusUser(WalletReload::class) +
                $this->transactionAccount->getWalletTransactionMonthAccountStatusUser(WalletInter::class),
            'som_commission' =>  $som_commission_month . ' ' . robus()->wallet->currencie->code,
        ], 200);
    }


    public function historical_year_distributer_master()
    {


        $som_commission_year =
            $this->transactionAccount->getYearTransactionGainUser(WalletBank::class) +
            $this->transactionAccount->getYearTransactionGainUser(WalletDeposit::class) +
            $this->transactionAccount->getYearTransactionGainUser(WalletPayment::class) +
            $this->transactionAccount->getYearTransactionGainUser(BankCard::class) +
            $this->transactionAccount->getYearTransactionGainUser(WalletPhoneCredit::class) +
            $this->transactionAccount->getYearTransactionGainUser(WalletTransaction::class) +
            $this->transactionAccount->getYearTransactionGainUser(WalletReload::class) +
            $this->transactionAccount->getYearTransactionGainUser(WalletInter::class);

        return response()->json([
            'success' => true,
            'bank_transaction_month' =>    $this->transactionAccount->getYearTransactionSingleGainUser(WalletBank::class),
            'deposit_transaction_month' => $this->transactionAccount->getYearTransactionSingleGainUser(WalletDeposit::class),
            'payment_transaction_month' =>  $this->transactionAccount->getYearTransactionSingleGainUser(WalletPayment::class),
            'reload_transaction_month' =>  $this->transactionAccount->getYearTransactionSingleGainUser(WalletReload::class),
            'transaction_transaction_month' => $this->transactionAccount->getYearTransactionSingleGainUser(WalletTransaction::class),
            'inter_transaction_month' =>  $this->transactionAccount->getYearTransactionSingleGainUser(WalletInter::class),
            'phonecredit_transaction_month' =>   $this->transactionAccount->getYearTransactionSingleGainUser(WalletPhoneCredit::class),
            'som_commission' =>  $som_commission_year . ' ' . robus()->wallet->currencie->code,
        ], 200);
    }

    public function historical_year_distributer()
    {


        $som_commission_year =
            $this->transactionAccount->getYearTransactionGainUser(WalletBank::class) +
            $this->transactionAccount->getYearTransactionGainUser(WalletDeposit::class) +
            $this->transactionAccount->getYearTransactionGainUser(WalletPayment::class) +
            $this->transactionAccount->getYearTransactionGainUser(BankCard::class) +
            $this->transactionAccount->getYearTransactionGainUser(WalletPhoneCredit::class) +
            $this->transactionAccount->getYearTransactionGainUser(WalletTransaction::class) +
            $this->transactionAccount->getYearTransactionGainUser(WalletReload::class) +
            $this->transactionAccount->getYearTransactionGainUser(WalletInter::class);

        return response()->json([
            'success' => true,
            'bank_transaction_month' =>    $this->transactionAccount->getYearTransactionSingleGainUser(WalletBank::class),
            'deposit_transaction_month' => $this->transactionAccount->getYearTransactionSingleGainUser(WalletDeposit::class),
            'payment_transaction_month' =>  $this->transactionAccount->getYearTransactionSingleGainUser(WalletPayment::class),
            'reload_transaction_month' =>  $this->transactionAccount->getYearTransactionSingleGainUser(WalletReload::class),
            'transaction_transaction_month' => $this->transactionAccount->getYearTransactionSingleGainUser(WalletTransaction::class),
            'inter_transaction_month' =>  $this->transactionAccount->getYearTransactionSingleGainUser(WalletInter::class),
            'phonecredit_transaction_month' =>   $this->transactionAccount->getYearTransactionSingleGainUser(WalletPhoneCredit::class),
            'som_commission' =>  $som_commission_year . ' ' . robus()->wallet->currencie->code,
        ], 200);
    }

    public function drawal_commissions()
    {

        try {

            $data = [];

            $wallet_user = robus();

            // $wallet_banks =  $this->transactionAccount->getUserAllCumulTransactions(WalletBank::class);

            // $wallet_deposits =  $this->transactionAccount->getUserAllCumulTransactions(WalletDeposit::class);

            // $wallet_payments =  $this->transactionAccount->getUserAllCumulTransactions(WalletPayment::class);

            // $wallet_transactions =  $this->transactionAccount->getUserAllCumulTransactions(WalletTransaction::class);

            // $wallet_inters =  $this->transactionAccount->getUserAllCumulTransactions(WalletInter::class);

            // $wallet_phone_credits =  $this->transactionAccount->getUserAllCumulTransactions(WalletPhoneCredit::class);

            // $wallet_reloads =  $this->transactionAccount->getUserAllCumulTransactions(WalletReload::class);

            // $order_banks =  $this->transactionAccount->getUserAllCumulOrderTransactions(BankCard::class);


            $transactionTypes = [
                WalletBank::class,
                WalletDeposit::class,
                WalletPayment::class,
                WalletTransaction::class,
                WalletInter::class,
                WalletPhoneCredit::class,
                BankCard::class
            ];


            foreach ($transactionTypes as $transactionType) {
                $transactions = $this->transactionAccount->getUserAllCumulTransactions($transactionType);
                $totalCommission = $transactions->sum('commissions');
                $data[] = $totalCommission;

                $transactions->update([
                    'commissions' => 0,
                    'commissions_admin' => 0,
                ]);
            }

            $totalCommissionSum = array_sum($data);

            if ($totalCommissionSum == 0) {
                return response()->json([
                    'success' => false,
                    'message' => ('Votre somme de commission est nul .')
                ]);
            } else {

                // Update Info valeurs

                $amountSolde = (float) $wallet_user->wallet->amount + (float) $totalCommissionSum;

                $valeurReceived = WithMerchant::create([
                    'amount_with_draw' => $totalCommissionSum,
                    'sid_user_id' => $wallet_user->id,
                    'amount_solde' => $amountSolde,
                    'type_transaction' => "COMMISSION",
                    'status' => "SUCCESS",
                    'date_removed' => Carbon::now(),
                ]);

                $wallet_user->wallet->update([
                    'reload_by' => robus()->firstname . ' ' . robus()->lastname,
                    'sid_user_relod' => robus()->id,
                    'amount' => DB::raw('amount+'  .  $valeurReceived->amount_with_draw),
                ]);

                $msg = 'Vous avez transferé vos commission sur votre compte';
                $msg_title = 'Remonté les commissions ';


                $wallet_user->notify(new UserNotification([
                    'title' => $msg_title,
                    'message' => $msg,
                ]));

                if ($wallet_user->country->code == "CI") {

                    $message = 'Vous avez transferé une commission de ' . $valeurReceived->amount_with_draw . ' ' . $wallet_user->wallet->currencie->code . ' le '
                        . $valeurReceived->created_at  . ' Robus vous remercie';

                    $encode = substr(robus()->phone,  -10);
                } else {


                    if ($wallet_user->email != null) {

                        Mail::to($wallet_user->email)->send(new UvSend('Vous avez transferé une commission de ' . $valeurReceived->amount_with_draw . ' ' . $wallet_user->wallet->currencie->code . ' le '
                            . $valeurReceived->created_at  . ' Robus vous remercie'));
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => ('Votre transfert de commission est effectué avec succès.')
                ]);
            }
        } catch (\Exception $exception) {
            $message = __('message.is not providers transactions');
            return json_message_response(false, $message, 333);
        }
    }



    public function drawal_commission_masters()
    {

        try {

            $data = [];

            $wallet_user = robus();


            $transactionTypes = [
                WalletBank::class,
                WalletDeposit::class,
                WalletPayment::class,
                WalletTransaction::class,
                WalletInter::class,
                WalletPhoneCredit::class,
                BankCard::class
            ];


            foreach ($transactionTypes as $transactionType) {
                $transactions = $this->transactionAccount->getUserAllCumulTransactions($transactionType);
                $totalCommission = $transactions->sum('commission_masters');
                $data[] = $totalCommission;

                $transactions->update([
                    'commission_masters' => 0,
                    // 'commissions_admin' => 0,
                ]);
            }

            $totalCommissionSum = array_sum($data);

            if ($totalCommissionSum == 0) {
                return response()->json([
                    'success' => false,
                    'message' => ('Votre somme de commission est nul .')
                ]);
            } else {

                // Update Info valeurs

                $amountSolde = (float) $wallet_user->wallet->amount + (float) $totalCommissionSum;

                $valeurReceived = WithMerchant::create([
                    'amount_with_draw' => $totalCommissionSum,
                    'sid_user_id' => $wallet_user->id,
                    'amount_solde' => $amountSolde,
                    'type_transaction' => "COMMISSION",
                    'status' => "SUCCESS",
                    'date_removed' => Carbon::now(),
                ]);

                $wallet_user->wallet->update([
                    'reload_by' => robus()->firstname . ' ' . robus()->lastname,
                    'sid_user_relod' => robus()->id,
                    'amount' => DB::raw('amount+'  .  $valeurReceived->amount_with_draw),
                ]);

                $msg = 'Vous avez transferé vos commission sur votre compte';
                $msg_title = 'Remonté les commissions ';

                $wallet_user->notify(new UserNotification([
                    'title' => $msg_title,
                    'message' => $msg,
                ]));

                if ($wallet_user->country->code == "CI") {

                    $message = 'Vous avez transferé une commission de ' . $valeurReceived->amount_with_draw . ' ' . $wallet_user->wallet->currencie->code . ' le '
                        . $valeurReceived->created_at  . ' Robus vous remercie';

                    $encode = substr(robus()->phone,  -10);
                } else {


                    if ($wallet_user->email != null) {

                        Mail::to($wallet_user->email)->send(new UvSend('Vous avez transferé une commission de ' . $valeurReceived->amount_with_draw . ' ' . $wallet_user->wallet->currencie->code . ' le '
                            . $valeurReceived->created_at  . ' Robus vous remercie'));
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => ('Votre transfert de commission est effectué avec succès.')
                ]);
            }
        } catch (\Exception $exception) {
            $message = __('message.is not providers transactions');
            return json_message_response(false, $message, 333);
        }
    }

    public function masters_under_distributors()
    {
        $under_distributors =    $this->transactionAccount->getUnderDistributorsUser();

        return response()->json([
            'success' => true,
            'under_distributors' => robus()->is_master ?  new UnderCollection($under_distributors) : " Vous devez etre un Masters"
        ]);
    }



    public function comzone_under_distributors()
    {
        $under_distributors =    $this->transactionAccount->getUnderDistributorsComUser();

        return response()->json([
            'success' => true,
            'under_distributors' => robus()->is_com_zone ? new UnderCollection($under_distributors) : "Vous devez être un COM ZONE",
        ]);
    }


    public function masters_com_zones()
    {
        $under_distributors =    $this->transactionAccount->getMastersUser();

        return response()->json([
            'success' => true,
            'under_distributors' => robus()->is_com_zone ? new UnderCollection($under_distributors) : "Vous devez être un COM ZONE",
        ]);
    }


    public function custumers_com_zones()
    {
        $under_distributors =    $this->transactionAccount->getCustmersUser();

        return response()->json([
            'success' => true,
            'under_distributors' => robus()->is_com_zone ? new UnderCollection($under_distributors) : "Vous devez être un COM ZONE",
        ]);
    }

    public function add_under_distributor(Request $request)
    {

        $admins = $this->validatedamountDays->adminCiv();

        $validator = Validator::make(

            $request->all(),
            [
                "num_wallet" => 'required',
            ],
            [

                'num_wallet.required' => 'Votre numero est obligatoire',

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



        try {


            $wallet_search = $this->robusTransactionService->searchWalletRobus($request->num_wallet);

            if (!$wallet_search || $request->num_wallet == robus()->wallet->wallet_id) {

                return response()->json([
                    'success' => false,
                    'message' => 'Ce numéro n\'est pas ROBUS ou est votre numéro',
                ], 333);
            }

            // verify id under distributor existe
            if ($wallet_search->sidUser->master_id == robus()->id) {

                return response()->json([
                    'success' => false,
                    'message' => 'Désolé ce numero existe deja dans votre reseau !!!',
                ], 333);
            }

            if ($wallet_search->sidUser->id == robus()->id || $wallet_search->sidUser->is_company || $wallet_search->sidUser->is_master || $wallet_search->sidUser->is_admin  || $wallet_search->sidUser->is_supervisor || $wallet_search->sidUser->is_distributer) {

                return  json_message_response(false, "Desolé Ce wallet n'es pas pour un sous distributeur  !!!", 333);
            } else {


                // Update Users
                $wallet_search->sidUser->update(
                    [
                        'master_id' => robus()->id,
                        'is_accepted'  => false,
                    ]
                );

                // Message admin
                Notification::send($admins, new AdminNotification('Demande de liaison sous distributeur'));

                if ($admins->count() > 0) {

                    foreach ($admins  as $user) {

                        $admin_single = SidUser::find($user->id);

                        $notifs = $admin_single->unreadNotifications;
                        event(new AdminNotifEvent($notifs));
                    }
                }

                if (robus()->email != null) {
                    Mail::to(robus()->email)->send(new NewAdmin("Vous avez fais une demande de liaison du sous distributeur "  . $wallet_search->sidUser->firstname  . ' ' .  $wallet_search->sidUser->firstname . env('APP_NAME')));
                }

                return  json_message_response(true, "Sous distributeur " . $wallet_search->sidUser->firstname . ' ' . $wallet_search->sidUser->lastname .  " ajouté a votre reseau ", 333);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
            ], 333);
        }
    }
}
