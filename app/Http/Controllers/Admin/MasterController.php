<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Admin\MailController;
use App\Http\Controllers\Controller;
use App\Http\Services\TransactionsAccountService;
use App\Mail\NewAdmin;
use App\Models\Banks\BankCard;
use App\Models\Custumers\City;
use App\Models\Custumers\Country;
use App\Models\Kyc;
use App\Models\Master;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletTransaction;
use App\Rules\MatchOldPassword;
use App\Tools\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Http\Services\UserCreateService;
use App\Http\Services\ValidatorService;

class MasterController extends Controller
{

    protected $transactionAccountService;
    protected $userValidatorService;
    protected $createUserService;

    public function __construct(
        TransactionsAccountService $transactionAccountService,
        ValidatorService $userValidatorService,
        UserCreateService $createUserService
    ) {
        $this->transactionAccountService = $transactionAccountService;
        $this->userValidatorService = $userValidatorService;
        $this->createUserService = $createUserService;
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

    public function showProfile()
    {
        if (master()) {
            $user = SidUser::where('id', Auth::user()->id)->first();
            return view('pages.admin.masters.showProfile', compact('user'));
        } else {
            abort(403);
        }
    }


    public function security()
    {

        if (master()) {
            $roles = Role::orderBy('created_at', 'DESC')->get();
            $user = SidUser::where('id', Auth::user()->id)->first();
            return view('pages.admin.masters.security', compact('user', 'roles'));
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

    public function store_masters(Request $request)
    {

        $validator = $this->userValidatorService->validateUserDataAdmin($request->all());

        if ($validator->fails()) {
            return redirect('masters/add_master')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Champ requise')
                ->withInput();
        }


        try {

            $response = $this->createUserService->createMasterWeb(
                $request->all(),
                $request->file('image'),
                $request->file('image_recto'),
                $request->file('image_verso')
            );

            $msg = "Un master crée avec succès";

            if ($response['success']) {

                $user = $response['user'];

                $valeur_message = $user->is_under_distributer == true ?  ' Un Sous distributeur ' : ' un masters ';
                Mail::to($user->email)->send(new NewAdmin('Vous etes désormais ' . $valeur_message . env('APP_NAME') .
                    ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

                $msg = "Utilisateur crée avec succès";
                $color = 'success';

                return redirect(route('masters.admins.browse'))->with('color', $color)->with('status', $msg);
            } else {

                $message = $response['message'];

                return redirect('masters/add_master')
                    // ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', $message)
                    ->withInput();
            }
        } catch (\Throwable $th) {
            return redirect('masters/add_master')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de création')
                ->withInput();
        }
    }

    public function store_comzones(Request $request)
    {

        $validator = $this->userValidatorService->validateUserDataAdmin($request->all());

        if ($validator->fails()) {
            return redirect('comzones/add')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Champ requise')
                ->withInput();
        }


        try {

            $response = $this->createUserService->createComZoneWeb(
                $request->all(),
                $request->file('image'),
                $request->file('image_recto'),
                $request->file('image_verso')
            );

            $msg = "Un com zone crée avec succès";

            if ($response['success']) {

                $user = $response['user'];

                $valeur_message = ' un com zone ';
                Mail::to($user->email)->send(new NewAdmin('Vous etes désormais ' . $valeur_message . env('APP_NAME') .
                    ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

                $msg = "Utilisateur crée avec succès";
                $color = 'success';

                return redirect(route('comzones.admins.browse'))->with('color', $color)->with('status', $msg);
            } else {

                $message = $response['message'];

                return redirect('comzones/add')
                    // ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', $message)
                    ->withInput();
            }
        } catch (\Throwable $th) {
            return redirect('comzones/add')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de création')
                ->withInput();
        }
    }

    public function storeunders(Request $request)
    {

        $validator = $this->userValidatorService->validateUserDataAdmin($request->all());

        if ($validator->fails()) {
            return redirect('underdistributors/add')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Champ requise')
                ->withInput();
        }


        try {

            $response = $this->createUserService->createUnderDistributorWeb(
                $request->all(),
                $request->file('image'),
                $request->file('image_recto'),
                $request->file('image_verso')
            );

            $msg = "Un sous distributeur crée avec succès";

            if ($response['success']) {

                $user = $response['user'];

                $valeur_message = ' Sous distributeur ';
                Mail::to($user->email)->send(new NewAdmin('Vous etes désormais ' . $valeur_message . env('APP_NAME') .
                    ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

                $msg = "Sous distributeur crée avec succès";
                $color = 'success';

                return redirect(route('unders.admins.browse'))->with('color', $color)->with('status', $msg);
            } else {

                $message = $response['message'];

                return redirect('underdistributors/add')
                    // ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', $message)
                    ->withInput();
            }
        } catch (\Throwable $th) {
            return redirect('underdistributors/add')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de création')
                ->withInput();
        }
    }

    public function storedistributors(Request $request)
    {

        $validator = $this->userValidatorService->validateUserDataAdmin($request->all());

        if ($validator->fails()) {
            return redirect('distribitors/add')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Champ requise')
                ->withInput();
        }


        try {

            $response = $this->createUserService->createOperatorsWeb(
                $request->all(),
                $request->file('image'),
                $request->file('image_recto'),
                $request->file('image_verso')
            );

            $msg = "Une operatrice crée avec succès";

            if ($response['success']) {

                $user = $response['user'];

                $valeur_message = ' Une operatrice ';
                Mail::to($user->email)->send(new NewAdmin('Vous etes désormais ' . $valeur_message . env('APP_NAME') .
                    ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

                $msg = "Operatrice crée avec succès";
                $color = 'success';

                return redirect(route('operators.admins.browse'))->with('color', $color)->with('status', $msg);
            } else {

                $message = $response['message'];

                return redirect('distribitors/add')
                    // ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', $message)
                    ->withInput();
            }
        } catch (\Throwable $th) {
            return redirect('distribitors/add')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de création')
                ->withInput();
        }
    }



    public function store_under_distributors(Request $request)
    {

        $validator = $this->userValidatorService->validateUserDataMaster($request->all());

        if ($validator->fails()) {
            return redirect('master/underdistributors/add')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Champ requise')
                ->withInput();
        }


        try {

            $response = $this->createUserService->createUnderMasterWeb(
                $request->all(),
                $request->file('image'),
                $request->file('image_recto'),
                $request->file('image_verso')
            );

            $msg = "Sous distributeur crée avec succès";

            if ($response['success']) {

                $user = $response['user'];

                $valeur_message = $user->is_under_distributer == true ?  ' Un Sous distributeur ' : ' un masters ';
                Mail::to($user->email)->send(new NewAdmin('Vous etes désormais ' . $valeur_message . env('APP_NAME') .
                    ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

                $msg = "Utilisateur crée avec succès";
                $color = 'success';

                return redirect(route('underdistributors.browse'))->with('color', $color)->with('status', $msg);
            } else {

                $message = $response['message'];

                return redirect('master/underdistributors/add')
                    // ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', $message)
                    ->withInput();
            }
        } catch (\Throwable $th) {
            return redirect('master/underdistributors/add')
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
            return redirect('master/account')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de modification')
                ->withInput();
        }


        try {

            DB::beginTransaction();
            $user = SidUser::find(master()->id);
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            // $user->phone = $request->phone;
            $user->created_by = master()->id;
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
            return redirect('master/account')
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

    public function show__custumers(Request $request, int $id)
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


            return view('masters.pages.show_custumers', compact(
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
