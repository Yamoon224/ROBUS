<?php

namespace App\Http\Services;

use App\Models\Sid\SidUser;
use App\Models\AdminsCom;
use App\Models\Wallets\WalletCommission;
use App\Models\Wallets\WalletAdmin;
use Illuminate\Support\Facades\DB;

class CommissionService
{
    /**
     * Distribuer la commission aux admins pour les compagnies acceptées
     */
    public function distributeAdminCommissionAcceptedCompanies($adminCiv, $walletColumn, $walletId, $totalCommission, $amountDeposit)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $walletCommission = WalletCommission::find(1);

        if ($adminCiv->count() > 0) {

            $commissionPerAdmin = $totalCommission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' => robus()->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' => robus()->is_under_distributer ? 'Paiement commission sous-distributer' : null,
                ], [$walletColumn => $walletId]));

                $total_commission = (float) $amountDeposit  + (float) $totalCommission;
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

    public function processAdminCommissionSDistributer($userId, $walletTransactionId, $amount, $valuer_deposit)
    {



        $admin_single = SidUser::find($userId);

        AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'wallet_transaction_id' => $walletTransactionId,
            'description' => 'Commission administrateur',
            'status'  => "SUCCESS",
            'amount_under' =>  robus()->is_under_distributer ? $valuer_deposit : 0,
            'description_underdistributer' =>  robus()->is_under_distributer ? 'Paiement commission sous-distributer' : null
        ]);

        $walletCommission = WalletCommission::find(1);
        $walletAdmin = WalletAdmin::find(1);
        if ($walletCommission && $walletCommission->amount >= $amount) {
            $walletCommission->decrement('amount', $amount);
            $walletAdmin->increment('amount', $amount);
        } else {
            $admin_single->wallet->decrement('amount', $amount);
        }
    }

    public function distributeAdminCommissionAccepteds($adminCiv, $walletColumn, $walletId, $totalCommission, $amountDeposit)
    {


        if ($adminCiv->isEmpty()) {
            return;
        }


        $walletCommission = WalletCommission::find(1);

        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $totalCommission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  robus()->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' =>  robus()->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ], [$walletColumn => $walletId]));

                if ($walletCommission && $walletCommission->amount >= $amountDeposit) {
                    $walletCommission->decrement('amount', $amountDeposit);
                } else {
                    $adminUser->wallet->decrement('amount', $totalCommission);
                }
            }
        }
    }
}
