@extends('layouts.master')

@section('title', 'Activité du superviseur')

@push('styles')
    @include('pages.reports.partials.detail-styles')
@endpush

@section('content')
    @php
        $summary = $details['resume'];
        $formatAmount = static fn ($amount) => number_format((float) $amount, 2, ',', ' ');
        $formatBalance = static fn ($amount) => $amount === null ? '—' : number_format((float) $amount, 2, ',', ' ');
        $hasDifference = static fn ($amount) => abs((float) $amount) >= 0.01;
        $label = static fn ($value) => ucfirst(str_replace('_', ' ', $value));
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
                        Activité complète du superviseur · Wallet {{ $details['wallet'] }} · {{ $date }}
                    </p>
                </div>
                <div class="report-detail__actions">
                    <a href="{{ route('reports.daily.supervisor.download', ['supervisor' => $details['id'], 'date' => $date]) }}"
                        class="report-detail__button">
                        <i data-feather="download" class="w-4 h-4"></i>
                        Télécharger le PDF
                    </a>
                    <a href="{{ route('reports.daily.actor', ['actor' => 'superviseurs', 'date' => $date]) }}"
                        class="report-detail__button">
                        <i data-feather="arrow-left" class="w-4 h-4"></i>
                        Liste des superviseurs
                    </a>
                    <a href="{{ route('reports.daily', ['date' => $date]) }}" class="report-detail__button">
                        <i data-feather="bar-chart-2" class="w-4 h-4"></i>
                        Rapport
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('reports.daily.supervisor', ['supervisor' => $details['id']]) }}"
                class="report-detail__filter">
                <div>
                    <label for="supervisor-report-date">Date du rapport</label>
                    <input id="supervisor-report-date" type="date" name="date" value="{{ $date }}"
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
                <div class="report-detail__metric-value">{{ $formatAmount($summary['montant_recharge']) }}</div>
                <div class="report-detail__metric-label">
                    {{ $summary['rechargements_envoyes'] }} rechargement(s) envoyé(s)
                </div>
                @if ($hasDifference($summary['difference_rechargements_envoyes']))
                    <div class="report-detail__metric-difference">
                        Différence débit/crédit : {{ $formatAmount($summary['difference_rechargements_envoyes']) }}
                    </div>
                @endif
            </article>
            <article class="report-detail__metric">
                <div class="report-detail__metric-value">{{ $formatAmount($summary['montant_distribue']) }}</div>
                <div class="report-detail__metric-label">Montant crédité aux bénéficiaires</div>
                @if ($hasDifference($summary['difference_rechargements_envoyes']))
                    <div class="report-detail__metric-difference">
                        Différence avec le montant rechargé :
                        {{ $formatAmount($summary['difference_rechargements_envoyes']) }}
                    </div>
                @endif
            </article>
            <article class="report-detail__metric">
                <div class="report-detail__metric-value">{{ $formatAmount($summary['montant_recu']) }}</div>
                <div class="report-detail__metric-label">
                    {{ $summary['rechargements_recus'] }} rechargement(s) reçu(s)
                </div>
                @if ($hasDifference($summary['difference_rechargements_recus']))
                    <div class="report-detail__metric-difference">
                        Différence envoyé/reçu : {{ $formatAmount($summary['difference_rechargements_recus']) }}
                    </div>
                @endif
            </article>
            <article class="report-detail__metric">
                <div class="report-detail__metric-value">{{ $formatAmount($summary['volume_transactions']) }}</div>
                <div class="report-detail__metric-label">{{ $summary['transactions'] }} transaction(s) effectuée(s)</div>
                @if ($hasDifference($summary['difference_transactions']))
                    <div class="report-detail__metric-difference">
                        Différence envoyé/crédité : {{ $formatAmount($summary['difference_transactions']) }}
                    </div>
                @endif
            </article>
            <article class="report-detail__metric">
                <div class="report-detail__metric-value">{{ $formatAmount($summary['volume_transactions_recues']) }}</div>
                <div class="report-detail__metric-label">
                    {{ $summary['transactions_recues'] }} transaction(s) reçue(s)
                </div>
                @if ($hasDifference($summary['difference_transactions_recues']))
                    <div class="report-detail__metric-difference">
                        Différence envoyé/reçu : {{ $formatAmount($summary['difference_transactions_recues']) }}
                    </div>
                @endif
            </article>
        </section>

        <section class="report-detail__section intro-y">
            <header class="report-detail__section-header">
                <div>
                    <h2 class="report-detail__section-title">Rechargements effectués</h2>
                    <p class="report-detail__section-copy">Tous les rechargements envoyés par ce superviseur</p>
                </div>
                <i data-feather="upload-cloud" class="w-5 h-5 text-primary"></i>
            </header>
            <div class="report-detail__table-wrap">
                <table class="report-detail__table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Bénéficiaire</th>
                            <th>Devise du receveur</th>
                            <th class="is-numeric">Montant débité</th>
                            <th class="is-numeric">Montant crédité</th>
                            <th class="is-numeric">Solde superviseur avant</th>
                            <th class="is-numeric">Solde superviseur après</th>
                            <th class="is-numeric">Solde receveur avant</th>
                            <th class="is-numeric">Solde receveur après</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($details['rechargements_envoyes'] as $reload)
                            <tr>
                                <td>{{ $reload['date'] }}</td>
                                <td>{{ $reload['beneficiaire'] }}</td>
                                <td>{{ $reload['devise_receveur'] }}</td>
                                <td class="is-numeric">{{ $formatAmount($reload['montant']) }}</td>
                                <td class="is-numeric">{{ $formatAmount($reload['montant_recu']) }}</td>
                                <td class="is-numeric">{{ $formatBalance($reload['solde_expediteur_avant']) }}</td>
                                <td class="is-numeric">{{ $formatBalance($reload['solde_expediteur_apres']) }}</td>
                                <td class="is-numeric">{{ $formatBalance($reload['solde_receveur_avant']) }}</td>
                                <td class="is-numeric">{{ $formatBalance($reload['solde_receveur_apres']) }}</td>
                                <td>
                                    <span class="report-detail__status report-detail__status--{{ $statusClass($reload['statut']) }}">
                                        {{ $reload['statut'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="report-detail__empty">Aucun rechargement effectué.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="report-detail__section intro-y">
            <header class="report-detail__section-header">
                <div>
                    <h2 class="report-detail__section-title">Rechargements reçus</h2>
                    <p class="report-detail__section-copy">Tous les montants crédités sur le wallet du superviseur</p>
                </div>
                <i data-feather="download-cloud" class="w-5 h-5 text-primary"></i>
            </header>
            <div class="report-detail__table-wrap">
                <table class="report-detail__table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Expéditeur</th>
                            <th>Devise du receveur</th>
                            <th class="is-numeric">Montant envoyé</th>
                            <th class="is-numeric">Montant reçu</th>
                            <th class="is-numeric">Solde receveur avant</th>
                            <th class="is-numeric">Solde receveur après</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($details['rechargements_recus'] as $reload)
                            <tr>
                                <td>{{ $reload['date'] }}</td>
                                <td>{{ $reload['expediteur'] }}</td>
                                <td>{{ $reload['devise_receveur'] }}</td>
                                <td class="is-numeric">{{ $formatAmount($reload['montant']) }}</td>
                                <td class="is-numeric">{{ $formatAmount($reload['montant_recu']) }}</td>
                                <td class="is-numeric">{{ $formatBalance($reload['solde_receveur_avant']) }}</td>
                                <td class="is-numeric">{{ $formatBalance($reload['solde_receveur_apres']) }}</td>
                                <td>
                                    <span class="report-detail__status report-detail__status--{{ $statusClass($reload['statut']) }}">
                                        {{ $reload['statut'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="report-detail__empty">Aucun rechargement reçu.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="report-detail__section intro-y">
            <header class="report-detail__section-header">
                <div>
                    <h2 class="report-detail__section-title">Transactions effectuées</h2>
                    <p class="report-detail__section-copy">Paiements, virements et opérations initiés par le superviseur</p>
                </div>
                <i data-feather="repeat" class="w-5 h-5 text-primary"></i>
            </header>
            <div class="report-detail__table-wrap">
                <table class="report-detail__table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Service</th>
                            <th>Bénéficiaire</th>
                            {{-- <th>Devise du receveur</th> --}}
                            <th class="is-numeric">Montant envoyé</th>
                            <th class="is-numeric">Montant crédité</th>
                            <th class="is-numeric">Solde superviseur avant</th>
                            <th class="is-numeric">Solde superviseur après</th>
                            <th class="is-numeric">Solde receveur avant</th>
                            <th class="is-numeric">Solde receveur après</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($details['transactions'] as $transaction)
                            <tr>
                                <td>{{ $transaction['date'] }}</td>
                                <td>{{ $label($transaction['service']) }}</td>
                                <td>{{ $transaction['beneficiaire'] }}</td>
                                {{-- <td>{{ $transaction['devise_receveur'] }}</td> --}}
                                <td class="is-numeric">{{ $formatAmount($transaction['montant']) }} {{ $transaction['devise_receveur'] }}</td>
                                <td class="is-numeric">{{ $formatAmount($transaction['montant_recu']) }} {{ $transaction['devise_receveur'] }}</td>
                                <td class="is-numeric">{{ $formatBalance($transaction['solde_superviseur_avant']) }}</td>
                                <td class="is-numeric">{{ $formatBalance($transaction['solde_superviseur_apres']) }}</td>
                                <td class="is-numeric">{{ $formatBalance($transaction['solde_receveur_apres']) }}</td>
                                <td class="is-numeric">{{ $formatBalance($transaction['solde_receveur_avant']) }}</td>

                                <td>
                                    <span class="report-detail__status report-detail__status--{{ $statusClass($transaction['statut']) }}">
                                        {{ $transaction['statut'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="12" class="report-detail__empty">Aucune autre transaction.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="report-detail__section intro-y">
            <header class="report-detail__section-header">
                <div>
                    <h2 class="report-detail__section-title">Transactions reçues</h2>
                    <p class="report-detail__section-copy">
                        Transferts et virements dont le wallet du superviseur est bénéficiaire
                    </p>
                </div>
                <i data-feather="log-in" class="w-5 h-5 text-primary"></i>
            </header>
            <div class="report-detail__table-wrap">
                <table class="report-detail__table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Service</th>
                            <th>Expéditeur</th>
                            <th>Description</th>
                            <th>Devise du receveur</th>
                            <th class="is-numeric">Montant envoyé</th>
                            <th class="is-numeric">Montant reçu</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($details['transactions_recues'] as $transaction)
                            <tr>
                                <td>{{ $transaction['date'] }}</td>
                                <td>{{ $label($transaction['service']) }}</td>
                                <td>{{ $transaction['expediteur'] }}</td>
                                <td>{{ $transaction['description'] ?: '—' }}</td>
                                <td>{{ $transaction['devise_receveur'] }}</td>
                                <td class="is-numeric">{{ $formatAmount($transaction['montant']) }}</td>
                                <td class="is-numeric">{{ $formatAmount($transaction['montant_recu']) }}</td>
                                <td>
                                    <span class="report-detail__status report-detail__status--{{ $statusClass($transaction['statut']) }}">
                                        {{ $transaction['statut'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="report-detail__empty">Aucune transaction reçue.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
