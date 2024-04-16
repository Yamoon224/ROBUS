<?php

namespace App\Http\Livewire\Historical;

use Livewire\Component;
use App\Models\Sid\SidUser;
use Livewire\WithPagination;
use App\Events\UserNotifEvent;
use App\Events\AdminNotifEvent;
use App\Models\Payments\Deposit;
use App\Models\Costs\DepositCost;
use App\Models\Providers\Provider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Events\UpdateReloadCardEvent;
use App\Models\Wallets\WalletDeposit;
use App\Notifications\UserNotification;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Admin\MailController;

class Cartehisto extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchCarteHistorical = "";
    public $statusFilter = "";
    public  $startDate = "";
    public  $endDate =  "";

    // edit
    public $reloadcard_description_edit;
    public $provider_reloadcard_edit;
    public $country_reloadcard_edit;
    public $amount_reloadcard_edit;
    public $account_number_reloadcard_edit;
    public $phone_reloadcard_edit;
    public $amount_reloadcard_robus_edit;
    public $country_reloadcard_robus_edit;
    public $reloadcardStatus;
    public $name_reloadcard_edit;
    public $reloadcardId;

    // Edit Reload Card
    public function editReloadCardHisto($id, $state)
    {
        $check = WalletDeposit::whereId($id)->first();
        $provider = Provider::whereId($check->provider_id)->first();
        $this->reloadcardId = $id;
        $this->reloadcard_description_edit = $check->description;
        $this->reloadcardStatus =  $check->status;
        $this->provider_reloadcard_edit = $provider->id;
        $this->amount_reloadcard_edit = $check->amount;
        $this->account_number_reloadcard_edit = $check->num_carte;
        $this->name_reloadcard_edit = $check->name;
        $this->emit($state);
    }

    public function updateReloadCardHisto()
    {
        $check = WalletDeposit::whereId($this->reloadcardId)->first();
        $admins = SidUser::where('status', true)->where('is_admin', true)->where('country_id', Auth::guard('web')->user()->country->id)->get();

        if ($check) {

            $this->validate(['reloadcardStatus' => 'required']);

            if ($check->updated_by ==  Auth::guard('web')->user()->id) {

                if ($this->reloadcardStatus == "REFUND") {

                    $check->update([
                        'updated_by' => Auth::guard('web')->user()->id,
                        'status'  => $this->reloadcardStatus,
                    ]);
                    $deposit_histo = Deposit::where('wallet_deposit_id', $check->id)->first();

                    $deposit_histo->update([
                        'wallet_deposit_id' => $check->id,
                        'sid_user_id' => Auth::guard('web')->user()->id,
                        'pay_amount' =>  0.0,
                        'fee_amount' =>  0.0,
                        'received_amount' => 0.0,
                        'status'  =>    $check->status,
                    ]);

                    $commission =  (($check->amount)  +  ($check->amount * $check->depositCost->percent));


                    $commission_transaction =  (($check->amount)  -  ($check->amount * $check->depositCost->percent));

                    if ($commission_transaction  <=   Auth::guard('web')->user()->wallet->amount) {

                        if (Auth::guard('web')->user()->whereStatus(true)->where('is_admin', true)) {

                            Auth::guard('web')->user()->wallet->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount-'  . $commission_transaction),
                            ]);
                        }

                        $check->wallet->update([
                            'amount' => DB::raw('amount+'  . $commission),

                        ]);

                        $check->update([
                            'commissions' => 0,
                            'commissions_admin' => 0,
                        ]);

                        if ($check->wallet->sidUser->country->code == "CI") {

                            $message = 'Votre demande de rechargement de carte de ' . $check->amount .  $check->wallet->currencie->code . ' ' . 'par '
                                . $check->provider->name  .  ' a '
                                . $check->updated_at . ' a été remboursé ' . 'Robus vous remercie';

                            $encode = substr($check->wallet->sidUser->phone,  -10);

                            Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");

                            $msg_title = 'Rechagement de carte';
                            $msg = 'Votre demande de commande de carte a été remboursé';
                            $check->wallet->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                            $notifs = $check->wallet->sidUser->unreadNotifications;
                            event(new UpdateReloadCardEvent($check));
                            event(new UserNotifEvent($notifs));
                            Notification::send($admins, new AdminNotification('Demande de commande de carte a été remboursé'));
                            $admin = SidUser::where('status', true)->where('is_admin', true)->first();
                            $notifs = $admin->unreadNotifications;
                            event(new AdminNotifEvent($notifs));
                            $this->resetReloadCardField();
                            $this->emit('modal-hide-reloadCardHisto');
                            $this->emit('error', 'Rechargement remboursé !');
                        } else {

                            $msg_title = 'Rechagement de carte';
                            $msg = 'Votre demande de commande de carte a été remboursé';
                            $check->wallet->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                            $notifs = $check->wallet->sidUser->unreadNotifications;
                            event(new UpdateReloadCardEvent($check));
                            event(new UserNotifEvent($notifs));
                            Notification::send($admins, new AdminNotification('Demande de commande de carte a été remboursé'));
                            $admin = SidUser::where('status', true)->where('is_admin', true)->first();
                            $notifs = $admin->unreadNotifications;
                            event(new AdminNotifEvent($notifs));
                            $this->resetReloadCardField();
                            $this->emit('modal-hide-reloadCardHisto');
                            $this->emit('error', 'Rechargement remboursé !');

                            $notify = new MailController;
                            $notify->newReload($check);
                        }
                    } else {


                        $this->resetReloadCardField();
                        $this->emit('modal-hide-reloadCardHisto');
                        $this->emit('info', 'Verifier votre solde SVP !');
                    }
                } else {

                    $this->resetReloadCardField();
                    $this->emit('modal-hide-reloadCardHisto');
                    $this->emit('error', 'Pas de modification !');
                }
            } else {

                $this->resetReloadCardField();
                $this->emit('modal-hide-reloadCardHisto');
                $this->emit('error', 'Pas le valideur !');
            }
        } else {
            $this->resetReloadCardField();
            $this->emit('modal-hide-reloadCard');
            $this->emit('error', 'Pas de modification');
        }
    }

    public function resetReloadCardField()
    {

        $this->emit('modal-hide', 'hide');
    }

    public function render()
    {

        $cartes =  WalletDeposit::whereIsWeb(false);

        $walletUser = Auth::guard('web')->user();

        if ($walletUser->wallet->sidUser->is_under_distributer == true) {

            abort(403);

        } else 
        {

            if ($this->startDate && $this->endDate) {

                // $cartes->whereBetween('wallet_deposits.created_at', [$this->startDate, $this->endDate]);

                return view(
                    'livewire.historical.cartehisto',
                    [
                        'user' => $this->WalletDepositsDays,
                        'card_historicals' => $cartes->join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                            ->where('wallets.country_id', $walletUser->country->id)->whereBetween('wallet_deposits.created_at', [$this->startDate, $this->endDate])->orderBy('wallet_deposits.created_at', 'desc')->select('wallet_deposits.*')->paginate(15),
                        'usersCountry' =>   $walletUser->country->currencie->name,
                        'provider_reloads' =>  $this->ReloadTransaction,
    
                    ]
                );
            }

            if ($this->searchCarteHistorical != "") {

                $this->resetPage();

                $cartes->orwhereHas(
                    'deposit',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchCarteHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchCarteHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchCarteHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_deposits.amount", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_deposits.num_carte", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_deposits.name", "LIKE",  "%" . $this->searchCarteHistorical . "%");
            }

            if ($this->statusFilter != "") {

                // $cartes->where("wallet_deposits.status", $this->statusFilter);

                return view(
                    'livewire.historical.cartehisto',
                    [
                        'user' => $this->DepositAllDaysStatus,
                        'card_historicals' => $cartes->where("wallet_deposits.status", $this->statusFilter)->join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                            ->where('wallets.country_id', $walletUser->country->id)->orderBy('wallet_deposits.created_at', 'desc')->select('wallet_deposits.*')->paginate(15),
                        'usersCountry' =>   $walletUser->country->currencie->name,
                        'provider_reloads' =>  $this->ReloadTransaction,
    
                    ]
                );
            }

            return view(
                'livewire.historical.cartehisto',
                [
                    'user' => $this->DepositAllDays,
                    'card_historicals' => $cartes->join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->orderBy('wallet_deposits.created_at', 'desc')->select('wallet_deposits.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,
                    'provider_reloads' =>  $this->ReloadTransaction,

                ]
            );
        }
    }


    public function getReloadTransactionProperty()
    {

        $walletUser = Auth::guard('web')->user();

        $reload_transaction = Provider::join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', $walletUser->country->id)->where('providers.status', true)
            ->where('providers.provider_type_id',  5)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')->get();

        return $reload_transaction;
    }

    public function getDepositAllDaysStatusProperty()
    {


        $data = [];

        $wallets = WalletDeposit::where('wallet_deposits.is_web' ,false)->where("wallet_deposits.status", $this->statusFilter)->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }
    public function getDepositAllDaysProperty()
    {


        $data = [];

        $wallets = WalletDeposit::where('wallet_deposits.is_web' ,false)->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }

    public function getWalletDepositsDaysProperty()
    {
        $data = [];

        $wallets = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_deposits.is_web' ,false)->whereBetween('wallet_deposits.created_at', [$this->startDate, $this->endDate])->where('wallet_deposits.status', 'SUCCESS')->select('wallet_deposits.*')->get() ;

        foreach ($wallets as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }
  

    public function getWalletBankComDaysProperty()
    {

       $data = [];

       // Exécutez la requête pour obtenir une collection d'objets
       $walletdeposits = WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
       ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_deposits.is_web' ,false)->whereBetween('wallet_deposits.created_at', [$this->startDate, $this->endDate])->where('wallet_deposits.status', 'SUCCESS')->select('wallet_deposits.*')->get() ;
       

       foreach ($walletdeposits as $wallet) {
           $data[] = $wallet->commissions;
       }



        return array_sum($data);
    }
}