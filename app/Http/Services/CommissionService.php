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

        //     Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
        // }
    }
    public function distributeAdminCommissionAcceptedCompanies($adminCiv, string $walletColumn, $walletId, $totalCommission, $amountDeposit)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();

        if ($adminCiv->count() > 0) {

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => 0,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_compagnie' => $userUnder->is_under_distributer ? $totalCommission : 0,
                    'amount_under' => $userUnder->is_under_distributer ? $amountDeposit : 0,
                    'is_under' => true,
                    'description_underdistributer' => $userUnder->is_under_distributer ? 'Paiement commission sous-distributer' : null,
                ], [$walletColumn => $walletId]));
            }
        }
    }

     public function distributeAdminCommissionAcceptedXbetCompanies($adminCiv, string $walletColumn, $walletId , $admin_valeur, $totalCommission, $amountDeposit)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();

        if ($adminCiv->count() > 0) {

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $admin_valeur ?? 0,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_compagnie' => $userUnder->is_under_distributer ? $totalCommission : 0,
                    'amount_under' => $userUnder->is_under_distributer ? $amountDeposit : 0,
                    'is_under' => true,
                    'description_underdistributer' => $userUnder->is_under_distributer ? 'Paiement commission sous-distributer' : null,
                ], [$walletColumn => $walletId]));
            }
        }
    }

     public function distributeAdminCommissionXbetNoCompanies($adminCiv, string $walletColumn, $walletId , $admin_valeur, $amountDeposit)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();

        if ($adminCiv->count() > 0) {

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $admin_valeur ?? 0,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_compagnie' =>  0,
                    'amount_under' => $userUnder->is_under_distributer ? $amountDeposit : 0,
                    'is_under' => true,
                    'description_underdistributer' => $userUnder->is_under_distributer ? 'Paiement commission sous-distributer' : null,
                ], [$walletColumn => $walletId]));
            }
        }
    }


    public function distributeAdminCommissionUnderBankAcceptedCompanies($adminCiv, string $walletColumn, $walletId, $valuer_admin_compagnie, $totalCommission, $amountDeposit, $amountBank = null)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();

        if ($adminCiv->count() > 0) {

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $valuer_admin_compagnie,
                    'sid_user_id' => $adminUser->id,
                    'amount_bank' => $amountBank,
                    'amount_compagnie' => $userUnder->is_under_distributer ? $totalCommission : 0,
                    'description' => 'Commission administrateur',
                    'amount_under' => $userUnder->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' => $userUnder->is_under_distributer ? 'Paiement commission sous-distributer' : null,
                ], [$walletColumn => $walletId]));
            }
        }
    }


    public function distributeAdminCommissionBankAcceptedCompanies($adminCiv, string $walletColumn, $walletId, $totalCommission, $amountDeposit, $amountBank = null)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();

        if ($adminCiv->count() > 0) {

            $total_commission = (float) $amountDeposit  + (float) $totalCommission;

            $commissionPerAdmin = $total_commission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'amount_bank' => $amountBank,
                    'description' => 'Commission administrateur',
                    'amount_under' => $userUnder->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' => $userUnder->is_under_distributer ? 'Paiement commission sous-distributer' : null,
                ], [$walletColumn => $walletId]));
            }
        }
    }

    public function distributeAdminAndBankCommissionAcceptedCompanies($adminCiv, string $walletColumn, $walletId, $totalCommission, $amountDeposit, $amountBank)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();

        if ($adminCiv->count() > 0) {

            $total_commission = (float) $amountDeposit  + (float) $totalCommission;

            $commissionPerAdmin = $total_commission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'amount_bank' => $amountBank,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' => $userUnder->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' => $userUnder->is_under_distributer ? 'Paiement commission sous-distributer' : null,
                ], [$walletColumn => $walletId]));
            }
        }
    }

     public function distributeAdminAndVersementCommissionAcceptedCompanies($adminCiv, string $walletColumn, $walletId , $admin_amount, $totalCompanie, $amountDeposit, $amountBank)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();

        if ($adminCiv->count() > 0) {

            $commissionPerAdmin = $admin_amount / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'amount_compagnie' => $totalCompanie,
                    'amount_bank' => $amountBank,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' => $userUnder->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' => $userUnder->is_under_distributer ? 'Paiement commission sous-distributer' : null,
                ], [$walletColumn => $walletId]));
            }
        }
    }

      public function distributeAdminInterCommissions($adminCiv, $walletColumn, $walletId, $totalCommission)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();

        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $totalCommission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' => 0,
                    'description_underdistributer' => null,
                ], [$walletColumn => $walletId]));
            }
        }
    }


    public function distributeAdminCommissions($adminCiv, $walletColumn, $walletId, $totalCommission)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();

        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $totalCommission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' => $userUnder->is_under_distributer ? $totalCommission : 0,
                    'description_underdistributer' => null,
                ], [$walletColumn => $walletId]));
            }
        }
    }

       public function distributeAdminUnderCommissions($adminCiv, $walletColumn, $walletId, $totalCommission)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();

        if ($adminCiv->count() > 0) {

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => 0,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' => $userUnder->is_under_distributer ? $totalCommission : 0,
                    'description_underdistributer' => null,
                ], [$walletColumn => $walletId]));
            }
        }
    }

    public function distributeAdminBanqueCommissions($adminCiv, $walletColumn, $walletId, $totalCommission, $totalBank)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }


        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $totalCommission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'amount_bank' => $totalBank,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  0,
                    'description_underdistributer' => null,
                ], [$walletColumn => $walletId]));
            }
        }
    }

    public function distributeAdminBanqueInterCommissions($adminCiv, $walletColumn, $walletId, $totalCommission,  $totalBank , $totalUnder)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }
        $userUnder = auth_user();

        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $totalCommission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'amount_bank' => $totalBank,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  $userUnder->is_under_distributer ? $totalUnder : 0,
                    'description_underdistributer' => null,
                ], [$walletColumn => $walletId]));
            }
        }
    }

    public function distributeAdminAndBankCommissions($adminCiv, $walletColumn, $walletId, $totalCommission, $amountBank)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }


        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $totalCommission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'amount_bank' => $amountBank,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  0,
                    'description_underdistributer' => null,
                ], [$walletColumn => $walletId]));
            }
        }
    }

    public function distributeAdminPaimentCommissions($adminCiv, $walletColumn, $walletId, $totalCommission, $banquecommission)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }


        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $totalCommission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);
                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'amount_cie' => $banquecommission,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  0,
                    'description_underdistributer' => null,
                ], [$walletColumn => $walletId]));
            }
        }
    }

    public function distributeAdminNoCompagniePaimentCommissions($adminCiv, $walletColumn, $walletId, $commissionAdmin, $undercommission, $amountBank)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();

        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $commissionAdmin / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);
                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'amount_cie' => $userUnder->is_under_distributer ? $amountBank : 0,
                    'description' => 'Commission administrateur',
                    'amount_under' => $userUnder->is_under_distributer ? $undercommission : 0,
                    'description_underdistributer' => null,
                ], [$walletColumn => $walletId]));
            }
        }
    }

     public function distributeAdminNoCompagnieBankSendCommissions($adminCiv, $walletColumn, $walletId, $commissionAdmin, $undercommission, $amountBank)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();

        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $commissionAdmin / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);
                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'amount_bank' => $userUnder->is_under_distributer ? $amountBank : 0,
                    'description' => 'Commission administrateur',
                    'amount_under' => $userUnder->is_under_distributer ? $undercommission : 0,
                    'description_underdistributer' => null,
                ], [$walletColumn => $walletId]));
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
            $walletAdmin->increment('amount', $amount);
        } else {
            $admin_single->wallet->increment('amount', $amount);
        }
    }

      public function processAdminCommissionSInterDistributerAndComp($userId, $walletTransactionId, $amount, $valuer_deposit, $amountCompagie)
    {


        $user = auth_user();

        $admin_single = SidUser::find($userId);

        AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'wallet_inter_id' => $walletTransactionId,
            'description' => 'Commission administrateur',
            'status'  => "SUCCESS",
            "amount_compagnie" => $amountCompagie ?? 0,
            'amount_under' =>  $user->is_under_distributer ? $valuer_deposit : 0,
            'description_underdistributer' =>  $user->is_under_distributer ? 'Paiement commission sous-distributer' : null
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

    public function processAdminDrawalCommissionSDistributer($userId, $walletTransactionId, $amount, $valuer_deposit)
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
            $walletAdmin->increment('amount', $amount);
        } else {
            $admin_single->wallet->increment('amount', $amount);
        }
    }

      public function processAdminDrawalInterCommissionSDistributer($userId, $walletTransactionId, $amount, $valuer_deposit)
    {

        $user = auth_user();

        $admin_single = SidUser::find($userId);

        AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'wallet_inter_id' => $walletTransactionId,
            'description' => 'Commission administrateur',
            'status'  => "SUCCESS",
            "amount_compagnie" => 0,
            'amount_under' =>  $user->is_under_distributer ? $valuer_deposit : 0,
            'description_underdistributer' =>  $user->is_under_distributer ? 'Paiement commission sous-distributer' : null
        ]);

        $walletCommission = WalletCommission::find(1);
        $walletAdmin = WalletAdmin::find(1);
        if ($walletCommission && $walletCommission->amount >= $amount) {
            $walletAdmin->increment('amount', $amount);
        } else {
            $admin_single->wallet->increment('amount', $amount);
        }
    }

    public function distributeAdminBankCommissionAccepteds($adminCiv, string $walletColumn, $walletId, $totalCommission, $amountDeposit, $amountBank = null)
    {


        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();

        $all_commission =  (float) $totalCommission +  (float) $amountDeposit;

        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $all_commission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'amount_bank' => $amountBank,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  $userUnder->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' =>  $userUnder->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ], [$walletColumn => $walletId]));
            }
        }
    }

    public function distributeAdminCommissionAccepteds($adminCiv, string $walletColumn, $walletId, $totalCommission, $amountDeposit)
    {


        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();


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
            }
        }
    }

    
    public function distributeAdminVirementCommissionAccepteds($adminCiv, string $walletColumn, $walletId, $amountDeposit)
    {


        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();


        if ($adminCiv->count() > 0) {

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => 0,
                    'sid_user_id' => $adminUser->id,
                    'description' => $userUnder->is_under_distributer ? 'Sans Commission administrateur' :  'Sans Commission administrateur' ,
                    'amount_under' =>  $userUnder->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' =>  $userUnder->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ], [$walletColumn => $walletId]));
            }
        }
    }


    public function distributeAdminCommissionNoAccepteds($adminCiv, string $walletColumn, $walletId, $amountDeposit)
    {


        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();

        if ($adminCiv->count() > 0) {

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => 0,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'sans Commission administrateur',
                    'amount_under' =>  $userUnder->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' =>  $userUnder->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ], [$walletColumn => $walletId]));
            }
        }
    }

    public function distributeAdminCommissionNoCompagnies($adminCiv, string $walletColumn, $walletId, $totalCommission, $amountDeposit)
    {


        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();

        $all_commission =  (float) $totalCommission;

        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $all_commission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  $userUnder->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' =>  $userUnder->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ], [$walletColumn => $walletId]));
            }
        }
    }

    public function distributeAdminNoCommissionCompagnies($adminCiv, string $walletColumn, $walletId, $amountDeposit)
    {


        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();


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
            }
        }
    }

     public function distributeAdminNoCommissionInterCompagnies($adminCiv, string $walletColumn, $walletId, $amountAdmin, $amountDeposit)
    {


        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();


        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $amountAdmin / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  $userUnder->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' =>  $userUnder->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ], [$walletColumn => $walletId]));
            }
        }
    }


    public function distributeAdminAndBankCommissionAccepteds($adminCiv, string $walletColumn, $walletId, $totalCommission, $amountDeposit, $amountBank)
    {


        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();


        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $totalCommission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'amount_bank' => $amountBank,
                    'sid_user_id' => $adminUser->id,
                    'description' => $userUnder->is_under_distributer  ? 'Commission  sous-distributer' : 'Commission administrateur',
                    'amount_under' =>  $userUnder->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' =>  $userUnder->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ], [$walletColumn => $walletId]));
            }
        }
    }


    public function distributeAdminCommissionUnders($adminCiv, string $walletColumn, $walletId, $amountDeposit)
    {


        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = auth_user();

        if ($adminCiv->count() > 0) {

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => 0 ,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  $userUnder->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' =>  $userUnder->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ], [$walletColumn => $walletId]));
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
