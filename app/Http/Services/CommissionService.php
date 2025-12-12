<?php

namespace App\Http\Services;

use App\Models\Sid\SidUser;
use App\Models\AdminsCom;
use App\Models\Wallets\WalletCommission;
use App\Models\Wallets\WalletAdmin;
use Illuminate\Support\Facades\DB;
use App\Models\Custumers\Country;

class CommissionService
{
    /**
     * Distribuer la commission aux admins pour les compagnies acceptées
     */

    public function SendSms()
    {
        // if (robus()->country->code == 'CI') {

        //     $message =  'Vous avez effectué un paiement de ' . $walletInter->amount .  robus()->wallet->currencie->code .
        //         ' ' .
        //         'par ' . $walletInter->provider->name .
        //         ' a ' . $walletInter->created_at .
        //         ' votre demande est en-cours de validation ' .
        //         'Robus vous remercie';

        //     $encode = substr(robus()->phone, -10);

        //     Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
        // }
    }
    public function distributeAdminCommissionAcceptedCompanies($adminCiv, string $walletColumn, $walletId, $totalCommission, $amountDeposit)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $walletCommission = WalletCommission::find(1);
        $userUnder = auth_user();

        if ($adminCiv->count() > 0) {

            $total_commission = (float) $amountDeposit  + (float) $totalCommission;

            $commissionPerAdmin = $total_commission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' => $userUnder->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' => $userUnder->is_under_distributer ? 'Paiement commission sous-distributer' : null,
                ], [$walletColumn => $walletId]));

                if ($walletCommission && $walletCommission->amount >= $total_commission) {
                    $walletCommission->decrement('amount', $total_commission);
                } else {
                    $adminUser->wallet->decrement('amount', $total_commission);
                }
            }
        }
    }

    public function distributeAdminCommissions($adminCiv, $walletColumn, $walletId, $totalCommission)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }


        $walletAdmin = WalletAdmin::find(1);

        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $totalCommission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  0,
                    'description_underdistributer' => null,
                ], [$walletColumn => $walletId]));


                if ($walletAdmin && $walletAdmin->amount >= $totalCommission) {
                    $walletAdmin->increment('amount', $totalCommission);
                } else {
                    $adminUser->wallet->increment('amount', $totalCommission);
                }
            }
        }
    }

    public function processAdminCommission($userId, $walletTransactionId, $amount)
    {
        $admin_single = SidUser::find($userId);

        AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'status'  => "SUCCESS",
            'wallet_transaction_id' => $walletTransactionId,
            'description' => 'Commission administrateur',
        ]);

        $walletAdmin = WalletAdmin::find(1);
        if ($walletAdmin && $walletAdmin->amount >= $amount) {
            $walletAdmin->increment('amount', $amount);
        } else {
            $admin_single->wallet->increment('amount', $amount);
        }
    }

    public function processAdminCommissionSDistributerAndComp($userId, $walletTransactionId, $amount, $valuer_deposit, $amountCompagie)
    {


        $user = auth_user();

        $admin_single = SidUser::find($userId);

        AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'wallet_transaction_id' => $walletTransactionId,
            'description' => 'Commission administrateur',
            'status'  => "SUCCESS",
            "amount_compagnie" => $amountCompagie ?? 0,
            'amount_under' =>  $user->is_under_distributer ? $valuer_deposit : 0,
            'description_underdistributer' =>  $user->is_under_distributer ? 'Paiement commission sous-distributer' : null
        ]);

        $amount_all = (float)$valuer_deposit +  (float)$amountCompagie;
        $walletCommission = WalletCommission::find(1);
        $walletAdmin = WalletAdmin::find(1);
        if ($walletCommission && $walletCommission->amount >= $amount) {
            $walletCommission->decrement('amount', $amount_all);
            $walletAdmin->increment('amount', $amount);
        } else {
            $admin_single->wallet->decrement('amount', $amount_all);
            $admin_single->wallet->increment('amount', $amount);
        }
    }

    public function processAdminCommissionSDistributer($userId, $walletTransactionId, $amount, $valuer_deposit)
    {

        $user = auth_user();

        $admin_single = SidUser::find($userId);

        AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'wallet_transaction_id' => $walletTransactionId,
            'description' => 'Commission administrateur',
            'status'  => "SUCCESS",
            "amount_compagnie" => 0,
            'amount_under' =>  $user->is_under_distributer ? $valuer_deposit : 0,
            'description_underdistributer' =>  $user->is_under_distributer ? 'Paiement commission sous-distributer' : null
        ]);

        $walletCommission = WalletCommission::find(1);
        $walletAdmin = WalletAdmin::find(1);
        if ($walletCommission && $walletCommission->amount >= $amount) {
            $walletCommission->decrement('amount', $valuer_deposit);
            $walletAdmin->increment('amount', $amount);
        } else {
            $admin_single->wallet->decrement('amount', $valuer_deposit);
            $admin_single->wallet->increment('amount', $amount);
        }
    }

    public function distributeAdminCommissionAccepteds($adminCiv, string $walletColumn, $walletId, $totalCommission, $amountDeposit)
    {


        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();

        $walletCommission = WalletCommission::find(1);

        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $totalCommission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  $userUnder->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' =>  $userUnder->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ], [$walletColumn => $walletId]));

                if ($walletCommission && $walletCommission->amount >= $amountDeposit) {
                    $walletCommission->decrement('amount', $amountDeposit);
                } else {
                    $adminUser->wallet->decrement('amount', $totalCommission);
                }
            }
        }
    }


    public function distributeAdminCommissionUnders($adminCiv, string $walletColumn, $walletId , $amountDeposit)
    {


        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();

        $walletCommission = WalletCommission::find(1);

        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $amountDeposit / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  $userUnder->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' =>  $userUnder->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ], [$walletColumn => $walletId]));

                if ($walletCommission && $walletCommission->amount >= $amountDeposit) {
                    $walletCommission->decrement('amount', $amountDeposit);
                } else {
                    $adminUser->wallet->decrement('amount', $amountDeposit);
                }
            }
        }
    }

    public function convertAdminCommission($amount)
    {

        $user = auth_user();

        $country_civ = Country::find(107);
        $robus_currency = $user->country->currencie->value;

        if ($country_civ->currencie->value > $user->country->currencie->value) {
            return $amount * $robus_currency;
        } elseif ($country_civ->currencie->value == $user->country->currencie->value) {
            return $amount;
        } else {
            return $amount * $robus_currency;
        }
    }
}
