<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\DB;
use App\Repositories\PaymentRepository;
use App\Http\Services\OperatorRestrictionService;

class PaymentService
{

    /** @var  PaymentRepository */
    public $paiementRepository;
    private $robusMasterTransaction;
    protected $operatorRestrictionService;

    public function __construct(
        PaymentRepository $paiementReposi,
        // MasterService $robusMasterTransaction,
        OperatorRestrictionService $operatorRestrictionService,

    ) {
        $this->paiementRepository = $paiementReposi;
        // $this->robusMasterTransaction = $robusMasterTransaction;
        $this->operatorRestrictionService = $operatorRestrictionService;
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
            // $valuer_master =  $master_id ? $percent_robus->percent_masters  : 0;
            // $amount_rec_under = (float)  $valuer_deposit - (float) $valuer_master;

            if (!empty($activeButtons['showRemonterPaiementButton'])) {

                $walletPayment->update([
                    'commissions' => $valuer_deposit,
                ]);

                $admin_valeur =  $percent_robus->amount_auther -  $percent_robus->comission_auther;

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted) {

                    $compagnie_amount =  $percent_robus->percent_companie +  $percent_robus->comission_auther;

                    $valuer_companie = $percent_robus->percent_companie;

                    $walletPayment->increment('commission_companie', $valuer_companie);

                    $this->distributeAdminCommissionAccepteds($admin_civ, $walletPayment, $compagnie_amount, $valuer_deposit);
                } else {
                    $this->distributeAdminCommissionAccepteds($admin_civ, $walletPayment, $admin_valeur, $valuer_deposit);
                }
                // $walletPayment->increment('commission_masters', $valuer_master);

            } else {

                $this->distributeAdminCommissions($admin_civ, $walletPayment, $admin_nocomission);
            }
        } else {

            // $valuer_master =  $master_id ? $percent_robus->percent_masters : 0;
            $admin_valeur =  (float)  $percent_robus->amount_auther;
            // $walletPayment->increment('commission_masters', $valuer_master);
            $this->distributeAdminCommissions($admin_civ, $walletPayment, $admin_valeur);
        }

        return true;
    }

    public function updateAmountPaymentOfferReceveid($admin_civ, $percent_robus, $walletPayment, $amount)
    {

        $amount_commission =  $amount;
        $amount_solde = robus()->wallet->amount - $amount;
        // $master_id = $this->robusMasterTransaction->mastersId();
        $userId = robus()->id;
        $activeButtons = $this->operatorRestrictionService->getActiveButtons($userId);


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

            // $valuer_master =  $master_id ? $percent_robus->percent_masters  : 0;

            // $amount_rec_under = (float)  $valuer_deposit - (float) $valuer_master;

            if (!empty($activeButtons['showRemonterPaiementButton'])) {

                $walletPayment->update([
                    'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
                ]);

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted) {

                    $compagnie_amount =  $percent_robus->percent_companie +  $percent_robus->amount_canal;

                    $valuer_companie = $percent_robus->percent_companie;

                    $walletPayment->increment('commission_companie', $valuer_companie);

                    $this->distributeAdminCommissionAccepteds($admin_civ, $walletPayment, $compagnie_amount, $valuer_deposit);

                } else {
                    $this->distributeAdminCommissionAccepteds($admin_civ, $walletPayment, $admin_valeur, $valuer_deposit);
                }
            } else {

                $this->distributeAdminCommissions($admin_civ, $walletPayment, $admin_valeur);

            }

            // $walletPayment->increment('commission_masters', $valuer_master);
        } else {

            $this->distributeAdminCommissions($admin_civ, $walletPayment, $admin_nocomission);

            // $valuer_master =  $master_id ? $percent_robus->percent_masters : 0;

            // $amount_rec_admin =  $percent_robus->amount_canal;

            // $admin_valeur =  (float) $amount_rec_admin - (float) $valuer_master;

            // $walletPayment->increment('commission_masters', $valuer_master);
        }


        return true;
    }

    private function distributeAdminCommissions($adminCiv, $walletPayment, $totalCommission)
    {
        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $totalCommission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create([
                    'amount' =>   $commissionPerAdmin,
                    'sid_user_id' =>   $adminUser->id,
                    'wallet_payment_id' => $walletPayment->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  0,
                    'description_underdistributer' =>  null
                ]);
            }
        }
    }

    private function distributeAdminCommissionAccepteds($adminCiv, $walletPayment, $totalCommission, $amountDeposit)
    {
        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $totalCommission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'wallet_payment_id' => $walletPayment->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  robus()->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' =>  robus()->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ]);
            }
        }
    }
}
