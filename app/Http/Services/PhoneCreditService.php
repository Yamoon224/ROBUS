<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\DB;
use App\Repositories\VirementRepository;
use App\Repositories\PhoneCreditRepository;

class PhoneCreditService
{

    /** @var  PhoneCreditRepository */
    public $phoneCreditRepository;

    public function __construct(PhoneCreditRepository $phoneCreditReposi)
    {
        $this->phoneCreditRepository = $phoneCreditReposi;
    }


    public function createRobusWalletTransactionPhone($amount_valeur, $provider, $percent_robus, $phone, $amountReceved)
    {

        $amount_solde = robus()->wallet->amount - $amount_valeur;

        return $this->phoneCreditRepository->create([
            'wallet_id' =>   robus()->wallet->id,
            'amount' => $amount_valeur,
            'description'  => 'Demande de rechargement de credit',
            'phone_credit_cost_id' => 1,
            'created_by' => robus()->id,
            'amount_solde'  => $amount_solde ?? null,
            'provider_id'  =>  $provider,
            'costs_amount' => ($percent_robus->percent * $amount_valeur),
            'phone'  =>  robus()->country->indecative . $phone,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
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
                    'wallet_phone_credit_id' => $walletPhone->id,
                    'description' => 'Commission administrateur',
                ]);

                // $admin_single->wallet->update([
                //     'reload_by' => robus()->firstname . ' ' . robus()->lastname,
                //     'sid_user_relod' => robus()->id,
                //     'amount' => DB::raw('amount+' . $valeur_single),
                // ]);
            }
        }

        return true;
    }
}
