@extends('layouts.master')

@section('title', 'Liste des rechargement')

@section('content')

    <div class="intro-y grid grid-cols-12 gap-5 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <div class="intro-y block sm:flex items-center h-10">
                <h2 class="text-lg font-medium truncate mr-5"> Les Activites des {{ $name_transaction ?? null }} sous
                    distributeurs {{ $countrie->name ?? null }}</h2>
                {{-- <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">

                    <a class="ml-3  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary mr-1 mb-2"
                        href="{{ route('payments.historical.reporting_payment_historical') }}" style="color: white;">
                        <i data-lucide="file-text" class="hidden sm:block w-4 h-4 mr-2"></i> Telecharger le rapport
                    </a>
                </div> --}}
            </div>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table_factures">
        <div>

            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-trending-down font-medium-5">
                <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                <polyline points="17 18 23 18 23 12"></polyline>
            </svg>
        </div>
        <div class="font-medium text-historique mt-6">Total payements commissions {{ $name_transaction ?? null }}</div>
        <div class="text-slate-500 mt-3"> {{ $valeur_payments ?? null }} {{ $countrie->currencie->code ?? null }}
            {{-- {{ $usersCountry }} --}}
        </div>
    </div>


    <div class="grid grid-cols-12 gap-6 mt-5">


        <div class="intro-y col-span-12 grid grid-cols-12 gap-5 items-center mt-2">
            {{-- <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
            <div class="w-56 relative text-slate-500 mt-3">
                <label for="">Date de debut</label>

                <input type="date" class="form-control  w-56 box pr-10 mt -1" value="{{ $startDate }}"
                    wire:model="startDate" placeholder="Rechercher par date les commandes" id="start_date"
                    aria-label="Date">

            </div>
        </div>
        <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
            <div class="w-56 relative text-slate-500 mt-3">
                <label for="">Date de fin</label>
                <input type="date" class="form-control  w-56 box pr-10 mt-1" value="{{ $endDate }}"
                    wire:model="endDate" placeholder="Rechercher par date" id="end_date" aria-label="Date">

            </div>

        </div> --}}


        </div>

        <div class="intro-y box px-3 pt-1 mt-6 col-span-12 overflow-auto lg:overflow-visible">
            <div class="grid grid-cols-12 gap-6  mt-6">
                <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                    <div class=" items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Liste des {{ $name_transaction ?? null }} effectués par les sous-distributeurs pour le pays
                            {{ $countrie->name ?? null }}
                        </h2>
                    </div>
                    <div class="tiny-slider py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table id="table_orders" class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>

                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Opérateur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Expéditeur
                                            </th>

                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Montant demandé
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Info {{ $name_transaction ?? null }}
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Commissions </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($wallet_payments->count() > 0)
                                            @foreach ($wallet_payments as $key => $item)
                                                <tr class="intro-x">
                                                    <td class="text-center w-40">
                                                        @include('components.infoTypeDjamo')

                                                        <div
                                                            class="px-3 py-2 text-dark btn-pink dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                            <div class="fc-event-title-container" style="color: white;">
                                                                <div class="fc-event-title fc-sticky">
                                                                    {{ $item->provider->name ?? null }}

                                                                </div>
                                                            </div>
                                                        </div>

                                                    </td>
                                                    <td class="user-info-card">
                                                        @include('components.infoUser')

                                                    </td>
                                                    <td>

                                                        @include('components.walletBalance')


                                                    </td>
                                                    <td>
                                                        <div class="mb-3">
                                                            @include('components.info_update')
                                                        </div>
                                                    </td>

                                                    <td class="w-40">
                                                        @include('components.info_commission')
                                                    </td>


                                                    <td class="w-40">
                                                        @include('components.info_status')
                                                    </td>

                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">Aucune donnée pour le
                                                    moment</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN: Pagination -->
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6 mb-6">
                    <nav class="w-full sm:w-auto sm:mr-auto">
                        <ul class="pagination">
                            {{ $wallet_payments->links() }}
                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->

            </div>


        </div>
    </div>
@endsection
