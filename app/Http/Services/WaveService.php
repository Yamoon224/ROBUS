<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use App\Repositories\WaveRepository;
use Illuminate\Support\Facades\DB;

class WaveService
{

    /** @var  WaveRepository */
    public $waveRepository;

    public function __construct(WaveRepository $waveRepository)
    {
        $this->waveRepository = $waveRepository;
    }


    public function createRobusWalletTransactionWave($amount_valeur, $provider, $percent_robus , $amountReceved)
    {

        $amount_commission = (float)$amount_valeur + (float)$percent_robus->percent * $amount_valeur;

        $amount_solde = (float)robus()->wallet->amount - (float)$amount_commission;


        return $this->waveRepository->create([
            'wallet_id' =>   robus()->wallet->id,
            'amount' => $amount_valeur,
            'description'  => 'Demande de depot UV par Wave',
            'reload_cost_id' => 1,
            'created_by' => robus()->id,
            'amount_solde'  => $amount_solde  ?? null,
            'provider_id'  =>  $provider,
            'costs_amount' => ($percent_robus->percent * $amount_valeur),
            'phone'  =>  robus()->phone,
            'full_name' =>  robus()->firstname . ' ' . robus()->lastname,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
        ]);
    }



    public function updateamountWaveReceveid($admin_civ, $percent_robus, $walletWave, $amount)
    {


        $amount_commission = $amount + ($percent_robus->percent * $amount);

        $amount_solde = robus()->wallet->amount - $amount_commission;

        $walletWave->update([
            'amount_solde' => $amount_solde,
        ]);

        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);


        $admin_valeur =  $amount * $percent_robus->percent;


        //  Send valeur in Admin
        if ($admin_civ->count() > 0) {

            $count_admin = $admin_civ->count();

            $valeur_single = $admin_valeur / $count_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                AdminsCom::create([
                    'amount' =>   $valeur_single,
                    'sid_user_id' =>   $admin_single->id,
                    'wallet_wave_id' => $walletWave->id,
                    'description' => 'Commission administrateur',
                ]);
            }
        }

        return true;
    }
}
