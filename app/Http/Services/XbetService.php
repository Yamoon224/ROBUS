<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Invoices\OneBetInvoice;
use App\Models\Payments\OneBetTransaction;
use App\Models\Sid\SidUser;
use App\Repositories\OnexbetRepository;
use Illuminate\Support\Facades\DB;
use App\Http\Services\CommissionService;

class XbetService
{

    /** @var  XbetRepository */
    public $xbetRepository;
    protected $commissionServices;
    protected $operatorRestrictionService;

    public function __construct(
        OnexbetRepository $xbetRepository,
        CommissionService $commissionServices,
        OperatorRestrictionService $operatorRestrictionService,

    ) {
        $this->xbetRepository = $xbetRepository;
        $this->commissionServices = $commissionServices;
        $this->operatorRestrictionService = $operatorRestrictionService;
    }


    public function createRobusWalletTransactionOneBet($amount_valeur, $provider, $percent_robus, $numberAcompte, $fullName, $amountReceved)
    {

        $amount_commission = (float)$amount_valeur + (float)$percent_robus->percent * $amount_valeur;

        $amount_solde = (float)robus()->wallet->amount - (float)$amount_commission;


        return $this->xbetRepository->create([
            'wallet_id' =>   robus()->wallet->id,
            'amount' => $amount_valeur,
            'description'  => 'Demande  de depot 1Xbet',
            'Onexbet_id' => 1,
            'created_by' => robus()->id,
            'amount_solde'  => $amount_solde  ?? null,
            'provider_id'  =>  $provider,
            'costs_amount' => ($percent_robus->percent * $amount_valeur),
            'choose_type_transaction' => 1,
            'num_acompte'  =>  $numberAcompte,
            'full_name' =>  $fullName,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
        ]);
    }


    public function createRobusWalletDrawalOneBet($amount_valeur, $provider, $percent_robus, $numberAcompte, $countrie, $citie, $code_drawal, $amountReceved)
    {

        $amount_solde = robus()->wallet->amount;

        return $this->xbetRepository->create([
            'wallet_id' =>   robus()->wallet->id,
            'amount' => $amount_valeur,
            'description'  => 'Demande de retrait 1Xbet',
            'Onexbet_id' => 1,
            'created_by' => robus()->id,
            'amount_solde'  => $amount_solde ?? null,
            'provider_id'  =>  $provider,
            'costs_amount' => ($percent_robus->percent_with_drawal * $amount_valeur),
            'choose_type_transaction' => 2,
            'num_acompte'  =>  $numberAcompte,
            'code_drawal' =>  $code_drawal,
            'countrie' =>  $countrie,
            'citie' =>  $citie,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
        ]);
    }

    public function updateamountOneBetReceveid($admin_civ, $percent_robus, $walletXbet, $amount)
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


        if (robus()->is_under_distributer) {


            $walletXbet->update([
                'amount_solde' => $amount,
            ]);

            robus()->wallet->update([
                'reload_by' => robus()->firstname . ' ' . robus()->lastname,
                'sid_user_relod' => robus()->id,
                'amount' => DB::raw('amount-'  .  $amount),
            ]);


            $valuer_deposit =  $percent_robus->percent_merchands  * $amount;
            $admin_nocomission =  $percent_robus->percent  * $amount;

            if (!empty($activeButtons['showRemonterXbetButton'])) {

                $walletXbet->update([
                    'commissions' => $valuer_deposit,
                ]);

                $admin_valeur =  $percent_robus->percent_admin *  $amount;

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterXbetCButton'])) {

                    // $compagnie_amount =  $percent_robus->percent_companie +  $percent_robus->comission_auther;

                    $valuer_companie = $percent_robus->percent_companie * $amount;

                    $walletXbet->increment('commission_companie', $valuer_companie);

                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ, 'wallet_xbet_id', $walletXbet->id, $valuer_companie, $valuer_deposit);
                } else {
                    $this->commissionServices->distributeAdminCommissionAccepteds($admin_civ, 'wallet_xbet_id', $walletXbet->id, $admin_valeur, $valuer_deposit);
                }
            } else {

                $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_xbet_id', $walletXbet->id, $admin_nocomission);
            }
        } else {

            $walletXbet->update([
                'amount_solde' => $amount_solde,
            ]);

            robus()->wallet->update([
                'reload_by' => robus()->firstname . ' ' . robus()->lastname,
                'sid_user_relod' => robus()->id,
                'amount' => DB::raw('amount-'  .  $amount_solde),
            ]);

            $admin_valeur =  $amount * $percent_robus->percent;

            $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_xbet_id', $walletXbet->id, $admin_valeur);
        }

        return true;
    }

    public function updateamountOneBetDrawal($admin_civ, $percent_robus, $walletXbet, $amount)
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


        $amount_commission = $amount + ($percent_robus->percent_with_drawal * $amount);
        
        $amount_solde = robus()->wallet->amount - $amount_commission;

        $walletXbet->update([
            'amount_solde' => $amount_solde,
        ]);

        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);


        if (robus()->is_under_distributer) {


            $valuer_deposit =  $percent_robus->percent_merchands * $amount;
            $admin_nocomission =  $percent_robus->percent  * $amount;

            if (!empty($activeButtons['showRemonterXbetDrawalButton'])) {

                $walletXbet->update([
                    'commissions' => $valuer_deposit,
                ]);

                $admin_valeur =  $percent_robus->percent_admin *  $amount;

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterXbetDrawalCButton'])) {

                    // $compagnie_amount =  $percent_robus->percent_companie +  $percent_robus->comission_auther;

                    $valuer_companie = $percent_robus->percent_companie * $amount;

                    $walletXbet->increment('commission_companie', $valuer_companie);

                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ, 'wallet_xbet_id', $walletXbet->id, $valuer_companie, $valuer_deposit);
                } else {
                    $this->commissionServices->distributeAdminCommissionAccepteds($admin_civ, 'wallet_xbet_id', $walletXbet->id, $admin_valeur, $valuer_deposit);
                }
            } else {

                $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_xbet_id', $walletXbet->id, $admin_nocomission);
            }
        } else {

            $admin_valeur =  $amount * $percent_robus->percent;

            $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_xbet_id', $walletXbet->id, $admin_valeur);
        }


        return true;
    }
}
