<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FACTURE - {{ $walletTransfers->transaction->transaction_id ?? 'N/A' }}</title>

    <link rel="stylesheet" href="{{ asset_url('css/invoice.css') }}" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8">

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
                <p>N° {{ $walletTransfers->transaction->transaction_id ?? 'N/A' }}</p>
            </div>
            <div class="clearfix"></div>
        </div>

        <!-- Infos transaction -->
        <div class="row">
            <div class="col-left">
                <table class="info-table">
                    <tr>
                        <td class="label">Code wallet:</td>
                        <td class="value bold">{{ $walletTransfers->wallet->wallet_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Date demande:</td>
                        <td class="value">
                            {{ $walletTransfers->created_at ? date('d/m/Y H:i', strtotime($walletTransfers->created_at)) : 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Date traitement:</td>
                        <td class="value">
                            {{ isset($walletTransfers->transaction->created_at) ? date('d/m/Y H:i', strtotime($walletTransfers->transaction->created_at)) : 'N/A' }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-right">
                <table class="info-table">
                    <tr>
                        <td class="label">Statut:</td>
                        <td class="value">
                            @if ($walletTransfers->status == 'SUCCESS')
                                <span class="status-badge status-success">✓ ACCEPTÉ</span>
                            @elseif($walletTransfers->status == 'REFUND')
                                <span class="status-badge status-refund">↺ REMBOURSÉ</span>
                            @else
                                <span class="status-badge status-pending">⏳ EN ATTENTE</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Référence:</td>
                        <td class="value bold">{{ $walletTransfers->transaction->transaction_id ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Description (si présente) -->
        @if ($walletTransfers->description)
            <div class="description">
                « {{ $walletTransfers->description }} »
            </div>
        @endif

        <!-- Client - Version compacte -->
        <div class="section">
            <div class="section-title">CLIENT</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nom complet</div>
                    <div class="info-value bold">
                        {{ $walletTransfers->wallet->sidUser->firstname ?? ($walletTransfers->wallet->user->firstname ?? 'N/A') }}
                        {{ $walletTransfers->wallet->sidUser->lastname ?? ($walletTransfers->wallet->user->lastname ?? '') }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Téléphone</div>
                    <div class="info-value">
                        {{ $walletTransfers->wallet->sidUser->phone ?? ($walletTransfers->wallet->user->phone ?? 'N/A') }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Pays / Ville</div>
                    <div class="info-value">
                        {{ $walletTransfers->wallet->sidUser->country->name ?? ($walletTransfers->wallet->user->country->name ?? 'N/A') }}
                        /
                        {{ $walletTransfers->wallet->sidUser->city->name ?? ($walletTransfers->wallet->user->city->name ?? 'N/A') }}
                    </div>
                </div>
                @if (isset($walletTransfers->wallet->sidUser->is_under_distributer) &&
                        $walletTransfers->wallet->sidUser->is_under_distributer == 1)
                    <div class="info-item">
                        <div class="info-label">Distributeur</div>
                        <div class="info-value" style="color:#e74c3c;">
                            {{ $walletTransfers->wallet->wallet_id ?? 'N/A' }}</div>
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
                    <div class="info-value bold">{{ $walletTransfers->provider->name ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Devise</div>
                    <div class="info-value">{{ $walletTransfers->wallet->currencie->code ?? 'FCFA' }}</div>
                </div>
                @if (isset($walletTransfers->phone))
                    <div class="info-item">
                        <div class="info-label">Numéro</div>
                        <div class="info-value">{{ $walletTransfers->phone }}</div>
                    </div>
                @endif
            </div>

            @php
                $wallet_info = walletByNumber($walletTransfers->num_wallet);
            @endphp
            <!-- Tableau des montants -->
            <table class="amount-table">
                <tr>
                    <td class="label">Montant demandé</td>
                    <td class="value">{{ number_format($walletTransfers->amount ?? 0, 0, ',', ' ') }}
                        {{ $walletTransfers->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>
                <tr>
                    <td class="label">Montant reçu</td>
                    @if ($walletTransfers->provider->name == 'ROBUS')
                        <td class="value bold">{{ number_format($walletTransfers->amount_received ?? 0, 0, ',', ' ') }}
                            {{ $wallet_info->currencie->code ?? 'FCFA' }}</td>
                    @else
                        <td class="value bold">{{ number_format($walletTransfers->amount_received ?? 0, 0, ',', ' ') }}
                            {{ $walletTransfers->wallet->currencie->code ?? 'FCFA' }}</td>
                    @endif

                </tr>
                <tr>
                    <td class="label">Frais</td>
                    <td class="value">{{ number_format($walletTransfers->costs_amount ?? 0, 0, ',', ' ') }}
                        {{ $walletTransfers->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>
                <tr class="total-row">
                    <td class="label bold">TOTAL À PAYER</td>
                    <td class="value bold">{{ number_format($walletTransfers->amount ?? 0, 0, ',', ' ') }}
                        {{ $walletTransfers->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>
            </table>

            <!-- Infos bancaires (compact) -->

            @if ($walletTransfers->provider->name == 'ROBUS')
                <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #ecf0f1;">

                    <div class="info-label" style="margin-bottom: 4px;">DETAILS DESTINATAIRE</div>

                    <div class="section">
                        <div class="section-title">Details destinataire</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Nom complet</div>
                                <div class="info-value bold">
                                    {{ $wallet_info->wallet->sidUser->firstname ?? ($wallet_info->wallet->user->firstname ?? 'N/A') }}
                                    {{ $wallet_info->wallet->sidUser->lastname ?? ($wallet_info->wallet->user->lastname ?? '') }}
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Téléphone</div>
                                <div class="info-value">
                                    {{ $wallet_info->wallet->sidUser->phone ?? ($wallet_info->wallet->user->phone ?? 'N/A') }}
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Pays / Ville</div>
                                <div class="info-value">
                                    {{ $wallet_info->wallet->sidUser->country->name ?? ($wallet_info->wallet->user->country->name ?? 'N/A') }}
                                    /
                                    {{ $wallet_info->wallet->sidUser->city->name ?? ($wallet_info->wallet->user->city->name ?? 'N/A') }}
                                </div>
                            </div>
                            @if (isset($wallet_info->wallet->sidUser->is_under_distributer) &&
                                    $wallet_info->wallet->sidUser->is_under_distributer == 1)
                                <div class="info-item">
                                    <div class="info-label">Distributeur</div>
                                    <div class="info-value" style="color:#e74c3c;">
                                        {{ $wallet_info->wallet->wallet_id ?? 'N/A' }}</div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            @else
                <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #ecf0f1;">


                    <div class="section">
                        <div class="section-title">Details destinataire</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Numéro </div>
                                <div class="info-value bold">
                                    {{ $walletTransfers->phone ?? null }}
                                </div>
                            </div>

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
        try {
            this.print();
        } catch (e) {
            window.onload = window.print;
        }
        window.onbeforeprint = function() {
            setTimeout(function() {
                window.close();
            }, 1500);
        }
    </script>
</body>

</html>
