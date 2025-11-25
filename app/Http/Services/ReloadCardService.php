<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\DB;
use App\Repositories\ReloadCardRepository;
use App\Http\Services\OperatorRestrictionService;
use App\Models\Wallets\WalletCommission;
use App\Models\Wallets\WalletAdmin;

class ReloadCardService
{

    /** @var  ReloadCardRepository */
    public $reloadCardRepository;
    // private $robusMasterTransaction;
    protected $operatorRestrictionService;
    protected $commissionServices;

    public function __construct(
        ReloadCardRepository $reloadCardReposi,
        // MasterService $robusMasterTransaction
        OperatorRestrictionService $operatorRestrictionService,
        CommissionService $commissionServices

    ) {
        $this->reloadCardRepository = $reloadCardReposi;
        // $this->robusMasterTransaction = $robusMasterTransaction;
        $this->operatorRestrictionService = $operatorRestrictionService;
        $this->commissionServices = $commissionServices;
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
        if ($company_id) {
            $compagnie = SidUser::whereIsCompany(true)->where('company_id', $company_id)->first();
            $sidUserId = $account_accepted && $compagnie ? $compagnie->id : null;
        } else {
            $compagnie = null;
            $sidUserId = null;
        }

        if ($sidUserId) {
            $activeCButtons = $this->operatorRestrictionService->getActiveCButtons($sidUserId);
        } else {
            $activeCButtons = [];
        }

        $walletOrderCard->update([
            'amount_solde' => $amount_solde,
        ]);

        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_verify),
        ]);


        if (robus()->is_under_distributer || robus()->is_api) {

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
                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ, 'wallet_deposit_id', $walletOrderCard->id, $valuer_companie, $valuer_deposit);
                } else {
                }
            } else {

                $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_deposit_id', $walletOrderCard->id, $admin_valeur_no);
            }
        } else {

            $admin_valeur =  $amount_commission;

            $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_deposit_id', $walletOrderCard->id, $admin_valeur);
        }

        return true;
    }
}
