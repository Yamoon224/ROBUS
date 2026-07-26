@extends('layouts.master')

@section('title', 'Réceptions par acteur')

@push('styles')
    @include('pages.reports.partials.detail-styles')
@endpush

@section('content')
    @php
        $actorLabel = ucfirst(str_replace('_', ' ', $actor));
        $totalReloads = collect($users)->sum('rechargements_recus');
        $totalAmount = collect($users)->sum('montant_recu');
        $totalSentReloads = collect($users)->sum('rechargements_envoyes');
        $totalTransactions = collect($users)->sum('transactions') + collect($users)->sum('transactions_recues');
        $totalReceivedTransactions = collect($users)->sum('transactions_recues');
        $totalReceivedTransactionAmount = collect($users)->sum('montant_transactions_recu');
        $formatAmount = static fn ($amount) => number_format((float) $amount, 2, ',', ' ');
    @endphp

    <div class="report-detail py-6">
        <section class="report-detail__hero intro-y">
            <div class="report-detail__hero-row">
                <div>
                    <h1 class="report-detail__title">{{ $actorLabel }}</h1>
                    <p class="report-detail__subtitle">
                        @if ($actor === 'superviseurs')
                            Rechargements envoyés et reçus, transactions émises et reçues le {{ $date }}
                        @else
                            Classement des utilisateurs ayant reçu le plus de rechargements le {{ $date }}
                        @endif
                    </p>
                </div>
                <div class="report-detail__actions">
                    @if (in_array($actor, ['superviseurs', 'sous_distributeurs'], true))
                        <a href="{{ route('reports.daily.actor.download', ['actor' => $actor, 'date' => $date]) }}"
                            class="report-detail__button">
                            <i data-feather="download" class="w-4 h-4"></i>
                            Télécharger le PDF
                        </a>
                    @endif
                    <a href="{{ route('reports.daily', ['date' => $date]) }}" class="report-detail__button">
                        <i data-feather="arrow-left" class="w-4 h-4"></i>
                        Retour au rapport
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('reports.daily.actor', ['actor' => $actor]) }}"
                class="report-detail__filter">
                <div>
                    <label for="actor-report-date">Date du rapport</label>
                    <input id="actor-report-date" type="date" name="date" value="{{ $date }}"
                        max="{{ now()->toDateString() }}" required>
                </div>
                <button type="submit" class="report-detail__button report-detail__button--light">
                    <i data-feather="filter" class="w-4 h-4"></i>
                    Actualiser
                </button>
            </form>
        </section>

        <section class="report-detail__metrics">
            <article class="report-detail__metric">
                <div class="report-detail__metric-value">{{ count($users) }}</div>
                <div class="report-detail__metric-label">
                    {{ $actor === 'superviseurs' ? 'Superviseurs actifs' : 'Utilisateurs rechargés' }}
                </div>
            </article>
            <article class="report-detail__metric">
                <div class="report-detail__metric-value">
                    {{ $actor === 'superviseurs' ? $totalSentReloads : $totalReloads }}
                </div>
                <div class="report-detail__metric-label">
                    {{ $actor === 'superviseurs' ? 'Rechargements envoyés' : 'Rechargements reçus' }}
                </div>
            </article>
            <article class="report-detail__metric">
                <div class="report-detail__metric-value">
                    {{ $actor === 'superviseurs' ? $totalReloads : $formatAmount($totalAmount) }}
                </div>
                <div class="report-detail__metric-label">
                    {{ $actor === 'superviseurs' ? 'Rechargements reçus' : 'Montant des rechargements reçus' }}
                </div>
            </article>
            <article class="report-detail__metric">
                <div class="report-detail__metric-value">
                    @if ($actor === 'superviseurs')
                        {{ $totalTransactions }}
                    @else
                        {{ $totalReceivedTransactions }}
                    @endif
                </div>
                <div class="report-detail__metric-label">
                    {{ $actor === 'superviseurs' ? 'Transactions émises et reçues' : 'Transactions reçues' }}
                </div>
            </article>
            @if ($actor !== 'superviseurs')
                <article class="report-detail__metric">
                    <div class="report-detail__metric-value">{{ $formatAmount($totalReceivedTransactionAmount) }}</div>
                    <div class="report-detail__metric-label">Montant des transactions reçues</div>
                </article>
            @endif
        </section>

        <section class="report-detail__section intro-y">
            <header class="report-detail__section-header">
                <div>
                    <h2 class="report-detail__section-title">Classement des bénéficiaires</h2>
                    <p class="report-detail__section-copy">
                        {{ $actor === 'superviseurs'
                            ? 'Trié selon le nombre total de mouvements'
                            : 'Trié par nombre de rechargements reçus, puis par montant' }}
                    </p>
                </div>
                <i data-feather="award" class="w-5 h-5 text-primary"></i>
            </header>

            <div class="report-detail__table-wrap">
                <table class="report-detail__table">
                    <thead>
                        <tr>
                            <th>Rang</th>
                            <th>Utilisateur</th>
                            @if ($actor === 'superviseurs')
                                <th class="is-numeric">Rechargements envoyés</th>
                                <th class="is-numeric">Rechargements reçus</th>
                                <th class="is-numeric">Transactions émises</th>
                                <th class="is-numeric">Transactions reçues</th>
                                <th>Dernière activité</th>
                                <th>Action</th>
                            @else
                                <th class="is-numeric">Rechargements reçus</th>
                                <th class="is-numeric">Transactions reçues</th>
                                <th class="is-numeric">Montant rechargements</th>
                                <th class="is-numeric">Montant transactions</th>
                                <th>Dernière activité reçue</th>
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td><span class="report-detail__rank">{{ $user['rang'] }}</span></td>
                                <td>
                                    <div class="report-detail__user">
                                        <span class="report-detail__avatar">
                                            {{ strtoupper(substr($user['nom'], 0, 2)) }}
                                        </span>
                                        {{ $user['nom'] }}
                                    </div>
                                </td>
                                @if ($actor === 'superviseurs')
                                    <td class="is-numeric">{{ $user['rechargements_envoyes'] }}</td>
                                    <td class="is-numeric">{{ $user['rechargements_recus'] }}</td>
                                    <td class="is-numeric">{{ $user['transactions'] }}</td>
                                    <td class="is-numeric">{{ $user['transactions_recues'] }}</td>
                                    <td>{{ $user['derniere_activite'] }}</td>
                                    <td>
                                        <a href="{{ route('reports.daily.supervisor', ['supervisor' => $user['id'], 'date' => $date]) }}"
                                            class="report-detail__link">
                                            Voir toute l’activité
                                            <i data-feather="arrow-right" class="w-4 h-4"></i>
                                        </a>
                                    </td>
                                @else
                                    <td class="is-numeric">{{ $user['rechargements_recus'] }}</td>
                                    <td class="is-numeric">{{ $user['transactions_recues'] }}</td>
                                    <td class="is-numeric font-medium">{{ $formatAmount($user['montant_recu']) }}</td>
                                    <td class="is-numeric font-medium">
                                        {{ $formatAmount($user['montant_transactions_recu']) }}
                                    </td>
                                    <td>{{ $user['derniere_reception'] }}</td>
                                    <td>
                                        <a href="{{ route('reports.daily.actor.user', ['actor' => $actor, 'user' => $user['id'], 'date' => $date]) }}"
                                            class="report-detail__link">
                                            Voir les soldes
                                            <i data-feather="arrow-right" class="w-4 h-4"></i>
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="report-detail__empty">
                                    {{ $actor === 'superviseurs'
                                        ? 'Aucun superviseur n’a eu de mouvement à cette date.'
                                        : 'Aucun utilisateur de ce type n’a reçu de rechargement à cette date.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
