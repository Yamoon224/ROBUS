<div class="space-y-6">
    @if (!$transactionCounters)
        <div class="alert alert-warning show" role="alert">
            Impossible de charger les statistiques : cet utilisateur n'est pas une COM ZONE.
        </div>
    @else
        @php
            $periodLabels = [
                'filtered' => 'Période filtrée',
                'daily' => 'Jour',
                'weekly' => 'Semaine',
                'monthly' => 'Mois',
                'quarterly' => 'Trimestre',
                'yearly' => 'Année',
            ];
            $linkedUsers = $transactionCounters['linked_users_counts'];
            $filteredCount = $transactionCounters['totals']['counts']['filtered'] ?? 0;
            $filteredAmount = $transactionCounters['totals']['amounts']['filtered'] ?? 0;
            $filteredCommission = collect($transactionCounters['services_stats'])->sum(fn ($service) => $service['commissions']['filtered'] ?? 0);
            $assignedServicesCount = collect($transactionCounters['services_stats'])->where('is_assigned', true)->count();
        @endphp

        <div class="box p-5">
            <div class="flex flex-col lg:flex-row lg:items-end gap-4">
                <div class="flex-1 grid grid-cols-12 gap-4">
                    <div class="col-span-12 md:col-span-3">
                        <label class="form-label">Statut</label>
                        <select wire:model="transactionStatus" class="form-control">
                            <option value="">Tous</option>
                            <option value="SUCCESS">SUCCESS</option>
                            <option value="PENDING">PENDING</option>
                            <option value="ERROR">ERROR</option>
                        </select>
                    </div>
                    <div class="col-span-12 md:col-span-3">
                        <label class="form-label">Date début</label>
                        <input wire:model="transactionStartDate" type="date" class="form-control">
                    </div>
                    <div class="col-span-12 md:col-span-3">
                        <label class="form-label">Date fin</label>
                        <input wire:model="transactionEndDate" type="date" class="form-control">
                    </div>
                    <div class="col-span-12 md:col-span-3 flex items-end">
                        <button wire:click="resetTransactionFilters" type="button" class="btn btn-secondary w-full">
                            Réinitialiser
                        </button>
                    </div>
                </div>
            </div>
            <div class="text-slate-500 text-xs mt-3">
                Période active : {{ $transactionCounters['filters']['resolved_start_date'] }} au
                {{ $transactionCounters['filters']['resolved_end_date'] }} - Statut :
                {{ $transactionCounters['status'] ?: 'Tous' }}
            </div>
        </div>

        <div class="grid grid-cols-12 gap-4 mt-6">
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 box p-5 style_user3">
                <div class="text-slate-500 text-sm">Utilisateurs liés</div>
                <div class="text-2xl font-semibold mt-2">{{ number_format($linkedUsers['total'], 0, ',', ' ') }}</div>
                <div class="text-xs text-slate-500 mt-2">
                    Clients {{ $linkedUsers['clients'] }} | Masters {{ $linkedUsers['masters'] }} | SD {{ $linkedUsers['under_distributors'] }}
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 box p-5">
                <div class="text-slate-500 text-sm">Transactions</div>
                <div class="text-2xl font-semibold mt-2">{{ number_format($filteredCount, 0, ',', ' ') }}</div>
                <div class="text-xs text-slate-500 mt-2">Sur la période filtrée</div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 box p-5 style_user2">
                <div class="text-slate-500 text-sm">Montant total</div>
                <div class="text-2xl font-semibold mt-2">{{ number_format($filteredAmount, 2, ',', ' ') }}</div>
                <div class="text-xs text-slate-500 mt-2">Sur les services assignés</div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 box p-5">
                <div class="text-slate-500 text-sm">Commission</div>
                <div class="text-2xl font-semibold mt-2">{{ number_format($filteredCommission, 2, ',', ' ') }}</div>
                <div class="text-xs text-slate-500 mt-2">{{ $assignedServicesCount }} services assignés</div>
            </div>
        </div>

        <div class="box p-5 mt-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-medium text-base">Totaux par période</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="table table-report">
                    <thead>
                        <tr>
                            <th>Période</th>
                            <th class="text-center">Transactions</th>
                            <th class="text-right">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($periodLabels as $period => $label)
                            <tr>
                                <td>{{ $label }}</td>
                                <td class="text-center">{{ number_format($transactionCounters['totals']['counts'][$period] ?? 0, 0, ',', ' ') }}</td>
                                <td class="text-right">{{ number_format($transactionCounters['totals']['amounts'][$period] ?? 0, 2, ',', ' ') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box p-5 mt-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-medium text-base">Services COM ZONE</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="table table-bordered table-hover -mt-2 -intro-x ">
                    <thead class="table-dark border-b-2 border-gray-200 ">
                        <tr>
                            <th>Service</th>
                            <th class="text-center">Assigné</th>
                            <th class="text-center">Transactions</th>
                            <th class="text-right">Montant</th>
                            <th class="text-center">Tx commission</th>
                            <th class="text-right">Montant commissionnable</th>
                            <th class="text-right">Commission</th>
                            <th class="text-center">Règles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactionCounters['services_stats'] as $service)
                            <tr>
                                <td class="font-medium">{{ $service['name'] }}</td>
                                <td class="text-center">
                                    <span class="px-2 py-1 rounded text-xs {{ $service['is_assigned'] ? 'bg-success/10 text-success' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $service['is_assigned'] ? 'Oui' : 'Non' }}
                                    </span>
                                </td>
                                <td class="text-center">{{ number_format($service['counts']['filtered'] ?? 0, 0, ',', ' ') }}</td>
                                <td class="text-right">{{ number_format($service['amounts']['filtered'] ?? 0, 2, ',', ' ') }}</td>
                                <td class="text-center">{{ number_format($service['commission_counts']['filtered'] ?? 0, 0, ',', ' ') }}</td>
                                <td class="text-right">{{ number_format($service['commission_amounts']['filtered'] ?? 0, 2, ',', ' ') }}</td>
                                <td class="text-right">{{ number_format($service['commissions']['filtered'] ?? 0, 2, ',', ' ') }}</td>
                                <td class="text-center text-xs text-slate-500">
                                    N: {{ number_format($service['numbers_rule'] ?? 0, 0, ',', ' ') }} |
                                    M: {{ number_format($service['amount_rule'] ?? 0, 2, ',', ' ') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
