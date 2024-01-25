<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kyc;
use App\Tools\Image;
use App\Mail\NewAdmin;
use App\Models\Company;
use App\Mail\OtpValidated;
use App\Models\Commission;
use App\Mail\CodeValidated;
use App\Models\Sid\SidUser;
use App\Models\WithMerchant;
use Illuminate\Http\Request;
use App\Mail\NotificationMail;
use App\Models\Banks\BankCard;
use App\Models\Custumers\City;
use App\Models\Custumers\Zone;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Rules\MatchOldPassword;
use App\Models\Custumers\Country;
use App\Models\Wallets\WalletBank;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\Wallets\WalletInter;
use App\Http\Controllers\Controller;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use Illuminate\Support\Facades\Session;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Admin\MailController;

class AdminController extends Controller
{

    
    // Company Infos

    public function home_company()
    {
        return view('home_company');
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

    public function browse(Request $request)
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-administrateur')) {
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

            if($verify_phone)
            {
                return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Utilisateur existe déja ')
                ->withInput();
            }else {

                SidUser::find($id)->update(['phone' => $user_info->country->indecative. $request->new_phone]);
    
                Mail::to($user_info->email)->send(new NewAdmin('Vote numero de telephone a été modifier par' . env('APP_NAME') .
                    ' Votre email : '  . $user_info->email . ' Votre nouveau numero : ' . $request->new_phone));
    
                return redirect(route('admin.browse'))->with('status', 'Mise a jour effectuée avec succès');

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
        $roles = Role::orderBy('created_at', 'desc')->get();
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
        $countries = Country::whereRobus(true)
            ->where('indecative', '!=', null)->get();
        return view('pages.admin.add_company', compact('roles', 'countries'));
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
                    // ->withErrors($validator)
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

        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'country_id' => 'required',
            'zone' => 'required',
            'roles' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:sid_users'],
            'phone' => ['required', 'unique:sid_users'],
            'password' => ['required', 'confirmed'],
            'image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_recto' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            'image_verso' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',

        ]);
        if ($validator->fails()) {
            return redirect('application/admin/add')
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
            $user->created_by = Auth::user()->id;
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
            $user->is_under_distributer  = $request->is_under_distributer ? true : false;
            $user->is_distributer  = $request->is_distributer ? true : false;
            $user->is_accounting  = $request->is_accounting ? true : false;
            $user->is_admin  = $request->is_admin ? true : false;
            $user->is_custumers  = $request->is_custumers ? true : false;
            $user->is_supervisor  = $request->is_supervisor ? true : false;
            $user->is_company  = false;

            $user->assignRole($request['roles']);

            if (!$user->country->currencie) {

                return redirect('application/admin/add')
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
                'created_by' => Auth::guard('web')->user()->id,
                'sid_user_id' =>  $user->id,
                'currencie_id' =>  $user->country->currencie->id,
                'amount' => 0.0,
                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                'status' => true

            ]);



            $user->update([
                'wallet_id'  => $wallet_user->wallet_id,
                'phone'  =>   $user->country->indecative . $user->phone,
                'silver_ceiling'  =>   $user->country->silver_ceiling
            ]);



            Mail::to($user->email)->send(new NewAdmin('Vous etes désormais un  utilisateur marchant de ' . env('APP_NAME') .
                ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

            $msg = "administrateur crée avec succès";
            $color = 'success';

            return redirect(route('admin.browse'))->with('color', $color)->with('status', $msg);
        } catch (\Throwable $th) {
            return redirect('application/admin/add')
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
                    'country_id' =>  $request->country_id,
                    'email' =>  $user->email,
                    'phone' =>  $user->phone,
                    'city' =>  $user->zone,
                    'currencie_id' =>  $user->country->currencie->id,
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
                return redirect(route('admin.browse'))->with('color', $color)->with('status', $msg);
            }



            Mail::to($user->email)->send(new NewAdmin('Vous etes désormais une société ' . env('APP_NAME') .
                ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

            $msg = "Societe crée avec succès";
            $color = 'success';

            return redirect(route('admin.browse'))->with('color', $color)->with('status', $msg);
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

        if (!Auth::guard('web')->user()->hasPermissionTo('modifier-administrateur')) {
            abort(403);
        }

        $paiement_status = null;

        $user = SidUser::whereId($id)->first();

        $wallet_solde = Wallet::where('sid_user_id', $user->id)->first();

        $transactions =  WalletTransaction::where('wallet_id', $wallet_solde->id)
            ->orWhere('num_wallet',  $wallet_solde->wallet_id)
            ->orderBy('created_at', 'desc')->paginate(20);

        $paiements =  WalletPayment::where('wallet_id', $wallet_solde->id)
            ->orderBy('created_at', 'desc')->paginate(20);


        $virements =  WalletBank::where('wallet_id', $wallet_solde->id)
            ->orderBy('created_at', 'desc')->paginate(20);

        $phoneCredits =  WalletPhoneCredit::where('wallet_id', $wallet_solde->id)
            ->orderBy('created_at', 'desc')->paginate(20);

        $reaload_uv =  WalletReload::where('wallet_id', $wallet_solde->id)
            ->orWhere('account_wallet',  $wallet_solde->wallet_id)
            ->orderBy('created_at', 'desc')->paginate(20);


        $reloadCards =  WalletDeposit::where('wallet_id', $wallet_solde->id)
            ->orderBy('created_at', 'desc')->paginate(20);

        $Internationals =  WalletInter::where('wallet_id', $wallet_solde->id)
            ->orWhere('number_compte',  $wallet_solde->wallet_id)
            ->orderBy('created_at', 'desc')->paginate(20);

        $walletOrderCarte =  BankCard::where('wallet_id', $wallet_solde->id)
            ->orderBy('created_at', 'desc')->paginate(20);


        $usersCountry = Auth::guard('web')->user()->country->currencie->name;

        $kycs = $user->kyc()->get() ?? null;

        $roles = Role::all();
        $countries = Country::where('status', true)->get();

        $sum_error = $this->getTransactionErrorUserProperty($wallet_solde->id)  + $this->getPaymentErrorUserProperty($wallet_solde->id) + $this->getReloadErrorUserProperty($wallet_solde->id)  +  $this->getVirementErrorUserProperty($wallet_solde->id) +  $this->getInterErrorUserProperty($wallet_solde->id) + $this->getPhoneCreditErrorUserProperty($wallet_solde->id) +   $this->getDepositErrorUserProperty($wallet_solde->id);
        $sum_success = $this->getTransactionSuccessUserProperty($wallet_solde->id)  + $this->getPaymentSuccessUserProperty($wallet_solde->id) + $this->getReloadSuccessUserProperty($wallet_solde->id)  +  $this->getVirementSuccessUserProperty($wallet_solde->id) +  $this->getInterSuccessUserProperty($wallet_solde->id) + $this->getPhoneCreditSuccessUserProperty($wallet_solde->id) +   $this->getDepositSuccessUserProperty($wallet_solde->id);
        $pending_success = $this->getTransactionPenddingUserProperty($wallet_solde->id)  + $this->getPaymentPenddingUserProperty($wallet_solde->id) + $this->getReloadPenddingUserProperty($wallet_solde->id)  +  $this->getVirementPenddingUserProperty($wallet_solde->id) +  $this->getInterPenddingUserProperty($wallet_solde->id) + $this->getPhoneCreditPenddingUserProperty($wallet_solde->id) +   $this->getDepositPenddingUserProperty($wallet_solde->id);
        $sum_wallet_bank = $this->getWalletBankSumUserProperty($wallet_solde->id);
        $sum_wallet_deposit = $this->getWalletDepositSumAdminProperty($wallet_solde->id);
        $sum_wallet_payment = $this->getWalletPaymentSumAdminProperty($wallet_solde->id);
        $sum_wallet_transaction = $this->getWalletTransactionSumAdminProperty($wallet_solde->id);
        $sum_wallet_transaction_received = $this->getWalletTransactionSumReceivedProperty($wallet_solde->id);
        $sum_wallet_phone = $this->getWalletPhoneCreditSumAdminProperty($wallet_solde->id);
        $sum_wallet_Inter = $this->getWalletInterSumAdminProperty($wallet_solde->id);
        $sum_wallet_Inter_received = $this->getWalletInterSumReceivedProperty($wallet_solde->id);
        $sum_wallet_Reload = $this->getWalletReloadSumAdminProperty($wallet_solde->id);
        $sum_wallet_drawer = $this->getWalletWithMerchantSumUserProperty($id);
        $sum_wallet_reload_received = $this->getWalletReloadSumReceviedAdminProperty($wallet_solde->id);
        $sum_commissions  =   $this->getWalletBankSumCommissionsProperty($wallet_solde->id) +
            $this->getWalletDepositSumCommissionsProperty($wallet_solde->id)
            +  $this->getWalletTransactionSumCommissionsProperty($wallet_solde->id) +
            $this->getWalletPaymentSumCommissionsProperty($wallet_solde->id) +
            $this->getWalletInterSumCommissionsProperty($wallet_solde->id) +
            $this->getWalletPhoneCreditSumCommissionsProperty($wallet_solde->id) +
            $this->getWalletReloadSumCommissionsProperty($wallet_solde->id)  + $this->getWalletOrderBankSumGainUserProperty($wallet_solde->id);
        $sum_drawals  =  $this->getWalletTransactionSumRetraitsProperty($wallet_solde->id);

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

            SidUser::find($id)->update(['password' => Hash::make($request->password)]);

            Mail::to($user_info->email)->send(new NewAdmin('Votre compte  sous distributeur marchant est activé ' . env('APP_NAME') .
                ' Votre email : '  . $user_info->email . ' Votre mot de passe : ' . $request->password));

            return redirect(route('admin.browse'))->with('status', 'Mise a jour effectuée avec succès');
        } catch (\Throwable $th) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de création')
                ->withInput();
        }
    }
    public function getWalletWithMerchantSumUserProperty($id)
    {


        $data = [];

        foreach (WithMerchant::where('sid_user_id', $id)->get() as $bank) {
            $data[] = $bank->amount_with_draw;
        }

        return array_sum($data);
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
    // Commissions valeurs
    public function getWalletBankSumCommissionsProperty($id)
    {

        $data = [];

        foreach (WalletBank::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletDepositSumCommissionsProperty($id)
    {

        $data = [];

        foreach (WalletDeposit::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $deposit) {
            $data[] = $deposit->commissions;
        }

        return array_sum($data);
    }


    public function getWalletPaymentSumCommissionsProperty($id)
    {

        $data = [];

        foreach (WalletPayment::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $payment) {
            $data[] = $payment->commissions;
        }

        return array_sum($data);
    }

    public function getWalletTransactionSumCommissionsProperty($id)
    {


        $data = [];

        foreach (WalletTransaction::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $transaction) {
            $data[] = $transaction->commissions;
        }

        return array_sum($data);
    }



    public function getWalletInterSumCommissionsProperty($id)
    {


        $data = [];

        foreach (WalletInter::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $inter) {
            $data[] = $inter->commissions;
        }

        return array_sum($data);
    }

    public function getWalletPhoneCreditSumCommissionsProperty($id)
    {

        $data = [];

        foreach (WalletPhoneCredit::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $phone) {
            $data[] = $phone->commissions;
        }

        return array_sum($data);
    }

    public function getWalletReloadSumCommissionsProperty($id)
    {

        $data = [];

        foreach (WalletReload::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $reload) {
            $data[] = $reload->commissions;
        }

        return array_sum($data);
    }


    public function getWalletOrderBankSumGainUserProperty($id)
    {
        $data = [];

        foreach (BankCard::where('wallet_id', $id)->where('status', 1)->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }


    // Sum Admin
    public function getWalletBankSumUserProperty($id)
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $data = [];

        foreach (WalletBank::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletDepositSumAdminProperty($id)
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $data = [];

        foreach (WalletDeposit::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $deposit) {
            $data[] = $deposit->amount;
        }

        return array_sum($data);
    }


    public function getWalletPaymentSumAdminProperty($id)
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $data = [];

        foreach (WalletPayment::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $payment) {
            $data[] = $payment->amount;
        }

        return array_sum($data);
    }

    public function getWalletTransactionSumAdminProperty($id)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $data = [];

        foreach (WalletTransaction::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $transaction) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }


    public function getWalletTransactionSumReceivedProperty($id)
    {


        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $data = [];

        $wallet_id = Wallet::whereId($id)->first();


        foreach (WalletTransaction::where('num_wallet', $wallet_id->wallet_id)->where('status', 'SUCCESS')->get() as $reload) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }

    public function getWalletInterSumAdminProperty($id)
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $data = [];

        foreach (WalletInter::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $inter) {
            $data[] = $inter->amount;
        }

        return array_sum($data);
    }

    public function getWalletInterSumReceivedProperty($id)
    {


        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $data = [];

        $wallet_id = Wallet::whereId($id)->first();


        foreach (WalletInter::where('number_compte', $wallet_id->wallet_id)->where('status', 'SUCCESS')->get() as $reload) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }
    public function getWalletPhoneCreditSumAdminProperty($id)
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $data = [];

        foreach (WalletPhoneCredit::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $phone) {
            $data[] = $phone->amount;
        }

        return array_sum($data);
    }

    public function getWalletReloadSumAdminProperty($id)
    {


        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $data = [];

        foreach (WalletReload::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $reload) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }

    public function getWalletReloadSumReceviedAdminProperty($id)
    {


        $todays = Carbon::now();
        $convertions = $todays->toDateString();
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

    // Details Transaction User
    public function getTransactionErrorUserProperty($id)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $transactionError = WalletTransaction::where('wallet_id', $id)->where('status', 'ERROR')
            ->count();
        return $transactionError;
    }

    public function getTransactionSuccessUserProperty($id)
    {

        $transactionSuccess = WalletTransaction::where('wallet_id', $id)->where('status', 'SUCCESS')
            ->count();
        return $transactionSuccess;
    }

    public function getTransactionPenddingUserProperty($id)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $transactionPendding = WalletTransaction::where('wallet_id', $id)->where('status', 'PENDING')
            ->count();
        return $transactionPendding;
    }

    public function getPaymentErrorUserProperty($id)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $paimentError = WalletPayment::where('wallet_id', $id)->where('status', 'ERROR')
            ->count();
        return $paimentError;
    }

    public function getPaymentSuccessUserProperty($id)
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $paimentSuccess = WalletPayment::where('wallet_id', $id)->where('status', 'SUCCESS')
            ->count();
        return $paimentSuccess;
    }

    public function getPaymentPenddingUserProperty($id)
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $paimentPendding = WalletPayment::where('wallet_id', $id)->where('status', 'PENDING')
            ->count();
        return $paimentPendding;
    }


    public function getReloadErrorUserProperty($id)
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $reloadError = WalletReload::where('wallet_id', $id)->where('status', 'ERROR')
            ->count();
        return $reloadError;
    }

    public function getReloadSuccessUserProperty($id)
    {


        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $reloadSuccess = WalletReload::where('wallet_id', $id)->where('wallet_reloads.status', 'SUCCESS')
            ->count();
        return $reloadSuccess;
    }

    public function getReloadPenddingUserProperty($id)
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $reloadPendding = WalletReload::where('wallet_id', $id)->where('status', 'PENDING')
            ->count();
        return $reloadPendding;
    }

    public function getVirementSuccessUserProperty($id)
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $virementSuccess = WalletBank::where('wallet_id', $id)->where('status', 'SUCCESS')
            ->count();
        return $virementSuccess;
    }

    public function getVirementPenddingUserProperty($id)
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $virementPendding = WalletBank::where('wallet_id', $id)->where('status', 'PENDING')
            ->count();
        return $virementPendding;
    }

    public function getVirementErrorUserProperty($id)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $virementError = WalletBank::where('wallet_id', $id)->where('status', 'ERROR')
            ->count();
        return $virementError;
    }

    public function getInterErrorUserProperty($id)
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $interError = WalletInter::where('wallet_id', $id)->where('status', 'ERROR')
            ->count();
        return $interError;
    }

    public function getInterSuccessUserProperty($id)
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $interSuccess = WalletInter::where('wallet_id', $id)->where('status', 'SUCCESS')
            ->count();
        return $interSuccess;
    }

    public function getInterPenddingUserProperty($id)
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $interPendding = WalletInter::where('wallet_id', $id)->where('status', 'PENDING')
            ->count();
        return $interPendding;
    }

    public function getPhoneCreditErrorUserProperty($id)
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $phoneCreditError = WalletPhoneCredit::where('wallet_id', $id)->where('status', 'ERROR')
            ->count();
        return $phoneCreditError;
    }

    public function getPhoneCreditSuccessUserProperty($id)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $phoneCreditSuccess = WalletPhoneCredit::where('wallet_id', $id)->where('status', 'SUCCESS')
            ->count();
        return $phoneCreditSuccess;
    }

    public function getPhoneCreditPenddingUserProperty($id)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $phoneCreditPendding = WalletPhoneCredit::where('wallet_id', $id)->where('status', 'PENDING')
            ->count();
        return $phoneCreditPendding;
    }

    public function getDepositErrorUserProperty($id)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $depositError = WalletDeposit::where('wallet_id', $id)->where('status', 'ERROR')
            ->count();
        return $depositError;
    }

    public function getDepositSuccessUserProperty($id)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $depositSuccess = WalletDeposit::where('wallet_id', $id)->where('status', 'SUCCESS')
            ->count();
        return $depositSuccess;
    }

    public function getDepositPenddingUserProperty($id)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $depositPendding = WalletDeposit::where('wallet_id', $id)->where('status', 'PENDING')
            ->count();
        return $depositPendding;
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
