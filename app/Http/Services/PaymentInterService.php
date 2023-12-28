<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use App\Events\AdminNotifEvent;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\DB;
use App\Models\Wallets\WalletInter;
use App\Events\CreateWalletInterEvent;
use App\Repositories\IntersRepository;
use App\Notifications\UserNotification;
use App\Http\Services\ConvertionService;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Admin\MailController;

class PaymentInterService
{

    /** @var  IntersRepository */
    public $intersRepository;
    public $convertionService;

    public function __construct(IntersRepository $inter_Reposi, ConvertionService $convertionService)
    {
        $this->intersRepository = $inter_Reposi;
        $this->convertionService = $convertionService;
    }

    public function createRobusWalletTransactionPayment(
        $val_amount,
        $percent_robus,
        $country_robus,
        $provider,
        $id_invoice,
        $number_decoder,
        $number_phone,
        $amount_conversion,
        $number_contract,
        $paiementChoix_phone,
        $offers,
        $amount_receveid
    ) {
        $amount_solde = robus()->wallet->amount - $val_amount;

        return WalletInter::create([
            'amount_solde' => $amount_solde ?? null,
            'wallet_id' =>   robus()->wallet->id,
            'amount' => $val_amount,
            'description'  => "Credit telephonique",
            'inter_cost_id' => null,
            'provider_type' =>  5,
            'payment_cost_id' => $percent_robus->id,
            'number_decoder'   => $number_decoder ?? null,
            'id_invoice' => $id_invoice ?? null,
            'number_contract' => $number_contract ?? null,
            'name' => $name ?? null,
            'created_by' => robus()->id,
            'provider_id'  =>   $provider,
            'number_phone' => $number_phone ?? null,
            'offer_id'  =>  $offers->id ?? null,
            'status'  => "PENDING",
            'costs_amount' =>  $amount_conversion,
            'country_id'  =>   $country_robus->id,
            'paiementChoix_phone'  => $paiementChoix_phone ?? null,
            'amount_received' =>  $amount_receveid ?? null,
        ]);
    }


    public function sendNotification($walletInter)
    {

        $msg_title = 'Demande de paiement Facture';
        $msg = 'Demande de paiement de facture est en cours de validation ';
        robus()->notify(
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg,
            ])
        );
        $notifs = robus()->unreadNotifications;
        event(new CreateWalletInterEvent($walletInter));
        $admins = $this->getAdminCIVProperty();
        Notification::send(
            $admins,
            new AdminNotification(
                'Demande de paiement de ' .
                    ' ' . $walletInter->wallet->sidUser->firstname
            )
        );
        Notification::send($admins, new AdminNotification('Paiement de facture de ' .
            ' ' . $walletInter->wallet->sidUser->firstname));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }
        }

        if (robus()->email != null) {
            $notify = new MailController();
            $notify->newPaiementInter($walletInter);
        }

        return true;
    }

    public function updateAmountPaymentReceveid($admin_civ, $percent_robus, $walletPayment, $amount)
    {

        $amount_solde = robus()->wallet->amount - $amount;

        $amount_commission =  $amount;

        if (robus()->is_under_distributer) {

            $valuer_deposit_amount =  $percent_robus->comission_auther;

            $valuer_deposit =  $this->convertionService->convertAdminCommissionInvoice($valuer_deposit_amount);

            $admin_valeur =  $percent_robus->amount_auther -  $percent_robus->comission_auther;

            $walletPayment->update([
                'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
            ]);

           
        } else {

            $admin_valeur =  $percent_robus->amount_auther;
        }

        $walletPayment->update([
            'amount_solde' => $amount_solde,
        ]);


        // $admin_valeur_convertion =  $this->convertionService->convertAdminCommission($admin_valeur);

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
                    'wallet_inter_id' => $walletPayment->id,
                    'description' => 'Commission administrateur',
                    'amount_under'=>  robus()->is_under_distributer ? $valuer_deposit : 0,
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

    public function updateAmountPaymentOfferReceveid($admin_civ, $percent_robus, $walletPayment, $amount)
    {

        $amount_solde = robus()->wallet->amount - $amount;

        $amount_commission =  $amount;

        if (robus()->is_under_distributer) {

            $valuer_deposit_amount =  $percent_robus->comission_auther;

            $valuer_deposit =  $this->convertionService->convertAdminCommissionInvoice($valuer_deposit_amount);

            $admin_valeur =  $percent_robus->amount_auther -  $percent_robus->comission_auther;

            $walletPayment->update([
                'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
            ]);

           
        } else {

            $admin_valeur =  $percent_robus->amount_canal;
        }

        $walletPayment->update([
            'amount_solde' => $amount_solde,
        ]);

        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);

        $admin_valeur_convertion =  $this->convertionService->convertAdminCommission($admin_valeur);

        //  Send valeur in Admin
        if ($admin_civ->count() > 0) {

            $count_admin = $admin_civ->count();

            $valeur_single = $admin_valeur / $count_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                AdminsCom::create([
                    'amount' =>   $valeur_single,
                    'sid_user_id' =>   $admin_single->id,
                    'wallet_inter_id' => $walletPayment->id,
                    'description' => 'Commission administrateur',
                    'amount_under'=>  robus()->is_under_distributer ? $valuer_deposit : 0,
                    'description_underdistributer' =>  robus()->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ]);
            }
        }

        return true;
    }


    public function getAdminCIVProperty()
    {

        $admin_civ =  SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

        return  $admin_civ;
    }
}
