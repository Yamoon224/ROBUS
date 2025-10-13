<?php

namespace App\Http\Livewire\Historical\UnderHistorical;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Providers\Provider;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletPhoneCredit;
use App\Http\Services\ServiceHistoricalBackend;

class PhoneCreditHisto extends Component
{
    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchPhoneHistorical = "";
    public $statusPhoneFilter = "";
    public  $startDate = "";
    public  $endDate =  "";
    // Phone credit inter
    public $phoneCreditId;
    public $phoneCreditStatus;
    public $provider_phone_credit_edit;
    public $number_phone_credit_edit;
    public $amount_phone_credit_edit;


    public function resetPhoneCreditField()
    {

        $this->emit('modal-hide', 'hide');
    }

    public function render()
    {

        $phones =  WalletPhoneCredit::query();

        $walletUser = Auth::guard('web')->user();

        if ($walletUser->wallet->sidUser->is_under_distributer == true ) {

            if ($this->startDate && $this->endDate) {

                $phones->whereBetween('wallet_phone_credits.created_at', [$this->startDate, $this->endDate]);
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
                $phones->where("wallet_phone_credits.status", $this->statusPhoneFilter);
            }

            return view(
                'livewire.historical.under-historical.phone-credit-histo',
                [
                    'user' => $walletUser,
                    'phone_historicals' =>   $phones->join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_phone_credits.created_by', Auth::guard('web')->user()->id)->orderBy('wallet_phone_credits.created_at', 'desc')->select('wallet_phone_credits.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,
                    'sum_commissions' => app(ServiceHistoricalBackend::class)->getWalletSumCommissionProperty(WalletPhoneCredit::class,  'wallet_phone_credits.wallet_id',  'wallet_phone_credits.created_by', 'wallet_phone_credits.status', 'wallet_phone_credits.*'),
                    'provider_credits' => $this->PhoneCreditTransaction,

                ]
            );
        } else
        {

            if ($this->startDate && $this->endDate) {

                $phones->whereBetween('wallet_phone_credits.created_at', [$this->startDate, $this->endDate]);
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
                $phones->where("wallet_phone_credits.status", $this->statusPhoneFilter);
            }

            return view(
                'livewire.historical.under-historical.phone-credit-histo',
                [
                    'user' => $walletUser,
                    'phone_historicals' =>   $phones->join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->orderBy('wallet_phone_credits.created_at', 'desc')->select('wallet_phone_credits.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,
                    'sum_commissions' => app(ServiceHistoricalBackend::class)->getWalletSumAdminProperty(WalletPhoneCredit::class , 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.status', 'wallet_phone_credits.*'),
                    'provider_credits' => app(ServiceHistoricalBackend::class)->getProvidersByAllType(Provider::class , 6),

                ]
            );
        }
    }



}
