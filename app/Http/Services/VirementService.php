<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\DB;
use App\Http\Services\MasterService;
use App\Repositories\VirementRepository;

class VirementService
{

    /** @var  VirementRepository */
    public $virementRepository;
    private $robusMasterTransaction;

    public function __construct(
        VirementRepository $virementReposi,
        MasterService $robusMasterTransaction
    ) {
        $this->virementRepository = $virementReposi;
        $this->robusMasterTransaction = $robusMasterTransaction;
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

        $master_id = $this->robusMasterTransaction->mastersId();

        if (robus()->is_under_distributer) {

            $valuer_master =  $master_id ? $amount_user->percent_masters  : 0;
            $valuer_deposit =  $amount_user->comission_distributer;
            $amount_deposit_under = (float) $valuer_deposit -  (float) $valuer_master;

            $walletBank->update([
                'commissions' => DB::raw('commissions+'  .  $amount_deposit_under),
                'commission_masters' => DB::raw('commission_masters+'  .  $valuer_master),
            ]);
        } else {

            $valuer_master =  $master_id ? $amount_user->percent_masters  : 0;

            $valuer_deposit = (float) $amount_user->amount - (float) $valuer_master;

            $walletBank->update([
                'commission_masters' => DB::raw('commission_masters+'  .  $valuer_master),
            ]);
        }


        $amount_commission = $amount_valeur;

        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);

        if ($admin_civ->count() > 0) {

            $count_admin = $admin_civ->count();
            $valeur_single = $valuer_deposit / $count_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $description = robus()->is_under_distributer
                    ? 'Paiement commission sous-distributer'
                    : 'Commission administrateur';

                AdminsCom::create([
                    'amount' => $valeur_single,
                    'sid_user_id' => $admin_single->id,
                    'wallet_transaction_id' => null,
                    'wallet_bank_id' => $walletBank->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  robus()->is_under_distributer ? $valuer_deposit : 0,
                    'description_underdistributer' =>  robus()->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ]);

            }
        }

        return true;
    }
}
