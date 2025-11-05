<?php

namespace App\Http\Livewire\Historical;

use App\Models\Offer;
use Livewire\Component;
use App\Models\Sid\SidUser;
use Livewire\WithPagination;
use App\Events\UserNotifEvent;
use App\Events\AdminNotifEvent;
use App\Models\Payments\Payment;
use App\Models\Providers\Provider;
use Illuminate\Support\Facades\DB;
use App\Events\UpdatePaiementEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Wallets\WalletPayment;
use App\Notifications\UserNotification;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Admin\MailController;
use App\Http\Services\HistoriqueService;

class Paymenthisto extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchPaymentHistorical = "";
    public $statusFilter = "";
    public  $startDate = "";
    public  $endDate =  "";

    
    public function resetPaiementField()
    {

        $this->emit('modal-hide', 'hide');
    }
    public function render()
    {

        $paiements =  WalletPayment::query();


        if (sabdy()->is_under_distributer) {
            $paiements->where('wallet_payments.created_by', sabdy()->id);
        }


        if ($this->startDate && $this->endDate) {

            // $paiements->whereBetween('wallet_payments.created_at', [$this->startDate, $this->endDate]);

            return view(
                'livewire.historical.paymenthisto',
                [
                    'user' =>  app(HistoriqueService::class)->getTransactionsDaysProperty(WalletPayment::class , $this->startDate,$this->endDate , 'wallet_payments.created_by' , 'wallet_payments.updated_by'),
                    'payment_historicals' =>   $paiements->join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->whereBetween('wallet_payments.created_at', [$this->startDate, $this->endDate])->orderBy('wallet_payments.created_at', 'desc')->select('wallet_payments.*')->paginate(15),
                    'usersCountry' =>  Auth::guard('web')->user()->country->currencie->name,

                ]
            );
        }

        if ($this->searchPaymentHistorical != "") {

            $this->resetPage();

            $paiements->orwhereHas(
                'payment',
                function ($query) {
                    $query->where('operator_transaction_name', 'like', '%' . $this->searchPaymentHistorical . '%')
                        ->orWhere('transaction_id', 'like', '%' .  $this->searchPaymentHistorical   . '%')
                        ->orWhere('trans_comp_id', 'like', '%' .  $this->searchPaymentHistorical  . '%');
                }
            )
                ->Where("wallet_payments.amount", "like",  "%" . $this->searchPaymentHistorical . "%")
                ->orWhere("wallet_payments.number_phone", "like",  "%" . $this->searchPaymentHistorical . "%")
                ->orWhere("wallet_payments.name", "like",  "%" . $this->searchPaymentHistorical . "%")
                ->orWhere("wallet_payments.number_decoder", "like",  "%" . $this->searchPaymentHistorical . "%")
                ->orWhere("wallet_payments.phone", "like",  "%" . $this->searchPaymentHistorical . "%")
                ->orWhere("wallet_payments.id_invoice_pre", "like",  "%" . $this->searchPaymentHistorical . "%")
                ->orWhere("wallet_payments.id_invoice", "like",  "%" . $this->searchPaymentHistorical . "%");
        }

        if ($this->statusFilter != "") {

            // $paiements->where("wallet_payments.status", $this->statusFilter);


            return view(
                'livewire.historical.paymenthisto',
                [
                     'user' =>  app(HistoriqueService::class)->getTransactionsStatusProperty(WalletPayment::class , 'wallet_payments.is_web', 'wallet_payments.status' , $this->statusTransferFilter , 'wallet_payments.created_by' , 'wallet_payments.updated_by'),
                    'payment_historicals' =>   $paiements->join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where("wallet_payments.status", $this->statusFilter)->orderBy('wallet_payments.created_at', 'desc')->select('wallet_payments.*')->paginate(15),
                    'usersCountry' =>  Auth::guard('web')->user()->country->currencie->name,
                ]
            );
        }

        return view(
            'livewire.historical.paymenthisto',
            [
                 'user' =>  app(HistoriqueService::class)->getTransactionsAllDaysProperty(WalletPayment::class , 'wallet_payments.is_web' , 'wallet_payments.created_by' , 'wallet_payments.updated_by'),
                'payment_historicals' =>   $paiements->join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->orderBy('wallet_payments.created_at', 'desc')->select('wallet_payments.*')->paginate(15),
                'usersCountry' =>  Auth::guard('web')->user()->country->currencie->name,

            ]
        );
    }

}
