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

class ReloadTransaction extends Component
{
    public $amount_reloadcard_inter;
    public $account_number_reload_card_inter;
    public $name_reloadcard_inter;
    public $country_inter_robus;
    public $interId;
    public $provider_payment_inter;
    public $amount_virement_inter;
    public $provider_reloadcard_inter;


    public function resetValeurReload()
    {
        $this->amount_reloadcard_inter = '';
        $this->account_number_reload_card_inter = '';
        $this->name_reloadcard_inter = '';
        $this->amount_virement_inter = '';
        $this->provider_reloadcard_inter = '';
        $this->country_inter_robus = null;
        $this->provider_payment_inter = null;
        // $this->emit('modal-hide-transfers-validated');
        // $this->emit('modal-hide-transfers');
    }

    public function resetInterField()
    {
        $this->amount_reloadcard_inter = '';
        $this->account_number_reload_card_inter = '';
        $this->name_reloadcard_inter = '';
        $this->amount_virement_inter = '';
        $this->provider_reloadcard_inter = '';
        $this->country_inter_robus = null;
        $this->provider_payment_inter = null;
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
        $this->amount_reloadcard_inter = '';
        $this->account_number_reload_card_inter = '';
        $this->name_reloadcard_inter = '';
        $this->amount_virement_inter = '';
        $this->provider_reloadcard_inter = '';
        $this->country_inter_robus = null;
        $this->provider_payment_inter = null;
        $this->emit('menu-inters-active');
        $this->emit('modal-hide-inters');
        $this->emit('transaction_inters');
    }

    public function addInterReloadCard()
    {
        $check = WalletInter::whereId($this->interId)->first();
        $country_robus = Country::whereId($this->country_inter_robus)
            // ->whereStatus(true)
            ->first();

        $country_civ = Country::whereId(107)
            // ->whereStatus(true)
            ->first();

        $provider_payment = Provider::whereId($this->provider_reloadcard_inter)->first();

        $admin_civ = $this->getAdminCIVProperty();
        $som_transfers =  $this->getSumValTransactionProperty();

        // Valuer amount cota
        $valeur_ceiling = sabdy()->silver_ceiling;

        try {

            if (!$check) {

                // Providers BSIc and ECOBANK
                if ($this->provider_reloadcard_inter == 64 || $this->provider_reloadcard_inter == 65 || $this->provider_reloadcard_inter == 67 || $this->provider_reloadcard_inter == 69) {

                    $this->validate(
                        [
                            'amount_reloadcard_inter' => 'required',
                            'account_number_reload_card_inter' => 'required',
                            'name_reloadcard_inter' => 'required',
                        ],
                        [
                            'amount_reloadcard_inter.required' =>
                            'Votre montant est obligatoire',
                            'name_reloadcard_inter.required' =>
                            'Nom  est obligatoire',
                            'account_number_reload_card_inter.required' =>
                            'Numero est obligatoire',
                        ]
                    );

                    $percent_robus = DepositCost::whereId(1)->first();

                    if (sabdy()->wallet->currencie->value >  $country_civ->currencie->value) {

                        //Convertir valeur en CFA and verify valeur 
                        $valeur_amount = $this->amount_reloadcard_inter *  sabdy()->wallet->currencie->value;

                        if ($valeur_amount < 2000) {
                            $this->resetInterField();
                            $this->emit('modal-hide-inters');
                            $this->emit('error', 'Verifier le montant minimum est de 2000 !');
                        } else {

                            // Verify valeur amount max in Landing the ROBUS
                            $valeur_end = $percent_robus->levelDeposits->where('is_inter_bank', true)->where('is_ivoires', false)->last();

                            //  First amount

                            //  Conversion valeur
                            $conversion_valeur_end  = $valeur_end->amount_end /  sabdy()->wallet->currencie->value;

                            if ($conversion_valeur_end  < $this->amount_reloadcard_inter) {
                                $this->resetInterField();
                                $this->emit('modal-hide-inters');
                                $this->emit('info', 'Vous avez depasser le montant max !');
                            } else {

                                foreach ($percent_robus->levelDeposits->where('is_inter_bank', true)->where('is_ivoires', false)  as $amount_user) {

                                    if ($valeur_amount >= $amount_user->amount_begin && $valeur_amount <= $amount_user->amount_end) {

                                        if ($som_transfers >= $valeur_ceiling) {
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit(
                                                'warning',
                                                'Votre montant journier est atteint !'
                                            );
                                        } else {

                                            // If commission is percent 
                                            if ($amount_user->amount == null) {

                                                if (sabdy()->is_under_distributer == 1) {
                                                    // Percent valeur of commission en CFA
                                                    $val_amount =  $valeur_amount;
                                                } else {

                                                    // Percent valeur of commission en CFA
                                                    $val_amount =  $valeur_amount +  $amount_user->percent * $valeur_amount;
                                                }

                                                // Convertion valeur in Devise Custumers AUTH
                                                $valeur_convertion  =  $val_amount  / sabdy()->wallet->currencie->value;


                                                if ($valeur_convertion >  sabdy()->wallet->amount) {
                                                    $this->resetInterField();
                                                    $this->emit('modal-hide-inters');
                                                    $this->emit('error', 'Verifier votre montant  svp ou votre pays !');
                                                } else {


                                                    $amount_solde =  sabdy()->wallet->amount -  $valeur_convertion;

                                                    $walletInter = WalletInter::create(
                                                        [

                                                            'amount_solde'  => $amount_solde ?? null,
                                                            'wallet_id' => sabdy()->wallet->id,
                                                            'amount' => $this->amount_reloadcard_inter ?? null,
                                                            'inter_cost_id' => null,
                                                            'deposit_cost_id' => 1,
                                                            'phone' =>  null,
                                                            'number_compte' =>  $this->account_number_reload_card_inter ?? null,
                                                            'created_by' => sabdy()->id,
                                                            'provider_id' =>  $provider_payment->id,
                                                            'status' => 'PENDING',
                                                            'country_id' => $country_robus->id,
                                                            'number_decoder' => null,
                                                            'id_invoice' =>  null,
                                                            'number_contract' =>  null,
                                                            'phone_invoice' =>  null,
                                                            'id_invoice_pre' =>   null,
                                                            'name' => $this->name_reloadcard_inter ?? null,
                                                            'provider_type' => 8,
                                                            'number_phone' =>  null,
                                                            'firstname' =>  null,
                                                            'lastname' =>  null,
                                                            'offer_id' =>  null,
                                                            'costs_amount' =>  $amount_user->percent * $valeur_amount,
                                                            'status' => 'PENDING',
                                                            'paiementChoix_phone' =>  null,
                                                            'paiementChoix_type' =>  null,
                                                            'factureChoix_type' => null,

                                                        ]
                                                    );



                                                    // Commissions Inters DIstributer Provider ECOBANK and NSIA
                                                    if (sabdy()->is_under_distributer == 1) {

                                                        // Valeur amount and commission User Auth
                                                        $commission = $valeur_convertion;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);


                                                        $admin_percent = $amount_user->percent * $valeur_amount;

                                                        // Devise convertion distributer and S-distributer in Auth devise
                                                        $commission_distributer =  $admin_percent * $amount_user->comission_distributer;

                                                        $admin_commission =  $admin_percent - $commission_distributer;

                                                        //  Convertion devise en Auth user
                                                        $convertion_commission_distributer =   $commission_distributer  /  sabdy()->wallet->currencie->value;

                                                        $valeur_received = $walletInter->amount -  ($amount_user->percent * $walletInter->amount);

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received  /  sabdy()->country->currencie->value,
                                                        ]);


                                                        $walletInter->update([
                                                            'commissions' => DB::raw('commissions+'  . $convertion_commission_distributer),
                                                        ]);
                                                        // send comission valeur admin 

                                                        if ($admin_civ->count() > 0) {

                                                            $nmbre_admin = $admin_civ->count();


                                                            $valeur_single = $admin_commission / $nmbre_admin;

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
                                                                    'description' => 'Paiement commisson sous distributeur',
                                                                ]);
                                                                $admin_single->wallet->update([
                                                                    'reload_by' => sabdy()->firstname .
                                                                        ' ' . sabdy()->lastname,
                                                                    'sid_user_relod' => sabdy()->id,
                                                                    'amount' => DB::raw('amount-' . $valeur_single),
                                                                ]);
                                                            }
                                                        }


                                                        $msg_title = 'Transfert internationnal';
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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

                                                        $commission = $valeur_convertion;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);


                                                        //  Convertion devise en Auth user
                                                        $convertion_commission_distributer =   ($amount_user->percent * $valeur_amount)  / sabdy()->wallet->currencie->value;


                                                        $valeur_received = $walletInter->amount -  ($amount_user->percent * $walletInter->amount);

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received  *  sabdy()->country->currencie->value,
                                                        ]);

                                                        // send comission valeur admin 

                                                        if ($admin_civ->count() > 0) {

                                                            $nmbre_admin = $admin_civ->count();

                                                            $valeur_single = $convertion_commission_distributer / $nmbre_admin;

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
                                                            $message = 'Vous avez effectué un rechargement de ' .
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
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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
                                            } else {

                                                if (sabdy()->is_under_distributer == 1) {
                                                    // Percent valeur of commission en CFA
                                                    $val_amount = $valeur_amount;
                                                } else {

                                                    // Percent valeur of commission en CFA
                                                    $val_amount = $valeur_amount + $amount_user->amount;
                                                }

                                                // Convertion valeur in Devise Custumers AUTH
                                                $valeur_convertion  =  $val_amount  / sabdy()->wallet->currencie->value;

                                                if ($valeur_convertion > sabdy()->wallet->amount) {
                                                    $this->resetInterField();
                                                    $this->emit('modal-hide-inters');
                                                    $this->emit('error', 'Verifier votre montant  svp ou votre pays !');
                                                } else {

                                                    $amount_solde =  sabdy()->wallet->amount -  $valeur_convertion;


                                                    $walletInter = WalletInter::create(
                                                        [

                                                            'amount_solde'  => $amount_solde ?? null,
                                                            'wallet_id' => sabdy()->wallet->id,
                                                            'amount' => $this->amount_reloadcard_inter ?? null,
                                                            'inter_cost_id' => null,
                                                            'deposit_cost_id' => 1,
                                                            'phone' =>  null,
                                                            'number_compte' =>  $this->account_number_reload_card_inter ?? null,
                                                            'created_by' => sabdy()->id,
                                                            'provider_id' =>  $provider_payment->id,
                                                            'status' => 'PENDING',
                                                            'country_id' => $country_robus->id,
                                                            'number_decoder' => null,
                                                            'id_invoice' =>  null,
                                                            'number_contract' =>  null,
                                                            'phone_invoice' =>  null,
                                                            'id_invoice_pre' =>   null,
                                                            'name' => $this->name_reloadcard_inter ?? null,
                                                            'provider_type' => 8,
                                                            'number_phone' =>  null,
                                                            'firstname' =>  null,
                                                            'lastname' =>  null,
                                                            'offer_id' =>  null,
                                                            'costs_amount' =>  $amount_user->percent * $valeur_amount,
                                                            'status' => 'PENDING',
                                                            'paiementChoix_phone' =>  null,
                                                            'paiementChoix_type' =>  null,
                                                            'factureChoix_type' => null,

                                                        ]
                                                    );


                                                    // Commissions Inters DIstributer Provider ECOBANK and NSIA
                                                    if (sabdy()->is_under_distributer == 1) {


                                                        // Valeur amount and commission User Auth
                                                        $commission = $valeur_convertion;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);

                                                        // Devise convertion distributer and S-distributer in Auth devise
                                                        $commission_distributer = $amount_user->comission_distributer;

                                                        $admin_commission =   $amount_user->amount - $commission_distributer;

                                                        //  Convertion devise en Auth user
                                                        $convertion_commission_distributer =  $commission_distributer  /  sabdy()->wallet->currencie->value;

                                                        $valeur_received = $walletInter->amount -  $amount_user->amount;

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received  /  sabdy()->country->currencie->value,
                                                        ]);


                                                        $walletInter->update([
                                                            'commissions' => DB::raw('commissions+'  . $convertion_commission_distributer),
                                                        ]);
                                                        // send comission valeur admin 

                                                        if ($admin_civ->count() > 0) {

                                                            $nmbre_admin = $admin_civ->count();

                                                            $valeur_single = $admin_commission / $nmbre_admin;

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


                                                        $msg_title = 'Transfert internationnal';
                                                        $msg = 'Demande de rechargement est en cours de validation ';
                                                        sabdy()->notify(
                                                            new UserNotification([
                                                                'title' => $msg_title,
                                                                'message' => $msg,
                                                            ])
                                                        );
                                                        $notifs = sabdy()->unreadNotifications;
                                                        event(new CreateWalletInterEvent($walletInter));
                                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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



                                                        // Valeur amount and commission User Auth
                                                        $commission_send = $valeur_amount + $amount_user->amount;

                                                        $convertion_commission_send =  $commission_send  /  sabdy()->wallet->currencie->value;


                                                        $valeur_received = $walletInter->amount -  $amount_user->amount;

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received  /  sabdy()->country->currencie->value,
                                                        ]);

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $convertion_commission_send),
                                                        ]);

                                                        $valeur_deposit_conver = $amount_user->amount;

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
                                                            $message = 'Vous avez effectué un rechargement de ' .
                                                                $walletInter->amount .
                                                                sabdy()->wallet->currencie->code .
                                                                ' ' .
                                                                'par ' .
                                                                $walletInter->provider->name .
                                                                ' a ' .
                                                                $walletInter->created_at .
                                                                ' votre demande est en-cours de validation ' .
                                                                'Robus vous remercie';

                                                            $encode = substr(
                                                                Auth::guard(
                                                                    'web'
                                                                )->user()->phone,
                                                                -10
                                                            );

                                                            Http::get(
                                                                "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                                            );
                                                        }

                                                        $msg_title = 'Transfert internationnal';
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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
                    } elseif (sabdy()->wallet->currencie->value <  $country_civ->currencie->value) {

                        // Montant Update cfa
                        $valeur_amount =  $this->amount_reloadcard_inter /  sabdy()->wallet->currencie->value;

                        if ($valeur_amount < 2000) {
                            $this->resetInterField();
                            $this->emit('modal-hide-inters');
                            $this->emit(
                                'error',
                                'Verifier le montant minimum est de 2000 !'
                            );
                        } else {

                            // Verify valeur amount max in Landing the ROBUS
                            $valeur_end = $percent_robus->levelDeposits->where('is_inter_bank', true)->where('is_ivoires', false)->last();

                            //  Conversion valeur
                            $conversion_valeur_end  = $valeur_end->amount_end * sabdy()->wallet->currencie->value;

                            if ($conversion_valeur_end  < $this->amount_reloadcard_inter) {
                                $this->resetInterField();
                                $this->emit('modal-hide-inters');
                                $this->emit('info', 'Vous avez depasser le montant max !');
                            } else {

                                foreach ($percent_robus->levelDeposits->where('is_inter_bank', true)->where('is_ivoires', false) as $amount_user) {

                                    if ($valeur_amount >=  $amount_user->amount_begin && $valeur_amount <= $amount_user->amount_end) {


                                        if ($som_transfers >= $valeur_ceiling) {
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit(
                                                'warning',
                                                'Votre montant journier est atteint !'
                                            );
                                        } else {


                                            if ($amount_user->amount == null) {

                                                if (sabdy()->is_under_distributer == 1) {

                                                    // Percent valeur of commission en CFA
                                                    $val_amount =  $valeur_amount;
                                                } else {

                                                    // Percent valeur of commission en CFA
                                                    $val_amount =  $valeur_amount +  $amount_user->percent * $valeur_amount;
                                                }

                                                // Convertion valeur in Devise Custumers AUTH
                                                $valeur_convertion  =  $val_amount  / sabdy()->wallet->currencie->value;


                                                if ($valeur_convertion >  sabdy()->wallet->amount) {
                                                    $this->resetInterField();
                                                    $this->emit('modal-hide-inters');
                                                    $this->emit('error', 'Verifier votre montant  svp ou votre pays !');
                                                } else {

                                                    $amount_solde =  sabdy()->wallet->amount -  $valeur_convertion;


                                                    $walletInter = WalletInter::create(
                                                        [

                                                            'amount_solde'  => $amount_solde ?? null,
                                                            'wallet_id' => sabdy()->wallet->id,
                                                            'amount' => $this->amount_reloadcard_inter ?? null,
                                                            'inter_cost_id' => null,
                                                            'deposit_cost_id' => 1,
                                                            'phone' =>  null,
                                                            'number_compte' =>  $this->account_number_reload_card_inter ?? null,
                                                            'created_by' => sabdy()->id,
                                                            'provider_id' =>  $provider_payment->id,
                                                            'status' => 'PENDING',
                                                            'country_id' => $country_robus->id,
                                                            'number_decoder' => null,
                                                            'id_invoice' =>  null,
                                                            'number_contract' =>  null,
                                                            'phone_invoice' =>  null,
                                                            'id_invoice_pre' =>   null,
                                                            'name' => $this->name_reloadcard_inter ?? null,
                                                            'provider_type' => 8,
                                                            'number_phone' =>  null,
                                                            'firstname' =>  null,
                                                            'lastname' =>  null,
                                                            'offer_id' =>  null,
                                                            'costs_amount' =>  $amount_user->percent * $valeur_amount,
                                                            'status' => 'PENDING',
                                                            'paiementChoix_phone' =>  null,
                                                            'paiementChoix_type' =>  null,
                                                            'factureChoix_type' => null,

                                                        ]
                                                    );


                                                    // Commissions Inters DIstributer Provider ECOBANK and NSIA
                                                    if (sabdy()->is_under_distributer == 1) {

                                                        // Valeur amount and commission User Auth
                                                        $commission = $valeur_convertion;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);


                                                        // Admin Commission valeur commission
                                                        $convertion_valeur_com_admin =  $amount_user->percent * $valeur_amount;

                                                        // Devise convertion distributer and S-distributer in Auth devise
                                                        $commission_distributer = $convertion_valeur_com_admin * $amount_user->comission_distributer;

                                                        $admin_commission = $convertion_valeur_com_admin -  $commission_distributer;
                                                        // Convertion valeur Distributeur

                                                        $valeur_conve_distri =  $commission_distributer / sabdy()->wallet->currencie->value;

                                                        $valeur_received = $walletInter->amount -  ($amount_user->percent * $walletInter->amount);

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received  * sabdy()->country->currencie->value,
                                                        ]);

                                                        // Commission distributer              
                                                        $walletInter->update([
                                                            'commissions' => DB::raw('commissions+'  . $valeur_conve_distri)
                                                        ]);
                                                        // send comission valeur admin 

                                                        if ($admin_civ->count() > 0) {

                                                            $nmbre_admin = $admin_civ->count();

                                                            $valeur_single = $admin_commission / $nmbre_admin;

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

                                                        $msg_title = 'Transfert internationnal';
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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

                                                        // Valeur amount and commission User Auth
                                                        $commission = $valeur_convertion;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);

                                                        // Admin Commission valeur commission
                                                        $convertion_valeur_com_admin =  $amount_user->percent * $valeur_amount;


                                                        $valeur_received = $walletInter->amount -  ($amount_user->percent * $walletInter->amount);

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received  * sabdy()->country->currencie->value,
                                                        ]);

                                                        // send comission valeur admin 

                                                        if ($admin_civ->count() > 0) {

                                                            $nmbre_admin = $admin_civ->count();

                                                            $valeur_single = $convertion_valeur_com_admin / $nmbre_admin;

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

                                                            $encode = substr(
                                                                Auth::guard(
                                                                    'web'
                                                                )->user()->phone,
                                                                -10
                                                            );

                                                            Http::get(
                                                                "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                                            );
                                                        }

                                                        $msg_title = 'Transfert internationnal';
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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
                                            } else {

                                                if (sabdy()->is_under_distributer == 1) {

                                                    // Percent valeur of commission en CFA
                                                    $val_amount =  $valeur_amount;
                                                } else {

                                                    // Percent valeur of commission en CFA
                                                    $val_amount =  $valeur_amount +  $amount_user->amount;
                                                }

                                                // Convertion valeur in Devise Custumers AUTH
                                                $valeur_convertion  =  $val_amount  / sabdy()->wallet->currencie->value;

                                                if ($valeur_convertion >  sabdy()->wallet->amount) {
                                                    $this->resetInterField();
                                                    $this->emit('modal-hide-inters');
                                                    $this->emit('error', 'Verifier votre montant  svp ou votre pays !');
                                                } else {

                                                    $amount_solde =  sabdy()->wallet->amount -  $valeur_convertion;

                                                    $walletInter = WalletInter::create(
                                                        [

                                                            'amount_solde'  => $amount_solde ?? null,
                                                            'wallet_id' => sabdy()->wallet->id,
                                                            'amount' => $this->amount_reloadcard_inter ?? null,
                                                            'inter_cost_id' => null,
                                                            'deposit_cost_id' => 1,
                                                            'phone' =>  null,
                                                            'number_compte' =>  $this->account_number_reload_card_inter ?? null,
                                                            'created_by' => sabdy()->id,
                                                            'provider_id' =>  $provider_payment->id,
                                                            'status' => 'PENDING',
                                                            'country_id' => $country_robus->id,
                                                            'number_decoder' => null,
                                                            'id_invoice' =>  null,
                                                            'number_contract' =>  null,
                                                            'phone_invoice' =>  null,
                                                            'id_invoice_pre' =>   null,
                                                            'name' => $this->name_reloadcard_inter ?? null,
                                                            'provider_type' => 8,
                                                            'number_phone' =>  null,
                                                            'firstname' =>  null,
                                                            'lastname' =>  null,
                                                            'offer_id' =>  null,
                                                            'costs_amount' =>  $amount_user->percent * $valeur_amount,
                                                            'status' => 'PENDING',
                                                            'paiementChoix_phone' =>  null,
                                                            'paiementChoix_type' =>  null,
                                                            'factureChoix_type' => null,

                                                        ]
                                                    );

                                                    // Commissions Inters DIstributer Provider ECOBANK and NSIA
                                                    if (sabdy()->is_under_distributer == 1) {

                                                        // Valeur amount and commission User Auth
                                                        $commission = $valeur_convertion;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);


                                                        // Devise convertion distributer and S-distributer in Auth devise
                                                        $commission_distributer = $amount_user->comission_distributer;

                                                        $admin_commission = $amount_user->amount -   $commission_distributer;

                                                        //  Convertion devise en Auth user
                                                        $convertion_commission_distributer =  $commission_distributer  /  sabdy()->wallet->currencie->value;


                                                        $walletInter->update([
                                                            'commissions' => DB::raw('commissions+'  . $convertion_commission_distributer),
                                                        ]);

                                                        $valeur_received = $walletInter->amount -  $amount_user->amount;

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received * sabdy()->country->currencie->value,
                                                        ]);

                                                        // send comission valeur under distributer 

                                                        if ($admin_civ->count() > 0) {

                                                            $nmbre_admin = $admin_civ->count();

                                                            $valeur_single = $admin_commission / $nmbre_admin;

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
                                                                    'description' => 'Paiement Commission sous distributeur',
                                                                ]);
                                                                $admin_single->wallet->update([
                                                                    'reload_by' => sabdy()->firstname .
                                                                        ' ' . sabdy()->lastname,
                                                                    'sid_user_relod' => sabdy()->id,
                                                                    'amount' => DB::raw('amount-' . $valeur_single),
                                                                ]);
                                                            }
                                                        }


                                                        $msg_title = 'Transfert internationnal';
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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


                                                        $valeur_deposit_conver =  $amount_user->amount;

                                                        $commission = $valeur_convertion;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);

                                                        $valeur_received = $walletInter->amount -  $amount_user->amount;

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received * sabdy()->country->currencie->value,
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

                                                            $encode = substr(
                                                                Auth::guard(
                                                                    'web'
                                                                )->user()->phone,
                                                                -10
                                                            );

                                                            Http::get(
                                                                "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                                            );
                                                        }

                                                        $msg_title = 'Transfert internationnal';
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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
                    } elseif (sabdy()->wallet->currencie->code == 'CFA') {

                        if ($this->amount_reloadcard_inter < 2000) {
                            $this->resetInterField();
                            $this->emit('modal-hide-inters');
                            $this->emit('error', 'Verifier le montant minimum est de 2000 !');
                        } else {

                            // Verify valeur amount max in Landing the ROBUS
                            $valeur_end = $percent_robus->levelDeposits->where('is_inter_bank', true)->where('is_ivoires', false)->last();

                            //  Conversion valeur
                            $conversion_valeur_end  = $valeur_end->amount_end;

                            if ($conversion_valeur_end  < $this->amount_reloadcard_inter) {
                                $this->resetInterField();
                                $this->emit('modal-hide-inters');
                                $this->emit('info', 'Vous avez depasser le montant max !');
                            } else {

                                foreach ($percent_robus->levelDeposits->where('is_inter_bank', true)->where('is_ivoires', false)  as $amount_user) {

                                    if ($this->amount_reloadcard_inter >=  $amount_user->amount_begin &&  $this->amount_reloadcard_inter <= $amount_user->amount_end) {


                                        if ($som_transfers >= $valeur_ceiling) {
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit(
                                                'warning',
                                                'Votre montant journier est atteint !'
                                            );
                                        } else {

                                            if ($amount_user->amount == null) {

                                                if (sabdy()->is_under_distributer == 1) {

                                                    $val_amount =  $this->amount_reloadcard_inter;
                                                } else {

                                                    $val_amount =  $this->amount_reloadcard_inter +  $amount_user->percent *  $this->amount_reloadcard_inter;
                                                }

                                                if ($val_amount > sabdy()->wallet->amount) {
                                                    $this->resetInterField();
                                                    $this->emit('modal-hide-inters');
                                                    $this->emit('error', 'Verifier votre montant  svp ou votre pays !');
                                                } else {

                                                    $amount_solde =  sabdy()->wallet->amount -  $val_amount;

                                                    $walletInter = WalletInter::create(
                                                        [

                                                            'amount_solde'  => $amount_solde ?? null,
                                                            'wallet_id' => sabdy()->wallet->id,
                                                            'amount' => $this->amount_reloadcard_inter ?? null,
                                                            'inter_cost_id' => null,
                                                            'deposit_cost_id' => 1,
                                                            'phone' =>  null,
                                                            'number_compte' =>  $this->account_number_reload_card_inter ?? null,
                                                            'created_by' => sabdy()->id,
                                                            'provider_id' =>  $provider_payment->id,
                                                            'status' => 'PENDING',
                                                            'country_id' => $country_robus->id,
                                                            'number_decoder' => null,
                                                            'id_invoice' =>  null,
                                                            'number_contract' =>  null,
                                                            'phone_invoice' =>  null,
                                                            'id_invoice_pre' =>   null,
                                                            'name' => $this->name_reloadcard_inter ?? null,
                                                            'provider_type' => 8,
                                                            'number_phone' =>  null,
                                                            'firstname' =>  null,
                                                            'lastname' =>  null,
                                                            'offer_id' =>  null,
                                                            'costs_amount' =>  $amount_user->percent *  $this->amount_reloadcard_inter,
                                                            'status' => 'PENDING',
                                                            'paiementChoix_phone' =>  null,
                                                            'paiementChoix_type' =>  null,
                                                            'factureChoix_type' => null,

                                                        ]
                                                    );

                                                    // Commissions Inters DIstributer Provider ECOBANK and NSIA
                                                    if (sabdy()->is_under_distributer == 1) {

                                                        // Valeur amount and commission User Auth
                                                        $commission = $val_amount;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);
                                                        // Admin Commission valeur commission
                                                        $convertion_valeur_com_admin =  $amount_user->percent *  $walletInter->amount;

                                                        // Devise convertion distributer and S-distributer in Auth devise
                                                        $commission_distributer = $convertion_valeur_com_admin * $amount_user->comission_distributer;

                                                        $admin_commission = $convertion_valeur_com_admin -  $commission_distributer;

                                                        $walletInter->update([
                                                            'commissions' => DB::raw('commissions+'  . $commission_distributer),
                                                        ]);

                                                        $valeur_received = $walletInter->amount -  ($amount_user->percent * $walletInter->amount);

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received,
                                                        ]);
                                                        // send comission valeur under distributer

                                                        if ($admin_civ->count() > 0) {

                                                            $nmbre_admin = $admin_civ->count();

                                                            $valeur_single = $admin_commission / $nmbre_admin;

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
                                                                    'description' => 'Paiement Commission sous distributeur',
                                                                ]);
                                                                $admin_single->wallet->update([
                                                                    'reload_by' => sabdy()->firstname .
                                                                        ' ' . sabdy()->lastname,
                                                                    'sid_user_relod' => sabdy()->id,
                                                                    'amount' => DB::raw('amount-' . $valeur_single),
                                                                ]);
                                                            }
                                                        }


                                                        $msg_title = 'Transfert internationnal';
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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


                                                        // Valeur amount and commission User Auth
                                                        $commission = $val_amount;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);

                                                        // Admin Commission valeur commission
                                                        $convertion_valeur_com_admin =  ($amount_user->percent *  $walletInter->amount);

                                                        $valeur_received = $walletInter->amount -  ($amount_user->percent * $walletInter->amount);

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received,
                                                        ]);

                                                        // send comission valeur admin 
                                                        if ($admin_civ->count() > 0) {

                                                            $nmbre_admin = $admin_civ->count();

                                                            $valeur_single = $convertion_valeur_com_admin / $nmbre_admin;

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
                                                            $message = 'Vous avez effectué un rechargement de ' .
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
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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
                                            } else {

                                                if (sabdy()->is_under_distributer == 1) {

                                                    $val_amount =  $this->amount_reloadcard_inter;
                                                } else {


                                                    $val_amount =  $this->amount_reloadcard_inter +  $amount_user->amount;
                                                }

                                                if ($val_amount >  sabdy()->wallet->amount) {
                                                    $this->resetInterField();
                                                    $this->emit(
                                                        'modal-hide-inters'
                                                    );
                                                    $this->emit(
                                                        'error',
                                                        'Verifier votre montant  svp ou votre pays !'
                                                    );
                                                } else {

                                                    $amount_solde =  sabdy()->wallet->amount -  $val_amount;

                                                    $walletInter = WalletInter::create(
                                                        [

                                                            'amount_solde'  => $amount_solde ?? null,
                                                            'wallet_id' => sabdy()->wallet->id,
                                                            'amount' => $this->amount_reloadcard_inter ?? null,
                                                            'inter_cost_id' => null,
                                                            'deposit_cost_id' => 1,
                                                            'phone' =>  null,
                                                            'number_compte' =>  $this->account_number_reload_card_inter ?? null,
                                                            'created_by' => sabdy()->id,
                                                            'provider_id' =>  $provider_payment->id,
                                                            'status' => 'PENDING',
                                                            'country_id' => $country_robus->id,
                                                            'number_decoder' => null,
                                                            'id_invoice' =>  null,
                                                            'number_contract' =>  null,
                                                            'phone_invoice' =>  null,
                                                            'id_invoice_pre' =>   null,
                                                            'name' => $this->name_reloadcard_inter ?? null,
                                                            'provider_type' => 8,
                                                            'number_phone' =>  null,
                                                            'firstname' =>  null,
                                                            'lastname' =>  null,
                                                            'offer_id' =>  null,
                                                            'costs_amount' =>  $amount_user->percent *  $this->amount_reloadcard_inter,
                                                            'status' => 'PENDING',
                                                            'paiementChoix_phone' =>  null,
                                                            'paiementChoix_type' =>  null,
                                                            'factureChoix_type' => null,

                                                        ]
                                                    );

                                                    // Commissions Inters DIstributer Provider ECOBANK and NSIA
                                                    if (sabdy()->is_under_distributer == 1) {

                                                        // Valeur amount and commission User Auth
                                                        $commission = $val_amount;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);

                                                        // Devise convertion distributer and S-distributer in Auth devise
                                                        $commission_distributer = $amount_user->comission_distributer;

                                                        // Admin Commission valeur commission
                                                        $convertion_valeur_com_admin =  $amount_user->amount;

                                                        $valeur_received = $walletInter->amount -  $amount_user->amount;


                                                        $walletInter->update([
                                                            'commissions' => DB::raw('commissions+'  . $commission_distributer),
                                                        ]);

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received,
                                                        ]);
                                                        // send comission valeur under distributer 

                                                        if ($admin_civ->count() > 0) {

                                                            $nmbre_admin = $admin_civ->count();

                                                            $valeur_single = $convertion_valeur_com_admin / $nmbre_admin;

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
                                                                    'description' => 'Paiement Commission sous distributeur',
                                                                ]);
                                                                $admin_single->wallet->update([
                                                                    'reload_by' => sabdy()->firstname .
                                                                        ' ' . sabdy()->lastname,
                                                                    'sid_user_relod' => sabdy()->id,
                                                                    'amount' => DB::raw('amount-' . $valeur_single),
                                                                ]);
                                                            }
                                                        }


                                                        $msg_title = 'Transfert internationnal';
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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

                                                        // Valeur amount and commission User Auth
                                                        $commission = $val_amount;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);


                                                        $valeur_deposit_conver = $amount_user->amount;

                                                        $valeur_received = $walletInter->amount;

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received,
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
                                                            $message = 'Vous avez effectué un rechargement de ' .
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
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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
                    }
                } elseif ($this->provider_reloadcard_inter == 63 ||  $this->provider_reloadcard_inter == 62 || $this->provider_reloadcard_inter == 61) {

                    $this->validate(
                        [
                            'amount_reloadcard_inter' => 'required',
                            'account_number_reload_card_inter' => 'required',
                            'name_reloadcard_inter' => 'required',
                        ],
                        [
                            'amount_reloadcard_inter.required' =>
                            'Votre montant est obligatoire',
                            'name_reloadcard_inter.required' =>
                            'Nom  est obligatoire',
                            'account_number_reload_card_inter.required' =>
                            'Numero est obligatoire',
                        ]
                    );

                    $percent_robus = DepositCost::whereId(1)->first();

                    if (sabdy()->wallet->currencie->value >  $country_civ->currencie->value) {

                        //Convertir valeur en CFA and verify valeur 
                        $valeur_amount = $this->amount_reloadcard_inter *  sabdy()->wallet->currencie->value;

                        if ($valeur_amount < 2000) {
                            $this->resetInterField();
                            $this->emit('modal-hide-inters');
                            $this->emit(
                                'error',
                                'Verifier le montant minimum est de 2000 !'
                            );
                        } else {

                            // Verify valeur amount max in Landing the ROBUS
                            $valeur_end = $percent_robus->levelDeposits->where('is_inter_bank', false)->where('is_ivoires', false)->last();

                            //  Conversion valeur
                            $conversion_valeur_end  = $valeur_end->amount_end /  sabdy()->wallet->currencie->value;

                            if ($conversion_valeur_end  < $this->amount_reloadcard_inter) {
                                $this->resetInterField();
                                $this->emit('modal-hide-inters');
                                $this->emit('info', 'Vous avez depasser le montant max !');
                            } else {

                                foreach ($percent_robus->levelDeposits->where('is_inter_bank', false)->where('is_ivoires', false)  as $amount_user) {

                                    if ($valeur_amount >= $amount_user->amount_begin && $valeur_amount <= $amount_user->amount_end) {


                                        if ($som_transfers >= $valeur_ceiling) {
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit(
                                                'warning',
                                                'Votre montant journier est atteint !'
                                            );
                                        } else {


                                            // If commission is percent 
                                            if ($amount_user->amount == null) {

                                                if (sabdy()->is_under_distributer == 1) {

                                                    // Percent valeur of commission en CFA
                                                    $val_amount =  $valeur_amount;
                                                } else {

                                                    // Percent valeur of commission en CFA
                                                    $val_amount =  $valeur_amount +  $amount_user->percent * $valeur_amount;
                                                }


                                                // Convertion valeur in Devise Custumers AUTH
                                                $valeur_convertion  =  $val_amount  / sabdy()->wallet->currencie->value;


                                                if ($valeur_convertion >  sabdy()->wallet->amount) {
                                                    $this->resetInterField();
                                                    $this->emit('modal-hide-inters');
                                                    $this->emit('error', 'Verifier votre montant  svp ou votre pays !');
                                                } else {

                                                    $amount_solde =  sabdy()->wallet->amount -  $valeur_convertion;


                                                    $walletInter = WalletInter::create(
                                                        [

                                                            'amount_solde'  => $amount_solde ?? null,
                                                            'wallet_id' => sabdy()->wallet->id,
                                                            'amount' => $this->amount_reloadcard_inter ?? null,
                                                            'inter_cost_id' => null,
                                                            'deposit_cost_id' => 1,
                                                            'phone' =>  null,
                                                            'number_compte' =>  $this->account_number_reload_card_inter ?? null,
                                                            'created_by' => sabdy()->id,
                                                            'provider_id' =>  $provider_payment->id,
                                                            'status' => 'PENDING',
                                                            'country_id' => $country_robus->id,
                                                            'number_decoder' => null,
                                                            'id_invoice' =>  null,
                                                            'number_contract' =>  null,
                                                            'phone_invoice' =>  null,
                                                            'id_invoice_pre' =>   null,
                                                            'name' => $this->name_reloadcard_inter ?? null,
                                                            'provider_type' => 8,
                                                            'number_phone' =>  null,
                                                            'firstname' =>  null,
                                                            'lastname' =>  null,
                                                            'offer_id' =>  null,
                                                            'costs_amount' =>  $amount_user->percent * $valeur_amount,
                                                            'status' => 'PENDING',
                                                            'paiementChoix_phone' =>  null,
                                                            'paiementChoix_type' =>  null,
                                                            'factureChoix_type' => null,

                                                        ]
                                                    );


                                                    // Commissions Inters DIstributer Provider ECOBANK and NSIA
                                                    if (sabdy()->is_under_distributer == 1) {

                                                        // Valeur amount and commission User Auth
                                                        $commission = $valeur_convertion;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);


                                                        $admin_percent = $amount_user->percent * $valeur_amount;

                                                        // Devise convertion distributer and S-distributer in Auth devise
                                                        $commission_distributer =  $admin_percent * $amount_user->comission_distributer;

                                                        $admin_commission =  $admin_percent - $commission_distributer;

                                                        //  Convertion devise en Auth user
                                                        $convertion_commission_distributer =   $commission_distributer  /  sabdy()->wallet->currencie->value;

                                                        $valeur_received = $walletInter->amount -  ($amount_user->percent * $walletInter->amount);

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received  /  sabdy()->country->currencie->value,
                                                        ]);


                                                        $walletInter->update([
                                                            'commissions' => DB::raw('commissions+'  . $convertion_commission_distributer),
                                                        ]);
                                                        // send comission valeur admin 

                                                        if ($admin_civ->count() > 0) {

                                                            $nmbre_admin = $admin_civ->count();


                                                            $valeur_single = $admin_commission / $nmbre_admin;

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
                                                                    'description' => 'Paiement commisson sous distributeur',
                                                                ]);
                                                                $admin_single->wallet->update([
                                                                    'reload_by' => sabdy()->firstname .
                                                                        ' ' . sabdy()->lastname,
                                                                    'sid_user_relod' => sabdy()->id,
                                                                    'amount' => DB::raw('amount-' . $valeur_single),
                                                                ]);
                                                            }
                                                        }


                                                        $msg_title = 'Transfert internationnal';
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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

                                                        $commission = $valeur_convertion;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);


                                                        //  Convertion devise en Auth user
                                                        $convertion_commission_distributer =   ($amount_user->percent * $valeur_amount)  / sabdy()->wallet->currencie->value;


                                                        $valeur_received = $walletInter->amount -  ($amount_user->percent * $walletInter->amount);

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received  *  sabdy()->country->currencie->value,
                                                        ]);

                                                        // send comission valeur admin 

                                                        if ($admin_civ->count() > 0) {

                                                            $nmbre_admin = $admin_civ->count();

                                                            $valeur_single = $convertion_commission_distributer / $nmbre_admin;

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
                                                            $message = 'Vous avez effectué un rechargement de ' .
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
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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
                                            } else {

                                                if (sabdy()->is_under_distributer == 1) {

                                                    // Percent valeur of commission en CFA
                                                    $val_amount = $valeur_amount;

                                                    // Convertion valeur in Devise Custumers AUTH
                                                    $valeur_convertion  =  $val_amount  / sabdy()->wallet->currencie->value;
                                                } else {

                                                    // Percent valeur of commission en CFA
                                                    $val_amount = $valeur_amount + $amount_user->amount;

                                                    // Convertion valeur in Devise Custumers AUTH
                                                    $valeur_convertion  =  $val_amount  / sabdy()->wallet->currencie->value;
                                                }


                                                if ($valeur_convertion > sabdy()->wallet->amount) {
                                                    $this->resetInterField();
                                                    $this->emit('modal-hide-inters');
                                                    $this->emit('error', 'Verifier votre montant  svp ou votre pays !');
                                                } else {

                                                    $amount_solde =  sabdy()->wallet->amount -  $valeur_convertion;

                                                    $walletInter = WalletInter::create(
                                                        [

                                                            'amount_solde'  => $amount_solde ?? null,
                                                            'wallet_id' => sabdy()->wallet->id,
                                                            'amount' => $this->amount_reloadcard_inter ?? null,
                                                            'inter_cost_id' => null,
                                                            'deposit_cost_id' => 1,
                                                            'phone' =>  null,
                                                            'number_compte' =>  $this->account_number_reload_card_inter ?? null,
                                                            'created_by' => sabdy()->id,
                                                            'provider_id' =>  $provider_payment->id,
                                                            'status' => 'PENDING',
                                                            'country_id' => $country_robus->id,
                                                            'number_decoder' => null,
                                                            'id_invoice' =>  null,
                                                            'number_contract' =>  null,
                                                            'phone_invoice' =>  null,
                                                            'id_invoice_pre' =>   null,
                                                            'name' => $this->name_reloadcard_inter ?? null,
                                                            'provider_type' => 8,
                                                            'number_phone' =>  null,
                                                            'firstname' =>  null,
                                                            'lastname' =>  null,
                                                            'offer_id' =>  null,
                                                            'costs_amount' =>  $amount_user->percent * $valeur_amount,
                                                            'status' => 'PENDING',
                                                            'paiementChoix_phone' =>  null,
                                                            'paiementChoix_type' =>  null,
                                                            'factureChoix_type' => null,

                                                        ]
                                                    );


                                                    // Commissions Inters DIstributer Provider ECOBANK and NSIA
                                                    if (sabdy()->is_under_distributer == 1) {

                                                        // Valeur amount and commission User Auth
                                                        $commission = $valeur_convertion;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);


                                                        // Devise convertion distributer and S-distributer in Auth devise
                                                        $commission_distributer = $amount_user->comission_distributer;

                                                        $admin_commission =   $amount_user->amount - $commission_distributer;

                                                        //  Convertion devise en Auth user
                                                        $convertion_commission_distributer =  $commission_distributer  /  sabdy()->wallet->currencie->value;

                                                        $valeur_received = $walletInter->amount -  $amount_user->amount;

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received  /  sabdy()->country->currencie->value,
                                                        ]);


                                                        $walletInter->update([
                                                            'commissions' => DB::raw('commissions+'  . $convertion_commission_distributer),
                                                        ]);
                                                        // send comission valeur admin 

                                                        if ($admin_civ->count() > 0) {

                                                            $nmbre_admin = $admin_civ->count();

                                                            $valeur_single = $admin_commission / $nmbre_admin;

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
                                                            $message = 'Vous avez effectué un rechargement de ' .
                                                                $walletInter->amount .
                                                                sabdy()->wallet->currencie->code .
                                                                ' ' .
                                                                'par ' .
                                                                $walletInter->provider->name .
                                                                ' a ' .
                                                                $walletInter->created_at .
                                                                ' votre demande est en-cours de validation ' .
                                                                'Robus vous remercie';

                                                            $encode = substr(
                                                                Auth::guard(
                                                                    'web'
                                                                )->user()->phone,
                                                                -10
                                                            );

                                                            Http::get(
                                                                "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                                            );
                                                        }

                                                        $msg_title = 'Transfert internationnal';
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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


                                                        $commission_send = $valeur_amount + $amount_user->amount;

                                                        $convertion_commission_send =  $commission_send  /  sabdy()->wallet->currencie->value;


                                                        $valeur_received = $walletInter->amount -  $amount_user->amount;

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received  /  sabdy()->country->currencie->value,
                                                        ]);

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $convertion_commission_send),
                                                        ]);

                                                        $valeur_deposit_conver = $amount_user->amount;

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
                                                            $message = 'Vous avez effectué un rechargement de ' .
                                                                $walletInter->amount .
                                                                sabdy()->wallet->currencie->code .
                                                                ' ' .
                                                                'par ' .
                                                                $walletInter->provider->name .
                                                                ' a ' .
                                                                $walletInter->created_at .
                                                                ' votre demande est en-cours de validation ' .
                                                                'Robus vous remercie';

                                                            $encode = substr(
                                                                Auth::guard(
                                                                    'web'
                                                                )->user()->phone,
                                                                -10
                                                            );

                                                            Http::get(
                                                                "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                                            );
                                                        }

                                                        $msg_title = 'Transfert internationnal';
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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
                    } elseif (sabdy()->wallet->currencie->value <  $country_civ->currencie->value) {
                        // Montant Update cfa
                        $valeur_amount =  $this->amount_reloadcard_inter /  sabdy()->wallet->currencie->value;

                        if ($valeur_amount < 2000) {
                            $this->resetInterField();
                            $this->emit('modal-hide-inters');
                            $this->emit(
                                'error',
                                'Verifier le montant minimum est de 2000 !'
                            );
                        } else {

                            // Verify valeur amount max in Landing the ROBUS
                            $valeur_end = $percent_robus->levelDeposits->where('is_inter_bank', false)->where('is_ivoires', false)->last();

                            //  Conversion valeur
                            $conversion_valeur_end  = $valeur_end->amount_end * sabdy()->wallet->currencie->value;

                            if ($conversion_valeur_end  < $this->amount_reloadcard_inter) {
                                $this->resetInterField();
                                $this->emit('modal-hide-inters');
                                $this->emit('info', 'Vous avez depasser le montant max !');
                            } else {

                                foreach ($percent_robus->levelDeposits->where('is_inter_bank', false)->where('is_ivoires', false) as $amount_user) {

                                    if ($valeur_amount >=  $amount_user->amount_begin && $valeur_amount <= $amount_user->amount_end) {


                                        if ($som_transfers >= $valeur_ceiling) {
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit(
                                                'warning',
                                                'Votre montant journier est atteint !'
                                            );
                                        } else {


                                            if ($amount_user->amount == null) {

                                                if (sabdy()->is_under_distributer == 1) {
                                                    // Percent valeur of commission en CFA
                                                    $val_amount =  $valeur_amount;
                                                } else {
                                                    // Percent valeur of commission en CFA
                                                    $val_amount =  $valeur_amount +  $amount_user->percent * $valeur_amount;
                                                }


                                                // Convertion valeur in Devise Custumers AUTH
                                                $valeur_convertion  =  $val_amount  / sabdy()->wallet->currencie->value;


                                                if ($valeur_convertion >  sabdy()->wallet->amount) {
                                                    $this->resetInterField();
                                                    $this->emit('modal-hide-inters');
                                                    $this->emit('error', 'Verifier votre montant  svp ou votre pays !');
                                                } else {

                                                    $amount_solde =  sabdy()->wallet->amount -  $valeur_convertion;

                                                    $walletInter = WalletInter::create(
                                                        [

                                                            'amount_solde'  => $amount_solde ?? null,
                                                            'wallet_id' => sabdy()->wallet->id,
                                                            'amount' => $this->amount_reloadcard_inter ?? null,
                                                            'inter_cost_id' => null,
                                                            'deposit_cost_id' => 1,
                                                            'phone' =>  null,
                                                            'number_compte' =>  $this->account_number_reload_card_inter ?? null,
                                                            'created_by' => sabdy()->id,
                                                            'provider_id' =>  $provider_payment->id,
                                                            'status' => 'PENDING',
                                                            'country_id' => $country_robus->id,
                                                            'number_decoder' => null,
                                                            'id_invoice' =>  null,
                                                            'number_contract' =>  null,
                                                            'phone_invoice' =>  null,
                                                            'id_invoice_pre' =>   null,
                                                            'name' => $this->name_reloadcard_inter ?? null,
                                                            'provider_type' => 8,
                                                            'number_phone' =>  null,
                                                            'firstname' =>  null,
                                                            'lastname' =>  null,
                                                            'offer_id' =>  null,
                                                            'costs_amount' =>  $amount_user->percent * $valeur_amount,
                                                            'status' => 'PENDING',
                                                            'paiementChoix_phone' =>  null,
                                                            'paiementChoix_type' =>  null,
                                                            'factureChoix_type' => null,

                                                        ]
                                                    );




                                                    // Commissions Inters DIstributer Provider ECOBANK and NSIA
                                                    if (sabdy()->is_under_distributer == 1) {

                                                        // Valeur amount and commission User Auth
                                                        $commission = $valeur_convertion;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);


                                                        // Admin Commission valeur commission
                                                        $convertion_valeur_com_admin =  $amount_user->percent * $valeur_amount;

                                                        // Devise convertion distributer and S-distributer in Auth devise
                                                        $commission_distributer = $convertion_valeur_com_admin * $amount_user->comission_distributer;

                                                        $admin_commission = $convertion_valeur_com_admin -  $commission_distributer;
                                                        // Convertion valeur Distributeur

                                                        $valeur_conve_distri =  $commission_distributer / sabdy()->wallet->currencie->value;

                                                        $valeur_received = $walletInter->amount -  ($amount_user->percent * $walletInter->amount);

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received  * sabdy()->country->currencie->value,
                                                        ]);

                                                        // Commission distributer              
                                                        $walletInter->update([
                                                            'commissions' => DB::raw('commissions+'  . $valeur_conve_distri)
                                                        ]);
                                                        // send comission valeur admin 

                                                        if ($admin_civ->count() > 0) {

                                                            $nmbre_admin = $admin_civ->count();

                                                            $valeur_single = $admin_commission / $nmbre_admin;

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


                                                        $msg_title = 'Transfert internationnal';
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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


                                                        // Valeur amount and commission User Auth
                                                        $commission = $valeur_convertion;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);

                                                        // Admin Commission valeur commission
                                                        $convertion_valeur_com_admin =  $amount_user->percent * $valeur_amount;


                                                        $valeur_received = $walletInter->amount -  ($amount_user->percent * $walletInter->amount);

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received  * sabdy()->country->currencie->value,
                                                        ]);

                                                        // send comission valeur admin 

                                                        if ($admin_civ->count() > 0) {

                                                            $nmbre_admin = $admin_civ->count();

                                                            $valeur_single = $convertion_valeur_com_admin / $nmbre_admin;

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

                                                            $encode = substr(
                                                                Auth::guard(
                                                                    'web'
                                                                )->user()->phone,
                                                                -10
                                                            );

                                                            Http::get(
                                                                "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                                            );
                                                        }

                                                        $msg_title = 'Transfert internationnal';
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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
                                            } else {

                                                if (sabdy()->is_under_distributer == 1) {
                                                    // Percent valeur of commission en CFA
                                                    $val_amount =  $valeur_amount;
                                                } else {
                                                    // Percent valeur of commission en CFA
                                                    $val_amount =  $valeur_amount +  $amount_user->amount;
                                                }


                                                // Convertion valeur in Devise Custumers AUTH
                                                $valeur_convertion  =  $val_amount  / sabdy()->wallet->currencie->value;

                                                if ($val_amount >  sabdy()->wallet->amount) {
                                                    $this->resetInterField();
                                                    $this->emit('modal-hide-inters');
                                                    $this->emit('error', 'Verifier votre montant  svp ou votre pays !');
                                                } else {

                                                    $amount_solde =  sabdy()->wallet->amount -  $val_amount;

                                                    $walletInter = WalletInter::create(
                                                        [

                                                            'amount_solde'  => $amount_solde ?? null,
                                                            'wallet_id' => sabdy()->wallet->id,
                                                            'amount' => $this->amount_reloadcard_inter ?? null,
                                                            'inter_cost_id' => null,
                                                            'deposit_cost_id' => 1,
                                                            'phone' =>  null,
                                                            'number_compte' =>  $this->account_number_reload_card_inter ?? null,
                                                            'created_by' => sabdy()->id,
                                                            'provider_id' =>  $provider_payment->id,
                                                            'status' => 'PENDING',
                                                            'country_id' => $country_robus->id,
                                                            'number_decoder' => null,
                                                            'id_invoice' =>  null,
                                                            'number_contract' =>  null,
                                                            'phone_invoice' =>  null,
                                                            'id_invoice_pre' =>   null,
                                                            'name' => $this->name_reloadcard_inter ?? null,
                                                            'provider_type' => 8,
                                                            'number_phone' =>  null,
                                                            'firstname' =>  null,
                                                            'lastname' =>  null,
                                                            'offer_id' =>  null,
                                                            'costs_amount' =>  $amount_user->percent * $valeur_amount,
                                                            'status' => 'PENDING',
                                                            'paiementChoix_phone' =>  null,
                                                            'paiementChoix_type' =>  null,
                                                            'factureChoix_type' => null,

                                                        ]
                                                    );


                                                    // Commissions Inters DIstributer Provider ECOBANK and NSIA
                                                    if (sabdy()->is_under_distributer == 1) {


                                                        // Valeur amount and commission User Auth
                                                        $commission = $valeur_convertion;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);


                                                        // Devise convertion distributer and S-distributer in Auth devise
                                                        $commission_distributer = $amount_user->comission_distributer;

                                                        $admin_commission = $amount_user->amount -   $commission_distributer;

                                                        //  Convertion devise en Auth user
                                                        $convertion_commission_distributer =  $commission_distributer  /  sabdy()->wallet->currencie->value;


                                                        $walletInter->update([
                                                            'commissions' => DB::raw('commissions+'  . $convertion_commission_distributer),
                                                        ]);

                                                        $valeur_received = $walletInter->amount -  $amount_user->amount;

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received * sabdy()->country->currencie->value,
                                                        ]);

                                                        // send comission valeur under distributer 

                                                        if ($admin_civ->count() > 0) {

                                                            $nmbre_admin = $admin_civ->count();

                                                            $valeur_single = $admin_commission / $nmbre_admin;

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
                                                                    'description' => 'Paiement Commission sous distributeur',
                                                                ]);
                                                                $admin_single->wallet->update([
                                                                    'reload_by' => sabdy()->firstname .
                                                                        ' ' . sabdy()->lastname,
                                                                    'sid_user_relod' => sabdy()->id,
                                                                    'amount' => DB::raw('amount-' . $valeur_single),
                                                                ]);
                                                            }
                                                        }


                                                        $msg_title = 'Transfert internationnal';
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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

                                                        $valeur_deposit_conver =  $amount_user->amount;

                                                        $commission = $valeur_convertion;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);

                                                        $valeur_received = $walletInter->amount -  $amount_user->amount;

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received * sabdy()->country->currencie->value,
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

                                                            $encode = substr(
                                                                Auth::guard(
                                                                    'web'
                                                                )->user()->phone,
                                                                -10
                                                            );

                                                            Http::get(
                                                                "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                                            );
                                                        }

                                                        $msg_title = 'Transfert internationnal';
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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
                    } elseif (sabdy()->wallet->currencie->code == 'CFA') {

                        if ($this->amount_reloadcard_inter < 2000) {
                            $this->resetInterField();
                            $this->emit('modal-hide-inters');
                            $this->emit(
                                'error',
                                'Verifier le montant minimum est de 2000 !'
                            );
                        } else {

                            // Verify valeur amount max in Landing the ROBUS
                            $valeur_end = $percent_robus->levelDeposits->where('is_inter_bank', false)->where('is_ivoires', false)->last();

                            //  Conversion valeur
                            $conversion_valeur_end  = $valeur_end->amount_end;

                            if ($conversion_valeur_end  < $this->amount_reloadcard_inter) {
                                $this->resetInterField();
                                $this->emit('modal-hide-inters');
                                $this->emit('info', 'Vous avez depasser le montant max !');
                            } else {

                                foreach ($percent_robus->levelDeposits->where('is_inter_bank', false)->where('is_ivoires', false)  as $amount_user) {

                                    if ($this->amount_reloadcard_inter >=  $amount_user->amount_begin &&  $this->amount_reloadcard_inter <= $amount_user->amount_end) {


                                        if ($som_transfers >= $valeur_ceiling) {
                                            $this->resetInterField();
                                            $this->emit('modal-hide-inters');
                                            $this->emit(
                                                'warning',
                                                'Votre montant journier est atteint !'
                                            );
                                        } else {

                                            if ($amount_user->amount == null) {

                                                if (sabdy()->is_under_distributer == 1) {

                                                    $val_amount =  $this->amount_reloadcard_inter;
                                                } else {

                                                    $val_amount =  $this->amount_reloadcard_inter +  $amount_user->percent *  $this->amount_reloadcard_inter;
                                                }

                                                if ($val_amount > sabdy()->wallet->amount) {
                                                    $this->resetInterField();
                                                    $this->emit('modal-hide-inters');
                                                    $this->emit('error', 'Verifier votre montant  svp ou votre pays !');
                                                } else {

                                                    $amount_solde =  sabdy()->wallet->amount -  $val_amount;

                                                    $walletInter = WalletInter::create(
                                                        [

                                                            'amount_solde'  => $amount_solde ?? null,
                                                            'wallet_id' => sabdy()->wallet->id,
                                                            'amount' => $this->amount_reloadcard_inter ?? null,
                                                            'inter_cost_id' => null,
                                                            'deposit_cost_id' => 1,
                                                            'phone' =>  null,
                                                            'number_compte' =>  $this->account_number_reload_card_inter ?? null,
                                                            'created_by' => sabdy()->id,
                                                            'provider_id' =>  $provider_payment->id,
                                                            'status' => 'PENDING',
                                                            'country_id' => $country_robus->id,
                                                            'number_decoder' => null,
                                                            'id_invoice' =>  null,
                                                            'number_contract' =>  null,
                                                            'phone_invoice' =>  null,
                                                            'id_invoice_pre' =>   null,
                                                            'name' => $this->name_reloadcard_inter ?? null,
                                                            'provider_type' => 8,
                                                            'number_phone' =>  null,
                                                            'firstname' =>  null,
                                                            'lastname' =>  null,
                                                            'offer_id' =>  null,
                                                            'costs_amount' =>  $amount_user->percent *  $this->amount_reloadcard_inter,
                                                            'status' => 'PENDING',
                                                            'paiementChoix_phone' =>  null,
                                                            'paiementChoix_type' =>  null,
                                                            'factureChoix_type' => null,

                                                        ]
                                                    );



                                                    // Commissions Inters DIstributer Provider ECOBANK and NSIA
                                                    if (sabdy()->is_under_distributer == 1) {


                                                        // Valeur amount and commission User Auth
                                                        $commission = $val_amount;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);
                                                        // Admin Commission valeur commission
                                                        $convertion_valeur_com_admin =  $amount_user->percent *  $walletInter->amount;

                                                        // Devise convertion distributer and S-distributer in Auth devise
                                                        $commission_distributer = $convertion_valeur_com_admin * $amount_user->comission_distributer;

                                                        $admin_commission = $convertion_valeur_com_admin -  $commission_distributer;

                                                        $walletInter->update([
                                                            'commissions' => DB::raw('commissions+'  . $commission_distributer),
                                                        ]);

                                                        $valeur_received = $walletInter->amount -  ($amount_user->percent * $walletInter->amount);

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received,
                                                        ]);
                                                        // send comission valeur under distributer

                                                        if ($admin_civ->count() > 0) {

                                                            $nmbre_admin = $admin_civ->count();

                                                            $valeur_single = $admin_commission / $nmbre_admin;

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
                                                                    'description' => 'Paiement Commission sous distributeur',
                                                                ]);
                                                                $admin_single->wallet->update([
                                                                    'reload_by' => sabdy()->firstname .
                                                                        ' ' . sabdy()->lastname,
                                                                    'sid_user_relod' => sabdy()->id,
                                                                    'amount' => DB::raw('amount-' . $valeur_single),
                                                                ]);
                                                            }
                                                        }

                                                        $msg_title = 'Transfert internationnal';
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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

                                                        $commission = $val_amount;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);

                                                        // Admin Commission valeur commission
                                                        $convertion_valeur_com_admin =  ($amount_user->percent *  $walletInter->amount);

                                                        $valeur_received = $walletInter->amount -  ($amount_user->percent * $walletInter->amount);

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received,
                                                        ]);

                                                        // send comission valeur admin 
                                                        if ($admin_civ->count() > 0) {

                                                            $nmbre_admin = $admin_civ->count();

                                                            $valeur_single = $convertion_valeur_com_admin / $nmbre_admin;

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
                                                            $message = 'Vous avez effectué un rechargement de ' .
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
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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
                                            } else {

                                                if (sabdy()->is_under_distributer == 1) {

                                                    $val_amount =  $this->amount_reloadcard_inter;
                                                } else {

                                                    $val_amount =  $this->amount_reloadcard_inter +  $amount_user->amount;
                                                }

                                                if ($val_amount >  sabdy()->wallet->amount) {
                                                    $this->resetInterField();
                                                    $this->emit(
                                                        'modal-hide-inters'
                                                    );
                                                    $this->emit(
                                                        'error',
                                                        'Verifier votre montant  svp ou votre pays !'
                                                    );
                                                } else {

                                                    $amount_solde =  sabdy()->wallet->amount -  $val_amount;

                                                    $walletInter = WalletInter::create(
                                                        [

                                                            'amount_solde'  => $amount_solde ?? null,
                                                            'wallet_id' => sabdy()->wallet->id,
                                                            'amount' => $this->amount_reloadcard_inter ?? null,
                                                            'inter_cost_id' => null,
                                                            'deposit_cost_id' => 1,
                                                            'phone' =>  null,
                                                            'number_compte' =>  $this->account_number_reload_card_inter ?? null,
                                                            'created_by' => sabdy()->id,
                                                            'provider_id' =>  $provider_payment->id,
                                                            'status' => 'PENDING',
                                                            'country_id' => $country_robus->id,
                                                            'number_decoder' => null,
                                                            'id_invoice' =>  null,
                                                            'number_contract' =>  null,
                                                            'phone_invoice' =>  null,
                                                            'id_invoice_pre' =>   null,
                                                            'name' => $this->name_reloadcard_inter ?? null,
                                                            'provider_type' => 8,
                                                            'number_phone' =>  null,
                                                            'firstname' =>  null,
                                                            'lastname' =>  null,
                                                            'offer_id' =>  null,
                                                            'costs_amount' =>  $amount_user->percent *  $this->amount_reloadcard_inter,
                                                            'status' => 'PENDING',
                                                            'paiementChoix_phone' =>  null,
                                                            'paiementChoix_type' =>  null,
                                                            'factureChoix_type' => null,

                                                        ]
                                                    );


                                                    // Commissions Inters DIstributer Provider ECOBANK and NSIA
                                                    if (sabdy()->is_under_distributer == 1) {


                                                        // Valeur amount and commission User Auth
                                                        $commission = $val_amount;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);

                                                        // Devise convertion distributer and S-distributer in Auth devise
                                                        $commission_distributer = $amount_user->comission_distributer;

                                                        // Admin Commission valeur commission
                                                        $convertion_valeur_com_admin =  $amount_user->amount;

                                                        $valeur_received = $walletInter->amount -  $amount_user->amount;


                                                        $walletInter->update([
                                                            'commissions' => DB::raw('commissions+'  . $commission_distributer),
                                                        ]);

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received,
                                                        ]);
                                                        // send comission valeur under distributer 

                                                        if ($admin_civ->count() > 0) {

                                                            $nmbre_admin = $admin_civ->count();

                                                            $valeur_single = $convertion_valeur_com_admin / $nmbre_admin;

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
                                                                    'description' => 'Paiement Commission sous distributeur',
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
                                                            $message = 'Vous avez effectué un rechargement de ' .
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
                                                        $msg = 'Demande de rechargement est en cours de validation ';
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
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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

                                                        $commission = $val_amount;

                                                        sabdy()->wallet->update([
                                                            'reload_by' => sabdy()->firstname .
                                                                ' ' . sabdy()->lastname,
                                                            'sid_user_relod' => sabdy()->id,
                                                            'amount' => DB::raw('amount-' . $commission),
                                                        ]);


                                                        $valeur_deposit_conver = $amount_user->amount;

                                                        $valeur_received = $walletInter->amount;

                                                        $walletInter->update([
                                                            'amount_received'  =>  $valeur_received,
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
                                                            $message = 'Vous avez effectué un rechargement de ' .
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
                                                        $msg = 'Demande de rechargement est en cours de validation ';
                                                        sabdy()->notify(
                                                            new UserNotification([
                                                                'title' => $msg_title,
                                                                'message' => $msg,
                                                            ])
                                                        );
                                                        $notifs =  sabdy()->unreadNotifications;
                                                        event(new CreateWalletInterEvent($walletInter));
                                                        $this->emit('transaction_inter_sends', $walletInter->id);
                                                        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                                        Notification::send($admins, new AdminNotification('Demande de rechargement de ' .
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
        $commission_reload_inter_val_inter_true_receive = null;
        $commission_reload_inter_val_inter_true_pay = null;
        $commission_reload_val_inter_convert_pay = null;
        $commission_virement_val_inter_convert_pay = null;
        $commission_phone_credit_val_inter_convert_pay = null;

        $amount_received_canal = null;
        $country_civ = Country::whereId(107)
            // ->whereStatus(true)
            ->first();
        $inter_search = WalletInter::query();


        if ($this->amount_reloadcard_inter != '') {
            //Valuer inter  bank
            $commission_inter = DepositCost::whereId(1)->first();

            if (sabdy()->wallet->currencie->value >  $country_civ->currencie->value) {

                $valeur_amount = $this->amount_reloadcard_inter * sabdy()->wallet->currencie->value;

                // Verify valeur amount max in Landing the ROBUS
                $valeur_end = $commission_inter->levelDeposits->where('is_inter_bank', true)->where('is_ivoires', false)->last();

                //  Conversion valeur
                $conversion_valeur_end  = $valeur_end->amount_end /  sabdy()->wallet->currencie->value;

                $valeur_first = $commission_inter->levelDeposits->where('is_inter_bank', true)->where('is_ivoires', false)->first();

                //  Conversion valeur        
                $conversion_valeur_first  = $valeur_first->amount_begin / sabdy()->wallet->currencie->value;

                if ($conversion_valeur_first  > $this->amount_reloadcard_inter) {

                    $commission_reload_inter_val_inter_true_pay = "Montant min est  : " .  number_format($conversion_valeur_first, 2, ',', ' ');

                    $commission_reload_inter_val_inter_true_receive = "Montant min est  : " .  number_format($conversion_valeur_first, 2, ',', ' ');
                } elseif ($conversion_valeur_end  < $this->amount_reloadcard_inter) {
                    $commission_reload_inter_val_inter_true_pay = "Montant maxi est  : " .  number_format($conversion_valeur_end);

                    $commission_reload_inter_val_inter_true_receive = "Montant maxi est  : " . number_format($conversion_valeur_end);
                } else {

                    foreach ($commission_inter->levelDeposits->where('is_inter_bank', true)->where('is_ivoires', false) as $amount_comission) {

                        if ($valeur_amount >= $amount_comission->amount_begin &&  $valeur_amount <= $amount_comission->amount_end) {

                            if ($amount_comission->amount == null) {

                                $valeur_cfa =   $amount_comission->percent * $valeur_amount;

                                $commission_reload_inter_val_inter_true_pay = number_format($valeur_cfa / sabdy()->wallet->currencie->value, 2, ',', ' ');

                                if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                                    $commission_reload_val_inter_convert_pay  =  $valeur_cfa / sabdy()->wallet->currencie->value;


                                    $amount_received_canal = (float) $amount_comission->comission_distributer * (float) $commission_reload_val_inter_convert_pay;
                                }

                                // Valeur receive 

                                $receive_valeur = $valeur_amount;


                                if ($receive_valeur < 0) {

                                    $commission_reload_inter_val_inter_true_receive = "Montant negatif";
                                } else {

                                    $commission_reload_inter_val_inter_true_receive = number_format($receive_valeur, 2, ',', ' ');
                                }
                            } else {


                                $commission_reload_inter_val_inter_true_pay = number_format($amount_comission->amount / sabdy()->wallet->currencie->value, 2, ',', ' ');

                                if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                                    $amount_received_canal = (float) $amount_comission->comission_distributer / sabdy()->wallet->currencie->value;
                                }
                                // Convertion commission
                                $valeur_cfa_receive =  $valeur_amount;

                                if ($valeur_cfa_receive < 0) {
                                    $commission_reload_inter_val_inter_true_receive =  "Montant negatif";
                                } else {

                                    $commission_reload_inter_val_inter_true_receive = number_format($valeur_cfa_receive / sabdy()->wallet->currencie->value, 2, ',', ' ');
                                }
                            }
                        }
                    }
                }
            } elseif (sabdy()->wallet->currencie->value <  $country_civ->currencie->value) {

                $valeur_amount = $this->amount_reloadcard_inter * sabdy()->wallet->currencie->value;

                // Verify valeur amount max in Landing the ROBUS
                $valeur_end = $commission_inter->levelDeposits->where('is_inter_bank', true)->where('is_ivoires', false)->last();

                //  Conversion valeur
                $conversion_valeur_end  = $valeur_end->amount_end * sabdy()->wallet->currencie->value;

                $valeur_first = $commission_inter->levelDeposits->where('is_inter_bank', true)->where('is_ivoires', false)->first();

                //  Conversion valeur        
                $conversion_valeur_first  = $valeur_first->amount_begin * sabdy()->wallet->currencie->value;

                //  Conversion valeur

                if ($conversion_valeur_first  > $this->amount_reloadcard_inter) {

                    $commission_reload_inter_val_inter_true_pay = "Montant min est  : " .  number_format($conversion_valeur_first, 2, ',', ' ');

                    $commission_reload_inter_val_inter_true_receive = "Montant min est  : " .  number_format($conversion_valeur_first, 2, ',', ' ');
                } elseif ($conversion_valeur_end  < $this->amount_reloadcard_inter) {
                    $commission_reload_inter_val_inter_true_pay = "Montant maxi est  : " .  number_format($conversion_valeur_end);

                    $commission_reload_inter_val_inter_true_receive = "Montant maxi est  : " . number_format($conversion_valeur_end);
                } else {

                    foreach ($commission_inter->levelDeposits->where('is_inter_bank', true)->where('is_ivoires', false) as $amount_comission) {
                        if ($valeur_amount >= $amount_comission->amount_begin && $valeur_amount <= $amount_comission->amount_end) {

                            if ($amount_comission->amount == null) {

                                $valeur_cfa =  $amount_comission->percent * $valeur_amount;


                                // Valuer calcul pay
                                $commission_reload_inter_val_inter_true_pay = number_format($valeur_cfa / sabdy()->wallet->currencie->value, 2, ',', ' ');

                                if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                                    $commission_phone_credit_val_inter_convert_pay  =  $valeur_cfa / sabdy()->wallet->currencie->value;

                                    $amount_received_canal = (float) $amount_comission->comission_distributer * (float) $commission_phone_credit_val_inter_convert_pay;
                                }
                                // Valeur receive 

                                $receive_valeur = $valeur_amount;

                                if ($receive_valeur < 0) {

                                    $commission_reload_inter_val_inter_true_receive = "Montant negatif";
                                } else {

                                    $commission_reload_inter_val_inter_true_receive = number_format($receive_valeur);
                                }
                            } else {


                                $commission_reload_inter_val_inter_true_pay = number_format($amount_comission->amount / sabdy()->wallet->currencie->value, 2, ',', ' ');

                                if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                                    $amount_received_canal = (float) $amount_comission->comission_distributer / sabdy()->wallet->currencie->value;
                                }

                                // Convertion commission
                                $valeur_cfa_receive =  $valeur_amount;

                                if ($valeur_cfa_receive < 0) {
                                    $commission_reload_inter_val_inter_true_receive = "Montant negatif";
                                } else {

                                    $commission_reload_inter_val_inter_true_receive = number_format($valeur_cfa_receive / sabdy()->wallet->currencie->value, 2, ',', ' ');
                                }
                            }
                        }
                    }
                }
            } elseif (sabdy()->wallet->currencie->code == 'CFA') {

                $valeur_amount = $this->amount_reloadcard_inter;

                // Verify valeur amount max in Landing the ROBUS
                $valeur_end = $commission_inter->levelDeposits->where('is_inter_bank', true)->where('is_ivoires', false)->last();

                //  Conversion valeur
                $conversion_valeur_end  = $valeur_end->amount_end;

                $valeur_first = $commission_inter->levelDeposits->where('is_inter_bank', true)->where('is_ivoires', false)->first();

                //  Conversion valeur        
                $conversion_valeur_first  = $valeur_first->amount_begin;

                if ($conversion_valeur_first  > $this->amount_reloadcard_inter) {

                    $commission_reload_inter_val_inter_true_pay = "Montant min est  : " .  number_format($conversion_valeur_first, 2, ',', ' ');

                    $commission_reload_inter_val_inter_true_receive = "Montant min est  : " .  number_format($conversion_valeur_first, 2, ',', ' ');
                } elseif ($conversion_valeur_end  < $this->amount_reloadcard_inter) {
                    $commission_reload_inter_val_inter_true_pay = "Montant maxi est  : " .  number_format($conversion_valeur_end);

                    $commission_reload_inter_val_inter_true_receive = "Montant maxi est  : " . number_format($conversion_valeur_end);
                } else {

                    foreach ($commission_inter->levelDeposits->where('is_inter_bank', true)->where('is_ivoires', false)  as $amount_comission) {

                        if ($valeur_amount >= $amount_comission->amount_begin && $valeur_amount <= $amount_comission->amount_end) {
                            if ($amount_comission->amount == null) {

                                $valeur_calcul_pay = $amount_comission->percent * $valeur_amount;

                                // Commission valuer ROBUS
                                $commission_reload_inter_val_inter_true_pay = number_format($amount_comission->percent * $valeur_amount, 2, ',', ' ');

                                if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                                    $amount_received_canal = (float) $amount_comission->comission_distributer * (float) $valeur_calcul_pay;
                                }
                                // Valeur receive 

                                $receive_valeur = $this->amount_reloadcard_inter;

                                if ($receive_valeur < 0) {

                                    $commission_reload_inter_val_inter_true_receive = "Montant negatif";
                                } else {

                                    $commission_reload_inter_val_inter_true_receive = number_format($receive_valeur, 2, ',', ' ');
                                }
                            } else {

                                $commission_reload_inter_val_inter_true_pay = $amount_comission->amount;

                                $valeur_verify =   $valeur_amount;

                                if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                                    $amount_received_canal = (float) $amount_comission->comission_distributer;
                                }

                                if ($valeur_verify < 0) {
                                    $commission_reload_inter_val_inter_true_receive =  "Montant negatif";
                                } else {

                                    $commission_reload_inter_val_inter_true_receive =  number_format($valeur_amount, 2, ',', ' ');
                                }
                            }
                        }
                    }
                }
            }
        }

        return view('livewire.transaction-payments.reload-transaction', [
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
            'amount_deposit_inter_inter_pay' => $commission_reload_inter_val_inter_true_pay,
            'amount_deposit_inter_inter_receive' => $commission_reload_inter_val_inter_true_receive,
            'amount_received_credit' => $amount_received_canal,
            'provider_reload_inters' => $this->RelaodCardInter,
            'usersCountry' => sabdy()->wallet->currencie->code,
        ]);
    }


    public function getRelaodCardInterProperty()
    {


        $reload_transaction = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', sabdy()->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 11)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();

        return $reload_transaction;
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
