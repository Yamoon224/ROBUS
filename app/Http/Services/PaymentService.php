<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\DB;
use App\Repositories\PaymentRepository;
use App\Http\Services\OperatorRestrictionService;
use App\Http\Services\CommissionService;

class PaymentService
{

    /** @var  PaymentRepository */
    public $paiementRepository;
    private $robusMasterTransaction;
    protected $operatorRestrictionService;
    protected $commissionServices;

    public function __construct(
        PaymentRepository $paiementReposi,
        OperatorRestrictionService $operatorRestrictionService,
        CommissionService $commissionServices

    ) {
        $this->paiementRepository = $paiementReposi;
        $this->operatorRestrictionService = $operatorRestrictionService;
        $this->commissionServices = $commissionServices;
    }


    public function createRobusWalletTransactionPayment(
        $amount_valeur,
        $provider,
        $percent_robus,
        $number_phone,
        $offers,
        $paiementChoix_phone,
        $facture_choix_type,
        $number_decoder,
        $id_invoice,
        $number_contract
    ) {

        $amount_solde = robus()->wallet->amount - $amount_valeur;

        return $this->paiementRepository->create([
            'wallet_id' =>  robus()->wallet->id,
            'amount' => $amount_valeur,
            'amount_solde' => $amount_solde ?? null,
            'description'  => "Paiement de facture CIE",
            'payment_cost_id' => $percent_robus->id,
            'number_decoder'   => $number_decoder ?? null,
            'id_invoice' => $id_invoice ?? null,
            'number_contract' => $number_contract ?? null,
            'name' => $name ?? null,
            'created_by' => robus()->id,
            'provider_id'  =>    $provider,
            'number_phone' => $number_phone ?? null,
            'costs_amount' => $percent_robus->amount_auther ?? null,
            'offer_id'  =>  $offers->id ?? null,
            'status'  => "PENDING",
            'paiementChoix_phone'  => $paiementChoix_phone ?? null,
            'factureChoix_type' =>  $facture_choix_type,
            'phone'  => null,
        ]);
    }

    public function createRobusWalletTransactionPaymentOffer(
        $amount_valeur,
        $provider,
        $percent_robus,
        $number_phone,
        $offers,
        $paiementChoix_phone,
        $facture_choix_type,
        $number_decoder,
        $id_invoice,
        $number_contract
    ) {

        $amount_solde = robus()->wallet->amount - $amount_valeur;

        return $this->paiementRepository->create([
            'wallet_id' =>  robus()->wallet->id,
            'amount' => $amount_valeur,
            'amount_solde' => $amount_solde ?? null,
            'description'  => "Paiement Canal",
            'payment_cost_id' => $percent_robus->id,
            'number_decoder'   => $number_decoder ?? null,
            'id_invoice' => $id_invoice ?? null,
            'number_contract' => $number_contract ?? null,
            'name' => $name ?? null,
            'created_by' => robus()->id,
            'provider_id'  =>    $provider,
            'number_phone' => $number_phone ?? null,
            'costs_amount' => $percent_robus->amount_canal ?? null,
            'offer_id'  =>  $offers->id ?? null,
            'status'  => "PENDING",
            'paiementChoix_phone'  => $paiementChoix_phone ?? null,
            'factureChoix_type' =>  $facture_choix_type,
            'phone'  => null,
        ]);
    }

    public function updateAmountPaymentReceveid($admin_civ, $percent_robus, $walletPayment, $amount)
    {

        $amount_commission =  $amount;

        $amount_solde = robus()->wallet->amount - $amount;
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

        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);

        $walletPayment->update([
            'amount_solde' => $amount_solde,
        ]);

        if (robus()->is_under_distributer) {

            $valuer_deposit =  $percent_robus->comission_auther;
            $admin_nocomission =  $percent_robus->amount_auther;

            if (!empty($activeButtons['showRemonterPaiementButton'])) {

                $walletPayment->update([
                    'commissions' => $valuer_deposit,
                ]);

                $admin_valeur =  $percent_robus->amount_auther -  $percent_robus->comission_auther;

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterPaiementCButton'])) {

                    $compagnie_amount =  $percent_robus->percent_companie +  $percent_robus->comission_auther;

                    $valuer_companie = $percent_robus->percent_companie;

                    $walletPayment->increment('commission_companie', $valuer_companie);

                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ, 'wallet_payment_id' ,$walletPayment->id, $compagnie_amount, $valuer_deposit);
                } else {
                    $this->commissionServices->distributeAdminCommissionAccepteds($admin_civ, 'wallet_payment_id' ,$walletPayment->id, $admin_valeur, $valuer_deposit);
                }
            } else {

                $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_payment_id' ,$walletPayment->id, $admin_nocomission);
            }
        } else {

            $admin_valeur =  (float)  $percent_robus->amount_auther;
            $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_payment_id' ,$walletPayment->id, $admin_valeur);
        }

        return true;
    }

    public function updateAmountPaymentOfferReceveid($admin_civ, $percent_robus, $walletPayment, $amount)
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
        $walletPayment->update([
            'amount_solde' => $amount_solde,
        ]);


        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);

        $admin_nocomission =  $percent_robus->amount_auther;

        if (robus()->is_under_distributer) {

            $valuer_deposit =  $percent_robus->comission_canal;

            $admin_valeur =  $percent_robus->amount_canal -  $percent_robus->comission_canal;

            if (!empty($activeButtons['showRemonterPaiementButton'])) {

                $walletPayment->update([
                    'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
                ]);

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterPaiementCButton'])) {

                    $compagnie_amount =  $percent_robus->percent_companie +  $percent_robus->amount_canal;

                    $valuer_companie = $percent_robus->percent_companie;

                    $walletPayment->increment('commission_companie', $valuer_companie);

                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ, 'wallet_payment_id' ,$walletPayment->id, $valuer_companie, $valuer_deposit);
                } else {
                    $this->commissionServices->distributeAdminCommissionAccepteds($admin_civ, 'wallet_payment_id' ,$walletPayment->id, $admin_valeur, $valuer_deposit);
                }
            } else {

                $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_payment_id' ,$walletPayment->id, $admin_valeur);
            }
        } else {

            $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_payment_id' ,$walletPayment->id, $admin_nocomission);
        }

        return true;
    }

}
