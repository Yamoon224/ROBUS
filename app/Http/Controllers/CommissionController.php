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

class CommissionController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

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


        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $bank_historicals = [];

        if ($usersCountry->is_under_distributer == true) {

            foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_banks.created_by', $usersCountry->id)
                ->whereDate('wallet_banks.created_at', $convertions)->select('wallet_banks.*')->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_banks.created_by', $usersCountry->id)
                ->whereDate('wallet_banks.created_at', $convertions)->where('wallet_banks.status', 'PENDING')->select('wallet_banks.*')->count();

            $account_success = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_banks.created_by', $usersCountry->id)
                ->whereDate('wallet_banks.created_at', $convertions)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->count();

            $account_errors = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_banks.created_by', $usersCountry->id)
                ->whereDate('wallet_banks.created_at', $convertions)->where('wallet_banks.status', 'ERROR')->select('wallet_banks.*')->count();

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_bank', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_virement.pdf');
        } elseif ($usersCountry->is_distributer == true) {

            foreach (WalletBank::where('updated_by', $usersCountry->id)
                ->whereDate('created_at', $convertions)
                ->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletBank::where('updated_by', $usersCountry->id)
                ->whereDate('created_at', $convertions)->where('status', 'PENDING')->count();

            $account_success = WalletBank::where('updated_by', $usersCountry->id)
                ->whereDate('created_at', $convertions)->where('status', 'SUCCESS')->count();

            $account_errors = WalletBank::where('updated_by', $usersCountry->id)
                ->whereDate('created_at', $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_bank', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_virement.pdf');
        } elseif ($usersCountry->is_admin == true) {

            foreach (WalletBank::whereDate('created_at',  $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletBank::whereDate('created_at', $convertions)->where('status', 'PENDING')->count();

            $account_success = WalletBank::whereDate('created_at', $convertions)->where('status', 'SUCCESS')->count();

            $account_errors = WalletBank::whereDate('created_at', $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_bank', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_virement.pdf');
        }
    }

    public function reporting_reload_historical()
    {

        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $bank_historicals = [];
        if ($usersCountry->is_under_distributer == true) {

            foreach (WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_reloads.created_by', $usersCountry->id)
                ->whereDate('wallet_reloads.created_at', $convertions)->select('wallet_reloads.*')->get() as $bank) {
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

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_reload', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_rechargment.pdf');
        } elseif ($usersCountry->is_distributer == true) {

            foreach (WalletReload::where('updated_by', $usersCountry->id)
                ->whereDate('created_at', $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }


            $account_penddings = WalletReload::where('updated_by', $usersCountry->id)
                ->whereDate('created_at', $convertions)->where('status', 'PENDING')->count();

            $account_success = WalletReload::where('updated_by', $usersCountry->id)
                ->whereDate('created_at', $convertions)->where('status', 'SUCCESS')->count();

            $account_errors = WalletReload::where('updated_by', $usersCountry->id)
                ->whereDate('created_at', $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_reload', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_rechargment.pdf');
        } elseif ($usersCountry->is_admin == true) {
            foreach (WalletReload::whereDate('created_at', $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletReload::whereDate('created_at', $convertions)->where('status', 'PENDING')->count();

            $account_success = WalletReload::whereDate('created_at', $convertions)->where('status', 'SUCCESS')->count();

            $account_errors = WalletReload::whereDate('created_at', $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_reload', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_rechargment.pdf');
        }
    }

    public function reporting_card_historical()
    {

        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $bank_historicals = [];
        if ($usersCountry->is_under_distributer == true) {

            foreach (WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_deposits.created_by', $usersCountry->id)
                ->whereDate('wallet_deposits.created_at',  $convertions)->select('wallet_deposits.*')->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_deposits.created_by', $usersCountry->id)
                ->whereDate('wallet_deposits.created_at',  $convertions)->where('wallet_deposits.status', 'PENDING')->select('wallet_deposits.*')->count();

            $account_success = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_deposits.created_by', $usersCountry->id)
                ->whereDate('wallet_deposits.created_at',  $convertions)->where('wallet_deposits.status', 'SUCCESS')->select('wallet_deposits.*')->count();

            $account_errors = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_deposits.created_by', $usersCountry->id)
                ->whereDate('wallet_deposits.created_at',  $convertions)->where('wallet_deposits.status', 'ERROR')->select('wallet_deposits.*')->count();

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_card_bank', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_commande.pdf');
        } elseif ($usersCountry->is_distributer == true) {
            foreach (WalletDeposit::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletDeposit::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'PENDING')->count();

            $account_success = WalletDeposit::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'SUCCESS')->count();

            $account_errors = WalletDeposit::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_card_bank', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_commande.pdf');
        } elseif ($usersCountry->is_admin == true) {
            foreach (WalletDeposit::whereDate('created_at',  $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletDeposit::whereDate('created_at',  $convertions)->where('status', 'PENDING')->count();

            $account_success = WalletDeposit::whereDate('created_at',  $convertions)->where('status', 'SUCCESS')->count();

            $account_errors = WalletDeposit::whereDate('created_at',  $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_card_bank', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_commande.pdf');
        }
    }


    public function reporting_inter_historical()
    {

        $usersCountry  =   Auth::guard('web')->user();

        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $bank_historicals = [];

        if ($usersCountry->is_under_distributer == true) {

            foreach (WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_inters.created_by', $usersCountry->id)
                ->whereDate('wallet_inters.created_at',  $convertions)->select('wallet_inters.*')->get() as $bank) {
                $bank_historicals[] = $bank;
            }
            $users_countrie = Auth::guard('web')->user()->wallet->currencie->code;

            $account_penddings = WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_inters.created_by', $usersCountry->id)
                ->whereDate('wallet_inters.created_at',  $convertions)->where('wallet_inters.status', 'PENDING')->select('wallet_inters.*')->count();

            $account_success = WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_inters.created_by', $usersCountry->id)
                ->whereDate('wallet_inters.created_at',  $convertions)->where('wallet_inters.status', 'SUCCESS')->select('wallet_inters.*')->count();

            $account_errors = WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_inters.created_by', $usersCountry->id)
                ->whereDate('wallet_inters.created_at',  $convertions)->where('wallet_inters.status', 'ERROR')->select('wallet_inters.*')->count();


            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_inter', compact('bank_historicals', 'users_countrie', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_inter.pdf');
        } elseif ($usersCountry->is_distributer == true) {

            foreach (WalletInter::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }
            $users_countrie = Auth::guard('web')->user()->wallet->currencie->code;

            $account_penddings = WalletInter::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'PENDING')->count();

            $account_success = WalletInter::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'SUCCESS')->count();

            $account_errors = WalletInter::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_inter', compact('bank_historicals', 'users_countrie', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_inter.pdf');
        } elseif ($usersCountry->is_admin == true) {
            foreach (WalletInter::whereDate('created_at',  $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $users_countrie = Auth::guard('web')->user()->wallet->currencie->code;

            $account_penddings = WalletInter::whereDate('created_at',  $convertions)->where('status', 'PENDING')->count();

            $account_success = WalletInter::whereDate('created_at',  $convertions)->where('status', 'SUCCESS')->count();

            $account_errors = WalletInter::whereDate('created_at',  $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_inter', compact('bank_historicals', 'users_countrie', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_inter.pdf');
        }
    }

    public function reporting_payment_historical()
    {

        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $bank_historicals = [];

        if ($usersCountry->is_under_distributer == true) {
            foreach (WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_payments.created_by', $usersCountry->id)
                ->whereDate('wallet_payments.created_at',  $convertions)->select('wallet_payments.*')->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_payments.created_by', $usersCountry->id)
                ->whereDate('wallet_payments.created_at',  $convertions)->where('wallet_payments.status', 'PENDING')->select('wallet_payments.*')->count();

            $account_success = WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_payments.created_by', $usersCountry->id)
                ->whereDate('wallet_payments.created_at',  $convertions)->where('wallet_payments.status', 'SUCCESS')->select('wallet_payments.*')->count();


            $account_errors = WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_payments.created_by', $usersCountry->id)
                ->whereDate('wallet_payments.created_at',  $convertions)->where('wallet_payments.status', 'ERROR')->select('wallet_payments.*')->count();

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_payment', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_payment.pdf');
        } elseif ($usersCountry->is_distributer == true) {

            foreach (WalletPayment::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletPayment::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'PENDING')->count();


            $account_success = WalletPayment::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'SUCCESS')->count();


            $account_errors = WalletPayment::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_payment', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_payment.pdf');
        } elseif ($usersCountry->is_admin == true) {

            foreach (WalletPayment::whereDate('created_at',  $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }


            $account_penddings = WalletPayment::whereDate('created_at',  $convertions)->where('status', 'PENDING')->count();


            $account_success = WalletPayment::whereDate('created_at',  $convertions)->where('status', 'SUCCESS')->count();


            $account_errors = WalletPayment::whereDate('created_at',  $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_payment', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_payment.pdf');
        }
    }

    public function reporting_phone_historical()
    {

        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $bank_historicals = [];

        if ($usersCountry->is_under_distributer == true) {

            foreach (WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_phone_credits.created_by', $usersCountry->id)
                ->whereDate('wallet_phone_credits.created_at',  $convertions)->select('wallet_phone_credits.*')->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_phone_credits.created_by', $usersCountry->id)
                ->whereDate('wallet_phone_credits.created_at',  $convertions)->where('wallet_phone_credits.status', 'PENDING')->select('wallet_phone_credits.*')->count();

            $account_success = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_phone_credits.created_by', $usersCountry->id)
                ->whereDate('wallet_phone_credits.created_at',  $convertions)->where('wallet_phone_credits.status', 'SUCCESS')->select('wallet_phone_credits.*')->count();

            $account_errors = WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_phone_credits.created_by', $usersCountry->id)
                ->whereDate('wallet_phone_credits.created_at',  $convertions)->where('wallet_phone_credits.status', 'ERROR')->select('wallet_phone_credits.*')->count();


            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_phone', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_phone.pdf');
        } elseif ($usersCountry->is_distributer == true) {
            foreach (WalletPhoneCredit::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletPhoneCredit::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'PENDING')->count();

            $account_success = WalletPhoneCredit::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'SUCCESS')->count();

            $account_errors = WalletPhoneCredit::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_phone', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_phone.pdf');
        } elseif ($usersCountry->is_admin == true) {
            foreach (WalletPhoneCredit::whereDate('created_at',  $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletPhoneCredit::whereDate('created_at',  $convertions)->where('status', 'PENDING')->count();

            $account_success = WalletPhoneCredit::whereDate('created_at',  $convertions)->where('status', 'SUCCESS')->count();

            $account_errors = WalletPhoneCredit::whereDate('created_at',  $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_phone', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_phone.pdf');
        }
    }

    public function reporting_transfer_historical()
    {

        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $bank_historicals = [];

        if ($usersCountry->is_under_distributer == true) {

            foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_transactions.created_by', $usersCountry->id)
                ->whereDate('wallet_transactions.created_at',  $convertions)
                ->select('wallet_transactions.*')->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_transactions.created_by', $usersCountry->id)
                ->whereDate('wallet_transactions.created_at',  $convertions)
                ->where('wallet_transactions.status', 'PENDING')
                ->select('wallet_transactions.*')->count();
            $account_success = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_transactions.created_by', $usersCountry->id)
                ->whereDate('wallet_transactions.created_at',  $convertions)
                ->where('wallet_transactions.status', 'SUCCESS')
                ->select('wallet_transactions.*')->count();

            $account_errors = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_transactions.created_by', $usersCountry->id)
                ->whereDate('wallet_transactions.created_at',  $convertions)
                ->where('wallet_transactions.status', 'ERROR')
                ->select('wallet_transactions.*')->count();

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_transfer', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_transfers.pdf');
        } elseif ($usersCountry->is_distributer == true) {

            foreach (WalletTransaction::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletTransaction::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)
                ->where('status', 'PENDING')
                ->count();
            $account_success = WalletTransaction::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)
                ->where('status', 'SUCCESS')
                ->count();

            $account_errors = WalletTransaction::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)
                ->where('status', 'ERROR')
                ->count();

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_transfer', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_transfers.pdf');
        } elseif ($usersCountry->is_admin == true) {
            foreach (WalletTransaction::whereDate('created_at',  $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletTransaction::whereDate('created_at',  $convertions)
                ->where('status', 'PENDING')
                ->count();
            $account_success = WalletTransaction::whereDate('created_at',  $convertions)
                ->where('status', 'SUCCESS')
                ->count();

            $account_errors = WalletTransaction::whereDate('created_at',  $convertions)
                ->where('status', 'ERROR')
                ->count();

            $pdf = Pdf::loadView('pages.historicals.under_distributer.pdf_transfer', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_transfers.pdf');
        }
    }

    // Reposting valeur distributers
    public function reporting_distri_historical(String $code)
    {

        $countrie = Country::where('name', $code)->first();

        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $bank_historicals = [];

        if ($usersCountry->is_admin == true) {

            $valeur_results =  AdminsCom::join('wallet_banks', 'wallet_banks.id', 'admins_coms.wallet_bank_id')
                ->join('wallets', 'wallets.id', 'wallet_banks.wallet_id')
                ->where('wallets.country_id', $countrie->id)
                ->where('admins_coms.description_distributer', 'Validation distributeur')
                ->whereDate('admins_coms.created_at',  $convertions)
                ->select('admins_coms.*')
                ->get();

            foreach ($valeur_results as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = $this->getCountWalletBankPending($countrie->id);

            $account_success = $this->getCountWalletBankSuccess($countrie->id);

            $account_errors = $this->getCountWalletBankError($countrie->id);

            $pdf = Pdf::loadView('pages.historicals.pdf_bank_distri', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_virement_distri.pdf');
        }
    }

    public function reporting_reload_distri_historical(String $code)
    {

        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $countrie = Country::where('name', $code)->first();

        $bank_historicals = [];

        if ($usersCountry->is_admin == true) {

            $valeur_results =  AdminsCom::join('wallet_reloads', 'wallet_reloads.id', 'admins_coms.wallet_reload_id')
                ->join('wallets', 'wallets.id', 'wallet_reloads.wallet_id')
                ->where('wallets.country_id', $countrie->id)
                ->where('admins_coms.description_distributer', 'Validation distributeur')
                ->whereDate('admins_coms.created_at',  $convertions)
                ->select('admins_coms.*')
                ->get();

            foreach ($valeur_results as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = $this->getCountWalletReloadPending($countrie->id);

            $account_success = $this->getCountWalletReloadSuccess($countrie->id);

            $account_errors = $this->getCountWalletReloadError($countrie->id);

            $pdf = Pdf::loadView('pages.historicals.pdf_reload_distri', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_rechargment_distri.pdf');
        }
    }

    public function reporting_card_distri_historical(String $code)
    {

        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $bank_historicals = [];
        $countrie = Country::where('name', $code)->first();

        if ($usersCountry->is_admin == true) {
           
            $valeur_results =  AdminsCom::join('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id')
                ->join('wallets', 'wallets.id', 'wallet_deposits.wallet_id')
                ->where('wallets.country_id', $countrie->id)
                ->where('admins_coms.description_distributer', 'Validation distributeur')
                ->whereDate('created_at',  $convertions)
                ->select('admins_coms.*')
                ->get();

            foreach ($valeur_results as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = $this->getCountWalletDepositPending($countrie->id);

            $account_success = $this->getCountWalletDepositSuccess($countrie->id);

            $account_errors = $this->getCountWalletDepositError($countrie->id);

            $pdf = Pdf::loadView('pages.historicals.pdf_card_bank_distri', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_commande_distri.pdf');
        }
    }


    public function reporting_inter_distri_historical()
    {

        $usersCountry  =   Auth::guard('web')->user();

        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $bank_historicals = [];

         if ($usersCountry->is_admin == true) {
           
            $valeur_results =  AdminsCom::join('wallet_inters', 'wallet_inters.id', 'admins_coms.wallet_inter_id')
            ->join('wallets', 'wallets.id', 'wallet_inters.wallet_id')
            // ->where('wallets.country_id', $countrie->id)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->whereDate('admins_coms.created_at',  $convertions)
            ->select('admins_coms.*')
            ->get();

        foreach ($valeur_results as $bank) {
            $bank_historicals[] = $bank;
        }

        $account_penddings = $this->getCountWalletInterPending();

        $account_success = $this->getCountWalletInterSuccess();

        $account_errors = $this->getCountWalletInterError();


            $pdf = Pdf::loadView('pages.historicals.pdf_inter_distri', compact('bank_historicals', 'users_countrie', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_inter_distri.pdf');
        }
    }

    public function reporting_payment_distri_historical(String $code)
    {

        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $bank_historicals = [];
        $countrie = Country::where('name', $code)->first();

        if ($usersCountry->is_admin == true) {

            $valeur_results =  AdminsCom::join('wallet_payments', 'wallet_payments.id', 'admins_coms.wallet_payment_id')
                ->join('wallets', 'wallets.id', 'wallet_payments.wallet_id')
                ->where('wallets.country_id', $countrie->id)
                ->where('admins_coms.description_distributer', 'Validation distributeur')
                ->whereDate('admins_coms.created_at',  $convertions)
                ->select('admins_coms.*')
                ->get();

            foreach ($valeur_results as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = $this->getCountWalletPaymentPending($countrie->id);

            $account_success = $this->getCountWalletPaymentSuccess($countrie->id);

            $account_errors = $this->getCountWalletPaymentError($countrie->id);

            $pdf = Pdf::loadView('pages.historicals.pdf_payment_distri', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_payment_distri.pdf');
        }
    }

    public function reporting_phone_distri_historical(String $code)
    {

        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $bank_historicals = [];
        $countrie = Country::where('name', $code)->first();

        if ($usersCountry->is_admin == true) {

            $valeur_results =  AdminsCom::join('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_phone_credit_id')
                ->join('wallets', 'wallets.id', 'wallet_phone_credits.wallet_id')
                ->where('wallets.country_id', $countrie->id)
                ->where('admins_coms.description_distributer', 'Validation distributeur')
                ->whereDate('admins_coms.created_at',  $convertions)
                ->select('admins_coms.*')
                ->get();

            foreach ($valeur_results as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = $this->getCountWalletPhonePending($countrie->id);

            $account_success = $this->getCountWalletPhoneSuccess($countrie->id);

            $account_errors = $this->getCountWalletPhoneError($countrie->id);

            $pdf = Pdf::loadView('pages.historicals.pdf_phone_distri', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_phone_distri.pdf');
        }
    }

    public function reporting_transfer_distri_historical(String $code)
    {

        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $bank_historicals = [];
        $countrie = Country::where('name', $code)->first();

         if ($usersCountry->is_admin == true) {
           
            $valeur_results =  AdminsCom::join('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id')
            ->join('wallets', 'wallets.id', 'wallet_transactions.wallet_id')
            ->where('wallets.country_id', $countrie->id)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->whereDate('admins_coms.created_at',  $convertions)
            ->select('admins_coms.*')
            ->get();

        foreach ($valeur_results as $bank) {
            $bank_historicals[] = $bank;
        }

        $account_penddings = $this->getCountWalletTransactionPending($countrie->id);

        $account_success = $this->getCountWalletTransactionSuccess($countrie->id);

        $account_errors = $this->getCountWalletTransactionError($countrie->id);


            $pdf = Pdf::loadView('pages.historicals.pdf_transfer_distri', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_transfers_distri.pdf');
        }
    }

    // All valeur Dawload

    public function reporting_distri_all_historical(String $code)
    {
   
        $countrie = Country::where('name', $code)->first();

        $usersCountry  =   Auth::guard('web')->user();
  
        $bank_historicals = [];


        if ($usersCountry->is_admin == true) {

            $valeur_results =  AdminsCom::join('wallet_banks', 'wallet_banks.id', 'admins_coms.wallet_bank_id')
                ->join('wallets', 'wallets.id', 'wallet_banks.wallet_id')
                ->where('wallets.country_id', $countrie->id)
                ->where('admins_coms.description_distributer', 'Validation distributeur')
                ->select('admins_coms.*')
                ->get();

            foreach ($valeur_results as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = $this->getCountWalletBankAllPending($countrie->id);

            $account_success = $this->getCountWalletBankAllSuccess($countrie->id);

            $account_errors = $this->getCountWalletBankAllError($countrie->id);

            $pdf = Pdf::loadView('pages.historicals.pdf_bank_distri', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_virement_distri.pdf');
        }
    }

    public function reporting_reload_distri_all_historical(String $code)
    {

        $usersCountry  =   Auth::guard('web')->user();
        $countrie = Country::where('name', $code)->first();

        $bank_historicals = [];

        if ($usersCountry->is_admin == true) {

            $valeur_results =  AdminsCom::join('wallet_reloads', 'wallet_reloads.id', 'admins_coms.wallet_reload_id')
                ->join('wallets', 'wallets.id', 'wallet_reloads.wallet_id')
                ->where('wallets.country_id', $countrie->id)
                ->where('admins_coms.description_distributer', 'Validation distributeur')
                ->select('admins_coms.*')
                ->get();

            foreach ($valeur_results as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = $this->getCountWalletReloadAllPending($countrie->id);

            $account_success = $this->getCountWalletReloadAllSuccess($countrie->id);

            $account_errors = $this->getCountWalletReloadAllError($countrie->id);

            $pdf = Pdf::loadView('pages.historicals.pdf_reload_distri', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_rechargment_distri.pdf');
        }
    }

    public function reporting_card_distri_all_historical(String $code)
    {

        $usersCountry  =   Auth::guard('web')->user();
        $bank_historicals = [];
        $countrie = Country::where('name', $code)->first();

        if ($usersCountry->is_admin == true) {
           
            $valeur_results =  AdminsCom::join('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id')
                ->join('wallets', 'wallets.id', 'wallet_deposits.wallet_id')
                ->where('wallets.country_id', $countrie->id)
                ->where('admins_coms.description_distributer', 'Validation distributeur')
                ->select('admins_coms.*')
                ->get();

            foreach ($valeur_results as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = $this->getCountWalletDepositAllPending($countrie->id);

            $account_success = $this->getCountWalletDepositAllSuccess($countrie->id);

            $account_errors = $this->getCountWalletDepositAllError($countrie->id);

            $pdf = Pdf::loadView('pages.historicals.pdf_card_bank_distri', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_commande_distri.pdf');
        }
    }


    public function reporting_inter_distri_all_historical()
    {

        $usersCountry  =   Auth::guard('web')->user();


        $bank_historicals = [];

         if ($usersCountry->is_admin == true) {
           
            $valeur_results =  AdminsCom::join('wallet_inters', 'wallet_inters.id', 'admins_coms.wallet_inter_id')
            ->join('wallets', 'wallets.id', 'wallet_inters.wallet_id')
            // ->where('wallets.country_id', $countrie->id)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->get();

        foreach ($valeur_results as $bank) {
            $bank_historicals[] = $bank;
        }

        $account_penddings = $this->getCountWalletInterAllPending();

        $account_success = $this->getCountWalletInterAllSuccess();

        $account_errors = $this->getCountWalletInterAllError();


            $pdf = Pdf::loadView('pages.historicals.pdf_inter_distri', compact('bank_historicals', 'users_countrie', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_inter_distri.pdf');
        }
    }

    public function reporting_payment_distri_all_historical(String $code)
    {

        $usersCountry  =   Auth::guard('web')->user();
        $bank_historicals = [];
        $countrie = Country::where('name', $code)->first();

        if ($usersCountry->is_admin == true) {

            $valeur_results =  AdminsCom::join('wallet_payments', 'wallet_payments.id', 'admins_coms.wallet_payment_id')
                ->join('wallets', 'wallets.id', 'wallet_payments.wallet_id')
                ->where('wallets.country_id', $countrie->id)
                ->where('admins_coms.description_distributer', 'Validation distributeur')
                ->select('admins_coms.*')
                ->get();

            foreach ($valeur_results as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = $this->getCountWalletPaymentAllPending($countrie->id);

            $account_success = $this->getCountWalletPaymentAllSuccess($countrie->id);

            $account_errors = $this->getCountWalletPaymentAllError($countrie->id);

            $pdf = Pdf::loadView('pages.historicals.pdf_payment_distri', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_payment_distri.pdf');
        }
    }

    public function reporting_phone_distri_all_historical(String $code)
    {

        $usersCountry  =   Auth::guard('web')->user();
        $bank_historicals = [];
        $countrie = Country::where('name', $code)->first();

        if ($usersCountry->is_admin == true) {

            $valeur_results =  AdminsCom::join('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_phone_credit_id')
                ->join('wallets', 'wallets.id', 'wallet_phone_credits.wallet_id')
                ->where('wallets.country_id', $countrie->id)
                ->where('admins_coms.description_distributer', 'Validation distributeur')
                ->select('admins_coms.*')
                ->get();

            foreach ($valeur_results as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = $this->getCountWalletPhoneAllPending($countrie->id);

            $account_success = $this->getCountWalletPhoneAllSuccess($countrie->id);

            $account_errors = $this->getCountWalletPhoneAllError($countrie->id);

            $pdf = Pdf::loadView('pages.historicals.pdf_phone_distri', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_phone_distri.pdf');
        }
    }

    public function reporting_transfer_distri_all_historical(String $code)
    {

        $usersCountry  =   Auth::guard('web')->user();
        $bank_historicals = [];
        $countrie = Country::where('name', $code)->first();

         if ($usersCountry->is_admin == true) {
           
            $valeur_results =  AdminsCom::join('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id')
            ->join('wallets', 'wallets.id', 'wallet_transactions.wallet_id')
            ->where('wallets.country_id', $countrie->id)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->get();

        foreach ($valeur_results as $bank) {
            $bank_historicals[] = $bank;
        }

        $account_penddings = $this->getCountWalletTransactionAllPending($countrie->id);

        $account_success = $this->getCountWalletTransactionAllSuccess($countrie->id);

        $account_errors = $this->getCountWalletTransactionAllError($countrie->id);


            $pdf = Pdf::loadView('pages.historicals.pdf_transfer_distri', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_transfers_distri.pdf');
        }
    }

    // Valeur all Padding and Success

    public function getCountWalletInterAllSuccess()
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

    public function getCountWalletInterAllPending()
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
    public function getCountWalletInterAllError()
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

    public function getCountWalletTransactionAllSuccess($countrie)
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

    public function getCountWalletTransactionAllPending($countrie)
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
    public function getCountWalletTransactionAllError($countrie)
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
    
    public function getCountWalletPhoneAllSuccess($countrie)
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

    public function getCountWalletPhoneAllPending($countrie)
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
    public function getCountWalletPhoneAllError($countrie)
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


    public function getCountWalletPaymentAllSuccess($countrie)
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

    public function getCountWalletPaymentAllPending($countrie)
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
    public function getCountWalletPaymentAllError($countrie)
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

    public function getCountWalletDepositAllSuccess($countrie)
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

    public function getCountWalletDepositAllPending($countrie)
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
    public function getCountWalletDepositAllError($countrie)
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


    public function getCountWalletReloadAllSuccess($countrie)
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

    public function getCountWalletReloadAllPending($countrie)
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
    public function getCountWalletReloadAllError($countrie)
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

    public function getCountWalletBankAllSuccess($countrie)
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

    public function getCountWalletBankAllPending($countrie)
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
    public function getCountWalletBankAllError($countrie)
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

    // Counts valeurs
    // Count valeur of Wallets

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

    public function getCountWalletTransactionSuccess($countrie)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $results =  AdminsCom::join('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id')
            ->join('wallets', 'wallets.id', 'wallet_transactions.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->whereDate('admins_coms.created_at', $convertions)
            ->where('wallet_transactions.status', 'SUCCESS')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletTransactionPending($countrie)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $results =  AdminsCom::join('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id')
            ->join('wallets', 'wallets.id', 'wallet_transactions.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->whereDate('admins_coms.created_at', $convertions)
            ->where('wallet_transactions.status', 'PENDING')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }
    public function getCountWalletTransactionError($countrie)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $results =  AdminsCom::join('wallet_transactions', 'wallet_transactions.id', 'admins_coms.wallet_transaction_id')
            ->join('wallets', 'wallets.id', 'wallet_transactions.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->whereDate('admins_coms.created_at', $convertions)
            ->where('wallet_transactions.status', 'ERROR')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }
    
    public function getCountWalletPhoneSuccess($countrie)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $results =  AdminsCom::join('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_phone_credit_id')
            ->join('wallets', 'wallets.id', 'wallet_phone_credits.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->whereDate('admins_coms.created_at', $convertions)
            ->where('wallet_phone_credits.status', 'SUCCESS')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletPhonePending($countrie)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $results =  AdminsCom::join('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_phone_credit_id')
            ->join('wallets', 'wallets.id', 'wallet_phone_credits.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->whereDate('admins_coms.created_at', $convertions)
            ->where('wallet_phone_credits.status', 'PENDING')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }
    public function getCountWalletPhoneError($countrie)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $results =  AdminsCom::join('wallet_phone_credits', 'wallet_phone_credits.id', 'admins_coms.wallet_phone_credit_id')
            ->join('wallets', 'wallets.id', 'wallet_phone_credits.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->whereDate('admins_coms.created_at', $convertions)
            ->where('wallet_phone_credits.status', 'ERROR')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }


    public function getCountWalletPaymentSuccess($countrie)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $results =  AdminsCom::join('wallet_payments', 'wallet_payments.id', 'admins_coms.wallet_payment_id')
            ->join('wallets', 'wallets.id', 'wallet_payments.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->whereDate('admins_coms.created_at', $convertions)
            ->where('wallet_payments.status', 'SUCCESS')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletPaymentPending($countrie)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $results =  AdminsCom::join('wallet_payments', 'wallet_payments.id', 'admins_coms.wallet_payment_id')
            ->join('wallets', 'wallets.id', 'wallet_payments.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->whereDate('admins_coms.created_at', $convertions)
            ->where('wallet_payments.status', 'PENDING')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }
    public function getCountWalletPaymentError($countrie)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $results =  AdminsCom::join('wallet_payments', 'wallet_payments.id', 'admins_coms.wallet_payment_id')
            ->join('wallets', 'wallets.id', 'wallet_payments.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->whereDate('admins_coms.created_at', $convertions)
            ->where('wallet_payments.status', 'ERROR')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletDepositSuccess($countrie)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $results =  AdminsCom::join('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id')
            ->join('wallets', 'wallets.id', 'wallet_deposits.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->whereDate('admins_coms.created_at', $convertions)
            ->where('wallet_deposits.status', 'SUCCESS')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletDepositPending($countrie)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $results =  AdminsCom::join('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id')
            ->join('wallets', 'wallets.id', 'wallet_deposits.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->whereDate('admins_coms.created_at', $convertions)
            ->where('wallet_deposits.status', 'PENDING')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }
    public function getCountWalletDepositError($countrie)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $results =  AdminsCom::join('wallet_deposits', 'wallet_deposits.id', 'admins_coms.wallet_deposit_id')
            ->join('wallets', 'wallets.id', 'wallet_deposits.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->whereDate('admins_coms.created_at', $convertions)
            ->where('wallet_deposits.status', 'ERROR')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }


    public function getCountWalletReloadSuccess($countrie)
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $results =  AdminsCom::join('wallet_reloads', 'wallet_reloads.id', 'admins_coms.wallet_reload_id')
            ->join('wallets', 'wallets.id', 'wallet_reloads.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->whereDate('admins_coms.created_at', $convertions)
            ->where('wallet_reloads.status', 'SUCCESS')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletReloadPending($countrie)
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $results =  AdminsCom::join('wallet_reloads', 'wallet_reloads.id', 'admins_coms.wallet_reload_id')
            ->join('wallets', 'wallets.id', 'wallet_reloads.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->whereDate('admins_coms.created_at', $convertions)
            ->where('wallet_reloads.status', 'PENDING')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }
    public function getCountWalletReloadError($countrie)
    {


        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $results =  AdminsCom::join('wallet_reloads', 'wallet_reloads.id', 'admins_coms.wallet_reload_id')
            ->join('wallets', 'wallets.id', 'wallet_reloads.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->whereDate('admins_coms.created_at', $convertions)
            ->where('wallet_reloads.status', 'ERROR')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletBankSuccess($countrie)
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $results =  AdminsCom::join('wallet_banks', 'wallet_banks.id', 'admins_coms.wallet_bank_id')
            ->join('wallets', 'wallets.id', 'wallet_banks.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->whereDate('admins_coms.created_at', $convertions)
            ->where('wallet_banks.status', 'SUCCESS')
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletBankPending($countrie)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $results =  AdminsCom::join('wallet_banks', 'wallet_banks.id', 'admins_coms.wallet_bank_id')
            ->join('wallets', 'wallets.id', 'wallet_banks.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('wallet_banks.status', 'PENDING')
            ->whereDate('admins_coms.created_at', $convertions)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }
    public function getCountWalletBankError($countrie)
    {
        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $results =  AdminsCom::join('wallet_banks', 'wallet_banks.id', 'admins_coms.wallet_bank_id')
            ->join('wallets', 'wallets.id', 'wallet_banks.wallet_id')
            ->where('wallets.country_id', $countrie)
            ->where('wallet_banks.status', 'ERROR')
            ->whereDate('admins_coms.created_at', $convertions)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
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

    public function browse_onexbets()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.onexbets');

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
}
