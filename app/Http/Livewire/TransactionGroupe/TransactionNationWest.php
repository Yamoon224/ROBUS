<?php

namespace App\Http\Livewire\TransactionGroupe;

use Livewire\Component;
use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use App\Models\Banks\BankCard;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Events\AdminNotifEvent;
use App\Models\Custumers\Country;
use App\Models\Moneys\LevelMoney;
use App\Models\Providers\Provider;
use App\Models\Wallets\WalletBank;
use Illuminate\Support\Facades\DB;
use App\Models\Wallets\WalletInter;
use App\Models\Payments\Transaction;
use App\Models\Wallets\WalletReload;
use App\Models\Costs\TransactionCost;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Notifications\UserNotification;
use App\Notifications\AdminNotification;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use App\Events\CreateWalletTransfersEvent;
use App\Models\Invoices\InvoiceTransaction;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Admin\MailController;

class TransactionNationWest extends Component
{
    public $transaction_amount_robus;
    public $provider_transaction;
    public $choose_type_transaction;
    public $compte_number_robus;
    public $transaction_amount_money;
    public $transactionId;
    public $transaction_phone;
    public $name_custumers;
    public $num_piece_custumers;
    public $date_piece;

    // protected $listeners = [
    //     'echo-private:wallet-transfers-created,.Created_wallet_transfers' => 'notifyNewTransfers',
    // ];


    public function resetValeurReload()
    {
        $this->compte_number_robus = '';
        $this->choose_type_transaction = null;
        $this->provider_transaction = null;
        $this->transaction_amount_robus = '';
        $this->transaction_amount_money = '';
        $this->transaction_phone = '';
        $this->name_custumers = '';
        $this->num_piece_custumers = '';
        $this->date_piece = '';
        // $this->emit('modal-hide-transfers-validated');
        // $this->emit('modal-hide-transfers');
    }

    public function resetTransactionField()
    {
        $this->compte_number_robus = '';
        $this->choose_type_transaction = null;
        $this->provider_transaction = null;
        $this->transaction_amount_robus = '';
        $this->transaction_amount_money = '';
        $this->transaction_phone = '';
        $this->name_custumers = '';
        $this->num_piece_custumers = '';
        $this->date_piece = '';
        $this->emit('modal-hide-transfers-validated');
        $this->emit('modal-hide-transfers');
        $this->emit('transaction_national');

    }
    public function mount()
    {
        $this->resetValeurReload();
        // $this->emit('monEvenement', $resetValeurReload);
    }

    public function cancelTransactionField()
    {
        $this->compte_number_robus = '';
        $this->choose_type_transaction = null;
        $this->provider_transaction = null;
        $this->transaction_amount_robus = '';
        $this->transaction_amount_money = '';
        $this->transaction_phone = '';
        $this->name_custumers = '';
        $this->num_piece_custumers = '';
        $this->date_piece = '';
        $this->emit('modal-hide-inters-validated');
        // $this->emit('menu-inters-active');
        $this->emit('modal-hide-transfers');
        // $this->emit('transaction_national');
    }

    public function addTransactionWestWest()
    {
        $check = WalletTransaction::whereId($this->transactionId)->first();
        $provider = Provider::whereId($this->provider_transaction)->first();
        $admin_civ = $this->getAdminCIVProperty();
        $som_transfers =  $this->getSumValTransactionProperty();
        // Verifier plafond days
        $valeur_ceiling = sabdy()->silver_ceiling;

        try {

            if (!$check) {

                if (sabdy()->is_under_distributer == 1) {

                    // If Transaction Is Robus
                    if ($this->provider_transaction == 9) {

                        $this->validate(
                            [
                                'transaction_amount_robus' => 'required',
                                'compte_number_robus' => 'required',
                            ],
                            [
                                'compte_number_robus.exists' =>
                                'Ce compte n\'existe pas dans chez nous ',
                                'transaction_amount_robus.required' =>
                                'Votre montant est obligatoire',
                                'compte_number_robus.required' =>
                                'le numero est obligatoire',
                            ]
                        );

                        // Search Wallet Send
                        $wallet_search = Wallet::where('wallet_id', $this->compte_number_robus)
                            ->where('country_id', sabdy()->country->id)
                            //   ->where('status', true)
                            ->first();
                            

                        if ($wallet_search->sidUser->is_custumers) {


                            //  Verify info User send
                            if ($wallet_search == null || $this->compte_number_robus == sabdy()->wallet->wallet_id) {
                                $this->resetTransactionField();
                                $this->emit('modal-hide-transfers');
                                $this->emit('error', 'Ce numero n\'existe pas dans cette base ou vous appartient !');
                            } else {

                                // Percent transaction fees 
                                $percent_robus = TransactionCost::whereId(32)->first();

                                if ($percent_robus !== null) {

                                    if ($this->validateDailyLimit($som_transfers, $valeur_ceiling)) {

                                        if ($this->validateAmount()) {

                                            $valeur_received = $this->transaction_amount_robus - ($percent_robus->percent * $this->transaction_amount_robus);

                                            $wallettransaction = $this->createRobusWalletTransaction($percent_robus, $provider);

                                            if ($wallettransaction != null) {

                                                if ($this->updateAmountRobusReceived($wallettransaction, $valeur_received)) {

                                                    if ($this->processCommissionsRobus($percent_robus, $wallettransaction, $admin_civ)) {
                                                        $this->sendNotification($wallettransaction);
                                                    } else {
                                                        $this->resetTransactionField();
                                                        $this->emit('modal-hide-transfers');
                                                        $this->emit('error', 'Error de transferts du montant!');
                                                    }
                                                }
                                            } else {
                                                $this->resetTransactionField();
                                                $this->emit('modal-hide-transfers');
                                                $this->emit('error', 'Error de createtion transferts !');
                                            }
                                        }
                                    }
                                } else {
                                    $this->resetTransactionField();
                                    $this->emit('modal-hide-transfers');
                                    $this->emit('error', 'Niveau de pourcentage introuvable !');
                                }
                            }
                        } else {

                            //  Verify info User send
                            if ($this->validateDailyLimit($som_transfers, $valeur_ceiling)) {

                                if ($this->validateAmount()) {

                                    $valeur_received = $this->transaction_amount_robus ;

                                    $wallettransaction = $this->createRobusWalletTransactionDistributer($provider);

                                    if ($wallettransaction != null) {

                                        if ($this->updateAmountRobusReceivedDistributer($wallettransaction, $valeur_received)) {

                                            if ($this->processCommissionsRobusDistributer()) {
                                                $this->sendNotification($wallettransaction);
                                            } else {
                                                $this->resetTransactionField();
                                                $this->emit('modal-hide-transfers');
                                                $this->emit('error', 'Error de transferts du montant!');
                                            }
                                        }
                                    } else {
                                        $this->resetTransactionField();
                                        $this->emit('modal-hide-transfers');
                                        $this->emit('error', 'Error de createtion transferts !');
                                    }
                                }
                            }
                        }
                    } elseif (in_array($this->provider_transaction, [1, 3, 4, 6 ,71])) {
                        // Providers ORANGE; MTN ; MOOV and Wave
                        $percent_robus = TransactionCost::whereId(32)->first();

                        $this->validate([
                            'transaction_amount_money' => 'required',
                            'transaction_phone' => 'required',
                        ], [
                            'transaction_amount_money.required' => 'Votre montant est obligatoire',
                            'transaction_phone.required' => 'le numero est obligatoire',

                        ]);


                        if ($percent_robus !== null) {

                            if ($this->validateDailyLimit($som_transfers, $valeur_ceiling)) {

                                if ($this->validateAmountMoney()) {

                                    $valeur_received = $this->transaction_amount_money - ($percent_robus->percent * $this->transaction_amount_money);

                                    $valuer_admin = $percent_robus->percent *  $this->transaction_amount_money;

                                    $valuer_deposit_com = $valuer_admin *  $percent_robus->percent_deposit;

                                    $wallettransaction = $this->createMobileWalletTransactionDistributer($percent_robus, $provider ,$valuer_deposit_com);

                                    if ($wallettransaction != null) {

                                        if ($this->updateAmountRobusReceivedMobile($wallettransaction, $valeur_received)) {

                                            if ($this->processCommissionsMobile($percent_robus, $wallettransaction, $admin_civ)) {
                                                $this->sendNotificationMobile($wallettransaction);
                                            } else {
                                                $this->resetTransactionField();
                                                $this->emit('modal-hide-transfers');
                                                $this->emit('error', 'Error de transferts du montant!');
                                            }
                                        }
                                    } else {
                                        $this->resetTransactionField();
                                        $this->emit('modal-hide-transfers');
                                        $this->emit('error', 'Error de createtion transferts !');
                                    }
                                }
                            }
                        } else {
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers');
                            $this->emit('error', 'Niveau de pourcentage introuvable !');
                        }
                    } else {
                        $this->resetTransactionField();
                        $this->emit('modal-hide-transfers');
                        $this->emit('error', 'Pas de selection de providers !');
                    }
                } else {

                    $this->resetTransactionField();
                    $this->emit('modal-hide-transfers');
                    $this->emit('info', 'Vous ne pouvez pas faire des transactions  !');     
                }
            } else {
                $this->resetTransactionField();
                $this->emit('modal-hide-transfers');
                $this->emit('error', 'Error de message de selection !');
            }
        } catch (\Exception $exception) {
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }
    public function render()
    {
        $default_valeur = 2000000;
        $commission_transaction_val_nat_pay = null;
        $commission_transaction_val_nat_receive = null;
        $commission_transaction_val_percent_pay = null;

        $transaction = WalletTransaction::query();

        // Nationnal commission transaction

        if ($this->transaction_amount_robus != '') {

            if (sabdy()->country->name_groupe == 'AFL') {

                //  Conversion valeur
                $conversion_valeur_end  =  $default_valeur  /  sabdy()->wallet->currencie->value;

                if ($conversion_valeur_end  < $this->transaction_amount_robus) {

                    $commission_transaction_val_nat_pay = "Montant maxi est  : " .  number_format($conversion_valeur_end, 2, ',', ' ');

                    $commission_transaction_val_nat_receive = "Montant maxi est  : " .  number_format($conversion_valeur_end, 2, ',', ' ');
                } else {

                    if ($this->provider_transaction == 9) {

                        if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                            $search_wallet = Wallet::where('wallet_id', $this->compte_number_robus)->where('country_id', sabdy()->country->id)->first();

                            if ($search_wallet) {

                                if ($search_wallet->sidUser->is_custumers) {

                                    $commission_transaction = TransactionCost::whereId(32)->first();

                                    $commission_transaction_val_percent_pay = (float) $commission_transaction->percent * $this->transaction_amount_robus;

                                    $commission_transaction_val_nat_pay = (float) $commission_transaction->percent_deposit * $commission_transaction_val_percent_pay;

                                    $commission_transaction_val_nat_receive = (float) $this->transaction_amount_robus - $commission_transaction_val_percent_pay;
                                } else {


                                    $commission_transaction_val_nat_pay =  0;

                                    $commission_transaction_val_nat_receive = (float) $this->transaction_amount_robus;
                                }
                            } else {


                                $commission_transaction_val_nat_pay = 0;

                                $commission_transaction_val_nat_receive = 0;
                            }
                        } else {

                            $commission_transaction_cust = TransactionCost::whereId(32)->first();

                            $commission_transaction_val_nat_pay =  (float) $commission_transaction_cust->percent *  $this->transaction_amount_robus;

                            $commission_transaction_val_nat_receive =  (float) $this->transaction_amount_robus - $commission_transaction_val_nat_pay;
                        }
                    } else {

                        $commission_transaction_val_nat_pay = 0;

                        $commission_transaction_val_nat_receive = 0;
                    }
                }
            } else {

                $commission_transaction_val_nat_pay =  0;

                $commission_transaction_val_nat_receive = (float) $this->transaction_amount_robus;
            }
        }

        if ($this->transaction_amount_money != '') {

            if (sabdy()->country->name_groupe == 'AFL') {

                //  Conversion valeur
                $conversion_valeur_end  =  $default_valeur  /  sabdy()->wallet->currencie->value;

                if ($conversion_valeur_end  < $this->transaction_amount_money) {

                    $commission_transaction_val_nat_pay = "Montant maxi est  : " .  number_format($conversion_valeur_end, 2, ',', ' ');

                    $commission_transaction_val_nat_receive = "Montant maxi est  : " .  number_format($conversion_valeur_end, 2, ',', ' ');
                } else {

                    if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                        $commission_transaction = TransactionCost::whereId(32)->first();

                        $commission_transaction_val_percent_pay = (float) $commission_transaction->percent * (float)$this->transaction_amount_money;

                        $commission_transaction_val_admin_pay = (float) $commission_transaction->percent_admin * $commission_transaction_val_percent_pay;

                        $commission_transaction_val_nat_pay = (float) $commission_transaction->percent_deposit * $commission_transaction_val_percent_pay;

                        $commission_transaction_val_nat_receive = (float) $this->transaction_amount_money - (float)$commission_transaction_val_percent_pay;
                    } else {

                        $commission_transaction_cust = TransactionCost::whereId(32)->first();

                        $commission_transaction_val_nat_pay =  (float) $commission_transaction_cust->percent * (float) $this->transaction_amount_money;

                        $commission_transaction_val_nat_receive =  (float) $this->transaction_amount_money - (float) $commission_transaction_val_nat_pay;
                    }
                }
            } else {

                $commission_transaction_val_nat_pay =  0;

                $commission_transaction_val_nat_receive =  0;
            }
        }

        return view('livewire.transaction-groupe.transaction-nation-west', [
            'walletSolde' => Wallet::where(
                'sid_user_id',
                sabdy()->id
            )->sum('amount'),
            'transactions' => $transaction
                ->join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                ->where('wallet_transactions.status', 'PENDING')
                ->where('wallet_transactions.created_by', sabdy()->id)
                // ->where('wallet_transactions.type_transaction' , 'Depot')
                ->select('wallet_transactions.*')
                ->orderBy('wallet_transactions.created_at', 'desc')
                ->paginate(15),
            'amount_transaction_nat_val_pay' => $commission_transaction_val_nat_pay,
            'amount_transaction_nat_val_receive' => $commission_transaction_val_nat_receive,
           
            'provide_west_benin' => Provider::whereIn('id', array(3, 1))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_west_mali' => Provider::whereIn('id', array(3, 4, 6, 9))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_west_bf' => Provider::whereIn('id', array(4,3,9,6))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_west_togo' => Provider::whereIn('id', array(3, 9))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_west_senegal' => Provider::whereIn('id', array(6, 9))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_west_ghana' => Provider::whereIn('id', array(1, 9))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_west_niger' => Provider::whereIn('id', array(71, 4, 3, 9))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_west_guinne_kona' => Provider::whereIn('id', array(1, 4, 9))
                ->orderBy('name', 'asc')
                ->get(),
            'usersCountry' => sabdy()->wallet->currencie->code,
            'countrie_number_local' => sabdy()->country
                ->indecative,
        ]);
    }

    private function sendNotification($walletTransaction)
    {

        $msg_title = 'Transfert Notional Robus';
        $msg = 'Vous avez effectué un transfert vers Robus avec succes';
        sabdy()->notify(
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg,
            ])
        );
        $notifs = sabdy()->unreadNotifications;
        event(new CreateWalletTransfersEvent($walletTransaction));
        // $this->emit('transaction_inter_sends', $walletTransaction->id);
        $admins = $this->getAdminCIVProperty();
        Notification::send(
            $admins,
            new AdminNotification(
                'Transfert Robus vers le client ' .
                    ' ' . $walletTransaction->wallet->sidUser->firstname
            )
        );
        Notification::send($admins, new AdminNotification('Transfert d\'argent Robus vers ' .
            ' ' . $walletTransaction->wallet->sidUser->firstname));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }

            // $notifs = $admin->unreadNotifications;
            // event(new AdminNotifEvent($notifs));
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers');
            $this->emit('success', 'Transfers effectué !');
            if (sabdy()->email != null) {
                $notify = new MailController();
                $notify->newTransaction($walletTransaction);
            }
        } else {

            // $notifs = $admin->unreadNotifications;
            // event(new AdminNotifEvent($notifs));
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers');
            $this->emit('success', 'Transfers effectué !');
            if (sabdy()->email != null) {
                $notify = new MailController();
                $notify->newTransaction($walletTransaction);
            }
        }
    }

    private function sendNotificationMobile($walletTransaction)
    {

        $msg_title = 'Transfert Mobile money';
        $msg = 'Vous avez effectué un transfert vers Mobile Money ';
        sabdy()->notify(
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg,
            ])
        );
        $notifs = sabdy()->unreadNotifications;
        event(new CreateWalletTransfersEvent($walletTransaction));
        // $this->emit('transaction_inter_sends', $walletTransaction->id);
        $admins = $this->getAdminCIVProperty();
        Notification::send(
            $admins,
            new AdminNotification(
                'Transfert Robus vers le client ' .
                    ' ' . $walletTransaction->wallet->sidUser->firstname
            )
        );
        Notification::send($admins, new AdminNotification('Transfert d\'argent Mobile Money vers ' .
            ' ' . $walletTransaction->wallet->sidUser->firstname));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }

            // $notifs = $admin->unreadNotifications;
            // event(new AdminNotifEvent($notifs));
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers');
            $this->emit('success', 'Transfers effectué !');
            if (sabdy()->email != null) {
                $notify = new MailController();
                $notify->newTransaction($walletTransaction);
            }
        } else {

            // $notifs = $admin->unreadNotifications;
            // event(new AdminNotifEvent($notifs));
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers');
            $this->emit('success', 'Transfers effectué !');
            if (sabdy()->email != null) {
                $notify = new MailController();
                $notify->newTransaction($walletTransaction);
            }
        }
    }

    private function updateAmountRobusReceivedMobile($walletTransaction, $valeur_received)
    {

        $commission_transaction_val_inter_convertion_send = (float)  $valeur_received;

        if ($commission_transaction_val_inter_convertion_send) {

            $walletTransaction->update(['amount_received' => $commission_transaction_val_inter_convertion_send]);

            return true;
        } else {
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers');
            $this->emit('error', 'Erreur de calcul du montant a recevoir !');
            return false;
        }
    }

    // Wallet Robus
    private function updateAmountRobusReceived($walletTransaction, $valeur_received)
    {


        $wallet_search = Wallet::where('wallet_id', $this->compte_number_robus)
            ->where('country_id', sabdy()->country->id)
            //   ->where('status', true)
            ->first();

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));


        $commission_transaction_val_inter_convertion_send = (float)  $valeur_received;

        if ($commission_transaction_val_inter_convertion_send) {

            $walletTransaction->update(['amount_received' => $commission_transaction_val_inter_convertion_send]);

            if ($wallet_search) {

                $wallet_search->update([
                    'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                    'sid_user_relod' => sabdy()->id,
                    'amount' => DB::raw('amount+' . $commission_transaction_val_inter_convertion_send),
                ]);
            } else {

                $this->resetTransactionField();
                $this->emit('modal-hide-transfers');
                $this->emit('error', 'Numero Wallet introuvable!');
                return false;
            }

            $transaction = Transaction::create([
                'wallet_transaction_id' => $walletTransaction->id,
                'sid_user_id' => sabdy()->id,
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
                // 'company_id' => sabdy()->company->id,
                'description' => $walletTransaction->description,
                'status' => $walletTransaction->status,
                'created_by' => sabdy()->id,
            ]);

            return true;
        } else {
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers');
            $this->emit('error', 'Erreur de calcul du montant a recevoir !');
            return false;
        }
    }

    private function updateAmountRobusReceivedDistributer($walletTransaction, $valeur_received)
    {


        $wallet_search = Wallet::where('wallet_id', $this->compte_number_robus)
            ->where('country_id', sabdy()->country->id)
            //   ->where('status', true)
            ->first();

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));


        $commission_transaction_val_inter_convertion_send = (float)  $valeur_received;

        if ($commission_transaction_val_inter_convertion_send) {

            $walletTransaction->update(['amount_received' => $commission_transaction_val_inter_convertion_send]);

            if ($wallet_search) {

                $wallet_search->update([
                    'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                    'sid_user_relod' => sabdy()->id,
                    'amount' => DB::raw('amount+' . $commission_transaction_val_inter_convertion_send),
                ]);
            } else {

                $this->resetTransactionField();
                $this->emit('modal-hide-transfers');
                $this->emit('error', 'Numero Wallet introuvable!');
                return false;
            }

            $transaction = Transaction::create([
                'wallet_transaction_id' => $walletTransaction->id,
                'sid_user_id' => sabdy()->id,
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
                'company_id' => sabdy()->company->id,
                'description' => $walletTransaction->description,
                'status' => $walletTransaction->status,
                'created_by' => sabdy()->id,
            ]);

            return true;
        } else {
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers');
            $this->emit('error', 'Erreur de calcul du montant a recevoir !');
            return false;
        }
    }
    // Validated wallet Robus

    private function createRobusWalletTransaction($percent_robus, $provider)
    {
        $val_amount = $this->transaction_amount_robus;
        $amount_solde = (float)sabdy()->wallet->amount - (float)$val_amount;

        return WalletTransaction::create(
            [
                'wallet_id' => sabdy()->wallet->id,
                'amount' => $this->transaction_amount_robus,
                'description' => $this->transaction_description_robus ?? null,
                'transaction_cost_id' =>  $percent_robus->id,
                'phone' => $this->transaction_phone ?? null,
                'costs_amount' => $percent_robus->percent * $this->transaction_amount_robus,
                'amount_received' => $this->transaction_amount_robus - ($percent_robus->percent * $this->transaction_amount_robus),
                'num_wallet' => $this->compte_number_robus,
                'created_by' => sabdy()->id,
                'provider_id' => $provider->id,
                'choose_type_transaction' => 1,
                'type_transaction' => 'Depot',
                'type_received' => 'Transfert',
                'status' => 'SUCCESS',
                'amount_solde' => $amount_solde ?? null
            ]
        );
    }
    private function createRobusWalletTransactionDistributer($provider)
    {
        $val_amount = $this->transaction_amount_robus;
        $amount_solde =(float) sabdy()->wallet->amount - (float)$val_amount;

        return WalletTransaction::create(
            [
                'wallet_id' => sabdy()->wallet->id,
                'amount' => $this->transaction_amount_robus,
                'description' => $this->transaction_description_robus ?? null,
                'transaction_cost_id' => null,
                'phone' => $this->transaction_phone ?? null,
                'costs_amount' => 0,
                'amount_received' => $this->transaction_amount_robus,
                'num_wallet' => $this->compte_number_robus,
                'created_by' => sabdy()->id,
                'provider_id' => $provider->id,
                'name_complet' => $this->name_custumers ?? null,
                'num_piece' => $this->num_piece_custumers ?? null,
                'validated_date' => $this->date_piece ?? null,
                'choose_type_transaction' => 1,
                'type_transaction' => 'Depot',
                'type_received' => 'Transfert',
                'status' => 'SUCCESS',
                'amount_solde' => $amount_solde ?? null
            ]
        );
    }
    private function createMobileWalletTransactionDistributer($percent_robus ,$provider , $valuer_deposit_com)
    {
        $val_amount = $this->transaction_amount_robus;
        $amount_solde = (float)sabdy()->wallet->amount - (float)$val_amount;

        return WalletTransaction::create(
            [
                'wallet_id' =>   sabdy()->wallet->id,
                'amount' => $this->transaction_amount_money,
                'description'  => $this->transaction_description ?? null,
                'transaction_cost_id' => $percent_robus->id,
                'phone'   => sabdy()->country->indecative . $this->transaction_phone,
                'created_by' => sabdy()->id,
                'provider_id'  =>    $provider->id,
                'num_wallet'   => $this->compte_number_robus ?? null,
                'costs_amount' => $valuer_deposit_com,
                'name_complet' => $this->name_custumers ?? null,
                'num_piece' => $this->num_piece_custumers ?? null,
                'validated_date' => $this->date_piece ?? null,
                'choose_type_transaction' => 1,
                'amount_received'  => $this->transaction_amount_money,
                'type_transaction' => 'Depot',
                'status'  => "PENDING",
                'amount_solde' => $amount_solde ?? null
            ]
        );
    }

    // Validated commissions

    private function processCommissionsRobus($percent_robus, $walletTransaction, $admin_civ)
    {

        $commission =  $this->transaction_amount_robus;
        $country_civ = Country::whereId(107)
            ->first();

        $wallet = sabdy()->wallet;


        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount - ' . $commission),
        ];

        $wallet->update($updatedValues);

        // Commissions Inters 
        if (sabdy()->is_under_distributer == 1) {

            // Percent admin 
            $valeur_percent_admin  = $percent_robus->percent * $this->transaction_amount_robus;

            // 50 %
            $valuer_admin = $percent_robus->percent_admin * $valeur_percent_admin;

            // 20%
            $valuer_deposit = $percent_robus->percent_deposit *  $valeur_percent_admin;


            $valuer_with_drawal =  $percent_robus->percent_with_drawal * $valeur_percent_admin;


            if ($country_civ->currencie->value > sabdy()->country->currencie->value) {


                $admin_valeur_convertion_drawal =  $valuer_with_drawal  *  (float) sabdy()->country->currencie->value;
                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valuer_admin *   (float) sabdy()->country->currencie->value;
            } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {

                //   Convertion in Country send
                $admin_valeur_convertion_drawal =  $valuer_with_drawal;
                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valuer_admin;
            } else {


                $admin_valeur_convertion_drawal =  $valuer_with_drawal  *  (float) sabdy()->country->currencie->value;
                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valuer_admin *   (float) sabdy()->country->currencie->value;
            }


            $walletTransaction->update([
                'commissions' => DB::raw('commissions+'  . $valuer_deposit),
            ]);

            if ($admin_civ->count() > 0) {

                $nmbre_admin = $admin_civ->count();

                $valeur_single = ($admin_valeur_convertion + $admin_valeur_convertion_drawal) / $nmbre_admin;

                foreach ($admin_civ as $user) {
                    $admin_single = SidUser::find($user->id);

                    AdminsCom::create([
                        'amount' =>   $valeur_single,
                        'sid_user_id' =>   $admin_single->id,
                        'wallet_transaction_id' => $walletTransaction->id,
                        'wallet_bank_id' => null,
                        'wallet_deposit_id' => null,
                        'amount_with_draw' =>  0,
                        'wallet_inter_id' => null,
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
        } else {


            // Percent admin 
            $valeur_percent_admin  = $percent_robus->percent_admin * $this->transaction_amount_robus;


            if ($country_civ->currencie->value > sabdy()->country->currencie->value) {

                $admin_valeur_convertion =   (float)  $valeur_percent_admin *   (float) sabdy()->country->currencie->value;
            } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {

                $admin_valeur_convertion = (float)   $valeur_percent_admin;
            } else {

                $admin_valeur_convertion =   (float)  $valeur_percent_admin *   (float) sabdy()->country->currencie->value;
            }

            if ($admin_civ->count() > 0) {

                $nmbre_admin = $admin_civ->count();

                $valeur_single = $admin_valeur_convertion / $nmbre_admin;

                foreach ($admin_civ as $user) {
                    $admin_single = SidUser::find($user->id);

                    AdminsCom::create([
                        'amount' =>   $valeur_single,
                        'sid_user_id' =>   $admin_single->id,
                        'wallet_transaction_id' => $walletTransaction->id,
                        'wallet_bank_id' => null,
                        'wallet_deposit_id' => null,
                        'amount_with_draw' =>  0,
                        'wallet_inter_id' => null,
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
        }

        return true;
    }

    private function processCommissionsMobile($percent_robus, $walletTransaction, $admin_civ)
    {

        $commission =  $this->transaction_amount_money;
        $country_civ = Country::whereId(107)
            ->first();

        $wallet = sabdy()->wallet;


        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount - ' . $commission),
        ];

        $wallet->update($updatedValues);

        // Commissions Inters 
        if (sabdy()->is_under_distributer == 1) {

            // Percent admin 
            $valeur_percent_admin  = $percent_robus->percent * $this->transaction_amount_money;

            // 50 %
            $valuer_admin = $percent_robus->percent_admin * $valeur_percent_admin;

            // 20%
            $valuer_deposit = $percent_robus->percent_deposit *  $valeur_percent_admin;


            if ($country_civ->currencie->value > sabdy()->country->currencie->value) {

                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valuer_admin *   (float) sabdy()->country->currencie->value;
            
            } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {
                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valuer_admin;
            } else {
                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valuer_admin *   (float) sabdy()->country->currencie->value;
            }


            $walletTransaction->update([
                'commissions' => DB::raw('commissions+'  . $valuer_deposit),
            ]);

            if ($admin_civ->count() > 0) {

                $nmbre_admin = $admin_civ->count();

                $valeur_single = $admin_valeur_convertion / $nmbre_admin;

                foreach ($admin_civ as $user) {
                    $admin_single = SidUser::find($user->id);

                    AdminsCom::create([
                        'amount' =>   $valeur_single,
                        'sid_user_id' =>   $admin_single->id,
                        'wallet_transaction_id' => $walletTransaction->id,
                        'wallet_bank_id' => null,
                        'wallet_deposit_id' => null,
                        'amount_with_draw' =>  0,
                        'wallet_inter_id' => null,
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
        } else {


            // Percent admin 
            $valeur_percent_admin  = $percent_robus->percent_admin * $this->transaction_amount_robus;


            if ($country_civ->currencie->value > sabdy()->country->currencie->value) {

                $admin_valeur_convertion =   (float)  $valeur_percent_admin *   (float) sabdy()->country->currencie->value;
            
            } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {

                $admin_valeur_convertion = (float)   $valeur_percent_admin;
            } else {

                $admin_valeur_convertion =   (float)  $valeur_percent_admin *   (float) sabdy()->country->currencie->value;
            }

            if ($admin_civ->count() > 0) {

                $nmbre_admin = $admin_civ->count();

                $valeur_single = $admin_valeur_convertion / $nmbre_admin;

                foreach ($admin_civ as $user) {
                    $admin_single = SidUser::find($user->id);

                    AdminsCom::create([
                        'amount' =>   $valeur_single,
                        'sid_user_id' =>   $admin_single->id,
                        'wallet_transaction_id' => $walletTransaction->id,
                        'wallet_bank_id' => null,
                        'wallet_deposit_id' => null,
                        'amount_with_draw' =>  0,
                        'wallet_inter_id' => null,
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
        }

        return true;
    }


    private function processCommissionsRobusDistributer()
    {

        $commission =  $this->transaction_amount_robus;

        $wallet = sabdy()->wallet;


        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount - ' . $commission),
        ];

        $wallet->update($updatedValues);

        return true;
    }

    // Validated phone
    private function validateAmount()
    {

        $val_amount = $this->transaction_amount_robus;

        if ($val_amount > sabdy()->wallet->amount) {
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers');
            $this->emit('error', 'Vérifiez votre solde !');
            return false;
        }

        return true;
    }

    private function validateAmountMoney()
    {

        $val_amount = $this->transaction_amount_money;

        if ($val_amount > sabdy()->wallet->amount) {
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers');
            $this->emit('error', 'Vérifiez votre solde !');
            return false;
        }

        return true;
    }
    // Validated date daily
    private function validateDailyLimit($som_transfers, $valeur_ceiling)
    {
        if ($som_transfers >= $valeur_ceiling) {
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers');
            $this->emit('warning', 'Votre montant journalier est atteint !');
            return false;
        }

        return true;
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
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where(
            'wallets.country_id',
            sabdy()->country->id
            )
            ->where(
            'wallet_banks.created_by',
            sabdy()->id
            )
            ->whereBetween('wallet_banks.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->select('wallet_banks.*')
            ->where('wallet_banks.status', 'SUCCESS')
            ->sum('wallet_banks.amount');
    }

    public function getWalletDepositsCeilingProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletDeposit::join(
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
            ->whereBetween('wallet_deposits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.status', 'SUCCESS')
            ->sum('wallet_deposits.amount');
    }

    public function getWalletIntersCeilingProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletInter::join(
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
            ->whereBetween('wallet_inters.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->select('wallet_inters.*')
            ->where('wallet_inters.status', 'SUCCESS')
            ->sum('wallet_inters.amount');
    }

    public function getWalletPaymentsCeilingProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletPayment::join(
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
            ->whereBetween('wallet_payments.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'SUCCESS')
            ->sum('wallet_payments.amount');
    }

    public function getWalletTransactionsCeilingProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletTransaction::join(
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
            ->whereBetween('wallet_transactions.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->select('wallet_transactions.*')
            ->where('wallet_transactions.status', 'SUCCESS')
            ->sum('wallet_transactions.amount');
    }

    public function getWalletPhoneCreditsCeilingProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletPhoneCredit::join(
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
            ->whereBetween('wallet_phone_credits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.status', 'SUCCESS')
            ->sum('wallet_phone_credits.amount');
    }

    public function getWalletBankCardCeilingProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return BankCard::where('created_by', sabdy()->id)
            ->whereBetween('created_at', [Carbon::today(), Carbon::today()->endOfDay()])->whereStatus(1)->sum('amount');
    }

    public function getWalletReloadsCeilingProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where(
            'wallets.country_id',
            sabdy()->country->id
            )->where(
            'wallet_reloads.created_by',
            sabdy()->id
            )
            ->whereBetween('wallet_reloads.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'SUCCESS')
            ->sum('wallet_reloads.amount');
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
