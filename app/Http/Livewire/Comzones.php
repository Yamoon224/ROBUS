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
use App\Notifications\UserNotification;

class Comzones extends Component
{
    use WithPagination;

    public $search = "";
    public $showMasterModal = false;
    public $countriesFilter = "";
    public $perPage = 30;
    public $sortField = 'id';
    public $sidUserInfos;
    public $sortAsc = true;
    public $sidUserId;
    public $sidUserStatus;
    public $user_id;
    public $comzone_id;
    public $company_id;
    public $selected_masters = [];
    public $user_country;
    public $user_city;
    public $master_selected;
    public $comzoneDesabled = 0;
    public $searchCustumers = '';

    public function edit($id, $state)
    {
        $check = SidUser::whereId($id)->first();
        $this->sidUserId = $id;
        $this->sidUserInfos = $check->firstname . ' ' . $check->lastname;
        $this->sidUserStatus = $check->status;
        $this->emit($state);
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
            $this->resetField();
            $this->emit('refreshPage');
            $this->emit('success', 'Utilisateur supprimer avec success !');
        }
    }


    public function addConnected($id, $state)
    {
        $check = SidUser::whereId($id)->first();
        $this->user_id = $check->id;
        $this->comzone_id = $check->com_zone_id;
        $this->user_country = $check->country->id;
        $this->user_city = $check->city->id;
        $this->comzoneDesabled = $check->is_com_accepted;
        $this->emit($state);
    }

    public function editConnectedComZone($id, $state)
    {
        $check = SidUser::whereId($id)->first();
        $this->user_id = $check->id;
        $this->company_id = $check->com_zone_id;
        $this->user_country = $check->country->id;
        $this->sidUserInfos = $check->firstname . ' ' . $check->lastname;
        $this->comzoneDesabled = $check->is_com_accepted;
        $this->emit($state);
    }

    public function connected()
    {

        // Vérifier qu'au moins un utilisateur est sélectionné
        if (empty($this->selected_masters)) {
            $this->emit('error', 'Veuillez sélectionner au moins un utilisateur.');
            return;
        }

        // Récupérer le COM ZONE (master) cible
        $master = SidUser::find($this->user_id);

        if (!$master) {
            $this->emit('error', 'COM ZONE introuvable.');
            return;
        }

        $isLier = ($this->comzoneDesabled == 0); // true = lier, false = détacher
        $updatedCount = 0;

        foreach ($this->selected_masters as $userId) {
            $user = SidUser::find($userId);
            if (!$user) continue;

            if ($isLier) {
                // Lier l'utilisateur au COM ZONE
                $user->update([
                    'com_zone_id' => $this->user_id,
                    'is_com_accepted' => true
                ]);
            } else {
                // Détacher l'utilisateur du COM ZONE
                $user->update([
                    'com_zone_id' => null,
                    'is_com_accepted' => false
                ]);
            }
            $updatedCount++;
        }

        // Notification au master (une seule fois avec le résumé)
        if ($updatedCount > 0) {
            $action = $isLier ? 'liés à' : 'détachés de';
            
            $master->notify(new UserNotification([
                'title' => 'Mise à jour des liaisons COM ZONE',
                'message' => $updatedCount . " utilisateur(s) ont été " . $action . " votre COM ZONE."
            ]));
        }

        // Fermeture et rafraîchissements
        $this->cancelConnectedField();
        $this->emit('modal-hide', 'hide');
        $this->emit('refreshPage');
        $this->emit('success', "{$updatedCount} utilisateur(s) traités avec succès.");
    }

    public function resetField()
    {
        $this->sidUserId = '';
        $this->sidUserStatus = '';
        $this->emit('modal-hide', 'hide');
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

    public function resetdesableField()
    {
        $this->emit('modal-hide', 'hide');
        // return redirect(request()->header('Referer'));
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

        $users = SidUser::query();

        if ($this->search != "") {

            $this->resetPage();

            $users->where('is_com_zone', true)
                ->orwhere("firstname", "LIKE",  "%" . $this->search . "%")
                ->orWhere("lastname", "LIKE",  "%" . $this->search . "%")
                ->orWhere("phone", "LIKE",  "%" . $this->search . "%")
                ->orWhere("email", "LIKE",  "%" . $this->search . "%")
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage);
        }

        if ($this->countriesFilter != "") {
            $users->where("country_id", $this->countriesFilter);
        }


        $users = $users->where('is_com_zone', true)
            ->with(['wallet.sidUser.country.currencie']) // Charge les relations nécessaires pour la conversion
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

        $mastersQuery = SidUser::query()
            ->where('is_distributer', false)
            ->where('is_accounting', false)
            ->where('is_supervisor', false)
            ->where('is_api', false)
            ->where('country_id', $this->user_country);

        // Filtre par recherche (nom, prénom, email)
        if (!empty($this->searchCustumers)) {
            $mastersQuery->where(function ($q) {
                $q->where('firstname', 'like', '%' . $this->searchCustumers . '%')
                    ->orWhere('lastname', 'like', '%' . $this->searchCustumers . '%')
                    ->orWhere('phone', 'like', '%' . $this->searchCustumers . '%')
                    ->orWhere('email', 'like', '%' . $this->searchCustumers . '%');
            });
        }

        $masters = $mastersQuery->paginate(15);

        return view('livewire.comzones', [
            "users" => $users,
            "countries" => Country::where('robus', true)->orderBy("name", 'desc')->get(),
            "operators" => Operator::whereStatus(true)->whereIsCom(true)->orderBy("name", 'desc')->get(),
            'masters' => $masters,

        ]);
    }
}
