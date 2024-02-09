<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\DB;
use App\Repositories\ReloadRepository;
use App\Repositories\ReloadCardRepository;
use App\Http\Services\MasterService;

class ReloadCardService
{

    /** @var  ReloadCardRepository */
    public $reloadCardRepository;
    private $robusMasterTransaction;

    public function __construct(ReloadCardRepository $reloadCardReposi ,
    MasterService $robusMasterTransaction
    )
    {
        $this->reloadCardRepository = $reloadCardReposi;
        $this->robusMasterTransaction = $robusMasterTransaction;
    }


    public function createRobusWalletReloadCard($amount_valeur, $provider, $percent_robus, $amount_cost, $num_carte, $name, $amountReceved)
    {

        $amount_solde = robus()->wallet->amount;

        return  $this->reloadCardRepository->create([
            'amount_solde' => $amount_solde ?? null,
            'wallet_id' =>  robus()->wallet->id,
            'amount' => $amount_valeur,
            'description'  => $description ?? 'Demande de rechargement de carte',
            'num_carte'  => $num_carte,
            'name'  => $name,
            'deposit_cost_id' => $percent_robus->id,
            'costs_amount' =>  $amount_cost,
            'created_by' => robus()->id,
            'provider_id'  =>  $provider,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
        ]);
    }


    public function updateamountReloadCardReceveid($admin_civ, $percent_merchands, $walletOrderCard, $amount_commission, $amount_verify , $amount_master)
    {

        $amount_solde = robus()->wallet->amount - $amount_verify;

        $master_id = $this->robusMasterTransaction->mastersId();


        if (robus()->is_under_distributer) {

            $valuer_deposit =  $percent_merchands;

            $admin_valeur =  $valuer_deposit;

            $walletOrderCard->update([
                'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
            ]);

            $walletOrderCard->update([
                'commission_masters' => DB::raw('commission_masters+'  .  $amount_master),
            ]);

        } else {

            $admin_valeur =  $amount_commission;

            $walletOrderCard->update([
                'commission_masters' => DB::raw('commission_masters+'  .  $amount_master),
            ]);
        }


        $walletOrderCard->update([
            'amount_solde' => $amount_solde,
        ]);

        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_verify),
        ]);


        if ($admin_civ->count() > 0) {

            $count_admin = $admin_civ->count();
            $valeur_single = $admin_valeur / $count_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $description = robus()->is_under_distributer
                    ? 'Paiement commission sous-distributer'
                    : 'Commission administrateur';

                AdminsCom::create([
                    'amount' => $valeur_single,
                    'sid_user_id' => $admin_single->id,
                    'wallet_deposit_id' =>  $walletOrderCard->id,
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
