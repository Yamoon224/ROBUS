<?php

namespace App\Http\Livewire;

use App\Mail\UvSend;
use Livewire\Component;
use App\Models\Sid\SidUser;
use Livewire\WithPagination;
use App\Events\UserNotifEvent;
use App\Models\Banks\BankCard;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Events\AdminNotifEvent;
use App\Models\Costs\ReloadCost;
use App\Events\ChooseReloadEvent;
use App\Events\CreateReloadEvent;
use App\Events\UpdateReloadEvent;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use App\Models\Wallets\WalletBank;
use Illuminate\Support\Facades\DB;
use App\Models\Wallets\WalletInter;
use App\Events\UpdateAllWalletEvent;
use App\Models\Payments\Transaction;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Models\Invoices\InvoiceReload;
use App\Models\Payments\PaymentReload;
use App\Notifications\UserNotification;
use App\Notifications\AdminNotification;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use App\Models\Invoices\InvoiceTransaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Admin\MailController;

class Reloaduv extends Component
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


    public function addReload()
    {


        $check = WalletReload::whereId($this->relaod_uv_id)->first();
        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId($this->provider_reload_uv)->first();
        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf("%0.9s", str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf("%0.8s", str_shuffle($rt . $tr));
        // if(!$check) {
        $admins = SidUser::where('status', true)->where('is_admin', true)->where('country_id', Auth::guard('web')->user()->country->id)->get();
        // Verifier plafond days with piece
        $valeur_ceiling  =  $walletUser->silver_ceiling;
        //  Verifier transfers Som days
        $som_transfers = $this->WalletBanksCeiling + $this->WalletDepositsCeiling + $this->WalletIntersCeiling +  $this->WalletPaymentsCeiling
            + $this->WalletTransactionsCeiling + $this->WalletPhoneCreditsCeiling + $this->WalletReloadsCeiling + $this->WalletBankCardCeiling;

        if ($this->provider_reload_uv == 9) {

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

            if ($wallet_search == null  || $wallet_search->sidUser->status == false) {

                $this->resetField();
                return  $this->errorWalletAccount();

            } else if ($wallet_search->sidUser->is_distributer == true || $wallet_search->sidUser->is_under_distributer == true  || $wallet_search->sidUser->is_admin == true  || $wallet_search->sidUser->is_supervisor == true || $wallet_search->sidUser->is_company == true) { {

                    if ($som_transfers >= $valeur_ceiling) {
                        $this->emit('modal-hide', 'hide');
                        $this->resetField();
                        $this->emit('warning', 'Votre montant journier est atteint !');
                    } {

                        $val_amount = $this->relaod_uv_amout_robus;

                        if ($val_amount > $walletUser->wallet->amount) {

                            $this->resetField();
                            $this->emit('modal-hide', 'hide');
                            $this->emit('success', 'Verifier votre montant ! !');
                        } else {

                            $valeur_receive =  $this->convertAdminCommission($this->relaod_uv_amout_robus, $wallet_search);

                            // $amount_solde = $check->wallet->amount +  $commission;
                            $walletAmountSend = Wallet::where('wallet_id',  $this->account_reload_uv)->first();

                            $amount_solde_drawal = (float)$walletAmountSend->amount +  (float)$valeur_receive;

                            $amount_drawal =      $amount_solde_drawal;

                            // Send in compte Client
                            $wallet_search->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount+'  . $valeur_receive),
                            ]);


                            $amount_solde = (float)sabdy()->wallet->amount - (float)$this->relaod_uv_amout_robus;


                            $reload_unders =  WalletReload::create([
                                'wallet_id' =>   $walletUser->wallet->id,
                                'amount' => $this->relaod_uv_amout_robus,
                                'description'  => 'Rechargement de compte UV',
                                'reload_cost_id' => 1,
                                'created_by' => Auth::guard('web')->user()->id,
                                'provider_id'  =>  $provider->id,
                                'account_wallet'  => $this->account_reload_uv,
                                'amount_received'  => $valeur_receive,
                                'type_transaction' => 'Réequilibrage',
                                'type_received' => 'Transfert',
                                'phone'  => $this->number_reload_uv ?? null,
                                'status'  => "SUCCESS",
                                'amount_solde'  => $amount_solde ?? null,
                                'amount_solde_drawal' => $amount_drawal ?? null
                            ]);



                            $commission =  ($reload_unders->amount);

                            $walletUser->wallet->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount-'  .  $commission),
                            ]);


                            $paymentRealod = PaymentReload::create([
                                'wallet_reload_id' => $reload_unders->id,
                                'sid_user_id' => Auth::guard('web')->user()->id,
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
                                'company_id' => Auth::guard('web')->user()->company->id,
                                'description' => $reload_unders->description,
                                'status'  =>    $reload_unders->status,
                                'created_by'  =>   Auth::guard('web')->user()->id,
                            ]);

                            // if ($walletUser->country->code == "CI") {

                            //     $message = 'Vous avez effectué un transafers de ' . $reload_unders->amount .  $walletUser->wallet->currencie->code .  'par '
                            //         . $reload_unders->provider->name  .  ' a '
                            //         . $reload_unders->created_at . ' votre demande est en-cours de validation ' . 'Robus vous remercie';

                            //     $encode = substr(Auth::guard('web')->user()->phone,  -10);

                            //     Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                            // }

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

                                $this->resetField();
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

                                $this->resetField();
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
                    }
                }
            }else if($wallet_search->sidUser->is_custumers == true ) {
                
                if ($som_transfers >= $valeur_ceiling) {
                    $this->emit('modal-hide', 'hide');
                    $this->resetField();
                    $this->emit('warning', 'Votre montant journier est atteint !');
                } {

                    $val_amount = $this->relaod_uv_amout_robus;

                    if ($val_amount > $walletUser->wallet->amount) {

                        $this->resetField();
                        $this->emit('modal-hide', 'hide');
                        $this->emit('success', 'Verifier votre montant ! !');
                    } else {

                        $valeur_receive =  $this->convertAdminCommission($this->relaod_uv_amout_robus, $wallet_search);

                        // $amount_solde = $check->wallet->amount +  $commission;
                        $walletAmountSend = Wallet::where('wallet_id',  $this->account_reload_uv)->first();

                        $amount_solde_drawal = (float)$walletAmountSend->amount +  (float)$valeur_receive;

                        $amount_drawal =      $amount_solde_drawal;

                        // Send in compte Client
                        $wallet_search->update([
                            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                            'sid_user_relod' => Auth::guard('web')->user()->id,
                            'amount' => DB::raw('amount+'  . $valeur_receive),
                        ]);


                        $amount_solde = (float)sabdy()->wallet->amount - (float)$this->relaod_uv_amout_robus;


                        $walletTransaction =  WalletTransaction::create(
                            [
                                'wallet_id' =>   $walletUser->wallet->id,
                                'amount' => $this->relaod_uv_amout_robus,
                                'description'  => "remboursement",
                                // 'transaction_cost_id' => $percent_robus->id,
                                // 'phone'   => sabdy()->country->indecative . $this->transaction_phone,
                                'created_by' => Auth::guard('web')->user()->id,
                                'provider_id'  =>  $provider->id,
                                'num_wallet'  => $this->account_reload_uv,
                                'costs_amount' => 0,
                                // 'name_complet' => $this->name_custumers ?? null,
                                // 'num_piece' => $this->num_piece_custumers ?? null,
                                // 'validated_date' => $this->date_piece ?? null,
                                'choose_type_transaction' => 1,
                                'amount_received'  => $valeur_receive,
                                'type_transaction' => 'Réequilibrage',
                                'type_received' => 'Transfert',
                                'status'  => "SUCCESS",
                                'amount_solde'  => $amount_solde ?? null,
                                'amount_solde_drawal' => $amount_drawal ?? null
                            ]
                        );


                        $commission =  ($walletTransaction->amount);

                        $walletUser->wallet->update([
                            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                            'sid_user_relod' => Auth::guard('web')->user()->id,
                            'amount' => DB::raw('amount-'  .  $commission),
                        ]);

                       
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

                        // if ($walletUser->country->code == "CI") {

                        //     $message = 'Vous avez effectué un transafers de ' . $reload_unders->amount .  $walletUser->wallet->currencie->code .  'par '
                        //         . $reload_unders->provider->name  .  ' a '
                        //         . $reload_unders->created_at . ' votre demande est en-cours de validation ' . 'Robus vous remercie';

                        //     $encode = substr(Auth::guard('web')->user()->phone,  -10);

                        //     Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                        // }

                        $msg_title = 'Achat d\'UV';
                        $msg = 'Votre demande de rechargement d\'UV ';
                        $wallet_search->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                        $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                        $notifs = $walletUser->unreadNotifications;
                        $notifs = $wallet_search->sidUser->unreadNotifications;
                        event(new CreateReloadEvent($walletTransaction));
                        Notification::send($admins, new AdminNotification('Demande de rechargement UV de ' . ' ' . $walletTransaction->wallet->sidUser->firstname));
                        if ($admins->count() > 0) {


                            foreach ($admins  as $user) {

                                $admin_single = SidUser::find($user->id);

                                $notifs = $admin_single->unreadNotifications;
                                event(new AdminNotifEvent($notifs));
                            }

                            $this->resetField();
                            $this->emit('modal-hide', 'hide');
                            $this->emit('success', 'Rechargement effectué !');
                            if ($walletUser->email != null) {

                                $notify = new MailController;
                                $notify->newReloadUV($walletTransaction);
                            }

                            if ($wallet_search->sidUser->email != null) {

                                Mail::to($wallet_search->sidUser->email)->send(new UvSend('Votre demande d\'achat d\'UV de ' . $walletTransaction->amount_received . ' ' . $wallet_search->currencie->code  . ' chez  : '  . $walletUser->firstname . $walletUser->lastname . ' ' . ' Le ' .  $walletTransaction->created_at  . ' Validé avec succes'));
                            }
                        } else {

                            $this->resetField();
                            $this->emit('modal-hide', 'hide');
                            $this->emit('success', 'Rechargement effectué !');
                            if ($walletUser->email != null) {

                                $notify = new MailController;
                                $notify->newReloadUV($walletTransaction);
                            }

                            if ($wallet_search->sidUser->email != null) {

                                Mail::to($wallet_search->sidUser->email)->send(new UvSend('Votre demande d\'achat d\'UV de ' . $walletTransaction->amount_received . ' ' . $wallet_search->currencie->code  . ' chez  : '  . $walletUser->firstname . $walletUser->lastname . ' ' . ' Le ' .  $walletTransaction->created_at  . ' Validé avec succes'));
                            }
                        }
                    }
                }
            } else  return  $this->errorWalletAccountDist();
        } else {
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('error', 'Selectionnez une valeur  !');
        }

    }

    private function createMobileWalletTransactionRobus($amount, $provider, $amount_solde, $wallet_user, $user, $wallet_info , $amount_drawal)
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

        return $walletTransaction ;

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

    public function ChooseUpdate($id)
    {
        $walletUpdate = WalletReload::findOrFail($id);
        $walletUpdate->update([
            'updated_by' => Auth::guard('web')->user()->id,
        ]);
        event(new ChooseReloadEvent($walletUpdate));
        $this->emit('info', 'Vous avez choisir');
    }

    public function updateReload()
    {
        $check = WalletReload::whereId($this->relaod_uv_id)->first();
        $wallet_user_admin = Auth::guard('web')->user();
        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf("%0.9s", str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf("%0.8s", str_shuffle($rt . $tr));
        $admins = SidUser::where('is_distributer', true)->orWhere('is_under_distributer', true)->orWhere('is_admin', true)->where('country_id', $wallet_user_admin->country->id)->get();
        if ($check) {

            $commission_reload =  (($check->amount)  -  ($check->amount * $check->reloadCost->percent));

            $commissions =  ($check->amount * $check->reloadCost->percent);



            if ($check->updated_by ==  Auth::guard('web')->user()->id) {

                $percent_robus = ReloadCost::where('status', true)->first();

                $check->update([
                    'updated_by' => Auth::guard('web')->user()->id,
                    'status'  => $this->reload_uv_status,
                ]);

                if ($check->status == "SUCCESS") {



                    $check->update([
                        'commissions' => DB::raw('commissions+'  . $commissions),

                    ]);

                    $paymentRealod = PaymentReload::create([
                        'wallet_reload_id' => $check->id,
                        'sid_user_id' => Auth::guard('web')->user()->id,
                        'transaction_id' => 'ROBUS_' . $check->provider->name . '_' . $transactionCode,
                        'operator_transaction_name' =>  $check->provider->name,
                        'trans_comp_id' => 'ROBUS_' . $transactionCompiCode,
                        'pay_amount' =>  $check->amount,
                        'fee_amount' =>   $check->reloadCost->percent,
                        'received_amount' => ($check->amount)  - ($check->amount * $check->reloadCost->percent),
                        'status'  =>    $check->status,
                    ]);

                    InvoiceReload::create([
                        'title' => "Rechargement de compte",
                        'wallet_reload_id' => $check->id,
                        'payment_reload_id' =>  $paymentRealod->id,
                        'company_id' => Auth::guard('web')->user()->company->id,
                        'description' => $check->description,
                        'status'  =>    $check->status,
                        'created_by'  =>   Auth::guard('web')->user()->id,
                    ]);

                    if (Auth::guard('web')->user()->whereStatus(true)->where('is_admin', true)) {

                        Auth::guard('web')->user()->wallet->update([
                            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                            'sid_user_relod' => Auth::guard('web')->user()->id,
                            'amount' => DB::raw('amount+'  . $commission_reload),
                        ]);
                    }

                    if ($check->wallet->sidUser->whereStatus(true)->where('is_distributer', true)  || $check->wallet->sidUser->whereStatus(true)->where('is_under_distributer', true)) {


                        // $percent_merchant = ($percent_robus->percent - $percent_robus->percent_merchands);

                        $commission_merchant =  ($check->amount * $percent_robus->percent_merchands);

                        $check->update([
                            'commissions' => DB::raw('commissions+'  . $commission_merchant),
                        ]);
                    }

                    if ($check->wallet->sidUser->country->code == "CI") {

                        $message = 'Vous demande de transaction de ' . $check->amount .  $check->wallet->currencie->code . ' ' . 'par '
                            . $check->provider->name  .  ' a '
                            . $check->updated_at . ' a été accepté avec success ' . 'Robus vous remercie';

                        $encode = substr($check->wallet->sidUser->phone,  -10);

                        Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                    }
                    $msg_title = 'Achat d\'UV';
                    $msg = 'Votre demande de rechargement validez';
                    // $user_search->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                    $check->wallet->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                    // $notifs = $user_search->unreadNotifications;
                    event(new UpdateReloadEvent($check));
                    // event(new UserNotifEvent($notifs));
                    Notification::send($admins, new AdminNotification('Demande Validez'));
                    $admin = SidUser::where('status', true)->where('is_admin', true)->first();
                    $notifs = $admin->unreadNotifications;
                    event(new AdminNotifEvent($notifs));
                    $this->resetField();
                    $this->emit('modal-hide', 'hide');
                    $this->emit('success', 'Rechargement Accepte !');
                    if ($check->wallet->sidUser->email != null) {
                        $notify = new MailController;
                        $notify->newReloadUV($check);
                    }
                } else if ($check->status == "ERROR") {

                    PaymentReload::create([
                        'wallet_reload_id' => $check->id,
                        'sid_user_id' => Auth::guard('web')->user()->id,
                        'transaction_id' => 'RB_' . $check->provider->name . '_' . $transactionCode,
                        'operator_transaction_name' =>  $check->provider->name,
                        'trans_comp_id' => 'RB_' . $transactionCompiCode,
                        'pay_amount' =>  $check->amount,
                        'fee_amount' =>   0.0,
                        'received_amount' => 0.0,
                        'status'  =>    $check->status,
                    ]);
                    if ($check->wallet->sidUser->country->code == "CI") {

                        $message = 'Vous demande de transaction de ' . $check->amount .  $check->wallet->currencie->code . ' ' . 'par '
                            . $check->provider->name  .  ' a '
                            . $check->updated_at . ' a été accepté avec success ' . 'Robus vous remercie';

                        $encode = substr($check->wallet->sidUser->phone,  -10);

                        Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                    }
                    $msg_title = 'Achat d\'UV';
                    $msg = 'Votre demande de rechargement refusez';
                    // $user_search->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                    $check->wallet->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                    // $notifs = $user_search->unreadNotifications;
                    event(new UpdateReloadEvent($check));
                    // event(new UserNotifEvent($notifs));
                    Notification::send($admins, new AdminNotification('Demande refusez'));
                    $admin = SidUser::where('status', true)->where('is_admin', true)->first();
                    $notifs = $admin->unreadNotifications;
                    event(new AdminNotifEvent($notifs));
                    $this->resetField();
                    $this->emit('modal-hide', 'hide');
                    $this->emit('error', 'Rechargement Refusé !');
                    if ($check->wallet->sidUser->email != null) {
                        $notify = new MailController;
                        $notify->newReloadUV($check);
                    }
                }
            } else {
                $this->resetField();
                $this->emit('modal-hide', 'hide');
                $this->emit('error', 'Aucune modification');
            }
        } else {
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('error', 'Aucune modification');
        }
    }

    public function resetField()
    {

        $this->relaod_uv_amout = '';
        $this->reload_uv_description = '';
        $this->reload_uv_status = '';
        $this->provider_reload_uv = null;
        $this->account_reload_uv = '';
        $this->number_reload_uv = '';
        $this->relaod_uv_amout_robus = '';
        $this->emit('modal-hide', 'hide');
    }

    public function cancelReloadCardField()
    {

        $this->relaod_uv_amout = '';
        $this->reload_uv_description = '';
        $this->reload_uv_status = '';
        $this->provider_reload_uv = null;
        $this->account_reload_uv = '';
        $this->number_reload_uv = '';
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
        $this->emit('warning', 'Montant min 2000 !' . Auth::guard('web')->user()->country->currencie->name);
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

    public function errorNumberValidated()
    {

        $this->relaod_uv_amout = '';
        $this->reload_uv_description = '';
        $this->reload_uv_status = '';
        $this->provider_reload_uv = '';
        $this->account_reload_uv = '';
        $this->number_reload_uv = '';
        $this->relaod_uv_amout_robus = '';
        $this->emit('modal-hide', 'hide');
        $this->emit('warning', 'Le numero doit corespondre a la selection !');
    }

    public function render()
    {

        $realod_search =  WalletReload::query();

        $realod =  WalletReload::get();

        $walletUser = Auth::guard('web')->user();

        $wallet = Wallet::query();

        $wallets = Wallet::join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_distributer', true)
            ->orWhere('sid_users.is_under_distributer', true)
            ->where('wallets.status', true)
            ->Where('wallets.country_id', $walletUser->country->id)
            ->get();

        $sid_search = SidUser::query();

        $wallet_info_user = null;


        if ($walletUser->is_under_distributer == true) {

            if ($this->searchRealod != "") {

                $this->resetPage();

                $realod_search->orwhereHas(
                    'paymentReload',
                    function ($query) {
                        $query->where('operator_transaction_name', 'like', '%' . $this->searchRealod . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchRealod   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchRealod  . '%');
                    }
                )
                    ->orWhere("wallet_reloads.amount", "like",  "%" . $this->searchRealod . "%")
                    ->orWhere("wallet_reloads.phone", "like",  "%" . $this->searchRealod . "%")
                    ->orWhere("wallet_reloads.account_wallet", "like",  "%" . $this->searchRealod . "%");


                // $this->emit('menu-reload-active');
            }


            if ($this->statusFilter != "") {
                $realod_search->where('wallet_reloads.status', $this->statusFilter);
                // $this->emit('menu-reload-active');
            }


            if ($this->account_reload_uv != '') {


                foreach ($wallets  as $wallet_user) {

                    $wallet_search_user = $wallet_user->where('wallet_id', $this->account_reload_uv)->first();

                    if ($wallet_search_user == null) {

                        $wallet_info_user = "N'existe pas chez nous";
                    } else {
                        $wallet_info_user = $wallet_search_user->sidUser->firstname ?? null . ' ' .  $wallet_search_user->sidUser->lastname ?? null;
                    }
                }
            }


            return view(
                'livewire.reloaduv',
                [
                    'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
                    'WalletReloads' => $this->WalletReloadsUser,
                    'reload_error' => WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_reloads.updated_by', Auth::guard('web')->user()->id)->where('wallet_reloads.status', 'ERROR')->whereDate('wallet_reloads.created_at', Carbon::today())->select('wallet_reloads.*')->count(),
                    'reload_success' =>   WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_reloads.updated_by', Auth::guard('web')->user()->id)->where('wallet_reloads.status', 'SUCCESS')->whereDate('wallet_reloads.created_at', Carbon::today())->select('wallet_reloads.*')->count(),
                    'reload_pendding' =>   WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_reloads.updated_by', Auth::guard('web')->user()->id)->where('wallet_reloads.status', 'PENDING')->whereDate('wallet_reloads.created_at', Carbon::today())->select('wallet_reloads.*')->count(),
                    'reaload_uv' => $realod_search->join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)
                        ->where('wallet_reloads.created_by', Auth::guard('web')->user()->id)
                        ->select('wallet_reloads.*')
                        ->orderBy('wallet_reloads.created_at', 'desc')->paginate(20),
                    'countrie_number_local' => Auth::guard('web')->user()->country->indecative,
                    'wallet_search' => $wallet->where("wallet_id", "like",  "%" . $this->account_reload_uv . "%")
                        ->where('country_id',  $walletUser->country->id)->where('status', true)->get(),
                    'number_search' => $sid_search->where("phone", "like",  "%" . $this->number_reload_uv . "%")
                        ->where('country_id',  $walletUser->country->id)->where('is_under_distributer', true)->get(),
                    'amount_reload' => ReloadCost::where('status', true)->first(),
                    'providers' =>  $this->ReloadUvTransaction,
                    'withdrawal_robus' => Provider::whereStatus(true)
                        ->whereId(9)
                        ->get(),
                    'wallet_info_searchs' => $wallet_info_user,
                    'usersCountry' => Auth::guard('web')->user()->country->currencie->name,

                ]
            );
        } elseif ($walletUser->is_distributer == true) {


            if ($this->searchRealod != "") {

                $this->resetPage();

                $realod_search->orwhereHas(
                    'paymentReload',
                    function ($query) {
                        $query->where('operator_transaction_name', 'like', '%' . $this->searchRealod . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchRealod   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchRealod  . '%');
                    }
                )
                    ->orWhere("wallet_reloads.amount", "like",  "%" . $this->searchRealod . "%")
                    ->orWhere("wallet_reloads.phone", "like",  "%" . $this->searchRealod . "%")
                    ->orWhere("wallet_reloads.account_wallet", "like",  "%" . $this->searchRealod . "%");


                // $this->emit('menu-reload-active');
            }


            if ($this->statusFilter != "") {
                $realod_search->where('wallet_reloads.status', $this->statusFilter);
                // $this->emit('menu-reload-active');
            }

            if ($this->account_reload_uv != '') {


                foreach ($wallets  as $wallet_user) {

                    $wallet_search_user = $wallet_user->where('wallet_id', $this->account_reload_uv)->first();

                    if ($wallet_search_user == null) {

                        $wallet_info_user = "N'existe pas chez nous";
                    } else {
                        $wallet_info_user = $wallet_search_user->sidUser->firstname ?? null . ' ' .  $wallet_search_user->sidUser->lastname ?? null;
                    }
                }
            }

            return view(
                'livewire.reloaduv',
                [
                    'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
                    'WalletReloads' => $this->WalletReloads,
                    'reload_error' => WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_reloads.status', 'ERROR')->whereDate('wallet_reloads.created_at', Carbon::today())->select('wallet_reloads.*')->count(),
                    'reload_success' =>   WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_reloads.status', 'SUCCESS')->whereDate('wallet_reloads.created_at', Carbon::today())->select('wallet_reloads.*')->count(),
                    'reload_pendding' =>   WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_reloads.status', 'PENDING')->whereDate('wallet_reloads.created_at', Carbon::today())->select('wallet_reloads.*')->count(),
                    'reaload_uv' => $realod_search->join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)
                        ->where('wallet_reloads.created_by', Auth::guard('web')->user()->id)
                        ->select('wallet_reloads.*')
                        ->orderBy('wallet_reloads.created_at', 'desc')->paginate(20),
                    'countrie_number_local' => Auth::guard('web')->user()->country->indecative,
                    'wallet_search' => $wallet->where("wallet_id", "like",  "%" . $this->account_reload_uv . "%")
                        ->where('country_id',  $walletUser->country->id)->where('status', true)->get(),
                    'number_search' => $sid_search->where("phone", "like",  "%" . $this->number_reload_uv . "%")
                        ->where('country_id',  $walletUser->country->id)->get(),
                    'amount_reload' => ReloadCost::where('status', true)->first(),
                    'providers' =>  $this->ReloadUvTransaction,
                    'withdrawal_robus' => Provider::whereStatus(true)
                        ->whereId(9)
                        ->get(),
                    'wallet_info_searchs' => $wallet_info_user,
                    'usersCountry' => Auth::guard('web')->user()->country->currencie->name,

                ]
            );
        } else {
            if ($this->searchRealod != "") {

                $this->resetPage();

                $realod_search->orwhereHas(
                    'paymentReload',
                    function ($query) {
                        $query->where('operator_transaction_name', 'like', '%' . $this->searchRealod . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchRealod   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchRealod  . '%');
                    }
                )
                    ->orWhere("wallet_reloads.amount", "like",  "%" . $this->searchRealod . "%")
                    ->orWhere("wallet_reloads.phone", "like",  "%" . $this->searchRealod . "%")
                    ->orWhere("wallet_reloads.account_wallet", "like",  "%" . $this->searchRealod . "%");


                // $this->emit('menu-reload-active');
            }


            if ($this->statusFilter != "") {
                $realod_search->where('wallet_reloads.status', $this->statusFilter);
                // $this->emit('menu-reload-active');
            }

            if ($this->account_reload_uv != '') {


                foreach ($wallets  as $wallet_user) {

                    $wallet_search_user = $wallet_user->where('wallet_id', $this->account_reload_uv)->first();

                    if ($wallet_search_user == null) {

                        $wallet_info_user = "N'existe pas chez nous";
                    } else {
                        $wallet_info_user = $wallet_search_user->sidUser->firstname ?? null . ' ' .  $wallet_search_user->sidUser->lastname ?? null;
                    }
                }
            }

            return view(
                'livewire.reloaduv',
                [
                    'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
                    'WalletReloads' => $this->WalletReloads,
                    'reload_error' => WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_reloads.status', 'ERROR')->whereDate('wallet_reloads.created_at', Carbon::today())->select('wallet_reloads.*')->count(),
                    'reload_success' =>   WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_reloads.status', 'SUCCESS')->whereDate('wallet_reloads.created_at', Carbon::today())->select('wallet_reloads.*')->count(),
                    'reload_pendding' =>   WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_reloads.status', 'PENDING')->whereDate('wallet_reloads.created_at', Carbon::today())->select('wallet_reloads.*')->count(),
                    'reaload_uv' => $realod_search->join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->select('wallet_reloads.*')->orderBy('wallet_reloads.created_at', 'desc')->paginate(20),
                    'countrie_number_local' => Auth::guard('web')->user()->country->indecative,
                    'wallet_search' => $wallet->where("wallet_id", "like",  "%" . $this->account_reload_uv . "%")
                        ->where('country_id',  $walletUser->country->id)->where('status', true)->get(),
                    'number_search' => $sid_search->where("phone", "like",  "%" . $this->number_reload_uv . "%")
                        ->where('country_id',  $walletUser->country->id)->get(),
                    'amount_reload' => ReloadCost::where('status', true)->first(),
                    'providers' =>  $this->ReloadUvTransaction,
                    'withdrawal_robus' => Provider::whereStatus(true)
                        ->whereId(9)
                        ->get(),
                    'wallet_info_searchs' => $wallet_info_user,
                    'usersCountry' => Auth::guard('web')->user()->country->currencie->name,

                ]
            );
        }
    }


    // Verify ceilling

    public function getWalletBankCardCeilingProperty()
    {
        $data = [];
        foreach (BankCard::where('created_by', Auth::guard('web')->user()->id)
            ->whereDate('created_at', Carbon::today())->whereStatus(1)->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }
    public function getWalletBanksCeilingProperty()
    {
        $data = [];
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->where('wallet_banks.created_by', Auth::guard('web')->user()->id)
            ->whereDate('wallet_banks.created_at', Carbon::today())->select('wallet_banks.*')->where('wallet_banks.status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletDepositsCeilingProperty()
    {

        $data = [];

        foreach (WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id',  Auth::guard('web')->user()->country->id)->where('wallet_deposits.created_by',  Auth::guard('web')->user()->id)
            ->whereDate('wallet_deposits.created_at', Carbon::today())->select('wallet_deposits.*')->where('wallet_deposits.status', 'SUCCESS')->get() as $deposit) {
            $data[] = $deposit->amount;
        }

        return array_sum($data);
    }

    public function getWalletIntersCeilingProperty()
    {
        $data = [];

        foreach (WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallets.country_id',  Auth::guard('web')->user()->country->id)->where('wallet_inters.created_by',  Auth::guard('web')->user()->id)
            ->whereDate('wallet_inters.created_at', Carbon::today())->select('wallet_inters.*')->where('wallet_inters.status', 'SUCCESS')->get() as $inter) {
            $data[] = $inter->amount;
        }

        return array_sum($data);
    }

    public function getWalletPaymentsCeilingProperty()
    {
        $data = [];

        foreach (WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
            ->where('wallets.country_id',  Auth::guard('web')->user()->country->id)->where('wallet_payments.created_by',  Auth::guard('web')->user()->id)
            ->whereDate('wallet_payments.created_at', Carbon::today())->select('wallet_payments.*')->where('wallet_payments.status', 'SUCCESS')->get() as $payment) {
            $data[] = $payment->amount;
        }

        return array_sum($data);
    }


    public function getWalletTransactionsCeilingProperty()
    {
        $data = [];

        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id',  Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by',  Auth::guard('web')->user()->id)
            ->whereDate('wallet_transactions.created_at', Carbon::today())->select('wallet_transactions.*')->where('wallet_transactions.status', 'SUCCESS')->get() as $transaction) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }

    public function getWalletPhoneCreditsCeilingProperty()
    {
        $data = [];


        foreach (WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_phone_credits.created_by', Auth::guard('web')->user()->id)
            ->whereDate('wallet_phone_credits.created_at', Carbon::today())->select('wallet_phone_credits.*')->where('wallet_phone_credits.status', 'SUCCESS')->get() as $phone) {
            $data[] = $phone->amount;
        }

        return array_sum($data);
    }

    public function getWalletReloadsCeilingProperty()
    {
        $data = [];

        foreach (WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id',  Auth::guard('web')->user()->country->id)->where('wallet_reloads.created_by',  Auth::guard('web')->user()->id)
            ->whereDate('wallet_reloads.created_at', Carbon::today())->select('wallet_reloads.*')->where('wallet_reloads.status', 'SUCCESS')->get() as $reload) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }

    public function getReloadUvTransactionProperty()
    {
        $walletUser = Auth::guard('web')->user();

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
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_reloads.status', 'SUCCESS')->whereDate('wallet_reloads.created_at', Carbon::today())->select('wallet_reloads.*')->get() as $reload) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }

    public function getWalletReloadsUserProperty()
    {

        $data = [];

        foreach (WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_reloads.created_by', Auth::guard('web')->user()->id)->where('wallet_reloads.status', 'SUCCESS')->whereDate('wallet_reloads.created_at', Carbon::today())->select('wallet_reloads.*')->get() as $reload) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }
}
