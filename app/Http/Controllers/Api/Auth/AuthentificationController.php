<?php

/** @noinspection PhpUndefinedClassInspection */

namespace App\Http\Controllers\Api\Auth;

use App\Events\CustumersEvent;
use App\Http\Controllers\Admin\MailController;
use App\Http\Controllers\Controller;
use App\Http\Services\AuthenticationService;
use App\Mail\UpdatePassword;
use App\Models\AppComZoneStatus;
use App\Models\AppMasterStatus;
use App\Models\AppStatus;
use App\Models\Custumers\City;
use App\Models\Custumers\Country;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Notifications\UserNotification;
use App\Repositories\RegisterRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthentificationController extends Controller
{

    /** @var  RegisterRepository */
    private $registerRepository;
    private $authService;

    public function __construct(RegisterRepository $registerReposi, AuthenticationService $authService)
    {
        $this->registerRepository = $registerReposi;
        $this->authService = $authService;
    }

    /**
     * @OA\POST(
     *      path="/auth/signUp",
     *      tags={"Phone register"},
     *      summary="inform your phone",
     *      description="Renseignez votre numero de telephone le pays et la villes",
     *  @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="phone", type="number", example="0678787878"),
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
     *                          property="Countrie",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          property="Phone",
     *                          type="number"
     *                      ),
     *                    @OA\Property(
     *                          property="Citie",
     *                          type="integer"
     *                      ),
     *                 ),
     *                 example={
     *                     "phone":"0546963728",
     *                     "Pays":"107",
     *                     "Ville": "09"
     *                }
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="phone is required",
     *      ),
     *      @OA\Response(
     *          response=333,
     *          description="User already exists"
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
    public function signUp(Request $request)
    {

        $user = $this->registerRepository->where('phone', $request->phone)->withTrashed()->first();
        $user_email = $this->registerRepository->where('email', $request->email)->withTrashed()->first();
        $citie = City::whereName($request->citie)->whereStatus(true)->first();
        $country = Country::whereName($request->country)->whereRobus(true)->first();
        $rt = rand(40, 5000) * time();
        $activation_code = sprintf("%0.4s", str_shuffle($rt));

        try {

            $validator = Validator::make(

                $request->all(),
                [
                    'phone' => 'required',
                    'country' => 'required',
                    'citie' => 'required',
                ],
                [

                    'phone.required' => 'Votre numero est obligatoire',
                    'country.required' => 'Votre pays est obligatoire',
                    'citie.required' => 'Votre pays est obligatoire',

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

            $verify_phone =  SidUser::wherePhone($country->indecative . $request->phone)->withTrashed()->first();

            if ($request->has('phone') ||  $request->has('email')) {

                if ($user != null || $user_email != null || $verify_phone) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Utilisateur existe.',
                        'data' => null
                    ], 333);
                }

                if (!$request->has('phone') || !$request->has('email')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'telephone et email est requis',
                        'data' => null
                    ], 401);
                }


                $user = $this->registerRepository->create([
                    'country_id'  => $country->id,
                    'company_id'  => 1,
                    'city_id'  => $citie->id,
                    'phone'  => $request->phone,
                    'email'  => $request->email,
                    'status' => false,
                    'is_custumers' => false,
                    'is_active' => false,
                    'verification_code' =>  rand(100000, 999999),
                    'activation_code' =>  $activation_code
                ]);


                if ($user->country->code == "CI") {

                    $message = 'Code activation ' . $user->activation_code;

                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$user->phone&from=ROBUS&sms=$message");
                }

                $notify = new MailController;
                $notify->newOtp($user);

                return response()->json([
                    'success' => true,
                    'message' => "Le numero est cree avec success.",
                    'data'  => $user->status ?? null,
                ], 200);
            } else {

                //User already exist
                abort(response()->json(
                    [
                        'success' => false,
                        'message' => 'Operation failed',
                        'data' => '',
                    ],
                    433
                ));
            }
        } catch (\Throwable $th) {

            return response()->json(
                [
                    'success' => false,
                    'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
                ],
                301
            );
        }
    }


    /**
     * Registration
     *
     * register new user of Phone
     * @method POST
     * @param  mixed $request:
     * @return
     *      code: 233 message :  please fill in the fields
     *      code: 433 message : Email et username required
     *      code: 333 message : Use already exists !!
     *      code: 200 message : Register success + $token
     *      code: 301 message : Username d'n't existe !!
     */

    private function verifyActivation()
    {
        return request()->validate([
            'activation_code' => 'required|exists:sid_users,activation_code'
        ]);
    }

    /**
     * @OA\POST(
     *      path="/auth/active_account",
     *      tags={"Phone register"},
     *      summary="Update Account",
     *      description="Validation du code Otp et renseignement des informations du clients",
     * @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="Otp", type="number", example="1234"),
     *              @OA\Property(property="firstname", type="string", example="Sabdy"),
     *              @OA\Property(property="lastname", type="string", example="Dev"),
     *              @OA\Property(property="password", type="password", example="password"),
     *              @OA\Property(property="zone", type="string", example="Cocody"),
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
     *                          property="OTP",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          property="Firstname",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="Lastname",
     *                          type="string"
     *                      ),
     *                    @OA\Property(
     *                          property="Password",
     *                          type="password"
     *                      ),
     *                  @OA\Property(
     *                          property="Zone",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={
     *                     "Fisrtname":"0546963728",
     *                     "Lastname":"107",
     *                     "Password": "password",
     *                     "Zone": "cocody",
     *                }
     *             )
     *         )
     *      ),
     *   @OA\Response(
     *          response=333,
     *          description="Le code ne correspond pas."
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
    public function activatePhone(Request $request)
    {

        $this->verifyActivation();

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $walletCode = sprintf("%0.6s", str_shuffle($rt . $tr));

        $status =  SidUser::whereActivationCode(request()->activation_code)->exists();

        $user_email = SidUser::where('email', $request->email)->withTrashed()->first();

        $user_phone = SidUser::where('phone', $request->phone)->withTrashed()->first();

        try {

            if ($status) {

                $validator = Validator::make(

                    $request->all(),
                    [
                        'firstname' => 'required',
                        'lastname' => 'required',
                    ],
                    [

                        'firstname.required' => 'Votre Nom est obligatoire',
                        'lastname.required' => 'Votre Prenom est obligatoire',

                    ]

                );

                if ($validator->fails()) {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'Champ est requis ',
                            'data' => $validator->errors()
                        ],
                        333
                    );
                }

                $user = SidUser::whereActivationCode(request()->activation_code)
                    ->first();

                if ($user->activation_code != request()->activation_code) {

                    return response()->json([
                        'success' => false,
                        'message' => 'Le code ne correspond pas.'
                    ], 333);
                } else {


                    $user->update([
                        'status' => true,
                        'is_custumers' => true,
                        'silver_ceiling' =>  $user->country->silver_ceiling,
                    ]);


                    if ($user->status == true) {
                        // retrieve callsign

                        $encode = substr($user->phone, 0, 1);

                        if ($encode == '+') {

                            return response()->json(
                                [
                                    'success' => false,
                                    'message' => 'Le compte a été deja creer ',
                                ],
                                401
                            );
                        }

                        if ($user->country->code == "CI") {

                            if ($user_email != null) {
                                return response()->json([
                                    'success' => false,
                                    'message' => 'Adresse email existe déjà.',
                                    'data' => null,
                                ]);
                            }
                        }


                        $wallet_user = Wallet::create([
                            'company_id' =>  1,
                            'wallet_id' =>   $user->country->code . $walletCode,
                            'country_id' =>  $user->country->id,
                            'created_by' =>  $user->id,
                            'sid_user_id' =>  $user->id,
                            'currencie_id' =>  $user->country->currencie->id,
                            'amount' => 0.0,
                            'reload_by' => $user->firstname . ' ' . $user->lastname,
                            'status' => true,
                        ]);


                        $user->update([
                            'firstname'  => $request->firstname,
                            'lastname'  => $request->lastname,
                            'email' =>  $user->email,
                            'password' =>  bcrypt($request->password),
                            'zone'  =>  $request->zone ?? null,
                            'wallet_id'  => $wallet_user->wallet_id,
                            'phone'  =>   $user->country->indecative . $user->phone,
                            'created_by' => $user->id,
                            'activation_code' => null,
                            'is_active' => false,
                            'activation_code' => null,
                        ]);



                        $tokenResult = $user->createToken('auth_token');

                        $msg_title = 'Inscriptions chez Robus';
                        $msg = 'Votre compte a ete cree avec succès, veuillez attendre la validation de votre compte.';
                        $user->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                        $notifs = $user->unreadNotifications;
                        event(new CustumersEvent($notifs));

                        return $this->loginSuccess($tokenResult, $user);
                    } else {
                        return response()->json(
                            [
                                'success' => false,
                                'message' => 'Erreur d\'inscription sur ROBUS',
                            ],
                            301
                        );
                    }
                }
            } else return response()->json(
                [
                    'success' => false,
                    'message' => 'Mauvais identifiants ! Authentification échouée',
                    'data' => 'code active do not exist:' . $request->activation_code
                ],
                401
            );
        } catch (\Throwable $th) {

            return response()->json(
                [
                    'success' => false,
                    'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
                ],
                301
            );
        }
    }

    /**
     * @OA\POST(
     *      path="/auth/login",
     *      tags={"Connexion"},
     *      summary="Connexion",
     *      description="Se connectez a son mot de passe et numero",
     *  @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="phone", type="number", example="060546963728"),
     *              @OA\Property(property="password", type="password", example="password"),
     *              @OA\Property(property="created_at_token", type="string", example="gbcwdqdffs125225gs")
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
     *                    
     *                    @OA\Property(
     *                          property="Password",
     *                          type="password"
     *                      ),
     *                 ),
     *                 example={
     *                     "phone":"0546963728",
     *                     "Password": "password",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=301,
     *          description="Unauthenticated",
     *      ),
     *   @OA\Response(
     *          response=333,
     *          description="Le code ne correspond pas."
     *      ),
     * @OA\Response(
     *      response=433,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  ),
     */
    public function loginCustumers(Request $request)
    {
        $user = SidUser::wherePhone($request->phone)->first();

        try {

            // Vérifier l'état de l'application
            $appStatusResult = $this->authService->appDispoRobus(AppStatus::class);

            if (!$appStatusResult['success']) {
                return response()->json($appStatusResult, 301);
            }

            if (!empty($user->forget_password)) {

                $credentials = $request->only(['phone', 'password']);

                if ($sidUser = SidUser::where($credentials)->first()) {

                    $user = $sidUser;
                    $user->fcm_token = $request->input('fcm_token'); // Utilisation de la fonction input() avec une valeur par défaut
                    $user->last_login = Carbon::now(); // Mise à jour de la dernière connexion
                    $user->last_logout = null; // Réinitialisation de la dernière déconnexion
                    $user->save();

                    if (!$user_data = $this->registerRepository->whereStatus(true)->where('is_custumers', true)->wherePhone($credentials['phone'])->first()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Mauvais identifiants ! Authentification échouée',
                            'data' => 'ne pas couper:' . $credentials['phone']
                        ], 301);
                    }

                    $tokenResult = $user_data->createToken('auth_token');
                    $message = 'Une fois connecté, veuillez modifier votre mot de passe temporaire dans votre profil';
                    $recipient = $user->country->code == 'CI' ? '225' . substr($user->phone, -10) : $user->email;

                    if ($user->country->code == 'CI') {
                        Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=$recipient&from=ROBUS&sms=$message");
                    } else {
                        Mail::to($recipient)->send(new UpdatePassword($message));
                    }

                    return $this->loginSuccess($tokenResult, $user_data);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Numéro de téléphone ou mot de passe incorrect.',
                        'data' => 'ne pas couper'
                    ], 301);
                }
            } else {

                $credentials = request(['phone', 'password']);

                $result = $this->authService->custumerLogin($credentials['phone'], $credentials['password']);

                if ($result['success']) {

                    return $this->loginSuccess($result['token'], $result['user']);
                    
                } else {
                    return response()->json(
                        ['success' => false, 'message' => $result['message']],
                        301
                    );
                }
            }
        } catch (\Throwable $th) {

            return response()->json(
                [
                    'success' => false,
                    'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
                ],
                301
            );
        }
    }

    public function loginAll(Request $request, $loginMethod)
    {
        try {

            $email = $request->input('email');
            $password = $request->input('password');
            $device_meta_data = $request->input('device_meta_data');

            $result = $this->authService->$loginMethod($email, $password , $device_meta_data);

            if ($result['success']) {
                return $this->loginSuccess($result['token'], $result['user']);
            } else {
                return response()->json(
                    ['success' => false, 'message' => $result['message']],
                    301
                );
            }
        } catch (\Throwable $th) {
            return response()->json(
                ['success' => false, 'message' => 'Une erreur s\'est produite lors de l\'authentification.'],
                301
            );
        }
    }


    public function loginMaster(Request $request)
    {
        // Vérifier l'état de l'application
        $appStatusResult = $this->authService->appDispoMasterRobus(AppMasterStatus::class);

        if (!$appStatusResult['success']) {
            return response()->json($appStatusResult, 200);
        }

        return $this->loginAll($request, 'masterLogin');
    }

    public function loginComZone(Request $request)
    {
        $appStatusResult = $this->authService->appDispoComZoneRobus(AppComZoneStatus::class);

        if (!$appStatusResult['success']) {
            return response()->json($appStatusResult, 200);
        }

        return $this->loginAll($request, 'comZoneLogin');
    }

    public function loginUnderDistributor(Request $request)
    {

        // Vérifier l'état de l'application
        $appStatusResult = $this->authService->appDispoRobus(AppStatus::class);

        if (!$appStatusResult['success']) {
            return response()->json($appStatusResult, 200);
        }

        return $this->loginAll($request, 'underDistributorLogin');
    }



    /**
     * Verify
     * login with username or email
     * @method POST
     * @param  mixed $request
     * @return
     *      code: 210 message: No user found with this email address. !!
     *      code: 215 message: Le code ne correspond pas.
     *      code: 422 message: Wrong credentials, user not found
     *      code : 301 'Mauvais identifiants ! Authentification échouée
     *      code : 400 message : Verify compte
     */
    public function verify(Request $request)
    {

        $user = SidUser::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No user found with this email address.'
            ], 210);
        }
        if ($user->verification_code != $request->code) {
            return response()->json([
                'success' => false,
                'message' => 'Le code ne correspond pas.'
            ], 215);
        } else {
            $user->email_verified_at = date('Y-m-d H:m:s');
            $user->save();
            $tokenResult = $user->createToken('auth_token');
            return $this->loginSuccess($tokenResult, $user);
        }
    }
    /**
     * @OA\POST(
     *      path="/auth/resendCode",
     *      tags={"Phone register"},
     *      summary="Resend",
     *      description="Renvoyer le code OTP",
     *  @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="phone", type="number", example="060546963728"),
     *          )
     *     ),
     * 
     * @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="phone",
     *                          type="number"
     *                      ),
     *                 ),
     *                 example={
     *                     "phone":"0546963728",
     *                }
     *             )
     *         )
     *      ), 
     *   @OA\Response(
     *          response=301,
     *          description="Unauthenticated",
     *      ),
     *   @OA\Response(
     *          response=333,
     *          description="Le code ne correspond pas."
     *      ),
     * @OA\Response(
     *      response=433,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  ),
     */
    public function resendCode(Request $request)
    {

        $rt = rand(40, 5000) * time();
        $activation_code = sprintf("%0.4s", str_shuffle($rt));
        $phone = $request->phone;
        $email = $request->email;
        $resend_user =  SidUser::wherePhone($phone)->first();
        $resend_user_email =  SidUser::wherePhone($email)->first();


        // if ($resend_user != null) {
        try {


            if ($resend_user != null || $resend_user_email  != null) {

                if ($resend_user->country->code == "CI") {

                    $resend_user->update([
                        'activation_code' =>  $activation_code
                    ]);

                    $message = 'Code activation ' . $resend_user->activation_code;

                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$resend_user->phone&from=ROBUS&sms=$message");
                } else {

                    $resend_user_email->update([
                        'activation_code' =>  $activation_code
                    ]);

                    $notify = new MailController;
                    $notify->newOtp($resend_user);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Votre code d\actualisation a ete envoyer.'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Votre code doit être validé.'
                ], 220);
            }
        } catch (\Throwable $th) {

            return response()->json(
                [
                    'success' => false,
                    'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
                ],
                301
            );
        }
    }

    /**
     * @OA\POST(
     *      path="/auth/forget/create",
     *      tags={"Forget password"},
     *      summary="Forget Password",
     *      description="Mot de passe oublier",
     *  @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="email", type="string", example="sabdydev@gmail.com"),
     *          )
     *     ),
     * @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={
     *                     "email":"sabdydev@gmail.com",
     *                }
     *             )
     *         )
     *      ), 
     *  @OA\Response(
     *          response=301,
     *          description="Unauthenticated",
     *      ),
     *   @OA\Response(
     *          response=333,
     *          description="Le code ne correspond pas."
     *      ),
     * @OA\Response(
     *      response=433,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  ),
     */

    public function forgotPassword(Request $request)
    {

        $country = Country::whereId($request->country)->whereRobus(true)->first();

        $rt = rand(40, 5000) * time();
        $activation_code = sprintf("%0.4s", str_shuffle($rt));

        try {
            if ($country) {
                if ($country->code == "CI") {
                    $user = SidUser::wherePhone('+225' . $request->phone)->first();
                    if (!empty($user)) {
                        $user->update(['forget_password' => $activation_code]);
                        $message = 'Utilisez ce mot de passe temporaire pour vous connectez ' .  $activation_code;
                        $phone = $request->phone;
                        Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$phone&from=ROBUS&sms=$message");
                        DB::commit();
                    }
                } else {
                    $user = SidUser::whereEmail($request->phone)->first();
                    if (!empty($user)) {
                        $user->update(['forget_password' => $activation_code]);
                        Mail::to($request->phone)->send(new UpdatePassword('Utilisez ce mot de passe temporaire pour vous connectez ' .  $activation_code));
                    }
                }
                return response()->json(
                    [
                        'success' =>  true,
                        'message' => 'Code envoyée'
                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Utilisateur inexistant'
                    ],
                    200
                );
            }
        } catch (ModelNotFoundException $ex) {

            abort(response()->json(
                [
                    'success' => false,
                    'message' => 'Invalid email',
                    'data' => $request->phone . 'not found'
                ],
                422,
            ));
        }
    }

    /**
     * @OA\POST(
     *      path="/auth/logout",
     *      tags={"Connexion"},
     *      summary="Deconnexion",
     *      description="Se deconnectez",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  ),
     */
    public function logout(Request $request)
    {
        Auth::guard('api')->user()->last_logout  = Carbon::now()->toDateTimeString();
        Auth::guard('api')->user()->save();
        $request->user()->token()->revoke();
        $request->user()->token()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie'
        ]);
    }

    protected function loginSuccess($tokenResult, $user)
    {
        $token = $tokenResult->token;
        $token->save();
        return response()->json([
            'success' => true,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'verified' => true,
            'message' => 'Success',
            'user' => [
                'id' => $user->id,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'phone' => $user->phone,
                'wallet_id' => $user->wallet_id,
            ]
        ]);
    }
}
