<?php

namespace App\Http\Livewire\Historical;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class Reloadhisto extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchRealodHistorical = "";
    public $statusFilter = "";
    public  $startDate = "";
    public  $endDate =  "";


    public function render()
    {


        $walletUser = Auth::guard('web')->user();


        $realod =  WalletReload::query();


        if ($walletUser->wallet->sidUser->is_under_distributer == true) {

            abort(403);

        } else
        {



            if ($this->startDate && $this->endDate) {

                // $realod->whereBetween('wallet_reloads.created_at', [$this->startDate, $this->endDate]);
           
                return view(
                    'livewire.historical.reloadhisto',
                    [
                        'user' => $this->ReloadsDays,
                        'reload_historicals' =>   $realod->whereBetween('created_at', [$this->startDate, $this->endDate])->orderBy('created_at', 'desc')->paginate(15),
                        'usersCountry' =>  $walletUser->country->currencie->name,
                    ]
                );
            }


            if ($this->searchRealodHistorical != "") {

                $this->resetPage();

                $realod->orwhereHas(
                    'paymentReload',
                    function ($query) {
                        $query->where('operator_transaction_name', 'like', '%' . $this->searchRealodHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchRealodHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchRealodHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_reloads.amount", "like",  "%" . $this->searchRealodHistorical . "%")
                    ->orWhere("wallet_reloads.phone", "like",  "%" . $this->searchRealodHistorical . "%")
                    ->orWhere("wallet_reloads.account_wallet", "like",  "%" . $this->searchRealodHistorical . "%");
            }

            if ($this->statusFilter != "") {

                // $realod->where("wallet_reloads.status", $this->statusFilter);
          
                return view(
                    'livewire.historical.reloadhisto',
                    [
                        'user' => $this->ReloadAllDaysStatus,
                        'reload_historicals' =>   $realod->where("status", $this->statusFilter)->where('created_by', Auth::guard('web')->user()->id)->select('wallet_reloads.*')->orderBy('created_at', 'desc')->paginate(15),
                        'usersCountry' =>  $walletUser->country->currencie->name,    
                    ]
                );
            };

            return view(
                'livewire.historical.reloadhisto',
                [
                    'user' => $this->ReloadsAllDays,
                    'reload_historicals' =>   $realod->orderBy('created_at', 'desc')->paginate(15),
                    'usersCountry' =>  $walletUser->country->currencie->name,
                ]
            );
        }
    }

  

    public function getReloadsAllDaysProperty()
    {


        $data = [];

        $wallets = WalletReload::get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }

 


}