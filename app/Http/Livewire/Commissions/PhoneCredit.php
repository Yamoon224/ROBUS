<?php

namespace App\Http\Livewire\Commissions;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Costs\PhoneCreditCost;

class PhoneCredit extends Component
{

    public $search = "";
    public $phoneId;
    public $phoneStatus;
    public $phoneName;
    public $phonePercent;
    public $orderCardMerchant;
    public $comission_master;
    public $comission_admin;


    protected $rules = [
        'phoneName' => 'required',
        'phonePercent' => 'required',
        'orderCardMerchant' => 'required',
    ];

    protected $messages = [
        'phoneName.required' => 'Ce champ est requis',
        'phonePercent.required' => 'Ce champ est requis',
        'orderCardMerchant.required' => 'Ce champ est requis',
    ];


    public function add()
    {
        $this->validate();
        $check = PhoneCreditCost::whereName($this->phoneName)->first();
        if (!$check) {
            PhoneCreditCost::create(
                [
                    'name' => $this->phoneName,
                    'percent'  => $this->phonePercent,
                    'percent_merchands'  => $this->orderCardMerchant,
                    'percent_masters'  => $this->comission_master,
                    'created_by' => Auth::guard('web')->user()->id,
                    'status'  => 1
                ]
            );
            $this->phoneName = '';
            $this->phonePercent = '';
            $this->orderCardMerchant = '';
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Commission  ajouté !');
        } else $this->emit('error', 'Commission  existante !');
    }

    public function edit($id, $state)
    {
        $check = PhoneCreditCost::whereId($id)->first();
        $this->phoneId = $id;
        $this->phoneName = $check->name;
        $this->phonePercent = $check->percent * 100;
        $this->orderCardMerchant = $check->percent_merchands * 100;
        $this->comission_master = $check->percent_masters * 100;
        $this->comission_admin = $check->percent_banque  * 100;
        $this->phoneStatus = $check->status;
        $this->emit($state);
    }

    public function update()
    {
        $this->validate();
        $check = PhoneCreditCost::whereId($this->phoneId)->first();
        if ($check->id != 3) {
            
            if ($this->orderCardMerchant > $this->phonePercent) {
                $this->emit('modal-hide', 'hide');
                $this->emit('error', 'Svp verifier les comissions !');
            } else {

                $check->update([
                    'name' => $this->phoneName,
                    'percent' => $this->phonePercent,
                    'percent_merchands'  => $this->orderCardMerchant,
                    'percent_masters'  => $this->comission_master,
                    'percent_banque'  => $this->comission_admin,
                    'status' => 1,
                    'created_by' => Auth::guard('web')->user()->id,
                ]);

                $this->resetField();
                $this->emit('modal-hide', 'hide');
                $this->emit('success', 'Commission  modifié !');
            }

        } else {

            $check->update([
                'name' => $this->phoneName,
                'percent' => $this->phonePercent,
                'percent_merchands'  => $this->orderCardMerchant,
                'percent_masters'  => $this->comission_master,
                'percent_banque'  => $this->comission_admin,
                'status' => 1,
                'created_by' => Auth::guard('web')->user()->id,
            ]);

            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Commission  modifié !');
        }
    }

    public function resetField()
    {
        $this->phoneName = '';
        $this->phonePercent = '';
        $this->orderCardMerchant = '';
        $this->comission_master = '';
        $this->comission_admin = '';
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = PhoneCreditCost::whereId($this->phoneId)->first();
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? ' activée !' : ' désactivée !');
        }
    }

    public function delete()
    {
        $check = PhoneCreditCost::find($this->phoneId);
        if (!empty($check)) {
            $check->destroy($this->phoneId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Credit telephonique supprimée !');
        }
    }

    public function render()
    {
        $phone_cost = PhoneCreditCost::query();

        if ($this->search != "") {

            $this->resetPage();

            $phone_cost->where("name", "LIKE",  "%" . $this->search . "%");
        }
        return view('livewire.commissions.phone-credit', [
            "phone_credits" => $phone_cost->orderBy('name', 'desc')->latest()->get(),
        ]);
    }
}
