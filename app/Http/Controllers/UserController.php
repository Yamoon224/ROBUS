<?php

namespace App\Http\Controllers;

use App\Events\UpdateActiveAccountEvent;
use App\Http\Controllers\Admin\MailController;
use App\Http\Controllers\Controller;
use App\Http\Services\TransactionsAccountService;
use App\Http\Services\UserCreateService;
use App\Http\Services\ValidatorService;
use App\Mail\NewAdmin;
use App\Models\Banks\BankCard;
use App\Models\Custumers\City;
use App\Models\Custumers\Country;
use App\Models\Custumers\Zone;
use App\Models\Sid\SidUser;
use App\Models\User;
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
use App\Rules\MatchOldPassword;
use App\Tools\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
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


    public function browse(Request $request)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('voir-clients')) {

            return view('pages.custumers.browse');
        } else {
            abort(403);
        }
    }

    public function browse_winnings()
    {
        // if (Auth::guard('web')->user()->hasPermissionTo('gain-commissions')) {

        return view('pages.custumers.browse_winnings');
        // } else {
        //     abort(403);
        // }
    }
    public function browse_company()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('voir-clients')) {

            return view('pages.custumers.browse_company');
        } else {
            abort(403);
        }
    }


    public function browse_comzone()
    {

        if (Auth::guard('web')->user()->hasPermissionTo('voir-clients')) {

            return view('pages.custumers.browse_comzone');
        } else {
            abort(403);
        }
    }

    public function browseunders()
    {

        if (Auth::guard('web')->user()->hasPermissionTo('voir-clients')) {

            return view('pages.custumers.browseunder');
        } else {
            abort(403);
        }
    }

    public function browsedistributors()
    {

        if (Auth::guard('web')->user()->hasPermissionTo('voir-clients')) {

            return view('pages.custumers.browsedistributor');
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
    public function show(Request $request, int $id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('voir-clients')) {


            $user = SidUser::whereId($id)->first();

            $kycs = $user->kyc()->get() ?? null;


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

            // Wallet Error and other
            $sum_error    = $this->getCountWalletTransaction($wallet_solde->id, 'ERROR');
            $sum_success    = $this->getCountWalletTransaction($wallet_solde->id, 'SUCCESS');
            $pending_success    = $this->getCountWalletTransaction($wallet_solde->id, 'PENDING');
            $totalSumAmounts = $this->getTotalSumTransactionsAmount($wallet_solde);

            $usersCountry = Auth::guard('web')->user()->country->currencie->name;

            $sum_wallet_transaction = $this->transactionAccountService->getTransactionsTypeAllAmount(WalletTransaction::class, 'num_wallet', $wallet_solde);

            $sum_wallet_bank = $this->transactionAccountService->getTransactionAmount(WalletBank::class, $wallet_solde->id);

            $sum_wallet_deposit = $this->transactionAccountService->getTransactionAmount(WalletDeposit::class, $wallet_solde->id);

            $sum_wallet_payment = $this->transactionAccountService->getTransactionAmount(WalletPayment::class, $wallet_solde->id);

            $sum_wallet_phone = $this->transactionAccountService->getTransactionAmount(WalletPhoneCredit::class, $wallet_solde->id);

            $sum_wallet_xbet = $this->transactionAccountService->getTransactionAmount(WalletXbet::class, $wallet_solde->id);

            $sum_wallet_Inter = $this->transactionAccountService->getTransactionsTypeAllAmount(WalletInter::class, 'number_compte', $wallet_solde);



            $sum_wallet_transaction_received = $this->transactionAccountService->getTransactionsRecevedAllAmount(WalletTransaction::class, 'num_wallet', $wallet_solde);

            $sum_wallet_Inter_received = $this->transactionAccountService->getTransactionsRecevedAllAmount(WalletInter::class, 'number_compte', $wallet_solde);

            return view('pages.custumers.show', compact(
                'totalSumAmounts',
                'kycs',
                'user',
                'sum_error',
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
                'virements',
                'reaload_uv',
                'reloadCards',
                'paiements',
                'Internationals',
                'transactions',
                'phoneCredits',
                'usersCountry',
                'walletOrderCarte',
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
    public function show_edit(Request $request, int $id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('modifier-utilisateur')) {


            $user = SidUser::whereId($id)->first();

            $kycs = $user->kyc()->get() ?? null;

            $countrieId = $user->country_id;
            
            $wallet_solde = Wallet::where('sid_user_id', $user->id)->first();

            $countrie = Country::whereId($countrieId)->first();

            $cities = City::where('country_id' ,$countrieId)->get();

            return view('pages.custumers.show_edit', compact(
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

    public function show_company(Request $request, int $id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('voir-clients')) {


            $user = SidUser::whereId($id)->first();

            $kycs = $user->kyc()->get() ?? null;


            $wallet_solde = Wallet::where('sid_user_id', $user->id)->first();

            $transactions =  WalletTransaction::where('wallet_id', $wallet_solde->id)
                // ->orWhere('num_wallet' ,  $wallet_solde->wallet_id)
                ->orderBy('created_at', 'desc')->paginate(20);

            $walletreloads =  WalletReload::where('wallet_id', $wallet_solde->id)
                // ->orWhere('num_wallet' ,  $wallet_solde->wallet_id)
                ->orderBy('created_at', 'desc')->paginate(20);

            $employers =  SidUser::where('is_custumers', true)->where('id', '!=', $id)->where('company_id', $user->company_id)->count();
            $underDistributors =  SidUser::where('is_under_distributer', true)->where('id', '!=', $id)->where('company_id', $user->company_id)->count();

            $usersCountry = Auth::guard('web')->user()->country->currencie->name;

            // Gains
            $gain_deposits = $this->getTodayTransactionCompagnieCommissionAmountTotal(WalletDeposit::class , $user->company_id , $user->id);
            $gain_payments = $this->getTodayTransactionCompagnieCommissionAmountTotal(WalletPayment::class, $user->company_id , $user->id);
            $gain_transactions = $this->getEmployerTransactionCommission(WalletTransaction::class, $user->wallet->id);
            $gain_moneys = $this->getTodayTransactionCompagnieMCommissionAmountTotal(WalletTransaction::class, $user->company_id , $user->id);
            $gain_depots = $this->getTodayTransactionCompagnieRobusCommissionAmountTotal(WalletTransaction::class, $user->company_id , $user->id);
            $gain_retraits = $this->getTodayTransactionCompagnieRetraitCommissionAmountTotal(WalletTransaction::class, $user->company_id , $user->id);
            $gain_phone_credits = $this->getTodayTransactionCompagnieCommissionAmountTotal(WalletPhoneCredit::class , $user->company_id , $user->id);
            $gain_inters = $this->getTodayTransactionCompagnieCommissionAmountTotal(WalletInter::class, $user->company_id , $user->id);
            $gain_banks = $this->getTodayTransactionCompagnieCommissionAmountTotal(WalletBank::class, $user->company_id , $user->id);

            $company_gains = $gain_payments + $gain_moneys  + $gain_depots + $gain_retraits  + $gain_transactions + $gain_phone_credits + $gain_inters + $gain_banks + $gain_deposits;

            // Execeptions
            $sum_wallet_drawer = $this->getWalletWithMerchantSumUserProperty($id);

            return view('pages.custumers.show_company', compact(
                'user',
                'transactions',
                'walletreloads',
                'usersCountry',
                'employers',
                'underDistributors',
                'company_gains',
                'gain_deposits',
                'gain_payments',
                'gain_transactions',
                'gain_moneys',
                'gain_depots',
                'gain_retraits',
                'gain_phone_credits',
                'gain_inters',
                'gain_banks',
                'kycs'
            ));
        } else {
            abort(403);
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

    public function getTodayTransactionCompagnieCommissionAmountTotal($walletModel , $idCompagnie ,$id)
    {

        $sidUsers = SidUser::where('company_id',  $idCompagnie)
            ->where('id', '!=', $id)
            ->get();

        $totalAmount = 0;

        foreach ($sidUsers as $sidUser) {

            $sidUserId = $sidUser->wallet->id;

            $amount = $walletModel::where('wallet_id', $sidUserId)
                ->whereStatus('SUCCESS')
                ->sum('commission_companie');

            if ($amount !== null) {
                $totalAmount += $amount;
            }
        }


        return $totalAmount;
    }

    public function getTodayTransactionCompagnieMCommissionAmountTotal($walletModel , $idCompagnie ,$id)
    {

        $sidUsers = SidUser::where('company_id',  $idCompagnie)
            ->where('id', '!=', $id)
            ->get();

        $totalAmount = 0;

        foreach ($sidUsers as $sidUser) {

            $sidUserId = $sidUser->wallet->id;

            $amount = $walletModel::where('wallet_id', $sidUserId)
            ->whereStatus('SUCCESS')
            ->where('provider_id', '!=', 9)
            ->sum('commission_companie');

            if ($amount !== null) {
                $totalAmount += $amount;
            }
        }


        return $totalAmount;
    }
    public function getTodayTransactionCompagnieRobusCommissionAmountTotal($walletModel , $idCompagnie ,$id)
    {

        $sidUsers = SidUser::where('company_id',  $idCompagnie)
            ->where('id', '!=', $id)
            ->get();

        $totalAmount = 0;

        foreach ($sidUsers as $sidUser) {

            $sidUserId = $sidUser->wallet->id;

            $amount = $walletModel::where('wallet_id', $sidUserId)
            ->whereStatus('SUCCESS')
            ->where('provider_id', 9)
            ->where('type_transaction', 'Depot')
            ->sum('commission_companie');

            if ($amount !== null) {
                $totalAmount += $amount;
            }
        }


        return $totalAmount;
    }

    public function getTodayTransactionCompagnieRetraitCommissionAmountTotal($walletModel , $idCompagnie ,$id)
    {

        $sidUsers = SidUser::where('company_id',  $idCompagnie)
            ->where('id', '!=', $id)
            ->get();

        $totalAmount = 0;

        foreach ($sidUsers as $sidUser) {

            $sidUserId = $sidUser->wallet->id;

            $amount = $walletModel::where('wallet_id', $sidUserId)
            ->whereStatus('SUCCESS')
            ->where('provider_id', 9)
            ->where('type_transaction', 'Retrait')
            ->sum('commission_companie');

            if ($amount !== null) {
                $totalAmount += $amount;
            }
        }


        return $totalAmount;
    }

    public function getEmployerTransactionCommission($walletModel , $id)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', $id)
            ->whereStatus('SUCCESS')
            ->sum('commission_companie');

        if ($amount !== null) {
            $totalAmount += $amount;
        }


        return $totalAmount;
    }
    public function getTotalSumTransactionsAmount($wallet_solde)
    {
        $sum_wallet_transaction = $this->transactionAccountService->getTransactionAmount(WalletTransaction::class,  $wallet_solde->id);
        $sum_wallet_bank = $this->transactionAccountService->getTransactionAmount(WalletBank::class, $wallet_solde->id);
        $sum_wallet_deposit = $this->transactionAccountService->getTransactionAmount(WalletDeposit::class, $wallet_solde->id);
        $sum_wallet_payment = $this->transactionAccountService->getTransactionAmount(WalletPayment::class, $wallet_solde->id);
        $sum_wallet_phone = $this->transactionAccountService->getTransactionAmount(WalletPhoneCredit::class, $wallet_solde->id);
        $sum_wallet_wave = $this->transactionAccountService->getTransactionAmount(WalletWave::class, $wallet_solde->id);
        $sum_wallet_xbet_depot = $this->transactionAccountService->getTransactionXbetAmount(WalletXbet::class, $wallet_solde->id, 1);
        $sum_wallet_xbet_retrait = $this->transactionAccountService->getTransactionXbetAmount(WalletXbet::class, $wallet_solde->id, 2);
        $sum_wallet_Inter = $this->transactionAccountService->getTransactionAmount(WalletInter::class,   $wallet_solde->id);
        $sum_wallet_Inter_received = $this->transactionAccountService->getTransactionsRecevedAllAmount(WalletInter::class, 'number_compte', $wallet_solde);
        // Wallet commissions
        $sum_wallet_transaction_received = $this->transactionAccountService->getTransactionsRecevedAllAmount(WalletTransaction::class, 'num_wallet', $wallet_solde);

        return [
            'sum_wallet_transaction' => $sum_wallet_transaction,
            'sum_wallet_bank' => $sum_wallet_bank,
            'sum_wallet_deposit' => $sum_wallet_deposit,
            'sum_wallet_payment' => $sum_wallet_payment,
            'sum_wallet_phone' => $sum_wallet_phone,
            'sum_wallet_xbet_depot' => $sum_wallet_xbet_depot,
            'sum_wallet_xbet_retrait' => $sum_wallet_xbet_retrait,
            'sum_wallet_Inter' => $sum_wallet_Inter,
            'sum_wallet_Inter_received' => $sum_wallet_Inter_received,
            'sum_wallet_wave' => $sum_wallet_wave,
            'sum_wallet_transaction_received' => $sum_wallet_transaction_received
        ];
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

    public function getReloadAmountTotal($walletModel, $id)
    {


        $total_get = $walletModel::where('wallet_id',  $id)
            ->whereStatus('SUCCESS')
            ->paginate(20);

        return $total_get;
    }


    public function getTransactionsAllTotal($walletModel, $wallet_transaction, $wallet_order_by)
    {


        return $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->where('wallets.country_id', sabdy()->country->id)
            ->orderBy($wallet_order_by, 'desc')->paginate(20);
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

                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
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

                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
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

    public function edit_custumers($id ,Request $request)
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

            $msg =" Modification effectuée avec succès ";

            if ($response['success']) {

                $user = $response['user'];

                $valeur_message = ' Profil ';

                Mail::to($user->email)->send(new NewAdmin('Vous etes désormais ' . $valeur_message . env('APP_NAME') .
                    ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

                $msg = "Modification de profil avec succès";
                $color = 'success';

                return redirect(route('custumers.browse'))->with('color', $color)->with('status', $msg);

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

                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
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

                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
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

                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
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

                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
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

                        Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
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

                        Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
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

                        Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
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
