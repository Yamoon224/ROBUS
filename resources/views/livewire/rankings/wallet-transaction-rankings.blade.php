<div>
    <div class="space-y-2 mt-10">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent">
            Classement des Sous-Distributeurs
        </h1>
        <p class="text-gray-500 text-sm">Classement par service, pays et performance globale</p>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <!-- En-tête avec filtres améliorés -->

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">


            <div class="hidden md:block mx-auto text-slate-500"></div>

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

                    <!-- Filtre service -->
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
                            @foreach ($providers as $provider)
                                <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                            @endforeach
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
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Bouton de réinitialisation -->
                    <button wire:click="$set('periode', 'all')"
                        class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-all duration-200 flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Réinitialiser
                    </button>
                </div>
            </div>

        </div>

        @forelse ($classment_transactions as $country => $subDistributors)
            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible mt-6">

                <table class="table table-bordered table-hover -mt-2 -intro-x ">
                    <thead class="table-dark">
                        <tr>
                            <th class="whitespace-nowrap text-left">Rang</th>
                            <th class="whitespace-nowrap text-left">Sous-Distributeur</th>
                            <th class="whitespace-nowrap text-center">Montant Total & Transactions</th>
                            <th class="whitespace-nowrap text-center"> Performance</th>
                            <th class="whitespace-nowrap text-center">Details</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($subDistributors as $index => $item)
                            @php
                                $performance =
                                    ($item['total_amount'] / max($subDistributors->max('total_amount'), 1)) * 100;
                                if ($performance >= 95) {
                                    $colorClass = 'neon-green';
                                } elseif ($performance >= 80) {
                                    $colorClass = 'indigo';
                                } elseif ($performance >= 65) {
                                    $colorClass = 'violet';
                                } elseif ($performance >= 50) {
                                    $colorClass = 'amber';
                                } elseif ($performance >= 35) {
                                    $colorClass = 'orange';
                                } else {
                                    $colorClass = 'red';
                                }
                            @endphp
                            <tr class="intro-x">
                                <td class="text-center w-40">

                                    <div
                                        class="px-3 py-2 text-dark btn-purple dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                        <div class="fc-event-title-container" style="color: white;">
                                            <div class="fc-event-title fc-sticky">
                                                {{ $item['provider'] }}

                                            </div>
                                        </div>
                                    </div>
                                    @if ($index < 3)
                                        <span
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full font-bold text-sm shadow-lg
                                            @if ($index === 0) user-name text-black
                                            @elseif($index === 1) user-name text-black
                                            @else  text-black @endif">
                                            {{ $index + 1 }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center user-name text-black justify-center w-8 h-8 rounded-full font-bold text-sm shadow-lg">
                                            {{ $index + 1 }}
                                        </span>
                                    @endif

                                </td>
                                <td class="user-info-card">
                                    <div class="user-badges-container">
                                        <div class="user-badge user-badge--distributor">
                                            Sous distributeur
                                        </div>

                                        <!-- Numéro de téléphone -->
                                        <div class="user-phone">
                                            {{ $item['sub_email'] }}
                                        </div>
                                    </div>

                                    <!-- Nom complet -->
                                    <div class="user-name">
                                        {{ $item['sub_distributor'] }}
                                    </div>

                                    <!-- Localisation -->
                                    <div class="user-location">
                                        {{ $item['sub_countrie'] }}
                                        •
                                        {{ $item['sub_citie'] }}
                                    </div>
                                </td>

                                <td class="w-55">

                                    <div class="transaction-alert">

                                        <div class="transaction-alert__date">
                                            <span class="transaction-alert__date-day">
                                                Date transaction : {{ $item['last_transaction_date'] }}
                                            </span>
                                            <span class="transaction-alert__date-time mt-3">
                                                Nombre de transactions : {{ $item['total_transactions'] }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="amount-section">
                                        <div class="amount-balance">
                                            <div class="flex items-baseline justify-between">
                                                <span class="amount-balance-value">
                                                    {{ number_format($item['total_amount'], 0, ',', ' ') }}
                                                </span>
                                                <span class="amount-balance-currency">
                                                    {{ $item['currency'] }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="w-40">
                                    <div class="flex items-center justify-center space-x-3">
                                        <div class="w-20 rounded-full h-2 shadow-inner">
                                            <div class="performance-fill {{ $colorClass }}"
                                                style="width: {{ min($performance, 100) }}%;"></div>

                                        </div>
                                        <span class="text-sm font-bold text-gray-700 min-w-[50px]">
                                            {{ number_format($performance, 1) }}%
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex justify-center items-center">
                                        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-administrateur'))
                                            <a class="btn btn-primary mr-1 mb-2 btn-sm"
                                                href="{{ route('unders.admins.show', ['id' => $item['sub_userId']]) }}">
                                                Details
                                            </a>
                                        @endif

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td class="text-center  py-4">
                                    Aucun sous-distributeur disponible pour ce pays.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pied de page amélioré -->
                <div
                    class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 flex justify-between items-center text-sm text-gray-600 border-t border-gray-200">
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span>Dernière mise à jour : {{ now()->format('d/m/Y à H:i') }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="intro-y col-span-12 flex justify-center mt-8">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 w-full lg:w-1/2">
        <div class="flex flex-col items-center justify-center h-64 text-gray-500 text-center">
            <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2-8H7m10-4H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2z"/>
            </svg>
            <p class="text-lg font-semibold">Aucun classement disponible</p>
            <p class="mt-2 text-sm text-gray-400">
                Veuillez ajuster vos filtres par pays et par service pour afficher le classement.
            </p>
        </div>
    </div>
</div>


        @endforelse
    </div>

</div>



<!-- Styles additionnels -->
<style>
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .shadow-strong {
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.15);
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        transition: all 0.3s ease-in-out;
    }
</style>
