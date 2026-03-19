<?php

namespace App\Http\Livewire\Integrations;

use App\Events\assignementEvent;
use App\Mail\UvSend;
use App\Models\Custumers\City;
use App\Models\Custumers\Country;
use App\Models\Operator;
use App\Models\OperatorService;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class ProspectLists extends Component
{
    use WithPagination;
    public $search = "";
    public $countriesFilter = "";
    public $perPage = 30;
    public $sortField = 'id';
    public $sortAsc = true;
    public $sidUserId;
    public $sidUserStatus;
    public $sidUserInfos;
    public $user_id;
    public $master_id;
    public $company_id;
    public $user_country;
    public $user_city;
    public $master_selected;
    public $underdistributeurDesabled;
    public $master_country;
    public $masters;
    public $countries;
    public $cities = [];
    public $citiesFilter;
    public $cityNameSearch = '';

    public function mount()
    {
        $this->countries = Country::orderBy('name')->get();
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
            $this->resetdesableField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Liste des operateurs mise à jour !');
        } else {

            $operator_service = new OperatorService;
            $operator_service->sid_user_id = $check_user->id;
            $operator_service->operator_id  = $check_operator->id;
            $operator_service->save();
            event(new assignementEvent($operator_service));

            $this->resetdesableField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Liste des operateurs mise à jour !');
        }
    }

    public function activeAllOPerators()
    {


        $utilisateurs = SidUser::where('is_api', true)->get();
        $operations = Operator::whereIsUnder(true)->get();

        foreach ($utilisateurs as $utilisateur) {
            foreach ($operations as $operation) {
                OperatorService::create([
                    'sid_user_id' => $utilisateur->id,
                    'operator_id' => $operation->id,
                ]);
            }
        }
    }

    public function edit($id, $state)
    {
        $check = SidUser::whereId($id)->first();
        $this->sidUserId = $id;
        $this->sidUserStatus = $check->status;
        $this->sidUserInfos = $check->firstname . ' ' . $check->lastname;

        $this->user_country = $check->country->id;
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
            $this->cancelConnectedField();
            $this->emit('refreshPage');
            $this->emit('success', 'Utilisateur supprimer avec success !');
        }
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

    public function resetField()
    {
        $this->sidUserId = '';
        $this->sidUserStatus = '';
        $this->emit('modal-hide', 'hide');
    }

    public function validated()
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
      public function editUpdateCommission($data)
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
            $this->resetdesableField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Liste des operateurs mise à jour !');
        } else {

            $operator_service = new OperatorService;
            $operator_service->sid_user_id = $check_user->id;
            $operator_service->operator_id  = $check_operator->id;
            $operator_service->save();
            event(new assignementEvent($operator_service));

            $this->resetdesableField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Liste des operateurs mise à jour !');
        }
    }

       public function resetdesableField()
    {
        $this->emit('modal-hide', 'hide');
    }

    public function render()
    {

        $userQuery = SidUser::query()->with('integrator');


        if ($this->search != "") {

            $this->resetPage();

            $userQuery->where('is_api', true)
                ->when($this->search, function ($query) {
                    $query->where(function ($query) {
                        $query->where("firstname", "LIKE", "%" . $this->search . "%")
                            ->orWhere("lastname", "LIKE", "%" . $this->search . "%")
                            ->orWhere("phone", "LIKE", "%" . $this->search . "%")
                            ->orWhere("email", "LIKE", "%" . $this->search . "%");
                    });
                })
                ->paginate($this->perPage);
        }

        if ($this->countriesFilter != "") {
            $userQuery->where("country_id", $this->countriesFilter);
        }

        if (!is_array($this->cities) && !is_object($this->cities)) {
            $this->cities = [];
        }

        if ($this->citiesFilter != "") {
            $userQuery->where("city_id", $this->citiesFilter);
        }

        if ($this->cityNameSearch != "") {

            $this->resetPage();
            $userQuery->where('is_api', true)
                ->when($this->cityNameSearch, function ($query) {
                    $query->where(function ($query) {
                        $query->where("zone", "LIKE", "%" . $this->cityNameSearch . "%");
                    });
                })
                ->paginate($this->perPage);
        }


        $users = $userQuery->with(['wallet.sidUser.country.currencie']) // Charge les relations nécessaires pour la conversion
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

        return view('livewire.integrations.prospect-lists', [
            "users" => $users,
            "countries" => Country::where('robus', true)->orderBy("name", 'desc')->get(),
            "operators" => Operator::whereStatus(true)->whereIsApi(true)->whereIsCommission(false)->orderBy("name", 'desc')->get(),
            "operatorsCommissions" => Operator::whereStatus(true)->whereIsCommission(true)->whereIsApi(true)->orderBy("name", 'desc')->get(),
        ]);
    }
}
