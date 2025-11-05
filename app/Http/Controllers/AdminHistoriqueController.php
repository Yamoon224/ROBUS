<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Custumers\Country;
use App\Models\LevelCountry;
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
use App\Models\Wallets\WalletDjamo;
use App\Models\Wallets\WalletPush;
use App\Models\Wallets\WalletVersement;
use App\Models\Wallets\WalletTresortMoney;
use App\Models\WithMerchant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

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

            $query = WalletBank::where('updated_by', $userId);

            if (request()->filled('startDate') && request()->filled('endDate')) {
                $query->whereBetween('updated_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $query->whereDate('updated_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $query->whereDate('updated_at', '<=', request('endDate'));
            }

            $bank_historicals = $query->get();

            $account_penddings = (clone $query)->where('status', 'PENDING')->count();
            $account_success   = (clone $query)->where('status', 'SUCCESS')->count();
            $account_errors    = (clone $query)->where('status', 'ERROR')->count();

            $amount_total = $this->getWalletBankSumDaysProperty($userId, request('startDate'), request('endDate'));

            $pdf = Pdf::loadView('pages.historicals.distributers.pdf_bank', compact('wallet_user', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_virement.pdf');
        } else {

            $query = WalletBank::where('wallet_id', $id);

            if (request()->filled('startDate') && request()->filled('endDate')) {
                $query->whereBetween('created_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $query->whereDate('created_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $query->whereDate('created_at', '<=', request('endDate'));
            }

            $bank_historicals = $query->get();

            $account_penddings = (clone $query)->where('status', 'PENDING')->count();
            $account_success   = (clone $query)->where('status', 'SUCCESS')->count();
            $account_errors    = (clone $query)->where('status', 'ERROR')->count();

            $amount_total = $this->getWalletBankSumProperty($id,  request('startDate'), request('endDate'));

            $pdf = Pdf::loadView('pages.historicals.reports.pdf_bank', compact('wallet_user', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_virement.pdf');
        }
    }

    public function reporting_xbet_historical($id)
    {


        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $formattedDate = today();
        $bank_historicals = [];

        $wallet_user = Wallet::whereId($id)->first();

        $userId =  $wallet_user->sidUser->id;

        if (sabdy()->is_distributer == true) {

            foreach (WalletXbet::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletXbet::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'PENDING')->count();

            $account_success = WalletXbet::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'SUCCESS')->count();

            $account_errors = WalletXbet::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->count();

            $amount_total = $this->getWalletSumDaysProperty(WalletXbet::class, $userId);

            $pdf = Pdf::loadView('pages.historicals.distributers.pdf_xbets', compact('wallet_user', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_virement.pdf');
        } else {

            foreach (WalletXbet::where('wallet_id', $id)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletXbet::where('wallet_id', $id)->where('status', 'PENDING')->count();

            $account_success = WalletXbet::where('wallet_id', $id)->where('status', 'SUCCESS')->count();

            $account_errors = WalletXbet::where('wallet_id', $id)->where('status', 'ERROR')->count();

            $amount_total = $this->getWalletSumDaysProperty(WalletXbet::class, $userId);

            $pdf = Pdf::loadView('pages.historicals.reports.pdf_xbets', compact('wallet_user', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
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

        $query = WalletReload::where('account_wallet', $wallet_id->wallet_id);

        if (request()->filled('startDate') && request()->filled('endDate')) {
            $query->whereBetween('created_at', [request('startDate'), request('endDate')]);
        } elseif (request()->filled('startDate')) {
            $query->whereDate('created_at', '>=', request('startDate'));
        } elseif (request()->filled('endDate')) {
            $query->whereDate('created_at', '<=', request('endDate'));
        }

        $bank_historicals = $query->get();
        $account_penddings = (clone $query)->where('status', 'PENDING')->count();
        $account_success   = (clone $query)->where('status', 'SUCCESS')->count();
        $account_errors    = (clone $query)->where('status', 'ERROR')->count();

        $amount_send =  $this->walletRelaodSumSendUserProperty($id, request('startDate'), request('endDate'));
        $amount_receveid =  $this->walletRelaodSumReceivedUserProperty($id, request('startDate'), request('endDate'));
        $amount_total = $this->walletRelaodSumSendUserProperty($wallet_id->wallet_id, request('startDate'), request('endDate'));

        if (sabdy()->is_distributer == true) {

            $pdf = Pdf::loadView('pages.historicals.distributers.pdf_reload', compact('wallet_id', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_rechargment.pdf');
        } else {

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

        $query = WalletTransaction::where('num_wallet', $wallet_id->wallet_id);

        if (request()->filled('startDate') && request()->filled('endDate')) {
            $query->whereBetween('created_at', [request('startDate'), request('endDate')]);
        } elseif (request()->filled('startDate')) {
            $query->whereDate('created_at', '>=', request('startDate'));
        } elseif (request()->filled('endDate')) {
            $query->whereDate('created_at', '<=', request('endDate'));
        }


        $bank_historicals = $query->get();

        $account_penddings = (clone $query)->where('status', 'PENDING')->count();
        $account_success   = (clone $query)->where('status', 'SUCCESS')->count();
        $account_errors    = (clone $query)->where('status', 'ERROR')->count();

        $amount_send =  $this->walletRelaodSumSendsTransactionProperty($id, request('startDate'), request('endDate'));
        $amount_receveid =  $this->walletTransactionSumReceivedUserProperty($id, request('startDate'), request('endDate'));
        $amount_total = $this->walletRelaodSumReceivedTransactionProperty($wallet_id->wallet_id, request('startDate'), request('endDate'));

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

            $queryTransactionReceived = WalletTransaction::where('num_wallet', $wallet_id->wallet_id)->where('status', 'ERROR');
            $queryTransactionSend = WalletTransaction::where('updated_by', $userId)->where('status', 'ERROR');
            $queryBankReceived = WalletBank::where('updated_by', $userId)->where('status', 'ERROR');
            $queryReloadReceived = WalletReload::where('account_wallet',  $wallet_id->wallet_id)->where('status', 'ERROR');
            $queryReloadSend = WalletReload::where('updated_by', $userId)->where('status', 'ERROR');
            $queryPhoneSend = WalletPhoneCredit::where('updated_by', $userId)->where('status', 'ERROR');
            $queryDepositSend = WalletDeposit::where('updated_by', $userId)->where('status', 'ERROR');
            $queryInterSend = WalletInter::where('updated_by', $userId)->where('status', 'ERROR');
            $queryInterReceived = WalletInter::where('number_compte', $wallet_id->wallet_id)->where('status', 'ERROR');
            $queryPaymentSend = WalletPayment::where('updated_by', $userId)->where('status', 'ERROR');

            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryTransactionReceived->whereBetween('updated_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryTransactionReceived->whereDate('updated_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryTransactionReceived->whereDate('updated_at', '<=', request('endDate'));
            }
            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryTransactionSend->whereBetween('updated_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryTransactionSend->whereDate('updated_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryTransactionSend->whereDate('updated_at', '<=', request('endDate'));
            }
            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryBankReceived->whereBetween('updated_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryBankReceived->whereDate('updated_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryBankReceived->whereDate('updated_at', '<=', request('endDate'));
            }
            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryReloadReceived->whereBetween('updated_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryReloadReceived->whereDate('updated_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryReloadReceived->whereDate('updated_at', '<=', request('endDate'));
            }
            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryReloadSend->whereBetween('updated_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryReloadSend->whereDate('updated_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryReloadSend->whereDate('updated_at', '<=', request('endDate'));
            }
            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryPhoneSend->whereBetween('updated_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryPhoneSend->whereDate('updated_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryPhoneSend->whereDate('updated_at', '<=', request('endDate'));
            }
            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryDepositSend->whereBetween('updated_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryDepositSend->whereDate('updated_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryDepositSend->whereDate('updated_at', '<=', request('endDate'));
            }
            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryInterSend->whereBetween('updated_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryInterSend->whereDate('updated_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryInterSend->whereDate('updated_at', '<=', request('endDate'));
            }
            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryInterReceived->whereBetween('updated_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryInterReceived->whereDate('updated_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryInterReceived->whereDate('updated_at', '<=', request('endDate'));
            }
            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryPaymentSend->whereBetween('updated_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryPaymentSend->whereDate('updated_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryPaymentSend->whereDate('updated_at', '<=', request('endDate'));
            }


            $bank_transfer_sends = $queryTransactionSend->get();
            $bank_transfer_recieveds = $queryTransactionReceived->get();
            $bank_bank_recieveds = $queryBankReceived->get();
            $bank_reloads_recieveds = $queryReloadReceived->get();
            $bank_reloads_send_recieveds = $queryReloadSend->get();
            $bank_phones_sends = $queryPhoneSend->get();
            $bank_deposits_sends = $queryDepositSend->get();
            $bank_inters_sends = $queryInterSend->get();
            $bank_inters_receiveds = $queryInterReceived->get();
            $bank_payments_sends = $queryPaymentSend->get();

            $transfers_received_sum_errors = $queryTransactionReceived->count();
            $transfers_send_sum_erros = $queryTransactionSend->count();
            $reloads_received_sum_errors = $queryReloadReceived->count();
            $reloads_send_sum_erros = $queryReloadSend->count();
            $bank_received_sum_errors = $queryBankReceived->count();
            $phone_send_sum_erros = $queryPhoneSend->count();
            $deposit_send_sum_erros = $queryDepositSend->count();
            $payment_send_sum_erros = $queryPaymentSend->count();
            $inter_received_sum_errors = $queryInterReceived->count();
            $inter_send_sum_erros = $queryInterSend->count();
            $amount_send_reload =  $this->walletPaymentSumErrorSendReloadDaysProperty($userId, request('startDate'), request('endDate'));
            $amount_receveid_reload =  $this->walletPaymentSumErrorSendReceivedDaysProperty($wallet_id->wallet_id, request('startDate'), request('endDate'));
            $amount_send_transaction =  $this->walletPaymentSumErrorSendTransactionDaysProperty($userId, request('startDate'), request('endDate'));
            $amount_receveid_transaction =  $this->walletPaymentSumErrorReceivedTransactionDaysProperty($wallet_id->wallet_id, request('startDate'), request('endDate'));
            $amount_send_inter =  $this->walletPaymentSumErrorSendinterDaysProperty($userId, request('startDate'), request('endDate'));
            $amount_receveid_inter =  $this->walletPaymentSumErrorReceivedinterDaysProperty($wallet_id->wallet_id, request('startDate'), request('endDate'));
            $amount_send_bank =  $this->walletPaymentSumErrorReceivedBankDaysProperty($userId, request('startDate'), request('endDate'));
            $amount_send_phone =  $this->walletPaymentSumErrorReceivedPhoneDaysProperty($userId, request('startDate'), request('endDate'));
            $amount_send_deposit =  $this->walletPaymentSumErrorReceivedDepositDaysProperty($userId, request('startDate'), request('endDate'));
            $amount_send_payment =  $this->walletPaymentSumErrorSendPaymentDaysProperty($userId, request('startDate'), request('endDate'));

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

            $queryTransactionReceived = WalletTransaction::where('num_wallet', $wallet_id->wallet_id)->where('status', 'ERROR');
            $queryTransactionSend = WalletTransaction::where('wallet_id', $id)->where('status', 'ERROR');
            $queryBankReceived = WalletBank::where('wallet_id', $id)->where('status', 'ERROR');
            $queryReloadReceived = WalletReload::where('account_wallet',  $wallet_id->wallet_id)->where('status', 'ERROR');
            $queryReloadSend = WalletReload::where('wallet_id', $id)->where('status', 'ERROR');
            $queryPhoneSend = WalletPhoneCredit::where('wallet_id', $id)->where('status', 'ERROR');
            $queryDepositSend = WalletDeposit::where('wallet_id', $id)->where('status', 'ERROR');
            $queryInterSend = WalletInter::where('wallet_id', $id)->where('status', 'ERROR');
            $queryInterReceived = WalletInter::where('number_compte', $wallet_id->wallet_id)->where('status', 'ERROR');
            $queryPaymentSend = WalletPayment::where('wallet_id', $id)->where('status', 'ERROR');

            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryTransactionReceived->whereBetween('created_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryTransactionReceived->whereDate('created_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryTransactionReceived->whereDate('created_at', '<=', request('endDate'));
            }
            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryTransactionSend->whereBetween('created_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryTransactionSend->whereDate('created_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryTransactionSend->whereDate('created_at', '<=', request('endDate'));
            }
            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryBankReceived->whereBetween('created_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryBankReceived->whereDate('created_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryBankReceived->whereDate('created_at', '<=', request('endDate'));
            }
            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryReloadReceived->whereBetween('created_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryReloadReceived->whereDate('created_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryReloadReceived->whereDate('created_at', '<=', request('endDate'));
            }
            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryReloadSend->whereBetween('created_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryReloadSend->whereDate('created_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryReloadSend->whereDate('created_at', '<=', request('endDate'));
            }
            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryPhoneSend->whereBetween('created_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryPhoneSend->whereDate('created_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryPhoneSend->whereDate('created_at', '<=', request('endDate'));
            }
            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryDepositSend->whereBetween('created_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryDepositSend->whereDate('created_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryDepositSend->whereDate('created_at', '<=', request('endDate'));
            }
            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryInterSend->whereBetween('created_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryInterSend->whereDate('created_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryInterSend->whereDate('created_at', '<=', request('endDate'));
            }
            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryInterReceived->whereBetween('created_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryInterReceived->whereDate('created_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryInterReceived->whereDate('created_at', '<=', request('endDate'));
            }
            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryPaymentSend->whereBetween('created_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryPaymentSend->whereDate('created_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryPaymentSend->whereDate('created_at', '<=', request('endDate'));
            }


            $bank_transfer_sends = $queryTransactionSend->get();
            $bank_transfer_recieveds = $queryTransactionReceived->get();
            $bank_bank_recieveds = $queryBankReceived->get();
            $bank_reloads_recieveds = $queryReloadReceived->get();
            $bank_reloads_send_recieveds = $queryReloadSend->get();
            $bank_phones_sends = $queryPhoneSend->get();
            $bank_deposits_sends = $queryDepositSend->get();
            $bank_inters_sends = $queryInterSend->get();
            $bank_inters_receiveds = $queryInterReceived->get();
            $bank_payments_sends = $queryPaymentSend->get();

            $transfers_received_sum_errors = $queryTransactionReceived->count();
            $transfers_send_sum_erros = $queryTransactionSend->count();
            $reloads_received_sum_errors = $queryReloadReceived->count();
            $reloads_send_sum_erros = $queryReloadSend->count();
            $bank_received_sum_errors = $queryBankReceived->count();
            $phone_send_sum_erros = $queryPhoneSend->count();
            $deposit_send_sum_erros = $queryDepositSend->count();
            $payment_send_sum_erros = $queryPaymentSend->count();
            $inter_received_sum_errors = $queryInterReceived->count();
            $inter_send_sum_erros = $queryInterSend->count();

            $amount_send_reload =  $this->walletPaymentSumErrorSendReloadProperty($id, request('startDate'), request('endDate'));
            $amount_receveid_reload =  $this->walletPaymentSumErrorSendReceivedProperty($wallet_id->wallet_id, request('startDate'), request('endDate'));
            $amount_send_transaction =  $this->walletPaymentSumErrorSendTransactionProperty($id, request('startDate'), request('endDate'));
            $amount_receveid_transaction =  $this->walletPaymentSumErrorReceivedTransactionProperty($wallet_id->wallet_id, request('startDate'), request('endDate'));
            $amount_send_inter =  $this->walletPaymentSumErrorSendinterProperty($id, request('startDate'), request('endDate'));
            $amount_receveid_inter =  $this->walletPaymentSumErrorReceivedinterProperty($wallet_id->wallet_id, request('startDate'), request('endDate'));

            $amount_send_bank =  $this->walletPaymentSumErrorReceivedBankProperty($id, request('startDate'), request('endDate'));
            $amount_send_phone =  $this->walletPaymentSumErrorReceivedPhoneProperty($id, request('startDate'), request('endDate'));
            $amount_send_deposit =  $this->walletPaymentSumErrorReceivedDepositProperty($id, request('startDate'), request('endDate'));
            $amount_send_payment =  $this->walletPaymentSumErrorSendPaymentProperty($id, request('startDate'), request('endDate'));
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
    public function walletPaymentSumErrorReceivedinterProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletInter::where('number_compte', $id)
            ->where('status', 'ERROR');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function walletPaymentSumErrorReceivedinterDaysProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletInter::where('number_compte', $id)
            ->where('status', 'ERROR');
        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('updated_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('updated_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function walletPaymentSumErrorSendinterProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletInter::where('wallet_id', $id)
            ->where('status', 'ERROR');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function walletPaymentSumErrorSendinterDaysProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletInter::where('updated_by', $id)
            ->where('status', 'ERROR');

        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('updated_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('updated_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }


    public function walletPaymentSumErrorSendReloadProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletReload::where('wallet_id', $id)
            ->where('status', 'ERROR');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }


    public function walletPaymentSumErrorSendReloadDaysProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletReload::where('wallet_id', $id)
            ->where('status', 'ERROR');

        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('updated_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('updated_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function walletPaymentSumErrorSendReceivedProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletReload::where('account_wallet', $id)
            ->where('status', 'ERROR');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function walletPaymentSumErrorSendReceivedDaysProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletReload::where('account_wallet', $id)
            ->where('status', 'ERROR');

        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('updated_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('updated_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function walletPaymentSumErrorSendTransactionProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletTransaction::where('wallet_id', $id)
            ->where('status', 'ERROR');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function walletPaymentSumErrorSendTransactionDaysProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletTransaction::where('updated_by', $id)
            ->where('status', 'ERROR');

        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('updated_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('updated_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }
    public function walletPaymentSumErrorReceivedTransactionProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletTransaction::where('num_wallet', $id)
            ->where('status', 'ERROR');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function walletPaymentSumErrorReceivedTransactionDaysProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletTransaction::where('num_wallet', $id)
            ->where('status', 'ERROR');

        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('updated_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('updated_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function walletPaymentSumErrorReceivedBankProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletBank::where('wallet_id', $id)
            ->where('status', 'ERROR');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function walletPaymentSumErrorReceivedBankDaysProperty($id, $startDate = null, $endDate = null)
    {
        $query = WalletBank::where('updated_by', $id)
            ->where('status', 'ERROR');

        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('updated_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('updated_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function walletPaymentSumErrorReceivedPhoneProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletPhoneCredit::where('wallet_id', $id)
            ->where('status', 'ERROR');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function walletPaymentSumErrorReceivedPhoneDaysProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletPhoneCredit::where('updated_by', $id)
            ->where('status', 'ERROR');

        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('updated_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('updated_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function walletPaymentSumErrorReceivedDepositProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletDeposit::where('wallet_id', $id)
            ->where('status', 'ERROR');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }


    public function walletPaymentSumErrorReceivedDepositDaysProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletDeposit::where('updated_by', $id)
            ->where('status', 'ERROR');

        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('updated_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('updated_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }
    public function walletPaymentSumErrorSendPaymentProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletPayment::where('wallet_id', $id)
            ->where('status', 'ERROR');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
    }

    public function walletPaymentSumErrorSendPaymentDaysProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletPayment::where('updated_by', $id)
            ->where('status', 'ERROR');

        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('updated_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('updated_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }
    // End totals Amount Error
    public function reporting_reload_received_historical($id)
    {
        $usersCountry  =   Auth::guard('web')->user();
        // $todays = Carbon::now();
        // $convertions = $todays->toDateString();
        $wallet_id = Wallet::whereId($id)->first();

        $bank_historicals = [];

        $query = WalletReload::where('wallet_id', $id);

        if (request()->filled('startDate') && request()->filled('endDate')) {
            $query->whereBetween('created_at', [request('startDate'), request('endDate')]);
        } elseif (request()->filled('startDate')) {
            $query->whereDate('created_at', '>=', request('startDate'));
        } elseif (request()->filled('endDate')) {
            $query->whereDate('created_at', '<=', request('endDate'));
        }

        $bank_historicals = $query->get();
        $account_penddings = (clone $query)->where('status', 'PENDING')->count();
        $account_success   = (clone $query)->where('status', 'SUCCESS')->count();
        $account_errors    = (clone $query)->where('status', 'ERROR')->count();
        $amount_send =  $this->walletRelaodSumSendUserProperty($id, request('startDate'), request('endDate'));
        $amount_receveid =  $this->walletRelaodSumReceivedUserProperty($id, request('startDate'), request('endDate'));
        $amount_total = $this->walletRelaodSumSendUserProperty($wallet_id->wallet_id, request('startDate'), request('endDate'));

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

    public function getWalletBankSumProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletBank::where('wallet_id', $id)
            ->where('status', 'SUCCESS');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function getWalletBankSumDaysProperty($id, $startDate = null, $endDate = null)
    {


        $query = WalletBank::where('updated_by', $id)
            ->where('status', 'SUCCESS');

        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('updated_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('updated_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function getWalletSumDaysProperty($wallet, $id)
    {

        $todays = Carbon::now();
        $formattedDate = today();

        $data = [];

        foreach ($wallet::where('updated_by', $id)->whereDate('updated_at', $formattedDate)->where('status', 'SUCCESS')->get() as $bank) {
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
    public function walletRelaodSumSendUserProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletReload::where('wallet_id', $id)
            ->where('status', 'SUCCESS');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->sum('amount');
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

    public function walletRelaodSumReceivedUserProperty($id, $startDate = null, $endDate = null)
    {

        $wallet_id = Wallet::whereId($id)->first();
        $todays = Carbon::now();

        $query = WalletReload::where('account_wallet',  $wallet_id->wallet_id)
            ->where('status', 'SUCCESS');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->sum('amount_received');
    }
    public function walletTransactionSumReceivedUserProperty($id, $startDate = null, $endDate = null)
    {

        $wallet_id = Wallet::whereId($id)->first();

        $query = WalletTransaction::where('num_wallet',  $wallet_id->wallet_id)
            ->where('status', 'SUCCESS');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->sum('amount_received');
    }

    public function reporting_card_historical($id)
    {

        $bank_historicals = [];
        $todays = Carbon::now();
        $formattedDate = today();

        $wallet_id = Wallet::whereId($id)->first();

        $userId =  $wallet_id->sidUser->id;



        if (sabdy()->is_distributer == true) {

            $query = WalletDeposit::where('updated_by', $userId);

            if (request()->filled('startDate') && request()->filled('endDate')) {
                $query->whereBetween('updated_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $query->whereDate('updated_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $query->whereDate('updated_at', '<=', request('endDate'));
            }

            $bank_historicals = $query->get();
            $account_penddings = (clone $query)->where('status', 'PENDING')->count();
            $account_success   = (clone $query)->where('status', 'SUCCESS')->count();
            $account_errors    = (clone $query)->where('status', 'ERROR')->count();
            $amount_total = $this->walletCardDepositSumUserProperty($userId, request('startDate'), request('endDate'));
            $wallet_id = Wallet::whereId($id)->first();
            $pdf = Pdf::loadView('pages.historicals.distributers.pdf_card_bank', compact('wallet_id', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_commande.pdf');
        } else {

            $query = WalletDeposit::where('wallet_id', $id);

            if (request()->filled('startDate') && request()->filled('endDate')) {
                $query->whereBetween('updated_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $query->whereDate('updated_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $query->whereDate('updated_at', '<=', request('endDate'));
            }

            $bank_historicals = $query->get();
            $account_penddings = (clone $query)->where('status', 'PENDING')->count();
            $account_success   = (clone $query)->where('status', 'SUCCESS')->count();
            $account_errors    = (clone $query)->where('status', 'ERROR')->count();
            $amount_total = $this->walletCardDepositSumUserProperty($id, request('startDate'), request('endDate'));
            $wallet_id = Wallet::whereId($id)->first();
            $pdf = Pdf::loadView('pages.historicals.reports.pdf_card_bank', compact('wallet_id', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_commande.pdf');
        }
    }


    public function reporting_wave_historical($id)
    {
        $bank_historicals = [];
        $todays = Carbon::now();
        $formattedDate = today();

        $wallet_id = Wallet::whereId($id)->first();

        $userId =  $wallet_id->sidUser->id;

        if (sabdy()->is_distributer == true) {

            foreach (WalletWave::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletWave::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'PENDING')->count();

            $account_success = WalletWave::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'SUCCESS')->count();

            $account_errors = WalletWave::where('updated_by', $userId)->whereDate('updated_at', $formattedDate)->where('status', 'ERROR')->count();

            $amount_total = $this->walletCardDepositSumUserProperty($userId);

            $wallet_id = Wallet::whereId($id)->first();


            $pdf = Pdf::loadView('pages.historicals.distributers.pdf_wave', compact('wallet_id', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_commande.pdf');
        } else {

            foreach (WalletWave::where('wallet_id', $id)->get() as $bank) {
                $bank_historicals[] = $bank;
            }

            $account_penddings = WalletWave::where('wallet_id', $id)->where('status', 'PENDING')->count();

            $account_success = WalletWave::where('wallet_id', $id)->where('status', 'SUCCESS')->count();

            $account_errors = WalletWave::where('wallet_id', $id)->where('status', 'ERROR')->count();

            $amount_total = $this->walletCardDepositSumUserProperty($id);

            $wallet_id = Wallet::whereId($id)->first();


            $pdf = Pdf::loadView('pages.historicals.reports.pdf_wave', compact('wallet_id', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_wave.pdf');
        }
    }

    public function searchByIntersDate(Request $request)
    {

        $this->validate($request, [
            'search_begin' => 'required|date',
            'search_end' => 'required|date|after_or_equal:search_begin',
        ]);


        $searchDateBegin = $request->input('search_begin');

        $searchDateEnd = $request->input('search_end');

        $walletInters = $this->getTransactionDate(WalletInter::class, $searchDateBegin, $searchDateEnd);

        $account_penddings =  $this->getTransactionCount(WalletInter::class, 'PENDING', $searchDateBegin, $searchDateEnd);;

        $account_success = $this->getTransactionCount(WalletInter::class, 'SUCCESS', $searchDateBegin, $searchDateEnd);

        $account_errors = $this->getTransactionCount(WalletInter::class, 'ERROR', $searchDateBegin, $searchDateEnd);

        $amount_total = $this->walletTransactionIntersDays(WalletInter::class, $searchDateBegin, $searchDateEnd);

        $amount_total_banque = $this->getWalletIntersProperty($searchDateBegin, $searchDateEnd);

        $pdf = Pdf::loadView('pages.historicals.transactionsDays.pdf_inter_days', compact('amount_total', 'walletInters', 'account_penddings', 'account_success', 'account_errors', 'searchDateBegin', 'searchDateEnd', 'amount_total_banque'))->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download('reporting_inters_days.pdf');
    }

    public function getWalletIntersProperty($searchDateBegin, $searchDateEnd)
    {


        $walletInters = $this->getTransactionDate(WalletInter::class, $searchDateBegin, $searchDateEnd);

        $percent_amount = $walletInters->map(function ($wallet) {

            $amount_send = $wallet->amount;

            $country_user = $wallet->wallet;

            $percent_robus = $this->getRobusPercentageForWalletInter($wallet);

            $amount_banque = $this->getConvertAdminCommission($amount_send, $country_user);

            return optional($percent_robus)->percent_banque * $amount_banque;
        });

        return $percent_amount->sum();
    }

    public function getConvertAdminCommission($amount, $countrie_send)
    {
        $country_civ = Country::find(107);

        $robus_currency = $countrie_send->country->currencie->value;
        if ($country_civ->currencie->value > $countrie_send->country->currencie->value) {
            return $amount * $robus_currency;
        } elseif ($country_civ->currencie->value == $countrie_send->country->currencie->value) {
            return $amount;
        } else {
            return $amount * $robus_currency;
        }
    }


    protected function getRobusPercentageForWalletInter($wallet)
    {
        $sidUser = $wallet->wallet->sidUser;
        $countrieGroupe = $sidUser->country->name_groupe;
        $countrieUserId = $wallet->country;
        $countrieUserSend = $wallet->wallet->country->id;
        $providerSendUser = $wallet->provider_id;
        $typeProviderSendUser = $wallet->provider_type;

        if ($typeProviderSendUser == 1 && $providerSendUser != 51) {
            return $this->getRobusPercentageInter($countrieGroupe, $countrieUserId, $providerSendUser, $countrieUserSend);
        }

        return null;
    }

    // Percent valeurs Transactions
    public function getRobusPercentageInter($groupe_name, $countrie, $provider_valeur, $countryId)
    {

        $levelValeur = 0;

        if ($groupe_name == 'AFL') {

            $countriesLevels = [
                // Is Benin
                25 => [
                    36 => ($provider_valeur != 51) ? 2 : 37, // BF
                    133 => ($provider_valeur != 51) ? 3 : 38, // Mali
                    214 => ($provider_valeur != 51) ? 4 : 39, // Togo
                    188 => ($provider_valeur != 51) ? 5 : 40, // Senegal
                    83 => ($provider_valeur != 51) ? 6 : 41, // Ghana
                    157 => ($provider_valeur != 51) ? 7 : 42, // Niger
                    91 => ($provider_valeur != 51) ? 8 : 43, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 1 : 44, // CIV
                    1 => ($provider_valeur != 51) ? 248 : 241, // USA
                    218 => ($provider_valeur != 51) ? 264 : 292, // Tunisia
                    146 => ($provider_valeur != 51) ? 278 : 302, // Maroc
                    199 => ($provider_valeur != 51) ? 435 : 312, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 467 : 355, // France
                    179 => ($provider_valeur != 51) ? 531 : 419, // Russie
                    226 => ($provider_valeur != 51) ? 499 : 387, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 515 : 403, // Italie
                    82 => ($provider_valeur != 51) ? 483 : 371, // Allemagne
                    39 => ($provider_valeur != 51) ? 129 : 178, // Cameroun
                    79 => ($provider_valeur != 51) ? 130 : 179, // Gabon
                    50 => ($provider_valeur != 51) ? 131 : 180, // COngo Braza
                    57 => ($provider_valeur != 51) ? 132 : 181, // RDC
                    122 => ($provider_valeur != 51) ? 133 : 182, // Liberia
                    43 => ($provider_valeur != 51) ? 134 : 183, // Thacd
                    42 => ($provider_valeur != 51) ? 135 : 184, // Afrique centrale
                ],
                // Is BF
                36 =>  [
                    25 => ($provider_valeur != 51) ? 2 : 37, // Benin
                    133 => ($provider_valeur != 51) ? 9 : 45, // Mali
                    214 => ($provider_valeur != 51) ? 10 : 46, // Togo
                    188 => ($provider_valeur != 51) ? 11 : 47, // Senegal
                    83 => ($provider_valeur != 51) ? 12 : 48, // Ghana
                    157 => ($provider_valeur != 51) ? 13 : 49, // Niger
                    91 => ($provider_valeur != 51) ? 14 : 50, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 15 : 51, // CIV
                    1 => ($provider_valeur != 51) ? 249 : 242, // USA
                    218 => ($provider_valeur != 51) ? 265 : 293, // Tunisia
                    146 => ($provider_valeur != 51) ? 279 : 302, // Maroc
                    199 => ($provider_valeur != 51) ? 436 : 313, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 468 : 356, // France
                    179 => ($provider_valeur != 51) ? 532 : 420, // Russie
                    226 => ($provider_valeur != 51) ? 500 : 388, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 516 : 404, // Italie
                    82 => ($provider_valeur != 51) ? 484 : 372, // Allemagne
                    39 => ($provider_valeur != 51) ? 136 : 185, // Cameroun
                    79 => ($provider_valeur != 51) ? 137 : 186, // Gabon
                    50 => ($provider_valeur != 51) ? 138 : 187, // COngo Braza
                    57 => ($provider_valeur != 51) ? 139 : 188, // RDC
                    122 => ($provider_valeur != 51) ? 140 : 189, // Liberia
                    43 => ($provider_valeur != 51) ? 141 : 190, // Thacd
                    42 => ($provider_valeur != 51) ? 142 : 191, // Afrique centrale
                ],
                // Mali
                133 =>  [
                    25 => ($provider_valeur != 51) ? 3 : 38, // Benin
                    214 => ($provider_valeur != 51) ? 16 : 52, // Togo
                    36 => ($provider_valeur != 51) ? 9 : 45, // BF
                    188 => ($provider_valeur != 51) ? 17 : 53, // Senegal
                    83 => ($provider_valeur != 51) ? 18 : 54, // Ghana
                    157 => ($provider_valeur != 51) ? 19 : 55, // Niger
                    91 => ($provider_valeur != 51) ? 20 : 56, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 21 : 57, // CIV
                    1 => ($provider_valeur != 51) ? 570 : 243, // USA
                    218 => ($provider_valeur != 51) ? 574 : 294, // Tunisia
                    146 => ($provider_valeur != 51) ? 575 : 303, // Maroc
                    199 => ($provider_valeur != 51) ? 437 : 314, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 469 : 357, // France
                    179 => ($provider_valeur != 51) ? 533 : 421, // Russie
                    226 => ($provider_valeur != 51) ? 501 : 389, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 517 : 405, // Italie
                    82 => ($provider_valeur != 51) ? 485 : 373, // Allemagne
                    39 => ($provider_valeur != 51) ? 143 : 192, // Cameroun
                    79 => ($provider_valeur != 51) ? 144 : 193, // Gabon
                    50 => ($provider_valeur != 51) ? 145 : 194, // COngo Braza
                    57 => ($provider_valeur != 51) ? 146 : 195, // RDC
                    122 => ($provider_valeur != 51) ? 147 : 196, // Liberia
                    43 => ($provider_valeur != 51) ? 148 : 197, // Thacd
                    42 => ($provider_valeur != 51) ? 149 : 198, // Afrique centrale
                ],
                // Togo
                214 =>  [
                    25 => ($provider_valeur != 51) ?  4 : 39, // Benin
                    188 => ($provider_valeur != 51) ? 22 : 58, // Senegal
                    83 => ($provider_valeur != 51) ? 23 : 59, // Ghana
                    36 => ($provider_valeur != 51) ? 10 : 46, // BF
                    133 => ($provider_valeur != 51) ? 16 : 52, // Mali
                    157 => ($provider_valeur != 51) ? 24 : 60, // Niger
                    91 => ($provider_valeur != 51) ? 25 : 61, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 26 : 62, // CIV
                    1 => ($provider_valeur != 51) ? 250 : 244, // USA
                    218 => ($provider_valeur != 51) ? 266 : 295, // Tunisia
                    146 => ($provider_valeur != 51) ? 280 : 304, // Maroc
                    199 => ($provider_valeur != 51) ? 438 : 315, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 470 : 358, // France
                    179 => ($provider_valeur != 51) ? 534 : 422, // Russie
                    226 => ($provider_valeur != 51) ? 502 : 390, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 518 : 406, // Italie
                    82 => ($provider_valeur != 51) ? 486 : 374, // Allemagne
                    39 => ($provider_valeur != 51) ? 150 : 199, // Cameroun
                    79 => ($provider_valeur != 51) ? 151 : 200, // Gabon
                    50 => ($provider_valeur != 51) ? 152 : 201, // COngo Braza
                    57 => ($provider_valeur != 51) ? 153 : 202, // RDC
                    122 => ($provider_valeur != 51) ? 154 : 203, // Liberia
                    43 => ($provider_valeur != 51) ? 155 : 204, // Thacd
                    42 => ($provider_valeur != 51) ? 156 : 205, // Afrique centrale
                ],
                // Senegal
                188 =>  [

                    25 => ($provider_valeur != 51) ?  5 : 40, // Benin
                    83 => ($provider_valeur != 51) ? 27 : 63, // Ghana
                    133 => ($provider_valeur != 51) ? 28 : 64, // Niger
                    36 => ($provider_valeur != 51) ? 11 : 47, // BF
                    188 => ($provider_valeur != 51) ? 17 : 53, // Mali
                    214 => ($provider_valeur != 51) ? 22 : 58, // TOgo
                    91 => ($provider_valeur != 51) ? 29 : 65, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 30 : 66, // CIV
                    1 => ($provider_valeur != 51) ? 251 : 245, // USA
                    218 => ($provider_valeur != 51) ? 267 : 296, // Tunisia
                    146 => ($provider_valeur != 51) ? 281 : 305, // Maroc
                    199 => ($provider_valeur != 51) ? 439 : 316, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 471 : 359, // France
                    179 => ($provider_valeur != 51) ? 535 : 423, // Russie
                    226 => ($provider_valeur != 51) ? 503 : 391, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 519 : 407, // Italie
                    82 => ($provider_valeur != 51) ? 487 : 375, // Allemagne
                    39 => ($provider_valeur != 51) ? 157 : 206, // Cameroun
                    79 => ($provider_valeur != 51) ? 158 : 207, // Gabon
                    50 => ($provider_valeur != 51) ? 159 : 208, // COngo Braza
                    57 => ($provider_valeur != 51) ? 160 : 209, // RDC
                    122 => ($provider_valeur != 51) ? 161 : 210, // Liberia
                    43 => ($provider_valeur != 51) ? 162 : 211, // Thacd
                    42 => ($provider_valeur != 51) ? 163 : 212, // Afrique centrale
                ],
                // Ghana
                83 =>  [
                    25 => ($provider_valeur != 51) ?  6 : 41, // Benin
                    188 => ($provider_valeur != 51) ? 27 : 63, // Senegal
                    157 => ($provider_valeur != 51) ? 31 : 67, // Niger
                    36 => ($provider_valeur != 51) ? 12 : 48, // bf
                    133 => ($provider_valeur != 51) ? 18 : 54, // Mali
                    214 => ($provider_valeur != 51) ? 23 : 59, // Ghana
                    91 => ($provider_valeur != 51) ? 32 : 68, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 33 : 69, // CIV
                    1 => ($provider_valeur != 51) ? 252 : 571, // USA
                    218 => ($provider_valeur != 51) ? 268 : 297, // Tunisia
                    146 => ($provider_valeur != 51) ? 282 : 306, // Maroc
                    199 => ($provider_valeur != 51) ? 440 : 317, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 472 : 360, // France
                    179 => ($provider_valeur != 51) ? 536 : 424, // Russie
                    226 => ($provider_valeur != 51) ? 504 : 392, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 520 : 408, // Italie
                    82 => ($provider_valeur != 51) ? 488 : 376, // Allemagne
                    39 => ($provider_valeur != 51) ? 164 : 213, // Cameroun
                    79 => ($provider_valeur != 51) ? 165 : 214, // Gabon
                    50 => ($provider_valeur != 51) ? 166 : 215, // COngo Braza
                    57 => ($provider_valeur != 51) ? 167 : 216, // RDC
                    122 => ($provider_valeur != 51) ? 168 : 217, // Liberia
                    43 => ($provider_valeur != 51) ? 169 : 218, // Thacd
                    42 => ($provider_valeur != 51) ? 170 : 219, // Afrique centrale
                ],
                // Niger
                157 =>  [
                    25 => ($provider_valeur != 51) ? 7 : 42, // Benin
                    188 => ($provider_valeur != 51) ? 28 : 64, // Senegal
                    91 => ($provider_valeur != 51) ? 34 : 70, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 35 : 71, // CIV
                    214 => ($provider_valeur != 51) ? 24 : 60, // Togo
                    1 => ($provider_valeur != 51) ? 253 : 246, // USA
                    36 => ($provider_valeur != 51) ? 13 : 49, // BF
                    133 => ($provider_valeur != 51) ? 19 : 55, // Mali
                    218 => ($provider_valeur != 51) ? 269 : 298, // Tunisia
                    146 => ($provider_valeur != 51) ? 283 : 307, // Maroc
                    199 => ($provider_valeur != 51) ? 441 : 318, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 473 : 361, // France
                    179 => ($provider_valeur != 51) ? 537 : 425, // Russie
                    226 => ($provider_valeur != 51) ? 505 : 393, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 521 : 409, // Italie
                    82 => ($provider_valeur != 51) ? 489 : 377, // Allemagne
                    39 => ($provider_valeur != 51) ? 576 : 220, // Cameroun
                    79 => ($provider_valeur != 51) ? 577 : 221, // Gabon
                    50 => ($provider_valeur != 51) ? 578 : 222, // COngo Braza
                    57 => ($provider_valeur != 51) ? 579 : 223, // RDC
                    122 => ($provider_valeur != 51) ? 580 : 224, // Liberia
                    43 => ($provider_valeur != 51) ? 581 : 225, // Thacd
                    42 => ($provider_valeur != 51) ? 582 : 226, // Afrique centrale
                ],
                // Guinnée Conakry
                91 =>  [
                    25 => ($provider_valeur != 51) ? 7 : 42, // Benin
                    188 => ($provider_valeur != 51) ? 29 : 65, // Senegal
                    157 => ($provider_valeur != 51) ? 34 : 70, // Niger
                    83 => ($provider_valeur != 51) ?  32 : 68, // Ghana
                    36 => ($provider_valeur != 51) ? 14 : 50, //BF
                    133 => ($provider_valeur != 51) ? 20 : 56, // Mali
                    107 => ($provider_valeur != 51) ? 36 : 72, // CIV
                    214 => ($provider_valeur != 51) ? 25 : 61, //Togo
                    1 => ($provider_valeur != 51) ? 253 : 246, // USA
                    36 => ($provider_valeur != 51) ? 14 : 50, // BF
                    218 => ($provider_valeur != 51) ? 270 : 299, // Tunisia
                    146 => ($provider_valeur != 51) ? 284 : 308, // Maroc
                    199 => ($provider_valeur != 51) ? 442 : 319, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 474 : 362, // France
                    179 => ($provider_valeur != 51) ? 538 : 426, // Russie
                    226 => ($provider_valeur != 51) ? 506 : 394, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 522 : 410, // Italie
                    82 => ($provider_valeur != 51) ? 490 : 378, // Allemagne
                    39 => ($provider_valeur != 51) ? 171 : 227, // Cameroun
                    79 => ($provider_valeur != 51) ? 172 : 228, // Gabon
                    50 => ($provider_valeur != 51) ? 173 : 229, // COngo Braza
                    57 => ($provider_valeur != 51) ? 174 : 230, // RDC
                    122 => ($provider_valeur != 51) ? 175 : 231, // Liberia
                    43 => ($provider_valeur != 51) ? 176 : 232, // Thacd
                    42 => ($provider_valeur != 51) ? 177 : 233, // Afrique centrale
                ]

                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } elseif ($groupe_name == 'AFC') {

            $countriesLevels = [
                // Is Cameroun
                39 => [
                    25 => ($provider_valeur != 51) ? 129 : 178, // Benin
                    36 => ($provider_valeur != 51) ? 136 : 185, // BF
                    133 => ($provider_valeur != 51) ? 143 : 192, // Mali
                    214 => ($provider_valeur != 51) ? 150 : 199, // Togo
                    188 => ($provider_valeur != 51) ? 157 : 206, // Senegal
                    83 => ($provider_valeur != 51) ? 164 : 213, // Ghana
                    157 => ($provider_valeur != 51) ? 576 : 220, // Niger
                    91 => ($provider_valeur != 51) ? 171 : 227, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 79 : 107, // CIV
                    1 => ($provider_valeur != 51) ? 255 : 234, // USA
                    218 => ($provider_valeur != 51) ? 271 : 327, // Tunisia
                    146 => ($provider_valeur != 51) ? 285 : 333, // Maroc
                    199 => ($provider_valeur != 51) ? 444 : 321, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 476 : 364, // France
                    179 => ($provider_valeur != 51) ? 540 : 428, // Russie
                    226 => ($provider_valeur != 51) ? 508 : 396, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 515 : 403, // Italie
                    82 => ($provider_valeur != 51) ? 524 : 412, // Allemagne
                    // 39 => ($provider_valeur != 51) ? 129 : 178, // Cameroun
                    79 => ($provider_valeur != 51) ? 73 : 101, // Gabon
                    50 => ($provider_valeur != 51) ? 74 : 102, // COngo Braza
                    57 => ($provider_valeur != 51) ? 75 : 103, // RDC
                    122 => ($provider_valeur != 51) ? 76 : 104, // Liberia
                    43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ? 78 : 106, // Afrique centrale
                ],
                // Gabon
                79 =>  [
                    25 => ($provider_valeur != 51) ? 130 : 179, // Benin
                    36 => ($provider_valeur != 51) ? 137 : 186, // BF
                    133 => ($provider_valeur != 51) ? 144 : 193, // Mali
                    214 => ($provider_valeur != 51) ? 151 : 200, // Togo
                    188 => ($provider_valeur != 51) ? 158 : 207, // Senegal
                    83 => ($provider_valeur != 51) ? 165 : 214, // Ghana
                    157 => ($provider_valeur != 51) ? 577 : 221, // Niger
                    91 => ($provider_valeur != 51) ? 173 : 229, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 85 : 113, // CIV
                    1 => ($provider_valeur != 51) ? 256 : 235, // USA
                    218 => ($provider_valeur != 51) ? 272 : 328, // Tunisia
                    146 => ($provider_valeur != 51) ? 286 : 334, // Maroc
                    199 => ($provider_valeur != 51) ? 445 : 322, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 477 : 365, // France
                    179 => ($provider_valeur != 51) ? 541 : 429, // Russie
                    226 => ($provider_valeur != 51) ? 509 : 397, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 525 : 413, // Italie
                    82 => ($provider_valeur != 51) ? 493 : 381, // Allemagne
                    39 => ($provider_valeur != 51) ? 73 : 101, // Cameroun
                    // 79 => ($provider_valeur != 51) ? 137 : 186, // Gabon
                    50 => ($provider_valeur != 51) ? 80 : 108, // COngo Braza
                    57 => ($provider_valeur != 51) ? 81 : 109, // RDC
                    122 => ($provider_valeur != 51) ? 82 : 110, // Liberia
                    43 => ($provider_valeur != 51) ? 83 : 111, // Thacd
                    42 => ($provider_valeur != 51) ? 84 : 112, // Afrique centrale
                ],
                // Congo Brazaville
                50 =>  [
                    25 => ($provider_valeur != 51) ?  131 : 180, // Benin
                    214 => ($provider_valeur != 51) ? 152 : 201, // Togo
                    36 => ($provider_valeur != 51) ? 138 : 187, // BF
                    188 => ($provider_valeur != 51) ? 159 : 208, // Senegal
                    83 => ($provider_valeur != 51) ? 166 : 215, // Ghana
                    157 => ($provider_valeur != 51) ? 578 : 222, // Niger
                    91 => ($provider_valeur != 51) ? 173 : 229, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 90 : 118, // CIV
                    1 => ($provider_valeur != 51) ? 570 : 243, // USA
                    218 => ($provider_valeur != 51) ? 574 : 294, // Tunisia
                    146 => ($provider_valeur != 51) ? 575 : 303, // Maroc
                    199 => ($provider_valeur != 51) ? 437 : 314, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 469 : 357, // France
                    179 => ($provider_valeur != 51) ? 533 : 421, // Russie
                    226 => ($provider_valeur != 51) ? 501 : 389, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 517 : 405, // Italie
                    82 => ($provider_valeur != 51) ? 485 : 373, // Allemagne
                    39 => ($provider_valeur != 51) ? 143 : 192, // Cameroun
                    79 => ($provider_valeur != 51) ? 144 : 193, // Gabon
                    // 50 => ($provider_valeur != 51) ? 145 : 194, // COngo Braza
                    57 => ($provider_valeur != 51) ? 86 : 114, // RDC
                    122 => ($provider_valeur != 51) ? 87 : 115, // Liberia
                    43 => ($provider_valeur != 51) ? 88 : 116, // Thacd
                    42 => ($provider_valeur != 51) ? 89 : 117, // Afrique centrale
                ],
                // RDC
                57 =>  [
                    25 => ($provider_valeur != 51) ?  132 : 181, // Benin
                    188 => ($provider_valeur != 51) ? 160 : 209, // Senegal
                    83 => ($provider_valeur != 51) ? 167 : 216, // Ghana
                    36 => ($provider_valeur != 51) ? 139 : 188, // BF
                    133 => ($provider_valeur != 51) ? 146 : 195, // Mali
                    157 => ($provider_valeur != 51) ? 579 : 223, // Niger
                    91 => ($provider_valeur != 51) ? 174 : 230, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 94 : 122, // CIV
                    1 => ($provider_valeur != 51) ? 258 : 237, // USA
                    218 => ($provider_valeur != 51) ? 374 : 329, // Tunisia
                    146 => ($provider_valeur != 51) ? 288 : 335, // Maroc
                    199 => ($provider_valeur != 51) ? 447 : 323, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 479 : 367, // France
                    179 => ($provider_valeur != 51) ? 543 : 431, // Russie
                    226 => ($provider_valeur != 51) ? 511 : 399, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 527 : 415, // Italie
                    82 => ($provider_valeur != 51) ? 495 : 383, // Allemagne
                    39 => ($provider_valeur != 51) ? 75 : 103, // Cameroun
                    79 => ($provider_valeur != 51) ? 81 : 109, // Gabon
                    50 => ($provider_valeur != 51) ? 86 : 114, // COngo Braza
                    // 57 => ($provider_valeur != 51) ? 153 : 202, // RDC
                    122 => ($provider_valeur != 51) ? 91 : 119, // Liberia
                    43 => ($provider_valeur != 51) ? 92 : 120, // Thacd
                    42 => ($provider_valeur != 51) ? 93 : 121, // Afrique centrale
                ],
                // Liberia
                122 =>  [

                    25 => ($provider_valeur != 51) ? 133 : 182, // Benin
                    83 => ($provider_valeur != 51) ? 168 : 217, // Ghana
                    133 => ($provider_valeur != 51) ?  580 : 224, // Niger
                    36 => ($provider_valeur != 51) ? 140 : 189, // BF
                    188 => ($provider_valeur != 51) ? 147 : 196, // Mali
                    214 => ($provider_valeur != 51) ? 154 : 203, // TOgo
                    91 => ($provider_valeur != 51) ?  175 : 231, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 97 : 125, // CIV
                    1 => ($provider_valeur != 51) ? 238 : 259, // USA
                    218 => ($provider_valeur != 51) ? 275 : 330, // Tunisia
                    146 => ($provider_valeur != 51) ? 336 : 289, // Maroc
                    199 => ($provider_valeur != 51) ? 448 : 324, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 368 : 480, // France
                    179 => ($provider_valeur != 51) ? 544 : 432, // Russie
                    226 => ($provider_valeur != 51) ? 512 : 400, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 416 : 528, // Italie
                    82 => ($provider_valeur != 51) ? 496 : 384, // Allemagne
                    39 => ($provider_valeur != 51) ? 157 : 206, // Cameroun
                    79 => ($provider_valeur != 51) ? 158 : 207, // Gabon
                    50 => ($provider_valeur != 51) ? 159 : 208, // COngo Braza
                    57 => ($provider_valeur != 51) ? 160 : 209, // RDC
                    // 122 => ($provider_valeur != 51) ? 161 : 210, // Liberia
                    43 => ($provider_valeur != 51) ? 95 : 123, // Thacd
                    42 => ($provider_valeur != 51) ? 96 : 124, // Afrique centrale
                ],
                // Tchad
                43 =>  [
                    25 => ($provider_valeur != 51) ? 134 : 183, // Benin
                    188 => ($provider_valeur != 51) ? 162 : 211, // Senegal
                    157 => ($provider_valeur != 51) ? 581 : 225, // Niger
                    36 => ($provider_valeur != 51) ? 141 : 190, // bf
                    133 => ($provider_valeur != 51) ? 148 : 197, // Mali
                    214 => ($provider_valeur != 51) ? 169 : 218, // Ghana
                    91 => ($provider_valeur != 51) ? 176 : 232, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 99 : 127, // CIV
                    1 => ($provider_valeur != 51) ? 260 : 239, // USA
                    218 => ($provider_valeur != 51) ? 276 : 331, // Tunisia
                    146 => ($provider_valeur != 51) ? 290 : 337, // Maroc
                    199 => ($provider_valeur != 51) ? 448 : 325, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 481 : 369, // France
                    179 => ($provider_valeur != 51) ? 545 : 433, // Russie
                    226 => ($provider_valeur != 51) ? 513 : 401, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 529 : 417, // Italie
                    82 => ($provider_valeur != 51) ? 497 : 385, // Allemagne
                    39 => ($provider_valeur != 51) ? 77 : 105, // Cameroun
                    79 => ($provider_valeur != 51) ? 83 : 111, // Gabon
                    50 => ($provider_valeur != 51) ?  88 : 116, // COngo Braza
                    57 => ($provider_valeur != 51) ? 92 : 120, // RDC
                    122 => ($provider_valeur != 51) ? 95 : 123, // Liberia
                    // 43 => ($provider_valeur != 51) ? 169 : 218, // Thacd
                    42 => ($provider_valeur != 51) ? 98 : 126, // Afrique centrale
                ],
                // Centre Afrique
                42 =>  [
                    25 => ($provider_valeur != 51) ? 135 : 184, // Benin
                    188 => ($provider_valeur != 51) ? 163 : 212, // Senegal
                    157 => ($provider_valeur != 51) ? 582 : 226, // Niger
                    83 => ($provider_valeur != 51) ?  170 : 219, // Ghana
                    36 => ($provider_valeur != 51) ? 142 : 191, //BF
                    133 => ($provider_valeur != 51) ? 149 : 198, // Mali
                    107 => ($provider_valeur != 51) ? 100 : 128, // CIV
                    214 => ($provider_valeur != 51) ? 156 : 205, //Togo
                    1 => ($provider_valeur != 51) ? 261 : 240, // USA
                    // 36 => ($provider_valeur != 51) ? 14 : 50, // BF
                    218 => ($provider_valeur != 51) ? 277 : 332, // Tunisia
                    146 => ($provider_valeur != 51) ? 291 : 338, // Maroc
                    199 => ($provider_valeur != 51) ? 450 : 326, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 482 : 370, // France
                    179 => ($provider_valeur != 51) ? 546 : 434, // Russie
                    226 => ($provider_valeur != 51) ? 514 : 402, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 530 : 418, // Italie
                    82 => ($provider_valeur != 51) ? 498 : 386, // Allemagne
                    39 => ($provider_valeur != 51) ? 78 : 106, // Cameroun
                    79 => ($provider_valeur != 51) ?  84 : 112, // Gabon
                    50 => ($provider_valeur != 51) ? 89 : 117, // COngo Braza
                    57 => ($provider_valeur != 51) ? 93 : 121, // RDC
                    122 => ($provider_valeur != 51) ? 96 : 124, // Liberia
                    43 => ($provider_valeur != 51) ? 98 : 126, // Thacd
                    // 42 => ($provider_valeur != 51) ? 177 : 233, // Afrique centrale
                ]
                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } elseif ($groupe_name == 'CIV') {


            $countriesLevels = [
                // Is Benin
                107 => [
                    36 => ($provider_valeur != 51) ? 15 : 51, // BF
                    25 => ($provider_valeur != 51) ? 1 : 44, // BENIN
                    133 => ($provider_valeur != 51) ? 21 : 57, // Mali
                    214 => ($provider_valeur != 51) ? 26 : 62, // Togo
                    188 => ($provider_valeur != 51) ? 30 : 66, // Senegal
                    83 => ($provider_valeur != 51) ? 33 : 69, // Ghana
                    157 => ($provider_valeur != 51) ? 35 : 71, // Niger
                    91 => ($provider_valeur != 51) ? 36 : 72, // Guinne conakry
                    // 107 => ($provider_valeur != 51) ? 1 : 44, // CIV
                    // Begin Vue
                    1 => ($provider_valeur != 51) ? 248 : 241, // USA
                    218 => ($provider_valeur != 51) ? 264 : 292, // Tunisia
                    146 => ($provider_valeur != 51) ? 278 : 302, // Maroc
                    199 => ($provider_valeur != 51) ? 435 : 312, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 467 : 355, // France
                    179 => ($provider_valeur != 51) ? 531 : 419, // Russie
                    226 => ($provider_valeur != 51) ? 499 : 387, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 515 : 403, // Italie
                    82 => ($provider_valeur != 51) ? 483 : 371, // Allemagne
                    // End view
                    39 => ($provider_valeur != 51) ? 79 : 107, // Cameroun
                    79 => ($provider_valeur != 51) ? 85 : 113, // Gabon
                    50 => ($provider_valeur != 51) ? 90 : 118, // COngo Braza
                    57 => ($provider_valeur != 51) ? 94 : 122, // RDC
                    122 => ($provider_valeur != 51) ? 97 : 125, // Liberia
                    43 => ($provider_valeur != 51) ? 99 : 127, // Thacd
                    42 => ($provider_valeur != 51) ? 100 : 128, // Afrique centrale
                ]
            ];
        } elseif ($groupe_name == 'URP') {

            $countriesLevels = [
                // Is Espagne
                199 => [
                    25 => ($provider_valeur != 51) ? 435 : 312, // Benin
                    36 => ($provider_valeur != 51) ? 436 : 313, // BF
                    133 => ($provider_valeur != 51) ? 437 : 314, // Mali
                    214 => ($provider_valeur != 51) ? 438 : 315, // Togo
                    188 => ($provider_valeur != 51) ? 439 : 316, // Senegal
                    83 => ($provider_valeur != 51) ?  440 : 317, // Ghana
                    157 => ($provider_valeur != 51) ? 441 : 318, // Niger
                    91 => ($provider_valeur != 51) ?  442 : 319, // Guinne conakry
                    // End vue
                    107 => ($provider_valeur != 51) ? 443 : 320, // CIV
                    1 => ($provider_valeur == 51) ? 547 : null, // USA
                    218 => ($provider_valeur == 51) ? 556 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 563 : null, // Maroc

                    // 199 => ($provider_valeur != 51) ? 444 : 321, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 584 : null, // France
                    179 => ($provider_valeur == 51) ? 587 : null, // Russie
                    226 => ($provider_valeur == 51) ? 588 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 589 : null, // Italie
                    82 => ($provider_valeur == 51) ? 586 : null, // Allemagne

                    // End vue
                    39 => ($provider_valeur != 51) ? 444 : 321, // Cameroun
                    79 => ($provider_valeur != 51) ? 445 : 322, // Gabon
                    50 => ($provider_valeur != 51) ? 437 : 314, // COngo Braza
                    57 => ($provider_valeur != 51) ? 447 : 323, // RDC
                    122 => ($provider_valeur != 51) ? 448 : 324, // Liberia
                    43 => ($provider_valeur != 51) ? 448 : 325, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ?  450 : 326, // Afrique centrale
                ],
                // France
                74 =>  [
                    25 => ($provider_valeur != 51) ?  467 : 355, // Benin
                    36 => ($provider_valeur != 51) ? 468 : 356, // BF
                    133 => ($provider_valeur != 51) ? 469 : 357, // Mali
                    214 => ($provider_valeur != 51) ? 470 : 358, // Togo
                    188 => ($provider_valeur != 51) ? 471 : 359, // Senegal
                    83 => ($provider_valeur != 51) ? 472 : 360, // Ghana
                    157 => ($provider_valeur != 51) ? 473 : 361, // Niger
                    91 => ($provider_valeur != 51) ? 474 : 362, // Guinne conakry
                    // vue France Ancien
                    107 => ($provider_valeur != 51) ? 85 : 113, // CIV
                    // En ancien
                    1 => ($provider_valeur == 51) ? 548 : null, // USA
                    218 => ($provider_valeur == 51) ? 557 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 564 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 584 : null, // ESPAGNE
                    // 74 => ($provider_valeur != 51) ? 477 : 365, // France

                    179 => ($provider_valeur == 51) ? 594 : null, // Russie
                    226 => ($provider_valeur == 51) ? 592 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 593 : null, // Italie
                    82 => ($provider_valeur == 51) ? 591 : null, // Allemagne

                    39 => ($provider_valeur != 51) ? 476 : 364, // Cameroun
                    79 => ($provider_valeur != 51) ? 477 : 365, // Gabon
                    50 => ($provider_valeur != 51) ? 469 : 357, // COngo Braza
                    57 => ($provider_valeur != 51) ? 479 : 367, // RDC
                    122 => ($provider_valeur != 51) ? 368 : 480, // Liberia
                    43 => ($provider_valeur != 51) ? 481 : 369, // Thacd
                    42 => ($provider_valeur != 51) ? 482 : 370, // Afrique centrale
                ],
                // Allemagne
                82 =>  [
                    25 => ($provider_valeur != 51) ?  483 : 371, // Benin
                    36 => ($provider_valeur != 51) ? 484 : 372, // BF
                    133 => ($provider_valeur != 51) ? 485 : 373, // Mali
                    214 => ($provider_valeur != 51) ? 486 : 374, // Togo
                    188 => ($provider_valeur != 51) ? 487 : 375, // Senegal
                    83 => ($provider_valeur != 51) ? 488 : 376, // Ghana
                    157 => ($provider_valeur != 51) ? 489 : 377, // Niger
                    91 => ($provider_valeur != 51) ? 490 : 378, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 491 : 379, // CIV

                    1 => ($provider_valeur == 51) ? 550 : null, // USA
                    218 => ($provider_valeur == 51) ? 559 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 566 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 584 : null, // ESPAGNE
                    // 74 => ($provider_valeur != 51) ? 477 : 365, // France

                    179 => ($provider_valeur == 51) ? 601 : null, // Russie
                    226 => ($provider_valeur == 51) ? 599 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 600 : null, // Italie
                    // 82 => ($provider_valeur == 51) ? 591 : null, // Allemagne

                    39 => ($provider_valeur != 51) ? 524 : 412, // Cameroun
                    79 => ($provider_valeur != 51) ? 493 : 381, // Gabon
                    50 => ($provider_valeur != 51) ? 485 : 373, // COngo Braza
                    57 => ($provider_valeur != 51) ? 495 : 383, // RDC
                    122 => ($provider_valeur != 51) ? 496 : 384, // Liberia
                    43 => ($provider_valeur != 51) ? 497 : 385, // Thacd
                    42 => ($provider_valeur != 51) ? 498 : 386, // Afrique centrale
                ],
                // Italie
                106 =>  [
                    25 => ($provider_valeur != 51) ?  515 : 403, // Benin
                    36 => ($provider_valeur != 51) ? 516 : 404, // BF
                    133 => ($provider_valeur != 51) ? 517 : 405, // Mali
                    214 => ($provider_valeur != 51) ? 518 : 406, // Togo
                    188 => ($provider_valeur != 51) ? 519 : 407, // Senegal
                    83 => ($provider_valeur != 51) ? 520 : 408, // Ghana
                    157 => ($provider_valeur != 51) ? 521 : 409, // Niger
                    91 => ($provider_valeur != 51) ? 522 : 410, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 523 : 411, // CIV

                    1 => ($provider_valeur == 51) ? 552 : null, // USA
                    218 => ($provider_valeur == 51) ? 561 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 568 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 589 : null, // ESPAGNE
                    // 74 => ($provider_valeur != 51) ? 477 : 365, // France

                    179 => ($provider_valeur == 51) ? 604 : null, // Russie
                    226 => ($provider_valeur == 51) ? 602 : null, // Royaume Unie
                    // 106 => ($provider_valeur == 51) ? 600 : null, // Italie
                    82 => ($provider_valeur == 51) ? 591 : null, // Allemagne

                    39 => ($provider_valeur != 51) ? 515 : 403, // Cameroun
                    79 => ($provider_valeur != 51) ? 525 : 413, // Gabon
                    50 => ($provider_valeur != 51) ? 517 : 405, // COngo Braza
                    57 => ($provider_valeur != 51) ? 527 : 415, // RDC
                    122 => ($provider_valeur != 51) ? 416 : 528, // Liberia
                    43 => ($provider_valeur != 51) ? 529 : 417, // Thacd
                    42 => ($provider_valeur != 51) ? 530 : 418, // Afrique centrale
                ],
                // Royaume Unie
                226 =>  [
                    25 => ($provider_valeur != 51) ?  499 : 387, // Benin
                    36 => ($provider_valeur != 51) ? 500 : 388, // BF
                    133 => ($provider_valeur != 51) ? 501 : 389, // Mali
                    214 => ($provider_valeur != 51) ? 502 : 390, // Togo
                    188 => ($provider_valeur != 51) ? 503 : 391, // Senegal
                    83 => ($provider_valeur != 51) ? 504 : 392, // Ghana
                    157 => ($provider_valeur != 51) ? 505 : 393, // Niger
                    91 => ($provider_valeur != 51) ? 506 : 394, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 507 : 395, // CIV

                    1 => ($provider_valeur == 51) ? 551 : null, // USA
                    218 => ($provider_valeur == 51) ? 560 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 567 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 588 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 592 : null, // France

                    179 => ($provider_valeur == 51) ? 603 : null, // Russie
                    // 226 => ($provider_valeur == 51) ? 602 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 602 : null, // Italie
                    82 => ($provider_valeur == 51) ? 599 : null, // Allemagne

                    39 => ($provider_valeur != 51) ? 508 : 396, // Cameroun
                    79 => ($provider_valeur != 51) ? 509 : 397, // Gabon
                    50 => ($provider_valeur != 51) ? 501 : 389, // COngo Braza
                    57 => ($provider_valeur != 51) ? 511 : 399, // RDC
                    122 => ($provider_valeur != 51) ? 512 : 400, // Liberia
                    43 => ($provider_valeur != 51) ? 513 : 401, // Thacd
                    42 => ($provider_valeur != 51) ? 514 : 402, // Afrique centrale
                ],
                // Ruissie
                179 =>  [
                    25 => ($provider_valeur != 51) ? 531 : 419, // Benin
                    36 => ($provider_valeur != 51) ? 532 : 420, // BF
                    133 => ($provider_valeur != 51) ? 533 : 421, // Mali
                    214 => ($provider_valeur != 51) ? 534 : 422, // Togo
                    188 => ($provider_valeur != 51) ? 535 : 423, // Senegal
                    83 => ($provider_valeur != 51) ? 536 : 424, // Ghana
                    157 => ($provider_valeur != 51) ? 537 : 425, // Niger
                    91 => ($provider_valeur != 51) ?  538 : 426, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 539 : 427, // CIV

                    1 => ($provider_valeur == 51) ? 554 : null, // USA
                    218 => ($provider_valeur == 51) ? 562 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 593 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 587 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 594 : null, // France

                    // 179 => ($provider_valeur == 51) ? 603 : null, // Russie
                    226 => ($provider_valeur == 51) ? 603 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 604 : null, // Italie
                    82 => ($provider_valeur == 51) ? 601 : null, // Allemagne

                    39 => ($provider_valeur != 51) ? 540 : 428, // Cameroun
                    79 => ($provider_valeur != 51) ? 541 : 429, // Gabon
                    50 => ($provider_valeur != 51) ? 533 : 421, // COngo Braza
                    57 => ($provider_valeur != 51) ? 543 : 431, // RDC
                    122 => ($provider_valeur != 51) ? 544 : 432, // Liberia
                    43 => ($provider_valeur != 51) ?  545 : 433, // Thacd
                    42 => ($provider_valeur != 51) ? 546 : 434, // Afrique centrale
                ],
                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } elseif ($groupe_name == 'USA') {

            $countriesLevels = [
                // Is USA
                1 => [
                    25 => ($provider_valeur != 51) ? 248 : 241, // Benin
                    36 => ($provider_valeur != 51) ? 249 : 242, // BF
                    133 => ($provider_valeur != 51) ? 570 : 243, // Mali
                    214 => ($provider_valeur != 51) ? 250 : 244, // Togo
                    188 => ($provider_valeur != 51) ? 251 : 245, // Senegal
                    83 => ($provider_valeur != 51) ? 252 : 571, // Ghana
                    157 => ($provider_valeur != 51) ? 253 : 246, // Niger
                    91 => ($provider_valeur != 51) ? 35 : 71, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 79 : 107, // CIV
                    // 1 => ($provider_valeur != 51) ? 255 : 234, // USA
                    218 => ($provider_valeur == 51) ? 554 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 555 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 547 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 548 : null, // France
                    179 => ($provider_valeur == 51) ? 553 : null, // Russie
                    226 => ($provider_valeur == 51) ? 551 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 552 : null, // Italie
                    82 => ($provider_valeur == 51) ? 550 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 255 : 234, // Cameroun
                    79 => ($provider_valeur != 51) ? 256 : 235, // Gabon
                    50 => ($provider_valeur != 51) ? 570 : 243, // COngo Braza
                    57 => ($provider_valeur != 51) ? 258 : 237, // RDC
                    122 => ($provider_valeur != 51) ? 238 : 259, // Liberia
                    43 => ($provider_valeur != 51) ? 260 : 239, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ? 261 : 240, // Afrique centrale
                ],
                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } elseif ($groupe_name == 'MAG') {

            $countriesLevels = [
                // Is Tunisia
                218 => [
                    25 => ($provider_valeur != 51) ? 264 : 292, // Benin
                    36 => ($provider_valeur != 51) ? 265 : 293, // BF
                    133 => ($provider_valeur != 51) ? 574 : 294, // Mali
                    214 => ($provider_valeur != 51) ? 266 : 295, // Togo
                    188 => ($provider_valeur != 51) ? 267 : 296, // Senegal
                    83 => ($provider_valeur != 51) ? 268 : 297, // Ghana
                    157 => ($provider_valeur != 51) ? 269 : 298, // Niger
                    91 => ($provider_valeur != 51) ? 270 : 299, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 310 : 300, // CIV
                    1 => ($provider_valeur != 51) ? 255 : 234, // USA
                    // 218 => ($provider_valeur == 51) ? 554 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 593 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 556 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 557 : null, // France
                    179 => ($provider_valeur == 51) ? 562 : null, // Russie
                    226 => ($provider_valeur == 51) ? 560 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 561 : null, // Italie
                    82 => ($provider_valeur == 51) ? 559 : null, // Allemagne
                    39 => ($provider_valeur != 51) ?  271 : 327, // Cameroun
                    79 => ($provider_valeur != 51) ? 272 : 328, // Gabon
                    50 => ($provider_valeur != 51) ? 574 : 294, // COngo Braza
                    57 => ($provider_valeur != 51) ? 374 : 329, // RDC
                    122 => ($provider_valeur != 51) ? 275 : 330, // Liberia
                    43 => ($provider_valeur != 51) ? 276 : 331, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ? 277 : 332, // Afrique centrale
                ],
                // Is Maroc
                146 => [
                    25 => ($provider_valeur != 51) ?  278 : 302, // Benin
                    36 => ($provider_valeur != 51) ? 279 : 302, // BF
                    133 => ($provider_valeur != 51) ? 575 : 303, // Mali
                    214 => ($provider_valeur != 51) ? 280 : 304, // Togo
                    188 => ($provider_valeur != 51) ? 281 : 305, // Senegal
                    83 => ($provider_valeur != 51) ? 282 : 306, // Ghana
                    157 => ($provider_valeur != 51) ? 283 : 307, // Niger
                    91 => ($provider_valeur != 51) ? 284 : 308, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 311 : 309, // CIV
                    1 => ($provider_valeur == 51) ? 555 : null, // USA
                    218 => ($provider_valeur == 51) ? 593 : null, // Tunisia
                    // 146 => ($provider_valeur == 51) ? 593 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 563 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 564 : null, // France
                    179 => ($provider_valeur == 51) ? 569 : null, // Russie
                    226 => ($provider_valeur == 51) ? 567 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 568 : null, // Italie
                    82 => ($provider_valeur == 51) ? 566 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 285 : 333, // Cameroun
                    79 => ($provider_valeur != 51) ? 286 : 334, // Gabon
                    50 => ($provider_valeur != 51) ? 575 : 303, // COngo Braza
                    57 => ($provider_valeur != 51) ? 288 : 335, // RDC
                    122 => ($provider_valeur != 51) ? 336 : 289, // Liberia
                    43 => ($provider_valeur != 51) ? 290 : 337, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ?  291 : 338, // Afrique centrale
                ],
                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } else {
            return null;
        }


        if (isset($countriesLevels[$countryId])) {

            $specificCountry = $countriesLevels[$countryId][$countrie->id];

            if ($specificCountry) {
                $levelValeur = $specificCountry;
            } else {
                return null; // La levelValeur pour $provider_valeur n'est pas définie pour $countrie
            }
        } else {
            return null; // Aucune correspondance pour $countrie
        }

        return  LevelCountry::whereId($levelValeur)->first();
    }

    public function searchByTransactionsDate(Request $request)
    {

        $this->validate($request, [
            'search_begin' => 'required|date',
            'search_end' => 'required|date|after_or_equal:search_begin',
        ]);


        $searchDateBegin = $request->input('search_begin');

        $searchDateEnd = $request->input('search_end');

        $walletInters = $this->getTransactionDate(WalletTransaction::class, $searchDateBegin, $searchDateEnd);

        $account_penddings =  $this->getTransactionCount(WalletTransaction::class, 'PENDING', $searchDateBegin, $searchDateEnd);;

        $account_success = $this->getTransactionCount(WalletTransaction::class, 'SUCCESS', $searchDateBegin, $searchDateEnd);

        $account_errors = $this->getTransactionCount(WalletTransaction::class, 'ERROR', $searchDateBegin, $searchDateEnd);

        $amount_total = $this->walletTransactionsDays(WalletTransaction::class, $searchDateBegin, $searchDateEnd);

        $pdf = Pdf::loadView('pages.historicals.transactionsDays.pdf_transfer', compact('amount_total', 'walletInters', 'account_penddings', 'account_success', 'account_errors', 'searchDateBegin', 'searchDateEnd'))->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
        return $pdf->download('reporting_transactions_days.pdf');
    }

    public function searchByBanksDate(Request $request)
    {
        $this->validate($request, [
            'search_begin' => 'required|date',
            'search_end' => 'required|date|after_or_equal:search_begin',
        ]);


        $searchDateBegin = $request->input('search_begin');

        $searchDateEnd = $request->input('search_end');

        $walletInters = $this->getTransactionDate(WalletBank::class, $searchDateBegin, $searchDateEnd);

        $account_penddings =  $this->getTransactionCount(WalletBank::class, 'PENDING', $searchDateBegin, $searchDateEnd);;

        $account_success = $this->getTransactionCount(WalletBank::class, 'SUCCESS', $searchDateBegin, $searchDateEnd);

        $account_errors = $this->getTransactionCount(WalletBank::class, 'ERROR', $searchDateBegin, $searchDateEnd);

        $amount_total = $this->walletTransactionsDays(WalletBank::class, $searchDateBegin, $searchDateEnd);

        $pdf = Pdf::loadView('pages.historicals.transactionsDays.pdf_bank', compact('amount_total', 'walletInters', 'account_penddings', 'account_success', 'account_errors', 'searchDateBegin', 'searchDateEnd'))->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
        return $pdf->download('reporting_payments_days.pdf');
    }

    public function searchByXbetsDate(Request $request)
    {

        $this->validate($request, [
            'search_begin' => 'required|date',
            'search_end' => 'required|date|after_or_equal:search_begin',
        ]);


        $searchDateBegin = $request->input('search_begin');

        $searchDateEnd = $request->input('search_end');

        $walletInters = $this->getTransactionDate(WalletXbet::class, $searchDateBegin, $searchDateEnd);

        $account_penddings =  $this->getTransactionCount(WalletXbet::class, 'PENDING', $searchDateBegin, $searchDateEnd);;

        $account_success = $this->getTransactionCount(WalletXbet::class, 'SUCCESS', $searchDateBegin, $searchDateEnd);

        $account_errors = $this->getTransactionCount(WalletXbet::class, 'ERROR', $searchDateBegin, $searchDateEnd);

        $amount_total = $this->walletTransactionsDays(WalletXbet::class, $searchDateBegin, $searchDateEnd);

        $pdf = Pdf::loadView('pages.historicals.transactionsDays.pdf_bank', compact('amount_total', 'walletInters', 'account_penddings', 'account_success', 'account_errors', 'searchDateBegin', 'searchDateEnd'))->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
        return $pdf->download('reporting_payments_days.pdf');
    }

    public function searchBywavesDate(Request $request)
    {

        $this->validate($request, [
            'search_begin' => 'required|date',
            'search_end' => 'required|date|after_or_equal:search_begin',
        ]);


        $searchDateBegin = $request->input('search_begin');

        $searchDateEnd = $request->input('search_end');

        $walletInters = $this->getTransactionDate(WalletWave::class, $searchDateBegin, $searchDateEnd);

        $account_penddings =  $this->getTransactionCount(WalletWave::class, 'PENDING', $searchDateBegin, $searchDateEnd);;

        $account_success = $this->getTransactionCount(WalletWave::class, 'SUCCESS', $searchDateBegin, $searchDateEnd);

        $account_errors = $this->getTransactionCount(WalletWave::class, 'ERROR', $searchDateBegin, $searchDateEnd);

        $amount_total = $this->walletTransactionsDays(WalletWave::class, $searchDateBegin, $searchDateEnd);

        $pdf = Pdf::loadView('pages.historicals.transactionsDays.pdf_wave', compact('amount_total', 'walletInters', 'account_penddings', 'account_success', 'account_errors', 'searchDateBegin', 'searchDateEnd'))->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
        return $pdf->download('reporting_waves_days.pdf');
    }

    public function searchByCartesDate(Request $request)
    {
        $this->validate($request, [
            'search_begin' => 'required|date',
            'search_end' => 'required|date|after_or_equal:search_begin',
        ]);


        $searchDateBegin = $request->input('search_begin');

        $searchDateEnd = $request->input('search_end');

        $walletInters = $this->getTransactionDate(WalletDeposit::class, $searchDateBegin, $searchDateEnd);

        $account_penddings =  $this->getTransactionCount(WalletDeposit::class, 'PENDING', $searchDateBegin, $searchDateEnd);;

        $account_success = $this->getTransactionCount(WalletDeposit::class, 'SUCCESS', $searchDateBegin, $searchDateEnd);

        $account_errors = $this->getTransactionCount(WalletDeposit::class, 'ERROR', $searchDateBegin, $searchDateEnd);

        $amount_total = $this->walletTransactionsDays(WalletDeposit::class, $searchDateBegin, $searchDateEnd);

        $pdf = Pdf::loadView('pages.historicals.transactionsDays.pdf_card_bank', compact('amount_total', 'walletInters', 'account_penddings', 'account_success', 'account_errors', 'searchDateBegin', 'searchDateEnd'))->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
        return $pdf->download('reporting_cartes_days.pdf');
    }

    public function searchByPushsDate(Request $request)
    {
        $this->validate($request, [
            'search_begin' => 'required|date',
            'search_end' => 'required|date|after_or_equal:search_begin',
        ]);


        $searchDateBegin = $request->input('search_begin');

        $searchDateEnd = $request->input('search_end');

        $push_historicals = $this->getTransactionDate(WalletPush::class, $searchDateBegin, $searchDateEnd);

        $account_penddings =  $this->getTransactionCount(WalletPush::class, 'PENDING', $searchDateBegin, $searchDateEnd);;

        $account_success = $this->getTransactionCount(WalletPush::class, 'SUCCESS', $searchDateBegin, $searchDateEnd);

        $account_errors = $this->getTransactionCount(WalletPush::class, 'ERROR', $searchDateBegin, $searchDateEnd);

        $amount_total = $this->walletTransactionsDays(WalletPush::class, $searchDateBegin, $searchDateEnd);

        $pdf = Pdf::loadView('pages.historicals.transactionsDays.pdf_auther_transactions', compact('amount_total', 'push_historicals', 'account_penddings', 'account_success', 'account_errors', 'searchDateBegin', 'searchDateEnd'))->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
        return $pdf->download('reporting_auther_days.pdf');
    }

    public function searchByDjamoDate(Request $request)
    {
        $this->validate($request, [
            'search_begin' => 'required|date',
            'search_end' => 'required|date|after_or_equal:search_begin',
        ]);


        $searchDateBegin = $request->input('search_begin');

        $searchDateEnd = $request->input('search_end');

        $walletTransactions = $this->getTransactionDate(WalletDjamo::class, $searchDateBegin, $searchDateEnd);

        $account_penddings =  $this->getTransactionCount(WalletDjamo::class, 'PENDING', $searchDateBegin, $searchDateEnd);;

        $account_success = $this->getTransactionCount(WalletDjamo::class, 'SUCCESS', $searchDateBegin, $searchDateEnd);

        $account_errors = $this->getTransactionCount(WalletDjamo::class, 'ERROR', $searchDateBegin, $searchDateEnd);

        $amount_total = $this->walletTransactionsDays(WalletDjamo::class, $searchDateBegin, $searchDateEnd);

        $pdf = Pdf::loadView('pages.historicals.transactionsDays.pdf_djamo_transactions', compact('amount_total', 'walletTransactions', 'account_penddings', 'account_success', 'account_errors', 'searchDateBegin', 'searchDateEnd'))->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
        return $pdf->download('reporting_djamo_days.pdf');
    }

    public function searchByVersmentDate(Request $request)
    {
        $this->validate($request, [
            'search_begin' => 'required|date',
            'search_end' => 'required|date|after_or_equal:search_begin',
        ]);


        $searchDateBegin = $request->input('search_begin');

        $searchDateEnd = $request->input('search_end');

        $walletTransactions = $this->getTransactionDate(WalletVersement::class, $searchDateBegin, $searchDateEnd);

        $account_penddings =  $this->getTransactionCount(WalletVersement::class, 'PENDING', $searchDateBegin, $searchDateEnd);;

        $account_success = $this->getTransactionCount(WalletVersement::class, 'SUCCESS', $searchDateBegin, $searchDateEnd);

        $account_errors = $this->getTransactionCount(WalletVersement::class, 'ERROR', $searchDateBegin, $searchDateEnd);

        $amount_total = $this->walletTransactionsDays(WalletVersement::class, $searchDateBegin, $searchDateEnd);

        $pdf = Pdf::loadView('pages.historicals.transactionsDays.pdf_versment_transactions', compact('amount_total', 'walletTransactions', 'account_penddings', 'account_success', 'account_errors', 'searchDateBegin', 'searchDateEnd'))->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
        return $pdf->download('reporting_versement_days.pdf');
    }

    public function searchByTresortDate(Request $request)
    {
        $this->validate($request, [
            'search_begin' => 'required|date',
            'search_end' => 'required|date|after_or_equal:search_begin',
        ]);


        $searchDateBegin = $request->input('search_begin');

        $searchDateEnd = $request->input('search_end');

        $walletTransactions = $this->getTransactionDate(WalletTresortMoney::class, $searchDateBegin, $searchDateEnd);

        $account_penddings =  $this->getTransactionCount(WalletTresortMoney::class, 'PENDING', $searchDateBegin, $searchDateEnd);;

        $account_success = $this->getTransactionCount(WalletTresortMoney::class, 'SUCCESS', $searchDateBegin, $searchDateEnd);

        $account_errors = $this->getTransactionCount(WalletTresortMoney::class, 'ERROR', $searchDateBegin, $searchDateEnd);

        $amount_total = $this->walletTransactionsDays(WalletTresortMoney::class, $searchDateBegin, $searchDateEnd);

        $pdf = Pdf::loadView('pages.historicals.transactionsDays.pdf_tresort_transactions', compact('amount_total', 'walletTransactions', 'account_penddings', 'account_success', 'account_errors', 'searchDateBegin', 'searchDateEnd'))->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
        return $pdf->download('reporting_tresort_days.pdf');
    }

    public function searchByPaymentsDate(Request $request)
    {

        $this->validate($request, [
            'search_begin' => 'required|date',
            'search_end' => 'required|date|after_or_equal:search_begin',
        ]);


        $searchDateBegin = $request->input('search_begin');

        $searchDateEnd = $request->input('search_end');

        $walletInters = $this->getTransactionDate(WalletPayment::class, $searchDateBegin, $searchDateEnd);

        $account_penddings =  $this->getTransactionCount(WalletPayment::class, 'PENDING', $searchDateBegin, $searchDateEnd);;

        $account_success = $this->getTransactionCount(WalletPayment::class, 'SUCCESS', $searchDateBegin, $searchDateEnd);

        $account_errors = $this->getTransactionCount(WalletPayment::class, 'ERROR', $searchDateBegin, $searchDateEnd);

        $amount_total = $this->walletTransactionsDays(WalletPayment::class, $searchDateBegin, $searchDateEnd);

        $pdf = Pdf::loadView('pages.historicals.transactionsDays.pdf_payment', compact('amount_total', 'walletInters', 'account_penddings', 'account_success', 'account_errors', 'searchDateBegin', 'searchDateEnd'))->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
        return $pdf->download('reporting_payments_days.pdf');
    }

    public function getTransactionDate($wallets, $searchDateBegin, $searchDateEnd)
    {
        return $wallets::whereBetween('created_at', [$searchDateBegin, $searchDateEnd])->get();
    }

    public function getTransactionCount($wallets, $status, $searchDateBegin, $searchDateEnd)
    {
        return $wallets::whereBetween('created_at', [$searchDateBegin, $searchDateEnd])->where('status', $status)->count();
    }

    public function walletTransactionSumDays($searchDateBegin, $searchDateEnd)
    {

        $data = [];

        foreach (WalletInter::whereBetween('created_at', [$searchDateBegin, $searchDateEnd])->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }


    public function walletTransactionIntersDays($wallets, $searchDateBegin, $searchDateEnd)
    {

        $data = [];

        foreach ($wallets::whereBetween('created_at', [$searchDateBegin, $searchDateEnd])->where('status', 'SUCCESS')->get() as $bank) {

            $amount_user =  $bank->amount;

            $country_user = $bank->wallet;

            $amount_banque = $this->getConvertAdminCommission($amount_user, $country_user);


            $data[] = $amount_banque;
        }

        return array_sum($data);
    }

    public function walletTransactionsDays($wallets, $searchDateBegin, $searchDateEnd)
    {

        $data = [];

        foreach ($wallets::whereBetween('created_at', [$searchDateBegin, $searchDateEnd])->where('status', 'SUCCESS')->get() as $bank) {

            $data[] = $bank->amount;
        }

        return array_sum($data);
    }


    public function walletCardDepositSumUserProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletDeposit::where('updated_by',  $id)
            ->where('status', 'SUCCESS');

        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('updated_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('updated_at', '<=', $endDate);
        }

        return $query->sum('amount');
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

            $query = WalletInter::where('updated_by', $userId);

            $queryReloads = WalletInter::where('number_compte', $wallet_id->wallet_id)->where('updated_by', $userId);

            if (request()->filled('startDate') && request()->filled('endDate')) {
                $query->whereBetween('updated_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $query->whereDate('updated_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $query->whereDate('updated_at', '<=', request('endDate'));
            }

            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryReloads->whereBetween('updated_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryReloads->whereDate('updated_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryReloads->whereDate('updated_at', '<=', request('endDate'));
            }


            $bank_historicals = $query->get();
            $bank_historicalReloads = $queryReloads->get();

            $account_penddings = (clone $query)->where('status', 'PENDING')->count();
            $account_success   = (clone $query)->where('status', 'SUCCESS')->count();
            $account_errors    = (clone $query)->where('status', 'ERROR')->count();

            $account_relods_penddings = (clone $queryReloads)->where('status', 'PENDING')->count();
            $account_relods_success   = (clone $queryReloads)->where('status', 'SUCCESS')->count();
            $account_relods_errors    = (clone $queryReloads)->where('status', 'ERROR')->count();

            $amount_total = $this->walletPaymentInterSumSendDaysProperty($userId, request('startDate'), request('endDate'));

            $amount_recived_total = $this->walletPaymentSumReceivedinterDaysProperty($wallet_id->wallet_id, request('startDate'), request('endDate'));


            $pdf = Pdf::loadView('pages.historicals.distributers.pdf_inter', compact('amount_total', 'amount_recived_total', 'bank_historicalReloads', 'wallet_id', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_inter.pdf');
        } else {

            $query = WalletInter::where('wallet_id', $id);

            $queryReloads = WalletInter::where('number_compte', $wallet_id->wallet_id);

            if (request()->filled('startDate') && request()->filled('endDate')) {
                $query->whereBetween('created_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $query->whereDate('created_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $query->whereDate('created_at', '<=', request('endDate'));
            }

            if (request()->filled('startDate') && request()->filled('endDate')) {
                $queryReloads->whereBetween('created_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $queryReloads->whereDate('created_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $queryReloads->whereDate('created_at', '<=', request('endDate'));
            }


            $bank_historicals = $query->get();
            $bank_historicalReloads = $queryReloads->get();

            $account_penddings = (clone $query)->where('status', 'PENDING')->count();
            $account_success   = (clone $query)->where('status', 'SUCCESS')->count();
            $account_errors    = (clone $query)->where('status', 'ERROR')->count();

            $account_relods_penddings = (clone $queryReloads)->where('status', 'PENDING')->count();
            $account_relods_success   = (clone $queryReloads)->where('status', 'SUCCESS')->count();
            $account_relods_errors    = (clone $queryReloads)->where('status', 'ERROR')->count();


            $amount_total = $this->walletPaymentInterSumSendProperty($id, request('startDate'), request('endDate'));

            $amount_recived_total = $this->walletPaymentSumReceivedinterProperty($wallet_id->wallet_id, request('startDate'), request('endDate'));


            $pdf = Pdf::loadView('pages.historicals.reports.pdf_inter', compact('amount_total', 'amount_recived_total', 'bank_historicalReloads', 'wallet_id', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_inter.pdf');
        }
    }

    public function walletPaymentInterSumSendProperty($id,  $startDate = null, $endDate = null)
    {

        $query = WalletInter::where('wallet_id',  $id)
            ->where('status', 'SUCCESS');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function walletPaymentInterSumSendDaysProperty($id, $startDate = null, $endDate = null)
    {
        $query = WalletInter::where('updated_by',  $id)
            ->where('status', 'SUCCESS');

        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('updated_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('updated_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function walletPaymentSumReceivedinterProperty($id,  $startDate = null, $endDate = null)
    {

        $query = WalletInter::where('number_compte',  $id)
            ->where('status', 'SUCCESS');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function walletPaymentSumReceivedinterDaysProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletTransaction::where('number_compte',  $id)
            ->where('status', 'SUCCESS');

        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('updated_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('updated_at', '<=', $endDate);
        }

        return $query->sum('amount');
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


            $query = WalletPayment::where('updated_by', $userId);

            if (request()->filled('startDate') && request()->filled('endDate')) {
                $query->whereBetween('updated_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $query->whereDate('updated_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $query->whereDate('updated_at', '<=', request('endDate'));
            }

            $bank_historicals = $query->get();

            $account_penddings = (clone $query)->where('status', 'PENDING')->count();
            $account_success   = (clone $query)->where('status', 'SUCCESS')->count();
            $account_errors    = (clone $query)->where('status', 'ERROR')->count();


            $amount_total = $this->walletPaymentSumSendTransactionDaysProperty($userId, request('startDate'), request('endDate'));

            $pdf = Pdf::loadView('pages.historicals.distributers.pdf_payment', compact('wallet_id', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_payment.pdf');
        } else {

            $query = WalletPayment::where('wallet_id', $id);

            if (request()->filled('startDate') && request()->filled('endDate')) {
                $query->whereBetween('created_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $query->whereDate('created_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $query->whereDate('created_at', '<=', request('endDate'));
            }

            $bank_historicals = $query->get();

            $account_penddings = (clone $query)->where('status', 'PENDING')->count();
            $account_success   = (clone $query)->where('status', 'SUCCESS')->count();
            $account_errors    = (clone $query)->where('status', 'ERROR')->count();


            $amount_total = $this->walletPaymentSumSendTransactionProperty($id, request('startDate'), request('endDate'));

            $pdf = Pdf::loadView('pages.historicals.reports.pdf_payment', compact('wallet_id', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_payment.pdf');
        }
    }

    // User 
    public function walletPaymentSumSendTransactionProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletPayment::where('wallet_id', $id)
            ->where('status', 'SUCCESS');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function walletPaymentSumSendTransactionDaysProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletPayment::where('updated_by', $id)
            ->where('status', 'SUCCESS');
        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('updated_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('updated_at', '<=', $endDate);
        }

        return $query->sum('amount');
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

            $query = WalletPhoneCredit::where('updated_by', $userId);

            if (request()->filled('startDate') && request()->filled('endDate')) {
                $query->whereBetween('updated_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $query->whereDate('updated_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $query->whereDate('updated_at', '<=', request('endDate'));
            }

            $bank_historicals = $query->get();

            $account_penddings = (clone $query)->where('status', 'PENDING')->count();
            $account_success   = (clone $query)->where('status', 'SUCCESS')->count();
            $account_errors    = (clone $query)->where('status', 'ERROR')->count();

            $amount_total = $this->walletPhoneSumSendTransactionDaysProperty($id, request('startDate'), request('endDate'));

            $pdf = Pdf::loadView('pages.historicals.distributers.pdf_phone', compact('wallet_id', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_phone.pdf');
        } else {

            $query = WalletPhoneCredit::where('wallet_id', $id);

            $bank_historicals = $query->get();

            $account_penddings = (clone $query)->where('status', 'PENDING')->count();
            $account_success   = (clone $query)->where('status', 'SUCCESS')->count();
            $account_errors    = (clone $query)->where('status', 'ERROR')->count();

            $amount_total = $this->walletPhoneSumSendTransactionProperty($id, request('startDate'), request('endDate'));

            $pdf = Pdf::loadView('pages.historicals.reports.pdf_phone', compact('wallet_id', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_phone.pdf');
        }
    }

    // User 
    public function walletPhoneSumSendTransactionProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletPhoneCredit::where('wallet_id',  $id)
            ->where('status', 'SUCCESS');
        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('updated_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('updated_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function walletPhoneSumSendTransactionDaysProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletPhoneCredit::where('updated_by',  $id)
            ->where('status', 'SUCCESS');

        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('updated_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('updated_at', '<=', $endDate);
        }

        return $query->sum('amount');
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

            $query = WalletTransaction::where('updated_by', $userId);

            if (request()->filled('startDate') && request()->filled('endDate')) {
                $query->whereBetween('updated_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $query->whereDate('updated_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $query->whereDate('updated_at', '<=', request('endDate'));
            }

            $bank_historicals = $query->get();
            $account_penddings = (clone $query)->where('status', 'PENDING')->count();
            $account_success   = (clone $query)->where('status', 'SUCCESS')->count();
            $account_errors    = (clone $query)->where('status', 'ERROR')->count();


            $amount_reload_total = $this->walletRelaodSumReceivedTransactionDaysProperty($userId, request('startDate'), request('endDate'));

            $amount_total = $this->walletRelaodSumSendTransactionDaysProperty($userId, request('startDate'), request('endDate'));

            $pdf = Pdf::loadView('pages.historicals.distributers.pdf_transfer', compact('wallet_id', 'amount_reload_total', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_transfers.pdf');
        } elseif (sabdy()->is_under_distributer == true) {

            $query = WalletTransaction::where('created_by', $userId);

            if (request()->filled('startDate') && request()->filled('endDate')) {
                $query->whereBetween('updated_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $query->whereDate('updated_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $query->whereDate('updated_at', '<=', request('endDate'));
            }

            $bank_historicals = $query->get();
            $account_penddings = (clone $query)->where('status', 'PENDING')->count();
            $account_success   = (clone $query)->where('status', 'SUCCESS')->count();
            $account_errors    = (clone $query)->where('status', 'ERROR')->count();

            $amount_reload_total = $this->walletRelaodSumReceivedTransactionDaysProperty($userId, request('startDate'), request('endDate'));

            $amount_total = $this->walletRelaodSumSendTransactionDaysProperty($userId, request('startDate'), request('endDate'));

            $pdf = Pdf::loadView('pages.historicals.distributers.pdf_transfer', compact('wallet_id', 'amount_reload_total', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_transfers.pdf');
        } else {

            $query = WalletTransaction::where('wallet_id', $id);

            if (request()->filled('startDate') && request()->filled('endDate')) {
                $query->whereBetween('created_at', [request('startDate'), request('endDate')]);
            } elseif (request()->filled('startDate')) {
                $query->whereDate('created_at', '>=', request('startDate'));
            } elseif (request()->filled('endDate')) {
                $query->whereDate('created_at', '<=', request('endDate'));
            }

            $bank_historicals = $query->get();
            $account_penddings = (clone $query)->where('status', 'PENDING')->count();
            $account_success   = (clone $query)->where('status', 'SUCCESS')->count();
            $account_errors    = (clone $query)->where('status', 'ERROR')->count();

            $amount_reload_total = $this->walletRelaodSumReceivedTransactionProperty($id, request('startDate'), request('endDate'));

            $amount_total = $this->walletRelaodSumSendTransactionProperty($id, request('startDate'), request('endDate'));

            $pdf = Pdf::loadView('pages.historicals.reports.pdf_transfer', compact('wallet_id', 'amount_reload_total', 'amount_total', 'bank_historicals', 'account_penddings', 'account_success', 'account_errors'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('reporting_transfers.pdf');
        }
    }



    // User 
    public function walletRelaodSumSendTransactionProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletTransaction::where('wallet_id',  $id)
            ->where('status', 'SUCCESS');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function walletRelaodSumSendTransactionDaysProperty($id, $startDate = null, $endDate = null)
    {


        $query = WalletTransaction::where('updated_by',  $id)
            ->where('status', 'SUCCESS');

        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('updated_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('updated_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }


    public function walletRelaodSumReceivedTransactionProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletTransaction::where('num_wallet',  $id)
            ->where('status', 'SUCCESS');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function walletRelaodSumSendsTransactionProperty($id, $startDate = null, $endDate = null)
    {

        $query = WalletTransaction::where('wallet_id',  $id)
            ->where('status', 'SUCCESS');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    public function walletRelaodSumReceivedTransactionDaysProperty($id,  $startDate = null, $endDate = null)
    {

        $wallet_id = Wallet::whereId($id)->first();
        $todays = Carbon::now();
        $formattedDate = today();
        $query = WalletTransaction::where('updated_by',  $id)
            ->where('status', 'SUCCESS');
        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('updated_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('updated_at', '<=', $endDate);
        }

        return $query->sum('amount');
    }
}
