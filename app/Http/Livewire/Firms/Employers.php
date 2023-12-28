<?php

namespace App\Http\Livewire\Firms;

use Livewire\Component;
use App\Models\AdminsCom;
use App\Models\Categorie;
use App\Models\Sid\SidUser;
use Livewire\WithPagination;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Events\AdminNotifEvent;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\DB;
use App\Models\Payments\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Costs\TransactionCost;
use App\Notifications\UserNotification;
use App\Notifications\AdminNotification;
use App\Models\Wallets\WalletTransaction;
use App\Events\CreateWalletTransfersEvent;
use App\Models\Invoices\InvoiceTransaction;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Admin\MailController;

class Employers extends Component
{

    use WithPagination;

    public $search = "";
    public $countriesFilter = "";
    public $statusFilter_piece = "";
    public $statusFilter_active = "";

    public $wallet_id;
    public $user_id;
    public $categorieId;
    public $employerDesabled;
    public $amount_payment;
    public $choose_type_payement_emp;
    public $wallet_id_employer;
    public $amount_payment_wallet;
    public $wallet_id_add;
    public $categorieId_add;
    public $transaction_description_robus;

    public function add_employers()
    {

        $this->validate([
            'wallet_id_add' => 'required',
            'categorieId_add' => 'required',
        ], [
            'wallet_id_add.required' => 'Votre wallet est obligatoire',
            'categorieId_add.required' => 'la categorie est obligatoire',

        ]);

        $check = Wallet::where('wallet_id', $this->wallet_id_add)->first();

        if ($check) {


            if ($check->sidUser->id == firm()->id || $check->sidUser->is_company == true) {

                $this->emit('modal-hide', 'hide');
                $this->cancelEmployesField();
                $this->emit('info', 'Desolé Ce wallet est pour vous !!!');
            } else {

                // Update Users
                $check->sidUser->update(
                    [
                        'company_id' => firm()->id,
                        'categorie_id'  => $this->categorieId_add,
                    ]
                );

                // Update Wallets
                $check->update(
                    [
                        'company_id' => firm()->id,
                    ]
                );

                $this->emit('modal-hide', 'hide');
                $this->cancelEmployesField();
                $this->emit('success', 'Employés ajouté !');
            }
        } else {
            $this->emit('modal-hide', 'hide');
            $this->cancelEmployesField();
            $this->emit('error', 'Numero Inconnu chez ROBUS !');
        }
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
                firm()->country->id
            )
            ->where(
                'wallet_transactions.created_by',
                firm()->id
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


    public function payments_employers()
    {



        $check = Wallet::where('wallet_id', $this->wallet_id)->first();
        $som_transfers =  $this->getWalletTransactionsCeilingProperty();
        $valeur_ceiling = firm()->silver_ceiling;
        $walletUser = firm();
        $admin_civ = $this->getAdminCIVProperty();
        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $admins = $this->getAdminValeurProperty();

        if (!$check) {

            if ($this->choose_type_payement_emp == 1) {



                $this->validate([
                    'amount_payment' => 'required',
                    'categorieId' => 'required',
                    'transaction_description_robus' => 'required',
                ], [
                    'amount_payment.required' => 'Votre montant est obligatoire',
                    'categorieId.required' => 'la categorie est obligatoire',
                    'transaction_description_robus.required' => 'la description est obligatoire',
                ]);

                $account_employers =  SidUser::where('company_id', firm()->id)->where('categorie_id', $this->categorieId)->count();

                $amount_payment_alls = (float)$account_employers  * (float) $this->amount_payment;


                if ($som_transfers >= $valeur_ceiling) {
                    $this->cancelEmployesField();
                    $this->emit('modal-hide');
                    $this->emit(
                        'warning',
                        'Votre montant journier est atteint !'
                    );
                } elseif ($amount_payment_alls > $walletUser->wallet->amount) {
                    $this->cancelEmployesField();
                    $this->emit('modal-hide');
                    $this->emit('success', 'Verifier votre montant ! !');
                } else {


                    $percent_robus = TransactionCost::where('is_sociaty', true)->first();

                    $amount_employers =  SidUser::where('company_id', firm()->id)->where('categorie_id', $this->categorieId)->get();

                    $amount_solde =  $walletUser->wallet->amount -  $amount_payment_alls;

                    $amount_recevied = $this->amount_payment - ($percent_robus->percent * $this->amount_payment);

                    $walletUser->wallet->update([
                        'reload_by' => firm()->firstname .
                            ' ' . firm()->lastname,
                        'sid_user_relod' => firm()->id,
                        'amount' => DB::raw('amount-' . $amount_payment_alls),
                    ]);

                    foreach ($amount_employers  as  $key => $wallet_user) {

                        // $amount_solde = $check->wallet->amount +  $commission;
                        $walletAmountSend = Wallet::where('wallet_id', $wallet_user->wallet_id)->first();

                        $amount_solde_drawal = (float)$walletAmountSend->amount +  (float) $this->amount_payment;

                        $amount_drawal =      $amount_solde_drawal;

                        $valeur_receive =  $this->convertAdminCommission($amount_recevied, $walletAmountSend);


                        $walletTransaction = WalletTransaction::create(
                            [
                                'wallet_id' => firm()->wallet->id,
                                'amount' => $this->amount_payment,
                                'description' => $this->transaction_description_robus ?? null,
                                'transaction_cost_id' =>  $percent_robus->id,
                                'phone' => $this->transaction_phone ?? null,
                                'costs_amount' => (float)$percent_robus->percent * (float)$this->amount_payment,
                                'amount_received' => $valeur_receive,
                                'num_wallet' => $wallet_user->wallet->wallet_id,
                                'created_by' => firm()->id,
                                'provider_id' => 9,
                                // 'choose_type_transaction' => $this->choose_type_transaction,
                                'choose_type_payement_emp' => $this->choose_type_payement_emp,
                                'type_transaction' => 'Depot',
                                'type_received' => 'Transfert',
                                'status' => 'SUCCESS',
                                'amount_solde' => $amount_solde,
                                'amount_solde_drawal' => $amount_drawal ?? null

                            ]
                        );



                        //valeur comission send
                        $commission = $amount_payment_alls;

                        // Valur off commission
                        $commission_valeur = $percent_robus->percent * $amount_payment_alls;

                        // Valeur received User
                        $amount_received_other = $amount_payment_alls / $account_employers;

                        $valuer_receive = $amount_received_other - ($percent_robus->percent * $amount_received_other);

                        $convertion_receive_admin =  $this->convertAdminCommissionReceived($commission_valeur);

                        $convertion_receive_user =  $this->convertAdminCommissionReceived($valuer_receive);

                        // wallet_valeur
                        // Send in compte User and name reload

                        $wallet_user->wallet->update([
                            'reload_by' => firm()->firstname . ' ' . firm()->lastname,
                            'sid_user_relod' => firm()->id,
                            'amount' => DB::raw('amount+' . $convertion_receive_user),
                        ]);

                        // Valeurs grade name 
                        $nam_firm = $walletUser->company != null ? $walletUser->company->name : 'Robus';

                        if ($walletUser->country->code == 'CI') {

                            $message = 'Vous avez reçu un transfert  de ' . $walletTransaction->amount .
                                $walletUser->wallet->currencie->code .
                                ' de ' . $nam_firm .
                                'par ' . $walletTransaction->provider->name .
                                '  Numero Transaction ' . $walletUser->phone .
                                ' a ' . $walletTransaction->created_at .
                                '  Robus vous remercie';

                            $encode = substr($wallet_user->phone, -10);

                            Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                        }

                        // Send valeur percent in Admin
                        if ($admin_civ->count() > 0) {

                            $count_admin = $admin_civ->count();

                            $valeur_single =  $convertion_receive_admin / $count_admin;


                            foreach ($admin_civ as $user) {
                                $admin_single = SidUser::find($user->id);
                                AdminsCom::create([
                                    'amount' =>   $valeur_single,
                                    'sid_user_id' =>   $admin_single->id,
                                    'wallet_transaction_id' => $walletTransaction->id,
                                    'wallet_bank_id' => null,
                                    'wallet_deposit_id' => null,
                                    'wallet_inter_id' => null,
                                    'wallet_payment_id' => null,
                                    'wallet_phone_credit_id' => null,
                                    'amount_with_draw' =>  0,
                                    'wallet_reload_id' => null,
                                    'bank_card_id' => null,
                                    'description' => 'Commission administrateur',
                                ]);
                                $admin_single->wallet->update([
                                    'reload_by' => firm()->firstname . ' ' . firm()->lastname,
                                    'sid_user_relod' => firm()->id,
                                    'amount' => DB::raw('amount+' . $valeur_single),
                                ]);
                            }
                        }

                        // Valuer in Ivoice transaction
                        $transaction = Transaction::create([
                            'wallet_transaction_id' => $walletTransaction->id,
                            'sid_user_id' => firm()->id,
                            'transaction_id' => 'RB_' . $walletTransaction->provider->name . '_' . $transactionCode . '_' . $wallet_user->firstname . $key,
                            'operator_transaction_name' => $walletTransaction->provider->name,
                            'trans_comp_id' => 'RB_' . $transactionCompiCode . '_' . $wallet_user->firstname . $key,
                            'pay_amount' => $valuer_receive,
                            'fee_amount' =>  $walletTransaction->costs_amount,
                            'received_amount' => $valuer_receive,
                            'status' => $walletTransaction->status,
                        ]);

                        InvoiceTransaction::create([
                            'title' => "Transfers d'argent",
                            'wallet_transaction_id' => $walletTransaction->id,
                            'transactions_id' => $transaction->id,
                            'company_id' => firm()->company->id,
                            'description' => $walletTransaction->description,
                            'status' => $walletTransaction->status,
                            'created_by' => firm()->id,
                        ]);



                        // Send Notification Real time Admin and User and send message everyone
                        $msg_title = 'Transactions';
                        $msg = 'Transfert validé ';
                        // Notify User Auth
                        $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                        // Notify no read
                        $notifs = $walletUser->unreadNotifications;
                        // Notify User Send
                        $wallet_user->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                        $notifs = $wallet_user->unreadNotifications;
                        // Create event Realt Time
                        event(new CreateWalletTransfersEvent($walletTransaction));
                        // Notify Admins of country
                        Notification::send($admins, new AdminNotification('Transfert de ' .
                            ' ' . $walletTransaction->wallet->sidUser->firstname));
                        if ($admins->count() > 0) {

                            foreach ($admins  as $user) {

                                $admin_single = SidUser::find($user->id);

                                $notifs = $admin_single->unreadNotifications;
                                event(new AdminNotifEvent($notifs));
                            }


                            //   $this->cancelEmployesField();
                            //   $this->emit('modal-hide');
                            //   $this->emit('success', 'Transfers effectué !');

                            if ($walletUser->email != null) {
                                $notify = new MailController();
                                $notify->newTransaction($walletTransaction);
                            }
                        } else {


                            //   $this->cancelEmployesField();
                            //   $this->emit('modal-hide');
                            //   $this->emit('success', 'Transfers effectué !');


                            if ($walletUser->email != null) {
                                $notify = new MailController();
                                $notify->newTransaction($walletTransaction);
                            }
                        }
                    }

                    $this->cancelEmployesField();
                    $this->emit('modal-hide');
                    $this->emit('success', 'Transfers effectué !');
                }
            } elseif ($this->choose_type_payement_emp == 2) {

                $this->validate([
                    'amount_payment_wallet' => 'required',
                    'wallet_id_employer' => 'required',
                ], [
                    'amount_payment_wallet.required' => 'Votre montant est obligatoire',
                    'wallet_id_employer.required' => 'le wallet est obligatoire',

                ]);

                $wallet_search  =  wallet::where('wallet_id', $this->wallet_id_employer)->first();

                if ($wallet_search == null) {

                    $this->cancelEmployesField();
                    $this->emit('modal-hide');
                    $this->emit('info', 'Ce numero n\'existe pas dans chez vous !');
                } elseif ($som_transfers >= $valeur_ceiling) {

                    $this->cancelEmployesField();
                    $this->emit('modal-hide');
                    $this->emit(
                        'warning',
                        'Votre montant journier est atteint !'
                    );
                } else {

                    // Emount + Percent comission
                    $val_amount =  $this->amount_payment_wallet;


                    // Verify compte in Use send
                    if ($val_amount > $walletUser->wallet->amount) {
                        $this->cancelEmployesField();
                        $this->emit('modal-hide-transfers');
                        $this->emit('success', 'Verifier votre montant ! !');
                    } else {

                        $amount_solde =  $walletUser->wallet->amount -  $val_amount;

                        $percent_robus = TransactionCost::where('is_sociaty', true)->first();


                        // $amount_solde = $check->wallet->amount +  $commission;
                        $walletAmountSend = Wallet::where('wallet_id',  $this->wallet_id_employer)->first();

                        $amount_solde_drawal = (float)$walletAmountSend->amount +  (float) $val_amount;

                        $amount_drawal =      $amount_solde_drawal;


                        $walletTransaction = WalletTransaction::create(
                            [
                                'wallet_id' => $walletUser->wallet->id,
                                'amount' => $this->amount_payment_wallet,
                                'description' => $this->transaction_description_robus ?? null,
                                'transaction_cost_id' =>  $percent_robus->id,
                                'phone' => $this->transaction_phone ?? null,
                                'costs_amount' => $percent_robus->percent * $this->amount_payment_wallet,
                                'amount_received' => $this->amount_payment_wallet - ($percent_robus->percent * $this->amount_payment_wallet),
                                'num_wallet' => $this->wallet_id_employer,
                                'created_by' => firm()->id,
                                'provider_id' => 9,
                                'choose_type_transaction' => $this->choose_type_transaction ?? null,
                                'choose_type_payement_emp' => $this->choose_type_payement_emp,
                                'type_transaction' => 'Depot',
                                'type_received' => 'Transfert',
                                'status' => 'SUCCESS',
                                'amount_solde'  => $amount_solde ?? null,
                                'amount_solde_drawal' => $amount_drawal ?? null
                            ]
                        );

                        //valeur comission send
                        $commission = $walletTransaction->amount;

                        // Valur off commission
                        $commission_valeur = $percent_robus->percent * $walletTransaction->amount;

                        // 50 %
                        $valuer_admin = $percent_robus->percent_admin * $commission_valeur;

                        // Convertion valeur CFA adim
                        $amount_cfa_admin =  $valuer_admin *  $walletUser->wallet->currencie->value;


                        $walletUser->wallet->update([
                            'reload_by' => firm()->firstname .
                                ' ' . firm()->lastname,
                            'sid_user_relod' => firm()->id,
                            'amount' => DB::raw('amount-' . $commission),
                        ]);

                        // Valeur received User
                        $valuer_receive = $walletTransaction->amount - ($percent_robus->percent * $this->amount_payment_wallet);


                        // wallet_valeur
                        // Send in compte User and name reload

                        $wallet_search->update([
                            'reload_by' => firm()->firstname . ' ' . firm()->lastname,
                            'sid_user_relod' => firm()->id,
                            'amount' => DB::raw('amount+' . $valuer_receive),
                        ]);

                        if ($walletUser->country->code == 'CI') {

                            $message = 'Vous avez recu un transfert  de ' . $walletTransaction->amount .
                                $walletUser->wallet->currencie->code .
                                // ' de ' . $walletUser->sidUser->country->name_ground != null ? $walletUser->sidUser->country->name_ground  : ' ' .  ' ' .
                                'par ' . $walletTransaction->provider->name .
                                'Numero Transaction ' . $walletUser->phone .
                                ' a ' . $walletTransaction->created_at .
                                'Robus vous remercie';

                            $encode = substr($wallet_search->sidUser->phone, -10);

                            Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                        }

                        // Send valeur percent in Admin
                        if ($admin_civ->count() > 0) {

                            $count_admin = $admin_civ->count();

                            $admin_valeur  =  $amount_cfa_admin;

                            $valeur_single =  $admin_valeur / $count_admin;


                            foreach ($admin_civ as $user) {
                                $admin_single = SidUser::find($user->id);
                                AdminsCom::create([
                                    'amount' =>   $valeur_single,
                                    'sid_user_id' =>   $admin_single->id,
                                    'wallet_transaction_id' => $walletTransaction->id,
                                    'wallet_bank_id' => null,
                                    'wallet_deposit_id' => null,
                                    'wallet_inter_id' => null,
                                    'wallet_payment_id' => null,
                                    'wallet_phone_credit_id' => null,
                                    'amount_with_draw' =>  0,
                                    'wallet_reload_id' => null,
                                    'bank_card_id' => null,
                                    'description' => 'Commission administrateur',
                                ]);
                                $admin_single->wallet->update([
                                    'reload_by' => firm()->firstname . ' ' . firm()->lastname,
                                    'sid_user_relod' => firm()->id,
                                    'amount' => DB::raw('amount+' . $valeur_single),
                                ]);
                            }
                        }

                        // Valuer in Ivoice transaction
                        $transaction = Transaction::create([
                            'wallet_transaction_id' => $walletTransaction->id,
                            'sid_user_id' => firm()->id,
                            'transaction_id' => 'RB_' . $walletTransaction->provider->name . '_' . $transactionCode,
                            'operator_transaction_name' => $walletTransaction->provider->name,
                            'trans_comp_id' => 'RB_' . $transactionCompiCode,
                            'pay_amount' => $walletTransaction->amount,
                            'fee_amount' =>  $walletTransaction->costs_amount,
                            'received_amount' => $walletTransaction->amount,
                            'status' => $walletTransaction->status,
                        ]);

                        InvoiceTransaction::create([
                            'title' => "Transfers d'argent",
                            'wallet_transaction_id' => $walletTransaction->id,
                            'transactions_id' => $transaction->id,
                            'company_id' => firm()->company->id,
                            'description' => $walletTransaction->description,
                            'status' => $walletTransaction->status,
                            'created_by' => firm()->id,
                        ]);


                        // Send Notification Real time Admin and User and send message everyone
                        $msg_title = 'Transactions';
                        $msg = 'Demande de transfert est en cours de validation ';
                        // Notify User Auth
                        $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                        // Notify no read
                        $notifs = $walletUser->unreadNotifications;
                        // Notify User Send
                        $wallet_search->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                        $notifs = $wallet_search->sidUser->unreadNotifications;
                        // Create event Realt Time
                        event(new CreateWalletTransfersEvent($walletTransaction));
                        // Notify Admins of country
                        Notification::send($admins, new AdminNotification('Demande de transfert de ' .
                            ' ' . $walletTransaction->wallet->sidUser->firstname));
                        if ($admins->count() > 0) {

                            foreach ($admins  as $user) {

                                $admin_single = SidUser::find($user->id);

                                $notifs = $admin_single->unreadNotifications;
                                event(new AdminNotifEvent($notifs));
                            }


                            $this->cancelEmployesField();
                            $this->emit('modal-hide');
                            $this->emit('success', 'Transfers effectué !');

                            if ($walletUser->email != null) {
                                $notify = new MailController();
                                $notify->newTransaction($walletTransaction);
                            }
                        } else {


                            $this->cancelEmployesField();
                            $this->emit('modal-hide');
                            $this->emit('success', 'Transfers effectué !');


                            if ($walletUser->email != null) {
                                $notify = new MailController();
                                $notify->newTransaction($walletTransaction);
                            }
                        }
                    }
                }
            } else {
                $this->cancelEmployesField();
                $this->emit('modal-hide');
                $this->emit('info', 'Ce Provider n\'existe pas dans chez vous !');
            }
        } else {
            $this->cancelEmployesField();
            $this->emit('modal-hide');
            $this->emit('info', 'Ce Provider n\'existe pas dans chez vous !');
        }
    }

    private function convertAdminCommissionReceived($amount)
    {
        $country_civ = Country::find(107);

        $robus_currency = sabdy()->country->currencie->value;

        if ($country_civ->currencie->value > sabdy()->country->currencie->value) {
            return $amount * $robus_currency;
        } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {
            return $amount;
        } else {
            return $amount * $robus_currency;
        }
    }

    private function convertAdminCommission($amount, $wallet)
    {
        $country_civ = Country::find(107);

        $robus_currency = sabdy()->country->currencie->value;

        if ($country_civ->currencie->value >  $wallet->country->currencie->value) {
            return $amount / $wallet->country->currencie->value;
        } elseif ($country_civ->currencie->value ==  $wallet->country->currencie->value) {
            return $amount;
        } else {
            return $amount / $wallet->country->currencie->value;
        }
    }

    public function getTransactionPenddingUserProperty()
    {
        $transactionPendding = WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                firm()->country->id
            )
            ->select('wallet_transactions.*')
            ->where(
                'wallet_transactions.created_by',
                firm()->id
            )
            ->where('wallet_transactions.status', 'PENDING')
            ->whereDate('wallet_transactions.created_at', Carbon::now())
            ->count();
        return $transactionPendding;
    }
    public function getTransactionErrorUserProperty()
    {
        $transactionError = WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                firm()->country->id
            )
            ->select('wallet_transactions.*')
            ->where(
                'wallet_transactions.created_by',
                firm()->id
            )
            ->where('wallet_transactions.status', 'ERROR')
            ->whereDate('wallet_transactions.created_at', Carbon::now())
            ->count();
        return $transactionError;
    }

    public function getWalletTransactionSumUserProperty()
    {
        $data = [];

        foreach (WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                firm()->country->id
            )
            ->select('wallet_transactions.*')
            ->where(
                'wallet_transactions.created_by',
                firm()->id
            )
            ->whereDate('wallet_transactions.created_at', Carbon::now())
            ->where('wallet_transactions.status', 'SUCCESS')
            ->get()
            as $transaction) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }

    public function getTransactionSuccessUserProperty()
    {
        $transactionSuccess = WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                firm()->country->id
            )
            ->select('wallet_transactions.*')
            ->where(
                'wallet_transactions.created_by',
                firm()->id
            )
            ->where('wallet_transactions.status', 'SUCCESS')
            ->whereDate('wallet_transactions.created_at', Carbon::now())
            ->count();
        return $transactionSuccess;
    }

    public function getWalletTransactionMonthUserProperty()
    {
        $data = [];

        $result = WalletTransaction::join(
            'wallets',
            'wallets.id',
            'wallet_transactions.wallet_id'
        )
            ->where(
                'wallets.country_id',
                firm()->country->id
            )
            ->where(
                'wallet_transactions.created_by',
                firm()->id
            )
            ->whereDate(
                'wallet_transactions.created_at',
                '>=',
                Carbon::now()->subDays(30)
            )
            ->select(
                DB::raw(
                    'sum(wallet_transactions.amount) as cumul_amount_chiffre'
                )
            )
            ->groupBy('wallet_transactions.created_at')
            ->get();

        foreach ($result as $sumTrans) {
            $data[] = $sumTrans->cumul_amount_chiffre;
        }

        return array_sum($data);
    }
    // Admin valeur function
    public function getAdminValeurProperty()
    {

        $admins = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', firm()->country->id)
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
    public function edit($id, $state)
    {
        $check = Wallet::whereId($id)->first();
        $this->user_id = $check->sidUser->id;
        $this->wallet_id_add = $check->wallet_id;
        $this->employerDesabled = $check->company_id;
        $this->categorieId_add = $check->sidUser->categorie_id;
        $this->emit($state);
    }

    public function update()
    {

        $this->validate([
            'wallet_id_add' => 'required',
            'categorieId_add' => 'required',
        ], [
            'wallet_id_add.required' => 'Votre wallet est obligatoire',
            'categorieId_add.required' => 'la categorie est obligatoire',

        ]);
        $check =  Wallet::where('wallet_id', $this->wallet_id_add)->first();

        $check->sidUser->update([
            'categorie_id'  => $this->categorieId_add,
        ]);

        $this->cancelEmployesField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Categorie modifié !');
    }

    public function disable()
    {
        $check = SidUser::find($this->user_id);
        if (!empty($check)) {
            if ($check->company_id == firm()->id) $check->update(['company_id' => 1, 'categorie_id' => null]);
            else $check->update(['company_id' => firm()->id, 'categorie_id' => null]);
            $this->emit('modal-hide', 'hide');
            $this->cancelEmployesField();
            $this->emit('success', $check->company_id == firm()->id ? 'Client activée !' : 'Client désactivée !');
        }
    }

    public function cancelEmployesField()
    {

        $this->wallet_id_add = '';
        $this->amount_payment = '';
        $this->amount_payment_wallet = '';
        $this->wallet_id_employer = '';
        $this->categorieId_add  = null;
        $this->choose_type_payement_emp = null;
        $this->transaction_description_robus = '';
        $this->emit('modal-hide', 'hide');
    }
    public function render()
    {
        $users = SidUser::query();


        $account_employers = null;
        $wallet_info_firstname = null;
        $wallet_info_lastname = null;
        $wallet_payment_firstname = null;
        $wallet_payment_lastname = null;
        $amount_categories = null;
        $commission_transaction_val_nat_pay = null;
        $commission_transaction_val_nat_receive = null;

        $commission_transaction_val_each_pay = null;
        $commission_transaction_val_each_receive = null;
        $commission_transaction_val_each_total_receive = null;

        $walletUser = firm();

        $default_valeur = 2000000;

        $wallets = Wallet::join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_custumers', true)
            // ->orWhere('sid_users.is_under_distributer', true)
            ->where('wallets.status', true)
            ->Where('wallets.country_id', firm()->country->id)
            ->get();

        $wallet_payments = Wallet::join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.company_id', firm()->id)
            // ->orWhere('sid_users.is_under_distributer', true)
            ->where('wallets.status', true)
            ->Where('wallets.country_id', firm()->country->id)
            ->get();


        if ($this->search != "") {

            $this->resetPage();

            $users->where('is_company', firm()->id)->where("firstname", "LIKE",  "%" . $this->search . "%")
                ->orWhere("lastname", "LIKE",  "%" . $this->search . "%")
                ->orWhere("email", "LIKE",  "%" . $this->search . "%")
                ->orWhere("phone", "LIKE",  "%" . $this->search . "%");
        }



        if ($this->wallet_id != '') {


            foreach ($wallets  as $wallet_user) {

                $wallet_search_user = $wallet_user->where('wallet_id', $this->wallet_id)->first();

                if ($wallet_search_user == null) {

                    $wallet_info_firstname = "N'existe pas chez nous";
                } else {

                    $wallet_info_firstname = $wallet_search_user->sidUser->firstname ?? null;

                    $wallet_info_lastname = $wallet_search_user->sidUser->lastname ?? null;
                }
            }
        }


        if ($this->choose_type_payement_emp == 1) {
            $this->choose_type_payement_emp == 1;

            // $this->amount_payment = '';

        } elseif ($this->choose_type_payement_emp == 2) {

            $this->choose_type_payement_emp == 2;

            // $this->amount_payment_wallet = '';
        }
        if ($this->wallet_id_employer != '') {


            foreach ($wallet_payments  as $wallet_user) {

                $wallet_search_user = $wallet_user->where('wallet_id', $this->wallet_id_employer)->first();



                if ($wallet_search_user == null) {

                    $wallet_payment_firstname = "N'existe pas chez nous";
                } else {

                    $wallet_payment_firstname = $wallet_search_user->sidUser->firstname ?? null;

                    $wallet_payment_lastname = $wallet_search_user->sidUser->lastname ?? null;
                }
            }
        }


        // Nationnal commission transaction
        if ($this->amount_payment_wallet != '') {



            if (
                $walletUser->wallet->currencie->code == 'EUR' ||
                $walletUser->wallet->currencie->code == 'DH' ||
                $walletUser->wallet->currencie->code == 'Dt' ||
                $walletUser->wallet->currencie->code == 'CEDI' ||
                $walletUser->wallet->currencie->code == 'USD'
            ) {

                //  Conversion valeur
                $conversion_valeur_end  =  $default_valeur  /  $walletUser->wallet->currencie->value;

                if ($conversion_valeur_end  < $this->amount_payment_wallet) {

                    $commission_transaction_val_nat_pay = "Montant maxi est  : " .  number_format($conversion_valeur_end, 2, ',', ' ');

                    $commission_transaction_val_nat_receive = "Montant maxi est  : " .  number_format($conversion_valeur_end, 2, ',', ' ');
                } else {

                    if ($walletUser->country->code == 'CF') {



                        $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                        $commission_transaction_val_nat_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment_wallet;

                        $commission_transaction_val_nat_receive =  (float) $this->amount_payment_wallet - $commission_transaction_val_nat_pay;
                    } elseif ($walletUser->country->code == 'GH') {


                        $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                        $commission_transaction_val_nat_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment_wallet;

                        $commission_transaction_val_nat_receive =  (float) $this->amount_payment_wallet - $commission_transaction_val_nat_pay;
                    } elseif ($walletUser->country->code == 'MA') {


                        $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                        $commission_transaction_val_nat_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment_wallet;

                        $commission_transaction_val_nat_receive =  (float) $this->amount_payment_wallet - $commission_transaction_val_nat_pay;
                    } elseif ($walletUser->country->code == 'TN') {


                        $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                        $commission_transaction_val_nat_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment_wallet;

                        $commission_transaction_val_nat_receive =  (float) $this->amount_payment_wallet - $commission_transaction_val_nat_pay;
                    } elseif ($walletUser->country->code == 'FR') {


                        $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                        $commission_transaction_val_nat_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment_wallet;

                        $commission_transaction_val_nat_receive =  (float) $this->amount_payment_wallet - $commission_transaction_val_nat_pay;
                    }
                }
            } elseif ($walletUser->wallet->currencie->code == 'GNF') {
                if ($walletUser->country->code == 'CF') {


                    $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                    $commission_transaction_val_nat_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment_wallet;

                    $commission_transaction_val_nat_receive =  (float) $this->amount_payment_wallet - $commission_transaction_val_nat_pay;
                } elseif ($walletUser->country->code == 'GH') {


                    $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                    $commission_transaction_val_nat_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment_wallet;

                    $commission_transaction_val_nat_receive =  (float) $this->amount_payment_wallet - $commission_transaction_val_nat_pay;
                } elseif ($walletUser->country->code == 'MA') {


                    $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                    $commission_transaction_val_nat_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment_wallet;

                    $commission_transaction_val_nat_receive =  (float) $this->amount_payment_wallet - $commission_transaction_val_nat_pay;
                } elseif ($walletUser->country->code == 'TN') {

                    $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                    $commission_transaction_val_nat_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment_wallet;

                    $commission_transaction_val_nat_receive =  (float) $this->amount_payment_wallet - $commission_transaction_val_nat_pay;
                } elseif ($walletUser->country->code == 'FR') {


                    $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                    $commission_transaction_val_nat_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment_wallet;

                    $commission_transaction_val_nat_receive =  (float) $this->amount_payment_wallet - $commission_transaction_val_nat_pay;
                }
            } elseif ($walletUser->wallet->currencie->code == 'CFA') {

                if ($walletUser->country->code == 'CF') {



                    $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                    $commission_transaction_val_nat_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment_wallet;

                    $commission_transaction_val_nat_receive =  (float) $this->amount_payment_wallet - $commission_transaction_val_nat_pay;
                } elseif ($walletUser->country->code == 'CI') {


                    $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                    $commission_transaction_val_nat_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment_wallet;

                    $commission_transaction_val_nat_receive =  (float) $this->amount_payment_wallet - $commission_transaction_val_nat_pay;
                }
            }
        }

        if ($this->categorieId != '') {

            $account_employers =  SidUser::where('company_id', firm()->id)->where('categorie_id', $this->categorieId)->count();

            if ($this->amount_payment != '') {



                if (
                    $walletUser->wallet->currencie->code == 'EUR' ||
                    $walletUser->wallet->currencie->code == 'DH' ||
                    $walletUser->wallet->currencie->code == 'Dt' ||
                    $walletUser->wallet->currencie->code == 'CEDI' ||
                    $walletUser->wallet->currencie->code == 'USD'
                ) {

                    //  Conversion valeur
                    $conversion_valeur_end  =  $default_valeur  /  $walletUser->wallet->currencie->value;

                    if ($conversion_valeur_end  < $this->amount_payment) {

                        $commission_transaction_val_each_pay = "Montant maxi est  : " .  number_format($conversion_valeur_end, 2, ',', ' ');

                        $commission_transaction_val_each_receive = "Montant maxi est  : " .  number_format($conversion_valeur_end, 2, ',', ' ');
                    } else {

                        if ($walletUser->country->code == 'CF') {



                            $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                            $commission_transaction_val_each_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment;

                            $commission_transaction_val_each_receive =  (float) $this->amount_payment - $commission_transaction_val_each_pay;

                            if ($account_employers != 0) {

                                $commission_transaction_val_each_total_receive =   $commission_transaction_val_each_receive;
                            } else {

                                $commission_transaction_val_each_total_receive =  0;
                            }
                        } elseif ($walletUser->country->code == 'GH') {


                            $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                            $commission_transaction_val_each_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment;

                            $commission_transaction_val_each_receive =  (float) $this->amount_payment - $commission_transaction_val_each_pay;
                            if ($account_employers != 0) {

                                $commission_transaction_val_each_total_receive =   $commission_transaction_val_each_receive;
                            } else {

                                $commission_transaction_val_each_total_receive =  0;
                            }
                        } elseif ($walletUser->country->code == 'MA') {


                            $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                            $commission_transaction_val_each_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment;

                            $commission_transaction_val_each_receive =  (float) $this->amount_payment - $commission_transaction_val_each_pay;
                            if ($account_employers != 0) {

                                $commission_transaction_val_each_total_receive =   $commission_transaction_val_each_receive;
                            } else {

                                $commission_transaction_val_each_total_receive =  0;
                            }
                        } elseif ($walletUser->country->code == 'TN') {


                            $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                            $commission_transaction_val_each_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment;

                            $commission_transaction_val_each_receive =  (float) $this->amount_payment - $commission_transaction_val_each_pay;
                            if ($account_employers != 0) {

                                $commission_transaction_val_each_total_receive =   $commission_transaction_val_each_receive;
                            } else {

                                $commission_transaction_val_each_total_receive =  0;
                            }
                        } elseif ($walletUser->country->code == 'FR') {


                            $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                            $commission_transaction_val_each_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment;

                            $commission_transaction_val_each_receive =  (float) $this->amount_payment - $commission_transaction_val_each_pay;
                            if ($account_employers != 0) {

                                $commission_transaction_val_each_total_receive =   $commission_transaction_val_each_receive;
                            } else {

                                $commission_transaction_val_each_total_receive =  0;
                            }
                        }
                    }
                } elseif ($walletUser->wallet->currencie->code == 'GNF') {

                    if ($walletUser->country->code == 'CF') {


                        $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                        $commission_transaction_val_each_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment;

                        $commission_transaction_val_each_receive =  (float) $this->amount_payment - $commission_transaction_val_each_pay;
                        if ($account_employers != 0) {

                            $commission_transaction_val_each_total_receive =   $commission_transaction_val_each_receive;
                        } else {

                            $commission_transaction_val_each_total_receive =  0;
                        }
                    } elseif ($walletUser->country->code == 'GH') {


                        $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                        $commission_transaction_val_each_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment;

                        $commission_transaction_val_each_receive =  (float) $this->amount_payment - $commission_transaction_val_each_pay;

                        if ($account_employers != 0) {

                            $commission_transaction_val_each_total_receive =   $commission_transaction_val_each_receive;
                        } else {

                            $commission_transaction_val_each_total_receive =  0;
                        }
                    } elseif ($walletUser->country->code == 'MA') {


                        $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                        $commission_transaction_val_each_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment;

                        $commission_transaction_val_each_receive =  (float) $this->amount_payment - $commission_transaction_val_each_pay;

                        if ($account_employers != 0) {

                            $commission_transaction_val_each_total_receive =   $commission_transaction_val_each_receive;
                        } else {

                            $commission_transaction_val_each_total_receive =  0;
                        }
                    } elseif ($walletUser->country->code == 'TN') {

                        $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                        $commission_transaction_val_each_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment;

                        $commission_transaction_val_each_receive =  (float) $this->amount_payment - $commission_transaction_val_each_pay;

                        if ($account_employers != 0) {

                            $commission_transaction_val_each_total_receive =   $commission_transaction_val_each_receive;
                        } else {

                            $commission_transaction_val_each_total_receive =  0;
                        }
                    } elseif ($walletUser->country->code == 'FR') {


                        $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                        $commission_transaction_val_each_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment;

                        $commission_transaction_val_each_receive =  (float) $this->amount_payment - $commission_transaction_val_each_pay;

                        if ($account_employers != 0) {

                            $commission_transaction_val_each_total_receive =   $commission_transaction_val_each_receive;
                        } else {

                            $commission_transaction_val_each_total_receive =  0;
                        }
                    }
                } elseif ($walletUser->wallet->currencie->code == 'CFA') {

                    if ($walletUser->country->code == 'CF') {


                        $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                        $commission_transaction_val_each_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment;

                        $commission_transaction_val_each_receive =  (float) $this->amount_payment - $commission_transaction_val_each_pay;

                        if ($account_employers != 0) {

                            $commission_transaction_val_each_total_receive =   $commission_transaction_val_each_receive;
                        } else {

                            $commission_transaction_val_each_total_receive =  0;
                        }
                    } elseif ($walletUser->country->code == 'CI') {


                        $commission_transaction_cust = TransactionCost::where('is_sociaty', true)->first();

                        $commission_transaction_val_each_pay =  (float) $commission_transaction_cust->percent *  $this->amount_payment;

                        $commission_transaction_val_each_receive =  (float) $this->amount_payment - $commission_transaction_val_each_pay;

                        if ($account_employers != 0) {

                            $commission_transaction_val_each_total_receive =   $commission_transaction_val_each_receive;
                        } else {

                            $commission_transaction_val_each_total_receive =  0;
                        }
                    }
                }
            }



            $amount_categories = (float)$account_employers  * (float) $this->amount_payment;
        }


        return view('livewire.firms.employers', [
            "users" => $users->where('company_id', firm()->id)->orderBy('created_at', 'desc')->latest()->paginate(30),
            "countries" => Country::where('status', true)->orderBy("name", 'desc')->get(),
            "categories" => Categorie::where('sid_user_id', firm()->id)->orderBy("name", 'desc')->get(),
            "wallet_amount" => Wallet::where('sid_user_id', firm()->id)->first(),
            "account_valeurs" => $account_employers,
            "amount_valeurs" => $amount_categories,
            'amount_transaction_under_pay' => $commission_transaction_val_nat_pay,
            'amount_transaction_earch_pay' => $commission_transaction_val_each_pay,
            'amount_transaction_under_receive' => $commission_transaction_val_nat_receive,
            'amount_transaction_each_receive' => $commission_transaction_val_each_total_receive,
            'wallet_info_searchs' => $wallet_info_firstname . '  ' . $wallet_info_lastname,
            'wallet_info_search_payment' => $wallet_payment_firstname . '  ' . $wallet_payment_lastname,
            'sum_error' =>  $this->TransactionErrorUser,
            'sum_success' =>  $this->TransactionSuccessUser,
            'pending_success' => $this->TransactionPenddingUser,
            'sum_amount' => $this->WalletTransactionSumUser,
            'usersCountry' => $walletUser->country->currencie->name,
            'sum_transaction' => $this->WalletTransactionSumUser,
            'sum_transaction_month' => $this->WalletTransactionMonthUser,
        ]);
    }
}
