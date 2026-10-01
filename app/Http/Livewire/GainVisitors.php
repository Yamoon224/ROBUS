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

class GainVisitors extends Component
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
        $gains = AdminsCom::where('is_web', true);


        if ($this->startDate && $this->endDate) {

            // $gains->where('description', '!=', 'Validation distributeur')->whereBetween('created_at', [$this->startDate, $this->endDate]);

            $banqueSum_percent =  $this->WalletIntersDays;


            return view('livewire.gain-visitors', [
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

            return view('livewire.gain-visitors', [
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

        return view('livewire.gain-visitors', [
            "gains" => $gains->where('description', '!=', 'Validation distributeur')->orderBy('created_at', 'desc')->latest()->paginate(30),
            "sum_gains" => $this->GainAdmins,
            "sum_gain_days" => $this->GainAdminTodays,
            "sum_gain_drawal_days" =>  $this->GainAdminTransactionsTodays,
            "sum_gain_banques" =>  $banqueSum_percent,
        ]);
    }

 

    // Wallet Inters

    public function getWalletIntersProperty()
    {


        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $walletBanks = AdminsCom::join('wallet_inters', 'admins_coms.wallet_inter_id', '=', 'wallet_inters.id')
            ->whereDate('admins_coms.created_at', $convertions)
            ->where('admins_coms.status', 'SUCCESS')
            ->where('admins_coms.is_web', true)
            ->select('admins_coms.*')
            ->get();


        $percent_amount = $walletBanks->map(function ($wallet) {

            $amount_send = $wallet->walletInter->amount;

            $percent_robus = $this->getRobusPercentageForWalletInter($wallet);

            return optional($percent_robus)->percent_banque * $amount_send;
        });

        return $percent_amount->sum();
    }

    public function getWalletIntersDaysProperty()
    {
        $walletBanks = AdminsCom::join('wallet_inters', 'admins_coms.wallet_inter_id', '=', 'wallet_inters.id')
            ->whereBetween('admins_coms.created_at', [$this->startDate, $this->endDate])
            ->where('admins_coms.status', 'SUCCESS')
            ->where('admins_coms.is_web', true)
            ->select('admins_coms.*')
            ->get();


        $percent_amount = $walletBanks->map(function ($wallet) {

            $amount_send = $wallet->walletInter->amount;

            $percent_robus = $this->getRobusPercentageForWalletInter($wallet);

            return optional($percent_robus)->percent_banque * $amount_send;
        });

        return $percent_amount->sum();
    }


    public function getWalletIntersStatutsProperty()
    {
        $walletBanks = AdminsCom::join('wallet_inters', 'admins_coms.wallet_inter_id', '=', 'wallet_inters.id')
            ->where("admins_coms.status", $this->statusTransferFilter)
            ->where('admins_coms.is_web', true)
            ->select('admins_coms.*')
            ->get();


        $percent_amount = $walletBanks->map(function ($wallet) {

            $amount_send = $wallet->walletInter->amount;


            $percent_robus = $this->getRobusPercentageForWalletInter($wallet);

            return optional($percent_robus)->percent_banque * $amount_send;
        });

        return $percent_amount->sum();
    }

    protected function getRobusPercentageForWalletInter($wallet)
    {
        $countrieUserId = $wallet->walletInter->country->id;
        $providerSendUser = $wallet->walletInter->provider_id;
        $typeProviderSendUser = $wallet->walletInter->provider_type;

        if ($typeProviderSendUser == 1 && $providerSendUser != 51) {
            return $this->getRobusPercentageInter($countrieUserId);
        }

        return null;
    }


    // Percent valeurs Transactions
    public function getRobusPercentageInter($countrie)
    {


        $countriesLevels = [

            // Web Visitors
            25 =>  605 ?? null, // BENIN
            36 =>  606 ?? null, // BF
            133 =>  607 ?? null, // Mali
            188 =>  608 ?? null, // Senegal
            214 =>  609 ?? null, // Togo
            39 =>  610 ?? null, // Cameroun
            91 =>  611 ?? null, // Guinne conakry
            157 =>  612 ?? null, // Niger
            57 =>  613 ?? null, // RDC
            50 =>  614 ?? null, // Congo Braza
            122 =>  615 ?? null, // Liberia
            83 =>  616 ?? null, // Ghana
            42 =>  617 ?? null, // Afrique centrale
            79 =>  618 ?? null, // Gabon
            43 =>  619 ?? null, // Thacd
            66 =>  620 ?? null, // Guinne Equa
            69 =>  621 ?? null, // Ethiopie
            223 =>  622 ?? null, // Ouganda
            191 =>  623 ?? null, // Serria Lionne
            180 =>  624 ?? null, // Rwanda
            112 =>  625 ?? null, // Keneya
            92 =>  626 ?? null, // Guinnéé Bissau
            80 =>  627 ?? null, // Gambie
            137 =>  628 ?? null, // Mauritanie
            37 =>  629 ?? null, // Burundi
            8 =>  630 ?? null, // Angola
            212 =>  631 ?? null, // Tanzanie

        ];

        if (isset($countriesLevels[$countrie])) {
            $levelValeur = $countriesLevels[$countrie];
        } else {
            return null; // Aucune correspondance pour $countrie
        }

        return  LevelCountry::whereId($levelValeur)->select('percent' , 'percent_admin')->first();
    }


    public function getGainAdminsProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return AdminsCom::whereIsWeb(true)->sum('amount');
    }

    public function getGainAdminsDaysProperty()
    {
        $data = [];

        // Exécutez la requête pour obtenir une collection d'objets
        $admins = AdminsCom::whereIsWeb(true)->whereBetween('created_at', [$this->startDate, $this->endDate])->get();

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

        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return AdminsCom::whereIsWeb(true)->where('description', 'Commission administrateur')->whereStatus('SUCCESS')->whereDate('created_at', $convertions)->sum('amount');
    }

    public function getGainAdminDateProperty()
    {

        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return AdminsCom::whereIsWeb(true)->whereBetween('created_at', [$this->startDate, $this->endDate])->where('description', 'Commission administrateur')->whereStatus('SUCCESS')->sum('amount');
    }


    public function getGainAdminStatutProperty()
    {

        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return AdminsCom::whereIsWeb(true)->where("status", $this->statusTransferFilter)->where('description', 'Commission administrateur')->sum('amount');
    }

    public function getGainAdminDrawalTodaysProperty()
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return AdminsCom::whereIsWeb(true)->where('description', 'Paiement commission sous-distributer')->whereStatus('SUCCESS')->whereDate('created_at', $convertions)->sum('amount');
    }

    public function getGainAdminTransactionsTodaysProperty()
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return AdminsCom::whereIsWeb(true)->where('description_underdistributer', 'Paiement commission sous-distributer')->whereStatus('SUCCESS')->whereDate('created_at', $convertions)->sum('amount_under');
    }

    public function getGainAdminTransactionsDaysProperty()
    {


        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return AdminsCom::whereIsWeb(true)->whereBetween('created_at', [$this->startDate, $this->endDate])->where('description_underdistributer', 'Paiement commission sous-distributer')->whereStatus('SUCCESS')->sum('amount_under');
    }

}
