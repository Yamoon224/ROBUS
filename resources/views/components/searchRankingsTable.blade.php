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
                                                    {{ number_format($item['total_amount'], 2, ',', ' ') }}
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