<div>
    @php
        $visibleServices = $pixelServices->getCollection();
        $activeCount    = $visibleServices->where('status', true)->count();
        $cashInCount    = $visibleServices->where('operation_type', 'Cash In')->count();
        $cashOutCount   = $visibleServices->where('operation_type', 'Cash Out')->count();
        $selectedCountry = $filterCountryId !== '' ? $countries->firstWhere('id', (int) $filterCountryId) : null;
        $activeZone = $filterIsXof ? 'XOF' : ($filterIsXaf ? 'XAF' : ($filterIsCdf ? 'CDF' : ($filterIsGmd ? 'GMD' : null)));
    @endphp

    {{-- ── En-tête ──────────────────────────────────────────────────────── --}}
    <div class="intro-y mt-8">
        <div class="flex flex-col gap-4 rounded-lg border border-slate-200 bg-white px-5 py-5 shadow-sm lg:flex-row lg:items-center">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                    </svg>
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
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Services
                </a>
                <button class="btn btn-primary inline-flex items-center gap-2" wire:click="showCreateModal">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Ajouter
                </button>
            </div>
        </div>
    </div>

    {{-- ── Cartes stats ─────────────────────────────────────────────────── --}}
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
                <div class="text-xs font-semibold uppercase text-slate-400">Filtres actifs</div>
                <div class="mt-2 flex flex-wrap items-center gap-1.5">
                    @if($selectedCountry)
                        <span class="inline-flex items-center gap-1 rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary">
                            <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                            </svg>
                            {{ $selectedCountry->name }}
                        </span>
                    @endif
                    @if($activeZone === 'XOF')
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                            <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                            </svg>
                            Zone XOF
                        </span>
                    @elseif($activeZone === 'XAF')
                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">
                            <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                            </svg>
                            Zone XAF
                        </span>
                    @elseif($activeZone === 'CDF')
                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">
                            <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                            </svg>
                            Zone CDF
                        </span>
                    @elseif($activeZone === 'GMD')
                        <span class="inline-flex items-center gap-1 rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">
                            <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                            </svg>
                            Zone GMD
                        </span>
                    @endif
                    @if($search !== '')
                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                            <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                            "{{ Str::limit($search, 12) }}"
                        </span>
                    @endif
                    @if(!$selectedCountry && !$activeZone && $search === '')
                        <span class="text-sm font-medium text-slate-400">Aucun filtre</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── Barre de filtres ─────────────────────────────────────────────── --}}
    <div class="intro-y mt-5 rounded-lg border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-col gap-3 p-4 xl:flex-row xl:items-center">

            {{-- Recherche — wrapper flex pour alignement parfait icône/texte --}}
            <div class="flex flex-1 items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 transition-shadow focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/10">
                <svg class="h-4 w-4 shrink-0 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input
                    type="text"
                    wire:model.debounce.300ms="search"
                    class="h-10 flex-1 bg-transparent text-sm outline-none placeholder:text-slate-400"
                    placeholder="Nom, ID Pixel, provider ou pays…">
                @if($search !== '')
                    <button type="button" wire:click="$set('search', '')"
                        class="flex items-center text-slate-400 hover:text-slate-600">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                @endif
            </div>

            {{-- Filtre pays — wrapper flex identique à la recherche --}}
            <div class="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 transition-shadow focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/10 xl:w-52">
                <svg class="h-4 w-4 shrink-0 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                </svg>
                <select wire:model="filterCountryId" class="h-10 flex-1 bg-transparent text-sm outline-none cursor-pointer appearance-none">
                    <option value="">Tous les pays</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Groupe segmenté Tous / zones --}}
            <div class="flex flex-wrap overflow-hidden rounded-lg border border-slate-200">
                <button type="button" wire:click="resetFilters"
                    class="inline-flex h-10 items-center gap-1.5 border-r border-slate-200 px-4 text-xs font-semibold transition-colors
                        {{ !$filterIsXof && !$filterIsXaf && !$filterIsCdf && !$filterIsGmd ? 'bg-slate-800 text-blue-800' : 'bg-white text-slate-500 hover:bg-slate-50' }}">
                    <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>
                    </svg>
                    Tous
                </button>
                <button type="button" wire:click="$set('filterIsXof', {{ $filterIsXof ? 'false' : 'true' }})"
                    class="inline-flex h-10 items-center gap-1.5 border-r border-slate-200 px-4 text-xs font-semibold transition-colors
                        {{ $filterIsXof ? 'bg-emerald-500 text-blue-800' : 'bg-white text-slate-500 hover:bg-emerald-50 hover:text-emerald-700' }}">
                    <span class="h-2 w-2 rounded-full {{ $filterIsXof ? 'bg-white' : 'bg-emerald-400' }}"></span>
                    Zone XOF
                </button>
                <button type="button" wire:click="$set('filterIsXaf', {{ $filterIsXaf ? 'false' : 'true' }})"
                    class="inline-flex h-10 items-center gap-1.5 border-r border-slate-200 px-4 text-xs font-semibold transition-colors
                        {{ $filterIsXaf ? 'bg-blue-500 text-blue-800' : 'bg-white text-slate-500 hover:bg-blue-50 hover:text-blue-700' }}">
                    <span class="h-2 w-2 rounded-full {{ $filterIsXaf ? 'bg-white' : 'bg-blue-400' }}"></span>
                    Zone XAF
                </button>
                <button type="button" wire:click="$set('filterIsCdf', {{ $filterIsCdf ? 'false' : 'true' }})"
                    class="inline-flex h-10 items-center gap-1.5 border-r border-slate-200 px-4 text-xs font-semibold transition-colors
                        {{ $filterIsCdf ? 'bg-amber-500 text-blue-800' : 'bg-white text-slate-500 hover:bg-amber-50 hover:text-amber-700' }}">
                    <span class="h-2 w-2 rounded-full {{ $filterIsCdf ? 'bg-white' : 'bg-amber-400' }}"></span>
                    Zone CDF
                </button>
                <button type="button" wire:click="$set('filterIsGmd', {{ $filterIsGmd ? 'false' : 'true' }})"
                    class="inline-flex h-10 items-center gap-1.5 px-4 text-xs font-semibold transition-colors
                        {{ $filterIsGmd ? 'bg-rose-500 text-blue-800' : 'bg-white text-slate-500 hover:bg-rose-50 hover:text-rose-700' }}">
                    <span class="h-2 w-2 rounded-full {{ $filterIsGmd ? 'bg-white' : 'bg-rose-400' }}"></span>
                    Zone GMD
                </button>
            </div>

            @if($search !== '' || $filterCountryId !== '' || $filterIsXof || $filterIsXaf || $filterIsCdf || $filterIsGmd)
                <button type="button" wire:click="resetFilters"
                    class="btn btn-outline-secondary h-10 inline-flex shrink-0 items-center gap-2 text-xs">
                    <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.95"/>
                    </svg>
                    Tout effacer
                </button>
            @endif
        </div>

        {{-- Barre résumé filtres actifs --}}
        @if($search !== '' || $filterCountryId !== '' || $filterIsXof || $filterIsXaf || $filterIsCdf || $filterIsGmd)
            <div class="flex flex-wrap items-center gap-2 border-t border-slate-100 bg-slate-50 px-4 py-4">
                <span class="text-xs text-slate-400">Filtres actifs :</span>

                @if($search !== '')
                    <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs font-medium text-slate-600">
                        <svg class="h-3 w-3 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        "{{ Str::limit($search, 20) }}"
                        <button wire:click="$set('search', '')" class="ml-0.5 text-slate-400 hover:text-slate-600">
                            <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </span>
                @endif

                @if($selectedCountry)
                    <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs font-medium text-slate-600">
                        <svg class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        {{ $selectedCountry->name }}
                        <button wire:click="$set('filterCountryId', '')" class="ml-0.5 text-slate-400 hover:text-slate-600">
                            <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </span>
                @endif

                @if($filterIsXof)
                    <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Zone XOF
                        <button wire:click="$set('filterIsXof', false)" class="ml-0.5 text-emerald-400 hover:text-emerald-700">
                            <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </span>
                @endif

                @if($filterIsXaf)
                    <span class="inline-flex items-center gap-1 rounded-full border border-blue-200 bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                        Zone XAF
                        <button wire:click="$set('filterIsXaf', false)" class="ml-0.5 text-blue-400 hover:text-blue-700">
                            <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </span>
                @endif

                @if($filterIsCdf)
                    <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                        Zone CDF
                        <button wire:click="$set('filterIsCdf', false)" class="ml-0.5 text-amber-400 hover:text-amber-700">
                            <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </span>
                @endif

                @if($filterIsGmd)
                    <span class="inline-flex items-center gap-1 rounded-full border border-rose-200 bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                        Zone GMD
                        <button wire:click="$set('filterIsGmd', false)" class="ml-0.5 text-rose-400 hover:text-rose-700">
                            <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </span>
                @endif

                <span class="ml-auto text-xs text-slate-400">{{ $pixelServices->total() }} résultat(s)</span>
            </div>
        @endif

    </div>
    

    {{-- ── Tableau ───────────────────────────────────────────────────────── --}}
    <div class="intro-y mt-5 overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="overflow-auto">
            <table class="table table-hover min-w-[1060px]">
                <thead>
                    <tr class="bg-slate-50 text-xs uppercase text-slate-500">
                        <th class="whitespace-nowrap px-5 py-4 text-left">Service</th>
                        <th class="whitespace-nowrap px-5 py-4 text-left">Provider</th>
                        <th class="whitespace-nowrap px-5 py-4 text-left">Pays</th>
                        <th class="whitespace-nowrap px-5 py-4 text-center">Operation</th>
                        <th class="whitespace-nowrap px-5 py-4 text-center">Zone</th>
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
                                    <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="4" y1="9" x2="20" y2="9"/><line x1="4" y1="15" x2="20" y2="15"/><line x1="10" y1="3" x2="8" y2="21"/><line x1="16" y1="3" x2="14" y2="21"/>
                                    </svg>
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
                                        <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="17" y1="7" x2="7" y2="17"/><polyline points="17 17 7 17 7 7"/>
                                        </svg>
                                        Cash In
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-pending/10 px-3 py-1.5 text-xs font-semibold text-pending">
                                        <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/>
                                        </svg>
                                        Cash Out
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-center align-middle">
                                <div class="flex items-center justify-center gap-1">
                                    @if($item->is_xof)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">XOF</span>
                                    @endif
                                    @if($item->is_xaf)
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">XAF</span>
                                    @endif
                                    @if($item->is_cdf)
                                        <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">CDF</span>
                                    @endif
                                    @if($item->is_gmd)
                                        <span class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">GMD</span>
                                    @endif
                                    @if(!$item->is_xof && !$item->is_xaf && !$item->is_cdf && !$item->is_gmd)
                                        <span class="text-xs text-slate-300">—</span>
                                    @endif
                                </div>
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
                                        class="btn  btn-sm btn-outline-warning inline-flex h-12 w-12 items-center justify-center p-0"
                                        title="Modifier">
                                        <svg class="h-4 w-4  shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>
                                        </svg>
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="edit({{ $item->id }}, 'show-delete-pixel-innove-modal'); $emit('show-delete-pixel-innove-modal')"
                                        class="btn btn-sm btn-outline-danger inline-flex h-12 w-12 items-center justify-center p-0"
                                        title="Supprimer">
                                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-14 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                        <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
                                        </svg>
                                    </div>
                                    <div class="mt-4 font-semibold text-slate-700">Aucun service Pixel Innove</div>
                                    <div class="mt-1 text-sm text-slate-400">Aucun resultat ne correspond aux filtres actuels.</div>
                                    <button class="btn btn-primary btn-sm mt-5 inline-flex items-center gap-2" wire:click="showCreateModal">
                                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                                        </svg>
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
