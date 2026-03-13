<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AdminsCom;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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

        return AdminsCom::whereIsWeb(false)
            ->whereDate('admins_coms.created_at', $convertions)
            ->sum(DB::raw('amount_cie + amount_bank'));
    }

    public function getWalletIntersDaysProperty()
    {

        return AdminsCom::whereIsWeb(false)
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->sum(DB::raw('amount_cie + amount_bank'));
    }


    public function getWalletIntersStatutsProperty()
    {


        return AdminsCom::whereIsWeb(false)
            ->whereDate('admins_coms.status', $this->statusTransferFilter)
            ->sum(DB::raw('amount_cie + amount_bank'));
    }


    public function getGainAdminsProperty()
    {
        return AdminsCom::whereIsWeb(false)->sum('amount');
    }

    public function getGainAdminsDaysProperty()
    {


        return AdminsCom::whereIsWeb(false)
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->sum(DB::raw('amount_cie + amount_bank'));
    }

    public function getGainAdminTodaysProperty()
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        return AdminsCom::whereIsWeb(false)
            ->where('description', 'Commission administrateur')->whereStatus('SUCCESS')->whereDate('created_at', $convertions)
            ->sum(DB::raw('amount'));
    }

    public function getGainAdminDateProperty()
    {


        return AdminsCom::whereIsWeb(false)
            ->where('description', 'Commission administrateur')->whereStatus('SUCCESS')->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->sum(DB::raw('amount'));
    }


    public function getGainAdminStatutProperty()
    {


        return AdminsCom::whereIsWeb(false)
            ->where('description', 'Commission administrateur')->where("status", $this->statusTransferFilter)
            ->sum(DB::raw('amount'));
    }

    public function getGainAdminDrawalTodaysProperty()
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();


        return AdminsCom::whereIsWeb(false)
            ->where('description', 'Paiement commission sous-distributer')->whereStatus('SUCCESS')->whereDate('created_at', $convertions)
            ->sum(DB::raw('amount'));
    }

    public function getGainAdminTransactionsTodaysProperty()
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();


        return AdminsCom::whereIsWeb(false)
            ->where('description_underdistributer', 'Paiement commission sous-distributer')->whereStatus('SUCCESS')->whereDate('created_at', $convertions)
            ->sum(DB::raw('amount'));
    }

    public function getGainAdminTransactionsDaysProperty()
    {


        return AdminsCom::whereIsWeb(false)
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->where('description_underdistributer', 'Paiement commission sous-distributer')->whereStatus('SUCCESS')
            ->sum(DB::raw('amount'));
    }
}
