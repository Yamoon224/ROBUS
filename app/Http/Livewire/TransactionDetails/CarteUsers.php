<?php

namespace App\Http\Livewire\TransactionDetails;

use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletDeposit;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Services\CeldingValeurService;

class CarteUsers extends Component
{
    use WithPagination;

    public $walletAllDeposits;
    public $search = "";


    public function mount($walletAllDeposits)
    {
        // Convertir l'objet en tableau
        $this->walletAllDeposits =  $walletAllDeposits;
    }


    public function render()
    {

        $sid_user = SidUser::whereId($this->walletAllDeposits)->first();

        $realod_search =  WalletDeposit::query();

        if ($sid_user->is_distributer) {


            $xbets =  app(CeldingValeurService::class)->getTransactionOperatorsWallet(WalletDeposit::class, $this->walletAllDeposits);

            if ($this->search != "") {

                $realod_search->where('updated_by', $this->walletAllDeposits)->orwhereHas(
                    'deposit',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchCarteHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchCarteHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchCarteHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_deposits.amount", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_deposits.num_carte", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_deposits.name", "LIKE",  "%" . $this->searchCarteHistorical . "%");
            }
        } else {

            $wallet_solde = Wallet::where('sid_user_id',  $this->walletAllDeposits)->first();

            $xbets =  app(CeldingValeurService::class)->getTransactionsWallet(WalletDeposit::class, $wallet_solde->id);


            if ($this->search != "") {


                $realod_search->where('wallet_id', $wallet_solde->id)->orwhereHas(
                    'deposit',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchCarteHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchCarteHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchCarteHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_deposits.amount", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_deposits.num_carte", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_deposits.name", "LIKE",  "%" . $this->searchCarteHistorical . "%");
            }
        }


        return view(
            'livewire.transaction-details.carte-users',
            [
                'all_deposits'  => $xbets,
            ]
        );
    }


}
