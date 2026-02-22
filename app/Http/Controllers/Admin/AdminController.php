<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\MailController;
use App\Http\Controllers\Controller;
use App\Http\Services\AuthenticationService;
use App\Http\Services\TransactionsAccountService;
use App\Http\Services\UserCreateService;
use App\Http\Services\ValidatorService;
use App\Mail\CodeValidated;
use App\Mail\NewAdmin;
use App\Mail\OtpValidated;
use App\Models\AdminsCom;
use App\Models\AppCompanyStatus;
use App\Models\IntegrationClient;
use App\Models\Banks\BankCard;
use App\Models\Notification;
use App\Models\Company;
use App\Models\Custumers\City;
use App\Models\Custumers\Country;
use App\Models\Custumers\Zone;
use App\Models\Kyc;
use App\Models\Master;
use App\Models\PercentCompanie;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletTransaction;
use App\Models\Wallets\WalletVersement;
use App\Models\Wallets\WalletPush;
use App\Models\Wallets\WalletDjamo;
use App\Models\Wallets\WalletTresortMoney;
use App\Models\Wallets\WalletWave;
use App\Models\Wallets\WalletXbet;
use App\Models\Wallets\WalletBankSend;
use App\Models\WithMerchant;
use App\Notifications\UserNotification;
use App\Rules\MatchOldPassword;
use App\Tools\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;

class AdminController extends Controller
{


    protected $transactionAccountService;
    protected $userValidatorService;
    protected $createUserService;
    private $authService;
    protected $appStatusResult;

    public function __construct(
        TransactionsAccountService $transactionAccountService,
        ValidatorService $userValidatorService,
        UserCreateService $createUserService,
        AuthenticationService $authService
    ) {
        $this->transactionAccountService = $transactionAccountService;
        $this->userValidatorService = $userValidatorService;
        $this->createUserService = $createUserService;
        $this->authService = $authService;
    }

    // Company Infos

    public function home_company()
    {

        return view('home_company');
    }


    public function home_masters()
    {
        return view('home_masters');
    }


    public function home_com_zones()
    {
        return view('home_comzone');
    }


    public function browse_activites()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-administrateur')) {
            abort(403);
        }
        return view('pages.admin.activities');
    }

    public function browse_activites_movements()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-administrateur')) {
            abort(403);
        }
        return view('pages.admin.activities_movements');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_edit(Request $request, int $id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('modifier-utilisateur')) {


            $user = SidUser::whereId($id)->first();

            $kycs = $user->kyc()->get() ?? null;

            $countrieId = $user->country_id;

            $wallet_solde = Wallet::where('sid_user_id', $user->id)->first();

            $countrie = Country::whereId($countrieId)->first();

            $cities = City::where('country_id', $countrieId)->get();

            return view('pages.custumers.show_under_edit', compact(
                'countrie',
                'cities',
                'kycs',
                'user',
                'wallet_solde'
            ));
        } else {
            abort(403);
        }
    }

    public function check(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:sid_users,email',
            'password' => 'required',

        ], [
            'email.exists' => 'Ce mail n\'existe pas dans chez nous '
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.login'))
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de connexion')
                ->withInput();
        }

        $creds = $request->only('email', 'password');

        try {


            $msg = 'Bienvenu chez ROBUS';
            $color = 'success';


            if (Auth::guard('web')->attempt($creds)) {

                return redirect(route('/'))->with('color', $color)->with('status', $msg);
            } else {
                return redirect(route('admin.login'))
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Erreur de connexion verifier votre mot de passe ou email')
                    ->withInput();
            }
        } catch (\Throwable $th) {
            return redirect(route('admin.login'))
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de connexion')
                ->withInput();
        }
    }

    public function edit_unders($id, Request $request)
    {


        $validator = $this->userValidatorService->validateUserUpdateDataAdmin($request->all());

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Champ requise')
                ->withInput();
        }


        try {


            $response = $this->createUserService->updateUsersWeb(
                $id,
                $request->all(),
                $request->file('image'),
                $request->file('image_recto'),
                $request->file('image_verso')
            );

            $msg = " Modification effectuée avec succès ";

            if ($response['success']) {

                $user = $response['user'];

                $valeur_message = ' Profil ';

                Mail::to($user->email)->send(new NewAdmin('Vous etes désormais ' . $valeur_message . env('APP_NAME') .
                    ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

                $msg = "Modification de profil avec succès";
                $color = 'success';

                if ($user->is_api) {
                    return redirect(route('users.prospects.browse'))->with('color', $color)->with('status', $msg);
                } else if ($user->is_distributer) {
                    return redirect(route('operators.admins.browse'))->with('color', $color)->with('status', $msg);
                } else {
                    return redirect(route('unders.admins.browse'))->with('color', $color)->with('status', $msg);
                }
            } else {

                $message = $response['message'];

                return back()
                    ->with('color', 'danger')
                    ->with('status', $message)
                    ->withInput();
            }
        } catch (\Throwable $th) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de création')
                ->withInput();
        }
    }

    public function browse(Request $request)
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('utilisateurs')) {
            abort(403);
        }

        return view('pages.admin.browse');
    }

    public function browse_gain(Request $request)
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-administrateur')) {
            abort(403);
        }


        return view('pages.admin.gains');
    }

    public function browse_gain_visitors()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-administrateur')) {
            abort(403);
        }


        return view('pages.admin.gain_visitors');
    }

    public function browse_gain_distributer(Request $request)
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-administrateur')) {
            abort(403);
        }


        return view('pages.admin.gain_distributer');
    }

    public function browse_gain_under_distributer()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-administrateur')) {
            abort(403);
        }


        return view('pages.admin.gain_under_distributer');
    }

    public function UpdatePassword_prospect(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);


        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de modification')
                ->withInput();
        }


        SidUser::find(propect()->id)->update(['password' => Hash::make($request->new_password)]);

        $notify = new MailController;
        $notify->modifyPassword($request);


        return back()->with('status', 'Mise a jour effectuée avec succès');
    }
    public function UpdatePassword_companie(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);


        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de modification')
                ->withInput();
        }


        SidUser::find(firm()->id)->update(['password' => Hash::make($request->new_password)]);

        $notify = new MailController;
        $notify->modifyPassword($request);


        return back()->with('status', 'Mise a jour effectuée avec succès');
    }

    public function update_phone(int $id, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'new_phone' => ['required'],
            // 'password_confirmation' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Verifier le champ saisie')
                ->withInput();
        }


        try {

            $user_info =  SidUser::whereId($id)->first();

            $verify_phone =  SidUser::wherePhone($user_info->country->indecative . $request->new_phone)->first();

            if ($verify_phone) {
                return back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Utilisateur existe déja ')
                    ->withInput();
            } else {

                SidUser::find($id)->update(['phone' => $user_info->country->indecative . $request->new_phone]);

                Mail::to($user_info->email)->send(new NewAdmin('Vote numero de telephone a été modifier par' . env('APP_NAME') .
                    ' Votre email : '  . $user_info->email . ' Votre nouveau numero : ' . $request->new_phone));

                return back()->with('status', 'Numero de téléphone modifié avec succès');
            }
        } catch (\Throwable $th) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de création')
                ->withInput();
        }
    }

    public function update_email(int $id, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'new_email' => ['required'],
            // 'password_confirmation' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Verifier le champ saisie')
                ->withInput();
        }


        try {

            $user_info =  SidUser::whereId($id)->first();

            $verify_email =  SidUser::whereEmail($request->new_email)->first();

            if ($verify_email) {
                return back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Utilisateur existe déja ')
                    ->withInput();
            } else {

                SidUser::find($id)->update(['email' => $request->new_email]);

                Mail::to($user_info->email)->send(new NewAdmin('Votre email a été modifier par' . env('APP_NAME') .
                    ' Votre nouveau email : '  . $request->new_email . ' Votre nouveau numero : ' . $user_info->phone));

                return back()->with('status', 'Email modifié  avec succès');
            }
        } catch (\Throwable $th) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de création')
                ->withInput();
        }
    }

    public function updatePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);


        if ($validator->fails()) {
            return redirect('account/security')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de modification')
                ->withInput();
        }


        SidUser::find(auth('web')->user()->id)->update(['password' => Hash::make($request->new_password)]);

        $notify = new MailController;
        $notify->modifyPassword($request);


        return redirect('account/security')->with('status', 'Mise a jour effectuée avec succès');
    }


    public function add()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('ajouter-administrateur')) {
            abort(403);
        }
        // $roles = Role::orderBy('created_at', 'desc')->get();
        $roles = Role::whereId(5)->orderBy('created_at', 'desc')->first();

        $countries = Country::whereRobus(true)
            ->where('indecative', '!=', null)->get();
        return view('pages.admin.add', compact('roles', 'countries'));
    }

    public function add_company()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('ajouter-administrateur')) {
            abort(403);
        }
        $roles = Role::orderBy('created_at', 'desc')->whereId(6)->get();
        $percentCompanies = PercentCompanie::orderBy('created_at', 'desc')->get();
        $countries = Country::whereRobus(true)
            ->where('indecative', '!=', null)->get();
        return view('pages.admin.add_company', compact('roles', 'countries', 'percentCompanies'));
    }

    public function edit_company($id)
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('ajouter-administrateur')) {
            abort(403);
        }

        $roles = Role::orderBy('created_at', 'desc')->whereId(6)->get();
        $percentCompanies = PercentCompanie::orderBy('created_at', 'desc')->get();
        $countries = Country::whereRobus(true)
            ->where('indecative', '!=', null)->get();

        $user = SidUser::whereId($id)->first();

        $kycs = $user->kyc()->get() ?? null;

        $countrieId = $user->country_id;

        $wallet_solde = Wallet::where('sid_user_id', $user->id)->first();

        $countrie = Country::whereId($countrieId)->first();

        $cities = City::where('country_id', $countrieId)->get();

        if ($countrie && $user->phone) {
            $indicative = $countrie->indecative;
            if (str_starts_with($user->phone, $indicative)) {
                $user->phone_without_indicative = substr($user->phone, strlen($indicative));
            } else {
                $user->phone_without_indicative = $user->phone;
            }
        } else {
            $user->phone_without_indicative = $user->phone;
        }

        return view('pages.admin.edit_company', compact(
            'roles',
            'countries',
            'percentCompanies',
            'countrie',
            'cities',
            'kycs',
            'user',
            'wallet_solde'
        ));
    }


    public function add_master_zone()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('ajouter-administrateur')) {
            abort(403);
        }

        $roles = Role::whereId(7)->orderBy('created_at', 'desc')->first();
        $countries = Country::whereRobus(true)
            ->where('indecative', '!=', null)->get();
        return view('pages.admin.add_master_zone', compact('roles', 'countries'));
    }

    public function add_com_zone()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('ajouter-administrateur')) {
            abort(403);
        }

        $roles = Role::whereId(8)->orderBy('created_at', 'desc')->first();
        $countries = Country::whereRobus(true)
            ->where('indecative', '!=', null)->get();
        return view('pages.admin.add_com_zone', compact('roles', 'countries'));
    }


    public function addunders()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('ajouter-administrateur')) {
            abort(403);
        }

        $roles = Role::whereId(4)->orderBy('created_at', 'desc')->first();
        $countries = Country::whereRobus(true)
            ->where('indecative', '!=', null)->get();

        return view('pages.admin.add_unders', compact('roles', 'countries'));
    }


    public function adddistributors()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('ajouter-administrateur')) {
            abort(403);
        }

        $roles = Role::whereId(3)->orderBy('created_at', 'desc')->first();
        $countries = Country::whereRobus(true)
            ->where('indecative', '!=', null)->get();

        return view('pages.admin.add_operators', compact('roles', 'countries'));
    }

    public function add_under_distributer()
    {

        $roles = Role::whereId(3)->orderBy('created_at', 'desc')->get();
        $currentStep = 1;
        $countries = Country::whereRobus(true)->where('indecative', '!=', null)->get();
        return view('pages.admin.add_under_distributer', compact('roles', 'countries', 'currentStep'));
    }




    public function add_under_distributer_two(Request $request)
    {

        $roles = Role::whereId(3)->orderBy('created_at', 'desc')->get();
        $currentStep = 2;
        $countries = Country::whereRobus(true)->where('indecative', '!=', null)->get();
        return view('pages.admin.add_under_distributer', compact('roles', 'countries', 'currentStep'));
    }

    public function store_under_distributerOne(Request $request)
    {

        $rt = rand(40, 5000) * time();
        $activation_code = sprintf("%0.4s", str_shuffle($rt));


        $validator = Validator::make($request->all(), [
            'name_ground' => ['required', 'string', 'max:255'],
            'email_agent' => ['required', 'string', 'email', 'max:255']
        ]);

        $request->session()->forget('key_under_distributer');

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Champ requises')
                ->withInput();
        }


        try {

            $user_email = SidUser::where('email', $request->email_agent)->first();

            if ($user_email) {

                $user_email->update([
                    'activation_code' =>  $activation_code
                ]);

                if (empty($request->session()->get('key_under_distributer'))) {
                    $newUser = new SidUser();
                    $existingUser = $request->session()->get('key_under_distributer');
                    // Assurez-vous que $existingUser n'est pas null
                    if ($existingUser) {
                        // Remplissez les propriétés de l'utilisateur existant avec les données du formulaire
                        $existingUser->fill($request->all());

                        // Mettez à jour l'utilisateur dans la session
                        $request->session()->put('key_under_distributer', $existingUser);
                    } else {
                        // Si $existingUser est null, utilisez la nouvelle instance créée
                        $newUser->fill($request->all());
                        $request->session()->put('key_under_distributer', $newUser);
                    }
                } else {
                    $user = $request->session()->get('key_under_distributer');
                    $user->fill($request->all());
                    $request->session()->put('key_under_distributer', $user);
                }

                Mail::to($user_email->email)->send(new OtpValidated('Code de validation est de  : ' . $user_email->activation_code));

                $msg = "Vous avez recu un code de validation dans votre boite email ";
                $color = 'success';

                return redirect(route('admin.add_under_distributer_two'))->with('color', $color)->with('status', $msg);
            } else {
                return back()
                    ->with('color', 'danger')
                    ->with('status', 'Email de l\'agent doit etre de Robus ou votre Email n\'est pas correct')
                    ->withInput();
            }
        } catch (\Throwable $th) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de création')
                ->withInput();
        }
    }
    public function store_under_distributer(Request $request)
    {

        $rt = rand(40, 5000) * time();
        $activation_code = sprintf("%0.4s", str_shuffle($rt));

        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'country_id' => 'required',
            'zone' => 'required',
            'lastname' => ['required', 'string', 'max:255'],
            'name_ground' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:sid_users'],
            'email_agent' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'unique:sid_users'],
            // 'password' => ['required', 'confirmed'],
            'image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_recto' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_verso' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return redirect('under_distibuters/add')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Champ requises')
                ->withInput();
        }


        // try {

        $user_email = DB::table('sid_users')->where('email', $request->email_agent)->first();

        if ($user_email->is_under_distributer) {

            $user_email->update([
                'activation_code' =>  $activation_code
            ]);

            // Mail::to($user_email->email)->send(new CodeValidated('Code de validation est de  : ' . $user_email->activation_code));

            $msg = "Vous avez recu un code de validation dans votre boite email ";
            $color = 'success';

            return redirect(route('admin.add_under_distributer_two'))->with('color', $color)->with('status', $msg);
        } else {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Email de l\'agent doit etre de Robus ou votre Email n\'est pas correct')
                ->withInput();
        }
    }

    public function store_under_distributerTwo(Request $request)
    {


        try {

            $rt = rand(40, 5000) * time();
            $tr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
            $walletCode = sprintf("%0.6s", str_shuffle($rt . $tr));

            $user_country = Country::whereId($request->country_id)
                ->first();

            $user_under = SidUser::whereActivationCode($request->validated_code)
                ->first();

            $user_email = SidUser::whereEmail($request->email)
                ->first();

            $user_phone = SidUser::wherePhone($user_country->indecative . $request->validated_code)
                ->first();

            if (!$user_under) {
                return back()
                    // ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Aucun utilisateur trouvé avec ce code de validation')
                    ->withInput();
            } elseif ($user_phone != null) {
                return back()
                    // ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Le numero existant ')
                    ->withInput();
            } elseif ($user_email != null) {
                return back()
                    // ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Adresse email existante')
                    ->withInput();
            } else {

                $under_distributer_key = $request->session()->get('key_under_distributer');

                if ($under_distributer_key != null) {

                    $user = new SidUser();
                    $user->firstname  = $request->firstname;
                    $user->company_id  = 1;
                    $user->lastname  = $request->lastname;
                    $user->name_ground = $under_distributer_key->name_ground ?? null;
                    $user->email_agent = $under_distributer_key->email_agent ?? null;
                    $user->country_id  = $request->country_id ?? null;
                    $user->city_id  = $request->city ?? null;
                    // $user->zone_id  = $request->zone_id ?? null;
                    $user->zone  = $request->zone ?? null;
                    $user->email =  $request->email;
                    $user->phone  = $request->phone;
                    $user->created_by = 1;
                    $file_image = $request->file('image');
                    $file_image_recto = $request->file('image_recto');
                    $file_image_verso = $request->file('image_verso');

                    if ($file_image) {
                        $destination_path = public_path('profil/' . $user->id . '/');
                        $front_file = date('YmdHis') . "." . $file_image->getClientOriginalExtension();
                        $file_image->move($destination_path, $front_file);
                        $user->image =  env('APP_URL') . "/public/profil/" . $user->id . "/$front_file";
                    }

                    // $user->password  = Hash::make($request->password);
                    $user->is_under_distributer  = true;
                    $user->is_distributer  = false;
                    $user->is_accounting  = false;
                    $user->is_admin  =  false;
                    $user->is_custumers  = false;
                    $user->is_supervisor  =  false;
                    $user->is_company  = false;

                    $user->assignRole(4);

                    if (!$user->country->currencie) {

                        return back()
                            // ->withErrors($validator)
                            ->with('color', 'danger')
                            ->with('status', 'Pas de devise dans se pays\n \n veillez activer la devise')
                            ->withInput();
                    }

                    $user->save();


                    if (isset($file_image_recto) && !empty($file_image_recto) && isset($file_image_verso) && !empty($file_image_verso)) {

                        $destination_path = public_path('kyc_distributer/' . $user->id . '/');
                        $front_file =  'face_' .  date('YmdHis') . "." . $file_image_recto->getClientOriginalExtension();
                        $back_file = 'arriere_' . date('YmdHis') . "." . $file_image_verso->getClientOriginalExtension();
                        $file_image_recto->move($destination_path, $front_file);
                        $file_image_verso->move($destination_path, $back_file);
                        Kyc::updateOrCreate(
                            [
                                'sid_user_id' => $user->id,
                                'company_id'  =>  $user->company_id
                            ],
                            [
                                'motif' => 'Validation de compte',
                                'screen' => env('APP_URL') . "/public/kyc_distributer/" . $user->id . "/$front_file",
                                'screen_back' => env('APP_URL') . "/public/kyc_distributer/" . $user->id . "/$back_file",
                                'sid_user_id' => $user->id,
                                'status' => true,
                                'company_id'  =>  $user->company_id
                            ]
                        );
                    }

                    $wallet_user = Wallet::create([
                        'company_id' =>  $user->company_id,
                        'wallet_id' =>   $user->country->code . $walletCode,
                        'country_id' =>  $user->country->id,
                        'created_by' =>  $user->id,
                        'sid_user_id' =>  $user->id,
                        'currencie_id' =>  $user->country->currencie->id,
                        'amount' => 0.0,
                        'reload_by' => null,
                        'status' => false

                    ]);


                    $user_email = SidUser::where('email', $user->email_agent)->first();

                    $user->update([
                        'created_by' => $user_email->id,
                        'wallet_id'  => $wallet_user->wallet_id,
                        'phone'  =>   $user->country->indecative . $user->phone,
                        'silver_ceiling'  =>   $user->country->silver_ceiling,
                    ]);

                    // Update valeurs code
                    $user_email->update([
                        'activation_code'  => null,
                    ]);

                    Mail::to($user->email)->send(new NewAdmin('Vous etes désormais un sous distributeur marchant de ' . env('APP_NAME') .
                        ' Votre email : '  . $user->email . ' Administrateur Robus vas activé votre compte pour commence les transactions'));


                    $msg = "Sous distributeur crée avec succès";
                    $color = 'success';
                    $request->session()->forget('key_under_distributer');
                    try {

                        $originalLogoPath = public_path('dist/images/logo_orange.png');

                        $qrData = json_encode([
                            'wallet_id' => $user->wallet->wallet_id,
                            'user' => $user->firstname . ' ' .  $user->lastname,
                            'countrie' => $user->country->name,
                            'currencie' => $user->country->currencie->name,
                        ]);

                        $result = Builder::create()
                            ->data($qrData)
                            ->encoding(new Encoding('UTF-8'))
                            ->size(300)
                            ->margin(10)
                            ->logoPath($originalLogoPath)
                            ->logoResizeToWidth(70)
                            ->logoResizeToHeight(70)

                            ->build();


                        $qrPath = 'qrcodes/wallet_' . $user->wallet->id . '.png';
                        $result->saveToFile(public_path($qrPath));

                        $user->wallet->update(['qr_code' => $qrPath]);
                    } catch (\Throwable $th) {

                        return response()->json(
                            [
                                'success' => false,
                                'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
                            ],
                            301
                        );
                    }
                    return redirect(route('admin.add_under_distributer'))->with('color', $color)->with('status', $msg);
                } else {
                    return back()
                        // ->withErrors($validator)
                        ->with('color', 'danger')
                        ->with('status', 'Aucune donnée disponible sur le sous distributeur qui inscrire vueillez reprendre la validation du code ')
                        ->withInput();
                }
            }
        } catch (\Throwable $th) {
            return back()
                // ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de création')
                ->withInput();
        }
    }

    public function store_employers(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'country_id' => 'required',
            'zone' => 'required',
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:sid_users'],
            'phone' => ['required', 'unique:sid_users'],
            'password' => ['required', 'confirmed'],
            'image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_recto' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_verso' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Champ requises')
                ->withInput();
        }

        $user_phone = DB::table('sid_users')->where('phone', $request->phone)->first();


        try {

            $rt = rand(40, 5000) * time();
            $tr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
            $walletCode = sprintf("%0.6s", str_shuffle($rt . $tr));


            if ($user_phone != null) {
                return redirect('employes/add')
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Numero existe')
                    ->withInput();
            }

            $user = new SidUser();
            $user->firstname  = $request->firstname;
            $user->company_id  = 1;
            $user->lastname  = $request->lastname;
            $user->country_id  = $request->country_id ?? null;
            $user->city_id  = $request->city ?? null;
            $user->zone_id  = $request->zone_id ?? null;
            $user->zone  = $request->zone ?? null;
            $user->email =  $request->email;
            $user->phone  = '+225' . $request->phone;
            $user->created_by = 1;
            $user->name_ground = $request->name_ground ?? null;
            $file_image = $request->file('image');
            $file_image_recto = $request->file('image_recto');
            $file_image_verso = $request->file('image_verso');

            if (!empty($file_image)) {
                $destination_path = public_path('profil/' . $user->id . '/');
                $front_file = date('YmdHis') . "." . $file_image->getClientOriginalExtension();
                $file_image->move($destination_path, $front_file);
                $user->image =  env('APP_URL') . "/public/profil/" . $user->id . "/$front_file";
            }

            $user->password  = Hash::make($request->password);
            $user->is_under_distributer  =  true;
            $user->is_distributer  = false;
            $user->is_accounting  = false;
            $user->is_admin  = false;
            $user->is_custumers  =  false;
            $user->is_supervisor  =  false;
            $user->is_company  = false;

            $user->assignRole(3);

            if (!$user->country->currencie) {

                return redirect('employes/add')
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Pas de devise dans se pays\n \nveillez activer la devise')
                    ->withInput();
            }

            $user->save();

            if (isset($file_image_recto) && !empty($file_image_recto) && isset($file_image_verso) && !empty($file_image_verso)) {

                $destination_path = public_path('kyc_distributer/' . $user->id . '/');
                $front_file =  'face_' .  date('YmdHis') . "." . $file_image_recto->getClientOriginalExtension();
                $back_file = 'arriere_' . date('YmdHis') . "." . $file_image_verso->getClientOriginalExtension();
                $file_image_recto->move($destination_path, $front_file);
                $file_image_verso->move($destination_path, $back_file);
                Kyc::updateOrCreate(
                    [
                        'sid_user_id' => $user->id,
                        'company_id'  =>  $user->company->id
                    ],
                    [
                        'motif' => 'Validation de compte',
                        'screen' => env('APP_URL') . "/public/kyc_distributer/" . $user->id . "/$front_file",
                        'screen_back' => env('APP_URL') . "/public/kyc_distributer/" . $user->id . "/$back_file",
                        'sid_user_id' => $user->id,
                        'status' => true,
                        'company_id'  =>  $user->company->id
                    ]
                );
            }




            $wallet_user = Wallet::create([

                'company_id' =>  $user->company->id,
                'wallet_id' =>   $user->country->code . $walletCode,
                'country_id' =>  $user->country->id,
                'created_by' =>  $user->id,
                'sid_user_id' =>  $user->id,
                'currencie_id' =>  $user->country->currencie->id,
                'amount' => 0.0,
                'reload_by' => null,
                'status' => true

            ]);


            $user->update([
                'wallet_id'  => $wallet_user->wallet_id,
                'phone'  =>   $user->country->indecative . $user->phone,
                'silver_ceiling'  =>   $user->country->silver_ceiling
            ]);



            Mail::to($user->email)->send(new NewAdmin('Vous etes désormais un employé distributeur marchant de ' . env('APP_NAME') .
                ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

            $msg = "Employé crée avec succès";
            $color = 'success';

            try {

                $originalLogoPath = public_path('dist/images/logo_orange.png');

                $qrData = json_encode([
                    'wallet_id' => $user->wallet->wallet_id,
                    'user' => $user->firstname . ' ' .  $user->lastname,
                    'countrie' => $user->country->name,
                    'currencie' => $user->country->currencie->name,
                ]);

                $result = Builder::create()
                    ->data($qrData)
                    ->encoding(new Encoding('UTF-8'))
                    ->size(300)
                    ->margin(10)
                    ->logoPath($originalLogoPath)
                    ->logoResizeToWidth(70)
                    ->logoResizeToHeight(70)

                    ->build();


                $qrPath = 'qrcodes/wallet_' . $user->wallet->id . '.png';
                $result->saveToFile(public_path($qrPath));

                $user->wallet->update(['qr_code' => $qrPath]);
            } catch (\Throwable $th) {

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
                    ],
                    301
                );
            }
            return redirect(route('employers.browse'))->with('color', $color)->with('status', $msg);
        } catch (\Throwable $th) {
            return redirect('employes/add')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de création')
                ->withInput();
        }
    }


    public function store(Request $request)
    {


        $validator = $this->userValidatorService->validateUserDataAdmin($request->all());

        if ($validator->fails()) {
            return redirect('application/admin/add')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Champ requise')
                ->withInput();
        }


        try {

            $response = $this->createUserService->createSupervisorsWeb(
                $request->all(),
                $request->file('image'),
                $request->file('image_recto'),
                $request->file('image_verso')
            );

            $msg = "Un superviseur crée avec succès";

            if ($response['success']) {

                $user = $response['user'];

                $valeur_message = ' Un superviseur ';
                Mail::to($user->email)->send(new NewAdmin('Vous etes désormais ' . $valeur_message . env('APP_NAME') .
                    ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

                $msg = "Superviseur crée avec succès";
                $color = 'success';

                try {

                    $originalLogoPath = public_path('dist/images/logo_orange.png');

                    $qrData = json_encode([
                        'wallet_id' => $user->wallet->wallet_id,
                        'user' => $user->firstname . ' ' .  $user->lastname,
                        'countrie' => $user->country->name,
                        'currencie' => $user->country->currencie->name,
                    ]);

                    $result = Builder::create()
                        ->data($qrData)
                        ->encoding(new Encoding('UTF-8'))
                        ->size(300)
                        ->margin(10)
                        ->logoPath($originalLogoPath)
                        ->logoResizeToWidth(70)
                        ->logoResizeToHeight(70)

                        ->build();


                    $qrPath = 'qrcodes/wallet_' . $user->wallet->id . '.png';
                    $result->saveToFile(public_path($qrPath));

                    $user->wallet->update(['qr_code' => $qrPath]);
                } catch (\Throwable $th) {

                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
                        ],
                        301
                    );
                }
                return redirect(route('admins.browse'))->with('color', $color)->with('status', $msg);
            } else {

                $message = $response['message'];

                return redirect('application/admin/add')
                    // ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', $message)
                    ->withInput();
            }
        } catch (\Throwable $th) {
            return redirect('application/admin/add')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de création')
                ->withInput();
        }
    }

    public function update_company($id, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'zone' => 'required',
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required'],
            'percent' => 'required',
            'password' => ['required', 'confirmed'],
            'image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_recto' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_verso' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Champ requise')
                ->withInput();
        }

        try {


            $response = $this->createUserService->updateUsersCompany(
                $id,
                $request->all(),
                $request->file('image'),
                $request->file('image_recto'),
                $request->file('image_verso')
            );

            $msg = " Modification effectuée avec succès ";

            if ($response['success']) {

                $user = $response['user'];

                $valeur_message = ' Profil ';

                Mail::to($user->email)->send(new NewAdmin('Vous etes désormais ' . $valeur_message . env('APP_NAME') .
                    ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

                $msg = "Modification de profil avec succès";
                $color = 'success';

                return redirect(route('company.browse'))->with('color', $color)->with('status', $msg);
            } else {

                $message = $response['message'];

                return back()
                    ->with('color', 'danger')
                    ->with('status', $message)
                    ->withInput();
            }
        } catch (\Throwable $th) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de création')
                ->withInput();
        }
    }

    public function store_company(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'country_id' => 'required',
            'zone' => 'required',
            'percent' => 'required',
            'roles' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:sid_users'],
            'phone' => ['required', 'unique:sid_users'],
            'password' => ['required', 'confirmed'],
            'image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_recto' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_verso' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',

        ]);

        if ($validator->fails()) {
            return redirect('application/admin/add_company')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Champ requise')
                ->withInput();
        }


        $user = DB::table('sid_users')->where('email', $request->email)->first();

        $tool = new Image;

        try {


            $rt = rand(40, 5000) * time();
            $tr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
            $walletCode = sprintf("%0.6s", str_shuffle($rt . $tr));

            $user = new SidUser();
            $user->firstname  = $request->firstname;
            $user->company_id  = 1;
            $user->lastname  = $request->lastname;
            $user->country_id  = $request->country_id ?? null;
            $user->city_id  = $request->city ?? null;
            $user->zone_id  = $request->zone_id ?? null;
            $user->zone  = $request->zone ?? null;
            $user->email =  $request->email;
            $user->phone  = $request->phone;
            $user->created_by = Auth::guard('web')->user()->id;
            $file_image = $request->file('image');
            $file_image_recto = $request->file('image_recto');
            $file_image_verso = $request->file('image_verso');
            if ($file_image) {
                $destination_path = public_path('profil/' . $user->id . '/');
                $front_file = date('YmdHis') . "." . $file_image->getClientOriginalExtension();
                $file_image->move($destination_path, $front_file);
                $user->image =  env('APP_URL') . "/public/profil/" . $user->id . "/$front_file";
            }

            $user->password  = Hash::make($request->password);
            $user->is_under_distributer  = false;
            $user->is_distributer  = false;
            $user->is_accounting  = false;
            $user->is_admin  =  false;
            $user->is_custumers  = false;
            $user->is_supervisor  =  false;
            $user->is_company  = true;

            $user->assignRole($request['roles']);

            if (!$user->country->currencie) {
                return redirect('application/admin/add_company')
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Pas de devise dans se pays\n \nveillez activer la devise')
                    ->withInput();
            }

            $user->save();

            if (isset($file_image_recto) && !empty($file_image_recto) && isset($file_image_verso) && !empty($file_image_verso)) {

                $destination_path = public_path('kyc_distributer/' . $user->id . '/');
                $front_file =  'face_' .  date('YmdHis') . "." . $file_image_recto->getClientOriginalExtension();
                $back_file = 'arriere_' . date('YmdHis') . "." . $file_image_verso->getClientOriginalExtension();
                $file_image_recto->move($destination_path, $front_file);
                $file_image_verso->move($destination_path, $back_file);
                Kyc::updateOrCreate(
                    [
                        'sid_user_id' => $user->id,
                        'company_id'  =>  $user->company->id
                    ],
                    [
                        'motif' => 'Validation de compte',
                        'screen' => env('APP_URL') . "/public/kyc_distributer/" . $user->id . "/$front_file",
                        'screen_back' => env('APP_URL') . "/public/kyc_distributer/" . $user->id . "/$back_file",
                        'sid_user_id' => $user->id,
                        'status' => true,
                        'company_id'  =>  $user->company->id
                    ]
                );
            }


            if ($user->is_company == true) {

                $company_user = Company::create([
                    'city_id' =>   $user->city_id,
                    'name' =>      $user->firstname,
                    'firstname_manager' => $request->firstname_manager,
                    'phone_gerant' =>  $request->phone_gerant,
                    'country_id' =>  $user->country_id,
                    'email' =>  $user->email,
                    'phone' =>  $user->country->indecative . $user->phone,
                    'percent_companie_id' => $request->percent,
                    'city' =>  $user->zone,
                    // 'currencie_id' =>  $user->country->currencie->id,
                    'status' => true
                ]);

                $wallet_user = Wallet::create([
                    'company_id' =>   $company_user->id,
                    'wallet_id' =>   $user->country->code . $walletCode,
                    'country_id' =>  $user->country->id,
                    'created_by' => Auth::guard('web')->user()->id,
                    'sid_user_id' =>  $user->id,
                    'currencie_id' =>  $user->country->currencie->id,
                    'amount' => 0.0,
                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                    'status' => true

                ]);

                $user->update([
                    'company_id'  => $company_user->id,
                    'wallet_id'  => $wallet_user->wallet_id,
                    'phone'  =>   $user->country->indecative . $user->phone,
                    'silver_ceiling'  =>   $user->country->silver_ceiling
                ]);
            } else {

                $msg = "Societe crée avec succès";
                $color = 'danger';
                return redirect(route('company.browse'))->with('color', $color)->with('status', $msg);
            }



            // Mail::to($user->email)->send(new NewAdmin('Vous etes désormais une société ' . env('APP_NAME') .
            //     ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

            $msg = "Societe crée avec succès";
            $color = 'success';
            try {

                $originalLogoPath = public_path('dist/images/logo_orange.png');

                $qrData = json_encode([
                    'wallet_id' => $user->wallet->wallet_id,
                    'user' => $user->firstname . ' ' .  $user->lastname,
                    'countrie' => $user->country->name,
                    'currencie' => $user->country->currencie->name,
                ]);

                $result = Builder::create()
                    ->data($qrData)
                    ->encoding(new Encoding('UTF-8'))
                    ->size(300)
                    ->margin(10)
                    ->logoPath($originalLogoPath)
                    ->logoResizeToWidth(70)
                    ->logoResizeToHeight(70)

                    ->build();


                $qrPath = 'qrcodes/wallet_' . $user->wallet->id . '.png';
                $result->saveToFile(public_path($qrPath));

                $user->wallet->update(['qr_code' => $qrPath]);
            } catch (\Throwable $th) {

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
                    ],
                    301
                );
            }
            return redirect(route('company.browse'))->with('color', $color)->with('status', $msg);
        } catch (\Throwable $th) {
            return redirect('application/admin/add_company')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de création')
                ->withInput();
        }
    }


    public function activate(int $id)
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('activer-administrateur')) {
            abort(403);
        }
        try {
            DB::beginTransaction();
            $user = SidUser::find($id);
            // dd($user->wallet);
            $user->deleted_by = NULL;
            $user->deleted_at = NULL;
            $user->save();
            DB::commit();

            $msg = 'Activation effectuée avec succès';
            $color = 'success';
        } catch (\Exception $exception) {
            DB::rollBack();
            $msg = 'Activation échouée';
            $color = 'danger';
        }
        return redirect()->back()->with('color', $color)
            ->with('status', $msg);
    }
    public function showUpdate(int $id)
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('modifier-administrateur')) {
            abort(403);
        }
        $user = SidUser::with('roles')->where('id', $id)->first();
        $roles = Role::all();
        $countries = Country::where('status', true)->get();
        $zones = Zone::where('status', true)->get();

        return view('pages.admin.showUpdate', compact('user', 'roles', 'countries', 'zones'));
    }

    public function showUpdate_companies(int $id)
    {

        $user = SidUser::with('roles')->where('id', $id)->first();
        $zones = Zone::where('status', true)->get();

        return view('pages.admin.showUpdate_employers', compact('user', 'zones'));
    }

    public function ShowProfil(int $id, Request $request)
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-utilisateur')) {
            abort(403);
        }

        $paiement_status = null;

        $user = SidUser::whereId($id)->first();

        $wallet_solde = Wallet::where('sid_user_id', $user->id)->first();


        $transactions =  $this->transactionAccountService->getTransactionUserWallet(WalletTransaction::class, $wallet_solde->id, 'num_wallet',  $wallet_solde->wallet_id);

        $paiements =  $this->transactionAccountService->getTransactionsWallet(WalletPayment::class, $wallet_solde->id);

        $virements =  $this->transactionAccountService->getTransactionsWallet(WalletBank::class, $wallet_solde->id);

        $phoneCredits =  $this->transactionAccountService->getTransactionsWallet(WalletPhoneCredit::class, $wallet_solde->id);

        $reloadCards =  $this->transactionAccountService->getTransactionsWallet(WalletDeposit::class, $wallet_solde->id);

        $walletOrderCarte =  $this->transactionAccountService->getTransactionsWallet(BankCard::class, $wallet_solde->id);

        $walletxbet =  $this->transactionAccountService->getTransactionsWallet(WalletXbet::class, $wallet_solde->id);

        $reaload_uv =  $this->transactionAccountService->getTransactionUserWallet(WalletReload::class, $wallet_solde->id, 'account_wallet',  $wallet_solde->wallet_id);

        $Internationals =  $this->transactionAccountService->getTransactionUserWallet(WalletInter::class, $wallet_solde->id, 'number_compte',  $wallet_solde->wallet_id);


        $usersCountry = Auth::guard('web')->user()->country->currencie->name;

        $kycs = $user->kyc()->get() ?? null;

        $roles = Role::all();
        $countries = Country::where('status', true)->get();

        $sum_error    = $this->getCountWalletTransaction($wallet_solde->id, 'ERROR');
        $sum_success    = $this->getCountWalletTransaction($wallet_solde->id, 'SUCCESS');
        $pending_success    = $this->getCountWalletTransaction($wallet_solde->id, 'PENDING');


        $sum_wallet_transaction = $this->transactionAccountService->getTransactionsTypeAllAmount(WalletTransaction::class, 'num_wallet', $wallet_solde);

        $sum_wallet_bank = $this->transactionAccountService->getTransactionAmount(WalletBank::class, $wallet_solde->id);

        $sum_wallet_deposit = $this->transactionAccountService->getTransactionAmount(WalletDeposit::class, $wallet_solde->id);

        $sum_wallet_payment = $this->transactionAccountService->getTransactionAmount(WalletPayment::class, $wallet_solde->id);

        $sum_wallet_phone = $this->transactionAccountService->getTransactionAmount(WalletPhoneCredit::class, $wallet_solde->id);

        $sum_wallet_xbet = $this->transactionAccountService->getTransactionAmount(WalletXbet::class, $wallet_solde->id);

        $sum_wallet_Inter = $this->transactionAccountService->getTransactionsTypeAllAmount(WalletInter::class, 'number_compte', $wallet_solde);

        $sum_wallet_Reload = $this->getWalletReloadSumAdminProperty($wallet_solde->id);
        $sum_wallet_drawer = $this->getWalletWithMerchantSumUserProperty($id);
        $sum_wallet_reload_received = $this->getWalletReloadSumReceviedAdminProperty($wallet_solde->id);

        $sum_commissions  = $this->getCommissionsWalletTransaction($wallet_solde->id);

        $sum_drawals  =  $this->getWalletTransactionSumRetraitsProperty($wallet_solde->id);

        $sum_wallet_transaction_received = $this->transactionAccountService->getTransactionsRecevedAllAmount(WalletTransaction::class, 'num_wallet', $wallet_solde);

        $sum_wallet_Inter_received = $this->transactionAccountService->getTransactionsRecevedAllAmount(WalletInter::class, 'number_compte', $wallet_solde);

        return view('pages.admin.show', compact(
            'user',
            'roles',
            'sum_wallet_drawer',
            'sum_drawals',
            'sum_error',
            'sum_commissions',
            'sum_success',
            'pending_success',
            'sum_wallet_deposit',
            'sum_wallet_bank',
            'sum_wallet_payment',
            'sum_wallet_transaction',
            'sum_wallet_transaction_received',
            'sum_wallet_phone',
            'sum_wallet_Inter',
            'sum_wallet_Inter_received',
            'sum_wallet_Reload',
            'sum_wallet_reload_received',
            'countries',
            'virements',
            'reaload_uv',
            'reloadCards',
            'paiements',
            'Internationals',
            'transactions',
            'phoneCredits',
            'usersCountry',
            'paiement_status',
            'walletOrderCarte',
            'kycs'

        ));
    }

    public function showunders(int $id, Request $request)
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-sous-distributeurs')) {
            abort(403);
        }


        $user = SidUser::whereId($id)->first();

        $wallet_solde = Wallet::where('sid_user_id', $user->id)->first();

        $usersCountry = Auth::guard('web')->user()->country->currencie->name;

        $kycs = $user->kyc()->get() ?? null;

        $roles = Role::all();

        $countries = Country::where('status', true)->get();
        // Wallet Error and other
        $sum_error    = $this->getCountWalletTransaction($wallet_solde->id, 'ERROR');
        $sum_success    = $this->getCountWalletTransaction($wallet_solde->id, 'SUCCESS');
        $pending_success    = $this->getCountWalletTransaction($wallet_solde->id, 'PENDING');
        $totalSumAmounts = $this->getTotalSumTransactionsAmount($wallet_solde);

        // Gains
        $gain_deposits = $this->getTodayTransactionCommissionAmountTotal(WalletDeposit::class, $user->wallet->id);
        $gain_payments = $this->getTodayTransactionCommissionAmountTotal(WalletPayment::class, $user->wallet->id);
        $gain_transactions = $this->getTodayTransactionCommissionAmountTotal(WalletTransaction::class, $user->wallet->id);
        $gain_moneys = $this->getTodayTransactionMobileCommissionAmountTotal(WalletTransaction::class, $user->wallet->id);
        $gain_depots = $this->getTodayTransactionRobusDepotCommissionAmountTotal(WalletTransaction::class, $user->wallet->id);
        $gain_retraits = $this->getTodayTransactionRobusRetraitCommissionAmountTotal(WalletTransaction::class, $user->wallet->id);
        $gain_phone_credits = $this->getTodayTransactionCommissionAmountTotal(WalletPhoneCredit::class,  $user->wallet->id);
        $gain_inters = $this->getTodayTransactionCommissionAmountTotal(WalletInter::class, $user->wallet->id);

        $gain_xbets = $this->getTodayTransactionCommissionAmountTotal(WalletXbet::class, $user->wallet->id);
        $gain_versment = $this->getTodayTransactionCommissionAmountTotal(WalletVersement::class, $user->wallet->id);
        $gain_push = $this->getTodayTransactionCommissionAmountTotal(WalletPush::class, $user->wallet->id);
        $gain_djamo = $this->getTodayTransactionCommissionAmountTotal(WalletDjamo::class, $user->wallet->id);
        $gain_tresorts = $this->getTodayTransactionCommissionAmountTotal(WalletTresortMoney::class, $user->wallet->id);
        $gain_bank_sends = $this->getTodayTransactionCommissionAmountTotal(WalletBankSend::class, $user->wallet->id);


        $count_days_inters = $this->getCountsTransactionsAmountTotal(WalletInter::class, $user->wallet->id, 'daily');
        $amount_days_inters = $this->getTransactionsAmountTotal(WalletInter::class, $user->wallet->id, 'daily');
        $count_mounth_inters = $this->getCountsTransactionsAmountTotal(WalletInter::class, $user->wallet->id, 'monthly');
        $amount_mounth_inters = $this->getTransactionsAmountTotal(WalletInter::class, $user->wallet->id, 'monthly');

        $gain_banks = $this->getTodayTransactionCommissionAmountTotal(WalletBank::class, $user->wallet->id);

        $company_gains = $gain_payments + $gain_transactions + $gain_phone_credits + $gain_inters + $gain_banks + $gain_deposits
            + $gain_xbets + $gain_versment + $gain_push + $gain_djamo + $gain_tresorts + $gain_bank_sends;

        // Execeptions
        $sum_wallet_drawer = $this->getWalletWithMerchantSumUserProperty($id);

        return view('pages.admin.show_unders', compact(
            'user',
            'gain_xbets',
            'gain_versment',
            'gain_bank_sends',
            'gain_push',
            'gain_djamo',
            'gain_tresorts',
            'count_days_inters',
            'amount_days_inters',
            'count_mounth_inters',
            'amount_mounth_inters',
            'roles',
            'sum_wallet_drawer',
            'sum_error',
            'sum_success',
            'pending_success',
            'countries',
            'usersCountry',
            'totalSumAmounts',
            'kycs',
            'gain_payments',
            'gain_transactions',
            'gain_phone_credits',
            'gain_inters',
            'gain_banks',
            'gain_deposits',
            'gain_depots',
            'gain_retraits',
            'gain_moneys',
            'company_gains'

        ));
    }


    public function show_propects(int $id, Request $request)
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-integrateurs')) {
            abort(403);
        }


        $user = SidUser::whereId($id)->first();

        $wallet_solde = Wallet::where('sid_user_id', $user->id)->first();

        $usersCountry = Auth::guard('web')->user()->country->currencie->name;

        $kycs = $user->kyc()->get() ?? null;

        $roles = Role::all();

        $countries = Country::where('status', true)->get();
        // Wallet Error and other
        $sum_error    = $this->getCountWalletTransaction($wallet_solde->id, 'ERROR');
        $sum_success    = $this->getCountWalletTransaction($wallet_solde->id, 'SUCCESS');
        $pending_success    = $this->getCountWalletTransaction($wallet_solde->id, 'PENDING');
        $totalSumAmounts = $this->getTotalSumTransactionsAmount($wallet_solde);

        // Gains
        $gain_deposits = $this->getTodayTransactionCommissionAmountTotal(WalletDeposit::class, $user->wallet->id);
        $gain_payments = $this->getTodayTransactionCommissionAmountTotal(WalletPayment::class, $user->wallet->id);
        $gain_transactions = $this->getTodayTransactionCommissionAmountTotal(WalletTransaction::class, $user->wallet->id);
        $gain_moneys = $this->getTodayTransactionMobileCommissionAmountTotal(WalletTransaction::class, $user->wallet->id);
        $gain_depots = $this->getTodayTransactionRobusDepotCommissionAmountTotal(WalletTransaction::class, $user->wallet->id);
        $gain_retraits = $this->getTodayTransactionRobusRetraitCommissionAmountTotal(WalletTransaction::class, $user->wallet->id);
        $gain_phone_credits = $this->getTodayTransactionCommissionAmountTotal(WalletPhoneCredit::class,  $user->wallet->id);
        $gain_inters = $this->getTodayTransactionCommissionAmountTotal(WalletInter::class, $user->wallet->id);

        $gain_xbets = $this->getTodayTransactionCommissionAmountTotal(WalletXbet::class, $user->wallet->id);
        $gain_versment = $this->getTodayTransactionCommissionAmountTotal(WalletVersement::class, $user->wallet->id);
        $gain_push = $this->getTodayTransactionCommissionAmountTotal(WalletPush::class, $user->wallet->id);
        $gain_djamo = $this->getTodayTransactionCommissionAmountTotal(WalletDjamo::class, $user->wallet->id);
        $gain_tresorts = $this->getTodayTransactionCommissionAmountTotal(WalletTresortMoney::class, $user->wallet->id);


        $count_days_inters = $this->getCountsTransactionsAmountTotal(WalletInter::class, $user->wallet->id, 'daily');
        $amount_days_inters = $this->getTransactionsAmountTotal(WalletInter::class, $user->wallet->id, 'daily');
        $count_mounth_inters = $this->getCountsTransactionsAmountTotal(WalletInter::class, $user->wallet->id, 'monthly');
        $amount_mounth_inters = $this->getTransactionsAmountTotal(WalletInter::class, $user->wallet->id, 'monthly');

        $gain_banks = $this->getTodayTransactionCommissionAmountTotal(WalletBank::class, $user->wallet->id);
        $gain_bank_sends = $this->getTodayTransactionCommissionAmountTotal(WalletBankSend::class, $user->wallet->id);
        $company_gains = $gain_payments + $gain_transactions + $gain_phone_credits + $gain_inters + $gain_banks + $gain_deposits
            + $gain_xbets + $gain_versment + $gain_push + $gain_djamo + $gain_tresorts + $gain_bank_sends;

        // Execeptions
        $sum_wallet_drawer = $this->getWalletWithMerchantSumUserProperty($id);

        $documents = IntegrationClient::where('sid_user_id', $id)->first();

        $documentsCollection = collect([
            ['name' => 'Front CNI', 'url' => $documents->front_document ?? null, 'status' => 'Validé'],
            ['name' => 'Verso CNI', 'url' => $documents->piece_document ?? null, 'status' => 'Validé'],
            ['name' => 'Juridict Form', 'url' => $documents->juridict_form ?? null, 'status' => 'Validé'],
        ])->filter(fn($doc) => $doc['url']);

        // Calcul des statistiques
        $validatedCount = $documentsCollection->where('status', 'Validé')->count();
        $pendingCount   = $documentsCollection->where('status', 'En attente')->count();
        $rejectedCount  = $documentsCollection->where('status', 'Rejeté')->count();
        $totalCount     = $documentsCollection->count();


        return view('pages.admin.show_unders', compact(
            'user',
            'documents',
            'validatedCount',
            'pendingCount',
            'rejectedCount',
            'totalCount',
            'gain_xbets',
            'gain_bank_sends',
            'gain_versment',
            'gain_push',
            'gain_djamo',
            'gain_tresorts',
            'count_days_inters',
            'amount_days_inters',
            'count_mounth_inters',
            'amount_mounth_inters',
            'roles',
            'sum_wallet_drawer',
            'sum_error',
            'sum_success',
            'pending_success',
            'countries',
            'usersCountry',
            'totalSumAmounts',
            'kycs',
            'gain_payments',
            'gain_transactions',
            'gain_phone_credits',
            'gain_inters',
            'gain_banks',
            'gain_deposits',
            'gain_depots',
            'gain_retraits',
            'gain_moneys',
            'company_gains',

        ));
    }


    public function remonteCommission($amount, $typeRemonte, int $id)
    {


        $user = SidUser::find($id);



        if (!$user) {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Utilisateur introuvable !!')
                ->withInput();
        }

        $gainMappings = [
            "Commission sur Paiements" => $this->getTodayTransactionCommissionAmountTotal(WalletPayment::class, $user->wallet->id),
            // "Transactions" => $this->getTodayTransactionCommissionAmountTotal(WalletTransaction::class, $user->wallet->id),
            "Commission sur Crédits" => $this->getTodayTransactionCommissionAmountTotal(WalletPhoneCredit::class, $user->wallet->id),
            "Commission sur International" => $this->getTodayTransactionCommissionAmountTotal(WalletInter::class, $user->wallet->id),
            "Commission sur Virements" => $this->getTodayTransactionCommissionAmountTotal(WalletBank::class, $user->wallet->id),
            "Commission sur Rechargements" => $this->getTodayTransactionCommissionAmountTotal(WalletDeposit::class, $user->wallet->id),
            "Commission sur Depots" => $this->getTodayTransactionRobusDepotCommissionAmountTotal(WalletTransaction::class, $user->wallet->id),
            "Commission sur Retraits" => $this->getTodayTransactionRobusRetraitCommissionAmountTotal(WalletTransaction::class, $user->wallet->id),
            "Commission sur Moneys" => $this->getTodayTransactionMobileCommissionAmountTotal(WalletTransaction::class, $user->wallet->id),
            // New transactions
            "Commission sur Xbets" => $this->getTodayTransactionCommissionAmountTotal(WalletXbet::class, $user->wallet->id),
            "Commission sur Push" => $this->getTodayTransactionCommissionAmountTotal(WalletPush::class, $user->wallet->id),
            "Commission sur Versement" => $this->getTodayTransactionCommissionAmountTotal(WalletVersement::class, $user->wallet->id),
            "Commission sur Tresort" => $this->getTodayTransactionCommissionAmountTotal(WalletTresortMoney::class, $user->wallet->id),
            "Commission sur Djamo" => $this->getTodayTransactionCommissionAmountTotal(WalletDjamo::class, $user->wallet->id),
            "Commission sur BankSend" => $this->getTodayTransactionCommissionAmountTotal(WalletBankSend::class, $user->wallet->id)
        ];


        $resetMappings = [
            "Commission sur Paiements" => WalletPayment::class,
            // "Transactions" => WalletTransaction::class,
            "Commission sur Crédits" => WalletPhoneCredit::class,
            "Commission sur International" => WalletInter::class,
            "Commission sur Virements" => WalletBank::class,
            "Commission sur Rechargements" => WalletDeposit::class,
            "Commission sur Depots" => WalletTransaction::class,
            "Commission sur Retraits" => WalletTransaction::class,
            "Commission sur Moneys" => WalletTransaction::class,
            "Commission sur Xbets" => WalletXbet::class,
            "Commission sur Push" => WalletPush::class,
            "Commission sur Versement" => WalletVersement::class,
            "Commission sur Tresort" => WalletTresortMoney::class,
            "Commission sur Djamo" => WalletDjamo::class,
            "Commission sur BankSend" => WalletBankSend::class
        ];



        $amount_solde = (float)$user->wallet->amount + (float)$amount;


        if ($amount <= 0) {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Le montant de la commission est invalide ou null !!')
                ->withInput();
        }


        if (!isset($gainMappings[$typeRemonte])) {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Type de remontée invalide !!')
                ->withInput();
        }

        $calculatedGainAmount = $gainMappings[$typeRemonte];


        if ((string)$amount !== (string)$calculatedGainAmount) {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Montant de la commission invalide !!')
                ->withInput();
        }


        $amount_solde = (float)$user->wallet->amount + (float)$amount;

        $amount_old_solde = (float)$user->wallet->amount;
        $amount_before_solde = (float)$user->wallet->amount;

        $withRemonte = $this->createWithCommission($amount_solde, $amount, $user->id,  $typeRemonte, $amount_old_solde, $amount_before_solde);

        if ($withRemonte !== null) {

            $user->wallet->update([
                'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                'sid_user_relod' => sabdy()->id,
                'amount' => DB::raw('amount+'  .  $withRemonte->amount_with_draw),
            ]);

            if (isset($resetMappings[$typeRemonte])) {

                $resetClass = $resetMappings[$typeRemonte];

                if ($typeRemonte === "Commission sur Depots") {
                    $this->getUpdateTransactionDepotRobusCommissionAmountNullTotal($resetClass, $user->wallet->id);
                } elseif ($typeRemonte === "Commission sur Retraits") {
                    $this->getUpdateTransactionRetraitRobusCommissionAmountNullTotal($resetClass, $user->wallet->id);
                } elseif ($typeRemonte === "Commission sur Moneys") {
                    $this->getUpdateTransactionMoneyCommissionAmountNullTotal($resetClass, $user->wallet->id);
                } else {
                    $this->getUpdateTransactionCommissionAmountNullTotal($resetClass, $user->wallet->id);
                }
            }

            $msg = 'Vous avez transféré vos commissions sur votre compte';
            $msg_title = 'Transfert de la commission';
            $user->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));

            return back()
                ->with('color', 'success')
                ->with('status', 'Votre commission a été remontée avec succès !!')
                ->withInput();
        } else {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Erreur de recuperation du montant de la commission !!')
                ->withInput();
        }
    }

    public  function remonteCommissionAdmins($amount, $typeRemonte, int $id, Request $request)
    {


        $user = SidUser::find($id);
        $admin_civ =  SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

        if (!$user) {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Utilisateur introuvable !!')
                ->withInput();
        }


        $gainMappings = [
            "Retrait Paiements" => $this->getTodayTransactionCommissionAmountTotal(WalletPayment::class, $user->wallet->id),
            "Retrait Transactions" => $this->getTodayTransactionCommissionAmountTotal(WalletTransaction::class, $user->wallet->id),
            "Retrait Crédits" => $this->getTodayTransactionCommissionAmountTotal(WalletPhoneCredit::class, $user->wallet->id),
            "Retrait International" => $this->getTodayTransactionCommissionAmountTotal(WalletInter::class, $user->wallet->id),
            "Retrait Virements" => $this->getTodayTransactionCommissionAmountTotal(WalletBank::class, $user->wallet->id),
            "Retrait Rechargements" => $this->getTodayTransactionCommissionAmountTotal(WalletDeposit::class, $user->wallet->id),
            "Retrait Depots" => $this->getTodayTransactionRobusDepotCommissionAmountTotal(WalletTransaction::class, $user->wallet->id),
            "Retrait Retraits" => $this->getTodayTransactionRobusRetraitCommissionAmountTotal(WalletTransaction::class, $user->wallet->id),
            "Retrait Moneys" => $this->getTodayTransactionMobileCommissionAmountTotal(WalletTransaction::class, $user->wallet->id),
            "Retrait Bank Sends" => $this->getTodayTransactionMobileCommissionAmountTotal(WalletBankSend::class, $user->wallet->id),
            // New amount
            "Retrait Xbet" => $this->getTodayTransactionMobileCommissionAmountTotal(WalletXbet::class, $user->wallet->id),
            "Retrait Push" => $this->getTodayTransactionMobileCommissionAmountTotal(WalletPush::class, $user->wallet->id),
            "Retrait Versement" => $this->getTodayTransactionMobileCommissionAmountTotal(WalletVersement::class, $user->wallet->id),
            "Retrait Tresort" => $this->getTodayTransactionMobileCommissionAmountTotal(WalletTresortMoney::class, $user->wallet->id),
            "Retrait Djamo" => $this->getTodayTransactionMobileCommissionAmountTotal(WalletDjamo::class, $user->wallet->id),


        ];

        $resetMappings = [
            "Retrait Paiements" => WalletPayment::class,
            "Retrait Transactions" => WalletTransaction::class,
            "Retrait Crédits" => WalletPhoneCredit::class,
            "Retrait International" => WalletInter::class,
            "Retrait Virements" => WalletBank::class,
            "Retrait Rechargements" => WalletDeposit::class,
            "Retrait Depots" => WalletTransaction::class,
            "Retrait Retraits" => WalletTransaction::class,
            "Retrait Moneys" => WalletTransaction::class,
            "Retrait Xbet" => WalletXbet::class,
            "Retrait Push" => WalletPush::class,
            "Retrait Versement" => WalletVersement::class,
            "Retrait Tresort" => WalletTresortMoney::class,
            "Retrait Djamo" => WalletDjamo::class,
                "Retrait Bank Sends" => WalletBankSend::class,

        ];


        $validator = Validator::make(

            $request->all(),
            [
                'amountDrawal' => 'required',
            ],
            [

                'amountDrawal.required' => 'Votre montant est obligatoire',

            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Le montant est  obligatoire',
                    'data' => $validator->errors()
                ],
                333
            );
        }

        if ($amount <= 0) {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Le montant de la commission est invalide ou null !!')
                ->withInput();
        }


        if (!isset($gainMappings[$typeRemonte])) {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Type de remontée invalide !!')
                ->withInput();
        }

        $calculatedGainAmount = $gainMappings[$typeRemonte];


        if ((string)$amount !== (string)$calculatedGainAmount) {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Montant de la commission invalide !!')
                ->withInput();
        }

        $amount_old_solde = (float)$user->wallet->amount;

        $resetClass = $resetMappings[$typeRemonte];

        $amount_solde = (float)$user->wallet->amount + (float)$amount;
        $amount_before_solde = (float)$user->wallet->amount ;

        $amountDrawa = $this->withdrawCommission($resetClass, $user, $request->amountDrawal);

        if ($amountDrawa === true) {

            $withRemonte = $this->createWithCommission($amount_solde, $request->amountDrawal, $user->id, $typeRemonte, $amount_old_solde , $amount_before_solde);

            if (isset($resetMappings[$typeRemonte])) {
                if ($admin_civ->count() > 0) {
                    $nmbre_admin = $admin_civ->count();
                    $valeur_single = $withRemonte->amount_with_draw / $nmbre_admin;

                    foreach ($admin_civ as $userAdmin) {
                        $this->processAdminCommissionSDistributer($userAdmin->id, $withRemonte->id, $valeur_single);
                    }
                }
            }

            $msg = 'Admins a fait un retrait sur votre commission';
            $msg_title = 'Transfert de la commission';
            $user->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));

            return back()
                ->with('color', 'success')
                ->with('status', 'Votre commission a été retirée avec succès !!')
                ->withInput();
        } else {
            // Il y a eu une erreur
            return back()
                ->with('color', 'danger')
                ->with('status', $amountDrawa) // L'erreur renvoyée par withdrawCommission
                ->withInput();
        }
    }

    public  function remonteCommissionCompanyAdmins($amount, $typeRemonte, int $id, Request $request)
    {


        $user = SidUser::find($id);
        $admin_civ =  SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

        if (!$user) {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Utilisateur introuvable !!')
                ->withInput();
        }


        $gainMappings = [
            "Retrait Paiements" => $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletPayment::class, $user->company_id, $user->id),
            "Retrait Transactions" => $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletTransaction::class, $user->company_id, $user->id),
            "Retrait Crédits" => $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletPhoneCredit::class, $user->company_id, $user->id),
            "Retrait International" => $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletInter::class, $user->company_id, $user->id),
            "Retrait Virements" => $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletBank::class, $user->company_id, $user->id),
            "Retrait Rechargements" => $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletDeposit::class, $user->company_id, $user->id),
            "Retrait Depots" => $this->getTodayTransactionRobusDepotCompagnieCommissionAmountTotal(WalletTransaction::class, $user->company_id, $user->id),
            "Retrait Retraits" => $this->getTodayTransactionRobusRetraitCompagnieCommissionAmountTotal(WalletTransaction::class, $user->company_id, $user->id),
            "Retrait Moneys" => $this->getTodayTransactionMobileCompagnieCommissionAmountTotal(WalletTransaction::class, $user->company_id, $user->id),
            "Retrait Bank Sends" => $this->getTodayTransactionMobileCompagnieCommissionAmountTotal(WalletBankSend::class, $user->company_id, $user->id),
            // New amount
            "Retrait Xbet" => $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletXbet::class, $user->company_id, $user->id),
            "Retrait Push" => $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletPush::class, $user->company_id, $user->id),
            "Retrait Versement" => $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletVersement::class, $user->company_id, $user->id),
            "Retrait Tresort" => $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletTresortMoney::class, $user->company_id, $user->id),
            "Retrait Djamo" => $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletDjamo::class, $user->company_id, $user->id)
        ];

        $resetMappings = [
            "Retrait Paiements" => WalletPayment::class,
            "Retrait Transactions" => WalletTransaction::class,
            "Retrait Crédits" => WalletPhoneCredit::class,
            "Retrait International" => WalletInter::class,
            "Retrait Virements" => WalletBank::class,
            "Retrait Rechargements" => WalletDeposit::class,
            "Retrait Depots" => WalletTransaction::class,
            "Retrait Retraits" => WalletTransaction::class,
            "Retrait Moneys" => WalletTransaction::class,
            "Retrait Xbet" => WalletXbet::class,
            "Retrait Push" => WalletPush::class,
            "Retrait Versement" => WalletVersement::class,
            "Retrait Tresort" => WalletTresortMoney::class,
            "Retrait Djamo" => WalletDjamo::class,
            "Retrait Bank Sends" => WalletBankSend::class,
        ];


        $validator = Validator::make(

            $request->all(),
            [
                'amountDrawal' => 'required',
            ],
            [

                'amountDrawal.required' => 'Votre montant est obligatoire',

            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Le montant est  obligatoire',
                    'data' => $validator->errors()
                ],
                333
            );
        }

        if ($amount <= 0) {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Le montant de la commission est invalide ou null !!')
                ->withInput();
        }


        if (!isset($gainMappings[$typeRemonte])) {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Type de remontée invalide !!')
                ->withInput();
        }

        $calculatedGainAmount = $gainMappings[$typeRemonte];


        if ((string)$amount !== (string)$calculatedGainAmount) {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Montant de la commission invalide !!')
                ->withInput();
        }

        $amount_solde = (float)$user->wallet->amount + (float)$request->amountDrawal;
        $amount_before_solde = (float)$user->wallet->amount;

        $resetClass = $resetMappings[$typeRemonte];

        $amountDrawa = $this->withdrawCommission($resetClass, $user, $request->amountDrawal);

        if ($amountDrawa === true) {

            $walletFreshAmount = (float) $user->wallet()->value('amount');

            $withRemonte = $this->createWithCommission($amount_solde, $request->amountDrawal, $user->id, $typeRemonte, $walletFreshAmount , $amount_before_solde);

            if (isset($resetMappings[$typeRemonte])) {
                if ($admin_civ->count() > 0) {
                    $nmbre_admin = $admin_civ->count();
                    $valeur_single = $withRemonte->amount_with_draw / $nmbre_admin;

                    foreach ($admin_civ as $userAdmin) {
                        $this->processAdminCommissionSDistributer($userAdmin->id, $withRemonte->id, $valeur_single);
                    }
                }
            }

            $msg = 'Admins a fait un retrait sur votre commission';
            $msg_title = 'Transfert de la commission';
            $user->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));

            return back()
                ->with('color', 'success')
                ->with('status', 'Votre commission a été retirée avec succès !!')
                ->withInput();
        } else {
            // Il y a eu une erreur
            return back()
                ->with('color', 'danger')
                ->with('status', $amountDrawa) // L'erreur renvoyée par withdrawCommission
                ->withInput();
        }
    }

    private function processAdminCommissionSDistributer($userId, $walletTransactionId, $amount)
    {
        $admin_single = SidUser::find($userId);

        AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'with_merchant_id' => $walletTransactionId,
            'description' => 'remonte commission sous distributeur',
            'status'  => "SUCCESS",
            'amount_under' =>  0,
            'description_underdistributer' =>   null
        ]);

        $admin_single->wallet->increment('amount', $amount);
    }

    public function remonteAllCommission($amount, $typeRemonte, int $id)
    {


        $user = SidUser::find($id);

        if (!$user) {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Utilisateur introuvable !!')
                ->withInput();
        }

        if ($user->is_under_distributer == true || $user->is_api == true) {
            $gain_deposits = $this->getTodayTransactionCommissionAmountTotal(WalletDeposit::class, $user->wallet->id);
            $gain_payments = $this->getTodayTransactionCommissionAmountTotal(WalletPayment::class, $user->wallet->id);
            $gain_transactions = $this->getTodayTransactionCommissionAmountTotal(WalletTransaction::class, $user->wallet->id);
            $gain_phone_credits = $this->getTodayTransactionCommissionAmountTotal(WalletPhoneCredit::class,  $user->wallet->id);
            $gain_inters = $this->getTodayTransactionCommissionAmountTotal(WalletInter::class, $user->wallet->id);
            $gain_banks = $this->getTodayTransactionCommissionAmountTotal(WalletBank::class, $user->wallet->id);
            $gain_xbets = $this->getTodayTransactionCommissionAmountTotal(WalletXbet::class, $user->wallet->id);
            $gain_versments = $this->getTodayTransactionCommissionAmountTotal(WalletVersement::class, $user->wallet->id);
            $gain_pushs = $this->getTodayTransactionCommissionAmountTotal(WalletPush::class, $user->wallet->id);
            $gain_djamos = $this->getTodayTransactionCommissionAmountTotal(WalletDjamo::class, $user->wallet->id);
            $gain_tresorts = $this->getTodayTransactionCommissionAmountTotal(WalletTresortMoney::class, $user->wallet->id);
                $gain_bank_sends = $this->getTodayTransactionCommissionAmountTotal(WalletBankSend::class, $user->wallet->id);
        } elseif ($user->is_company == true) {
            $gain_deposits = $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletDeposit::class, $user->company_id, $user->id);
            $gain_payments = $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletPayment::class, $user->company_id, $user->id);
            $gain_transactions = $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletTransaction::class, $user->company_id, $user->id);
            $gain_phone_credits = $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletPhoneCredit::class,  $user->company_id, $user->id);
            $gain_inters = $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletInter::class, $user->company_id, $user->id);
            $gain_banks = $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletBank::class, $user->company_id, $user->id);
            $gain_xbets = $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletXbet::class, $user->company_id, $user->id);
            $gain_versments = $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletVersement::class, $user->company_id, $user->id);
            $gain_pushs = $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletPush::class, $user->company_id, $user->id);
            $gain_djamos = $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletDjamo::class, $user->company_id, $user->id);
            $gain_tresorts = $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletTresortMoney::class, $user->company_id, $user->id);
            $gain_bank_sends = $this->getTodayTransactionCommissionCompagnieAmountTotal(WalletBankSend::class, $user->company_id, $user->id);

        } else {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Le montant de la commission est invalide ou utilisateur introuvable !!')
                ->withInput();
        }

        $company_gains = $gain_payments + $gain_transactions + $gain_phone_credits + $gain_inters + $gain_banks + $gain_deposits
            + $gain_xbets + $gain_versments + $gain_pushs + $gain_djamos + $gain_tresorts + $gain_bank_sends;


        if ($amount <= 0) {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Le montant de la commission est invalide ou null !!')
                ->withInput();
        }


        if ((string)$amount !== (string) $company_gains) {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Montant de la commission invalide !!')
                ->withInput();
        }

        $walletFreshAmount = (float) $user->wallet()->value('amount');

        $amount_solde = (float)$user->wallet->amount + (float)$amount;
        $amount_before_solde = (float)$user->wallet->amount;

        $withRemonte = $this->createWithCommission($amount_solde, $amount, $user->id,  $typeRemonte, $walletFreshAmount, $amount_before_solde);

        if ($withRemonte !== null) {

            $user->wallet->update([
                'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                'sid_user_relod' => sabdy()->id,
                'amount' => DB::raw('amount+'  .  $withRemonte->amount_with_draw),
            ]);

            $this->getUpdateTransactionCommissionAmountNullTotal(WalletPayment::class, $user->wallet->id);
            $this->getUpdateTransactionCommissionAmountNullTotal(WalletTransaction::class, $user->wallet->id);
            $this->getUpdateTransactionCommissionAmountNullTotal(WalletPhoneCredit::class, $user->wallet->id);
            $this->getUpdateTransactionCommissionAmountNullTotal(WalletInter::class, $user->wallet->id);
            $this->getUpdateTransactionCommissionAmountNullTotal(WalletDeposit::class, $user->wallet->id);
            $this->getUpdateTransactionCommissionAmountNullTotal(WalletBank::class, $user->wallet->id);

            $this->getUpdateTransactionCommissionAmountNullTotal(WalletXbet::class, $user->wallet->id);
            $this->getUpdateTransactionCommissionAmountNullTotal(WalletVersement::class, $user->wallet->id);
            $this->getUpdateTransactionCommissionAmountNullTotal(WalletPush::class, $user->wallet->id);
            $this->getUpdateTransactionCommissionAmountNullTotal(WalletDjamo::class, $user->wallet->id);
            $this->getUpdateTransactionCommissionAmountNullTotal(WalletTresortMoney::class, $user->wallet->id);
            $this->getUpdateTransactionCommissionAmountNullTotal(WalletBankSend::class, $user->wallet->id);



            $msg = 'Vous avez transféré vos commissions sur votre compte';
            $msg_title = 'Transfert de la commission';
            $user->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));

            return back()
                ->with('color', 'success')
                ->with('status', 'Votre commission a été remontée avec succès !!')
                ->withInput();
        } else {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Erreur de recuperation du montant de la commission !!')
                ->withInput();
        }
    }

    public function remonteAllCountryCommission(int $id)
    {
        $countryDetails = Country::find($id);

        if (!$countryDetails) {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Pays non trouvé !')
                ->withInput();
        }

        $usersToRemonte = SidUser::where('country_id', $countryDetails->id)
            ->where('is_under_distributer', true)
            ->get();

        $successCount = 0;

        foreach ($usersToRemonte as $user) {

            if (!$user->wallet) {
                continue;
            }

            $commissionModels = [
                "Commission sur Rechargements"         => WalletDeposit::class,
                "Commission sur Paiements"             => WalletPayment::class,
                "Commission sur Transactions"          => WalletTransaction::class,
                "Commission sur Crédits"   => WalletPhoneCredit::class,
                "Commission sur International" => WalletInter::class,
                "Commission sur Virements"              => WalletBank::class,
                "Commission sur Xbets"                  => WalletXbet::class,
                "Commission sur Versement"             => WalletVersement::class,
                "Commission sur Push"                  => WalletPush::class,
                "Commission sur Djamo"                 => WalletDjamo::class,
                "Commission sur Tresort"         => WalletTresortMoney::class,
                "Commission sur BankSend"            => WalletBankSend::class,
            ];

            foreach ($commissionModels as $type => $model) {

                $commission = $model::where('wallet_id', $user->wallet->id)
                    ->where('status', 'SUCCESS')
                    ->sum('commissions');

                if ($commission <= 0) {
                    continue;
                }

                $model::where('wallet_id', $user->wallet->id)
                    ->where('status', 'SUCCESS')
                    ->update(['commissions' => 0]);

                // Nouveau solde
                $walletFreshAmount = (float) $user->wallet()->value('amount');

                $amount_solde = $walletFreshAmount + (float) $commission;
                $amount_before_solde = $walletFreshAmount ;

                $withRemonte = $this->createWithCommission(
                    $amount_solde,
                    $commission,
                    $user->id,
                    $type,
                    $walletFreshAmount,
                    $amount_before_solde
                );

                if ($withRemonte) {

                    // Mise à jour du wallet
                    $user->wallet->update([
                        'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                        'sid_user_relod' => sabdy()->id,
                        'amount' => DB::raw('amount + ' . $withRemonte->amount_with_draw),
                    ]);

                    // Notif
                    $msg = "Votre commission ($type) a été transférée sur votre compte.";
                    $msg_title = 'Transfert de commission par ' . sabdy()->firstname . ' ' . sabdy()->lastname;

                    $user->notify(new UserNotification([
                        'title' => $msg_title,
                        'message' => $msg
                    ]));

                    $successCount++;
                }
            }
        }

        if ($successCount > 0) {
            return back()
                ->with('color', 'success')
                ->with('status', "$successCount commission(s) ont été remontée(s) avec succès !")
                ->withInput();
        }

        return back()
            ->with('color', 'info')
            ->with('status', 'Aucune commission à remonter pour ce pays ou aucun sous-distributeur trouvé.')
            ->withInput();
    }

    public function remonteAllCountryCompanies(int $id)
    {
        $countryDetails = Country::find($id);

        if (!$countryDetails) {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Pays non trouvé !')
                ->withInput();
        }

        $companieCountries = SidUser::where('country_id', $countryDetails->id)
            ->where('is_company', true)
            ->where('company_id', '!=', 1)
            ->get();

        $successCount = 0;

        foreach ($companieCountries as $company) {


            if (!$company->wallet) {
                continue;
            }

            $usersCompanieToRemonte = SidUser::where('company_id', $company->id)
                ->where('is_under_distributer', true)
                ->get();



            $commissionModels = [
                "Commission sur Rechargements"         => WalletDeposit::class,
                "Commission sur Paiements"             => WalletPayment::class,
                "Commission sur Transactions"          => WalletTransaction::class,
                "Commission sur Crédits"   => WalletPhoneCredit::class,
                "Commission sur International" => WalletInter::class,
                "Commission sur Virements"              => WalletBank::class,
                "Commission sur Xbets"                  => WalletXbet::class,
                "Commission sur Versement"             => WalletVersement::class,
                "Commission sur Push"                  => WalletPush::class,
                "Commission sur Djamo"                 => WalletDjamo::class,
                "Commission sur Tresort"         => WalletTresortMoney::class,
                "Commission sur BankSend"            => WalletBankSend::class,
            ];

            foreach ($usersCompanieToRemonte as $user) {


                // 1. Parcours de tous les modèles pour cet utilisateur
                foreach ($commissionModels as $type => $model) {

                    $commission = $model::where('wallet_id', $user->wallet->id)
                        ->where('status', 'SUCCESS')
                        ->sum('commission_companie');

                    if ($commission <= 0) {
                        continue;
                    }

                    $model::where('wallet_id', $user->wallet->id)
                        ->where('status', 'SUCCESS')
                        ->update(['commission_companie' => 0]);

                    // Nouveau solde
                    $walletFreshAmount = (float) $company->wallet()->value('amount');

                    $amount_solde = $walletFreshAmount + (float) $commission;

                    $amount_before_solde = $walletFreshAmount;

                    $withRemonte = $this->createWithCommission(
                        $amount_solde,
                        $commission,
                        $company->id,
                        $type,
                        $walletFreshAmount,
                        $amount_before_solde
                        
                    );

                    if ($withRemonte) {

                        // Mise à jour du wallet
                        $company->wallet->update([
                            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                            'sid_user_relod' => sabdy()->id,
                            'amount'         => DB::raw('amount + ' . ((float)$withRemonte->amount_with_draw)),
                        ]);

                        $msg = "Votre commission ($type) a été transférée sur votre compte.";
                        $msg_title = 'Transfert de commission par ' . sabdy()->firstname . ' ' . sabdy()->lastname;

                        $company->notify(new UserNotification([
                            'title' => $msg_title,
                            'message' => $msg
                        ]));

                        $successCount++;
                    }
                }
            }
        }

        if ($successCount > 0) {
            return back()
                ->with('color', 'success')
                ->with('status', "$successCount commission(s) ont été remontée(s) avec succès !")
                ->withInput();
        }

        return back()
            ->with('color', 'info')
            ->with('status', 'Aucune commission à remonter pour ce pays ou aucun sous-distributeur trouvé.')
            ->withInput();
    }


    public function calculateAndClearTotalCommission(array $walletModels, int $walletId): float
    {
        $grandTotalCommission = 0.0;

        foreach ($walletModels as $walletModel) {

            // 1. Calcule la somme des commissions pour ce modèle
            $totalForModel = $walletModel::where('wallet_id', $walletId)
                ->whereStatus('SUCCESS')
                ->sum('commissions');

            if ($totalForModel > 0) {
                // 2. Met à jour la colonne 'commissions' à 0 (Nettoyage/Remise à null)
                $walletModel::where('wallet_id', $walletId)
                    ->whereStatus('SUCCESS')
                    ->update(['commissions' => 0]);

                // 3. Ajoute au total général
                $grandTotalCommission += $totalForModel;
            }
        }

        return $grandTotalCommission;
    }

    public function getUpdateTransactionCommissionAmountNullTotal($walletModel, $sidUserId)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', $sidUserId)
            ->whereStatus('SUCCESS')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        $walletModel::where('wallet_id', $sidUserId)
            ->whereStatus('SUCCESS')
            ->update(['commissions' => 0]);

        return $totalAmount;
    }


    public function getUpdateTransactionDepotRobusCommissionAmountNullTotal($walletModel, $sidUserId)
    {



        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', $sidUserId)
            ->whereStatus('SUCCESS')
            ->where('provider_id', '=', 9)
            ->where('type_transaction', 'Depot')
            ->sum('commissions');


        if ($amount !== null) {
            $totalAmount += $amount;
        }

        $walletModel::where('wallet_id', $sidUserId)
            ->whereStatus('SUCCESS')
            ->where('provider_id', '=', 9)
            ->where('type_transaction', 'Depot')
            ->update(['commissions' => 0]);

        return $totalAmount;
    }

    public function getUpdateTransactionRetraitRobusCommissionAmountNullTotal($walletModel, $sidUserId)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', $sidUserId)
            ->whereStatus('SUCCESS')
            ->where('provider_id', 9)
            ->where('type_transaction', 'Retrait')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        $walletModel::where('wallet_id', $sidUserId)
            ->whereStatus('SUCCESS')
            ->where('provider_id', 9)
            ->where('type_transaction', 'Retrait')
            ->update(['commissions' => 0]);

        return $totalAmount;
    }

    public function getUpdateTransactionMoneyCommissionAmountNullTotal($walletModel, $sidUserId)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', $sidUserId)
            ->whereStatus('SUCCESS')
            ->where('provider_id', '!=', 9)
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        $walletModel::where('wallet_id', $sidUserId)
            ->whereStatus('SUCCESS')
            ->where('provider_id', '!=', 9)
            ->update(['commissions' => 0]);

        return $totalAmount;
    }

    public function getUpdateTransactionEmplCommissionAmountNullTotal($walletModel, $sidUserId)
    {

        $transactions = $walletModel::where('wallet_id', $sidUserId)
            ->whereStatus('SUCCESS')
            ->get();

        $totalAmount = 0;

        foreach ($transactions as $transaction) {
            if ($transaction->commissions !== null) {
                $totalAmount += $transaction->commissions;
                $transaction->commissions = null;
                $transaction->save();
            }
        }

        return $totalAmount;
    }



    private function createWithCommission($amount_solde, $gain_amount, $userId, $typeRemonte, $oldSolde , $amount_before_solde = null)
    {
        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        return WithMerchant::create([
            'amount_with_draw' =>  $gain_amount,
            'sid_user_id' =>   $userId,
            'ref' => 'RB_' . 'ROBUS' . '_' . $transactionCode,
            'amount_solde' =>   $amount_solde,
            'type_transaction' => $typeRemonte,
            'status' =>  "SUCCESS",
            'old_balance' => $oldSolde,
            'balance_before' =>  $amount_before_solde,
            'date_removed' => Carbon::now(),
        ]);
    }
    public function getTodayTransactionCommissionAmountTotal($walletModel, $userID)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', $userID)
            ->whereStatus('SUCCESS')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }

    public function getTodayTransactionCommissionCompagnieAmountTotal($walletModel, $idCompagnie, $id)
    {


        $sidUsers = SidUser::where('company_id', $idCompagnie)
            ->where('id', '!=', $id)
            ->get();

        $totalAmount = 0;


        foreach ($sidUsers as $sidUser) {
            // Vérifie que le wallet existe avant d’y accéder
            if (!$sidUser->wallet) {
                continue; // passe à l'utilisateur suivant
            }

            $sidUserId = $sidUser->wallet->id;

            $amount = $walletModel::where('wallet_id', $sidUserId)
                ->where('status', 'SUCCESS')
                ->sum('commission_companie');

            $totalAmount += $amount ?? 0;
        }

        return $totalAmount;
    }

    public function getTransactionsAmountTotal($walletModel, $userID, $period = 'daily')
    {

        $totalAmount = 0;
        $query = $walletModel::where('wallet_id', $userID)
            ->whereStatus('SUCCESS');

        if ($period === 'daily') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($period === 'monthly') {
            $query->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);
        }

        $amount = $query->sum('amount');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }

    public function getCountsTransactionsAmountTotal($walletModel, $userID, $period = 'daily')
    {


        $query = $walletModel::where('wallet_id', $userID)
            ->whereStatus('SUCCESS');

        if ($period === 'daily') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($period === 'monthly') {
            $query->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);
        }

        return $query->count();
    }

    public function withdrawCommission($walletModel, $user, $amount)
    {
        $walletTransaction = $walletModel::where('wallet_id', $user->wallet->id)
            ->where('status', 'SUCCESS')
            ->whereCommissions($amount)
            ->first();

        if ($walletTransaction) {
            $walletTransaction->commissions -= $amount;
            if ($walletTransaction->commissions < 0) {
                return 'Le retrait dépasse la commission disponible.';
            }
            $walletTransaction->save();
            return true;
        } else {
            return 'Aucune transaction trouvée avec ce montant pour ce retrait de ' . $amount;
        }
    }


    public function getTodayTransactionRobusRetraitCommissionAmountTotal($walletModel, $userID)
    {

        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', $userID)
            ->whereStatus('SUCCESS')
            ->where('provider_id', 9)
            ->where('type_transaction', 'Retrait')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }


    public function getTodayTransactionRobusRetraitCompagnieCommissionAmountTotal($walletModel, $idCompagnie, $id)
    {

        $sidUsers = SidUser::where('company_id', $idCompagnie)
            ->where('id', '!=', $id)
            ->get();

        $totalAmount = 0;

        foreach ($sidUsers as $sidUser) {
            // Vérifie que le wallet existe avant d’y accéder
            if (!$sidUser->wallet) {
                continue; // passe à l'utilisateur suivant
            }

            $sidUserId = $sidUser->wallet->id;

            $amount = $walletModel::where('wallet_id', $sidUserId)
                ->where('status', 'SUCCESS')
                ->where('provider_id', 9)
                ->where('type_transaction', 'Retrait')
                ->sum('commission_companie');

            $totalAmount += $amount ?? 0;
        }


        return $totalAmount;
    }

    public function getTodayTransactionRobusDepotCommissionAmountTotal($walletModel, $userID)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id',  $userID)
            ->whereStatus('SUCCESS')
            ->where('provider_id', 9)
            ->where('type_transaction', 'Depot')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }


    public function getTodayTransactionRobusDepotCompagnieCommissionAmountTotal($walletModel, $idCompagnie, $id)
    {


        $sidUsers = SidUser::where('company_id', $idCompagnie)
            ->where('id', '!=', $id)
            ->get();

        $totalAmount = 0;


        foreach ($sidUsers as $sidUser) {
            // Vérifie que le wallet existe avant d’y accéder
            if (!$sidUser->wallet) {
                continue; // passe à l'utilisateur suivant
            }

            $sidUserId = $sidUser->wallet->id;

            $amount = $walletModel::where('wallet_id', $sidUserId)
                ->whereStatus('SUCCESS')
                ->where('provider_id', 9)
                ->where('type_transaction', 'Depot')
                ->sum('commission_companie');

            $totalAmount += $amount ?? 0;
        }

        return $totalAmount;
    }

    public function getTodayTransactionMobileCommissionAmountTotal($walletModel, $userID)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id',  $userID)
            ->whereStatus('SUCCESS')
            ->where('provider_id', '!=', 9)
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }

    public function getTodayTransactionMobileCompagnieCommissionAmountTotal($walletModel, $idCompagnie, $id)
    {


        $sidUsers = SidUser::where('company_id', $idCompagnie)
            ->where('id', '!=', $id)
            ->get();

        $totalAmount = 0;


        foreach ($sidUsers as $sidUser) {
            // Vérifie que le wallet existe avant d’y accéder
            if (!$sidUser->wallet) {
                continue; // passe à l'utilisateur suivant
            }

            $sidUserId = $sidUser->wallet->id;

            $amount = $walletModel::where('wallet_id', $sidUserId)
                ->where('status', 'SUCCESS')
                ->where('provider_id', '!=', 9)
                ->sum('commission_companie');

            $totalAmount += $amount ?? 0;
        }



        return $totalAmount;
    }


    public function showdistributors(int $id, Request $request)
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('modifier-administrateur')) {
            abort(403);
        }

        $paiement_status = null;

        $user = SidUser::whereId($id)->first();

        $user = SidUser::whereId($id)->first();

        $wallet_solde = Wallet::where('sid_user_id', $user->id)->first();

        $usersCountry = Auth::guard('web')->user()->country->currencie->name;

        $kycs = $user->kyc()->get() ?? null;

        $roles = Role::all();

        $countries = Country::where('status', true)->get();

        // Wallet Error and other
        $sum_error    = $this->getCountWalletOperatorsTransaction($user->id, 'ERROR');
        $sum_success    = $this->getCountWalletOperatorsTransaction($user->id, 'SUCCESS');
        $pending_success    = $this->getCountWalletOperatorsTransaction($user->id, 'PENDING');
        $totalSumAmounts = $this->getTotalSumTransactionsOperatorsAmount($user);
        $sum_wallet_Inter_received = $this->transactionAccountService->getTransactionsRecevedAllAmount(WalletInter::class, 'number_compte', $wallet_solde);
        $sum_wallet_reload_received =  $this->transactionAccountService->getTransactionsRecevedAllAmount(WalletReload::class, 'account_wallet', $wallet_solde);
        $sum_wallet_transaction_received = $this->transactionAccountService->getTransactionsRecevedAllAmount(WalletTransaction::class, 'num_wallet', $wallet_solde);

        $count_days_inters = $this->getCountsValidatedAmountTotal(WalletInter::class, $user->id, 'daily');
        $amount_days_inters = $this->getTransactionOperatriceAmountTotal(WalletInter::class, $user->id, 'daily');
        $count_mounth_inters = $this->getCountsValidatedAmountTotal(WalletInter::class, $user->id, 'monthly');
        $amount_mounth_inters = $this->getTransactionOperatriceAmountTotal(WalletInter::class, $user->id, 'monthly');


        return view('pages.admin.show_operators', compact(
            'user',
            'roles',
            'count_days_inters',
            'amount_days_inters',
            'count_mounth_inters',
            'amount_mounth_inters',
            'sum_error',
            'sum_success',
            'pending_success',
            'countries',
            'usersCountry',
            'sum_wallet_Inter_received',
            'sum_wallet_reload_received',
            'sum_wallet_transaction_received',
            'totalSumAmounts',
            'kycs'

        ));
    }


    public function getCountsValidatedAmountTotal($walletModel, $userID, $period = 'daily')
    {


        $query = $walletModel::where('updated_by', $userID);

        if ($period === 'daily') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($period === 'monthly') {
            $query->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);
        }

        return $query->count();
    }


    public function getTransactionOperatriceAmountTotal($walletModel, $userID, $period = 'daily')
    {

        $totalAmount = 0;
        $query = $walletModel::where('updated_by', $userID);

        if ($period === 'daily') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($period === 'monthly') {
            $query->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);
        }

        $amount = $query->sum('amount');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }

    public function getTotalSumTransactionsOperatorsAmount($user)
    {
        $sum_wallet_transaction = $this->transactionAccountService->getTransactionOperatorsAmount(WalletTransaction::class, $user->id);
        $sum_wallet_bank = $this->transactionAccountService->getTransactionOperatorsAmount(WalletBank::class, $user->id);
        $sum_wallet_deposit = $this->transactionAccountService->getTransactionOperatorsAmount(WalletDeposit::class, $user->id);
        $sum_wallet_payment = $this->transactionAccountService->getTransactionOperatorsAmount(WalletPayment::class, $user->id);
        $sum_wallet_phone = $this->transactionAccountService->getTransactionOperatorsAmount(WalletPhoneCredit::class, $user->id);
        $sum_wallet_wave = $this->transactionAccountService->getTransactionOperatorsAmount(WalletWave::class, $user->id);
        $sum_wallet_Reload = $this->transactionAccountService->getTransactionOperatorsAmount(WalletReload::class, $user->id);
        $sum_wallet_xbet_depot = $this->transactionAccountService->getTransactionOperatorsAmount(WalletXbet::class, $user->id);
        $sum_wallet_xbet_retrait = $this->transactionAccountService->getTransactionOperatorsAmount(WalletXbet::class, $user->id);
        $sum_wallet_Inter = $this->transactionAccountService->getTransactionOperatorsAmount(WalletInter::class,  $user);

        $sum_wallet_push = $this->transactionAccountService->getTransactionOperatorsAmount(WalletPush::class,  $user);
        $sum_wallet_djamo = $this->transactionAccountService->getTransactionOperatorsAmount(WalletDjamo::class,  $user);
        $sum_wallet_tresortMoney = $this->transactionAccountService->getTransactionOperatorsAmount(WalletTresortMoney::class,  $user);
        $sum_wallet_versement = $this->transactionAccountService->getTransactionOperatorsAmount(WalletVersement::class,  $user);
        $sum_wallet_bankSend = $this->transactionAccountService->getTransactionOperatorsAmount(WalletBankSend::class,  $user);


        return [
            'sum_wallet_transaction' => $sum_wallet_transaction,
            'sum_wallet_bank' => $sum_wallet_bank,
            'sum_wallet_deposit' => $sum_wallet_deposit,
            'sum_wallet_payment' => $sum_wallet_payment,
            'sum_wallet_phone' => $sum_wallet_phone,
            'sum_wallet_Reload' => $sum_wallet_Reload,
            'sum_wallet_wave' => $sum_wallet_wave,
            'sum_wallet_xbet_depot' => $sum_wallet_xbet_depot,
            'sum_wallet_xbet_retrait' => $sum_wallet_xbet_retrait,
            'sum_wallet_Inter' => $sum_wallet_Inter,
            'sum_wallet_push' => $sum_wallet_push,
            'sum_wallet_djamo' => $sum_wallet_djamo,
            'sum_wallet_tresortMoney' => $sum_wallet_tresortMoney,
            'sum_wallet_versement' => $sum_wallet_versement,
            'sum_wallet_bankSend' => $sum_wallet_bankSend
        ];
    }

    public function getTotalSumTransactionsAmount($wallet_solde)
    {
        $sum_wallet_transaction = $this->transactionAccountService->getTransactionAmount(WalletTransaction::class, $wallet_solde->id);
        $sum_wallet_bank = $this->transactionAccountService->getTransactionAmount(WalletBank::class, $wallet_solde->id);
        $sum_wallet_deposit = $this->transactionAccountService->getTransactionAmount(WalletDeposit::class, $wallet_solde->id);
        $sum_wallet_payment = $this->transactionAccountService->getTransactionAmount(WalletPayment::class, $wallet_solde->id);
        $sum_wallet_phone = $this->transactionAccountService->getTransactionAmount(WalletPhoneCredit::class, $wallet_solde->id);
        $sum_wallet_Reload = $this->transactionAccountService->getTransactionAmount(WalletReload::class, $wallet_solde->id);
        $sum_wallet_xbet_depot = $this->transactionAccountService->getTransactionXbetAmount(WalletXbet::class, $wallet_solde->id, 1);
        $sum_wallet_xbet_retrait = $this->transactionAccountService->getTransactionXbetAmount(WalletXbet::class, $wallet_solde->id, 2);
        $sum_wallet_Inter = $this->transactionAccountService->getTransactionAmount(WalletInter::class,  $wallet_solde->id);
        $sum_wallet_Inter_received = $this->transactionAccountService->getTransactionsRecevedAllAmount(WalletInter::class, 'number_compte', $wallet_solde);
        $sum_wallet_reload_received =  $this->transactionAccountService->getTransactionsRecevedAllAmount(WalletReload::class, 'account_wallet', $wallet_solde);
        // Wallet commissions
        $sum_commissions  = $this->getCommissionsWalletTransaction($wallet_solde->id);
        $sum_drawals  =  $this->getWalletTransactionSumRetraitsProperty($wallet_solde->id);
        $sum_wallet_transaction_received = $this->transactionAccountService->getTransactionsRecevedAllAmount(WalletTransaction::class, 'num_wallet', $wallet_solde);
        $sum_wallet_wave = $this->transactionAccountService->getTransactionAmount(WalletWave::class, $wallet_solde->id);


        return [
            'sum_wallet_transaction' => $sum_wallet_transaction,
            'sum_wallet_bank' => $sum_wallet_bank,
            'sum_wallet_deposit' => $sum_wallet_deposit,
            'sum_wallet_payment' => $sum_wallet_payment,
            'sum_wallet_phone' => $sum_wallet_phone,
            'sum_wallet_Reload' => $sum_wallet_Reload,
            'sum_wallet_xbet_depot' => $sum_wallet_xbet_depot,
            'sum_wallet_xbet_retrait' => $sum_wallet_xbet_retrait,
            'sum_wallet_Inter' => $sum_wallet_Inter,
            'sum_wallet_Inter_received' => $sum_wallet_Inter_received,
            'sum_wallet_reload_received' => $sum_wallet_reload_received,
            'sum_commissions' => $sum_commissions,
            'sum_drawals' => $sum_drawals,
            'sum_wallet_wave' => $sum_wallet_wave,
            'sum_wallet_transaction_received' => $sum_wallet_transaction_received,
        ];
    }


    public function update_password(int $id, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => ['required', 'confirmed'],
            // 'password_confirmation' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Verifier les champs de saisies')
                ->withInput();
        }


        try {

            $user_info =  SidUser::whereId($id)->first();

            SidUser::find($id)->update([
                'password' => Hash::make($request->password),
                'activation_code'  =>  null,
                'forget_password'  =>  null,
            ]);


            Mail::to($user_info->email)->send(new NewAdmin('Votre compte  sous distributeur marchant est activé ' . env('APP_NAME') .
                ' Votre email : '  . $user_info->email . ' Votre mot de passe : ' . $request->password));

            return back()->with('status', 'Mise a jour effectuée avec succès');
        } catch (\Throwable $th) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de renitialisation')
                ->withInput();
        }
    }

    public function getWalletReloadSumAdminProperty($id)
    {
        $data = [];

        foreach (WalletReload::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $transaction) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }
    public function getWalletWithMerchantSumUserProperty($id)
    {


        $data = [];

        foreach (WithMerchant::where('sid_user_id', $id)->get() as $bank) {
            $data[] = $bank->amount_with_draw;
        }

        return array_sum($data);
    }

    public function getCountWalletTransaction($wallet_id, $status)
    {
        return  $this->transactionAccountService->getTransactionWalletDetailsCount(WalletTransaction::class, $wallet_id, $status) +
            $this->transactionAccountService->getTransactionWalletDetailsCount(WalletPayment::class, $wallet_id, $status) +
            $this->transactionAccountService->getTransactionWalletDetailsCount(WalletBank::class, $wallet_id, $status) +
            $this->transactionAccountService->getTransactionWalletDetailsCount(WalletPhoneCredit::class, $wallet_id, $status) +
            $this->transactionAccountService->getTransactionWalletDetailsCount(WalletReload::class, $wallet_id, $status) +
            $this->transactionAccountService->getTransactionWalletDetailsCount(WalletDeposit::class, $wallet_id, $status) +
            $this->transactionAccountService->getTransactionWalletDetailsCount(WalletInter::class, $wallet_id, $status) +
            $this->transactionAccountService->getTransactionWalletDetailsCount(BankCard::class, $wallet_id, $status) +
            $this->transactionAccountService->getTransactionWalletDetailsCount(WalletXbet::class, $wallet_id, $status) +
            $this->transactionAccountService->getTransactionWalletDetailsCount(WalletWave::class, $wallet_id, $status);
    }


    public function getCountWalletOperatorsTransaction($wallet_id, $status)
    {
        return  $this->transactionAccountService->getTransactionserUDetailsCount(WalletTransaction::class, $wallet_id, $status) +
            $this->transactionAccountService->getTransactionserUDetailsCount(WalletPayment::class, $wallet_id, $status) +
            $this->transactionAccountService->getTransactionserUDetailsCount(WalletBank::class, $wallet_id, $status) +
            $this->transactionAccountService->getTransactionserUDetailsCount(WalletPhoneCredit::class, $wallet_id, $status) +
            $this->transactionAccountService->getTransactionserUDetailsCount(WalletReload::class, $wallet_id, $status) +
            $this->transactionAccountService->getTransactionserUDetailsCount(WalletDeposit::class, $wallet_id, $status) +
            $this->transactionAccountService->getTransactionserUDetailsCount(WalletInter::class, $wallet_id, $status) +
            $this->transactionAccountService->getTransactionserUDetailsCount(WalletXbet::class, $wallet_id, $status) +
            $this->transactionAccountService->getTransactionserUDetailsCount(WalletWave::class, $wallet_id, $status);
    }

    public function getCommissionsWalletTransaction($wallet_id)
    {
        return  $this->transactionAccountService->getTransactionCommissionAmount(WalletTransaction::class, $wallet_id) +
            $this->transactionAccountService->getTransactionCommissionAmount(WalletPayment::class, $wallet_id) +
            $this->transactionAccountService->getTransactionCommissionAmount(WalletBank::class, $wallet_id) +
            $this->transactionAccountService->getTransactionCommissionAmount(WalletPhoneCredit::class, $wallet_id) +
            $this->transactionAccountService->getTransactionCommissionAmount(WalletReload::class, $wallet_id) +
            $this->transactionAccountService->getTransactionCommissionAmount(WalletDeposit::class, $wallet_id) +
            $this->transactionAccountService->getTransactionCommissionAmount(WalletInter::class, $wallet_id) +
            $this->transactionAccountService->getTransactionCommissionAmount(BankCard::class, $wallet_id) +
            $this->transactionAccountService->getTransactionCommissionAmount(WalletXbet::class, $wallet_id) +
            $this->transactionAccountService->getTransactionCommissionAmount(WalletWave::class, $wallet_id);
    }
    // Retrait valeurs

    public function getWalletTransactionSumRetraitsProperty($id)
    {


        $data = [];

        foreach (WalletTransaction::where('wallet_id', $id)->where('type_transaction', 'Retrait')->where('status', 'SUCCESS')->get() as $transaction) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }



    public function getWalletReloadSumReceviedAdminProperty($id)
    {



        $data = [];

        $wallet_id = Wallet::whereId($id)->first();


        foreach (WalletReload::where('account_wallet', $wallet_id->wallet_id)->where('status', 'SUCCESS')->get() as $reload) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }

    public function updateProfil(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirect('application/admin/read/' . (auth('web')->user()->id) . '/update')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de modification')
                ->withInput();
        }


        try {
            DB::beginTransaction();
            $user = SidUser::find(auth('web')->user()->id);
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->phone = $request->phone;
            $user->created_by = Auth::user()->id;
            $file_image = $request->file('image');
            if ($file_image) {
                $destination_path = public_path('profil/' . $user->firstname . '/');
                $front_file = date('YmdHis') . "." . $file_image->getClientOriginalExtension();
                $file_image->move($destination_path, $front_file);
                $user->image =  env('APP_URL') . "/public/profil/" . $user->firstname . "/$front_file";
            }
            $user->save();

            $color = 'success';

            return redirect()->back()->with('color', $color)->with('status', 'Mise a jour effectuée avec succès');
            // return redirect(route('admin.browse'))->with('status', 'Mise a jour effectuée avec succès');
        } catch (\Exception $exception) {
            return redirect('application/admin/read/' . (auth('web')->user()->id) . '/update')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de modification')
                ->withInput();
        }
    }

    public function updateProfil_company(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de modification')
                ->withInput();
        }


        try {
            DB::beginTransaction();
            $user = SidUser::find(firm()->id);
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->phone = $request->phone;
            $user->created_by = firm()->id;
            $file_image = $request->file('image');

            if ($file_image) {
                $destination_path = public_path('profil/' . $user->firstname . '/');
                $front_file = date('YmdHis') . "." . $file_image->getClientOriginalExtension();
                $file_image->move($destination_path, $front_file);
                $user->image =  env('APP_URL') . "/public/profil/" . $user->firstname . "/$front_file";
            }

            $user->save();

            $color = 'success';

            return redirect()->back()->with('color', $color)->with('status', 'Mise a jour effectuée avec succès');
            // return redirect(route('admin.browse'))->with('status', 'Mise a jour effectuée avec succès');
        } catch (\Exception $exception) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de modification')
                ->withInput();
        }
    }

    public function updateProfil_admin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de modification')
                ->withInput();
        }


        try {
            DB::beginTransaction();
            $user = SidUser::find(sabdy()->id);
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->phone = $request->phone;
            $user->created_by = sabdy()->id;
            $file_image = $request->file('image');

            if ($file_image) {
                $destination_path = public_path('profil/' . $user->firstname . '/');
                $front_file = date('YmdHis') . "." . $file_image->getClientOriginalExtension();
                $file_image->move($destination_path, $front_file);
                $user->image =  env('APP_URL') . "/public/profil/" . $user->firstname . "/$front_file";
            }

            $user->save();

            $color = 'success';

            return redirect()->back()->with('color', $color)->with('status', 'Mise a jour effectuée avec succès');
            // return redirect(route('admin.browse'))->with('status', 'Mise a jour effectuée avec succès');
        } catch (\Exception $exception) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de modification')
                ->withInput();
        }
    }

    public function updateProspect_company(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de modification')
                ->withInput();
        }


        try {
            DB::beginTransaction();
            $user = SidUser::find(propect()->id);
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->phone = $request->phone;
            $user->created_by = propect()->id;
            $file_image = $request->file('image');

            if ($file_image) {
                $destination_path = public_path('profil/' . $user->firstname . '/');
                $front_file = date('YmdHis') . "." . $file_image->getClientOriginalExtension();
                $file_image->move($destination_path, $front_file);
                $user->image =  env('APP_URL') . "/public/profil/" . $user->firstname . "/$front_file";
            }

            $user->save();

            $color = 'success';

            return redirect()->back()->with('color', $color)->with('status', 'Mise a jour effectuée avec succès');
            // return redirect(route('admin.browse'))->with('status', 'Mise a jour effectuée avec succès');
        } catch (\Exception $exception) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de modification')
                ->withInput();
        }
    }

    public function updateProfilManager(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirect('application/admin/read/' . (auth('web')->user()->id) . '/update')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de modification')
                ->withInput();
        }

        $tool = new Image;

        try {
            DB::beginTransaction();
            $user = SidUser::find(auth('web')->user()->id);
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->phone = $request->phone;
            $user->created_by = Auth::user()->id;

            if ($request->image) {
                if ($tool->urlToBase64($request->image)) {
                    $user->image =  $tool->saveInDisk($request, 'admins/' . Auth::user()->company_id . '/admin');
                }
            }

            $user->save();

            return redirect(route('account.profile.manager'))->with('status', 'Mise a jour effectuée avec succès');
        } catch (\Exception $exception) {
            return redirect('application/admin/read/' . (auth('web')->user()->id) . '/update')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de modification')
                ->withInput();
        }
    }



    public function delete(int $id)
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('supprimer-administrateur')) {
            abort(403);
        }
        try {

            DB::beginTransaction();
            $user = SidUser::findOrFail($id);
            $user->deleted_by = Auth::guard('web')->user()->email;

            $user->delete();

            DB::commit();

            $msg = 'Suppression effectuée avec succès';
            $color = 'success';
        } catch (\Exception $exception) {
            DB::rollBack();
            $msg = 'Suppression échouée';
            $color = 'danger';
        }
        return redirect()->back()->with('color', $color)
            ->with('status', $msg);
    }


    /**
     * Log out account user.
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Session::flush();

        Auth::guard('web')->logout();


        return redirect(route('admin.login'));
    }


    public function security()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('modifier-profil')) {
            abort(403);
        }
        $roles = Role::orderBy('created_at', 'DESC')->get();
        $user = SidUser::where('id', Auth::user()->id)->first();
        return view('pages.account.security', compact('user', 'roles'));
    }

    public function security_employers()
    {

        $roles = Role::orderBy('created_at', 'DESC')->get();
        $user = SidUser::where('id', Auth::user()->id)->first();
        return view('firms.pages.security_employers', compact('user', 'roles'));
    }

    public function security_prospects()
    {

        $roles = Role::orderBy('created_at', 'DESC')->get();
        $user = SidUser::where('id', Auth::user()->id)->first();
        return view('firms.pages.security_prospects', compact('user', 'roles'));
    }
    public function showProfile()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('modifier-profil')) {
            abort(403);
        }

        $user = SidUser::where('id', Auth::user()->id)->first();
        return view('pages.admin.showProfile', compact('user'));
    }

    public function show_profile()
    {
        if (!firm()) {
            abort(403);
        }

        $user = SidUser::where('id', firm()->id)->first();
        return view('firms.pages.showProfile', compact('user'));
    }

    public function show_prospect_profile()
    {
        if (!propect()) {
            abort(403);
        }

        $user = SidUser::where('id', propect()->id)->first();
        return view('firms.pages.showProspectProfile', compact('user'));
    }

    public function ShowProfil_employes()
    {

        $user = SidUser::where('id', Auth::user()->id)->first();
        return view('pages.admin.showProfile_employer', compact('user'));
    }

    // NOTIFICATIONS
    public function get_notifications()
    {
        $admin = SidUser::find(Auth::guard('web')->user()->id);

        // $notifications = $admin->notifications()->latest()->paginate(30);
        return view(
            'pages.admin.admin-notifications',
            compact('admin')
        );
    }

    public function get_notification()
    {
        $admin = SidUser::find(Auth::guard('web')->user()->id);
        $notifications = $admin->notifications()->latest()->paginate(30);

        return view(
            'pages.admin.admin-notification-all',
            compact('admin', 'notifications')
        );
    }
    public function get_unread_notifications()
    {
        $admin = SidUser::find(Auth::guard('web')->user()->id);
        $unread_notifications = $admin->unreadNotifications()->orderBy('created_at', 'desc')->latest()->get();
        return response()->json(['unread_notifications' => $unread_notifications]);
    }

    public function make_notifications_as_read()
    {
        $admin = SidUser::find(Auth::guard('web')->user()->id);
        foreach ($admin->unreadNotifications as $notification) {
            $notification->markAsRead();
        }
        return response()->json(['success' => true]);
    }

    public function make_read_all()
    {
        dd('iic');

        $admin = SidUser::find(Auth::guard('web')->user()->id);

        dd($admin);

        foreach ($admin->unreadNotifications as $notification) {
            $notification->markAsRead();
        }

        $msg = 'Vous avez marquer comme lu';
        $color = 'info';

        return redirect()->back()->with('color', $color)
            ->with('status', $msg);
    }

    public function notifs_read_all()
    {

        $admin = SidUser::find(Auth::guard('web')->user()->id);

        $unreadNotifications = Notification::whereNull('read_at')->get();

        $notificationsByUser = $unreadNotifications->groupBy('notifiable_id');

        foreach ($notificationsByUser as $userId => $notifications) {
            if ($notifications->count() >= 70) {
                Notification::where('notifiable_id', $userId)
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);
            }
        }

        // Marquer toutes les notifications de l'administrateur comme lues
        foreach ($admin->unreadNotifications as $notification) {
            $notification->markAsRead();
        }

        $msg = 'Vous avez marquer comme lu';
        $color = 'info';

        return redirect()->back()->with('color', $color)
            ->with('status', $msg);
    }

    public function make_read_all_message()
    {
        $admin = SidUser::find(Auth::guard('web')->user()->id);
        foreach ($admin->unreadNotifications as $notification) {
            $notification->markAsRead();
        }

        $msg = 'Vous avez marquer comme lu';
        $color = 'info';

        return redirect()->back()->with('color', $color)
            ->with('status', $msg);
    }


    public function notif_read_single($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            $msg = 'Vous avez marquer comme lu';
            $color = 'info';

            return redirect()->back()->with('color', $color)
                ->with('status', $msg);
        }
    }
    public function make_read_single($id)
    {

        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            $msg = 'Vous avez marquer comme lu';
            $color = 'info';

            return redirect()->back()->with('color', $color)
                ->with('status', $msg);
        }
    }
}
