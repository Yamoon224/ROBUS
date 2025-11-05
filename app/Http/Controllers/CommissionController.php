<?php

namespace App\Http\Controllers;

use App\Models\AdminsCom;
use App\Models\WithMerchant;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\PaymentCountrie;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Costs\PaymentCost;
use App\Models\Custumers\Country;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Services\HistoriqueService;

class CommissionController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $historiqueService;

    public function __construct(
        HistoriqueService $historiqueService,
    ) {
        $this->historiqueService = $historiqueService;
    }


    public function browse_historique()
    {
        return view('pages.historicals.under_distributer.browse_historiques');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_reload()
    {

        return view('pages.historicals.under_distributer.browse_reload');
    }

    public function reporting_historical()
    {

        return $this->historiqueService->generateDailyReport(
            modelClass: WalletBank::class,
            relationTableWallet: 'wallet_banks.wallet_id',
            walletCreatedBy: 'wallet_banks.created_by',
            walletAll: 'wallet_banks.*',
            walletCreated: 'wallet_banks.created_at',
            walletStatus: 'wallet_banks.status',
            viewPath: 'pages.historicals.under_distributer.pdf_bank',
            fileName: 'reporting_virement.pdf',
        );
    }

    public function reporting_reload_historical()
    {


        return $this->historiqueService->generateDailyReport(
            modelClass: WalletReload::class,
            relationTableWallet: 'wallet_reloads.wallet_id',
            walletCreatedBy: 'wallet_reloads.created_by',
            walletAll: 'wallet_reloads.*',
            walletCreated: 'wallet_reloads.created_at',
            walletStatus: 'wallet_reloads.status',
            viewPath: 'pages.historicals.under_distributer.pdf_reload',
            fileName: 'reporting_rechargment.pdf',
        );
    }

    public function reporting_card_historical()
    {

        return $this->historiqueService->generateDailyReport(
            modelClass: WalletDeposit::class,
            relationTableWallet: 'wallet_deposits.wallet_id',
            walletCreatedBy: 'wallet_deposits.created_by',
            walletAll: 'wallet_deposits.*',
            walletCreated: 'wallet_deposits.created_at',
            walletStatus: 'wallet_deposits.status',
            viewPath: 'pages.historicals.under_distributer.pdf_card_bank',
            fileName: 'reporting_commande.pdf',
        );
    }


    public function reporting_inter_historical()
    {

        return $this->historiqueService->generateDailyReport(
            modelClass: WalletInter::class,
            relationTableWallet: 'wallet_inters.wallet_id',
            walletCreatedBy: 'wallet_inters.created_by',
            walletAll: 'wallet_inters.*',
            walletCreated: 'wallet_inters.created_at',
            walletStatus: 'wallet_inters.status',
            viewPath: 'pages.historicals.under_distributer.pdf_inter',
            fileName: 'reporting_inter.pdf',
        );
    }

    public function reporting_payment_historical()
    {

        return $this->historiqueService->generateDailyReport(
            modelClass: WalletPayment::class,
            relationTableWallet: 'wallet_payments.wallet_id',
            walletCreatedBy: 'wallet_payments.created_by',
            walletAll: 'wallet_payments.*',
            walletCreated: 'wallet_payments.created_at',
            walletStatus: 'wallet_payments.status',
            viewPath: 'pages.historicals.under_distributer.pdf_payment',
            fileName: 'reporting_payment.pdf',
        );
    }

    public function reporting_phone_historical()
    {

        return $this->historiqueService->generateDailyReport(
            modelClass: WalletPhoneCredit::class,
            relationTableWallet: 'wallet_phone_credits.wallet_id',
            walletCreatedBy: 'wallet_phone_credits.created_by',
            walletAll: 'wallet_phone_credits.*',
            walletCreated: 'wallet_phone_credits.created_at',
            walletStatus: 'wallet_phone_credits.status',
            viewPath: 'pages.historicals.under_distributer.pdf_phone',
            fileName: 'reporting_phone.pdf',
        );
    }

    public function reporting_transfer_historical()
    {


        return $this->historiqueService->generateDailyReport(
            modelClass: WalletTransaction::class,
            relationTableWallet: 'wallet_transactions.wallet_id',
            walletCreatedBy: 'wallet_transactions.created_by',
            walletAll: 'wallet_transactions.*',
            walletCreated: 'wallet_transactions.created_at',
            walletStatus: 'wallet_transactions.status',
            viewPath: 'pages.historicals.under_distributer.pdf_transfer',
            fileName: 'reporting_transfers.pdf',
        );
    }

    // Reposting valeur distributers
    public function reporting_distri_historical(String $code)
    {

        $countrie = Country::where('name', $code)->first();

        return $this->historiqueService->generateDailyDistributorReport(
            modelClass: AdminsCom::class,
            relationTableWallet: 'wallet_banks',
            relationWalletId: 'wallet_banks.id',
            relationAdminTableWalletId: 'admins_coms.wallet_bank_id',
            relationTableWalletId: 'wallet_banks.wallet_id',
            countrie: $countrie,
            adminDescription: 'admins_coms.description_distributer',
            description: 'Validation distributeur',
            walletCreated: 'admins_coms.created_at',
            walletAll: 'admins_coms.*',
            walletStatus: 'wallet_banks.status',
            viewPath: 'pages.historicals.pdf_bank_distri',
            fileName: 'reporting_virement_distri.pdf',
        );


        // if ($usersCountry->is_admin == true) {

        //     $valeur_results =  AdminsCom::join('wallet_banks', 'wallet_banks.id', 'admins_coms.wallet_bank_id')
        //         ->join('wallets', 'wallets.id', 'wallet_banks.wallet_id')
        //         ->where('wallets.country_id', $countrie->id)
        //         ->where('admins_coms.description_distributer', 'Validation distributeur')
        //         ->whereDate('admins_coms.created_at',  $convertions)
        //         ->select('admins_coms.*')
        //         ->get();

        //     foreach ($valeur_results as $bank) {
        //         $bank_historicals[] = $bank;
        //     }

        //     $account_penddings = $this->getCountWalletBankPending($countrie->id);

        //     $account_success = $this->getCountWalletBankSuccess($countrie->id);

        //     $account_errors = $this->getCountWalletBankError($countrie->id);

        //     $pdf = Pdf::loadView('pages.historicals.pdf_bank_distri', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
        //     return $pdf->download('reporting_virement_distri.pdf');
        // }
    }

    public function reporting_reload_distri_historical(String $code)
    {


        $countrie = Country::where('name', $code)->first();

        return $this->historiqueService->generateDailyDistributorReport(
            modelClass: AdminsCom::class,
            relationTableWallet: 'wallet_reloads',
            relationWalletId: 'wallet_reloads.id',
            relationAdminTableWalletId: 'admins_coms.wallet_reload_id',
            relationTableWalletId: 'wallet_reloads.wallet_id',
            countrie: $countrie,
            adminDescription: 'admins_coms.description_distributer',
            description: 'Validation distributeur',
            walletCreated: 'admins_coms.created_at',
            walletAll: 'admins_coms.*',
            walletStatus: 'wallet_reloads.status',
            viewPath: 'pages.historicals.pdf_reload_distri',
            fileName: 'reporting_rechargment_distri.pdf',
        );
    }

    public function reporting_card_distri_historical(String $code)
    {

        $countrie = Country::where('name', $code)->first();

        return $this->historiqueService->generateDailyDistributorReport(
            modelClass: AdminsCom::class,
            relationTableWallet: 'wallet_deposits',
            relationWalletId: 'wallet_deposits.id',
            relationAdminTableWalletId: 'admins_coms.wallet_deposit_id',
            relationTableWalletId: 'wallet_deposits.wallet_id',
            countrie: $countrie,
            adminDescription: 'admins_coms.description_distributer',
            description: 'Validation distributeur',
            walletCreated: 'admins_coms.created_at',
            walletAll: 'admins_coms.*',
            walletStatus: 'wallet_deposits.status',
            viewPath: 'pages.historicals.pdf_card_bank_distri',
            fileName: 'reporting_commande_distri.pdf',
        );
    }


    public function reporting_inter_distri_historical()
    {


        return $this->historiqueService->generateDailyAllDistributorReport(
            modelClass: AdminsCom::class,
            relationTableWallet: 'wallet_inters',
            relationWalletId: 'wallet_inters.id',
            relationAdminTableWalletId: 'admins_coms.wallet_inter_id',
            relationTableWalletId: 'wallet_inters.wallet_id',
            adminDescription: 'admins_coms.description_distributer',
            description: 'Validation distributeur',
            walletCreated: 'admins_coms.created_at',
            walletAll: 'admins_coms.*',
            walletStatus: 'wallet_inters.status',
            viewPath: 'pages.historicals.pdf_inter_distri',
            fileName: 'reporting_inter_distri.pdf',
        );
    }

    public function reporting_payment_distri_historical(String $code)
    {

        $countrie = Country::where('name', $code)->first();

        return $this->historiqueService->generateDailyDistributorReport(
            modelClass: AdminsCom::class,
            relationTableWallet: 'wallet_payments',
            relationWalletId: 'wallet_payments.id',
            relationAdminTableWalletId: 'admins_coms.wallet_payment_id',
            relationTableWalletId: 'wallet_payments.wallet_id',
            countrie: $countrie,
            adminDescription: 'admins_coms.description_distributer',
            description: 'Validation distributeur',
            walletCreated: 'admins_coms.created_at',
            walletAll: 'admins_coms.*',
            walletStatus: 'wallet_payments.status',
            viewPath: 'pages.historicals.pdf_payment_distri',
            fileName: 'reporting_payment_distri.pdf',
        );
    }

    public function reporting_phone_distri_historical(String $code)
    {

        $countrie = Country::where('name', $code)->first();

        return $this->historiqueService->generateDailyDistributorReport(
            modelClass: AdminsCom::class,
            relationTableWallet: 'wallet_phone_credits',
            relationWalletId: 'wallet_phone_credits.id',
            relationAdminTableWalletId: 'admins_coms.wallet_phone_credit_id',
            relationTableWalletId: 'wallet_phone_credits.wallet_id',
            countrie: $countrie,
            adminDescription: 'admins_coms.description_distributer',
            description: 'Validation distributeur',
            walletCreated: 'admins_coms.created_at',
            walletAll: 'admins_coms.*',
            walletStatus: 'wallet_phone_credits.status',
            viewPath: 'pages.historicals.pdf_phone_distri',
            fileName: 'reporting_phone_distri.pdf',
        );
    }

    public function reporting_transfer_distri_historical(String $code)
    {

        $countrie = Country::where('name', $code)->first();

        return $this->historiqueService->generateDailyDistributorReport(
            modelClass: AdminsCom::class,
            relationTableWallet: 'wallet_transactions',
            relationWalletId: 'wallet_transactions.id',
            relationAdminTableWalletId: 'admins_coms.wallet_transaction_id',
            relationTableWalletId: 'wallet_transactions.wallet_id',
            countrie: $countrie,
            adminDescription: 'admins_coms.description_distributer',
            description: 'Validation distributeur',
            walletCreated: 'admins_coms.created_at',
            walletAll: 'admins_coms.*',
            walletStatus: 'wallet_transactions.status',
            viewPath: 'pages.historicals.pdf_transfer_distri',
            fileName: 'reporting_transfers_distri.pdf',
        );
    }

    // All valeur Dawload

    public function reporting_distri_all_historical(String $code)
    {

        $countrie = Country::where('name', $code)->first();

        return $this->historiqueService->generateDailyDistributorReport(
            modelClass: AdminsCom::class,
            relationTableWallet: 'wallet_banks',
            relationWalletId: 'wallet_banks.id',
            relationAdminTableWalletId: 'admins_coms.wallet_bank_id',
            relationTableWalletId: 'wallet_banks.wallet_id',
            countrie: $countrie,
            adminDescription: 'admins_coms.description_distributer',
            description: 'Validation distributeur',
            walletCreated: 'admins_coms.created_at',
            walletAll: 'admins_coms.*',
            walletStatus: 'wallet_banks.status',
            viewPath: 'pages.historicals.pdf_bank_distri',
            fileName: 'reporting_virement_distri.pdf',
        );
    }

    public function reporting_reload_distri_all_historical(String $code)
    {

        $countrie = Country::where('name', $code)->first();

        return $this->historiqueService->generateDailyDistributorReport(
            modelClass: AdminsCom::class,
            relationTableWallet: 'wallet_reloads',
            relationWalletId: 'wallet_reloads.id',
            relationAdminTableWalletId: 'admins_coms.wallet_reload_id',
            relationTableWalletId: 'wallet_reloads.wallet_id',
            countrie: $countrie,
            adminDescription: 'admins_coms.description_distributer',
            description: 'Validation distributeur',
            walletCreated: 'admins_coms.created_at',
            walletAll: 'admins_coms.*',
            walletStatus: 'wallet_reloads.status',
            viewPath: 'pages.historicals.pdf_reload_distri',
            fileName: 'reporting_rechargment_distri.pdf',
        );
    }

    public function reporting_card_distri_all_historical(String $code)
    {

        $countrie = Country::where('name', $code)->first();

        return $this->historiqueService->generateDailyDistributorReport(
            modelClass: AdminsCom::class,
            relationTableWallet: 'wallet_deposits',
            relationWalletId: 'wallet_deposits.id',
            relationAdminTableWalletId: 'admins_coms.wallet_deposit_id',
            relationTableWalletId: 'wallet_deposits.wallet_id',
            countrie: $countrie,
            adminDescription: 'admins_coms.description_distributer',
            description: 'Validation distributeur',
            walletCreated: 'admins_coms.created_at',
            walletAll: 'admins_coms.*',
            walletStatus: 'wallet_deposits.status',
            viewPath: 'pages.historicals.pdf_card_bank_distri',
            fileName: 'reporting_commande_distri.pdf',
        );
    }


    public function reporting_inter_distri_all_historical()
    {

        return $this->historiqueService->generateDailyAllDistributorReport(
            modelClass: AdminsCom::class,
            relationTableWallet: 'wallet_inters',
            relationWalletId: 'wallet_inters.id',
            relationAdminTableWalletId: 'admins_coms.wallet_inter_id',
            relationTableWalletId: 'wallet_inters.wallet_id',
            adminDescription: 'admins_coms.description_distributer',
            description: 'Validation distributeur',
            walletCreated: 'admins_coms.created_at',
            walletAll: 'admins_coms.*',
            walletStatus: 'wallet_inters.status',
            viewPath: 'pages.historicals.pdf_inter_distri',
            fileName: 'reporting_inter_distri.pdf',
        );
    }

    public function reporting_payment_distri_all_historical(String $code)
    {

        $countrie = Country::where('name', $code)->first();

        return $this->historiqueService->generateDailyDistributorReport(
            modelClass: AdminsCom::class,
            relationTableWallet: 'wallet_payments',
            relationWalletId: 'wallet_payments.id',
            relationAdminTableWalletId: 'admins_coms.wallet_payment_id',
            relationTableWalletId: 'wallet_payments.wallet_id',
            countrie: $countrie,
            adminDescription: 'admins_coms.description_distributer',
            description: 'Validation distributeur',
            walletCreated: 'admins_coms.created_at',
            walletAll: 'admins_coms.*',
            walletStatus: 'wallet_payments.status',
            viewPath: 'pages.historicals.pdf_payment_distri',
            fileName: 'reporting_payment_distri.pdf',
        );
    }

    public function reporting_phone_distri_all_historical(String $code)
    {

        $countrie = Country::where('name', $code)->first();

        return $this->historiqueService->generateDailyDistributorReport(
            modelClass: AdminsCom::class,
            relationTableWallet: 'wallet_phone_credits',
            relationWalletId: 'wallet_phone_credits.id',
            relationAdminTableWalletId: 'admins_coms.wallet_phone_credit_id',
            relationTableWalletId: 'wallet_phone_credits.wallet_id',
            countrie: $countrie,
            adminDescription: 'admins_coms.description_distributer',
            description: 'Validation distributeur',
            walletCreated: 'admins_coms.created_at',
            walletAll: 'admins_coms.*',
            walletStatus: 'wallet_phone_credits.status',
            viewPath: 'pages.historicals.pdf_phone_distri',
            fileName: 'reporting_phone_distri.pdf',
        );
    }

    public function reporting_transfer_distri_all_historical(String $code)
    {

        $countrie = Country::where('name', $code)->first();

        return $this->historiqueService->generateDailyDistributorReport(
            modelClass: AdminsCom::class,
            relationTableWallet: 'wallet_transactions',
            relationWalletId: 'wallet_transactions.id',
            relationAdminTableWalletId: 'admins_coms.wallet_transaction_id',
            relationTableWalletId: 'wallet_transactions.wallet_id',
            countrie: $countrie,
            adminDescription: 'admins_coms.description_distributer',
            description: 'Validation distributeur',
            walletCreated: 'admins_coms.created_at',
            walletAll: 'admins_coms.*',
            walletStatus: 'wallet_transactions.status',
            viewPath: 'pages.historicals.pdf_transfer_distri',
            fileName: 'reporting_transfers_distri.pdf',
        );
    }



    // Reporting Drawer
    public function reporting_drawer_historical()
    {

        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $bank_historicals = [];

        if ($usersCountry->is_under_distributer == true) {

            foreach (WithMerchant::where('sid_user_id', $usersCountry->id)->whereDate('created_at',  $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }


            $account_valeurs = WithMerchant::where('sid_user_id', $usersCountry->id)->whereDate('created_at',  $convertions)
                ->count();

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_drawser', compact('bank_historicals', 'account_valeurs'))->setOptions(['defaultFont' => 'sans-serif']);

            return $pdf->download('reporting_drawers.pdf');
        } elseif ($usersCountry->is_distributer == true) {

            foreach (WithMerchant::whereDate('created_at',  $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }


            $account_valeurs = WithMerchant::whereDate('created_at',  $convertions)
                ->count();

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_drawser', compact('bank_historicals', 'account_valeurs'))->setOptions(['defaultFont' => 'sans-serif']);

            return $pdf->download('reporting_drawers.pdf');
        } elseif ($usersCountry->is_admin == true) {
            foreach (WithMerchant::whereDate('created_at',  $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }


            $account_valeurs = WithMerchant::whereDate('created_at',  $convertions)
                ->count();

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_drawser', compact('bank_historicals', 'account_valeurs'))->setOptions(['defaultFont' => 'sans-serif']);

            return $pdf->download('reporting_drawers.pdf');
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_transfer()
    {

        return view('pages.historicals.under_distributer.browse_transfer');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_paiement()
    {

        return view('pages.historicals.under_distributer.browse_paiement');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_virement()
    {

        return view('pages.historicals.under_distributer.browse_virement');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_inter()
    {

        return view('pages.historicals.under_distributer.browse_inter');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_carte()
    {

        return view('pages.historicals.under_distributer.browse_carte');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_phone()
    {

        return view('pages.historicals.under_distributer.browse_phone');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_banks()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }

        return view('pages.commissions.banks');
    }

    public function browse_transactions_mooney_caf_civ()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }

        return view('pages.commissions.transactions.centre_afrique_civ');
    }

    public function browse_transactions_inter_caf_civ()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }

        return view('pages.commissions.transactions.centre_afrique_inter_civ');
    }


    public function browse_transactions_civ_caf()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }

        return view('pages.commissions.transactions.civ_inter_centre_afrique');
    }

    public function browse_transactions_mooney_fr_civ()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }

        return view('pages.commissions.transactions.france_civ');
    }

    public function browse_transactions_inter_fr_civ()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }

        return view('pages.commissions.transactions.france_inter_civ');
    }

    public function browse_transactions_inter_marco_civ()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }

        return view('pages.commissions.transactions.maroc_inter_civ');
    }

    public function browse_transactions_inter_tunisie_civ()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }

        return view('pages.commissions.transactions.tunisie_inter_civ');
    }

    public function browse_transactions_civ_fr()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }

        return view('pages.commissions.transactions.civ_inter_fance');
    }
    public function country_amounts(Request $request)
    {
        $country_ids = $request->country_ids;
        return view('pages.commissions.costs.payment_cost_amount', compact('country_ids'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_banks()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('modifier-cautions')) {
            abort(403);
        }
        $countries = Country::where('robus', 1)
            ->get();

        return view('pages.commissions.add_bank', compact('countries'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit_banks()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('modifier-cautions')) {
            abort(403);
        }

        return view('pages.commissions.edit');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store_banks()
    {

        if (sabdyWebVerify()) {
            return view('pages.commissions.banks');
        } else {
            abort(403);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_deposits()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.deposits');
    }

    public function browse_virements()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.virements');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_bankSends()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.banksends');
    }


    /**
     * Display a listing of the resource.
     *p
     * @return \Illuminate\Http\Response
     */
    public function browse_inters()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.inters');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_payments()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.payments');
    }

    public function add_payments()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        $countries = Country::where('robus', 1)
            ->get();
        return view('pages.commissions.add_payments', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_payments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Veuillez renseigner tous les champ requis !')
                ->withInput();
        }

        // try {

        $payment_cost = new PaymentCost();
        $payment_cost->name = $request->name;
        $payment_cost->description  = $request->description;
        $payment_cost->created_by = Auth::guard('web')->user()->id;
        $payment_cost->status = true;
        $payment_cost->payment_type = $request->payment_type;

        if ($payment_cost->save()) {

            foreach ($request->country_id as $key => $country_s_id) {

                $country_service = new PaymentCountrie;
                $country_service->payment_cost_id = $payment_cost->id;
                $country_service->country_id = $country_s_id;
                $country_service->amount = $request['amount_' . $country_s_id];
                $root_countrie = Country::findOrFail($country_s_id);

                $country_service->save();

                $root_countrie->save();
            }

            $root_countrie->save();

            return redirect(route('commissions.browse_payments'))->with('status', 'Payement ajouter avec succès');
        }
        // } catch (\Throwable $th) {
        //    return back()
        //       ->withErrors($validator)
        //       ->with('color', 'danger')
        //       ->with('status', 'Une erreure est survenue')
        //       ->withInput();
        // }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_phones()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.phone_credits');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_reloads()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.reloads');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_transactions()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.transactions');
    }

    public function browse_companies()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.browse_companies');
    }

    public function browse_onexbets()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.onexbets');
    }

    public function browse_versement_banks()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.versement_banks');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_orders()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.orderCards');
    }

    public function country_costs(Request $request)
    {
        $country_ids = $request->country_ids;
        return view('pages.commissions.costs.countrys_cost', compact('country_ids'));
    }

    public function browse_mobile_money()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.mobile_moneys');
    }

    public function browse_centre_africa()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.browse_centre_africa');
    }

    public function browse_visitors()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.browse_visitors');
    }

    public function browse_europe()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.browse_europe');
    }

    public function browse_maghredb()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.browse_maghredb');
    }

    public function browse_usa()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.browse_usa');
    }

    public function browse_asie()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.browse_asie');
    }

    public function browse_east()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.browse_east');
    }

    public function browse_civ()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.browse_civ');
    }
}
