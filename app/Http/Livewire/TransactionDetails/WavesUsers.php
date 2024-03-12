<?php

namespace App\Http\Livewire\TransactionDetails;

use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletWave;
use Livewire\Component;
use Livewire\WithPagination;

class WavesUsers extends Component
{

    use WithPagination;

    public $walletWave;
    public $search = "";


    public function mount($walletWave)
    {
        // Convertir l'objet en tableau
        $this->walletWave =  $walletWave;
    }


    public function render()
    {

        $sid_user = SidUser::whereId($this->walletWave)->first();

        $realod_search =  WalletWave::query();

        if ($sid_user->is_distributer) {


            $waves =  $this->getTransactionOperatorsWallet(WalletWave::class, $this->walletWave);


            if ($this->search != "") {


                $realod_search->where('updated_by', $this->walletWave)->orWhereHas(
                    'transactionWave',
                    function ($query) {
                        $query->where('operator_transaction_name', 'like', '%' . $this->search . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->search   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->search  . '%');
                    }
                )
                    ->orWhere("wallet_waves.amount", "like",  "%" . $this->search . "%")
                    ->orWhere("wallet_waves.phone", "like",  "%" . $this->search . "%")
                    ->orWhere("wallet_waves.account_wallet", "like",  "%" . $this->search . "%");
            }
        } else {

            $wallet_solde = Wallet::where('sid_user_id',  $this->walletWave)->first();

            $waves =  $this->getTransactionsWallet(WalletWave::class, $wallet_solde->id);


            if ($this->search != "") {


                $realod_search->where('wallet_id', $wallet_solde->id)
                    ->orWhere('account_wallet',   $wallet_solde->wallet_id)->orWhereHas(
                        'transactionWave',
                        function ($query) {
                            $query->where('operator_transaction_name', 'like', '%' . $this->search . '%')
                                ->orWhere('transaction_id', 'like', '%' .  $this->search   . '%')
                                ->orWhere('trans_comp_id', 'like', '%' .  $this->search  . '%');
                        }
                    )
                    ->orWhere("wallet_waves.amount", "like",  "%" . $this->search . "%")
                    ->orWhere("wallet_waves.phone", "like",  "%" . $this->search . "%")
                    ->orWhere("wallet_waves.account_wallet", "like",  "%" . $this->search . "%");
            }
        }


        return view(
            'livewire.transaction-details.waves-users',
            [
                'all_waves'  => $waves,
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
