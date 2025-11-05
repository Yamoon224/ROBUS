<?php

namespace App\Http\Livewire\Historical;

use Livewire\Component;
use App\Models\Sid\SidUser;
use Livewire\WithPagination;
use App\Models\Wallets\WalletBank;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\HistoriqueService;

class Bankhisto extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchBankHistorical = "";
    public $statusFilter = "";
    public  $startDate = "";
    public  $endDate =  "";

  
    public function resetVirementField()
    {

        $this->emit('modal-hide', 'hide');
    }

    public function render()
    {

        $walletUser = Auth::guard('web')->user();

        $banks =  WalletBank::query();

        if (sabdy()->is_under_distributer) {
            $banks->where('wallet_banks.created_by', sabdy()->id);
        }


        if ($this->startDate && $this->endDate) {

            // $banks->whereBetween('wallet_banks.created_at', [$this->startDate, $this->endDate]);

            return view(
                'livewire.historical.bankhisto',
                [
                    'user' =>  app(HistoriqueService::class)->getTransactionsDaysProperty(WalletBank::class , $this->startDate,$this->endDate , 'wallet_banks.created_by' , 'wallet_banks.updated_by'),
                    'bank_historicals' => $banks->join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->whereBetween('wallet_banks.created_at', [$this->startDate, $this->endDate])->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->orderBy('wallet_banks.created_at', 'desc')->select('wallet_banks.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,
                ]
            );
        }

        if ($this->searchBankHistorical != "") {


            $this->resetPage();

            $banks->orwhereHas(
                'paymentBank',
                function ($query) {
                    $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchBankHistorical . '%')
                        ->orWhere('transaction_id', 'like', '%' .  $this->searchBankHistorical   . '%')
                        ->orWhere('trans_comp_id', 'like', '%' .  $this->searchBankHistorical  . '%');
                }
            )
                ->orWhere("wallet_banks.amount", "LIKE",  "%" . $this->searchBankHistorical . "%")
                ->orWhere("wallet_banks.phone", "LIKE",  "%" . $this->searchBankHistorical . "%")
                ->orWhere("wallet_banks.number_compte", "LIKE",  "%" . $this->searchBankHistorical . "%")
                ->orWhere("wallet_banks.firstname", "LIKE",  "%" . $this->searchBankHistorical . "%")
                ->orWhere("wallet_banks.lastname", "LIKE",  "%" . $this->searchBankHistorical . "%");
        }


        if ($this->statusFilter != "") {

            // $banks->where("wallet_banks.status", $this->statusFilter);


            return view(
                'livewire.historical.bankhisto',
                [
                     'user' =>  app(HistoriqueService::class)->getTransactionsStatusProperty(WalletBank::class , 'wallet_banks.is_web', 'wallet_banks.status' , $this->statusTransferFilter , 'wallet_banks.created_by' , 'wallet_banks.updated_by'),
                    'bank_historicals' => $banks->join('wallets', 'wallet_banks.wallet_id', 'wallets.id')->where("wallet_banks.status", $this->statusFilter)
                        ->where('wallets.country_id', $walletUser->country->id)->orderBy('wallet_banks.created_at', 'desc')->select('wallet_banks.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,
                ]
            );
        }

        return view(
            'livewire.historical.bankhisto',
            [
                'user' =>  app(HistoriqueService::class)->getTransactionsAllDaysProperty(WalletBank::class , 'wallet_banks.is_web' , 'wallet_banks.created_by' , 'wallet_banks.updated_by'),
                'bank_historicals' => $banks->join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $walletUser->country->id)->orderBy('wallet_banks.created_at', 'desc')->select('wallet_banks.*')->paginate(15),
                'usersCountry' =>   $walletUser->country->currencie->name,

            ]
        );
    }

}
