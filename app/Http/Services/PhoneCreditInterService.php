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
    protected $commissionServices;

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
            'balance_before' =>  robus()->wallet->amount,
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
        $valuer_companie = $percent_robus->percent_companie * $amount;

        $admin_valeur_convertion =  $this->convertionService->convertAdminCommission($amount_percent);

        $commission_convertion =  $this->convertionService->convertAdminCommission($valuer_deposit);
        $commission_bank_convertion =  $this->convertionService->convertAdminCommission($valuer_companie);

        if (robus()->is_under_distributer || robus()->is_api) {

            if (!empty($activeButtons['showRemonterInterButton'])) {

                $walletPhone->increment('commissions', $valuer_deposit);

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterInterCButton'])) {
                    $walletPhone->increment('commission_companie', $valuer_companie);
                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ,  'wallet_inter_id',  $walletPhone->id, $commission_bank_convertion, $commission_convertion);
                } else {
                    $this->commissionServices->distributeAdminNoCommissionInterCompagnies($admin_civ,  'wallet_inter_id',  $walletPhone->id, $commission_bank_convertion , $commission_convertion);
                }
            }
        } else {

            $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_inter_id',  $walletPhone->id, $admin_valeur_convertion);
        }

        return true;
    }
}
