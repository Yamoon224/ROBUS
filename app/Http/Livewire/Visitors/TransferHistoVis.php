<?php

namespace App\Http\Livewire\Visitors;


use App\Models\Sid\SidUser;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TransferHistoVis extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchTransferHistorical = "";
    public $statusTransferFilter = "";
    public  $startDate = "";
    public  $endDate =  "";
    public  $beginDate = "";
    public  $endDateT =  "";




    protected function getTransactionsDays()
    {
        return WalletTransaction::whereIsWeb(true)->whereBetween('created_at', [$this->beginDate, $this->endDateT])->get();
    }

    protected function getTransactionCount($status)
    {
        return WalletTransaction::whereIsWeb(true)->whereBetween('created_at', [$this->beginDate, $this->endDateT])->where('status', $status)->count();
    }



    public function resetTransactionField()
    {

        $this->emit('modal-hide', 'hide');
    }
    public function render()
    {

        $transfers =  WalletTransaction::whereIsWeb(true);

        $walletUser = Auth::guard('web')->user();

        if ($walletUser->wallet->sidUser->is_under_distributer == true) {

            abort(403);
        } else {

            if ($this->startDate && $this->endDate) {

                // $transfers->whereBetween('wallet_transactions.created_at', [$this->startDate, $this->endDate])->orderBy('wallet_transactions.created_at', 'desc')->paginate(15);

                return view(
                    'livewire.visitors.transfer-histo',
                    [
                        'user' => $this->TransactionsDays,
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
                    ->orWhere("amount", "LIKE",  "%" . $this->searchTransferHistorical . "%")
                    ->orWhere("num_wallet", "LIKE",  "%" . $this->searchTransferHistorical . "%")
                    ->orWhere("phone", "LIKE",  "%" . $this->searchTransferHistorical . "%");
            }

            if ($this->statusTransferFilter != "") {

                // $transfers->where("wallet_transactions.status", $this->statusTransferFilter);

                return view(
                    'livewire.visitors.transfer-histo-vis',
                    [
                        'user' => $this->TransactionsAllStatus,
                        'transfer_historicals' => $transfers->where("wallet_transactions.status", $this->statusTransferFilter)->orderBy('wallet_transactions.created_at', 'desc')->paginate(15),
                        'usersCountry' =>  $walletUser->country->currencie->name,
                       

                    ]
                );
            }

            return view(
                'livewire.visitors.transfer-histo-vis',
                [
                    'user' => $this->TransactionsAllDays,
                    'transfer_historicals' => $transfers->orderBy('wallet_transactions.created_at', 'desc')->paginate(15),
                    'usersCountry' =>  $walletUser->country->currencie->name,
                   

                ]
            );
        }
    }


    public function getTransactionsAllStatusProperty()
    {


        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletTransaction::whereIsWeb(true)->where("wallet_transactions.status", $this->statusTransferFilter)->sum('amount');
    }
    public function getTransactionsAllDaysProperty()
    {


        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletTransaction::whereIsWeb(true)->sum('amount');
    }

    public function getTransactionsDaysProperty()
    {


        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletTransaction::whereIsWeb(true)->whereBetween('created_at', [$this->beginDate, $this->endDateT])->sum('amount');
    }

    public function getTransactionsAllsProperty()
    {


        $wallets = WalletTransaction::whereIsWeb(true)->whereBetween('created_at', [$this->beginDate, $this->endDateT])->get();

        return $wallets;
    }



    public function getAdminCIVProperty()
    {

        $admin_civ =  SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

        return  $admin_civ;
    }
}
