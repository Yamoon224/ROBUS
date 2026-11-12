<div>
    <div class="grid grid-cols-12 gap-6 mt-6">
        <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200/60 bg-white">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-8 bg-orange-500 rounded-r-full"></div>
                    <div>
                        <h2 class="font-medium text-base text-slate-900 text-xl">
                            Transferts {{ $zone_label }} (Pixel)
                        </h2>
                        <div class="flex items-center gap-4 mt-2 mb-2">
                            <span class="text-sm text-slate-600 font-medium">
                                {{ $transfers->total() }} éléments
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
                                    <th class="whitespace-nowrap">Références</th>
                                    <th class="whitespace-nowrap">Expéditeur</th>
                                    <th class="whitespace-nowrap">Destinataire</th>
                                    <th class="text-center whitespace-nowrap">Montant débité</th>
                                    <th class="text-center whitespace-nowrap">Frais</th>
                                    <th class="text-center whitespace-nowrap">Reçu</th>
                                    <th class="text-center whitespace-nowrap">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transfers as $item)
                                    @php
                                        $badge = match ($item->status) {
                                            'SUCCESS' => 'bg-success text-white',
                                            'FAILED', 'ERROR', 'REFUNDED' => 'bg-danger text-white',
                                            default => 'bg-warning text-white',
                                        };
                                    @endphp
                                    <tr class="intro-x">
                                        <td class="whitespace-nowrap">{{ $item->created_at }}</td>
                                        <td class="whitespace-nowrap">
                                            <div class="font-medium">{{ $item->sender_transaction_id ?? '—' }}</div>
                                            <div class="text-xs text-slate-500">{{ $item->recipient_transaction_id ?? '—' }}</div>
                                        </td>
                                        <td>
                                            {{ $item->sender_phone }}
                                            <div class="text-xs text-slate-500">
                                                {{ $item->senderService->provider->name ?? '' }} {{ $item->sender_country_iso2 }}
                                            </div>
                                        </td>
                                        <td>
                                            {{ $item->recipient_phone }}
                                            <div class="text-xs text-slate-500">
                                                {{ $item->recipientService->provider->name ?? '' }} {{ $item->recipient_country_iso2 }}
                                            </div>
                                        </td>
                                        <td class="text-center whitespace-nowrap">
                                            {{ number_format($item->amount, 2, ',', ' ') }} {{ $item->sender_currency ?? $item->currency }}
                                        </td>
                                        <td class="text-center whitespace-nowrap">
                                            {{ number_format((float) $item->total_fee, 2, ',', ' ') }} {{ $item->sender_currency ?? $item->currency }}
                                        </td>
                                        <td class="text-center whitespace-nowrap">
                                            {{ number_format((float) $item->amount_received, 0, ',', ' ') }} {{ $item->recipient_currency }}
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
                    {{ $transfers->links('vendor.livewire.tailwind-paiements') }}
                </ul>
            </nav>
        </div>
    </div>
</div>
