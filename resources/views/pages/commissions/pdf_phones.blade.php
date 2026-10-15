<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Commissions Crédit Téléphonique</title>
    <style>
        @page { margin: 18px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; color: #1f2937; }
        h1 { font-size: 16px; margin: 0 0 4px; }
        .muted { color: #6b7280; font-size: 9px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th { background-color: #1e3a5f; color: #fff; padding: 5px 6px; text-align: left; font-size: 9px; }
        td { padding: 4px 6px; border-bottom: 1px solid #e5e7eb; font-size: 9px; }
        tr:nth-child(even) td { background-color: #f8fafc; }
        .text-center { text-align: center; }
        .active { color: #16a34a; font-weight: bold; }
        .inactive { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Commissions — Crédit téléphonique</h1>
    <p class="muted">Généré le {{ now()->format('d/m/Y à H:i') }} — {{ $items->count() }} entrée(s)</p>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th class="text-center">Pourcentage (%)</th>
                <th class="text-center">SD (%)</th>
                <th class="text-center">Compagnie (%)</th>
                <th class="text-center">Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ is_array($item->name) ? implode(', ', $item->name) : ($item->name ?? '—') }}</td>
                    <td class="text-center">{{ is_numeric($item->percent) ? number_format($item->percent * 100, 2).' %' : '—' }}</td>
                    <td class="text-center">{{ is_numeric($item->percent_merchands) ? number_format($item->percent_merchands * 100, 2).' %' : '—' }}</td>
                    <td class="text-center">{{ is_numeric($item->percent_companie ?? null) ? number_format($item->percent_companie * 100, 2).' %' : '—' }}</td>
                    <td class="text-center">
                        @if($item->status)
                            <span class="active">Actif</span>
                        @else
                            <span class="inactive">Inactif</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Aucune donnée.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
