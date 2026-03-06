<?php

namespace App\Http\Services;

use App\Models\Sid\SidUser;
use App\Models\AdminsCom;
use App\Models\Wallets\WalletCommission;
use App\Models\Wallets\WalletAdmin;
use Illuminate\Support\Facades\DB;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\LedgerEntry;
use App\Models\Costs\BankCost;

class CommissionServiceBackend
{
    /**
     * Distribuer la commission aux admins pour les compagnies acceptées
     */

    public function debit($walletId, float $amount, string $description,  bool $isAdmin = false,  bool $isCommission = false)
    {
        return LedgerEntry::create([
            'transaction_id' => (string) Str::uuid(),
            'admin_com_id' => $walletId,
            'debit' => $amount,
            'credit' => 0,
            'description' => $description,
            'is_admin' => $isAdmin,
            'is_comission' => $isCommission,
        ]);
    }

    public function credit($walletId, float $amount, string $description,  bool $isAdmin = false, bool $isCommission = false)
    {
        return LedgerEntry::create([
            'transaction_id' => (string) Str::uuid(),
            'admin_com_id' => $walletId,
            'debit' => 0,
            'credit' => $amount,
            'description' => $description,
            'is_admin' => $isAdmin,
            'is_comission' => $isCommission,
        ]);
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

    public function distributeAdminAndBankCommissionAccepteds($adminCiv, string $walletColumn, $walletId, $totalCommission, $amountDeposit, $amountBank)
    {


        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = sabdy();


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

    public function distributeAdminAndVersementCommissionAcceptedCompanies($adminCiv, string $walletColumn, $walletId, $admin_amount, $totalCompanie, $amountDeposit, $amountBank)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = sabdy();

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

    public function distributeAdminCommissionXbetNoCompanies($adminCiv, string $walletColumn, $walletId, $admin_valeur, $amountDeposit)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = sabdy();

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

    public function distributeAdminCommissionAcceptedXbetCompanies($adminCiv, string $walletColumn, $walletId, $admin_valeur, $totalCommission, $amountDeposit)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = sabdy();

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

    public function distributeAdminCommissionIntersAcceptedCompanies($adminCiv, string $walletColumn, $walletId, $totalCommission, $totalCommissionCompany, $amountDeposit)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = sabdy();

        if ($adminCiv->count() > 0) {

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $totalCommission,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_compagnie' => $userUnder->is_under_distributer ? $totalCommissionCompany : 0,
                    'amount_under' => $userUnder->is_under_distributer ? $amountDeposit : 0,
                    'is_under' => true,
                    'description_underdistributer' => $userUnder->is_under_distributer ? 'Paiement commission sous-distributer' : null,
                ], [$walletColumn => $walletId]));
            }
        }
    }

    public function getRobusPercentageRelaodCard()
    {
        return BankCost::whereId(1)->first();
    }

    public function distributeAdminPaiementCommissions($adminCiv, $walletColumn, $walletId, $totalCommission)
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

    public function distributeAdminPaymentCommissions($adminCiv, $walletColumn, $walletId, $totalCommission)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = sabdy();

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

    public function distributeAdminNoCompagniePaimentCommissions($adminCiv, $walletColumn, $walletId, $commissionAdmin, $undercommission, $amountBank)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = sabdy();

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

    public function distributeAdminCommissionUnderBankAcceptedCompanies($adminCiv, string $walletColumn, $walletId, $valuer_admin_compagnie, $totalCommission, $amountDeposit, $amountBank = null)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = sabdy();

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

    public function distributeAdminCommissionUnders($adminCiv, string $walletColumn, $walletId, $amountDeposit)
    {


        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = sabdy();

        if ($adminCiv->count() > 0) {

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => 0,
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

        $userUnder = sabdy();


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

    public function distributeUnderCommissionAcceptedCompanies($adminCiv, string $walletColumn, $walletId, $totalCommission, $amountDeposit)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $userUnder = sabdy();

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


    public function handleAdminCommission($check, $admin_civ)
    {
        $amountValeur = $check->adminCom->amount;

        $walletAdmin = WalletAdmin::find(1);

        if ($admin_civ->count() > 0) {

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $check->adminCom->update([
                    'amount' => $amountValeur,
                    'sid_user_id' => $admin_single->id,
                    'description' => 'Commission administrateur',
                    'status' => "SUCCESS",
                ]);

                if ($walletAdmin != null) {
                    $walletAdmin->increment('amount', $amountValeur);
                    $this->credit(
                        $check->adminCom->id,
                        $amountValeur,
                        'Commission administrateur',
                        true,
                        false
                    );
                } else {
                    $admin_single->wallet->update([
                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                        'sid_user_relod' => Auth::guard('web')->user()->id,
                        'amount' => DB::raw('amount+' . $amountValeur),
                    ]);
                }
            }
        }
    }

    public function handleAdminBankCommission($check, $admin_civ)
    {
        $amountValeur = $check->adminCom->amount ?? 0;
        $amountBank = $check->adminCom->amount_bank ?? 0;
        $walletAdmin = WalletAdmin::find(1);
        $walletCommission = WalletCommission::find(1);

        if ($admin_civ->count() > 0) {

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $check->adminCom->update([
                    'amount' => $amountValeur,
                    'sid_user_id' => $admin_single->id,
                    'description' => 'Commission administrateur',
                    'status' => "SUCCESS",
                ]);

                if ($walletAdmin && $walletCommission) {
                    $this->credit(
                        $check->adminCom->id,
                        $amountValeur,
                        'Commission administrateur',
                        true,
                        false
                    );
                    $walletAdmin->increment('amount', $amountValeur);
                    $walletCommission->increment('amount', $amountBank);

                    $this->credit(
                        $check->adminCom->id,
                        $amountBank,
                        'Commission banque',
                        false,
                        true
                    );
                } else {
                    $admin_single->wallet->update([
                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                        'sid_user_relod' => Auth::guard('web')->user()->id,
                        'amount' => DB::raw('amount+' . $amountValeur),
                    ]);
                }
            }
        }
    }


    public function handleAdminBankSendCommission($check, $admin_civ)
    {
        $amountValeur = $check->adminCom->amount ?? 0;
        $amountBank = $check->adminCom->amount_bank ?? 0;
        $walletAdmin = WalletAdmin::find(1);
        $walletCommission = WalletCommission::find(1);

        if ($admin_civ->count() > 0) {

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $check->adminCom->update([
                    'amount' => $amountValeur,
                    'sid_user_id' => $admin_single->id,
                    'description' => 'Commission administrateur',
                    'status' => "SUCCESS",
                ]);

                if ($walletAdmin && $walletCommission) {
                    $this->credit(
                        $check->adminCom->id,
                        $amountValeur,
                        'Commission administrateur',
                        true,
                        false
                    );
                    $walletAdmin->increment('amount', $amountValeur);
                    $walletCommission->increment('amount', $amountBank);
                    $this->credit(
                        $check->adminCom->id,
                        $amountBank,
                        'Commission banque',
                        false,
                        true
                    );
                } else {
                    $admin_single->wallet->update([
                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                        'sid_user_relod' => Auth::guard('web')->user()->id,
                        'amount' => DB::raw('amount+' . $amountValeur),
                    ]);
                }
            }
        }
    }

    public function handleAdminBankSendUnderCommission($check, $admin_civ)
    {
        $amountValeur = $check->adminCom->amount ?? 0;
        $amountBank = $check->adminCom->amount_bank ?? 0;
        $walletAdmin = WalletAdmin::find(1);
        $walletCommission = WalletCommission::find(1);

        if ($admin_civ->count() > 0) {

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $check->adminCom->update([
                    'amount' => $amountValeur,
                    'sid_user_id' => $admin_single->id,
                    'description' => 'Commission administrateur',
                    'status' => "SUCCESS",
                ]);

                if ($walletAdmin && $walletCommission) {
                    $this->credit(
                        $check->adminCom->id,
                        $amountValeur,
                        'Commission administrateur',
                        true,
                        false
                    );
                    $walletAdmin->increment('amount', $amountValeur);
                    $walletCommission->increment('amount', $amountBank);
                    $this->credit(
                        $check->adminCom->id,
                        $amountBank,
                        'Commission banque',
                        false,
                        true
                    );
                } else {
                    $admin_single->wallet->update([
                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                        'sid_user_relod' => Auth::guard('web')->user()->id,
                        'amount' => DB::raw('amount+' . $amountValeur),
                    ]);
                }
            }
        }
    }

    public function handleAdminPaimentCommission($check, $admin_civ)
    {
        $amountValeur = $check->adminCom->amount;
        $amountBanque = $check->adminCom->amount_cie;
        $amount_amount =  $check->adminCom->amount;

        $walletAdmin = WalletAdmin::find(1);
        $walletAdminCommission = WalletCommission::find(1);

        if ($admin_civ->count() > 0) {

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $check->adminCom->update([
                    'amount' => $amountValeur,
                    'sid_user_id' => $admin_single->id,
                    'description' => 'Commission administrateur',
                    'status' => "SUCCESS",
                ]);

                if ($walletAdmin != null) {
                    $this->credit(
                        $check->adminCom->id,
                        $amountValeur,
                        'Commission administrateur',
                        true,
                        false
                    );
                    $walletAdmin->increment('amount', $amount_amount);
                    $walletAdminCommission->increment('amount', $amountBanque);
                    $this->credit(
                        $check->adminCom->id,
                        $amountBanque,
                        'Commission banque',
                        false,
                        true
                    );
                } else {
                    $admin_single->wallet->update([
                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                        'sid_user_relod' => Auth::guard('web')->user()->id,
                        'amount' => DB::raw('amount+' . $amountValeur),
                    ]);
                }
            }
        }
    }

    public function handleUnderDistributorCommission($check, $admin_civ, $description)
    {
        $valeur_amount_com = (float)$check->adminCom->amount_compagnie + (float)$check->adminCom->amount_under;
        $admin_amount = (float)$check->adminCom->amount;
        $walletCommission = WalletCommission::find(1);
        $walletAdmin = WalletAdmin::find(1);

        if ($admin_civ->count() > 0) {

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $check->adminCom->update([
                    'amount' =>   0,
                    'sid_user_id' =>   $admin_single->id,
                    'description' => $description,
                    'status'  => "SUCCESS",
                    'amount_under' =>  $check->adminCom->amount_under,
                    'description_underdistributer' =>  $description
                ]);

                if ($walletCommission && $walletCommission->amount >= $valeur_amount_com) {
                    $this->debit(
                        $check->adminCom->id,
                        $valeur_amount_com,
                        'Commission banque',
                        false,
                        true
                    );
                    $walletCommission->decrement('amount', $valeur_amount_com);
                    $walletAdmin->increment('amount', $admin_amount);
                    $this->credit(
                        $check->adminCom->id,
                        $admin_amount,
                        'Commission administrateur',
                        true,
                        false
                    );
                } else {
                    $admin_single->wallet->decrement('amount', $valeur_amount_com);
                    $admin_single->wallet->increment('amount', $admin_amount);
                }
            }
        }
    }

    public function handleUnderDistributorPhoneCreditCommission($check, $admin_civ, $description)
    {
        $valeur_amount_com = (float)$check->adminCom->amount_compagnie + (float)$check->adminCom->amount_under;
        $walletCommission = WalletCommission::find(1);

        if ($admin_civ->count() > 0) {

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $check->adminCom->update([
                    'amount' =>   0,
                    'sid_user_id' =>   $admin_single->id,
                    'description' => $description,
                    'status'  => "SUCCESS",
                    'amount_under' =>  $check->adminCom->amount_under,
                    'description_underdistributer' =>  $description
                ]);

                if ($walletCommission && $walletCommission->amount >= $valeur_amount_com) {
                    $walletCommission->decrement('amount', $valeur_amount_com);
                    $this->debit(
                        $check->adminCom->id,
                        $valeur_amount_com,
                        'Commission banque',
                        false,
                        true
                    );
                } else {
                    $admin_single->wallet->decrement('amount', $valeur_amount_com);
                }
            }
        }
    }

      public function handleUnderDistributorInterPhoneCreditCommission($check, $admin_civ, $description)
    {
        $valeur_amount_com = (float)$check->adminCom->amount;
        $walletAdmin = WalletAdmin::find(1);

        if ($admin_civ->count() > 0) {

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $check->adminCom->update([
                    'amount' =>   $valeur_amount_com,
                    'sid_user_id' =>   $admin_single->id,
                    'description' => $description,
                    'status'  => "SUCCESS",
                    'amount_under' =>  $check->adminCom->amount_under,
                    'description_underdistributer' =>  $description
                ]);

                if ($walletAdmin && $walletAdmin->amount >= $valeur_amount_com) {
                    $walletAdmin->increment('amount', $valeur_amount_com);
                    $this->credit(
                        $check->adminCom->id,
                        $valeur_amount_com,
                        'Commission administrateur',
                        true,
                        false
                    );
                } else {
                    $admin_single->wallet->increment('amount', $valeur_amount_com);
                }
            }
        }
    }

    public function handleUnderDistributorXbetCommission($check, $admin_civ, $description)
    {
        $admin_amount = (float)$check->adminCom->amount;
        $walletAdmin = WalletAdmin::find(1);

        if ($admin_civ->count() > 0) {

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $check->adminCom->update([
                    'amount' =>   $admin_amount,
                    'sid_user_id' =>   $admin_single->id,
                    'description' => $description,
                    'status'  => "SUCCESS",
                    'amount_under' =>  $check->adminCom->amount_under,
                    'description_underdistributer' =>  $description
                ]);

                if ($walletAdmin && $walletAdmin->amount >= $admin_amount) {
                    $walletAdmin->increment('amount', $admin_amount);
                    $this->credit(
                        $check->adminCom->id,
                        $admin_amount,
                        'Commission administrateur',
                        true,
                        false
                    );
                } else {
                    $admin_single->wallet->increment('amount', $admin_amount);
                }
            }
        }
    }


    public function handleUnderDistributorAndCompanieCommission($check, $admin_civ, $description)
    {
        $valeur_amount_com = (float) $check->adminCom->amount_under + (float)$check->adminCom->amount_compagnie;
        $walletCommission = WalletCommission::find(1);

        if ($admin_civ->count() > 0) {

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $check->adminCom->update([
                    'amount' =>   0,
                    'sid_user_id' =>   $admin_single->id,
                    'description' => $description,
                    'status'  => "SUCCESS",
                    'amount_under' => $check->adminCom->amount_under,
                    'amount_compagnie' => $check->adminCom->amount_compagnie,
                    'description_underdistributer' =>  $description
                ]);

                if ($walletCommission && $walletCommission->amount >= $valeur_amount_com) {
                    $walletCommission->decrement('amount', $valeur_amount_com);

                    $this->debit(
                        $check->adminCom->id,
                        $valeur_amount_com,
                        'Commission banque',
                        false,
                        true
                    );
                } else {
                    $admin_single->wallet->decrement('amount', $valeur_amount_com);
                }
            }
        }
    }

    public function handleUnderDistributorVirementInterCompanieCommission($check, $admin_civ, $description)
    {
        $valeur_amount_com = (float) $check->adminCom->amount;
        $walletAdmin = WalletAdmin::find(1);

        if ($admin_civ->count() > 0) {

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $check->adminCom->update([
                    'amount' =>   $valeur_amount_com,
                    'sid_user_id' =>   $admin_single->id,
                    'description' => $description,
                    'status'  => "SUCCESS",
                    'amount_under' => $check->adminCom->amount_under ?? 0,
                    'amount_compagnie' => $check->adminCom->amount_compagnie ?? 0,
                    'description_underdistributer' =>  $description
                ]);

                if ($walletAdmin && $walletAdmin->amount >= $valeur_amount_com) {
                    $walletAdmin->increment('amount', $valeur_amount_com);
                    $this->credit(
                        $check->adminCom->id,
                        $valeur_amount_com,
                        'Commission administrateur',
                        true,
                        false
                    );
                } else {
                    $admin_single->wallet->increment('amount', $valeur_amount_com);
                }
            }
        }
    }

    public function handleUnderDistributorPaiementCommission($check, $admin_civ, $description)
    {
        $amount_admin = (float) $check->adminCom->amount;
        $amount_invoice = (float)$check->adminCom->amount_under + (float)$check->adminCom->amount_compagnie;
        $amount_cie_invoice = (float) $check->adminCom->amount_cie;
        $amount_offer = (float) $check->adminCom->amount;
        $payment_seach = $check->adminCom->walletPayment->provider_id;
        $walletCommission = WalletCommission::find(1);
        $walletAdmin = WalletAdmin::find(1);

        if ($admin_civ->count() > 0) {

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $check->adminCom->update([
                    'amount' => $payment_seach == 7 ?  $amount_offer  : $amount_admin,
                    'sid_user_id' =>   $admin_single->id,
                    'description' => $description,
                    'status'  => "SUCCESS",
                    'amount_under' =>  $amount_invoice,
                    'description_underdistributer' =>  $description
                ]);

                if ($payment_seach == 7) {
                    if ($walletAdmin && $walletAdmin->amount >= $amount_offer) {
                        $walletAdmin->increment('amount', $amount_offer);
                        $this->credit(
                            $check->adminCom->id,
                            $amount_offer,
                            'Commission administrateur',
                            true,
                            false
                        );
                    }
                } else {

                    if ($walletCommission && $walletCommission->amount >= $amount_invoice) {
                        // $walletCommission->decrement('amount', $amount_invoice);
                        $walletCommission->increment('amount', $amount_cie_invoice);
                        $this->credit(
                            $check->adminCom->id,
                            $amount_cie_invoice,
                            'Commission banque',
                            false,
                            true
                        );
                        $walletAdmin->increment('amount', $amount_admin);
                        $this->credit(
                            $check->adminCom->id,
                            $amount_admin,
                            'Commission administrateur',
                            true,
                            false
                        );
                    } else {
                        $admin_single->wallet->increment('amount', $amount_invoice);
                        $walletAdmin->increment('amount', $amount_admin);
                    }
                }
            }
        }
    }

      public function handleUnderDistributorInterPaiementCommission($check, $admin_civ, $description)
    {
        $amount_admin = (float) $check->adminCom->amount;
        $amount_invoice = (float)$check->adminCom->amount_under + (float)$check->adminCom->amount_compagnie;
        $amount_cie_invoice = (float) $check->adminCom->amount_cie;
        $amount_offer = (float) $check->adminCom->amount;
        $payment_seach = $check->adminCom->walletInter->provider_id;
        $walletCommission = WalletCommission::find(1);
        $walletAdmin = WalletAdmin::find(1);

        if ($admin_civ->count() > 0) {

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $check->adminCom->update([
                    'amount' => $amount_admin,
                    'sid_user_id' =>   $admin_single->id,
                    'description' => $description,
                    'status'  => "SUCCESS",
                    'amount_under' =>  $amount_invoice,
                    'description_underdistributer' =>  $description
                ]);

                if ($payment_seach == 52) {
                    if ($walletAdmin && $walletAdmin->amount >= $amount_offer) {
                        $walletAdmin->increment('amount', $amount_offer);
                        $this->credit(
                            $check->adminCom->id,
                            $amount_offer,
                            'Commission administrateur',
                            true,
                            false
                        );
                    }
                } else {

                    if ($walletCommission && $walletCommission->amount >= $amount_invoice) {
                        // $walletCommission->decrement('amount', $amount_invoice);
                        $walletCommission->increment('amount', $amount_cie_invoice);
                        $this->credit(
                            $check->adminCom->id,
                            $amount_cie_invoice,
                            'Commission banque',
                            false,
                            true
                        );
                        $walletAdmin->increment('amount', $amount_admin);
                        $this->credit(
                            $check->adminCom->id,
                            $amount_admin,
                            'Commission administrateur',
                            true,
                            false
                        );
                    } else {
                        $admin_single->wallet->increment('amount', $amount_invoice);
                        $walletAdmin->increment('amount', $amount_admin);
                    }
                }
            }
        }
    }

    public function handleUnderBankDistributorCommission($check, $admin_civ, $description)
    {

        $amount_admin = (float) $check->adminCom->amount;
        $amount_drawal = (float) $check->adminCom->amount_compagnie + (float)$check->adminCom->amount_under;
        $amount_banque = (float)$check->adminCom->amount_bank ?? 0;

        $walletCommission = WalletCommission::find(1);
        $walletAdmin = WalletAdmin::find(1);

        if ($admin_civ->count() > 0) {

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $check->adminCom->update([
                    'amount' =>   $amount_admin,
                    'sid_user_id' =>   $admin_single->id,
                    'description' => $description,
                    'status'  => "SUCCESS",
                    'amount_under' =>  $check->adminCom->amount_under,
                    'description_underdistributer' =>  $description
                ]);

                if ($walletAdmin && $walletAdmin->amount >= $amount_admin) {
                    $this->credit(
                        $check->adminCom->id,
                        $amount_admin,
                        'Commission administrateur',
                        true,
                        false
                    );
                    $walletCommission->increment('amount', $amount_banque);
                    $walletAdmin->increment('amount', $amount_admin);
                    $this->credit(
                        $check->adminCom->id,
                        $amount_admin,
                        'Commission banque',
                        false,
                        true
                    );
                } else {
                    $walletAdmin->increment('amount', $amount_admin);
                }
            }
        }
    }


    public function distributeAdminCommissionAcceptedCompanies($adminCiv, string $walletColumn, $walletId, $totalCommission, $amountDeposit)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        $walletCommission = WalletCommission::find(1);

        $walletAdmin = WalletAdmin::find(1);

        $walletAdmin->increment('amount', $amountDeposit);

        if ($adminCiv->count() > 0) {

            $commissionPerAdmin = $amountDeposit / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                $admin_amount =   AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' => sabdy()->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' => sabdy()->is_under_distributer ? 'Paiement commission sous-distributer' : null,
                ], [$walletColumn => $walletId]));

                if ($walletCommission && $walletCommission->amount >= $totalCommission) {
                    $walletCommission->decrement('amount', $totalCommission);
                    $this->debit(
                        $admin_amount->id,
                        $totalCommission,
                        'Commission banque',
                        false,
                        true
                    );
                } else {
                    $adminUser->wallet->decrement('amount', $totalCommission);
                }
            }
        }
    }

    public function distributeAdminNoCommissionAcceptedCompanies($adminCiv, string $walletColumn, $walletId, $totalCommission, $amountDeposit)
    {

        if ($adminCiv->isEmpty()) {
            return;
        }

        if ($adminCiv->count() > 0) {

            $amount_compagnie = (float) $totalCommission - (float)$amountDeposit;
            $commissionPerAdmin = $amountDeposit / $adminCiv->count();
            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' => sabdy()->is_under_distributer ? $amountDeposit : 0,
                    'amount_compagnie' => $amount_compagnie ?? 0,
                    'amount_total' => $totalCommission ?? 0,
                    'status'  => "PENDING",
                    'description_underdistributer' => sabdy()->is_under_distributer ? 'Paiement commission sous-distributer' : null,
                ], [$walletColumn => $walletId]));
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

                $admin_commission =  AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  0,
                    'description_underdistributer' => null,
                ], [$walletColumn => $walletId]));


                if ($walletAdmin && $walletAdmin->amount >= $totalCommission) {
                    $walletAdmin->increment('amount', $totalCommission);
                    $this->credit(
                        $admin_commission->id,
                        $totalCommission,
                        'Commission administrateur',
                        true,
                        false
                    );
                } else {
                    $adminUser->wallet->increment('amount', $totalCommission);
                }
            }
        }
    }

    public function distributeAdminNoCommissions($adminCiv, $walletColumn, $walletId, $totalCommission)
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
                    'status'  => "PENDING",
                    'description_underdistributer' => null,
                ], [$walletColumn => $walletId]));
            }
        }
    }

    public function adminCommissions($userId, $walletTransactionId, $amount)
    {


        $walletAdmin = WalletAdmin::find(1);

        $admin_single = SidUser::find($userId);

        $admin_commission = AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'status'  => "SUCCESS",
            'wallet_transaction_id' => $walletTransactionId,
            'description' => 'Commission administrateur',
        ]);


        if ($walletAdmin && $walletAdmin->amount >= $amount) {
            $walletAdmin->increment('amount', $amount);
            $this->credit(
                $admin_commission->id,
                $amount,
                'Commission administrateur',
                true,
                false
            );
        } else {
            $admin_single->wallet->increment('amount', $amount);
        }
    }

    public function processAdminCommission($userId, $walletTransactionId, $amount)
    {
        $admin_single = SidUser::find($userId);

        $admin_commission =  AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'status'  => "SUCCESS",
            'wallet_transaction_id' => $walletTransactionId,
            'description' => 'Commission administrateur',
        ]);

        $walletAdmin = WalletAdmin::find(1);
        if ($walletAdmin && $walletAdmin->amount >= $amount) {
            $walletAdmin->increment('amount', $amount);
            $this->credit(
                $admin_commission->id,
                $amount,
                'Commission administrateur',
                true,
                false
            );
        } else {
            $admin_single->wallet->increment('amount', $amount);
        }
    }

    public function processCommissionsCustumerRobus($transaction_amount_robus, $percent_robus, $walletTransaction, $admin_civ)
    {

        $commission =  $transaction_amount_robus;
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

        return true;
    }

    public function processAdminCommissionSDistributer($userId, $walletTransactionId, $amount, $valuer_deposit)
    {



        $admin_single = SidUser::find($userId);

        $admin_commission =  AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'wallet_transaction_id' => $walletTransactionId,
            'description' => 'Commission administrateur',
            'status'  => "SUCCESS",
            'amount_under' =>  sabdy()->is_under_distributer ? $valuer_deposit : 0,
            'description_underdistributer' =>  sabdy()->is_under_distributer ? 'Paiement commission sous-distributer' : null
        ]);

        $walletCommission = WalletCommission::find(1);
        $walletAdmin = WalletAdmin::find(1);
        if ($walletCommission && $walletCommission->amount >= $valuer_deposit) {
            $walletCommission->decrement('amount', $valuer_deposit);
            $this->debit(
                $admin_commission->id,
                $amount,
                'Commission banque',
                false,
                true
            );
        } else {
            $admin_single->wallet->decrement('amount', $valuer_deposit);
        }

        $walletAdmin->increment('amount', $amount);
        $this->credit(
            $admin_commission->id,
            $amount,
            'Commission administration',
            true,
            false
        );

        return true;
    }

    public function processAdminNoCommissionSDistributer($userId, $walletTransactionId, $amount, $valuer_deposit)
    {



        $admin_single = SidUser::find($userId);

        AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'wallet_transaction_id' => $walletTransactionId,
            'description' => 'Commission administrateur',
            'status'  => "SUCCESS",
            'amount_under' =>  sabdy()->is_under_distributer ? $valuer_deposit : 0,
            'description_underdistributer' =>  sabdy()->is_under_distributer ? 'Paiement commission sous-distributer' : null
        ]);

        $walletCommission = WalletCommission::find(1);
        $walletAdmin = WalletAdmin::find(1);
        if ($walletCommission && $walletCommission->amount >= $valuer_deposit) {
            $walletCommission->decrement('amount', $valuer_deposit);
        } else {
            $admin_single->wallet->decrement('amount', $valuer_deposit);
        }

        $walletAdmin->increment('amount', $amount);

        return true;
    }

    public function processAdminCommissionDrawalSDistributer($userId, $walletTransactionId, $amount, $valuer_deposit)
    {



        $admin_single = SidUser::find($userId);

        AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'wallet_transaction_id' => $walletTransactionId,
            'description' => 'Retrait d\'argent',
            'status'  => "SUCCESS",
            'amount_under' =>  sabdy()->is_under_distributer ? $valuer_deposit : 0,
            'description_underdistributer' =>  sabdy()->is_under_distributer ? 'Paiement commission sous-distributer' : null
        ]);

        $walletCommission = WalletCommission::find(1);

        if ($walletCommission && $walletCommission->amount >= $valuer_deposit) {
            $walletCommission->decrement('amount', $valuer_deposit);
        } else {
            $admin_single->wallet->decrement('amount', $valuer_deposit);
        }

        return true;
    }

    public function processAdminCommissionDrawalAdmin($userId, $walletTransactionId, $amount, $valuer_deposit)
    {



        $admin_single = SidUser::find($userId);

        AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'wallet_transaction_id' => $walletTransactionId,
            'description' => 'Retrait d\'argent depuis un SD ou Superviseur ou ADMIN',
            'status'  => "SUCCESS",
            'amount_under' =>   0,
            'description_underdistributer' =>  null
        ]);

        $walletAdmin = WalletAdmin::find(1);

        if ($walletAdmin && $walletAdmin->amount >= $valuer_deposit) {
            $walletAdmin->decrement('amount', $valuer_deposit);
        } else {
            $admin_single->wallet->decrement('amount', $valuer_deposit);
        }

        return true;
    }



    public function processAdminCommissionNoDistributer($userId, $walletTransactionId, $amount)
    {



        $admin_single = SidUser::find($userId);

        AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'wallet_transaction_id' => $walletTransactionId,
            'description' => 'Commission administrateur',
            'status'  => "SUCCESS",
            'amount_under' =>   0,
            'description_underdistributer' =>   null
        ]);

        $walletAdmin = WalletAdmin::find(1);
        if ($walletAdmin) {
            // $walletCommission->decrement('amount', $amount);
            $walletAdmin->increment('amount', $amount);
        } else {
            $admin_single->wallet->increment('amount', $amount);
        }
    }

    public function processAdminNoCommissionDistributer($userId, $walletTransactionId, $amount)
    {



        $admin_single = SidUser::find($userId);

        AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'wallet_transaction_id' => $walletTransactionId,
            'description' => 'Commission administrateur',
            'status'  => "SUCCESS",
            'amount_under' =>   0,
            'description_underdistributer' =>   null
        ]);

        // $walletAdmin = WalletAdmin::find(1);
        // if ($walletAdmin) {
        //     // $walletCommission->decrement('amount', $amount);
        //     $walletAdmin->increment('amount', $amount);
        // } else {
        //     $admin_single->wallet->increment('amount', $amount);
        // }
    }

    public function distributeAdminCommissionAccepteds($adminCiv, string $walletColumn, $walletId, $totalCommission, $amountDeposit)
    {


        if ($adminCiv->isEmpty()) {
            return;
        }


        $walletCommission = WalletCommission::find(1);
        $walletAdmin = WalletAdmin::find(1);
        $walletAdmin->increment('amount', $totalCommission);

        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $totalCommission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                $admin_commission =  AdminsCom::create(array_merge([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  sabdy()->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' =>  sabdy()->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ], [$walletColumn => $walletId]));

                if ($walletCommission && $walletCommission->amount >= $amountDeposit) {
                    $walletCommission->decrement('amount', $amountDeposit);
                    $this->debit(
                        $admin_commission->id,
                        $amountDeposit,
                        'Commission banque',
                        false,
                        true
                    );
                } else {
                    $adminUser->wallet->decrement('amount', $amountDeposit);
                }
            }
        }
    }

    public function distributeAdminNoCommissionAccepteds($adminCiv, string $walletColumn, $walletId, $totalCommission, $amountDeposit)
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
                    'sid_user_id' => $adminUser->id,
                    'description' => 'Commission administrateur',
                    'amount_compagnie' => 0,
                    'amount_total' => $totalCommission ?? 0,
                    'amount_under' =>  sabdy()->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' =>  sabdy()->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ], [$walletColumn => $walletId]));
            }
        }
    }

    public function convertAdminCommission($amount)
    {
        $country_civ = Country::find(107);
        $robus_currency = sabdy()->country->currencie->value;

        if ($country_civ->currencie->value > sabdy()->country->currencie->value) {
            return $amount * $robus_currency;
        } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {
            return $amount;
        } else {
            return $amount * $robus_currency;
        }
    }
}
