<?php

namespace App\Http\Livewire\TransactionDetails;

use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletReload;
use Livewire\Component;
use Livewire\WithPagination;

class ReloadTransactions extends Component
{
    use WithPagination;

    public $walletAllReloads;
    public $search = "";


    public function mount($walletAllReloads)
    {
        // Convertir l'objet en tableau
        $this->walletAllReloads =  $walletAllReloads;
    }


    public function render()
    {


        
        $wallet_solde = Wallet::where('sid_user_id',  $this->walletAllReloads)->first();

  
        $reaload_uv =  $this->getTransactionUserWallet(WalletReload::class, $wallet_solde->id, 'account_wallet',  $wallet_solde->wallet_id);

        $realod_search =  WalletReload::query();

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


        return view('livewire.transaction-details.reload-transactions', [
            'all_reloads'  => $reaload_uv,
        ]);
    }

    public function getTransactionUserWallet($wallet, $id, $num_wallet, $wallet_user)
    {
        return  $wallet::where('wallet_id', $id)
            ->orWhere($num_wallet,  $wallet_user)
            ->orderBy('created_at', 'desc')->paginate(20);
    }
}
