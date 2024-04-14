<?php

namespace App\Http\Livewire;


use App\Events\AdminNotifEvent;
use App\Events\Visitors\ChooseCardVisEvent;
use App\Events\Visitors\ChooseInterVisEvent;
use App\Events\Visitors\ChoosePhoneVisEvent;
use App\Events\Visitors\ChooseTransactionVisEvent;
use App\Events\Visitors\ChooseXbetVisEvent;
use App\Http\Controllers\Admin\MailController;
use App\Models\Costs\PhoneCreditCost;
use App\Models\Invoices\InvoiceDeposit;
use App\Models\Invoices\InvoiceInter;
use App\Models\Invoices\InvoicePhoneCredit;
use App\Models\Invoices\InvoiceTransaction;
use App\Models\Invoices\OneBetInvoice;
use App\Models\Payments\Deposit;
use App\Models\Payments\OneBetTransaction;
use App\Models\Payments\PaymentPhoneCredit;
use App\Models\Payments\Transaction;
use App\Models\Payments\TransactionInter;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use App\Models\Wallets\WalletXbet;
use App\Notifications\AdminNotification;
use App\Notifications\UserNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class TransferVisitors extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $searchTransaction = '';
    public $search = '';
    public $wallet;
    public $searchPaiment = '';
    public $seachXbet = '';
    public $searchUser = '';
    public $searchInter = '';
    public $searchReloadCard = '';
    public $searchPhoneCredit = '';
    public $statusFilterPaiement = '';
    public $statusFilterVirement = '';
    public $statusFilterInter = '';
    public $statusFilterReloadCard = '';
    public $statusFilterPhoneCredit = '';
    public $statusFilterTransaction = '';

    // Transactions
    public $transaction_description;
    public $transactionStatus;
    public $password_transaction;

    // Inters
    public $inter_description_robus;
    public $interStatus;
    public $phoneCreditId;
    public $reloadcardId;
    public $transactionId;
    public $xbet_id;
    public $interId;


    // Relaod cards
    public $reloadcard_description;
    public $reloadcardStatus;


    // Phone credit
    public $phoneCreditStatus;
    public $description_phone_credit;


    // Edit Xbet
    public $status_xbet_edit;
    public $xbetDescription;


    public $showNTransfersNotification = false;

    // Protected valeurs Services
    private $phoneCreditService1;

    protected $listeners = [
        // Choose Event Channel
        'echo:wallet-transfers-choose-vis-created,.Created_wallet_transfers_vis_choose' =>
        'notifyNewTransfers',
        'echo:wallet-card-choose-vis-created,.Created_wallet_card_vis_choose' =>
        'notifyNewCard',
        'echo:wallet-inter-choose-vis-created,.Created_wallet_inter_vis_choose' =>
        'notifyNewInter',
        'echo:wallet-phone-choose-vis-created,.Created_wallet_phone_vis_choose' =>
        'notifyNewPhone',
        'echo:wallet-xbet-choose-vis-created,.Created_wallet_xbet_vis_choose' =>
        'notifyNewXbet',
    ];
    public function notifyNewTransfers()
    {
        $this->showNTransfersNotification = true;
        // $this->emit('modal-hide-transfers');
        $this->emit('menu-transaction-active');
    }

    public function notifyNewInter()
    {
        $this->showNTransfersNotification = true;
        // $this->emit('modal-hide-inters');
        $this->emit('menu-inters-active');
    }

    public function notifyNewCard()
    {
        $this->showNTransfersNotification = true;
        // $this->emit('modal-hide-reloadCard');
        $this->emit('menu-reloadCarte-active');
    }

    public function notifyNewPhone()
    {
        $this->showNTransfersNotification = true;
        // $this->emit('modal-hide-phoneCredit');
        $this->emit('menu-phoneCredit-active');
    }

    public function notifyNewXbet()
    {
        $this->showNTransfersNotification = true;
        // $this->emit('modal-hide-phoneCredit');
        $this->emit('menu-xbet-active');
    }

    // Choose Transactions
    public function ChooseUpdatePhoneCredit($id)
    {
        $walletUpdate = WalletPhoneCredit::findOrFail($id);

        $sidAuth = sabdy();

        try {

            if ($walletUpdate->wallet->country->id == $sidAuth->country->id) {

                if (sabdy()->is_distributer == 1) {
                    $walletUpdate->update([
                        'updated_by' => sabdy()->id,
                    ]);
                    event(new ChoosePhoneVisEvent($walletUpdate));
                    // $this->emit('modal-hide-phoneCredit');
                    $this->emit('menu-phoneCredit-vis-active');
                    $this->emit('info', 'Vous avez choisi credit téléphonique');
                } else {

                    $walletUpdate->update([
                        'updated_by' => sabdy()->id,
                    ]);
                    event(new ChoosePhoneVisEvent($walletUpdate));
                    // $this->emit('modal-hide-phoneCredit');
                    $this->emit('menu-phoneCredit-vis-active');
                    $this->emit('info', 'Vous avez choisi credit téléphonique');
                }
            } else {
                $this->emit('menu-phoneCredit-vis-active');
                $this->emit(
                    'error',
                    'Vous ne pouvez pas valider cette transaction ... ! Merci bien !'
                );
            }
        } catch (\Exception $exception) {
            $this->emit('menu-phoneCredit-active');
            $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }
    public function ChooseUpdateReloadCard($id)
    {
        $walletUpdate = WalletDeposit::findOrFail($id);
        $sidAuth = sabdy();

        try {

            if ($walletUpdate->wallet->country->id == $sidAuth->country->id) {

                if (sabdy()->is_distributer == 1) {

                    $walletUpdate->update([
                        'updated_by' => sabdy()->id,
                    ]);
                    $this->emit('menu-reloadCarte-vis-active');
                    $this->emit('info', 'Vous avez choisi rechargement de carte');
                    event(new ChooseCardVisEvent($walletUpdate));
                } else {

                    $walletUpdate->update([
                        'updated_by' => sabdy()->id,
                    ]);
                    $this->emit('menu-reloadCarte-vis-active');
                    $this->emit('info', 'Vous avez choisi rechargement de carte');
                    event(new ChooseCardVisEvent($walletUpdate));
                }
            } else {
                $this->emit('menu-reloadCarte-vis-active');
                $this->emit(
                    'error',
                    'Vous ne pouvez pas valider cette transaction ... ! Merci bien !'
                );
            }
        } catch (\Exception $exception) {
            $this->emit('menu-reloadCarte-vis-active');
            $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }

    // Choose User adminto validated transactions
    public function ChooseUpdateTransaction($id)
    {
        $walletUpdate = WalletTransaction::findOrFail($id);
        $sidAuth = sabdy();
        try {

            if ($walletUpdate->wallet->country->id == $sidAuth->country->id) {

                if (sabdy()->is_distributer == 1) {

                    $walletUpdate->update([
                        'updated_by' => sabdy()->id,
                    ]);

                    event(new ChooseTransactionVisEvent($walletUpdate));
                    // $this->emit('modal-hide-transfers');
                    $this->emit('menu-transaction-vis-active');
                    $this->emit('info', 'Vous avez choisi transaction');
                } else {

                    $walletUpdate->update([
                        'updated_by' => sabdy()->id,
                    ]);

                    event(new ChooseTransactionVisEvent($walletUpdate));
                    // $this->emit('modal-hide-transfers');
                    $this->emit('menu-transaction-vis-active');
                    $this->emit('info', 'Vous avez choisi transaction');
                }
            } else {
                // $this->emit('modal-hide-transfers');
                $this->emit('menu-transaction-vis-active');
                $this->emit(
                    'error',
                    'Vous ne pouvez pas valider cette transaction ... ! Merci bien !'
                );
            }
        } catch (\Exception $exception) {
            $this->emit('menu-transaction-active');
            $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }


    // Choose 1xbets

    public function ChooseUpdateXbet($id)
    {
        $walletUpdate = WalletXbet::findOrFail($id);
        $sidAuth = sabdy();

        try {

            if ($walletUpdate->wallet->country->id == $sidAuth->country->id) {

                if (sabdy()->is_distributer == 1) {

                    $walletUpdate->update([
                        'updated_by' => sabdy()->id,
                    ]);
                    event(new ChooseXbetVisEvent($walletUpdate));
                    $this->emit('menu-xbet-vis-active');
                    $this->emit('info', 'Vous avez choisi Xbet');
                } else {

                    event(new ChooseXbetVisEvent($walletUpdate));
                    $walletUpdate->update([
                        'updated_by' => sabdy()->id,
                    ]);
                    // $this->emit('modal-hide-xbet');
                    $this->emit('menu-xbet-vis-active');
                    $this->emit('info', 'Vous avez choisi Xbet');
                }
            } else {
                //  $this->emit('modal-hide-xbet');
                $this->emit('menu-xbet-vis-active');
                $this->emit(
                    'error',
                    'Vous ne pouvez pas valider cette transaction ... ! Merci bien !'
                );
            }
        } catch (\Exception $exception) {
            $this->emit('menu-xbet-vis-active');
            $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }

    public function ChooseUpdateInter($id)
    {
        $walletUpdate = WalletInter::findOrFail($id);

        try {

            if (sabdy()->is_distributer == 1) {

                $walletUpdate->update([
                    'updated_by' => sabdy()->id,
                ]);
                event(new ChooseInterVisEvent($walletUpdate));
                // $this->emit('modal-hide-inters');
                $this->emit('menu-inters-vis-active');
                $this->emit('info', 'Vous avez choisi transfert international');
            } else {

                $walletUpdate->update([
                    'updated_by' => sabdy()->id,
                ]);
                event(new ChooseInterVisEvent($walletUpdate));
                // $this->emit('modal-hide-inters');
                $this->emit('menu-inters-vis-active');
                $this->emit('info', 'Vous avez choisi transfert international');
            }
        } catch (\Exception $exception) {
            $this->emit('menu-inters-vis-active');
            $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }

    // Update Phone
    public function updatePhoneCredit()
    {
        $check = WalletPhoneCredit::whereId($this->phoneCreditId)->first();
        $rt = rand(40, 5000) * time();
        $user_auth = sabdy();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $admins = $this->getAdminValeurProperty();
        $percent_robus = PhoneCreditCost::whereId(1)->first();
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

        try {
            if ($check) {

                $this->validate([
                    'phoneCreditStatus' => 'required',
                    'password_transaction' => 'required',
                ]);

                if (Hash::check($this->password_transaction, $user_auth->password)) {

                    $check->update([
                        'description' => $this->description_phone_credit ?? null,
                        'updated_by' => sabdy()->id,
                        'status' => $this->phoneCreditStatus,
                    ]);

                    $this->password_transaction = "";
                    $this->phoneCreditStatus = "";
                    $this->phoneCreditId = "";

                    if ($check->updated_by == sabdy()->id) {

                        if ($check->status == 'SUCCESS') {

                            if ($check->updated_by == sabdy()->id) {

                                $paymentPhoneCredit = PaymentPhoneCredit::create([

                                    'wallet_phone_credit_id' => $check->id,
                                    'sid_user_id' => sabdy()->id,
                                    'transaction_id' => 'ROBUS_' . $check->provider->name . '_' . $transactionCode,
                                    'operator_transaction_name' => $check->provider->name,
                                    'trans_comp_id' => 'ROBUS_' . $transactionCompiCode,
                                    'pay_amount' => $check->amount,
                                    'fee_amount' => $check->costs_amount ?? null,
                                    'received_amount' => $check->amount,
                                    'status' => $check->status,
                                ]);


                                InvoicePhoneCredit::create([
                                    'title' => 'Achat de credit telephonique',
                                    'wallet_phone_credit_id' => $check->id,
                                    'payment_credit_id' => $paymentPhoneCredit->id,
                                    'company_id' => sabdy()->company->id,
                                    'description' => $check->description ?? null,
                                    'status' => $check->status,
                                    'created_by' => sabdy()->id,
                                ]);

                                $commission_transaction = $check->amount;

                                if (sabdy()->is_distributer == 1) {

                                    // Send valeur historique distributer
                                    $check->adminCom->update([
                                        // 'amount' => $valeur_single,
                                        'amount_distributer' => $commission_transaction,
                                        'sid_user_id' => sabdy()->id,
                                        'description_distributer' => 'Validation distributeur',
                                    ]);
                                }

                                // Valeur amount commission distributeur
                                $valeur_amount_com = $check->adminCom->amount;

                                //  Send valeur in Admin
                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);
                                        $check->adminCom->update([
                                            'amount' =>   $valeur_amount_com,
                                            'sid_user_id' =>   $admin_single->id,
                                            'description' => 'Commission administrateur',
                                            'status'  => "SUCCESS",
                                            'is_web'  => true,
                                        ]);
                                    }
                                }

                                $message = 'Votre demande de credit de ' .
                                    $check->amount . ' FCFA ' .
                                    ' ' .
                                    'par ' .
                                    $check->provider->name .
                                    ' le ' .
                                    $check->updated_at .
                                    ' a été acceptée avec succès ' .
                                    'Robus vous remercie';

                                $encode = substr($check->phone_benef, -10);

                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");


                                Notification::send(
                                    $admins,
                                    new AdminNotification(
                                        'Demande de paiement de crédit validé'
                                    )
                                );
                                $this->resetPhoneCreditField();
                                $this->emit('modal-hide-phoneCredit-validated');
                                $this->emit('modal-hide-phoneCredit');
                                $this->emit('success', 'Achat de credit effectué !');
                                if ($admins->count() > 0) {

                                    foreach ($admins  as $user) {

                                        $admin_single = SidUser::find($user->id);

                                        $notifs = $admin_single->unreadNotifications;

                                        event(new AdminNotifEvent($notifs));
                                    }
                                }
                            } else {

                                $this->resetPhoneCreditField();
                                $this->emit('modal-hide-phoneCredit-validated');
                                $this->emit('modal-hide-phoneCredit');
                                $this->emit('error', 'Pas vous le valideur !!');
                            }
                        } elseif ($check->status == 'ERROR') {

                            if ($check->updated_by == sabdy()->id) {

                                PaymentPhoneCredit::create([
                                    'wallet_phone_credit_id' => $check->id,
                                    'sid_user_id' => sabdy()->id,
                                    'transaction_id' => 'ROBUS_' . $check->provider->name . '_' . $transactionCode,
                                    'operator_transaction_name' => $check->provider->name,
                                    'trans_comp_id' => 'ROBUS_' . $transactionCompiCode,
                                    'pay_amount' => 0.0,
                                    'fee_amount' => 0.0,
                                    'received_amount' => $check->amount + $check->costs_amount,
                                    'status' => $check->status,
                                ]);

                                $commission = $check->amount + $check->costs_amount;


                                // Update commission
                                $check->update([
                                    'commissions' => 0,
                                    'commissions_admin' => 0,
                                    'amount_solde' => $commission,
                                ]);

                                // Valeur amount commission distributeur
                                $valeur_amount_com = $check->adminCom->amount;

                                //  Send valeur in Admin
                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);
                                        $check->adminCom->update([
                                            'amount' =>   0,
                                            'sid_user_id' =>   $admin_single->id,
                                            'description' => 'Remboursement de la commission client',
                                            'status'  => "ERROR",
                                            'is_web'  => true,
                                        ]);
                                    }
                                }


                                $message =
                                    'Votre demande de crédit de ' .
                                    $check->amount .
                                    $check->wallet->currencie->code .
                                    ' ' .
                                    'par ' .
                                    $check->provider->name .
                                    ' le ' .
                                    $check->updated_at .
                                    ' a été refusé ' .
                                    'Robus vous remercie';


                                $encode = substr($check->phone_benef, -10);

                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");

                               

                                Notification::send(
                                    $admins,
                                    new AdminNotification('Demande de paiement de credit est refusée')
                                );

                                $this->resetPhoneCreditField();
                                $this->emit('modal-hide-phoneCredit-validated');
                                $this->emit('modal-hide-phoneCredit');
                                $this->emit('error', 'Achat de credit refusé !');

                                if ($admins->count() > 0) {

                                    foreach ($admins  as $user) {

                                        $admin_single = SidUser::find($user->id);

                                        $notifs = $admin_single->unreadNotifications;

                                        event(new AdminNotifEvent($notifs));
                                    }
                                } 

                            } else {
                                $this->resetPhoneCreditField();
                                $this->emit('modal-hide-phoneCredit-validated');
                                $this->emit('modal-hide-phoneCredit');
                                $this->emit('error', 'Pas de modification !');
                            }
                        } else {
                            $this->resetPhoneCreditField();
                            $this->emit('modal-hide-phoneCredit-validated');
                            $this->emit('modal-hide-phoneCredit');
                            $this->emit('error', 'Selectionnez le choix du traitement de la demande SVP !');
                        }
                    } else {
                        $this->resetPhoneCreditField();
                        $this->emit('modal-hide-phoneCredit');
                        $this->emit('modal-hide-phoneCredit-validated');
                        $this->emit('error', 'Pas le valideur !');
                    }
                } else {
                    $this->resetPhoneCreditField();
                    $this->emit('modal-hide-phoneCredit');
                    $this->emit('modal-hide-phoneCredit-validated');
                    $this->emit('error', 'Mot de passe Incorrect !');
                }
            } elseif ($this->password_transaction = "" ||  $this->phoneCreditStatus = "") {
                $this->resetPhoneCreditField();
                $this->emit('modal-hide-phoneCredit-validated');
                $this->emit('modal-hide-phoneCredit');
                $this->emit('info', 'Vous avez cliquez deux fois de suite !');
            } else {
                $this->resetPhoneCreditField();
                $this->emit('modal-hide-phoneCredit');
                $this->emit('modal-hide-phoneCredit-validated');
                $this->emit('info', 'Aucune donnée ou Vous avez cliquez deux fois de suite!');
            }
        } catch (\Exception $exception) {
            $this->resetPhoneCreditField();
            $this->emit('modal-hide-phoneCredit-validated');
            $this->emit('modal-hide-phoneCredit');
            $this->emit('error', 'Vous avez cliquez deux fois ou une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }


    public function updateReloadCard()
    {
        $check = WalletDeposit::whereId($this->reloadcardId)->first();
        $rt = rand(40, 5000) * time();
        $user_auth = sabdy();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $admins = $this->getAdminValeurProperty();
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();
        try {

            if ($check) {

                $this->validate([
                    'reloadcardStatus' => 'required',
                    'password_transaction' => 'required',
                ]);



                if (Hash::check($this->password_transaction, $user_auth->password)) {

                    $check->update([
                        'description' => $this->reloadcard_description_edit ?? null,
                        'updated_by' => sabdy()->id,
                        'status' => $this->reloadcardStatus,
                    ]);

                    $this->password_transaction = "";
                    $this->reloadcardStatus = "";
                    $this->reloadcardId = "";

                    if ($check->updated_by == sabdy()->id) {

                        if ($check->status == 'SUCCESS') {

                            $depositWallet = Deposit::create([

                                'wallet_deposit_id' => $check->id,
                                'sid_user_id' => sabdy()->id,
                                'transaction_id' => 'ROBUS_' . $check->provider->name . '_' . $transactionCode,
                                'operator_transaction_name' => $check->provider->name,
                                'trans_comp_id' => 'ROBUS_' . $transactionCompiCode,
                                'pay_amount' => $check->amount,
                                'fee_amount' =>  $check->costs_amount,
                                'received_amount' => $check->amount,
                                'status' => $check->status,
                            ]);

                            InvoiceDeposit::create([
                                'title' => 'Rechargement de carte',
                                'wallet_deposit_id' => $check->id,
                                'deposit_id' => $depositWallet->id,
                                'company_id' => sabdy()->company->id,
                                'description' => $check->description,
                                'status' => $check->status,
                                'created_by' => sabdy()->id,
                            ]);

                            // Validated account Update Admin
                            $commission_transaction =  $check->amount_received;


                            if (sabdy()->is_distributer == 1) {


                                sabdy()->wallet->update([
                                    'reload_by' => sabdy()->firstname .
                                        ' ' . sabdy()->lastname,
                                    'sid_user_relod' => sabdy()->id,
                                    'amount' => DB::raw('amount+' . $commission_transaction),
                                ]);

                                // Send valeur historique distributer
                                $valeur_decrement = $check->adminCom->update([
                                    // 'amount' => $valeur_single,
                                    'amount_distributer' => $commission_transaction,
                                    'sid_user_id' => sabdy()->id,
                                    'description_distributer' => 'Validation distributeur',
                                ]);
                            } else {

                                sabdy()->wallet->update([
                                    'reload_by' => sabdy()->firstname .
                                        ' ' . sabdy()->lastname,
                                    'sid_user_relod' => sabdy()->id,
                                    'amount' => DB::raw('amount+' . $commission_transaction),
                                ]);
                            }

                            $valeur_amount_com = $check->adminCom->amount;


                            if ($check->wallet->sidUser->is_under_distributer == true) {


                                if ($valeur_amount_com != null) {
                                    //  Send valeur in Admin
                                    if ($admin_civ->count() > 0) {

                                        $count_admin = $admin_civ->count();

                                        $valeur_single = $valeur_amount_com / $count_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            $check->adminCom->update([
                                                'amount' =>   $valeur_amount_com,
                                                'sid_user_id' =>   $admin_single->id,
                                                'description' => 'Paiement commission sous-distributer',
                                                'description_underdistributer' => 'Paiement commission sous-distributer',
                                                'status'  => "SUCCESS",
                                            ]);

                                            $admin_single->wallet->update([
                                                'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                                                'sid_user_relod' => sabdy()->id,
                                                'amount' => DB::raw('amount-' . $valeur_amount_com),
                                            ]);
                                        }
                                    }
                                } else {
                                    $this->resetReloadCardField();
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit('success', 'Rechargement Acceptée sans commission sous-distibuteur !');
                                }
                            } else {


                                //  Send valeur in Admin
                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    $valeur_single = $valeur_amount_com / $count_admin;

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);
                                        $check->adminCom->update([
                                            'amount' =>   $valeur_amount_com,
                                            'sid_user_id' =>   $admin_single->id,
                                            'description' => 'Commission administrateur',
                                            'status'  => "SUCCESS",
                                        ]);
                                        $admin_single->wallet->update([
                                            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                                            'sid_user_relod' => sabdy()->id,
                                            'amount' => DB::raw('amount+' . $valeur_single),
                                        ]);
                                    }
                                }
                            }

                            if ($check->wallet->sidUser->is_under_distributer == 1) {

                                $msg_title = 'Rechargement de carte';
                                $msg =
                                    'Votre demande de rechargement de carte a été validée';
                                $check->wallet->sidUser->notify(
                                    new UserNotification([
                                        'title' => $msg_title,
                                        'message' => $msg,
                                    ])
                                );
                                $notifs = $check->wallet->sidUser->unreadNotifications;
                                // event(new UpdateReloadCardEvent($check));
                                // event(new UserNotifEvent($notifs));
                                Notification::send(
                                    $admins,
                                    new AdminNotification(
                                        'Demande de commande de carte a été validée'
                                    )
                                );


                                $this->resetReloadCardField();
                                $this->emit('modal-hide-reloadCard');
                                $this->emit('success', 'Rechargement Acceptée !');

                                if ($admins->count() > 0) {

                                    foreach ($admins  as $user) {

                                        $admin_single = SidUser::find($user->id);

                                        $notifs = $admin_single->unreadNotifications;

                                        event(new AdminNotifEvent($notifs));
                                    }



                                    $notify = new MailController;
                                    $notify->newReload($check);
                                } else {


                                    $notify = new MailController;
                                    $notify->newReload($check);
                                }
                            } else {

                                if ($check->wallet->sidUser->country->code == 'CI') {

                                    $message =
                                        'Votre demande de rechargement de carte de ' .
                                        $check->amount .
                                        $check->wallet->currencie->code .
                                        ' ' .
                                        'par ' .
                                        $check->provider->name .
                                        ' le ' .
                                        $check->updated_at .
                                        ' a été acceptée avec succès ' .
                                        'Robus vous remercie';

                                    $encode = substr($check->wallet->sidUser->phone, -10);

                                    Http::get(
                                        "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                    );

                                    $msg_title = 'Rechargement de carte';
                                    $msg = 'Votre demande de rechargement de carte a été validée';
                                    $check->wallet->sidUser->notify(
                                        new UserNotification([
                                            'title' => $msg_title,
                                            'message' => $msg,
                                        ])
                                    );
                                    $notifs = $check->wallet->sidUser->unreadNotifications;
                                    // event(new UpdateReloadCardEvent($check));
                                    // event(new UserNotifEvent($notifs));
                                    Notification::send(
                                        $admins,
                                        new AdminNotification(
                                            'Demande de rechargement de carte a été validée'
                                        )
                                    );

                                    $this->resetReloadCardField();
                                    $this->emit('modal-hide-reloadCard-validated');
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit('success', 'Rechargement acceptée !');

                                    if ($admins->count() > 0) {

                                        foreach ($admins  as $user) {

                                            $admin_single = SidUser::find($user->id);

                                            $notifs = $admin_single->unreadNotifications;

                                            event(new AdminNotifEvent($notifs));
                                        }


                                        $notify = new MailController;
                                        $notify->newReload($check);
                                    } else {


                                        $notify = new MailController;
                                        $notify->newReload($check);
                                    }
                                } else {

                                    $msg_title = 'Rechargement de carte';
                                    $msg =
                                        'Votre demande de rechargement de carte a été validée';
                                    $check->wallet->sidUser->notify(
                                        new UserNotification([
                                            'title' => $msg_title,
                                            'message' => $msg,
                                        ])
                                    );
                                    $notifs = $check->wallet->sidUser->unreadNotifications;
                                    // event(new UpdateReloadCardEvent($check));
                                    // event(new UserNotifEvent($notifs));
                                    Notification::send(
                                        $admins,
                                        new AdminNotification(
                                            'Demande de commande de carte a été validée'
                                        )
                                    );


                                    $this->resetReloadCardField();
                                    $this->emit('modal-hide-reloadCard-validated');
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit('success', 'Rechargement acceptée !');

                                    if ($admins->count() > 0) {

                                        foreach ($admins  as $user) {

                                            $admin_single = SidUser::find($user->id);

                                            $notifs = $admin_single->unreadNotifications;

                                            event(new AdminNotifEvent($notifs));
                                        }



                                        $notify = new MailController;
                                        $notify->newReload($check);
                                    } else {


                                        $notify = new MailController;
                                        $notify->newReload($check);
                                    }
                                }
                            }
                        } elseif ($check->status == 'ERROR') {


                            if ($check->wallet->sidUser->is_under_distributer == 1) {


                                $commission =  $check->amount;

                                $check->wallet->update([
                                    'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                                    'sid_user_relod' => sabdy()->id,
                                    'amount' => DB::raw('amount+' . $commission),
                                ]);

                                // Update commission
                                $check->update([
                                    'commissions' => 0,
                                    'commissions_admin' => 0,
                                ]);

                                // Valeur amount commission distributeur
                                $valeur_amount_com = $check->adminCom->amount;

                                if ($valeur_amount_com != null) {
                                    //  Send valeur in Admin
                                    if ($admin_civ->count() > 0) {

                                        $count_admin = $admin_civ->count();

                                        $valeur_single = $valeur_amount_com / $count_admin;


                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            $valeur_decrement = $check->adminCom->update([
                                                'amount' =>   0,
                                                'sid_user_id' =>   $admin_single->id,
                                                'description' => 'Remboursement de la commission sous distributeur',
                                                'status'  => "ERROR",
                                            ]);
                                        }
                                    }
                                }

                                Deposit::create([
                                    'wallet_deposit_id' => $check->id,
                                    'sid_user_id' => sabdy()->id,
                                    'transaction_id' => 'ROBUS_' . $check->provider->name .
                                        '_' .
                                        $transactionCode,
                                    'operator_transaction_name' => $check->provider->name,
                                    'trans_comp_id' => 'ROBUS_' . $transactionCompiCode,
                                    'pay_amount' => 0.0,
                                    'fee_amount' => 0.0,
                                    'received_amount' => $check->amount + $check->costs_amount,
                                    'status' => $check->status,
                                ]);

                                $msg_title = 'Rechargement de carte';
                                $msg = 'Votre demande de commande de carte a été refusée';
                                $check->wallet->sidUser->notify(
                                    new UserNotification([
                                        'title' => $msg_title,
                                        'message' => $msg,
                                    ])
                                );
                                $notifs = $check->wallet->sidUser->unreadNotifications;
                                Notification::send(
                                    $admins,
                                    new AdminNotification(
                                        'Demande de commande de carte a été refusée'
                                    )
                                );

                                if ($admins->count() > 0) {

                                    foreach ($admins  as $user) {

                                        $admin_single = SidUser::find($user->id);

                                        $notifs = $admin_single->unreadNotifications;

                                        event(new AdminNotifEvent($notifs));
                                    }

                                    $this->resetReloadCardField();
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit('modal-hide-reloadCard-validated');
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit('error', 'Rechargement refusé !');
                                    $notify = new MailController;
                                    $notify->newReload($check);
                                } else {


                                    $notify = new MailController;
                                    $notify->newReload($check);
                                }
                            } else {

                                $commission =  $check->amount + $check->costs_amount;

                                $check->wallet->update([
                                    'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                                    'sid_user_relod' => sabdy()->id,
                                    'amount' => DB::raw('amount+' . $commission),
                                ]);

                                // Update commission
                                $check->update([
                                    'commissions' => 0,
                                    'commissions_admin' => 0,
                                ]);

                                // Valeur amount commission distributeur
                                $valeur_amount_com = $check->adminCom->amount;

                                if ($valeur_amount_com != null) {
                                    //  Send valeur in Admin
                                    if ($admin_civ->count() > 0) {

                                        $count_admin = $admin_civ->count();

                                        $valeur_single = $valeur_amount_com / $count_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            $valeur_decrement = $check->adminCom->update([
                                                'amount' =>   0,
                                                'sid_user_id' =>   $admin_single->id,
                                                'status'  => "ERROR",
                                                'description' => 'Remboursement de la commission client',
                                            ]);
                                        }
                                    }
                                }

                                Deposit::create([
                                    'wallet_deposit_id' => $check->id,
                                    'sid_user_id' => sabdy()->id,
                                    'transaction_id' => 'ROBUS_' . $check->provider->name .
                                        '_' .
                                        $transactionCode,
                                    'operator_transaction_name' => $check->provider->name,
                                    'trans_comp_id' => 'ROBUS_' . $transactionCompiCode,
                                    'pay_amount' => 0.0,
                                    'fee_amount' => 0.0,
                                    'received_amount' => $check->amount + $check->costs_amount,
                                    'status' => $check->status,
                                ]);

                                if ($check->wallet->sidUser->country->code == 'CI') {

                                    $message =
                                        'Votre demande de rechargement de carte de ' .
                                        $check->amount .
                                        $check->wallet->currencie->code .
                                        ' ' .
                                        'par ' .
                                        $check->provider->name .
                                        ' le ' .
                                        $check->updated_at .
                                        ' a été refusée ' .
                                        'Robus vous remercie';

                                    $encode = substr($check->wallet->sidUser->phone, -10);

                                    Http::get(
                                        "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                    );

                                    $msg_title = 'Rechargement de carte';
                                    $msg =
                                        'Votre demande de commande de carte a été refusé';
                                    $check->wallet->sidUser->notify(
                                        new UserNotification([
                                            'title' => $msg_title,
                                            'message' => $msg,
                                        ])
                                    );
                                    $notifs = $check->wallet->sidUser->unreadNotifications;
                                    Notification::send(
                                        $admins,
                                        new AdminNotification(
                                            'Demande de commande de carte a été refusé'
                                        )
                                    );

                                    $this->resetReloadCardField();
                                    $this->emit('modal-hide-reloadCard-validated');
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit('error', 'Rechargement refusé !');

                                    if ($admins->count() > 0) {

                                        foreach ($admins  as $user) {

                                            $admin_single = SidUser::find($user->id);

                                            $notifs = $admin_single->unreadNotifications;

                                            event(new AdminNotifEvent($notifs));
                                        }


                                        $notify = new MailController;
                                        $notify->newReload($check);
                                    } else {


                                        $notify = new MailController;
                                        $notify->newReload($check);
                                    }
                                } else {

                                    $msg_title = 'Rechargement de carte';
                                    $msg = 'Votre demande de commande de carte a été refusée';
                                    $check->wallet->sidUser->notify(
                                        new UserNotification([
                                            'title' => $msg_title,
                                            'message' => $msg,
                                        ])
                                    );
                                    $notifs = $check->wallet->sidUser->unreadNotifications;
                                    Notification::send(
                                        $admins,
                                        new AdminNotification(
                                            'Demande de commande de carte a été refusée'
                                        )
                                    );

                                    $this->resetReloadCardField();
                                    $this->emit('modal-hide-reloadCard-validated');
                                    $this->emit('modal-hide-reloadCard');
                                    $this->emit('error', 'Rechargement refusé !');

                                    if ($admins->count() > 0) {

                                        foreach ($admins  as $user) {

                                            $admin_single = SidUser::find($user->id);

                                            $notifs = $admin_single->unreadNotifications;

                                            event(new AdminNotifEvent($notifs));
                                        }


                                        $notify = new MailController;
                                        $notify->newReload($check);
                                    } else {


                                        $notify = new MailController;
                                        $notify->newReload($check);
                                    }
                                }
                            }
                        } else {
                            $this->resetReloadCardField();
                            $this->emit('modal-hide-reloadCard-validated');
                            $this->emit('modal-hide-reloadCard');
                            $this->emit('error', 'Selectionnez le choix de la demande SVP !');
                        }
                    } else {
                        $this->resetReloadCardField();
                        $this->emit('modal-hide-reloadCard-validated');
                        $this->emit('modal-hide-reloadCard');
                        $this->emit('error', 'Pas le valideur !');
                    }
                } else {
                    $this->resetReloadCardField();
                    $this->emit('modal-hide-reloadCard-validated');
                    $this->emit('modal-hide-reloadCard');
                    $this->emit('info', 'Vérifier votre mot de passe !');
                }
            } elseif ($this->password_transaction = "") {
                $this->resetReloadCardField();
                $this->emit('modal-hide-reloadCard-validated');
                $this->emit('modal-hide-reloadCard');
                $this->emit('info', 'Vous avez cliquez deux fois de suite le boutton !!');
            } else {
                $this->resetReloadCardField();
                $this->emit('modal-hide-reloadCard-validated');
                $this->emit('modal-hide-reloadCard');
                $this->emit('error', 'Aucune donnée ou Vous avez cliquez deux fois de suite !');
            }
        } catch (\Exception $exception) {
            $this->resetReloadCardField();
            $this->emit('modal-hide-reloadCard-validated');
            $this->emit('modal-hide-reloadCard');
            $this->emit('error', 'Vous avez cliquez deux fois Ou une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }


    public function updateTransaction()
    {

        $check = WalletTransaction::whereId($this->transactionId)->first();
        $user_auth = sabdy();
        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $admins = $this->getAdminValeurProperty();
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

        try {


            if ($check) {

                $this->validate([
                    'transactionStatus' => 'required',
                    'password_transaction' => 'required',
                ]);

                if (Hash::check($this->password_transaction, $user_auth->password)) {


                    $commission_transaction = $check->amount_received;

                    $check->update([
                        'description' => $this->transaction_description_edit ?? null,
                        'updated_by' => sabdy()->id,
                        'status' => $this->transactionStatus,
                    ]);

                    $this->password_transaction = "";
                    $this->transactionStatus = "";
                    $this->transactionId = "";

                    if ($check->updated_by == sabdy()->id) {

                        if ($check->status == 'SUCCESS') {

                            $transaction = Transaction::create([
                                'wallet_transaction_id' => $check->id,
                                'sid_user_id' => sabdy()->id,
                                'transaction_id' => 'RB_' . $check->provider->name . '_' . $transactionCode,
                                'operator_transaction_name' => $check->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => $check->amount,
                                'fee_amount' =>  $check->costs_amount,
                                'received_amount' => $check->amount,
                                'status' => $check->status,
                            ]);

                            InvoiceTransaction::create([
                                'title' => "Transfers d'argent",
                                'wallet_transaction_id' => $check->id,
                                'transactions_id' => $transaction->id,
                                'company_id' => sabdy()->company->id,
                                'description' => $check->description,
                                'status' => $check->status,
                                'created_by' => sabdy()->id,
                            ]);


                            if (sabdy()->is_distributer == 1) {


                                sabdy()->wallet->update([
                                    'reload_by' => sabdy()->firstname .
                                        ' ' . sabdy()->lastname,
                                    'sid_user_relod' => sabdy()->id,
                                    'amount' => DB::raw('amount+' . $commission_transaction),
                                ]);

                                // Send valeur historique distributer
                                $check->adminCom->update([
                                    // 'amount' => $valeur_single,
                                    'amount_distributer' => $commission_transaction,
                                    'sid_user_id' => sabdy()->id,
                                    'description_distributer' => 'Validation distributeur',
                                ]);
                            } else {

                                sabdy()->wallet->update([
                                    'reload_by' => sabdy()->firstname .
                                        ' ' . sabdy()->lastname,
                                    'sid_user_relod' => sabdy()->id,
                                    'amount' => DB::raw('amount+' . $commission_transaction),
                                ]);
                            }

                            if ($check->wallet->sidUser->country->code == 'CI' && $check->wallet->sidUser->is_custumers == 1) {

                                $message =
                                    'Vous demande de transaction de ' .
                                    $check->amount .
                                    $check->wallet->currencie->code .
                                    ' ' .
                                    'par ' .
                                    $check->provider->name .
                                    ' le ' .
                                    $check->updated_at .
                                    ' a été acceptée avec succès ' .
                                    'Robus vous remercie';

                                $encode = substr($check->wallet->sidUser->phone, -10);

                                Http::get(
                                    "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                );
                            }

                            $valeur_amount_com = $check->adminCom->amount;

                            if ($check->wallet->sidUser->is_under_distributer == true) {

                                //  Send valeur in Admin
                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    $valeur_single = $valeur_amount_com / $count_admin;

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);
                                        $check->adminCom->update([
                                            'amount' =>   $valeur_amount_com,
                                            'sid_user_id' =>   $admin_single->id,
                                            'description' => 'Paiement commission sous-distributer',
                                            'status'  => "SUCCESS",
                                            'amount_under' =>  $valeur_amount_com,
                                            'description_underdistributer' =>  'Paiement commission sous-distributer'
                                        ]);

                                        $admin_single->wallet->update([
                                            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                                            'sid_user_relod' => sabdy()->id,
                                            'amount' => DB::raw('amount-' . $valeur_single),
                                        ]);
                                    }
                                }
                            } else {

                                //  Send valeur in Admin
                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    $valeur_single = $valeur_amount_com / $count_admin;

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);
                                        $check->adminCom->update([
                                            'amount' =>   $valeur_amount_com,
                                            'sid_user_id' =>   $admin_single->id,
                                            'description' => 'Commission administrateur',
                                            'status'  => "SUCCESS",
                                        ]);
                                        $admin_single->wallet->update([
                                            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                                            'sid_user_relod' => sabdy()->id,
                                            'amount' => DB::raw('amount+' . $valeur_single),
                                        ]);
                                    }
                                }
                            }

                            $msg_title = 'Transfers';
                            $msg = 'Demande de transfert a été validé';
                            $check->wallet->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );
                            $notifs = $check->wallet->sidUser->unreadNotifications;
                            Notification::send(
                                $admins,
                                new AdminNotification('Demande de transfers validé')
                            );
                            $this->cancelTransactionField();
                            $this->emit('modal-hide-transfers-validated');
                            $this->emit('modal-hide-transfers');
                            $this->emit('success', 'Transfers accepte !');

                            if ($admins->count() > 0) {

                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }

                                $notify = new MailController;
                                $notify->newTransaction($check);
                            }

                            $notify = new MailController;
                            $notify->newTransaction($check);
                        } elseif ($check->status == 'ERROR') {


                            if ($check->wallet->sidUser->is_under_distributer == 1) {

                                if ($check->provider->id != 9) {


                                    $commission =  $check->amount;


                                    $check->wallet->update([
                                        'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                                        'sid_user_relod' => sabdy()->id,
                                        'amount' => DB::raw('amount+' . $commission),
                                    ]);

                                    $wallet_received =  $check->amount_solde;

                                    $amount_solde = (float)$wallet_received  +   (float)$commission;
                                    // Update commission
                                    $check->update([
                                        'commissions' => 0,
                                        'commissions_admin' => 0,
                                        'amount_solde' => $amount_solde,
                                    ]);


                                    // Valeur amount commission distributeur
                                    $valeur_amount_com = $check->adminCom->amount;
                                    //  Send valeur in Admin
                                    if ($admin_civ->count() > 0) {

                                        $count_admin = $admin_civ->count();

                                        $valeur_single = $valeur_amount_com / $count_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            $check->adminCom->update([
                                                'amount' =>   $valeur_single,
                                                'sid_user_id' =>   $admin_single->id,
                                                'status'  => "ERROR",
                                                'description' =>  'Remboursement des commissions sous distributeur',
                                            ]);
                                        }
                                    }
                                } else {

                                    $commission =  $check->amount + $check->costs_amount;

                                    $check->wallet->update([
                                        'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                                        'sid_user_relod' => sabdy()->id,
                                        'amount' => DB::raw('amount+' . $commission),
                                    ]);

                                    $wallet_received =  $check->amount_solde;

                                    $amount_solde = (float)$wallet_received  +  (float)$commission;
                                    // Update commission
                                    $check->update([
                                        'commissions' => 0,
                                        'commissions_admin' => 0,
                                        'amount_solde' => $amount_solde,
                                    ]);

                                    // Valeur amount commission distributeur
                                    $valeur_amount_com = $check->adminCom->amount;

                                    //  Send valeur in Admin
                                    if ($admin_civ->count() > 0) {

                                        $count_admin = $admin_civ->count();

                                        $valeur_single = $valeur_amount_com / $count_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            $check->adminCom->update([
                                                'amount' =>   0,
                                                'status'  => "ERROR",
                                                'sid_user_id' =>   $admin_single->id,
                                                'description' => 'Remboursement de la commission client',
                                            ]);
                                        }
                                    }
                                }
                            } else {

                                if ($check->provider->id != 9) {
                                    $commission =  $check->amount;
                                } else {

                                    $commission =  $check->amount + $check->costs_amount;
                                }

                                $check->wallet->update([
                                    'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                                    'sid_user_relod' => sabdy()->id,
                                    'amount' => DB::raw('amount+' . $commission),
                                ]);

                                $wallet_received =  $check->amount_solde;

                                $amount_solde = (float)$wallet_received  +   (float)$commission;

                                // Update commission
                                $check->update([
                                    'commissions' => 0,
                                    'commissions_admin' => 0,
                                    'amount_solde' => $amount_solde,
                                ]);

                                // Valeur amount commission distributeur
                                $valeur_amount_com = $check->adminCom->amount;

                                //  Send valeur in Admin
                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    $valeur_single = $valeur_amount_com / $count_admin;

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);
                                        $check->adminCom->update([
                                            'amount' =>   0,
                                            'sid_user_id' =>   $admin_single->id,
                                            'status'  => "ERROR",
                                            'description' => 'Remboursement de la commission client',
                                        ]);
                                    }
                                }

                                if ($check->wallet->sidUser->country->code == 'CI') {

                                    $message =
                                        'Vous demande de transaction de ' .
                                        $check->amount .
                                        $check->wallet->currencie->code .
                                        ' ' .
                                        'par ' .
                                        $check->provider->name .
                                        ' le ' .
                                        $check->updated_at .
                                        ' a été refusée ' .
                                        'Robus vous remercie';

                                    $encode = substr($check->wallet->sidUser->phone, -10);

                                    Http::get(
                                        "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                    );
                                }
                            }

                            Transaction::create([
                                'wallet_transaction_id' => $check->id,
                                'sid_user_id' => sabdy()->id,
                                'transaction_id' => 'RB_' . $check->provider->name . '_' . $transactionCode,
                                'operator_transaction_name' => $check->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => 0.0,
                                'fee_amount' => 0.0,
                                'received_amount' => $check->amount + $check->costs_amount,
                                'status' => $check->status,
                            ]);

                            $msg_title = 'Transfers';
                            $msg = 'Demande de transfers a été refusé';
                            $check->wallet->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );
                            $notifs = $check->wallet->sidUser->unreadNotifications;
                            Notification::send(
                                $admins,
                                new AdminNotification(
                                    'Demande de transfers a été refusé'
                                )
                            );

                            if ($admins->count() > 0) {

                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }
                            }
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers-validated');
                            $this->emit('modal-hide-transfers');
                            $this->emit('warning', 'Transfers Refusé !');
                            $notify = new MailController;
                            $notify->newTransaction($check);
                        } else {
                            $this->cancelTransactionField();
                            $this->emit('modal-hide-transfers-validated');
                            $this->emit('modal-hide-transfers');
                            $this->emit('error', 'Vous devez faire un choix de traitement !');
                        }
                    } else {
                        $this->cancelTransactionField();
                        $this->emit('modal-hide-transfers-validated');
                        $this->emit('modal-hide-transfers');
                        $this->emit('error', 'Aucune donnée !');
                    }
                } else {
                    $this->cancelTransactionField();
                    $this->emit('modal-hide-transfers-validated');
                    $this->emit('modal-hide-transfers');
                    $this->emit('error', 'Vérifier votre mot de passe !');
                }
            } elseif ($this->password_transaction = "") {
                $this->resetTransactionField();
                $this->emit('modal-hide-transfers-validated');
                $this->emit('modal-hide-transfers');
                $this->emit('info', 'Vous avez cliquez dexu fois le boutton !!');
            } else {
                $this->resetTransactionField();
                $this->emit('modal-hide-transfers-validated');
                $this->emit('modal-hide-transfers');
                $this->emit('info', 'Aucune donnée ou Vous avez cliquez deux fois de suite !');
            }
        } catch (\Exception $exception) {
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers-validated');
            $this->emit('modal-hide-transfers');
            $this->emit('error', 'Vous avez cliquez deux fois sur le button ou une erreur veuillez informer Support client ROBUS !');
        }
    }

    public function updateXbet()
    {
        $check = WalletXbet::whereId($this->xbet_id)->first();
        $rt = rand(40, 5000) * time();
        $user_auth = sabdy();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $admins = $this->getAdminValeurProperty();
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();
        try {

            if ($check) {

                $this->validate([
                    'status_xbet_edit' => 'required',
                    'password_transaction' => 'required',
                ]);


                if (Hash::check($this->password_transaction, $user_auth->password)) {

                    $check->update([
                        'description' => $this->xbetDescription ?? null,
                        'updated_by' => sabdy()->id,
                        'status' => $this->status_xbet_edit,
                    ]);

                    $this->password_transaction = "";
                    $this->status_xbet_edit = "";
                    $this->xbet_id = "";

                    if ($check->updated_by == sabdy()->id) {

                        if ($check->status == 'SUCCESS') {

                            $commission_transaction = $check->amount_received;


                            if ($check->choose_type_transaction == 1) {

                                sabdy()->wallet->update([
                                    'reload_by' => sabdy()->firstname .
                                        ' ' . sabdy()->lastname,
                                    'sid_user_relod' => sabdy()->id,
                                    'amount' => DB::raw('amount+' . $commission_transaction),
                                ]);
                            } else {

                                sabdy()->wallet->update([
                                    'reload_by' => sabdy()->firstname .
                                        ' ' . sabdy()->lastname,
                                    'sid_user_relod' => sabdy()->id,
                                    'amount' => DB::raw('amount-' . $commission_transaction),
                                ]);

                                // solde custumers
                                $amount_solde = (float)  $check->wallet->amount + (float) $commission_transaction;

                                $check->update([
                                    'amount_solde'  => $amount_solde,
                                ]);

                                $check->wallet->update([
                                    'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                                    'sid_user_relod' => sabdy()->id,
                                    'amount' => DB::raw('amount+' . $commission_transaction),
                                ]);
                            }


                            $valeur_amount_com = $check->adminCom->amount;

                            if ($admin_civ->count() > 0) {

                                $count_admin = $admin_civ->count();

                                $valeur_single = $valeur_amount_com / $count_admin;

                                foreach ($admin_civ as $user) {
                                    $admin_single = SidUser::find($user->id);
                                    $check->adminCom->update([
                                        'amount' =>   $valeur_amount_com,
                                        'sid_user_id' =>   $admin_single->id,
                                        'description' => 'Commission administrateur',
                                        'status'  => "SUCCESS",
                                    ]);
                                    $admin_single->wallet->update([
                                        'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                                        'sid_user_relod' => sabdy()->id,
                                        'amount' => DB::raw('amount+' . $valeur_single),
                                    ]);
                                }
                            }


                            $transaction = OneBetTransaction::create([
                                'wallet_xbet_id' => $check->id,
                                'sid_user_id' => sabdy()->id,
                                'transaction_id' => 'RB_' . $check->provider->name . '_' . $transactionCode,
                                'operator_transaction_name' => $check->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => $check->amount,
                                'fee_amount' =>  $check->costs_amount,
                                'received_amount' => $check->amount,
                                'status' => $check->status,
                            ]);

                            OneBetInvoice::create([
                                'title' => "Depot 1xbet",
                                'wallet_xbet_id' => $check->id,
                                'one_bet_transaction_id' => $transaction->id,
                                'description' => $check->description,
                                'status' => $check->status,
                                'created_by' => sabdy()->id,
                            ]);


                            if ($check->wallet->sidUser->country->code == 'CI' && $check->wallet->sidUser->is_custumers == 1) {

                                $messageType = $check->choose_type_transaction == 1 ? 'demande de dépôt de ' : 'demande de retrait de ';
                                $amount = $check->amount . ' ' . $check->wallet->currencie->code;
                                $provider = $check->provider->name;
                                $username = $check->firstname . ' ' . $check->firstname;
                                $date = $check->updated_at;

                                $message = 'Votre ' . $messageType . $amount . ' par ' . $provider . ' a été validée avec succès le ' . $date . '. Robus vous remercie.';


                                Http::get(
                                    "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                );
                            }

                            $msg_title = 'Service Xbet';
                            $msg = 'Votre xbet a été validé';
                            $check->wallet->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );

                            Notification::send(
                                $admins,
                                new AdminNotification(
                                    'Demande de virement a été validée'
                                )
                            );


                            if ($admins->count() > 0) {


                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }
                            }
                            $this->resetXbetField();
                            $this->emit('menu-xbet-active');
                            $this->emit('modal-hide-xbet-validated');
                            $this->emit('success', 'xbet Accepté !');
                            $notify = new MailController;
                            $notify->newVirement($check);
                        } elseif ($check->status == 'ERROR') {

                            $commission =  $check->amount + $check->costs_amount;

                            if ($check->choose_type_transaction == 1) {
                                $check->wallet->update([
                                    'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                                    'sid_user_relod' => sabdy()->id,
                                    'amount' => DB::raw('amount+' . $commission),
                                ]);

                                $wallet_received =  $check->amount_solde;

                                $amount_solde = (float)$wallet_received  +   (float)$commission;

                                // Update commission
                                $check->update([
                                    'commissions' => 0,
                                    'commissions_admin' => 0,
                                    'amount_solde' => $amount_solde,
                                    'commission_masters' => 0,
                                ]);
                            }


                            // Valeur amount commission distributeur
                            $valeur_amount_com = $check->adminCom->amount;

                            //  Send valeur in Admin
                            if ($admin_civ->count() > 0) {

                                $count_admin = $admin_civ->count();

                                $valeur_single = $valeur_amount_com / $count_admin;

                                foreach ($admin_civ as $user) {
                                    $admin_single = SidUser::find($user->id);
                                    $check->adminCom->update([
                                        'amount' =>   0,
                                        'sid_user_id' =>   $admin_single->id,
                                        'status'  => "ERROR",
                                        'description' => 'Remboursement de la commission client',
                                    ]);
                                }
                            }


                            if ($check->wallet->sidUser->country->code == 'CI') {

                                $messageType = $check->choose_type_transaction == 1 ? 'demande de dépôt de ' : 'demande de retrait de ';
                                $amount = $check->amount . ' ' . $check->wallet->currencie->code;
                                $provider = $check->provider->name;
                                $username = $check->firstname . ' ' . $check->firstname;
                                $date = $check->updated_at;

                                $message = 'Votre ' . $messageType . $amount . ' par ' . $provider . ' a été refusée le ' . $date . '. Robus vous remercie.';

                                $encode = substr($check->wallet->sidUser->phone, -10);

                                Http::get(
                                    "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                );
                            }


                            $transaction = OneBetTransaction::create([
                                'wallet_xbet_id' => $check->id,
                                'sid_user_id' => sabdy()->id,
                                'transaction_id' => 'RB_' . $check->provider->name . '_' . $transactionCode,
                                'operator_transaction_name' => $check->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => $check->amount,
                                'fee_amount' =>  $check->costs_amount,
                                'received_amount' => $check->amount,
                                'status' => $check->status,
                            ]);

                            OneBetInvoice::create([
                                'title' => "Depot 1xbet",
                                'wallet_xbet_id' => $check->id,
                                'one_bet_transaction_id' => $transaction->id,
                                'description' => $check->description,
                                'status' => $check->status,
                                'created_by' => sabdy()->id,
                            ]);


                            $msg_title = 'Service Xbet';
                            $msg = 'Demande de Xbet a été réfusée ';
                            $check->wallet->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );

                            $notifs = $check->wallet->sidUser->unreadNotifications;

                            Notification::send(
                                $admins,
                                new AdminNotification(
                                    'Demande de xbet a été refusée'
                                )
                            );
                            if ($admins->count() > 0) {


                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }
                            }

                            $this->resetXbetField();
                            $this->emit('modal-hide-xbet-validated');
                            $this->emit('menu-xbet-active');

                            $this->emit('error', 'xbet refusée !');

                            $notify = new MailController;
                            $notify->newVirement($check);
                        } else {
                            $this->resetXbetField();
                            $this->emit('modal-hide-xbet-validated');
                            $this->emit('menu-xbet-active');
                            $this->emit('success', 'xbet modifié !');
                        }
                    } else {
                        $this->resetXbetField();
                        $this->emit('menu-xbet-active');
                        $this->emit('modal-hide-xbet-validated');
                        $this->emit('error', 'Pas vous le valideur !');
                    }
                } else {
                    $this->resetXbetField();
                    $this->emit('modal-hide-xbet-validated');
                    $this->emit('menu-xbet-active');
                    $this->emit('error', 'Mot de passe Incorrect !');
                }
            } elseif ($this->password_transaction = "" ||  $this->status_xbet_edit = "") {
                $this->resetXbetField();
                $this->emit('modal-hide-xbet-validated');
                $this->emit('menu-xbet-active');
                $this->emit('info', 'Vous avez cliquez deux fois de suite !');
            } else {
                $this->resetXbetField();
                $this->emit('modal-hide-xbet-validated');
                $this->emit('menu-xbet-active');
                $this->emit('info', 'Aucune donnée ou Vous avez cliquez deux fois de suite!');
            }
        } catch (\Exception $exception) {
            $this->resetXbetField();
            $this->emit('menu-xbet-active');
            $this->emit('modal-hide-xbet-validated');
            $this->emit('error', 'Vous avez cliquez deux fois ou une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }

    public function updateInter()
    {
        $check = WalletInter::whereId($this->interId)->first();
        $rt = rand(40, 5000) * time();
        $user_auth = sabdy();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $admins = $this->getAdminValeurProperty();
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

        try {


            if ($check) {

                $this->validate(
                    [
                        'interStatus' => 'required',
                        'password_transaction' => 'required',
                    ],
                    [

                        'password_transaction.required' =>
                        'Mot de passe obligatoire',
                        'interStatus.required' =>
                        'Statut obligatoire',
                    ]
                );


                if (Hash::check($this->password_transaction, $user_auth->password)) {

                    $check->update([
                        'description'  => $this->inter_description_robus,
                        'updated_by' => sabdy()->id,
                        'status' => $this->interStatus,
                    ]);

                    $this->password_transaction = "";
                    $this->interStatus = "";
                    $this->interId = "";

                    if ($check->updated_by == sabdy()->id) {

                        if ($check->status == 'SUCCESS') {

                            $transaction_inter = TransactionInter::create([
                                'wallet_inter_id' => $check->id,
                                'sid_user_id' => sabdy()->id,
                                'transaction_id' => 'RB_' . $check->provider->name .
                                    '_' . $transactionCode,
                                'operator_transaction_name' =>
                                $check->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => $check->amount,
                                'fee_amount' => $check->costs_amount,
                                'received_amount' => $check->amount,
                                'status' => $check->status,
                            ]);

                            InvoiceInter::create([
                                'title' => "Transfers d'argent",
                                // 'transaction_inter_id' => $check->id,
                                'transaction_inter_id' => $transaction_inter->id,
                                // 'company_id' => sabdy()->company->id,
                                'description' => $check->description,
                                'status' => $check->status,
                                'created_by' => sabdy()->id,
                            ]);

                            if (sabdy()->country->currencie->value > $check->country->currencie->value) {
                                //   Convertion  in CFA
                                $valeur_amount_receveid = (float) $check->amount_received * (float) $check->country->currencie->value;

                                $valeur_amount_send = (float) $check->amount * (float) $check->country->currencie->value;
                            } elseif (sabdy()->country->currencie->value == $check->country->currencie->value) {
                                $valeur_amount_receveid = $check->amount_received;
                                $valeur_amount_send = (float) $check->amount;
                            } elseif (sabdy()->country->currencie->value < $check->country->currencie->value) {
                                //   Convertion in Country send
                                $valeur_amount_receveid = (float) $check->amount_received  * (float) $check->country->currencie->value;
                                $valeur_amount_send = (float) $check->amount * (float) $check->country->currencie->value;
                            }

                            if (
                                $check->provider->id == 48
                                || $check->provider->id == 71
                                || $check->provider->id == 72
                                || $check->provider->id == 73
                                || $check->provider->id == 74
                                || $check->provider->id == 75
                                || $check->provider->id == 76
                                || $check->provider->id == 77
                                || $check->provider->id == 78
                                || $check->provider->id == 49
                                || $check->provider->id == 50
                                || $check->provider->id == 55
                            ) {

                                $commission_transaction = $valeur_amount_receveid;
                            } else {

                                $commission_transaction = $valeur_amount_receveid;
                            }



                            if (sabdy()->is_distributer == 1) {


                                sabdy()->wallet->update([
                                    'reload_by' => sabdy()->firstname .
                                        ' ' . sabdy()->lastname,
                                    'sid_user_relod' => sabdy()->id,
                                    'amount' => DB::raw('amount+' . $commission_transaction),
                                ]);

                                // Send valeur historique distributer
                                $check->adminCom->update([
                                    // 'amount' => $valeur_single,
                                    'amount_distributer' => $commission_transaction,
                                    'sid_user_id' => sabdy()->id,
                                    'description_distributer' => 'Validation distributeur',
                                ]);
                            } else {

                                sabdy()->wallet->update([
                                    'reload_by' => sabdy()->firstname .
                                        ' ' . sabdy()->lastname,
                                    'sid_user_relod' => sabdy()->id,
                                    'amount' => DB::raw('amount+' . $commission_transaction),
                                ]);
                            }


                            if ($check->wallet->sidUser->country->code == 'CI' && $check->wallet->sidUser->is_custumers == 1) {

                                $message =
                                    'Votre demande de paiement de ' .
                                    $check->amount .
                                    $check->wallet->currencie->code .
                                    ' ' .
                                    'par ' .
                                    $check->provider->name .
                                    ' le ' .
                                    $check->updated_at .
                                    ' a été validé avec success \n' .
                                    'Robus vous remercie';

                                $encode = substr($check->wallet->sidUser->phone, -10);


                                Http::get(
                                    "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                );
                            }

                            $valeur_amount_com = $check->adminCom->amount;

                            if ($check->provider_type == 7 || $check->provider_type == 8) {

                                if ($check->wallet->sidUser->is_under_distributer == true) {


                                    if ($valeur_amount_com != null) {
                                        //  Send valeur in Admin
                                        if ($admin_civ->count() > 0) {

                                            $count_admin = $admin_civ->count();

                                            $valeur_single = $valeur_amount_com / $count_admin;

                                            foreach ($admin_civ as $user) {
                                                $admin_single = SidUser::find($user->id);
                                                $check->adminCom->update([
                                                    'amount' =>   $valeur_amount_com,
                                                    'sid_user_id' =>   $admin_single->id,
                                                    'description' => 'Commission administrateur',
                                                    'description_underdistributer' => 'Paiement commission sous-distributer',
                                                    'status'  => "SUCCESS",
                                                ]);

                                                $admin_single->wallet->update([
                                                    'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                                                    'sid_user_relod' => sabdy()->id,
                                                    'amount' => DB::raw('amount+' . $valeur_amount_com),
                                                ]);
                                            }
                                        }
                                    } else {
                                        $this->resetReloadCardField();
                                        $this->emit('modal-hide-reloadCard');
                                        $this->emit('success', 'Rechargement Acceptée sans commission sous-distibuteur !');
                                    }
                                    //  Send valeur in Admin
                                } else {

                                    //  Send valeur in Admin
                                    if ($admin_civ->count() > 0) {

                                        $count_admin = $admin_civ->count();

                                        $valeur_single = $valeur_amount_com / $count_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            $check->adminCom->update([
                                                'amount' =>   $valeur_amount_com,
                                                'sid_user_id' =>   $admin_single->id,
                                                'description' => 'Commission administrateur',
                                                'status'  => "SUCCESS",
                                            ]);
                                            $admin_single->wallet->update([
                                                'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                                                'sid_user_relod' => sabdy()->id,
                                                'amount' => DB::raw('amount+' . $valeur_single),
                                            ]);
                                        }
                                    }
                                }
                            } else {

                                //  Send valeur in Admin
                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    $valeur_single = $valeur_amount_com / $count_admin;

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);
                                        $check->adminCom->update([
                                            'amount' =>   $valeur_amount_com,
                                            'sid_user_id' =>   $admin_single->id,
                                            'description' => 'Commission administrateur',
                                            'status'  => "SUCCESS",
                                        ]);
                                        $admin_single->wallet->update([
                                            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                                            'sid_user_relod' => sabdy()->id,
                                            'amount' => DB::raw('amount+' . $valeur_single),
                                        ]);
                                    }
                                }
                            }
                            $msg_title = 'Paiement internationnal';
                            $msg = 'Demande de transfers inter été validé';
                            $check->wallet->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );

                            $notifs = $check->wallet->sidUser->unreadNotifications;
                            // event(new UpdateInterEvent($check));
                            Notification::send(
                                $admins,
                                new AdminNotification(
                                    'Demande de tarnsfers inter Validé'
                                )
                            );

                            if ($admins->count() > 0) {

                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }
                            }

                            $this->resetInterField();
                            $this->emit('modal-hide-inters-validated');
                            $this->emit('modal-hide-inters');
                            $this->emit('success', 'Transfers Accepte !');
                            $notify = new MailController;
                            $notify->newPaiementInter($check);
                        } elseif ($check->status == 'ERROR') {

                            if ($check->wallet->sidUser->is_under_distributer == 1) {

                                if (
                                    $check->provider->id == 61
                                    || $check->provider->id == 62
                                    || $check->provider->id == 63
                                    || $check->provider->id == 64
                                    || $check->provider->id == 65
                                    || $check->provider->id == 59
                                    || $check->provider->id == 60
                                    || $check->provider->id == 48
                                    || $check->provider->id == 49
                                    || $check->provider->id == 50
                                    || $check->provider->id == 55
                                    || $check->provider->id == 71
                                    || $check->provider->id == 72
                                    || $check->provider->id == 73
                                    || $check->provider->id == 74
                                    || $check->provider->id == 75
                                    || $check->provider->id == 76
                                    || $check->provider->id == 77
                                    || $check->provider->id == 78
                                ) {

                                    $commission =  $check->amount;

                                    $check->wallet->update([
                                        'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                                        'sid_user_relod' => sabdy()->id,
                                        'amount' => DB::raw('amount+' . $commission),
                                    ]);

                                    // Update commission
                                    $wallet_received =  $check->amount_solde;

                                    $amount_solde = (float)$wallet_received  +   (float)$commission;

                                    // Update commission
                                    $check->update([
                                        'commissions' => 0,
                                        'commissions_admin' => 0,
                                        'amount_solde' => $amount_solde,
                                    ]);
                                } else {

                                    $commission =  $check->amount + $check->costs_amount;

                                    $check->wallet->update([
                                        'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                                        'sid_user_relod' => sabdy()->id,
                                        'amount' => DB::raw('amount+' . $commission),
                                    ]);

                                    $wallet_received =  $check->amount_solde;

                                    $amount_solde = (float)$wallet_received  +   (float)$commission;

                                    // Update commission
                                    $check->update([
                                        'commissions' => 0,
                                        'commissions_admin' => 0,
                                        'amount_solde' => $amount_solde,
                                    ]);
                                }
                            } else {

                                if (
                                    $check->provider->id == 48
                                    || $check->provider->id == 71
                                    || $check->provider->id == 72
                                    || $check->provider->id == 73
                                    || $check->provider->id == 74
                                    || $check->provider->id == 75
                                    || $check->provider->id == 76
                                    || $check->provider->id == 77
                                    || $check->provider->id == 78
                                    || $check->provider->id == 49
                                    || $check->provider->id == 50
                                    || $check->provider->id == 55
                                ) {

                                    $commission =  $check->amount;

                                    $check->wallet->update([
                                        'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                                        'sid_user_relod' => sabdy()->id,
                                        'amount' => DB::raw('amount+' . $commission),
                                    ]);

                                    $wallet_received =  $check->amount_solde;

                                    $amount_solde = (float)$wallet_received  +   (float)$commission;

                                    // Update commission
                                    $check->update([
                                        'commissions' => 0,
                                        'commissions_admin' => 0,
                                        'amount_solde' => $amount_solde,
                                    ]);
                                } else {

                                    $commission =  $check->amount + $check->costs_amount;

                                    $check->wallet->update([
                                        'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                                        'sid_user_relod' => sabdy()->id,
                                        'amount' => DB::raw('amount+' . $commission),
                                    ]);

                                    $wallet_received =  $check->amount_solde;

                                    $amount_solde = (float)$wallet_received  +   (float)$commission;

                                    // Update commission
                                    $check->update([
                                        'commissions' => 0,
                                        'commissions_admin' => 0,
                                        'amount_solde' => $amount_solde,
                                    ]);
                                }
                            }

                            // Valeur amount commission distributeur
                            $valeur_amount_com = $check->adminCom->amount;

                            //  Send valeur in Admin
                            if ($admin_civ->count() > 0) {

                                $count_admin = $admin_civ->count();

                                $valeur_single = $valeur_amount_com / $count_admin;

                                foreach ($admin_civ as $user) {
                                    $admin_single = SidUser::find($user->id);
                                    $check->adminCom->update([
                                        'amount' =>   0,
                                        'sid_user_id' =>   $admin_single->id,
                                        'status'  => "ERROR",
                                        'description' => 'Remboursement de la commission client',
                                    ]);
                                }
                            }
                            $transaction_inter = TransactionInter::create([
                                'wallet_inter_id' => $check->id,
                                'sid_user_id' => sabdy()->id,
                                'transaction_id' => 'RB_' . $check->provider->name .
                                    '_' . $transactionCode,
                                'operator_transaction_name' =>
                                $check->provider->name,
                                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                'pay_amount' => $check->amount,
                                'fee_amount' => $check->costs_amount,
                                'received_amount' => $check->amount,
                                'status' => $check->status,
                            ]);

                            InvoiceInter::create([
                                'title' => "Transfers d'argent",
                                // 'transaction_inter_id' => $check->id,
                                'transaction_inter_id' => $transaction_inter->id,
                                // 'company_id' => sabdy()->company->id,
                                'description' => $check->description,
                                'status' => $check->status,
                                'created_by' => sabdy()->id,
                            ]);



                            if ($check->wallet->sidUser->country->code == 'CI' && $check->wallet->sidUser->is_custumers == 1) {

                                $message = 'Votre demande de paiement de ' .
                                    $check->amount .
                                    $check->wallet->currencie->code .
                                    ' ' .
                                    'par ' .
                                    $check->provider->name .
                                    ' a ' .
                                    $check->updated_at .
                                    ' a été refusé  ' .
                                    'Robus vous remercie';

                                $encode = substr(
                                    $check->wallet->sidUser->phone,
                                    -10
                                );


                                Http::get(
                                    "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                );
                            }

                            $msg_title = 'Paiement internationnal';
                            $msg = 'Demande de transfers inter été refusé';
                            $check->wallet->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );
                            $notifs = $check->wallet->sidUser->unreadNotifications;
                            // $user_search->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                            // $notifs = $user_search->unreadNotifications;
                            // event(new UpdateInterEvent($check));
                            // event(new UserNotifEvent($notifs));
                            Notification::send(
                                $admins,
                                new AdminNotification(
                                    'Demande de transfers refusé'
                                )
                            );

                            if ($admins->count() > 0) {

                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }
                            }
                            $this->resetInterField();
                            $this->emit('modal-hide-inters-validated');
                            $this->emit('modal-hide-inters');
                            $this->emit('error', 'Transfers refusé !');
                            $notify = new MailController;
                            $notify->newPaiementInter($check);
                        } else {
                            $this->resetInterField();
                            $this->emit('modal-hide-inters-validated');
                            $this->emit('modal-hide-inters');
                            $this->emit('error', 'Selectionnez le choix du traitement de la demande SVP !');
                        }
                    } else {
                        $this->resetInterField();
                        $this->emit('modal-hide-inters-validated');
                        $this->emit('modal-hide-inters');
                        $this->emit('error', 'Pas le valideur !');
                    }
                } else {
                    $this->resetInterField();
                    $this->emit('modal-hide-inters');
                    $this->emit('modal-hide-inters-validated');
                    $this->emit('error', 'Mot de passe Incorrect !');
                }
            } elseif ($this->password_transaction = "" ||  $this->interStatus = "") {
                $this->resetInterField();
                $this->emit('modal-hide-inters-validated');
                $this->emit('modal-hide-inters');
                $this->emit('info', 'Vous avez cliquez deux fois de suite !');
            }
        } catch (\Exception $exception) {
            $this->resetInterField();
            $this->emit('modal-hide-inters-validated');
            $this->emit('modal-hide-inters');
            $this->emit('error', 'Vous avez cliquez deux fois Ou une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
        }
    }


    public function render()
    {


        $transaction = WalletTransaction::whereIsWeb(true);

        $inter_search = WalletInter::whereIsWeb(true);

        $reload_card = WalletDeposit::whereIsWeb(true);

        $xbet = WalletXbet::whereIsWeb(true);

        $phone_credit = WalletPhoneCredit::whereIsWeb(true);


        // Account valeurs transactions User

        $transaction_error = $this->getTransactionCountValidated(WalletTransaction::class, 'ERROR', 'created_at', 'updated_by', 'status',  'is_web', true);
        $transaction_padding = $this->getTransactionCountValidated(WalletTransaction::class, 'PENDING', 'created_at', 'updated_by', 'status',  'is_web', true);
        $transaction_success = $this->getTransactionCountValidated(WalletTransaction::class, 'SUCCESS', 'created_at', 'updated_by', 'status',  'is_web', true);


        $deposit_error = $this->getTransactionCountValidated(WalletDeposit::class, 'ERROR', 'created_at', 'updated_by', 'status',  'is_web', true);
        $deposit_padding = $this->getTransactionCountValidated(WalletDeposit::class, 'PENDING', 'created_at', 'updated_by', 'status',  'is_web', true);
        $deposit_success = $this->getTransactionCountValidated(WalletDeposit::class, 'SUCCESS', 'created_at', 'updated_by', 'status',  'is_web', true);

        $phone_credit_error = $this->getTransactionCountValidated(WalletPhoneCredit::class, 'ERROR', 'created_at', 'updated_by', 'status',  'is_web', true);
        $phone_credit_padding = $this->getTransactionCountValidated(WalletPhoneCredit::class, 'PENDING', 'created_at', 'updated_by', 'status',  'is_web', true);
        $phone_credit_success = $this->getTransactionCountValidated(WalletPhoneCredit::class, 'SUCCESS', 'created_at', 'updated_by', 'status',  'is_web', true);

        $inter_error = $this->getTransactionCountValidated(WalletInter::class, 'ERROR', 'created_at', 'updated_by', 'status',  'is_web', true);
        $inter_padding = $this->getTransactionCountValidated(WalletInter::class, 'PENDING', 'created_at', 'updated_by', 'status',  'is_web', true);
        $inter_success = $this->getTransactionCountValidated(WalletInter::class, 'SUCCESS', 'created_at', 'updated_by', 'status',  'is_web', true);

        $xbet_error = $this->getTransactionCountValidated(WalletXbet::class, 'ERROR', 'created_at', 'updated_by', 'status',  'is_web', true);
        $xbet_padding = $this->getTransactionCountValidated(WalletXbet::class, 'PENDING', 'wallet_id', 'created_at', 'updated_by', 'status',  'is_web', true);
        $xbet_success = $this->getTransactionCountValidated(WalletXbet::class, 'SUCCESS', 'created_at', 'updated_by', 'status',  'is_web', true);



        // Amount transactions days Success
        $transaction_amount_success  = $this->getTodayTransactionAmountTotal(WalletTransaction::class, 'created_at',  'status', 'SUCCESS', 'updated_by',  'amount',  'is_web', true);
        $phonecredit_amount_success  = $this->getTodayTransactionAmountTotal(WalletPhoneCredit::class, 'created_at',  'status', 'SUCCESS', 'updated_by',  'amount',  'is_web', true);
        $inter_amount_success  = $this->getTodayTransactionAmountTotal(WalletInter::class, 'created_at',  'status', 'SUCCESS', 'updated_by',  'amount',  'is_web', true);
        $deposit_amount_success  = $this->getTodayTransactionAmountTotal(WalletDeposit::class, 'created_at',  'status', 'SUCCESS', 'updated_by',  'amount',  'is_web', true);
        $xbet_amount_success  = $this->getTodayTransactionAmountTotal(WalletXbet::class, 'created_at',  'status', 'SUCCESS', 'updated_by',  'amount',  'is_web', true);

        $walletUser = sabdy()->id;

        return view('livewire.transfer-visitors', [
            'walletSolde' => Wallet::where(
                'sid_user_id',
                $walletUser
            )->sum('amount'),
            'transactions' => $transaction->where('status', 'PENDING')
                ->where('is_web', true)
                ->orderBy('created_at', 'desc')
                ->paginate(15),
            'Internationals' => $inter_search->where('status', 'PENDING')
                ->where('is_web', true)
                ->orderBy('created_at', 'desc')
                ->paginate(15),
            'reloadCards' => $reload_card->where('status', 'PENDING')
                ->where('is_web', true)
                ->orderBy('created_at', 'desc')
                ->paginate(15),
            'xbets' => $xbet->where('status', 'PENDING')
                ->where('is_web', true)
                ->orderBy('created_at', 'desc')
                ->paginate(15),
            'phoneCredits' => $phone_credit->where('status', 'PENDING')
                ->where('is_web', true)
                ->orderBy('created_at', 'desc')
                ->paginate(15),
            'transaction_error' => $transaction_error,
            'transaction_success' => $transaction_success,
            'transaction_pendding' => $transaction_padding,
            'inter_error' => $inter_error,
            'inter_success' => $inter_success,
            'inter_pendding' => $inter_padding,
            'card_error' => $deposit_error,
            'card_success' => $deposit_success,
            'card_pendding' => $deposit_padding,
            'xbets_error' => $xbet_error,
            'xbets_success' => $xbet_success,
            'xbets_pendding' => $xbet_padding,
            'phone_error' => $phone_credit_error,
            'phone_success' => $phone_credit_success,
            'phone_pendding' => $phone_credit_padding,
            'WalletDeposits' => $deposit_amount_success,
            'WalletInters' => $inter_amount_success,
            'WalletTransactions' => $transaction_amount_success,
            'WalletPhoneCredits' => $phonecredit_amount_success,
            'walletsXbets' => $xbet_amount_success,
            'usersCountry' => sabdy()->wallet->currencie->code,

        ]);
    }


    public function getTransactionCountValidated($walletModel, $status, $transaction_days, $walletMy, $wallet_status, $IswebStatus, $isWeb)
    {
        return $walletModel::where(function ($query) use ($walletMy) {
            if (sabdy()->is_distributer) {
                $query->where($walletMy, '=', sabdy()->id);
            } else {
                $query->where($walletMy, '!=', sabdy()->id)
                    ->orWhere($walletMy, '=', sabdy()->id);
            }
        })
            ->whereDate($transaction_days, Carbon::today())
            ->where($wallet_status, $status)
            ->where($IswebStatus, $isWeb)
            ->count();
    }


    public function getTodayTransactionAmountTotal($walletModel, $transaction_days, $transaction_status, $status, $walletMy, $wallet_amount, $IswebStatus, $isWeb)
    {

        return $walletModel::where(function ($query) use ($walletMy) {
            if (sabdy()->is_distributer) {
                $query->where($walletMy, '=', sabdy()->id);
            } else {
                $query->where($walletMy, '!=', sabdy()->id)
                    ->orWhere($walletMy, '=', sabdy()->id);
            }
        })
            ->whereDate($transaction_days, Carbon::today())
            ->where($transaction_status, $status)
            ->where($IswebStatus, $isWeb)
            ->sum($wallet_amount);
    }
}
