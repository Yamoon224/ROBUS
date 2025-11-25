<?php

namespace App\Http\Livewire\Historical;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletPush;
use App\Http\Services\HistoriqueService;

class PushHisto extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchHistorical = "";
    public $statusFilter = "";
    public  $startDate = "";
    public  $endDate =  "";

    public function render()
    {
        
        $walletTransaction =  WalletPush::whereIsWeb(false);

       
        $walletUser = Auth::guard('web')->check()
            ? Auth::guard('web')->user()
            : (Auth::guard('integration')->check()
                ? Auth::guard('integration')->user()
                : null);

        if ($walletUser->is_under_distributer  || $walletUser->is_api) {
            $walletTransaction->where('wallet_pushes.created_by', $walletUser->id);
        }


        if ($this->startDate && $this->endDate) {


            return view(
                'livewire.historical.push-histo',
                [
                    'user' =>  app(HistoriqueService::class)->getTransactionsDaysProperty(WalletPush::class , $this->startDate,$this->endDate , 'wallet_pushes.created_by' , 'wallet_pushes.updated_by'),
                    'push_historicals' =>   $walletTransaction->join('wallets', 'wallet_pushes.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_pushes.is_web', false)->whereBetween('wallet_pushes.created_at', [$this->startDate, $this->endDate])->orderBy('wallet_pushes.created_at', 'desc')->select('wallet_pushes.*')->paginate(15),
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
                ->orWhere("wallet_pushes.amount", "LIKE",  "%" . $this->searchHistorical . "%")
                ->orWhere("wallet_pushes.phone", "LIKE",  "%" . $this->searchHistorical . "%");
        }

        if ($this->statusFilter != "") {


            return view(
                'livewire.historical.push-histo',
                [
                     'user' =>  app(HistoriqueService::class)->getTransactionsStatusProperty(WalletPush::class , 'wallet_pushes.is_web', 'wallet_pushes.status' , $this->statusFilter , 'wallet_pushes.created_by' , 'wallet_pushes.updated_by'),
                    'push_historicals' =>   $walletTransaction->join('wallets', 'wallet_pushes.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_pushes.is_web', false)->orderBy('wallet_pushes.created_at', 'desc')->where("wallet_pushes.status", $this->statusFilter)->select('wallet_pushes.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,

                ]
            );
        }

        return view(
            'livewire.historical.push-histo',
            [
                'user' =>  app(HistoriqueService::class)->getTransactionsAllDaysProperty(WalletPush::class , 'wallet_pushes.is_web' , 'wallet_pushes.created_by' , 'wallet_pushes.updated_by'),
                'push_historicals' =>  $walletTransaction->join('wallets', 'wallet_pushes.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $walletUser->country->id)->where('wallet_pushes.is_web', false)->orderBy('wallet_pushes.created_at', 'desc')->select('wallet_pushes.*')->paginate(15),
                'usersCountry' =>   $walletUser->country->currencie->name,
            ]
        );

    }

}
