<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\DB;
use App\Repositories\OrderCardRepository;
use App\Http\Services\MasterService;

class OrderCardService
{

    /** @var  OrderCardRepository */
    public $orderCardRepository;
    private $robusMasterTransaction;

    public function __construct(
        OrderCardRepository $orderCardReposi,
        MasterService $robusMasterTransaction
    ) {
        $this->orderCardRepository = $orderCardReposi;
        $this->robusMasterTransaction = $robusMasterTransaction;
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

        $master_id = $this->robusMasterTransaction->mastersId();


        if (robus()->is_under_distributer) {


            $valuer_master =  $master_id ? $percent_robus->percent_masters  : 0;

            $valuer_deposit = (float) $percent_robus->percent_merchands - (float) $valuer_master;

            // Convertion in devise Use send
            $convertion_commission = $this->convertAdminCommission($valuer_deposit);

            $convertion_commission_master = $this->convertAdminCommission($valuer_master);

            $admin_valeur =  $amount_commission -  $percent_robus->percent_merchands;

            $walletOrderCard->update([
                'commissions' => DB::raw('commissions+'  .  $convertion_commission),
                'commission_masters' => DB::raw('commission_masters+'  .  $convertion_commission_master),
            ]);


            if ($admin_civ->count() > 0) {

                $nmbre_admin = $admin_civ->count();

                $valeur_single = $admin_valeur / $nmbre_admin;

                foreach ($admin_civ as $user) {
                    $admin_single = SidUser::find($user->id);

                    AdminsCom::create([
                        'amount' =>   $admin_valeur,
                        'sid_user_id' =>   $admin_single->id,
                        'bank_card_id' =>  $walletOrderCard->id,
                        'description' => 'Commission administrateur',
                        'amount_under' =>  robus()->is_under_distributer ? $valuer_deposit : 0,
                        'description_underdistributer' =>  robus()->is_under_distributer ? 'Paiement commission sous-distributer' : null
                    ]);
                }
            }
        } else {

            $valuer_master =  $master_id ? $percent_robus->percent_masters  : 0;

            $admin_valeur = (float) $amount_commission - (float) $valuer_master;


            $convertion_commission_master = $this->convertAdminCommission($valuer_master);


            $walletOrderCard->update([
                'commission_masters' => DB::raw('commission_masters+'  .  $convertion_commission_master),
            ]);
        }


        $admin_valeur_convertion = $this->convertAdminCommission($admin_valeur);


        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);


        //  Send valeur in Admin
        if ($admin_civ->count() > 0) {

            $count_admin = $admin_civ->count();

            $valeur_single = $admin_valeur_convertion / $count_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                AdminsCom::create([
                    'amount' =>   $valeur_single,
                    'sid_user_id' =>   $admin_single->id,
                    'bank_card_id' =>  $walletOrderCard->id,
                    'description' => 'Commission administrateur',
                ]);
            }
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
