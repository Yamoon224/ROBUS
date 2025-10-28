<?php

namespace App\Http\Livewire\TransactionDetails;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Wallets\Wallet;
use App\Models\Sid\SidUser;
use App\Http\Services\CeldingValeurService;
use App\Models\Wallets\WalletPush;

class Pushs extends Component
{
     use WithPagination;

    public $walletPushs;
    public $search = "";


    public function mount($walletPushs)
    {
        // Convertir l'objet en tableau
        $this->walletPushs =  $walletPushs;
    }


    public function render()
    {

        $sid_user = SidUser::whereId($this->walletPushs)->first();

        $realod_search =  WalletPush::query();

        if ($sid_user->is_distributer) {


            $pushs =  app(CeldingValeurService::class)->getTransactionOperatorsWallet(WalletPush::class, $this->walletPushs);

            if ($this->search != "") {

                $realod_search->where('updated_by', $this->walletPushs)->orwhereHas(
                    'transaction',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchCarteHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchCarteHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchCarteHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_pushes.amount", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_pushes.full_name", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_pushes.phone", "LIKE",  "%" . $this->searchCarteHistorical . "%");
            }
        } else {

            $wallet_solde = Wallet::where('sid_user_id',  $this->walletPushs)->first();

            $pushs =  app(CeldingValeurService::class)->getTransactionsWallet(WalletPush::class, $wallet_solde->id);


            if ($this->search != "") {


                $realod_search->where('wallet_id', $wallet_solde->id)->orwhereHas(
                    'transaction',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchCarteHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchCarteHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchCarteHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_pushes.amount", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_pushes.full_name", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_pushes.phone", "LIKE",  "%" . $this->searchCarteHistorical . "%");
            }
        }


        return view(
            'livewire.transaction-details.pushs',
            [
                'all_pushs'  => $pushs,
            ]
        );
    }

}
