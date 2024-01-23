<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AdminsCom;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;

class Gain extends Component
{


    use WithPagination;

    public $search = "";

    public $typeFilter = "";
    public  $startDate = "";
    public  $endDate =  "";

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

        return view('livewire.gain', [
            "gains" => $gains->where('description', '!=', 'Validation distributeur')->orderBy('created_at', 'desc')->latest()->paginate(30),
            "sum_gains" => $this->GainAdmins,
            "sum_gain_days" => $this->GainAdminTodays,
            "sum_gain_drawal_days" =>  $this->GainAdminTransactionsTodays,
        ]);
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