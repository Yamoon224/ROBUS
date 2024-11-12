<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\DB;
use App\Models\Wallets\WalletInter;
use App\Repositories\IntersRepository;
use App\Http\Services\OperatorRestrictionService;

class PhoneCreditInterService
{


    /** @var  IntersRepository */
    public $intersRepository;
    public $convertionService;
    protected $operatorRestrictionService;

    public function __construct(
        IntersRepository $inter_Reposi,
        ConvertionService $convertionService,
        OperatorRestrictionService $operatorRestrictionService,
    ) {
        $this->intersRepository = $inter_Reposi;
        $this->convertionService = $convertionService;
        $this->operatorRestrictionService = $operatorRestrictionService;
    }


    public function createRobusWalletTransactionCreditPayment(
        $val_amount,
        $percent_robus,
        $country_robus,
        $provider,
        $id_invoice,
        $number_decoder,
        $number_phone,
        $amount_conversion,
        $number_contract,
        $paiementChoix_phone,
        $offers,
        $amount_receveid
    ) {
        $amount_solde = robus()->wallet->amount - $val_amount;

        return WalletInter::create([
            'amount_solde' => $amount_solde ?? null,
            'wallet_id' =>   robus()->wallet->id,
            'amount' => $val_amount,
            'description'  => "Payment de Credit Téléphonique",
            'inter_cost_id' => null,
            'provider_type' =>  6,
            'payment_cost_id' => $percent_robus->id,
            'number_decoder'   => $number_decoder ?? null,
            'id_invoice' => $id_invoice ?? null,
            'phone' =>   $country_robus->indecative . $number_contract ?? null,
            'name' => $name ?? null,
            'created_by' => robus()->id,
            'provider_id'  =>   $provider,
            'number_phone' => $number_phone ?? null,
            'offer_id'  =>  $offers->id ?? null,
            'status'  => "PENDING",
            'costs_amount' =>  $amount_conversion,
            'country_id'  =>   $country_robus->id,
            'paiementChoix_phone'  => $paiementChoix_phone ?? null,
            'amount_received' =>  $amount_receveid ?? null,
        ]);
    }

    public function updateamountPhoneCreditReceveid($admin_civ, $percent_robus, $walletPhone, $amount)
    {

        $amount_commission = $amount + ($percent_robus->percent * $amount);

        $amount_solde = robus()->wallet->amount - $amount_commission;

        $walletPhone->update([
            'amount_solde' => $amount_solde,
        ]);

        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);

        $amount_percent =  $amount * $percent_robus->percent;

        $valuer_deposit = (float) $percent_robus->percent_merchands * $amount;

        $admin_valeur_convertion =  $this->convertionService->convertAdminCommission($amount_percent);

        $commission_convertion =  $this->convertionService->convertAdminCommission($valuer_deposit);

        if (robus()->is_under_distributer) {

            if (!empty($activeButtons['showRemonterInterButton'])) {

                $valuer_companie = $percent_robus->percent_companie * $amount;

                $cumul_valeurs = (float) $valuer_companie + (float) $valuer_deposit;

                $admin_valeur = (float) $amount_percent - (float) $cumul_valeurs;

               $admin_convertion =  $this->convertionService->convertAdminCommission($admin_valeur);

                $walletPhone->increment('commissions', $valuer_deposit);

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted) {
                    $walletPhone->increment('commission_companie', $valuer_companie);
                    $this->distributeAdminCommissionAccepteds($admin_civ, $walletPhone, $admin_convertion, $valuer_deposit);
                } else {
                    $this->distributeAdminCommissionAccepteds($admin_civ, $walletPhone, $commission_convertion, $valuer_deposit);
                }
            } else {

                $this->distributeAdminCommissions($admin_civ, $walletPhone, $admin_valeur_convertion);
            }
        } else {

            $this->distributeAdminCommissions($admin_civ, $walletPhone, $admin_valeur_convertion);
        }


        //  Send valeur in Admin
        if ($admin_civ->count() > 0) {

            $count_admin = $admin_civ->count();

            $valeur_single = $admin_valeur_convertion / $count_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                AdminsCom::create([
                    'amount' =>   $valeur_single,
                    'sid_user_id' =>   $admin_single->id,
                    'wallet_inter_id' => $walletPhone->id,
                    'description' => 'Commission administrateur',
                ]);
            }
        }

        return true;
    }

    private function distributeAdminCommissions($adminCiv, $walletPhone, $totalCommission)
    {
        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $totalCommission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create([
                    'amount' =>   $commissionPerAdmin,
                    'sid_user_id' =>   $adminUser->id,
                    'wallet_inter_id' => $walletPhone->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  0,
                    'description_underdistributer' =>  null
                ]);
            }
        }
    }

    private function distributeAdminCommissionAccepteds($adminCiv, $walletPhone, $totalCommission, $amountDeposit)
    {
        if ($adminCiv->count() > 0) {
            $commissionPerAdmin = $totalCommission / $adminCiv->count();

            foreach ($adminCiv as $user) {
                $adminUser = SidUser::find($user->id);

                AdminsCom::create([
                    'amount' => $commissionPerAdmin,
                    'sid_user_id' => $adminUser->id,
                    'wallet_inter_id' => $walletPhone->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  robus()->is_under_distributer ? $amountDeposit : 0,
                    'description_underdistributer' =>  robus()->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ]);
            }
        }
    }
}
