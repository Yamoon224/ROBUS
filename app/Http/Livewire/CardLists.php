<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Banks\BankCard;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\Auth;

class CardLists extends Component
{
    use WithPagination;

    public $search = "";
    public $countriesFilter = "";
    public $ref_card_card;
    public $phone_card;
    public $email_card;
    public $cardStatus;
    public $cardId;
    public $showNBankNotification = false;

    protected $listeners = [
        'echo:card-bank-created,.Created_card_bank' => 'notifyNewOrderCard',
        'echo:updated_order_card,.update_order_card' => 'notifyNewOrderCard',
    ];

    public function edit($id, $state)
    {
        $check = BankCard::whereId($id)->first();
        $this->cardStatus = $check->status;
        $this->phone_card = $check->phone;
        $this->email_card = $check->email;
        $this->emit($state);
    }

    public function notifyNewOrderCard()
    {
        $this->showNBankNotification = true;
        $this->emit('modal-hide');
    }

    public function disable()
    {
        $check = BankCard::find($this->cardId);

        if (!empty($check)) {
            if ($check->status == 0) $check->update(
                [
                    'status' => 1,
                    'ref_card' => $this->ref_card_card
                ]
            );
            else $check->update([
                'status' => 0,
                'ref_card' => $this->ref_card_card
            ]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Carte activée !' : 'Carte désactivée !');
        }
    }

    public function render()
    {
        $bank_cards = BankCard::query();

        $walletUser = Auth::guard('web')->user();


        if ($walletUser->is_under_distributer) {
            $bank_cards->where('created_by', $walletUser->id); 
        }

        if ($this->search != "") {

            $this->resetPage();

            $bank_cards->where("bank_cards.firstname", "LIKE",  "%" . $this->search . "%")
                ->orWhere("bank_cards.lastname", "LIKE",  "%" . $this->search . "%")
                ->orWhere("bank_cards.email", "LIKE",  "%" . $this->search . "%")
                ->orWhere("bank_cards.phone", "LIKE",  "%" . $this->search . "%");
        }

        if ($this->countriesFilter != "") {
            $bank_cards->where("country_id", $this->countriesFilter);
        }

        return view('livewire.card-lists', [
            "cards" => $bank_cards->orderBy('bank_cards.created_at', 'desc')->paginate(30),
            "countries" => Country::whererobus(true)->orderBy("name", 'desc')->get()
        ]);
    }
}
