<div>


    <div class="intro-y col-span-12 lg:col-span-12">
        {{-- Statistiques en 4 colonnes --}}
        <div class="grid grid-cols-12 gap-6 mt-5">
            {{-- Total visites --}}
            <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y style_user3">
                <div
                    class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 flex flex-col justify-between h-full">
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="p-2 bg-white/20 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <span
                                class="text-xs font-semibold text-blue-100 bg-white/20 px-2 py-1 rounded-full">Total</span>
                        </div>
                        <p class="text-4xl font-bold text-white mb-1">{{ number_format($statistics['total'], 0, ',', '
                            ') }}</p>
                        <p class="text-xs text-blue-100">visites enregistrées</p>
                    </div>
                    <div class="bg-white/10 px-5 py-2">
                        <p class="text-xs text-blue-100">Toutes périodes confondues</p>
                    </div>
                </div>
            </div>

            {{-- En attente --}}
            <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y style_user1">
                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 flex flex-col justify-between h-full">
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="p-2 bg-white/20 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-yellow-100 bg-white/20 px-2 py-1 rounded-full">En
                                attente</span>
                        </div>
                        <p class="text-4xl font-bold text-white mb-1">{{ number_format($statistics['pending'], 0, ',', '
                            ') }}</p>
                        <p class="text-xs text-yellow-100">visites à traiter</p>
                    </div>
                    <div class="bg-white/10 px-5 py-2">
                        <p class="text-xs text-yellow-100">Action requise</p>
                    </div>
                </div>
            </div>

            {{-- Confirmées --}}
            <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y style_table4">
                <div
                    class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 flex flex-col justify-between h-full">
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="p-2 bg-white/20 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span
                                class="text-xs font-semibold text-green-100 bg-white/20 px-2 py-1 rounded-full">Confirmées</span>
                        </div>
                        <p class="text-4xl font-bold text-white mb-1">{{ number_format($statistics['confirmed'], 0, ',',
                            ' ') }}</p>
                        <p class="text-xs text-green-100">visites validées</p>
                    </div>
                    <div class="bg-white/10 px-5 py-2">
                        <p class="text-xs text-green-100">Taux: {{ $statistics['total'] > 0 ?
                            round(($statistics['confirmed'] / $statistics['total']) * 100, 1) : 0 }}%</p>
                    </div>
                </div>
            </div>

            {{-- Rejetées --}}
            <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y style_facture_home3">
                <div
                    class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 flex flex-col justify-between h-full">
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="p-2 bg-white/20 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <span
                                class="text-xs font-semibold text-red-100 bg-white/20 px-2 py-1 rounded-full">Rejetées</span>
                        </div>
                        <p class="text-4xl font-bold text-white mb-1">{{ number_format($statistics['rejected'], 0, ',',
                            ' ') }}</p>
                        <p class="text-xs text-red-100">visites refusées</p>
                    </div>
                    <div class="bg-white/10 px-5 py-2">
                        <p class="text-xs text-red-100">Taux: {{ $statistics['total'] > 0 ?
                            round(($statistics['rejected'] / $statistics['total']) * 100, 1) : 0 }}%</p>
                    </div>
                </div>
            </div>
        </div>


        {{-- Filtres améliorés - Version corrigée en colonnes --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg mb-6 border mt-6 border-gray-200 dark:border-gray-700 overflow-hidden">
            <div
                class="px-6 py-3 bg-gradient-to-r from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Filtres avancés</h3>
                </div>
            </div>

            <div class="p-5">
                {{-- Première ligne de filtres - 5 colonnes --}}
                <div class="grid grid-cols-3 md:grid-cols-12 lg:grid-cols-5 gap-4">
                    {{-- Statut visite --}}
                    <div class="col-span-1">
                        <label class="block text-xs font-medium  mb-1">Statut
                            visite</label>
                        <select wire:model="statusFilter"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                            <option value="">📊 Tous les statuts</option>
                            <option value="pending">⏳ En attente</option>
                            <option value="confirmed">✅ Confirmé</option>
                            <option value="rejected">❌ Rejeté</option>
                        </select>
                    </div>

                    {{-- Statut utilisateur --}}
                    <div class="col-span-1">
                        <label class="block text-xs font-medium  mb-1">Statut
                            utilisateur</label>
                        <select wire:model="userStatus"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                            <option value="">👥 Tous les utilisateurs</option>
                            <option value="1">🟢 Actif</option>
                            <option value="0">🔴 Inactif</option>
                        </select>
                    </div>

                     {{-- Recherche --}}
                    <div class="col-span-1">
                        <label class="block text-xs font-medium  mb-1">Recherche</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" wire:model.debounce.300ms="search"
                                class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                placeholder="ID, nom, email...">
                        </div>
                    </div>
                    {{-- Date début --}}
                    <div class="col-span-1">
                        <label class="block text-xs font-medium  mb-1">Date
                            début</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <input type="date" wire:model="startDate"
                                class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                    </div>

                    {{-- Date fin --}}
                    <div class="col-span-1">
                        <label class="block text-xs font-medium  mb-1">Date fin</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <input type="date" wire:model="endDate"
                                class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                    </div>

                   
                    <div class="col-span-1">
                        <label class="block text-xs font-medium  mb-1">Afficher</label>
                        <select wire:model="perPage"
                            class="w-full md:w-40 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                            <option value="10">10 par page</option>
                            <option value="15">15 par page</option>
                            <option value="25">25 par page</option>
                            <option value="50">50 par page</option>
                            <option value="100">100 par page</option>
                        </select>
                    </div>

                </div>

                {{-- Deuxième ligne : Nombre par page et bouton réinitialiser --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-200">


                    <div class="col-span-1 flex justify-end items-end">
                        <button wire:click="resetFilters"
                            class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Réinitialiser les filtres
                        </button>
                    </div>
                </div>

                {{-- Affichage des filtres actifs sous forme de badges --}}
                @if($statusFilter || $userStatus || $startDate || $endDate || $search)
                <div class="flex flex-wrap gap-2 mt-4 pt-3 border-t border-gray-200">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Filtres actifs :</span>

                    @if($statusFilter)
                    <span
                        class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-primary/10 text-primary rounded-full">
                        Statut: {{ $statusFilter == 'pending' ? 'En attente' : ($statusFilter == 'confirmed' ?
                        'Confirmé' : 'Rejeté') }}
                        <button wire:click="$set('statusFilter', '')" class="hover:text-primary-dark">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </span>
                    @endif

                    @if($userStatus)
                    <span
                        class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-primary/10 text-primary rounded-full">
                        Utilisateur: {{ $userStatus == '1' ? 'Actif' : 'Inactif' }}
                        <button wire:click="$set('userStatus', '')" class="hover:text-primary-dark">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </span>
                    @endif

                    @if($startDate)
                    <span
                        class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-primary/10 text-primary rounded-full">
                        Du: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
                        <button wire:click="$set('startDate', '')" class="hover:text-primary-dark">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </span>
                    @endif

                    @if($endDate)
                    <span
                        class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-primary/10 text-primary rounded-full">
                        Au: {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                        <button wire:click="$set('endDate', '')" class="hover:text-primary-dark">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </span>
                    @endif

                    @if($search)
                    <span
                        class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-primary/10 text-primary rounded-full">
                        Recherche: {{ $search }}
                        <button wire:click="$set('search', '')" class="hover:text-primary-dark">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </span>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>


    {{-- Tableau des visites amélioré avec modal --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        {{-- En-tête avec statistiques rapides --}}
        <div
            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-primary/10 rounded-xl">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                            Liste des visites
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Gestion des visites terrain</p>
                    </div>
                    <div class="flex items-center gap-1 ml-2">
                        <span class="px-2.5 py-1 text-xs font-bold bg-primary/10 text-primary rounded-full">
                            Total: {{ $total }}
                        </span>
                    </div>
                </div>

            </div>
        </div>

        {{-- Tableau avec design amélioré --}}
        <div class="overflow-x-auto">
            <table class="table table-bordered table-hover -mt-2 -intro-x ">
                <thead class="table-dark border-b-2 border-gray-200 ">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold  uppercase tracking-wider cursor-pointer hover:text-primary transition-colors"
                            wire:click="setSortField('id')">
                            <div class="flex items-center gap-1">
                                ID
                                @if($sortField == 'id')
                                <span class="text-primary">{{ $sortAsc ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold  uppercase tracking-wider cursor-pointer hover:text-primary transition-colors"
                            wire:click="setSortField('user_name')">
                            <div class="flex items-center gap-1">
                                COM ZONE
                                @if($sortField == 'user_name')
                                <span class="text-primary">{{ $sortAsc ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold  uppercase tracking-wider">Localisation</th>
                        <th class="px-6 py-3 text-left text-xs font-bold  uppercase tracking-wider cursor-pointer hover:text-primary transition-colors"
                            wire:click="setSortField('status')">
                            <div class="flex items-center gap-1">
                                Statut
                                @if($sortField == 'status')
                                <span class="text-primary">{{ $sortAsc ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold  uppercase tracking-wider cursor-pointer hover:text-primary transition-colors"
                            wire:click="setSortField('distance_m')">
                            <div class="flex items-center gap-1">
                                Distance
                                @if($sortField == 'distance_m')
                                <span class="text-primary">{{ $sortAsc ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold  uppercase tracking-wider cursor-pointer hover:text-primary transition-colors"
                            wire:click="setSortField('created_at')">
                            <div class="flex items-center gap-1">
                                Date création
                                @if($sortField == 'created_at')
                                <span class="text-primary">{{ $sortAsc ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-bold  uppercase tracking-wider">Visité</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($visites as $visite)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-all duration-200 group">
                        {{-- ID --}}
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center gap-1 px-2 py-1 text-xs font-mono font-semibold bg-gray-100 dark:bg-gray-700 rounded-lg">
                                #{{ $visite->code_visit }}
                            </span>
                        </td>

                        {{-- Utilisateur --}}
                        <td class="px-6 py-4">

                            <div class="user-badges-container">
                                @if ($visite->sidUser?->is_under_distributer == 1)
                                <div class="user-badge user-badge--distributor">
                                    Sous-distributeur
                                </div>
                                @elseif ($visite->sidUser?->is_custumers == 1)
                                <div class="user-badge user-badge--customer">
                                    Client
                                </div>
                                @elseif ($visite->sidUser?->is_company == 1)
                                <div class="user-badge user-badge--compagnie">
                                    Société
                                </div>
                                @elseif ($visite->sidUser?->is_master == 1)
                                <div class="user-badge user-badge--compagnie">
                                    Masteur
                                </div>
                                @elseif ($visite->sidUser?->is_api == 1)
                                <div class="user-badge user-badge--compagnie">
                                    Intégrateur API
                                </div>
                                @elseif ($visite->sidUser?->is_com_zone == 1)
                                <div class="user-badge user-badge--compagnie">
                                    COM ZONE
                                </div>
                                @else
                                <div class="user-badge user-badge--admin">
                                    Administrateur
                                </div>
                                @endif

                                <!-- Numéro de téléphone -->
                                <div class="user-phone">
                                    {{ $visite->sidUser?->phone ?? 'Non renseigné' }}
                                </div>
                            </div>

                            <!-- Nom complet -->
                            <div class="user-name">
                                {{ $visite->sidUser?->firstname ?? '' }}
                                {{ $visite->sidUser?->lastname ?? '' }}
                            </div>

                            <!-- Localisation -->
                            <div class="user-location">
                                {{ $visite->sidUser?->country->name ?? 'Pays inconnu' }}
                                •
                                {{ $visite->sidUser?->city->name ?? 'Ville inconnue' }}
                            </div>

                        </td>

                        {{-- Localisation --}}
                        <td class="px-6 py-4">
                            @if($visite->latitude && $visite->longitude)
                            <a href="https://www.google.com/maps?q={{ $visite->latitude }},{{ $visite->longitude }}"
                                target="_blank"
                                class="inline-flex items-center gap-1.5 px-2 py-1 text-primary hover:bg-primary/10 rounded-lg transition-colors text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Voir carte
                            </a>
                            @else
                            <span class="text-gray-400 text-sm italic">Non renseigné</span>
                            @endif
                        </td>

                        {{-- Statut --}}
                        <td class="px-6 py-4">

                            @if($visite->status == 'pending')
                            <span
                                class="inline-flex items-center gap-1.5 bg-pending px-2.5 py-1.5 text-xs font-semibold text-white  rounded-lg">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></span>
                                En attente
                            </span>
                            @elseif($visite->status == 'confirmed')
                            <span
                                class="inline-flex items-center  bg-success gap-1.5 px-2.5 py-1.5 text-xs font-semibold text-white rounded-lg">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Confirmé
                            </span>
                            @else
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-semibold text-red-800 bg-red-100 rounded-lg">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Rejeté
                            </span>
                            @endif
                        </td>

                        {{-- Distance --}}
                        <td class="px-6 py-4">
                            @if($visite->distance_m)
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                <span class="font-mono font-medium">{{ number_format($visite->distance_m, 0, ',', ' ')
                                    }} m</span>
                            </div>
                            @else
                            <span class="text-gray-400">—</span>
                            @endif
                        </td>

                        {{-- Date --}}
                        <td class="px-6 py-4">
                            <div class="transaction-alert">
                                <div class="transaction-alert__content">
                                    <div class="transaction-alert__icon">
                                        <svg fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <span class="transaction-alert__text">Date de création</span>
                                </div>
                                <div class="transaction-alert__date">
                                    <span class="transaction-alert__date-day">
                                        {{ $visite->created_at ? $visite->created_at->format('d/m/Y') : 'N/A' }}
                                    </span>
                                    <span class="text-xs text-gray-400">
                                        {{ $visite->created_at ? $visite->created_at->format('H:i:s') : '' }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4">
                             @include('components.infoUserCom')
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center gap-4">
                                <div
                                    class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-lg font-medium text-gray-900 dark:text-gray-100">Aucune visite
                                        trouvée</p>
                                    <p class="text-sm text-gray-500 mt-1">Aucune visite ne correspond à vos critères</p>
                                </div>
                                <button wire:click="resetFilters"
                                    class="mt-2 px-4 py-2 text-sm font-medium text-primary bg-primary/10 hover:bg-primary/20 rounded-lg transition-colors">
                                    Réinitialiser les filtres
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination avec info --}}
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Affichage de <span class="font-semibold">{{ $visites->firstItem() ?? 0 }}</span>
                    à <span class="font-semibold">{{ $visites->lastItem() ?? 0 }}</span>
                    sur <span class="font-semibold">{{ $visites->total() }}</span> résultats
                </div>
                <div>
                    {{ $visites->links() }}
                </div>
            </div>
        </div>
    </div>

</div>