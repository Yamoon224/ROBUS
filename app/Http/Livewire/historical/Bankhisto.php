<?php

namespace App\Http\Livewire\Historical;

use Livewire\Component;
use App\Models\Sid\SidUser;
use Livewire\WithPagination;
use App\Events\UserNotifEvent;
use App\Events\AdminNotifEvent;
use App\Events\UpdateBankEvent;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use App\Models\Wallets\WalletBank;
use Illuminate\Support\Facades\DB;
use App\Models\Payments\PaymentBank;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Notifications\UserNotification;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Admin\MailController;

class Bankhisto extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchBankHistorical = "";
    public $statusFilter = "";
    public  $startDate = "";
    public  $endDate =  "";

    // edit
    public $virementDescription_edit;
    public $provider_virement_edit;
    public $firstname_user_edit;
    public $lastname_user_edit;
    public $phone_user_edit;
    public $amount_virement_edit;
    public $account_number_user_edit;
    public $country_virement_edit;
    public $virementStatus;
    public $virementId;
    public $currency_custumers;

    // Edit Virement

    public function editVirementHisto($id, $state)
    {
        $check = WalletBank::whereId($id)->first();
        $provider = Provider::whereId($check->provider_id)->first();
        $country = Country::whereId($check->country_id)->first();
        $this->virementId = $id;
        $this->virementStatus =  $check->status;
        $this->virementDescription_edit = $check->description;
        $this->provider_virement_edit =  $provider->id;
        $this->firstname_user_edit = $check->firstname;
        $this->lastname_user_edit  = $check->lastname;
        $this->phone_user_edit = $check->phone;
        $this->country_virement_edit = $country->id ?? null;
        $this->amount_virement_edit = $check->amount;
        $this->account_number_user_edit = $check->number_compte;
        $this->currency_custumers = $check->wallet->currencie->code ?? null;
        $this->emit($state);
    }
    public function updateVirementHisto()
    {
        $check = WalletBank::whereId($this->virementId)->first();
        $admins = SidUser::where('status', true)->where('is_admin', true)->where('country_id', Auth::guard('web')->user()->country->id)->get();
        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $admins = $this->getAdminValeurProperty();
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();
        if ($check) {

            $this->validate(['virementStatus' => 'required']);


            if ($check->updated_by ==  Auth::guard('web')->user()->id) {

                if ($this->virementStatus == "REFUND") {

                    if ($check->wallet->sidUser->is_under_distributer == 1) {
                        $commission =  $check->amount;

                        $check->wallet->update([
                            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                            'sid_user_relod' => Auth::guard('web')->user()->id,
                            'amount' => DB::raw('amount+' . $commission),
                        ]);

                        // Update commission
                        $check->update([
                            'commissions' => 0,
                            'commissions_admin' => 0,
                        ]);

                        // Valeur amount commission distributeur
                        $valeur_amount_com = $check->adminCom->amount;
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
                                ]);
                                $admin_single->wallet->update([
                                    'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                    'sid_user_relod' => Auth::guard('web')->user()->id,
                                    'amount' => DB::raw('amount+' . $valeur_single),
                                ]);
                            }
                        }

                        $msg_title = 'Versement banquaire';
                        $msg = 'Demande de virement a été remboursée';
                        $check->wallet->sidUser->notify(
                            new UserNotification([
                                'title' => $msg_title,
                                'message' => $msg,
                            ])
                        );
                        $notifs = $check->wallet->sidUser->unreadNotifications;
                        // event(new UpdateBankEvent($check));
                        event(new UserNotifEvent($notifs));
                        Notification::send(
                            $admins,
                            new AdminNotification(
                                'Demande de virement a été remboursée'
                            )
                        );
                        if ($admins->count() > 0) {

                            foreach ($admins  as $user) {

                                $admin_single = SidUser::find($user->id);

                                $notifs = $admin_single->unreadNotifications;

                                event(new AdminNotifEvent($notifs));
                            }


                            $this->resetVirementField();
                            $this->emit('modal-hide-virement');
                            $this->emit('error', 'Virement Remboursée !');

                            $notify = new MailController;
                            $notify->newVirement($check);
                        } else {

                            $this->resetVirementField();
                            $this->emit('modal-hide-virement');
                            $this->emit('error', 'Virement Remboursée !');

                            $notify = new MailController;
                            $notify->newVirement($check);
                        }
                    } else {

                        $commission =  $check->amount + $check->costs_amount;

                        $check->wallet->update([
                            'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                            'sid_user_relod' => Auth::guard('web')->user()->id,
                            'amount' => DB::raw('amount+' . $commission),
                        ]);

                        // Update commission
                        $check->update([
                            'commissions' => 0,
                            'commissions_admin' => 0,
                        ]);

                        // Valeur amount commission distributeur
                        $valeur_amount_com = $check->adminCom->amount;

                        //  Send valeur in Admin
                        if ($admin_civ->count() > 0) {

                            $count_admin = $admin_civ->count();

                            $valeur_single = $valeur_amount_com / $count_admin;

                            foreach ($admin_civ as $user) {
                                $admin_single = SidUser::find($user->id);
                                $valeur_decrement = $check->adminCom->update([
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

                        if ($check->wallet->sidUser->country->code == 'CI') {

                            $message =
                                'Votre demande de virement de ' .
                                $check->amount .
                                $check->wallet->currencie->code .
                                ' ' .
                                'par ' .
                                $check->provider->name .
                                ' a ' .
                                $check->firstname .
                                ' ' .
                                $check->firstname .
                                'a été remboursée' .
                                'le ' .
                                $check->updated_at .
                                ' ' .
                                'Robus vous remercie';

                            $encode = substr($check->wallet->sidUser->phone, -10);

                            Http::get(
                                "https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message"
                            );
                            $msg_title = 'Versement banquaire';
                            $msg = 'Demande de virement a été validé';
                            $check->wallet->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );
                            $notifs = $check->wallet->sidUser->unreadNotifications;
                            // event(new UpdateBankEvent($check));
                            // event(new UserNotifEvent($notifs));
                            Notification::send(
                                $admins,
                                new AdminNotification(
                                    'Demande de virement a été remboursée'
                                )
                            );

                            if ($admins->count() > 0) {

                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }


                                $this->resetVirementField();
                                $this->emit('modal-hide-virement');
                                $this->emit('error', 'Virement Remboursé !');
                                $notify = new MailController;
                                $notify->newVirement($check);
                            } else {


                                $this->resetVirementField();
                                $this->emit('modal-hide-virement');
                                $this->emit('error', 'Virement Remboursé !');
                                $notify = new MailController;
                                $notify->newVirement($check);
                            }
                        } else {

                            $msg_title = 'Versement banquaire';
                            $msg = 'Demande de virement a été validée';
                            $check->wallet->sidUser->notify(
                                new UserNotification([
                                    'title' => $msg_title,
                                    'message' => $msg,
                                ])
                            );
                            $notifs = $check->wallet->sidUser->unreadNotifications;
                            // event(new UpdateBankEvent($check));
                            // event(new UserNotifEvent($notifs));
                            Notification::send(
                                $admins,
                                new AdminNotification(
                                    'Demande de virement a été remboursée'
                                )
                            );
                            if ($admins->count() > 0) {

                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }


                                $this->resetVirementField();
                                $this->emit('modal-hide-virement');
                                $this->emit('error', 'Virement Remboursée !');

                                $notify = new MailController;
                                $notify->newVirement($check);
                            } else {

                                $this->resetVirementField();
                                $this->emit('modal-hide-virement');
                                $this->emit('error', 'Virement Remboursée !');

                                $notify = new MailController;
                                $notify->newVirement($check);
                            }
                        }
                    }

                    PaymentBank::create([
                        'wallet_bank_id' => $check->id,
                        'sid_user_id' => Auth::guard('web')->user()->id,
                        'transaction_id' =>
                        'RB_' .
                            $check->provider->name .
                            '_' .
                            $transactionCode,
                        'operator_transaction_name' => $check->provider->name,
                        'trans_comp_id' => 'RB_' . $transactionCompiCode,
                        'pay_amount' => 0.0,
                        'fee_amount' => 0.0,
                        'received_amount' => $commission,
                        'status' => $check->status,
                    ]);
                } else {

                    $this->resetVirementField();
                    $this->emit('modal-hide-virementHisto');
                    $this->emit('success', 'Virement modifié !');
                }
            } else {
                $this->resetVirementField();
                $this->emit('modal-hide-virementHisto');
                $this->emit('error', 'Pas vous le valideur !');
            }
        } else {
            $this->resetVirementField();
            $this->emit('modal-hide-virementHisto');
            $this->emit('error', 'Aucune donnée !');
        }
    }

    public function resetVirementField()
    {

        $this->emit('modal-hide', 'hide');
    }

    public function render()
    {

        $walletUser = Auth::guard('web')->user();

        $banks =  WalletBank::query();

        if (sabdy()->is_under_distributer) {
            $banks->where('wallet_banks.created_by', sabdy()->id);
        }


        if ($this->startDate && $this->endDate) {

            // $banks->whereBetween('wallet_banks.created_at', [$this->startDate, $this->endDate]);

            return view(
                'livewire.historical.bankhisto',
                [
                    'user' => $this->VirementsDays,
                    'bank_historicals' => $banks->join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->whereBetween('wallet_banks.created_at', [$this->startDate, $this->endDate])->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->orderBy('wallet_banks.created_at', 'desc')->select('wallet_banks.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,
                    'provider_virements' =>  $this->VirementTransaction,
                    'countries' => Country::where('status', true)->orderBy('name', 'asc')->get(),

                ]
            );
        }

        if ($this->searchBankHistorical != "") {


            $this->resetPage();

            $banks->orwhereHas(
                'paymentBank',
                function ($query) {
                    $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchBankHistorical . '%')
                        ->orWhere('transaction_id', 'like', '%' .  $this->searchBankHistorical   . '%')
                        ->orWhere('trans_comp_id', 'like', '%' .  $this->searchBankHistorical  . '%');
                }
            )
                ->orWhere("wallet_banks.amount", "LIKE",  "%" . $this->searchBankHistorical . "%")
                ->orWhere("wallet_banks.phone", "LIKE",  "%" . $this->searchBankHistorical . "%")
                ->orWhere("wallet_banks.number_compte", "LIKE",  "%" . $this->searchBankHistorical . "%")
                ->orWhere("wallet_banks.firstname", "LIKE",  "%" . $this->searchBankHistorical . "%")
                ->orWhere("wallet_banks.lastname", "LIKE",  "%" . $this->searchBankHistorical . "%");
        }


        if ($this->statusFilter != "") {

            // $banks->where("wallet_banks.status", $this->statusFilter);


            return view(
                'livewire.historical.bankhisto',
                [
                    'user' => $this->VirementsAllDaysStatus,
                    'bank_historicals' => $banks->join('wallets', 'wallet_banks.wallet_id', 'wallets.id')->where("wallet_banks.status", $this->statusFilter)
                        ->where('wallets.country_id', $walletUser->country->id)->orderBy('wallet_banks.created_at', 'desc')->select('wallet_banks.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,
                    'provider_virements' =>  $this->VirementTransaction,
                    'countries' => Country::where('status', true)->orderBy('name', 'asc')->get(),

                ]
            );
        }

        return view(
            'livewire.historical.bankhisto',
            [
                'user' => $this->VirementsAllDays,
                'bank_historicals' => $banks->join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $walletUser->country->id)->orderBy('wallet_banks.created_at', 'desc')->select('wallet_banks.*')->paginate(15),
                'usersCountry' =>   $walletUser->country->currencie->name,
                'provider_virements' =>  $this->VirementTransaction,
                'countries' => Country::where('status', true)->orderBy('name', 'asc')->get(),

            ]
        );
    }


    public function getVirementsAllDaysStatusProperty()
    {


        $data = [];

        $wallets = WalletBank::where("wallet_banks.status", $this->statusFilter);

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
    public function getVirementsAllDaysProperty()
    {


        $data = [];

        $wallets = WalletBank::get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }

    public function getVirementsDaysProperty()
    {


        $data = [];

        $wallets = WalletBank::whereBetween('created_at', [$this->startDate, $this->endDate]);

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


    public function getVirementTransactionProperty()
    {

        $walletUser = Auth::guard('web')->user();

        $virement_transaction = Provider::join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', $walletUser->country->id)->where('providers.status', true)
            ->where('providers.provider_type_id',  4)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')->get();

        return $virement_transaction;
    }
}
