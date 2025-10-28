<?php

namespace App\Http\Livewire\TransactionDetails;

use App\Models\Wallets\WalletDjamo;
use Livewire\Component;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use Livewire\WithPagination;
use App\Http\Services\CeldingValeurService;


class Djamos extends Component
{
     use WithPagination;

    public $walletDjamos;
    public $search = "";


    public function mount($walletDjamos)
    {
        // Convertir l'objet en tableau
        $this->walletDjamos =  $walletDjamos;
    }


    public function render()
    {

        $sid_user = SidUser::whereId($this->walletDjamos)->first();

        $realod_search =  WalletDjamo::query();

        if ($sid_user->is_distributer) {


            $djamos =  app(CeldingValeurService::class)->getTransactionOperatorsWallet(WalletDjamo::class, $this->walletDjamos);

            if ($this->search != "") {

                $realod_search->where('updated_by', $this->walletDjamos)->orwhereHas(
                    'transaction',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchCarteHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchCarteHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchCarteHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_djamos.amount", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_djamos.full_name", "LIKE",  "%" . $this->searchCarteHistorical . "%");
            }
        } else {

            $wallet_solde = Wallet::where('sid_user_id',  $this->walletDjamos)->first();

            $djamos =  app(CeldingValeurService::class)->getTransactionsWallet(WalletDjamo::class, $wallet_solde->id);


            if ($this->search != "") {


                $realod_search->where('wallet_id', $wallet_solde->id)->orwhereHas(
                    'transaction',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchCarteHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchCarteHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchCarteHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_djamos.amount", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_djamos.full_name", "LIKE",  "%" . $this->searchCarteHistorical . "%");
            }
        }


        return view(
            'livewire.transaction-details.djamos',
            [
                'all_djamos'  => $djamos,
            ]
        );
    }


}
