<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\DB;
use App\Repositories\PhoneCreditRepository;
use App\Http\Services\OperatorRestrictionService;
use App\Http\Services\CommissionService;

class PhoneCreditService
{

    /** @var  PhoneCreditRepository */
    public $phoneCreditRepository;
    protected $operatorRestrictionService;
    protected $commissionServices;

    public function __construct(
        PhoneCreditRepository $phoneCreditReposi,
        OperatorRestrictionService $operatorRestrictionService,
        CommissionService $commissionServices
    ) {
        $this->phoneCreditRepository = $phoneCreditReposi;
        $this->operatorRestrictionService = $operatorRestrictionService;
        $this->commissionServices = $commissionServices;
    }


    public function createRobusWalletTransactionPhone($amount_valeur, $provider, $percent_robus, $phone, $amountReceved)
    {

        $amount_solde = robus()->wallet->amount - $amount_valeur;

        return $this->phoneCreditRepository->create([
            'wallet_id' =>   robus()->wallet->id,
            'amount' => $amount_valeur,
            'description'  => 'Demande de rechargement de credit',
            'phone_credit_cost_id' => 1,
            'created_by' => robus()->id,
            'amount_solde'  => $amount_solde ?? null,
            'provider_id'  =>  $provider,
            'costs_amount' => ($percent_robus->percent * $amount_valeur),
            'phone'  =>  robus()->country->indecative . $phone,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
        ]);
    }

    public function updateamountPhoneCreditReceveid($admin_civ, $percent_robus, $walletPhone, $amount)
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

        $amount_commission = (robus()?->is_under_distributer || robus()?->is_api) ?  $amount  : $amount + ($percent_robus->percent * $amount);
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

        if (robus()->is_under_distributer || robus()->is_api) {

            if (!empty($activeButtons['showRemonterCreditButton'])) {

                $valuer_companie = $percent_robus->percent_companie * $amount;

                $cumul_valeurs = (float) $valuer_companie + (float) $valuer_deposit;

                $admin_valeur = (float) $amount_percent - (float) $cumul_valeurs;

                $walletPhone->increment('commissions', $valuer_deposit);

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterCreditCButton'])) {
                    $walletPhone->increment('commission_companie', $valuer_companie);
                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ,  'wallet_phone_credit_id',  $walletPhone->id, $valuer_companie, $valuer_deposit);
                } else {
                    $this->commissionServices->distributeAdminCommissionAccepteds($admin_civ,  'wallet_phone_credit_id',  $walletPhone->id, $amount_percent, $valuer_deposit);
                }
            } else {

                $admin_valeur =  $amount * $percent_robus->percent;

                $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_phone_credit_id',  $walletPhone->id, $admin_valeur);
            }
        } else {

            $admin_valeur =  $amount * $percent_robus->percent;

            $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_phone_credit_id',  $walletPhone->id, $admin_valeur);
        }


        return true;
    }
}
