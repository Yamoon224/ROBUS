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
            'balance_before' =>  robus()->wallet->amount,
            'company_id'   => robus()->company_id,
            'country_id'   => robus()->country->id,
            'city_id'   => robus()->city->id,
            'bank_card_id'   => $provider,
            'amount'   => $amount_valeur,
            'amount_received' => $amount_valeur ?? null,
            'order_card_cost_id'  =>  $percent_robus->id,
            'address'   => $address,
            //phone   => $phone,
            'email'   => $email,
            'wallet_id'  => robus()->wallet->id,
            'status'   => false,
            'costs_amount'   => 0,
            'is_either'   => false,
            'is_other'   => true,
            'created_by'  => robus()->id,
        ]);
    }

    public function createRobusWalletOrderCardOther($amount_valeur, $provider, $percent_robus, $address, $email, $phone, $number_card,
     $file_identity_doc, $file_identity_form, $type_piece, $num_piece_custumer, $date_piece,)
    {

        $amount_solde = robus()->wallet->amount - $amount_valeur;


        $cardBank = $this->orderCardRepository->create([
            'amount_solde'  => $amount_solde ?? null,
            'company_id'   => robus()->company_id,
            'country_id'   => robus()->country->id,
            'city_id'   => robus()->city->id,
            'bank_card_id'   => $provider,
            'balance_before' =>  robus()->wallet->amount,
            'amount'   => $amount_valeur,
            'amount_received' => $amount_valeur ?? null,
            'order_card_cost_id'  =>  $percent_robus->id,
            'address'   => $address,
            'phone'   => robus()->country->indecative . $phone,
            'email'   => $email,
            'number_card'   => $number_card,
            'wallet_id'  => robus()->wallet->id,
            'status'   => false,
            'costs_amount'   => 0,
            'type_piece'   => $type_piece,
            'num_piece_custumer'   => $num_piece_custumer,
            'date_piece'   => $date_piece,
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

    public function updateamountOrderCardReceveid($amount_commission)
    {

 
        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);

    

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
