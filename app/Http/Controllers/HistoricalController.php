<?php

namespace App\Http\Controllers;

use App\Models\WithMerchant;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use App\Http\Services\HistoriqueService;
use App\Models\Wallets\WalletDjamo;
use App\Models\Wallets\WalletPush;
use App\Models\Wallets\WalletTresortMoney;
use App\Models\Wallets\WalletVersement;

class HistoricalController extends Controller
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

    public function browse_reload()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('historiques')) {
            abort(403);
        }

        return view('pages.historicals.browse_reload');
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
            viewPath: 'pages.historicals.pdf_bank',
            fileName: 'reporting_virement.pdf',
        );
    }

    public function reporting_reload_historical()
    {

        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $bank_historicals = [];
        if ($usersCountry->is_under_distributer == true) {

            foreach (
                WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $usersCountry->country->id)
                    ->where('wallet_reloads.created_by', $usersCountry->id)
                    ->whereDate('wallet_reloads.created_at', $convertions)->select('wallet_reloads.*')->get() as $bank
            ) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_reloads.created_by', $usersCountry->id)
                ->whereDate('wallet_reloads.created_at', $convertions)->where('wallet_reloads.status', 'PENDING')->select('wallet_reloads.*')->count();

            $account_success = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_reloads.created_by', $usersCountry->id)
                ->whereDate('wallet_reloads.created_at', $convertions)->where('wallet_reloads.status', 'SUCCESS')->select('wallet_reloads.*')->count();

            $account_errors = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_reloads.created_by', $usersCountry->id)
                ->whereDate('wallet_reloads.created_at', $convertions)->where('wallet_reloads.status', 'ERROR')->select('wallet_reloads.*')->count();

            $pdf = Pdf::loadView('pages.historicals.pdf_reload', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_rechargment.pdf');
        } elseif ($usersCountry->is_distributer == true) {

            foreach (
                WalletReload::where('updated_by', $usersCountry->id)
                    ->whereDate('created_at', $convertions)->get() as $bank
            ) {
                $bank_historicals[] = $bank;
            }


            $account_penddings = WalletReload::where('updated_by', $usersCountry->id)
                ->whereDate('created_at', $convertions)->where('status', 'PENDING')->count();

            $account_success = WalletReload::where('updated_by', $usersCountry->id)
                ->whereDate('created_at', $convertions)->where('status', 'SUCCESS')->count();

            $account_errors = WalletReload::where('updated_by', $usersCountry->id)
                ->whereDate('created_at', $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.pdf_reload', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_rechargment.pdf');
        } elseif ($usersCountry->is_admin == true) {
            foreach (WalletReload::whereDate('created_at', $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletReload::whereDate('created_at', $convertions)->where('status', 'PENDING')->count();

            $account_success = WalletReload::whereDate('created_at', $convertions)->where('status', 'SUCCESS')->count();

            $account_errors = WalletReload::whereDate('created_at', $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.pdf_reload', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_rechargment.pdf');
        }
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
            viewPath: 'pages.historicals.pdf_card_bank',
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
            viewPath: 'pages.historicals.pdf_inter',
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
            viewPath: 'pages.historicals.pdf_payment',
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
            viewPath: 'pages.historicals.pdf_phone',
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
            viewPath: 'pages.historicals.pdf_transfer',
            fileName: 'reporting_transfers.pdf',
        );
    }

    public function reporting_push_historical()
    {

        return $this->historiqueService->generateDailyReport(
            modelClass: WalletPush::class,
            relationTableWallet: 'wallet_pushes.wallet_id',
            walletCreatedBy: 'wallet_pushes.created_by',
            walletAll: 'wallet_pushes.*',
            walletCreated: 'wallet_pushes.created_at',
            walletStatus: 'wallet_pushes.status',
            viewPath: 'pages.historicals.transactionsDays.pdf_auther_transactions',
            fileName: 'reporting_pushs.pdf',
        );
    }

    public function reporting_versment_historical()
    {

        return $this->historiqueService->generateDailyReport(
            modelClass: WalletVersement::class,
            relationTableWallet: 'wallet_versements.wallet_id',
            walletCreatedBy: 'wallet_versements.created_by',
            walletAll: 'wallet_versements.*',
            walletCreated: 'wallet_versements.created_at',
            walletStatus: 'wallet_versements.status',
            viewPath: 'pages.historicals.transactionsDays.pdf_versment_transactions',
            fileName: 'reporting_versment.pdf',
        );
    }

    public function reporting_djamo_historical()
    {

        return $this->historiqueService->generateDailyReport(
            modelClass: WalletDjamo::class,
            relationTableWallet: 'wallet_djamos.wallet_id',
            walletCreatedBy: 'wallet_djamos.created_by',
            walletAll: 'wallet_djamos.*',
            walletCreated: 'wallet_djamos.created_at',
            walletStatus: 'wallet_djamos.status',
            viewPath: 'pages.historicals.transactionsDays.pdf_versment_transactions',
            fileName: 'reporting_djamo.pdf',
        );
    }

    public function reporting_tresort_historical()
    {

        return $this->historiqueService->generateDailyReport(
            modelClass: WalletTresortMoney::class,
            relationTableWallet: 'wallet_tresort_money.wallet_id',
            walletCreatedBy: 'wallet_tresort_money.created_by',
            walletAll: 'wallet_tresort_money.*',
            walletCreated: 'wallet_tresort_money.created_at',
            walletStatus: 'wallet_tresort_money.status',
            viewPath: 'pages.historicals.transactionsDays.pdf_tresort_transactions',
            fileName: 'reporting_tresort_money.pdf',
        );
    }

    public function reporting_transfer_historical_company()
    {


        return $this->historiqueService->generateDailyFirmReport(
            modelClass: WalletTransaction::class,
            relationTableWallet: 'wallet_transactions.wallet_id',
            walletCreatedBy: 'wallet_transactions.created_by',
            walletAll: 'wallet_transactions.*',
            walletCreated: 'wallet_transactions.created_at',
            walletStatus: 'wallet_transactions.status',
            viewPath: 'pages.historicals.pdf_transfer_company',
            fileName: 'reporting_transfers.pdf',
        );
    }

    public function reporting_reload_historical_company()
    {

        $usersCountry  =   firm();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $bank_historicals = [];


        foreach (
            WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_reloads.created_by', $usersCountry->id)
                ->whereDate('wallet_reloads.created_at', $convertions)->select('wallet_reloads.*')->get() as $bank
        ) {
            $bank_historicals[] = $bank;
        }

        $account_penddings = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', $usersCountry->country->id)
            ->where('wallet_reloads.created_by', $usersCountry->id)
            ->whereDate('wallet_reloads.created_at', $convertions)->where('wallet_reloads.status', 'PENDING')->select('wallet_reloads.*')->count();

        $account_success = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', $usersCountry->country->id)
            ->where('wallet_reloads.created_by', $usersCountry->id)
            ->whereDate('wallet_reloads.created_at', $convertions)->where('wallet_reloads.status', 'SUCCESS')->select('wallet_reloads.*')->count();

        $account_errors = WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', $usersCountry->country->id)
            ->where('wallet_reloads.created_by', $usersCountry->id)
            ->whereDate('wallet_reloads.created_at', $convertions)->where('wallet_reloads.status', 'ERROR')->select('wallet_reloads.*')->count();

        $pdf = Pdf::loadView('pages.historicals.pdf_reload', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download('reporting_rechargment.pdf');
    }

    public function searchByReloadsDate(Request $request)
    {

        $this->validate($request, [
            'search_begin' => 'required|date',
            'search_end' => 'required|date|after_or_equal:search_begin',
        ]);


        $searchDateBegin = $request->input('search_begin');

        $searchDateEnd = $request->input('search_end');

        $bank_historicals = $this->getTransactionDate(WalletReload::class, $searchDateBegin, $searchDateEnd);

        $account_penddings =  $this->getTransactionCount(WalletReload::class, 'PENDING', $searchDateBegin, $searchDateEnd);;

        $account_success = $this->getTransactionCount(WalletReload::class, 'SUCCESS', $searchDateBegin, $searchDateEnd);

        $account_errors = $this->getTransactionCount(WalletReload::class, 'ERROR', $searchDateBegin, $searchDateEnd);

        $amount_total = $this->walletTransactionsDays(WalletReload::class, $searchDateBegin, $searchDateEnd);

        $pdf = Pdf::loadView('pages.historicals.pdf_reload', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download('reporting_rechargment.pdf');
    }

    public function getTransactionDate($wallets, $searchDateBegin, $searchDateEnd)
    {
        return $wallets::whereBetween('created_at', [$searchDateBegin, $searchDateEnd])->get();
    }

    public function getTransactionCount($wallets, $status, $searchDateBegin, $searchDateEnd)
    {
        return $wallets::whereBetween('created_at', [$searchDateBegin, $searchDateEnd])->where('status', $status)->count();
    }

    public function walletTransactionsDays($wallets, $searchDateBegin, $searchDateEnd)
    {

        $data = [];

        foreach ($wallets::whereBetween('created_at', [$searchDateBegin, $searchDateEnd])->where('wallet_id', firm()->wallet->wallet_id)->where('status', 'SUCCESS')->get() as $bank) {

            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

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

            $pdf = Pdf::loadView('pages.historicals.pdf_drawser', compact('bank_historicals', 'account_valeurs'))->setOptions(['defaultFont' => 'sans-serif']);

            return $pdf->download('reporting_drawers.pdf');
        } elseif ($usersCountry->is_distributer == true) {

            foreach (WithMerchant::whereDate('created_at',  $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }


            $account_valeurs = WithMerchant::whereDate('created_at',  $convertions)
                ->count();

            $pdf = Pdf::loadView('pages.historicals.pdf_drawser', compact('bank_historicals', 'account_valeurs'))->setOptions(['defaultFont' => 'sans-serif']);

            return $pdf->download('reporting_drawers.pdf');
        } elseif ($usersCountry->is_admin == true) {
            foreach (WithMerchant::whereDate('created_at',  $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }


            $account_valeurs = WithMerchant::whereDate('created_at',  $convertions)
                ->count();

            $pdf = Pdf::loadView('pages.historicals.pdf_drawser', compact('bank_historicals', 'account_valeurs'))->setOptions(['defaultFont' => 'sans-serif']);

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

        return view('firms.pages.browse_transfer');
    }

    public function browse_reloads()
    {
        return view('firms.pages.browse_reloads');
    }

    public function browse_transferfirm()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('historiques')) {
            abort(403);
        }
        return view('pages.historicals.browse_transfer');
    }

    public function browse_transfert_visitors()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('historiques')) {
            abort(403);
        }
        return view('pages.historicals.visitors.browse_transfert_visitors');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_paiement()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('historiques')) {
            abort(403);
        }

        return view('pages.historicals.browse_paiement');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_virement()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('historiques')) {
            abort(403);
        }

        return view('pages.historicals.browse_virement');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_inter()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('historiques')) {
            abort(403);
        }

        return view('pages.historicals.browse_inter');
    }

    public function browse_inter_visitors()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('historiques')) {
            abort(403);
        }

        return view('pages.historicals.visitors.browse_inter_visitors');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_carte()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('historiques')) {
            abort(403);
        }

        return view('pages.historicals.browse_carte');
    }


    public function browse_carte_visitors()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('historiques')) {
            abort(403);
        }


        return view('pages.historicals.visitors.browse_carte_visitors');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_phone()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('historiques')) {
            abort(403);
        }

        return view('pages.historicals.browse_phone');
    }

    public function browse_phone_visitors()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('historiques')) {
            abort(403);
        }

        return view('pages.historicals.visitors.browse_phone_visitors');
    }

    public function browse_waves()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('historiques')) {
            abort(403);
        }

        return view('pages.historicals.browse_wave');
    }

    public function browse_xbets()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('historiques')) {
            abort(403);
        }

        return view('pages.historicals.browse_xbets');
    }

    public function browse_versments()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('historiques')) {
            abort(403);
        }

        return view('pages.historicals.browse_versments');
    }

    public function browse_push()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('historiques')) {
            abort(403);
        }

        return view('pages.historicals.browse_push');
    }

    public function browse_djamo()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('historiques')) {
            abort(403);
        }

        return view('pages.historicals.browse_djamo');
    }

    public function browse_tresort()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('historiques')) {
            abort(403);
        }

        return view('pages.historicals.browse_tresort');
    }


    public function browse_xbets_visitors()
    {
        if (sabdyWebVerify()) {
            return view('pages.historicals.visitors.browse_xbets_visitors');
        } else {
            abort(403);
        }
    }


    public function browse_drawer()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('historiques')) {
            abort(403);
        }
        return view('pages.historicals.browse_drawer');
    }
}
