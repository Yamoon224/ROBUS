<?php

namespace App\Http\Livewire\Historical;

use Livewire\Component;
use App\Models\Sid\SidUser;
use Livewire\WithPagination;
use App\Events\UserNotifEvent;
use App\Events\AdminNotifEvent;
use App\Models\Providers\Provider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Events\UpdatePhoneCreditEvent;
use App\Notifications\UserNotification;
use App\Notifications\AdminNotification;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Payments\PaymentPhoneCredit;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Admin\MailController;

class PhoneCredithisto extends Component
{
    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchPhoneHistorical = "";
    public $statusPhoneFilter = "";
    public  $startDate = "";
    public  $endDate =  "";
    // Phone credit inter
    public $phoneCreditId;
    public $phoneCreditStatus;
    public $provider_phone_credit_edit;
    public $number_phone_credit_edit;
    public $amount_phone_credit_edit;


    // Edit Phone credit
    public function editPhoneCreditHisto($id, $state)
    {
        $check = WalletPhoneCredit::whereId($id)->first();
        $provider = Provider::whereId($check->provider_id)->first();
        $this->phoneCreditId = $id;
        $this->phoneCreditStatus = $check->status;
        $this->provider_phone_credit_edit = $provider->id;
        $this->number_phone_credit_edit = $check->phone;
        $this->amount_phone_credit_edit = $check->amount;
        $this->emit($state);
    }


    public function updatePhoneCredit()
    {
        $check = WalletPhoneCredit::whereId($this->phoneCreditId)->first();
        $admins = SidUser::where('status', true)->where('is_admin', true)->where('country_id', Auth::guard('web')->user()->country->id)->get();

        if ($check) {

            $this->validate(['phoneCreditStatus' => 'required']);


            if ($check->updated_by ==  Auth::guard('web')->user()->id) {

                if ($this->phoneCreditStatus == "REFUND") {

                    $check->update([
                        'updated_by' => Auth::guard('web')->user()->id,
                        'status'  => $this->phoneCreditStatus,
                    ]);

                    $phone_credit = PaymentPhoneCredit::where('wallet_phone_credit_id', $check->id)->first();


                    $phone_credit->update([
                        'wallet_phone_credit_id' => $check->id,
                        'sid_user_id' => Auth::guard('web')->user()->id,
                        'pay_amount' =>  0.0,
                        'fee_amount' =>  0.0,
                        'received_amount' => 0.0,
                        'status'  =>    $check->status,
                    ]);

                    $commission =  (($check->amount)  +  ($check->amount * $check->phoneCreditCost->percent));

                    $commission_transaction =  (($check->amount)  -  ($check->amount * $check->paymentCost->percent));

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

                            $message = 'Votre demande de credit de ' . $check->amount .  $check->wallet->currencie->code . ' ' . 'par '
                                . $check->provider->name  .  ' a '
                                . $check->updated_at . ' a été Remboursé ' . 'Robus vous remercie';

                            $encode = substr($check->wallet->sidUser->phone,  -10);

                            Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");

                            $msg_title = 'Credit téléphonique';
                            $msg = 'Demande de paiement de credit a été Remboursé';
                            $check->wallet->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                            $notifs = $check->wallet->sidUser->unreadNotifications;
                            event(new UpdatePhoneCreditEvent($check));
                            event(new UserNotifEvent($notifs));
                            Notification::send($admins, new AdminNotification('Demande de paiement de credit est Remboursé'));
                            $admin = SidUser::where('status', true)->where('is_admin', true)->first();
                            $notifs = $admin->unreadNotifications;
                            event(new AdminNotifEvent($notifs));
                            $this->resetPhoneCreditField();
                            $this->emit('modal-hide-phoneCreditHisto');
                            $this->emit('error', 'Achat de credit rembourse !');
                        } else {

                            $msg_title = 'Credit téléphonique';
                            $msg = 'Demande de paiement de credit a été remboursé';
                            $check->wallet->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                            $notifs = $check->wallet->sidUser->unreadNotifications;
                            event(new UpdatePhoneCreditEvent($check));
                            event(new UserNotifEvent($notifs));
                            Notification::send($admins, new AdminNotification('Demande de paiement de credit est remboursé'));
                            $admin = SidUser::where('status', true)->where('is_admin', true)->first();
                            $notifs = $admin->unreadNotifications;
                            event(new AdminNotifEvent($notifs));
                            $this->resetPhoneCreditField();
                            $this->emit('modal-hide-phoneCreditHisto');
                            $this->emit('error', 'Achat de credit remboursé !');
                            $notify = new MailController;
                            $notify->newPhoneCredit($check);
                        }
                    } else {

                        $this->resetPhoneCreditField();
                        $this->emit('modal-hide-phoneCreditHisto');
                        $this->emit('error', 'Verifier votre solde !');
                    }
                } else {

                    $this->resetPhoneCreditField();
                    $this->emit('modal-hide-phoneCreditHisto');
                    $this->emit('error', 'Pas de modification !');
                }
            } else {

                $this->resetPhoneCreditField();
                $this->emit('modal-hide-phoneCreditHisto');
                $this->emit('error', 'Pas le valideur !');
            }
        } else {
            $this->resetPhoneCreditField();
            $this->emit('modal-hide-phoneCredit');
            $this->emit('error', 'Pas de modification');
        }
    }

    public function resetPhoneCreditField()
    {

        $this->emit('modal-hide', 'hide');
    }

    public function render()
    {

        $phones =  WalletPhoneCredit::query();

        $walletUser = Auth::guard('web')->user();

        if ($walletUser->wallet->sidUser->is_under_distributer == true ) {

            if ($this->startDate && $this->endDate) {

                $phones->whereBetween('wallet_phone_credits.created_at', [$this->startDate, $this->endDate]);
            }

            if ($this->searchPhoneHistorical != "") {

                $this->resetPage();

                $phones->orwhereHas(
                    'paymentPhone',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchPhoneHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchPhoneHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchPhoneHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_phone_credits.amount", "LIKE",  "%" . $this->searchPhoneHistorical . "%")
                    ->orWhere("wallet_phone_credits.phone", "LIKE",  "%" . $this->searchPhoneHistorical . "%");
            }

            if ($this->statusPhoneFilter != "") {
                $phones->where("wallet_phone_credits.status", $this->statusPhoneFilter);
            }

            return view(
                'livewire.historical.phone-credithisto',
                [
                    'user' => $walletUser,
                    'phone_historicals' =>   $phones->join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_phone_credits.created_by', Auth::guard('web')->user()->id)->orderBy('wallet_phone_credits.created_at', 'desc')->select('wallet_phone_credits.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,
                    'sum_commissions' => $this->WalletPhoneCreditUser,
                    'provider_credits' => $this->PhoneCreditTransaction,

                ]
            );
        } else
         {

            if ($this->startDate && $this->endDate) {

                $phones->whereBetween('wallet_phone_credits.created_at', [$this->startDate, $this->endDate]);
            }

            if ($this->searchPhoneHistorical != "") {

                $this->resetPage();

                $phones->orwhereHas(
                    'paymentPhone',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchPhoneHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchPhoneHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchPhoneHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_phone_credits.amount", "LIKE",  "%" . $this->searchPhoneHistorical . "%")
                    ->orWhere("wallet_phone_credits.phone", "LIKE",  "%" . $this->searchPhoneHistorical . "%");
            }

            if ($this->statusPhoneFilter != "") {
                $phones->where("wallet_phone_credits.status", $this->statusPhoneFilter);
            }

            return view(
                'livewire.historical.phone-credithisto',
                [
                    'user' => $walletUser,
                    'phone_historicals' =>   $phones->join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->orderBy('wallet_phone_credits.created_at', 'desc')->select('wallet_phone_credits.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,
                    'sum_commissions' => $this->WalletPhoneCredit,
                    'provider_credits' => $this->PhoneCreditTransaction,

                ]
            );
        }
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


    // Admin 
    public function getWalletPhoneCreditProperty()
    {

        $data = [];
        foreach (WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_phone_credits.status', 'SUCCESS')->select('wallet_phone_credits.*')->get() as $bank) {
            $data[] = $bank->commissions_admin;
        }

        return array_sum($data);
    }

    // User 
    public function getWalletPhoneCreditUserProperty()
    {

        $data = [];
        foreach (WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_phone_credits.created_by', Auth::guard('web')->user()->id)->where('wallet_phone_credits.status', 'SUCCESS')->select('wallet_phone_credits.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }
}