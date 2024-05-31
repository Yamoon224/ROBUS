<?php

namespace App\Http\Livewire\Visitors;

use App\Models\Wallets\WalletDeposit;
use Livewire\Component;
use Livewire\WithPagination;

class CardHistoVis extends Component
{
    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchCarteHistorical = "";
    public $statusFilter = "";
    public  $startDate = "";
    public  $endDate =  "";
  

    public function resetReloadCardField()
    {

        $this->emit('modal-hide', 'hide');
    }

    public function render()
    {

        $cartes =  WalletDeposit::whereIsWeb(true);

        $walletUser = sabdy();

        if ($walletUser->wallet->sidUser->is_under_distributer == true) {

            abort(403);

        } else 
        {

            if ($this->startDate && $this->endDate) {

                // $cartes->whereBetween('wallet_deposits.created_at', [$this->startDate, $this->endDate]);

                return view(
                    'livewire.visitors.card-histo-vis',
                    [
                        'user' => $this->WalletDepositsDays,
                        'card_historicals' => $cartes->join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                            ->where('wallets.country_id', $walletUser->country->id)->whereBetween('wallet_deposits.created_at', [$this->startDate, $this->endDate])->orderBy('wallet_deposits.created_at', 'desc')->select('wallet_deposits.*')->paginate(15),
                        'usersCountry' =>   $walletUser->country->currencie->name
    
                    ]
                );
            }

            if ($this->searchCarteHistorical != "") {

                $this->resetPage();

                $cartes->orwhereHas(
                    'deposit',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchCarteHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchCarteHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchCarteHistorical  . '%');
                    }
                )
                    ->orWhere("wallet_deposits.amount", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_deposits.num_carte", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                    ->orWhere("wallet_deposits.name", "LIKE",  "%" . $this->searchCarteHistorical . "%");
            }

            if ($this->statusFilter != "") {

                // $cartes->where("wallet_deposits.status", $this->statusFilter);

                return view(
                    'livewire.visitors.card-histo-vis',
                    [
                        'user' => $this->DepositAllDaysStatus,
                        'card_historicals' => $cartes->where("status", $this->statusFilter)->orderBy('created_at', 'desc')->select('wallet_deposits.*')->paginate(15),
                        'usersCountry' =>   $walletUser->country->currencie->name,
    
                    ]
                );
            }

            return view(
                'livewire.visitors.card-histo-vis',
                [
                    'user' => $this->DepositAllDays,
                    'card_historicals' => $cartes->orderBy('created_at', 'desc')->select('wallet_deposits.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,
         

                ]
            );
        }
    }



    public function getDepositAllDaysStatusProperty()
    {


        $data = [];

        $wallets = WalletDeposit::whereIsWeb(true)->where("status", $this->statusFilter)->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }
    public function getDepositAllDaysProperty()
    {


        $data = [];

        $wallets = WalletDeposit::whereIsWeb(true)->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }

    public function getWalletDepositsDaysProperty()
    {
        $data = [];

        $wallets = WalletDeposit::whereIsWeb(true)->whereBetween('created_at', [$this->startDate, $this->endDate])->where('status', 'SUCCESS')->select('wallet_deposits.*')->get() ;

        foreach ($wallets as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }
  

    public function getWalletBankComDaysProperty()
    {

       $data = [];

       // Exécutez la requête pour obtenir une collection d'objets
       $walletdeposits = WalletDeposit::whereIsWeb(true)->whereBetween('created_at', [$this->startDate, $this->endDate])->where('wallet_deposits.status', 'SUCCESS')->select('wallet_deposits.*')->get() ;
       

       foreach ($walletdeposits as $wallet) {
           $data[] = $wallet->commissions;
       }



        return array_sum($data);
    }

}
