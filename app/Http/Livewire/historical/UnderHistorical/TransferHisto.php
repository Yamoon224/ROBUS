<?php

namespace App\Http\Livewire\Historical\UnderHistorical;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Providers\Provider;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletTransaction;
use App\Http\Services\HistoriqueService;

class TransferHisto extends Component
{
    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchTransferHistorical = "";
    public $statusTransferFilter = "";
    public  $startDate = "";
    public  $endDate =  "";

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


    public function resetTransactionField()
    {

        $this->emit('modal-hide', 'hide');
    }
    public function render()
    {

        $transfers =  WalletTransaction::query();

        $walletUser = Auth::guard('web')->user();

        if ($walletUser->wallet->sidUser->is_under_distributer == true) {

            if ($this->startDate && $this->endDate) {

                $transfers->whereBetween('wallet_transactions.created_at', [$this->startDate, $this->endDate]);
            }

            if ($this->searchTransferHistorical != "") {
                $this->resetPage();
                $transfers->orwhereHas(
                    'transaction',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchTransferHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchTransferHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchTransferHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_transactions.amount", "LIKE",  "%" . $this->searchTransferHistorical . "%")
                    ->orWhere("wallet_transactions.num_wallet", "LIKE",  "%" . $this->searchTransferHistorical . "%")
                    ->orWhere("wallet_transactions.phone", "LIKE",  "%" . $this->searchTransferHistorical . "%");
            }

            if ($this->statusTransferFilter != "") {
                $transfers->where("wallet_transactions.status", $this->statusTransferFilter);
            }

            return view(
                'livewire.historical.under-historical.transfer-histo',
                [
                    'user' => $walletUser,
                    'transfer_historicals' => $transfers->join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->select('wallet_transactions.*')->orderBy('wallet_transactions.created_at', 'desc')->paginate(15),
                    'usersCountry' =>  $walletUser->country->currencie->name,
                    'sum_commissions' => app(HistoriqueService::class)->getWalletCommissionUserProperty(WalletTransaction::class , 'wallet_transactions.wallet_id' ,'wallet_transactions.created_by' ,'wallet_transactions.status' ,'wallet_transactions.*'),

                ]
            );
        } else
        {

            if ($this->startDate && $this->endDate) {

                $transfers->whereBetween('wallet_transactions.created_at', [$this->startDate, $this->endDate]);
            }

            if ($this->searchTransferHistorical != "") {
                $this->resetPage();
                $transfers->orwhereHas(
                    'transaction',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchTransferHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchTransferHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchTransferHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_transactions.amount", "LIKE",  "%" . $this->searchTransferHistorical . "%")
                    ->orWhere("wallet_transactions.num_wallet", "LIKE",  "%" . $this->searchTransferHistorical . "%")
                    ->orWhere("wallet_transactions.phone", "LIKE",  "%" . $this->searchTransferHistorical . "%");
            }

            if ($this->statusTransferFilter != "") {
                $transfers->where("wallet_transactions.status", $this->statusTransferFilter);
            }

            return view(
                'livewire.historical.under-historical.transfer-histo',
                [
                    'user' => $walletUser,
                    'transfer_historicals' => $transfers->orderBy('wallet_transactions.created_at', 'desc')->paginate(15),
                    'usersCountry' =>  $walletUser->country->currencie->name,
                    'sum_commissions' => app(HistoriqueService::class)->getWalletAdminCommissionProperty(WalletTransaction::class , 'wallet_transactions.wallet_id','wallet_transactions.status' ,'wallet_transactions.*'),

                ]
            );
        }
    }

}
