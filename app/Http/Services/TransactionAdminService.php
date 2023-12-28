<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\DB;
use App\Models\Payments\Transaction;
use App\Models\Invoices\InvoiceTransaction;
use App\Repositories\TransactionRepository;


class TransactionAdminService
{

    /** @var  TransactionRepository */
    private $transctionRepository;

    public function __construct(TransactionRepository $transactionReposi)
    {
        $this->transctionRepository = $transactionReposi;
    }

    public function createRobusWalletTransaction($transaction_amount_sabdy, $percent_sabdy, $provider, $compte_number_sabdy)
    {
        $val_amount = $transaction_amount_sabdy;

        $amount_solde = (float)sabdy()->wallet->amount - (float)$val_amount;

        return  $this->transctionRepository->create(
            [
                'wallet_id' => sabdy()->wallet->id,
                'amount' => $transaction_amount_sabdy,
                'description' => "Transfert d'argent vers Robus",
                'transaction_cost_id' =>  $percent_sabdy->id,
                'phone' =>  null,
                'costs_amount' => $percent_sabdy->percent * $transaction_amount_sabdy,
                'amount_received' => $transaction_amount_sabdy - ($percent_sabdy->percent * $transaction_amount_sabdy),
                'num_wallet' => $compte_number_sabdy,
                'name_complet' => null,
                'num_piece' => null,
                'validated_date' => null,
                'created_by' => sabdy()->id,
                'provider_id' => $provider,
                'choose_type_transaction' => 1,
                'type_transaction' => 'Depot',
                'type_received' => 'Transfert',
                'status' => 'SUCCESS',
                'amount_solde' => $amount_solde ?? null
            ]
        );
    }

    public function processCommissionsDistributerRobus($transaction_amount_sabdy, $percent_sabdy, $walletTransaction, $admin_civ)
    {
        $commission = $transaction_amount_sabdy;
        $country_civ = Country::find(107);
        $wallet = sabdy()->wallet;

        $updatedValues = [
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount - ' . $commission),
        ];

        $wallet->update($updatedValues);

        // Gestion des commissions d'intermédiaires
        if (sabdy()->is_under_distributer) {
            $valeur_percent_admin = $percent_sabdy->percent * $transaction_amount_sabdy;
            $valeur_admin = $percent_sabdy->percent_admin * $valeur_percent_admin;
            $valuer_deposit = $percent_sabdy->percent_deposit * $valeur_percent_admin;
            $valuer_with_drawal = $percent_sabdy->percent_with_drawal * $valeur_percent_admin;

            if ($country_civ->currencie->value > sabdy()->country->currencie->value) {
                $admin_valeur_convertion_drawal = $valuer_with_drawal * (float)sabdy()->country->currencie->value;
                $admin_valeur_convertion = (float)$valeur_admin * (float)sabdy()->country->currencie->value;
            } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {
                $admin_valeur_convertion_drawal = $valuer_with_drawal;
                $admin_valeur_convertion = (float)$valeur_admin;
            } else {
                $admin_valeur_convertion_drawal = $valuer_with_drawal * (float)sabdy()->country->currencie->value;
                $admin_valeur_convertion = (float)$valeur_admin * (float)sabdy()->country->currencie->value;
            }

            $walletTransaction->increment('commissions', $valuer_deposit);

            if ($admin_civ->count() > 0) {
                $nmbre_admin = $admin_civ->count();
                $valeur_single = ($admin_valeur_convertion + $admin_valeur_convertion_drawal) / $nmbre_admin;

                foreach ($admin_civ as $user) {
                    $this->processAdminCommissionUnderDistrubuter($user->id, $walletTransaction->id, $valeur_single, $valuer_deposit);
                }
            }
        } else {

            $valeur_percent_admin = $percent_sabdy->percent_admin * $transaction_amount_sabdy;
            $admin_valeur_convertion = $this->convertAdminCommission($valeur_percent_admin);

            if ($admin_civ->count() > 0) {
                $nmbre_admin = $admin_civ->count();
                $valeur_single = $admin_valeur_convertion / $nmbre_admin;

                foreach ($admin_civ as $user) {
                    $this->processAdminCommission($user->id, $walletTransaction->id, $valeur_single);
                }
            }
        }

        return true;
    }

    private function processAdminCommissionUnderDistrubuter($userId, $walletTransactionId, $amount, $valuer_deposit)
    {
        $admin_single = SidUser::find($userId);

        AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'wallet_transaction_id' => $walletTransactionId,
            'description' => 'Commission administrateur',
            'amount_under' =>  robus()->is_under_distributer ? $valuer_deposit : 0,
            'description_underdistributer' =>  robus()->is_under_distributer ? 'Paiement commission sous-distributer' : null
        ]);

        $admin_single->wallet->increment('amount', $amount);
    }

    private function processAdminCommission($userId, $walletTransactionId, $amount)
    {
        $admin_single = SidUser::find($userId);

        AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'wallet_transaction_id' => $walletTransactionId,
            'description' => 'Commission administrateur',
        ]);

        $admin_single->wallet->increment('amount', $amount);
    }
    private function convertAdminCommission($amount)
    {
        $country_civ = Country::find(107);
        $sabdy_currency = sabdy()->country->currencie->value;

        if ($country_civ->currencie->value > sabdy()->country->currencie->value) {
            return $amount * $sabdy_currency;
        } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {
            return $amount;
        } else {
            return $amount * $sabdy_currency;
        }
    }

    public function updateAmountRobusReceived($compte_number_sabdy, $walletTransaction, $valeur_received)
    {

        $wallet_search = Wallet::where('wallet_id', $compte_number_sabdy)
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



    public function searchWalletRobus($num_wallet)
    {
        // Search acompte ROBUS
        return Wallet::where('wallet_id',  $num_wallet)
            ->where('country_id',  sabdy()->country->id)
            ->whereStatus(true)
            ->first();
    }

    public function updateAmountRobusReceivedDistributer($compte_number_sabdy, $walletTransaction, $valeur_received)
    {

        $wallet_search = Wallet::where('wallet_id', $compte_number_sabdy)
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
                'company_id' => sabdy()->company->id,
                'description' => $walletTransaction->description,
                'status' => $walletTransaction->status,
                'created_by' => sabdy()->id,
            ]);

            return true;
        } else {

            return false;
        }
    }

    public function processCommissionsCustumerRobus($transaction_amount_sabdy, $percent_sabdy, $walletTransaction, $admin_civ)
    {

        $commission =  $transaction_amount_sabdy;
        $country_civ = Country::whereId(107)
            ->first();

        $wallet = sabdy()->wallet;


        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount - ' . $commission),
        ];

        $wallet->update($updatedValues);

        // Percent admin 
        $valeur_percent_admin  = $percent_sabdy->percent_admin * $transaction_amount_sabdy;


        if ($country_civ->currencie->value > sabdy()->country->currencie->value) {

            $admin_valeur_convertion =   (float)  $valeur_percent_admin *   (float) sabdy()->country->currencie->value;
        } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {

            $admin_valeur_convertion = (float)   $valeur_percent_admin;
        } else {

            $admin_valeur_convertion =   (float)  $valeur_percent_admin *   (float) sabdy()->country->currencie->value;
        }

        if ($admin_civ->count() > 0) {

            $nmbre_admin = $admin_civ->count();

            $valeur_single = $admin_valeur_convertion / $nmbre_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);

                AdminsCom::create([
                    'amount' =>   $valeur_single,
                    'sid_user_id' =>   $admin_single->id,
                    'wallet_transaction_id' => $walletTransaction->id,
                    'amount_with_draw' =>  0,
                    'description' => 'Commission administrateur',
                ]);

                $admin_single->wallet->update([
                    'reload_by' => sabdy()->firstname .
                        ' ' .
                        sabdy()->lastname,
                    'sid_user_relod' => sabdy()->id,
                    'amount' => DB::raw('amount+' . $valeur_single),
                ]);
            }
        }


        return true;
    }
    public function processCommissionsMobile($transaction_amount_money, $percent_sabdy, $walletTransaction, $admin_civ)
    {

        $commission =  $transaction_amount_money;
        $country_civ = Country::whereId(107)
            ->first();

        $wallet = sabdy()->wallet;


        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount - ' . $commission),
        ];

        $wallet->update($updatedValues);

        // Commissions Inters 
        if (sabdy()->is_under_distributer == 1) {

            // Percent admin 
            $valeur_percent_admin  = $percent_sabdy->percent * $transaction_amount_money;

            // 50 %
            $valuer_admin = $percent_sabdy->percent_admin * $valeur_percent_admin;

            // 20%
            $valuer_deposit = $percent_sabdy->percent_deposit *  $valeur_percent_admin;


            if ($country_civ->currencie->value > sabdy()->country->currencie->value) {

                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valuer_admin *   (float) sabdy()->country->currencie->value;
            } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {
                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valuer_admin;
            } else {
                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valuer_admin *   (float) sabdy()->country->currencie->value;
            }


            $walletTransaction->update([
                'commissions' => DB::raw('commissions+'  . $valuer_deposit),
            ]);
        } else {


            // Percent admin 
            $valeur_percent_admin  = $percent_sabdy->percent_admin * $transaction_amount_money;


            if ($country_civ->currencie->value > sabdy()->country->currencie->value) {

                $admin_valeur_convertion =   (float)  $valeur_percent_admin *   (float) sabdy()->country->currencie->value;
            } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {

                $admin_valeur_convertion = (float)   $valeur_percent_admin;
            } else {

                $admin_valeur_convertion =   (float)  $valeur_percent_admin *   (float) sabdy()->country->currencie->value;
            }
        }

        if ($admin_civ->count() > 0) {

            $nmbre_admin = $admin_civ->count();

            $valeur_single = $admin_valeur_convertion / $nmbre_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);

                AdminsCom::create([
                    'amount' =>   $valeur_single,
                    'sid_user_id' =>   $admin_single->id,
                    'wallet_transaction_id' => $walletTransaction->id,
                    'wallet_bank_id' => null,
                    'wallet_deposit_id' => null,
                    'amount_with_draw' =>  0,
                    'wallet_inter_id' => null,
                    'wallet_payment_id' => null,
                    'wallet_phone_credit_id' => null,
                    'wallet_reload_id' => null,
                    'bank_card_id' => null,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  robus()->is_under_distributer ? $valuer_deposit : 0,
                    'description_underdistributer' =>  robus()->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ]);

                $admin_single->wallet->update([
                    'reload_by' => sabdy()->firstname .
                        ' ' .
                        sabdy()->lastname,
                    'sid_user_relod' => sabdy()->id,
                    'amount' => DB::raw('amount+' . $valeur_single),
                ]);
            }
        }

        return true;
    }

    public function processCommissionsRobusDistributer($transaction_amount_sabdy)
    {

        $commission =  $transaction_amount_sabdy;

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
}
