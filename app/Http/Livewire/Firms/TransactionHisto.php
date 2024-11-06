<?php

namespace App\Http\Livewire\Firms;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Providers\Provider;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletTransaction;

class TransactionHisto extends Component
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


    public function render()
    {
        $walletId = firm()->wallet->id;
        $walletUser = firm();
        $transfersQuery = WalletTransaction::where('wallet_id', $walletId);
    
        // Filtrage par date
        if ($this->startDate && $this->endDate) {
            $transfersQuery->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }
    
        if (!empty($this->searchTransferHistorical)) {
            $this->resetPage();
            $transfersQuery->where(function ($query) {
                $query->whereHas('transaction', function ($subQuery) {
                    $subQuery->where('operator_transaction_name', 'LIKE', '%' . $this->searchTransferHistorical . '%')
                        ->orWhere('transaction_id', 'LIKE', '%' . $this->searchTransferHistorical . '%')
                        ->orWhere('trans_comp_id', 'LIKE', '%' . $this->searchTransferHistorical . '%');
                })
                ->orWhere('amount', 'LIKE', '%' . $this->searchTransferHistorical . '%')
                ->orWhere('num_wallet', 'LIKE', '%' . $this->searchTransferHistorical . '%')
                ->orWhere('phone', 'LIKE', '%' . $this->searchTransferHistorical . '%');
            });
        }
    
        if (!empty($this->statusTransferFilter)) {
            $transfersQuery->where('status', $this->statusTransferFilter);
        }
    
        $transferHistoricals = $transfersQuery->orderByDesc('created_at')->paginate(15);
    
        return view('livewire.firms.transaction-histo', [
            'user' => $walletUser,
            'transfer_historicals' => $transferHistoricals,
            'usersCountry' => $walletUser->country->currencie->name,
            'sum_commissions' => $this->WalletRelaod,
            'providers' => $this->NationTransaction,
        ]);
    }
    

    public function getNationTransactionProperty()
    {
        $walletUser = firm();

        $transaction_nationnal = Provider::join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', $walletUser->country->id)->where('providers.status', true)
            ->where('providers.provider_type_id',  1)->select('providers.*')->orderBy('providers.name', 'asc')->get();

        return $transaction_nationnal;
    }

    // Admin 
    public function getWalletRelaodProperty()
    {
        $data = [];
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', firm()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->get() as $bank) {
            $data[] = $bank->commission_companie;
        }

        return array_sum($data);
    }

    // User 
    public function getWalletRelaodUserProperty()
    {

        $data = [];
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', firm()->country->id)->where('wallet_transactions.created_by', firm()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->get() as $bank) {
            $data[] = $bank->commission_companie;
        }

        return array_sum($data);
    }
}
