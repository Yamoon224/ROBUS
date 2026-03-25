<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FACTURE - {{ $walletPayments->payment->transaction_id ?? 'N/A' }}</title>

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
                <p>N° {{ $walletPayments->payment->transaction_id ?? 'N/A' }}</p>
            </div>
            <div class="clearfix"></div>
        </div>

        <!-- Infos transaction -->
        <div class="row">
            <div class="col-left">
                <table class="info-table">
                    <tr>
                        <td class="label">Code wallet:</td>
                        <td class="value bold">{{ $walletPayments->wallet->wallet_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Date demande:</td>
                        <td class="value">
                            {{ $walletPayments->created_at ? date('d/m/Y H:i', strtotime($walletPayments->created_at)) : 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Date traitement:</td>
                        <td class="value">
                            {{ isset($walletPayments->payment->created_at) ? date('d/m/Y H:i', strtotime($walletPayments->payment->created_at)) : 'N/A' }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-right">
                <table class="info-table">
                    <tr>
                        <td class="label">Statut:</td>
                        <td class="value">
                            @if ($walletPayments->status == 'SUCCESS')
                                <span class="status-badge status-success">✓ ACCEPTÉ</span>
                            @elseif($walletPayments->status == 'REFUND')
                                <span class="status-badge status-refund">↺ REMBOURSÉ</span>
                            @else
                                <span class="status-badge status-pending">⏳ EN ATTENTE</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Référence:</td>
                        <td class="value bold">{{ $walletPayments->payment->transaction_id ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Description (si présente) -->
        @if ($walletPayments->description)
            <div class="description">
                « {{ $walletPayments->description }} »
            </div>
        @endif

        <!-- Client - Version compacte -->
        <div class="section">
            <div class="section-title">CLIENT</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nom complet</div>
                    <div class="info-value bold">
                        {{ $walletPayments->wallet->sidUser->firstname ?? ($walletPayments->wallet->user->firstname ?? 'N/A') }}
                        {{ $walletPayments->wallet->sidUser->lastname ?? ($walletPayments->wallet->user->lastname ?? '') }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Téléphone</div>
                    <div class="info-value">
                        {{ $walletPayments->wallet->sidUser->phone ?? ($walletPayments->wallet->user->phone ?? 'N/A') }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Pays / Ville</div>
                    <div class="info-value">
                        {{ $walletPayments->wallet->sidUser->country->name ?? ($walletPayments->wallet->user->country->name ?? 'N/A') }}
                        /
                        {{ $walletPayments->wallet->sidUser->city->name ?? ($walletPayments->wallet->user->city->name ?? 'N/A') }}
                    </div>
                </div>
                @if (isset($walletPayments->wallet->sidUser->is_under_distributer) &&
                        $walletPayments->wallet->sidUser->is_under_distributer == 1)
                    <div class="info-item">
                        <div class="info-label">Distributeur</div>
                        <div class="info-value" style="color:#e74c3c;">
                            {{ $walletPayments->wallet->wallet_id ?? 'N/A' }}</div>
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
                    <div class="info-value bold">{{ $walletPayments->provider->name ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Devise</div>
                    <div class="info-value">{{ $walletPayments->wallet->currencie->code ?? 'FCFA' }}</div>
                </div>

            </div>

            <!-- Tableau des montants -->
            <table class="amount-table">
                <tr>
                    <td class="label">Montant demandé</td>
                    <td class="value">{{ number_format($walletPayments->amount ?? 0, 0, ',', ' ') }}
                        {{ $walletPayments->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>
                <tr>
                    <td class="label">Montant reçu</td>
                    <td class="value bold">{{ number_format($walletPayments->amount_received ?? 0, 0, ',', ' ') }}
                        {{ $walletPayments->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>
                <tr>
                    <td class="label">Frais</td>
                    <td class="value">{{ number_format($walletPayments->costs_amount ?? 0, 0, ',', ' ') }}
                        {{ $walletPayments->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>
                <tr class="total-row">
                    <td class="label bold">TOTAL À PAYER</td>
                    <td class="value bold">{{ number_format($walletPayments->amount ?? 0, 0, ',', ' ') }}
                        {{ $walletPayments->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>
            </table>

            <!-- Infos bancaires (compact) -->
            @if ($walletPayments->provider->name == 'CANAL')
                <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #ecf0f1;">
                    <div class="info-label" style="margin-bottom: 4px;">Paiement de facture CANAL+</div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                @if ($walletPayments->number_phone != null)
                                    Numero de telephone(10 chiffres)
                                @else
                                    Numero du decodeur(14 chiffres)
                                @endif
                            </div>
                            <div class="info-value">
                                {{ $walletPayments->number_phone ?? $walletPayments->number_decoder }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                Offre
                            </div>
                            <div class="info-value">
                                {{ $walletPayments->offer->name ?? null }}
                            </div>
                        </div>

                    </div>
                </div>
            @elseif ($walletPayments->provider->name == 'CIE')
                <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #ecf0f1;">
                    <div class="info-label" style="margin-bottom: 4px;">Paiement de facture CANAL+</div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                Nom de de facture
                            </div>
                            <div class="info-value">
                                {{ $walletPayments->name ?? $walletPayments->number_decoder }}</div>
                        </div>

                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                Identifiant facture(09 chiffres)
                            </div>
                            <div class="info-value">
                                {{ $walletPayments->id_invoice ?? $walletPayments->number_decoder }}</div>
                        </div>

                    </div>
                    @if ($walletPayments->paiementChoix_type == 1)
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">
                                    Paiement total
                                </div>
                            </div>

                        </div>
                    @else
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">
                                    Paiement partiel
                                </div>
                            </div>

                        </div>
                    @endif
                </div>
            @elseif ($walletPayments->provider->name == 'SODECI')
                <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #ecf0f1;">
                    <div class="info-label" style="margin-bottom: 4px;">Paiement de facture CANAL+</div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                Nom de de facture
                            </div>
                            <div class="info-value">
                                {{ $walletPayments->name ?? null }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                Numero du contrat (09 chiffres)
                            </div>
                            <div class="info-value">
                                {{ $walletPayments->number_contract ?? null }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                Nom de de facture
                            </div>
                            <div class="info-value">
                                {{ $walletPayments->name ?? null }}</div>
                        </div>
                        @if ($walletPayments->paiementChoix_type == 1)
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">
                                        Paiement total
                                    </div>
                                </div>

                            </div>
                        @else
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">
                                        Paiement partiel
                                    </div>
                                </div>

                            </div>
                        @endif

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
