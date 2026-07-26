@extends('layouts.master')

@section('title', 'Rapport journalier')

@push('styles')
    <style>
        .daily-report {
            --report-primary: #2563eb;
            --report-primary-dark: #1d4ed8;
            --report-success: #059669;
            --report-warning: #d97706;
            --report-danger: #dc2626;
            --report-ink: #0f172a;
            --report-muted: #64748b;
            --report-line: #e2e8f0;
            --report-surface: #ffffff;
        }

        .daily-report__hero {
            position: relative;
            overflow: hidden;
            padding: 2rem;
            border-radius: 1.25rem;
            color: #fff;
            background:
                radial-gradient(circle at 88% 15%, rgba(255, 255, 255, .2), transparent 24%),
                radial-gradient(circle at 72% 115%, rgba(14, 165, 233, .55), transparent 34%),
                linear-gradient(125deg, #0f172a 0%, #1e3a8a 58%, #2563eb 100%);
            box-shadow: 0 20px 45px rgba(30, 64, 175, .18);
        }

        .daily-report__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .4rem .7rem;
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 999px;
            background: rgba(255, 255, 255, .12);
            color: rgba(255, 255, 255, .86);
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .daily-report__hero-title {
            margin-top: .9rem;
            font-size: clamp(1.7rem, 3vw, 2.4rem);
            font-weight: 700;
            line-height: 1.15;
        }

        .daily-report__hero-copy {
            max-width: 42rem;
            margin-top: .65rem;
            color: rgba(255, 255, 255, .72);
        }

        .daily-report__hero-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: end;
            gap: .75rem;
            margin-top: 1.5rem;
        }

        .daily-report__date-field {
            min-width: 220px;
        }

        .daily-report__date-field label {
            display: block;
            margin-bottom: .4rem;
            color: rgba(255, 255, 255, .78);
            font-size: .75rem;
            font-weight: 600;
        }

        .daily-report__date-input {
            width: 100%;
            min-height: 42px;
            padding: .65rem .8rem;
            border: 1px solid rgba(255, 255, 255, .24);
            border-radius: .7rem;
            outline: none;
            color: #fff;
            background: rgba(15, 23, 42, .42);
            color-scheme: dark;
        }

        .daily-report__hero-button {
            display: inline-flex;
            min-height: 42px;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            padding: .65rem 1rem;
            border-radius: .7rem;
            font-weight: 600;
            transition: transform .18s ease, background-color .18s ease;
        }

        .daily-report__hero-button:hover {
            transform: translateY(-1px);
        }

        .daily-report__hero-button--primary {
            color: var(--report-primary-dark);
            background: #fff;
        }

        .daily-report__hero-button--ghost {
            border: 1px solid rgba(255, 255, 255, .22);
            color: #fff;
            background: rgba(255, 255, 255, .1);
        }

        .daily-report__section {
            margin-top: 1.5rem;
        }

        .daily-report__section-heading {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: .85rem;
        }

        .daily-report__section-title {
            color: var(--report-ink);
            font-size: 1rem;
            font-weight: 700;
        }

        .daily-report__section-copy {
            margin-top: .2rem;
            color: var(--report-muted);
            font-size: .8rem;
        }

        .daily-report__grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
        }

        .daily-report__metric {
            position: relative;
            overflow: hidden;
            min-height: 145px;
            padding: 1.15rem;
            border: 1px solid var(--report-line);
            border-radius: 1rem;
            background: var(--report-surface);
            box-shadow: 0 10px 28px rgba(15, 23, 42, .055);
        }

        .daily-report__metric::after {
            position: absolute;
            right: -30px;
            bottom: -48px;
            width: 105px;
            height: 105px;
            border-radius: 999px;
            background: var(--metric-soft);
            content: '';
        }

        .daily-report__metric-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
        }

        .daily-report__metric-icon {
            display: inline-flex;
            width: 42px;
            height: 42px;
            align-items: center;
            justify-content: center;
            border-radius: .8rem;
            color: var(--metric-color);
            background: var(--metric-soft);
        }

        .daily-report__metric-value {
            position: relative;
            z-index: 1;
            margin-top: 1rem;
            color: var(--report-ink);
            font-size: 1.65rem;
            font-weight: 700;
            letter-spacing: -.025em;
        }

        .daily-report__metric-label {
            position: relative;
            z-index: 1;
            margin-top: .25rem;
            color: var(--report-muted);
            font-size: .82rem;
        }

        .daily-report__metric--blue {
            --metric-color: #2563eb;
            --metric-soft: #dbeafe;
        }

        .daily-report__metric--green {
            --metric-color: #059669;
            --metric-soft: #d1fae5;
        }

        .daily-report__metric--amber {
            --metric-color: #d97706;
            --metric-soft: #fef3c7;
        }

        .daily-report__metric--red {
            --metric-color: #dc2626;
            --metric-soft: #fee2e2;
        }

        .daily-report__commission-panel {
            padding: 1.25rem;
            border: 1px solid #dbeafe;
            border-radius: 1rem;
            background: linear-gradient(145deg, #eff6ff 0%, #fff 62%);
        }

        .daily-report__supervisor-panel {
            padding: 1.25rem;
            border: 1px solid #ede9fe;
            border-radius: 1rem;
            background: linear-gradient(145deg, #f5f3ff 0%, #fff 58%);
        }

        .daily-report__commission {
            padding: 1rem;
            border: 1px solid rgba(148, 163, 184, .22);
            border-radius: .85rem;
            background: rgba(255, 255, 255, .82);
        }

        .daily-report__commission-label {
            display: flex;
            align-items: center;
            gap: .5rem;
            color: var(--report-muted);
            font-size: .8rem;
            font-weight: 600;
        }

        .daily-report__commission-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--commission-color);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .08);
        }

        .daily-report__commission-value {
            margin-top: .65rem;
            color: var(--report-ink);
            font-size: 1.35rem;
            font-weight: 700;
        }

        .daily-report__commission--sd {
            --commission-color: #8b5cf6;
        }

        .daily-report__commission--company {
            --commission-color: #0ea5e9;
        }

        .daily-report__commission--admin {
            --commission-color: #f59e0b;
        }

        .daily-report__commission--total {
            --commission-color: #10b981;
            color: #fff;
            background: linear-gradient(135deg, #047857, #10b981);
        }

        .daily-report__commission--total .daily-report__commission-label,
        .daily-report__commission--total .daily-report__commission-value {
            color: #fff;
        }

        .daily-report__tables {
            display: grid;
            grid-template-columns: minmax(0, .8fr) minmax(0, 1.2fr);
            gap: 1rem;
        }

        .daily-report__panel {
            overflow: hidden;
            border: 1px solid var(--report-line);
            border-radius: 1rem;
            background: var(--report-surface);
            box-shadow: 0 10px 28px rgba(15, 23, 42, .045);
        }

        .daily-report__panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.15rem;
            border-bottom: 1px solid var(--report-line);
        }

        .daily-report__panel-title {
            color: var(--report-ink);
            font-size: .9rem;
            font-weight: 700;
        }

        .daily-report__badge {
            display: inline-flex;
            align-items: center;
            padding: .28rem .6rem;
            border-radius: 999px;
            color: #1d4ed8;
            background: #dbeafe;
            font-size: .7rem;
            font-weight: 700;
        }

        .daily-report__table-wrap {
            overflow-x: auto;
        }

        .daily-report__table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }

        .daily-report__table th {
            padding: .75rem 1rem;
            color: var(--report-muted);
            background: #f8fafc;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .035em;
            text-align: left;
            text-transform: uppercase;
        }

        .daily-report__table td {
            padding: .85rem 1rem;
            border-top: 1px solid #f1f5f9;
            color: #334155;
            font-size: .8rem;
        }

        .daily-report__table tbody tr {
            transition: background-color .16s ease;
        }

        .daily-report__table tbody tr:hover {
            background: #f8fafc;
        }

        .daily-report__table .is-numeric {
            text-align: right;
            font-variant-numeric: tabular-nums;
        }

        .daily-report__actor {
            display: flex;
            align-items: center;
            gap: .6rem;
            font-weight: 600;
        }

        .daily-report__actor-link {
            color: inherit;
        }

        .daily-report__actor-link:hover {
            color: var(--report-primary);
        }

        .daily-report__actor-avatar {
            display: inline-flex;
            width: 30px;
            height: 30px;
            align-items: center;
            justify-content: center;
            border-radius: .65rem;
            color: #1d4ed8;
            background: #eff6ff;
            font-size: .72rem;
        }

        .daily-report__empty {
            padding: 2.5rem 1rem !important;
            color: var(--report-muted) !important;
            text-align: center !important;
        }

        @media (max-width: 1023px) {
            .daily-report__grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .daily-report__tables {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 639px) {
            .daily-report__hero {
                padding: 1.35rem;
                border-radius: 1rem;
            }

            .daily-report__hero-actions,
            .daily-report__date-field,
            .daily-report__hero-button {
                width: 100%;
            }

            .daily-report__grid {
                grid-template-columns: 1fr;
            }

            .daily-report__metric {
                min-height: 132px;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $summary = $report['resume']['tous_les_mouvements'];
        $commissions = $report['resume']['commissions'];
        $supervisors = $report['superviseurs'];
        $formatAmount = static fn ($amount) => number_format((float) $amount, 2, ',', ' ');
        $label = static fn ($value) => ucfirst(str_replace('_', ' ', $value));
        $actorInitials = static fn ($value) => strtoupper(substr(str_replace('_', '', $value), 0, 2));
    @endphp

    <div class="daily-report py-6">
        <section class="daily-report__hero intro-y">
            <span class="daily-report__eyebrow">
                <i data-feather="activity" class="w-3 h-3"></i>
                Vue financière
            </span>

            <h1 class="daily-report__hero-title">Rapport journalier</h1>
            <p class="daily-report__hero-copy">
                Suivez les rechargements, les transactions et la distribution des commissions depuis une vue unique.
            </p>

            <form method="GET" action="{{ route('reports.daily') }}" class="daily-report__hero-actions">
                <div class="daily-report__date-field">
                    <label for="report-date">Date du rapport</label>
                    <input
                        id="report-date"
                        name="date"
                        type="date"
                        value="{{ $report['date'] }}"
                        max="{{ now()->toDateString() }}"
                        class="daily-report__date-input"
                        required
                    >
                </div>

                <button type="submit" class="daily-report__hero-button daily-report__hero-button--primary">
                    <i data-feather="filter" class="w-4 h-4"></i>
                    Actualiser
                </button>

                <a href="{{ route('transactions.uv.browse') }}"
                    class="daily-report__hero-button daily-report__hero-button--ghost">
                    <i data-feather="arrow-left" class="w-4 h-4"></i>
                    Transactions UV
                </a>
            </form>

            @error('date')
                <div class="mt-3 text-sm text-red-200">{{ $message }}</div>
            @enderror
        </section>

        <section class="daily-report__section">
            <div class="daily-report__section-heading">
                <div>
                    <h2 class="daily-report__section-title">Vue d’ensemble</h2>
                    <p class="daily-report__section-copy">Activité enregistrée le {{ $report['date'] }}</p>
                </div>
                <span class="daily-report__badge">{{ $summary['nombre'] }} opération(s)</span>
            </div>

            <div class="daily-report__grid">
                @foreach ([
                    ['label' => 'Volume réussi', 'value' => $formatAmount($summary['volume_reussi']), 'icon' => 'trending-up', 'theme' => 'blue'],
                    ['label' => 'Opérations réussies', 'value' => $summary['reussies'], 'icon' => 'check-circle', 'theme' => 'green'],
                    ['label' => 'En attente', 'value' => $summary['en_attente'], 'icon' => 'clock', 'theme' => 'amber'],
                    ['label' => 'Opérations échouées', 'value' => $summary['echouees'], 'icon' => 'x-circle', 'theme' => 'red'],
                ] as $card)
                    <article class="daily-report__metric daily-report__metric--{{ $card['theme'] }} intro-y">
                        <div class="daily-report__metric-top">
                            <span class="daily-report__metric-icon">
                                <i data-feather="{{ $card['icon'] }}" class="w-5 h-5"></i>
                            </span>
                        </div>
                        <div class="daily-report__metric-value">{{ $card['value'] }}</div>
                        <div class="daily-report__metric-label">{{ $card['label'] }}</div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="daily-report__section daily-report__supervisor-panel intro-y">
            <div class="daily-report__section-heading">
                <div>
                    <h2 class="daily-report__section-title">Activité des superviseurs</h2>
                    <p class="daily-report__section-copy">
                        Distinction entre les montants débités, distribués et reçus
                    </p>
                </div>
                <span class="daily-report__badge">{{ $supervisors['nombre_actifs'] }} actif(s)</span>
            </div>

            <div class="daily-report__grid">
                @foreach ([
                    [
                        'label' => 'Montant débité pour recharger',
                        'value' => $formatAmount($supervisors['montant_recharge']),
                        'icon' => 'upload-cloud',
                        'theme' => 'blue',
                    ],
                    [
                        'label' => 'Montant crédité aux bénéficiaires',
                        'value' => $formatAmount($supervisors['montant_distribue']),
                        'icon' => 'send',
                        'theme' => 'green',
                    ],
                    [
                        'label' => 'Montant reçu par les superviseurs',
                        'value' => $formatAmount($supervisors['montant_recu']),
                        'icon' => 'download-cloud',
                        'theme' => 'amber',
                    ],
                    [
                        'label' => $supervisors['operations_rechargement'].' rechargement(s) · '.$supervisors['operations_recues'].' réception(s)',
                        'value' => $supervisors['nombre_actifs'],
                        'icon' => 'user-check',
                        'theme' => 'red',
                    ],
                ] as $card)
                    <article class="daily-report__metric daily-report__metric--{{ $card['theme'] }}">
                        <div class="daily-report__metric-top">
                            <span class="daily-report__metric-icon">
                                <i data-feather="{{ $card['icon'] }}" class="w-5 h-5"></i>
                            </span>
                        </div>
                        <div class="daily-report__metric-value">{{ $card['value'] }}</div>
                        <div class="daily-report__metric-label">{{ $card['label'] }}</div>
                    </article>
                @endforeach
            </div>

            <div class="daily-report__panel mt-4">
                <header class="daily-report__panel-header">
                    <div>
                        <h3 class="daily-report__panel-title">Détail individuel des superviseurs</h3>
                        <p class="daily-report__section-copy">Uniquement les opérations UV réussies de la date sélectionnée</p>
                    </div>
                    <i data-feather="briefcase" class="w-5 h-5 text-primary"></i>
                </header>

                <div class="daily-report__table-wrap">
                    <table class="daily-report__table">
                        <thead>
                            <tr>
                                <th>Superviseur</th>
                                <th class="is-numeric">Rechargements</th>
                                <th class="is-numeric">Montant débité</th>
                                <th class="is-numeric">Montant distribué</th>
                                <th class="is-numeric">Réceptions</th>
                                <th class="is-numeric">Montant reçu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($supervisors['details'] as $supervisor)
                                <tr>
                                    <td>
                                        <div class="daily-report__actor">
                                            <span class="daily-report__actor-avatar">
                                                {{ strtoupper(substr($supervisor['nom'], 0, 2)) }}
                                            </span>
                                            {{ $supervisor['nom'] }}
                                        </div>
                                    </td>
                                    <td class="is-numeric">{{ $supervisor['operations_rechargement'] }}</td>
                                    <td class="is-numeric">{{ $formatAmount($supervisor['montant_recharge']) }}</td>
                                    <td class="is-numeric text-success">{{ $formatAmount($supervisor['montant_distribue']) }}</td>
                                    <td class="is-numeric">{{ $supervisor['operations_recues'] }}</td>
                                    <td class="is-numeric text-warning">{{ $formatAmount($supervisor['montant_recu']) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="daily-report__empty">
                                        Aucune activité superviseur pour cette date
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="daily-report__section daily-report__commission-panel intro-y">
            <div class="daily-report__section-heading">
                <div>
                    <h2 class="daily-report__section-title">Distribution des commissions</h2>
                    <p class="daily-report__section-copy">
                        {{ $commissions['transactions_commissionnees'] }} transaction(s) commissionnée(s)
                    </p>
                </div>
            </div>

            <div class="daily-report__grid">
                @foreach ([
                    ['label' => 'Sous-distributeurs', 'value' => $commissions['sous_distributeurs'], 'theme' => 'sd'],
                    ['label' => 'Compagnies', 'value' => $commissions['compagnies'], 'theme' => 'company'],
                    ['label' => 'Administrateur', 'value' => $commissions['admin'], 'theme' => 'admin'],
                    ['label' => 'Total commissions', 'value' => $commissions['total'], 'theme' => 'total'],
                ] as $commission)
                    <article class="daily-report__commission daily-report__commission--{{ $commission['theme'] }}">
                        <div class="daily-report__commission-label">
                            <span class="daily-report__commission-dot"></span>
                            {{ $commission['label'] }}
                        </div>
                        <div class="daily-report__commission-value">{{ $formatAmount($commission['value']) }}</div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="daily-report__section daily-report__tables">
            <article class="daily-report__panel intro-y">
                <header class="daily-report__panel-header">
                    <div>
                        <h2 class="daily-report__panel-title">Mouvements par acteur</h2>
                        <p class="daily-report__section-copy">Répartition des volumes réussis</p>
                    </div>
                    <i data-feather="users" class="w-5 h-5 text-primary"></i>
                </header>

                <div class="daily-report__table-wrap">
                    <table class="daily-report__table">
                        <thead>
                            <tr>
                                <th>Acteur</th>
                                <th class="is-numeric">Nombre</th>
                                <th class="is-numeric">Volume réussi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($report['par_acteur'] as $actor => $values)
                                <tr>
                                    <td>
                                        <a
                                            href="{{ route('reports.daily.actor', ['actor' => $actor, 'date' => $report['date']]) }}"
                                            class="daily-report__actor daily-report__actor-link"
                                            title="Voir les utilisateurs ayant reçu des rechargements"
                                        >
                                            <span class="daily-report__actor-avatar">{{ $actorInitials($actor) }}</span>
                                            <span>{{ $label($actor) }}</span>
                                            <i data-feather="chevron-right" class="w-4 h-4 ml-auto"></i>
                                        </a>
                                    </td>
                                    <td class="is-numeric">{{ $values['nombre'] }}</td>
                                    <td class="is-numeric">{{ $formatAmount($values['volume_reussi']) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="daily-report__empty">Aucun mouvement pour cette date</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="daily-report__panel intro-y">
                <header class="daily-report__panel-header">
                    <div>
                        <h2 class="daily-report__panel-title">Détail par service</h2>
                        <p class="daily-report__section-copy">Performance de chaque canal transactionnel</p>
                    </div>
                    <i data-feather="layers" class="w-5 h-5 text-primary"></i>
                </header>

                <div class="daily-report__table-wrap">
                    <table class="daily-report__table">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th class="is-numeric">Total</th>
                                <th class="is-numeric">Réussies</th>
                                <th class="is-numeric">Attente</th>
                                <th class="is-numeric">Échecs</th>
                                <th class="is-numeric">Volume</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($report['par_service'] as $service => $values)
                                <tr>
                                    <td class="font-medium">{{ $label($service) }}</td>
                                    <td class="is-numeric">{{ $values['nombre'] }}</td>
                                    <td class="is-numeric text-success">{{ $values['reussies'] }}</td>
                                    <td class="is-numeric text-warning">{{ $values['en_attente'] }}</td>
                                    <td class="is-numeric text-danger">{{ $values['echouees'] }}</td>
                                    <td class="is-numeric font-medium">{{ $formatAmount($values['volume_reussi']) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="daily-report__empty">Aucune donnée disponible</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
        </section>
    </div>
@endsection
