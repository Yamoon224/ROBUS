<?php

namespace App\Http\Livewire\TransactionDetails;

use App\Models\Banks\BankCard;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersUsers extends Component
{

    use WithPagination;

    public $walletAllOrders;
    public $search = "";


    public function mount($walletAllOrders)
    {
        // Convertir l'objet en tableau
        $this->walletAllOrders =  $walletAllOrders;
    }


    public function render()
    {

        
        
        $wallet_solde = Wallet::where('sid_user_id',  $this->walletAllOrders)->first();


        $orders =  $this->getTransactionsWallet(BankCard::class, $wallet_solde->id);
       
        $realod_search =  BankCard::query();


        if ($this->search != "") {


            $realod_search->where('wallet_id', $wallet_solde->id)
                ->orWhere('account_wallet',   $wallet_solde->wallet_id)->orWhereHas(
                    'paymentReload',
                    function ($query) {
                        $query->where('operator_transaction_name', 'like', '%' . $this->search . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->search   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->search  . '%');
                    }
                )
                ->orWhere("wallet_reloads.amount", "like",  "%" . $this->search . "%")
                ->orWhere("wallet_reloads.phone", "like",  "%" . $this->search . "%")
                ->orWhere("wallet_reloads.account_wallet", "like",  "%" . $this->search . "%");
        }


        return view('livewire.transaction-details.orders-users',
        [
            'all_ordercards'  => $orders,
        ]);
    }

    public function getTransactionsWallet($wallet, $id)
    {
        return  $wallet::where('wallet_id', $id)
            ->orderBy('created_at', 'desc')->paginate(20);
    }
}
