<?php

namespace App\Http\Services;

use App\Models\AdminsCom;
use App\Models\Custumers\Country;
use App\Models\Invoices\InvoiceBank;
use App\Models\Invoices\InvoiceDeposit;
use App\Models\Invoices\InvoiceInter;
use App\Models\Invoices\InvoicePayment;
use App\Models\Invoices\InvoiceBankSend;
use App\Models\Invoices\InvoicePhoneCredit;
use App\Models\Invoices\InvoiceTransaction;
use App\Models\Invoices\OneBetInvoice;
use App\Models\Moneys\LevelMoney;
use App\Models\Offer;
use App\Models\OperatorService;
use App\Models\Payments\Deposit;
use App\Models\Payments\Payment;
use App\Models\Payments\PaymentBank;
use App\Models\Payments\PaymentPhoneCredit;
use App\Models\Payments\Transaction;
use App\Models\Payments\TransactionInter;
use App\Models\Payments\OneBetTransaction;
use App\Models\Providers\Provider;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletTransaction;
use App\Models\Wallets\WalletXbet;
use App\Models\Wallets\WalletDjamo;
use App\Models\Wallets\WalletBankSend;
use App\Models\Wallets\WalletPush;
use App\Models\Wallets\WalletVersement;
use App\Notifications\AdminNotification;
use App\Notifications\UserNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\CodeValidated;
use App\Models\Wallets\WalletTresortMoney;
use App\Models\Invoices\AutherTransactionInvoice;
use App\Models\Payments\AutherTransaction;
use App\Models\Invoices\InvoiceReload;
use App\Models\Payments\PaymentReload;

class TransfertServiceBackend
{
    /**
     * Distribuer la commission aux admins pour les compagnies acceptées
     */

    public function createRobusWalletTransaction($transaction_amount_robus, $percent_robus, $provider, $compte_number_robus, $amount_drawal, $choose_transaction , $amount_before_drawal)
    {

        $amount_solde = (float)sabdy()->wallet->amount - (float)$transaction_amount_robus;

        return WalletTransaction::create(
            [
                'wallet_id' => sabdy()->wallet->id,
                'amount' => $transaction_amount_robus,
                'description' => "Transfert d'argent vers Robus",
                'transaction_cost_id' =>  $percent_robus->id,
                'phone' =>  null,
                'costs_amount' => $percent_robus->percent * $transaction_amount_robus,
                'amount_received' => $transaction_amount_robus - ($percent_robus->percent * $transaction_amount_robus),
                'num_wallet' => strtoupper($compte_number_robus),
                'name_complet' => null,
                'num_piece' => null,
                'validated_date' => null,
                'created_by' => sabdy()->id,
                'balance_before' => sabdy()->wallet->amount,
                'provider_id' => $provider->id,
                'choose_type_transaction' => 1,
                'type_transaction' => 'Depot',
                'type_received' => 'Transfert',
                'status' => 'SUCCESS',
                'amount_solde' => $amount_solde ?? null,
                'amount_solde_drawal' => $amount_drawal ?? null,
                'amount_solde_before_drawal' => $amount_before_drawal ?? null,
                'choose_transaction' => $choose_transaction ?? 1
            ]
        );
    }


    public function createWalletTransactionCiv($percent_robus, $provider, $transaction_amount_money, $valuer_deposit_com, $transaction_phone, $choose_transaction, $name_custumers, $identity_number)
    {

        $amount_solde = (float)sabdy()->wallet->amount - (float)$transaction_amount_money;

        return  WalletTransaction::create(
            [
                'wallet_id' =>   sabdy()->wallet->id,
                'amount' => $transaction_amount_money,
                'description'  => $transaction_description ?? null,
                'transaction_cost_id' => $percent_robus->id,
                'phone'   => sabdy()->country->indecative . $transaction_phone,
                'created_by' => sabdy()->id,
                'provider_id'  =>   $provider->id,
                'num_wallet'   =>  null,
                'costs_amount' => $valuer_deposit_com,
                'balance_before' => sabdy()->wallet->amount,

                'name_complet' => $name_custumers ?? null,
                'num_piece' => $identity_number ?? null,
                'choose_transaction' => $choose_transaction ?? 1,
                'choose_type_transaction' => 1,
                'amount_received'  => 0,
                'type_transaction' => 'Retrait',
                'status'  => "PENDING",
                'amount_solde' => $amount_solde ?? null
            ]
        );
    }


    public function createWalletTransactionReloadReload($walletUser, $relaod_uv_amout_robus, $provider, $account_reload_uv, $valeur_receive, $amount_solde, $amount_drawal , $amount_before_drawal)
    {

        return  WalletTransaction::create(
            [
                'wallet_id' =>   $walletUser->wallet->id,
                'amount' => $relaod_uv_amout_robus,
                'description'  => "remboursement",
                'created_by' => Auth::guard('web')->user()->id,
                'provider_id'  =>  $provider->id,
                'num_wallet'  => $account_reload_uv,
                'costs_amount' => 0,
                'choose_type_transaction' => 1,
                'amount_received'  => $valeur_receive,
                'balance_before' => sabdy()->wallet->amount,
                'type_transaction' => 'Réequilibrage',
                'type_received' => 'Transfert',
                'status'  => "SUCCESS",
                'amount_solde'  => $amount_solde ?? null,
                'amount_solde_drawal' => $amount_drawal ?? null,
                'amount_solde_before_drawal' => $amount_before_drawal ?? null,
            ]
        );
    }

     public function createWalletTransactionCommissionReload($walletUser, $relaod_uv_amout_robus, $provider, $account_reload_uv, $valeur_receive, $amount_solde , $balance_before, $amount_drawal , $amount_before_drawal)
    {

        return  WalletTransaction::create(
            [
                'wallet_id' =>   $walletUser->wallet->id,
                'amount' => $relaod_uv_amout_robus,
                'description'  => "Transfert du montant commission admin",
                'created_by' => Auth::guard('web')->user()->id,
                'provider_id'  =>  $provider->id,
                'num_wallet'  => $account_reload_uv,
                'costs_amount' => 0,
                'choose_type_transaction' => 1,
                'amount_received'  => $valeur_receive,
                'balance_before' => $balance_before,
                'type_transaction' => 'Réequilibrage',
                'type_received' => 'Transfert',
                'status'  => "SUCCESS",
                'amount_solde'  => $amount_solde ?? null,
                'amount_solde_drawal' => $amount_drawal ?? null,
                'amount_solde_before_drawal' => $amount_before_drawal ?? null,
            ]
        );
    }

    public function createWalletTReloadInvoiceReload($walletTransaction, $transactionCode, $transactionCompiCode)
    {

        return  Transaction::create([
            'wallet_transaction_id' => $walletTransaction->id,
            'sid_user_id' => sabdy()->id,
            'transaction_id' => 'RB_' . $walletTransaction->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $walletTransaction->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => $walletTransaction->amount,
            'fee_amount' =>  $walletTransaction->costs_amount,
            'received_amount' => $walletTransaction->amount,
            'status' => $walletTransaction->status,
        ]);
    }

    public function createWalletTranloadInvoiceReload($walletTransaction, $transaction)
    {

        return InvoiceTransaction::create([
            'title' => "Transfers d'argent",
            'wallet_transaction_id' => $walletTransaction->id,
            'transactions_id' => $transaction->id,
            'company_id' => sabdy()->company->id,
            'description' => $walletTransaction->description,
            'status' => $walletTransaction->status,
            'created_by' => sabdy()->id,
        ]);
    }

    public function createWalletReload($walletUser, $relaod_uv_amout_robus, $provider, $account_reload_uv, $valeur_receive, $number_reload_uv, $amount_solde, $amount_drawal , $amount_before_drawal)
    {

        return  WalletReload::create([
            'wallet_id' =>   $walletUser->wallet->id,
            'amount' => $relaod_uv_amout_robus,
            'description'  => 'Rechargement de compte UV',
            'reload_cost_id' => 1,
            'created_by' => Auth::guard('web')->user()->id,
            'provider_id'  =>  $provider->id,
            'account_wallet'  => $account_reload_uv,
            'amount_received'  => $valeur_receive,
            'type_transaction' => 'Réequilibrage',
            'type_received' => 'Transfert',
            'phone'  => $number_reload_uv ?? null,
            'status'  => "SUCCESS",
            'amount_solde'  => $amount_solde ?? null,
            'balance_before' => sabdy()->wallet->amount ?? null ,
            'amount_solde_drawal' => $amount_drawal ?? null ,
            'amount_solde_before_drawal'=> $amount_before_drawal ?? null ,
        ]);
    }

    public function createWalletPaymentReload($reload_unders, $transactionCode, $transactionCompiCode)
    {

        return  PaymentReload::create([
            'wallet_reload_id' => $reload_unders->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'RB_' . $reload_unders->provider->name . '_' . $transactionCode,
            'operator_transaction_name' =>  $reload_unders->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' =>  $reload_unders->amount,
            'fee_amount' =>  $reload_unders->amount,
            'received_amount' => ($reload_unders->amount),
            'status'  =>    $reload_unders->status,
        ]);
    }

    public function createWalletPaymentInvoiceReload($reload_unders, $paymentRealod)
    {

        return   InvoiceReload::create([
            'title' => "Rechargement de compte",
            'wallet_reload_id' => $reload_unders->id,
            'payment_reload_id' =>  $paymentRealod->id,
            'company_id' => Auth::guard('web')->user()->company->id,
            'description' => $reload_unders->description,
            'status'  =>    $reload_unders->status,
            'created_by'  =>   Auth::guard('web')->user()->id,
        ]);
    }

    public function createRobusWalletInter($val_amount, $percent_robus, $country_robus, $provider_payment, $number_compte, $provider_type, $amount_drawal)
    {
        $amount_solde = sabdy()->wallet->amount - $val_amount;

        return WalletInter::create([
            'wallet_id' => sabdy()->wallet->id,
            'amount' => $val_amount,
            'description' => "Transfert par Robus",
            'inter_cost_id' => null,
            'transaction_cost_id' => $percent_robus->id,
            'phone' =>  null,
            'number_compte' => strtoupper($number_compte),
            'created_by' => sabdy()->id,
            'provider_id' => $provider_payment,
            'costs_amount' => sabdy()->is_under_distributer ? ($percent_robus->percent_under_distributer * $val_amount) : ($percent_robus->percent * $val_amount),
            'provider_type' => 1,
            'type_transaction' => 'Depot',
            'balance_before' => sabdy()->wallet->amount,
            'type_received' => 'Transfert',
            'status' => 'SUCCESS',
            'country_id' => $country_robus->id,
            'choose_type_transaction' => $provider_type,
            'amount_solde' => $amount_solde ?? null,
            'amount_solde_drawal' => $amount_drawal ?? null
        ]);
    }


    public function createXbetTransaction($val_amount, $provider, $percent_robus, $amount_solde, $choose_type_transaction, $fullName, $numberAcompte, $codeAcompte, $amountReceved)
    {

        return WalletXbet::create([
            'wallet_id' =>   sabdy()->wallet->id,
            'amount' => $val_amount,
            'description'  => 'Demande  de depot 1Xbet',
            'Onexbet_id' => $choose_type_transaction === 1 ? 1  : 2,
            'created_by' => sabdy()->id,
            'amount_solde'  => $amount_solde  ?? null,
            'provider_id'  =>  $provider->id,
            'costs_amount' => ($percent_robus->percent * $val_amount),
            'choose_type_transaction' => $choose_type_transaction,
            'num_acompte'  =>  $numberAcompte ?? null,
            'balance_before' => sabdy()->wallet->amount,
            'full_name' =>  $fullName ?? null,
            'countrie' => "Bar Namendé 2" ?? null,
            'citie' =>  "KAYA(Centre-Nord)" ?? null,
            'code_acompte' =>  $codeAcompte ?? null,
            'countrie_id' => sabdy()->country->id,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
        ]);
    }

    public function createPushTransaction($val_amount, $provider, $percent_robus, $amount_solde, $fullName, $phoneNumber, $amountReceved, $identity_number, $choose_transaction)
    {

        return WalletPush::create([
            'wallet_id' =>   sabdy()->wallet->id,
            'amount' => $val_amount,
            'description'  => 'Demande  de rechargement Push',
            'push_percent_id' => 1,
            'created_by' => sabdy()->id,
            'amount_solde'  => $amount_solde  ?? null,
            'provider_id'  =>  $provider->id,
            'costs_amount' => ($percent_robus->percent * $val_amount),
            'phone'  =>  $phoneNumber ?? null,
            'balance_before' => sabdy()->wallet->amount,
            'full_name' =>  $fullName ?? null,
            'identity_number' =>  $identity_number ?? null,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
            'choose_transaction' => $choose_transaction == 1 ? "Transfert" : "Retrait"

        ]);
    }

    public function createTresortTransaction($val_amount, $provider, $percent_robus, $amount_solde, $fullName, $phoneNumber, $amountReceved, $choose_transaction, $identity_number)
    {

        return WalletTresortMoney::create([
            'wallet_id' =>   sabdy()->wallet->id,
            'amount' => $val_amount,
            'description'  => 'Demande de rechargement Tresort Money',
            'argent_tresort_id' => 2,
            'created_by' => sabdy()->id,
            'amount_solde'  => $amount_solde  ?? null,
            'provider_id'  =>  $provider->id,
            'costs_amount' => ($percent_robus->percent * $val_amount),
            'phone'  =>  $phoneNumber ?? null,
            'full_name' =>  $fullName ?? null,
            'balance_before' => sabdy()->wallet->amount,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
            'identity_number' =>  $identity_number ?? null,
            'choose_transaction' => $choose_transaction == 1 ? "Transfert" : "Retrait"

        ]);
    }

    public function createVersementTransaction($val_amount, $provider, $percent_robus, $amount_solde, $numberAccount, $bankCode, $guichetCode, $numberRib, $amountReceved)
    {

        return WalletVersement::create([
            'wallet_id' =>   sabdy()->wallet->id,
            'amount' => $val_amount,
            'description'  => 'Demande de versement Bancaire',
            'created_by' => sabdy()->id,
            'amount_solde'  => $amount_solde  ?? null,
            'provider_id'  =>  $provider->id,
            'costs_amount' => ($percent_robus->percent * $val_amount),
            'virement_percent_id' => $percent_robus->id,
            'num_accompte'   => $numberAccount ?? null,
            'balance_before' => sabdy()->wallet->amount,
            'bank_code' => $bankCode ?? null,
            'guichet_code' => $guichetCode ?? null,
            'num_authers' => $numberRib ?? null,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
        ]);
    }


    public function createDjamoTransaction($val_amount, $provider, $percent_robus, $amount_solde, $fullName, $image, $amountReceved, $choose_transaction, $identity_number, $phone)
    {

        return WalletDjamo::create([
            'wallet_id' =>   sabdy()->wallet->id,
            'amount' => $val_amount,
            'description'  => 'Demande de rechargement Djamo',
            'djamo_percent_id' => 2,
            'created_by' => sabdy()->id,
            'amount_solde'  => $amount_solde  ?? null,
            'provider_id'  =>  $provider->id,
            'costs_amount' => ($percent_robus->percent * $val_amount),
            'image'  =>  $image ?? null,
            'full_name' =>  $fullName ?? null,
            'phone' =>  $phone ?? null,
            'balance_before' => sabdy()->wallet->amount,
            'status'  => "PENDING",
            'identity_number' =>  $identity_number ?? null,
            'amount_received'  =>  $amountReceved ?? 0.0,
            'choose_transaction' => $choose_transaction == 1 ? "Transfert" :  "Retrait"

        ]);
    }



    public function updateAmountReceived($walletInter, $country_robus, $valeur_received)
    {


        if (sabdy()->country->currencie->value > $country_robus->currencie->value) {

            //   Convertion  in CFA
            $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_received *   (float) sabdy()->country->currencie->value;
            //   Convertion in Country send
            $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $country_robus->currencie->value;
        } elseif (sabdy()->country->currencie->value == $country_robus->currencie->value) {

            $commission_transaction_val_inter_convertion_send = (float)   $valeur_received;
        } elseif (sabdy()->country->currencie->value < $country_robus->currencie->value) {

            //   Convertion  in CFA
            $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_received *   (float) sabdy()->country->currencie->value;

            //   Convertion in Country send
            $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $country_robus->currencie->value;
        }

        if ($commission_transaction_val_inter_convertion_send) {

            $walletInter->update(['amount_received' => $commission_transaction_val_inter_convertion_send]);

            return true;
        } else {
            return false;
        }
    }


     public function createWithVersementWalletInter(
        $amount_transaction,
        $percent_robus,
        $country_robus,
        $provider_payment,
        $provider_type,
        $name_custumers,
        $guichet_code,
        $bank_code,
        $account_number,
        $name,

    ) {

        $amount_solde = sabdy()->wallet->amount - $amount_transaction;

        return WalletInter::create([
            'wallet_id' => sabdy()->wallet->id,
            'amount' => $amount_transaction,
            'description' => null,
            'balance_before' =>  sabdy()->wallet->amount,
            'inter_cost_id' => null,
            'virement_percent_id' => $percent_robus->id ?? null,
            'number_compte' => $account_number,
            'created_by' => sabdy()->id,
            'provider_id' => $provider_payment,
            'name' => $name,
            'num_authers' => $name_custumers ?? null,
            'bank_code' => $bank_code ?? null,
            'guichet_code' => $guichet_code ?? null,
            'costs_amount' => (sabdy()?->is_under_distributer || sabdy()?->is_api) ?  ($percent_robus->percent_merchands * $amount_transaction) : ($percent_robus->percent * $amount_transaction),
            'provider_type' => 9,
            'status' => 'PENDING',
            'country_id' => $country_robus->id,
            'choose_type_transaction' => $provider_type,
            'amount_solde' => $amount_solde ?? null,
        ]);
    }


    public function createRobusWalletTransactionOrderCard(
        $val_amount,
        $account_number,
        $country_robus,
        $provider,
        $amount_user,
        $name,
        $amount_receveid
    ) {
        $amount_solde = sabdy()->wallet->amount - $val_amount;

        return WalletInter::create(
            [

                'amount_solde' => $amount_solde ?? null,
                'wallet_id' => sabdy()->wallet->id,
                'amount' => $val_amount,
                'balance_before' =>  sabdy()->wallet->amount,
                'inter_cost_id' => null,
                'deposit_cost_id' => 1,
                'number_compte' =>  null,
                'balance_before' =>  sabdy()->wallet->amount,
                'created_by' => sabdy()->id,
                'provider_id' =>  $provider,
                'number_card' =>  $account_number,
                'country_id' => $country_robus->id,
                'name' => $name,
                'provider_type' => 8,
                'costs_amount' => $amount_user,
                'status' => 'PENDING',
                'amount_received' =>  $amount_receveid ?? null,

            ]
        );
    }

    public function createRobusWalletTransactionVirement(
        $val_amount,
        $account_number,
        $country_robus,
        $provider,
        $amount_user,
        $phone,
        $firstname,
        $lastname,
        $amount_receveid
    ) {
        $amount_solde = sabdy()->wallet->amount - $val_amount;

        return WalletInter::create([
            'wallet_id' => sabdy()->wallet->id,
            'amount_solde' => $amount_solde ?? null,
            'amount' => $val_amount,
            'inter_cost_id' => null,
            'bank_cost_id' => 1,
            'balance_before' =>  sabdy()->wallet->amount,
            'phone' => $phone,
            'number_compte' => $account_number,
            'created_by' => sabdy()->id,
            'provider_id' => $provider,
            'status' => 'PENDING',
            'country_id' => $country_robus->id,
            'provider_type' => 7,
            'firstname' =>  $firstname ??  null,
            'lastname' => $lastname ?? null,
            'costs_amount' =>  $amount_user,
            'amount_received' =>  $amount_receveid ?? null,
        ]);
    }


    public function createRobusWalletTransactionCreditPayment(
        $val_amount,
        $percent_robus,
        $country_robus,
        $provider,
        $name_custumer,
        $number_phone,
        $amount_conversion,
        $amount_receveid
    ) {
        $amount_solde = sabdy()->wallet->amount - $val_amount;

        return WalletInter::create([
            'amount_solde' => $amount_solde ?? null,
            'wallet_id' =>   sabdy()->wallet->id,
            'amount' => $val_amount,
            'description'  => "Payment de Credit Téléphonique",
            'balance_before' =>  sabdy()->wallet->amount,
            'provider_type' =>  6,
            'payment_cost_id' => $percent_robus->id,
            'phone' =>   $country_robus->indecative . $number_phone ?? null,
            'name' => $name ?? null,
            'created_by' => sabdy()->id,
            'provider_id'  =>   $provider,
            'name'  =>   $name_custumer ?? null,
            'status'  => "PENDING",
            'costs_amount' =>  $amount_conversion,
            'country_id'  =>   $country_robus->id,
            'amount_received' =>  $amount_receveid ?? null,
        ]);
    }



    public function createMobileWalletInter(
        $amount_transaction,
        $percent_robus,
        $country_robus,
        $provider_payment,
        $phone,
        $name_custumers,
        $num_piece_custumers,
        $date_piece,
        $name,
        $name_sender,
        $account_number
    ) {

        $amount_solde = sabdy()->wallet->amount - $amount_transaction;

        return WalletInter::create([
            'wallet_id' => sabdy()->wallet->id,
            'amount' => $amount_transaction,
            'description' => null,
            'inter_cost_id' => null,
            'transaction_cost_id' => $percent_robus->id,
            'phone' => $country_robus->indecative . $phone ?? null,
            'number_compte' => $account_number,
            'balance_before' => sabdy()->wallet->amount,
            'created_by' => sabdy()->id,
            'provider_id' => $provider_payment,
            'name_complet' => $name_custumers ?? null,
            'name' => $name ?? null,
            'num_piece' => $num_piece_custumers ?? null,
            'name_benef' => $name_sender ?? null,
            'validated_date' => $date_piece ?? null,
            'costs_amount' => sabdy()->is_under_distributer ? ($percent_robus->percent_under_distributer * $amount_transaction) : ($percent_robus->percent * $amount_transaction),
            'provider_type' => 1,
            'status' => 'PENDING',
            'country_id' => $country_robus->id,
            'choose_type_transaction' => 1,
            'amount_solde' => $amount_solde ?? null
        ]);
    }

    public function createMobileWalletInterWith(
        $amount_transaction,
        $percent_robus,
        $country_robus,
        $provider_payment,
        $phone,
        $name_custumers,
        $num_piece_custumers,
        $date_piece,
        $name,
        $name_sender,
        $account_number,
        $action_percent_id,
        $imagePath,
    ) {

        $amount_solde = sabdy()->wallet->amount - $amount_transaction;

        return WalletInter::create([
            'wallet_id' => sabdy()->wallet->id,
            'amount' => $amount_transaction,
            'description' => null,
            'inter_cost_id' => null,
            'transaction_cost_id' => $percent_robus->id,
            'djamo_percent_id' => $provider_payment == 104 ? $action_percent_id : null,
            'push_percent_id'       => $provider_payment == 105 ? $action_percent_id : null,
            'argent_tresort_id'       => $provider_payment == 106 ? $action_percent_id : null,
            'phone' => $country_robus->indecative . $phone ?? null,
            'number_compte' => $account_number,
            'created_by' => sabdy()->id,
            'provider_id' => $provider_payment,
            'name_complet' => $name_custumers ?? null,
            'name' => $name ?? null,
            'num_piece' => $num_piece_custumers ?? null,
            'balance_before' => sabdy()->wallet->amount,
            'name_benef' => $name_sender ?? null,
            'validated_date' => $date_piece ?? null,
            'costs_amount' => sabdy()->is_under_distributer ? ($percent_robus->percent_merchands * $amount_transaction) : ($percent_robus->percent * $amount_transaction),
            'provider_type' => 1,
            'status' => 'PENDING',
            'country_id' => $country_robus->id,
            'choose_type_transaction' => 1,
            'amount_solde' => $amount_solde ?? null,
            'image' => $imagePath ?? null,
        ]);
    }


    public function createRobusWalletTransactionPayment(
        $val_amount,
        $percent_robus,
        $country_robus,
        $provider,
        $id_invoice,
        $number_decoder,
        $number_phone,
        $amount_conversion,
        $number_contract,
        $paiementChoix_phone,
        $facture_choix_type,
        $offers,
        $amount_receveid,
        $name
    ) {
        $amount_solde = sabdy()->wallet->amount - $val_amount;

        return WalletInter::create([
            'amount_solde' => $amount_solde ?? null,
            'wallet_id' =>   sabdy()->wallet->id,
            'balance_before' =>  sabdy()->wallet->amount,
            'amount' => $val_amount,
            'description'  => "Paiement facture",
            'inter_cost_id' => null,
            'provider_type' =>  5,
            'payment_cost_id' => $percent_robus->id,
            'number_decoder'   => $number_decoder ?? null,
            'id_invoice' => $id_invoice ?? null,
            'number_contract' => $number_contract ?? null,
            'name' => $name ?? null,
            'created_by' => sabdy()->id,
            'provider_id'  =>   $provider,
            'number_phone' => $number_phone ?? null,
            'offer_id'  =>  $offers->id ?? null,
            'status'  => "PENDING",
            'costs_amount' =>  $amount_conversion,
            'country_id'  =>   $country_robus->id,
            'paiementChoix_phone'  => $paiementChoix_phone ?? null,
            'factureChoix_type' =>  $facture_choix_type ?? null,
            'amount_received' =>  $amount_receveid ?? null,
        ]);
    }

    public function createMobileWalletBankSendWith(
        $amount_transaction,
        $percent_robus,
        $country_robus,
        $provider_payment,
        $phone,
        $name_custumers,
        $num_piece_custumers,
        $date_piece,
        $name,
        $name_sender,
        $account_number,
        $phone_inter_expe,
        $swit_code,
        $imagePath,
        $imageFrontendPath,
        $imageBackendPath,
        $type_piece,
    ) {

        $amount_solde = sabdy()->wallet->amount - $amount_transaction;


        return WalletBankSend::create([
            'wallet_id' => sabdy()->wallet->id,
            'amount' => $amount_transaction,
            'description' => "Demande de transaction Bank send",
            'bank_cost_id' => $percent_robus->id,
            'phone' => $country_robus->indecative . $phone ?? null,
            'number_compte' => $account_number,
            'created_by' => sabdy()->id,
            'provider_id' => $provider_payment,
            'name_complet' => $name_custumers ?? null,
            'name' => $name ?? null,
            'num_piece' => $num_piece_custumers ?? null,
            'name_benef' => $name_sender ?? null,
            'phone_inter_expe' => $phone_inter_expe ?? null,
            'swit_code' => $swit_code ?? null,
            'balance_before' => sabdy()->wallet->amount,
            'validated_date' => $date_piece ?? null,
            'costs_amount' => sabdy()->is_under_distributer ? ($percent_robus->amount) : ($percent_robus->amount),
            'provider_type' => 1,
            'status' => 'PENDING',
            'countrie_id' => $country_robus->id,
            'choose_type_transaction' => 1,
            'amount_solde' => $amount_solde ?? null,
            'image' => $imagePath ?? $imageFrontendPath,
            'image_bakend' => $imageBackendPath ?? $imageFrontendPath,
            'type_piece' => $type_piece ?? null,
        ]);
    }
    public function updateAmountRobusReceived($walletInter, $country_robus, $valeur_received, $wallet)
    {

        $wallet_search = Wallet::where('wallet_id', $wallet)
            ->where('country_id', $country_robus->id)
            //   ->where('status', true)
            ->first();

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));


        if (sabdy()->country->currencie->value != $country_robus->currencie->value) {

            $commission_transaction_val_inter_convertion_cfa = (float) $valeur_received * (float) sabdy()->country->currencie->value;

            $commission_transaction_val_inter_convertion_send = (float) $commission_transaction_val_inter_convertion_cfa / (float) $country_robus->currencie->value;
        } else {

            $commission_transaction_val_inter_convertion_send = (float) $valeur_received;
        }

        $walletAmountSend = Wallet::where('wallet_id',  $wallet)->first();

        $amount_solde_drawal = (float)$walletAmountSend->amount + $commission_transaction_val_inter_convertion_send;

        if ($commission_transaction_val_inter_convertion_send) {

            $walletInter->update([
                'amount_received' => $commission_transaction_val_inter_convertion_send,
                'amount_solde_drawal' => $amount_solde_drawal
            ]);

            $wallet_search->update([
                'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                'sid_user_relod' => sabdy()->id,
                'amount' => DB::raw('amount+' . $commission_transaction_val_inter_convertion_send),
            ]);


            // Valuer in Ivoice transaction
            $transaction = TransactionInter::create([
                'wallet_inter_id' => $walletInter->id,
                'sid_user_id' => sabdy()->id,
                'transaction_id' => 'RB_' . $walletInter->provider->name . '_' . $transactionCode,
                'operator_transaction_name' => $walletInter->provider->name,
                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                'pay_amount' => $walletInter->amount,
                'fee_amount' =>  $walletInter->costs_amount,
                'received_amount' => $commission_transaction_val_inter_convertion_send,
                'status' => $walletInter->status,
            ]);

            InvoiceInter::create([
                'title' => "Transfers d'argent",
                'transaction_inter_id' => $transaction->id,
                'description' => $walletInter->description,
                'is_send' => true,
                'status' => $walletInter->status,
                'created_by' => sabdy()->id,
            ]);

            return true;
        } else {
            return false;
        }
    }

    public function createDeposit($check, $transactionCode, $transactionCompiCode)
    {
        return  Deposit::create([

            'wallet_deposit_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'ROBUS_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'ROBUS_' . $transactionCompiCode,
            'pay_amount' => $check->amount,
            'fee_amount' =>  $check->costs_amount,
            'received_amount' => $check->amount,
            'status' => $check->status,
        ]);
    }


    public function createInvoiceReloadCard($check, $depositWallet)
    {
        return   InvoiceDeposit::create([
            'title' => 'Rechargement de carte',
            'wallet_deposit_id' => $check->id,
            'deposit_id' => $depositWallet->id,
            'company_id' => Auth::guard('web')->user()->company->id,
            'description' => $check->description,
            'status' => $check->status,
            'created_by' => Auth::guard('web')->user()->id,
        ]);
    }

    public function createTransaction($check, $transactionCode, $transactionCompiCode)
    {
        return Transaction::create([
            'wallet_transaction_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'RB_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => $check->amount,
            'fee_amount' =>  $check->costs_amount,
            'received_amount' => $check->amount,
            'status' => $check->status,
        ]);
    }

    public function createInvoiceTransaction($check, $transaction)
    {
        return  InvoiceTransaction::create([
            'title' => "Transfers d'argent",
            'wallet_transaction_id' => $check->id,
            'transactions_id' => $transaction->id,
            'company_id' => Auth::guard('web')->user()->company->id,
            'description' => $check->description,
            'status' => $check->status,
            'created_by' => Auth::guard('web')->user()->id,
        ]);
    }


    public function createVirement($check, $transactionCode, $transactionCompiCode)
    {
        return PaymentBank::create([
            'wallet_bank_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'RB_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => $check->amount,
            'fee_amount' => $check->costs_amount,
            'received_amount' =>  $check->amount,
            'status' => $check->status,
        ]);
    }

    public function createInvoiceVirement($check, $paymentBank)
    {
        return   InvoiceBank::create([
            'title' => 'Virement bancaire',
            'wallet_bank_id' => $check->id,
            'payment_bank_id' => $paymentBank->id,
            'company_id' => Auth::guard('web')->user()->company->id,
            'status' => $check->status,
            'created_by' => Auth::guard('web')->user()->id,
        ]);
    }

    public function createPayment($check, $transactionCode, $transactionCompiCode)
    {
        return Payment::create([
            'wallet_payment_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'RB_' . $check->provider->name . '_' . $transactionCode ??  null,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => $check->amount ?? null,
            'fee_amount' =>  $check->costs_amount ?? null,
            'received_amount' => $check->amount,
            'status' => $check->status,
        ]);
    }


    public function createInvoicePayment($check, $payment)
    {
        return  InvoicePayment::create([
            'title' => 'Paiement de facture ',
            'wallet_payment_id' => $check->id,
            'payment_id' => $payment->id,
            'company_id' => Auth::guard('web')->user()->company->id,
            'description' => $check->description,
            'status' => $check->status,
            'created_by' => Auth::guard('web')->user()->id,
        ]);
    }

    public function createErrorTransaction($check, $transactionCode, $transactionCompiCode)
    {
        return  Transaction::create([
            'wallet_transaction_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'RB_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => 0.0,
            'fee_amount' => 0.0,
            'received_amount' => $check->amount + $check->costs_amount,
            'status' => $check->status,
        ]);
    }

    public function createErrorPhoneCredit($check, $transactionCode, $transactionCompiCode)
    {
        return  PaymentPhoneCredit::create([
            'wallet_phone_credit_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'ROBUS_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'ROBUS_' . $transactionCompiCode,
            'pay_amount' => 0.0,
            'fee_amount' => 0.0,
            'received_amount' => $check->amount + $check->costs_amount,
            'status' => $check->status,
        ]);
    }

    public function createErrorBankSend($check, $transactionCode, $transactionCompiCode)
    {
        return  InvoiceBankSend::create([
            'wallet_bank_send_id' => $check->id,
            'created_by' => Auth::guard('web')->user()->id,
            'transaction_id' => 'ROBUS_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'pay_amount' => 0.0,
            'fee_amount' => 0.0,
            'received_amount' => $check->amount + $check->costs_amount,
            'status' => $check->status,
        ]);
    }


    public function createErrorDeposit($check, $transactionCode, $transactionCompiCode)
    {
        return  Deposit::create([
            'wallet_deposit_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'ROBUS_' . $check->provider->name .
                '_' .
                $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'ROBUS_' . $transactionCompiCode,
            'pay_amount' => 0.0,
            'fee_amount' => 0.0,
            'received_amount' => $check->amount + $check->costs_amount,
            'status' => $check->status,
        ]);
    }

    public function createErrorPayment($check, $transactionCode, $transactionCompiCode)
    {
        return Payment::create([
            'wallet_payment_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' =>
            'RB_' .
                $check->provider->name .
                '_' .
                $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => 0.0,
            'fee_amount' => 0.0,
            'received_amount' => $check->amount + $check->costs_amount,
            'status' => $check->status,
        ]);
    }


    public function createErrorInter($check, $transactionCode, $transactionCompiCode)
    {
        return    TransactionInter::create([
            'wallet_inter_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'RB_' . $check->provider->name .
                '_' . $transactionCode,
            'operator_transaction_name' =>
            $check->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => $check->amount,
            'fee_amount' => $check->costs_amount,
            'received_amount' => $check->amount,
            'status' => $check->status,
        ]);
    }

    public function createErrorVirement($check, $transactionCode, $transactionCompiCode, $commission)
    {
        return    PaymentBank::create([
            'wallet_bank_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'RB_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => 0.0,
            'fee_amount' => 0.0,
            'received_amount' => $commission,
            'status' => $check->status,
        ]);
    }

    public function createInter($check, $transactionCode, $transactionCompiCode)
    {
        return TransactionInter::create([
            'wallet_inter_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'RB_' . $check->provider->name .
                '_' . $transactionCode,
            'operator_transaction_name' =>
            $check->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => $check->amount,
            'fee_amount' => $check->costs_amount,
            'received_amount' => $check->amount,
            'status' => $check->status,
        ]);
    }


    public function createInvoiceInter($check, $transaction_inter)
    {
        return  InvoiceInter::create([
            'title' => "Transfers d'argent",
            // 'transaction_inter_id' => $check->id,
            'transaction_inter_id' => $transaction_inter->id,
            // 'company_id' => Auth::guard('web')->user()->company->id,
            'description' => $check->description,
            'status' => $check->status,
            'created_by' => Auth::guard('web')->user()->id,
        ]);
    }


    public function updatePaiementWallet($walletUser, $transaction_amount_robus)
    {

        return $walletUser->wallet->update([
            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
            'sid_user_relod' => Auth::guard('web')->user()->id,
            'amount' => DB::raw('amount-' . $transaction_amount_robus),
        ]);
    }

    public function updateVerifyCodeWallet($verify_code, $amount_solde, $amount_solde_drawal)
    {

        return  $verify_code->update([
            'type_transaction' => 'Retrait',
            'type_received' => 'Transfert',
            'status' => 'SUCCESS',
            'code_validated' => null,
            'amount_received' => null,
            'amount_solde'  => $amount_solde ?? null,
            'amount_solde_drawal' => $amount_solde_drawal ?? null,
            'created_by' => Auth::guard('web')->user()->id,
        ]);
    }

    public function createDrawalTransaction(
        $walletUser,
        $transaction_amount_robus,
        $transaction_description_robus,
        $transaction_phone,
        $compte_number_robus,
        $provider,
        $commission_send,
        $choose_type_transaction,
        $amount_solde,
        $amount_drawal,
        $amount_before_drawal
    ) {

        $wallet = sabdy()->wallet->first();

        return WalletTransaction::create(
            [
                'wallet_id' => $walletUser->wallet->id,
                'amount' => $transaction_amount_robus,
                'description' => $transaction_description_robus ?? null,
                'transaction_cost_id' => 26,
                'phone' => $transaction_phone ??  null,
                'num_wallet' =>  $compte_number_robus,
                'created_by' => Auth::guard('web')->user()->id,
                'provider_id' => $provider->id,
                'code_validated' => null,
                'balance_before' => $wallet->amount,
                'amount_received'  => $commission_send,
                'choose_type_transaction' => $choose_type_transaction,
                'type_transaction' => 'Compense',
                'type_received' => 'Transfert',
                'status' => 'SUCCESS',
                'amount_solde' => $amount_solde ?? null,
                'amount_solde_drawal' => $amount_drawal ?? null,
                'amount_solde_before_drawal' => $amount_before_drawal ?? null,
            ]
        );
    }

    public function createNewVirement(
        $walletUser,
        $amount_virement,
        $virementDescription,
        $provider,
        $account_number_user_bank,
        $amount_user,
        $firstname_user,
        $lastname_user,
        $phone_user,
        $amount_solde,
        $country_virement,
        $amountReceved
    ) {
        return WalletBank::create([
            'wallet_id' => $walletUser->wallet->id,
            'amount' => $amount_virement,
            'description' => $virementDescription,
            'bank_cost_id' => 2,
            'created_by' => Auth::guard('web')->user()->id,
            'provider_id' => $provider->id,
            'number_compte' => $account_number_user_bank,
            'costs_amount' => $amount_user->amount,
            'firstname' => $firstname_user,
            'lastname' => $lastname_user,
            'balance_before' => sabdy()->wallet->amount,
            'phone' => $phone_user,
            'status' => 'PENDING',
            'amount_solde' => $amount_solde ?? null,
            'country_id' => $country_virement->id ?? null,
            'amount_received' => $amountReceved ?? null,
        ]);
    }

    public function createNewInvoiceTransaction($walletTransaction, $transactionCompiCode, $transactionCode)
    {
        return Transaction::create([
            'wallet_transaction_id' => $walletTransaction->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'RB_' . $walletTransaction->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $walletTransaction->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => $walletTransaction->amount,
            'fee_amount' => 0,
            'received_amount' => $walletTransaction->amount,
            'status' => $walletTransaction->status,
        ]);
    }


    public function createInvoiceTransactionDrawal($transaction, $walletTransaction)
    {
        return   InvoiceTransaction::create([
            'title' => "Transfers d'argent",
            'wallet_transaction_id' => $walletTransaction->id,
            'transactions_id' => $transaction->id,
            'company_id' => Auth::guard('web')->user()->company->id,
            'description' => $walletTransaction->description,
            'status' => $walletTransaction->status,
            'created_by' => Auth::guard('web')->user()->id,
        ]);
    }


    public function createNewPhoneCredit(
        $walletUser,
        $amount_phone_credit,
        $provider,
        $percent_robus,
        $number_phone_credit,
        $amount_solde,
        $amountReceved
    ) {
        return WalletPhoneCredit::create([
            'wallet_id' => $walletUser->wallet->id,
            'amount' => $amount_phone_credit,
            'description' => 'Demande de credit',
            'phone_credit_cost_id' => 1,
            'created_by' => Auth::guard('web')->user()->id,
            'provider_id' => $provider->id,
            'balance_before' => sabdy()->wallet->amount,
            'costs_amount' => $percent_robus->percent  *  $amount_phone_credit,
            'phone' => Auth::guard('web')->user()->country->indecative .  $number_phone_credit,
            'status' => 'PENDING',
            'amount_solde'  => $amount_solde ?? null,
            'amount_received'  =>  $amountReceved ?? 0.0,
        ]);
    }

    public function createInvoicePhoneCredit($check, $paymentPhoneCredit)
    {
        return  InvoicePhoneCredit::create([
            'title' => 'Achat de credit telephonique',
            'wallet_phone_credit_id' => $check->id,
            'payment_credit_id' => $paymentPhoneCredit->id,
            'company_id' => Auth::guard('web')->user()->company->id,
            'description' => $check->description ?? null,
            'status' => $check->status,
            'created_by' => Auth::guard('web')->user()->id,
        ]);
    }

    public function createPhoneCredit($check, $transactionCode, $transactionCompiCode)
    {
        return PaymentPhoneCredit::create([
            'wallet_phone_credit_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'ROBUS_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'ROBUS_' . $transactionCompiCode,
            'pay_amount' => $check->amount,
            'fee_amount' => $check->costs_amount ?? null,
            'received_amount' => $check->amount,
            'status' => $check->status,
        ]);
    }

    public function createBankSend($check, $transactionCode, $transactionCompiCode)
    {
        return InvoiceBankSend::create([
            'wallet_bank_send_id' => $check->id,
            'created_by' => Auth::guard('web')->user()->id,
            'transaction_id' => 'ROBUS_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'pay_amount' => $check->amount,
            'fee_amount' => $check->costs_amount ?? null,
            'received_amount' => $check->amount,
            'status' => $check->status,
        ]);
    }

    public function createXbet($check, $transactionCode, $transactionCompiCode)
    {
        return OneBetTransaction::create([
            'wallet_xbet_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'ROBUS_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'ROBUS_' . $transactionCompiCode,
            'pay_amount' => $check->amount,
            'fee_amount' => $check->costs_amount ?? null,
            'received_amount' => $check->amount,
            'status' => $check->status,
        ]);
    }

    public function createVersement($check, $transactionCode, $transactionCompiCode)
    {
        return AutherTransaction::create([
            'wallet_versement_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'ROBUS_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'ROBUS_' . $transactionCompiCode,
            'pay_amount' => $check->amount,
            'fee_amount' => $check->costs_amount ?? null,
            'received_amount' => $check->amount,
            'status' => $check->status,
        ]);
    }


    public function createPush($check, $transactionCode, $transactionCompiCode)
    {
        return AutherTransaction::create([
            'wallet_push_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'ROBUS_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'ROBUS_' . $transactionCompiCode,
            'pay_amount' => $check->amount,
            'fee_amount' => $check->costs_amount ?? null,
            'received_amount' => $check->amount,
            'status' => $check->status,
        ]);
    }

    public function createDjamo($check, $transactionCode, $transactionCompiCode)
    {
        return AutherTransaction::create([
            'wallet_djamo_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'ROBUS_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'ROBUS_' . $transactionCompiCode,
            'pay_amount' => $check->amount,
            'fee_amount' => $check->costs_amount ?? null,
            'received_amount' => $check->amount,
            'status' => $check->status,
        ]);
    }


    public function createTresort($check, $transactionCode, $transactionCompiCode)
    {
        return AutherTransaction::create([
            'wallet_tresort_money_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'ROBUS_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'ROBUS_' . $transactionCompiCode,
            'pay_amount' => $check->amount,
            'fee_amount' => $check->costs_amount ?? null,
            'received_amount' => $check->amount,
            'status' => $check->status,
        ]);
    }


    public function createErrorXbet($check, $transactionCode, $transactionCompiCode)
    {
        return OneBetTransaction::create([
            'wallet_xbet_id' => $check->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'ROBUS_' . $check->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $check->provider->name,
            'trans_comp_id' => 'ROBUS_' . $transactionCompiCode,
            'pay_amount' => $check->amount,
            'fee_amount' => $check->costs_amount ?? null,
            'received_amount' => $check->amount,
            'status' => $check->status,
        ]);
    }

    public function createInvoiceXbet($check, $paymentPhoneCredit)
    {
        return  OneBetInvoice::create([
            'title' => 'Achat de Transaction 1XBET',
            'wallet_xbet_id' => $check->id,
            'one_bet_transaction_id' => $paymentPhoneCredit->id,
            // 'company_id' => Auth::guard('web')->user()->company->id,
            'description' => $check->description ?? null,
            'status' => $check->status,
            'created_by' => Auth::guard('web')->user()->id,
        ]);
    }


    public function createInvoiceVersement($check)
    {
        return  AutherTransactionInvoice::create([
            'title' => 'Achat de Transaction',
            'auther_transaction_id' => $check->id,
            'description' => $check->description ?? null,
            'status' => $check->status,
            'created_by' => Auth::guard('web')->user()->id,
        ]);
    }

    public function createNewReloadCard(
        $amount_solde,
        $walletUser,
        $amount_reloadcard,
        $reloadcard_description,
        $account_number_reload_card,
        $name_reloadcard,
        $amount_user,
        $provider,
        $amountReceved,
    ) {
        return WalletDeposit::create(
            [
                'amount_solde'  => $amount_solde ?? null,
                'wallet_id' => $walletUser->wallet->id,
                'amount' => $amount_reloadcard,
                'description' => $reloadcard_description,
                'num_carte' =>  $account_number_reload_card,
                'name' => $name_reloadcard,
                'balance_before' => sabdy()->wallet->amount,
                'deposit_cost_id' => 2,
                'costs_amount' =>  $amount_user,
                'created_by' => Auth::guard('web')->user()->id,
                'provider_id' => $provider->id,
                'amount_received' => $amountReceved,
                'status' => 'PENDING',
            ]
        );
    }


    public function createNewPaiementCanal(
        $walletUser,
        $offer,
        $paiement_decoder_canal,
        $paiement_name,
        $paiement_id_invoice,
        $paiement_num_contrat,
        $pre_phone_invoice,
        $pre_id_invoice,
        $paiement_name_sodecie,
        $provider,
        $percent_robus,
        $paiement_phone_canal,
        $paiementChoix_phone,
        $paiementChoix_type,
        $facture_choix_type,
        $amount_received,
        $amount_solde,
    ) {
        return WalletPayment::create([
            'wallet_id' => $walletUser->wallet->id,
            'amount' => $offer->amount ?? null,
            'description' => 'Paiement de facture CANAL',
            'payment_cost_id' => 2,
            'number_decoder' => $paiement_decoder_canal ?? null,
            'id_invoice' => $paiement_id_invoice ?? null,
            'number_contract' =>  $paiement_num_contrat ?? null,
            'phone' => null,
            'id_invoice_pre' => $pre_id_invoice ?? null,
            'name' => $paiement_name ?? $pre_phone_invoice,
            'balance_before' => sabdy()->wallet->amount,
            'created_by' => Auth::guard('web')->user()->id,
            'provider_id' => $provider->id,
            'costs_amount' => $percent_robus->amount_canal,
            'number_phone' => $paiement_phone_canal ?? null,
            'offer_id' => $offer->id ?? null,
            'status' => 'PENDING',
            'paiementChoix_phone' => $paiementChoix_phone ?? null,
            'paiementChoix_type' =>  $paiementChoix_type ?? null,
            'factureChoix_type' => $facture_choix_type ?? null,
            'amount_received' => $amount_received ?? null,
            'amount_solde'  =>   $amount_solde ?? null
        ]);
    }


    public function createNewPaiementSodeci(
        $walletUser,
        $paiement_amount_t_sodecie,
        $paiement_amount_p_sodecie,
        $paiement_decoder_canal,
        $paiement_id_invoice,
        $paiement_num_contrat,
        $pre_phone_invoice,
        $pre_id_invoice,
        $paiement_name_sodecie,
        $provider,
        $percent_robus,
        $paiement_phone_canal,
        $offer,
        $paiementChoix_phone,
        $paiementChoix_type,
        $facture_choix_type,
        $amount_received,
        $amount_solde,
    ) {
        return WalletPayment::create([
            'wallet_id' => $walletUser->wallet->id,
            'amount' => $paiement_amount_t_sodecie == null ? $paiement_amount_p_sodecie : $paiement_amount_t_sodecie,
            'description' => 'Paiement de facture SODECI',
            'payment_cost_id' => 2,
            'number_decoder' => $paiement_decoder_canal ?? null,
            'id_invoice' => $paiement_id_invoice ?? null,
            'number_contract' => $paiement_num_contrat ?? null,
            'phone' => $pre_phone_invoice ?? null,
            'id_invoice_pre' => $pre_id_invoice ?? null,
            'balance_before' => sabdy()->wallet->amount,
            'name' => $paiement_name_sodecie ?? null,
            'created_by' => Auth::guard('web')->user()->id,
            'provider_id' => $provider->id,
            'costs_amount' => $percent_robus->amount_auther,
            'number_phone' => $paiement_phone_canal ?? null,
            'offer_id' => $offer->id ?? null,
            'status' => 'PENDING',
            'paiementChoix_phone' => $paiementChoix_phone ?? null,
            'paiementChoix_type' =>  $paiementChoix_type ?? null,
            'factureChoix_type' => $facture_choix_type ?? null,
            'amount_received' => $amount_received ?? null,
            'amount_solde'  =>   $amount_solde  ?? null
        ]);
    }

    public function createNewPaiement(
        $walletUser,
        $paiement_amount_total,
        $paiement_decoder_canal,
        $paiement_id_invoice_pre,
        $paiement_id_invoice,
        $paiement_num_contrat,
        $pre_phone_invoice,
        $paiement_name_cie,
        $provider,
        $paiement_phone_canal,
        $offer,
        $percent_robus,
        $paiementChoix_phone,
        $paiementChoix_type,
        $facture_choix_type,
        $amount_received,
        $amount_solde,
    ) {
        return WalletPayment::create([
            'wallet_id' => $walletUser->wallet->id,
            'amount' => $paiement_amount_total,
            'description' => 'Paiement de facture',
            'payment_cost_id' => 2,
            'number_decoder' => $paiement_decoder_canal ?? null,
            'id_invoice' => $paiement_id_invoice_pre == null ? $paiement_id_invoice : $paiement_id_invoice_pre,
            'number_contract' =>  $paiement_num_contrat ?? null,
            'phone' => $pre_phone_invoice ?? null,
            'id_invoice_pre' =>  $pre_id_invoice ?? null,
            'name' => $paiement_name_cie ?? null,
            'created_by' => Auth::guard('web')->user()->id,
            'balance_before' => sabdy()->wallet->amount,
            'provider_id' => $provider->id,
            'number_phone' => $paiement_phone_canal ?? null,
            'offer_id' => $offer->id ?? null,
            'costs_amount' => $percent_robus->amount_auther,
            'status' => 'PENDING',
            'paiementChoix_phone' =>  $paiementChoix_phone ?? null,
            'paiementChoix_type' => $paiementChoix_type ?? null,
            'factureChoix_type' => $facture_choix_type ?? null,
            'amount_received' => $amount_received ?? null,
            'amount_solde'  =>   $amount_solde ?? null
        ]);
    }

    public function createRobusWalletWithDrawalTransaction($transaction_amount_robus, $provider, $compte_number_robus, $wallet_search, $amount_drawal_percent)
    {


        $todays = strtotime(Carbon::now());
        $compare_date = date('H:i:s', $todays);
        $temps_ecoule = strtotime($compare_date);
        $ecoule_min = date('s', $temps_ecoule);
        $robus = str_shuffle('1234567894568392344224');
        $rt = rand(40, 906769895) * time() + $ecoule_min;
        $code_valdated = sprintf('%0.4s', str_shuffle($rt . $temps_ecoule . $robus));
        $val_amount = $transaction_amount_robus;

        if ($wallet_search->sidUser->country->name_groupe == 'CIV') {

            $message = 'Code de validation : ' . $code_valdated  . ' . Ne partagez ce code qu\'avec un agent ROBUS lors d\'une transaction ' . ' Expire dans 5 min';

            sendTryCashMessage($wallet_search->sidUser->phone, $message);
        }

        if ($wallet_search->sidUser->email != null) {

            Mail::to($wallet_search->sidUser->email)->send(new CodeValidated(' CODE : '  . $code_valdated));
        }

        return  WalletTransaction::create([
            'wallet_id' =>  sabdy()->wallet->id,
            'amount' => $val_amount,
            'description'  => 'Demande de retrait Robus',
            'transaction_cost_id' => 1,
            'created_by' => sabdy()->id,
            'provider_id'  => $provider,
            'balance_before' => sabdy()->wallet->amount,
            'num_wallet' => strtoupper($compte_number_robus),
            'choose_type_transaction' => 1,
            'costs_amount' => $amount_drawal_percent,
            'type_transaction' => 'Retrait',
            'choose_type_transaction' => 2,
            'code_validated' => $code_valdated,
            'phone'  =>   null,
            'status'  => "PENDING"
        ]);
    }


    public function updatePaiementWithdrawalWallet($wallet_search, $commission_send)
    {

        return $wallet_search->update([
            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
            'sid_user_relod' => Auth::guard('web')->user()->id,
            'amount' => DB::raw('amount-' . $commission_send),
        ]);
    }

    public function updatePaiementDrawalWallet($walletUser, $commission)
    {

        return $walletUser->wallet->update([
            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
            'sid_user_relod' => Auth::guard('web')->user()->id,
            'amount' => DB::raw('amount+' . $commission),
        ]);
    }
}
