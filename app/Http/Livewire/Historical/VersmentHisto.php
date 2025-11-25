<?php

namespace App\Http\Livewire\Historical;

use Livewire\Component;
use App\Http\Services\HistoriqueService;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use App\Models\Wallets\WalletVersement;

class VersmentHisto extends Component
{

     use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchHistorical = "";
    public $statusFilter = "";
    public  $startDate = "";
    public  $endDate =  "";

     public function render()
    {

        $walletTransaction =  WalletVersement::whereIsWeb(false);

              $walletUser = Auth::guard('web')->check()
            ? Auth::guard('web')->user()
            : (Auth::guard('integration')->check()
                ? Auth::guard('integration')->user()
                : null);

        if ($walletUser->is_under_distributer || $walletUser->is_api) {
            $walletTransaction->where('wallet_versements.created_by', $walletUser->id);
        }


        if ($this->startDate && $this->endDate) {


            return view(
                'livewire.historical.versment-histo',
                [
                    'user' =>  app(HistoriqueService::class)->getTransactionsDaysProperty(WalletVersement::class, $this->startDate, $this->endDate, 'wallet_versements.created_by', 'wallet_versements.updated_by'),
                    'verment_historicals' =>   $walletTransaction->join('wallets', 'wallet_versements.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_versements.is_web', false)->whereBetween('wallet_versements.created_at', [$this->startDate, $this->endDate])->orderBy('wallet_versements.created_at', 'desc')->select('wallet_versements.*')->paginate(15),
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
                ->orWhere("wallet_versements.amount", "LIKE",  "%" . $this->searchHistorical . "%")
                ->orWhere("wallet_versements.phone", "LIKE",  "%" . $this->searchHistorical . "%");
        }

        if ($this->statusFilter != "") {


            return view(
                'livewire.historical.versment-histo',
                [
                    'user' =>  app(HistoriqueService::class)->getTransactionsStatusProperty(WalletVersement::class, 'wallet_versements.is_web', 'wallet_versements.status', $this->statusFilter, 'wallet_versements.created_by', 'wallet_versements.updated_by'),
                    'verment_historicals' =>   $walletTransaction->join('wallets', 'wallet_versements.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_versements.is_web', false)->orderBy('wallet_versements.created_at', 'desc')->where("wallet_versements.status", $this->statusFilter)->select('wallet_versements.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,

                ]
            );
        }

        return view(
            'livewire.historical.versment-histo',
            [
                'user' =>  app(HistoriqueService::class)->getTransactionsAllDaysProperty(WalletVersement::class, 'wallet_versements.is_web', 'wallet_versements.created_by', 'wallet_versements.updated_by'),
                'verment_historicals' =>  $walletTransaction->join('wallets', 'wallet_versements.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $walletUser->country->id)->where('wallet_versements.is_web', false)->orderBy('wallet_versements.created_at', 'desc')->select('wallet_versements.*')->paginate(15),
                'usersCountry' =>   $walletUser->country->currencie->name,
            ]
        );
    }

}
