<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use App\Repositories\WaveRepository;
use Illuminate\Support\Facades\DB;
use App\Http\Services\CommissionService;

class WaveService
{

    /** @var  WaveRepository */
    public $waveRepository;
    protected $commissionServices;
    protected $operatorRestrictionService;


    public function __construct(
        WaveRepository $waveRepository,
        CommissionService $commissionServices,
        OperatorRestrictionService $operatorRestrictionService,
    ) {
        $this->waveRepository = $waveRepository;
        $this->commissionServices = $commissionServices;
        $this->operatorRestrictionService = $operatorRestrictionService;
    }


    public function createRobusWalletTransactionWave($amount_valeur, $provider, $percent_robus, $amountReceved)
    {

        $amount_commission = (float)$amount_valeur + (float)$percent_robus->percent * $amount_valeur;

        $amount_solde = (float)robus()->wallet->amount - (float)$amount_commission;


        return $this->waveRepository->create([
            'wallet_id' =>   robus()->wallet->id,
            'amount' => $amount_valeur,
            'description'  => 'Demande de depot UV par Wave',
            'reload_cost_id' => 1,
            'created_by' => robus()->id,
            'amount_solde'  => $amount_solde  ?? null,
            'provider_id'  =>  $provider,
            'costs_amount' => ($percent_robus->percent * $amount_valeur),
            'phone'  =>  robus()->phone,
            'full_name' =>  robus()->firstname . ' ' . robus()->lastname,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
        ]);
    }



    public function updateamountWaveReceveid($admin_civ, $percent_robus, $walletWave, $amount)
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


        $amount_commission = $amount + ($percent_robus->percent * $amount);

        $amount_solde = robus()->wallet->amount - $amount_commission;


        if (robus()->is_under_distributer || robus()->is_api) {


            $walletWave->update([
                'amount_solde' => $amount,
            ]);

            robus()->wallet->update([
                'reload_by' => robus()->firstname . ' ' . robus()->lastname,
                'sid_user_relod' => robus()->id,
                'amount' => DB::raw('amount-'  .  $amount),
            ]);


            $valuer_deposit =  $percent_robus->percent_merchands  * $amount;
            $admin_nocomission =  $percent_robus->percent  * $amount;

            if (!empty($activeButtons['showRemonterWaveButton'])) {

                $walletWave->update([
                    'commissions' => $valuer_deposit,
                ]);

                $admin_valeur =  $percent_robus->percent_admin *  $amount;

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterWaveCButton'])) {

                    // $compagnie_amount =  $percent_robus->percent_companie +  $percent_robus->comission_auther;

                    $valuer_companie = $percent_robus->percent_companie * $amount;

                    $walletWave->increment('commission_companie', $valuer_companie);

                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ, 'wallet_wave_id', $walletWave->id, $valuer_companie, $valuer_deposit);
                } else {
                    $this->commissionServices->distributeAdminCommissionAccepteds($admin_civ, 'wallet_wave_id', $walletWave->id, $admin_valeur, $valuer_deposit);
                }
            } else {

                $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_wave_id', $walletWave->id, $admin_nocomission);
            }
        } else {

            $walletWave->update([
                'amount_solde' => $amount_solde,
            ]);

            robus()->wallet->update([
                'reload_by' => robus()->firstname . ' ' . robus()->lastname,
                'sid_user_relod' => robus()->id,
                'amount' => DB::raw('amount-'  .  $amount_solde),
            ]);

            $admin_valeur =  $amount * $percent_robus->percent;

            $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_wave_id', $walletWave->id, $admin_valeur);
        }


        return true;
    }
}
