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
        @if (Auth::guard('web')->user()->is_admin == 1)
        Listes des Paiements 
        @else
        Paiements validés par {{ Auth::guard('web')->user()->lastname ?? null }}
        {{ Auth::guard('web')->user()->firstname ?? null }}
        @endif
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
                            Les Activites des paiements validés par
                            {{ $wallet_id->sidUser->lastname ?? null }}
                            {{ $wallet_id->sidUser->firstname ?? null }}
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
                                                        Montant total
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
                                                                {{ formatDisplayAmount($amount_total ?? null) }}  {{ $wallet_id->currencie->code ?? null }}
                                                            </p>
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
                                    Liste des paiements d'aujourd'hui
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
                                                        Numero
                                                    </th>
                                                    <th>
                                                        Envoyer le
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
                                                @foreach ($bank_historicals as $key => $item)
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
                                                            <div class="text-slate-500  mt-0.5">
                                                                <span
                                                                    class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                    {{ $item->wallet->sidUser->country->name ?? null }}
                                                                    ville :
                                                                    {{ $item->wallet->sidUser->city->name ?? null }}
                                                                </span>
                                                            </div>

                                                        </td>
                                                        <td>
                                                            <p href="" class="font-medium whitespace-nowrap">
                                                                {{ formatDisplayAmount($item->amount ?? null) }}
                                                                {{ $item->wallet->currencie->code ?? null }}
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <p class="font-medium whitespace-nowrap">
                                                                @if ($item->factureChoix_type == 1)
                                                                    <div class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                        <div class="fc-event-title-container"
                                                                           >
                                                                            <div class="fc-event-title fc-sticky">
                                                                                Facture
                                                                                prépayée</div>
                                                                        </div>
                                                                    </div>
                                                                    {{ $item->id_invoice ?? null }}
                                                                @elseif($item->factureChoix_type == 2)
                                                                    <div
                                                                        class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                        <div class="fc-event-title-container"
                                                                           >
                                                                            <div class="fc-event-title fc-sticky">
                                                                                Facture
                                                                                normale</div>
                                                                        </div>
                                                                    </div>
                                                                    {{ $item->id_invoice ?? null }}
                                                                @elseif($item->paiementChoix_phone == 1)
                                                                    <div class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                        <div class="fc-event-title-container"
                                                                           >
                                                                            <div class="fc-event-title fc-sticky">
      
                                                                                Numero de telephone</div>
                                                                        </div>
                                                                    </div>
      
                                                                    {{ $item->number_phone ?? null }}
                                                                @elseif($item->paiementChoix_phone == 2)
                                                                    <div class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                        <div class="fc-event-title-container"
                                                                           >
                                                                            <div class="fc-event-title fc-sticky">
                                                                                Numero du decodeur
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    {{ ($item->number_decoder) }}
      
                                                                @else
                                                                    {{ $item->number_contract ?? null }}
                                                                @endif
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <p href="" class="font-medium whitespace-nowrap">
                                                                {{ $item->created_at ?? null }}</p>
                                                        </td>
                                                        <td>
                                                            <p href="" class="font-medium whitespace-nowrap">
                                                                {{ $item->payment->created_at ?? null }}</p>
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
                                                                       > --}}
                                                                    Traiter
                                                                    {{-- </span> --}}

                                                                </div>
                                                            @else
                                                                <div class="flex items-center justify-center">
                                                                    <div class="flex items-center justify-center">
                                                                        {{-- <span
                                                                            class="alert  alert-success inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                           > --}}
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
