<?php

namespace App\Http\Livewire\Historical\UnderHistorical;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Providers\Provider;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletDeposit;

class CarteHisto extends Component
{
    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchCarteHistorical = "";
    public $statusFilter = "";
    public  $startDate = "";
    public  $endDate =  "";

    // edit
    public $reloadcard_description_edit;
    public $provider_reloadcard_edit;
    public $country_reloadcard_edit;
    public $amount_reloadcard_edit;
    public $account_number_reloadcard_edit;
    public $phone_reloadcard_edit;
    public $amount_reloadcard_robus_edit;
    public $country_reloadcard_robus_edit;
    public $reloadcardStatus;
    public $name_reloadcard_edit;
    public $reloadcardId;




    public function render()
    {

        $cartes =  WalletDeposit::query();

        $walletUser = Auth::guard('web')->user();

        if ( $walletUser->wallet->sidUser->is_under_distributer == true) {

            if ($this->startDate && $this->endDate) {

                $cartes->whereBetween('wallet_deposits.created_at', [$this->startDate, $this->endDate])->orderBy('wallet_deposits.created_at', 'desc')->paginate(15);
            }

            if ($this->searchCarteHistorical != "") {

                $this->resetPage();

                $cartes->orwhereHas(
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

            if ($this->statusFilter != "") {
                $cartes->where("wallet_deposits.status", $this->statusFilter);
            }

            return view(
                'livewire.historical.under-historical.carte-histo',
                [
                    'user' => $walletUser,
                    'card_historicals' => $cartes->join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_deposits.created_by', Auth::guard('web')->user()->id)->orderBy('wallet_deposits.created_at', 'desc')->select('wallet_deposits.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,
                    'sum_commissions' => $this->WalletDepsositsUser,
                    'provider_reloads' =>  $this->ReloadTransaction,

                ]
            );
        } else 
        {

            if ($this->startDate && $this->endDate) {

                $cartes->whereBetween('wallet_deposits.created_at', [$this->startDate, $this->endDate]);
            }

            if ($this->searchCarteHistorical != "") {

                $this->resetPage();

                $cartes->orwhereHas(
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

            if ($this->statusFilter != "") {
                $cartes->where("wallet_deposits.status", $this->statusFilter);
            }

            return view(
                'livewire.historical.under-historical.carte-histo',
                [
                    'user' => $walletUser,
                    'card_historicals' => $cartes->join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->orderBy('wallet_deposits.created_at', 'desc')->select('wallet_deposits.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,
                    'sum_commissions' => $this->WalletDepsosits,
                    'provider_reloads' =>  $this->ReloadTransaction,

                ]
            );
        }
    }


    public function getReloadTransactionProperty()
    {

        $walletUser = Auth::guard('web')->user();

        $reload_transaction = Provider::join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', $walletUser->country->id)->where('providers.status', true)
            ->where('providers.provider_type_id',  5)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')->get();

        return $reload_transaction;
    }
    // Admin 
    public function getWalletDepsositsProperty()
    {
        $data = [];
        foreach (WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_deposits.status', 'SUCCESS')->select('wallet_deposits.*')->get() as $bank) {
            $data[] = $bank->commissions_admin;
        }

        return array_sum($data);
    }

    // User 
    public function getWalletDepsositsUserProperty()
    {

        $data = [];
        foreach (WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_deposits.created_by', Auth::guard('web')->user()->id)->where('wallet_deposits.status', 'SUCCESS')->select('wallet_deposits.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }


}
