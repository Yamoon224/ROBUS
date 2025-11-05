<?php

namespace App\Http\Livewire\Historical;

use App\Models\Wallets\WalletTresortMoney;
use Livewire\Component;
use App\Http\Services\HistoriqueService;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class TresortHisto extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchHistorical = "";
    public $statusFilter = "";
    public  $startDate = "";
    public  $endDate =  "";

    public function render()
    {

        $walletTransaction =  WalletTresortMoney::whereIsWeb(false);

        $walletUser = Auth::guard('web')->user();

        if ($walletUser->is_under_distributer) {
            $walletTransaction->where('wallet_tresort_money.created_by', $walletUser->id);
        }


        if ($this->startDate && $this->endDate) {


            return view(
                'livewire.historical.tresort-histo',
                [
                    'user' =>  app(HistoriqueService::class)->getTransactionsDaysProperty(WalletTresortMoney::class, $this->startDate, $this->endDate, 'wallet_tresort_money.created_by', 'wallet_tresort_money.updated_by'),
                    'tresortMoney_historicals' =>   $walletTransaction->join('wallets', 'wallet_tresort_money.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_tresort_money.is_web', false)->whereBetween('wallet_tresort_money.created_at', [$this->startDate, $this->endDate])->orderBy('wallet_tresort_money.created_at', 'desc')->select('wallet_tresort_money.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,

                ]
            );
        }

        if ($this->searchHistorical != "") {

            $this->resetPage();

            $walletTransaction->orwhereHas(
                'transaction',
                function ($query) {
                    $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchHistorical . '%')
                        ->orWhere('transaction_id', 'like', '%' .  $this->searchHistorical   . '%')
                        ->orWhere('trans_comp_id', 'like', '%' .  $this->searchHistorical  . '%');
                }
            )
                ->orWhere("wallet_tresort_money.amount", "LIKE",  "%" . $this->searchHistorical . "%")
                ->orWhere("wallet_tresort_money.phone", "LIKE",  "%" . $this->searchHistorical . "%");
        }

        if ($this->statusFilter != "") {


            return view(
                'livewire.historical.tresort-histo',
                [
                    'user' =>  app(HistoriqueService::class)->getTransactionsStatusProperty(WalletTresortMoney::class, 'wallet_tresort_money.is_web', 'wallet_tresort_money.status', $this->statusFilter, 'wallet_tresort_money.created_by', 'wallet_tresort_money.updated_by'),
                    'tresortMoney_historicals' =>   $walletTransaction->join('wallets', 'wallet_tresort_money.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_tresort_money.is_web', false)->orderBy('wallet_tresort_money.created_at', 'desc')->where("wallet_tresort_money.status", $this->statusFilter)->select('wallet_tresort_money.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,

                ]
            );
        }

        return view(
            'livewire.historical.tresort-histo',
            [
                'user' =>  app(HistoriqueService::class)->getTransactionsAllDaysProperty(WalletTresortMoney::class, 'wallet_tresort_money.is_web', 'wallet_tresort_money.created_by', 'wallet_tresort_money.updated_by'),
                'tresortMoney_historicals' =>  $walletTransaction->join('wallets', 'wallet_tresort_money.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $walletUser->country->id)->where('wallet_tresort_money.is_web', false)->orderBy('wallet_tresort_money.created_at', 'desc')->select('wallet_tresort_money.*')->paginate(15),
                'usersCountry' =>   $walletUser->country->currencie->name,
            ]
        );
    }
    
}
