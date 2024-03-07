<?php

namespace App\Http\Livewire\TransactionDetails;

use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletPhoneCredit;
use Livewire\Component;
use Livewire\WithPagination;

class PhoneCreditUsers extends Component
{

    use WithPagination;

    public $walletAllPhoneCredits;
    public $search = "";


    public function mount($walletAllPhoneCredits)
    {
        // Convertir l'objet en tableau
        $this->walletAllPhoneCredits =  $walletAllPhoneCredits;
    }


    public function render()
    {


        $sid_user = SidUser::whereId($this->walletAllPhoneCredits)->first();

        $realod_search =  WalletPhoneCredit::query();


        if ($sid_user->is_distributer) {


            $phone_credits =  $this->getTransactionOperatorsWallet(WalletPhoneCredit::class, $this->walletAllPhoneCredits);


            if ($this->search != "") {

                $realod_search->where('updated_by', $this->walletAllPhoneCredits)->orwhereHas(
                    'paymentPhone',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchPhoneHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchPhoneHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchPhoneHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_phone_credits.amount", "LIKE",  "%" . $this->searchPhoneHistorical . "%")
                    ->orWhere("wallet_phone_credits.phone", "LIKE",  "%" . $this->searchPhoneHistorical . "%");
            }
        } else {

            $wallet_solde = Wallet::where('sid_user_id',  $this->walletAllPhoneCredits)->first();

            $phone_credits =  $this->getTransactionsWallet(WalletPhoneCredit::class, $wallet_solde->id);


            if ($this->search != "") {

                $realod_search->where('wallet_id', $wallet_solde->id)->orwhereHas(
                    'paymentPhone',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchPhoneHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchPhoneHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchPhoneHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_phone_credits.amount", "LIKE",  "%" . $this->searchPhoneHistorical . "%")
                    ->orWhere("wallet_phone_credits.phone", "LIKE",  "%" . $this->searchPhoneHistorical . "%");
            }
        }


        return view(
            'livewire.transaction-details.phone-credit-users',
            [
                'all_phonecredits'  => $phone_credits,
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
