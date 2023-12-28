<?php

namespace App\Http\Livewire\TransactionPayments;

use Livewire\Component;
use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use App\Models\Banks\BankCard;
use App\Models\Costs\BankCost;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Events\AdminNotifEvent;
use App\Models\Costs\DepositCost;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use App\Models\Wallets\WalletBank;
use Illuminate\Support\Facades\DB;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;
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

class VirementTransaction extends Component
{
    public $account_number_user_bank_inter;
    public $firstname_user_inter;
    public $lastname_user_inter;
    public $country_inter_robus;
    public $phone_user_inter;
    public $amount_virement_inter;
    public $interId;
    public $provider_Inter_virement;


    public function resetValeurReload()
    {
        $this->account_number_user_bank_inter = '';
        $this->firstname_user_inter = '';
        $this->lastname_user_inter = '';
        $this->amount_virement_inter = '';
        $this->phone_user_inter = '';
        $this->country_inter_robus = null;
        $this->provider_Inter_virement = null;
        // $this->emit('modal-hide-transfers-validated');
        // $this->emit('modal-hide-transfers');
    }

    public function resetInterField()
    {
        $this->account_number_user_bank_inter = '';
        $this->firstname_user_inter = '';
        $this->lastname_user_inter = '';
        $this->country_inter_robus = null;
        $this->amount_virement_inter = '';
        $this->phone_user_inter = '';
        $this->provider_Inter_virement = null;
        $this->emit('menu-inters-active');
        $this->emit('modal-hide-inters');
        $this->emit('transaction_inters');
    }
    public function mount()
    {
        $this->resetValeurReload();
        // $this->emit('monEvenement', $resetValeurReload);
    }

    public function cancelInterVirementField()
    {
        $this->account_number_user_bank_inter = '';
        $this->firstname_user_inter = '';
        $this->lastname_user_inter = '';
        $this->country_inter_robus = null;
        $this->amount_virement_inter = '';
        $this->phone_user_inter = '';
        $this->provider_Inter_virement = null;
        $this->emit('menu-inters-active');
        $this->emit('modal-hide-inters');
        $this->emit('transaction_inters');
    }

    public function addInterVirement()
    {
        $check = WalletInter::whereId($this->interId)->first();
        $country_robus = Country::whereId($this->country_inter_robus_inter)
            // ->whereStatus(true)
            ->first();

        $country_civ = Country::whereId(107)
            // ->whereStatus(true)
            ->first();

        $provider_virement = Provider::whereId($this->provider_Inter_virement)->first();

        $admin_civ = $this->getAdminCIVProperty();
        $som_transfers =  $this->getSumValTransactionProperty();

        // Valuer amount cota
        $valeur_ceiling = sabdy()->silver_ceiling;

        try {

            if (!$check) {

                if ($this->provider_Inter_virement == 59 || $this->provider_Inter_virement == 60) {

                    $this->validate(
                        [
                            'account_number_user_bank_inter' => 'required',
                            'firstname_user_inter' => 'required',
                            'lastname_user_inter' => 'required',
                            'country_inter_robus' => 'required',
                            'phone_user_inter' => 'required',
                            'amount_virement_inter' => 'required',
                        ],
                        [
                            'lastname_user_inter.required' => 'Champ est obligatoire',
                            'firstname_user_inter.required' => 'Champ  est obligatoire',
                            'account_number_user_bank_inter.required' => 'Numero  est obligatoire',
                            'country_inter_robus.required' => 'Pays  est obligatoire',
                            'phone_user_inter.required' => 'Champ  est obligatoire',
                            'amount_virement_inter.required' => 'Champ  est obligatoire',
                        ]
                    );


                    $percent_robus = BankCost::whereId(1)->first();

                    if (sabdy()->wallet->currencie->value >  $country_civ->currencie->value) {

                        // Covertion valeur in CFA
                        $valeur_amount = $this->amount_virement_inter *  sabdy()->wallet->currencie->value;

                        // Verify valeur amount max in Landing the ROBUS
                        $valeur_end = $percent_robus->levelBanks->last();

                        //  Conversion valeur
                        $conversion_valeur_end  = $valeur_end->amount_end /  sabdy()->wallet->currencie->value;

                        if ($conversion_valeur_end  < $this->amount_virement_inter) {
                            $this->resetInterField();
                            $this->emit('modal-hide-inters');
                            $this->emit('info', 'Vous avez depasser le montant max !');
                        } else {

                            foreach ($percent_robus->levelBanks as $amount_user) {

                                if ($valeur_amount >= $amount_user->amount_begin &&  $valeur_amount <= $amount_user->amount_end) {


                                    if ($som_transfers >= $valeur_ceiling) {
                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit(
                                            'warning',
                                            'Votre montant journier est atteint !'
                                        );
                                    } else {

                                        if (sabdy()->is_under_distributer == 1) {

                                            // Admin valeur comission
                                            $val_amount = $valeur_amount;
                                        } else {
                                            // Admin valeur comission
                                            $val_amount = $valeur_amount + $amount_user->percent * $valeur_amount;
                                        }

                                        //Convertion value in Verify account
                                        $Valeur_verify  =  $val_amount  /  sabdy()->wallet->currencie->value;

                                        if ($Valeur_verify > sabdy()->wallet->amount) {
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit('error', 'Verifier votre montant svp ou votre pays !');
                                        } else {

                                            $amount_solde =  sabdy()->wallet->amount -  $Valeur_verify;

                                            $walletInter = WalletInter::create([
                                                'amount_solde'  =>   $amount_solde ?? null,
                                                'wallet_id' => sabdy()->wallet->id,
                                                'amount' =>  $this->amount_virement_inter,
                                                'inter_cost_id' => null,
                                                'bank_cost_id' => 1,
                                                'phone' => $country_robus->indecative . $this->phone_user_inter,
                                                'number_compte' => null,
                                                'created_by' => sabdy()->id,
                                                'provider_id' =>  $provider_virement->id,
                                                'status' => 'PENDING',
                                                'country_id' =>  $country_robus->id,
                                                'number_decoder' => null,
                                                'id_invoice' => null,
                                                'number_contract' => null,
                                                'phone_invoice' =>  null,
                                                'id_invoice_pre' =>  null,
                                                'name' =>  null,
                                                'provider_type' => 7,
                                                'number_phone' =>  null,
                                                'firstname' =>  $this->firstname_user_inter ??  null,
                                                'lastname' => $this->lastname_user_inter ?? null,
                                                'offer_id' => null,
                                                'costs_amount' =>  $amount_user->percent * $valeur_amount,
                                                'paiementChoix_phone' =>  null,
                                                'paiementChoix_type' => null,
                                                'factureChoix_type' => null,
                                            ]);

                                            // Commissions Inters DIstributer Provider ECOBANK and NSIA
                                            if (sabdy()->is_under_distributer == 1) {

                                                // Convertion valuer in Devise Auth

                                                $commission_acompte = $this->amount_virement_inter;

                                                sabdy()->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount-' . $commission_acompte),
                                                ]);

                                                $commission = $amount_user->percent * $this->amount_virement_inter;

                                                // Distributer valeur commission
                                                $val_amount_distr = $amount_user->comission_distributer * $commission;

                                                // Amount valeur in CFA
                                                if (sabdy()->country->id  != 107) {

                                                    $amout_valeur_distri_cfa =   $val_amount_distr  *  sabdy()->country->currencie->value;
                                                } else {

                                                    $amout_valeur_distri_cfa =   $val_amount_distr  /   $country_civ->currencie->value;
                                                }
                                                // send comission valeur admin 

                                                $valeur_received = $walletInter->amount -  ($amount_user->percent * $walletInter->amount);

                                                $walletInter->update([
                                                    'amount_received'  =>  $valeur_received  *  sabdy()->country->currencie->value,
                                                    'commissions' => DB::raw('commissions+'  . $val_amount_distr),
                                                ]);


                                                if ($admin_civ->count() > 0) {

                                                    $nmbre_admin = $admin_civ->count();

                                                    $valeur_single = $amout_valeur_distri_cfa / $nmbre_admin;

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
                                                            'description' => 'Paiement commission sous distributeur',
                                                        ]);
                                                        $admin_single->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $valeur_single),
                                                        ]);
                                                    }
                                                }

                                                if (sabdy()->country->code == 'CI') {
                                                    $message_is_send = 'Votre code de la facture prepéyé est ' . $walletInter->id_invoice_pre;

                                                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$walletInter->phone_invoice&from=ROBUS&sms=$message_is_send");

                                                    $walletInter->update([
                                                        'is_send' => 1,
                                                    ]);
                                                    $message =
                                                        'Vous avez effectué un paiement de ' .
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

                                                    Http::get(
                                                        "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                                    );
                                                }

                                                $msg_title = 'Transfert internationnal';
                                                $msg =
                                                    'Demande de paiement est en cours de validation ';
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
                                                Notification::send($admins, new AdminNotification('Demande de virement de ' .
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
                                            } else {

                                                $commission_acompte = $this->amount_virement_inter + ($amount_user->percent * $this->amount_virement_inter);

                                                sabdy()->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount-' . $commission_acompte),
                                                ]);

                                                $commission = $amount_user->percent * $this->amount_virement_inter;

                                                if (sabdy()->country->id  != 107) {

                                                    $valeur_deposit_conver =   $commission  *  sabdy()->country->currencie->value;
                                                } else {

                                                    $valeur_deposit_conver =   $commission  /   $country_civ->currencie->value;
                                                }

                                                $valeur_received = $walletInter->amount -  ($amount_user->percent * $walletInter->amount);

                                                $walletInter->update([
                                                    'amount_received'  =>  $valeur_received  *  sabdy()->country->currencie->value,
                                                ]);

                                                // send comission valeur admin 

                                                if ($admin_civ->count() > 0) {

                                                    $nmbre_admin = $admin_civ->count();

                                                    $valeur_single = $valeur_deposit_conver / $nmbre_admin;

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
                                                    $message_is_send = 'Votre code de la facture prepéyé est ' . $walletInter->id_invoice_pre;

                                                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$walletInter->phone_invoice&from=ROBUS&sms=$message_is_send");

                                                    $walletInter->update([
                                                        'is_send' => 1,
                                                    ]);
                                                    $message =
                                                        'Vous avez effectué un paiement de ' .
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

                                                    Http::get(
                                                        "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                                    );
                                                }

                                                $msg_title = 'Transfert internationnal';
                                                $msg =
                                                    'Demande de paiement est en cours de validation ';
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
                                                Notification::send($admins, new AdminNotification('Demande de virement de ' .
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
                                    }
                                }
                            }
                        }
                    } elseif (sabdy()->country->currencie->value < $country_robus->currencie->value) {

                        // Montant Update cfa
                        $valeur_amount = $this->amount_virement_inter *   sabdy()->wallet->currencie->value;

                        // Verify valeur amount max in Landing the ROBUS
                        $valeur_end = $percent_robus->levelBanks->last();

                        //  Conversion valeur
                        $conversion_valeur_end  = $valeur_end->amount_end * sabdy()->wallet->currencie->value;

                        if ($conversion_valeur_end  < $this->amount_virement_inter) {
                            $this->resetInterField();
                            $this->emit('modal-hide-inters');
                            $this->emit('info', 'Vous avez depasser le montant max !');
                        } else {

                            // Verifier plafond days
                            foreach ($percent_robus->levelBanks as $amount_user) {

                                if ($valeur_amount >= $amount_user->amount_begin && $valeur_amount <= $amount_user->amount_end) {


                                    if ($som_transfers >= $valeur_ceiling) {
                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('warning', 'Votre montant journier est atteint !');
                                    } else {


                                        if (sabdy()->is_under_distributer == 1) {

                                            $val_amount =  $valeur_amount;
                                        } else {

                                            $val_amount =  $valeur_amount +  $amount_user->percent * $valeur_amount;
                                        }

                                        //Convertion value in Verify account
                                        $Valeur_verify  =  $val_amount  /  sabdy()->wallet->currencie->value;


                                        if ($Valeur_verify > sabdy()->wallet->amount) {
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit(
                                                'error',
                                                'Verifier votre montant svp ou le pays d\'envoie !'
                                            );
                                        } else {

                                            $amount_solde =  sabdy()->wallet->amount -  $Valeur_verify;

                                            $walletInter = WalletInter::create([
                                                'amount_solde'  =>   $amount_solde ?? null,
                                                'wallet_id' => sabdy()->wallet->id,
                                                'amount' =>  $this->amount_virement_inter,
                                                'inter_cost_id' => null,
                                                'bank_cost_id' => 1,
                                                'phone' => $country_robus->indecative . $this->phone_user_inter,
                                                'number_compte' => null,
                                                'created_by' => sabdy()->id,
                                                'provider_id' =>  $provider_virement->id,
                                                'status' => 'PENDING',
                                                'country_id' =>  $country_robus->id,
                                                'number_decoder' => null,
                                                'id_invoice' => null,
                                                'number_contract' => null,
                                                'phone_invoice' =>  null,
                                                'id_invoice_pre' =>  null,
                                                'name' =>  null,
                                                'provider_type' => 7,
                                                'number_phone' =>  null,
                                                'firstname' =>  $this->firstname_user_inter ??  null,
                                                'lastname' => $this->lastname_user_inter ?? null,
                                                'offer_id' => null,
                                                'costs_amount' =>  $amount_user->percent * $valeur_amount,
                                                'paiementChoix_phone' =>  null,
                                                'paiementChoix_type' => null,
                                                'factureChoix_type' => null,
                                            ]);


                                            // Commissions Inters DIstributer Provider ECOBANK and NSIA
                                            if (sabdy()->is_under_distributer == 1) {


                                                $commission_acompte =  $Valeur_verify;


                                                sabdy()->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount-' . $commission_acompte),
                                                ]);

                                                // Convertion valuer in Devise Auth
                                                $commission = $amount_user->percent * $this->amount_virement_inter;

                                                // Distributer valeur commission
                                                $val_amount_distr = $amount_user->comission_distributer * $commission;

                                                // Amount valeur in CFA
                                                $amout_valeur_distri_cfa =  $val_amount_distr / sabdy()->wallet->currencie->value;

                                                $valeur_received = $walletInter->amount -  ($amount_user->percent * $walletInter->amount);

                                                $walletInter->update([
                                                    'amount_received'  =>  $valeur_received * sabdy()->country->currencie->value,
                                                ]);


                                                // send comission valeur admin 

                                                if ($admin_civ->count() > 0) {

                                                    $nmbre_admin = $admin_civ->count();

                                                    $valeur_single = $amout_valeur_distri_cfa / $nmbre_admin;

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
                                                            'description' => 'Paiement commission sous distributeur',
                                                        ]);
                                                        $admin_single->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $valeur_single),
                                                        ]);
                                                    }
                                                }


                                                $walletInter->update([
                                                    'commissions' => DB::raw('commissions+'  . $val_amount_distr),
                                                ]);

                                                $walletInter->update([
                                                    'is_send' => 1,
                                                ]);

                                                if (sabdy()->country->code == 'CI') {

                                                    $message_invoice = 'Votre code de la facture prepéyé est ' . $walletInter->id_invoice_pre;

                                                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$walletInter->phone_invoice&from=ROBUS&sms=$message_invoice");

                                                    $message =
                                                        'Vous avez effectué un paiement de ' .
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

                                                    Http::get(
                                                        "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                                    );
                                                }

                                                $msg_title = 'Transfert internationnal';
                                                $msg = 'Demande de paiement est en cours de validation ';
                                                sabdy()->notify(
                                                    new UserNotification([
                                                        'title' => $msg_title,
                                                        'message' => $msg,
                                                    ])
                                                );
                                                $notifs =
                                                    sabdy()->unreadNotifications;
                                                event(new CreateWalletInterEvent($walletInter));
                                                $this->emit('transaction_inter_sends', $walletInter->id);
                                                $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                                Notification::send($admins, new AdminNotification('Demande de virement de ' .
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
                                            } else {

                                                $commission_acompte =  $this->amount_virement_inter + ($amount_user->percent * $this->amount_virement_inter);


                                                sabdy()->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount-' . $commission_acompte),
                                                ]);
                                                // Convertion valuer in Devise Auth
                                                $commission = $amount_user->percent * $this->amount_virement_inter;

                                                $valeur_deposit_conver =   $commission  /  sabdy()->wallet->currencie->value;

                                                $valeur_received = $walletInter->amount -  ($amount_user->percent * $walletInter->amount);

                                                $walletInter->update([
                                                    'amount_received'  =>  $valeur_received  / sabdy()->country->currencie->value,
                                                ]);

                                                // send comission valeur admin 
                                                if ($admin_civ->count() > 0) {

                                                    $nmbre_admin = $admin_civ->count();

                                                    $valeur_single = $valeur_deposit_conver / $nmbre_admin;

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


                                                $walletInter->update([
                                                    'is_send' => 1,
                                                ]);

                                                if (sabdy()->country->code == 'CI') {

                                                    $message_invoice = 'Votre code de la facture prepéyé est ' . $walletInter->id_invoice_pre;

                                                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$walletInter->phone_invoice&from=ROBUS&sms=$message_invoice");

                                                    $message =
                                                        'Vous avez effectué un paiement de ' .
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

                                                    Http::get(
                                                        "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                                    );
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
                                                Notification::send($admins, new AdminNotification('Demande de virement de ' .
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
                                    }
                                }
                            }
                        }
                    } elseif (sabdy()->wallet->currencie->code == 'CFA') {

                        // Verify valeur amount max in Landing the ROBUS
                        $valeur_end = $percent_robus->levelBanks->last();

                        //  Conversion valeur
                        $conversion_valeur_end  = $valeur_end->amount_end;

                        if ($conversion_valeur_end  < $this->amount_virement_inter) {
                            $this->resetInterField();
                            $this->emit('modal-hide-inters');
                            $this->emit('info', 'Vous avez depasser le montant max !');
                        } else {

                            // Verifier plafond days
                            foreach ($percent_robus->levelBanks as $amount_user) {

                                if ($this->amount_virement_inter >= $amount_user->amount_begin && $this->amount_virement_inter <= $amount_user->amount_end) {


                                    if ($som_transfers >= $valeur_ceiling) {
                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit(
                                            'warning',
                                            'Votre montant journier est atteint !'
                                        );
                                    } else {

                                        if (sabdy()->is_under_distributer == 1) {

                                            $val_amount = $this->amount_virement_inter;
                                        } else {

                                            $val_amount = $this->amount_virement_inter + ($amount_user->percent * $this->amount_virement_inter);
                                        }

                                        if ($val_amount > sabdy()->wallet->amount) {

                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit(
                                                'error',
                                                'Verifier votre montant  svp ou le pays d\'envoie !'
                                            );
                                        } else {

                                            $amount_solde =  sabdy()->wallet->amount -  $val_amount;

                                            $walletInter = WalletInter::create([
                                                'amount_solde'  =>   $amount_solde ?? null,
                                                'wallet_id' => sabdy()->wallet->id,
                                                'amount' =>  $this->amount_virement_inter,
                                                'inter_cost_id' => null,
                                                'bank_cost_id' => 1,
                                                'phone' => $country_robus->indecative . $this->phone_user_inter,
                                                'number_compte' => null,
                                                'created_by' => sabdy()->id,
                                                'provider_id' =>  $provider_virement->id,
                                                'status' => 'PENDING',
                                                'country_id' =>  $country_robus->id,
                                                'number_decoder' => null,
                                                'id_invoice' => null,
                                                'number_contract' => null,
                                                'phone_invoice' =>  null,
                                                'id_invoice_pre' =>  null,
                                                'name' =>  null,
                                                'provider_type' => 7,
                                                'number_phone' =>  null,
                                                'firstname' =>  $this->firstname_user_inter ??  null,
                                                'lastname' => $this->lastname_user_inter ?? null,
                                                'offer_id' => null,
                                                'costs_amount' =>  $amount_user->percent * $this->amount_virement_inter,
                                                'paiementChoix_phone' =>  null,
                                                'paiementChoix_type' => null,
                                                'factureChoix_type' => null,
                                            ]);

                                            // Commissions Inters DIstributer Provider ECOBANK and NSIA
                                            if (sabdy()->is_under_distributer == 1) {


                                                $commission_acompte = $this->amount_virement_inter;

                                                sabdy()->wallet->update([
                                                    'reload_by' => sabdy()->firstname .
                                                        ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount-' . $commission_acompte),
                                                ]);


                                                // Convertion valuer in Devise Auth
                                                $commission = $amount_user->percent * $this->amount_virement_inter;

                                                // Distributer valeur commission
                                                $val_amount_distr = $amount_user->comission_distributer * $commission;

                                                $valeur_received = $walletInter->amount -  ($amount_user->percent * $walletInter->amount);

                                                $walletInter->update([
                                                    'amount_received'  =>  $valeur_received,
                                                ]);
                                                // send comission valeur admin 

                                                if ($admin_civ->count() > 0) {

                                                    $nmbre_admin = $admin_civ->count();

                                                    $valeur_single = $val_amount_distr / $nmbre_admin;

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
                                                            'description' => 'Paiement commission sous distributeur',
                                                        ]);
                                                        $admin_single->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $valeur_single),
                                                        ]);
                                                    }
                                                }

                                                $walletInter->update([
                                                    'commissions' => DB::raw('commissions+'  . $val_amount_distr),
                                                ]);

                                                $walletInter->update([
                                                    'is_send' => 1,
                                                ]);

                                                if (sabdy()->country->code == 'CI') {
                                                    $message_invoice =
                                                        'Votre code de la facture prepéyé est ' .
                                                        $walletInter->id_invoice_pre;

                                                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$walletInter->phone_invoice&from=ROBUS&sms=$message_invoice");

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

                                                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                                                }

                                                $msg_title = 'Transfert internationnal';
                                                $msg = 'Demande de paiement est en cours de validation ';
                                                sabdy()->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                                                $notifs =  sabdy()->unreadNotifications;
                                                event(new CreateWalletInterEvent($walletInter));
                                                $this->emit('transaction_inter_sends', $walletInter->id);
                                                $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                                Notification::send($admins, new AdminNotification('Demande de virement de ' .
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
                                            } else {

                                                // Convertion valuer in Devise Auth
                                                $commission = $amount_user->percent * $this->amount_virement_inter;

                                                $valeur_received = $walletInter->amount -  ($amount_user->percent * $walletInter->amount);

                                                $walletInter->update([
                                                    'amount_received'  =>  $valeur_received  *  sabdy()->country->currencie->value,
                                                ]);

                                                // send comission valeur admin 

                                                if ($admin_civ->count() > 0) {

                                                    $nmbre_admin = $admin_civ->count();

                                                    $valeur_single = $commission / $nmbre_admin;

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

                                                $walletInter->update([
                                                    'is_send' => 1,
                                                ]);

                                                if (sabdy()->country->code == 'CI') {
                                                    $message_invoice =
                                                        'Votre code de la facture prepéyé est ' .
                                                        $walletInter->id_invoice_pre;

                                                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$walletInter->phone_invoice&from=ROBUS&sms=$message_invoice");

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

                                                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                                                }

                                                $msg_title = 'Transfert internationnal';
                                                $msg = 'Demande de paiement est en cours de validation ';
                                                sabdy()->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                                                $notifs =  sabdy()->unreadNotifications;
                                                event(new CreateWalletInterEvent($walletInter));
                                                $this->emit('transaction_inter_sends', $walletInter->id);
                                                $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                                Notification::send($admins, new AdminNotification('Demande de virement de ' .
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
                                    }
                                }
                            }
                        }
                    }
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


        $default_valeur = 2000000;
        $commission_virement_val_inter_receive = null;
        $commission_virement_val_inter_pay = null;
        $commission_phone_credit_val_inter_admin_pay = null;
        $commission_virement_val_inter_convert_pay = null;
        $commission_phone_credit_val_inter_convert_pay = null;

        $amount_received_canal = null;
        $country_civ = Country::whereId(107)
            // ->whereStatus(true)
            ->first();
        $inter_search = WalletInter::query();


        if ($this->amount_virement_inter != '') {

            $commission_inter = BankCost::whereId(1)->first();

            if (sabdy()->wallet->currencie->value >  $country_civ->currencie->value) {

                // Convertion on CFA
                $valeur_amount =  $this->amount_virement_inter *  sabdy()->wallet->currencie->value;

                // Verify valeur amount max in Landing the ROBUS
                $valeur_end = $commission_inter->levelBanks->last();

                //  Conversion valeur
                $conversion_valeur_end  = $valeur_end->amount_end * sabdy()->wallet->currencie->value;

                if ($conversion_valeur_end  < $this->amount_virement_inter) {

                    $commission_virement_val_inter_pay = "Montant maxi est  : " .  $conversion_valeur_end;

                    $commission_virement_val_inter_receive = "Montant maxi est  : " .  $conversion_valeur_end;
                } else {

                    foreach ($commission_inter->levelBanks as $amount_comission) {

                        if ($valeur_amount >= $amount_comission->amount_begin &&  $valeur_amount <= $amount_comission->amount_end) {
                            // add commission of Amount
                            if ($amount_comission->percent != null) {

                                $commission_phone_credit_val_inter_admin_pay = $amount_comission->percent * $valeur_amount;


                                if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                                    $commission_phone_credit_val_inter_convert_pay  =  $commission_phone_credit_val_inter_admin_pay / sabdy()->wallet->currencie->value;

                                    $amount_received_canal = (float) $amount_comission->comission_distributer * (float) $commission_phone_credit_val_inter_convert_pay;
                                }

                                $commission_virement_val_inter_convert_pay = $amount_comission->percent * $valeur_amount;

                                $commission_virement_val_inter_pay = $commission_virement_val_inter_convert_pay / sabdy()->wallet->currencie->value;

                                $valeur_receive = $valeur_amount;

                                if ($valeur_receive < 0) {

                                    $commission_virement_val_inter_receive = "Montant negatif";
                                } else {

                                    $commission_virement_val_inter_receive =  $valeur_receive;
                                }
                            } else {

                                $commission_phone_credit_val_inter_admin_pay = $amount_comission->amount;

                                if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                                    $commission_phone_credit_val_inter_convert_pay  =  $commission_phone_credit_val_inter_admin_pay / sabdy()->wallet->currencie->value;

                                    $amount_received_canal = (float) $amount_comission->comission_distributer * (float) $commission_phone_credit_val_inter_convert_pay;
                                }

                                $commission_virement_val_inter_pay = $amount_comission->amount;


                                $valeur_receive = $valeur_amount;

                                if ($valeur_receive < 0) {

                                    $commission_virement_val_inter_receive = "Montant negatif";
                                } else {

                                    $commission_virement_val_inter_receive =  $valeur_receive;
                                }
                            }
                        }
                    }
                }
            } elseif (sabdy()->wallet->currencie->value <  $country_civ->currencie->value) {

                $valeur_amount = $this->amount_virement_inter * sabdy()->wallet->currencie->value;

                // Verify valeur amount max in Landing the ROBUS
                $valeur_end = $commission_inter->levelBanks->last();

                //  Conversion valeur
                $conversion_valeur_end  = $valeur_end->amount_end * sabdy()->wallet->currencie->value;

                if ($conversion_valeur_end  < $this->amount_virement_inter) {
                    $commission_virement_val_inter_pay = "Montant maxi est  : " .  $conversion_valeur_end;

                    $commission_virement_val_inter_receive = "Montant maxi est  : " .  $conversion_valeur_end;
                } else {

                    foreach ($commission_inter->levelBanks as $amount_comission) {

                        if ($valeur_amount >= $amount_comission->amount_begin && $valeur_amount <= $amount_comission->amount_end) {

                            if ($amount_comission->percent != null) {

                                $commission_phone_credit_val_inter_admin_pay = $amount_comission->percent * $valeur_amount;


                                if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                                    $commission_phone_credit_val_inter_convert_pay  =  $commission_phone_credit_val_inter_admin_pay / sabdy()->wallet->currencie->value;

                                    $amount_received_canal = (float) $amount_comission->comission_distributer * (float) $commission_phone_credit_val_inter_convert_pay;
                                }

                                $commission_virement_val_inter_convert_pay = $amount_comission->percent * $valeur_amount;

                                $commission_virement_val_inter_pay = $commission_virement_val_inter_convert_pay / sabdy()->wallet->currencie->value;

                                $valeur_receive = $this->amount_virement_inter;

                                if ($valeur_receive < 0) {

                                    $commission_virement_val_inter_receive = "Montant negatif";
                                } else {

                                    $commission_virement_val_inter_receive =  $valeur_receive;
                                }
                            } else {

                                $commission_phone_credit_val_inter_admin_pay = $amount_comission->amount;

                                if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                                    $commission_phone_credit_val_inter_convert_pay  =  $commission_phone_credit_val_inter_admin_pay / sabdy()->wallet->currencie->value;

                                    $amount_received_canal = (float) $amount_comission->comission_distributer * (float) $commission_phone_credit_val_inter_convert_pay;
                                }

                                $commission_virement_val_inter_pay = $amount_comission->amount;


                                $valeur_receive = $this->amount_virement_inter;

                                if ($valeur_receive < 0) {

                                    $commission_virement_val_inter_receive = "Montant negatif";
                                } else {

                                    $commission_virement_val_inter_receive =  $valeur_receive;
                                }
                            }
                        }
                    }
                }
            } elseif (sabdy()->wallet->currencie->code == 'CFA') {

                $valeur_amount = $this->amount_virement_inter;

                // Verify valeur amount max in Landing the ROBUS
                $valeur_end = $commission_inter->levelBanks->last();

                //  Conversion valeur
                $conversion_valeur_end  = $valeur_end->amount_end;

                if ($conversion_valeur_end  < $valeur_amount) {
                    $commission_virement_val_inter_pay = "Montant maxi est  : " .  $conversion_valeur_end;

                    $commission_virement_val_inter_receive = "Montant maxi est  : " .  $conversion_valeur_end;
                } else {

                    foreach ($commission_inter->levelBanks  as $amount_comission) {

                        if ($valeur_amount >= $amount_comission->amount_begin && $valeur_amount <= $amount_comission->amount_end) {

                            if ($amount_comission->percent != null) {

                                $commission_phone_credit_val_inter_admin_pay = $amount_comission->percent * $valeur_amount;

                                if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                                    $amount_received_canal = (float) $amount_comission->comission_distributer * (float) $commission_phone_credit_val_inter_admin_pay;
                                }

                                $commission_virement_val_inter_pay = $amount_comission->percent * $valeur_amount;


                                $valeur_receive = $this->amount_virement_inter;

                                if ($valeur_receive < 0) {

                                    $commission_virement_val_inter_receive = "Montant negatif";
                                } else {

                                    $commission_virement_val_inter_receive =  $valeur_receive;
                                }
                            } else {

                                $commission_phone_credit_val_inter_admin_pay = $amount_comission->amount;

                                if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                                    $amount_received_canal = (float) $amount_comission->comission_distributer * (float) $commission_phone_credit_val_inter_admin_pay;
                                }

                                $commission_virement_val_inter_pay = $amount_comission->amount;


                                $valeur_receive = $this->amount_virement_inter;

                                if ($valeur_receive < 0) {

                                    $commission_virement_val_inter_receive = "Montant negatif";
                                } else {

                                    $commission_virement_val_inter_receive =  $valeur_receive;
                                }
                            }
                        }
                    }
                }
            }
        }


        return view('livewire.transaction-payments.virement-transaction', [
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
            'countries' => Country::whereRobus(true)
                ->orderBy('name', 'asc')
                ->get(),
            'countrie_inter_cf' => Country::whereRobus(true)->whereId(107)
                ->first(),
            'amount_virement_inter_val_pay' => $commission_virement_val_inter_pay,
            'amount_virement_inter_val_receive' => $commission_virement_val_inter_receive,
            'amount_received_credit' => $amount_received_canal,
            'provider_inter_virements' => $this->VirmentTransactionInter,
            'usersCountry' => sabdy()->wallet->currencie->code,
        ]);
    }

    public function getVirmentTransactionInterProperty()
    {

        $phone_transaction = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', sabdy()->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 10)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();

        return $phone_transaction;
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
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
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
            as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletDepositsCeilingProperty()
    {
        $data = [];

        foreach (WalletDeposit::join(
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
            as $deposit) {
            $data[] = $deposit->amount;
        }

        return array_sum($data);
    }

    public function getWalletIntersCeilingProperty()
    {
        $data = [];

        foreach (WalletInter::join(
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
            as $inter) {
            $data[] = $inter->amount;
        }

        return array_sum($data);
    }

    public function getWalletPaymentsCeilingProperty()
    {
        $data = [];

        foreach (WalletPayment::join(
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
            as $payment) {
            $data[] = $payment->amount;
        }

        return array_sum($data);
    }

    public function getWalletTransactionsCeilingProperty()
    {
        $data = [];

        foreach (WalletTransaction::join(
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
            as $transaction) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }

    public function getWalletPhoneCreditsCeilingProperty()
    {
        $data = [];

        foreach (WalletPhoneCredit::join(
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
            as $phone) {
            $data[] = $phone->amount;
        }

        return array_sum($data);
    }

    public function getWalletBankCardCeilingProperty()
    {
        $data = [];
        foreach (BankCard::where('created_by', sabdy()->id)
            ->whereDate('created_at', Carbon::today())->whereStatus(1)->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletReloadsCeilingProperty()
    {
        $data = [];

        foreach (WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
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
            as $reload) {
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
