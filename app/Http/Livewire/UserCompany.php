<?php

namespace App\Http\Livewire;

use App\Mail\UvSend;
use App\Models\Custumers\Country;
use App\Models\Operator;
use App\Models\OperatorService;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;
use App\Events\assignementEvent;
use App\Models\Custumers\City;
use App\Notifications\UserNotification;

class UserCompany extends Component
{

    use WithPagination;

    public $search = "";
    public $master_selected;
    public $countriesFilter = "";
    public $searchComZone = "";
    public $perPage = 30;
    public $sortField = 'id';
    public $sortAsc = true;
    public $sidUserId;
    public $user_city;
    public $sidUserStatus;
    public $user_country;
    public $user_id;
    public $underdistributeurDesabled;
    public $countries;
    public $cities = [];
    public $citiesFilter;
    public $cityNameSearch = '';
    public $sidUserInfos;
    public $master_id;

    public function mount()
    {
        $this->countries = Country::orderBy('name')->get();
    }


    public function addConnectedComZone($id, $state)
    {
        $check = SidUser::whereId($id)->first();
        $this->user_id = $check->id;
        $this->master_id = $check->com_zone_id;
        $this->user_country = $check->country->id;
        $this->user_city = $check->city->id;
        $this->underdistributeurDesabled = $check->is_accepted;
        $this->emit($state);
    }

    public function updatedCountriesFilter($countryId)
    {

        if ($countryId) {
            $this->cities = City::where('country_id', $countryId)
                ->orderBy('name')
                ->get();
        } else {
            $this->cities = [];
        }

        $this->citiesFilter = null;
    }

    public function edit($id, $state)
    {
        $check = SidUser::whereId($id)->first();
        $this->sidUserId = $id;
        $this->sidUserInfos = $check->firstname . ' ' . $check->lastname;
        $this->sidUserStatus = $check->status;
        $this->emit($state);
    }

    public function deletedUser()
    {
        $user = SidUser::find($this->sidUserId);

        if (!empty($user)) {

            if ($user->wallet) {
                $user->wallet->forceDelete();
            }

            $user->forceDelete();
            $this->emit('modal-hide', 'hide');
            $this->emit('refreshPage');
            $this->emit('success', 'Utilisateur supprimer avec success !');
        }
    }

    public function editConnected($id, $state)
    {
        $check = SidUser::whereId($id)->first();
        $this->user_id = $check->id;
        $this->master_id = $check->com_zone_id;
        $this->user_country = $check->country->id;
        $this->underdistributeurDesabled = $check->is_accepted;
        $this->emit($state);
    }


    public function cancelConnectedField()
    {
        $this->user_country = null;
        $this->user_city = null;
        $this->master_selected = null;
        $this->emit('modal-hide', 'hide');
    }

    public function connectedOneComZone()
    {
        $check = SidUser::find($this->user_id);

        $check_master = SidUser::find($this->master_selected)->first();

        if (!empty($check)) {
            $check->update(['com_zone_id' => $this->master_selected, 'is_com_accepted' => true]);
            $msg = 'Vous avez été lié a un SD';
            $msg_title = 'Liaison a un SD';
            $check_master->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
            $this->emit('modal-hide', 'hide');
            $this->cancelConnectedField();
            $this->emit('refreshPage');
            $this->emit('success', $check->com_zone_id  == $this->master_selected ? 'SD lié !' : 'SD désactivée !');
        }
    }

    public function confirmDetach()
    {
        $user = SidUser::find($this->user_id);
        if ($user) {
            $user->update([
                'com_zone_id' => null,
                'is_com_accepted' => false
            ]);
            $this->emit('refreshPage');
            $this->emit('success', 'Utilisateur détaché avec succès.');
        }
        $this->resetField();
    }
    public function edit_update($data)
    {

        $sid_user = $data['user_id'];

        $operator_id = $data['operator_id'];

        $check_user = SidUser::whereId($sid_user)->first();

        $check_operator = Operator::whereId($operator_id)->first();

        $old_operators = OperatorService::where('sid_user_id', $check_user->id)
            ->where('operator_id', $check_operator->id)
            ->first();

        if (!empty($old_operators)) {

            $old_operators->forcedelete();
            event(new assignementEvent($old_operators));
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Liste des operateurs mise à jour !');
        } else {

            $operator_service = new OperatorService;
            $operator_service->sid_user_id = $check_user->id;
            $operator_service->operator_id  = $check_operator->id;
            $operator_service->save();
            event(new assignementEvent($operator_service));

            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Liste des operateurs mise à jour !');
        }
    }

    public function resetField()
    {
        $this->sidUserId = '';
        $this->sidUserStatus = '';
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = SidUser::whereId($this->sidUserId)->first();

        if (!empty($check)) {

            if ($check->status == 0) {
                $check->update(['status' => 1]);

                if ($check->email != null) {

                    Mail::to($check->email)->send(new UvSend('Votre compte Robus a été activé pour une raison \n Merci Bien'));
                }

                $this->resetField();
                $this->emit('modal-hide', 'hide');
                $this->emit('success', $check->status == 1 ? 'Utilisateur activée !' : 'Utilisateur désactivée !');
            } else {

                $check->update(['status' => 0]);

                if ($check->email != null) {

                    Mail::to($check->email)->send(new UvSend('Votre compte Robus a été desactivé pour une raison \n Merci Bien'));
                }
                $this->resetField();
                $this->emit('modal-hide', 'hide');
                $this->emit('success', $check->status == 1 ? 'Utilisateur activée !' : 'Utilisateur désactivée !');
            }
        }
    }

    public function render()
    {
        $users = SidUser::query()->where('is_company', true);

        if ($this->search != "") {
            $this->resetPage();

            $users->where(function ($query) {
                $query->where('is_admin', true)
                    ->orWhere("is_distributer", true)
                    ->orWhere("is_under_distributer", true)
                    ->orWhere("firstname", "LIKE", "%" . $this->search . "%")
                    ->orWhere("lastname", "LIKE", "%" . $this->search . "%")
                    ->orWhere("phone", "LIKE", "%" . $this->search . "%")
                    ->orWhere("email", "LIKE", "%" . $this->search . "%");
            });
        }



        if ($this->countriesFilter != "") {
            $users->where("country_id", $this->countriesFilter);
        }

        if (!is_array($this->cities) && !is_object($this->cities)) {
            $this->cities = [];
        }

        if ($this->citiesFilter != "") {
            $users->where("city_id", $this->citiesFilter);
        }

        if ($this->cityNameSearch != "") {

            $this->resetPage();
            $users->where('is_company', true)
                ->when($this->cityNameSearch, function ($query) {
                    $query->where(function ($query) {
                        $query->where("zone", "LIKE", "%" . $this->cityNameSearch . "%");
                    });
                })
                ->paginate($this->perPage);
        }


        $users = $users->with(['wallet.sidUser.country.currencie'])
            ->withSum('wallet as wallet_balance', 'amount')
            ->orderByDesc('wallet_balance')
            ->paginate($this->perPage ?? 30);


        foreach ($users as $user) {
            $solde = $user->wallet_balance ?? 0;
            $currencyUser = $user->wallet?->sidUser?->country?->currencie?->value;
            $senderCurrency = sabdy()->country?->currencie?->value;

            // Logique de conversion
            if ($senderCurrency > $currencyUser) {
                $amount_solde = (float) $solde * (float) $senderCurrency;
            } elseif ($senderCurrency == $currencyUser) {
                $amount_solde = (float) $solde;
            } elseif ($senderCurrency < $currencyUser) {
                $amount_solde = (float) $solde * (float) $currencyUser;
            } else {
                $amount_solde = (float) $solde;
            }

            // Déterminer le niveau basé sur le montant en FCFA
            if ($amount_solde >= 100000) {
                $user->level_data = [
                    'name' => 'Diamant',
                    'color' => '#fbbf24',
                    'bg' => '#fef3c7',
                    'text' => '#92400e',
                    'icon' => '💎',
                    'next_level' => null,
                    'next_target' => null,
                    'percentage' => 100
                ];
            } elseif ($amount_solde >= 50000) {
                $user->level_data = [
                    'name' => 'Or',
                    'color' => '#3b82f6',
                    'bg' => '#dbeafe',
                    'text' => '#1e40af',
                    'icon' => '🥇',
                    'next_level' => 'Diamant',
                    'next_target' => 100000 - $amount_solde,
                    'percentage' => ($amount_solde / 100000) * 100
                ];
            } elseif ($amount_solde > 0) {
                $user->level_data = [
                    'name' => 'Argent',
                    'color' => '#10b981',
                    'bg' => '#d1fae5',
                    'text' => '#065f46',
                    'icon' => '🥈',
                    'next_level' => 'Or',
                    'next_target' => 50000 - $amount_solde,
                    'percentage' => ($amount_solde / 100000) * 100
                ];
            } else {
                $user->level_data = [
                    'name' => 'Bronze',
                    'color' => '#9ca3af',
                    'bg' => '#f3f4f6',
                    'text' => '#4b5563',
                    'icon' => '🥉',
                    'next_level' => 'Argent',
                    'next_target' => null,
                    'percentage' => 0
                ];
            }

            // Stocker le montant converti
            $user->amount_fcfa = $amount_solde;
        }

        $query = SidUser::whereIsComZone(true)
            ->where('country_id', $this->user_country)
            ->when($this->searchComZone, function ($q) {
                $q->where(function ($q2) {
                    $q2->where('firstname', 'like', '%' . $this->searchComZone . '%')
                        ->orWhere('lastname', 'like', '%' . $this->searchComZone . '%')
                        ->orWhere('email', 'like', '%' . $this->searchComZone . '%');
                });
            });

        return view('livewire.user-company', [
            "users" => $users,
            "comZones" => $query->paginate(10),
            "countries" => Country::where('robus', true)->orderBy("name", 'desc')->get(),
            "operators" => Operator::whereStatus(true)->whereIsCommission(true)->orderBy("name", 'desc')->get(),
        ]);
    }
}
