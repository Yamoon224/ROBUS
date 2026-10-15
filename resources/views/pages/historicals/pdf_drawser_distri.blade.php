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
            Virement bancaires
        @else
            Virement bancaires {{ Auth::guard('web')->user()->lastname ?? null }}
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
                            @if (Auth::guard('web')->user()->is_admin == 1)
                                Toutes les Activites des commissions remontées d'aujourd'hui
                            @else
                                Les Activites des commissions remontées d'aujourd'hui de
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
                                    Liste des virements d'aujourd'hui
                                </h2>
                            </div>
                            <div class="tiny-slider py-5">
                                <div class="px-5">
                                    <div class="overflow-x-auto" id="#emp">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>

                                                    <th >
                                                        Date 
                                                    </th>
                                                    <th >
                                                        Nom 
                                                    </th>
                                                    <th >
                                                       Prénom(s)
                                                    </th>
                                                    <th >
                                                        Pays
                                                     </th>
                                                    <th>
                                                        Montant 
                                                    </th>
                                                   

                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- @if ($bank_historicals->count() > 0) --}}
                                                @foreach ($bank_historicals as $key => $item)
                                                <tr class="intro-x">
                                                    <td class="w-40">
                                                        <p href="" class="font-medium whitespace-nowrap">
                                                            {{ $item->created_at ?? null }}</p>
                                                    </td>

                                                    <td class="w-40">
                                                        <p href="" class="font-medium whitespace-nowrap">
                                                            {{ $item->sidUser->firstname ?? null }}
                                                        </p>

                                                    </td>
                                                    <td class="w-40">
                                                        <p href="" class="font-medium whitespace-nowrap">
                                                            {{ $item->sidUser->lastname ?? null }}
                                                        </p>

                                                    </td>
                                                   
                                                    <td class="w-40">
                                                        <div class="text-slate-500  mt-0.5">
                                                            <span
                                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                {{ $item->sidUser->country->name ?? null }}
                                                                ville :
                                                                {{ $item->sidUser->city->name ?? null }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td class="w-40">
                                                        <p href="" class="font-medium whitespace-nowrap">
                                                            {{ formatDisplayAmount($item->amount_with_draw ?? null) }} {{ $item->sidUser->wallet->currencie->name ?? null }}</p>
                                                    </td>
                                                </tr>
                                                @endforeach
                                             
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
