<?php

namespace App\Http\Livewire\Commissions;

use Livewire\Component;
use App\Models\Costs\ReloadCost;
use Illuminate\Support\Facades\Auth;
use App\Models\ArgentTresorts;
use App\Models\DjamoPercents;
use App\Models\PushPercents;

class Realods extends Component
{
    public $search = "";
    public $reloadId;
    public $reloadStatus;
    public $reloadName;
    public $reloadPercent;
    public $orderCardMerchant;
    public $transaction_id;
    public $transaction_status;
    public $transaction_name;
    public $percent_transaction;
    public $percent_admin;
    public $percent_deposit;
    public $percent_with_drawal;
    public $distributer_custumer;
    public $comission_master;
    public $comissionCompanie;
    public $editTypeTresort = false;
    public $editTypePush = false;
    public $editTypeDjamo = false;
    public $editTypeXbet = false;

    protected $rules = [
        'reloadName' => 'required',
        'reloadPercent' => 'required',
        'orderCardMerchant' => 'required',
    ];

    protected $messages = [
        'reloadName.required' => 'Ce champ est requis',
        'reloadPercent.required' => 'Ce champ est requis',
        'orderCardMerchant.required' => 'Ce champ est requis',
    ];

    public function editTresort($id, $state)
    {
        $check = ArgentTresorts::whereId($id)->first();

        $this->transaction_id = $check->id;
        $this->editTypeTresort = true;
        $this->transaction_name = $check->name;
        $this->percent_transaction = $check->percent * 100;
        $this->percent_admin = $check->percent_admin * 100;
        $this->percent_with_drawal = $check->percent_with_drawal * 100;
        $this->percent_deposit = $check->percent_merchands * 100;
        $this->comissionCompanie = $check->percent_companie * 100;
        $this->distributer_custumer = $check->distributer_custumer;
        $this->emit($state);
    }

    public function editPush($id, $state)
    {
        $check = PushPercents::whereId($id)->first();

        $this->transaction_id = $check->id;
        $this->transaction_name = $check->name;
        $this->editTypePush = true;
        $this->percent_transaction = $check->percent * 100;
        $this->percent_admin = $check->percent_admin * 100;
        $this->percent_with_drawal = $check->percent_with_drawal * 100;
        $this->percent_deposit = $check->percent_merchands * 100;
        $this->comissionCompanie = $check->percent_companie * 100;
        $this->distributer_custumer = $check->distributer_custumer;
        $this->emit($state);
    }

    public function editDjamo($id, $state)
    {
        $check = DjamoPercents::whereId($id)->first();

        $this->transaction_id = $check->id;
        $this->transaction_name = $check->name;
        $this->editTypeDjamo = true;
        $this->percent_transaction = $check->percent * 100;
        $this->percent_admin = $check->percent_admin * 100;
        $this->percent_with_drawal = $check->percent_with_drawal * 100;
        $this->percent_deposit = $check->percent_merchands * 100;
        $this->comissionCompanie = $check->percent_companie * 100;
        $this->distributer_custumer = $check->distributer_custumer;
        $this->emit($state);
    }


    public function updateTresort()
    {
        
        $this->validate(
            [
                'transaction_name' => 'required',
                'percent_admin' => 'required',
                'percent_transaction' => 'required',
                'percent_deposit' => 'required',
                'comissionCompanie' => 'required',
            ],
            [
                'transaction_name.required' => 'Ce champ est requis',
                'percent_admin.required' => 'Ce champ est requis',
                'percent_transaction.required' => 'Ce champ est requis',
                'percent_deposit.required' => 'Ce champ est requis',
                'comissionCompanie.required' => 'Ce champ est requis',
            ]
        );
        $check = ArgentTresorts::whereId($this->transaction_id)->first();
       
        $check->update([
            'name' => $this->transaction_name,
            'percent' => $this->percent_transaction,
            'percent_admin' => $this->percent_admin,
            'percent_merchands' => $this->percent_deposit,
            'percent_with_drawal'  => $this->percent_with_drawal,
            'percent_companie'  => $this->comissionCompanie,
            'status' => 1,
            'created_by' => Auth::guard('web')->user()->id,
        ]);

        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Commission banque modifié !');
    }


    public function updateDjamo()
    {
        $this->validate(
            [
                'transaction_name' => 'required',
                'percent_admin' => 'required',
                'percent_transaction' => 'required',
                'percent_deposit' => 'required',
                'comissionCompanie' => 'required',
            ],
            [
                'transaction_name.required' => 'Ce champ est requis',
                'percent_admin.required' => 'Ce champ est requis',
                'percent_transaction.required' => 'Ce champ est requis',
                'percent_deposit.required' => 'Ce champ est requis',
                'comissionCompanie.required' => 'Ce champ est requis',
            ]
        );
        $check = DjamoPercents::whereId($this->transaction_id)->first();

        $check->update([
            'name' => $this->transaction_name,
            'percent' => $this->percent_transaction,
            'percent_admin' => $this->percent_admin,
            'percent_merchands' => $this->percent_deposit,
            'percent_with_drawal'  => $this->percent_with_drawal,
            'percent_companie'  => $this->comissionCompanie,
            'status' => 1,
            'created_by' => Auth::guard('web')->user()->id,
        ]);

        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Commission banque modifié !');
    }


    public function updatePush()
    {
        $this->validate(
            [
                'transaction_name' => 'required',
                'percent_admin' => 'required',
                'percent_transaction' => 'required',
                'percent_deposit' => 'required',
                'comissionCompanie' => 'required',
            ],
            [
                'transaction_name.required' => 'Ce champ est requis',
                'percent_admin.required' => 'Ce champ est requis',
                'percent_transaction.required' => 'Ce champ est requis',
                'percent_deposit.required' => 'Ce champ est requis',
                'comissionCompanie.required' => 'Ce champ est requis',
            ]
        );
        $check = PushPercents::whereId($this->transaction_id)->first();

        $check->update([
            'name' => $this->transaction_name,
            'percent' => $this->percent_transaction,
            'percent_admin' => $this->percent_admin,
            'percent_merchands' => $this->percent_deposit,
            'percent_with_drawal'  => $this->percent_with_drawal,
            'percent_companie'  => $this->comissionCompanie,
            'status' => 1,
            'created_by' => Auth::guard('web')->user()->id,
        ]);

        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Commission banque modifié !');
    }


    public function add()
    {
        $this->validate();
        $check = ReloadCost::whereName($this->reloadName)->first();
        if (!$check) {
            ReloadCost::create(
                [
                    'name' => $this->reloadName,
                    'percent'  => $this->reloadPercent,
                    'percent_merchands'  => $this->orderCardMerchant,

                    'created_by' => Auth::guard('web')->user()->id,
                    'status'  => 1
                ]
            );
            $this->reloadName = '';
            $this->reloadPercent = '';
            $this->orderCardMerchant = '';
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Commission  ajouté !');
        } else $this->emit('error', 'Commission  existante !');
    }

    public function edit($id, $state)
    {
        $check = ReloadCost::whereId($id)->first();
        $this->reloadName = $check->name;
        $this->reloadPercent = $check->percent * 100;
        $this->orderCardMerchant = $check->percent_merchands * 100;
        $this->reloadStatus = $check->status;
        $this->emit($state);
    }

    public function update()
    {
        $this->validate();
        $check = ReloadCost::first();
        if ($this->orderCardMerchant > $this->reloadPercent) {
            $this->emit('modal-hide', 'hide');
            $this->emit('error', 'Svp verifier les comissions !');
        } else {
            $check->update([
                'name' => $this->reloadName,
                'percent' => $this->reloadPercent,
                'percent_merchands'  => $this->orderCardMerchant,
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
        $this->reloadName = '';
        $this->reloadPercent = '';
        $this->orderCardMerchant = '';
        $this->transaction_name = '';
        $this->percent_admin = '';
        $this->percent_deposit = '';
        $this->percent_with_drawal = '';
        $this->percent_transaction = '';
        $this->comissionCompanie = '';
        $this->editTypeTresort =false;
        $this->editTypePush =false;
        $this->editTypeDjamo =false;
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = ReloadCost::first();
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? ' activée !' : ' désactivée !');
        }
    }

    public function delete()
    {
        $check = ReloadCost::find($this->reloadId);
        if (!empty($check)) {
            $check->destroy($this->reloadId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Rechargement supprimée !');
        }
    }

    public function render()
    {
        $reload_cost = ReloadCost::query();
        $reload_argentCost = ArgentTresorts::query();
        $reload_djamoCost = DjamoPercents::query();
        $reload_pushCost = PushPercents::query();

        if ($this->search != "") {

            $this->resetPage();

            $reload_cost->where("name", "LIKE",  "%" . $this->search . "%");
        }
        return view('livewire.commissions.realods', [
            "realods" => $reload_cost->orderBy('name', 'desc')->latest()->paginate(1),
            "realodArgents" => $reload_argentCost->orderBy('name', 'desc')->latest()->paginate(10),
            "realodDjamos" => $reload_djamoCost->orderBy('name', 'desc')->latest()->paginate(10),
            "realodPushs" => $reload_pushCost->orderBy('name', 'desc')->latest()->paginate(10),
        ]);
    }
}