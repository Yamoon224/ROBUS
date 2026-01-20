<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AdminsCom;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use App\Models\Costs\TransactionCost;
use App\Models\Costs\PhoneCreditCost;
use App\Models\Costs\PaymentCost;
use App\Models\Costs\DepositCost;
use App\Models\Costs\BankCost;
use App\Models\LevelCountry;
use App\Models\Custumers\Country;
use App\Http\Services\PercentRobusAuthService;

class Gain extends Component
{


    use WithPagination;

    public $search = "";

    public $typeFilter = "";
    public  $startDate = "";
    public  $endDate =  "";
    public  $groupe_countrie =  "";
    public $statusTransferFilter = "";

    public function render()
    {
        $gains = AdminsCom::where('is_web', false);


        if ($this->startDate && $this->endDate) {

            // $gains->where('description', '!=', 'Validation distributeur')->whereBetween('created_at', [$this->startDate, $this->endDate]);

            $banqueSum_percent =  $this->WalletIntersDays;


            return view('livewire.gain', [
                "gains" => $gains->where('description', '!=', 'Validation distributeur')->orderBy('created_at', 'desc')->whereBetween('created_at', [$this->startDate, $this->endDate])->latest()->paginate(30),
                "sum_gains" => $this->GainAdminsDays,
                "sum_gain_days" => $this->GainAdminDate,
                "sum_gain_drawal_days" =>  $this->GainAdminTransactionsDays,
                "sum_gain_banques" =>  $banqueSum_percent,

            ]);
        }
        if ($this->search != "") {

            $this->resetPage();

            $gains->where("description", "LIKE",  "%" . $this->search . "%")->orWhere("amount", "LIKE",  "%" . $this->search . "%");
        }


        if ($this->statusTransferFilter != "") {


            $banqueSum_percent =  $this->WalletIntersStatuts;

            return view('livewire.gain', [
                "gains" => $gains->where('description', '!=', 'Validation distributeur')->where("status", $this->statusTransferFilter)->orderBy('created_at', 'desc')->paginate(30),
                "sum_gains" => $this->GainAdminsDays,
                "sum_gain_days" => $this->GainAdminStatut,
                "sum_gain_drawal_days" =>  $this->GainAdminTransactionsDays,
                "sum_gain_banques" =>  $banqueSum_percent,

            ]);
        }


        if ($this->typeFilter != "") {
            $gains->where("country_id", $this->typeFilter);
        }


        $banqueSum_percent = $this->WalletInters;

        return view('livewire.gain', [
            "gains" => $gains->where('description', '!=', 'Validation distributeur')->orderBy('created_at', 'desc')->latest()->paginate(30),
            "sum_gains" => $this->GainAdmins,
            "sum_gain_days" => $this->GainAdminTodays,
            "sum_gain_drawal_days" =>  $this->GainAdminTransactionsTodays,
            "sum_gain_banques" =>  $banqueSum_percent,
        ]);
    }


    public function getWalletIntersProperty()
    {


        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $walletBanks = AdminsCom::join('wallet_inters', 'admins_coms.wallet_inter_id', '=', 'wallet_inters.id')
            ->whereDate('admins_coms.created_at', $convertions)
            ->where('admins_coms.status', 'SUCCESS')
            ->where('admins_coms.is_web', false)
            ->select('admins_coms.*')
            ->get();


        $percent_amount = $walletBanks->map(function ($wallet) {

            $amount_send = $wallet->walletInter->amount;

            $country_user = $wallet->walletInter->wallet;

            $percent_robus = $this->getRobusPercentageForWalletInter($wallet);

            $amount_banque = $this->getConvertAdminCommission($amount_send, $country_user);

            return optional($percent_robus)->percent_banque * $amount_banque;
        });

        return $percent_amount->sum();
    }

    public function getWalletIntersDaysProperty()
    {
        $walletBanks = AdminsCom::join('wallet_inters', 'admins_coms.wallet_inter_id', '=', 'wallet_inters.id')
            ->whereBetween('admins_coms.created_at', [$this->startDate, $this->endDate])
            ->where('admins_coms.status', 'SUCCESS')
            ->where('admins_coms.is_web', false)
            ->select('admins_coms.*')
            ->get();


        $percent_amount = $walletBanks->map(function ($wallet) {

            $amount_send = $wallet->walletInter->amount;

            $country_user = $wallet->walletInter->wallet;

            $percent_robus = $this->getRobusPercentageForWalletInter($wallet);

            $amount_banque = $this->getConvertAdminCommission($amount_send, $country_user);

            return optional($percent_robus)->percent_banque * $amount_banque;
        });

        return $percent_amount->sum();
    }


    public function getWalletIntersStatutsProperty()
    {
        $walletBanks = AdminsCom::join('wallet_inters', 'admins_coms.wallet_inter_id', '=', 'wallet_inters.id')
            ->where("admins_coms.status", $this->statusTransferFilter)
            ->where('admins_coms.is_web', false)
            ->select('admins_coms.*')
            ->get();


        $percent_amount = $walletBanks->map(function ($wallet) {

            $amount_send = $wallet->walletInter->amount;

            $country_user = $wallet->walletInter->wallet;

            $percent_robus = $this->getRobusPercentageForWalletInter($wallet);

            $amount_banque = $this->getConvertAdminCommission($amount_send, $country_user);

            return optional($percent_robus)->percent_banque * $amount_banque;
        });

        return $percent_amount->sum();
    }

    protected function getRobusPercentageForWalletInter($wallet)
    {
        $sidUser = $wallet->walletInter->wallet->sidUser;
        $countrieGroupe = $sidUser->country->name_groupe;
        $countrieUserId = $wallet->walletInter->country;
        $countrieUserSend = $wallet->walletInter->wallet->country->id;
        $providerSendUser = $wallet->walletInter->provider_id;
        $typeProviderSendUser = $wallet->walletInter->provider_type;

        if ($typeProviderSendUser == 1 && $providerSendUser != 51) {
            return app(PercentRobusAuthService::class)->getRobusPercentageBackendInter($countrieGroupe, $countrieUserId, $providerSendUser, $countrieUserSend);
            
        }

        return null;
    }

    // amount inters
    public function getConvertAdminCommission($amount, $countrie_send)
    {
        $country_civ = Country::find(107);

        $robus_currency = $countrie_send->country->currencie->value;
        if ($country_civ->currencie->value > $countrie_send->country->currencie->value) {
            return $amount * $robus_currency;
        } elseif ($country_civ->currencie->value == $countrie_send->country->currencie->value) {
            return $amount;
        } else {
            return $amount * $robus_currency;
        }
    }


    public function getGainAdminsProperty()
    {
        $data = [];
        foreach (AdminsCom::whereIsWeb(false)->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getGainAdminsDaysProperty()
    {
        $data = [];

        // Exécutez la requête pour obtenir une collection d'objets
        $admins = AdminsCom::whereIsWeb(false)->whereBetween('created_at', [$this->startDate, $this->endDate])->get();

        foreach ($admins as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getGainAdminTodaysProperty()
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $data = [];

        foreach (AdminsCom::whereIsWeb(false)->where('description', 'Commission administrateur')->whereStatus('SUCCESS')->whereDate('created_at', $convertions)->get() as $bank) {

            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getGainAdminDateProperty()
    {

        $data = [];


        $admins = AdminsCom::whereIsWeb(false)->whereBetween('created_at', [$this->startDate, $this->endDate])->where('description', 'Commission administrateur')->whereStatus('SUCCESS')->get();

        foreach ($admins as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }


    public function getGainAdminStatutProperty()
    {

        $data = [];


        $admins = AdminsCom::where('is_web',false)->where("status", $this->statusTransferFilter)->where('description', 'Commission administrateur')->get();

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

        foreach (AdminsCom::whereIsWeb(false)->where('description', 'Paiement commission sous-distributer')->whereStatus('SUCCESS')->whereDate('created_at', $convertions)->get() as $bank) {

            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getGainAdminTransactionsTodaysProperty()
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $data = [];

        foreach (AdminsCom::whereIsWeb(false)->where('description_underdistributer', 'Paiement commission sous-distributer')->whereStatus('SUCCESS')->whereDate('created_at', $convertions)->get() as $bank) {

            $data[] = $bank->amount_under;
        }

        return array_sum($data);
    }

    public function getGainAdminTransactionsDaysProperty()
    {

        $data = [];
        $admins = AdminsCom::whereIsWeb(false)->whereBetween('created_at', [$this->startDate, $this->endDate])->where('description_underdistributer', 'Paiement commission sous-distributer')->whereStatus('SUCCESS')->get();
        foreach ($admins as $bank) {
            $data[] = $bank->amount_under;
        }


        return array_sum($data);
    }
}
