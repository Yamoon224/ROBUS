<div>
    @php
        $visibleServices = $pixelServices->getCollection();
        $activeCount = $visibleServices->where('status', true)->count();
        $cashInCount = $visibleServices->where('operation_type', 'Cash In')->count();
        $cashOutCount = $visibleServices->where('operation_type', 'Cash Out')->count();
        $selectedCountry = $filterCountryId !== '' ? $countries->firstWhere('id', (int) $filterCountryId) : null;
    @endphp

    <div class="intro-y mt-8">
        <div class="flex flex-col gap-4 rounded-lg border border-slate-200 bg-white px-5 py-5 shadow-sm lg:flex-row lg:items-center">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                    <i data-feather="credit-card" class="h-5 w-5"></i>
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-xl font-semibold text-slate-800">Pixel Innove</h2>
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500">
                            {{ $pixelServices->total() }} service(s)
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-slate-500">Configuration des services Pixel Innov par pays, provider et type d'operation.</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 lg:ml-auto">
                <a class="btn btn-outline-secondary inline-flex items-center gap-2" href="{{ route('services.browse') }}">
                    <i data-feather="arrow-left" class="h-4 w-4"></i>
                    Services
                </a>
                <button class="btn btn-primary inline-flex items-center gap-2" wire:click="showCreateModal">
                    <i data-feather="plus" class="h-4 w-4"></i>
                    Ajouter
                </button>
            </div>
        </div>
    </div>

    <div class="intro-y mt-5 grid grid-cols-12 gap-4">
        <div class="col-span-12 sm:col-span-4 xl:col-span-3">
            <div class="rounded-lg border border-slate-200 bg-white px-4 py-3 shadow-sm">
                <div class="text-xs font-semibold uppercase text-slate-400">Actifs visibles</div>
                <div class="mt-2 flex items-end justify-between">
                    <span class="text-2xl font-semibold text-slate-800">{{ $activeCount }}</span>
                    <span class="rounded-full bg-success/10 px-2 py-1 text-xs font-semibold text-success">Actif</span>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-4 xl:col-span-3">
            <div class="rounded-lg border border-slate-200 bg-white px-4 py-3 shadow-sm">
                <div class="text-xs font-semibold uppercase text-slate-400">Cash In visibles</div>
                <div class="mt-2 flex items-end justify-between">
                    <span class="text-2xl font-semibold text-slate-800">{{ $cashInCount }}</span>
                    <span class="rounded-full bg-success/10 px-2 py-1 text-xs font-semibold text-success">Encaissement</span>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-4 xl:col-span-3">
            <div class="rounded-lg border border-slate-200 bg-white px-4 py-3 shadow-sm">
                <div class="text-xs font-semibold uppercase text-slate-400">Cash Out visibles</div>
                <div class="mt-2 flex items-end justify-between">
                    <span class="text-2xl font-semibold text-slate-800">{{ $cashOutCount }}</span>
                    <span class="rounded-full bg-pending/10 px-2 py-1 text-xs font-semibold text-pending">Decaissement</span>
                </div>
            </div>
        </div>
        <div class="col-span-12 xl:col-span-3">
            <div class="rounded-lg border border-slate-200 bg-white px-4 py-3 shadow-sm">
                <div class="text-xs font-semibold uppercase text-slate-400">Filtre pays</div>
                <div class="mt-2 truncate text-lg font-semibold text-slate-800">
                    {{ $selectedCountry->name ?? 'Tous les pays' }}
                </div>
            </div>
        </div>
    </div>

    <div class="intro-y mt-5 rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex flex-col gap-3 xl:flex-row xl:items-center">
            <div class="relative flex-1">
                <i data-feather="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                <input
                    type="text"
                    wire:model.debounce.300ms="search"
                    class="form-control h-11 w-full rounded-md border-slate-200 pl-10 pr-10 text-sm"
                    placeholder="Rechercher un nom, ID Pixel, provider ou pays">
                @if($search !== '')
                    <button
                        type="button"
                        wire:click="$set('search', '')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                        title="Effacer la recherche">
                        <i data-feather="x" class="h-4 w-4"></i>
                    </button>
                @endif
            </div>

            <div class="relative xl:w-72">
                <i data-feather="map-pin" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                <select wire:model="filterCountryId" class="form-select h-11 w-full rounded-md border-slate-200 pl-10 text-sm">
                    <option value="">Tous les pays</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>

            @if($search !== '' || $filterCountryId !== '')
                <button
                    type="button"
                    class="btn btn-outline-secondary h-11 inline-flex items-center gap-2"
                    wire:click="resetFilters">
                    <i data-feather="rotate-ccw" class="h-4 w-4"></i>
                    Reinitialiser
                </button>
            @endif
        </div>
    </div>

    <div class="intro-y mt-5 overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="overflow-auto">
            <table class="table table-hover min-w-[980px]">
                <thead>
                    <tr class="bg-slate-50 text-xs uppercase text-slate-500">
                        <th class="whitespace-nowrap px-5 py-4 text-left">Service</th>
                        <th class="whitespace-nowrap px-5 py-4 text-left">Provider</th>
                        <th class="whitespace-nowrap px-5 py-4 text-left">Pays</th>
                        <th class="whitespace-nowrap px-5 py-4 text-center">Operation</th>
                        <th class="whitespace-nowrap px-5 py-4 text-right">Frais fixe</th>
                        <th class="whitespace-nowrap px-5 py-4 text-right">Commission</th>
                        <th class="whitespace-nowrap px-5 py-4 text-center">Statut</th>
                        <th class="whitespace-nowrap px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pixelServices as $item)
                        <tr class="intro-x border-t border-slate-100">
                            <td class="px-5 py-4 align-middle">
                                <div class="font-semibold text-slate-800">{{ $item->name }}</div>
                                <div class="mt-1 inline-flex items-center gap-1 rounded bg-slate-100 px-2 py-1 font-mono text-xs text-slate-500">
                                    <i data-feather="hash" class="h-3 w-3"></i>
                                    {{ $item->service_id }}
                                </div>
                            </td>

                            <td class="px-5 py-4 align-middle">
                                <div class="font-medium text-slate-700">{{ $item->provider->name ?? '-' }}</div>
                                <div class="mt-1 text-xs text-slate-400">{{ $item->providerType->name ?? 'Type non defini' }}</div>
                            </td>

                            <td class="px-5 py-4 align-middle">
                                <div class="flex items-center gap-2 text-slate-700">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-semibold text-slate-500">
                                        {{ strtoupper(substr($item->country->code ?? $item->country->name ?? '--', 0, 2)) }}
                                    </span>
                                    <span class="text-sm font-medium">{{ $item->country->name ?? '-' }}</span>
                                </div>
                            </td>

                            <td class="px-5 py-4 text-center align-middle">
                                @if($item->operation_type === 'Cash In')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-success/10 px-3 py-1.5 text-xs font-semibold text-success">
                                        <i data-feather="arrow-down-left" class="h-3.5 w-3.5"></i>
                                        Cash In
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-pending/10 px-3 py-1.5 text-xs font-semibold text-pending">
                                        <i data-feather="arrow-up-right" class="h-3.5 w-3.5"></i>
                                        Cash Out
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-right align-middle text-sm text-slate-600">
                                {{ $item->fixed_fee !== null ? number_format((float) $item->fixed_fee, 2, ',', ' ') . ' %' : '-' }}
                            </td>

                            <td class="px-5 py-4 text-right align-middle">
                                <span class="font-semibold text-primary">{{ number_format((float) $item->commission_percent, 2, ',', ' ') }} %</span>
                            </td>

                            <td class="px-5 py-4 text-center align-middle">
                                <button
                                    type="button"
                                    wire:click="edit({{ $item->id }}, 'show-disabled-pixel-innove-modal'); $emit('show-disabled-pixel-innove-modal')"
                                    class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold transition-colors
                                        {{ $item->status ? 'bg-success/10 text-success hover:bg-success/20' : 'bg-danger/10 text-danger hover:bg-danger/20' }}"
                                    title="{{ $item->status ? 'Desactiver' : 'Activer' }}">
                                    <span class="h-2 w-2 rounded-full {{ $item->status ? 'bg-success' : 'bg-danger' }}"></span>
                                    {{ $item->status ? 'Actif' : 'Inactif' }}
                                </button>
                            </td>

                            <td class="px-5 py-4 text-right align-middle">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        type="button"
                                        wire:click="edit({{ $item->id }}, 'show-update-pixel-innove-modal'); $emit('show-update-pixel-innove-modal')"
                                        class="btn btn-sm btn-outline-warning inline-flex h-9 w-9 items-center justify-center p-0"
                                        title="Modifier">
                                        <i data-feather="edit-2" class="h-4 w-4"></i>
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="edit({{ $item->id }}, 'show-delete-pixel-innove-modal'); $emit('show-delete-pixel-innove-modal')"
                                        class="btn btn-sm btn-outline-danger inline-flex h-9 w-9 items-center justify-center p-0"
                                        title="Supprimer">
                                        <i data-feather="trash-2" class="h-4 w-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-14 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                        <i data-feather="inbox" class="h-6 w-6"></i>
                                    </div>
                                    <div class="mt-4 font-semibold text-slate-700">Aucun service Pixel Innove</div>
                                    <div class="mt-1 text-sm text-slate-400">Aucun resultat ne correspond aux filtres actuels.</div>
                                    <button class="btn btn-primary btn-sm mt-5 inline-flex items-center gap-2" wire:click="showCreateModal">
                                        <i data-feather="plus" class="h-4 w-4"></i>
                                        Ajouter un service
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-3 border-t border-slate-100 px-5 py-4 sm:flex-row sm:items-center">
            <div class="text-sm text-slate-500">
                Page {{ $pixelServices->currentPage() }} sur {{ $pixelServices->lastPage() }}
            </div>
            <nav class="sm:ml-auto">
                {{ $pixelServices->links() }}
            </nav>
        </div>
    </div>

    @include('livewire.pixel-innove.modals.create')
    @include('livewire.pixel-innove.modals.update')
    @include('livewire.pixel-innove.modals.disabled')
    @include('livewire.pixel-innove.modals.delete')
</div>
