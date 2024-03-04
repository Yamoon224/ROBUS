<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use App\Models\WithMerchant;
use Illuminate\Http\Request;
use App\Models\Custumers\City;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Models\Custumers\Country;
use App\Models\Wallets\WalletBank;
use Illuminate\Support\Facades\DB;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Http\Services\HistoriqueService;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Wallets\WalletXbet;


class CountrieController extends Controller
{


    protected $historiqueService;

    public function __construct(HistoriqueService $historiqueService)
    {
        $this->historiqueService = $historiqueService;
    }

    // Show Distributer
    public function show_distri_payment(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments =  AdminsCom::join('wallet_payments', 'wallet_payments.id', 'admins_coms.wallet_payment_id')
            ->join('wallets', 'wallets.id', 'wallet_payments.wallet_id')
            ->where('wallets.country_id', $countrie->id)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            // ->groupBy('admins_coms.created_at')
            ->paginate(20);


        $valeur_payments = $this->getCumulsWalletPaymentDistributers($countrie->id);
        $valeur_success = $this->getCountWalletPaymentSuccess($countrie->id);
        $valeur_pending = $this->getCountWalletPaymentPending($countrie->id);
        $valeur_error = $this->getCountWalletPaymentError($countrie->id);

        return view('pages.historicals.under_distributer.browse_paiement_distri', compact('wallet_payments', 'valeur_payments', 'countrie', 'valeur_success', 'valeur_pending', 'valeur_error'));
    }

    public function show_distri_transaction(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments =  AdminsCom::join('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id')
            ->join('wallets', 'wallets.id', 'wallet_transactions.wallet_id')
            ->where('wallets.country_id', $countrie->id)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            // ->groupBy('admins_coms.created_at')
            ->paginate(20);


        $valeur_payments = $this->getCumulsWalletTransactionDistributers($countrie->id);
        $valeur_success = $this->getCountWalletTransactionSuccess($countrie->id);
        $valeur_pending = $this->getCountWalletTransactionPending($countrie->id);
        $valeur_error = $this->getCountWalletTransactionError($countrie->id);
        return view('pages.historicals.under_distributer.browse_transfer_distri', compact('wallet_payments', 'valeur_payments', 'countrie', 'valeur_success', 'valeur_pending', 'valeur_error'));
    }

    public function show_distri_bank(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments =  AdminsCom::join('wallet_banks', 'wallet_banks.id', 'admins_coms.wallet_bank_id')
            ->join('wallets', 'wallets.id', 'wallet_banks.wallet_id')
            ->where('wallets.country_id', $countrie->id)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            // ->groupBy('admins_coms.created_at')
            ->paginate(20);

        $valeur_payments = $this->getCumulsWalletBankDistributers($countrie->id);
        $valeur_success = $this->getCountWalletBankSuccess($countrie->id);
        $valeur_pending = $this->getCountWalletBankPending($countrie->id);
        $valeur_error = $this->getCountWalletBankError($countrie->id);


        return view('pages.historicals.under_distributer.browse_virement_distri', compact('wallet_payments', 'valeur_payments', 'countrie', 'valeur_success', 'valeur_pending', 'valeur_error'));
    }

    public function show_distri_deposit(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments =  AdminsCom::join('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id')
            ->join('wallets', 'wallets.id', 'wallet_deposits.wallet_id')
            ->where('wallets.country_id', $countrie->id)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            // ->groupBy('admins_coms.created_at')
            ->paginate(20);

        $valeur_success = $this->getCountWalletDepositSuccess($countrie->id);
        $valeur_pending = $this->getCountWalletDepositPending($countrie->id);
        $valeur_error = $this->getCountWalletDepositError($countrie->id);


        $valeur_payments = $this->getCumulsWalletDepositDistributers($countrie->id);

        return view('pages.historicals.under_distributer.browse_carte_distri', compact('wallet_payments', 'valeur_payments', 'countrie', 'valeur_success', 'valeur_pending', 'valeur_error'));
    }

    public function show_distri_reload(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments =  AdminsCom::join('wallet_reloads', 'wallet_reloads.id', 'admins_coms.wallet_reload_id')
            ->join('wallets', 'wallets.id', 'wallet_reloads.wallet_id')
            ->where('wallets.country_id', $countrie->id)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            // ->groupBy('admins_coms.created_at')
            ->paginate(20);

        $valeur_success = $this->getCountWalletReloadSuccess($countrie->id);
        $valeur_pending = $this->getCountWalletReloadPending($countrie->id);
        $valeur_error = $this->getCountWalletReloadError($countrie->id);

        $valeur_payments = $this->getCumulsWalletReloadDistributers($countrie->id);

        return view('pages.historicals.under_distributer.browse_paiement_distri', compact('wallet_payments', 'valeur_payments', 'countrie', 'valeur_success', 'valeur_pending', 'valeur_error'));
    }

    public function show_distri_inter(Request $request)
    {
        // $countrie = Country::where('name', $code)->first();

        $wallet_payments =  AdminsCom::join('wallet_inters', 'wallet_inters.id', 'admins_coms.wallet_inter_id')
            ->join('wallets', 'wallets.id', 'wallet_inters.wallet_id')
            // ->where('wallets.country_id', $countrie)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            // ->groupBy('admins_coms.created_at')
            ->paginate(20);



        $valeur_payments = $this->getCumulsWalletInterDistributers();
        $valeur_success = $this->getCountWalletInterSuccess();
        $valeur_pending = $this->getCountWalletInterPending();
        $valeur_error = $this->getCountWalletInterError();


        return view('pages.historicals.under_distributer.browse_inter_distri', compact('wallet_payments', 'valeur_payments', 'countrie', 'valeur_success', 'valeur_pending', 'valeur_error'));
    }

    public function show_distri_phone(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments =   AdminsCom::join('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_phone_credit_id')
            ->join('wallets', 'wallets.id', 'wallet_phone_credits.wallet_id')
            ->where('wallets.country_id', $countrie->id)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->groupBy('admins_coms.created_at')
            ->paginate(20);


        $valeur_payments = $this->getCumulsWalletPhoneDistributers($countrie->id);
        $valeur_success = $this->getCountWalletPhoneSuccess($countrie->id);
        $valeur_pending = $this->getCountWalletPhonePending($countrie->id);
        $valeur_error = $this->getCountWalletPhoneError($countrie->id);

        return view('pages.historicals.under_distributer.browse_phone_distri', compact('wallet_payments', 'valeur_payments', 'countrie', 'valeur_success', 'valeur_pending', 'valeur_error'));
    }
    // Show Under distributer
    public function show_under_payment(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments = WalletPayment::join('wallets', 'wallets.id', 'wallet_payments.wallet_id')
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie->id)
            ->where('wallet_payments.commissions', '!=', 0)
            ->where('wallet_payments.status', 'SUCCESS')
            ->select('wallet_payments.*')
            ->limit(5)
            ->groupBy('wallet_payments.created_at')
            ->paginate(20);


        $valeur_payments = $this->getCumulsWalletPayments($countrie->id);

        return view('pages.historicals.under_distributer.browse_paiement', compact('wallet_payments', 'valeur_payments', 'countrie'));
    }

    public function show_under_transaction(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments =  WalletTransaction::join('wallets', 'wallets.id', 'wallet_transactions.wallet_id')
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie->id)
            ->where('wallet_transactions.commissions', '!=', 0)
            ->where('wallet_transactions.status', 'SUCCESS')
            ->select('wallet_transactions.*')
            ->groupBy('wallet_transactions.created_at')
            ->paginate(20);


        $valeur_payments = $this->getCumulsWalletTransactions($countrie->id);

        return view('pages.historicals.under_distributer.browse_transfer', compact('wallet_payments', 'valeur_payments', 'countrie'));
    }

    public function show_under_bank(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments = WalletBank::join('wallets', 'wallets.id', 'wallet_banks.wallet_id')
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie->id)
            ->where('wallet_banks.commissions', '!=', 0)
            ->select('wallet_banks.*')
            ->where('wallet_banks.status', 'SUCCESS')
            ->groupBy('wallet_banks.created_at')
            ->limit(5)
            ->paginate(20);


        $valeur_payments = $this->getCumulsWalletBanks($countrie->id);

        return view('pages.historicals.under_distributer.browse_virement', compact('wallet_payments', 'valeur_payments', 'countrie'));
    }

    public function show_under_deposit(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments =   walletDeposit::join('wallets', 'wallets.id', 'wallet_deposits.wallet_id')
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie->id)
            ->where('wallet_deposits.commissions', '!=', 0)
            ->where('wallet_deposits.status', 'SUCCESS')
            ->select('wallet_deposits.*')
            ->groupBy('wallet_deposits.created_at')
            ->paginate(20);


        $valeur_payments = $this->getCumulsWalletDeposits($countrie->id);

        return view('pages.historicals.under_distributer.browse_carte', compact('wallet_payments', 'valeur_payments', 'countrie'));
    }

    public function show_under_reload(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments = WalletReload::join('wallets', 'wallets.id', 'wallet_reloads.wallet_id')
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie->id)
            ->where('wallet_reloads.commissions', '!=', 0)
            ->where('wallet_reloads.status', 'SUCCESS')
            ->select('wallet_reloads.*')
            ->groupBy('wallet_reloads.created_at')
            ->paginate(20);


        $valeur_payments = $this->getCumulsWalletReloads($countrie->id);

        return view('pages.historicals.under_distributer.browse_paiement', compact('wallet_payments', 'valeur_payments', 'countrie'));
    }

    public function show_under_inter(Request $request, String $code)
    {

        $countrie = Country::where('name', $code)->first();

        $wallet_payments =  WalletInter::join('wallets', 'wallets.id', 'wallet_inters.wallet_id')
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie->id)
            ->where('wallet_inters.commissions', '!=', 0)
            ->where('wallet_inters.status', 'SUCCESS')
            ->select('wallet_inters.*')
            ->groupBy('wallet_inters.created_at')
            ->paginate(20);


        $valeur_payments = $this->getCumulsWalletInters($countrie->id);

        return view('pages.historicals.under_distributer.browse_inter', compact('wallet_payments', 'valeur_payments'));
    }

    public function show_under_phone(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments =  WalletPhoneCredit::join('wallets', 'wallets.id', 'wallet_phone_credits.wallet_id')
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie->id)
            ->where('wallet_phone_credits.commissions', '!=', 0)
            ->where('wallet_phone_credits.status', 'SUCCESS')
            ->select('wallet_phone_credits.*')
            ->groupBy('wallet_phone_credits.created_at')
            ->paginate(20);


        $valeur_payments = $this->getCumulsWalletPhones($countrie->id);

        return view('pages.historicals.under_distributer.browse_phone', compact('wallet_payments', 'valeur_payments', 'countrie'));
    }
    public function currencies()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-devises')) {
            abort(403);
        }
        return view('pages.countries.currencies');
    }

    public function browse()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-pays')) {
            abort(403);
        }

        return view('pages.countries.browse');
    }

    public function cities()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-pays')) {
            abort(403);
        }
        return view('pages.countries.cities');
    }

    public function countrie_cities($id)
    {
        return City::whereCountryId($id)
            ->where('status', true)
            ->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function Show(string $code, Request $request)
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-pays')) {
            abort(403);
        }

        $users_status = null;

        $countrie = Country::whereName($code)->first();
        // Amount transactions days
        $sum_payment_todays =  $this->historiqueService->getTodayTransactionAmountCountrieTotal(WalletPayment::class, 'wallet_payments.wallet_id', $countrie->id, 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount');
        $sum_transaction_todays =  $this->historiqueService->getTodayTransactionAmountCountrieTotal(WalletTransaction::class, 'wallet_transactions.wallet_id' , $countrie->id, 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount');
        $sum_reload_todays =  $this->historiqueService->getTodayReloadAmountTotal(WalletReload::class);
        $sum_phoneCredit_todays =  $this->historiqueService->getTodayTransactionAmountCountrieTotal(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', $countrie->id , 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by',  'wallet_phone_credits.status',  'wallet_phone_credits.amount');
        $sum_inter_todays =  $this->historiqueService->getTodayTransactionAmountCountrieTotal(WalletInter::class, 'wallet_inters.wallet_id', $countrie->id, 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount');
        $sum_bank_todays =  $this->historiqueService->getTodayTransactionAmountCountrieTotal(WalletBank::class, 'wallet_banks.wallet_id', $countrie->id, 'wallet_banks.created_at', 'wallet_banks.updated_by',  'wallet_banks.status',  'wallet_banks.amount');
        $sum_deposit_todays =  $this->historiqueService->getTodayTransactionAmountCountrieTotal(WalletDeposit::class, 'wallet_deposits.wallet_id', $countrie->id, 'wallet_deposits.created_at', 'wallet_deposits.updated_by',  'wallet_deposits.status',  'wallet_deposits.amount');
        $xbet_amount =  $this->historiqueService->getTodayTransactionAmountCountrieTotal(WalletXbet::class, 'wallet_xbets.wallet_id', $countrie->id, 'wallet_xbets.created_at', 'wallet_xbets.updated_by',  'wallet_xbets.status',  'wallet_xbets.amount');

        // Amount transactions month
        $sum_payment_month =  $this->historiqueService->getDaysTransactionCountrie(WalletPayment::class, 'wallet_payments.wallet_id' , $countrie->id, 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount');
        $sum_transaction_month =  $this->historiqueService->getDaysTransactionCountrie(WalletTransaction::class, 'wallet_transactions.wallet_id', $countrie->id, 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount');
        $sum_reload_month =  $this->historiqueService->getDaysReloadAmountTotal(WalletReload::class);
        $sum_phoneCredit_month =  $this->historiqueService->getDaysTransactionCountrie(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', $countrie->id, 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by',  'wallet_phone_credits.status',  'wallet_phone_credits.amount');
        $sum_inter_month =  $this->historiqueService->getDaysTransactionCountrie(WalletInter::class, 'wallet_inters.wallet_id', $countrie->id, 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount');
        $sum_bank_month =  $this->historiqueService->getDaysTransactionCountrie(WalletBank::class, 'wallet_banks.wallet_id', $countrie->id, 'wallet_banks.created_at', 'wallet_banks.updated_by',  'wallet_banks.status',  'wallet_banks.amount');
        $sum_deposit_month =  $this->historiqueService->getDaysTransactionCountrie(WalletDeposit::class, 'wallet_deposits.wallet_id', $countrie->id, 'wallet_deposits.created_at', 'wallet_deposits.updated_by',  'wallet_deposits.status',  'wallet_deposits.amount');
        $xbet_amount_month =  $this->historiqueService->getDaysTransactionCountrie(WalletXbet::class, 'wallet_xbets.wallet_id', $countrie->id, 'wallet_xbets.created_at', 'wallet_xbets.updated_by',  'wallet_xbets.status',  'wallet_xbets.amount');
        // Infos uses
 

        $custumers_countries = SidUser::whereIsCustumers(true)->where('country_id', $countrie->id)->get();
        $users_distri_Countries = SidUser::Where('country_id', $countrie->id)->Where('is_distributer', true)->get();
        $user_company = SidUser::where('country_id', $countrie->id)->where('is_company', true)->get();
        $user_master = SidUser::where('country_id', $countrie->id)->where('is_master', true)->get();
        $user_supervisor = SidUser::where('country_id', $countrie->id)->where('is_supervisor', true)->get();
        $user_comzone = SidUser::where('country_id', $countrie->id)->where('is_com_zone', true)->get();
        $user_under_distributors = SidUser::where('country_id', $countrie->id)->where('is_under_distributer', true)->get();

        // Counts Users
        $custumers_countrie_count = SidUser::whereIsCustumers(true)->where('country_id', $countrie->id)->count();
        $users_distri_Countrie_count = SidUser::Where('country_id', $countrie->id)->Where('is_distributer', true)->count();
        $user_company_count = SidUser::where('country_id', $countrie->id)->where('is_company', true)->count();
        $user_master_count = SidUser::where('country_id', $countrie->id)->where('is_master', true)->count();
        $user_supervisor_count = SidUser::where('country_id', $countrie->id)->where('is_supervisor', true)->count();
        $user_comzone_count = SidUser::where('country_id', $countrie->id)->where('is_com_zone', true)->count();
        $user_under_distributor_count = SidUser::where('country_id', $countrie->id)->where('is_under_distributer', true)->count();


        $sum_wallet_custumers = $this->historiqueService->sumwalletCountry($countrie->id, 'sid_users.is_custumers');

        $sum_wallet_supervisors = $this->historiqueService->sumwalletCountry($countrie->id, 'sid_users.is_supervisor');

        $sum_wallet_companies = $this->historiqueService->sumwalletCountry($countrie->id, 'sid_users.is_company');

        $sum_wallet_under_distributors = $this->historiqueService->sumwalletCountry($countrie->id, 'sid_users.is_under_distributer');

        $sum_wallet_distributors = $this->historiqueService->sumwalletCountry($countrie->id, 'sid_users.is_distributer');

        $sum_wallet_masters = $this->historiqueService->sumwalletCountry($countrie->id, 'sid_users.is_master');

        $sum_wallet_comZones = $this->historiqueService->sumwalletCountry($countrie->id, 'sid_users.is_com_zone');

        $countrie = Country::where('name', $code)->first();

        $get_users =    SidUser::Where('country_id', $countrie->id)->paginate(20);

      
        $usersCountry = $countrie->currencie->name ?? null;


    

        return view('pages.countries.show', compact(
            'get_users',
            'countrie',
            'usersCountry',     
            // Users
            'custumers_countries',
            'users_distri_Countries',
            'user_company',
            'user_master',
            'user_supervisor',
            'user_comzone',
            'user_under_distributors',

            // Users counts
            'custumers_countrie_count',
            'users_distri_Countrie_count',
            'user_company_count',
            'user_master_count',
            'user_supervisor',
            'user_supervisor_count',
            'user_comzone_count',
            'user_under_distributor_count',

            // Wallet Sum
            'sum_wallet_custumers',
            'sum_wallet_supervisors',
            'sum_wallet_companies',
            'sum_wallet_under_distributors',
            'sum_wallet_distributors',
            'sum_wallet_masters',
            'sum_wallet_comZones',
            'users_status',
            'sum_bank_month',
            'sum_deposit_month',
            'sum_payment_month',
            'sum_reload_month',
            'sum_transaction_month',
            'sum_inter_month',
            'sum_phoneCredit_month',
            'sum_bank_todays',
            'sum_deposit_todays',
            'sum_payment_todays',
            'sum_reload_todays',
            'sum_transaction_todays',
            'sum_inter_todays',
            'sum_phoneCredit_todays',
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function Show_under_distributer(string $code, Request $request)
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-pays')) {
            abort(403);
        }

        $users_status = null;

        if (Auth::guard('web')->user()->wallet->sidUser->is_admin == true ||  Auth::guard('web')->user()->wallet->sidUser->is_supervisor == true) {

            $countrie = Country::where('name', $code)->first();

            $users_distri_Countries = SidUser::Where('country_id', $countrie->id)->Where('is_under_distributer', true)->count();

            $custumers_countries = SidUser::where('is_custumers', true)->where('country_id', $countrie->id)->count();

            $distri_countries = SidUser::where('is_custumers', true)->where('country_id', $countrie->id)->count();


            // Admin
            $sum_bank =  $this->getCumulsWalletBanks($countrie->id);
            $sum_amount_distributer =  $this->historiqueService->getSDistributeurSumCountryAdmin($countrie->id);
            $sum_amount_custumers =  $this->historiqueService->getCustumersSumCountrieAdmin($countrie->id);
            $sum_deposit = $this->getCumulsWalletDeposits($countrie->id);
            $sum_payment = $this->getCumulsWalletPayments($countrie->id);
            $sum_reload = $this->getCumulsWalletReloads($countrie->id);
            $sum_transaction = $this->getCumulsWalletTransactions($countrie->id);
            $sum_inter  = $this->getCumulsWalletInters($countrie->id);
            $sum_phoneCredit = $this->getCumulsWalletPhones($countrie->id);


            $cumul_solde =   $this->getCumulsWalletBanks($countrie->id)  +   $this->getCumulsWalletPayments($countrie->id) + $this->getCumulsWalletDeposits($countrie->id) +   $this->getCumulsWalletReloads($countrie->id) + $this->getCumulsWalletTransactions($countrie->id)
                +    $this->getCumulsWalletInters($countrie->id) +   $this->getCumulsWalletPhones($countrie->id);

            $wallet_banks =   $this->getWalletBanks($countrie->id);
            $wallet_deposits =   $this->getWalletDepostits($countrie->id);
            $wallet_payments =   $this->getWalletPayments($countrie->id);
            $wallet_reloads =   $this->getWalletReloads($countrie->id);
            $wallet_transactions =   $this->getWalletTransactions($countrie->id);
            $wallet_inters =   $this->getWalletInters($countrie->id);
            $wallet_phones =   $this->getWalletPhoneCredits($countrie->id);
            $sum_wallet_drawer = $this->getWalletWithMerchantSumUserDaysProperty($countrie->id);
            $With_drawer_merchant = $this->getWalletWithMerchantSumUserDaygetProperty($countrie->id);

            // Last valeurs
            return view('pages.countries.show_under_distributer', compact(
                'countrie',
                'cumul_solde',
                'sum_bank',
                'sum_deposit',
                'sum_payment',
                'sum_reload',
                'sum_transaction',
                'sum_inter',
                'sum_phoneCredit',
                'users_distri_Countries',
                'distri_countries',
                'custumers_countries',
                'wallet_banks',
                'wallet_deposits',
                'wallet_payments',
                'wallet_reloads',
                'wallet_transactions',
                'wallet_inters',
                'wallet_phones',
                'sum_wallet_drawer',
                'With_drawer_merchant',
                'sum_amount_distributer',
                'sum_amount_custumers'
            ));
        } else {
            abort(403);
        }
    }

    public function Show_distri_distributer(string $code, Request $request)
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-pays')) {
            abort(403);
        }

        $users_status = null;

        if (Auth::guard('web')->user()->wallet->sidUser->is_admin == true ||  Auth::guard('web')->user()->wallet->sidUser->is_supervisor == true) {

            $countrie = Country::where('name', $code)->first();

            $users_distri_Countries = SidUser::Where('country_id', $countrie->id)->Where('is_under_distributer', true)->count();

            $custumers_countries = SidUser::where('is_custumers', true)->where('country_id', $countrie->id)->count();


            $distri_countries = SidUser::where('is_custumers', true)->where('country_id', $countrie->id)->count();



            // Admins
            $sum_deposit = $this->getCumulsWalletDepositDistributers($countrie->id);
            $sum_payment = $this->getCumulsWalletPaymentDistributers($countrie->id);
            $sum_reload = $this->getCumulsWalletReloadDistributers($countrie->id);
            $sum_transaction = $this->getCumulsWalletTransactionDistributers($countrie->id);
            $sum_inter  = $this->getCumulsWalletInterDistributers();
            $sum_phoneCredit = $this->getCumulsWalletPhoneDistributers($countrie->id);
            $sum_bank = $this->getCumulsWalletBankDistributers($countrie->id);



            $cumul_solde =   $this->getCumulsWalletBankDistributers($countrie->id)  +   $this->getCumulsWalletPaymentDistributers($countrie->id) + $this->getCumulsWalletDepositDistributers($countrie->id) +   $this->getCumulsWalletReloadDistributers($countrie->id) + $this->getCumulsWalletTransactionDistributers($countrie->id)
                +    $this->getCumulsWalletInterDistributers() +   $this->getCumulsWalletPhoneDistributers($countrie->id);

            $wallet_banks =   $this->getWalletBankDistributers($countrie->id);
            $wallet_deposits =   $this->getWalletDepositDistributers($countrie->id);
            $wallet_payments =   $this->getWalletPaymentDistributers($countrie->id);
            $wallet_reloads =   $this->getWalletReloadDistributers($countrie->id);
            $wallet_transactions =   $this->getWalletTransactionDistributers($countrie->id);
            $wallet_inters =   $this->getWalletInterDistributers();
            $wallet_phones =   $this->getWalletPhoneCreditDistributers($countrie->id);
            $sum_wallet_drawer = $this->getWalletWithMerchantSumUserProperty($countrie->id);

            // Last valeurs
            return view('pages.countries.show_distri_distributer', compact(
                'countrie',
                'cumul_solde',
                'sum_bank',
                'sum_deposit',
                'sum_payment',
                'sum_reload',
                'sum_transaction',
                'sum_inter',
                'sum_phoneCredit',
                'users_distri_Countries',
                'distri_countries',
                'custumers_countries',
                'wallet_banks',
                'wallet_deposits',
                'wallet_payments',
                'wallet_reloads',
                'wallet_transactions',
                'wallet_inters',
                'wallet_phones',
                'sum_wallet_drawer'
            ));
        } else {
            abort(403);
        }
    }

    public function getWalletWithMerchantSumUserDaysProperty($countrie)
    {


        $data = [];

        $withMerchant = WithMerchant::join('sid_users', 'sid_users.id', 'with_merchants.sid_user_id')
            ->where('sid_users.country_id', $countrie)
            ->where('with_merchants.date_removed', '!=', null)
            ->whereDate('with_merchants.date_removed', Carbon::now())
            ->orderBy('with_merchants.created_at', 'desc')
            ->select('with_merchants.*')
            ->distinct()
            ->get();

        foreach ($withMerchant  as $bank) {
            $data[] = $bank->amount_with_draw;
        }

        return array_sum($data);
    }

    public function getWalletWithMerchantSumUserProperty($countrie)
    {


        $data = [];

        $withMerchant = WithMerchant::join('sid_users', 'sid_users.id', 'with_merchants.sid_user_id')
            ->where('sid_users.country_id', $countrie)
            ->where('with_merchants.date_removed', '!=', null)
            // ->whereDate('with_merchants.date_removed', Carbon::now())
            ->orderBy('with_merchants.created_at', 'desc')
            ->select('with_merchants.*')
            ->distinct()
            ->get();

        foreach ($withMerchant  as $bank) {
            $data[] = $bank->amount_with_draw;
        }

        return array_sum($data);
    }
    public function getWalletWithMerchantSumUserDaygetProperty($countrie)
    {


        $data = [];

        $withMerchant = WithMerchant::join('sid_users', 'sid_users.id', 'with_merchants.sid_user_id')
            ->where('sid_users.country_id', $countrie)
            ->where('with_merchants.date_removed', '!=', null)
            ->whereDate('with_merchants.date_removed', Carbon::now())
            ->orderBy('with_merchants.date_removed', 'desc')
            ->select('with_merchants.*')
            ->limit(5)
            ->distinct()
            ->get();

        return   $withMerchant;
    }
    // Count valeur of Wallets

    public function getCountWalletBankSuccess($countrie)
    {
        $results =  AdminsCom::join('wallet_banks', 'wallet_banks.id', 'admins_coms.wallet_bank_id')
            ->join('wallets', 'wallets.id', 'wallet_banks.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('wallet_banks.status', 'SUCCESS')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletBankPending($countrie)
    {
        $results =  AdminsCom::join('wallet_banks', 'wallet_banks.id', 'admins_coms.wallet_bank_id')
            ->join('wallets', 'wallets.id', 'wallet_banks.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('wallet_banks.status', 'PENDING')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }
    public function getCountWalletBankError($countrie)
    {
        $results =  AdminsCom::join('wallet_banks', 'wallet_banks.id', 'admins_coms.wallet_bank_id')
            ->join('wallets', 'wallets.id', 'wallet_banks.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('wallet_banks.status', 'ERROR')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletPaymentSuccess($countrie)
    {
        $results =  AdminsCom::join('wallet_payments', 'wallet_payments.id', 'admins_coms.wallet_payment_id')
            ->join('wallets', 'wallets.id', 'wallet_payments.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('wallet_payments.status', 'SUCCESS')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletPaymentPending($countrie)
    {
        $results =  AdminsCom::join('wallet_payments', 'wallet_payments.id', 'admins_coms.wallet_payment_id')
            ->join('wallets', 'wallets.id', 'wallet_payments.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('wallet_payments.status', 'PENDING')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }
    public function getCountWalletPaymentError($countrie)
    {
        $results =  AdminsCom::join('wallet_payments', 'wallet_payments.id', 'admins_coms.wallet_payment_id')
            ->join('wallets', 'wallets.id', 'wallet_payments.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('wallet_payments.status', 'ERROR')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletTransactionSuccess($countrie)
    {
        $results =  AdminsCom::join('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id')
            ->join('wallets', 'wallets.id', 'wallet_transactions.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('wallet_transactions.status', 'SUCCESS')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletTransactionPending($countrie)
    {
        $results =  AdminsCom::join('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id')
            ->join('wallets', 'wallets.id', 'wallet_transactions.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('wallet_transactions.status', 'PENDING')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }
    public function getCountWalletTransactionError($countrie)
    {
        $results =  AdminsCom::join('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id')
            ->join('wallets', 'wallets.id', 'wallet_transactions.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('wallet_transactions.status', 'ERROR')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletDepositSuccess($countrie)
    {
        $results =  AdminsCom::join('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id')
            ->join('wallets', 'wallets.id', 'wallet_deposits.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('wallet_deposits.status', 'SUCCESS')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletDepositPending($countrie)
    {
        $results =  AdminsCom::join('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id')
            ->join('wallets', 'wallets.id', 'wallet_deposits.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('wallet_deposits.status', 'PENDING')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }
    public function getCountWalletDepositError($countrie)
    {
        $results =  AdminsCom::join('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id')
            ->join('wallets', 'wallets.id', 'wallet_deposits.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('wallet_deposits.status', 'ERROR')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletReloadSuccess($countrie)
    {
        $results =  AdminsCom::join('wallet_reloads', 'wallet_reloads.id', 'admins_coms.wallet_reload_id')
            ->join('wallets', 'wallets.id', 'wallet_reloads.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('wallet_reloads.status', 'SUCCESS')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletReloadPending($countrie)
    {
        $results =  AdminsCom::join('wallet_reloads', 'wallet_reloads.id', 'admins_coms.wallet_reload_id')
            ->join('wallets', 'wallets.id', 'wallet_reloads.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('wallet_reloads.status', 'PENDING')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }
    public function getCountWalletReloadError($countrie)
    {
        $results =  AdminsCom::join('wallet_reloads', 'wallet_reloads.id', 'admins_coms.wallet_reload_id')
            ->join('wallets', 'wallets.id', 'wallet_reloads.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('wallet_reloads.status', 'ERROR')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletPhoneSuccess($countrie)
    {
        $results =  AdminsCom::join('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_phone_credit_id')
            ->join('wallets', 'wallets.id', 'wallet_phone_credits.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('wallet_phone_credits.status', 'SUCCESS')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletPhonePending($countrie)
    {
        $results =  AdminsCom::join('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_phone_credit_id')
            ->join('wallets', 'wallets.id', 'wallet_phone_credits.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('wallet_phone_credits.status', 'PENDING')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }
    public function getCountWalletPhoneError($countrie)
    {
        $results =  AdminsCom::join('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_phone_credit_id')
            ->join('wallets', 'wallets.id', 'wallet_phone_credits.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('wallet_phone_credits.status', 'ERROR')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletInterSuccess()
    {
        $results =  AdminsCom::join('wallet_inters', 'wallet_inters.id', 'admins_coms.wallet_inter_id')
            ->join('wallets', 'wallets.id', 'wallet_inters.wallet_id')
            // ->where('wallets.country_id', $countrie)
            ->where('wallet_inters.status', 'SUCCESS')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletInterPending()
    {
        $results =  AdminsCom::join('wallet_inters', 'wallet_inters.id', 'admins_coms.wallet_inter_id')
            ->join('wallets', 'wallets.id', 'wallet_inters.wallet_id')
            // ->where('wallets.country_id', $countrie)
            ->where('wallet_inters.status', 'PENDING')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }
    public function getCountWalletInterError()
    {
        $results =  AdminsCom::join('wallet_inters', 'wallet_inters.id', 'admins_coms.wallet_inter_id')
            ->join('wallets', 'wallets.id', 'wallet_inters.wallet_id')
            // ->where('wallets.country_id', $countrie)
            ->where('wallet_inters.status', 'ERROR')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }
    // Cumuls valeur distributer 
    public function getCumulsWalletBankDistributers($countrie)
    {
        $data_payement = [];

        $result =  AdminsCom::join('wallet_banks', 'wallet_banks.id', 'admins_coms.wallet_bank_id')
            ->join('wallets', 'wallets.id', 'wallet_banks.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select(DB::raw('sum(admins_coms.amount) as cumul_amount_chiffre'))
            ->groupBy('admins_coms.created_at')
            ->get();


        foreach ($result as $sumPay) {
            $data_payement[] = $sumPay->cumul_amount_chiffre;
        }

        return  array_sum($data_payement);
    }

    public function getCumulsWalletPaymentDistributers($countrie)
    {
        $data_payement = [];


        $result =  AdminsCom::join('wallet_payments', 'wallet_payments.id', 'admins_coms.wallet_payment_id')
            ->join('wallets', 'wallets.id', 'wallet_payments.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select(DB::raw('sum(admins_coms.amount) as cumul_amount_chiffre'))
            ->groupBy('admins_coms.created_at')
            ->get();


        foreach ($result as $sumPay) {
            $data_payement[] = $sumPay->cumul_amount_chiffre;
        }

        return  array_sum($data_payement);
    }

    public function getCumulsWalletDepositDistributers($countrie)
    {
        $data_payement = [];

        $result =  AdminsCom::join('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id')
            ->join('wallets', 'wallets.id', 'wallet_deposits.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select(DB::raw('sum(admins_coms.amount) as cumul_amount_chiffre'))
            ->groupBy('admins_coms.created_at')
            ->get();

        foreach ($result as $sumPay) {
            $data_payement[] = $sumPay->cumul_amount_chiffre;
        }

        return  array_sum($data_payement);
    }

    public function getCumulsWalletReloadDistributers($countrie)
    {
        $data_payement = [];


        $result =  AdminsCom::join('wallet_reloads', 'wallet_reloads.id', 'admins_coms.wallet_reload_id')
            ->join('wallets', 'wallets.id', 'wallet_reloads.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select(DB::raw('sum(admins_coms.amount) as cumul_amount_chiffre'))
            ->groupBy('admins_coms.created_at')
            ->get();


        foreach ($result as $sumPay) {
            $data_payement[] = $sumPay->cumul_amount_chiffre;
        }

        return  array_sum($data_payement);
    }

    public function getCumulsWalletTransactionDistributers($countrie)
    {
        $data_payement = [];


        $result =  AdminsCom::join('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id')
            ->join('wallets', 'wallets.id', 'wallet_transactions.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select(DB::raw('sum(admins_coms.amount) as cumul_amount_chiffre'))
            ->groupBy('admins_coms.created_at')
            ->get();


        foreach ($result as $sumPay) {
            $data_payement[] = $sumPay->cumul_amount_chiffre;
        }

        return  array_sum($data_payement);
    }

    public function getCumulsWalletInterDistributers()
    {
        $data_payement = [];

        $result =  AdminsCom::join('wallet_inters', 'wallet_inters.id', 'admins_coms.wallet_inter_id')
            ->join('wallets', 'wallets.id', 'wallet_inters.wallet_id')
            // ->where('wallets.country_id', $countrie)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select(DB::raw('sum(admins_coms.amount) as cumul_amount_chiffre'))
            ->groupBy('admins_coms.created_at')
            ->get();


        foreach ($result as $sumPay) {
            $data_payement[] = $sumPay->cumul_amount_chiffre;
        }

        return  array_sum($data_payement);
    }

    public function getCumulsWalletPhoneDistributers($countrie)
    {
        $data_payement = [];

        $result =  AdminsCom::join('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_phone_credit_id')
            ->join('wallets', 'wallets.id', 'wallet_phone_credits.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select(DB::raw('sum(admins_coms.amount) as cumul_amount_chiffre'))
            ->groupBy('admins_coms.created_at')
            ->get();

        foreach ($result as $sumPay) {
            $data_payement[] = $sumPay->cumul_amount_chiffre;
        }

        return  array_sum($data_payement);
    }

    // Account 5 valeur distributer 
    public function getWalletBankDistributers($countrie)
    {

        $result =  AdminsCom::join('wallet_banks', 'wallet_banks.id', 'admins_coms.wallet_bank_id')
            ->join('wallets', 'wallets.id', 'wallet_banks.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->groupBy('admins_coms.created_at')
            ->limit(5)
            ->get();


        return   $result;
    }

    public function getWalletPaymentDistributers($countrie)
    {

        $result =  AdminsCom::join('wallet_payments', 'wallet_payments.id', 'admins_coms.wallet_payment_id')
            ->join('wallets', 'wallets.id', 'wallet_payments.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->groupBy('admins_coms.created_at')
            ->limit(5)
            ->get();

        return   $result;
    }

    public function getWalletDepositDistributers($countrie)
    {

        $result =  AdminsCom::join('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id')
            ->join('wallets', 'wallets.id', 'wallet_deposits.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->groupBy('admins_coms.created_at')
            ->limit(5)
            ->get();

        return  $result;
    }

    public function getWalletReloadDistributers($countrie)
    {

        $result =  AdminsCom::join('wallet_reloads', 'wallet_reloads.id', 'admins_coms.wallet_reload_id')
            ->join('wallets', 'wallets.id', 'wallet_reloads.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->groupBy('admins_coms.created_at')
            ->limit(5)
            ->get();
        return  $result;
    }

    public function getWalletTransactionDistributers($countrie)
    {

        $result =  AdminsCom::join('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id')
            ->join('wallets', 'wallets.id', 'wallet_transactions.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->groupBy('admins_coms.created_at')
            ->limit(5)
            ->get();

        return  $result;
    }

    public function getWalletInterDistributers()
    {

        $result =  AdminsCom::join('wallet_inters', 'wallet_inters.id', 'admins_coms.wallet_inter_id')
            ->join('wallets', 'wallets.id', 'wallet_inters.wallet_id')
            // ->where('wallets.country_id', $countrie)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->groupBy('admins_coms.created_at')
            ->limit(5)
            ->get();

        return  $result;
    }

    public function getWalletPhoneCreditDistributers($countrie)
    {

        $result =  AdminsCom::join('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_phone_credit_id')
            ->join('wallets', 'wallets.id', 'wallet_phone_credits.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->groupBy('admins_coms.created_at')
            ->limit(5)
            ->get();

        return  $result;
    }

    // Cumuls valeurs Under distributer

    public function getCumulsWalletBanks($countrie)
    {
        $data_payement = [];

        $result =  WalletBank::join('wallets', 'wallets.id', 'wallet_banks.wallet_id')
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie)
            ->where('wallet_banks.commissions', '!=', 0)
            ->select(DB::raw('sum(wallet_banks.commissions) as cumul_amount_chiffre'))
            ->where('wallet_banks.status', 'SUCCESS')
            ->groupBy('wallet_banks.created_at')
            ->get();


        foreach ($result as $sumPay) {
            $data_payement[] = $sumPay->cumul_amount_chiffre;
        }

        return  array_sum($data_payement);
    }
    public function getCumulsWalletPayments($countrie)
    {
        $data_payement = [];


        $result =  WalletPayment::join('wallets', 'wallets.id', 'wallet_payments.wallet_id')
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie)
            ->where('wallet_payments.commissions', '!=', 0)
            ->where('wallet_payments.status', 'SUCCESS')
            ->select(DB::raw('sum(wallet_payments.commissions) as cumul_amount_chiffre'))
            ->groupBy('wallet_payments.created_at')
            ->get();


        foreach ($result as $sumPay) {
            $data_payement[] = $sumPay->cumul_amount_chiffre;
        }

        return  array_sum($data_payement);
    }

    public function getCumulsWalletDeposits($countrie)
    {
        $data_payement = [];


        $result = walletDeposit::join('wallets', 'wallets.id', 'wallet_deposits.wallet_id')
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie)
            ->where('wallet_deposits.commissions', '!=', 0)
            ->where('wallet_deposits.status', 'SUCCESS')
            ->select(DB::raw('sum(wallet_deposits.commissions) as cumul_amount_chiffre'))
            ->groupBy('wallet_deposits.created_at')
            ->get();


        foreach ($result as $sumPay) {
            $data_payement[] = $sumPay->cumul_amount_chiffre;
        }

        return  array_sum($data_payement);
    }

    public function getCumulsWalletReloads($countrie)
    {
        $data_payement = [];


        $result = WalletReload::join('wallets', 'wallets.id', 'wallet_reloads.wallet_id')
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie)
            ->where('wallet_reloads.status', 'SUCCESS')
            ->where('wallet_reloads.commissions', '!=', 0)
            ->select(DB::raw('sum(wallet_reloads.commissions) as cumul_amount_chiffre'))
            ->groupBy('wallet_reloads.created_at')
            ->get();


        foreach ($result as $sumPay) {
            $data_payement[] = $sumPay->cumul_amount_chiffre;
        }

        return  array_sum($data_payement);
    }

    public function getCumulsWalletTransactions($countrie)
    {
        $data_payement = [];


        $result = WalletTransaction::join('wallets', 'wallets.id', 'wallet_transactions.wallet_id')
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie)
            ->where('wallet_transactions.status', 'SUCCESS')
            ->where('wallet_transactions.commissions', '!=', 0)
            ->select(DB::raw('sum(wallet_transactions.commissions) as cumul_amount_chiffre'))
            ->groupBy('wallet_transactions.created_at')
            ->get();

        foreach ($result as $sumPay) {
            $data_payement[] = $sumPay->cumul_amount_chiffre;
        }

        return  array_sum($data_payement);
    }

    public function getCumulsWalletInters($countrie)
    {
        $data_payement = [];


        $result = WalletInter::join('wallets', 'wallets.id', 'wallet_inters.wallet_id')
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie)
            ->where('wallet_inters.commissions', '!=', 0)
            ->where('wallet_inters.status', 'SUCCESS')
            ->select(DB::raw('sum(wallet_inters.commissions) as cumul_amount_chiffre'))
            ->groupBy('wallet_inters.created_at')
            ->get();


        foreach ($result as $sumPay) {
            $data_payement[] = $sumPay->cumul_amount_chiffre;
        }

        return  array_sum($data_payement);
    }

    public function getCumulsWalletPhones($countrie)
    {
        $data_payement = [];


        $result = WalletPhoneCredit::join('wallets', 'wallets.id', 'wallet_phone_credits.wallet_id')
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie)
            ->where('wallet_phone_credits.status', 'SUCCESS')
            ->where('wallet_phone_credits.commissions', '!=', 0)
            ->select(DB::raw('sum(wallet_phone_credits.commissions) as cumul_amount_chiffre'))
            ->groupBy('wallet_phone_credits.created_at')
            ->get();


        foreach ($result as $sumPay) {
            $data_payement[] = $sumPay->cumul_amount_chiffre;
        }

        return  array_sum($data_payement);
    }


    public function getWalletBanks($countrie)
    {


        return    $results_banks = WalletBank::join('wallets', 'wallets.id', 'wallet_banks.wallet_id')
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie)
            ->where('wallet_banks.commissions', '!=', 0)
            ->select('wallet_banks.*')
            ->where('wallet_banks.status', 'SUCCESS')
            ->groupBy('wallet_banks.created_at')
            ->limit(5)
            ->get();
    }

    public function getWalletDepostits($countrie)
    {


        return    walletDeposit::join('wallets', 'wallets.id', 'wallet_deposits.wallet_id')
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie)
            ->where('wallet_deposits.commissions', '!=', 0)
            ->where('wallet_deposits.status', 'SUCCESS')
            ->select('wallet_deposits.*')
            ->limit(5)
            ->groupBy('wallet_deposits.created_at')
            ->get();
    }

    public function getWalletPayments($countrie)
    {


        return    WalletPayment::join('wallets', 'wallets.id', 'wallet_payments.wallet_id')
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie)
            ->where('wallet_payments.commissions', '!=', 0)
            ->where('wallet_payments.status', 'SUCCESS')
            ->select('wallet_payments.*')
            ->limit(5)
            ->groupBy('wallet_payments.created_at')
            ->get();
    }

    public function getWalletReloads($countrie)
    {


        return   WalletReload::join('wallets', 'wallets.id', 'wallet_reloads.wallet_id')
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie)
            ->where('wallet_reloads.commissions', '!=', 0)
            ->where('wallet_reloads.status', 'SUCCESS')
            ->select('wallet_reloads.*')
            ->limit(5)
            ->groupBy('wallet_reloads.created_at')
            ->get();
    }

    public function getWalletTransactions($countrie)
    {


        return   WalletTransaction::join('wallets', 'wallets.id', 'wallet_transactions.wallet_id')
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie)
            ->where('wallet_transactions.commissions', '!=', 0)
            ->where('wallet_transactions.status', 'SUCCESS')
            ->select('wallet_transactions.*')
            ->limit(5)
            ->groupBy('wallet_transactions.created_at')
            ->get();
    }

    public function getWalletInters($countrie)
    {


        return   WalletInter::join('wallets', 'wallets.id', 'wallet_inters.wallet_id')
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie)
            ->where('wallet_inters.commissions', '!=', 0)
            ->where('wallet_inters.status', 'SUCCESS')
            ->select('wallet_inters.*')
            ->limit(5)
            ->groupBy('wallet_inters.created_at')
            ->get();
    }


    public function getWalletPhoneCredits($countrie)
    {


        return   WalletPhoneCredit::join('wallets', 'wallets.id', 'wallet_phone_credits.wallet_id')
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie)
            ->where('wallet_phone_credits.commissions', '!=', 0)
            ->where('wallet_phone_credits.status', 'SUCCESS')
            ->select('wallet_phone_credits.*')
            ->limit(5)
            ->groupBy('wallet_phone_credits.created_at')
            ->get();
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
}
