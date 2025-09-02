<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\DB;
use App\Repositories\OrderCardRepository;
use App\Http\Services\MasterService;
use App\Http\Services\CommissionService;

class OrderCardService
{

    /** @var  OrderCardRepository */
    public $orderCardRepository;
    // private $robusMasterTransaction;
    protected $operatorRestrictionService;
    protected $commissionServices;

    public function __construct(
        OrderCardRepository $orderCardReposi,
        // MasterService $robusMasterTransaction,
        OperatorRestrictionService $operatorRestrictionService,
        CommissionService $commissionServices

    ) {
        $this->orderCardRepository = $orderCardReposi;
        // $this->robusMasterTransaction = $robusMasterTransaction;
        $this->operatorRestrictionService = $operatorRestrictionService;
        $this->commissionServices = $commissionServices;
    }


    public function createRobusWalletOrderCard($amount_valeur, $provider, $percent_robus, $address, $email)
    {

        $amount_solde = robus()->wallet->amount - $amount_valeur;

        return $this->orderCardRepository->create([
            'amount_solde'  => $amount_solde ?? null,
            'company_id'   => robus()->company_id,
            'country_id'   => robus()->country->id,
            'city_id'   => robus()->city->id,
            'bank_card_id'   => $provider,
            'amount'   => $amount_valeur,
            'order_card_cost_id'  =>  $percent_robus->id,
            'address'   => $address,
            //phone   => $phone,
            'email'   => $email,
            'wallet_id'  => robus()->wallet->id,
            'status'   => false,
            'costs_amount'   => $amount_valeur,
            'is_either'   => false,
            'is_other'   => true,
            'created_by'  => robus()->id,
            'status'   => "PENDING",
        ]);
    }

    public function createRobusWalletOrderCardOther($amount_valeur, $provider, $percent_robus, $address, $email, $phone, $number_card, $file_identity_doc, $file_identity_form)
    {

        $amount_solde = robus()->wallet->amount - $amount_valeur;


        $cardBank = $this->orderCardRepository->create([
            'amount_solde'  => $amount_solde ?? null,
            'company_id'   => robus()->company_id,
            'country_id'   => robus()->country->id,
            'city_id'   => robus()->city->id,
            'bank_card_id'   => $provider,
            'amount'   => $amount_valeur,
            'order_card_cost_id'  =>  $percent_robus->id,
            'address'   => $address,
            'phone'   => $phone,
            'email'   => $email,
            'number_card'   => $number_card,
            'wallet_id'  => robus()->wallet->id,
            'status'   => false,
            'costs_amount'   => $amount_valeur,
            'is_either'   => false,
            'is_other'   => true,
            'created_by'  => robus()->id,
        ]);

        // Upload Images        
        if (isset($file_identity_doc)) {
            $destination_path = public_path('bank_cards/' . robus()->id . '/');
            $front_file = 'recto_' . date('YmdHis') . "." . $file_identity_doc->getClientOriginalExtension();
            $file_identity_doc->move($destination_path, $front_file);
            $cardBank->update([
                'identity_doc' =>  env('APP_URL') . "/public/bank_cards/" . robus()->id . "/$front_file",
            ]);
        }

        if (isset($file_identity_form)) {
            $destination_path = public_path('bank_cards/' . robus()->id  . '/');
            $back_file = 'verso_' . date('YmdHis') . "." . $file_identity_form->getClientOriginalExtension();
            $file_identity_form->move($destination_path, $back_file);
            $cardBank->update([
                'identity_form' =>  env('APP_URL') . "/public/bank_cards/" . robus()->id . "/$back_file",
            ]);
        }

        return $cardBank;
    }

    public function updateamountOrderCardReceveid($admin_civ, $percent_robus, $walletOrderCard, $amount_commission)
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

        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);

        if (robus()->is_under_distributer) {


            $valuer_deposit = (float) $percent_robus->percent_merchands;
            // Convertion in devise Use send
            $convertion_commission = $this->commissionServices->convertAdminCommission($valuer_deposit);
            $admin_valeur =  $amount_commission -  $percent_robus->percent_merchands;

            if (!empty($activeButtons['showRemonterOrderButton'])) {


                $walletOrderCard->update([
                    'commissions' => DB::raw('commissions+'  .  $convertion_commission),
                ]);

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterOrderCButton'])) {

                    $valuer_companie = $percent_robus->percent_companie;

                    $convertion_commission_compagnie = $this->commissionServices->convertAdminCommission($valuer_companie);

                    $walletOrderCard->increment('commission_companie', $convertion_commission_compagnie);
                    
                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ, 'bank_card_id' ,$walletOrderCard->id, $convertion_commission_compagnie, $valuer_deposit);

                }else {
                    $this->commissionServices->distributeAdminCommissionAccepteds($admin_civ, 'bank_card_id' ,$walletOrderCard->id, $admin_valeur, $valuer_deposit); 
                }

            } else {

                $this->commissionServices->distributeAdminCommissions($admin_civ, 'bank_card_id' ,$walletOrderCard->id, $amount_commission);

            }
        } else {


            $admin_valeur = (float) $amount_commission;

            $admin_valeur_convertion = $this->commissionServices->convertAdminCommission($admin_valeur);

            $this->commissionServices->distributeAdminCommissions($admin_civ, 'bank_card_id' ,$walletOrderCard->id, $admin_valeur_convertion);
        }


        return true;
    }

    public function convertAdminCommission($amount)
    {
        $country_civ = Country::find(107);
        $robus_currency = robus()->country->currencie->value;

        if ($country_civ->currencie->value > robus()->country->currencie->value) {
            return $amount * $robus_currency;
        } elseif ($country_civ->currencie->value == robus()->country->currencie->value) {
            return $amount;
        } else {
            return $amount * $robus_currency;
        }
    }
}
