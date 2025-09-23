<?php

namespace App\Http\Services;

use App\Models\Sid\SidUser;
use App\Models\Wallets\WalletTransaction;
use App\Models\Wallets\Wallet;
use App\Models\Payments\Transaction;
use App\Models\Invoices\InvoiceTransaction;
use Illuminate\Support\Facades\DB;
use App\Models\Custumers\Country;

class ServiceTransactionBackend
{
    /**
     * Distribuer la commission aux admins pour les compagnies acceptées
     */
    public function createRobusWalletTransaction($transaction_amount_robus, $percent_robus, $provider, $compte_number_robus, $amount_drawal)
    {
        $val_amount = $transaction_amount_robus;
        $amount_solde = (float)sabdy()->wallet->amount - (float)$val_amount;

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
                'provider_id' => $provider->id,
                'choose_type_transaction' => 1,
                'type_transaction' => 'Depot',
                'type_received' => 'Transfert',
                'status' => 'SUCCESS',
                'amount_solde' => $amount_solde ?? null,
                'amount_solde_drawal' => $amount_drawal ?? null
            ]
        );
    }

    public function createRobusWalletTransactionDistributer($percent_robus, $provider, $transaction_amount_money, $valuer_deposit_com, $transaction_phone)
    {

        $val_amount = $transaction_amount_money;

        $amount_solde = (float)sabdy()->wallet->amount - (float)$val_amount;

        return WalletTransaction::create(
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
                // 'name_complet' => $name_custumers ?? null,
                // 'num_piece' => $num_piece_custumers ?? null,
                // 'validated_date' => $date_piece ?? null,
                'choose_type_transaction' => 1,
                'amount_received'  => 0,
                'type_transaction' => 'Depot',
                'status'  => "PENDING",
                'amount_solde' => $amount_solde ?? null
            ]
        );
    }

    public function updateAmountRobusReceived($compte_number_robus, $walletTransaction, $valeur_received)
    {

        $wallet_search = Wallet::where('wallet_id', $compte_number_robus)
            ->where('country_id', sabdy()->country->id)
            //   ->where('status', true)
            ->first();

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));


        $commission_transaction_val_inter_convertion_send = (float)  $valeur_received;

        if ($commission_transaction_val_inter_convertion_send) {

            $walletTransaction->update(['amount_received' => $commission_transaction_val_inter_convertion_send]);

            if ($wallet_search) {

                $wallet_search->update([
                    'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                    'sid_user_relod' => sabdy()->id,
                    'amount' => DB::raw('amount+' . $commission_transaction_val_inter_convertion_send),
                ]);
            } else {
                return false;
            }

            $transaction = Transaction::create([
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

            InvoiceTransaction::create([
                'title' => "Transfers d'argent",
                'wallet_transaction_id' => $walletTransaction->id,
                'transactions_id' => $transaction->id,
                'company_id' => sabdy()->company_id,
                'description' => $walletTransaction->description,
                'status' => $walletTransaction->status,
                'created_by' => sabdy()->id,
            ]);

            return true;
        } else {

            return false;
        }
    }

    public function updateAmountRobusReceivedDistributer($compte_number_robus, $walletTransaction)
    {


        $wallet_search = Wallet::where('wallet_id', $compte_number_robus)
            ->where('country_id', sabdy()->country->id)
            //   ->where('status', true)
            ->first();

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));

        if (! $walletTransaction) {
            return false;
        }

        if (! $wallet_search) {
            return false;
        }

        $walletTransaction->update(['amount_received' => $walletTransaction->amount_received]);

        $wallet_search->update([
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount+' . $walletTransaction->amount_received),
        ]);

        $transaction = Transaction::create([
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

        InvoiceTransaction::create([
            'title' => "Transfers d'argent",
            'wallet_transaction_id' => $walletTransaction->id,
            'transactions_id' => $transaction->id,
            'company_id' => sabdy()->company->id,
            'description' => $walletTransaction->description,
            'status' => $walletTransaction->status,
            'created_by' => sabdy()->id,
        ]);

        return true;
    }

    public function getTodayTransactionCommissionAmountTotal($walletModel)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', sabdy()->wallet->id)
            ->whereStatus('SUCCESS')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }

    public function getTodayTransactionMobileCommissionAmountTotal($walletModel)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id',  sabdy()->wallet->id)
            ->whereStatus('SUCCESS')
            ->where('provider_id', '!=', 9)
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }

    public function updateAmountRobusReceivedMobile($walletTransaction, $valeur_received)
    {

        if (! $walletTransaction) {
            return false;
        }

        $amountReceived = (float)  $valeur_received;


        if ($amountReceived <= 0) {
            return false;
        }

        $walletTransaction->update(['amount_received' => $amountReceived]);

        return true;
    }

    public function processCommissionsRobusDistributer($transaction_amount_robus)
    {

        $commission =  $transaction_amount_robus;

        $wallet = sabdy()->wallet;

        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount - ' . $commission),
        ];

        $wallet->update($updatedValues);

        return true;
    }

    public function processCommissionsCustumerRobus($transaction_amount_robus, $percent_robus, $walletTransaction, $admin_civ)
    {

        $commission =  $transaction_amount_robus;
        $country_civ = Country::whereId(107)
            ->first();

        $wallet = sabdy()->wallet;


        $updatedValues = [
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount - ' . $commission),
        ];

        $wallet->update($updatedValues);

        return true;
    }

    public function getTodayTransactionRobusDepotCommissionAmountTotal($walletModel)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id',  sabdy()->wallet->id)
            ->whereStatus('SUCCESS')
            ->where('provider_id', 9)
            ->where('type_transaction', 'Depot')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }

    public function getTodayTransactionRobusRetraitCommissionAmountTotal($walletModel)
    {

        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', sabdy()->wallet->id)
            ->whereStatus('SUCCESS')
            ->where('provider_id', 9)
            ->where('type_transaction', 'Retrait')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }
}
