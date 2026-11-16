<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ProviderType;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\CountryService;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Support\ZoneCountrySwitch;

class Service extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $search = "";
    public $providerId;
    public $providerStatus;
    public $providerOrder;
    public $provider_amount;
    public $providerName;
    protected $paginationTheme = 'bootstrap';
    public $providerDescription;
    public $providerType;
    public $countrys = [];
    public $image;
    public $currentLogo;
    public $managementCountryId = '';
    public $managementProviderTypeId = '';

    protected $rules = [
        'providerName' => 'required',
        'providerDescription' => 'required',
        'countrys' => 'required',
        'providerType'  => 'required',
    ];

    protected $messages = [
        'providerName.required' => 'Ce champ est requis',
        'providerDescription.required' => 'Ce champ est requis',
        'countrys.required' => 'Ce champ est requis',
        'providerType.required' => 'Ce champ est requis',
    ];



    public function edit($id, $state)
    {
        $check = Provider::whereId($id)->first();
        $country = Country::whereId($check->country_id)->first();
        $providerType = ProviderType::whereId($check->provider_type_id)->first();
        // $old_countries = $kits->category_products->pluck('category_id')->toArray();

        $this->providerId = $id;
        $this->providerName = $check->name;
        $this->providerDescription = $check->description;
        // $this->image = $check->image;
        $this->currentLogo = $check->logo;
        $this->providerStatus = $check->status;
        $this->providerOrder = $check->is_order;
        $this->provider_amount = $check->amount ?? null;
        $this->countrys  = $check->country_services->pluck('country_id')->toArray() ?? null;
        $this->providerType = $providerType->id ?? null;
        $this->emit($state);
    }

    public function update()
    {
        $this->validate();
        $check = Provider::whereId($this->providerId)->first();
        // $country = Country::whereId($this->country)->first();
        $providerType = ProviderType::whereId($this->providerType)->first();
        $provider = $check->update([
            'name' => $this->providerName,
            'description' => $this->providerDescription,
            'status' => 1,
            'company_id' => Auth::guard('web')->user()->company->id,
            'created_by' => Auth::guard('web')->user()->id,
            // 'country_id' =>  $country->id,
            'amount' =>  $this->provider_amount ?? null,
            'provider_type_id'   =>  $providerType->id,
        ]);

        $provider->country_services()->delete();

        foreach ($this->countrys as $key => $country_s_id) {

            $country_service = new CountryService;
            $country_service->provider_id = $provider->id;
            $country_service->country_id = $country_s_id;
            $root_countrie = Country::findOrFail($country_s_id);

            $country_service->save();

            $root_countrie->save();
        }

        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Operateur  modifié !');
    }

    public function resetField()
    {
        $this->providerName = '';
        $this->providerDescription = '';
        $this->countrys = '';
        $this->image = '';
        $this->providerType = '';
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = Provider::find($this->providerId);
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);

            Artisan::call('cache:clear');

            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Operateur activée !' : 'Operateur désactivée !');
        }
    }

    // Cible de l'activation : clients, sous-distributeurs ou les deux.
    public $managementTarget = 'both';

    // Types d'opérateurs qui ne sont pas des services : exclus du sélecteur de groupe
    // (Bank deposit, Bank send x2, Bank send inter, Crédit tél. inter, Djamo, Djamo inter,
    // Facture inter, Mobile money, Opérations bancaires, Push, Push inter, Rechargement carte inter,
    // Trésor money, Trésor money inter, Versement bancaire inter, Wave UV).
    // 26 = « Xbet inter » : masqué, il suit le groupe 1XBET (13) pour le pays choisi.
    private const HIDDEN_GROUP_TYPE_IDS = [12, 19, 27, 28, 9, 25, 22, 7, 3, 24, 21, 11, 23, 20, 10, 17, 26];

    // Groupe du sélecteur => types d'opérateurs réellement modifiés.
    // 5  = Rechargement de carte : l'app y affiche aussi Push (24) et Djamo (25).
    // 8  = Transfert international : l'app y regroupe factures (7), crédit tél. (9), versement (10),
    //      rechargement de carte (11) et Bank send (28) inter.
    private const GROUP_EXTRA_TYPES = [
        13 => [13, 26],
        5 => [5, 24, 25],
        8 => [8, 7, 9, 10, 11, 28],
    ];


    // Noms affichés dans le sélecteur, alignés sur les tuiles de l'app :
    // « Dépôt » = Mobile Money (section « Transfert national » de l'app),
    // « Transfert international » = Mobile Money inter.
    private const GROUP_LABELS = [
        1 => 'Dépôt',
        8 => 'Transfert international',
    ];

    private function targetColumns(?string $target = null): array
    {
        return match ($target ?? $this->managementTarget) {
            'client' => ['status'],
            'sub_distributor' => ['status_sub_distributor'],
            default => ['status', 'status_sub_distributor'],
        };
    }

    private function targetPayload(bool $status, ?string $target = null): array
    {
        return array_merge(
            array_fill_keys($this->targetColumns($target), $status),
            ['updated_at' => now()]
        );
    }

    private function applyZone(string $zone, bool $status): void
    {
        $columns = $this->targetColumns();
        if (in_array('status', $columns, true)) {
            ZoneCountrySwitch::set($zone, (int) $this->managementCountryId, $status, false);
        }
        if (in_array('status_sub_distributor', $columns, true)) {
            ZoneCountrySwitch::set($zone, (int) $this->managementCountryId, $status, true);
        }
    }

    private function targetLabel(?string $target = null): string
    {
        return match ($target ?? $this->managementTarget) {
            'client' => 'les clients',
            'sub_distributor' => 'les sous-distributeurs',
            default => 'les clients et sous-distributeurs',
        };
    }

    public function setCountryServiceStatus(int $countryServiceId, bool $status, string $target = 'client'): void
    {
        $this->authorizeActivationManagement();
        abort_unless(in_array($target, ['client', 'sub_distributor'], true), 422);

        // save() (et non un update de masse) pour garder la logique Paystack / Pixel Innov côté clients.
        CountryService::findOrFail($countryServiceId)
            ->forceFill(array_fill_keys($this->targetColumns($target), $status))
            ->save();
        Artisan::call('cache:clear');

        $this->emit('success', ($status ? 'Méthode activée' : 'Méthode désactivée') . ' pour ' . $this->targetLabel($target) . ' !');
    }

    public function setGroupStatus(bool $status): void
    {
        $this->authorizeActivationManagement();
        $this->validate([
            'managementCountryId' => 'required|exists:countries,id',
            'managementProviderTypeId' => 'required|exists:provider_types,id',
            'managementTarget' => 'required|in:client,sub_distributor,both',
        ]);

        $zone = ZoneCountrySwitch::zoneForTypeName(
            ProviderType::whereKey($this->managementProviderTypeId)->value('name')
        );

        if ($zone) {
            $this->applyZone($zone, $status);
            Artisan::call('cache:clear');
            $this->emit('success', ($status ? 'Groupe activé' : 'Groupe désactivé') . ' pour ' . $this->targetLabel() . ' !');

            return;
        }

        DB::table('country_services')
            ->where('country_id', $this->managementCountryId)
            ->whereIn('provider_id', function ($query) {
                $query->select('id')->from('providers')
                    ->whereIn('provider_type_id', self::GROUP_EXTRA_TYPES[(int) $this->managementProviderTypeId] ?? [(int) $this->managementProviderTypeId])
                    ->whereNull('deleted_at');
            })
            ->update($this->targetPayload($status));

        \App\Support\UvPaymentSwitch::enforce();
        Artisan::call('cache:clear');
        $this->emit('success', ($status ? 'Groupe activé' : 'Groupe désactivé') . ' pour ' . $this->targetLabel() . ' !');
    }

    public function setCountryStatus(bool $status): void
    {
        $this->authorizeActivationManagement();
        $this->validate([
            'managementCountryId' => 'required|exists:countries,id',
            'managementTarget' => 'required|in:client,sub_distributor,both',
        ]);

        DB::table('country_services')
            ->where('country_id', $this->managementCountryId)
            ->update($this->targetPayload($status));

        foreach ([ZoneCountrySwitch::INTERNATIONAL, ZoneCountrySwitch::DEPOSIT] as $zone) {
            $this->applyZone($zone, $status);
        }

        \App\Support\UvPaymentSwitch::enforce();
        Artisan::call('cache:clear');
        $this->emit('success', ($status ? 'Toutes les méthodes du pays sont activées' : 'Toutes les méthodes du pays sont désactivées') . ' pour ' . $this->targetLabel() . ' !');
    }

    private function authorizeActivationManagement(): void
    {
        abort_unless(Auth::guard('web')->user()->hasPermissionTo('active-operateurs'), 403);
    }

    public function disableOrder()
    {
        $check = Provider::find($this->providerId);
        if (!empty($check)) {
            if ($check->is_order == 0) $check->update(['is_order' => 1]);
            else $check->update(['is_order' => 0]);
            Artisan::call('cache:clear');

            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'Commande de carte activée !' : 'Commande de carte désactivée !');
        }
    }

    public function delete()
    {
        $check = Provider::find($this->providerId);
        if (!empty($check)) {
            $check->destroy($this->providerId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Operateur supprimée !');
        }
    }

    public function render()
    {
        $provider = Provider::with(['providerType', 'country_services.country']);


        if ($this->search != "") {

            $this->resetPage();
            $provider->orwhereHas(
                'providerType',
                function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                }
            )
                ->orWhere("name", "like",  "%" . $this->search . "%");
        }

        return view('livewire.service', [
            "providers" => $provider->orderBy('name', 'asc')->latest()->paginate(10),
            "countries" => Country::where('robus', true)->orderBy('name', 'asc')->get(),
            "providerTypes" => ProviderType::where('status', true)
                ->whereNotIn('id', self::HIDDEN_GROUP_TYPE_IDS)
                ->get()
                ->each(fn ($type) => $type->name = self::GROUP_LABELS[$type->id] ?? $type->name)
                ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
                ->values(),
        ]);
    }
}
