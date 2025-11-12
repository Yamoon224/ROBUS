<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use App\Models\WithMerchant;
use Illuminate\Http\Request;
use App\Models\Custumers\City;
use Illuminate\Support\Carbon;
use App\Models\Custumers\Country;
use App\Models\Wallets\WalletBank;
use Illuminate\Support\Facades\DB;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPush;
use App\Models\Wallets\WalletDjamo;
use App\Models\Wallets\WalletTresortMoney;
use App\Models\Wallets\WalletVersement;
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

    public function getCumulsWalletAdminDistributers($wallet_transactions, $wallet_transaction_id, $wallet_transaction_type, $wallet_id, $countrie)
    {

        return  AdminsCom::join($wallet_transactions, $wallet_transaction_id, $wallet_transaction_type)
            ->join('wallets', 'wallets.id', $wallet_id)
            ->where('wallets.country_id', $countrie)
            ->where('admins_coms.is_web', false)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->paginate(20);
    }


    public function getCumulsWalletAdminVisitorsDistributers($wallet_transactions, $wallet_transaction_id, $wallet_transaction_type)
    {

        return  AdminsCom::join($wallet_transactions, $wallet_transaction_id, $wallet_transaction_type)
            ->where('admins_coms.is_web', true)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->paginate(20);
    }
    // Show Distributer Mobile
    public function show_distri_payment(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments = $this->getCumulsWalletAdminDistributers('wallet_payments', 'wallet_payments.id', 'admins_coms.wallet_payment_id', 'wallet_payments.wallet_id', $countrie->id);

        // Admin
        $valeur_payments = $this->getCumulsWalletPaymentDistributers($countrie->id);

        $valeur_success =  $this->historiqueService->getCountWalletCountSuccess('wallet_payments', 'wallet_payments.id', 'admins_coms.wallet_payment_id', 'wallet_payments.wallet_id', $countrie->id, 'wallet_payments.status', 'SUCCESS');

        $valeur_pending =  $this->historiqueService->getCountWalletCountSuccess('wallet_payments', 'wallet_payments.id', 'admins_coms.wallet_payment_id', 'wallet_payments.wallet_id', $countrie->id, 'wallet_payments.status', 'PENDING');

        $valeur_error =  $this->historiqueService->getCountWalletCountSuccess('wallet_payments', 'wallet_payments.id', 'admins_coms.wallet_payment_id', 'wallet_payments.wallet_id', $countrie->id, 'wallet_payments.status', 'ERROR');

        return view('pages.historicals.under_distributer.browse_paiement_distri', compact('wallet_payments', 'valeur_payments', 'countrie', 'valeur_success', 'valeur_pending', 'valeur_error'));
    }

    public function show_distri_transaction(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments = $this->getCumulsWalletAdminDistributers('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id', 'wallet_transactions.wallet_id', $countrie->id);

        $valeur_payments = $this->getCumulsWalletTransactionDistributers($countrie->id);

        $valeur_success =  $this->historiqueService->getCountWalletCountSuccess('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id', 'wallet_transactions.wallet_id', $countrie->id, 'wallet_transactions.status', 'SUCCESS');

        $valeur_pending =  $this->historiqueService->getCountWalletCountSuccess('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id', 'wallet_transactions.wallet_id', $countrie->id, 'wallet_transactions.status', 'PENDING');

        $valeur_error =  $this->historiqueService->getCountWalletCountSuccess('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id', 'wallet_transactions.wallet_id', $countrie->id, 'wallet_transactions.status', 'ERROR');

        return view('pages.historicals.under_distributer.browse_transfer_distri', compact('wallet_payments', 'valeur_payments', 'countrie', 'valeur_success', 'valeur_pending', 'valeur_error'));
    }

    public function show_distri_bank(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments = $this->getCumulsWalletAdminDistributers('wallet_banks', 'wallet_banks.id', 'admins_coms.wallet_bank_id', 'wallet_banks.wallet_id', $countrie->id);

        $valeur_payments = $this->getCumulsWalletBankDistributers($countrie->id);

        $valeur_success =  $this->historiqueService->getCountWalletCountSuccess('wallet_banks', 'wallet_banks.id', 'admins_coms.wallet_bank_id', 'wallet_banks.wallet_id', $countrie->id, 'wallet_banks.status', 'SUCCESS');

        $valeur_pending =  $this->historiqueService->getCountWalletCountSuccess('wallet_banks', 'wallet_banks.id', 'admins_coms.wallet_bank_id', 'wallet_banks.wallet_id', $countrie->id, 'wallet_banks.status', 'PENDING');

        $valeur_error =  $this->historiqueService->getCountWalletCountSuccess('wallet_banks', 'wallet_banks.id', 'admins_coms.wallet_bank_id', 'wallet_banks.wallet_id', $countrie->id, 'wallet_banks.status', 'ERROR');


        return view('pages.historicals.under_distributer.browse_virement_distri', compact('wallet_payments', 'valeur_payments', 'countrie', 'valeur_success', 'valeur_pending', 'valeur_error'));
    }

    public function show_distri_deposit(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments = $this->getCumulsWalletAdminDistributers('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id', 'wallet_deposits.wallet_id', $countrie->id);

        $valeur_success =  $this->historiqueService->getCountWalletCountSuccess('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id', 'wallet_deposits.wallet_id', $countrie->id, 'wallet_deposits.status', 'SUCCESS');

        $valeur_pending =  $this->historiqueService->getCountWalletCountSuccess('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id', 'wallet_deposits.wallet_id', $countrie->id, 'wallet_deposits.status', 'PENDING');

        $valeur_error =  $this->historiqueService->getCountWalletCountSuccess('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id', 'wallet_deposits.wallet_id', $countrie->id, 'wallet_deposits.status', 'ERROR');

        $valeur_payments = $this->getCumulsWalletDepositDistributers($countrie->id);

        return view('pages.historicals.under_distributer.browse_carte_distri', compact('wallet_payments', 'valeur_payments', 'countrie', 'valeur_success', 'valeur_pending', 'valeur_error'));
    }

    public function show_distri_reload(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments = $this->getCumulsWalletAdminDistributers('wallet_reloads', 'wallet_reloads.id', 'admins_coms.wallet_reload_id', 'wallet_reloads.wallet_id', $countrie->id);

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
            ->where('admins_coms.is_web', false)
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

        $wallet_payments = $this->getCumulsWalletAdminDistributers('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_phone_credit_id', 'wallet_phone_credits.wallet_id', $countrie->id);

        $valeur_payments = $this->getCumulsWalletPhoneDistributers($countrie->id);

        $valeur_success =  $this->historiqueService->getCountWalletCountSuccess('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_payment_id', 'wallet_phone_credits.wallet_id', $countrie->id, 'wallet_phone_credits.status', 'SUCCESS');

        $valeur_pending =  $this->historiqueService->getCountWalletCountSuccess('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_payment_id', 'wallet_phone_credits.wallet_id', $countrie->id, 'wallet_phone_credits.status', 'PENDING');

        $valeur_error =  $this->historiqueService->getCountWalletCountSuccess('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_payment_id', 'wallet_phone_credits.wallet_id', $countrie->id, 'wallet_phone_credits.status', 'ERROR');

        return view('pages.historicals.under_distributer.browse_phone_distri', compact('wallet_payments', 'valeur_payments', 'countrie', 'valeur_success', 'valeur_pending', 'valeur_error'));
    }


    // Show Distributors Visitors


    public function show_visitors_transaction(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments = $this->getCumulsWalletAdminVisitorsDistributers('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id');

        $valeur_payments = $this->getCumulsWalletTransactionDistributers($countrie->id);

        $valeur_success =  $this->historiqueService->getCountWalletVisitorsCountSuccess('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id', 'wallet_transactions.status', 'SUCCESS');

        $valeur_pending =  $this->historiqueService->getCountWalletVisitorsCountSuccess('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id', 'wallet_transactions.status', 'PENDING');

        $valeur_error =  $this->historiqueService->getCountWalletVisitorsCountSuccess('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id', 'wallet_transactions.status', 'ERROR');

        return view('pages.historicals.under_distributer.visitors.browse_transfer_distri', compact('wallet_payments', 'valeur_payments', 'countrie', 'valeur_success', 'valeur_pending', 'valeur_error'));
    }


    public function show_visitors_deposit(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments = $this->getCumulsWalletAdminVisitorsDistributers('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id');

        $valeur_success =  $this->historiqueService->getCountWalletVisitorsCountSuccess('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id',  'wallet_deposits.status', 'SUCCESS');

        $valeur_pending =  $this->historiqueService->getCountWalletVisitorsCountSuccess('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id',  'wallet_deposits.status', 'PENDING');

        $valeur_error =  $this->historiqueService->getCountWalletVisitorsCountSuccess('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id',  'wallet_deposits.status', 'ERROR');

        $valeur_payments = $this->getCumulsWalletDepositDistributers($countrie->id);

        return view('pages.historicals.under_distributer.visitors.browse_carte_distri', compact('wallet_payments', 'valeur_payments', 'countrie', 'valeur_success', 'valeur_pending', 'valeur_error'));
    }


    public function show_visitors_inter(Request $request)
    {
        // $countrie = Country::where('name', $code)->first();

        $wallet_payments =  AdminsCom::join('wallet_inters', 'wallet_inters.id', 'admins_coms.wallet_inter_id')
            ->join('wallets', 'wallets.id', 'wallet_inters.wallet_id')
            ->where('admins_coms.is_web', true)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            // ->groupBy('admins_coms.created_at')
            ->paginate(20);

        $valeur_payments = $this->getCumulsWalletInterDistributers();
        $valeur_success = $this->getCountWalletInterSuccess();
        $valeur_pending = $this->getCountWalletInterPending();
        $valeur_error = $this->getCountWalletInterError();


        return view('pages.historicals.under_distributer.visitors.browse_inter_distri', compact('wallet_payments', 'valeur_payments',  'valeur_success', 'valeur_pending', 'valeur_error'));
    }

    public function show_visitors_phone(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments = $this->getCumulsWalletAdminVisitorsDistributers('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_phone_credit_id');

        $valeur_payments = $this->getCumulsWalletPhoneDistributers($countrie->id);

        $valeur_success =  $this->historiqueService->getCountWalletVisitorsCountSuccess('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_phone_credit_id', 'wallet_phone_credits.status', 'SUCCESS');

        $valeur_pending =  $this->historiqueService->getCountWalletVisitorsCountSuccess('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_phone_credit_id', 'wallet_phone_credits.status', 'PENDING');

        $valeur_error =  $this->historiqueService->getCountWalletVisitorsCountSuccess('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_phone_credit_id', 'wallet_phone_credits.status', 'ERROR');

        return view('pages.historicals.under_distributer.visitors.browse_phone_distri', compact('wallet_payments', 'valeur_payments', 'countrie', 'valeur_success', 'valeur_pending', 'valeur_error'));
    }


    public function show_visitors_xbets(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments = $this->getCumulsWalletAdminVisitorsDistributers('wallet_xbets', 'wallet_xbets.id', 'admins_coms.wallet_xbet_id');

        $valeur_payments = $this->getCumulsWalletPhoneDistributers($countrie->id);

        $valeur_success =  $this->historiqueService->getCountWalletVisitorsCountSuccess('wallet_xbets', 'wallet_xbets.id', 'admins_coms.wallet_xbet_id', 'wallet_xbets.status', 'SUCCESS');

        $valeur_pending =  $this->historiqueService->getCountWalletVisitorsCountSuccess('wallet_xbets', 'wallet_xbets.id', 'admins_coms.wallet_xbet_id', 'wallet_xbets.status', 'PENDING');

        $valeur_error =  $this->historiqueService->getCountWalletVisitorsCountSuccess('wallet_xbets', 'wallet_xbets.id', 'admins_coms.wallet_xbet_id', 'wallet_xbets.status', 'ERROR');

        return view('pages.historicals.under_distributer.visitors.browse_xbet_distri', compact('wallet_payments', 'valeur_payments', 'countrie', 'valeur_success', 'valeur_pending', 'valeur_error'));
    }

    // Show Under distributer

    public function getCumulsWalletMobileTransactions($wallet, $wallet_transaction, $countryId, $wallet_commission, $wallet_success, $isWeb, $wallet_trans, $wallet_created)
    {
        return  $wallet::join('wallets', 'wallets.id', $wallet_transaction)
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countryId)
            ->where($wallet_commission, '!=', 0)
            ->where($wallet_success, 'SUCCESS')
            ->where($isWeb, false)
            ->select($wallet_trans)
            ->groupBy($wallet_created)
            ->limit(5)
            ->paginate(20);
    }



    public function show_under_payment(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments =  $this->getCumulsWalletMobileTransactions(WalletPayment::class, 'wallet_payments.wallet_id', $countrie->id, 'wallet_payments.commissions', 'wallet_payments.status', 'wallet_payments.is_web', 'wallet_payments.*',  'wallet_payments.created_at');


        $valeur_payments = $this->getCumulsWalletPayments($countrie->id);

        return view('pages.historicals.under_distributer.browse_paiement', compact('wallet_payments', 'valeur_payments', 'countrie'));
    }

    public function show_under_transaction(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();


        $wallet_payments =  $this->getCumulsWalletMobileTransactions(WalletTransaction::class, 'wallet_transactions.wallet_id', $countrie->id, 'wallet_transactions.commissions', 'wallet_transactions.status', 'wallet_transactions.is_web', 'wallet_transactions.*',  'wallet_transactions.created_at');

        $valeur_payments = $this->getCumulsWalletTransactions($countrie->id);

        return view('pages.historicals.under_distributer.browse_transfer', compact('wallet_payments', 'valeur_payments', 'countrie'));
    }

    public function show_under_bank(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments =  $this->getCumulsWalletMobileTransactions(WalletBank::class, 'wallet_banks.wallet_id', $countrie->id, 'wallet_banks.commissions', 'wallet_banks.status', 'wallet_banks.is_web', 'wallet_banks.*',  'wallet_banks.created_at');

        $valeur_payments = $this->getCumulsWalletBanks($countrie->id);

        return view('pages.historicals.under_distributer.browse_virement', compact('wallet_payments', 'valeur_payments', 'countrie'));
    }

    public function show_under_deposit(Request $request, String $code)
    {
        $countrie = Country::where('name', $code)->first();

        $wallet_payments =  $this->getCumulsWalletMobileTransactions(walletDeposit::class, 'wallet_deposits.wallet_id', $countrie->id, 'wallet_deposits.commissions', 'wallet_deposits.status', 'wallet_deposits.is_web', 'wallet_deposits.*',  'wallet_deposits.created_at');


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

        $wallet_payments =  $this->getCumulsWalletMobileTransactions(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', $countrie->id, 'wallet_phone_credits.commissions', 'wallet_phone_credits.status', 'wallet_phone_credits.is_web', 'wallet_phone_credits.*',  'wallet_phone_credits.created_at');

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


    public function browse_visitors()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-pays')) {
            abort(403);
        }

        return view('pages.countries.browse_visitors');
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
        $sum_transaction_todays =  $this->historiqueService->getTodayTransactionAmountCountrieTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', $countrie->id, 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount');
        $sum_reload_todays =  $this->historiqueService->getTodayReloadAmountTotal(WalletReload::class);
        $sum_phoneCredit_todays =  $this->historiqueService->getTodayTransactionAmountCountrieTotal(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', $countrie->id, 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by',  'wallet_phone_credits.status',  'wallet_phone_credits.amount');
        $sum_inter_todays =  $this->historiqueService->getTodayTransactionAmountCountrieTotal(WalletInter::class, 'wallet_inters.wallet_id', $countrie->id, 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount');
        $sum_bank_todays =  $this->historiqueService->getTodayTransactionAmountCountrieTotal(WalletBank::class, 'wallet_banks.wallet_id', $countrie->id, 'wallet_banks.created_at', 'wallet_banks.updated_by',  'wallet_banks.status',  'wallet_banks.amount');
        $sum_deposit_todays =  $this->historiqueService->getTodayTransactionAmountCountrieTotal(WalletDeposit::class, 'wallet_deposits.wallet_id', $countrie->id, 'wallet_deposits.created_at', 'wallet_deposits.updated_by',  'wallet_deposits.status',  'wallet_deposits.amount');
        $xbet_amount =  $this->historiqueService->getTodayTransactionAmountCountrieTotal(WalletXbet::class, 'wallet_xbets.wallet_id', $countrie->id, 'wallet_xbets.created_at', 'wallet_xbets.updated_by',  'wallet_xbets.status',  'wallet_xbets.amount');

        // new wallets
        $sum_push_todays =  $this->historiqueService->getTodayTransactionAmountCountrieTotal(WalletPush::class, 'wallet_pushes.wallet_id', $countrie->id, 'wallet_pushes.created_at', 'wallet_pushes.updated_by',  'wallet_pushes.status',  'wallet_pushes.amount');
        $sum_djamo_todays =  $this->historiqueService->getTodayTransactionAmountCountrieTotal(WalletDjamo::class, 'wallet_djamos.wallet_id', $countrie->id, 'wallet_djamos.created_at', 'wallet_djamos.updated_by',  'wallet_djamos.status',  'wallet_djamos.amount');
        $sum_tresort_todays =  $this->historiqueService->getTodayTransactionAmountCountrieTotal(WalletTresortMoney::class, 'wallet_tresort_money.wallet_id', $countrie->id, 'wallet_tresort_money.created_at', 'wallet_tresort_money.updated_by',  'wallet_tresort_money.status',  'wallet_tresort_money.amount');
        $sum_versment_todays =  $this->historiqueService->getTodayTransactionAmountCountrieTotal(WalletVersement::class, 'wallet_versements.wallet_id', $countrie->id, 'wallet_versements.created_at', 'wallet_versements.updated_by',  'wallet_versements.status',  'wallet_versements.amount');

        // Amount transactions month
        $sum_payment_month =  $this->historiqueService->getDaysTransactionCountrie(WalletPayment::class, 'wallet_payments.wallet_id', $countrie->id, 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount');
        $sum_transaction_month =  $this->historiqueService->getDaysTransactionCountrie(WalletTransaction::class, 'wallet_transactions.wallet_id', $countrie->id, 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount');
        $sum_reload_month =  $this->historiqueService->getDaysReloadAmountTotal(WalletReload::class);
        $sum_phoneCredit_month =  $this->historiqueService->getDaysTransactionCountrie(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', $countrie->id, 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by',  'wallet_phone_credits.status',  'wallet_phone_credits.amount');
        $sum_inter_month =  $this->historiqueService->getDaysTransactionCountrie(WalletInter::class, 'wallet_inters.wallet_id', $countrie->id, 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount');
        $sum_bank_month =  $this->historiqueService->getDaysTransactionCountrie(WalletBank::class, 'wallet_banks.wallet_id', $countrie->id, 'wallet_banks.created_at', 'wallet_banks.updated_by',  'wallet_banks.status',  'wallet_banks.amount');
        $sum_deposit_month =  $this->historiqueService->getDaysTransactionCountrie(WalletDeposit::class, 'wallet_deposits.wallet_id', $countrie->id, 'wallet_deposits.created_at', 'wallet_deposits.updated_by',  'wallet_deposits.status',  'wallet_deposits.amount');
        $xbet_amount_month =  $this->historiqueService->getDaysTransactionCountrie(WalletXbet::class, 'wallet_xbets.wallet_id', $countrie->id, 'wallet_xbets.created_at', 'wallet_xbets.updated_by',  'wallet_xbets.status',  'wallet_xbets.amount');

        // new wallets
        $push_amount_month =  $this->historiqueService->getDaysTransactionCountrie(WalletPush::class, 'wallet_pushes.wallet_id', $countrie->id, 'wallet_pushes.created_at', 'wallet_pushes.updated_by',  'wallet_pushes.status',  'wallet_pushes.amount');
        $djamo_amount_month =  $this->historiqueService->getDaysTransactionCountrie(WalletDjamo::class, 'wallet_djamos.wallet_id', $countrie->id, 'wallet_djamos.created_at', 'wallet_djamos.updated_by',  'wallet_djamos.status',  'wallet_djamos.amount');
        $tresort_amount_month =  $this->historiqueService->getDaysTransactionCountrie(WalletTresortMoney::class, 'wallet_tresort_money.wallet_id', $countrie->id, 'wallet_tresort_money.created_at', 'wallet_tresort_money.updated_by',  'wallet_tresort_money.status',  'wallet_tresort_money.amount');
        $verment_amount_month =  $this->historiqueService->getDaysTransactionCountrie(WalletVersement::class, 'wallet_versements.wallet_id', $countrie->id, 'wallet_versements.created_at', 'wallet_versements.updated_by',  'wallet_versements.status',  'wallet_versements.amount');

        // Sum commissions
        $push_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletPush::class, 'wallet_pushes.wallet_id', $countrie->id,  'wallet_pushes.status',  'wallet_pushes.commissions');
        $payment_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletPayment::class, 'wallet_payments.wallet_id', $countrie->id,  'wallet_payments.status',  'wallet_payments.commissions');
        $transaction_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletTransaction::class, 'wallet_transactions.wallet_id', $countrie->id,  'wallet_transactions.status',  'wallet_transactions.commissions');
        $phoneCredit_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', $countrie->id,  'wallet_phone_credits.status',  'wallet_phone_credits.commissions');
        $inter_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletInter::class, 'wallet_inters.wallet_id', $countrie->id,  'wallet_inters.status',  'wallet_inters.commissions');
        $bank_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletBank::class, 'wallet_banks.wallet_id', $countrie->id,  'wallet_banks.status',  'wallet_banks.commissions');
        $deposit_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletDeposit::class, 'wallet_deposits.wallet_id', $countrie->id,  'wallet_deposits.status',  'wallet_deposits.commissions');
        $xbet_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletXbet::class, 'wallet_xbets.wallet_id', $countrie->id,  'wallet_xbets.status',  'wallet_xbets.commissions');
        $djamo_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletDjamo::class, 'wallet_djamos.wallet_id', $countrie->id,  'wallet_djamos.status',  'wallet_djamos.commissions');
        $tresort_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletTresortMoney::class, 'wallet_tresort_money.wallet_id', $countrie->id,  'wallet_tresort_money.status',  'wallet_tresort_money.commissions');
        $versment_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletVersement::class, 'wallet_versements.wallet_id', $countrie->id,  'wallet_versements.status',  'wallet_versements.commissions');


        $push_c_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletPush::class, 'wallet_pushes.wallet_id', $countrie->id,  'wallet_pushes.status',  'wallet_pushes.commission_companie');
        $payment_c_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletPayment::class, 'wallet_payments.wallet_id', $countrie->id,  'wallet_payments.status',  'wallet_payments.commission_companie');
        $transaction_c_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletTransaction::class, 'wallet_transactions.wallet_id', $countrie->id,  'wallet_transactions.status',  'wallet_transactions.commission_companie');
        $phoneCredit_c_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', $countrie->id,  'wallet_phone_credits.status',  'wallet_phone_credits.commission_companie');
        $inter_c_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletInter::class, 'wallet_inters.wallet_id', $countrie->id,  'wallet_inters.status',  'wallet_inters.commission_companie');
        $bank_c_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletBank::class, 'wallet_banks.wallet_id', $countrie->id,  'wallet_banks.status',  'wallet_banks.commission_companie');
        $deposit_c_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletDeposit::class, 'wallet_deposits.wallet_id', $countrie->id,  'wallet_deposits.status',  'wallet_deposits.commission_companie');
        $xbet_c_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletXbet::class, 'wallet_xbets.wallet_id', $countrie->id,  'wallet_xbets.status',  'wallet_xbets.commission_companie');
        $djamo_c_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletDjamo::class, 'wallet_djamos.wallet_id', $countrie->id,  'wallet_djamos.status',  'wallet_djamos.commission_companie');
        $tresort_c_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletTresortMoney::class, 'wallet_tresort_money.wallet_id', $countrie->id,  'wallet_tresort_money.status',  'wallet_tresort_money.commission_companie');
        $versment_c_commission =  $this->historiqueService->getCommissionTransactionCountrie(WalletVersement::class, 'wallet_versements.wallet_id', $countrie->id,  'wallet_versements.status',  'wallet_versements.commission_companie');



        $sumTotalCommission = $push_commission +  $payment_commission + $transaction_commission + $phoneCredit_commission + $inter_commission + $bank_commission + $deposit_commission +
            $xbet_commission + $djamo_commission + $tresort_commission +  $versment_commission;

        $sumCTotalCommission = $push_c_commission +  $payment_c_commission + $transaction_c_commission + $phoneCredit_c_commission + $inter_c_commission + $bank_c_commission + $deposit_c_commission +
            $xbet_c_commission + $djamo_c_commission + $tresort_c_commission +  $versment_c_commission;
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
            'sumTotalCommission',
            'sumCTotalCommission',
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
            // New wallet
            'sum_push_todays',
            'sum_djamo_todays',
            'sum_tresort_todays',
            'sum_versment_todays',

            'push_amount_month',
            'djamo_amount_month',
            'tresort_amount_month',
            'verment_amount_month',

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
            $sum_bank =  $this->historiqueService->getCumulsWalletTransactions(WalletBank::class, 'wallet_banks.wallet_id', $countrie->id, 'wallet_banks.commissions', 'wallet_banks.status');

            $sum_deposit =  $this->historiqueService->getCumulsWalletTransactions(WalletDeposit::class, 'wallet_deposits.wallet_id', $countrie->id, 'wallet_deposits.commissions', 'wallet_deposits.status');
            $sum_payment =  $this->historiqueService->getCumulsWalletTransactions(WalletPayment::class, 'wallet_payments.wallet_id', $countrie->id, 'wallet_payments.commissions', 'wallet_payments.status');
            $sum_transaction =  $this->historiqueService->getCumulsWalletTransactions(WalletTransaction::class, 'wallet_transactions.wallet_id', $countrie->id, 'wallet_transactions.commissions', 'wallet_transactions.status');
            $sum_inter =  $this->historiqueService->getCumulsWalletTransactions(WalletInter::class, 'wallet_inters.wallet_id', $countrie->id, 'wallet_inters.commissions', 'wallet_inters.status');
            $sum_phoneCredit =  $this->historiqueService->getCumulsWalletTransactions(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', $countrie->id, 'wallet_phone_credits.commissions', 'wallet_phone_credits.status');
            $sum_xbet =  $this->historiqueService->getCumulsWalletTransactions(WalletXbet::class, 'wallet_xbets.wallet_id', $countrie->id, 'wallet_xbets.commissions', 'wallet_xbets.status');


            $sum_amount_distributer =  $this->historiqueService->getSDistributeurSumCountryAdmin($countrie->id);
            $sum_amount_custumers =  $this->historiqueService->getCustumersSumCountrieAdmin($countrie->id);


            $cumul_solde = $sum_bank +  $sum_deposit +  $sum_payment + $sum_transaction + $sum_inter +  $sum_phoneCredit + $sum_xbet;


            $wallet_banks =  $this->historiqueService->getWalletLimitViews(WalletBank::class, 'wallet_banks.wallet_id', $countrie->id, 'wallet_banks.commissions', 'wallet_banks.status',  'wallet_banks.created_at');
            $wallet_deposits =  $this->historiqueService->getWalletLimitViews(WalletDeposit::class, 'wallet_deposits.wallet_id', $countrie->id, 'wallet_deposits.commissions', 'wallet_deposits.status',  'wallet_deposits.created_at');
            $wallet_payments =  $this->historiqueService->getWalletLimitViews(WalletPayment::class, 'wallet_payments.wallet_id', $countrie->id, 'wallet_payments.commissions', 'wallet_payments.status',  'wallet_payments.created_at');
            $wallet_transactions =  $this->historiqueService->getWalletLimitViews(WalletTransaction::class, 'wallet_transactions.wallet_id', $countrie->id, 'wallet_transactions.commissions', 'wallet_transactions.status',  'wallet_transactions.created_at');
            $wallet_inters =  $this->historiqueService->getWalletLimitViews(WalletInter::class, 'wallet_inters.wallet_id', $countrie->id, 'wallet_inters.commissions', 'wallet_inters.status',  'wallet_inters.created_at');
            $wallet_phones =  $this->historiqueService->getWalletLimitViews(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', $countrie->id, 'wallet_phone_credits.commissions', 'wallet_phone_credits.status',  'wallet_phone_credits.created_at');
            $wallet_xbets =  $this->historiqueService->getWalletLimitViews(WalletXbet::class, 'wallet_xbets.wallet_id', $countrie->id, 'wallet_xbets.commissions', 'wallet_xbets.status',  'wallet_xbets.created_at');
            $sum_wallet_companies = $this->historiqueService->sumwalletCountry($countrie->id, 'sid_users.is_company');

            $sum_wallet_drawer = $this->getWalletWithMerchantSumUserDaysProperty($countrie->id);
            $With_drawer_merchant = $this->getWalletWithMerchantSumUserDaygetProperty($countrie->id);
            $With_drawer_compagnie = $this->getWalletWithMerchantCompagnieSumUserDaysProperty($countrie->id);

            // Last valeurs
            return view('pages.countries.show_under_distributer', compact(
                'countrie',
                'cumul_solde',
                'sum_bank',
                'sum_deposit',
                'sum_wallet_companies',
                'With_drawer_compagnie',
                'sum_payment',
                'sum_transaction',
                'sum_xbet',
                'sum_inter',
                'sum_phoneCredit',
                'users_distri_Countries',
                'distri_countries',
                'custumers_countries',
                'wallet_banks',
                'wallet_deposits',
                'wallet_payments',
                'wallet_transactions',
                'wallet_inters',
                'wallet_phones',
                'wallet_xbets',
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


            // Admin
            $sum_bank =  $this->historiqueService->getCumulsWalletDepositDistributers('wallet_banks', 'wallet_banks.id', 'admins_coms.wallet_bank_id', 'wallet_banks.wallet_id', $countrie->id);

            $sum_deposit =  $this->historiqueService->getCumulsWalletDepositDistributers('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id', 'wallet_deposits.wallet_id', $countrie->id);

            $sum_payment =  $this->historiqueService->getCumulsWalletDepositDistributers('wallet_payments', 'wallet_payments.id', 'admins_coms.wallet_payment_id', 'wallet_payments.wallet_id', $countrie->id);

            $sum_transaction =  $this->historiqueService->getCumulsWalletDepositDistributers('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id', 'wallet_transactions.wallet_id', $countrie->id);

            $sum_inter =  $this->historiqueService->getCumulsWalletDepositDistributers('wallet_inters', 'wallet_inters.id', 'admins_coms.wallet_inter_id', 'wallet_inters.wallet_id', $countrie->id);

            $sum_phoneCredit =  $this->historiqueService->getCumulsWalletDepositDistributers('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_phone_credit_id', 'wallet_phone_credits.wallet_id', $countrie->id);

            $sum_xbet =  $this->historiqueService->getCumulsWalletDepositDistributers('wallet_xbets', 'wallet_xbets.id', 'admins_coms.wallet_xbet_id', 'wallet_xbets.wallet_id', $countrie->id);


            $cumul_solde = $sum_bank +  $sum_deposit +  $sum_payment + $sum_transaction + $sum_inter +  $sum_phoneCredit + $sum_xbet;


            $wallet_banks =  $this->historiqueService->getWalletBankDistributersLimitViews('wallet_banks', 'wallet_banks.id', 'admins_coms.wallet_bank_id', 'wallet_banks.wallet_id', $countrie->id);

            $wallet_deposits =  $this->historiqueService->getWalletBankDistributersLimitViews('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id', 'wallet_deposits.wallet_id', $countrie->id);

            $wallet_payments =  $this->historiqueService->getWalletBankDistributersLimitViews('wallet_payments', 'wallet_payments.id', 'admins_coms.wallet_payment_id', 'wallet_payments.wallet_id', $countrie->id);

            $wallet_transactions =  $this->historiqueService->getWalletBankDistributersLimitViews('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id', 'wallet_transactions.wallet_id', $countrie->id);

            $wallet_inters =  $this->historiqueService->getWalletBankDistributersLimitViews('wallet_inters', 'wallet_inters.id', 'admins_coms.wallet_inter_id', 'wallet_inters.wallet_id', $countrie->id);

            $wallet_phones =  $this->historiqueService->getWalletBankDistributersLimitViews('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_phone_credit_id', 'wallet_phone_credits.wallet_id', $countrie->id);

            $wallet_xbets =  $this->historiqueService->getWalletBankDistributersLimitViews('wallet_xbets', 'wallet_xbets.id', 'admins_coms.wallet_xbet_id', 'wallet_xbets.wallet_id', $countrie->id);



            // Last valeurs
            return view('pages.countries.show_distri_distributer', compact(
                'countrie',
                'cumul_solde',
                'sum_bank',
                'sum_deposit',
                'sum_payment',
                'sum_xbet',
                'sum_transaction',
                'sum_inter',
                'sum_phoneCredit',
                'users_distri_Countries',
                'distri_countries',
                'custumers_countries',
                'wallet_banks',
                'wallet_deposits',
                'wallet_payments',
                'wallet_xbets',
                'wallet_transactions',
                'wallet_inters',
                'wallet_phones',
            ));
        } else {
            abort(403);
        }
    }

    public function Show_distri_visitors_distributer(string $code, Request $request)
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
            $sum_bank =  $this->historiqueService->getCumulsWalletDepositVisitorsDistributers('wallet_banks', 'wallet_banks.id', 'admins_coms.wallet_bank_id');

            $sum_deposit =  $this->historiqueService->getCumulsWalletDepositVisitorsDistributers('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id');

            $sum_payment =  $this->historiqueService->getCumulsWalletDepositVisitorsDistributers('wallet_payments', 'wallet_payments.id', 'admins_coms.wallet_payment_id');

            $sum_transaction =  $this->historiqueService->getCumulsWalletDepositVisitorsDistributers('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id');

            $sum_inter =  $this->historiqueService->getCumulsWalletDepositVisitorsDistributers('wallet_inters', 'wallet_inters.id', 'admins_coms.wallet_inter_id');

            $sum_phoneCredit =  $this->historiqueService->getCumulsWalletDepositVisitorsDistributers('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_phone_credit_id');

            $sum_xbet =  $this->historiqueService->getCumulsWalletDepositVisitorsDistributers('wallet_xbets', 'wallet_xbets.id', 'admins_coms.wallet_xbet_id');


            $cumul_solde = $sum_bank +  $sum_deposit +  $sum_payment + $sum_transaction + $sum_inter +  $sum_phoneCredit + $sum_xbet;


            $wallet_banks =  $this->historiqueService->getWalletBankVisitorsDistributersLimitViews('wallet_banks', 'wallet_banks.id', 'admins_coms.wallet_bank_id');

            $wallet_deposits =  $this->historiqueService->getWalletBankVisitorsDistributersLimitViews('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id');

            $wallet_payments =  $this->historiqueService->getWalletBankVisitorsDistributersLimitViews('wallet_payments', 'wallet_payments.id', 'admins_coms.wallet_payment_id');

            $wallet_transactions =  $this->historiqueService->getWalletBankVisitorsDistributersLimitViews('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id');

            $wallet_inters =  $this->historiqueService->getWalletBankVisitorsDistributersLimitViews('wallet_inters', 'wallet_inters.id', 'admins_coms.wallet_inter_id');

            $wallet_phones =  $this->historiqueService->getWalletBankVisitorsDistributersLimitViews('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_phone_credit_id');

            $wallet_xbets =  $this->historiqueService->getWalletBankVisitorsDistributersLimitViews('wallet_xbets', 'wallet_xbets.id', 'admins_coms.wallet_xbet_id');



            // Last valeurs
            return view('pages.countries.show_distri_visitors_distributer', compact(
                'countrie',
                'cumul_solde',
                'sum_bank',
                'sum_deposit',
                'sum_payment',
                'sum_xbet',
                'sum_transaction',
                'sum_inter',
                'sum_phoneCredit',
                'users_distri_Countries',
                'distri_countries',
                'custumers_countries',
                'wallet_banks',
                'wallet_deposits',
                'wallet_payments',
                'wallet_xbets',
                'wallet_transactions',
                'wallet_inters',
                'wallet_phones',
            ));
        } else {
            abort(403);
        }
    }

    public function getWalletWithMerchantSumUserDaysProperty($countrie)
    {
        return WithMerchant::join('sid_users', 'sid_users.id', 'with_merchants.sid_user_id')
            ->where('sid_users.country_id', $countrie)
            ->where('sid_users.is_under_distributer', true)
            ->where('with_merchants.date_removed', '!=', null)
            ->whereDate('with_merchants.date_removed', Carbon::now())
            ->sum('with_merchants.amount_with_draw');
    }

    public function getWalletWithMerchantCompagnieSumUserDaysProperty($countrie)
    {
        return WithMerchant::join('sid_users', 'sid_users.id', 'with_merchants.sid_user_id')
            ->where('sid_users.country_id', $countrie)
            ->where('sid_users.is_company', true)
            ->where('with_merchants.date_removed', '!=', null)
            ->whereDate('with_merchants.date_removed', Carbon::now())
            ->sum('with_merchants.amount_with_draw');
    }


    // public function getWalletWithMerchantSumUserDaysProperty($countrie)
    // {


    //     $data = [];

    //     $withMerchant = WithMerchant::join('sid_users', 'sid_users.id', 'with_merchants.sid_user_id')
    //         ->where('sid_users.country_id', $countrie)
    //         ->where('with_merchants.date_removed', '!=', null)
    //         ->whereDate('with_merchants.date_removed', Carbon::now())
    //         ->orderBy('with_merchants.created_at', 'desc')
    //         ->select('with_merchants.*')
    //         ->distinct()
    //         ->get();

    //     foreach ($withMerchant  as $bank) {
    //         $data[] = $bank->amount_with_draw;
    //     }

    //     return array_sum($data);
    // }

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
