<?php

namespace App\Http\Livewire\Historical\UnderHistorical;

use Livewire\Component;
use App\Mail\WalletBank;
use Livewire\WithPagination;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\HistoriqueService;

class BankHisto extends Component
{
    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchBankHistorical = "";
    public $statusFilter = "";
    public  $startDate = "";
    public  $endDate =  "";



    public function render()
    {

        $walletUser = Auth::guard('web')->user();

        $banks =  WalletBank::query();

        if ($walletUser->wallet->sidUser->is_under_distributer == true) {


            if ($this->startDate && $this->endDate) {

                $banks->whereBetween('wallet_banks.created_at', [$this->startDate, $this->endDate]);
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
                $banks->where("wallet_banks.status", $this->statusFilter);
            }

            return view(
                'livewire.historical.under-historical.bank-histo',
                [
                    'user' => $walletUser,
                    'bank_historicals' => $banks->join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->orderBy('wallet_banks.created_at', 'desc')->select('wallet_banks.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,
                    'sum_commissions' => app(HistoriqueService::class)->getWalletCommissionUserProperty(WalletBank::class , 'wallet_banks.wallet_id' ,'wallet_banks.created_by' ,'wallet_banks.status' ,'wallet_banks.*'),

                ]
            );
        } else 
        {

            if ($this->startDate && $this->endDate) {

                $banks->whereBetween('wallet_banks.created_at', [$this->startDate, $this->endDate]);
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
                $banks->where("wallet_banks.status", $this->statusFilter);
            }

            return view(
                'livewire.historical.under-historical.bank-histo',
                [
                    'user' => $walletUser,
                    'bank_historicals' => $banks->join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->orderBy('wallet_banks.created_at', 'desc')->select('wallet_banks.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,
                    'sum_commissions' => app(HistoriqueService::class)->getWalletAdminCommissionProperty(WalletBank::class , 'wallet_banks.wallet_id'  ,'wallet_banks.status' ,'wallet_banks.*'),

                ]
            );
        }
    }


}
