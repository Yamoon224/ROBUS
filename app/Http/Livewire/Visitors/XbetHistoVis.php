<?php

namespace App\Http\Livewire\Visitors;

use App\Models\Wallets\WalletXbet;
use Livewire\Component;

class XbetHistoVis extends Component
{

    protected $historiqueService;
    public  $startDate = "";
    public  $endDate =  "";
    public $statusFilter = "";
    public $searchRealodHistorical = "";

    
    public function render()
    {

        $walletUser = sabdy();


        $realod =  WalletXbet::whereIsWeb(true);


        if ($walletUser->wallet->sidUser->is_under_distributer == true) {

            abort(403);

        } else
        {


            if ($this->startDate && $this->endDate) {

           
                return view(
                    'livewire.visitors.xbet-histo-vis',
                    [
                        'xbet_historicals' =>   $realod->whereBetween('created_at', [$this->startDate, $this->endDate])->orderBy('created_at', 'desc')->paginate(15),
                        'usersCountry' =>  $walletUser->country->currencie->name,
                    ]
                );
            }


            if ($this->searchRealodHistorical != "") {

                $this->resetPage();

                $realod->orWhere("amount", "like",  "%" . $this->searchRealodHistorical . "%")
                    ->orWhere("account_wallet", "like",  "%" . $this->searchRealodHistorical . "%");
            }

            if ($this->statusFilter != "") {

                // $realod->where("wallet_xbets.status", $this->statusFilter);
          
                return view(
                    'livewire.visitors.xbet-histo-vis',
                    [
                        'xbet_historicals' =>   $realod->where("status", $this->statusFilter)->where('created_by', sabdy()->id)->select('wallet_xbets.*')->orderBy('created_at', 'desc')->paginate(15),
                        'usersCountry' =>  $walletUser->country->currencie->name,    
                    ]
                );
            };

            return view(
                'livewire.visitors.xbet-histo-vis',
                [
                    'xbet_historicals' =>   $realod->orderBy('created_at', 'desc')->paginate(15),
                    'usersCountry' =>  $walletUser->country->currencie->name,
                ]
            );
        }
    }

    public function getVirementsAllDaysStatusProperty()
    {


        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletXbet::whereIsWeb(true)->where("status", $this->statusFilter)->sum('amount');
    }
    public function getVirementsAllDaysProperty()
    {


        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletXbet::whereIsWeb(true)->sum('amount');
    }

    public function getVirementsDaysProperty()
    {


        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletXbet::whereIsWeb(true)->whereBetween('created_at', [$this->startDate, $this->endDate])->sum('amount');
    }


   
}
