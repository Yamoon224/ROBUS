<?php

namespace App\Http\Livewire\Historical;

use Livewire\Component;
use App\Models\Sid\SidUser;
use Livewire\WithPagination;
use App\Events\UserNotifEvent;
use App\Models\Wallets\Wallet;
use App\Events\AdminNotifEvent;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use Illuminate\Support\Facades\DB;
use App\Events\UpdateTransferEvent;
use App\Models\Payments\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Costs\TransactionCost;
use App\Notifications\UserNotification;
use App\Notifications\AdminNotification;
use App\Models\Wallets\WalletTransaction;
use App\Events\CreateWalletTransfersEvent;
use App\Models\Invoices\InvoiceTransaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Admin\MailController;
use Barryvdh\DomPDF\Facade\Pdf;


class TransferHisto extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchTransferHistorical = "";
    public $statusTransferFilter = "";
    public  $startDate = "";
    public  $endDate =  "";
    public  $beginDate = "";
    public  $endDateT =  "";

    // Edit
    public $transactionId;
    public $transaction_description_edit;
    public $transaction_phone_edit;
    public $transaction_cost_edit;
    public $provider_transaction_edit;
    public $compte_number_robus_edit;
    public $transaction_amount_robus_edit;
    public $transaction_amount_money_edit;
    public $transaction_description_robus_edit;
    public $transactionStatus;

    public function editTransactionHisto($id, $state)
    {
        $check = WalletTransaction::whereId($id)->first();
        $provider = Provider::whereId($check->provider_id)->first();
        $this->transactionId = $id;
        $this->transactionStatus =  $check->status;
        $this->provider_transaction_edit = $provider->id;
        $this->transaction_description_edit = $check->description ?? null;
        $this->transaction_phone_edit = $check->phone ?? null;
        $this->compte_number_robus_edit = $check->num_wallet ?? null;
        $this->transaction_amount_robus_edit = $check->amount ?? null;
        $this->transaction_description_robus_edit = $check->description ?? null;
        $this->emit($state);
    }


    // protected function getTransactionsDays()
    // {
    //     return WalletTransaction::whereIsWeb(false)->whereBetween('created_at', [$this->beginDate, $this->endDateT])->get();
    // }

    // protected function getTransactionCount($status)
    // {
    //     return WalletTransaction::whereIsWeb(false)->whereBetween('created_at', [$this->beginDate, $this->endDateT])->where('status', $status)->count();
    // }

    public function updateTransactionHisto()
    {
        $check = WalletTransaction::whereId($this->transactionId)->first();
        $walletUser = Auth::guard('web')->user()->wallet->amount;
        $admins = SidUser::where('status', true)->where('is_admin', true)->where('country_id', Auth::guard('web')->user()->country->id)->get();
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

        if ($check) {

            $this->validate(['transactionStatus' => 'required']);


            if ($this->transactionStatus == "REFUND") {

                if ($check->provider->id != 9) {

                    if ($check->updated_by ==  Auth::guard('web')->user()->id) {
                        $check->update([
                            'updated_by' => Auth::guard('web')->user()->id,
                            'status'  => $this->transactionStatus,
                        ]);


                        if ($this->transactionStatus != "") {


                            if ($check->wallet->sidUser->is_under_distributer == 1) {

                                $commission =  $check->amount;

                                $commission_send =  $check->commissions;
                            } else {

                                $commission =  $check->amount_received + $check->costs_amount;
                                $commission_send =  0;
                            }

                            $wallet = $check->wallet;

                            $valeur_amount_com =  $this->convertAdminCommission($commission, $wallet);


                            $check->wallet->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount+' . $commission),
                            ]);


                            $walletAmount = DB::table('wallets')->select(DB::raw('SUM(amount) as total_amount'))->where('wallet_id',  $check->wallet->wallet_id)->first();

                            // $amount_solde = $check->wallet->amount +  $commission;
                            $amount_solde = (float)$walletAmount->total_amount +  (float)$commission;


                            if ($check->wallet->sidUser->is_under_distributer == 1) {

                                if ($check->commissions != 0) {

                                    $check->update([
                                        'commissions' => 0,
                                        'commissions_admin' => 0,
                                    ]);
                                } else {

                                    $check->wallet->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount-' . $commission_send),
                                    ]);
                                }

                                // Update commission
                                $check->update([
                                    'commissions' => 0,
                                    'commissions_admin' => 0,
                                ]);

                                //  Send valeur in Admin
                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    $valeur_single = $commission_send / $count_admin;

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);
                                        $check->adminCom->update([
                                            'amount' =>  $valeur_single,
                                            'sid_user_id' =>   $admin_single->id,
                                            'description' => 'Remboursement des frais sous distributeur',
                                        ]);
                                        $admin_single->wallet->update([
                                            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                            'sid_user_relod' => Auth::guard('web')->user()->id,
                                            'amount' => DB::raw('amount+' . $valeur_single),
                                        ]);
                                    }
                                }

                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    $valeur_single = $valeur_amount_com / $count_admin;

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);
                                        $check->adminCom->update([
                                            'amount' =>  $valeur_single,
                                            'sid_user_id' =>   $admin_single->id,
                                            'description' => 'Remboursement des montant sous distributeur',
                                        ]);
                                        $admin_single->wallet->update([
                                            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                            'sid_user_relod' => Auth::guard('web')->user()->id,
                                            'amount' => DB::raw('amount-' . $valeur_single),
                                        ]);
                                    }
                                }
                            } else {

                                if ($admin_civ->count() > 0) {

                                    $count_admin = $admin_civ->count();

                                    $valeur_single = $valeur_amount_com / $count_admin;

                                    foreach ($admin_civ as $user) {
                                        $admin_single = SidUser::find($user->id);
                                        $check->adminCom->update([
                                            'amount' =>   0,
                                            'sid_user_id' =>   $admin_single->id,
                                            'description' => 'Remboursement de la commission client',
                                        ]);
                                        $admin_single->wallet->update([
                                            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                            'sid_user_relod' => Auth::guard('web')->user()->id,
                                            'amount' => DB::raw('amount-' . $valeur_single),
                                        ]);
                                    }
                                }
                            }

                            // Wallet User 
                            $wallet_user = $check->wallet->id;
                            $user =  $check->wallet->sidUser->id;
                            $phone =  $check->phone;

                            $wallettransaction = $this->createMobileWalletTransactionDistributer($commission,  $check->provider->id, $amount_solde, $wallet_user, $user, $phone);

                            if ($wallettransaction != null) {

                                if ($this->updateAmountRobusReceivedMobile($wallettransaction, $commission)) {

                                    if ($check->wallet->sidUser->country->code == "CI") {

                                        $message = 'Vous demande de transaction de ' . $check->amount .  $check->wallet->currencie->code . ' ' .  'par '
                                            . $check->provider->name  .  ' a '
                                            . $check->updated_at . ' a été remboursée ' . 'Robus vous remercie';

                                        $encode = substr($check->wallet->sidUser->phone,  -10);

                                        Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                                    }
                                    $this->sendNotificationMobile($wallettransaction);
                                } else {
                                    $this->resetTransactionField();
                                    $this->emit('modal-hide-transfers-histo');
                                    $this->emit('error', 'Error de createtion transferts !');
                                }
                            } else {
                                $this->resetTransactionField();
                                $this->emit('modal-hide-transfers-histo');
                                $this->emit('error', 'Error de createtion transferts !');
                            }

                            $this->transactionStatus = "";
                        } else {
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers-histo');
                            $this->emit('error', 'Demande deja lancée  !!!!');
                        }
                    } else {
                        $this->resetTransactionField();
                        $this->emit('modal-hide-transfers-histo');
                        $this->emit('error', 'Vous n\'êtes pas le validateur de cette transaction  !!!!');
                    }
                } else {


                    // Check wallet receveid 
                    $wallet_received = Wallet::where('wallet_id',  $check->num_wallet)->first();

                    if ($wallet_received) {
                        // Opérations avec $wallet_received

                        $wallet_send = $check->wallet_id;

                        $commission_receiveid =  $check->amount;

                        // Valeur amount commission distributeur
                        $wallet = $wallet_received;

                        $valeur_amount_com =  $this->convertAdminCommission($check->adminCom->amount, $wallet);

                        //Verifier soslde
                        if ($wallet_received->amount >  $commission_receiveid) {

                            $check->update([
                                'updated_by' => Auth::guard('web')->user()->id,
                                'status'  => $this->transactionStatus,
                            ]);


                            if ($this->transactionStatus != "") {

                                $wallet_received->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount-' . $commission_receiveid),
                                ]);

                                $check->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $commission_receiveid),
                                ]);

                                if ($check->wallet->sidUser->is_under_distributer == 1) {

                                    if ($check->commissions != 0) {

                                        $check->update([
                                            'commissions' => 0,
                                            'commissions_admin' => 0,
                                        ]);
                                    } else {

                                        $check->wallet->update([
                                            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                            'sid_user_relod' => Auth::guard('web')->user()->id,
                                            'amount' => DB::raw('amount-' . $valeur_amount_com),
                                        ]);
                                    }


                                    // Update commission
                                    $check->update([
                                        'commissions' => 0,
                                        'commissions_admin' => 0,
                                    ]);

                                    //  Send valeur in Admin
                                    if ($admin_civ->count() > 0) {

                                        $count_admin = $admin_civ->count();

                                        $valeur_single = $valeur_amount_com / $count_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            $check->adminCom->update([
                                                'amount' =>  $valeur_single,
                                                'sid_user_id' =>   $admin_single->id,
                                                'description' => 'Remboursement des frais sous distributeur',
                                            ]);
                                            $admin_single->wallet->update([
                                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                                'amount' => DB::raw('amount+' . $valeur_single),
                                            ]);
                                        }
                                    }
                                } else {

                                    if ($admin_civ->count() > 0) {

                                        $count_admin = $admin_civ->count();

                                        $valeur_single = $valeur_amount_com / $count_admin;

                                        foreach ($admin_civ as $user) {
                                            $admin_single = SidUser::find($user->id);
                                            $check->adminCom->update([
                                                'amount' =>   0,
                                                'sid_user_id' =>   $admin_single->id,
                                                'description' => 'Remboursement de la commission client',
                                            ]);
                                            $admin_single->wallet->update([
                                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                                'amount' => DB::raw('amount-' . $valeur_single),
                                            ]);
                                        }
                                    }
                                }

                                if ($wallet_send !=  null) {

                                    // amount receved valeurs
                                    $walletAmount = DB::table('wallets')->select(DB::raw('SUM(amount) as total_amount'))->where('wallet_id',  $check->wallet->wallet_id)->first();

                                    $amount_solde = (float)$walletAmount->total_amount +  (float)$commission_receiveid;

                                    $wallet_user =  $wallet_received->id;

                                    $user =  $wallet_received->sidUser->id;

                                    $wallet_info = $check->wallet->wallet_id;

                                    // Amount send valeurs
                                    $walletAmountSend = DB::table('wallets')->select(DB::raw('SUM(amount) as total_amount'))->where('wallet_id',  $wallet_received->wallet_id)->first();

                                    $amount_soldeSend = (float)$walletAmountSend->total_amount -  (float)$commission_receiveid;

                                    // Create Trance Use send error transaction
                                    $wallettransaction = $this->createMobileWalletTransactionRobus($commission_receiveid, $check->provider->id, $amount_solde, $wallet_user, $user, $wallet_info, $amount_soldeSend);


                                    if ($wallettransaction != null) {

                                        if ($this->updateAmountRobusReceivedMobile($wallettransaction, $commission_receiveid)) {

                                            if ($check->wallet->sidUser->country->code == "CI") {

                                                $message = 'Vous demande de transaction de ' . $check->amount .  $check->wallet->currencie->code . ' ' .  'par '
                                                    . $check->provider->name  .  ' a '
                                                    . $check->updated_at . ' a été remboursée ' . 'Robus vous remercie';

                                                $encode = substr($check->wallet->sidUser->phone,  -10);

                                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                                            }
                                            $this->sendNotificationRobus($wallettransaction);
                                        } else {
                                            $this->resetTransactionField();
                                            $this->emit('modal-hide-transfers-histo');
                                            $this->emit('error', 'Error de createtion transferts !');
                                        }
                                    } else {
                                        $this->resetTransactionField();
                                        $this->emit('modal-hide-transfers-histo');
                                        $this->emit('error', 'Error de createtion transferts !');
                                    }
                                }
                            } else {
                                $this->resetTransactionField();
                                $this->emit('modal-hide-transfers-histo');
                                $this->emit('info', 'Demande deja lancée !!');
                            }
                        } else {
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transfers-histo');
                            $this->emit('info', 'Solde insuffisant !!');
                        }

                        $this->transactionStatus = "";
                    } else {
                        $this->resetTransactionField();
                        $this->emit('modal-hide-transfers-histo');
                        $this->emit('error', 'Wallet Introuvable !!');
                    }
                }
            } else {
                $this->resetTransactionField();
                $this->emit('modal-hide-transfers-histo');
                $this->emit('error', 'Aucune donnée !');
            }
        } else {
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers-histo');

            $this->emit('error', 'Pas de modification');
        }
    }


    public function resetTransactionField()
    {

        $this->emit('modal-hide', 'hide');
    }
    public function render()
    {

        $transfers =  WalletTransaction::whereIsWeb(false);

        $walletUser = Auth::guard('web')->user();

        if ($walletUser->is_under_distributer) {
            $transfers->where('wallet_transactions.created_by', $walletUser->id); 
        }

        if ($this->startDate && $this->endDate) {

            // $transfers->whereBetween('wallet_transactions.created_at', [$this->startDate, $this->endDate])->orderBy('wallet_transactions.created_at', 'desc')->paginate(15);

            return view(
                'livewire.historical.transfer-histo',
                [
                    'user' => $this->TransactionsDays,
                    'transfer_historicals' =>  $transfers->whereBetween('wallet_transactions.created_at', [$this->startDate, $this->endDate])->orderBy('wallet_transactions.created_at', 'desc')->paginate(15),
                    'usersCountry' =>  $walletUser->country->currencie->name,
                    'providers' =>  $this->NationTransaction,

                ]
            );
        }

        if ($this->searchTransferHistorical != "") {

            $this->resetPage();

            $transfers->orwhereHas(
                'transaction',
                function ($query) {
                    $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchTransferHistorical . '%')
                        ->orWhere('transaction_id', 'like', '%' .  $this->searchTransferHistorical   . '%')
                        ->orWhere('trans_comp_id', 'like', '%' .  $this->searchTransferHistorical  . '%');
                }
            )
                ->orWhere("wallet_transactions.amount", "LIKE",  "%" . $this->searchTransferHistorical . "%")
                ->orWhere("wallet_transactions.num_wallet", "LIKE",  "%" . $this->searchTransferHistorical . "%")
                ->orWhere("wallet_transactions.phone", "LIKE",  "%" . $this->searchTransferHistorical . "%");
        }

        if ($this->statusTransferFilter != "") {

            // $transfers->where("wallet_transactions.status", $this->statusTransferFilter);

            return view(
                'livewire.historical.transfer-histo',
                [
                    'user' => $this->TransactionsAllStatus,
                    'transfer_historicals' => $transfers->where("wallet_transactions.status", $this->statusTransferFilter)->orderBy('wallet_transactions.created_at', 'desc')->paginate(15),
                    'usersCountry' =>  $walletUser->country->currencie->name,
                    'providers' =>  $this->NationTransaction,

                ]
            );
        }

        return view(
            'livewire.historical.transfer-histo',
            [
                'user' => $this->TransactionsAllDays,
                'transfer_historicals' => $transfers->orderBy('wallet_transactions.created_at', 'desc')->paginate(15),
                'usersCountry' =>  $walletUser->country->currencie->name,
                'providers' =>  $this->NationTransaction,

            ]
        );
    }


    public function getTransactionsAllStatusProperty()
    {


        $data = [];


        $wallets = WalletTransaction::where('wallet_transactions.is_web', false)->where("wallet_transactions.status", $this->statusTransferFilter);

        $walletUser = Auth::guard('web')->user(); 

        if ($walletUser->is_under_distributer) {
            // Filtrer par utilisateur
            $wallets->where('created_by', $walletUser->id);
        }

        $wallets = $wallets->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }
    public function getTransactionsAllDaysProperty()
    {


        $data = [];

        $wallets = WalletTransaction::where('wallet_transactions.is_web', false);

        $walletUser = Auth::guard('web')->user(); 

        if ($walletUser->is_under_distributer) {
            // Filtrer par utilisateur
            $wallets->where('created_by', $walletUser->id);
        }

        $wallets = $wallets->get();
        
        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }

    public function getTransactionsDaysProperty()
    {


        $data = [];

        $wallets = WalletTransaction::whereIsWeb(false)->whereBetween('created_at', [$this->beginDate, $this->endDateT]);

        $walletUser = Auth::guard('web')->user(); 

        if ($walletUser->is_under_distributer) {
            // Filtrer par utilisateur
            $wallets->where('created_by', $walletUser->id);
        }

        $wallets = $wallets->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }

    public function getTransactionsAllsProperty()
    {


        $wallets = WalletTransaction::whereIsWeb(false)->whereBetween('created_at', [$this->beginDate, $this->endDateT])->get();

        return $wallets;
    }


    private function convertAdminCommission($amount, $wallet)
    {
        $country_civ = Country::find(107);

        $robus_currency = sabdy()->country->currencie->value;

        if ($country_civ->currencie->value >  $wallet->country->currencie->value) {
            return $amount * $robus_currency;
        } elseif ($country_civ->currencie->value ==  $wallet->country->currencie->value) {
            return $amount;
        } else {
            return $amount * $robus_currency;
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
            $this->emit('modal-hide-transfers-histo');
            $this->emit('error', 'Erreur de calcul du montant a recevoir !');
            return false;
        }
    }
    private function createMobileWalletTransactionRobus($amount, $provider, $amount_solde, $wallet_user, $user, $wallet_info, $amount_drawal)
    {

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));

        $walletTransaction =  WalletTransaction::create(
            [
                'wallet_id' =>   $wallet_user,
                'amount' => $amount,
                'description'  => $this->transaction_description_edit ?? null,
                // 'transaction_cost_id' => $percent_robus->id,
                // 'phone'   => sabdy()->country->indecative . $this->transaction_phone,
                'created_by' => $user,
                'provider_id'  =>    $provider,
                'num_wallet'   => $wallet_info ?? null,
                // 'costs_amount' => $valuer_deposit_com,
                // 'name_complet' => $this->name_custumers ?? null,
                // 'num_piece' => $this->num_piece_custumers ?? null,
                // 'validated_date' => $this->date_piece ?? null,
                'choose_type_transaction' => 1,
                'amount_received'  => $amount,
                'type_transaction' => 'Compense',
                'type_received' => 'Transfert',
                'status'  => $this->transactionStatus,
                'amount_solde' => $amount_solde ?? null,
                'amount_solde_drawal' => $amount_drawal ?? null
            ]
        );

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

        return $walletTransaction;
    }
    private function createMobileWalletTransactionDistributer($amount, $provider, $amount_solde, $wallet_user, $user, $phone)
    {

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));

        $walletTransaction =  WalletTransaction::create(
            [
                'wallet_id' =>   $wallet_user,
                'amount' => $amount,
                'description'  => $this->transaction_description_edit ?? null,
                // 'transaction_cost_id' => $percent_robus->id,
                'phone'   => $phone,
                'created_by' => $user,
                'provider_id'  =>    $provider,
                // 'num_wallet'   => $this->compte_number_robus ?? null,
                // 'costs_amount' => $valuer_deposit_com,
                // 'name_complet' => $this->name_custumers ?? null,
                // 'num_piece' => $this->num_piece_custumers ?? null,
                // 'validated_date' => $this->date_piece ?? null,
                'choose_type_transaction' => 1,
                'amount_received'  => $amount,
                'type_transaction' => 'Compense',
                'type_received' => 'Transfert',
                'status'  => $this->transactionStatus,
                'amount_solde' => $amount_solde ?? null,
            ]
        );

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

        return $walletTransaction;
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
            $this->emit('modal-hide-transfers-histo');
            $this->emit('success', 'Transfers effectué !');
            if (sabdy()->email != null) {
                $notify = new MailController();
                $notify->newTransaction($walletTransaction);
            }
        } else {

            // $notifs = $admin->unreadNotifications;
            // event(new AdminNotifEvent($notifs));
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers-histo');
            $this->emit('success', 'Transfers effectué !');
            if (sabdy()->email != null) {
                $notify = new MailController();
                $notify->newTransaction($walletTransaction);
            }
        }
    }

    public function getAdminCIVProperty()
    {

        $admin_civ =  SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

        return  $admin_civ;
    }
    private function sendNotificationRobus($walletTransaction)
    {

        $msg_title = 'Transfert Robus';
        $msg = 'Vous avez effectué un transfert vers Robus ';
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
        Notification::send($admins, new AdminNotification('Transfert Robus vers ' .
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
            $this->emit('modal-hide-transfers-histo');
            $this->emit('success', 'Transfers effectué !');
            if (sabdy()->email != null) {
                $notify = new MailController();
                $notify->newTransaction($walletTransaction);
            }
        } else {

            // $notifs = $admin->unreadNotifications;
            // event(new AdminNotifEvent($notifs));
            $this->resetTransactionField();
            $this->emit('modal-hide-transfers-histo');
            $this->emit('success', 'Transfers effectué !');
            if (sabdy()->email != null) {
                $notify = new MailController();
                $notify->newTransaction($walletTransaction);
            }
        }
    }
    public function getNationTransactionProperty()
    {
        $walletUser = Auth::guard('web')->user();

        $transaction_nationnal = Provider::join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', $walletUser->country->id)->where('providers.status', true)
            ->where('providers.provider_type_id',  1)->select('providers.*')->orderBy('providers.name', 'asc')->get();

        return $transaction_nationnal;
    }

    public function getWalletRelaodDistributeDaysProperty()
    {

        $data = [];

        // Exécutez la requête pour obtenir une collection d'objets
        $wallettransactions = walletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')->where('wallets.country_id', Auth::guard('web')->user()->country->id)->whereBetween('wallet_transactions.created_at', [$this->startDate, $this->endDate])->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->get();

        foreach ($wallettransactions as $wallet) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $wallet->costs_amount;
        }

        return array_sum($data);
    }
}
