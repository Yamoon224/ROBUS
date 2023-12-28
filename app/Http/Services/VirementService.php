<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\DB;
use App\Repositories\VirementRepository;

class VirementService
{

    /** @var  VirementRepository */
    public $virementRepository;

    public function __construct(VirementRepository $virementReposi)
    {
        $this->virementRepository = $virementReposi;
    }


    public function createRobusVirement($amount_valeur , $amount_send, $provider, $description, $percent_robus, $number_compte, $firstname, $lastname, $comission_distributer, $phone , $amountReceved)
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

    public function updateamountVirementReceveid($admin_civ, $amount_user, $walletBank , $amount_valeur)
    {

        if (robus()->is_under_distributer) {

            $valuer_deposit = $amount_user->comission_distributer;

            $walletBank->update([
                'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
            ]);

        } else {

            $valuer_deposit = $amount_user->amount;
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

                // $reloadAmount = robus()->is_under_distributer ? 'amount-' : 'amount+';
                // $admin_single->wallet->update([
                //     'reload_by' => robus()->firstname . ' ' . robus()->lastname,
                //     'sid_user_relod' => robus()->id,
                //     'amount' => DB::raw($reloadAmount . $valeur_single),
                // ]);
            }
        }

        return true;
    }
}
