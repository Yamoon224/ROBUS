<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Commissions Pixel Innov</title>
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
        .badge { background: #e2e8f0; color: #475569; padding: 1px 5px; border-radius: 3px; }
        .active { color: #16a34a; font-weight: bold; }
        .inactive { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Commissions Pixel Innov — Structure tarifaire directe</h1>
    <p class="muted">Généré le {{ now()->format('d/m/Y à H:i') }} — {{ $services->count() }} service(s)</p>

    <table>
        <thead>
            <tr>
                <th>Pays</th>
                <th>Service / Opérateur</th>
                <th>Type</th>
                <th class="text-center">Frais direct (%)</th>
                <th class="text-center">Frais fixe</th>
                <th class="text-center">S-Distributeur (%)</th>
                <th class="text-center">Compagnie (%)</th>
                <th class="text-center">Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($services as $item)
                <tr>
                    <td>{{ $item->country->name ?? '—' }}</td>
                    <td>{{ $item->name }}</td>
                    <td><span class="badge">{{ $item->operation_type }}</span></td>
                    <td class="text-center">{{ number_format($item->commission_percent, 2) }} %</td>
                    <td class="text-center">{{ $item->fixed_fee ? number_format($item->fixed_fee, 0) : '—' }}</td>
                    <td class="text-center">{{ number_format($item->commission_under_pct, 2) }} %</td>
                    <td class="text-center">{{ number_format($item->commission_companie_pct, 2) }} %</td>
                    <td class="text-center">
                        @if($item->status)
                            <span class="active">Actif</span>
                        @else
                            <span class="inactive">Inactif</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">Aucun service configuré.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
