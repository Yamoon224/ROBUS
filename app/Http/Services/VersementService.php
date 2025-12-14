<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\DB;
use App\Repositories\VersementRepository;
use App\Http\Services\OperatorRestrictionService;
use App\Http\Services\CommissionService;

class VersementService
{

    /** @var  VersementRepository */
    public $versementRepository;
    protected $operatorRestrictionService;
    protected $commissionServices;

    public function __construct(
        VersementRepository $paiementReposi,
        OperatorRestrictionService $operatorRestrictionService,
        CommissionService $commissionServices

    ) {
        $this->versementRepository = $paiementReposi;
        $this->operatorRestrictionService = $operatorRestrictionService;
        $this->commissionServices = $commissionServices;
    }


    public function createRobusWalletTransactionPayment(
        $reel_amount,
        $valeur_received,
        $amount_valeur,
        $provider,
        $percent_robus,
        $number_phone,
        $number_decoder,
        $id_invoice,
        $number_contract
    ) {

        $amount_solde = robus()->wallet->amount - $amount_valeur;

        return $this->versementRepository->create([
            'wallet_id' =>  robus()->wallet->id,
            'amount' => $reel_amount,
            'amount_solde' => $amount_solde ?? null,
            'amount_received' => $valeur_received ?? null,
            'description'  => "Paiement versements",
            'virement_percent_id' => $percent_robus->id,
            'num_accompte'   => $number_decoder ?? null,
            'bank_code' => $id_invoice ?? null,
            'guichet_code' => $number_contract ?? null,
            // 'name' => $name ?? null,
            'created_by' => robus()->id,
            'provider_id'  =>    $provider,
            'num_authers' => $number_phone ?? null,
            'costs_amount' => $percent_robus->amount_auther ?? null,
            'status'  => "PENDING",
        ]);
    }

    public function updateAmountPaymentReceveid($admin_civ, $percent_robus, $walletPayment, $amount)
    {

        $amount_commission =  $amount;

        $amount_solde = robus()->wallet->amount - $amount;
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

        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);

        $walletPayment->update([
            'amount_solde' => $amount_solde,
        ]);

        if (robus()->is_under_distributer || robus()->is_api) {

            $valuer_deposit =  $percent_robus->percent_merchands * $walletPayment->amount;
            $admin_nocomission =  $percent_robus->percent * $walletPayment->amount;

            if (!empty($activeButtons['showRemonterVersementButton'])) {

                $walletPayment->update([
                    'commissions' => $valuer_deposit,
                ]);

                $admin_valeur =  $percent_robus->percent_admin  * $walletPayment->amount;

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterVersementCButton'])) {


                    $valuer_companie = $percent_robus->percent_companie * $walletPayment->amount;

                    $compagnie_amount =  $valuer_companie +  $valuer_deposit;

                    $walletPayment->increment('commission_companie', $valuer_companie);

                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ, 'wallet_versement_id', $walletPayment->id, $compagnie_amount, $valuer_deposit);
                } else {
                    $this->commissionServices->distributeAdminCommissionAccepteds($admin_civ, 'wallet_versement_id', $walletPayment->id, $admin_valeur, $valuer_deposit);
                }
            } else {

                $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_versement_id', $walletPayment->id, $admin_nocomission);
            }
        } else {

            $admin_valeur =  (float)  $percent_robus->amount_auther;
            $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_versement_id', $walletPayment->id, $admin_valeur);
        }

        return true;
    }
}
