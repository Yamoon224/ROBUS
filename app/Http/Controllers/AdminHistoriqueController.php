<?php

namespace App\Http\Controllers;

use App\Models\WithMerchant;
use Illuminate\Http\Request;
use App\Models\Wallets\Wallet;
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

class AdminHistoriqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function reporting_historical($id)
    {


        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $formattedDate = today();
        $bank_historicals = [];

        $wallet_user = Wallet::whereId($id)->first();

        $userId =  $wallet_user->sidUser->id;

        if (sabdy()->is_distributer == true) {

            foreach (WalletBank::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletBank::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'PENDING')->count();

            $account_success = WalletBank::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'SUCCESS')->count();

            $account_errors = WalletBank::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->count();

            $amount_total = $this->getWalletBankSumDaysProperty($userId);

            $pdf = Pdf::loadView('pages.historicals.distributers.pdf_bank', compact('wallet_user', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_virement.pdf');
        } else {

            foreach (WalletBank::where('wallet_id', $id)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletBank::where('wallet_id', $id)->where('status', 'PENDING')->count();

            $account_success = WalletBank::where('wallet_id', $id)->where('status', 'SUCCESS')->count();

            $account_errors = WalletBank::where('wallet_id', $id)->where('status', 'ERROR')->count();

            $amount_total = $this->getWalletBankSumProperty($id);

            $pdf = Pdf::loadView('pages.historicals.reports.pdf_bank', compact('wallet_user', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_virement.pdf');
        }
    }

    public function reporting_reload_historical($id)
    {


        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $wallet_id = Wallet::whereId($id)->first();
        $formattedDate = today();

        $bank_historicals = [];

        $userId =  $wallet_id->sidUser->id;

        if (sabdy()->is_distributer == true) {

            foreach (WalletReload::where('account_wallet', $wallet_id->wallet_id)->get() as $bank) {
                $bank_historicals[] = $bank;
            }


            $account_penddings = WalletReload::where('account_wallet', $wallet_id->wallet_id)->where('status', 'PENDING')->count();

            $account_success = WalletReload::where('account_wallet', $wallet_id->wallet_id)->where('status', 'SUCCESS')->count();

            $account_errors = WalletReload::where('account_wallet', $wallet_id->wallet_id)->where('status', 'ERROR')->count();

            $amount_send =  $this->walletRelaodSumSendUserProperty($id);
            $amount_receveid =  $this->walletRelaodSumReceivedUserProperty($id);
            $amount_total = $this->walletRelaodSumSendUserProperty($wallet_id->wallet_id);

            $pdf = Pdf::loadView('pages.historicals.distributers.pdf_reload', compact('wallet_id', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_rechargment.pdf');
        } else {

            foreach (WalletReload::where('account_wallet', $wallet_id->wallet_id)->get() as $bank) {
                $bank_historicals[] = $bank;
            }


            $account_penddings = WalletReload::where('account_wallet', $wallet_id->wallet_id)->where('status', 'PENDING')->count();

            $account_success = WalletReload::where('account_wallet', $wallet_id->wallet_id)->where('status', 'SUCCESS')->count();

            $account_errors = WalletReload::where('account_wallet', $wallet_id->wallet_id)->where('status', 'ERROR')->count();

            $amount_send =  $this->walletRelaodSumSendUserProperty($id);
            $amount_receveid =  $this->walletRelaodSumReceivedUserProperty($id);
            $amount_total = $this->walletRelaodSumSendUserProperty($wallet_id->wallet_id);

            $pdf = Pdf::loadView('pages.historicals.reports.pdf_reload', compact('wallet_id', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_rechargment.pdf');
        }
    }

    public function reporting_transfer_received_historical($id)
    {


        $usersCountry  =   Auth::guard('web')->user();
        // $todays = Carbon::now();
        // $convertions = $todays->toDateString();
        $wallet_id = Wallet::whereId($id)->first();

        $bank_historicals = [];

        foreach (WalletTransaction::where('num_wallet', $wallet_id->wallet_id)->get() as $bank) {
            $bank_historicals[] = $bank;
        }


        $account_penddings = WalletTransaction::where('num_wallet', $wallet_id->wallet_id)->where('status', 'PENDING')->count();

        $account_success = WalletTransaction::where('num_wallet', $wallet_id->wallet_id)->where('status', 'SUCCESS')->count();

        $account_errors = WalletTransaction::where('num_wallet', $wallet_id->wallet_id)->where('status', 'ERROR')->count();

        $amount_send =  $this->walletRelaodSumReceivedTransactionProperty($id);
        $amount_receveid =  $this->walletRelaodSumReceivedUserProperty($id);
        $amount_total = $this->walletRelaodSumReceivedTransactionProperty($wallet_id->wallet_id);

        $pdf = Pdf::loadView('pages.historicals.reports.pdf_transfer', compact('wallet_id', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download('reporting_transfers.pdf');
    }

    public function reporting_transfer_echecs_historical($id)
    {
        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $wallet_id = Wallet::whereId($id)->first();
        $formattedDate = today();

        $bank_transfer_recieveds = [];

        $userId =  $wallet_id->sidUser->id;

        if (sabdy()->is_distributer == true) {

            foreach (WalletTransaction::where('num_wallet', $wallet_id->wallet_id)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->get() as $bank) {
                $bank_transfer_recieveds[] = $bank;
            }

            $bank_transfer_sends = [];

            foreach (WalletTransaction::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->get() as $bank) {
                $bank_transfer_sends[] = $bank;
            }

            $bank_bank_recieveds = [];

            foreach (WalletBank::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->get() as $bank) {
                $bank_bank_recieveds[] = $bank;
            }


            $bank_reloads_recieveds = [];

            foreach (WalletReload::where('account_wallet',  $wallet_id->wallet_id)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->get() as $bank) {
                $bank_reloads_recieveds[] = $bank;
            }

            $bank_reloads_send_recieveds = [];

            foreach (WalletReload::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->get() as $bank) {
                $bank_reloads_send_recieveds[] = $bank;
            }

            $bank_phones_sends = [];

            foreach (WalletPhoneCredit::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->get() as $bank) {
                $bank_phones_sends[] = $bank;
            }

            $bank_deposits_sends = [];

            foreach (WalletDeposit::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->get() as $bank) {
                $bank_deposits_sends[] = $bank;
            }

            $bank_inters_sends = [];

            foreach (WalletInter::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->get() as $bank) {
                $bank_inters_sends[] = $bank;
            }

            $bank_inters_receiveds = [];

            foreach (WalletInter::where('number_compte', $wallet_id->wallet_id)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->get() as $bank) {
                $bank_inters_receiveds[] = $bank;
            }

            $bank_payments_sends = [];

            foreach (WalletPayment::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->get() as $bank) {
                $bank_payments_sends[] = $bank;
            }

            $transfers_received_sum_errors = WalletTransaction::where('num_wallet', $wallet_id->wallet_id)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->count();

            $transfers_send_sum_erros = WalletTransaction::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->count();

            $reloads_received_sum_errors = WalletReload::where('account_wallet', $wallet_id->wallet_id)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->count();

            $reloads_send_sum_erros = WalletReload::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->count();

            $bank_received_sum_errors = WalletBank::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->count();

            $phone_send_sum_erros = WalletPhoneCredit::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->count();

            $deposit_send_sum_erros = WalletDeposit::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->count();

            $payment_send_sum_erros = WalletPayment::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->count();


            $inter_received_sum_errors = WalletInter::where('number_compte', $wallet_id->wallet_id)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->count();

            $inter_send_sum_erros = WalletInter::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->count();


            $amount_send_reload =  $this->walletPaymentSumErrorSendReloadDaysProperty($userId);
            $amount_receveid_reload =  $this->walletPaymentSumErrorSendReceivedDaysProperty($wallet_id->wallet_id);
            $amount_send_transaction =  $this->walletPaymentSumErrorSendTransactionDaysProperty($userId);
            $amount_receveid_transaction =  $this->walletPaymentSumErrorReceivedTransactionDaysProperty($wallet_id->wallet_id);
            $amount_send_inter =  $this->walletPaymentSumErrorSendinterDaysProperty($userId);
            $amount_receveid_inter =  $this->walletPaymentSumErrorReceivedinterDaysProperty($wallet_id->wallet_id);

            $amount_send_bank =  $this->walletPaymentSumErrorReceivedBankDaysProperty($userId);
            $amount_send_phone =  $this->walletPaymentSumErrorReceivedPhoneDaysProperty($userId);
            $amount_send_deposit =  $this->walletPaymentSumErrorReceivedDepositDaysProperty($userId);
            $amount_send_payment =  $this->walletPaymentSumErrorSendPaymentDaysProperty($userId);
            $pdf = Pdf::loadView('pages.historicals.distributers.all_errors', compact(
                'wallet_id',
                'bank_transfer_recieveds',
                'bank_transfer_sends',
                'bank_bank_recieveds',
                'bank_reloads_recieveds',
                'bank_reloads_send_recieveds',
                'bank_phones_sends',
                'bank_deposits_sends',
                'bank_inters_sends',
                'bank_inters_receiveds',
                'bank_payments_sends',
                'transfers_received_sum_errors',
                'transfers_send_sum_erros',
                'reloads_received_sum_errors',
                'reloads_send_sum_erros',
                'bank_received_sum_errors',
                'phone_send_sum_erros',
                'deposit_send_sum_erros',
                'payment_send_sum_erros',
                'inter_received_sum_errors',
                'inter_send_sum_erros',
                'amount_send_reload',
                'amount_receveid_reload',
                'amount_send_transaction',
                'amount_receveid_transaction',
                'amount_send_inter',
                'amount_receveid_inter',
                'amount_send_bank',
                'amount_send_phone',
                'amount_send_deposit',
                'amount_send_payment'
            ))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_transfers.pdf');
        } else {

            foreach (WalletTransaction::where('num_wallet', $wallet_id->wallet_id)->where('status', 'ERROR')->get() as $bank) {
                $bank_transfer_recieveds[] = $bank;
            }

            $bank_transfer_sends = [];

            foreach (WalletTransaction::where('wallet_id', $id)->where('status', 'ERROR')->get() as $bank) {
                $bank_transfer_sends[] = $bank;
            }

            $bank_bank_recieveds = [];

            foreach (WalletBank::where('wallet_id', $id)->where('status', 'ERROR')->get() as $bank) {
                $bank_bank_recieveds[] = $bank;
            }


            $bank_reloads_recieveds = [];

            foreach (WalletReload::where('account_wallet',  $wallet_id->wallet_id)->where('status', 'ERROR')->get() as $bank) {
                $bank_reloads_recieveds[] = $bank;
            }

            $bank_reloads_send_recieveds = [];

            foreach (WalletReload::where('wallet_id', $id)->where('status', 'ERROR')->get() as $bank) {
                $bank_reloads_send_recieveds[] = $bank;
            }

            $bank_phones_sends = [];

            foreach (WalletPhoneCredit::where('wallet_id', $id)->where('status', 'ERROR')->get() as $bank) {
                $bank_phones_sends[] = $bank;
            }

            $bank_deposits_sends = [];

            foreach (WalletDeposit::where('wallet_id', $id)->where('status', 'ERROR')->get() as $bank) {
                $bank_deposits_sends[] = $bank;
            }

            $bank_inters_sends = [];

            foreach (WalletInter::where('wallet_id', $id)->where('status', 'ERROR')->get() as $bank) {
                $bank_inters_sends[] = $bank;
            }

            $bank_inters_receiveds = [];

            foreach (WalletInter::where('number_compte', $wallet_id->wallet_id)->where('status', 'ERROR')->get() as $bank) {
                $bank_inters_receiveds[] = $bank;
            }

            $bank_payments_sends = [];

            foreach (WalletPayment::where('wallet_id', $id)->where('status', 'ERROR')->get() as $bank) {
                $bank_payments_sends[] = $bank;
            }

            $transfers_received_sum_errors = WalletTransaction::where('num_wallet', $wallet_id->wallet_id)->where('status', 'ERROR')->count();

            $transfers_send_sum_erros = WalletTransaction::where('wallet_id', $id)->where('status', 'ERROR')->count();

            $reloads_received_sum_errors = WalletReload::where('account_wallet', $wallet_id->wallet_id)->where('status', 'ERROR')->count();

            $reloads_send_sum_erros = WalletReload::where('wallet_id', $id)->where('status', 'ERROR')->count();

            $bank_received_sum_errors = WalletBank::where('wallet_id', $id)->where('status', 'ERROR')->count();

            $phone_send_sum_erros = WalletPhoneCredit::where('wallet_id', $id)->where('status', 'ERROR')->count();

            $deposit_send_sum_erros = WalletDeposit::where('wallet_id', $id)->where('status', 'ERROR')->count();

            $payment_send_sum_erros = WalletPayment::where('wallet_id', $id)->where('status', 'ERROR')->count();


            $inter_received_sum_errors = WalletInter::where('number_compte', $wallet_id->wallet_id)->where('status', 'ERROR')->count();

            $inter_send_sum_erros = WalletInter::where('wallet_id', $id)->where('status', 'ERROR')->count();


            $amount_send_reload =  $this->walletPaymentSumErrorSendReloadProperty($id);
            $amount_receveid_reload =  $this->walletPaymentSumErrorSendReceivedProperty($wallet_id->wallet_id);
            $amount_send_transaction =  $this->walletPaymentSumErrorSendTransactionProperty($id);
            $amount_receveid_transaction =  $this->walletPaymentSumErrorReceivedTransactionProperty($wallet_id->wallet_id);
            $amount_send_inter =  $this->walletPaymentSumErrorSendinterProperty($id);
            $amount_receveid_inter =  $this->walletPaymentSumErrorReceivedinterProperty($wallet_id->wallet_id);

            $amount_send_bank =  $this->walletPaymentSumErrorReceivedBankProperty($id);
            $amount_send_phone =  $this->walletPaymentSumErrorReceivedPhoneProperty($id);
            $amount_send_deposit =  $this->walletPaymentSumErrorReceivedDepositProperty($id);
            $amount_send_payment =  $this->walletPaymentSumErrorSendPaymentProperty($id);
            $pdf = Pdf::loadView('pages.historicals.reports.all_errors', compact(
                'wallet_id',
                'bank_transfer_recieveds',
                'bank_transfer_sends',
                'bank_bank_recieveds',
                'bank_reloads_recieveds',
                'bank_reloads_send_recieveds',
                'bank_phones_sends',
                'bank_deposits_sends',
                'bank_inters_sends',
                'bank_inters_receiveds',
                'bank_payments_sends',
                'transfers_received_sum_errors',
                'transfers_send_sum_erros',
                'reloads_received_sum_errors',
                'reloads_send_sum_erros',
                'bank_received_sum_errors',
                'phone_send_sum_erros',
                'deposit_send_sum_erros',
                'payment_send_sum_erros',
                'inter_received_sum_errors',
                'inter_send_sum_erros',
                'amount_send_reload',
                'amount_receveid_reload',
                'amount_send_transaction',
                'amount_receveid_transaction',
                'amount_send_inter',
                'amount_receveid_inter',
                'amount_send_bank',
                'amount_send_phone',
                'amount_send_deposit',
                'amount_send_payment'
            ))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_transfers.pdf');
        }
    }

    // Totals amount Errors
    public function walletPaymentSumErrorReceivedinterProperty($id)
    {

        $data = [];
        foreach (WalletInter::where('number_compte', $id)->where('status', 'ERROR')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletPaymentSumErrorReceivedinterDaysProperty($id)
    {

        $todays = Carbon::now();
        $formattedDate = today();

        $data = [];
        foreach (WalletInter::where('number_compte', $id)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletPaymentSumErrorSendinterProperty($id)
    {

        $data = [];
        foreach (WalletInter::where('wallet_id', $id)->where('status', 'ERROR')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletPaymentSumErrorSendinterDaysProperty($id)
    {

        $todays = Carbon::now();
        $formattedDate = today();

        $data = [];
        foreach (WalletInter::where('updated_by', $id)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }


    public function walletPaymentSumErrorSendReloadProperty($id)
    {

        $data = [];
        foreach (WalletReload::where('wallet_id', $id)->where('status', 'ERROR')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }


    public function walletPaymentSumErrorSendReloadDaysProperty($id)
    {
        $todays = Carbon::now();
        $formattedDate = today();

        $data = [];
        foreach (WalletReload::where('wallet_id', $id)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletPaymentSumErrorSendReceivedProperty($id)
    {
        $data = [];
        foreach (WalletReload::where('account_wallet', $id)->where('status', 'ERROR')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletPaymentSumErrorSendReceivedDaysProperty($id)
    {
        $todays = Carbon::now();
        $formattedDate = today();

        $data = [];
        foreach (WalletReload::where('account_wallet', $id)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletPaymentSumErrorSendTransactionProperty($id)
    {

        $data = [];
        foreach (WalletTransaction::where('wallet_id', $id)->where('status', 'ERROR')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletPaymentSumErrorSendTransactionDaysProperty($id)
    {

        $todays = Carbon::now();
        $formattedDate = today();
        $data = [];
        foreach (WalletTransaction::where('updated_by', $id)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }
    public function walletPaymentSumErrorReceivedTransactionProperty($id)
    {

        $data = [];
        foreach (WalletTransaction::where('num_wallet', $id)->where('status', 'ERROR')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletPaymentSumErrorReceivedTransactionDaysProperty($id)
    {

        $todays = Carbon::now();
        $formattedDate = today();

        $data = [];
        foreach (WalletTransaction::where('num_wallet', $id)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletPaymentSumErrorReceivedBankProperty($id)
    {

        $data = [];
        foreach (WalletBank::where('wallet_id', $id)->where('status', 'ERROR')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletPaymentSumErrorReceivedBankDaysProperty($id)
    {

        $todays = Carbon::now();
        $formattedDate = today();
        $data = [];
        foreach (WalletBank::where('updated_by', $id)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletPaymentSumErrorReceivedPhoneProperty($id)
    {

        $data = [];
        foreach (WalletPhoneCredit::where('wallet_id', $id)->where('status', 'ERROR')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletPaymentSumErrorReceivedPhoneDaysProperty($id)
    {

        $todays = Carbon::now();
        $formattedDate = today();

        $data = [];
        foreach (WalletPhoneCredit::where('updated_by', $id)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletPaymentSumErrorReceivedDepositProperty($id)
    {

        $data = [];
        foreach (WalletDeposit::where('wallet_id', $id)->where('status', 'ERROR')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    
    public function walletPaymentSumErrorReceivedDepositDaysProperty($id)
    {
        $todays = Carbon::now();
        $formattedDate = today();

        $data = [];
        foreach (WalletDeposit::where('updated_by', $id)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }
    public function walletPaymentSumErrorSendPaymentProperty($id)
    {

        $data = [];
        foreach (WalletPayment::where('wallet_id', $id)->where('status', 'ERROR')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletPaymentSumErrorSendPaymentDaysProperty($id)
    {

        $todays = Carbon::now();
        $formattedDate = today();

        $data = [];
        foreach (WalletPayment::where('updated_by', $id)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }
    // End totals Amount Error
    public function reporting_reload_received_historical($id)
    {
        $usersCountry  =   Auth::guard('web')->user();
        // $todays = Carbon::now();
        // $convertions = $todays->toDateString();
        $wallet_id = Wallet::whereId($id)->first();

        $bank_historicals = [];

        foreach (WalletReload::where('wallet_id', $id)->get() as $bank) {
            $bank_historicals[] = $bank;
        }


        $account_penddings = WalletReload::where('wallet_id', $id)->where('status', 'PENDING')->count();

        $account_success = WalletReload::where('wallet_id', $id)->where('status', 'SUCCESS')->count();

        $account_errors = WalletReload::where('wallet_id', $id)->where('status', 'ERROR')->count();

        $amount_send =  $this->walletRelaodSumSendUserProperty($id);
        $amount_receveid =  $this->walletRelaodSumReceivedUserProperty($id);
        $amount_total = $this->walletRelaodSumUserProperty($id);

        $pdf = Pdf::loadView('pages.historicals.reports.pdf_reload', compact('wallet_id', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download('reporting_rechargment.pdf');
    }


    // Ammount total
    public function getWalletBankSumCommissionsProperty($id)
    {

        $data = [];

        foreach (WalletBank::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletBankSumProperty($id)
    {

        $data = [];

        foreach (WalletBank::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletBankSumDaysProperty($id)
    {

        $todays = Carbon::now();
        $formattedDate = today();

        $data = [];

        foreach (WalletBank::where('updated_by', $id)->whereDate('updated_at', $formattedDate)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletDepositSumCommissionsProperty($id)
    {

        $data = [];

        foreach (WalletDeposit::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $deposit) {
            $data[] = $deposit->commissions;
        }

        return array_sum($data);
    }


    public function getWalletPaymentSumCommissionsProperty($id)
    {

        $data = [];

        foreach (WalletPayment::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $payment) {
            $data[] = $payment->commissions;
        }

        return array_sum($data);
    }

    public function getWalletTransactionSumCommissionsProperty($id)
    {


        $data = [];

        foreach (WalletTransaction::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $transaction) {
            $data[] = $transaction->commissions;
        }

        return array_sum($data);
    }


    public function getWalletInterSumCommissionsProperty($id)
    {


        $data = [];

        foreach (WalletInter::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $inter) {
            $data[] = $inter->commissions;
        }

        return array_sum($data);
    }

    public function getWalletPhoneCreditSumCommissionsProperty($id)
    {

        $data = [];

        foreach (WalletPhoneCredit::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $phone) {
            $data[] = $phone->commissions;
        }

        return array_sum($data);
    }

    public function getWalletReloadSumCommissionsProperty($id)
    {

        $data = [];

        foreach (WalletReload::where('account_wallet', $id)->where('status', 'SUCCESS')->get() as $reload) {
            $data[] = $reload->commissions;
        }

        return array_sum($data);
    }

    // User 
    public function walletRelaodSumSendUserProperty($id)
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $data = [];
        foreach (WalletReload::where('account_wallet', $id)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletRelaodSumUserProperty($id)
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $data = [];
        foreach (WalletReload::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletRelaodSumReceivedUserProperty($id)
    {

        $wallet_id = Wallet::whereId($id)->first();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $data = [];
        foreach (WalletReload::where('account_wallet', $wallet_id->wallet_id)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }


    public function reporting_card_historical($id)
    {

        $bank_historicals = [];
        $todays = Carbon::now();
        $formattedDate = today();

        $wallet_id = Wallet::whereId($id)->first();

        $userId =  $wallet_id->sidUser->id;

        if (sabdy()->is_distributer == true) {

            foreach (WalletDeposit::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletDeposit::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'PENDING')->count();

            $account_success = WalletDeposit::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'SUCCESS')->count();

            $account_errors = WalletDeposit::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->count();

            $amount_total = $this->walletCardDepositSumUserProperty($userId);

            $wallet_id = Wallet::whereId($id)->first();


            $pdf = Pdf::loadView('pages.historicals.distributers.pdf_card_bank', compact('wallet_id', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_commande.pdf');
        } else {

            foreach (WalletDeposit::where('wallet_id', $id)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletDeposit::where('wallet_id', $id)->where('status', 'PENDING')->count();

            $account_success = WalletDeposit::where('wallet_id', $id)->where('status', 'SUCCESS')->count();

            $account_errors = WalletDeposit::where('wallet_id', $id)->where('status', 'ERROR')->count();

            $amount_total = $this->walletCardDepositSumUserProperty($id);

            $wallet_id = Wallet::whereId($id)->first();


            $pdf = Pdf::loadView('pages.historicals.reports.pdf_card_bank', compact('wallet_id', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_commande.pdf');
        }
    }

    public function walletCardDepositSumUserProperty($id)
    {

        $data = [];
        $todays = Carbon::now();
        $formattedDate = today();

        foreach (WalletDeposit::where('updated_by', $id)->whereDate('updated_at', $formattedDate)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletCardDepositSumUserDaysProperty($id)
    {

        $data = [];
        foreach (WalletDeposit::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }


    public function reporting_inter_historical($id)
    {

        $usersCountry  =   Auth::guard('web')->user();
        $wallet_id = Wallet::whereId($id)->first();

        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $formattedDate = today();

        $bank_historicals = [];

        $wallet_id = Wallet::whereId($id)->first();

        $userId =  $wallet_id->sidUser->id;

        if (sabdy()->is_distributer == true) {

            foreach (WalletInter::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $bank_historicalReloads = [];

            foreach (WalletInter::where('number_compte', $wallet_id->wallet_id)->where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->get() as $bank) {
                $bank_historicalReloads[] = $bank;
            }


            $account_penddings = WalletInter::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'PENDING')->count();

            $account_success = WalletInter::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'SUCCESS')->count();

            $account_errors = WalletInter::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->count();

            $account_relods_penddings = WalletInter::where('number_compte', $wallet_id->wallet_id)->where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'PENDING')->count();

            $account_relods_success = WalletInter::where('number_compte', $wallet_id->wallet_id)->where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'SUCCESS')->count();

            $account_relods_errors = WalletInter::where('number_compte', $wallet_id->wallet_id)->where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->count();

            $amount_total = $this->walletPaymentInterSumSendDaysProperty($userId);

            $amount_recived_total = $this->walletPaymentSumReceivedinterDaysProperty($wallet_id->wallet_id);


            $pdf = Pdf::loadView('pages.historicals.distributers.pdf_inter', compact('amount_total', 'amount_recived_total', 'bank_historicalReloads', 'wallet_id', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_inter.pdf');
        } else {

            foreach (WalletInter::where('wallet_id', $id)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $bank_historicalReloads = [];

            foreach (WalletInter::where('number_compte', $wallet_id->wallet_id)->get() as $bank) {
                $bank_historicalReloads[] = $bank;
            }


            $account_penddings = WalletInter::where('wallet_id', $id)->where('status', 'PENDING')->count();

            $account_success = WalletInter::where('wallet_id', $id)->where('status', 'SUCCESS')->count();

            $account_errors = WalletInter::where('wallet_id', $id)->where('status', 'ERROR')->count();

            $account_relods_penddings = WalletInter::where('number_compte', $wallet_id->wallet_id)->where('status', 'PENDING')->count();

            $account_relods_success = WalletInter::where('number_compte', $wallet_id->wallet_id)->where('status', 'SUCCESS')->count();

            $account_relods_errors = WalletInter::where('number_compte', $wallet_id->wallet_id)->where('status', 'ERROR')->count();

            $amount_total = $this->walletPaymentInterSumSendProperty($id);

            $amount_recived_total = $this->walletPaymentSumReceivedinterProperty($wallet_id->wallet_id);


            $pdf = Pdf::loadView('pages.historicals.reports.pdf_inter', compact('amount_total', 'amount_recived_total', 'bank_historicalReloads', 'wallet_id', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_inter.pdf');
        }
    }

    public function walletPaymentInterSumSendProperty($id)
    {

        $todays = Carbon::now();

        $data = [];
        foreach (WalletInter::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletPaymentInterSumSendDaysProperty($id)
    {

        $todays = Carbon::now();
        $formattedDate = today();

        $data = [];
        foreach (WalletInter::where('updated_by', $id)->whereDate('updated_at', $formattedDate)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletPaymentSumReceivedinterProperty($id)
    {

        $todays = Carbon::now();

        $data = [];
        foreach (WalletInter::where('number_compte', $id)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletPaymentSumReceivedinterDaysProperty($id)
    {

        $todays = Carbon::now();
        $formattedDate = today();

        $data = [];
        foreach (WalletInter::where('number_compte', $id)->whereDate('updated_at', $formattedDate)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }
    public function reporting_payment_historical($id)
    {

        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $bank_historicals = [];
        $formattedDate = today();

        $wallet_id = Wallet::whereId($id)->first();

        $userId =  $wallet_id->sidUser->id;


        if (sabdy()->is_distributer == true) {


            foreach (WalletPayment::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletPayment::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'PENDING')->count();

            $account_success = WalletPayment::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'SUCCESS')->count();

            $account_errors = WalletPayment::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->count();

            $amount_total = $this->walletPaymentSumSendTransactionDaysProperty($userId);

            $pdf = Pdf::loadView('pages.historicals.distributers.pdf_payment', compact('wallet_id', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_payment.pdf');
        } else {


            foreach (WalletPayment::where('wallet_id', $id)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletPayment::where('wallet_id', $id)->where('status', 'PENDING')->count();

            $account_success = WalletPayment::where('wallet_id', $id)->where('status', 'SUCCESS')->count();

            $account_errors = WalletPayment::where('wallet_id', $id)->where('status', 'ERROR')->count();

            $amount_total = $this->walletPaymentSumSendTransactionProperty($id);

            $pdf = Pdf::loadView('pages.historicals.reports.pdf_payment', compact('wallet_id', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_payment.pdf');
        }
    }

    // User 
    public function walletPaymentSumSendTransactionProperty($id)
    {

        $todays = Carbon::now();

        $data = [];
        foreach (WalletPayment::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletPaymentSumSendTransactionDaysProperty($id)
    {

        $todays = Carbon::now();
        $formattedDate = today();

        $data = [];
        foreach (WalletPayment::where('updated_by', $id)->whereDate('updated_at', $formattedDate)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function reporting_phone_historical($id)
    {

        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $bank_historicals = [];
        $formattedDate = today();
        $wallet_id = Wallet::whereId($id)->first();
        $userId =  $wallet_id->sidUser->id;

        if (sabdy()->is_distributer == true) {

            foreach (WalletPhoneCredit::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletPhoneCredit::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'PENDING')->count();

            $account_success = WalletPhoneCredit::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'SUCCESS')->count();

            $account_errors = WalletPhoneCredit::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->count();

            $amount_total = $this->walletPhoneSumSendTransactionDaysProperty($id);

            $pdf = Pdf::loadView('pages.historicals.distributers.pdf_phone', compact('wallet_id', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_phone.pdf');
        } else {

            foreach (WalletPhoneCredit::where('wallet_id', $id)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletPhoneCredit::where('wallet_id', $id)->where('status', 'PENDING')->count();

            $account_success = WalletPhoneCredit::where('wallet_id', $id)->where('status', 'SUCCESS')->count();

            $account_errors = WalletPhoneCredit::where('wallet_id', $id)->where('status', 'ERROR')->count();

            $amount_total = $this->walletPhoneSumSendTransactionProperty($id);

            $pdf = Pdf::loadView('pages.historicals.reports.pdf_phone', compact('wallet_id', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_phone.pdf');
        }
    }

    // User 
    public function walletPhoneSumSendTransactionProperty($id)
    {

        $todays = Carbon::now();

        $data = [];
        foreach (WalletPhoneCredit::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletPhoneSumSendTransactionDaysProperty($id)
    {

        $todays = Carbon::now();
        $formattedDate = today();

        $data = [];
        foreach (WalletPhoneCredit::where('updated_by', $id)->whereDate('updated_at', $formattedDate)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function reporting_transfer_historical($id)
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $formattedDate = today();

        $wallet_id = Wallet::whereId($id)->first();

        $userId =  $wallet_id->sidUser->id;

        $bank_historicals = [];

        if (sabdy()->is_distributer == true) {

            foreach (WalletTransaction::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->get() as $bank) {
                $bank_historicals[] = $bank;
            }


            $account_penddings = WalletTransaction::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)
                ->where('status', 'PENDING')->count();
            $account_success = WalletTransaction::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)
                ->where('status', 'SUCCESS')->count();

            $account_errors = WalletTransaction::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)
                ->where('status', 'ERROR')->count();

            $amount_reload_total = $this->walletRelaodSumReceivedTransactionDaysProperty($userId);

            $amount_total = $this->walletRelaodSumSendTransactionDaysProperty($userId);

            $pdf = Pdf::loadView('pages.historicals.distributers.pdf_transfer', compact('wallet_id', 'amount_reload_total', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_transfers.pdf');
        } else {

            foreach (WalletTransaction::where('wallet_id', $id)->get() as $bank) {
                $bank_historicals[] = $bank;
            }


            $account_penddings = WalletTransaction::where('wallet_id', $id)
                ->where('status', 'PENDING')->count();
            $account_success = WalletTransaction::where('wallet_id', $id)
                ->where('status', 'SUCCESS')->count();

            $account_errors = WalletTransaction::where('wallet_id', $id)
                ->where('status', 'ERROR')->count();

            $amount_reload_total = $this->walletRelaodSumReceivedTransactionProperty($id);

            $amount_total = $this->walletRelaodSumSendTransactionProperty($id);

            $pdf = Pdf::loadView('pages.historicals.reports.pdf_transfer', compact('wallet_id', 'amount_reload_total', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_transfers.pdf');
        }
    }



    // User 
    public function walletRelaodSumSendTransactionProperty($id)
    {

        $todays = Carbon::now();

        $data = [];
        foreach (WalletTransaction::where('wallet_id', $id)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletRelaodSumSendTransactionDaysProperty($id)
    {

        $todays = Carbon::now();
        $formattedDate = today();

        $data = [];
        foreach (WalletTransaction::where('updated_by', $id)->whereDate('updated_at', $formattedDate)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }


    public function walletRelaodSumReceivedTransactionProperty($id)
    {

        $wallet_id = Wallet::whereId($id)->first();
        $todays = Carbon::now();

        $data = [];
        foreach (WalletTransaction::where('num_wallet', $id)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function walletRelaodSumReceivedTransactionDaysProperty($id)
    {

        $wallet_id = Wallet::whereId($id)->first();
        $todays = Carbon::now();
        $formattedDate = today();

        $data = [];
        foreach (WalletTransaction::where('updated_by', $id)->whereDate('updated_at', $formattedDate)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }
}
