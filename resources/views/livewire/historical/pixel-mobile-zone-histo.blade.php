<div>

    {{-- ── En-tête de page ── --}}
    <div class="intro-y block sm:flex items-center h-10">
        <h2 class="text-lg font-medium truncate mr-5">Historique Transferts Mobile Zone</h2>
        <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
            <a class="ml-3 flex items-center text-slate-600 dark:text-slate-300 btn btn-primary mr-1 mb-2"
                href="{{ route('pixel-mobile-zone.historical.reporting_pixel_mobile_zone_historical') }}"
                style="color: white;">
                <i data-lucide="file-text" class="hidden sm:block w-4 h-4 mr-2"></i>
                Telecharger le rapport
            </a>
        </div>
    </div>

    {{-- ── Cartes statistiques ── --}}
    <div class="intro-y grid grid-cols-12 gap-5 mt-5">

        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table_factures">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                stroke-linejoin="round" class="feather feather-send font-medium-5">
                <line x1="22" y1="2" x2="11" y2="13"></line>
                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
            </svg>
            <div class="font-medium text-historique mt-6">Total transferts</div>
            <div class="text-slate-500 mt-3">
                {{ number_format((float) ($totalAmount ?? 0), 2, ',', ' ') }} {{ $usersCountry }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 box p-5 cursor-pointer zoom-in style-pendd">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                stroke-linejoin="round" class="feather feather-alert-circle font-medium-5">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div class="font-medium text-historique mt-6">En attente</div>
            <div class="text-slate-500 mt-3">{{ $pendingCount ?? 0 }}</div>
        </div>

        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 box bg-primary p-5 cursor-pointer zoom-in style-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                stroke-linejoin="round" class="feather feather-check font-medium-5">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <div class="font-medium text-historique text-white mt-6">Success</div>
            <div class="text-white text-opacity-80 dark:text-slate-500 mt-3">{{ $successCount ?? 0 }}</div>
        </div>

        <div class="col-span-12 sm:col-span-6 2xl:col-span-3 box p-5 cursor-pointer zoom-in style-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                stroke-linejoin="round" class="feather feather-x-circle font-medium-5">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            <div class="font-medium text-historique mt-6">Echec / Rembourse</div>
            <div class="text-slate-500 mt-3">{{ $errorCount ?? 0 }}</div>
        </div>

    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">

        {{-- ── Filtres ── --}}
        <div class="intro-y col-span-12 grid grid-cols-12 gap-5 items-end mt-2">

            <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
                <div class="relative text-slate-500 mt-3">
                    <label for="start_date" class="block mb-1 text-xs text-slate-600">Date de début</label>
                    <input type="date" class="form-control box pr-10"
                        wire:model="startDate" id="start_date" aria-label="Date de début">
                </div>
            </div>

            <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
                <div class="relative text-slate-500 mt-3">
                    <label for="end_date" class="block mb-1 text-xs text-slate-600">Date de fin</label>
                    <input type="date" class="form-control box pr-10"
                        wire:model="endDate" id="end_date" aria-label="Date de fin">
                </div>
            </div>

            <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
                <div class="relative text-slate-500 mt-3">
                    <label for="statusFilter" class="block mb-1 text-xs text-slate-600">Statut</label>
                    <select id="statusFilter" wire:model="statusFilter"
                        class="form-select box py-3 px-4 w-full">
                        <option value="">Tous les statuts</option>
                        <option value="SUCCESS">Success</option>
                        <option value="PROCESSING">En traitement</option>
                        <option value="PENDING">En attente</option>
                        <option value="FAILED">Echec</option>
                        <option value="REFUNDED">Rembourse</option>
                    </select>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
                <div class="relative text-slate-500 mt-3">
                    <label class="block mb-1 text-xs text-slate-600">Rechercher</label>
                    <input type="text" wire:model.debounce.250ms="searchHistorical"
                        class="form-control box pr-10" style="height: 3rem;"
                        placeholder="Opérateur, numéro, référence…">
                </div>
            </div>

        </div>

        {{-- ── Tableau ── --}}
        <div class="intro-y box px-3 pt-1 mt-6 col-span-12 overflow-auto lg:overflow-visible">
            <div class="grid grid-cols-12 gap-6 mt-6">
                <div class="intro-y box col-span-12" style="background-color: #eff3f7;">

                    <div class="items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Liste des transferts Mobile Zone
                            <span class="ml-2 text-xs font-normal text-slate-500">({{ $totalCount ?? 0 }} au total)</span>
                        </h2>
                    </div>

                    <div class="tiny-slider py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">

                                <table id="table_pixel_mz" class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">
                                                Opérateur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">
                                                Expéditeur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Montant
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Info transaction
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Destinataire
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Statut
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($pixel_mobile_zone_historicals as $transfer)
                                            @php
                                                $walletOwner       = $transfer->wallet->sidUser ?? null;
                                                $senderCurrency    = $transfer->sender_currency    ?? $transfer->currency ?? 'XOF';
                                                $recipientCurrency = $transfer->recipient_currency ?? $transfer->currency ?? 'XOF';
                                            @endphp
                                            <tr class="intro-x">

                                                {{-- Opérateur --}}
                                                <td class="text-center w-40">
                                                    <div class="px-3 py-2 text-dark btn-primary dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                        <div class="fc-event-title-container" style="color: white;">
                                                            <div class="fc-event-title fc-sticky">
                                                                {{ $transfer->senderService->provider->name ?? '-' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-indigo-100 text-indigo-600">
                                                        Mobile Zone
                                                    </div>
                                                    <div class="text-xs text-slate-500 mt-1">
                                                        {{ $transfer->senderService->country->name ?? $transfer->sender_country_iso2 ?? '?' }}
                                                    </div>
                                                    <div class="text-xs text-slate-400 mt-0.5">
                                                        {{ $transfer->sender_phone }}
                                                    </div>
                                                </td>

                                                {{-- Expéditeur (compte Robus) --}}
                                                <td class="user-info-card">
                                                    <div class="user-badges-container">
                                                        @if ($walletOwner?->is_under_distributer == 1)
                                                            <div class="user-badge user-badge--distributor">Sous-distributeur</div>
                                                        @elseif ($walletOwner?->is_custumers == 1)
                                                            <div class="user-badge user-badge--customer">Client</div>
                                                        @elseif ($walletOwner?->is_company == 1)
                                                            <div class="user-badge user-badge--compagnie">Société</div>
                                                        @elseif ($walletOwner?->is_api == 1)
                                                            <div class="user-badge user-badge--compagnie">Intégrateur</div>
                                                        @else
                                                            <div class="user-badge user-badge--admin">Administrateur</div>
                                                        @endif
                                                        <div class="user-phone">
                                                            {{ $walletOwner->phone ?? 'Non renseigné' }}
                                                        </div>
                                                    </div>
                                                    <div class="user-name">
                                                        {{ ($walletOwner->firstname ?? '') . ' ' . ($walletOwner->lastname ?? '') }}
                                                    </div>
                                                    <div class="user-location">
                                                        {{ $walletOwner->country->name ?? 'Pays inconnu' }}
                                                        @if ($walletOwner?->city)
                                                            • {{ $walletOwner->city->name }}
                                                        @endif
                                                    </div>
                                                </td>

                                                {{-- Montant --}}
                                                <td>
                                                    <div class="amount-section">
                                                        <div class="amount-label">Montant envoyé</div>
                                                        <div class="flex items-baseline">
                                                            <span class="amount-value">
                                                                {{ number_format((float) $transfer->amount, 2, ',', ' ') }}
                                                            </span>
                                                            <span class="amount-currency">{{ $senderCurrency }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="amount-section">
                                                        <div class="amount-label">Montant reçu</div>
                                                        <div class="amount-received">
                                                            <div class="flex items-baseline justify-between">
                                                                <span class="amount-received-value">
                                                                    {{ number_format((float) $transfer->amount_received, 2, ',', ' ') }}
                                                                </span>
                                                                <span class="amount-received-currency">{{ $recipientCurrency }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="amount-section">
                                                        <div class="amount-cfa">
                                                            <div class="amount-cfa-label">Frais total</div>
                                                            <div class="amount-cfa-value">
                                                                {{ number_format((float) $transfer->total_fee, 2, ',', ' ') }}
                                                                {{ $senderCurrency }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                {{-- Info transaction --}}
                                                <td class="w-40">
                                                    <div class="transaction-alert">
                                                        <div class="transaction-alert__content">
                                                            <div class="transaction-alert__icon">
                                                                <svg fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                            <span class="transaction-alert__text">INFO TRANSACTION</span>
                                                        </div>
                                                        <div class="transaction-alert__date">
                                                            <span class="transaction-alert__date-day">
                                                                {{ \Carbon\Carbon::parse($transfer->created_at)->format('d/m/Y') }}
                                                            </span>
                                                            <span class="transaction-alert__date-time">
                                                                {{ \Carbon\Carbon::parse($transfer->created_at)->format('H:i') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="transaction-alert-valiated">
                                                        <div class="transaction-alert__content">
                                                            <div class="transaction-alert__icon">
                                                                <svg fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                            <span class="transaction-alert__text">INFO TRAITEMENT</span>
                                                        </div>
                                                        <div class="transaction-alert__date">
                                                            <span class="transaction-alert__date-day">
                                                                {{ \Carbon\Carbon::parse($transfer->updated_at)->format('d/m/Y') }}
                                                            </span>
                                                            <span class="transaction-alert__date-time">
                                                                {{ \Carbon\Carbon::parse($transfer->updated_at)->format('H:i') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    {{-- Références Pixel --}}
                                                    <div class="mt-3 text-xs leading-5 text-slate-600">
                                                        @if ($transfer->sender_transaction_id)
                                                            <div>
                                                                <span class="font-semibold">Cash Out:</span>
                                                                {{ $transfer->sender_transaction_id }}
                                                            </div>
                                                        @endif
                                                        @if ($transfer->recipient_transaction_id)
                                                            <div>
                                                                <span class="font-semibold">Cash In:</span>
                                                                {{ $transfer->recipient_transaction_id }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>

                                                {{-- Destinataire --}}
                                                <td class="w-60">
                                                    <span class="bg-success text-white text-xs px-2 py-1 rounded">
                                                        Destinataire Mobile
                                                    </span>
                                                    <div class="user-badges-container mt-3">
                                                        <div class="user-phone">{{ $transfer->recipient_phone }}</div>
                                                    </div>
                                                    <div class="user-name">
                                                        {{ $transfer->recipientService->provider->name ?? '-' }}
                                                    </div>
                                                    <div class="user-location">
                                                        {{ $transfer->recipientService->country->name ?? $transfer->recipient_country_iso2 ?? '?' }}
                                                    </div>
                                                    {{-- Statuts Cash Out / Cash In --}}
                                                    <div class="mt-3 flex flex-col gap-1">
                                                        <span class="inline-flex items-center gap-1 text-xs">
                                                            <span class="text-slate-400">Débit:</span>
                                                            <span class="px-1.5 py-0.5 rounded text-xs font-medium
                                                                @if ($transfer->sender_status === 'SUCCESS') bg-success/20 text-success
                                                                @elseif (in_array($transfer->sender_status, ['PENDING','PROCESSING'])) bg-warning/20 text-warning
                                                                @else bg-danger/20 text-danger @endif">
                                                                {{ $transfer->sender_status }}
                                                            </span>
                                                        </span>
                                                        <span class="inline-flex items-center gap-1 text-xs">
                                                            <span class="text-slate-400">Crédit:</span>
                                                            <span class="px-1.5 py-0.5 rounded text-xs font-medium
                                                                @if ($transfer->recipient_status === 'SUCCESS') bg-success/20 text-success
                                                                @elseif (in_array($transfer->recipient_status, ['PENDING','PROCESSING'])) bg-warning/20 text-warning
                                                                @else bg-danger/20 text-danger @endif">
                                                                {{ $transfer->recipient_status }}
                                                            </span>
                                                        </span>
                                                        @if ($transfer->refund_status)
                                                            <span class="inline-flex items-center gap-1 text-xs">
                                                                <span class="text-slate-400">Remb.:</span>
                                                                <span class="px-1.5 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-600">
                                                                    {{ $transfer->refund_status }}
                                                                </span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>

                                                {{-- Statut global --}}
                                                <td class="w-60">
                                                    @if (in_array($transfer->status, ['PENDING', 'PROCESSING']))
                                                        <div class="flex items-center justify-center text-pending mt-3">
                                                            <span class="alert alert-pending inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                {{ $transfer->status === 'PROCESSING' ? 'En traitement' : 'En attente' }}
                                                            </span>
                                                        </div>
                                                    @elseif (in_array($transfer->status, ['FAILED', 'ERROR']))
                                                        <div class="flex items-center justify-center text-danger mt-3">
                                                            <span class="alert alert-danger inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                Refusé
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center justify-center mt-3">
                                                            <span class="alert alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded" style="color: white;">
                                                                Traiter
                                                            </span>
                                                        </div>
                                                    @elseif ($transfer->status === 'REFUNDED')
                                                        <div class="flex items-center justify-center mt-3">
                                                            <span class="alert alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded" style="color: white;">
                                                                Remboursé
                                                            </span>
                                                        </div>
                                                    @else
                                                        <div class="flex items-center justify-center text-success mt-3">
                                                            <span class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded" style="color: white;">
                                                                Accepté
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center justify-center mt-3">
                                                            <span class="alert alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded" style="color: white;">
                                                                Traiter
                                                            </span>
                                                        </div>
                                                    @endif
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-8 text-slate-500">
                                                    Aucun transfert Mobile Zone trouvé.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                </div>

                {{-- Pagination --}}
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6 mb-6">
                    <nav class="w-full sm:w-auto sm:mr-auto">
                        <ul class="pagination">
                            {{ $pixel_mobile_zone_historicals->links() }}
                        </ul>
                    </nav>
                </div>

            </div>
        </div>

    </div>

</div>
