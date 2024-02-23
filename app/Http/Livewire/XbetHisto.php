<?php

namespace App\Http\Livewire;

use App\Models\Providers\Provider;
use App\Models\Wallets\WalletXbet;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class XbetHisto extends Component
{
    protected $historiqueService;
    public  $startDate = "";
    public  $endDate =  "";
    public $statusFilter = "";
    public $searchRealodHistorical = "";

    
    public function render()
    {

        $walletUser = sabdy();


        $realod =  WalletXbet::query();


        if ($walletUser->wallet->sidUser->is_under_distributer == true) {

            abort(403);

        } else
        {


            if ($this->startDate && $this->endDate) {

           
                return view(
                    'livewire.xbet-histo',
                    [
                        'xbet_historicals' =>   $realod->whereBetween('created_at', [$this->startDate, $this->endDate])->orderBy('created_at', 'desc')->paginate(15),
                        'usersCountry' =>  $walletUser->country->currencie->name,
                    ]
                );
            }


            if ($this->searchRealodHistorical != "") {

                $this->resetPage();

                $realod->orWhere("wallet_xbets.amount", "like",  "%" . $this->searchRealodHistorical . "%")
                    ->orWhere("wallet_xbets.account_wallet", "like",  "%" . $this->searchRealodHistorical . "%");
            }

            if ($this->statusFilter != "") {

                // $realod->where("wallet_reloads.status", $this->statusFilter);
          
                return view(
                    'livewire.xbet-histo',
                    [
                        'xbet_historicals' =>   $realod->where("status", $this->statusFilter)->where('created_by', Auth::guard('web')->user()->id)->select('wallet_reloads.*')->orderBy('created_at', 'desc')->paginate(15),
                        'usersCountry' =>  $walletUser->country->currencie->name,    
                    ]
                );
            };

            return view(
                'livewire.xbet-histo',
                [
                    'xbet_historicals' =>   $realod->orderBy('created_at', 'desc')->paginate(15),
                    'usersCountry' =>  $walletUser->country->currencie->name,
                ]
            );
        }
    }

    public function getVirementsAllDaysStatusProperty()
    {


        $data = [];

        $wallets = WalletXbet::where("wallet_xbets.status", $this->statusFilter)->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }
    public function getVirementsAllDaysProperty()
    {


        $data = [];

        $wallets = WalletXbet::get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }

    public function getVirementsDaysProperty()
    {


        $data = [];

        $wallets = WalletXbet::whereBetween('created_at', [$this->startDate, $this->endDate])->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }


    public function getVirementTransactionProperty()
    {

        $walletUser = Auth::guard('web')->user();

        $virement_transaction = Provider::join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', $walletUser->country->id)->where('providers.status', true)
            ->where('providers.provider_type_id',  4)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')->get();

        return $virement_transaction;
    }
}
