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
