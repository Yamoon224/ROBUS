<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\DB;
use App\Repositories\PaymentRepository;

class PaymentService
{

    /** @var  PaymentRepository */
    public $paiementRepository;
    private $robusMasterTransaction;

    public function __construct(
        PaymentRepository $paiementReposi,
        MasterService $robusMasterTransaction
    ) {
        $this->paiementRepository = $paiementReposi;
        $this->robusMasterTransaction = $robusMasterTransaction;
    }


    public function createRobusWalletTransactionPayment(
        $amount_valeur,
        $provider,
        $percent_robus,
        $number_phone,
        $offers,
        $paiementChoix_phone,
        $facture_choix_type,
        $number_decoder,
        $id_invoice,
        $number_contract
    ) {

        $amount_solde = robus()->wallet->amount - $amount_valeur;

        return $this->paiementRepository->create([
            'wallet_id' =>  robus()->wallet->id,
            'amount' => $amount_valeur,
            'amount_solde' => $amount_solde ?? null,
            'description'  => "Paiement de facture CIE",
            'payment_cost_id' => $percent_robus->id,
            'number_decoder'   => $number_decoder ?? null,
            'id_invoice' => $id_invoice ?? null,
            'number_contract' => $number_contract ?? null,
            'name' => $name ?? null,
            'created_by' => robus()->id,
            'provider_id'  =>    $provider,
            'number_phone' => $number_phone ?? null,
            'costs_amount' => $percent_robus->amount_auther ?? null,
            'offer_id'  =>  $offers->id ?? null,
            'status'  => "PENDING",
            'paiementChoix_phone'  => $paiementChoix_phone ?? null,
            'factureChoix_type' =>  $facture_choix_type,
            'phone'  => null,
        ]);
    }

    public function createRobusWalletTransactionPaymentOffer(
        $amount_valeur,
        $provider,
        $percent_robus,
        $number_phone,
        $offers,
        $paiementChoix_phone,
        $facture_choix_type,
        $number_decoder,
        $id_invoice,
        $number_contract
    ) {

        $amount_solde = robus()->wallet->amount - $amount_valeur;

        return $this->paiementRepository->create([
            'wallet_id' =>  robus()->wallet->id,
            'amount' => $amount_valeur,
            'amount_solde' => $amount_solde ?? null,
            'description'  => "Paiement Canal",
            'payment_cost_id' => $percent_robus->id,
            'number_decoder'   => $number_decoder ?? null,
            'id_invoice' => $id_invoice ?? null,
            'number_contract' => $number_contract ?? null,
            'name' => $name ?? null,
            'created_by' => robus()->id,
            'provider_id'  =>    $provider,
            'number_phone' => $number_phone ?? null,
            'costs_amount' => $percent_robus->amount_canal ?? null,
            'offer_id'  =>  $offers->id ?? null,
            'status'  => "PENDING",
            'paiementChoix_phone'  => $paiementChoix_phone ?? null,
            'factureChoix_type' =>  $facture_choix_type,
            'phone'  => null,
        ]);
    }

    public function updateAmountPaymentReceveid($admin_civ, $percent_robus, $walletPayment, $amount)
    {

        $amount_commission =  $amount;

        $amount_solde = robus()->wallet->amount - $amount;

        $master_id = $this->robusMasterTransaction->mastersId();


        if (robus()->is_under_distributer) {

            $valuer_deposit =  $percent_robus->comission_auther;

            $admin_valeur =  $percent_robus->amount_auther -  $percent_robus->comission_auther;

            $valuer_master =  $master_id ? $percent_robus->percent_masters  : 0;

            $amount_rec_under = (float)  $valuer_deposit - (float) $valuer_master;

            $walletPayment->update([
                'commissions' => $amount_rec_under,
            ]);

            $walletPayment->increment('commission_masters', $valuer_master);
        } else {

            $valuer_master =  $master_id ? $percent_robus->percent_masters : 0;

            $amount_rec_admin =  $percent_robus->amount_auther;

            $admin_valeur =  (float) $amount_rec_admin - (float) $valuer_master;

            $walletPayment->increment('commission_masters', $valuer_master);
        }

        $walletPayment->update([
            'amount_solde' => $amount_solde,
        ]);

        //  Send valeur in Admin
        if ($admin_civ->count() > 0) {

            $count_admin = $admin_civ->count();

            $valeur_single = $admin_valeur / $count_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                AdminsCom::create([
                    'amount' =>   $valeur_single,
                    'sid_user_id' =>   $admin_single->id,
                    'wallet_payment_id' => $walletPayment->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  robus()->is_under_distributer ? $valuer_deposit : 0,
                    'description_underdistributer' =>  robus()->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ]);

                // $admin_single->wallet->update([
                //     'reload_by' => robus()->firstname . ' ' . robus()->lastname,
                //     'sid_user_relod' => robus()->id,
                //     'amount' => DB::raw('amount+' . $valeur_single),
                // ]);
            }
        }

        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);



        return true;
    }

    public function updateAmountPaymentOfferReceveid($admin_civ, $percent_robus, $walletPayment, $amount)
    {

        $amount_commission =  $amount;

        $amount_solde = robus()->wallet->amount - $amount;

        $master_id = $this->robusMasterTransaction->mastersId();

        if (robus()->is_under_distributer) {

            $valuer_deposit =  $percent_robus->comission_canal;

            $admin_valeur =  $percent_robus->amount_canal -  $percent_robus->comission_canal;

            $valuer_master =  $master_id ? $percent_robus->percent_masters  : 0;

            $amount_rec_under = (float)  $valuer_deposit - (float) $valuer_master;

            $walletPayment->update([
                'commissions' => DB::raw('commissions+'  .  $amount_rec_under),
            ]);

            $walletPayment->increment('commission_masters', $valuer_master);
        } else {

            $valuer_master =  $master_id ? $percent_robus->percent_masters : 0;

            $amount_rec_admin =  $percent_robus->amount_canal;

            $admin_valeur =  (float) $amount_rec_admin - (float) $valuer_master;

            $walletPayment->increment('commission_masters', $valuer_master);
        }

        $walletPayment->update([
            'amount_solde' => $amount_solde,
        ]);


        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);


        //  Send valeur in Admin
        if ($admin_civ->count() > 0) {

            $count_admin = $admin_civ->count();

            $valeur_single = $admin_valeur / $count_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                AdminsCom::create([
                    'amount' =>   $valeur_single,
                    'sid_user_id' =>   $admin_single->id,
                    'wallet_payment_id' => $walletPayment->id,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  robus()->is_under_distributer ? $valuer_deposit : 0,
                    'description_underdistributer' =>  robus()->is_under_distributer ? 'Paiement commission sous-distributer' : null
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
