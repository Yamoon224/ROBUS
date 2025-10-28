<?php

namespace App\Http\Livewire\TransactionDetails;

use Livewire\Component;
use App\Http\Services\CeldingValeurService;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletTresortMoney;
use Livewire\WithPagination;

class TresortMoneys extends Component
{

    use WithPagination;
    public $walletTresorts;
    public $search = "";


    public function mount($walletTresorts)
    {
        // Convertir l'objet en tableau
        $this->walletTresorts =  $walletTresorts;
    }

    public function render()
    {

        $sid_user = SidUser::whereId($this->walletTresorts)->first();

        $realod_search =  WalletTresortMoney::query();

        if ($sid_user->is_distributer) {


            $tresorts =  app(CeldingValeurService::class)->getTransactionOperatorsWallet(WalletTresortMoney::class, $this->walletTresorts);

            if ($this->search != "") {

                $realod_search->where('updated_by', $this->walletTresorts)->orwhereHas(
                    'transaction',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchCarteHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchCarteHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchCarteHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_tresort_money.amount", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_tresort_money.full_name", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_tresort_money.phone", "LIKE",  "%" . $this->searchCarteHistorical . "%");
            }
        } else {

            $wallet_solde = Wallet::where('sid_user_id',  $this->walletTresorts)->first();

            $tresorts =  app(CeldingValeurService::class)->getTransactionsWallet(WalletTresortMoney::class, $wallet_solde->id);

            if ($this->search != "") {


                $realod_search->where('wallet_id', $wallet_solde->id)->orwhereHas(
                    'transaction',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchCarteHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchCarteHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchCarteHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_tresort_money.amount", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_tresort_money.full_name", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_tresort_money.phone", "LIKE",  "%" . $this->searchCarteHistorical . "%");
            }
        }


        return view(
            'livewire.transaction-details.tresort-moneys',
            [
                'all_tresortMoney'  => $tresorts,
            ]
        );
    }

}
