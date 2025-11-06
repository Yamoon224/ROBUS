<?php

namespace App\Http\Livewire;

use App\Mail\UvSend;
use Livewire\Component;
use App\Models\Sid\SidUser;
use Livewire\WithPagination;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Models\Costs\ReloadCost;
use App\Events\ChooseReloadEvent;
use App\Events\CreateReloadEvent;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use Illuminate\Support\Facades\DB;
use App\Models\Wallets\WalletReload;
use App\Models\Payments\TransactionCommission;
use App\Models\Wallets\WalletAdmin;
use App\Models\Wallets\WalletCommission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Notifications\UserNotification;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Notification;
use App\Http\Services\TransfertServiceBackend;
use App\Http\Services\NotificationServiceBackend;

class Reloaduv extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchRealod = "";
    public $searchRealodAcompte = "";
    public $statusFilter = "";
    public $statusFilterAcompte = "";


    // Reload UV
    public $relaod_uv_amout;
    public $relaod_amount_commission;
    public $relaod_uv_id;
    public $reload_uv_description;
    public $reload_uv_status;
    public $reload_cost_uv_reload;
    public $provider_reload_uv;
    public $providerCommission;
    public $providerAllCommission;
    public $providerAdmin;
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


                        $reload_unders = app(TransfertServiceBackend::class)->createWalletReload(
                            $walletUser,
                            $this->relaod_uv_amout_robus,
                            $provider,
                            $this->account_reload_uv,
                            $valeur_receive,
                            $this->number_reload_uv,
                            $amount_solde,
                            $amount_drawal
                        );


                        $commission =  ($reload_unders->amount);

                        $walletUser->wallet->update([
                            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                            'sid_user_relod' => Auth::guard('web')->user()->id,
                            'amount' => DB::raw('amount-'  .  $commission),
                        ]);


                        $paymentRealod = app(TransfertServiceBackend::class)
                            ->createWalletPaymentReload(
                                $reload_unders,
                                $transactionCode,
                                $transactionCompiCode
                            );

                        app(TransfertServiceBackend::class)
                            ->createWalletPaymentInvoiceReload(
                                $reload_unders,
                                $paymentRealod
                            );


                        if ($walletUser->country->code == "CI") {

                            $message = 'Vous avez effectué un transafers de ' . $reload_unders->amount .  $walletUser->wallet->currencie->code .  'par '
                                . $reload_unders->provider->name  .  ' a '
                                . $reload_unders->created_at . ' votre demande est en-cours de validation ' . 'Robus vous remercie';

                            $encode = substr(Auth::guard('web')->user()->phone,  -10);

                            Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                        }

                        $msg_title = 'Achat d\'UV';
                        $msg = 'Votre demande de rechargement d\'UV ';
                        $wallet_search->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                        $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                        $notifs = $walletUser->unreadNotifications;
                        $notifs = $wallet_search->sidUser->unreadNotifications;
                        event(new CreateReloadEvent($reload_unders));
                        Notification::send($admins, new AdminNotification('Demande de rechargement UV de ' . ' ' . $reload_unders->wallet->sidUser->firstname));
                        if ($admins->count() > 0) {
                            app(NotificationServiceBackend::class)->notifyAdmins($admins);
                        }

                        if ($walletUser->email) {
                            app(NotificationServiceBackend::class)->sendMailReloadUVNotification($reload_unders);
                        }
                        if ($wallet_search->sidUser->email != null) {
                            Mail::to($wallet_search->sidUser->email)->send(new UvSend('Votre demande d\'achat d\'UV de ' . $reload_unders->amount_received . ' ' . $wallet_search->currencie->code  . ' chez  : '  . $walletUser->firstname . $walletUser->lastname . ' ' . ' Le ' .  $reload_unders->created_at  . ' Validé avec succes'));
                        }
                    }
                }
            } else if ($wallet_search->sidUser->is_custumers == true) {
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


                    $walletTransaction = app(TransfertServiceBackend::class)->createWalletTransactionReloadReload(
                        $walletUser,
                        $this->relaod_uv_amout_robus,
                        $provider,
                        $this->account_reload_uv,
                        $valeur_receive,
                        $amount_solde,
                        $amount_drawal
                    );


                    $commission =  ($walletTransaction->amount);

                    $walletUser->wallet->update([
                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                        'sid_user_relod' => Auth::guard('web')->user()->id,
                        'amount' => DB::raw('amount-'  .  $commission),
                    ]);


                    $transaction = app(TransfertServiceBackend::class)->createWalletTReloadInvoiceReload(
                        $walletTransaction,
                        $transactionCode,
                        $transactionCompiCode
                    );

                    app(TransfertServiceBackend::class)->createWalletTranloadInvoiceReload(
                        $walletTransaction,
                        $transaction
                    );
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
                    event(new CreateReloadEvent($walletTransaction));
                    Notification::send($admins, new AdminNotification('Demande de rechargement UV de ' . ' ' . $walletTransaction->wallet->sidUser->firstname));
                    if ($admins->count() > 0) {
                        app(NotificationServiceBackend::class)->notifyAdmins($admins);
                    }

                    if ($walletUser->email) {
                        app(NotificationServiceBackend::class)->sendMailReloadUVNotification($reload_unders);
                    }
                    if ($wallet_search->sidUser->email != null) {
                        Mail::to($wallet_search->sidUser->email)->send(new UvSend('Votre demande d\'achat d\'UV de ' . $reload_unders->amount_received . ' ' . $wallet_search->currencie->code  . ' chez  : '  . $walletUser->firstname . $walletUser->lastname . ' ' . ' Le ' .  $reload_unders->created_at  . ' Validé avec succes'));
                    }
                }
            } else  return  $this->errorWalletAccountDist();
        } else {
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('error', 'Selectionnez une valeur  !');
        }
    }


    public function addReloadCommission()
    {

        $walletUser = Auth::guard('web')->user();
        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf("%0.9s", str_shuffle($rt . $tr));
        $admins = SidUser::where('status', true)->where('is_admin', true)->where('country_id', Auth::guard('web')->user()->country->id)->get();

        if ($this->providerAllCommission) {

            $this->validate([
                'relaod_amount_commission' => 'required',
            ], [
                'relaod_amount_commission.required' => 'Votre montant est obligatoire',

            ]);

            $val_amount = $this->relaod_amount_commission;

            if ($val_amount > $walletUser->wallet->amount) {

                $this->resetField();
                $this->emit('modal-hide', 'hide');
                $this->emit('success', 'Verifier votre montant ! !');
            } else {


                if ($this->providerAllCommission == 1) {

                    $wallet = WalletCommission::first();

                    $amountBefore = $wallet ? (float) $wallet->amount : 0;

                    $walletAfter = $wallet ? (float) $wallet->amount  +  (float) $val_amount : 0;

                    $wallet->update([
                        'amount' => DB::raw('amount+'  . $val_amount),
                    ]);
                } else {

                    $wallet = WalletAdmin::first();
                    $amountBefore = $wallet ? (float) $wallet->amount : 0;
                    $walletAfter = $wallet ? (float) $wallet->amount  +  (float) $val_amount : 0;
                    $wallet->update([
                        'amount' => DB::raw('amount+'  . $val_amount),
                    ]);
                }

                $amount_solde = (float)sabdy()->wallet->amount - (float)$this->relaod_uv_amout_robus;


                $walletTransaction = TransactionCommission::create(
                    [
                        'pay_amount' => $val_amount,
                        'sid_user_id' => Auth::guard('web')->user()->id,
                        'description' => $this->providerAllCommission == 1
                            ? 'Rechargement compte commission par ' . sabdy()->firstname
                            : 'Rechargement compte admin ' . sabdy()->firstname,
                        'amount_received'  => $val_amount,
                        'type_transaction' =>  $this->providerAllCommission == 1 ? 'Commission' : 'Admin',
                        'status'  => "SUCCESS",
                        "transaction_id" => "Robus_" . $transactionCode,
                        "operator_transaction_name" => "Robus_" . $transactionCode,
                        'amount_solde'  => $amount_solde ?? null,
                        'amount_solde_after' => $walletAfter ?? null,
                        'amount_solde_befor' => $amountBefore ?? null,
                    ]
                );

                $walletUser->wallet->update([
                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                    'sid_user_relod' => Auth::guard('web')->user()->id,
                    'amount' => DB::raw('amount-'  .  $val_amount),
                ]);

                $msg_title = 'Rechargement de compte';
                $msg = 'Votre demande de réchargement de compte ';
                $walletUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                Notification::send($admins, new AdminNotification('Demande de réchargement de ' . ' ' . $walletTransaction->sidUser->firstname));
                if ($admins->count() > 0) {
                    app(NotificationServiceBackend::class)->notifyAdmins($admins);
                }

                if ($walletUser->email) {
                    app(NotificationServiceBackend::class)->sendMailReloadAcompteCommissionNotification($walletTransaction);
                }

                $this->emit('modal-hide', 'hide');
                $this->cancelReloadAcompteField();
            }
        } else {
            $this->emit('modal-hide', 'hide');
            $this->cancelReloadAcompteField();
            $this->emit('error', 'Selectionnez une valeur  !');
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

    public function cancelReloadAcompteField()
    {

        $this->relaod_uv_amout = '';
        $this->reload_uv_description = '';
        $this->relaod_amount_commission = '';
        $this->providerCommission = null;
        $this->providerAdmin = null;
        $this->reload_uv_status = '';
        $this->provider_reload_uv = null;
        $this->account_reload_uv = '';
        $this->number_reload_uv = '';
        $this->relaod_uv_amout_robus = '';
        $this->emit('menu-reloadAcompte-active');
    }

    public function cancelReloadCardField()
    {

        $this->relaod_uv_amout = '';
        $this->reload_uv_description = '';
        $this->relaod_amount_commission = '';
        $this->providerCommission = null;
        $this->providerAdmin = null;
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
        $realod_acoumpte_search =  TransactionCommission::query();

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

            abort(403);
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
            }

            if ($this->searchRealodAcompte != "") {

                $this->resetPage();

                $realod_acoumpte_search->where("transaction_commissions.type_transaction", "like",  "%" . $this->searchRealodAcompte . "%")
                    ->orWhere("transaction_commissions.pay_amount", "like",  "%" . $this->searchRealodAcompte . "%")
                    ->orWhere("transaction_commissions.operator_transaction_name", "like",  "%" . $this->searchRealodAcompte . "%")
                    ->orWhere("transaction_commissions.description", "like",  "%" . $this->searchRealodAcompte . "%");
            }


            if ($this->statusFilter != "") {
                $realod_search->where('wallet_reloads.status', $this->statusFilter);
            }

            if ($this->statusFilterAcompte != "") {
                $realod_acoumpte_search->where('transaction_commissions.type_transaction', $this->statusFilterAcompte);
                $this->emit('menu-reloadAcompte-active');
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
                    'WalletTransactionCommissions' => $this->WalletReloads,
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
                    'walletCommissions' => WalletCommission::where('status', 1)->sum('amount'),
                    'walletAdmin' => WalletAdmin::where('status', 1)->sum('amount'),
                    'withdrawal_robus' => Provider::whereStatus(true)
                        ->whereId(9)
                        ->get(),
                    'wallet_info_searchs' => $wallet_info_user,
                    'reaload_acomptes' => $realod_acoumpte_search->orderBy('transaction_commissions.created_at', 'desc')->paginate(20),
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


            if ($this->searchRealodAcompte != "") {

                $this->resetPage();

                $realod_acoumpte_search->where("transaction_commissions.type_transaction", "like",  "%" . $this->searchRealodAcompte . "%")
                    ->orWhere("transaction_commissions.pay_amount", "like",  "%" . $this->searchRealodAcompte . "%")
                    ->orWhere("transaction_commissions.operator_transaction_name", "like",  "%" . $this->searchRealodAcompte . "%")
                    ->orWhere("transaction_commissions.description", "like",  "%" . $this->searchRealodAcompte . "%");
            }


            if ($this->statusFilter != "") {
                $realod_search->where('wallet_reloads.status', $this->statusFilter);
                // $this->emit('menu-reload-active');
            }

            if ($this->statusFilterAcompte != "") {
                $realod_acoumpte_search->where('transaction_commissions.type_transaction', $this->statusFilterAcompte);
                $this->emit('menu-reloadAcompte-active');
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
                    'reaload_acomptes' => $realod_acoumpte_search->orderBy('transaction_commissions.created_at', 'desc')->paginate(20),
                    'walletCommissions' => WalletCommission::where('status', 1)->sum('amount'),
                    'walletAdmin' => WalletAdmin::where('status', 1)->sum('amount'),
                    'wallet_info_searchs' => $wallet_info_user,
                    'usersCountry' => Auth::guard('web')->user()->country->currencie->name,

                ]
            );
        }
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

        foreach (
            WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_reloads.status', 'SUCCESS')->whereDate('wallet_reloads.created_at', Carbon::today())->select('wallet_reloads.*')->get() as $reload
        ) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }

    public function getWalletReloadsUserProperty()
    {

        $data = [];

        foreach (
            WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_reloads.created_by', Auth::guard('web')->user()->id)->where('wallet_reloads.status', 'SUCCESS')->whereDate('wallet_reloads.created_at', Carbon::today())->select('wallet_reloads.*')->get() as $reload
        ) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }
}
