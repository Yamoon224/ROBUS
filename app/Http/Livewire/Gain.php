<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AdminsCom;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use App\Models\Costs\TransactionCost;
use APp\Models\Wallets\WalletTransaction;
use APp\Models\Wallets\WalletBank;

class Gain extends Component
{


    use WithPagination;

    public $search = "";

    public $typeFilter = "";
    public  $startDate = "";
    public  $endDate =  "";
    public  $groupe_countrie =  "";

    public function render()
    {
        $gains = AdminsCom::query();

        if ($this->startDate && $this->endDate) {

            // $gains->where('description', '!=', 'Validation distributeur')->whereBetween('created_at', [$this->startDate, $this->endDate]);

            return view('livewire.gain', [
                "gains" => $gains->where('description', '!=', 'Validation distributeur')->orderBy('created_at', 'desc')->whereBetween('created_at', [$this->startDate, $this->endDate])->latest()->paginate(30),
                "sum_gains" => $this->GainAdminsDays,
                "sum_gain_days" => $this->GainAdminDate,
                "sum_gain_drawal_days" =>  $this->GainAdminTransactionsDays,
            ]);
        }
        if ($this->search != "") {

            $this->resetPage();

            $gains->where("description", "LIKE",  "%" . $this->search . "%")->orWhere("amount", "LIKE",  "%" . $this->search . "%");
        }



        if ($this->typeFilter != "") {
            $gains->where("country_id", $this->typeFilter);
        }

        dd($this->WalletBanks);

        return view('livewire.gain', [
            "gains" => $gains->where('description', '!=', 'Validation distributeur')->orderBy('created_at', 'desc')->latest()->paginate(30),
            "sum_gains" => $this->GainAdmins,
            "sum_gain_days" => $this->GainAdminTodays,
            "sum_gain_drawal_days" =>  $this->GainAdminTransactionsTodays,
        ]);
    }

    // Wallet Transactions

    public function getWalletBanksProperty()
    {


        $walletTransactions = AdminsCom::join('wallet_transactions', 'admins_coms.wallet_transaction_id', '=', 'wallet_transactions.id')
            ->where('admins_coms.status', 'SUCCESS')
            ->select('admins_coms.*')
            ->get();

        $percent_amount = [];

        // PENDING
        foreach ($walletTransactions as $wallet) {
            $sidUser = $wallet->walletTransaction->wallet->sidUser;
            $countrieUser = $sidUser->country->name_groupe;
            $amount_admin = $wallet->amount;

            if ($sidUser->is_under_distributer) {
                $percent_robus = $this->getRobusPercentageDistributers($countrieUser);
            } else {
                $percent_robus = $this->getRobusPercentageCustumers($countrieUser);
            }

            if ($percent_robus !== null) {
                $percent_amount[] = $percent_robus->percent_banque * $amount_admin;
            }
        }

        return array_sum($percent_amount);
    }


    public function getRobusPercentageCustumers($countryNameGroup)
    {
        // $countryNameGroup = sabdy()->country->name_groupe;

        switch ($countryNameGroup) {
            case 'CIV':
                return TransactionCost::whereId(28)->first();
            case 'AFL':
                return TransactionCost::whereId(37)->first();
            case 'AFC':
                return TransactionCost::whereId(38)->first();
            case 'USA':
                return TransactionCost::whereId(39)->first();
            case 'URP':
                return TransactionCost::whereId(40)->first();
            case 'MAG':
                return TransactionCost::whereId(41)->first();
            default:
                return null;
        }
    }

    public function getRobusPercentageDistributers($countryNameGroup)
    {
        // $countryNameGroup = robus()->country->name_groupe;

        switch ($countryNameGroup) {
            case 'CIV':
                return TransactionCost::whereId(26)->first();
            case 'AFL':
                return TransactionCost::whereId(32)->first();
            case 'AFC':
                return TransactionCost::whereId(33)->first();
            case 'USA':
                return TransactionCost::whereId(34)->first();
            case 'URP':
                return TransactionCost::whereId(35)->first();
            case 'MAG':
                return TransactionCost::whereId(36)->first();
            default:
                return null; // Handle the default case as needed
        }
    }

    public function getGainAdminsProperty()
    {
        $data = [];
        foreach (AdminsCom::get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getGainAdminsDaysProperty()
    {
        $data = [];

        // Exécutez la requête pour obtenir une collection d'objets
        $admins = AdminsCom::whereBetween('created_at', [$this->startDate, $this->endDate])->get();

        foreach ($admins as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getGainAdminTodaysProperty()
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $data = [];

        foreach (AdminsCom::where('description', 'Commission administrateur')->whereStatus('SUCCESS')->whereDate('created_at', $convertions)->get() as $bank) {

            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getGainAdminDateProperty()
    {

        $data = [];


        $admins = AdminsCom::whereBetween('created_at', [$this->startDate, $this->endDate])->where('description', 'Commission administrateur')->whereStatus('SUCCESS')->get();

        foreach ($admins as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }


    public function getGainAdminDrawalTodaysProperty()
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $data = [];

        foreach (AdminsCom::where('description', 'Paiement commission sous-distributer')->whereStatus('SUCCESS')->whereDate('created_at', $convertions)->get() as $bank) {

            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getGainAdminTransactionsTodaysProperty()
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $data = [];

        foreach (AdminsCom::where('description_underdistributer', 'Paiement commission sous-distributer')->whereStatus('SUCCESS')->whereDate('created_at', $convertions)->get() as $bank) {

            $data[] = $bank->amount_under;
        }

        return array_sum($data);
    }

    public function getGainAdminTransactionsDaysProperty()
    {


        $data = [];

        $admins = AdminsCom::whereBetween('created_at', [$this->startDate, $this->endDate])->where('description_underdistributer', 'Paiement commission sous-distributer')->whereStatus('SUCCESS')->get();

        foreach ($admins as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount_under;
        }


        return array_sum($data);
    }
}
