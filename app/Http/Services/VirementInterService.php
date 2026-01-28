<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\DB;
use App\Models\Wallets\WalletInter;
use App\Http\Services\ConvertionService;
use App\Repositories\IntersRepository;
use App\Http\Services\OperatorRestrictionService;

class VirementInterService
{


    /** @var  IntersRepository */
    public $intersRepository;
    public $convertionService;
    public $operatorRestrictionService;
    public $commissionServices;

    public function __construct(
        IntersRepository $inter_Reposi,
        ConvertionService $convertionService,
        OperatorRestrictionService $operatorRestrictionService,
        CommissionService $commissionServices
    ) {
        $this->intersRepository = $inter_Reposi;
        $this->convertionService = $convertionService;
        $this->commissionServices = $commissionServices;
        $this->operatorRestrictionService = $operatorRestrictionService;
    }

    public function createRobusWalletTransactionOrderCard(
        $val_amount,
        $account_number,
        $country_robus,
        $provider,
        $amount_user,
        $name,
        $amount_receveid
    ) {
        $amount_solde = robus()->wallet->amount - $val_amount;

        return $this->intersRepository->create(
            [

                'amount_solde' => $amount_solde ?? null,
                'wallet_id' => robus()->wallet->id,
                'amount' => $val_amount,
                'balance_before' =>  robus()->wallet->amount,
                'inter_cost_id' => null,
                'deposit_cost_id' => 1,
                'number_compte' =>  null,
                'balance_before' =>  robus()->wallet->amount,
                'created_by' => robus()->id,
                'provider_id' =>  $provider,
                'number_card' =>  $account_number,
                'country_id' => $country_robus->id,
                'name' => $name,
                'provider_type' => 8,
                'costs_amount' => $amount_user,
                'status' => 'PENDING',
                'amount_received' =>  $amount_receveid ?? null,

            ]
        );
    }


    public function createRobusWalletTransactionVirement(
        $val_amount,
        $account_number,
        $country_robus,
        $provider,
        $amount_user,
        $phone,
        $firstname,
        $lastname,
        $amount_receveid
    ) {
        $amount_solde = robus()->wallet->amount - $val_amount;

        return WalletInter::create([
            'wallet_id' => robus()->wallet->id,
            'amount_solde' => $amount_solde ?? null,
            'amount' => $val_amount,
            'inter_cost_id' => null,
            'bank_cost_id' => 1,
            'balance_before' =>  robus()->wallet->amount,
            'phone' => $phone,
            'number_compte' => $account_number,
            'created_by' => robus()->id,
            'provider_id' => $provider,
            'status' => 'PENDING',
            'country_id' => $country_robus->id,
            'provider_type' => 7,
            'firstname' =>  $firstname ??  null,
            'lastname' => $lastname ?? null,
            'costs_amount' =>  $amount_user,
            'amount_received' =>  $amount_receveid ?? null,
        ]);
    }

    public function updateamountVirementInterReceveid($admin_civ, $amount_user, $walletBank, $amount_valeur, $percent_valeur, $amount_commission_compagnie)
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

        $amount_solde = robus()->wallet->amount - $amount_valeur;

        $walletBank->update([
            'amount_solde' => $amount_solde,
        ]);

        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_valeur),
        ]);


        if (robus()->is_under_distributer || robus()->is_api) {

            $valuer_deposit = $amount_user;

            if (!empty($activeButtons['showRemonterInterButton'])) {

                $amountCompanie =  $amount_commission_compagnie;
                $totalAmount = (float)$valuer_deposit + $amountCompanie;
                $valuer_depositUnder = (float) $valuer_deposit;

                $walletBank->update([
                    'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
                ]);

                $admin_valeur_convertion =  $this->convertionService->convertAdminCommission($valuer_deposit);

                $admin_cumul_convertion =  $this->convertionService->convertAdminCommission($totalAmount);

                $valeur_amount_admin =  (float) $percent_valeur  -  (float)$admin_valeur_convertion;

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterInterCButton'])) {

                    $walletBank->increment('commission_companie', $amountCompanie);

                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ, 'wallet_inter_id' ,$walletBank->id, $admin_cumul_convertion, $valeur_amount_admin);

                } else {
                    $this->commissionServices->distributeAdminCommissionAccepteds($admin_civ , 'wallet_inter_id', $walletBank->id, $valeur_amount_admin , $admin_valeur_convertion);
                }
            } else {

                $admin_valeur_convertion = $percent_valeur;
                $this->commissionServices->distributeAdminCommissions($admin_civ , 'wallet_inter_id', $walletBank->id, $admin_valeur_convertion);

            }
        } else {

            $valeur_amount_admin = $percent_valeur;
            $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_inter_id' ,$walletBank->id, $valeur_amount_admin);
        }

        return true;
    }

}
