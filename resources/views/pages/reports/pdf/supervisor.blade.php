<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport superviseur</title>
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
    </style>
</head>
<body>
    @php
        $summary = $details['resume'];
        $formatAmount = static fn ($amount) => number_format((float) $amount, 2, ',', ' ');
        $formatBalance = static fn ($amount) => $amount === null ? '—' : number_format((float) $amount, 2, ',', ' ');
        $label = static fn ($value) => ucfirst(str_replace('_', ' ', $value));
    @endphp

    <h1>{{ $details['nom'] }}</h1>
    <div class="subtitle">
        Rapport superviseur du {{ $date }} · Wallet {{ $details['wallet'] ?: 'non identifié' }}
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

    <h2>Rechargements effectués</h2>
    <table class="data">
        <thead><tr><th>Date</th><th>Bénéficiaire</th><th>Devise</th><th class="number">Débité</th><th class="number">Crédité</th><th class="number">Superviseur avant</th><th class="number">Superviseur après</th><th class="number">Receveur avant</th><th class="number">Receveur après</th><th>Statut</th></tr></thead>
        <tbody>
            @forelse ($details['rechargements_envoyes'] as $row)
                <tr><td>{{ $row['date'] }}</td><td>{{ $row['beneficiaire'] }}</td><td>{{ $row['devise_receveur'] }}</td><td class="number">{{ $formatAmount($row['montant']) }}</td><td class="number">{{ $formatAmount($row['montant_recu']) }}</td><td class="number">{{ $formatBalance($row['solde_expediteur_avant']) }}</td><td class="number">{{ $formatBalance($row['solde_expediteur_apres']) }}</td><td class="number">{{ $formatBalance($row['solde_receveur_avant']) }}</td><td class="number">{{ $formatBalance($row['solde_receveur_apres']) }}</td><td>{{ $row['statut'] }}</td></tr>
            @empty
                <tr><td colspan="10" class="empty">Aucun rechargement effectué.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Rechargements reçus</h2>
    <table class="data">
        <thead><tr><th>Date</th><th>Expéditeur</th><th>Devise</th><th class="number">Envoyé</th><th class="number">Reçu</th><th class="number">Solde avant</th><th class="number">Solde après</th><th>Statut</th></tr></thead>
        <tbody>
            @forelse ($details['rechargements_recus'] as $row)
                <tr><td>{{ $row['date'] }}</td><td>{{ $row['expediteur'] }}</td><td>{{ $row['devise_receveur'] }}</td><td class="number">{{ $formatAmount($row['montant']) }}</td><td class="number">{{ $formatAmount($row['montant_recu']) }}</td><td class="number">{{ $formatBalance($row['solde_receveur_avant']) }}</td><td class="number">{{ $formatBalance($row['solde_receveur_apres']) }}</td><td>{{ $row['statut'] }}</td></tr>
            @empty
                <tr><td colspan="8" class="empty">Aucun rechargement reçu.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Transactions effectuées</h2>
    <table class="data">
        <thead><tr><th>Date</th><th>Service</th><th>Bénéficiaire</th><th>Devise</th><th class="number">Envoyé</th><th class="number">Crédité</th><th class="number">Superviseur avant</th><th class="number">Superviseur après</th><th class="number">Receveur avant</th><th class="number">Receveur après</th><th>Statut</th></tr></thead>
        <tbody>
            @forelse ($details['transactions'] as $row)
                <tr><td>{{ $row['date'] }}</td><td>{{ $label($row['service']) }}</td><td>{{ $row['beneficiaire'] }}</td><td>{{ $row['devise_receveur'] }}</td><td class="number">{{ $formatAmount($row['montant']) }}</td><td class="number">{{ $formatAmount($row['montant_recu']) }}</td><td class="number">{{ $formatBalance($row['solde_superviseur_avant']) }}</td><td class="number">{{ $formatBalance($row['solde_superviseur_apres']) }}</td><td class="number">{{ $formatBalance($row['solde_receveur_avant']) }}</td><td class="number">{{ $formatBalance($row['solde_receveur_apres']) }}</td><td>{{ $row['statut'] }}</td></tr>
            @empty
                <tr><td colspan="11" class="empty">Aucune transaction effectuée.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Transactions reçues</h2>
    <table class="data">
        <thead><tr><th>Date</th><th>Service</th><th>Expéditeur</th><th>Devise</th><th class="number">Envoyé</th><th class="number">Reçu</th><th>Statut</th></tr></thead>
        <tbody>
            @forelse ($details['transactions_recues'] as $row)
                <tr><td>{{ $row['date'] }}</td><td>{{ $label($row['service']) }}</td><td>{{ $row['expediteur'] }}</td><td>{{ $row['devise_receveur'] }}</td><td class="number">{{ $formatAmount($row['montant']) }}</td><td class="number">{{ $formatAmount($row['montant_recu']) }}</td><td>{{ $row['statut'] }}</td></tr>
            @empty
                <tr><td colspan="7" class="empty">Aucune transaction reçue.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
