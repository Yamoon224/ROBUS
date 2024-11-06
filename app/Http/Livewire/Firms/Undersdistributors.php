<?php

namespace App\Http\Livewire\Firms;

use App\Events\AdminNotifEvent;
use App\Events\CreateReloadEvent;
use App\Http\Controllers\Admin\MailController;
use App\Mail\UvSend;
use App\Models\Custumers\Country;
use App\Models\Invoices\InvoiceReload;
use App\Models\Invoices\InvoiceTransaction;
use App\Models\Payments\PaymentReload;
use App\Models\Payments\Transaction;
use App\Models\Providers\Provider;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletTransaction;
use App\Notifications\AdminNotification;
use App\Notifications\UserNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class Undersdistributors extends Component
{


    use WithPagination;

    public $search = "";
    public $wallet_id;
    public $user_id;
    public $employerDesabled;
    public $userdetails;
    public $userLocation;
    public $userPhone;
    public $wallet_id_add;
    public $categorieId_add;
    public $transaction_description_robus;
    public $account_reload_uv;
    public $relaod_uv_amout;
    public $wallet_under;

    protected $listeners = [
        "echo-private:wallet-reload-created,.Illuminate\Notifications\Events\BroadcastNotificationCreated" => 'notifyNewReload',
        'echo:wallet-reload-created,.Created_wallet_reload' => 'notifyNewReload',
        'echo:updated_reload,.update_wallet_reload' => 'notifyNewReload',
        'echo:update_all_wallet,.wallet_update_all' => 'notifyNewReload',
        // Choose
        'echo:wallet-reload-choose-created,.Created_wallet_reload_choose' => 'notifyNewReload',
    ];

    public function addReload()
    {


        $walletUser = firm();
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
                ->where('country_id',  $walletUser->country->id)
                // ->whereStatus(true)
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

                    if ($account_is_under || $account_is_master) {

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

                                $walletReloads =  $this->createWAlletReloadRobus($walletUser, $this->relaod_uv_amout_robus, $provider, $this->account_reload_uv, $valeur_receive , $amount_solde, $amount_drawal);

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
                    } else {

                        $this->cancelReload();
                        $this->emit('modal-hide', 'hide');
                        $this->emit('error', 'Le type utilisateur est necessaire dans la transaction !');
                    }
                }
            } else  return  $this->errorWalletAccountDist();
        } else {
            $this->cancelReload();
            $this->emit('modal-hide', 'hide');
            $this->emit('error', 'Selectionnez une valeur  !');
        }
    }

    private function createWAlletReloadRobus($walletUser, $relaod_uv_amout_robus, $provider, $account_reload_uv, $valeur_receive , $amount_solde, $amount_drawal)
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
            'phone'  =>  null,
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


    public function errorSolde()
    {

        $this->relaod_uv_amout = '';
        $this->emit('modal-hide', 'hide');
        $this->emit('warning', 'Montant min 2000 !' . master()->country->currencie->name);
    }

    public function errorWalletAccount()
    {

        $this->relaod_uv_amout = '';
        $this->emit('modal-hide', 'hide');
        $this->emit('warning', 'Ce numero n\'existe pas dans cette base ou vous appartient !');
    }

    public function errorWalletAccountDist()
    {

        $this->relaod_uv_amout = '';
        $this->emit('modal-hide', 'hide');
        $this->emit('warning', 'Le numero doit appartenir a un distributeur ou sousdistributer !');
    }


    public function edit($id, $state)
    {
        $check = Wallet::whereId($id)->first();
        $this->user_id = $check->sidUser->id;
        $this->userdetails = $check->sidUser->firstname . ' ' . $check->sidUser->lastname;
        $this->userLocation = $check->sidUser->country->name . ' - ' . $check->sidUser->city->name .  ' - ' . $check->sidUser->zone;
        $this->wallet_id_add = $check->wallet_id;
        $this->userPhone = $check->sidUser->phone;
        $this->employerDesabled = $check->company_id;
        $this->categorieId_add = $check->sidUser->categorie_id;
        $this->emit($state);
    }

    public function disable()
    {

        $check = SidUser::find($this->user_id);

        if (!empty($check)) {

            if ($check->company_id  == firm()->id && $check->is_accepted_companie) {
                $msg = 'Vous avez été rétiré de ' . firm()->firstname;
                $msg_title = 'Déliaison a  ' . firm()->firstname;
                $check->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $check->update(['company_id' => 1, 'is_accepted_companie' => false, 'categorie_id' => null]);
            } else {
                $msg = 'Vous avez été rétiré de ' . firm()->firstname;
                $msg_title = 'Déliaison a  ' . firm()->firstname;
                $check->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $check->update(['company_id' => 1, 'is_accepted_companie' => false, 'categorie_id' => null]);
            }
            $this->cancelEmployesField();
            $this->emit('modal-hide', 'hide');
            $this->emit('refreshPage');
            $this->emit('success', $check->company_id  == firm()->id ? 'Client lié !' : 'Client détaché !');
        }
    }

    public function cancelEmployesField()
    {

        $this->wallet_id_add = '';
        $this->transaction_description_robus = '';
        $this->emit('modal-hide', 'hide');
    }

    public function cancelReload()
    {

        $this->account_reload_uv = '';
        $this->relaod_uv_amout = '';
        $this->emit('modal-hide', 'hide');
    }


    public function render()
    {
        $users = SidUser::query();

        $walletUser = firm();

        if ($this->search != "") {

            $this->resetPage();

            $users->where('is_company', firm()->id)->where("firstname", "LIKE",  "%" . $this->search . "%")
                ->orWhere("lastname", "LIKE",  "%" . $this->search . "%")
                ->orWhere("email", "LIKE",  "%" . $this->search . "%")
                ->orWhere("phone", "LIKE",  "%" . $this->search . "%");
        }

        $transaction_error = $this->getTransactionCount(WalletTransaction::class, 'ERROR', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');
        $transaction_padding = $this->getTransactionCount(WalletTransaction::class, 'PENDING', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');
        $transaction_success = $this->getTransactionCount(WalletTransaction::class, 'SUCCESS', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');
        $transaction_amount = $this->getTodayTransactionAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount');
        $transaction_amount_month = $this->getDaysTransaction(WalletTransaction::class);
        $companyId = firm()->company->id;
        $currentUserId = firm()->id;

    
        return view('livewire.firms.undersdistributors', [
            "users" => SidUser::where('is_under_distributer', true)
                ->where('company_id', $companyId)
                ->where('id', '!=', $currentUserId)
                ->orderByDesc('created_at')
                ->paginate(30),
            'sum_error' =>  $transaction_error,
            'sum_success' =>  $transaction_success,
            'pending_success' => $transaction_padding,
            'sum_amount' => $transaction_amount,
            'usersCountry' => $walletUser->country->currencie->name,
            'sum_transaction_month' => $transaction_amount_month,
        ]);
    }

    public function getTransactionCount($walletModel, $status, $wallet_transactions, $transaction_days, $walletMy, $wallet_status)
    {

        $query = $walletModel::join('wallets', $wallet_transactions, 'wallets.id')
            ->whereDate($transaction_days, Carbon::today())
            ->where($wallet_status, $status);

        if (sabdy()->is_company) {
            $query->where(function ($query) use ($wallet_transactions) {
                $query->where($wallet_transactions, firm()->wallet->id);
            });
        }

        return $query->count();
    }

    public function getTodayTransactionAmountTotal($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount)
    {

        $query = $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereDate($transaction_days, Carbon::today())
            ->where($transaction_status, 'SUCCESS');

        if (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, firm()->wallet->id);
            });
        }


        return $query->sum($wallet_amount);
    }

    public function getDaysTransaction($walletModel)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', firm()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        if ($amount !== null) {
            $totalAmount += $amount;
        }


        return $totalAmount;
    }
}
