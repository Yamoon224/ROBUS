<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\DB;
use App\Repositories\VirementRepository;
use App\Http\Services\OperatorRestrictionService;
use App\Models\Wallets\WalletCommission;
use App\Models\Wallets\WalletAdmin;

class VirementService
{

    /** @var  VirementRepository */
    public $virementRepository;
    // private $robusMasterTransaction;
    public $operatorRestrictionService;

    public function __construct(
        VirementRepository $virementReposi,
        // MasterService $robusMasterTransaction
        OperatorRestrictionService $operatorRestrictionService,

    ) {
        $this->virementRepository = $virementReposi;
        // $this->robusMasterTransaction = $robusMasterTransaction;
        $this->operatorRestrictionService = $operatorRestrictionService;
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

                // $valuer_master =  $master_id ? $amount_user->percent_masters  : 0;
                $amountDeposit =  $amount_user->comission_distributer;
                $amountCompanie =  $amount_user->percent_companie;
                $totalAmount = (float)$amountDeposit + $amountCompanie;
                $amountDepositUnder = (float) $amountDeposit;
              
                $walletBank->update([
                    'commissions' => DB::raw('commissions+'  .  $amountDepositUnder),
                ]);

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterVirementCButton'])) {

                    if ($admin_civ->count() > 0) {

                        $count_admin = $admin_civ->count();

                        $valeur_single = $totalAmount / $count_admin;
    
                        foreach ($admin_civ as $user) {
                            $admin_single = SidUser::find($user->id);
                            AdminsCom::create([
                                'amount' => $valeur_single,
                                'sid_user_id' => $admin_single->id,
                                'wallet_transaction_id' => null,
                                'wallet_bank_id' => $walletBank->id,
                                'description' => 'Commission administrateur',
                                'amount_under' =>  robus()->is_under_distributer ? $amountDeposit : 0,
                                'description_underdistributer' =>  robus()->is_under_distributer ? 'Paiement commission sous-distributer' : null
                            ]);
                        }
                    }

                    $walletBank->increment('commission_companie', $amountCompanie);

                }else {
 
                 $this->distributeAdminCommissionAccepteds($admin_civ, $walletBank, $amountDeposit , $amountDeposit);

                }

               
            } else {

                $amountDeposit = (float) $amount_user->amount;
                $this->distributeAdminCommissions($admin_civ, $walletBank, $amountDeposit);
            }
        } else {

            $amountDeposit = (float) $amount_user->amount;

            $this->distributeAdminCommissions($admin_civ, $walletBank, $amountDeposit);
        }

        return true;
    }

    private function distributeAdminCommissions($adminCiv, $walletBank, $totalCommission)
    {
        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $totalCommission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'wallet_bank_id' => $walletBank->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  0,
                    'description_underdistributer' => null,
                ]);
            }
        }
    }

    private function distributeAdminCommissionAccepteds($adminCiv, $walletBank, $totalCommission , $amountDeposit)
    {
        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $totalCommission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'wallet_bank_id' => $walletBank->id,
                    'description' => 'Commission administrateur',
                    'description' => 'Commission administrateur',
                    'amount_under' =>  robus()->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' =>  robus()->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ]);
            }
        }
    }
}
