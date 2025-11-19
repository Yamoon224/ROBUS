<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\DB;
use App\Repositories\djamoRepository;
use App\Http\Services\OperatorRestrictionService;
use App\Http\Services\CommissionService;

class DjamoService
{

    /** @var  DjamoRepository */
    public $djamoRepository;
    protected $operatorRestrictionService;
    protected $commissionServices;

    public function __construct(
        DjamoRepository $djamoReposi,
        OperatorRestrictionService $operatorRestrictionService,
        CommissionService $commissionServices
    ) {
        $this->djamoRepository = $djamoReposi;
        $this->operatorRestrictionService = $operatorRestrictionService;
        $this->commissionServices = $commissionServices;
    }


    public function createRobusWalletTransactionDjamo($amount_valeur, $provider, $percent_robus, $phone , $fullName , $savedImage, $amountReceved)
    {

        $amount_solde = robus()->wallet->amount - $amount_valeur;

        return $this->djamoRepository->create([
            'wallet_id' =>   robus()->wallet->id,
            'amount' => $amount_valeur,
            'description'  => 'Demande de rechargement Djamo',
            'djamo_percent_id' => 1,
            'created_by' => robus()->id,
            'amount_solde'  => $amount_solde ?? null,
            'provider_id'  =>  $provider,
            'image'  =>  $savedImage ?? null,
             'full_name' =>  $fullName ?? null,
            'costs_amount' => ($percent_robus->percent * $amount_valeur),
            'phone'  =>  robus()->country->indecative . $phone,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
        ]);
    }

    public function updateamountDjamoReceveid($admin_civ, $percent_robus, $walletPhone, $amount)
    {

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

        $amount_commission = robus()->is_under_distributer ? $amount  : $amount + ($percent_robus->percent * $amount);
        $amount_solde = robus()->wallet->amount - $amount_commission;

        $walletPhone->update([
            'amount_solde' => $amount_solde,
        ]);

        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);

        $amount_percent = $percent_robus->percent * $amount;

        $valuer_deposit = (float) $percent_robus->percent_merchands * $amount;

        if (robus()->is_under_distributer) {

            if (!empty($activeButtons['showRemonterDjamoButton'])) {

                $valuer_companie = $percent_robus->percent_companie * $amount;

                $cumul_valeurs = (float) $valuer_companie + (float) $valuer_deposit;

                $admin_valeur = (float) $amount_percent - (float) $cumul_valeurs;

                $walletPhone->increment('commissions', $valuer_deposit);

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterDjamoCButton'])) {
                    $walletPhone->increment('commission_companie', $valuer_companie);
                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ,  'wallet_phone_credit_id' ,  $walletPhone->id, $valuer_companie, $valuer_deposit);
                } else {
                    $this->commissionServices->distributeAdminCommissionAccepteds($admin_civ,  'wallet_phone_credit_id' ,  $walletPhone->id, $amount_percent, $valuer_deposit);
                }
            } else {

                $admin_valeur =  $amount * $percent_robus->percent;

                $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_phone_credit_id' ,  $walletPhone->id, $admin_valeur);
            }
        } else {

            $admin_valeur =  $amount * $percent_robus->percent;

            $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_phone_credit_id' ,  $walletPhone->id, $admin_valeur);
        }


        return true;
    }

}
