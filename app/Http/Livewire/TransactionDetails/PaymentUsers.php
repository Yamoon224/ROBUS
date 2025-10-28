<?php

namespace App\Http\Livewire\TransactionDetails;

use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletPayment;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Services\CeldingValeurService;

class PaymentUsers extends Component
{

    use WithPagination;

    public $walletAllPayments;
    public $search = "";


    public function mount($walletAllPayments)
    {
        // Convertir l'objet en tableau
        $this->walletAllPayments =  $walletAllPayments;
    }


    public function render()
    {


        $sid_user = SidUser::whereId($this->walletAllPayments)->first();

        $realod_search =  WalletPayment::query();


        if ($sid_user->is_distributer) {


            $paiements =  app(CeldingValeurService::class)->getTransactionOperatorsWallet(WalletPayment::class, $this->walletAllPayments);


            if ($this->search != "") {


                $realod_search->where('updated_by', $this->walletAllPayments)->orwhereHas(
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
        } else {

            $wallet_solde = Wallet::where('sid_user_id',  $this->walletAllPayments)->first();


            $paiements =  app(CeldingValeurService::class)->getTransactionsWallet(WalletPayment::class, $wallet_solde->id);


            if ($this->search != "") {


                $realod_search->where('wallet_id', $wallet_solde->id)->orwhereHas(
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
        }


        return view(
            'livewire.transaction-details.payment-users',
            [
                'all_payments'  => $paiements,
            ]
        );
    }


}
