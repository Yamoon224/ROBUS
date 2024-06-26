<?php

namespace App\Http\Livewire\Historical\UnderHistorical;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Wallets\WalletInter;
use Illuminate\Support\Facades\Auth;

class InterHisto extends Component
{
    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchInterHistorical = "";
    public $statusInterFilter = "";
    public  $startDate = "";
    public  $endDate =  "";


    public function render()
    {

        $inters =  WalletInter::query();

        $walletUser = Auth::guard('web')->user();

        if ($walletUser->wallet->sidUser->is_under_distributer == true) {

            if ($this->startDate && $this->endDate) {

                $inters->whereBetween('wallet_inters.created_at', [$this->startDate, $this->endDate]);
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
                $inters->where("wallet_inters.status", $this->statusInterFilter);
            }

            return view(
                'livewire.historical.under-historical.inter-histo',
                [
                    'user' => $walletUser,
                    'inter_historicals' =>   $inters->join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_inters.created_by', Auth::guard('web')->user()->id)->orderBy('wallet_inters.created_at', 'desc')->select('wallet_inters.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,
                    'sum_commissions' => $this->WalletIntersUser,

                ]
            );
        } else
        {

            if ($this->startDate && $this->endDate) {

                $inters->whereBetween('wallet_inters.created_at', [$this->startDate, $this->endDate]);
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
                $inters->where("wallet_inters.status", $this->statusInterFilter);
            }

            return view(
                'livewire.historical.under-historical.inter-histo',
                [
                    'user' => $walletUser,
                    'inter_historicals' =>  $inters->orderBy('wallet_inters.created_at', 'desc')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,
                    'sum_commissions' => $this->WalletInters,

                ]
            );
        }
    }

    // Admin 
    public function getWalletIntersProperty()
    {
        $data = [];
        foreach (WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_inters.status', 'SUCCESS')->select('wallet_inters.*')->get() as $bank) {
            $data[] = $bank->commissions_admin;
        }

        return array_sum($data);
    }

    // User 
    public function getWalletIntersUserProperty()
    {

        $data = [];
        foreach (WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_inters.created_by', Auth::guard('web')->user()->id)->where('wallet_inters.status', 'SUCCESS')->select('wallet_inters.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }
    

}
