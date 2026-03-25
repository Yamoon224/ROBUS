<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
       <title>FACTURE - {{ $order->transaction->transaction_id ?? 'N/A' }}</title>
    
    <link rel="stylesheet" 
      href="{{ asset_url('css/invoice.css') }}?v={{ filemtime(public_path('css/invoice.css')) }}" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8">
 
</head>

<body>

    <div class="invoice">
        <!-- Header compact -->
        <div class="header">
            <div class="logo-area">
                @if(isset($logo) && $logo)
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
                <p>N° {{ $order->transaction->transaction_id ?? 'N/A' }}</p>
            </div>
            <div class="clearfix"></div>
        </div>

        <!-- Infos transaction -->
        <div class="row">
            <div class="col-left">
                <table class="info-table">
                    <tr>
                        <td class="label">Code wallet:</td>
                        <td class="value bold">{{ $order->wallet->wallet_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Date demande:</td>
                        <td class="value">{{ $order->created_at ? date('d/m/Y H:i', strtotime($order->created_at)) : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Date traitement:</td>
                        <td class="value">{{ isset($order->transaction->created_at) ? date('d/m/Y H:i', strtotime($order->transaction->created_at)) : 'N/A' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-right">
                <table class="info-table">
                    <tr>
                        <td class="label">Statut:</td>
                        <td class="value">
                            @if($order->status == 'SUCCESS')
                                <span class="status-badge status-success">✓ ACCEPTÉ</span>
                            @elseif($order->status == 'REFUND')
                                <span class="status-badge status-refund">↺ REMBOURSÉ</span>
                            @else
                                <span class="status-badge status-pending">⏳ EN ATTENTE</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Référence:</td>
                        <td class="value bold">{{ $order->transaction->transaction_id ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Description (si présente) -->
        @if($order->description)
        <div class="description">
            « {{ $order->description }} »
        </div>
        @endif

        <!-- Client - Version compacte -->
        <div class="section">
            <div class="section-title">CLIENT</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nom complet</div>
                    <div class="info-value bold">
                        {{ $order->wallet->sidUser->firstname ?? ($order->wallet->user->firstname ?? 'N/A') }}
                        {{ $order->wallet->sidUser->lastname ?? ($order->wallet->user->lastname ?? '') }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Téléphone</div>
                    <div class="info-value">{{ $order->wallet->sidUser->phone ?? ($order->wallet->user->phone ?? 'N/A') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Pays / Ville</div>
                    <div class="info-value">
                        {{ $order->wallet->sidUser->country->name ?? ($order->wallet->user->country->name ?? 'N/A') }}
                        / {{ $order->wallet->sidUser->city->name ?? ($order->wallet->user->city->name ?? 'N/A') }}
                    </div>
                </div>
                @if(isset($order->wallet->sidUser->is_under_distributer) && $order->wallet->sidUser->is_under_distributer == 1)
                <div class="info-item">
                    <div class="info-label">Distributeur</div>
                    <div class="info-value" style="color:#e74c3c;">{{ $order->wallet->wallet_id ?? 'N/A' }}</div>
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
                    <div class="info-value bold">{{ $order->provider->name ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Devise</div>
                    <div class="info-value">{{ $order->wallet->currencie->code ?? 'FCFA' }}</div>
                </div>
                @if(isset($order->phone))
                <div class="info-item">
                    <div class="info-label">Numéro</div>
                    <div class="info-value">{{ $order->phone }}</div>
                </div>
                @endif
            </div>

            <!-- Tableau des montants -->
            <table class="amount-table">
                <tr>
                    <td class="label">Montant demandé</td>
                    <td class="value">{{ number_format($order->amount ?? 0, 0, ',', ' ') }} {{ $order->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>
                <tr>
                    <td class="label">Montant reçu</td>
                    <td class="value bold">{{ number_format($order->amount_received ?? 0, 0, ',', ' ') }} {{ $order->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>
                <tr>
                    <td class="label">Frais</td>
                    <td class="value">{{ number_format($order->costs_amount ?? 0, 0, ',', ' ') }} {{ $order->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>
                <tr class="total-row">
                    <td class="label bold">TOTAL À PAYER</td>
                    <td class="value bold">{{ number_format($order->amount ?? 0, 0, ',', ' ') }} {{ $order->wallet->currencie->code ?? 'FCFA' }}</td>
                </tr>
            </table>

            <!-- Infos bancaires (compact) -->
            @if(isset($order->transaction->wallet_versement_id) && $order->transaction->wallet_versement_id != null)
            <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #ecf0f1;">
                <div class="info-label" style="margin-bottom: 4px;">COORDONNÉES BANCAIRES</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Compte</div>
                        <div class="info-value">{{ $order->num_accompte ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Guichet/Banque</div>
                        <div class="info-value">{{ $order->guichet_code ?? 'N/A' }}/{{ $order->bank_code ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Titulaire</div>
                        <div class="info-value">{{ $order->full_name ?? 'N/A' }}</div>
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

    <div>
        <div style="padding:0px 19px;">
            <table>
                <thead>
                    <tr>
                        <th width="50%"></th>
                        <th width="50%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <img src="{{ asset_url('dist/images/logo_robus.png') }}" height="30"
                                                style="display:inline-block;margin-bottom:10px">

                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="" class="bold">{{ env('APP_NAME') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="">{{ 'Adresse de la facture' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="">{{ 'Email' }}:
                                            {{ env('EMAIL_USER') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="">{{ 'Telephone' }}:
                                            {{ env('PHONE_USE') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table class="text-right">
                                <tbody>
                                    <tr>
                                        <td style="font-size: 2rem;" class="bold">{{ 'Facture' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="">
                                            <span class=" ">{{ 'Code de la commande' }}:</span>
                                            <span class="bold" style="color: #ED2939">{{ $order->uniq }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="">
                                            <span class=" ">{{ 'Date de la commande' }}:</span>
                                            <span class="bold">{{ $order->created_at  }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class=" ">{{ 'Type de livraison' }}:</span>
                                            <span class="bold"
                                                style="text-transform: capitalize">{{ $order->delivery_type }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="margin:8px 8px 15px 8px; clear:both">
            <div style="padding:10px 14px; border:1px solid #DEDEDE;border-radius:3px;width:45%;float:left;">
                <table class="">
                    <tbody>
                        <tr>
                            <td class="bold">{{ 'Adresse de la maison ' }}:</td>
                        </tr>
                        <tr>
                            <td class="">{{ $order->user->address ?? $order->user->zone->name }}</td>
                        </tr>
                        <tr>
                            <td class="">{{ 'Telephone' }}: {{ $order->user->phone ?? 'Inconnu' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="padding:10px 14px; border:1px solid #DEDEDE;border-radius:3px;width:45%;float:right">
                <table class="text-right">
                    <tbody>
                        <tr>
                            <td class="bold">{{ 'Adresse' }}:</td>
                        </tr>
                        <tr>
                            <td class="">{{ $address->zone->name ?? $order->user->zone->name }},
                                {{ $address->postal_code }}</td>
                        </tr>
                        <tr>
                            <td class="">{{ $address->city }},
                                {{ $address->phone }}, {{ $address->country->name }}</td>

                        </tr>
                        <tr>
                            <td class="">{{ 'Telephone' }}: {{ $address->phone }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="margin:0 8px;border:1px solid #DEDEDE;border-radius:3px;padding:0 7px">
            <table class="padding">
                <thead>
                    <tr>
                        <td width="5%" class="text-left bold">{{ 'S/L' }}</td>
                        <td width="45%" class="text-left bold">{{ 'Nom du kit' }}</td>
                        <td width="13%" class="text-left bold">{{ 'Qty' }}</td>
                        <td width="15%" class="text-left bold">{{ 'Prix' }}</td>
                        <td width="12%" class="text-right bold">{{ 'Total' }}</td>
                    </tr>
                </thead>
            </table>
        </div>
        <div style="margin:8px;">
            <table class="lg-padding" style="border-collapse: collapse">
                <tr>
                    <th width="6%" class="text-left"></th>
                    <th width="44%" class="text-left"></th>
                    <th width="13%" class="text-left"></th>
                    <th width="15%" class="text-left"></th>
                    <th width="14%" class="text-right"></th>
                </tr>
                <tbody class="strong">
                    @foreach ($order->orderDetails as $key => $orderDetail)
                        @if ($orderDetail->kit != null)
                            <tr>
                                <td style="border-bottom:1px solid #DEDEDE;padding-left:20px">{{ $key + 1 }}</td>
                                <td style="border-bottom:1px solid #DEDEDE;">
                                    <span style="display: block">{{ $orderDetail->kit->name }}</span>

                                </td>
                                <td class="" style="border-bottom:1px solid #DEDEDE;">
                                    {{ $orderDetail->quantity }}</td>
                                <td class="" style="border-bottom:1px solid #DEDEDE;">
                                    {{ $orderDetail->price }}</td>
                                <td class="text-right bold" style="border-bottom:1px solid #DEDEDE;padding-right:20px;">
                                    {{ $orderDetail->total }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin:15px 8px;clear:both">
            <div style="float: left; width:43%;padding:14px 20px;">
                @if ($order->payment_status == '1')
                    <div class="mt-5">
                    </div>
                @elseif($order->payment_type == 'cash_on_delivery')
                    <div class="mt-5">

                    </div>
                @endif
            </div>
            <div style="float: right; width:43%;padding:14px 20px; border:1px solid #DEDEDE;border-radius:3px;">
                <table class="text-right sm-padding" style="border-collapse:collapse">
                    <tbody>
                        <tr>
                            <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                {{ 'Montant total' }}</td>
                            <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                {{ $order->orderDetails->sum('total') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>

</html>
