<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\DB;
use App\Models\Wallets\WalletInter;
use App\Http\Services\ConvertionService;
use App\Repositories\IntersRepository;

class VirementInterService
{


    /** @var  IntersRepository */
    public $intersRepository;
    public $convertionService;

    public function __construct(IntersRepository $inter_Reposi, ConvertionService $convertionService)
    {
        $this->intersRepository = $inter_Reposi;
        $this->convertionService = $convertionService;
    }

    public function createRobusWalletTransactionOrderCard(
        $val_amount,
        $account_number,
        $country_robus,
        $provider,
        $amount_user,
        $name,
        $amount_receveid
    ) {
        $amount_solde = robus()->wallet->amount - $val_amount;

        return $this->intersRepository->create(
            [

                'amount_solde' => $amount_solde ?? null,
                'wallet_id' => robus()->wallet->id,
                'amount' => $val_amount,
                'inter_cost_id' => null,
                'deposit_cost_id' => 1,
                'number_compte' =>  null,
                'created_by' => robus()->id,
                'provider_id' =>  $provider,
                'number_card' =>  $account_number,
                'country_id' => $country_robus->id,
                'name' => $name,
                'provider_type' => 8,
                'costs_amount' => $amount_user,
                'status' => 'PENDING',
                'amount_received' =>  $amount_receveid ?? null,

            ]
        );
    }


    public function createRobusWalletTransactionVirement(
        $val_amount,
        $account_number,
        $country_robus,
        $provider,
        $amount_user,
        $phone,
        $firstname,
        $lastname,
        $amount_receveid
    ) {
        $amount_solde = robus()->wallet->amount - $val_amount;

        return WalletInter::create([
            'wallet_id' => robus()->wallet->id,
            'amount_solde' => $amount_solde ?? null,
            'amount' => $val_amount,
            'inter_cost_id' => null,
            'bank_cost_id' => 1,
            'phone' => $phone,
            'number_compte' => $account_number,
            'created_by' => robus()->id,
            'provider_id' => $provider,
            'status' => 'PENDING',
            'country_id' => $country_robus->id,
            'provider_type' => 7,
            'firstname' =>  $firstname ??  null,
            'lastname' => $lastname ?? null,
            'costs_amount' =>  $amount_user,
            'amount_received' =>  $amount_receveid ?? null,
        ]);
    }

    public function updateamountVirementInterReceveid($admin_civ, $amount_user, $walletBank, $amount_valeur, $percent_valeur)
    {

        $amount_solde = robus()->wallet->amount - $amount_valeur;

        if (robus()->is_under_distributer) {

            $valuer_deposit = $amount_user;

            $walletBank->update([
                'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
            ]);


            // $amount_commission = $amount_valeur;

            $admin_valeur_convertion =  $this->convertionService->convertAdminCommission($valuer_deposit);

            $valeur_amount_admin =  (float) $percent_valeur  -  (float)$admin_valeur_convertion;

        } else {

            $valuer_deposit = $amount_user;

            // $amount_commission = $amount_valeur;

            $valeur_amount_admin = $percent_valeur ;

            $admin_valeur_convertion =  $this->convertionService->convertAdminCommission($valuer_deposit);

        }

        $walletBank->update([
            'amount_solde' => $amount_solde,
        ]);

        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_valeur),
        ]);

        if ($admin_civ->count() > 0) {

            $count_admin = $admin_civ->count();
            $valeur_single = $valeur_amount_admin / $count_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                $description = robus()->is_under_distributer
                    ? 'Paiement commission sous-distributer'
                    : 'Commission administrateur';

                AdminsCom::create([
                    'amount' => $valeur_single,
                    'sid_user_id' => $admin_single->id,
                    'wallet_inter_id' => $walletBank->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  robus()->is_under_distributer ? $admin_valeur_convertion : 0,
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