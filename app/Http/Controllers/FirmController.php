<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Tools\Image;
use App\Models\Sid\SidUser;
use Illuminate\Http\Request;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Rules\MatchOldPassword;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Events\UpdateActiveAccountEvent;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Admin\MailController;
use App\Http\Services\TransactionsAccountService;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletReload;
use App\Models\Banks\BankCard;

class FirmController extends Controller
{

    protected $transactionAccountService;

    public function __construct(
        TransactionsAccountService $transactionAccountService,
    ) {
        $this->transactionAccountService = $transactionAccountService;
    }


    public function browse_employes()
    {

        if (firm()) {
            return view('firms.pages.browse_employes');
        } else {
            abort(403);
        }
    }

    public function browse_underDistributors()
    {
        if (firm()) {
            return view('firms.pages.browse_underDistributors');
        } else {
            abort(403);
        }
    }


    public function browse_categories()
    {
        if (firm()) {
            return view('firms.pages.browse_categories');
        } else {
            abort(403);
        }
    }


    public function browse_commissions()
    {
        if (firm()) {
            return view('firms.pages.browse_commissions');
        } else {
            abort(403);
        }
    }

    public function browse_invalidated(Request $request)
    {

        if (Auth::guard('web')->user()->hasPermissionTo('voir-clients')) {

            return view('pages.custumers.browse_invadated');
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
    public function show__employes(Request $request, int $id)
    {
        if (firm()) {


            $user = SidUser::whereId($id)->first();

            $user_wallet =  $user->wallet->wallet_id;

            $sum_wallet_transaction = $this->getWalletTransactionSumAdminProperty(firm()->wallet->id,  $user_wallet);


            $transactions =  WalletTransaction::where('wallet_id', firm()->wallet->id)
                ->where('num_wallet',   $user_wallet)
                ->orderBy('created_at', 'desc')->paginate(20);


            $usersCountry = Auth::guard('web')->user()->country->currencie->name;

            return view('firms.pages.show_custumers', compact(
                'user',
                'transactions',
                'usersCountry',
                'sum_wallet_transaction'
            ));
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
        if (firm()) {


            $user = SidUser::whereId($id)->first();

            $user_wallet =  $user->wallet->id;

            $master_wallet =  firm()->wallet->id;

            // commissions transactions
            $transaction_com = $this->transactionAccountService->walletSumCommissionCompany(WalletTransaction::class, $user_wallet);

            $paiement_com = $this->transactionAccountService->walletSumCommissionCompany(WalletPayment::class, $user_wallet);

            $virement_com = $this->transactionAccountService->walletSumCommissionCompany(WalletBank::class, $user_wallet);

            $phoneCredit_com = $this->transactionAccountService->walletSumCommissionCompany(WalletPhoneCredit::class, $user_wallet);

            $reloadCard_com = $this->transactionAccountService->walletSumCommissionCompany(WalletDeposit::class, $user_wallet);

            $International_com = $this->transactionAccountService->walletSumCommissionCompany(WalletInter::class, $user_wallet);

            $walletOrderCarte_com = $this->transactionAccountService->walletSumCommissionCompany(BankCard::class, $user_wallet);

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


            return view('firms.pages.show_underdistributors', compact(
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
    public function getWalletTransactionSumAdminProperty($id, $user_wallet)
    {
        $todays = Carbon::now();

        $data = [];

        foreach (WalletTransaction::where('wallet_id', $id)->where('num_wallet',   $user_wallet)->where('status', 'SUCCESS')->get() as $transaction) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }
    public function show_company(Request $request, int $id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('voir-clients')) {


            $user = SidUser::whereId($id)->first();

            $kycs = $user->kyc()->get() ?? null;


            $wallet_solde = Wallet::where('sid_user_id', $user->id)->first();

            $transactions =  WalletTransaction::where('wallet_id', $wallet_solde->id)
                ->orWhere('num_wallet',  $wallet_solde->wallet_id)
                ->orderBy('created_at', 'desc')->paginate(20);



            $employers =  SidUser::where('company_id', $user->company_id)->paginate(20);

            $usersCountry = Auth::guard('web')->user()->country->currencie->name;

            return view('pages.custumers.show_company', compact(
                'user',
                'transactions',
                'usersCountry',
                'employers',
                'kycs'
            ));
        } else {
            abort(403);
        }
    }
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('users/browse')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Données saisie incorrecte')
                ->withInput();
        }

        $zones = DB::table('zones')->orderBy('name', 'ASC')->get();

        $users = User::where('status', true)->where('firstname', 'like', '%' . $request->get('query') . '%')
            ->orWhere('email',  'like', '%' . $request->get('query') . '%')
            ->orWhere('phone',  'like', '%' . $request->get('query') . '%')
            ->orWhere('lastname',  'like', '%' . $request->get('query') . '%')
            ->paginate(15);
        return view('pages.custumers.browse', compact('users', 'zones'));
    }

    public function go(Request $request)
    {

        $select = array();

        $q = DB::table('users');
        $q->where('users.status', true);
        $q->join('zones', 'users.zone_id', 'zones.id');
        $q->orderBy('users.created_at', 'DESC');

        array_push($select, 'users.*');
        $zones = DB::table('zones')->orderBy('name', 'ASC')->get();

        if ($request->zone_id) {
            $q->where('zone_id', $request->get('zone_id'));
        }

        // dd($q);

        $q->select($select);
        $users = $q->get();
        //    $users = $users->paginate(15);

        return view('pages.custumers.browse', compact('users', 'zones'));
    }

    public function activate(int $id)
    {
        try {
            DB::beginTransaction();
            $user = SidUser::find($id);

            if ($user->is_active == '1') {

                $user->is_active = '0';
                $msg = 'Désactivation effectuée avec succès';
                $message = 'Votre compte a été desactivé avec success';

                if ($user->country->code == "CI") {

                    $message = 'Votre compte a été desactivez avec success';

                    $encode = substr($user->phone,  -10);


                    Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                } else {

                    $notify = new MailController;
                    $notify->activeAccount($user);
                }
            } else {

                $user->is_active = '1';
                $msg = 'Activation effectuée avec succès';

                $message = 'Votre compte a été activé avec succès';

                if ($user->country->code == "CI") {

                    $message = 'Votre compte a été activé avec succès';

                    $encode = substr($user->phone,  -10);

                    Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                } else {

                    $notify = new MailController;
                    $notify->activeAccount($user);
                }
            }
            $user->save();
            DB::commit();

            $color = 'success';
        } catch (\Exception $exception) {
            DB::rollBack();
            $msg = 'Action échouée';
            $color = 'danger';
        }
        return redirect()->back()->with('color', $color)
            ->with('status', $msg);
    }

    public function activate_wallet(int $id)
    {

        try {

            DB::beginTransaction();

            $user = SidUser::find($id);

            if ($user->wallet->status == '1') {

                $user->wallet->status == '0';
                $user->wallet->update([
                    'status' => 0
                ]);
                $msg = 'Désactivation effectuée avec succès';
                $message = 'Votre compte a été desactivé avec success';

                if ($user->country->code == "CI") {

                    $message = 'Votre compte a été desactivé pour une raison';

                    $encode = substr($user->phone,  -10);

                    Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                } else {

                    $notify = new MailController;
                    $notify->activeAccount($user);
                }
            } else {

                $user->wallet->status == '1';
                $user->wallet->update([
                    'status' => 1
                ]);
                $msg = 'Activation effectuée avec succès';

                $message = 'Votre compte a été activé avec succès';

                if ($user->country->code == "CI") {

                    $message = 'Votre compte a été activé avec succès';

                    $encode = substr($user->phone,  -10);

                    Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                } else {

                    $notify = new MailController;
                    $notify->activeAccount($user);
                }
            }

            $user->wallet->save();

            DB::commit();

            $color = 'success';
        } catch (\Exception $exception) {
            DB::rollBack();
            $msg = 'Action échouée';
            $color = 'danger';
        }

        return redirect()->back()->with('color', $color)
            ->with('status', $msg);
    }

    public function activate_wallet_employer(int $id)
    {

        try {

            DB::beginTransaction();

            $user = SidUser::find($id);

            if ($user->wallet->status == '1') {

                $user->wallet->status == '0';
                $user->wallet->update([
                    'status' => 0
                ]);
                $msg = 'Désactivation effectuée avec succès';
                $message = 'Votre compte a été desactivé avec success';

                if ($user->country->code == "CI") {

                    $message = 'Votre compte a été desactivé pour une raison';

                    $encode = substr($user->phone,  -10);

                    Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                } else {

                    $notify = new MailController;
                    $notify->activeAccount($user);
                }
            } else {

                $user->wallet->status == '1';
                $user->wallet->update([
                    'status' => 1
                ]);
                $msg = 'Activation effectuée avec succès';

                $message = 'Votre compte a été activé avec succès';

                if ($user->country->code == "CI") {

                    $message = 'Votre compte a été activé avec succès';

                    $encode = substr($user->phone,  -10);

                    Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                } else {

                    $notify = new MailController;
                    $notify->activeAccount($user);
                }
            }

            $user->wallet->save();

            DB::commit();

            $color = 'success';
        } catch (\Exception $exception) {
            DB::rollBack();
            $msg = 'Action échouée';
            $color = 'danger';
        }

        return redirect()->back()->with('color', $color)
            ->with('status', $msg);
    }
    public function index()
    {

        if (!Auth::user()->hasPermissionTo('voir-administrateur')) {
            abort(403);
        }

        $countries = Country::where('status', true)->get();
        $roles = Role::orderBy('created_at', 'DESC')->get();
        $user = SidUser::where('id', Auth::user()->id)->first();
        return view('pages.account.profile', compact('user', 'countries', 'roles'));
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

    public function update_employe(Request $request)
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
            return redirect(route('companies.home'))->with('status', 'Mise a jour effectuée avec succès');
        } catch (\Exception $exception) {
            return redirect('account')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de modification')
                ->withInput();
        }
    }
    public function update_custumers(Request $request, $id)
    {



        try {


            DB::beginTransaction();
            $user = SidUser::findOrFail($id);
            // Update User active

            if ($user->kyc != null) {

                if ($user->kyc->image != null &&  $user->kyc->screen == null &&  $user->kyc->screen_back == null) {

                    $user->kyc->update([
                        'validated_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                        'validated_at' => Carbon::today(),
                        'status' => true
                    ]);

                    $user->update([
                        'is_active' => true,
                        'silver_ceiling'  => $user->country->silver_ceiling_no_identity,
                        'Without_piece'  => false
                    ]);

                    $user->wallet->update([
                        'status' => true
                    ]);

                    if ($user->country->code == "CI") {

                        $message = 'Votre compte a été activé avec succès';

                        $encode = substr($user->phone,  -10);

                        Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                    } else {

                        $notify = new MailController;
                        $notify->activeAccount($user);
                    }
                    event(new UpdateActiveAccountEvent($user));
                    return redirect(route('custumers.browse'))->with('status', 'Client active avec succès');
                } elseif ($user->kyc->image == null &&  $user->kyc->screen != null &&  $user->kyc->screen_back != null) {


                    $user->kyc->update([
                        'validated_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                        'validated_at' => Carbon::today(),
                        'status' => true
                    ]);

                    $user->update([
                        'is_active' => true,
                        'Without_piece'  => true,
                        'silver_ceiling'  => $user->country->silver_ceiling ?? null,
                    ]);

                    $user->wallet->update([
                        'status' => true
                    ]);

                    if ($user->country->code == "CI") {

                        $message = 'Votre compte a été activé avec succès';

                        $encode = substr($user->phone,  -10);

                        Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                    } else {

                        $notify = new MailController;
                        $notify->activeAccount($user);
                    }
                    event(new UpdateActiveAccountEvent($user));
                    return redirect(route('custumers.browse'))->with('status', 'Client active avec succès');
                } elseif ($user->kyc->image != null &&  $user->kyc->screen != null &&  $user->kyc->screen_back != null) {


                    $user->kyc->update([
                        'validated_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                        'validated_at' => Carbon::today(),
                        'status' => true
                    ]);

                    $user->update([
                        'is_active' => true,
                        'Without_piece'  => true,
                        'silver_ceiling'  => $user->country->silver_ceiling ?? null,
                    ]);

                    $user->wallet->update([
                        'status' => true
                    ]);

                    if ($user->country->code == "CI") {

                        $message = 'Votre compte a été activé avec succès';

                        $encode = substr($user->phone,  -10);

                        Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                    } else {

                        $notify = new MailController;
                        $notify->activeAccount($user);
                    }
                    event(new UpdateActiveAccountEvent($user));
                    return redirect(route('custumers.browse'))->with('status', 'Client active avec succès');
                }
            } else {

                return back()
                    ->with('color', 'danger')
                    ->with('status', 'Ce client a pas ses dossiers')
                    ->withInput();
            }


            // DB::commit();

            // return redirect(route('custumers.browse'))->with('status', 'Vous avez validez le compte de \n' . ' \n ' . $user->firstname . ' ' .  $user->lastname);
        } catch (\Exception $exception) {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Erreur de modification')
                ->withInput();
        }
    }


    public function update_silver_ceiling(Request $request, $id)
    {



        try {


            DB::beginTransaction();
            $user = SidUser::findOrFail($id);

            $user->update([
                'silver_ceiling' => $request->silver_ceiling,
            ]);

            return redirect(route('custumers.browse'))->with('status', 'Mise a jour effectuée avec succès');
        } catch (\Exception $exception) {
            return redirect('account')
                ->with('color', 'danger')
                ->with('status', 'Erreur de modification')
                ->withInput();
        }
    }

    public function update_silver_admin_ceiling(Request $request, $id)
    {



        try {


            DB::beginTransaction();
            $user = SidUser::findOrFail($id);

            $user->update([
                'silver_ceiling' => $request->silver_ceiling,
            ]);

            return redirect(route('admin.browse'))->with('status', 'Mise a jour effectuée avec succès');
        } catch (\Exception $exception) {
            return redirect('account')
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

    public function updatePassword_employer(Request $request)
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

    public function delete(int $id)
    {
        if (!Auth::user()->hasPermissionTo('supprimer-clients')) {
            abort(403);
        }
        try {
            DB::beginTransaction();
            $users = SidUser::findOrFail($id);
            $users->forceDelete();
            if ($users->wallet != null) {
                $users->wallet->forceDelete();
            }
            DB::commit();
            return back()
                ->with('status', 'Client supprimée avec succèss');
        } catch (\Exception $exception) {
            return redirect(route('users.browse'))
                ->with('color', 'danger')
                ->with('status', 'Une erreure est survenue');
        }
    }

    public function delete_employe(int $id)
    {

        try {
            DB::beginTransaction();
            $users = SidUser::findOrFail($id);
            $users->forceDelete();
            if ($users->wallet != null) {
                $users->wallet->forceDelete();
            }
            DB::commit();
            return back()
                ->with('status', 'Client supprimée avec succèss');
        } catch (\Exception $exception) {
            return redirect(route('users.browse'))
                ->with('color', 'danger')
                ->with('status', 'Une erreure est survenue');
        }
    }
}
