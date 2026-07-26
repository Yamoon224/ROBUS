@extends('layouts.master')

@section('title', 'Activité du compte')

@push('styles')
    @include('pages.reports.partials.detail-styles')
@endpush

@section('content')
    @php
        $summary = $details['resume'];
        $actor = $details['actor'] ?? ($actor ?? 'superviseurs');
        $actorLabels = [
            'superviseurs' => 'superviseur',
            'distributeurs' => 'distributeur',
            'sous_distributeurs' => 'sous-distributeur',
        ];
        $actorLabel = $actorLabels[$actor] ?? 'utilisateur';
        $reconciliation = $details['reconciliation'];
        $separateAccounts = $details['comptes_separes'];
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
                        Activité complète du {{ $actorLabel }} · Wallet {{ $details['wallet'] }} · {{ $date }}
                    </p>
                </div>
                <div class="report-detail__actions">
                    <a href="{{ $actor === 'superviseurs'
                            ? route('reports.daily.supervisor.download', ['supervisor' => $details['id'], 'date' => $date])
                            : route('reports.daily.actor.user.download', ['actor' => $actor, 'user' => $details['id'], 'date' => $date]) }}"
                        class="report-detail__button">
                        <i data-feather="download" class="w-4 h-4"></i>
                        Télécharger le PDF
                    </a>
                    <a href="{{ route('reports.daily.actor', ['actor' => $actor, 'date' => $date]) }}"
                        class="report-detail__button">
                        <i data-feather="arrow-left" class="w-4 h-4"></i>
                        Liste des {{ str_replace('_', ' ', $actor) }}
                    </a>
                    <a href="{{ route('reports.daily', ['date' => $date]) }}" class="report-detail__button">
                        <i data-feather="bar-chart-2" class="w-4 h-4"></i>
                        Rapport
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ $actor === 'superviseurs'
                    ? route('reports.daily.supervisor', ['supervisor' => $details['id']])
                    : route('reports.daily.actor.user', ['actor' => $actor, 'user' => $details['id']]) }}"
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
                    <h2 class="report-detail__section-title">Réconciliation du compte principal</h2>
                    <p class="report-detail__section-copy">
                        Solde d’ouverture + entrées − sorties, sans les comptes de commissions séparés
                    </p>
                </div>
                <i data-feather="calculator" class="w-5 h-5 text-primary"></i>
            </header>
            @if ($reconciliation['operations_suspectes'] > 0 || $reconciliation['ruptures_continuite'] > 0)
                <div class="report-detail__alert--danger">
                    <strong>{{ $reconciliation['operations_suspectes'] }} opération(s) réellement suspecte(s)</strong>
                    et <strong>{{ $reconciliation['ruptures_continuite'] }} rupture(s) entre deux opérations</strong>
                    détectée(s). Une rupture ne signifie pas que la transaction suivante est incorrecte.
                </div>
            @endif
            <div class="report-detail__table-wrap">
                <table class="report-detail__table">
                    <tbody>
                        <tr>
                            <td>Solde d’ouverture enregistré</td>
                            <td class="is-numeric font-medium">{{ $formatBalance($reconciliation['solde_ouverture']) }}</td>
                        </tr>
                        <tr>
                            <td>+ Rechargements reçus</td>
                            <td class="is-numeric">{{ $formatAmount($reconciliation['rechargements_recus']) }}</td>
                        </tr>
                        <tr>
                            <td>+ Transactions reçues</td>
                            <td class="is-numeric">{{ $formatAmount($reconciliation['transactions_recues']) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total des entrées</strong></td>
                            <td class="is-numeric"><strong>{{ $formatAmount($reconciliation['total_entrees']) }}</strong></td>
                        </tr>
                        <tr>
                            <td>− Rechargements envoyés</td>
                            <td class="is-numeric">{{ $formatAmount($reconciliation['rechargements_envoyes']) }}</td>
                        </tr>
                        <tr>
                            <td>− Transactions effectuées</td>
                            <td class="is-numeric">{{ $formatAmount($reconciliation['transactions_effectuees']) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total des sorties</strong></td>
                            <td class="is-numeric"><strong>{{ $formatAmount($reconciliation['total_sorties']) }}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Solde de clôture calculé</strong></td>
                            <td class="is-numeric"><strong>{{ $formatBalance($reconciliation['solde_calcule']) }}</strong></td>
                        </tr>
                        <tr>
                            <td>Dernier solde enregistré pour la période</td>
                            <td class="is-numeric">{{ $formatBalance($reconciliation['solde_cloture_enregistre']) }}</td>
                        </tr>
                        <tr>
                            <td>Écart de réconciliation</td>
                            <td class="is-numeric {{ $hasDifference($reconciliation['ecart']) ? 'text-danger font-medium' : '' }}">
                                {{ $formatBalance($reconciliation['ecart']) }}
                            </td>
                        </tr>
                        <tr>
                            <td>Solde principal à la date sélectionnée ({{ $date }})</td>
                            <td class="is-numeric font-medium">
                                {{ $formatBalance($reconciliation['solde_a_la_date']) }} {{ $details['devise'] }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @if ($reconciliation['solde_ouverture'] === null)
                <div class="p-4 text-warning">
                    Le solde d’ouverture n’a pas été enregistré sur les opérations de cette période ;
                    le solde de clôture calculé ne peut donc pas être déterminé.
                </div>
            @endif

            <header class="report-detail__section-header">
                <div>
                    <h3 class="report-detail__section-title">Contrôle par wallet</h3>
                    <p class="report-detail__section-copy">
                        Chaque portefeuille est calculé séparément pour éviter de mélanger ses soldes
                    </p>
                </div>
            </header>
            <div class="report-detail__table-wrap">
                <table class="report-detail__table">
                    <thead>
                        <tr>
                            <th>Wallet</th>
                            <th class="is-numeric">Opérations</th>
                            <th class="is-numeric">Ouverture</th>
                            <th class="is-numeric">Entrées</th>
                            <th class="is-numeric">Sorties</th>
                            <th class="is-numeric">Clôture calculée</th>
                            <th class="is-numeric">Clôture enregistrée</th>
                            <th class="is-numeric">Manquant / écart</th>
                            <th class="is-numeric">Solde à la date</th>
                            <th>Source du solde</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reconciliation['audit_wallets'] as $walletAudit)
                            <tr>
                                <td>{{ $walletAudit['wallet'] }} · {{ $walletAudit['devise'] }}</td>
                                <td class="is-numeric">{{ $walletAudit['operations'] }}</td>
                                <td class="is-numeric">{{ $formatBalance($walletAudit['solde_ouverture']) }}</td>
                                <td class="is-numeric">{{ $formatAmount($walletAudit['entrees']) }}</td>
                                <td class="is-numeric">{{ $formatAmount($walletAudit['sorties']) }}</td>
                                <td class="is-numeric">{{ $formatBalance($walletAudit['solde_calcule']) }}</td>
                                <td class="is-numeric">{{ $formatBalance($walletAudit['solde_cloture']) }}</td>
                                <td class="is-numeric font-medium">{{ $formatBalance($walletAudit['ecart']) }}</td>
                                <td class="is-numeric">{{ $formatBalance($walletAudit['solde_a_la_date']) }}</td>
                                <td>
                                    {{ $walletAudit['source_solde'] ?: '—' }}
                                    @if ($walletAudit['date_solde'])
                                        <br><small>{{ $walletAudit['date_solde'] }}</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="report-detail__empty">Aucun wallet actif pour cette période.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <header class="report-detail__section-header">
                <div>
                    <h3 class="report-detail__section-title">Opérations à contrôler et ruptures de continuité</h3>
                    <p class="report-detail__section-copy">
                        Vérification avant/après et continuité. Écart positif : entrée non expliquée ;
                        écart négatif : sortie non expliquée.
                    </p>
                </div>
            </header>
            <div class="report-detail__table-wrap">
                <table class="report-detail__table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Wallet</th>
                            <th>Anomalie</th>
                            <th>Opération</th>
                            <th>Type transaction</th>
                            <th>Type reçu</th>
                            <th>Référence</th>
                            <th class="is-numeric">Montant</th>
                            <th class="is-numeric">Solde attendu</th>
                            <th class="is-numeric">Solde observé</th>
                            <th class="is-numeric">Manquant / écart</th>
                            <th>Détails</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reconciliation['anomalies'] as $anomaly)
                            @php
                                $auditWallet = collect($reconciliation['audit_wallets'])
                                    ->firstWhere('wallet_id', $anomaly['wallet_id']);
                            @endphp
                            <tr class="{{ $anomaly['affecte_operation']
                                ? 'report-detail__row--suspicious'
                                : 'report-detail__row--continuity' }}">
                                <td>{{ $anomaly['date'] }}</td>
                                <td>{{ $auditWallet['wallet'] ?? '#'.$anomaly['wallet_id'] }}</td>
                                <td>
                                    {{ $anomaly['type_anomalie'] }}
                                    <br>
                                    <small>
                                        {{ $anomaly['affecte_operation']
                                            ? 'La transaction elle-même est à contrôler'
                                            : 'Intervalle suspect — transaction non mise en cause' }}
                                    </small>
                                </td>
                                <td>{{ $anomaly['operation'] }}</td>
                                <td>{{ $anomaly['type_transaction'] ?: '—' }}</td>
                                <td>{{ $anomaly['type_received'] ?: '—' }}</td>
                                <td>{{ $anomaly['reference'] }}</td>
                                <td class="is-numeric">{{ $formatAmount($anomaly['montant']) }}</td>
                                <td class="is-numeric">{{ $formatAmount($anomaly['attendu']) }}</td>
                                <td class="is-numeric">{{ $formatAmount($anomaly['observe']) }}</td>
                                <td class="is-numeric font-medium">{{ $formatAmount($anomaly['ecart']) }}</td>
                                <td>
                                    <details class="report-detail__cause">
                                        <summary>Voir la cause</summary>
                                        <div class="report-detail__cause-panel">
                                            <div><strong>Diagnostic :</strong> {{ $anomaly['cause_probable'] }}</div>
                                            <div><strong>Contrôle :</strong> {{ $anomaly['formule'] }}</div>
                                            @if ($anomaly['operation_precedente'])
                                                <div>
                                                    <strong>Important :</strong>
                                                    {{ $anomaly['reference'] }} marque la fin de l’intervalle contrôlé ;
                                                    cette opération n’est pas déclarée incorrecte.
                                                </div>
                                                <div>
                                                    <strong>Comparaison :</strong>
                                                    solde précédent {{ $formatBalance($anomaly['attendu']) }}
                                                    → nouveau solde avant {{ $formatBalance($anomaly['observe']) }}
                                                </div>
                                            @else
                                                <div>
                                                    <strong>Calcul :</strong>
                                                    {{ $formatBalance($anomaly['avant']) }}
                                                    {{ $anomaly['direction'] === 'entree' ? '+' : '−' }}
                                                    {{ $formatAmount($anomaly['montant']) }}
                                                    = {{ $formatAmount($anomaly['attendu']) }}
                                                </div>
                                            @endif
                                            <div>
                                                <strong>Variation réellement observée :</strong>
                                                {{ $formatAmount($anomaly['variation_observee']) }}
                                            </div>
                                            @if ($anomaly['operation_precedente'])
                                                <div>
                                                    <strong>Opération précédente :</strong>
                                                    {{ $anomaly['operation_precedente']['reference'] }}
                                                    · {{ $anomaly['operation_precedente']['date'] }}
                                                    · solde après
                                                    {{ $formatBalance($anomaly['operation_precedente']['solde_apres']) }}
                                                </div>
                                                <div>
                                                    <strong>Intervalle à rechercher :</strong>
                                                    après {{ $anomaly['operation_precedente']['date'] }}
                                                    et avant {{ $anomaly['date'] }}.
                                                </div>
                                            @endif
                                            <div>
                                                <strong>À vérifier :</strong> la référence {{ $anomaly['reference'] }},
                                                les écritures créées à la même heure et toute modification manuelle du wallet.
                                            </div>
                                        </div>
                                    </details>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="report-detail__empty">
                                    Aucun écart détecté dans les soldes enregistrés.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($reconciliation['mouvements_non_rattaches'] > 0)
                <div class="p-4 text-warning">
                    {{ $reconciliation['mouvements_non_rattaches'] }} mouvement(s) n’ont pas pu être rattachés
                    à un wallet du {{ $actorLabel }}. Ils doivent être vérifiés en priorité.
                </div>
            @endif
        </section>

        <section class="report-detail__section intro-y">
            <header class="report-detail__section-header">
                <div>
                    <h2 class="report-detail__section-title">Rechargements effectués</h2>
                    <p class="report-detail__section-copy">Tous les rechargements envoyés par ce {{ $actorLabel }}</p>
                </div>
                <i data-feather="upload-cloud" class="w-5 h-5 text-primary"></i>
            </header>
            <div class="report-detail__table-wrap">
                <table class="report-detail__table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Bénéficiaire</th>
                            <th>Type transaction</th>
                            <th>Type reçu</th>
                            <th>Devise du receveur</th>
                            <th class="is-numeric">Montant débité</th>
                            <th class="is-numeric">Montant crédité</th>
                            <th class="is-numeric">Solde {{ $actorLabel }} avant</th>
                            <th class="is-numeric">Solde {{ $actorLabel }} après</th>
                            <th class="is-numeric">Solde receveur avant</th>
                            <th class="is-numeric">Solde receveur après</th>
                            <th>Statut</th>
                            <th>Contrôle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($details['rechargements_envoyes'] as $reload)
                            <tr class="{{ $reload['suspecte'] ? 'report-detail__row--suspicious' : '' }}">
                                <td>{{ $reload['date'] }}</td>
                                <td>{{ $reload['beneficiaire'] }}</td>
                                <td>{{ $reload['type_operation'] ?: '—' }}</td>
                                <td>{{ $reload['type_received'] ?: '—' }}</td>
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
                                <td>
                                    @if ($reload['suspecte'])
                                        <span class="report-detail__suspicious-badge">
                                            Suspecte · écart {{ $formatAmount($reload['ecart_suspect']) }}
                                        </span>
                                        <br><small>{{ $reload['raison_suspecte'] }}</small>
                                    @else
                                        Conforme
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="13" class="report-detail__empty">Aucun rechargement effectué.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="report-detail__section intro-y">
            <header class="report-detail__section-header">
                <div>
                    <h2 class="report-detail__section-title">Rechargements reçus</h2>
                    <p class="report-detail__section-copy">Tous les montants crédités sur le wallet du {{ $actorLabel }}</p>
                </div>
                <i data-feather="download-cloud" class="w-5 h-5 text-primary"></i>
            </header>
            <div class="report-detail__table-wrap">
                <table class="report-detail__table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Expéditeur</th>
                            <th>Type transaction</th>
                            <th>Type reçu</th>
                            <th>Devise du receveur</th>
                            <th class="is-numeric">Montant envoyé</th>
                            <th class="is-numeric">Montant reçu</th>
                            <th class="is-numeric">Solde receveur avant</th>
                            <th class="is-numeric">Solde receveur après</th>
                            <th>Statut</th>
                            <th>Contrôle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($details['rechargements_recus'] as $reload)
                            <tr class="{{ $reload['suspecte'] ? 'report-detail__row--suspicious' : '' }}">
                                <td>{{ $reload['date'] }}</td>
                                <td>{{ $reload['expediteur'] }}</td>
                                <td>{{ $reload['type_operation'] ?: '—' }}</td>
                                <td>{{ $reload['type_received'] ?: '—' }}</td>
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
                                <td>
                                    @if ($reload['suspecte'])
                                        <span class="report-detail__suspicious-badge">
                                            Suspecte · écart {{ $formatAmount($reload['ecart_suspect']) }}
                                        </span>
                                        <br><small>{{ $reload['raison_suspecte'] }}</small>
                                    @else
                                        Conforme
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="11" class="report-detail__empty">Aucun rechargement reçu.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="report-detail__section intro-y">
            <header class="report-detail__section-header">
                <div>
                    <h2 class="report-detail__section-title">Transactions effectuées</h2>
                    <p class="report-detail__section-copy">Paiements, virements et opérations initiés par le {{ $actorLabel }}</p>
                </div>
                <i data-feather="repeat" class="w-5 h-5 text-primary"></i>
            </header>
            <div class="report-detail__table-wrap">
                <table class="report-detail__table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Service</th>
                            <th>Type transaction</th>
                            <th>Type reçu</th>
                            <th>Bénéficiaire</th>
                            {{-- <th>Devise du receveur</th> --}}
                            <th class="is-numeric">Montant envoyé</th>
                            <th class="is-numeric">Montant crédité</th>
                            <th class="is-numeric">Solde {{ $actorLabel }} avant</th>
                            <th class="is-numeric">Solde {{ $actorLabel }} après</th>
                            <th class="is-numeric">Solde receveur avant</th>
                            <th class="is-numeric">Solde receveur après</th>
                            <th>Statut</th>
                            <th>Contrôle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($details['transactions'] as $transaction)
                            <tr class="{{ $transaction['suspecte'] ? 'report-detail__row--suspicious' : '' }}">
                                <td>{{ $transaction['date'] }}</td>
                                <td>{{ $label($transaction['service']) }}</td>
                                <td>{{ $transaction['type_operation'] ?: '—' }}</td>
                                <td>{{ $transaction['type_received'] ?: '—' }}</td>
                                <td>{{ $transaction['beneficiaire'] }}</td>
                                {{-- <td>{{ $transaction['devise_receveur'] }}</td> --}}
                                <td class="is-numeric">{{ $formatAmount($transaction['montant']) }} {{ $transaction['devise_receveur'] }}</td>
                                <td class="is-numeric">{{ $formatAmount($transaction['montant_recu']) }} {{ $transaction['devise_receveur'] }}</td>
                                <td class="is-numeric">{{ $formatBalance($transaction['solde_superviseur_avant']) }}</td>
                                <td class="is-numeric">{{ $formatBalance($transaction['solde_superviseur_apres']) }}</td>
                                <td class="is-numeric">{{ $formatBalance($transaction['solde_receveur_avant']) }}</td>
                                <td class="is-numeric">{{ $formatBalance($transaction['solde_receveur_apres']) }}</td>

                                <td>
                                    <span class="report-detail__status report-detail__status--{{ $statusClass($transaction['statut']) }}">
                                        {{ $transaction['statut'] }}
                                    </span>
                                </td>
                                <td>
                                    @if ($transaction['suspecte'])
                                        <span class="report-detail__suspicious-badge">
                                            Suspecte · écart {{ $formatAmount($transaction['ecart_suspect']) }}
                                        </span>
                                        <br><small>{{ $transaction['raison_suspecte'] }}</small>
                                    @else
                                        Conforme
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="13" class="report-detail__empty">Aucune autre transaction.</td></tr>
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
                        Transferts et virements dont le wallet du {{ $actorLabel }} est bénéficiaire
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
                            <th>Type transaction</th>
                            <th>Type reçu</th>
                            <th>Référence</th>
                            <th>Expéditeur</th>
                            <th>Description</th>
                            <th>Devise du receveur</th>
                            <th class="is-numeric">Montant envoyé</th>
                            <th class="is-numeric">Montant reçu</th>
                            <th class="is-numeric">Solde {{ $actorLabel }} avant</th>
                            <th class="is-numeric">Solde {{ $actorLabel }} après</th>
                            <th>Statut</th>
                            <th>Contrôle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($details['transactions_recues'] as $transaction)
                            <tr class="{{ $transaction['suspecte'] ? 'report-detail__row--suspicious' : '' }}">
                                <td>{{ $transaction['date'] }}</td>
                                <td>{{ $label($transaction['service']) }}</td>
                                <td>{{ $transaction['type_operation'] ?: '—' }}</td>
                                <td>{{ $transaction['type_received'] ?: '—' }}</td>
                                <td>{{ $transaction['reference_operation'] }}</td>
                                <td>{{ $transaction['expediteur'] }}</td>
                                <td>{{ $transaction['description'] ?: '—' }}</td>
                                <td>{{ $transaction['devise_receveur'] }}</td>
                                <td class="is-numeric">{{ $formatAmount($transaction['montant']) }}</td>
                                <td class="is-numeric">{{ $formatAmount($transaction['montant_recu']) }}</td>
                                <td class="is-numeric">{{ $formatBalance($transaction['solde_receveur_avant']) }}</td>
                                <td class="is-numeric">
                                    {{ $formatBalance($transaction['solde_receveur_apres']) }}
                                    @if ($transaction['solde_reconstitue'] ?? false)
                                        <br><small>Solde reconstitué</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="report-detail__status report-detail__status--{{ $statusClass($transaction['statut']) }}">
                                        {{ $transaction['statut'] }}
                                    </span>
                                </td>
                                <td>
                                    @if ($transaction['suspecte'])
                                        <span class="report-detail__suspicious-badge">
                                            Suspecte · écart {{ $formatAmount($transaction['ecart_suspect']) }}
                                        </span>
                                        <br><small>{{ $transaction['raison_suspecte'] }}</small>
                                    @else
                                        Conforme
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="14" class="report-detail__empty">Aucune transaction reçue.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
