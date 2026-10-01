<!DOCTYPE html>
<!--
Template Name: Rubick - HTML Admin Dashboard Template
-->
<html lang="en" class="light">
<!-- BEGIN: Head -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Rubick admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Rubick Admin Template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="LEFT4CODE">
    <title>
        
        Liste des transferts nationaux
       
    </title>
    <!-- BEGIN: CSS Assets-->
    <style media="all">
        @page {
            padding: 0;
            margin: 20px;
        }

        #emp {
            font-display: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            widows: 100%;
        }

        #emp,
        td,
        #emp,
        th {
            border: 0.5px solid #ddd;
            padding: 0.5px;
        }

        #emp tr:nth-child(even) {
            background-color: #0dfdfd;
        }

        #emp,
        th {
            padding-top: 2px;
            padding-bottom: 2px;
            text-align: left;
            background-color: blue;
            color: white
        }


        body {
            font-size: 1rem;
            font-weight: normal;
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
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold
        }
    </style>
    <!-- END: CSS Assets-->
</head>
<!-- END: Head -->

<body class="py-5">

    <div class="flex">
        <div class="intro-y grid grid-cols-12 gap-5 mt-5">
            <div class="col-span-12 sm:col-span-3 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_3">
                <div>
                    <img src="{{ asset_url('dist/images/logo_robus.png') }}" height="30"
                                                style="display:inline-block;margin-bottom:10px">
                </div>
            </div>
            <div class="col-span-12 sm:col-span-3 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_3">
              
                    <div class="font-medium text-historique mt-6">Email :  
                        {{ env('EMAIL_USER') }}</div>
            </div>
            <div class="col-span-12 sm:col-span-3 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table_factures">
              
                <div class="font-medium text-historique mt-6">Telephone :   {{ env('PHONE_USE') }}</div>
               
            </div>
        </div>

        <!-- BEGIN: Content -->
        <div class="content">

            <div class="intro-y grid grid-cols-12 gap-5 mt-5">
                <div class="intro-y col-span-12 lg:col-span-12">
                    <div class="intro-y block sm:flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">
                            Les transferts  de la date du {{ $searchDateBegin  }}  au  {{ $searchDateEnd }}
                           
                        </h2>
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-12 gap-6 mt-5">

                <div class="intro-y box px-3 pt-1 mt-6 col-span-12 overflow-auto lg:overflow-visible">
                    <div class="grid grid-cols-12 gap-6  mt-6">
                        <div class="intro-y box col-span-12">
                            <div class=" items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                                <h2 class="font-medium text-base mr-auto">
                                    Informations
                                </h2>
                            </div>
                            <div class="tiny-slider py-5">
                                <div class="px-5">
                                    <div class="overflow-x-auto" id="#emp">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>

                                                    <th>
                                                        Nombre d'attentes
                                                    </th>
                                                    <th>
                                                        Nombre de succès

                                                    </th>
                                                    <th>
                                                        Nombre d'échecs
                                                    </th>

                                                    <th>
                                                       Montant
                                                    </th>
                                                   
                                                </tr>
                                            </thead>
                                            <tbody>
                                                    <tr>

                                                        <td>
                                                            <p href="" class="font-medium whitespace-nowrap">
                                                                {{ $account_penddings ?? null }}</p>
                                                        </td>
                                                        <td>
                                                            <p href="" class="font-medium whitespace-nowrap">
                                                                {{ $account_success ?? null }} </p>
                                                        </td>
                                                        <td>
                                                            <p href="" class="font-medium whitespace-nowrap">
                                                                {{ $account_errors ?? null }}</p>
                                                        </td>
                                                        <td>
                                                            <p href="" class="font-medium whitespace-nowrap">
                                                                {{ $amount_total ?? null }}</p>
                                                        </td>

                                                    </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- BEGIN: Pagination -->

                        <!-- END: Pagination -->

                    </div>


                </div>

                <div class="intro-y box px-3 pt-1 mt-6 col-span-12 overflow-auto lg:overflow-visible">
                    <div class="grid grid-cols-12 gap-6  mt-6">
                        <div class="intro-y box col-span-12">
                            <div class=" items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                                <h2 class="font-medium text-base mr-auto">
                                    Liste des transferts d'aujourd'hui
                                </h2>
                            </div>
                            <div class="tiny-slider py-5">
                                <div class="px-5">
                                    <div class="overflow-x-auto" id="#emp">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>

                                                    <th>
                                                        Opérateur
                                                    </th>
                                                    <th>
                                                        Expéditeur
                                                    </th>
                                                    <th>
                                                        Montant demandé
                                                    </th>
                                                    <th>
                                                        Envoyer le
                                                    </th>
                                                    <th>
                                                        Numero
                                                    </th>
                                                    <th>
                                                        Traité le
                                                    </th>

                                                    <th>
                                                        Statut </th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- @if ($bank_historicals->count() > 0) --}}
                                                @foreach ($walletInters as $key => $item)
                                                    <tr>
                                                        <td>
                                                            <p href="" class="font-medium whitespace-nowrap">
                                                                {{ $item->provider->name ?? null }}
                                                            </p>

                                                        </td>
                                                        <td class="w-50">
                                                            <div class="mt-0.5 mb-2">
                                                                <div class="mt-0.5">
                                                                    @if ($item->wallet->sidUser->is_under_distributer == 1)
                                                                    <div
                                                                        class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium">
                                                                        <div class="fc-event-title-container">
                                                                            <div class="fc-event-title fc-sticky">
                                                                                Sous-distributeur</div>
                                                                        </div>
                                                                    </div>
                                                                @elseif($item->wallet->sidUser->is_custumers == 1)
                                                                    <div
                                                                        class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium">
                                                                        <div class="fc-event-title-container">
                                                                            <div class="fc-event-title fc-sticky">
                                                                                Client</div>
                                                                        </div>
                                                                    </div>
                                                                @elseif($item->wallet->sidUser->is_supervisor == 1)
                                                                    <div
                                                                        class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium">
                                                                        <div class="fc-event-title-container">
                                                                            <div class="fc-event-title fc-sticky">
                                                                                SUPERVISUER
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @elseif($item->wallet->sidUser->is_master == 1)
                                                                    <div
                                                                        class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium">
                                                                        <div class="fc-event-title-container">
                                                                            <div class="fc-event-title fc-sticky">
                                                                                MASTER</div>
                                                                        </div>
                                                                    </div>
                                                                @elseif($item->wallet->sidUser->is_com_zone == 1)
                                                                    <div
                                                                        class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium">
                                                                        <div class="fc-event-title-container">
                                                                            <div class="fc-event-title fc-sticky">
                                                                                COM ZONE</div>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div
                                                                        class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium">
                                                                        <div class="fc-event-title-container">
                                                                            <div class="fc-event-title fc-sticky">
                                                                                Admin</div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                    {{ $item->wallet->sidUser->firstname ?? null }}
                                                                    {{ $item->wallet->sidUser->lastname ?? null }}
                                                                </div>
                                                            </div>
                                                            <div class="mt-0.5">
                                                                <span
                                                                    class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                    {{ $item->wallet->sidUser->country->name ?? null }}
                                                                    ville :
                                                                    {{ $item->wallet->sidUser->city->name ?? null }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <p class="mb-3">
                                                                Montant envoyé :
                                                            </p>
                                                            {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->currencie->code ?? null }}
                                                            <p class="mt-3">
                                                                Montant reçu
                                                            </p>
                                                            <span
                                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                {{ number_format($item->amount_received, 2, ',', ' ') ?? null }}
                                                                {{ $item->wallet->currencie->code ?? null }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <p href="" class="font-medium whitespace-nowrap">
                                                                {{ $item->created_at ?? null }}</p>
                                                        </td>
                                                        <td>
                                                            @php
                                                            $wallet_info = \App\Models\Wallets\Wallet::where('wallet_id', $item->num_wallet)->first();
                                                        @endphp
                                                        <p href="" class="font-medium whitespace-nowrap">
                                                            {{ $item->phone ?? null }}
                                                            {{ $item->num_wallet ?? null }}</p>
                                                        <p href="" class="font-medium whitespace-nowrap">
                                                            {{ $wallet_info->sidUser->firstname ?? null }}
                                                            {{ $wallet_info->sidUser->lastname ?? null }}
                                                        </p>
                                                           
                                                        </td>
                                                        <td>
                                                            <p href="" class="font-medium whitespace-nowrap">
                                                                {{ $item->transaction->created_at ?? null }}</p>
                                                        </td>
                                                        <td>
                                                            @if ($item->status == 'PENDING')
                                                                <div class="flex items-center justify-center">
                                                                    {{-- <span
                                                                        class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"> --}}
                                                                    En-attente
                                                                    {{-- </span> --}}

                                                                </div>
                                                            @elseif ($item->status == 'ERROR')
                                                                <div class="flex items-center justify-center">
                                                                    <div class="flex items-center justify-center">
                                                                        {{-- <span
                                                                            class="alert alert-danger  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"> --}}
                                                                        Refusé
                                                                        {{-- </span> --}}

                                                                    </div>

                                                                </div>
                                                                <div class="flex items-center justify-center">
                                                                    {{-- <span
                                                                        class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                        style="color: white;"> --}}
                                                                    Traiter
                                                                    {{-- </span> --}}

                                                                </div>
                                                            @else
                                                                <div class="flex items-center justify-center">
                                                                    <div class="flex items-center justify-center">
                                                                        {{-- <span
                                                                            class="alert  alert-success inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                            style="color: white;"> --}}
                                                                        Accepté
                                                                        {{-- </span> --}}


                                                                    </div>

                                                                </div>
                                                                <div class="flex items-center justify-center">
                                                                    {{-- <span
                                                                        class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                        style="color: white;"> --}}
                                                                    Traiter
                                                                    {{-- </span> --}}

                                                                </div>
                                                            @endif
                                                        </td>

                                                    </tr>
                                                @endforeach
                                                {{-- @else
                                            <tr>
                                                <td colspan="7" class="text-center">Aucune donnée pour le
                                                    moment</td>
                                            </tr>
                                        @endif --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- BEGIN: Pagination -->

                        <!-- END: Pagination -->

                    </div>


                </div>
            </div>
        </div>
        <!-- END: Content -->
    </div>

    <!-- BEGIN: JS Assets-->


</body>

</html>
