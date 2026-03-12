<?php

namespace App\Http\Livewire\Historical;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Facades\DB;
use App\Models\Wallets\WalletInter;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\HistoriqueService;

class Interhisto extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchInterHistorical = "";
    public $statusInterFilter = "";
    public  $startDate = "";
    public  $endDate =  "";
    public $searchTransferHistorical = "";
    public $statusTransferFilter = "";

    public function resetTransactionField()
    {

        $this->emit('modal-hide', 'hide');
    }

    public function render()
    {

        $inters =  WalletInter::whereIsWeb(false);

        $walletUser = Auth::guard('web')->check()
            ? Auth::guard('web')->user()
            : (Auth::guard('integration')->check()
                ? Auth::guard('integration')->user()
                : null);


        if ($walletUser->is_under_distributer || $walletUser->is_api) {
            $inters->where('wallet_inters.created_by', sabdy()->id);
        }


        if ($this->startDate && $this->endDate) {

            // $inters->whereBetween('wallet_inters.created_at', [$this->startDate, $this->endDate]);

            return view(
                'livewire.historical.interhisto',
                [
                    'user' =>  app(HistoriqueService::class)->getTransactionsDaysProperty(WalletInter::class , $this->startDate,$this->endDate , 'wallet_inters.created_by' , 'wallet_inters.updated_by'),
                    'inter_historicals' =>  $inters->whereBetween('wallet_inters.created_at', [$this->startDate, $this->endDate])->orderBy('wallet_inters.created_at', 'desc')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,

                ]
            );
        }

        if ($this->searchInterHistorical != "") {
            $this->resetPage();

            $inters->orwhereHas(
                'transactionInter',
                function ($query) {
                    $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchInterHistorical . '%')
                        ->orWhere('transaction_id', 'like', '%' .  $this->searchInterHistorical   . '%')
                        ->orWhere('trans_comp_id', 'like', '%' .  $this->searchInterHistorical  . '%');
                }
            )
                ->orWhere("wallet_inters.amount", "LIKE",  "%" . $this->searchInterHistorical . "%")
                ->orWhere("wallet_inters.number_compte", "LIKE",  "%" . $this->searchInterHistorical . "%")
                ->orWhere("wallet_inters.phone", "LIKE",  "%" . $this->searchInterHistorical . "%");
        }


        if ($this->statusInterFilter != "") {

            // $inters->where("wallet_inters.status", $this->statusInterFilter);


            return view(
                'livewire.historical.interhisto',
                [
                    'user' =>  app(HistoriqueService::class)->getTransactionsStatusProperty(WalletInter::class , 'wallet_inters.is_web', 'wallet_inters.status' , $this->statusTransferFilter , 'wallet_inters.created_by' , 'wallet_inters.updated_by'),
                    'inter_historicals' =>  $inters->where("wallet_inters.status", $this->statusInterFilter)->orderBy('wallet_inters.created_at', 'desc')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,

                ]
            );
        }

        return view(
            'livewire.historical.interhisto',
            [
                 'user' =>  app(HistoriqueService::class)->getTransactionsAllDaysProperty(WalletInter::class , 'wallet_inters.is_web' , 'wallet_inters.created_by' , 'wallet_inters.updated_by'),

                'inter_historicals' =>  $inters->orderBy('wallet_inters.created_at', 'desc')->paginate(15),
                'usersCountry' =>   $walletUser->country->currencie->name,

            ]
        );
    }

}
