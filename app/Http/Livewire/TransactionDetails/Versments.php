<?php

namespace App\Http\Livewire\TransactionDetails;

use Livewire\Component;
use App\Http\Services\CeldingValeurService;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletVersement;
use Livewire\WithPagination;

class Versments extends Component
{

       use WithPagination;
    public $walletVersements;
    public $search = "";


    public function mount($walletVersements)
    {
        // Convertir l'objet en tableau
        $this->walletVersements =  $walletVersements;
    }

    public function gotoPagePaie($page)
    {
        $this->setPage($page);
    }

    public function nextPagePaie()
    {
        $this->setPage($this->page + 1);
    }

    public function previousPagePaie()
    {
        $this->setPage(max($this->page - 1, 1));
    }

    public function render()
    {

        $sid_user = SidUser::whereId($this->walletVersements)->first();

        $realod_search =  WalletVersement::query();

        if ($sid_user->is_distributer) {


            $tresorts =  app(CeldingValeurService::class)->getTransactionOperatorsWallet(WalletVersement::class, $this->walletVersements);

            if ($this->search != "") {

                $realod_search->where('updated_by', $this->walletVersements)->orwhereHas(
                    'transaction',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchCarteHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchCarteHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchCarteHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_versements.amount", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_versements.num_accompte", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_versements.bank_code", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_versements.guichet_code", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_versements.num_authers", "LIKE",  "%" . $this->searchCarteHistorical . "%");
            }
        } else {

            $wallet_solde = Wallet::where('sid_user_id',  $this->walletVersements)->first();

            $tresorts =  app(CeldingValeurService::class)->getTransactionsWallet(WalletVersement::class, $wallet_solde->id);


            if ($this->search != "") {


                $realod_search->where('wallet_id', $wallet_solde->id)->orwhereHas(
                    'transaction',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchCarteHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchCarteHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchCarteHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_versements.amount", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_versements.num_accompte", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_versements.bank_code", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_versements.guichet_code", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_versements.num_authers", "LIKE",  "%" . $this->searchCarteHistorical . "%");
            }
        }


        return view(
            'livewire.transaction-details.versments',
            [
                'verment_historicals'  => $tresorts,
            ]
        );
    }
    
 
}
