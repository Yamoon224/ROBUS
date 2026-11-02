<?php

namespace App\Http\Livewire\Historical\UnderHistorical;

use App\Models\Offer;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Providers\Provider;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletPayment;
use App\Http\Services\HistoriqueService;

class PaymentHisto extends Component
{
    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchPaymentHistorical = "";
    public $statusFilter = "";
    public  $startDate = "";
    public  $endDate =  "";
    public $wallet_payment;


    // Edit Paiment


    public function resetPaiementField()
    {

        $this->emit('modal-hide', 'hide');
    }
    public function render()
    {

        $paiements =  WalletPayment::query();

        if (Auth::guard('web')->user()->wallet->sidUser->is_under_distributer == true) {

            if ($this->startDate && $this->endDate) {

                $paiements->whereBetween('wallet_payments.created_at', [$this->startDate, $this->endDate]);
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
                    ->orWhere("wallet_payments.amount", "like",  "%" . $this->searchPaymentHistorical . "%")
                    ->orWhere("wallet_payments.number_phone", "like",  "%" . $this->searchPaymentHistorical . "%")
                    ->orWhere("wallet_payments.name", "like",  "%" . $this->searchPaymentHistorical . "%")
                    ->orWhere("wallet_payments.number_decoder", "like",  "%" . $this->searchPaymentHistorical . "%")
                    ->orWhere("wallet_payments.phone", "like",  "%" . $this->searchPaymentHistorical . "%")
                    ->orWhere("wallet_payments.id_invoice_pre", "like",  "%" . $this->searchPaymentHistorical . "%")
                    ->orWhere("wallet_payments.id_invoice", "like",  "%" . $this->searchPaymentHistorical . "%");
            }
            if ($this->statusFilter != "") {
                $paiements->where("wallet_payments.status", $this->statusFilter);
            }

            return view(
                'livewire.historical.under-historical.payment-histo',
                [
                    'user' => Auth::guard('web')->user(),
                    'payment_historicals' =>   $paiements->join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_payments.created_by', Auth::guard('web')->user()->id)->orderBy('wallet_payments.created_at', 'desc')->select('wallet_payments.*')->paginate(15),
                    'usersCountry' =>  Auth::guard('web')->user()->country->currencie->name,
                    'sum_commissions' => app(HistoriqueService::class)->getWalletCommissionUserProperty(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_by', 'wallet_payments.status', 'wallet_payments.*'),
                ]
            );
        } else {

            if ($this->startDate && $this->endDate) {

                $paiements->whereBetween('wallet_payments.created_at', [$this->startDate, $this->endDate]);
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
                $paiements->where("wallet_payments.status", $this->statusFilter);
            }

            return view(
                'livewire.historical.under-historical.payment-histo',
                [
                    'user' => Auth::guard('web')->user(),
                    'payment_historicals' =>   $paiements->join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->orderBy('wallet_payments.created_at', 'desc')->select('wallet_payments.*')->paginate(15),
                    'usersCountry' =>  Auth::guard('web')->user()->country->currencie->name,
                    'sum_commissions' => app(HistoriqueService::class)->getWalletAdminCommissionProperty(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.status', 'wallet_payments.*'),

                ]
            );
        }
    }

}
