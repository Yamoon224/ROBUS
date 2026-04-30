<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FACTURE - {{ $walletDeposits->deposit->transaction_id ?? 'N/A' }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8">

    <link rel="stylesheet" href="{{ asset_url('css/invoice.css') }}?v={{ filemtime(public_path('css/invoice.css')) }}" />
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
                <p>N° {{ $walletDeposits->deposit->transaction_id ?? 'N/A' }}</p>
            </div>
            <div class="clearfix"></div>
        </div>

        <!-- Infos transaction -->
        <div class="row">
            <div class="col-left">
                <table class="info-table">
                    <tr>
                        <td class="label">Code wallet:</td>
                        <td class="value bold">{{ $walletDeposits->wallet->wallet_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Date demande:</td>
                        <td class="value">
                            {{ $walletDeposits->created_at ? date('d/m/Y H:i', strtotime($walletDeposits->created_at)) : 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Date traitement:</td>
                        <td class="value">
                            {{ isset($walletDeposits->deposit->created_at) ? date('d/m/Y H:i', strtotime($walletDeposits->deposit->created_at)) : 'N/A' }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-right">
                <table class="info-table">
                    <tr>
                        <td class="label">Statut:</td>
                        <td class="value">
                            @if ($walletDeposits->status == 'SUCCESS')
                                <span class="status-badge status-success">✓ ACCEPTÉ</span>
                            @elseif($walletDeposits->status == 'REFUND')
                                <span class="status-badge status-refund">↺ REMBOURSÉ</span>
                            @else
                                <span class="status-badge status-pending">⏳ EN ATTENTE</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Référence:</td>
                        <td class="value bold">{{ $walletDeposits->deposit->transaction_id ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Description (si présente) -->
        @if ($walletDeposits->description)
            <div class="description">
                « {{ $walletDeposits->description }} »
            </div>
        @endif

        <!-- Client - Version compacte -->
        <div class="section">
            <div class="section-title">CLIENT</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nom complet</div>
                    <div class="info-value bold">
                        {{ $walletDeposits->wallet->sidUser->firstname ?? ($walletDeposits->wallet->user->firstname ?? 'N/A') }}
                        {{ $walletDeposits->wallet->sidUser->lastname ?? ($walletDeposits->wallet->user->lastname ?? '') }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Téléphone</div>
                    <div class="info-value">
                        {{ $walletDeposits->wallet->sidUser->phone ?? ($walletDeposits->wallet->user->phone ?? 'N/A') }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Pays / Ville</div>
                    <div class="info-value">
                        {{ $walletDeposits->wallet->sidUser->country->name ?? ($walletDeposits->wallet->user->country->name ?? 'N/A') }}
                        /
                        {{ $walletDeposits->wallet->sidUser->city->name ?? ($walletDeposits->wallet->user->city->name ?? 'N/A') }}
                    </div>
                </div>
                @if (isset($walletDeposits->wallet->sidUser->is_under_distributer) &&
                        $walletDeposits->wallet->sidUser->is_under_distributer == 1)
                    <div class="info-item">
                        <div class="info-label">Distributeur</div>
                        <div class="info-value" style="color:#e74c3c;">
                            {{ $walletDeposits->wallet->wallet_id ?? 'N/A' }}</div>
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
                    <div class="info-value bold">{{ $walletDeposits->provider->name ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Devise</div>
                    <div class="info-value">{{ $walletDeposits->wallet->currencie->code ?? 'FCFA' }}</div>
                </div>
                @if (isset($walletDeposits->num_carte))
                    <div class="info-item">
                        <div class="info-label">Numéro de la carte</div>
                        <div class="info-value">{{ $walletDeposits->num_carte }}</div>
                    </div>
                @endif
                @if (isset($walletDeposits->name))
                    <div class="info-item">
                        <div class="info-label">Nom & prénom </div>
                        <div class="info-value">{{ $walletDeposits->name }}</div>
                    </div>
                @endif
            </div>

            <!-- Tableau des montants -->
            <table class="amount-table">
                <tr>
                    <td class="label">Montant demandé</td>
                    <td class="value">{{ number_format($walletDeposits->amount ?? 0, 0, ',', ' ') }}
                        {{ $walletDeposits->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>
                <tr>
                    <td class="label">Montant reçu</td>
                    <td class="value bold">{{ number_format($walletDeposits->amount_received ?? 0, 0, ',', ' ') }}
                        {{ $walletDeposits->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>


                @if ($walletDeposits->wallet->sidUser->is_custumers)
                    <tr>
                        <td class="label">Frais</td>
                        <td class="value">{{ number_format($walletDeposits->costs_amount ?? 0, 0, ',', ' ') }}
                            {{ $walletDeposits->wallet->currencie->code ?? 'FCFA' }}</td>
                    </tr>
                @endif


                <tr class="total-row">
                    <td class="label bold">TOTAL À PAYER</td>
                    <td class="value bold">{{ number_format($walletDeposits->amount ?? 0, 0, ',', ' ') }}
                        {{ $walletDeposits->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>
            </table>

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
