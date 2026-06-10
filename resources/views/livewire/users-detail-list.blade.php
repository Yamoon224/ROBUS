<div>
    {{-- Barre de filtres améliorée --}}

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-6 border border-gray-200 dark:border-gray-700 p-5">
        {{-- Titre avec bouton réinitialiser --}}
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2">
                <div class="p-1.5 bg-primary/10 rounded-lg">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Filtres</h3>
            </div>
            <button wire:click="resetFilters"
                class="text-xs text-gray-500 hover:text-primary transition-colors flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
                Tout effacer
            </button>
        </div>

        {{-- Filtres sur une seule ligne horizontale --}}
        <div class="flex flex-wrap items-end gap-4">
            {{-- Statut compte --}}
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                    Statut compte
                </label>
                <select wire:model="status"
                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary focus:border-primary bg-white dark:bg-gray-800">
                    <option value="">Tous</option>
                    <option value="1">Actif</option>
                    <option value="0">Inactif</option>
                </select>
            </div>

            {{-- Statut KYC --}}
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                    Statut KYC
                </label>
                <select wire:model="kycStatus"
                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary focus:border-primary bg-white dark:bg-gray-800">
                    <option value="">Tous</option>
                    <option value="1">Validé</option>
                    <option value="0">En attente</option>
                </select>
            </div>

            {{-- Recherche --}}
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                    Recherche
                </label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text"
                        class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary focus:border-primary bg-white dark:bg-gray-800"
                        wire:model.debounce.300ms="search" placeholder="Nom, email, téléphone...">
                </div>
            </div>
        </div>

        {{-- Badges des filtres actifs --}}
        <div class="flex flex-wrap gap-2 mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
            @if($status)
            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-primary/10 text-primary rounded-full">
                Statut: {{ $status == '1' ? 'Actif' : 'Inactif' }}
                <button wire:click="$set('status', '')" class="hover:text-primary-dark">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </span>
            @endif

            @if($kycStatus)
            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-primary/10 text-primary rounded-full">
                KYC: {{ $kycStatus == '1' ? 'Validé' : 'En attente' }}
                <button wire:click="$set('kycStatus', '')" class="hover:text-primary-dark">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </span>
            @endif

            @if($search)
            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-primary/10 text-primary rounded-full">
                Recherche: {{ $search }}
                <button wire:click="$set('search', '')" class="hover:text-primary-dark">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </span>
            @endif
        </div>
    </div>
    {{-- Tableau des utilisateurs --}}
    <div class="card">
        <div
            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 rounded-t-lg">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-primary/10 rounded-lg">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-gray-200">
                        {{ $title }}
                        <span class="ml-2 px-2.5 py-0.5 text-xs font-medium bg-primary/10 text-primary rounded-full">
                            {{ $total }}
                        </span>
                    </h3>
                </div>
                <button type="button"
                    class="p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                    onclick="this.closest('.bg-white').querySelector('.card-body').classList.toggle('hidden')">
                    <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">Prénom(s) & nom</th>
                            <th class="text-center">Email & Solde</th>
                            <th class="text-center">Status compte & doc</th>
                            <th class="text-center">Status Wallet</th>
                            <th class="text-center">Statut utilisateur</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $item)
                        <tr class="intro-x">
                            <td class="w-60">
                                <div class="mb-3">
                                    @include('components.infoUserDetails', ['user' => $item])
                                </div>
                                @include('components.infoDetailsUser', ['user' => $item])
                            </td>


                            <td class="w-50">
                                @include('components.solde_color')


                                <div class="text-slate-300 text-xs w-60  mt-0.5 mb-2 mt-3">
                                    <span
                                        class="alert alert-success  inline-flex items-center text-white justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">

                                        Forfait : {{ $item->company->percent->name ?? null }}
                                    </span>

                                </div>
                            </td>

                            <td class="w-48 px-4 py-3">
                                <div class="flex flex-col space-y-3">
                                    {{-- Badge Statut du compte --}}
                                    @if ($item->is_active == '1')
                                    <div
                                        class="inline-flex items-center px-3 py-1.5 rounded-full bg-success border border-green-200 text-green-700 text-sm font-medium shadow-sm">
                                        <i class="fas fa-check-circle text-green-500 mr-2 text-success"></i>
                                        <span class="text-white">Compte activé</span>
                                    </div>
                                    @else
                                    <div
                                        class="inline-flex items-center px-3 py-1.5 rounded-full bg-pending border border-red-200 text-red-700 text-sm font-medium shadow-sm">
                                        <i class="fas fa-ban text-red-500 mr-2 text-base"></i>
                                        <span class="text-white">Compte désactivé</span>
                                    </div>
                                    @endif

                                    {{-- Badge Statut KYC --}}
                                    @if ($item->kyc && $item->kyc->status == '1')
                                    <div
                                        class="inline-flex items-center px-3 py-1.5 rounded-full bg-success border border-emerald-200 text-emerald-700 text-sm font-medium shadow-sm">
                                        <i class="fas fa-shield-check text-success mr-2 text-base"></i>
                                        <span class="text-white">Documents validés</span>
                                    </div>
                                    @else
                                    <div
                                        class="inline-flex items-center px-3 py-1.5 rounded-full bg-pending border border-amber-200 text-amber-700 text-sm font-medium shadow-sm">
                                        <i class="fas fa-clock text-amber-500 mr-2 text-white"></i>
                                        <span class="text-white">Documents en attente</span>
                                    </div>
                                    @endif
                                </div>
                            </td>

                            <td class="w-40">
                                @if ($item->status == '1')
                                @if (Auth::guard('web')->user()->hasPermissionTo('active-clients'))
                                <div class="flex items-center justify-center">
                                    <a class="flex items-center mr-3 text-success" href="#"
                                        data-message="Etes vous sûr de vouloir effectuer cette action ?"
                                        onclick="promptAlert(this)">
                                        <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Activé
                                    </a>
                                </div>
                                @endif
                                @else
                                @if (Auth::guard('web')->user()->hasPermissionTo('desactive-client'))
                                <div class="flex items-center justify-center">
                                    <a class="flex items-center mr-3 text-danger" href="#"
                                        data-message="Etes vous sûr de vouloir effectuer cette action ?"
                                        onclick="promptAlert(this)">
                                        <i class="fas fa-lock w-4 h-4 mr-2"></i> Désactivé
                                    </a>
                                </div>
                                @endif
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($item->status == '1')
                                <span class="badge bg-success">Utilisateur actif</span>
                                @else
                                <span class="badge bg-danger">Utilisateur inactif</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <a class="btn btn-sm btn-info" href="{{ route($routePrefix . '.show', $item->id) }}">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="alert alert-info mb-0">
                                    Aucun {{ strtolower($title) }} trouvé
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted small">
                    Affichage de {{ $users->firstItem() ?? 0 }} à {{ $users->lastItem() ?? 0 }}
                    sur {{ $total }} résultat(s)
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>