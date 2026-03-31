<?php

namespace App\Http\Livewire;

use App\Events\assignementEvent;
use App\Mail\UvSend;
use App\Models\Company;
use App\Models\Custumers\City;
use App\Models\Custumers\Country;
use App\Models\Operator;
use App\Models\OperatorService;
use App\Models\Sid\SidUser;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class UnderDistributors extends Component
{
    use WithPagination;

    public $search = "";
    public $countriesFilter = "";
    public $perPage = 30;
    public $sortField = 'id';
    public $sortAsc = true;
    public $statusFilter_active = "";
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
    public $startDate = null;
    public $endDate = null;

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

    public function edit($id, $state)
    {
        $check = SidUser::whereId($id)->first();
        $this->sidUserId = $id;
        $this->sidUserStatus = $check->status;
        $this->sidUserInfos = $check->firstname . ' ' . $check->lastname;

        $this->user_country = $check->country->id;
        $this->emit($state);
    }

    public function editConnected($id, $state)
    {
        $check = SidUser::whereId($id)->first();
        $this->user_id = $check->id;
        $this->master_id = $check->master_id;
        $this->user_country = $check->country->id;
        $this->underdistributeurDesabled = $check->is_accepted;
        $this->emit($state);
    }


    public function activeAll()
    {

        OperatorService::truncate();
        $utilisateurs = SidUser::where('is_under_distributer', true)->get();
        $operations = Operator::whereIsCommission(true)->get();

        foreach ($utilisateurs as $utilisateur) {
            foreach ($operations as $operation) {
                OperatorService::create([
                    'sid_user_id' => $utilisateur->id,
                    'operator_id' => $operation->id,
                ]);
            }
        }
    }

    public function activeAllOPerators()
    {


        $utilisateurs = SidUser::where('is_under_distributer', true)->get();
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

    public function editConnectedCompanie($id, $state)
    {
        $check = SidUser::whereId($id)->first();
        $this->user_id = $check->id;
        $this->company_id = $check->company_id;
        $this->user_country = $check->country->id;
        $this->sidUserInfos = $check->firstname . ' ' . $check->lastname;
        $this->underdistributeurDesabled = $check->is_accepted_companie;
        $this->emit($state);
    }

    public function addConnected($id, $state)
    {
        $check = SidUser::whereId($id)->first();
        $this->user_id = $check->id;
        $this->master_id = $check->master_id;
        $this->user_country = $check->country->id;
        $this->user_city = $check->city->id;
        $this->underdistributeurDesabled = $check->is_accepted;
        $this->emit($state);
    }

    public function addConnectedCompanie($id, $state)
    {
        $check = SidUser::whereId($id)->first();
        $this->user_id = $check->id;
        $this->company_id = $check->company_id;
        $this->user_country = $check->country->id;
        $this->user_city = $check->city->id;
        $this->underdistributeurDesabled = $check->is_accepted_companie;
        $this->emit($state);
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


    public function connected()
    {
        $check = SidUser::find($this->user_id);

        $check_master = SidUser::find($this->master_id)->first();

        if (!empty($check)) {
            if ($check->master_id  == $this->master_id && $check->is_accepted) {
                $msg = 'Vous avez été lié a un sous distributeur';
                $msg_title = 'Liaison a un sous distributeur';
                $check_master->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $check->update(['master_id' => null, 'is_accepted' => false]);
                $check->assignRole(4);
            } else {
                $msg = 'Vous avez été detaché a un sous distributeur';
                $msg_title = 'Détachément a un sous distributeur';
                $check_master->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $check->update(['master_id' => $this->master_id, 'is_accepted' => true]);
                $check->assignRole(4);
            }
            $this->cancelConnectedField();
            $this->emit('modal-hide', 'hide');
            $this->emit('refreshPage'); // À appeler après avoir effectué les modifications
            $this->emit('success', $check->master_id  == $this->master_id ? 'Sous distributeur lié !' : 'Sous distributeur détaché !');
        }
    }

    public function connectedCompanie()
    {
        $check = SidUser::find($this->user_id);

        $check_master = SidUser::find($this->company_id)->first();

        if (!empty($check)) {
            if ($check->company_id  == $this->company_id && $check->is_accepted) {
                $msg = 'Vous avez été lié a un sous distributeur';
                $msg_title = 'Liaison a un sous distributeur';
                $check_master->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $check->update(['company_id' => null, 'is_accepted_companie' => false]);
                $check->assignRole(4);
            } else {
                $msg = 'Vous avez été detaché a un sous distributeur';
                $msg_title = 'Détachément a un sous distributeur';
                $check_master->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $check->update(['company_id' => $this->company_id, 'is_accepted_companie' => true]);
                $check->assignRole(4);
            }
            $this->cancelConnectedField();
            $this->emit('modal-hide', 'hide');
            $this->emit('refreshPage'); // À appeler après avoir effectué les modifications
            $this->emit('success', $check->company_id  == $this->company_id ? 'Sous distributeur lié !' : 'Sous distributeur détaché !');
        }
    }

    public function cancelConnectedField()
    {
        $this->user_country = null;
        $this->user_city = null;
        $this->master_selected = null;
        $this->emit('modal-hide', 'hide');
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

    public function connectedOneMaster()
    {
        $check = SidUser::find($this->user_id);

        $check_master = SidUser::find($this->master_selected)->first();

        if (!empty($check)) {
            $check->update(['master_id' => $this->master_selected, 'is_accepted' => true]);
            $check->assignRole(4);
            $msg = 'Vous avez été lié a un sous distributeur';
            $msg_title = 'Liaison a un sous distributeur';
            $check_master->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
            $this->emit('modal-hide', 'hide');
            $this->cancelConnectedField();
            $this->emit('refreshPage');
            $this->emit('success', $check->master_id  == $this->master_selected ? 'Sous distributeur lié !' : 'Sous distributeur désactivée !');
        }
    }

    public function connectedOneCompanie()
    {
        $check = SidUser::find($this->user_id);

        $check_company = SidUser::whereId($this->master_selected)->first();


        if (!empty($check_company->company_id)) {

            if ($check->company_id  == $this->company_id && $check_company->is_accepted_companie) {

                $msg = 'Vous avez été lié a un sous distributeur';
                $msg_title = 'Liaison a un sous distributeur';
                $check->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $check->update(['company_id' => $check_company->company_id, 'is_accepted_companie' => true]);
            } else {
                $msg = 'Vous avez été détaché a un sous distributeur';
                $msg_title = 'Détachément a un sous distributeur';
                $check->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $check->update(['company_id' => $check_company->company_id, 'is_accepted_companie' => true]);
            }

            $this->cancelConnectedField();
            $this->emit('refreshPage');
            $this->emit('success', $check->company_id  == $this->master_selected ? 'Sous distributeur lié !' : 'Sous distributeur désactivée !');
        } else {
            $this->cancelConnectedField();
            $this->emit('refreshPage');
            $this->emit('error', 'Pas de selection de la companie !');
        }
    }


    public function deconnectedOneCompanie()
    {
        $check = SidUser::find($this->user_id);

        if (!empty($check)) {

            if ($check->company_id  == $this->company_id && $check->is_accepted_companie) {
                $msg = 'Vous avez été lié a un sous distributeur';
                $msg_title = 'Liaison a un sous distributeur';
                $check->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $check->update(['company_id' => 1, 'is_accepted_companie' => false]);
            } else {
                $this->cancelConnectedField();
                $this->emit('refreshPage');
                $this->emit('error', 'Pas de selection de la companie !');
            }

            $this->cancelConnectedField();
            $this->emit('refreshPage');
            $this->emit('success', $check->company_id  == $this->master_selected ? 'Sous distributeur lié !' : 'Sous distributeur désactivée !');
        } else {
            $this->cancelConnectedField();
            $this->emit('refreshPage');
            $this->emit('error', 'Pas de selection de la companie !');
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
        $usersQuery = SidUser::query();

        if ($this->search != "") {
            $this->resetPage();
            $usersQuery->where('is_under_distributer', true)
                ->when($this->search, function ($query) {
                    $query->where(function ($query) {
                        $query->where("firstname", "LIKE", "%" . $this->search . "%")
                            ->orWhere("lastname", "LIKE", "%" . $this->search . "%")
                            ->orWhere("phone", "LIKE", "%" . $this->search . "%")
                            ->orWhere("email", "LIKE", "%" . $this->search . "%");
                    });
                });
        }

        if ($this->countriesFilter != "") {
            $usersQuery->where("country_id", $this->countriesFilter);
        }

        if (!is_array($this->cities) && !is_object($this->cities)) {
            $this->cities = [];
        }

        if ($this->statusFilter_active != "") {
            $usersQuery->where("status", $this->statusFilter_active);
        }

        if ($this->citiesFilter != "") {
            $usersQuery->where("city_id", $this->citiesFilter);
        }

        if ($this->cityNameSearch != "") {
            $this->resetPage();
            $usersQuery->where('is_under_distributer', true)
                ->when($this->cityNameSearch, function ($query) {
                    $query->where(function ($query) {
                        $query->where("zone", "LIKE", "%" . $this->cityNameSearch . "%");
                    });
                });
        }

        if ($this->startDate) {
            $usersQuery->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $usersQuery->whereDate('created_at', '<=', $this->endDate);
        }

        // Récupérer les utilisateurs avec leurs relations
        $users = $usersQuery->where('is_under_distributer', true)
            ->with(['wallet.sidUser.country.currencie']) // Charge les relations nécessaires pour la conversion
            ->withSum('wallet as wallet_balance', 'amount')
            ->orderByDesc('wallet_balance')
            ->paginate($this->perPage ?? 30);

        // Ajouter les informations de conversion et niveaux à chaque utilisateur
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

        return view('livewire.under-distributors', [
            "users" => $users,
            "countries" => Country::where('robus', true)->orderBy("name", 'desc')->get(),
            "operators" => Operator::whereStatus(true)->whereIsUnder(true)->orderBy("name", 'desc')->get(),
            "operatorsCommissions" => Operator::whereStatus(true)->whereIsCommission(true)->orderBy("name", 'desc')->get(),
        ]);
    }
}
