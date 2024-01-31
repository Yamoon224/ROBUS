<?php

namespace App\Http\Livewire\Masters;

use App\Events\AdminNotifEvent;
use App\Events\CreateReloadEvent;
use App\Events\CreateWalletTransfersEvent;
use App\Http\Controllers\Admin\MailController;
use App\Mail\CodeValidated;
use App\Mail\UvSend;
use App\Models\AdminsCom;
use App\Models\Banks\BankCard;
use App\Models\Costs\ReloadCost;
use App\Models\Costs\TransactionCost;
use App\Models\Custumers\Country;
use App\Models\Invoices\InvoiceReload;
use App\Models\Invoices\InvoiceTransaction;
use App\Models\Payments\PaymentReload;
use App\Models\Payments\Transaction;
use App\Models\Providers\Provider;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletTransaction;
use App\Notifications\AdminNotification;
use App\Notifications\UserNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class Dumps extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchRealod = "";
    public $statusFilter = "";


    // Reload UV
    public $relaod_uv_amout;
    public $relaod_uv_id;
    public $reload_uv_description;
    public $reload_uv_status;
    public $reload_cost_uv_reload;
    public $provider_reload_uv;
    public $account_reload_uv;
    public $number_reload_uv;
    public $relaod_uv_amout_robus;
    public $newReloadNotification = false;
    public $wallet_search_under;
    public $wallet_under;

    // Edit
    public $provider_reload_uv_edit;
    public $relaod_uv_amout_edit;
    public $account_reload_uv_edit;
    public $number_reload_uv_edit;


    // valeur wallet id
    protected $listeners = [
        "echo-private:wallet-reload-created,.Illuminate\Notifications\Events\BroadcastNotificationCreated" => 'notifyNewReload',
        'echo:wallet-reload-created,.Created_wallet_reload' => 'notifyNewReload',
        'echo:updated_reload,.update_wallet_reload' => 'notifyNewReload',
        'echo:update_all_wallet,.wallet_update_all' => 'notifyNewReload',
        // Choose
        'echo:wallet-reload-choose-created,.Created_wallet_reload_choose' => 'notifyNewReload',
    ];



    public function notifyNewReload()
    {

        $this->newReloadNotification = true;
    }


    // Sted 1 validated Transaction
    public function unload()
    {
        // $todays = Carbon::today();
        $todays = strtotime(Carbon::now());
        $compare_date = date('H:i:s', $todays);
        $temps_ecoule = strtotime($compare_date);
        $ecoule_min = date('s', $temps_ecoule);
        $rt = rand(40, 906769895) * time() + $ecoule_min;
        $code_valdated = sprintf('%0.4s', str_shuffle($rt . $ecoule_min));
        $walletUser = master();
        $provider = Provider::whereId(9)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        // Verifier transfers sum days
        $som_transfers =  $this->getSumValTransactionProperty();
        $rt = rand(40, 500000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $admins = $this->getAdminValeurProperty();
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();


        if (sabdy()->country->name_groupe == 'CIV') {
            $percent_robus_distri =  TransactionCost::whereId(26)->first();
        } elseif (sabdy()->country->name_groupe == 'AFL') {
            $percent_robus_distri =  TransactionCost::whereId(32)->first();
        } elseif (sabdy()->country->name_groupe == 'AFC') {
            $percent_robus_distri =  TransactionCost::whereId(33)->first();
        } elseif (sabdy()->country->name_groupe == 'USA') {
            $percent_robus_distri =  TransactionCost::whereId(34)->first();
        } elseif (sabdy()->country->name_groupe == 'URP') {
            $percent_robus_distri =  TransactionCost::whereId(35)->first();
        } elseif (sabdy()->country->name_groupe == 'MAG') {
            $percent_robus_distri =  TransactionCost::whereId(36)->first();
        }

        try {

            if ($provider->id == 9) {

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
                    ]
                );


                $wallet_search = Wallet::where('wallet_id',  $this->compte_number_robus)
                    ->where('country_id', $walletUser->country->id)
                    // ->where('status', true)
                    ->first();


                $account_active = $wallet_search->sidUser->master_id;

                $account_accepted = $wallet_search->sidUser->is_accepted;

                $account_is_under = $wallet_search->sidUser->is_under_distributer;

                $account_is_custumers = $wallet_search->sidUser->is_custumers;

                $account_is_master = $wallet_search->sidUser->is_master;

                if ($account_active == master()->id && $account_accepted) {

        

                    if ($wallet_search == null &&  $this->compte_number_robus == $walletUser->wallet->wallet_id) {
                        $this->resetTransactionField();
                        $this->emit('modal-hide', 'hide');
                        $this->emit('error', 'Ce numero n\'existe pas dans cette base ou vous appartient !');
                        
                    } elseif (!$account_is_under) {

                        if ($som_transfers >= $valeur_ceiling) {
                            $this->resetTransactionField();
                            $this->emit('modal-hide', 'hide');
                            $this->emit('warning', 'Votre montant journier est atteint !');
                        } else {


                            $wallet_send = $wallet_search;

                            $commission_receiveid =  $this->transaction_amount_robus;

                            $wallet_receveid =  $walletUser->wallet;

                            $commission_send =  $this->convertAdminCommissionInter($commission_receiveid, $wallet_send, $wallet_receveid);

                            // Verify amount
                            if ($commission_send > $wallet_search->amount) {
                                $this->resetTransactionField();
                                $this->emit('modal-hide', 'hide');
                                $this->emit('success', 'Verifier votre montant ! !');
                            } else {



                                $wallet = $wallet_search;

                                $commission_valeur = $percent_robus_distri->percent * $this->transaction_amount_robus;

                                $valeur_amount_com = $percent_robus_distri->percent_with_drawal * $commission_valeur;



                                $valuer_with_drawal =  $this->convertAdminCommission($valeur_amount_com, $wallet);

                                $walletAmountSend = DB::table('wallets')->select(DB::raw('SUM(amount) as total_amount'))->where('wallet_id',  $this->compte_number_robus)->first();

                                $amount_solde_drawal = (float)$walletAmountSend->total_amount -  (float)$commission_send;

                                $amount_drawal =      $amount_solde_drawal;

                                // Update decrement in compte Client
                                $wallet_search->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount-' . $commission_send),
                                ]);


                                // $amount_solde = $check->wallet->amount +  $commission;
                                $amount_solde = (float)sabdy()->wallet->amount +  (float) $this->transaction_amount_robus;

                                $walletUser->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+'  .  $this->transaction_amount_robus),
                                ]);



                                $walletTransaction = WalletTransaction::create(
                                    [
                                        'wallet_id' => $walletUser->wallet->id,
                                        'amount' => $this->transaction_amount_robus,
                                        'description' => $this->transaction_description_robus ?? null,
                                        'transaction_cost_id' => 26,
                                        'phone' => $this->transaction_phone ??  null,
                                        'num_wallet' =>  $this->compte_number_robus,
                                        'created_by' => Auth::guard('web')->user()->id,
                                        'provider_id' => $provider->id,
                                        'code_validated' => null,
                                        'amount_received'  => $commission_send,
                                        'choose_type_transaction' => $this->choose_type_transaction,
                                        'type_transaction' => 'Compense',
                                        'type_received' => 'Transfert',
                                        'status' => 'SUCCESS',
                                        'amount_solde' => $amount_solde ?? null,
                                        'amount_solde_drawal' => $amount_drawal ?? null
                                    ]
                                );
                                // Valeur receive


                                $transaction = Transaction::create([
                                    'wallet_transaction_id' => $walletTransaction->id,
                                    'sid_user_id' => Auth::guard('web')->user()->id,
                                    'transaction_id' => 'RB_' . $walletTransaction->provider->name . '_' . $transactionCode,
                                    'operator_transaction_name' => $walletTransaction->provider->name,
                                    'trans_comp_id' => 'RB_' . $transactionCompiCode,
                                    'pay_amount' => $walletTransaction->amount,
                                    'fee_amount' => 0,
                                    'received_amount' => $walletTransaction->amount,
                                    'status' => $walletTransaction->status,
                                ]);

                                InvoiceTransaction::create([
                                    'title' => "Transfers d'argent",
                                    'wallet_transaction_id' => $walletTransaction->id,
                                    'transactions_id' => $transaction->id,
                                    'company_id' => Auth::guard('web')->user()->company->id,
                                    'description' => $walletTransaction->description,
                                    'status' => $walletTransaction->status,
                                    'created_by' => Auth::guard('web')->user()->id,
                                ]);

                                // Commission Under_distributer



                                // Send Email
                                if ($wallet_search->sidUser->email != null) {

                                    Mail::to($wallet_search->sidUser->email)->send(new CodeValidated('Robus vous a retiré la somme de  : ' . $walletTransaction->amount . ' Sur votre compte ' . ' Votre montant est de : ' .  $walletUser->wallet->amount));
                                }

                                if ($walletUser->email != null) {
                                    $notify = new MailController();
                                    $notify->newTransaction($walletTransaction);
                                }

                                // Notify And User an Admins
                                $msg_title = 'Transactions';
                                $msg = 'Demande de transfert est en cours de validation ';
                                $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                                $notifs = $walletUser->unreadNotifications;
                                $wallet_search->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg,]));
                                $notifs = $wallet_search->sidUser->unreadNotifications;
                                event(new CreateWalletTransfersEvent($walletTransaction));
                                Notification::send($admins, new AdminNotification('Retrait effectuer par ' . $walletTransaction->wallet->sidUser->firstname));
                                $admin = SidUser::where('status', true)
                                    ->where('is_admin', true)
                                    ->first();
                                $notifs = $admin->unreadNotifications;
                                event(new AdminNotifEvent($notifs));
                                $this->resetTransactionField();
                                $this->emit('modal-hide', 'hide');
                                $this->emit('success', 'Retrait effectué avec success !');
                            }
                        }
                    } else {

                        $this->resetTransactionField();
                        $this->emit('modal-hide', 'hide');
                        $this->emit('error', 'Pas de selection de providers !');
                    }
                } else {

                    $this->resetTransactionField();
                    $this->emit('modal-hide', 'hide');
                    $this->emit('error', 'Pas de selection de providers !');
                }
            } else {
                $this->resetTransactionField();
                $this->emit('modal-hide', 'hide');
                $this->emit('error', 'Pas de selection de providers !');
            }
        } catch (\Exception $exception) {
            $this->resetTransactionField();
            $this->emit('modal-hide', 'hide');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
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

    public function addReload()
    {


        $walletUser = master();
        $provider = Provider::whereId(9)->first();
        // if(!$check) {
        $admins = SidUser::where('status', true)->where('is_admin', true)->where('country_id', master()->country->id)->get();
        // Verifier plafond days with piece
        $valeur_ceiling  =  $walletUser->silver_ceiling;
        //  Verifier transfers Som days
        $som_transfers = $this->WalletBanksCeiling + $this->WalletDepositsCeiling + $this->WalletIntersCeiling +  $this->WalletPaymentsCeiling
            + $this->WalletTransactionsCeiling + $this->WalletPhoneCreditsCeiling + $this->WalletReloadsCeiling + $this->WalletBankCardCeiling;

        if ($provider->id == 9) {

            $this->validate([
                'relaod_uv_amout_robus' => 'required',
                'account_reload_uv' => 'required',
            ], [
                'relaod_uv_amout_robus.required' => 'Votre montant est obligatoire',
                'account_reload_uv.required' => 'le numero est obligatoire',
            ]);


            $wallet_search = Wallet::where('wallet_id',  $this->account_reload_uv)
                // ->where('country_id',  $walletUser->country->id)
                ->whereStatus(true)
                ->first();

            $account_active = $wallet_search->sidUser->master_id;

            $account_accepted = $wallet_search->sidUser->is_accepted;

            $account_is_under = $wallet_search->sidUser->is_under_distributer;

            $account_is_custumers = $wallet_search->sidUser->is_custumers;

            $account_is_master = $wallet_search->sidUser->is_master;

            if ($wallet_search == null) {

                $this->cancelReload();
                return  $this->errorWalletAccount();
            } else if ($account_active == master()->id && $account_accepted) { {

                    if ($account_is_under) {

                        if ($som_transfers >= $valeur_ceiling) {
                            $this->emit('modal-hide', 'hide');
                            $this->cancelReload();
                            $this->emit('warning', 'Votre montant journier est atteint !');
                        } {

                            $val_amount = $this->relaod_uv_amout_robus;

                            if ($val_amount > $walletUser->wallet->amount) {

                                $this->cancelReload();
                                $this->emit('modal-hide', 'hide');
                                $this->emit('success', 'Verifier votre montant ! !');
                            } else {

                                $valeur_receive =  $this->convertAdminCommission($this->relaod_uv_amout_robus, $wallet_search);

                                // $amount_solde = $check->wallet->amount +  $commission;
                                $walletAmountSend = Wallet::where('wallet_id',  $this->account_reload_uv)->first();

                                $amount_solde_drawal = (float)$walletAmountSend->amount +  (float)$valeur_receive;

                                $amount_drawal =      $amount_solde_drawal;


                                $amount_solde = (float)sabdy()->wallet->amount - (float)$this->relaod_uv_amout_robus;

                                $walletReloads =  $this->createWAlletReloadRobus($walletUser, $this->relaod_uv_amout_robus, $provider, $this->account_reload_uv, $valeur_receive,  $this->number_reload_uv, $amount_solde, $amount_drawal);

                                if ($walletReloads != null) {
                                    if ($this->updateAmountRobusReceived($walletReloads, $walletUser, $wallet_search, $valeur_receive)) {
                                        $this->sendNotification($wallet_search, $walletUser, $walletReloads, $admins);
                                    } else {
                                        $this->cancelReload();
                                        $this->emit('modal-hide', 'hide');
                                        $this->emit('error', 'Error de transferts du montant !');
                                    }
                                } else {

                                    $this->cancelReload();
                                    $this->emit('modal-hide', 'hide');
                                    $this->emit('error', 'Error de transferts !');
                                }
                            }
                        }
                    } elseif ($account_is_custumers) {
                    } elseif ($account_is_master) {
                    }
                }
            } else  return  $this->errorWalletAccountDist();
        } else {
            $this->cancelReload();
            $this->emit('modal-hide', 'hide');
            $this->emit('error', 'Selectionnez une valeur  !');
        }
    }

    private function createWAlletReloadRobus($walletUser, $relaod_uv_amout_robus, $provider, $account_reload_uv, $valeur_receive, $number_reload_uv, $amount_solde, $amount_drawal)
    {

        $reload_unders =  WalletReload::create([
            'wallet_id' =>   $walletUser->wallet->id,
            'amount' => $relaod_uv_amout_robus,
            'description'  => 'Rechargement de compte UV',
            'reload_cost_id' => 1,
            'created_by' => master()->id,
            'provider_id'  =>  $provider->id,
            'account_wallet'  => $account_reload_uv,
            'amount_received'  => $valeur_receive,
            'type_transaction' => 'Réequilibrage',
            'type_received' => 'Transfert',
            'phone'  => $number_reload_uv ?? null,
            'status'  => "SUCCESS",
            'amount_solde'  => $amount_solde ?? null,
            'amount_solde_drawal' => $amount_drawal ?? null
        ]);


        return $reload_unders;
    }

    private function updateAmountRobusReceived($reload_unders, $walletUser, $wallet_search, $valeur_receive)
    {

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf("%0.9s", str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf("%0.8s", str_shuffle($rt . $tr));

        $commission =  ($reload_unders->amount);

        $walletUser->wallet->update([
            'reload_by' => master()->firstname . ' ' . master()->lastname,
            'sid_user_relod' => master()->id,
            'amount' => DB::raw('amount-'  .  $commission),
        ]);

        // Send in compte Client
        $wallet_search->update([
            'reload_by' => master()->firstname . ' ' . master()->lastname,
            'sid_user_relod' => master()->id,
            'amount' => DB::raw('amount+'  . $valeur_receive),
        ]);


        $paymentRealod = PaymentReload::create([
            'wallet_reload_id' => $reload_unders->id,
            'sid_user_id' => master()->id,
            'transaction_id' => 'RB_' . $reload_unders->provider->name . '_' . $transactionCode,
            'operator_transaction_name' =>  $reload_unders->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' =>  $reload_unders->amount,
            'fee_amount' =>  $reload_unders->amount,
            'received_amount' => ($reload_unders->amount),
            'status'  =>    $reload_unders->status,
        ]);

        InvoiceReload::create([
            'title' => "Rechargement de compte",
            'wallet_reload_id' => $reload_unders->id,
            'payment_reload_id' =>  $paymentRealod->id,
            'company_id' => master()->company->id,
            'description' => $reload_unders->description,
            'status'  =>    $reload_unders->status,
            'created_by'  =>   master()->id,
        ]);

        return true;
    }


    private function sendNotification($wallet_search, $walletUser, $reload_unders, $admins)
    {

        $msg_title = 'Achat d\'UV';
        $msg = 'Votre demande de rechargement d\'UV ';
        $wallet_search->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
        $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
        $notifs = $walletUser->unreadNotifications;
        $notifs = $wallet_search->sidUser->unreadNotifications;
        event(new CreateReloadEvent($reload_unders));
        Notification::send($admins, new AdminNotification('Demande de rechargement UV de ' . ' ' . $reload_unders->wallet->sidUser->firstname));

        if ($admins->count() > 0) {


            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;
                event(new AdminNotifEvent($notifs));
            }

            $this->cancelReload();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Rechargement effectué !');
            if ($walletUser->email != null) {

                $notify = new MailController;
                $notify->newReloadUV($reload_unders);
            }

            if ($wallet_search->sidUser->email != null) {

                Mail::to($wallet_search->sidUser->email)->send(new UvSend('Votre demande d\'achat d\'UV de ' . $reload_unders->amount_received . ' ' . $wallet_search->currencie->code  . ' chez  : '  . $walletUser->firstname . $walletUser->lastname . ' ' . ' Le ' .  $reload_unders->created_at  . ' Validé avec succes'));
            }
        } else {

            $this->cancelReload();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Rechargement effectué !');
            if ($walletUser->email != null) {

                $notify = new MailController;
                $notify->newReloadUV($reload_unders);
            }

            if ($wallet_search->sidUser->email != null) {

                Mail::to($wallet_search->sidUser->email)->send(new UvSend('Votre demande d\'achat d\'UV de ' . $reload_unders->amount_received . ' ' . $wallet_search->currencie->code  . ' chez  : '  . $walletUser->firstname . $walletUser->lastname . ' ' . ' Le ' .  $reload_unders->created_at  . ' Validé avec succes'));
            }
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

    private function convertAdminCommission($amount, $wallet)
    {
        $country_civ = Country::find(107);

        if ($country_civ->currencie->value >  $wallet->country->currencie->value) {
            return $amount / $wallet->country->currencie->value;
        } elseif ($country_civ->currencie->value ==  $wallet->country->currencie->value) {
            return $amount;
        } else {
            return $amount / $wallet->country->currencie->value;
        }
    }
    public function editReload($id, $state)
    {
        $check = WalletReload::whereId($id)->first();
        $provider = Provider::whereId($check->provider_id)->first();
        $this->relaod_uv_id = $id;
        $this->provider_reload_uv_edit  = $provider->id;
        $this->relaod_uv_amout_edit = $check->amount;
        $this->account_reload_uv_edit = $check->account_wallet ?? null;
        $this->number_reload_uv_edit = $check->phone ?? null;

        $this->emit($state);
    }




    public function cancelReload()
    {

        $this->account_reload_uv = '';
        $this->relaod_uv_amout_robus = '';
        $this->emit('modal-hide', 'hide');
    }




    public function errorSolde()
    {

        $this->relaod_uv_amout = '';
        $this->reload_uv_description = '';
        $this->reload_uv_status = '';
        $this->provider_reload_uv = '';
        $this->account_reload_uv = '';
        $this->number_reload_uv = '';
        $this->relaod_uv_amout_robus = '';
        $this->emit('modal-hide', 'hide');
        $this->emit('warning', 'Montant min 2000 !' . master()->country->currencie->name);
    }

    public function errorWalletAccount()
    {

        $this->relaod_uv_amout = '';
        $this->reload_uv_description = '';
        $this->reload_uv_status = '';
        $this->provider_reload_uv = '';
        $this->account_reload_uv = '';
        $this->number_reload_uv = '';
        $this->relaod_uv_amout_robus = '';
        $this->emit('modal-hide', 'hide');
        $this->emit('warning', 'Ce numero n\'existe pas dans cette base ou vous appartient !');
    }

    public function errorWalletAccountDist()
    {

        $this->relaod_uv_amout = '';
        $this->reload_uv_description = '';
        $this->reload_uv_status = '';
        $this->provider_reload_uv = '';
        $this->account_reload_uv = '';
        $this->number_reload_uv = '';
        $this->relaod_uv_amout_robus = '';
        $this->emit('modal-hide', 'hide');
        $this->emit('warning', 'Le numero doit appartenir a un distributeur ou sousdistributer !');
    }



    public function render()
    {

        $realod_search =  WalletReload::query();


        $walletUser = master();

        $wallet = Wallet::query();



        $wallet_info_user = null;
        $wallet_user_image = null;
        $wallet_user_phone = null;
        $wallet_user_country = null;
        $wallet_user_city = null;


        if ($this->statusFilter != "") {
            $realod_search->where('wallet_reloads.status', $this->statusFilter);
        }



        if ($this->account_reload_uv != '') {

            $wallet_search_user = Wallet::where('wallet_id', $this->account_reload_uv)->first();

            if ($wallet_search_user && $wallet_search_user->sidUser) {

                $account_active = $wallet_search_user->sidUser->master_id;

                if ($account_active == master()->id && $wallet_search_user->sidUser->is_accepted) {
                    $wallet_info_user = $wallet_search_user->sidUser->firstname  . ' ' .  $wallet_search_user->sidUser->lastname;
                    $wallet_user_image = optional(optional($wallet_search_user)->sidUser)->image ?? 'public/dist/images/profil.png';
                    $wallet_user_phone = optional(optional($wallet_search_user)->sidUser)->phone;
                    $wallet_user_country = optional(optional($wallet_search_user)->sidUser)->country->name;
                    $wallet_user_city = optional(optional($wallet_search_user)->sidUser)->city->name;
                } else {
                    $wallet_info_user = "Vous n'êtes pas encore lié à ce numéro de wallet ROBUS";
                }
            } else {
                $wallet_info_user = "Numéro de wallet ROBUS introuvable";
            }
        }


        return view(
            'livewire.masters.dumps',
            [
                'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
                'WalletReloads' => $this->WalletReloads,
                'reload_error' => WalletReload::where('wallet_id', $walletUser->wallet->id)->where('status', 'ERROR')->whereDate('created_at', Carbon::today())->count(),
                'reload_success' =>   WalletReload::where('wallet_id', $walletUser->wallet->id)->where('status', 'SUCCESS')->whereDate('created_at', Carbon::today())->count(),
                'reload_pendding' =>   WalletReload::where('wallet_id', $walletUser->wallet->id)->where('status', 'PENDING')->whereDate('created_at', Carbon::today())->count(),
                'reaload_uv' => $realod_search->where('wallet_id', $walletUser->wallet->id)->orWhere('account_wallet', $walletUser->wallet->wallet_id)->orderBy('created_at', 'desc')->paginate(25),
                'wallet_search' => $wallet->where("wallet_id", "like",  "%" . $this->account_reload_uv . "%")
                    ->where('country_id',  $walletUser->country->id)->where('status', true)->get(),
                'amount_reload' => ReloadCost::where('status', true)->first(),
                'withdrawal_robus' => Provider::whereStatus(true)
                    ->whereId(9)
                    ->get(),
                'wallet_info_searchs' => $wallet_info_user,
                'wallet_image' => $wallet_user_image,
                'wallet_phone' =>  $wallet_user_phone,
                'wallet_country' => $wallet_user_country,
                'wallet_city' => $wallet_user_city,
                'usersCountry' => $walletUser->country->currencie->name
            ]
        );
    }


    // Verify ceilling

    public function getWalletBankCardCeilingProperty()
    {
        $data = [];
        foreach (BankCard::where('created_by', master()->id)
            ->whereDate('created_at', Carbon::today())->whereStatus(1)->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }
    public function getWalletBanksCeilingProperty()
    {
        $data = [];
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', master()->country->id)
            ->where('wallet_banks.created_by', master()->id)
            ->whereDate('wallet_banks.created_at', Carbon::today())->select('wallet_banks.*')->where('wallet_banks.status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletDepositsCeilingProperty()
    {

        $data = [];

        foreach (WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id',  master()->country->id)->where('wallet_deposits.created_by',  master()->id)
            ->whereDate('wallet_deposits.created_at', Carbon::today())->select('wallet_deposits.*')->where('wallet_deposits.status', 'SUCCESS')->get() as $deposit) {
            $data[] = $deposit->amount;
        }

        return array_sum($data);
    }

    public function getWalletIntersCeilingProperty()
    {
        $data = [];

        foreach (WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallets.country_id',  master()->country->id)->where('wallet_inters.created_by',  master()->id)
            ->whereDate('wallet_inters.created_at', Carbon::today())->select('wallet_inters.*')->where('wallet_inters.status', 'SUCCESS')->get() as $inter) {
            $data[] = $inter->amount;
        }

        return array_sum($data);
    }

    public function getWalletPaymentsCeilingProperty()
    {
        $data = [];

        foreach (WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id',  master()->country->id)->where('wallet_payments.created_by',  master()->id)
            ->whereDate('wallet_payments.created_at', Carbon::today())->select('wallet_payments.*')->where('wallet_payments.status', 'SUCCESS')->get() as $payment) {
            $data[] = $payment->amount;
        }

        return array_sum($data);
    }


    public function getWalletTransactionsCeilingProperty()
    {
        $data = [];

        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id',  master()->country->id)->where('wallet_transactions.created_by',  master()->id)
            ->whereDate('wallet_transactions.created_at', Carbon::today())->select('wallet_transactions.*')->where('wallet_transactions.status', 'SUCCESS')->get() as $transaction) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }

    public function getWalletPhoneCreditsCeilingProperty()
    {
        $data = [];


        foreach (WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', master()->country->id)->where('wallet_phone_credits.created_by', master()->id)
            ->whereDate('wallet_phone_credits.created_at', Carbon::today())->select('wallet_phone_credits.*')->where('wallet_phone_credits.status', 'SUCCESS')->get() as $phone) {
            $data[] = $phone->amount;
        }

        return array_sum($data);
    }

    public function getWalletReloadsCeilingProperty()
    {
        $data = [];

        foreach (WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id',  master()->country->id)->where('wallet_reloads.created_by',  master()->id)
            ->whereDate('wallet_reloads.created_at', Carbon::today())->select('wallet_reloads.*')->where('wallet_reloads.status', 'SUCCESS')->get() as $reload) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }

    public function getReloadUvTransactionProperty()
    {
        $walletUser = master();

        $payments = Provider::join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', $walletUser->country->id)
            ->where('providers.status', true)
            ->where('providers.provider_type_id',  1)
            ->where('providers.name', '!=', 'Wave')
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')->get();

        return $payments;
    }

    public function getWalletReloadsProperty()
    {

        $data = [];

        foreach (WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', master()->country->id)->where('wallet_reloads.status', 'SUCCESS')->whereDate('wallet_reloads.created_at', Carbon::today())->select('wallet_reloads.*')->get() as $reload) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }

    public function getWalletReloadsUserProperty()
    {

        $data = [];

        foreach (WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', master()->country->id)->where('wallet_reloads.created_by', master()->id)->where('wallet_reloads.status', 'SUCCESS')->whereDate('wallet_reloads.created_at', Carbon::today())->select('wallet_reloads.*')->get() as $reload) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }
}
