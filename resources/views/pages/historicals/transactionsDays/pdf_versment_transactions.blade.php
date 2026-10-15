<!DOCTYPE html>
<!--
Template Name: Rubick - HTML Admin Dashboard Template
-->
<html lang="en" class="light">
<!-- BEGIN: Head -->

<head>
    <meta charset="utf-8">
    <link href="{{ asset('dist/images/logo.svg') }}" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Rubick admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Rubick Admin Template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="LEFT4CODE">
    <title>

        Liste des transactions des versements
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

        .full-calendar .fc-event-title-container {
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
            font-size: 0.75rem;
            line-height: 1rem;
        }

        .transaction-amounts {
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .amount-section {
            margin-bottom: 1.25rem;
            /* padding-bottom: 1.25rem; */
            border-bottom: 1px solid #f1f5f9;
        }

        .amount-section:last-of-type {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .amount-label {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .amount-label::before {
            content: '';
            width: 4px;
            height: 4px;
            background: #94a3b8;
            border-radius: 50%;
        }



        .amount-currency {
            color: #475569;
            font-size: 1rem;
            font-weight: 600;
            margin-left: 0.25rem;
        }

        /* ===== STYLE INFORMATIONS UTILISATEUR ===== */
        .user-info-card {
            padding: 20px;
            /* 1.25rem */
            background-color: #f1f5f9;
            /* fallback pour gradient */
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            /* box-shadow ignoré par DomPDF, on peut utiliser une bordure plus foncée si besoin */
        }

        .user-badges-container {
            /* Flex simplifié */
            display: block;
            margin-bottom: 16px;
            /* 1rem */
        }

        .user-badge {
            display: inline-block;
            /* flex non fiable dans DomPDF */
            padding: 8px 12px;
            /* 0.5rem 0.75rem */
            border-radius: 8px;
            font-weight: 600;
            font-size: 12px;
            /* 0.8rem */
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid;
            position: relative;
            overflow: hidden;
            color: white;
            background-color: #777;
            /* fallback si gradient non appliqué */
        }

        .user-badge--distributor {
            background-color: #7c3aed;
            /* fallback */
            border-color: #8b5cf6;
        }

        .user-badge--customer {
            background-color: #059669;
            /* fallback */
            border-color: #10b981;
        }

        .user-badge--admin {
            background-color: #dc2626;
            /* fallback */
            border-color: #ef4444;
        }

        .transaction-alert {
            display: block;
            /* flex remplacé */
            padding: 8px 16px;
            /* 0.5rem 1rem */
            margin-bottom: 16px;
            background-color: #e0f2fe;
            /* fallback pour gradient */
            border: 1px solid #7dd3fc;
            border-left: 4px solid #0ea5e9;
            border-radius: 12px;
            /* box-shadow ignoré par DomPDF */
            position: relative;
            overflow: hidden;
        }


        .transaction-alert__icon svg {
            width: 20px;
            height: 20px;
            color: #0ea5e9;
        }

        .transaction-alert__content {
            display: inline-block;
            vertical-align: middle;
        }

        .transaction-alert__text {
            color: #0369a1;
            font-weight: 500;
            font-size: 14px;
            /* text-shadow ignoré */
        }

        .pdf-btn-pink {
            display: block;
            padding: 8px 12px;
            /* équivalent px-3 py-2 */
            background-color: #ec4899;
            /* couleur rose */
            color: black;
            /* texte */
            border-radius: 8px;
            /* arrondi */
            font-weight: 500;
            /* font-medium */
            margin-bottom: 8px;
            /* mb-2 */
            text-align: center;
            text-decoration: none;
        }

        /* Texte à l'intérieur */
        .pdf-btn-pink .fc-event-title-container {
            color: black;
            /* texte blanc */
        }

        .pdf-btn-pink .fc-event-title {
            font-weight: bold;
            font-size: 14px;
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

                <div class="font-medium text-historique mt-6">Telephone : {{ env('PHONE_USE') }}</div>

            </div>
        </div>

        <!-- BEGIN: Content -->
        <div class="content">

            <div class="intro-y grid grid-cols-12 gap-5 mt-5">
                <div class="intro-y col-span-12 lg:col-span-12">
                    <div class="intro-y block sm:flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">
                            Les transactions versements de la date du {{ $searchDateBegin }} au {{ $searchDateEnd }}
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
                                    Informations sur les transactions versements Money
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
                                                            {{ formatDisplayAmount($amount_total ?? null) }}</p>
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
                                    Liste des transactions des versements
                                </h2>
                            </div>
                            <div class="tiny-slider py-5">
                                <div class="px-5">
                                    <div class="overflow-x-auto" id="#emp">
                                        @include('components.reports.tableVerment')

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
