<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Rapport Transferts Mobile Zone</title>
    <style>
        @page {
            margin: 18px;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #1f2937;
        }

        h1 {
            font-size: 18px;
            margin: 0 0 4px;
        }

        .muted {
            color: #6b7280;
        }

        .summary {
            width: 100%;
            margin: 14px 0;
            border-collapse: collapse;
        }

        .summary td {
            border: 1px solid #e5e7eb;
            padding: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #1d4ed8;
            color: #fff;
            text-align: left;
            padding: 6px;
            font-weight: bold;
        }

        td {
            border: 1px solid #e5e7eb;
            padding: 5px;
            vertical-align: top;
        }

        .status {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>Rapport Transferts Mobile Zone</h1>
    <div class="muted">Date du rapport : {{ now()->format('d/m/Y H:i') }}</div>

    <table class="summary">
        <tr>
            <td><strong>Total montant</strong><br>{{ number_format((float) ($totalAmount ?? 0), 0, ',', ' ') }}</td>
            <td><strong>Success</strong><br>{{ $account_success ?? 0 }}</td>
            <td><strong>En attente</strong><br>{{ $account_penddings ?? 0 }}</td>
            <td><strong>Echec / rembourse</strong><br>{{ $account_errors ?? 0 }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Expediteur</th>
                <th>Destinataire</th>
                <th>Montants</th>
                <th>References Pixel</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pixel_mobile_zone_historicals as $transfer)
                <tr>
                    <td>{{ optional($transfer->created_at)->format('d/m/Y H:i') }}</td>
                    <td>
                        {{ $transfer->senderService->provider->name ?? '-' }}<br>
                        {{ $transfer->senderService->country->name ?? $transfer->sender_country_iso2 ?? '-' }}<br>
                        {{ $transfer->sender_phone }}
                    </td>
                    <td>
                        {{ $transfer->recipientService->provider->name ?? '-' }}<br>
                        {{ $transfer->recipientService->country->name ?? $transfer->recipient_country_iso2 ?? '-' }}<br>
                        {{ $transfer->recipient_phone }}
                    </td>
                    <td>
                        Envoye :
                        {{ number_format((float) $transfer->amount, 0, ',', ' ') }}
                        {{ $transfer->sender_currency ?? $transfer->currency }}<br>
                        Recu :
                        {{ number_format((float) $transfer->amount_received, 0, ',', ' ') }}
                        {{ $transfer->recipient_currency ?? $transfer->currency }}<br>
                        Frais :
                        {{ number_format((float) $transfer->total_fee, 0, ',', ' ') }}
                        {{ $transfer->sender_currency ?? $transfer->currency }}
                    </td>
                    <td>
                        Cash Out : {{ $transfer->sender_transaction_id ?? '-' }}<br>
                        Cash In : {{ $transfer->recipient_transaction_id ?? '-' }}
                        @if ($transfer->refund_transaction_id)
                            <br>Remb. : {{ $transfer->refund_transaction_id }}
                        @endif
                    </td>
                    <td class="status">
                        {{ $transfer->status }}<br>
                        <span class="muted">Debit: {{ $transfer->sender_status }}</span><br>
                        <span class="muted">Credit: {{ $transfer->recipient_status }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Aucun transfert Mobile Zone trouve pour aujourd'hui.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
