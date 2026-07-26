<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport d'activité</title>
    <style>
        @page { margin: 20px; }
        body { color: #1e293b; font-family: DejaVu Sans, sans-serif; font-size: 8px; }
        h1 { margin: 0 0 3px; color: #0f172a; font-size: 18px; }
        h2 { margin: 16px 0 6px; color: #1e40af; font-size: 12px; }
        .subtitle { margin-bottom: 12px; color: #64748b; }
        .metrics { width: 100%; margin-bottom: 12px; border-collapse: separate; border-spacing: 5px; }
        .metrics td { padding: 8px; border: 1px solid #cbd5e1; background: #f8fafc; }
        .metric-value { color: #0f172a; font-size: 13px; font-weight: bold; }
        .metric-label { color: #64748b; font-size: 7px; }
        table.data { width: 100%; border-collapse: collapse; }
        .data th, .data td { padding: 5px; border: 1px solid #cbd5e1; }
        .data th { color: #fff; background: #1e40af; font-size: 6px; text-align: left; text-transform: uppercase; }
        .number { text-align: right; }
        .empty { padding: 12px; color: #64748b; text-align: center; }
        .danger { color: #b91c1c; font-weight: bold; }
        .suspicious td { color: #991b1b; background: #fee2e2; }
        .continuity td { color: #92400e; background: #fffbeb; }
        .alert { margin: 8px 0; padding: 7px; border: 1px solid #fca5a5; color: #991b1b; background: #fef2f2; }
    </style>
</head>
<body>
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
        $label = static fn ($value) => ucfirst(str_replace('_', ' ', $value));
    @endphp

    <h1>{{ $details['nom'] }}</h1>
    <div class="subtitle">
        Rapport du {{ $actorLabel }} du {{ $date }} · Wallet {{ $details['wallet'] ?: 'non identifié' }}
        · Généré le {{ now()->format('d/m/Y H:i') }}
    </div>

    <table class="metrics">
        <tr>
            <td><div class="metric-value">{{ $formatAmount($summary['montant_recharge']) }}</div><div class="metric-label">{{ $summary['rechargements_envoyes'] }} rechargement(s) envoyé(s)</div></td>
            <td><div class="metric-value">{{ $formatAmount($summary['montant_distribue']) }}</div><div class="metric-label">Montant crédité</div></td>
            <td><div class="metric-value">{{ $formatAmount($summary['montant_recu']) }}</div><div class="metric-label">{{ $summary['rechargements_recus'] }} rechargement(s) reçu(s)</div></td>
            <td><div class="metric-value">{{ $formatAmount($summary['volume_transactions']) }}</div><div class="metric-label">{{ $summary['transactions'] }} transaction(s) effectuée(s)</div></td>
            <td><div class="metric-value">{{ $formatAmount($summary['volume_transactions_recues']) }}</div><div class="metric-label">{{ $summary['transactions_recues'] }} transaction(s) reçue(s)</div></td>
        </tr>
    </table>

    <h2>Réconciliation du compte principal</h2>
    @if ($reconciliation['operations_suspectes'] > 0 || $reconciliation['ruptures_continuite'] > 0)
        <div class="alert">
            {{ $reconciliation['operations_suspectes'] }} opération(s) réellement suspecte(s) et
            {{ $reconciliation['ruptures_continuite'] }} rupture(s) de continuité.
            Une rupture ne met pas automatiquement en cause la transaction suivante.
        </div>
    @endif
    <table class="data">
        <thead><tr><th>Solde ouverture</th><th class="number">Entrées</th><th class="number">Sorties</th><th class="number">Solde calculé</th><th class="number">Solde enregistré</th><th class="number">Écart</th><th class="number">Solde au {{ $date }}</th></tr></thead>
        <tbody>
            <tr>
                <td class="number">{{ $formatBalance($reconciliation['solde_ouverture']) }}</td>
                <td class="number">{{ $formatAmount($reconciliation['total_entrees']) }}</td>
                <td class="number">{{ $formatAmount($reconciliation['total_sorties']) }}</td>
                <td class="number">{{ $formatBalance($reconciliation['solde_calcule']) }}</td>
                <td class="number">{{ $formatBalance($reconciliation['solde_cloture_enregistre']) }}</td>
                <td class="number {{ abs((float) $reconciliation['ecart']) >= 0.01 ? 'danger' : '' }}">{{ $formatBalance($reconciliation['ecart']) }}</td>
                <td class="number">{{ $formatBalance($reconciliation['solde_a_la_date']) }} {{ $details['devise'] }}</td>
            </tr>
        </tbody>
    </table>

    <h2>Contrôle par wallet</h2>
    <table class="data">
        <thead><tr><th>Wallet</th><th class="number">Opérations</th><th class="number">Ouverture</th><th class="number">Entrées</th><th class="number">Sorties</th><th class="number">Clôture calculée</th><th class="number">Clôture enregistrée</th><th class="number">Manquant / écart</th><th class="number">Solde à la date</th><th>Source</th></tr></thead>
        <tbody>
            @forelse ($reconciliation['audit_wallets'] as $wallet)
                <tr><td>{{ $wallet['wallet'] }} · {{ $wallet['devise'] }}</td><td class="number">{{ $wallet['operations'] }}</td><td class="number">{{ $formatBalance($wallet['solde_ouverture']) }}</td><td class="number">{{ $formatAmount($wallet['entrees']) }}</td><td class="number">{{ $formatAmount($wallet['sorties']) }}</td><td class="number">{{ $formatBalance($wallet['solde_calcule']) }}</td><td class="number">{{ $formatBalance($wallet['solde_cloture']) }}</td><td class="number">{{ $formatBalance($wallet['ecart']) }}</td><td class="number">{{ $formatBalance($wallet['solde_a_la_date']) }}</td><td>{{ $wallet['source_solde'] ?: '—' }}<br>{{ $wallet['date_solde'] ?: '' }}</td></tr>
            @empty
                <tr><td colspan="10" class="empty">Aucun wallet actif pour cette période.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Transactions suspectes et ruptures de continuité</h2>
    <table class="data">
        <thead><tr><th>Date</th><th>Anomalie</th><th>Opération</th><th>Type transaction</th><th>Type reçu</th><th>Référence</th><th class="number">Montant</th><th class="number">Attendu</th><th class="number">Observé</th><th class="number">Écart</th><th>Cause / contrôle</th></tr></thead>
        <tbody>
            @forelse ($reconciliation['anomalies'] as $anomaly)
                <tr class="{{ $anomaly['affecte_operation'] ? 'suspicious' : 'continuity' }}"><td>{{ $anomaly['date'] }}</td><td>{{ $anomaly['type_anomalie'] }}<br>{{ $anomaly['affecte_operation'] ? 'Transaction à contrôler' : 'Intervalle à expliquer, transaction non mise en cause' }}</td><td>{{ $anomaly['operation'] }}</td><td>{{ $anomaly['type_transaction'] ?: '—' }}</td><td>{{ $anomaly['type_received'] ?: '—' }}</td><td>{{ $anomaly['reference'] }}</td><td class="number">{{ $formatAmount($anomaly['montant']) }}</td><td class="number">{{ $formatAmount($anomaly['attendu']) }}</td><td class="number">{{ $formatAmount($anomaly['observe']) }}</td><td class="number">{{ $formatAmount($anomaly['ecart']) }}</td><td>{{ $anomaly['cause_probable'] }}<br><strong>{{ $anomaly['formule'] }}</strong>@if ($anomaly['operation_precedente'])<br>Précédente : {{ $anomaly['operation_precedente']['reference'] }} · {{ $anomaly['operation_precedente']['date'] }}@endif</td></tr>
            @empty
                <tr><td colspan="11" class="empty">Aucun écart détecté.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Comptes séparés — non inclus dans le calcul principal</h2>
    <table class="data">
        <thead><tr><th>Compte</th><th class="number">Solde</th><th>Rattachement</th></tr></thead>
        <tbody>
            <tr><td>WalletAdmin</td><td class="number">{{ $formatAmount($separateAccounts['wallet_admin']['solde']) }} {{ $separateAccounts['wallet_admin']['devise'] }}</td><td>{{ $separateAccounts['wallet_admin']['rattache'] ? 'Rattaché au '.$actorLabel : 'Non rattaché' }}</td></tr>
            <tr><td>WalletCommission</td><td class="number">{{ $formatAmount($separateAccounts['wallet_commission']['solde']) }} {{ $separateAccounts['wallet_commission']['devise'] }}</td><td>{{ $separateAccounts['wallet_commission']['rattache'] ? 'Rattaché au '.$actorLabel : 'Non rattaché' }}</td></tr>
        </tbody>
    </table>

    <h2>Rechargements effectués</h2>
    <table class="data">
        <thead><tr><th>Date</th><th>Bénéficiaire</th><th>Type transaction</th><th>Type reçu</th><th>Devise</th><th class="number">Débité</th><th class="number">Crédité</th><th class="number">Superviseur avant</th><th class="number">Superviseur après</th><th class="number">Receveur avant</th><th class="number">Receveur après</th><th>Statut</th><th>Contrôle</th></tr></thead>
        <tbody>
            @forelse ($details['rechargements_envoyes'] as $row)
                <tr class="{{ $row['suspecte'] ? 'suspicious' : '' }}"><td>{{ $row['date'] }}</td><td>{{ $row['beneficiaire'] }}</td><td>{{ $row['type_operation'] ?: '—' }}</td><td>{{ $row['type_received'] ?: '—' }}</td><td>{{ $row['devise_receveur'] }}</td><td class="number">{{ $formatAmount($row['montant']) }}</td><td class="number">{{ $formatAmount($row['montant_recu']) }}</td><td class="number">{{ $formatBalance($row['solde_expediteur_avant']) }}</td><td class="number">{{ $formatBalance($row['solde_expediteur_apres']) }}</td><td class="number">{{ $formatBalance($row['solde_receveur_avant']) }}</td><td class="number">{{ $formatBalance($row['solde_receveur_apres']) }}</td><td>{{ $row['statut'] }}</td><td>{{ $row['suspecte'] ? 'Écart '.$formatAmount($row['ecart_suspect']) : 'Conforme' }}</td></tr>
            @empty
                <tr><td colspan="13" class="empty">Aucun rechargement effectué.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Rechargements reçus</h2>
    <table class="data">
        <thead><tr><th>Date</th><th>Expéditeur</th><th>Type transaction</th><th>Type reçu</th><th>Devise</th><th class="number">Envoyé</th><th class="number">Reçu</th><th class="number">Solde avant</th><th class="number">Solde après</th><th>Statut</th><th>Contrôle</th></tr></thead>
        <tbody>
            @forelse ($details['rechargements_recus'] as $row)
                <tr class="{{ $row['suspecte'] ? 'suspicious' : '' }}"><td>{{ $row['date'] }}</td><td>{{ $row['expediteur'] }}</td><td>{{ $row['type_operation'] ?: '—' }}</td><td>{{ $row['type_received'] ?: '—' }}</td><td>{{ $row['devise_receveur'] }}</td><td class="number">{{ $formatAmount($row['montant']) }}</td><td class="number">{{ $formatAmount($row['montant_recu']) }}</td><td class="number">{{ $formatBalance($row['solde_receveur_avant']) }}</td><td class="number">{{ $formatBalance($row['solde_receveur_apres']) }}</td><td>{{ $row['statut'] }}</td><td>{{ $row['suspecte'] ? 'Écart '.$formatAmount($row['ecart_suspect']) : 'Conforme' }}</td></tr>
            @empty
                <tr><td colspan="11" class="empty">Aucun rechargement reçu.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Transactions effectuées</h2>
    <table class="data">
        <thead><tr><th>Date</th><th>Service</th><th>Type transaction</th><th>Type reçu</th><th>Bénéficiaire</th><th>Devise</th><th class="number">Envoyé</th><th class="number">Crédité</th><th class="number">Superviseur avant</th><th class="number">Superviseur après</th><th class="number">Receveur avant</th><th class="number">Receveur après</th><th>Statut</th><th>Contrôle</th></tr></thead>
        <tbody>
            @forelse ($details['transactions'] as $row)
                <tr class="{{ $row['suspecte'] ? 'suspicious' : '' }}"><td>{{ $row['date'] }}</td><td>{{ $label($row['service']) }}</td><td>{{ $row['type_operation'] ?: '—' }}</td><td>{{ $row['type_received'] ?: '—' }}</td><td>{{ $row['beneficiaire'] }}</td><td>{{ $row['devise_receveur'] }}</td><td class="number">{{ $formatAmount($row['montant']) }}</td><td class="number">{{ $formatAmount($row['montant_recu']) }}</td><td class="number">{{ $formatBalance($row['solde_superviseur_avant']) }}</td><td class="number">{{ $formatBalance($row['solde_superviseur_apres']) }}</td><td class="number">{{ $formatBalance($row['solde_receveur_avant']) }}</td><td class="number">{{ $formatBalance($row['solde_receveur_apres']) }}</td><td>{{ $row['statut'] }}</td><td>{{ $row['suspecte'] ? 'Écart '.$formatAmount($row['ecart_suspect']) : 'Conforme' }}</td></tr>
            @empty
                <tr><td colspan="14" class="empty">Aucune transaction effectuée.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Transactions reçues</h2>
    <table class="data">
        <thead><tr><th>Date</th><th>Service</th><th>Type transaction</th><th>Type reçu</th><th>Référence</th><th>Expéditeur</th><th>Devise</th><th class="number">Envoyé</th><th class="number">Reçu</th><th class="number">Solde avant</th><th class="number">Solde après</th><th>Statut</th><th>Contrôle</th></tr></thead>
        <tbody>
            @forelse ($details['transactions_recues'] as $row)
                <tr class="{{ $row['suspecte'] ? 'suspicious' : '' }}"><td>{{ $row['date'] }}</td><td>{{ $label($row['service']) }}</td><td>{{ $row['type_operation'] ?: '—' }}</td><td>{{ $row['type_received'] ?: '—' }}</td><td>{{ $row['reference_operation'] }}</td><td>{{ $row['expediteur'] }}</td><td>{{ $row['devise_receveur'] }}</td><td class="number">{{ $formatAmount($row['montant']) }}</td><td class="number">{{ $formatAmount($row['montant_recu']) }}</td><td class="number">{{ $formatBalance($row['solde_receveur_avant']) }}</td><td class="number">{{ $formatBalance($row['solde_receveur_apres']) }}{{ ($row['solde_reconstitue'] ?? false) ? ' *' : '' }}</td><td>{{ $row['statut'] }}</td><td>{{ $row['suspecte'] ? 'Écart '.$formatAmount($row['ecart_suspect']) : 'Conforme' }}</td></tr>
            @empty
                <tr><td colspan="13" class="empty">Aucune transaction reçue.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
