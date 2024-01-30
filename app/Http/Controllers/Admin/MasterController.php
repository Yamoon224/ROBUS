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

class MasterController extends Controller
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

        return view('pages.admin.masters.browse');
    }
    public function browse_custumers()
    {
        if (master()) {
            return view('masters.pages.browse_custumers');
        } else {
            abort(403);
        }
    }
    public function browse_underdistributors()
    {

        if (master()) {
            return view('masters.pages.browse_underdistributors');
        } else {
            abort(403);
        }
    }

    public function browse_reloads()
    {
        if (master()) {
            return view('masters.pages.browse_reloads');
        } else {
            abort(403);
        }
    }


    public function browse_dumps()
    {
        if (master()) {
            return view('masters.pages.browse_dumps');
        } else {
            abort(403);
        }
    }

    public function add_underdistributors()
    {

        if (master()) {
            $role = Role::whereId(4)->orderBy('created_at', 'desc')->first();
            $countrie_master = master()->country->id;

            $countrie = Country::whereId($countrie_master)->first();
            $cities  = City::whereCountryId($countrie_master)->orderBy('name', 'asc')->get();
            return view('masters.pages.add_underdistributors', compact('role', 'countrie', 'cities'));
        } else {
            abort(403);
        }
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
            return redirect('master/underdistributors/add')
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
            $countrie_master = master()->country->id;
            $countrie = Country::whereId($countrie_master)->first();

            $user = new SidUser();
            $user->firstname  = $request->firstname;
            $user->company_id  = 1;
            $user->lastname  = $request->lastname;
            $user->country_id  = $countrie->id ?? null;
            $user->city_id  = $request->city_id ?? null;
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
            $user->is_under_distributer  = true;
            $user->is_distributer  = false;
            $user->is_accounting  = false;
            $user->is_admin  =  false;
            $user->is_custumers  = false;
            $user->is_supervisor  =  false;
            $user->is_accepted  =  false;
            $user->is_company  = false;
            $user->master_id = master()->id;
            $roles = [4];

            // after active for Admin
            // $user->assignRole($roles , 'web');
            // countrie valeurs

            if (!$countrie->currencie) {

                return redirect('master/underdistributors/add')
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
                'created_by' => master()->id,
                'sid_user_id' =>  $user->id,
                'currencie_id' =>  $user->country->currencie->id,
                'amount' => 0.0,
                'reload_by' => master()->firstname . ' ' . master()->lastname,
                'status' => true

            ]);



            $user->update([
                'wallet_id'  => $wallet_user->wallet_id,
                'phone'  =>   $user->country->indecative . $user->phone,
                'silver_ceiling'  =>   $user->country->silver_ceiling
            ]);



            Mail::to($user->email)->send(new NewAdmin('Vous etes désormais un utilisateur marchant de ' . env('APP_NAME') .
                ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

            $msg = "sous distributeur crée avec succès";
            $color = 'success';

            return redirect(route('underdistributors.home'))->with('color', $color)->with('status', $msg);
        } catch (\Throwable $th) {
            return redirect('master/underdistributors/add')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de création')
                ->withInput();
        }
    }


    public function ShowProfil(int $id, Request $request)
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('modifier-master')) {
            abort(403);
        }

        $paiement_status = null;

        $user = SidUser::whereId($id)->first();

        $wallet_solde = Wallet::where('sid_user_id', $user->id)->first();

        $transactions = $this->transactionAccountService->getTransactionsTypeAll(WalletTransaction::class , 'num_wallet' ,$wallet_solde ) ;  

        $paiements = $this->transactionAccountService->getTransactionsAll(WalletPayment::class , $wallet_solde);

        $virements = $this->transactionAccountService->getTransactionsAll(WalletBank::class , $wallet_solde);

        $phoneCredits = $this->transactionAccountService->getTransactionsAll(WalletPhoneCredit::class , $wallet_solde);

        $reaload_uv = $this->transactionAccountService->getTransactionsTypeAll(WalletReload::class  , 'account_wallet', $wallet_solde);

        $reloadCards = $this->transactionAccountService->getTransactionsAll(WalletDeposit::class , $wallet_solde);

        $reloadCards = $this->transactionAccountService->getTransactionsAll(WalletDeposit::class , $wallet_solde);

        $Internationals = $this->transactionAccountService->getTransactionsTypeAll(WalletInter::class  , 'number_compte', $wallet_solde);

        $walletOrderCarte = $this->transactionAccountService->getTransactionsAll(BankCard::class , $wallet_solde);

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

    public function browse_categories()
    {
        if (master()) {
            return view('masters.pages.browse_categories');
        } else {
            abort(403);
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
        if (master()) {


            $user = SidUser::whereId($id)->first();

            $user_wallet =  $user->wallet->id;

            $master_wallet =  master()->wallet->id;

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


            return view('masters.pages.show_underdistributors', compact(
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

        SidUser::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);


        // $notify = new MailController;
        // $notify->modifyPassword($request);

        return redirect('account/security')
            ->with('status', 'Mise a jour effectuée avec succès');
    }
}
