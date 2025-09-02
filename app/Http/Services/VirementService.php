<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\DB;
use App\Repositories\VirementRepository;
use App\Http\Services\OperatorRestrictionService;
use App\Http\Services\CommissionService;

class VirementService
{

    /** @var  VirementRepository */
    public $virementRepository;
    // private $robusMasterTransaction;
    public $operatorRestrictionService;
    public $commissionServices;

    public function __construct(
        VirementRepository $virementReposi,
        // MasterService $robusMasterTransaction
        OperatorRestrictionService $operatorRestrictionService,
        CommissionService $commissionServices

    ) {
        $this->virementRepository = $virementReposi;
        // $this->robusMasterTransaction = $robusMasterTransaction;
        $this->operatorRestrictionService = $operatorRestrictionService;
        $this->commissionServices = $commissionServices;
    }


    public function createRobusVirement($amount_valeur, $amount_send, $provider, $description, $percent_robus, $number_compte, $firstname, $lastname, $comission_distributer, $phone, $amountReceved)
    {

        $amount_solde = robus()->wallet->amount - $amount_valeur;

        return $this->virementRepository->create([
            'amount_solde' => $amount_solde ?? null,
            'wallet_id' =>  robus()->wallet->id,
            'amount' => $amount_send,
            'description'  => $description,
            'bank_cost_id' =>  $percent_robus->id,
            'created_by' => robus()->id,
            'provider_id'  => $provider,
            'number_compte' =>  $number_compte,
            'firstname' =>  $firstname,
            'lastname' =>  $lastname,
            'costs_amount' => $comission_distributer,
            'phone' =>  $phone,
            'status'  => "PENDING",
            'country_id'  =>  $country ?? null,
            'amount_received'  =>  $amountReceved ?? 0.0,
        ]);
    }

    public function updateamountVirementReceveid($admin_civ, $amount_user, $walletBank, $amount_valeur)
    {

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

        $amount_commission = $amount_valeur;

        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);

        if (robus()->is_under_distributer) {

            if (!empty($activeButtons['showRemonterVirementButton'])) {

                $amountDeposit =  $amount_user->comission_distributer;
                $amountCompanie =  $amount_user->percent_companie;
                $totalAmount = (float)$amountDeposit + $amountCompanie;
                $amountDepositUnder = (float) $amountDeposit;
              
                $walletBank->update([
                    'commissions' => DB::raw('commissions+'  .  $amountDepositUnder),
                ]);

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterVirementCButton'])) {
                    $walletBank->increment('commission_companie', $amountCompanie);
                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ, 'wallet_bank_id' ,$walletBank->id, $amountCompanie, $amountDepositUnder);
                }else {
                 $this->commissionServices->distributeAdminCommissionAccepteds($admin_civ , 'wallet_bank_id', $walletBank->id, $amountDeposit , $amountDeposit);
                }

            } else {

                $amountDeposit = (float) $amount_user->amount;
                $this->commissionServices->distributeAdminCommissions($admin_civ , 'wallet_bank_id', $walletBank->id, $amountDeposit);
            }
        } else {

            $amountDeposit = (float) $amount_user->amount;

            $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_bank_id' ,$walletBank->id, $amountDeposit);
        }

        return true;
    }

}
