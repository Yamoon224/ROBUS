<?php

namespace App\Http\Livewire\Visitors;

use App\Models\Sid\SidUser;
use App\Models\Wallets\WalletInter;
use Livewire\Component;
use Livewire\WithPagination;


class IntersHistoVis extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchInterHistorical = "";
    public $statusInterFilter = "";
    public  $startDate = "";
    public  $endDate =  "";
    public $searchTransferHistorical = "";
    public $statusTransferFilter = "";



    public function resetTransactionField()
    {

        $this->emit('modal-hide', 'hide');
    }

    public function render()
    {

        $inters =  WalletInter::whereIsWeb(true);

        $walletUser = sabdy();

        if ($walletUser->wallet->sidUser->is_under_distributer == true) {

            abort(403);

        } else {

            if ($this->startDate && $this->endDate) {

                // $inters->whereBetween('wallet_inters.created_at', [$this->startDate, $this->endDate]);

                return view(
                    'livewire.visitors.inters-histo-vis',
                    [
                        'user' => $this->WalletIntersDays,
                        'inter_historicals' =>  $inters->whereBetween('created_at', [$this->startDate, $this->endDate])->orderBy('created_at', 'desc')->paginate(15),
                        'usersCountry' =>   $walletUser->country->currencie->name,
                      

                    ]
                );
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
                    ->orWhere("amount", "LIKE",  "%" . $this->searchInterHistorical . "%")
                    ->orWhere("number_compte", "LIKE",  "%" . $this->searchInterHistorical . "%")
                    ->orWhere("phone", "LIKE",  "%" . $this->searchInterHistorical . "%");
            }


            if ($this->statusInterFilter != "") {

                // $inters->where("wallet_inters.status", $this->statusInterFilter);


                return view(
                    'livewire.visitors.inters-histo-vis',
                    [
                        'user' => $this->walletInterAllStatus,
                        'inter_historicals' =>  $inters->where("status", $this->statusInterFilter)->orderBy('created_at', 'desc')->paginate(15),
                        'usersCountry' =>   $walletUser->country->currencie->name,
                      

                    ]
                );
            }

            return view(
                'livewire.visitors.inters-histo-vis',
                [
                    'user' => $this->WalletIntersPayments,
                    'inter_historicals' =>  $inters->orderBy('created_at', 'desc')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,
                  

                ]
            );
        }
    }

    public function getWalletIntersPaymentsProperty()
    {


        $data = [];

        $wallets = WalletInter::whereIsWeb(true)->orderBy('created_at', 'desc')->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }

   

    // Wallet inter transactions
    public function getWalletIntersDaysProperty()
    {


        $data = [];

        $wallets = WalletInter::whereIsWeb(true)->whereBetween('created_at', [$this->startDate, $this->endDate])->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }


    public function getwalletInterAllStatusProperty()
    {


        $data = [];

        $wallets = WalletInter::whereIsWeb(true)->where("status", $this->statusInterFilter)->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }



    public function getPhoneCreditsDaysProperty()
    {


        $data = [];

        $wallets = WalletInter::whereIsWeb(true)->whereBetween('created_at', [$this->startDate, $this->endDate])->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }




    public function getWalletInterStatusComProperty()
    {

        $data = [];
        foreach (WalletInter::whereIsWeb(true)->where("status", $this->statusPhoneFilter)->where('status', 'SUCCESS')->select('wallet_inters.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
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
