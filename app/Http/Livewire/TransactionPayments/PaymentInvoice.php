<?php

namespace App\Http\Livewire\TransactionPayments;

use App\Models\Offer;
use Livewire\Component;
use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use App\Models\Banks\BankCard;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Events\AdminNotifEvent;
use App\Models\Costs\PaymentCost;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use App\Models\Wallets\WalletBank;
use Illuminate\Support\Facades\DB;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Http;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Events\CreateWalletInterEvent;
use App\Notifications\UserNotification;
use App\Notifications\AdminNotification;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Admin\MailController;

class PaymentInvoice extends Component
{

    public $paiement_id_invoice_inter;
    public $paiement_name_cie_inter;
    public $paiement_amount_total_inter;
    public $provider_payment_inter;
    public $paiement_name_sodecie_inter;
    public $paiement_num_contrat_inter;
    public $paiement_amount_t_sodecie_inter;
    public $offer_paiement;
    public $country_inter_robus;
    public $paiementChoix_phone;
    public $facture_choix_type;
    public $paiement_phone_canal_inter;
    public $paiement_decoder_canal_inter;
    public $pre_phone_invoice_inter;
    public $pre_id_invoice_inter;
    public $interId;

    public function resetValeurReload()
    {
        $this->pre_id_invoice_inter = '';
        $this->paiement_id_invoice_inter = '';
        $this->paiement_name_cie_inter = '';
        $this->paiement_amount_total_inter = '';
        $this->provider_payment_inter = null;
        $this->paiement_phone_canal_inter  = null;
        $this->paiement_name_sodecie_inter = '';
        $this->paiement_num_contrat_inter = '';
        $this->paiement_amount_t_sodecie_inter = '';
        $this->offer_paiement = null;
        $this->country_inter_robus = null;
        $this->paiementChoix_phone = null;
        $this->facture_choix_type = null;
        $this->pre_phone_invoice_inter = '';
        $this->paiement_decoder_canal_inter = '';
        // $this->emit('modal-hide-transfers-validated');
        // $this->emit('modal-hide-transfers');
    }

    public function resetInterField()
    {
        $this->paiement_id_invoice_inter = '';
        $this->paiement_name_cie_inter = '';
        $this->paiement_amount_total_inter = '';
        $this->provider_payment_inter = null;
        $this->paiement_name_sodecie_inter = '';
        $this->paiement_num_contrat_inter = '';
        $this->paiement_amount_t_sodecie_inter = '';
        $this->paiement_phone_canal_inter  = null;
        $this->offer_paiement = null;
        $this->pre_phone_invoice_inter = '';
        $this->country_inter_robus = null;
        $this->paiementChoix_phone = null;
        $this->facture_choix_type = null;
        $this->paiement_decoder_canal_inter = '';
        $this->emit('menu-inters-active');
        $this->emit('modal-hide-inters');
        $this->emit('transaction_inters');
    }
    public function mount()
    {
        $this->resetValeurReload();
        // $this->emit('monEvenement', $resetValeurReload);
    }

    public function cancelInterField()
    {
        $this->paiement_id_invoice_inter = '';
        $this->paiement_decoder_canal_inter = '';
        $this->paiement_name_cie_inter = '';
        $this->pre_phone_invoice_inter = '';
        $this->paiement_amount_total_inter = '';
        $this->provider_payment_inter = null;
        $this->paiement_name_sodecie_inter = '';
        $this->paiement_num_contrat_inter = '';
        $this->paiement_amount_t_sodecie_inter = '';
        $this->paiement_phone_canal_inter  = null;
        $this->offer_paiement = null;
        $this->country_inter_robus = null;
        $this->paiementChoix_phone = null;
        $this->facture_choix_type = null;
        $this->emit('menu-inters-active');
        $this->emit('modal-hide-inters');
        $this->emit('transaction_inters');
    }

    public function addInterInvoice()
    {
        $check = WalletInter::whereId($this->interId)->first();
        $country_robus = Country::whereId($this->country_inter_robus)
            // ->whereStatus(true)
            ->first();

        $country_civ = Country::whereId(107)
            // ->whereStatus(true)
            ->first();

        $offer = Offer::whereId($this->offer_paiement)->first();

        $provider_payment = Provider::whereId($this->provider_payment_inter)->first();

        $admin_civ = $this->getAdminCIVProperty();
        $som_transfers =  $this->getSumValTransactionProperty();

        // Valuer amount cota
        $valeur_ceiling = sabdy()->silver_ceiling;

        try {

            if (!$check) {


                // Facture paiements  Providers CIE, SODECI, CANAL+
                if ($this->provider_payment_inter == 53) {

                    if ($this->facture_choix_type == 1) {

                        $this->validate(
                            [
                                'paiement_id_invoice_inter' => 'required',
                                'paiement_name_cie_inter' => 'required',
                                'paiement_amount_total_inter' => 'required',
                            ],
                            [
                                'paiement_amount_total_inter.required' =>
                                'Votre montant est obligatoire',
                                'paiement_name_cie_inter.required' =>
                                'Nom  est obligatoire',
                                'paiement_id_invoice_inter.required' =>
                                'Numero  est obligatoire',
                            ]
                        );

                        $percent_robus = PaymentCost::whereId(1)->first();

                        // Conversion in Price amount
                        $amount_conversion = $percent_robus->amount_auther;

                        if (sabdy()->wallet->currencie->value >  $country_civ->currencie->value) {
                            // Convertion valeur en Devise user Auth
                            $valeur_amount =  $amount_conversion /  sabdy()->wallet->currencie->value;

                            if ($som_transfers >= $valeur_ceiling) {
                                $this->resetInterField();
                                $this->emit('modal-hide-inters');
                                $this->emit(
                                    'warning',
                                    'Votre montant journier est atteint !'
                                );
                            } else {

                                $val_amount = $this->paiement_amount_total_inter + $valeur_amount;

                                if ($val_amount > sabdy()->wallet->amount) {
                                    $this->resetInterField();
                                    $this->emit('modal-hide-inters');
                                    $this->emit('error', 'Verifier votre montant  svp ou votre pays !');
                                } else {


                                    $amount_solde =  sabdy()->wallet->amount -  $val_amount;

                                    // Create Wallet Inter
                                    $walletInter = WalletInter::create([
                                        'wallet_id' => sabdy()->wallet->id,
                                        'amount' =>  $this->paiement_amount_total_inter,
                                        'inter_cost_id' => null,
                                        'payment_cost_id' => 1,
                                        'phone' =>    $country_robus->indecative . $this->paiement_phone_canal_inter ?? null,
                                        'number_compte' => $this->account_number_inter ?? null,
                                        'created_by' => sabdy()->id,
                                        'provider_id' => $provider_payment->id,
                                        'status' => 'PENDING',
                                        'country_id' =>  $country_robus->id,
                                        'number_decoder' => $this->paiement_decoder_canal ?? null,
                                        'id_invoice' => $this->paiement_id_invoice_inter,
                                        'number_contract' => $this->paiement_num_contrat_inter ?? null,
                                        'phone_invoice' => $this->pre_phone_invoice_inter ??  null,
                                        'id_invoice_pre' =>  $this->pre_id_invoice_inter ?? null,
                                        'name' =>  $this->paiement_name_cie_inter ?? $this->paiement_name_sodecie_inter ?? null,
                                        'provider_type' => 5,
                                        'number_phone' => $this->paiement_phone_canal ?? null,
                                        'offer_id' => null,
                                        'costs_amount' =>  $amount_conversion,
                                        'status' => 'PENDING',
                                        'paiementChoix_phone' => $this->paiementChoix_phone ?? null,
                                        'paiementChoix_type' => $this->paiementChoix_type ?? null,
                                        'factureChoix_type' => $this->facture_choix_type ?? null,
                                        'amount_solde'  =>   $amount_solde ?? null
                                    ]);

                                    $commission = $walletInter->amount + $valeur_amount;

                                    sabdy()->wallet->update([
                                        'amount' => DB::raw('amount-' . $commission),
                                    ]);

                                    // Commissions Inters UnderDistributer
                                    if (sabdy()->is_under_distributer == 1) {

                                        // Admin Comission 

                                        $valeur_com_admin = $amount_conversion -  $percent_robus->comission_auther;

                                        // Commission distributer 

                                        $valuer_deposit =     $percent_robus->comission_auther / sabdy()->wallet->currencie->value;

                                        $walletInter->update([
                                            'commissions' => DB::raw('commissions+'  . $valuer_deposit),
                                        ]);

                                        $walletInter->update([
                                            'is_send' => 1,
                                        ]);

                                        // send comission valeur admin 

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_com_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }
                                        if (sabdy()->country->code == 'CI') {

                                            $message_pre = 'Votre code de la facture prepéyé est ' . $walletInter->id_invoice_pre;

                                            Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$walletInter->phone_invoice&from=ROBUS&sms=$message_pre");
                                            $message = 'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie
                                                ->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }

                                        // Notifications Users and Admins
                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                    } else {


                                        $valeur_send_admin  =  $amount_conversion;

                                        $walletInter->update([
                                            'is_send' => 1,
                                        ]);

                                        // send comission valeur admin 

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_send_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }
                                        if (sabdy()->country->code == 'CI') {

                                            $message_pre = 'Votre code de la facture prepéyé est ' . $walletInter->id_invoice_pre;

                                            Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$walletInter->phone_invoice&from=ROBUS&sms=$message_pre");
                                            $message = 'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie
                                                ->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }

                                        // Notifications Users and Admins
                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                    }
                                }
                            }
                        } elseif (sabdy()->wallet->currencie->code == 'GNF') {

                            // Convertion valeur en Devise user Auth
                            $valeur_amount = $amount_conversion * sabdy()->wallet->currencie->value;


                            if ($som_transfers >= $valeur_ceiling) {
                                $this->resetInterField();
                                $this->emit('modal-hide-inters');
                                $this->emit('warning', 'Votre montant journier est atteint !');
                            } else {


                                $valAmount =  $this->paiement_amount_total_inter + $valeur_amount;

                                if ($valAmount > sabdy()->wallet->amount) {
                                    $this->resetInterField();
                                    $this->emit('modal-hide-inters');
                                    $this->emit('error', 'Verifier votre montant svp ou le pays d\'envoie !');
                                } else {

                                    $amount_solde =  sabdy()->wallet->amount -  $valAmount;

                                    $walletInter = WalletInter::create([
                                        'wallet_id' => sabdy()->wallet->id,
                                        'amount' =>  $this->paiement_amount_total_inter,
                                        'inter_cost_id' => null,
                                        'payment_cost_id' => 1,
                                        'phone' =>    $country_robus->indecative . $this->paiement_phone_canal_inter ?? null,
                                        'number_compte' => $this->account_number_inter ?? null,
                                        'created_by' => sabdy()->id,
                                        'provider_id' => $provider_payment->id,
                                        'status' => 'PENDING',
                                        'country_id' =>  $country_robus->id,
                                        'number_decoder' => $this->paiement_decoder_canal ?? null,
                                        'id_invoice' => $this->paiement_id_invoice_inter,
                                        'number_contract' => $this->paiement_num_contrat_inter ?? null,
                                        'phone_invoice' => $this->pre_phone_invoice_inter ??  null,
                                        'id_invoice_pre' =>  $this->pre_id_invoice_inter ?? null,
                                        'name' =>  $this->paiement_name_cie_inter ?? $this->paiement_name_sodecie_inter ?? null,
                                        'provider_type' => 5,
                                        'number_phone' => $this->paiement_phone_canal ?? null,
                                        'offer_id' => null,
                                        'costs_amount' =>  $amount_conversion,
                                        'status' => 'PENDING',
                                        'paiementChoix_phone' => $this->paiementChoix_phone ?? null,
                                        'paiementChoix_type' => $this->paiementChoix_type ?? null,
                                        'factureChoix_type' => $this->facture_choix_type ?? null,
                                        'amount_solde'  =>   $amount_solde ?? null
                                    ]);

                                    $commission = $walletInter->amount + $valeur_amount;

                                    sabdy()->wallet->update([
                                        'reload_by' => sabdy()->firstname .
                                            ' ' . sabdy()->lastname,
                                        'sid_user_relod' => sabdy()->id,
                                        'amount' => DB::raw('amount-' . $commission),
                                    ]);

                                    // Commissions Inters UnderDistributer
                                    if (sabdy()->is_under_distributer == 1) {

                                        // Admin Comission 
                                        $valeur_com_admin =   $amount_conversion  -  $percent_robus->comission_auther;

                                        // Commission distributer 
                                        $valuer_deposit =      $percent_robus->comission_auther / sabdy()->wallet->currencie->value;

                                        $walletInter->update([
                                            'commissions' => DB::raw('commissions+'  . $valuer_deposit),

                                        ]);
                                        $walletInter->update([
                                            'is_send' => 1,
                                        ]);

                                        if (sabdy()->country->code == 'CI') {

                                            $message_preo =  'Votre code de la facture prepéyé est ' . $walletInter->id_invoice_pre;

                                            Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$walletInter->phone_invoice&from=ROBUS&sms=$message_preo");

                                            $message = 'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie
                                                ->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }

                                        // send comission valeur admin 

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_com_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }

                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        $this->emit('transaction_inter_sends', $walletInter->id);

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    } else {

                                        // Admin Comission 
                                        $valeur_com_admin =   $amount_conversion  -  $percent_robus->comission_auther;

                                        // Commission distributer 
                                        $valuer_deposit =      $percent_robus->comission_auther / sabdy()->wallet->currencie->value;

                                        $valeur_send_admin  =  $amount_conversion;

                                        $walletInter->update([
                                            'is_send' => 1,
                                        ]);

                                        if (sabdy()->country->code == 'CI') {

                                            $message_preo =  'Votre code de la facture prepéyé est ' . $walletInter->id_invoice_pre;

                                            Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$walletInter->phone_invoice&from=ROBUS&sms=$message_preo");

                                            $message = 'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie
                                                ->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }

                                        // send comission valeur admin 

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_send_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }

                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    }
                                }
                            }
                        } elseif (sabdy()->wallet->currencie->code == 'CFA') {

                            $valeur_amount = $amount_conversion;

                            if ($som_transfers >= $valeur_ceiling) {
                                $this->resetInterField();
                                $this->emit('modal-hide-inters');
                                $this->emit('warning', 'Votre montant journier est atteint !');
                            } else {


                                $val_amount = $this->paiement_amount_total_inter +  $valeur_amount;

                                if ($val_amount > sabdy()->wallet->amount || $country_robus->code != 'CI') {
                                    $this->resetInterField();
                                    $this->emit('modal-hide-inters');
                                    $this->emit('error', 'Verifier votre montant  svp ou le pays d\'envoie !');
                                } else {


                                    $amount_solde =  sabdy()->wallet->amount -  $val_amount;

                                    $walletInter = WalletInter::create([
                                        'wallet_id' => sabdy()->wallet->id,
                                        'amount' =>  $this->paiement_amount_total_inter,
                                        'inter_cost_id' => null,
                                        'payment_cost_id' => 1,
                                        'phone' =>    $country_robus->indecative . $this->paiement_phone_canal_inter ?? null,
                                        'number_compte' => $this->account_number_inter ?? null,
                                        'created_by' => sabdy()->id,
                                        'provider_id' => $provider_payment->id,
                                        'status' => 'PENDING',
                                        'country_id' =>  $country_robus->id,
                                        'number_decoder' => $this->paiement_decoder_canal ?? null,
                                        'id_invoice' => $this->paiement_id_invoice_inter,
                                        'number_contract' => $this->paiement_num_contrat_inter ?? null,
                                        'phone_invoice' => $this->pre_phone_invoice_inter ??  null,
                                        'id_invoice_pre' =>  $this->pre_id_invoice_inter ?? null,
                                        'name' =>  $this->paiement_name_cie_inter ?? $this->paiement_name_sodecie_inter ?? null,
                                        'provider_type' => 5,
                                        'number_phone' => $this->paiement_phone_canal ?? null,
                                        'offer_id' => null,
                                        'costs_amount' =>  $amount_conversion,
                                        'status' => 'PENDING',
                                        'paiementChoix_phone' => $this->paiementChoix_phone ?? null,
                                        'paiementChoix_type' => $this->paiementChoix_type ?? null,
                                        'factureChoix_type' => $this->facture_choix_type ?? null,
                                        'amount_solde'  =>   $amount_solde ?? null
                                    ]);


                                    $commission = $walletInter->amount + $valeur_amount;

                                    sabdy()->wallet->update([
                                        'reload_by' => sabdy()->firstname .
                                            ' ' . sabdy()->lastname,
                                        'sid_user_relod' => sabdy()->id,
                                        'amount' => DB::raw('amount-' . $commission),
                                    ]);

                                    // Commissions Inters UnderDistributer
                                    if (sabdy()->is_under_distributer == 1) {

                                        // Admin Comission 

                                        $valeur_com_admin =   $amount_conversion  -  $percent_robus->comission_auther;

                                        // Commission distributer 
                                        $valuer_deposit =      $percent_robus->comission_auther;

                                        $walletInter->update([
                                            'commissions' => DB::raw('commissions+'  . $valuer_deposit),

                                        ]);

                                        $walletInter->update([
                                            'is_send' => 1,
                                        ]);

                                        // send comission valeur admin 

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_com_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }
                                        if (sabdy()->country->code == 'CI') {

                                            $message_pro = 'Votre code de la facture prepéyé est ' . $walletInter->id_invoice_pre;

                                            Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$walletInter->phone_invoice&from=ROBUS&sms=$message_pro");

                                            $message = 'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }

                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        $this->emit('transaction_inter_sends', $walletInter->id);

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    } else {

                                        $valeur_send_admin  =  $amount_conversion;

                                        $walletInter->update([
                                            'is_send' => 1,
                                        ]);

                                        // send comission valeur admin 

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_send_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }
                                        if (sabdy()->country->code == 'CI') {

                                            $message_pro = 'Votre code de la facture prepéyé est ' . $walletInter->id_invoice_pre;

                                            Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$walletInter->phone_invoice&from=ROBUS&sms=$message_pro");

                                            $message = 'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }
                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        $this->emit('transaction_inter_sends', $walletInter->id);

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $this->emit('transaction_inters');
                    } elseif ($this->facture_choix_type == 2) {

                        $this->validate(
                            [
                                'paiement_name_cie_inter' => 'required',
                                'paiement_id_invoice_inter' => 'required',
                                'paiement_amount_total_inter' => 'required',
                            ],
                            [
                                'paiement_amount_total_inter.required' =>
                                'Votre montant est obligatoire',
                                'paiement_name_cie_inter.required' =>
                                'Nom  est obligatoire',
                                'paiement_id_invoice_inter.required' =>
                                'Numero  est obligatoire',
                            ]
                        );

                        $percent_robus = PaymentCost::whereId(1)->first();

                        // Conversion in Price amount
                        $amount_conversion = $percent_robus->amount_auther;

                        if (sabdy()->wallet->currencie->value >  $country_civ->currencie->value) {
                            $valeur_amount = $amount_conversion /  sabdy()->wallet->currencie->value;

                            if ($som_transfers >= $valeur_ceiling) {
                                $this->resetInterField();
                                $this->emit('modal-hide-inters');
                                $this->emit('warning', 'Votre montant journier est atteint !');
                            } else {

                                $val_amount = $this->paiement_amount_total_inter +  $valeur_amount;

                                if ($val_amount > sabdy()->wallet->amount) {
                                    $this->resetInterField();
                                    $this->emit('modal-hide-inters');
                                    $this->emit('error', 'Verifier votre montant svp ou le pays d\'envoie  !');
                                } else {

                                    $amount_solde =  sabdy()->wallet->amount -  $val_amount;

                                    $walletInter = WalletInter::create([
                                        'wallet_id' => sabdy()->wallet->id,
                                        'amount' =>  $this->paiement_amount_total_inter ?? $this->paiement_amount_t_sodecie_inter ??  $this->amount_inter,
                                        'inter_cost_id' => null,
                                        'payment_cost_id' => 1,
                                        'phone' =>   $country_robus->indecative . $this->paiement_phone_canal_inter ?? null,
                                        'number_compte' => $this->account_number_inter ?? null,
                                        'created_by' => sabdy()->id,
                                        'provider_id' => $provider_payment->id,
                                        'status' => 'PENDING',
                                        'country_id' => $country_robus->id,
                                        'number_decoder' => $this->paiement_decoder_canal ?? null,
                                        'id_invoice' => $this->paiement_id_invoice_inter ?? $this->paiement_id_invoice,
                                        'number_contract' => $this->paiement_num_contrat_inter ?? null,
                                        'phone_invoice' => $this->pre_phone_invoice_inter ??  null,
                                        'id_invoice_pre' =>  $this->pre_id_invoice_inter ?? null,
                                        'name' =>  $this->paiement_name_cie_inter ?? $this->paiement_name_sodecie_inter ?? null,
                                        'provider_type' => 5,
                                        'number_phone' => $this->paiement_phone_canal ?? null,
                                        'offer_id' => $offer->id ?? null,
                                        'costs_amount' =>  $valeur_amount,
                                        'status' => 'PENDING',
                                        'paiementChoix_phone' => $this->paiementChoix_phone ?? null,
                                        'paiementChoix_type' => $this->paiementChoix_type ?? null,
                                        'factureChoix_type' => $this->facture_choix_type ?? null,
                                        'amount_solde'  =>   $amount_solde ?? null
                                    ]);

                                    $commission = $walletInter->amount + $valeur_amount;


                                    sabdy()->wallet->update([
                                        'reload_by' => sabdy()->firstname .
                                            ' ' . sabdy()->lastname,
                                        'sid_user_relod' => sabdy()->id,
                                        'amount' => DB::raw('amount-' . $commission),
                                    ]);

                                    // Commissions Inters UnderDistributer
                                    if (sabdy()->is_under_distributer == 1) {

                                        // Admin Comission 

                                        $valeur_com_admin =   $amount_conversion  -  $percent_robus->comission_auther;
                                        // Commission distributer 

                                        $valuer_deposit =      $percent_robus->comission_auther / sabdy()->wallet->currencie->value;

                                        $walletInter->update([
                                            'commissions' => DB::raw('commissions+'  . $valuer_deposit),

                                        ]);
                                        // send comission valeur admin 

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_com_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }
                                        if (sabdy()->country->code == 'CI') {

                                            $message = 'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie
                                                ->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }

                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        $this->emit('transaction_inter_sends', $walletInter->id);

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    } else {

                                        $valeur_send_admin  =  $amount_conversion;

                                        // send comission valeur admin 

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_send_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }
                                        if (sabdy()->country->code == 'CI') {

                                            $message = 'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie
                                                ->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }

                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        $this->emit('transaction_inter_sends', $walletInter->id);

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    }
                                }
                            }
                        } elseif (sabdy()->wallet->currencie->code == 'GNF') {

                            // Convertion valeur en Devise user Auth
                            $valeur_amount = $amount_conversion * sabdy()->wallet->currencie->value;


                            if ($som_transfers >= $valeur_ceiling) {
                                $this->resetInterField();
                                $this->emit('modal-hide-inters');
                                $this->emit(
                                    'warning',
                                    'Votre montant journier est atteint !'
                                );
                            } else {

                                $val_amount = $this->paiement_amount_total_inter + $valeur_amount;

                                if ($val_amount > sabdy()->wallet->amount) {
                                    $this->resetInterField();
                                    $this->emit('modal-hide-inters');
                                    $this->emit(
                                        'error',
                                        'Verifier votre montant  svp !'
                                    );
                                } else {

                                    $amount_solde =  sabdy()->wallet->amount -  $val_amount;

                                    $walletInter = WalletInter::create([

                                        'wallet_id' => sabdy()->wallet->id,
                                        'amount' =>  $this->paiement_amount_total_inter ?? $this->paiement_amount_t_sodecie_inter ??  $this->amount_inter,
                                        'inter_cost_id' => null,
                                        'payment_cost_id' => 1,
                                        'phone' =>   $country_robus->indecative . $this->paiement_phone_canal_inter ?? null,
                                        'number_compte' => $this->account_number_inter ?? null,
                                        'created_by' => sabdy()->id,
                                        'provider_id' => $provider_payment->id,
                                        'status' => 'PENDING',
                                        'country_id' => $country_robus->id,
                                        'number_decoder' => $this->paiement_decoder_canal ?? null,
                                        'id_invoice' => $this->paiement_id_invoice_inter ?? $this->paiement_id_invoice,
                                        'number_contract' => $this->paiement_num_contrat_inter ?? null,
                                        'phone_invoice' => $this->pre_phone_invoice_inter ??  null,
                                        'id_invoice_pre' =>  $this->pre_id_invoice_inter ?? null,
                                        'name' =>  $this->paiement_name_cie_inter ?? $this->paiement_name_sodecie_inter ?? null,
                                        'provider_type' => 5,
                                        'number_phone' => $this->paiement_phone_canal ?? null,
                                        'offer_id' => $offer->id ?? null,
                                        'costs_amount' =>  $valeur_amount,
                                        'status' => 'PENDING',
                                        'paiementChoix_phone' => $this->paiementChoix_phone ?? null,
                                        'paiementChoix_type' => $this->paiementChoix_type ?? null,
                                        'factureChoix_type' => $this->facture_choix_type ?? null,
                                        'amount_solde'  =>   $amount_solde ?? null
                                    ]);

                                    $commission = $walletInter->amount + $valeur_amount;


                                    sabdy()->wallet->update([
                                        'reload_by' => sabdy()->firstname .
                                            ' ' . sabdy()->lastname,
                                        'sid_user_relod' => sabdy()->id,
                                        'amount' => DB::raw('amount-' . $commission),
                                    ]);


                                    // Commissions Inters UnderDistributer
                                    if (sabdy()->is_under_distributer == 1) {


                                        // Admin Comission 

                                        $valeur_com_admin =   $amount_conversion  -  $percent_robus->comission_auther;
                                        // Commission distributer 

                                        $valuer_deposit =      $percent_robus->comission_auther / sabdy()->wallet->currencie->value;


                                        $walletInter->update([
                                            'commissions' => DB::raw('commissions+'  . $valuer_deposit),

                                        ]);

                                        // send comission valeur admin 
                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_com_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }

                                        if (sabdy()->country->code == 'CI') {
                                            $message = 'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie
                                                ->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }

                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        $this->emit('transaction_inter_sends', $walletInter->id);

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    } else {

                                        // send comission valeur admin 

                                        // Admin Comission 

                                        $valeur_com_admin =   $amount_conversion  -  $percent_robus->comission_auther;
                                        // Commission distributer 

                                        $valuer_deposit =      $percent_robus->comission_auther / sabdy()->wallet->currencie->value;

                                        $valeur_send_admin  =  $amount_conversion;

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_send_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }

                                        if (sabdy()->country->code == 'CI') {
                                            $message = 'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie
                                                ->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }

                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        $this->emit('transaction_inter_sends', $walletInter->id);

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    }
                                }
                            }
                        } elseif (sabdy()->wallet->currencie->code == 'CFA') {

                            if ($som_transfers >= $valeur_ceiling) {
                                $this->resetInterField();
                                $this->emit('modal-hide-inters');
                                $this->emit(
                                    'warning',
                                    'Votre montant journier est atteint !'
                                );
                            } else {

                                $valAmount = $this->paiement_amount_total_inter + $percent_robus->amount_auther;

                                if ($valAmount > sabdy()->wallet->amount || $country_civ->id == sabdy()->country->id) {
                                    $this->resetInterField();
                                    $this->emit('modal-hide-inters');
                                    $this->emit(
                                        'error',
                                        'Verifier votre montant  svp !'
                                    );
                                } else {

                                    $amount_solde =  sabdy()->wallet->amount -  $valAmount;

                                    $walletInter = WalletInter::create([
                                        'wallet_id' => sabdy()->wallet->id,
                                        'amount' =>  $this->paiement_amount_total_inter ?? $this->paiement_amount_t_sodecie_inter ??  $this->amount_inter,
                                        'inter_cost_id' => null,
                                        'payment_cost_id' => 1,
                                        'phone' =>  $country_robus->indecative . $this->paiement_phone_canal_inter ?? null,
                                        'number_compte' => $this->account_number_inter ?? null,
                                        'created_by' => sabdy()->id,
                                        'provider_id' => $provider_payment->id,
                                        'status' => 'PENDING',
                                        'country_id' =>  $country_robus->id,
                                        'number_decoder' => $this->paiement_decoder_canal ?? null,
                                        'id_invoice' => $this->paiement_id_invoice_inter ?? $this->paiement_id_invoice,
                                        'number_contract' => $this->paiement_num_contrat_inter ?? null,
                                        'phone_invoice' => $this->pre_phone_invoice_inter ??  null,
                                        'id_invoice_pre' =>  $this->pre_id_invoice_inter ?? null,
                                        'name' =>  $this->paiement_name_cie_inter ?? $this->paiement_name_sodecie_inter ?? null,
                                        'provider_type' => 5,
                                        'number_phone' => $this->paiement_phone_canal ?? null,
                                        'offer_id' => $offer->id ?? null,
                                        'costs_amount' =>  $percent_robus->amount_auther,
                                        'status' => 'PENDING',
                                        'paiementChoix_phone' => $this->paiementChoix_phone ?? null,
                                        'paiementChoix_type' => $this->paiementChoix_type ?? null,
                                        'factureChoix_type' => $this->facture_choix_type ?? null,
                                        'amount_solde'  =>   $amount_solde ?? null
                                    ]);


                                    $commission = $walletInter->amount + $amount_conversion;

                                    sabdy()->wallet->update([
                                        'reload_by' => sabdy()->firstname .
                                            ' ' . sabdy()->lastname,
                                        'sid_user_relod' => sabdy()->id,
                                        'amount' => DB::raw('amount-' . $commission),
                                    ]);

                                    // Commissions Inters UnderDistributer
                                    if (sabdy()->is_under_distributer == 1) {

                                        // Admin Comission 
                                        $valeur_com_admin =   $amount_conversion - $percent_robus->comission_auther;

                                        // Commission distributer 
                                        $valuer_deposit =      $percent_robus->comission_auther;


                                        $walletInter->update([
                                            'commissions' => DB::raw('commissions+'  . $valuer_deposit),

                                        ]);

                                        // send comission valeur admin 

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_com_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }


                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        $this->emit('transaction_inter_sends', $walletInter->id);

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }


                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    } else {

                                        $valeur_send_admin  =  $amount_conversion;

                                        // send comission valeur admin 

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_send_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }


                                        if (sabdy()->country->code == 'CI') {

                                            $message = 'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie
                                                ->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }

                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        $this->emit('transaction_inter_sends', $walletInter->id);

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $this->emit('transaction_inters');
                    }
                } elseif ($this->provider_payment_inter == 54) {

                    $this->validate(
                        [
                            'paiement_name_sodecie_inter' => 'required',
                            'paiement_num_contrat_inter' => 'required|max:9',
                            'paiement_amount_t_sodecie_inter' => 'required',
                        ],
                        [
                            'paiement_amount_t_sodecie_inter.required' =>
                            'Votre montant est obligatoire',
                            'paiement_name_sodecie_inter.required' =>
                            'Nom  est obligatoire',
                            'paiement_num_contrat_inter.required' =>
                            'Numero  est obligatoire',
                        ]
                    );

                    $percent_robus = PaymentCost::whereId(1)->first();

                    // Conversion in Price amount
                    $amount_conversion = $percent_robus->amount_auther;

                    if (sabdy()->wallet->currencie->value >  $country_civ->currencie->value) {

                        $valeur_amount =  $amount_conversion / sabdy()->wallet->currencie->value;

                        if ($som_transfers >= $valeur_ceiling) {
                            $this->resetInterField();
                            $this->emit('modal-hide-inters');
                            $this->emit(
                                'warning',
                                'Votre montant journier est atteint !'
                            );
                        } else {

                            $val_amount = $this->paiement_amount_t_sodecie_inter + $valeur_amount;

                            if ($val_amount > sabdy()->wallet->amount) {
                                $this->resetInterField();
                                $this->emit('modal-hide-inters');
                                $this->emit(
                                    'error',
                                    'Verifier votre montant svp ou le pays d\'envoie !'
                                );
                            } else {

                                $amount_solde =  sabdy()->wallet->amount -  $val_amount;

                                $walletInter = WalletInter::create([
                                    'wallet_id' => sabdy()->wallet->id,
                                    'amount' =>  $this->paiement_amount_total_inter ?? $this->paiement_amount_t_sodecie_inter,
                                    'inter_cost_id' => null,
                                    'payment_cost_id' => 1,
                                    'phone' => $country_robus->indecative . $this->paiement_phone_canal_inter ?? null,
                                    'number_compte' => $this->account_number_inter ?? null,
                                    'created_by' => sabdy()->id,
                                    'provider_id' => $provider_payment->id,
                                    'status' => 'PENDING',
                                    'country_id' => $country_robus->id,
                                    'number_decoder' => $this->paiement_decoder_canal ?? null,
                                    'id_invoice' => $this->paiement_id_invoice_inter ?? null,
                                    'number_contract' => $this->paiement_num_contrat_inter ?? null,
                                    'phone_invoice' => $this->pre_phone_invoice_inter ??  null,
                                    'id_invoice_pre' =>  $this->pre_id_invoice_inter ?? null,
                                    'name' =>  $this->paiement_name_sodecie_inter ?? $this->paiement_name_cie_inter ?? null,
                                    'provider_type' => 5,
                                    'number_phone' => $this->paiement_phone_canal ?? null,
                                    'offer_id' => $offer->id ?? null,
                                    'costs_amount' =>  $valeur_amount,
                                    'status' => 'PENDING',
                                    'paiementChoix_phone' => $this->paiementChoix_phone ?? null,
                                    'paiementChoix_type' => $this->paiementChoix_type ?? null,
                                    'factureChoix_type' => $this->facture_choix_type ?? null,
                                    'amount_solde'  =>   $amount_solde ?? null
                                ]);

                                $commission = $walletInter->amount + $valeur_amount;

                                sabdy()->wallet->update([
                                    'reload_by' => sabdy()->firstname .
                                        ' ' . sabdy()->lastname,
                                    'sid_user_relod' => sabdy()->id,
                                    'amount' => DB::raw('amount-' . $commission),
                                ]);


                                // Commissions Inters UnderDistributer
                                if (sabdy()->is_under_distributer == 1) {

                                    // Admin Comission 
                                    $valeur_com_admin =   $amount_conversion - $percent_robus->comission_auther;
                                    // Commission distributer 

                                    $valuer_deposit =      $percent_robus->comission_auther / sabdy()->wallet->currencie->value;

                                    $walletInter->update([
                                        'commissions' => DB::raw('commissions+'  . $valuer_deposit),

                                    ]);

                                    if ($admin_civ->count() > 0) {

                                        $nmbre_admin = $admin_civ->count();

                                        $valeur_single = $valeur_com_admin / $nmbre_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            AdminsCom::create([
                                                'amount' =>   $valeur_single,
                                                'sid_user_id' =>   $admin_single->id,
                                                'wallet_transaction_id' => null,
                                                'wallet_bank_id' => null,
                                                'wallet_deposit_id' => null,
                                                'wallet_inter_id' =>  $walletInter->id,
                                                'wallet_payment_id' => null,
                                                'wallet_phone_credit_id' => null,
                                                'wallet_reload_id' => null,
                                                'bank_card_id' => null,
                                                'description' => 'Commission',
                                            ]);
                                            $admin_single->wallet->update([
                                                'reload_by' => sabdy()->firstname .
                                                    ' ' . sabdy()->lastname,
                                                'sid_user_relod' => sabdy()->id,
                                                'amount' => DB::raw('amount+' . $valeur_single),
                                            ]);
                                        }
                                    }

                                    $msg_title = 'Transfert internationnal';
                                    $msg = 'Demande de paiement est en cours de validation  ';
                                    sabdy()->notify(
                                        new UserNotification([
                                            'title' => $msg_title,
                                            'message' => $msg,
                                        ])
                                    );
                                    $notifs = sabdy()->unreadNotifications;
                                    event(new CreateWalletInterEvent($walletInter));
                                    $this->emit('transaction_inter_sends', $walletInter->id);
                                    $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                    Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                        ' ' . $walletInter->wallet->sidUser->firstname));
                                    $this->emit('transaction_inter_sends', $walletInter->id);

                                    if ($admins->count() > 0) {

                                        foreach ($admins  as $user) {

                                            $admin_single = SidUser::find($user->id);

                                            $notifs = $admin_single->unreadNotifications;

                                            event(new AdminNotifEvent($notifs));
                                        }

                                        // $notifs = $admin->unreadNotifications;
                                        // event(new AdminNotifEvent($notifs));
                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('success', 'Transfers effectué !');
                                        if (sabdy()->email != null) {
                                            $notify = new MailController();
                                            $notify->newPaiementInter($walletInter);
                                        }
                                    } else {

                                        // $notifs = $admin->unreadNotifications;
                                        // event(new AdminNotifEvent($notifs));
                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('success', 'Transfers effectué !');
                                        if (sabdy()->email != null) {
                                            $notify = new MailController();
                                            $notify->newPaiementInter($walletInter);
                                        }
                                    }
                                } else {


                                    $valeur_send_admin = $amount_conversion;

                                    if ($admin_civ->count() > 0) {

                                        $nmbre_admin = $admin_civ->count();

                                        $valeur_single = $valeur_send_admin / $nmbre_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            AdminsCom::create([
                                                'amount' =>   $valeur_single,
                                                'sid_user_id' =>   $admin_single->id,
                                                'wallet_transaction_id' => null,
                                                'wallet_bank_id' => null,
                                                'wallet_deposit_id' => null,
                                                'wallet_inter_id' =>  $walletInter->id,
                                                'wallet_payment_id' => null,
                                                'wallet_phone_credit_id' => null,
                                                'wallet_reload_id' => null,
                                                'bank_card_id' => null,
                                                'description' => 'Commission',
                                            ]);
                                            $admin_single->wallet->update([
                                                'reload_by' => sabdy()->firstname .
                                                    ' ' . sabdy()->lastname,
                                                'sid_user_relod' => sabdy()->id,
                                                'amount' => DB::raw('amount+' . $valeur_single),
                                            ]);
                                        }
                                    }
                                    if (sabdy()->country->code == 'CI') {

                                        $message =  'Vous avez effectué un paiement de ' .
                                            $walletInter->amount .
                                            sabdy()->wallet->currencie->code .
                                            ' ' .
                                            'par ' .
                                            $walletInter->provider->name .
                                            ' a ' .
                                            $walletInter->created_at .
                                            ' votre demande est en-cours de validation ' .
                                            'Robus vous remercie';

                                        $encode = substr(sabdy()->phone, -10);

                                        Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                    }

                                    $msg_title = 'Transfert internationnal';
                                    $msg = 'Demande de paiement est en cours de validation  ';
                                    sabdy()->notify(
                                        new UserNotification([
                                            'title' => $msg_title,
                                            'message' => $msg,
                                        ])
                                    );
                                    $notifs = sabdy()->unreadNotifications;
                                    event(new CreateWalletInterEvent($walletInter));
                                    $this->emit('transaction_inter_sends', $walletInter->id);
                                    $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                    Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                        ' ' . $walletInter->wallet->sidUser->firstname));
                                    $this->emit('transaction_inter_sends', $walletInter->id);

                                    if ($admins->count() > 0) {

                                        foreach ($admins  as $user) {

                                            $admin_single = SidUser::find($user->id);

                                            $notifs = $admin_single->unreadNotifications;

                                            event(new AdminNotifEvent($notifs));
                                        }

                                        // $notifs = $admin->unreadNotifications;
                                        // event(new AdminNotifEvent($notifs));
                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('success', 'Transfers effectué !');
                                        if (sabdy()->email != null) {
                                            $notify = new MailController();
                                            $notify->newPaiementInter($walletInter);
                                        }
                                    } else {

                                        // $notifs = $admin->unreadNotifications;
                                        // event(new AdminNotifEvent($notifs));
                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('success', 'Transfers effectué !');
                                        if (sabdy()->email != null) {
                                            $notify = new MailController();
                                            $notify->newPaiementInter($walletInter);
                                        }
                                    }
                                }
                                // send comission valeur admin 
                            }
                        }
                    } elseif (sabdy()->wallet->currencie->code == 'GNF') {

                        // Devise AUth User GNF
                        $valeur_amount =  $amount_conversion *  sabdy()->wallet->currencie->value;

                        if ($som_transfers >= $valeur_ceiling) {
                            $this->resetInterField();
                            $this->emit('modal-hide-inters');
                            $this->emit(
                                'warning',
                                'Votre montant journier est atteint !'
                            );
                        } else {

                            $valAmount = $this->paiement_amount_t_sodecie_inter + $valeur_amount;

                            if ($valAmount > sabdy()->wallet->amount || $country_robus->code != 'CI') {
                                $this->resetInterField();
                                $this->emit('modal-hide-inters');
                                $this->emit(
                                    'error',
                                    'Verifier votre montant  svp ou votre pays !'
                                );
                            } else {


                                $amount_solde =  sabdy()->wallet->amount -  $valAmount;

                                $walletInter = WalletInter::create([
                                    'wallet_id' => sabdy()->wallet->id,
                                    'amount' =>  $this->paiement_amount_total_inter ?? $this->paiement_amount_t_sodecie_inter,
                                    'inter_cost_id' => null,
                                    'payment_cost_id' => 1,
                                    'phone' => $country_robus->indecative . $this->paiement_phone_canal_inter ?? null,
                                    'number_compte' => $this->account_number_inter ?? null,
                                    'created_by' => sabdy()->id,
                                    'provider_id' => $provider_payment->id,
                                    'status' => 'PENDING',
                                    'country_id' => $country_robus->id,
                                    'number_decoder' => $this->paiement_decoder_canal ?? null,
                                    'id_invoice' => $this->paiement_id_invoice_inter ?? null,
                                    'number_contract' => $this->paiement_num_contrat_inter ?? null,
                                    'phone_invoice' => $this->pre_phone_invoice_inter ??  null,
                                    'id_invoice_pre' =>  $this->pre_id_invoice_inter ?? null,
                                    'name' =>  $this->paiement_name_sodecie_inter ?? $this->paiement_name_cie_inter ?? null,
                                    'provider_type' => 5,
                                    'number_phone' => $this->paiement_phone_canal ?? null,
                                    'offer_id' => $offer->id ?? null,
                                    'costs_amount' =>  $valeur_amount,
                                    'status' => 'PENDING',
                                    'paiementChoix_phone' => $this->paiementChoix_phone ?? null,
                                    'paiementChoix_type' => $this->paiementChoix_type ?? null,
                                    'factureChoix_type' => $this->facture_choix_type ?? null,
                                    'amount_solde'  =>   $amount_solde ?? null
                                ]);

                                $commission = $walletInter->amount + $valeur_amount;

                                sabdy()->wallet->update([
                                    'reload_by' => sabdy()->firstname .
                                        ' ' . sabdy()->lastname,
                                    'sid_user_relod' => sabdy()->id,
                                    'amount' => DB::raw('amount-' . $commission),
                                ]);


                                // Commissions Inters UnderDistributer
                                if (sabdy()->is_under_distributer == 1) {

                                    // Admin Comission 

                                    $valeur_com_admin =   $amount_conversion  -  $percent_robus->comission_auther;
                                    // Commission distributer 

                                    $valuer_deposit =      $percent_robus->comission_auther / sabdy()->wallet->currencie->value;

                                    $walletInter->update([
                                        'commissions' => DB::raw('commissions+'  . $valuer_deposit),
                                    ]);

                                    // send comission valeur admin 

                                    if ($admin_civ->count() > 0) {

                                        $nmbre_admin = $admin_civ->count();

                                        $valeur_single = $valeur_com_admin / $nmbre_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            AdminsCom::create([
                                                'amount' =>   $valeur_single,
                                                'sid_user_id' =>   $admin_single->id,
                                                'wallet_transaction_id' => null,
                                                'wallet_bank_id' => null,
                                                'wallet_deposit_id' => null,
                                                'wallet_inter_id' =>  $walletInter->id,
                                                'wallet_payment_id' => null,
                                                'wallet_phone_credit_id' => null,
                                                'wallet_reload_id' => null,
                                                'bank_card_id' => null,
                                                'description' => 'Commission',
                                            ]);
                                            $admin_single->wallet->update([
                                                'reload_by' => sabdy()->firstname .
                                                    ' ' . sabdy()->lastname,
                                                'sid_user_relod' => sabdy()->id,
                                                'amount' => DB::raw('amount+' . $valeur_single),
                                            ]);
                                        }
                                    }


                                    $msg_title = 'Transfert internationnal';
                                    $msg = 'Demande de paiement est en cours de validation  ';
                                    sabdy()->notify(
                                        new UserNotification([
                                            'title' => $msg_title,
                                            'message' => $msg,
                                        ])
                                    );
                                    $notifs = sabdy()->unreadNotifications;
                                    event(new CreateWalletInterEvent($walletInter));
                                    $this->emit('transaction_inter_sends', $walletInter->id);
                                    $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                    Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                        ' ' . $walletInter->wallet->sidUser->firstname));
                                    $this->emit('transaction_inter_sends', $walletInter->id);

                                    if ($admins->count() > 0) {

                                        foreach ($admins  as $user) {

                                            $admin_single = SidUser::find($user->id);

                                            $notifs = $admin_single->unreadNotifications;

                                            event(new AdminNotifEvent($notifs));
                                        }

                                        // $notifs = $admin->unreadNotifications;
                                        // event(new AdminNotifEvent($notifs));
                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('success', 'Transfers effectué !');
                                        if (sabdy()->email != null) {
                                            $notify = new MailController();
                                            $notify->newPaiementInter($walletInter);
                                        }
                                    } else {

                                        // $notifs = $admin->unreadNotifications;
                                        // event(new AdminNotifEvent($notifs));
                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('success', 'Transfers effectué !');
                                        if (sabdy()->email != null) {
                                            $notify = new MailController();
                                            $notify->newPaiementInter($walletInter);
                                        }
                                    }
                                } else {
                                    // Admin Comission 

                                    $valeur_com_admin =   $amount_conversion  -  $percent_robus->comission_auther;
                                    // Commission distributer 

                                    $valeur_send_admin = $amount_conversion;


                                    // send comission valeur admin 

                                    if ($admin_civ->count() > 0) {

                                        $nmbre_admin = $admin_civ->count();

                                        $valeur_single = $valeur_send_admin / $nmbre_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            AdminsCom::create([
                                                'amount' =>   $valeur_single,
                                                'sid_user_id' =>   $admin_single->id,
                                                'wallet_transaction_id' => null,
                                                'wallet_bank_id' => null,
                                                'wallet_deposit_id' => null,
                                                'wallet_inter_id' =>  $walletInter->id,
                                                'wallet_payment_id' => null,
                                                'wallet_phone_credit_id' => null,
                                                'wallet_reload_id' => null,
                                                'bank_card_id' => null,
                                                'description' => 'Commission',
                                            ]);
                                            $admin_single->wallet->update([
                                                'reload_by' => sabdy()->firstname .
                                                    ' ' . sabdy()->lastname,
                                                'sid_user_relod' => sabdy()->id,
                                                'amount' => DB::raw('amount+' . $valeur_single),
                                            ]);
                                        }
                                    }

                                    if (sabdy()->country->code == 'CI') {
                                        $message = 'Vous avez effectué un paiement de ' .
                                            $walletInter->amount .
                                            sabdy()->wallet->currencie->code .
                                            ' ' .
                                            'par ' .
                                            $walletInter->provider->name .
                                            ' a ' .
                                            $walletInter->created_at .
                                            ' votre demande est en-cours de validation ' .
                                            'Robus vous remercie';

                                        $encode = substr(sabdy()->phone, -10);

                                        Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                    }

                                    $msg_title = 'Transfert internationnal';
                                    $msg = 'Demande de paiement est en cours de validation  ';
                                    sabdy()->notify(
                                        new UserNotification([
                                            'title' => $msg_title,
                                            'message' => $msg,
                                        ])
                                    );
                                    $notifs = sabdy()->unreadNotifications;
                                    event(new CreateWalletInterEvent($walletInter));
                                    $this->emit('transaction_inter_sends', $walletInter->id);
                                    $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                    Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                        ' ' . $walletInter->wallet->sidUser->firstname));
                                    $this->emit('transaction_inter_sends', $walletInter->id);

                                    if ($admins->count() > 0) {

                                        foreach ($admins  as $user) {

                                            $admin_single = SidUser::find($user->id);

                                            $notifs = $admin_single->unreadNotifications;

                                            event(new AdminNotifEvent($notifs));
                                        }

                                        // $notifs = $admin->unreadNotifications;
                                        // event(new AdminNotifEvent($notifs));
                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('success', 'Transfers effectué !');
                                        if (sabdy()->email != null) {
                                            $notify = new MailController();
                                            $notify->newPaiementInter($walletInter);
                                        }
                                    } else {

                                        // $notifs = $admin->unreadNotifications;
                                        // event(new AdminNotifEvent($notifs));
                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('success', 'Transfers effectué !');
                                        if (sabdy()->email != null) {
                                            $notify = new MailController();
                                            $notify->newPaiementInter($walletInter);
                                        }
                                    }
                                }
                            }
                        }
                    } elseif (sabdy()->wallet->currencie->code == 'CFA') {

                        $valeur_amount = $amount_conversion;


                        if ($som_transfers >= $valeur_ceiling) {
                            $this->resetInterField();
                            $this->emit('modal-hide-inters');
                            $this->emit(
                                'warning',
                                'Votre montant journier est atteint !'
                            );
                        } else {

                            $valAmount =  $this->paiement_amount_t_sodecie_inter +   $valeur_amount;

                            if ($valAmount > sabdy()->wallet->amount || $country_robus->id == sabdy()->country->id) {
                                $this->resetInterField();
                                $this->emit('modal-hide-inters');
                                $this->emit(
                                    'error',
                                    'Verifier votre montant  svp ou votre pays !'
                                );
                            } else {

                                $amount_solde =  sabdy()->wallet->amount -  $valAmount;

                                $walletInter = WalletInter::create([
                                    'wallet_id' => sabdy()->wallet->id,
                                    'amount' =>  $this->paiement_amount_total_inter ?? $this->paiement_amount_t_sodecie_inter,
                                    'inter_cost_id' => null,
                                    'payment_cost_id' => 1,
                                    'phone' => $country_robus->indecative . $this->paiement_phone_canal_inter ?? null,
                                    'number_compte' => $this->account_number_inter ?? null,
                                    'created_by' => sabdy()->id,
                                    'provider_id' => $provider_payment->id,
                                    'status' => 'PENDING',
                                    'country_id' => $country_robus->id,
                                    'number_decoder' => $this->paiement_decoder_canal ?? null,
                                    'id_invoice' => $this->paiement_id_invoice_inter ?? null,
                                    'number_contract' => $this->paiement_num_contrat_inter ?? null,
                                    'phone_invoice' => $this->pre_phone_invoice_inter ??  null,
                                    'id_invoice_pre' =>  $this->pre_id_invoice_inter ?? null,
                                    'name' =>  $this->paiement_name_sodecie_inter ?? $this->paiement_name_cie_inter ?? null,
                                    'provider_type' => 5,
                                    'number_phone' => $this->paiement_phone_canal ?? null,
                                    'offer_id' => $offer->id ?? null,
                                    'costs_amount' =>  $valeur_amount,
                                    'status' => 'PENDING',
                                    'paiementChoix_phone' => $this->paiementChoix_phone ?? null,
                                    'paiementChoix_type' => $this->paiementChoix_type ?? null,
                                    'factureChoix_type' => $this->facture_choix_type ?? null,
                                    'amount_solde'  =>   $amount_solde ?? null
                                ]);


                                $commission = (float) $walletInter->amount + (float)$amount_conversion;


                                sabdy()->wallet->update([
                                    'reload_by' => sabdy()->firstname .
                                        ' ' . sabdy()->lastname,
                                    'sid_user_relod' => sabdy()->id,
                                    'amount' => DB::raw('amount-' . $commission),
                                ]);



                                // Commissions Inters UnderDistributer
                                if (sabdy()->is_under_distributer == 1) {

                                    // Admin Comission 
                                    $valeur_com_admin =   $amount_conversion - $percent_robus->comission_auther;

                                    // Commission distributer 
                                    $valuer_deposit =      $percent_robus->comission_auther;

                                    $valeur_send_admin = $amount_conversion;

                                    $walletInter->update([
                                        'commissions' => DB::raw('commissions+'  . $valuer_deposit),

                                    ]);


                                    // send comission valeur admin 

                                    if ($admin_civ->count() > 0) {

                                        $nmbre_admin = $admin_civ->count();

                                        $valeur_single = $valeur_com_admin / $nmbre_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            AdminsCom::create([
                                                'amount' =>   $valeur_single,
                                                'sid_user_id' =>   $admin_single->id,
                                                'wallet_transaction_id' => null,
                                                'wallet_bank_id' => null,
                                                'wallet_deposit_id' => null,
                                                'wallet_inter_id' =>  $walletInter->id,
                                                'wallet_payment_id' => null,
                                                'wallet_phone_credit_id' => null,
                                                'wallet_reload_id' => null,
                                                'bank_card_id' => null,
                                                'description' => 'Commission',
                                            ]);
                                            $admin_single->wallet->update([
                                                'reload_by' => sabdy()->firstname .
                                                    ' ' . sabdy()->lastname,
                                                'sid_user_relod' => sabdy()->id,
                                                'amount' => DB::raw('amount+' . $valeur_single),
                                            ]);
                                        }
                                    }

                                    if (sabdy()->country->code == 'CI') {
                                        $message =
                                            'Vous avez effectué un paiement de ' .
                                            $walletInter->amount .
                                            sabdy()->wallet->currencie->code .
                                            ' ' .
                                            'par ' . $walletInter->provider->name .
                                            ' a ' . $walletInter->created_at .
                                            ' votre demande est en-cours de validation ' .
                                            'Robus vous remercie';

                                        $encode = substr(sabdy()->phone, -10);

                                        Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                    }

                                    $msg_title = 'Transfert internationnal';
                                    $msg = 'Demande de paiement est en cours de validation  ';
                                    sabdy()->notify(
                                        new UserNotification([
                                            'title' => $msg_title,
                                            'message' => $msg,
                                        ])
                                    );
                                    $notifs = sabdy()->unreadNotifications;
                                    event(new CreateWalletInterEvent($walletInter));
                                    $this->emit('transaction_inter_sends', $walletInter->id);
                                    $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                    Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                        ' ' . $walletInter->wallet->sidUser->firstname));
                                    $this->emit('transaction_inter_sends', $walletInter->id);

                                    if ($admins->count() > 0) {

                                        foreach ($admins  as $user) {

                                            $admin_single = SidUser::find($user->id);

                                            $notifs = $admin_single->unreadNotifications;

                                            event(new AdminNotifEvent($notifs));
                                        }

                                        // $notifs = $admin->unreadNotifications;
                                        // event(new AdminNotifEvent($notifs));
                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('success', 'Transfers effectué !');
                                        if (sabdy()->email != null) {
                                            $notify = new MailController();
                                            $notify->newPaiementInter($walletInter);
                                        }
                                    } else {

                                        // $notifs = $admin->unreadNotifications;
                                        // event(new AdminNotifEvent($notifs));
                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('success', 'Transfers effectué !');
                                        if (sabdy()->email != null) {
                                            $notify = new MailController();
                                            $notify->newPaiementInter($walletInter);
                                        }
                                    }
                                } else {


                                    $valeur_send_admin = $amount_conversion;

                                    // send comission valeur admin 

                                    if ($admin_civ->count() > 0) {

                                        $nmbre_admin = $admin_civ->count();

                                        $valeur_single = $valeur_send_admin / $nmbre_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            AdminsCom::create([
                                                'amount' =>   $valeur_single,
                                                'sid_user_id' =>   $admin_single->id,
                                                'wallet_transaction_id' => null,
                                                'wallet_bank_id' => null,
                                                'wallet_deposit_id' => null,
                                                'wallet_inter_id' =>  $walletInter->id,
                                                'wallet_payment_id' => null,
                                                'wallet_phone_credit_id' => null,
                                                'wallet_reload_id' => null,
                                                'bank_card_id' => null,
                                                'description' => 'Commission',
                                            ]);
                                            $admin_single->wallet->update([
                                                'reload_by' => sabdy()->firstname .
                                                    ' ' . sabdy()->lastname,
                                                'sid_user_relod' => sabdy()->id,
                                                'amount' => DB::raw('amount+' . $valeur_single),
                                            ]);
                                        }
                                    }

                                    if (sabdy()->country->code == 'CI') {
                                        $message =
                                            'Vous avez effectué un paiement de ' .
                                            $walletInter->amount .
                                            sabdy()->wallet->currencie->code .
                                            ' ' .
                                            'par ' . $walletInter->provider->name .
                                            ' a ' . $walletInter->created_at .
                                            ' votre demande est en-cours de validation ' .
                                            'Robus vous remercie';

                                        $encode = substr(sabdy()->phone, -10);

                                        Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                    }

                                    $msg_title = 'Transfert internationnal';
                                    $msg = 'Demande de paiement est en cours de validation  ';
                                    sabdy()->notify(
                                        new UserNotification([
                                            'title' => $msg_title,
                                            'message' => $msg,
                                        ])
                                    );
                                    $notifs = sabdy()->unreadNotifications;
                                    event(new CreateWalletInterEvent($walletInter));
                                    $this->emit('transaction_inter_sends', $walletInter->id);
                                    $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                    Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                        ' ' . $walletInter->wallet->sidUser->firstname));
                                    $this->emit('transaction_inter_sends', $walletInter->id);

                                    if ($admins->count() > 0) {

                                        foreach ($admins  as $user) {

                                            $admin_single = SidUser::find($user->id);

                                            $notifs = $admin_single->unreadNotifications;

                                            event(new AdminNotifEvent($notifs));
                                        }

                                        // $notifs = $admin->unreadNotifications;
                                        // event(new AdminNotifEvent($notifs));
                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('success', 'Transfers effectué !');
                                        if (sabdy()->email != null) {
                                            $notify = new MailController();
                                            $notify->newPaiementInter($walletInter);
                                        }
                                    } else {

                                        // $notifs = $admin->unreadNotifications;
                                        // event(new AdminNotifEvent($notifs));
                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('success', 'Transfers effectué !');
                                        if (sabdy()->email != null) {
                                            $notify = new MailController();
                                            $notify->newPaiementInter($walletInter);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $this->emit('transaction_inters');
                } elseif ($this->provider_payment_inter == 52) {

                    // Canal paiement
                    if ($this->paiementChoix_phone == 1) {

                        $this->validate(
                            [
                                'offer_paiement' => 'required',
                            ],
                            [
                                'offer_paiement.required' =>
                                'Offre  est obligatoire',
                            ]
                        );

                        $percent_robus = PaymentCost::whereId(1)->first();

                        // Conversion in Price amount
                        $amount_conversion = $percent_robus->amount_canal;


                        if ($som_transfers >= $valeur_ceiling) {
                            $this->resetInterField();
                            $this->emit('modal-hide-inters');
                            $this->emit(
                                'warning',
                                'Votre montant journier est atteint !'
                            );
                        } else {
                            if (sabdy()->wallet->currencie->value >  $country_civ->currencie->value) {

                                $valeur_amount_com =  $amount_conversion /  sabdy()->wallet->currencie->value;

                                // Montant Update
                                $valeur_amount =  $offer->amount / sabdy()->wallet->currencie->value;

                                $val_amount =  $valeur_amount + $valeur_amount_com;


                                if ($val_amount > sabdy()->wallet->amount) {
                                    $this->resetInterField();
                                    $this->emit('modal-hide-inters');
                                    $this->emit('error', 'Verifier votre montant  svp !');
                                } else {

                                    $amount_solde =  sabdy()->wallet->amount -  $val_amount;

                                    $walletInter = WalletInter::create([
                                        'amount_solde'  =>   $amount_solde ?? null,
                                        'wallet_id' => sabdy()->wallet->id,
                                        'amount' => $valeur_amount,
                                        'inter_cost_id' => null,
                                        'payment_cost_id' => 1,
                                        'phone' =>   $country_robus->indecative . $this->paiement_phone_canal_inter ??  null,
                                        'number_compte' => $this->account_number_inter ?? null,
                                        'created_by' => sabdy()->id,
                                        'provider_id' => $provider_payment->id,
                                        'status' => 'PENDING',
                                        'country_id' =>  $country_robus->id,
                                        'number_decoder' => $this->paiement_decoder_canal_inter ?? null,
                                        'id_invoice' => $this->paiement_id_invoice_inter ?? $this->paiement_id_invoice,
                                        'number_contract' => $this->paiement_num_contrat_inter ??  null,
                                        'phone_invoice' => $this->pre_phone_invoice ?? null,
                                        'id_invoice_pre' => $this->pre_id_invoice_inter ?? null,
                                        'name' => $this->paiement_name_sodecie_inter ?? $this->paiement_name_cie_inter ??  null,
                                        'provider_type' =>  5,
                                        'number_phone' => $this->paiement_phone_canal_inter ??  null,
                                        'offer_id' => $offer->id ?? null,
                                        'status' => 'PENDING',
                                        'costs_amount' =>  $valeur_amount_com,
                                        'paiementChoix_phone' =>  $this->paiementChoix_phone ?? null,
                                        'paiementChoix_type' =>  $this->paiementChoix_type ?? null,
                                        'factureChoix_type' =>  $this->facture_choix_type ?? null,
                                    ]);

                                    $commission = $walletInter->amount + $valeur_amount_com;

                                    sabdy()->wallet->update([
                                        'reload_by' => sabdy()->firstname .
                                            ' ' . sabdy()->lastname,
                                        'sid_user_relod' => sabdy()->id,
                                        'amount' => DB::raw('amount-' . $commission),
                                    ]);


                                    // Commissions Inters UnderDistributer
                                    if (sabdy()->is_under_distributer == 1) {

                                        // Admin Comission 
                                        $valeur_com_admin =   $amount_conversion  -  $percent_robus->comission_canal;

                                        // Commission distributer canal
                                        $valuer_deposit =      $percent_robus->comission_canal / sabdy()->wallet->currencie->value;

                                        $valeur_send_admin = $amount_conversion;

                                        $walletInter->update([
                                            'commissions' => DB::raw('commissions+'  . $valuer_deposit),

                                        ]);

                                        // send comission valeur admin 

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_com_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }

                                        // Notifications Users and Admins
                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation  ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        $this->emit('transaction_inter_sends', $walletInter->id);

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    } else {

                                        $valeur_send_admin = $amount_conversion;


                                        if (sabdy()->country->code == 'CI') {

                                            $message = 'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie
                                                ->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }

                                        // send comission valeur admin 

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_send_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }
                                        // Notifications Users and Admins
                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation  ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        $this->emit('transaction_inter_sends', $walletInter->id);

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    }
                                }
                            } elseif (sabdy()->wallet->currencie->code == 'GNF') {

                                $valeur_amount_com = $amount_conversion *  sabdy()->wallet->currencie->value;

                                // Montant Update cfa
                                $valeur_amount_cfa =  $offer->amount *  sabdy()->wallet->currencie->value;

                                // Send in compte Client
                                $val_amount =   $valeur_amount_cfa + $valeur_amount_com;

                                if ($val_amount > sabdy()->wallet->amount) {
                                    $this->resetInterField();
                                    $this->emit('modal-hide-inters');
                                    $this->emit('error', 'Verifier votre montant svp ou votre pays d\'envoie !');
                                } else {

                                    $amount_solde =  sabdy()->wallet->amount -  $val_amount;

                                    $walletInter = WalletInter::create([
                                        'amount_solde'  =>   $amount_solde ?? null,
                                        'wallet_id' => sabdy()->wallet->id,
                                        'amount' => $valeur_amount_cfa,
                                        'inter_cost_id' => null,
                                        'payment_cost_id' => 1,
                                        'phone' => $country_robus->indecative . $this->paiement_phone_canal_inter ??  null,
                                        'number_compte' => $this->account_number_inter ?? null,
                                        'created_by' => sabdy()->id,
                                        'provider_id' => $provider_payment->id,
                                        'status' => 'PENDING',
                                        'country_id' => $country_robus->id,
                                        'number_decoder' => $this->paiement_decoder_canal_inter ?? null,
                                        'id_invoice' => $this->paiement_id_invoice_inter ?? $this->paiement_id_invoice,
                                        'number_contract' => $this->paiement_num_contrat_inter ??  null,
                                        'phone_invoice' =>  $this->pre_phone_invoice ?? null,
                                        'id_invoice_pre' => $this->pre_id_invoice_inter ?? null,
                                        'name' => $this->paiement_name_sodecie_inter ?? $this->paiement_name_cie_inter ?? null,
                                        'provider_type' => 5,
                                        'number_phone' => $this->paiement_phone_canal_inter ?? null,
                                        'offer_id' => $offer->id ?? null,
                                        'costs_amount' =>  $valeur_amount_com,
                                        'status' => 'PENDING',
                                        'paiementChoix_phone' =>  $this->paiementChoix_phone ?? null,
                                        'paiementChoix_type' => $this->paiementChoix_type ?? null,
                                        'factureChoix_type' =>  $this->facture_choix_type ?? null,
                                    ]);

                                    $commission = $walletInter->amount + $valeur_amount_com;


                                    sabdy()->wallet->update([
                                        'reload_by' => sabdy()->firstname .
                                            ' ' . sabdy()->lastname,
                                        'sid_user_relod' => sabdy()->id,
                                        'amount' => DB::raw('amount-' . $commission),
                                    ]);


                                    // Commissions Inters UnderDistributer
                                    if (sabdy()->is_under_distributer == 1) {

                                        // Admin Comission 
                                        $valeur_com_admin =   $amount_conversion - $percent_robus->comission_canal;
                                        // Commission distributer 

                                        $valuer_deposit =      $percent_robus->comission_canal / sabdy()->wallet->currencie->value;

                                        $walletInter->update([
                                            'commissions' => DB::raw('commissions+'  . $valuer_deposit),

                                        ]);

                                        // send comission valeur admin 

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_com_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }

                                        // Commissions Inters UnderDistributer


                                        if (sabdy()->country->code == 'CI') {

                                            $message = 'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }

                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation  ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        $this->emit('transaction_inter_sends', $walletInter->id);

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    } else {

                                        // Admin Comission 
                                        $valeur_com_admin =   $amount_conversion - $percent_robus->comission_canal;
                                        // Commission distributer 

                                        $valeur_send_admin = $amount_conversion;


                                        // send comission valeur admin 

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_send_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }

                                        // Commissions Inters UnderDistributer

                                        if (sabdy()->country->code == 'CI') {

                                            $message = 'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }

                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation  ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        $this->emit('transaction_inter_sends', $walletInter->id);

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    }
                                }
                            } elseif (sabdy()->wallet->currencie->code == 'CFA') {

                                $valeur_amount_com = $amount_conversion;

                                $val_amount =  $offer->amount + $valeur_amount_com;

                                if ($val_amount > sabdy()->wallet->amount) {
                                    $this->resetInterField();
                                    $this->emit('modal-hide-inters');
                                    $this->emit('error', 'Verifier votre montant svp ou votre pays d\'envoie !');
                                } else {

                                    $amount_solde =  sabdy()->wallet->amount -  $val_amount;

                                    $walletInter = WalletInter::create([
                                        'wallet_id' => sabdy()->wallet->id,
                                        'amount_solde'  =>   $amount_solde ?? null,
                                        'amount' => $offer->amount,
                                        'inter_cost_id' => null,
                                        'payment_cost_id' => 1,
                                        'phone' => $country_robus->indecative . $this->paiement_phone_canal_inter ?? null,
                                        'number_compte' => $this->account_number_inter ?? null,
                                        'created_by' => sabdy()->id,
                                        'provider_id' => $provider_payment->id,
                                        'status' => 'PENDING',
                                        'country_id' => $country_robus->id,
                                        'number_decoder' =>  $this->paiement_decoder_canal_inter ?? null,
                                        'id_invoice' => $this->paiement_id_invoice_inter ?? $this->paiement_id_invoice,
                                        'number_contract' =>  $this->paiement_num_contrat_inter ??  null,
                                        'phone_invoice' =>  $this->pre_phone_invoice ?? null,
                                        'id_invoice_pre' =>  $this->pre_id_invoice_inter ?? null,
                                        'name' =>   $this->paiement_name_sodecie_inter ?? $this->paiement_name_cie_inter ?? null,
                                        'provider_type' => 5,
                                        'number_phone' => $this->paiement_phone_canal_inter ??  null,
                                        'offer_id' => $offer->id ?? null,
                                        'status' => 'PENDING',
                                        'costs_amount' =>  $valeur_amount_com,
                                        'paiementChoix_phone' => $this->paiementChoix_phone ?? null,
                                        'paiementChoix_type' => $this->paiementChoix_type ?? null,
                                        'factureChoix_type' =>  $this->facture_choix_type ?? null,
                                    ]);

                                    $commission = $walletInter->amount + $valeur_amount_com;

                                    sabdy()->wallet->update([
                                        'reload_by' => sabdy()->firstname .
                                            ' ' . sabdy()->lastname,
                                        'sid_user_relod' => sabdy()->id,
                                        'amount' => DB::raw('amount-' . $commission),
                                    ]);



                                    // Commissions Inters UnderDistributer
                                    if (sabdy()->is_under_distributer == 1) {

                                        // Admin Comission 
                                        $valeur_com_admin =   $amount_conversion  -  $percent_robus->comission_canal;

                                        // Commission distributer canal
                                        $valuer_deposit =      $percent_robus->comission_canal;

                                        $valeur_send_admin = $amount_conversion;

                                        $walletInter->update([
                                            'commissions' => DB::raw('commissions+'  . $valuer_deposit),

                                        ]);

                                        // send comission valeur admin 

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_com_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }


                                        if (sabdy()->country->code == 'CI') {

                                            $message = 'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }

                                        // Notifications Users and admins
                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation  ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        $this->emit('transaction_inter_sends', $walletInter->id);

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    } else {


                                        $valeur_send_admin = $amount_conversion;

                                        // send comission valeur admin 

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_send_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }


                                        if (sabdy()->country->code == 'CI') {

                                            $message = 'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }

                                        // Notifications Users and admins
                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation  ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        $this->emit('transaction_inter_sends', $walletInter->id);

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } elseif ($this->paiementChoix_phone == 2) {

                        $this->validate(
                            [
                                'offer_paiement' => 'required',
                            ],
                            [
                                'offer_paiement.required' =>
                                'Offre  est obligatoire',
                            ]
                        );

                        $percent_robus = PaymentCost::whereId(1)->first();

                        // Conversion in Price amount
                        $amount_conversion = $percent_robus->amount_canal;

                        if ($som_transfers >= $valeur_ceiling) {
                            $this->resetInterField();
                            $this->emit('modal-hide-inters');
                            $this->emit(
                                'warning',
                                'Votre montant journier est atteint !'
                            );
                        } else {
                            if (sabdy()->wallet->currencie->value >  $country_civ->currencie->value) {
                                // Comission

                                $valeur_amount_com = $amount_conversion / sabdy()->wallet->currencie->value;

                                // Montant Update
                                $valeur_amount = $offer->amount / sabdy()->wallet->currencie->value;

                                $val_amount = $valeur_amount + $amount_conversion;


                                if ($val_amount > sabdy()->wallet->amount) {
                                    $this->resetPaiementField();
                                    $this->emit('modal-hide-paiement');
                                    $this->emit('error', 'Verifier votre montant  svp ou le pays d\'envoie !');
                                } else {

                                    $amount_solde =  sabdy()->wallet->amount -  $val_amount;

                                    $walletInter = WalletInter::create([
                                        'amount_solde'  =>   $amount_solde ?? null,
                                        'wallet_id' => sabdy()->wallet->id,
                                        'amount' => $valeur_amount,
                                        'inter_cost_id' => null,
                                        'payment_cost_id' => 1,
                                        'phone' =>  $country_robus->indecative . $this->paiement_phone_canal_inter ?? null,
                                        'number_compte' => $this->account_number_inter ?? null,
                                        'created_by' => sabdy()->id,
                                        'provider_id' => $provider_payment->id,
                                        'status' => 'PENDING',
                                        'country_id' =>  $country_robus->id,
                                        'number_decoder' => $this->paiement_decoder_canal_inter ?? null,
                                        'id_invoice' =>  $this->paiement_id_invoice_inter ??  $this->paiement_id_invoice,
                                        'number_contract' => $this->paiement_num_contrat_inter ??   null,
                                        'phone_invoice' => $this->pre_phone_invoice ?? null,
                                        'id_invoice_pre' => $this->pre_id_invoice_inter ?? null,
                                        'name' =>  $this->paiement_name_sodecie_inter ?? $this->paiement_name_cie_inter ??  null,
                                        'provider_type' =>  5,
                                        'number_phone' => $this->paiement_phone_canal ?? null,
                                        'offer_id' => $offer->id ?? null,
                                        'costs_amount' =>  $valeur_amount_com,
                                        'status' => 'PENDING',
                                        'paiementChoix_phone' => $this->paiementChoix_phone ?? null,
                                        'paiementChoix_type' =>  $this->paiementChoix_type ?? null,
                                        'factureChoix_type' =>  $this->facture_choix_type ?? null,
                                    ]);

                                    $commission = $walletInter->amount + $valeur_amount_com;


                                    sabdy()->wallet->update([
                                        'reload_by' => sabdy()->firstname .
                                            ' ' . sabdy()->lastname,
                                        'sid_user_relod' => sabdy()->id,
                                        'amount' => DB::raw('amount-' . $commission),
                                    ]);



                                    // Commissions Inters UnderDistributer
                                    if (sabdy()->is_under_distributer == 1) {

                                        // Admin Comission 
                                        $valeur_com_admin =   $amount_conversion  -  $percent_robus->comission_canal;

                                        // Commission distributer canal
                                        $valuer_deposit =      $percent_robus->comission_canal / sabdy()->wallet->currencie->value;


                                        $walletInter->update([
                                            'commissions' => DB::raw('commissions+'  . $valuer_deposit),

                                        ]);

                                        // send comission valeur admin 

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_com_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }

                                        if (sabdy()->country->code == 'CI') {
                                            $message =  'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }

                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        $this->emit('transaction_inter_sends', $walletInter->id);

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    } else {


                                        $valeur_send_admin = $amount_conversion;

                                        // send comission valeur admin 

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_send_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }

                                        if (sabdy()->country->code == 'CI') {
                                            $message =  'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }

                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        $this->emit('transaction_inter_sends', $walletInter->id);

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    }
                                }
                            } elseif (sabdy()->wallet->currencie->code == 'GNF') {

                                $valeur_amount_com =  $amount_conversion * sabdy()->wallet->currencie->value;

                                // Montant Update cfa
                                $valeur_amount_cfa =  $offer->amount * sabdy()->wallet->currencie->value;

                                // Send in compte Client
                                $val_amount =   $valeur_amount_cfa + $valeur_amount_com;

                                if ($val_amount > sabdy()->wallet->amount) {
                                    $this->resetPaiementField();
                                    $this->emit('modal-hide-paiement');
                                    $this->emit('error', 'Verifier votre montant  svp ou le pays d\'envoie !');
                                } else {

                                    $amount_solde =  sabdy()->wallet->amount -  $val_amount;

                                    $walletInter = WalletInter::create([
                                        'amount_solde'  =>   $amount_solde ?? null,
                                        'wallet_id' => sabdy()->wallet->id,
                                        'amount' => $valeur_amount_cfa,
                                        'inter_cost_id' => null,
                                        'payment_cost_id' => 1,
                                        'phone' =>  $country_robus->indecative . $this->paiement_phone_canal_inter ??  null,
                                        'number_compte' =>  $this->account_number_inter ?? null,
                                        'created_by' => sabdy()->id,
                                        'provider_id' => $provider_payment->id,
                                        'status' => 'PENDING',
                                        'country_id' =>  $country_robus->id,
                                        'number_decoder' => $this->paiement_decoder_canal_inter ?? null,
                                        'id_invoice' => $this->paiement_id_invoice_inter ?? $this->paiement_id_invoice,
                                        'number_contract' =>  $this->paiement_num_contrat_inter ?? null,
                                        'phone_invoice' => $this->pre_phone_invoice ?? null,
                                        'id_invoice_pre' => $this->pre_id_invoice_inter ?? null,
                                        'name' =>  $this->paiement_name_sodecie_inter ?? $this->paiement_name_cie_inter ??  null,
                                        'provider_type' => 5,
                                        'number_phone' =>  $this->paiement_phone_canal ?? null,
                                        'offer_id' => $offer->id ?? null,
                                        'costs_amount' =>  $valeur_amount_com,
                                        'status' => 'PENDING',
                                        'paiementChoix_phone' => $this->paiementChoix_phone ?? null,
                                        'paiementChoix_type' =>  $this->paiementChoix_type ?? null,
                                        'factureChoix_type' => $this->facture_choix_type ?? null,
                                    ]);

                                    $commission = $walletInter->amount + $valeur_amount_com;

                                    sabdy()->wallet->update([
                                        'reload_by' => sabdy()->firstname .
                                            ' ' . sabdy()->lastname,
                                        'sid_user_relod' => sabdy()->id,
                                        'amount' => DB::raw('amount-' . $commission),
                                    ]);

                                    // Commissions Inters UnderDistributer
                                    if (sabdy()->is_under_distributer == 1) {

                                        // Admin Comission 

                                        $valeur_com_admin =   $amount_conversion - $percent_robus->comission_canal;
                                        // Commission distributer 

                                        $valuer_deposit =      $percent_robus->comission_canal / sabdy()->wallet->currencie->value;


                                        $walletInter->update([
                                            'commissions' => DB::raw('commissions+'  . $valuer_deposit),

                                        ]);

                                        // send comission valeur admin 

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_com_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }


                                        if (sabdy()->country->code == 'CI') {

                                            $message = 'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }

                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        $this->emit('transaction_inter_sends', $walletInter->id);

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    } else {

                                        // Admin Comission 

                                        $valeur_com_admin =   $amount_conversion - $percent_robus->comission_canal;

                                        $valeur_send_admin = $amount_conversion;

                                        // send comission valeur admin 

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_send_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }


                                        if (sabdy()->country->code == 'CI') {

                                            $message = 'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }

                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    }
                                }
                            } elseif (sabdy()->wallet->currencie->code == 'CFA') {

                                $valeur_amount_com = $amount_conversion;

                                $val_amount = $offer->amount + $valeur_amount_com;

                                if ($val_amount > sabdy()->wallet->amount || $country_robus->id == sabdy()->country->id) {
                                    $this->resetPaiementField();
                                    $this->emit('modal-hide-paiement');
                                    $this->emit('error', 'Verifier votre montant  svp ou le pays d\'envoie !');
                                } else {

                                    $amount_solde =  sabdy()->wallet->amount -  $val_amount;

                                    $walletInter = WalletInter::create([
                                        'amount_solde'  =>   $amount_solde ?? null,
                                        'wallet_id' => sabdy()->wallet->id,
                                        'amount' => $offer->amount,
                                        'inter_cost_id' => null,
                                        'payment_cost_id' => 1,
                                        'phone' =>  $country_robus->indecative . $this->paiement_phone_canal_inter ??  null,
                                        'number_compte' => $this->account_number_inter ?? null,
                                        'created_by' => sabdy()->id,
                                        'provider_id' => $provider_payment->id,
                                        'status' => 'PENDING',
                                        'country_id' =>  $country_robus->id,
                                        'number_decoder' => $this->paiement_decoder_canal_inter ?? null,
                                        'id_invoice' => $this->paiement_id_invoice_inter ?? $this->paiement_id_invoice,
                                        'number_contract' => $this->paiement_num_contrat_inter ??  null,
                                        'phone_invoice' => $this->pre_phone_invoice ?? null,
                                        'id_invoice_pre' =>  $this->pre_id_invoice_inter ?? null,
                                        'name' =>  $this->paiement_name_sodecie_inter ?? $this->paiement_name_cie_inter ?? null,
                                        'provider_type' => 5,
                                        'number_phone' =>  $this->paiement_phone_canal ?? null,
                                        'offer_id' => $offer->id ?? null,
                                        'costs_amount' =>  $valeur_amount_com,
                                        'status' => 'PENDING',
                                        'paiementChoix_phone' =>  $this->paiementChoix_phone ?? null,
                                        'paiementChoix_type' => $this->paiementChoix_type ?? null,
                                        'factureChoix_type' =>  $this->facture_choix_type ?? null,
                                    ]);

                                    $commission = $walletInter->amount + $valeur_amount_com;

                                    sabdy()->wallet->update([
                                        'reload_by' => sabdy()->firstname .
                                            ' ' . sabdy()->lastname,
                                        'sid_user_relod' => sabdy()->id,
                                        'amount' => DB::raw('amount-' . $commission),
                                    ]);


                                    // Commissions Inters UnderDistributer
                                    if (sabdy()->is_under_distributer == 1) {

                                        // Admin Comission 
                                        $valeur_com_admin =   $amount_conversion  -  $percent_robus->comission_canal;

                                        // Commission distributer canal
                                        $valuer_deposit =      $percent_robus->comission_canal;

                                        $valeur_send_admin = $amount_conversion;

                                        $walletInter->update([
                                            'commissions' => DB::raw('commissions+'  . $valuer_deposit),

                                        ]);

                                        // send comission valeur admin 

                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_com_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }


                                        if (sabdy()->country->code == 'CI') {

                                            $message = 'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }

                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        $this->emit('transaction_inter_sends', $walletInter->id);

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    } else {

                                        $valeur_send_admin = $amount_conversion;

                                        // send comission valeur admin 
                                        if ($admin_civ->count() > 0) {

                                            $nmbre_admin = $admin_civ->count();

                                            $valeur_single = $valeur_send_admin / $nmbre_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                AdminsCom::create([
                                                    'amount' =>   $valeur_single,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'wallet_transaction_id' => null,
                                                    'wallet_bank_id' => null,
                                                    'wallet_deposit_id' => null,
                                                    'wallet_inter_id' =>  $walletInter->id,
                                                    'wallet_payment_id' => null,
                                                    'wallet_phone_credit_id' => null,
                                                    'wallet_reload_id' => null,
                                                    'bank_card_id' => null,
                                                    'description' => 'Commission',
                                                ]);
                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_single),
                                                ]);
                                            }
                                        }

                                        if (sabdy()->country->code == 'CI') {

                                            $message = 'Vous avez effectué un paiement de ' .
                                                $walletInter->amount .
                                                sabdy()->wallet->currencie->code .
                                                ' ' .
                                                'par ' .
                                                $walletInter->provider->name .
                                                ' a ' .
                                                $walletInter->created_at .
                                                ' votre demande est en-cours de validation ' .
                                                'Robus vous remercie';

                                            $encode = substr(sabdy()->phone, -10);

                                            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
                                        }

                                        $msg_title = 'Transfert internationnal';
                                        $msg = 'Demande de paiement est en cours de validation ';
                                        sabdy()->notify(
                                            new UserNotification([
                                                'title' => $msg_title,
                                                'message' => $msg,
                                            ])
                                        );
                                        $notifs = sabdy()->unreadNotifications;
                                        event(new CreateWalletInterEvent($walletInter));
                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                        Notification::send($admins, new AdminNotification('Demande de paiement de facture de ' .
                                            ' ' . $walletInter->wallet->sidUser->firstname));
                                        $this->emit('transaction_inter_sends', $walletInter->id);

                                        if ($admins->count() > 0) {

                                            foreach ($admins  as $user) {

                                                $admin_single = SidUser::find($user->id);

                                                $notifs = $admin_single->unreadNotifications;

                                                event(new AdminNotifEvent($notifs));
                                            }

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        } else {

                                            // $notifs = $admin->unreadNotifications;
                                            // event(new AdminNotifEvent($notifs));
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('success', 'Transfers effectué !');
                                            if (sabdy()->email != null) {
                                                $notify = new MailController();
                                                $notify->newPaiementInter($walletInter);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $this->emit('transaction_inters');
                }
            } else {
                $this->emit('error', 'Internationnal existante !');
            }
        } catch (\Exception $exception) {
            $this->resetInterField();
            $this->emit('modal-hide-inters');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    public function render()
    {

        $offers = Offer::whereStatus(true)
            ->latest()
            ->orderBy('name', 'asc')
            ->get();

        $country_civ = Country::whereId(107)
            // ->whereStatus(true)
            ->first();
        $default_valeur = 2000000;
        $commission_paiement_invoice_val_inter_pay = null;
        $commission_paiement_invoice_val_inter_receive = null;
        $commission_paiement_invoice_canal_val_inter = null;
        $valeur_auth_devise  = null;

        $amount_received_canal = null;

        $inter_search = WalletInter::query();

        if ($this->offer_paiement != '') {


            if (sabdy()->wallet->currencie->value >  $country_civ->currencie->value) {

                $commission_invoice = PaymentCost::whereId(1)->first();

                foreach ($offers  as $offer) {

                    $offer_amount = $offer->whereId($this->offer_paiement)->first();

                    $valeur_offer =  $offer_amount->amount + $commission_invoice->amount_canal;

                    if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                        $amount_received_canal =  $commission_invoice->comission_canal / sabdy()->wallet->currencie->value;
                    }

                    $commission_paiement_invoice_canal_val_inter = number_format($valeur_offer / sabdy()->wallet->currencie->value, 2, ',', ' ');
                }
            } elseif (sabdy()->wallet->currencie->value <  $country_civ->currencie->value) {

                $commission_invoice = PaymentCost::whereId(1)->first();

                foreach ($offers  as $offer) {

                    $offer_amount = $offer->whereId($this->offer_paiement)->first();

                    $valeur_offer =  $offer_amount->amount + $commission_invoice->amount_canal;

                    if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                        $amount_received_canal = $commission_invoice->comission_canal * sabdy()->wallet->currencie->value;
                    }

                    $commission_paiement_invoice_canal_val_inter =  number_format($valeur_offer * sabdy()->wallet->currencie->value, 2, ',', ' ');
                }
            } elseif (sabdy()->wallet->currencie->code == 'CFA') {

                if (sabdy()->wallet->country->code = 'CI') {

                    $commission_invoice = PaymentCost::whereId(2)->first();

                    foreach ($offers  as $offer) {

                        $offer_amount = $offer->whereId($this->offer_paiement)->first();

                        $valeur_offer =  $offer_amount->amount + $commission_invoice->amount_canal;

                        if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                            $amount_received_canal = $commission_invoice->comission_canal;
                        }


                        $commission_paiement_invoice_canal_val_inter =  number_format($valeur_offer, 2, ',', ' ');
                    }
                } else {

                    $commission_invoice = PaymentCost::whereId(1)->first();

                    foreach ($offers  as $offer) {

                        $offer_amount = $offer->whereId($this->offer_paiement)->first();

                        $valeur_offer =  $offer_amount->amount + $commission_invoice->amount_canal;

                        if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                            $amount_received_canal = $commission_invoice->comission_canal;
                        }

                        $commission_paiement_invoice_canal_val_inter =  number_format($valeur_offer, 2, ',', ' ');
                    }
                }
            }
        }

        if ($this->paiement_amount_total_inter != '') {

            $commission_invoice = PaymentCost::whereId(1)->first();

            if (sabdy()->wallet->currencie->value >  $country_civ->currencie->value) {

                // Conversion valeur in Auth devise User
                $commission_paiement_invoice_val_inter_pay = number_format($commission_invoice->amount_auther / sabdy()->wallet->currencie->value, 2, ',', ' ');

                if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                    $valeur_auth_devise =   $commission_invoice->comission_auther /  sabdy()->wallet->currencie->value;
                }

                $valeur_receive =  (float) $this->paiement_amount_total_inter;

                if ($valeur_receive < 0) {

                    $commission_paiement_invoice_val_inter_receive = "Montant negatif";
                } else {

                    $commission_paiement_invoice_val_inter_receive =  number_format($valeur_receive, 2, ',', ' ');
                }
            } elseif (sabdy()->wallet->currencie->value <  $country_civ->currencie->value) {

                $commission_paiement_invoice_val_inter_pay = number_format($commission_invoice->amount_auther * sabdy()->wallet->currencie->value, 2, ',', ' ');

                if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                    $valeur_auth_devise =   $commission_invoice->comission_auther  *   sabdy()->wallet->currencie->value;
                }

                $valeur_receive = (float) $this->paiement_amount_total_inter;

                if ($valeur_receive < 0) {

                    $commission_paiement_invoice_val_inter_receive = "Montant negatif";
                } else {

                    $commission_paiement_invoice_val_inter_receive = number_format($valeur_receive * sabdy()->wallet->currencie->value, 2, ',', ' ');
                }
            } elseif (sabdy()->wallet->currencie->code == 'CFA') {

                $commission_paiement_invoice_val_inter_pay = $commission_invoice->amount_auther;

                if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                    $valeur_auth_devise =   $commission_invoice->comission_auther;
                }

                $valeur_receive =   (float) $this->paiement_amount_total_inter;

                if ($valeur_receive < 0) {

                    $commission_paiement_invoice_val_inter_receive = "Montant negatif";
                } else {

                    $commission_paiement_invoice_val_inter_receive = (float)  $this->paiement_amount_total_inter;
                }
            }
        }

        if ($this->paiement_amount_t_sodecie_inter != '') {

            $commission_invoice = PaymentCost::whereId(1)->first();

            if (sabdy()->wallet->currencie->value >  $country_civ->currencie->value) {


                // Conversion valeur in Auth devise User
                $commission_paiement_invoice_val_inter_pay = number_format($commission_invoice->amount_auther / sabdy()->wallet->currencie->value, 2, ',', ' ');

                if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                    $valeur_auth_devise =  (float) $commission_invoice->comission_auther /  sabdy()->wallet->currencie->value;
                }

                $valeur_receive =  $this->paiement_amount_t_sodecie_inter;


                if ($valeur_receive < 0) {

                    $commission_paiement_invoice_val_inter_receive = "Montant negatif";
                } else {

                    $commission_paiement_invoice_val_inter_receive =  number_format($valeur_receive, 2, ',', ' ');
                }
            } elseif (sabdy()->wallet->currencie->value <  $country_civ->currencie->value) {


                $commission_paiement_invoice_val_inter_pay = number_format($commission_invoice->amount_auther * sabdy()->wallet->currencie->value, 2, ',', ' ');

                if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                    $valeur_auth_devise =  (float) $commission_invoice->comission_auther  *   sabdy()->wallet->currencie->value;
                }

                $valeur_receive =  $this->paiement_amount_t_sodecie_inter;

                if ($valeur_receive < 0) {

                    $commission_paiement_invoice_val_inter_receive = "Montant negatif";
                } else {

                    $commission_paiement_invoice_val_inter_receive = number_format($valeur_receive * sabdy()->wallet->currencie->value, 2, ',', ' ');
                }
            } elseif (sabdy()->wallet->currencie->code == 'CFA') {

                $commission_paiement_invoice_val_inter_pay = (float) $commission_invoice->amount_auther;

                $valeur_receive =  (float) $this->paiement_amount_t_sodecie_inter;

                if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                    $valeur_auth_devise =  (float) $commission_invoice->comission_auther;
                }
                if ($valeur_receive < 0) {

                    $commission_paiement_invoice_val_inter_receive = "Montant negatif";
                } else {

                    $commission_paiement_invoice_val_inter_receive = (float) $this->paiement_amount_t_sodecie_inter;
                }
            }
        }
        return view('livewire.transaction-payments.payment-invoice', [
            'walletSolde' => Wallet::where(
                'sid_user_id',
                sabdy()->id
            )->sum('amount'),
            'Internationals' => $inter_search
                ->join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
                ->where('wallet_inters.country_id', sabdy()->country->id)
                ->where('wallet_inters.status', 'PENDING')
                ->where('wallet_inters.created_by', sabdy()->id)
                ->select('wallet_inters.*')
                ->orderBy('wallet_inters.created_at', 'desc')
                ->paginate(15),
            'countrie_inter_cf' => Country::whereRobus(true)->whereId(107)
                ->first(),
            'offers_canal' => $offers,
            'countries' => Country::whereRobus(true)
                ->orderBy('name', 'asc')
                ->get(),
            'amount_paiement_invoice_auther_inter_pay' => $commission_paiement_invoice_val_inter_pay,
            'amount_paiement_invoice_auther_inter_receive' => $commission_paiement_invoice_val_inter_receive,
            'amount_paiement_invoice_canal_inter' => $commission_paiement_invoice_canal_val_inter,
            'amount_received_canal' => $amount_received_canal,
            'amount_received_payment' => $valeur_auth_devise,
            'provide_west_africa' => Provider::whereIn('id', array(1, 3, 4, 6, 9))
                ->orderBy('name', 'asc')
                ->get(),
            'provider_paiements_inter' => $this->PaymentInterTransaction,
            'usersCountry' => sabdy()->wallet->currencie->code,
            'countrie_number_local' => sabdy()->country
                ->indecative,
        ]);
    }

    public function getPaymentInterTransactionProperty()
    {

        $payment_inter = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', sabdy()->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 7)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();

        return $payment_inter;
    }

    public function getSumValTransactionProperty()
    {

        $somm_valeur =  $this->WalletBanksCeiling + $this->WalletDepositsCeiling +
            $this->WalletIntersCeiling +
            $this->WalletPaymentsCeiling +
            $this->WalletTransactionsCeiling +
            $this->WalletPhoneCreditsCeiling +
            $this->WalletReloadsCeiling + $this->WalletBankCardCeiling;

        return  $somm_valeur;
    }

    // Verify ceilling

    public function getWalletBanksCeilingProperty()
    {
        $data = [];
        foreach (
            WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where(
                    'wallets.country_id',
                    sabdy()->country->id
                )
                ->where(
                    'wallet_banks.created_by',
                    sabdy()->id
                )
                ->whereDate('wallet_banks.created_at', Carbon::today())
                ->select('wallet_banks.*')
                ->where('wallet_banks.status', 'SUCCESS')
                ->get()
            as $bank
        ) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletDepositsCeilingProperty()
    {
        $data = [];

        foreach (
            WalletDeposit::join(
                'wallets',
                'wallet_deposits.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    sabdy()->country->id
                )
                ->where(
                    'wallet_deposits.created_by',
                    sabdy()->id
                )
                ->whereDate('wallet_deposits.created_at', Carbon::today())
                ->select('wallet_deposits.*')
                ->where('wallet_deposits.status', 'SUCCESS')
                ->get()
            as $deposit
        ) {
            $data[] = $deposit->amount;
        }

        return array_sum($data);
    }

    public function getWalletIntersCeilingProperty()
    {
        $data = [];

        foreach (
            WalletInter::join(
                'wallets',
                'wallet_inters.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    sabdy()->country->id
                )
                ->where(
                    'wallet_inters.created_by',
                    sabdy()->id
                )
                ->whereDate('wallet_inters.created_at', Carbon::today())
                ->select('wallet_inters.*')
                ->where('wallet_inters.status', 'SUCCESS')
                ->get()
            as $inter
        ) {
            $data[] = $inter->amount;
        }

        return array_sum($data);
    }

    public function getWalletPaymentsCeilingProperty()
    {
        $data = [];

        foreach (
            WalletPayment::join(
                'wallets',
                'wallet_payments.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    sabdy()->country->id
                )
                ->where(
                    'wallet_payments.created_by',
                    sabdy()->id
                )
                ->whereDate('wallet_payments.created_at', Carbon::today())
                ->select('wallet_payments.*')
                ->where('wallet_payments.status', 'SUCCESS')
                ->get()
            as $payment
        ) {
            $data[] = $payment->amount;
        }

        return array_sum($data);
    }

    public function getWalletTransactionsCeilingProperty()
    {
        $data = [];

        foreach (
            WalletTransaction::join(
                'wallets',
                'wallet_transactions.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    sabdy()->country->id
                )
                ->where(
                    'wallet_transactions.created_by',
                    sabdy()->id
                )
                ->whereDate('wallet_transactions.created_at', Carbon::today())
                ->select('wallet_transactions.*')
                ->where('wallet_transactions.status', 'SUCCESS')
                ->get()
            as $transaction
        ) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }

    public function getWalletPhoneCreditsCeilingProperty()
    {
        $data = [];

        foreach (
            WalletPhoneCredit::join(
                'wallets',
                'wallet_phone_credits.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    sabdy()->country->id
                )
                ->where(
                    'wallet_phone_credits.created_by',
                    sabdy()->id
                )
                ->whereDate('wallet_phone_credits.created_at', Carbon::today())
                ->select('wallet_phone_credits.*')
                ->where('wallet_phone_credits.status', 'SUCCESS')
                ->get()
            as $phone
        ) {
            $data[] = $phone->amount;
        }

        return array_sum($data);
    }

    public function getWalletBankCardCeilingProperty()
    {
        $data = [];
        foreach (
            BankCard::where('created_by', sabdy()->id)
                ->whereDate('created_at', Carbon::today())->whereStatus(1)->get() as $bank
        ) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletReloadsCeilingProperty()
    {
        $data = [];

        foreach (
            WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                ->where(
                    'wallets.country_id',
                    sabdy()->country->id
                )->where(
                    'wallet_reloads.created_by',
                    sabdy()->id
                )
                ->whereDate('wallet_reloads.created_at', Carbon::today())
                ->select('wallet_reloads.*')
                ->where('wallet_reloads.status', 'SUCCESS')
                ->get()
            as $reload
        ) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }


    public function getAdminDrawalCIVProperty()
    {

        $admin_drawal =  SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->whereId(1)
            ->where('country_id', 107)
            ->first();

        return $admin_drawal;
    }

    public function getAdminValeurProperty()
    {

        $admins = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', sabdy()->country->id)
            ->get();

        return  $admins;
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
