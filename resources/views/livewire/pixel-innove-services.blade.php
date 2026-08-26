<div>
    {{-- En-tête --}}
    <div class="intro-y flex flex-col sm:flex-row sm:items-center mt-8 gap-3">
        <div>
            <h2 class="text-xl font-semibold text-slate-800">Pixel Innove</h2>
            <p class="text-slate-500 text-sm mt-0.5">Services liés aux pays, providers et types existants</p>
        </div>
        <div class="sm:ml-auto flex flex-wrap gap-2">
            <button class="btn btn-primary flex items-center gap-2" wire:click="showCreateModal">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Ajouter
            </button>
            <a class="btn btn-outline-secondary flex items-center gap-2" href="{{ route('services.browse') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Services
            </a>
        </div>
    </div>

    {{-- Barre de filtres --}}
    <div class="intro-y box px-4 py-3 mt-5 mb-4">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3">

            {{-- Recherche --}}
            <div class="flex items-center gap-2 flex-1 min-w-0 border border-slate-200 rounded-md px-3 bg-white h-10">
                <svg class="w-4 h-4 shrink-0 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <input type="text" wire:model.debounce.300ms="search"
                    class="border-0 outline-none bg-transparent w-full text-sm text-slate-700 placeholder-slate-400"
                    placeholder="Rechercher par nom, ID, provider…">
                @if($search !== '')
                    <button wire:click="$set('search', '')" class="shrink-0 text-slate-300 hover:text-slate-500 transition-colors">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                @endif
            </div>

            {{-- Filtre pays --}}
            <div class="flex items-center gap-2 border border-slate-200 rounded-md px-3 bg-white h-10 sm:w-56 shrink-0">
                <svg class="w-4 h-4 shrink-0 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <select wire:model="filterCountryId"
                    class="border-0 outline-none bg-transparent w-full text-sm text-slate-700 cursor-pointer">
                    <option value="">Tous les pays</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Compteur --}}
            <div class="flex items-center gap-2 shrink-0">
                <span class="text-slate-400 text-sm whitespace-nowrap">
                    <span class="font-semibold text-slate-600">{{ $pixelServices->total() }}</span> service(s)
                </span>
                @if($filterCountryId !== '')
                    <button wire:click="$set('filterCountryId', '')"
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-primary text-white text-xs font-medium hover:bg-primary/80 transition-colors">
                        {{ $countries->firstWhere('id', $filterCountryId)?->name ?? '' }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                @endif
            </div>

        </div>
    </div>

    {{-- Tableau --}}
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible mt-8">
        <table class="table table-hover -mt-2 ">
            <thead>
                <tr class="bg-slate-100 text-slate-600 text-xs uppercase tracking-wide">
                    <th class="text-center py-3 px-4">Nom / ID Pixel</th>
                    <th class="text-center py-3 px-4">Provider</th>
                    <th class="text-center py-3 px-4">Pays</th>
                    <th class="text-center py-3 px-4">Opération</th>
                    <th class="text-center py-3 px-4">Frais fixe (%)</th>
                    <th class="text-center py-3 px-4">Commission (%)</th>
                    <th class="text-center py-3 px-4">Statut</th>
                    <th class="text-center py-3 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pixelServices as $item)
                    <tr class="intro-x border-b border-slate-100 hover:bg-slate-50 transition-colors">

                        {{-- Nom / ID --}}
                        <td class="text-center align-middle py-3 px-4">
                            <div class="font-medium text-slate-700 text-sm">{{ $item->name }}</div>
                            <div class="text-slate-400 text-xs mt-0.5">
                                <span class="font-mono bg-slate-100 px-1.5 py-0.5 rounded">
                                    #{{ $item->service_id }}
                                </span>
                            </div>
                        </td>

                        {{-- Provider --}}
                        <td class="text-center align-middle py-3 px-4">
                            <div class="font-medium text-slate-700 text-sm">{{ $item->provider->name ?? '—' }}</div>
                            @if($item->providerType)
                                <span class="text-xs text-slate-400 mt-0.5 block">{{ $item->providerType->name }}</span>
                            @endif
                        </td>

                        {{-- Pays --}}
                        <td class="text-center align-middle py-3 px-4">
                            <span class="text-sm text-slate-700">{{ $item->country->name ?? '—' }}</span>
                        </td>

                        {{-- Opération --}}
                        <td class="text-center align-middle py-3 px-4">
                            @if($item->operation_type === 'Cash In')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-success/10 text-success">
                                    ↓ Cash In
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-pending/10 text-pending">
                                    ↑ Cash Out
                                </span>
                            @endif
                        </td>

                        {{-- Frais fixe --}}
                        <td class="text-center align-middle py-3 px-4 text-sm text-slate-600">
                            {{ $item->fixed_fee !== null ? $item->fixed_fee . '%' : '—' }}
                        </td>

                        {{-- Commission --}}
                        <td class="text-center align-middle py-3 px-4">
                            <span class="font-semibold text-primary text-sm">{{ $item->commission_percent }}%</span>
                        </td>

                        {{-- Statut --}}
                        <td class="text-center align-middle py-3 px-4">
                            <a wire:click="edit({{ $item->id }}, 'show-disabled-pixel-innove-modal'); $emit('show-disabled-pixel-innove-modal')"
                                href="javascript:;"
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer
                                    {{ $item->status ? 'bg-success/10 text-success hover:bg-success/20' : 'bg-danger/10 text-danger hover:bg-danger/20' }}
                                    transition-colors">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $item->status ? 'bg-success' : 'bg-danger' }}"></span>
                                {{ $item->status ? 'Actif' : 'Inactif' }}
                            </a>
                        </td>

                        {{-- Actions --}}
                        <td class="text-center align-middle py-3 px-4">
                            <div class="flex items-center justify-center gap-1">
                                <button
                                    wire:click="edit({{ $item->id }}, 'show-update-pixel-innove-modal'); $emit('show-update-pixel-innove-modal')"
                                    class="btn btn-sm btn-outline-warning py-1 px-2.5 text-xs">
                                    Modifier
                                </button>
                                <button
                                    wire:click="edit({{ $item->id }}, 'show-delete-pixel-innove-modal'); $emit('show-delete-pixel-innove-modal')"
                                    class="btn btn-sm btn-outline-danger py-1 px-2.5 text-xs">
                                    Supprimer
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-10">
                            <div class="flex flex-col items-center text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v6m-6 0h6"/>
                                </svg>
                                <span class="text-sm">Aucun service Pixel Innove pour le moment</span>
                                <button class="btn btn-primary btn-sm mt-4" wire:click="showCreateModal">Ajouter le premier</button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-5">
        <nav class="w-full sm:w-auto sm:mr-auto">
            {{ $pixelServices->links() }}
        </nav>
    </div>

    @include('livewire.pixel-innove.modals.create')
    @include('livewire.pixel-innove.modals.update')
    @include('livewire.pixel-innove.modals.disabled')
    @include('livewire.pixel-innove.modals.delete')
</div>
