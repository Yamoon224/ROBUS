<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ 'FACTURE' }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8">
    <style media="all">
        @page {
            padding: 0;
            margin: 20px;
        }

        body {
            font-size: 0.75rem;
            font-family: '<?php echo $font_family; ?>';
            font-weight: normal;
            direction: <?php echo $direction; ?>;
            text-align: <?php echo $default_text_align; ?>;
            padding: 0;
            margin: 0;
            color: #232323;
        }

        table {
            width: 100%;
        }

        table th {
            font-weight: normal;
        }

        table.padding th {
            padding: 0 .8rem;
        }

        table.padding td {
            padding: .8rem;
        }

        table.sm-padding td {
            padding: .5rem .7rem;
        }

        table.lg-padding td {
            padding: 1rem 1.2rem;
        }

        .border-bottom td,
        .border-bottom th {
            border-bottom: 1px solid #eceff4;
        }

        .text-left {
            text-align: <?php echo $default_text_align; ?>;
        }

        .text-right {
            text-align: <?php echo $reverse_text_align; ?>;
        }

        .bold {
            font-weight: bold
        }
    </style>
</head>

<body>
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
                                            <img src="{{ asset('public/dist/images/login.jpg') }}" height="30"
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
                                            <span class=" ">{{ 'Code du  wallet' }}:</span>
                                            <span class="bold"
                                                style="color: #ED2939">{{ $walletInters->wallet->wallet_id ?? null }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="">
                                            <span class=" ">{{ 'Date de la demande' }}:</span>
                                            <span
                                                class="bold">{{ $walletInters->created_at->format('d.m.Y') ?? null }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class=" ">{{ 'Date traitement' }}:</span>
                                            <span class="bold"
                                                style="text-transform: capitalize">{{ $walletInters->transactionInter->created_at ?? null }}</span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <span class=" ">{{ 'Reference' }}:</span>
                                            <span class="bold"
                                                style="text-transform: capitalize">{{ $walletInters->transactionInter->transaction_id ?? null }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="margin:15px 8px;clear:both ; text-center;font-size: 20px;">
            {{ 'Statut' }} :
            @if ($walletInters->status == 'SUCCESS')
                <div style="color: green;font-size: 20px;">
                    {{ 'Accepté' }}
                </div>
            @elseif ($walletInters->status == 'REFUND')
                <div style="color:#ED2939;font-size: 20px;">
                    {{ 'Remboursé' }}
                </div>
            @endif
        </div>
        <div style="margin:15px 8px;clear:both ; text-center;font-size: 20px;">
            {{ 'Information client' }}
        </div>
        <div style="margin:15px 8px;clear:both">
            <div style="float: center;width:80%;padding: 17px 0%;">
                <table class="text-center sm-padding" style="border-collapse:collapse">
                    <tbody>
                        <tr>
                            <td class="text-left">
                                {{ 'Nom et prenom' }}</td>
                            <td class="bold">
                                {{ $walletInters->wallet->sidUser->firstname ?? $order->wallet->user->firstname }}
                                {{ $walletInters->wallet->sidUser->lastname ?? $order->wallet->user->lastname }}
                            </td>
                        </tr>
                        @if ($walletInters->wallet->sidUser->is_under_distributer == 1)
                            <tr>
                                <td class="text-left">
                                    {{ 'Wallet sous distributeur' }}</td>
                                <td class="bold" style="color: #ED2939">
                                    {{ $walletInters->wallet->wallet_id ?? null }}

                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td class="text-left">
                                {{ 'Pays' }}</td>
                            <td class="bold">
                                {{ $walletInters->wallet->sidUser->country->name ?? $walletInters->wallet->user->country->name }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                {{ 'Ville' }}</td>
                            <td class="bold">
                                {{ $walletInters->wallet->sidUser->city->name ?? $walletInters->wallet->user->city->name }}
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left">
                                {{ 'Telephone' }}</td>
                            <td class="bold">
                                {{ $walletInters->wallet->sidUser->phone ?? $walletInters->wallet->user->phone }}
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <div style="margin:15px 8px;clear:both ; text-center;font-size: 2rem;">
            {{ $walletInters->description }}
        </div>
        <div style="margin:15px 8px;clear:both">
            <div style="float: center; width:90%;padding:14px 20px; border:1px solid #DEDEDE;border-radius:3px;">
                <table class="text-center sm-padding" style="border-collapse:collapse">
                    <tbody>
                        @if ($walletInters->provider_type == 1)

                            @if ($walletInters->provider->name == 'ROBUS')
                                <tr>
                                    <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                        {{ 'Fournisseur' }}</td>
                                    <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                        {{ $walletInters->provider->name ?? null }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                        {{ 'Numero du compte' }}</td>
                                    <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                        {{ $walletInters->wallet->wallet_id ?? null }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                        {{ 'Montant demandé' }}</td>
                                    <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                        {{ $walletInters->amount ?? null }}
                                        {{ $walletInters->wallet->currencie->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                        {{ 'Montant recu' }}</td>
                                    <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                        {{ $walletInters->amount_received ?? null }}
                                        {{ $walletInters->country->currencie->name ?? null }}</td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                        {{ 'Pays' }}</td>
                                    <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                        {{ $walletInters->country->name ?? null }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                        {{ 'Numero du compte' }}</td>
                                    <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                        {{ $walletInters->number_compte ?? null }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                        {{ 'Fournisseur' }}</td>
                                    <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                        {{ $walletInters->provider->name ?? null }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                        {{ 'Montant demandé' }}</td>
                                    <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                        {{ $walletInters->amount ?? null }}
                                        {{ $walletInters->wallet->currencie->name ?? null }}</td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                        {{ 'Montant recu' }}</td>
                                    <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                        {{ $walletInters->amount_received ?? null }}
                                        {{ $walletInters->country->currencie->name ?? null }}</td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                        {{ 'Pays' }}</td>
                                    <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                        {{ $walletInters->country->name ?? null }}
                                    </td>
                                </tr>
                                @if ($walletInters->provider->id == 99)
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Numero bénéficiaire' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->phone ?? null }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Numero du compte' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->number_compte ?? null }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Numero de téléphone' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->phone ?? null }}</td>
                                    </tr>
                                @endif

                                <tr>
                                    <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                        {{ 'frais' }}</td>
                                    <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                        {{ $walletInters->transactionInter->fee_amount ?? null }}</td>
                                </tr>
                            @endif
                        @elseif($walletInters->provider_type == 5)
                            @if ($walletInters->provider->id == 7)
                                @if ($walletInters->paiementChoix_phone == 1)
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Fournisseur' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->provider->name ?? null }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Numero du compte' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->wallet->wallet_id ?? null }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Montant demandé' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->amount ?? null }}
                                            {{ $walletInters->wallet->currencie->name ?? null }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Montant recu' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->amount_received ?? null }}
                                            {{ $walletInters->country->currencie->name ?? null }}</td>
                                    </tr>

                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Pays' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->country->name ?? null }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Numero du telephone(10 chiffres)' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->number_phone ?? null }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Offre' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->offer->name ?? null }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'frais' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->transactionInter->fee_amount ?? null }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Fournisseur' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->provider->name ?? null }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Numero du compte' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->wallet->wallet_id ?? null }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Montant demandé' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->amount ?? null }}
                                            {{ $walletInters->wallet->currencie->name ?? null }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Montant recu' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->amount_received ?? null }}
                                            {{ $walletInters->country->currencie->name ?? null }}</td>
                                    </tr>

                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Pays' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->country->name ?? null }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Numero du decodeur(14 chiffres)' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->number_decoder ?? null }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Offre' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->offer->name ?? null }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'frais' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->transactionInter->fee_amount ?? null }}</td>
                                    </tr>
                                @endif
                            @elseif ($walletInters->provider->id == 8)
                                @if ($walletInters->factureChoix_type == 1)
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Fournisseur' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->provider->name ?? null }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Numero du compte' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->wallet->wallet_id ?? null }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Montant demandé' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->amount ?? null }}
                                            {{ $walletInters->wallet->currencie->name ?? null }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Montant recu' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->amount_received ?? null }}
                                            {{ $walletInters->country->currencie->name ?? null }}</td>
                                    </tr>

                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Pays' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->country->name ?? null }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Numero du facture(11 chiffres)' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->id_invoice ?? null }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'frais' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->transactionInter->fee_amount ?? null }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Fournisseur' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->provider->name ?? null }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Numero du compte' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->wallet->wallet_id ?? null }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Montant demandé' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->amount ?? null }}
                                            {{ $walletInters->wallet->currencie->name ?? null }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Montant recu' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->amount_received ?? null }}
                                            {{ $walletInters->country->currencie->name ?? null }}</td>
                                    </tr>

                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Pays' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->country->name ?? null }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'Numero du facture(09 chiffres)' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->id_invoice ?? null }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                            {{ 'frais' }}</td>
                                        <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                            {{ $walletInters->transactionInter->fee_amount ?? null }}</td>
                                    </tr>
                                @endif
                            @elseif ($walletInters->provider->id == 10)
                                <tr>
                                    <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                        {{ 'Fournisseur' }}</td>
                                    <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                        {{ $walletInters->provider->name ?? null }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                        {{ 'Numero du compte' }}</td>
                                    <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                        {{ $walletInters->wallet->wallet_id ?? null }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                        {{ 'Montant demandé' }}</td>
                                    <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                        {{ $walletInters->amount ?? null }}
                                        {{ $walletInters->wallet->currencie->name ?? null }}</td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                        {{ 'Montant recu' }}</td>
                                    <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                        {{ $walletInters->amount_received ?? null }}
                                        {{ $walletInters->country->currencie->name ?? null }}</td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                        {{ 'Pays' }}</td>
                                    <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                        {{ $walletInters->country->name ?? null }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                        {{ 'Numero du contrat(09 chiffres)' }}</td>
                                    <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                        {{ $walletInters->number_contract ?? null }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                        {{ 'frais' }}</td>
                                    <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                        {{ $walletInters->transactionInter->fee_amount ?? null }}</td>
                                </tr>
                            @endif
                        @elseif($walletInters->provider_type == 6)
                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'Fournisseur' }}</td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->provider->name ?? null }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'Numero du compte' }}</td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->wallet->wallet_id ?? null }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'Montant demandé' }}</td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->amount ?? null }}
                                    {{ $walletInters->wallet->currencie->name ?? null }}</td>
                            </tr>
                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'Montant recu' }}</td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->amount_received ?? null }}
                                    {{ $walletInters->country->currencie->name ?? null }}</td>
                            </tr>

                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'Pays' }}</td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->country->name ?? null }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'Numero du telephone(10 chiffres)' }}
                                </td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->number_phone ?? null }}
                                    {{ $walletInters->phone ?? null }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'frais' }}</td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->transactionInter->fee_amount ?? null }}</td>
                            </tr>
                        @elseif($walletInters->provider_type == 7)
                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'Fournisseur' }}</td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->provider->name ?? null }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'Numero du compte' }}</td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->wallet->wallet_id ?? null }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'Montant demandé' }}</td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->amount ?? null }}
                                    {{ $walletInters->wallet->currencie->name ?? null }}</td>
                            </tr>
                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'Montant recu' }}</td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->amount_received ?? null }}
                                    {{ $walletInters->country->currencie->name ?? null }}</td>
                            </tr>

                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'Pays' }}</td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->country->name ?? null }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'Numero du telephone(10 chiffres)' }}</td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->number_phone ?? ($walletInters->phone ?? null) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'Nom & prenom' }}</td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->firstname ?? null }} {{ $walletInters->lastname ?? null }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'frais' }}</td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->transactionInter->fee_amount ?? null }}</td>
                            </tr>
                        @elseif ($walletInters->provider_type == 8)
                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'Fournisseur' }}</td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->provider->name ?? null }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'Numero du compte' }}</td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->wallet->wallet_id ?? null }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'Montant demandé' }}</td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->amount ?? null }}
                                    {{ $walletInters->wallet->currencie->name ?? null }}</td>
                            </tr>
                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'Montant recu' }}</td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->amount_received ?? null }}
                                    {{ $walletInters->country->currencie->name ?? null }}</td>
                            </tr>

                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'Pays' }}</td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->country->name ?? null }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'Numero du compte' }}</td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->number_contract ?? ($walletInters->id_invoice_pre ?? ($walletInters->number_contract ?? null)) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                    {{ 'frais' }}</td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                    {{ $walletInters->transactionInter->fee_amount ?? null }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
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
