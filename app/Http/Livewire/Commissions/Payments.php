<?php

namespace App\Http\Livewire\Commissions;

use Livewire\Component;
use App\Models\Costs\PaymentCost;
use Illuminate\Support\Facades\Auth;

class Payments extends Component
{

    public $search = "";
    public $paymentId;
    public $paymentStatus;
    public $payment_name;
    public $payment_amount;
    public $payment_amount_auther;
    public $comission_auther;
    public $comission_canal;
    public $comission_master;


    protected $rules = [
        'payment_name' => 'required',
        'payment_amount_auther' => 'required',
        'comission_auther' => 'required',
        'comission_canal' => 'required',
    ];

    protected $messages = [
        'payment_name.required' => 'Ce champ est requis',
        'payment_amount_auther.required' => 'Ce champ est requis',
        'comission_auther.required' => 'Ce champ est requis',
        'comission_canal.required' => 'Ce champ est requis',

    ];


    public function add()
    {
        $this->validate();
        $check = PaymentCost::whereName($this->payment_name)->first();
        if (!$check) {
            PaymentCost::create(
                [
                    'name' => $this->payment_name,
                    'amount_canal'  => $this->payment_amount,
                    'amount_auther'  => $this->payment_amount_auther,
                    'comission_auther'  => $this->comission_auther,
                    'comission_canal'  => $this->comission_canal,
                    'created_by' => Auth::guard('web')->user()->id,
                    'percent_masters'  => $this->comission_master,
                    'status'  => 1
                ]
            );
            $this->payment_name = '';
            $this->payment_amount = '';
            $this->payment_amount_auther = '';
            $this->comission_auther = '';
            $this->comission_canal = '';
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Commission  ajouté !');
        } else $this->emit('error', 'Commission  existante !');
    }

    public function edit($id, $state)
    {
        $check = PaymentCost::whereId($id)->first();
        $this->paymentId = $check->id;
        $this->payment_name = $check->name;
        $this->payment_amount = $check->amount_canal;
        $this->payment_amount_auther = $check->amount_auther;
        $this->comission_auther = $check->comission_auther;
        $this->comission_canal = $check->comission_canal;
        $this->comission_master = $check->percent_masters;
        $this->paymentStatus = $check->status;
        $this->emit($state);
    }


    public function update()
    {
        $this->validate();
        $check = PaymentCost::whereId($this->paymentId)->first();

        $check->update([
            'name' => $this->payment_name,
            'amount_canal' => $this->payment_amount,
            'amount_auther'  => $this->payment_amount_auther,
            'comission_auther'  => $this->comission_auther,
            'comission_canal'  => $this->comission_canal,
            'percent_masters'  => $this->comission_master,
            'status' => 1,
            'created_by' => Auth::guard('web')->user()->id,
        ]);

        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Commission  modifié !');
    }

    public function resetField()
    {
        $this->payment_name = '';
        $this->payment_amount = '';
        $this->payment_amount_auther = '';
        $this->comission_auther = '';
        $this->comission_canal = '';
        $this->comission_master = '';
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = PaymentCost::first();
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? ' activée !' : ' désactivée !');
        }
    }

    public function delete()
    {
        $check = PaymentCost::find($this->paymentId);
        if (!empty($check)) {
            $check->destroy($this->paymentId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', ' Paiement supprimée !');
        }
    }

    public function render()
    {
        $payment_cost = PaymentCost::query();

        if ($this->search != "") {

            $this->resetPage();

            $payment_cost->where("name", "LIKE",  "%" . $this->search . "%");
        }
        return view('livewire.commissions.payments', [
            "payments" => $payment_cost->orderBy('name', 'desc')->latest()->get(),
        ]);
    }
}
