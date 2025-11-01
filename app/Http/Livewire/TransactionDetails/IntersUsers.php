<?php

namespace App\Http\Livewire\TransactionDetails;

use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletInter;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Services\CeldingValeurService;

class IntersUsers extends Component
{
    use WithPagination;

    public $walletAllInters;
    public $search = "";


    public function mount($walletAllInters)
    {
        // Convertir l'objet en tableau
        $this->walletAllInters =  $walletAllInters;
    }


    public function render()
    {

        $sid_user = SidUser::whereId($this->walletAllInters)->first();

        $realod_search =  WalletInter::query();

        if ($sid_user->is_distributer) {


            $wallet_transaction =  app(CeldingValeurService::class)->getTransactionOperatorsWallet(WalletInter::class, $this->walletAllInters);


            if ($this->search != "") {


                $realod_search->where('updated_by', $this->walletAllInters)
                    ->orwhereHas(
                        'transactionInter',
                        function ($query) {
                            $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchInterHistorical . '%')
                                ->orWhere('transaction_id', 'like', '%' .  $this->searchInterHistorical   . '%')
                                ->orWhere('trans_comp_id', 'like', '%' .  $this->searchInterHistorical  . '%');
                        }
                    )
                    ->orWhere("wallet_inters.amount", "LIKE",  "%" . $this->searchInterHistorical . "%")
                    ->orWhere("wallet_inters.number_compte", "LIKE",  "%" . $this->searchInterHistorical . "%")
                    ->orWhere("wallet_inters.phone", "LIKE",  "%" . $this->searchInterHistorical . "%");
            }
        } else {

            $wallet_solde = Wallet::where('sid_user_id',  $this->walletAllInters)->first();

            $wallet_transaction =  app(CeldingValeurService::class)->getTransactionUserWallet(WalletInter::class, $wallet_solde->id, 'number_compte',  $wallet_solde->wallet_id);

            if ($this->search != "") {


                $realod_search->where('wallet_id', $wallet_solde->id)
                    ->orWhere('number_compte',   $wallet_solde->wallet_id)->orwhereHas(
                        'transactionInter',
                        function ($query) {
                            $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchInterHistorical . '%')
                                ->orWhere('transaction_id', 'like', '%' .  $this->searchInterHistorical   . '%')
                                ->orWhere('trans_comp_id', 'like', '%' .  $this->searchInterHistorical  . '%');
                        }
                    )
                    ->orWhere("wallet_inters.amount", "LIKE",  "%" . $this->searchInterHistorical . "%")
                    ->orWhere("wallet_inters.number_compte", "LIKE",  "%" . $this->searchInterHistorical . "%")
                    ->orWhere("wallet_inters.phone", "LIKE",  "%" . $this->searchInterHistorical . "%");
            }
        }


        return view(
            'livewire.transaction-details.inters-users',
            [
                'inter_historicals'  => $wallet_transaction,
            ]
        );
    }

}
