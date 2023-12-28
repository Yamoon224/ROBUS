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
                                                style="color: #ED2939">{{ $walletBanks->wallet->wallet_id ?? null }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="">
                                            <span class=" ">{{ 'Date de la demande' }}:</span>
                                            <span class="bold">{{ $walletBanks->created_at ?? null }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class=" ">{{ 'Date traitement' }}:</span>
                                            <span class="bold"
                                                style="text-transform: capitalize">{{ $walletBanks->paymentBank->created_at ?? null }}</span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <span class=" ">{{ 'Reference' }}:</span>
                                            <span class="bold"
                                                style="text-transform: capitalize">{{ $walletBanks->paymentBank->transaction_id ?? null }}</span>
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
            @if ($walletBanks->status == 'SUCCESS')
                <div style="color: green;font-size: 20px;">
                    {{ 'Accepté' }}
                </div>
            @elseif ($walletBanks->status == 'REFUND')
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
                                {{ $walletBanks->firstname ?? null }}
                                {{ $walletBanks->lastname ?? null }}
                            </td>
                        </tr>
                        @if ($walletBanks->wallet->sidUser->is_under_distributer == 1)
                        <tr>
                            <td class="text-left">
                                {{ 'Wallet sous distributeur' }}</td>
                            <td class="bold" style="color: #ED2939">
                                {{ $walletBanks->wallet->wallet_id ?? null }}
                               
                            </td>
                        </tr>                        
                        @endif
                        <tr>
                            <td class="text-left">
                                {{ 'Telephone' }}</td>
                            <td class="bold">
                                {{ $walletBanks->phone ?? null }}
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
        <div style="margin:15px 8px;clear:both ; text-center;font-size: 2rem;">
            {{ $walletBanks->description }}
        </div>
        <div style="margin:15px 8px;clear:both">
            <div style="float: center; width:92%;padding:14px 20px; border:1px solid #DEDEDE;border-radius:3px;">
                <table class="text-center sm-padding" style="border-collapse:collapse">
                    <tbody>
                        
                        <tr>
                            <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                {{ 'Virement bancaire' }}</td>
                            <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                {{ $walletBanks->provider->name ?? null }}</td>
                        </tr>
                        <tr>
                            <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                {{ 'Numero du compte' }}</td>
                            <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                {{ $walletBanks->number_compte ?? null }}</td>
                        </tr>
                        <tr>
                            <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                {{ 'Montant' }}</td>
                            <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                {{ $walletBanks->amount ?? null }}
                                {{ $walletBanks->wallet->currencie->name ?? null }}</td>
                        </tr>

                        <tr>
                            <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                {{ 'Frais' }}</td>
                            <td class="bold" style="border-bottom:1px dotted #B8B8B8">0
                                {{ $walletBanks->wallet->currencie->name ?? null }}</td>
                        </tr>
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
