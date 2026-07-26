<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport {{ $actor }}</title>
    <style>
        @page { margin: 24px; }
        body { color: #1e293b; font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h1 { margin: 0 0 4px; color: #0f172a; font-size: 20px; }
        .subtitle { margin-bottom: 18px; color: #64748b; }
        .summary { margin-bottom: 14px; padding: 10px; border: 1px solid #cbd5e1; background: #f8fafc; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 7px; border: 1px solid #cbd5e1; }
        th { color: #fff; background: #1e40af; font-size: 8px; text-align: left; text-transform: uppercase; }
        .number { text-align: right; }
        .empty { padding: 20px; color: #64748b; text-align: center; }
    </style>
</head>
<body>
    @php
        $label = $actor === 'superviseurs' ? 'Superviseurs' : 'Sous-distributeurs';
        $formatAmount = static fn ($amount) => number_format((float) $amount, 2, ',', ' ');
    @endphp

    <h1>Rapport des {{ $label }}</h1>
    <div class="subtitle">Rapport journalier du {{ $date }} · Généré le {{ now()->format('d/m/Y H:i') }}</div>
    <div class="summary"><strong>{{ count($users) }}</strong> utilisateur(s) avec une activité enregistrée.</div>

    <table>
        <thead>
            <tr>
                <th>Rang</th>
                <th>Utilisateur</th>
                @if ($actor === 'superviseurs')
                    <th class="number">Rechargements envoyés</th>
                    <th class="number">Rechargements reçus</th>
                    <th class="number">Transactions effectuées</th>
                    <th class="number">Transactions reçues</th>
                    <th>Dernière activité</th>
                @else
                    <th class="number">Rechargements reçus</th>
                    <th class="number">Montant rechargements</th>
                    <th class="number">Transactions reçues</th>
                    <th class="number">Montant transactions</th>
                    <th>Dernière activité</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user['rang'] }}</td>
                    <td>{{ $user['nom'] }}</td>
                    @if ($actor === 'superviseurs')
                        <td class="number">{{ $user['rechargements_envoyes'] }}</td>
                        <td class="number">{{ $user['rechargements_recus'] }}</td>
                        <td class="number">{{ $user['transactions'] }}</td>
                        <td class="number">{{ $user['transactions_recues'] }}</td>
                        <td>{{ $user['derniere_activite'] ?: '—' }}</td>
                    @else
                        <td class="number">{{ $user['rechargements_recus'] }}</td>
                        <td class="number">{{ $formatAmount($user['montant_recu']) }}</td>
                        <td class="number">{{ $user['transactions_recues'] }}</td>
                        <td class="number">{{ $formatAmount($user['montant_transactions_recu']) }}</td>
                        <td>{{ $user['derniere_reception'] ?: '—' }}</td>
                    @endif
                </tr>
            @empty
                <tr><td colspan="7" class="empty">Aucune activité pour cette date.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
