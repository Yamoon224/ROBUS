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
use App\Models\Custumers\Country;
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
use App\Models\Providers\Provider;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use App\Models\Wallets\WalletXbet;
use App\Notifications\AdminNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class TransferVisitorsOrigin extends Component
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

        // Reload card forms
        public $reloadcard_description_;
        public $provider_reloadcard_;
        public $amount_reloadcard_;
        public $account_number_reloadcard_;
        public $name_reloadcard_;
        public $amount_received;
        public $phone_reloadcard_;
        public $last_digits_reloadcard_;

        // Phone credits
        public $provider_phone_credit_;
        public $number_phone_credit_;
        public $amount_phone_credit_;
        public $phone_benef_phone_credit_;
        public $name_benef_credit_;
        public $name_sender_credit_;
        public $amount_received_credit_;

        // Transactions
        public $provider_transaction_;
        public $transaction_description_;
        public $transaction_phone_;
        public $compte_number_robus_;
        public $transaction_amount_robus_;
        public $transaction_amount_money_;
        public $transaction_description_robus_;
        public $name_robus_;
        public $phone_benef_;

        // Inters transactions
        public $inter_description_;
        public $inter_description_robus_;
        public $provider_inter_;
        public $country_inter_;
        public $amount_inter_;
        public $account_number_inter_;
        public $phone_inter_;
        public $amount_inter_robus_;
        public $country_inter_robus_;
        public $name_benef_inter;
        public $phone_benef_inters;

        // Xbets Transactions
        public $provider_xbet_;
        public $provider_name_xbet_;
        public $code_xbet_;
        public $number_xbet_;
        public $fullname_xbet_;
        public $amount_xbet_;
        public $choose_xbet_;
        public $countrie_xbet_;
        public $phone_xbet_;
        public $amount_received_xbet_;
        public $account_last_digits_;
        public $account_phone_;
        public $type_type;
        public $amount_receved_;
        public $name_sender_inter_;
        public $amount_receved_inter;
        public $countrie_receved_inter;
        // Listerners
        protected $listeners = [

                // Transactions events
                'echo:wallet-xbet-vis-created,.Created_wallet_xbet_vis' => 'notifyNewXbet',
                'echo:wallet-transfers-vis-created,.Created_wallet_transfers_vis' => 'notifyNewTransfers',
                'echo:wallet-inter-vis-created,.Created_wallet_inter_vis' => 'notifyNewInter',
                'echo:wallet-card-vis-created,.Created_wallet_card_vis' => 'notifyNewCard',
                'echo:wallet-phone-vis-created,.Created_wallet_phone_vis' => 'notifyNewPhone',

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
                $this->TransactionActiveMenu();
        }

        public function notifyNewInter()
        {
                $this->showNTransfersNotification = true;
                // $this->emit('modal-hide-inters');
                $this->InterActiveMenu();
        }

        public function notifyNewCard()
        {
                $this->showNTransfersNotification = true;
                // $this->emit('modal-hide-reloadCard');
                $this->ReloadCardActiveMenu();
        }

        public function notifyNewPhone()
        {
                $this->showNTransfersNotification = true;
                // $this->emit('modal-hide-phoneCredit');
                $this->phoneCreditActiveMenu();
        }

        public function notifyNewXbet()
        {
                $this->showNTransfersNotification = true;
                // $this->emit('modal-hide-phoneCredit');
                $this->XbetActiveMenu();
        }

        // Goto pagninated

        //    Paginated
        public function gotoPageTrans($page)
        {
                $this->setPage($page);
                $this->TransactionActiveMenu();
        }

        public function nextPageTrans()
        {
                $this->setPage($this->page + 1);
                $this->TransactionActiveMenu();
        }

        public function previousPageTrans()
        {
                $this->setPage(max($this->page - 1, 1));
                $this->TransactionActiveMenu();
        }

        public function gotoPageInter($page)
        {
                $this->setPage($page);
                $this->InterActiveMenu();
        }

        public function nextPageInter()
        {
                $this->setPage($this->page + 1);
                $this->InterActiveMenu();
        }

        public function previousPageInter()
        {
                $this->setPage(max($this->page - 1, 1));
                $this->InterActiveMenu();
        }

        public function previousPageReload()
        {
                $this->setPage(max($this->page - 1, 1));
                $this->ReloadCardActiveMenu();
        }

        public function gotoPageReload($page)
        {
                $this->setPage($page);
                $this->ReloadCardActiveMenu();
        }

        public function nextPageReload()
        {
                $this->setPage($this->page + 1);
                $this->ReloadCardActiveMenu();
        }

        public function previousPagePhone()
        {
                $this->setPage(max($this->page - 1, 1));
                $this->phoneCreditActiveMenu();
        }

        public function gotoPagePhone($page)
        {
                $this->setPage($page);
                $this->phoneCreditActiveMenu();
        }


        public function previousPageXbet()
        {
                $this->setPage(max($this->page - 1, 1));
                $this->phoneCreditActiveMenu();
        }

        public function gotoPageXbet($page)
        {
                $this->setPage($page);
                $this->XbetActiveMenu();
        }

        public function nextPageXbet()
        {
                $this->setPage($this->page + 1);
                $this->XbetActiveMenu();
        }


        public function nextPagePhone()
        {
                $this->setPage($this->page + 1);
                $this->XbetActiveMenu();
        }

        // Choose Transactions
        public function ChooseUpdatePhoneCredit($id)
        {
                $walletUpdate = WalletPhoneCredit::findOrFail($id);

                try {

                        $walletUpdate->update([
                                'updated_by' => sabdy()->id,
                        ]);
                        event(new ChoosePhoneVisEvent($walletUpdate));
                        $this->phoneCreditActiveMenu();
                        $this->emit('info', 'Vous avez choisi credit téléphonique');
                } catch (\Exception $exception) {
                        $this->phoneCreditActiveMenu();
                        $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
                }
        }
        public function ChooseUpdateReloadCard($id)
        {
                $walletUpdate = WalletDeposit::findOrFail($id);

                try {

                        $walletUpdate->update([
                                'updated_by' => sabdy()->id,
                        ]);
                        $this->ReloadCardActiveMenu();
                        $this->emit('info', 'Vous avez choisi rechargement de carte');
                        event(new ChooseCardVisEvent($walletUpdate));
                } catch (\Exception $exception) {
                        $this->ReloadCardActiveMenu();
                        $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
                }
        }

        // Choose User adminto validated transactions
        public function ChooseUpdateTransaction($id)
        {
                $walletUpdate = WalletTransaction::findOrFail($id);
                try {

                        $walletUpdate->update([
                                'updated_by' => sabdy()->id,
                        ]);

                        event(new ChooseTransactionVisEvent($walletUpdate));
                        $this->TransactionActiveMenu();
                        $this->emit('info', 'Vous avez choisi transaction');
                } catch (\Exception $exception) {
                        $this->TransactionActiveMenu();
                        $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
                }
        }

        // Choose 1xbets
        public function ChooseUpdateXbet($id)
        {
                $walletUpdate = WalletXbet::findOrFail($id);

                try {

                        $walletUpdate->update([
                                'updated_by' => sabdy()->id,
                        ]);
                        event(new ChooseXbetVisEvent($walletUpdate));
                        $this->XbetActiveMenu();
                        $this->emit('info', 'Vous avez choisi Xbet');
                } catch (\Exception $exception) {
                        $this->XbetActiveMenu();
                        $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
                }
        }

        // Update informations
        public function ChooseUpdateInter($id)
        {
                $walletUpdate = WalletInter::findOrFail($id);

                try {

                        $walletUpdate->update([
                                'updated_by' => sabdy()->id,
                        ]);
                        event(new ChooseInterVisEvent($walletUpdate));
                        // $this->emit('modal-hide-inters');
                        $this->InterActiveMenu();
                        $this->emit('info', 'Vous avez choisi transfert international');
                } catch (\Exception $exception) {
                        $this->InterActiveMenu();
                        $this->emit('error', 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
                }
        }

        // Edit valeurs informations

        // Edit Reload Card
        public function editReloadCard($id, $state)
        {
                $check = WalletDeposit::whereId($id)->first();
                $provider = Provider::whereId($check->provider_id)->first();
                $this->reloadcardId = $id;
                $this->reloadcard_description_ = $check->description;
                $this->reloadcardStatus = null;
                $this->type_type = $provider->is_push;
                $this->provider_reloadcard_ = $provider->name;
                $this->amount_reloadcard_ = $check->amount;
                $this->account_number_reloadcard_ = $check->num_carte;
                $this->account_last_digits_ = $check->last_digits;
                $this->amount_receved_ = $check->amount_received;
                $this->account_phone_ = $check->phone;
                $this->name_reloadcard_ = $check->name;
                $this->emit($state);
        }

        // Edit Phone credit
        public function editPhoneCredit($id, $state)
        {
                $check = WalletPhoneCredit::whereId($id)->first();
                $provider = Provider::whereId($check->provider_id)->first();
                $this->phoneCreditId = $id;
                $this->phoneCreditStatus = null;
                $this->provider_phone_credit_ = $provider->name;
                $this->number_phone_credit_ = $check->phone;
                $this->amount_phone_credit_ = $check->amount;
                $this->name_benef_credit_ = $check->name_benef;
                $this->phone_benef_phone_credit_ = $check->phone_benef;
                $this->name_sender_credit_ = $check->name_sender;
                $this->amount_received_credit_ = $check->amount_received;
                $this->emit($state);
        }

        // Edit Transaction
        public function editTransaction($id, $state)
        {
                $check = WalletTransaction::whereId($id)->first();
                $provider = Provider::whereId($check->provider_id)->first();
                $this->transactionId = $id;
                $this->transactionStatus = null;
                $this->provider_transaction_ = $provider->name;
                $this->transaction_description_ = $check->description ?? null;
                $this->transaction_phone_ = $check->phone ?? null;
                $this->compte_number_robus_ = $check->num_wallet ?? null;
                $this->transaction_amount_robus_ = $check->amount ?? null;
                $this->transaction_amount_money_ = $check->amount_received  ?? null;
                $this->name_robus_ = $check->name_complet ?? null;
                $this->phone_benef_ = $check->phone_benef ?? null;
                $this->transaction_description_robus_ = $check->description ?? null;
                $this->emit($state);
        }



        // Edit inter
        public function editInter($id, $state)
        {
                $check = WalletInter::whereId($id)->first();
                $provider = Provider::whereId($check->provider_id)->first();
                $country = Country::whereId($check->country_id)->first();

                // Mooney paiement
                $this->interId = $id;
                $this->inter_description_ = $check->description ?? null;
                $this->interStatus = null;
                $this->provider_inter_ = $provider->name;
                $this->country_inter_ = $country->name ?? null;
                $this->amount_inter_ = $check->amount ?? null;
                $this->account_number_inter_ = $check->number_compte ?? null;
                $this->phone_inter_ = $check->phone ?? null;
                $this->name_sender_inter_ = $check->name_complet ?? null;
                $this->name_benef_inter = $check->name_benef ?? null;
                $this->phone_benef_inters = $check->phone_benef ?? null;
                $this->amount_receved_inter = $check->amount_received  ?? null;
                $this->emit($state);
        }


        public function editXbet($id, $state)
        {
                $check = WalletXbet::whereId($id)->first();
                $provider = Provider::whereId($check->provider_id)->first();
                $this->xbet_id = $id;
                $this->provider_xbet_ = $provider->name;
                $this->provider_name_xbet_ = $provider->name;
                $this->code_xbet_ = $check->code_drawal;
                $this->number_xbet_ = $check->num_acompte ?? null;
                $this->fullname_xbet_ = $check->full_name ?? null;
                $this->amount_xbet_ = $check->amount ?? null;
                $this->xbetDescription = $check->description ?? null;
                $this->status_xbet_edit = null;
                $this->choose_xbet_ = $check->choose_type_transaction ?? null;
                $this->countrie_xbet_ = $check->countrie ?? null;
                $this->phone_xbet_ = $check->phone ?? null;
                $this->amount_received_xbet_ = $check->amount_received  ?? null;
                $this->emit($state);
        }

        public function sendSms()
        {
                return  'Votre opération a été traitée avec succès. Vous recevrez une confirmation de l\'opérateur. 
        ROBUS vous remercie.';
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

                                                                $message = $this->sendSms();

                                                                $encode = substr($check->phone_benef, -10);

                                                                http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");


                                                                Notification::send(
                                                                        $admins,
                                                                        new AdminNotification(
                                                                                'Demande de paiement de crédit validé'
                                                                        )
                                                                );
                                                                $this->cancelPhoneCreditField();
                                                                $this->phoneCreditHide();
                                                                $this->emit('success', 'Achat de credit effectué !');
                                                                if ($admins->count() > 0) {

                                                                        foreach ($admins  as $user) {

                                                                                $admin_single = SidUser::find($user->id);

                                                                                $notifs = $admin_single->unreadNotifications;

                                                                                event(new AdminNotifEvent($notifs));
                                                                        }
                                                                }
                                                        } else {

                                                                $this->cancelPhoneCreditField();
                                                                $this->phoneCreditHide();
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


                                                                // $message =
                                                                //     'Votre demande de crédit de ' .
                                                                //     $check->amount .
                                                                //     $check->wallet->currencie->code .
                                                                //     ' ' .
                                                                //     'par ' .
                                                                //     $check->provider->name .
                                                                //     ' le ' .
                                                                //     $check->updated_at .
                                                                //     ' a été refusé ' .
                                                                //     'Robus vous remercie';


                                                                // $encode = substr($check->phone_benef, -10);

                                                                //                 http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");



                                                                Notification::send(
                                                                        $admins,
                                                                        new AdminNotification('Demande de paiement de credit est refusée')
                                                                );

                                                                $this->cancelPhoneCreditField();
                                                                $this->emit('modal-hide-phoneCredit-visitors-validated');

                                                                $this->emit('error', 'Achat de credit refusé !');

                                                                if ($admins->count() > 0) {

                                                                        foreach ($admins  as $user) {

                                                                                $admin_single = SidUser::find($user->id);

                                                                                $notifs = $admin_single->unreadNotifications;

                                                                                event(new AdminNotifEvent($notifs));
                                                                        }
                                                                }
                                                        } else {
                                                                $this->cancelPhoneCreditField();
                                                                $this->phoneCreditHide();
                                                                $this->emit('error', 'Pas de modification !');
                                                        }
                                                } else {
                                                        $this->cancelPhoneCreditField();
                                                        $this->phoneCreditHide();
                                                        $this->emit('error', 'Selectionnez le choix du traitement de la demande SVP !');
                                                }
                                        } else {
                                                $this->cancelPhoneCreditField();
                                                $this->phoneCreditHide();
                                                $this->emit('error', 'Pas le valideur !');
                                        }
                                } else {
                                        $this->cancelPhoneCreditField();
                                        $this->phoneCreditHide();
                                        $this->emit('error', 'Mot de passe Incorrect !');
                                }
                        } elseif ($this->password_transaction = "" ||  $this->phoneCreditStatus = "") {
                                $this->cancelPhoneCreditField();
                                $this->phoneCreditHide();
                                $this->emit('info', 'Vous avez cliquez deux fois de suite !');
                        } else {
                                $this->cancelPhoneCreditField();
                                $this->phoneCreditHide();
                                $this->emit('info', 'Aucune donnée ou Vous avez cliquez deux fois de suite!');
                        }
                } catch (\Exception $exception) {
                        $this->cancelPhoneCreditField();
                        $this->phoneCreditHide();
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

                                                                // Send valeur historique distributer
                                                                $check->adminCom->update([
                                                                        // 'amount' => $valeur_single,
                                                                        'amount_distributer' => $commission_transaction,
                                                                        'sid_user_id' => sabdy()->id,
                                                                        'description_distributer' => 'Validation distributeur',
                                                                ]);
                                                        }

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
                                                                                'description' => 'Commission administrateur',
                                                                                'status'  => "SUCCESS",
                                                                        ]);
                                                                }
                                                        }

                                                        $message = $this->sendSms();

                                                        $encode = substr($check->phone, -10);

                                                        http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");


                                                        Notification::send(
                                                                $admins,
                                                                new AdminNotification(
                                                                        'Demande de rechargement de carte a été validée'
                                                                )
                                                        );

                                                        $this->cancelReloadCardField();
                                                        $this->ReloadCardHide();
                                                        $this->emit('success', 'Rechargement acceptée !');

                                                        if ($admins->count() > 0) {

                                                                foreach ($admins  as $user) {

                                                                        $admin_single = SidUser::find($user->id);

                                                                        $notifs = $admin_single->unreadNotifications;

                                                                        event(new AdminNotifEvent($notifs));
                                                                }
                                                        }
                                                } elseif ($check->status == 'ERROR') {


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
                                                                                $check->adminCom->update([
                                                                                        'amount' =>   0,
                                                                                        'sid_user_id' =>   $admin_single->id,
                                                                                        'status'  => "ERROR",
                                                                                        'description' => 'Remboursement de la commission',
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

                                                        // $message = $this->sendSms();

                                                        // $encode = substr($check->phone, -10);

                                                        // Http::get(
                                                        //     "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                                        // );


                                                        Notification::send(
                                                                $admins,
                                                                new AdminNotification(
                                                                        'Demande de commande de carte a été refusé'
                                                                )
                                                        );

                                                        $this->cancelReloadCardField();
                                                        $this->ReloadCardHide();
                                                        $this->emit('error', 'Rechargement refusé !');

                                                        if ($admins->count() > 0) {

                                                                foreach ($admins  as $user) {

                                                                        $admin_single = SidUser::find($user->id);

                                                                        $notifs = $admin_single->unreadNotifications;

                                                                        event(new AdminNotifEvent($notifs));
                                                                }
                                                        }
                                                } else {
                                                        $this->cancelReloadCardField();
                                                        $this->ReloadCardHide();
                                                        $this->emit('error', 'Selectionnez le choix de la demande SVP !');
                                                }
                                        } else {
                                                $this->cancelReloadCardField();
                                                $this->ReloadCardHide();
                                                $this->emit('error', 'Pas le valideur !');
                                        }
                                } else {
                                        $this->cancelReloadCardField();
                                        $this->ReloadCardHide();
                                        $this->emit('info', 'Vérifier votre mot de passe !');
                                }
                        } elseif ($this->password_transaction = "") {
                                $this->cancelReloadCardField();
                                $this->ReloadCardHide();
                                $this->emit('info', 'Vous avez cliquez deux fois de suite le boutton !!');
                        } else {
                                $this->cancelReloadCardField();
                                $this->ReloadCardHide();
                                $this->emit('error', 'Aucune donnée ou Vous avez cliquez deux fois de suite !');
                        }
                } catch (\Exception $exception) {
                        $this->cancelReloadCardField();
                        $this->ReloadCardHide();
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
                                                'description' => $this->transaction_description_ ?? null,
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


                                                                // Send valeur historique distributer
                                                                $check->adminCom->update([
                                                                        // 'amount' => $valeur_single,
                                                                        'amount_distributer' => $commission_transaction,
                                                                        'sid_user_id' => sabdy()->id,
                                                                        'description_distributer' => 'Validation distributeur',
                                                                ]);
                                                        }

                                                        $valeur_amount_com = $check->adminCom->amount;

                                                        $message = $this->sendSms();

                                                        $encode = substr($check->phone_benef, -10);

                                                        http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");

                                                        //  Send valeur in Admin
                                                        if ($admin_civ->count() > 0) {


                                                                foreach ($admin_civ as $user) {
                                                                        $admin_single = SidUser::find($user->id);
                                                                        $check->adminCom->update([
                                                                                'amount' =>   $valeur_amount_com,
                                                                                'sid_user_id' =>   $admin_single->id,
                                                                                'description' => 'Commission administrateur',
                                                                                'status'  => "SUCCESS",
                                                                        ]);
                                                                }
                                                        }

                                                        Notification::send(
                                                                $admins,
                                                                new AdminNotification('Demande de transfers validé')
                                                        );
                                                        $this->cancelTransactionField();
                                                        $this->TransactionHide();
                                                        $this->emit('success', 'Transfers accepte !');

                                                        if ($admins->count() > 0) {

                                                                foreach ($admins  as $user) {

                                                                        $admin_single = SidUser::find($user->id);

                                                                        $notifs = $admin_single->unreadNotifications;

                                                                        event(new AdminNotifEvent($notifs));
                                                                }
                                                        }
                                                } elseif ($check->status == 'ERROR') {

                                                        // Update commission
                                                        $check->update([
                                                                'commissions' => 0,
                                                                'commissions_admin' => 0,
                                                        ]);

                                                        // Valeur amount commission distributeur
                                                        $valeur_amount_com = $check->adminCom->amount;

                                                        //  Send valeur in Admin
                                                        if ($admin_civ->count() > 0) {


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

                                                        // $message = $this->sendSms();

                                                        // $encode = substr($check->phone, -10);

                                                        // Http::get(
                                                        //     "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                                        // );

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

                                                        $this->cancelTransactionField();
                                                        $this->TransactionHide();
                                                        $this->emit('warning', 'Transfers Refusé !');
                                                } else {
                                                        $this->cancelTransactionField();
                                                        $this->TransactionHide();
                                                        $this->emit('error', 'Vous devez faire un choix de traitement !');
                                                }
                                        } else {
                                                $this->cancelTransactionField();
                                                $this->TransactionHide();
                                                $this->emit('error', 'Aucune donnée !');
                                        }
                                } else {
                                        $this->cancelTransactionField();
                                        $this->TransactionHide();
                                        $this->emit('error', 'Vérifier votre mot de passe !');
                                }
                        } elseif ($this->password_transaction = "") {
                                $this->cancelTransactionField();
                                $this->TransactionHide();
                                $this->emit('info', 'Vous avez cliquez dexu fois le boutton !!');
                        } else {
                                $this->cancelTransactionField();
                                $this->TransactionHide();
                                $this->emit('info', 'Aucune donnée ou Vous avez cliquez deux fois de suite !');
                        }
                } catch (\Exception $exception) {
                        $this->cancelTransactionField();
                        $this->TransactionHide();
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


                                                        $valeur_amount_com = $check->adminCom->amount;

                                                        if ($admin_civ->count() > 0) {

                                                                $count_admin = $admin_civ->count();

                                                                foreach ($admin_civ as $user) {
                                                                        $admin_single = SidUser::find($user->id);
                                                                        $check->adminCom->update([
                                                                                'amount' =>   $valeur_amount_com,
                                                                                'sid_user_id' =>   $admin_single->id,
                                                                                'description' => 'Commission administrateur',
                                                                                'status'  => "SUCCESS",
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

                                                        $messageType = $check->choose_type_transaction == 1 ? 'demande de dépôt de ' : 'demande de retrait de ';
                                                        $amount = $check->amount . ' ' .  'FCFA';
                                                        $provider = $check->provider->name;

                                                        $date = $check->updated_at;

                                                        $message = $this->sendSms();

                                                        $encode = substr($check->phone, -10);

                                                        http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");

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
                                                        $this->cancelXbetField();
                                                        $this->XbetHide();
                                                        $this->emit('success', 'xbet Accepté !');
                                                } elseif ($check->status == 'ERROR') {

                                                        // Valeur amount commission distributeur
                                                        $valeur_amount_com = $check->adminCom->amount;

                                                        //  Send valeur in Admin
                                                        if ($admin_civ->count() > 0) {

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


                                                        // $message = $this->sendSms();

                                                        // $encode = substr($check->phone, -10);

                                                        // Http::get(
                                                        //     "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                                        // );

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

                                                        $this->cancelXbetField();
                                                        $this->XbetHide();
                                                        $this->emit('error', 'xbet refusée !');
                                                } else {
                                                        $this->cancelXbetField();
                                                        $this->XbetHide();
                                                        $this->emit('success', 'xbet modifié !');
                                                }
                                        } else {
                                                $this->cancelXbetField();
                                                $this->XbetHide();
                                                $this->emit('error', 'Pas vous le valideur !');
                                        }
                                } else {
                                        $this->cancelXbetField();
                                        $this->XbetHide();
                                        $this->emit('error', 'Mot de passe Incorrect !');
                                }
                        } elseif ($this->password_transaction = "" ||  $this->status_xbet_edit = "") {
                                $this->cancelXbetField();
                                $this->XbetHide();
                                $this->emit('info', 'Vous avez cliquez deux fois de suite !');
                        } else {
                                $this->cancelXbetField();
                                $this->XbetHide();
                                $this->emit('info', 'Aucune donnée ou Vous avez cliquez deux fois de suite!');
                        }
                } catch (\Exception $exception) {
                        $this->cancelXbetField();
                        $this->XbetHide();
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


                                                        $commission_transaction = $check->amount_received;


                                                        if (sabdy()->is_distributer == 1) {

                                                                // Send valeur historique distributer
                                                                $check->adminCom->update([
                                                                        // 'amount' => $valeur_single,
                                                                        'amount_distributer' => $commission_transaction,
                                                                        'sid_user_id' => sabdy()->id,
                                                                        'description_distributer' => 'Validation distributeur',
                                                                ]);
                                                        }

                                                        $message = $this->sendSms();

                                                        $encode = substr($check->phone_benef, -10);

                                                        http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");

                                                        $valeur_amount_com = $check->adminCom->amount;

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

                                                        $this->cancelInterField();
                                                        $this->InterHide();
                                                        $this->emit('success', 'Transfers Accepte !');
                                                } elseif ($check->status == 'ERROR') {


                                                        // Update commission
                                                        $check->update([
                                                                'commissions' => 0,
                                                                'commissions_admin' => 0,
                                                                'amount_solde' => 0.0,
                                                        ]);

                                                        // Valeur amount commission distributeur
                                                        $valeur_amount_com = $check->adminCom->amount;

                                                        //  Send valeur in Admin
                                                        if ($admin_civ->count() > 0) {

                                                                foreach ($admin_civ as $user) {
                                                                        $admin_single = SidUser::find($user->id);
                                                                        $check->adminCom->update([
                                                                                'amount' =>   $valeur_amount_com,
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


                                                        // $message = $this->sendSms();

                                                        // $encode = substr(
                                                        //     $check->phone_benef,
                                                        //     -10
                                                        // );


                                                        // Http::get(
                                                        //     "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                                                        // );


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
                                                        $this->cancelInterField();
                                                        $this->InterHide();
                                                        $this->emit('error', 'Transfers refusé !');
                                                } else {
                                                        $this->cancelInterField();
                                                        $this->InterHide();
                                                        $this->emit('error', 'Selectionnez le choix du traitement de la demande SVP !');
                                                }
                                        } else {
                                                $this->cancelInterField();
                                                $this->InterHide();
                                                $this->emit('error', 'Pas le valideur !');
                                        }
                                } else {
                                        $this->cancelInterField();
                                        $this->InterHide();
                                        $this->emit('error', 'Mot de passe Incorrect !');
                                }
                        } elseif ($this->password_transaction = "" ||  $this->interStatus = "") {
                                $this->cancelInterField();
                                $this->InterHide();
                                $this->emit('info', 'Vous avez cliquez deux fois de suite !');
                        }
                } catch (\Exception $exception) {
                        $this->cancelInterField();
                        $this->InterHide();
                        $this->emit('error', 'Vous avez cliquez deux fois Ou une erreur inattendue s\'est produite. veuillez informer Support client ROBUS !');
                }
        }

        // Cancel valeurs transactions

        // reset form Field
        public function cancelInterField()
        {

                $this->interId = '';
                $this->inter_description_ = '';
                $this->inter_description_robus_ = '';
                $this->interStatus = '';
                $this->provider_inter_ = '';
                $this->country_inter_ = '';
                $this->amount_inter_ = ' ';
                $this->account_number_inter_ = ' ';
                $this->phone_inter_ = ' ';
                $this->amount_inter_robus_ = ' ';
                $this->country_inter_robus_ = '';
                $this->name_benef_inter =  '';
                $this->phone_benef_inters = '';
                $this->InterHide();
        }


        public function cancelPhoneCreditField()
        {
                $this->phoneCreditId = '';
                $this->phoneCreditStatus = '';
                $this->provider_phone_credit_ = '';
                $this->number_phone_credit_ = '';
                $this->amount_phone_credit_ = '';
                $this->name_benef_credit_ = '';
                $this->phone_benef_phone_credit_ = '';
                $this->name_sender_credit_ = '';
                $this->amount_received_credit_ = '';
                $this->phoneCreditHide();
        }


        public function cancelReloadCardField()
        {
                $this->reloadcardId = '';
                $this->reloadcard_description_ = '';
                $this->reloadcardStatus = '';
                $this->provider_reloadcard_ = '';
                $this->amount_reloadcard_ = '';
                $this->account_number_reloadcard_ = '';
                $this->name_reloadcard_ = '';
                $this->ReloadCardHide();
        }


        public function cancelXbetField()
        {
                $this->xbet_id = '';
                $this->provider_xbet_ = '';
                $this->provider_name_xbet_ = '';
                $this->code_xbet_ = '';
                $this->number_xbet_ = '';
                $this->fullname_xbet_ = '';
                $this->amount_xbet_ = '';
                $this->xbetDescription = '';
                $this->status_xbet_edit = '';
                $this->choose_xbet_ = '';
                $this->countrie_xbet_ = '';
                $this->phone_xbet_ = '';
                $this->amount_received_xbet_ = '';
                $this->XbetHide();
        }

        public function cancelTransactionField()
        {
                $this->transactionId = '';
                $this->transactionStatus = '';
                $this->provider_transaction_ = '';
                $this->transaction_description_ = '';
                $this->transaction_phone_ = '';
                $this->compte_number_robus_ = '';
                $this->transaction_amount_robus_ = '';
                $this->transaction_amount_money_ = '';
                $this->name_robus_ = '';
                $this->phone_benef_ = '';
                $this->transaction_description_robus_ = '';
                $this->transactionHide();
                $this->TransactionActiveMenu();
        }
        // Hide Modals 

        public function phoneCreditHide()
        {
                $this->emit('modal-hide-phoneCredit-visitors-validated');

                $this->phoneCreditActiveMenu();
        }

        public function XbetHide()
        {
                $this->emit('modal-hide-xbet-visitors-validated');
                $this->XbetActiveMenu();
        }

        public function ReloadCardHide()
        {
                $this->emit('modal-hide-reloadCard-visitors-validated');
                $this->ReloadCardActiveMenu();
        }

        public function InterHide()
        {
                $this->emit('modal-hide-inters-visitors-validated');
                $this->InterActiveMenu();
        }

        public function TransactionHide()
        {
                $this->emit('modal-hide-transfers-visitors-validated');
                $this->TransactionActiveMenu();
        }



        // Active Menus

        public function phoneCreditActiveMenu()
        {
                $this->emit('menu-phoneCredit-vis-active');
        }

        public function XbetActiveMenu()
        {
                $this->emit('menu-xbet-vis-active');
        }

        public function ReloadCardActiveMenu()
        {
                $this->emit('menu-reloadCarte-vis-active');
        }

        public function TransactionActiveMenu()
        {
                $this->emit('menu-transaction-vis-active');
        }

        public function InterActiveMenu()
        {
                $this->emit('menu-inters-vis-active');
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

                // Seach informations


                if ($this->searchTransaction != '') {
                        $this->resetPage();

                        $transaction->where(
                                'amount',
                                'like',
                                '%' . $this->searchTransaction . '%'
                        )
                                ->orWhere(
                                        'num_wallet',
                                        'like',
                                        '%' . $this->searchTransaction . '%'
                                )
                                ->orWhere(
                                        'phone',
                                        'like',
                                        '%' . $this->searchTransaction . '%'
                                )->orWhere(
                                        'name_complet',
                                        'like',
                                        '%' . $this->searchTransaction . '%'
                                )->orWhere(
                                        'phone_benef',
                                        'like',
                                        '%' . $this->searchTransaction . '%'
                                )

                                ->where('status', 'PENDING');

                        $this->TransactionActiveMenu();
                }


                if ($this->seachXbet != '') {
                        $this->resetPage();

                        $xbet->where(
                                'amount',
                                'like',
                                '%' . $this->seachXbet . '%'
                        )
                                ->orWhere(
                                        'code_drawal',
                                        'like',
                                        '%' . $this->seachXbet . '%'
                                )
                                ->orWhere(
                                        'number_compte',
                                        'like',
                                        '%' . $this->seachXbet . '%'
                                )
                                ->orWhere(
                                        'full_name',
                                        'like',
                                        '%' . $this->seachXbet . '%'
                                )->orWhere(
                                        'phone',
                                        'like',
                                        '%' . $this->seachXbet . '%'
                                )
                                ->where('status', 'PENDING');

                        $this->XbetActiveMenu();
                }

                if ($this->searchInter != '') {
                        $this->resetPage();

                        $inter_search->where(
                                'amount',
                                'like',
                                '%' . $this->searchInter . '%'
                        )
                                ->orWhere(
                                        'number_compte',
                                        'like',
                                        '%' . $this->searchInter . '%'
                                )
                                ->orWhere(
                                        'phone',
                                        'like',
                                        '%' . $this->searchInter . '%'
                                )->orWhere(
                                        'name_benef',
                                        'like',
                                        '%' . $this->searchInter . '%'
                                )->orWhere(
                                        'phone_benef',
                                        'like',
                                        '%' . $this->searchInter . '%'
                                )
                                ->where('status', 'PENDING');

                        $this->InterActiveMenu();
                }

                if ($this->searchReloadCard != '') {

                        $this->resetPage();

                        $reload_card->where(
                                'amount',
                                'like',
                                '%' . $this->searchReloadCard . '%'
                        )
                                ->orWhere(
                                        'num_carte',
                                        'like',
                                        '%' . $this->searchReloadCard . '%'
                                )
                                ->orWhere(
                                        'name',
                                        'like',
                                        '%' . $this->searchReloadCard . '%'
                                )->orWhere(
                                        'phone',
                                        'like',
                                        '%' . $this->searchReloadCard . '%'
                                )
                                ->orWhere(
                                        'last_digits',
                                        'like',
                                        '%' . $this->searchReloadCard . '%'
                                )
                                ->where('status', 'PENDING');

                        $this->ReloadCardActiveMenu();
                }

                if ($this->searchPhoneCredit != '') {
                        $this->resetPage();

                        $phone_credit->where(
                                'amount',
                                'like',
                                '%' . $this->searchPhoneCredit . '%'
                        )
                                ->orWhere(
                                        'phone',
                                        'like',
                                        '%' . $this->searchPhoneCredit . '%'
                                )->orWhere(
                                        'phone_benef',
                                        'like',
                                        '%' . $this->searchPhoneCredit . '%'
                                )->orWhere(
                                        'name_benef',
                                        'like',
                                        '%' . $this->searchPhoneCredit . '%'
                                )
                                ->where('status', 'PENDING');

                        $this->phoneCreditActiveMenu();
                }


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

        public function getAdminValeurProperty()
        {

                $admins = SidUser::whereStatus(true)
                        ->whereIsAdmin(true)
                        ->where('country_id', sabdy()->country->id)
                        ->get();

                return  $admins;
        }
}
