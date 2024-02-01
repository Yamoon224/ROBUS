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
    public $account_unlock_uv;
    public $relaod_unlock_amout_robus;
    public $newReloadNotification = false;
    public $wallet_search_under;
    public $wallet_under;



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
        $walletUser = master();
        $provider = Provider::whereId(9)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        // Verifier transfers sum days
        $som_transfers =  $this->getSumValTransactionProperty();
        $admins = SidUser::where('status', true)->where('is_admin', true)->where('country_id', master()->country->id)->get();
    

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

        // try {

            if ($provider->id == 9) {

                $this->validate(
                    [
                        'relaod_unlock_amout_robus' => 'required',
                        'account_unlock_uv' => 'required',
                    ],
                    [
                        'account_unlock_uv.exists' =>
                        'Ce compte n\'existe pas dans chez nous ',
                        'relaod_unlock_amout_robus.required' =>
                        'Votre montant est obligatoire',
                    ]
                );


                $wallet_search = Wallet::where('wallet_id',  $this->account_unlock_uv)
                    ->where('country_id', $walletUser->country->id)
                    // ->where('status', true)
                    ->first();


                $account_active = $wallet_search->sidUser->master_id;

                $account_accepted = $wallet_search->sidUser->is_accepted;

                $account_is_under = $wallet_search->sidUser->is_under_distributer;

                $account_is_custumers = $wallet_search->sidUser->is_custumers;

                $account_is_master = $wallet_search->sidUser->is_master;

                if ($account_active == master()->id && $account_accepted) {


                    if ($wallet_search == null &&  $this->account_unlock_uv == $walletUser->wallet->wallet_id) {
                        $this->cancelReload();
                        $this->emit('modal-hide', 'hide');
                        $this->emit('error', 'Ce numero n\'existe pas dans cette base ou vous appartient !');
                    } elseif ($account_is_under) {

                        if ($som_transfers >= $valeur_ceiling) {
                            $this->cancelReload();
                            $this->emit('modal-hide', 'hide');
                            $this->emit('warning', 'Votre montant journier est atteint !');
                        } else {


                            $wallet_send = $wallet_search;

                            $commission_receiveid =  $this->relaod_unlock_amout_robus;

                            $wallet_receveid =  $walletUser->wallet;

                            $commission_send =  $this->convertAdminCommissionInter($commission_receiveid, $wallet_send, $wallet_receveid);

                            // Verify amount
                            if ($commission_send > $wallet_search->amount) {
                                $this->cancelReload();
                                $this->emit('modal-hide', 'hide');
                                $this->emit('success', 'Verifier votre montant ! !');
                            } else {


                                //  commission valeurs
                                // $commission_valeur = $percent_robus_distri->percent * $this->relaod_unlock_amout_robus;
                                // $valeur_amount_com = $percent_robus_distri->percent_with_drawal * $commission_valeur;

                                $walletAmountSend = DB::table('wallets')->select(DB::raw('SUM(amount) as total_amount'))->where('wallet_id',  $this->account_unlock_uv)->first();

                                $amount_solde_drawal = (float)$walletAmountSend->total_amount -  (float)$commission_send;

                                $amount_drawal =      $amount_solde_drawal;

                                // $amount_solde = $check->wallet->amount +  $commission;
                                $amount_solde = (float)sabdy()->wallet->amount +  (float) $this->relaod_unlock_amout_robus;

                                $walletUnlock =  $this->createWAlletUnlockRobus($walletUser, $this->relaod_unlock_amout_robus, $provider, $this->account_unlock_uv , $amount_solde, $amount_drawal , $commission_send);

                                if ($walletUnlock != null) {
                                    if ($this->updateAmountRobusReceived($commission_send, $walletUser, $wallet_search, $walletUnlock)) {
                                        $this->sendNotification($wallet_search, $walletUser, $walletUnlock, $admins);
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
                        $this->emit('error', 'Pas de selection de providers !');
                    }
                } else {

                    $this->cancelReload();
                    $this->emit('modal-hide', 'hide');
                    $this->emit('error', 'Pas de selection de providers !');
                }
            } else {
                $this->cancelReload();
                $this->emit('modal-hide', 'hide');
                $this->emit('error', 'Pas de selection de providers !');
            }
        // } catch (\Exception $exception) {
        //     $this->cancelReload();
        //     $this->emit('modal-hide', 'hide');
        //     $this->emit('error', 'Error de message demande a l\'admin !');
        // }
    }

    private function convertAdminCommissionInter($amount, $walletSend, $walletReceveid)
    {

        $country_civ = Country::find(107);

        if ($walletSend->country->id == 107) {

            if ($walletSend->currencie->value >  $walletReceveid->currencie->value) {
                return $amount / $walletReceveid->currencie->value;
            } elseif ($walletSend->currencie->value ==  $walletReceveid->currencie->value) {
                return $amount;
            } else {
                return $amount * $walletReceveid->currencie->value;
            }
        } else {

            if ($walletReceveid->country->id == 107) {

                if ($walletReceveid->currencie->value >  $walletSend->currencie->value) {
                    // Convertion in FCA
                    $amount = $amount / $walletSend->currencie->value;

                    return $amount;
                } elseif ($walletReceveid->currencie->value ==  $walletSend->currencie->value) {
                    return $amount;
                } else {

                    $amount = $amount / $walletSend->currencie->value;

                    return $amount;
                }
            } else {

                if ($walletReceveid->currencie->value >  $walletSend->currencie->value) {
                    // Convertion in FCA
                    if ($country_civ->currencie->value >  $walletSend->currencie->value) {
                        $amount_cfa = $amount * $walletSend->currencie->value;
                        // convertion in valeurs
                        $amount = $amount_cfa / $walletReceveid->currencie->value;
                    } else {
                        // convertion in valeurs
                        $amount = $amount / $walletReceveid->currencie->value;
                    }
                    return $amount;
                } elseif ($walletReceveid->currencie->value ==  $walletSend->currencie->value) {
                    return $amount;
                } else {

                    if ($country_civ->currencie->value <  $walletSend->currencie->value) {
                        $amount_cfa = $amount * $walletSend->currencie->value;
                        // convertion in valeurs
                        $amount = $amount_cfa / $walletReceveid->currencie->value;
                    } else {
                        // convertion in valeurs
                        $amount = $amount / $walletReceveid->currencie->value;
                    }
                    return $amount;
                }
            }
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


    private function createWAlletUnlockRobus($walletUser, $relaod_unlock_amout_robus, $provider, $account_unlock_uv, $amount_solde, $amount_drawal, $commission_send)
    {

        $walletTransaction = WalletTransaction::create(
            [
                'wallet_id' => $walletUser->wallet->id,
                'amount' => $relaod_unlock_amout_robus,
                'description' =>  'Déchargement du compte' ?? null,
                'transaction_cost_id' => 26,
                'phone' =>  null,
                'num_wallet' =>  $account_unlock_uv,
                'created_by' => master()->id,
                'provider_id' => $provider->id,
                'code_validated' => null,
                'amount_received'  => $commission_send,
                'choose_type_transaction' => 2,
                'type_transaction' => 'Compense',
                'type_received' => 'Transfert',
                'status' => 'SUCCESS',
                'amount_solde' => $amount_solde ?? null,
                'amount_solde_drawal' => $amount_drawal ?? null
            ]
        );


        return $walletTransaction;
    }

    private function updateAmountRobusReceived($commission_send, $walletUser, $wallet_search, $walletTransaction)
    {

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf("%0.9s", str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf("%0.8s", str_shuffle($rt . $tr));

        // Update decrement in compte Client
        $wallet_search->update([
            'reload_by' => master()->firstname . ' ' . master()->lastname,
            'sid_user_relod' => master()->id,
            'amount' => DB::raw('amount-' . $commission_send),
        ]);

        // Send in compte Client
        $walletUser->wallet->update([
            'reload_by' => master()->firstname . ' ' . master()->lastname,
            'sid_user_relod' => master()->id,
            'amount' => DB::raw('amount+'  .  $this->relaod_unlock_amout_robus),
        ]);


        $transaction = Transaction::create([
            'wallet_transaction_id' => $walletTransaction->id,
            'sid_user_id' => master()->id,
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
            'company_id' => master()->company->id,
            'description' => $walletTransaction->description,
            'status' => $walletTransaction->status,
            'created_by' => master()->id,
        ]);

        return true;
    }


    private function sendNotification($wallet_search, $walletUser, $walletTransaction, $admins)
    {

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
        $this->cancelReload();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Retrait effectué avec success !');
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


    public function cancelReload()
    {

        $this->account_unlock_uv = '';
        $this->relaod_unlock_amout_robus = '';
        $this->emit('modal-hide', 'hide');
    }




    public function errorWalletAccount()
    {


        $this->account_unlock_uv = '';
        $this->relaod_unlock_amout_robus = '';
        $this->emit('modal-hide', 'hide');
        $this->emit('warning', 'Ce numero n\'existe pas dans cette base ou vous appartient !');
    }

    public function errorWalletAccountDist()
    {


        $this->account_unlock_uv = '';
        $this->relaod_unlock_amout_robus = '';
        $this->emit('modal-hide', 'hide');
        $this->emit('warning', 'Le numero doit appartenir a un distributeur ou sousdistributer !');
    }



    public function render()
    {

        $realod_search =  WalletTransaction::query();


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



        if ($this->account_unlock_uv != '') {

            $wallet_search_user = Wallet::where('wallet_id', $this->account_unlock_uv)->first();

            if ($wallet_search_user) {

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
                
                'WalletReloads' => $realod_search->where('wallet_id', $walletUser->wallet->id)->where('type_transaction' , 'Compense')->sum('amount'),
                'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
                'unlocks' => $realod_search->where('wallet_id', $walletUser->wallet->id)->where('type_transaction' , 'Compense')->orderBy('created_at', 'desc')->paginate(25),
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
