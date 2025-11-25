<?php

namespace App\Http\Livewire\Historical;

use Livewire\Component;
use App\Http\Services\HistoriqueService;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use App\Models\Wallets\WalletDjamo;

class DjamoHisto extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchHistorical = "";
    public $statusFilter = "";
    public  $startDate = "";
    public  $endDate =  "";

    public function render()
    {

        $walletTransaction =  WalletDjamo::whereIsWeb(false);

        $walletUser = Auth::guard('web')->user();

        $walletUser = Auth::guard('web')->check()
            ? Auth::guard('web')->user()
            : (Auth::guard('integration')->check()
                ? Auth::guard('integration')->user()
                : null);

        if ($walletUser->is_under_distributer  || $walletUser->is_api) {
            $walletTransaction->where('wallet_djamos.created_by', $walletUser->id);
        }


        if ($this->startDate && $this->endDate) {


            return view(
                'livewire.historical.djamo-histo',
                [
                    'user' =>  app(HistoriqueService::class)->getTransactionsDaysProperty(WalletDjamo::class, $this->startDate, $this->endDate, 'wallet_djamos.created_by', 'wallet_djamos.updated_by'),
                    'djamo_historicals' =>   $walletTransaction->join('wallets', 'wallet_djamos.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_djamos.is_web', false)->whereBetween('wallet_djamos.created_at', [$this->startDate, $this->endDate])->orderBy('wallet_djamos.created_at', 'desc')->select('wallet_djamos.*')->paginate(15),
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
                ->orWhere("wallet_djamos.amount", "LIKE",  "%" . $this->searchHistorical . "%")
                ->orWhere("wallet_djamos.phone", "LIKE",  "%" . $this->searchHistorical . "%");
        }

        if ($this->statusFilter != "") {


            return view(
                'livewire.historical.djamo-histo',
                [
                    'user' =>  app(HistoriqueService::class)->getTransactionsStatusProperty(WalletDjamo::class, 'wallet_djamos.is_web', 'wallet_djamos.status', $this->statusFilter, 'wallet_djamos.created_by', 'wallet_djamos.updated_by'),
                    'djamo_historicals' =>   $walletTransaction->join('wallets', 'wallet_djamos.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_djamos.is_web', false)->orderBy('wallet_djamos.created_at', 'desc')->where("wallet_djamos.status", $this->statusFilter)->select('wallet_djamos.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,

                ]
            );
        }

        return view(
            'livewire.historical.djamo-histo',
            [
                'user' =>  app(HistoriqueService::class)->getTransactionsAllDaysProperty(WalletDjamo::class, 'wallet_djamos.is_web', 'wallet_djamos.created_by', 'wallet_djamos.updated_by'),
                'djamo_historicals' =>  $walletTransaction->join('wallets', 'wallet_djamos.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $walletUser->country->id)->where('wallet_djamos.is_web', false)->orderBy('wallet_djamos.created_at', 'desc')->select('wallet_djamos.*')->paginate(15),
                'usersCountry' =>   $walletUser->country->currencie->name,
            ]
        );
    }
}
