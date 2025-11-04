<?php

namespace App\Http\Livewire\Historical;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletPhoneCredit;

class PhoneCredithisto extends Component
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

        $phones =  WalletPhoneCredit::whereIsWeb(false);

        $walletUser = Auth::guard('web')->user();

        if ($walletUser->is_under_distributer) {
            $phones->where('wallet_phone_credits.created_by', $walletUser->id);
        }


        if ($this->startDate && $this->endDate) {

            // $phones->whereBetween('wallet_phone_credits.created_at', [$this->startDate, $this->endDate]);

            return view(
                'livewire.historical.phone-credithisto',
                [
                    'phone_historicals' =>   $phones->join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_phone_credits.is_web', false)->whereBetween('wallet_phone_credits.created_at', [$this->startDate, $this->endDate])->orderBy('wallet_phone_credits.created_at', 'desc')->select('wallet_phone_credits.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,

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
                ->orWhere("wallet_phone_credits.amount", "LIKE",  "%" . $this->searchPhoneHistorical . "%")
                ->orWhere("wallet_phone_credits.phone", "LIKE",  "%" . $this->searchPhoneHistorical . "%");
        }

        if ($this->statusPhoneFilter != "") {

            // $phones->where("wallet_phone_credits.status", $this->statusPhoneFilter);

            return view(
                'livewire.historical.phone-credithisto',
                [
                    'phone_historicals' =>   $phones->join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_phone_credits.is_web', false)->orderBy('wallet_phone_credits.created_at', 'desc')->where("wallet_phone_credits.status", $this->statusPhoneFilter)->select('wallet_phone_credits.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,

                ]
            );
        }

        return view(
            'livewire.historical.phone-credithisto',
            [
                'phone_historicals' =>   $phones->join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $walletUser->country->id)->where('wallet_phone_credits.is_web', false)->orderBy('wallet_phone_credits.created_at', 'desc')->select('wallet_phone_credits.*')->paginate(15),
                'usersCountry' =>   $walletUser->country->currencie->name,

            ]
        );
    }

}
