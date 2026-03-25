<?php

namespace App\Http\Livewire\Historical;

use Livewire\Component;
use App\Models\Sid\SidUser;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletTransaction;
use App\Http\Services\HistoriqueService;


class TransferHisto extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchTransferHistorical = "";
    public $statusTransferFilter = "";
    public  $startDate = "";
    public  $endDate =  "";
    public  $beginDate = "";
    public  $endDateT =  "";



    public function resetTransactionField()
    {

        $this->emit('modal-hide', 'hide');
    }
    public function render()
    {

        $transfers =  WalletTransaction::whereIsWeb(false);

        $walletUser = Auth::guard('web')->check()
            ? Auth::guard('web')->user()
            : (Auth::guard('integration')->check()
                ? Auth::guard('integration')->user()
                : null);

        if ($walletUser->is_under_distributer || $walletUser->is_api ) {
            $transfers->where('wallet_transactions.created_by', $walletUser->id); 
        }

        if ($this->startDate && $this->endDate) {

            // $transfers->whereBetween('wallet_transactions.created_at', [$this->startDate, $this->endDate])->orderBy('wallet_transactions.created_at', 'desc')->paginate(15);

            return view(
                'livewire.historical.transfer-histo',
                [
                    'user' =>  app(HistoriqueService::class)->getTransactionsDaysProperty(WalletTransaction::class , $this->startDate,$this->endDate , 'wallet_transactions.created_by' , 'wallet_transactions.updated_by'),
                    'transfer_historicals' =>  $transfers->whereBetween('wallet_transactions.created_at', [$this->startDate, $this->endDate])->orderBy('wallet_transactions.created_at', 'desc')->paginate(15),
                    'usersCountry' =>  $walletUser->country->currencie->name,

                ]
            );
        }

        if ($this->searchTransferHistorical != "") {

            $this->resetPage();

            $transfers->orwhereHas(
                'transaction',
                function ($query) {
                    $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchTransferHistorical . '%')
                        ->orWhere('transaction_id', 'like', '%' .  $this->searchTransferHistorical   . '%')
                        ->orWhere('trans_comp_id', 'like', '%' .  $this->searchTransferHistorical  . '%');
                }
            )
                ->orWhere("wallet_transactions.amount", "LIKE",  "%" . $this->searchTransferHistorical . "%")
                ->orWhere("wallet_transactions.num_wallet", "LIKE",  "%" . $this->searchTransferHistorical . "%")
                ->orWhere("wallet_transactions.phone", "LIKE",  "%" . $this->searchTransferHistorical . "%");
        }

        if ($this->statusTransferFilter != "") {


            return view(
                'livewire.historical.transfer-histo',
                [
                     'user' =>  app(HistoriqueService::class)->getTransactionsStatusProperty(WalletTransaction::class , 'wallet_transactions.is_web', 'wallet_transactions.status' , $this->statusTransferFilter , 'wallet_transactions.created_by' , 'wallet_transactions.updated_by'),
                    'transfer_historicals' => $transfers->where("wallet_transactions.status", $this->statusTransferFilter)
                             ->orderBy('wallet_transactions.created_at', 'desc')->paginate(15),
                    'usersCountry' =>  $walletUser->country->currencie->name,
                ]
            );
        }


        return view(
            'livewire.historical.transfer-histo',
            [
                 'user' =>  app(HistoriqueService::class)->getTransactionsAllDaysProperty(WalletTransaction::class , 'wallet_transactions.is_web' , 'wallet_transactions.created_by' , 'wallet_transactions.updated_by'),
                'transfer_historicals' => $transfers->orderBy('wallet_transactions.created_at', 'desc')->paginate(15),
                'usersCountry' =>  $walletUser->country->currencie->name,

            ]
        );
    }


}
