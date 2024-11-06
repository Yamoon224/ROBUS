<?php

namespace App\Http\Livewire\TransactionDetails;

use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionsUsers extends Component
{

    use WithPagination;

    public $walletAllTransactions;
    public $search = "";


    public function mount($walletAllTransactions)
    {
        $this->walletAllTransactions =  $walletAllTransactions;
    }


    public function render()
    {

        $sid_user = SidUser::whereId($this->walletAllTransactions)->first();

        $realod_search =  WalletTransaction::query();

        if ($sid_user->is_distributer) {

            $wallet_transaction =  $this->getTransactionOperatorsWallet(WalletTransaction::class, $this->walletAllTransactions);


            if ($this->search != "") {


                $realod_search->where('updated_by',  $this->walletAllTransactions)->orwhereHas(
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
        } else {

            $wallet_solde = Wallet::where('sid_user_id',  $this->walletAllTransactions)->first();

            $wallet_transaction =  $this->getTransactionUserWallet(WalletTransaction::class, $wallet_solde->id, 'num_wallet',  $wallet_solde->wallet_id);

            if ($this->search != "") {


                $realod_search->where('wallet_id', $wallet_solde->id)
                    ->orWhere('account_wallet',   $wallet_solde->wallet_id)->orwhereHas(
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
        }


        return view(
            'livewire.transaction-details.transactions-users',
            [
                'all_transactions'  => $wallet_transaction,
            ]
        );
    }


    public function getTransactionUserWallet($wallet, $id, $num_wallet, $wallet_user)
    {
        return  $wallet::where('wallet_id', $id)
            ->orWhere($num_wallet,  $wallet_user)
            ->orderBy('created_at', 'desc')->paginate(20);
    }

    public function getTransactionOperatorsWallet($wallet, $id)
    {
        return  $wallet::where('updated_by', $id)
            ->orderBy('created_at', 'desc')->paginate(20);
    }
}
