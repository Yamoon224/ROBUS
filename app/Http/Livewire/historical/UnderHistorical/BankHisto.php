<?php

namespace App\Http\Livewire\Historical\UnderHistorical;

use Livewire\Component;
use App\Mail\WalletBank;
use Livewire\WithPagination;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use Illuminate\Support\Facades\Auth;

class BankHisto extends Component
{
    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchBankHistorical = "";
    public $statusFilter = "";
    public  $startDate = "";
    public  $endDate =  "";

    // edit
    public $virementDescription_edit;
    public $provider_virement_edit;
    public $firstname_user_edit;
    public $lastname_user_edit;
    public $phone_user_edit;
    public $amount_virement_edit;
    public $account_number_user_edit;
    public $country_virement_edit;
    public $virementStatus;
    public $virementId;
    public $currency_custumers;

  

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
                    'sum_commissions' => $this->WalletBanksUser,
                    'provider_virements' =>  $this->VirementTransaction,
                    'countries' => Country::where('status', true)->orderBy('name', 'asc')->get(),

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
                    'sum_commissions' => $this->WalletBanks,
                    'provider_virements' =>  $this->VirementTransaction,
                    'countries' => Country::where('status', true)->orderBy('name', 'asc')->get(),

                ]
            );
        }
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

    // Admin 
    public function getWalletBanksProperty()
    {

        $data = [];
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->get() as $bank) {
            $data[] = $bank->commissions_admin;
        }

        return array_sum($data);
    }

    // User 
    public function getWalletBanksUserProperty()
    {

        $data = [];
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

}
