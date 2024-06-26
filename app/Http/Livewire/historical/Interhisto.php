<?php

namespace App\Http\Livewire\Historical;

use Livewire\Component;
use App\Models\Sid\SidUser;
use Livewire\WithPagination;
use App\Models\Wallets\Wallet;
use App\Events\AdminNotifEvent;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use Illuminate\Support\Facades\DB;
use App\Models\Wallets\WalletInter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Invoices\InvoiceInter;
use App\Notifications\UserNotification;
use App\Notifications\AdminNotification;
use App\Models\Payments\TransactionInter;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Admin\MailController;

class Interhisto extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchInterHistorical = "";
    public $statusInterFilter = "";
    public  $startDate = "";
    public  $endDate =  "";
    public $searchTransferHistorical = "";
    public $statusTransferFilter = "";

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
        $check = WalletInter::whereId($id)->first();
        $provider = Provider::whereId($check->provider_id)->first();
        $this->transactionId = $id;
        $this->transactionStatus =  $check->status;
        $this->provider_transaction_edit = $provider->id;
        $this->transaction_description_edit = $check->description ?? null;
        $this->transaction_phone_edit = $check->phone ?? null;
        $this->compte_number_robus_edit = $check->number_compte ?? null;
        $this->transaction_amount_robus_edit = $check->amount ?? null;
        $this->transaction_description_robus_edit = $check->description ?? null;
        $this->emit($state);
    }


    public function updateTransactionHisto()
    {
        $check = WalletInter::whereId($this->transactionId)->first();
        $walletUser = Auth::guard('web')->user()->wallet->amount;
        $admins = SidUser::where('status', true)->where('is_admin', true)->where('country_id', Auth::guard('web')->user()->country->id)->get();
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

        if ($check) {

            $this->validate(['transactionStatus' => 'required']);


            if ($this->transactionStatus == "REFUND") {

                if ($check->provider->id != 51) {

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
                            $country_robus =  $check->country_id;
                            $WalletInter = $this->createMobileWalletInterDistributer($commission,  $check->provider->id, $amount_solde, $wallet_user, $user, $phone,  $country_robus);

                            if ($WalletInter != null) {

                                if ($this->updateAmountRobusReceivedMobile($WalletInter, $commission)) {

                                    if ($check->wallet->sidUser->country->code == "CI") {

                                        $message = 'Vous demande de transaction de ' . $check->amount .  $check->wallet->currencie->code . ' ' .  'par '
                                            . $check->provider->name  .  ' a '
                                            . $check->updated_at . ' a été remboursée ' . 'Robus vous remercie';

                                        $encode = substr($check->wallet->sidUser->phone,  -10);

                                        Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                                    }
                                    $this->sendNotificationMobile($WalletInter);
                                } else {
                                    $this->resetTransactionField();
                                    $this->emit('modal-hide-transferInters-histo');
                                    $this->emit('error', 'Error de createtion transferts !');
                                }
                            } else {
                                $this->resetTransactionField();
                                $this->emit('modal-hide-transferInters-histo');
                                $this->emit('error', 'Error de createtion transferts !');
                            }

                            $this->transactionStatus = "";
                        } else {
                            $this->resetTransactionField();
                            $this->emit('modal-hide-transferInters-histo');
                            $this->emit('info', 'Demande deja lancée !');
                        }
                    } else {
                        $this->resetTransactionField();
                        $this->emit('modal-hide-transferInters-histo');
                        $this->emit('error', 'Vous n\'êtes pas le validateur de cette transaction  !!!!');
                    }
                } else {


                    // Check wallet receveid 
                    $wallet_received = Wallet::where('wallet_id',  $check->number_compte)->first();

                    if ($wallet_received) {
                        // Opérations avec $wallet_received

                        $wallet_send = $check->wallet;

                        $commission_receiveid =  $check->amount;

                        $amount_send =  $check->amount_received;

                        $wallet_receveid = $wallet_received;

                        $commission_send =  $this->convertAdminCommissionInter($commission_receiveid, $wallet_send, $wallet_receveid);

                        // Valeur amount commission distributeur
                        $wallet = $wallet_received;

                        $valeur_amount_com =  $this->convertAdminCommission($check->adminCom->amount, $wallet);

                        //Verifier soslde
                        if ($wallet_received->amount >  $commission_send) {

                            $check->update([
                                'updated_by' => Auth::guard('web')->user()->id,
                                'status'  => $this->transactionStatus,
                            ]);


                            if ($this->transactionStatus != "") {

                                $wallet_received->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount-' . $commission_send),
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

                                    $walletAmount = DB::table('wallets')->select(DB::raw('SUM(amount) as total_amount'))->where('wallet_id',  $check->wallet->wallet_id)->first();

                                    $amount_solde = (float)$walletAmount->total_amount +  (float)$commission_receiveid;

                                    $wallet_user =  $wallet_received->id;

                                    $user =  $wallet_received->sidUser->id;

                                    $wallet_info = $check->wallet->wallet_id;

                                    $country_robus =  $check->wallet->country_id;

                                    // Create Trance Use send error transaction
                                    $WalletInter = $this->createMobileWalletInterRobus($commission_receiveid, $check->provider->id, $amount_solde, $wallet_user, $user, $wallet_info,  $country_robus);


                                    if ($WalletInter != null) {

                                        if ($this->updateAmountRobusReceivedMobile($WalletInter, $commission_receiveid)) {

                                            if ($check->wallet->sidUser->country->code == "CI") {

                                                $message = 'Vous demande de transaction de ' . $check->amount .  $check->wallet->currencie->code . ' ' .  'par '
                                                    . $check->provider->name  .  ' a '
                                                    . $check->updated_at . ' a été remboursée ' . 'Robus vous remercie';

                                                $encode = substr($check->wallet->sidUser->phone,  -10);

                                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                                            }
                                            $this->sendNotificationRobus($WalletInter);
                                        } else {
                                            $this->resetTransactionField();
                                            $this->emit('modal-hide-transferInters-histo');
                                            $this->emit('error', 'Error de createtion transferts !');
                                        }
                                    } else {
                                        $this->resetTransactionField();
                                        $this->emit('modal-hide-transferInters-histo');
                                        $this->emit('error', 'Error de createtion transferts !');
                                    }
                                }

                                if ($wallet_received != null) {

                                    $walletAmount = DB::table('wallets')->select(DB::raw('SUM(amount) as total_amount'))->where('wallet_id',  $wallet_received->wallet_id)->first();

                                    $amount_solde = (float)$walletAmount->total_amount - (float)$commission_receiveid;

                                    $wallet_user =  $wallet_received->id;

                                    $user =  $wallet_received->sidUser->id;

                                    $wallet_info = $check->number_compte;

                                    $country_robus =  $check->wallet->country_id;

                                    // Create Trance Use send error transaction
                                    $WalletInter = $this->createMobileWalletInterRobus($commission_receiveid,  $check->provider->id, $amount_solde, $wallet_user, $user, $wallet_info, $country_robus);

                                    if ($WalletInter != null) {

                                        if ($this->updateAmountRobusReceivedMobile($WalletInter, $commission_receiveid)) {

                                            if ($wallet_received->sidUser->country->code == "CI") {

                                                $message = 'Vous demande de transaction de ' . $check->amount .  $wallet_received->currencie->code . ' ' .  'par '
                                                    . $check->provider->name  .  ' a '
                                                    . $check->updated_at . ' a été remboursée ' . 'Robus vous remercie';

                                                $encode = substr($wallet_received->sidUser->phone,  -10);

                                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                                            }
                                            $this->sendNotificationRobus($WalletInter);
                                        } else {
                                            $this->resetTransactionField();
                                            $this->emit('modal-hide-transferInters-histo');
                                            $this->emit('error', 'Error de createtion transferts !');
                                        }
                                    } else {
                                        $this->resetTransactionField();
                                        $this->emit('modal-hide-transferInters-histo');
                                        $this->emit('error', 'Error de createtion transferts !');
                                    }
                                }
                            } else {
                                $this->resetTransactionField();
                                $this->emit('modal-hide-transferInters-histo');
                                $this->emit('info', 'Demande deja lancée !!');
                            }

                            $this->transactionStatus = "";
                        } else {

                            $this->resetTransactionField();
                            $this->emit('modal-hide-transferInters-histo');
                            $this->emit('info', 'Solde insuffisant !!');
                        }
                    } else {
                        $this->resetTransactionField();
                        $this->emit('modal-hide-transferInters-histo');
                        $this->emit('error', 'Wallet Introuvable !!');
                    }
                }
            } else {
                $this->resetTransactionField();
                $this->emit('modal-hide-transferInters-histo');
                $this->emit('error', 'Aucune donnée !');
            }
        } else {
            $this->resetTransactionField();
            $this->emit('modal-hide-transferInters-histo');

            $this->emit('error', 'Pas de modification');
        }
    }
    public function resetTransactionField()
    {

        $this->emit('modal-hide', 'hide');
    }

    public function render()
    {

        $inters =  WalletInter::whereIsWeb(false);

        $walletUser = Auth::guard('web')->user();

        if ($walletUser->wallet->sidUser->is_under_distributer == true) {

            abort(403);

        } else {

            if ($this->startDate && $this->endDate) {

                // $inters->whereBetween('wallet_inters.created_at', [$this->startDate, $this->endDate]);

                return view(
                    'livewire.historical.interhisto',
                    [
                        'user' => $this->WalletIntersDays,
                        'inter_historicals' =>  $inters->whereBetween('wallet_inters.created_at', [$this->startDate, $this->endDate])->orderBy('created_at', 'desc')->paginate(15),
                        'usersCountry' =>   $walletUser->country->currencie->name,
                        'providers' =>  $this->NationTransaction,

                    ]
                );
            }

            if ($this->searchInterHistorical != "") {
                $this->resetPage();

                $inters->orwhereHas(
                    'transactionInter',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchInterHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchInterHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchInterHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_inters.amount", "LIKE",  "%" . $this->searchInterHistorical . "%")
                    ->orWhere("wallet_inters.number_compte", "LIKE",  "%" . $this->searchInterHistorical . "%")
                    ->orWhere("wallet_inters.phone", "LIKE",  "%" . $this->searchInterHistorical . "%");
            }


            if ($this->statusInterFilter != "") {

                // $inters->where("wallet_inters.status", $this->statusInterFilter);


                return view(
                    'livewire.historical.interhisto',
                    [
                        'user' => $this->walletInterAllStatus,
                        'inter_historicals' =>  $inters->where("wallet_inters.status", $this->statusInterFilter)->orderBy('wallet_inters.created_at', 'desc')->paginate(15),
                        'usersCountry' =>   $walletUser->country->currencie->name,
                        'providers' =>  $this->NationTransaction,

                    ]
                );
            }

            return view(
                'livewire.historical.interhisto',
                [
                    'user' => $this->WalletIntersPayments,
                    'inter_historicals' =>  $inters->orderBy('wallet_inters.created_at', 'desc')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,
                    'providers' =>  $this->NationTransaction,

                ]
            );
        }
    }

    public function getWalletIntersPaymentsProperty()
    {


        $data = [];

        $wallets = WalletInter::where('wallet_inters.is_web' ,false)->orderBy('wallet_inters.created_at', 'desc')->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }

   

    // Wallet inter transactions
    public function getWalletIntersDaysProperty()
    {


        $data = [];

        $wallets = WalletInter::where('wallet_inters.is_web' ,false)->whereBetween('wallet_inters.created_at', [$this->startDate, $this->endDate])->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }


    public function getwalletInterAllStatusProperty()
    {


        $data = [];

        $wallets = WalletInter::where('wallet_inters.is_web' ,false)->where("wallet_inters.status", $this->statusInterFilter)->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }



    public function getPhoneCreditsDaysProperty()
    {


        $data = [];

        $wallets = WalletInter::where('wallet_inters.is_web' ,false)->whereBetween('wallet_inters.created_at', [$this->startDate, $this->endDate])->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }

    public function getPhoneCreditTransactionProperty()
    {

        $walletUser = Auth::guard('web')->user();

        $phone_transaction = Provider::join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', $walletUser->country->id)->where('providers.status', true)
            ->where('providers.provider_type_id',  6)
            ->select('providers.*')
            ->orderBy('providers..name', 'asc')->get();

        return $phone_transaction;
    }


    public function getWalletInterStatusComProperty()
    {

        $data = [];
        foreach (WalletInter::where('wallet_inters.is_web' ,false)->where("wallet_inters.status", $this->statusPhoneFilter)->where('wallet_inters.status', 'SUCCESS')->select('wallet_inters.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
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
    private function createMobileWalletInterRobus($amount, $provider, $amount_solde, $wallet_user, $user, $wallet_info, $country_robus)
    {

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));

        $walletInter =  WalletInter::create(
            [
                'wallet_id' =>   $wallet_user,
                'amount' => $amount,
                'description'  => $this->transaction_description_edit ?? null,
                // 'transaction_cost_id' => $percent_robus->id,
                // 'phone'   => sabdy()->country->indecative . $this->transaction_phone,
                'created_by' => $user,
                'provider_id'  =>    $provider,
                'number_compte'   => $wallet_info ?? null,
                'country_id' => $country_robus,
                // 'costs_amount' => $valuer_deposit_com,
                // 'name_complet' => $this->name_custumers ?? null,
                // 'num_piece' => $this->num_piece_custumers ?? null,
                // 'validated_date' => $this->date_piece ?? null,
                'provider_type' => 1,
                'amount_received'  => $amount,
                'type_transaction' => 'Compense',
                'type_received' => 'Transfert',
                'status'  => $this->transactionStatus,
                'amount_solde' => $amount_solde ?? null
            ]
        );

        $transaction_inter = TransactionInter::create([
            'wallet_inter_id' => $walletInter->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'RB_' . $walletInter->provider->name .
                '_' . $transactionCode,
            'operator_transaction_name' =>
            $walletInter->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => $walletInter->amount,
            'fee_amount' => $walletInter->costs_amount,
            'received_amount' => $walletInter->amount,
            'status' => $walletInter->status,
        ]);

        InvoiceInter::create([
            'title' => "Transfers d'argent",
            // 'transaction_inter_id' => $check->id,
            'transaction_inter_id' => $transaction_inter->id,
            // 'company_id' => Auth::guard('web')->user()->company->id,
            'description' => $walletInter->description,
            'status' => $walletInter->status,
            'created_by' => Auth::guard('web')->user()->id,
        ]);

        return $walletInter;
    }
    private function createMobileWalletInterDistributer($amount, $provider, $amount_solde, $wallet_user, $user, $phone, $country_robus)
    {

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));

        $walletInter =  WalletInter::create(
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
                // 'choose_type_transaction' => 1,
                'country_id' => $country_robus,
                'provider_type' => 1,
                'amount_received'  => $amount,
                'type_transaction' => 'Compense',
                'type_received' => 'Transfert',
                'status'  => $this->transactionStatus,
                'amount_solde' => $amount_solde ?? null
            ]
        );

        $transaction_inter = TransactionInter::create([
            'wallet_inter_id' => $walletInter->id,
            'sid_user_id' => Auth::guard('web')->user()->id,
            'transaction_id' => 'RB_' . $walletInter->provider->name .
                '_' . $transactionCode,
            'operator_transaction_name' =>
            $walletInter->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => $walletInter->amount,
            'fee_amount' => $walletInter->costs_amount,
            'received_amount' => $walletInter->amount,
            'status' => $walletInter->status,
        ]);

        InvoiceInter::create([
            'title' => "Transfers d'argent",
            // 'transaction_inter_id' => $check->id,
            'transaction_inter_id' => $transaction_inter->id,
            // 'company_id' => Auth::guard('web')->user()->company->id,
            'description' => $walletInter->description,
            'status' => $walletInter->status,
            'created_by' => Auth::guard('web')->user()->id,
        ]);

        return $walletInter;
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
        // event(new CreateWalletTransfersEvent($walletTransaction));
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
        // event(new CreateWalletTransfersEvent($walletTransaction));
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

        $transaction_nationnal = Provider::join('country_services', 'country_services.provider_id', 'providers.id')->where('providers.status', true)
            ->where('providers.provider_type_id',  1)->select('providers.*')->orderBy('providers.name', 'asc')->get();

        return $transaction_nationnal;
    }
   
}
