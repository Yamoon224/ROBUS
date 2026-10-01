<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FACTURE - {{ $walletReload->paymentReload->transaction_id ?? 'N/A' }}</title>

    <link rel="stylesheet" href="{{ asset_url('css/invoice.css') }}?v={{ filemtime(public_path('css/invoice.css')) }}" />
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
                <p>N° {{ $walletReload->paymentReload->transaction_id ?? 'N/A' }}</p>
            </div>
            <div class="clearfix"></div>
        </div>

        <!-- Infos transaction -->
        <div class="row">
            <div class="col-left">
                <table class="info-table">
                    <tr>
                        <td class="label">Code wallet:</td>
                        <td class="value bold">{{ $walletReload->wallet->wallet_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Date demande:</td>
                        <td class="value">
                            {{ $walletReload->created_at ? date('d/m/Y H:i', strtotime($walletReload->created_at)) : 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Date traitement:</td>
                        <td class="value">
                            {{ isset($walletReload->paymentReload->created_at) ? date('d/m/Y H:i', strtotime($walletReload->paymentReload->created_at)) : 'N/A' }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-right">
                <table class="info-table">
                    <tr>
                        <td class="label">Statut:</td>
                        <td class="value">
                            @if ($walletReload->status == 'SUCCESS')
                                <span class="status-badge status-success">✓ ACCEPTÉ</span>
                            @elseif($walletReload->status == 'REFUND')
                                <span class="status-badge status-refund">↺ REMBOURSÉ</span>
                            @else
                                <span class="status-badge status-pending">⏳ EN ATTENTE</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Référence:</td>
                        <td class="value bold">{{ $walletReload->paymentReload->transaction_id ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Description (si présente) -->
        @if ($walletReload->description)
            <div class="description">
                « {{ $walletReload->description }} »
            </div>
        @endif

        <!-- Client - Version compacte -->
        <div class="section">
            <div class="section-title">CLIENT</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nom complet</div>
                    <div class="info-value bold">
                        {{ $walletReload->wallet->sidUser->firstname ?? ($walletReload->wallet->user->firstname ?? 'N/A') }}
                        {{ $walletReload->wallet->sidUser->lastname ?? ($walletReload->wallet->user->lastname ?? '') }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Téléphone</div>
                    <div class="info-value">
                        {{ $walletReload->wallet->sidUser->phone ?? ($walletReload->wallet->user->phone ?? 'N/A') }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Pays / Ville</div>
                    <div class="info-value">
                        {{ $walletReload->wallet->sidUser->country->name ?? ($walletReload->wallet->user->country->name ?? 'N/A') }}
                        /
                        {{ $walletReload->wallet->sidUser->city->name ?? ($walletReload->wallet->user->city->name ?? 'N/A') }}
                    </div>
                </div>
                @if (isset($walletReload->wallet->sidUser->is_under_distributer) &&
                        $walletReload->wallet->sidUser->is_under_distributer == 1)
                    <div class="info-item">
                        <div class="info-label">Distributeur</div>
                        <div class="info-value" style="color:#e74c3c;">{{ $walletReload->wallet->wallet_id ?? 'N/A' }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="section">
            <div class="section-title">Destinataire</div>
            @php
                $wallet_info = walletByNumber($walletReload->account_wallet);
            @endphp
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nom complet</div>
                    <div class="info-value bold">
                        {{  $wallet_info->sidUser->firstname ?? ( $wallet_info->user->firstname ?? 'N/A') }}
                        {{  $wallet_info->sidUser->lastname ?? ( $wallet_info->user->lastname ?? '') }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Téléphone</div>
                    <div class="info-value">
                        {{  $wallet_info->sidUser->phone ?? ( $wallet_info->user->phone ?? 'N/A') }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Pays / Ville</div>
                    <div class="info-value">
                        {{  $wallet_info->sidUser->country->name ?? ( $wallet_info->user->country->name ?? 'N/A') }}
                        /
                        {{  $wallet_info->sidUser->city->name ?? ( $wallet_info->user->city->name ?? 'N/A') }}
                    </div>
                </div>
                @if (isset( $wallet_info->sidUser->is_under_distributer) &&
                         $wallet_info->sidUser->is_under_distributer == 1)
                    <div class="info-item">
                        <div class="info-label">Distributeur</div>
                        <div class="info-value" style="color:#e74c3c;">{{  $wallet_info->wallet_id ?? 'N/A' }}
                        </div>
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
                    <div class="info-value bold">{{ $walletReload->provider->name ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Devise</div>
                    <div class="info-value">{{ $walletReload->wallet->currencie->code ?? 'FCFA' }}</div>
                </div>
                @if (isset($walletReload->account_wallet))
                    <div class="info-item">
                        <div class="info-label">Numero du compte</div>
                        <div class="info-value">{{ $walletReload->account_wallet }}</div>
                    </div>
                @endif
            </div>

            <!-- Tableau des montants -->
            <table class="amount-table">
                <tr>
                    <td class="label">Montant demandé</td>
                    <td class="value">{{ number_format($walletReload->amount ?? 0, 0, ',', ' ') }}
                        {{ $walletReload->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>
                <tr>
                    <td class="label">Montant reçu</td>
                    <td class="value bold">{{ number_format($walletReload->amount_received ?? 0, 0, ',', ' ') }}
                        {{ $wallet_info->currencie->code ?? 'FCFA' }}</td>
                </tr>
                <tr>
                    <td class="label">Frais</td>
                    <td class="value">{{ number_format($walletReload->costs_amount ?? 0, 0, ',', ' ') }}
                        {{ $walletReload->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>
                <tr class="total-row">
                    <td class="label bold">TOTAL À PAYER</td>
                    <td class="value bold">{{ number_format($walletReload->amount ?? 0, 0, ',', ' ') }}
                        {{ $walletReload->wallet->currencie->code ?? 'FCFA' }}</td>
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
