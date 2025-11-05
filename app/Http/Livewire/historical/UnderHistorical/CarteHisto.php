<?php

namespace App\Http\Livewire\Historical\UnderHistorical;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Providers\Provider;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletDeposit;
use App\Http\Services\HistoriqueService;

class CarteHisto extends Component
{
    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchCarteHistorical = "";
    public $statusFilter = "";
    public  $startDate = "";
    public  $endDate =  "";



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
                    'sum_commissions' => app(HistoriqueService::class)->getWalletCommissionUserProperty(WalletDeposit::class , 'wallet_deposits.wallet_id' ,'wallet_deposits.created_by' ,'wallet_deposits.status' ,'wallet_deposits.*'),

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
                    'sum_commissions' => app(HistoriqueService::class)->getWalletAdminCommissionProperty(WalletDeposit::class , 'wallet_deposits.wallet_id','wallet_deposits.status' ,'wallet_deposits.*'),

                ]
            );
        }
    }

}
