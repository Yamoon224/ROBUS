<?php

namespace App\Http\Livewire\Visitors;

use App\Models\Wallets\WalletPhoneCredit;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PhoneCreditHistoVis extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchPhoneHistorical = "";
    public $statusPhoneFilter = "";
    public  $startDate = "";
    public  $endDate =  "";



    public function resetPhoneCreditField()
    {

        $this->emit('modal-hide', 'hide');
    }

    public function render()
    {

        $phones =  WalletPhoneCredit::whereIsWeb(true);

        $walletUser = sabdy();

        if ($walletUser->wallet->sidUser->is_under_distributer == true) {

            abort(403);
        } else {

            if ($this->startDate && $this->endDate) {

                return view(
                    'livewire.visitors.phone-credit-histo-vis',
                    [
                        'user' => $this->WalletPhoneCreditsDays,
                        'phone_historicals' =>   $phones->whereBetween('created_at', [$this->startDate, $this->endDate])->orderBy('created_at', 'desc')->select('wallet_phone_credits.*')->paginate(15),
                        'usersCountry' =>   $walletUser->country->currencie->name,
                        'provider_credits' => $this->PhoneCreditTransaction,

                    ]
                );
            }

            if ($this->searchPhoneHistorical != "") {

                $this->resetPage();

                $phones->orwhereHas(
                    'paymentPhone',
                    function ($query) {
                        $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchPhoneHistorical . '%')
                            ->orWhere('transaction_id', 'like', '%' .  $this->searchPhoneHistorical   . '%')
                            ->orWhere('trans_comp_id', 'like', '%' .  $this->searchPhoneHistorical  . '%');
                    }
                )
                    ->orWhere("amount", "LIKE",  "%" . $this->searchPhoneHistorical . "%")
                    ->orWhere("phone", "LIKE",  "%" . $this->searchPhoneHistorical . "%");
            }

            if ($this->statusPhoneFilter != "") {

                // $phones->where("wallet_phone_credits.status", $this->statusPhoneFilter);

                return view(
                    'livewire.visitors.phone-credit-histo-vis',
                    [
                        'user' => $this->PhoneCreditsAllStatus,
                        'phone_historicals' =>   $phones->orderBy('created_at', 'desc')->where("status", $this->statusPhoneFilter)->select('wallet_phone_credits.*')->paginate(15),
                        'usersCountry' =>   $walletUser->country->currencie->name,
                        'provider_credits' => $this->PhoneCreditTransaction,

                    ]
                );
            }

            return view(
                'livewire.visitors.phone-credit-histo-vis',
                [
                    'user' => $this->PhoneCreditsAllDays,
                    'phone_historicals' =>   $phones->orderBy('created_at', 'desc')->select('wallet_phone_credits.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name
                ]
            );
        }
    }



    public function getWalletPhoneCreditsDaysProperty()
    {


        $data = [];

        $wallets = WalletPhoneCredit::whereIsWeb(false)->whereBetween('created_at', [$this->startDate, $this->endDate])->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }


    public function getPhoneCreditsAllStatusProperty()
    {


        $data = [];

        $wallets = WalletPhoneCredit::whereIsWeb(false)->where("status", $this->statusPhoneFilter)->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }

    public function getPhoneCreditsAllDaysProperty()
    {


        $data = [];

        $wallets = WalletPhoneCredit::whereIsWeb(true)->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }

    public function getPhoneCreditsDaysProperty()
    {


        $data = [];

        $wallets = WalletPhoneCredit::whereIsWeb(true)->whereBetween('created_at', [$this->startDate, $this->endDate])->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }



    public function getWalletPhoneCreditDaysProperty()
    {

        $data = [];

        // Exécutez la requête pour obtenir une collection d'objets
        $wallettransactions = WalletPhoneCredit::whereIsWeb(true)->whereBetween('created_at', [$this->startDate, $this->endDate])->where('status', 'SUCCESS')->get();

        foreach ($wallettransactions as $wallet) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $wallet->costs_amount;
        }

        return array_sum($data);
    }
}
