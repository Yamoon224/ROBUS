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
          Validations Transferts internationnal distributeur
        @else
            Transfert inter {{ Auth::guard('web')->user()->lastname ?? null }}
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
                    <img src="{{ asset('public/dist/images/login.jpg') }}" height="30"
                        style="display:inline-block;margin-bottom:10px">
                </div>
            </div>
            <div class="col-span-12 sm:col-span-3 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_3">

                <div class="font-medium text-historique mt-6">Email :
                    {{ env('EMAIL_USER') }}</div>
            </div>
            <div class="col-span-12 sm:col-span-3 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table_factures">

                <div class="font-medium text-historique mt-6">Telephone : {{ env('PHONE_USE') }}</div>

            </div>
        </div>

        <!-- BEGIN: Content -->
        <div class="content">

            <div class="intro-y grid grid-cols-12 gap-5 mt-5">
                <div class="intro-y col-span-12 lg:col-span-12">
                    <div class="intro-y block sm:flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">
                            @if (Auth::guard('web')->user()->is_admin == 1)
                                Les Activites des validations transferts inter d'aujourd'hui
                            @else
                                Les Activites des transferts validationsq inter d'aujourd'hui de
                                {{ Auth::guard('web')->user()->lastname ?? null }}
                                {{ Auth::guard('web')->user()->firstname ?? null }}
                            @endif
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
                                    Liste des transferts inter d'aujourd'hui
                                </h2>
                            </div>
                            <div class="tiny-slider py-5">
                                <div class="px-5">
                                    <div class="overflow-x-auto" id="#emp">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>

                                                    <th >Date</th>
                                                    <th>Montant</th>
                                                    <th>Nom et prenom </th>
                                                    <th>Statut</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($bank_historicals as $key => $item)
                                                    <tr>
                                                        <td>
                                                            <p href="" class="font-medium whitespace-nowrap">
                                                                {{ $item->created_at ?? null }}
                                                            </p>

                                                        </td>
                                                       <td class="w-56">
                                                            <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                                <div class="text-slate-300 text-xs  mt-0.5"> {{ $item->amount_distributer ?? null }}  {{ $item->sidUser->wallet->currencie->code ?? null }}
                                                                </div>
                                                            </div>
                                                            <div class="text-slate-500  mt-0.5">
                                                                <span
                                                                    class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                    {{ $item->description_distributer ?? null }}
                                                                </span>
                                                            </div>
                        
                                                        </td>
                                                        <td class="w-40">
                                                            <div class="mt-0.5 mb-2">
                                                                <div class="mt-0.5">
                                                                    <div
                                                                    class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium">
                                                                    <div class="fc-event-title-container">
                                                                        <div class="fc-event-title fc-sticky">
                                                                            Sous-distributeur</div>
                                                                    </div>
                                                                </div>
                                                                    {{ $item->sidUser->firstname ?? null }}
                                                                    {{ $item->sidUser->lastname ?? null }}
                                                                </div>
                                                            </div>
                                                            <div class="mt-0.5">
                                                                <span
                                                                    class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                    {{ $item->sidUser->country->name ?? null }} ville :
                                                                    {{ $item->sidUser->city->name ?? null }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="w-40">
                                                            @if ($item->walletBank->status == 'PENDING')
                                                                <div class="flex items-center justify-center text-pending">
                                                                        En-attente

                                                                </div>
                                                            @elseif ($item->walletBank->status == 'ERROR')
                                                                <div
                                                                    class="flex items-center justify-center text-danger">
                                                                    <div
                                                                        class="flex items-center justify-center text-pending">
                                                                            Refusé
                                                                       

                                                                    </div>

                                                                </div>
                                                                <div
                                                                    class="flex items-center justify-center text-pending">
                                                                  
                                                                        Traiter
                                                                   

                                                                </div>
                                                            @elseif($item->walletBank->status == 'REFUND')
                                                                <div
                                                                    class="flex items-center justify-center text-primary">
                                                                    <div
                                                                        class="flex items-center justify-center text-pending">
                                                                       
                                                                            Remboursé
                                                                       

                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="flex items-center justify-center text-pending">
                                                                
                                                                        Traiter
                                                                   

                                                                </div>
                                                            @else
                                                                <div
                                                                    class="flex items-center justify-center text-success">
                                                                    <div
                                                                        class="flex items-center justify-center text-pending">
                                                                      
                                                                            Accepté
                                                                       


                                                                    </div>

                                                                </div>
                                                                <div
                                                                    class="flex items-center justify-center text-pending">
                                                                   
                                                                        Traiter
                                                                   

                                                                </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                              
                                            </tbody>
                                        </table>                                    </div>
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
