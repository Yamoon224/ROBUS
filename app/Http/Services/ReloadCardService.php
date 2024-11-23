<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\DB;
use App\Repositories\ReloadCardRepository;
use App\Http\Services\MasterService;
use App\Http\Services\OperatorRestrictionService;

class ReloadCardService
{

    /** @var  ReloadCardRepository */
    public $reloadCardRepository;
    // private $robusMasterTransaction;
    protected $operatorRestrictionService;

    public function __construct(
        ReloadCardRepository $reloadCardReposi,
        // MasterService $robusMasterTransaction
        OperatorRestrictionService $operatorRestrictionService,

    ) {
        $this->reloadCardRepository = $reloadCardReposi;
        // $this->robusMasterTransaction = $robusMasterTransaction;
        $this->operatorRestrictionService = $operatorRestrictionService;
    }


    public function createRobusWalletReloadCard($amount_valeur, $provider, $percent_robus, $amount_cost, $num_carte, $name, $amountReceved)
    {

        $amount_solde = robus()->wallet->amount;

        return  $this->reloadCardRepository->create([
            'amount_solde' => $amount_solde ?? null,
            'wallet_id' =>  robus()->wallet->id,
            'amount' => $amount_valeur,
            'description'  => $description ?? 'Demande de rechargement de carte',
            'num_carte'  => $num_carte,
            'name'  => $name,
            'deposit_cost_id' => $percent_robus->id,
            'costs_amount' =>  $amount_cost,
            'created_by' => robus()->id,
            'provider_id'  =>  $provider,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
        ]);
    }


    public function updateamountReloadCardReceveid($admin_civ, $percent_merchands, $walletOrderCard, $amount_commission, $amount_verify, $commissionCompanie)
    {

        $amount_solde = robus()->wallet->amount - $amount_verify;

        // $master_id = $this->robusMasterTransaction->mastersId();
        $userId = robus()->id;
        $activeButtons = $this->operatorRestrictionService->getActiveButtons($userId);
        $account_accepted = robus()->is_accepted_companie ?? false;
        $company_id = robus()->company_id ?? null;
        $compagnie = SidUser::whereIsCompany(true)->where('company_id', $company_id)->first();
        $activeCButtons = $this->operatorRestrictionService->getActiveCButtons(($account_accepted && $compagnie->id) ? $compagnie->id : null);

        $walletOrderCard->update([
            'amount_solde' => $amount_solde,
        ]);

        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_verify),
        ]);


        if (robus()->is_under_distributer) {

            $valuer_deposit =  $percent_merchands;

            $admin_valeur =  $valuer_deposit  + $commissionCompanie;

            $admin_valeur_no =  $amount_commission;

            if (!empty($activeButtons['showRemonterRechargementButton'])) {

                $walletOrderCard->update([
                    'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
                ]);

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterRechargementCButton'])) {
                    $valuer_companie = $commissionCompanie;
                    $walletOrderCard->increment('commission_companie', $valuer_companie);
                }

                if ($admin_civ->count() > 0) {

                    $count_admin = $admin_civ->count();
                    $valeur_single = $admin_valeur / $count_admin;

                    foreach ($admin_civ as $user) {
                        $admin_single = SidUser::find($user->id);

                        AdminsCom::create([
                            'amount' => $valeur_single,
                            'sid_user_id' => $admin_single->id,
                            'wallet_deposit_id' =>  $walletOrderCard->id,
                            'description' => 'Commission administrateur',
                            'amount_under' =>  robus()->is_under_distributer ? $valuer_deposit : 0,
                            'description_underdistributer' =>  robus()->is_under_distributer ? 'Paiement commission sous-distributer' : null
                        ]);
                    }
                }
            } else {

                $this->distributeAdminCommissions($admin_civ, $walletOrderCard, $admin_valeur_no);
            }

            // $walletOrderCard->update([
            //     'commission_masters' => DB::raw('commission_masters+'  .  $amount_master),
            // ]);

        } else {

            $admin_valeur =  $amount_commission;

            // $walletOrderCard->update([
            //     'commission_masters' => DB::raw('commission_masters+'  .  $amount_master),
            // ]);

            $this->distributeAdminCommissions($admin_civ, $walletOrderCard, $admin_valeur);
        }

        return true;
    }

    private function distributeAdminCommissions($adminCiv, $walletOrderCard, $totalCommission)
    {
        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $totalCommission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'wallet_deposit_id' => $walletOrderCard->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  0,
                    'description_underdistributer' => null,
                ]);
            }
        }
    }
}
