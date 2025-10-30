{{-- <div class="space-y-10">
    @foreach($classment_transactions as $country => $subDistributors)
        <div>
            <h2 class="text-2xl font-semibold text-blue-700 mb-3 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .843-3 1.875S10.343 11.75 12 11.75 15 10.907 15 9.875 13.657 8 12 8zM12 14.25c-2.5 0-4.75 1.25-4.75 3V19h9.5v-1.75c0-1.75-2.25-3-4.75-3z" />
                </svg>
                {{ $country }}
            </h2>

            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                <table class="min-w-full text-sm text-left text-gray-700">
                    <thead class="bg-blue-600 text-white text-xs uppercase">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Sous-Distributeur</th>
                            <th class="px-4 py-3">Pays</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Balance</th>
                            <th class="px-4 py-3">Service</th>
                            <th class="px-4 py-3 text-right">Montant Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subDistributors as $index => $item)
                            <tr class="{{ $loop->even ? 'bg-gray-100' : 'bg-white' }} hover:bg-blue-50 transition">
                                <td class="px-4 py-2 font-medium text-gray-800">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 font-semibold text-gray-900">{{ $item['sub_distributor'] }}</td>
                                <td class="px-4 py-2">{{ $item['sub_countrie'] }}</td>
                                <td class="px-4 py-2">{{ $item['sub_email'] }}</td>
                                <td class="px-4 py-2">{{ number_format($item['wallet_balance'], 0, ',', ' ') }} FCFA</td>
                                <td class="px-4 py-2">{{ $item['provider'] }}</td>
                                <td class="px-4 py-2 text-right font-semibold text-blue-700">
                                    {{ number_format($item['total_amount'], 0, ',', ' ') }}  {{ $item['currency'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div> --}}

<div class="p-6 space-y-6">
    <!-- En-tête avec filtres -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Classement des Sous-Distributeurs</h1>
            <p class="text-gray-600 mt-1">Performance par service et par pays</p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <!-- Filtre période -->
            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                <option>Aujourd'hui</option>
                <option>Cette semaine</option>
                <option>Ce mois</option>
                <option>Cette année</option>
            </select>
            
            <!-- Filtre service -->
            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                <option>Tous les services</option>
                <option>Service Mobile</option>
                <option>Service Internet</option>
                <option>Service TV</option>
            </select>
        </div>
    </div>

    @foreach($classment_transactions as $country => $subDistributors)
    <!-- Carte par pays -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
        <!-- En-tête de la carte -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">{{ $loop->iteration }}</span>
                    </div>
                    <h2 class="text-xl font-bold text-white">{{ $country }}</h2>
                </div>
                <span class="text-blue-100 bg-white/20 px-3 py-1 rounded-full text-sm font-medium">
                    {{ count($subDistributors) }} sous-distributeurs
                </span>
            </div>
        </div>

        <!-- Tableau -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Rang
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Sous-Distributeur
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Contact
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Wallet
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Service
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Balance
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Montant Total
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Performance
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($subDistributors as $index => $item)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <!-- Rang -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($index < 3)
                                <div class="w-8 h-8 rounded-full flex items-center justify-center 
                                    @if($index === 0) bg-yellow-100 text-yellow-800
                                    @elseif($index === 1) bg-gray-100 text-gray-800
                                    @else bg-orange-100 text-orange-800 @endif">
                                    <span class="font-bold text-sm">{{ $index + 1 }}</span>
                                </div>
                                @else
                                <span class="text-gray-600 font-medium">{{ $index + 1 }}</span>
                                @endif
                            </div>
                        </td>

                        <!-- Sous-Distributeur -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                                    {{ substr($item['sub_distributor'], 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $item['sub_distributor'] }}</div>
                                    <div class="text-sm text-gray-500">{{ $item['sub_countrie'] }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Contact -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item['sub_email'] }}</div>
                            <div class="text-xs text-gray-500">{{ $item['phone'] ?? 'N/A' }}</div>
                        </td>

                        <!-- Wallet -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-mono text-gray-600 bg-gray-100 px-2 py-1 rounded">
                                {{ $item['wallet_id'] }}
                            </div>
                        </td>

                        <!-- Service -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($item['provider'] === 'Service Mobile') bg-green-100 text-green-800
                                @elseif($item['provider'] === 'Service Internet') bg-blue-100 text-blue-800
                                @elseif($item['provider'] === 'Service TV') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $item['provider'] }}
                            </span>
                        </td>

                        <!-- Balance -->
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm font-semibold text-gray-900">
                                {{ number_format($item['wallet_balance'], 0, ',', ' ') }} FCFA
                            </div>
                        </td>

                        <!-- Montant Total -->
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-lg font-bold text-blue-600">
                                {{ number_format($item['total_amount'], 0, ',', ' ') }} FCFA
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $item['transaction_count'] ?? 0 }} transactions
                            </div>
                        </td>

                        <!-- Performance -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php
                                $performance = ($item['total_amount'] / max($subDistributors->max('total_amount'), 1)) * 100;
                            @endphp
                            <div class="flex items-center justify-center space-x-2">
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-400 to-blue-500 h-2 rounded-full" 
                                         style="width: {{ min($performance, 100) }}%"></div>
                                </div>
                                <span class="text-xs font-medium text-gray-600 w-10">
                                    {{ number_format($performance, 1) }}%
                                </span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pied de carte -->
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
            <div class="flex justify-between items-center text-sm text-gray-600">
                <span>Dernière mise à jour: {{ now()->format('d/m/Y H:i') }}</span>
                <button class="text-blue-600 hover:text-blue-800 font-medium flex items-center space-x-1">
                    <span>Voir tout</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Statistiques résumées -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <div class="text-sm opacity-90">Total Transactions</div>
            {{-- <div class="text-2xl font-bold mt-2">{{ number_format($total_transactions ?? 0, 0, ',', ' ') }}</div> --}}
            <div class="text-sm opacity-80 mt-1">Tous services</div>
        </div>
        
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
            <div class="text-sm opacity-90">Montant Total</div>
            {{-- <div class="text-2xl font-bold mt-2">{{ number_format($total_amount ?? 0, 0, ',', ' ') }} FCFA</div> --}}
            <div class="text-sm opacity-80 mt-1">Volume d'affaires</div>
        </div>
        
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white">
            <div class="text-sm opacity-90">Sous-Distributeurs</div>
            {{-- <div class="text-2xl font-bold mt-2">{{ $total_distributors ?? 0 }}</div> --}}
            <div class="text-sm opacity-80 mt-1">Actifs</div>
        </div>
        
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white">
            <div class="text-sm opacity-90">Services</div>
            {{-- <div class="text-2xl font-bold mt-2">{{ $total_services ?? 0 }}</div> --}}
            <div class="text-sm opacity-80 mt-1">Disponibles</div>
        </div>
    </div>
</div>

<!-- Styles additionnels -->
<style>
    .shadow-strong {
        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .hover-lift:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease-in-out;
    }
</style>
