<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AdminsCom;
use Livewire\WithPagination;

class GainUderDistributer extends Component
{

    use WithPagination;

    public $search = "";

    public $typeFilter = "";

    public function render()
    {

        $gains = AdminsCom::query();

        if ($this->search != "") {

            $this->resetPage();

            $gains->orwhereHas(
                'sidUser',
                function ($query) {
                    $query->where('firstname', 'LIKE', '%' . $this->search . '%')
                        ->orWhere('lastname', 'like', '%' .  $this->search   . '%')
                        ->orWhere('email', 'like', '%' .  $this->search   . '%')
                        ->orWhere('phone', 'like', '%' .  $this->search  . '%');
                }
            )
                ->orWhere("admins_coms.amount_distributer", "LIKE",  "%" . $this->search . "%");
        }

      
        return view('livewire.gain-uder-distributer', [
            "gains" => $gains->where('description_distributer' ,'Validation distributeur')->orderBy('created_at', 'desc')->latest()->paginate(30),
            "sum_gains" => $this->GainAdmins,
            "sum_gain_days" => $this->GainAdminTodays,
        ]);
    }

    public function getGainAdminsProperty()
    {
        $data = [];
        foreach (AdminsCom::where('description_distributer' ,'Validation distributeur')->get() as $bank) {
            $data[] = $bank->amount_distributer;
        }

        return array_sum($data);
    }

    public function getGainAdminTodaysProperty()
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $data = [];

        foreach (AdminsCom::where('description_distributer' ,'Validation distributeur')->whereDate('created_at', $convertions)->get() as $bank) {
           
            $data[] = $bank->amount_distributer;
        }

        return array_sum($data);
    }

    public function getGainAdminDrawalTodaysProperty()
    {

        $todays = Carbon::now();
        $convertions = $todays->toDateString();

        $data = [];

        foreach (AdminsCom::where('description_distributer','Validation distributeur')->whereDate('created_at', $convertions)->get() as $bank) {
           
            $data[] = $bank->amount_distributer;
        }

        return array_sum($data);
    }


}
