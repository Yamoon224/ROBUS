<?php

namespace App\Http\Livewire\TransactionDetails;

use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletBank;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Services\CeldingValeurService;

class VirementsUsers extends Component
{

    use WithPagination;

    public $walletAllVirements;
    public $search = "";


    public function mount($walletAllVirements)
    {
        // Convertir l'objet en tableau
        $this->walletAllVirements =  $walletAllVirements;
    }

    public function render()
    {


        $sid_user = SidUser::whereId($this->walletAllVirements)->first();
        $realod_search =  WalletBank::query();

        if ($sid_user->is_distributer) {

            $virements =  app(CeldingValeurService::class)->getTransactionOperatorsWallet(WalletBank::class, $this->walletAllVirements);

            if ($this->search != "") {

                $realod_search->where('updated_by', $this->walletAllVirements)->orwhereHas(
                    'paymentBank',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchBankHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchBankHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchBankHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_banks.amount", "LIKE",  "%" . $this->searchBankHistorical . "%")
                    ->orWhere("wallet_banks.phone", "LIKE",  "%" . $this->searchBankHistorical . "%")
                    ->orWhere("wallet_banks.number_compte", "LIKE",  "%" . $this->searchBankHistorical . "%")
                    ->orWhere("wallet_banks.firstname", "LIKE",  "%" . $this->searchBankHistorical . "%")
                    ->orWhere("wallet_banks.lastname", "LIKE",  "%" . $this->searchBankHistorical . "%");
            }
        } else {

            $wallet_solde = Wallet::where('sid_user_id',  $this->walletAllVirements)->first();

            $virements =  app(CeldingValeurService::class)->getTransactionsWallet(WalletBank::class, $wallet_solde->id);


            if ($this->search != "") {

                $realod_search->where('wallet_id', $wallet_solde->id)->orwhereHas(
                    'paymentBank',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchBankHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchBankHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchBankHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_banks.amount", "LIKE",  "%" . $this->searchBankHistorical . "%")
                    ->orWhere("wallet_banks.phone", "LIKE",  "%" . $this->searchBankHistorical . "%")
                    ->orWhere("wallet_banks.number_compte", "LIKE",  "%" . $this->searchBankHistorical . "%")
                    ->orWhere("wallet_banks.firstname", "LIKE",  "%" . $this->searchBankHistorical . "%")
                    ->orWhere("wallet_banks.lastname", "LIKE",  "%" . $this->searchBankHistorical . "%");
            }
        }


        return view(
            'livewire.transaction-details.virements-users',
            [
                'all_virements'  => $virements,
            ]
        );
    }

}
