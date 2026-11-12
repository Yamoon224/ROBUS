<div>
    <div class="grid grid-cols-12 gap-6 mt-6">
        <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
            <div class="flex items-center justify-between border-b border-slate-200/60 bg-white">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-8 bg-orange-500 rounded-r-full"></div>
                    <div>
                        <h2 class="font-medium text-base text-slate-900 text-xl">
                            Historique {{ $zone_label }} (Pixel)
                        </h2>
                        <div class="flex items-center gap-4 mt-2 mb-2">
                            <span class="text-sm text-slate-600 font-medium">
                                Dernière mise à jour: {{ now()->format('d/m/Y H:i') }}
                            </span>
                            <span class="w-1 h-1 bg-slate-400 rounded-full"></span>
                            <span class="text-sm text-slate-600 font-medium">
                                {{ $pixel_historicals->total() }} éléments
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mr-5">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control w-56 box"
                        placeholder="Référence, numéro, montant...">
                </div>
            </div>
            <div class="py-5">
                <div class="px-5">
                    <div class="overflow-x-auto">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th class="whitespace-nowrap">Date</th>
                                    <th class="whitespace-nowrap">Référence</th>
                                    <th class="whitespace-nowrap">Service</th>
                                    <th class="whitespace-nowrap">Destinataire</th>
                                    <th class="text-center whitespace-nowrap">Montant débité</th>
                                    <th class="text-center whitespace-nowrap">Frais</th>
                                    <th class="text-center whitespace-nowrap">Reçu (devise locale)</th>
                                    <th class="text-center whitespace-nowrap">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pixel_historicals as $item)
                                    @php
                                        // metadata est casté en tableau par le modèle, mais peut rester
                                        // une chaîne JSON (éventuellement doublement encodée)
                                        $md = $item->metadata ?? [];
                                        if (is_string($md)) {
                                            $md = json_decode($md, true);
                                        }
                                        if (is_string($md)) {
                                            $md = json_decode($md, true);
                                        }
                                        $md = is_array($md) ? $md : [];
                                        $badge = match ($item->status) {
                                            'SUCCESS' => 'bg-success text-white',
                                            'ERROR' => 'bg-danger text-white',
                                            default => 'bg-warning text-white',
                                        };
                                    @endphp
                                    <tr class="intro-x">
                                        <td class="whitespace-nowrap">{{ $item->created_at }}</td>
                                        <td class="whitespace-nowrap font-medium">{{ $item->reference }}</td>
                                        <td>
                                            {{ $md['provider'] ?? '—' }}
                                            <div class="text-xs text-slate-500">
                                                {{ $md['country'] ?? '' }} {{ $md['operation_type'] ?? $item->type_transaction }}
                                            </div>
                                        </td>
                                        <td>{{ $md['destination'] ?? '—' }}</td>
                                        <td class="text-center whitespace-nowrap">
                                            {{ number_format($item->amount, 2, ',', ' ') }} {{ $item->currency }}
                                        </td>
                                        <td class="text-center whitespace-nowrap">
                                            {{ number_format((float) $item->fees, 2, ',', ' ') }} {{ $item->currency }}
                                        </td>
                                        <td class="text-center whitespace-nowrap">
                                            @if (isset($md['amount_credited']))
                                                {{ number_format($md['amount_credited'], 0, ',', ' ') }}
                                                {{ $md['recipient_currency'] ?? '' }}
                                                <div class="text-xs text-slate-500">taux {{ $md['exchange_rate'] ?? '—' }}</div>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="inline-flex px-2 py-1 text-xs font-bold rounded {{ $badge }}">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Aucune donnée pour le moment</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
            <nav class="w-full sm:w-auto sm:mr-auto">
                <ul class="pagination">
                    {{ $pixel_historicals->links('vendor.livewire.tailwind-paiements') }}
                </ul>
            </nav>
        </div>
    </div>
</div>
