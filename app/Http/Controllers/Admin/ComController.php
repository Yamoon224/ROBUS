<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Tools\Image;
use App\Models\Sid\SidUser;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletBank;
use App\Models\Banks\BankCard;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletReload;
use App\Http\Services\TransactionsAccountService;
use App\Models\Custumers\City;
use Illuminate\Support\Facades\Mail;
use App\Models\Wallets\Wallet;
use App\Models\Kyc;
use App\Mail\NewAdmin;
use App\Models\Master;


class ComController extends Controller
{

        protected $transactionAccountService;

        public function __construct(
                TransactionsAccountService $transactionAccountService,
        ) {
                $this->transactionAccountService = $transactionAccountService;
        }


        public function browse_masters()
        {
                if (!Auth::guard('web')->user()->hasPermissionTo('voir-masters')) {
                        abort(403);
                }

                return view('pages.admin.comzones.browse');
        }
        public function browse_custumers()
        {
                if (comzone()) {
                        return view('comzones.pages.browse_custumers');
                } else {
                        abort(403);
                }
        }

        public function browse_com_masters()
        {
                if (comzone()) {
                        return view('comzones.pages.browse_masters');
                } else {
                        abort(403);
                }
        }

        public function browse_underdistributors()
        {

                if (comzone()) {
                        return view('comzones.pages.browse_underdistributors');
                } else {
                        abort(403);
                }
        }

        public function browse_reloads()
        {
                if (comzone()) {
                        return view('comzones.pages.browse_reloads');
                } else {
                        abort(403);
                }
        }

        public function showProfile()
        {
                if (comzone()) {
                        $user = SidUser::where('id', Auth::user()->id)->first();
                        return view('pages.admin.comzones.showProfile', compact('user'));
                } else {
                        abort(403);
                }
        }


        public function security()
        {

                if (comzone()) {
                        $roles = Role::orderBy('created_at', 'DESC')->get();
                        $user = SidUser::where('id', Auth::user()->id)->first();
                        return view('pages.admin.comzones.security', compact('user', 'roles'));
                } else {
                        abort(403);
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
                        return redirect('master/account/security/update')
                                ->withErrors($validator)
                                ->with('color', 'danger')
                                ->with('status', 'Erreur de modification')
                                ->withInput();
                }


                SidUser::find(master()->id)->update(['password' => Hash::make($request->new_password)]);

                $notify = new MailController;
                $notify->modifyPassword($request);


                return redirect('master/account/security/update')->with('status', 'Mise a jour effectuée avec succès');
        }



        public function browse_dumps()
        {
                if (master()) {
                        return view('comzones.pages.browse_dumps');
                } else {
                        abort(403);
                }
        }

        public function add_underdistributors()
        {

                if (comzone()) {
                        $role = Role::whereId(4)->orderBy('created_at', 'desc')->first();
                        $countrie_master = comzone()->country->id;
                        $countrie = Country::whereId($countrie_master)->first();
                        $cities  = City::whereCountryId($countrie_master)->orderBy('name', 'asc')->get();
                        return view('comzones.pages.add_underdistributors', compact('role', 'countrie', 'cities'));
                } else {
                        abort(403);
                }
        }

        public function add_custumers()
        {
                if (comzone()) {
                        $role = Role::whereId(4)->orderBy('created_at', 'desc')->first();
                        $countrie_master = comzone()->country->id;
                        $countrie = Country::whereId($countrie_master)->first();
                        $cities  = City::whereCountryId($countrie_master)->orderBy('name', 'asc')->get();
                        return view('comzones.pages.add_custumers', compact('role', 'countrie', 'cities'));
                } else {
                        abort(403);
                }
        }

        public function add_masters_com()
        {
                if (comzone()) {
                        $role = Role::whereId(7)->orderBy('created_at', 'desc')->first();
                        $countrie_master = comzone()->country->id;
                        $countrie = Country::whereId($countrie_master)->first();
                        $cities  = City::whereCountryId($countrie_master)->orderBy('name', 'asc')->get();
                        return view('comzones.pages.add_masters', compact('role', 'countrie', 'cities'));
                } else {
                        abort(403);
                }
        }

        public function store_masters(Request $request)
        {

                $validator = Validator::make($request->all(), [
                        'firstname' => ['required', 'string', 'max:255'],
                        'lastname' => ['required', 'string', 'max:255'],
                        'country_id' => 'required',
                        'zone' => 'required',
                        'email' => ['required', 'string', 'email', 'max:255', 'unique:sid_users'],
                        'phone' => ['required', 'unique:sid_users'],
                        'password' => ['required', 'confirmed'],
                        'image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
                        'image_recto' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
                        'image_verso' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
                ]);

                if ($validator->fails()) {
                        return redirect('masters/add_master')
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
                        $user->is_company  = false;
                        $user->is_master   = true;
                        $user->is_com_zone = false;
                        $user->assignRole(7);

                        if (!$user->country->currencie) {

                                return redirect('comzones/add_master')
                                        ->withErrors($validator)
                                        ->with('color', 'danger')
                                        ->with('status', 'Pas de devise dans se pays\n \nveillez activer la devise')
                                        ->withInput();
                        }



                        $user->save();

                        if (isset($file_image_recto) && !empty($file_image_recto) && isset($file_image_verso) && !empty($file_image_verso)) {

                                $destination_path = public_path('kyc_master/' . $user->id . '/');
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
                                                'screen' => env('APP_URL') . "/public/kyc_master/" . $user->id . "/$front_file",
                                                'screen_back' => env('APP_URL') . "/public/kyc_master/" . $user->id . "/$back_file",
                                                'sid_user_id' => $user->id,
                                                'status' => true,
                                                'company_id'  =>  $user->company->id
                                        ]
                                );
                        }


                        Master::create([
                                'city_id' =>  $user->city_id,
                                'lastname' =>  $user->lastname,
                                'firstname' =>  $user->firstname,
                                'country_id' =>  $request->country_id,
                                'email' =>  $user->email,
                                'phone' =>  $user->country->indecative .  $user->phone,
                                'zone' =>  $user->zone,
                                'is_master' => $user->is_master == true ? true : false,
                                'currency_id' =>  $user->country->currencie->id,
                                'status' => true
                        ]);

                        $wallet_user = Wallet::create([
                                'company_id' =>   1,
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
                                'company_id'  => 1,
                                'wallet_id'  => $wallet_user->wallet_id,
                                // 'master_id'  => $user->id,
                                'is_accepted' => false,
                                'phone'  =>   $user->country->indecative . $user->phone,
                                'silver_ceiling'  =>   $user->country->silver_ceiling
                        ]);


                        $valeur_message = $user->is_master == true ?  ' Un MASTER' : ' un COM ZONE ';
                        Mail::to($user->email)->send(new NewAdmin('Vous etes désormais ' . $valeur_message . env('APP_NAME') .
                                ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

                        $msg = "Utilisateur crée avec succès";
                        $color = 'success';

                        return redirect(route('masters.admins.browse'))->with('color', $color)->with('status', $msg);
                } catch (\Throwable $th) {
                        return redirect('masters/add_master')
                                ->withErrors($validator)
                                ->with('color', 'danger')
                                ->with('status', 'Erreur de création')
                                ->withInput();
                }
        }

        public function updateProfil(Request $request)
        {

                $validator = Validator::make($request->all(), [
                        'firstname' => ['required', 'string', 'max:255'],
                        'lastname' => ['required', 'string', 'max:255'],
                ]);

                if ($validator->fails()) {
                        return redirect('comzone/account')
                                ->withErrors($validator)
                                ->with('color', 'danger')
                                ->with('status', 'Erreur de modification')
                                ->withInput();
                }


                try {

                        DB::beginTransaction();
                        $user = SidUser::find(comzone()->id);
                        $user->firstname = $request->firstname;
                        $user->lastname = $request->lastname;
                        // $user->phone = $request->phone;
                        $user->created_by = comzone()->id;
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
                } catch (\Exception $exception) {
                        return redirect('comzone/account')
                                ->withErrors($validator)
                                ->with('color', 'danger')
                                ->with('status', 'Erreur de modification')
                                ->withInput();
                }
        }

        public function show_masters(int $id, Request $request)
        {

                if (!Auth::guard('web')->user()->hasPermissionTo('modifier-master')) {
                        abort(403);
                }


                $user = SidUser::whereId($id)->first();

                $wallet_solde = Wallet::where('sid_user_id', $user->id)->first();

                $transactions = $this->transactionAccountService->getTransactionsTypeAll(WalletTransaction::class, 'num_wallet', $wallet_solde);

                $reaload_uv = $this->transactionAccountService->getTransactionsTypeAll(WalletReload::class, 'account_wallet', $wallet_solde);

                $reload_all = $this->transactionAccountService->getTransactionsAll(WalletReload::class, $wallet_solde);

                $transaction_all = $this->transactionAccountService->getTransactionsAll(WalletReload::class, $wallet_solde);

                $Internationals = $this->transactionAccountService->getTransactionsTypeAll(WalletInter::class, 'number_compte', $wallet_solde);

                // amount valeurs
                $transaction_amount = $this->transactionAccountService->getTransactionsTypeAllAmount(WalletTransaction::class, 'num_wallet', $wallet_solde);

                $reaload_amount = $this->transactionAccountService->getTransactionsTypeAllAmount(WalletReload::class, 'account_wallet', $wallet_solde);

                $reload_amount = $this->transactionAccountService->getTransactionAmount(WalletReload::class, $wallet_solde);

                $transaction_amoun = $this->transactionAccountService->getTransactionAmount(WalletTransaction::class, $wallet_solde);

                $transaction_all_amount = $this->transactionAccountService->getTransactionAmount(WalletReload::class, $wallet_solde);

                $International_amount = $this->transactionAccountService->getTransactionsTypeAllAmount(WalletInter::class, 'number_compte', $wallet_solde);

                $usersCountry = Auth::guard('web')->user()->country->currencie->name;

                $kycs = $user->kyc()->get() ?? null;

                $users =   $this->transactionAccountService->getUsersMasters($id);

                return view('pages.admin.masters.show_master', compact(
                        'user',
                        'users',
                        'reaload_uv',
                        'Internationals',
                        'transactions',
                        'usersCountry',
                        'reload_all',
                        'kycs',
                        'transaction_all',
                        'transaction_amount',
                        'reaload_amount',
                        'reload_amount',
                        'transaction_all_amount',
                        'International_amount',
                        'transaction_amoun'
                ));
        }


        public function store_under_distributors(Request $request)
        {

                $validator = Validator::make($request->all(), [
                        'firstname' => ['required', 'string', 'max:255'],
                        'lastname' => ['required', 'string', 'max:255'],
                        'zone' => 'required',
                        'email' => ['required', 'string', 'email', 'max:255', 'unique:sid_users'],
                        'phone' => ['required', 'unique:sid_users'],
                        'password' => ['required', 'confirmed'],
                        'image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
                        'image_recto' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
                        'image_verso' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
                ]);

                if ($validator->fails()) {
                        return redirect('comzone/underdistributors/add')
                                ->withErrors($validator)
                                ->with('color', 'danger')
                                ->with('status', 'Champ requise')
                                ->withInput();
                }


                $user = DB::table('sid_users')->where('email', $request->email)->first();


                try {

                        $rt = rand(40, 5000) * time();
                        $tr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
                        $walletCode = sprintf("%0.6s", str_shuffle($rt . $tr));

                        $user = new SidUser();
                        $user->firstname  = $request->firstname;
                        $user->company_id  = 1;
                        $user->lastname  = $request->lastname;
                        $user->country_id  = comzone()->country->id ?? null;
                        $user->city_id  = $request->city ?? null;
                        $user->zone_id  = $request->zone_id ?? null;
                        $user->zone  = $request->zone ?? null;
                        $user->email =  $request->email;
                        $user->phone  = $request->phone;
                        $user->created_by = comzone()->id;
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
                        $user->is_under_distributer  = true;
                        $user->is_distributer  = false;
                        $user->is_accounting  = false;
                        $user->is_admin  =  false;
                        $user->is_custumers  = false;
                        $user->is_supervisor  =  false;
                        $user->is_company  = false;
                        $user->is_master   = false;
                        $user->is_com_zone = false;
                        $user->assignRole(4);

                        if (!$user->country->currencie) {

                                return redirect('comzone/underdistributors/add')
                                        ->withErrors($validator)
                                        ->with('color', 'danger')
                                        ->with('status', 'Pas de devise dans se pays\n \nveillez activer la devise')
                                        ->withInput();
                        }



                        $user->save();

                        if (isset($file_image_recto) && !empty($file_image_recto) && isset($file_image_verso) && !empty($file_image_verso)) {

                                $destination_path = public_path('kyc_underDistributors/' . $user->id . '/');
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
                                                'screen' => env('APP_URL') . "/public/kyc_underDistributors/" . $user->id . "/$front_file",
                                                'screen_back' => env('APP_URL') . "/public/kyc_underDistributors/" . $user->id . "/$back_file",
                                                'sid_user_id' => $user->id,
                                                'status' => true,
                                                'company_id'  =>  $user->company->id
                                        ]
                                );
                        }


                        $wallet_user = Wallet::create([
                                'company_id' =>   1,
                                'wallet_id' =>   $user->country->code . $walletCode,
                                'country_id' =>  $user->country->id,
                                'created_by' => comzone()->id,
                                'sid_user_id' =>  $user->id,
                                'currencie_id' =>  $user->country->currencie->id,
                                'amount' => 0.0,
                                'reload_by' => comzone()->firstname . ' ' . comzone()->lastname,
                                'status' => true
                        ]);

                        $user->update([
                                'company_id'  => 1,
                                'wallet_id'  => $wallet_user->wallet_id,
                                'master_id'  => null,
                                'is_accepted' => false,
                                'phone'  =>   $user->country->indecative . $user->phone,
                                'silver_ceiling'  =>   $user->country->silver_ceiling
                        ]);


                        $valeur_message = $user->is_under_distributer == true ?  ' Un Sous distributeur ' : ' un masters ';
                        Mail::to($user->email)->send(new NewAdmin('Vous etes désormais ' . $valeur_message . env('APP_NAME') .
                                ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

                        $msg = "Utilisateur crée avec succès";
                        $color = 'success';

                        return redirect(route('comzones.underdistributors.browse'))->with('color', $color)->with('status', $msg);
                } catch (\Throwable $th) {
                        return redirect('comzone/underdistributors/add')
                                ->withErrors($validator)
                                ->with('color', 'danger')
                                ->with('status', 'Erreur de création')
                                ->withInput();
                }
        }

        public function store_under_masters(Request $request)
        {

                $validator = Validator::make($request->all(), [
                        'firstname' => ['required', 'string', 'max:255'],
                        'lastname' => ['required', 'string', 'max:255'],
                        'zone' => 'required',
                        'email' => ['required', 'string', 'email', 'max:255', 'unique:sid_users'],
                        'phone' => ['required', 'unique:sid_users'],
                        'password' => ['required', 'confirmed'],
                        'image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
                        'image_recto' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
                        'image_verso' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
                ]);

                if ($validator->fails()) {
                        return redirect('comzone/masters/add')
                                ->withErrors($validator)
                                ->with('color', 'danger')
                                ->with('status', 'Champ requise')
                                ->withInput();
                }


                $user = DB::table('sid_users')->where('email', $request->email)->first();


                try {

                        $rt = rand(40, 5000) * time();
                        $tr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
                        $walletCode = sprintf("%0.6s", str_shuffle($rt . $tr));

                        $user = new SidUser();
                        $user->firstname  = $request->firstname;
                        $user->company_id  = 1;
                        $user->lastname  = $request->lastname;
                        $user->country_id  = comzone()->country->id ?? null;
                        $user->city_id  = $request->city ?? null;
                        $user->zone_id  = $request->zone_id ?? null;
                        $user->zone  = $request->zone ?? null;
                        $user->email =  $request->email;
                        $user->phone  = $request->phone;
                        $user->created_by = comzone()->id;
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
                        $user->is_company  = false;
                        $user->is_master   = true;
                        $user->is_com_zone = false;
                        $user->assignRole(7);

                        if (!$user->country->currencie) {

                                return redirect('comzone/masters/add')
                                        ->withErrors($validator)
                                        ->with('color', 'danger')
                                        ->with('status', 'Pas de devise dans se pays\n \nveillez activer la devise')
                                        ->withInput();
                        }



                        $user->save();

                        if (isset($file_image_recto) && !empty($file_image_recto) && isset($file_image_verso) && !empty($file_image_verso)) {

                                $destination_path = public_path('kyc_masters/' . $user->id . '/');
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
                                                'screen' => env('APP_URL') . "/public/kyc_masters/" . $user->id . "/$front_file",
                                                'screen_back' => env('APP_URL') . "/public/kyc_masters/" . $user->id . "/$back_file",
                                                'sid_user_id' => $user->id,
                                                'status' => true,
                                                'company_id'  =>  $user->company->id
                                        ]
                                );
                        }


                        $wallet_user = Wallet::create([
                                'company_id' =>   1,
                                'wallet_id' =>   $user->country->code . $walletCode,
                                'country_id' =>  $user->country->id,
                                'created_by' => comzone()->id,
                                'sid_user_id' =>  $user->id,
                                'currencie_id' =>  $user->country->currencie->id,
                                'amount' => 0.0,
                                'reload_by' => comzone()->firstname . ' ' . comzone()->lastname,
                                'status' => true
                        ]);

                        $user->update([
                                'company_id'  => 1,
                                'wallet_id'  => $wallet_user->wallet_id,
                                'master_id'  => null,
                                'is_accepted' => false,
                                'phone'  =>   $user->country->indecative . $user->phone,
                                'silver_ceiling'  =>   $user->country->silver_ceiling
                        ]);


                        $valeur_message = $user->is_master == true ?  ' Un MASTER ' : ' un sous distributeur ';
                        Mail::to($user->email)->send(new NewAdmin('Vous etes désormais ' . $valeur_message . env('APP_NAME') .
                                ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

                        $msg = "Utilisateur crée avec succès";
                        $color = 'success';

                        return redirect(route('masters.comzones.browse'))->with('color', $color)->with('status', $msg);
                } catch (\Throwable $th) {
                        return redirect('comzone/masters/add')
                                ->withErrors($validator)
                                ->with('color', 'danger')
                                ->with('status', 'Erreur de création')
                                ->withInput();
                }
        }

        public function store_under_custumers(Request $request)
        {

                $validator = Validator::make($request->all(), [
                        'firstname' => ['required', 'string', 'max:255'],
                        'lastname' => ['required', 'string', 'max:255'],
                        'zone' => 'required',
                        'email' => ['required', 'string', 'email', 'max:255', 'unique:sid_users'],
                        'phone' => ['required', 'unique:sid_users'],
                        'password' => ['required', 'confirmed'],
                        'image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
                        'image_recto' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
                        'image_verso' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
                ]);

                if ($validator->fails()) {
                        return redirect('comzone/custumers/add')
                                ->withErrors($validator)
                                ->with('color', 'danger')
                                ->with('status', 'Champ requise')
                                ->withInput();
                }


                $user = DB::table('sid_users')->where('email', $request->email)->first();


                try {

                        $rt = rand(40, 5000) * time();
                        $tr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
                        $walletCode = sprintf("%0.6s", str_shuffle($rt . $tr));

                        $user = new SidUser();
                        $user->firstname  = $request->firstname;
                        $user->company_id  = 1;
                        $user->lastname  = $request->lastname;
                        $user->country_id  = comzone()->country->id ?? null;
                        $user->city_id  = $request->city ?? null;
                        $user->zone_id  = $request->zone_id ?? null;
                        $user->zone  = $request->zone ?? null;
                        $user->email =  $request->email;
                        $user->phone  = $request->phone;
                        $user->created_by = comzone()->id;
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
                        $user->is_custumers  = true;
                        $user->is_supervisor  =  false;
                        $user->is_company  = false;
                        $user->is_master   = false;
                        $user->is_com_zone = false;
                        // $user->assignRole(7);

                        if (!$user->country->currencie) {

                                return redirect('comzone/custumers/add')
                                        ->withErrors($validator)
                                        ->with('color', 'danger')
                                        ->with('status', 'Pas de devise dans se pays\n \nveillez activer la devise')
                                        ->withInput();
                        }



                        $user->save();

                        if (isset($file_image_recto) && !empty($file_image_recto) && isset($file_image_verso) && !empty($file_image_verso)) {

                                $destination_path = public_path('kyc_masters/' . $user->id . '/');
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
                                                'screen' => env('APP_URL') . "/public/kyc_masters/" . $user->id . "/$front_file",
                                                'screen_back' => env('APP_URL') . "/public/kyc_masters/" . $user->id . "/$back_file",
                                                'sid_user_id' => $user->id,
                                                'status' => true,
                                                'company_id'  =>  $user->company->id
                                        ]
                                );
                        }


                        $wallet_user = Wallet::create([
                                'company_id' =>   1,
                                'wallet_id' =>   $user->country->code . $walletCode,
                                'country_id' =>  $user->country->id,
                                'created_by' => comzone()->id,
                                'sid_user_id' =>  $user->id,
                                'currencie_id' =>  $user->country->currencie->id,
                                'amount' => 0.0,
                                'reload_by' => comzone()->firstname . ' ' . comzone()->lastname,
                                'status' => true
                        ]);

                        $user->update([
                                'company_id'  => 1,
                                'wallet_id'  => $wallet_user->wallet_id,
                                'master_id'  => null,
                                'is_accepted' => false,
                                'phone'  =>   $user->country->indecative . $user->phone,
                                'silver_ceiling'  =>   $user->country->silver_ceiling
                        ]);


                        $valeur_message = $user->is_custumers == true ?  ' Un Client ' : ' un sous distributeur ';
                        Mail::to($user->email)->send(new NewAdmin('Vous etes désormais ' . $valeur_message . env('APP_NAME') .
                                ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

                        $msg = "Utilisateur crée avec succès";
                        $color = 'success';

                        return redirect(route('custumers.comzones.browse'))->with('color', $color)->with('status', $msg);
                } catch (\Throwable $th) {
                        return redirect('comzone/custumers/add')
                                ->withErrors($validator)
                                ->with('color', 'danger')
                                ->with('status', 'Erreur de création')
                                ->withInput();
                }
        }
        /**
         * Display the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function show__underdistributors(Request $request, int $id)
        {
                if (comzone()) {


                        $user = SidUser::whereId($id)->first();

                        $user_wallet =  $user->wallet->id;

                        $master_wallet =  comzone()->wallet->id;

                        // commissions transactions
                        $transaction_com = $this->transactionAccountService->walletSumCommission(WalletTransaction::class, $user_wallet);

                        $paiement_com = $this->transactionAccountService->walletSumCommission(WalletPayment::class, $user_wallet);

                        $virement_com = $this->transactionAccountService->walletSumCommission(WalletBank::class, $user_wallet);

                        $phoneCredit_com = $this->transactionAccountService->walletSumCommission(WalletPhoneCredit::class, $user_wallet);

                        $reloadCard_com = $this->transactionAccountService->walletSumCommission(WalletDeposit::class, $user_wallet);

                        $International_com = $this->transactionAccountService->walletSumCommission(WalletInter::class, $user_wallet);

                        $walletOrderCarte_com = $this->transactionAccountService->walletSumCommission(BankCard::class, $user_wallet);

                        // amounts valeurs transactions
                        $transactionAmounts = $this->transactionAccountService->getTransactionAmount(WalletTransaction::class, $user_wallet);

                        $paiementAmounts = $this->transactionAccountService->getTransactionAmount(WalletPayment::class, $user_wallet);

                        $virementAmounts = $this->transactionAccountService->getTransactionAmount(WalletBank::class, $user_wallet);

                        $phoneCreditAmounts = $this->transactionAccountService->getTransactionAmount(WalletPhoneCredit::class, $user_wallet);

                        $reloadCardAmounts = $this->transactionAccountService->getTransactionAmount(WalletDeposit::class, $user_wallet);

                        $reloadAmounts = $this->transactionAccountService->getTransactionAmount(WalletReload::class, $master_wallet);

                        $InternationalAmounts = $this->transactionAccountService->getTransactionAmount(WalletInter::class, $user_wallet);

                        $walletOrderCarteAmounts = $this->transactionAccountService->getTransactionAmount(BankCard::class, $user_wallet);

                        // sum commissions masters

                        $sum_wallet_transaction = $transaction_com + $paiement_com + $virement_com  + $phoneCredit_com + $reloadCard_com + $International_com + $walletOrderCarte_com;

                        // All types transactions listes
                        $transactions = $this->transactionAccountService->getWalletTransactions(WalletTransaction::class, $user_wallet);

                        $paiements = $this->transactionAccountService->getWalletTransactions(WalletPayment::class, $user_wallet);

                        $virements = $this->transactionAccountService->getWalletTransactions(WalletBank::class, $user_wallet);

                        $phoneCredits = $this->transactionAccountService->getWalletTransactions(WalletPhoneCredit::class, $user_wallet);

                        $realoadUvs = $this->transactionAccountService->getWalletTransactions(WalletReload::class, $master_wallet);

                        $reloadCards = $this->transactionAccountService->getWalletTransactions(WalletDeposit::class, $user_wallet);

                        $internationals = $this->transactionAccountService->getWalletTransactions(WalletInter::class, $user_wallet);

                        $walletOrderCarte = $this->transactionAccountService->getWalletTransactions(BankCard::class, $user_wallet);

                        // Utilisation de la fonction pour les transactions de compte
                        $accountTransactionCounts = $this->getTransactionCounts(WalletTransaction::class, $user_wallet);
                        // Utilisation de la fonction pour les virements de compte bancaire
                        $accountVirementCounts = $this->getTransactionCounts(WalletBank::class, $user_wallet);
                        $accountPaymentCounts = $this->getTransactionCounts(WalletPayment::class, $user_wallet);
                        $accountBankCounts = $this->getTransactionCounts(BankCard::class, $user_wallet);
                        $accountInterCounts = $this->getTransactionCounts(WalletInter::class, $user_wallet);
                        $accountDepositCounts = $this->getTransactionCounts(WalletDeposit::class, $user_wallet);
                        $accountPhoneCreditCounts = $this->getTransactionCounts(WalletPhoneCredit::class, $user_wallet);

                        // Accéder aux compteurs spécifiques
                        $accountTransactionPendings = $accountTransactionCounts['PENDING'];
                        $accountTransactionSuccess = $accountTransactionCounts['SUCCESS'];
                        $accountTransactionErrors = $accountTransactionCounts['ERROR'];

                        $accountVirementPendings = $accountVirementCounts['PENDING'];
                        $accountVirementSuccess = $accountVirementCounts['SUCCESS'];
                        $accountVirementErrors = $accountVirementCounts['ERROR'];

                        $accountPaymentPendings = $accountPaymentCounts['PENDING'];
                        $accountPaymentSuccess = $accountPaymentCounts['SUCCESS'];
                        $accountPaymentErrors = $accountPaymentCounts['ERROR'];

                        $accountBankPendings = $accountBankCounts['PENDING'];
                        $accountBankSuccess = $accountBankCounts['SUCCESS'];
                        $accountBankErrors = $accountBankCounts['ERROR'];

                        $accountInterPendings = $accountInterCounts['PENDING'];
                        $accountInterSuccess = $accountInterCounts['SUCCESS'];
                        $accountInterErrors = $accountInterCounts['ERROR'];

                        $accountDepositPendings = $accountDepositCounts['PENDING'];
                        $accountDepositSuccess = $accountDepositCounts['SUCCESS'];
                        $accountDepositErrors = $accountDepositCounts['ERROR'];

                        $accountPhonePendings = $accountPhoneCreditCounts['PENDING'];
                        $accountPhoneSuccess = $accountPhoneCreditCounts['SUCCESS'];
                        $accountPhoneErrors = $accountPhoneCreditCounts['ERROR'];


                        return view('comzones.pages.show_underdistributors', compact(
                                'user',
                                'transaction_com',
                                'paiement_com',
                                'virement_com',
                                'phoneCredit_com',
                                'reloadCard_com',
                                'International_com',
                                'walletOrderCarte_com',
                                'transactionAmounts',
                                'paiementAmounts',
                                'virementAmounts',
                                'phoneCreditAmounts',
                                'reloadCardAmounts',
                                'InternationalAmounts',
                                'walletOrderCarteAmounts',
                                'sum_wallet_transaction',
                                'transactions',
                                'paiements',
                                'virements',
                                'phoneCredits',
                                'realoadUvs',
                                'reloadCards',
                                'internationals',
                                'walletOrderCarte',
                                'accountTransactionPendings',
                                'accountTransactionSuccess',
                                'accountTransactionErrors',
                                'accountVirementPendings',
                                'accountVirementSuccess',
                                'accountVirementErrors',
                                'accountPaymentPendings',
                                'accountPaymentSuccess',
                                'accountPaymentErrors',
                                'accountBankPendings',
                                'accountBankSuccess',
                                'accountBankErrors',
                                'accountInterPendings',
                                'accountInterSuccess',
                                'accountInterErrors',
                                'accountDepositPendings',
                                'accountDepositSuccess',
                                'accountDepositErrors',
                                'accountPhonePendings',
                                'accountPhoneSuccess',
                                'accountPhoneErrors',
                                'reloadAmounts'
                        ));
                } else {
                        abort(403);
                }
        }

        public function show__custumers(Request $request, int $id)
        {

                if (comzone()) {


                        $user = SidUser::whereId($id)->first();

                        $user_wallet =  $user->wallet->id;

                        $master_wallet =  comzone()->wallet->id;

                        // commissions transactions
                        $transaction_com = $this->transactionAccountService->walletSumCommission(WalletTransaction::class, $user_wallet);

                        $paiement_com = $this->transactionAccountService->walletSumCommission(WalletPayment::class, $user_wallet);

                        $virement_com = $this->transactionAccountService->walletSumCommission(WalletBank::class, $user_wallet);

                        $phoneCredit_com = $this->transactionAccountService->walletSumCommission(WalletPhoneCredit::class, $user_wallet);

                        $reloadCard_com = $this->transactionAccountService->walletSumCommission(WalletDeposit::class, $user_wallet);

                        $International_com = $this->transactionAccountService->walletSumCommission(WalletInter::class, $user_wallet);

                        $walletOrderCarte_com = $this->transactionAccountService->walletSumCommission(BankCard::class, $user_wallet);

                        // amounts valeurs transactions
                        $transactionAmounts = $this->transactionAccountService->getTransactionAmount(WalletTransaction::class, $user_wallet);

                        $paiementAmounts = $this->transactionAccountService->getTransactionAmount(WalletPayment::class, $user_wallet);

                        $virementAmounts = $this->transactionAccountService->getTransactionAmount(WalletBank::class, $user_wallet);

                        $phoneCreditAmounts = $this->transactionAccountService->getTransactionAmount(WalletPhoneCredit::class, $user_wallet);

                        $reloadCardAmounts = $this->transactionAccountService->getTransactionAmount(WalletDeposit::class, $user_wallet);

                        $reloadAmounts = $this->transactionAccountService->getTransactionAmount(WalletReload::class, $master_wallet);

                        $InternationalAmounts = $this->transactionAccountService->getTransactionAmount(WalletInter::class, $user_wallet);

                        $walletOrderCarteAmounts = $this->transactionAccountService->getTransactionAmount(BankCard::class, $user_wallet);

                        // sum commissions masters

                        $sum_wallet_transaction = $transaction_com + $paiement_com + $virement_com  + $phoneCredit_com + $reloadCard_com + $International_com + $walletOrderCarte_com;

                        // All types transactions listes
                        $transactions = $this->transactionAccountService->getWalletTransactions(WalletTransaction::class, $user_wallet);

                        $paiements = $this->transactionAccountService->getWalletTransactions(WalletPayment::class, $user_wallet);

                        $virements = $this->transactionAccountService->getWalletTransactions(WalletBank::class, $user_wallet);

                        $phoneCredits = $this->transactionAccountService->getWalletTransactions(WalletPhoneCredit::class, $user_wallet);

                        $realoadUvs = $this->transactionAccountService->getWalletTransactions(WalletReload::class, $master_wallet);

                        $reloadCards = $this->transactionAccountService->getWalletTransactions(WalletDeposit::class, $user_wallet);

                        $internationals = $this->transactionAccountService->getWalletTransactions(WalletInter::class, $user_wallet);

                        $walletOrderCarte = $this->transactionAccountService->getWalletTransactions(BankCard::class, $user_wallet);

                        // Utilisation de la fonction pour les transactions de compte
                        $accountTransactionCounts = $this->getTransactionCounts(WalletTransaction::class, $user_wallet);
                        // Utilisation de la fonction pour les virements de compte bancaire
                        $accountVirementCounts = $this->getTransactionCounts(WalletBank::class, $user_wallet);
                        $accountPaymentCounts = $this->getTransactionCounts(WalletPayment::class, $user_wallet);
                        $accountBankCounts = $this->getTransactionCounts(BankCard::class, $user_wallet);
                        $accountInterCounts = $this->getTransactionCounts(WalletInter::class, $user_wallet);
                        $accountDepositCounts = $this->getTransactionCounts(WalletDeposit::class, $user_wallet);
                        $accountPhoneCreditCounts = $this->getTransactionCounts(WalletPhoneCredit::class, $user_wallet);

                        // Accéder aux compteurs spécifiques
                        $accountTransactionPendings = $accountTransactionCounts['PENDING'];
                        $accountTransactionSuccess = $accountTransactionCounts['SUCCESS'];
                        $accountTransactionErrors = $accountTransactionCounts['ERROR'];

                        $accountVirementPendings = $accountVirementCounts['PENDING'];
                        $accountVirementSuccess = $accountVirementCounts['SUCCESS'];
                        $accountVirementErrors = $accountVirementCounts['ERROR'];

                        $accountPaymentPendings = $accountPaymentCounts['PENDING'];
                        $accountPaymentSuccess = $accountPaymentCounts['SUCCESS'];
                        $accountPaymentErrors = $accountPaymentCounts['ERROR'];

                        $accountBankPendings = $accountBankCounts['PENDING'];
                        $accountBankSuccess = $accountBankCounts['SUCCESS'];
                        $accountBankErrors = $accountBankCounts['ERROR'];

                        $accountInterPendings = $accountInterCounts['PENDING'];
                        $accountInterSuccess = $accountInterCounts['SUCCESS'];
                        $accountInterErrors = $accountInterCounts['ERROR'];

                        $accountDepositPendings = $accountDepositCounts['PENDING'];
                        $accountDepositSuccess = $accountDepositCounts['SUCCESS'];
                        $accountDepositErrors = $accountDepositCounts['ERROR'];

                        $accountPhonePendings = $accountPhoneCreditCounts['PENDING'];
                        $accountPhoneSuccess = $accountPhoneCreditCounts['SUCCESS'];
                        $accountPhoneErrors = $accountPhoneCreditCounts['ERROR'];


                        return view('comzones.pages.show_custumers', compact(
                                'user',
                                'transaction_com',
                                'paiement_com',
                                'virement_com',
                                'phoneCredit_com',
                                'reloadCard_com',
                                'International_com',
                                'walletOrderCarte_com',
                                'transactionAmounts',
                                'paiementAmounts',
                                'virementAmounts',
                                'phoneCreditAmounts',
                                'reloadCardAmounts',
                                'InternationalAmounts',
                                'walletOrderCarteAmounts',
                                'sum_wallet_transaction',
                                'transactions',
                                'paiements',
                                'virements',
                                'phoneCredits',
                                'realoadUvs',
                                'reloadCards',
                                'internationals',
                                'walletOrderCarte',
                                'accountTransactionPendings',
                                'accountTransactionSuccess',
                                'accountTransactionErrors',
                                'accountVirementPendings',
                                'accountVirementSuccess',
                                'accountVirementErrors',
                                'accountPaymentPendings',
                                'accountPaymentSuccess',
                                'accountPaymentErrors',
                                'accountBankPendings',
                                'accountBankSuccess',
                                'accountBankErrors',
                                'accountInterPendings',
                                'accountInterSuccess',
                                'accountInterErrors',
                                'accountDepositPendings',
                                'accountDepositSuccess',
                                'accountDepositErrors',
                                'accountPhonePendings',
                                'accountPhoneSuccess',
                                'accountPhoneErrors',
                                'reloadAmounts'
                        ));
                } else {
                        abort(403);
                }
        }



        private function getTransactionCounts($modelClass, $userWallet)
        {
                $statuses = ['PENDING', 'SUCCESS', 'ERROR'];

                $counts = [];

                foreach ($statuses as $status) {
                        $counts[$status] = $this->transactionAccountService->getTransactionCount($modelClass, $userWallet, $status);
                }

                return $counts;
        }

        public function update(Request $request)
        {

                $validator = Validator::make($request->all(), [
                        'firstname' => ['required', 'string', 'max:255'],
                        'lastname' => ['required', 'string', 'max:255'],
                        'phone' => ['required', 'string', 'max:255'],
                        'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
                ]);

                if ($validator->fails()) {
                        return redirect('account')
                                ->withErrors($validator)
                                ->with('color', 'danger')
                                ->with('status', 'Erreur de modification')
                                ->withInput();
                }
                try {

                        $tool = new Image;

                        DB::beginTransaction();
                        $user = SidUser::find(Auth::user()->id);
                        $user->firstname = $request->firstname;
                        $user->lastname = $request->lastname;
                        $user->phone = $request->phone;
                        $user->country_id = $request->country_id ?? '157';
                        if ($request->image) {
                                $tool = new Image;
                                $user->image = $tool->saveInDisk($request, 'admins/' . Auth::user()->company_id . '/admin');
                        }
                        $user->save();

                        DB::commit();
                        return redirect(route('account.profile'))->with('status', 'Mise a jour effectuée avec succès');
                } catch (\Exception $exception) {
                        return redirect('account')
                                ->withErrors($validator)
                                ->with('color', 'danger')
                                ->with('status', 'Erreur de modification')
                                ->withInput();
                }
        }
}
