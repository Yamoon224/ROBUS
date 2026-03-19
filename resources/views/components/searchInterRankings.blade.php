<div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 w-full lg:w-auto">
    <div class="flex flex-col sm:flex-row gap-4 items-center">
        <!-- Filtre période -->
        <div class="relative flex-1 min-w-[180px]">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <select wire:model="periode"
                class="pl-10 pr-4 py-3 w-full border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all duration-200 appearance-none cursor-pointer">
                <option value="today">📅 Aujourd'hui</option>
                <option value="week">📊 Cette semaine</option>
                <option value="month">📈 Ce mois</option>
                <option value="year">🎯 Cette année</option>
                <option value="all">🌍 Toutes les périodes</option>
            </select>
        </div>

        <!-- Filtre service - Version sécurisée avec vérification -->
        <div class="relative flex-1 min-w-[180px]">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <select wire:model="service"
                class="pl-10 pr-4 py-3 w-full border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all duration-200 appearance-none cursor-pointer">
                <option value="">🔧 Tous les services</option>
                
                @if(isset($providers) && $providers->count() > 0)
                    @php
                        // Vérifier que la relation providerType existe
                        $groupedProviders = $providers->groupBy(function($provider) {
                            return $provider->providerType?->name ?? 'Autres services';
                        });
                        
                        // Trier les groupes par clé
                        $groupedProviders = $groupedProviders->sortKeys();
                    @endphp
                    
                    @foreach($groupedProviders as $typeName => $providersInType)
                        @if($typeName && $providersInType->count() > 0)
                            <optgroup label="📌 {{ $typeName }} ({{ $providersInType->count() }})">
                                @foreach($providersInType as $provider)
                                    <option value="{{ $provider->id }}" 
                                        {{ $service == $provider->id ? 'selected' : '' }}>
                                        {{ $provider->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                    @endforeach
                @else
                    <option value="" disabled>Aucun service disponible</option>
                @endif
            </select>
        </div>

        <!-- Filtre pays -->
        <div class="relative flex-1 min-w-[180px]">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064" />
                </svg>
            </div>
            <select wire:model="country"
                class="pl-10 pr-4 py-3 w-full border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all duration-200 appearance-none cursor-pointer">
                <option value="">🌐 Tous les pays</option>
                @if(isset($countries) && $countries->count() > 0)
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <!-- Bouton de réinitialisation -->
        <button wire:click="resetFilters" 
            class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-all duration-200 flex items-center gap-2">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Réinitialiser
        </button>
    </div>
</div>