<?php

namespace App\Http\Livewire\TransactionPayments;

use Livewire\Component;
use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use App\Models\Banks\BankCard;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Events\AdminNotifEvent;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use App\Models\Wallets\WalletBank;
use Illuminate\Support\Facades\DB;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Http;
use App\Models\Costs\PhoneCreditCost;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Events\CreateWalletInterEvent;
use App\Notifications\UserNotification;
use App\Notifications\AdminNotification;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Admin\MailController;

class PhoneTransaction extends Component
{
    public $provider_phone_credit_inter;
    public $number_phone_credit_inter;
    public $amount_phone_credit_inter;
    public $country_inter_robus_inter;
    public $interId;


    public function resetValeurReload()
    {
        $this->provider_phone_credit_inter = '';
        $this->number_phone_credit_inter = '';
        $this->amount_phone_credit_inter = '';
        // $this->country_inter_robus_inter = null;
        // $this->emit('modal-hide-transfers-validated');
        // $this->emit('modal-hide-transfers');
    }

    public function resetInterField()
    {
        $this->provider_phone_credit_inter = '';
        $this->number_phone_credit_inter = '';
        $this->amount_phone_credit_inter = '';
        $this->country_inter_robus_inter = null;
        $this->emit('menu-inters-active');
        $this->emit('modal-hide-inters');
        $this->emit('transaction_inters');
    }
    public function mount()
    {
        $this->resetValeurReload();
        // $this->emit('monEvenement', $resetValeurReload);
    }

    public function cancelInterPhoneField()
    {
        $this->provider_phone_credit_inter = '';
        $this->number_phone_credit_inter = '';
        $this->amount_phone_credit_inter = '';
        $this->country_inter_robus_inter = null;
        $this->emit('menu-inters-active');
        $this->emit('modal-hide-inters');
        $this->emit('transaction_inters');
    }

    public function addInterPhone()
    {
        $check = WalletInter::whereId($this->interId)->first();
        $country_robus = Country::whereId($this->country_inter_robus_inter)
            // ->whereStatus(true)
            ->first();

        $country_civ = Country::whereId(107)
            // ->whereStatus(true)
            ->first();

        $provider_phone_credit = Provider::whereId($this->provider_phone_credit_inter)->first();

        $admin_civ = $this->getAdminCIVProperty();
        $som_transfers =  $this->getSumValTransactionProperty();

        // Valuer amount cota
        $valeur_ceiling = sabdy()->silver_ceiling;

        try {

            if (!$check) {

                if ( $this->provider_phone_credit_inter == 56 ||
                    $this->provider_phone_credit_inter == 57 ||
                    $this->provider_phone_credit_inter == 58) {

                    $this->validate(
                        [
                            'number_phone_credit_inter' => 'required',
                            'amount_phone_credit_inter' => 'required',
                            'country_inter_robus_inter' => 'required',
                        ],
                        [
                            'amount_phone_credit_inter.required' =>
                            'Votre montant est obligatoire',
                            'country_inter_robus_inter.required' =>
                            'Pays  est obligatoire',
                            'number_phone_credit_inter.required' =>
                            'Numero est obligatoire',
                        ]
                    );


                    $percent_robus = PhoneCreditCost::whereId(2)->first();


                    if ($som_transfers >= $valeur_ceiling) {
                        $this->resetInterField();
                        $this->emit('modal-hide-inters');
                        $this->emit('\App\Models\Costs\BankCost', 'Votre montant journier est atteint !');
                    } else {

                        if (sabdy()->is_under_distributer == 1) {
                            // Verification valeur amount with amount acompte Use
                            $val_amount =  $this->amount_phone_credit_inter;
                        } else {

                            // Verification valeur amount with amount acompte Use
                            $val_amount =  $this->amount_phone_credit_inter +  $percent_robus->percent *  $this->amount_phone_credit_inter;
                        }


                        if ($val_amount > sabdy()->wallet->amount) {
                            $this->resetInterField();
                            $this->emit('modal-hide-inters');
                            $this->emit(
                                'error',
                                'Verifier votre solde ou votre pays d\'envoie svp !'
                            );
                        } else {

                            $amount_solde =  sabdy()->wallet->amount -  $val_amount;

                            $walletInter = WalletInter::create([
                                'amount_solde'  =>   $amount_solde ?? null,
                                'wallet_id' => sabdy()->wallet->id,
                                'amount' => $this->amount_phone_credit_inter,
                                'inter_cost_id' => null,
                                'phone_credit_cost_id' => 2,
                                'phone' =>  $country_robus->indecative . $this->number_phone_credit_inter ?? null,
                                'number_compte' =>  null,
                                'created_by' => sabdy()->id,
                                'provider_id' => $provider_phone_credit->id,
                                'status' => 'PENDING',
                                'country_id' => $country_robus->id,
                                'number_decoder' =>  null,
                                'id_invoice' =>  null,
                                'number_contract' => null,
                                'phone_invoice' =>  null,
                                'id_invoice_pre' =>   null,
                                'name' =>  null,
                                'provider_type' => 6,
                                'number_phone' => null,
                                'offer_id' =>  null,
                                'costs_amount' =>  $percent_robus->percent *  $this->amount_phone_credit_inter,
                                'paiementChoix_phone' =>  $this->paiementChoix_phone ?? null,
                                'paiementChoix_type' => $this->paiementChoix_type ?? null,
                                'factureChoix_type' =>  $this->facture_choix_type ?? null,
                            ]);

                            $commission =  $val_amount;

                            sabdy()->wallet->update([
                                'reload_by' => sabdy()->firstname .
                                    ' ' . sabdy()->lastname,
                                'sid_user_relod' => sabdy()->id,
                                'amount' => DB::raw('amount-' . $commission),
                            ]);



                            // Commissions Inters 
                            if (sabdy()->is_under_distributer == 1) {


                                $msg_title = 'Transfert internationnal';
                                $msg = 'Demande de paiement de credit en cours de validation ';
                                sabdy()->notify(
                                    new UserNotification([
                                        'title' => $msg_title,
                                        'message' => $msg,
                                    ])
                                );

                                $valeur_percent_admin  = $percent_robus->percent * $walletInter->amount;

                                $valuer_deposit =   $valeur_percent_admin  *   $percent_robus->percent_merchands;
                                
                                $valuer_admin =   $valeur_percent_admin - $valuer_deposit;

                                if (sabdy()->wallet->currencie->value >  $country_civ->currencie->value)
                                {
                                    if (sabdy()->country->id  != 107)
                                    {
                                       
                                        $valeur_received = $walletInter->amount -  ($percent_robus->percent * $walletInter->amount);

                                        $walletInter->update([
                                            'amount_received'  =>  $valeur_received  *  sabdy()->country->currencie->value,
                                        ]);

                                        $admin_convertion =   $valuer_admin  *  sabdy()->country->currencie->value;
                                        
                                    }else {

                                        $valeur_received = $walletInter->amount -  ($percent_robus->percent * $walletInter->amount);

                                        $walletInter->update([
                                            'amount_received'  =>  $valeur_received  /  $country_civ->currencie->value,
                                        ]);

                                        $admin_convertion =   $valuer_admin  /   $country_civ->currencie->value;

                                    }

                                }elseif(sabdy()->wallet->currencie->code == 'CFA'){

                                    $valeur_received = $walletInter->amount -  ($percent_robus->percent * $walletInter->amount);

                                    $walletInter->update([
                                        'amount_received'  =>  $valeur_received ,
                                    ]);

                                    $admin_convertion =   $valuer_admin ;

                                }elseif(sabdy()->country->currencie->value < $country_robus->currencie->value)
                                {

                                    $valeur_received = $walletInter->amount -  ($percent_robus->percent * $walletInter->amount);

                                    $walletInter->update([
                                        'amount_received'  =>  $valeur_received  / $country_civ->currencie->value,
                                    ]);

                                    $admin_convertion =   $valuer_admin  /   $country_civ->currencie->value;

                                }

                                $walletInter->update([
                                    'commissions' => DB::raw('commissions+'  . $valuer_deposit),
                                ]);


                                if ($admin_civ->count() > 0) {

                                    $nmbre_admin = $admin_civ->count();

                                    $valeur_single = $admin_convertion / $nmbre_admin;

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
                                                ' ' .
                                                sabdy()->lastname,
                                            'sid_user_relod' => sabdy()->id,
                                            'amount' => DB::raw('amount+' . $valeur_single),
                                        ]);
                                    }
                                }


                                $notifs = sabdy()->unreadNotifications;
                                event(new CreateWalletInterEvent($walletInter));
                                $this->emit('transaction_inter_sends', $walletInter->id);
                                $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
                                Notification::send($admins, new AdminNotification('Demande de credit telephonique de ' .
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


                                if (sabdy()->country->code == 'CI') {
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

                                    Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                                }
                                // Percent admin 
                                $valeur_percent_admin  = $percent_robus->percent * $walletInter->amount;


                                if (sabdy()->wallet->currencie->value >  $country_civ->currencie->value)
                                {
                                    if (sabdy()->country->id  != 107)
                                    {
                                       
                                        $valeur_deposit_conver =   $valeur_percent_admin  *  sabdy()->wallet->currencie->value;

                                        
                                    }else {

                                        $valeur_deposit_conver =   $valeur_percent_admin  / $country_civ->currencie->value;

                                    }

                                }elseif(sabdy()->wallet->currencie->code == 'CFA'){

                                    $valeur_deposit_conver =   $valeur_percent_admin ;


                                }elseif(sabdy()->country->currencie->value < $country_robus->currencie->value)
                                {

                                    $valeur_deposit_conver =   $valeur_percent_admin  / $country_civ->currencie->value;

                                }
                                

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
                                                ' ' .
                                                sabdy()->lastname,
                                            'sid_user_relod' => sabdy()->id,
                                            'amount' => DB::raw('amount+' . $valeur_single),
                                        ]);
                                    }
                                }

                                $msg_title = 'Transfert internationnal';
                                $msg =
                                    'Demande de paiement de credit en cours de validation ';
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
                                Notification::send($admins, new AdminNotification('Demande de credit telephonique de ' .
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
                }else {
                    $this->resetInterField();
                    $this->emit('modal-hide-inters');
                    $this->emit('error', 'Vous n\'avez Pas selectionnez de Providers Merci bien !');
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
        $commission_phone_credit_val_inter_receive = null;
        $commission_phone_credit_val_inter_pay = null;

        $amount_received_canal = null;
        $country_civ = Country::whereId(107)
            // ->whereStatus(true)
            ->first();
        $inter_search = WalletInter::query();

        // Phone inter 
        if ($this->amount_phone_credit_inter != '') {

            $commission_phone_credit = PhoneCreditCost::whereId(2)->first();

            if (sabdy()->wallet->currencie->value >  $country_civ->currencie->value) {

                $commission_phone_credit_val_inter_pay = (float) $this->amount_phone_credit_inter * (float) $commission_phone_credit->percent;

                $valeur_receive = (float) $this->amount_phone_credit_inter;

                if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                    $amount_received_canal = (float) $commission_phone_credit->percent_merchands * (float) $commission_phone_credit_val_inter_pay;
                }

                if ($valeur_receive < 0) {

                    $commission_phone_credit_val_inter_receive = "Montant negatif";
                } else {

                    $commission_phone_credit_val_inter_receive =  $valeur_receive;
                }
            } elseif (sabdy()->wallet->currencie->value <  $country_civ->currencie->value) {

                $commission_phone_credit_val_inter_pay = (float) $this->amount_phone_credit_inter * (float) $commission_phone_credit->percent;

                $valeur_receive = (float) $this->amount_phone_credit_inter;

                if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                    $amount_received_canal = (float) $commission_phone_credit->percent_merchands * (float) $commission_phone_credit_val_inter_pay;
                }

                if ($valeur_receive < 0) {

                    $commission_phone_credit_val_inter_receive = "Montant negatif";
                } else {

                    $commission_phone_credit_val_inter_receive =  $valeur_receive;
                }
            } elseif (sabdy()->wallet->currencie->code == 'CFA') {

                $commission_phone_credit_val_inter_pay = (float) $this->amount_phone_credit_inter * (float) $commission_phone_credit->percent;

                $valeur_receive = (float) $this->amount_phone_credit_inter;

                if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                    $amount_received_canal = (float) $commission_phone_credit->percent_merchands * (float) $commission_phone_credit_val_inter_pay;
                }

                if ($valeur_receive < 0) {

                    $commission_phone_credit_val_inter_receive = "Montant negatif";
                } else {

                    $commission_phone_credit_val_inter_receive =  $valeur_receive;
                }
            }
        }

        return view('livewire.transaction-payments.phone-transaction', [
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
            'amount_phone_credit_val_pay' => $commission_phone_credit_val_inter_pay,
            'amount_phone_credit_val_receive' => $commission_phone_credit_val_inter_receive,
            'amount_received_credit' => $amount_received_canal,
            'provider_credits_inter' => $this->PhoneCreditTransactionInter,
            'usersCountry' => sabdy()->wallet->currencie->code,
        ]);
    }

    public function getPhoneCreditTransactionInterProperty()
    {

        $phone_transaction = Provider::join(
            'country_services',
            'country_services.provider_id',
            'providers.id'
        )
            ->where('country_services.country_id', sabdy()->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', 9)
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
