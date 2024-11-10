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
use Illuminate\Support\Facades\File;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;

class HistoricalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_reload()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('historiques')) {
            abort(403);
        }

        return view('pages.historicals.browse_reload');
    }

    public function reporting_historical()
    {


        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $bank_historicals = [];

        if ($usersCountry->is_under_distributer == true) {

            foreach (
                WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $usersCountry->country->id)
                    ->where('wallet_banks.created_by', $usersCountry->id)
                    ->whereDate('wallet_banks.created_at', $convertions)->select('wallet_banks.*')->get() as $bank
            ) {
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

            $pdf = Pdf::loadView('pages.historicals.pdf_bank', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_virement.pdf');

        } elseif ($usersCountry->is_distributer == true) {

            foreach (
                WalletBank::where('updated_by', $usersCountry->id)
                    ->whereDate('created_at', $convertions)
                    ->get() as $bank
            ) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletBank::where('updated_by', $usersCountry->id)
                ->whereDate('created_at', $convertions)->where('status', 'PENDING')->count();

            $account_success = WalletBank::where('updated_by', $usersCountry->id)
                ->whereDate('created_at', $convertions)->where('status', 'SUCCESS')->count();

            $account_errors = WalletBank::where('updated_by', $usersCountry->id)
                ->whereDate('created_at', $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.pdf_bank', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_virement.pdf');
        } elseif ($usersCountry->is_admin == true) {

            foreach (WalletBank::whereDate('created_at',  $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletBank::whereDate('created_at', $convertions)->where('status', 'PENDING')->count();

            $account_success = WalletBank::whereDate('created_at', $convertions)->where('status', 'SUCCESS')->count();

            $account_errors = WalletBank::whereDate('created_at', $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.pdf_bank', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
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

        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $bank_historicals = [];
        if ($usersCountry->is_under_distributer == true) {

            foreach (
                WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $usersCountry->country->id)
                    ->where('wallet_deposits.created_by', $usersCountry->id)
                    ->whereDate('wallet_deposits.created_at',  $convertions)->select('wallet_deposits.*')->get() as $bank
            ) {
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

            $pdf = Pdf::loadView('pages.historicals.pdf_card_bank', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_commande.pdf');
        } elseif ($usersCountry->is_distributer == true) {
            foreach (
                WalletDeposit::where('updated_by', $usersCountry->id)
                    ->whereDate('created_at',  $convertions)->get() as $bank
            ) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletDeposit::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'PENDING')->count();

            $account_success = WalletDeposit::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'SUCCESS')->count();

            $account_errors = WalletDeposit::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.pdf_card_bank', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_commande.pdf');
        } elseif ($usersCountry->is_admin == true) {
            foreach (WalletDeposit::whereDate('created_at',  $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletDeposit::whereDate('created_at',  $convertions)->where('status', 'PENDING')->count();

            $account_success = WalletDeposit::whereDate('created_at',  $convertions)->where('status', 'SUCCESS')->count();

            $account_errors = WalletDeposit::whereDate('created_at',  $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.pdf_card_bank', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
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

            foreach (
                WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $usersCountry->country->id)
                    ->where('wallet_inters.created_by', $usersCountry->id)
                    ->whereDate('wallet_inters.created_at',  $convertions)->select('wallet_inters.*')->get() as $bank
            ) {
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


            $pdf = Pdf::loadView('pages.historicals.pdf_inter', compact('bank_historicals', 'users_countrie', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_inter.pdf');
        } elseif ($usersCountry->is_distributer == true) {

            foreach (
                WalletInter::where('updated_by', $usersCountry->id)
                    ->whereDate('created_at',  $convertions)->get() as $bank
            ) {
                $bank_historicals[] = $bank;
            }
            $users_countrie = Auth::guard('web')->user()->wallet->currencie->code;

            $account_penddings = WalletInter::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'PENDING')->count();

            $account_success = WalletInter::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'SUCCESS')->count();

            $account_errors = WalletInter::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.pdf_inter', compact('bank_historicals', 'users_countrie', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_inter.pdf');
        } elseif ($usersCountry->is_admin == true) {
            foreach (WalletInter::whereDate('created_at',  $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $users_countrie = Auth::guard('web')->user()->wallet->currencie->code;

            $account_penddings = WalletInter::whereDate('created_at',  $convertions)->where('status', 'PENDING')->count();

            $account_success = WalletInter::whereDate('created_at',  $convertions)->where('status', 'SUCCESS')->count();

            $account_errors = WalletInter::whereDate('created_at',  $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.pdf_inter', compact('bank_historicals', 'users_countrie', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
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
            foreach (
                WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $usersCountry->country->id)
                    ->where('wallet_payments.created_by', $usersCountry->id)
                    ->whereDate('wallet_payments.created_at',  $convertions)->select('wallet_payments.*')->get() as $bank
            ) {
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

            $pdf = Pdf::loadView('pages.historicals.pdf_payment', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_payment.pdf');
        } elseif ($usersCountry->is_distributer == true) {

            foreach (
                WalletPayment::where('updated_by', $usersCountry->id)
                    ->whereDate('created_at',  $convertions)->get() as $bank
            ) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletPayment::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'PENDING')->count();


            $account_success = WalletPayment::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'SUCCESS')->count();


            $account_errors = WalletPayment::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.pdf_payment', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_payment.pdf');
        } elseif ($usersCountry->is_admin == true) {

            foreach (WalletPayment::whereDate('created_at',  $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }


            $account_penddings = WalletPayment::whereDate('created_at',  $convertions)->where('status', 'PENDING')->count();


            $account_success = WalletPayment::whereDate('created_at',  $convertions)->where('status', 'SUCCESS')->count();


            $account_errors = WalletPayment::whereDate('created_at',  $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.pdf_payment', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
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

            foreach (
                WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $usersCountry->country->id)
                    ->where('wallet_phone_credits.created_by', $usersCountry->id)
                    ->whereDate('wallet_phone_credits.created_at',  $convertions)->select('wallet_phone_credits.*')->get() as $bank
            ) {
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


            $pdf = Pdf::loadView('pages.historicals.pdf_phone', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_phone.pdf');
        } elseif ($usersCountry->is_distributer == true) {
            foreach (
                WalletPhoneCredit::where('updated_by', $usersCountry->id)
                    ->whereDate('created_at',  $convertions)->get() as $bank
            ) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletPhoneCredit::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'PENDING')->count();

            $account_success = WalletPhoneCredit::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'SUCCESS')->count();

            $account_errors = WalletPhoneCredit::where('updated_by', $usersCountry->id)
                ->whereDate('created_at',  $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.pdf_phone', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_phone.pdf');
        } elseif ($usersCountry->is_admin == true) {
            foreach (WalletPhoneCredit::whereDate('created_at',  $convertions)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletPhoneCredit::whereDate('created_at',  $convertions)->where('status', 'PENDING')->count();

            $account_success = WalletPhoneCredit::whereDate('created_at',  $convertions)->where('status', 'SUCCESS')->count();

            $account_errors = WalletPhoneCredit::whereDate('created_at',  $convertions)->where('status', 'ERROR')->count();

            $pdf = Pdf::loadView('pages.historicals.pdf_phone', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
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

            foreach (
                WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $usersCountry->country->id)
                    ->where('wallet_transactions.created_by', $usersCountry->id)
                    ->whereDate('wallet_transactions.created_at',  $convertions)
                    ->select('wallet_transactions.*')->get() as $bank
            ) {
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

            $pdf = Pdf::loadView('pages.historicals.pdf_transfer', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_transfers.pdf');
        } elseif ($usersCountry->is_distributer == true) {

            foreach (
                WalletTransaction::where('updated_by', $usersCountry->id)
                    ->whereDate('created_at',  $convertions)->get() as $bank
            ) {
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

            $pdf = Pdf::loadView('pages.historicals.pdf_transfer', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
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

            $pdf = Pdf::loadView('pages.historicals.pdf_transfer', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_transfers.pdf');
        }
    }

    public function reporting_transfer_historical_company()
    {

        $usersCountry  =   firm();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $bank_historicals = [];


        foreach (
            WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                ->where('wallets.country_id', $usersCountry->country->id)
                ->where('wallet_transactions.wallet_id', $usersCountry->wallet->id)
                // ->whereDate('wallet_transactions.created_at',  $convertions)
                ->select('wallet_transactions.*')->get() as $bank
        ) {
            $bank_historicals[] = $bank;
        }

        $account_penddings = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', $usersCountry->country->id)
            ->where('wallet_transactions.wallet_id', $usersCountry->wallet->id)
            // ->whereDate('wallet_transactions.created_at',  $convertions)
            ->where('wallet_transactions.status', 'PENDING')
            ->select('wallet_transactions.*')->count();
        $account_success = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', $usersCountry->country->id)
            ->where('wallet_transactions.wallet_id', $usersCountry->wallet->id)
            // ->whereDate('wallet_transactions.created_at',  $convertions)
            ->where('wallet_transactions.status', 'SUCCESS')
            ->select('wallet_transactions.*')->count();

        $account_errors = WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', $usersCountry->country->id)
            ->where('wallet_transactions.wallet_id', $usersCountry->wallet->id)
            // ->whereDate('wallet_transactions.created_at',  $convertions)
            ->where('wallet_transactions.status', 'ERROR')
            ->select('wallet_transactions.*')->count();


        $account_penddings = WalletTransaction::where('status', 'PENDING')->where('wallet_id', $usersCountry->wallet->id)
            ->count();
        $account_success = WalletTransaction::where('status', 'SUCCESS')->where('wallet_id', $usersCountry->wallet->id)
            ->count();

        $account_errors = WalletTransaction::where('status', 'ERROR')->where('wallet_id', $usersCountry->wallet->id)
            ->count();

        $pdf = Pdf::loadView('pages.historicals.pdf_transfer_company', compact('bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->download('reporting_transfers.pdf');
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

        foreach ($wallets::whereBetween('created_at', [$searchDateBegin, $searchDateEnd])->where('wallet_id' , firm()->wallet->wallet_id)->where('status', 'SUCCESS')->get() as $bank) {

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
