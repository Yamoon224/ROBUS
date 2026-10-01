<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FACTURE - {{ $walletInters->transactionInter->transaction_id ?? 'N/A' }}</title>

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
                <p>N° {{ $walletInters->transactionInter->transaction_id ?? 'N/A' }}</p>
            </div>
            <div class="clearfix"></div>
        </div>

        <!-- Infos transaction -->
        <div class="row">
            <div class="col-left">
                <table class="info-table">
                    <tr>
                        <td class="label">Code wallet:</td>
                        <td class="value bold">{{ $walletInters->wallet->wallet_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Date demande:</td>
                        <td class="value">
                            {{ $walletInters->created_at ? date('d/m/Y H:i', strtotime($walletInters->created_at)) : 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Date traitement:</td>
                        <td class="value">
                            {{ isset($walletInters->transactionInter->created_at) ? date('d/m/Y H:i', strtotime($walletInters->transactionInter->created_at)) : 'N/A' }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-right">
                <table class="info-table">
                    <tr>
                        <td class="label">Statut:</td>
                        <td class="value">
                            @if ($walletInters->status == 'SUCCESS')
                                <span class="status-badge status-success">✓ ACCEPTÉ</span>
                            @elseif($walletInters->status == 'REFUND')
                                <span class="status-badge status-refund">↺ REMBOURSÉ</span>
                            @else
                                <span class="status-badge status-pending">⏳ EN ATTENTE</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Référence:</td>
                        <td class="value bold">{{ $walletInters->transactionInter->transaction_id ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Description (si présente) -->
        @if ($walletInters->description)
            <div class="description">
                « {{ $walletInters->description }} »
            </div>
        @endif

        <!-- Client - Version compacte -->
        <div class="section">
            <div class="section-title">CLIENT</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nom complet</div>
                    <div class="info-value bold">
                        {{ $walletInters->wallet->sidUser->firstname ?? ($walletInters->wallet->user->firstname ?? 'N/A') }}
                        {{ $walletInters->wallet->sidUser->lastname ?? ($walletInters->wallet->user->lastname ?? '') }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Téléphone</div>
                    <div class="info-value">
                        {{ $walletInters->wallet->sidUser->phone ?? ($walletInters->wallet->user->phone ?? 'N/A') }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Pays / Ville</div>
                    <div class="info-value">
                        {{ $walletInters->wallet->sidUser->country->name ?? ($walletInters->wallet->user->country->name ?? 'N/A') }}
                        /
                        {{ $walletInters->wallet->sidUser->city->name ?? ($walletInters->wallet->user->city->name ?? 'N/A') }}
                    </div>
                </div>
                @if (isset($walletInters->wallet->sidUser->is_under_distributer) &&
                        $walletInters->wallet->sidUser->is_under_distributer == 1)
                    <div class="info-item">
                        <div class="info-label">Distributeur</div>
                        <div class="info-value" style="color:#e74c3c;">{{ $walletInters->wallet->wallet_id ?? 'N/A' }}
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
                    <div class="info-value bold">{{ $walletInters->provider->name ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Devise</div>
                    <div class="info-value">{{ $walletInters->wallet->currencie->code ?? 'FCFA' }}</div>
                </div>
                @if (isset($walletInters->phone))
                    <div class="info-item">
                        <div class="info-label">Numéro</div>
                        <div class="info-value">{{ $walletInters->phone }}</div>
                    </div>
                @endif
            </div>

            <!-- Tableau des montants -->
            <table class="amount-table">
                <tr>
                    <td class="label">Montant demandé</td>
                    <td class="value">{{ number_format($walletInters->amount ?? 0, 0, ',', ' ') }}
                        {{ $walletInters->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>
                <tr>
                    <td class="label">Montant reçu</td>
                    <td class="value bold">{{ number_format($walletInters->amount_received ?? 0, 0, ',', ' ') }}
                        {{ $walletTransfers->wallet->currencie->name ?? null }}</td>
                </tr>
                <tr>
                    <td class="label">Frais</td>
                    <td class="value">{{ number_format($walletInters->costs_amount ?? 0, 0, ',', ' ') }}
                        {{ $walletInters->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>
                <tr class="total-row">
                    <td class="label bold">TOTAL À PAYER</td>
                    <td class="value bold">{{ number_format($walletInters->amount ?? 0, 0, ',', ' ') }}
                        {{ $walletInters->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>
            </table>

            @if ($walletInters->provider_type == 1)
                @if ($walletInters->provider->name == 'ROBUS')
                    <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #ecf0f1;">
                        @php
                            $wallet_info = \App\Models\Wallets\Wallet::where(
                                'wallet_id',
                                $walletInters->number_compte,
                            )->first();

                        @endphp
                        <div class="info-label" style="margin-bottom: 4px;">DETAILS DESTINATAIRE</div>

                        <div class="section">
                            <div class="section-title">Details destinataire</div>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Nom complet</div>
                                    <div class="info-value bold">
                                        {{ $wallet_info->sidUser->firstname ?? ($wallet_info->user->firstname ?? 'N/A') }}
                                        {{ $wallet_info->sidUser->lastname ?? ($wallet_info->user->lastname ?? '') }}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Téléphone</div>
                                    <div class="info-value">
                                        {{ $wallet_info->sidUser->phone ?? ($wallet_info->user->phone ?? 'N/A') }}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Pays / Ville</div>
                                    <div class="info-value">
                                        {{ $wallet_info->sidUser->country->name ?? ($wallet_info->user->country->name ?? 'N/A') }}
                                        /
                                        {{ $wallet_info->sidUser->city->name ?? ($wallet_info->user->city->name ?? 'N/A') }}
                                    </div>
                                </div>
                                @if (isset($wallet_info->sidUser->is_under_distributer) && $wallet_info->sidUser->is_under_distributer == 1)
                                    <div class="info-item">
                                        <div class="info-label">Distributeur</div>
                                        <div class="info-value" style="color:#e74c3c;">
                                            {{ $wallet_info->wallet_id ?? 'N/A' }}</div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                @else
                    <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #ecf0f1;">

                        <div class="info-label" style="margin-bottom: 4px;">DETAILS DESTINATAIRE</div>

                        <div class="section">
                            <div class="section-title">Details destinataire</div>
                            <div class="info-grid">

                                <div class="info-item">
                                    <div class="info-label">Téléphone</div>
                                    <div class="info-value">
                                        {{ $walletInters->phone ?? $walletInters->number_compte }}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Pays</div>
                                    <div class="info-value">
                                        {{ $walletInters->country->name ?? null }}

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                @endif
            @elseif($walletInters->provider_type == 5)
                @if ($walletInters->provider->name == 'CANAL')
                    <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #ecf0f1;">
                        <div class="info-label" style="margin-bottom: 4px;">Paiement de facture CANAL+</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">
                                    @if ($walletInters->number_phone != null)
                                        Numero de telephone(10 chiffres)
                                    @else
                                        Numero du decodeur(14 chiffres)
                                    @endif
                                </div>
                                <div class="info-value">
                                    {{ $walletInters->number_phone ?? $walletInters->number_decoder }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    Offre
                                </div>
                                <div class="info-value">
                                    {{ $walletInters->offer->name ?? null }}
                                </div>
                            </div>

                        </div>
                    </div>
                @elseif ($walletInters->provider->name == 'CIE')
                    <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #ecf0f1;">
                        <div class="info-label" style="margin-bottom: 4px;">Paiement de facture CANAL+</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">
                                    Nom de de facture
                                </div>
                                <div class="info-value">
                                    {{ $walletInters->name ?? $walletInters->number_decoder }}</div>
                            </div>

                        </div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">
                                    Identifiant facture(09 chiffres)
                                </div>
                                <div class="info-value">
                                    {{ $walletInters->id_invoice ?? $walletInters->number_decoder }}</div>
                            </div>

                        </div>
                        @if ($walletInters->paiementChoix_type == 1)
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
                @elseif ($walletInters->provider->name == 'SODECI')
                    <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #ecf0f1;">
                        <div class="info-label" style="margin-bottom: 4px;">Paiement de facture CANAL+</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">
                                    Nom de de facture
                                </div>
                                <div class="info-value">
                                    {{ $walletInters->name ?? null }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    Numero du contrat (09 chiffres)
                                </div>
                                <div class="info-value">
                                    {{ $walletInters->number_contract ?? null }}</div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">
                                    Nom de de facture
                                </div>
                                <div class="info-value">
                                    {{ $walletInters->name ?? null }}</div>
                            </div>
                            @if ($walletInters->paiementChoix_type == 1)
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
            @elseif($walletInters->provider_type == 6)
                <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #ecf0f1;">

                    <div class="info-label" style="margin-bottom: 4px;">DETAILS DESTINATAIRE</div>

                    <div class="section">
                        <div class="section-title">Details destinataire</div>
                        <div class="info-grid">

                            <div class="info-item">
                                <div class="info-label">Téléphone</div>
                                <div class="info-value">
                                    {{ $walletInters->phone ?? $walletInters->number_phone }}
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Pays</div>
                                <div class="info-value">
                                    {{ $walletInters->country->name ?? null }}

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @elseif ($walletInters->provider_type == 7)
                <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #ecf0f1;">

                    <div class="info-label" style="margin-bottom: 4px;">DETAILS DESTINATAIRE</div>

                    <div class="section">
                        <div class="section-title">Details destinataire</div>
                        <div class="info-grid">

                            <div class="info-item">
                                <div class="info-label">Téléphone</div>
                                <div class="info-value">
                                    {{ $walletInters->phone ?? $walletInters->number_phone }}
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Pays</div>
                                <div class="info-value">
                                    {{ $walletInters->country->name ?? null }}

                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Nom & Prénom</div>
                                <div class="info-value">
                                    {{ $walletInters->firstname ?? null }} {{ $walletInters->lastname ?? null }}

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @elseif($walletInters->provider_type == 8)
                <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #ecf0f1;">

                    <div class="info-label" style="margin-bottom: 4px;">DETAILS DESTINATAIRE</div>

                    <div class="section">
                        <div class="section-title">Details destinataire</div>
                        <div class="info-grid">

                            <div class="info-item">
                                <div class="info-label">Téléphone</div>
                                <div class="info-value">
                                    {{ $walletInters->phone ?? $walletInters->number_phone }}
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Pays</div>
                                <div class="info-value">
                                    {{ $walletInters->country->name ?? null }}

                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Nom & Prénom</div>
                                <div class="info-value">
                                    {{ $walletInters->firstname ?? null }} {{ $walletInters->lastname ?? null }}

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @elseif ($walletInters->provider_type == 9)
                <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #ecf0f1;">
                    <div class="info-label" style="margin-bottom: 4px;">COORDONNÉES BANCAIRES</div>
                    <div class="info-grid">
                          <div class="info-item">
                            <div class="info-label">Nom & Prénom</div>
                            <div class="info-value">{{ $walletInters->name_complet ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Compte</div>
                            <div class="info-value">{{ $walletInters->num_accompte ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Guichet/Banque</div>
                            <div class="info-value">
                                {{ $walletInters->guichet_code ?? 'N/A' }}/{{ $walletInters->bank_code ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Titulaire</div>
                            <div class="info-value">{{ $walletInters->full_name ?? 'N/A' }}</div>
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
