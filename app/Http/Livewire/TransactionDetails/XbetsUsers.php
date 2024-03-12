<?php

namespace App\Http\Livewire\TransactionDetails;

use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletXbet;
use Livewire\Component;
use Livewire\WithPagination;

class XbetsUsers extends Component
{

    use WithPagination;

    public $walletAllXbets;
    public $search = "";


    public function mount($walletAllXbets)
    {
        // Convertir l'objet en tableau
        $this->walletAllXbets =  $walletAllXbets;
    }


    public function render()
    {

        $sid_user = SidUser::whereId($this->walletAllXbets)->first();

        $realod_search =  WalletXbet::query();

        if ($sid_user->is_distributer) {


            $xbets =  $this->getTransactionOperatorsWallet(WalletXbet::class, $this->walletAllXbets);


            if ($this->search != "") {


                $realod_search->where('updated_by', $this->walletAllXbets)->orWhereHas(
                    'transaction',
                    function ($query) {
                        $query->where('operator_transaction_name', 'like', '%' . $this->search . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->search   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->search  . '%');
                    }
                )
                    ->orWhere("wallet_xbets.amount", "like",  "%" . $this->search . "%")
                    ->orWhere("wallet_xbets.phone", "like",  "%" . $this->search . "%")
                    ->orWhere("wallet_xbets.account_wallet", "like",  "%" . $this->search . "%");
            }
        } else {

            $wallet_solde = Wallet::where('sid_user_id',  $this->walletAllXbets)->first();

            $xbets =  $this->getTransactionsWallet(WalletXbet::class, $wallet_solde->id);


            if ($this->search != "") {


                $realod_search->where('wallet_id', $wallet_solde->id)->orWhereHas(
                    'transaction',
                    function ($query) {
                        $query->where('operator_transaction_name', 'like', '%' . $this->search . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->search   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->search  . '%');
                    }
                )
                    ->orWhere("wallet_xbets.amount", "like",  "%" . $this->search . "%")
                    ->orWhere("wallet_xbets.phone", "like",  "%" . $this->search . "%")
                    ->orWhere("wallet_xbets.account_wallet", "like",  "%" . $this->search . "%");
            }
        }


        return view(
            'livewire.transaction-details.xbets-users',
            [
                'all_xbets'  => $xbets,
            ]
        );
    }

    public function getTransactionsWallet($wallet, $id)
    {
        return  $wallet::where('wallet_id', $id)
            ->orderBy('created_at', 'desc')->paginate(20);
    }

    public function getTransactionOperatorsWallet($wallet, $id)
    {
        return  $wallet::where('updated_by', $id)
            ->orderBy('created_at', 'desc')->paginate(20);
    }
}
