@extends('layouts.master')

@section('title', 'Soldes des rechargements')

@push('styles')
    @include('pages.reports.partials.detail-styles')
@endpush

@section('content')
    @php
        $formatAmount = static fn ($amount) => number_format((float) $amount, 2, ',', ' ');
        $formatBalance = static fn ($amount) => $amount === null ? '—' : number_format((float) $amount, 2, ',', ' ');
        $statusClass = static function ($status) {
            $status = strtoupper((string) $status);
            return $status === 'SUCCESS'
                ? 'success'
                : (in_array($status, ['ERROR', 'FAILED'], true) ? 'failed' : 'pending');
        };
    @endphp

    <div class="report-detail py-6">
        <section class="report-detail__hero intro-y">
            <div class="report-detail__hero-row">
                <div>
                    <h1 class="report-detail__title">{{ $details['nom'] }}</h1>
                    <p class="report-detail__subtitle">
                        Rechargements reçus · Wallet {{ $details['wallet'] ?: 'non identifié' }} · {{ $date }}
                    </p>
                </div>
                <a href="{{ route('reports.daily.actor', ['actor' => $actor, 'date' => $date]) }}"
                    class="report-detail__button">
                    <i data-feather="arrow-left" class="w-4 h-4"></i>
                    Retour à la liste
                </a>
            </div>
        </section>

        <section class="report-detail__metrics">
            <article class="report-detail__metric">
                <div class="report-detail__metric-value">{{ $details['nombre_rechargements'] }}</div>
                <div class="report-detail__metric-label">Rechargements reçus</div>
            </article>
            <article class="report-detail__metric">
                <div class="report-detail__metric-value">{{ $formatAmount($details['montant_recu']) }}</div>
                <div class="report-detail__metric-label">Montant total reçu</div>
            </article>
        </section>

        <section class="report-detail__section intro-y">
            <header class="report-detail__section-header">
                <div>
                    <h2 class="report-detail__section-title">Historique des rechargements</h2>
                    <p class="report-detail__section-copy">
                        Solde du receveur avant et après chaque rechargement
                    </p>
                </div>
                <i data-feather="credit-card" class="w-5 h-5 text-primary"></i>
            </header>

            <div class="report-detail__table-wrap">
                <table class="report-detail__table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Expéditeur</th>
                            <th>Devise</th>
                            <th class="is-numeric">Montant envoyé</th>
                            <th class="is-numeric">Montant reçu</th>
                            <th class="is-numeric">Solde avant</th>
                            <th class="is-numeric">Solde après</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($details['rechargements'] as $reload)
                            <tr>
                                <td>{{ $reload['date'] }}</td>
                                <td>{{ $reload['expediteur'] }}</td>
                                <td>{{ $reload['devise_receveur'] }}</td>
                                <td class="is-numeric">{{ $formatAmount($reload['montant']) }}</td>
                                <td class="is-numeric">{{ $formatAmount($reload['montant_recu']) }}</td>
                                <td class="is-numeric">{{ $formatBalance($reload['solde_avant']) }}</td>
                                <td class="is-numeric">{{ $formatBalance($reload['solde_apres']) }}</td>
                                <td>
                                    <span class="report-detail__status report-detail__status--{{ $statusClass($reload['statut']) }}">
                                        {{ $reload['statut'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="report-detail__empty">
                                    Aucun rechargement reçu à cette date.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
