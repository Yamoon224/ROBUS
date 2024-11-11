<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Kyc;
use App\Mail\UvSend;
use App\Tools\Image;
use App\Models\Sid\SidUser;
use App\Models\WithMerchant;
use Illuminate\Http\Request;
use App\Models\Banks\BankCard;
use App\Models\Custumers\City;
use App\Models\Wallets\Wallet;
use App\Models\Custumers\Country;
use App\Models\Wallets\WalletBank;
use Illuminate\Support\Facades\DB;
use App\Models\Wallets\WalletInter;
use App\Http\Controllers\Controller;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Notifications\UserNotification;
use App\Http\Resources\ReloadCardSingle;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\KycSingleCollection;
use App\Http\Resources\ReloadCartCollection;
use App\Http\Resources\UserSingleCollection;
use App\Http\Resources\ReloadSingleCollection;
use App\Http\Resources\WalletSingleCollection;
use App\Http\Resources\PaymentSingleCollection;
use App\Http\Resources\RemonteSingleCollection;
use App\Http\Resources\VirementSingleCollection;
use App\Http\Resources\PhoneCreditSingleCollection;
use App\Http\Resources\TransactionSingleCollection;
use App\Http\Resources\InterTransactionSingleCollection;

class UserControllerAncien extends Controller
{

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

        $wallet_user = Auth::guard('api')->user();
        $wallet_solde = Wallet::where('sid_user_id', $wallet_user->id)->first();
        $dashboard_data['wallet_user'] = new WalletSingleCollection($wallet_solde);
        $startOfWeek = Carbon::now()->startOfWeek(); // Début de la semaine (lundi)
        $endOfWeek = Carbon::now()->endOfDay(); // Fin de la journée actuelle

        $walletTransaction = WalletTransaction::where('wallet_id', $wallet_solde->id)
            ->orWhere('num_wallet', $wallet_solde->wallet_id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        $walletPayment = WalletPayment::where('wallet_id', $wallet_solde->id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        $walletBank = WalletBank::where('wallet_id', $wallet_solde->id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        $walletPhone = WalletPhoneCredit::where('wallet_id', $wallet_solde->id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        $walletReload = WalletReload::where('wallet_id', $wallet_solde->id)
            ->orWhere('account_wallet', $wallet_solde->wallet_id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        $walletReloadCard = WalletDeposit::where('wallet_id', $wallet_solde->id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        $walletInter = WalletInter::where('wallet_id', $wallet_solde->id)
            ->orWhere('number_compte', $wallet_solde->wallet_id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        $walletOrderCarte = BankCard::where('wallet_id', $wallet_solde->id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();


        $walletRemonte = WithMerchant::where('sid_user_id', $wallet_user->id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        $som_commissions = $this->getWalletBankSumGainUserProperty() + $this->getWalletDepositSumGainUserProperty() + $this->getWalletPaymentSumGainUserProperty()
            + $this->getWalletTransactionSumGainUserProperty() + $this->getWalletInterSumGainUserProperty() + $this->getWalletPhoneCreditSumGainUserProperty()
            + $this->getWalletReloadSumGainUserProperty() + $this->getWalletOrderBankSumGainUserProperty();

            
        $dashboard_data['Transactions'] = TransactionSingleCollection::collection($walletTransaction);

        $dashboard_data['Commission remonté'] = RemonteSingleCollection::collection($walletRemonte);
        $dashboard_data['Paiements'] = PaymentSingleCollection::collection($walletPayment);
        $dashboard_data['Versements'] = VirementSingleCollection::collection($walletBank);
        $dashboard_data['Credit telephoniques'] = PhoneCreditSingleCollection::collection($walletPhone);
        $dashboard_data['Rechargement de carte'] = ReloadCardSingle::collection($walletReloadCard);
        $dashboard_data['Rechargement d\'UV'] = ReloadSingleCollection::collection($walletReload);
        $dashboard_data['Commande de carte'] = ReloadCartCollection::collection($walletOrderCarte);
        $dashboard_data['Transfers inter'] = InterTransactionSingleCollection::collection($walletInter);


        return response()->json([
            'success' => true,
            'historicals' =>  $dashboard_data,
            'som_commission' =>  $som_commissions . ' ' . Auth::guard('api')->user()->wallet->currencie->code,
        ], 200);
    }


    public function dashboard_historique()
    {

        $wallet_user = Auth::guard('api')->user();
        $wallet_solde = Wallet::where('sid_user_id', $wallet_user->id)->first();
        $dashboard_data['wallet_user'] = new WalletSingleCollection($wallet_solde);


        $walletTransaction = WalletTransaction::where('wallet_id', $wallet_solde->id)
            ->orWhere('num_wallet', $wallet_solde->wallet_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $walletPayment = WalletPayment::where('wallet_id', $wallet_solde->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $walletBank = WalletBank::where('wallet_id', $wallet_solde->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $walletPhone = WalletPhoneCredit::where('wallet_id', $wallet_solde->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $walletReload = WalletReload::where('wallet_id', $wallet_solde->id)
            ->orWhere('account_wallet', $wallet_solde->wallet_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $walletReloadCard = WalletDeposit::where('wallet_id', $wallet_solde->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $walletInter = WalletInter::where('wallet_id', $wallet_solde->id)
            ->orWhere('number_compte', $wallet_solde->wallet_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $walletOrderCarte = BankCard::where('wallet_id', $wallet_solde->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);


        $walletRemonte = WithMerchant::where('sid_user_id', $wallet_user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $som_commissions = $this->getWalletBankSumGainUserProperty() + $this->getWalletDepositSumGainUserProperty() + $this->getWalletPaymentSumGainUserProperty()
            + $this->getWalletTransactionSumGainUserProperty() + $this->getWalletInterSumGainUserProperty() + $this->getWalletPhoneCreditSumGainUserProperty()
            + $this->getWalletReloadSumGainUserProperty() + $this->getWalletOrderBankSumGainUserProperty();

            
        $dashboard_data['Transactions'] = TransactionSingleCollection::collection($walletTransaction);

        $dashboard_data['Commission remonté'] = RemonteSingleCollection::collection($walletRemonte);
        $dashboard_data['Paiements'] = PaymentSingleCollection::collection($walletPayment);
        $dashboard_data['Versements'] = VirementSingleCollection::collection($walletBank);
        $dashboard_data['Credit telephoniques'] = PhoneCreditSingleCollection::collection($walletPhone);
        $dashboard_data['Rechargement de carte'] = ReloadCardSingle::collection($walletReloadCard);
        $dashboard_data['Rechargement d\'UV'] = ReloadSingleCollection::collection($walletReload);
        $dashboard_data['Commande de carte'] = ReloadCartCollection::collection($walletOrderCarte);
        $dashboard_data['Transfers inter'] = InterTransactionSingleCollection::collection($walletInter);


        return response()->json([
            'success' => true,
            'historicals' =>  $dashboard_data,
            'som_commission' =>  $som_commissions . ' ' . Auth::guard('api')->user()->wallet->currencie->code,
        ], 200);
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
        $user = SidUser::find(Auth::guard('api')->user()->id);

        return response()->json([
            'success' => true,
            'user' => new UserSingleCollection($user)
        ]);
    }

    /**
     * @OA\POST(
     *      path="/user/info/
     ",
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

        $user = SidUser::find(Auth::guard('api')->user()->id);

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
            'lastname' => $request->lastname
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
        $user = SidUser::find(Auth::guard('api')->user()->id);

        // $validator = Validator::make(

        //     $request->all(),
        //     [
        //         'front' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        //         'back' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        //     ]
        // );

        // if ($validator->fails()) {
        //     return response()->json(
        //         [
        //             'status' => false,
        //             'message' => 'Validated champ',
        //             'data' => $validator->errors()
        //         ],
        //         333
        //     );
        // }

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

        // if ($file_back) {

        //     $destination_path_back = public_path('validated_accounts/' . Auth::user()->firstname . '/');
        //     $back_file = 'arriere_' . date('YmdHis') . "." . $file_back->getClientOriginalExtension();
        //     $file_back->move($destination_path_back, $back_file);
        //     $user->kyc->update([
        //         'motif' => 'Validation de compte',
        //         'sid_user_id' => $user->id,
        //         'screen_back' => env('APP_URL') . "/public/validated_accounts/" . Auth::user()->firstname . "/$back_file",
        //         'status' => false,
        //         'company_id'  =>  $user->company->id
        //     ]);
        // }

        return response()->json([
            'success' => true,
            'message' => 'Les informations de profil ont été mises à jour avec succès',
            'user' => new UserSingleCollection($user)
        ], 200);
    }

    // View liste of commission User
    public function kyc()
    {
        $user = SidUser::find(Auth::guard('api')->user()->id);

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
            'sum_transaction_days' =>   $this->getWalletBankAccountGainUserProperty() +  $this->getWalletDepositAccountGainUserProperty()
                + $this->getWalletPaymentAccountGainUserProperty()
                + $this->getWalletReloadAccountGainUserProperty() +  $this->getWalletTransactionAccountGainUserProperty()
                + $this->getWalletInterAccountGainUserProperty() +  $this->getWalletPhoneCreditAccountGainUserProperty()
                + $this->getWalletOrderBankAccountSumGainUserProperty(),
        ], 200);
    }

    public function historical_distributer_evolution()
    {


        return response()->json([
            'success' => true,
            'success_transaction_days' =>   $this->getTransactionSuccessUserProperty() +  $this->getPaymentSuccessUserProperty()
                +  $this->getReloadSuccessUserProperty() +  $this->getVirementSuccessUserProperty() +  $this->getInterSuccessUserProperty()
                +  $this->getPhoneCreditSuccessUserProperty()  +  $this->getDepositSuccessUserProperty(),
            'error_transaction_days' => $this->getTransactionErrorUserProperty() +  $this->getPaymentErrorUserProperty()
                +  $this->getReloadErrorUserProperty() +  $this->getVirementErrorUserProperty() +  $this->getInterErrorUserProperty()
                +  $this->getPhoneCreditErrorUserProperty()  +  $this->getDepositErrorUserProperty(),
            'pendding_transaction_days' =>   $this->getTransactionPenddingUserProperty() +  $this->getPaymentPenddingUserProperty()
                +  $this->getReloadPenddingUserProperty() +  $this->getVirementPenddingUserProperty() +  $this->getInterPenddingUserProperty()
                +  $this->getPhoneCreditPenddingUserProperty()  +  $this->getDepositPenddingUserProperty(),

        ], 200);
    }

    public function historical_month_distributer()
    {

        $som_commission_month = $this->getWalletBankMonthSumGainUserProperty() + $this->getWalletDepositMonthSumGainUserProperty() + $this->getWalletPaymentMonthSumGainUserProperty()
            + $this->getWalletTransactionMonthSumGainUserProperty() + $this->getWalletInterMonthSumGainUserProperty() + $this->getWalletPhoneCreditMonthSumGainUserProperty()
            + $this->getWalletReloadMonthSumGainUserProperty() + $this->getWalletOrderBankSumGainUserProperty();

        return response()->json([
            'success' => true,
            'sum_transaction_month' =>   $this->getWalletBankMonthUserProperty() +  $this->getWalletDepositMonthUserProperty() +
                $this->getWalletPaymentMonthUserProperty() + $this->getWalletReloadMonthUserProperty()
                +  $this->getWalletTransactionMonthUserProperty() + $this->getWalletInterMonthUserProperty() + $this->getWalletPhoneCreditMonthUserProperty() + $this->getWalletOrderBankSumGainUserProperty(),
            'som_commission' =>  $som_commission_month . ' ' . Auth::guard('api')->user()->wallet->currencie->code,
        ], 200);
    }

    public function historical_year_distributer()
    {
        $wallet_user = Auth::guard('api')->user();

        $som_commission_year = $this->getWalletBankYearSumGainUserProperty() + $this->getWalletDepositYearSumGainUserProperty() + $this->getWalletPaymentYearSumGainUserProperty()
            + $this->getWalletTransactionYearSumGainUserProperty() + $this->getWalletInterYearSumGainUserProperty() + $this->getWalletPhoneCreditYearSumGainUserProperty()
            + $this->getWalletReloadYearSumGainUserProperty();

        return response()->json([
            'success' => true,
            'bank_transaction_month' =>   $this->getWalletBankYearUserProperty(),
            'deposit_transaction_month' =>  $this->getWalletDepositYearUserProperty(),
            'payment_transaction_month' =>  $this->getWalletPaymentYearUserProperty(),
            'reload_transaction_month' =>  $this->getWalletReloadYearUserProperty(),
            'transaction_transaction_month' =>  $this->getWalletTransactionYearUserProperty(),
            'inter_transaction_month' =>  $this->getWalletInterYearUserProperty(),
            'phonecredit_transaction_month' =>   $this->getWalletPhoneCreditYearUserProperty(),
            'som_commission' =>  $som_commission_year . ' ' . Auth::guard('api')->user()->wallet->currencie->code,
        ], 200);
     
    }

    public function drawal_commissions()
    {

        try {

            $data1 = [];
            $data2 = [];
            $data3 = [];
            $data4 = [];
            $data5 = [];
            $data6 = [];
            $data7 = [];
            $data8 = [];

            $wallet_user = Auth::guard('api')->user();
// 
            $wallet_banks =  WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
                // ->whereDate('wallet_banks.created_at', Carbon::now())
                ->where('wallet_banks.created_by', Auth::guard('api')->user()->id)
                ->where('wallet_banks.status', 'SUCCESS')
                ->select('wallet_banks.*')
                ->get();
// 
            $wallet_deposits = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
                // ->whereDate('wallet_deposits.created_at', Carbon::now())
                ->where('wallet_deposits.created_by', Auth::guard('api')->user()->id)
                ->where('wallet_deposits.status', 'SUCCESS')
                ->select('wallet_deposits.*')
                ->get();
// 
            $wallet_payments = WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
                // ->whereDate('wallet_payments.created_at', Carbon::now())
                ->where('wallet_payments.created_by', Auth::guard('api')->user()->id)
                ->where('wallet_payments.status', 'SUCCESS')
                ->select('wallet_payments.*')
                ->get();
//
            $wallet_transactions = WalletTransaction::join(
                'wallets',
                'wallet_transactions.wallet_id',
                'wallets.id'
            )
                ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
                // ->whereDate('wallet_transactions.created_at', Carbon::now())
                ->where('wallet_transactions.created_by', Auth::guard('api')->user()->id)
                ->where('wallet_transactions.status', 'SUCCESS')
                ->select('wallet_transactions.*')
                ->get();
// 
            $wallet_inters = WalletInter::join(
                'wallets',
                'wallet_inters.wallet_id',
                'wallets.id'
            )
                // ->where('wallet_inters.country_id', Auth::guard('api')->user()->country->id)
                // ->whereDate('wallet_inters.created_at', Carbon::now())
                ->where('wallet_inters.created_by', Auth::guard('api')->user()->id)
                ->where('wallet_inters.status', 'SUCCESS')
                ->select('wallet_inters.*')
                ->get();
                // 
            $wallet_phone_credits = WalletPhoneCredit::join(
                'wallets',
                'wallet_phone_credits.wallet_id',
                'wallets.id'
            )
                ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
                // ->whereDate('wallet_phone_credits.created_at', Carbon::now())
                ->where('wallet_phone_credits.created_by', Auth::guard('api')->user()->id)
                ->where('wallet_phone_credits.status', 'SUCCESS')
                ->select('wallet_phone_credits.*')
                ->get();
// 
            $wallet_reloads = WalletReload::join(
                'wallets',
                'wallet_reloads.wallet_id',
                'wallets.id'
            )
                ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
                // ->whereDate('wallet_reloads.created_at', Carbon::now())
                ->where('wallet_reloads.created_by', Auth::guard('api')->user()->id)
                ->where('wallet_reloads.status', 'SUCCESS')
                ->select('wallet_reloads.*')
                ->get();

            $order_banks =  BankCard::join('wallets', 'bank_cards.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
                // ->whereDate('bank_cards.created_at', Carbon::now())
                ->where('bank_cards.created_by', Auth::guard('api')->user()->id)
                ->where('bank_cards.status', 1)
                ->select('bank_cards.*')
                ->get();


            foreach ($wallet_banks as $bank) {


                $data1[] = $bank->commissions;

                $bank->update([
                    'commissions' => 0,
                    'commissions_admin' => 0,
                ]);
            }


            foreach ($wallet_deposits as $deposit) {

                $data2[] = $deposit->commissions;

                $deposit->update([
                    'commissions' => 0,
                    'commissions_admin' => 0,
                ]);
            }

            foreach ($wallet_payments as $paiement) {

                $data3[] = $paiement->commissions;

                $paiement->update([
                    'commissions' => 0,
                    'commissions_admin' => 0,
                ]);
            }

            foreach ($wallet_transactions as $transaction) {

                $data4[] = $transaction->commissions;

                $transaction->update([
                    'commissions' => 0,
                    'commissions_admin' => 0,
                ]);
            }

            foreach ($wallet_inters as $inter) {

                $data5[] = $inter->commissions;

                $inter->update([
                    'commissions' => 0,
                    'commissions_admin' => 0,
                ]);
            }

            foreach ($wallet_phone_credits as $phone) {

                $data6[] = $phone->commissions;


                $phone->update([
                    'commissions' => 0,
                    'commissions_admin' => 0,
                ]);
            }

            foreach ($wallet_reloads as $reload) {

                $data7[] = $reload->commissions;

                $reload->update([
                    'commissions' => 0,
                    'commissions_admin' => 0,
                ]);
            }

            foreach ($order_banks as $order) {

                $data8[] = $order->commissions;

                $order->update([
                    'commissions' => 0,
                    'commissions_admin' => 0,
                ]);
            }

            $valeur_sum = array_sum($data1) + array_sum($data2) + array_sum($data3) + array_sum($data4)
                + array_sum($data5) + array_sum($data6) + array_sum($data7) + array_sum($data8);

            if ($valeur_sum == 0) {
                return response()->json([
                    'success' => false,
                    'message' => ('Votre somme de commission est nul .')
                ]);
            } else {

                // Update Info valeurs

                $amount_solde = (float)robus()->wallet->amount + (float)$valeur_sum;

                $valeur_received = WithMerchant::create([
                    'amount_with_draw' =>    $valeur_sum,
                    'sid_user_id' =>   $wallet_user->id,
                    'amount_solde' =>   $amount_solde,
                    'type_transaction' =>  "COMMISSION",
                    'status' =>  "SUCCESS",
                    'date_removed' => Carbon::now(),
                ]);

                $wallet_user->wallet->update([
                    'reload_by' => Auth::guard('api')->user()->firstname . ' ' . Auth::guard('api')->user()->lastname,
                    'sid_user_relod' => Auth::guard('api')->user()->id,
                    'amount' => DB::raw('amount+'  .  $valeur_received->amount_with_draw),
                ]);

                $msg = 'Vous avez transferé vos commission sur votre compte';
                $msg_title = 'Demande de virement';

                $wallet_user->notify(new UserNotification([
                    'title' => $msg_title,
                    'message' => $msg,
                ]));

                if ($wallet_user->country->code == "CI") {

                    $message = 'Vous avez transferé une commission de ' . $valeur_received->amount_with_draw . ' ' . $wallet_user->wallet->currencie->code . ' le '
                        . $valeur_received->created_at  . ' Robus vous remercie';

                    $encode = substr(Auth::guard('api')->user()->phone,  -10);

                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
              
                } else {


                    if ($wallet_user->email != null) {

                        Mail::to($wallet_user->email)->send(new UvSend('Vous avez transferé une commission de ' . $valeur_received->amount_with_draw . ' ' . $wallet_user->wallet->currencie->code . ' le '
                            . $valeur_received->created_at  . ' Robus vous remercie'));
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => ('Votre transfert de commission est effectué avec succès.')
                ]);
            }
        } catch (\Exception $exception) {
                return response()->json([
                        'success' => true,
                        'message' => ('Votre transfert de commission est effectué avec succès.')
                    ]);
        }
    }
    // Transaction amount User
    // Show User Month
    public function getWalletBankMonthUserProperty()
    {


        $result = WalletBank::join('wallets', 'wallets.id', 'wallet_banks.wallet_id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            ->where('wallet_banks.created_by', Auth::guard('api')->user()->id)
            ->whereMonth('wallet_banks.created_at', now()->subMonth()->month)
            // ->select(DB::raw('sum(wallet_banks.amount) as cumul_amount_chiffre'))
            ->groupBy('wallet_banks.created_at')
            ->count();

        return $result;
    }

    public function getWalletDepositMonthUserProperty()
    {
        $data = [];

        $result = walletDeposit::join('wallets', 'wallets.id', 'wallet_deposits.wallet_id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            ->where('wallet_deposits.created_by', Auth::guard('api')->user()->id)
            ->whereMonth('wallet_deposits.created_at', now()->subMonth()->month)
            // ->select(DB::raw('sum(wallet_deposits.amount) as cumul_amount_chiffre'))
            ->groupBy('wallet_deposits.created_at')
            ->count();

        return $result;
    }
    public function getWalletPaymentMonthUserProperty()
    {

        $result = WalletPayment::join('wallets', 'wallets.id', 'wallet_payments.wallet_id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            ->where('wallet_payments.created_by', Auth::guard('api')->user()->id)
            ->whereMonth('wallet_payments.created_at', now()->subMonth()->month)
            // ->select(DB::raw('sum(wallet_payments.amount) as cumul_amount_chiffre'))
            ->groupBy('wallet_payments.created_at')
            ->count();

        return $result;
    }

    public function getWalletTransactionMonthUserProperty()
    {

        $result = WalletTransaction::join('wallets', 'wallets.id', 'wallet_transactions.wallet_id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            ->where('wallet_transactions.created_by', Auth::guard('api')->user()->id)
            ->whereMonth('wallet_transactions.created_at', now()->subMonth()->month)
            // ->select(DB::raw('sum(wallet_transactions.amount) as cumul_amount_chiffre'))
            ->groupBy('wallet_transactions.created_at')
            ->count();

        return $result;
    }

    public function getWalletInterMonthUserProperty()
    {

        $result = WalletInter::join('wallets', 'wallets.id', 'wallet_inters.wallet_id')
            // ->where('wallet_inters.country_id', Auth::guard('api')->user()->country->id)
            ->where('wallet_inters.created_by', Auth::guard('api')->user()->id)
            ->whereMonth('wallet_inters.created_at', '>=', Carbon::now()->subDays(30))
            // ->select(DB::raw('sum(wallet_inters.amount) as cumul_amount_chiffre'))
            ->groupBy('wallet_inters.created_at')
            ->count();

        return $result;
    }

    public function getWalletPhoneCreditMonthUserProperty()
    {

        $result = WalletPhoneCredit::join('wallets', 'wallets.id', 'wallet_phone_credits.wallet_id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            ->where('wallet_phone_credits.created_by', Auth::guard('api')->user()->id)
            ->whereMonth('wallet_phone_credits.created_at',now()->subMonth()->month)
            // ->select(DB::raw('sum(wallet_phone_credits.amount) as cumul_amount_chiffre'))
            ->groupBy('wallet_phone_credits.created_at')
            ->count();

        return $result;
    }

    public function getWalletReloadMonthUserProperty()
    {

        $result = WalletReload::join(
            'wallets',
            'wallets.id',
            '=',
            'wallet_reloads.wallet_id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->where('wallet_reloads.created_by', Auth::guard('api')->user()->id)
            ->whereMonth(
                'wallet_reloads.created_at', now()->subMonth()->month
            )
            // ->select(
            //     DB::raw('sum(wallet_reloads.amount) as cumul_amount_chiffre')
            // )
            ->groupBy('wallet_reloads.created_at')
            ->count();

        return $result;
    }
    // Transaction Year
    public function getWalletBankYearUserProperty()
    {
        $data = [];
        // dd(Carbon::now()->year);
        $result = WalletBank::join('wallets', 'wallets.id', 'wallet_banks.wallet_id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            ->where('wallet_banks.created_by', Auth::guard('api')->user()->id)
            ->whereDate('wallet_banks.created_at', Carbon::now()->year)
            ->select(DB::raw('sum(wallet_banks.amount) as cumul_amount_chiffre'))
            ->groupBy('wallet_banks.created_at')
            ->get();

        foreach ($result as $sumBank) {
            $data[] = $sumBank->cumul_amount_chiffre;
        }

        return array_sum($data);
    }

    public function getWalletDepositYearUserProperty()
    {
        $data = [];

        $result = walletDeposit::join('wallets', 'wallets.id', 'wallet_deposits.wallet_id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            ->where('wallet_deposits.created_by', Auth::guard('api')->user()->id)
            ->whereDate('wallet_deposits.created_at', Carbon::now()->year)
            ->select(DB::raw('sum(wallet_deposits.amount) as cumul_amount_chiffre'))
            ->groupBy('wallet_deposits.created_at')
            ->get();

        foreach ($result as $sumDepos) {
            $data[] = $sumDepos->cumul_amount_chiffre;
        }

        return array_sum($data);
    }
    public function getWalletPaymentYearUserProperty()
    {
        $data = [];

        $result = WalletPayment::join('wallets', 'wallets.id', 'wallet_payments.wallet_id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            ->where('wallet_payments.created_by', Auth::guard('api')->user()->id)
            ->whereDate('wallet_payments.created_at', Carbon::now()->year)
            ->select(DB::raw('sum(wallet_payments.amount) as cumul_amount_chiffre'))
            ->groupBy('wallet_payments.created_at')
            ->get();

        foreach ($result as $sumPay) {
            $data[] = $sumPay->cumul_amount_chiffre;
        }

        return array_sum($data);
    }

    public function getWalletTransactionYearUserProperty()
    {
        $data = [];

        $result = WalletTransaction::join('wallets', 'wallets.id', 'wallet_transactions.wallet_id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            ->where('wallet_transactions.created_by', Auth::guard('api')->user()->id)
            ->whereDate('wallet_transactions.created_at', Carbon::now()->year)
            ->select(DB::raw('sum(wallet_transactions.amount) as cumul_amount_chiffre'))
            ->groupBy('wallet_transactions.created_at')
            ->get();

        foreach ($result as $sumTrans) {
            $data[] = $sumTrans->cumul_amount_chiffre;
        }

        return array_sum($data);
    }

    public function getWalletInterYearUserProperty()
    {
        $data = [];

        $result = WalletInter::join('wallets', 'wallets.id', 'wallet_inters.wallet_id')
            // ->where('wallet_inters.country_id', Auth::guard('api')->user()->country->id)
            ->where('wallet_inters.created_by', Auth::guard('api')->user()->id)
            ->whereDate('wallet_inters.created_at', Carbon::now()->year)
            ->select(DB::raw('sum(wallet_inters.amount) as cumul_amount_chiffre'))
            ->groupBy('wallet_inters.created_at')
            ->get();

        foreach ($result as $sumInter) {
            $data[] = $sumInter->cumul_amount_chiffre;
        }

        return array_sum($data);
    }

    public function getWalletPhoneCreditYearUserProperty()
    {
        $data = [];

        $result = WalletPhoneCredit::join('wallets', 'wallets.id', 'wallet_phone_credits.wallet_id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            ->where('wallet_phone_credits.created_by', Auth::guard('api')->user()->id)
            ->whereDate('wallet_phone_credits.created_at', Carbon::now()->year)
            ->select(DB::raw('sum(wallet_phone_credits.amount) as cumul_amount_chiffre'))
            ->groupBy('wallet_phone_credits.created_at')
            ->get();

        foreach ($result as $sumPhone) {
            $data[] = $sumPhone->cumul_amount_chiffre;
        }

        return array_sum($data);
    }

    public function getWalletReloadYearUserProperty()
    {
        $data = [];

        $result = WalletReload::join('wallets', 'wallets.id', '=', 'wallet_reloads.wallet_id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            ->where('wallet_reloads.created_by', Auth::guard('api')->user()->id)
            ->whereDate('wallet_reloads.created_at', Carbon::now()->year)
            ->select(DB::raw('sum(wallet_reloads.amount) as cumul_amount_chiffre'))
            ->groupBy('wallet_reloads.created_at')
            ->get();

        foreach ($result as $sumReload) {
            $data[] = $sumReload->cumul_amount_chiffre;
        }
        return array_sum($data);
    }
    // Transactions Days User
    public function getWalletBankAccountGainUserProperty()
    {

        $account_valeur = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            ->whereDate('wallet_banks.created_at', Carbon::now())
            ->where('wallet_banks.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_banks.status', 'SUCCESS')
            ->select('wallet_banks.*')
            ->count();

        return $account_valeur;
    }

    public function getWalletDepositAccountGainUserProperty()
    {
        $account_valeur  =  WalletDeposit::join(
            'wallets',
            'wallet_deposits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->whereDate('wallet_deposits.created_at', Carbon::now())
            ->where(
                'wallet_deposits.created_by',
                Auth::guard('api')->user()->id
            )
            ->where('wallet_deposits.status', 'SUCCESS')
            ->select('wallet_deposits.*')
            ->count();


        return $account_valeur;
    }

    public function getWalletPaymentAccountGainUserProperty()
    {
        $account_valeur =   WalletPayment::join(
            'wallets',
            'wallet_payments.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->whereDate('wallet_payments.created_at', Carbon::now())
            ->where(
                'wallet_payments.created_by',
                Auth::guard('api')->user()->id
            )
            ->where('wallet_payments.status', 'SUCCESS')
            ->select('wallet_payments.*')
            ->count();


        return $account_valeur;
    }

    public function getWalletTransactionAccountGainUserProperty()
    {
        $account_valeur =    WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->whereDate('wallet_transactions.created_at', Carbon::now())
            ->where(
                'wallet_transactions.created_by',
                Auth::guard('api')->user()->id
            )
            ->where('wallet_transactions.status', 'SUCCESS')
            ->select('wallet_transactions.*')
            ->count();


        return $account_valeur;
    }

    public function getWalletInterAccountGainUserProperty()
    {
        $account_valeur =    WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            // ->where('wallet_inters.country_id', Auth::guard('api')->user()->country->id)
            ->whereDate('wallet_inters.created_at', Carbon::now())
            ->where('wallet_inters.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_inters.status', 'SUCCESS')
            ->select('wallet_inters.*')
            ->count();

        return $account_valeur;
    }
    public function getWalletPhoneCreditAccountGainUserProperty()
    {
        $account_valeur =     WalletPhoneCredit::join(
            'wallets',
            'wallet_phone_credits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->whereDate('wallet_phone_credits.created_at', Carbon::now())
            ->where(
                'wallet_phone_credits.created_by',
                Auth::guard('api')->user()->id
            )
            ->where('wallet_phone_credits.status', 'SUCCESS')
            ->select('wallet_phone_credits.*')
            ->count();

        return $account_valeur;
    }

    public function getWalletReloadAccountGainUserProperty()
    {
        $account_valeur =   WalletReload::join(
            'wallets',
            'wallet_reloads.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->whereDate('wallet_reloads.created_at', Carbon::now())
            ->where(
                'wallet_reloads.created_by',
                Auth::guard('api')->user()->id
            )
            ->where('wallet_reloads.status', 'SUCCESS')
            ->select('wallet_reloads.*')
            ->count();

        return $account_valeur;
    }

    public function getWalletOrderBankAccountSumGainUserProperty()
    {
        $data = BankCard::join('wallets', 'bank_cards.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            ->whereDate('bank_cards.created_at', Carbon::now())
            ->where('bank_cards.created_by', Auth::guard('api')->user()->id)
            ->where('bank_cards.status', 1)
            ->select('bank_cards.*')
            ->count();
        return $data;
    }

    // Details Transaction User
    public function getTransactionErrorUserProperty()
    {
        $transactionError = WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->select('wallet_transactions.*')
            ->where(
                'wallet_transactions.created_by',
                Auth::guard('api')->user()->id
            )
            ->where('wallet_transactions.status', 'ERROR')
            ->whereDate('wallet_transactions.created_at', Carbon::now())
            ->count();
        return $transactionError;
    }

    public function getTransactionSuccessUserProperty()
    {
        $transactionSuccess = WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->select('wallet_transactions.*')
            ->where(
                'wallet_transactions.created_by',
                Auth::guard('api')->user()->id
            )
            ->where('wallet_transactions.status', 'SUCCESS')
            ->whereDate('wallet_transactions.created_at', Carbon::now())
            ->count();
        return $transactionSuccess;
    }

    public function getTransactionPenddingUserProperty()
    {
        $transactionPendding = WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->select('wallet_transactions.*')
            ->where(
                'wallet_transactions.created_by',
                Auth::guard('api')->user()->id
            )
            ->where('wallet_transactions.status', 'PENDING')
            ->whereDate('wallet_transactions.created_at', Carbon::now())
            ->count();
        return $transactionPendding;
    }

    public function getPaymentErrorUserProperty()
    {
        $paimentError = WalletPayment::join(
            'wallets',
            'wallet_payments.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'ERROR')
            ->where(
                'wallet_payments.created_by',
                Auth::guard('api')->user()->id
            )
            ->whereDate('wallet_payments.created_at', Carbon::now())
            ->count();
        return $paimentError;
    }

    public function getPaymentSuccessUserProperty()
    {
        $paimentSuccess = WalletPayment::join(
            'wallets',
            'wallet_payments.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'SUCCESS')
            ->where(
                'wallet_payments.created_by',
                Auth::guard('api')->user()->id
            )
            ->whereDate('wallet_payments.created_at', Carbon::now())
            ->count();
        return $paimentSuccess;
    }

    public function getPaymentPenddingUserProperty()
    {
        $paimentPendding = WalletPayment::join(
            'wallets',
            'wallet_payments.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'PENDING')
            ->where(
                'wallet_payments.created_by',
                Auth::guard('api')->user()->id
            )
            ->whereDate('wallet_payments.created_at', Carbon::now())
            ->count();
        return $paimentPendding;
    }

    public function getReloadErrorUserProperty()
    {
        $reloadError = WalletReload::join(
            'wallets',
            'wallet_reloads.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'ERROR')
            ->where('wallet_reloads.created_by', Auth::guard('api')->user()->id)
            ->whereDate('wallet_reloads.created_at', Carbon::now())
            ->count();
        return $reloadError;
    }

    public function getReloadSuccessUserProperty()
    {
        $reloadSuccess = WalletReload::join(
            'wallets',
            'wallet_reloads.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'SUCCESS')
            ->where('wallet_reloads.created_by', Auth::guard('api')->user()->id)
            ->whereDate('wallet_reloads.created_at', Carbon::now())
            ->count();
        return $reloadSuccess;
    }

    public function getReloadPenddingUserProperty()
    {
        $reloadPendding = WalletReload::join(
            'wallets',
            'wallet_reloads.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'PENDING')
            ->where('wallet_reloads.created_by', Auth::guard('api')->user()->id)
            ->whereDate('wallet_reloads.created_at', Carbon::now())
            ->count();
        return $reloadPendding;
    }

    public function getVirementSuccessUserProperty()
    {
        $virementSuccess = WalletBank::join(
            'wallets',
            'wallet_banks.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->select('wallet_banks.*')
            ->where('wallet_banks.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_banks.status', 'SUCCESS')
            ->whereDate('wallet_banks.created_at', Carbon::now())
            ->count();
        return $virementSuccess;
    }

    public function getVirementPenddingUserProperty()
    {
        $virementPendding = WalletBank::join(
            'wallets',
            'wallet_banks.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->select('wallet_banks.*')
            ->where('wallet_banks.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_banks.status', 'PENDING')
            ->whereDate('wallet_banks.created_at', Carbon::now())
            ->count();
        return $virementPendding;
    }

    public function getVirementErrorUserProperty()
    {
        $virementError = WalletBank::join(
            'wallets',
            'wallet_banks.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->select('wallet_banks.*')
            ->where('wallet_banks.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_banks.status', 'ERROR')
            ->whereDate('wallet_banks.created_at', Carbon::now())
            ->count();
        return $virementError;
    }

    public function getInterErrorUserProperty()
    {
        $interError = WalletInter::join(
            'wallets',
            'wallet_inters.wallet_id',
            'wallets.id'
        )
            // ->where(
            //     'wallet_inters.country_id',
            //     Auth::guard('api')->user()->country->id
            // )
            ->select('wallet_inters.*')
            ->where('wallet_inters.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_inters.status', 'ERROR')
            ->whereDate('wallet_inters.created_at', Carbon::now())
            ->count();
        return $interError;
    }

    public function getInterSuccessUserProperty()
    {
        $interSuccess = WalletInter::join(
            'wallets',
            'wallet_inters.wallet_id',
            'wallets.id'
        )
            // ->where(
            //     'wallet_inters.country_id',
            //     Auth::guard('api')->user()->country->id
            // )
            ->select('wallet_inters.*')
            ->where('wallet_inters.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_inters.status', 'SUCCESS')
            ->whereDate('wallet_inters.created_at', Carbon::now())
            ->count();
        return $interSuccess;
    }

    public function getInterPenddingUserProperty()
    {
        $interPendding = WalletInter::join(
            'wallets',
            'wallet_inters.wallet_id',
            'wallets.id'
        )
            // ->where(
            //     'wallet_inters.country_id',
            //     Auth::guard('api')->user()->country->id
            // )
            ->where('wallet_inters.created_by', Auth::guard('api')->user()->id)
            ->select('wallet_inters.*')
            ->where('wallet_inters.status', 'PENDING')
            ->whereDate('wallet_inters.created_at', Carbon::now())
            ->count();
        return $interPendding;
    }

    public function getPhoneCreditErrorUserProperty()
    {
        $phoneCreditError = WalletPhoneCredit::join(
            'wallets',
            'wallet_phone_credits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->select('wallet_phone_credits.*')
            ->where(
                'wallet_phone_credits.created_by',
                Auth::guard('api')->user()->id
            )
            ->where('wallet_phone_credits.status', 'ERROR')
            ->whereDate('wallet_phone_credits.created_at', Carbon::now())
            ->count();
        return $phoneCreditError;
    }

    public function getPhoneCreditSuccessUserProperty()
    {
        $phoneCreditSuccess = WalletPhoneCredit::join(
            'wallets',
            'wallet_phone_credits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->select('wallet_phone_credits.*')
            ->where(
                'wallet_phone_credits.created_by',
                Auth::guard('api')->user()->id
            )
            ->where('wallet_phone_credits.status', 'SUCCESS')
            ->whereDate('wallet_phone_credits.created_at', Carbon::now())
            ->count();
        return $phoneCreditSuccess;
    }

    public function getPhoneCreditPenddingUserProperty()
    {
        $phoneCreditPendding = WalletPhoneCredit::join(
            'wallets',
            'wallet_phone_credits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->where(
                'wallet_phone_credits.created_by',
                Auth::guard('api')->user()->id
            )
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.status', 'PENDING')
            ->whereDate('wallet_phone_credits.created_at', Carbon::now())
            ->count();
        return $phoneCreditPendding;
    }

    public function getDepositErrorUserProperty()
    {
        $depositError = WalletDeposit::join(
            'wallets',
            'wallet_deposits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->select('wallet_deposits.*')
            ->where(
                'wallet_deposits.created_by',
                Auth::guard('api')->user()->id
            )
            ->where('wallet_deposits.status', 'ERROR')
            ->whereDate('wallet_deposits.created_at', Carbon::now())
            ->count();
        return $depositError;
    }

    public function getDepositSuccessUserProperty()
    {
        $depositSuccess = WalletDeposit::join(
            'wallets',
            'wallet_deposits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->select('wallet_deposits.*')
            ->where(
                'wallet_deposits.created_by',
                Auth::guard('api')->user()->id
            )
            ->where('wallet_deposits.status', 'SUCCESS')
            ->whereDate('wallet_deposits.created_at', Carbon::now())
            ->count();
        return $depositSuccess;
    }

    public function getDepositPenddingUserProperty()
    {
        $depositPendding = WalletDeposit::join(
            'wallets',
            'wallet_deposits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('api')->user()->country->id
            )
            ->where(
                'wallet_deposits.created_by',
                Auth::guard('api')->user()->id
            )
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.status', 'PENDING')
            ->whereDate('wallet_deposits.created_at', Carbon::now())
            ->count();
        return $depositPendding;
    }

    //   State gains User

    public function getWalletOrderBankSumGainUserProperty()
    {
        $data = [];
        foreach (BankCard::join('wallets', 'bank_cards.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            // ->whereDate('bank_cards.created_at', Carbon::now())
            ->where('bank_cards.created_by', Auth::guard('api')->user()->id)
            ->where('bank_cards.status', 1)
            ->select('bank_cards.*')
            ->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletBankSumGainUserProperty()
    {
        $data = [];
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            // ->whereDate('wallet_banks.created_at', Carbon::now())
            ->where('wallet_banks.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_banks.status', 'SUCCESS')
            ->select('wallet_banks.*')
            ->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletDepositSumGainUserProperty()
    {
        $data = [];

        foreach (WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            // ->whereDate('wallet_deposits.created_at', Carbon::now())
            ->where('wallet_deposits.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_deposits.status', 'SUCCESS')
            ->select('wallet_deposits.*')
            ->get() as $bank) {

            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletPaymentSumGainUserProperty()
    {
        $data = [];
        foreach (WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            // ->whereDate('wallet_payments.created_at', Carbon::now())
            ->where('wallet_payments.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_payments.status', 'SUCCESS')
            ->select('wallet_payments.*')
            ->get()  as $bank) {

            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletTransactionSumGainUserProperty()
    {
        $data = [];
        foreach (WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
        )
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            // ->whereDate('wallet_transactions.created_at', Carbon::now())
            ->where('wallet_transactions.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_transactions.status', 'SUCCESS')
            ->select('wallet_transactions.*')
            ->get()  as $bank) {

            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletInterSumGainUserProperty()
    {
        $data = [];
        foreach (WalletInter::join(
            'wallets',
            'wallet_inters.wallet_id',
            'wallets.id'
        )
            // ->where('wallet_inters.country_id', Auth::guard('api')->user()->country->id)
            // ->whereDate('wallet_inters.created_at', Carbon::now())
            ->where('wallet_inters.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_inters.status', 'SUCCESS')
            ->select('wallet_inters.*')
            ->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }
    public function getWalletPhoneCreditSumGainUserProperty()
    {
        $data = [];
        foreach (WalletPhoneCredit::join(
            'wallets',
            'wallet_phone_credits.wallet_id',
            'wallets.id'
        )
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            // ->whereDate('wallet_phone_credits.created_at', Carbon::now())
            ->where('wallet_phone_credits.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_phone_credits.status', 'SUCCESS')
            ->select('wallet_phone_credits.*')
            ->get()
            as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletReloadSumGainUserProperty()
    {
        $data = [];
        foreach (WalletReload::join(
            'wallets',
            'wallet_reloads.wallet_id',
            'wallets.id'
        )
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            // ->whereDate('wallet_reloads.created_at', Carbon::now())
            ->where('wallet_reloads.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_reloads.status', 'SUCCESS')
            ->select('wallet_reloads.*')
            ->get()
            as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }
    //  State gains user Year
    public function getWalletBankYearSumGainUserProperty()
    {
        $data = [];
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            ->whereDate('wallet_banks.created_at', Carbon::now()->year)
            ->where('wallet_banks.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_banks.status', 'SUCCESS')
            ->select('wallet_banks.*')
            ->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletDepositYearSumGainUserProperty()
    {
        $data = [];

        foreach (WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            ->whereDate('wallet_deposits.created_at', Carbon::now()->year)
            ->where('wallet_deposits.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_deposits.status', 'SUCCESS')
            ->select('wallet_deposits.*')
            ->get() as $bank) {

            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletPaymentYearSumGainUserProperty()
    {
        $data = [];
        foreach (WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            ->whereDate('wallet_payments.created_at', Carbon::now()->year)
            ->where('wallet_payments.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_payments.status', 'SUCCESS')
            ->select('wallet_payments.*')
            ->get()  as $bank) {

            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletTransactionYearSumGainUserProperty()
    {
        $data = [];
        foreach (WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
        )
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            ->whereDate('wallet_transactions.created_at', Carbon::now()->year)
            ->where('wallet_transactions.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_transactions.status', 'SUCCESS')
            ->select('wallet_transactions.*')
            ->get()  as $bank) {

            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletInterYearSumGainUserProperty()
    {
        $data = [];
        foreach (WalletInter::join(
            'wallets',
            'wallet_inters.wallet_id',
            'wallets.id'
        )
            // ->where('wallet_inters.country_id', Auth::guard('api')->user()->country->id)
            ->whereDate('wallet_inters.created_at', Carbon::now()->year)
            ->where('wallet_inters.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_inters.status', 'SUCCESS')
            ->select('wallet_inters.*')
            ->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }
    public function getWalletPhoneCreditYearSumGainUserProperty()
    {
        $data = [];
        foreach (WalletPhoneCredit::join(
            'wallets',
            'wallet_phone_credits.wallet_id',
            'wallets.id'
        )
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            ->whereDate('wallet_phone_credits.created_at', Carbon::now()->year)
            ->where('wallet_phone_credits.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_phone_credits.status', 'SUCCESS')
            ->select('wallet_phone_credits.*')
            ->get()
            as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletReloadYearSumGainUserProperty()
    {
        $data = [];
        foreach (WalletReload::join(
            'wallets',
            'wallet_reloads.wallet_id',
            'wallets.id'
        )
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            ->whereDate('wallet_reloads.created_at', Carbon::now()->year)
            ->where('wallet_reloads.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_reloads.status', 'SUCCESS')
            ->select('wallet_reloads.*')
            ->get()
            as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }
    //   State gains User 30 days later
    public function getWalletBankMonthSumGainUserProperty()
    {
        $data = [];
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            // ->whereDate('wallet_banks.created_at', '>=', Carbon::now()->subDays(30))
            ->where('wallet_banks.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_banks.status', 'SUCCESS')
            ->select('wallet_banks.*')
            ->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletDepositMonthSumGainUserProperty()
    {
        $data = [];

        foreach (WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            // ->whereDate('wallet_deposits.created_at', '>=', Carbon::now()->subDays(30))
            ->where('wallet_deposits.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_deposits.status', 'SUCCESS')
            ->select('wallet_deposits.*')
            ->get() as $bank) {

            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletPaymentMonthSumGainUserProperty()
    {
        $data = [];
        foreach (WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            // ->whereDate('wallet_payments.created_at', '>=', Carbon::now()->subDays(30))
            ->where('wallet_payments.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_payments.status', 'SUCCESS')
            ->select('wallet_payments.*')
            ->get()  as $bank) {

            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletTransactionMonthSumGainUserProperty()
    {
        $data = [];
        foreach (WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
        )
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            // ->whereDate('wallet_transactions.created_at', '>=', Carbon::now()->subDays(30))
            ->where('wallet_transactions.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_transactions.status', 'SUCCESS')
            ->select('wallet_transactions.*')
            ->get()  as $bank) {

            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletInterMonthSumGainUserProperty()
    {
        $data = [];
        foreach (WalletInter::join(
            'wallets',
            'wallet_inters.wallet_id',
            'wallets.id'
        )
            // ->where('wallet_inters.country_id', Auth::guard('api')->user()->country->id)
            // ->whereDate('wallet_inters.created_at', '>=', Carbon::now()->subDays(30))
            ->where('wallet_inters.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_inters.status', 'SUCCESS')
            ->select('wallet_inters.*')
            ->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }
    public function getWalletPhoneCreditMonthSumGainUserProperty()
    {
        $data = [];
        foreach (WalletPhoneCredit::join(
            'wallets',
            'wallet_phone_credits.wallet_id',
            'wallets.id'
        )
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            // ->whereDate('wallet_phone_credits.created_at', '>=', Carbon::now()->subDays(30))
            ->where('wallet_phone_credits.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_phone_credits.status', 'SUCCESS')
            ->select('wallet_phone_credits.*')
            ->get()
            as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletReloadMonthSumGainUserProperty()
    {
        $data = [];
        foreach (WalletReload::join(
            'wallets',
            'wallet_reloads.wallet_id',
            'wallets.id'
        )
            ->where('wallets.country_id', Auth::guard('api')->user()->country->id)
            // ->whereDate('wallet_reloads.created_at', '>=', Carbon::now()->subDays(30))
            ->where('wallet_reloads.created_by', Auth::guard('api')->user()->id)
            ->where('wallet_reloads.status', 'SUCCESS')
            ->select('wallet_reloads.*')
            ->get()
            as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }
}