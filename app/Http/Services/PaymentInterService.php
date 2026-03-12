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
use App\Http\Services\OperatorRestrictionService;

class PaymentInterService
{

    /** @var  IntersRepository */
    public $intersRepository;
    public $convertionService;
    protected $operatorRestrictionService;
    protected $commissionServices;

    public function __construct(
        IntersRepository $inter_Reposi,
        ConvertionService $convertionService,
        OperatorRestrictionService $operatorRestrictionService,
        CommissionService $commissionServices

    ) {
        $this->intersRepository = $inter_Reposi;
        $this->convertionService = $convertionService;
        $this->operatorRestrictionService = $operatorRestrictionService;
        $this->commissionServices = $commissionServices;
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
        $facture_choix_type,
        $offers,
        $amount_receveid,
        $name
    ) {
        $amount_solde = robus()->wallet->amount - $val_amount;

        return WalletInter::create([
            'amount_solde' => $amount_solde ?? null,
            'wallet_id' =>   robus()->wallet->id,
            'balance_before' =>  robus()->wallet->amount,
            'amount' => $val_amount,
            'description'  => "Paiement facture",
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
            'factureChoix_type' =>  $facture_choix_type ?? null,
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
        $userId = robus()->id;
        $activeButtons = $this->operatorRestrictionService->getActiveButtons($userId);
        $account_accepted = robus()->is_accepted_companie ?? false;
        $company_id = robus()->company_id ?? null;
        if ($company_id) {
            $compagnie = SidUser::whereIsCompany(true)->where('company_id', $company_id)->first();
            $sidUserId = $account_accepted && $compagnie ? $compagnie->id : null;
        } else {
            $compagnie = null;
            $sidUserId = null;
        }

        if ($sidUserId) {
            $activeCButtons = $this->operatorRestrictionService->getActiveCButtons($sidUserId);
        } else {
            $activeCButtons = [];
        }

        $walletPayment->update([
            'amount_solde' => $amount_solde,
        ]);

        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);

        if (robus()->is_under_distributer || robus()->is_api) {

            $valuer_deposit_amount =  $percent_robus->comission_auther;
            $valuer_companie = $percent_robus->percent_companie;
            $banque_valeur =  (float)  $percent_robus->bank_comission;

            $valuer_deposit =  $this->convertionService->convertAdminCommissionInvoice($valuer_deposit_amount);
            $amountCompConvert =  $this->convertionService->convertAdminCommissionInvoice($valuer_companie);

            $admin_valeur = (float) $percent_robus->amount_auther - (float)($percent_robus->comission_auther + $banque_valeur);

            if (!empty($activeButtons['showRemonterPaiementButton'])) {
                $walletPayment->update([
                    'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
                ]);

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterPaiementCButton'])) {

                    $walletPayment->increment('commission_companie', $amountCompConvert);

                    $valuer_admin_compagnie = (float)  $admin_valeur  - ((float) $valuer_deposit_amount + (float)$valuer_companie + (float)$banque_valeur);

                    $this->commissionServices->distributeAdminCommissionUnderBankAcceptedCompanies($admin_civ, 'wallet_inter_id', $walletPayment->id,  $valuer_admin_compagnie, $valuer_companie, $valuer_deposit, $banque_valeur);
                } else {

                    $valor_commission = $percent_robus->amount_auther;
                    $admin_sender_valeur =  (float) $valor_commission - ((float)$valuer_deposit_amount + (float)$banque_valeur);
                    $this->commissionServices->distributeAdminNoCompagniePaimentCommissions($admin_civ, 'wallet_inter_id', $walletPayment->id, $admin_sender_valeur, $valuer_deposit_amount, $banque_valeur);
                }
            } else {
                $banque_valeur =  (float)  $percent_robus->bank_comission;
                $admin_valeur =  (float)  $percent_robus->amount_auther -  (float) $banque_valeur;
                $this->commissionServices->distributeAdminPaimentCommissions($admin_civ, 'wallet_inter_id', $walletPayment->id, $admin_valeur, $banque_valeur);
            }
        } else {

            $banque_valeur =  (float)  $percent_robus->bank_comission;
            $admin_valeur =  (float)  $percent_robus->amount_auther -  $banque_valeur;

            $this->commissionServices->distributeAdminPaimentCommissions($admin_civ, 'wallet_inter_id', $walletPayment->id, $admin_valeur, $banque_valeur);
        }


        return true;
    }


    public function updateAmountPaymentOfferReceveid($admin_civ, $percent_robus, $walletPayment, $amount)
    {

        $amount_solde = robus()->wallet->amount - $amount;
        $userId = robus()->id;
        $activeButtons = $this->operatorRestrictionService->getActiveButtons($userId);
        $account_accepted = robus()->is_accepted_companie ?? false;
        $company_id = robus()->company_id ?? null;
        if ($company_id) {
            $compagnie = SidUser::whereIsCompany(true)->where('company_id', $company_id)->first();
            $sidUserId = $account_accepted && $compagnie ? $compagnie->id : null;
        } else {
            $compagnie = null;
            $sidUserId = null;
        }

        if ($sidUserId) {
            $activeCButtons = $this->operatorRestrictionService->getActiveCButtons($sidUserId);
        } else {
            $activeCButtons = [];
        }

        $walletPayment->update([
            'amount_solde' => $amount_solde,
        ]);

        $amount_commission =  $amount;


        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);


        if (robus()->is_under_distributer || robus()->is_api) {

            $valuer_companie = $percent_robus->percent_companie;
            $valuer_deposit_amount =  $percent_robus->comission_canal;
            $valuer_deposit =  $this->convertionService->convertAdminCommissionInvoice($valuer_deposit_amount);
            $amountCompConvert =  $this->convertionService->convertAdminCommissionInvoice($valuer_companie);
            $admin_nocomission =  $percent_robus->amount_canal;

            $admin_compagnie_valeur =  $percent_robus->amount_canal -  ($percent_robus->comission_canal + $valuer_companie);
            $admin_no_compagnie =  $percent_robus->amount_canal -  ($percent_robus->comission_canal + $valuer_companie);
            $banque_valeur =  0;

            if (!empty($activeButtons['showRemonterPaiementButton'])) {

                $walletPayment->update([
                    'commissions' => DB::raw('commissions+'  .  $valuer_deposit),
                ]);

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterPaiementCButton'])) {

                    $compagnie_amount =  $percent_robus->percent_companie +  $percent_robus->comission_auther;

                    $walletPayment->increment('commission_companie', $amountCompConvert);

                    $this->commissionServices->distributeAdminCommissionUnderBankAcceptedCompanies($admin_civ, 'wallet_inter_id', $walletPayment->id, $admin_compagnie_valeur, $compagnie_amount, $valuer_deposit_amount, $banque_valeur);
                } else {
                    $this->commissionServices->distributeAdminNoCompagniePaimentCommissions($admin_civ, 'wallet_inter_id', $walletPayment->id, $admin_no_compagnie, $valuer_deposit_amount, $banque_valeur);
                }
            } else {

                $admin_valeur =   $percent_robus->comission_canal;
                $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_inter_id', $walletPayment->id, $admin_valeur);
            }
        } else {

            $admin_nocomission =   $percent_robus->amount_canal;
            $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_inter_id', $walletPayment->id, $admin_nocomission);
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
