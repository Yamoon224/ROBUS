<div>
    <div class="intro-y grid grid-cols-12 gap-5 mt-5">
        <div class="col-span-6 2xl:col-span-3 box p-5">
            <div class="font-medium text-historique">Total opérations</div>
            <div class="text-slate-500 mt-3">{{ $totalCount }}</div>
        </div>
        <div class="col-span-6 2xl:col-span-3 box p-5">
            <div class="font-medium text-historique">En attente</div>
            <div class="text-slate-500 mt-3">{{ $pendingCount }}</div>
        </div>
        <div class="col-span-6 2xl:col-span-3 box p-5">
            <div class="font-medium text-historique">Success</div>
            <div class="text-slate-500 mt-3">{{ $successCount }}</div>
        </div>
        <div class="col-span-6 2xl:col-span-3 box p-5">
            <div class="font-medium text-historique">Echec / Remboursé</div>
            <div class="text-slate-500 mt-3">{{ $errorCount }}</div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap items-end gap-4 mt-2">
            <div class="w-full sm:w-44">
                <label class="block mb-1 text-xs text-slate-600">Date de début</label>
                <input type="date" class="form-control box" wire:model="startDate">
            </div>
            <div class="w-full sm:w-44">
                <label class="block mb-1 text-xs text-slate-600">Date de fin</label>
                <input type="date" class="form-control box" wire:model="endDate">
            </div>
            <div class="w-full sm:w-48">
                <label class="block mb-1 text-xs text-slate-600">Pays du client</label>
                <select class="form-select box py-3 px-4 w-full" wire:model="countryFilter">
                    <option value="">Tous les pays</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-48">
                <label class="block mb-1 text-xs text-slate-600">Zone</label>
                <select class="form-select box py-3 px-4 w-full" wire:model="zoneFilter">
                    <option value="">Toutes les zones</option>
                    <option value="mobile">Mobile Zone (même pays)</option>
                    <option value="international">International Zone</option>
                </select>
            </div>
            <div class="w-full sm:w-44">
                <label class="block mb-1 text-xs text-slate-600">Statut</label>
                <select class="form-select box py-3 px-4 w-full" wire:model="statusFilter">
                    <option value="">Tous les statuts</option>
                    <option value="SUCCESS">Success</option>
                    <option value="PENDING">En attente</option>
                    <option value="ERROR">Erreur</option>
                </select>
            </div>
            <div class="w-full sm:w-64">
                <label class="block mb-1 text-xs text-slate-600">Rechercher</label>
                <input type="text" wire:model.debounce.400ms="search" class="form-control box" style="height: 3rem;"
                    placeholder="Référence, client, numéro, montant…">
            </div>
        </div>

        <div class="intro-y col-span-12 overflow-x-auto">
            <table class="table table-bordered table-hover" style="min-width: 1100px;">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Date</th>
                        <th class="whitespace-nowrap">Référence</th>
                        <th class="whitespace-nowrap">Client</th>
                        <th class="whitespace-nowrap">Zone</th>
                        <th class="whitespace-nowrap">Service / Destinataire</th>
                        <th class="text-center whitespace-nowrap">Montant débité</th>
                        <th class="text-center whitespace-nowrap">Frais</th>
                        <th class="text-center whitespace-nowrap">Reçu</th>
                        <th class="text-center whitespace-nowrap">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($historicals as $item)
                        @php
                            $md = json_decode($item->metadata ?? '', true);
                            if (is_string($md)) {
                                $md = json_decode($md, true);
                            }
                            $md = is_array($md) ? $md : [];
                            $recipientIso = $md['country_iso2'] ?? null;
                            $international = $recipientIso && $recipientIso !== $item->wallet_country_code;
                            $user = optional($item->wallet)->sidUser;
                            $badge = match ($item->status) {
                                'SUCCESS' => 'bg-success text-white',
                                'ERROR', 'FAILED', 'REFUNDED' => 'bg-danger text-white',
                                default => 'bg-warning text-white',
                            };
                        @endphp
                        <tr class="intro-x">
                            <td class="whitespace-nowrap">{{ $item->created_at }}</td>
                            <td class="whitespace-nowrap font-medium">{{ $item->reference }}</td>
                            <td>
                                {{ trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? '')) ?: '—' }}
                                <div class="text-xs text-slate-500">{{ $user->phone ?? '' }}</div>
                                <div class="text-xs text-slate-500">{{ $item->wallet_country_name ?? 'Pays inconnu' }}</div>
                            </td>
                            <td class="whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-bold rounded text-white {{ $international ? 'bg-primary' : 'bg-slate-500' }}">
                                    {{ $international ? 'International' : 'Mobile' }}
                                </span>
                            </td>
                            <td>
                                {{ $md['provider'] ?? '—' }} · {{ $md['destination'] ?? '—' }}
                                <div class="text-xs text-slate-500">{{ $md['country'] ?? '' }} {{ $md['operation_type'] ?? $item->type_transaction }}</div>
                            </td>
                            <td class="text-center whitespace-nowrap">
                                {{ number_format($item->amount, 2, ',', ' ') }} {{ $item->currency }}
                            </td>
                            <td class="text-center whitespace-nowrap">
                                {{ number_format((float) $item->fees, 2, ',', ' ') }} {{ $item->currency }}
                            </td>
                            <td class="text-center whitespace-nowrap">
                                @if (isset($md['amount_credited']))
                                    {{ number_format($md['amount_credited'], 0, ',', ' ') }} {{ $md['recipient_currency'] ?? '' }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="inline-flex px-2 py-1 text-xs font-bold rounded {{ $badge }}">{{ $item->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Aucune donnée pour le moment</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            <nav class="w-full sm:w-auto sm:mr-auto">
                {{ $historicals->links() }}
            </nav>
        </div>
    </div>
</div>
