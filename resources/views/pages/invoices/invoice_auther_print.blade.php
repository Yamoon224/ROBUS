<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="UTF-8">
    <title>FACTURE - {{ $walletTransaction->transaction->transaction_id ?? 'N/A' }}</title>

    <link rel="stylesheet" href="{{ asset_url('css/invoice.css') }}" />

</head>

<body>
    <div class="invoice">
        <!-- Header compact -->
        <div class="header">
            <div class="logo-area">
                @if (isset($logo) && $logo)
                    <img src="{{ $logo }}" height="35" alt="Logo">
                @else
                    <strong style="font-size: 14pt; color: #3498db;">{{ env('APP_NAME', 'Application') }}</strong>
                @endif
                <div style="font-size: 7pt; color: #7f8c8d; margin-top: 2px;">
                    {{ env('APP_ADDRESS', 'Adresse') }}<br>
                    Tél: {{ env('PHONE_USE', 'N/A') }} | Email: {{ env('EMAIL_USER', 'N/A') }}
                </div>
            </div>
            <div class="title-area">
                <h1>FACTURE</h1>
                <x-receipt-operation :transaction="$walletTransaction" />
                <p>N° {{ $walletTransaction->transaction->transaction_id ?? 'N/A' }}</p>
            </div>
            <div class="clearfix"></div>
        </div>

        <!-- Infos transaction -->
        <div class="row">
            <div class="col-left">
                <table class="info-table">
                    <tr>
                        <td class="label">Code wallet:</td>
                        <td class="value bold">{{ $walletTransaction->wallet->wallet_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Date demande:</td>
                        <td class="value">
                            {{ $walletTransaction->created_at ? date('d/m/Y H:i', strtotime($walletTransaction->created_at)) : 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Date traitement:</td>
                        <td class="value">
                            {{ isset($walletTransaction->transaction->created_at) ? date('d/m/Y H:i', strtotime($walletTransaction->transaction->created_at)) : 'N/A' }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-right">
                <table class="info-table">
                    <tr>
                        <td class="label">Statut:</td>
                        <td class="value">
                            @if ($walletTransaction->status == 'SUCCESS')
                                <span class="status-badge status-success">✓ ACCEPTÉ</span>
                            @elseif($walletTransaction->status == 'REFUND')
                                <span class="status-badge status-refund">↺ REMBOURSÉ</span>
                            @else
                                <span class="status-badge status-pending">⏳ EN ATTENTE</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Référence:</td>
                        <td class="value bold">{{ $walletTransaction->transaction->transaction_id ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Description (si présente) -->
        @if ($walletTransaction->description)
            <div class="description">
                « {{ $walletTransaction->description }} »
            </div>
        @endif

        <!-- Client - Version compacte -->
        <div class="section">
            <div class="section-title">CLIENT</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nom complet</div>
                    <div class="info-value bold">
                        {{ $walletTransaction->wallet->sidUser->firstname ?? ($walletTransaction->wallet->user->firstname ?? 'N/A') }}
                        {{ $walletTransaction->wallet->sidUser->lastname ?? ($walletTransaction->wallet->user->lastname ?? '') }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Téléphone</div>
                    <div class="info-value">
                        {{ $walletTransaction->wallet->sidUser->phone ?? ($walletTransaction->wallet->user->phone ?? 'N/A') }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Pays / Ville</div>
                    <div class="info-value">
                        {{ $walletTransaction->wallet->sidUser->country->name ?? ($walletTransaction->wallet->user->country->name ?? 'N/A') }}
                        /
                        {{ $walletTransaction->wallet->sidUser->city->name ?? ($walletTransaction->wallet->user->city->name ?? 'N/A') }}
                    </div>
                </div>
                @if (isset($walletTransaction->wallet->sidUser->is_under_distributer) &&
                        $walletTransaction->wallet->sidUser->is_under_distributer == 1)
                    <div class="info-item">
                        <div class="info-label">Distributeur</div>
                        <div class="info-value" style="color:#e74c3c;">
                            {{ $walletTransaction->wallet->wallet_id ?? 'N/A' }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Détails transaction -->
        <div class="section">
            <div class="section-title">DÉTAILS</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Opérateur</div>
                    <div class="info-value bold">{{ $walletTransaction->provider->name ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Devise</div>
                    <div class="info-value">{{ $walletTransaction->wallet->currencie->code ?? 'FCFA' }}</div>
                </div>
                @if (isset($walletTransaction->phone))
                    <div class="info-item">
                        <div class="info-label">Numéro</div>
                        <div class="info-value">{{ $walletTransaction->phone }}</div>
                    </div>
                @endif
            </div>

            <!-- Tableau des montants -->
            <table class="amount-table">
                <tr>
                    <td class="label">Montant demandé</td>
                    <td class="value">{{ number_format($walletTransaction->amount ?? 0, 2, ',', ' ') }}
                        {{ $walletTransaction->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>
                <tr>
                    <td class="label">Montant reçu</td>
                    <td class="value bold">{{ number_format($walletTransaction->amount_received ?? 0, 2, ',', ' ') }}
                        {{ $walletTransaction->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>
                @if ($walletTransaction->wallet->sidUser->is_custumers)
                    <tr>
                        <td class="label">Frais</td>
                        <td class="value">{{ number_format($walletTransaction->costs_amount ?? 0, 2, ',', ' ') }}
                            {{ $walletTransaction->wallet->currencie->code ?? 'FCFA' }}</td>
                    </tr>
                @endif

                <tr class="total-row">
                    <td class="label bold">TOTAL À PAYER</td>
                    <td class="value bold">{{ number_format($walletTransaction->amount ?? 0, 2, ',', ' ') }}
                        {{ $walletTransaction->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>
            </table>

            <!-- Infos bancaires (compact) -->
            @if (isset($walletTransaction->transaction->wallet_versement_id) &&
                    $walletTransaction->transaction->wallet_versement_id != null)
                <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #ecf0f1;">
                    <div class="info-label" style="margin-bottom: 4px;">COORDONNÉES BANCAIRES</div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Compte</div>
                            <div class="info-value">{{ $walletTransaction->num_accompte ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Guichet/Banque</div>
                            <div class="info-value">
                                {{ $walletTransaction->guichet_code ?? 'N/A' }}/{{ $walletTransaction->bank_code ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Titulaire</div>
                            <div class="info-value">{{ $walletTransaction->full_name ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Facture générée automatiquement - {{ now()->format('d/m/Y H:i') }}</p>
            <p>Merci de votre confiance</p>
        </div>
    </div>

    <script type="text/javascript">
        (function() {
            // Impression automatique
            if (window.print) {
                window.print();
            }

            // Fermeture après impression
            window.onafterprint = function() {
                setTimeout(function() {
                    window.close();
                }, 1000);
            };
        })();
    </script>
</body>

</html>
